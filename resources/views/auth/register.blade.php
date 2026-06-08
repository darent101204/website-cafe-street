@extends('layouts.app')

@section('title', 'Register - Coffee Street')

@section('content')
<<<<<<< Updated upstream
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Create Account</h2>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" class="form-control rounded-5" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control rounded-5" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" class="form-control rounded-5" type="password" name="password" required autocomplete="new-password">
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input id="password_confirmation" class="form-control rounded-5" type="password" name="password_confirmation" required autocomplete="new-password">
                            @error('password_confirmation')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg rounded-5 text-white" style="background-color: #FF902A;">
                                Register
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a class="text-muted small text-decoration-none" href="{{ route('login') }}">
                                Already registered?
                            </a>
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
            <h1 class="cs-auth-hero-title">Start Your Journey!</h1>
            <p class="cs-auth-hero-text">
                Join the Plan B club to get access to custom premium blends, rewards points, faster ordering, and exclusive seasonal member offers.
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
>>>>>>> Stashed changes
        </div>
    </div>
</div>
@endsection
