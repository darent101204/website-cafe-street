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
            @if(!empty($order->tracking_token))
                <a href="{{ route('order.receipt', $order->tracking_token) }}" target="_blank" class="btn btn-outline-success rounded-5 me-2">
                    🧾 Open Receipt
                </a>
            @endif
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
                                        <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Amount</strong></td>
                                    <td class="text-end"><h4 style="color: #FF902A;"><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></h4></td>
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
                        <label class="text-muted small d-block">Order Type</label>
                        @if(($order->order_type ?? 'takeaway') === 'dine_in')
                            <span class="badge bg-warning text-dark"><i class="fa fa-utensils me-1"></i> Dine In</span>
                            @if($order->table)
                                <span class="fw-bold ms-1" style="color: #FF902A;">(Table {{ $order->table->table_number }})</span>
                            @endif
                        @elseif(($order->order_type ?? 'takeaway') === 'delivery')
                            <span class="badge bg-info text-white"><i class="fa fa-truck me-1"></i> Delivery</span>
                        @else
                            <span class="badge bg-secondary text-white"><i class="fa fa-bag-shopping me-1"></i> Take Away</span>
                        @endif
                    </div>
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
                    @if($order->maps_link)
                        <div class="mb-3">
                            <label class="text-muted small">Maps Location</label>
                            <p class="mb-0">
                                <a href="{{ $order->maps_link }}" target="_blank" class="text-decoration-none fw-bold" style="color: #FF902A;">
                                    <i class="fa fa-map-location-dot me-1"></i> View on Google Maps
                                </a>
                            </p>
                        </div>
                    @endif
                    @if($order->notes)
                        <div class="mb-0">
                            <label class="text-muted small">Notes</label>
                            <p class="mb-0 fst-italic">"{{ $order->notes }}"</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Payment Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small d-block mb-1">Payment Method</label>
                        @if($order->payment_method === 'cash')
                            <span class="badge bg-success text-white px-3 py-1.5 rounded-pill"><i class="fa-solid fa-money-bill-wave me-1"></i> Cash at Cashier</span>
                        @elseif($order->payment_method === 'online')
                            <span class="badge bg-primary text-white px-3 py-1.5 rounded-pill"><i class="fa fa-qrcode me-1"></i> QRIS / Online</span>
                        @else
                            <span class="badge bg-secondary text-white px-3 py-1.5 rounded-pill"><i class="fa fa-clock me-1"></i> Legacy / Cash</span>
                        @endif
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small d-block mb-1">Payment Status</label>
                        @php
                            $statusLabels = [
                                'pending_cash' => ['label' => 'Pending Cash', 'class' => 'bg-warning text-dark'],
                                'unpaid' => ['label' => 'Unpaid', 'class' => 'bg-orange text-white'],
                                'paid' => ['label' => 'Paid', 'class' => 'bg-success text-white'],
                                'failed' => ['label' => 'Failed', 'class' => 'bg-danger text-white'],
                                'expired' => ['label' => 'Expired', 'class' => 'bg-dark text-white'],
                            ];
                            $paymentStatus = $order->payment_status ?? 'unpaid';
                            if (is_null($order->payment_method)) {
                                $currentLabel = ['label' => 'Legacy (Paid)', 'class' => 'bg-secondary text-white'];
                            } else {
                                $currentLabel = $statusLabels[$paymentStatus] ?? ['label' => ucfirst($paymentStatus), 'class' => 'bg-secondary text-white'];
                            }
                        @endphp
                        <span class="badge {{ $currentLabel['class'] }} px-3 py-1.5 rounded-pill" style="{{ $paymentStatus === 'unpaid' && $order->payment_method ? 'background-color: #FF902A !important;' : '' }}">
                            {{ $currentLabel['label'] }}
                        </span>
                    </div>
                </div>
            </div>

            @if($order->payment_method)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Update Payment Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.payment', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="form-select">
                                <option value="pending_cash" {{ ($order->payment_status ?? 'unpaid') == 'pending_cash' ? 'selected' : '' }}>Pending Cash</option>
                                <option value="unpaid" {{ ($order->payment_status ?? 'unpaid') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ ($order->payment_status ?? 'unpaid') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ ($order->payment_status ?? 'unpaid') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="expired" {{ ($order->payment_status ?? 'unpaid') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn w-100 text-white rounded-5" style="background-color: #FF902A;">
                            Update Payment Status
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <!-- Quick Progression Flow (Additive) -->
                    @php
                        $nextStatus = null;
                        $btnText = '';
                        $btnClass = '';
                        $currentStatus = $order->status;
                        
                        if ($currentStatus === 'pending') {
                            $nextStatus = 'confirmed';
                            $btnText = '👍 Confirm Order';
                            $btnClass = 'btn-primary';
                        } elseif ($currentStatus === 'confirmed') {
                            $nextStatus = 'brewing';
                            $btnText = '☕ Start Brewing';
                            $btnClass = 'btn-info text-white';
                        } elseif ($currentStatus === 'brewing' || $currentStatus === 'preparing' || $currentStatus === 'processed') {
                            $nextStatus = 'ready';
                            $btnText = '🔔 Mark Ready for Pickup';
                            $btnClass = 'btn-warning text-dark';
                        } elseif ($currentStatus === 'ready') {
                            if (($order->order_type ?? 'takeaway') === 'delivery') {
                                $nextStatus = 'on_delivery';
                                $btnText = '🚚 Out for Delivery';
                                $btnClass = 'btn-info text-white';
                            } else {
                                $nextStatus = 'completed';
                                $btnText = '✅ Complete Order';
                                $btnClass = 'btn-success';
                            }
                        } elseif ($currentStatus === 'on_delivery') {
                            $nextStatus = 'completed';
                            $btnText = '✅ Complete Order';
                            $btnClass = 'btn-success';
                        }
                    @endphp

                    @if($nextStatus)
                        <div class="mb-3 text-center">
                            <span class="text-muted small d-block mb-2">Recommended Action</span>
                            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $nextStatus }}">
                                <button type="submit" class="btn w-100 rounded-5 fw-bold {{ $btnClass }} shadow-sm py-2">
                                    {{ $btnText }}
                                </button>
                            </form>
                        </div>
                    @endif

                    @if(!in_array($currentStatus, ['completed', 'cancelled']))
                        <div class="mb-3 text-center">
                            <form action="{{ route('admin.orders.status', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn btn-outline-danger w-100 rounded-5 fw-bold btn-sm py-1.5">
                                    ❌ Cancel Order
                                </button>
                            </form>
                        </div>
                        <hr class="my-3 text-muted" style="opacity: 0.15;">
                    @endif

                    <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Current Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                <option value="brewing" {{ $order->status == 'brewing' ? 'selected' : '' }}>Brewing</option>
                                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready for Pickup</option>
                                <option value="on_delivery" {{ $order->status == 'on_delivery' ? 'selected' : '' }}>On Delivery</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="processed" {{ $order->status == 'processed' ? 'selected' : '' }}>Processed</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn w-100 text-white rounded-5" style="background-color: #FF902A;">
                            Update Status Manually
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
