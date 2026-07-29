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
    <a href="{{ url('/user/dashboard/blogs') }}" class="btn btn-secondary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        My Blogs
    </a>
</div>

@if($errors->any())
<div class="alert-bar alert-bar-error">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <span>Please fix {{ $errors->count() }} error{{ $errors->count() > 1 ? 's' : '' }} below</span>
    <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
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
                        <div class="upload-sub">JPG, PNG, WebP &middot; Max 3MB</div>
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
                    <button type="button" class="tb-btn" data-cmd="bold" title="Bold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg></button>
                    <button type="button" class="tb-btn" data-cmd="italic" title="Italic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg></button>
                    <button type="button" class="tb-btn" data-cmd="underline" title="Underline"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 3v7a6 6 0 006 6 6 6 0 006-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg></button>
                    <span class="tb-divider"></span>
                    <button type="button" class="tb-btn" data-cmd="heading" title="Heading"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 12h12M6 4v16M18 4v16"/></svg></button>
                    <button type="button" class="tb-btn" data-cmd="bullet" title="Bullet list"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></button>
                    <button type="button" class="tb-btn" data-cmd="link" title="Insert link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg></button>
                    <span class="tb-divider"></span>
                    <button type="button" class="tb-btn" data-cmd="preview" title="Preview"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
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
                    <button type="submit" name="submit_now" value="0" class="btn btn-secondary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Save Draft
                    </button>
                    <button type="submit" name="submit_now" value="1" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Submit for Review
                    </button>
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

@push('page_styles')
<style>
.create-hero {
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    margin-bottom: 28px; padding: 24px 28px;
    background: linear-gradient(135deg, var(--surface) 0%, color-mix(in srgb, var(--accent) 4%, var(--surface)) 100%);
    border: 1px solid var(--border); border-radius: var(--radius);
    box-shadow: var(--shadow);
}
.create-hero-content { display: flex; align-items: center; gap: 16px; }
.create-hero-icon {
    width: 48px; height: 48px; border-radius: 12px;
    background: linear-gradient(135deg, var(--accent) 0%, color-mix(in srgb, var(--accent) 70%, #000) 100%);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(99,102,241,0.25);
}
.create-hero-icon svg { width: 22px; height: 22px; color: #fff; }
.create-hero h2 { font-size: 22px; font-family: 'DM Mono'; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }
.create-hero p  { font-size: 12.5px; color: var(--text3); margin-top: 3px; }

.editor-layout { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }

.form-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
    padding: 24px; box-shadow: var(--shadow); margin-bottom: 16px;
    animation: fadeUp 0.4s ease both;
    transition: box-shadow 0.2s, border-color 0.2s;
}
.form-card:focus-within { border-color: var(--accent); box-shadow: 0 0 0 1px var(--accent-glow), var(--shadow); }
.form-card:nth-child(1) { animation-delay: 0.02s; }
.form-card:nth-child(2) { animation-delay: 0.06s; }
.form-card:nth-child(3) { animation-delay: 0.10s; }
.form-card:nth-child(4) { animation-delay: 0.14s; }

.card-hdr { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
.card-step {
    width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
    background: var(--surface2); border: 1px solid var(--border2);
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700; font-family: var(--font-mono); color: var(--text3);
}
.card-title { font-size: 13px; font-weight: 600; color: var(--text); }
.card-sub  { font-size: 11.5px; color: var(--text3); margin-top: 2px; }

.field { margin-bottom: 18px; }
.field:last-child { margin-bottom: 0; }
.field-label {
    display: flex; align-items: center; justify-content: space-between;
    font-size: 12.5px; font-weight: 600; color: var(--text2); margin-bottom: 7px;
}
.field-label span[style*="color:var(--red)"] { margin-left: 2px; }

.char-inline { font-size: 10.5px; font-family: var(--font-mono); color: var(--text3); font-weight: 400; transition: color 0.2s; }
.char-inline.warn { color: var(--yellow); }
.char-inline.over { color: var(--red); }
.char-inline.ok   { color: var(--green); }

.field-input, .field-select, .field-textarea {
    width: 100%; background: var(--surface2); border: 1px solid var(--border2);
    border-radius: var(--radius-sm); padding: 10px 13px; font-size: 13px; color: var(--text);
    font-family: var(--font); outline: none;
    transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
}
.field-input::placeholder, .field-textarea::placeholder { color: var(--text3); }
.field-input:focus, .field-select:focus, .field-textarea:focus {
    border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); background: var(--surface);
}
.field-input.is-error, .field-textarea.is-error { border-color: var(--red); }
.field-select { cursor: pointer; }
.select-wrap { position: relative; }
.select-wrap::after {
    content: ''; position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    width: 10px; height: 10px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-size: contain; background-repeat: no-repeat; pointer-events: none;
}
.select-wrap select { padding-right: 36px; appearance: none; }
.field-textarea { resize: vertical; line-height: 1.65; }
.field-error { font-size: 11.5px; color: var(--red); margin-top: 5px; }
.field-hint  { font-size: 11px; color: var(--text3); margin-top: 5px; }

.field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

.tag-select { display: flex; flex-wrap: wrap; gap: 6px; }
.tag-chip {
    display: inline-flex; padding: 5px 12px; border-radius: 100px;
    background: var(--surface2); border: 1px solid var(--border2);
    font-size: 11.5px; color: var(--text2); cursor: pointer;
    transition: all 0.15s; user-select: none;
}
.tag-chip:hover { border-color: var(--accent); color: var(--text); }
.tag-chip:has(input:checked) {
    background: var(--accent-glow); border-color: var(--accent); color: var(--accent); font-weight: 600;
}

.upload-zone {
    position: relative; border-radius: var(--radius-sm); overflow: hidden;
    background: var(--surface2); border: 2px dashed var(--border2);
    transition: border-color var(--transition), background var(--transition);
    cursor: pointer; min-height: 160px;
}
.upload-zone:hover { border-color: var(--accent); background: color-mix(in srgb, var(--accent) 4%, var(--surface2)); }
.upload-zone:has(.upload-preview-wrap[style*="display:block"]) { border-color: var(--green); border-style: solid; }
.upload-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; z-index: 2; }

.upload-placeholder { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 36px 24px; }
.upload-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: color-mix(in srgb, var(--accent) 10%, transparent);
    display: flex; align-items: center; justify-content: center; margin-bottom: 12px;
}
.upload-icon svg { width: 20px; height: 20px; color: var(--accent); }
.upload-text { font-size: 13px; font-weight: 600; color: var(--text2); }
.upload-sub  { font-size: 11.5px; color: var(--text3); margin-top: 4px; }

.upload-preview-wrap { position: relative; display: none; }
.upload-preview { width: 100%; max-height: 220px; object-fit: cover; display: block; }
.upload-remove {
    position: absolute; top: 8px; right: 8px;
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(0,0,0,0.55); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.15s;
}
.upload-remove:hover { background: rgba(239,68,68,0.8); }
.upload-remove svg { width: 14px; height: 14px; color: #fff; }

.editor-toolbar {
    display: flex; align-items: center; gap: 2px;
    padding: 6px 8px; margin-bottom: 0;
    background: var(--surface2); border: 1px solid var(--border2); border-bottom: none;
    border-radius: var(--radius-sm) var(--radius-sm) 0 0;
    flex-wrap: wrap;
}
.tb-btn {
    width: 30px; height: 30px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    border: none; background: transparent; cursor: pointer;
    color: var(--text3); transition: all 0.12s;
}
.tb-btn:hover { background: var(--surface); color: var(--text); }
.tb-btn:active { transform: scale(0.92); }
.tb-btn svg { width: 15px; height: 15px; }
.tb-divider { width: 1px; height: 18px; background: var(--border); margin: 0 4px; }

.editor-wrap { border: 1px solid var(--border2); border-radius: 0 0 var(--radius-sm) var(--radius-sm); }
.editor-ta {
    border: none !important; border-radius: 0 !important;
    padding: 14px 16px !important; font-size: 13.5px !important; line-height: 1.7 !important;
    resize: vertical; min-height: 280px; font-family: inherit;
}
.editor-ta:focus { box-shadow: none !important; }
.editor-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 8px 14px; border-top: 1px solid var(--border);
    background: var(--surface2);
}
.read-time-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 9px; border-radius: 100px;
    background: color-mix(in srgb, var(--accent) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--accent) 20%, transparent);
    font-size: 11px; font-weight: 600; color: var(--accent); font-family: var(--font-mono);
}
.read-time-badge svg { width: 11px; height: 11px; }
.char-count { font-size: 11.5px; font-family: var(--font-mono); color: var(--text3); transition: color var(--transition); }
.char-count.warn { color: var(--red); }
.char-count.ok   { color: var(--green); }

.action-bar {
    position: sticky; bottom: 12px;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 14px 20px;
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08); margin-top: 16px;
    animation: fadeUp 0.4s 0.15s ease both; z-index: 50; flex-wrap: wrap;
}
.action-bar-left { display: flex; align-items: center; gap: 14px; }
.action-indicator { display: flex; align-items: center; gap: 6px; }
.unsaved-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green); transition: background 0.3s; }
.unsaved-dot.dirty { background: var(--yellow); }
.unsaved-text { font-size: 11px; color: var(--text3); font-family: var(--font-mono); }
.action-bar-hint { font-size: 12px; color: var(--text3); }
.action-bar-hint strong { color: var(--text2); font-weight: 600; }
.action-btns { display: flex; gap: 8px; }

.alert-bar {
    padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13px;
    margin-bottom: 16px; display: flex; align-items: center; gap: 10px;
    border: 1px solid transparent; animation: slideDown 0.3s ease;
}
.alert-bar svg { width: 15px; height: 15px; flex-shrink: 0; }
.alert-bar-error { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.2); color: #b91c1c; }
.alert-close {
    margin-left: auto; background: none; border: none; cursor: pointer;
    font-size: 18px; color: inherit; opacity: 0.5; padding: 0 2px;
}
.alert-close:hover { opacity: 1; }

.right-panel { position: sticky; top: 80px; display: flex; flex-direction: column; gap: 14px; }
.p-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
    padding: 18px; box-shadow: var(--shadow); overflow: hidden;
    animation: fadeUp 0.4s ease both;
}
.p-card[data-delay="0"] { animation-delay: 0.08s; }
.p-card[data-delay="1"] { animation-delay: 0.16s; }
.p-card[data-delay="2"] { animation-delay: 0.24s; }
.p-card[data-delay="3"] { animation-delay: 0.32s; }
.p-card[data-delay="4"] { animation-delay: 0.40s; }

.p-card-title {
    font-size: 10px; font-weight: 700; color: var(--text3);
    text-transform: uppercase; letter-spacing: 0.12em;
    font-family: var(--font-mono); margin-bottom: 14px;
    display: flex; align-items: center; gap: 6px;
}
.p-card-title svg { width: 12px; height: 12px; opacity: 0.6; }

.score-ring-wrap { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
.score-ring { position: relative; width: 72px; height: 72px; flex-shrink: 0; }
.score-ring svg { transform: rotate(-90deg); }
.score-ring-bg  { fill: none; stroke: var(--surface2); stroke-width: 7; }
.score-ring-fill { fill: none; stroke-width: 7; stroke-linecap: round; transition: stroke-dashoffset 0.7s cubic-bezier(.4,0,.2,1), stroke 0.4s ease; }
.score-ring-num { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; color: var(--text); font-family: var(--font-mono); }
.score-info-title { font-size: 14px; font-weight: 600; color: var(--text); }
.score-info-sub   { font-size: 11.5px; color: var(--text3); margin-top: 2px; }

.q-checks { display: flex; flex-direction: column; gap: 8px; }
.q-check  { display: flex; align-items: center; gap: 9px; font-size: 12px; color: var(--text2); }
.q-check-icon { width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background var(--transition); }
.q-check-icon.done { background: rgba(16,185,129,0.15); }
.q-check-icon.fail { background: rgba(239,68,68,0.10); }
.q-check-icon.wait { background: var(--surface2); border: 1px solid var(--border2); }
.q-check-icon svg { width: 9px; height: 9px; }
.q-check-val { margin-left: auto; font-size: 10.5px; font-family: var(--font-mono); color: var(--text3); }

.serp-box { background: var(--surface2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 12px 14px; }
.serp-url   { font-size: 10.5px; color: var(--green); margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.serp-title { font-size: 13px; font-weight: 600; color: #4f8ef7; line-height: 1.35; margin-bottom: 4px; }
.serp-title.empty { color: var(--text3); font-style: italic; font-weight: 400; }
.serp-desc  { font-size: 11.5px; color: var(--text2); line-height: 1.5; }
.serp-desc.empty { color: var(--text3); font-style: italic; }
.serp-bars  { display: flex; flex-direction: column; gap: 6px; margin-top: 12px; }
.serp-bar-row { display: flex; align-items: center; gap: 8px; }
.serp-bar-lbl { font-size: 10.5px; color: var(--text3); width: 32px; flex-shrink: 0; }
.serp-bar-track { flex: 1; height: 4px; background: var(--surface2); border-radius: 100px; overflow: hidden; border: 1px solid var(--border); }
.serp-bar-fill  { height: 100%; border-radius: 100px; transition: width 0.4s ease, background 0.3s; }
.serp-bar-num   { font-size: 10px; font-family: var(--font-mono); color: var(--text3); width: 36px; text-align: right; flex-shrink: 0; }

.read-stats { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-top: 12px; }
.read-stat  { background: var(--surface2); border-radius: var(--radius-sm); padding: 9px 10px; text-align: center; border: 1px solid var(--border); }
.read-stat-num { font-size: 16px; font-weight: 700; color: var(--text); font-family: var(--font-mono); }
.read-stat-lbl { font-size: 9.5px; color: var(--text3); margin-top: 2px; }

.read-grade-bar { margin-bottom: 10px; }
.read-grade-label { display: flex; justify-content: space-between; margin-bottom: 5px; }
.read-grade-label span { font-size: 12px; font-weight: 600; color: var(--text); }
.read-grade-label small { font-size: 11px; color: var(--text3); }
.bar-track  { height: 6px; background: var(--surface2); border-radius: 100px; overflow: hidden; border: 1px solid var(--border); }
.bar-fill   { height: 100%; border-radius: 100px; transition: width 0.6s cubic-bezier(.4,0,.2,1), background 0.3s; }

.checklist { display: flex; flex-direction: column; gap: 6px; }
.cl-item {
    display: flex; align-items: center; gap: 9px;
    padding: 8px 10px; border-radius: var(--radius-sm);
    background: var(--surface2); border: 1px solid var(--border);
    font-size: 12px; color: var(--text2);
    transition: background var(--transition), border-color var(--transition);
}
.cl-item.done { background: rgba(16,185,129,0.06); border-color: rgba(16,185,129,0.2); }
.cl-item.warn { background: rgba(245,158,11,0.06); border-color: rgba(245,158,11,0.18); }
.cl-dot { width: 18px; height: 18px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: background var(--transition); }
.cl-dot.done { background: rgba(16,185,129,0.2); }
.cl-dot.warn { background: rgba(245,158,11,0.18); }
.cl-dot.fail { background: rgba(239,68,68,0.12); border: 1px dashed rgba(239,68,68,0.3); }
.cl-dot svg { width: 9px; height: 9px; }
.cl-val { margin-left: auto; font-size: 10.5px; font-family: var(--font-mono); }
.cl-val.done { color: var(--green); }
.cl-val.warn { color: var(--yellow); }
.cl-val.fail { color: var(--red); }
.ready-row { display: flex; align-items: center; justify-content: space-between; margin-top: 12px; padding-top: 10px; border-top: 1px solid var(--border); }
.ready-lbl { font-size: 11.5px; color: var(--text3); }
.ready-badge { font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px; font-family: var(--font-mono); }
.ready-badge.none  { background: rgba(239,68,68,0.12); color: var(--red); }
.ready-badge.part  { background: rgba(245,158,11,0.15); color: var(--yellow); }
.ready-badge.full  { background: rgba(16,185,129,0.15); color: var(--green); }

.tip-list { display: flex; flex-direction: column; gap: 8px; }
.tip-item { display: flex; align-items: flex-start; gap: 8px; font-size: 12px; color: var(--text2); line-height: 1.5; }
.tip-item::before { content: ''; width: 3px; height: 3px; border-radius: 50%; background: var(--accent); margin-top: 6px; flex-shrink: 0; }
.tip-item strong { color: var(--text); font-weight: 600; }

@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

@media (max-width: 1100px) {
    .editor-layout { grid-template-columns: 1fr; }
    .right-panel { position: static; display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
}
@media (max-width: 860px) {
    .body { padding: 16px 16px 80px; }
    .create-hero { flex-direction: column; align-items: stretch; text-align: center; padding: 20px; }
    .create-hero-content { flex-direction: column; }
    .right-panel { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
    .field-grid { grid-template-columns: 1fr; }
    .action-bar { flex-direction: column; align-items: stretch; text-align: center; }
    .action-bar-left { flex-direction: column; gap: 6px; }
    .action-btns { justify-content: center; }
}
@media (max-width: 480px) {
    .form-card { padding: 16px; }
    .action-bar { padding: 12px 14px; }
    .action-btns { flex-direction: column; }
    .action-btns .btn { width: 100%; justify-content: center; }
    .create-hero h2 { font-size: 18px; }
    .upload-placeholder { padding: 24px 16px; }
    .editor-toolbar { overflow-x: auto; flex-wrap: nowrap; }
}
</style>
@endpush

@push('page_scripts')
<script>
(function(){
'use strict';

function $(id){ return document.getElementById(id); }

function wordCount(text){
    return text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
}
function sentences(text){
    return text.split(/[.!?]+/).filter(function(s){ return s.trim().split(/\s+/).length > 2; });
}
function avgWPS(text){
    var s = sentences(text);
    if(!s.length) return 0;
    return Math.round(s.reduce(function(a,b){ return a + b.trim().split(/\s+/).length; },0) / s.length);
}
function longSents(text){
    return sentences(text).filter(function(s){ return s.trim().split(/\s+/).length > 20; }).length;
}
function paraCount(text){
    return text.split(/\n\s*\n/).filter(function(p){ return p.trim().length > 0; }).length || (text.trim().length > 0 ? 1 : 0);
}
function readScore(text){
    if(wordCount(text) < 10) return 0;
    var avg = avgWPS(text);
    if(avg <= 12) return 95; if(avg <= 15) return 82; if(avg <= 20) return 65;
    if(avg <= 25) return 45; return 25;
}
function slugify(t){
    return t.toLowerCase().replace(/[^a-z0-9\s]/g,'').trim().replace(/\s+/g,'-').slice(0,45) || 'your-title';
}
function barColor(pct){
    if(pct >= 70) return 'var(--green)'; if(pct >= 40) return 'var(--yellow)'; return 'var(--red)';
}
function titleBarColor(len){
    if(len >= 40 && len <= 60) return 'var(--green)';
    if(len > 60 && len <= 70) return 'var(--yellow)';
    if(len > 70) return 'var(--red)';
    return 'var(--border2)';
}
function descBarColor(len){
    if(len >= 120 && len <= 160) return 'var(--green)';
    if(len > 160) return 'var(--red)';
    if(len >= 50) return 'var(--yellow)';
    return 'var(--border2)';
}

function setQCheck(id, state){
    var el = $(id);
    var icon = el.querySelector('.q-check-icon');
    icon.className = 'q-check-icon ' + state;
    if(state === 'done'){
        icon.innerHTML = '<svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4.5" fill="rgba(16,185,129,0.2)"/><path d="M2.5 5l1.5 1.5 3-3" stroke="var(--green)" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    } else {
        icon.innerHTML = '<svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg>';
    }
}

function setCLItem(id, state, valText){
    var el = $(id);
    el.className = 'cl-item ' + state;
    var dot = el.querySelector('.cl-dot');
    dot.className = 'cl-dot ' + state;
    if(state === 'done'){
        dot.innerHTML = '<svg viewBox="0 0 10 10" fill="none"><path d="M2 5l2 2 4-4" stroke="var(--green)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    } else if(state === 'warn'){
        dot.innerHTML = '<svg viewBox="0 0 10 10"><rect x="4.25" y="1.5" width="1.5" height="4" rx=".75" fill="var(--yellow)"/><rect x="4.25" y="7" width="1.5" height="1.5" rx=".75" fill="var(--yellow)"/></svg>';
    } else {
        dot.innerHTML = '<svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg>';
    }
    var vEl = el.querySelector('.cl-val');
    vEl.className = 'cl-val ' + state;
    vEl.textContent = valText;
}

function update(){
    var titleVal   = ($('title') || {value:''}).value;
    var contentVal = ($('blogContent') || {value:''}).value;
    var excerptVal = ($('excerpt') || {value:''}).value;
    var metaTitleVal = ($('meta_title') || {value:''}).value;
    var metaDescVal  = ($('meta_description') || {value:''}).value;
    var catEl      = $('category_id');
    var hasCat     = catEl && catEl.value && catEl.value !== '';
    var tagEl      = $('tag_ids');
    var hasTags    = tagEl && [].some.call(tagEl.querySelectorAll('input[type=checkbox]'), function(o){ return o.checked; });
    var imgEl      = $('coverUpload');
    var hasImg     = imgEl && imgEl.files && imgEl.files.length > 0;

    var wc       = wordCount(contentVal);
    var tLen     = titleVal.length;
    var hasWords = wc >= 300;
    var hasTitle = tLen >= 40 && tLen <= 70;
    var hasExcerpt = excerptVal.trim().length > 0;
    var hasMeta    = metaDescVal.trim().length > 0;

    var score = 0;
    if(hasTitle) score += 20; else if(tLen > 0) score += Math.round(tLen/70*20);
    if(hasWords) score += 30; else score += Math.round(Math.min(wc/300,1)*30);
    if(hasExcerpt) score += 15;
    if(hasImg)     score += 20;
    if(hasMeta)    score += 15;
    score = Math.min(100, Math.round(score));

    var circ   = 188.5;
    var offset = circ - (circ * score / 100);
    var ringEl = $('scoreRingFill');
    ringEl.style.strokeDashoffset = offset.toFixed(1);
    ringEl.style.stroke = barColor(score);
    $('scoreNum').textContent = score;

    var lbl, sub;
    if(score >= 85){ lbl='Excellent'; sub='Great blog — ready to publish!'; }
    else if(score >= 65){ lbl='Good'; sub='Almost there, a few tweaks needed'; }
    else if(score >= 40){ lbl='Fair'; sub='Keep going, more content needed'; }
    else if(score > 0){ lbl='Weak'; sub='Fill in more details to improve'; }
    else { lbl='Not started'; sub='Fill in the form to build your score'; }
    $('scoreLabel').textContent = lbl;
    $('scoreSub').textContent   = sub;

    setQCheck('qc-title',   hasTitle ? 'done' : 'wait');
    $('qc-title-v').textContent = tLen + ' chars';
    setQCheck('qc-words',   hasWords ? 'done' : 'wait');
    $('qc-words-v').textContent = wc + ' words';
    setQCheck('qc-excerpt', hasExcerpt ? 'done' : 'wait');
    setQCheck('qc-image',   hasImg ? 'done' : 'wait');
    setQCheck('qc-meta',    hasMeta ? 'done' : 'wait');

    var cLen = contentVal.length;
    $('charCount').textContent = cLen.toLocaleString() + ' chars';
    $('charCount').className = 'char-count' + (cLen > 0 && cLen < 50 ? ' warn' : cLen >= 50 ? ' ok' : '');

    var mins = Math.max(1, Math.ceil(wc / 200));
    $('readTimeText').textContent = mins + ' min';
    $('readTimeInput').value = mins;

    var rs = readScore(contentVal);
    $('readBar').style.width = rs + '%';
    $('readBar').style.background = barColor(rs);
    var rl;
    if(rs >= 80) rl='Easy to read'; else if(rs >= 60) rl='Fairly readable'; else if(rs >= 40) rl='Moderate'; else if(rs > 0) rl='Difficult'; else rl='No content';
    $('readLabel').textContent = rl;
    $('readScore').textContent = rs > 0 ? rs + '/100' : '\u2014';
    $('avgWords').textContent  = contentVal.trim() ? avgWPS(contentVal) + 'w' : '\u2014';
    $('longSents').textContent = contentVal.trim() ? longSents(contentVal) : '\u2014';
    $('paraCount').textContent = contentVal.trim() ? paraCount(contentVal) : '\u2014';

    var dispTitle = metaTitleVal || titleVal;
    var dispDesc  = metaDescVal  || excerptVal;
    var slug      = slugify(titleVal);
    $('serpUrl').textContent = 'DonateBazaar.com › blog › ' + slug;

    var serpT = $('serpTitle');
    if(dispTitle){
        serpT.textContent = dispTitle.length > 65 ? dispTitle.slice(0,65)+'…' : dispTitle;
        serpT.className = 'serp-title';
    } else {
        serpT.textContent = 'Your title will appear here';
        serpT.className = 'serp-title empty';
    }
    var serpD = $('serpDesc');
    if(dispDesc){
        serpD.textContent = dispDesc.length > 155 ? dispDesc.slice(0,155)+'…' : dispDesc;
        serpD.className = 'serp-desc';
    } else {
        serpD.textContent = 'Your meta description will appear here\u2026';
        serpD.className = 'serp-desc empty';
    }

    var tBarLen = (metaTitleVal || titleVal).length;
    var dBarLen = metaDescVal.length;
    $('titleBar').style.width = Math.min(100, Math.round(tBarLen/60*100)) + '%';
    $('titleBar').style.background = titleBarColor(tBarLen);
    $('titleBarNum').textContent = tBarLen + '/60';
    $('descBar').style.width = Math.min(100, Math.round(dBarLen/160*100)) + '%';
    $('descBar').style.background = descBarColor(dBarLen);
    $('descBarNum').textContent = dBarLen + '/160';

    var reqDone = 0;
    if(tLen > 0){ setCLItem('cl-title','done', tLen+' chars'); reqDone++; }
    else setCLItem('cl-title','fail','Missing');

    if(hasCat){ setCLItem('cl-cat','done','Selected'); reqDone++; }
    else setCLItem('cl-cat','fail','Missing');

    if(wc >= 100){ setCLItem('cl-content','done', wc+' words'); reqDone++; }
    else if(wc > 0) setCLItem('cl-content','warn', wc+' words');
    else setCLItem('cl-content','fail','0 words');

    if(hasImg){ setCLItem('cl-cover','done','Uploaded'); reqDone++; }
    else setCLItem('cl-cover','fail','Not set');

    if(hasExcerpt) setCLItem('cl-excerpt','done','Added');
    else setCLItem('cl-excerpt','warn','Optional');

    if(hasTags) setCLItem('cl-tags','done','Tagged');
    else setCLItem('cl-tags','warn','Optional');

    var rb = $('readyBadge');
    rb.textContent = reqDone + ' / 4 done';
    rb.className   = 'ready-badge ' + (reqDone >= 4 ? 'full' : reqDone >= 2 ? 'part' : 'none');

    var dl = metaDescVal.length;
    var dc = $('metaDescCounter');
    dc.textContent = dl + ' / 160';
    dc.className = 'char-inline desc-status' + (dl > 160 ? ' over' : dl >= 120 ? ' ok' : '');

    var tc = $('titleCounter');
    tc.textContent = tLen + '/255';
    tc.className = 'char-inline' + (tLen > 255 ? ' over' : tLen > 200 ? ' warn' : '');

    var ec = $('excerptCounter');
    ec.textContent = excerptVal.length;
    ec.className = 'char-inline' + (excerptVal.length > 160 ? ' warn' : '');

    var mtc = $('metaTitleCounter');
    mtc.textContent = metaTitleVal.length;
    mtc.className = 'char-inline' + (metaTitleVal.length > 60 ? ' over' : metaTitleVal.length >= 50 ? ' ok' : '');

    var unsavedDot = document.querySelector('.unsaved-dot');
    if(unsavedDot) {
        var hasChanges = tLen > 0 || contentVal.length > 0 || excerptVal.length > 0 || metaTitleVal.length > 0 || metaDescVal.length > 0 || hasCat || hasTags || hasImg;
        unsavedDot.className = 'unsaved-dot' + (hasChanges ? ' dirty' : '');
        unsavedDot.parentElement.querySelector('.unsaved-text').textContent = hasChanges ? 'Unsaved changes' : 'All saved';
    }
}

['title','blogContent','excerpt','meta_title','meta_description'].forEach(function(id){
    var el = document.getElementById(id);
    if(el) el.addEventListener('input', update);
});
var catEl = document.getElementById('category_id');
if(catEl) catEl.addEventListener('change', update);

document.querySelectorAll('#tagContainer input[type=checkbox]').forEach(function(cb){
    cb.addEventListener('change', update);
});

var upload  = document.getElementById('coverUpload');
var zone    = document.getElementById('uploadZone');
var preview = document.getElementById('uploadPreview');
var previewWrap = document.getElementById('uploadPreviewWrap');
var placeholder = document.getElementById('uploadPlaceholder');
var removeBtn   = document.getElementById('uploadRemove');

if(upload){
    upload.addEventListener('change', function(){
        var file = this.files[0];
        if(!file) return;
        var reader = new FileReader();
        reader.onload = function(e){
            preview.src = e.target.result;
            previewWrap.style.display = 'block';
            placeholder.style.display = 'none';
            zone.classList.add('has-file');
            update();
        };
        reader.readAsDataURL(file);
    });

    if(removeBtn){
        removeBtn.addEventListener('click', function(e){
            e.stopPropagation();
            upload.value = '';
            previewWrap.style.display = 'none';
            placeholder.style.display = 'flex';
            zone.classList.remove('has-file');
            update();
        });
    }

    zone.addEventListener('dragover', function(e){ e.preventDefault(); zone.style.borderColor='var(--accent)'; });
    zone.addEventListener('dragleave', function(){ zone.style.borderColor=''; });
    zone.addEventListener('drop', function(e){
        e.preventDefault(); zone.style.borderColor='';
        var file = e.dataTransfer.files[0];
        if(file && file.type.startsWith('image/')){
            var dt = new DataTransfer(); dt.items.add(file); upload.files = dt.files;
            upload.dispatchEvent(new Event('change'));
        }
    });
}

// Editor toolbar
var toolbar = document.getElementById('editorToolbar');
var textarea = document.getElementById('blogContent');
if(toolbar && textarea){
    toolbar.addEventListener('click', function(e){
        var btn = e.target.closest('.tb-btn');
        if(!btn) return;
        var cmd = btn.dataset.cmd;
        if(cmd === 'preview') return;
        e.preventDefault();
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var text = textarea.value;
        var selected = text.substring(start, end);
        var before = text.substring(0, start);
        var after = text.substring(end);
        var wrap;
        switch(cmd){
            case 'bold': wrap = ['**', '**']; break;
            case 'italic': wrap = ['*', '*']; break;
            case 'underline': wrap = ['<u>', '</u>']; break;
            case 'heading': wrap = ['\n## ', '\n']; selected = selected || 'Heading'; break;
            case 'bullet': wrap = ['\n- ', '']; selected = selected || 'List item'; break;
            case 'link':
                var url = prompt('Enter URL:', 'https://');
                if(!url) return;
                wrap = ['[', ']('+url+')']; selected = selected || 'link text';
                break;
        }
        if(wrap){
            var insertion = (cmd === 'heading' || cmd === 'bullet') && start === 0 ? wrap[0].trim() : wrap[0] + selected + wrap[1];
            textarea.value = before + insertion + after;
            var pos = start + insertion.length;
            textarea.setSelectionRange(pos, pos);
            textarea.focus();
            update();
        }
    });
}

update();

})();
</script>
@endpush
