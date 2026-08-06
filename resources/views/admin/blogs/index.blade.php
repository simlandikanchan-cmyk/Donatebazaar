@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/blogs.css')
@endpush


@section('sidebar_blogs', 'active')
@section('page_title', 'Blog Posts')
@section('page_subtitle', 'Manage and review blog content')

@section('topbar_left')
<div class="search-wrap">
  <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
  <input class="search-input" id="searchInput" type="text" name="search" placeholder="Search blogs…" value="{{ request('search') }}" autocomplete="off">
  @if(request('search'))
  <a href="{{ route('admin.blogs.index', request()->except(['search','page'])) }}" class="search-clear" aria-label="Clear search">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
  </a>
  @endif
</div>
<x-button variant="primary" type="button" class="tb-btn">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
  <span class="notif-dot"></span>
</x-button>
<x-button variant="primary" href="{{ route('admin.blogs.create') }}">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
  New Post
</x-button>
@endsection

@section('content')
@php
  $cntPending   = $pendingCount   ?? 0;
  $cntPublished = $publishedCount ?? 0;
  $cntRejected  = $rejectedCount  ?? 0;
  $cntArchived  = $archivedCount  ?? 0;
  $cntFlagged   = $flaggedCount   ?? 0;
  $cntDraft     = $draftCount     ?? 0;
  $cntTotal     = $cntPending + $cntPublished + $cntRejected + $cntArchived + $cntFlagged + $cntDraft;
  $activeStatus = request('status', 'all');
  $activeSort   = request('sort',   'latest');
@endphp

<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg></div>
    <div class="stat-body">
      <div class="stat-lbl">Total</div>
      <div class="stat-val sv-blue" id="statTotal">{{ $cntTotal }}</div>
      <div class="stat-foot">All blog posts</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
    <div class="stat-body">
      <div class="stat-lbl">Pending</div>
      <div class="stat-val sv-amber" id="statPending">{{ $cntPending }}</div>
      <div class="stat-foot">Awaiting review</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body">
      <div class="stat-lbl">Published</div>
      <div class="stat-val sv-green" id="statPublished">{{ $cntPublished }}</div>
      <div class="stat-foot">Live on site</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body">
      <div class="stat-lbl">Rejected</div>
      <div class="stat-val sv-red" id="statRejected">{{ $cntRejected }}</div>
      <div class="stat-foot">Declined</div>
    </div>
  </div>
  @if($cntArchived || $cntFlagged)
  <div class="stat">
    <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8v13H3V8m2-4h14a2 2 0 012 2v2H3V6a2 2 0 012-2z"/></svg></div>
    <div class="stat-body">
      <div class="stat-lbl">Archived</div>
      <div class="stat-val sv-amber">{{ $cntArchived }}</div>
      <div class="stat-foot">Stored drafts</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.31 6.343l-2.12-2.12a1 1 0 00-1.41 0L4 8.12v2.47m4.31-4.25l2.12 2.12A4 4 0 016 12v4a4 4 0 004 4h2a4 4 0 004-4v-4a4 4 0 01-1.31-3.27v-.65a1 1 0 00-1-1h-.34l-2.12-2.12a1 1 0 00-1.41 0z"/></svg></div>
    <div class="stat-body">
      <div class="stat-lbl">Flagged</div>
      <div class="stat-val sv-blue">{{ $cntFlagged }}</div>
      <div class="stat-foot">Needs attention</div>
    </div>
  </div>
  @endif
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
    <select class="filter-sel" id="catFilter">
      <option value="all">All categories</option>
      @foreach($categories as $cat)
      <option value="{{ $cat->name }}" {{ request('category') === $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
      @endforeach
    </select>
    <select class="filter-sel" id="sortSelect">
      <option value="latest" {{ $activeSort === 'latest' ? 'selected' : '' }}>Latest first</option>
      <option value="oldest" {{ $activeSort === 'oldest' ? 'selected' : '' }}>Oldest first</option>
      <option value="title"  {{ $activeSort === 'title'  ? 'selected' : '' }}>Title A–Z</option>
    </select>
    <div class="ftabs" id="ftabs">
      <button class="ftab {{ $activeStatus === 'all'       ? 'on' : '' }}" data-status="all"><span class="cnt" id="fcntAll">{{ $cntTotal }}</span> All</button>
      <button class="ftab {{ $activeStatus === 'pending'   ? 'on' : '' }}" data-status="pending"><span class="cnt" id="fcntPending">{{ $cntPending }}</span> Pending</button>
      <button class="ftab {{ $activeStatus === 'published' ? 'on' : '' }}" data-status="published"><span class="cnt" id="fcntPublished">{{ $cntPublished }}</span> Published</button>
      <button class="ftab {{ $activeStatus === 'rejected'  ? 'on' : '' }}" data-status="rejected"><span class="cnt" id="fcntRejected">{{ $cntRejected }}</span> Rejected</button>
      @if($cntArchived || $cntFlagged)
      <button class="ftab {{ $activeStatus === 'archived'  ? 'on' : '' }}" data-status="archived"><span class="cnt">{{ $cntArchived }}</span> Archived</button>
      <button class="ftab {{ $activeStatus === 'flagged'   ? 'on' : '' }}" data-status="flagged"><span class="cnt">{{ $cntFlagged }}</span> Flagged</button>
      @endif
    </div>
    <select class="ftab-select" id="ftabSelect">
      <option value="all" {{ $activeStatus === 'all' ? 'selected' : '' }}>All ({{ $cntTotal }})</option>
      <option value="pending" {{ $activeStatus === 'pending' ? 'selected' : '' }}>Pending ({{ $cntPending }})</option>
      <option value="published" {{ $activeStatus === 'published' ? 'selected' : '' }}>Published ({{ $cntPublished }})</option>
      <option value="rejected" {{ $activeStatus === 'rejected' ? 'selected' : '' }}>Rejected ({{ $cntRejected }})</option>
      @if($cntArchived || $cntFlagged)
      <option value="archived" {{ $activeStatus === 'archived' ? 'selected' : '' }}>Archived ({{ $cntArchived }})</option>
      <option value="flagged" {{ $activeStatus === 'flagged' ? 'selected' : '' }}>Flagged ({{ $cntFlagged }})</option>
      @endif
    </select>
  </div>
</div>

<div class="table-bulk-bar" id="bulkBar">
  <div class="table-bulk-inner">
    <div class="table-bulk-left"><strong id="bulkCount">0</strong> selected</div>
    <div class="table-bulk-actions">
      <x-button variant="primary" type="button" class="bb-btn bb-publish" id="bulkPublish">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Publish
      </x-button>
      <x-button variant="destructive" type="button" class="bb-btn bb-delete" id="bulkDelete">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 01-1.995 1.858L6 17l-1-14H4"/></svg>
        Delete
      </x-button>
      <x-button variant="secondary" type="button" class="bb-btn bb-clear" id="bulkClear">Clear</x-button>
    </div>
  </div>
</div>

<div class="table-card">
  <div class="table-scroll">
    <table>
      <thead>
        <tr>
          <th class="col-check"><input type="checkbox" id="selectAll" class="row-select" aria-label="Select all"></th>
          <th>Post</th>
          <th>Author</th>
          <th>Category</th>
          <th>Status</th>
          <th>Published</th>
          <th class="th-actions">Actions</th>
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
          $dateVal = ($status === 'published' && $blog->published_at) ? $blog->published_at : $blog->created_at;
        @endphp
        <tr data-id="{{ $blog->id }}"
            data-status="{{ $status }}"
            data-category="{{ $catName ?? '' }}"
            data-search="{{ $srch }}">
          <td class="col-check">
            <input type="checkbox" class="row-select row-check" value="{{ $blog->id }}" aria-label="Select post">
          </td>
          <td data-label="Post">
            <div class="title-cell">
              <div class="blog-thumb">
                @if(!empty($blog->cover_image))
                  <img src="{{ $blog->cover_image_url ?? asset('storage/'.$blog->cover_image) }}" alt="{{ $blog->title }}">
                @else
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                @endif
              </div>
              <div class="title-info">
                <div class="title-primary" title="{{ $blog->title }}">{{ \Illuminate\Support\Str::limit($blog->title ?? 'Untitled', 60) }}</div>
                <div class="title-id">#{{ $blog->id }}</div>
              </div>
            </div>
          </td>
          <td data-label="Author">
            <div class="author-cell">
              @if($blog->author->avatar)
                <img class="author-img" src="{{ asset('storage/'.$blog->author->avatar) }}" alt="{{ $blog->author->name }}">
              @else
                <div class="author-av">{{ strtoupper(substr($blog->author->name ?? 'U', 0, 2)) }}</div>
              @endif
              <span class="author-name">{{ $blog->author->name ?? 'Unknown' }}</span>
            </div>
          </td>
          <td data-label="Category">
            @if($catName)
              <span class="cat-tag">{{ $catName }}</span>
            @else
              <span class="cell-muted">—</span>
            @endif
          </td>
          <td data-label="Status">
            <span class="badge b-{{ $status }}">
              <span class="badge-dot"></span>{{ $blog->readable_status ?? ucfirst($status) }}
            </span>
          </td>
          <td class="date-cell" data-label="Published">
            {{ $dateVal->format('d M Y') }}
            <div class="date-ago">{{ $dateVal->diffForHumans() }}</div>
          </td>
          <td data-label="Actions">
            <div class="actions">
              <x-button variant="secondary" href="{{ route('admin.blogs.show', $blog) }}" class="ab-view ab-view-compact">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span class="btn-label">View</span>
              </x-button>
              <x-button variant="secondary" href="{{ route('admin.blogs.edit', $blog) }}" class="ab-edit ab-edit-compact">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/><path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                <span class="btn-label">Edit</span>
              </x-button>
              @if($status === 'pending')
              <x-button variant="secondary" type="button" class="ab-approve js-approve" data-id="{{ $blog->id }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="btn-label">Approve</span>
              </x-button>
              @elseif($status === 'published')
              <x-button variant="secondary" type="button" class="ab-archive js-archive" data-id="{{ $blog->id }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8v13H3V8m2-4h14a2 2 0 012 2v2H3V6a2 2 0 012-2z"/></svg>
                <span class="btn-label">Archive</span>
              </x-button>
              @endif
              <x-button variant="secondary" type="button" class="ab-feature js-feature" data-id="{{ $blog->id }}" data-featured="{{ $blog->is_featured ? '1' : '0' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span class="btn-label">{{ $blog->is_featured ? 'Unfeature' : 'Feature' }}</span>
              </x-button>
              <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" style="display:inline;" onsubmit="return confirm('Delete \'{{ addslashes($blog->title) }}\'?')">
                @csrf @method('DELETE')
                <x-button variant="destructive" type="submit" class="ab-delete ab-delete-compact" aria-label="Delete">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </x-button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr class="empty-row">
          <td colspan="7">
            <div class="empty-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
              <p>No blog posts found.</p>
              <a href="{{ route('admin.blogs.create') }}" class="empty-cta">Create your first post</a>
            </div>
          </td>
        </tr>
        @endforelse
        <tr id="noResultsRow" style="display:none;">
          <td colspan="7">
            <div class="empty-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <p>No results match your filters.</p>
              <button class="clear-filters-btn" type="button">Clear all filters</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="table-footer">
    <div class="tfoot-count">Showing <strong id="cntVisF">{{ $blogs->count() }}</strong> of <strong id="cntTotalF">{{ $blogs->total() }}</strong> results</div>
    <div class="tfoot-total">Total {{ $blogs->total() }} posts</div>
  </div>
</div>

@if($blogs->hasPages())
<div class="pagination-wrap">
  <div class="pagination-info">
    Page {{ $blogs->currentPage() }} of {{ $blogs->lastPage() }}
  </div>
  {{ $blogs->appends(request()->query())->links('vendor.pagination.admin') }}
</div>
@endif
@endsection

@push('page_scripts')
<script>
(function(){
  'use strict';

  var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  var counts = {
    total:     parseInt(document.getElementById('statTotal').textContent, 10) || 0,
    pending:   parseInt(document.getElementById('statPending').textContent, 10) || 0,
    published: parseInt(document.getElementById('statPublished').textContent, 10) || 0,
    rejected:  parseInt(document.getElementById('statRejected').textContent, 10) || 0
  };
  var activeStatus = '{{ $activeStatus }}';

  function writeCounts(){
    document.getElementById('statTotal').textContent     = counts.total;
    document.getElementById('statPending').textContent   = counts.pending;
    document.getElementById('statPublished').textContent = counts.published;
    document.getElementById('statRejected').textContent  = counts.rejected;
    document.getElementById('fcntAll').textContent       = counts.total;
    document.getElementById('fcntPending').textContent   = counts.pending;
    document.getElementById('fcntPublished').textContent = counts.published;
    document.getElementById('fcntRejected').textContent  = counts.rejected;
  }

  function toast(msg, type){
    var icons = {
      success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
      error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
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

  var rows = Array.from(document.querySelectorAll('#tbody tr[data-id]'));
  var noRow = document.getElementById('noResultsRow');
  var bulkBar = document.getElementById('bulkBar');

  /* client-side filter for rows on current page (category + search match) */
  function applyFilters(){
    var q   = (document.getElementById('searchInput').value || '').toLowerCase().trim();
    var cat = document.getElementById('catFilter').value;
    var vis = 0;
    rows.forEach(function(r){
      var mS  = !q   || (r.dataset.search || '').includes(q);
      var mC  = cat === 'all' || (r.dataset.category || '') === cat;
      var show = mS && mC;
      r.classList.toggle('row-hidden', !show);
      if(show) vis++;
    });
    var e = document.getElementById('cntVisF');
    if(e) e.textContent = vis;
    if(noRow) noRow.style.display = (vis === 0 && rows.length > 0) ? '' : 'none';
  }

  /* server-side search with debounce */
  var _st;
  document.getElementById('searchInput').addEventListener('input', function(){
    clearTimeout(_st);
    _st = setTimeout(function(){
      var url = new URL(window.location.href);
      var v = document.getElementById('searchInput').value.trim();
      if(v) url.searchParams.set('search', v); else url.searchParams.delete('search');
      url.searchParams.set('page', 1);
      window.location.href = url.toString();
    }, 500);
  });

  /* category filter → server-side */
  document.getElementById('catFilter').addEventListener('change', function(){
    var url = new URL(window.location.href);
    if(this.value !== 'all') url.searchParams.set('category', this.value); else url.searchParams.delete('category');
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
  });

  /* status tabs */
  document.querySelectorAll('.ftab').forEach(function(tab){
    tab.addEventListener('click', function(){
      var url = new URL(window.location.href);
      url.searchParams.set('status', this.dataset.status);
      url.searchParams.set('page', 1);
      window.location.href = url.toString();
    });
  });

  /* sort select */
  document.getElementById('sortSelect').addEventListener('change', function(){
    var url = new URL(window.location.href);
    url.searchParams.set('sort', this.value);
    url.searchParams.set('status', '{{ $activeStatus }}');
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
  });

  /* mobile status select */
  document.getElementById('ftabSelect').addEventListener('change', function(){
    var url = new URL(window.location.href);
    url.searchParams.set('status', this.value);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
  });

  /* clear all filters */
  var _cf = document.querySelector('.clear-filters-btn');
  if(_cf) _cf.addEventListener('click', function(){
    var url = new URL(window.location.href);
    url.searchParams.delete('status');
    url.searchParams.delete('search');
    url.searchParams.delete('category');
    url.searchParams.delete('sort');
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
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
    fetch("{{ route('admin.blogs.bulk') }}", {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
      body: JSON.stringify({ ids: ids, action: action })
    })
    .then(function(r){ return r.json(); })
    .then(function(d){ if(d.ok && onDone) onDone(d); else toast('Something went wrong.', 'error'); })
    .catch(function(){ toast('Network error.', 'error'); });
  }

  document.getElementById('bulkPublish').addEventListener('click', function(){
    var ids = selectedIds();
    if(!ids.length) return;
    postBulk('publish', ids, function(d){
      ids.forEach(function(id){
        var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
        if(!tr) return;
        var old = tr.dataset.status;
        setRowStatus(tr, 'published', false);
        counts.published++;
        if(old === 'pending') counts.pending = Math.max(0, counts.pending - 1);
        else if(old === 'rejected') counts.rejected = Math.max(0, counts.rejected - 1);
        else if(old !== 'published') counts.total++;
      });
      writeCounts();
      syncBulkBar();
      toast(d.msg || 'Published.', 'success');
    });
  });

  document.getElementById('bulkDelete').addEventListener('click', function(){
    var ids = selectedIds();
    if(!ids.length) return;
    if(!confirm('Delete ' + ids.length + ' selected post(s)?')) return;
    ids.forEach(function(id){
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(tr){
        if(tr.dataset.status === 'pending') counts.pending = Math.max(0, counts.pending - 1);
        else if(tr.dataset.status === 'published') counts.published = Math.max(0, counts.published - 1);
        else if(tr.dataset.status === 'rejected') counts.rejected = Math.max(0, counts.rejected - 1);
        counts.total = Math.max(0, counts.total - 1);
      }
    });
    postBulk('delete', ids, function(d){
      ids.forEach(function(id){
        var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
        if(tr) tr.remove();
      });
      rows = Array.from(document.querySelectorAll('#tbody tr[data-id]'));
      writeCounts();
      syncBulkBar();
      toast(d.msg || 'Deleted.', 'success');
    });
  });

  /* status / featured helpers */
  function setRowStatus(tr, status, featured){
    tr.dataset.status = status;
    var badge = tr.querySelector('.badge');
    if(badge){
      badge.className = 'badge b-' + status;
      badge.innerHTML = '<span class="badge-dot"></span>' + (status.charAt(0).toUpperCase() + status.slice(1));
    }
    if(typeof featured !== 'undefined'){
      var fBtn = tr.querySelector('.js-feature');
      if(fBtn){
        fBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> <span class="btn-label">' + (featured ? 'Unfeature' : 'Feature') + '</span>';
      }
    }
  }

  function ajaxAction(url, tr, onOk){
    fetch(url, { method:'POST', headers:{ 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' } })
    .then(function(r){ return r.json(); })
    .then(function(d){
      if(!d.ok){ toast((d.message)||'Action failed.', 'error'); return; }
      if(onOk) onOk(d);
    })
    .catch(function(){ toast('Network error.', 'error'); });
  }

  document.querySelectorAll('.js-approve').forEach(function(btn){
    btn.addEventListener('click', function(){
      var id = btn.dataset.id;
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(!tr) return;
      ajaxAction("{{ route('admin.blogs.approve', '__ID__') }}".replace('__ID__', id), tr, function(d){
        setRowStatus(tr, 'published', false);
        counts.pending = Math.max(0, counts.pending - 1);
        counts.published++;
        writeCounts();
        toast(d.message || 'Approved.', 'success');
      });
    });
  });

  document.querySelectorAll('.js-archive').forEach(function(btn){
    btn.addEventListener('click', function(){
      var id = btn.dataset.id;
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(!tr) return;
      ajaxAction("{{ route('admin.blogs.archive', '__ID__') }}".replace('__ID__', id), tr, function(d){
        setRowStatus(tr, 'archived', false);
        counts.published = Math.max(0, counts.published - 1);
        counts.total = Math.max(0, counts.total - 1);
        writeCounts();
        toast(d.message || 'Archived.', 'success');
      });
    });
  });

  document.querySelectorAll('.js-feature').forEach(function(btn){
    btn.addEventListener('click', function(){
      var id = btn.dataset.id;
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(!tr) return;
      ajaxAction("{{ route('admin.blogs.feature', '__ID__') }}".replace('__ID__', id), tr, function(d){
        var featured = !!(d.is_featured);
        setRowStatus(tr, tr.dataset.status, featured);
        toast(d.message || (featured ? 'Featured.' : 'Unfeatured.'), 'success');
      });
    });
  });

  syncBulkBar();
  applyFilters();
})();
</script>
@endpush
