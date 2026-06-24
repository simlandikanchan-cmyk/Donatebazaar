{{-- resources/views/events/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Register – ' . $event->title)

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">

        {{-- ── Event Banner ─────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">

            @if($event->cover_image)
            <div class="h-48 w-full overflow-hidden">
                <img src="{{ asset('storage/' . $event->cover_image) }}"
                     alt="{{ $event->title }}"
                     class="w-full h-full object-cover">
            </div>
            @endif

            <div class="px-6 py-5">
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 rounded-full px-3 py-1 mb-3">
                    ● Active
                </span>

                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $event->title }}</h1>

                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mt-3">
                    {{-- Date --}}
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($event->event_date)->format('l, F j, Y') }}
                    </span>

                    {{-- Time --}}
                    @if($event->start_time)
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                        @if($event->end_time)
                            – {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                        @endif
                    </span>
                    @endif

                    {{-- Spots --}}
                    @if($event->max_participants)
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a4 4 0 00-5.356-3.779M9 20H4v-2a4 4 0 015.356-3.779M15 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        {{ $event->remainingSpots() }} spot{{ $event->remainingSpots() !== 1 ? 's' : '' }} left
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Flash Messages ───────────────────────────── --}}
        @if(session('error'))
        <div class="mb-6 flex gap-3 items-start bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-4.75a.75.75 0 001.5 0V8.75a.75.75 0 00-1.5 0v4.5zm.75-6.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- ── Registration Form ────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-8">

            <h2 class="text-lg font-semibold text-gray-800 mb-1">Register for this Event</h2>
            <p class="text-sm text-gray-500 mb-6">Fill in your details below — no account required.</p>

            <form action="{{ route('events.register.store', $event->id) }}" method="POST" novalidate>
                @csrf

                <div class="space-y-5">

                    {{-- Full Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Jane Doe"
                            autocomplete="name"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                   @error('name') border-red-400 bg-red-50 @enderror"
                        >
                        @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="jane@example.com"
                            autocomplete="email"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                   @error('email') border-red-400 bg-red-50 @enderror"
                        >
                        @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="+91 98765 43210"
                            autocomplete="tel"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                   @error('phone') border-red-400 bg-red-50 @enderror"
                        >
                        @error('phone')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Message / Note --}}
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                            Note <span class="text-gray-400 font-normal">(optional)</span>
                        </label>
                        <textarea
                            id="message"
                            name="message"
                            rows="3"
                            placeholder="Any questions or notes for the organiser?"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none
                                   @error('message') border-red-400 bg-red-50 @enderror"
                        >{{ old('message') }}</textarea>
                        @error('message')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Submit --}}
                <div class="mt-7">
                    <button
                        type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-semibold
                               rounded-lg px-6 py-3 text-sm transition-colors duration-150 focus:outline-none
                               focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    >
                        Confirm Registration
                    </button>
                </div>

                <p class="mt-4 text-center text-xs text-gray-400">
                    A confirmation email will be sent to your inbox after you register.
                </p>
            </form>
        </div>

        {{-- Back link --}}
        <div class="mt-6 text-center">
            <a href="{{ route('events.show', $event->id) }}"
               class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                ← Back to event details
            </a>
        </div>

    </div>
</div>
@endsection