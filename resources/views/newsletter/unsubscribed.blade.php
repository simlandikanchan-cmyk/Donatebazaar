@extends('layouts.app')

@section('title', 'Unsubscribed')
@section('meta_description', 'You have been unsubscribed from our newsletter.')

@section('content')
<div class="nl-unsub">
    <div class="nl-unsub-card">
        <div class="nl-unsub-icon {{ $found ? ($already ? 'nl-icon-warn' : 'nl-icon-ok') : 'nl-icon-muted' }}">
            @if($found && !$already)
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @elseif($found && $already)
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            @endif
        </div>

        @if($found && !$already)
            <h1>You're Unsubscribed</h1>
            <p>You have been successfully removed from our mailing list. You will no longer receive newsletters from us.</p>
            <p class="nl-sub">Changed your mind? You can subscribe again anytime from our website.</p>
        @elseif($found && $already)
            <h1>Already Unsubscribed</h1>
            <p>This email was already unsubscribed from our mailing list.</p>
        @else
            <h1>Invalid Link</h1>
            <p>This unsubscribe link is invalid or expired. If you believe this is an error, please contact our support team.</p>
        @endif

        <a href="{{ url('/') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Go to Homepage
        </a>
    </div>
</div>
@endsection

@push('styles') @vite(['resources/css/public/newsletter-unsubscribed.css']) @endpush
