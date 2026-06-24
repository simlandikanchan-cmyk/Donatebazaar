{{-- resources/views/user/blogs/index.blade.php --}}
{{-- Extends your existing dashboard layout --}}
@extends('layouts.dashboard')

@section('title', 'My Blogs')

@section('content')
<div class="container-fluid px-4">

    {{-- Header row --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">My Blogs</h4>
            <p class="text-muted small mb-0">Write and manage your blog posts</p>
        </div>
        <a href="{{ route('user.blogs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> New Blog
        </a>
    </div>

    {{-- Status filter tabs --}}
    <ul class="nav nav-tabs mb-4">
        @foreach(['all' => 'All', 'draft' => 'Drafts', 'pending' => 'Pending Review', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $key => $label)
        <li class="nav-item">
            <a class="nav-link {{ $status === $key ? 'active' : '' }}"
               href="{{ route('user.blogs.index', ['status' => $key]) }}">
                {{ $label }}
                @if($key !== 'all' && ($counts[$key] ?? 0) > 0)
                    <span class="badge bg-{{ $key === 'pending' ? 'warning text-dark' : ($key === 'rejected' ? 'danger' : 'secondary') }} ms-1">
                        {{ $counts[$key] }}
                    </span>
                @endif
            </a>
        </li>
        @endforeach
    </ul>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Rejected notice —  help user understand why --}}
    @if($status === 'rejected' && $blogs->isNotEmpty())
        <div class="alert alert-warning">
            <i class="fas fa-info-circle me-2"></i>
            Your blog was rejected. Read the reason below, fix your post, and resubmit.
        </div>
    @endif

    {{-- Blog list --}}
    @forelse($blogs as $blog)
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                {{-- Cover image --}}
                @if($blog->cover_image)
                <div class="col-auto d-none d-md-block">
                    <img src="{{ Storage::url($blog->cover_image) }}"
                         class="rounded" style="width:80px;height:60px;object-fit:cover;" alt="">
                </div>
                @endif

                {{-- Content --}}
                <div class="col">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-{{ $blog->status_badge }}">{{ ucfirst($blog->status) }}</span>
                        @if($blog->is_featured)
                            <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Featured</span>
                        @endif
                        @if($blog->category)
                            <span class="text-muted small">{{ $blog->category->name }}</span>
                        @endif
                    </div>

                    <h6 class="mb-1">
                        <a href="{{ route('user.blogs.show', $blog) }}" class="text-decoration-none">
                            {{ $blog->title }}
                        </a>
                    </h6>

                    {{-- Rejection reason --}}
                    @if($blog->status === 'rejected' && $blog->rejection_reason)
                        <div class="alert alert-danger py-1 px-2 mb-2 small">
                            <strong>Rejection reason:</strong> {{ $blog->rejection_reason }}
                        </div>
                    @endif

                    <div class="text-muted small">
                        {{ $blog->read_time_minutes }} min read &bull;
                        {{ $blog->views_count }} views &bull;
                        {{ $blog->likes_count }} likes &bull;
                        {{ $blog->created_at->diffForHumans() }}
                    </div>
                </div>

                {{-- Actions --}}
                <div class="col-auto">
                    <div class="btn-group btn-group-sm">
                        {{-- Edit: only for draft/rejected --}}
                        @if(in_array($blog->status, ['draft', 'rejected']))
                            <a href="{{ route('user.blogs.edit', $blog) }}"
                               class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endif

                        {{-- Submit: only for draft/rejected --}}
                        @if(in_array($blog->status, ['draft', 'rejected']))
                            <form action="{{ route('user.blogs.submit', $blog) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-outline-success"
                                        onclick="return confirm('Submit this blog for admin review?')">
                                    <i class="fas fa-paper-plane"></i> Submit
                                </button>
                            </form>
                        @endif

                        {{-- View public (approved only) --}}
                        @if($blog->status === 'approved')
                            <a href="{{ route('blogs.show', $blog->slug) }}"
                               class="btn btn-outline-info" target="_blank">
                                <i class="fas fa-eye"></i> View
                            </a>
                        @endif

                        {{-- Delete --}}
                        <form action="{{ route('user.blogs.destroy', $blog) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger"
                                    onclick="return confirm('Delete this blog? This cannot be undone.')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="fas fa-pen-nib fa-3x mb-3 d-block"></i>
            <h5>No blogs yet</h5>
            <p>Start sharing your story with the community.</p>
            <a href="{{ route('user.blogs.create') }}" class="btn btn-primary">Write your first blog</a>
        </div>
    @endforelse

    {{ $blogs->links() }}
</div>
@endsection
