@extends('layouts.admin')

@section('sidebar_fundraiser_levels', 'active')
@section('page_title', 'Add Level')
@section('page_subtitle', 'Create a new fundraiser progression level')

@section('topbar_left')
  <a href="{{ route('admin.fundraiser-levels.index') }}" class="back-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
    All Levels
  </a>
@endsection

@push('page_styles')
<style>
.back-btn{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 16px;background:var(--surface2);color:var(--text2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;font-weight:600;cursor:pointer;transition:all var(--ease);font-family:var(--font);text-decoration:none;}
.back-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.back-btn svg{width:13px;height:13px;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;max-width:760px;}
.card-head{display:flex;align-items:center;gap:10px;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:11.5px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-body{padding:22px;}
.field{margin-bottom:18px;}
.field:last-child{margin-bottom:0;}
.f-label{display:block;font-size:11.5px;font-weight:600;color:var(--text2);margin-bottom:7px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.f-label .req{color:var(--red);margin-left:2px;}
.f-input{width:100%;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color .2s,box-shadow .2s,background .2s;}
.f-input::placeholder{color:var(--text3);}
.f-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
.f-input.err{border-color:var(--red);}
.f-hint{font-size:11px;color:var(--text3);margin-top:5px;line-height:1.5;}
.f-error{font-size:11.5px;color:var(--red);margin-top:5px;font-family:var(--mono);}
.toggle-row{display:flex;align-items:center;justify-content:space-between;padding:4px 0;}
.toggle-lbl{font-size:13px;font-weight:600;color:var(--text);}
.toggle-sub{font-size:11.5px;color:var(--text3);margin-top:2px;}
.sw{position:relative;flex-shrink:0;}
.sw input{position:absolute;opacity:0;width:0;height:0;}
.sw label{display:block;width:46px;height:26px;border-radius:100px;background:var(--border2);cursor:pointer;position:relative;transition:background .2s;}
.sw label::after{content:'';position:absolute;width:20px;height:20px;border-radius:50%;background:#fff;top:3px;left:3px;transition:transform .25s cubic-bezier(.4,0,.2,1);box-shadow:0 1px 4px rgba(0,0,0,.2);}
.sw input:checked+label{background:var(--a);}
.sw input:checked+label::after{transform:translateX(20px);}
.submit-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 22px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:14px;font-weight:700;cursor:pointer;font-family:var(--mono);transition:opacity .2s,transform .15s;box-shadow:0 4px 18px rgba(37,99,235,.35);}
.submit-btn:hover{opacity:.88;transform:translateY(-1px);}
.submit-btn svg{width:15px;height:15px;}
.alert-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.22);color:#b91c1c;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;}
.alert-error svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}
[data-theme="dark"] .alert-error{color:#f87171;}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
@media(max-width:640px){.grid-2{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')
@if($errors->any())
<div class="alert-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  <div>
    <strong>Please fix the following:</strong>
    <ul style="margin-top:4px;padding-left:16px;">
      @foreach($errors->all() as $e)<li style="font-size:12px;margin-top:2px;">{{ $e }}</li>@endforeach
    </ul>
  </div>
</div>
@endif

<form method="POST" action="{{ route('admin.fundraiser-levels.store') }}">
  @csrf
  <div class="card">
    <div class="card-head">
      <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/></svg></div>
      <span class="card-head-title">Level Details</span>
    </div>
    <div class="card-body">
      <div class="grid-2">
        <div class="field">
          <label class="f-label" for="level_number">Level Number <span class="req">*</span></label>
          <input id="level_number" name="level_number" type="number" min="1" value="{{ old('level_number') }}" class="f-input {{ $errors->has('level_number')?'err':'' }}" placeholder="e.g. 1">
          @error('level_number')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="f-label" for="level_name">Level Name <span class="req">*</span></label>
          <input id="level_name" name="level_name" type="text" value="{{ old('level_name') }}" class="f-input {{ $errors->has('level_name')?'err':'' }}" placeholder="e.g. Trusted" required>
          @error('level_name')<p class="f-error">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="field">
        <label class="f-label" for="description">Description</label>
        <input id="description" name="description" type="text" value="{{ old('description') }}" class="f-input" placeholder="Short description of this tier">
        @error('description')<p class="f-error">{{ $message }}</p>@enderror
      </div>

      <div class="grid-2">
        <div class="field">
          <label class="f-label" for="max_goal_amount">Max Goal Amount (₹) <span class="req">*</span></label>
          <input id="max_goal_amount" name="max_goal_amount" type="number" step="0.01" min="0" value="{{ old('max_goal_amount') }}" class="f-input {{ $errors->has('max_goal_amount')?'err':'' }}" placeholder="e.g. 500000" required>
          @error('max_goal_amount')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="f-label" for="max_active_campaigns">Max Active Campaigns <span class="req">*</span></label>
          <input id="max_active_campaigns" name="max_active_campaigns" type="number" min="1" value="{{ old('max_active_campaigns',1) }}" class="f-input {{ $errors->has('max_active_campaigns')?'err':'' }}" required>
          @error('max_active_campaigns')<p class="f-error">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="grid-2">
        <div class="field">
          <label class="f-label" for="min_campaigns_completed">Min Campaigns Completed <span class="req">*</span></label>
          <input id="min_campaigns_completed" name="min_campaigns_completed" type="number" min="0" value="{{ old('min_campaigns_completed',0) }}" class="f-input {{ $errors->has('min_campaigns_completed')?'err':'' }}" required>
          @error('min_campaigns_completed')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="f-label" for="min_raised_percent">Min % Raised <span class="req">*</span></label>
          <input id="min_raised_percent" name="min_raised_percent" type="number" step="0.01" min="0" max="100" value="{{ old('min_raised_percent',0) }}" class="f-input {{ $errors->has('min_raised_percent')?'err':'' }}" required>
          @error('min_raised_percent')<p class="f-error">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="grid-2">
        <div class="field">
          <label class="f-label" for="kyc_requirement">KYC Requirement <span class="req">*</span></label>
          <select id="kyc_requirement" name="kyc_requirement" class="f-input {{ $errors->has('kyc_requirement')?'err':'' }}" required>
            <option value="none" {{ old('kyc_requirement')==='none'?'selected':'' }}>None</option>
            <option value="basic" {{ old('kyc_requirement')==='basic'?'selected':'' }}>Basic</option>
            <option value="full" {{ old('kyc_requirement')==='full'?'selected':'' }}>Full</option>
            <option value="org" {{ old('kyc_requirement')==='org'?'selected':'' }}>Organization</option>
          </select>
          @error('kyc_requirement')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="f-label" for="badge_color">Badge Color</label>
          <input id="badge_color" name="badge_color" type="text" value="{{ old('badge_color','#6366f1') }}" class="f-input" placeholder="#6366f1">
          <p class="f-hint">Hex color used for the level badge in the UI.</p>
          @error('badge_color')<p class="f-error">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="grid-2">
        <div class="field">
          <div class="toggle-row">
            <div>
              <div class="toggle-lbl">Requires Admin Approval</div>
              <div class="toggle-sub">Upgrades to this level need manual review</div>
            </div>
            <div class="sw">
              <input type="checkbox" name="requires_admin_approval" id="reqApproval" value="1" {{ old('requires_admin_approval')?'checked':'' }}>
              <label for="reqApproval"></label>
            </div>
          </div>
        </div>
        <div class="field">
          <div class="toggle-row">
            <div>
              <div class="toggle-lbl">Set as Default</div>
              <div class="toggle-sub">New fundraisers start at this level</div>
            </div>
            <div class="sw">
              <input type="checkbox" name="is_default" id="isDefault" value="1" {{ old('is_default')?'checked':'' }}>
              <label for="isDefault"></label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div style="margin-top:18px;display:flex;gap:10px;">
    <button type="submit" class="submit-btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Create Level
    </button>
    <a href="{{ route('admin.fundraiser-levels.index') }}" class="back-btn" style="text-decoration:none;">Cancel</a>
  </div>
</form>
@endsection
