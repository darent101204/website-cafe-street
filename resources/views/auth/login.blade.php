@extends('layouts.app')

@section('title', 'Login - Coffee Street')

@section('content')
<<<<<<< Updated upstream
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Login</h2>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control rounded-5" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" class="form-control rounded-5" type="password" name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <label for="remember_me" class="form-check-label text-sm text-gray-600">Remember me</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg rounded-5 text-white" style="background-color: #FF902A;">
                                Log in
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            @if (Route::has('password.request'))
                                <a class="text-muted small text-decoration-none" href="{{ route('password.request') }}">
                                    Forgot your password?
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
=======
<div class="cs-auth-split">
    <!-- Left Hero Panel (Desktop Only) -->
    <div class="cs-auth-hero">
        <div class="cs-auth-hero-content">
            <div class="cs-auth-hero-logo">
                <i class="fa fa-coffee"></i> Plan B
            </div>
            <h1 class="cs-auth-hero-title">Welcome Back!</h1>
            <p class="cs-auth-hero-text">
                Fresh coffee, handcrafted for your day. Sign in to access your order history, favorites, and quick checkout.
            </p>
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="cs-auth-form-side">
        <div class="cs-auth-form-card">
            <h2 class="h3 fw-bold mb-1" style="color: var(--cs-brown);">Sign In</h2>
            <p class="text-muted small mb-4">Access your Coffee Street account</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="cs-form-group">
                    <label for="email" class="cs-form-label">Email Address</label>
                    <input id="email" class="cs-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com">
                    @error('email')
                        <div class="cs-validation-error">
                            <i class="fa fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="cs-form-group">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="cs-form-label mb-0">Password</label>
                        @if (Route::has('password.request'))
                            <a class="small text-decoration-none" href="{{ route('password.request') }}" style="color: var(--cs-orange); font-weight: 500;">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="cs-password-wrapper">
                        <input id="password" class="cs-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                        <button type="button" class="cs-password-toggle" onclick="togglePasswordVisibility('password', 'password-toggle-icon')" aria-label="Toggle password visibility">
                            <i id="password-toggle-icon" class="fa fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="cs-validation-error">
                            <i class="fa fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-4 form-check d-flex align-items-center gap-2">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember" style="cursor: pointer;">
                    <label for="remember_me" class="form-check-label text-muted small" style="cursor: pointer; user-select: none;">Remember my session</label>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-lg btn-primary rounded-3 text-white">
                        Sign In <i class="fa fa-arrow-right-long ms-1" style="font-size: 0.95rem;"></i>
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center">
                    <span class="text-muted small">New to Plan B?</span>
                    <a href="{{ route('register') }}" class="small text-decoration-none ms-1" style="color: var(--cs-orange); font-weight: 600;">
                        Create Account
                    </a>
                </div>
            </form>
>>>>>>> Stashed changes
        </div>
    </div>
</div>
@endsection
