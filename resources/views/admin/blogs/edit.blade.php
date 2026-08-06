@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/blogs.css')
@endpush


@section('sidebar_blogs', 'active')
@section('page_title', 'Edit Blog Post')
@section('page_subtitle', 'Update content, status, and settings')

@section('content')
<div class="breadcrumb">
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
  <a href="{{ route('admin.blogs.index') }}">Blogs</a>
  <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Edit Post</span>
</div>

@if(session('success'))
<div class="flash-success">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="flash-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
  Please fix {{ $errors->count() }} error(s) before saving.
</div>
@endif

<div class="page-header">
  <div class="page-header-left">
    <h2>Edit: "{{ Str::limit($blog->title ?? 'Blog Post', 48) }}"</h2>
    <p>Post ID #{{ $blog->id ?? '—' }} · Last updated {{ $blog->updated_at?->diffForHumans() ?? 'recently' }}</p>
  </div>
  <div class="page-header-right">
    <a href="{{ route('blogs.show', $blog->slug ?? $blog->id) }}" target="_blank" class="btn btn-ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
      Preview
    </a>
    <x-button variant="ghost" href="{{ route('admin.blogs.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </x-button>
    <x-button variant="primary" type="submit">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Save Changes
    </x-button>
  </div>
</div>

<form id="editForm"
      method="POST"
      action="{{ route('admin.blogs.update', $blog) }}"
      enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="edit-layout">

    <div>
      <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            Post Details
          </div>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label" for="title">
              Title <span class="req">*</span>
              <span class="char-counter" id="titleCounter">0 / 100</span>
            </label>
            <input type="text" id="title" name="title"
              class="form-input {{ $errors->has('title') ? 'error' : '' }}"
              value="{{ old('title', $blog->title ?? '') }}"
              placeholder="Enter a compelling post title…"
              maxlength="100" required autocomplete="off">
            @error('title')
            <span class="form-error">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              {{ $message }}
            </span>
            @enderror
          </div>
          <div class="form-group">
            <label class="form-label" for="slug">URL Slug</label>
            <div class="slug-wrap">
              <span class="slug-prefix">/blog/</span>
              <input type="text" id="slug" name="slug"
                class="form-input slug-input {{ $errors->has('slug') ? 'error' : '' }}"
                value="{{ old('slug', $blog->slug ?? '') }}"
                placeholder="auto-generated-from-title"
                autocomplete="off">
            </div>
            <span class="form-hint">Leave blank to auto-generate. Lowercase letters, numbers, hyphens only.</span>
            @error('slug')
            <span class="form-error"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
            @enderror
          </div>
          <div class="form-row">
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label" for="category_id">Category</label>
              <select id="category_id" name="category_id" class="form-select">
                <option value="">Select category…</option>
                @foreach($categories ?? [] as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $blog->category_id ?? '') == $cat->id)>
                  {{ $cat->name }}
                </option>
                @endforeach
              </select>
              @error('category_id')
              <span class="form-error"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label" for="read_time_minutes">Read Time (min)</label>
              <input type="number" id="read_time_minutes" name="read_time_minutes"
                min="1" max="60"
                class="form-input {{ $errors->has('read_time_minutes') ? 'error' : '' }}"
                value="{{ old('read_time_minutes', $blog->read_time_minutes ?? 1) }}">
              @error('read_time_minutes')
              <span class="form-error"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
              @enderror
            </div>
          </div>
        </div>
      </div>

      <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10"/></svg>
            </div>
            Excerpt
          </div>
          <span id="excerptCounter" style="font-size:11px;color:var(--text3);font-family:var(--mono);">0 / 200</span>
        </div>
        <div class="card-body">
          <div class="form-group" style="margin-bottom:0;">
            <textarea id="excerpt" name="excerpt"
              class="form-textarea {{ $errors->has('excerpt') ? 'error' : '' }}"
              placeholder="A short compelling summary shown in cards and previews…"
              maxlength="200" rows="3">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
            <span class="form-hint">Shown on blog listing cards. Keep under 160 chars for best SEO.</span>
            @error('excerpt')
            <span class="form-error"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
            @enderror
          </div>
        </div>
      </div>

      <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            Content
          </div>
        </div>
        <div class="editor-toolbar">
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg></x-button>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg></x-button>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3v7a6 6 0 006 6 6 6 0 006-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg></x-button>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.3 12H3m3.6-5s.9-2 3.4-2c2.3 0 3.4 1.4 3.4 1.4"/><path stroke-linecap="round" stroke-linejoin="round" d="M10 17s.8 2 3.3 2c2.5 0 3.7-1.5 3.7-2.6 0-.4 0-.8-.2-1.4"/></svg></x-button>
          <div class="editor-divider"></div>
          <x-button variant="ghost" type="button" class="editor-btn">H2</x-button>
          <x-button variant="ghost" type="button" class="editor-btn">H3</x-button>
          <div class="editor-divider"></div>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><line x1="4" y1="6" x2="4.01" y2="6"/><line x1="4" y1="12" x2="4.01" y2="12"/><line x1="4" y1="18" x2="4.01" y2="18"/></svg></x-button>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h1v4"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 10h2"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg></x-button>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1zm12 0c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/></svg></x-button>
          <div class="editor-divider"></div>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg></x-button>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L17.94 6M10.5 10.5L8 18M17.5 6.5L13 19"/></svg></x-button>
          <div class="editor-divider"></div>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 00-4-4H4"/></svg></x-button>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><polyline points="15 14 20 9 15 4"/><path d="M4 20v-7a4 4 0 014-4h12"/></svg></x-button>
        </div>
        <div id="editor" class="editor-content" contenteditable="true" spellcheck="true">{!! old('content', $blog->content ?? '') !!}</div>
        <input type="hidden" name="content" id="contentInput">
        <div class="editor-footer">
          <span id="wordCount">0 words</span>
          <span id="charCount">0 characters</span>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </div>
            SEO &amp; Meta
          </div>
          <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--text3);font-family:var(--mono);">
            Score <span id="seoScoreVal" style="color:var(--a);font-weight:700;">0%</span>
          </div>
        </div>
        <div class="card-body">
          <div class="seo-score-bar" style="margin-bottom:14px;">
            <div class="seo-score-fill" id="seoFill" style="width:0%"></div>
          </div>
          <div class="seo-checks" style="margin-bottom:20px;">
            <div class="seo-check fail" id="chkTitle"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> Title is 40–65 characters</div>
            <div class="seo-check fail" id="chkExcerpt"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> Excerpt is under 160 characters</div>
            <div class="seo-check fail" id="chkSlug"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> URL slug is set</div>
            <div class="seo-check fail" id="chkContent"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> Content is at least 300 words</div>
            <div class="seo-check fail" id="chkImage"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> Cover image uploaded</div>
          </div>
          <div class="form-group">
            <label class="form-label" for="meta_title">
              Meta Title
              <span class="char-counter" id="metaTitleCounter">0 / 65</span>
            </label>
            <input type="text" id="meta_title" name="meta_title"
              class="form-input"
              value="{{ old('meta_title', $blog->meta_title ?? '') }}"
              placeholder="Leave blank to use post title…" maxlength="65">
            <span class="form-hint">Appears in browser tab and search results (40–65 chars ideal).</span>
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label" for="meta_description">
              Meta Description
              <span class="char-counter" id="metaDescCounter">0 / 160</span>
            </label>
            <textarea id="meta_description" name="meta_description"
              class="form-textarea" rows="2" maxlength="160"
              placeholder="Leave blank to use excerpt…">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
            <span class="form-hint">Shown in Google results. Aim for 120–160 characters.</span>
          </div>
        </div>
      </div>
    </div>

    <div>
      <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            Status
          </div>
          <span class="badge b-{{ $blog->status ?? 'draft' }}">
            <span class="badge-dot"></span>{{ ucfirst($blog->status ?? 'draft') }}
          </span>
        </div>
        <div class="card-body">
          @php $curStatus = old('status', $blog->status ?? 'draft'); @endphp

          <label class="status-option {{ $curStatus === 'approved' ? 'selected' : '' }}">
            <input type="radio" name="status" value="approved" {{ $curStatus === 'approved' ? 'checked' : '' }}>
            <span class="status-dot sd-approved"></span>
            <div><div class="status-option-label">Approved</div><div class="status-option-desc">Visible to all visitors</div></div>
          </label>
          <label class="status-option {{ $curStatus === 'pending' ? 'selected' : '' }}">
            <input type="radio" name="status" value="pending" {{ $curStatus === 'pending' ? 'checked' : '' }}>
            <span class="status-dot sd-pending"></span>
            <div><div class="status-option-label">Pending Review</div><div class="status-option-desc">Awaiting admin approval</div></div>
          </label>
          <label class="status-option {{ $curStatus === 'rejected' ? 'selected' : '' }}">
            <input type="radio" name="status" value="rejected" {{ $curStatus === 'rejected' ? 'checked' : '' }}>
            <span class="status-dot sd-rejected"></span>
            <div><div class="status-option-label">Rejected</div><div class="status-option-desc">Hidden, author notified</div></div>
          </label>
          <label class="status-option {{ $curStatus === 'draft' ? 'selected' : '' }}">
            <input type="radio" name="status" value="draft" {{ $curStatus === 'draft' ? 'checked' : '' }}>
            <span class="status-dot sd-draft"></span>
            <div><div class="status-option-label">Draft</div><div class="status-option-desc">Only visible to editors</div></div>
          </label>

          <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border);display:flex;gap:8px;">
            <x-button variant="primary" type="submit">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Save
            </x-button>
            <x-button variant="secondary" href="{{ route('admin.blogs.index') }}">Cancel</x-button>
          </div>
        </div>
      </div>

      <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            Options
          </div>
        </div>
        <div class="card-body">
          @php $isFeatured = old('is_featured', $blog->is_featured ?? false); @endphp
          <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);">
            <div>
              <div style="font-size:13px;font-weight:500;color:var(--text);">Featured Post</div>
              <div style="font-size:11px;color:var(--text3);">Show in featured strip</div>
            </div>
            <div style="position:relative;display:inline-block;width:40px;height:22px;">
              <input type="hidden" name="is_featured" value="0">
              <input type="checkbox" name="is_featured" value="1" id="isFeatured"
                {{ $isFeatured ? 'checked' : '' }}
                style="opacity:0;width:0;height:0;position:absolute;">
              <span id="toggleTrack"
                onclick="toggleSwitch('isFeatured','toggleTrack','toggleThumb')"
                style="position:absolute;inset:0;background:{{ $isFeatured ? 'var(--a)' : 'var(--border2)' }};border-radius:100px;cursor:pointer;transition:background .2s;">
                <span id="toggleThumb" style="position:absolute;left:{{ $isFeatured ? '20px' : '2px' }};top:2px;width:18px;height:18px;border-radius:50%;background:#fff;transition:left .2s;box-shadow:0 1px 4px rgba(0,0,0,.2);"></span>
              </span>
            </div>
          </div>

          @php $allowComments = old('allow_comments', $blog->allow_comments ?? true); @endphp
          <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;">
            <div>
              <div style="font-size:13px;font-weight:500;color:var(--text);">Allow Comments</div>
              <div style="font-size:11px;color:var(--text3);">Readers can leave comments</div>
            </div>
            <div style="position:relative;display:inline-block;width:40px;height:22px;">
              <input type="hidden" name="allow_comments" value="0">
              <input type="checkbox" name="allow_comments" value="1" id="allowComments"
                {{ $allowComments ? 'checked' : '' }}
                style="opacity:0;width:0;height:0;position:absolute;">
              <span id="toggleTrack2"
                onclick="toggleSwitch('allowComments','toggleTrack2','toggleThumb2')"
                style="position:absolute;inset:0;background:{{ $allowComments ? 'var(--a)' : 'var(--border2)' }};border-radius:100px;cursor:pointer;transition:background .2s;">
                <span id="toggleThumb2" style="position:absolute;left:{{ $allowComments ? '20px' : '2px' }};top:2px;width:18px;height:18px;border-radius:50%;background:#fff;transition:left .2s;box-shadow:0 1px 4px rgba(0,0,0,.2);"></span>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            Cover Image
          </div>
        </div>
        <div class="card-body">
          <div id="coverPreviewWrap" style="{{ $blog->cover_image ? '' : 'display:none;' }}">
            <div class="cover-preview-wrap" style="margin-bottom:10px;">
              <img id="coverPreview"
                src="{{ $blog->cover_image ? asset('storage/'.$blog->cover_image) : '' }}"
                alt="Cover">
              <div class="cover-preview-actions">
                <x-button variant="destructive" type="button" class="cover-preview-btn cpb-remove">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </x-button>
                <x-button variant="primary" type="button" class="cover-preview-btn cpb-change">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                </x-button>
              </div>
            </div>
          </div>

          <div id="coverDropzone" class="cover-drop" style="{{ $blog->cover_image ? 'display:none;' : '' }}">
            <input type="file" id="coverInput" name="cover_image" accept="image/*" onchange="previewCover(this)">
            <div class="cover-drop-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p>Drop image here or click to upload</p>
            <span>PNG, JPG, WebP — max 5MB</span>
          </div>

          <input type="hidden" name="remove_cover" id="removeCoverFlag" value="0">

          @error('cover_image')
          <span class="form-error" style="margin-top:6px;">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $message }}
          </span>
          @enderror
        </div>
      </div>

      <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
            </div>
            Tags
          </div>
        </div>
        <div class="card-body">
          @php
            $oldTags = old('tags');
            if ($oldTags !== null) {
              $tagNames = array_values(array_filter(array_map('trim', explode(',', $oldTags))));
            } else {
              $tagNames = $blog->tags->pluck('name')->map(fn($n) => trim($n))->filter()->values()->toArray();
            }
            $tagsHiddenValue = implode(',', $tagNames);
          @endphp

          <div class="tags-input-wrap" id="tagsWrap" onclick="document.getElementById('tagInput').focus()">
            @foreach($tagNames as $tagName)
            <span class="tag-chip" data-tag="{{ $tagName }}">
              {{ $tagName }}
              <button type="button" onclick="removeTag(this.parentElement)">×</button>
            </span>
            @endforeach
            <input type="text" id="tagInput" class="tags-real-input" placeholder="Add tag, press Enter…" autocomplete="off">
          </div>
          <input type="hidden" name="tags" id="tagsHidden" value="{{ $tagsHiddenValue }}">
          <span class="form-hint" style="margin-top:6px;">Press Enter or comma to add a tag.</span>
        </div>
      </div>

      <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            Post Info
          </div>
        </div>
        <div class="card-body" style="padding:14px 16px;">
          <div class="meta-info">
            <div class="meta-row">
              <span class="meta-key">ID</span>
              <span class="meta-val">#{{ $blog->id ?? '—' }}</span>
            </div>
            <div class="meta-row">
              <span class="meta-key">Author</span>
              <span class="meta-val">{{ $blog->author->name ?? '—' }}</span>
            </div>
            <div class="meta-row">
              <span class="meta-key">Created</span>
              <span class="meta-val">{{ $blog->created_at?->format('d M Y') ?? '—' }}</span>
            </div>
            <div class="meta-row">
              <span class="meta-key">Updated</span>
              <span class="meta-val">{{ $blog->updated_at?->diffForHumans() ?? '—' }}</span>
            </div>
            <div class="meta-row">
              <span class="meta-key">Views</span>
              <span class="meta-val">{{ number_format($blog->views_count ?? 0) }}</span>
            </div>
            <div class="meta-row">
              <span class="meta-key">Likes</span>
              <span class="meta-val">{{ number_format($blog->likes_count ?? 0) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</form>

<div style="margin-top:20px;max-width:290px;margin-left:auto;">
  <div class="card" style="border-color:rgba(240,68,68,.25);">
    <div class="card-header" style="background:rgba(240,68,68,.04);">
      <div class="card-title" style="color:var(--red);">
        <div class="card-title-icon" style="background:rgba(240,68,68,.12);">
          <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        Danger Zone
      </div>
    </div>
    <div class="card-body">
      <p style="font-size:12.5px;color:var(--text2);margin-bottom:12px;line-height:1.6;">
        Soft-delete this post. It can be restored later from the admin panel.
      </p>
      <form method="POST"
            action="{{ route('admin.blogs.destroy', $blog) }}"
            onsubmit="return confirm('Delete \'{{ addslashes($blog->title ?? '') }}\'?\nThis will soft-delete the post.')">
        @csrf
        @method('DELETE')
        <x-button variant="destructive" type="submit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete Post
        </x-button>
      </form>
    </div>
  </div>
</div>
@endsection

@push('page_scripts')
<script>
(function () {
'use strict';

/* —€—€ RICH TEXT EDITOR —€—€ */
var editor       = document.getElementById('editor');
var contentInput = document.getElementById('contentInput');

document.querySelectorAll('.editor-btn[data-cmd]').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var cmd = this.dataset.cmd;
    if      (cmd === 'h2')         { document.execCommand('formatBlock', false, 'h2'); }
    else if (cmd === 'h3')         { document.execCommand('formatBlock', false, 'h3'); }
    else if (cmd === 'blockquote') { document.execCommand('formatBlock', false, 'blockquote'); }
    else if (cmd === 'createLink') {
      var url = prompt('Enter URL:');
      if (url) document.execCommand('createLink', false, url);
    } else {
      document.execCommand(cmd, false, null);
    }
    editor.focus();
    updateCounts();
  });
});

function updateCounts() {
  var text  = editor.innerText || '';
  var words = text.trim() ? text.trim().split(/\s+/).length : 0;
  document.getElementById('wordCount').textContent = words + ' word' + (words !== 1 ? 's' : '');
  document.getElementById('charCount').textContent = text.length + ' character' + (text.length !== 1 ? 's' : '');
  contentInput.value = editor.innerHTML;
  updateSEO();
}
editor.addEventListener('input', updateCounts);
updateCounts();

/* —€—€ TITLE COUNTER + AUTO-SLUG —€—€ */
var titleInput = document.getElementById('title');
var slugInput  = document.getElementById('slug');
if (slugInput.value.trim()) { slugInput.dataset.manual = '1'; }

function updateTitleCounter() {
  var len = titleInput.value.length;
  document.getElementById('titleCounter').textContent = len + ' / 100';
  if (!slugInput.dataset.manual) {
    slugInput.value = titleInput.value.toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-')
      .replace(/-+/g, '-').replace(/^-|-$/g, '');
  }
  updateSEO();
}
titleInput.addEventListener('input', updateTitleCounter);
slugInput.addEventListener('input', function () { this.dataset.manual = '1'; });
updateTitleCounter();

/* —€—€ EXCERPT COUNTER —€—€ */
var excerptEl = document.getElementById('excerpt');
function updateExcerptCounter() {
  document.getElementById('excerptCounter').textContent = excerptEl.value.length + ' / 200';
  updateSEO();
}
excerptEl.addEventListener('input', updateExcerptCounter);
updateExcerptCounter();

/* —€—€ META COUNTERS —€—€ */
[['meta_title','metaTitleCounter',65],['meta_description','metaDescCounter',160]].forEach(function (cfg) {
  var el = document.getElementById(cfg[0]);
  var counter = document.getElementById(cfg[1]);
  var max = cfg[2];
  function upd() {
    var l = el.value.length;
    counter.textContent = l + ' / ' + max;
    counter.style.color = l > max * 0.9 ? 'var(--red)' : 'var(--text3)';
  }
  el.addEventListener('input', upd); upd();
});

/* —€—€ SEO CHECKER —€—€ */
function updateSEO() {
  var titleLen   = (document.getElementById('title').value || '').length;
  var excerptLen = (document.getElementById('excerpt').value || '').length;
  var slugVal    = (document.getElementById('slug').value || '').trim();
  var wordCount  = (editor.innerText || '').trim().split(/\s+/).filter(Boolean).length;
  var imgSrc     = document.getElementById('coverPreview').src;
  var hasImage   = imgSrc && imgSrc !== '' && imgSrc !== window.location.href;

  var checks = [
    { id:'chkTitle',   pass: titleLen >= 40 && titleLen <= 65,   label:'Title is 40–65 characters' },
    { id:'chkExcerpt', pass: excerptLen > 0 && excerptLen <= 160, label:'Excerpt is under 160 characters' },
    { id:'chkSlug',    pass: slugVal.length > 0,                  label:'URL slug is set' },
    { id:'chkContent', pass: wordCount >= 300,                    label:'Content is at least 300 words' },
    { id:'chkImage',   pass: !!hasImage,                          label:'Cover image uploaded' },
  ];

  var score = 0;
  checks.forEach(function (c) {
    var el = document.getElementById(c.id);
    if (!el) return;
    if (c.pass) {
      el.className = 'seo-check pass';
      el.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> ' + c.label;
      score++;
    } else {
      el.className = 'seo-check fail';
      el.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> ' + c.label;
    }
  });

  var pct  = Math.round((score / checks.length) * 100);
  var fill = document.getElementById('seoFill');
  fill.style.width      = pct + '%';
  fill.style.background = pct >= 80 ? 'linear-gradient(90deg,#059669,#05c48a)' :
                          pct >= 50 ? 'linear-gradient(90deg,var(--a),var(--a2))' :
                                      'linear-gradient(90deg,#dc2626,#f04444)';
  document.getElementById('seoScoreVal').textContent = pct + '%';
}
updateSEO();

/* —€—€ COVER IMAGE PREVIEW —€—€ */
window.previewCover = function (input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function (e) {
    document.getElementById('coverPreview').src                = e.target.result;
    document.getElementById('coverPreviewWrap').style.display  = '';
    document.getElementById('coverDropzone').style.display     = 'none';
    document.getElementById('removeCoverFlag').value           = '0';
    updateSEO();
  };
  reader.readAsDataURL(input.files[0]);
};

window.removeCover = function () {
  document.getElementById('coverPreview').src                = '';
  document.getElementById('coverPreviewWrap').style.display  = 'none';
  document.getElementById('coverDropzone').style.display     = '';
  document.getElementById('coverInput').value                = '';
  document.getElementById('removeCoverFlag').value           = '1';
  updateSEO();
};

var dz = document.getElementById('coverDropzone');
if (dz) {
  dz.addEventListener('dragover',  function (e) { e.preventDefault(); this.classList.add('drag-over'); });
  dz.addEventListener('dragleave', function ()  { this.classList.remove('drag-over'); });
  dz.addEventListener('drop', function (e) {
    e.preventDefault(); this.classList.remove('drag-over');
    if (e.dataTransfer.files[0]) {
      document.getElementById('coverInput').files = e.dataTransfer.files;
      previewCover(document.getElementById('coverInput'));
    }
  });
}

/* —€—€ TAGS CHIP INPUT —€—€ */
function syncTags() {
  var chips = document.querySelectorAll('#tagsWrap .tag-chip');
  var vals  = Array.from(chips).map(function (c) { return c.dataset.tag; });
  document.getElementById('tagsHidden').value = vals.join(',');
}
window.removeTag = function (chip) { chip.remove(); syncTags(); };

var tagInput = document.getElementById('tagInput');
tagInput.addEventListener('keydown', function (e) {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    var val = this.value.replace(/,/g, '').trim().toLowerCase();
    if (!val) return;
    var exists = Array.from(document.querySelectorAll('#tagsWrap .tag-chip'))
                      .some(function (c) { return c.dataset.tag === val; });
    if (!exists) {
      var chip         = document.createElement('span');
      chip.className   = 'tag-chip';
      chip.dataset.tag = val;
      chip.innerHTML   = val + '<button type="button" onclick="removeTag(this.parentElement)">×</button>';
      document.getElementById('tagsWrap').insertBefore(chip, tagInput);
      syncTags();
    }
    this.value = '';
  }
  if (e.key === 'Backspace' && !this.value) {
    var chips = document.querySelectorAll('#tagsWrap .tag-chip');
    if (chips.length) { chips[chips.length - 1].remove(); syncTags(); }
  }
});

/* —€—€ STATUS RADIO HIGHLIGHT —€—€ */
document.querySelectorAll('.status-option input[type="radio"]').forEach(function (radio) {
  radio.addEventListener('change', function () {
    document.querySelectorAll('.status-option').forEach(function (o) { o.classList.remove('selected'); });
    this.closest('.status-option').classList.add('selected');
  });
});

/* —€—€ TOGGLE SWITCHES —€—€ */
window.toggleSwitch = function (inputId, trackId, thumbId) {
  var cb     = document.getElementById(inputId);
  cb.checked = !cb.checked;
  var track  = document.getElementById(trackId);
  var thumb  = document.getElementById(thumbId);
  track.style.background = cb.checked ? 'var(--a)' : 'var(--border2)';
  thumb.style.left        = cb.checked ? '20px' : '2px';
};

/* —€—€ SYNC CONTENT ON SUBMIT —€—€ */
document.getElementById('editForm').addEventListener('submit', function () {
  contentInput.value = editor.innerHTML;
});

})();
</script>
@endpush
