@extends('layouts.user')

@section('page_title', 'My Blogs')

@section('content')
@php
    $blogTotal     = $blogs->count();
    $blogPublished = $blogs->where('status', 'published')->count();
    $blogDraft     = $blogs->where('status', 'draft')->count();
    $blogPending   = $blogs->where('status', 'pending')->count();
    $blogRejected  = $blogs->where('status', 'rejected')->count();
@endphp

<div class="page-hdr">
    <div>
        <div class="page-hdr-title">My Blogs</div>
        <div class="page-hdr-sub" id="subLabel">{{ $blogTotal }} post{{ $blogTotal !== 1 ? 's' : '' }} total</div>
    </div>
    <x-button variant="primary" href="{{ url('/user/dashboard/blogs/create') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Write a Blog
    </x-button>
</div>

<div class="stats-row">
    <div class="stat-card" data-filter="all"
         style="--sc-color:#5b5ef4;--sc-bg:rgba(91,94,244,0.08);">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        </div>
        <div>
            <div class="stat-val">{{ $blogTotal }}</div>
            <div class="stat-lbl">Total Posts</div>
        </div>
    </div>
    <div class="stat-card" data-filter="published"
         style="--sc-color:#10b981;--sc-bg:rgba(16,185,129,0.08);">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="stat-val stat-val-green">{{ $blogPublished }}</div>
            <div class="stat-lbl">Published</div>
        </div>
    </div>
    <div class="stat-card" data-filter="pending"
         style="--sc-color:#f59e0b;--sc-bg:rgba(245,158,11,0.08);">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="stat-val stat-val-yellow">{{ $blogPending }}</div>
            <div class="stat-lbl">In Review</div>
        </div>
    </div>
    <div class="stat-card" data-filter="draft"
         style="--sc-color:#8b5cf6;--sc-bg:rgba(139,92,246,0.08);">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </div>
        <div>
            <div class="stat-val stat-val-accent2">{{ $blogDraft }}</div>
            <div class="stat-lbl">Drafts</div>
        </div>
    </div>
</div>

<div class="filter-bar">
    <div class="ftabs">
        <button class="ftab on" data-filter="all">All <span class="cnt">{{ $blogTotal }}</span></button>
        <button class="ftab" data-filter="published">Published <span class="cnt">{{ $blogPublished }}</span></button>
        <button class="ftab" data-filter="pending">In Review <span class="cnt">{{ $blogPending }}</span></button>
        <button class="ftab" data-filter="draft">Drafts <span class="cnt">{{ $blogDraft }}</span></button>
        @if($blogRejected > 0)
        <button class="ftab" data-filter="rejected">Rejected <span class="cnt">{{ $blogRejected }}</span></button>
        @endif
    </div>
    <div class="search-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/></svg>
        <input type="text" class="search-input" id="searchInput" placeholder="Search your blogs…">
    </div>
</div>

<div class="blog-grid" id="blogGrid">
    @forelse($blogs as $blog)
    <div class="blog-card"
         data-status="{{ $blog->status }}"
         data-title="{{ strtolower($blog->title) }}"
         data-excerpt="{{ strtolower($blog->excerpt ?? '') }}">
        <div class="blog-cover">
            @if($blog->cover_image)
                <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="{{ $blog->title }}" loading="lazy">
            @else
                <div class="cover-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
            @endif
            <span class="status-badge s-{{ $blog->status }}">
                {{ $blog->status === 'published' ? '● Published' : ucfirst($blog->status) }}
            </span>
            @if($blog->category ?? false)
            <span class="cat-chip">{{ $blog->category->name }}</span>
            @endif
        </div>
        <div class="blog-body">
            <div class="blog-title">{{ $blog->title }}</div>
            @if($blog->excerpt)
            <div class="blog-excerpt">{{ $blog->excerpt }}</div>
            @endif
            <div class="blog-footer">
                <span class="blog-date">{{ $blog->created_at->format('d M Y') }}</span>
                <div class="blog-actions">
                    @if(in_array($blog->status, ['draft','rejected']))
                    <x-button variant="secondary" href="{{ route('user.blogs.edit', $blog) }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </x-button>
                    @endif
                    <x-button variant="primary" href="{{ route('user.blogs.show', $blog) }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        View
                    </x-button>
                </div>
            </div>
        </div>
    </div>
    @empty
    @endforelse
</div>

<div class="empty-state" id="emptyState">
    <div class="empty-icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
    </div>
    <div class="empty-title" id="emptyTitle">No blogs yet</div>
    <p class="empty-sub" id="emptySub">Start writing your first blog post to share your story with the world.</p>
    <x-button variant="primary" href="{{ url('/user/dashboard/blogs/create') }}" id="emptyBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Write Your First Blog
    </x-button>
</div>
@endsection

@push('page_scripts')
<script>
var currentFilter = 'all';
var currentSearch = '';

var emptyTitles = {
    all:'No blogs yet', published:'No published blogs',
    pending:'No blogs in review', draft:'No draft blogs', rejected:'No rejected blogs'
};
var emptySubs = {
    all:'Start writing your first blog post to share your story with the world.',
    published:'Your published blogs will appear here once approved.',
    pending:'Submit a blog to have it reviewed by the admin team.',
    draft:'Save a blog as draft and come back to finish it later.',
    rejected:'Edit and resubmit any rejected blogs.'
};

function applyFilter(filter, search) {
    currentFilter = filter || currentFilter;
    currentSearch = (search !== undefined) ? search : currentSearch;

    var cards   = document.querySelectorAll('#blogGrid .blog-card');
    var visible = 0;

    cards.forEach(function(card) {
        var matchFilter = currentFilter === 'all' || card.dataset.status === currentFilter;
        var matchSearch = !currentSearch ||
            card.dataset.title.includes(currentSearch) ||
            card.dataset.excerpt.includes(currentSearch);
        var show = matchFilter && matchSearch;
        card.classList.toggle('hidden', !show);
        if (show) visible++;
    });

    document.querySelectorAll('.ftab').forEach(function(b){
        b.classList.toggle('on', b.dataset.filter === currentFilter);
    });

    document.querySelectorAll('.stat-card[data-filter]').forEach(function(c){
        c.classList.toggle('active-filter', c.dataset.filter === currentFilter);
    });

    var labels = { all:'total', published:'published', pending:'in review', draft:'drafts', rejected:'rejected' };
    var txt = visible + ' post' + (visible !== 1 ? 's' : '') + ' ' + (labels[currentFilter] || '');
    document.getElementById('subLabel').textContent = txt;

    var empty = document.getElementById('emptyState');
    document.getElementById('emptyTitle').textContent = emptyTitles[currentFilter] || emptyTitles.all;
    document.getElementById('emptySub').textContent   = emptySubs[currentFilter]   || emptySubs.all;
    document.getElementById('emptyBtn').style.display = currentFilter === 'all' ? '' : 'none';
    empty.style.display = visible === 0 ? 'block' : 'none';
}

document.querySelectorAll('[data-filter]').forEach(function(el){
    el.addEventListener('click', function(){ applyFilter(this.dataset.filter); });
});

document.getElementById('searchInput').addEventListener('input', function(){
    applyFilter(null, this.value.trim().toLowerCase());
});

document.addEventListener('DOMContentLoaded', function(){
    applyFilter('all', '');
    document.querySelectorAll('.blog-card').forEach(function(card, i){
        card.style.animationDelay = (i * 0.06) + 's';
    });
});
</script>
@endpush
