@extends('layouts.app')

@section('title', 'Search Results - Coffee Street')

@section('content')
<div class="container mt-4 mt-md-5 mb-5">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 text-center text-md-start">
            <h2 class="fw-bold mb-1">
                Search Results for "<span style="color: #FF902A;">{{ $query }}</span>"
            </h2>
            <p class="text-muted small mb-0">Found {{ $products->count() }} product(s) matching your keyword.</p>
        </div>
    </div>

    @if($products->count() > 0)
        <!-- Product Grid -->
        <div class="row g-4 justify-content-center">
            @foreach($products as $product)
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-column" 
                         style="background-color: #FFF8F0; border: 1px solid rgba(246, 235, 218, 0.4) !important;">
                        
                        <!-- Top Actions Overlay -->
                        <div class="position-relative">
                            <!-- Favorite Toggle (Preserved) -->
                            @auth
                                @php
                                    $isFavorited = \App\Models\Favorite::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
                                @endphp
                                <form action="{{ route('products.favorite', $product->id) }}" method="POST" class="position-absolute" style="top: 12px; left: 12px; z-index: 10;">
                                    @csrf
                                    <button type="submit" class="btn p-0 rounded-circle bg-white shadow-sm border-0 d-flex align-items-center justify-content-center" 
                                            style="width: 34px; height: 34px; min-height: unset; min-width: unset;">
                                        <i class="fa-solid fa-heart" style="color: {{ $isFavorited ? '#dc3545' : '#dee2e6' }}; font-size: 1.05rem;"></i>
                                    </button>
                                </form>
                            @endauth

                            <!-- Rating Badge (Preserved) -->
                            <div class="position-absolute" style="top: 12px; right: 12px; z-index: 10;">
                                <span class="badge bg-white text-dark shadow-sm border rounded-5 px-2.5 py-1.5 fw-semibold d-inline-flex align-items-center gap-1">
                                    <i class="fa fa-star text-warning"></i> {{ number_format($product->rating, 1) }}
                                </span>
                            </div>

                            <!-- Product Hero Image -->
                            <div class="image-wrapper overflow-hidden" style="height: 220px; background-color: #F6EBDA;">
                                <img src="{{ $product->image ? (str_starts_with($product->image, 'images/') ? asset($product->image) : Storage::url($product->image)) : asset('images/no-image.png') }}" 
                                     class="w-100 h-100 object-fit-cover transition-transform duration-300 hover-scale" 
                                     alt="{{ $product->name }}">
                            </div>
                        </div>

                        <!-- Card Body with Flex Layout for Consistency -->
                        <div class="card-body p-3.5 d-flex flex-column flex-grow-1">
                            <!-- Title -->
                            <h5 class="fw-bold text-dark mb-1 text-truncate" title="{{ $product->name }}">
                                {{ $product->name }}
                            </h5>

                            <!-- Description (Clamp to 2 lines max) -->
                            <p class="text-muted small mb-3 flex-grow-1 line-clamp-2" style="line-height: 1.45;">
                                {{ $product->description }}
                            </p>

                            <!-- Price (Premium & Visual stand-out) -->
                            <div class="fw-bold mb-3" style="color: #2F2105; font-size: 1.15rem; letter-spacing: -0.01em;">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>

                            <!-- Footer Actions -->
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2.5 border-top border-light-subtle">
                                <!-- Category Badge -->
                                <span class="badge border rounded-5 px-2.5 py-1 text-uppercase" 
                                      style="color: {{ $product->category == 'hot' ? '#FF902A' : '#7E7D7A' }}; border-color: {{ $product->category == 'hot' ? '#FF902A' : '#E5D9C8' }}; background: transparent; font-size: 0.65rem;">
                                    {{ $product->category }}
                                </span>

                                <!-- Add to Cart Button (Preserved) -->
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn p-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                            style="background-color: #FF902A; color: white; width: 34px; height: 34px; border: none; min-height: unset; min-width: unset;" 
                                            title="Add to Cart">
                                        <i class="fa fa-plus" style="font-size: 0.85rem;"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Premium Minimal Empty State -->
        <div class="row">
            <div class="col-12 text-center py-5">
                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle bg-white shadow-sm" style="width: 80px; height: 80px;">
                    <i class="fa fa-search" style="color: #F6EBDA; font-size: 2.2rem;"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">No coffee found ☕</h4>
                <p class="text-muted small mb-4">Try searching with different keywords or explore our catalog.</p>
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark rounded-5 px-4 fw-bold" style="min-height: 44px;">
                    Browse All Products
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
