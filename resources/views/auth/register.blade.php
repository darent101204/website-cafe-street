@extends('layouts.app')

@section('title', 'Register - Coffee Street')

@section('content')
<div class="cs-auth-split">
    <!-- Left Hero Panel (Desktop Only) -->
    <div class="cs-auth-hero">
        <div class="cs-auth-hero-content">
            <div class="cs-auth-hero-logo">
                <i class="fa fa-coffee"></i> Coffee Street
            </div>
            <h1 class="cs-auth-hero-title">Start Your Journey!</h1>
            <p class="cs-auth-hero-text">
                Join the Coffee Street club to get access to custom premium blends, rewards points, faster ordering, and exclusive seasonal member offers.
            </p>
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="cs-auth-form-side">
        <div class="cs-auth-form-card">
            <h2 class="h3 fw-bold mb-1" style="color: var(--cs-brown);">Create Account</h2>
            <p class="text-muted small mb-4">Start ordering premium coffee today</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="cs-form-group">
                    <label for="name" class="cs-form-label">Full Name</label>
                    <input id="name" class="cs-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
                    @error('name')
                        <div class="cs-validation-error">
                            <i class="fa fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="cs-form-group">
                    <label for="email" class="cs-form-label">Email Address</label>
                    <input id="email" class="cs-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com">
                    @error('email')
                        <div class="cs-validation-error">
                            <i class="fa fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="cs-form-group">
                    <label for="password" class="cs-form-label">Password</label>
                    <div class="cs-password-wrapper">
                        <input id="password" class="cs-input" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
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

                <!-- Confirm Password -->
                <div class="cs-form-group mb-4">
                    <label for="password_confirmation" class="cs-form-label">Confirm Password</label>
                    <div class="cs-password-wrapper">
                        <input id="password_confirmation" class="cs-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                        <button type="button" class="cs-password-toggle" onclick="togglePasswordVisibility('password_confirmation', 'confirm-password-toggle-icon')" aria-label="Toggle password visibility">
                            <i id="confirm-password-toggle-icon" class="fa fa-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="cs-validation-error">
                            <i class="fa fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-lg btn-primary rounded-3 text-white">
                        Create Account <i class="fa fa-coffee ms-1" style="font-size: 0.95rem;"></i>
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <span class="text-muted small">Already registered?</span>
                    <a href="{{ route('login') }}" class="small text-decoration-none ms-1" style="color: var(--cs-orange); font-weight: 600;">
                        Sign In
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
