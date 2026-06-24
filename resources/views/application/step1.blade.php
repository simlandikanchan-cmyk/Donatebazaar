@extends('application.layout')

@php $currentStep = 1; @endphp

@section('step_content')
<form id="step-form" method="POST" action="{{ route('application.step1.post') }}">
  @csrf
  <div class="step-panel active" id="step-1">
    <div class="field-stack">

      <div class="field-grid">
        <div class="field-wrap">
          <label class="field-label">Organisation name <span class="req">*</span></label>
          <input type="text" name="name" class="field-input" value="{{ old('name', $application->name ?? '') }}" placeholder="e.g. Hope Foundation Trust">
          @error('name')<p class="field-hint" style="color:var(--danger)">{{ $message }}</p>@enderror
        </div>
        <div class="field-wrap">
          <label class="field-label">Organisation type <span class="req">*</span></label>
          <select name="organization_type" class="field-input">
            <option value="">— Select type —</option>
            @foreach(['NGO','Trust','Society','Section-8'] as $type)
              <option value="{{ $type }}" {{ old('organization_type', $application->organization_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
          </select>
          @error('organization_type')<p class="field-hint" style="color:var(--danger)">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="field-grid">
        <div class="field-wrap">
          <label class="field-label">Registration number</label>
          <input type="text" name="registration_number" class="field-input" value="{{ old('registration_number', $application->registration_number ?? '') }}" placeholder="e.g. MH/2010/0012345">
        </div>
        <div class="field-wrap">
          <label class="field-label">Registration date</label>
          <input type="date" name="registration_date" class="field-input" value="{{ old('registration_date', isset($application->registration_date) ? $application->registration_date->format('Y-m-d') : '') }}">
        </div>
      </div>

      <div class="section-divider">Address</div>

      <div class="field-wrap">
        <label class="field-label">Street address</label>
        <textarea name="address" class="field-input" rows="2" placeholder="Street address, area, landmark…">{{ old('address', $application->address ?? '') }}</textarea>
      </div>

      <div class="field-grid-3">
        <div class="field-wrap">
          <label class="field-label">City</label>
          <input type="text" name="city" class="field-input" value="{{ old('city', $application->city ?? '') }}" placeholder="Mumbai">
        </div>
        <div class="field-wrap">
          <label class="field-label">State</label>
          <input type="text" name="state" class="field-input" value="{{ old('state', $application->state ?? '') }}" placeholder="Maharashtra">
        </div>
        <div class="field-wrap">
          <label class="field-label">Pincode</label>
          <input type="text" name="pincode" class="field-input" maxlength="6" value="{{ old('pincode', $application->pincode ?? '') }}" placeholder="400001">
        </div>
      </div>

      <div class="section-divider">Founder &amp; Mission</div>

      <div class="field-grid">
        <div class="field-wrap">
          <label class="field-label">Founder name</label>
          <input type="text" name="founder_name" class="field-input" value="{{ old('founder_name', $application->founder_name ?? '') }}" placeholder="Full name">
        </div>
        <div class="field-wrap">
          <label class="field-label">Founder LinkedIn</label>
          <input type="url" name="founder_linkedin" class="field-input" value="{{ old('founder_linkedin', $application->founder_linkedin ?? '') }}" placeholder="https://linkedin.com/in/…">
        </div>
      </div>

      <div class="field-wrap">
        <label class="field-label">Mission statement</label>
        <textarea name="mission_statement" class="field-input" rows="3" placeholder="What drives your organisation?…">{{ old('mission_statement', $application->mission_statement ?? '') }}</textarea>
      </div>

      <div class="section-divider">Cause areas</div>
      <div class="cause-grid">
        @php $selectedCauses = old('causes', $application->causes ?? []); @endphp
        @foreach(['Education','Healthcare','Animal Welfare','Environment','Women Empowerment','Child Welfare','Disability','Elderly Care','Disaster Relief','Arts & Culture'] as $cause)
          <label class="cause-chip">
            <input type="checkbox" name="causes[]" value="{{ $cause }}" {{ in_array($cause, $selectedCauses) ? 'checked' : '' }}>
            <span class="chip-dot"></span>{{ $cause }}
          </label>
        @endforeach
      </div>

    </div>
  </div>
</form>
@endsection