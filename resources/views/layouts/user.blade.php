<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
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
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<aside class="sidebar" id="sidebar">

    <div class="s-logo">
        <div class="s-logo-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div>
            <div class="s-logo-name">DonateBazaar</div>
            <div class="s-logo-tag">My Portal</div>
        </div>
    </div>

    <div class="s-user">
        <div class="s-avatar">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
            @else
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            @endif
        </div>
        <div style="flex:1;overflow:hidden;">
            <div class="s-user-name">{{ auth()->user()->name ?? 'User' }}</div>
            <div class="s-user-role">Fundraiser</div>
        </div>
        <div class="s-user-dot"></div>
    </div>

    @php
        $sidebarKyc = auth()->user()->kycVerification;
        $sidebarCampaigns = \App\Models\Campaign::where('user_id', auth()->id())->get();
        $sidebarAll      = $sidebarCampaigns->count();
        $sidebarActive   = $sidebarCampaigns->where('campaign_state','active')->count();
        $sidebarInactive = $sidebarCampaigns->where('campaign_state','inactive')->count();
        $sidebarPending  = $sidebarCampaigns->where('campaign_state','pending')->count();
        $sidebarPaused   = $sidebarCampaigns->where('campaign_state','paused')->count();
        $sidebarRejected = $sidebarCampaigns->where('campaign_state','rejected')->count();
        $sidebarExpired  = $sidebarCampaigns->where('campaign_state','expired')->count();
        $sidebarBlogs    = \App\Models\Blog::where('author_id', auth()->id())->get();
        $sidebarBlogTotal     = $sidebarBlogs->count();
        $sidebarBlogPublished = $sidebarBlogs->where('status','approved')->count();
        $sidebarBlogDraft     = $sidebarBlogs->where('status','draft')->count();
        $sidebarBlogPending   = $sidebarBlogs->where('status','pending')->count();
    @endphp

    @if(!$sidebarKyc)
        <div class="kyc-banner kyc-warn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <div>
                <div class="kyc-banner-title">KYC Required</div>
                Submit documents so campaigns go live.
                <br><a href="{{ url('/user/kyc') }}">Submit KYC →</a>
            </div>
        </div>
    @elseif($sidebarKyc->status === 'pending')
        <div class="kyc-banner kyc-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <div>
                <div class="kyc-banner-title">KYC Under Review</div>
                Campaigns live once KYC is approved.
            </div>
        </div>
    @elseif($sidebarKyc->status === 'approved')
        <div class="kyc-banner kyc-ok">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><div class="kyc-banner-title">KYC Verified ✓</div>Your account is fully verified.</div>
        </div>
    @elseif($sidebarKyc->status === 'rejected')
        <div class="kyc-banner kyc-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <div>
                <div class="kyc-banner-title">KYC Rejected</div>
                Re-submit correct documents.
                <br><a href="{{ url('/user/kyc') }}">Re-submit →</a>
            </div>
        </div>
    @endif

    <div class="sidebar-mobile-search">
        <div class="sms-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" class="sms-input" id="mobileSearchInput" placeholder="Search campaigns…" autocomplete="off">
        </div>
        <select class="sms-sort" id="mobileSortSelect">
            <option value="">Sort by…</option>
            <option value="amount-desc">Amount ↓</option>
            <option value="amount-asc">Amount ↑</option>
            <option value="date-desc">Newest first</option>
            <option value="date-asc">Oldest first</option>
        </select>
    </div>

    <div class="s-label">Overview</div>
    <nav class="s-nav">
        <a href="{{ url('/user/dashboard') }}" class="s-link {{ request()->is('user/dashboard') && !request()->is('user/dashboard/*') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="{{ route('profile.show') }}" class="s-link {{ request()->is('profile') || request()->is('profile/*') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            My Profile
        </a>
    </nav>

    <div class="s-label">Campaigns</div>
    <nav class="s-nav">
        <a href="{{ route('campaign.create') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Campaign
        </a>
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            All Campaigns
            @if($sidebarAll > 0)<span class="s-badge">{{ $sidebarAll }}</span>@endif
        </a>
        @if($sidebarActive > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" onclick="setFilter('active')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Active
            <span class="s-badge ok">{{ $sidebarActive }}</span>
        </a>
        @endif
        @if($sidebarPending > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" onclick="setFilter('pending')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Pending
            <span class="s-badge warn">{{ $sidebarPending }}</span>
        </a>
        @endif
        @if($sidebarPaused > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" onclick="setFilter('paused')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Paused
            <span class="s-badge">{{ $sidebarPaused }}</span>
        </a>
        @endif
        @if($sidebarRejected > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" onclick="setFilter('rejected')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Rejected
            <span class="s-badge err">{{ $sidebarRejected }}</span>
        </a>
        @endif
        @if($sidebarExpired > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" onclick="setFilter('expired')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Expired
            <span class="s-badge">{{ $sidebarExpired }}</span>
        </a>
        @endif
    </nav>

    <div class="s-label">Identity & KYC</div>
    <nav class="s-nav">
        @php $kycActive = request()->is('user/kyc') || request()->is('kyc/*') ? 'active' : ''; @endphp
        @if(!$sidebarKyc || $sidebarKyc->status === 'rejected')
        <a href="{{ url('/user/kyc') }}" class="s-link {{ $kycActive }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Submit KYC
            <span class="s-badge warn">Action needed</span>
        </a>
        @else
        <a href="{{ url('/user/kyc') }}" class="s-link {{ $kycActive }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            KYC Status
            <span class="s-badge {{ $sidebarKyc->status === 'approved' ? 'ok' : 'warn' }}">{{ ucfirst($sidebarKyc->status) }}</span>
        </a>
        @endif
    </nav>

    <div class="s-label">Donations</div>
    <nav class="s-nav">
        <a href="{{ route('recurring.index') }}" class="s-link {{ request()->is('my-recurring-donations') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Recurring Donations
            @if(isset($recurringCount) && $recurringCount > 0)<span class="s-badge ok">{{ $recurringCount }}</span>@endif
        </a>
    </nav>

    <div class="s-divider"></div>

    <div class="s-label">Blogs</div>
    <nav class="s-nav">
        <a href="{{ url('/user/dashboard/blogs') }}" class="s-link {{ request()->is('user/dashboard/blogs') || request()->is('user/dashboard/blogs/*') && !request()->is('user/dashboard/blogs/create') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            My Blogs
            @if($sidebarBlogTotal > 0)<span class="s-badge">{{ $sidebarBlogTotal }}</span>@endif
        </a>
        <a href="{{ url('/user/dashboard/blogs/create') }}" class="s-link {{ request()->is('user/dashboard/blogs/create') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Write a Blog
        </a>
    </nav>
    @if($sidebarBlogTotal > 0)
    <div class="s-sub">
        @if($sidebarBlogPublished > 0)
        <a href="{{ url('/user/dashboard/blogs?status=approved') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>Published
            <span style="margin-left:auto;font-size:10px;color:var(--green);font-family:var(--mono);">{{ $sidebarBlogPublished }}</span>
        </a>
        @endif
        @if($sidebarBlogDraft > 0)
        <a href="{{ url('/user/dashboard/blogs?status=draft') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>Drafts
            <span style="margin-left:auto;font-size:10px;color:var(--yellow);font-family:var(--mono);">{{ $sidebarBlogDraft }}</span>
        </a>
        @endif
        @if($sidebarBlogPending > 0)
        <a href="{{ url('/user/dashboard/blogs?status=pending') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>In Review
            <span style="margin-left:auto;font-size:10px;color:var(--text3);font-family:var(--mono);">{{ $sidebarBlogPending }}</span>
        </a>
        @endif
    </div>
    @endif

    <div class="s-bottom">
        <a href="{{ route('profile.show') }}" class="s-link" style="color:rgba(165,180,252,.75);margin-bottom:2px;">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            My Profile
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('__lf').submit();" class="s-link" style="color:rgba(248,113,113,.75);">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
        <form id="__lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>

</aside>

{{-- ══════════════════════════════════════════
     MAIN
══════════════════════════════════════════ --}}
<div class="main">

    <header class="topbar">
        <div style="display:flex;align-items:center;gap:10px;">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="topbar-left">
                <h1>@yield('page_title', 'Dashboard')</h1>
                <p>@yield('page_subtitle')</p>
            </div>
        </div>
        <div class="topbar-right">
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
            <div class="av-wrap" id="avWrap">
                <div class="t-avatar" title="Account">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
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
