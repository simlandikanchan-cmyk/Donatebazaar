@extends('layouts.app')

@push('styles') @vite(['resources/css/errors-3.css']) @endpush

@section('content')
<div id="error-root">
    <div class="e-hero">
        <div class="e-ring e-ring-1"></div>
        <div class="e-ring e-ring-2"></div>
        <div class="e-ring e-ring-3"></div>
        <div class="e-glows"></div>
        <div class="e-fade"></div>

        <div class="e-inner">
            <div class="e-code">4<span class="dim">0</span>4</div>
            <div class="e-sub">Page Not Found</div>
            <h1 class="e-title">Looks like you're lost in the crowd</h1>
            <p class="e-desc">The page you're looking for doesn't exist or has been moved. Let's get you back on track.</p>
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
