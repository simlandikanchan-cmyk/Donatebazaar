@extends('layouts.user')

@section('page_title', 'Create Event')
@section('page_subtitle', Str::limit($campaign->title, 45))

@section('topbar_left_prefix')
<a href="{{ route('campaign.show', $campaign->id) }}" class="topbar-back" title="Back to Campaign">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
</a>
@endsection

@php
    $raised = $campaign->raised_amount ?? 0;
    $goal   = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
    $pct    = min(100, round(($raised / $goal) * 100));
@endphp

@section('content')
<x-page-hero
    tag="Events"
    title="Create Event"
    subtitle="{{ $campaign->title }}"
>
    <x-slot:badges>
        <span class="wb-badge wbb-green">{{ $pct }}% funded</span>
        <span class="wb-badge wbb-primary">{{ ucfirst($campaign->campaign_state) }}</span>
    </x-slot:badges>
    <x-slot:actions>
        <x-button variant="primary" href="{{ route('campaign.show', $campaign->id) }}" class="wb-btn wb-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            View Campaign
        </x-button>
    </x-slot:actions>
</x-page-hero>
<div class="page-grid">

    {{-- ═════ LEFT — FORM ═════ --}}
    <div>

        @if(session('success'))
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('events.store', $campaign->id) }}" method="POST" enctype="multipart/form-data" id="eventForm">
            @csrf

            {{-- Card 1: Basic Info --}}
            <div class="card d1">
                <div class="card-header">
                    <div class="card-icon ic-indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Basic Information</div>
                        <div class="card-sub">Event name and description</div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="form-section">
                        <label class="form-label" for="title">Event Title <span class="req">*</span></label>
                        <input
                            id="title" type="text" name="title"
                            value="{{ old('title') }}"
                            placeholder="e.g. Annual Charity Walkathon 2025"
                            required
                            class="form-input {{ $errors->has('title') ? 'err' : '' }}"
                        >
                        @error('title')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-section">
                        <label class="form-label" for="description">Description <span class="req">*</span></label>
                        <textarea
                            id="description" name="description" rows="4" required
                            placeholder="Describe the event, what attendees can expect, and how it connects to the campaign…"
                            class="form-textarea {{ $errors->has('description') ? 'err' : '' }}"
                        >{{ old('description') }}</textarea>
                        @error('description')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>

            {{-- Card 2: Date & Time --}}
            <div class="card d2">
                <div class="card-header">
                    <div class="card-icon ic-yellow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Date &amp; Time</div>
                        <div class="card-sub">Schedule your event</div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="form-section">
                        <label class="form-label" for="event_date">Event Date <span class="req">*</span></label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <input
                                id="event_date" type="date" name="event_date"
                                value="{{ old('event_date') }}" required
                                class="form-input {{ $errors->has('event_date') ? 'err' : '' }}"
                            >
                        </div>
                        @error('event_date')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-row">
                        <div class="form-section">
                            <label class="form-label" for="start_time">Start Time</label>
                            <div class="input-wrap">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <input
                                    id="start_time" type="time" name="start_time"
                                    value="{{ old('start_time') }}"
                                    class="form-input {{ $errors->has('start_time') ? 'err' : '' }}"
                                >
                            </div>
                            @error('start_time')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-section">
                            <label class="form-label" for="end_time">End Time</label>
                            <div class="input-wrap">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <input
                                    id="end_time" type="time" name="end_time"
                                    value="{{ old('end_time') }}"
                                    class="form-input {{ $errors->has('end_time') ? 'err' : '' }}"
                                >
                            </div>
                            @error('end_time')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- Card 3: Location --}}
            <div class="card d3">
                <div class="card-header">
                    <div class="card-icon ic-green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Location</div>
                        <div class="card-sub">Where will the event take place?</div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="form-section">
                        <label class="form-label" for="location">Venue / Address</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <input
                                id="location" type="text" name="location"
                                value="{{ old('location') }}"
                                placeholder="e.g. City Park, Mumbai or Online (Zoom)"
                                class="form-input {{ $errors->has('location') ? 'err' : '' }}"
                            >
                        </div>
                        @error('location')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>

            {{-- Card 4: Goals & Capacity --}}
            <div class="card d4">
                <div class="card-header">
                    <div class="card-icon ic-pink">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Goals &amp; Capacity</div>
                        <div class="card-sub">Optional — leave blank to skip</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-section">
                            <label class="form-label" for="goal_amount">Goal Amount</label>
                            <div class="input-wrap">
                                <span class="input-prefix">₹</span>
                                <input
                                    id="goal_amount" type="number" step="0.01" min="0"
                                    name="goal_amount" value="{{ old('goal_amount') }}"
                                    placeholder="0.00"
                                    class="form-input has-prefix {{ $errors->has('goal_amount') ? 'err' : '' }}"
                                >
                            </div>
                            <p class="form-hint">Fundraising target for this event</p>
                            @error('goal_amount')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-section">
                            <label class="form-label" for="max_participants">Max Participants</label>
                            <div class="input-wrap">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                                <input
                                    id="max_participants" type="number" min="1"
                                    name="max_participants" value="{{ old('max_participants') }}"
                                    placeholder="Unlimited"
                                    class="form-input {{ $errors->has('max_participants') ? 'err' : '' }}"
                                >
                            </div>
                            <p class="form-hint">Leave blank for unlimited seats</p>
                            @error('max_participants')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 5: Cover Image --}}
            <div class="card d5">
                <div class="card-header">
                    <div class="card-icon ic-blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Cover Image</div>
                        <div class="card-sub">Optional — Recommended: 1200×600px, max 2MB</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="upload-zone" id="createUploadZone">
                        <input type="file" name="cover_image" id="createCoverInput" accept="image/*" onchange="previewCreateImage(this)">
                        <img src="" alt="Preview" class="upload-preview" id="createUploadPreview">
                        <div class="upload-overlay"><span>Click to change</span></div>
                        <div id="createUploadPlaceholder">
                            <div class="upload-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="upload-text">Click to upload cover image</div>
                            <div class="upload-sub">PNG, JPG, WEBP — max 2MB</div>
                        </div>
                    </div>
                    @error('cover_image')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Submit --}}
            <x-button variant="primary" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Create Event
            </x-button>

        </form>
    </div>

    {{-- ═════ RIGHT COL ═════ --}}
    <div class="right-col">

        {{-- Campaign Preview --}}
        <div class="card" style="--delay:.08s">
            <div class="card-header">
                <div class="card-icon ic-indigo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div>
                    <div class="card-title">Campaign</div>
                    <div class="card-sub">This event belongs to</div>
                </div>
            </div>
            <div class="card-body">
                @if($campaign->cover_image)
                    <img src="{{ asset('storage/'.$campaign->cover_image) }}" class="preview-thumb" alt="{{ $campaign->title }}">
                @endif
                <div class="preview-title">{{ $campaign->title }}</div>
                <div class="preview-meta">Created {{ $campaign->created_at->diffForHumans() }}</div>
                <div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
                <div class="prog-pct">{{ $pct }}% funded · ₹{{ number_format($raised) }} raised</div>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="card" style="--delay:.14s">
            <div class="card-header">
                <div class="card-icon ic-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div><div class="card-title">Quick Links</div></div>
            </div>
            <div class="card-body">
                <a href="{{ route('campaign.show', $campaign->id) }}" class="action-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Campaign Overview
                </a>
                <a href="{{ route('campaign.edit', $campaign->id) }}" class="action-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Campaign
                </a>
                <x-button variant="primary" href="{{ route('dashboard') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Back to Dashboard
                </x-button>
            </div>
        </div>

        {{-- Tips --}}
        <div class="card" style="--delay:.20s">
            <div class="card-header">
                <div class="card-icon ic-pink">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <div><div class="card-title">Tips</div><div class="card-sub">Make your event stand out</div></div>
            </div>
            <div class="card-body">
                <div class="tips-list">
                    <div class="tip-row">
                        <div class="tip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></div>
                        <p class="tip-text">Use a clear, action-oriented title that tells people exactly what to expect.</p>
                    </div>
                    <div class="tip-row">
                        <div class="tip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                        <p class="tip-text">Setting start and end times helps donors plan their participation easily.</p>
                    </div>
                    <div class="tip-row">
                        <div class="tip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                        <p class="tip-text">Adding a fundraising goal motivates attendees to contribute more.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /.right-col --}}
</div>{{-- /.page-grid --}}
@endsection

@push('page_styles')
<style>
.page-grid{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;}
.right-col{position:sticky;top:84px;display:flex;flex-direction:column;gap:16px;}

.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp 0.4s both;margin-bottom:16px;}
.card:last-child{margin-bottom:0;}
.right-col .card{margin-bottom:0;}
.card-header{padding:15px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;}
.card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:15px;height:15px;}
.ic-indigo{background:rgba(99,102,241,0.12);color:var(--accent);}
.ic-green {background:rgba(16,185,129,0.12);color:var(--green);}
.ic-yellow{background:rgba(245,158,11,0.12);color:var(--yellow);}
.ic-pink  {background:rgba(236,72,153,0.12);color:#ec4899;}
.card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-0.01em;}
.card-sub  {font-size:11px;color:var(--text3);margin-top:1px;}
.card-body {padding:20px;}

@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:translateY(0);}}

.form-section{margin-bottom:18px;}
.form-section:last-child{margin-bottom:0;}
.form-label{
    display:block;font-size:11px;font-weight:600;color:var(--text2);
    text-transform:uppercase;letter-spacing:0.08em;font-family:var(--font-mono);margin-bottom:6px;
}
.form-label .req{color:var(--red);margin-left:2px;}

.form-input,
.form-textarea,
.form-select{
    width:100%;background:var(--surface2);border:1px solid var(--border2);
    border-radius:var(--radius-sm);padding:10px 13px;
    font-size:13px;color:var(--text);font-family:var(--font);outline:none;
    transition:border-color var(--transition),box-shadow var(--transition),background var(--transition);
    appearance:none;-webkit-appearance:none;
}
.form-input::placeholder,.form-textarea::placeholder{color:var(--text3);}
.form-input:focus,.form-textarea:focus,.form-select:focus{
    border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);background:var(--surface);
}
.form-input.err,.form-textarea.err{border-color:var(--red);box-shadow:0 0 0 3px rgba(239,68,68,0.10);}
.form-textarea{resize:vertical;min-height:100px;line-height:1.65;}

.input-wrap{position:relative;}
.input-wrap .input-icon{
    position:absolute;left:12px;top:50%;transform:translateY(-50%);
    width:14px;height:14px;color:var(--text3);pointer-events:none;
}
.input-wrap .form-input{padding-left:36px;}
.input-prefix{
    position:absolute;left:0;top:0;bottom:0;
    display:flex;align-items:center;padding:0 12px;
    font-size:13px;font-weight:600;color:var(--text3);font-family:var(--font-mono);
    border-right:1px solid var(--border2);background:var(--surface2);
    border-radius:var(--radius-sm) 0 0 var(--radius-sm);pointer-events:none;
}
.has-prefix{padding-left:44px;border-radius:0 var(--radius-sm) var(--radius-sm) 0 !important;}

.form-hint{font-size:11px;color:var(--text3);margin-top:5px;font-family:var(--font-mono);}
.form-hint.err-msg{color:var(--red);}

.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}

.alert{
    display:flex;align-items:flex-start;gap:10px;
    padding:12px 14px;border-radius:var(--radius-sm);
    font-size:12.5px;margin-bottom:18px;border:1px solid transparent;
    animation:fadeUp 0.35s both;
}
.alert svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}
.alert-success{background:rgba(16,185,129,0.08);border-color:rgba(16,185,129,0.25);color:#065f46;}
.alert-error  {background:rgba(239,68,68,0.08); border-color:rgba(239,68,68,0.25); color:#991b1b;}
[data-theme="dark"] .alert-success{color:#189d68;}
[data-theme="dark"] .alert-error  {color:#a72a2a;}
.alert ul{list-style:disc;padding-left:16px;display:flex;flex-direction:column;gap:3px;}

.submit-btn{
    display:flex;align-items:center;justify-content:center;gap:8px;
    width:100%;padding:13px 20px;border-radius:var(--radius-sm);
    font-size:14px;font-weight:700;cursor:pointer;border:none;font-family:var(--font);
    background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;
    box-shadow:0 4px 18px rgba(99,102,241,0.35);letter-spacing:-0.01em;
    transition:opacity var(--transition),transform var(--transition),box-shadow var(--transition);
}
.submit-btn:hover{opacity:.92;transform:translateY(-1px);box-shadow:0 7px 24px rgba(99,102,241,0.45);}
.submit-btn:active{transform:translateY(0);}
.submit-btn svg{width:15px;height:15px;}

.action-btn{
    display:flex;align-items:center;justify-content:center;gap:6px;
    width:100%;padding:10px 16px;border-radius:var(--radius-sm);
    font-size:12.5px;font-weight:600;cursor:pointer;
    border:1px solid var(--border2);background:var(--surface2);
    color:var(--text2);font-family:var(--font);text-decoration:none;
    transition:all var(--transition);margin-bottom:8px;
}
.action-btn:last-child{margin-bottom:0;}
.action-btn:hover{background:var(--accent-glow);color:var(--accent);border-color:var(--accent);transform:translateY(-1px);}
.action-btn svg{width:13px;height:13px;}

.preview-thumb{width:100%;height:100px;object-fit:cover;border-radius:var(--radius-sm);margin-bottom:12px;display:block;}
.preview-title{font-size:14px;font-weight:700;color:var(--text);letter-spacing:-0.01em;line-height:1.4;margin-bottom:5px;}
.preview-meta {font-size:11px;color:var(--text3);font-family:var(--font-mono);}
.prog-bar{width:100%;background:var(--surface2);border-radius:100px;height:5px;overflow:hidden;margin:10px 0 4px;}
.prog-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--accent),var(--accent2));}
.prog-pct{font-size:10px;color:var(--text3);font-family:var(--font-mono);}

.tips-list{display:flex;flex-direction:column;gap:12px;}
.tip-row{display:flex;align-items:flex-start;gap:10px;}
.tip-icon{width:26px;height:26px;border-radius:7px;background:rgba(99,102,241,0.10);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.tip-icon svg{width:13px;height:13px;color:var(--accent);}
.tip-text{font-size:11.5px;color:var(--text2);line-height:1.6;padding-top:3px;}

.ic-blue  {background:rgba(59,130,246,0.12);color:#3b82f6;}
.upload-zone{border:2px dashed var(--border2);border-radius:var(--radius-sm);padding:28px 20px;text-align:center;cursor:pointer;transition:all .2s ease;position:relative;overflow:hidden;background:var(--surface2);}
.upload-zone:hover{border-color:var(--accent);background:var(--accent-glow);}
.upload-zone.has-preview{border-style:solid;border-color:var(--accent);padding:0;}
.upload-zone input{position:absolute;inset:0;opacity:0;cursor:pointer;}
.upload-icon{width:44px;height:44px;border-radius:12px;background:var(--accent-glow);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;}
.upload-icon svg{width:20px;height:20px;color:var(--accent);}
.upload-text{font-size:13px;font-weight:600;color:var(--text2);}
.upload-sub{font-size:11px;color:var(--text3);margin-top:4px;}
.upload-preview{width:100%;height:160px;object-fit:cover;border-radius:calc(var(--radius-sm) - 2px);display:none;}
.upload-preview.show{display:block;}
.upload-overlay{position:absolute;inset:0;background:rgba(0,0,0,.4);display:none;align-items:center;justify-content:center;border-radius:calc(var(--radius-sm) - 2px);}
.upload-zone.has-preview:hover .upload-overlay{display:flex;}
.upload-overlay span{color:#fff;font-size:12px;font-weight:600;font-family:var(--font-mono);}
.alert-icon{width:18px;height:18px;flex-shrink:0;margin-top:2px;}
.d1{animation-delay:var(--delay,.05s)}.d2{animation-delay:var(--delay,.10s)}.d3{animation-delay:var(--delay,.15s)}.d4{animation-delay:var(--delay,.20s)}.d5{animation-delay:var(--delay,.25s)}

@media(max-width:960px){
    .page-grid{grid-template-columns:1fr;}
    .right-col{position:static;}
    .form-row{grid-template-columns:1fr;}
}
</style>
@endpush

@push('page_scripts')
<script>
(function(){
'use strict';

/* ── SUBMIT GUARD ── */
document.getElementById('eventForm').addEventListener('submit', function(){
    var btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Creating…';
});

window.previewCreateImage = function(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var preview = document.getElementById('createUploadPreview');
        var zone = document.getElementById('createUploadZone');
        var placeholder = document.getElementById('createUploadPlaceholder');
        preview.src = e.target.result;
        preview.classList.add('show');
        placeholder.style.display = 'none';
        zone.classList.add('has-preview');
    };
    reader.readAsDataURL(input.files[0]);
};

})();
</script>
<style>
@keyframes spin{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}
</style>
@endpush
