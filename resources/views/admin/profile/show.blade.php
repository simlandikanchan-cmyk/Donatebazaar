@extends('layouts.admin')

@section('sidebar_profile', 'active')
@section('page_title', 'My Profile')
@section('page_subtitle', auth()->user()->email ?? '')

@push('page_styles')
<style>
/* ── LAYOUT ── */
.profile-grid{display:grid;grid-template-columns:300px 1fr;gap:24px;align-items:start;}
@media(max-width:768px){.profile-grid{grid-template-columns:1fr;}}

/* ── CARDS ── */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;}
.card+.card{margin-top:20px;}
.card-header{padding:16px 20px;border-bottom:1px solid var(--border);font-size:13px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:10px;}
.card-header svg{width:15px;height:15px;color:var(--a);flex-shrink:0;}
.card-body{padding:22px;}

/* ── FLASH BANNER ── */
.flash-banner{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:20px;animation:fadeUp .3s ease both;}
.flash-banner svg{width:16px;height:16px;flex-shrink:0;}
.flash-banner .flash-x{margin-left:auto;background:transparent;border:none;cursor:pointer;color:inherit;opacity:.6;font-size:15px;line-height:1;}
.flash-banner .flash-x:hover{opacity:1;}
.flash-ok{background:rgba(5,196,138,.09);border:1px solid rgba(5,196,138,.25);color:#065f46;}
.flash-err{background:rgba(240,68,68,.08);border:1px solid rgba(240,68,68,.22);color:#b91c1c;}

/* ── AVATAR CARD ── */
.av-card{text-align:center;padding:30px 20px 22px;background:linear-gradient(180deg,rgba(37,99,235,.05),transparent);}
.profile-av-wrap{position:relative;width:104px;height:104px;margin:0 auto 16px;}
.profile-av-wrap img{width:104px;height:104px;border-radius:50%;object-fit:cover;border:3px solid var(--border2);display:block;transition:opacity .2s;}
.profile-av-wrap .av-letter{width:104px;height:104px;border-radius:50%;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;display:flex;align-items:center;justify-content:center;font-size:38px;font-weight:700;font-family:var(--mono);margin:0 auto;border:3px solid var(--border2);}
.av-cam-btn{position:absolute;bottom:2px;right:2px;width:34px;height:34px;border-radius:50%;background:var(--a);color:#fff;border:2px solid var(--surface);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:transform .2s,background .2s;overflow:hidden;}
.av-cam-btn:hover{transform:scale(1.1);}
.av-cam-btn svg{width:14px;height:14px;}
.av-cam-btn.loading{background:var(--text3);pointer-events:none;}
.av-cam-btn.loading svg{display:none;}
.av-cam-btn.loading::after{content:'';width:15px;height:15px;border:2px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;}
.av-name{font-size:18px;font-weight:800;color:var(--text);margin-bottom:2px;line-height:1.2;}
.av-email{font-size:12px;color:var(--text3);margin-bottom:12px;word-break:break-all;}
.av-role{display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:100px;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);background:rgba(99,102,241,.1);color:var(--a);border:1px solid rgba(99,102,241,.2);}
.av-hint{font-size:10.5px;color:var(--text3);margin-top:14px;font-family:var(--mono);}
.av-err{font-size:11px;color:var(--red);margin-top:8px;display:none;}

/* ── ACCOUNT META ── */
.av-meta{margin-top:18px;padding-top:18px;border-top:1px solid var(--border);text-align:left;}
.av-meta-row{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:7px 0;font-size:12px;}
.av-meta-row+.av-meta-row{border-top:1px solid var(--border);}
.av-meta-lbl{color:var(--text3);font-family:var(--mono);}
.av-meta-val{font-weight:600;color:var(--text2);font-family:var(--mono);}
.verified-badge{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:100px;font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.2);font-family:var(--mono);}
.verified-badge.pending{background:rgba(245,158,11,.12);color:var(--amber);border-color:rgba(245,158,11,.25);}

/* ── FORMS ── */
.form-group{margin-bottom:16px;}
.form-group label{display:block;font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:6px;}
.form-group input{width:100%;height:40px;padding:0 13px;border:1px solid var(--border);border-radius:var(--r-sm);font-size:13px;color:var(--text);background:var(--surface);outline:none;transition:border-color .2s,box-shadow .2s;}
.form-group input:focus{border-color:var(--a);box-shadow:0 0 0 3px rgba(99,102,241,.12);}
.form-group input.err{border-color:var(--red);box-shadow:0 0 0 3px rgba(240,68,68,.1);}
.field-err{font-size:11px;color:var(--red);margin-top:5px;display:block;font-family:var(--mono);font-weight:600;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
@media(max-width:480px){.form-row{grid-template-columns:1fr;}}

/* ── PASSWORD WRAP + TOGGLE ── */
.pw-wrap{position:relative;}
.pw-wrap input{padding-right:42px;}
.pw-toggle{position:absolute;right:6px;top:50%;transform:translateY(-50%);width:28px;height:28px;border:none;background:transparent;color:var(--text3);cursor:pointer;display:flex;align-items:center;justify-content:center;border-radius:6px;transition:color .2s,background .2s;}
.pw-toggle:hover{color:var(--a);background:var(--surface2);}
.pw-toggle svg{width:15px;height:15px;}

/* ── STRENGTH METER ── */
.pw-strength{margin-top:9px;}
.pw-strength-bar{height:5px;border-radius:100px;background:var(--surface3);overflow:hidden;}
.pw-strength-fill{height:100%;border-radius:100px;width:0;transition:width .3s ease,background .3s ease;}
.pw-strength-lbl{font-size:10.5px;font-family:var(--mono);margin-top:5px;font-weight:600;color:var(--text3);}
.pw-tips{font-size:10.5px;color:var(--text3);margin-top:6px;font-family:var(--mono);}

/* ── EMAIL VERIFIED INLINE ── */
.email-line{display:flex;align-items:center;gap:8px;}
.email-line .verified-badge{margin-left:auto;}

/* ── BUTTONS ── */
.btn-save{display:inline-flex;align-items:center;gap:6px;height:40px;padding:0 20px;border-radius:var(--r-sm);font-size:12.5px;font-weight:700;border:none;cursor:pointer;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;transition:transform .2s,box-shadow .2s;box-shadow:0 4px 16px rgba(37,99,235,.3);}
.btn-save:hover{transform:translateY(-1px);box-shadow:0 6px 22px rgba(37,99,235,.4);}
.btn-save:active{transform:scale(.98);}
.btn-save:disabled{opacity:.6;cursor:not-allowed;transform:none;box-shadow:none;}
.btn-save svg{width:14px;height:14px;}

/* ── SESSIONS ── */
.sessions-card .card-header svg{color:var(--amber);}
.session-row{display:flex;align-items:center;gap:12px;padding:12px 20px;font-size:12.5px;}
.session-row+.session-row{border-top:1px solid var(--border);}
.session-icon{width:36px;height:36px;border-radius:10px;background:var(--surface2);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--text3);}
.session-icon svg{width:16px;height:16px;}
.session-info{flex:1;min-width:0;}
.session-device{font-weight:600;color:var(--text);}
.session-meta{font-size:11px;color:var(--text3);margin-top:2px;display:flex;gap:6px;flex-wrap:wrap;align-items:center;}
.sess-badge{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:100px;font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.2);font-family:var(--mono);}
.sess-revoke{flex-shrink:0;padding:6px 13px;border-radius:var(--r-sm);font-size:11px;font-weight:600;border:1px solid var(--border2);background:var(--surface2);color:var(--text3);cursor:pointer;transition:all var(--ease);text-decoration:none;}
.sess-revoke:hover{background:var(--red-lt);border-color:var(--red);color:var(--red);}
.sess-footer{padding:12px 20px;border-top:1px solid var(--border);display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.sess-revoke-all{display:inline-flex;align-items:center;gap:5px;padding:8px 14px;border-radius:var(--r-sm);font-size:11px;font-weight:600;border:1px solid var(--border2);background:var(--surface2);color:var(--text3);cursor:pointer;transition:all var(--ease);text-decoration:none;white-space:nowrap;}
.sess-revoke-all:hover{background:var(--amber-lt);border-color:var(--amber);color:var(--amber);}
@media(max-width:600px){
  .session-row{flex-wrap:wrap;gap:8px;padding:10px 14px;}
  .session-info{flex:1 1 100%;order:-1;}
  .sess-revoke{margin-left:auto;}
  .sess-footer{flex-direction:column;align-items:stretch;gap:8px;padding:12px 14px;}
  .sess-footer span{flex:none !important;text-align:center;}
  .sess-footer form{align-self:center;}
}

/* ── DANGER ZONE ── */
.danger-card{border-color:rgba(240,68,68,.3);}
.danger-card .card-header{color:var(--red);background:rgba(240,68,68,.04);}
.danger-warn{font-size:12.5px;color:var(--text3);line-height:1.6;margin-bottom:16px;}
.danger-warn strong{color:var(--red);}
.danger-btn{display:inline-flex;align-items:center;gap:6px;height:40px;padding:0 18px;border-radius:var(--r-sm);font-size:12.5px;font-weight:700;border:1px solid rgba(240,68,68,.3);background:var(--red-lt);color:var(--red);cursor:pointer;transition:all var(--ease);}
.danger-btn:hover{background:var(--red);color:#fff;border-color:var(--red);}
.danger-btn svg{width:14px;height:14px;}

/* ── MODAL ── */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);z-index:9000;display:none;align-items:center;justify-content:center;padding:20px;}
.modal-overlay.open{display:flex;}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:var(--r);box-shadow:var(--sh-lg);padding:28px;max-width:420px;width:100%;animation:modalIn .2s ease both;}
@keyframes modalIn{from{opacity:0;transform:scale(.95) translateY(10px)}to{opacity:1;transform:none}}
.modal-ico{width:48px;height:48px;border-radius:14px;background:var(--red-lt);border:1px solid rgba(240,68,68,.22);display:flex;align-items:center;justify-content:center;margin-bottom:16px;}
.modal-ico svg{width:22px;height:22px;color:var(--red);}
.modal-ttl{font-family:var(--mono);font-size:18px;font-weight:800;color:var(--text);margin-bottom:8px;letter-spacing:-.02em;}
.modal-desc{font-size:13.5px;color:var(--text2);line-height:1.6;margin-bottom:20px;}
.modal-desc strong{color:var(--text);font-weight:700;}
.modal-btns{display:flex;gap:10px;}
.modal-btns .btn{flex:1;justify-content:center;}
.btn-modal-cancel{display:inline-flex;align-items:center;gap:6px;height:40px;padding:0 18px;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;background:var(--surface2);color:var(--text2);border:1px solid var(--border2);cursor:pointer;transition:background .2s;}
.btn-modal-cancel:hover{background:var(--surface3);}
.btn-modal-delete{display:inline-flex;align-items:center;gap:6px;height:40px;padding:0 18px;border-radius:var(--r-sm);font-size:12.5px;font-weight:700;border:none;cursor:pointer;background:linear-gradient(135deg,#dc2626,#f04444);color:#fff;box-shadow:0 4px 18px rgba(240,68,68,.3);transition:transform .2s;}
.btn-modal-delete:hover{transform:translateY(-1px);box-shadow:0 6px 22px rgba(240,68,68,.4);}

@keyframes spin{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}
</style>
@endpush

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
        <button type="button" class="av-cam-btn" id="avCamBtn" onclick="document.getElementById('avatarInput').click()" title="Change photo">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
        </button>
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
          <button type="submit" class="btn-save">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Update Password
          </button>
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
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        Danger Zone
      </div>
      <div class="card-body">
        <div class="danger-warn">
          <strong>Irreversible action.</strong> Deleting your account will permanently remove all your data including campaigns, donation records, and personal information. This cannot be undone.
        </div>
        <button type="button" class="danger-btn" onclick="openDeleteModal()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete My Account
        </button>
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
        <button type="button" class="btn-modal-cancel" onclick="closeDeleteModal()">Cancel</button>
        <button type="submit" class="btn-modal-delete">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete Account
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

/* ── Toast (fallback for avatar client errors) ── */
function toast(msg,type){
  var t=document.createElement('div');
  t.style.cssText='position:fixed;top:20px;right:20px;z-index:9999;display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:260px;box-shadow:0 10px 30px rgba(0,0,0,.25);animation:fadeUp .3s ease both;'+(type==='error'?'background:linear-gradient(135deg,#dc2626,#f04444);':'background:linear-gradient(135deg,#059669,#10b981);');
  t.innerHTML='<span>'+msg+'</span>';
  document.body.appendChild(t);
  setTimeout(function(){t.style.transition='opacity .3s,transform .3s';t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(function(){t.remove();},300);},3800);
}

/* ── Avatar upload: preview + validate + loading ── */
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

/* ── Password visibility toggles ── */
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

/* ── Password strength meter ── */
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

/* ── Delete modal ── */
window.openDeleteModal=function(){document.getElementById('deleteModal').classList.add('open');document.getElementById('delete_password').focus();};
window.closeDeleteModal=function(){document.getElementById('deleteModal').classList.remove('open');};
document.getElementById('deleteModal').addEventListener('click',function(e){if(e.target===this)closeDeleteModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeDeleteModal();});

})();
</script>
@endpush
