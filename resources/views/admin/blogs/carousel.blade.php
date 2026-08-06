@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/blogs.css')
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
        <x-button variant="primary" type="button" class="f-btn f-up">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
        </x-button>
        <x-button variant="primary" type="button" class="f-btn f-down">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </x-button>
        <form method="POST" action="{{ route('admin.blogs.feature', $blog) }}" style="display:inline;">
          @csrf
          <x-button variant="primary" type="submit" class="f-btn f-remove">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </x-button>
        </form>
      </div>
      @empty
      <div class="empty-mini">No featured posts yet.</div>
      @endforelse
    </div>

    <div class="save-bar">
      <x-button type="button" variant="primary" id="saveOrder" :disabled="$featured->count() < 2">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:5px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Save Order
      </x-button>
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
        <x-button type="submit" variant="primary" size="sm">Add</x-button>
      </form>
    </div>
    @empty
    <div class="empty-mini">No eligible posts.</div>
    @endforelse
  </div>
</div>
@endsection

@push('page_scripts')
<script>
(function(){
  'use strict';

  var list = document.getElementById('featuredList');
  var rows = Array.prototype.slice.call(list.querySelectorAll('.feature-row'));
  if (rows.length < 2) return;

  var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var saveBtn = document.getElementById('saveOrder');
  var hint = document.getElementById('saveHint');

  function renumber(){
    rows.forEach(function(row, i){
      row.querySelector('.f-pos').textContent = i + 1;
      row.querySelector('.f-up').disabled = (i === 0);
      row.querySelector('.f-down').disabled = (i === rows.length - 1);
    });
  }

  function swap(a, b){
    if (a < 0 || b < 0 || a >= rows.length || b >= rows.length || a === b) return;
    var na = rows[a], nb = rows[b];
    if (a < b) list.insertBefore(nb, na);
    else list.insertBefore(na, nb);
    var t = rows[a]; rows[a] = rows[b]; rows[b] = t;
    renumber();
  }

  list.addEventListener('click', function(e){
    var btn = e.target.closest('.f-up, .f-down');
    if (!btn) return;
    var row = btn.closest('.feature-row');
    var i = rows.indexOf(row);
    if (btn.classList.contains('f-up')) swap(i - 1, i);
    else swap(i, i + 1);
  });

  var dragId = null;
  list.addEventListener('dragstart', function(e){
    var row = e.target.closest('.feature-row');
    if (!row) return;
    dragId = rows.indexOf(row);
    row.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
  });
  list.addEventListener('dragover', function(e){
    e.preventDefault();
    var row = e.target.closest('.feature-row');
    if (!row || dragId === null) return;
    var overId = rows.indexOf(row);
    if (overId !== dragId && overId !== dragId + 1) swap(dragId, overId);
  });
  list.addEventListener('dragend', function(e){
    var row = e.target.closest('.feature-row');
    if (row) row.classList.remove('dragging');
    dragId = null;
  });

  saveBtn.addEventListener('click', function(){
    var order = rows.map(function(row){ return row.dataset.id; });
    saveBtn.disabled = true;
    fetch("{{ route('admin.blogs.carousel.reorder') }}", {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: JSON.stringify({ order: order })
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
      if (d.success) {
        hint.textContent = 'Order saved.';
        hint.style.color = 'var(--green)';
      } else {
        hint.textContent = 'Save failed.';
        hint.style.color = 'var(--red)';
      }
      saveBtn.disabled = false;
    })
    .catch(function(){
      hint.textContent = 'Network error.';
      hint.style.color = 'var(--red)';
      saveBtn.disabled = false;
    });
  });

  renumber();
})();
</script>
@endpush
