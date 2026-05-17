@extends('layouts.app')

@section('title', 'Shopping Cart - Coffee Street')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Your Shopping <span style="border-bottom: 3px solid #FF902A;">Cart</span></h2>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row">
            <div class="col-lg-8">
                @foreach(session('cart') as $id => $details)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    @if(file_exists(public_path('images/' . $details['image'])))
                                        <img src="{{ asset('images/' . $details['image']) }}" class="img-fluid rounded" alt="{{ $details['name'] }}">
                                    @elseif(file_exists(public_path('images/products/' . $details['image'])))
                                        <img src="{{ asset('images/products/' . $details['image']) }}" class="img-fluid rounded" alt="{{ $details['name'] }}">
                                    @else
                                        <img src="{{ asset('images/img_product.png') }}" class="img-fluid rounded" alt="{{ $details['name'] }}">
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h5><b>{{ $details['name'] }}</b></h5>
                                    <p class="text-muted mb-0">{{ Str::limit($details['description'], 50) }}</p>
                                </div>
                                <div class="col-md-2 text-center">
                                    <h5 class="mb-0"><b>{{ number_format($details['price'], 0) }} K</b></h5>
                                </div>
                                <div class="col-md-2 text-center">
                                    <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <div class="input-group input-group-sm">
                                            <input type="number" name="quantity" value="{{ $details['quantity'] }}" 
                                                min="1" class="form-control text-center" style="max-width: 70px;">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-2 text-center">
                                    <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-5">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-4">
                <div class="card shadow-lg" style="border-color: #F6EBDA;">
                    <div class="card-body">
                        <h4 class="mb-4">Order <span style="color: #FF902A;">Summary</span></h4>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span><b>{{ number_format($total, 0) }} K</b></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery:</span>
                            <span><b>Free</b></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <h5><b>Total:</b></h5>
                            <h5><b style="color: #FF902A;">{{ number_format($total, 0) }} K</b></h5>
                        </div>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mb-2 rounded-5">
                            Continue Shopping
                        </a>
                        <a href="{{ route('checkout.index') }}" class="btn w-100 rounded-5" style="background-color: #FF902A; color: white;">
                            Proceed to Checkout
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-5"
                                onclick="return confirm('Are you sure you want to clear your cart?')">
                                Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12 text-center py-5">
                <i class="fa fa-cart-shopping fa-5x mb-4" style="color: #F6EBDA;"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted">Add some delicious coffee to get started!</p>
                <a href="{{ route('products.index') }}" class="btn btn-lg rounded-5 px-5 mt-3" 
                    style="background-color: #FF902A; color: white;">
                    Browse Products
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
