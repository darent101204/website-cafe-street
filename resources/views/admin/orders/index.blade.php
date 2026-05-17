@extends('layouts.app')

@section('title', 'Admin - Order Management')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2>Order <span style="border-bottom: 3px solid #FF902A;">Management</span></h2>
        </div>
        <div class="col-md-6 text-end">
            <span class="badge bg-secondary">Admin Area</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                                <td>{{ number_format($order->total_price, 0) }} K</td>
                                <td>
                                    @php
                                        $badges = [
                                            'pending' => 'bg-warning text-dark',
                                            'processed' => 'bg-info text-white',
                                            'completed' => 'bg-success',
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
@endsection
