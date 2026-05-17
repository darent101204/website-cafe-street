@extends('layouts.app')

@section('title', 'Order Success - Coffee Street')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fa fa-check-circle text-success" style="font-size: 80px;"></i>
                    </div>
                    <h2 class="mb-3">Thank You!</h2>
                    <h4 class="mb-4 text-muted">Your order has been placed successfully.</h4>
                    
                    <p class="lead mb-4">
                        We have received your order and are processing it. <br>
                        Sit back and relax while we brew your coffee!
                    </p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('home') }}" class="btn btn-lg rounded-5" style="background-color: #FF902A; color: white;">
                            Back to Home
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-lg btn-outline-secondary rounded-5">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
