<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Coffee Street — Premium coffee delivered fresh to your door. Order now.">
    <meta name="theme-color" content="#F6EBDA">

    <title>@yield('title', config('app.name', 'Coffee Street'))</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Custom Global Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Vite (Tailwind + App JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/67123840ad.js" crossorigin="anonymous" defer></script>

    <!-- Page-specific styles -->
    @stack('styles')
</head>

<body class="no-overflow-x">

    <!-- ═══════════════════════════════════════════════
         NAVBAR — Premium Responsive with Mobile Menu
    ═══════════════════════════════════════════════ -->
    <header class="cs-header" id="mainHeader">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <nav class="cs-nav d-flex align-items-center justify-content-between" style="min-height: 64px;">

                <!-- ── Logo ───────────────────────────────── -->
                <a href="{{ route('home') }}" class="cs-nav__logo d-flex align-items-center text-decoration-none flex-shrink-0"
                   aria-label="Coffee Street – Home">
                    <img src="{{ asset('images/logo_coffe.svg') }}"
                         alt="Coffee Street"
                         class="cs-nav__logo-img"
                         style="height: 42px; width: auto;">
                </a>

                <!-- ── Desktop Nav Links (lg+) ─────────────── -->
                <ul class="cs-nav__links d-none d-lg-flex align-items-center list-unstyled mb-0 gap-1">
                    <li>
                        <a href="{{ route('home') }}#about" class="nav-link">About Us</a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="nav-link">Our Products</a>
                    </li>
                    <li>
                        <a href="{{ route('delivery') }}" class="nav-link">Delivery</a>
                    </li>
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <li>
                                <a href="{{ route('admin.orders.index') }}"
                                   class="nav-link fw-semibold"
                                   style="color: var(--cs-orange) !important;">
                                    <i class="fa fa-shield-halved me-1" style="font-size: 0.75em;"></i>
                                    Manage Orders
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- ── Desktop Right: Search + Cart + Auth ── -->
                <div class="cs-nav__right d-none d-lg-flex align-items-center gap-3">

                    <!-- Search Form -->
                    <form action="{{ route('search') }}" method="GET" role="search" class="cs-search-form">
                        <div class="cs-search-wrap position-relative">
                            <i class="fa fa-search cs-search-icon"></i>
                            <input type="search"
                                   name="q"
                                   class="form-control cs-search-input"
                                   placeholder="Search coffee…"
                                   aria-label="Search"
                                   value="{{ request('q') }}">
                        </div>
                    </form>

                    <!-- Cart Icon -->
                    <div class="position-relative">
                        <a href="{{ route('cart.index') }}"
                           class="cs-nav-icon text-decoration-none d-flex align-items-center justify-content-center"
                           aria-label="Shopping cart">
                            <i class="fa fa-cart-shopping" style="font-size: 1.15rem; color: #2F2105;"></i>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="cart-badge">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    </div>

                    <!-- Auth Links -->
                    @guest
                        <a href="{{ route('login') }}"
                           class="btn btn-outline-dark btn-sm rounded-5 px-4">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="btn btn-sm rounded-5 px-4 fw-semibold"
                           style="background-color: #FF902A; border-color: #FF902A; color: white;">
                            Sign up
                        </a>
                    @endguest

                    @auth
                        <div class="dropdown">
                            <button class="btn btn-sm dropdown-toggle d-flex align-items-center gap-2 rounded-5 px-3"
                                    style="background-color: var(--cs-cream); border-color: #E5D9C8; color: #2F2105;"
                                    type="button"
                                    id="userDropdown"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa fa-circle-user" style="font-size: 1.05rem; color: var(--cs-orange);"></i>
                                <span class="fw-semibold" style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ Auth::user()->name }}
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fa fa-user me-2" style="color: var(--cs-orange); width: 16px;"></i>Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('order.history') }}">
                                        <i class="fa fa-clock-rotate-left me-2" style="color: var(--cs-orange); width: 16px;"></i>Order History
                                    </a>
                                </li>
                                @if(Auth::user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.orders.index') }}">
                                            <i class="fa fa-gauge me-2" style="color: var(--cs-orange); width: 16px;"></i>Dashboard
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fa fa-right-from-bracket me-2" style="width: 16px;"></i>Sign out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>

                <!-- ── Mobile Right: Cart + Hamburger ──────── -->
                <div class="d-flex d-lg-none align-items-center gap-3">

                    <!-- Mobile Cart -->
                    <div class="position-relative">
                        <a href="{{ route('cart.index') }}"
                           class="text-decoration-none d-flex align-items-center justify-content-center"
                           aria-label="Shopping cart"
                           style="width: 40px; height: 40px;">
                            <i class="fa fa-cart-shopping" style="font-size: 1.1rem; color: #2F2105;"></i>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="cart-badge">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    </div>

                    <!-- Hamburger Button -->
                    <button class="cs-hamburger"
                            id="mobileMenuToggle"
                            aria-label="Toggle navigation"
                            aria-expanded="false"
                            aria-controls="mobileMenu"
                            type="button">
                        <span class="cs-hamburger__line"></span>
                        <span class="cs-hamburger__line"></span>
                        <span class="cs-hamburger__line"></span>
                    </button>
                </div>

            </nav><!-- /cs-nav -->

            <!-- ── Mobile Search (always visible on mobile) ── -->
            <div class="d-lg-none pb-2 px-1">
                <form action="{{ route('search') }}" method="GET" role="search">
                    <div class="cs-search-wrap position-relative">
                        <i class="fa fa-search cs-search-icon"></i>
                        <input type="search"
                               name="q"
                               class="form-control cs-search-input w-100"
                               placeholder="Search coffee…"
                               aria-label="Search"
                               value="{{ request('q') }}">
                    </div>
                </form>
            </div>

        </div><!-- /container -->

        <!-- ── Mobile Drawer Menu ──────────────────────────── -->
        <div class="cs-mobile-menu" id="mobileMenu" aria-hidden="true">
            <div class="cs-mobile-menu__body">

                <!-- Nav Links -->
                <nav>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="{{ route('home') }}#about" class="cs-mobile-link">
                                <i class="fa fa-circle-info me-2"></i>About Us
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}" class="cs-mobile-link">
                                <i class="fa fa-mug-hot me-2"></i>Our Products
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('delivery') }}" class="cs-mobile-link">
                                <i class="fa fa-truck me-2"></i>Delivery
                            </a>
                        </li>
                        @auth
                            <li>
                                <a href="{{ route('order.history') }}" class="cs-mobile-link">
                                    <i class="fa fa-clock-rotate-left me-2"></i>Order History
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile.edit') }}" class="cs-mobile-link">
                                    <i class="fa fa-user me-2"></i>Profile
                                </a>
                            </li>
                            @if(Auth::user()->role === 'admin')
                                <li>
                                    <a href="{{ route('admin.orders.index') }}" class="cs-mobile-link"
                                       style="color: var(--cs-orange);">
                                        <i class="fa fa-gauge me-2"></i>Manage Orders
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                </nav>

                <!-- Mobile Auth Buttons -->
                <div class="cs-mobile-menu__auth mt-4 pt-4 border-top">
                    @guest
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('login') }}"
                               class="btn btn-outline-dark rounded-5 w-100">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                               class="btn rounded-5 w-100 fw-semibold"
                               style="background-color: #FF902A; color: white;">
                                Sign up
                            </a>
                        </div>
                    @endguest

                    @auth
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="fa fa-circle-user" style="font-size: 1.4rem; color: var(--cs-orange);"></i>
                            <div>
                                <div class="fw-bold" style="color: #2F2105;">{{ Auth::user()->name }}</div>
                                <div class="text-muted" style="font-size: 0.78rem;">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="btn btn-outline-danger rounded-5 w-100 btn-sm">
                                <i class="fa fa-right-from-bracket me-1"></i>Sign out
                            </button>
                        </form>
                    @endauth
                </div>

            </div><!-- /body -->
        </div><!-- /mobile-menu -->

        <!-- Mobile menu backdrop -->
        <div class="cs-mobile-backdrop" id="mobileBackdrop" aria-hidden="true"></div>

    </header>
    <!-- /NAVBAR -->

    <!-- Session flash messages -->
    @if(session('success') && !request()->routeIs('products.*', 'cart.*', 'admin.*', 'order.*'))
        <div class="container pt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════ -->
    <main>
        @yield('content')
    </main>

    <!-- ═══════════════════════════════
         FOOTER
    ═══════════════════════════════ -->
    <footer class="cs-footer mt-5">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-4 border-top">
                <p class="col-md-4 mb-0 text-muted small">
                    © {{ date('Y') }} Coffee Street · Made with
                    <i class="fa fa-heart" style="color: var(--cs-orange);"></i>
                </p>

                <a href="{{ route('home') }}"
                   class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 text-decoration-none">
                    <img src="{{ asset('images/logo_coffe.svg') }}"
                         alt="Coffee Street"
                         style="max-height: 36px; width: auto;">
                </a>

                <ul class="nav col-md-4 justify-content-end list-unstyled d-flex gap-1 mb-0">
                    <li>
                        <a href="{{ route('home') }}" class="nav-link py-1 px-2 text-muted small">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="nav-link py-1 px-2 text-muted small">Products</a>
                    </li>
                    <li>
                        <a href="{{ route('delivery') }}" class="nav-link py-1 px-2 text-muted small">Delivery</a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTopBtn" title="Back to top" aria-label="Back to top">
        <i class="fa fa-arrow-up"></i>
    </button>

    <!-- ═══════════════════════════════
         SCRIPTS
    ═══════════════════════════════ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script src="{{ asset('js/main.js') }}"></script>

    <script>
    // ── Sticky header shadow on scroll ──────────────────
    (function() {
        var header = document.getElementById('mainHeader');
        if (!header) return;
        var onScroll = function() {
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        };
        window.addEventListener('scroll', onScroll, { passive: true });
    })();

    // ── Mobile hamburger menu ────────────────────────────
    (function() {
        var toggleBtn  = document.getElementById('mobileMenuToggle');
        var mobileMenu = document.getElementById('mobileMenu');
        var backdrop   = document.getElementById('mobileBackdrop');
        if (!toggleBtn || !mobileMenu) return;

        var isOpen = false;

        function openMenu() {
            isOpen = true;
            mobileMenu.classList.add('is-open');
            backdrop.classList.add('is-visible');
            toggleBtn.classList.add('is-active');
            toggleBtn.setAttribute('aria-expanded', 'true');
            mobileMenu.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            isOpen = false;
            mobileMenu.classList.remove('is-open');
            backdrop.classList.remove('is-visible');
            toggleBtn.classList.remove('is-active');
            toggleBtn.setAttribute('aria-expanded', 'false');
            mobileMenu.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        toggleBtn.addEventListener('click', function() {
            isOpen ? closeMenu() : openMenu();
        });

        backdrop.addEventListener('click', closeMenu);

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) closeMenu();
        });

        // Close when a menu link is clicked
        var links = mobileMenu.querySelectorAll('a');
        links.forEach(function(link) {
            link.addEventListener('click', closeMenu);
        });
    })();

    // ── Back to Top ────────────────────────────────────
    (function() {
        var btn = document.getElementById('backToTopBtn');
        if (!btn) return;
        window.addEventListener('scroll', function() {
            btn.style.display = window.scrollY > 300 ? 'flex' : 'none';
        }, { passive: true });
        btn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    })();
    </script>

    @stack('scripts')

</body>
</html>
