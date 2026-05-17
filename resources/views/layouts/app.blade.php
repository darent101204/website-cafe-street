<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'Coffee Street'))</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Vite Scripts (Optional if we want to use Breeze's JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <header class="p-3 mb-3">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="{{ route('home') }}" class="d-inline-flex link-body-emphasis text-decoration-none">
                    <!-- Ensure image exists, referencing public path -->
                    <img src="{{ asset('images/logo_coffe.svg') }}" class="img-fluid" alt="logo" style="max-height: 50px;">
                </a>

                <ul class="nav col-lg-auto me-lg-auto mb-2 mb-md-0 justify-content-center mx-auto">
                    <li><a href="{{ route('home') }}#about" class="nav-link px-2 text-dark">About Us</a></li>
                    <li><a href="{{ route('products.index') }}" class="nav-link px-2 text-dark">Our Product</a></li>
                    <li><a href="{{ route('delivery') }}" class="nav-link px-2 text-dark">Delivery</a></li>
                    
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <li><a href="{{ route('admin.orders.index') }}" class="nav-link px-2 text-danger fw-bold">Manage Orders</a></li>
                        @endif
                    @endauth
                </ul>

                <!-- Search Form -->
                <form action="{{ route('search') }}" method="GET" class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
                    <input type="search" name="q" class="form-control rounded-5" style="font-family: Poppins, FontAwesome;"
                        placeholder="&#xf002 Cappuccino" aria-label="Search" value="{{ request('q') }}">
                </form>

                <div class="d-flex align-items-center gap-3">
                    <!-- Cart Icon -->
                    <div class="dropdown text-end position-relative">
                        <a href="{{ route('cart.index') }}" class="text-dark text-decoration-none">
                            <i class="fa fa-cart-shopping fa-lg"></i>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="cart-badge">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    </div>

                    <!-- Auth Links -->
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-5 btn-sm">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-dark rounded-5 btn-sm" style="background-color: #FF902A; border-color: #FF902A;">Sign-up</a>
                    @endguest

                    @auth
                        <div class="dropdown text-end">
                            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <b>{{ Auth::user()->name }}</b>
                            </a>
                            <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">Dashboard</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Sign out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <!-- Footer Start -->
    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-body-secondary">© 2026 Darent Glusefik</p>

            <a href="{{ route('home') }}"
                class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <img src="{{ asset('images/logo_coffe.svg') }}" class="img-fluid" alt="logo" style="max-height: 40px;">
            </a>

            <ul class="nav col-md-4 justify-content-end">
                <li class="nav-item"><a href="{{ route('home') }}" class="nav-link px-2 text-body-secondary">Home</a></li>
                <li class="nav-item"><a href="{{ route('products.index') }}" class="nav-link px-2 text-body-secondary">Our Product</a></li>
                <li class="nav-item"><a href="{{ route('delivery') }}" class="nav-link px-2 text-body-secondary">Delivery</a></li>
            </ul>
        </footer>
    </div>
    <!-- Footer End -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/67123840ad.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>
