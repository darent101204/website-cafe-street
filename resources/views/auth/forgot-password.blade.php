@extends('layouts.app')

@section('title', 'Forgot Password - Coffee Street')

@section('content')
<div class="cs-auth-compact-container">
    <div class="cs-auth-compact-card">
        <h2 class="h4 fw-bold mb-2" style="color: var(--cs-brown);">Reset Password</h2>
        <p class="text-muted small mb-4">
            {{ __('Forgot your password? No problem. Just enter your email address and we will email you a password reset link.') }}
        </p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success border-0 rounded-3 mb-4 small" style="background-color: var(--cs-cream); color: var(--cs-brown);">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="cs-form-group mb-4">
                <label for="email" class="cs-form-label">Email Address</label>
                <input id="email" class="cs-input" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                @error('email')
                    <div class="cs-validation-error">
                        <i class="fa fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-lg btn-primary rounded-3 text-white">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="small text-decoration-none" style="color: var(--cs-orange); font-weight: 600;">
                    <i class="fa fa-arrow-left-long me-1"></i> Back to Sign In
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
