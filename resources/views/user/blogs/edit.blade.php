@extends('layouts.user')

@section('page_title', 'Edit Blog')

@section('content')
<x-page-hero
    tag="Blog"
    title="Edit Blog"
    subtitle='Update "{{ Str::limit($blog->title, 50) }}"'
>
    <x-slot:actions>
        <x-button variant="secondary" href="{{ route('user.blogs.index') }}" class="wb-btn wb-btn-ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to Blogs
        </x-button>
    </x-slot:actions>
</x-page-hero>

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
                <select name="tag_ids[]" multiple class="field-select field-select-multi">
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
        <p class="field-hint field-hint-lg">Upload a new image to replace the existing one. Recommended: 1200×630px.</p>
    </div>

    <div class="form-card">
        <div class="textarea-header">
            <label class="field-label field-label-no-mb">
                <span class="field-label-flex">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    Content <span class="field-required">*</span>
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
            <x-button variant="secondary" href="{{ route('user.blogs.index') }}">
                Cancel
            </x-button>
            <x-button variant="secondary" type="submit">
                Save Draft
            </x-button>
            @if($blog->status == 'draft')
                <x-button variant="primary" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Publish Blog
                </x-button>
            @else
                <x-button variant="primary" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Blog
                </x-button>
            @endif
        </div>
    </div>

</form>
@endsection

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
