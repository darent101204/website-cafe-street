<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::latest()->paginate(10);

        // ── Analytics KPIs (read-only, additive) ──────────────────────────
        $totalOrders      = Order::count();
        $totalRevenue     = Order::where('payment_status', 'paid')->sum('total_price');
        $todayOrders      = Order::whereDate('created_at', today())->count();
        $todayRevenue     = Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total_price');
        $pendingOrders    = Order::where('status', 'pending')->count();
        $completedOrders  = Order::where('status', 'completed')->count();
        $paidOrders       = Order::where('payment_status', 'paid')->count();
        $unpaidOrders     = Order::whereIn('payment_status', ['unpaid', 'pending_cash'])->count();
        $qrisOrders       = Order::where('payment_method', 'online')->count();
        $cashOrders       = Order::where('payment_method', 'cash')->count();
        $dineInOrders     = Order::where('order_type', 'dine_in')->count();
        $takeawayOrders   = Order::where('order_type', 'takeaway')->count();
        $deliveryOrders   = Order::where('order_type', 'delivery')->count();
        // ──────────────────────────────────────────────────────────────────

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders', 'totalRevenue', 'todayOrders', 'todayRevenue',
            'pendingOrders', 'completedOrders', 'paidOrders', 'unpaidOrders',
            'qrisOrders', 'cashOrders', 'dineInOrders', 'takeawayOrders', 'deliveryOrders'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the status of the order.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,processed',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated successfully!');
    }

    /**
     * Update the payment status of the order.
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending_cash,unpaid,paid,failed,expired',
        ]);

        $updateData = ['payment_status' => $request->payment_status];
        if ($request->payment_status === 'paid') {
            $updateData['paid_at'] = now();
        }

        $order->update($updateData);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Payment status updated successfully!');
    }
}
