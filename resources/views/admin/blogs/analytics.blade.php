@extends('layouts.admin')

@push('page_styles')
<style>
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);padding:18px 20px;display:flex;align-items:center;gap:14px;animation:fadeUp .4s ease both;}
.stat-icon{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:18px;height:18px;}
.si-blue{background:var(--a-lt);color:var(--a);}
.si-amber{background:var(--amber-lt);color:var(--amber);}
.si-green{background:var(--green-lt);color:var(--green);}
.si-red{background:var(--red-lt);color:var(--red);}
.si-violet{background:rgba(139,92,246,.10);color:#8b5cf6;}
.stat-num{font-family:var(--mono);font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.02em;}
.stat-name{font-size:11px;color:var(--text3);font-weight:600;text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);margin-top:2px;}
.panel{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;margin-top:18px;animation:fadeUp .4s ease both;}
.panel-title{padding:14px 18px;border-bottom:1px solid var(--border);font-family:var(--mono);font-size:13px;font-weight:700;color:var(--text);}
.scroll-x{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
th,td{padding:10px 14px;text-align:left;font-size:12.5px;color:var(--text2);}
th{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);background:var(--surface2);border-bottom:1px solid var(--border);}
tbody tr{border-bottom:1px solid var(--border);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
.rank{font-family:var(--mono);font-weight:700;color:var(--text3);width:40px;}
.num-cell{font-family:var(--mono);font-weight:600;color:var(--text);text-align:right;}
.activity-row{display:flex;gap:12px;padding:12px 18px;border-bottom:1px solid var(--border);align-items:flex-start;}
.activity-row:last-child{border-bottom:none;}
.a-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:6px;}
.a-body{flex:1;min-width:0;}
.a-line{font-size:12.5px;color:var(--text2);line-height:1.5;}
.a-line strong{color:var(--text);}
.status-pill{display:inline-flex;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;font-family:var(--mono);}
.sp-green{background:var(--green-lt);color:#059669;}
.sp-red{background:var(--red-lt);color:var(--red);}
.sp-amber{background:var(--amber-lt);color:#b45309;}
.sp-gray{background:var(--surface2);color:var(--text3);border:1px solid var(--border2);}
.a-meta{font-size:10.5px;color:var(--text3);font-family:var(--mono);margin-top:3px;}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;}
</style>
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
