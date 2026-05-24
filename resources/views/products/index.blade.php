@extends('layouts.app')

@section('title', 'Our Products - Coffee Street')

@section('content')
<div class="container mt-4 mt-md-5 mb-5">
    <!-- Page Title & Header Section -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-md-6 text-center text-md-start">
            <h2 class="fw-bold mb-1">
                @auth
                    @if(Auth::user()->role === 'admin')
                        Product <span style="border-bottom: 3px solid #FF902A;">Management</span>
                    @else
                        Our Premium <span style="border-bottom: 3px solid #FF902A;">Coffee Menu</span>
                    @endif
                @else
                    Our Premium <span style="border-bottom: 3px solid #FF902A;">Coffee Menu</span>
                @endauth
            </h2>
            <p class="text-muted small mb-0">Handcrafted drinks and snacks, freshly prepared for your enjoyment.</p>
        </div>
        <div class="col-12 col-md-6 text-center text-md-end">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('products.create') }}" class="btn rounded-5 px-4 py-2 fw-bold" 
                       style="background-color: #FF902A; color: white; border: none; box-shadow: 0 4px 14px rgba(255, 144, 42, 0.3);">
                        <i class="fa fa-plus me-1"></i> Add New Product
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Alert Banner -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Visual Category chips quick navigation (Visual details) -->
    <div class="d-flex gap-2 overflow-x-auto pb-3 pt-1 mb-4 scrollbar-none" style="-webkit-overflow-scrolling: touch; white-space: nowrap;">
        <span class="badge px-3 py-2 border rounded-5 fw-semibold cursor-pointer" 
              style="background-color: #2F2105; color: #fff; border-color: #2F2105;">
            ✨ All items
        </span>
        <span class="badge px-3 py-2 border rounded-5 text-dark bg-white fw-semibold cursor-pointer" style="border-color: #E5D9C8;">
            ☕ Coffee
        </span>
        <span class="badge px-3 py-2 border rounded-5 text-dark bg-white fw-semibold cursor-pointer" style="border-color: #E5D9C8;">
            🧋 Latte
        </span>
        <span class="badge px-3 py-2 border rounded-5 text-dark bg-white fw-semibold cursor-pointer" style="border-color: #E5D9C8;">
            🥤 Non-Coffee
        </span>
        <span class="badge px-3 py-2 border rounded-5 text-dark bg-white fw-semibold cursor-pointer" style="border-color: #E5D9C8;">
            🍰 Snacks
        </span>
    </div>

    <!-- Responsive Product Grid -->
    <div class="row g-4 justify-content-center">
        @forelse($products as $product)
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

                        <!-- Description (Graceful clamp to 2 lines max) -->
                        <p class="text-muted small mb-3 flex-grow-1 line-clamp-2" style="line-height: 1.45;">
                            {{ $product->description }}
                        </p>

                        <!-- Price (Premium & Visual stand-out) -->
                        <div class="fw-bold mb-3" style="color: #2F2105; font-size: 1.15rem; letter-spacing: -0.01em;">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>

                        <!-- Footer Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-auto pt-2.5 border-top border-light-subtle">
                            <!-- Badges Group -->
                            <div class="d-flex gap-1 flex-wrap">
                                <span class="badge border rounded-5 px-2.5 py-1 text-uppercase" 
                                      style="color: {{ $product->category == 'hot' ? '#FF902A' : '#7E7D7A' }}; border-color: {{ $product->category == 'hot' ? '#FF902A' : '#E5D9C8' }}; background: transparent; font-size: 0.65rem;">
                                    {{ $product->category }}
                                </span>
                                @if($product->is_featured)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 rounded-5 px-2.5 py-1" style="font-size: 0.65rem;">
                                        Featured
                                    </span>
                                @endif
                            </div>

                            <!-- Buttons (Side-by-Side overlay) -->
                            <div class="d-flex align-items-center gap-1.5">
                                @auth
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('products.edit', $product) }}" class="btn p-0 rounded-circle border d-flex align-items-center justify-content-center" 
                                           style="width: 32px; height: 32px; border-color: #E5D9C8; background-color: white; color: #2F2105; min-height: unset; min-width: unset;" 
                                           title="Edit product">
                                            <i class="fa fa-edit" style="font-size: 0.78rem;"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn p-0 rounded-circle border border-danger border-opacity-25 d-flex align-items-center justify-content-center text-danger" 
                                                    style="width: 32px; height: 32px; background-color: #fff5f5; min-height: unset; min-width: unset;" 
                                                    title="Delete product">
                                                <i class="fa fa-trash" style="font-size: 0.78rem;"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth

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
            </div>
        @empty
            <!-- Minimal, Elegant Empty State -->
            <div class="col-12 text-center py-5">
                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle bg-white shadow-sm" style="width: 80px; height: 80px;">
                    <i class="fa fa-mug-hot" style="color: #F6EBDA; font-size: 2.2rem;"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">No coffee found ☕</h4>
                <p class="text-muted small mb-4">Start by adding your first product or adjusting your filters.</p>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('products.create') }}" class="btn btn-outline-dark rounded-5 px-4 fw-bold">
                            Add First Product
                        </a>
                    @endif
                @endauth
            </div>
        @endforelse
    </div>
</div>
@endsection
