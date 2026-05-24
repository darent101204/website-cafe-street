@extends('layouts.app')

@section('title', 'Checkout — Coffee Street')

@section('content')

{{-- ── PHASE 4: PREMIUM CHECKOUT PAGE ─────────────────────────────────────── --}}
<section class="cs-checkout-section">
    <div class="container">

        {{-- Page Header --}}
        <div class="cs-checkout-header">
            <div>
                <h1 class="cs-checkout-title">Checkout</h1>
                <p class="cs-checkout-subtitle">Almost there — complete your order below</p>
            </div>
            <a href="{{ route('cart.index') }}" class="cs-cart-back-link">
                <i class="fa fa-arrow-left"></i> Back to Cart
            </a>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
        <div class="cs-alert cs-alert-danger" role="alert">
            <i class="fa fa-exclamation-circle"></i>
            <ul class="cs-alert-list mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- ── MAIN FORM ───────────────────────────────────────────── --}}
        <form action="{{ route('checkout.store') }}" method="POST" id="cs-checkout-form">
            @csrf

            <div class="cs-checkout-layout">

                {{-- LEFT: Customer Details --}}
                <div class="cs-checkout-form-col">

                    {{-- Order Type Indicator --}}
                    <div class="cs-checkout-block">
                        <div class="cs-checkout-block-header">
                            <i class="fa fa-location-dot cs-block-icon"></i>
                            <h2 class="cs-checkout-block-title">Order Type</h2>
                        </div>
                        <div class="cs-checkout-block-body">
                            @if(session('order_type') === 'dine_in')
                            <div class="cs-order-type-badge cs-badge-dinein">
                                <i class="fa fa-utensils"></i>
                                <div>
                                    <strong>Dine In</strong>
                                    <span>Table {{ session('table_number') }}</span>
                                </div>
                            </div>
                            @elseif(session('order_type') === 'delivery')
                            <div class="cs-order-type-badge cs-badge-delivery">
                                <i class="fa fa-truck"></i>
                                <div>
                                    <strong>Delivery</strong>
                                    <span>Address required below</span>
                                </div>
                            </div>
                            @else
                            <div class="cs-order-type-badge cs-badge-takeaway">
                                <i class="fa fa-bag-shopping"></i>
                                <div>
                                    <strong>Take Away</strong>
                                    <span>Pick up at counter</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Customer Info --}}
                    <div class="cs-checkout-block">
                        <div class="cs-checkout-block-header">
                            <i class="fa fa-user cs-block-icon"></i>
                            <h2 class="cs-checkout-block-title">Customer Details</h2>
                        </div>
                        <div class="cs-checkout-block-body">

                            @if(session('order_type') === 'dine_in')
                            <div class="cs-form-group">
                                <label class="cs-form-label" for="table_number">Table Number</label>
                                <input type="text" class="cs-form-input cs-form-input--readonly" id="table_number"
                                    value="Table {{ session('table_number') }}" readonly>
                            </div>
                            @endif

                            <div class="cs-form-group">
                                <label class="cs-form-label" for="name">Full Name <span class="cs-required">*</span></label>
                                <input type="text" class="cs-form-input" id="name" name="name"
                                    required placeholder="Your full name"
                                    value="{{ Auth::check() ? Auth::user()->name : old('name') }}">
                            </div>

                            <div class="cs-form-group">
                                <label class="cs-form-label" for="phone">Phone Number <span class="cs-required">*</span></label>
                                <input type="text" class="cs-form-input" id="phone" name="phone"
                                    required placeholder="08123456789" value="{{ old('phone') }}">
                            </div>

                            @if(session('order_type') === 'delivery')
                            <div class="cs-form-group">
                                <label class="cs-form-label" for="address">Delivery Address <span class="cs-required">*</span></label>
                                <textarea class="cs-form-input cs-form-textarea" id="address" name="address"
                                    rows="3" required placeholder="Street name, number, area, city...">{{ old('address') }}</textarea>
                            </div>
                            <div class="cs-form-group">
                                <label class="cs-form-label" for="maps_link">
                                    Google Maps Link
                                    <span class="cs-form-hint">(optional but recommended)</span>
                                </label>
                                <input type="url" class="cs-form-input" id="maps_link" name="maps_link"
                                    placeholder="https://maps.google.com/..." value="{{ old('maps_link') }}">
                            </div>
                            @endif

                            <div class="cs-form-group">
                                <label class="cs-form-label" for="notes">
                                    Special Notes
                                    <span class="cs-form-hint">(optional)</span>
                                </label>
                                <textarea class="cs-form-input cs-form-textarea" id="notes" name="notes"
                                    rows="2" placeholder="Less sugar, extra ice, no straw...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="cs-checkout-block">
                        <div class="cs-checkout-block-header">
                            <i class="fa fa-credit-card cs-block-icon"></i>
                            <h2 class="cs-checkout-block-title">Payment Method</h2>
                        </div>
                        <div class="cs-checkout-block-body">

                            @if(session('order_type') === 'delivery')
                                {{-- Delivery: QRIS only --}}
                                <div class="cs-payment-card cs-payment-card--selected">
                                    <input type="radio" name="payment_method" id="payment_qris_del"
                                        value="qris" checked style="display:none;">
                                    <i class="fa fa-qrcode cs-payment-icon"></i>
                                    <div class="cs-payment-info">
                                        <strong>QRIS / Online Payment</strong>
                                        <span>Pay with any QRIS-supported e-wallet or banking app</span>
                                    </div>
                                    <i class="fa fa-check-circle cs-payment-check"></i>
                                </div>
                            @else
                                {{-- Dine In / Take Away: Cash or QRIS --}}
                                <div class="cs-payment-options">
                                    <label class="cs-payment-card" id="pcard_cash" for="payment_cash">
                                        <input type="radio" name="payment_method" id="payment_cash"
                                            value="cash" checked onchange="selectPayment('cash')">
                                        <i class="fa fa-money-bill-wave cs-payment-icon cs-payment-icon--cash"></i>
                                        <div class="cs-payment-info">
                                            <strong>Cash at Cashier</strong>
                                            <span>Pay directly at the counter</span>
                                        </div>
                                        <i class="fa fa-check-circle cs-payment-check" id="pcheck_cash"></i>
                                    </label>
                                    <label class="cs-payment-card" id="pcard_qris" for="payment_qris">
                                        <input type="radio" name="payment_method" id="payment_qris"
                                            value="qris" onchange="selectPayment('qris')">
                                        <i class="fa fa-qrcode cs-payment-icon cs-payment-icon--qris"></i>
                                        <div class="cs-payment-info">
                                            <strong>QRIS / Online</strong>
                                            <span>Pay with QRIS e-wallet or banking app</span>
                                        </div>
                                        <i class="fa fa-check-circle cs-payment-check" id="pcheck_qris" style="opacity:0;"></i>
                                    </label>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>{{-- /.cs-checkout-form-col --}}

                {{-- RIGHT: Order Summary --}}
                <div class="cs-checkout-summary-col">
                    <div class="cs-summary-card cs-summary-card--sticky">

                        <div class="cs-summary-header">
                            <i class="fa fa-receipt cs-summary-icon"></i>
                            <h2 class="cs-summary-title">Order Summary</h2>
                        </div>

                        {{-- Items --}}
                        <div class="cs-summary-lines">
                            @foreach($cart as $id => $item)
                            <div class="cs-summary-line">
                                <span class="cs-summary-line-name">
                                    {{ $item['name'] }}
                                    <span class="cs-summary-line-qty">×{{ $item['quantity'] }}</span>
                                </span>
                                <span class="cs-summary-line-price">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </span>
                            </div>
                            @endforeach
                        </div>

                        <div class="cs-summary-divider"></div>

                        <div class="cs-summary-totals">
                            <div class="cs-summary-row">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="cs-summary-row">
                                <span>Delivery</span>
                                <span class="cs-summary-free">Free</span>
                            </div>
                        </div>

                        <div class="cs-summary-divider"></div>

                        <div class="cs-summary-total">
                            <span>Total</span>
                            <span class="cs-summary-total-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <div class="cs-summary-divider"></div>

                        {{-- Place Order CTA --}}
                        <button type="submit" class="cs-checkout-btn" form="cs-checkout-form">
                            Place Order
                            <i class="fa fa-arrow-right"></i>
                        </button>

                        <a href="{{ route('cart.index') }}" class="cs-continue-link">
                            <i class="fa fa-arrow-left"></i> Back to Cart
                        </a>
                    </div>
                </div>

            </div>{{-- /.cs-checkout-layout --}}
        </form>

    </div>
</section>

@push('scripts')
<script>
    /* ── Payment Card Selection ───────────────────────────────── */
    function selectPayment(method) {
        const cards  = ['cash', 'qris'];
        cards.forEach(m => {
            const card  = document.getElementById('pcard_' + m);
            const check = document.getElementById('pcheck_' + m);
            if (!card) return;
            if (m === method) {
                card.classList.add('cs-payment-card--selected');
                if (check) check.style.opacity = '1';
            } else {
                card.classList.remove('cs-payment-card--selected');
                if (check) check.style.opacity = '0';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Set initial state (cash selected by default)
        const cashInput = document.getElementById('payment_cash');
        if (cashInput && cashInput.checked) selectPayment('cash');
    });
</script>
@endpush

@endsection
