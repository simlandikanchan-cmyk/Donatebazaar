@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/organizations.css')
@endpush


@section('page_title', 'Onboard NGO')
@section('page_subtitle', 'Manually register an organization')
@section('sidebar_organizations', 'active')

@section('content')

@if(session('success'))
<div class="alert-ok" id="flashAlert">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

<form class="form-wrap" method="POST" action="{{ route('admin.organizations.store') }}">
  @csrf

  <div class="main-card" style="margin-bottom:20px;">
    <div class="card-head">
      <div class="card-head-left">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div>
        <span class="card-head-title">Organization Details</span>
      </div>
    </div>
    <div style="padding:22px 24px;">
      <div class="form-grid">
        <div class="field">
          <label>Organization Type <span class="req">*</span></label>
          <select name="organization_type" required>
            <option value="">Select type…</option>
            <option value="NGO" {{ old('organization_type')=='NGO'?'selected':'' }}>NGO</option>
            <option value="Trust" {{ old('organization_type')=='Trust'?'selected':'' }}>Trust</option>
            <option value="Society" {{ old('organization_type')=='Society'?'selected':'' }}>Society</option>
            <option value="Section-8" {{ old('organization_type')=='Section-8'?'selected':'' }}>Section-8</option>
          </select>
        </div>
        <div class="field">
          <label>Organization Name <span class="req">*</span></label>
          <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Hope Foundation" required>
        </div>
        <div class="field">
          <label>Registration Number</label>
          <input type="text" name="registration_number" value="{{ old('registration_number') }}" placeholder="Optional">
        </div>
        <div class="field">
          <label>Founder Name</label>
          <input type="text" name="founder_name" value="{{ old('founder_name') }}" placeholder="Optional">
        </div>
        <div class="field full">
          <label>Address</label>
          <input type="text" name="address" value="{{ old('address') }}" placeholder="Street address">
        </div>
        <div class="field">
          <label>City</label>
          <input type="text" name="city" value="{{ old('city') }}" placeholder="City">
        </div>
        <div class="field">
          <label>State</label>
          <input type="text" name="state" value="{{ old('state') }}" placeholder="State">
        </div>
        <div class="field">
          <label>Pincode</label>
          <input type="text" name="pincode" value="{{ old('pincode') }}" placeholder="Pincode">
        </div>
        <div class="field">
          <label>Website</label>
          <input type="url" name="website" value="{{ old('website') }}" placeholder="https://">
        </div>
        <div class="field">
          <label>Budget Range</label>
          <input type="text" name="budget_range" value="{{ old('budget_range') }}" placeholder="e.g. 10L–50L">
        </div>
        <div class="field">
          <label>Donor Strength</label>
          <input type="text" name="donor_strength" value="{{ old('donor_strength') }}" placeholder="Optional">
        </div>
        <div class="field">
          <label>Employee Strength</label>
          <input type="text" name="employee_strength" value="{{ old('employee_strength') }}" placeholder="Optional">
        </div>
        <div class="field">
          <label>Campaign Timeline</label>
          <input type="text" name="campaign_timeline" value="{{ old('campaign_timeline') }}" placeholder="Optional">
        </div>
        <div class="field full">
          <div class="check-row">
            <input type="checkbox" name="has_crowdfunded" value="1" id="has_crowdfunded" {{ old('has_crowdfunded')?'checked':'' }}>
            <span>Has previously crowdfunded</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="main-card" style="margin-bottom:20px;">
    <div class="card-head">
      <div class="card-head-left">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
        <span class="card-head-title">Primary Contact</span>
      </div>
    </div>
    <div style="padding:22px 24px;">
      <div class="form-grid">
        <div class="field">
          <label>Contact Name <span class="req">*</span></label>
          <input type="text" name="contact_name" value="{{ old('contact_name') }}" placeholder="Full name" required>
        </div>
        <div class="field">
          <label>Contact Email <span class="req">*</span></label>
          <input type="email" name="contact_email" value="{{ old('contact_email') }}" placeholder="email@org.com" required>
        </div>
        <div class="field">
          <label>Contact Phone <span class="req">*</span></label>
          <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="+91…" required>
        </div>
        <div class="field">
          <label>Contact Role</label>
          <input type="text" name="contact_role" value="{{ old('contact_role') }}" placeholder="e.g. Director">
        </div>
      </div>
    </div>
  </div>

  <div class="main-card">
    <div class="card-head">
      <div class="card-head-left">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></div>
        <span class="card-head-title">Admin Notes</span>
      </div>
    </div>
    <div style="padding:22px 24px;">
      <div class="field full">
        <label>Internal Notes</label>
        <textarea name="admin_notes" placeholder="Optional note sent to the NGO in the approval email">{{ old('admin_notes') }}</textarea>
        <div class="form-note">On save the NGO is marked <strong>Approved</strong> and the contact receives an onboarding email.</div>
      </div>
    </div>
  </div>

  <div class="form-actions">
    <x-button variant="primary" type="submit">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Onboard NGO
    </x-button>
    <x-button variant="secondary" href="{{ route('admin.organizations.index') }}">Cancel</x-button>
  </div>
</form>

@endsection
