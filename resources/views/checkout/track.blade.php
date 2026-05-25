@extends('layouts.app')

@section('title', 'Track Order #' . $order->id . ' — Coffee Street')

@push('styles')
    {{-- Auto-refresh only while order is active --}}
    @if(!in_array($order->status, ['completed', 'cancelled']))
        <meta http-equiv="refresh" content="15">
    @endif
@endpush

@section('content')

{{-- ── PHASE 5: PREMIUM ORDER TRACKING ─────────────────────────── --}}
<section class="cs-track-section">
    <div class="container">
        <div class="cs-track-wrap">

            {{-- ── SUCCESS ALERT ────────────────────────────────── --}}
            @if(session('success'))
            <div class="cs-alert cs-alert-success mb-4" role="alert">
                <i class="fa fa-check-circle"></i>
                {{ session('success') }}
                <button class="cs-alert-close" onclick="this.parentElement.remove()">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            @endif

            {{-- ── PENDING PAYMENT BANNER ───────────────────────── --}}
            @if($order->payment_method === 'online' && $order->payment_status !== 'paid')
            <div class="cs-track-pay-banner">
                <div class="cs-track-pay-banner-icon">
                    <i class="fa fa-qrcode"></i>
                </div>
                <div class="cs-track-pay-banner-body">
                    <strong>Waiting for Payment</strong>
                    <p>Please complete your QRIS payment to start preparing your order.</p>
                </div>
                <a href="{{ route('checkout.payment', $order->tracking_token) }}" class="cs-track-pay-btn">
                    Complete Payment <i class="fa fa-arrow-right"></i>
                </a>
            </div>
            @endif

            {{-- ── ORDER HEADER CARD ────────────────────────────── --}}
            <div class="cs-track-header-card">
                <div class="cs-track-header-inner">
                    <div class="cs-track-header-left">
                        <span class="cs-track-label">Order Tracking</span>
                        <h1 class="cs-track-order-id">
                            <span class="cs-track-coffee-icon">☕</span>
                            Order #{{ $order->id }}
                        </h1>
                        <p class="cs-track-tagline">Track your handcrafted coffee in real-time</p>
                    </div>
                    <div class="cs-track-badges">
                        {{-- Order type badge --}}
                        @php $orderType = $order->order_type ?? 'takeaway'; @endphp
                        @if($orderType === 'dine_in')
                            <span class="cs-track-badge cs-track-badge--dinein">
                                <i class="fa fa-utensils"></i>
                                Dine In — Table {{ $order->table->table_number ?? 'N/A' }}
                            </span>
                        @elseif($orderType === 'delivery')
                            <span class="cs-track-badge cs-track-badge--delivery">
                                <i class="fa fa-truck"></i> Delivery
                            </span>
                        @else
                            <span class="cs-track-badge cs-track-badge--takeaway">
                                <i class="fa fa-bag-shopping"></i> Take Away
                            </span>
                        @endif

                        {{-- Payment method badge --}}
                        @if($order->payment_method === 'cash')
                            <span class="cs-track-badge cs-track-badge--cash">
                                <i class="fa fa-money-bill-wave"></i> Cash
                            </span>
                        @elseif($order->payment_method === 'online')
                            <span class="cs-track-badge cs-track-badge--qris">
                                <i class="fa fa-qrcode"></i> QRIS
                            </span>
                        @endif

                        {{-- Payment status badge --}}
                        @php
                            $psLabels = [
                                'pending_cash' => 'Pending Cash',
                                'unpaid'       => 'Unpaid',
                                'paid'         => 'Paid',
                                'failed'       => 'Failed',
                                'expired'      => 'Expired',
                            ];
                            $psStyles = [
                                'pending_cash' => 'cs-track-badge--warn',
                                'unpaid'       => 'cs-track-badge--danger',
                                'paid'         => 'cs-track-badge--paid',
                                'failed'       => 'cs-track-badge--danger',
                                'expired'      => 'cs-track-badge--muted',
                            ];
                            $psLabel = $psLabels[$order->payment_status] ?? ucfirst($order->payment_status);
                            $psStyle = $psStyles[$order->payment_status] ?? 'cs-track-badge--muted';
                        @endphp
                        <span class="cs-track-badge {{ $psStyle }}">{{ $psLabel }}</span>
                    </div>
                </div>
            </div>

            {{-- ── STATUS CONTEXT CARD ──────────────────────────── --}}
            @php
                $isDelivery = $orderType === 'delivery';

                $statusContexts = [
                    'pending'   => ['icon' => '⏳', 'title' => 'Order Placed',         'msg' => 'Your order has been received and is waiting in the queue.',           'color' => 'orange'],
                    'confirmed' => ['icon' => '👍', 'title' => 'Order Confirmed',       'msg' => 'Our team has confirmed your order and is preparing to brew.',          'color' => 'blue'],
                    'brewing'   => ['icon' => '☕', 'title' => 'Brewing Your Coffee',  'msg' => 'Our barista is handcrafting your drink fresh right now.',              'color' => 'orange'],
                    'preparing' => ['icon' => '☕', 'title' => 'Brewing Your Coffee',  'msg' => 'Our barista is handcrafting your drink fresh right now.',              'color' => 'orange'],
                    'processed' => ['icon' => '☕', 'title' => 'Brewing Your Coffee',  'msg' => 'Our barista is handcrafting your drink fresh right now.',              'color' => 'orange'],
                    'ready'     => ['icon' => '🔔', 'title' => 'Ready for Pickup',      'msg' => $orderType === 'dine_in' ? 'Table ' . ($order->table->table_number ?? '') . ' — your order is here! Enjoy.' : 'Please collect your warm drink at the counter.', 'color' => 'green'],
                    'on_delivery' => ['icon' => '🚚', 'title' => 'Out for Delivery',   'msg' => 'Your freshly brewed coffee is on its way to your location!',          'color' => 'blue'],
                    'completed' => ['icon' => '✅', 'title' => 'Order Completed',       'msg' => 'Thank you for choosing Coffee Street. Enjoy every sip! ☕',            'color' => 'green'],
                    'cancelled' => ['icon' => '❌', 'title' => 'Order Cancelled',       'msg' => 'This order has been cancelled. Please contact support if needed.',     'color' => 'red'],
                ];
                $ctx = $statusContexts[$order->status] ?? ['icon' => '📋', 'title' => ucfirst($order->status), 'msg' => 'Your order is being processed.', 'color' => 'muted'];
            @endphp

            <div class="cs-track-status-card cs-track-status--{{ $ctx['color'] }}">
                <span class="cs-track-status-emoji">{{ $ctx['icon'] }}</span>
                <div class="cs-track-status-body">
                    <h2 class="cs-track-status-title">{{ $ctx['title'] }}</h2>
                    <p class="cs-track-status-msg">{{ $ctx['msg'] }}</p>
                </div>
                @if(!in_array($order->status, ['completed', 'cancelled']))
                <div class="cs-track-live-dot">
                    <span class="cs-track-dot-ring"></span>
                    <span class="cs-track-dot-core"></span>
                </div>
                @endif
            </div>

            {{-- ── PROGRESS STEPPER ─────────────────────────────── --}}
            @php
                if ($isDelivery) {
                    $steps = [
                        ['status' => 'pending',     'label' => 'Placed',    'icon' => 'fa-clipboard-list', 'aliases' => []],
                        ['status' => 'confirmed',   'label' => 'Confirmed', 'icon' => 'fa-thumbs-up',      'aliases' => []],
                        ['status' => 'brewing',     'label' => 'Brewing',   'icon' => 'fa-mug-hot',        'aliases' => ['preparing', 'processed']],
                        ['status' => 'ready',       'label' => 'Ready',     'icon' => 'fa-bell',           'aliases' => []],
                        ['status' => 'on_delivery', 'label' => 'Shipping',  'icon' => 'fa-truck',          'aliases' => []],
                        ['status' => 'completed',   'label' => 'Done',      'icon' => 'fa-circle-check',   'aliases' => []],
                    ];
                } else {
                    $steps = [
                        ['status' => 'pending',   'label' => 'Placed',    'icon' => 'fa-clipboard-list', 'aliases' => []],
                        ['status' => 'confirmed', 'label' => 'Confirmed', 'icon' => 'fa-thumbs-up',      'aliases' => []],
                        ['status' => 'brewing',   'label' => 'Brewing',   'icon' => 'fa-mug-hot',        'aliases' => ['preparing', 'processed']],
                        ['status' => 'ready',     'label' => 'Ready',     'icon' => 'fa-bell',           'aliases' => []],
                        ['status' => 'completed', 'label' => 'Done',      'icon' => 'fa-circle-check',   'aliases' => []],
                    ];
                }

                $currentIdx = 0;
                foreach ($steps as $idx => $step) {
                    if ($order->status === $step['status'] || in_array($order->status, $step['aliases'])) {
                        $currentIdx = $idx;
                        break;
                    }
                }
                if ($order->status === 'completed') {
                    $currentIdx = count($steps) - 1;
                }
                $progressPercent = $order->status === 'cancelled' ? 0 : (($currentIdx / max(count($steps) - 1, 1)) * 100);
            @endphp

            @if($order->status === 'cancelled')
                <div class="cs-track-cancelled-card">
                    <div class="cs-track-cancelled-icon">
                        <i class="fa fa-circle-xmark"></i>
                    </div>
                    <div>
                        <strong>Order Cancelled</strong>
                        <p>This order was cancelled and cannot be tracked further. Please contact our support if you have questions.</p>
                    </div>
                </div>
            @else
                <div class="cs-track-stepper-card">
                    <div class="cs-stepper">
                        {{-- Progress track --}}
                        <div class="cs-stepper-track">
                            <div class="cs-stepper-fill" style="width: {{ $progressPercent }}%;"></div>
                        </div>

                        {{-- Steps --}}
                        <div class="cs-stepper-steps">
                            @foreach($steps as $idx => $step)
                            @php
                                $isFinished = $idx < $currentIdx;
                                $isActive   = $idx === $currentIdx;
                                $stateClass = $isFinished ? 'cs-step--done' : ($isActive ? 'cs-step--active' : 'cs-step--upcoming');
                            @endphp
                            <div class="cs-step {{ $stateClass }}">
                                <div class="cs-step-icon">
                                    @if($isFinished)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa {{ $step['icon'] }}"></i>
                                    @endif
                                </div>
                                <span class="cs-step-label">{{ $step['label'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Live indicator --}}
                    @if(!in_array($order->status, ['completed', 'cancelled']))
                    <div class="cs-track-live-row">
                        <span class="cs-track-live-spinner"></span>
                        <span class="cs-track-live-text">Auto-refreshing every 15 seconds…</span>
                    </div>
                    @endif
                </div>
            @endif

            {{-- ── ORDER SUMMARY CARD ───────────────────────────── --}}
            <div class="cs-track-items-card">
                <div class="cs-track-items-header">
                    <i class="fa fa-receipt cs-track-items-icon"></i>
                    <h2 class="cs-track-items-title">Order Summary</h2>
                </div>

                <div class="cs-track-items-body">
                    @foreach($order->items as $item)
                    <div class="cs-track-item-row">
                        <div class="cs-track-item-info">
                            <span class="cs-track-item-name">{{ $item->product->name ?? 'Unknown Product' }}</span>
                            <span class="cs-track-item-unit">Rp {{ number_format($item->price, 0, ',', '.') }} / item</span>
                        </div>
                        <span class="cs-track-item-qty">×{{ $item->quantity }}</span>
                        <span class="cs-track-item-subtotal">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                    @endforeach

                    @if($order->notes)
                    <div class="cs-track-notes-box">
                        <i class="fa fa-sticky-note"></i>
                        <span>{{ $order->notes }}</span>
                    </div>
                    @endif

                    <div class="cs-track-total-row">
                        <span>Total Amount</span>
                        <span class="cs-track-total-price">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- ── ACTION BUTTONS ───────────────────────────────── --}}
            <div class="cs-track-actions">
                @if($order->status === 'completed' || $order->payment_status === 'paid')
                <a href="{{ route('order.receipt', $order->tracking_token) }}" target="_blank" class="cs-btn-primary">
                    <i class="fa fa-receipt"></i> View Receipt
                </a>
                @endif
                <a href="{{ route('products.index') }}" class="cs-btn-ghost">
                    <i class="fa fa-arrow-left"></i> Back to Menu
                </a>
            </div>

        </div>{{-- /.cs-track-wrap --}}
    </div>
</section>

@push('styles')
<style>
/* ── PHASE 5: TRACK PAGE STYLES ──────────────────────────────── */

.cs-track-section {
    padding: 3rem 0 5rem;
    min-height: 80vh;
    background: #FDFAF6;
}

.cs-track-wrap {
    max-width: 720px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* ── Payment Banner ─────────────────────────────────────────── */
.cs-track-pay-banner {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.1rem 1.4rem;
    background: #FFFBEB;
    border: 1.5px solid #FDE68A;
    border-radius: 16px;
    flex-wrap: wrap;
}
.cs-track-pay-banner-icon {
    width: 44px;
    height: 44px;
    background: #FEF3C7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #D97706;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.cs-track-pay-banner-body { flex: 1; min-width: 160px; }
.cs-track-pay-banner-body strong { display: block; color: #92400E; font-size: 0.9rem; font-weight: 700; }
.cs-track-pay-banner-body p  { color: #B45309; font-size: 0.8rem; margin: 0.2rem 0 0; }
.cs-track-pay-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #D97706;
    color: #fff;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 0.55rem 1.1rem;
    border-radius: 50px;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.2s, transform 0.15s;
}
.cs-track-pay-btn:hover { background: #B45309; transform: translateY(-1px); color: #fff; }

/* ── Header Card ─────────────────────────────────────────────── */
.cs-track-header-card {
    background: linear-gradient(135deg, #2F2105 0%, #1A1200 100%);
    border-radius: 20px;
    padding: 2rem 2.25rem;
    position: relative;
    overflow: hidden;
}
.cs-track-header-card::after {
    content: '☕';
    position: absolute;
    right: 2rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 6rem;
    opacity: 0.07;
    pointer-events: none;
    line-height: 1;
}
.cs-track-header-inner {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1.25rem;
    flex-wrap: wrap;
}
.cs-track-header-left { flex: 1; min-width: 0; }
.cs-track-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.45);
    margin-bottom: 0.35rem;
}
.cs-track-coffee-icon { margin-right: 0.3rem; }
.cs-track-order-id {
    font-size: clamp(1.4rem, 4vw, 2rem);
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.35rem;
    letter-spacing: -0.02em;
}
.cs-track-tagline {
    font-size: 0.82rem;
    color: rgba(255,255,255,0.5);
    margin: 0;
}
.cs-track-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
    align-items: flex-start;
    padding-top: 0.25rem;
}
.cs-track-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}
.cs-track-badge--dinein   { background: rgba(255,144,42,0.2); color: #FFB366; border: 1px solid rgba(255,144,42,0.3); }
.cs-track-badge--delivery { background: rgba(96,165,250,0.2); color: #93C5FD; border: 1px solid rgba(96,165,250,0.3); }
.cs-track-badge--takeaway { background: rgba(134,239,172,0.2);color: #86EFAC; border: 1px solid rgba(134,239,172,0.3);}
.cs-track-badge--cash     { background: rgba(134,239,172,0.2);color: #86EFAC; border: 1px solid rgba(134,239,172,0.3);}
.cs-track-badge--qris     { background: rgba(96,165,250,0.2); color: #93C5FD; border: 1px solid rgba(96,165,250,0.3); }
.cs-track-badge--paid     { background: rgba(134,239,172,0.2);color: #86EFAC; border: 1px solid rgba(134,239,172,0.3);}
.cs-track-badge--warn     { background: rgba(253,230,138,0.2);color: #FDE68A; border: 1px solid rgba(253,230,138,0.3);}
.cs-track-badge--danger   { background: rgba(252,165,165,0.2);color: #FCA5A5; border: 1px solid rgba(252,165,165,0.3);}
.cs-track-badge--muted    { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); border: 1px solid rgba(255,255,255,0.15); }

/* ── Status Context Card ─────────────────────────────────────── */
.cs-track-status-card {
    display: flex;
    align-items: center;
    gap: 1.1rem;
    padding: 1.25rem 1.5rem;
    border-radius: 16px;
    border: 1.5px solid transparent;
    position: relative;
}
.cs-track-status--orange { background: #FFF9F2; border-color: #FFD28F; }
.cs-track-status--blue   { background: #EFF6FF; border-color: #93C5FD; }
.cs-track-status--green  { background: #F0FDF4; border-color: #86EFAC; }
.cs-track-status--red    { background: #FEF2F2; border-color: #FECACA; }
.cs-track-status--muted  { background: #F9FAFB; border-color: #E5E7EB; }

.cs-track-status-emoji { font-size: 2rem; flex-shrink: 0; line-height: 1; }
.cs-track-status-body  { flex: 1; min-width: 0; }
.cs-track-status-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--cs-dark);
    margin: 0 0 0.2rem;
}
.cs-track-status-msg {
    font-size: 0.85rem;
    color: var(--cs-muted);
    margin: 0;
}

/* Live dot animation */
.cs-track-live-dot {
    position: relative;
    width: 12px;
    height: 12px;
    flex-shrink: 0;
}
.cs-track-dot-ring,
.cs-track-dot-core {
    position: absolute;
    inset: 0;
    border-radius: 50%;
}
.cs-track-dot-core {
    background: var(--cs-orange);
    width: 8px;
    height: 8px;
    top: 2px;
    left: 2px;
}
.cs-track-dot-ring {
    background: rgba(255,144,42,0.3);
    animation: cs-ping 1.5s cubic-bezier(0,0,0.2,1) infinite;
}
@keyframes cs-ping {
    75%, 100% { transform: scale(2); opacity: 0; }
}

/* ── Stepper Card ────────────────────────────────────────────── */
.cs-track-stepper-card {
    background: #fff;
    border: 1px solid #F0E8DC;
    border-radius: 20px;
    padding: 2rem 1.75rem 1.5rem;
    box-shadow: 0 1px 12px rgba(47,33,5,0.06);
}

/* Horizontal stepper */
.cs-stepper {
    position: relative;
    padding-bottom: 0.5rem;
}
.cs-stepper-track {
    position: absolute;
    top: 27px;
    left: 27px;
    right: 27px;
    height: 4px;
    background: #EDE0D4;
    border-radius: 4px;
    z-index: 0;
}
.cs-stepper-fill {
    height: 100%;
    background: linear-gradient(90deg, #198754, #22C55E);
    border-radius: 4px;
    transition: width 0.6s ease;
}
.cs-stepper-steps {
    display: flex;
    justify-content: space-between;
    position: relative;
    z-index: 1;
}
.cs-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
    text-align: center;
}
/* Step icon */
.cs-step-icon {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    border: 3px solid #fff;
    transition: all 0.3s ease;
}
/* Step label */
.cs-step-label {
    font-size: 0.72rem;
    font-weight: 600;
    transition: color 0.3s;
}

/* Done steps */
.cs-step--done .cs-step-icon {
    background: #198754;
    color: #fff;
    box-shadow: 0 2px 8px rgba(25,135,84,0.25);
}
.cs-step--done .cs-step-label { color: #198754; }

/* Active step */
.cs-step--active .cs-step-icon {
    background: var(--cs-orange);
    color: #fff;
    box-shadow: 0 0 0 6px rgba(255,144,42,0.18);
    animation: cs-pulse-step 2s infinite;
}
.cs-step--active .cs-step-label {
    color: var(--cs-orange);
    font-weight: 700;
    font-size: 0.78rem;
}
@keyframes cs-pulse-step {
    0%   { box-shadow: 0 0 0 0   rgba(255,144,42,0.35); }
    70%  { box-shadow: 0 0 0 10px rgba(255,144,42,0);   }
    100% { box-shadow: 0 0 0 0   rgba(255,144,42,0);    }
}

/* Upcoming steps */
.cs-step--upcoming .cs-step-icon {
    background: #F0ECE6;
    color: #C0B0A0;
}
.cs-step--upcoming .cs-step-label { color: #C0B0A0; }

/* Live row */
.cs-track-live-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1.5rem;
    padding-top: 1.25rem;
    border-top: 1px solid #F5F0E8;
}
.cs-track-live-spinner {
    width: 8px;
    height: 8px;
    background: var(--cs-orange);
    border-radius: 50%;
    display: inline-block;
    animation: cs-spin-pulse 1.4s ease-in-out infinite;
}
@keyframes cs-spin-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.4; transform: scale(0.7); }
}
.cs-track-live-text {
    font-size: 0.78rem;
    color: var(--cs-muted);
}

/* ── Cancelled Card ──────────────────────────────────────────── */
.cs-track-cancelled-card {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    background: #FEF2F2;
    border: 1.5px solid #FECACA;
    border-radius: 16px;
}
.cs-track-cancelled-icon {
    width: 44px;
    height: 44px;
    background: #FEE2E2;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #EF4444;
    font-size: 1.15rem;
    flex-shrink: 0;
}
.cs-track-cancelled-card strong { display: block; color: #B91C1C; font-weight: 700; margin-bottom: 0.25rem; }
.cs-track-cancelled-card p { color: #991B1B; font-size: 0.85rem; margin: 0; }

/* ── Order Summary Card ──────────────────────────────────────── */
.cs-track-items-card {
    background: #fff;
    border: 1px solid #F0E8DC;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 12px rgba(47,33,5,0.06);
}
.cs-track-items-header {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid #F5F0E8;
    background: #FDFAF6;
}
.cs-track-items-icon {
    width: 32px;
    height: 32px;
    background: var(--cs-cream);
    color: var(--cs-orange);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
}
.cs-track-items-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--cs-dark);
    margin: 0;
}
.cs-track-items-body {
    padding: 1.25rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.cs-track-item-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 0;
    border-bottom: 1px solid #F5F0E8;
}
.cs-track-item-row:last-of-type { border-bottom: none; }
.cs-track-item-info { flex: 1; min-width: 0; }
.cs-track-item-name {
    display: block;
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--cs-dark);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.cs-track-item-unit {
    display: block;
    font-size: 0.75rem;
    color: var(--cs-muted);
}
.cs-track-item-qty {
    font-size: 0.82rem;
    color: var(--cs-muted);
    white-space: nowrap;
    flex-shrink: 0;
}
.cs-track-item-subtotal {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--cs-dark);
    white-space: nowrap;
    flex-shrink: 0;
}

.cs-track-notes-box {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    padding: 0.7rem 0.9rem;
    background: #FFFBF5;
    border: 1px solid #FFE8C8;
    border-radius: 10px;
    font-size: 0.82rem;
    color: #6B4E37;
    font-style: italic;
}
.cs-track-notes-box i { color: var(--cs-orange); margin-top: 2px; flex-shrink: 0; }

.cs-track-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0 0;
    border-top: 1px solid #F0E8DC;
    margin-top: 0.25rem;
}
.cs-track-total-row span:first-child {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--cs-dark);
}
.cs-track-total-price {
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--cs-orange);
    letter-spacing: -0.02em;
}

/* ── Action Buttons ──────────────────────────────────────────── */
.cs-track-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    flex-wrap: wrap;
    padding-top: 0.5rem;
}
.cs-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.75rem 1.75rem;
    background: var(--cs-orange);
    color: #fff;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 700;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    box-shadow: 0 4px 16px rgba(255,144,42,0.3);
}
.cs-btn-primary:hover {
    background: var(--cs-orange-hover);
    transform: translateY(-1px);
    box-shadow: 0 6px 22px rgba(255,144,42,0.4);
    color: #fff;
}
.cs-btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.75rem 1.75rem;
    background: #fff;
    color: var(--cs-muted);
    border: 1.5px solid #E8DDD0;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: border-color 0.2s, color 0.2s;
}
.cs-btn-ghost:hover { border-color: var(--cs-orange); color: var(--cs-orange); }

/* ── Responsive ─────────────────────────────────────────────── */
@media (max-width: 768px) {
    .cs-track-section { padding: 1.75rem 0 4rem; }
    .cs-track-header-card { padding: 1.5rem 1.25rem; }
    .cs-track-header-card::after { display: none; }
    .cs-track-order-id { font-size: 1.4rem; }
    .cs-track-badges { padding-top: 0.75rem; }
    .cs-track-stepper-card { padding: 1.5rem 1rem 1.25rem; }

    /* Mobile: vertical stepper */
    .cs-stepper-track { display: none; }
    .cs-stepper-steps {
        flex-direction: column;
        gap: 0;
        align-items: flex-start;
    }
    .cs-step {
        flex-direction: row;
        align-items: center;
        gap: 1rem;
        text-align: left;
        flex: none;
        width: 100%;
        padding: 0.6rem 0;
        position: relative;
    }
    .cs-step:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 26px;
        top: 60px;
        width: 2px;
        height: calc(100% - 14px);
        background: #EDE0D4;
        z-index: 0;
    }
    .cs-step--done:not(:last-child)::after { background: #198754; }
    .cs-step-icon {
        width: 48px;
        height: 48px;
        font-size: 1rem;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }
    .cs-step-label {
        font-size: 0.85rem;
    }
    .cs-track-items-header { padding: 1rem 1.1rem; }
    .cs-track-items-body   { padding: 1rem 1.1rem; }
    .cs-track-status-card  { padding: 1rem 1.1rem; }
}

@media (max-width: 480px) {
    .cs-track-header-inner { flex-direction: column; }
    .cs-track-pay-banner { flex-direction: column; align-items: flex-start; }
    .cs-track-actions { flex-direction: column; }
    .cs-btn-primary, .cs-btn-ghost { width: 100%; justify-content: center; }
}
</style>
@endpush

@endsection
