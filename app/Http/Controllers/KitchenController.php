<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    /**
     * Display the Kanban Kitchen Dashboard.
     */
    public function index()
    {
        // Load active orders grouped by operational status and filtered by payment visibility rules
        $orders = Order::with('items.product', 'table')
            ->whereIn('status', ['pending', 'preparing', 'ready', 'completed'])
            ->where(function ($query) {
                // Show cash orders that are pending cash payment confirmation
                $query->where(function ($q) {
                    $q->where('payment_method', 'cash')
                      ->where('payment_status', 'pending_cash');
                })
                // OR show any order that is paid (e.g. paid QRIS or cash completed)
                ->orWhere('payment_status', 'paid')
                // OR show legacy orders for backward compatibility
                ->orWhereNull('payment_method');
            })
            ->latest()
            ->get();

        $pending = $orders->where('status', 'pending');
        $preparing = $orders->where('status', 'preparing');
        $ready = $orders->where('status', 'ready');
        
        // Limit recent completed orders to the latest 15 to prevent dashboard clutter
        $completed = $orders->where('status', 'completed')->take(15);

        return view('admin.kitchen.index', compact('pending', 'preparing', 'ready', 'completed'));
    }

    /**
     * Update the operational kitchen status of an order with strict sequential transition guards.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $currentStatus = $order->status;
        $targetStatus = $request->status;

        // Strict sequential transition guard mapping
        $validTransitions = [
            'pending' => 'preparing',
            'preparing' => 'ready',
            'ready' => 'completed',
        ];

        // Validate sequential flow
        if (!isset($validTransitions[$currentStatus]) || $validTransitions[$currentStatus] !== $targetStatus) {
            return redirect()->back()->with('error', sprintf(
                'Invalid transition! Order #%d is currently "%s" and cannot be transitioned directly to "%s".',
                $order->id,
                ucfirst($currentStatus),
                ucfirst($targetStatus)
            ));
        }

        // Apply transition update
        $order->update(['status' => $targetStatus]);

        return redirect()->route('admin.kitchen.index')->with('success', sprintf(
            'Order #%d status updated to "%s" successfully!',
            $order->id,
            ucfirst($targetStatus)
        ));
    }
}
