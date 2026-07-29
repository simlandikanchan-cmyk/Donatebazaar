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
            <div class="e-icon">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="e-sub">Too Many Requests</div>
            <h1 class="e-title">You're moving too fast</h1>
            <p class="e-desc">You've made too many requests in a short period. Please wait a moment before trying again.</p>
            <div class="e-actions">
                <a href="/" class="btn-home">
                    <svg viewBox="0 0 24 24"><path d="M3 12l9-9 9 9"/><path d="M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/></svg>
                    Back to Home
                </a>
                <button class="btn-back" onclick="window.history.back()">
                    <svg viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Go Back
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
