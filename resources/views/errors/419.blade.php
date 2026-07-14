@extends('layouts.app')

@push('styles') @vite(['resources/css/errors.css']) @endpush

@section('content')
<div id="error-root">
    <div class="e-hero">
        <div class="e-ring e-ring-1"></div>
        <div class="e-ring e-ring-2"></div>
        <div class="e-ring e-ring-3"></div>
        <div class="e-glows"></div>
        <div class="e-fade"></div>

        <div class="e-inner">
            <div class="e-icon">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="e-sub">Session Expired</div>
            <h1 class="e-title">Your session has timed out</h1>
            <p class="e-desc">For security reasons, your session expired. Please submit the form again after refreshing the page.</p>
            <div class="e-actions">
                <a href="{{ url()->previous() }}" class="btn-home">
                    <svg viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
                    Refresh &amp; Try Again
                </a>
                <a href="/" class="btn-back">
                    <svg viewBox="0 0 24 24"><path d="M3 12l9-9 9 9"/><path d="M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/></svg>
                    Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
