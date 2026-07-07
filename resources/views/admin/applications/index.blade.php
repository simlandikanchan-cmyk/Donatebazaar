{{-- resources/views/admin/applications/index.blade.php --}}
@extends('layouts.admin')

@section('sidebar_applications', 'active')
@section('page_title', 'Applications')
@section('page_subtitle', 'Manage applications')

@push('page_styles')
<style>
.alert-success{background:#ecfdf5;border:1px solid rgba(5,196,138,.3);color:#047857;padding:14px 18px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both}
[data-theme="dark"] .alert-success{background:rgba(5,196,138,.1);border-color:rgba(5,196,138,.2);color:#189d68}
.alert-success svg{width:16px;height:16px;flex-shrink:0;color:var(--green)}
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;box-shadow:var(--sh);animation:fadeUp .4s .15s ease both}
.table-scroll{overflow-x:auto}
.table-scroll::-webkit-scrollbar{height:4px}
.table-scroll::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px}
table{width:100%;border-collapse:collapse}
thead{background:var(--surface2)}
thead th{padding:13px 16px;text-align:left;font-size:10px;text-transform:uppercase;color:var(--text3);font-family:var(--mono);white-space:nowrap;letter-spacing:.08em;font-weight:700;border-bottom:1px solid var(--border)}
tbody td{padding:16px;border-top:1px solid var(--border);font-size:13px;vertical-align:middle}
tbody tr{transition:background var(--ease)}
tbody tr:hover{background:var(--surface2)}
.org-name{font-weight:700;color:var(--text);font-size:13.5px}
.org-loc{font-size:11px;color:var(--text3);margin-top:3px;font-family:var(--mono)}
.u-av{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:11px;font-weight:700;font-family:var(--mono);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.u-row{display:flex;align-items:center;gap:9px}
.u-name{font-weight:600;color:var(--text);font-size:13px}
.u-email{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--mono)}
.type-chip{display:inline-flex;padding:4px 10px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);background:var(--a-lt);color:var(--a);border:1px solid rgba(110,86,247,.15)}
.date-val{font-family:var(--mono);font-size:12px;color:var(--text2)}
.actions{display:flex;gap:6px;flex-wrap:nowrap}
.empty-state{text-align:center;padding:64px 20px}
.empty-state svg{width:44px;height:44px;margin:0 auto 14px;display:block;opacity:.2}
.empty-state strong{display:block;font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2);margin-bottom:5px}
.empty-state span{font-size:13px;color:var(--text3)}
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:4px 10px;border-radius:7px;text-transform:uppercase;letter-spacing:.07em;font-family:var(--mono)}
.badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;opacity:.7}
.b-pending{background:rgba(245,158,11,.15);color:#b45309;border:1px solid rgba(245,158,11,.25)}
.b-review{background:rgba(59,130,246,.15);color:#1d4ed8;border:1px solid rgba(59,130,246,.25)}
.b-approved{background:rgba(5,196,138,.15);color:#047857;border:1px solid rgba(5,196,138,.25)}
.b-rejected{background:rgba(240,68,68,.15);color:#b91c1c;border:1px solid rgba(240,68,68,.25)}
[data-theme="dark"] .b-pending{color:#fbbf24}
[data-theme="dark"] .b-review{color:#93c5fd}
[data-theme="dark"] .b-approved{color:#189d68}
[data-theme="dark"] .b-rejected{color:#a72a2a}
</style>
@endpush

@section('content')
{{-- STATS --}}
<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Pending</div>
      <div class="stat-val sv-amber">{{ $applications->where('status','pending')->count() }}</div>
      <div class="stat-foot">Awaiting review</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Under Review</div>
      <div class="stat-val sv-blue">{{ $applications->where('status','under_review')->count() }}</div>
      <div class="stat-foot">Being evaluated</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Approved</div>
      <div class="stat-val sv-green">{{ $applications->where('status','approved')->count() }}</div>
      <div class="stat-foot">NGOs onboarded</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Rejected</div>
      <div class="stat-val sv-red">{{ $applications->where('status','rejected')->count() }}</div>
      <div class="stat-foot">Declined applications</div>
    </div>
  </div>
</div>

{{-- SUCCESS FLASH --}}
@if(session('success'))
<div class="alert-success">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

{{-- TABLE --}}
<div class="sec-hdr">
  <div class="sec-ttl">All Applications</div>
  <div class="sec-right">
    <span style="font-size:12px;color:var(--text3);font-family:var(--mono);">{{ $applications->total() }} total</span>
  </div>
</div>

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
      <tbody>
        @forelse($applications as $app)
        <tr>
          <td>
            <span style="font-family:var(--mono);font-size:12px;color:var(--text3);font-weight:600;">#{{ $app->id }}</span>
          </td>
          <td>
            <div class="org-name">{{ $app->name }}</div>
            @if($app->city || $app->state)
            <div class="org-loc">
              <svg style="width:10px;height:10px;display:inline;margin-right:3px;vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              {{ $app->city }}{{ $app->city && $app->state ? ', ' : '' }}{{ $app->state }}
            </div>
            @endif
          </td>
          <td>
            <div class="u-row">
              <div class="u-av">{{ strtoupper(substr($app->user->name ?? 'U', 0, 1)) }}</div>
              <div>
                <div class="u-name">{{ $app->user->name ?? '—' }}</div>
                @if($app->user->email ?? false)
                <div class="u-email">{{ $app->user->email }}</div>
                @endif
              </div>
            </div>
          </td>
          <td>
            @if($app->organization_type)
              <span class="type-chip">{{ $app->organization_type }}</span>
            @else
              <span style="color:var(--text3);font-size:12px;">—</span>
            @endif
          </td>
          <td>
            <div class="u-name">{{ $app->contact_name }}</div>
            <div class="u-email">{{ $app->contact_email }}</div>
          </td>
          <td>
            <span class="date-val">{{ $app->submitted_at ? $app->submitted_at->format('d M Y') : '—' }}</span>
          </td>
          <td>
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
          <td>
            <div class="actions">
              <a href="{{ route('admin.applications.show', $app->id) }}" class="c-btn c-btn-view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
              </a>
              @if($app->status === 'pending' || $app->status === 'under_review')
              <form method="POST" action="{{ route('admin.applications.approve', $app->id) }}" onsubmit="return handleSub(this,'Approving…')">
                @csrf
                <button type="submit" class="c-btn c-btn-approve">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  Approve
                </button>
              </form>
              <button type="button" class="c-btn c-btn-reject" onclick="openReject({{ $app->id }})">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Reject
              </button>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="empty-state">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <strong>No applications found</strong>
              <span>No NGO applications have been submitted yet.</span>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="pagination-wrap">
  {{ $applications->links('vendor.pagination.admin') }}
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

function toast(msg,type){
  var icons={
    success:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    error:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
  };
  var t=document.createElement('div');
  t.className='toast toast-'+(type==='success'?'ok':'err');
  t.innerHTML=(icons[type]||'')+'<span>'+msg+'</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
  document.getElementById('toastWrap').appendChild(t);
  setTimeout(function(){t.style.transition='opacity .3s,transform .3s';t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(function(){t.remove();},300);},4200);
}
@if(session('success')) setTimeout(function(){toast(@json(session('success')),'success');},200); @endif
@if(session('error'))   setTimeout(function(){toast(@json(session('error')),'error');},200);   @endif

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
