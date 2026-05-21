@extends('layouts.app')

@section('title', 'Coffee Street - Home')

@section('content')
    <section class="hero">
        <div class="container">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-10 col-sm-8 col-lg-6">
                    <img src="{{ asset('images/img-hero.png') }}" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes"
                        width="700" height="500" loading="lazy">
                </div>
                <div class="col-lg-6">
                    <h1 class="display-5 fw-semibold text-body-emphasis lh-1 mb-3">Enjoy your <span class="fw-semibold"
                            style="color: #FF902A;">coffee</span> <br>before your activity</h1>
                    <p style="color: #7E7D7A;">Boost your productivity and build your <br> mood with a glass of coffee
                        in the morning</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4 me-md-2 rounded-5"
                            style="background-color: #2F2105; border-color: #2F2105;">Order now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="popular-now" style="background-image: linear-gradient(to bottom, #F6EBDA 50%, #fff 50%);">
        <div class=" container mb-5">
            <h4>Poppular <span style="border-bottom: 3px solid #FF902A;">now</span></h4>
            <div class="container pb-5 mb-5"
                style="background-image: linear-gradient(to bottom, #F6EBDA 30%, #F9D9AA 30%);">
                <div class="row justify-content-center gap-5">
                    @forelse($featuredProducts as $product)
                        <div class="col-lg-3 mt-3">
                            <div class="card text-center border-5 shadow-lg" style="border-color: #F6EBDA;">
                                <div class="card-body">
                                    <div class="position-relative">
                                        @auth
                                            @php
                                                $isFavorited = \App\Models\Favorite::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
                                            @endphp
                                            <form action="{{ route('products.favorite', $product->id) }}" method="POST" class="position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light rounded-circle shadow" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa-solid fa-heart" style="color: {{ $isFavorited ? '#dc3545' : '#dee2e6' }}; font-size: 1.1rem;"></i>
                                                </button>
                                            </form>
                                        @endauth
                                        <img src="{{ $product->image ? (str_starts_with($product->image, 'images/') ? asset($product->image) : Storage::url($product->image)) : asset('images/no-image.png') }}" class="rounded-3 img-fluid" alt="{{ $product->name }}">
                                    </div>
                                    <div class="mt-2 row justify-content-between">
                                        <div class="col-8 text-start">
                                            <h5><b>{{ $product->name }}</b></h5>
                                        </div>
                                        <div class="col-4">
                                            <h5><b>Rp {{ number_format($product->price, 0, ',', '.') }}</b></h5>
                                        </div>
                                    </div>
                                    <div class="mt-2 row justify-content-between">
                                        <div class="col-6">
                                            <button class="btn btn-sm"
                                                style="color: {{ $product->category == 'hot' ? '#FF902A' : '#FFD28F' }}; border-color: {{ $product->category == 'hot' ? '#FF902A' : '#FFD28F' }};">{{ ucfirst($product->category) }}</button>
                                        </div>
                                        <div class="col-4">
                                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn rounded-5"
                                                    style="background-color: #FF902A; color: white;"><i
                                                        class="fa fa-cart-shopping"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>No featured products available</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <section class="delivery" id="delivery">
        <div class="container mt-5 mb-5">
            <h4>How to use delivery <span style="border-bottom: 3px solid #FF902A;">service</span></h4>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 mt-3 mb-sm-0">
                        <div class="text-center">
                            <div class="card-body">
                                <img src="{{ asset('images/cup-coffee.png') }}" width="100px" alt="">
                                <p><b>choose your coffee</b><br><span>there are 20+ coffees for you</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 mt-3">
                        <div class="text-center">
                            <div class="card-body">
                                <img src="{{ asset('images/food-truck.png') }}" width="100px" alt="">
                                <p><b>we delivery it to you</b><br><span>Choose delivery service</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 mt-3">
                        <div class="text-center">
                            <div class="card-body">
                                <img src="{{ asset('images/cup-coffee2.png') }}" width="100px" alt="">
                                <p><b>Enjoy your coffee</b><br><span>Enjoy with your coffee</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about" id="about" style="background-image: linear-gradient(to bottom, #fff 20%, #F6EBDA 20%);">
        <div class="container pb-5">
            <div class="row">
                <div class="col-sm-4 mt-3 mb-sm-0">
                    <div class="text-center">
                        <div class="card text-center border-4 shadow-lg" style="border-color: #fff;">
                            <img src="{{ asset('images/latteart.png') }}" class="img-fluid" alt="latte about">
                        </div>
                    </div>
                </div>
                <div class="col-sm-1">
                </div>
                <div class="col-sm-7 mt-3 d-flex align-items-center">
                    <div class="row">
                        <div class="col-12 mt-5">
                            <h4>About <span style="border-bottom: 3px solid #FF902A;">us</span></h4>
                        </div>
                        <div class="col-12 mt-3">
                            <h5>We provide quality coffee, <br> and ready to deliver.</h5>
                        </div>
                        <div class="col-12 mt-3">
                            <p style="color: #7E7D7A;">We are a company that makes and distributes <br> delicious
                                drinks. our main product is made with a <br> secret recipe and available in stores
                                worldwide.</p>
                        </div>
                        <div class="col-12 mt-3">
                            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm px-4 me-md-2 rounded-5"
                                style="background-color: #2F2105; border-color: #2F2105; color: #FF902A;">get your
                                coffee</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="special-menu" id="special-menu">
        <div class=" container mb-5 mt-5 pb-5">
            <h4>Special menu <span style="border-bottom: 3px solid #FF902A;">for you</span></h4>
            <div class="container">
                <div class="row justify-content-center gap-5">
                    @forelse($specialProducts as $product)
                        <div class="col-lg-3 mt-3">
                            <div class="card text-center border-light shadow-lg">
                                <div class="card-body">
                                    <div class="image-wrapper position-relative">
                                        @auth
                                            @php
                                                $isFavorited = \App\Models\Favorite::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
                                            @endphp
                                            <form action="{{ route('products.favorite', $product->id) }}" method="POST" class="position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light rounded-circle shadow" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa-solid fa-heart" style="color: {{ $isFavorited ? '#dc3545' : '#dee2e6' }}; font-size: 1.1rem;"></i>
                                                </button>
                                            </form>
                                        @endauth
                                        <img src="{{ $product->image ? (str_starts_with($product->image, 'images/') ? asset($product->image) : Storage::url($product->image)) : asset('images/no-image.png') }}" class="rounded-3 img-fluid" alt="{{ $product->name }}">
                                        <button class="btn-rating position-absolute rounded-5 border border-4"
                                            style="background-color: white; border-color: #7E7D7A;"><b>{{ number_format($product->rating, 1) }}</b> <i
                                                class="fa fa-star" style="color: #FFD057;"></i></button>
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
                                            <span style="color: #7E7D7A;">{{ Str::limit($product->description, 40) }}</span>
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
                    @empty
                        <div class="col-12 text-center">
                            <p>No products available</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <section class="testimoni d-none d-lg-flex pt-5 pb-5"
        style="background-color: #F6EBDA; background-image: linear-gradient(to left, #fff 40%, #F6EBDA 40%);">
        <div class="container">
            <div class="row mx-5">
                <div class="col-4 d-flex align-items-center">
                    <div class="row">
                        <div class="col-12">
                            <h4>What they say about us</h4>
                        </div>
                        <div class="col-12">
                            <p style="color: #7E7D7A;">We always provide the best service and always maintain the
                                quality of coffee</p>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="row">
                        <div class="col-4">
                            <img class="img-fluid" src="{{ asset('images/user1.png') }}" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid" src="{{ asset('images/user2.png') }}" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid" src="{{ asset('images/user3.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="subscribe d-none d-lg-flex pb-5 pt-5 text-center position-relative">
        <div class="container mt-5 position-relative">
            <img src="{{ asset('images/coffee_subs.png') }}" alt="" class="img-fluid">
            <div class="input-container position-absolute top-50 start-50 translate-middle text-center">
                <h4 class="text-light mb-3">Subscribe to get 50% discount price</h4>
                <div class="input-group">
                    <input type="text" class="form-control text-start rounded-end rounded-5"
                        placeholder="Enter your text here">
                    <button class="btn rounded-start rounded-5" style="background-color: #2F2105; color: white;"
                        type="button">Order now</button>
                </div>
            </div>
        </div>
    </section>
@endsection
