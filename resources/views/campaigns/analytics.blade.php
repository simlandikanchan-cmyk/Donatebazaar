@php
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
@endphp

@extends('layouts.user')

@section('page_title', 'Analytics — ' . Str::limit($campaign->title, 25))
@section('page_subtitle', 'Donation trends, donor insights & performance')

@section('topbar_left_prefix')
    <a href="{{ route('campaign.show', $campaign->id) }}" class="topbar-back" title="Back to Campaign">
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

@section('content')
<script type="application/json" id="analyticsData">
@php
    $analyticsData = [
        'dailyData' => $dailyData,
        'donationTypeBreakdown' => $donationTypeBreakdown,
        'dayOfWeekLabels' => $dayOfWeekLabels,
        'dayOfWeekTotals' => $dayOfWeekTotals,
        'dayOfWeekCounts' => $dayOfWeekCounts,
    ];
@endphp
@json($analyticsData)
</script>
<div class="anl">
    <div class="anl-header">
        <div class="anl-header-left">
            <div class="anl-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <div class="anl-header-title">{{ $campaign->title }}</div>
                <div class="anl-header-sub">Real-time analytics & donor insights</div>
            </div>
        </div>
        <div class="anl-header-actions">
            <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-ghost">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Campaign
            </a>
        </div>
    </div>

    <div class="anl-grid">
        <div class="anl-card anl-stat">
            <div class="anl-stat-icon si-accent">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="anl-stat-body">
                <span class="anl-stat-val">₹{{ number_format($totalRaised, 0) }}</span>
                <span class="anl-stat-lbl">Total Raised</span>
            </div>
        </div>
        <div class="anl-card anl-stat">
            <div class="anl-stat-icon si-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="anl-stat-body">
                <span class="anl-stat-val">{{ $donationCount }}</span>
                <span class="anl-stat-lbl">Donations</span>
            </div>
        </div>
        <div class="anl-card anl-stat">
            <div class="anl-stat-icon si-yellow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div class="anl-stat-body">
                <span class="anl-stat-val">{{ $uniqueDonors }}</span>
                <span class="anl-stat-lbl">Unique Donors</span>
            </div>
        </div>
        <div class="anl-card anl-stat">
            <div class="anl-stat-icon si-pink">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div class="anl-stat-body">
                <span class="anl-stat-val">₹{{ number_format($avgDonation, 0) }}</span>
                <span class="anl-stat-lbl">Avg Donation</span>
            </div>
        </div>
        <div class="anl-card anl-stat">
            <div class="anl-stat-icon si-red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1<a1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </div>
            <div class="anl-stat-body">
                <span class="anl-stat-val">₹{{ number_format($platformFees, 0) }}</span>
                <span class="anl-stat-lbl">Platform Fees</span>
            </div>
        </div>
        <div class="anl-card anl-stat">
            <div class="anl-stat-icon si-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div class="anl-stat-body">
                <span class="anl-stat-val">₹{{ number_format($maxDonation, 0) }}</span>
                <span class="anl-stat-lbl">Largest Donation</span>
            </div>
        </div>
    </div>

    <div class="anl-charts">
        <div class="anl-card anl-chart-card">
            <div class="anl-chart-header">
                <div class="anl-chart-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    Donation Trend (Last 60 Days)
                </div>
            </div>
            <div class="anl-chart-body">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
        <div class="anl-card anl-chart-card">
            <div class="anl-chart-header">
                <div class="anl-chart-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
                    Donation Type
                </div>
            </div>
            <div class="anl-chart-body anl-chart-body-centered">
                <canvas id="typeChart" class="anl-canvas-constrained"></canvas>
            </div>
        </div>
        <div class="anl-card anl-chart-card anl-chart-card-full">
            <div class="anl-chart-header">
                <div class="anl-chart-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Donations by Day of Week
                </div>
            </div>
            <div class="anl-chart-body">
                <canvas id="dayChart"></canvas>
            </div>
        </div>
    </div>

    <div class="anl-bottom">
        @if($topDonors->isNotEmpty())
        <div class="anl-card anl-table-card">
            <div class="anl-chart-header">
                <div class="anl-chart-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Top Donors
                </div>
                <span class="anl-badge">{{ $topDonors->count() }} of {{ $uniqueDonors }}</span>
            </div>
            <div class="anl-table-wrap">
                <table class="anl-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Donor</th>
                            <th>Email</th>
                            <th class="num">Donations</th>
                            <th class="num">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topDonors as $i => $donor)
                        <tr>
                            <td class="dim">{{ $i + 1 }}</td>
                            <td class="name">{{ $donor->donor_name }}</td>
                            <td class="dim">{{ \Illuminate\Support\Str::mask($donor->donor_email, '*', 2, strpos($donor->donor_email, '@') - 2) }}</td>
                            <td class="num">{{ $donor->donations }}</td>
                            <td class="num accent">₹{{ number_format($donor->total, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($recentDonations->isNotEmpty())
        <div class="anl-card anl-table-card">
            <div class="anl-chart-header">
                <div class="anl-chart-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Recent Donations
                </div>
            </div>
            <div class="anl-table-wrap">
                <table class="anl-table">
                    <thead>
                        <tr>
                            <th>Donor</th>
                            <th>Type</th>
                            <th class="num">Amount</th>
                            <th class="num">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDonations as $d)
                        <tr>
                            <td class="name">{{ $d->donor_name ?? 'Anonymous' }}</td>
                            <td><span class="chip-type chip-{{ $d->donation_type }}">{{ ucfirst($d->donation_type) }}</span></td>
                            <td class="num accent">₹{{ number_format($d->total_amount, 0) }}</td>
                            <td class="num dim">{{ $d->paid_at ? \Carbon\Carbon::parse($d->paid_at)->format('d M, h:i A') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('page_styles')
@vite(['resources/css/user/pages/analytics.css'])
@endpush

@push('page_scripts')
@vite(['resources/js/user/analytics.js'])
@endpush
