<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentArchitectureTest extends TestCase
{
    use RefreshDatabase;

    private $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::create([
            'name' => 'Caramel Macchiato',
            'price' => 35,
            'description' => 'Espresso with steamed milk, vanilla, and caramel.',
            'image' => 'img_product.png',
            'rating' => 4.5,
            'category' => 'hot',
            'is_featured' => true,
        ]);
    }

    /**
     * Test 1: Dine In Cash Placement setting status rules
     */
    public function test_dine_in_cash_placement(): void
    {
        $table = Table::create([
            'table_number' => 'A1',
            'qr_token' => 'token-a1',
            'status' => 'available',
        ]);

        $response = $this->withSession([
            'cart' => [
                $this->product->id => [
                    'name' => $this->product->name,
                    'quantity' => 2,
                    'price' => $this->product->price,
                    'image' => $this->product->image,
                ]
            ],
            'order_type' => 'dine_in',
            'table_id' => $table->id,
            'table_number' => $table->table_number,
        ])->post('/checkout', [
            'name' => 'Dine In User',
            'phone' => '08123456789',
            'payment_method' => 'cash',
        ]);

        $order = Order::latest()->first();

        $this->assertNotNull($order);
        $this->assertEquals('dine_in', $order->order_type);
        $this->assertEquals('cash', $order->payment_method);
        $this->assertEquals('pending_cash', $order->payment_status);
        $response->assertRedirect(route('order.track', $order->tracking_token));
    }

    /**
     * Test 2: Delivery QRIS-Only Restriction
     */
    public function test_delivery_qris_restriction_and_unpaid_status(): void
    {
        // Try placing with cash payment method (should fail validation)
        $response1 = $this->withSession([
            'cart' => [
                $this->product->id => [
                    'name' => $this->product->name,
                    'quantity' => 1,
                    'price' => $this->product->price,
                    'image' => $this->product->image,
                ]
            ],
            'order_type' => 'delivery',
        ])->post('/checkout', [
            'name' => 'Delivery User',
            'phone' => '08123456789',
            'address' => '123 Coffee Street',
            'maps_link' => 'https://maps.google.com/123',
            'payment_method' => 'cash',
        ]);

        $response1->assertSessionHasErrors(['payment_method']);

        // Place with QRIS payment method (should succeed)
        $response2 = $this->withSession([
            'cart' => [
                $this->product->id => [
                    'name' => $this->product->name,
                    'quantity' => 1,
                    'price' => $this->product->price,
                    'image' => $this->product->image,
                ]
            ],
            'order_type' => 'delivery',
        ])->post('/checkout', [
            'name' => 'Delivery User',
            'phone' => '08123456789',
            'address' => '123 Coffee Street',
            'maps_link' => 'https://maps.google.com/123',
            'payment_method' => 'qris',
        ]);

        $order = Order::latest()->first();

        $this->assertNotNull($order);
        $this->assertEquals('delivery', $order->order_type);
        $this->assertEquals('qris', $order->payment_method);
        $this->assertEquals('unpaid', $order->payment_status);
        $response2->assertRedirect(route('order.track', $order->tracking_token));
    }

    /**
     * Test 3: Kitchen Dashboard Visibility Rules
     */
    public function test_kitchen_dashboard_visibility_rules(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 1. Cash order with pending_cash -> should show
        $orderCashPending = Order::create([
            'name' => 'Cash Pending',
            'phone' => '081234',
            'address' => '-',
            'total_price' => 35,
            'status' => 'pending',
            'order_type' => 'takeaway',
            'payment_method' => 'cash',
            'payment_status' => 'pending_cash',
            'tracking_token' => 'token-1',
        ]);

        // 2. QRIS order with unpaid -> should hide
        $orderQrisUnpaid = Order::create([
            'name' => 'QRIS Unpaid',
            'phone' => '081234',
            'address' => '-',
            'total_price' => 35,
            'status' => 'pending',
            'order_type' => 'takeaway',
            'payment_method' => 'qris',
            'payment_status' => 'unpaid',
            'tracking_token' => 'token-2',
        ]);

        // 3. QRIS order with paid -> should show
        $orderQrisPaid = Order::create([
            'name' => 'QRIS Paid',
            'phone' => '081234',
            'address' => '-',
            'total_price' => 35,
            'status' => 'pending',
            'order_type' => 'takeaway',
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'tracking_token' => 'token-3',
        ]);

        // 4. Legacy order with null payment_method -> should show
        $orderLegacy = Order::create([
            'name' => 'Legacy Order',
            'phone' => '081234',
            'address' => '-',
            'total_price' => 35,
            'status' => 'pending',
            'order_type' => 'takeaway',
            'payment_method' => null,
            'payment_status' => 'unpaid', // payment_method is null, so it shows
            'tracking_token' => 'token-4',
        ]);

        $response = $this->actingAs($admin)->get('/admin/kitchen');

        $response->assertStatus(200);

        // Retrieve orders passed to view
        $pendingOrders = $response->viewData('pending');

        $this->assertTrue($pendingOrders->contains('id', $orderCashPending->id));
        $this->assertFalse($pendingOrders->contains('id', $orderQrisUnpaid->id));
        $this->assertTrue($pendingOrders->contains('id', $orderQrisPaid->id));
        $this->assertTrue($pendingOrders->contains('id', $orderLegacy->id));
    }

    /**
     * Test 4: Admin Update Payment Status
     */
    public function test_admin_update_payment_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $order = Order::create([
            'name' => 'Order To Pay',
            'phone' => '081234',
            'address' => '-',
            'total_price' => 35,
            'status' => 'pending',
            'order_type' => 'takeaway',
            'payment_method' => 'cash',
            'payment_status' => 'pending_cash',
            'tracking_token' => 'token-pay',
        ]);

        $response = $this->actingAs($admin)->patch("/admin/orders/{$order->id}/payment", [
            'payment_status' => 'paid',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertNotNull($order->paid_at);
    }
}
