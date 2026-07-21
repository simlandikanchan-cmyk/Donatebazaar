@extends('layouts.admin')

@section('sidebar_blogs', 'active')
@section('page_title', 'Edit Blog Post')
@section('page_subtitle', 'Update content, status, and settings')

@push('page_styles')
<style>
/* ── edit page-specific ── */
.breadcrumb{display:flex;align-items:center;gap:6px;font-size:11.5px;color:var(--text3);font-family:var(--mono);margin-bottom:20px;animation:fadeUp .3s both;}
.breadcrumb a{color:var(--text3);transition:color var(--ease);}
.breadcrumb a:hover{color:var(--a);}
.breadcrumb svg{width:10px;height:10px;stroke:var(--text3);fill:none;stroke-width:2;flex-shrink:0;}
.breadcrumb span{color:var(--text2);}
.flash-success{background:rgba(5,196,138,.09);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:11px 14px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.flash-error  {background:rgba(240,68,68,.09);border:1px solid rgba(240,68,68,.25);color:#991b1b;padding:11px 14px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
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
.btn-danger{background:rgba(240,68,68,.10);color:var(--red);border:1px solid rgba(240,68,68,.22);}
.btn-danger:hover{background:rgba(240,68,68,.18);}
[data-theme="dark"] .btn-danger{color:#f87171;}
.edit-layout{display:grid;grid-template-columns:1fr 290px;gap:20px;align-items:start;}
@media(max-width:1100px){.edit-layout{grid-template-columns:1fr;}}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s both;}
.card:nth-child(1){animation-delay:.05s;}.card:nth-child(2){animation-delay:.10s;}.card:nth-child(3){animation-delay:.12s;}.card:nth-child(4){animation-delay:.14s;}.card:nth-child(5){animation-delay:.16s;}.card:nth-child(6){animation-delay:.18s;}
.card-header{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid var(--border);background:var(--surface2);}
.card-title{display:flex;align-items:center;gap:8px;font-family:var(--font);font-size:11px;font-weight:700;color:var(--text);text-transform:uppercase;letter-spacing:.06em;}
.card-title-icon{width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;background:var(--a-lt);}
.card-title-icon svg{width:13px;height:13px;stroke:var(--a);fill:none;stroke-width:2;}
.card-body{padding:20px 18px;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
@media(max-width:700px){.form-row{grid-template-columns:1fr;}}
.form-group{display:flex;flex-direction:column;gap:5px;margin-bottom:16px;}
.form-group:last-child{margin-bottom:0;}
.form-label{font-size:10px;font-weight:700;color:var(--text2);font-family:var(--mono);letter-spacing:.08em;text-transform:uppercase;display:flex;align-items:center;gap:5px;}
.form-label .req{color:var(--red);font-size:13px;}
.form-input,.form-select,.form-textarea{width:100%;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:9px 12px;font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease),background var(--ease);}
.form-input::placeholder,.form-textarea::placeholder{color:var(--text3);}
.form-input:focus,.form-select:focus,.form-textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
.form-input.error,.form-textarea.error{border-color:var(--red);box-shadow:0 0 0 3px rgba(240,68,68,.12);}
.form-textarea{resize:vertical;min-height:100px;line-height:1.65;}
.form-select{appearance:none;cursor:pointer;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;padding-right:32px;}
.form-hint{font-size:11px;color:var(--text3);margin-top:3px;line-height:1.5;}
.form-error{font-size:11px;color:var(--red);margin-top:3px;display:flex;align-items:center;gap:4px;}
.form-error svg{width:11px;height:11px;flex-shrink:0;stroke:var(--red);fill:none;stroke-width:2;}
.char-counter{font-size:11px;color:var(--text3);font-family:var(--mono);margin-left:auto;}
.slug-wrap{position:relative;}
.slug-prefix{position:absolute;left:11px;top:50%;transform:translateY(-50%);font-size:12px;color:var(--text3);font-family:var(--mono);pointer-events:none;}
.slug-input{padding-left:70px !important;}
.editor-toolbar{display:flex;flex-wrap:wrap;gap:3px;padding:8px 10px;border-bottom:1px solid var(--border);background:var(--surface2);}
.editor-btn{width:30px;height:28px;border-radius:6px;border:none;background:transparent;color:var(--text2);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;transition:background var(--ease),color var(--ease);cursor:pointer;font-family:var(--font);}
.editor-btn:hover{background:var(--a-lt);color:var(--a);}
.editor-btn svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;}
.editor-divider{width:1px;height:24px;background:var(--border2);margin:0 3px;align-self:center;}
.editor-content{min-height:320px;padding:16px;font-size:14px;line-height:1.75;color:var(--text);font-family:var(--font);outline:none;border:none;background:var(--surface);}
.editor-content:focus{outline:none;}
.editor-footer{display:flex;align-items:center;justify-content:space-between;padding:8px 12px;border-top:1px solid var(--border);background:var(--surface2);}
.editor-footer span{font-size:11px;color:var(--text3);font-family:var(--mono);}
.cover-drop{border:2px dashed var(--border2);border-radius:var(--r-sm);background:var(--surface2);padding:28px 20px;text-align:center;cursor:pointer;transition:border-color var(--ease),background var(--ease);position:relative;overflow:hidden;}
.cover-drop:hover,.cover-drop.drag-over{border-color:var(--a);background:rgba(37,99,235,.04);}
.cover-drop input[type="file"]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}
.cover-drop-icon{width:44px;height:44px;border-radius:12px;background:var(--a-lt);border:1px solid rgba(37,99,235,.2);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
.cover-drop-icon svg{width:20px;height:20px;stroke:var(--a);fill:none;stroke-width:1.5;}
.cover-drop p{font-size:13px;font-weight:500;color:var(--text2);margin-bottom:4px;}
.cover-drop span{font-size:11.5px;color:var(--text3);}
.cover-preview-wrap{position:relative;border-radius:var(--r-sm);overflow:hidden;}
.cover-preview-wrap img{width:100%;height:180px;object-fit:cover;display:block;border-radius:var(--r-sm);}
.cover-preview-actions{position:absolute;top:8px;right:8px;display:flex;gap:6px;}
.cover-preview-btn{width:30px;height:30px;border-radius:7px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:opacity var(--ease);}
.cover-preview-btn:hover{opacity:.85;}
.cover-preview-btn svg{width:13px;height:13px;}
.cpb-remove{background:rgba(240,68,68,.9);color:#fff;}
.cpb-change{background:rgba(255,255,255,.92);color:var(--text);}
.status-option{display:flex;align-items:center;gap:8px;padding:9px 12px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);cursor:pointer;transition:border-color var(--ease),background var(--ease);margin-bottom:8px;}
.status-option:last-child{margin-bottom:0;}
.status-option.selected{border-color:var(--a);background:var(--a-lt);}
.status-option input[type="radio"]{display:none;}
.status-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.sd-approved{background:var(--green);}.sd-pending{background:var(--amber);}
.sd-rejected{background:var(--red);}.sd-draft{background:var(--text3);}
.status-option-label{font-size:12.5px;font-weight:600;color:var(--text);}
.status-option-desc{font-size:11px;color:var(--text3);margin-top:1px;}
.tags-input-wrap{display:flex;flex-wrap:wrap;gap:6px;padding:8px 10px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);min-height:42px;cursor:text;transition:border-color var(--ease),box-shadow var(--ease);}
.tags-input-wrap:focus-within{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.tag-chip{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:100px;background:var(--a-lt);border:1px solid rgba(37,99,235,.25);color:var(--a);font-size:11.5px;font-weight:500;font-family:var(--mono);}
.tag-chip button{background:none;border:none;color:var(--a);font-size:13px;cursor:pointer;line-height:1;padding:0;display:flex;align-items:center;transition:opacity var(--ease);}
.tag-chip button:hover{opacity:.7;}
.tags-real-input{border:none;background:transparent;outline:none;font-size:12.5px;color:var(--text);font-family:var(--font);min-width:100px;flex:1;}
.tags-real-input::placeholder{color:var(--text3);}
.seo-score-bar{height:6px;background:var(--surface2);border-radius:100px;overflow:hidden;margin-bottom:6px;}
.seo-score-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--a),var(--a2));transition:width .5s cubic-bezier(.4,0,.2,1);}
.seo-checks{display:flex;flex-direction:column;gap:6px;}
.seo-check{display:flex;align-items:center;gap:7px;font-size:12px;color:var(--text2);}
.seo-check svg{width:13px;height:13px;flex-shrink:0;}
.seo-check.pass svg{stroke:var(--green);fill:none;stroke-width:2.5;}
.seo-check.fail svg{stroke:var(--text3);fill:none;stroke-width:2;}
.meta-info{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:2px 14px;}
.meta-row{display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid var(--border);}
.meta-row:last-child{border-bottom:none;}
.meta-key{font-size:10px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.meta-val{font-size:12px;font-weight:500;color:var(--text2);}
.b-pending {background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.3);}
.b-approved{background:var(--green-lt);color:#065f46;border:1px solid rgba(5,196,138,.3);}
.b-rejected{background:var(--red-lt);color:#991b1b;border:1px solid rgba(240,68,68,.3);}
.b-draft   {background:rgba(156,163,175,.15);color:#6b7280;border:1px solid rgba(156,163,175,.3);}
[data-theme="dark"] .b-pending{color:var(--amber);}[data-theme="dark"] .b-approved{color:#34d399;}
[data-theme="dark"] .b-rejected{color:#f87171;}[data-theme="dark"] .b-draft{color:#9ca3af;}
@media(max-width:600px){.page-header{flex-direction:column;align-items:flex-start;}}
@media(max-width:480px){.edit-layout{gap:14px}.card-body{padding:14px 12px}.form-row{gap:12px}.form-input,.form-select,.form-textarea{font-size:12px;padding:8px 11px}.card-header{padding:12px 14px}.page-header-left h2{font-size:16px}.page-header-right{width:100%}.page-header-right .btn{flex:1;justify-content:center}}
@media(max-width:380px){.card-body{padding:12px 10px}.form-label{font-size:9px}.form-input,.form-select,.form-textarea{font-size:11px;padding:7px 10px}.form-row{grid-template-columns:1fr}.card-title{font-size:10px}.page-header-left h2{font-size:14px}.page-header-right{flex-direction:column;gap:6px}.page-header-right .btn{width:100%}.meta-row{padding:7px 0}.meta-val{font-size:11px}.editor-toolbar{gap:2px;padding:6px 8px}.editor-btn{width:26px;height:24px;font-size:11px}.slug-prefix{font-size:10px}.slug-input{padding-left:60px!important}}
</style>
@endpush
@section('content')
<div class="breadcrumb">
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
  <a href="{{ route('admin.blogs.index') }}">Blogs</a>
  <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Edit Post</span>
</div>

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

<div class="page-header">
  <div class="page-header-left">
    <h2>Edit: "{{ Str::limit($blog->title ?? 'Blog Post', 48) }}"</h2>
    <p>Post ID #{{ $blog->id ?? '—' }} · Last updated {{ $blog->updated_at?->diffForHumans() ?? 'recently' }}</p>
  </div>
  <div class="page-header-right">
    <a href="{{ route('blogs.show', $blog->slug ?? $blog->id) }}" target="_blank" class="btn btn-ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
      Preview
    </a>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
    <button type="submit" form="editForm" class="btn btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Save Changes
    </button>
  </div>
</div>

<form id="editForm"
      method="POST"
      action="{{ route('admin.blogs.update', $blog) }}"
      enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="edit-layout">

    <div>
      <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            Post Details
          </div>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label" for="title">
              Title <span class="req">*</span>
              <span class="char-counter" id="titleCounter">0 / 100</span>
            </label>
            <input type="text" id="title" name="title"
              class="form-input {{ $errors->has('title') ? 'error' : '' }}"
              value="{{ old('title', $blog->title ?? '') }}"
              placeholder="Enter a compelling post title…"
              maxlength="100" required autocomplete="off">
            @error('title')
            <span class="form-error">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              {{ $message }}
            </span>
            @enderror
          </div>
          <div class="form-group">
            <label class="form-label" for="slug">URL Slug</label>
            <div class="slug-wrap">
              <span class="slug-prefix">/blog/</span>
              <input type="text" id="slug" name="slug"
                class="form-input slug-input {{ $errors->has('slug') ? 'error' : '' }}"
                value="{{ old('slug', $blog->slug ?? '') }}"
                placeholder="auto-generated-from-title"
                autocomplete="off">
            </div>
            <span class="form-hint">Leave blank to auto-generate. Lowercase letters, numbers, hyphens only.</span>
            @error('slug')
            <span class="form-error"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
            @enderror
          </div>
          <div class="form-row">
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label" for="category_id">Category</label>
              <select id="category_id" name="category_id" class="form-select">
                <option value="">Select category…</option>
                @foreach($categories ?? [] as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $blog->category_id ?? '') == $cat->id)>
                  {{ $cat->name }}
                </option>
                @endforeach
              </select>
              @error('category_id')
              <span class="form-error"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label" for="read_time_minutes">Read Time (min)</label>
              <input type="number" id="read_time_minutes" name="read_time_minutes"
                min="1" max="60"
                class="form-input {{ $errors->has('read_time_minutes') ? 'error' : '' }}"
                value="{{ old('read_time_minutes', $blog->read_time_minutes ?? 1) }}">
              @error('read_time_minutes')
              <span class="form-error"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
              @enderror
            </div>
          </div>
        </div>
      </div>

      <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10"/></svg>
            </div>
            Excerpt
          </div>
          <span id="excerptCounter" style="font-size:11px;color:var(--text3);font-family:var(--mono);">0 / 200</span>
        </div>
        <div class="card-body">
          <div class="form-group" style="margin-bottom:0;">
            <textarea id="excerpt" name="excerpt"
              class="form-textarea {{ $errors->has('excerpt') ? 'error' : '' }}"
              placeholder="A short compelling summary shown in cards and previews…"
              maxlength="200" rows="3">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
            <span class="form-hint">Shown on blog listing cards. Keep under 160 chars for best SEO.</span>
            @error('excerpt')
            <span class="form-error"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
            @enderror
          </div>
        </div>
      </div>

      <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            Content
          </div>
        </div>
        <div class="editor-toolbar">
          <button type="button" class="editor-btn" data-cmd="bold" title="Bold"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg></button>
          <button type="button" class="editor-btn" data-cmd="italic" title="Italic"><svg viewBox="0 0 24 24"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg></button>
          <button type="button" class="editor-btn" data-cmd="underline" title="Underline"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3v7a6 6 0 006 6 6 6 0 006-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg></button>
          <button type="button" class="editor-btn" data-cmd="strikeThrough" title="Strikethrough"><svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.3 12H3m3.6-5s.9-2 3.4-2c2.3 0 3.4 1.4 3.4 1.4"/><path stroke-linecap="round" stroke-linejoin="round" d="M10 17s.8 2 3.3 2c2.5 0 3.7-1.5 3.7-2.6 0-.4 0-.8-.2-1.4"/></svg></button>
          <div class="editor-divider"></div>
          <button type="button" class="editor-btn" data-cmd="h2" title="Heading 2" style="font-size:11px;width:36px;">H2</button>
          <button type="button" class="editor-btn" data-cmd="h3" title="Heading 3" style="font-size:11px;width:36px;">H3</button>
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
        <div id="editor" class="editor-content" contenteditable="true" spellcheck="true">{!! old('content', $blog->content ?? '') !!}</div>
        <input type="hidden" name="content" id="contentInput">
        <div class="editor-footer">
          <span id="wordCount">0 words</span>
          <span id="charCount">0 characters</span>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </div>
            SEO &amp; Meta
          </div>
          <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--text3);font-family:var(--mono);">
            Score <span id="seoScoreVal" style="color:var(--a);font-weight:700;">0%</span>
          </div>
        </div>
        <div class="card-body">
          <div class="seo-score-bar" style="margin-bottom:14px;">
            <div class="seo-score-fill" id="seoFill" style="width:0%"></div>
          </div>
          <div class="seo-checks" style="margin-bottom:20px;">
            <div class="seo-check fail" id="chkTitle"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> Title is 40–65 characters</div>
            <div class="seo-check fail" id="chkExcerpt"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> Excerpt is under 160 characters</div>
            <div class="seo-check fail" id="chkSlug"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> URL slug is set</div>
            <div class="seo-check fail" id="chkContent"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> Content is at least 300 words</div>
            <div class="seo-check fail" id="chkImage"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> Cover image uploaded</div>
          </div>
          <div class="form-group">
            <label class="form-label" for="meta_title">
              Meta Title
              <span class="char-counter" id="metaTitleCounter">0 / 65</span>
            </label>
            <input type="text" id="meta_title" name="meta_title"
              class="form-input"
              value="{{ old('meta_title', $blog->meta_title ?? '') }}"
              placeholder="Leave blank to use post title…" maxlength="65">
            <span class="form-hint">Appears in browser tab and search results (40–65 chars ideal).</span>
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label" for="meta_description">
              Meta Description
              <span class="char-counter" id="metaDescCounter">0 / 160</span>
            </label>
            <textarea id="meta_description" name="meta_description"
              class="form-textarea" rows="2" maxlength="160"
              placeholder="Leave blank to use excerpt…">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
            <span class="form-hint">Shown in Google results. Aim for 120–160 characters.</span>
          </div>
        </div>
      </div>
    </div>

    <div>
      <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            Status
          </div>
          <span class="badge b-{{ $blog->status ?? 'draft' }}">
            <span class="badge-dot"></span>{{ ucfirst($blog->status ?? 'draft') }}
          </span>
        </div>
        <div class="card-body">
          @php $curStatus = old('status', $blog->status ?? 'draft'); @endphp

          <label class="status-option {{ $curStatus === 'approved' ? 'selected' : '' }}">
            <input type="radio" name="status" value="approved" {{ $curStatus === 'approved' ? 'checked' : '' }}>
            <span class="status-dot sd-approved"></span>
            <div><div class="status-option-label">Approved</div><div class="status-option-desc">Visible to all visitors</div></div>
          </label>
          <label class="status-option {{ $curStatus === 'pending' ? 'selected' : '' }}">
            <input type="radio" name="status" value="pending" {{ $curStatus === 'pending' ? 'checked' : '' }}>
            <span class="status-dot sd-pending"></span>
            <div><div class="status-option-label">Pending Review</div><div class="status-option-desc">Awaiting admin approval</div></div>
          </label>
          <label class="status-option {{ $curStatus === 'rejected' ? 'selected' : '' }}">
            <input type="radio" name="status" value="rejected" {{ $curStatus === 'rejected' ? 'checked' : '' }}>
            <span class="status-dot sd-rejected"></span>
            <div><div class="status-option-label">Rejected</div><div class="status-option-desc">Hidden, author notified</div></div>
          </label>
          <label class="status-option {{ $curStatus === 'draft' ? 'selected' : '' }}">
            <input type="radio" name="status" value="draft" {{ $curStatus === 'draft' ? 'checked' : '' }}>
            <span class="status-dot sd-draft"></span>
            <div><div class="status-option-label">Draft</div><div class="status-option-desc">Only visible to editors</div></div>
          </label>

          <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border);display:flex;gap:8px;">
            <button type="submit" form="editForm" class="btn btn-primary">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Save
            </button>
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-ghost">Cancel</a>
          </div>
        </div>
      </div>

      <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            Options
          </div>
        </div>
        <div class="card-body">
          @php $isFeatured = old('is_featured', $blog->is_featured ?? false); @endphp
          <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);">
            <div>
              <div style="font-size:13px;font-weight:500;color:var(--text);">Featured Post</div>
              <div style="font-size:11px;color:var(--text3);">Show in featured strip</div>
            </div>
            <div style="position:relative;display:inline-block;width:40px;height:22px;">
              <input type="hidden" name="is_featured" value="0">
              <input type="checkbox" name="is_featured" value="1" id="isFeatured"
                {{ $isFeatured ? 'checked' : '' }}
                style="opacity:0;width:0;height:0;position:absolute;">
              <span id="toggleTrack"
                onclick="toggleSwitch('isFeatured','toggleTrack','toggleThumb')"
                style="position:absolute;inset:0;background:{{ $isFeatured ? 'var(--a)' : 'var(--border2)' }};border-radius:100px;cursor:pointer;transition:background .2s;">
                <span id="toggleThumb" style="position:absolute;left:{{ $isFeatured ? '20px' : '2px' }};top:2px;width:18px;height:18px;border-radius:50%;background:#fff;transition:left .2s;box-shadow:0 1px 4px rgba(0,0,0,.2);"></span>
              </span>
            </div>
          </div>

          @php $allowComments = old('allow_comments', $blog->allow_comments ?? true); @endphp
          <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;">
            <div>
              <div style="font-size:13px;font-weight:500;color:var(--text);">Allow Comments</div>
              <div style="font-size:11px;color:var(--text3);">Readers can leave comments</div>
            </div>
            <div style="position:relative;display:inline-block;width:40px;height:22px;">
              <input type="hidden" name="allow_comments" value="0">
              <input type="checkbox" name="allow_comments" value="1" id="allowComments"
                {{ $allowComments ? 'checked' : '' }}
                style="opacity:0;width:0;height:0;position:absolute;">
              <span id="toggleTrack2"
                onclick="toggleSwitch('allowComments','toggleTrack2','toggleThumb2')"
                style="position:absolute;inset:0;background:{{ $allowComments ? 'var(--a)' : 'var(--border2)' }};border-radius:100px;cursor:pointer;transition:background .2s;">
                <span id="toggleThumb2" style="position:absolute;left:{{ $allowComments ? '20px' : '2px' }};top:2px;width:18px;height:18px;border-radius:50%;background:#fff;transition:left .2s;box-shadow:0 1px 4px rgba(0,0,0,.2);"></span>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            Cover Image
          </div>
        </div>
        <div class="card-body">
          <div id="coverPreviewWrap" style="{{ $blog->cover_image ? '' : 'display:none;' }}">
            <div class="cover-preview-wrap" style="margin-bottom:10px;">
              <img id="coverPreview"
                src="{{ $blog->cover_image ? asset('storage/'.$blog->cover_image) : '' }}"
                alt="Cover">
              <div class="cover-preview-actions">
                <button type="button" class="btn btn-red cover-preview-btn cpb-remove" onclick="removeCover()" title="Remove">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <button type="button" class="cover-preview-btn cpb-change" onclick="document.getElementById('coverInput').click()" title="Change">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                </button>
              </div>
            </div>
          </div>

          <div id="coverDropzone" class="cover-drop" style="{{ $blog->cover_image ? 'display:none;' : '' }}">
            <input type="file" id="coverInput" name="cover_image" accept="image/*" onchange="previewCover(this)">
            <div class="cover-drop-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p>Drop image here or click to upload</p>
            <span>PNG, JPG, WebP — max 5MB</span>
          </div>

          <input type="hidden" name="remove_cover" id="removeCoverFlag" value="0">

          @error('cover_image')
          <span class="form-error" style="margin-top:6px;">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $message }}
          </span>
          @enderror
        </div>
      </div>

      <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
            </div>
            Tags
          </div>
        </div>
        <div class="card-body">
          @php
            $oldTags = old('tags');
            if ($oldTags !== null) {
              $tagNames = array_values(array_filter(array_map('trim', explode(',', $oldTags))));
            } else {
              $tagNames = $blog->tags->pluck('name')->map(fn($n) => trim($n))->filter()->values()->toArray();
            }
            $tagsHiddenValue = implode(',', $tagNames);
          @endphp

          <div class="tags-input-wrap" id="tagsWrap" onclick="document.getElementById('tagInput').focus()">
            @foreach($tagNames as $tagName)
            <span class="tag-chip" data-tag="{{ $tagName }}">
              {{ $tagName }}
              <button type="button" onclick="removeTag(this.parentElement)">×</button>
            </span>
            @endforeach
            <input type="text" id="tagInput" class="tags-real-input" placeholder="Add tag, press Enter…" autocomplete="off">
          </div>
          <input type="hidden" name="tags" id="tagsHidden" value="{{ $tagsHiddenValue }}">
          <span class="form-hint" style="margin-top:6px;">Press Enter or comma to add a tag.</span>
        </div>
      </div>

      <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
          <div class="card-title">
            <div class="card-title-icon">
              <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            Post Info
          </div>
        </div>
        <div class="card-body" style="padding:14px 16px;">
          <div class="meta-info">
            <div class="meta-row">
              <span class="meta-key">ID</span>
              <span class="meta-val">#{{ $blog->id ?? '—' }}</span>
            </div>
            <div class="meta-row">
              <span class="meta-key">Author</span>
              <span class="meta-val">{{ $blog->author->name ?? '—' }}</span>
            </div>
            <div class="meta-row">
              <span class="meta-key">Created</span>
              <span class="meta-val">{{ $blog->created_at?->format('d M Y') ?? '—' }}</span>
            </div>
            <div class="meta-row">
              <span class="meta-key">Updated</span>
              <span class="meta-val">{{ $blog->updated_at?->diffForHumans() ?? '—' }}</span>
            </div>
            <div class="meta-row">
              <span class="meta-key">Views</span>
              <span class="meta-val">{{ number_format($blog->views_count ?? 0) }}</span>
            </div>
            <div class="meta-row">
              <span class="meta-key">Likes</span>
              <span class="meta-val">{{ number_format($blog->likes_count ?? 0) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</form>

<div style="margin-top:20px;max-width:290px;margin-left:auto;">
  <div class="card" style="border-color:rgba(240,68,68,.25);">
    <div class="card-header" style="background:rgba(240,68,68,.04);">
      <div class="card-title" style="color:var(--red);">
        <div class="card-title-icon" style="background:rgba(240,68,68,.12);">
          <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        Danger Zone
      </div>
    </div>
    <div class="card-body">
      <p style="font-size:12.5px;color:var(--text2);margin-bottom:12px;line-height:1.6;">
        Soft-delete this post. It can be restored later from the admin panel.
      </p>
      <form method="POST"
            action="{{ route('admin.blogs.destroy', $blog) }}"
            onsubmit="return confirm('Delete \'{{ addslashes($blog->title ?? '') }}\'?\nThis will soft-delete the post.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-red">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete Post
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

@push('page_scripts')
<script>
(function () {
'use strict';

/* ── RICH TEXT EDITOR ── */
var editor       = document.getElementById('editor');
var contentInput = document.getElementById('contentInput');

document.querySelectorAll('.editor-btn[data-cmd]').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var cmd = this.dataset.cmd;
    if      (cmd === 'h2')         { document.execCommand('formatBlock', false, 'h2'); }
    else if (cmd === 'h3')         { document.execCommand('formatBlock', false, 'h3'); }
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

function updateCounts() {
  var text  = editor.innerText || '';
  var words = text.trim() ? text.trim().split(/\s+/).length : 0;
  document.getElementById('wordCount').textContent = words + ' word' + (words !== 1 ? 's' : '');
  document.getElementById('charCount').textContent = text.length + ' character' + (text.length !== 1 ? 's' : '');
  contentInput.value = editor.innerHTML;
  updateSEO();
}
editor.addEventListener('input', updateCounts);
updateCounts();

/* ── TITLE COUNTER + AUTO-SLUG ── */
var titleInput = document.getElementById('title');
var slugInput  = document.getElementById('slug');
if (slugInput.value.trim()) { slugInput.dataset.manual = '1'; }

function updateTitleCounter() {
  var len = titleInput.value.length;
  document.getElementById('titleCounter').textContent = len + ' / 100';
  if (!slugInput.dataset.manual) {
    slugInput.value = titleInput.value.toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-')
      .replace(/-+/g, '-').replace(/^-|-$/g, '');
  }
  updateSEO();
}
titleInput.addEventListener('input', updateTitleCounter);
slugInput.addEventListener('input', function () { this.dataset.manual = '1'; });
updateTitleCounter();

/* ── EXCERPT COUNTER ── */
var excerptEl = document.getElementById('excerpt');
function updateExcerptCounter() {
  document.getElementById('excerptCounter').textContent = excerptEl.value.length + ' / 200';
  updateSEO();
}
excerptEl.addEventListener('input', updateExcerptCounter);
updateExcerptCounter();

/* ── META COUNTERS ── */
[['meta_title','metaTitleCounter',65],['meta_description','metaDescCounter',160]].forEach(function (cfg) {
  var el = document.getElementById(cfg[0]);
  var counter = document.getElementById(cfg[1]);
  var max = cfg[2];
  function upd() {
    var l = el.value.length;
    counter.textContent = l + ' / ' + max;
    counter.style.color = l > max * 0.9 ? 'var(--red)' : 'var(--text3)';
  }
  el.addEventListener('input', upd); upd();
});

/* ── SEO CHECKER ── */
function updateSEO() {
  var titleLen   = (document.getElementById('title').value || '').length;
  var excerptLen = (document.getElementById('excerpt').value || '').length;
  var slugVal    = (document.getElementById('slug').value || '').trim();
  var wordCount  = (editor.innerText || '').trim().split(/\s+/).filter(Boolean).length;
  var imgSrc     = document.getElementById('coverPreview').src;
  var hasImage   = imgSrc && imgSrc !== '' && imgSrc !== window.location.href;

  var checks = [
    { id:'chkTitle',   pass: titleLen >= 40 && titleLen <= 65,   label:'Title is 40–65 characters' },
    { id:'chkExcerpt', pass: excerptLen > 0 && excerptLen <= 160, label:'Excerpt is under 160 characters' },
    { id:'chkSlug',    pass: slugVal.length > 0,                  label:'URL slug is set' },
    { id:'chkContent', pass: wordCount >= 300,                    label:'Content is at least 300 words' },
    { id:'chkImage',   pass: !!hasImage,                          label:'Cover image uploaded' },
  ];

  var score = 0;
  checks.forEach(function (c) {
    var el = document.getElementById(c.id);
    if (!el) return;
    if (c.pass) {
      el.className = 'seo-check pass';
      el.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> ' + c.label;
      score++;
    } else {
      el.className = 'seo-check fail';
      el.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> ' + c.label;
    }
  });

  var pct  = Math.round((score / checks.length) * 100);
  var fill = document.getElementById('seoFill');
  fill.style.width      = pct + '%';
  fill.style.background = pct >= 80 ? 'linear-gradient(90deg,#059669,#05c48a)' :
                          pct >= 50 ? 'linear-gradient(90deg,var(--a),var(--a2))' :
                                      'linear-gradient(90deg,#dc2626,#f04444)';
  document.getElementById('seoScoreVal').textContent = pct + '%';
}
updateSEO();

/* ── COVER IMAGE PREVIEW ── */
window.previewCover = function (input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function (e) {
    document.getElementById('coverPreview').src                = e.target.result;
    document.getElementById('coverPreviewWrap').style.display  = '';
    document.getElementById('coverDropzone').style.display     = 'none';
    document.getElementById('removeCoverFlag').value           = '0';
    updateSEO();
  };
  reader.readAsDataURL(input.files[0]);
};

window.removeCover = function () {
  document.getElementById('coverPreview').src                = '';
  document.getElementById('coverPreviewWrap').style.display  = 'none';
  document.getElementById('coverDropzone').style.display     = '';
  document.getElementById('coverInput').value                = '';
  document.getElementById('removeCoverFlag').value           = '1';
  updateSEO();
};

var dz = document.getElementById('coverDropzone');
if (dz) {
  dz.addEventListener('dragover',  function (e) { e.preventDefault(); this.classList.add('drag-over'); });
  dz.addEventListener('dragleave', function ()  { this.classList.remove('drag-over'); });
  dz.addEventListener('drop', function (e) {
    e.preventDefault(); this.classList.remove('drag-over');
    if (e.dataTransfer.files[0]) {
      document.getElementById('coverInput').files = e.dataTransfer.files;
      previewCover(document.getElementById('coverInput'));
    }
  });
}

/* ── TAGS CHIP INPUT ── */
function syncTags() {
  var chips = document.querySelectorAll('#tagsWrap .tag-chip');
  var vals  = Array.from(chips).map(function (c) { return c.dataset.tag; });
  document.getElementById('tagsHidden').value = vals.join(',');
}
window.removeTag = function (chip) { chip.remove(); syncTags(); };

var tagInput = document.getElementById('tagInput');
tagInput.addEventListener('keydown', function (e) {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    var val = this.value.replace(/,/g, '').trim().toLowerCase();
    if (!val) return;
    var exists = Array.from(document.querySelectorAll('#tagsWrap .tag-chip'))
                      .some(function (c) { return c.dataset.tag === val; });
    if (!exists) {
      var chip         = document.createElement('span');
      chip.className   = 'tag-chip';
      chip.dataset.tag = val;
      chip.innerHTML   = val + '<button type="button" onclick="removeTag(this.parentElement)">×</button>';
      document.getElementById('tagsWrap').insertBefore(chip, tagInput);
      syncTags();
    }
    this.value = '';
  }
  if (e.key === 'Backspace' && !this.value) {
    var chips = document.querySelectorAll('#tagsWrap .tag-chip');
    if (chips.length) { chips[chips.length - 1].remove(); syncTags(); }
  }
});

/* ── STATUS RADIO HIGHLIGHT ── */
document.querySelectorAll('.status-option input[type="radio"]').forEach(function (radio) {
  radio.addEventListener('change', function () {
    document.querySelectorAll('.status-option').forEach(function (o) { o.classList.remove('selected'); });
    this.closest('.status-option').classList.add('selected');
  });
});

/* ── TOGGLE SWITCHES ── */
window.toggleSwitch = function (inputId, trackId, thumbId) {
  var cb     = document.getElementById(inputId);
  cb.checked = !cb.checked;
  var track  = document.getElementById(trackId);
  var thumb  = document.getElementById(thumbId);
  track.style.background = cb.checked ? 'var(--a)' : 'var(--border2)';
  thumb.style.left        = cb.checked ? '20px' : '2px';
};

/* ── SYNC CONTENT ON SUBMIT ── */
document.getElementById('editForm').addEventListener('submit', function () {
  contentInput.value = editor.innerHTML;
});

})();
</script>
@endpush
