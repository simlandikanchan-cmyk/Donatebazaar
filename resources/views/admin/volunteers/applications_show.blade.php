@extends('layouts.admin')

@section('sidebar_volunteer_applications', 'active')
@section('page_title', 'Application #'.$application->id)
@section('page_subtitle', 'Reviewing volunteer application details')

@push('page_styles')
<style>
.hero-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:28px 30px;box-shadow:var(--sh);margin-bottom:20px;display:flex;align-items:flex-start;justify-content:space-between;gap:20px;animation:fadeUp .35s ease both;position:relative;overflow:hidden}
.hero-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--a),var(--a2));border-radius:var(--r) var(--r) 0 0}
.hero-left{display:flex;align-items:center;gap:18px;min-width:0}
.hero-av{width:58px;height:58px;border-radius:16px;flex-shrink:0;background:linear-gradient(135deg,var(--a),var(--a2));display:flex;align-items:center;justify-content:center;font-family:var(--mono);font-size:22px;font-weight:800;color:#fff;box-shadow:0 4px 18px rgba(37,99,235,.35)}
.hero-title{font-family:var(--mono);font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.03em;line-height:1.2}
.hero-sub{font-size:12px;color:var(--text3);margin-top:5px;font-family:var(--mono)}
.hero-meta{display:flex;align-items:center;gap:14px;margin-top:10px;flex-wrap:wrap}
.hero-meta-item{display:flex;align-items:center;gap:5px;font-size:11.5px;color:var(--text3);font-family:var(--mono)}
.hero-meta-item svg{width:12px;height:12px;flex-shrink:0}
.hero-right{display:flex;flex-direction:column;align-items:flex-end;gap:12px;flex-shrink:0}

.detail-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:24px;box-shadow:var(--sh);margin-bottom:20px;animation:fadeUp .4s ease both}
.detail-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.info-box{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:16px 18px;transition:border-color var(--ease),box-shadow var(--ease)}
.info-box:hover{border-color:rgba(37,99,235,.25);box-shadow:0 0 0 3px var(--a-lt)}
.info-label{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;margin-bottom:7px;font-family:var(--mono)}
.info-value{font-size:14px;font-weight:600;color:var(--text);line-height:1.5;word-break:break-word;font-family:var(--mono)}
.info-value.empty{color:var(--text3);font-weight:400}

.flash-ok{background:rgba(5,196,138,.09);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px}
[data-theme="dark"] .flash-ok{color:#34d399}
.flash-ok svg{width:15px;height:15px;flex-shrink:0}
.flash-err{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px}
[data-theme="dark"] .flash-err{color:#f87171}
.flash-err svg{width:15px;height:15px;flex-shrink:0}

.action-bar{display:flex;gap:10px;margin-top:16px;flex-wrap:wrap}
.action-bar form{display:inline}

.btn-approve{display:inline-flex;align-items:center;gap:7px;padding:10px 22px;border-radius:var(--r-sm);font-size:13px;font-weight:600;font-family:var(--font);border:none;cursor:pointer;transition:all var(--ease);text-decoration:none;background:rgba(5,196,138,.12);color:#047857;border:1px solid rgba(5,196,138,.25)}
.btn-approve:hover{background:var(--green);color:#fff;border-color:var(--green);transform:translateY(-1px);box-shadow:0 4px 14px rgba(5,196,138,.35)}
[data-theme="dark"] .btn-approve{color:#34d399}
[data-theme="dark"] .btn-approve:hover{color:#fff}

.btn-reject{display:inline-flex;align-items:center;gap:7px;padding:10px 22px;border-radius:var(--r-sm);font-size:13px;font-weight:600;font-family:var(--font);border:none;cursor:pointer;transition:all var(--ease);text-decoration:none;background:rgba(240,68,68,.12);color:#b91c1c;border:1px solid rgba(240,68,68,.25)}
.btn-reject:hover{background:var(--red);color:#fff;border-color:var(--red);transform:translateY(-1px);box-shadow:0 4px 14px rgba(240,68,68,.35)}
[data-theme="dark"] .btn-reject{color:#f87171}
[data-theme="dark"] .btn-reject:hover{color:#fff}
@media(max-width:960px){.detail-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:640px){.detail-grid{grid-template-columns:1fr}.hero-card{flex-direction:column;align-items:stretch;padding:22px 20px}.hero-right{align-items:flex-start}.hero-title{font-size:18px}}
@media(max-width:480px){.hero-card{padding:18px 16px}.hero-title{font-size:16px}.hero-sub{font-size:11px}.info-box{padding:12px 14px}.info-value{font-size:12px}}
@media(max-width:380px){.hero-av{width:44px;height:44px;font-size:18px;border-radius:12px}.info-value{font-size:11px}.action-bar{flex-direction:column}.action-bar .btn-approve,.action-bar .btn-reject{width:100%;justify-content:center}}
</style>
@endpush

@section('content')

@if(session('success'))
<div class="flash-ok">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="flash-err">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  {{ session('error') }}
</div>
@endif

<div class="hero-card">
  <div class="hero-left">
    <div class="hero-av">
      {{ strtoupper(substr($application->volunteer?->user?->name ?? '?', 0, 1)) }}
    </div>
    <div>
      <div class="hero-title">{{ $application->volunteer?->user?->name ?? 'Unknown User' }}</div>
      <div class="hero-sub">{{ $application->volunteer?->user?->email ?? '—' }}</div>
      <div class="hero-meta">
        <span class="hero-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          {{ $application->campaign?->title ?? 'General Application' }}
        </span>
        <span class="hero-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          {{ $application->status }}
        </span>
        <span class="hero-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          Applied {{ $application->created_at->format('d M Y, h:i A') }}
        </span>
      </div>
    </div>
  </div>
  <div class="hero-right">
    <span class="badge {{ $application->status === 'approved' ? 'b-shortlisted' : ($application->status === 'rejected' ? 'b-rejected' : 'b-pending') }}" style="font-size:13px;padding:6px 16px">
      {{ ucfirst($application->status) }}
    </span>
  </div>
</div>

<div class="detail-card">
  <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:var(--text3);font-family:var(--mono);margin-bottom:16px">Application Details</div>
  <div class="detail-grid">
    <div class="info-box">
      <div class="info-label">Applicant Name</div>
      <div class="info-value">{{ $application->volunteer?->user?->name ?? '—' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">Email</div>
      <div class="info-value">{{ $application->volunteer?->user?->email ?? '—' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">Phone</div>
      <div class="info-value">{{ $application->volunteer?->phone ?? '—' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">Campaign</div>
      <div class="info-value">{{ $application->campaign?->title ?? 'General Application' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">Volunteer Since</div>
      <div class="info-value">{{ $application->volunteer?->created_at?->format('d M Y') ?? '—' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">Country</div>
      <div class="info-value">{{ $application->volunteer?->country ?? 'India' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">State</div>
      <div class="info-value">{{ $application->volunteer?->state ?? '—' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">City</div>
      <div class="info-value">{{ $application->volunteer?->city ?? '—' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">Availability</div>
      <div class="info-value">{{ $application->volunteer?->availability ? str_replace('_', ' ', ucfirst($application->volunteer->availability)) : '—' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">Skills</div>
      <div class="info-value">
        @if($application->volunteer?->skills && is_array($application->volunteer->skills) && count($application->volunteer->skills))
          <div style="display:flex;flex-wrap:wrap;gap:4px">
            @foreach($application->volunteer->skills as $skill)
              <span style="padding:2px 8px;border-radius:100px;font-size:10px;font-weight:500;background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.15)">{{ $skill }}</span>
            @endforeach
          </div>
        @else
          <span class="empty">—</span>
        @endif
      </div>
    </div>
    @if($application->volunteer?->bio)
    <div class="info-box" style="grid-column:span 3">
      <div class="info-label">Bio</div>
      <div class="info-value" style="font-weight:400;line-height:1.7">{{ $application->volunteer->bio }}</div>
    </div>
    @endif
    @if($application->message)
    <div class="info-box" style="grid-column:span 3">
      <div class="info-label">Applicant Message</div>
      <div class="info-value" style="font-weight:400;line-height:1.7;white-space:pre-wrap">{{ $application->message }}</div>
    </div>
    @endif
  </div>

  @if($application->status === 'pending')
  <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--border)">
    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:var(--text3);font-family:var(--mono);margin-bottom:8px">Decision</div>
    <p style="font-size:13px;color:var(--text3);margin-bottom:14px">Review the application details above before making a decision. Approving will mark the volunteer as verified and send them an email notification.</p>
    <div class="action-bar">
      <form method="POST" action="{{ route('admin.volunteer_applications.approve', $application) }}">
        @csrf
        <button type="submit" class="btn btn-green btn-approve" onclick="return confirm('Approve this application? The volunteer will be marked as verified and notified via email.')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Approve & Verify
        </button>
      </form>
      <form method="POST" action="{{ route('admin.volunteer_applications.reject', $application) }}">
        @csrf
        <button type="submit" class="btn btn-red btn-reject" onclick="return confirm('Reject this application? The applicant will be notified via email.')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          Reject
        </button>
      </form>
    </div>
  </div>
  @endif
</div>

<div style="margin-top:10px">
  <a href="{{ route('admin.volunteer_applications.index') }}" class="filter-clear" style="display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 16px;border-radius:var(--r-sm);border:1px solid var(--border2);font-size:12.5px;color:var(--text3);text-decoration:none;transition:all var(--ease)">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
    Back to Applications
  </a>
</div>

@endsection

@push('page_styles')
<style>
.b-shortlisted{background:rgba(5,196,138,.85);color:#fff;padding:4px 12px;border-radius:6px;font-size:11px;font-weight:600;font-family:var(--mono);display:inline-block}
.b-rejected{background:rgba(240,68,68,.12);color:var(--red);border:1px solid rgba(240,68,68,.22);padding:4px 12px;border-radius:6px;font-size:11px;font-weight:600;font-family:var(--mono);display:inline-block}
.b-pending{background:rgba(245,158,11,.12);color:#b45309;border:1px solid rgba(245,158,11,.22);padding:4px 12px;border-radius:6px;font-size:11px;font-weight:600;font-family:var(--mono);display:inline-block}
[data-theme="dark"] .b-pending{color:#fbbf24}
[data-theme="dark"] .b-rejected{color:#f87171}
.filter-clear:hover{border-color:var(--a);color:var(--a)}
</style>
@endpush
