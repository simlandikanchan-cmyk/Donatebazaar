@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/blogs.css')
@endpush


@section('sidebar_blogs', 'active')
@section('page_title', 'Create Blog')
@section('page_subtitle', 'Admin — publish directly or schedule')

@section('content')
<div class="admin-badge">Admin Publishing Mode — Direct Publish Available</div>

<div class="page-hdr">
    <div class="page-hdr-left">
        <h2>Create New Blog Post</h2>
        <p>Fill in all fields — admins can publish directly, schedule, or save as draft</p>
    </div>
</div>

@if($errors->any())
<div class="alert-error">
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

<form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
    @csrf

    <div class="editor-layout">

        {{-- ══ LEFT COLUMN ══ --}}
        <div class="form-col">

            {{-- Card 1: Basic Info --}}
            <div class="form-card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Basic Information
                </div>
                <div class="field">
                    <label class="field-label" for="title">Title <span>*</span></label>
                    <input id="title" name="title" type="text" value="{{ old('title') }}" class="field-input {{ $errors->has('title') ? 'is-error' : '' }}" placeholder="Enter a compelling blog title…" required>
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
                        <label class="field-label" for="tag_ids">Tags <small>Hold Ctrl / Cmd for multiple</small></label>
                        <select id="tag_ids" name="tag_ids[]" multiple class="field-select">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tag_ids', [])))>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="field">
                    <label class="field-label" for="excerpt">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" rows="3" class="field-textarea" placeholder="Short description shown on listing pages…">{{ old('excerpt') }}</textarea>
                    <p class="field-hint">Keep it under 160 characters for best SEO results</p>
                </div>
            </div>

            {{-- Card 2: Cover Image --}}
            <div class="form-card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    Cover Image
                </div>
                <div class="upload-zone" id="uploadZone">
                    <input type="file" name="cover_image" accept="image/*" id="coverUpload">
                    <div class="upload-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg></div>
                    <div class="upload-text" id="uploadText">Click to upload or drag &amp; drop image</div>
                    <div class="upload-sub">JPG, PNG, WebP · Max 5MB</div>
                    <img id="uploadPreview" class="upload-preview" alt="Cover Preview">
                </div>
            </div>

            {{-- Card 3: Content --}}
            <div class="form-card">
                <div class="content-header">
                    <div class="card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
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
                <div class="field" style="margin-bottom:0">
                    <textarea id="blogContent" name="content" rows="16" class="field-textarea {{ $errors->has('content') ? 'is-error' : '' }}" placeholder="Write your blog content here…" required>{{ old('content') }}</textarea>
                    @error('content')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <input type="hidden" name="read_time_minutes" id="readTimeInput" value="{{ old('read_time_minutes', 0) }}">
            </div>

            {{-- Card 4: SEO --}}
            <div class="form-card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
                    SEO Settings
                </div>
                <div class="field">
                    <label class="field-label" for="meta_title">SEO Title</label>
                    <input id="meta_title" name="meta_title" type="text" value="{{ old('meta_title') }}" class="field-input" placeholder="Leave blank to use the blog title">
                    <p class="field-hint">Recommended: 50–60 characters</p>
                </div>
                <div class="field">
                    <label class="field-label" for="meta_description">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="3" class="field-textarea" maxlength="160" placeholder="Brief summary for search engines…">{{ old('meta_description') }}</textarea>
                    <div class="meta-desc-footer">
                        <p class="field-hint" style="margin-top:0;">Recommended: 120–160 characters</p>
                        <span class="desc-count" id="descCount">0 / 160</span>
                    </div>
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

            {{-- Card 5: Publish Controls --}}
            <div class="form-card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Publish Controls
                    <span class="card-title-badge">Admin Only</span>
                </div>
                <div class="toggle-row">
                    <div class="toggle-info">
                        <div class="toggle-label">Publish Immediately</div>
                        <div class="toggle-desc">Make this post live as soon as you save</div>
                    </div>
                    <div class="toggle-switch">
                        <input type="checkbox" id="publishNow" name="publish_now" value="1" {{ old('publish_now') ? 'checked' : '' }} onchange="toggleSchedule()">
                        <label for="publishNow"></label>
                    </div>
                </div>
                <div class="toggle-row">
                    <div class="toggle-info">
                        <div class="toggle-label">Schedule for Later</div>
                        <div class="toggle-desc">Set a specific date and time to publish</div>
                    </div>
                    <div class="toggle-switch">
                        <input type="checkbox" id="scheduleToggle" name="schedule_toggle" value="1" onchange="toggleScheduleDate()">
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

            {{-- Card 6: Visibility & Features --}}
            <div class="form-card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Visibility &amp; Features
                    <span class="card-title-badge green">Controls</span>
                </div>
                <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Featured Post</div><div class="toggle-desc">Show in homepage featured blog section</div></div><div class="toggle-switch"><input type="checkbox" id="isFeatured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}><label for="isFeatured"></label></div></div>
                <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Pinned to Top</div><div class="toggle-desc">Always show at the top of blog listings</div></div><div class="toggle-switch"><input type="checkbox" id="isPinned" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}><label for="isPinned"></label></div></div>
                <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Allow Comments</div><div class="toggle-desc">Let readers comment on this post</div></div><div class="toggle-switch"><input type="checkbox" id="allowComments" name="allow_comments" value="1" checked {{ old('allow_comments',1) ? 'checked' : '' }}><label for="allowComments"></label></div></div>
                <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Allow Likes</div><div class="toggle-desc">Let readers like / react to this post</div></div><div class="toggle-switch"><input type="checkbox" id="allowLikes" name="allow_likes" value="1" checked {{ old('allow_likes',1) ? 'checked' : '' }}><label for="allowLikes"></label></div></div>
                <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Show Share Buttons</div><div class="toggle-desc">Display social share options on post</div></div><div class="toggle-switch"><input type="checkbox" id="showShare" name="show_share" value="1" checked {{ old('show_share',1) ? 'checked' : '' }}><label for="showShare"></label></div></div>
                <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Newsletter Syndication</div><div class="toggle-desc">Include in next newsletter email blast</div></div><div class="toggle-switch"><input type="checkbox" id="syndicateNewsletter" name="syndicate_newsletter" value="1" {{ old('syndicate_newsletter') ? 'checked' : '' }}><label for="syndicateNewsletter"></label></div></div>
            </div>

            {{-- Card 7: Attribution & Campaign Link --}}
            <div class="form-card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Attribution &amp; Campaign Link
                    <span class="card-title-badge orange">Extended</span>
                </div>
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
                <div class="field">
                    <label class="field-label" for="reading_level">Target Reading Level</label>
                    <select id="reading_level" name="reading_level" class="field-select">
                        <option value="general" @selected(old('reading_level','general') === 'general')>General Public (Grade 8–10)</option>
                        <option value="educated" @selected(old('reading_level') === 'educated')>Educated Adults (Grade 12+)</option>
                        <option value="expert"   @selected(old('reading_level') === 'expert')>Expert / Professional</option>
                        <option value="simple"   @selected(old('reading_level') === 'simple')>Simple / Easy Read (Grade 6)</option>
                    </select>
                </div>
                <div class="field-grid">
                    <div class="field" style="margin-bottom:0">
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
                </div>
            </div>

            {{-- Card 8: OG / Social --}}
            <div class="form-card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                    Open Graph &amp; Social Sharing
                    <span class="card-title-badge">OG</span>
                </div>
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
                        <div class="upload-text" id="ogText" style="font-size:12px;">Click to upload OG image (1200×630 recommended)</div>
                        <img id="ogPreview" class="upload-preview" alt="OG Preview">
                    </div>
                </div>
            </div>

            {{-- Sticky Action Bar --}}
            <div class="action-bar">
                <p class="action-bar-hint">
                    <strong>Draft</strong> saves privately ·
                    <strong>Publish</strong> goes live instantly ·
                    <strong>Schedule</strong> sets a future date
                </p>
                <div class="action-btns">
                    <x-button variant="secondary" type="submit" name="action" value="draft">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Save Draft
                    </x-button>
                    <x-button variant="primary" type="submit" name="action" value="schedule">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Schedule
                    </x-button>
                    <x-button variant="primary" type="submit" name="action" value="publish">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3l14 9-14 9V3z"/></svg>
                        Publish Now
                    </x-button>
                </div>
            </div>

        </div>

        {{-- ══ RIGHT PANEL ══ --}}
        <aside class="right-panel">

            @php
                $totalBlogs     = \App\Models\Blog::count();
                $publishedBlogs = \App\Models\Blog::where('status','approved')->count();
                $draftBlogs     = \App\Models\Blog::where('status','draft')->count();
                $pendingBlogs   = \App\Models\Blog::where('status','pending')->count();
            @endphp
            <div class="p-card accent-card">
                <div class="p-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Blog Stats
                </div>
                <div class="admin-stat-grid">
                    <div class="admin-stat-box"><div class="admin-stat-num">{{ $totalBlogs }}</div><div class="admin-stat-lbl">Total</div></div>
                    <div class="admin-stat-box"><div class="admin-stat-num" style="color:var(--green)">{{ $publishedBlogs }}</div><div class="admin-stat-lbl">Live</div></div>
                    <div class="admin-stat-box"><div class="admin-stat-num" style="color:var(--amber)">{{ $draftBlogs }}</div><div class="admin-stat-lbl">Drafts</div></div>
                    <div class="admin-stat-box"><div class="admin-stat-num" style="color:var(--orange)">{{ $pendingBlogs }}</div><div class="admin-stat-lbl">Pending</div></div>
                </div>
            </div>

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
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Readability
                </div>
                <div class="read-grade-bar">
                    <div class="read-grade-label"><span id="readLabel">No content</span><small id="readScore">—</small></div>
                    <div class="bar-track"><div class="bar-fill" id="readBar" style="width:0%;background:var(--red)"></div></div>
                </div>
                <div class="read-stats">
                    <div class="read-stat"><div class="read-stat-num" id="avgWords">—</div><div class="read-stat-lbl">avg words/sent</div></div>
                    <div class="read-stat"><div class="read-stat-num" id="longSents">—</div><div class="read-stat-lbl">long sentences</div></div>
                    <div class="read-stat"><div class="read-stat-num" id="paraCount">—</div><div class="read-stat-lbl">paragraphs</div></div>
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
<script>
(function(){
'use strict';

/* —€—€ SCHEDULE TOGGLES —€—€ */
window.toggleSchedule=function(){
  var schedEl=document.getElementById('scheduleToggle');
  if(document.getElementById('publishNow').checked&&schedEl){schedEl.checked=false;toggleScheduleDate();}
};
window.toggleScheduleDate=function(){
  var row=document.getElementById('scheduleRow');
  var checked=document.getElementById('scheduleToggle').checked;
  row.classList.toggle('show',checked);
  if(checked) document.getElementById('publishNow').checked=false;
};

/* —€—€ HELPERS —€—€ */
function $$(id){return document.getElementById(id);}
function wordCount(t){return t.trim()===''?0:t.trim().split(/\s+/).length;}
function sentences(t){return t.split(/[.!?]+/).filter(function(s){return s.trim().split(/\s+/).length>2;});}
function avgWPS(t){var s=sentences(t);if(!s.length)return 0;return Math.round(s.reduce(function(a,b){return a+b.trim().split(/\s+/).length;},0)/s.length);}
function longSents(t){return sentences(t).filter(function(s){return s.trim().split(/\s+/).length>20;}).length;}
function paraCount(t){return t.split(/\n\s*\n/).filter(function(p){return p.trim().length>0;}).length||(t.trim().length>0?1:0);}
function readScore(t){if(wordCount(t)<10)return 0;var avg=avgWPS(t);if(avg<=12)return 95;if(avg<=15)return 82;if(avg<=20)return 65;if(avg<=25)return 45;return 25;}
function slugify(t){return t.toLowerCase().replace(/[^a-z0-9\s]/g,'').trim().replace(/\s+/g,'-').slice(0,50)||'your-title';}
function barColor(p){return p>=70?'var(--green)':p>=40?'var(--amber)':'var(--red)';}
function titleBarColor(l){if(l>=40&&l<=60)return'var(--green)';if(l>60&&l<=70)return'var(--amber)';if(l>70)return'var(--red)';return'var(--border2)';}
function descBarColor(l){if(l>=120&&l<=160)return'var(--green)';if(l>160)return'var(--red)';if(l>=50)return'var(--amber)';return'var(--border2)';}

function setQCheck(id,state){
  var el=document.getElementById(id);if(!el)return;
  var icon=el.querySelector('.q-check-icon');
  icon.className='q-check-icon '+state;
  icon.innerHTML=state==='done'
    ?'<svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4.5" fill="rgba(5,196,138,0.2)"/><path d="M2.5 5l1.5 1.5 3-3" stroke="var(--green)" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    :'<svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg>';
}

function setCLItem(id,state,valText){
  var el=$$(id);if(!el)return;
  el.className='cl-item '+state;
  var dot=el.querySelector('.cl-dot');
  dot.className='cl-dot '+state;
  dot.innerHTML=state==='done'
    ?'<svg viewBox="0 0 10 10" fill="none"><path d="M2 5l2 2 4-4" stroke="var(--green)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    :state==='warn'
    ?'<svg viewBox="0 0 10 10"><rect x="4.25" y="1.5" width="1.5" height="4" rx=".75" fill="var(--amber)"/><rect x="4.25" y="7" width="1.5" height="1.5" rx=".75" fill="var(--amber)"/></svg>'
    :'<svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg>';
  var v=el.querySelector('.cl-val');
  v.className='cl-val '+state;
  v.textContent=valText;
}

function update(){
  var titleVal=($$('title')||{value:''}).value;
  var contentVal=($$('blogContent')||{value:''}).value;
  var excerptVal=($$('excerpt')||{value:''}).value;
  var metaTitleVal=($$('meta_title')||{value:''}).value;
  var metaDescVal=($$('meta_description')||{value:''}).value;
  var catEl=$$('category_id');var hasCat=catEl&&catEl.value&&catEl.value!=='';
  var tagEl=$$('tag_ids');var hasTags=tagEl&&[].some.call(tagEl.options,function(o){return o.selected;});
  var imgEl=$$('coverUpload');var hasImg=imgEl&&imgEl.files&&imgEl.files.length>0;
  var wc=wordCount(contentVal);
  var tLen=titleVal.length;
  var hasTitle=tLen>=40&&tLen<=70;
  var hasWords=wc>=300;
  var hasExcerpt=excerptVal.trim().length>0;
  var hasMeta=metaDescVal.trim().length>0;

  /* Score */
  var score=0;
  if(hasTitle)score+=20;else if(tLen>0)score+=Math.round(tLen/70*20);
  if(hasWords)score+=30;else score+=Math.round(Math.min(wc/300,1)*30);
  if(hasExcerpt)score+=15;if(hasImg)score+=20;if(hasMeta)score+=15;
  score=Math.min(100,Math.round(score));
  var circ=188.5,offset=circ-(circ*score/100);
  var ringEl=$$('scoreRingFill');
  ringEl.style.strokeDashoffset=offset.toFixed(1);
  ringEl.style.stroke=barColor(score);
  $$('scoreNum').textContent=score;
  var lbl,sub;
  if(score>=85){lbl='Excellent';sub='Great post — ready to publish!';}
  else if(score>=65){lbl='Good';sub='Almost there, small tweaks needed';}
  else if(score>=40){lbl='Fair';sub='Keep going, more content needed';}
  else if(score>0){lbl='Weak';sub='Fill in more details to improve';}
  else{lbl='Not started';sub='Fill in the form to build score';}
  $$('scoreLabel').textContent=lbl;$$('scoreSub').textContent=sub;
  setQCheck('qc-title',hasTitle?'done':'wait');$$('qc-title-v').textContent=tLen+' chars';
  setQCheck('qc-words',hasWords?'done':'wait');$$('qc-words-v').textContent=wc+' words';
  setQCheck('qc-excerpt',hasExcerpt?'done':'wait');
  setQCheck('qc-image',hasImg?'done':'wait');
  setQCheck('qc-meta',hasMeta?'done':'wait');

  /* Char count + read time */
  var cLen=contentVal.length;
  $$('charCount').textContent=cLen.toLocaleString()+' chars';
  $$('charCount').className='char-count'+(cLen>0&&cLen<50?' warn':cLen>=50?' ok':'');
  var mins=Math.max(1,Math.ceil(wc/200));
  $$('readTimeText').textContent=mins+' min';
  $$('readTimeInput').value=mins;

  /* Readability */
  var rs=readScore(contentVal);
  $$('readBar').style.width=rs+'%';$$('readBar').style.background=barColor(rs);
  var rl=rs>=80?'Easy to read':rs>=60?'Fairly readable':rs>=40?'Moderate':rs>0?'Difficult':'No content';
  $$('readLabel').textContent=rl;$$('readScore').textContent=rs>0?rs+'/100':'—';
  $$('avgWords').textContent=contentVal.trim()?avgWPS(contentVal)+'w':'—';
  $$('longSents').textContent=contentVal.trim()?longSents(contentVal):'—';
  $$('paraCount').textContent=contentVal.trim()?paraCount(contentVal):'—';

  /* SERP */
  var dispTitle=metaTitleVal||titleVal;
  var dispDesc=metaDescVal||excerptVal;
  var slug=slugify(titleVal);
  $$('serpUrl').textContent='DonateBazaar.com › blog › '+slug;
  var serpT=$$('serpTitle');
  if(dispTitle){serpT.textContent=dispTitle.length>65?dispTitle.slice(0,65)+'…':dispTitle;serpT.className='serp-title';}
  else{serpT.textContent='Your title will appear here';serpT.className='serp-title empty';}
  var serpD=$$('serpDesc');
  if(dispDesc){serpD.textContent=dispDesc.length>155?dispDesc.slice(0,155)+'…':dispDesc;serpD.className='serp-desc';}
  else{serpD.textContent='Your meta description will appear here…';serpD.className='serp-desc empty';}
  var tBarLen=(metaTitleVal||titleVal).length,dBarLen=metaDescVal.length;
  $$('titleBar').style.width=Math.min(100,Math.round(tBarLen/60*100))+'%';
  $$('titleBar').style.background=titleBarColor(tBarLen);
  $$('titleBarNum').textContent=tBarLen+'/60';
  $$('descBar').style.width=Math.min(100,Math.round(dBarLen/160*100))+'%';
  $$('descBar').style.background=descBarColor(dBarLen);
  $$('descBarNum').textContent=dBarLen+'/160';
  var dl=metaDescVal.length,dc=$$('descCount');
  if(dc){dc.textContent=dl+' / 160';dc.className='desc-count'+(dl>160?' over':dl>=120?' great':'');}

  /* Checklist */
  var reqDone=0;
  if(tLen>0){setCLItem('cl-title','done',tLen+' chars');reqDone++;}else setCLItem('cl-title','fail','Missing');
  if(hasCat){setCLItem('cl-cat','done','Selected');reqDone++;}else setCLItem('cl-cat','fail','Missing');
  if(wc>=100){setCLItem('cl-content','done',wc+' words');reqDone++;}else if(wc>0)setCLItem('cl-content','warn',wc+' words');else setCLItem('cl-content','fail','0 words');
  if(hasImg){setCLItem('cl-cover','done','Uploaded');reqDone++;}else setCLItem('cl-cover','fail','Not set');
  if(hasExcerpt)setCLItem('cl-excerpt','done','Added');else setCLItem('cl-excerpt','warn','Optional');
  if(hasMeta)setCLItem('cl-seo','done','Added');else setCLItem('cl-seo','warn','Optional');
  if(hasTags)setCLItem('cl-tags','done','Tagged');else setCLItem('cl-tags','warn','Optional');
  var rb=$$('readyBadge');
  rb.textContent=reqDone+' / 4 done';
  rb.className='ready-badge '+(reqDone>=4?'full':reqDone>=2?'part':'none');
}

['title','blogContent','excerpt','meta_title','meta_description'].forEach(function(id){
  var el=document.getElementById(id);if(el)el.addEventListener('input',update);
});
['category_id','tag_ids'].forEach(function(id){
  var el=document.getElementById(id);if(el)el.addEventListener('change',update);
});

function setupUpload(inputId,zoneId,previewId,textId){
  var upload=document.getElementById(inputId);
  var zone=document.getElementById(zoneId);
  var preview=document.getElementById(previewId);
  var upText=document.getElementById(textId);
  if(!upload)return;
  upload.addEventListener('change',function(){
    var file=this.files[0];if(!file)return;
    var reader=new FileReader();
    reader.onload=function(e){preview.src=e.target.result;preview.style.display='block';if(upText)upText.textContent=file.name;if(zone)zone.classList.add('has-file');update();};
    reader.readAsDataURL(file);
  });
  if(zone){
    zone.addEventListener('dragover',function(e){e.preventDefault();zone.style.borderColor='var(--a)';});
    zone.addEventListener('dragleave',function(){zone.style.borderColor='';});
    zone.addEventListener('drop',function(e){
      e.preventDefault();zone.style.borderColor='';
      var file=e.dataTransfer.files[0];
      if(file&&file.type.startsWith('image/')){var dt=new DataTransfer();dt.items.add(file);upload.files=dt.files;upload.dispatchEvent(new Event('change'));}
    });
  }
}
setupUpload('coverUpload','uploadZone','uploadPreview','uploadText');
setupUpload('ogUpload','ogZone','ogPreview','ogText');

var slugField=document.getElementById('slug');
var titleField=document.getElementById('title');
var slugEdited=slugField&&slugField.value!=='';
if(slugField&&titleField){
  slugField.addEventListener('input',function(){slugEdited=this.value!=='';});
  titleField.addEventListener('input',function(){if(!slugEdited)slugField.value=slugify(this.value);});
}

update();
})();
</script>
@endpush
