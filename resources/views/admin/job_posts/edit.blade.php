@push('page_css')
@vite('resources/css/admin/entries/jobs.css')
@endpush

@extends('layouts.admin')

@section('sidebar_job_posts', 'active')
@section('page_title', 'Edit Job Post')
@section('page_subtitle')
    Edit Job Post › Edit Listing #{{ $jobPost->id }}
@endsection

@push('page_styles')
@vite('resources/css/admin/entries/jobs-edit.css')
@endpush

@section('content')

{{-- DELETE MODAL --}}
<div class="modal-overlay" id="deleteModal">
  <div class="modal">
    <div class="modal-ico">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
    </div>
    <div class="modal-ttl">Delete Job Post?</div>
    <div class="modal-desc">You are about to permanently delete <strong>"{{ $jobPost->title }}"</strong>. This will also remove all associated applications. This action <strong>cannot be undone</strong>.</div>
    <div class="modal-btns">
      <button class="btn btn-secondary btn-modal-cancel" data-action="close-delete">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Cancel
      </button>
      <form action="{{ route('admin.job_posts.destroy', $jobPost->id) }}" method="POST" style="flex:1;">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-red btn-modal-delete">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

{{-- DISCARD MODAL --}}
<div class="modal-overlay" id="discardModal">
  <div class="modal">
    <div class="modal-ico" style="background:rgba(245,158,11,.12);border-color:rgba(245,158,11,.22);">
      <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </div>
    <div class="modal-ttl">Discard Changes?</div>
    <div class="modal-desc">You have <strong>unsaved changes</strong> to this job post. If you leave now, your edits will be lost.</div>
    <div class="modal-btns">
      <button class="btn btn-secondary btn-modal-cancel" data-action="close-discard">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Keep Editing
      </button>
      <a href="{{ route('admin.job_posts.index') }}" class="btn btn-yellow btn-modal-delete">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17l-5-5 5-5m7 5l-5-5 5-5"/></svg>Discard &amp; Leave
      </a>
    </div>
  </div>
</div>

<div class="breadcrumb">
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <a href="{{ route('admin.job_posts.index') }}">Job Posts</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Edit #{{ $jobPost->id }}</span>
</div>

<div class="page-hdr">
  <div class="page-hdr-left">
    <div class="page-hdr-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></div>
    <div class="page-ttl">Edit Job Post</div>
    <div class="page-sub">Update the listing details for <strong style="color:var(--text2);">{{ $jobPost->title }}</strong></div>
  </div>
  <div class="page-hdr-right">
    <div class="edit-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>ID #{{ $jobPost->id }}</div>
    <span class="unsaved-badge" id="unsavedBadge"><span class="ud-dot"></span>Unsaved changes</span>
    <a href="{{ route('admin.job_posts.index') }}" class="btn-back"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>Back to Listings</a>
  </div>
</div>

@if($errors->any())
<div class="alert alert-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
  <div><strong>Please fix the following errors:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
</div>
@endif

<form id="jobForm" action="{{ route('admin.job_posts.update', $jobPost->id) }}" method="POST" novalidate>
  @csrf
  @method('PUT')

  <div class="form-layout">

    {{-- LEFT --}}
    <div>

      {{-- CARD 1: BASIC INFO --}}
      <div class="card" style="animation-delay:.05s">
        <div class="card-hdr">
          <div class="card-ico ci-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
          <div><div class="card-ttl">Basic Information</div><div class="card-sub">Core job details visible to all applicants</div></div>
        </div>

        {{-- Title --}}
        <div class="field">
          <label class="lbl" for="title">Job Title <span>*</span></label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <input type="text" id="title" name="title" class="inp @error('title') err @enderror" placeholder="e.g. Senior Product Designer" value="{{ old('title', $jobPost->title) }}" maxlength="150" autocomplete="off">
          </div>
          <span class="char-counter" id="titleCounter">{{ strlen(old('title', $jobPost->title)) }} / 150</span>
          @error('title')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="field-hint">Be specific — a clear title attracts better candidates.</div>
        </div>

        {{-- Slug --}}
        <div class="field">
          <label class="lbl" for="slug">URL Slug <span>*</span></label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            <input type="text" id="slug" name="slug" class="inp @error('slug') err @enderror" placeholder="url-slug-here" value="{{ old('slug', $jobPost->slug) }}" maxlength="255" autocomplete="off">
          </div>
          @error('slug')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="slug-preview" id="slugPreview">
            <span class="slug-base">/jobs/</span>
            <span class="slug-val" id="slugDisplay">{{ old('slug', $jobPost->slug) }}</span>
            <button type="button" class="slug-lock-btn" id="slugLockBtn" style="color:var(--amber);border-color:var(--amber);">Manual</button>
          </div>
          <div class="field-hint">Edit carefully — changing the slug will break existing links to this job. Must be unique.</div>
        </div>

        {{-- Type + Location --}}
        <div class="field-row field">
          <div>
            <label class="lbl" for="type">Job Type <span>*</span></label>
              <select id="type" name="type" class="sel @error('type') err @enderror">
              <option value="" disabled>Select type…</option>
              @foreach(['full-time','part-time','contract','internship','volunteer','freelance','remote'] as $t)
                <option value="{{ $t }}" {{ old('type', $jobPost->type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
              @endforeach
            </select>
            @error('type')<p class="field-error show">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="lbl" for="location">Location</label>
            <div class="inp-wrap">
              <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <input type="text" id="location" name="location" class="inp @error('location') err @enderror" placeholder="e.g. Mumbai, India" value="{{ old('location', $jobPost->location) }}" autocomplete="off">
            </div>
            @error('location')<p class="field-error show">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- Remote Toggle --}}
        <div class="field">
          <label class="lbl">Remote Work</label>
          <label class="toggle-row" id="remoteToggleRow">
            <div class="toggle-row-info">
              <div class="toggle-row-title">This is a remote position</div>
              <div class="toggle-row-sub">Enables the "Remote" badge on the listing</div>
            </div>
            <div class="toggle-switch">
              <input type="checkbox" id="is_remote" name="is_remote" value="1" {{ old('is_remote', $jobPost->is_remote) ? 'checked' : '' }}>
              <label for="is_remote"></label>
            </div>
          </label>
          @error('is_remote')<p class="field-error show">{{ $message }}</p>@enderror
        </div>

        {{-- Salary --}}
        <div class="field">
          <label class="lbl" for="salary">Salary / Compensation</label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <input type="text" id="salary" name="salary" class="inp @error('salary') err @enderror" placeholder="e.g. ₹6–10 LPA or $60,000–$80,000/yr" value="{{ old('salary', $jobPost->salary) }}" autocomplete="off">
          </div>
          @error('salary')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="field-hint">Leave blank if you'd prefer not to disclose. Ranges perform better than fixed figures.</div>
        </div>

        {{-- Application Deadline --}}
        <div class="field">
          <label class="lbl" for="application_deadline">Application Deadline</label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <input type="date" id="application_deadline" name="application_deadline" class="inp @error('application_deadline') err @enderror" value="{{ old('application_deadline', $jobPost->application_deadline ? \Carbon\Carbon::parse($jobPost->application_deadline)->format('Y-m-d') : '') }}">
          </div>
          @error('application_deadline')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="field-hint">Leave blank for a rolling / no-deadline listing.</div>
        </div>

      </div>{{-- /.card --}}

      {{-- CARD 2: DESCRIPTION --}}
      <div class="card" style="animation-delay:.10s">
        <div class="card-hdr">
          <div class="card-ico ci-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h10"/></svg></div>
          <div><div class="card-ttl">Job Description</div><div class="card-sub">Detailed overview, responsibilities &amp; requirements</div></div>
        </div>
        <div class="field">
          <label class="lbl" for="description">Description <span>*</span>
            <span class="char-counter" id="descCounter" style="text-transform:none;letter-spacing:0;">{{ strlen(old('description', $jobPost->description)) }} chars</span>
          </label>
          <textarea id="description" name="description" class="ta @error('description') err @enderror" rows="10" placeholder="Describe the role, key responsibilities, required qualifications, benefits…">{{ old('description', $jobPost->description) }}</textarea>
          @error('description')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="field-hint">Tip: Use plain text. Include sections like "About the Role", "Responsibilities", "Requirements", and "Benefits".</div>
        </div>
      </div>{{-- /.card --}}

      {{-- CARD: ROLE DETAILS & REQUIREMENTS --}}
      <div class="card" style="animation-delay:.12s">
        <div class="card-hdr">
          <div class="card-ico ci-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
          <div><div class="card-ttl">Role Details &amp; Requirements</div><div class="card-sub">Department, experience, vacancies &amp; skills</div></div>
        </div>

        {{-- Department + Experience --}}
        <div class="field-row field">
          <div>
            <label class="lbl" for="department">Department</label>
            <div class="inp-wrap">
              <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
              <input type="text" id="department" name="department" class="inp @error('department') err @enderror" placeholder="e.g. Engineering" value="{{ old('department', $jobPost->department) }}" autocomplete="off">
            </div>
            @error('department')<p class="field-error show">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="lbl" for="experience_required">Experience Required</label>
            <div class="inp-wrap">
              <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <input type="text" id="experience_required" name="experience_required" class="inp @error('experience_required') err @enderror" placeholder="e.g. 3+ years" value="{{ old('experience_required', $jobPost->experience_required) }}" autocomplete="off">
            </div>
            @error('experience_required')<p class="field-error show">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- Vacancies --}}
        <div class="field">
          <label class="lbl" for="vacancies">Number of Vacancies</label>
          <div class="inp-wrap" style="max-width:200px;">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
            <input type="number" id="vacancies" name="vacancies" class="inp num @error('vacancies') err @enderror" placeholder="1" min="1" value="{{ old('vacancies', $jobPost->vacancies) }}">
          </div>
          @error('vacancies')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="field-hint">How many open positions are available for this role.</div>
        </div>

        {{-- Featured toggle --}}
        <div class="field">
          <label class="lbl">Featured Listing</label>
          <label class="toggle-row" id="featuredToggleRow">
            <div class="toggle-row-info">
              <div class="toggle-row-title">Show on homepage &amp; featured sections</div>
              <div class="toggle-row-sub">Highlights this post across the site</div>
            </div>
            <div class="toggle-switch">
              <input type="checkbox" id="featured" name="featured" value="1" {{ old('featured', $jobPost->featured) ? 'checked' : '' }}>
              <label for="featured"></label>
            </div>
          </label>
          @error('featured')<p class="field-error show">{{ $message }}</p>@enderror
        </div>

        {{-- Skills --}}
        <div class="field">
          <label class="lbl" for="skills">Skills</label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            <input type="text" id="skills" name="skills" class="inp @error('skills') err @enderror" placeholder="React, Node.js, Figma" value="{{ old('skills', is_array($jobPost->skills) ? implode(', ', $jobPost->skills) : $jobPost->skills) }}" autocomplete="off">
          </div>
          <div class="skill-preview" id="skillPreview"></div>
          @error('skills')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="field-hint">Comma-separated. Each becomes a tag on the public listing.</div>
        </div>
      </div>{{-- /.card --}}

      {{-- CARD: SEO & META --}}
      <div class="card" style="animation-delay:.13s">
        <div class="card-hdr">
          <div class="card-ico ci-gray"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg></div>
          <div><div class="card-ttl">SEO &amp; Meta</div><div class="card-sub">Optimise how this listing appears in search</div></div>
        </div>
        <div class="field">
          <label class="lbl" for="meta_title">Meta Title <span class="char-counter" id="metaTitleCounter" style="text-transform:none;letter-spacing:0;">{{ strlen(old('meta_title', $jobPost->meta_title ?? '')) }} / 255</span></label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7V5a1 1 0 011-1h14a1 1 0 011 1v2M9 20h6M12 5v15"/></svg>
            <input type="text" id="meta_title" name="meta_title" class="inp @error('meta_title') err @enderror" placeholder="Optional search title" maxlength="255" value="{{ old('meta_title', $jobPost->meta_title) }}" autocomplete="off">
          </div>
          @error('meta_title')<p class="field-error show">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="lbl" for="meta_description">Meta Description <span class="char-counter" id="metaDescCounter" style="text-transform:none;letter-spacing:0;">{{ strlen(old('meta_description', $jobPost->meta_description ?? '')) }} / 500</span></label>
          <textarea id="meta_description" name="meta_description" class="ta @error('meta_description') err @enderror" rows="3" placeholder="Optional summary shown in search results" maxlength="500">{{ old('meta_description', $jobPost->meta_description) }}</textarea>
          @error('meta_description')<p class="field-error show">{{ $message }}</p>@enderror
        </div>
      </div>{{-- /.card --}}

      {{-- CARD 3: STATUS --}}
      <div class="card" style="animation-delay:.15s">
        <div class="card-hdr">
          <div class="card-ico ci-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
          <div><div class="card-ttl">Publication Status</div><div class="card-sub">Control visibility of this job listing</div></div>
        </div>
        <div class="field">
          <label class="lbl">Status <span>*</span></label>
          <div class="status-pills">
            <label class="status-pill sp-draft">
              <input type="radio" name="status" value="draft" {{ old('status', $jobPost->status) === 'draft' ? 'checked' : '' }}>
              <div class="status-pill-dot"></div>
              <div><div class="status-pill-lbl">Draft</div><div class="status-pill-sub">Not visible yet</div></div>
            </label>
            <label class="status-pill sp-active">
              <input type="radio" name="status" value="active" {{ old('status', $jobPost->status) === 'active' ? 'checked' : '' }}>
              <div class="status-pill-dot"></div>
              <div><div class="status-pill-lbl">Active</div><div class="status-pill-sub">Live &amp; accepting</div></div>
            </label>
            <label class="status-pill sp-closed">
              <input type="radio" name="status" value="closed" {{ old('status', $jobPost->status) === 'closed' ? 'checked' : '' }}>
              <div class="status-pill-dot"></div>
              <div><div class="status-pill-lbl">Closed</div><div class="status-pill-sub">No longer hiring</div></div>
            </label>
          </div>
          @error('status')<p class="field-error show">{{ $message }}</p>@enderror
        </div>
        <div class="submit-row">
          <div class="submit-info">Fields marked <span>*</span> are required</div>
          <div class="submit-btns">
            <a href="{{ route('admin.job_posts.index') }}" class="btn btn-secondary" id="discardBtn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Discard
            </a>
            <button type="submit" class="btn btn-primary" id="saveBtn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>Save Changes
            </button>
          </div>
        </div>
      </div>{{-- /.card --}}

    </div>{{-- /left col --}}

    {{-- RIGHT SIDEBAR --}}
    <div class="side-stack">

      {{-- LIVE PREVIEW --}}
      <div class="preview-card">
        <div class="preview-ttl-row">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          <span>Live Preview</span>
        </div>
        <div class="prev-job-title" id="prevTitle">{{ $jobPost->title }}</div>
        <div class="prev-meta">
          <span class="prev-chip" id="prevType" style="{{ $jobPost->type ? '' : 'display:none;' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span id="prevTypeVal">{{ $jobPost->type ?? '—' }}</span>
          </span>
          <span class="prev-chip" id="prevLoc" style="{{ $jobPost->location ? '' : 'display:none;' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span id="prevLocVal">{{ $jobPost->location ?? '—' }}</span>
          </span>
          <span class="prev-chip" id="prevSal" style="{{ $jobPost->salary ? '' : 'display:none;' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span id="prevSalVal">{{ $jobPost->salary ?? '—' }}</span>
          </span>
          <div class="prev-skills" id="prevSkills"></div>
          <span class="prev-chip remote-chip" id="prevRemote" style="{{ $jobPost->is_remote ? '' : 'display:none;' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/><circle cx="12" cy="12" r="9"/></svg>
            Remote
          </span>
          <span class="prev-chip" id="prevDeadline" style="{{ $jobPost->application_deadline ? '' : 'display:none;' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span id="prevDeadlineVal">{{ $jobPost->application_deadline ? 'Deadline: ' . \Carbon\Carbon::parse($jobPost->application_deadline)->format('M d, Y') : '' }}</span>
          </span>
        </div>
        <div class="prev-desc" id="prevDesc" style="{{ $jobPost->description ? 'color:var(--text2);' : '' }}">{{ $jobPost->description ? Str::limit($jobPost->description, 160) : 'Description preview will appear here…' }}</div>
        <div class="prev-status-row">
          <div style="display:flex;align-items:center;">
            <div class="prev-status-dot" id="prevDot" style="background:{{ $jobPost->status === 'active' ? '#05c48a' : ($jobPost->status === 'closed' ? '#f04444' : '#6b7280') }};"></div>
            <span class="prev-status-lbl" id="prevStatus" style="color:{{ $jobPost->status === 'active' ? '#05c48a' : ($jobPost->status === 'closed' ? '#f04444' : '#6b7280') }};">{{ ucfirst($jobPost->status ?? 'draft') }}</span>
          </div>
          <span style="font-size:11px;color:var(--text3);font-family:var(--mono);">Preview</span>
        </div>
      </div>

      {{-- POST META --}}
      <div class="meta-card">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span style="font-size:12px;font-weight:700;color:#3b82f6;font-family:var(--mono);text-transform:uppercase;letter-spacing:0.08em;">Post Info</span>
        </div>
        <div class="meta-row"><span class="meta-lbl">Post ID</span><span class="meta-val">#{{ $jobPost->id }}</span></div>
        <div class="meta-row"><span class="meta-lbl">Created</span><span class="meta-val">{{ $jobPost->created_at->format('M d, Y') }}</span></div>
        <div class="meta-row"><span class="meta-lbl">Last Updated</span><span class="meta-val">{{ $jobPost->updated_at->format('M d, Y') }}</span></div>
        <div class="meta-row"><span class="meta-lbl">Applications</span><span class="meta-val" style="color:var(--amber);">{{ $jobPost->applications()->count() ?? 0 }}</span></div>
      </div>

      {{-- TIPS --}}
      <div class="tips-card">
        <div class="tips-hdr">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          <span>Edit Tips</span>
        </div>
        <div class="tip-item"><div class="tip-bullet">1</div><div>Changing status to <strong>Closed</strong> immediately stops new applications.</div></div>
        <div class="tip-item"><div class="tip-bullet">2</div><div>The <strong>slug</strong> is in Manual mode — change it carefully to avoid broken links.</div></div>
        <div class="tip-item"><div class="tip-bullet">3</div><div>Existing applicants are <strong>not notified</strong> of edits — contact them separately if needed.</div></div>
        <div class="tip-item"><div class="tip-bullet">4</div><div>Use <strong>Delete</strong> only to permanently remove this listing and all its applications.</div></div>
      </div>

      {{-- DANGER ZONE --}}
      <div class="danger-card">
        <div class="danger-hdr">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          <span>Danger Zone</span>
        </div>
        <div class="danger-desc">Permanently delete this job post and all associated applications. This action cannot be undone.</div>
        <button type="button" class="btn btn-red" data-action="open-delete">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete This Job Post
        </button>
      </div>

      {{-- QUICK LINKS --}}
      <div class="preview-card" style="animation-delay:.18s;">
        <div class="preview-ttl-row">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
          <span>Quick Links</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;margin-top:4px;">
          <a href="{{ route('admin.job_posts.index') }}" class="s-link" style="color:var(--text2);border-radius:var(--r-xs);padding:7px 10px;font-size:12.5px;"><svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h10"/></svg>All Job Posts</a>
          <a href="{{ route('admin.job_posts.create') }}" class="s-link" style="color:var(--text2);border-radius:var(--r-xs);padding:7px 10px;font-size:12.5px;"><svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Post New Job</a>
          <a href="{{ route('admin.job_post_applications.index') }}" class="s-link" style="color:var(--text2);border-radius:var(--r-xs);padding:7px 10px;font-size:12.5px;"><svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>Job Applicants</a>
        </div>
      </div>

    </div>{{-- /side-stack --}}
  </div>{{-- /.form-layout --}}
</form>

@endsection

@push('page_scripts')
@vite('resources/js/admin/job-edit.js')
@endpush