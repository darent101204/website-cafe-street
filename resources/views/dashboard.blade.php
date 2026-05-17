@extends('layouts.app')

@section('title', 'Dashboard - Coffee Street')

@section('content')
    <div class="py-5">
        <div class="container">
            <h2 class="h4 font-weight-bold text-dark mb-4">
                {{ __('Dashboard') }}
            </h2>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-dark">
                        {{ __("You're logged in!") }}
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('home') }}" class="btn btn-primary rounded-5" style="background-color: #FF902A; border: none;">
                    Go to Home
                </a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark rounded-5 ms-2">
                        Manage Orders
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
