@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush


@section('sidebar_legal', 'active')
@section('page_title', 'Edit Legal Page')
@section('page_subtitle', 'Update policy content and settings')

@section('topbar_left')
  <x-button variant="secondary" href="{{ route('admin.legal.index') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
    Legal Pages
  </x-button>
@endsection

@section('content')
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

<div class="breadcrumb">
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
  <a href="{{ route('admin.legal.index') }}">Legal Pages</a>
  <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Edit {{ \App\Models\LegalPage::slugs()[$slug] ?? ucfirst($slug) }}</span>
</div>

<div class="page-header">
  <div class="page-header-left">
    <h2>Edit: {{ \App\Models\LegalPage::slugs()[$slug] ?? ucfirst($slug) }}</h2>
    <p>Manage content for /{{ $slug }} &middot; {{ $page?->updated_at?->diffForHumans() ?? 'Not yet customized' }}</p>
  </div>
  <div class="page-header-right">
    <x-button variant="ghost" href="{{ url('/'.$slug) }}" target="_blank">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
      Preview
    </x-button>
    <x-button variant="ghost" href="{{ route('admin.legal.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </x-button>
    <x-button variant="primary" type="submit">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Save Changes
    </x-button>
  </div>
</div>

<form id="legalForm" method="POST" action="{{ route('admin.legal.update', $slug) }}">
  @csrf @method('PUT')
  <div class="edit-layout">

    <div>
      <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            Page Details
          </div>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label" for="title">Page Title <span class="req">*</span></label>
            <input id="title" name="title" type="text" value="{{ old('title', $page->title ?? \App\Models\LegalPage::slugs()[$slug]) }}"
              class="form-input {{ $errors->has('title')?'error':'' }}" placeholder="e.g. Privacy Policy" required>
            <p class="form-hint">Shown in the browser tab and page heading.</p>
            @error('title')<span class="form-error"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>@enderror
          </div>
        </div>
      </div>

      <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10"/></svg>
            </div>
            Policy Content
          </div>
          <span id="wordCount" style="font-size:11px;color:var(--text3);font-family:var(--mono);">0 words</span>
        </div>
        <div class="editor-toolbar">
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg></x-button>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg></x-button>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3v7a6 6 0 006 6 6 6 0 006-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg></x-button>
          <x-button variant="ghost" type="button" class="editor-btn"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.3 12H3m3.6-5s.9-2 3.4-2c2.3 0 3.4 1.4 3.4 1.4"/><path stroke-linecap="round" stroke-linejoin="round" d="M10 17s.8 2 3.3 2c2.5 0 3.7-1.5 3.7-2.6 0-.4 0-.8-.2-1.4"/></svg></x-button>
          <div class="editor-divider"></div>
          <x-button variant="ghost" type="button" class="editor-btn">H2</x-button>
          <x-button variant="ghost" type="button" class="editor-btn">H3</x-button>
          <x-button variant="ghost" type="button" class="editor-btn">P</x-button>
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
        <div id="editor" class="editor-content" contenteditable="true" spellcheck="true">{!! old('content', $page->content ?? '') !!}</div>
        <input type="hidden" name="content" id="contentInput">
        <div class="editor-footer">
          <span id="charCount">0 characters</span>
          <span style="font-size:10px;color:var(--text3);">HTML is preserved</span>
        </div>
        @error('content')<span class="form-error" style="margin-top:8px;margin-left:4px;"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>@enderror
      </div>
    </div>

    <div>
      <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            Page Info
          </div>
        </div>
        <div class="card-body">
          <div class="meta-info">
            <div class="meta-row">
              <span class="meta-key">URL Slug</span>
              <span class="meta-val"><span class="slug-badge">/{{ $slug }}</span></span>
            </div>
            <div class="meta-row">
              <span class="meta-key">Status</span>
              <span class="meta-val">
                @if($page && $page->exists)
                  <span class="status-badge sb-custom"><span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>Custom</span>
                @else
                  <span class="status-badge sb-default"><span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>Default</span>
                @endif
              </span>
            </div>
            @if($page && $page->exists)
            <div class="meta-row">
              <span class="meta-key">Last Updated</span>
              <span class="meta-val">{{ $page->updated_at?->format('M d, Y') ?? '—' }}</span>
            </div>
            <div class="meta-row">
              <span class="meta-key">Updated By</span>
              <span class="meta-val">{{ $page->updatedBy->name ?? '—' }}</span>
            </div>
            @endif
            <div class="meta-row">
              <span class="meta-key">Public Page</span>
              <span class="meta-val"><a href="{{ url('/'.$slug) }}" target="_blank">View live page &rarr;</a></span>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            </div>
            Tips
          </div>
        </div>
        <div class="card-body">
          <div class="tip-box">
            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Use the toolbar to format headings, lists, and links. Changes are saved only when you click <strong>Save Changes</strong>.</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</form>
@endsection

@push('page_scripts')
<script>
(function () {
  'use strict';

  var editor       = document.getElementById('editor');
  var contentInput = document.getElementById('contentInput');
  var wordCountEl  = document.getElementById('wordCount');
  var charCountEl  = document.getElementById('charCount');

  function updateCounts() {
    var text  = editor.innerText || '';
    var words = text.trim() ? text.trim().split(/\s+/).length : 0;
    wordCountEl.textContent = words + ' word' + (words !== 1 ? 's' : '');
    charCountEl.textContent = text.length + ' character' + (text.length !== 1 ? 's' : '');
    contentInput.value = editor.innerHTML;
  }

  editor.addEventListener('input', updateCounts);
  updateCounts();

  document.querySelectorAll('.editor-btn[data-cmd]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var cmd = this.dataset.cmd;
      if      (cmd === 'h2')         { document.execCommand('formatBlock', false, 'h2'); }
      else if (cmd === 'h3')         { document.execCommand('formatBlock', false, 'h3'); }
      else if (cmd === 'p')          { document.execCommand('formatBlock', false, 'p'); }
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

  document.getElementById('legalForm').addEventListener('submit', function () {
    contentInput.value = editor.innerHTML;
  });
})();
</script>
@endpush
