@extends('layouts.app')

@section('title', 'Your Cart — Coffee Street')

@section('content')

{{-- ── PHASE 4: PREMIUM CART PAGE ─────────────────────────────────────── --}}
<section class="cs-cart-section">
    <div class="container">

        {{-- Page Header --}}
        <div class="cs-cart-header">
            <div>
                <h1 class="cs-cart-title">Your Cart</h1>
                <p class="cs-cart-subtitle">
                    @if(session('cart') && count(session('cart')) > 0)
                        {{ count(session('cart')) }} item{{ count(session('cart')) > 1 ? 's' : '' }} ready to order
                    @else
                        Your cart is waiting to be filled
                    @endif
                </p>
            </div>
            @if(session('cart') && count(session('cart')) > 0)
            <a href="{{ route('products.index') }}" class="cs-cart-back-link">
                <i class="fa fa-arrow-left"></i> Continue Shopping
            </a>
            @endif
        </div>

        {{-- Success Alert --}}
        @if(session('success'))
        <div class="cs-alert cs-alert-success" role="alert">
            <i class="fa fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="cs-alert-close" onclick="this.parentElement.remove()" aria-label="Close">
                <i class="fa fa-times"></i>
            </button>
        </div>
        @endif

        @if(session('cart') && count(session('cart')) > 0)

        {{-- ── MAIN LAYOUT ──────────────────────────────────────────── --}}
        <div class="cs-cart-layout">

            {{-- LEFT: Cart Items --}}
            <div class="cs-cart-items-col">
                <div class="cs-cart-items-header">
                    <span class="cs-cart-items-label">Order Items</span>
                </div>

                @foreach(session('cart') as $id => $details)
                <div class="cs-cart-item">
                    {{-- Product Image --}}
                    <div class="cs-cart-item-img-wrap">
                        <img
                            src="{{ !empty($details['image']) ? (str_starts_with($details['image'], 'images/') ? asset($details['image']) : Storage::url($details['image'])) : asset('images/no-image.png') }}"
                            alt="{{ $details['name'] }}"
                            class="cs-cart-item-img"
                        >
                    </div>

                    {{-- Product Info --}}
                    <div class="cs-cart-item-info">
                        <h3 class="cs-cart-item-name">{{ $details['name'] }}</h3>
                        @if(!empty($details['description']))
                        <p class="cs-cart-item-desc">{{ Str::limit($details['description'], 60) }}</p>
                        @endif
                        <p class="cs-cart-item-unit-price">Rp {{ number_format($details['price'], 0, ',', '.') }} / item</p>
                    </div>

                    {{-- Controls --}}
                    <div class="cs-cart-item-controls">
                        {{-- Quantity Stepper --}}
                        <form action="{{ route('cart.update') }}" method="POST" class="cs-qty-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="id" value="{{ $id }}">
                            <div class="cs-qty-stepper">
                                <button type="button" class="cs-qty-btn" onclick="stepQty(this, -1)">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" max="99"
                                    class="cs-qty-input" id="qty-{{ $id }}" onchange="this.form.submit()">
                                <button type="button" class="cs-qty-btn" onclick="stepQty(this, 1)">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </form>

                        {{-- Item Subtotal --}}
                        <p class="cs-cart-item-subtotal">
                            Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                        </p>

                        {{-- Remove --}}
                        <form action="{{ route('cart.remove') }}" method="POST" class="cs-remove-form">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $id }}">
                            <button type="submit" class="cs-remove-btn" title="Remove item">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach

                {{-- Clear Cart --}}
                <div class="cs-cart-footer-actions">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cs-clear-btn"
                            onclick="return confirm('Clear your entire cart?')">
                            <i class="fa fa-trash-alt"></i> Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            {{-- RIGHT: Order Summary + Checkout --}}
            <div class="cs-cart-summary-col">
                <div class="cs-summary-card">

                    {{-- Summary Header --}}
                    <div class="cs-summary-header">
                        <i class="fa fa-receipt cs-summary-icon"></i>
                        <h2 class="cs-summary-title">Order Summary</h2>
                    </div>

                    {{-- Line Items --}}
                    <div class="cs-summary-lines">
                        @foreach(session('cart') as $id => $details)
                        <div class="cs-summary-line">
                            <span class="cs-summary-line-name">
                                {{ $details['name'] }}
                                <span class="cs-summary-line-qty">×{{ $details['quantity'] }}</span>
                            </span>
                            <span class="cs-summary-line-price">
                                Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <div class="cs-summary-divider"></div>

                    {{-- Subtotal + Delivery --}}
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

                    {{-- Grand Total --}}
                    <div class="cs-summary-total">
                        <span>Total</span>
                        <span class="cs-summary-total-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <div class="cs-summary-divider"></div>

                    {{-- Order Type + Checkout Form --}}
                    <form action="{{ route('checkout.setup') }}" method="POST">
                        @csrf

                        <div class="cs-order-type-section">
                            <p class="cs-order-type-label">Order Type</p>

                            @if(session()->has('table_number'))
                                {{-- Dine In locked --}}
                                <div class="cs-order-type-locked">
                                    <i class="fa fa-utensils cs-order-type-locked-icon"></i>
                                    <div>
                                        <strong>Dine In</strong>
                                        <span class="cs-order-type-table">Table {{ session('table_number') }}</span>
                                    </div>
                                    <input type="hidden" name="order_type" value="dine_in">
                                </div>
                            @else
                                {{-- Take Away / Delivery --}}
                                <div class="cs-order-type-options">
                                    <label class="cs-order-type-option" id="label_takeaway">
                                        <input type="radio" name="order_type" value="takeaway"
                                            {{ session('order_type', 'takeaway') === 'takeaway' ? 'checked' : '' }}
                                            onchange="updateOrderType()">
                                        <span class="cs-order-type-card" id="card_takeaway">
                                            <i class="fa fa-bag-shopping"></i>
                                            <span>Take Away</span>
                                        </span>
                                    </label>
                                    <label class="cs-order-type-option" id="label_delivery">
                                        <input type="radio" name="order_type" value="delivery"
                                            {{ session('order_type') === 'delivery' ? 'checked' : '' }}
                                            onchange="updateOrderType()">
                                        <span class="cs-order-type-card" id="card_delivery">
                                            <i class="fa fa-truck"></i>
                                            <span>Delivery</span>
                                        </span>
                                    </label>
                                </div>
                                <p class="cs-delivery-note" id="delivery_note" style="{{ session('order_type') === 'delivery' ? '' : 'display:none;' }}">
                                    <i class="fa fa-info-circle"></i> Delivery address required at checkout
                                </p>
                            @endif
                        </div>

                        <button type="submit" class="cs-checkout-btn">
                            Proceed to Checkout
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>

                    <a href="{{ route('products.index') }}" class="cs-continue-link">
                        <i class="fa fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>

        </div>{{-- /.cs-cart-layout --}}

        @else

        {{-- ── EMPTY CART ────────────────────────────────────────────── --}}
        <div class="cs-cart-empty">
            <div class="cs-cart-empty-icon">
                <i class="fa fa-cart-shopping"></i>
            </div>
            <h2 class="cs-cart-empty-title">Your cart is empty</h2>
            <p class="cs-cart-empty-text">Explore our menu and add your favorite drinks to get started.</p>
            <a href="{{ route('products.index') }}" class="cs-btn-primary cs-btn-lg">
                <i class="fa fa-coffee"></i> Browse Menu
            </a>
        </div>

        @endif

    </div>
</section>

@push('scripts')
<script>
    /* ── Quantity Stepper ────────────────────────────────────── */
    function stepQty(btn, delta) {
        const stepper = btn.closest('.cs-qty-stepper');
        const input   = stepper.querySelector('.cs-qty-input');
        const form    = btn.closest('.cs-qty-form');
        let val = parseInt(input.value, 10) + delta;
        if (val < 1) val = 1;
        if (val > 99) val = 99;
        input.value = val;
        form.submit();
    }

    /* ── Order Type Visual Toggle ────────────────────────────── */
    function updateOrderType() {
        const takeaway = document.querySelector('input[name="order_type"][value="takeaway"]');
        const delivery = document.querySelector('input[name="order_type"][value="delivery"]');
        const note     = document.getElementById('delivery_note');
        const cTakeaway = document.getElementById('card_takeaway');
        const cDelivery = document.getElementById('card_delivery');

        if (!takeaway || !delivery) return;

        if (takeaway.checked) {
            cTakeaway && cTakeaway.classList.add('active');
            cDelivery && cDelivery.classList.remove('active');
            note && (note.style.display = 'none');
        } else {
            cDelivery && cDelivery.classList.add('active');
            cTakeaway && cTakeaway.classList.remove('active');
            note && (note.style.display = '');
        }
    }

    document.addEventListener('DOMContentLoaded', updateOrderType);
</script>
@endpush

@endsection
