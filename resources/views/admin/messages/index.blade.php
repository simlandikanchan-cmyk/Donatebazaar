@push('page_css')
@vite('resources/css/admin/entries/messages.css')
@endpush

@extends('layouts.admin')

@section('sidebar_messages', 'active')
@section('page_title', 'Messages')
@section('page_subtitle', 'Manage all messages')

@push('page_styles')
@vite('resources/css/admin/entries/messages-index.css')
<style>
@media(max-width:960px){.stats-grid{grid-template-columns:repeat(2,1fr)!important}}
@media(max-width:640px){.stats-grid{grid-template-columns:repeat(2,1fr)!important}.filter-bar{flex-wrap:wrap}.filter-bar .filter-group{width:100%;min-width:0}.filter-bar .filter-btn{width:100%;margin-top:8px}.table-scroll{min-width:520px}}
@media(max-width:480px){.stats-grid{grid-template-columns:1fr!important}}
</style>
@endpush

@section('content')
@php
  $cntTotal = $total;
  $cntRead  = $read;
  $cntNew   = $unread;
@endphp

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Messages</div>
    <div class="hero-name">Contact Messages</div>
    <div class="hero-sub">Read and manage messages sent by visitors through the contact forms.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-purple">{{ $cntTotal }} total</span>
      <span class="hero-badge hb-amber">{{ $cntNew }} unread</span>
      <span class="hero-badge hb-green">{{ $cntRead }} read</span>
      <span class="hero-badge hb-blue">{{ $today }} today</span>
    </div>
  </div>
</div>

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
<div class="flash-ok">
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
    <select class="ftab-select">
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

<div class="bulk-bar" id="bulkBar"
     data-bulk-url="{{ route('admin.messages.bulk') }}"
     data-toggle-url="{{ route('admin.messages.toggle-read', '__ID__') }}">
  <div class="bulk-left"><strong id="bulkCount">0</strong> selected</div>
  <div class="bulk-actions">
    <button class="btn btn-secondary bb-btn bb-read" id="bulkRead">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Mark as read
    </button>
    <button class="btn btn-red bb-btn bb-delete" id="bulkDelete">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
      Delete
    </button>
    <button class="btn btn-secondary bb-btn bb-clear" id="bulkClear">Clear</button>
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
              <a href="{{ route('admin.messages.show', $msg->id) }}" class="btn btn-secondary act-btn ab-view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
              </a>
              <button type="button" class="btn btn-secondary act-btn ab-toggle" data-id="{{ $msg->id }}" data-read="{{ $isRead ? '1' : '0' }}">
                @if($isRead)
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9 6 9-6"/></svg> Unread
                @else
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Read
                @endif
              </button>
              <form action="{{ route('admin.messages.delete', $msg->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this message? This cannot be undone.');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-red act-btn ab-delete" title="Delete">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
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
    <span class="pg-arrow disabled">‹</span>
  @else
    <a href="{{ $messages->previousPageUrl() }}" class="pg-arrow">‹</a>
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

@push('page_styles')
<style>
@media(max-width:860px){
  .stats-grid{grid-template-columns:repeat(2,1fr)!important}
  .sec-hdr{flex-wrap:wrap}
  .sec-hdr .sec-right{width:100%;margin-top:8px}
}
@media(max-width:480px){
  .stats-grid{grid-template-columns:1fr!important}
}
@media(max-width:640px){
  .table-wrap{min-width:480px}
  .ftabs{width:100%;order:1;margin-top:8px}
  .ftab-select{width:100%;order:2;margin-top:8px}
}
</style>
@endpush

@push('page_scripts')
@vite('resources/js/admin/entries/messages-index.js')
@endpush
