@extends('layouts.app')

@section('title', 'Profile - Coffee Street')

@section('content')
<div class="container py-5" style="max-width: 800px;">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; background-color: var(--cs-cream); color: var(--cs-orange);">
            <i class="fa-solid fa-circle-user fa-2xl"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-0" style="color: var(--cs-brown);">Account Settings</h2>
            <p class="text-muted small mb-0">Manage your profile, password, and security</p>
        </div>
    </div>

    @include('profile.partials.update-profile-information-form')

    @include('profile.partials.update-password-form')

    @include('profile.partials.delete-user-form')
</div>
@endsection
