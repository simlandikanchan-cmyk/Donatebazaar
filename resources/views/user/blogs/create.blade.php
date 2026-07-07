@extends('layouts.user')

@section('page_title', 'Write a Blog')

@section('content')
<div class="page-hdr">
    <div class="page-hdr-left">
        <h2>Create Your Blog First</h2>
        <p>Fill in the details below and save as draft or submit for review</p>
    </div>
    <a href="{{ url('/user/dashboard/blogs') }}" class="btn btn-secondary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        My Blogs
    </a>
</div>

@if($errors->any())
<div class="alert alert-error">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <div>
        <strong>Please fix the following errors:</strong>
        <ul style="margin-top:4px;padding-left:16px;">
            @foreach($errors->all() as $error)
                <li style="font-size:12px;margin-top:2px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('user.blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
    @csrf

    <div class="editor-layout">

        <div class="form-col">

            <div class="form-card">
                <div class="card-title">Basic Information</div>
                <div class="field">
                    <label class="field-label" for="title">Title <span>*</span></label>
                    <input id="title" name="title" type="text" value="{{ old('title') }}"
                        class="field-input {{ $errors->has('title') ? 'is-error' : '' }}"
                        placeholder="Enter a compelling title…" required>
                    @error('title')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="field-grid">
                    <div class="field">
                        <label class="field-label" for="category_id">Category <span>*</span></label>
                        <select id="category_id" name="category_id" class="field-select">
                            <option value="">Select category…</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label class="field-label" for="tag_ids">Tags</label>
                        <select id="tag_ids" name="tag_ids[]" multiple class="field-select">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tag_ids', [])))>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <p class="field-hint">Hold Ctrl / Cmd to select multiple</p>
                    </div>
                </div>
                <div class="field">
                    <label class="field-label" for="excerpt">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" rows="3" class="field-textarea"
                        placeholder="Short description shown on listing pages…">{{ old('excerpt') }}</textarea>
                    <p class="field-hint">Keep it under 160 characters for best SEO results</p>
                </div>
            </div>

            <div class="form-card">
                <div class="card-title">Cover Image</div>
                <div class="upload-zone" id="uploadZone">
                    <input type="file" name="cover_image" accept="image/*" id="coverUpload">
                    <div class="upload-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    </div>
                    <div class="upload-text" id="uploadText">Click to upload or drag an image here</div>
                    <div class="upload-sub">JPG, PNG, WebP · Max 3MB</div>
                    <img id="uploadPreview" class="upload-preview" alt="Preview">
                </div>
            </div>

            <div class="form-card">
                <div class="content-header">
                    <div class="card-title" style="margin-bottom:0;padding-bottom:0;border-bottom:none;">
                        Content <span style="color:var(--red);margin-left:2px;">*</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span class="read-time-badge" id="readTimeBadge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span id="readTimeText">0 min</span>
                        </span>
                        <span class="char-count" id="charCount">0 chars</span>
                    </div>
                </div>
                <div style="margin-top:14px;border-top:1px solid var(--border);padding-top:14px;">
                    <textarea id="blogContent" name="content" rows="18"
                        class="field-textarea {{ $errors->has('content') ? 'is-error' : '' }}"
                        placeholder="Write your blog content here…" required>{{ old('content') }}</textarea>
                    @error('content')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <input type="hidden" name="read_time_minutes" id="readTimeInput" value="{{ old('read_time_minutes', 0) }}">
            </div>

            <div class="form-card">
                <div class="card-title">SEO Settings</div>
                <div class="field">
                    <label class="field-label" for="meta_title">SEO Title</label>
                    <input id="meta_title" name="meta_title" type="text" value="{{ old('meta_title') }}"
                        class="field-input" placeholder="Leave blank to use the blog title">
                    <p class="field-hint">Recommended: 50–60 characters</p>
                </div>
                <div class="field">
                    <label class="field-label" for="meta_description">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="3"
                        class="field-textarea" maxlength="160"
                        placeholder="Brief summary for search engines…">{{ old('meta_description') }}</textarea>
                    <div class="meta-desc-footer">
                        <p class="field-hint" style="margin-top:0;">Leave blank to auto-generate from excerpt</p>
                        <span class="desc-count" id="descCount">0 / 160</span>
                    </div>
                </div>
            </div>

            <div class="action-bar">
                <p class="action-bar-hint">
                    <strong>Draft</strong> saves without review ·
                    <strong>Submit</strong> sends for admin approval
                </p>
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

            <div class="p-card">
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
                        <span>Title length (40–70 chars)</span>
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

            <div class="p-card">
                <div class="p-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Readability
                </div>
                <div class="read-grade-bar">
                    <div class="read-grade-label">
                        <span id="readLabel">No content</span>
                        <small id="readScore">—</small>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill" id="readBar" style="width:0%;background:var(--red)"></div>
                    </div>
                </div>
                <div class="read-stats">
                    <div class="read-stat">
                        <div class="read-stat-num" id="avgWords">—</div>
                        <div class="read-stat-lbl">avg words/sent</div>
                    </div>
                    <div class="read-stat">
                        <div class="read-stat-num" id="longSents">—</div>
                        <div class="read-stat-lbl">long sentences</div>
                    </div>
                    <div class="read-stat">
                        <div class="read-stat-num" id="paraCount">—</div>
                        <div class="read-stat-lbl">paragraphs</div>
                    </div>
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

            <div class="p-card">
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

            <div class="p-card">
                <div class="p-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    Writing Tips
                </div>
                <div class="tip-list">
                    <div class="tip-item">Aim for <strong>300–1,200 words</strong> — ideal length for donation blog engagement</div>
                    <div class="tip-item">Keep sentences <strong>under 20 words</strong> for clarity and easy reading</div>
                    <div class="tip-item">Use <strong>short paragraphs</strong> of 2–4 sentences to improve scanability</div>
                    <div class="tip-item">End with a <strong>clear call to action</strong> — tell readers why they should donate</div>
                    <div class="tip-item">Add a <strong>cover image</strong> — posts with images get 3× more engagement</div>
                </div>
            </div>

        </aside>

    </div>

</form>
@endsection

@push('page_styles')
<style>
.page-hdr { margin-bottom: 24px; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.page-hdr-left h2 { font-size: 22px; font-family: 'DM Mono'; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }
.page-hdr-left p  { font-size: 12.5px; color: var(--text3); margin-top: 3px; }
.editor-layout { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }
.form-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); margin-bottom: 16px; animation: fadeUp 0.35s ease both; }
.form-card:nth-child(1) { animation-delay: 0.05s; }
.form-card:nth-child(2) { animation-delay: 0.10s; }
.form-card:nth-child(3) { animation-delay: 0.15s; }
.card-title { font-size: 12px; font-weight: 600; color: var(--text3); text-transform: uppercase; letter-spacing: 0.09em; font-family: var(--font-mono); margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid var(--border); }
.field { margin-bottom: 18px; }
.field:last-child { margin-bottom: 0; }
.field-label { display: block; font-size: 12.5px; font-weight: 600; color: var(--text2); margin-bottom: 7px; }
.field-label span { color: var(--red); margin-left: 2px; }
.field-input, .field-select, .field-textarea { width: 100%; background: var(--surface2); border: 1px solid var(--border2); border-radius: var(--radius-sm); padding: 10px 13px; font-size: 13px; color: var(--text); font-family: var(--font); outline: none; transition: border-color var(--transition), box-shadow var(--transition), background var(--transition); }
.field-input::placeholder, .field-textarea::placeholder { color: var(--text3); }
.field-input:focus, .field-select:focus, .field-textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); background: var(--surface); }
.field-input.is-error, .field-textarea.is-error { border-color: var(--red); }
.field-select { appearance: none; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px; }
.field-textarea { resize: vertical; line-height: 1.6; }
.field-error { font-size: 11.5px; color: var(--red); margin-top: 5px; font-family: var(--font-mono); }
.field-hint  { font-size: 11px; color: var(--text3); margin-top: 5px; }
.field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media(max-width:640px){
  .field-grid { grid-template-columns: 1fr; }
  .action-bar { flex-direction: column; align-items: stretch; text-align: center; }
  .action-btns { justify-content: center; }
  .content-header { flex-direction: column; align-items: flex-start; gap: 8px; }
}
@media(max-width:480px){
  .form-card { padding: 16px; }
  .action-bar { padding: 12px 14px; }
  .action-btns { flex-direction: column; }
  .action-btns .btn { width: 100%; justify-content: center; }
  .page-hdr-left h2 { font-size: 18px; }
  .upload-zone { padding: 20px 14px; }
}
.field-select[multiple] { padding: 8px; background-image: none; height: 100px; }
.field-select[multiple] option { padding: 5px 8px; border-radius: 5px; margin-bottom: 2px; }
.upload-zone { border: 2px dashed var(--border2); border-radius: var(--radius-sm); padding: 28px 20px; text-align: center; cursor: pointer; transition: border-color var(--transition), background var(--transition); position: relative; }
.upload-zone:hover { border-color: var(--accent); background: var(--accent-glow); }
.upload-zone.has-file { border-color: var(--green); border-style: solid; background: rgba(16,185,129,0.04); }
.upload-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.upload-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(99,102,241,0.10); display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; }
.upload-icon svg { width: 20px; height: 20px; color: var(--accent); }
.upload-text { font-size: 13px; font-weight: 500; color: var(--text2); }
.upload-sub  { font-size: 11.5px; color: var(--text3); margin-top: 3px; }
.upload-preview { width: 100%; max-height: 180px; object-fit: cover; border-radius: 8px; margin-top: 12px; display: none; }
.content-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 7px; }
.char-count { font-size: 11.5px; font-family: var(--font-mono); color: var(--text3); transition: color var(--transition); }
.char-count.warn { color: var(--red); }
.char-count.ok   { color: var(--green); }
.read-time-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 100px; background: rgba(99,102,241,0.10); border: 1px solid rgba(99,102,241,0.2); font-size: 11px; font-weight: 600; color: var(--accent); font-family: var(--font-mono); }
.read-time-badge svg { width: 11px; height: 11px; }
.meta-desc-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 5px; }
.desc-count { font-size: 11px; font-family: var(--font-mono); color: var(--text3); }
.desc-count.over  { color: var(--red); }
.desc-count.great { color: var(--green); }
.action-bar { position: sticky; bottom: 0; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; box-shadow: 0 -4px 24px rgba(0,0,0,0.06); margin-top: 16px; animation: fadeUp 0.4s 0.2s ease both; z-index: 50; flex-wrap: wrap; }
.action-bar-hint { font-size: 12px; color: var(--text3); }
.action-bar-hint strong { color: var(--text2); font-weight: 600; }
.action-btns { display: flex; gap: 8px; }
.alert { padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13px; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 10px; border: 1px solid transparent; }
.alert svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 1px; }
.alert-error { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.2); color: #b91c1c; }
.right-panel { position: sticky; top: 80px; display: flex; flex-direction: column; gap: 14px; animation: fadeUp 0.4s 0.1s ease both; }
.p-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px; box-shadow: var(--shadow); overflow: hidden; }
.p-card-title { font-size: 10px; font-weight: 700; color: var(--text3); text-transform: uppercase; letter-spacing: 0.12em; font-family: var(--font-mono); margin-bottom: 14px; display: flex; align-items: center; gap: 6px; }
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
.cl-item { display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: var(--radius-sm); background: var(--surface2); border: 1px solid var(--border); font-size: 12px; color: var(--text2); transition: background var(--transition), border-color var(--transition); }
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
@keyframes toastIn { from { opacity: 0; transform: translateX(20px) scale(0.96); } to { opacity: 1; transform: translateX(0) scale(1); } }
@media (max-width: 1100px) { .editor-layout { grid-template-columns: 1fr; } .right-panel { position: static; } }
@media (max-width: 860px) { .body { padding: 16px 16px 80px; } }
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
    var hasTags    = tagEl && [].some.call(tagEl.options, function(o){ return o.selected; });
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
    $('readScore').textContent = rs > 0 ? rs + '/100' : '—';
    $('avgWords').textContent  = contentVal.trim() ? avgWPS(contentVal) + 'w' : '—';
    $('longSents').textContent = contentVal.trim() ? longSents(contentVal) : '—';
    $('paraCount').textContent = contentVal.trim() ? paraCount(contentVal) : '—';

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
        serpD.textContent = 'Your meta description will appear here…';
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
    var dc = $('descCount');
    dc.textContent = dl + ' / 160';
    dc.className = 'desc-count' + (dl > 160 ? ' over' : dl >= 120 ? ' great' : '');
}

['title','blogContent','excerpt','meta_title','meta_description'].forEach(function(id){
    var el = document.getElementById(id);
    if(el) el.addEventListener('input', update);
});
var catEl = document.getElementById('category_id');
if(catEl) catEl.addEventListener('change', update);
var tagEl = document.getElementById('tag_ids');
if(tagEl) tagEl.addEventListener('change', update);

var upload  = document.getElementById('coverUpload');
var zone    = document.getElementById('uploadZone');
var preview = document.getElementById('uploadPreview');
var upText  = document.getElementById('uploadText');

if(upload){
    upload.addEventListener('change', function(){
        var file = this.files[0];
        if(!file) return;
        var reader = new FileReader();
        reader.onload = function(e){
            preview.src = e.target.result;
            preview.style.display = 'block';
            upText.textContent = file.name;
            zone.classList.add('has-file');
            update();
        };
        reader.readAsDataURL(file);
    });

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

update();

})();
</script>
@endpush
