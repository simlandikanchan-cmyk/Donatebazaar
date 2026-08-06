@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/jobs.css')
@endpush


@section('sidebar_job_applicants', 'active')
@section('page_title', $jobPostApplication->name)
@section('page_subtitle', 'Application details')

@section('topbar_left')
<x-button variant="secondary" href="{{ route('admin.job_post_applications.index') }}">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
  All Applications
</x-button>
@endsection

@section('content')

    {{-- —€—€ FLASH —€—€ --}}
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

    {{-- —€—€ HERO STRIP —€—€ --}}
    <div class="hero-strip">
      <div class="hs-left">
        <div class="hs-avatar">{{ strtoupper(substr($jobPostApplication->name, 0, 1)) }}</div>
        <div>
          <div class="hs-name">{{ $jobPostApplication->name }}</div>
          <div class="hs-sub">Applied {{ $jobPostApplication->created_at->diffForHumans() }} &middot; {{ $jobPostApplication->created_at->format('d M Y, h:i A') }}</div>
        </div>
      </div>
      <div class="hs-right">
        <span class="badge b-{{ $jobPostApplication->status }}">{{ $jobPostApplication->status }}</span>
        @if($jobPostApplication->cv_path)
        <x-button variant="primary" href="{{ route('admin.job_post_applications.downloadCv', $jobPostApplication) }}" class="cv-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Download CV
        </x-button>
        @endif
      </div>
    </div>

    {{-- —€—€ CONTENT GRID —€—€ --}}
    <div class="content-grid">

      {{-- LEFT COLUMN --}}
      <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Contact Info --}}
        <div class="card" style="animation-delay:.05s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--a-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              </div>
              <span class="card-title">Applicant Details</span>
            </div>
          </div>
          <div class="card-body">
            <div class="applicant-grid">
              <div class="field">
                <div class="field-lbl">Full Name</div>
                <div class="field-val">{{ $jobPostApplication->name }}</div>
              </div>
              <div class="field">
                <div class="field-lbl">Email Address</div>
                <div class="field-val"><a href="mailto:{{ $jobPostApplication->email }}">{{ $jobPostApplication->email }}</a></div>
              </div>
              @if($jobPostApplication->phone)
              <div class="field">
                <div class="field-lbl">Phone</div>
                <div class="field-val"><a href="tel:{{ $jobPostApplication->phone }}">{{ $jobPostApplication->phone }}</a></div>
              </div>
              @endif
              <div class="field">
                <div class="field-lbl">Applied On</div>
                <div class="field-val">{{ $jobPostApplication->created_at->format('d M Y \a\t h:i A') }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Cover Letter --}}
        @if($jobPostApplication->cover_letter)
        <div class="card" style="animation-delay:.10s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--blue-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
              <span class="card-title">Cover Letter</span>
            </div>
          </div>
          <div class="card-body">
            <p class="cover-letter">{{ $jobPostApplication->cover_letter }}</p>
          </div>
        </div>
        @endif

        {{-- Job Post --}}
        <div class="card" style="animation-delay:.15s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--green-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              </div>
              <span class="card-title">Applied For</span>
            </div>
            <a href="{{ route('admin.job_posts.show', $jobPostApplication->jobPost) }}" class="card-link">
              View Post
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>
          <div class="card-body">
            <div class="field-val" style="font-size:15px;font-weight:700;">{{ $jobPostApplication->jobPost->title }}</div>
            <div class="job-chips">
              <span class="job-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ ucfirst($jobPostApplication->jobPost->type) }}
              </span>
              @if($jobPostApplication->jobPost->location)
              <span class="job-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $jobPostApplication->jobPost->location }}
              </span>
              @endif
              @if($jobPostApplication->jobPost->salary)
              <span class="job-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                &#8377;{{ $jobPostApplication->jobPost->salary }}
              </span>
              @endif
            </div>
          </div>
        </div>

      </div>

      {{-- RIGHT COLUMN --}}
      <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Review & Decision --}}
        <div class="card" style="animation-delay:.08s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--amber-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
              </div>
              <span class="card-title">Review &amp; Decision</span>
            </div>
          </div>
          <div class="card-body">
            <div class="status-preview">
              <span class="sp-label">Current Status</span>
              <span class="badge b-{{ $jobPostApplication->status }}">{{ $jobPostApplication->status }}</span>
            </div>
            <form method="POST" action="{{ route('admin.job_post_applications.updateStatus', $jobPostApplication) }}">
              @csrf
              @method('PATCH')
              <div class="form-group">
                <label class="form-lbl">Update Status</label>
                <select name="status" class="form-select">
                  @foreach(['pending','shortlisted','rejected','hired'] as $s)
                    <option value="{{ $s }}" @selected($jobPostApplication->status === $s)>{{ ucfirst($s) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label class="form-lbl">Admin Notes</label>
                <textarea name="admin_notes" class="form-textarea" placeholder="Internal notes about this applicant…">{{ old('admin_notes', $jobPostApplication->admin_notes) }}</textarea>
              </div>
              <x-button variant="primary" type="submit">Save Decision</x-button>
            </form>
          </div>
        </div>

        {{-- CV Download --}}
        @if($jobPostApplication->cv_path)
        <div class="card" style="animation-delay:.12s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--green-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
              <span class="card-title">Resume / CV</span>
            </div>
          </div>
          <div class="card-body">
            <x-button variant="primary" href="{{ route('admin.job_post_applications.downloadCv', $jobPostApplication) }}" class="cv-btn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              Download CV
            </x-button>
          </div>
        </div>
        @endif

        {{-- Activity Timeline --}}
        <div class="card" style="animation-delay:.16s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--a-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <span class="card-title">Timeline</span>
            </div>
          </div>
          <div class="card-body" style="padding:0;">
            <div class="timeline" style="padding:0 20px;">
              <div class="tl-item">
                <div class="tl-dot" style="background:var(--a-lt);">
                  <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                  <div class="tl-label">Application submitted</div>
                  <div class="tl-time">{{ $jobPostApplication->created_at->format('d M Y, h:i A') }}</div>
                </div>
              </div>
              @if($jobPostApplication->updated_at && $jobPostApplication->updated_at->ne($jobPostApplication->created_at))
              <div class="tl-item">
                <div class="tl-dot" style="background:var(--amber-lt);">
                  <svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                  <div class="tl-label">Status updated to <strong>{{ $jobPostApplication->status }}</strong></div>
                  <div class="tl-time">{{ $jobPostApplication->updated_at->format('d M Y, h:i A') }}</div>
                </div>
              </div>
              @endif
              <div class="tl-item" style="border-bottom:none;">
                @php
                  $isResolved = in_array($jobPostApplication->status, ['shortlisted','rejected','hired']);
                @endphp
                <div class="tl-dot" style="background:{{ $isResolved ? 'var(--green-lt)' : 'var(--surface3)' }};">
                  <svg viewBox="0 0 24 24" fill="none" stroke="{{ $isResolved ? 'var(--green)' : 'var(--text3)' }}" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                  <div class="tl-label" style="{{ $isResolved ? '' : 'color:var(--text3)' }}">{{ $isResolved ? 'Review complete' : 'Awaiting decision' }}</div>
                  <div class="tl-time">{{ $isResolved ? $jobPostApplication->updated_at->format('d M Y') : 'Pending' }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>{{-- /.content-grid --}}

@endsection

