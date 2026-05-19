<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Show the checkout page
     */
    public function create()
    {
        // Redirect if cart is empty
        if (!session()->has('cart') || count(session('cart')) == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Defensive session fallback for order_type
        if (!session()->has('order_type')) {
            if (session()->has('table_id') && session()->has('table_number')) {
                session(['order_type' => 'dine_in']);
            } else {
                session(['order_type' => 'takeaway']);
            }
        }

        $cart = session()->get('cart');
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    /**
     * Store the order
     */
    public function store(Request $request)
    {
        $orderType = session('order_type', 'takeaway');
        
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ];

        if ($orderType === 'delivery') {
            $rules['address'] = 'required|string|max:500';
            $rules['maps_link'] = 'nullable|url|max:1000';
            $rules['payment_method'] = 'required|in:qris';
        } else {
            $rules['payment_method'] = 'required|in:cash,qris';
        }

        $request->validate($rules);

        $cart = session()->get('cart');
        
        if (!$cart || count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Determine order type, table_id, maps_link and address based on specs
        $tableId = null;
        $mapsLink = null;
        $address = '-';

        if ($orderType === 'dine_in') {
            $tableId = session('table_id');
        } elseif ($orderType === 'takeaway') {
            // Keep default null/'-'
        } else {
            // Delivery
            $address = $request->address;
            $mapsLink = $request->maps_link;
        }

        DB::beginTransaction();

        try {
            $trackingToken = \Illuminate\Support\Str::uuid()->toString();
            $dbPaymentMethod = ($request->payment_method === 'cash') ? 'cash' : 'online';
            $paymentStatus = ($request->payment_method === 'cash') ? 'pending_cash' : 'unpaid';

            // Create Order
            $order = Order::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $address,
                'notes' => $request->notes,
                'total_price' => $total,
                'status' => 'pending',
                'user_id' => Auth::check() ? Auth::id() : null,
                'order_type' => $orderType,
                'table_id' => $tableId,
                'maps_link' => $mapsLink,
                'tracking_token' => $trackingToken,
                'payment_method' => $dbPaymentMethod,
                'payment_status' => $paymentStatus,
            ]);

            // Create Order Items
            foreach ($cart as $id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            // If online/qris, generate Midtrans Snap transaction
            if ($dbPaymentMethod === 'online') {
                $this->initMidtrans();

                $midtransOrderId = 'ORDER-' . $order->id . '-' . time();
                
                $params = [
                    'transaction_details' => [
                        'order_id' => $midtransOrderId,
                        'gross_amount' => (int) $total,
                    ],
                    'customer_details' => [
                        'first_name' => $request->name,
                        'phone' => $request->phone,
                    ],
                    'enabled_payments' => ['qris', 'gopay', 'shopeepay'],
                ];

                // Generate Snap Token
                $snapToken = app()->environment('testing') ? 'test-snap-token' : \Midtrans\Snap::getSnapToken($params);

                // Update Order with token and midtrans id
                $order->update([
                    'midtrans_order_id' => $midtransOrderId,
                    'snap_token' => $snapToken,
                ]);
            }

            // Clear Cart
            session()->forget('cart');
            
            DB::commit();

            if ($dbPaymentMethod === 'online') {
                return redirect()->route('checkout.payment', ['tracking_token' => $order->tracking_token]);
            }

            return redirect()->route('order.track', ['tracking_token' => $order->tracking_token])->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Checkout payment setup failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Initialize Midtrans Configuration
     */
    private function initMidtrans()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Show dedicated premium payment waiting page
     */
    public function payment($tracking_token)
    {
        if (empty($tracking_token)) {
            abort(404);
        }

        $order = Order::with('items.product', 'table')
            ->where('tracking_token', $tracking_token)
            ->first();

        if (!$order) {
            abort(404);
        }

        // If it's a cash order, or already paid online, direct to tracking immediately
        if ($order->payment_method !== 'online' || $order->payment_status === 'paid') {
            return redirect()->route('order.track', $order->tracking_token);
        }

        return view('checkout.payment', compact('order'));
    }

    /**
     * Handle secure Midtrans Webhook Callback
     */
    public function callback(Request $request)
    {
        Log::info('Midtrans Webhook Received:', $request->all());

        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');

        $serverKey = config('midtrans.server_key');

        // Verify signature key authenticity
        $computedSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $computedSignature) {
            Log::warning("Midtrans Webhook signature verification failed! Computed: {$computedSignature}, Received: {$signatureKey}");
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Extract internal database order ID from Midtrans order_id
        $parts = explode('-', $orderId);
        $dbOrderId = isset($parts[1]) ? (int) $parts[1] : null;

        if (!$dbOrderId) {
            Log::error("Failed to parse internal Order ID from Midtrans Order ID: {$orderId}");
            return response()->json(['message' => 'Invalid order format'], 400);
        }

        $order = Order::find($dbOrderId);

        if (!$order) {
            Log::error("Order #{$dbOrderId} not found in database for Midtrans callback.");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Idempotent safeguard: skip if already paid
        if ($order->payment_status === 'paid') {
            Log::info("Order #{$order->id} is already marked as Paid. Skipping webhook updates (Idempotency Safe).");
            return response()->json(['message' => 'Order already processed (idempotent)'], 200);
        }

        $transactionStatus = $request->input('transaction_status');

        // Map status safely
        $mappedStatus = 'unpaid';
        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            $mappedStatus = 'paid';
        } elseif ($transactionStatus === 'pending') {
            $mappedStatus = 'pending';
        } elseif ($transactionStatus === 'expire') {
            $mappedStatus = 'expired';
        } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'deny') {
            $mappedStatus = 'failed';
        }

        // Update ONLY payment fields
        $updateData = ['payment_status' => $mappedStatus];
        if ($mappedStatus === 'paid') {
            $updateData['paid_at'] = now();
        }

        $order->update($updateData);

        Log::info("Order #{$order->id} payment status successfully updated to {$mappedStatus} by Midtrans Webhook.");

        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }

    /**
     * Show success page
     */
    public function success()
    {
        return view('checkout.success');
    }

    /**
     * Show customer order tracking page
     */
    public function track($tracking_token)
    {
        if (empty($tracking_token)) {
            abort(404);
        }

        $order = Order::with('items.product', 'table')
            ->where('tracking_token', $tracking_token)
            ->first();

        if (!$order) {
            abort(404);
        }

        return view('checkout.track', compact('order'));
    }
}
