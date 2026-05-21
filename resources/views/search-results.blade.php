@extends('layouts.app')

@section('title', 'Search Results - Coffee Street')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Search Results for "<span style="color: #FF902A;">{{ $query }}</span>"</h2>
            <p class="text-muted">Found {{ $products->count() }} product(s)</p>
        </div>
    </div>

    @if($products->count() > 0)
        <div class="row justify-content-center gap-4">
            @foreach($products as $product)
                <div class="col-lg-3 mt-3">
                    <div class="card text-center border-light shadow-lg">
                        <div class="card-body">
                            <div class="image-wrapper position-relative">
                                <img src="{{ $product->image ? (str_starts_with($product->image, 'images/') ? asset($product->image) : Storage::url($product->image)) : asset('images/no-image.png') }}" class="rounded-3 img-fluid" alt="{{ $product->name }}">
                                <button class="btn-rating position-absolute rounded-5 border border-4"
                                    style="background-color: white; border-color: #7E7D7A;">
                                    <b>{{ number_format($product->rating, 1) }}</b> 
                                    <i class="fa fa-star" style="color: #FFD057;"></i>
                                </button>
                            </div>
                            <div class="mt-2 row justify-content-between">
                                <div class="col-9 text-start">
                                    <h5><b>{{ $product->name }}</b></h5>
                                </div>
                                <div class="col-3">
                                    <h5><b>Rp {{ number_format($product->price, 0, ',', '.') }}</b></h5>
                                </div>
                            </div>
                            <div class="row justify-content-between">
                                <div class="col-9 text-start">
                                    <span style="color: #7E7D7A;">{{ Str::limit($product->description, 50) }}</span>
                                </div>
                                <div class="col-3">
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn rounded-5" style="background-color: #FF902A; color: white;">
                                            <i class="fa fa-cart-shopping"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-12 text-center py-5">
                <i class="fa fa-search fa-5x mb-4" style="color: #F6EBDA;"></i>
                <h3>No products found</h3>
                <p class="text-muted">Try searching with different keywords</p>
                <a href="{{ route('products.index') }}" class="btn btn-lg rounded-5 px-5 mt-3" 
                    style="background-color: #FF902A; color: white;">
                    Browse All Products
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
