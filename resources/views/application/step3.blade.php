@extends('application.layout')

@php $currentStep = 3; @endphp

@section('step_content')
<form id="step-form" method="POST" action="{{ route('application.step3.post') }}">
  @csrf
  <div class="step-panel active">
    <div class="field-stack">

      <div class="section-divider">Tax &amp; legal certifications</div>

      <div class="toggle-set">
        <label class="toggle-card" onclick="toggleCert('section80g',this)">
          <input type="checkbox" name="has_80g" value="1" class="toggle-input" {{ old('has_80g', $application->has_80g ?? false) ? 'checked' : '' }}>
          <div class="toggle-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 14l2 2 4-4"/><rect x="3" y="3" width="18" height="18" rx="3"/></svg></div>
          <div class="toggle-text">
            <div class="toggle-title">80G Tax Exemption</div>
            <div class="toggle-desc">Donors can claim tax deductions — builds trust significantly</div>
          </div>
          <div class="toggle-track"><div class="toggle-thumb"></div></div>
        </label>
        <div class="cert-expand {{ old('has_80g', $application->has_80g ?? false) ? 'open' : '' }}" id="section80g">
          <div class="field-wrap">
            <label class="field-label">80G certificate number</label>
            <input type="text" name="80g_number" class="field-input" value="{{ old('80g_number', $application->{'80g_number'} ?? '') }}" placeholder="e.g. MH/80G/2021/0001">
          </div>
          <div class="field-wrap">
            <label class="field-label">Expiry date</label>
            <input type="date" name="80g_expiry" class="field-input" value="{{ old('80g_expiry', isset($application->{'80g_expiry'}) ? $application->{'80g_expiry'}->format('Y-m-d') : '') }}">
          </div>
        </div>
      </div>

      <div class="toggle-set">
        <label class="toggle-card" onclick="toggleCert('sectionFcra',this)">
          <input type="checkbox" name="has_fcra" value="1" class="toggle-input" {{ old('has_fcra', $application->has_fcra ?? false) ? 'checked' : '' }}>
          <div class="toggle-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg></div>
          <div class="toggle-text">
            <div class="toggle-title">FCRA Registration</div>
            <div class="toggle-desc">Foreign Contribution (Regulation) Act — enables international donations</div>
          </div>
          <div class="toggle-track"><div class="toggle-thumb"></div></div>
        </label>
        <div class="cert-expand {{ old('has_fcra', $application->has_fcra ?? false) ? 'open' : '' }}" id="sectionFcra" style="grid-template-columns:1fr">
          <div class="field-wrap">
            <label class="field-label">FCRA number</label>
            <input type="text" name="fcra_number" class="field-input" value="{{ old('fcra_number', $application->fcra_number ?? '') }}" placeholder="e.g. 083781234">
          </div>
        </div>
      </div>

      <div class="toggle-set">
        <label class="toggle-card" onclick="toggleCert('section12a',this)">
          <input type="checkbox" name="has_12a" value="1" class="toggle-input" {{ old('has_12a', $application->has_12a ?? false) ? 'checked' : '' }}>
          <div class="toggle-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg></div>
          <div class="toggle-text">
            <div class="toggle-title">12A Registration</div>
            <div class="toggle-desc">Income tax exemption for the organisation's own income</div>
          </div>
          <div class="toggle-track"><div class="toggle-thumb"></div></div>
        </label>
        <div class="cert-expand {{ old('has_12a', $application->has_12a ?? false) ? 'open' : '' }}" id="section12a" style="grid-template-columns:1fr">
          <div class="field-wrap">
            <label class="field-label">12A number</label>
            <input type="text" name="12a_number" class="field-input" value="{{ old('12a_number', $application->{'12a_number'} ?? '') }}" placeholder="e.g. MH/12A/2018/0002">
          </div>
        </div>
      </div>

      <label class="toggle-card">
        <input type="checkbox" name="has_csr_eligible" value="1" class="toggle-input" {{ old('has_csr_eligible', $application->has_csr_eligible ?? false) ? 'checked' : '' }}>
        <div class="toggle-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></div>
        <div class="toggle-text">
          <div class="toggle-title">CSR Eligible</div>
          <div class="toggle-desc">Eligible to receive Corporate Social Responsibility funding</div>
        </div>
        <div class="toggle-track"><div class="toggle-thumb"></div></div>
      </label>

      <div class="section-divider">Identification</div>

      <div class="field-grid">
        <div class="field-wrap">
          <label class="field-label">PAN number</label>
          <input type="text" name="pan_number" class="field-input" value="{{ old('pan_number', $application->pan_number ?? '') }}" placeholder="e.g. AAABC1234D" style="text-transform:uppercase">
        </div>
        <div class="field-wrap">
          <label class="field-label">NGO Darpan ID</label>
          <input type="text" name="darpan_id" class="field-input" value="{{ old('darpan_id', $application->darpan_id ?? '') }}" placeholder="e.g. MH/2018/0123456">
        </div>
      </div>

      <div class="section-divider">Bank account</div>

      <div class="field-grid">
        <div class="field-wrap">
          <label class="field-label">Bank name</label>
          <input type="text" name="bank_name" class="field-input" value="{{ old('bank_name', $application->bank_name ?? '') }}" placeholder="e.g. State Bank of India">
        </div>
        <div class="field-wrap">
          <label class="field-label">Account type</label>
          <select name="bank_account_type" class="field-input">
            <option value="">— Select —</option>
            <option {{ old('bank_account_type', $application->bank_account_type ?? '') == 'Savings' ? 'selected' : '' }}>Savings</option>
            <option {{ old('bank_account_type', $application->bank_account_type ?? '') == 'Current' ? 'selected' : '' }}>Current</option>
          </select>
        </div>
        <div class="field-wrap">
          <label class="field-label">Account number</label>
          <input type="text" name="bank_account_number" class="field-input" value="{{ old('bank_account_number', $application->bank_account_number ?? '') }}" placeholder="00112233445566">
        </div>
        <div class="field-wrap">
          <label class="field-label">IFSC code</label>
          <input type="text" name="bank_ifsc" class="field-input" value="{{ old('bank_ifsc', $application->bank_ifsc ?? '') }}" placeholder="SBIN0001234" style="text-transform:uppercase">
        </div>
      </div>

    </div>
  </div>
</form>
@endsection