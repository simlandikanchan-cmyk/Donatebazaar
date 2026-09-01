@props([
    'campaign',
    'variant' => 'grid',
    'index' => 0,
])

@php
    $state = $campaign->campaign_state;
    if      ($state === 'active')   { $fv='active';   $bc='b-active';   $bl='Active'; }
    elseif  ($state === 'paused')   { $fv='paused';   $bc='b-paused';   $bl='Paused'; }
    elseif  ($state === 'rejected') { $fv='rejected'; $bc='b-rejected'; $bl='Rejected'; }
    elseif  ($state === 'expired')  { $fv='expired';  $bc='b-expired';  $bl='Expired'; }
    elseif  ($state === 'inactive') { $fv='inactive'; $bc='b-inactive'; $bl='Under Review'; }
    elseif  ($state === 'pending')  { $fv='pending';  $bc='b-pending';  $bl='Pending'; }
    else                            { $fv='other';    $bc='b-default';  $bl=ucfirst($state ?? 'Draft'); }

    $raised = $campaign->raised_amount ?? 0;
    $goal   = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
    $pct    = min(100, round(($raised / $goal) * 100));

    $daysLeft = $campaign->end_date ? now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($campaign->end_date)->startOfDay(), false) : null;
    $campCats = $campaign->category;

    $isGrid = $variant === 'grid';
    $delay  = $isGrid ? ($index * .04) : ($index * .03);
@endphp

@if($isGrid)
<div class="c-card"
     data-filter="{{ $fv }}"
     data-title="{{ strtolower($campaign->title) }}"
     data-amount="{{ $campaign->goal_amount }}"
     data-date="{{ $campaign->created_at }}"
     style="animation-delay:{{ $delay }}s">
    <div class="c-thumb">
        @if($campaign->cover_image)
            <img src="{{ asset('storage/'.$campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
            <div class="c-thumb-overlay"></div>
        @else
            <div class="c-thumb-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
        @endif
        <div class="c-badge-wrap">
            <span class="badge {{ $bc }}">{{ $bl }}</span>
            @if($fv === 'active' && $daysLeft !== null && $daysLeft >= 0)
                <span class="badge b-active" style="margin-left:4px;">
                    @if($daysLeft === 0)
                        Ends today
                    @elseif($daysLeft <= 3)
                        {{ $daysLeft }} day{{ $daysLeft !== 1 ? 's' : '' }} left
                    @else
                        {{ $daysLeft }}d left
                    @endif
                </span>
            @elseif($fv === 'expired')
                <span class="badge b-expired" style="margin-left:4px;">Ended</span>
            @endif
        </div>
    </div>
    <div class="c-body">
        <div class="c-title">
            {{ $campaign->title }}
            @if($campCats)
                <span style="display:inline-block;font-size:10px;font-weight:500;color:var(--text3);font-family:var(--mono);margin-left:6px;">{{ $campCats->name }}</span>
            @endif
        </div>

        @if($fv === 'inactive')
        <div class="reason reason-b">
            <div class="reason-lbl"><svg class="reason-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>Awaiting admin review</div>
            <div class="reason-txt">Your campaign will go live once approved.</div>
        </div>
        @elseif($fv === 'pending')
        <div class="reason reason-b">
            <div class="reason-lbl">Pending submission</div>
            <div class="reason-txt">Waiting to be reviewed by an admin.</div>
        </div>
        @elseif($fv === 'rejected' && $campaign->rejection_reason)
        <div class="reason reason-r">
            <div class="reason-lbl"><svg class="reason-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>Rejection reason</div>
            <div class="reason-txt">{{ $campaign->rejection_reason }}</div>
        </div>
        @elseif($fv === 'paused' && $campaign->pause_reason)
        <div class="reason reason-y">
            <div class="reason-lbl"><svg class="reason-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>Pause reason</div>
            <div class="reason-txt">{{ $campaign->pause_reason }}</div>
        </div>
        @elseif($fv === 'expired')
        <div class="reason reason-g">
            <div class="reason-lbl">Expired</div>
            <div class="reason-txt">This campaign has ended. Create a new one to continue.</div>
        </div>
        @endif

        <div class="prog-wrap">
            <div class="prog-numbers">
                <span class="prog-raised">₹{{ number_format($raised) }}</span>
                <span class="prog-goal">of ₹{{ number_format($campaign->goal_amount) }}</span>
            </div>
            <div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
            <div class="prog-meta">
                <span class="prog-pct">{{ $pct }}% funded</span>
                @if($campaign->donations_count > 0)
                    <span style="margin-left:auto;font-size:10.5px;color:var(--text3);">{{ $campaign->donations_count }} donation{{ $campaign->donations_count !== 1 ? 's' : '' }}</span>
                @endif
            </div>
        </div>

        <div class="c-actions">
            <x-button variant="secondary" href="{{ route('campaign.show', $campaign->id) }}" title="View" iconOnly>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
            </x-button>
            <x-button variant="secondary" href="{{ route('campaign.edit', $campaign->id) }}" title="Edit" iconOnly>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16.862 4.487a2.06 2.06 0 1 2.912 2.912L7.5 19.675 3 21l1.325-4.5L16.862 4.487z"/></svg>
            </x-button>
            @if($fv === 'active')
            <form action="{{ route('campaign.pause', $campaign->id) }}" method="POST" data-loading-text="Pausing…">
                @csrf
                <x-button variant="secondary" type="submit" title="Pause" iconOnly>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>
                </x-button>
            </form>
            @elseif($fv === 'paused')
            <form action="{{ route('campaign.resume', $campaign->id) }}" method="POST" data-loading-text="Resuming…">
                @csrf
                <x-button variant="secondary" type="submit" title="Resume" iconOnly>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="6,3 20,12 6,21" fill="currentColor"/></svg>
                </x-button>
            </form>
            @elseif($fv === 'rejected')
            <form action="{{ route('campaign.resubmit', $campaign->id) }}" method="POST" data-loading-text="Resubmitting…">
                @csrf
                <x-button variant="secondary" type="submit" title="Resubmit" iconOnly>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-2.64-6.36"/><path d="M21 3v6h-6"/></svg>
                </x-button>
            </form>
            @endif
        </div>
    </div>
</div>

@else

<div class="c-list-item"
     data-filter="{{ $fv }}"
     data-title="{{ strtolower($campaign->title) }}"
     data-amount="{{ $campaign->goal_amount }}"
     data-date="{{ $campaign->created_at }}"
     style="animation-delay:{{ $delay }}s">
    <div class="c-list-thumb">
        @if($campaign->cover_image)
            <img src="{{ asset('storage/'.$campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
        @else
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        @endif
    </div>
    <div class="c-list-info">
        <div class="c-list-title">
            {{ $campaign->title }}
            @if($campCats)
                <span style="font-size:10px;font-weight:500;color:var(--text3);font-family:var(--mono);margin-left:6px;">{{ $campCats->name }}</span>
            @endif
        </div>
        <div class="c-list-sub">
            <span>₹{{ number_format($raised) }} raised</span>
            <span class="c-list-dot"></span>
            <span>of ₹{{ number_format($campaign->goal_amount) }}</span>
            @if($campaign->donations_count > 0)
                <span class="c-list-dot"></span>
                <span>{{ $campaign->donations_count }} donation{{ $campaign->donations_count !== 1 ? 's' : '' }}</span>
            @endif
            @if($fv === 'active' && $daysLeft !== null && $daysLeft >= 0)
                <span class="c-list-dot"></span>
                <span style="color:var(--yellow);font-weight:600;">
                    @if($daysLeft === 0) Ends today
                    @elseif($daysLeft <= 3) {{ $daysLeft }}d left
                    @else {{ $daysLeft }}d
                    @endif
                </span>
            @endif
        </div>
    </div>
    <div class="c-list-prog">
        <div class="c-list-pct">{{ $pct }}%</div>
        <div class="c-list-bar"><div class="c-list-fill" style="width:{{ $pct }}%"></div></div>
    </div>
    <div class="c-list-badge">
        <span class="badge {{ $bc }}">{{ $bl }}</span>
        @if($fv === 'expired')
            <span class="badge b-expired" style="margin-left:3px;">Ended</span>
        @endif
    </div>
    <div class="c-list-actions">
        <x-button variant="secondary" href="{{ route('campaign.show', $campaign->id) }}" iconOnly title="View">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
        </x-button>
        <x-button variant="secondary" href="{{ route('campaign.edit', $campaign->id) }}" iconOnly title="Edit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16.862 4.487a2.06 2.06 0 1 2.912 2.912L7.5 19.675 3 21l1.325-4.5L16.862 4.487z"/></svg>
        </x-button>
        @if($fv === 'active')
        <form action="{{ route('campaign.pause', $campaign->id) }}" method="POST" data-loading-text="…">
            @csrf
            <x-button variant="secondary" type="submit" iconOnly title="Pause">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>
            </x-button>
        </form>
        @elseif($fv === 'paused')
        <form action="{{ route('campaign.resume', $campaign->id) }}" method="POST" data-loading-text="…">
            @csrf
            <x-button variant="secondary" type="submit" iconOnly title="Resume">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="6,3 20,12 6,21" fill="currentColor"/></svg>
            </x-button>
        </form>
        @endif
    </div>
</div>

@endif
