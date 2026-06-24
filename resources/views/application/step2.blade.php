@extends('application.layout')

@php $currentStep = 2; @endphp

@section('step_content')
<form id="step-form" method="POST" action="{{ route('application.step2.post') }}">
  @csrf
  <div class="step-panel active">
    <div class="field-stack">

      <div class="field-grid">
        <div class="field-wrap">
          <label class="field-label">Full name <span class="req">*</span></label>
          <input type="text" name="contact_name" class="field-input" value="{{ old('contact_name', $application->contact_name ?? '') }}" placeholder="e.g. Priya Sharma">
          @error('contact_name')<p class="field-hint" style="color:var(--danger)">{{ $message }}</p>@enderror
        </div>
        <div class="field-wrap">
          <label class="field-label">Role / designation</label>
          <input type="text" name="contact_role" class="field-input" value="{{ old('contact_role', $application->contact_role ?? '') }}" placeholder="e.g. Executive Director">
        </div>
      </div>

      <div class="field-wrap">
        <label class="field-label">Email address <span class="req">*</span></label>
        <input type="email" name="contact_email" class="field-input" value="{{ old('contact_email', $application->contact_email ?? '') }}" placeholder="contact@yourorg.org">
        @error('contact_email')<p class="field-hint" style="color:var(--danger)">{{ $message }}</p>@enderror
      </div>

      <div class="field-grid">
        <div class="field-wrap">
          <label class="field-label">Phone <span class="req">*</span></label>
          <div class="prefix-wrap">
            <span class="prefix-icon">+91</span>
            <input type="tel" name="contact_phone" class="field-input" value="{{ old('contact_phone', $application->contact_phone ?? '') }}" placeholder="98765 43210">
          </div>
          @error('contact_phone')<p class="field-hint" style="color:var(--danger)">{{ $message }}</p>@enderror
        </div>
        <div class="field-wrap">
          <label class="field-label">WhatsApp</label>
          <div class="prefix-wrap">
            <span class="prefix-icon">+91</span>
            <input type="tel" name="contact_whatsapp" class="field-input" value="{{ old('contact_whatsapp', $application->contact_whatsapp ?? '') }}" placeholder="98765 43210">
          </div>
        </div>
      </div>

      <div class="field-wrap">
        <label class="field-label">LinkedIn profile</label>
        <input type="url" name="contact_linkedin" class="field-input" value="{{ old('contact_linkedin', $application->contact_linkedin ?? '') }}" placeholder="https://linkedin.com/in/…">
      </div>

      <div class="section-divider">Online presence</div>

      <div class="field-grid">
        <div class="field-wrap">
          <label class="field-label">Website</label>
          <input type="url" name="website" class="field-input" value="{{ old('website', $application->website ?? '') }}" placeholder="https://yourorg.org">
        </div>
        <div class="field-wrap">
          <label class="field-label">Facebook</label>
          <input type="url" name="social_facebook" class="field-input" value="{{ old('social_facebook', $application->social_facebook ?? '') }}" placeholder="https://facebook.com/…">
        </div>
        <div class="field-wrap">
          <label class="field-label">Instagram</label>
          <input type="url" name="social_instagram" class="field-input" value="{{ old('social_instagram', $application->social_instagram ?? '') }}" placeholder="https://instagram.com/…">
        </div>
        <div class="field-wrap">
          <label class="field-label">Twitter / X</label>
          <input type="url" name="social_twitter" class="field-input" value="{{ old('social_twitter', $application->social_twitter ?? '') }}" placeholder="https://twitter.com/…">
        </div>
      </div>

    </div>
  </div>
</form>
@endsection