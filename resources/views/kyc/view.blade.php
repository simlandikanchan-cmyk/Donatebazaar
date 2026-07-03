@extends('layouts.user')

@section('page_title', 'KYC Documents — ' . $campaign->title)
@section('page_subtitle', Str::limit($campaign->title, 45))

@push('page_styles')
<style>
.page-grid{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;}
.right-col{position:sticky;top:84px;display:flex;flex-direction:column;gap:16px;}
.card{background:var(--surface);border:1px solid var(--border2);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;margin-bottom:16px;}
.card-header{padding:15px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;}
.card-header-left{display:flex;align-items:center;gap:10px;}
.card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:15px;height:15px;}
.ic-indigo{background:rgba(99,102,241,0.12);color:var(--accent);}
.ic-green{background:rgba(16,185,129,0.12);color:var(--green);}
.ic-yellow{background:rgba(245,158,11,0.12);color:var(--yellow);}
.ic-blue{background:rgba(59,130,246,0.12);color:#3b82f6;}
.card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-0.01em;}
.card-sub{font-size:11px;color:var(--text3);margin-top:1px;}
.card-body{padding:20px;}
.kyc-banner{display:flex;align-items:center;gap:16px;padding:18px 20px;border-radius:var(--radius);margin-bottom:20px;border:1px solid transparent;animation:fadeUp 0.4s ease both;}
.kyc-banner-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:22px;}
.kyc-banner-text h3{font-size:15px;font-weight:700;margin-bottom:2px;}
.kyc-banner-text p{font-size:12px;opacity:0.85;line-height:1.5;}
.banner-pending{background:rgba(245,158,11,0.08);border-color:rgba(245,158,11,0.22);color:#92400e;}
.banner-approved{background:rgba(16,185,129,0.08);border-color:rgba(16,185,129,0.22);color:#064e3b;}
.banner-rejected{background:rgba(239,68,68,0.08);border-color:rgba(239,68,68,0.22);color:#7f1d1d;}
.banner-none{background:rgba(99,102,241,0.06);border-color:rgba(99,102,241,0.18);color:#3730a3;}
[data-theme="dark"] .banner-pending{color:#fcd34d;}
[data-theme="dark"] .banner-approved{color:#6ee7b7;}
[data-theme="dark"] .banner-rejected{color:#fca5a5;}
[data-theme="dark"] .banner-none{color:#a5b4fc;}
.banner-pending .kyc-banner-icon{background:rgba(245,158,11,0.15);}
.banner-approved .kyc-banner-icon{background:rgba(16,185,129,0.15);}
.banner-rejected .kyc-banner-icon{background:rgba(239,68,68,0.15);}
.banner-none .kyc-banner-icon{background:rgba(99,102,241,0.12);}
.doc-preview-wrap{position:relative;border-radius:var(--radius-sm);overflow:hidden;background:var(--surface2);border:1px solid var(--border2);}
.doc-img-inner{position:relative;}
.doc-img-inner img{width:100%;max-height:340px;object-fit:contain;display:block;padding:12px;cursor:zoom-in;transition:transform 0.3s ease;background:var(--surface2);}
.doc-img-inner:hover img{transform:scale(1.015);}
.doc-expand-btn{position:absolute;bottom:10px;right:10px;width:30px;height:30px;border-radius:8px;background:rgba(0,0,0,0.55);backdrop-filter:blur(6px);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;border:none;transition:background 0.2s;}
.doc-expand-btn:hover{background:rgba(0,0,0,0.78);}
.doc-expand-btn svg{width:13px;height:13px;}
.doc-pdf-inner{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;padding:40px 20px;text-align:center;}
.doc-pdf-icon{width:60px;height:60px;border-radius:14px;background:rgba(239,68,68,0.10);display:flex;align-items:center;justify-content:center;}
.doc-pdf-icon svg{width:30px;height:30px;color:#ef4444;}
.doc-pdf-name{font-size:12.5px;font-weight:600;color:var(--text2);}
.doc-pdf-sub{font-size:11px;color:var(--text3);}
.doc-pdf-open{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:var(--radius-sm);font-size:12px;font-weight:700;background:rgba(239,68,68,0.10);color:#dc2626;border:1px solid rgba(239,68,68,0.25);text-decoration:none;transition:background 0.2s;}
.doc-pdf-open:hover{background:rgba(239,68,68,0.18);}
.doc-pdf-open svg{width:12px;height:12px;}
[data-theme="dark"] .doc-pdf-open{color:#f87171;}
.doc-no-file{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;padding:40px 20px;border-radius:var(--radius-sm);background:var(--surface2);border:1px dashed var(--border2);text-align:center;}
.doc-no-file svg{width:32px;height:32px;color:var(--text3);opacity:0.3;}
.doc-no-file span{font-size:12px;color:var(--text3);}
.kyc-docs-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;}
@media(max-width:640px){.kyc-docs-grid{grid-template-columns:1fr;}}
.kyc-doc-tile{border:1px solid var(--border2);border-radius:var(--radius-sm);overflow:hidden;background:var(--surface2);}
.kyc-doc-tile-header{display:flex;align-items:center;justify-content:space-between;padding:9px 12px;border-bottom:1px solid var(--border);background:var(--surface);}
.kyc-doc-tile-label{display:flex;align-items:center;gap:6px;font-size:11px;font-weight:700;color:var(--text2);font-family:var(--mono);}
.kyc-doc-tile-label svg{width:12px;height:12px;}
.kyc-doc-tile-img{width:100%;height:160px;object-fit:cover;display:block;cursor:zoom-in;transition:opacity .2s;}
.kyc-doc-tile-img:hover{opacity:.85;}
.kyc-doc-tile-pdf{width:100%;height:160px;border:none;display:block;}
.kyc-doc-tile-missing{height:90px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;}
.kyc-doc-tile-missing svg{width:22px;height:22px;color:var(--text3);opacity:.3;}
.kyc-doc-tile-missing span{font-size:11px;color:var(--text3);}
.kyc-doc-btn{display:inline-flex;align-items:center;gap:4px;font-size:10.5px;font-weight:600;padding:3px 9px;border-radius:6px;font-family:var(--font);transition:background var(--ease);border:none;cursor:pointer;text-decoration:none;}
.kyc-doc-btn-view{background:rgba(99,102,241,.10);color:var(--accent);border:1px solid rgba(99,102,241,.20);}
.kyc-doc-btn-view:hover{background:rgba(99,102,241,.20);}
.kyc-doc-btn-dl{background:rgba(16,185,129,.10);color:var(--green);border:1px solid rgba(16,185,129,.20);}
.kyc-doc-btn-dl:hover{background:rgba(16,185,129,.20);}
.kyc-doc-btn svg{width:10px;height:10px;flex-shrink:0;}
.kyc-selfie-wrap{display:flex;gap:12px;align-items:flex-start;margin-bottom:16px;}
.kyc-selfie-img{width:120px;height:120px;object-fit:cover;border-radius:var(--radius-sm);border:2px solid var(--border2);flex-shrink:0;cursor:zoom-in;}
.kyc-selfie-missing{width:120px;height:120px;background:var(--surface2);border:1px dashed var(--border2);border-radius:var(--radius-sm);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;flex-shrink:0;}
.kyc-selfie-missing svg{width:24px;height:24px;color:var(--text3);opacity:.3;}
.kyc-selfie-missing span{font-size:10px;color:var(--text3);}
.kyc-selfie-info{flex:1;}
.kyc-selfie-title{font-size:12px;font-weight:700;color:var(--text);font-family:var(--mono);margin-bottom:6px;}
.kyc-selfie-sub{font-size:11px;color:var(--text3);line-height:1.6;}
.bank-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
@media(max-width:640px){.bank-grid{grid-template-columns:1fr;}}
.bank-field{background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:10px 12px;}
.bank-field-lbl{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;font-family:var(--mono);margin-bottom:4px;}
.bank-field-val{font-size:13px;font-weight:600;color:var(--text);font-family:var(--mono);}
.bank-field-val.empty{color:var(--text3);font-style:italic;font-family:var(--font);font-weight:400;font-size:12px;}
.info-rows{display:flex;flex-direction:column;gap:0;}
.info-row{display:flex;justify-content:space-between;align-items:center;font-size:12px;padding:10px 0;}
.info-row+.info-row{border-top:1px solid var(--border);}
.info-row-lbl{color:var(--text3);font-family:var(--mono);letter-spacing:0.04em;font-size:11px;}
.kyc-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-size:10.5px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;font-family:var(--mono);}
.kyc-chip-pending{background:rgba(245,158,11,0.12);color:#f59e0b;border:1px solid rgba(245,158,11,0.3);}
.kyc-chip-approved{background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.3);}
.kyc-chip-rejected{background:rgba(239,68,68,0.12);color:#ef4444;border:1px solid rgba(239,68,68,0.3);}
.status-chip{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:100px;font-size:11px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;font-family:var(--mono);}
.status-chip .dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;}
.chip-active{background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.25);}
.chip-paused{background:rgba(99,102,241,0.12);color:#818cf8;border:1px solid rgba(99,102,241,0.25);}
.chip-pending{background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.3);}
.chip-rejected{background:rgba(239,68,68,0.12);color:#ef4444;border:1px solid rgba(239,68,68,0.25);}
.section-label{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--mono);margin-bottom:10px;}
.section-divider{border-top:1px solid var(--border);margin:16px 0;}
.timeline{display:flex;flex-direction:column;}
.tl-item{display:flex;gap:12px;padding-bottom:18px;position:relative;}
.tl-item:last-child{padding-bottom:0;}
.tl-item:not(:last-child)::before{content:'';position:absolute;left:13px;top:28px;bottom:0;width:2px;background:var(--border2);}
.tl-dot{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:12px;border:2px solid;}
.tl-done{background:rgba(16,185,129,0.12);border-color:#10b981;color:#10b981;}
.tl-active{background:rgba(245,158,11,0.12);border-color:#f59e0b;color:#f59e0b;}
.tl-wait{background:var(--surface2);border-color:var(--border2);color:var(--text3);}
.tl-fail{background:rgba(239,68,68,0.12);border-color:#ef4444;color:#ef4444;}
.tl-label{font-size:12px;font-weight:700;color:var(--text);}
.tl-desc{font-size:11px;color:var(--text3);margin-top:2px;}
.action-buttons{display:flex;flex-direction:column;gap:8px;}
.lightbox{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.88);z-index:9998;align-items:center;justify-content:center;backdrop-filter:blur(4px);}
.lightbox.open{display:flex;animation:fadeIn 0.2s ease;}
.lightbox img{max-width:90vw;max-height:90vh;border-radius:10px;box-shadow:0 20px 80px rgba(0,0,0,0.6);object-fit:contain;}
.lightbox-close{position:absolute;top:20px;right:20px;width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,0.12);color:#fff;border:none;cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;}
.lightbox-close:hover{background:rgba(255,255,255,0.22);}
@keyframes toastIn{from{opacity:0;transform:translateX(20px) scale(0.96);}to{opacity:1;transform:translateX(0) scale(1);}}
@keyframes fadeUp{from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);}}
@keyframes fadeIn{from{opacity:0;}to{opacity:1;}}
@media(max-width:960px){.page-grid{grid-template-columns:1fr;}.right-col{position:static;}}
</style>
@endpush

@section('content')
{{-- Lightbox for image preview --}}
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()">✕</button>
    <img id="lightboxImg" src="" alt="Document Preview">
</div>

@php
    if ($campaign->campaign_state === 'paused') {
        $chipClass = 'chip-paused';
        $chipLabel = 'Paused';
    } elseif ($campaign->campaign_state === 'rejected') {
        $chipClass = 'chip-rejected';
        $chipLabel = 'Rejected';
    } elseif ($campaign->campaign_state === 'pending') {
        $chipClass = 'chip-pending';
        $chipLabel = 'Pending';
    } elseif (in_array($campaign->campaign_state, ['approved', 'live', 'active'])) {
        $chipClass = 'chip-active';
        $chipLabel = 'Active';
    } else {
        $chipClass = 'chip-pending';
        $chipLabel = ucfirst($campaign->campaign_state ?? 'Draft');
    }

    $kycStatus  = $kyc?->status ?? 'none';
    $docUrl     = $kyc?->document_url ?? null;
    $docExt     = $docUrl ? strtolower(pathinfo($docUrl, PATHINFO_EXTENSION)) : null;
    $isPdf      = $docExt === 'pdf';

    $docServeUrl = $kyc ? route('kyc.document', $campaign->id) : null;

    $kycAadhaarUrl = $kyc?->aadhaar_url  ? asset('storage/'.$kyc->aadhaar_url)  : null;
    $kycPanUrl     = $kyc?->pan_url      ? asset('storage/'.$kyc->pan_url)      : null;
    $kycSelfieUrl  = $kyc?->selfie_url   ? asset('storage/'.$kyc->selfie_url)   : null;

    $isImg = fn($url) => $url && preg_match('/\.(jpe?g|png|webp|gif)$/i', $url);
    $isPdfUrl = fn($url) => $url && str_ends_with(strtolower($url), '.pdf');

    $bankName   = $kyc?->kyc_bank_name      ?? null;
    $bankAcc    = $kyc?->kyc_account_number ?? null;
    $bankIfsc   = $kyc?->kyc_ifsc           ?? null;
    $bankHolder = $kyc?->kyc_account_name   ?? null;

    $hasMultiDocs = $kycAadhaarUrl || $kycPanUrl || $kycSelfieUrl;
@endphp

{{-- ══ STATUS BANNER ══ --}}
@if($kycStatus === 'none')
    <div class="kyc-banner banner-none">
        <div class="kyc-banner-icon">📋</div>
        <div class="kyc-banner-text">
            <h3>No KYC Documents Submitted</h3>
            <p>Upload a government-issued ID to get your campaign approved by our team.</p>
        </div>
    </div>
@elseif($kycStatus === 'pending')
    <div class="kyc-banner banner-pending">
        <div class="kyc-banner-icon">⏳</div>
        <div class="kyc-banner-text">
            <h3>Documents Under Review</h3>
            <p>Submitted {{ $kyc->created_at->format('d M Y, h:i A') }}. Our team will verify within 24 hours.</p>
        </div>
    </div>
@elseif($kycStatus === 'approved')
    <div class="kyc-banner banner-approved">
        <div class="kyc-banner-icon">✅</div>
        <div class="kyc-banner-text">
            <h3>Identity Verified</h3>
            <p>KYC approved{{ $kyc->verified_at ? ' on ' . \Carbon\Carbon::parse($kyc->verified_at)->format('d M Y') : '' }}. Your campaign is eligible for approval.</p>
        </div>
    </div>
@elseif($kycStatus === 'rejected')
    <div class="kyc-banner banner-rejected">
        <div class="kyc-banner-icon">❌</div>
        <div class="kyc-banner-text">
            <h3>KYC Rejected</h3>
            <p>{{ $kyc->rejection_reason ?? 'Your documents were rejected. Please re-upload valid documents.' }}</p>
        </div>
    </div>
@endif

<div class="page-grid">

    {{-- ════ LEFT COLUMN ════ --}}
    <div>

        @if($kyc)

        {{-- Document Details --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Document Details</div>
                        <div class="card-sub">Submitted identity information</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="info-rows">
                    <div class="info-row">
                        <span class="info-row-lbl">DOCUMENT TYPE</span>
                        @if($kyc->document_type)
                        <span style="font-size:12px;font-weight:700;color:var(--text);text-transform:capitalize;">
                            {{ str_replace('_', ' ', $kyc->document_type) }}
                        </span>
                        @else
                        <span style="font-size:12px;color:var(--text3);font-style:italic;">Not provided</span>
                        @endif
                    </div>
                    <div class="info-row">
                        <span class="info-row-lbl">DOCUMENT NUMBER</span>
                        @if($kyc->document_number)
                        <span style="font-size:12px;font-weight:700;color:var(--text);font-family:var(--mono);">
                            {{ $kyc->document_number }}
                        </span>
                        @else
                        <span style="font-size:12px;color:var(--text3);font-style:italic;">Not provided</span>
                        @endif
                    </div>
                    <div class="info-row">
                        <span class="info-row-lbl">SUBMITTED</span>
                        <span style="font-size:12px;color:var(--text2);">
                            {{ $kyc->created_at->format('d M Y, h:i A') }}
                        </span>
                    </div>
                    @if($kyc->verified_at)
                    <div class="info-row">
                        <span class="info-row-lbl">VERIFIED ON</span>
                        <span style="font-size:12px;color:#10b981;font-weight:600;">
                            {{ \Carbon\Carbon::parse($kyc->verified_at)->format('d M Y, h:i A') }}
                        </span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-row-lbl">STATUS</span>
                        <span class="kyc-chip kyc-chip-{{ $kycStatus }}">
                            @if($kycStatus === 'pending') Pending
                            @elseif($kycStatus === 'approved') ✓ Verified
                            @else ✗ Rejected
                            @endif
                        </span>
                    </div>
                    @if($kycStatus === 'rejected' && $kyc->rejection_reason)
                    <div class="info-row" style="flex-direction:column;align-items:flex-start;gap:4px;">
                        <span class="info-row-lbl">REJECTION REASON</span>
                        <span style="font-size:12px;color:#ef4444;line-height:1.5;padding-top:2px;">
                            {{ $kyc->rejection_reason }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ══ NEW: Identity Documents (Aadhaar + PAN) ══ --}}
        @if($hasMultiDocs)
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h6"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Identity Documents</div>
                        <div class="card-sub">Aadhaar, PAN &amp; selfie verification</div>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="section-label">ID Documents</div>
                <div class="kyc-docs-grid">

                    {{-- Aadhaar --}}
                    <div class="kyc-doc-tile">
                        <div class="kyc-doc-tile-header">
                            <span class="kyc-doc-tile-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h6"/></svg>
                                Aadhaar Card
                            </span>
                            @if($kycAadhaarUrl)
                            <div style="display:flex;gap:5px;">
                                <a href="{{ $kycAadhaarUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Open
                                </a>
                                <a href="{{ $kycAadhaarUrl }}" download class="kyc-doc-btn kyc-doc-btn-dl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    DL
                                </a>
                            </div>
                            @endif
                        </div>
                        @if($kycAadhaarUrl)
                            @if($isImg($kycAadhaarUrl))
                                <a href="{{ $kycAadhaarUrl }}" target="_blank" onclick="event.preventDefault();openLightbox('{{ $kycAadhaarUrl }}')">
                                    <img src="{{ $kycAadhaarUrl }}" alt="Aadhaar" loading="lazy" class="kyc-doc-tile-img">
                                </a>
                            @elseif($isPdfUrl($kycAadhaarUrl))
                                <iframe src="{{ $kycAadhaarUrl }}" class="kyc-doc-tile-pdf" title="Aadhaar PDF"></iframe>
                            @else
                                <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg><span>Preview unavailable</span></div>
                            @endif
                        @else
                            <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span>Not uploaded</span></div>
                        @endif
                    </div>

                    {{-- PAN --}}
                    <div class="kyc-doc-tile">
                        <div class="kyc-doc-tile-header">
                            <span class="kyc-doc-tile-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h4"/></svg>
                                PAN Card
                            </span>
                            @if($kycPanUrl)
                            <div style="display:flex;gap:5px;">
                                <a href="{{ $kycPanUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Open
                                </a>
                                <a href="{{ $kycPanUrl }}" download class="kyc-doc-btn kyc-doc-btn-dl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    DL
                                </a>
                            </div>
                            @endif
                        </div>
                        @if($kycPanUrl)
                            @if($isImg($kycPanUrl))
                                <a href="{{ $kycPanUrl }}" target="_blank" onclick="event.preventDefault();openLightbox('{{ $kycPanUrl }}')">
                                    <img src="{{ $kycPanUrl }}" alt="PAN" loading="lazy" class="kyc-doc-tile-img">
                                </a>
                            @elseif($isPdfUrl($kycPanUrl))
                                <iframe src="{{ $kycPanUrl }}" class="kyc-doc-tile-pdf" title="PAN PDF"></iframe>
                            @else
                                <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg><span>Preview unavailable</span></div>
                            @endif
                        @else
                            <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span>Not uploaded</span></div>
                        @endif
                    </div>

                </div>{{-- /kyc-docs-grid --}}

                {{-- Selfie --}}
                <div class="section-divider"></div>
                <div class="section-label">Selfie Verification</div>
                <div class="kyc-selfie-wrap">
                    @if($kycSelfieUrl)
                        <img src="{{ $kycSelfieUrl }}" alt="Selfie with ID" loading="lazy"
                             class="kyc-selfie-img"
                             onclick="openLightbox('{{ $kycSelfieUrl }}')">
                    @else
                        <div class="kyc-selfie-missing">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"/></svg>
                            <span>Not uploaded</span>
                        </div>
                    @endif
                    <div class="kyc-selfie-info">
                        <div class="kyc-selfie-title">Selfie with ID Document</div>
                        <div class="kyc-selfie-sub">Photo holding your Aadhaar or PAN card next to your face, used to verify your identity against submitted documents.</div>
                        @if($kycSelfieUrl)
                        <div style="margin-top:10px;display:flex;gap:6px;">
                            <a href="{{ $kycSelfieUrl }}" target="_blank" onclick="event.preventDefault();openLightbox('{{ $kycSelfieUrl }}')" class="kyc-doc-btn kyc-doc-btn-view">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                View full size
                            </a>
                            <a href="{{ $kycSelfieUrl }}" download class="kyc-doc-btn kyc-doc-btn-dl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Bank Account Details --}}
                <div class="section-divider"></div>
                <div class="section-label">Bank Account Details</div>
                <div class="bank-grid">
                    <div class="bank-field">
                        <div class="bank-field-lbl">Account Holder</div>
                        @if($bankHolder)
                            <div class="bank-field-val">{{ $bankHolder }}</div>
                        @else
                            <div class="bank-field-val empty">Not provided</div>
                        @endif
                    </div>
                    <div class="bank-field">
                        <div class="bank-field-lbl">Bank Name</div>
                        @if($bankName)
                            <div class="bank-field-val">{{ $bankName }}</div>
                        @else
                            <div class="bank-field-val empty">Not provided</div>
                        @endif
                    </div>
                    <div class="bank-field">
                        <div class="bank-field-lbl">Account Number</div>
                        @if($bankAcc)
                            <div class="bank-field-val" style="letter-spacing:.08em;">
                                <span id="accNum" style="filter:blur(4px);cursor:pointer;transition:filter .2s;" onclick="this.style.filter='none';document.getElementById('accReveal').style.display='none';">{{ $bankAcc }}</span>
                                <span id="accReveal" style="font-size:10px;color:var(--accent);cursor:pointer;font-family:var(--font);font-weight:500;" onclick="document.getElementById('accNum').style.filter='none';this.style.display='none';">click to reveal</span>
                            </div>
                        @else
                            <div class="bank-field-val empty">Not provided</div>
                        @endif
                    </div>
                    <div class="bank-field">
                        <div class="bank-field-lbl">IFSC Code</div>
                        @if($bankIfsc)
                            <div class="bank-field-val" style="letter-spacing:.1em;">{{ strtoupper($bankIfsc) }}</div>
                        @else
                            <div class="bank-field-val empty">Not provided</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        @endif
        {{-- /multi-doc card --}}

        {{-- Legacy Uploaded Document Preview (only shown when no multi-docs) --}}
        @if(! $hasMultiDocs)
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Uploaded Document</div>
                        <div class="card-sub">Stored securely on our servers</div>
                    </div>
                </div>
                @if($docServeUrl)
                <a href="{{ $docServeUrl }}" target="_blank"
                   style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;color:var(--accent);text-decoration:none;font-family:var(--mono);">
                    <svg style="width:12px;height:12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download
                </a>
                @endif
            </div>
            <div class="card-body">
                @if(! $docUrl)
                    <div class="doc-no-file">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>No file found on record.</span>
                    </div>
                @elseif($isPdf)
                    <div class="doc-preview-wrap">
                        <div class="doc-pdf-inner">
                            <div class="doc-pdf-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="doc-pdf-name">{{ str_replace('_', ' ', ucfirst($kyc->document_type)) }}</div>
                            <div class="doc-pdf-sub">PDF document — cannot preview inline</div>
                            <a href="{{ $docServeUrl }}" target="_blank" class="doc-pdf-open">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Open PDF in New Tab
                            </a>
                        </div>
                    </div>
                @else
                    <div class="doc-preview-wrap">
                        <div class="doc-img-inner">
                            <img src="{{ $docServeUrl }}"
                                 alt="{{ ucfirst($kyc->document_type) }}"
                                 loading="lazy"
                                 onclick="openLightbox(this.src)">
                            <button class="doc-expand-btn" onclick="openLightbox('{{ $docServeUrl }}')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
        {{-- /legacy doc --}}

        @else
        {{-- No KYC submitted --}}
        <div class="card">
            <div class="card-body" style="padding:52px 20px;text-align:center;">
                <svg style="width:52px;height:52px;color:var(--text3);opacity:0.2;margin:0 auto 16px;display:block;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p style="font-size:15px;font-weight:700;color:var(--text2);margin-bottom:6px;">No documents uploaded yet</p>
                <p style="font-size:12px;color:var(--text3);margin-bottom:24px;max-width:300px;margin-left:auto;margin-right:auto;line-height:1.6;">
                    Upload a government-issued ID (Aadhaar, PAN, Passport, etc.) to get your campaign verified and approved.
                </p>
                <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="btn btn-primary" style="max-width:220px;margin:0 auto;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Upload KYC Documents
                </a>
            </div>
        </div>
        @endif

    </div>{{-- /.left --}}

    {{-- ════ RIGHT COLUMN ════ --}}
    <div class="right-col">

        {{-- Verification Timeline --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div><div class="card-title">Verification Steps</div></div>
                </div>
            </div>
            <div class="card-body">
                <div class="timeline">

                    {{-- Step 1: Submitted --}}
                    <div class="tl-item">
                        <div class="tl-dot {{ $kyc ? 'tl-done' : 'tl-wait' }}">
                            {{ $kyc ? '✓' : '1' }}
                        </div>
                        <div>
                            <div class="tl-label">Documents Submitted</div>
                            <div class="tl-desc">
                                {{ $kyc ? 'Submitted ' . $kyc->created_at->diffForHumans() : 'Not yet submitted' }}
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Under Review --}}
                    <div class="tl-item">
                        @php
                            $step2Class = match($kycStatus) {
                                'pending'  => 'tl-active',
                                'approved' => 'tl-done',
                                'rejected' => 'tl-fail',
                                default    => 'tl-wait',
                            };
                            $step2Icon = match($kycStatus) {
                                'pending'  => '⏳',
                                'approved' => '✓',
                                'rejected' => '✗',
                                default    => '2',
                            };
                        @endphp
                        <div class="tl-dot {{ $step2Class }}">{{ $step2Icon }}</div>
                        <div>
                            <div class="tl-label">Under Review</div>
                            <div class="tl-desc">Admin verifies your document</div>
                        </div>
                    </div>

                    {{-- Step 3: Approved --}}
                    <div class="tl-item">
                        <div class="tl-dot {{ $kycStatus === 'approved' ? 'tl-done' : 'tl-wait' }}">
                            {{ $kycStatus === 'approved' ? '✓' : '3' }}
                        </div>
                        <div>
                            <div class="tl-label">KYC Approved</div>
                            <div class="tl-desc">
                                @if($kycStatus === 'approved' && $kyc->verified_at)
                                    Verified {{ \Carbon\Carbon::parse($kyc->verified_at)->diffForHumans() }}
                                @else
                                    Identity confirmed by team
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Step 4: Campaign Live --}}
                    <div class="tl-item">
                        @php
                        $campaignLive = $kycStatus === 'approved' && in_array($campaign->campaign_state, ['approved', 'live', 'active']);
                        @endphp
                        <div class="tl-dot {{ $campaignLive ? 'tl-done' : 'tl-wait' }}">
                            {{ $campaignLive ? '✓' : '4' }}
                        </div>
                        <div>
                            <div class="tl-label">Campaign Goes Live</div>
                            <div class="tl-desc">Campaign approved &amp; published</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div><div class="card-title">Actions</div></div>
                </div>
            </div>
            <div class="card-body">
                <div class="action-buttons">
                @if($kycStatus !== 'approved')
                    <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        {{ $kyc ? 'Re-upload Documents' : 'Upload KYC Now' }}
                    </a>
                @endif
                <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Campaign Overview
                </a>
                <a href="{{ url('/user/dashboard') }}" class="btn btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Back to Dashboard
                </a>
                </div>
            </div>
        </div>

        {{-- Campaign + KYC summary --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-yellow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div><div class="card-title">Summary</div></div>
                </div>
            </div>
            <div class="card-body">
                <div class="info-rows">
                    <div class="info-row">
                        <span class="info-row-lbl">CAMPAIGN</span>
                        <span style="font-size:11px;font-weight:600;color:var(--text2);max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;text-align:right;">
                            {{ $campaign->title }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-lbl">CAMPAIGN STATUS</span>
                        <span class="status-chip {{ $chipClass }}" style="font-size:10px;padding:3px 9px;">
                            <span class="dot"></span> {{ $chipLabel }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-lbl">KYC STATUS</span>
                        @if($kycStatus === 'none')
                            <span style="font-size:11px;font-weight:700;color:#ef4444;">Not Submitted</span>
                        @elseif($kycStatus === 'pending')
                            <span style="font-size:11px;font-weight:700;color:#f59e0b;">Pending</span>
                        @elseif($kycStatus === 'approved')
                            <span style="font-size:11px;font-weight:700;color:#10b981;">✓ Verified</span>
                        @else
                            <span style="font-size:11px;font-weight:700;color:#ef4444;">✗ Rejected</span>
                        @endif
                    </div>
                    @if($hasMultiDocs)
                    <div class="info-row">
                        <span class="info-row-lbl">AADHAAR</span>
                        <span style="font-size:11px;font-weight:600;color:{{ $kycAadhaarUrl ? 'var(--green)' : 'var(--text3)' }};">
                            {{ $kycAadhaarUrl ? '✓ Uploaded' : '— Not uploaded' }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-lbl">PAN</span>
                        <span style="font-size:11px;font-weight:600;color:{{ $kycPanUrl ? 'var(--green)' : 'var(--text3)' }};">
                            {{ $kycPanUrl ? '✓ Uploaded' : '— Not uploaded' }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-lbl">SELFIE</span>
                        <span style="font-size:11px;font-weight:600;color:{{ $kycSelfieUrl ? 'var(--green)' : 'var(--text3)' }};">
                            {{ $kycSelfieUrl ? '✓ Uploaded' : '— Not uploaded' }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>{{-- /.right-col --}}

</div>{{-- /.page-grid --}}
@endsection

@push('page_scripts')
<script>
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') closeLightbox();
});
</script>
@endpush
