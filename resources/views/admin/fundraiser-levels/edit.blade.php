@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush


@section('sidebar_fundraiser_levels', 'active')
@section('page_title', 'Edit Level')
@section('page_subtitle', 'Update fundraiser level requirements')

@section('topbar_left')
  <x-button variant="secondary" href="{{ route('admin.fundraiser-levels.index') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
    All Levels
  </x-button>
@endsection

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

<form method="POST" action="{{ route('admin.fundraiser-levels.update', $fundraiserLevel->id) }}">
  @csrf @method('PUT')
  <div class="card">
    <div class="card-head">
      <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/></svg></div>
      <span class="card-head-title">Level Details</span>
    </div>
    <div class="card-body">
      <div class="grid-2">
        <div class="field">
          <label class="f-label" for="level_number">Level Number <span class="req">*</span></label>
          <input id="level_number" name="level_number" type="number" min="1" value="{{ old('level_number',$fundraiserLevel->level_number) }}" class="f-input {{ $errors->has('level_number')?'err':'' }}" required>
          @error('level_number')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="f-label" for="level_name">Level Name <span class="req">*</span></label>
          <input id="level_name" name="level_name" type="text" value="{{ old('level_name',$fundraiserLevel->level_name) }}" class="f-input {{ $errors->has('level_name')?'err':'' }}" required>
          @error('level_name')<p class="f-error">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="field">
        <label class="f-label" for="description">Description</label>
        <input id="description" name="description" type="text" value="{{ old('description',$fundraiserLevel->description) }}" class="f-input">
        @error('description')<p class="f-error">{{ $message }}</p>@enderror
      </div>

      <div class="grid-2">
        <div class="field">
          <label class="f-label" for="max_goal_amount">Max Goal Amount (₹) <span class="req">*</span></label>
          <input id="max_goal_amount" name="max_goal_amount" type="number" step="0.01" min="0" value="{{ old('max_goal_amount',$fundraiserLevel->max_goal_amount) }}" class="f-input {{ $errors->has('max_goal_amount')?'err':'' }}" required>
          @error('max_goal_amount')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="f-label" for="max_active_campaigns">Max Active Campaigns <span class="req">*</span></label>
          <input id="max_active_campaigns" name="max_active_campaigns" type="number" min="1" value="{{ old('max_active_campaigns',$fundraiserLevel->max_active_campaigns) }}" class="f-input {{ $errors->has('max_active_campaigns')?'err':'' }}" required>
          @error('max_active_campaigns')<p class="f-error">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="grid-2">
        <div class="field">
          <label class="f-label" for="min_campaigns_completed">Min Campaigns Completed <span class="req">*</span></label>
          <input id="min_campaigns_completed" name="min_campaigns_completed" type="number" min="0" value="{{ old('min_campaigns_completed',$fundraiserLevel->min_campaigns_completed) }}" class="f-input {{ $errors->has('min_campaigns_completed')?'err':'' }}" required>
          @error('min_campaigns_completed')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="f-label" for="min_raised_percent">Min % Raised <span class="req">*</span></label>
          <input id="min_raised_percent" name="min_raised_percent" type="number" step="0.01" min="0" max="100" value="{{ old('min_raised_percent',$fundraiserLevel->min_raised_percent) }}" class="f-input {{ $errors->has('min_raised_percent')?'err':'' }}" required>
          @error('min_raised_percent')<p class="f-error">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="grid-2">
        <div class="field">
          <label class="f-label" for="kyc_requirement">KYC Requirement <span class="req">*</span></label>
          <select id="kyc_requirement" name="kyc_requirement" class="f-input {{ $errors->has('kyc_requirement')?'err':'' }}" required>
            @foreach(['none','basic','full','org'] as $opt)
              <option value="{{ $opt }}" {{ old('kyc_requirement',$fundraiserLevel->kyc_requirement)===$opt?'selected':'' }}>{{ ucfirst($opt) }}</option>
            @endforeach
          </select>
          @error('kyc_requirement')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="f-label" for="badge_color">Badge Color</label>
          <input id="badge_color" name="badge_color" type="text" value="{{ old('badge_color',$fundraiserLevel->badge_color) }}" class="f-input" placeholder="#6366f1">
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
              <input type="checkbox" name="requires_admin_approval" id="reqApproval" value="1" {{ old('requires_admin_approval',$fundraiserLevel->requires_admin_approval)?'checked':'' }}>
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
              <input type="checkbox" name="is_default" id="isDefault" value="1" {{ old('is_default',$fundraiserLevel->is_default)?'checked':'' }}>
              <label for="isDefault"></label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div style="margin-top:18px;display:flex;gap:10px;">
    <x-button variant="primary" type="submit">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
      Save Changes
    </x-button>
    <x-button variant="secondary" href="{{ route('admin.fundraiser-levels.index') }}">Cancel</x-button>
  </div>
</form>
@endsection
