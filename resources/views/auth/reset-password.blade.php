@extends('layouts.app')

@section('title', 'Reset Password - Coffee Street')

@section('content')
<div class="cs-auth-compact-container">
    <div class="cs-auth-compact-card">
        <h2 class="h4 fw-bold mb-1" style="color: var(--cs-brown);">New Password</h2>
        <p class="text-muted small mb-4">Set your new account password</p>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="cs-form-group">
                <label for="email" class="cs-form-label">Email Address</label>
                <input id="email" class="cs-input" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="name@example.com">
                @error('email')
                    <div class="cs-validation-error">
                        <i class="fa fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="cs-form-group">
                <label for="password" class="cs-form-label">New Password</label>
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

            <div class="d-grid">
                <button type="submit" class="btn btn-lg btn-primary rounded-3 text-white">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
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
