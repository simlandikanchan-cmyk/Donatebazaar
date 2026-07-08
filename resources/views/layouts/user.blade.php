<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="@yield('meta_description', 'Manage your DonateBazaar campaigns, track donations, and grow your fundraising impact.')">
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<title>@yield('page_title', 'Dashboard') — DonateBazaar</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
@vite('resources/css/user.css')
@stack('page_styles')
</head>
<body>

<div class="toast-container" id="toastContainer"
     @if(session('success')) data-success="{{ session('success') }}" @endif
     @if(session('error'))   data-error="{{ session('error') }}" @endif>
</div>

<div class="shell">

{{-- ══════════════════════════════════════════
     SIDEBAR
══════════════════════════════════════════ --}}
@auth
@include('partials.user-sidebar')
@endauth

{{-- ══════════════════════════════════════════
     MAIN
══════════════════════════════════════════ --}}
<div class="main">

    <header class="topbar">
        <div style="display:flex;align-items:center;gap:10px;">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            @yield('topbar_left_prefix')
            <div class="topbar-left">
                <h1>@yield('page_title', 'Dashboard')</h1>
                <p>@yield('page_subtitle')</p>
            </div>
        </div>
        <div class="topbar-right">
            @hasSection('topbar_right')
                @yield('topbar_right')
            @else
            <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input class="search-input" id="searchInput" type="text" placeholder="Search campaigns…" autocomplete="off">
            </div>
            <select class="sort-sel" id="sortSelect">
                <option value="">Sort by…</option>
                <option value="amount-desc">Amount ↓</option>
                <option value="amount-asc">Amount ↑</option>
                <option value="date-desc">Newest first</option>
                <option value="date-asc">Oldest first</option>
            </select>
            <button class="tb-btn" title="Notifications">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if(isset($sidebarPending) && ($sidebarPending > 0 || $sidebarRejected > 0))<span class="notif-dot"></span>@endif
            </button>
            <div class="theme-toggle">
                <input type="checkbox" id="themeToggle">
                <label for="themeToggle">
                    <div class="theme-icons">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    </div>
                </label>
            </div>
            <a href="{{ route('campaign.create') }}" class="create-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Campaign
            </a>
            @auth
            <div class="av-wrap" id="avWrap">
                <div class="t-avatar" title="Account">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name ?? 'User' }}">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    @endif
                </div>
                <div class="av-dd" id="avDD">
                    <div class="dd-hdr">
                        <div class="dd-name">{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="dd-email">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                    <a href="{{ route('profile.show') }}" class="dd-item accent">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        View Profile
                    </a>
                    <a href="{{ route('profile.edit') }}" class="dd-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Profile
                    </a>
                    <a href="{{ route('recurring.index') }}" class="dd-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Recurring Donations
                    </a>
                    <div class="dd-sep"></div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('__lf').submit();" class="dd-item danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign Out
                    </a>
                </div>
            </div>
            @endauth
            @endif
        </div>
    </header>

    <div class="body">
        @yield('content')
    </div>

</div>{{-- /.main --}}
</div>{{-- /.shell --}}

@vite('resources/js/user.js')
@stack('page_scripts')
</body>
</html>
