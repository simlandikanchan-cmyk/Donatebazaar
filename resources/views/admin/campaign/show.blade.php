@push('page_css')
@vite('resources/css/admin/entries/campaign-show.css')
@endpush

@extends('layouts.admin')

@section('sidebar_campaigns', 'active')

@section('page_title', Str::limit($campaign->title, 38))
@section('page_subtitle', 'Campaign details')

@push('page_styles')
@vite('resources/css/admin/entries/campaign-show.css')
<style>
@media(max-width:860px){
  .content-grid,.detail-grid{grid-template-columns:1fr!important}
  .side-panel,.side-stack{width:100%}
  .kyc-docs-grid{grid-template-columns:1fr!important}
  .kyc-selfie-wrap{flex-direction:column!important}
  .fund-stats-3{grid-template-columns:1fr!important}
}
@media(max-width:640px){
  .table-scroll{min-width:480px}
  .hero-right{width:100%;margin-top:14px}
  .hero-right .hero-btn{width:100%;justify-content:center}
  .cover-wrap img{max-height:220px;object-fit:cover;width:100%}
  .kyc-selfie-img{max-width:100%;max-height:260px;object-fit:cover}
  .card-body{padding:14px!important}
  .card>.card-body:first-child{padding-top:14px!important}
  .info-row{flex-direction:column;gap:4px}
  .bank-grid{grid-template-columns:1fr!important}
}
</style>
@endpush

@section('content')

{{-- Reject Modal --}}
<div class="modal-overlay" id="rejectModal">
    <div class="modal">
        <div class="modal-title">Reject Campaign</div>
        <p class="modal-body">Please provide a reason for rejecting this campaign. This will be shown to the fundraiser.</p>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="rejection_reason" placeholder="Rejection reason (optional)…"
                class="c-reject-ta"></textarea>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary action-btn btn-ghost" data-action="close-reject">Cancel</button>
                <button type="submit" class="action-btn btn-red">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>

{{-- Image Lightbox --}}
<div class="lightbox" id="lightbox">
    <button type="button" class="lightbox-close" data-action="close-lightbox" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
    <img src="" alt="Preview" id="lightboxImg">
</div>

@php
    $kyc   = $campaign->user->kycVerification ?? null;
    $state = $campaign->campaign_state;

    $chipClass = match($state) {
        'active'    => 'chip-active',
        'paused'    => 'chip-paused',
        'pending'   => 'chip-pending',
        'rejected'  => 'chip-rejected',
        'expired'   => 'chip-expired',
        'completed' => 'chip-completed',
        default     => 'chip-pending',
    };
    $chipLabel = match($state) {
        'active'    => 'Active',
        'paused'    => 'Paused',
        'pending'   => 'Pending',
        'rejected'  => 'Rejected',
        'expired'   => 'Expired',
        'completed' => 'Completed',
        default     => ucfirst($state ?? 'Unknown'),
    };

    $raised     = $campaign->raised_amount ?? 0;
    $goal       = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
    $percentage = min(100, round(($raised / $goal) * 100));
    $remaining  = max(0, $campaign->goal_amount - $raised);

    /* legacy single-doc KYC */
    $kycDocUrl = $kyc?->document_url ? route('admin.kyc.document', $kyc->id) : null;
    $kycExt    = $kyc?->document_url ? strtolower(pathinfo($kyc->document_url, PATHINFO_EXTENSION)) : null;
    $kycIsPdf  = $kycExt === 'pdf';
    $kycIsImg  = in_array($kycExt, ['jpg','jpeg','png','webp','gif']);

    /* new multi-doc KYC fields — adjust attribute names to match your model */
    $kycAadhaarUrl = $kyc?->aadhaar_url  ? asset('storage/'.$kyc->aadhaar_url)  : null;
    $kycPanUrl     = $kyc?->pan_url      ? asset('storage/'.$kyc->pan_url)      : null;
    $kycSelfieUrl  = $kyc?->selfie_url   ? asset('storage/'.$kyc->selfie_url)   : null;

    $isImg = fn($url) => $url && preg_match('/\.(jpe?g|png|webp|gif)$/i', $url);
    $isPdf = fn($url) => $url && str_ends_with(strtolower($url), '.pdf');

    /* bank details */
    $bankName   = $kyc?->kyc_bank_name    ?? null;
    $bankAcc    = $kyc?->kyc_account_number ?? null;
    $bankIfsc   = $kyc?->kyc_ifsc          ?? null;
    $bankHolder = $kyc?->kyc_account_name  ?? null;

    /* campaign updates */
    $updates = $campaign->updates ?? collect();

    /* fundraiser + donor stats */
    $fundraiser  = $campaign->user ?? null;
    $donorCount  = 0;
    try { $donorCount = $campaign->donations()->count(); } catch (\Throwable $e) {}
    $avgDonation = $donorCount > 0 ? round($raised / $donorCount) : 0;

    /* progress ring geometry (r = 46) */
    $ringCirc   = 2 * M_PI * 46;
    $ringOffset = $ringCirc * (1 - ($percentage / 100));

    $kycStatus = $kyc?->status ?? 'none';
@endphp

@if(session('success'))
<div class="flash-success">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="flash-error">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('error') }}
</div>
@endif

<div class="detail-toolbar">
    <div class="crumbs">
        <a href="{{ route('admin.dashboard') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <span class="crumb-sep">/</span>
        <a href="{{ route('admin.campaign.index') }}">Campaigns</a>
        <span class="crumb-sep">/</span>
        <span class="crumb-current">{{ Str::limit($campaign->title, 34) }}</span>
        <span class="crumb-id">#{{ $campaign->id }}</span>
    </div>
    <div class="top-actions">
        @if(Route::has('campaign.show'))
        <!-- <a href="{{ route('campaign.show', $campaign->id) }}" target="_blank" class="tb-action">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            View live
        </a> -->

        @endif
        <button type="button" class="tb-action" data-action="copy-link" data-url="{{ Route::has('campaign.show') ? route('campaign.show', $campaign->id) : url('/admin/campaign/'.$campaign->id) }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            Copy link
        </button>
        @if(Route::has('admin.campaign.edit'))
        <a href="{{ route('admin.campaign.edit', $campaign->id) }}" class="tb-action primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
        @endif
        <form action="{{ route('admin.campaign.destroy', $campaign->id) }}" method="POST" style="display:inline;" data-confirm="Delete this campaign permanently? This cannot be undone.">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-red act-btn ab-delete" title="Delete">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            Delete
          </button>
        </form>
    </div>
</div>

<div class="page-grid">

    {{-- LEFT COLUMN --}}
    <div>

        {{-- Cover --}}
        <div class="card">
            <div class="cover-wrap">
                @if($campaign->cover_image)
                    <img src="{{ asset('storage/' . $campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
                    <div class="cover-overlay"></div>
                    <div class="cover-meta">
                        <div>
                            <div class="cover-title">{{ Str::limit($campaign->title, 50) }}</div>
                            <div class="cover-created">Created {{ $campaign->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="badge c-cover-badge">
                            <span class="badge-dot"></span>
                            {{ $chipLabel }}
                        </span>
                    </div>
                @else
                    <div class="cover-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        <span>No cover image</span>
                    </div>
                    <div class="c-cover-no-img">
                        <div class="c-cover-no-title">{{ $campaign->title }}</div>
                        <div class="c-cover-no-date">Created {{ $campaign->created_at->diffForHumans() }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- About --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-teal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">About This Campaign</div>
                        <div class="card-sub">Campaign description</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="desc-text">{{ $campaign->description }}</p>
            </div>
        </div>

        {{-- ── NEW: Campaign Updates & Documents ── --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-yellow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Updates &amp; Documents</div>
                        <div class="card-sub">{{ $updates->count() }} update{{ $updates->count() !== 1 ? 's' : '' }} submitted</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($updates->count() > 0)
                    <div class="updates-list">
                        @foreach($updates as $update)
                        <div class="update-item">
                            <div class="update-item-header">
                                <div class="update-item-title">{{ $update->title }}</div>
                                <div class="update-item-date">{{ \Carbon\Carbon::parse($update->created_at)->format('d M Y') }}</div>
                            </div>
                            @if($update->body)
                            <div class="update-item-body">{{ $update->body }}</div>
                            @endif
                            @if($update->document_url)
                            <a href="{{ asset('storage/'.$update->document_url) }}" target="_blank" class="update-doc-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                View attached document
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p>No updates or documents submitted for this campaign.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── NEW: KYC Identity Documents ── --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/></svg>
                    </div>
                    <div>
                        <div class="card-title">KYC Verification</div>
                        <div class="card-sub">Identity documents &amp; bank details</div>
                    </div>
                </div>
                @if($kyc)
                <span class="badge b-{{ $kyc->status }}">
                    <span class="badge-dot"></span>
                    {{ ucfirst($kyc->status) }}
                </span>
                @endif
            </div>
            <div class="card-body">

                @if(! $kyc)
                    <div class="kyc-notice kyc-notice-red">
                        <div class="kyc-notice-title">⚠ KYC Not Submitted</div>
                        <p class="kyc-notice-body">This user has not submitted any KYC documents. The campaign cannot be approved until KYC is verified.</p>
                    </div>

                @else

                    {{-- Status banner --}}
                    @if($kyc->status === 'pending')
                        <div class="kyc-notice kyc-notice-yellow">
                            <div class="kyc-notice-title"> KYC Under Review</div>
                            <p class="kyc-notice-body">Documents submitted on {{ $kyc->created_at->format('d M Y') }}. Awaiting admin verification.</p>
                        </div>
                    @elseif($kyc->status === 'approved')
                        <div class="kyc-notice kyc-notice-green">
                            <div class="kyc-notice-title">✓ KYC Approved</div>
                            <p class="kyc-notice-body">Identity verified @if($kyc->verified_at)on {{ \Carbon\Carbon::parse($kyc->verified_at)->format('d M Y') }}@endif. Eligible for campaign approval.</p>
                        </div>
                    @elseif($kyc->status === 'rejected')
                        <div class="kyc-notice kyc-notice-red">
                            <div class="kyc-notice-title">✗ KYC Rejected</div>
                            <p class="kyc-notice-body">{{ $kyc->rejection_reason ?? 'Documents were rejected.' }}</p>
                        </div>
                    @endif

                    {{-- ── Aadhaar + PAN side-by-side ── --}}
                    <div class="c-section-label">Identity Documents</div>
                    <div class="kyc-docs-grid">

                        {{-- Aadhaar --}}
                        <div class="kyc-doc-tile">
                            <div class="kyc-doc-tile-header">
                                <span class="kyc-doc-tile-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h6"/></svg>
                                    Aadhaar Card
                                </span>
                                @if($kycAadhaarUrl)
                                <div class="c-doc-btns">
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
                                    <a href="{{ $kycAadhaarUrl }}" target="_blank">
                                        <img src="{{ $kycAadhaarUrl }}" alt="Aadhaar" loading="lazy" class="kyc-doc-tile-img">
                                    </a>
                                @elseif($isPdf($kycAadhaarUrl))
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
                                <div class="c-doc-btns">
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
                                    <a href="{{ $kycPanUrl }}" target="_blank">
                                        <img src="{{ $kycPanUrl }}" alt="PAN" loading="lazy" class="kyc-doc-tile-img">
                                    </a>
                                @elseif($isPdf($kycPanUrl))
                                    <iframe src="{{ $kycPanUrl }}" class="kyc-doc-tile-pdf" title="PAN PDF"></iframe>
                                @else
                                    <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg><span>Preview unavailable</span></div>
                                @endif
                            @else
                                <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span>Not uploaded</span></div>
                            @endif
                        </div>

                    </div>{{-- /kyc-docs-grid --}}

                    {{-- ── Selfie with ID ── --}}
                    <div class="c-section-label">Selfie Verification</div>
                    <div class="kyc-selfie-wrap">
                        @if($kycSelfieUrl)
                            <a href="{{ $kycSelfieUrl }}" target="_blank">
                                <img src="{{ $kycSelfieUrl }}" alt="Selfie with ID" loading="lazy" class="kyc-selfie-img">
                            </a>
                        @else
                            <div class="kyc-selfie-missing">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"/></svg>
                                <span>Not uploaded</span>
                            </div>
                        @endif
                        <div class="kyc-selfie-info">
                            <div class="kyc-selfie-title">Selfie with ID Document</div>
                            <div class="kyc-selfie-sub">Applicant must appear holding their Aadhaar or PAN card next to their face. Used to cross-verify identity against submitted documents.</div>
                            @if($kycSelfieUrl)
                            <div class="c-selfie-actions">
                                <a href="{{ $kycSelfieUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
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

                    {{-- ── Bank Account Details ── --}}
                    <div class="c-bank-label">Bank Account Details</div>
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
                                <div class="bank-field-val c-acc-nums">
                                    <span id="accNum" class="c-acc-hidden" data-action="reveal-acc">{{ $bankAcc }}</span>
                                    <span id="accReveal" class="c-acc-reveal" data-action="reveal-acc">click to reveal</span>
                                </div>
                            @else
                                <div class="bank-field-val empty">Not provided</div>
                            @endif
                        </div>
                        <div class="bank-field">
                            <div class="bank-field-lbl">IFSC Code</div>
                            @if($bankIfsc)
                                <div class="bank-field-val c-ifsc">{{ strtoupper($bankIfsc) }}</div>
                            @else
                                <div class="bank-field-val empty">Not provided</div>
                            @endif
                        </div>
                    </div>

                    {{-- Legacy single doc fallback --}}
                    @if($kycDocUrl && !$kycAadhaarUrl && !$kycPanUrl)
                    <div class="c-legacy-sep">
                        <div class="c-section-label">Legacy Document</div>
                        <div class="kyc-doc-row">
                            <div class="kyc-doc-icon">📄</div>
                            <div>
                                <div class="kyc-doc-type">{{ ucfirst(str_replace('_', ' ', $kyc->document_type ?? 'Document')) }}</div>
                                <div class="kyc-doc-num">{{ $kyc->document_number ?? '' }}</div>
                            </div>
                        </div>
                        <div class="kyc-doc-preview">
                            <div class="kyc-doc-preview-header">
                                <div class="kyc-doc-preview-header-left">
                                    @if($kycIsPdf)
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--red);"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        PDF Document
                                    @else
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--blue);"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        Image Document
                                    @endif
                                </div>
                                <div class="kyc-doc-preview-actions">
                                    <a href="{{ $kycDocUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        View
                                    </a>
                                    <a href="{{ $kycDocUrl }}?download=1" download class="kyc-doc-btn kyc-doc-btn-dl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                            @if($kycIsImg)
                                <a href="{{ $kycDocUrl }}" target="_blank">
                                    <img src="{{ $kycDocUrl }}" alt="KYC document" loading="lazy" class="kyc-doc-preview-img">
                                </a>
                            @elseif($kycIsPdf)
                                <iframe src="{{ $kycDocUrl }}" class="kyc-doc-preview-iframe" title="KYC PDF Document"></iframe>
                            @else
                                <div class="kyc-doc-preview-fallback">Preview not available. Use View or Download above.</div>
                            @endif
                        </div>
                    </div>
                    @endif

                @endif
            </div>
        </div>

        {{-- Events --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-yellow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Campaign Events</div>
                        <div class="card-sub">{{ $campaign->events->count() }} event{{ $campaign->events->count() !== 1 ? 's' : '' }}</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($campaign->events->count() > 0)
                <div class="events-grid">
                    @foreach($campaign->events as $event)
                    @php
                        $evCls = match($event->status) {
                            'approved' => 'ev-approved',
                            'pending'  => 'ev-pending',
                            default    => 'ev-default',
                        };
                    @endphp
                    <div class="event-card">
                        <span class="event-badge {{ $evCls }}">{{ ucfirst($event->status) }}</span>
                        <div class="event-title">{{ $event->title }}</div>
                        <div class="event-date">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</div>
                        <div class="event-desc">{{ Str::limit($event->description, 100) }}</div>
                        <a href="{{ route('admin.events.show', $event->id) }}" class="event-link">
                            View details
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:10px;height:10px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p>No events have been created for this campaign.</p>
                </div>
                @endif
            </div>
        </div>

    </div>{{-- /left --}}

    {{-- RIGHT COLUMN --}}
    <div class="right-col">

        {{-- Status + Actions --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-teal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Status &amp; Actions</div>
                    </div>
                </div>
            </div>
            <div class="status-section">

                <div class="status-section-label">CAMPAIGN STATE</div>
                <div class="status-chips">
                    <span class="status-chip-lg {{ $chipClass }}">
                        <span class="chip-dot"></span>
                        {{ $chipLabel }}
                    </span>
                </div>

                <div class="kyc-pill kyc-pill-{{ $kyc?->status ?? 'none' }}">
                    @if(! $kyc) <span>⚠ KYC Not Submitted</span>
                    @elseif($kyc->status === 'pending') <span> KYC Pending Review</span>
                    @elseif($kyc->status === 'approved') <span>✓ KYC Approved</span>
                    @elseif($kyc->status === 'rejected') <span>✗ KYC Rejected</span>
                    @endif
                </div>

                @if($kyc && $kyc->status === 'pending')
                        <form action="{{ route('admin.kyc.approve', $kyc->id) }}" method="POST" class="c-form-mb">
                        @csrf
                        <button type="submit" class="action-btn btn-accent">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Approve KYC
                        </button>
                    </form>
                @endif

                @if($state === 'pending')
                    @if($kyc && $kyc->status === 'approved')
                        <form action="{{ route('admin.campaign.approve', $campaign->id) }}" method="POST" class="c-form-mb">
                            @csrf
                            <button type="submit" class="action-btn btn-green">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Approve Campaign
                            </button>
                        </form>
                    @else
                        <button type="button" class="action-btn btn-disabled" disabled title="KYC must be approved first">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Approve Campaign
                        </button>
                        <p class="c-kyc-warn">⚠ KYC must be approved before approving campaign</p>
                    @endif
                    <button type="button" class="action-btn btn-red" data-action="open-reject" data-id="{{ $campaign->id }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Reject Campaign
                    </button>

                @elseif($state === 'active')
                    <button type="button" class="action-btn btn-red" data-action="open-reject" data-id="{{ $campaign->id }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reject Campaign
                    </button>
                    <form action="{{ route('admin.campaign.pause', $campaign->id) }}" method="POST" class="c-form-mt">
                        @csrf
                        <button type="submit" class="action-btn btn-yellow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>
                            Pause Campaign
                        </button>
                    </form>

                @elseif($state === 'paused')
                    <form action="{{ route('admin.campaign.resume', $campaign->id) }}" method="POST" class="c-form-mb">
                        @csrf
                        <button type="submit" class="action-btn btn-green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Resume Campaign
                        </button>
                    </form>
                    <button type="button" class="action-btn btn-red" data-action="open-reject" data-id="{{ $campaign->id }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reject Campaign
                    </button>

                @elseif($state === 'rejected')
                    <form action="{{ route('admin.campaign.approve', $campaign->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="action-btn btn-green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Re-approve Campaign
                        </button>
                    </form>

                @elseif($state === 'expired' || $state === 'completed')
                    <div class="c-expired-notice">
                        This campaign is {{ $chipLabel }} and no further actions are available.
                    </div>
                @endif

                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary action-btn btn-ghost c-back-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Back to Dashboard
                </a>

            </div>
        </div>

        {{-- Fundraiser --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Fundraiser</div>
                        <div class="card-sub">Campaign owner</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="fundraiser-box">
                    <div class="fundraiser-av">
                        @if($fundraiser?->avatar)
                            <img src="{{ asset('storage/'.$fundraiser->avatar) }}" alt="">
                        @else
                            {{ strtoupper(substr($fundraiser->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <div class="fundraiser-meta">
                        <div class="fundraiser-name">{{ $fundraiser->name ?? '—' }}</div>
                        <div class="fundraiser-email">{{ $fundraiser->email ?? 'No email' }}</div>
                        @php
                            $kycPillMap = [
                                'approved' => ['kyc-pill-approved','✓ KYC Verified'],
                                'pending'  => ['kyc-pill-pending','● KYC Pending'],
                                'rejected' => ['kyc-pill-rejected','✗ KYC Rejected'],
                                'none'     => ['kyc-pill-none','⚠ No KYC'],
                            ];
                            [$fkCls,$fkLbl] = $kycPillMap[$kycStatus] ?? $kycPillMap['none'];
                        @endphp
                        <span class="fundraiser-kyc {{ $fkCls }}">{{ $fkLbl }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fundraising --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-teal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Fundraising</div>
                        <div class="card-sub">Current progress</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <svg width="0" height="0" style="position:absolute;"><defs><linearGradient id="ringGrad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#2563eb "/><stop offset="100%" stop-color="#0d9488"/></linearGradient></defs></svg>
                <div class="fund-ring-row">
                    <div class="fund-ring-wrap">
                        <svg class="fund-ring" viewBox="0 0 104 104">
                            <circle class="fund-ring-bg" cx="52" cy="52" r="46"/>
                            <circle class="fund-ring-fill" cx="52" cy="52" r="46"
                                    style="stroke-dasharray:{{ $ringCirc }};stroke-dashoffset:{{ $ringOffset }};"/>
                        </svg>
                        <div class="fund-ring-center">
                            <div class="fund-ring-pct">{{ $percentage }}%</div>
                            <div class="fund-ring-lbl">funded</div>
                        </div>
                    </div>
                    <div class="fund-ring-side">
                        <div class="fund-raised">₹{{ number_format($raised) }}</div>
                        <div class="fund-goal">raised of ₹{{ number_format($campaign->goal_amount) }} goal</div>
                    </div>
                </div>
                <div class="fund-stats-3">
                    <div class="mini-stat">
                        <div class="mini-stat-val">{{ number_format($donorCount) }}</div>
                        <div class="mini-stat-lbl">Donors</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-val c-mini-stat-sm">₹{{ number_format($remaining) }}</div>
                        <div class="mini-stat-lbl">Remaining</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-val c-mini-stat-sm">₹{{ number_format($avgDonation) }}</div>
                        <div class="mini-stat-lbl">Avg gift</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Campaign Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-pink">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Campaign Info</div>
                    </div>
                </div>
            </div>
            <div class="card-body c-info-pad">
                <div class="info-row">
                    <span class="info-row-lbl">STATE</span>
                    <span class="badge b-{{ $state }}"><span class="badge-dot"></span>{{ $chipLabel }}</span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">KYC</span>
                    <span class="c-info-val-dynamic" style="color:{{ $kyc?->status === 'approved' ? 'var(--green)' : ($kyc?->status === 'pending' ? 'var(--amber)' : 'var(--red)') }};">
                        @if(!$kyc) ⚠ Not Submitted
                        @elseif($kyc->status === 'pending') ✓ Pending
                        @elseif($kyc->status === 'approved') ✓ Verified
                        @else ✗ Rejected
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">UPDATES</span>
                    <span class="c-info-val-mono">{{ $updates->count() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">FUNDRAISER</span>
                    <span class="c-info-val-text">{{ $campaign->user->name ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">CATEGORY</span>
                    <span class="c-info-val-text">{{ $campaign->category->name ?? '—' }}</span>
                </div>
                @if($campaign->end_date)
                <div class="info-row">
                    <span class="info-row-lbl">END DATE</span>
                    <span class="c-info-val-date" style="color:{{ now()->gt($campaign->end_date) ? 'var(--red)' : 'var(--text2)' }};">
                        {{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y') }}
                        @if(now()->gt($campaign->end_date))<span class="c-info-val-date-expired"> (expired)</span>@endif
                    </span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-row-lbl">EVENTS</span>
                    <span class="c-info-val-mono">{{ $campaign->events->count() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">CREATED</span>
                    <span class="c-created-val">{{ $campaign->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

    </div>{{-- /right-col --}}
</div>{{-- /page-grid --}}

@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/campaign-show.js')
@endpush
