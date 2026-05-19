@extends('layouts.app')

@section('title', 'Admin - Order Management')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2>Order <span style="border-bottom: 3px solid #FF902A;">Management</span></h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.kitchen.index') }}" class="btn btn-warning rounded-5 px-3 me-2" style="background-color: #FF902A; border-color: #FF902A; color: white;">
                <i class="fa fa-mug-hot me-1"></i> Kitchen Dashboard
            </a>
            <span class="badge bg-secondary">Admin Area</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- ANALYTICS KPI DASHBOARD (additive) --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    @push('styles')
    <style>
        /* ── KPI Cards ─────────────────────────────────── */
        .kpi-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px 22px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid #f0f0f0;
            position: relative;
            overflow: hidden;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.10);
        }
        .kpi-card .kpi-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .kpi-card .kpi-label {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #adb5bd;
            margin-bottom: 4px;
        }
        .kpi-card .kpi-value {
            font-size: 1.55rem;
            font-weight: 800;
            color: #1a1a1a;
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .kpi-card .kpi-sub {
            font-size: 0.72rem;
            color: #adb5bd;
            margin-top: 4px;
        }
        .kpi-card .kpi-bg-icon {
            position: absolute;
            right: -8px; bottom: -8px;
            font-size: 4rem;
            opacity: 0.05;
            pointer-events: none;
        }
        /* Accent colours */
        .kpi-orange { border-top: 3px solid #FF902A; }
        .kpi-green  { border-top: 3px solid #198754; }
        .kpi-blue   { border-top: 3px solid #0d6efd; }
        .kpi-teal   { border-top: 3px solid #20c997; }
        .kpi-red    { border-top: 3px solid #dc3545; }
        .kpi-purple { border-top: 3px solid #6f42c1; }
        .kpi-icon-orange { background: #fff3e0; color: #FF902A; }
        .kpi-icon-green  { background: #d1fae5; color: #198754; }
        .kpi-icon-blue   { background: #dbeafe; color: #0d6efd; }
        .kpi-icon-teal   { background: #d0f5ea; color: #20c997; }
        .kpi-icon-red    { background: #fee2e2; color: #dc3545; }
        .kpi-icon-purple { background: #ede9fe; color: #6f42c1; }

        /* ── Breakdown Row ──────────────────────────────── */
        .breakdown-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 18px 22px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid #f0f0f0;
        }
        .breakdown-title {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #adb5bd;
            margin-bottom: 14px;
        }
        .breakdown-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .breakdown-item:last-child { margin-bottom: 0; }
        .breakdown-label { font-size: 0.82rem; color: #4b5563; font-weight: 500; }
        .breakdown-val   { font-size: 0.85rem; font-weight: 700; color: #1a1a1a; }
        .breakdown-bar-wrap {
            height: 5px;
            background: #f3f4f6;
            border-radius: 10px;
            margin-top: 5px;
            overflow: hidden;
        }
        .breakdown-bar {
            height: 5px;
            border-radius: 10px;
        }

        /* ── Section header ─────────────────────────────── */
        .analytics-heading {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #adb5bd;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .analytics-heading::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f0f0f0;
        }
    </style>
    @endpush

    @php
        $safeTotal = max($totalOrders, 1);
    @endphp

    {{-- Section label --}}
    <div class="analytics-heading mb-3">
        <i class="fa fa-chart-line" style="color:#FF902A;"></i>
        Analytics Overview
    </div>

    {{-- Row 1 — Primary KPIs --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="kpi-card kpi-orange">
                <div class="d-flex align-items-start gap-3">
                    <div class="kpi-icon kpi-icon-orange"><i class="fa fa-receipt"></i></div>
                    <div>
                        <div class="kpi-label">Total Orders</div>
                        <div class="kpi-value">{{ number_format($totalOrders) }}</div>
                        <div class="kpi-sub">All-time</div>
                    </div>
                </div>
                <div class="kpi-bg-icon"><i class="fa fa-receipt"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card kpi-green">
                <div class="d-flex align-items-start gap-3">
                    <div class="kpi-icon kpi-icon-green"><i class="fa fa-money-bill-wave"></i></div>
                    <div>
                        <div class="kpi-label">Total Revenue</div>
                        <div class="kpi-value" style="font-size:1.2rem;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        <div class="kpi-sub">From paid orders</div>
                    </div>
                </div>
                <div class="kpi-bg-icon"><i class="fa fa-money-bill-wave"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card kpi-blue">
                <div class="d-flex align-items-start gap-3">
                    <div class="kpi-icon kpi-icon-blue"><i class="fa fa-calendar-day"></i></div>
                    <div>
                        <div class="kpi-label">Today's Orders</div>
                        <div class="kpi-value">{{ number_format($todayOrders) }}</div>
                        <div class="kpi-sub">{{ today()->format('d M Y') }}</div>
                    </div>
                </div>
                <div class="kpi-bg-icon"><i class="fa fa-calendar-day"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card kpi-teal">
                <div class="d-flex align-items-start gap-3">
                    <div class="kpi-icon kpi-icon-teal"><i class="fa fa-coins"></i></div>
                    <div>
                        <div class="kpi-label">Today's Revenue</div>
                        <div class="kpi-value" style="font-size:1.2rem;">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
                        <div class="kpi-sub">Paid today</div>
                    </div>
                </div>
                <div class="kpi-bg-icon"><i class="fa fa-coins"></i></div>
            </div>
        </div>
    </div>

    {{-- Row 2 — Status KPIs --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="kpi-card kpi-red">
                <div class="d-flex align-items-start gap-3">
                    <div class="kpi-icon kpi-icon-red"><i class="fa fa-hourglass-half"></i></div>
                    <div>
                        <div class="kpi-label">Pending</div>
                        <div class="kpi-value">{{ number_format($pendingOrders) }}</div>
                        <div class="kpi-sub">Awaiting kitchen</div>
                    </div>
                </div>
                <div class="kpi-bg-icon"><i class="fa fa-hourglass-half"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card kpi-green">
                <div class="d-flex align-items-start gap-3">
                    <div class="kpi-icon kpi-icon-green"><i class="fa fa-circle-check"></i></div>
                    <div>
                        <div class="kpi-label">Completed</div>
                        <div class="kpi-value">{{ number_format($completedOrders) }}</div>
                        <div class="kpi-sub">All-time</div>
                    </div>
                </div>
                <div class="kpi-bg-icon"><i class="fa fa-circle-check"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card kpi-green">
                <div class="d-flex align-items-start gap-3">
                    <div class="kpi-icon kpi-icon-teal"><i class="fa fa-check-double"></i></div>
                    <div>
                        <div class="kpi-label">Paid Orders</div>
                        <div class="kpi-value">{{ number_format($paidOrders) }}</div>
                        <div class="kpi-sub">Payment confirmed</div>
                    </div>
                </div>
                <div class="kpi-bg-icon"><i class="fa fa-check-double"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card kpi-red">
                <div class="d-flex align-items-start gap-3">
                    <div class="kpi-icon kpi-icon-red"><i class="fa fa-clock-rotate-left"></i></div>
                    <div>
                        <div class="kpi-label">Unpaid</div>
                        <div class="kpi-value">{{ number_format($unpaidOrders) }}</div>
                        <div class="kpi-sub">Pending payment</div>
                    </div>
                </div>
                <div class="kpi-bg-icon"><i class="fa fa-clock-rotate-left"></i></div>
            </div>
        </div>
    </div>

    {{-- Row 3 — Breakdown Cards --}}
    <div class="row g-3 mb-4">
        {{-- Payment Method Breakdown --}}
        <div class="col-md-4">
            <div class="breakdown-card h-100">
                <div class="breakdown-title"><i class="fa fa-credit-card me-1" style="color:#FF902A;"></i> Payment Method</div>
                <div class="breakdown-item">
                    <div>
                        <div class="breakdown-label">💵 Cash</div>
                        <div class="breakdown-bar-wrap">
                            <div class="breakdown-bar" style="width:{{ $safeTotal > 0 ? round($cashOrders/$safeTotal*100) : 0 }}%; background:#198754;"></div>
                        </div>
                    </div>
                    <div class="breakdown-val ms-3">{{ $cashOrders }}</div>
                </div>
                <div class="breakdown-item">
                    <div style="flex:1;">
                        <div class="breakdown-label">📱 QRIS / Online</div>
                        <div class="breakdown-bar-wrap">
                            <div class="breakdown-bar" style="width:{{ $safeTotal > 0 ? round($qrisOrders/$safeTotal*100) : 0 }}%; background:#0d6efd;"></div>
                        </div>
                    </div>
                    <div class="breakdown-val ms-3">{{ $qrisOrders }}</div>
                </div>
            </div>
        </div>

        {{-- Order Type Breakdown --}}
        <div class="col-md-4">
            <div class="breakdown-card h-100">
                <div class="breakdown-title"><i class="fa fa-utensils me-1" style="color:#FF902A;"></i> Order Type</div>
                <div class="breakdown-item">
                    <div style="flex:1;">
                        <div class="breakdown-label">🍽️ Dine In</div>
                        <div class="breakdown-bar-wrap">
                            <div class="breakdown-bar" style="width:{{ $safeTotal > 0 ? round($dineInOrders/$safeTotal*100) : 0 }}%; background:#FF902A;"></div>
                        </div>
                    </div>
                    <div class="breakdown-val ms-3">{{ $dineInOrders }}</div>
                </div>
                <div class="breakdown-item">
                    <div style="flex:1;">
                        <div class="breakdown-label">🥡 Takeaway</div>
                        <div class="breakdown-bar-wrap">
                            <div class="breakdown-bar" style="width:{{ $safeTotal > 0 ? round($takeawayOrders/$safeTotal*100) : 0 }}%; background:#6f42c1;"></div>
                        </div>
                    </div>
                    <div class="breakdown-val ms-3">{{ $takeawayOrders }}</div>
                </div>
                <div class="breakdown-item">
                    <div style="flex:1;">
                        <div class="breakdown-label">🛵 Delivery</div>
                        <div class="breakdown-bar-wrap">
                            <div class="breakdown-bar" style="width:{{ $safeTotal > 0 ? round($deliveryOrders/$safeTotal*100) : 0 }}%; background:#20c997;"></div>
                        </div>
                    </div>
                    <div class="breakdown-val ms-3">{{ $deliveryOrders }}</div>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="col-md-4">
            <div class="breakdown-card h-100">
                <div class="breakdown-title"><i class="fa fa-gauge-high me-1" style="color:#FF902A;"></i> Quick Stats</div>
                <div class="breakdown-item">
                    <span class="breakdown-label">Completion Rate</span>
                    <span class="breakdown-val" style="color:#198754;">
                        {{ $safeTotal > 0 ? round($completedOrders / $safeTotal * 100) : 0 }}%
                    </span>
                </div>
                <div class="breakdown-bar-wrap mb-3">
                    <div class="breakdown-bar" style="width:{{ $safeTotal > 0 ? round($completedOrders/$safeTotal*100) : 0 }}%; background:#198754;"></div>
                </div>
                <div class="breakdown-item">
                    <span class="breakdown-label">Payment Success Rate</span>
                    <span class="breakdown-val" style="color:#0d6efd;">
                        {{ $safeTotal > 0 ? round($paidOrders / $safeTotal * 100) : 0 }}%
                    </span>
                </div>
                <div class="breakdown-bar-wrap mb-3">
                    <div class="breakdown-bar" style="width:{{ $safeTotal > 0 ? round($paidOrders/$safeTotal*100) : 0 }}%; background:#0d6efd;"></div>
                </div>
                <div class="breakdown-item">
                    <span class="breakdown-label">Avg. Order Value</span>
                    <span class="breakdown-val" style="color:#FF902A;">
                        Rp {{ $safeTotal > 0 ? number_format(($totalRevenue / $paidOrders ?: 1), 0, ',', '.') : '0' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    
    {{-- Row 4 — Revenue Analytics Chart (Additive) --}}
    @php
        // Fetch last 7 days of paid revenue (no controller changes)
        $chartData = \App\Models\Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [\Carbon\Carbon::parse($item->date)->format('D') => $item->total * 1000];
            });

        $chartLabels = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $dayName = now()->subDays($i)->format('D');
            $chartLabels[] = $dayName;
            $chartValues[] = $chartData[$dayName] ?? 0;
        }
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="breakdown-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="breakdown-title mb-0"><i class="fa fa-chart-area me-1" style="color:#FF902A;"></i> 7-Day Revenue Trend</div>
                </div>
                <div style="height: 300px; position: relative;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    
    {{-- Row 5 — Business Intelligence (Top Sellers & Insights) --}}
    @php
        // Top 4 Selling Products (from paid orders)
        $topProducts = \App\Models\OrderItem::selectRaw('product_id, SUM(quantity) as total_qty')
            ->whereHas('order', function($q) {
                $q->where('payment_status', 'paid');
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(4)
            ->get();

        // Advanced Payment Insights: Cash vs QRIS Revenue
        $cashRevenue = \App\Models\Order::where('payment_method', 'cash')->where('payment_status', 'paid')->sum('total_price') * 1000;
        $qrisRevenue = \App\Models\Order::where('payment_method', 'online')->where('payment_status', 'paid')->sum('total_price') * 1000;
        $totalRev = $cashRevenue + $qrisRevenue;
        $cashPct = $totalRev > 0 ? round(($cashRevenue / $totalRev) * 100) : 0;
        $qrisPct = $totalRev > 0 ? round(($qrisRevenue / $totalRev) * 100) : 0;

        // BI Stats
        $totalItemsSold = \App\Models\OrderItem::whereHas('order', function($q){
            $q->where('payment_status', 'paid');
        })->sum('quantity');
        $avgItemsPerOrder = $paidOrders > 0 ? round($totalItemsSold / $paidOrders, 1) : 0;
        
        $returningCustomers = \App\Models\Order::select('phone')
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->groupBy('phone')
            ->havingRaw('COUNT(id) > 1')
            ->get()
            ->count();
    @endphp

    <div class="row g-3 mb-4">
        {{-- Top Selling Products --}}
        <div class="col-md-5">
            <div class="breakdown-card h-100">
                <div class="breakdown-title"><i class="fa fa-crown me-1" style="color:#FF902A;"></i> Top Selling Products</div>
                @forelse($topProducts as $item)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <div style="width: 40px; height: 40px; background-color: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width:100%; height:100%; object-fit:cover;">
                                @else
                                    <i class="fa fa-coffee text-muted"></i>
                                @endif
                            </div>
                        </div>
                        <div style="flex:1;">
                            <div class="breakdown-label" style="font-weight: 600; font-size: 0.9rem;">{{ $item->product ? $item->product->name : 'Unknown Product' }}</div>
                            <div class="kpi-sub mt-0" style="font-size: 0.75rem;">{{ $item->product ? $item->product->category->name ?? 'Uncategorized' : 'N/A' }}</div>
                        </div>
                        <div class="text-end">
                            <div class="breakdown-val fs-6">{{ $item->total_qty }}</div>
                            <div class="kpi-sub mt-0">sold</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted" style="font-size: 0.85rem;">
                        <i class="fa fa-box-open mb-2" style="font-size: 1.5rem; color: #dee2e6;"></i><br>
                        No sales data yet
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Advanced Payment Insights --}}
        <div class="col-md-4">
            <div class="breakdown-card h-100 d-flex flex-column">
                <div class="breakdown-title"><i class="fa fa-wallet me-1" style="color:#FF902A;"></i> Revenue by Method</div>
                
                <div class="mt-2 mb-auto">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <div>
                            <div class="kpi-sub mt-0 text-uppercase fw-bold"><i class="fa fa-qrcode text-primary me-1"></i> QRIS / Online</div>
                            <div class="breakdown-val fs-5 mt-1">Rp {{ number_format($qrisRevenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary rounded-pill px-3">{{ $qrisPct }}%</span>
                        </div>
                    </div>
                    <div class="breakdown-bar-wrap mb-4" style="height: 6px;">
                        <div class="breakdown-bar" style="width:{{ $qrisPct }}%; background:#0d6efd;"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <div>
                            <div class="kpi-sub mt-0 text-uppercase fw-bold"><i class="fa fa-money-bill-wave text-success me-1"></i> Cash</div>
                            <div class="breakdown-val fs-5 mt-1">Rp {{ number_format($cashRevenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success rounded-pill px-3">{{ $cashPct }}%</span>
                        </div>
                    </div>
                    <div class="breakdown-bar-wrap" style="height: 6px;">
                        <div class="breakdown-bar" style="width:{{ $cashPct }}%; background:#198754;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Business Intelligence Mini Widgets --}}
        <div class="col-md-3">
            <div class="d-flex flex-column gap-3 h-100">
                <div class="breakdown-card flex-fill d-flex flex-column justify-content-center" style="padding: 15px 20px;">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon kpi-icon-purple me-3" style="width: 42px; height: 42px;"><i class="fa fa-shopping-basket" style="font-size: 1.1rem;"></i></div>
                        <div>
                            <div class="kpi-label mb-1" style="font-size: 0.65rem;">Avg Items / Order</div>
                            <div class="breakdown-val fs-5">{{ $avgItemsPerOrder }}</div>
                        </div>
                    </div>
                </div>
                <div class="breakdown-card flex-fill d-flex flex-column justify-content-center" style="padding: 15px 20px;">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon kpi-icon-teal me-3" style="width: 42px; height: 42px;"><i class="fa fa-users" style="font-size: 1.1rem;"></i></div>
                        <div>
                            <div class="kpi-label mb-1" style="font-size: 0.65rem;">Returning Customers</div>
                            <div class="breakdown-val fs-5">{{ number_format($returningCustomers) }}</div>
                        </div>
                    </div>
                </div>
                <div class="breakdown-card flex-fill d-flex flex-column justify-content-center" style="padding: 15px 20px;">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon kpi-icon-orange me-3" style="width: 42px; height: 42px;"><i class="fa fa-box" style="font-size: 1.1rem;"></i></div>
                        <div>
                            <div class="kpi-label mb-1" style="font-size: 0.65rem;">Total Items Sold</div>
                            <div class="breakdown-val fs-5">{{ number_format($totalItemsSold) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ═══════════════════════════════════════════════════════════════════ --}}

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Date</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->name }}</td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td>Rp {{ number_format($order->total_price * 1000, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $badges = [
                                            'pending' => 'bg-warning text-dark',
                                            'preparing' => 'bg-info text-white',
                                            'ready' => 'bg-primary text-white',
                                            'completed' => 'bg-success',
                                            'processed' => 'bg-secondary text-white',
                                        ];
                                    @endphp
                                    <span class="badge {{ $badges[$order->status] ?? 'bg-secondary' }} rounded-pill px-3">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary rounded-5">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-0">No orders found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart');
        if(!ctx) return;
        
        const chartCtx = ctx.getContext('2d');
        let gradient = chartCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(255, 144, 42, 0.4)');
        gradient.addColorStop(1, 'rgba(255, 144, 42, 0.0)');

        new Chart(chartCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: {!! json_encode($chartValues) !!},
                    borderColor: '#FF902A',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#FF902A',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a1a1a',
                        titleColor: '#ffffff',
                        bodyColor: '#adb5bd',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed.y;
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f0f0f0',
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#adb5bd',
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#adb5bd',
                            font: { weight: '500' }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
