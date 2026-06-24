@extends('application.layout')

@php $currentStep = 4; @endphp

@section('step_content')
<form id="step-form" method="POST" action="{{ route('application.submit') }}" enctype="multipart/form-data">
  @csrf
  <div class="step-panel active">
    <div class="field-stack">

      <div class="section-divider">Upload documents</div>
      <p class="field-hint" style="margin-top:-10px">PDF, JPG, or PNG — max 2 MB each.</p>

      <div style="display:flex;flex-direction:column;gap:10px">
        @php
          $docs = [
            'doc_registration_cert' => ['Registration Certificate',   'Trust deed / society certificate'],
            'doc_80g_certificate'   => ['80G Certificate',            'Tax exemption certificate'],
            'doc_pan_card'          => ['PAN Card copy',              'Organisation PAN'],
            'doc_annual_report'     => ['Annual report',              'Most recent financial year'],
            'doc_audited_statement' => ['Audited financial statement','CA-certified accounts'],
            'doc_fcra_certificate'  => ['FCRA Certificate',           'If applicable'],
          ];
        @endphp
        @foreach($docs as $field => [$label, $hint])
          <div class="doc-upload-item {{ !empty($application->$field) ? 'uploaded' : '' }}" id="docitem-{{ $field }}">
            <div class="doc-left">
              <div class="doc-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              </div>
              <div>
                <div class="doc-name">{{ $label }}</div>
                <div class="doc-size">{{ !empty($application->$field) ? 'Already uploaded — re-upload to replace' : $hint }}</div>
              </div>
            </div>
            <label class="doc-upload-btn" for="docfile-{{ $field }}">
              @if(!empty($application->$field))
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px"><path d="M20 6L9 17l-5-5"/></svg> Uploaded
              @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg> Upload
              @endif
            </label>
            <input type="file" id="docfile-{{ $field }}" name="{{ $field }}" accept=".pdf,.jpg,.jpeg,.png"
              style="display:none" onchange="markUploaded('{{ $field }}',this)">
          </div>
        @endforeach
      </div>

      <div class="section-divider">Review your application</div>

      <div class="review-section">
        <div class="review-section-head">
          <div class="review-section-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div>
          <span class="review-section-title">Organisation info</span>
        </div>
        <div class="review-row"><span class="review-label">Name</span><span class="review-value">{{ $application->name ?? '—' }}</span></div>
        <div class="review-row"><span class="review-label">Type</span><span class="review-value">{{ $application->organization_type ?? '—' }}</span></div>
        <div class="review-row"><span class="review-label">Location</span><span class="review-value">{{ implode(', ', array_filter([$application->city ?? null, $application->state ?? null])) ?: '—' }}</span></div>
      </div>

      <div class="review-section">
        <div class="review-section-head">
          <div class="review-section-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
          <span class="review-section-title">Contact person</span>
        </div>
        <div class="review-row"><span class="review-label">Name</span><span class="review-value">{{ $application->contact_name ?? '—' }}</span></div>
        <div class="review-row"><span class="review-label">Email</span><span class="review-value">{{ $application->contact_email ?? '—' }}</span></div>
        <div class="review-row"><span class="review-label">Phone</span><span class="review-value">{{ $application->contact_phone ? '+91 '.$application->contact_phone : '—' }}</span></div>
      </div>

      <div class="review-section">
        <div class="review-section-head">
          <div class="review-section-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 14l2 2 4-4"/><rect x="3" y="3" width="18" height="18" rx="3"/></svg></div>
          <span class="review-section-title">Certifications</span>
        </div>
        <div class="review-row">
          <span class="review-label">Certificates</span>
          <span class="review-value">
            @php
              $certs = array_filter([
                $application->has_80g ?? false  ? '80G'  : null,
                $application->has_fcra ?? false ? 'FCRA' : null,
                $application->has_12a ?? false  ? '12A'  : null,
                $application->has_csr_eligible ?? false ? 'CSR' : null,
              ]);
            @endphp
            @if(count($certs))
              @foreach($certs as $cert)
                <span class="review-chip review-chip-green">{{ $cert }}</span>
              @endforeach
            @else
              None selected
            @endif
          </span>
        </div>
      </div>

      <div class="submit-notice">
        <div class="submit-notice-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="submit-notice-text">By submitting, you confirm all information is accurate. Our team will verify and respond within <strong>5–7 business days</strong>.</div>
      </div>

    </div>
  </div>
</form>
@endsection