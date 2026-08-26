@push('page_css')
@vite('resources/css/admin/entries/applications-index.css')
@endpush

{{-- resources/views/admin/applications/index.blade.php --}}
@extends('layouts.admin')

@section('sidebar_applications', 'active')
@section('page_title', 'NGO Applications')
@section('page_subtitle', 'Review and manage NGO onboarding applications')

@push('page_styles')
<style>
.flash-ok{background:rgba(5,196,138,.09);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px}
[data-theme="dark"] .flash-ok{color:#34d399}
.flash-ok svg{width:15px;height:15px;flex-shrink:0}
.sec-search{position:relative;display:flex;align-items:center}
.sec-search svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text3);pointer-events:none}
.sec-search input{height:38px;width:230px;max-width:46vw;padding:0 12px 0 34px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-size:12.5px;font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease)}
.sec-search input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface)}
.sec-search input::placeholder{color:var(--text3)}
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;box-shadow:var(--sh);animation:fadeUp .4s .15s ease both}
.table-scroll{overflow-x:auto;-webkit-overflow-scrolling:touch}
.table-scroll::-webkit-scrollbar{height:5px}
.table-scroll::-webkit-scrollbar-track{background:var(--surface2)}
.table-scroll::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px}
table{width:100%;min-width:900px;border-collapse:collapse}
thead{background:var(--surface2)}
thead th{padding:13px 16px;text-align:left;font-size:10px;text-transform:uppercase;color:var(--text3);font-family:var(--mono);white-space:nowrap;letter-spacing:.08em;font-weight:700;border-bottom:1px solid var(--border)}
tbody td{padding:14px 16px;border-bottom:1px solid var(--border);font-size:13px;vertical-align:middle;color:var(--text2)}
tbody tr:last-child td{border-bottom:none}
tbody tr{transition:background var(--ease)}
tbody tr:hover{background:var(--surface2)}
.org-cell .org-name{font-weight:700;color:var(--text);font-size:13.5px;display:block}
.org-cell .org-loc{font-size:11px;color:var(--text3);margin-top:3px;font-family:var(--mono);display:flex;align-items:center;gap:4px}
.org-cell .org-loc svg{width:11px;height:11px;flex-shrink:0}
.u-cell{display:flex;align-items:center;gap:10px}
.u-cell .u-av{width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:11px;font-weight:700;font-family:var(--mono);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.u-cell .u-name{font-weight:600;color:var(--text);font-size:13px;display:block}
.u-cell .u-email{font-size:10.5px;color:var(--text3);margin-top:1px;font-family:var(--mono)}
.type-chip{display:inline-flex;padding:4px 11px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.15)}
.contact-cell .u-name{font-weight:600;color:var(--text);font-size:12.5px;display:block}
.contact-cell .u-email{font-size:10.5px;color:var(--text3);margin-top:2px;font-family:var(--mono)}
.date-cell{font-family:var(--mono);font-size:12px;color:var(--text2);white-space:nowrap}
.id-cell{font-family:var(--mono);font-size:12px;color:var(--text3);font-weight:600;white-space:nowrap}
.action-cell{display:flex;gap:5px;flex-wrap:nowrap}
.action-cell .c-btn{padding:6px 10px;font-size:11px}
.action-cell .c-btn svg{width:11px;height:11px}
.empty-state{padding:60px 20px;text-align:center}
.empty-state svg{width:44px;height:44px;margin:0 auto 14px;display:block;opacity:.2}
.empty-state strong{display:block;font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2);margin-bottom:5px}
.empty-state p{font-size:13px;color:var(--text3)}
/* Badge overrides for review status */
.b-review{background:rgba(59,130,246,.15);color:#1d4ed8;border:1px solid rgba(59,130,246,.25)}
[data-theme="dark"] .b-review{color:#93c5fd}
/* Mobile card layout */
@media(max-width:860px){
  .sec-search{order:2;width:100%}
  .sec-search input{width:100%;max-width:none}
  table{min-width:0}
  thead{display:none}
  tbody tr{display:block;margin-bottom:12px;padding:10px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r-sm);box-shadow:var(--sh)}
  tbody td{display:flex;align-items:flex-start;gap:8px;padding:7px 6px;border:none!important;text-align:left}
  tbody td::before{content:attr(data-label);font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text3);font-family:var(--mono);min-width:70px;flex-shrink:0;padding-top:2px}
  tbody td.action-cell{flex-wrap:wrap;gap:5px}
  tbody td.action-cell::before{padding-top:4px}
  .action-cell .c-btn{flex:1;min-width:0}
}
@media(max-width:640px){.stat{padding:14px 16px}.sec-hdr>div:last-child{flex-direction:column;align-items:stretch}.sec-hdr>div:last-child .ftabs{overflow-x:auto;white-space:nowrap;-webkit-overflow-scrolling:touch;padding-bottom:2px}}
@media(max-width:480px){.stat{padding:12px 14px}.stat-val{font-size:15px}.stat-lbl{font-size:9px}}
@media(max-width:380px){
  .stat{padding:10px 12px}
  .stat-val{font-size:13px}
  .pagination-wrap{flex-direction:column;gap:8px;text-align:center}
  .sec-search input{width:100%;max-width:none;}
}
</style>
@endpush

@section('content')

{{-- HERO --}}
<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>NGO Onboarding</div>
    <div class="hero-name">Applications</div>
    <div class="hero-sub">Review and manage NGO onboarding applications.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-blue">Total {{ $applications->total() }}</span>
      <span class="hero-badge hb-amber">Pending {{ $cntPending }}</span>
      <span class="hero-badge hb-purple">Under Review {{ $cntReview }}</span>
      <span class="hero-badge hb-green">Approved {{ $cntApproved }}</span>
      <span class="hero-badge hb-red">Rejected {{ $cntRejected }}</span>
    </div>
  </div>
</div>

{{-- STATS --}}
<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Pending</div>
      <div class="stat-val sv-amber" id="statPending">{{ $cntPending }}</div>
      <div class="stat-foot">Awaiting review</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Under Review</div>
      <div class="stat-val sv-blue" id="statReview">{{ $cntReview }}</div>
      <div class="stat-foot">Being evaluated</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Approved</div>
      <div class="stat-val sv-green" id="statApproved">{{ $cntApproved }}</div>
      <div class="stat-foot">NGOs onboarded</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Rejected</div>
      <div class="stat-val sv-red" id="statRejected">{{ $cntRejected }}</div>
      <div class="stat-foot">Declined</div>
    </div>
  </div>
</div>

{{-- FLASH MESSAGES --}}
@if(session('success'))
<div class="flash-ok">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

{{-- HEADER WITH SEARCH + FILTER TABS --}}
<div class="sec-hdr">
  <div class="sec-ttl">All Applications</div>
  <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
    <div class="sec-search">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" id="searchInput" placeholder="Search name, email, org…" autocomplete="off" aria-label="Search applications">
    </div>
    <div class="ftabs" id="ftabs">
      <button class="ftab on" data-filter="all">All <span class="cnt" id="cntAll">{{ $applications->total() }}</span></button>
      <button class="ftab" data-filter="pending">Pending <span class="cnt" id="cntPending">{{ $cntPending }}</span></button>
      <button class="ftab" data-filter="under_review">Review <span class="cnt" id="cntReview">{{ $cntReview }}</span></button>
      <button class="ftab" data-filter="approved">Approved <span class="cnt" id="cntApproved">{{ $cntApproved }}</span></button>
      <button class="ftab" data-filter="rejected">Rejected <span class="cnt" id="cntRejected">{{ $cntRejected }}</span></button>
    </div>
    <select class="ftab-select" onchange="var btn=document.querySelector('.ftab[data-filter=&quot;'+this.value+'&quot;]');if(btn)btn.click();">
      <option value="all">All ({{ $applications->total() }})</option>
      <option value="pending">Pending ({{ $cntPending }})</option>
      <option value="under_review">Review ({{ $cntReview }})</option>
      <option value="approved">Approved ({{ $cntApproved }})</option>
      <option value="rejected">Rejected ({{ $cntRejected }})</option>
    </select>
  </div>
</div>

{{-- TABLE --}}
<div class="table-card">
  <div class="table-scroll">
    <table>
      <thead>
        <tr>
          <th>#ID</th>
          <th>Organization</th>
          <th>Applicant</th>
          <th>Type</th>
          <th>Contact</th>
          <th>Submitted</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="tbody">
        @forelse($applications as $app)
        @php
          $srchStr = strtolower(($app->name ?? '').' '.($app->user->name ?? '').' '.($app->user->email ?? '').' '.($app->contact_name ?? '').' '.($app->contact_email ?? '').' '.($app->organization_type ?? ''));
        @endphp
        <tr data-id="{{ $app->id }}" data-status="{{ $app->status }}" data-search="{{ $srchStr }}">
          <td data-label="ID">
            <span class="id-cell">#{{ $app->id }}</span>
          </td>
          <td class="org-cell" data-label="Organization">
            <span class="org-name">{{ $app->name }}</span>
            @if($app->city || $app->state)
            <span class="org-loc">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              {{ $app->city }}{{ $app->city && $app->state ? ', ' : '' }}{{ $app->state }}
            </span>
            @endif
          </td>
          <td data-label="Applicant">
            <div class="u-cell">
              <div class="u-av">{{ strtoupper(substr($app->user->name ?? 'U', 0, 1)) }}</div>
              <div>
                <span class="u-name">{{ $app->user->name ?? '—' }}</span>
                @if($app->user->email ?? false)
                <span class="u-email">{{ $app->user->email }}</span>
                @endif
              </div>
            </div>
          </td>
          <td data-label="Type">
            @if($app->organization_type)
              <span class="type-chip">{{ $app->organization_type }}</span>
            @else
              <span style="color:var(--text3);font-size:12px;">—</span>
            @endif
          </td>
          <td class="contact-cell" data-label="Contact">
            <span class="u-name">{{ $app->contact_name }}</span>
            <span class="u-email">{{ $app->contact_email }}</span>
          </td>
          <td data-label="Submitted">
            <span class="date-cell">{{ $app->submitted_at ? $app->submitted_at->format('d M Y') : '—' }}</span>
          </td>
          <td data-label="Status">
            @if($app->status === 'pending')
              <span class="badge b-pending">Pending</span>
            @elseif($app->status === 'under_review')
              <span class="badge b-review">Under Review</span>
            @elseif($app->status === 'approved')
              <span class="badge b-approved">Approved</span>
            @elseif($app->status === 'rejected')
              <span class="badge b-rejected">Rejected</span>
            @endif
          </td>
          <td class="action-cell" data-label="Actions">
              <a href="{{ route('admin.applications.show', $app->id) }}" class="btn btn-secondary act-btn ab-view" title="View details">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              View
            </a>
            @if($app->status === 'pending' || $app->status === 'under_review')
            <form method="POST" action="{{ route('admin.applications.approve', $app->id) }}" data-loading-text="Approving…" style="display:inline">
              @csrf
              <button type="submit" class="btn btn-green c-btn c-btn-approve" title="Approve">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Approve
              </button>
            </form>
            <button type="button" class="btn btn-red c-btn c-btn-reject" data-action="open-reject-modal" data-id="{{ $app->id }}" title="Reject">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
              Reject
            </button>
            @endif
            <form method="POST" action="{{ route('admin.applications.destroy', $app->id) }}" style="display:inline;" data-confirm="Delete this application? This cannot be undone.">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-red act-btn ab-delete" title="Delete">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                Delete
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="empty-state">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <strong>No applications found</strong>
              <p>No NGO applications have been submitted yet.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- PAGINATION --}}
<div class="pagination-wrap" style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;flex-wrap:wrap;gap:10px;">
  <div style="font-size:12px;color:var(--text3);font-family:var(--mono);">
    Showing <strong style="color:var(--text);">{{ $applications->firstItem() }}–{{ $applications->lastItem() }}</strong> of <strong style="color:var(--text);">{{ $applications->total() }}</strong>
  </div>
  {{ $applications->onEachSide(1)->links('vendor.pagination.admin') }}
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
@vite('resources/js/admin/entries/applications-index.js')
@endpush
