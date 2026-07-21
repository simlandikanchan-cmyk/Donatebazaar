@extends('layouts.user')

@section('page_title', Str::limit($campaign->title, 40))
@section('page_subtitle', 'Campaign overview & events')

@section('topbar_left_prefix')
    <a href="{{ url('/user/dashboard') }}" class="topbar-back" title="Back to Dashboard">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
@endsection

@section('topbar_right')
    <span class="status-chip {{ $chipClass }}"><span class="dot"></span> {{ $chipLabel }}</span>
    <div class="theme-toggle" title="Toggle dark mode">
        <input type="checkbox" id="themeToggle">
        <label for="themeToggle">
            <div class="theme-icons">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            </div>
        </label>
    </div>
    <div class="t-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
@endsection

@push('page_styles')
@vite('resources/css/campaigns-show.css')
@endpush

@section('content')
<div class="page-grid">

    {{-- ════ LEFT COLUMN ════ --}}
    <div>
        @include('campaigns.partials._cover_title')
        @include('campaigns.partials._about')
        @include('campaigns.partials._updates')
        @include('campaigns.partials._events')
    </div>{{-- /.left --}}

    {{-- ════ RIGHT COLUMN ════ --}}
    <div class="right-col">
        @include('campaigns.partials._fundraising_progress')
        @include('campaigns.partials._actions')
        @include('campaigns.partials._campaign_info')
    </div>{{-- /.right-col --}}

</div>{{-- /.page-grid --}}
@endsection

@push('page_scripts')
@vite('resources/js/campaigns-show.js')
@endpush
