<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order->id }} — Coffee Street</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0ede8;
            min-height: 100vh;
            padding: 40px 16px;
            color: #1a1a1a;
        }

        /* ===== PAGE WRAPPER ===== */
        .receipt-page {
            max-width: 800px;
            margin: 0 auto;
        }

        /* ===== PRINT BUTTON BAR ===== */
        .action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-bottom: 20px;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #2f2f2f;
            color: #ffffff;
            border: none;
            padding: 10px 22px;
            border-radius: 50px;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.15s ease;
        }
        .btn-print:hover {
            background-color: #FF902A;
            transform: translateY(-1px);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: transparent;
            color: #6b6b6b;
            border: 1.5px solid #d4d0cb;
            padding: 10px 22px;
            border-radius: 50px;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: border-color 0.2s ease, color 0.2s ease;
        }
        .btn-back:hover {
            border-color: #2f2f2f;
            color: #2f2f2f;
        }

        /* ===== RECEIPT CARD ===== */
        .receipt-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.04), 0 20px 60px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* ===== HEADER ===== */
        .receipt-header {
            background: linear-gradient(135deg, #2f2f2f 0%, #1a1a1a 100%);
            color: #ffffff;
            padding: 40px 48px;
            position: relative;
            overflow: hidden;
        }
        .receipt-header::before {
            content: '☕';
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 6rem;
            opacity: 0.07;
            pointer-events: none;
        }

        .brand-logo {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }
        .brand-logo span { color: #FF902A; }

        .brand-subtitle {
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #adb5bd;
            margin-bottom: 28px;
        }

        .receipt-meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .meta-item {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 50px;
            padding: 5px 14px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #e9ecef;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .meta-order-id {
            font-size: 1rem;
            font-weight: 700;
            color: #ffffff;
            background: rgba(255, 144, 42, 0.25);
            border-color: rgba(255, 144, 42, 0.4);
        }

        /* ===== PAID BADGE ===== */
        .paid-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #198754, #20c997);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 50px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* ===== BODY SECTIONS ===== */
        .receipt-body {
            padding: 36px 48px;
        }

        .section {
            margin-bottom: 32px;
        }

        .section-title {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #adb5bd;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        /* ===== CUSTOMER INFO GRID ===== */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }

        .info-cell label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #adb5bd;
            margin-bottom: 4px;
        }
        .info-cell p {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1a1a1a;
        }

        /* ===== ITEMS TABLE ===== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead tr {
            border-bottom: 2px solid #f0f0f0;
        }

        .items-table th {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #adb5bd;
            padding: 10px 12px;
            text-align: left;
        }
        .items-table th:last-child,
        .items-table td:last-child { text-align: right; }
        .items-table th:nth-child(2),
        .items-table td:nth-child(2) { text-align: center; }
        .items-table th:nth-child(3),
        .items-table td:nth-child(3) { text-align: right; }

        .items-table tbody tr {
            border-bottom: 1px solid #f7f7f7;
            transition: background 0.15s;
        }
        .items-table tbody tr:hover { background: #fafafa; }
        .items-table tbody tr:last-child { border-bottom: none; }

        .items-table td {
            padding: 14px 12px;
            font-size: 0.9rem;
            color: #2f2f2f;
        }

        .item-name {
            font-weight: 600;
            color: #1a1a1a;
        }

        /* ===== SUMMARY SECTION ===== */
        .summary-wrapper {
            display: flex;
            justify-content: flex-end;
        }

        .summary-box {
            min-width: 280px;
            background: #fafafa;
            border-radius: 14px;
            padding: 20px 24px;
            border: 1px solid #f0f0f0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.875rem;
            color: #6b6b6b;
            padding: 6px 0;
        }

        .summary-divider {
            border: none;
            border-top: 1px solid #e8e8e8;
            margin: 10px 0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0 0;
        }

        .summary-total .label {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a1a;
        }
        .summary-total .amount {
            font-size: 1.4rem;
            font-weight: 800;
            color: #FF902A;
            letter-spacing: -0.5px;
        }

        /* ===== NOTES ===== */
        .notes-box {
            background: #fffbf5;
            border: 1px solid #ffe8c8;
            border-radius: 10px;
            padding: 14px 18px;
            font-size: 0.875rem;
            color: #6b6b6b;
            font-style: italic;
        }
        .notes-box strong { color: #FF902A; font-style: normal; }

        /* ===== DIVIDER ===== */
        .dashed-divider {
            border: none;
            border-top: 2px dashed #e8e8e8;
            margin: 32px 0;
        }

        /* ===== FOOTER ===== */
        .receipt-footer {
            background: #fafafa;
            border-top: 1px solid #f0f0f0;
            padding: 24px 48px;
            text-align: center;
        }

        .footer-main {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2f2f2f;
            margin-bottom: 6px;
        }

        .footer-sub {
            font-size: 0.75rem;
            color: #adb5bd;
        }

        /* ===== PAYMENT STATUS BADGE COLORS ===== */
        .status-paid     { background: #d1fae5; color: #065f46; }
        .status-pending  { background: #fef3c7; color: #92400e; }
        .status-unpaid   { background: #fee2e2; color: #991b1b; }
        .status-failed   { background: #fee2e2; color: #991b1b; }
        .status-expired  { background: #f3f4f6; color: #4b5563; }
        .status-default  { background: #f3f4f6; color: #4b5563; }

        .status-badge {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 50px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            body { padding: 16px 8px; }
            .receipt-header { padding: 28px 24px; }
            .receipt-body  { padding: 24px 24px; }
            .receipt-footer{ padding: 20px 24px; }
            .receipt-header::before { display: none; }
            .summary-wrapper { justify-content: stretch; }
            .summary-box { min-width: 100%; width: 100%; }
            .info-grid { grid-template-columns: 1fr; }
        }

        /* ===== PRINT ===== */
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .action-bar { display: none !important; }
            .receipt-card {
                box-shadow: none;
                border-radius: 0;
            }
            .receipt-header::before { opacity: 0.04; }
            .items-table tbody tr:hover { background: transparent; }
        }
    </style>
</head>
<body>

<div class="receipt-page">

    <!-- Action Bar (hidden on print) -->
    <div class="action-bar">
        <a href="javascript:history.back()" class="btn-back">← Back</a>
        <a href="{{ route('order.invoice', $order->tracking_token) }}" class="btn-print" style="text-decoration:none;">⬇️ Download Invoice</a>
        <button class="btn-print" onclick="window.print()">🖨️ Print Receipt</button>
    </div>

    <!-- Receipt Card -->
    <div class="receipt-card">

        <!-- ===== HEADER ===== -->
        <div class="receipt-header">
            <div class="brand-logo">☕ Coffee <span>Street</span></div>
            <div class="brand-subtitle">Official Order Receipt</div>

            <div class="receipt-meta-row">
                <span class="meta-item meta-order-id">Order #{{ $order->id }}</span>
                <span class="meta-item">🗓 {{ $order->created_at->format('d M Y, H:i') }}</span>

                @php
                    $orderType = $order->order_type ?? 'takeaway';
                @endphp
                @if($orderType === 'dine_in')
                    <span class="meta-item">🍽️ Dine In{{ $order->table ? ' — Table ' . $order->table->table_number : '' }}</span>
                @elseif($orderType === 'delivery')
                    <span class="meta-item">🛵 Delivery</span>
                @else
                    <span class="meta-item">🥡 Takeaway</span>
                @endif

                @if(($order->payment_method ?? '') === 'cash')
                    <span class="meta-item">💵 Cash</span>
                @elseif(($order->payment_method ?? '') === 'online')
                    <span class="meta-item">📱 QRIS</span>
                @endif

                @php
                    $ps = $order->payment_status ?? 'unknown';
                    $psLabels = [
                        'paid'         => 'Paid',
                        'pending_cash' => 'Pending Cash',
                        'unpaid'       => 'Unpaid',
                        'failed'       => 'Failed',
                        'expired'      => 'Expired',
                    ];
                    $psClasses = [
                        'paid'         => 'status-paid',
                        'pending_cash' => 'status-pending',
                        'unpaid'       => 'status-unpaid',
                        'failed'       => 'status-failed',
                        'expired'      => 'status-expired',
                    ];
                    $psLabel = $psLabels[$ps] ?? ucfirst($ps);
                    $psClass = $psClasses[$ps] ?? 'status-default';
                @endphp
                <span class="status-badge {{ $psClass }}">{{ $psLabel }}</span>

                @if(($order->payment_method ?? '') === 'online' && $ps === 'paid')
                    <span class="paid-badge">✅ Paid via QRIS</span>
                @endif
            </div>
        </div>
        <!-- /HEADER -->

        <!-- ===== BODY ===== -->
        <div class="receipt-body">

            <!-- Customer Information -->
            <div class="section">
                <div class="section-title">Customer Information</div>
                <div class="info-grid">
                    <div class="info-cell">
                        <label>Full Name</label>
                        <p>{{ $order->name ?? '—' }}</p>
                    </div>
                    <div class="info-cell">
                        <label>Phone</label>
                        <p>{{ $order->phone ?? '—' }}</p>
                    </div>
                    @if($orderType === 'dine_in' && $order->table)
                        <div class="info-cell">
                            <label>Table</label>
                            <p>Table {{ $order->table->table_number }}</p>
                        </div>
                    @endif
                    @if($orderType === 'delivery' && !empty($order->address))
                        <div class="info-cell">
                            <label>Delivery Address</label>
                            <p>{{ $order->address }}</p>
                        </div>
                    @endif
                </div>

                @if(!empty($order->notes))
                    <div class="notes-box" style="margin-top: 16px;">
                        <strong>Note:</strong> "{{ $order->notes }}"
                    </div>
                @endif
            </div>

            <!-- Items Table -->
            <div class="section">
                <div class="section-title">Order Items</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="item-name">{{ $item->product->name ?? 'Unknown Item' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr class="dashed-divider">

            <!-- Summary -->
            <div class="section" style="margin-bottom: 0;">
                <div class="summary-wrapper">
                    <div class="summary-box">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax (0%)</span>
                            <span>Rp 0</span>
                        </div>
                        <hr class="summary-divider">
                        <div class="summary-total">
                            <span class="label">Total</span>
                            <span class="amount">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /BODY -->

        <!-- ===== FOOTER ===== -->
        <div class="receipt-footer">
            <div class="footer-main">Thank you for ordering at Coffee Street ☕</div>
            <div class="footer-sub">Generated automatically by Coffee Street &nbsp;·&nbsp; {{ $order->created_at->format('d M Y') }}</div>
        </div>

    </div>
    <!-- /Receipt Card -->

</div>

</body>
</html>
