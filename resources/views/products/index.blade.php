@extends('layouts.app')

@section('title', 'Our Products - Coffee Street')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Product <span style="border-bottom: 3px solid #FF902A;">Management</span></h2>
        </div>
        <div class="col-md-6 text-end">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('products.create') }}" class="btn btn-lg rounded-5" style="background-color: #FF902A; color: white;">
                        <i class="fa fa-plus"></i> Add New Product
                    </a>
                @endif
            @endauth
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center gap-4">
        @forelse($products as $product)
            <div class="col-lg-3 mt-3">
                <div class="card text-center border-light shadow-lg h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="image-wrapper position-relative mb-3">
                            @if(file_exists(public_path('images/' . $product->image)))
                                <img src="{{ asset('images/' . $product->image) }}" class="rounded-3 img-fluid w-100" style="height: 200px; object-fit: cover;" alt="{{ $product->name }}">
                            @elseif(file_exists(public_path('images/products/' . $product->image)))
                                <img src="{{ asset('images/products/' . $product->image) }}" class="rounded-3 img-fluid w-100" style="height: 200px; object-fit: cover;" alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('images/img_product.png') }}" class="rounded-3 img-fluid w-100" style="height: 200px; object-fit: cover;" alt="{{ $product->name }}">
                            @endif
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge rounded-pill bg-white text-dark shadow-sm border">
                                    <i class="fa fa-star text-warning"></i> {{ number_format($product->rating, 1) }}
                                </span>
                            </div>
                        </div>

                        <div class="row justify-content-between align-items-center mb-2">
                            <div class="col-8 text-start">
                                <h5 class="mb-0 text-truncate" title="{{ $product->name }}"><b>{{ $product->name }}</b></h5>
                            </div>
                            <div class="col-4 text-end">
                                <h6 class="mb-0 fw-bold text-nowrap">{{ number_format($product->price, 0) }} K</h6>
                            </div>
                        </div>

                        <div class="text-start mb-3 flex-grow-1">
                            <small class="text-muted">{{ Str::limit($product->description, 60) }}</small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div class="d-flex gap-1">
                                <span class="badge" style="background-color: {{ $product->category == 'hot' ? '#FF902A' : '#FFD28F' }};">
                                    {{ ucfirst($product->category) }}
                                </span>
                                @if($product->is_featured)
                                    <span class="badge bg-success">Featured</span>
                                @endif
                            </div>

                            <div class="d-flex gap-2">
                                @auth
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" 
                                            onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm rounded-circle text-white" style="background-color: #FF902A;" title="Add to Cart">
                                        <i class="fa fa-cart-shopping"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <h4>No products found</h4>
                    <p>Start by adding your first product!</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
