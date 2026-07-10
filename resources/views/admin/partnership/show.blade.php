@extends('layouts.admin')

@section('page_title', $partnership->name)
@section('page_subtitle', 'Partnership details')
@section('sidebar_partnerships', 'active')


@push('page_styles')
<style>
.alert-ok{background:rgba(5,196,138,.08);border:1px solid rgba(5,196,138,.22);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease;}
[data-theme="dark"] .alert-ok{color:#189d68;}
.alert-ok svg{width:15px;height:15px;flex-shrink:0;}
.back-btn{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 14px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;font-weight:600;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all var(--ease);margin-bottom:20px;text-decoration:none;}
.back-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.back-btn svg{width:13px;height:13px;}
.page-hdr{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:22px;flex-wrap:wrap;animation:fadeUp .4s ease both;}
.page-hdr-left{display:flex;align-items:center;gap:14px;}
.page-av{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:20px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);box-shadow:0 4px 18px rgba(37,99,235,.35);}
.page-name{font-family:var(--mono);font-size:20px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1.2;}
.page-meta{font-size:11.5px;color:var(--text3);margin-top:3px;font-family:var(--mono);}
.page-hdr-right{display:flex;align-items:center;gap:8px;flex-shrink:0;flex-wrap:wrap;}
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;}
.s-pending{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.22);}
.s-approved{background:var(--green-lt);color:#059669;border:1px solid rgba(5,196,138,.22);}
.s-rejected{background:var(--red-lt);color:#b91c1c;border:1px solid rgba(240,68,68,.22);}
[data-theme="dark"] .s-pending{color:var(--amber);}
[data-theme="dark"] .s-approved{color:var(--green);}
[data-theme="dark"] .s-rejected{color:var(--red);}
.status-dot{width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;}
.pri-pill{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;}
.pri-high{background:var(--green-lt);color:#059669;border:1px solid rgba(5,196,138,.22);}
.pri-medium{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.22);}
.pri-low{background:var(--red-lt);color:#b91c1c;border:1px solid rgba(240,68,68,.22);}
[data-theme="dark"] .pri-high{color:var(--green);}
[data-theme="dark"] .pri-medium{color:var(--amber);}
[data-theme="dark"] .pri-low{color:var(--red);}
.score-badge{font-size:11px;font-weight:700;font-family:var(--mono);padding:5px 10px;border-radius:100px;background:var(--surface2);border:1px solid var(--border2);color:var(--text3);}
.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .1s ease both;}
.card-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.card-head-left{display:flex;align-items:center;gap:10px;}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-section{padding:22px 24px;border-bottom:1px solid var(--border);}
.card-section:last-child{border-bottom:none;}
.section-label{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.18em;font-family:var(--mono);margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.section-label svg{width:12px;height:12px;opacity:.7;}
.info-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:0;}
.info-item{padding:14px 18px;border-right:1px solid var(--border);border-bottom:1px solid var(--border);}
.info-item:nth-child(3n){border-right:none;}
.info-item:nth-last-child(-n+3){border-bottom:none;}
.info-lbl{font-size:9.5px;font-family:var(--mono);color:var(--text3);text-transform:uppercase;letter-spacing:.09em;margin-bottom:5px;}
.info-val{font-size:13px;font-weight:500;color:var(--text);line-height:1.4;}
.info-val a{color:var(--a);}
.info-val a:hover{text-decoration:underline;}
.info-val.mono{font-family:var(--mono);font-size:12.5px;}
.info-val.muted{color:var(--text3);font-style:italic;}
.info-val.accent{color:var(--a);font-weight:600;}
.msg-box{background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:18px 20px;font-size:13.5px;color:var(--text2);line-height:1.75;}
.doc-btn{display:inline-flex;align-items:center;gap:8px;height:38px;padding:0 16px;background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.20);border-radius:var(--r-sm);font-size:12.5px;font-weight:600;font-family:var(--font);transition:all var(--ease);text-decoration:none;}
.doc-btn:hover{background:var(--a);color:#fff;transform:translateY(-1px);box-shadow:0 4px 14px rgba(37,99,235,.3);}
.doc-btn svg{width:13px;height:13px;}
.review-row{display:flex;align-items:center;gap:28px;flex-wrap:wrap;}
.review-item{display:flex;flex-direction:column;gap:4px;}
.review-lbl{font-size:9.5px;font-family:var(--mono);color:var(--text3);text-transform:uppercase;letter-spacing:.09em;}
.review-val{font-size:13px;font-weight:600;color:var(--text);}
.review-val.pending{color:var(--amber);}
.review-val.empty{color:var(--text3);font-weight:400;font-style:italic;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;}
.form-group{display:flex;flex-direction:column;gap:6px;}
.form-lbl{font-size:10px;font-family:var(--mono);color:var(--text3);text-transform:uppercase;letter-spacing:.09em;}
.form-select,.form-textarea{width:100%;border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-size:13px;font-family:var(--font);color:var(--text);background:var(--surface2);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.form-select:focus,.form-textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.form-textarea{resize:vertical;line-height:1.6;}
.submit-btn{display:inline-flex;align-items:center;gap:7px;height:40px;padding:0 20px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:13px;font-weight:600;cursor:pointer;font-family:var(--font);transition:opacity var(--ease),transform var(--ease);box-shadow:0 4px 14px rgba(37,99,235,.3);}
.submit-btn:hover{opacity:.88;transform:translateY(-1px);}
.submit-btn svg{width:13px;height:13px;}
@-webkit-keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@media(max-width:640px){.info-grid{grid-template-columns:1fr 1fr}.info-item:nth-child(3n){border-right:1px solid var(--border)}.info-item:nth-child(2n){border-right:none}.info-item:nth-last-child(-n+3){border-bottom:1px solid var(--border)}.info-item:nth-last-child(-n+2){border-bottom:none}.form-row{grid-template-columns:1fr}.page-hdr{flex-direction:column;gap:12px}}
</style>
@endpush
@section('content')
{{-- BACK --}}
<a href="{{ route('admin.partnership.index') }}" class="back-btn">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
  Back to Partnerships
</a>

@php
  $init   = strtoupper(substr($partnership->name ?? 'A', 0, 1));
  $score  = $partnership->priority_score ?? 0;
  $priCls = $score >= 30 ? 'pri-high'   : ($score >= 10 ? 'pri-medium' : 'pri-low');
  $priLbl = $score >= 30 ? 'High'        : ($score >= 10 ? 'Medium'    : 'Low');
@endphp

{{-- PAGE HEADER --}}
<div class="page-hdr">
  <div class="page-hdr-left">
    <div class="page-av">{{ $init }}</div>
    <div>
      <div class="page-name">{{ $partnership->name }}</div>
      <div class="page-meta">
        #{{ $partnership->id }}
        &nbsp;·&nbsp;
        {{ $partnership->organization_name ?? 'No Organisation' }}
        &nbsp;·&nbsp;
        Submitted {{ \Carbon\Carbon::parse($partnership->created_at)->format('d M Y') }}
      </div>
    </div>
  </div>
  <div class="page-hdr-right">
    <span class="pri-pill {{ $priCls }}"><span class="status-dot"></span>{{ $priLbl }} Priority</span>
    <span class="score-badge">Score: {{ $score }}</span>
    @if($partnership->status === 'pending')
      <span class="status-pill s-pending"><span class="status-dot"></span> Pending</span>
    @elseif($partnership->status === 'approved')
      <span class="status-pill s-approved"><span class="status-dot"></span> Approved</span>
    @else
      <span class="status-pill s-rejected"><span class="status-dot"></span> Rejected</span>
    @endif
  </div>
</div>

{{-- MAIN CARD --}}
<div class="main-card">

  {{-- ── Contact Information ─────────────────────────────────────────── --}}
  <div class="card-section">
    <div class="section-label">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      Contact Information
    </div>
    <div class="info-grid">
      <div class="info-item">
        <div class="info-lbl">Full Name</div>
        <div class="info-val">{{ $partnership->name }}</div>
      </div>
      <div class="info-item">
        <div class="info-lbl">Email Address</div>
        <div class="info-val"><a href="mailto:{{ $partnership->email }}">{{ $partnership->email }}</a></div>
      </div>
      <div class="info-item">
        <div class="info-lbl">Phone</div>
        <div class="info-val {{ $partnership->phone ? '' : 'muted' }}">{{ $partnership->phone ?? 'Not provided' }}</div>
      </div>
    </div>
  </div>

  {{-- ── Organisation Details ────────────────────────────────────────── --}}
  <div class="card-section">
    <div class="section-label">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
      Organisation Details
    </div>
    <div class="info-grid">
      <div class="info-item">
        <div class="info-lbl">Organisation Name</div>
        <div class="info-val" style="font-weight:600;">{{ $partnership->organization_name ?? '—' }}</div>
      </div>
      <div class="info-item">
        <div class="info-lbl">Website</div>
        <div class="info-val">
          @if($partnership->website)
            <a href="{{ $partnership->website }}" target="_blank" rel="noopener">{{ $partnership->website }}</a>
          @else
            <span class="muted">Not provided</span>
          @endif
        </div>
      </div>
      <div class="info-item">
        <div class="info-lbl">Organisation Type</div>
        <div class="info-val">{{ $partnership->organization_type ?? '—' }}</div>
      </div>
      <div class="info-item">
        <div class="info-lbl">Team Size</div>
        <div class="info-val mono">{{ $partnership->organization_size ?? '—' }}</div>
      </div>
      <div class="info-item">
        <div class="info-lbl">Location</div>
        <div class="info-val">{{ $partnership->location ?? '—' }}</div>
      </div>
    </div>
  </div>

  {{-- ── Partnership Details ──────────────────────────────────────────── --}}
  <div class="card-section">
    <div class="section-label">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
      Partnership Details
    </div>
    <div class="info-grid">
      <div class="info-item">
        <div class="info-lbl">Partnership Type</div>
        <div class="info-val accent">{{ ucfirst($partnership->partnership_type ?? '—') }}</div>
      </div>
      <div class="info-item">
        <div class="info-lbl">Goal</div>
        <div class="info-val">{{ ucfirst($partnership->goal ?? '—') }}</div>
      </div>
      <div class="info-item">
        <div class="info-lbl">Timeline</div>
        <div class="info-val">{{ ucfirst(str_replace('_', ' ', $partnership->timeline ?? 'N/A')) }}</div>
      </div>
      <div class="info-item">
        <div class="info-lbl">Priority Score</div>
        <div class="info-val">
          <span class="pri-pill {{ $priCls }}" style="font-size:10px;padding:3px 9px;">
            <span class="status-dot"></span>{{ $priLbl }}
          </span>
          <span class="score-badge" style="font-size:10.5px;padding:3px 8px;margin-left:4px;">{{ $score }}</span>
        </div>
      </div>
    </div>
  </div>

  {{-- ── Proposal Message ────────────────────────────────────────────── --}}
  <div class="card-section">
    <div class="section-label">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
      Proposal Message
    </div>
    <div class="msg-box">{{ $partnership->message ?? 'No message provided.' }}</div>
  </div>

  {{-- ── Attached Document (conditional) ────────────────────────────── --}}
  @if($partnership->document)
  <div class="card-section">
    <div class="section-label">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Attached Document
    </div>
    <a href="{{ asset('storage/'.$partnership->document) }}" class="doc-btn" target="_blank" rel="noopener">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
      Download Document
    </a>
  </div>
  @endif

  {{-- ── Review Status ───────────────────────────────────────────────── --}}
  <div class="card-section">
    <div class="section-label">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
      Review Status
    </div>
    <div class="review-row">
      <div class="review-item">
        <div class="review-lbl">Current Status</div>
        <div class="review-val">
          @if($partnership->status === 'pending')
            <span class="status-pill s-pending" style="font-size:10px;padding:3px 10px;"><span class="status-dot"></span> Pending</span>
          @elseif($partnership->status === 'approved')
            <span class="status-pill s-approved" style="font-size:10px;padding:3px 10px;"><span class="status-dot"></span> Approved</span>
          @else
            <span class="status-pill s-rejected" style="font-size:10px;padding:3px 10px;"><span class="status-dot"></span> Rejected</span>
          @endif
        </div>
      </div>
      <div class="review-item">
        <div class="review-lbl">Reviewed By</div>
        @if($partnership->reviewer)
          <div class="review-val">{{ $partnership->reviewer->name }}</div>
        @else
          <div class="review-val empty">Not reviewed yet</div>
        @endif
      </div>
      <div class="review-item">
        <div class="review-lbl">Reviewed At</div>
        @if($partnership->reviewed_at)
          <div class="review-val">{{ \Carbon\Carbon::parse($partnership->reviewed_at)->format('d M Y, h:i A') }}</div>
        @else
          <div class="review-val pending">Pending</div>
        @endif
      </div>
      <div class="review-item">
        <div class="review-lbl">Submitted At</div>
        <div class="review-val">{{ \Carbon\Carbon::parse($partnership->created_at)->format('d M Y, h:i A') }}</div>
      </div>
    </div>
  </div>

  {{-- ── Admin Actions ───────────────────────────────────────────────── --}}
  <div class="card-section">
    <div class="section-label">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      Admin Actions
    </div>
    <form method="POST" action="{{ route('admin.partnership.update', $partnership->id) }}">
      @csrf
      <div class="form-row">
        <div class="form-group">
          <label class="form-lbl">Update Status</label>
          <select name="status" class="form-select" required>
            <option value="">Select new status…</option>
            <option value="approved" {{ $partnership->status == 'approved' ? 'selected' : '' }}>✓ Approve</option>
            <option value="rejected" {{ $partnership->status == 'rejected' ? 'selected' : '' }}>✗ Reject</option>
            <option value="pending"  {{ $partnership->status == 'pending'  ? 'selected' : '' }}>↩ Reset to Pending</option>
          </select>
        </div>
      </div>
      <div class="form-group" style="margin-bottom:16px;">
        <label class="form-lbl">Admin Notes (internal)</label>
        <textarea name="admin_notes" class="form-textarea" rows="5"
          placeholder="Write internal review notes…">{{ $partnership->admin_notes }}</textarea>
      </div>
      <button type="submit" class="submit-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        Update Partnership Status
      </button>
    </form>
  </div>

</div>{{-- /main-card --}}
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';
var a=document.querySelector('.alert-ok');if(!a)return;
setTimeout(function(){a.style.transition='opacity .4s,transform .4s';a.style.opacity='0';a.style.transform='translateY(-6px)';setTimeout(function(){a.remove();},400);},4000);
})();
</script>
@endpush
