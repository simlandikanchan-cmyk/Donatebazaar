@push('page_css')
@vite('resources/css/admin/entries/legal.css')
@endpush

@extends('layouts.admin')

@section('sidebar_legal', 'active')
@section('page_title', 'Edit Legal Page')
@section('page_subtitle', 'Update policy content shown at /{{ $slug }}')

@section('topbar_left')
  <a href="{{ route('admin.legal.index') }}" class="btn btn-secondary back-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
    Legal Pages
  </a>
@endsection

@section('content')
@if($errors->any())
<div class="alert-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  <div>
    <strong>Please fix the following:</strong>
    <ul style="margin-top:4px;padding-left:16px;">
      @foreach($errors->all() as $e)<li style="font-size:12px;margin-top:2px;">{{ $e }}</li>@endforeach
    </ul>
  </div>
</div>
@endif

<form method="POST" action="{{ route('admin.legal.update', $slug) }}">
  @csrf @method('PUT')
  <div class="card">
    <div class="card-head">
      <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
      <span class="card-head-title">Content</span>
    </div>
    <div class="card-body">
      <div class="field">
        <label class="f-label" for="title">Page Title <span class="req">*</span></label>
        <input id="title" name="title" type="text" value="{{ old('title', $page->title ?? \App\Models\LegalPage::slugs()[$slug]) }}"
          class="f-input {{ $errors->has('title')?'err':'' }}" required>
        @error('title')<p class="f-error">{{ $message }}</p>@enderror
      </div>

      <div class="field">
        <label class="f-label" for="content">Policy Content (HTML allowed) <span class="req">*</span></label>
        <textarea id="content" name="content" class="f-input {{ $errors->has('content')?'err':'' }}" placeholder="<h2>Heading</h2><p>Your policy text…</p>" required>{{ old('content', $page->content ?? '') }}</textarea>
        <p class="f-hint">You may use basic HTML (headings, paragraphs, lists, links) to structure the policy.</p>
        @error('content')<p class="f-error">{{ $message }}</p>@enderror
      </div>
    </div>
  </div>

  <div style="margin-top:18px;display:flex;gap:10px;">
    <button type="submit" class="submit-btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
      Save Page
    </button>
    <a href="{{ route('admin.legal.index') }}" class="btn btn-secondary back-btn" style="text-decoration:none;">Cancel</a>
  </div>
</form>
@endsection

