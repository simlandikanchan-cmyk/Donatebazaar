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
                <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name ?? 'User' }}">
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
        $sidebarLevel = auth()->user()->fundraiserLevel;
        $sidebarLevelName = auth()->user()->fundraiserLevelName();
    @endphp

    @if(!$sidebarKyc)
        <div class="kyc-banner kyc-warn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <div>
                <div class="kyc-banner-title">KYC Required</div>
                Submit documents.
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
        <a href="{{ route('user.level') }}" class="s-link {{ request()->is('user/dashboard/level') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Fundraiser Level
            @php
                $sidebarLevel = auth()->user()->fundraiserLevel;
                $sidebarLevelName = auth()->user()->fundraiserLevelName();
            @endphp
            @if($sidebarLevel && $sidebarLevelName !== 'Starter')
                <span class="s-badge ok">{{ $sidebarLevelName }}</span>
            @else
                <span class="s-badge">Lvl 1</span>
            @endif
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
        <a href="{{ route('saved.campaigns') }}" class="s-link {{ request()->is('user/dashboard/saved-campaigns') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
            Saved Campaigns
        </a>
        @if($sidebarActive > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" data-action="set-filter" data-filter="active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Active
            <span class="s-badge ok">{{ $sidebarActive }}</span>
        </a>
        @endif
        @if($sidebarPending > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" data-action="set-filter" data-filter="pending">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Pending
            <span class="s-badge warn">{{ $sidebarPending }}</span>
        </a>
        @endif
        @if($sidebarPaused > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" data-action="set-filter" data-filter="paused">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Paused
            <span class="s-badge">{{ $sidebarPaused }}</span>
        </a>
        @endif
        @if($sidebarRejected > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" data-action="set-filter" data-filter="rejected">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Rejected
            <span class="s-badge err">{{ $sidebarRejected }}</span>
        </a>
        @endif
        @if($sidebarExpired > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" data-action="set-filter" data-filter="expired">
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

    <div class="s-label">Finance</div>
    <nav class="s-nav">
        <a href="{{ route('dashboard.wallet') }}" class="s-link {{ request()->is('user/dashboard/wallet') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Wallet
        </a>
        <a href="{{ route('recurring.index') }}" class="s-link {{ request()->is('my-recurring-donations') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Recurring Donations
            @if(isset($recurringCount) && $recurringCount > 0)<span class="s-badge ok">{{ $recurringCount }}</span>@endif
        </a>
        <a href="{{ route('gift-cards.index') }}" class="s-link {{ request()->is('gift-cards*') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
            Gift Cards
        </a>
        <a href="{{ route('donation.history') }}" class="s-link {{ request()->is('donation-history') || request()->is('donation-history/*') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Donation History
        </a>
    </nav>

    <div class="s-label">Volunteer</div>
    <nav class="s-nav">
        <a href="{{ route('volunteer.dashboard') }}" class="s-link {{ request()->is('my-volunteer-dashboard') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            My Dashboard
        </a>
        <a href="{{ route('volunteer.apply') }}" class="s-link {{ request()->is('volunteer/apply') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Apply as Volunteer
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
        <a href="{{ route('profile.show') }}" class="s-link" style="color:#64748b;margin-bottom:2px;">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            My Profile
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('__lf').submit();" class="s-link" style="color:#ef4444;">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
        <form id="__lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>

</aside>
