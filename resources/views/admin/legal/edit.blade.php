@extends('layouts.admin')

@section('sidebar_legal', 'active')
@section('page_title', 'Edit Legal Page')
@section('page_subtitle', 'Update policy content and settings')

@section('topbar_left')
  <a href="{{ route('admin.legal.index') }}" class="btn btn-secondary back-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
    Legal Pages
  </a>
@endsection

@push('page_styles')
<style>
.breadcrumb{display:flex;align-items:center;gap:6px;font-size:11.5px;color:var(--text3);font-family:var(--mono);margin-bottom:20px;animation:fadeUp .3s both;}
.breadcrumb a{color:var(--text3);transition:color var(--ease);text-decoration:none;}
.breadcrumb a:hover{color:var(--a);}
.breadcrumb svg{width:10px;height:10px;stroke:var(--text3);fill:none;stroke-width:2;flex-shrink:0;}
.breadcrumb span{color:var(--text2);font-weight:600;}
.flash-success{background:rgba(5,196,138,.09);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:11px 14px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;animation:fadeUp .35s both;}
.flash-error{background:rgba(240,68,68,.09);border:1px solid rgba(240,68,68,.25);color:#991b1b;padding:11px 14px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;animation:fadeUp .35s both;}
[data-theme="dark"] .flash-success{color:#34d399;}[data-theme="dark"] .flash-error{color:#f87171;}
.flash-success svg,.flash-error svg{width:15px;height:15px;flex-shrink:0;}
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:22px;gap:14px;flex-wrap:wrap;animation:fadeUp .35s both;}
.page-header-left h2{font-family:var(--mono);font-size:19px;font-weight:800;color:var(--text);letter-spacing:-.02em;margin-bottom:3px;}
.page-header-left p{font-size:12px;color:var(--text3);}
.page-header-right{display:flex;gap:8px;flex-wrap:wrap;}
.btn{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 16px;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;border:none;transition:opacity var(--ease),transform var(--ease),box-shadow var(--ease);cursor:pointer;font-family:var(--font);white-space:nowrap;text-decoration:none;}
.btn:hover{opacity:.88;transform:translateY(-1px);}
.btn svg{width:13px;height:13px;}
.btn-primary{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 14px rgba(37,99,235,.35);}
.btn-primary:hover{box-shadow:0 8px 22px rgba(37,99,235,.45);}
.btn-ghost{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-ghost:hover{border-color:var(--a);color:var(--a);}
.edit-layout{display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start;}
@media(max-width:1100px){.edit-layout{grid-template-columns:1fr;}}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s both;}
.card:nth-child(1){animation-delay:.05s;}.card:nth-child(2){animation-delay:.10s;}.card:nth-child(3){animation-delay:.12s;}
.card-header{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid var(--border);background:var(--surface2);}
.card-title{display:flex;align-items:center;gap:8px;font-family:var(--font);font-size:11px;font-weight:700;color:var(--text);text-transform:uppercase;letter-spacing:.06em;}
.card-title-icon{width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;background:var(--a-lt);}
.card-title-icon svg{width:13px;height:13px;stroke:var(--a);fill:none;stroke-width:2;}
.card-body{padding:20px 18px;}
.form-group{display:flex;flex-direction:column;gap:5px;margin-bottom:18px;}
.form-group:last-child{margin-bottom:0;}
.form-label{font-size:10px;font-weight:700;color:var(--text2);font-family:var(--mono);letter-spacing:.08em;text-transform:uppercase;display:flex;align-items:center;gap:5px;}
.form-label .req{color:var(--red);font-size:13px;}
.form-input,.form-textarea{width:100%;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease),background var(--ease);}
.form-input::placeholder,.form-textarea::placeholder{color:var(--text3);}
.form-input:focus,.form-textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
.form-input.error,.form-textarea.error{border-color:var(--red);box-shadow:0 0 0 3px rgba(240,68,68,.12);}
.form-textarea{resize:vertical;min-height:120px;line-height:1.65;}
.form-hint{font-size:11px;color:var(--text3);margin-top:3px;line-height:1.5;}
.form-error{font-size:11px;color:var(--red);margin-top:3px;display:flex;align-items:center;gap:4px;}
.form-error svg{width:11px;height:11px;flex-shrink:0;stroke:var(--red);fill:none;stroke-width:2;}
.editor-toolbar{display:flex;flex-wrap:wrap;gap:3px;padding:8px 10px;border-bottom:1px solid var(--border);background:var(--surface2);border-radius:var(--r-sm) var(--r-sm) 0 0;}
.editor-btn{width:30px;height:28px;border-radius:6px;border:none;background:transparent;color:var(--text2);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;transition:background var(--ease),color var(--ease);cursor:pointer;font-family:var(--font);}
.editor-btn:hover{background:var(--a-lt);color:var(--a);}
.editor-btn svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;}
.editor-divider{width:1px;height:24px;background:var(--border2);margin:0 3px;align-self:center;}
.editor-content{min-height:340px;max-height:520px;overflow-y:auto;padding:16px;font-size:14px;line-height:1.75;color:var(--text);font-family:var(--font);outline:none;border:none;background:var(--surface);border-radius:0 0 var(--r-sm) var(--r-sm);}
.editor-content:focus{outline:none;}
.editor-content h2{font-size:20px;font-weight:700;margin:16px 0 8px;color:var(--text);}
.editor-content h3{font-size:16px;font-weight:600;margin:14px 0 6px;color:var(--text);}
.editor-content p{margin:0 0 12px;}
.editor-content ul,.editor-content ol{margin:0 0 12px;padding-left:24px;}
.editor-content a{color:var(--a);text-decoration:underline;}
.editor-content blockquote{border-left:3px solid var(--a);padding-left:12px;margin:12px 0;color:var(--text2);font-style:italic;}
.editor-footer{display:flex;align-items:center;justify-content:space-between;padding:8px 12px;border-top:1px solid var(--border);background:var(--surface2);border-radius:0 0 var(--r-sm) var(--r-sm);margin-top:-1px;}
.editor-footer span{font-size:11px;color:var(--text3);font-family:var(--mono);}
.meta-info{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:2px 14px;}
.meta-row{display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid var(--border);}
.meta-row:last-child{border-bottom:none;}
.meta-key{font-size:10px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.meta-val{font-size:12px;font-weight:500;color:var(--text2);}
.meta-val a{color:var(--a);text-decoration:none;}
.meta-val a:hover{text-decoration:underline;}
.slug-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:100px;font-size:10px;font-weight:700;font-family:var(--mono);background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.2);}
.status-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:100px;font-size:10px;font-weight:700;font-family:var(--mono);}
.sb-custom{background:var(--green-lt);color:#059669;border:1px solid rgba(5,196,138,.25);}
.sb-default{background:rgba(100,116,139,.08);color:var(--text3);border:1px solid var(--border2);}
.tip-box{display:flex;gap:10px;padding:12px 14px;border-radius:var(--r-sm);background:var(--a-lt);border:1px solid rgba(37,99,235,.15);font-size:12px;color:var(--text2);line-height:1.55;}
.tip-box svg{width:14px;height:14px;flex-shrink:0;stroke:var(--a);fill:none;stroke-width:2;margin-top:1px;}
@media(max-width:640px){.edit-layout{gap:14px;grid-template-columns:1fr;}.page-header{flex-direction:column;align-items:flex-start;}.page-header-right{width:100%;}.page-header-right .btn{flex:1;justify-content:center;}}
@media(max-width:480px){.card-body{padding:14px 12px;}.form-input,.form-textarea{font-size:12px;padding:8px 11px;}.form-label{font-size:9px;}.editor-content{min-height:260px;font-size:13px;}.card-header{padding:12px 14px;}.page-header-left h2{font-size:16px;}}
</style>
@endpush

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
    <a href="{{ url('/'.$slug) }}" target="_blank" class="btn btn-ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
      Preview
    </a>
    <a href="{{ route('admin.legal.index') }}" class="btn btn-ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
    <button type="submit" form="legalForm" class="btn btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Save Changes
    </button>
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
          <button type="button" class="editor-btn" data-cmd="bold" title="Bold (Ctrl+B)"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg></button>
          <button type="button" class="editor-btn" data-cmd="italic" title="Italic (Ctrl+I)"><svg viewBox="0 0 24 24"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg></button>
          <button type="button" class="editor-btn" data-cmd="underline" title="Underline (Ctrl+U)"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3v7a6 6 0 006 6 6 6 0 006-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg></button>
          <button type="button" class="editor-btn" data-cmd="strikeThrough" title="Strikethrough"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.3 12H3m3.6-5s.9-2 3.4-2c2.3 0 3.4 1.4 3.4 1.4"/><path stroke-linecap="round" stroke-linejoin="round" d="M10 17s.8 2 3.3 2c2.5 0 3.7-1.5 3.7-2.6 0-.4 0-.8-.2-1.4"/></svg></button>
          <div class="editor-divider"></div>
          <button type="button" class="editor-btn" data-cmd="h2" title="Heading 2" style="font-size:11px;width:36px;">H2</button>
          <button type="button" class="editor-btn" data-cmd="h3" title="Heading 3" style="font-size:11px;width:36px;">H3</button>
          <button type="button" class="editor-btn" data-cmd="p" title="Paragraph" style="font-size:11px;width:36px;">P</button>
          <div class="editor-divider"></div>
          <button type="button" class="editor-btn" data-cmd="insertUnorderedList" title="Bullet list"><svg viewBox="0 0 24 24"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><line x1="4" y1="6" x2="4.01" y2="6"/><line x1="4" y1="12" x2="4.01" y2="12"/><line x1="4" y1="18" x2="4.01" y2="18"/></svg></button>
          <button type="button" class="editor-btn" data-cmd="insertOrderedList" title="Numbered list"><svg viewBox="0 0 24 24"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h1v4"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 10h2"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg></button>
          <button type="button" class="editor-btn" data-cmd="blockquote" title="Blockquote"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1zm12 0c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/></svg></button>
          <div class="editor-divider"></div>
          <button type="button" class="editor-btn" data-cmd="createLink" title="Insert link"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg></button>
          <button type="button" class="editor-btn" data-cmd="removeFormat" title="Clear formatting"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L17.94 6M10.5 10.5L8 18M17.5 6.5L13 19"/></svg></button>
          <div class="editor-divider"></div>
          <button type="button" class="editor-btn" data-cmd="undo" title="Undo"><svg viewBox="0 0 24 24"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 00-4-4H4"/></svg></button>
          <button type="button" class="editor-btn" data-cmd="redo" title="Redo"><svg viewBox="0 0 24 24"><polyline points="15 14 20 9 15 4"/><path d="M4 20v-7a4 4 0 014-4h12"/></svg></button>
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
