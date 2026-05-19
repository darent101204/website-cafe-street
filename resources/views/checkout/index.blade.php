@extends('layouts.app')

@section('title', 'Checkout - Coffee Street')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h2>Checkout <span style="border-bottom: 3px solid #FF902A;">Form</span></h2>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Customer Details Form -->
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h4 class="mb-0 text-muted">Customer Details</h4>
                    </div>
                    <div class="card-body">
                        @if(session('order_type') === 'dine_in')
                            <!-- Dine In Readonly Table Number -->
                            <div class="mb-3">
                                <label for="table_number" class="form-label">Table Number</label>
                                <input type="text" class="form-control bg-light" id="table_number" value="Table {{ session('table_number') }}" readonly style="font-weight: bold; color: #FF902A;">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="John Doe" value="{{ Auth::check() ? Auth::user()->name : '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" required placeholder="08123456789">
                        </div>

                        @if(session('order_type') === 'delivery')
                            <!-- Delivery Address & Maps Link -->
                            <div class="mb-3">
                                <label for="address" class="form-label">Delivery Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required placeholder="Street name, number, city..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="maps_link" class="form-label">Maps Link (Google Maps URL)</label>
                                <input type="url" class="form-control" id="maps_link" name="maps_link" placeholder="https://maps.google.com/...">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="notes" class="form-label">Optional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Less sugar, extra ice..."></textarea>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label class="form-label d-block fw-bold mb-2">Payment Method</label>
                            @if(session('order_type') === 'delivery')
                                <!-- Delivery is QRIS only -->
                                <div class="form-check border p-3 rounded-4 w-100 mb-2 border-primary bg-light" style="position: relative;">
                                    <input class="form-check-input ms-1" type="radio" name="payment_method" id="payment_qris" value="qris" checked style="pointer-events: none;">
                                    <label class="form-check-label ms-4" for="payment_qris" style="cursor: pointer;">
                                        <i class="fa fa-qrcode me-1 text-primary"></i> <strong>QRIS / Online Payment</strong>
                                        <span class="d-block small text-muted mt-1">Pay instantly and securely using any QRIS-supported e-wallet or banking app.</span>
                                    </label>
                                </div>
                            @else
                                <!-- Dine In & Take Away: Cash and QRIS -->
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="d-block h-100 mb-0">
                                            <div class="form-check border p-3 rounded-4 h-100 payment-card cursor-pointer" id="card_cash" style="border: 2px solid #dee2e6; cursor: pointer; transition: all 0.2s ease;">
                                                <input class="form-check-input ms-1" type="radio" name="payment_method" id="payment_cash" value="cash" checked onclick="selectPaymentCard('cash')">
                                                <div class="ms-4">
                                                    <i class="fa-solid fa-money-bill-wave me-1 text-success"></i> <strong>Cash at Cashier</strong>
                                                    <span class="d-block small text-muted mt-1">Pay directly at the cashier desk with cash.</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="d-block h-100 mb-0">
                                            <div class="form-check border p-3 rounded-4 h-100 payment-card cursor-pointer" id="card_qris" style="border: 2px solid #dee2e6; cursor: pointer; transition: all 0.2s ease;">
                                                <input class="form-check-input ms-1" type="radio" name="payment_method" id="payment_qris" value="qris" onclick="selectPaymentCard('qris')">
                                                <div class="ms-4">
                                                    <i class="fa fa-qrcode me-1 text-primary"></i> <strong>QRIS / Online</strong>
                                                    <span class="d-block small text-muted mt-1">Pay with QRIS e-wallet or banking app.</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <script>
                                    function selectPaymentCard(method) {
                                        const cardCash = document.getElementById('card_cash');
                                        const cardQris = document.getElementById('card_qris');
                                        
                                        if (method === 'cash') {
                                            cardCash.style.borderColor = '#FF902A';
                                            cardCash.style.backgroundColor = '#FFF9F2';
                                            cardQris.style.borderColor = '#dee2e6';
                                            cardQris.style.backgroundColor = 'transparent';
                                        } else {
                                            cardQris.style.borderColor = '#FF902A';
                                            cardQris.style.backgroundColor = '#FFF9F2';
                                            cardCash.style.borderColor = '#dee2e6';
                                            cardCash.style.backgroundColor = 'transparent';
                                        }
                                    }
                                    // Set initial styling for Cash
                                    document.addEventListener("DOMContentLoaded", function() {
                                        selectPaymentCard('cash');
                                    });
                                </script>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-5">
                <div class="card shadow-lg" style="border-color: #F6EBDA;">
                    <div class="card-header" style="background-color: #F6EBDA;">
                        <h4 class="mb-0" style="color: #6F4E37;">Order Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    @foreach($cart as $id => $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item['name'] }}</strong><br>
                                                <small class="text-muted">Rp {{ number_format($item['price'], 0, ',', '.') }} x {{ $item['quantity'] }}</small>
                                            </td>
                                            <td class="text-end">
                                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="border-top">
                                        <td><strong>Subtotal</strong></td>
                                        <td class="text-end"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Delivery</td>
                                        <td class="text-end"><span class="badge bg-success">Free</span></td>
                                    </tr>
                                    <tr class="border-top">
                                        <td><h4><strong>Total</strong></h4></td>
                                        <td class="text-end"><h4 style="color: #FF902A;"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></h4></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-lg rounded-5" style="background-color: #FF902A; color: white;">
                                Place Order
                            </button>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary rounded-5">
                                Back to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
