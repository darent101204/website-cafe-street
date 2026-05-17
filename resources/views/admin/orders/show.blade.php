@extends('layouts.app')

@section('title', 'Order Details #' . $order->id)

@section('content')
<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3>Order Details <span style="color: #FF902A;">#{{ $order->id }}</span></h3>
            <p class="text-muted">{{ $order->created_at->format('l, d F Y H:i') }}</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-5">
                <i class="fa fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Order Items -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead class="text-muted border-bottom">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr class="border-bottom">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && file_exists(public_path('images/' . $item->product->image)))
                                                    <img src="{{ asset('images/' . $item->product->image) }}" class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                                                @elseif($item->product && file_exists(public_path('images/products/' . $item->product->image)))
                                                    <img src="{{ asset('images/products/' . $item->product->image) }}" class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('images/img_product.png') }}" class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                                                @endif
                                                <div>
                                                    <span class="d-block fw-bold">{{ $item->product ? $item->product->name : 'Unknown Product' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ number_format($item->price, 0) }} K</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end fw-bold">{{ number_format($item->price * $item->quantity, 0) }} K</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Amount</strong></td>
                                    <td class="text-end"><h4 style="color: #FF902A;"><strong>{{ number_format($order->total_price, 0) }} K</strong></h4></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info & Status -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Full Name</label>
                        <p class="fw-bold mb-0">{{ $order->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Phone Number</label>
                        <p class="fw-bold mb-0">{{ $order->phone }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Delivery Address</label>
                        <p class="mb-0">{{ $order->address }}</p>
                    </div>
                    @if($order->notes)
                        <div class="mb-0">
                            <label class="text-muted small">Notes</label>
                            <p class="mb-0 fst-italic">"{{ $order->notes }}"</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Current Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processed" {{ $order->status == 'processed' ? 'selected' : '' }}>Processed</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn w-100 text-white rounded-5" style="background-color: #FF902A;">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
