<div class="cs-profile-card">
    <div class="cs-profile-header">
        <h3 class="cs-profile-title">{{ __('Profile Information') }}</h3>
        <p class="cs-profile-desc">{{ __("Update your account's profile information and email address.") }}</p>
    </div>

    <div class="cs-profile-body">
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <!-- Name -->
            <div class="cs-form-group">
                <label for="name" class="cs-form-label">Full Name</label>
                <input id="name" name="name" type="text" class="cs-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
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
                <input id="email" name="email" type="email" class="cs-input" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                    <div class="cs-validation-error">
                        <i class="fa fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 p-3 rounded-3" style="background-color: var(--cs-light); border: 1px dashed var(--cs-orange-light);">
                        <p class="text-sm mb-2 text-dark font-semibold">
                            {{ __('Your email address is unverified.') }}
                        </p>
                        <button form="send-verification" class="btn btn-sm btn-outline-primary">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-success small fw-semibold">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary rounded-3 px-4 text-white">
                    {{ __('Save Changes') }}
                </button>

                @if (session('status') === 'profile-updated')
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
