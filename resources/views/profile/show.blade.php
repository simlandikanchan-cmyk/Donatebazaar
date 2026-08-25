@extends('layouts.user')

@section('page_title', 'My Profile')
@section('page_subtitle', $user->email)

@section('content')
{{-- Hidden upload forms --}}
<form id="avatarForm" action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" style="display:none">
  @csrf <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp">
</form>
<form id="coverForm" action="{{ route('profile.cover') }}" method="POST" enctype="multipart/form-data" style="display:none">
  @csrf <input type="file" name="cover_image" id="coverInput" accept="image/jpeg,image/png,image/webp">
</form>

{{-- Upload preview modal --}}
<div class="overlay" id="uploadModal">
  <div class="modal">
    <button type="button" class="modal-x" data-action="cancel-upload">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
    <div class="modal-ttl" id="modalTitle">Confirm upload</div>
    <div class="modal-sub">Does this look good?</div>
    <img class="modal-preview" id="modalPreviewImg" src="" alt="Preview">
    <div class="modal-acts">
      <x-button variant="secondary" type="button" class="modal-btn" data-action="cancel-upload">Cancel</x-button>
      <x-button variant="primary" type="button" class="modal-btn" id="confirmUploadBtn">Upload</x-button>
    </div>
  </div>
</div>

{{-- Delete account modal --}}
<div class="overlay" id="deleteModal">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-delete-modal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
    <div class="modal-ttl">Delete your account?</div>
    <div class="modal-sub">This is permanent and cannot be undone. Enter your password to confirm.</div>
    <form action="{{ route('profile.destroy') }}" method="POST">
      @csrf @method('DELETE')
      <div class="field">
        <label>Password</label>
        <div class="pw-wrap">
          <input type="password" name="password" id="del-pw" placeholder="Enter your password" required>
          <button type="button" class="pw-eye" data-action="toggle-eye" data-input="del-pw">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        @error('password', 'userDeletion')<div class="field-err">{{ $message }}</div>@enderror
      </div>
      <div class="modal-acts">
        <x-button variant="secondary" type="button" data-action="close-delete-modal">Keep Account</x-button>
        <x-button variant="destructive" type="submit">Delete Permanently</x-button>
      </div>
    </form>
  </div>
</div>



{{-- ═══════════════════════════════════
     COVER / HERO CARD
═══════════════════════════════════ --}}
<div class="cover-card">

  {{-- Cover strip --}}
  <div class="cover-bg" id="coverBg">
    @if($user->cover_image)
      <img class="cover-img" src="{{ asset('storage/'.$user->cover_image) }}" id="coverImg" alt="Cover photo of {{ $user->name }}">
    @else
      <img class="cover-img" src="" id="coverImg" style="display:none;" alt="">
    @endif
    <x-button variant="primary" type="button" class="cover-edit-btn" data-action="trigger-click" data-target="coverInput">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
        <circle cx="12" cy="13" r="4"/>
      </svg>
      {{ $user->cover_image ? 'Edit' : 'Add' }}
    </x-button>
  </div>

  {{-- Hero inner --}}
  <div class="hero-inner">
    <div class="hero-av-group">
      <div class="profile-av-wrap">
        <div class="av-ring" id="avatarRing">
          @if($user->avatar)
            <img src="{{ asset('storage/'.$user->avatar) }}" id="avatarImg" alt="">
          @else
            <span id="avatarInitials">{{ strtoupper(substr($user->name,0,1)) }}</span>
            <img src="" id="avatarImg" style="display:none;" alt="">
          @endif
        </div>
        <button type="button" class="av-cam" data-action="trigger-click" data-target="avatarInput" title="Change photo">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
            <circle cx="12" cy="13" r="4"/>
          </svg>
        </button>
      </div>

      <div class="hero-meta">
        <div class="hero-name">{{ $user->name }}</div>
        <div class="hero-badges">
          <span class="hbadge hb-role">
            <svg width="9" height="9" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            {{ ucfirst($user->role ?? 'Donor') }}
          </span>
          @if($user->email_verified_at)
            <span class="hbadge hb-verified">✓ Verified</span>
          @else
            <span class="hbadge hb-unverified">! Unverified</span>
          @endif
        </div>
        <div class="hero-handle">&#64;{{ strtolower(str_replace(' ','_',$user->name)) }} · Joined {{ $user->created_at?->format('M Y') }}</div>
      </div>
    </div>

    <div class="hero-actions">
      <x-button variant="primary" type="button" data-action="switch-tab" data-tab="about">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit Profile
      </x-button>
      @if(Route::has('campaign.create'))
      <x-button variant="primary" href="{{ route('campaign.create') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Campaign
      </x-button>
      @endif
    </div>
  </div>

  {{-- Stat pills strip --}}
  <div class="stat-pills">
    <div class="stat-pill active" id="pill-campaigns" data-action="switch-tab" data-tab="campaigns">
      <div class="sp-val">{{ $campaignCount }}</div>
      <div class="sp-lbl">Campaigns</div>
    </div>
    <div class="stat-pill" id="pill-donations">
      <div class="sp-val">{{ $donationCount }}</div>
      <div class="sp-lbl">Donations</div>
    </div>
    <div class="stat-pill" id="pill-raised">
      <div class="sp-val">₹{{ number_format($donationTotal) }}</div>
      <div class="sp-lbl">Total Raised</div>
    </div>
  </div>
</div>{{-- /cover-card --}}

{{-- ═══════════════════════════════════
     PROFILE GRID
═══════════════════════════════════ --}}
<div class="profile-grid" @if($errors->any() && ($errors->has('current_password')||$errors->has('password')||$errors->has('name')||$errors->has('phone')||$errors->has('bio'))) data-auto-action="switch-tab" data-tab="about" @endif @if($errors->userDeletion->any()) data-auto-action="open-delete-modal" @endif>

  {{-- ── LEFT SIDEBAR ── --}}
  <div>

    {{-- Intro card --}}
    <div class="card" style="animation-delay:.06s;">
      <div class="card-head">
        <div class="card-head-left">
          <div class="card-ico" style="background:var(--a-lt);">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          </div>
          <div>
            <div class="card-ttl">Intro</div>
            <div class="card-sub">Public info</div>
          </div>
        </div>
        <x-button variant="secondary" type="button" data-action="switch-tab" data-tab="about">Edit</x-button>
      </div>
      <div class="card-body">
        @if($user->bio)
        <p style="font-size:12.5px;color:var(--text2);line-height:1.7;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid var(--border);">{{ $user->bio }}</p>
        @endif
        <div class="info-row">
          <span class="ir-left"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Role</span>
          <span class="ir-val">{{ ucfirst($user->role ?? 'Donor') }}</span>
        </div>
        @if($user->phone)
        <div class="info-row">
          <span class="ir-left"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.63A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z"/></svg>Phone</span>
          <span class="ir-val">{{ $user->phone }}</span>
        </div>
        @endif
        <div class="info-row">
          <span class="ir-left"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>Email</span>
          <span class="ir-val" style="font-size:10.5px;max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $user->email }}</span>
        </div>
        <div class="info-row">
          <span class="ir-left"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Joined</span>
          <span class="ir-val">{{ $user->created_at?->format('d M Y') }}</span>
        </div>
        <div class="info-row">
          <span class="ir-left"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>Verified</span>
          <span class="ir-val" style="color:{{ $user->email_verified_at ? 'var(--green)' : 'var(--red)' }}">
            {{ $user->email_verified_at ? 'Yes ✓' : 'No' }}
          </span>
        </div>
      </div>
    </div>

    {{-- Activity card --}}
    <div class="card" style="animation-delay:.1s;">
      <div class="card-head">
        <div class="card-head-left">
          <div class="card-ico" style="background:var(--green-lt);">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
          </div>
          <div>
            <div class="card-ttl">Activity</div>
            <div class="card-sub">Your impact stats</div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="activity-grid">
          <div class="act-stat" style="background:var(--a-lt);border:1px solid rgba(37,99,235,.14);">
            <div class="act-stat-val" style="color:var(--a);">{{ $campaignCount }}</div>
            <div class="act-stat-lbl" style="color:var(--a);">Campaigns</div>
          </div>
          <div class="act-stat" style="background:var(--green-lt);border:1px solid rgba(5,196,138,.14);">
            <div class="act-stat-val" style="color:var(--green);">{{ $donationCount }}</div>
            <div class="act-stat-lbl" style="color:var(--green);">Donations</div>
          </div>
        </div>
        <div class="act-stat" style="background:var(--amber-lt);border:1px solid rgba(245,158,11,.14);border-radius:var(--r-sm);padding:13px;text-align:center;margin-top:8px;">
          <div class="act-stat-val" style="color:var(--amber);">₹{{ number_format($donationTotal) }}</div>
          <div class="act-stat-lbl" style="color:var(--amber);">Total Raised</div>
        </div>
      </div>
    </div>

    {{-- ══ LEVEL CARD ══ --}}
    @if($currentLevel)
    <div class="card" style="animation-delay:.14s;">
      <div class="card-head">
        <div class="card-head-left">
          <div class="card-ico" style="background:{{ $currentLevel->badge_color ?: 'var(--a)' }}22;">
            <svg viewBox="0 0 24 24" fill="none" stroke="{{ $currentLevel->badge_color ?: 'var(--a)' }}" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
          </div>
          <div>
            <div class="card-ttl">Fundraiser Level</div>
            <div class="card-sub">Your fundraising rank</div>
          </div>
        </div>
        <span class="pf-level-badge" style="--lbg:{{ $currentLevel->badge_color ?: 'var(--a)' }}">{{ $levelName }}</span>
      </div>
      <div class="card-body">
        @if($nextLevel)
        <div style="margin-bottom:10px;">
          <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:5px;">
            <span style="font-size:11px;color:var(--text3);font-family:var(--mono);">Progress to <strong style="color:var(--text);">{{ $nextLevel->level_name }}</strong></span>
            <span style="font-size:11px;font-weight:700;font-family:var(--mono);color:var(--a);">{{ $levelProgress }}%</span>
          </div>
          <div style="width:100%;background:var(--surface3);border-radius:100px;height:6px;overflow:hidden;">
            <div style="height:100%;border-radius:100px;background:linear-gradient(90deg,{{ $currentLevel->badge_color ?: 'var(--a)' }},{{ $currentLevel->badge_color ?: 'var(--a2)' }});width:{{ $levelProgress }}%;transition:width 1s ease;"></div>
          </div>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <div class="pf-level-req {{ $campaignsCompleted >= $nextLevel->min_campaigns_completed ? 'done' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M20 6 9 17l-5-5"/></svg>
            {{ $campaignsCompleted }}/{{ $nextLevel->min_campaigns_completed }} campaigns
          </div>
          @if($nextLevel->min_raised_percent > 0)
          <div class="pf-level-req {{ $totalRaisedAll >= $nextLevel->min_raised_percent ? 'done' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M20 6 9 17l-5-5"/></svg>
            ₹{{ number_format($totalRaisedAll) }}/₹{{ number_format($nextLevel->min_raised_percent) }} raised
          </div>
          @endif
        </div>
        @else
        <div style="text-align:center;padding:4px 0;">
          <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2" style="width:22px;height:22px;display:block;margin:0 auto 4px;"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
          <div style="font-size:12px;font-weight:700;color:var(--a);font-family:var(--mono);">Highest Level Reached!</div>
          <div style="font-size:10.5px;color:var(--text3);margin-top:2px;">You're at the top tier.</div>
        </div>
        @endif
      </div>
    </div>
    @endif

  </div>{{-- /left --}}

  {{-- ── RIGHT: TAB CONTENT ── --}}
  <div>
    {{-- Tab bar --}}
    <div class="tab-bar">
      <x-button variant="secondary" type="button" class="tab-btn on" id="tb-campaigns" data-action="switch-tab" data-tab="campaigns">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
        <span>Campaigns</span> <span class="tab-cnt">{{ $campaignCount }}</span>
      </x-button>
      <x-button variant="secondary" type="button" class="tab-btn" id="tb-about" data-action="switch-tab" data-tab="about">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        <span>About &amp; Edit</span>
      </x-button>
      <x-button variant="secondary" type="button" class="tab-btn" id="tb-settings" data-action="switch-tab" data-tab="settings">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
        <span>Settings</span>
      </x-button>
    </div>

    {{-- ══ CAMPAIGNS TAB ══ --}}
    <div id="tc-campaigns" class="tab-content on">
      @forelse($userCampaigns as $i => $campaign)
      @php
        $goal     = $campaign->goal_amount ?? $campaign->goal ?? 0;
        $raised   = $campaign->raised_amount ?? $campaign->raised ?? 0;
        $pct      = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
        $status   = $campaign->campaign_state ?? 'active';
        $deadline = isset($campaign->end_date) ? \Carbon\Carbon::parse($campaign->end_date) : null;
        $daysLeft = $deadline ? max(0, now()->diffInDays($deadline, false)) : null;
        $donorCnt = $campaign->donations_count ?? 0;
        $campId   = $campaign->id ?? '';
        $statusClass = match($status) { 'active'=>'b-active','pending'=>'b-pending','rejected'=>'b-rejected','paused'=>'b-paused','expired'=>'b-expired','completed'=>'b-completed', default=>'b-inactive' };
      @endphp
      <div class="camp-card" style="animation-delay:{{ $i * 0.05 }}s;">
        <div class="camp-thumb">
          @if(!empty($campaign->cover_image))
            <img src="{{ asset('storage/'.$campaign->cover_image) }}" alt="{{ $campaign->title }}">
            <div class="camp-overlay"></div>
          @elseif(!empty($campaign->image))
            <img src="{{ asset('storage/'.$campaign->image) }}" alt="{{ $campaign->title }}">
            <div class="camp-overlay"></div>
          @else
            <div class="camp-placeholder">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
          @endif
          <div class="camp-badge-wrap">
            <span class="badge {{ $statusClass }}">{{ ucfirst($status) }}</span>
            @if($daysLeft !== null && $daysLeft <= 7 && $status === 'active')
              <span class="badge" style="background:rgba(240,68,68,.85);color:#fff;">
                {{ $daysLeft == 0 ? 'Last day!' : $daysLeft.' day'.($daysLeft!=1?'s':'').' left' }}
              </span>
            @endif
          </div>
        </div>
        <div class="camp-body">
          <div class="camp-title">{{ $campaign->title }}</div>
          @if(!empty($campaign->description))
            <div class="camp-desc">{{ $campaign->description }}</div>
          @endif
          @if($goal > 0)
          <div class="prog">
            <div class="prog-nums">
              <span class="prog-raised">₹{{ number_format($raised) }}</span>
              <span class="prog-goal">of ₹{{ number_format($goal) }}</span>
            </div>
            <div class="prog-bar">
              <div class="prog-fill {{ in_array($status,['inactive','expired','completed']) ? 'prog-fill-gray' : '' }}" style="width:{{ $pct }}%"></div>
            </div>
            <div class="prog-pct" style="{{ in_array($status,['inactive','expired','completed']) ? 'color:#64748b' : '' }}">{{ $pct }}% funded</div>
          </div>
          @endif
        </div>
        <div class="camp-footer">
          <div class="cf-meta">
            <span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
              {{ $donorCnt }} donor{{ $donorCnt !== 1 ? 's' : '' }}
            </span>
            @if($deadline)
            <span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              {{ $deadline->format('d M Y') }}
            </span>
            @endif
          </div>
          <div class="cf-actions">
            <x-button variant="primary" size="sm" type="button" data-action="share-campaign" data-title="{{ addslashes($campaign->title) }}" data-url="{{ route('campaign.show', $campaign->id) }}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
              Share
            </x-button>
            @if(Route::has('campaign.edit') && isset($campaign->id))
            <a href="{{ route('campaign.edit', $campaign->id) }}" class="btn btn-sm">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit
            </a>
            @endif
            @if(Route::has('campaign.show') && isset($campaign->id))
            <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-sm btn-primary">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              View
            </a>
            @endif
          </div>
        </div>
      </div>
      @empty
      <div class="empty-state">
        <div class="empty-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
        </div>
        <h3>No campaigns yet</h3>
        <p>Start your first campaign and make a real difference.</p>
        @if(Route::has('campaign.create'))
          <x-button variant="primary" href="{{ route('campaign.create') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Start a Campaign
          </x-button>
        @endif
      </div>
      @endforelse
    </div>

    {{-- ══ ABOUT & EDIT TAB ══ --}}
    <div id="tc-about" class="tab-content">

      {{-- Personal info --}}
      <div class="card" style="margin-bottom:14px;">
        <div class="card-head">
          <div class="card-head-left">
            <div class="card-ico" style="background:var(--a-lt);">
              <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
              <div class="card-ttl">Personal Info</div>
              <div class="card-sub">Update your public profile</div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('profile.update') }}" method="POST">
            @csrf @method('PATCH')
            <div class="two-col">
              <div class="field">
                <label>Full name</label>
                <input type="text" name="name" value="{{ old('name',$user->name) }}" placeholder="Your full name" required>
                @error('name')<div class="field-err">{{ $message }}</div>@enderror
              </div>
              <div class="field">
                <label>Phone number</label>
                <input type="text" name="phone" value="{{ old('phone',$user->phone) }}" placeholder="+91 XXXXX XXXXX">
                @error('phone')<div class="field-err">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="field">
              <label>Email address</label>
              <input type="email" value="{{ $user->email }}" readonly>
              <div class="field-hint">Email cannot be changed from here.</div>
            </div>
            <div class="field">
              <label>Bio</label>
              <textarea name="bio" rows="3" placeholder="Tell people a little about yourself...">{{ old('bio',$user->bio) }}</textarea>
              @error('bio')<div class="field-err">{{ $message }}</div>@enderror
            </div>
            <x-button variant="primary" type="submit">Save Changes</x-button>
          </form>
        </div>
      </div>

      {{-- Change password --}}
      <div class="card" style="margin-bottom:14px;">
        <div class="card-head">
          <div class="card-head-left">
            <div class="card-ico" style="background:var(--amber-lt);">
              <svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/></svg>
            </div>
            <div>
              <div class="card-ttl">Change Password</div>
              <div class="card-sub">Keep your account secure</div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            <div class="field">
              <label>Current Password</label>
              <div class="pw-wrap">
                <input type="password" name="current_password" id="pw-cur" placeholder="Enter current password">
                <button type="button" class="pw-eye" data-action="toggle-eye" data-input="pw-cur">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
              @error('current_password')<div class="field-err">{{ $message }}</div>@enderror
            </div>
            <div class="two-col">
              <div class="field">
                <label>New Password</label>
                <div class="pw-wrap">
                  <input type="password" name="password" id="pw-new" placeholder="Min 8 characters">
                  <button type="button" class="pw-eye" data-action="toggle-eye" data-input="pw-new">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </div>
                @error('password')<div class="field-err">{{ $message }}</div>@enderror
              </div>
              <div class="field">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Repeat new password">
              </div>
            </div>
            <x-button variant="primary" type="submit" class="ghost" style="margin-top:16px;">Update Password</x-button>
          </form>
        </div>
      </div>

      {{-- Account details (read-only) --}}
      <div class="card">
        <div class="card-head">
          <div class="card-head-left">
            <div class="card-ico" style="background:var(--blue-lt);">
              <svg viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
              <div class="card-ttl">Account Details</div>
              <div class="card-sub">Your account metadata</div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="acct-grid">
            <div class="acct-item">
              <div class="acct-lbl">Member Since</div>
              <div class="acct-val">{{ $user->created_at?->format('d M Y') }}</div>
            </div>
            <div class="acct-item">
              <div class="acct-lbl">Account Role</div>
              <div class="acct-val">{{ ucfirst($user->role ?? 'Donor') }}</div>
            </div>
            <div class="acct-item">
              <div class="acct-lbl">Email Verified</div>
              <div class="acct-val" style="color:{{ $user->email_verified_at ? 'var(--green)' : 'var(--red)' }}">
                {{ $user->email_verified_at ? 'Verified ✓' : 'Not verified' }}
              </div>
            </div>
            <div class="acct-item">
              <div class="acct-lbl">Account Status</div>
              <div class="acct-val">{{ ucfirst($user->status ?? 'Active') }}</div>
            </div>
          </div>
        </div>
      </div>

    </div>{{-- /tc-about --}}

    {{-- ══ SETTINGS TAB ══ --}}
    <div id="tc-settings" class="tab-content">

      {{-- Privacy & notifications --}}
      <div class="card" style="margin-bottom:14px;">
        <div class="card-head">
          <div class="card-head-left">
            <div class="card-ico" style="background:var(--a-lt);">
              <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/></svg>
            </div>
            <div>
              <div class="card-ttl">Privacy Settings</div>
              <div class="card-sub">Control your visibility</div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="setting-row">
            <div>
              <div class="setting-lbl">Profile visibility</div>
              <div class="setting-sub">Who can view your profile page</div>
            </div>
            <select>
              <option>Everyone</option>
              <option>Only me</option>
            </select>
          </div>
          <div class="setting-row">
            <div>
              <div class="setting-lbl">Email notifications</div>
              <div class="setting-sub">Receive updates about your campaigns</div>
            </div>
            <div class="toggle-sw">
              <input type="checkbox" id="ts-notif" checked>
              <div class="toggle-track" data-action="trigger-click" data-target="ts-notif"></div>
            </div>
          </div>
          <div class="setting-row">
            <div>
              <div class="setting-lbl">Show donation history</div>
              <div class="setting-sub">Make donations publicly visible</div>
            </div>
            <div class="toggle-sw">
              <input type="checkbox" id="ts-dh">
              <div class="toggle-track" data-action="trigger-click" data-target="ts-dh"></div>
            </div>
          </div>
          <div class="setting-row">
            <div>
              <div class="setting-lbl">Campaign updates</div>
              <div class="setting-sub">Get notified about campaigns you follow</div>
            </div>
            <div class="toggle-sw">
              <input type="checkbox" id="ts-cu" checked>
              <div class="toggle-track" data-action="trigger-click" data-target="ts-cu"></div>
            </div>
          </div>
          <div style="margin-top:16px;">
            <x-button variant="primary" type="button">Save Settings</x-button>
          </div>
        </div>
      </div>

      {{-- Danger zone --}}
      <div class="card" style="border-color:rgba(240,68,68,.2);">
        <div class="card-head" style="border-color:rgba(240,68,68,.12);">
          <div class="card-head-left">
            <div class="card-ico" style="background:var(--red-lt);">
              <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div>
              <div class="card-ttl" style="color:var(--red);">Danger Zone</div>
              <div class="card-sub">Irreversible actions</div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <p style="font-size:12.5px;color:var(--text2);margin-bottom:16px;line-height:1.7;">These actions are permanent and cannot be undone. Please be absolutely certain before proceeding.</p>
          <x-button variant="destructive" type="button" class="danger" data-action="open-delete-modal">
            Delete My Account
          </x-button>
        </div>
      </div>

    </div>{{-- /tc-settings --}}

  </div>{{-- /right --}}
</div>{{-- /profile-grid --}}
@endsection

@push('page_styles')
@vite('resources/css/user/pages/profile-show.css')
@endpush

@push('page_scripts')
@vite('resources/js/user/profile-show.js')
@endpush
