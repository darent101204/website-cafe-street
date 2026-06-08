@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4"> <br>
    <br>  
        <h2>📊 Financial Report</h2>
        <a href="{{ route('admin.admin.financePDF', [
            'year' => $year,
            'month' => $month
        ]) }}" class="btn btn-success rounded-5">
        <i class="fa fa-file-pdf"></i>    Download PDF
        </a>
    </div>

    {{-- FILTER --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <form method="GET" action="{{ route('admin.admin.finance') }}">

                <div class="row">

                    <div class="col-md-4">
                        <label>Tahun</label>

                        <select name="year" class="form-select">

                            @for($i = date('Y'); $i >= 2023; $i--)
                                <option value="{{ $i }}"
                                    {{ $year == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor

                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Bulan</label>

                        <select name="month" class="form-select">

                            <option value="">
                                Semua Bulan
                            </option>

                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}"
                                    {{ $month == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endfor

                        </select>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-primary w-100">
                            Filter Laporan
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Revenue</h6>
                    <h3>
                        Rp {{ number_format($totalRevenue,0,',','.') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Orders</h6>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Average Order</h6>
                    <h3>
                        Rp {{ number_format($avgOrder,0,',','.') }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    {{-- TABEL --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($orders as $order)

                            <tr>

                                <td>#{{ $order->id }}</td>

                                <td>{{ $order->name }}</td>

                                <td>
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </td>

                                <td>
                                    {{ ucfirst($order->status) }}
                                </td>

                                <td>
                                    Rp {{ number_format($order->total_price,0,',','.') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center">
                                    Tidak ada data
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@endsection