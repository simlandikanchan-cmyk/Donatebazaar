{{-- resources/views/admin/applications/show.blade.php --}}
@extends('layouts.admin')

@section('sidebar_applications', 'active')
@section('page_title', Str::limit($application->name, 28))
@section('page_subtitle', 'Reviewing NGO application details')

@push('page_styles')
<style>
.hero-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:28px 30px;box-shadow:var(--sh);margin-bottom:20px;display:flex;align-items:flex-start;justify-content:space-between;gap:20px;animation:fadeUp .35s ease both;position:relative;overflow:hidden}
.hero-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--a),var(--a2));border-radius:var(--r) var(--r) 0 0}
.hero-left{display:flex;align-items:center;gap:18px;min-width:0}
.hero-av{width:58px;height:58px;border-radius:16px;flex-shrink:0;background:linear-gradient(135deg,var(--a),var(--a2));display:flex;align-items:center;justify-content:center;font-family:var(--mono);font-size:22px;font-weight:800;color:#fff;box-shadow:0 4px 18px rgba(110,86,247,.35)}
.hero-title{font-family:var(--mono);font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.03em;line-height:1.2}
.hero-sub{font-size:12px;color:var(--text3);margin-top:5px;font-family:var(--mono)}
.hero-meta{display:flex;align-items:center;gap:14px;margin-top:10px;flex-wrap:wrap}
.hero-meta-item{display:flex;align-items:center;gap:5px;font-size:11.5px;color:var(--text3);font-family:var(--mono)}
.hero-meta-item svg{width:12px;height:12px;flex-shrink:0}
.hero-right{display:flex;flex-direction:column;align-items:flex-end;gap:12px;flex-shrink:0}
.details-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:24px;box-shadow:var(--sh);margin-bottom:20px;animation:fadeUp .4s .08s ease both}
.details-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.info-box{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:16px 18px;transition:border-color var(--ease),box-shadow var(--ease)}
.info-box:hover{border-color:rgba(110,86,247,.25);box-shadow:0 0 0 3px var(--a-lt)}
.info-label{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;margin-bottom:7px;font-family:var(--mono)}
.info-value{font-size:14px;font-weight:600;color:var(--text);line-height:1.5;word-break:break-word;font-family:var(--mono)}
.info-value.empty{color:var(--text3);font-weight:400}
.actions-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:22px 24px;box-shadow:var(--sh);display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;animation:fadeUp .4s .16s ease both}
.actions-left{font-size:12.5px;color:var(--text3);font-family:var(--mono)}
.actions-left strong{display:block;font-size:13px;font-weight:700;color:var(--text2);margin-bottom:2px}
.actions-right{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.c-btn-back{background:var(--surface2);color:var(--text2);border-color:var(--border2)}
.c-btn-back:hover{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.25);transform:translateY(-1px)}
.badge{display:inline-flex;align-items:center;gap:5px;font-size:10px;font-weight:700;padding:5px 12px;border-radius:7px;text-transform:uppercase;letter-spacing:.07em;font-family:var(--mono)}
.badge::before{content:'';width:6px;height:6px;border-radius:50%;background:currentColor;opacity:.7}
.b-pending{background:rgba(245,158,11,.15);color:#b45309;border:1px solid rgba(245,158,11,.25)}
.b-review{background:rgba(59,130,246,.15);color:#1d4ed8;border:1px solid rgba(59,130,246,.25)}
.b-approved{background:rgba(5,196,138,.15);color:#047857;border:1px solid rgba(5,196,138,.25)}
.b-rejected{background:rgba(240,68,68,.15);color:#b91c1c;border:1px solid rgba(240,68,68,.25)}
[data-theme="dark"] .b-pending{color:#fbbf24}
[data-theme="dark"] .b-review{color:#93c5fd}
[data-theme="dark"] .b-approved{color:#189d68}
[data-theme="dark"] .b-rejected{color:#a72a2a}
@media(max-width:1100px){.details-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:680px){.details-grid{grid-template-columns:1fr}.hero-card{flex-direction:column}.hero-right{flex-direction:row;align-items:center}.actions-card{flex-direction:column;align-items:flex-start}}
@media(max-width:600px){.hero-left{flex-direction:column;align-items:flex-start}.hero-av{width:48px;height:48px;font-size:18px}.hero-title{font-size:18px}.c-btn{width:100%}.actions-right{width:100%;flex-direction:column}}
</style>
@endpush

@section('content')
{{-- FLASH --}}
@if(session('success'))
<div class="alert-success">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
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
      </div>
    </div>
  </div>
  <div class="hero-right">
    @if($application->status === 'pending')
      <span class="badge b-pending">Pending</span>
    @elseif($application->status === 'under_review')
      <span class="badge b-review">Under Review</span>
    @elseif($application->status === 'approved')
      <span class="badge b-approved">Approved</span>
    @elseif($application->status === 'rejected')
      <span class="badge b-rejected">Rejected</span>
    @endif
  </div>
</div>

{{-- DETAILS GRID --}}
<div class="details-card">
  <div class="sec-hdr" style="margin-bottom:18px;">
    <span class="sec-ttl">Application Details</span>
  </div>
  <div class="details-grid">
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
      <div class="info-label">Founder Name</div>
      <div class="info-value {{ !$application->founder_name ? 'empty' : '' }}">
        {{ $application->founder_name ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Contact Name</div>
      <div class="info-value {{ !$application->contact_name ? 'empty' : '' }}">
        {{ $application->contact_name ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Contact Email</div>
      <div class="info-value {{ !$application->contact_email ? 'empty' : '' }}">
        {{ $application->contact_email ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Phone Number</div>
      <div class="info-value {{ !$application->contact_phone ? 'empty' : '' }}">
        {{ $application->contact_phone ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">City</div>
      <div class="info-value {{ !$application->city ? 'empty' : '' }}">
        {{ $application->city ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">State</div>
      <div class="info-value {{ !$application->state ? 'empty' : '' }}">
        {{ $application->state ?? '—' }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-label">Pincode</div>
      <div class="info-value {{ !$application->pincode ? 'empty' : '' }}">
        {{ $application->pincode ?? '—' }}
      </div>
    </div>
    <div class="info-box" style="grid-column:1/-1;">
      <div class="info-label">Website</div>
      <div class="info-value {{ !$application->website ? 'empty' : '' }}">
        @if($application->website)
          <a href="{{ $application->website }}" target="_blank" rel="noopener"
             style="color:var(--a);text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
            {{ $application->website }}
            <svg style="width:11px;height:11px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        @else
          —
        @endif
      </div>
    </div>
  </div>
</div>

{{-- ACTIONS CARD --}}
<div class="actions-card">
  <div class="actions-left">
    <strong>Application Actions</strong>
    @if($application->status === 'pending' || $application->status === 'under_review')
      Approve to onboard this NGO, or reject with a reason.
    @else
      This application has been <strong style="color:var(--text);">{{ $application->status }}</strong>.
    @endif
  </div>
  <div class="actions-right">
    <a href="{{ route('admin.applications') }}" class="c-btn c-btn-back">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to List
    </a>
    @if($application->status === 'pending' || $application->status === 'under_review')
      <form method="POST" action="{{ route('admin.applications.approve', $application->id) }}" onsubmit="return handleSub(this,'Approving…')">
        @csrf
        <button type="submit" class="c-btn c-btn-approve">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Approve Application
        </button>
      </form>
      <button type="button" class="c-btn c-btn-reject" onclick="openReject({{ $application->id }})">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        Reject Application
      </button>
    @endif
  </div>
</div>

{{-- REJECT MODAL --}}
<div id="rejectOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeReject()">
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
      <textarea id="rejectReason" name="reason" rows="3" placeholder="Or type a custom reason…" class="modal-ta"></textarea>
      <p id="rejectErr" class="modal-err">⚠ Please provide a reason before rejecting.</p>
      <div class="modal-acts">
        <button type="button" onclick="closeReject()" class="modal-btn modal-cancel">Cancel</button>
        <button type="submit" id="rejectBtn" class="modal-btn modal-red">✕ Reject Application</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

window.openReject=function(id){
  document.getElementById('rejectForm').action='{{ route('admin.applications.reject', ':id') }}'.replace(':id', id);
  document.getElementById('rejectReason').value='';
  document.getElementById('rejectErr').style.display='none';
  var btn=document.getElementById('rejectBtn');btn.disabled=false;btn.innerHTML='✕ Reject Application';
  document.querySelectorAll('.chip-red').forEach(function(c){c.classList.remove('on');});
  document.getElementById('rejectOverlay').classList.add('open');
  setTimeout(function(){document.getElementById('rejectReason').focus();},80);
};
window.closeReject=function(){document.getElementById('rejectOverlay').classList.remove('open');};

document.querySelectorAll('.chip-red').forEach(function(btn){
  btn.addEventListener('click',function(){
    document.querySelectorAll('.chip-red').forEach(function(b){b.classList.remove('on');});
    this.classList.add('on');
    document.getElementById('rejectReason').value=this.dataset.r;
    document.getElementById('rejectErr').style.display='none';
  });
});

document.getElementById('rejectForm').addEventListener('submit',function(e){
  if(!document.getElementById('rejectReason').value.trim()){
    e.preventDefault();document.getElementById('rejectErr').style.display='block';return;
  }
  var btn=document.getElementById('rejectBtn');btn.disabled=true;btn.innerHTML='Rejecting…';
});

document.getElementById('rejectOverlay').addEventListener('click',function(e){if(e.target===this)closeReject();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeReject();});

window.handleSub=function(form,txt){
  form.querySelectorAll('button[type=submit]').forEach(function(b){b.disabled=true;b.textContent=txt;});
  return true;
};

})();
</script>
@endpush
