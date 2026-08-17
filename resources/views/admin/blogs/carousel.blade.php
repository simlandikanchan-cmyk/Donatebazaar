@extends('layouts.admin')

@push('page_styles')
@vite('resources/css/admin/pages/blogs-carousel.css')
@endpush


@section('sidebar_blogs', 'active')
@section('page_title', 'Blog Carousel')
@section('page_subtitle', 'Manage the featured posts shown on the blog home')

@section('content')
@if(session('success'))
<div class="flash-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="flash-error">{{ session('error') }}</div>
@endif

<div class="car-wrap">
  <div class="car-col">
    <div class="car-title">Featured Posts</div>
    <div class="car-sub">Drag to reorder or use the arrows, then save.</div>

    <div id="featuredList">
      @forelse($featured as $blog)
      <div class="featured-row feature-row" data-id="{{ $blog->id }}">
        <span class="f-pos">{{ $loop->iteration }}</span>
        <span class="f-handle" title="Drag">⠿</span>
        <div class="f-info">
          <div class="f-name">{{ $blog->title }}</div>
          <div class="f-meta">#{{ $blog->id }} · {{ $blog->author->name ?? '' }}</div>
        </div>
        <button type="button" class="f-btn f-up" aria-label="Move up">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
        </button>
        <button type="button" class="f-btn f-down" aria-label="Move down">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <form method="POST" action="{{ route('admin.blogs.feature', $blog) }}" style="display:inline;">
          @csrf
          <button type="submit" class="f-btn f-remove" aria-label="Remove from featured">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </form>
      </div>
      @empty
      <div class="empty-mini">No featured posts yet.</div>
      @endforelse
    </div>

    <div class="save-bar">
      <button type="button" class="btn btn-primary" id="saveOrder" :disabled="$featured->count() < 2">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:5px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Save Order
      </button>
      <span class="save-hint" id="saveHint">Order applies to the blog home carousel.</span>
    </div>
  </div>

  <div class="car-col">
    <div class="car-title">Eligible Posts</div>
    <div class="car-sub">Published blogs that can be added to the carousel.</div>

    @forelse($eligible as $blog)
    <div class="feature-row">
      <div class="f-info">
        <div class="f-name">{{ $blog->title }}</div>
        <div class="f-meta">#{{ $blog->id }}</div>
      </div>
      <form method="POST" action="{{ route('admin.blogs.feature', $blog) }}" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-primary btn-sm">Add</button>
      </form>
    </div>
    @empty
    <div class="empty-mini">No eligible posts.</div>
    @endforelse
  </div>
</div>
{{-- Page data for blogs-carousel.js --}}
<script type="application/json" id="blogsCarouselData">@json([
    'reorderUrl' => route('admin.blogs.carousel.reorder'),
])</script>

@endsection

@push('page_scripts')
@vite('resources/js/admin/blogs-carousel.js')
@endpush
