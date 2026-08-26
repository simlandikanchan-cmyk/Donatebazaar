@extends('layouts.admin')

@push('page_styles')
@vite('resources/css/admin/pages/blogs-flagged.css')
@endpush


@section('sidebar_blogs', 'active')
@section('page_title', 'Flagged Blogs')
@section('page_subtitle', 'Community-reported & admin-flagged posts')

@section('content')
@if(session('success'))
<div class="flash-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="flash-error">{{ session('error') }}</div>
@endif

<h2 class="flag-page-hdr">
  Flagged Posts ({{ $blogs->total() }})
</h2>

@forelse($blogs as $blog)
<div class="flag-card">
  <div class="flag-hdr">
    <div>
      <a href="{{ route('admin.blogs.show', $blog) }}" class="flag-title flag-title-link">{{ $blog->title }}</a>
      <div class="flag-meta">#{{ $blog->id }} · by {{ $blog->author->name ?? 'Unknown' }} ({{ ucfirst($blog->author->role ?? 'user') }}) · {{ $blog->reports_count ?? $blog->reports->count() }} report(s)</div>
    </div>
    @if($blog->cover_image)
      <img src="{{ $blog->cover_image_url }}" alt="" class="flag-cover">
    @endif
  </div>

  @if($blog->reports->count())
  <div class="flag-reports">
    @foreach($blog->reports as $report)
    <div class="report-row">
      <div class="report-info">
        <span><strong>{{ $report->reporter->name ?? 'Unknown' }}</strong> reported</span>
        <span class="report-reason">{{ str_replace('_', ' ', $report->reason ?? 'other') }}</span>
        @if($report->note)
          <div class="report-note">"{{ $report->note }}"</div>
        @endif
        <div class="report-status {{ $report->status === 'pending' ? '' : 'done' }}">
          {{ $report->status }} · {{ $report->created_at->diffForHumans() }}
        </div>
      </div>
      @if($report->status === 'pending')
      <form method="POST" action="{{ route('admin.blogs.reports.dismiss', $report) }}">
        @csrf
        <button type="submit" class="btn btn-secondary btn-sm">Dismiss report</button>
      </form>
      @endif
    </div>
    @endforeach
  </div>
  @endif

  <div class="flag-actions">
    <a href="{{ route('admin.blogs.show', $blog) }}" class="btn btn-secondary btn-sm">Preview</a>

    <form method="POST" action="{{ route('admin.blogs.approve', $blog) }}">
      @csrf
      <button type="submit" class="btn btn-primary btn-sm">✓ Approve</button>
    </form>

    <form method="POST" action="{{ route('admin.blogs.reject', $blog) }}"
          onsubmit="return promptFlagReason(this)">
      @csrf
      <input type="hidden" name="reason" id="flag_reject_reason_{{ $blog->id }}">
      <button type="submit" class="btn btn-red btn-sm" data-id="{{ $blog->id }}">✗ Reject</button>
    </form>
  </div>
</div>
@empty
<div class="empty-state">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  <p>No flagged blogs. All clear!</p>
</div>
@endforelse

@if($blogs->hasPages())
<div class="flag-pagination">{{ $blogs->links('vendor.pagination.admin') }}</div>
@endif
@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/blogs-flagged.js')
@endpush
