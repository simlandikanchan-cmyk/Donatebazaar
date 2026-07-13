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

@push('styles')
<style>
.nl-unsub{display:flex;align-items:center;justify-content:center;min-height:70vh;padding:40px 20px;}
.nl-unsub-card{max-width:440px;width:100%;background:#fff;border:1px solid rgba(0,0,0,0.08);border-radius:16px;box-shadow:0 8px 40px rgba(0,0,0,0.10);padding:40px 32px;text-align:center;animation:fadeUp .5s both;}
.nl-unsub-icon{width:64px;height:64px;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;}
.nl-unsub-icon svg{width:30px;height:30px;}
.nl-icon-ok{background:rgba(16,185,129,0.12);color:#10b981;}
.nl-icon-warn{background:rgba(245,158,11,0.12);color:#f59e0b;}
.nl-icon-muted{background:rgba(107,114,128,0.12);color:#6b7280;}
.nl-unsub-card h1{font-size:22px;font-weight:800;color:#0f1117;margin-bottom:10px;letter-spacing:-0.03em;}
.nl-unsub-card p{font-size:13.5px;color:#4b5563;line-height:1.7;margin-bottom:4px;}
.nl-unsub-card .nl-sub{font-size:12px;color:#9ca3af;margin-top:8px;}
.nl-unsub-card .btn{display:inline-flex;align-items:center;gap:8px;margin-top:22px;padding:11px 24px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;}
.nl-unsub-card .btn svg{width:15px;height:15px;}
.btn-primary{background:#6366f1;color:#fff;border:1px solid #6366f1;}
.btn-primary:hover{opacity:.9;}
@keyframes fadeUp{0%{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}
</style>
@endpush
