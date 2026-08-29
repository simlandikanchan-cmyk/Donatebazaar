@extends('layouts.user')

@section('page_title', 'Volunteer Dashboard')
@section('page_subtitle', 'Manage your assignments, events & applications')

@section('content')
@php
$isVerified = $volunteer && $volunteer->is_verified;
$hasProfile = $volunteer !== null;
$pendingApps = $applications->where('status', 'pending')->count();
$approvedApps = $applications->where('status', 'approved')->count();
$rejectedApps = $applications->where('status', 'rejected')->count();
@endphp

<x-page-hero
    tag="Volunteer"
    title="Volunteer Dashboard"
    subtitle="Manage your assignments, events & applications."
>
    <x-slot:badges>
        <span class="wb-badge wbb-green">{{ $stats['total'] }} assignments</span>
        <span class="wb-badge wbb-primary">{{ $stats['active'] }} active</span>
        <span class="wb-badge wbb-yellow">{{ $stats['applications'] }} applications</span>
    </x-slot:badges>
    <x-slot:actions>
        <x-button variant="primary" href="{{ route('volunteer.apply') }}" class="wb-btn wb-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            {{ $hasProfile ? 'Apply for more' : 'Apply Now' }}
        </x-button>
    </x-slot:actions>
</x-page-hero>

@if(!$hasProfile)
<div class="vd-empty">
    <div class="vd-empty-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    </div>
    <h2>Become a Volunteer</h2>
    <p>Join our volunteer community and make a difference. Apply to support campaigns and events that need your help.</p>
    <x-button variant="primary" href="{{ route('volunteer.apply') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Apply Now
    </x-button>
</div>
@elseif(!$isVerified)
<div class="vd-banner vd-banner-warn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    <div>
        <strong>Application Under Review</strong>
        <p>Your volunteer application is being reviewed by our team. You'll be notified once it's approved.</p>
    </div>
</div>
@endif

@if($hasProfile)
<div class="vd-header">
    <div class="vd-header-left">
        <div class="vd-avatar">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
            @else
                {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 1)) }}
            @endif
        </div>
        <div>
            <div class="vd-name">{{ auth()->user()->name }}</div>
            <div class="vd-badge-row">
                @if($isVerified)
                <span class="vd-badge vd-badge-ok">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Verified Volunteer
                </span>
                @else
                <span class="vd-badge vd-badge-pending">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Pending Verification
                </span>
                @endif
                @if($volunteer->skills && count($volunteer->skills) > 0)
                <span class="vd-badge vd-badge-skill">{{ implode(', ', array_slice($volunteer->skills, 0, 3)) }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="vd-stats">
    <div class="vd-stat">
        <div class="vd-stat-icon si-accent">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <div class="vd-stat-num">{{ $stats['total'] }}</div>
            <div class="vd-stat-lbl">Total Assignments</div>
        </div>
    </div>
    <div class="vd-stat">
        <div class="vd-stat-icon si-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="vd-stat-num">{{ $stats['active'] }}</div>
            <div class="vd-stat-lbl">Active</div>
        </div>
    </div>
    <div class="vd-stat">
        <div class="vd-stat-icon si-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="vd-stat-num">{{ $stats['completed'] }}</div>
            <div class="vd-stat-lbl">Completed</div>
        </div>
    </div>
    <div class="vd-stat">
        <div class="vd-stat-icon si-yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
            <div class="vd-stat-num">{{ $stats['applications'] }}</div>
            <div class="vd-stat-lbl">Applications</div>
        </div>
    </div>
</div>

@if($activeAssignments->isNotEmpty())
<div class="vd-section">
    <div class="vd-section-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        Active Assignments
    </div>
    <div class="vd-list">
        @foreach($activeAssignments as $assignment)
        <div class="vd-row">
            <div class="vd-row-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="vd-row-body">
                <div class="vd-row-title">
                    {{ $assignment->event?->title ?? $assignment->campaign?->title ?? 'Assignment' }}
                </div>
                <div class="vd-row-meta">
                    @if($assignment->event)
                    <span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ \Carbon\Carbon::parse($assignment->event->event_date)->format('d M Y') }}
                    </span>
                    @endif
                    @if($assignment->campaign)
                    <span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        {{ $assignment->campaign->title }}
                    </span>
                    @endif
                    @if($assignment->role)
                    <span class="vd-chip">{{ $assignment->role }}</span>
                    @endif
                </div>
            </div>
            <div class="vd-row-status">
                <span class="vd-chip vd-chip-active">
                    <span class="dot"></span> Active
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($applications->isNotEmpty())
<div class="vd-section">
    <div class="vd-section-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Applications
    </div>
    <div class="vd-list">
        @foreach($applications as $app)
        <div class="vd-row">
            <div class="vd-row-icon">
                @if($app->campaign && $app->campaign->cover_image)
                <img src="{{ asset('storage/'.$app->campaign->cover_image) }}" alt="">
                @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                @endif
            </div>
            <div class="vd-row-body">
                <div class="vd-row-title">{{ $app->campaign?->title ?? 'General Application' }}</div>
                <div class="vd-row-meta">
                    <span>{{ $app->applied_at ? \Carbon\Carbon::parse($app->applied_at)->format('d M Y') : '-' }}</span>
                    @if($app->message)
                    <span class="vd-msg-preview">{{ Str::limit($app->message, 50) }}</span>
                    @endif
                </div>
            </div>
            <div class="vd-row-status">
                @if($app->status === 'approved')
                <span class="vd-chip vd-chip-approved"><span class="dot"></span> Approved</span>
                @elseif($app->status === 'rejected')
                <span class="vd-chip vd-chip-rejected"><span class="dot"></span> Rejected</span>
                @else
                <span class="vd-chip vd-chip-pending"><span class="dot"></span> Pending</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($completedAssignments->isNotEmpty())
<div class="vd-section">
    <div class="vd-section-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        History
    </div>
    <div class="vd-list">
        @foreach($completedAssignments as $assignment)
        <div class="vd-row vd-row-dim">
            <div class="vd-row-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="vd-row-body">
                <div class="vd-row-title">
                    {{ $assignment->event?->title ?? $assignment->campaign?->title ?? 'Assignment' }}
                </div>
                <div class="vd-row-meta">
                    @if($assignment->event)
                    <span>{{ \Carbon\Carbon::parse($assignment->event->event_date)->format('d M Y') }}</span>
                    @endif
                    @if($assignment->campaign)
                    <span>{{ $assignment->campaign->title }}</span>
                    @endif
                    @if($assignment->role)
                    <span class="vd-chip">{{ $assignment->role }}</span>
                    @endif
                </div>
            </div>
            <div class="vd-row-status">
                <span class="vd-chip vd-chip-completed">
                    <span class="dot"></span> Completed
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($activeAssignments->isEmpty() && $completedAssignments->isEmpty() && $applications->isEmpty() && $isVerified)
<div class="vd-empty vd-empty-sm">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    <h3>No Assignments Yet</h3>
    <p>You're verified! Once an admin assigns you to an event or campaign, it will appear here.</p>
</div>
@endif
@endif
@endsection

@push('page_styles')
<style>
.vd-header{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:20px;flex-wrap:wrap;}
.vd-header-left{display:flex;align-items:center;gap:14px;}
.vd-avatar{width:44px;height:44px;border-radius:12px;background:rgba(99,102,241,0.15);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;font-family:var(--mono);overflow:hidden;flex-shrink:0;}
.vd-avatar img{width:100%;height:100%;object-fit:cover;}
.vd-name{font-size:17px;font-weight:800;color:var(--text);letter-spacing:-0.02em;}
.vd-badge-row{display:flex;align-items:center;gap:7px;margin-top:4px;flex-wrap:wrap;}
.vd-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:100px;font-size:10px;font-weight:700;font-family:var(--mono);letter-spacing:0.03em;}
.vd-badge svg{width:11px;height:11px;}
.vd-badge-ok{background:rgba(16,185,129,0.12);color:var(--green);border:1px solid rgba(16,185,129,0.25);}
.vd-badge-pending{background:rgba(245,158,11,0.12);color:var(--yellow);border:1px solid rgba(245,158,11,0.25);}
.vd-badge-skill{background:rgba(99,102,241,0.10);color:var(--text2);border:1px solid var(--border2);font-size:9.5px;}

.vd-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;}
.vd-stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);padding:15px 16px;display:flex;align-items:center;gap:12px;}
.vd-stat-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.vd-stat-icon svg{width:16px;height:16px;}
.si-accent{background:rgba(99,102,241,0.12);color:var(--accent);}
.si-green{background:rgba(16,185,129,0.12);color:var(--green);}
.si-blue{background:rgba(59,130,246,0.12);color:#3b82f6;}
.si-yellow{background:rgba(245,158,11,0.12);color:var(--yellow);}
.vd-stat-num{font-size:20px;font-weight:800;color:var(--text);font-family:var(--mono);letter-spacing:-0.02em;line-height:1.1;}
.vd-stat-lbl{font-size:10px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:0.06em;margin-top:2px;}

.vd-section{margin-bottom:18px;}
.vd-section-title{display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700;color:var(--text);margin-bottom:10px;letter-spacing:-0.01em;}
.vd-section-title svg{width:15px;height:15px;color:var(--accent);}

.vd-list{display:flex;flex-direction:column;gap:8px;}
.vd-row{display:flex;align-items:center;gap:12px;padding:12px 14px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r-sm);box-shadow:var(--sh);transition:border-color .2s;}
.vd-row:hover{border-color:var(--border2);}
.vd-row-dim{opacity:.75;}
.vd-row-icon{width:36px;height:36px;border-radius:9px;background:var(--surface2);color:var(--text3);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
.vd-row-icon img{width:100%;height:100%;object-fit:cover;}
.vd-row-icon svg{width:15px;height:15px;}
.vd-row-body{flex:1;min-width:0;}
.vd-row-title{font-size:12.5px;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.vd-row-meta{display:flex;align-items:center;gap:10px;margin-top:3px;flex-wrap:wrap;}
.vd-row-meta span{font-size:10.5px;color:var(--text3);font-family:var(--mono);display:flex;align-items:center;gap:4px;}
.vd-row-meta span svg{width:10px;height:10px;}
.vd-row-status{flex-shrink:0;}
.vd-msg-preview{font-family:var(--font)!important;color:var(--text2)!important;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}

.vd-chip{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:100px;font-size:9.5px;font-weight:700;font-family:var(--mono);text-transform:uppercase;letter-spacing:0.04em;white-space:nowrap;}
.vd-chip .dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
.vd-chip-active{background:rgba(16,185,129,0.12);color:var(--green);border:1px solid rgba(16,185,129,0.25);}
.vd-chip-completed{background:rgba(107,114,128,0.12);color:#6b7280;border:1px solid rgba(107,114,128,0.25);}
.vd-chip-approved{background:rgba(16,185,129,0.12);color:var(--green);border:1px solid rgba(16,185,129,0.25);}
.vd-chip-rejected{background:rgba(239,68,68,0.12);color:var(--red);border:1px solid rgba(239,68,68,0.25);}
.vd-chip-pending{background:rgba(245,158,11,0.12);color:var(--yellow);border:1px solid rgba(245,158,11,0.25);}

.vd-empty{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;padding:70px 20px;text-align:center;}
.vd-empty svg{width:50px;height:50px;color:var(--text3);opacity:.25;}
.vd-empty h2{font-size:17px;font-weight:800;color:var(--text);}
.vd-empty p{font-size:12px;color:var(--text3);max-width:340px;line-height:1.6;}
.vd-empty .btn{padding:10px 22px;font-size:12.5px;}
.vd-empty .btn svg{width:13px;height:13px;opacity:1;}
.vd-empty-sm{padding:50px 20px;}
.vd-empty-sm h3{font-size:14px;font-weight:700;color:var(--text2);}
.vd-empty-icon{width:56px;height:56px;border-radius:16px;background:rgba(99,102,241,0.10);color:var(--accent);display:flex;align-items:center;justify-content:center;}
.vd-empty-icon svg{width:26px;height:26px;opacity:1!important;}

.vd-banner{display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-radius:var(--r-sm);margin-bottom:20px;}
.vd-banner svg{width:20px;height:20px;flex-shrink:0;margin-top:1px;}
.vd-banner strong{font-size:13px;display:block;margin-bottom:2px;}
.vd-banner p{font-size:11.5px;margin:0;line-height:1.5;opacity:.85;}
.vd-banner-warn{background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);color:var(--yellow);}
.vd-banner-warn strong{color:var(--yellow);}
.vd-banner-warn p{color:var(--text2);}

[data-theme="dark"] .vd-chip-completed{color:#9ca3af;}
[data-theme="dark"] .vd-chip-rejected{color:#f87171;}
[data-theme="dark"] .vd-badge-skill{color:var(--text3);}

@media(max-width:860px){.vd-stats{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.vd-stats{grid-template-columns:1fr;}}
</style>
@endpush
