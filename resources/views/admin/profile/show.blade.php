
@extends('layouts.admin')

@section('sidebar_profile', 'active')
@section('page_title', 'My Profile')
@section('page_subtitle', auth()->user()->email ?? '')

@push('page_styles')
@vite('resources/css/admin/entries/profile-show.css')
<style>
@media(max-width:860px){.hero{flex-direction:column;align-items:stretch;gap:16px}.hero-right{width:100%;margin-top:14px}.hero-right .hero-btn{width:100%;justify-content:center}.profile-grid{grid-template-columns:1fr!important}.profile-grid>div:last-child{display:none}}
@media(max-width:640px){.hero-left{flex-direction:column;align-items:flex-start;gap:12px}.hero-badges{flex-wrap:wrap}.av-card{padding:20px!important}.card-body{padding:16px!important}.form-row{grid-template-columns:1fr!important}.form-row .form-group{width:100%!important}.email-line{flex-direction:column;align-items:flex-start!important}}
@media(max-width:480px){.hero-name{font-size:18px!important}.hero-sub{font-size:12px}.card-header{font-size:11px!important;padding:12px 14px!important}}
</style>
@endpush

@section('content')

@if(session('success'))
<div class="flash-banner flash-ok" id="flashBanner">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  <span>{{ session('success') }}</span>
  <button class="flash-x" data-action="dismiss-flash" aria-label="Dismiss">✕</button>
</div>
@endif
@if($errors->any())
<div class="flash-banner flash-err" id="flashBanner">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
  <span>Please fix the errors below.</span>
  <button class="flash-x" data-action="dismiss-flash" aria-label="Dismiss">✕</button>
</div>
@endif

<div class="hero">
  <div class="hero-left">
    <div class="hero-greeting">
      <div class="hero-avatar">
        @if(auth()->user()->avatar)
          <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
        @else
          {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
        @endif
      </div>
      <div>
        <div class="hero-tag"><span class="hero-tag-dot"></span>My Account</div>
        <div class="hero-name">{{ auth()->user()->name }}</div>
        <div class="hero-sub">{{ auth()->user()->email }}</div>
      </div>
    </div>
    <div class="hero-badges">
      <span class="hero-badge hb-purple">Administrator</span>
      @if(auth()->user()->email_verified_at)
        <span class="hero-badge hb-green">✓ Verified email</span>
      @else
        <span class="hero-badge hb-amber">Pending verification</span>
      @endif
      <span class="hero-badge hb-blue">Member since {{ auth()->user()->created_at->format('M Y') }}</span>
    </div>
  </div>
  <div class="hero-right">
    <a href="{{ route('admin.dashboard') }}" class="hero-btn hero-btn-ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Back to Dashboard
    </a>
  </div>
</div>

<div class="profile-grid">

  {{-- Left: Avatar Card --}}
  <div class="card">
    <div class="av-card">
      <div class="profile-av-wrap">
        <form id="avatarForm" action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data" style="display:none">
          @csrf
          <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp">
        </form>
        @if(auth()->user()->avatar)
          <img src="{{ asset('storage/'.auth()->user()->avatar) }}" id="adminAvatarImg" alt="Avatar">
        @else
          <div class="av-letter">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
        @endif
        <button type="button" class="av-cam-btn" id="avCamBtn" title="Change photo">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
        </button>
      </div>
      <div class="av-name">{{ auth()->user()->name }}</div>
      <div class="av-email">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        {{ auth()->user()->email }}
      </div>
      <div class="av-role">Administrator</div>
      <div class="av-hint">Click the camera to upload a photo</div>
      <div class="av-err" id="avErr"></div>

      <div class="av-meta">
        <div class="av-meta-row">
          <span class="av-meta-lbl">Member since</span>
          <span class="av-meta-val">{{ auth()->user()->created_at->format('M Y') }}</span>
        </div>
        <div class="av-meta-row">
          <span class="av-meta-lbl">Email</span>
          @if(auth()->user()->email_verified_at)
            <span class="verified-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="width:9px;height:9px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Verified</span>
          @else
            <span class="verified-badge pending"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="width:9px;height:9px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/></svg>Pending</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Right: Edit Forms --}}
  <div>

    {{-- Profile Information --}}
    <div class="card">
      <div class="card-header">
        <span class="h-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
        Profile Information
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.profile.update') }}" id="profileForm">
          @csrf
          @method('PATCH')
          <div class="form-row">
            <div class="form-group">
              <label for="name">Name</label>
              <div class="inp-wrap">
                <svg class="inp-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" class="@error('name') err @enderror" required>
              </div>
              @error('name') <span class="field-err">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <div class="email-line">
                <div class="inp-wrap" style="flex:1;">
                  <svg class="inp-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" class="@error('email') err @enderror" required>
                </div>
                @if(auth()->user()->email_verified_at)
                  <span class="verified-badge" title="Email verified"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="width:9px;height:9px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Verified</span>
                @else
                  <span class="verified-badge pending" title="Email not verified"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="width:9px;height:9px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/></svg>Pending</span>
                @endif
              </div>
              @error('email') <span class="field-err">{{ $message }}</span> @enderror
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-save">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Save Changes
          </button>
        </form>
      </div>
    </div>

    {{-- Change Password --}}
    <div class="card">
      <div class="card-header">
        <span class="h-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
        Change Password
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.profile.password') }}" id="pwForm">
          @csrf
          <div class="form-group">
            <label for="current_password">Current Password</label>
            <div class="pw-wrap">
              <svg class="inp-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
              <input id="current_password" name="current_password" type="password" class="@error('current_password') err @enderror" required>
              <button type="button" class="pw-toggle" data-target="current_password" aria-label="Show password">
                <svg class="ic-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <svg class="ic-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0112 20c-4.477 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.74 0 3.37.45 4.82 1.24M9.88 9.88a3 3 0 104.24 4.24"/></svg>
              </button>
            </div>
            @error('current_password') <span class="field-err">{{ $message }}</span> @enderror
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="password">New Password</label>
              <div class="pw-wrap">
                <svg class="inp-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                <input id="password" name="password" type="password" class="@error('password') err @enderror" required>
                <button type="button" class="pw-toggle" data-target="password" aria-label="Show password">
                  <svg class="ic-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  <svg class="ic-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0112 20c-4.477 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.74 0 3.37.45 4.82 1.24M9.88 9.88a3 3 0 104.24 4.24"/></svg>
                </button>
              </div>
              @error('password') <span class="field-err">{{ $message }}</span> @enderror
              <div class="pw-strength" id="pwStrength" style="display:none;">
                <div class="pw-strength-bar"><div class="pw-strength-fill" id="pwFill"></div></div>
                <div class="pw-strength-lbl" id="pwLbl"></div>
              </div>
            </div>
            <div class="form-group">
              <label for="password_confirmation">Confirm Password</label>
              <div class="pw-wrap">
                <svg class="inp-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                <input id="password_confirmation" name="password_confirmation" type="password" required>
                <button type="button" class="pw-toggle" data-target="password_confirmation" aria-label="Show password">
                  <svg class="ic-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  <svg class="ic-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0112 20c-4.477 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.74 0 3.37.45 4.82 1.24M9.88 9.88a3 3 0 104.24 4.24"/></svg>
                </button>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-save">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Update Password
          </button>
        </form>
      </div>
    </div>

    {{-- Active Sessions --}}
    <div class="card sessions-card">
      <div class="card-header">
        <span class="h-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
        Active Sessions
      </div>
      <div class="card-body" style="padding:0;">
        @forelse($sessions as $session)
          <div class="session-row">
            <div class="session-icon">
              @php
                $ua = $session->user_agent ?? '';
                $isMobile = preg_match('/Android|iPhone|iPad|iPod/i', $ua);
              @endphp
              @if($isMobile)
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><path d="M12 18h.01"/></svg>
              @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              @endif
            </div>
            <div class="session-info">
              <div class="session-device">
                @php
                  $agent = $session->user_agent ?? '';
                  $parts = [];
                  if (preg_match('/Windows/i', $agent)) $parts[] = 'Windows';
                  elseif (preg_match('/Macintosh|Mac OS/i', $agent)) $parts[] = 'macOS';
                  elseif (preg_match('/Linux/i', $agent) && !preg_match('/Android/i', $agent)) $parts[] = 'Linux';
                  if (preg_match('/Android/i', $agent)) $parts[] = 'Android';
                  elseif (preg_match('/iPhone|iPad|iPod/i', $agent)) $parts[] = 'iOS';
                  if (preg_match('/Chrome/i', $agent) && !preg_match('/Edg/i', $agent)) $parts[] = 'Chrome';
                  elseif (preg_match('/Firefox/i', $agent)) $parts[] = 'Firefox';
                  elseif (preg_match('/Safari/i', $agent) && !preg_match('/Chrome/i', $agent)) $parts[] = 'Safari';
                  elseif (preg_match('/Edg/i', $agent)) $parts[] = 'Edge';
                  echo implode(' · ', $parts) ?: 'Unknown device';
                @endphp
              </div>
              <div class="session-meta">
                <span>{{ $session->ip_address }}</span>
                <span>·</span>
                <span>{{ $session->last_active }}</span>
                @if($session->is_current)
                  <span class="sess-badge">This device</span>
                @endif
              </div>
            </div>
            @if(!$session->is_current)
              <form method="POST" action="{{ route('admin.profile.sessions.revoke', $session->id) }}" data-confirm="Revoke this session? The device will be signed out." style="display:inline-flex;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-red sess-revoke">Revoke</button>
              </form>
            @endif
          </div>
        @empty
          <div style="padding:20px;text-align:center;font-size:12px;color:var(--text3);">No active sessions found.</div>
        @endforelse
        @if($sessions->count() > 1)
          <div class="sess-footer">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;color:var(--text3);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span style="font-size:11.5px;color:var(--text3);flex:1;">Revoke all sessions on other devices. You will stay logged in here.</span>
            <form method="POST" action="{{ route('admin.profile.sessions.revoke-all') }}" data-confirm="Revoke all other active sessions? You will stay logged in on this device." style="display:inline-flex;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-red sess-revoke-all">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Revoke All Others
              </button>
            </form>
          </div>
        @endif
      </div>
    </div>

    {{-- Danger Zone --}}
    <div class="card danger-card">
      <div class="card-header">
        <span class="h-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></span>
        Danger Zone
      </div>
      <div class="card-body">
        <div class="danger-warn">
          <strong>Irreversible action.</strong> Deleting your account will permanently remove all your data including campaigns, donation records, and personal information. This cannot be undone.
        </div>
        <button type="button" class="btn btn-red danger-btn" data-action="open-delete-modal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete My Account
        </button>
      </div>
    </div>
  </div>
</div>

{{-- DELETE ACCOUNT MODAL --}}
<div class="modal-overlay" id="deleteModal">
  <div class="modal">
    <div class="modal-ico">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
    </div>
    <div class="modal-ttl">Delete Account?</div>
    <div class="modal-desc">This will <strong>permanently delete</strong> your account and all associated data. Enter your password to confirm — this cannot be undone.</div>
    <form method="POST" action="{{ route('admin.profile.destroy') }}" id="deleteForm">
      @csrf
      @method('DELETE')
      <div class="form-group" style="margin-bottom:18px;">
        <label for="delete_password">Password</label>
        <div class="pw-wrap">
          <svg class="inp-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          <input id="delete_password" name="password" type="password" class="@error('password') err @enderror" required>
          <button type="button" class="pw-toggle" data-target="delete_password" aria-label="Show password">
            <svg class="ic-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <svg class="ic-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0112 20c-4.477 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.74 0 3.37.45 4.82 1.24M9.88 9.88a3 3 0 104.24 4.24"/></svg>
          </button>
        </div>
        @error('password') <span class="field-err">{{ $message }}</span> @enderror
      </div>
      <div class="modal-btns">
        <button type="button" class="btn btn-secondary btn-modal-cancel" data-action="close-delete-modal">Cancel</button>
        <button type="submit" class="btn btn-red btn-modal-delete">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete Account
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/profile-show.js')
@endpush
