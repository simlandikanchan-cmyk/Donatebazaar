@push('page_css')
@vite('resources/css/admin/entries/job-applications-index.css')
@endpush

@extends('layouts.admin')

@section('sidebar_job_applicants', 'active')
@section('page_title', 'Job Applications')
@section('page_subtitle', 'Review job applications')

@section('content')

    {{-- ── HERO ── --}}
    <div class="hero">
      <div class="hero-left">
        <div class="hero-tag"><span class="hero-tag-dot"></span>Job Board</div>
        <div class="hero-name">Job Applicants</div>
        <div class="hero-sub">Review and manage all submitted applications across every job post on DonateBazaar.</div>
        <div class="hero-badges">
          <span class="hero-badge hb-purple">{{ $stats['total'] }} total</span>
          @if($stats['pending'] > 0)
            <span class="hero-badge hb-amber">⏱ {{ $stats['pending'] }} pending</span>
          @endif
          @if($stats['shortlisted'] > 0)
            <span class="hero-badge hb-green">✓ {{ $stats['shortlisted'] }} shortlisted</span>
          @endif
          @if($stats['rejected'] > 0)
            <span class="hero-badge hb-red">✕ {{ $stats['rejected'] }} rejected</span>
          @endif
        </div>
      </div>
      <div class="hero-right">
        <a href="{{ route('admin.job_posts.create') }}" class="hero-btn hero-btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Post a Job
        </a>
        <a href="{{ route('admin.job_posts.index') }}" class="hero-btn hero-btn-ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          All Jobs
        </a>
      </div>
    </div>

    {{-- ── STATS ── --}}
    <div class="stats-grid">
      <div class="stat">
        <div class="stat-icon si-blue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Total</div>
          <div class="stat-val sv-blue">{{ $stats['total'] }}</div>
          <div class="stat-foot">All applications</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-amber">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Pending</div>
          <div class="stat-val sv-amber">{{ $stats['pending'] }}</div>
          <div class="stat-foot">Awaiting review</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-green">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Shortlisted</div>
          <div class="stat-val sv-green">{{ $stats['shortlisted'] }}</div>
          <div class="stat-foot">Moved forward</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-red">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Rejected</div>
          <div class="stat-val sv-red">{{ $stats['rejected'] }}</div>
          <div class="stat-foot">Not selected</div>
        </div>
      </div>
    </div>

    {{-- ── FLASH ── --}}
    @if(session('success'))
    <div class="flash flash-success">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flash flash-error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      {{ session('error') }}
    </div>
    @endif

    {{-- ── FILTER BAR ── --}}
    <form method="GET" action="{{ route('admin.job_post_applications.index') }}" class="filter-bar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;color:var(--text3);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
      <input class="filter-inp" type="text" name="search" placeholder="Search applicant name…" value="{{ request('search') }}">
      <select class="filter-sel" name="job_id">
        <option value="">All job posts</option>
        @foreach($jobPosts as $jp)
          <option value="{{ $jp->id }}" @selected(request('job_id') == $jp->id)>{{ $jp->title }}</option>
        @endforeach
      </select>
      <select class="filter-sel" name="status">
        <option value="">All statuses</option>
        <option value="pending"     @selected(request('status') === 'pending')>Pending</option>
        <option value="shortlisted" @selected(request('status') === 'shortlisted')>Shortlisted</option>
        <option value="rejected"    @selected(request('status') === 'rejected')>Rejected</option>
        <option value="hired"       @selected(request('status') === 'hired')>Hired</option>
      </select>
      <button type="submit" class="filter-btn">Apply Filters</button>
      @if(request('search') || request('status') || request('job_id'))
        <a href="{{ route('admin.job_post_applications.index') }}" class="filter-clear">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          Clear
        </a>
      @endif
    </form>

    {{-- ── TABLE ── --}}
    <div class="sec-hdr">
      <div class="sec-ttl">All Job Applicants</div>
      <div class="sec-right" style="font-size:12px;color:var(--text3);font-family:var(--mono);">
        {{ $applications->total() }} result{{ $applications->total() !== 1 ? 's' : '' }}
      </div>
    </div>

    <div class="table-card">
      <div class="table-wrap">
        <table id="appTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Applicant</th>
              <th>Job Post</th>
              <th>Status</th>
              <th>CV</th>
              <th>Applied</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($applications as $app)
            <tr data-name="{{ strtolower($app->name) }} {{ strtolower($app->email) }}">
              <td class="cell-id">{{ $app->id }}</td>
              <td>
                <div class="applicant-name">{{ $app->name }}</div>
                <div class="applicant-email">{{ $app->email }}</div>
              </td>
              <td>
                <div class="job-name">{{ $app->jobPost?->title ?? '—' }}</div>
                <div class="job-type">{{ ucfirst($app->jobPost?->type ?? '') }}</div>
              </td>
              <td>
                @php
                  $statusClass = match($app->status) {
                    'shortlisted' => 'b-shortlisted',
                    'rejected'    => 'b-rejected',
                    'hired'       => 'b-hired',
                    default       => 'b-pending',
                  };
                @endphp
                <span class="badge {{ $statusClass }}">{{ $app->status }}</span>
              </td>
              <td>
                @if($app->cv_path)
                  <a href="{{ route('admin.job_post_applications.downloadCv', $app) }}" class="cv-link" target="_blank">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    CV
                  </a>
                @else
                  <span class="no-cv">—</span>
                @endif
              </td>
              <td class="cell-date">{{ $app->created_at->format('d M Y') }}</td>
              <td>
                <a href="{{ route('admin.job_post_applications.show', $app) }}" class="act-link">
                  Review
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                <form method="POST" action="{{ route('admin.job_post_applications.destroy', $app) }}" style="display:inline;" data-confirm="Delete this application? This cannot be undone.">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-red act-btn ab-delete" title="Delete" style="margin-left:8px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
                  </button>
                </form>
              </td>
            </tr>
            @empty
            <tr class="empty-row">
              <td colspan="7">
                <div class="empty-inner">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                  <strong>No applications found</strong>
                  <span>No applications match your current filter or search.</span>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="pagination-wrap">{{ $applications->links('vendor.pagination.admin') }}</div>

@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/job-applications-index2.js')
@endpush

