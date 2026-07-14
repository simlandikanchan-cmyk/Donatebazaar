<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $campaign->title }} — DonateBazaar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
@push('styles') @vite(['resources/css/campaigns-show-new.css']) @endpush
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<div class="shell">

@php
    $kyc = auth()->user()->kycVerification ?? null;

    // / Use campaign_state only — same as dashboard
    $state = $campaign->campaign_state;

    if ($state === 'active') {
        $chipClass = 'chip-active';   $chipLabel = 'Active';
    } elseif ($state === 'paused') {
        $chipClass = 'chip-paused';   $chipLabel = 'Paused';
    } elseif ($state === 'rejected') {
        $chipClass = 'chip-rejected'; $chipLabel = 'Rejected';
    } elseif ($state === 'expired') {
        $chipClass = 'chip-expired';  $chipLabel = 'Expired';
    } elseif ($state === 'inactive') {
        $chipClass = 'chip-inactive'; $chipLabel = 'Under Review';
    } elseif ($state === 'pending') {
        $chipClass = 'chip-pending';  $chipLabel = 'Pending';
    } else {
        $chipClass = 'chip-pending';  $chipLabel = ucfirst($state ?? 'Draft');
    }



    $raised      = $campaign->raised_amount ?? 0;
    $goal        = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
    $rawPercent  = round(($raised / $goal) * 100);
    $percentage  = min(100, $rawPercent);   // bar never exceeds 100% width
    $isOverfunded = $raised > $campaign->goal_amount;
    $remaining   = max(0, $campaign->goal_amount - $raised);
    $surplus     = $isOverfunded ? ($raised - $campaign->goal_amount) : 0;

    /* ── donor + timing stats ── */
    $donorsList   = collect();
    try {
        $donorsList = $campaign->donations()->where('payment_status', 'completed')->get();
    } catch (\Throwable $e) {}
    $donorCount   = $donorsList->count();
    $avgDonation  = $donorCount > 0 ? $donorsList->avg('total_amount') : 0;
    $maxDonation  = $donorCount > 0 ? $donorsList->max('total_amount') : 0;
    $lastDonation = $donorsList->sortByDesc('created_at')->first();
    $recentDonations = $donorsList->sortByDesc('created_at')->take(8);

    /*
     * ── start / end date ──
     * Campaign model casts these to 'date', so they are already Carbon
     * instances (or null) — no need to wrap in Carbon::parse().
     * $isEnded is computed independently via isPast() so it can never be
     * thrown off by sign-flip issues in diffInDays().
     */
    $startDate = $campaign->start_date; // Carbon|null
    $endDate   = $campaign->end_date;   // Carbon|null

    $isEnded  = $endDate ? $endDate->copy()->startOfDay()->isPast() : false;
    $daysLeft = $endDate ? abs(\Carbon\Carbon::today()->diffInDays($endDate->copy()->startOfDay())) : null;

    /* new multi-doc KYC fields */
    $kycAadhaarUrl = $kyc?->aadhaar_url  ? asset('storage/'.$kyc->aadhaar_url)  : null;
    $kycPanUrl     = $kyc?->pan_url      ? asset('storage/'.$kyc->pan_url)      : null;
    $kycSelfieUrl  = $kyc?->selfie_url   ? asset('storage/'.$kyc->selfie_url)   : null;

    $isImg = fn($url) => $url && preg_match('/\.(jpe?g|png|webp|gif)$/i', $url);
    $isPdf = fn($url) => $url && str_ends_with(strtolower($url), '.pdf');

    /* bank details */
    $bankName   = $kyc?->kyc_bank_name      ?? null;
    $bankAcc    = $kyc?->kyc_account_number ?? null;
    $bankIfsc   = $kyc?->kyc_ifsc           ?? null;
    $bankHolder = $kyc?->kyc_account_name   ?? null;

    /* campaign updates */
    $updates = $campaign->updates ?? collect();

    /* public page link, if category/slug are set up */
    $publicUrl = null;
    try {
        if ($campaign->category && $campaign->slug) {
            $publicUrl = route('campaign.public', ['category' => $campaign->category->slug, 'slug' => $campaign->slug]);
        }
    } catch (\Throwable $e) {}
@endphp

{{-- ══ SIDEBAR ══ --}}
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
        <div class="s-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
        <div style="overflow:hidden;">
            <div class="s-user-name">{{ auth()->user()->name ?? 'User' }}</div>
            <div class="s-user-role">Fundraiser</div>
        </div>
    </div>

    <div class="s-label">Navigation</div>
    <nav class="s-nav">
        <a href="{{ url('/user/dashboard') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="{{ route('campaign.create') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Campaign
        </a>
    </nav>

    <div class="s-divider"></div>

    <div class="s-label">This Campaign</div>
    <nav class="s-nav">
        <a href="#" class="s-link active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Overview
        </a>
        <a href="{{ route('campaign.edit', $campaign->id) }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Campaign
        </a>
        <a href="{{ route('events.create', $campaign->id) }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Add Event
        </a>
        @if($kyc)
        <a href="{{ route('kyc.view', $campaign->id) }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            KYC Documents
        </a>
        @else
        <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            KYC Documents
        </a>
        @endif
    </nav>

    <div class="s-bottom">
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('__lf').submit();" class="s-link" style="color:rgba(248,113,113,0.75);">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
        <form id="__lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>

</aside>

{{-- ══ MAIN ══ --}}
<div class="main">

    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="{{ url('/user/dashboard') }}" class="topbar-back" title="Back to Dashboard">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </a>
            <div class="topbar-title">
                <h1>{{ Str::limit($campaign->title, 40) }}</h1>
                <p>Campaign overview &amp; events</p>
            </div>
        </div>
        <div class="topbar-right">
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
        </div>
    </header>

    <div class="body">
        <div class="page-grid">

            {{-- ════ LEFT COLUMN ════ --}}
            <div>

                {{-- Cover + Title --}}
                <div class="card" style="margin-bottom:16px;">
                    <div class="cover-wrap">
                        @if($campaign->cover_image)
                            <img src="{{ asset('storage/' . $campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
                        @else
                            <div class="cover-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                <span>No cover image</span>
                            </div>
                        @endif
                        <span class="cover-badge">
                            <span style="width:6px;height:6px;border-radius:50%;background:#fff;flex-shrink:0;display:inline-block;"></span>
                            {{ $chipLabel }}
                        </span>
                    </div>
                    <div class="campaign-title-block">
                        <h2>{{ $campaign->title }}</h2>
                        <div class="campaign-meta">Created {{ $campaign->created_at->diffForHumans() }}</div>

                        {{-- ── quick meta chips ── --}}
                        <div class="title-meta-chips">
                            @if($campaign->category)
                            <span class="title-meta-chip">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                                {{ $campaign->category->name }}
                            </span>
                            @endif
                            @if($campaign->location)
                            <span class="title-meta-chip">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $campaign->location }}
                            </span>
                            @endif
                            @if($startDate)
                            <span class="title-meta-chip">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                Starts {{ $startDate->format('d M Y') }}
                            </span>
                            @endif
                            @if($endDate)
                            <span class="title-meta-chip {{ $isEnded ? 'warn' : '' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ $isEnded ? 'Ended ' . $daysLeft . ' days ago' : $daysLeft . ' days left' }}
                            </span>
                            @endif
                            @if($publicUrl)
                            <a href="{{ $publicUrl }}" target="_blank" class="title-meta-chip" style="text-decoration:none;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                View Public Page
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- About --}}
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">About This Campaign</div>
                                <div class="card-sub">Campaign description</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="desc-text">{{ $campaign->description }}</p>
                    </div>
                </div>

                {{-- Updates & Documents --}}
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-yellow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Updates &amp; Documents</div>
                                <div class="card-sub">{{ $updates->count() }} update{{ $updates->count() !== 1 ? 's' : '' }} submitted</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($updates->count() > 0)
                            <div class="updates-list">
                                @foreach($updates as $update)
                                <div class="update-item">
                                    <div class="update-item-header">
                                        <div class="update-item-title">{{ $update->title }}</div>
                                        <div class="update-item-date">{{ \Carbon\Carbon::parse($update->created_at)->format('d M Y') }}</div>
                                    </div>
                                    @if($update->body)
                                    <div class="update-item-body">{{ $update->body }}</div>
                                    @endif
                                    @if($update->document_url)
                                    <a href="{{ asset('storage/'.$update->document_url) }}" target="_blank" class="update-doc-pill">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        View attached document
                                    </a>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p>No updates or documents submitted for this campaign.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- KYC Card (existing, now enhanced with multi-doc grid + bank details) --}}
                <!-- <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-blue">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/></svg>
                            </div>
                            <div>
                                <div class="card-title">KYC Verification</div>
                                <div class="card-sub">Identity document status</div>
                            </div>
                        </div>
                        @if(! $kyc || $kyc->status === 'rejected')
                            <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="create-event-btn" style="background:rgba(59,130,246,0.10);color:#3b82f6;border-color:rgba(59,130,246,0.2);">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                {{ $kyc ? 'Re-upload' : 'Upload KYC' }}
                            </a>
                        @else
                            <a href="{{ route('kyc.view', $campaign->id) }}" class="create-event-btn" style="background:rgba(16,185,129,0.10);color:#059669;border-color:rgba(16,185,129,0.25);">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                View Documents
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if(! $kyc)
                            <div class="kyc-notice kyc-notice-red">
                                <div class="kyc-notice-title">KYC Not Submitted</div>
                                <p class="kyc-notice-body">Your campaign cannot be approved until you submit your KYC documents. Please upload a valid government-issued ID.</p>
                            </div>
                            <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="action-btn btn-accent" style="margin-top:4px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload KYC Documents
                            </a>
                        @elseif($kyc->status === 'pending')
                            <div class="kyc-notice kyc-notice-yellow">
                                <div class="kyc-notice-title">Under Review</div>
                                <p class="kyc-notice-body">Your documents were submitted on {{ $kyc->created_at->format('d M Y') }}. Our team will review them within 24 hours.</p>
                            </div>
                            <div class="kyc-doc-row">
                                <div class="kyc-doc-icon">📄</div>
                                <div>
                                    <div class="kyc-doc-type">{{ ucfirst($kyc->document_type) }}</div>
                                    <div class="kyc-doc-num">{{ $kyc->document_number }}</div>
                                </div>
                            </div>
                            <div class="kyc-view-row">
                                <div class="kyc-view-row-text">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    View your submitted document
                                </div>
                                <a href="{{ route('kyc.view', $campaign->id) }}" class="kyc-view-link">
                                    View
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        @elseif($kyc->status === 'approved')
                            <div class="kyc-notice kyc-notice-green">
                                <div class="kyc-notice-title">KYC Approved</div>
                                <p class="kyc-notice-body">
                                    Your identity has been verified
                                    @if($kyc->verified_at) on {{ \Carbon\Carbon::parse($kyc->verified_at)->format('d M Y') }} @endif.
                                    Your campaign is eligible for approval.
                                </p>
                            </div>
                            <div class="kyc-doc-row">
                                <div class="kyc-doc-icon">/</div>
                                <div>
                                    <div class="kyc-doc-type">{{ ucfirst($kyc->document_type) }}</div>
                                    <div class="kyc-doc-num">{{ $kyc->document_number }}</div>
                                </div>
                            </div>
                            <div class="kyc-view-row">
                                <div class="kyc-view-row-text">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    View your verified document
                                </div>
                                <a href="{{ route('kyc.view', $campaign->id) }}" class="kyc-view-link">
                                    View
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        @elseif($kyc->status === 'rejected')
                            <div class="kyc-notice kyc-notice-red">
                                <div class="kyc-notice-title">KYC Rejected</div>
                                <p class="kyc-notice-body">{{ $kyc->rejection_reason ?? 'Your documents were rejected. Please re-upload valid documents.' }}</p>
                            </div>
                            <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="action-btn btn-yellow" style="margin-top:4px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Re-upload Documents
                            </a>
                        @endif

                        @if($kyc && ($kycAadhaarUrl || $kycPanUrl || $kycSelfieUrl))
                            <div style="border-top:1px solid var(--border);margin-top:16px;padding-top:16px;">

                                <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--font-mono);margin-bottom:10px;">Identity Documents</div>
                                <div class="kyc-docs-grid">

                                    <div class="kyc-doc-tile">
                                        <div class="kyc-doc-tile-header">
                                            <span class="kyc-doc-tile-label">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h6"/></svg>
                                                Aadhaar Card
                                            </span>
                                            @if($kycAadhaarUrl)
                                            <div style="display:flex;gap:5px;">
                                                <a href="{{ $kycAadhaarUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    Open
                                                </a>
                                                <a href="{{ $kycAadhaarUrl }}" download class="kyc-doc-btn kyc-doc-btn-dl">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    DL
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                        @if($kycAadhaarUrl)
                                            @if($isImg($kycAadhaarUrl))
                                                <a href="{{ $kycAadhaarUrl }}" target="_blank">
                                                    <img src="{{ $kycAadhaarUrl }}" alt="Aadhaar" loading="lazy" class="kyc-doc-tile-img">
                                                </a>
                                            @elseif($isPdf($kycAadhaarUrl))
                                                <iframe src="{{ $kycAadhaarUrl }}" class="kyc-doc-tile-pdf" title="Aadhaar PDF"></iframe>
                                            @else
                                                <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg><span>Preview unavailable</span></div>
                                            @endif
                                        @else
                                            <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span>Not uploaded</span></div>
                                        @endif
                                    </div>

                                    <div class="kyc-doc-tile">
                                        <div class="kyc-doc-tile-header">
                                            <span class="kyc-doc-tile-label">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h4"/></svg>
                                                PAN Card
                                            </span>
                                            @if($kycPanUrl)
                                            <div style="display:flex;gap:5px;">
                                                <a href="{{ $kycPanUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    Open
                                                </a>
                                                <a href="{{ $kycPanUrl }}" download class="kyc-doc-btn kyc-doc-btn-dl">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    DL
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                        @if($kycPanUrl)
                                            @if($isImg($kycPanUrl))
                                                <a href="{{ $kycPanUrl }}" target="_blank">
                                                    <img src="{{ $kycPanUrl }}" alt="PAN" loading="lazy" class="kyc-doc-tile-img">
                                                </a>
                                            @elseif($isPdf($kycPanUrl))
                                                <iframe src="{{ $kycPanUrl }}" class="kyc-doc-tile-pdf" title="PAN PDF"></iframe>
                                            @else
                                                <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg><span>Preview unavailable</span></div>
                                            @endif
                                        @else
                                            <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span>Not uploaded</span></div>
                                        @endif
                                    </div>

                                </div>

                                <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--font-mono);margin-bottom:10px;margin-top:6px;">Selfie Verification</div>
                                <div class="kyc-selfie-wrap">
                                    @if($kycSelfieUrl)
                                        <a href="{{ $kycSelfieUrl }}" target="_blank">
                                            <img src="{{ $kycSelfieUrl }}" alt="Selfie with ID" loading="lazy" class="kyc-selfie-img">
                                        </a>
                                    @else
                                        <div class="kyc-selfie-missing">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"/></svg>
                                            <span>Not uploaded</span>
                                        </div>
                                    @endif
                                    <div class="kyc-selfie-info">
                                        <div class="kyc-selfie-title">Selfie with ID Document</div>
                                        <div class="kyc-selfie-sub">Photo holding your Aadhaar or PAN card next to your face, used to verify your identity against submitted documents.</div>
                                        @if($kycSelfieUrl)
                                        <div style="margin-top:10px;display:flex;gap:6px;">
                                            <a href="{{ $kycSelfieUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                View full size
                                            </a>
                                            <a href="{{ $kycSelfieUrl }}" download class="kyc-doc-btn kyc-doc-btn-dl">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                Download
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--font-mono);margin-bottom:10px;margin-top:6px;padding-top:14px;border-top:1px solid var(--border);">Bank Account Details</div>
                                <div class="bank-grid">
                                    <div class="bank-field">
                                        <div class="bank-field-lbl">Account Holder</div>
                                        @if($bankHolder)
                                            <div class="bank-field-val">{{ $bankHolder }}</div>
                                        @else
                                            <div class="bank-field-val empty">Not provided</div>
                                        @endif
                                    </div>
                                    <div class="bank-field">
                                        <div class="bank-field-lbl">Bank Name</div>
                                        @if($bankName)
                                            <div class="bank-field-val">{{ $bankName }}</div>
                                        @else
                                            <div class="bank-field-val empty">Not provided</div>
                                        @endif
                                    </div>
                                    <div class="bank-field">
                                        <div class="bank-field-lbl">Account Number</div>
                                        @if($bankAcc)
                                            <div class="bank-field-val" style="letter-spacing:.08em;">
                                                <span id="accNum" style="filter:blur(4px);cursor:pointer;transition:filter .2s;" onclick="this.style.filter='none';document.getElementById('accReveal').style.display='none';">{{ $bankAcc }}</span>
                                                <span id="accReveal" style="font-size:10px;color:var(--accent);cursor:pointer;font-family:var(--font);font-weight:500;" onclick="document.getElementById('accNum').style.filter='none';this.style.display='none';">click to reveal</span>
                                            </div>
                                        @else
                                            <div class="bank-field-val empty">Not provided</div>
                                        @endif
                                    </div>
                                    <div class="bank-field">
                                        <div class="bank-field-lbl">IFSC Code</div>
                                        @if($bankIfsc)
                                            <div class="bank-field-val" style="letter-spacing:.1em;">{{ strtoupper($bankIfsc) }}</div>
                                        @else
                                            <div class="bank-field-val empty">Not provided</div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        @endif

                    </div>
                </div> -->

                {{-- ══ Donations ══ --}}
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-green">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Donations</div>
                                <div class="card-sub">{{ $donorCount }} donation{{ $donorCount !== 1 ? 's' : '' }} received</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($donorCount > 0)
                            {{-- Summary stats --}}
                            <div class="donations-summary-bar">
                                <div class="donations-summary-stat">
                                    <div class="donations-summary-val">{{ number_format($donorCount) }}</div>
                                    <div class="donations-summary-lbl">Total Donors</div>
                                </div>
                                <div class="donations-summary-stat">
                                    <div class="donations-summary-val">₹{{ number_format($avgDonation) }}</div>
                                    <div class="donations-summary-lbl">Average</div>
                                </div>
                                <div class="donations-summary-stat">
                                    <div class="donations-summary-val">₹{{ number_format($maxDonation) }}</div>
                                    <div class="donations-summary-lbl">Highest</div>
                                </div>
                            </div>

                            {{-- Donation rows --}}
                            <div class="donations-list">
                                @foreach($recentDonations as $donation)
                                    @php
                                        $displayName = $donation->is_anonymous ? 'Anonymous Donor' : ($donation->donor_name ?? 'Anonymous');
                                        $initial     = $donation->is_anonymous ? '?' : strtoupper(substr($displayName, 0, 1));
                                        $statusKey   = $donation->payment_status ?? 'completed';
                                    @endphp
                                    <div class="donation-row">
                                        <div class="donation-avatar {{ $donation->is_anonymous ? 'anon' : '' }}">{{ $initial }}</div>
                                        <div class="donation-main">
                                            <div class="donation-name-row">
                                                <span class="donation-name">{{ $displayName }}</span>
                                                <span class="donation-amt">₹{{ number_format($donation->total_amount) }}</span>
                                            </div>
                                            <div class="donation-meta-row">
                                                <span class="donation-date">{{ \Carbon\Carbon::parse($donation->created_at)->diffForHumans() }}</span>
                                                <span class="donation-badge {{ $statusKey }}">{{ ucfirst($statusKey) }}</span>
                                            </div>
                                            {{-- If your Donation model has a message/comment column, rename the field below to match --}}
                                            @if(! empty($donation->message))
                                                <div class="donation-msg">&ldquo;{{ Str::limit($donation->message, 120) }}&rdquo;</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($donorCount > 8)
                                <a href="#" class="donations-view-all">
                                    View all {{ $donorCount }} donations →
                                </a>
                            @endif
                        @else
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p>No donations yet — share your campaign to start receiving support.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Events --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-yellow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Campaign Events</div>
                                <div class="card-sub">{{ $campaign->events->count() }} event{{ $campaign->events->count() !== 1 ? 's' : '' }}</div>
                            </div>
                        </div>
                        <a href="{{ route('events.create', $campaign->id) }}" class="create-event-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Create Event
                        </a>
                    </div>
                    <div class="card-body">
                        @if($campaign->events->count() > 0)
                        <div class="events-grid">
                            @foreach($campaign->events as $event)
                            @php
                                $evClass = match($event->status) {
                                    'approved' => 'ev-approved',
                                    'pending'  => 'ev-pending',
                                    default    => 'ev-default',
                                };
                            @endphp
                            <div class="event-card">
                                <span class="event-badge {{ $evClass }}">{{ ucfirst($event->status) }}</span>
                                <div class="event-title">{{ $event->title }}</div>
                                <div class="event-date">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</div>
                                <div class="event-desc">{{ Str::limit($event->description, 110) }}</div>
                                <a href="{{ route('events.show', $event->id) }}" class="event-link">
                                    View details
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p>No events yet — create one to get started.</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>{{-- /.left --}}

            {{-- ════ RIGHT COLUMN ════ --}}
            <div class="right-col">

                {{-- Fundraising progress --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Fundraising</div>
                                <div class="card-sub">Current progress</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="prog-numbers">
                            <div class="prog-raised">₹{{ number_format($raised) }}</div>
                            <div class="prog-goal">of ₹{{ number_format($campaign->goal_amount) }}</div>
                        </div>
                        <div class="prog-bar">
                            <div class="prog-fill" style="width:{{ $percentage }}%"></div>
                        </div>


                        <div class="prog-pct">
    @if($isOverfunded)
        <span style="color:#10b981;font-weight:700;">{{ $rawPercent }}% funded — goal exceeded!</span>
    @else
        {{ $percentage }}% funded
    @endif
</div>
<div class="mini-stats">
    <div class="mini-stat">
        <div class="mini-stat-val">{{ $rawPercent }}%</div>
        <div class="mini-stat-lbl">Completed</div>
    </div>
    @if($isOverfunded)
        <div class="mini-stat" style="background:rgba(16,185,129,0.08);border-color:rgba(16,185,129,0.25);">
            <div class="mini-stat-val" style="font-size:14px;color:#10b981;">+₹{{ number_format($surplus) }}</div>
            <div class="mini-stat-lbl" style="color:#10b981;">Overfunded</div>
        </div>
    @else
        <div class="mini-stat">
            <div class="mini-stat-val" style="font-size:14px;">₹{{ number_format($remaining) }}</div>
            <div class="mini-stat-lbl">Remaining</div>
        </div>
    @endif
</div>

{{-- ── donor count + average donation ── --}}
<div class="mini-stats-row2">
    <div class="mini-stat">
        <div class="mini-stat-val">{{ number_format($donorCount) }}</div>
        <div class="mini-stat-lbl">Donors</div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-val" style="font-size:14px;">₹{{ number_format($avgDonation) }}</div>
        <div class="mini-stat-lbl">Avg. Donation</div>
    </div>
</div>

{{-- ── time remaining, if campaign has an end date ── --}}
@if($daysLeft !== null)
<div class="mini-stats-row2">
    <div class="mini-stat" style="grid-column:1 / -1;{{ $isEnded ? 'background:rgba(239,68,68,0.06);border-color:rgba(239,68,68,0.2);' : '' }}">
        <div class="mini-stat-val" style="font-size:14px;{{ $isEnded ? 'color:#ef4444;' : '' }}">
            {{ $isEnded ? 'Campaign ended ' . $daysLeft . ' days ago' : $daysLeft . ' days left' }}
        </div>
        <div class="mini-stat-lbl" style="{{ $isEnded ? 'color:#ef4444;' : '' }}">{{ $isEnded ? 'Status' : 'Time Remaining' }}</div>
    </div>
</div>
@endif

{{-- ── lightweight pointer down to the full Donations card ── --}}
@if($donorCount > 0 && $lastDonation)
<div class="donor-mini-footer">
    Last donation {{ \Carbon\Carbon::parse($lastDonation->created_at)->diffForHumans() }} — see full list below
</div>
@endif
                    </div>
                </div>

                {{-- KYC Status sidebar card --}}
                <!-- <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-blue">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">KYC Status</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(! $kyc)
                            <div class="kyc-pill kyc-pill-none"><span>Not Submitted</span></div>
                            <p style="font-size:11.5px;color:var(--text3);margin-bottom:12px;line-height:1.6;">Upload your KYC documents to unlock campaign approval.</p>
                            <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="action-btn btn-accent">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload Now
                            </a>
                        @elseif($kyc->status === 'pending')
                            <div class="kyc-pill kyc-pill-pending"><span>Pending Review</span></div>
                            <p style="font-size:11.5px;color:var(--text3);margin-bottom:12px;line-height:1.6;">Submitted {{ $kyc->created_at->diffForHumans() }}. We'll notify you once reviewed.</p>
                            <a href="{{ route('kyc.view', $campaign->id) }}" class="action-btn btn-kyc-view">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                View My Documents
                            </a>
                        @elseif($kyc->status === 'approved')
                            <div class="kyc-pill kyc-pill-approved"><span>Verified</span></div>
                            <p style="font-size:11.5px;color:var(--text3);margin-bottom:12px;line-height:1.6;">Your identity is verified. Campaign is eligible for approval.</p>
                            <a href="{{ route('kyc.view', $campaign->id) }}" class="action-btn btn-kyc-view">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                View My Documents
                            </a>
                        @elseif($kyc->status === 'rejected')
                            <div class="kyc-pill kyc-pill-rejected"><span>Rejected</span></div>
                            <p style="font-size:11.5px;color:var(--text3);margin-bottom:12px;line-height:1.6;">{{ Str::limit($kyc->rejection_reason ?? 'Documents were rejected.', 80) }}</p>
                            <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="action-btn btn-yellow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Re-upload
                            </a>
                        @endif
                    </div>
                </div> -->

                {{-- Actions --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-green">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Actions</div>
                                <div class="card-sub">Manage this campaign</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($publicUrl)
                        <a href="{{ $publicUrl }}" target="_blank" class="action-btn btn-accent">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View Public Page
                        </a>
                        @endif
                        <a href="{{ route('campaign.edit', $campaign->id) }}" class="action-btn {{ $publicUrl ? 'btn-ghost' : 'btn-accent' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Campaign
                        </a>
                        @if($kyc && $kyc->status !== 'rejected')
                            <a href="{{ route('kyc.view', $campaign->id) }}" class="action-btn btn-kyc-view">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                KYC Documents
                            </a>
                        @else
                            <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="action-btn btn-ghost">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                                KYC Documents
                            </a>
                        @endif
                        <a href="{{ route('events.create', $campaign->id) }}" class="action-btn btn-ghost">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Create Event
                        </a>
                        <a href="{{ url('/user/dashboard') }}" class="action-btn btn-ghost">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>

                {{-- Campaign info --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-pink">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Campaign Info</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                        <div class="info-row">
                            <span class="info-row-lbl">STATUS</span>
                            <span class="status-chip {{ $chipClass }}" style="font-size:10px;padding:3px 9px;"><span class="dot"></span> {{ $chipLabel }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-lbl">GOAL</span>
                            <span style="font-weight:700;color:var(--text);font-family:var(--font-mono);">₹{{ number_format($campaign->goal_amount) }}</span>
                        </div>
                        @if($campaign->category)
                        <div class="info-row">
                            <span class="info-row-lbl">CATEGORY</span>
                            <span style="font-weight:600;color:var(--text2);font-size:11px;">{{ $campaign->category->name }}</span>
                        </div>
                        @endif
                        @if($campaign->location)
                        <div class="info-row">
                            <span class="info-row-lbl">LOCATION</span>
                            <span style="font-weight:600;color:var(--text2);font-size:11px;">{{ $campaign->location }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-row-lbl">START DATE</span>
                            <span style="font-weight:600;color:var(--text2);font-size:11px;">
                                {{ $startDate ? $startDate->format('d M Y') : 'Not set' }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-lbl">END DATE</span>
                            <span style="font-weight:600;color:var(--text2);font-size:11px;">
                                {{ $endDate ? $endDate->format('d M Y') : 'Not set' }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-lbl">KYC</span>
                            <span style="font-size:11px;font-weight:700;
                                color:{{ $kyc?->status === 'approved' ? '#10b981' : ($kyc?->status === 'pending' ? '#f59e0b' : '#ef4444') }};">
                                @if(! $kyc) Not Submitted
                                @elseif($kyc->status === 'pending') Pending
                                @elseif($kyc->status === 'approved') Verified
                                @else Rejected
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-lbl">DONORS</span>
                            <span style="font-weight:700;color:var(--text);font-family:var(--font-mono);">{{ number_format($donorCount) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-lbl">UPDATES</span>
                            <span style="font-weight:700;color:var(--text);font-family:var(--font-mono);">{{ $updates->count() }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-lbl">EVENTS</span>
                            <span style="font-weight:700;color:var(--text);font-family:var(--font-mono);">{{ $campaign->events->count() }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-lbl">CREATED</span>
                            <span style="font-weight:600;color:var(--text2);font-size:11px;">{{ $campaign->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

            </div>{{-- /.right-col --}}

        </div>{{-- /.page-grid --}}
    </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

<script>
/* ── Dark mode ── */
var html   = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved  = localStorage.getItem('theme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
    var t = this.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', t);
    localStorage.setItem('theme', t);
});

/* ── Hamburger ── */
var sidebar = document.getElementById('sidebar');
document.getElementById('hamburger').addEventListener('click', function(){
    sidebar.classList.toggle('open');
});
document.addEventListener('click', function(e){
    if (window.innerWidth <= 860 && !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target)){
        sidebar.classList.remove('open');
    }
});

/* ── Toast flash ── */
@if(session('success'))
    window.addEventListener('DOMContentLoaded', function(){
        var t = document.createElement('div');
        t.className = 'toast toast-success';
        t.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>{{ addslashes(session('success')) }}</span><button class="toast-close" onclick="this.parentElement.remove()">x</button>';
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(function(){ t.remove(); }, 4500);
    });
@endif
@if(session('error'))
    window.addEventListener('DOMContentLoaded', function(){
        var t = document.createElement('div');
        t.className = 'toast toast-error';
        t.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>{{ addslashes(session('error')) }}</span><button class="toast-close" onclick="this.parentElement.remove()">x</button>';
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(function(){ t.remove(); }, 4500);
    });
@endif
</script>

</body>
</html>