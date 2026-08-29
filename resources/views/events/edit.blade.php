@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
@vite(['resources/css/user/user.css', 'resources/css/public/events-edit.css', 'resources/js/public/events-edit.js'])

@php
    // Status chip
    if ($event->status === 'active') {
        $chipClass = 'chip-active'; $chipLabel = 'Active';
    } elseif ($event->status === 'pending') {
        $chipClass = 'chip-pending'; $chipLabel = 'Pending';
    } elseif ($event->status === 'completed') {
        $chipClass = 'chip-completed'; $chipLabel = 'Completed';
    } elseif ($event->status === 'cancelled') {
        $chipClass = 'chip-rejected'; $chipLabel = 'Cancelled';
    } else {
        $chipClass = 'chip-pending'; $chipLabel = ucfirst($event->status ?? 'Draft');
    }
@endphp

<div class="shell" id="shell">

{{-- ═══════════ SIDEBAR ═══════════ --}}
@include('partials.user-sidebar')

{{-- ═══════════ MAIN ═══════════ --}}
<div class="main">

    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <a href="{{ route('events.show', $event->id) }}" class="topbar-back" title="Back to Event">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
            </a>
            <div class="topbar-title">
                <h1>Edit Event</h1>
                <p>{{ Str::limit($event->title, 45) }}</p>
            </div>
        </div>
        <div class="topbar-right">
            <div class="theme-toggle">
                <input type="checkbox" id="themeToggle">
                <label for="themeToggle">
                    <div class="theme-icons">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    </div>
                </label>
            </div>
            <div class="c-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
        </div>
    </header>

    {{-- BODY --}}
    <div class="body">
        <div class="page-grid">

            {{-- ════ LEFT — FORM ════ --}}
            <div>

                {{-- Validation errors --}}
                @if ($errors->any())
                <div class="card card--mb">
                    <div class="card-header">
                        <div class="card-icon ic-red">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Please fix the following errors</div>
                            <div class="card-sub">{{ $errors->count() }} {{ Str::plural('issue', $errors->count()) }} found</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="error-block">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
                    @csrf
                    @method('PUT')

                    {{-- ── Basic Info ── --}}
                    <div class="card" style="margin-bottom:16px;">
                        <div class="card-header">
                            <div class="card-icon ic-primary">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Basic Information</div>
                                <div class="card-sub">Event title and description</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="field">
                                <label class="field-label" for="title">Event Title</label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    class="field-input"
                                    value="{{ old('title', $event->title) }}"
                                    placeholder="Enter a clear, compelling title…"
                                    required>
                            </div>
                            <div class="field">
                                <label class="field-label" for="description">Description</label>
                                <textarea
                                    id="description"
                                    name="description"
                                    class="field-textarea"
                                    placeholder="Describe what this event is about…"
                                    required>{{ old('description', $event->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ── Schedule ── --}}
                    <div class="card" style="margin-bottom:16px;">
                        <div class="card-header">
                            <div class="card-icon ic-yellow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Schedule</div>
                                <div class="card-sub">Date and timings</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="field-grid-3">
                                <div class="field">
                                    <label class="field-label" for="event_date">Event Date</label>
                                    <input
                                        type="date"
                                        id="event_date"
                                        name="event_date"
                                        class="field-input"
                                        value="{{ old('event_date', $event->event_date) }}"
                                        required>
                                </div>
                                <div class="field">
                                    <label class="field-label" for="start_time">Start Time</label>
                                    <input
                                        type="time"
                                        id="start_time"
                                        name="start_time"
                                        class="field-input"
                                        value="{{ old('start_time', $event->start_time) }}">
                                </div>
                                <div class="field">
                                    <label class="field-label" for="end_time">End Time</label>
                                    <input
                                        type="time"
                                        id="end_time"
                                        name="end_time"
                                        class="field-input"
                                        value="{{ old('end_time', $event->end_time) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Fundraising & Capacity ── --}}
                    <div class="card" style="margin-bottom:16px;">
                        <div class="card-header">
                            <div class="card-icon ic-green">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Fundraising &amp; Capacity</div>
                                <div class="card-sub">Goal and participant limits</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="field-grid-2">
                                <div class="field">
                                    <label class="field-label" for="goal_amount">Goal Amount</label>
                                    <div class="field-input-wrap">
                                        <span class="field-prefix">₹</span>
                                        <input
                                            type="number"
                                            id="goal_amount"
                                            name="goal_amount"
                                            class="field-input"
                                            step="0.01"
                                            min="0"
                                            value="{{ old('goal_amount', $event->goal_amount) }}"
                                            placeholder="0.00">
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="field-label" for="max_participants">Max Participants</label>
                                    <input
                                        type="number"
                                        id="max_participants"
                                        name="max_participants"
                                        class="field-input"
                                        min="0"
                                        value="{{ old('max_participants', $event->max_participants) }}"
                                        placeholder="Unlimited">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Cover Image ── --}}
                    <div class="card" style="margin-bottom:16px;">
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
                            <div class="upload-zone" id="editUploadZone">
                                <input type="file" name="cover_image" id="editCoverInput" accept="image/*" data-action="preview-edit-image">
                                <img src="{{ $event->cover_image ? asset('storage/'.$event->cover_image) : '' }}" alt="Preview"
                                     class="upload-preview {{ $event->cover_image ? 'show' : '' }}" id="editUploadPreview">
                                <div class="upload-overlay"><span>Click to change</span></div>
                                <div id="editUploadPlaceholder" @if(!$event->cover_image)class="show"@endif>
                                    <div class="upload-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="upload-text">Click to upload cover image</div>
                                    <div class="upload-sub">PNG, JPG, WEBP — max 2MB</div>
                                </div>
                            </div>
                            @error('cover_image')<p class="field-hint err-msg field-hint--mt">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- ── Submit row ── --}}
                    <div class="submit-row">
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-secondary btn-cancel">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                            Cancel
                        </a>
                        <x-button variant="primary" type="submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </x-button>
                    </div>

                </form>
            </div>

            {{-- ════ RIGHT ════ --}}
            <div class="right-col">

                {{-- Current status --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon ic-blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Event Info</div>
                        </div>
                    </div>
                    <div class="card-body card-body--col">
                        <div class="info-row">
                            <span class="info-row-label">Status</span>
                            <span class="status-chip {{ $chipClass }}"><span class="dot"></span>{{ $chipLabel }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-label">Date</span>
                            <span class="info-row-val">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                        </div>
                        @if($event->start_time)
                        <div class="info-row">
                            <span class="info-row-label">Start</span>
                            <span class="info-row-val">{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</span>
                        </div>
                        @endif
                        @if($event->end_time)
                        <div class="info-row">
                            <span class="info-row-label">End</span>
                            <span class="info-row-val">{{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-row-label">Campaign</span>
                            <span class="info-row-val info-row-val--accent">{{ Str::limit($event->campaign->title, 22) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon ic-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Quick Actions</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <x-button variant="primary" type="submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </x-button>
                        <a href="{{ route('events.show', $event->id) }}" class="action-btn btn-ghost">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View Event
                        </a>
                        <a href="{{ route('campaign.show', $event->campaign->id) }}" class="action-btn btn-ghost">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                            Back to Campaign
                        </a>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon ic-yellow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Tips</div>
                        </div>
                    </div>
                    <div class="card-body card-body--col card-body--gap">
                        <p class="text-muted-sm">A clear title and description help donors find and trust your event.</p>
                        <p class="text-muted-sm">Setting a goal amount shows your progress and motivates donors.</p>
                        <p class="text-muted-sm">Leave Max Participants blank for unlimited registrations.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

@endsection