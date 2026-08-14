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

@push('page_styles')
<style>
.back-btn{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 16px;background:var(--surface2);color:var(--text2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;font-weight:600;cursor:pointer;transition:all var(--ease);font-family:var(--font);text-decoration:none;}
.back-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.back-btn svg{width:13px;height:13px;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;max-width:900px;}
.card-head{display:flex;align-items:center;gap:10px;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:11.5px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-body{padding:22px;}
.field{margin-bottom:20px;}
.field:last-child{margin-bottom:0;}
.f-label{display:block;font-size:11.5px;font-weight:600;color:var(--text2);margin-bottom:7px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.f-label .req{color:var(--red);margin-left:2px;}
.f-input{width:100%;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color .2s,box-shadow .2s,background .2s;}
.f-input::placeholder{color:var(--text3);}
.f-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
.f-input.err{border-color:var(--red);}
.f-hint{font-size:11px;color:var(--text3);margin-top:5px;line-height:1.5;}
.f-error{font-size:11.5px;color:var(--red);margin-top:5px;font-family:var(--mono);}
textarea.f-input{resize:vertical;min-height:420px;line-height:1.7;font-family:var(--mono);font-size:12.5px;}
.submit-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 22px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:14px;font-weight:700;cursor:pointer;font-family:var(--mono);transition:opacity .2s,transform .15s;box-shadow:0 4px 18px rgba(37,99,235,.35);}
.submit-btn:hover{opacity:.88;transform:translateY(-1px);}
.submit-btn svg{width:15px;height:15px;}
.alert-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.22);color:#b91c1c;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;}
.alert-error svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}
[data-theme="dark"] .alert-error{color:#f87171;}
</style>
@endpush

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
