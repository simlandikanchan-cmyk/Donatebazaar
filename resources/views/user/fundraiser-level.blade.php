@extends('layouts.user')

@section('page_title', 'Fundraiser Level')
@section('page_subtitle', 'Your progression & benefits')

@section('content')

{{-- ══ CURRENT LEVEL CARD ══ --}}
<div class="welcome-banner" style="background:var(--surface);margin-bottom:22px;">
    <div class="wb-left">
        <div class="wb-tag">
            <span class="wb-tag-dot" style="background:{{ $currentLevel->badge_color }};box-shadow:0 0 0 3px {{ $currentLevel->badge_color }}33;"></span>
            Current Level
        </div>
        <div class="wb-name" style="display:flex;align-items:center;gap:12px;">
            {{ $currentLevel->level_name }}
            <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;font-size:16px;font-weight:800;color:#fff;background:{{ $currentLevel->badge_color }};box-shadow:0 4px 14px {{ $currentLevel->badge_color }}44;">
                {{ $currentLevel->level_number }}
            </span>
        </div>
        <div class="wb-sub">{{ $currentLevel->description }}</div>
        <div class="wb-badges">
            @if($userLevel)
            <span class="wb-badge" style="background:{{ $currentLevel->badge_color }}22;color:{{ $currentLevel->badge_color }};border-color:{{ $currentLevel->badge_color }}44;">
                {{ ucfirst($userLevel->status) }}
            </span>
            <span class="wb-badge wbb-purple">
                ₹{{ number_format($totalRaised, 0) }} raised
            </span>
            <span class="wb-badge wbb-green">
                {{ $campaignsCompleted }} campaign{{ $campaignsCompleted !== 1 ? 's' : '' }}
            </span>
            @if($nextLevel && $nextLevel->max_goal_amount)
            <span class="wb-badge wbb-yellow">
                Max goal ₹{{ number_format($nextLevel->max_goal_amount) }}
            </span>
            @endif
            @endif
        </div>
    </div>
    <div class="wb-right">
        @if($nextLevel)
        <div style="text-align:right;">
            <div style="font-size:11px;color:var(--text3);font-family:var(--mono);margin-bottom:6px;">Progress to {{ $nextLevel->level_name }}</div>
            <div style="width:200px;height:7px;background:var(--surface3);border-radius:100px;overflow:hidden;">
                <div style="height:100%;border-radius:100px;background:linear-gradient(90deg,{{ $currentLevel->badge_color }},{{ $nextLevel->badge_color }});width:{{ $completionPct }}%;transition:width 1.2s cubic-bezier(.4,0,.2,1);"></div>
            </div>
            <div style="font-size:11px;color:var(--text3);margin-top:4px;font-family:var(--mono);">{{ round($completionPct) }}% complete</div>
        </div>
        @else
        <div class="wb-badge wbb-purple" style="font-size:13px;padding:8px 18px;">🏆 Maximum level reached</div>
        @endif
    </div>
</div>

{{-- ══ LEVEL REQUIREMENTS TABLE ══ --}}
<div class="chart-card" style="margin-bottom:22px;">
    <div class="chart-card-hdr">
        <div>
            <div class="chart-title">All Levels</div>
            <div class="chart-sub">Requirements and benefits for each fundraiser level</div>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="border-bottom:2px solid var(--border);">
                    <th style="text-align:left;padding:10px 12px;font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);">Level</th>
                    <th style="text-align:left;padding:10px 12px;font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);">Max Goal</th>
                    <th style="text-align:left;padding:10px 12px;font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);">Max Active</th>
                    <th style="text-align:left;padding:10px 12px;font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);">Min Completed</th>
                    <th style="text-align:left;padding:10px 12px;font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);">Min Raised %</th>
                    <th style="text-align:left;padding:10px 12px;font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);">KYC</th>
                    <th style="text-align:left;padding:10px 12px;font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);">Admin Approval</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allLevels as $level)
                @php $isCurrent = $level->id === $currentLevel->id; @endphp
                <tr style="border-bottom:1px solid var(--border);{{ $isCurrent ? 'background:var(--accent-lt);' : '' }} transition:background var(--ease);">
                    <td style="padding:12px;font-weight:600;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:7px;font-size:12px;font-weight:700;color:#fff;background:{{ $level->badge_color }};">{{ $level->level_number }}</span>
                            <span>{{ $level->level_name }}</span>
                            @if($isCurrent)
                            <span style="font-size:9px;background:var(--accent);color:#fff;padding:2px 7px;border-radius:100px;font-family:var(--mono);font-weight:700;">YOU</span>
                            @endif
                        </div>
                    </td>
                    <td style="padding:12px;color:var(--text2);font-family:var(--mono);font-size:12px;">
                        {{ $level->max_goal_amount ? '₹'.number_format($level->max_goal_amount) : '∞' }}
                    </td>
                    <td style="padding:12px;color:var(--text2);font-family:var(--mono);font-size:12px;">
                        {{ $level->max_active_campaigns }}
                    </td>
                    <td style="padding:12px;color:var(--text2);font-family:var(--mono);font-size:12px;">
                        {{ $level->min_campaigns_completed }}
                    </td>
                    <td style="padding:12px;color:var(--text2);font-family:var(--mono);font-size:12px;">
                        {{ $level->min_raised_percent }}%
                    </td>
                    <td style="padding:12px;">
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:6px;font-size:10px;font-weight:600;font-family:var(--mono);
                            @switch($level->kyc_requirement)
                                @case('none') background:var(--gray-lt);color:var(--text3); @break
                                @case('basic') background:var(--yellow-lt);color:var(--yellow); @break
                                @case('full') background:var(--accent-lt);color:var(--accent); @break
                                @case('org') background:var(--pink-lt);color:var(--pink); @break
                            @endswitch
                        ">{{ ucfirst($level->kyc_requirement) }}</span>
                    </td>
                    <td style="padding:12px;">
                        @if($level->requires_admin_approval)
                        <span style="color:var(--yellow);font-weight:600;font-size:11px;">Required</span>
                        @else
                        <span style="color:var(--text3);font-size:11px;">Auto</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ══ PROGRESS TO NEXT LEVEL ══ --}}
@if($nextLevel)
<div class="chart-card" style="margin-bottom:22px;">
    <div class="chart-card-hdr">
        <div>
            <div class="chart-title">Next Level: {{ $nextLevel->level_name }}</div>
            <div class="chart-sub">Requirements to reach {{ $nextLevel->level_name }}</div>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;">
        @php $nextReqs = []; @endphp
        @if($nextLevel->min_campaigns_completed > 0)
            @php
                $reqCamp = $nextLevel->min_campaigns_completed;
                $curCamp = min($campaignsCompleted, $reqCamp);
                $campPct = $reqCamp > 0 ? round(($curCamp / $reqCamp) * 100) : 0;
                $nextReqs[] = ['Completed Campaigns', $curCamp, $reqCamp, $campPct, 'var(--green)'];
            @endphp
        @endif
        @if($nextLevel->min_raised_percent > 0)
            @php $nextReqs[] = ['Avg Raised %', min(100,$campaignsCompleted>0?100:0), $nextLevel->min_raised_percent.'%', $campaignsCompleted>0?100:0, 'var(--accent)']; @endphp
        @endif
        @if($nextLevel->max_goal_amount)
            @php $nextReqs[] = ['Max Goal Cap', '₹'.number_format($nextLevel->max_goal_amount), '', 100, 'var(--yellow)']; @endphp
        @endif
        @if($nextLevel->kyc_requirement !== 'none')
            @php
                $kycStatus = optional(auth()->user()->kycVerification)->status ?? 'none';
                $kycMet = $kycStatus === 'approved' ? 100 : ($kycStatus === 'pending' ? 50 : 0);
                $nextReqs[] = ['KYC: '.ucfirst($nextLevel->kyc_requirement), $kycStatus === 'approved' ? 'Approved' : ucfirst($kycStatus), ucfirst($nextLevel->kyc_requirement), $kycMet, 'var(--pink)'];
            @endphp
        @endif
        @foreach($nextReqs as [$label, $current, $required, $pct, $color])
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:16px;">
            <div style="font-size:11px;color:var(--text3);font-family:var(--mono);margin-bottom:8px;">{{ $label }}</div>
            <div style="display:flex;align-items:baseline;gap:6px;margin-bottom:8px;">
                <span style="font-size:18px;font-weight:700;color:var(--text);">{{ $current }}</span>
                @if($required)
                <span style="font-size:12px;color:var(--text3);font-family:var(--mono);">/ {{ $required }}</span>
                @endif
            </div>
            <div style="height:5px;background:var(--surface3);border-radius:100px;overflow:hidden;">
                <div style="height:100%;border-radius:100px;background:{{ $color }};width:{{ min($pct,100) }}%;transition:width .8s ease;"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ══ LEVEL HISTORY ══ --}}
@if($userLevel && $userLevel->history->count() > 0)
<div class="activity-card">
    <div class="activity-hdr">
        <div class="activity-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Level History
        </div>
    </div>
    <div class="activity-list">
        @foreach($userLevel->history->sortByDesc('created_at') as $history)
        <div class="activity-item">
            <div class="activity-dot-col">
                <div class="activity-dot d-accent">
                    <span class="ad-letter">{{ $history->toLevel?->level_number ?? '?' }}</span>
                </div>
                <div class="activity-line"></div>
            </div>
            <div class="activity-body">
                <div class="activity-body-top">
                    <div class="activity-lbl">
                        {{ $history->reason ?? 'Level change' }}
                        <span>
                            @if($history->fromLevel)
                                {{ $history->fromLevel->level_name }} → 
                            @endif
                            {{ $history->toLevel?->level_name ?? 'N/A' }}
                        </span>
                    </div>
                </div>
                <div class="activity-sub">
                    {{ $history->created_at ? $history->created_at->format('d M Y, h:i A') : '' }}
                    @if($history->triggered_by === 'admin' && $history->admin)
                        · by {{ $history->admin->name }}
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
