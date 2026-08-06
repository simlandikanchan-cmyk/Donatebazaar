@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/blogs.css')
@endpush


@section('sidebar_blogs', 'active')
@section('page_title', 'Blog Analytics')
@section('page_subtitle', 'Performance overview & recent activity')

@section('content')
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon si-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg></div>
    <div class="stat-num">{{ number_format($stats['total']) }}</div>
    <div class="stat-name">Total Posts</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
    <div class="stat-num">{{ number_format($stats['pending']) }}</div>
    <div class="stat-name">Pending</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-num">{{ number_format($stats['published']) }}</div>
    <div class="stat-name">Published</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-num">{{ number_format($stats['flagged']) }}</div>
    <div class="stat-name">Flagged</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></div>
    <div class="stat-num">{{ number_format($stats['total_views']) }}</div>
    <div class="stat-name">Total Views</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-violet"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
    <div class="stat-num">{{ number_format($stats['total_likes']) }}</div>
    <div class="stat-name">Total Likes</div>
  </div>
</div>

<div class="panel">
  <div class="panel-title">Top Blog Posts by Views</div>
  <div class="scroll-x">
    <table>
      <thead>
        <tr><th>#</th><th>Post</th><th style="text-align:right;">Views</th><th style="text-align:right;">Likes</th></tr>
      </thead>
      <tbody>
        @forelse($topBlogs as $blog)
        <tr>
          <td class="rank">{{ $loop->iteration }}</td>
          <td>
            <a href="{{ route('admin.blogs.show', $blog) }}" style="color:var(--a);text-decoration:none">{{ $blog->title }}</a>
            <div style="font-size:10.5px;color:var(--text3);font-family:var(--mono);">/{{ $blog->slug }}</div>
          </td>
          <td class="num-cell">{{ number_format($blog->views_count) }}</td>
          <td class="num-cell">{{ number_format($blog->likes_count) }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:var(--text3);padding:30px;">No published posts yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="panel">
  <div class="panel-title">Recent Activity (last 7 days)</div>
  @forelse($recentActivity as $blog)
    @foreach($blog->statusLogs->take(3) as $log)
    <div class="activity-row">
      <span class="a-dot" style="background:{{ $log->to_status === 'rejected' ? 'var(--red)' : ($log->to_status === 'published' || $log->to_status === 'approved' ? 'var(--green)' : 'var(--amber)') }};"></span>
      <div class="a-body">
        <div class="a-line">
          <strong>{{ $blog->title }}</strong> moved to
          <span class="status-pill {{ in_array($log->to_status, ['published','approved']) ? 'sp-green' : ($log->to_status === 'rejected' ? 'sp-red' : ($log->to_status === 'pending' ? 'sp-amber' : 'sp-gray')) }}">{{ strtoupper($log->to_status) }}</span>
          @if($log->note)
            <span style="color:var(--text3);">— {{ Str::limit($log->note, 90) }}</span>
          @endif
        </div>
        <div class="a-meta">{{ $log->created_at->format('d M Y H:i') }} · {{ $log->created_at->diffForHumans() }}</div>
      </div>
    </div>
    @endforeach
  @empty
    <div style="text-align:center;color:var(--text3);padding:24px;">No status changes in the last 7 days.</div>
  @endforelse
</div>
@endsection
