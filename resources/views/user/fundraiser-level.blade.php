@extends('layouts.user')

@section('page_title', 'Fundraiser Level')
@section('page_subtitle', 'Your progression & benefits')

@section('content')

{{-- ══ CURRENT LEVEL CARD ══ --}}
<div class="welcome-banner">
    <div class="wb-left">
        <div class="wb-tag">
            <span class="wb-tag-dot wb-tag-dot-dynamic" style="--dot-color:{{ $currentLevel->badge_color }};--dot-color-shadow:{{ $currentLevel->badge_color }}33;"></span>
            Current Level
        </div>
        <div class="wb-name">
            {{ $currentLevel->level_name }}
            <span class="level-number-badge" style="--level-color:{{ $currentLevel->badge_color }};">
                {{ $currentLevel->level_number }}
            </span>
        </div>
        <div class="wb-sub">{{ $currentLevel->description }}</div>
        <div class="wb-badges">
            @if($userLevel)
            <span class="wb-badge wbb-green">{{ ucfirst($userLevel->status) }}</span>
            <span class="wb-badge wbb-purple">₹{{ number_format($totalRaised, 0) }} raised</span>
            <span class="wb-badge wbb-green">{{ $campaignsCompleted }} campaign{{ $campaignsCompleted !== 1 ? 's' : '' }}</span>
            @if($nextLevel && $nextLevel->max_goal_amount)
            <span class="wb-badge wbb-yellow">Max goal ₹{{ number_format($nextLevel->max_goal_amount) }}</span>
            @endif
            @endif
        </div>
    </div>
    <div class="wb-right">
        @if($nextLevel)
        <div class="level-progress-box">
            <div class="level-progress-label">Progress to {{ $nextLevel->level_name }}</div>
            <div class="level-progress-track">
                <div class="level-progress-fill level-progress-fill-dynamic" style="--level-progress-width:{{ $completionPct }}%;--level-progress-bg:linear-gradient(90deg,{{ $currentLevel->badge_color }},{{ $nextLevel->badge_color }});"></div>
            </div>
            <div class="level-progress-pct">{{ round($completionPct) }}% complete</div>
        </div>
        @else
        <div class="wb-badge wbb-purple wbb-purple-lg">🏆 Maximum level reached</div>
        @endif
    </div>
</div>
{{-- ══ LEVEL REQUIREMENTS TABLE ══ --}}
<div class="chart-card">
    <div class="chart-card-hdr">
        <div>
            <div class="chart-title">All Levels</div>
            <div class="chart-sub">Requirements and benefits for each fundraiser level</div>
        </div>
    </div>
    <div class="table-wrap">
        <table class="level-table">
            <thead>
                <tr>
                    <th>Level</th>
                    <th>Max Goal</th>
                    <th>Max Active</th>
                    <th>Min Completed</th>
                    <th>Min Raised %</th>
                    <th>KYC</th>
                    <th>Admin Approval</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allLevels as $level)
                @php $isCurrent = $level->id === $currentLevel->id; @endphp
                <tr class="{{ $isCurrent ? 'is-current' : '' }}">
                    <td>
                        <div class="level-table-name">
                            <span class="level-table-badge level-table-badge-dynamic" style="--badge-bg:{{ $level->badge_color }};">{{ $level->level_number }}</span>
                            <span>{{ $level->level_name }}</span>
                            @if($isCurrent)
                            <span class="level-table-you">YOU</span>
                            @endif
                        </div>
                    </td>
                    <td class="mono">{{ $level->max_goal_amount ? '₹'.number_format($level->max_goal_amount) : '∞' }}</td>
                    <td class="mono">{{ $level->max_active_campaigns }}</td>
                    <td class="mono">{{ $level->min_campaigns_completed }}</td>
                    <td class="mono">{{ $level->min_raised_percent }}%</td>
                    <td>
                        <span class="kyc-badge
                            @switch($level->kyc_requirement)
                                @case('none') kyc-none @break
                                @case('basic') kyc-basic @break
                                @case('full') kyc-full @break
                                @case('org') kyc-org @break
                            @endswitch
                        ">{{ ucfirst($level->kyc_requirement) }}</span>
                    </td>
                    <td>
                        @if($level->requires_admin_approval)
                        <span class="text-warning text-warning-sm">Required</span>
                        @else
                        <span class="text-muted text-muted-sm">Auto</span>
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
<div class="chart-card">
    <div class="chart-card-hdr">
        <div>
            <div class="chart-title">Next Level: {{ $nextLevel->level_name }}</div>
            <div class="chart-sub">Requirements to reach {{ $nextLevel->level_name }}</div>
        </div>
    </div>
    <div class="next-reqs-grid">
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
        <div class="next-req-card">
            <div class="next-req-label">{{ $label }}</div>
            <div class="next-req-values">
                <span class="next-req-current">{{ $current }}</span>
                @if($required)
                <span class="next-req-required">/ {{ $required }}</span>
                @endif
            </div>
            <div class="next-req-bar">
                <div class="next-req-fill next-req-fill-dynamic" style="--req-width:{{ min($pct,100) }}%;--req-bg:{{ $color }};"></div>
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
