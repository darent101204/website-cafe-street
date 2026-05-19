@extends('layouts.app')

@section('title', 'Operational Kitchen Dashboard')

@push('styles')
    <!-- Meta auto-refresh every 15 seconds -->
    <meta http-equiv="refresh" content="15">
    <style>
        .kanban-board {
            display: flex;
            gap: 1.5rem;
            overflow-x: auto;
            padding: 0.5rem 0;
            min-height: 70vh;
        }
        .kanban-col {
            flex: 1;
            min-width: 280px;
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
        }
        .kanban-col-header {
            font-weight: 700;
            font-size: 1.1rem;
            color: #495057;
            padding-bottom: 0.75rem;
            margin-bottom: 1rem;
            border-bottom: 3px solid;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .kanban-col-pending { border-color: #FF902A; }
        .kanban-col-preparing { border-color: #17a2b8; }
        .kanban-col-ready { border-color: #0d6efd; }
        .kanban-col-completed { border-color: #198754; }
        
        .kanban-cards-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            max-height: 62vh;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding-right: 0.25rem;
        }
        /* Custom scrollbar for columns */
        .kanban-cards-wrapper::-webkit-scrollbar {
            width: 5px;
        }
        .kanban-cards-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }
        .kanban-cards-wrapper::-webkit-scrollbar-thumb {
            background: #dee2e6;
            border-radius: 5px;
        }
        .order-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-left: 4px solid #dee2e6;
        }
        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
        }
        .order-card-pending { border-color: #FF902A; }
        .order-card-preparing { border-color: #17a2b8; }
        .order-card-ready { border-color: #0d6efd; }
        .order-card-completed { border-color: #198754; }

        .empty-state {
            text-align: center;
            padding: 2.5rem 1rem;
            color: #adb5bd;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4 mt-4 mb-5">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-0 fw-bold">Kitchen <span style="border-bottom: 3px solid #FF902A;">Dashboard</span></h2>
            <p class="text-muted small mb-0">Operational real-time board. Auto-refreshes every 15s.</p>
        </div>
        <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
            <span class="badge bg-secondary p-2"><i class="fa fa-rotate me-1"></i> Live</span>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-5 px-3">
                <i class="fa fa-list me-1"></i> View Order List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="kanban-board">
        <!-- 1. PENDING COLUMN -->
        <div class="kanban-col">
            <div class="kanban-col-header kanban-col-pending">
                <span>📥 Pending Orders</span>
                <span class="badge bg-dark rounded-pill">{{ $pending->count() }}</span>
            </div>
            <div class="kanban-cards-wrapper">
                @forelse($pending as $order)
                    <div class="order-card order-card-pending">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-muted small">#{{ $order->id }}</span>
                            <span class="text-muted small"><i class="fa-regular fa-clock me-1"></i>{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                        <h6 class="fw-bold mb-2">{{ $order->name }}</h6>
                        
                        <div class="mb-2">
                            @if(($order->order_type ?? 'takeaway') === 'dine_in')
                                <span class="badge bg-warning text-dark"><i class="fa fa-utensils me-1"></i> Dine In — Table {{ $order->table->table_number ?? 'N/A' }}</span>
                            @elseif(($order->order_type ?? 'takeaway') === 'delivery')
                                <span class="badge bg-info text-white"><i class="fa fa-truck me-1"></i> Delivery</span>
                            @else
                                <span class="badge bg-secondary text-white"><i class="fa fa-bag-shopping me-1"></i> Take Away</span>
                            @endif
                        </div>

                        <hr class="my-2" style="border-style: dashed;">
                        
                        <div class="my-2">
                            @foreach($order->items as $item)
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">{{ $item->product->name ?? 'Unknown Product' }}</span>
                                    <span class="fw-bold text-dark">x{{ $item->quantity }}</span>
                                </div>
                            @endforeach
                        </div>

                        @if($order->notes)
                            <div class="bg-light p-2 rounded mb-2 small fst-italic">
                                "{{ $order->notes }}"
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold" style="color: #FF902A;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            <form action="{{ route('admin.kitchen.status', $order) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="preparing">
                                <button type="submit" class="btn btn-sm text-white rounded-5 px-3" style="background-color: #FF902A; font-size: 0.75rem;">
                                    Start Preparing 🍳
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                        No pending orders
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 2. PREPARING COLUMN -->
        <div class="kanban-col">
            <div class="kanban-col-header kanban-col-preparing">
                <span>🍳 Preparing</span>
                <span class="badge bg-dark rounded-pill">{{ $preparing->count() }}</span>
            </div>
            <div class="kanban-cards-wrapper">
                @forelse($preparing as $order)
                    <div class="order-card order-card-preparing">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-muted small">#{{ $order->id }}</span>
                            <span class="text-muted small"><i class="fa-regular fa-clock me-1"></i>{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                        <h6 class="fw-bold mb-2">{{ $order->name }}</h6>
                        
                        <div class="mb-2">
                            @if(($order->order_type ?? 'takeaway') === 'dine_in')
                                <span class="badge bg-warning text-dark"><i class="fa fa-utensils me-1"></i> Dine In — Table {{ $order->table->table_number ?? 'N/A' }}</span>
                            @elseif(($order->order_type ?? 'takeaway') === 'delivery')
                                <span class="badge bg-info text-white"><i class="fa fa-truck me-1"></i> Delivery</span>
                            @else
                                <span class="badge bg-secondary text-white"><i class="fa fa-bag-shopping me-1"></i> Take Away</span>
                            @endif
                        </div>

                        <hr class="my-2" style="border-style: dashed;">
                        
                        <div class="my-2">
                            @foreach($order->items as $item)
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">{{ $item->product->name ?? 'Unknown Product' }}</span>
                                    <span class="fw-bold text-dark">x{{ $item->quantity }}</span>
                                </div>
                            @endforeach
                        </div>

                        @if($order->notes)
                            <div class="bg-light p-2 rounded mb-2 small fst-italic">
                                "{{ $order->notes }}"
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold" style="color: #FF902A;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            <form action="{{ route('admin.kitchen.status', $order) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="ready">
                                <button type="submit" class="btn btn-sm btn-info text-white rounded-5 px-3" style="font-size: 0.75rem;">
                                    Mark Ready 🔔
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fa fa-mug-hot fa-2x mb-2 d-block"></i>
                        Nothing preparing
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 3. READY COLUMN -->
        <div class="kanban-col">
            <div class="kanban-col-header kanban-col-ready">
                <span>🔔 Ready for Pick Up</span>
                <span class="badge bg-dark rounded-pill">{{ $ready->count() }}</span>
            </div>
            <div class="kanban-cards-wrapper">
                @forelse($ready as $order)
                    <div class="order-card order-card-ready">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-muted small">#{{ $order->id }}</span>
                            <span class="text-muted small"><i class="fa-regular fa-clock me-1"></i>{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                        <h6 class="fw-bold mb-2">{{ $order->name }}</h6>
                        
                        <div class="mb-2">
                            @if(($order->order_type ?? 'takeaway') === 'dine_in')
                                <span class="badge bg-warning text-dark"><i class="fa fa-utensils me-1"></i> Dine In — Table {{ $order->table->table_number ?? 'N/A' }}</span>
                            @elseif(($order->order_type ?? 'takeaway') === 'delivery')
                                <span class="badge bg-info text-white"><i class="fa fa-truck me-1"></i> Delivery</span>
                            @else
                                <span class="badge bg-secondary text-white"><i class="fa fa-bag-shopping me-1"></i> Take Away</span>
                            @endif
                        </div>

                        <hr class="my-2" style="border-style: dashed;">
                        
                        <div class="my-2">
                            @foreach($order->items as $item)
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">{{ $item->product->name ?? 'Unknown Product' }}</span>
                                    <span class="fw-bold text-dark">x{{ $item->quantity }}</span>
                                </div>
                            @endforeach
                        </div>

                        @if($order->notes)
                            <div class="bg-light p-2 rounded mb-2 small fst-italic">
                                "{{ $order->notes }}"
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold" style="color: #FF902A;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            <form action="{{ route('admin.kitchen.status', $order) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-sm btn-success text-white rounded-5 px-3" style="font-size: 0.75rem;">
                                    Complete Order ✅
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fa fa-bell fa-2x mb-2 d-block"></i>
                        No orders ready
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 4. COMPLETED COLUMN -->
        <div class="kanban-col">
            <div class="kanban-col-header kanban-col-completed">
                <span>✅ Completed (Last 15)</span>
                <span class="badge bg-dark rounded-pill">{{ $completed->count() }}</span>
            </div>
            <div class="kanban-cards-wrapper">
                @forelse($completed as $order)
                    <div class="order-card order-card-completed" style="opacity: 0.75;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-muted small">#{{ $order->id }}</span>
                            <span class="text-muted small"><i class="fa-regular fa-clock me-1"></i>{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                        <h6 class="fw-bold mb-2 text-decoration-line-through">{{ $order->name }}</h6>
                        
                        <div class="mb-2">
                            @if(($order->order_type ?? 'takeaway') === 'dine_in')
                                <span class="badge bg-warning text-dark"><i class="fa fa-utensils me-1"></i> Dine In — Table {{ $order->table->table_number ?? 'N/A' }}</span>
                            @elseif(($order->order_type ?? 'takeaway') === 'delivery')
                                <span class="badge bg-info text-white"><i class="fa fa-truck me-1"></i> Delivery</span>
                            @else
                                <span class="badge bg-secondary text-white"><i class="fa fa-bag-shopping me-1"></i> Take Away</span>
                            @endif
                        </div>

                        <hr class="my-2" style="border-style: dashed;">
                        
                        <div class="my-2 text-muted">
                            @foreach($order->items as $item)
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">{{ $item->product->name ?? 'Unknown Product' }}</span>
                                    <span>x{{ $item->quantity }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold text-muted">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            <span class="badge bg-success py-1 px-2.5 rounded-pill" style="font-size: 0.7rem;">Done</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fa fa-circle-check fa-2x mb-2 d-block"></i>
                        No completed orders
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
