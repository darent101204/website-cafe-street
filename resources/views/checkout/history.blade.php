@extends('layouts.app')

@section('title', 'My Order History - Coffee Street')

@section('content')
<div class="container mt-5 mb-5" style="min-height: 60vh;">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2>My <span style="border-bottom: 3px solid #FF902A;">Order History</span></h2>
            <p class="text-muted small mt-1">View and track all your past and current orders.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('products.index') }}" class="btn btn-outline-dark rounded-5 px-4 me-2">
                <i class="fa fa-mug-hot me-1"></i> New Order
            </a>
            <span class="badge bg-light border text-dark p-2 rounded-5 px-3">
                <i class="fa fa-user me-1" style="color: #FF902A;"></i> {{ Auth::user()->name }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($favorites && $favorites->count() > 0)
        <div class="mb-5">
            <h4 class="mb-3">Your <span style="border-bottom: 3px solid #dc3545;">Favorites ❤️</span></h4>
            <div class="row g-3 flex-row flex-nowrap overflow-auto pb-3 px-1" style="scrollbar-width: thin;">
                @foreach($favorites as $favorite)
                    @if($favorite->product)
                    <div class="col-10 col-sm-6 col-md-4 col-lg-3">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-body p-3 d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    @if(file_exists(public_path('images/' . $favorite->product->image)))
                                        <img src="{{ asset('images/' . $favorite->product->image) }}" class="rounded-3 me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    @elseif(file_exists(public_path('images/products/' . $favorite->product->image)))
                                        <img src="{{ asset('images/products/' . $favorite->product->image) }}" class="rounded-3 me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="rounded-3 me-3 bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fa fa-coffee text-muted"></i>
                                        </div>
                                    @endif
                                    <div style="flex: 1; min-width: 0;">
                                        <h6 class="fw-bold mb-1 text-truncate">{{ $favorite->product->name }}</h6>
                                        <span class="text-muted small">Rp {{ number_format($favorite->product->price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="ms-2">
                                        <form action="{{ route('products.favorite', $favorite->product_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-link text-danger p-0 shadow-none">
                                                <i class="fa-solid fa-heart" style="font-size: 1.2rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <form action="{{ route('cart.add', $favorite->product_id) }}" method="POST" class="mt-auto">
                                    @csrf
                                    <button type="submit" class="btn btn-sm w-100 rounded-5 fw-bold" style="background-color: #FF902A; color: white;">
                                        <i class="fa fa-plus me-1"></i> Quick Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <div class="row g-4">
        @forelse($orders as $order)
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden transition-hover" style="transition: transform 0.2s, box-shadow 0.2s;">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <span class="text-muted small d-block mb-1">
                                <i class="fa-regular fa-calendar me-1"></i> {{ $order->created_at->format('d M Y, H:i') }}
                            </span>
                            <h5 class="fw-bold mb-0">Order #{{ $order->id }}</h5>
                        </div>
                        <div class="d-flex gap-2">
                            @if(($order->order_type ?? 'takeaway') === 'dine_in')
                                <span class="badge bg-warning text-dark py-2 px-3 rounded-pill"><i class="fa fa-utensils me-1"></i> Dine In (Table {{ $order->table->table_number ?? 'N/A' }})</span>
                            @elseif(($order->order_type ?? 'takeaway') === 'delivery')
                                <span class="badge bg-info text-white py-2 px-3 rounded-pill"><i class="fa fa-truck me-1"></i> Delivery</span>
                            @else
                                <span class="badge bg-secondary text-white py-2 px-3 rounded-pill"><i class="fa fa-bag-shopping me-1"></i> Take Away</span>
                            @endif

                            @php
                                $statusClasses = [
                                    'pending' => 'bg-warning text-dark',
                                    'preparing' => 'bg-info text-white',
                                    'ready' => 'bg-primary text-white',
                                    'completed' => 'bg-success text-white',
                                    'processed' => 'bg-secondary text-white',
                                ];
                                $statusIcon = [
                                    'pending' => 'fa-inbox',
                                    'preparing' => 'fa-mug-hot',
                                    'ready' => 'fa-bell',
                                    'completed' => 'fa-circle-check',
                                    'processed' => 'fa-spinner',
                                ];
                            @endphp
                            <span class="badge {{ $statusClasses[$order->status] ?? 'bg-secondary' }} py-2 px-3 rounded-pill">
                                <i class="fa {{ $statusIcon[$order->status] ?? 'fa-circle' }} me-1"></i> {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body px-4 py-2">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($order->items as $item)
                                        <div class="d-inline-flex align-items-center bg-light rounded-pill px-3 py-1 border">
                                            @if($item->product && file_exists(public_path('images/' . $item->product->image)))
                                                <img src="{{ asset('images/' . $item->product->image) }}" class="rounded-circle me-2" style="width: 20px; height: 20px; object-fit: cover;">
                                            @elseif($item->product && file_exists(public_path('images/products/' . $item->product->image)))
                                                <img src="{{ asset('images/products/' . $item->product->image) }}" class="rounded-circle me-2" style="width: 20px; height: 20px; object-fit: cover;">
                                            @else
                                                <i class="fa fa-coffee text-muted me-2" style="font-size: 0.8rem;"></i>
                                            @endif
                                            <span class="small fw-medium">{{ $item->quantity }}x {{ $item->product->name ?? 'Unknown' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <span class="text-muted small d-block mb-1">Total Amount</span>
                                <h5 class="fw-bold mb-0" style="color: #FF902A;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top-0 px-4 py-3 mt-2 d-flex justify-content-between align-items-center flex-wrap gap-3 rounded-bottom-4">
                        <div>
                            @php
                                $paymentStatus = $order->payment_status ?? 'unpaid';
                            @endphp
                            <span class="text-muted small me-2">Payment Status:</span>
                            @if($paymentStatus === 'paid')
                                <span class="badge bg-success rounded-pill px-3"><i class="fa fa-check me-1"></i> Paid</span>
                            @elseif($paymentStatus === 'pending_cash')
                                <span class="badge bg-warning text-dark rounded-pill px-3"><i class="fa fa-clock me-1"></i> Pay at Cashier</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3"><i class="fa fa-xmark me-1"></i> Unpaid</span>
                            @endif
                        </div>
                        
                        <div class="d-flex gap-2">
                            @if($order->status !== 'completed')
                                <a href="{{ route('order.track', $order->tracking_token) }}" class="btn btn-sm btn-outline-primary rounded-5 px-3">
                                    <i class="fa fa-location-dot me-1"></i> Track Order
                                </a>
                            @else
                                <a href="{{ route('order.track', $order->tracking_token) }}" class="btn btn-sm btn-outline-secondary rounded-5 px-3">
                                    <i class="fa fa-eye me-1"></i> Details
                                </a>
                            @endif
                            
                            @if($order->status === 'completed' || $order->payment_status === 'paid')
                                <a href="{{ route('order.receipt', $order->tracking_token) }}" target="_blank" class="btn btn-sm btn-success rounded-5 px-3">
                                    <i class="fa fa-receipt me-1"></i> Receipt
                                </a>
                                <a href="{{ route('order.invoice', $order->tracking_token) }}" class="btn btn-sm btn-outline-danger rounded-5 px-3">
                                    <i class="fa fa-file-pdf me-1"></i> PDF
                                </a>
                            @endif
                            
                            <form action="{{ route('order.reorder', $order) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm rounded-5 px-3" style="background-color: #FF902A; color: white;">
                                    <i class="fa fa-rotate-right me-1"></i> Buy Again
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 mt-4">
                <div style="width: 100px; height: 100px; background-color: #f8f9fa; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                    <i class="fa fa-receipt" style="font-size: 2.5rem; color: #dee2e6;"></i>
                </div>
                <h4 class="fw-bold mb-3">No Orders Yet</h4>
                <p class="text-muted mb-4">You haven't placed any orders with us. Start exploring our menu!</p>
                <a href="{{ route('products.index') }}" class="btn rounded-5 px-4 py-2 fw-bold" style="background-color: #FF902A; color: white;">
                    <i class="fa fa-mug-hot me-1"></i> Order Now
                </a>
            </div>
        @endforelse
    </div>

    @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
