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
                <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            </div>
            <div class="e-sub">Forbidden</div>
            <h1 class="e-title">You don't have permission</h1>
            <p class="e-desc">This area is restricted. If you believe this is an error, please contact the site administrator.</p>
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
