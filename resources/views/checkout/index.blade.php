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
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="John Doe">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" required placeholder="08123456789">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Delivery Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required placeholder="Street name, number, city..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Optional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Less sugar, extra ice..."></textarea>
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
                                                <small class="text-muted">{{ number_format($item['price'], 0) }} K x {{ $item['quantity'] }}</small>
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($item['price'] * $item['quantity'], 0) }} K
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="border-top">
                                        <td><strong>Subtotal</strong></td>
                                        <td class="text-end"><strong>{{ number_format($total, 0) }} K</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Delivery</td>
                                        <td class="text-end"><span class="badge bg-success">Free</span></td>
                                    </tr>
                                    <tr class="border-top">
                                        <td><h4><strong>Total</strong></h4></td>
                                        <td class="text-end"><h4 style="color: #FF902A;"><strong>{{ number_format($total, 0) }} K</strong></h4></td>
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
