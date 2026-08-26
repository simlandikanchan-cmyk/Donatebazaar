@push('page_css')
@vite('resources/css/admin/entries/jobs-index.css')
@endpush

@extends('layouts.admin')

@section('sidebar_job_posts', 'active')
@section('page_title', $jobPost->title)
@section('page_subtitle', 'Job details')

@push('page_styles')
@vite('resources/css/admin/entries/jobs-show.css')
<style>
@media(max-width:860px){
  .content-grid{grid-template-columns:1fr!important}
  .hero-right{width:100%;margin-top:16px}
  .hero-right .hero-stat-card{width:100%}
}
@media(max-width:640px){
  .table-scroll{min-width:520px}
  .act-btns{flex-direction:column;gap:4px}
  .act-btns .btn{width:100%;justify-content:center}
}
</style>
@endpush

@section('content')

@php
  $isExpired = $jobPost->application_deadline && \Carbon\Carbon::parse($jobPost->application_deadline)->isPast();
  $statusKey = ($jobPost->status === 'closed' || $isExpired) ? 'closed' : ($jobPost->status === 'draft' ? 'draft' : 'active');
  $appCount  = $jobPost->applications()->count();
  $pendCount = $jobPost->applications()->where('status','pending')->count();
  $accCount  = $jobPost->applications()->whereIn('status',['shortlisted','accepted'])->count();
  $rejCount  = $jobPost->applications()->where('status','rejected')->count();
  $skills    = is_array($jobPost->skills) ? $jobPost->skills : [];
  $views     = $jobPost->views_count ?? 0;
  $apps      = $jobPost->applications_count ?? 0;
  $convRate  = $views > 0 ? round(($apps / $views) * 100, 1) : 0;
@endphp

<div id="deleteOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-delete">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="modal-head">
      <div class="modal-ico" style="background:var(--red-lt);">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
      </div>
      <div>
        <div class="modal-ttl">Delete Job Post</div>
        <div class="modal-sub">This action cannot be undone</div>
      </div>
    </div>
    <div class="modal-body">
      Are you sure you want to permanently delete <strong>"{{ $jobPost->title }}"</strong>?
      All <strong>{{ $appCount }} application(s)</strong> linked to this post will also be removed.
    </div>
    <div class="modal-acts">
      <button type="button" data-action="close-delete" class="btn btn-secondary modal-btn modal-cancel">Cancel</button>
      <form action="{{ route('admin.job_posts.destroy', $jobPost->id) }}" method="POST" style="flex:1;">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-red modal-btn modal-red">🗑 Delete Permanently</button>
      </form>
    </div>
  </div>
</div>

<div class="page-actions">
  <a href="{{ route('admin.job_posts.index') }}" class="btn-back">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    All Listings
  </a>
  <div class="page-actions-right">
    <a href="{{ route('admin.job_posts.edit', $jobPost->id) }}" class="btn btn-edit" style="width:auto;padding:9px 18px;font-size:12.5px;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      Edit Post
    </a>
    <button type="button" data-action="open-delete" class="btn btn-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
      Delete
    </button>
  </div>
</div>

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Job Board · #{{ $jobPost->id }}</div>
    <div class="hero-name">{{ $jobPost->title }}</div>
    <div class="hero-meta">
      @if($jobPost->type)
      <span class="hero-chip hc-type">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        {{ ucfirst($jobPost->type) }}
      </span>
      @endif
      @if($jobPost->location)
      <span class="hero-chip hc-loc">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        {{ $jobPost->location }}
      </span>
      @endif
      @if($jobPost->salary)
      <span class="hero-chip hc-sal">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ $jobPost->salary }}
      </span>
      @endif
      @if($jobPost->experience_required)
      <span class="hero-chip hc-exp">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        {{ $jobPost->experience_required }}
      </span>
      @endif
      @if($jobPost->vacancies)
      <span class="hero-chip hc-vac">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        {{ $jobPost->vacancies }} {{ Str::plural('vacancy', $jobPost->vacancies) }}
      </span>
      @endif
    </div>
    <div class="hero-badges">
      @if($statusKey === 'active')
        <span class="hero-badge hb-green">● Active</span>
      @elseif($statusKey === 'draft')
        <span class="hero-badge hb-amber">✎ Draft</span>
      @else
        <span class="hero-badge hb-red">✕ Closed</span>
      @endif
      @if($jobPost->featured)<span class="hero-badge hb-amber">★ Featured</span>@endif
      @if($jobPost->is_remote)<span class="hero-badge hb-purple">🌐 Remote</span>@endif
      <span class="hero-badge" style="background:var(--surface2);color:var(--text3);border:1px solid var(--border2);">Posted {{ $jobPost->created_at->format('d M Y') }}</span>
    </div>
  </div>
  <div class="hero-right">
    <div class="hero-stat-card">
      <div class="hsc-val" style="color:var(--amber);">{{ $appCount }}</div>
      <div class="hsc-lbl">Applications</div>
    </div>
    <div class="hero-stat-card">
      <div class="hsc-val" style="color:var(--green);">{{ $accCount }}</div>
      <div class="hsc-lbl">Shortlisted</div>
    </div>
  </div>
</div>

<div class="stat-strip">
  <div class="stat">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Total</div>
      <div class="stat-val sv-amber">{{ $appCount }}</div>
      <div class="stat-foot">All applications</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Shortlisted</div>
      <div class="stat-val sv-green">{{ $accCount }}</div>
      <div class="stat-foot">Moving forward</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Rejected</div>
      <div class="stat-val sv-red">{{ $rejCount }}</div>
      <div class="stat-foot">Not selected</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Pending</div>
      <div class="stat-val sv-blue">{{ $pendCount }}</div>
      <div class="stat-foot">Awaiting review</div>
    </div>
  </div>
</div>

@if(session('success'))
<div class="flash-ok">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

<div class="content-grid">

  <div>

    <div class="card">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-hico ci-purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h10"/></svg>
          </div>
          <div>
            <div class="card-title">Job Description</div>
            <div class="card-sub">Full listing content</div>
          </div>
        </div>
      </div>
      <div class="card-body">
        @if($jobPost->description)
          <div class="desc-body">{{ $jobPost->description }}</div>
        @else
          <div style="color:var(--text3);font-size:13px;font-style:italic;">No description provided.</div>
        @endif
      </div>
    </div>

    @if(!empty($skills))
    <div class="card">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-hico ci-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          </div>
          <div>
            <div class="card-title">Skills Required</div>
            <div class="card-sub">{{ count($skills) }} skill{{ count($skills) !== 1 ? 's' : '' }}</div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="skill-tags">
          @foreach($skills as $skill)
            <span class="skill-tag">{{ $skill }}</span>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <div class="card">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-hico ci-amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
          </div>
          <div>
            <div class="card-title">Applicants</div>
            <div class="card-sub">{{ $appCount }} submission{{ $appCount !== 1 ? 's' : '' }}</div>
          </div>
        </div>
        @if($appCount > 0)
        <a href="{{ route('admin.job_post_applications.index', ['job_id' => $jobPost->id]) }}" class="btn btn-secondary" style="width:auto;padding:9px 18px;font-size:12.5px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          View All
        </a>
        @endif
      </div>

      @if($appCount > 0)
      <div class="table-scroll">
        <table style="min-width:520px">
          <thead>
            <tr>
              <th>#</th>
              <th>Applicant</th>
              <th>Email</th>
              <th>Status</th>
              <th>Applied</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($jobPost->applications()->latest()->take(10)->get() as $i => $app)
            @php
              $aBadge = match($app->status ?? 'pending') {
                'shortlisted','accepted' => 'b-shortlisted',
                'rejected'               => 'b-rejected',
                'hired'                  => 'b-hired',
                default                  => 'b-pending',
              };
            @endphp
            <tr>
              <td class="td-mono">{{ $i + 1 }}</td>
              <td>
                <div class="td-name">{{ $app->name ?? 'N/A' }}</div>
                @if($app->phone)<div class="td-sub">{{ $app->phone }}</div>@endif
              </td>
              <td class="td-mono" style="font-size:12px;">{{ $app->email ?? '—' }}</td>
              <td><span class="badge {{ $aBadge }}">{{ ucfirst($app->status ?? 'pending') }}</span></td>
              <td class="td-mono">{{ $app->created_at->format('d M Y') }}</td>
              <td>
                <div class="act-btns">
                  <a href="{{ route('admin.job_post_applications.show', $app->id) }}" class="btn btn-secondary act-btn ab-view">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Review</span>
                  </a>
                  @if($app->cv_path)
                  <a href="{{ route('admin.job_post_applications.downloadCv', $app) }}" class="act-btn ab-download" target="_blank">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>CV</span>
                  </a>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if($appCount > 10)
      <div style="padding:14px 22px;border-top:1px solid var(--border);text-align:center;">
        <a href="{{ route('admin.job_post_applications.index', ['job_id' => $jobPost->id]) }}" style="font-size:12.5px;color:var(--a);font-weight:600;">
          View all {{ $appCount }} applicants →
        </a>
      </div>
      @endif
      @else
      <div class="empty-state">
        <div class="empty-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        </div>
        <div class="empty-ttl">No applications yet</div>
        <div class="empty-sub">Applications will appear here once candidates apply.</div>
      </div>
      @endif
    </div>

  </div>

  <div class="side-stack">

    <div class="side-card">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-hico ci-purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <div class="card-title">Quick Actions</div>
        </div>
      </div>
      <div class="btn-stack">
        <a href="{{ route('admin.job_posts.edit', $jobPost->id) }}" class="btn btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          Edit This Post
        </a>
        <a href="{{ route('admin.job_post_applications.index', ['job_id' => $jobPost->id]) }}" class="btn btn-secondary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
          View All Applicants
          @if($appCount)<span class="s-chip sc-amber" style="margin-left:auto;">{{ $appCount }}</span>@endif
        </a>
        <a href="{{ route('admin.job_posts.create') }}" class="btn btn-secondary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Post Another Job
        </a>
      </div>
    </div>

    <div class="side-card">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-hico ci-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div class="card-title">Post Details</div>
        </div>
      </div>
      <div class="info-list">
        <div class="info-row">
          <span class="info-lbl">Status</span>
          <span class="badge {{ $statusKey === 'active' ? 'b-active' : ($statusKey === 'draft' ? 'b-draft' : 'b-closed') }}">{{ ucfirst($statusKey) }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Post ID</span>
          <span class="info-val">#{{ $jobPost->id }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Slug</span>
          <span class="info-val" style="font-size:11px;">/{{ $jobPost->slug }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Type</span>
          <span class="info-val">{{ $jobPost->type ? ucfirst($jobPost->type) : '—' }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Location</span>
          <span class="info-val">{{ $jobPost->location ?: '—' }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Salary</span>
          <span class="info-val green">{{ $jobPost->salary ?: 'Undisclosed' }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Experience</span>
          <span class="info-val">{{ $jobPost->experience_required ?: 'Any' }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Vacancies</span>
          <span class="info-val amber">{{ $jobPost->vacancies ?: '—' }}</span>
        </div>
        @if($jobPost->department)
        <div class="info-row">
          <span class="info-lbl">Department</span>
          <span class="info-val">{{ $jobPost->department }}</span>
        </div>
        @endif
        <div class="info-row">
          <span class="info-lbl">Remote</span>
          <span class="info-val {{ $jobPost->is_remote ? 'green' : '' }}">{{ $jobPost->is_remote ? 'Yes' : 'No' }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Featured</span>
          <span class="info-val {{ $jobPost->featured ? 'amber' : '' }}">{{ $jobPost->featured ? '★ Yes' : 'No' }}</span>
        </div>
        @if($jobPost->application_deadline)
        <div class="info-row">
          <span class="info-lbl">Deadline</span>
          <span class="info-val {{ $isExpired ? 'red' : 'amber' }}">
            {{ \Carbon\Carbon::parse($jobPost->application_deadline)->format('d M Y') }}
            {{ $isExpired ? '· Expired' : '' }}
          </span>
        </div>
        @endif
        <div class="info-row">
          <span class="info-lbl">Posted</span>
          <span class="info-val">{{ $jobPost->created_at->format('d M Y') }}</span>
        </div>
        @if($jobPost->published_at)
        <div class="info-row">
          <span class="info-lbl">Published</span>
          <span class="info-val green">{{ $jobPost->published_at->format('d M Y') }}</span>
        </div>
        @endif
        <div class="info-row">
          <span class="info-lbl">Updated</span>
          <span class="info-val">{{ $jobPost->updated_at->diffForHumans() }}</span>
        </div>
      </div>
    </div>

    <div class="side-card">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-hico ci-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          </div>
          <div class="card-title">Engagement</div>
        </div>
      </div>
      <div>
        <div class="eng-row">
          <div class="eng-ico si-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          </div>
          <div class="eng-body">
            <div class="eng-val">{{ number_format($views) }}</div>
            <div class="eng-lbl">Total views</div>
          </div>
        </div>
        <div class="eng-row">
          <div class="eng-ico si-amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div class="eng-body">
            <div class="eng-val">{{ number_format($apps) }}</div>
            <div class="eng-lbl">Applications received</div>
          </div>
        </div>
        <div class="eng-row">
          <div class="eng-ico" style="background:var(--surface2);color:var(--text2);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          </div>
          <div class="eng-body">
            <div class="eng-val">{{ $convRate }}%</div>
            <div class="eng-lbl">Application rate</div>
            <div class="eng-bar-wrap">
              <div class="eng-bar-fill" style="width:{{ max(min($convRate * 3, 100), 4) }}%;background:linear-gradient(90deg,var(--a),var(--a2));"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @if($jobPost->meta_title || $jobPost->meta_description)
    <div class="side-card">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-hico ci-gray">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
          </div>
          <div class="card-title">SEO &amp; Meta</div>
        </div>
      </div>
      <div style="padding:14px 18px;">
        @if($jobPost->meta_title)
        <div style="margin-bottom:10px;">
          <div style="font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;margin-bottom:4px;font-family:var(--mono);">Meta Title</div>
          <div style="font-size:12px;color:var(--text2);font-family:var(--mono);">{{ $jobPost->meta_title }}</div>
        </div>
        @endif
        @if($jobPost->meta_description)
        <div>
          <div style="font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;margin-bottom:4px;font-family:var(--mono);">Meta Description</div>
          <div style="font-size:12px;color:var(--text2);line-height:1.5;">{{ $jobPost->meta_description }}</div>
        </div>
        @endif
      </div>
    </div>
    @endif

    <div class="side-card">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-hico ci-amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div class="card-title">Timeline</div>
        </div>
      </div>
      <div style="padding:0 18px;">
        <div class="timeline">
          <div class="tl-item">
            <div class="tl-dot tl-dot-done">
              <svg style="width:12px;height:12px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="tl-body">
              <div class="tl-title">Post Created</div>
              <div class="tl-sub">{{ $jobPost->created_at->format('d M Y · h:i A') }}</div>
            </div>
          </div>
          @if($jobPost->published_at)
          <div class="tl-item">
            <div class="tl-dot tl-dot-done">
              <svg style="width:12px;height:12px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="tl-body">
              <div class="tl-title">Published</div>
              <div class="tl-sub">{{ $jobPost->published_at->format('d M Y · h:i A') }}</div>
            </div>
          </div>
          @endif
          <div class="tl-item">
            <div class="tl-dot {{ $statusKey === 'active' ? 'tl-dot-current' : ($statusKey === 'draft' ? 'tl-dot-pending' : 'tl-dot-muted') }}">
              @if($statusKey === 'active')
              <svg style="width:12px;height:12px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              @elseif($statusKey === 'draft')
              <svg style="width:12px;height:12px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              @else
              <svg style="width:12px;height:12px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
              @endif
            </div>
            <div class="tl-body">
              <div class="tl-title">{{ $statusKey === 'active' ? 'Currently Active' : ($statusKey === 'draft' ? 'In Draft' : 'Closed / Expired') }}</div>
              @if($isExpired)
              <div class="tl-sub">Deadline passed · {{ \Carbon\Carbon::parse($jobPost->application_deadline)->diffForHumans() }}</div>
              @elseif($jobPost->application_deadline)
              <div class="tl-sub">Deadline: {{ \Carbon\Carbon::parse($jobPost->application_deadline)->format('d M Y') }}</div>
              @else
              <div class="tl-sub">No deadline set</div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="danger-zone">
      <div class="danger-hdr">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span>Danger Zone</span>
      </div>
      <div class="danger-desc">Permanently delete this job post and all {{ $appCount }} linked application(s). Cannot be undone.</div>
      <button type="button" data-action="open-delete" class="btn btn-red">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        Delete Job Post
      </button>
    </div>

  </div>
</div>

<div id="toastWrap" class="toast-wrap"></div>

@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

document.addEventListener('click', function (e) {
  var open  = e.target.closest('[data-action="open-delete"]');
  if (open)  { document.getElementById('deleteOverlay').classList.add('open'); return; }
  var close = e.target.closest('[data-action="close-delete"]');
  if (close) { document.getElementById('deleteOverlay').classList.remove('open'); }
});
document.getElementById('deleteOverlay').addEventListener('click', function(e){ if(e.target === this) this.classList.remove('open'); });
document.addEventListener('keydown', function(e){ if(e.key === 'Escape') document.getElementById('deleteOverlay').classList.remove('open'); });

})();
</script>
@endpush
