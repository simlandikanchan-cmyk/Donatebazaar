@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/jobs.css')
@endpush


@section('sidebar_job_posts', 'active')
@section('page_title', 'Create Job Post')
@section('page_subtitle', 'Add a new job listing')

@section('content')

    <div class="breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <a href="{{ route('admin.job_posts.index') }}">Job Posts</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <span>Create New</span>
    </div>

    {{-- ══════════ HERO ══════════ --}}
    <div class="hero">
      <div class="hero-left">
        <div class="hero-tag"><span class="hero-tag-dot"></span>Job Board</div>
        <div class="hero-name">Post a New Job</div>
        <div class="hero-sub">Fill in the details below to publish a listing on the DonateBazaar Job Board.</div>
      </div>
      <div class="hero-right">
        <div class="hero-actions">
        <x-button variant="primary" href="{{ route('admin.job_posts.index') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          Back to Listings
        </x-button>
        </div>
      </div>
    </div>

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
    <div class="alert-error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      <div>
        <strong>Please fix the following errors:</strong>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    </div>
    @endif

    {{-- ══════════ FORM ══════════ --}}
    <form id="jobForm" action="{{ route('admin.job_posts.store') }}" method="POST" novalidate>
      @csrf

      <div class="form-layout">

        {{-- —€—€ LEFT: MAIN FIELDS —€—€ --}}
        <div>

          {{-- ════ CARD 1: BASIC INFO ════ --}}
          <div class="card" style="animation-delay:.05s;">
            <div class="card-hdr">
              <div class="card-ico ci-teal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
              <div>
                <div class="card-ttl">Basic Information</div>
                <div class="card-sub">Core job details visible to all applicants</div>
              </div>
            </div>

            {{-- Job Title --}}
            <div class="field">
              <label class="lbl" for="title">
                Job Title <span class="req">*</span>
                <span class="counter" id="titleCounter">0 / 150</span>
              </label>
              <div class="inp-wrap">
                <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <input type="text" id="title" name="title" class="inp @error('title') err @enderror"
                  placeholder="e.g. Senior Product Designer" value="{{ old('title') }}"
                  maxlength="150" autocomplete="off" required>
              </div>
              @error('title')<p class="field-error show">{{ $message }}</p>@enderror
              <p class="field-hint">Be specific — a clear title attracts better candidates.</p>
            </div>

            {{-- Slug --}}
            <div class="field">
              <label class="lbl" for="slug">URL Slug <span class="req">*</span></label>
              <div class="inp-wrap">
                <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                <input type="text" id="slug" name="slug" class="inp @error('slug') err @enderror"
                  placeholder="auto-generated-from-title" value="{{ old('slug') }}"
                  maxlength="255" autocomplete="off">
              </div>
              @error('slug')<p class="field-error show">{{ $message }}</p>@enderror
              <div class="slug-preview">
                <span class="slug-base">/jobs/</span>
                <span class="slug-val" id="slugDisplay">your-job-slug-here</span>
                <button type="button" class="slug-lock" id="slugLockBtn">Auto</button>
              </div>
              <p class="field-hint">Auto-generated from title. Edit manually to customise the URL.</p>
            </div>

            {{-- Type + Department --}}
            <div class="field-row field">
              <div>
                <label class="lbl" for="type">Job Type <span class="req">*</span></label>
                <select id="type" name="type" class="sel @error('type') err @enderror" required>
                  <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select type…</option>
                  @foreach(['full-time'=>'Full-time','part-time'=>'Part-time','contract'=>'Contract','internship'=>'Internship','volunteer'=>'Volunteer','freelance'=>'Freelance','remote'=>'Remote'] as $val=>$lbl)
                    <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                  @endforeach
                </select>
                @error('type')<p class="field-error show">{{ $message }}</p>@enderror
              </div>
              <div>
                <label class="lbl" for="department">Department</label>
                <div class="inp-wrap">
                  <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                  <input type="text" id="department" name="department" class="inp @error('department') err @enderror"
                    placeholder="e.g. Engineering, Marketing" value="{{ old('department') }}" autocomplete="off">
                </div>
                @error('department')<p class="field-error show">{{ $message }}</p>@enderror
              </div>
            </div>

            {{-- Location + Experience --}}
            <div class="field-row field">
              <div>
                <label class="lbl" for="location">Location</label>
                <div class="inp-wrap">
                  <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  <input type="text" id="location" name="location" class="inp @error('location') err @enderror"
                    placeholder="e.g. Mumbai, India" value="{{ old('location') }}" autocomplete="off">
                </div>
                @error('location')<p class="field-error show">{{ $message }}</p>@enderror
              </div>
              <div>
                <label class="lbl" for="experience_required">Experience Required</label>
                <div class="inp-wrap">
                  <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                  <input type="text" id="experience_required" name="experience_required"
                    class="inp @error('experience_required') err @enderror"
                    placeholder="e.g. 2–4 years, Fresher OK" value="{{ old('experience_required') }}" autocomplete="off">
                </div>
                @error('experience_required')<p class="field-error show">{{ $message }}</p>@enderror
                <p class="field-hint">Leave blank if open to all experience levels.</p>
              </div>
            </div>

            {{-- Salary + Vacancies --}}
            <div class="field-row field">
              <div>
                <label class="lbl" for="salary">Salary / Compensation</label>
                <div class="inp-wrap">
                  <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  <input type="text" id="salary" name="salary" class="inp @error('salary') err @enderror"
                    placeholder="e.g. ₹6–10 LPA" value="{{ old('salary') }}" autocomplete="off">
                </div>
                @error('salary')<p class="field-error show">{{ $message }}</p>@enderror
                <p class="field-hint">Ranges perform better than fixed figures.</p>
              </div>
              <div>
                <label class="lbl" for="vacancies">No. of Vacancies</label>
                <div class="num-wrap">
                  <input type="number" id="vacancies" name="vacancies" class="inp @error('vacancies') err @enderror"
                    placeholder="1" value="{{ old('vacancies', 1) }}" min="1" max="9999" autocomplete="off">
                  <span class="num-badge" id="vacancyBadge">open</span>
                </div>
                @error('vacancies')<p class="field-error show">{{ $message }}</p>@enderror
                <p class="field-hint">Total open positions for this role.</p>
              </div>
            </div>

            {{-- Application Deadline --}}
            <div class="field">
              <label class="lbl" for="application_deadline">Application Deadline</label>
              <div class="inp-wrap">
                <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <input type="date" id="application_deadline" name="application_deadline"
                  class="inp @error('application_deadline') err @enderror"
                  value="{{ old('application_deadline') }}" min="{{ date('Y-m-d') }}">
              </div>
              @error('application_deadline')<p class="field-error show">{{ $message }}</p>@enderror
              <p class="field-hint">Leave blank for a rolling / no-deadline listing.</p>
            </div>

            {{-- Remote Toggle --}}
            <div class="field">
              <label class="lbl">Remote Work</label>
              <label class="toggle-row" id="remoteRow">
                <div>
                  <div class="toggle-row-title">This is a remote position</div>
                  <div class="toggle-row-sub">Enables the "Remote" badge on the listing</div>
                </div>
                <div class="toggle-switch">
                  <input type="checkbox" id="is_remote" name="is_remote" value="1" {{ old('is_remote') ? 'checked' : '' }}>
                  <label for="is_remote"></label>
                </div>
              </label>
            </div>

            {{-- Featured Toggle --}}
            <div class="field">
              <label class="lbl">Featured Listing</label>
              <label class="toggle-row" id="featuredRow">
                <div>
                  <div class="toggle-row-title">Mark as a featured job</div>
                  <div class="toggle-row-sub">Pinned at the top of the board with a ★ badge</div>
                </div>
                <div class="toggle-switch">
                  <input type="checkbox" id="featured" name="featured" value="1" class="amber-tog" {{ old('featured') ? 'checked' : '' }}>
                  <label for="featured" id="featuredLabel"></label>
                </div>
              </label>
            </div>
          </div>{{-- /card 1 --}}

          {{-- ════ CARD 2: DESCRIPTION ════ --}}
          <div class="card" style="animation-delay:.10s;">
            <div class="card-hdr">
              <div class="card-ico ci-purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h10"/></svg>
              </div>
              <div>
                <div class="card-ttl">Job Description</div>
                <div class="card-sub">Detailed overview, responsibilities &amp; requirements</div>
              </div>
            </div>

            <div class="field">
              <label class="lbl" for="description">
                Description <span class="req">*</span>
                <span class="counter" id="descCounter" style="font-weight:400;">0 chars</span>
              </label>
              <textarea id="description" name="description"
                class="ta @error('description') err @enderror" rows="10"
                placeholder="Describe the role, key responsibilities, required qualifications, benefits…"
                required>{{ old('description') }}</textarea>
              @error('description')<p class="field-error show">{{ $message }}</p>@enderror
              <p class="field-hint">Tip: Use sections like "About the Role", "Responsibilities", "Requirements", and "Benefits".</p>
            </div>

            {{-- Skills Tags --}}
            <div class="field">
              <label class="lbl" for="skillTagInput">Required Skills</label>
              <div class="tags-wrap" id="tagsWrap" onclick="document.getElementById('skillTagInput').focus()">
                <input type="text" id="skillTagInput" placeholder="Type a skill and press Enter or comma…"
                  class="tag-input" autocomplete="off">
              </div>
              <input type="hidden" name="skills" id="skillsHidden"
                value="{{ old('skills') ? (is_array(old('skills')) ? implode(',', old('skills')) : old('skills')) : '' }}">
              @error('skills')<p class="field-error show">{{ $message }}</p>@enderror
              <p class="field-hint">Press <kbd style="font-family:var(--mono);font-size:10px;padding:1px 5px;border:1px solid var(--border2);border-radius:4px;">Enter</kbd> or <kbd style="font-family:var(--mono);font-size:10px;padding:1px 5px;border:1px solid var(--border2);border-radius:4px;">,</kbd> after each skill.</p>
            </div>
          </div>{{-- /card 2 --}}

          {{-- ════ CARD 3: SEO ════ --}}
          <div class="card" style="animation-delay:.13s;">
            <div class="card-hdr">
              <div class="card-ico ci-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              </div>
              <div>
                <div class="card-ttl">SEO &amp; Meta</div>
                <div class="card-sub">Improve discoverability in search engines (optional)</div>
              </div>
            </div>

            <div class="field">
              <label class="lbl" for="meta_title">
                Meta Title
                <span class="counter" id="metaTitleCounter" style="font-weight:400;">0 / 70</span>
              </label>
              <div class="inp-wrap">
                <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
                <input type="text" id="meta_title" name="meta_title" class="inp @error('meta_title') err @enderror"
                  placeholder="e.g. Senior Product Designer at DonateBazaar | Remote"
                  value="{{ old('meta_title') }}" maxlength="70" autocomplete="off">
              </div>
              @error('meta_title')<p class="field-error show">{{ $message }}</p>@enderror
              <p class="field-hint">Defaults to the job title if left blank. Keep under 60 chars for best results.</p>
            </div>

            <div class="field">
              <label class="lbl" for="meta_description">
                Meta Description
                <span class="counter" id="metaDescCounter" style="font-weight:400;">0 / 160</span>
              </label>
              <textarea id="meta_description" name="meta_description"
                class="ta @error('meta_description') err @enderror" rows="3"
                placeholder="A short summary shown in Google search results…"
                maxlength="160" style="min-height:80px;">{{ old('meta_description') }}</textarea>
              @error('meta_description')<p class="field-error show">{{ $message }}</p>@enderror
              <p class="field-hint">Aim for 120–160 characters. Describe who the role is for and what makes it exciting.</p>
            </div>
          </div>{{-- /card 3 --}}

          {{-- ════ CARD 4: STATUS & SUBMIT ════ --}}
          <div class="card" style="animation-delay:.16s;">
            <div class="card-hdr">
              <div class="card-ico ci-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <div>
                <div class="card-ttl">Publication Status</div>
                <div class="card-sub">Control visibility of this job listing</div>
              </div>
            </div>

            <div class="field">
              <label class="lbl">Status <span class="req">*</span></label>
              <div class="status-pills">
                <label class="status-pill sp-draft">
                  <input type="radio" name="status" value="draft" {{ old('status','draft') === 'draft' ? 'checked' : '' }}>
                  <div class="sp-dot"></div>
                  <div><div class="sp-lbl">Draft</div><div class="sp-sub">Not visible yet</div></div>
                </label>
                <label class="status-pill sp-active">
                  <input type="radio" name="status" value="active" {{ old('status') === 'active' ? 'checked' : '' }}>
                  <div class="sp-dot"></div>
                  <div><div class="sp-lbl">Active</div><div class="sp-sub">Live &amp; accepting</div></div>
                </label>
                <label class="status-pill sp-closed">
                  <input type="radio" name="status" value="closed" {{ old('status') === 'closed' ? 'checked' : '' }}>
                  <div class="sp-dot"></div>
                  <div><div class="sp-lbl">Closed</div><div class="sp-sub">No longer hiring</div></div>
                </label>
              </div>
              @error('status')<p class="field-error show">{{ $message }}</p>@enderror
            </div>

            <div class="submit-row">
              <div class="submit-info">Fields marked <span class="req">*</span> are required</div>
              <div class="submit-btns">
                <x-button variant="secondary" href="{{ route('admin.job_posts.index') }}">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                  Discard
                </x-button>
                <x-button variant="secondary" type="submit" id="draftBtn" name="_action" value="draft">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                  Save Draft
                </x-button>
                <x-button variant="primary" type="submit" id="publishBtn" name="_action" value="publish">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  Publish Job
                </x-button>
              </div>
            </div>
          </div>{{-- /card 4 --}}

        </div>{{-- /left col --}}

        {{-- —€—€ RIGHT: STICKY PANEL —€—€ --}}
        <div class="side-stack">

          {{-- LIVE PREVIEW --}}
          <div class="preview-card">
            <div class="preview-hdr">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              <span>Live Preview</span>
            </div>
            <div class="prev-title" id="prevTitle">Job title will appear here</div>
            <div class="prev-meta">
              <span class="prev-chip" id="prevType"     style="display:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg><span id="prevTypeVal"></span></span>
              <span class="prev-chip" id="prevDept"     style="display:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg><span id="prevDeptVal"></span></span>
              <span class="prev-chip" id="prevLoc"      style="display:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span id="prevLocVal"></span></span>
              <span class="prev-chip" id="prevSal"      style="display:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span id="prevSalVal"></span></span>
              <span class="prev-chip" id="prevVac"      style="display:none;"><span id="prevVacVal"></span></span>
              <span class="prev-chip remote-chip"   id="prevRemote"   style="display:none;">Remote</span>
              <span class="prev-chip featured-chip" id="prevFeatured" style="display:none;">★ Featured</span>
              <span class="prev-chip" id="prevDeadline" style="display:none;"><span id="prevDeadlineVal"></span></span>
            </div>
            <div class="prev-desc" id="prevDesc">Description preview will appear here…</div>
            <div class="prev-status-row">
              <div style="display:flex;align-items:center;">
                <div class="prev-dot" id="prevDot" style="background:#6b7280;"></div>
                <span class="prev-lbl" id="prevStatus" style="color:#6b7280;">Draft</span>
              </div>
              <span style="font-size:11px;color:var(--text3);font-family:var(--mono);">Preview</span>
            </div>
          </div>

          {{-- TIPS --}}
          <div class="tips-card">
            <div class="tips-hdr">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
              <span>Posting Tips</span>
            </div>
            @foreach([
              ['Use a specific job title — avoid internal codes or vague titles like "Associate".'],
              ['Include a salary range — listings with pay details get 3× more applicants.'],
              ['Add skills tags to help candidates and search engines match the role.'],
              ['Set an application deadline to create urgency and manage inbox volume.'],
              ['Toggle Featured to pin this listing at the top of the job board.'],
            ] as $idx => $tip)
            <div class="tip-item">
              <div class="tip-num">{{ $idx + 1 }}</div>
              <div>{{ $tip[0] }}</div>
            </div>
            @endforeach
          </div>

          {{-- QUICK LINKS --}}
          <div class="preview-card" style="animation-delay:.16s;">
            <div class="preview-hdr">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
              <span>Quick Links</span>
            </div>
            <div style="display:flex;flex-direction:column;gap:4px;margin-top:4px;">
              <a href="{{ route('admin.job_posts.index') }}" class="s-link" style="padding:8px 10px;font-size:12.5px;">
                <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h10"/></svg>
                All Job Posts
              </a>
              <a href="{{ route('admin.job_post_applications.index') }}" class="s-link" style="padding:8px 10px;font-size:12.5px;">
                <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                Job Applicants
              </a>
              <a href="{{ route('admin.dashboard') }}" class="s-link" style="padding:8px 10px;font-size:12.5px;">
                <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
              </a>
            </div>
          </div>

        </div>{{-- /side-stack --}}
      </div>{{-- /form-layout --}}
    </form>

@endsection

@push('page_scripts')
<script>
(function () {
  'use strict';

  /* —€—€ Toast from layout —€—€ */
  function toast(msg, type) {
    var t  = document.createElement('div');
    t.className = 'toast toast-' + (type === 'success' ? 'ok' : 'err');
    var ok  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    var err = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    t.innerHTML = (type === 'success' ? ok : err) + '<span>' + msg + '</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
    document.getElementById('toastWrap').appendChild(t);
    setTimeout(function () {
      t.style.transition = 'opacity .3s,transform .3s'; t.style.opacity = '0'; t.style.transform = 'translateX(20px)';
      setTimeout(function () { t.remove(); }, 300);
    }, 4200);
  }
  @if(session('success')) setTimeout(function(){ toast(@json(session('success')), 'success'); }, 200); @endif
  @if(session('error'))   setTimeout(function(){ toast(@json(session('error')),   'error');   }, 200); @endif

  /* —€—€ SLUG AUTO-GENERATION —€—€ */
  var titleInp  = document.getElementById('title');
  var slugInp   = document.getElementById('slug');
  var slugDisp  = document.getElementById('slugDisplay');
  var slugBtn   = document.getElementById('slugLockBtn');
  var slugAuto  = true;

  function toSlug(s) {
    return s.toLowerCase().replace(/[^a-z0-9\s-]/g,'').trim()
            .replace(/\s+/g,'-').replace(/-+/g,'-').slice(0,255);
  }
  function refreshSlug() {
    slugDisp.textContent = slugInp.value || toSlug(titleInp.value) || 'your-job-slug-here';
  }
  titleInp.addEventListener('input', function () {
    if (slugAuto) { slugInp.value = toSlug(this.value); refreshSlug(); }
  });
  slugInp.addEventListener('input', function () {
    slugAuto = false; slugBtn.textContent = 'Manual';
    slugBtn.style.cssText = 'color:var(--amber);border-color:var(--amber);';
    refreshSlug();
  });
  slugBtn.addEventListener('click', function () {
    slugAuto = !slugAuto;
    if (slugAuto) { slugInp.value = toSlug(titleInp.value); this.textContent = 'Auto'; this.style.cssText = ''; }
    else          { this.textContent = 'Manual'; this.style.cssText = 'color:var(--amber);border-color:var(--amber);'; }
    refreshSlug();
  });
  @if(old('slug')) slugAuto = false; slugBtn.textContent = 'Manual'; slugBtn.style.cssText = 'color:var(--amber);border-color:var(--amber);'; @endif
  refreshSlug();

  /* —€—€ Toggle rows —€—€ */
  var remoteChk  = document.getElementById('is_remote');
  var remoteRow  = document.getElementById('remoteRow');
  var featChk    = document.getElementById('featured');
  var featRow    = document.getElementById('featuredRow');
  var featLabel  = document.getElementById('featuredLabel');

  function syncRemote()   { remoteRow.classList.toggle('active-toggle',       remoteChk.checked); }
  function syncFeatured() {
    featRow.classList.toggle('active-toggle-amber', featChk.checked);
    featLabel.style.background = featChk.checked ? 'var(--amber)' : '';
  }
  remoteChk.addEventListener('change', syncRemote);
  featChk.addEventListener('change',   syncFeatured);
  syncRemote(); syncFeatured();

  /* —€—€ Vacancies badge —€—€ */
  var vacInp   = document.getElementById('vacancies');
  var vacBadge = document.getElementById('vacancyBadge');
  function syncVac() {
    var v = parseInt(vacInp.value, 10);
    vacBadge.textContent = (!isNaN(v) && v > 0) ? v + ' open' : 'open';
  }
  vacInp.addEventListener('input', syncVac); syncVac();

  /* —€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€
     SKILLS TAGS
  —€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€ */
  var tagsWrap    = document.getElementById('tagsWrap');
  var skillInput  = document.getElementById('skillTagInput');
  var skillHidden = document.getElementById('skillsHidden');
  var skills      = [];

  (function hydrate() {
    var raw = skillHidden.value.trim();
    if (!raw) return;
    raw.split(',').map(function (s) { return s.trim(); }).filter(Boolean).forEach(function (s) { addTag(s); });
  }());

  function addTag(val) {
    val = val.trim();
    if (!val || skills.indexOf(val) !== -1) return;
    skills.push(val);
    var span = document.createElement('span');
    span.className = 'tag-item';
    span.innerHTML = val + '<button type="button" class="tag-remove">✕</button>';
    span.querySelector('.tag-remove').addEventListener('click', function () {
      skills.splice(skills.indexOf(val), 1); span.remove(); syncSkills();
    });
    tagsWrap.insertBefore(span, skillInput);
    syncSkills();
  }
  function syncSkills() { skillHidden.value = skills.join(','); }

  skillInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ',') {
      e.preventDefault();
      var v = this.value.replace(/,/g,'').trim();
      if (v) { addTag(v); this.value = ''; }
    }
    if (e.key === 'Backspace' && !this.value && skills.length) {
      var items = tagsWrap.querySelectorAll('.tag-item');
      if (items.length) { skills.pop(); items[items.length - 1].remove(); syncSkills(); }
    }
  });
  skillInput.addEventListener('blur', function () {
    var v = this.value.replace(/,/g,'').trim();
    if (v) { addTag(v); this.value = ''; }
  });

  /* —€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€
     LIVE PREVIEW
  —€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€ */
  var typeInp     = document.getElementById('type');
  var deptInp     = document.getElementById('department');
  var locInp      = document.getElementById('location');
  var salInp      = document.getElementById('salary');
  var descInp     = document.getElementById('description');
  var dlInp       = document.getElementById('application_deadline');

  var statusColors = { draft:'#6b7280', active:'#05c48a', closed:'#f04444' };
  var statusLabels = { draft:'Draft', active:'Active', closed:'Closed' };

  function fmtDate(v) {
    if (!v) return '';
    var d = new Date(v + 'T00:00:00');
    return d.toLocaleDateString('en-IN', { day:'numeric', month:'short', year:'numeric' });
  }

  function chip(elId, val, transform) {
    var el = document.getElementById(elId);
    var valEl = document.getElementById(elId + 'Val');
    if (val) { if (valEl) valEl.textContent = transform ? transform(val) : val; el.style.display = 'inline-flex'; }
    else      { el.style.display = 'none'; }
  }

  function updatePreview() {
    var t = titleInp.value.trim();
    var prev = document.getElementById('prevTitle');
    prev.textContent = t || 'Job title will appear here';
    prev.style.color = t ? '' : 'var(--text3)';

    chip('prevType', typeInp.value);
    chip('prevDept', deptInp.value.trim());
    chip('prevLoc',  locInp.value.trim());
    chip('prevSal',  salInp.value.trim());
    var v = parseInt(vacInp.value, 10);
    chip('prevVac', (!isNaN(v) && v > 0) ? v + ' ' + (v === 1 ? 'vacancy' : 'vacancies') : '', null);

    document.getElementById('prevRemote').style.display   = remoteChk.checked ? 'inline-flex' : 'none';
    document.getElementById('prevFeatured').style.display = featChk.checked   ? 'inline-flex' : 'none';
    chip('prevDeadline', dlInp.value, function (v) { return 'Deadline: ' + fmtDate(v); });

    var d = descInp.value.trim();
    var prevDesc = document.getElementById('prevDesc');
    prevDesc.textContent = d ? (d.length > 160 ? d.slice(0,160) + '…' : d) : 'Description preview will appear here…';
    prevDesc.style.color = d ? 'var(--text2)' : '';

    var sv = (document.querySelector('input[name="status"]:checked') || {}).value || 'draft';
    document.getElementById('prevDot').style.background  = statusColors[sv];
    document.getElementById('prevStatus').textContent    = statusLabels[sv];
    document.getElementById('prevStatus').style.color    = statusColors[sv];
  }

  [titleInp, deptInp, locInp, salInp, vacInp, descInp].forEach(function (el) { el.addEventListener('input', updatePreview); });
  [typeInp, remoteChk, featChk, dlInp].forEach(function (el) { el.addEventListener('change', updatePreview); });
  document.querySelectorAll('input[name="status"]').forEach(function (r) { r.addEventListener('change', updatePreview); });
  updatePreview();

  /* —€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€
     CHARACTER COUNTERS (shared helper)
  —€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€ */
  function attachCounter(inputId, counterId, max) {
    var inp = document.getElementById(inputId);
    var cnt = document.getElementById(counterId);
    if (!inp || !cnt) return;
    function update() {
      var len = inp.value.length;
      cnt.textContent = max ? len + ' / ' + max : len + ' chars';
      cnt.className   = 'counter' + (max && len > max * 0.9 ? (len >= max ? ' over' : ' warn') : '');
    }
    inp.addEventListener('input', update); update();
  }
  attachCounter('title',            'titleCounter',    150);
  attachCounter('description',      'descCounter',     null);
  attachCounter('meta_title',       'metaTitleCounter', 70);
  attachCounter('meta_description', 'metaDescCounter', 160);

  /* —€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€
     FORM SUBMIT
  —€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€—€ */
  var jobForm    = document.getElementById('jobForm');
  var publishBtn = document.getElementById('publishBtn');
  var draftBtn   = document.getElementById('draftBtn');

  jobForm.addEventListener('submit', function (e) {
    var action = (document.activeElement && document.activeElement.name === '_action')
      ? document.activeElement.value : 'publish';

    if (action === 'publish') {
      var ar = document.querySelector('input[name="status"][value="active"]');
      if (ar) ar.checked = true;
    }

    /* ensure slug */
    if (!slugInp.value.trim() && titleInp.value.trim())
      slugInp.value = toSlug(titleInp.value);

    /* flush tag input */
    var raw = skillInput.value.replace(/,/g,'').trim();
    if (raw) { addTag(raw); skillInput.value = ''; }

    /* validation */
    var valid = true;
    [[titleInp, true], [slugInp, true], [typeInp, true], [descInp, true]].forEach(function (pair) {
      var el = pair[0], req = pair[1];
      if (req && !el.value.trim()) { el.classList.add('err'); valid = false; }
      else el.classList.remove('err');
    });

    if (!valid) { e.preventDefault(); toast('Please fill in all required fields.', 'error'); return; }

    publishBtn.disabled = draftBtn.disabled = true;
    publishBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin .7s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Publishing…';
  });

}());
</script>
@endpush
