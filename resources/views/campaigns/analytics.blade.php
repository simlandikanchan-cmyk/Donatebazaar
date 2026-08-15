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
            <div class="anl-chart-body" style="display:flex;justify-content:center;min-height:240px;">
                <canvas id="typeChart" style="max-width:260px;max-height:260px;"></canvas>
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
<style>
:root {
    --anl-bg:           #f4f5fb;
    --anl-surface:      #ffffff;
    --anl-surface2:     #f8f9fe;
    --anl-border:       rgba(0,0,0,0.06);
    --anl-border2:      rgba(0,0,0,0.10);
    --anl-text:         #0f1117;
    --anl-text2:        #4b5563;
    --anl-text3:        #9ca3af;
    --anl-accent:       #6366f1;
    --anl-accent2:      #8b5cf6;
    --anl-green:        #10b981;
    --anl-yellow:       #f59e0b;
    --anl-red:          #ef4444;
    --anl-pink:         #ec4899;
    --anl-blue:         #3b82f6;
    --anl-font:         'DM Sans', sans-serif;
    --anl-mono:         'DM Mono', monospace;
    --anl-r:            14px;
    --anl-rs:           9px;
    --anl-sh:           0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
}
[data-theme="dark"] {
    --anl-bg:           #0b0c14;
    --anl-surface:      #13141f;
    --anl-surface2:     #1a1b2e;
    --anl-border:       rgba(255,255,255,0.06);
    --anl-border2:      rgba(255,255,255,0.10);
    --anl-text:         #f0f1ff;
    --anl-text2:        #a5b4c8;
    --anl-text3:        #5a6579;
    --anl-sh:           0 1px 3px rgba(0,0,0,0.3), 0 4px 16px rgba(0,0,0,0.2);
}

.anl { font-family: var(--anl-font); }
.anl-header{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:20px;flex-wrap:wrap;}
.anl-header-left{display:flex;align-items:center;gap:13px;}
.anl-header-icon{width:40px;height:40px;border-radius:11px;background:rgba(99,102,241,0.12);color:var(--anl-accent);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.anl-header-icon svg{width:18px;height:18px;}
.anl-header-title{font-size:17px;font-weight:800;color:var(--anl-text);letter-spacing:-0.02em;}
.anl-header-sub{font-size:11px;color:var(--anl-text3);margin-top:1px;}
.anl-header-actions{display:flex;gap:8px;}
.anl-header-actions .btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:var(--anl-rs);font-size:12px;font-weight:600;text-decoration:none;font-family:var(--anl-font);}
.anl-header-actions .btn svg{width:13px;height:13px;}
.btn-ghost{background:var(--anl-surface2);color:var(--anl-text2);border:1px solid var(--anl-border2);}
.btn-ghost:hover{opacity:.8;}

.anl-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:12px;margin-bottom:20px;}
.anl-card{background:var(--anl-surface);border:1px solid var(--anl-border2);border-radius:var(--anl-r);box-shadow:var(--anl-sh);overflow:hidden;}
.anl-stat{display:flex;align-items:center;gap:13px;padding:15px 16px;}
.anl-stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.anl-stat-icon svg{width:17px;height:17px;}
.si-accent{background:rgba(99,102,241,0.12);color:var(--anl-accent);}
.si-green{background:rgba(16,185,129,0.12);color:var(--anl-green);}
.si-yellow{background:rgba(245,158,11,0.12);color:var(--anl-yellow);}
.si-pink{background:rgba(236,72,153,0.12);color:var(--anl-pink);}
.si-red{background:rgba(239,68,68,0.12);color:var(--anl-red);}
.si-blue{background:rgba(59,130,246,0.12);color:var(--anl-blue);}
.anl-stat-body{display:flex;flex-direction:column;}
.anl-stat-val{font-size:18px;font-weight:800;color:var(--anl-text);letter-spacing:-0.02em;font-family:var(--anl-mono);line-height:1.2;}
.anl-stat-lbl{font-size:9.5px;color:var(--anl-text3);font-family:var(--anl-mono);text-transform:uppercase;letter-spacing:0.06em;margin-top:2px;}

.anl-charts{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px;}
.anl-chart-card-full{grid-column:1/-1;}
.anl-chart-header{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid var(--anl-border);}
.anl-chart-title{display:flex;align-items:center;gap:8px;font-size:12.5px;font-weight:700;color:var(--anl-text);letter-spacing:-0.01em;}
.anl-chart-title svg{width:15px;height:15px;color:var(--anl-accent);flex-shrink:0;}
.anl-chart-body{padding:18px;position:relative;min-height:220px;}
.anl-badge{font-size:10px;font-weight:700;color:var(--anl-text3);font-family:var(--anl-mono);padding:3px 9px;border-radius:100px;background:var(--anl-surface2);border:1px solid var(--anl-border);}

.anl-bottom{display:flex;flex-direction:column;gap:14px;}
.anl-table-card{}
.anl-table-wrap{overflow-x:auto;}
.anl-table{width:100%;border-collapse:collapse;font-size:12px;}
.anl-table th{text-align:left;padding:10px 16px;font-size:10px;font-weight:700;color:var(--anl-text3);font-family:var(--anl-mono);text-transform:uppercase;letter-spacing:0.06em;border-bottom:1px solid var(--anl-border);}
.anl-table td{padding:10px 16px;border-bottom:1px solid var(--anl-border);color:var(--anl-text2);}
.anl-table tbody tr:hover{background:var(--anl-surface2);}
.anl-table .num{text-align:right;font-family:var(--anl-mono);white-space:nowrap;}
.anl-table .name{font-weight:600;color:var(--anl-text);}
.anl-table .accent{font-weight:700;color:var(--anl-accent);}
.anl-table .dim{color:var(--anl-text3);}

.chip-type{display:inline-flex;padding:2px 9px;border-radius:100px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;font-family:var(--anl-mono);}
.chip-money{background:rgba(99,102,241,0.12);color:var(--anl-accent);border:1px solid rgba(99,102,241,0.2);}
.chip-product{background:rgba(16,185,129,0.12);color:var(--anl-green);border:1px solid rgba(16,185,129,0.2);}

.topbar-back{display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid var(--anl-border2);background:var(--anl-surface2);color:var(--anl-text2);cursor:pointer;text-decoration:none;transition:background .2s,color .2s;flex-shrink:0;}
.topbar-back:hover{background:rgba(99,102,241,0.10);color:var(--anl-accent);border-color:var(--anl-accent);}
.topbar-back svg{width:14px;height:14px;}
.status-chip{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:100px;font-size:11px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;font-family:var(--anl-mono);}
.status-chip .dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;}
.chip-active{background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.25);}
.chip-paused{background:rgba(99,102,241,0.12);color:#818cf8;border:1px solid rgba(99,102,241,0.25);}
.chip-pending{background:rgba(245,158,11,0.12);color:#f59e0b;border:1px solid rgba(245,158,11,0.25);}
.chip-rejected{background:rgba(239,68,68,0.12);color:#ef4444;border:1px solid rgba(239,68,68,0.25);}
.chip-inactive{background:rgba(59,130,246,0.12);color:#3b82f6;border:1px solid rgba(59,130,246,0.25);}
.chip-expired{background:rgba(107,114,128,0.12);color:#6b7280;border:1px solid rgba(107,114,128,0.25);}
.theme-toggle{display:flex;align-items:center;}
.theme-icons{display:flex;align-items:center;gap:8px;}.theme-icons svg{width:15px;height:15px;color:var(--anl-text3);}
.t-avatar{width:30px;height:30px;border-radius:8px;background:rgba(99,102,241,0.15);color:var(--anl-accent);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;font-family:var(--anl-mono);}

@media(max-width:1060px){.anl-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:860px){.anl-charts{grid-template-columns:1fr;}.topbar-right .status-chip{display:none;}}
@media(max-width:660px){.anl-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.topbar-right .status-chip{display:none;}.t-avatar{width:28px;height:28px;font-size:10px;}}
@media(max-width:420px){.anl-grid{grid-template-columns:1fr;}}
</style>
@endpush

@push('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
var textColor = isDark ? '#a5b4c8' : '#4b5563';
var gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
var accentColor = '#6366f1';
var greenColor = '#10b981';
var yellowColor = '#f59e0b';
var pinkColor = '#ec4899';

function renderChart(canvasId, config) {
    var ctx = document.getElementById(canvasId);
    if (!ctx) return;
    return new Chart(ctx.getContext('2d'), config);
}

var trendData = @json($dailyData);
var trendLabels = Object.keys(trendData);
var trendValues = Object.values(trendData);

renderChart('trendChart', {
    type: 'line',
    data: {
        labels: trendLabels.map(function(d){ var p = d.split('-'); return p[2]+'/'+p[1]; }),
        datasets: [{
            label: 'Donations',
            data: trendValues,
            borderColor: accentColor,
            backgroundColor: 'rgba(99,102,241,0.10)',
            fill: true,
            tension: 0.35,
            pointRadius: 3,
            pointHoverRadius: 6,
            pointBackgroundColor: accentColor,
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: isDark ? '#1a1b2e' : '#fff',
                titleColor: isDark ? '#f0f1ff' : '#0f1117',
                bodyColor: isDark ? '#a5b4c8' : '#4b5563',
                borderColor: isDark ? 'rgba(255,255,255,0.10)' : 'rgba(0,0,0,0.10)',
                borderWidth: 1,
                padding: 10,
                callbacks: {
                    label: function(ctx) { return '₹' + Number(ctx.raw).toLocaleString('en-IN'); }
                }
            }
        },
        scales: {
            x: {
                ticks: { color: textColor, font: { size: 10, family: "'DM Mono',monospace" }, maxTicksLimit: 12 },
                grid: { color: gridColor }
            },
            y: {
                ticks: { color: textColor, font: { size: 10, family: "'DM Mono',monospace" }, callback: function(v) { return '₹'+v.toLocaleString('en-IN'); } },
                grid: { color: gridColor }
            }
        },
        interaction: { intersect: false, mode: 'index' }
    }
});

var typeData = @json($donationTypeBreakdown);
renderChart('typeChart', {
    type: 'doughnut',
    data: {
        labels: typeData.map(function(d){ return d.type + ' (₹' + Number(d.amount).toLocaleString('en-IN') + ')'; }),
        datasets: [{
            data: typeData.map(function(d){ return d.amount; }),
            backgroundColor: [accentColor, greenColor],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { color: textColor, font: { size: 11, family: "'DM Sans',sans-serif" }, padding: 14 }
            },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        var d = typeData[ctx.dataIndex];
                        return d.type + ': ₹' + Number(d.amount).toLocaleString('en-IN') + ' (' + d.count + ' donations)';
                    }
                }
            }
        }
    }
});

renderChart('dayChart', {
    type: 'bar',
    data: {
        labels: @json($dayOfWeekLabels),
        datasets: [{
            label: 'Amount',
            data: @json($dayOfWeekTotals),
            backgroundColor: [
                'rgba(99,102,241,0.6)', 'rgba(16,185,129,0.6)', 'rgba(245,158,11,0.6)',
                'rgba(236,72,153,0.6)', 'rgba(59,130,246,0.6)', 'rgba(239,68,68,0.6)',
                'rgba(99,102,241,0.6)'
            ],
            borderColor: [
                '#6366f1','#10b981','#f59e0b','#ec4899','#3b82f6','#ef4444','#6366f1'
            ],
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        var count = @json($dayOfWeekCounts)[ctx.dataIndex];
                        return '₹' + Number(ctx.raw).toLocaleString('en-IN') + ' (' + count + ' donations)';
                    }
                }
            }
        },
        scales: {
            x: { ticks: { color: textColor, font: { size: 10 } }, grid: { color: gridColor } },
            y: { ticks: { color: textColor, font: { size: 10, family: "'DM Mono',monospace" }, callback: function(v) { return '₹'+v.toLocaleString('en-IN'); } }, grid: { color: gridColor } }
        }
    }
});
});
</script>
@endpush
