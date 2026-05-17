@extends('layouts.app')

@section('title', 'Track Order #' . $order->id)

@push('styles')
    <!-- Conditional auto-refresh: only reload if the status is NOT completed -->
    @if($order->status !== 'completed')
        <meta http-equiv="refresh" content="15">
    @endif

    <style>
        .progress-stepper {
            display: flex;
            justify-content: justify;
            position: relative;
            margin: 2rem 0;
            padding: 0;
            list-style: none;
        }
        .progress-stepper::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: #e9ecef;
            z-index: 1;
        }
        .step-item {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .step-icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            border: 4px solid #ffffff;
            transition: all 0.3s ease;
        }
        .step-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #6c757d;
            display: block;
        }

        /* Completed (finished) steps styling */
        .step-item.finished .step-icon {
            background-color: #198754;
            color: #ffffff;
        }
        .step-item.finished .step-label {
            color: #198754;
        }

        /* Active (current) step styling */
        .step-item.active .step-icon {
            background-color: #FF902A;
            color: #ffffff;
            box-shadow: 0 0 0 5px rgba(255, 144, 42, 0.25);
            animation: pulse-glow 2s infinite;
        }
        .step-item.active .step-label {
            color: #FF902A;
            font-weight: 700;
        }

        /* Connector progress line */
        .progress-line-fill {
            position: absolute;
            top: 25px;
            left: 0;
            height: 4px;
            background-color: #198754;
            z-index: 1;
            transition: width 0.5s ease;
        }

        @keyframes pulse-glow {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 144, 42, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(255, 144, 42, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 144, 42, 0);
            }
        }

        @media (max-width: 576px) {
            .step-icon {
                width: 44px;
                height: 44px;
                font-size: 1rem;
            }
            .progress-stepper::before, .progress-line-fill {
                top: 20px;
            }
        }
    </style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                    <i class="fa-regular fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($order->payment_method === 'online' && $order->payment_status !== 'paid')
                <div class="alert alert-warning rounded-4 shadow-sm border-0 mb-4 p-4 text-center">
                    <h5 class="fw-bold mb-2" style="color: #856404;"><i class="fa fa-qrcode me-2"></i> Waiting for Payment</h5>
                    <p class="small text-muted mb-3">We are waiting for your QRIS payment. Please complete the transaction to process your order in the kitchen.</p>
                    <a href="{{ route('checkout.payment', $order->tracking_token) }}" class="btn btn-warning rounded-5 px-4 text-dark fw-bold btn-sm">
                        <i class="fa fa-credit-card me-1"></i> Complete Payment Now
                    </a>
                </div>
            @endif

            <!-- Main Status Card -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="p-4 text-center text-white" style="background-color: #2F2F2F;">
                    <span class="text-uppercase small tracking-wider text-muted-custom" style="color: #adb5bd;">Order Tracking ID</span>
                    <h4 class="mb-0 fw-bold">#{{ $order->id }}</h4>
                    <div class="mt-2 d-flex justify-content-center gap-2 flex-wrap">
                        @if(($order->order_type ?? 'takeaway') === 'dine_in')
                            <span class="badge bg-warning text-dark py-1.5 px-3 rounded-pill"><i class="fa fa-utensils me-1"></i> Dine In — Table {{ $order->table->table_number ?? 'N/A' }}</span>
                        @elseif(($order->order_type ?? 'takeaway') === 'delivery')
                            <span class="badge bg-info text-white py-1.5 px-3 rounded-pill"><i class="fa fa-truck me-1"></i> Delivery</span>
                        @else
                            <span class="badge bg-secondary text-white py-1.5 px-3 rounded-pill"><i class="fa fa-bag-shopping me-1"></i> Take Away</span>
                        @endif

                        @if($order->payment_method === 'cash')
                            <span class="badge bg-success text-white py-1.5 px-3 rounded-pill"><i class="fa-solid fa-money-bill-wave me-1"></i> Cash at Cashier</span>
                        @elseif($order->payment_method === 'online')
                            <span class="badge bg-primary text-white py-1.5 px-3 rounded-pill"><i class="fa fa-qrcode me-1"></i> QRIS</span>
                        @endif

                        @if($order->payment_method)
                            @php
                                $statusClasses = [
                                    'pending_cash' => 'bg-warning text-dark',
                                    'unpaid' => 'bg-danger text-white',
                                    'paid' => 'bg-success text-white',
                                    'failed' => 'bg-danger text-white',
                                    'expired' => 'bg-secondary text-white',
                                ];
                                $statusLabels = [
                                    'pending_cash' => 'Pending Cash',
                                    'unpaid' => 'Unpaid',
                                    'paid' => 'Paid',
                                    'failed' => 'Failed',
                                    'expired' => 'Expired',
                                ];
                                $currentClass = $statusClasses[$order->payment_status] ?? 'bg-secondary text-white';
                                $currentLabel = $statusLabels[$order->payment_status] ?? ucfirst($order->payment_status);
                            @endphp
                            <span class="badge {{ $currentClass }} py-1.5 px-3 rounded-pill">
                                {{ $currentLabel }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    
                    <!-- Dynamic Status Description Callout -->
                    <div class="text-center mb-5">
                        @if($order->status === 'pending')
                            <h2 class="fw-bold mb-2">🟡 Pending</h2>
                            <p class="text-muted mb-0">Your order has been received and is waiting in the queue.</p>
                        @elseif($order->status === 'preparing')
                            <h2 class="fw-bold mb-2 text-info">🍳 Preparing</h2>
                            <p class="text-muted mb-0">Our kitchen is preparing your delicious order right now.</p>
                        @elseif($order->status === 'ready')
                            <h2 class="fw-bold mb-2 text-primary">🔔 Ready for Pickup</h2>
                            @if(($order->order_type ?? 'takeaway') === 'dine_in')
                                <p class="text-success fw-bold mb-0">Table {{ $order->table->table_number ?? 'N/A' }} order is ready!</p>
                            @else
                                <p class="text-success fw-bold mb-0">Please collect your warm order at the counter.</p>
                            @endif
                        @elseif($order->status === 'completed')
                            <h2 class="fw-bold mb-2 text-success">✅ Completed</h2>
                            <p class="text-muted mb-0">Enjoy your meal! Thank you for ordering from Coffee Street ☕</p>
                        @else
                            <h2 class="fw-bold mb-2 text-secondary">{{ ucfirst($order->status) }}</h2>
                            <p class="text-muted mb-0">Your order is being processed.</p>
                        @endif
                    </div>

                    <!-- Progress Stepper Stepped Nodes -->
                    @php
                        $statuses = ['pending', 'preparing', 'ready', 'completed'];
                        $currentIdx = array_search($order->status, $statuses);
                        
                        // Handle legacy "processed" status falling into preparing index fallback
                        if ($currentIdx === false) {
                            $currentIdx = ($order->status === 'processed') ? 1 : 0;
                        }
                        
                        // Calculate percentage of filling progress line
                        $progressPercent = ($currentIdx / (count($statuses) - 1)) * 100;
                    @endphp

                    <div class="position-relative mb-5 px-3">
                        <ul class="progress-stepper">
                            <!-- Connector Progress Line Fill -->
                            <div class="progress-line-fill" style="width: {{ $progressPercent }}%;"></div>

                            <!-- Step 1: Pending -->
                            <li class="step-item {{ $currentIdx > 0 ? 'finished' : ($currentIdx == 0 ? 'active' : '') }}">
                                <div class="step-icon">
                                    @if($currentIdx > 0)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa fa-inbox"></i>
                                    @endif
                                </div>
                                <span class="step-label">Pending</span>
                            </li>

                            <!-- Step 2: Preparing -->
                            <li class="step-item {{ $currentIdx > 1 ? 'finished' : ($currentIdx == 1 ? 'active' : '') }}">
                                <div class="step-icon">
                                    @if($currentIdx > 1)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa fa-mug-hot"></i>
                                    @endif
                                </div>
                                <span class="step-label">Preparing</span>
                            </li>

                            <!-- Step 3: Ready -->
                            <li class="step-item {{ $currentIdx > 2 ? 'finished' : ($currentIdx == 2 ? 'active' : '') }}">
                                <div class="step-icon">
                                    @if($currentIdx > 2)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa fa-bell"></i>
                                    @endif
                                </div>
                                <span class="step-label">Ready</span>
                            </li>

                            <!-- Step 4: Completed -->
                            <li class="step-item {{ $currentIdx == 3 ? 'finished' : '' }}">
                                <div class="step-icon">
                                    <i class="fa fa-circle-check"></i>
                                </div>
                                <span class="step-label">Completed</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Live Indicator (only shown when order is active) -->
                    @if($order->status !== 'completed')
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                            <span class="spinner-grow spinner-grow-sm text-warning" role="status"></span>
                            <span class="text-muted small">Auto-checking order status in real-time...</span>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Order Details Summary -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold mb-0">Order Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr class="border-bottom">
                                        <td class="ps-0 py-3" style="width: 70%;">
                                            <h6 class="fw-bold mb-0">{{ $item->product->name ?? 'Unknown Product' }}</h6>
                                            <span class="text-muted small">{{ number_format($item->price, 0) }} K</span>
                                        </td>
                                        <td class="text-center py-3 text-muted">
                                            x{{ $item->quantity }}
                                        </td>
                                        <td class="text-end pe-0 py-3 fw-bold">
                                            {{ number_format($item->price * $item->quantity, 0) }} K
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($order->notes)
                        <div class="bg-light p-3 rounded-4 mt-3 mb-4">
                            <span class="text-muted small d-block mb-1">Custom Notes</span>
                            <p class="mb-0 fst-italic text-dark">"{{ $order->notes }}"</p>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <h5 class="fw-bold mb-0">Total Amount</h5>
                        <h4 class="fw-bold mb-0" style="color: #FF902A;">{{ number_format($order->total_price, 0) }} K</h4>
                    </div>
                </div>
            </div>

            <!-- Back navigation links -->
            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark rounded-5 px-4">
                    <i class="fa fa-arrow-left me-1"></i> Back to Menu
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
