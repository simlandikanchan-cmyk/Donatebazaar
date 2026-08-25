@extends('layouts.user')

@section('page_title', 'Write a Blog')

@section('content')
<div class="create-hero">
    <div class="create-hero-content">
        <div class="create-hero-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
        </div>
        <div>
            <h2>Write a Blog</h2>
            <p>Share your story, insights, and campaign updates with your supporters</p>
        </div>
    </div>
    <x-button variant="secondary" href="{{ url('/user/dashboard/blogs') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        My Blogs
    </x-button>
</div>

@if($errors->any())
<div class="alert-bar alert-bar-error">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <span>Please fix {{ $errors->count() }} error{{ $errors->count() > 1 ? 's' : '' }} below</span>
    <button type="button" class="alert-close" data-action="alert-close">&times;</button>
</div>
@endif

<form action="{{ route('user.blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
    @csrf

    <div class="editor-layout">

        <div class="form-col">

            <div class="form-card" data-step="1">
                <div class="card-hdr">
                    <span class="card-step">01</span>
                    <div>
                        <div class="card-title">Basic Information</div>
                        <div class="card-sub">Tell readers what your blog is about</div>
                    </div>
                </div>
                <div class="field">
                    <label class="field-label" for="title">
                        Title <span>*</span>
                        <span class="char-inline" id="titleCounter">0/255</span>
                    </label>
                    <input id="title" name="title" type="text" value="{{ old('title') }}"
                        class="field-input {{ $errors->has('title') ? 'is-error' : '' }}"
                        placeholder="e.g. How We Built a School in 30 Days…" maxlength="255" required>
                    @error('title')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="field-grid">
                    <div class="field">
                        <label class="field-label" for="category_id">Category <span>*</span></label>
                        <div class="select-wrap">
                            <select id="category_id" name="category_id" class="field-select">
                                <option value="">Choose a category…</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
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
                        placeholder="A short summary shown on listing pages and in search results…" maxlength="300">{{ old('excerpt') }}</textarea>
                    <p class="field-hint">Keep it under 160 characters for best SEO — this is what shows in Google results</p>
                </div>
            </div>

            <div class="form-card" data-step="2">
                <div class="card-hdr">
                    <span class="card-step">02</span>
                    <div>
                        <div class="card-title">Cover Image</div>
                        <div class="card-sub">Add a striking visual to grab attention</div>
                    </div>
                </div>
                <div class="upload-zone" id="uploadZone">
                    <input type="file" name="cover_image" accept="image/*" id="coverUpload">
                    <div class="upload-placeholder" id="uploadPlaceholder">
                        <div class="upload-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        </div>
                        <div class="upload-text">Click to upload or drag &amp; drop</div>
                        <div class="upload-sub">JPG, PNG, WebP &middot; Max 3MB &middot; Recommended 1200&times;630px</div>
                    </div>
                    <div class="upload-preview-wrap" id="uploadPreviewWrap" style="display:none;">
                        <img id="uploadPreview" class="upload-preview" alt="Cover preview">
                        <button type="button" class="upload-remove" id="uploadRemove" title="Remove image">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                @error('cover_image')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-card" data-step="3">
                <div class="card-hdr">
                    <span class="card-step">03</span>
                    <div>
                        <div class="card-title">Content <span>*</span></div>
                        <div class="card-sub">Write your blog post — make it compelling</div>
                    </div>
                </div>
                <div class="editor-toolbar" id="editorToolbar">
                    <x-button variant="primary" type="button" class="tb-btn" data-cmd="bold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg></x-button>
                    <x-button variant="primary" type="button" class="tb-btn" data-cmd="italic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg></x-button>
                    <x-button variant="primary" type="button" class="tb-btn" data-cmd="underline"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 3v7a6 6 0 006 6 6 6 0 006-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg></x-button>
                    <span class="tb-divider"></span>
                    <x-button variant="primary" type="button" class="tb-btn" data-cmd="heading"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 12h12M6 4v16M18 4v16"/></svg></x-button>
                    <x-button variant="primary" type="button" class="tb-btn" data-cmd="bullet"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></x-button>
                    <x-button variant="primary" type="button" class="tb-btn" data-cmd="link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg></x-button>
                    <span class="tb-divider"></span>
                    <x-button variant="primary" type="button" class="tb-btn" data-cmd="preview"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></x-button>
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

            <div class="form-card" data-step="4">
                <div class="card-hdr">
                    <span class="card-step">04</span>
                    <div>
                        <div class="card-title">SEO Settings</div>
                        <div class="card-sub">Optimise how your blog appears in search</div>
                    </div>
                </div>
                <div class="field">
                    <label class="field-label" for="meta_title">
                        SEO Title
                        <span class="char-inline" id="metaTitleCounter">0</span>
                    </label>
                    <input id="meta_title" name="meta_title" type="text" value="{{ old('meta_title') }}"
                        class="field-input" placeholder="Leave blank to use the blog title" maxlength="70">
                    <p class="field-hint">Recommended: 50&ndash;60 characters &middot; Leave blank to auto-use the blog title</p>
                </div>
                <div class="field">
                    <label class="field-label" for="meta_description">
                        Meta Description
                        <span class="char-inline desc-status" id="metaDescCounter">0 / 160</span>
                    </label>
                    <textarea id="meta_description" name="meta_description" rows="3"
                        class="field-textarea" maxlength="160"
                        placeholder="A brief summary for search engine results…">{{ old('meta_description') }}</textarea>
                    <p class="field-hint">120&ndash;160 characters ideal &middot; Leave blank to auto-generate from excerpt</p>
                </div>
            </div>

            <div class="action-bar">
                <div class="action-bar-left">
                    <div class="action-indicator" id="unsavedIndicator">
                        <span class="unsaved-dot"></span>
                        <span class="unsaved-text">All saved</span>
                    </div>
                    <p class="action-bar-hint">
                        <strong>Draft</strong> saves privately &middot;
                        <strong>Submit</strong> sends for admin review
                    </p>
                </div>
                <div class="action-btns">
                    <x-button variant="secondary" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Save Draft
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Submit for Review
                    </x-button>
                </div>
            </div>

        </div>

        <aside class="right-panel">

            <div class="p-card" data-delay="0">
                <div class="p-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Content Score
                </div>
                <div class="score-ring-wrap">
                    <div class="score-ring">
                        <svg width="72" height="72" viewBox="0 0 72 72">
                            <circle class="score-ring-bg" cx="36" cy="36" r="30"/>
                            <circle class="score-ring-fill" id="scoreRingFill" cx="36" cy="36" r="30"
                                stroke="#ef4444"
                                stroke-dasharray="188.5"
                                stroke-dashoffset="188.5"/>
                        </svg>
                        <div class="score-ring-num" id="scoreNum">0</div>
                    </div>
                    <div>
                        <div class="score-info-title" id="scoreLabel">Not started</div>
                        <div class="score-info-sub" id="scoreSub">Fill in the form to build your score</div>
                    </div>
                </div>
                <div class="q-checks">
                    <div class="q-check" id="qc-title">
                        <div class="q-check-icon wait">
                            <svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg>
                        </div>
                        <span>Title length (40&ndash;70 chars)</span>
                        <span class="q-check-val" id="qc-title-v">0</span>
                    </div>
                    <div class="q-check" id="qc-words">
                        <div class="q-check-icon wait">
                            <svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg>
                        </div>
                        <span>300+ words written</span>
                        <span class="q-check-val" id="qc-words-v">0 words</span>
                    </div>
                    <div class="q-check" id="qc-excerpt">
                        <div class="q-check-icon wait">
                            <svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg>
                        </div>
                        <span>Excerpt provided</span>
                    </div>
                    <div class="q-check" id="qc-image">
                        <div class="q-check-icon wait">
                            <svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg>
                        </div>
                        <span>Cover image uploaded</span>
                    </div>
                    <div class="q-check" id="qc-meta">
                        <div class="q-check-icon wait">
                            <svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg>
                        </div>
                        <span>Meta description</span>
                    </div>
                </div>
            </div>

            <div class="p-card" data-delay="1">
                <div class="p-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Readability
                </div>
                <div class="read-grade-bar">
                    <div class="read-grade-label">
                        <span id="readLabel">No content</span>
                        <small id="readScore">&mdash;</small>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill" id="readBar" style="width:0%;background:var(--red)"></div>
                    </div>
                </div>
                <div class="read-stats">
                    <div class="read-stat">
                        <div class="read-stat-num" id="avgWords">&mdash;</div>
                        <div class="read-stat-lbl">avg words/sent</div>
                    </div>
                    <div class="read-stat">
                        <div class="read-stat-num" id="longSents">&mdash;</div>
                        <div class="read-stat-lbl">long sentences</div>
                    </div>
                    <div class="read-stat">
                        <div class="read-stat-num" id="paraCount">&mdash;</div>
                        <div class="read-stat-lbl">paragraphs</div>
                    </div>
                </div>
            </div>

            <div class="p-card" data-delay="2">
                <div class="p-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
                    Search Preview
                </div>
                <div class="serp-box">
                    <div class="serp-url" id="serpUrl">DonateBazaar.com &rsaquo; blog &rsaquo; your-title</div>
                    <div class="serp-title empty" id="serpTitle">Your title will appear here</div>
                    <div class="serp-desc empty" id="serpDesc">Your meta description will appear here&hellip;</div>
                </div>
                <div class="serp-bars">
                    <div class="serp-bar-row">
                        <span class="serp-bar-lbl">Title</span>
                        <div class="serp-bar-track">
                            <div class="serp-bar-fill" id="titleBar" style="width:0%;background:var(--border2)"></div>
                        </div>
                        <span class="serp-bar-num" id="titleBarNum">0/60</span>
                    </div>
                    <div class="serp-bar-row">
                        <span class="serp-bar-lbl">Desc</span>
                        <div class="serp-bar-track">
                            <div class="serp-bar-fill" id="descBar" style="width:0%;background:var(--border2)"></div>
                        </div>
                        <span class="serp-bar-num" id="descBarNum">0/160</span>
                    </div>
                </div>
            </div>

            <div class="p-card" data-delay="3">
                <div class="p-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Publish Checklist
                </div>
                <div class="checklist">
                    <div class="cl-item fail" id="cl-title">
                        <div class="cl-dot fail">
                            <svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </div>
                        <span>Blog title</span>
                        <span class="cl-val fail" id="cl-title-v">Missing</span>
                    </div>
                    <div class="cl-item fail" id="cl-cat">
                        <div class="cl-dot fail">
                            <svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </div>
                        <span>Category</span>
                        <span class="cl-val fail" id="cl-cat-v">Missing</span>
                    </div>
                    <div class="cl-item fail" id="cl-content">
                        <div class="cl-dot fail">
                            <svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </div>
                        <span>Content written</span>
                        <span class="cl-val fail" id="cl-content-v">0 words</span>
                    </div>
                    <div class="cl-item fail" id="cl-cover">
                        <div class="cl-dot fail">
                            <svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </div>
                        <span>Cover image</span>
                        <span class="cl-val fail" id="cl-cover-v">Not set</span>
                    </div>
                    <div class="cl-item warn" id="cl-excerpt">
                        <div class="cl-dot warn">
                            <svg viewBox="0 0 10 10"><rect x="4.25" y="1.5" width="1.5" height="4" rx=".75" fill="var(--yellow)"/><rect x="4.25" y="7" width="1.5" height="1.5" rx=".75" fill="var(--yellow)"/></svg>
                        </div>
                        <span>Excerpt</span>
                        <span class="cl-val warn" id="cl-excerpt-v">Optional</span>
                    </div>
                    <div class="cl-item warn" id="cl-tags">
                        <div class="cl-dot warn">
                            <svg viewBox="0 0 10 10"><rect x="4.25" y="1.5" width="1.5" height="4" rx=".75" fill="var(--yellow)"/><rect x="4.25" y="7" width="1.5" height="1.5" rx=".75" fill="var(--yellow)"/></svg>
                        </div>
                        <span>Tags</span>
                        <span class="cl-val warn" id="cl-tags-v">Optional</span>
                    </div>
                </div>
                <div class="ready-row">
                    <span class="ready-lbl">Ready to submit?</span>
                    <span class="ready-badge none" id="readyBadge">0 / 4 done</span>
                </div>
            </div>

            <div class="p-card" data-delay="4">
                <div class="p-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    Writing Tips
                </div>
                <div class="tip-list">
                    <div class="tip-item">Aim for <strong>300&ndash;1,200 words</strong> &mdash; ideal length for donation blog engagement</div>
                    <div class="tip-item">Keep sentences <strong>under 20 words</strong> for clarity and easy reading</div>
                    <div class="tip-item">Use <strong>short paragraphs</strong> of 2&ndash;4 sentences to improve scanability</div>
                    <div class="tip-item">End with a <strong>clear call to action</strong> &mdash; tell readers why they should donate</div>
                    <div class="tip-item">Add a <strong>cover image</strong> &mdash; posts with images get 3&times; more engagement</div>
                </div>
            </div>

        </aside>

    </div>

</form>
@endsection

@push('page_scripts')
@vite(['resources/js/user/blogs-create.js'])
@endpush
