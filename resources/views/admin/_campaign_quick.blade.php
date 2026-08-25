@php
  $raised = $campaign->raised_amount ?? 0;
  $goal = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
  $pct = min(100, round(($raised / $goal) * 100));
  $state = $campaign->campaign_state;
  $uName = $campaign->user?->name ?? 'Unknown';
  $uEmail = $campaign->user?->email ?? '';
  $uInit = strtoupper(substr($uName, 0, 1));
  $kyc = optional($campaign->user)->kycVerification;
  $kycStatus = $kyc ? $kyc->status : 'not submitted';
  $badgeClass = match($state) {
    'pending' => 'b-pending',
    'active'  => 'b-active',
    'paused'  => 'b-paused',
    'rejected'=> 'b-rejected',
    default   => 'b-inactive',
  };
@endphp

<div class="qk-hero" style="position:relative;overflow:hidden">
  @if($campaign->cover_image)
    <img src="{{ asset('storage/'.$campaign->cover_image) }}" alt="{{ $campaign->title }}" class="qk-cover" style="width:100%;height:180px;object-fit:cover;display:block">
  @else
    <div class="qk-cover qk-cover-ph" style="width:100%;height:180px;display:flex;align-items:center;justify-content:center;background:var(--surface2)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:48px;height:48px;color:var(--text3);opacity:.4"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
  @endif
  <div class="qk-hero-grad" style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0) 0%,rgba(0,0,0,.45) 100%);pointer-events:none"></div>
  <span class="badge {{ $badgeClass }} qk-badge" style="position:absolute;top:12px;left:12px;">{{ ucfirst($state) }}</span>
</div>

<style>
@media(max-width:640px){.qk-hero{max-height:200px}.qk-cover{height:140px!important}.qk-body{padding:14px!important}.qk-stats{grid-template-columns:1fr 1fr!important}.qk-acts{flex-direction:column!important}.qk-acts>form,.qk-acts>button{width:100%!important;justify-content:center}}
</style>

<div class="qk-body">
  <h3 class="qk-title">{{ $campaign->title }}</h3>

  <div class="qk-owner">
    <div class="c-uav">{{ $uInit }}</div>
    <div>
      <div class="c-uname">{{ $uName }}</div>
      @if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif
    </div>
    <span class="qk-kyc qk-kyc-{{ str_replace(' ','-',$kycStatus) }}">{{ ucfirst($kycStatus) }}</span>
  </div>

  <div class="qk-stats">
    <div><span>Raised</span><b>₹{{ number_format($raised) }}</b></div>
    <div><span>Goal</span><b>₹{{ number_format($campaign->goal_amount) }}</b></div>
    <div><span>Funded</span><b>{{ $pct }}%</b></div>
  </div>

  <div class="prog-bar qk-prog"><div class="prog-fill @if($state==='rejected')prog-fill-red @elseif($state==='expired'||$state==='completed')prog-fill-gray @endif" style="width:{{ $pct }}%"></div></div>

  <div class="qk-meta">
    <div><span>Category</span><b>{{ $campaign->category?->name ?? '—' }}</b></div>
    <div><span>Created</span><b>{{ $campaign->created_at?->format('d M Y') ?? '—' }}</b></div>
    <div><span>Ends</span><b>{{ $campaign->end_date?->format('d M Y') ?? '—' }}</b></div>
  </div>

  @if($campaign->short_description)
    <p class="qk-desc">{{ $campaign->short_description }}</p>
  @endif
  @if($campaign->description)
    <p class="qk-desc qk-desc-full">{{ \Illuminate\Support\Str::limit(strip_tags($campaign->description), 420) }}</p>
  @endif

  @if($state === 'paused' && $campaign->pause_reason)
    <div class="reason reason-amber"><div class="reason-lbl">⏸ PAUSE REASON</div><div class="reason-txt">{{ $campaign->pause_reason }}</div></div>
  @elseif($state === 'rejected' && $campaign->rejection_reason)
    <div class="reason reason-red"><div class="reason-lbl">✕ REJECTION REASON</div><div class="reason-txt">{{ $campaign->rejection_reason }}</div></div>
  @endif

  <div class="qk-acts">
    <a href="{{ route('admin.campaign.show',$campaign->id) }}" class="btn btn-secondary act-btn ab-view" style="flex:1;">Open Full Page</a>
    @if($state === 'pending')
      <form action="{{ route('admin.campaign.approve',$campaign->id) }}" method="POST" style="flex:1;" data-loading-text="Approving…">@csrf<button type="submit" class="btn btn-green c-btn c-btn-approve">Approve</button></form>
      <button type="button" data-action="close-quick" data-action-2="open-reject" data-id="{{ $campaign->id }}" class="btn btn-red c-btn c-btn-reject">Reject</button>
    @elseif($state === 'active')
      <button type="button" data-action="close-quick" data-action-2="open-pause" data-id="{{ $campaign->id }}" class="btn btn-secondary c-btn c-btn-pause" style="flex:1;">Pause</button>
    @elseif($state === 'paused')
      <form action="{{ route('admin.campaign.resume',$campaign->id) }}" method="POST" style="flex:1;" data-loading-text="Resuming…">@csrf<button type="submit" class="btn btn-green c-btn c-btn-resume">Resume</button></form>
    @endif
  </div>
</div>
