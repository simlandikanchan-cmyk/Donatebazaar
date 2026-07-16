@extends('layouts.admin')

@section('page_title', 'Onboard NGO')
@section('page_subtitle', 'Manually register an organization')
@section('sidebar_organizations', 'active')

@push('page_styles')
<style>
.form-wrap{max-width:820px;}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px 20px;}
.form-grid .full{grid-column:1 / -1;}
.field{display:flex;flex-direction:column;gap:6px;}
.field label{font-size:12px;font-weight:600;color:var(--text2);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.field .req{color:var(--red);}
.field input,.field select,.field textarea{height:40px;padding:0 13px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.field textarea{height:auto;padding:10px 13px;resize:vertical;min-height:90px;}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.field select{appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 11px center;background-size:14px;padding-right:34px;}
.check-row{display:flex;align-items:center;gap:10px;height:40px;}
.check-row input{width:18px;height:18px;accent-color:var(--a);}
.check-row span{font-size:13px;color:var(--text2);}
.form-actions{display:flex;align-items:center;gap:12px;margin-top:24px;}
.btn-primary{display:inline-flex;align-items:center;gap:7px;height:42px;padding:0 22px;background:var(--a);color:#fff;border:none;border-radius:var(--r-sm);font-size:13.5px;font-weight:600;font-family:var(--font);cursor:pointer;transition:filter var(--ease);}
.btn-primary:hover{filter:brightness(1.06);}
.btn-ghost{display:inline-flex;align-items:center;gap:7px;height:42px;padding:0 18px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:13.5px;font-weight:600;color:var(--text2);text-decoration:none;cursor:pointer;transition:all var(--ease);}
.btn-ghost:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.form-note{font-size:12px;color:var(--text3);margin-top:6px;}
.card-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.card-head-left{display:flex;align-items:center;gap:10px;}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.alert-ok{background:rgba(5,196,138,.08);border:1px solid rgba(5,196,138,.22);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease;}
.alert-ok svg{width:15px;height:15px;flex-shrink:0;}
@media(max-width:640px){.form-grid{grid-template-columns:1fr;}}
@media(max-width:480px){.main-card>div{padding:16px 14px}.card-head{padding:12px 14px}.form-grid{gap:14px}.field label{font-size:11px}.field input,.field select,.field textarea{font-size:12px;height:36px;padding:0 11px}.btn-primary,.btn-ghost{height:38px;font-size:12px;padding:0 16px}.form-actions{flex-direction:column;align-items:stretch;gap:10px}.form-actions .btn-primary,.form-actions .btn-ghost{justify-content:center}}
@media(max-width:380px){.main-card>div{padding:12px 10px}.card-head{padding:10px 12px}.card-head-title{font-size:10px}.field label{font-size:10px}.field input,.field select,.field textarea{font-size:11px;height:34px;padding:0 10px}.form-note{font-size:11px}.alert-ok{font-size:12px;padding:10px 12px}}
</style>
@endpush

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
    <button type="submit" class="btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Onboard NGO
    </button>
    <a href="{{ route('admin.organizations.index') }}" class="btn-ghost">Cancel</a>
  </div>
</form>

@endsection
