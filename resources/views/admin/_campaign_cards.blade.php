@foreach($campaigns as $c)
@php
  $raised = $c->raised_amount ?? 0;
  $goal = $c->goal_amount > 0 ? $c->goal_amount : 1;
  $pct = min(100, round(($raised / $goal) * 100));
  $state = $c->campaign_state;
  $isPaused = ($state === 'paused');
  $filter = $isPaused ? 'paused' : $state;
  $uName = $c->user?->name ?? 'Unknown';
  $uEmail = $c->user?->email ?? '';
  $uInit = strtoupper(substr($uName, 0, 1));
  $fillMod = $state === 'rejected' ? 'prog-fill-red' : (($state === 'expired' || $state === 'completed') ? 'prog-fill-gray' : '');
  $pctStyle = $state === 'rejected' ? 'color:var(--red)' : (($state === 'expired' || $state === 'completed') ? 'color:#64748b' : '');
@endphp
<div class="c-card" data-id="{{ $c->id }}" data-filter="{{ $filter }}" data-title="{{ strtolower($c->title) }}" data-amount="{{ $c->goal_amount }}" data-date="{{ $c->created_at }}" style="--delay:{{ $loop->index * 0.04 }}s">
  <label class="c-check" title="Select campaign">
    <input type="checkbox" class="c-checkbox" value="{{ $c->id }}">
    <span class="c-check-box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
  </label>
  <div class="c-thumb">
    @if($c->cover_image)<img src="{{ asset('storage/'.$c->cover_image) }}" alt="{{ $c->title }}" loading="lazy"><div class="c-overlay"></div>
    @else<div class="c-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>@endif
    <div class="c-badge-pos">
      @if($state === 'pending')<span class="badge b-pending">Pending</span>
      @elseif($isPaused)<span class="badge b-paused">Paused</span>
      @elseif($state === 'active')<span class="badge b-active">Active</span>
      @elseif($state === 'rejected')<span class="badge b-rejected">Rejected</span>
      @else<span class="badge b-inactive">{{ $state === 'completed' ? 'Completed' : 'Inactive' }}</span>@endif
    </div>
  </div>
  <div class="c-user"><div class="c-uav">{{ $uInit }}</div><div><div class="c-uname">{{ $uName }}</div>@if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif</div></div>
  <div class="c-body">
    <div class="c-title">{{ $c->title }}</div>

    @if($isPaused && $c->pause_reason)
      <div class="reason reason-amber"><div class="reason-lbl">⏸ PAUSE REASON</div><div class="reason-txt">{{ $c->pause_reason }}</div></div>
    @elseif($state === 'rejected' && $c->rejection_reason)
      <div class="reason reason-red"><div class="reason-lbl">✕ REJECTION REASON</div><div class="reason-txt">{{ $c->rejection_reason }}</div></div>
    @endif

    <div class="prog"><div class="prog-nums"><span class="prog-raised">₹{{ number_format($raised) }}</span><span class="prog-goal">of ₹{{ number_format($c->goal_amount) }}</span></div><div class="prog-bar"><div class="prog-fill {{ $fillMod }}" style="width:{{ $pct }}%"></div></div><div class="prog-pct prog-pct--{{ $filter }}">{{ $pct }}% funded</div></div>

    <div class="c-actions">
      @if($state === 'pending')
        <form action="{{ route('admin.campaign.approve',$c->id) }}" method="POST" class="c-form" onsubmit="return handleSub(this,'Approving…')">@csrf<x-button variant="primary" type="submit" class="c-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Approve</x-button></form>
        <x-button variant="destructive" type="button" class="c-btn" onclick="openReject({{ $c->id }})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Reject</x-button>
        <x-button variant="ghost" type="button" class="c-btn c-btn-view" href="{{ route('admin.campaign.show',$c->id) }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></x-button>
      @elseif($state === 'active' || $isPaused)
        @if(!$isPaused)
          <x-button variant="secondary" type="button" class="c-btn c-btn-pause" onclick="openPause({{ $c->id }})" fullWidth><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Pause</x-button>
        @else
          <form action="{{ route('admin.campaign.resume',$c->id) }}" method="POST" class="c-form" onsubmit="return handleSub(this,'Resuming…')">@csrf<x-button variant="primary" type="submit" class="c-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Resume</x-button></form>
        @endif
        <x-button variant="secondary" type="button" class="c-btn c-btn-view" href="{{ route('admin.campaign.show',$c->id) }}" fullWidth><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>View</x-button>
      @else
        <x-button variant="secondary" type="button" class="c-btn c-btn-view" href="{{ route('admin.campaign.show',$c->id) }}" fullWidth><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>View Details</x-button>
      @endif
    </div>
  </div>
</div>
@endforeach
