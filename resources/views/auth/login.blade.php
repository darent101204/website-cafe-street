@extends('layouts.app')

@section('title', 'Login - Coffee Street')

@section('content')
<div class="cs-auth-split">
    <!-- Left Hero Panel (Desktop Only) -->
    <div class="cs-auth-hero">
        <div class="cs-auth-hero-content">
            <div class="cs-auth-hero-logo">
                <i class="fa fa-coffee"></i> Coffee Street
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
                    <span class="text-muted small">New to Coffee Street?</span>
                    <a href="{{ route('register') }}" class="small text-decoration-none ms-1" style="color: var(--cs-orange); font-weight: 600;">
                        Create Account
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(fieldId, iconId) {
        const passwordField = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
