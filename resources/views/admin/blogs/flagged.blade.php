@extends('layouts.admin')

@push('page_styles')
<style>
.flag-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;margin-bottom:14px;animation:fadeUp .4s ease both;}
.flag-hdr{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 18px;border-bottom:1px solid var(--border);}
.flag-title{font-size:14px;font-weight:700;color:var(--text);}
.flag-meta{font-size:11px;color:var(--text3);font-family:var(--mono);margin-top:3px;}
.flag-reports{padding:10px 18px;border-bottom:1px solid var(--border);}
.report-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:10px 0;border-bottom:1px solid var(--border);}
.report-row:last-child{border-bottom:none;}
.report-info{flex:1;min-width:0;}
.report-reason{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;font-family:var(--mono);background:var(--amber-lt);color:#b45309;margin-left:6px;}
.report-note{font-size:11px;color:var(--text3);margin-top:3px;font-style:italic;}
.report-status{font-size:10.5px;color:var(--text3);font-family:var(--mono);margin-top:3px;}
.report-status.done{color:var(--green);}
.flag-actions{display:flex;gap:8px;padding:12px 18px;flex-wrap:wrap;}
.empty-state{display:flex;flex-direction:column;align-items:center;gap:10px;padding:56px 20px;color:var(--text3);}
.empty-state svg{width:48px;height:48px;opacity:.25;}
.flash-success{background:var(--green-lt);border:1px solid rgba(5,196,138,.25);color:#059669;padding:10px 14px;border-radius:var(--r-sm);margin-bottom:14px;font-size:12.5px;font-weight:600;}
.flash-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);padding:10px 14px;border-radius:var(--r-sm);margin-bottom:14px;font-size:12.5px;font-weight:600;}
</style>
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

<h2 style="font-family:var(--mono);font-size:15px;font-weight:800;color:var(--text);margin-bottom:16px;">
  Flagged Posts ({{ $blogs->total() }})
</h2>

@forelse($blogs as $blog)
<div class="flag-card">
  <div class="flag-hdr">
    <div>
      <a href="{{ route('admin.blogs.show', $blog) }}" class="flag-title" style="text-decoration:none;color:var(--a)">{{ $blog->title }}</a>
      <div class="flag-meta">#{{ $blog->id }} · by {{ $blog->author->name ?? 'Unknown' }} ({{ ucfirst($blog->author->role ?? 'user') }}) · {{ $blog->reports_count ?? $blog->reports->count() }} report(s)</div>
    </div>
    @if($blog->cover_image)
      <img src="{{ $blog->cover_image_url }}" alt="" style="width:72px;height:52px;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
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
<div style="margin-top:18px;">{{ $blogs->links('vendor.pagination.admin') }}</div>
@endif
@endsection

@push('page_scripts')
<script>
function promptFlagReason(form) {
  const id = form.querySelector('button[data-id]').dataset.id;
  const reason = prompt('Rejection reason (required):');
  if (!reason || !reason.trim()) return false;
  document.getElementById('flag_reject_reason_' + id).value = reason;
  return true;
}
</script>
@endpush
