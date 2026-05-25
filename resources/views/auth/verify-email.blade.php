@extends('layouts.app')

@section('title', 'Verify Email - Coffee Street')

@section('content')
<div class="cs-auth-compact-container">
    <div class="cs-auth-compact-card">
        <h2 class="h4 fw-bold mb-2" style="color: var(--cs-brown);">Verify Email</h2>
        <p class="text-muted small mb-4">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success border-0 rounded-3 mb-4 small" style="background-color: var(--cs-cream); color: var(--cs-brown);">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="mt-4 d-flex flex-column gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div class="d-grid">
                    <button type="submit" class="btn btn-lg btn-primary rounded-3 text-white">
                        {{ __('Resend Verification Email') }}
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="btn btn-link text-muted small text-decoration-none" style="border: none; background: none; font-weight: 500;">
                    <i class="fa fa-right-from-bracket me-1"></i> {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
