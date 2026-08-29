@extends('layouts.user')

@section('page_title', $blog->title)

@section('content')
<x-page-hero
    tag="Blog"
    title="{{ $blog->title }}"
    subtitle="Created {{ $blog->created_at->format('d M Y') }} · {{ ucfirst($blog->status) }}"
>
    <x-slot:badges>
        @if($blog->status === 'draft')
        <span class="wb-badge wbb-yellow">Draft</span>
        @elseif($blog->status === 'rejected')
        <span class="wb-badge wbb-red">Rejected</span>
        @elseif($blog->status === 'approved')
        <span class="wb-badge wbb-primary">Approved</span>
        @else
        <span class="wb-badge wbb-purple">{{ ucfirst($blog->status) }}</span>
        @endif
    </x-slot:badges>
    @if($blog->status === 'draft' || $blog->status === 'rejected')
    <x-slot:actions>
        <x-button variant="primary" href="{{ route('user.blogs.edit', $blog) }}" class="wb-btn wb-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Edit Blog
        </x-button>
    </x-slot:actions>
    @endif
</x-page-hero>
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
            <span class="meta-date meta-date-muted">Updated {{ $blog->updated_at->format('d M Y') }}</span>
            @endif
        </div>

        @if($blog->excerpt)
        <div class="show-excerpt">{{ $blog->excerpt }}</div>
        @endif

        <div class="show-content">
            {!! nl2br($blog->content) !!}
        </div>

    </div>

</div>
@endsection

@push('page_scripts')
