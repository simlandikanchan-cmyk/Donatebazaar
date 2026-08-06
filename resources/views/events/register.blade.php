{{-- resources/views/events/register.blade.php --}}
@extends('layouts.app')

@php
    use Carbon\Carbon;
@endphp

@section('title', 'Register – ' . $event->title)

@section('content')
@push('styles') @vite(['resources/css/public/events-register.css']) @endpush

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
                <label for="message" class="reg-label">Note <span class="reg-label reg-label--muted">(optional)</span></label>
                <textarea id="message" name="message" rows="3"
                          placeholder="Any questions or notes for the organiser?"
                          class="reg-input reg-textarea @error('message') error @enderror">{{ old('message') }}</textarea>
                @error('message')
                <p class="reg-error">{{ $message }}</p>
                @enderror
            </div>

            <x-button variant="primary" type="submit">
                Confirm Registration
            </x-button>
        </form>

        <p class="reg-footnote">A confirmation email will be sent after you register.</p>
    </div>

    <div class="reg-back">
        <a href="{{ route('events.show', $event->id) }}">&larr; Back to event details</a>
    </div>

</div>
@endsection