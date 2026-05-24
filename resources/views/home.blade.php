@extends('layouts.app')

@section('title', 'Coffee Street - Premium Coffee Experience')

@section('content')
    <!-- ═══════════════════════════════════════════════
         1. HERO SECTION (Split Desktop, Stacked Mobile)
    ═══════════════════════════════════════════════ -->
    <section class="cs-section hero py-4 py-md-5" style="background-color: #F6EBDA; min-height: calc(100vh - 64px); display: flex; align-items: center;">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <!-- Left text content -->
                <div class="col-12 col-lg-6 text-center text-lg-start">
                    <h1 class="display-4 fw-bold text-dark mb-3 lh-sm" style="letter-spacing: -0.02em;">
                        Enjoy your <span style="color: #FF902A;">coffee</span><br>before your activity
                    </h1>
                    <p class="fs-5 text-muted mb-4 mx-auto mx-lg-0" style="max-width: 500px; line-height: 1.6;">
                        Boost your productivity and elevate your mood with a perfectly brewed cup of coffee to start your morning.
                    </p>
                    
                    <!-- CTA Buttons (Immediately visible on mobile above the fold/image) -->
                    <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3 mb-4 mb-lg-0">
                        <a href="{{ route('products.index') }}" class="btn px-4 py-2.5 rounded-5 fw-bold" 
                           style="background-color: #2F2105; border-color: #2F2105; color: #fff; min-height: 48px; box-shadow: 0 4px 14px rgba(47, 33, 5, 0.2);">
                            Order Now
                        </a>
                        <a href="#special-menu" class="btn btn-outline-dark px-4 py-2.5 rounded-5 fw-bold" 
                           style="min-height: 48px;">
                            Browse Menu
                        </a>
                    </div>
                </div>

                <!-- Right image content (Appears below CTAs on mobile) -->
                <div class="col-12 col-lg-6 text-center">
                    <div class="position-relative d-inline-block">
                        <img src="{{ asset('images/img-hero.png') }}" 
                             class="img-fluid mx-auto" 
                             alt="Premium Coffee Cup" 
                             style="max-height: 480px; object-fit: contain;"
                             width="550" 
                             height="400">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════
         2. CATEGORY QUICK CHIPS & POPULAR NOW SECTION
    ═══════════════════════════════════════════════ -->
    <section class="cs-section popular-now py-5 bg-white">
        <div class="container">
            <!-- Section Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h2 class="fw-bold mb-1">Popular <span style="border-bottom: 3px solid #FF902A;">Now</span></h2>
                    <p class="text-muted small mb-0">Our most-loved blends and brews chosen by our community.</p>
                </div>
                
                <!-- Lightweight Scrollable Category Chips (Visual only) -->
                <div class="d-flex gap-2 overflow-x-auto pb-2 pt-1 scrollbar-none" style="-webkit-overflow-scrolling: touch; white-space: nowrap; max-width: 100%;">
                    <span class="badge px-3 py-2 border rounded-5 fw-semibold d-inline-flex align-items-center gap-1 cursor-pointer" 
                          style="background-color: #2F2105; color: #fff; border-color: #2F2105;">
                        ✨ All items
                    </span>
                    <span class="badge px-3 py-2 border rounded-5 text-dark bg-light fw-semibold d-inline-flex align-items-center gap-1 cursor-pointer" style="border-color: #E5D9C8;">
                        ☕ Coffee
                    </span>
                    <span class="badge px-3 py-2 border rounded-5 text-dark bg-light fw-semibold d-inline-flex align-items-center gap-1 cursor-pointer" style="border-color: #E5D9C8;">
                        🧋 Latte
                    </span>
                    <span class="badge px-3 py-2 border rounded-5 text-dark bg-light fw-semibold d-inline-flex align-items-center gap-1 cursor-pointer" style="border-color: #E5D9C8;">
                        🥤 Non-Coffee
                    </span>
                    <span class="badge px-3 py-2 border rounded-5 text-dark bg-light fw-semibold d-inline-flex align-items-center gap-1 cursor-pointer" style="border-color: #E5D9C8;">
                        🍰 Snacks
                    </span>
                </div>
            </div>

            <!-- Product Showcase Grid -->
            <div class="row g-4 justify-content-center">
                @forelse($featuredProducts as $product)
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative" style="background-color: #FFF8F0; border: 1px solid rgba(246, 235, 218, 0.4) !important;">
                            
                            <!-- Favorite System Button (Preserved) -->
                            @auth
                                @php
                                    $isFavorited = \App\Models\Favorite::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
                                @endphp
                                <form action="{{ route('products.favorite', $product->id) }}" method="POST" class="position-absolute" style="top: 12px; right: 12px; z-index: 10;">
                                    @csrf
                                    <button type="submit" class="btn p-0 rounded-circle bg-white shadow-sm border-0 d-flex align-items-center justify-content-center" 
                                            style="width: 34px; height: 34px; min-height: unset; min-width: unset;">
                                        <i class="fa-solid fa-heart" style="color: {{ $isFavorited ? '#dc3545' : '#dee2e6' }}; font-size: 1.05rem;"></i>
                                    </button>
                                </form>
                            @endauth

                            <!-- Product Image -->
                            <div class="image-wrapper overflow-hidden" style="height: 200px; background-color: #F6EBDA;">
                                <img src="{{ $product->image ? (str_starts_with($product->image, 'images/') ? asset($product->image) : Storage::url($product->image)) : asset('images/no-image.png') }}" 
                                     class="w-100 h-100 object-fit-cover transition-transform duration-300 hover-scale" 
                                     alt="{{ $product->name }}">
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-3.5 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                                    <h5 class="fw-bold mb-0 text-dark text-truncate">{{ $product->name }}</h5>
                                    <span class="badge bg-white text-dark border border-light-subtle rounded-5 py-1 px-2.5 small">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                <p class="text-muted small mb-3 flex-grow-1 line-clamp-2">
                                    {{ Str::limit($product->description, 50) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top border-light-subtle">
                                    <!-- Category Badge -->
                                    <span class="badge border rounded-5 px-2.5 py-1 text-uppercase" 
                                          style="color: {{ $product->category == 'hot' ? '#FF902A' : '#7E7D7A' }}; border-color: {{ $product->category == 'hot' ? '#FF902A' : '#E5D9C8' }}; background: transparent; font-size: 0.65rem;">
                                        {{ $product->category }}
                                    </span>

                                    <!-- Add to Cart System Button (Preserved) -->
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn p-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                                style="background-color: #FF902A; color: white; width: 34px; height: 34px; border: none; min-height: unset; min-width: unset;">
                                            <i class="fa fa-plus" style="font-size: 0.85rem;"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">No featured products available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════
         3. PROMO / TRUST SECTION
    ═══════════════════════════════════════════════ -->
    <section class="cs-section trust-section py-5" style="background-color: #FFF8F0;">
        <div class="container">
            <div class="row text-center g-4 justify-content-center">
                <!-- Block 1 -->
                <div class="col-12 col-md-4">
                    <div class="p-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-white mx-auto mb-3 shadow-sm" style="width: 60px; height: 60px;">
                            <i class="fa fa-mug-hot" style="color: #FF902A; font-size: 1.5rem;"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Freshly Roasted</h5>
                        <p class="text-muted small mx-auto" style="max-width: 250px;">
                            100% premium Arabica & Robusta beans roasted to perfection daily.
                        </p>
                    </div>
                </div>
                <!-- Block 2 -->
                <div class="col-12 col-md-4">
                    <div class="p-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-white mx-auto mb-3 shadow-sm" style="width: 60px; height: 60px;">
                            <i class="fa fa-truck" style="color: #FF902A; font-size: 1.5rem;"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Fast Delivery</h5>
                        <p class="text-muted small mx-auto" style="max-width: 250px;">
                            Warm and fresh coffee delivered directly to your doorstep.
                        </p>
                    </div>
                </div>
                <!-- Block 3 -->
                <div class="col-12 col-md-4">
                    <div class="p-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-white mx-auto mb-3 shadow-sm" style="width: 60px; height: 60px;">
                            <i class="fa fa-leaf" style="color: #FF902A; font-size: 1.5rem;"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Premium Quality</h5>
                        <p class="text-muted small mx-auto" style="max-width: 250px;">
                            Carefully crafted secret recipes to ensure a delicious aroma.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════
         4. ABOUT US SECTION
    ═══════════════════════════════════════════════ -->
    <section class="cs-section about-section py-5 bg-white" id="about">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-12 col-md-5">
                    <div class="rounded-4 overflow-hidden shadow-sm" style="border: 4px solid #fff;">
                        <img src="{{ asset('images/latteart.png') }}" class="img-fluid w-100" alt="Latte Art Detail" style="max-height: 380px; object-fit: cover;">
                    </div>
                </div>
                <div class="col-12 col-md-7 text-center text-md-start">
                    <h2 class="fw-bold mb-3">About <span style="border-bottom: 3px solid #FF902A;">Us</span></h2>
                    <h4 class="fw-semibold text-dark mb-3">We provide quality coffee, and ready to deliver.</h4>
                    <p class="text-muted mb-4" style="line-height: 1.7; max-width: 600px;">
                        We are a company that makes and distributes delicious coffee beverages. Our signature brews are prepared using hand-selected premium coffee beans and secret roasting formulas to ensure top-notch flavors in every cup.
                    </p>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark rounded-5 px-4 fw-bold">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════
         5. SPECIAL MENU SECTION
    ═══════════════════════════════════════════════ -->
    <section class="cs-section special-menu py-5 bg-white" id="special-menu">
        <div class="container">
            <!-- Section Title -->
            <div class="mb-4">
                <h2 class="fw-bold mb-1">Special Menu <span style="border-bottom: 3px solid #FF902A;">For You</span></h2>
                <p class="text-muted small">Special recipes curated for unique tastes.</p>
            </div>

            <!-- Product Showcase Grid -->
            <div class="row g-4 justify-content-center">
                @forelse($specialProducts as $product)
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative" style="background-color: #FFF8F0; border: 1px solid rgba(246, 235, 218, 0.4) !important;">
                            
                            <!-- Favorite System Button (Preserved) -->
                            @auth
                                @php
                                    $isFavorited = \App\Models\Favorite::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
                                @endphp
                                <form action="{{ route('products.favorite', $product->id) }}" method="POST" class="position-absolute" style="top: 12px; right: 12px; z-index: 10;">
                                    @csrf
                                    <button type="submit" class="btn p-0 rounded-circle bg-white shadow-sm border-0 d-flex align-items-center justify-content-center" 
                                            style="width: 34px; height: 34px; min-height: unset; min-width: unset;">
                                        <i class="fa-solid fa-heart" style="color: {{ $isFavorited ? '#dc3545' : '#dee2e6' }}; font-size: 1.05rem;"></i>
                                    </button>
                                </form>
                            @endauth

                            <!-- Rating Button (Preserved) -->
                            <button class="btn-rating position-absolute rounded-5 border border-4 py-1 px-2 d-flex align-items-center gap-1" 
                                    style="background-color: white; border-color: #F6EBDA; font-size: 0.72rem; top: 12px; left: 12px; pointer-events: none;">
                                <b>{{ number_format($product->rating, 1) }}</b> 
                                <i class="fa fa-star" style="color: #FFD057;"></i>
                            </button>

                            <!-- Product Image -->
                            <div class="image-wrapper overflow-hidden" style="height: 200px; background-color: #F6EBDA;">
                                <img src="{{ $product->image ? (str_starts_with($product->image, 'images/') ? asset($product->image) : Storage::url($product->image)) : asset('images/no-image.png') }}" 
                                     class="w-100 h-100 object-fit-cover transition-transform duration-300 hover-scale" 
                                     alt="{{ $product->name }}">
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-3.5 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                                    <h5 class="fw-bold mb-0 text-dark text-truncate">{{ $product->name }}</h5>
                                    <span class="badge bg-white text-dark border border-light-subtle rounded-5 py-1 px-2.5 small">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                <p class="text-muted small mb-3 flex-grow-1 line-clamp-2">
                                    {{ Str::limit($product->description, 50) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top border-light-subtle">
                                    <!-- Category Badge -->
                                    <span class="badge border rounded-5 px-2.5 py-1 text-uppercase" 
                                          style="color: {{ $product->category == 'hot' ? '#FF902A' : '#7E7D7A' }}; border-color: {{ $product->category == 'hot' ? '#FF902A' : '#E5D9C8' }}; background: transparent; font-size: 0.65rem;">
                                        {{ $product->category }}
                                    </span>

                                    <!-- Add to Cart System Button (Preserved) -->
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn p-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                                style="background-color: #FF902A; color: white; width: 34px; height: 34px; border: none; min-height: unset; min-width: unset;">
                                            <i class="fa fa-plus" style="font-size: 0.85rem;"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">No products available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════
         6. TESTIMONIALS SECTION (Lightweight & Elegant)
    ═══════════════════════════════════════════════ -->
    <section class="cs-section testimonial-section py-5" style="background-color: #F6EBDA;">
        <div class="container">
            <div class="row align-items-center g-4 justify-content-between">
                <div class="col-12 col-lg-4 text-center text-lg-start">
                    <h2 class="fw-bold mb-2">What they say <br class="d-none d-lg-block">about us</h2>
                    <p class="text-muted small mb-0">We always prioritize exceptional customer service and premium ingredient quality.</p>
                </div>
                <div class="col-12 col-lg-8">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="card p-3 border-0 rounded-3 shadow-sm h-100" style="background: rgba(255,255,255,0.75);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <img class="rounded-circle" src="{{ asset('images/user1.png') }}" alt="User 1" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold" style="font-size: 0.85rem;">Alifia K.</h6>
                                        <span class="text-warning" style="font-size: 0.7rem;"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></span>
                                    </div>
                                </div>
                                <p class="text-muted mb-0 small" style="line-height: 1.45;">"The absolute best coffee in town. Fast delivery and perfect temperature."</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="card p-3 border-0 rounded-3 shadow-sm h-100" style="background: rgba(255,255,255,0.75);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <img class="rounded-circle" src="{{ asset('images/user2.png') }}" alt="User 2" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold" style="font-size: 0.85rem;">Devon L.</h6>
                                        <span class="text-warning" style="font-size: 0.7rem;"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></span>
                                    </div>
                                </div>
                                <p class="text-muted mb-0 small" style="line-height: 1.45;">"Love their lattes! Secret recipe makes it completely stand out."</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="card p-3 border-0 rounded-3 shadow-sm h-100" style="background: rgba(255,255,255,0.75);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <img class="rounded-circle" src="{{ asset('images/user3.png') }}" alt="User 3" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold" style="font-size: 0.85rem;">Sarah T.</h6>
                                        <span class="text-warning" style="font-size: 0.7rem;"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></span>
                                    </div>
                                </div>
                                <p class="text-muted mb-0 small" style="line-height: 1.45;">"Warm café vibes even on delivery. Packaging is secure and neat!"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════
         7. NEWSLETTER / SUBSCRIBE (Lightweight & Elegant)
    ═══════════════════════════════════════════════ -->
    <section class="cs-section subscribe-section py-5 bg-white text-center position-relative">
        <div class="container">
            <div class="card border-0 rounded-4 overflow-hidden p-4 p-md-5 text-center position-relative" style="background-image: linear-gradient(135deg, #2F2105 0%, #1A1200 100%);">
                <!-- Background visual touch -->
                <div class="position-absolute opacity-10 end-0 bottom-0" style="pointer-events: none;">
                    <img src="{{ asset('images/coffee_subs.png') }}" alt="" style="max-height: 250px; filter: grayscale(1);">
                </div>
                
                <div class="position-relative z-index-2 mx-auto" style="max-width: 500px;">
                    <h3 class="text-white mb-2 fw-bold">Subscribe & Get 50% Off</h3>
                    <p class="text-light-subtle small mb-4">Be the first to hear about special offers, new single-origin beans, and members-only discounts.</p>
                    
                    <div class="input-group rounded-5 overflow-hidden bg-white p-1" style="border: 2px solid rgba(255,255,255,0.15);">
                        <input type="email" class="form-control text-start border-0 bg-transparent px-3" 
                               placeholder="Your email address" aria-label="Your email address" style="min-height: 38px;">
                        <button class="btn border-0 px-4 py-2 text-white fw-bold rounded-5" style="background-color: #FF902A; min-height: 38px; min-width: unset;" type="button">
                            Subscribe
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
