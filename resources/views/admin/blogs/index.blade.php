@extends('layouts.admin')

@section('sidebar_blogs', 'active')
@section('page_title', 'Blog Posts')
@section('page_subtitle', 'Manage and review')

@section('topbar_left')
<div class="search-wrap">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
  <input class="search-input" id="searchInput" type="text" placeholder="Search blogs…" autocomplete="off">
</div>
<button class="tb-btn" title="Notifications">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
  <span class="notif-dot"></span>
</button>
<a href="{{ route('admin.blogs.create') }}" class="btn-create">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
  New Post
</a>
@endsection

@push('page_styles')
<style>
/* ── index page-specific ── */
.search-wrap{position:relative;width:230px;}
.search-wrap svg{position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;}
.search-input{width:100%;height:36px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:0 12px 0 32px;font-size:12px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.search-input::placeholder{color:var(--text3);}
.search-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.btn-create{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 16px;background:var(--a);color:#fff;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;border:none;transition:opacity var(--ease),box-shadow var(--ease);font-family:var(--font);text-decoration:none;white-space:nowrap;}
.btn-create:hover{opacity:.88;box-shadow:0 4px 14px rgba(110,86,247,.35);}
.btn-create svg{width:13px;height:13px;}
.sort-select{height:34px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-xs);padding:0 28px 0 10px;font-size:11.5px;color:var(--text2);font-family:var(--font);outline:none;cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;transition:border-color var(--ease);}
.sort-select:focus{border-color:var(--a);}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;box-shadow:var(--sh);display:flex;align-items:center;gap:14px;transition:transform var(--ease),box-shadow var(--ease);animation:fadeUp .4s ease both;}
.stat-card:hover{transform:translateY(-2px);box-shadow:var(--sh-md);}
.stat-card:nth-child(1){animation-delay:.05s}.stat-card:nth-child(2){animation-delay:.10s}.stat-card:nth-child(3){animation-delay:.15s}.stat-card:nth-child(4){animation-delay:.20s}
.si-red{background:var(--red-lt);color:var(--red);}
.sv-blue{color:var(--blue);}.sv-amber{color:var(--amber);}.sv-green{color:var(--green);}.sv-red{color:var(--red);}
.stat-num{font-family:var(--mono);font-size:1.9rem;font-weight:800;line-height:1;letter-spacing:-.03em;}
.stat-name{font-size:10px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:.07em;margin-top:3px;}
.flash-success{background:var(--green-lt);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:11px 14px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
[data-theme="dark"] .flash-success{color:var(--green);}
.sec-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px;animation:fadeUp .4s .2s ease both;}
.sec-title{font-family:var(--mono);font-size:15px;font-weight:800;color:var(--text);letter-spacing:-.02em;}
.sec-right{display:flex;align-items:center;gap:8px;}
.fcnt{display:inline-flex;align-items:center;justify-content:center;min-width:16px;height:16px;border-radius:100px;font-size:9.5px;padding:0 3px;background:var(--a-lt);color:var(--a);font-weight:700;font-family:var(--mono);}
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .25s ease both;}
.table-scroll{overflow-x:auto;}
.table-scroll::-webkit-scrollbar{height:5px;}
.table-scroll::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
table{width:100%;min-width:860px;border-collapse:collapse;}
thead tr{border-bottom:2px solid var(--border);}
thead th{padding:11px 14px;text-align:left;font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);background:var(--surface2);white-space:nowrap;}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
tbody tr.row-hidden{display:none;}
tbody td{padding:13px 14px;font-size:13px;color:var(--text2);vertical-align:middle;}
.title-cell{display:flex;align-items:center;gap:10px;}
.blog-thumb{width:44px;height:34px;border-radius:7px;overflow:hidden;flex-shrink:0;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;}
.blog-thumb img{width:100%;height:100%;object-fit:cover;}
.blog-thumb svg{width:14px;height:14px;color:var(--border2);}
.title-primary{font-size:13px;font-weight:600;color:var(--text);line-height:1.35;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:260px;}
.title-id{font-size:10px;color:var(--text3);font-family:var(--mono);margin-top:1px;}
.author-cell{display:flex;align-items:center;gap:7px;}
.author-av{width:26px;height:26px;border-radius:7px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);}
.b-pending{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.25);}
.b-published{background:var(--green-lt);color:#065f46;border:1px solid rgba(5,196,138,.25);}
.b-rejected{background:var(--red-lt);color:#991b1b;border:1px solid rgba(240,68,68,.25);}
.b-draft{background:var(--surface3);color:var(--text3);border:1px solid var(--border2);}
.b-archived{background:var(--blue-lt);color:#1e40af;border:1px solid rgba(59,130,246,.25);}
.b-flagged{background:#fdf2f8;color:#9d174d;border:1px solid rgba(236,72,153,.25);}
[data-theme="dark"] .b-pending{color:var(--amber);}
[data-theme="dark"] .b-published{color:var(--green);}
[data-theme="dark"] .b-rejected{color:var(--red);}
[data-theme="dark"] .b-draft{color:var(--text2);}
[data-theme="dark"] .b-archived{color:var(--blue);}
[data-theme="dark"] .b-flagged{color:#f9a8d4;}
.cat-tag{display:inline-block;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:500;background:var(--a-lt);color:var(--a);border:1px solid rgba(110,86,247,.18);}
td.date-cell{font-family:var(--mono);font-size:11.5px;color:var(--text3);}
.date-ago{font-size:10.5px;margin-top:2px;}
.actions{display:flex;align-items:center;gap:5px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:11px;font-weight:600;cursor:pointer;border:1px solid transparent;transition:opacity var(--ease),transform var(--ease);white-space:nowrap;font-family:var(--font);text-decoration:none;}
.act-btn:hover{opacity:.82;transform:scale(.97);}
.act-btn svg{width:11px;height:11px;}
.ab-view{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.2);}
.ab-edit{background:var(--amber-lt);color:var(--amber);border-color:rgba(245,158,11,.2);}
.ab-delete{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.2);padding:5px 8px;}
.empty-row td{padding:56px 20px;text-align:center;}
.empty-wrap{display:flex;flex-direction:column;align-items:center;gap:8px;color:var(--text3);}
.empty-wrap svg{width:40px;height:40px;opacity:.25;}
.empty-wrap p{font-size:13px;}
.table-footer{display:flex;align-items:center;justify-content:space-between;padding:11px 16px;border-top:1px solid var(--border);background:var(--surface2);}
.tfoot-count{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.tfoot-count strong{color:var(--text);font-weight:600;}
</style>
@endpush
@section('content')
@php
  $cntPending   = $pendingCount   ?? 0;
  $cntPublished = $publishedCount ?? 0;
  $cntRejected  = $rejectedCount  ?? 0;
  $cntTotal     = $cntPending + $cntPublished + $cntRejected;
  $activeStatus = request('status', 'all');
  $activeSort   = request('sort',   'latest');
@endphp

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon si-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
    </div>
    <div>
      <div class="stat-num sv-blue">{{ $cntTotal }}</div>
      <div class="stat-name">Total</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div>
      <div class="stat-num sv-amber">{{ $cntPending }}</div>
      <div class="stat-name">Pending</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div>
      <div class="stat-num sv-green">{{ $cntPublished }}</div>
      <div class="stat-name">Published</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div>
      <div class="stat-num sv-red">{{ $cntRejected }}</div>
      <div class="stat-name">Rejected</div>
    </div>
  </div>
</div>

@if(session('success'))
<div class="flash-success">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

<div class="sec-header">
  <div class="sec-title">All Blog Posts</div>
  <div class="sec-right">
    <select class="sort-select" id="sortSelect">
      <option value="latest" {{ $activeSort === 'latest' ? 'selected' : '' }}>Latest first</option>
      <option value="oldest" {{ $activeSort === 'oldest' ? 'selected' : '' }}>Oldest first</option>
      <option value="title"  {{ $activeSort === 'title'  ? 'selected' : '' }}>Title A–Z</option>
    </select>
    <div class="ftabs" id="ftabs">
      <button class="ftab {{ $activeStatus === 'all'       ? 'on' : '' }}" data-status="all">All <span class="fcnt">{{ $cntTotal }}</span></button>
      <button class="ftab {{ $activeStatus === 'pending'   ? 'on' : '' }}" data-status="pending">Pending <span class="fcnt">{{ $cntPending }}</span></button>
      <button class="ftab {{ $activeStatus === 'published' ? 'on' : '' }}" data-status="published">Published <span class="fcnt">{{ $cntPublished }}</span></button>
      <button class="ftab {{ $activeStatus === 'rejected'  ? 'on' : '' }}" data-status="rejected">Rejected <span class="fcnt">{{ $cntRejected }}</span></button>
    </div>
  </div>
</div>

<div class="table-card">
  <div class="table-scroll">
    <table>
      <thead>
        <tr>
          <th>Post</th>
          <th>Author</th>
          <th>Category</th>
          <th>Status</th>
          <th>Published</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="tbody">
        @forelse($blogs as $blog)
        @php
          $status = $blog->status ?? 'draft';
          $catName = null;
          if (!empty($blog->category)) {
            if (is_string($blog->category)) {
              $dec = json_decode($blog->category);
              $catName = (json_last_error() === JSON_ERROR_NONE && isset($dec->name)) ? $dec->name : $blog->category;
            } elseif (is_object($blog->category)) {
              $catName = $blog->category->name ?? null;
            } elseif (is_array($blog->category)) {
              $catName = $blog->category['name'] ?? null;
            }
          }
          $srch = strtolower(($blog->title ?? '') . ' ' . ($blog->author->name ?? '') . ' ' . ($catName ?? '') . ' ' . $status);
        @endphp
        <tr data-status="{{ $status }}" data-search="{{ $srch }}">
          <td>
            <div class="title-cell">
              <div class="blog-thumb">
                @if(!empty($blog->cover_image))
                  <img src="{{ $blog->cover_image_url ?? asset('storage/'.$blog->cover_image) }}" alt="{{ $blog->title }}">
                @else
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                @endif
              </div>
              <div>
                <div class="title-primary" title="{{ $blog->title }}">{{ $blog->title }}</div>
                <div class="title-id">#{{ $blog->id }}</div>
              </div>
            </div>
          </td>
          <td>
            <div class="author-cell">
              <div class="author-av">{{ strtoupper(substr($blog->author->name ?? 'U', 0, 2)) }}</div>
              <span style="font-size:12.5px;font-weight:500;color:var(--text);">{{ $blog->author->name ?? 'Unknown' }}</span>
            </div>
          </td>
          <td>
            @if($catName)
              <span class="cat-tag">{{ $catName }}</span>
            @else
              <span style="color:var(--text3);font-size:12px;">—</span>
            @endif
          </td>
          <td>
            <span class="badge b-{{ $status }}">
              <span class="badge-dot"></span>{{ ucfirst($status) }}
            </span>
          </td>
          <td class="date-cell">
            {{ $blog->created_at->format('d M Y') }}
            <div class="date-ago">{{ $blog->created_at->diffForHumans() }}</div>
          </td>
          <td>
            <div class="actions">
              <a href="{{ route('admin.blogs.show', $blog) }}" class="act-btn ab-view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Review
              </a>
              <a href="{{ route('admin.blogs.edit', $blog) }}" class="act-btn ab-edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit
              </a>
              <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" style="display:inline;" onsubmit="return confirm('Delete \'{{ addslashes($blog->title) }}\'?')">
                @csrf @method('DELETE')
                <button type="submit" class="act-btn ab-delete" title="Delete">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr class="empty-row">
          <td colspan="6">
            <div class="empty-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
              <p>No blog posts found.</p>
            </div>
          </td>
        </tr>
        @endforelse
        <tr id="noResultsRow" style="display:none;">
          <td colspan="6">
            <div class="empty-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <p>No results match your search.</p>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="table-footer">
    <div class="tfoot-count">Showing <strong id="cntVisF">{{ $blogs->total() }}</strong> of <strong>{{ $cntTotal }}</strong> results</div>
    <div style="font-size:11px;color:var(--text3);font-family:var(--mono);">Total {{ $cntTotal }} posts</div>
  </div>
</div>

@if($blogs->hasPages())
<div class="pagination-wrap">{{ $blogs->appends(request()->query())->links('vendor.pagination.admin') }}</div>
@endif
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

var rows = Array.from(document.querySelectorAll('#tbody tr[data-status]'));
var noRow = document.getElementById('noResultsRow');

function applySearch(){
  var q = document.getElementById('searchInput').value.toLowerCase().trim();
  var vis = 0;
  rows.forEach(function(r){
    var show = !q || (r.dataset.search || '').includes(q);
    r.classList.toggle('row-hidden', !show);
    if(show) vis++;
  });
  var e2 = document.getElementById('cntVisF');
  if(e2) e2.textContent = vis;
  if(noRow) noRow.style.display = (vis === 0 && rows.length > 0) ? '' : 'none';
}
applySearch();

var st;
document.getElementById('searchInput').addEventListener('input', function(){
  clearTimeout(st);
  st = setTimeout(applySearch, 180);
});

document.querySelectorAll('.ftab').forEach(function(tab){
  tab.addEventListener('click', function(){
    var url = new URL(window.location.href);
    url.searchParams.set('status', this.dataset.status);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
  });
});

document.getElementById('sortSelect').addEventListener('change', function(){
  var url = new URL(window.location.href);
  url.searchParams.set('sort', this.value);
  url.searchParams.set('status', '{{ $activeStatus }}');
  url.searchParams.set('page', 1);
  window.location.href = url.toString();
});

})();
</script>
@endpush
