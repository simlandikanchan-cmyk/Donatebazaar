@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/messages.css')
@endpush


@section('sidebar_messages', 'active')
@section('page_title', 'Messages')
@section('page_subtitle', 'Manage all messages')

@section('content')
@php
  $cntTotal = $total;
  $cntRead  = $read;
  $cntNew   = $unread;
@endphp

<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Total Messages</div>
      <div class="stat-val sv-blue" id="statTotal">{{ $cntTotal }}</div>
      <div class="stat-foot">All time received</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-orange">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 5v5l3 3"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Unread</div>
      <div class="stat-val sv-orange" id="statUnread">{{ $cntNew }}</div>
      <div class="stat-foot">Need attention</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Read</div>
      <div class="stat-val sv-green" id="statRead">{{ $cntRead }}</div>
      <div class="stat-foot">Already reviewed</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-purple">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Today</div>
      <div class="stat-val sv-purple" id="statToday">{{ $today }}</div>
      <div class="stat-foot">Received today</div>
    </div>
  </div>
</div>

@if(session('success'))
<div class="alert-ok" id="flashAlert">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

<div class="sec-hdr">
  <div class="sec-ttl">All Messages</div>
  <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
    <div class="sec-search">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" id="searchInput" placeholder="Search name, email, subject…" autocomplete="off" aria-label="Search messages">
    </div>
    <div class="ftabs" id="ftabs">
      <button class="ftab on" data-filter="all">All <span class="cnt" id="cntAll">{{ $cntTotal }}</span></button>
      <button class="ftab" data-filter="new">Unread <span class="cnt" id="cntUnread">{{ $cntNew }}</span></button>
      <button class="ftab" data-filter="read">Read <span class="cnt" id="cntRead">{{ $cntRead }}</span></button>
    </div>
    <select class="ftab-select" onchange="var btn=document.querySelector('.ftab[data-filter=&quot;'+this.value+'&quot;]');if(btn)btn.click();">
      <option value="all">All ({{ $cntTotal }})</option>
      <option value="new">Unread ({{ $cntNew }})</option>
      <option value="read">Read ({{ $cntRead }})</option>
    </select>
  </div>
</div>

<div class="filter-bar">
  <div class="filter-group">
    <span class="filter-lbl">Period</span>
    <select class="filter-sel" id="filterPeriod">
      <option value="all">All time</option>
      <option value="today">Today</option>
      <option value="yesterday">Yesterday</option>
      <option value="week">This week</option>
      <option value="month">This month</option>
      <option value="custom">Custom…</option>
    </select>
  </div>

  <div class="filter-group" id="customDateGroup" style="display:none;">
    <span class="filter-lbl">From</span>
    <input type="date" class="filter-date" id="dateFrom">
    <span class="filter-lbl">To</span>
    <input type="date" class="filter-date" id="dateTo">
  </div>

  <div class="filter-div"></div>

  <div class="filter-group">
    <span class="filter-lbl">Sort</span>
    <select class="filter-sel" id="filterSort">
      <option value="newest">Newest first</option>
      <option value="oldest">Oldest first</option>
      <option value="name_az">Name A → Z</option>
      <option value="name_za">Name Z → A</option>
    </select>
  </div>

  <div class="filter-div"></div>

  <div class="filter-group">
    <span class="filter-lbl">Subject</span>
    <select class="filter-sel" id="filterSubject">
      <option value="all">Any</option>
      <option value="has">Has subject</option>
      <option value="none">No subject</option>
    </select>
  </div>

  <button class="filter-reset" id="filterReset">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    Reset
  </button>
</div>

<div class="table-bulk-bar" id="bulkBar">
  <div class="table-bulk-left"><strong id="bulkCount">0</strong> selected</div>
  <div class="table-bulk-actions">
    <x-button variant="secondary" type="button" class="bb-btn bb-read" id="bulkRead">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Mark as read
    </x-button>
    <x-button variant="destructive" type="button" class="bb-btn bb-delete" id="bulkDelete">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
      Delete
    </x-button>
    <x-button variant="secondary" type="button" class="bb-btn bb-clear" id="bulkClear">Clear</x-button>
  </div>
</div>

<div class="table-card">
  <div class="table-scroll">
    <table>
      <thead>
        <tr>
          <th class="col-check"><input type="checkbox" id="selectAll" class="row-select" aria-label="Select all"></th>
          <th>Sender</th>
          <th>Message</th>
          <th class="sortable" id="thDate">
            Date
            <span class="sort-arrows">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg>
            </span>
          </th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="tbody">
        @forelse($messages as $msg)
        @php
          $init    = strtoupper(substr($msg->name ?? 'U', 0, 1));
          $isRead  = (bool) $msg->is_read;
          $status  = $isRead ? 'read' : 'new';
          $hasSubj = !empty($msg->subject) ? 'has' : 'none';
          $srch    = strtolower(($msg->name ?? '').' '.($msg->email ?? '').' '.($msg->subject ?? '').' '.($msg->message ?? ''));
        @endphp
        <tr data-id="{{ $msg->id }}"
            data-status="{{ $status }}"
            data-search="{{ $srch }}"
            data-subject="{{ $hasSubj }}"
            data-ts="{{ $msg->created_at->timestamp }}"
            data-name="{{ strtolower($msg->name ?? '') }}"
            data-datestr="{{ $msg->created_at->format('Y-m-d') }}">
          <td class="col-check">
            <input type="checkbox" class="row-select row-check" value="{{ $msg->id }}" aria-label="Select message">
          </td>
          <td data-label="Sender">
            <div class="sender-cell">
              <div class="row-av">{{ $init }}@if(!$isRead)<span class="unread-dot"></span>@endif</div>
              <div>
                <div class="sender-name">{{ $msg->name }}</div>
                <div class="sender-email">{{ $msg->email }}</div>
              </div>
            </div>
          </td>
          <td class="msg-cell" data-label="Message">
            @if($msg->subject)
              <div class="msg-subj"><span class="subj-tag">Subject</span>{{ $msg->subject }}</div>
            @endif
            <div class="msg-prev">{{ \Illuminate\Support\Str::limit($msg->message, 140) }}</div>
          </td>
          <td class="date-cell" data-label="Date">
            {{ $msg->created_at->format('d M Y') }}
            <div class="date-ago">{{ $msg->created_at->diffForHumans() }}</div>
          </td>
          <td data-label="Status">
            <span class="badge b-{{ $status }}">
              <span class="badge-dot"></span>{{ $isRead ? 'Read' : 'New' }}
            </span>
          </td>
          <td data-label="Actions">
            <div class="actions">
              <a href="{{ route('admin.messages.show', $msg->id) }}" class="act-btn ab-view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
              </a>
              <button type="button" class="act-btn ab-toggle" data-id="{{ $msg->id }}" data-read="{{ $isRead ? '1' : '0' }}">
                @if($isRead)
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9 6 9-6"/></svg> Unread
                @else
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Read
                @endif
              </button>
              <form action="{{ route('admin.messages.delete', $msg->id) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="act-btn ab-delete">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                  Delete
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr class="empty-row">
          <td colspan="6">
            <div class="empty-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <strong>No messages yet</strong>
              <p>When users send messages they'll appear here.</p>
            </div>
          </td>
        </tr>
        @endforelse
        <tr id="noResultsRow" style="display:none;">
          <td colspan="6">
            <div class="empty-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <strong>No results found</strong>
              <p>Try adjusting your filters or search query.</p>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="table-footer">
    <div class="tfoot-info">Showing <strong id="cntVisF">{{ $messages->count() }}</strong> of <strong id="cntTotalF">{{ $cntTotal }}</strong> messages</div>
    <div class="tfoot-total">{{ $cntTotal }} total</div>
  </div>
</div>

@if($messages->lastPage() > 1)
<div class="pagination-wrap">
  @if($messages->onFirstPage())
    <span class="pg-arrow disabled">¹</span>
  @else
    <a href="{{ $messages->previousPageUrl() }}" class="pg-arrow">¹</a>
  @endif
  <div class="pg-pages">
    @for($i = 1; $i <= $messages->lastPage(); $i++)
      @if($i === $messages->currentPage())
        <span class="pg-page active">{{ $i }}</span>
      @else
        <a href="{{ $messages->url($i) }}" class="pg-page">{{ $i }}</a>
      @endif
    @endfor
  </div>
  @if($messages->hasMorePages())
    <a href="{{ $messages->nextPageUrl() }}" class="pg-arrow">›</a>
  @else
    <span class="pg-arrow disabled">›</span>
  @endif
</div>
@endif
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

var csrf   = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var bulkUrl = "{{ route('admin.messages.bulk') }}";

/* live totals (server-provided, adjusted on actions) */
var totals = {
  total:  parseInt(document.getElementById('statTotal').textContent, 10) || 0,
  unread: parseInt(document.getElementById('statUnread').textContent, 10) || 0,
  read:   parseInt(document.getElementById('statRead').textContent, 10) || 0,
  today:  parseInt(document.getElementById('statToday').textContent, 10) || 0
};

/* sidebar unread chip */
(function(){
  var chip = document.getElementById('sidebarUnread');
  if(chip && totals.unread > 0){
    chip.textContent = totals.unread;
    chip.style.display = '';
  } else if(chip){
    chip.style.display = 'none';
  }
})();

var activeFilter = 'all';
var activeSort   = 'newest';
var activePeriod = 'all';
var activeSubj   = 'all';
var dateFrom = '', dateTo = '';
var rows  = Array.from(document.querySelectorAll('#tbody tr[data-id]'));
var tbody = document.getElementById('tbody');
var noRow = document.getElementById('noResultsRow');
var bulkBar = document.getElementById('bulkBar');

function todayStr(){ return new Date().toISOString().slice(0,10); }
function yesterdayStr(){ var d=new Date(); d.setDate(d.getDate()-1); return d.toISOString().slice(0,10); }
function weekStartStr(){ var d=new Date(); d.setDate(d.getDate()-d.getDay()); return d.toISOString().slice(0,10); }
function monthStartStr(){ var d=new Date(); return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-01'; }
function getPeriodRange(p){
  var t = todayStr();
  if(p==='today')     return [t,t];
  if(p==='yesterday') return [yesterdayStr(),yesterdayStr()];
  if(p==='week')      return [weekStartStr(),t];
  if(p==='month')     return [monthStartStr(),t];
  if(p==='custom')    return [dateFrom,dateTo];
  return ['',''];
}

function applyFilters(){
  var q     = (document.getElementById('searchInput').value || '').toLowerCase().trim();
  var range = getPeriodRange(activePeriod);
  var from  = range[0], to = range[1];
  var vis   = 0;

  rows.forEach(function(r){
    var mF    = activeFilter === 'all' || r.dataset.status === activeFilter;
    var mS    = !q || (r.dataset.search || '').includes(q);
    var mSubj = activeSubj === 'all' || r.dataset.subject === activeSubj;
    var mDate = true;
    if(from && to){
      var ds = r.dataset.datestr || '';
      mDate = ds >= from && ds <= to;
    } else if(from){
      mDate = (r.dataset.datestr || '') >= from;
    } else if(to){
      mDate = (r.dataset.datestr || '') <= to;
    }
    var show = mF && mS && mSubj && mDate;
    r.classList.toggle('row-hidden', !show);
    if(show) vis++;
  });

  sortRows();

  var e2 = document.getElementById('cntVisF');
  if(e2) e2.textContent = vis;
  if(noRow) noRow.style.display = (vis === 0 && rows.length > 0) ? '' : 'none';
}

function sortRows(){
  var visible = rows.filter(function(r){ return !r.classList.contains('row-hidden'); });
  visible.sort(function(a,b){
    if(activeSort === 'newest')  return Number(b.dataset.ts) - Number(a.dataset.ts);
    if(activeSort === 'oldest')  return Number(a.dataset.ts) - Number(b.dataset.ts);
    if(activeSort === 'name_az') return (a.dataset.name||'').localeCompare(b.dataset.name||'');
    if(activeSort === 'name_za') return (b.dataset.name||'').localeCompare(a.dataset.name||'');
    return 0;
  });
  visible.forEach(function(r){ tbody.appendChild(r); });
  if(noRow) tbody.appendChild(noRow);
}

/* filter + search wiring */
document.querySelectorAll('.ftab').forEach(function(tab){
  tab.addEventListener('click', function(){
    document.querySelectorAll('.ftab').forEach(function(t){ t.classList.remove('on'); });
    this.classList.add('on');
    activeFilter = this.dataset.filter;
    applyFilters();
  });
});

var st;
document.getElementById('searchInput').addEventListener('input', function(){
  clearTimeout(st);
  st = setTimeout(applyFilters, 180);
});

document.getElementById('filterPeriod').addEventListener('change', function(){
  activePeriod = this.value;
  var g = document.getElementById('customDateGroup');
  if(this.value === 'custom'){ g.style.display = 'flex'; }
  else { g.style.display = 'none'; dateFrom = ''; dateTo = ''; }
  applyFilters();
});
document.getElementById('dateFrom').addEventListener('change', function(){ dateFrom = this.value; applyFilters(); });
document.getElementById('dateTo').addEventListener('change',   function(){ dateTo   = this.value; applyFilters(); });
document.getElementById('filterSort').addEventListener('change', function(){ activeSort = this.value; applyFilters(); });
document.getElementById('filterSubject').addEventListener('change', function(){ activeSubj = this.value; applyFilters(); });
document.getElementById('filterReset').addEventListener('click', function(){
  activeFilter = 'all'; activePeriod = 'all'; activeSort = 'newest'; activeSubj = 'all';
  dateFrom = ''; dateTo = '';
  document.getElementById('filterPeriod').value  = 'all';
  document.getElementById('filterSort').value    = 'newest';
  document.getElementById('filterSubject').value = 'all';
  document.getElementById('searchInput').value   = '';
  document.getElementById('customDateGroup').style.display = 'none';
  document.getElementById('dateFrom').value = '';
  document.getElementById('dateTo').value   = '';
  document.querySelectorAll('.ftab').forEach(function(t){ t.classList.remove('on'); });
  document.querySelector('.ftab[data-filter="all"]').classList.add('on');
  applyFilters();
});
document.getElementById('thDate').addEventListener('click', function(){
  if(activeSort === 'newest'){ activeSort = 'oldest'; }
  else if(activeSort === 'oldest'){ activeSort = 'newest'; }
  else { activeSort = 'newest'; }
  this.classList.toggle('sort-asc', activeSort === 'oldest');
  this.classList.toggle('sort-desc', activeSort === 'newest');
  document.getElementById('filterSort').value = activeSort;
  applyFilters();
});

/* selection + bulk */
function selectedIds(){
  return Array.from(document.querySelectorAll('.row-check:checked')).map(function(c){ return c.value; });
}
function syncBulkBar(){
  var ids = selectedIds();
  document.getElementById('bulkCount').textContent = ids.length;
  bulkBar.classList.toggle('show', ids.length > 0);
  var all = document.querySelectorAll('.row-check').length;
  var checked = ids.length;
  document.getElementById('selectAll').checked = all > 0 && checked === all;
  document.getElementById('selectAll').indeterminate = checked > 0 && checked < all;
}
document.getElementById('selectAll').addEventListener('change', function(){
  document.querySelectorAll('.row-check').forEach(function(c){ c.checked = document.getElementById('selectAll').checked; });
  syncBulkBar();
});
document.querySelectorAll('.row-check').forEach(function(c){
  c.addEventListener('change', syncBulkBar);
});
document.getElementById('bulkClear').addEventListener('click', function(){
  document.querySelectorAll('.row-check').forEach(function(c){ c.checked = false; });
  syncBulkBar();
});

function postBulk(action, ids, onDone){
  fetch(bulkUrl, {
    method: 'POST',
    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
    body: JSON.stringify({ ids: ids, action: action })
  })
  .then(function(r){ return r.json(); })
  .then(function(d){ if(d.ok && onDone) onDone(d); else toast('Something went wrong.', 'error'); })
  .catch(function(){ toast('Network error.', 'error'); });
}

document.getElementById('bulkRead').addEventListener('click', function(){
  var ids = selectedIds();
  if(!ids.length) return;
  postBulk('read', ids, function(){
    ids.forEach(function(id){
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(tr && tr.dataset.status === 'new'){ setRowRead(tr, true); }
    });
    syncBulkBar();
    toast(ids.length + ' message' + (ids.length===1?'':'s') + ' marked as read.', 'success');
  });
});
document.getElementById('bulkDelete').addEventListener('click', function(){
  var ids = selectedIds();
  if(!ids.length) return;
  if(!confirm('Delete ' + ids.length + ' selected message(s)?')) return;
  /* adjust totals before removing rows from the DOM */
  ids.forEach(function(id){
    var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
    if(tr && tr.dataset.status === 'new'){ totals.unread = Math.max(0, totals.unread - 1); }
    else { totals.read = Math.max(0, totals.read - 1); }
    totals.total = Math.max(0, totals.total - 1);
  });
  postBulk('delete', ids, function(d){
    ids.forEach(function(id){
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(tr) tr.remove();
    });
    rows = Array.from(document.querySelectorAll('#tbody tr[data-id]'));
    writeTotals();
    syncBulkBar();
    applyFilters();
    toast(d.msg || 'Deleted.', 'success');
  });
});

/* per-row mark read / unread toggle */
function setRowRead(tr, isRead){
  tr.dataset.status = isRead ? 'read' : 'new';
  var name = tr.querySelector('.sender-name');
  var av   = tr.querySelector('.row-av .unread-dot');
  var badge = tr.querySelector('.badge');
  var toggle = tr.querySelector('.ab-toggle');
  tr.classList.toggle('unread', !isRead);
  if(isRead && av){ av.remove(); }
  if(!isRead && !av){
    var nd = document.createElement('span'); nd.className = 'unread-dot'; tr.querySelector('.row-av').appendChild(nd);
  }
  if(badge){
    badge.className = 'badge b-' + (isRead ? 'read' : 'new');
    badge.innerHTML = '<span class="badge-dot"></span>' + (isRead ? 'Read' : 'New');
  }
  if(toggle){
    toggle.dataset.read = isRead ? '1' : '0';
    toggle.innerHTML = isRead
      ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9 6 9-6"/></svg> Unread'
      : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Read';
  }
}
function writeTotals(){
  document.getElementById('statUnread').textContent = totals.unread;
  document.getElementById('statRead').textContent   = totals.read;
  document.getElementById('statTotal').textContent  = totals.total;
  document.getElementById('cntUnread').textContent  = totals.unread;
  document.getElementById('cntRead').textContent    = totals.read;
  var chip = document.getElementById('sidebarUnread');
  if(chip){
    if(totals.unread > 0){ chip.textContent = totals.unread; chip.style.display = ''; }
    else { chip.style.display = 'none'; }
  }
}

document.querySelectorAll('.ab-toggle').forEach(function(btn){
  btn.addEventListener('click', function(e){
    e.preventDefault();
    var id = btn.dataset.id;
    var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
    if(!tr) return;
    var url = "{{ route('admin.messages.toggle-read', '__ID__') }}".replace('__ID__', id);
    fetch(url, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' }
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
      if(!d.ok) return;
      setRowRead(tr, d.is_read);
      if(d.is_read){ totals.unread = Math.max(0, totals.unread - 1); totals.read++; }
      else { totals.read = Math.max(0, totals.read - 1); totals.unread++; }
      totals.total = totals.unread + totals.read;
      writeTotals();
      toast(d.is_read ? 'Marked as read.' : 'Marked as unread.', 'success');
    })
    .catch(function(){ toast('Network error.', 'error'); });
  });
});

/* toast */
function toast(msg, type){
  var icons = {
    success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
  };
  var el = document.createElement('div');
  el.className = 'toast ' + (type === 'error' ? 'toast-err' : 'toast-ok');
  el.innerHTML = (icons[type]||icons.success) + '<span>' + msg + '</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
  document.getElementById('toastWrap').appendChild(el);
  setTimeout(function(){
    el.style.transition = 'opacity .3s,transform .3s';
    el.style.opacity = '0';
    el.style.transform = 'translateX(20px)';
    setTimeout(function(){ el.remove(); }, 300);
  }, 4200);
}

@if(session('success'))
  setTimeout(function(){ toast(@json(session('success')), 'success'); }, 200);
@endif

syncBulkBar();
applyFilters();
})();
</script>
@endpush
