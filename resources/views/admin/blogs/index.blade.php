@push('page_styles')
@vite('resources/css/admin/entries/blogs.css')
<style>
@media(max-width:860px){
  .stats-grid{grid-template-columns:repeat(2,1fr)!important}
  .sec-header{flex-wrap:wrap}
  .sec-header .sec-right{width:100%;margin-top:8px}
}
@media(max-width:480px){
  .stats-grid{grid-template-columns:1fr!important}
}
@media(max-width:640px){
  .table-wrap{min-width:480px}
}
</style>
@endpush

@extends('layouts.admin')

@section('sidebar_blogs', 'active')
@section('page_title', 'Blog Posts')
@section('page_subtitle', 'Manage and review')

@section('content')
@php
  $cntPending   = $pendingCount   ?? 0;
  $cntPublished = $publishedCount ?? 0;
  $cntRejected  = $rejectedCount  ?? 0;
  $cntTotal     = $cntPending + $cntPublished + $cntRejected;
  $activeStatus = request('status', 'all');
  $activeSort   = request('sort',   'latest');
@endphp

{{-- HERO --}}
<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Content</div>
    <div class="hero-name">Blog Posts</div>
    <div class="hero-sub">Manage and review all blog posts.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-blue">Total {{ $cntTotal }}</span>
      <span class="hero-badge hb-amber">Pending {{ $cntPending }}</span>
      <span class="hero-badge hb-green">Published {{ $cntPublished }}</span>
      <span class="hero-badge hb-red">Rejected {{ $cntRejected }}</span>
    </div>
  </div>
  <div class="hero-right">
    <a href="{{ route('admin.blogs.create') }}" class="hero-btn hero-btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      New Post
    </a>
  </div>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon si-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
    </div>
    <div>
      <div class="stat-num sv-blue" id="statTotal">{{ $cntTotal }}</div>
      <div class="stat-name">Total</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div>
      <div class="stat-num sv-amber" id="statPending">{{ $cntPending }}</div>
      <div class="stat-name">Pending</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div>
      <div class="stat-num sv-green" id="statPublished">{{ $cntPublished }}</div>
      <div class="stat-name">Published</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div>
      <div class="stat-num sv-red" id="statRejected">{{ $cntRejected }}</div>
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
    <div class="search-wrap">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input class="search-input" id="searchInput" type="text" placeholder="Search blogs…" autocomplete="off">
    </div>
    <select class="sort-select" id="catFilter">
      <option value="all">All categories</option>
    </select>
    <select class="sort-select" id="sortSelect">
      <option value="latest" {{ $activeSort === 'latest' ? 'selected' : '' }}>Latest first</option>
      <option value="oldest" {{ $activeSort === 'oldest' ? 'selected' : '' }}>Oldest first</option>
      <option value="title"  {{ $activeSort === 'title'  ? 'selected' : '' }}>Title A–Z</option>
    </select>
    <div class="ftabs" id="ftabs">
      <button class="ftab {{ $activeStatus === 'all'       ? 'on' : '' }}" data-status="all"><span class="fcnt" id="fcntAll">{{ $cntTotal }}</span> All</button>
      <button class="ftab {{ $activeStatus === 'pending'   ? 'on' : '' }}" data-status="pending"><span class="fcnt" id="fcntPending">{{ $cntPending }}</span> Pending</button>
      <button class="ftab {{ $activeStatus === 'published' ? 'on' : '' }}" data-status="published"><span class="fcnt" id="fcntPublished">{{ $cntPublished }}</span> Published</button>
      <button class="ftab {{ $activeStatus === 'rejected'  ? 'on' : '' }}" data-status="rejected"><span class="fcnt" id="fcntRejected">{{ $cntRejected }}</span> Rejected</button>
    </div>
    <select class="ftab-select" data-action="ftab-select">
      <option value="all" {{ $activeStatus === 'all' ? 'selected' : '' }}>All ({{ $cntTotal }})</option>
      <option value="pending" {{ $activeStatus === 'pending' ? 'selected' : '' }}>Pending ({{ $cntPending }})</option>
      <option value="published" {{ $activeStatus === 'published' ? 'selected' : '' }}>Published ({{ $cntPublished }})</option>
      <option value="rejected" {{ $activeStatus === 'rejected' ? 'selected' : '' }}>Rejected ({{ $cntRejected }})</option>
    </select>
  </div>
</div>

<div class="bulk-bar" id="bulkBar">
  <div class="bulk-left"><strong id="bulkCount">0</strong> selected</div>
  <div class="bulk-actions">
    <button class="bb-btn bb-publish" id="bulkPublish">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Publish
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
              <div>
                <div class="title-primary" title="{{ $blog->title }}">{{ $blog->title }}</div>
                <div class="title-id">#{{ $blog->id }}</div>
              </div>
            </div>
          </td>
          <td data-label="Author">
            <div class="author-cell">
              <div class="author-av">{{ strtoupper(substr($blog->author->name ?? 'U', 0, 2)) }}</div>
              <span style="font-size:12.5px;font-weight:500;color:var(--text);">{{ $blog->author->name ?? 'Unknown' }}</span>
            </div>
          </td>
          <td data-label="Category">
            @if($catName)
              <span class="cat-tag">{{ $catName }}</span>
            @else
              <span style="color:var(--text3);font-size:12px;">—</span>
            @endif
          </td>
          <td data-label="Status">
            <span class="badge b-{{ $status }}">
              <span class="badge-dot"></span>{{ ucfirst($status) }}
            </span>
          </td>
          <td class="date-cell" data-label="Published">
            {{ $dateVal->format('d M Y') }}
            <div class="date-ago">{{ $dateVal->diffForHumans() }}</div>
          </td>
          <td data-label="Actions">
            <div class="actions">
              <a href="{{ route('admin.blogs.show', $blog) }}" class="btn btn-secondary act-btn ab-view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Review
              </a>
              <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-secondary act-btn ab-edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit
              </a>
              <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" style="display:inline;" data-confirm="Delete '{{ $blog->title }}'?">
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
          <td colspan="7">
            <div class="empty-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
              <p>No blog posts found.</p>
            </div>
          </td>
        </tr>
        @endforelse
        <tr id="noResultsRow" style="display:none;">
          <td colspan="7">
            <div class="empty-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <p>No results match your filters.</p>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="table-footer">
    <div class="tfoot-count">Showing <strong id="cntVisF">{{ $blogs->total() }}</strong> of <strong id="cntTotalF">{{ $cntTotal }}</strong> results</div>
    <div style="font-size:11px;color:var(--text3);font-family:var(--mono);">Total {{ $cntTotal }} posts</div>
  </div>
</div>

@if($blogs->hasPages())
<div class="pagination-wrap">{{ $blogs->appends(request()->query())->links('vendor.pagination.admin') }}</div>
@endif

{{-- Page data for blogs-index.js --}}
<script type="application/json" id="blogsIndexData">
@php
    $blogsIndexData = [
        'activeStatus' => $activeStatus,
        'bulkUrl'      => route('admin.blogs.bulk'),
        'approveUrl'   => route('admin.blogs.approve', '__ID__'),
        'archiveUrl'   => route('admin.blogs.archive', '__ID__'),
        'featureUrl'   => route('admin.blogs.feature', '__ID__'),
        'success'      => session('success'),
    ];
@endphp
@json($blogsIndexData)
</script>
@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/blogs-index.js')
@endpush

@push('page_styles')
@vite('resources/css/admin/pages/blogs-index.css')
<style>
@media(max-width:640px){
  .table-scroll{min-width:640px}
}
</style>
@endpush
