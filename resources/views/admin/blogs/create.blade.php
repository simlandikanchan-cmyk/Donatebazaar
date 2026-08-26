@push('page_css')
@vite('resources/css/admin/entries/blogs-list.css')
@endpush

@extends('layouts.admin')

@section('sidebar_blogs', 'active')
@section('page_title', 'Create Blog')
@section('page_subtitle', 'Admin — publish directly or schedule')

@push('page_styles')
@vite('resources/css/admin/entries/blogs-create.css')
@endpush
@section('content')
<div class="admin-badge">Admin Publishing Mode — Direct Publish Available</div>

<div class="page-hdr">
    <div class="page-hdr-left">
        <h2>Create New Blog Post</h2>
        <p>Fill in all fields — admins can publish directly, schedule, or save as draft</p>
    </div>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-draft" style="text-decoration:none;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        All Blogs
    </a>
</div>

@php
    $totalBlogs     = \App\Models\Blog::count();
    $publishedBlogs = \App\Models\Blog::where('status','approved')->count();
    $draftBlogs     = \App\Models\Blog::where('status','draft')->count();
    $pendingBlogs   = \App\Models\Blog::where('status','pending')->count();
@endphp
<div class="stat-strip">
    <span class="stat-strip-item"><span class="stat-dot blue"></span> {{ $totalBlogs }} Total</span>
    <span class="stat-strip-item"><span class="stat-dot green"></span> {{ $publishedBlogs }} Published</span>
    <span class="stat-strip-item"><span class="stat-dot amber"></span> {{ $draftBlogs }} Drafts</span>
</div>

@if($errors->any())
<div class="alert alert-error">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <div>
        <strong>Please fix {{ $errors->count() }} error{{ $errors->count() > 1 ? 's' : '' }} below</strong>
    </div>
    <button type="button" class="alert-close" data-action="alert-close">&times;</button>
</div>
@endif

<div class="progress-stepper" id="progressStepper">
    <div class="stepper-step active" data-section="sec-basic">
        <div class="stepper-step-num"><span>1</span></div>
        <span class="stepper-step-label">Basic Info</span>
        <span class="stepper-step-count" id="stepper-basic-count">0/2</span>
    </div>
    <div class="stepper-divider"></div>
    <div class="stepper-step" data-section="sec-cover">
        <div class="stepper-step-num"><span>2</span></div>
        <span class="stepper-step-label">Cover</span>
        <span class="stepper-step-count" id="stepper-cover-count">0/1</span>
    </div>
    <div class="stepper-divider"></div>
    <div class="stepper-step" data-section="sec-content">
        <div class="stepper-step-num"><span>3</span></div>
        <span class="stepper-step-label">Content</span>
        <span class="stepper-step-count" id="stepper-content-count">0/1</span>
    </div>
    <div class="stepper-divider"></div>
    <div class="stepper-step" data-section="sec-seo">
        <div class="stepper-step-num"><span>4</span></div>
        <span class="stepper-step-label">SEO</span>
        <span class="stepper-step-count" id="stepper-seo-count">0/0</span>
    </div>
    <div class="stepper-divider"></div>
    <div class="stepper-step" data-section="sec-admin">
        <div class="stepper-step-num"><span>5</span></div>
        <span class="stepper-step-label">Admin</span>
    </div>
</div>

<div class="progress-bar-wrap">
    <div class="progress-bar-track">
        <div class="progress-bar-fill" id="progressBarFill"></div>
    </div>
</div>

<form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
    @csrf

    <div class="editor-layout">

        {{-- ══ LEFT COLUMN ══ --}}
        <div class="form-col">

            {{-- Section 1: Basic Info --}}
            <div class="form-section is-open" id="sec-basic" data-step="1">
                <div class="section-header" data-toggle="sec-basic">
                    <div class="section-step"><span>1</span></div>
                    <div class="section-info">
                        <div class="section-title">Basic Information</div>
                        <div class="section-sub">Tell readers what your blog is about</div>
                    </div>
                    <span class="section-count" id="sec-basic-count">0/2</span>
                    <svg class="section-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="section-body">
                    <div class="section-body-inner" style="padding-top:18px;">
                        <div class="field">
                            <label class="field-label" for="title">
                                Title <span>*</span>
                                <span class="char-inline" id="titleCounter">0/255</span>
                            </label>
                            <input id="title" name="title" type="text" value="{{ old('title') }}"
                                class="field-input {{ $errors->has('title') ? 'is-error' : '' }}"
                                placeholder="Enter a compelling blog title…" maxlength="255" required>
                            @error('title')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field-grid">
                            <div class="field">
                                <label class="field-label" for="category_id">Category <span>*</span></label>
                                <select id="category_id" name="category_id" class="field-select" required>
                                    <option value="">Select category…</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<p class="field-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="field">
                                <label class="field-label" for="tag_ids">Tags</label>
                                <div class="tag-select" id="tagContainer">
                                    @foreach($tags as $tag)
                                        <label class="tag-chip" data-tag-id="{{ $tag->id }}">
                                            <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" hidden
                                                @checked(in_array($tag->id, old('tag_ids', [])))>
                                            <span>{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="field-hint">Click tags to add — helps readers discover your blog</p>
                            </div>
                        </div>
                        <div class="field">
                            <label class="field-label" for="excerpt">
                                Excerpt
                                <span class="char-inline" id="excerptCounter">0</span>
                            </label>
                            <textarea id="excerpt" name="excerpt" rows="3" class="field-textarea"
                                placeholder="Short description shown on listing pages…" maxlength="500">{{ old('excerpt') }}</textarea>
                            <p class="field-hint">Keep it under 160 characters for best SEO results</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Cover Image --}}
            <div class="form-section is-open" id="sec-cover" data-step="2">
                <div class="section-header" data-toggle="sec-cover">
                    <div class="section-step"><span>2</span></div>
                    <div class="section-info">
                        <div class="section-title">Cover Image</div>
                        <div class="section-sub">Add a striking visual to grab attention</div>
                    </div>
                    <span class="section-count" id="sec-cover-count">0/1</span>
                    <svg class="section-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="section-body">
                    <div class="section-body-inner" style="padding-top:18px;">
                        <div class="upload-zone" id="uploadZone">
                            <input type="file" name="cover_image" accept="image/*" id="coverUpload">
                            <div class="upload-placeholder" id="uploadPlaceholder">
                                <div class="upload-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                </div>
                                <div class="upload-text" id="uploadText">Click to upload or drag &amp; drop image</div>
                                <div class="upload-sub">JPG, PNG, WebP · Max 5MB · Recommended 1200×630px</div>
                            </div>
                            <div class="upload-preview-wrap" id="uploadPreviewWrap" style="display:none;">
                                <img id="uploadPreview" class="upload-preview" alt="Cover preview">
                                <button type="button" class="upload-remove" id="uploadRemove" title="Remove image">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Content --}}
            <div class="form-section is-open" id="sec-content" data-step="3">
                <div class="section-header" data-toggle="sec-content">
                    <div class="section-step"><span>3</span></div>
                    <div class="section-info">
                        <div class="section-title">Content <span style="color:var(--red);margin-left:2px;">*</span></div>
                        <div class="section-sub">Write your blog post — make it compelling</div>
                    </div>
                    <span class="section-count" id="sec-content-count">0/1</span>
                    <svg class="section-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="section-body">
                    <div class="section-body-inner" style="padding-top:18px;">
                        <div class="editor-toolbar" id="editorToolbar">
                            <button type="button" class="tb-btn" data-cmd="bold" title="Bold (Ctrl+B)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg>
                            </button>
                            <button type="button" class="tb-btn" data-cmd="italic" title="Italic (Ctrl+I)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>
                            </button>
                            <button type="button" class="tb-btn" data-cmd="underline" title="Underline (Ctrl+U)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 3v7a6 6 0 006 6 6 6 0 006-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg>
                            </button>
                            <span class="tb-divider"></span>
                            <button type="button" class="tb-btn" data-cmd="heading" title="Heading">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 12h12M6 4v16M18 4v16"/></svg>
                            </button>
                            <button type="button" class="tb-btn" data-cmd="bullet" title="Bullet List">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            </button>
                            <button type="button" class="tb-btn" data-cmd="link" title="Insert Link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                            </button>
                            <span class="tb-divider"></span>
                            <button type="button" class="tb-btn" data-cmd="preview" title="Preview">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        <div class="editor-wrap">
                            <textarea id="blogContent" name="content" rows="20"
                                class="field-textarea editor-ta {{ $errors->has('content') ? 'is-error' : '' }}"
                                placeholder="Start writing your blog here…" required>{{ old('content') }}</textarea>
                            <div class="editor-footer">
                                <span class="read-time-badge" id="readTimeBadge">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span id="readTimeText">0 min</span>
                                </span>
                                <span class="char-count" id="charCount">0 chars</span>
                            </div>
                        </div>
                        @error('content')<p class="field-error">{{ $message }}</p>@enderror
                        <input type="hidden" name="read_time_minutes" id="readTimeInput" value="{{ old('read_time_minutes', 0) }}">
                    </div>
                </div>
            </div>

            {{-- Section 4: SEO --}}
            <div class="form-section" id="sec-seo" data-step="4">
                <div class="section-header" data-toggle="sec-seo">
                    <div class="section-step"><span>4</span></div>
                    <div class="section-info">
                        <div class="section-title">SEO Settings</div>
                        <div class="section-sub">Optimise how your blog appears in search</div>
                    </div>
                    <span class="section-count" id="sec-seo-count">0/0</span>
                    <svg class="section-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="section-body">
                    <div class="section-body-inner" style="padding-top:18px;">
                        <div class="field">
                            <label class="field-label" for="meta_title">
                                SEO Title
                                <span class="char-inline" id="metaTitleCounter">0</span>
                            </label>
                            <input id="meta_title" name="meta_title" type="text" value="{{ old('meta_title') }}"
                                class="field-input" placeholder="Leave blank to use the blog title" maxlength="70">
                            <p class="field-hint">Recommended: 50–60 characters · Leave blank to auto-use the blog title</p>
                        </div>
                        <div class="field">
                            <label class="field-label" for="meta_description">
                                Meta Description
                                <span class="char-inline desc-status" id="metaDescCounter">0 / 160</span>
                            </label>
                            <textarea id="meta_description" name="meta_description" rows="3"
                                class="field-textarea" maxlength="160"
                                placeholder="Brief summary for search engines…">{{ old('meta_description') }}</textarea>
                            <p class="field-hint">120–160 characters ideal · Leave blank to auto-generate from excerpt</p>
                        </div>
                        <div class="field-grid">
                            <div class="field">
                                <label class="field-label" for="slug">Custom Slug <small>optional</small></label>
                                <input id="slug" name="slug" type="text" value="{{ old('slug') }}" class="field-input" placeholder="auto-generated-from-title">
                                <p class="field-hint">Leave blank to auto-generate</p>
                            </div>
                            <div class="field">
                                <label class="field-label" for="canonical_url">Canonical URL <small>optional</small></label>
                                <input id="canonical_url" name="canonical_url" type="url" value="{{ old('canonical_url') }}" class="field-input" placeholder="https://…">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 5: Admin Controls --}}
            <div class="form-section admin-section" id="sec-admin" data-step="5">
                <div class="section-header" data-toggle="sec-admin">
                    <div class="section-step"><span>5</span></div>
                    <div class="section-info">
                        <div class="section-title">Admin Controls</div>
                        <div class="section-sub">Publish settings, visibility, and attribution</div>
                    </div>
                    <span class="section-badge admin">Admin Only</span>
                    <svg class="section-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="section-body">
                    <div class="section-body-inner" style="padding-top:18px;">

                        {{-- Publish Controls --}}
                        <div style="margin-bottom:18px;">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text3);margin-bottom:12px;font-family:var(--mono);">Publish Controls</div>
                            <div class="toggle-row">
                                <div class="toggle-info">
                                    <div class="toggle-label">Publish Immediately</div>
                                    <div class="toggle-desc">Make this post live as soon as you save</div>
                                </div>
                                <div class="toggle-switch">
                                    <input type="checkbox" id="publishNow" name="publish_now" value="1" {{ old('publish_now') ? 'checked' : '' }}>
                                    <label for="publishNow"></label>
                                </div>
                            </div>
                            <div class="toggle-row">
                                <div class="toggle-info">
                                    <div class="toggle-label">Schedule for Later</div>
                                    <div class="toggle-desc">Set a specific date and time to publish</div>
                                </div>
                                <div class="toggle-switch">
                                    <input type="checkbox" id="scheduleToggle" name="schedule_toggle" value="1">
                                    <label for="scheduleToggle"></label>
                                </div>
                            </div>
                            <div class="schedule-row" id="scheduleRow">
                                <div class="field-grid">
                                    <div class="field" style="margin-bottom:0">
                                        <label class="field-label" for="scheduled_at_date">Publish Date <span>*</span></label>
                                        <input type="date" id="scheduled_at_date" name="scheduled_at_date" class="field-input" value="{{ old('scheduled_at_date') }}">
                                    </div>
                                    <div class="field" style="margin-bottom:0">
                                        <label class="field-label" for="scheduled_at_time">Publish Time <span>*</span></label>
                                        <input type="time" id="scheduled_at_time" name="scheduled_at_time" class="field-input" value="{{ old('scheduled_at_time', '09:00') }}">
                                    </div>
                                </div>
                                <p class="field-hint" style="margin-top:10px">Timezone: Asia/Kolkata (IST) · Server will auto-publish at this time</p>
                            </div>
                        </div>

                        <div style="height:1px;background:var(--border);margin:18px 0;"></div>

                        {{-- Visibility & Features --}}
                        <div style="margin-bottom:18px;">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text3);margin-bottom:12px;font-family:var(--mono);">Visibility &amp; Features</div>
                            <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Featured Post</div><div class="toggle-desc">Show in homepage featured blog section</div></div><div class="toggle-switch"><input type="checkbox" id="isFeatured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}><label for="isFeatured"></label></div></div>
                            <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Pinned to Top</div><div class="toggle-desc">Always show at the top of blog listings</div></div><div class="toggle-switch"><input type="checkbox" id="isPinned" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}><label for="isPinned"></label></div></div>
                            <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Allow Comments</div><div class="toggle-desc">Let readers comment on this post</div></div><div class="toggle-switch"><input type="checkbox" id="allowComments" name="allow_comments" value="1" checked {{ old('allow_comments',1) ? 'checked' : '' }}><label for="allowComments"></label></div></div>
                            <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Allow Likes</div><div class="toggle-desc">Let readers like / react to this post</div></div><div class="toggle-switch"><input type="checkbox" id="allowLikes" name="allow_likes" value="1" checked {{ old('allow_likes',1) ? 'checked' : '' }}><label for="allowLikes"></label></div></div>
                            <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Show Share Buttons</div><div class="toggle-desc">Display social share options on post</div></div><div class="toggle-switch"><input type="checkbox" id="showShare" name="show_share" value="1" checked {{ old('show_share',1) ? 'checked' : '' }}><label for="showShare"></label></div></div>
                            <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Newsletter Syndication</div><div class="toggle-desc">Include in next newsletter email blast</div></div><div class="toggle-switch"><input type="checkbox" id="syndicateNewsletter" name="syndicate_newsletter" value="1" {{ old('syndicate_newsletter') ? 'checked' : '' }}><label for="syndicateNewsletter"></label></div></div>
                        </div>

                        <div style="height:1px;background:var(--border);margin:18px 0;"></div>

                        {{-- Attribution & Campaign Link --}}
                        <div style="margin-bottom:18px;">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text3);margin-bottom:12px;font-family:var(--mono);">Attribution &amp; Campaign Link</div>
                            <div class="field-grid">
                                <div class="field">
                                    <label class="field-label" for="author_override">Author Override <small>optional</small></label>
                                    <input id="author_override" name="author_override" type="text" value="{{ old('author_override') }}" class="field-input" placeholder="Default: {{ auth()->user()->name }}">
                                    <p class="field-hint">Leave blank to use your admin name</p>
                                </div>
                                <div class="field">
                                    <label class="field-label" for="author_role_override">Author Role <small>optional</small></label>
                                    <input id="author_role_override" name="author_role_override" type="text" value="{{ old('author_role_override') }}" class="field-input" placeholder="e.g. Editor, Guest Writer">
                                </div>
                            </div>
                            <div class="field">
                                <label class="field-label" for="linked_campaign_id">Link to Campaign <small>optional</small></label>
                                <select id="linked_campaign_id" name="linked_campaign_id" class="field-select">
                                    <option value="">None — standalone blog post</option>
                                    @foreach($campaigns ?? [] as $campaign)
                                        <option value="{{ $campaign->id }}" @selected(old('linked_campaign_id') == $campaign->id)>{{ $campaign->title }}</option>
                                    @endforeach
                                </select>
                                <p class="field-hint">Linking shows a campaign donation box at the end of the blog post</p>
                            </div>
                            <div class="field-grid">
                                <div class="field">
                                    <label class="field-label" for="reading_level">Target Reading Level</label>
                                    <select id="reading_level" name="reading_level" class="field-select">
                                        <option value="general" @selected(old('reading_level','general') === 'general')>General Public (Grade 8–10)</option>
                                        <option value="educated" @selected(old('reading_level') === 'educated')>Educated Adults (Grade 12+)</option>
                                        <option value="expert"   @selected(old('reading_level') === 'expert')>Expert / Professional</option>
                                        <option value="simple"   @selected(old('reading_level') === 'simple')>Simple / Easy Read (Grade 6)</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label class="field-label" for="content_type">Content Type</label>
                                    <select id="content_type" name="content_type" class="field-select">
                                        <option value="article"   @selected(old('content_type','article') === 'article')>Article</option>
                                        <option value="story"     @selected(old('content_type') === 'story')>Impact Story</option>
                                        <option value="guide"     @selected(old('content_type') === 'guide')>How-To Guide</option>
                                        <option value="news"      @selected(old('content_type') === 'news')>News Update</option>
                                        <option value="interview" @selected(old('content_type') === 'interview')>Interview</option>
                                        <option value="listicle"  @selected(old('content_type') === 'listicle')>Listicle</option>
                                    </select>
                                </div>
                            </div>
                            <div class="field-grid">
                                <div class="field" style="margin-bottom:0">
                                    <label class="field-label" for="language">Language</label>
                                    <select id="language" name="language" class="field-select">
                                        <option value="en" @selected(old('language','en') === 'en')>English</option>
                                        <option value="hi" @selected(old('language') === 'hi')>हिन्दी (Hindi)</option>
                                        <option value="ta" @selected(old('language') === 'ta')>தமிழ் (Tamil)</option>
                                        <option value="te" @selected(old('language') === 'te')>తెలుగు (Telugu)</option>
                                        <option value="mr" @selected(old('language') === 'mr')>मराठी (Marathi)</option>
                                        <option value="bn" @selected(old('language') === 'bn')>বাংলা (Bengali)</option>
                                        <option value="gu" @selected(old('language') === 'gu')>ગુજરાતી (Gujarati)</option>
                                        <option value="kn" @selected(old('language') === 'kn')>ಕನ್ನಡ (Kannada)</option>
                                    </select>
                                </div>
                                <div class="field" style="margin-bottom:0"></div>
                            </div>
                        </div>

                        <div style="height:1px;background:var(--border);margin:18px 0;"></div>

                        {{-- OG / Social --}}
                        <div>
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text3);margin-bottom:12px;font-family:var(--mono);">Open Graph &amp; Social Sharing</div>
                            <div class="field">
                                <label class="field-label" for="og_title">OG Title <small>optional</small></label>
                                <input id="og_title" name="og_title" type="text" value="{{ old('og_title') }}" class="field-input" placeholder="Override title for Facebook, LinkedIn…">
                            </div>
                            <div class="field">
                                <label class="field-label" for="og_description">OG Description <small>optional</small></label>
                                <textarea id="og_description" name="og_description" rows="2" class="field-textarea" placeholder="Override description for social cards…">{{ old('og_description') }}</textarea>
                            </div>
                            <div class="field" style="margin-bottom:0">
                                <label class="field-label">OG Image <small>optional — defaults to cover image</small></label>
                                <div class="upload-zone" id="ogZone" style="padding:16px 20px;">
                                    <input type="file" name="og_image" accept="image/*" id="ogUpload">
                                    <div class="upload-placeholder" style="padding:12px 0;">
                                        <div class="upload-text" id="ogText" style="font-size:12px;">Click to upload OG image (1200×630 recommended)</div>
                                        <div class="upload-sub">JPG, PNG, WebP · Max 5MB</div>
                                    </div>
                                    <img id="ogPreview" class="upload-preview" alt="OG Preview" style="display:none;">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Sticky Action Bar --}}
            <div class="action-bar">
                <div class="action-bar-left">
                    <div class="action-indicator" id="unsavedIndicator">
                        <span class="unsaved-dot"></span>
                        <span class="unsaved-text">All saved</span>
                    </div>
                    <p class="action-bar-hint">
                        <strong>Draft</strong> saves privately ·
                        <strong>Publish</strong> goes live instantly ·
                        <strong>Schedule</strong> sets a future date
                    </p>
                </div>
                <div class="action-btns">
                    <button type="submit" name="action" value="draft" class="btn btn-draft">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Save Draft
                    </button>
                    <button type="submit" name="action" value="schedule" class="btn btn-schedule">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Schedule
                    </button>
                    <button type="submit" name="action" value="publish" class="btn btn-publish">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3l14 9-14 9V3z"/></svg>
                        Publish Now
                    </button>
                </div>
            </div>

        </div>

        {{-- ══ RIGHT PANEL ══ --}}
        <aside class="right-panel">

            <div class="p-card">
                <div class="p-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Content Score
                </div>
                <div class="score-ring-wrap">
                    <div class="score-ring">
                        <svg width="72" height="72" viewBox="0 0 72 72">
                            <circle class="score-ring-bg" cx="36" cy="36" r="30"/>
                            <circle class="score-ring-fill" id="scoreRingFill" cx="36" cy="36" r="30" stroke="#f04444" stroke-dasharray="188.5" stroke-dashoffset="188.5"/>
                        </svg>
                        <div class="score-ring-num" id="scoreNum">0</div>
                    </div>
                    <div>
                        <div class="score-info-title" id="scoreLabel">Not started</div>
                        <div class="score-info-sub" id="scoreSub">Fill in the form to build score</div>
                    </div>
                </div>
                <div class="q-checks">
                    <div class="q-check" id="qc-title"><div class="q-check-icon wait"><svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg></div><span>Title length (40–70 chars)</span><span class="q-check-val" id="qc-title-v">0</span></div>
                    <div class="q-check" id="qc-words"><div class="q-check-icon wait"><svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg></div><span>300+ words written</span><span class="q-check-val" id="qc-words-v">0 words</span></div>
                    <div class="q-check" id="qc-excerpt"><div class="q-check-icon wait"><svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg></div><span>Excerpt provided</span></div>
                    <div class="q-check" id="qc-image"><div class="q-check-icon wait"><svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg></div><span>Cover image uploaded</span></div>
                    <div class="q-check" id="qc-meta"><div class="q-check-icon wait"><svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg></div><span>Meta description</span></div>
                </div>
            </div>

            <div class="p-card">
                <div class="p-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Publish Checklist
                </div>
                <div class="checklist">
                    <div class="cl-item fail" id="cl-title"><div class="cl-dot fail"><svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg></div><span>Blog title</span><span class="cl-val fail" id="cl-title-v">Missing</span></div>
                    <div class="cl-item fail" id="cl-cat"><div class="cl-dot fail"><svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg></div><span>Category</span><span class="cl-val fail" id="cl-cat-v">Missing</span></div>
                    <div class="cl-item fail" id="cl-content"><div class="cl-dot fail"><svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg></div><span>Content written</span><span class="cl-val fail" id="cl-content-v">0 words</span></div>
                    <div class="cl-item fail" id="cl-cover"><div class="cl-dot fail"><svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg></div><span>Cover image</span><span class="cl-val fail" id="cl-cover-v">Not set</span></div>
                    <div class="cl-item warn" id="cl-excerpt"><div class="cl-dot warn"><svg viewBox="0 0 10 10"><rect x="4.25" y="1.5" width="1.5" height="4" rx=".75" fill="var(--amber)"/><rect x="4.25" y="7" width="1.5" height="1.5" rx=".75" fill="var(--amber)"/></svg></div><span>Excerpt</span><span class="cl-val warn" id="cl-excerpt-v">Optional</span></div>
                    <div class="cl-item warn" id="cl-seo"><div class="cl-dot warn"><svg viewBox="0 0 10 10"><rect x="4.25" y="1.5" width="1.5" height="4" rx=".75" fill="var(--amber)"/><rect x="4.25" y="7" width="1.5" height="1.5" rx=".75" fill="var(--amber)"/></svg></div><span>Meta description</span><span class="cl-val warn" id="cl-seo-v">Optional</span></div>
                    <div class="cl-item warn" id="cl-tags"><div class="cl-dot warn"><svg viewBox="0 0 10 10"><rect x="4.25" y="1.5" width="1.5" height="4" rx=".75" fill="var(--amber)"/><rect x="4.25" y="7" width="1.5" height="1.5" rx=".75" fill="var(--amber)"/></svg></div><span>Tags</span><span class="cl-val warn" id="cl-tags-v">Optional</span></div>
                </div>
                <div class="ready-row">
                    <span class="ready-lbl">Ready to publish?</span>
                    <span class="ready-badge none" id="readyBadge">0 / 4 done</span>
                </div>
            </div>

            <div class="p-card">
                <div class="p-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
                    Search Preview
                </div>
                <div class="serp-box">
                    <div class="serp-url" id="serpUrl">DonateBazaar.com › blog › your-title</div>
                    <div class="serp-title empty" id="serpTitle">Your title will appear here</div>
                    <div class="serp-desc empty" id="serpDesc">Your meta description will appear here…</div>
                </div>
                <div class="serp-bars">
                    <div class="serp-bar-row"><span class="serp-bar-lbl">Title</span><div class="serp-bar-track"><div class="serp-bar-fill" id="titleBar" style="width:0%;background:var(--border2)"></div></div><span class="serp-bar-num" id="titleBarNum">0/60</span></div>
                    <div class="serp-bar-row"><span class="serp-bar-lbl">Desc</span><div class="serp-bar-track"><div class="serp-bar-fill" id="descBar" style="width:0%;background:var(--border2)"></div></div><span class="serp-bar-num" id="descBarNum">0/160</span></div>
                </div>
            </div>

            <div class="p-card">
                <div class="p-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    Admin Tips
                </div>
                <div class="tip-list">
                    <div class="tip-item">Use <strong>Featured Post</strong> toggle to highlight key articles on the homepage</div>
                    <div class="tip-item"><strong>Linking a campaign</strong> adds a donation box at the bottom — great for impact stories</div>
                    <div class="tip-item">Enable <strong>Newsletter Syndication</strong> to include this post in the next email blast</div>
                    <div class="tip-item">Set a <strong>custom slug</strong> before publishing — it cannot be changed without breaking links</div>
                    <div class="tip-item">Use <strong>OG image</strong> (1200×630) for better social sharing cards on Facebook and LinkedIn</div>
                </div>
            </div>

        </aside>
    </div>
</form>
@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/blogs-create.js')
@endpush
