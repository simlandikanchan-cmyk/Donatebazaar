@extends('layouts.user')

@section('page_title', $blog->title)

@section('content')
<div class="breadcrumb">
    <a href="{{ url('/user/dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <a href="{{ url('/user/dashboard/blogs') }}">My Blogs</a>
    <span class="breadcrumb-sep">›</span>
    <span class="breadcrumb-cur">{{ $blog->title }}</span>
</div>

<div class="page-hdr">
    <div class="page-hdr-left">
        <h2>Blog Post</h2>
        <p>{{ $blog->created_at->format('d M Y') }} · {{ ucfirst($blog->status) }}</p>
    </div>
    <div class="page-hdr-actions">
        <x-button variant="secondary" href="{{ url('/user/dashboard/blogs') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </x-button>
        @if($blog->status === 'draft' || $blog->status === 'rejected')
        <x-button variant="primary" href="{{ route('user.blogs.edit', $blog) }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Blog
        </x-button>
        @endif
    </div>
</div>

<div class="blog-show-card">

    <div class="show-cover">
        @if($blog->cover_image)
            <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="{{ $blog->title }}">
        @else
            <div class="show-cover-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <span>No cover image</span>
            </div>
        @endif
        <span class="cover-badge badge-{{ $blog->status }}">{{ ucfirst($blog->status) }}</span>
    </div>

    @if($blog->status === 'rejected' && $blog->rejection_reason)
    <div class="rejection-note">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="rejection-note-text">
            <div class="rejection-note-label">Rejection Reason</div>
            {{ $blog->rejection_reason }}
        </div>
    </div>
    @endif

    <div class="show-body">

        <h1 class="show-title">{{ $blog->title }}</h1>

        <div class="show-meta">
            @php
                $pillClass = [
                    'draft'    => 'pill-draft',
                    'pending'  => 'pill-pending',
                    'approved' => 'pill-approved',
                    'rejected' => 'pill-rejected',
                ][$blog->status] ?? 'pill-draft';
            @endphp
            <span class="meta-pill {{ $pillClass }}">{{ ucfirst($blog->status) }}</span>
            <span class="meta-dot"></span>
            <span class="meta-date">{{ $blog->created_at->format('d M Y') }}</span>
            @if($blog->updated_at && $blog->updated_at->ne($blog->created_at))
            <span class="meta-dot"></span>
            <span class="meta-date" style="color:var(--text3);">Updated {{ $blog->updated_at->format('d M Y') }}</span>
            @endif
        </div>

        @if($blog->excerpt)
        <div class="show-excerpt">{{ $blog->excerpt }}</div>
        @endif

        <div class="show-content">
            {!! nl2br(e($blog->content)) !!}
        </div>

    </div>

</div>
@endsection

@push('page_styles')
<style>
.breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text3); margin-bottom: 20px; }
.breadcrumb a { color: var(--text3); text-decoration: none; transition: color var(--transition); }
.breadcrumb a:hover { color: var(--accent); }
.breadcrumb-sep { opacity: 0.4; }
.breadcrumb-cur { color: var(--text2); font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 260px; }
.page-hdr { margin-bottom: 24px; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.page-hdr-left h2 { font-size: 22px; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }
.page-hdr-left p  { font-size: 12.5px; color: var(--text3); margin-top: 3px; }
.page-hdr-actions { display: flex; align-items: center; gap: 8px; }
.blog-show-card { background: var(--surface); border: 1px solid var(--border2); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); }
.show-cover { position: relative; height: 320px; overflow: hidden; flex-shrink: 0; }
.show-cover img { width: 100%; height: 100%; object-fit: cover; display: block; }
.show-cover-placeholder { width: 100%; height: 100%; background: linear-gradient(135deg, rgba(99,102,241,0.08) 0%, rgba(139,92,246,0.08) 100%); display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 10px; }
.show-cover-placeholder svg { width: 48px; height: 48px; color: var(--accent); opacity: 0.25; }
.show-cover-placeholder span { font-size: 12px; color: var(--text3); }
.cover-badge { position: absolute; top: 16px; left: 16px; font-size: 10px; font-weight: 700; padding: 4px 12px; border-radius: 100px; font-family: var(--font-mono); letter-spacing: 0.05em; text-transform: uppercase; background: #ffffff; }
.badge-draft    { background: rgba(0,0,0,0.55);      color: #d1d5db; backdrop-filter: blur(6px); }
.badge-pending  { background: rgba(245,158,11,0.18); color: #d97706; border: 1px solid rgba(245,158,11,0.3); }
.badge-approved { background: rgba(16,185,129,0.15); color: #059669; border: 1px solid rgba(16,185,129,0.25); }
.badge-rejected { background: rgba(239,68,68,0.15);  color: #dc2626; border: 1px solid rgba(239,68,68,0.25); }
.rejection-note { margin: 0 24px; padding: 13px 16px; background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.18); border-radius: var(--radius-sm); display: flex; align-items: flex-start; gap: 10px; margin-top: 20px; }
.rejection-note svg { width: 15px; height: 15px; color: var(--red); flex-shrink: 0; margin-top: 1px; }
.rejection-note-text { font-size: 12.5px; color: var(--red); line-height: 1.5; }
.rejection-note-label { font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 3px; }
.show-body { padding: 28px 32px 36px; }
.show-title { font-size: 26px; font-weight: 700; color: var(--text); letter-spacing: -0.025em; line-height: 1.3; margin-bottom: 16px; }
.show-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--border); }
.meta-pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 100px; font-size: 11px; font-weight: 600; font-family: var(--font-mono); letter-spacing: 0.04em; text-transform: uppercase; }
.pill-draft    { background: rgba(156,163,175,0.12); color: var(--text3); border: 1px solid var(--border2); }
.pill-pending  { background: rgba(245,158,11,0.1);   color: #d97706;     border: 1px solid rgba(245,158,11,0.2); }
.pill-approved { background: rgba(16,185,129,0.1);   color: #059669;     border: 1px solid rgba(16,185,129,0.2); }
.pill-rejected { background: rgba(239,68,68,0.1);    color: #dc2626;     border: 1px solid rgba(239,68,68,0.2); }
.meta-dot { width: 3px; height: 3px; border-radius: 50%; background: var(--border2); }
.meta-date { font-size: 12px; color: var(--text3); font-family: var(--font-mono); }
.show-excerpt { background: var(--surface2); border-left: 3px solid var(--accent); border-radius: 0 var(--radius-sm) var(--radius-sm) 0; padding: 14px 18px; margin-bottom: 28px; font-size: 14px; color: var(--text2); font-weight: 300; line-height: 1.7; font-style: italic; }
.show-content { font-size: 15px; color: var(--text2); line-height: 1.8; font-weight: 300; }
.show-content p { margin-bottom: 18px; }
.show-content p:last-child { margin-bottom: 0; }
@media (max-width: 860px) { .body { padding: 16px 16px 60px; } .show-cover { height: 220px; } .show-body { padding: 20px 20px 28px; } .show-title { font-size: 20px; } .breadcrumb-cur { max-width: 140px; } }
@media (max-width: 480px) { .show-cover { height: 160px; } .show-body { padding: 14px 14px 20px; } .show-title { font-size: 17px; } .show-content { font-size: 14px; } .breadcrumb { font-size: 11px; gap: 4px; } .breadcrumb-cur { max-width: 100px; } }
</style>
@endpush
