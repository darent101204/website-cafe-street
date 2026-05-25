<div class="cs-profile-card border-danger" style="border-color: rgba(220, 53, 69, 0.2) !important;">
    <div class="cs-profile-header bg-danger-subtle" style="background-color: #FFF2F2 !important; border-bottom: 1px solid rgba(220, 53, 69, 0.1);">
        <h3 class="cs-profile-title text-danger">{{ __('Delete Account') }}</h3>
        <p class="cs-profile-desc text-danger" style="opacity: 0.85;">{{ __('Permanently delete your account and all associated data.') }}</p>
    </div>

    <div class="cs-profile-body">
        <p class="text-muted small mb-4">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>

        <button
            type="button"
            class="btn btn-danger rounded-3 px-4 text-white"
            data-bs-toggle="modal"
            data-bs-target="#confirmUserDeletionModal"
        >
            {{ __('Delete Account') }}
        </button>

        <!-- Bootstrap Modal -->
        <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow-lg" style="overflow: hidden;">
                    <div class="modal-header border-0 pb-0" style="padding: 1.5rem 1.5rem 0.5rem 1.5rem;">
                        <h4 class="modal-title fw-bold text-dark h5" id="confirmUserDeletionLabel">
                            {{ __('Are you sure you want to delete your account?') }}
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')

                        <div class="modal-body" style="padding: 0.5rem 1.5rem 1.5rem 1.5rem;">
                            <p class="text-muted small mb-3">
                                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                            </p>

                            <!-- Password -->
                            <div class="cs-form-group mb-0">
                                <label for="delete_password" class="cs-form-label sr-only">Password</label>
                                <div class="cs-password-wrapper">
                                    <input id="delete_password" name="password" type="password" class="cs-input" placeholder="{{ __('Password') }}" required>
                                    <button type="button" class="cs-password-toggle" onclick="togglePasswordVisibility('delete_password', 'delete-password-toggle-icon')" aria-label="Toggle password visibility">
                                        <i id="delete-password-toggle-icon" class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('password', 'userDeletion')
                                    <div class="cs-validation-error">
                                        <i class="fa fa-circle-exclamation"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer border-0 bg-light rounded-bottom-4" style="padding: 1rem 1.5rem; gap: 0.5rem;">
                            <button type="button" class="btn btn-outline-dark btn-sm rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 36px;">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" class="btn btn-danger btn-sm rounded-3 px-3 text-white" style="min-height: 36px;">
                                {{ __('Delete Account') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

@if ($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
        deleteModal.show();
    });
</script>
@endif
