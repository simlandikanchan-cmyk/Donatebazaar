@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/jobs.css')
@endpush


@section('sidebar_volunteer_applications', 'active')
@section('page_title', 'Application #'.$application->id)
@section('page_subtitle', 'Reviewing volunteer application details')

@section('content')

@if(session('success'))
<div class="flash-ok">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="flash-error">
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
        <x-button variant="primary" type="submit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Approve & Verify
        </x-button>
      </form>
      <form method="POST" action="{{ route('admin.volunteer_applications.reject', $application) }}">
        @csrf
        <x-button variant="destructive" type="submit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          Reject
        </x-button>
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

