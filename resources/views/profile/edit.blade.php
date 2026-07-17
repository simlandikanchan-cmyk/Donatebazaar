@extends('layouts.user')

@section('page_title', 'Edit Profile')
@section('page_subtitle', 'Update your Profile')

@section('content')
<div class="profile-grid">
  <div>
    {{-- Personal Info --}}
    <div class="card" style="margin-bottom:14px;">
      <div class="card-head">
        <div class="card-head-left">
          <div class="card-ico" style="background:var(--accent-lt);">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
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
          <div class="two-col" style="margin-bottom:14px;">
            <div class="field">
              <label>Full name</label>
              <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Your full name" required>
              @error('name')<div class="field-err">{{ $message }}</div>@enderror
            </div>
            <div class="field">
              <label>Phone number</label>
              <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+91 XXXXX XXXXX">
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
            <textarea name="bio" rows="3" placeholder="Tell people a little about yourself...">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')<div class="field-err">{{ $message }}</div>@enderror
          </div>
          <button type="submit" class="btn btn-primary save-btn">Save Changes</button>
        </form>
      </div>
    </div>

    {{-- Change Password --}}
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
              <button type="button" class="pw-eye" onclick="toggleEye('pw-cur',this)">
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
                <button type="button" class="pw-eye" onclick="toggleEye('pw-new',this)">
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
          <button type="submit" class="btn btn-primary save-btn ghost">Update Password</button>
        </form>
      </div>
    </div>

    {{-- Danger Zone --}}
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
        <p style="font-size:12.5px;color:var(--text2);margin-bottom:16px;line-height:1.7;">Once your account is deleted, all of its resources and data will be permanently removed. Please be absolutely certain before proceeding.</p>
        <form method="post" action="{{ route('profile.destroy') }}">
          @csrf @method('delete')
          <div class="field" style="margin-bottom:12px;">
            <label>Enter your password to confirm</label>
            <input type="password" name="password" placeholder="Your current password">
            @error('password', 'userDeletion')<div class="field-err">{{ $message }}</div>@enderror
          </div>
          <button type="submit" class="btn btn-red save-btn danger">Delete My Account</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('page_styles')
<style>
.profile-grid{display:grid;grid-template-columns:minmax(0,640px);justify-content:center;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;transition:box-shadow var(--ease);}
.card:hover{box-shadow:var(--sh-md);}
.card-head{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid var(--border);}
.card-head-left{display:flex;align-items:center;gap:9px;}
.card-ico{width:30px;height:30px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-ico svg{width:14px;height:14px;}
.card-ttl{font-family:var(--mono);font-size:13.5px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-sub{font-size:10px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.card-body{padding:15px 16px;}
.field{margin-bottom:14px;}
.field:last-child{margin-bottom:0;}
.field label{display:block;font-size:9.5px;font-weight:700;color:var(--text3);margin-bottom:5px;letter-spacing:.1em;text-transform:uppercase;font-family:var(--mono);}
.field input,.field textarea,.field select{width:100%;border:1px solid var(--border2);border-radius:var(--r-sm);padding:9px 12px;font-family:var(--font);font-size:13px;color:var(--text);background:var(--surface2);outline:none;resize:vertical;transition:border-color var(--ease),background var(--ease),box-shadow var(--ease);}
.field input:focus,.field textarea:focus,.field select:focus{border-color:var(--accent);background:var(--surface);box-shadow:0 0 0 3px var(--accent-glow);}
.field input::placeholder,.field textarea::placeholder{color:var(--text3);}
.field input[readonly]{opacity:.45;cursor:not-allowed;}
.field-err{font-size:10.5px;color:var(--red);margin-top:4px;font-family:var(--mono);font-weight:600;}
.field-hint{font-size:10.5px;color:var(--text3);margin-top:3px;}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.pw-wrap{position:relative;}
.pw-wrap input{padding-right:40px;}
.pw-eye{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text3);display:flex;padding:0;transition:color var(--ease);}
.pw-eye:hover{color:var(--text2);}
.pw-eye svg{width:14px;height:14px;}
.save-btn{width:100%;padding:10px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;border:none;border-radius:var(--r-sm);font-size:13px;font-weight:700;cursor:pointer;font-family:var(--font);transition:all var(--ease);box-shadow:0 4px 14px rgba(99,102,241,.28);}
.save-btn:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(99,102,241,.4);}
.save-btn.ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border2);box-shadow:none;}
.save-btn.ghost:hover{background:var(--surface3);transform:none;}
.save-btn.danger{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.18);box-shadow:none;}
.save-btn.danger:hover{background:var(--red);color:#fff;transform:none;}
@media(max-width:640px){
  .card-head{padding:12px 14px;}
  .card-body{padding:12px 14px;}
  .card-ttl{font-size:12.5px;}
  .two-col{grid-template-columns:1fr;}
}
@media(max-width:480px){
  .card-head{padding:10px 12px;}
  .card-body{padding:10px 12px;}
  .card-ico{width:26px;height:26px;border-radius:7px;}
  .card-ico svg{width:12px;height:12px;}
  .card-ttl{font-size:12px;}
  .card-sub{font-size:9px;}
  .field label{font-size:9px;}
  .field input,.field textarea{padding:8px 10px;font-size:12px;}
  .save-btn{padding:9px;font-size:12px;}
}
@media(max-width:380px){
  .card-head{padding:8px 10px;}
  .card-body{padding:8px 10px;}
  .profile-grid{grid-template-columns:1fr;}
}
</style>
@endpush

@push('page_scripts')
<script>
function toggleEye(id, btn) {
  var inp = document.getElementById(id);
  if (!inp) return;
  if (inp.type === 'password') {
    inp.type = 'text';
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
  } else {
    inp.type = 'password';
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
  }
}
</script>
@endpush
