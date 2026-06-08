<!DOCTYPE html>

<html>
<head>
<meta charset="UTF-8">
<title>Financial Report</title>

<style>

body{
    font-family: DejaVu Sans, sans-serif;
    color:#2c2c2c;
    font-size:11px;
    margin:20px;
}

.header{
    text-align:center;
    border-bottom:3px solid #FF902A;
    padding-bottom:15px;
    margin-bottom:25px;
}

.company{
    font-size:28px;
    font-weight:bold;
}

.company-logo{
    text-align:center;
    margin-bottom:10px;
}

.company-logo img{
    height:45px;
}

.company span{
    color:#FF902A;
}

.report-title{
    margin-top:8px;
    font-size:18px;
    font-weight:bold;
}

.period{
    color:#666;
    margin-top:8px;
    line-height:1.6;
}

.summary{
    width:100%;
    margin-bottom:25px;
}

.summary td{
    width:33%;
    padding:8px;
    vertical-align:top;
}

.card{
    border:1px solid #ddd;
    border-left:5px solid #FF902A;
    padding:12px;
    border-radius:4px;
    background:#fafafa;
}

.card-title{
    color:#666;
    font-size:11px;
}

.card-value{
    margin-top:5px;
    font-size:18px;
    font-weight:bold;
}

.section-title{
    margin-top:25px;
    margin-bottom:10px;
    font-size:14px;
    font-weight:bold;
    border-left:4px solid #FF902A;
    padding-left:10px;
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#343a40;
    color:white;
}

th{
    padding:10px;
    font-size:10px;
    text-align:left;
}

td{
    padding:8px;
    border-bottom:1px solid #eaeaea;
}

.text-right{
    text-align:right;
}

.total-row{
    background:#FFF3E6;
    font-weight:bold;
    color:#FF902A;
}

.info-table td{
    border:1px solid #eee;
}

.footer{
    margin-top:30px;
    text-align:center;
    font-size:10px;
    color:#777;
    border-top:1px solid #ddd;
    padding-top:15px;
}

</style>

</head>
<body>

<div class="header">

<div class="company-logo">
    <img src="{{ public_path('images/icon.png') }}" alt="Plan B Logo">
</div>

<div class="report-title">
    FINANCIAL REPORT
</div>

<div style="margin-top:5px;font-size:11px;color:#666;">
    Report No :
    FIN-{{ $year }}{{ $month ? str_pad($month,2,'0',STR_PAD_LEFT) : '00' }}
</div>

<div class="period">
    Reporting Period :
    {{ $month ? DateTime::createFromFormat('!m',$month)->format('F') : 'All Months' }}
    {{ $year }}
    <br>
    Generated on {{ now()->format('d M Y H:i') }} WIB
</div>

</div>

<!-- SUMMARY -->

<table class="summary">

<tr>

<td>
<div class="card">
<div class="card-title">Total Revenue</div>
<div class="card-value">
Rp {{ number_format($totalRevenue,0,',','.') }}
</div>
</div>
</td>

<td>
<div class="card">
<div class="card-title">Total Orders</div>
<div class="card-value">
{{ $totalOrders }}
</div>
</div>
</td>

<td>
<div class="card">
<div class="card-title">Average Order</div>
<div class="card-value">
Rp {{ number_format($avgOrder,0,',','.') }}
</div>
</div>
</td>

</tr>

</table>

<!-- PAYMENT SUMMARY -->

<div class="section-title">
Payment Summary
</div>

<table class="info-table">

<tr>
<td>Cash Revenue</td>
<td class="text-right">
Rp {{ number_format($cashRevenue,0,',','.') }}
</td>
</tr>

<tr>
<td>Online Revenue (QRIS)</td>
<td class="text-right">
Rp {{ number_format($onlineRevenue,0,',','.') }}
</td>
</tr>

</table>

<!-- ORDER TYPE SUMMARY -->

<div class="section-title">
Order Type Summary
</div>

<table class="info-table">

<tr>
<td>Dine In Orders</td>
<td>{{ $dineIn }}</td>
</tr>

<tr>
<td>Takeaway Orders</td>
<td>{{ $takeaway }}</td>
</tr>

<tr>
<td>Delivery Orders</td>
<td>{{ $delivery }}</td>
</tr>

</table>

<!-- TRANSACTION DETAILS -->

<div class="section-title">
Transaction Details
</div>

<table>

<thead>
<tr>
<th>ID</th>
<th>Customer</th>
<th>Date</th>
<th>Order Type</th>
<th>Payment</th>
<th>Status</th>
<th>Total</th>
</tr>
</thead>

<tbody>

@if($orders->count() > 0)

@foreach($orders as $order)

<tr>

    <td>#{{ $order->id }}</td>

    <td>{{ $order->name }}</td>

    <td>
        {{ $order->created_at->format('d M Y') }}
    </td>

    <td>
        {{ ucfirst(str_replace('_',' ',$order->order_type)) }}
    </td>

    <td>
        {{ ucfirst($order->payment_method) }}
    </td>

    <td>
        {{ ucfirst($order->status) }}
    </td>

    <td class="text-right">
        Rp {{ number_format($order->total_price,0,',','.') }}
    </td>

</tr>

@endforeach

@else

<tr>
<td colspan="7" style="text-align:center;padding:20px;">
No transactions found for this reporting period.
</td>
</tr>

@endif

<tr class="total-row">

<td colspan="6" class="text-right">
TOTAL REVENUE
</td>

<td class="text-right">
Rp {{ number_format($totalRevenue,0,',','.') }}
</td>

</tr>

</tbody>

</table>

<div class="footer">

<strong>Plan B Financial Report</strong>

<br><br>

This report was generated automatically by the Plan B Management System.

<br><br>

Generated at:
{{ now()->format('d M Y H:i') }} WIB

</div>

</body>
</html>
