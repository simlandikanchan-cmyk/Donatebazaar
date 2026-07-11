{{-- resources/views/events/register.blade.php --}}
@extends('layouts.app')

@php
    use Carbon\Carbon;
@endphp

@section('title', 'Register – ' . $event->title)

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    --bg:           #f4f5fb;
    --surface:      #ffffff;
    --surface2:     #f8f9fe;
    --surface3:     #f0f1fa;
    --border:       rgba(0,0,0,0.06);
    --border2:      rgba(0,0,0,0.10);
    --text:         #0f1117;
    --text2:        #4b5563;
    --text3:        #9ca3af;
    --accent:       #2563eb;
    --accent2:      #0d9488;
    --accent-glow:  rgba(37,99,235,0.18);
    --green:        #16a34a;
    --yellow:       #f59e0b;
    --red:          #ef4444;
    --blue:         #3b82f6;
    --font:         'DM Sans', sans-serif;
    --font-mono:    'DM Mono', monospace;
    --radius:       14px;
    --radius-sm:    10px;
    --radius-lg:    24px;
    --shadow:       0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --shadow-md:    0 4px 24px rgba(0,0,0,0.08);
    --shadow-lg:    0 8px 40px rgba(0,0,0,0.12);
    --transition:   0.22s cubic-bezier(0.4,0,0.2,1);
}

body {
    font-family:'DM Sans', sans-serif;
    background:var(--bg);
    color:var(--text);
}

.reg-wrap {
    max-width:640px;
    margin:0 auto;
    padding:48px 20px 64px;
}

.reg-card {
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:var(--radius);
    box-shadow:var(--shadow);
    overflow:hidden;
    margin-bottom:28px;
}
.reg-card-cover {
    height:200px;
    overflow:hidden;
    background:var(--surface2);
}
.reg-card-cover img {
    width:100%;height:100%;
    object-fit:cover;
}
.reg-card-header {
    padding:28px 28px 0;
}
.reg-badge {
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size:11px;
    font-weight:600;
    font-family:'DM Mono',monospace;
    letter-spacing:.04em;
    color:#065f46;
    background:rgba(16,185,129,.08);
    border:1px solid rgba(16,185,129,.2);
    border-radius:100px;
    padding:5px 14px;
    margin-bottom:16px;
}
.reg-badge::before {
    content:'';
    width:6px;height:6px;
    border-radius:50%;
    background:var(--green);
}
.reg-title {
    font-size:1.5rem;
    font-weight:700;
    letter-spacing:-.02em;
    margin-bottom:16px;
    color:var(--text);
}
.reg-meta {
    display:flex;
    flex-wrap:wrap;
    gap:16px;
    font-size:13px;
    color:var(--text3);
    margin-bottom:20px;
}
.reg-meta-item {
    display:inline-flex;
    align-items:center;
    gap:6px;
}
.reg-meta-item svg {
    width:15px;height:15px;
    stroke:var(--text3);
    flex-shrink:0;
}
.reg-meta-item strong {
    color:var(--text2);
    font-weight:600;
}

.reg-form-card {
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:var(--radius);
    box-shadow:var(--shadow);
    padding:28px;
}
.reg-form-title {
    font-size:1.1rem;
    font-weight:700;
    color:var(--text);
    margin-bottom:4px;
}
.reg-form-sub {
    font-size:13px;
    color:var(--text3);
    margin-bottom:24px;
}

.reg-field {
    margin-bottom:20px;
}
.reg-label {
    display:block;
    font-size:13px;
    font-weight:600;
    color:var(--text2);
    margin-bottom:6px;
}
.reg-label .req {
    color:#dc2626;
    font-weight:600;
}
.reg-input {
    display:block;
    width:100%;
    border:1.5px solid var(--border2);
    border-radius:var(--radius-sm);
    padding:11px 14px;
    font-size:14px;
    font-family:'DM Sans',sans-serif;
    color:var(--text);
    background:var(--surface);
    transition:border-color var(--transition), box-shadow var(--transition);
    outline:none;
}
.reg-input::placeholder {
    color:var(--text3);
    opacity:.6;
}
.reg-input:focus {
    border-color:var(--text);
    box-shadow:0 0 0 3px rgba(15,13,10,.06);
}
.reg-input.error {
    border-color:#dc2626;
    background:#fef2f2;
}
.reg-error {
    font-size:12px;
    color:#dc2626;
    margin-top:5px;
}
.reg-textarea {
    resize:none;
    min-height:80px;
}

.reg-btn {
    display:block;
    width:100%;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    color:#fff;
    font-family:'DM Sans',sans-serif;
    font-size:14px;
    font-weight:600;
    padding:14px 24px;
    border:none;
    border-radius:var(--radius-sm);
    cursor:pointer;
    box-shadow:0 6px 24px rgba(37,99,235,.45);
    transition:opacity var(--transition), transform var(--transition), box-shadow var(--transition);
}
.reg-btn:hover {
    opacity:.94;
    transform:translateY(-2px);
    box-shadow:0 12px 32px rgba(37,99,235,.55);
}
.reg-btn:active {
    transform:translateY(0);
}

.reg-footnote {
    margin-top:16px;
    text-align:center;
    font-size:12px;
    color:var(--text3);
}

.reg-back {
    text-align:center;
    margin-top:8px;
}
.reg-back a {
    font-size:13px;
    color:var(--text3);
    text-decoration:none;
    transition:color var(--transition);
}
.reg-back a:hover {
    color:var(--text);
}

.flash-error {
    display:flex;
    gap:12px;
    align-items:flex-start;
    background:#fef2f2;
    border:1px solid rgba(220,38,38,.15);
    color:#b91c1c;
    border-radius:var(--radius-sm);
    padding:14px 18px;
    font-size:13px;
    margin-bottom:24px;
}
.flash-error svg {
    width:18px;height:18px;
    fill:#dc2626;
    flex-shrink:0;
    margin-top:1px;
}

@media(max-width:480px){
    .reg-wrap{padding:24px 16px 48px;}
    .reg-card-header{padding:20px 20px 0;}
    .reg-form-card{padding:20px;}
    .reg-title{font-size:1.2rem;}
}
</style>

<div class="reg-wrap">

    {{-- ── EVENT CARD ── --}}
    <div class="reg-card">

        @if($event->cover_image)
        <div class="reg-card-cover">
            <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->title }}">
        </div>
        @endif

        <div class="reg-card-header">
            <div class="reg-badge">Active</div>

            <h1 class="reg-title">{{ $event->title }}</h1>

            <div class="reg-meta">
                <span class="reg-meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ Carbon::parse($event->event_date)->format('l, F j, Y') }}
                </span>

                @if($event->start_time)
                <span class="reg-meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ Carbon::parse($event->start_time)->format('g:i A') }}
                    @if($event->end_time)
                        &ndash; {{ Carbon::parse($event->end_time)->format('g:i A') }}
                    @endif
                </span>
                @endif

                @if($event->max_participants)
                <span class="reg-meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-5.356-3.779M9 20H4v-2a4 4 0 015.356-3.779M15 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <strong>{{ $event->remainingSpots() }}</strong> spot{{ $event->remainingSpots() !== 1 ? 's' : '' }} left
                </span>
                @endif

                @if($event->location)
                <span class="reg-meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $event->location }}
                </span>
                @endif
            </div>
        </div>
    </div>

    {{-- ── FLASH ERROR ── --}}
    @if(session('error'))
    <div class="flash-error">
        <svg viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-4.75a.75.75 0 001.5 0V8.75a.75.75 0 00-1.5 0v4.5zm.75-6.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- ── FORM ── --}}
    <div class="reg-form-card">
        <h2 class="reg-form-title">Register for this Event</h2>
        <p class="reg-form-sub">Fill in your details below — no account required.</p>

        <form action="{{ route('events.register.store', $event->id) }}" method="POST" novalidate>
            @csrf

            <div class="reg-field">
                <label for="name" class="reg-label">Full Name <span class="req">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       placeholder="Jane Doe" autocomplete="name"
                       class="reg-input @error('name') error @enderror">
                @error('name')
                <p class="reg-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="reg-field">
                <label for="email" class="reg-label">Email Address <span class="req">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       placeholder="jane@example.com" autocomplete="email"
                       class="reg-input @error('email') error @enderror">
                @error('email')
                <p class="reg-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="reg-field">
                <label for="phone" class="reg-label">Phone Number <span class="req">*</span></label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                       placeholder="+1 (555) 123-4567" autocomplete="tel"
                       class="reg-input @error('phone') error @enderror">
                @error('phone')
                <p class="reg-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="reg-field">
                <label for="message" class="reg-label">Note <span class="reg-label" style="font-weight:400;color:var(--text3);">(optional)</span></label>
                <textarea id="message" name="message" rows="3"
                          placeholder="Any questions or notes for the organiser?"
                          class="reg-input reg-textarea @error('message') error @enderror">{{ old('message') }}</textarea>
                @error('message')
                <p class="reg-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="reg-btn">
                Confirm Registration
            </button>
        </form>

        <p class="reg-footnote">A confirmation email will be sent after you register.</p>
    </div>

    <div class="reg-back">
        <a href="{{ route('events.show', $event->id) }}">&larr; Back to event details</a>
    </div>

</div>
@endsection