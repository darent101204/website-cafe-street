<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $order->id }} — Coffee Street</title>
    <style>
        /* ===== BASE ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            background: #ffffff;
            line-height: 1.4;
        }

        /* ===== PAGE FRAME ===== */
        .page {
            padding: 44px 48px 36px 48px;
            position: relative;
        }

        /* ===== PAID DIAGONAL WATERMARK ===== */
        .watermark {
            position: fixed;
            top: 38%;
            left: 22%;
            font-size: 72px;
            font-weight: 900;
            color: #198754;
            opacity: 0.07;
            transform: rotate(-35deg);
            letter-spacing: 6px;
            text-transform: uppercase;
            z-index: 0;
        }

        /* ===== TOP ACCENT BAR ===== */
        .accent-bar {
            width: 100%;
            height: 6px;
            background-color: #FF902A;
            margin-bottom: 32px;
        }

        /* ===== HEADER ROW ===== */
        .header-table { width: 100%; }
        .header-table td { vertical-align: top; }
        .header-right  { text-align: right; }

        .brand-name {
            font-size: 28px;
            font-weight: 900;
            color: #1a1a1a;
            letter-spacing: -1px;
        }
        .brand-accent { color: #FF902A; }

        .brand-sub {
            font-size: 9px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #adb5bd;
            margin-top: 3px;
        }

        .invoice-word {
            font-size: 32px;
            font-weight: 900;
            color: #2f2f2f;
            letter-spacing: -1.5px;
        }

        .invoice-ref {
            font-size: 10px;
            color: #6b6b6b;
            margin-top: 5px;
            line-height: 1.7;
        }
        .invoice-ref strong { color: #2f2f2f; }

        /* ===== ORANGE DIVIDER ===== */
        .hr-orange {
            border: none;
            border-top: 2px solid #FF902A;
            margin: 22px 0;
        }
        .hr-light {
            border: none;
            border-top: 1px solid #e8e8e8;
            margin: 14px 0;
        }

        /* ===== INFO CARDS ROW ===== */
        .cards-table { width: 100%; margin-bottom: 28px; }
        .cards-table td { vertical-align: top; }

        .info-card {
            background: #f8f8f6;
            border-left: 3px solid #FF902A;
            padding: 12px 14px;
            border-radius: 4px;
        }
        .info-card-right {
            background: #f8f8f6;
            border-left: 3px solid #2f2f2f;
            padding: 12px 14px;
            border-radius: 4px;
        }

        .card-label {
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #adb5bd;
            margin-bottom: 6px;
        }
        .card-name {
            font-size: 13px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 3px;
        }
        .card-detail {
            font-size: 10px;
            color: #6b6b6b;
            line-height: 1.6;
        }

        /* ===== ORDER META INLINE ===== */
        .meta-table { width: 100%; margin-bottom: 4px; }
        .meta-table td { font-size: 10px; color: #6b6b6b; padding: 2px 0; }
        .meta-table td strong { color: #2f2f2f; font-weight: 600; }

        /* ===== STATUS BADGE ===== */
        .badge {
            display: inline-block;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 3px 9px;
            border-radius: 20px;
        }
        .badge-paid     { background: #d1fae5; color: #065f46; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-unpaid   { background: #fee2e2; color: #991b1b; }
        .badge-failed   { background: #fee2e2; color: #991b1b; }
        .badge-expired  { background: #f3f4f6; color: #4b5563; }

        /* ===== SECTION TITLE ===== */
        .section-title {
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #adb5bd;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid #f0f0f0;
        }

        /* ===== ITEMS TABLE ===== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .items-table thead tr {
            background-color: #2f2f2f;
        }
        .items-table thead th {
            color: #ffffff;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 9px 12px;
            text-align: left;
        }
        .items-table thead th.right { text-align: right; }
        .items-table thead th.center { text-align: center; }

        .items-table tbody tr { border-bottom: 1px solid #f0f0f0; }
        .items-table tbody tr.even { background-color: #fdfcfb; }

        .items-table tbody td {
            padding: 10px 12px;
            font-size: 10.5px;
            color: #2f2f2f;
            vertical-align: middle;
        }
        .items-table tbody td.center { text-align: center; color: #6b6b6b; }
        .items-table tbody td.right  { text-align: right; }
        .items-table tbody td.subtotal { text-align: right; font-weight: 700; color: #1a1a1a; }

        .item-name { font-weight: 600; color: #1a1a1a; }

        /* ===== TOTALS SECTION ===== */
        .totals-outer { width: 100%; margin-top: 18px; }
        .totals-spacer { width: 52%; }
        .totals-box {
            width: 48%;
            background: #f8f8f6;
            border-radius: 6px;
            padding: 14px 16px;
        }

        .totals-row { width: 100%; margin-bottom: 6px; }
        .totals-row td {
            font-size: 10.5px;
            color: #6b6b6b;
            padding: 2px 0;
        }
        .totals-row td.right { text-align: right; color: #2f2f2f; }

        .totals-grand { width: 100%; border-top: 2px solid #2f2f2f; padding-top: 10px; margin-top: 8px; }
        .totals-grand td.label { font-size: 12px; font-weight: 800; color: #1a1a1a; }
        .totals-grand td.amount { text-align: right; font-size: 16px; font-weight: 900; color: #FF902A; }

        /* ===== NOTES ===== */
        .notes-block {
            background: #fffbf5;
            border-left: 3px solid #FF902A;
            padding: 8px 14px;
            font-size: 10px;
            color: #6b6b6b;
            font-style: italic;
            margin: 16px 0 0 0;
            border-radius: 0 4px 4px 0;
        }
        .notes-block strong { color: #FF902A; font-style: normal; font-weight: 700; }

        /* ===== BOTTOM ACCENT ===== */
        .bottom-bar {
            width: 100%;
            height: 4px;
            background-color: #FF902A;
            margin-top: 32px;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 12px;
            text-align: center;
        }
        .footer-main {
            font-size: 11px;
            font-weight: 700;
            color: #2f2f2f;
        }
        .footer-sub {
            font-size: 8.5px;
            color: #adb5bd;
            margin-top: 4px;
            line-height: 1.6;
        }
        .footer-divider {
            display: inline-block;
            margin: 0 6px;
            color: #dee2e6;
        }
    </style>
</head>
<body>

@php
    $ps         = $order->payment_status ?? 'unknown';
    $orderType  = $order->order_type ?? 'takeaway';
    $psLabels   = ['paid'=>'Paid','pending_cash'=>'Pending Cash','unpaid'=>'Unpaid','failed'=>'Failed','expired'=>'Expired'];
    $psClasses  = ['paid'=>'badge-paid','pending_cash'=>'badge-pending','unpaid'=>'badge-unpaid','failed'=>'badge-failed','expired'=>'badge-expired'];
    $psLabel    = $psLabels[$ps] ?? ucfirst($ps);
    $psClass    = $psClasses[$ps] ?? 'badge-expired';
    $invoiceNo  = 'INV-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
    $payLabel   = ($order->payment_method ?? '') === 'online' ? 'QRIS / Online' : 'Cash at Cashier';
    $typeLabel  = match($orderType) { 'dine_in' => 'Dine In', 'delivery' => 'Delivery', default => 'Takeaway' };
@endphp

@if($ps === 'paid')
    <div class="watermark">PAID</div>
@endif

<div class="page">

    <!-- Top accent bar -->
    <div class="accent-bar"></div>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width:55%;">
                <div class="brand-name">&#9749; Coffee <span class="brand-accent">Street</span></div>
                <div class="brand-sub">Official Tax Invoice</div>
            </td>
            <td class="header-right" style="width:45%;">
                <div class="invoice-word">INVOICE</div>
                <div class="invoice-ref">
                    <strong>{{ $invoiceNo }}</strong><br>
                    Date: {{ $order->created_at->format('d M Y') }}<br>
                    Time: {{ $order->created_at->format('H:i') }} WIB
                </div>
            </td>
        </tr>
    </table>

    <div class="hr-orange"></div>

    <!-- Bill To & Order Info Cards -->
    <table class="cards-table">
        <tr>
            <td style="width: 48%; padding-right: 12px;">
                <div class="info-card">
                    <div class="card-label">Bill To</div>
                    <div class="card-name">{{ $order->name ?? '—' }}</div>
                    <div class="card-detail">
                        {{ $order->phone ?? '—' }}<br>
                        @if($orderType === 'dine_in' && $order->table)
                            Table {{ $order->table->table_number }}
                        @elseif($orderType === 'delivery' && !empty($order->address))
                            {{ $order->address }}
                        @else
                            Takeaway / Walk-in
                        @endif
                    </div>
                </div>
            </td>
            <td style="width: 52%;">
                <div class="info-card-right">
                    <div class="card-label">Order Details</div>
                    <table class="meta-table">
                        <tr>
                            <td style="width:45%;"><strong>Order ID</strong></td>
                            <td>#{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Type</strong></td>
                            <td>{{ $typeLabel }}{{ ($orderType === 'dine_in' && $order->table) ? ' — Table '.$order->table->table_number : '' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Payment</strong></td>
                            <td>{{ $payLabel }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td><span class="badge {{ $psClass }}">{{ $psLabel }}</span></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Items Section -->
    <div class="section-title">Items Ordered</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:46%; text-align:left;">Item Description</th>
                <th class="center" style="width:10%;">Qty</th>
                <th class="right" style="width:22%; text-align:right;">Unit Price</th>
                <th class="right" style="width:22%; text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
                <tr class="{{ $i % 2 === 1 ? 'even' : '' }}">
                    <td class="item-name">{{ $item->product->name ?? 'Unknown Item' }}</td>
                    <td class="center">{{ $item->quantity }}</td>
                    <td class="right">Rp {{ number_format($item->price * 1000, 0, ',', '.') }}</td>
                    <td class="subtotal">Rp {{ number_format($item->price * $item->quantity * 1000, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if(!empty($order->notes))
        <div class="notes-block"><strong>Note:</strong> "{{ $order->notes }}"</div>
    @endif

    <!-- Totals -->
    <table class="totals-outer">
        <tr>
            <td class="totals-spacer"></td>
            <td class="totals-box">
                <table class="totals-row" style="width:100%;">
                    <tr><td>Subtotal</td><td class="right">Rp {{ number_format($order->total_price * 1000, 0, ',', '.') }}</td></tr>
                    <tr><td>Tax (0%)</td><td class="right">Rp 0</td></tr>
                    <tr><td>Delivery</td><td class="right">Free</td></tr>
                </table>
                <table class="totals-grand" style="width:100%;">
                    <tr>
                        <td class="label">Total Due</td>
                        <td class="amount">Rp {{ number_format($order->total_price * 1000, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Bottom bar & Footer -->
    <div class="bottom-bar"></div>
    <div class="footer">
        <div class="footer-main">Thank you for ordering at Coffee Street &#9749;</div>
        <div class="footer-sub">
            This is a computer-generated invoice and requires no signature.
            <span class="footer-divider">|</span>
            Generated: {{ now()->format('d M Y, H:i') }} WIB
            <span class="footer-divider">|</span>
            {{ $invoiceNo }}
        </div>
    </div>

</div>
</body>
</html>
