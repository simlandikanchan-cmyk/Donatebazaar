{{-- resources/views/admin/applications/index.blade.php --}}
@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/applications.css')
@endpush


@section('sidebar_applications', 'active')
@section('page_title', 'NGO Applications')
@section('page_subtitle', 'Review and manage NGO onboarding applications')

@section('content')

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
<div class="table-card apps-table">
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
            <a href="{{ route('admin.applications.show', $app->id) }}" class="btn btn-secondary c-btn c-btn-view" title="View details">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              View
            </a>
            @if($app->status === 'pending' || $app->status === 'under_review')
            <form method="POST" action="{{ route('admin.applications.approve', $app->id) }}" onsubmit="return handleSub(this,'Approving…')" style="display:inline">
              @csrf
              <x-button variant="primary" type="submit" class="c-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Approve
              </x-button>
            </form>
            <x-button variant="destructive" type="button" class="c-btn" onclick="openReject({{ $app->id }})" title="Reject">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
              Reject
            </x-button>
            @endif
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
      <textarea id="rejectReason" name="rejection_reason" rows="3" placeholder="Or type a custom reason…" class="modal-ta"></textarea>
      <p id="rejectErr" class="modal-err">⚠ Please provide a reason before rejecting.</p>
      <div class="modal-acts">
        <x-button variant="secondary" type="button" class="modal-btn">Cancel</x-button>
        <x-button variant="destructive" type="submit" class="modal-btn">✕ Reject Application</x-button>
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

/* —€—€ CLIENT-SIDE FILTER + SEARCH —€—€ */
var rows   = Array.from(document.querySelectorAll('#tbody tr[data-id]'));
var noRow  = document.querySelector('#tbody .empty-state');
var activeFilter = 'all';

function applyFilter(){
  var q = (document.getElementById('searchInput').value || '').toLowerCase().trim();
  var vis = 0;
  rows.forEach(function(r){
    var mF = activeFilter === 'all' || r.dataset.status === activeFilter;
    var mS = !q || (r.dataset.search || '').includes(q);
    r.style.display = (mF && mS) ? '' : 'none';
    if(mF && mS) vis++;
  });
  var empty = document.querySelector('#tbody tr.empty-filter');
  if(vis === 0 && rows.length > 0){
    if(!empty){
      empty = document.createElement('tr'); empty.className = 'empty-filter';
      empty.innerHTML = '<td colspan="8"><div class="empty-state"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg><strong>No results found</strong><p>Try adjusting your filters or search query.</p></div></td>';
      document.getElementById('tbody').appendChild(empty);
    }
    empty.style.display = '';
  } else if(empty){
    empty.style.display = 'none';
  }
}

/* Filter tabs */
document.querySelectorAll('.ftab').forEach(function(tab){
  tab.addEventListener('click', function(){
    document.querySelectorAll('.ftab').forEach(function(t){ t.classList.remove('on'); });
    this.classList.add('on');
    activeFilter = this.dataset.filter;
    applyFilter();
  });
});

/* Search input */
var st;
document.getElementById('searchInput').addEventListener('input', function(){
  clearTimeout(st);
  st = setTimeout(applyFilter, 180);
});

applyFilter();
})();
</script>
@endpush
