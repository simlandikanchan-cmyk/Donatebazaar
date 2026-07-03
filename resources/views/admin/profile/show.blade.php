@extends('layouts.admin')

@section('sidebar_profile', 'active')
@section('page_title', 'My Profile')
@section('page_subtitle', auth()->user()->email ?? '')

@push('page_styles')
<style>
.profile-grid{display:grid;grid-template-columns:280px 1fr;gap:24px;align-items:start;}
@media(max-width:768px){.profile-grid{grid-template-columns:1fr;}}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;}
.card-header{padding:16px 20px;border-bottom:1px solid var(--border);font-size:13px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:10px;}
.card-header svg{width:15px;height:15px;color:var(--a);flex-shrink:0;}
.card-body{padding:20px;}
.av-card{text-align:center;padding:32px 20px 24px;}
.profile-av-wrap{position:relative;width:100px;height:100px;margin:0 auto 16px;}
.profile-av-wrap img{width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--border2);display:block;}
.profile-av-wrap .av-letter{width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:700;font-family:var(--mono);margin:0 auto;border:3px solid var(--border2);}
.av-cam-btn{position:absolute;bottom:0;right:0;width:32px;height:32px;border-radius:50%;background:var(--a);color:#fff;border:2px solid var(--surface);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:transform .2s;}
.av-cam-btn:hover{transform:scale(1.1);}
.av-cam-btn svg{width:13px;height:13px;}
.av-name{font-size:17px;font-weight:800;color:var(--text);margin-bottom:2px;}
.av-email{font-size:12px;color:var(--text3);margin-bottom:12px;}
.av-role{display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:100px;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);background:rgba(99,102,241,.1);color:var(--a);border:1px solid rgba(99,102,241,.2);}
.form-group{margin-bottom:16px;}
.form-group label{display:block;font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:5px;}
.form-group input{width:100%;height:38px;padding:0 12px;border:1px solid var(--border);border-radius:var(--r-sm);font-size:13px;color:var(--text);background:var(--surface);outline:none;transition:border-color .2s;}
.form-group input:focus{border-color:var(--a);box-shadow:0 0 0 3px rgba(99,102,241,.12);}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
@media(max-width:480px){.form-row{grid-template-columns:1fr;}}
.btn-save{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 18px;border-radius:var(--r-sm);font-size:12px;font-weight:700;border:none;cursor:pointer;background:var(--a);color:#fff;transition:opacity .2s;}
.btn-save:hover{opacity:.85;}
.btn-save svg{width:13px;height:13px;}
.section-divider{border-top:1px solid var(--border);margin:20px 0;}
.sessions-card .card-body{padding:0;}
.sessions-card .card-header svg{color:var(--amber);}
.session-row{display:flex;align-items:center;gap:12px;padding:11px 20px;font-size:12.5px;}
.session-row+.session-row{border-top:1px solid var(--border);}
.session-icon{width:34px;height:34px;border-radius:9px;background:var(--surface2);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--text3);}
.session-icon svg{width:15px;height:15px;}
.session-info{flex:1;min-width:0;}
.session-device{font-weight:600;color:var(--text);}
.session-meta{font-size:11px;color:var(--text3);margin-top:1px;display:flex;gap:6px;flex-wrap:wrap;}
.sess-badge{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:100px;font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.2);font-family:var(--mono);}
.sess-revoke{flex-shrink:0;padding:5px 12px;border-radius:var(--r-sm);font-size:11px;font-weight:600;border:1px solid var(--border2);background:var(--surface2);color:var(--text3);cursor:pointer;transition:all var(--ease);text-decoration:none;}
.sess-revoke:hover{background:var(--red-lt);border-color:var(--red);color:var(--red);}
.sess-footer{padding:10px 20px;border-top:1px solid var(--border);display:flex;align-items:center;gap:10px;}
.sess-revoke-all{display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border-radius:var(--r-sm);font-size:11px;font-weight:600;border:1px solid var(--border2);background:var(--surface2);color:var(--text3);cursor:pointer;transition:all var(--ease);text-decoration:none;white-space:nowrap;}
.sess-revoke-all:hover{background:var(--amber-lt);border-color:var(--amber);color:var(--amber);}
.danger-warn{font-size:12px;color:var(--text3);line-height:1.6;margin-bottom:14px;}
.danger-warn strong{color:var(--red);}
.danger-btn{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 16px;border-radius:var(--r-sm);font-size:12px;font-weight:700;border:1px solid rgba(240,68,68,.25);background:var(--red-lt);color:var(--red);cursor:pointer;transition:all var(--ease);}
.danger-btn:hover{background:var(--red);color:#fff;border-color:var(--red);}
.danger-btn svg{width:13px;height:13px;}
</style>
@endpush

@section('content')
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
        <button type="button" class="av-cam-btn" onclick="document.getElementById('avatarInput').click()" title="Change photo">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
        </button>
      </div>
      <div class="av-name">{{ auth()->user()->name }}</div>
      <div class="av-email">{{ auth()->user()->email }}</div>
      <div class="av-role">Administrator</div>
    </div>
  </div>

  {{-- Right: Edit Forms --}}
  <div>

    {{-- Profile Information --}}
    <div class="card" style="margin-bottom:20px; margin-top:20px;">
      <div class="card-header">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        Profile Information
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.profile.update') }}">
          @csrf
          @method('PATCH')
          <div class="form-row">
            <div class="form-group">
              <label for="name">Name</label>
              <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" required>
              @error('name') <span style="font-size:11px;color:#ef4444;margin-top:3px;display:block;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required>
              @error('email') <span style="font-size:11px;color:#ef4444;margin-top:3px;display:block;">{{ $message }}</span> @enderror
            </div>
          </div>
          <button type="submit" class="btn-save">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Save Changes
          </button>
        </form>
      </div>
    </div>

    {{-- Change Password --}}
    <div class="card">
      <div class="card-header">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        Change Password
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.profile.password') }}">
          @csrf
          <div class="form-group">
            <label for="current_password">Current Password</label>
            <input id="current_password" name="current_password" type="password" required>
            @error('current_password') <span style="font-size:11px;color:#ef4444;margin-top:3px;display:block;">{{ $message }}</span> @enderror
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="password">New Password</label>
              <input id="password" name="password" type="password" required>
              @error('password') <span style="font-size:11px;color:#ef4444;margin-top:3px;display:block;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label for="password_confirmation">Confirm Password</label>
              <input id="password_confirmation" name="password_confirmation" type="password" required>
            </div>
          </div>
          <button type="submit" class="btn-save">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Update Password
          </button>
        </form>
      </div>
    </div>

    {{-- Active Sessions --}}
    <div class="card sessions-card" style="margin-bottom:20px;">
      <div class="card-header">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Active Sessions
      </div>
      <div class="card-body">
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
              <form method="POST" action="{{ route('admin.profile.sessions.revoke', $session->id) }}" onsubmit="return confirm('Revoke this session? The device will be signed out.');" style="display:inline-flex;">
                @csrf
                @method('DELETE')
                <button type="submit" class="sess-revoke">Revoke</button>
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
            <form method="POST" action="{{ route('admin.profile.sessions.revoke-all') }}" onsubmit="return confirm('Revoke all other active sessions? You will stay logged in on this device.');" style="display:inline-flex;">
              @csrf
              @method('DELETE')
              <button type="submit" class="sess-revoke-all">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Revoke All Others
              </button>
            </form>
          </div>
        @endif
      </div>
    </div>

    {{-- Danger Zone --}}
    <div class="card" style="border-color:rgba(240,68,68,.25);">
      <div class="card-header" style="color:var(--red);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        Danger Zone
      </div>
      <div class="card-body">
        <div class="danger-warn">
          <strong>Irreversible action.</strong> Deleting your account will permanently remove all your data including campaigns, donation records, and personal information. This cannot be undone.
        </div>
        <form method="POST" action="{{ route('admin.profile.destroy') }}" onsubmit="return confirm('Are you absolutely sure? This will permanently delete your account and all associated data.');">
          @csrf
          @method('DELETE')
          <div class="form-group" style="margin-bottom:12px;">
            <label for="delete_password">Enter your password to confirm</label>
            <input id="delete_password" name="password" type="password" required style="max-width:300px;">
            @error('password') <span style="font-size:11px;color:#ef4444;margin-top:3px;display:block;">{{ $message }}</span> @enderror
          </div>
          <button type="submit" class="danger-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete My Account
          </button>
        </form>
      </div>
    </div>

    {{-- Back link --}}
    <div style="margin-top:16px;">
      <a href="{{ route('admin.dashboard') }}" style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:var(--text3);text-decoration:none;">
        <svg style="width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Back to Dashboard
      </a>
    </div>

  </div>
</div>

<script>
document.getElementById('avatarInput')?.addEventListener('change', function() {
  if (this.files && this.files[0]) {
    document.getElementById('avatarForm').submit();
  }
});
</script>
@endsection
