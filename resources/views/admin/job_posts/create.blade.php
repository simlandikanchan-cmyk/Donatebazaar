@push('page_css')
@vite('resources/css/admin/entries/jobs-index.css')
@endpush

@extends('layouts.admin')

@section('sidebar_job_posts', 'active')
@section('page_title', 'Create Job Post')
@section('page_subtitle', 'Add a new job listing')

@push('page_styles')
@vite('resources/css/admin/entries/jobs-create.css')
<style>
@media(max-width:860px){
  .form-layout{grid-template-columns:1fr!important}
  .form-layout > div:last-child{order:-1}
}
@media(max-width:640px){
  .field-row field{grid-template-columns:1fr!important}
  .field-row field > div{width:100%!important}
  .submit-row{flex-wrap:wrap}
  .submit-btns{width:100%;margin-top:8px;justify-content:stretch}
  .submit-btns .btn{flex:1;justify-content:center}
}
</style>
@endpush

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
        <a href="{{ route('admin.job_posts.index') }}" class="hero-btn hero-btn-ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          Back to Listings
        </a>
      </div>
    </div>

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
    <div class="alert alert-error">
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

        {{-- ── LEFT: MAIN FIELDS ── --}}
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
                  maxlength="255" autocomplete="off" data-old="{{ old('slug') ? '1' : '' }}">
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
              <div class="tags-wrap" id="tagsWrap">
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
                <a href="{{ route('admin.job_posts.index') }}" class="btn btn-secondary">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                  Discard
                </a>
                <button type="submit" name="_action" value="draft" class="btn btn-secondary btn-draft" id="draftBtn">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                  Save Draft
                </button>
                <button type="submit" name="_action" value="publish" class="btn btn-primary" id="publishBtn">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  Publish Job
                </button>
              </div>
            </div>
          </div>{{-- /card 4 --}}

        </div>{{-- /left col --}}

        {{-- ── RIGHT: STICKY PANEL ── --}}
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
@vite('resources/js/admin/entries/jobs-create.js')
@endpush