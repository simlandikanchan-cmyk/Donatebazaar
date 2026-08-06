@extends('layouts.user')

@section('page_title', 'Saved Campaigns')
@section('page_subtitle', 'Campaigns you\'re following')

@section('content')

@if($campaigns->count() > 0)

<div class="c-grid" id="campaignGrid">
    @foreach($campaigns as $i => $campaign)
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
    @endphp
    @php
        $daysLeft = $campaign->end_date ? now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($campaign->end_date)->startOfDay(), false) : null;
        $campCats = $campaign->category;
    @endphp
    <div class="c-card"
         data-title="{{ strtolower($campaign->title) }}"
         data-amount="{{ $campaign->goal_amount }}"
         data-date="{{ $campaign->created_at }}"
         style="animation-delay:{{ $i * .04 }}s">
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
                        @if($daysLeft === 0) Ends today
                        @elseif($daysLeft <= 3) {{ $daysLeft }} day{{ $daysLeft !== 1 ? 's' : '' }} left
                        @else {{ $daysLeft }}d left
                        @endif
                    </span>
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
                <a href="{{ $campaign->slug ? url('/campaigns/'.($campaign->category?->slug ?? 'general').'/'.$campaign->slug) : route('campaign.show', $campaign->id) }}" class="btn btn-accent" style="flex:1;" target="_blank">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View
                </a>
                <form action="{{ route('campaign.follow', $campaign->id) }}" method="POST" style="flex:1;">
                    @csrf
                    <x-button variant="primary" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        Unfollow
                    </x-button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($campaigns->hasPages())
<div class="rd-pagination" style="margin-top:18px;">
    {{ $campaigns->links() }}
</div>
@endif

@else
<div class="empty-state">
    <div class="empty-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
    </div>
    <div class="empty-title">No saved campaigns yet</div>
    <div class="empty-sub">When you follow a campaign, it will appear here so you can track its progress.</div>
    <x-button variant="primary" href="{{ route('all.campaigns') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Browse Campaigns
    </x-button>
</div>
@endif

@endsection
