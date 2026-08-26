@extends('layouts.user')

@section('page_title', 'Edit Profile')
@section('page_subtitle', 'Update your Profile')

@section('content')
<div class="profile-grid">
  <div>
    {{-- Personal Info --}}
    <div class="card mb-14">
      <div class="card-head">
        <div class="card-head-left">
          <div class="card-ico card-ico-dynamic" style="--ico-bg:var(--accent-lt);">
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
          <div class="two-col mb-14">
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
          <x-button variant="primary" type="submit">Save Changes</x-button>
        </form>
      </div>
    </div>

    {{-- Change Password --}}
    <div class="card mb-14">
      <div class="card-head">
        <div class="card-head-left">
          <div class="card-ico card-ico-dynamic" style="--ico-bg:var(--amber-lt);">
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
          <x-button variant="primary" type="submit" class="ghost">Update Password</x-button>
        </form>
      </div>
    </div>

    {{-- Danger Zone --}}
    <div class="card card-danger">
      <div class="card-head card-head-danger">
        <div class="card-head-left">
          <div class="card-ico card-ico-dynamic" style="--ico-bg:var(--red-lt);">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          </div>
          <div>
            <div class="card-ttl card-ttl-danger">Danger Zone</div>
            <div class="card-sub">Irreversible actions</div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <p class="font-12-5 text-secondary mb-16 line-height-1-7">Once your account is deleted, all of its resources and data will be permanently removed. Please be absolutely certain before proceeding.</p>
        <form method="post" action="{{ route('profile.destroy') }}">
          @csrf @method('delete')
          <div class="field mb-12">
            <label>Enter your password to confirm</label>
            <input type="password" name="password" placeholder="Your current password">
            @error('password', 'userDeletion')<div class="field-err">{{ $message }}</div>@enderror
          </div>
          <x-button variant="destructive" type="submit" class="danger">Delete My Account</x-button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('page_styles')
@vite('resources/css/user/pages/profile-show.css')
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
