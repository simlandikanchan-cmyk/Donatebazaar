@extends('layouts.app')

@push('styles') @vite(['resources/css/public/errors-4.css']) @endpush

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
            <div class="e-sub">We'll Be Back Soon</div>
            <h1 class="e-title">Scheduled Maintenance</h1>
            <p class="e-desc">We're making things better. The site is temporarily down for scheduled maintenance.</p>
            <p class="e-desc">Please check back in a little while.</p>
            <div class="e-actions">
                <x-button variant="primary" href="/">
                    <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;"><path d="M3 12l9-9 9 9"/><path d="M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/></svg>
                    Try Again
                </x-button>
            </div>
        </div>
    </div>
</div>
@endsection
