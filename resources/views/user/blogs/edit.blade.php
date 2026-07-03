@extends('layouts.user')

@section('page_title', 'Edit Blog')

@section('content')
<div class="page-hdr">
    <div class="page-hdr-left">
        <h2>Edit Blog</h2>
        <p>Update "{{ Str::limit($blog->title, 50) }}"</p>
    </div>
    <a href="{{ route('user.blogs.index') }}" class="btn btn-secondary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Back to Blogs
    </a>
</div>

<form action="{{ route('user.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-card">
        <div class="form-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Basic Information
        </div>

        <div class="field">
            <label class="field-label">Title <span>*</span></label>
            <input
                type="text"
                name="title"
                value="{{ old('title', $blog->title) }}"
                placeholder="Enter a compelling title…"
                class="field-input @error('title') has-error @enderror">
            @error('title')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field-grid">
            <div class="field">
                <label class="field-label">Category</label>
                <div class="select-wrap">
                    <select name="category_id" class="field-select">
                        <option value="">Select category…</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                @selected(old('category_id', $blog->category_id) == $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="field">
                <label class="field-label">Tags</label>
                <select name="tag_ids[]" multiple class="field-select" style="height: auto; min-height: 42px;">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}"
                            @selected(in_array($tag->id, old('tag_ids', $blog->tags->pluck('id')->toArray() ?? [])))>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                <p class="field-hint">Hold Ctrl / Cmd to select multiple</p>
            </div>
        </div>

        <div class="field">
            <label class="field-label">Excerpt</label>
            <textarea
                name="excerpt"
                rows="3"
                placeholder="A short summary shown in blog listings…"
                class="field-textarea">{{ old('excerpt', $blog->excerpt) }}</textarea>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/></svg>
            Cover Image
        </div>

        @if($blog->cover_image)
        <div class="cover-preview">
            <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="Current cover">
            <span class="cover-preview-label">Current</span>
        </div>
        @endif

        <div class="file-wrap">
            <input type="file" name="cover_image" accept="image/*">
        </div>
        <p class="field-hint" style="margin-top:8px;">Upload a new image to replace the existing one. Recommended: 1200×630px.</p>
    </div>

    <div class="form-card">
        <div class="textarea-header">
            <label class="field-label" style="margin-bottom:0;">
                <span style="display:flex;align-items:center;gap:6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" style="opacity:.8;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    Content <span style="color:var(--red);margin-left:2px;">*</span>
                </span>
            </label>
            <span id="charCount" class="char-count">0 characters</span>
        </div>

        <textarea
            name="content"
            id="blogContent"
            rows="18"
            placeholder="Write your blog content here…"
            class="field-textarea @error('content') has-error @enderror">{{ old('content', $blog->content) }}</textarea>

        @error('content')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-card">
        <div class="form-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
            SEO Settings
        </div>

        <div class="field">
            <label class="field-label">Meta Title</label>
            <input
                type="text"
                name="meta_title"
                value="{{ old('meta_title', $blog->meta_title) }}"
                placeholder="Override the page title for search engines…"
                class="field-input">
            <p class="field-hint">Leave blank to use the blog title. Recommended: 50–60 characters.</p>
        </div>
    </div>

    <div class="action-bar">
        <p class="action-bar-info">All changes will be saved and submitted for review.</p>
        <div class="action-btns">
            <a href="{{ route('user.blogs.index') }}" class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit"
                    name="action"
                    value="draft"
                    class="btn btn-secondary">
                Save Draft
            </button>
            @if($blog->status == 'draft')
                <button type="submit"
                        name="action"
                        value="publish"
                        class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Publish Blog
                </button>
            @else
                <button type="submit"
                        class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Blog
                </button>
            @endif
        </div>
    </div>

</form>
@endsection

@push('page_styles')
<style>
.page-hdr { margin-bottom: 24px; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.page-hdr-left h2 { font-size: 22px; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }
.page-hdr-left p  { font-size: 12.5px; color: var(--text3); margin-top: 3px; }
.form-card { background: var(--surface); border: 1px solid var(--border2); border-radius: var(--radius); box-shadow: var(--shadow); padding: 24px 26px; margin-bottom: 16px; }
.form-card-title { font-size: 13px; font-weight: 700; color: var(--text); letter-spacing: -0.01em; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 8px; }
.form-card-title svg { width: 15px; height: 15px; color: var(--accent); opacity: 0.8; }
.field { margin-bottom: 18px; }
.field:last-child { margin-bottom: 0; }
.field-label { display: block; font-size: 12px; font-weight: 600; color: var(--text2); margin-bottom: 7px; letter-spacing: 0.01em; }
.field-label span { color: var(--red); margin-left: 2px; }
.field-input, .field-select, .field-textarea { width: 100%; padding: 10px 14px; background: var(--surface2); border: 1px solid var(--border2); border-radius: var(--radius-sm); font-size: 13px; font-family: var(--font); color: var(--text); outline: none; transition: border-color var(--transition), box-shadow var(--transition); appearance: none; -webkit-appearance: none; }
.field-input:focus, .field-select:focus, .field-textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
.field-input.has-error, .field-textarea.has-error { border-color: var(--red); }
.field-error { font-size: 11px; color: var(--red); margin-top: 5px; }
.field-hint  { font-size: 11px; color: var(--text3); margin-top: 5px; }
.field-textarea { resize: vertical; line-height: 1.6; }
.select-wrap { position: relative; }
.select-wrap::after { content: ''; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 0; height: 0; border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 5px solid var(--text3); pointer-events: none; }
.field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.file-wrap { border: 1.5px dashed var(--border2); border-radius: var(--radius-sm); padding: 14px 16px; background: var(--surface2); transition: border-color var(--transition); cursor: pointer; }
.file-wrap:hover { border-color: var(--accent); }
.file-wrap input[type="file"] { width: 100%; font-size: 12.5px; font-family: var(--font); color: var(--text2); background: transparent; border: none; outline: none; cursor: pointer; }
.cover-preview { position: relative; border-radius: var(--radius-sm); overflow: hidden; margin-bottom: 12px; border: 1px solid var(--border2); background: var(--surface2); }
.cover-preview img { width: 100%; height: 160px; object-fit: cover; display: block; }
.cover-preview-label { position: absolute; top: 10px; left: 10px; font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 100px; font-family: var(--font-mono); letter-spacing: 0.05em; text-transform: uppercase; background: rgba(0,0,0,0.55); color: #d1d5db; backdrop-filter: blur(6px); }
.textarea-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 7px; }
.char-count { font-size: 11px; font-family: var(--font-mono); color: var(--text3); transition: color var(--transition); }
.char-count.warn { color: var(--red); }
.action-bar { position: sticky; bottom: 16px; background: var(--surface); border: 1px solid var(--border2); border-radius: var(--radius); padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--shadow-lg); gap: 12px; flex-wrap: wrap; backdrop-filter: blur(12px); margin-top: 8px; }
.action-bar-info { font-size: 12.5px; color: var(--text3); }
.action-btns { display: flex; align-items: center; gap: 8px; }
@media (max-width: 860px) { .body { padding: 16px 16px 60px; } }
@media (max-width: 640px) { .field-grid { grid-template-columns: 1fr; } .action-bar { flex-direction: column; align-items: stretch; } .action-btns { justify-content: flex-end; } }
</style>
@endpush

@push('page_scripts')
<script>
var textarea = document.getElementById('blogContent');
var counter  = document.getElementById('charCount');

function updateCount() {
    var length = textarea.value.length;
    counter.textContent = length.toLocaleString() + ' characters';
    counter.classList.toggle('warn', length < 50);
}

textarea.addEventListener('input', updateCount);
updateCount();
</script>
@endpush
