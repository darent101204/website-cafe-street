<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        // Determine address content cleanly per approved specs (using neutral "-" placeholder for dine_in/takeaway)
        $address = '-';
        if ($orderType === 'delivery') {
            $address = $request->address;
            if ($request->filled('maps_link')) {
                $address .= "\nMaps Link: " . $request->maps_link;
            }
        }

        DB::beginTransaction();

        try {
            // Create Order
            $order = Order::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $address,
                'notes' => $request->notes,
                'total_price' => $total,
                'status' => 'pending',
                'user_id' => Auth::check() ? Auth::id() : null,
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

            // Clear Cart
            session()->forget('cart');
            
            DB::commit();

            return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Show success page
     */
    public function success()
    {
        return view('checkout.success');
    }
}
