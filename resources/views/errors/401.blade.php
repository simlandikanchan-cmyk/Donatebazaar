@extends('layouts.app')

@push('styles') @vite(['resources/css/public/errors.css']) @endpush

@section('content')
<div id="error-root">
    <div class="e-hero">
        <div class="e-ring e-ring-1"></div>
        <div class="e-ring e-ring-2"></div>
        <div class="e-ring e-ring-3"></div>
        <div class="e-glows"></div>
        <div class="e-fade"></div>

        <div class="e-inner">
            <div class="e-code">4<span class="dim">0</span>1</div>
            <div class="e-sub">Unauthorized</div>
            <h1 class="e-title">Authentication required</h1>
            <p class="e-desc">You need to log in to access this page. Please sign in with your credentials and try again.</p>
            <div class="e-actions">
                <x-button variant="primary" href="{{ route('login') }}">
                    <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Sign In
                </x-button>
                <x-button variant="secondary" href="/">
                    <svg viewBox="0 0 24 24"><path d="M3 12l9-9 9 9"/><path d="M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/></svg>
                    Home
                </x-button>
            </div>
        </div>
    </div>
</div>
@endsection
