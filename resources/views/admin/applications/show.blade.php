@push('page_css')
@vite('resources/css/admin/entries/applications.css')
@endpush

{{-- resources/views/admin/applications/show.blade.php --}}
@extends('layouts.admin')

@section('sidebar_applications', 'active')
@section('page_title', Str::limit($application->name, 28))
@section('page_subtitle', 'Reviewing NGO application details')

@push('page_styles')
<style>
/* ── Hero Card ── */
.hero-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:28px 30px;box-shadow:var(--sh);margin-bottom:20px;display:flex;align-items:flex-start;justify-content:space-between;gap:20px;animation:fadeUp .35s ease both;position:relative;overflow:hidden}
.hero-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--a),var(--a2));border-radius:var(--r) var(--r) 0 0}
.hero-left{display:flex;align-items:center;gap:18px;min-width:0}
.hero-av{width:58px;height:58px;border-radius:16px;flex-shrink:0;background:linear-gradient(135deg,var(--a),var(--a2));display:flex;align-items:center;justify-content:center;font-family:var(--mono);font-size:22px;font-weight:800;color:#fff;box-shadow:0 4px 18px rgba(37,99,235,.35)}
.hero-title{font-family:var(--mono);font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.03em;line-height:1.2}
.hero-sub{font-size:12px;color:var(--text3);margin-top:5px;font-family:var(--mono)}
.hero-meta{display:flex;align-items:center;gap:14px;margin-top:10px;flex-wrap:wrap}
.hero-meta-item{display:flex;align-items:center;gap:5px;font-size:11.5px;color:var(--text3);font-family:var(--mono)}
.hero-meta-item svg{width:12px;height:12px;flex-shrink:0}
.hero-right{display:flex;flex-direction:column;align-items:flex-end;gap:12px;flex-shrink:0}

/* ── Section Card ── */
.detail-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:24px;box-shadow:var(--sh);margin-bottom:20px;animation:fadeUp .4s ease both}
.detail-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.info-box{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:16px 18px;transition:border-color var(--ease),box-shadow var(--ease)}
.info-box:hover{border-color:rgba(37,99,235,.25);box-shadow:0 0 0 3px var(--a-lt)}
.info-label{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;margin-bottom:7px;font-family:var(--mono)}
.info-value{font-size:14px;font-weight:600;color:var(--text);line-height:1.5;word-break:break-word;font-family:var(--mono)}
.info-value.empty{color:var(--text3);font-weight:400}
.info-value a{color:var(--a);text-decoration:none;display:inline-flex;align-items:center;gap:5px}
.info-value a:hover{text-decoration:underline}
.info-value a svg{width:11px;height:11px;flex-shrink:0}

/* ── Tag chips ── */
.tag{display:inline-flex;padding:4px 11px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.15);margin:2px 3px}
.tag-green{background:rgba(5,196,138,.12);color:#047857;border-color:rgba(5,196,138,.22)}
[data-theme="dark"] .tag-green{color:#34d399}
.tag-amber{background:rgba(245,158,11,.12);color:#b45309;border-color:rgba(245,158,11,.22)}
[data-theme="dark"] .tag-amber{color:#fbbf24}
.tag-red{background:rgba(240,68,68,.12);color:#b91c1c;border-color:rgba(240,68,68,.22)}
[data-theme="dark"] .tag-red{color:#f87171}
.tag-blue{background:rgba(59,130,246,.12);color:#1d4ed8;border-color:rgba(59,130,246,.22)}
[data-theme="dark"] .tag-blue{color:#93c5fd}

/* ── Document link ── */
.doc-link{display:inline-flex;align-items:center;gap:7px;padding:8px 14px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);color:var(--text2);font-size:12px;font-weight:500;text-decoration:none;transition:all var(--ease);font-family:var(--mono)}
.doc-link:hover{background:var(--a-lt);color:var(--a);border-color:rgba(37,99,235,.25);transform:translateY(-1px)}
.doc-link svg{width:14px;height:14px;flex-shrink:0}
.doc-link .doc-size{font-size:10px;color:var(--text3);font-weight:400}
.doc-empty{font-size:12px;color:var(--text3);font-family:var(--mono);font-style:italic}

/* ── Badge override ── */
.b-review{background:rgba(59,130,246,.15);color:#1d4ed8;border:1px solid rgba(59,130,246,.25)}
[data-theme="dark"] .b-review{color:#93c5fd}

/* ── Flash ── */
.flash-ok{background:rgba(5,196,138,.09);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px}
[data-theme="dark"] .flash-ok{color:#34d399}
.flash-ok svg{width:15px;height:15px;flex-shrink:0}

/* ── Rejection banner ── */
.rejection-banner{background:rgba(240,68,68,.08);border:1px solid rgba(240,68,68,.25);border-radius:var(--r-sm);padding:16px 20px;margin-bottom:20px;display:flex;align-items:flex-start;gap:14px;animation:fadeUp .4s .1s ease both}
.rejection-banner svg{width:20px;height:20px;color:var(--red);flex-shrink:0;margin-top:2px}
.rejection-banner .rej-ttl{font-weight:700;font-size:13px;color:var(--red);font-family:var(--mono)}
.rejection-banner .rej-msg{font-size:13px;color:var(--text2);margin-top:4px;line-height:1.5;padding:8px 12px;background:var(--surface);border-radius:var(--r-xs);font-family:var(--mono)}

/* ── Admin notes ── */
.admin-notes-text{background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:14px 16px;font-size:13px;color:var(--text2);line-height:1.6;min-height:50px;font-family:var(--mono)}
.admin-notes-text:empty::before{content:'No admin notes yet.';color:var(--text3);font-style:italic}

/* ── Timeline ── */
.timeline{display:flex;flex-direction:column;gap:0}
.tl-item{display:flex;align-items:flex-start;gap:14px;padding:14px 0;position:relative}
.tl-item:not(:last-child)::after{content:'';position:absolute;left:11px;top:40px;bottom:-2px;width:2px;background:var(--border2);border-radius:2px}
.tl-dot{width:24px;height:24px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#fff}
.tl-dot-done{background:var(--green)}
.tl-dot-pending{background:var(--amber)}
.tl-dot-muted{background:var(--surface3);color:var(--text3)}
.tl-body{flex:1;min-width:0}
.tl-title{font-size:13px;font-weight:600;color:var(--text);font-family:var(--mono)}
.tl-sub{font-size:11.5px;color:var(--text3);margin-top:2px;font-family:var(--mono)}

/* ── Actions card ── */
.actions-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:22px 24px;box-shadow:var(--sh);display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;animation:fadeUp .4s .16s ease both}
.actions-left{font-size:12.5px;color:var(--text3);font-family:var(--mono)}
.actions-left strong{display:block;font-size:13px;font-weight:700;color:var(--text2);margin-bottom:2px}
.actions-right{display:flex;align-items:center;gap:10px;flex-wrap:wrap}

/* ── Responsive ── */
@media(max-width:1100px){.detail-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:680px){.detail-grid{grid-template-columns:1fr}.hero-card{flex-direction:column}.hero-right{flex-direction:row;align-items:center}.actions-card{flex-direction:column;align-items:flex-start}}
@media(max-width:600px){.hero-left{flex-direction:column;align-items:flex-start}.hero-av{width:48px;height:48px;font-size:18px}.hero-title{font-size:18px}.c-btn{width:100%}.actions-right{width:100%;flex-direction:column}}
@media(max-width:480px){.hero-card{padding:18px 16px}.detail-card{padding:16px}.hero-title{font-size:16px}.hero-meta{flex-direction:column;align-items:flex-start;gap:8px}.info-box{padding:12px 14px}.info-value{font-size:13px}.actions-card{padding:16px 18px}.modal{padding:20px}.rejection-banner{padding:12px 14px;flex-direction:column;gap:8px}}
@media(max-width:380px){.hero-card{padding:14px 12px}.hero-av{width:42px;height:42px;font-size:16px}.hero-title{font-size:15px}.hero-sub{font-size:11px}.detail-card{padding:12px}.sec-ttl{font-size:11px}.info-box{padding:10px 12px}.info-value{font-size:12px}.info-label{font-size:8px}.detail-grid{gap:8px}.c-btn{font-size:12px;padding:8px 14px;height:36px}.tl-item{padding:10px 0}.tl-title{font-size:12px}.tl-sub{font-size:10px}.actions-left strong{font-size:12px}}
</style>
@endpush

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
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <button type="button" class="btn btn-secondary c-btn c-btn-view" onclick="saveAdminNotes()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
        Save Notes
      </button>
    </div>
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
@vite('resources/js/admin/applications-show.js')
@endpush