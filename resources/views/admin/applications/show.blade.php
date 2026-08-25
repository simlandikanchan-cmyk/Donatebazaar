@push('page_css')
@vite('resources/css/admin/entries/applications.css')
@endpush

{{-- resources/views/admin/applications/show.blade.php --}}
@extends('layouts.admin')

@section('sidebar_applications', 'active')
@section('page_title', Str::limit($application->name, 28))
@section('page_subtitle', 'Reviewing NGO application details')

@section('content')
@php
$statusLabel = [
    'pending'      => ['Pending', 'b-pending'],
    'under_review' => ['Under Review', 'b-review'],
    'approved'     => ['Approved', 'b-approved'],
    'rejected'     => ['Rejected', 'b-rejected'],
];
$causesList = is_array($application->causes) ? $application->causes : (json_decode($application->causes, true) ?? []);
@endphp

{{-- FLASH --}}
@if(session('success'))
<div class="flash-ok">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

{{-- REJECTION BANNER (shown only when rejected) --}}
@if($application->status === 'rejected' && $application->rejection_reason)
<div class="rejection-banner">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  <div>
    <div class="rej-ttl">Application Rejected</div>
    <div class="rej-msg">{{ $application->rejection_reason }}</div>
  </div>
</div>
@endif

{{-- HERO CARD --}}
<div class="hero-card">
  <div class="hero-left">
    <div class="hero-av">{{ strtoupper(substr($application->name, 0, 1)) }}</div>
    <div>
      <div class="hero-title">{{ $application->name }}</div>
      <div class="hero-sub">Submitted NGO Application · #{{ $application->id }}</div>
      <div class="hero-meta">
        @if($application->city || $application->state)
        <div class="hero-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          {{ $application->city }}{{ $application->city && $application->state ? ', ' : '' }}{{ $application->state }}
        </div>
        @endif
        @if($application->submitted_at)
        <div class="hero-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          {{ $application->submitted_at->format('d M Y') }}
        </div>
        @endif
        @if($application->organization_type)
        <div class="hero-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          {{ $application->organization_type }}
        </div>
        @endif
        @if($application->submitted_at)
        <div class="hero-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          {{ $application->submitted_at->diffForHumans() }}
        </div>
        @endif
      </div>
    </div>
  </div>
  <div class="hero-right">
    @php $s = $statusLabel[$application->status] ?? ['Unknown', 'b-pending']; @endphp
    <span class="badge {{ $s[1] }}">{{ $s[0] }}</span>
    @if($application->reviewer)
    <div style="display:flex;align-items:center;gap:6px;font-size:10.5px;font-family:var(--mono);color:var(--text3);">
      <svg style="width:11px;height:11px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      {{ $application->reviewer->name }}
    </div>
    @endif
  </div>
</div>

{{-- ORGANIZATION INFO --}}
<div class="detail-card">
  <div class="sec-hdr" style="margin-bottom:18px;">
    <span class="sec-ttl">Organization Info</span>
  </div>
  <div class="detail-grid">
    <div class="info-box">
      <div class="info-label">Organization Name</div>
      <div class="info-value">{{ $application->name }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">Organization Type</div>
      <div class="info-value {{ !$application->organization_type ? 'empty' : '' }}">
        {{ $application->organization_type ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Registration Number</div>
      <div class="info-value {{ !$application->registration_number ? 'empty' : '' }}">
        {{ $application->registration_number ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Registration Date</div>
      <div class="info-value {{ !$application->registration_date ? 'empty' : '' }}">
        {{ $application->registration_date ? $application->registration_date->format('d M Y') : '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Founder Name</div>
      <div class="info-value {{ !$application->founder_name ? 'empty' : '' }}">
        {{ $application->founder_name ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Founder LinkedIn</div>
      <div class="info-value {{ !$application->founder_linkedin ? 'empty' : '' }}">
        @if($application->founder_linkedin)
          <a href="{{ $application->founder_linkedin }}" target="_blank" rel="noopener">
            {{ $application->founder_linkedin }}
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        @else
          —
        @endif
      </div>
    </div>
    <div class="info-box" style="grid-column:span 2;">
      <div class="info-label">Address</div>
      <div class="info-value {{ !$application->address ? 'empty' : '' }}">
        {{ $application->address ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Pincode</div>
      <div class="info-value {{ !$application->pincode ? 'empty' : '' }}">
        {{ $application->pincode ?? '—' }}
      </div>
    </div>
    @if(!empty($causesList))
    <div class="info-box" style="grid-column:1/-1;">
      <div class="info-label">Causes / Areas of Work</div>
      <div class="info-value" style="display:flex;flex-wrap:wrap;gap:3px;">
        @foreach($causesList as $cause)
        <span class="tag">{{ $cause }}</span>
        @endforeach
      </div>
    </div>
    @endif
    @if($application->mission_statement)
    <div class="info-box" style="grid-column:1/-1;">
      <div class="info-label">Mission Statement</div>
      <div class="info-value" style="font-weight:400;line-height:1.7;">{{ $application->mission_statement }}</div>
    </div>
    @endif
  </div>
</div>

{{-- CONTACT PERSON --}}
<div class="detail-card">
  <div class="sec-hdr" style="margin-bottom:18px;">
    <span class="sec-ttl">Contact Person</span>
  </div>
  <div class="detail-grid">
    <div class="info-box">
      <div class="info-label">Name</div>
      <div class="info-value {{ !$application->contact_name ? 'empty' : '' }}">
        {{ $application->contact_name ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Email</div>
      <div class="info-value {{ !$application->contact_email ? 'empty' : '' }}">
        {{ $application->contact_email ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Phone</div>
      <div class="info-value {{ !$application->contact_phone ? 'empty' : '' }}">
        {{ $application->contact_phone ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Role / Designation</div>
      <div class="info-value {{ !$application->contact_role ? 'empty' : '' }}">
        {{ $application->contact_role ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">LinkedIn</div>
      <div class="info-value {{ !$application->contact_linkedin ? 'empty' : '' }}">
        @if($application->contact_linkedin)
          <a href="{{ $application->contact_linkedin }}" target="_blank" rel="noopener">
            View Profile
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        @else
          —
        @endif
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">WhatsApp</div>
      <div class="info-value {{ !$application->contact_whatsapp ? 'empty' : '' }}">
        {{ $application->contact_whatsapp ?? '—' }}
      </div>
    </div>
  </div>
</div>

{{-- CERTIFICATIONS & LEGAL --}}
<div class="detail-card">
  <div class="sec-hdr" style="margin-bottom:18px;">
    <span class="sec-ttl">Certifications &amp; Legal</span>
  </div>
  <div class="detail-grid">
    <div class="info-box">
      <div class="info-label">80G Certificate</div>
      <div class="info-value">
        @if($application->has_80g)
          <span class="tag tag-green">Active</span>
        @else
          <span class="tag tag-red">Not Available</span>
        @endif
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">80G Number</div>
      <div class="info-value {{ !$application->{'80g_number'} ? 'empty' : '' }}">
        {{ $application->{'80g_number'} ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">80G Expiry</div>
      <div class="info-value {{ !$application->{'80g_expiry'} ? 'empty' : '' }}">
        {{ $application->{'80g_expiry'} ? $application->{'80g_expiry'}->format('d M Y') : '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">FCRA Registration</div>
      <div class="info-value">
        @if($application->has_fcra)
          <span class="tag tag-green">Active</span>
        @else
          <span class="tag tag-red">Not Available</span>
        @endif
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">FCRA Number</div>
      <div class="info-value {{ !$application->fcra_number ? 'empty' : '' }}">
        {{ $application->fcra_number ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">12A Registration</div>
      <div class="info-value">
        @if($application->has_12a)
          <span class="tag tag-green">Registered</span>
        @else
          <span class="tag tag-red">Not Available</span>
        @endif
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">12A Number</div>
      <div class="info-value {{ !$application->{'12a_number'} ? 'empty' : '' }}">
        {{ $application->{'12a_number'} ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">CSR Eligible</div>
      <div class="info-value">
        @if($application->has_csr_eligible)
          <span class="tag tag-green">Yes</span>
        @else
          <span class="tag tag-amber">No</span>
        @endif
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">PAN Number</div>
      <div class="info-value {{ !$application->pan_number ? 'empty' : '' }}">
        {{ $application->pan_number ? '********' . substr($application->pan_number, -4) : '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">DARPAN ID</div>
      <div class="info-value {{ !$application->darpan_id ? 'empty' : '' }}">
        {{ $application->darpan_id ?? '—' }}
      </div>
    </div>
  </div>
</div>

{{-- BANK DETAILS --}}
@if($application->bank_name || $application->bank_account_number || $application->bank_ifsc)
<div class="detail-card">
  <div class="sec-hdr" style="margin-bottom:18px;">
    <span class="sec-ttl">Bank Details</span>
  </div>
  <div class="detail-grid">
    <div class="info-box">
      <div class="info-label">Bank Name</div>
      <div class="info-value {{ !$application->bank_name ? 'empty' : '' }}">
        {{ $application->bank_name ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Account Number</div>
      <div class="info-value {{ !$application->bank_account_number ? 'empty' : '' }}">
        {{ $application->bank_account_number ? '••••' . substr($application->bank_account_number, -4) : '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">IFSC Code</div>
      <div class="info-value {{ !$application->bank_ifsc ? 'empty' : '' }}">
        {{ $application->bank_ifsc ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Account Type</div>
      <div class="info-value {{ !$application->bank_account_type ? 'empty' : '' }}">
        {{ $application->bank_account_type ?? '—' }}
      </div>
    </div>
  </div>
</div>
@endif

{{-- REFERENCES --}}
@if($application->reference_1_name || $application->reference_2_name)
<div class="detail-card">
  <div class="sec-hdr" style="margin-bottom:18px;">
    <span class="sec-ttl">References</span>
  </div>
  <div class="detail-grid">
    <div class="info-box">
      <div class="info-label">Reference 1 Name</div>
      <div class="info-value {{ !$application->reference_1_name ? 'empty' : '' }}">
        {{ $application->reference_1_name ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Reference 1 Organization</div>
      <div class="info-value {{ !$application->reference_1_org ? 'empty' : '' }}">
        {{ $application->reference_1_org ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Reference 1 Phone</div>
      <div class="info-value {{ !$application->reference_1_phone ? 'empty' : '' }}">
        {{ $application->reference_1_phone ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Reference 2 Name</div>
      <div class="info-value {{ !$application->reference_2_name ? 'empty' : '' }}">
        {{ $application->reference_2_name ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Reference 2 Organization</div>
      <div class="info-value {{ !$application->reference_2_org ? 'empty' : '' }}">
        {{ $application->reference_2_org ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Reference 2 Phone</div>
      <div class="info-value {{ !$application->reference_2_phone ? 'empty' : '' }}">
        {{ $application->reference_2_phone ?? '—' }}
      </div>
    </div>
  </div>
</div>
@endif

{{-- SOCIAL & PROFILE --}}
<div class="detail-card">
  <div class="sec-hdr" style="margin-bottom:18px;">
    <span class="sec-ttl">Profile &amp; Social</span>
  </div>
  <div class="detail-grid">
    <div class="info-box">
      <div class="info-label">Website</div>
      <div class="info-value {{ !$application->website ? 'empty' : '' }}">
        @if($application->website)
          <a href="{{ $application->website }}" target="_blank" rel="noopener">
            {{ $application->website }}
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        @else
          —
        @endif
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Facebook</div>
      <div class="info-value {{ !$application->social_facebook ? 'empty' : '' }}">
        @if($application->social_facebook)
          <a href="{{ $application->social_facebook }}" target="_blank" rel="noopener">
            View Page
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        @else
          —
        @endif
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Instagram</div>
      <div class="info-value {{ !$application->social_instagram ? 'empty' : '' }}">
        @if($application->social_instagram)
          <a href="{{ $application->social_instagram }}" target="_blank" rel="noopener">
            View Profile
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        @else
          —
        @endif
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Twitter / X</div>
      <div class="info-value {{ !$application->social_twitter ? 'empty' : '' }}">
        @if($application->social_twitter)
          <a href="{{ $application->social_twitter }}" target="_blank" rel="noopener">
            View Profile
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        @else
          —
        @endif
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">YouTube</div>
      <div class="info-value {{ !$application->social_youtube ? 'empty' : '' }}">
        @if($application->social_youtube)
          <a href="{{ $application->social_youtube }}" target="_blank" rel="noopener">
            View Channel
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        @else
          —
        @endif
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Budget Range</div>
      <div class="info-value {{ !$application->budget_range ? 'empty' : '' }}">
        {{ $application->budget_range ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Donor Strength</div>
      <div class="info-value {{ !$application->donor_strength ? 'empty' : '' }}">
        {{ $application->donor_strength ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Employee Strength</div>
      <div class="info-value {{ !$application->employee_strength ? 'empty' : '' }}">
        {{ $application->employee_strength ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Campaign Timeline</div>
      <div class="info-value {{ !$application->campaign_timeline ? 'empty' : '' }}">
        {{ $application->campaign_timeline ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Campaigns Completed</div>
      <div class="info-value {{ !$application->campaigns_completed ? 'empty' : '' }}">
        {{ $application->campaigns_completed ?? '0' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Previously Crowdfunded</div>
      <div class="info-value">
        @if($application->has_crowdfunded)
          <span class="tag tag-amber">Yes</span>
        @else
          <span class="tag tag-blue">No</span>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- DOCUMENTS --}}
@php
$docs = [
    'doc_registration_cert' => ['Registration Certificate', 'file-text'],
    'doc_80g_certificate'   => ['80G Certificate', 'file-text'],
    'doc_fcra_certificate'  => ['FCRA Certificate', 'file-text'],
    'doc_annual_report'     => ['Annual Report', 'file-text'],
    'doc_audited_statement' => ['Audited Statement', 'file-text'],
    'doc_pan_card'          => ['PAN Card', 'file-text'],
];
$hasDocs = false;
foreach ($docs as $field => $info) { if ($application->$field) { $hasDocs = true; break; } }
@endphp
@if($hasDocs)
<div class="detail-card">
  <div class="sec-hdr" style="margin-bottom:18px;">
    <span class="sec-ttl">Uploaded Documents</span>
  </div>
  <div class="detail-grid">
    @foreach($docs as $field => $info)
    @if($application->$field)
    @php
    $url = asset('storage/' . $application->$field);
    $ext = strtolower(pathinfo($application->$field, PATHINFO_EXTENSION));
    $isPdf = $ext === 'pdf';
    @endphp
    <div class="info-box">
      <div class="info-label">{{ $info[0] }}</div>
      <div style="margin-top:8px;">
        <a href="{{ $url }}" target="_blank" class="doc-link">
          @if($isPdf)
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v6h6"/></svg>
          @else
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          @endif
          View {{ $info[0] }}
        </a>
      </div>
    </div>
    @endif
    @endforeach
  </div>
</div>
@endif

{{-- TIMELINE --}}
<div class="detail-card">
  <div class="sec-hdr" style="margin-bottom:18px;">
    <span class="sec-ttl">Timeline</span>
  </div>
  <div class="timeline">
    <div class="tl-item">
      <div class="tl-dot tl-dot-done">
        <svg style="width:12px;height:12px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      </div>
      <div class="tl-body">
        <div class="tl-title">Application Submitted</div>
        <div class="tl-sub">{{ $application->submitted_at ? $application->submitted_at->format('d M Y · h:i A') : '—' }}</div>
      </div>
    </div>
    @if($application->reviewed_at)
    <div class="tl-item">
      <div class="tl-dot {{ $application->status === 'approved' ? 'tl-dot-done' : 'tl-dot-muted' }}">
        @if($application->status === 'approved')
        <svg style="width:12px;height:12px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        @else
        <svg style="width:12px;height:12px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        @endif
      </div>
      <div class="tl-body">
        <div class="tl-title">{{ $application->status === 'approved' ? 'Application Approved' : 'Application Reviewed' }}</div>
        <div class="tl-sub">{{ $application->reviewed_at->format('d M Y · h:i A') }} @if($application->reviewer) by {{ $application->reviewer->name }} @endif</div>
      </div>
    </div>
    @else
    <div class="tl-item">
      <div class="tl-dot tl-dot-pending">
        <svg style="width:12px;height:12px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div class="tl-body">
        <div class="tl-title">Awaiting Review</div>
        <div class="tl-sub">This application has not been reviewed yet.</div>
      </div>
    </div>
    @endif
  </div>
</div>

{{-- ADMIN NOTES --}}
<div class="detail-card">
  <div class="sec-hdr" style="margin-bottom:16px;">
    <span class="sec-ttl">Admin Notes</span>
  </div>
  @if($application->status === 'pending' || $application->status === 'under_review')
  <form method="POST" action="{{ route('admin.applications.approve', $application->id) }}" id="adminNotesForm">
    @csrf
    <input type="hidden" name="admin_notes" id="adminNotesInput">
    <textarea id="adminNotesTextarea" rows="3" class="modal-ta" placeholder="Add internal notes about this application…" style="margin-bottom:12px;">{{ $application->admin_notes }}</textarea>
    <button type="button" onclick="saveAdminNotes()">Save Notes</button>
  </form>
  @else
  <div class="admin-notes-text">{{ $application->admin_notes }}</div>
  @endif
</div>

{{-- ACTIONS CARD --}}
<div class="actions-card">
  <div class="actions-left">
    <strong>Application Actions</strong>
    @if($application->status === 'pending' || $application->status === 'under_review')
      Approve to onboard this NGO, or reject with a reason.
    @else
      This application has been <strong style="color:var(--text);text-transform:capitalize;">{{ str_replace('_', ' ', $application->status) }}</strong>.
      @if($application->reviewed_at)
      on {{ $application->reviewed_at->format('d M Y') }}
      @endif
    @endif
  </div>
  <div class="actions-right">
    <a href="{{ route('admin.applications') }}" class="btn btn-secondary c-btn c-btn-back">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to List
    </a>
    @if($application->status === 'pending' || $application->status === 'under_review')
      <form method="POST" action="{{ route('admin.applications.approve', $application->id) }}" data-loading-text="Approving…">
        @csrf
        <button type="submit" class="btn btn-green c-btn c-btn-approve">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Approve Application
        </button>
      </form>
      <button type="button" class="btn btn-red c-btn c-btn-reject" data-action="open-reject-modal" data-id="{{ $application->id }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        Reject Application
      </button>
    @endif
    <form method="POST" action="{{ route('admin.applications.destroy', $application->id) }}" onsubmit="return confirm('Delete this application permanently? This cannot be undone.');">
      @csrf @method('DELETE')
      <button type="submit" class="btn btn-red act-btn ab-delete">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
        Delete Application
      </button>
    </form>
  </div>
</div>

{{-- REJECT MODAL --}}
<div id="rejectOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-modal" data-target="#rejectOverlay">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="modal-head">
      <div class="modal-ico" style="background:var(--red-lt);">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div>
        <div class="modal-ttl">Reject Application</div>
        <div class="modal-sub">Reason will be shown to the applicant</div>
      </div>
    </div>
    <form id="rejectForm" method="POST">
      @csrf
      <div class="modal-lbl">Select or write a reason <span>*</span></div>
      <div class="chips">
        <button type="button" class="chip chip-red" data-r="Incomplete or missing documentation">Incomplete docs</button>
        <button type="button" class="chip chip-red" data-r="Organization does not meet eligibility criteria">Not eligible</button>
        <button type="button" class="chip chip-red" data-r="Fraudulent or misleading information provided">Fraudulent info</button>
        <button type="button" class="chip chip-red" data-r="Duplicate application already exists">Duplicate</button>
        <button type="button" class="chip chip-red" data-r="Violation of platform terms and conditions">Terms violation</button>
      </div>
      <textarea id="rejectReason" name="rejection_reason" rows="3" placeholder="Or type a custom reason…" class="modal-ta"></textarea>
      <p id="rejectErr" class="modal-err">⚠ Please provide a reason before rejecting.</p>
      <div class="modal-acts">
        <button type="button" data-action="close-modal" data-target="#rejectOverlay" class="btn btn-secondary modal-btn modal-cancel">Cancel</button>
        <button type="submit" id="rejectBtn" class="btn btn-red modal-btn modal-red">✕ Reject Application</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/applications-show.js')
@endpush
