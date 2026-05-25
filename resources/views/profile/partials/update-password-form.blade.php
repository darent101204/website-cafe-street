<div class="cs-profile-card">
    <div class="cs-profile-header">
        <h3 class="cs-profile-title">{{ __('Update Password') }}</h3>
        <p class="cs-profile-desc">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
    </div>

    <div class="cs-profile-body">
        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <!-- Current Password -->
            <div class="cs-form-group">
                <label for="update_password_current_password" class="cs-form-label">Current Password</label>
                <div class="cs-password-wrapper">
                    <input id="update_password_current_password" name="current_password" type="password" class="cs-input" autocomplete="current-password" placeholder="••••••••">
                    <button type="button" class="cs-password-toggle" onclick="togglePasswordVisibility('update_password_current_password', 'current-password-toggle-icon')" aria-label="Toggle password visibility">
                        <i id="current-password-toggle-icon" class="fa fa-eye"></i>
                    </button>
                </div>
                @error('current_password', 'updatePassword')
                    <div class="cs-validation-error">
                        <i class="fa fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- New Password -->
            <div class="cs-form-group">
                <label for="update_password_password" class="cs-form-label">New Password</label>
                <div class="cs-password-wrapper">
                    <input id="update_password_password" name="password" type="password" class="cs-input" autocomplete="new-password" placeholder="••••••••">
                    <button type="button" class="cs-password-toggle" onclick="togglePasswordVisibility('update_password_password', 'new-password-toggle-icon')" aria-label="Toggle password visibility">
                        <i id="new-password-toggle-icon" class="fa fa-eye"></i>
                    </button>
                </div>
                @error('password', 'updatePassword')
                    <div class="cs-validation-error">
                        <i class="fa fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="cs-form-group mb-4">
                <label for="update_password_password_confirmation" class="cs-form-label">Confirm New Password</label>
                <div class="cs-password-wrapper">
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="cs-input" autocomplete="new-password" placeholder="••••••••">
                    <button type="button" class="cs-password-toggle" onclick="togglePasswordVisibility('update_password_password_confirmation', 'confirm-new-password-toggle-icon')" aria-label="Toggle password visibility">
                        <i id="confirm-new-password-toggle-icon" class="fa fa-eye"></i>
                    </button>
                </div>
                @error('password_confirmation', 'updatePassword')
                    <div class="cs-validation-error">
                        <i class="fa fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary rounded-3 px-4 text-white">
                    {{ __('Update Password') }}
                </button>

                @if (session('status') === 'password-updated')
                    <span
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-success small fw-semibold"
                    >
                        <i class="fa fa-circle-check"></i> {{ __('Saved.') }}
                    </span>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
    if (typeof togglePasswordVisibility !== 'function') {
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
    }
</script>
