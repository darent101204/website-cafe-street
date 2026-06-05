<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Financial Report — Coffee Street</title>

<style>
* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
    color:#1a1a1a;
    background:#fff;
}

.page {
    padding: 40px;
}

/* HEADER */
.header {
    text-align:center;
    margin-bottom: 25px;
}

.title {
    font-size: 26px;
    font-weight: 900;
}

.subtitle {
    font-size: 10px;
    color:#777;
    margin-top:5px;
}

/* SUMMARY BOX */
.summary {
    width:100%;
    margin-bottom:20px;
}

.box {
    width: 33%;
    display:inline-block;
    padding:12px;
    background:#f8f8f6;
    border-left:3px solid #FF902A;
    margin-right:5px;
}

.box h3 {
    font-size: 14px;
    margin-bottom:5px;
}

.box p {
    font-size: 12px;
    font-weight: bold;
}

/* TABLE */
table {
    width:100%;
    border-collapse: collapse;
    margin-top:15px;
}

th {
    background:#2f2f2f;
    color:#fff;
    padding:8px;
    font-size:10px;
    text-transform: uppercase;
}

td {
    padding:8px;
    border-bottom:1px solid #eee;
    font-size:10px;
}

.right {
    text-align:right;
}

/* TOTAL */
.total-box {
    margin-top:20px;
    text-align:right;
    font-size:14px;
    font-weight:900;
}

.total-box span {
    color:#FF902A;
    font-size:16px;
}
</style>
</head>

<body>

@php
    $completedOrders = $orders->where('status', 'completed');
    $totalRevenue = $completedOrders->sum('total_price');
    $totalOrders = $completedOrders->count();
    $avgOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
@endphp

<div class="page">

    <!-- HEADER -->
    <div class="header">
        <div class="title">FINANCIAL REPORT</div>
        <div class="subtitle">
            Coffee Street — {{ now()->format('d M Y') }}
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="summary">
        <div class="box">
            <h3>Total Revenue</h3>
            <p>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>

        <div class="box">
            <h3>Total Orders</h3>
            <p>{{ $totalOrders }}</p>
        </div>

        <div class="box">
            <h3>Average Order</h3>
            <p>Rp {{ number_format($avgOrder, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Status</th>
                <th class="right">Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach($completedOrders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td class="right">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTAL -->
    <div class="total-box">
        TOTAL REVENUE: 
        <span>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
    </div>

</div>

</body>
</html>