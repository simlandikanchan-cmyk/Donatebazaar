@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush


@section('sidebar_profile', 'active')
@section('page_title', 'My Profile')
@section('page_subtitle', auth()->user()->email ?? '')

@section('content')

@if(session('success'))
<div class="flash-banner flash-ok" id="flashBanner">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  <span>{{ session('success') }}</span>
  <button class="flash-x" onclick="this.parentElement.remove()" aria-label="Dismiss">✕</button>
</div>
@endif
@if($errors->any())
<div class="flash-banner flash-err" id="flashBanner">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
  <span>Please fix the errors below.</span>
  <button class="flash-x" onclick="this.parentElement.remove()" aria-label="Dismiss">✕</button>
</div>
@endif

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
        <x-button variant="primary" type="button" class="av-cam-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
        </x-button>
      </div>
      <div class="av-name">{{ auth()->user()->name }}</div>
      <div class="av-email">{{ auth()->user()->email }}</div>
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
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        Profile Information
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.profile.update') }}" id="profileForm">
          @csrf
          @method('PATCH')
          <div class="form-row">
            <div class="form-group">
              <label for="name">Name</label>
              <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" class="@error('name') err @enderror" required>
              @error('name') <span class="field-err">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <div class="email-line">
                <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" class="@error('email') err @enderror" style="flex:1;" required>
                @if(auth()->user()->email_verified_at)
                  <span class="verified-badge" title="Email verified"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="width:9px;height:9px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Verified</span>
                @else
                  <span class="verified-badge pending" title="Email not verified"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="width:9px;height:9px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/></svg>Pending</span>
                @endif
              </div>
              @error('email') <span class="field-err">{{ $message }}</span> @enderror
            </div>
          </div>
          <x-button variant="primary" type="submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Save Changes
          </x-button>
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
        <form method="POST" action="{{ route('admin.profile.password') }}" id="pwForm">
          @csrf
          <div class="form-group">
            <label for="current_password">Current Password</label>
            <div class="pw-wrap">
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
                <input id="password_confirmation" name="password_confirmation" type="password" required>
                <button type="button" class="pw-toggle" data-target="password_confirmation" aria-label="Show password">
                  <svg class="ic-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  <svg class="ic-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0112 20c-4.477 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.74 0 3.37.45 4.82 1.24M9.88 9.88a3 3 0 104.24 4.24"/></svg>
                </button>
              </div>
            </div>
          </div>
          <x-button variant="primary" type="submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Update Password
          </x-button>
        </form>
      </div>
    </div>

    {{-- Active Sessions --}}
    <div class="card sessions-card">
      <div class="card-header">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
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
              <form method="POST" action="{{ route('admin.profile.sessions.revoke', $session->id) }}" onsubmit="return confirm('Revoke this session? The device will be signed out.');" style="display:inline-flex;">
                @csrf
                @method('DELETE')
                <x-button variant="destructive" type="submit">Revoke</x-button>
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
              <x-button variant="destructive" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Revoke All Others
              </x-button>
            </form>
          </div>
        @endif
      </div>
    </div>

    {{-- Danger Zone --}}
    <div class="card danger-card">
      <div class="card-header">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        Danger Zone
      </div>
      <div class="card-body">
        <div class="danger-warn">
          <strong>Irreversible action.</strong> Deleting your account will permanently remove all your data including campaigns, donation records, and personal information. This cannot be undone.
        </div>
        <x-button variant="destructive" type="button">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete My Account
        </x-button>
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
          <input id="delete_password" name="password" type="password" class="@error('password') err @enderror" required>
          <button type="button" class="pw-toggle" data-target="delete_password" aria-label="Show password">
            <svg class="ic-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <svg class="ic-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0112 20c-4.477 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.74 0 3.37.45 4.82 1.24M9.88 9.88a3 3 0 104.24 4.24"/></svg>
          </button>
        </div>
        @error('password') <span class="field-err">{{ $message }}</span> @enderror
      </div>
      <div class="modal-btns">
        <x-button variant="secondary" type="button" class="btn-modal-cancel">Cancel</x-button>
        <x-button variant="destructive" type="submit" class="btn-modal-delete">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete Account
        </x-button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

/* —€—€ Toast (fallback for avatar client errors) —€—€ */
function toast(msg,type){
  var t=document.createElement('div');
  t.style.cssText='position:fixed;top:20px;right:20px;z-index:9999;display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:260px;box-shadow:0 10px 30px rgba(0,0,0,.25);animation:fadeUp .3s ease both;'+(type==='error'?'background:linear-gradient(135deg,#dc2626,#f04444);':'background:linear-gradient(135deg,#059669,#10b981);');
  t.innerHTML='<span>'+msg+'</span>';
  document.body.appendChild(t);
  setTimeout(function(){t.style.transition='opacity .3s,transform .3s';t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(function(){t.remove();},300);},3800);
}

/* —€—€ Avatar upload: preview + validate + loading —€—€ */
var avInput=document.getElementById('avatarInput');
var avForm=document.getElementById('avatarForm');
var avCam=document.getElementById('avCamBtn');
var avErr=document.getElementById('avErr');
var avImg=document.getElementById('adminAvatarImg');
var MAX=2*1024*1024, TYPES=['image/jpeg','image/png','image/webp'];

avInput.addEventListener('change',function(){
  avErr.style.display='none';
  var file=this.files&&this.files[0];
  if(!file)return;
  if(TYPES.indexOf(file.type)===-1){avErr.textContent='Use a JPG, PNG or WebP image.';avErr.style.display='block';this.value='';return;}
  if(file.size>MAX){avErr.textContent='Image must be under 2 MB.';avErr.style.display='block';this.value='';return;}
  var reader=new FileReader();
  reader.onload=function(e){
    if(avImg){avImg.src=e.target.result;}
    else{
      var wrap=avCam.parentElement;
      var img=document.createElement('img');img.id='adminAvatarImg';img.src=e.target.result;img.alt='Avatar';
      var letter=wrap.querySelector('.av-letter');if(letter)letter.remove();
      wrap.insertBefore(img,avCam);
      avImg=img;
    }
  };
  reader.readAsDataURL(file);
  avCam.classList.add('loading');
  avForm.submit();
});

/* —€—€ Password visibility toggles —€—€ */
document.querySelectorAll('.pw-toggle').forEach(function(btn){
  btn.addEventListener('click',function(){
    var inp=document.getElementById(btn.dataset.target);
    if(!inp)return;
    var show=inp.type==='password';
    inp.type=show?'text':'password';
    btn.querySelector('.ic-show').style.display=show?'none':'block';
    btn.querySelector('.ic-hide').style.display=show?'block':'none';
  });
});

/* —€—€ Password strength meter —€—€ */
var pwInp=document.getElementById('password');
var pwStrength=document.getElementById('pwStrength');
var pwFill=document.getElementById('pwFill');
var pwLbl=document.getElementById('pwLbl');
var levels=[{c:'var(--red)',t:'Weak'},{c:'var(--amber)',t:'Fair'},{c:'#3b82f6',t:'Good'},{c:'var(--green)',t:'Strong'}];
function scorePw(p){
  var s=0;
  if(p.length>=8)s++; if(p.length>=12)s++;
  if(/[a-z]/.test(p)&&/[A-Z]/.test(p))s++;
  if(/\d/.test(p))s++;
  if(/[^a-zA-Z0-9]/.test(p))s++;
  return Math.min(s,4);
}
pwInp.addEventListener('input',function(){
  var v=this.value;
  if(!v){pwStrength.style.display='none';return;}
  pwStrength.style.display='block';
  var sc=scorePw(v);
  pwFill.style.width=((sc/4)*100)+'%';
  pwFill.style.background=levels[sc-1].c;
  pwLbl.textContent='Strength: '+levels[sc-1].t;
  pwLbl.style.color=levels[sc-1].c;
});

/* —€—€ Delete modal —€—€ */
window.openDeleteModal=function(){document.getElementById('deleteModal').classList.add('open');document.getElementById('delete_password').focus();};
window.closeDeleteModal=function(){document.getElementById('deleteModal').classList.remove('open');};
document.getElementById('deleteModal').addEventListener('click',function(e){if(e.target===this)closeDeleteModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeDeleteModal();});

})();
</script>
@endpush
