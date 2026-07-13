@extends('layouts.admin')

@section('sidebar_faqs', 'active')
@section('page_title', 'Edit FAQ')
@section('page_subtitle', 'Update this frequently asked question')

@section('topbar_left')
  <a href="{{ route('admin.faqs.index') }}" class="back-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
    All FAQs
  </a>
@endsection

@push('page_styles')
<style>
.back-btn{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 16px;background:var(--surface2);color:var(--text2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;font-weight:600;cursor:pointer;transition:all var(--ease);font-family:var(--font);text-decoration:none;}
.back-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.back-btn svg{width:13px;height:13px;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;max-width:820px;}
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
textarea.f-input{resize:vertical;min-height:140px;line-height:1.6;}
.toggle-row{display:flex;align-items:center;justify-content:space-between;padding:4px 0;}
.toggle-lbl{font-size:13px;font-weight:600;color:var(--text);}
.toggle-sub{font-size:11.5px;color:var(--text3);margin-top:2px;}
.sw{position:relative;flex-shrink:0;}
.sw input{position:absolute;opacity:0;width:0;height:0;}
.sw label{display:block;width:46px;height:26px;border-radius:100px;background:var(--border2);cursor:pointer;position:relative;transition:background .2s;}
.sw label::after{content:'';position:absolute;width:20px;height:20px;border-radius:50%;background:#fff;top:3px;left:3px;transition:transform .25s cubic-bezier(.4,0,.2,1);box-shadow:0 1px 4px rgba(0,0,0,.2);}
.sw input:checked+label{background:var(--a);}
.sw input:checked+label::after{transform:translateX(20px);}
.submit-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 22px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:14px;font-weight:700;cursor:pointer;font-family:var(--mono);transition:opacity .2s,transform .15s;box-shadow:0 4px 18px rgba(37,99,235,.35);}
.submit-btn:hover{opacity:.88;transform:translateY(-1px);}
.submit-btn svg{width:15px;height:15px;}
.alert-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.22);color:#b91c1c;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;}
.alert-error svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}
[data-theme="dark"] .alert-error{color:#f87171;}
.grid-2{display:grid;grid-template-columns:1fr 160px;gap:20px;}
@media(max-width:640px){.grid-2{grid-template-columns:1fr;}}
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

<form method="POST" action="{{ route('admin.faqs.update', $faq->id) }}">
  @csrf @method('PUT')
  <div class="card">
    <div class="card-head">
      <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-2 2.25-3.5 4.772-3.5 2.771 0 5 2.462 5 5.5 0 1.845-.98 3.46-2.448 4.5M12 21v.01M9.5 16.5a9.5 9.5 0 01-3.5-7c0-3.866 3.134-7 7-7s7 3.134 7 7a9.46 9.46 0 01-2.5 6.5"/></svg></div>
      <span class="card-head-title">FAQ Details</span>
    </div>
    <div class="card-body">
      <div class="grid-2">
        <div class="field">
          <label class="f-label" for="category">Category <span class="req">*</span></label>
          <input id="category" name="category" type="text" value="{{ old('category',$faq->category) }}"
            class="f-input {{ $errors->has('category')?'err':'' }}" placeholder="e.g. Donations, Account" list="faqCategories" required>
          <datalist id="faqCategories">
            @foreach(['Getting Started','Donations','Campaign Management','Account & Support'] as $c)<option value="{{ $c }}">@endforeach
          </datalist>
          <p class="f-hint">Group FAQs so related questions display together on the public FAQ page.</p>
          @error('category')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="f-label" for="sort_order">Display Order</label>
          <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order',$faq->sort_order) }}"
            class="f-input {{ $errors->has('sort_order')?'err':'' }}">
          <p class="f-hint">Lower numbers appear first.</p>
          @error('sort_order')<p class="f-error">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="field">
        <label class="f-label" for="question">Question <span class="req">*</span></label>
        <input id="question" name="question" type="text" value="{{ old('question',$faq->question) }}"
          class="f-input {{ $errors->has('question')?'err':'' }}" placeholder="e.g. How do I make a donation?" required>
        @error('question')<p class="f-error">{{ $message }}</p>@enderror
      </div>

      <div class="field">
        <label class="f-label" for="answer">Answer <span class="req">*</span></label>
        <textarea id="answer" name="answer" class="f-input {{ $errors->has('answer')?'err':'' }}" placeholder="Provide a clear, helpful answer…" required>{{ old('answer',$faq->answer) }}</textarea>
        @error('answer')<p class="f-error">{{ $message }}</p>@enderror
      </div>

      <div class="field">
        <div class="toggle-row">
          <div>
            <div class="toggle-lbl">Active</div>
            <div class="toggle-sub">Show this FAQ on the public FAQ page</div>
          </div>
          <div class="sw">
            <input type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active',$faq->is_active)?'checked':'' }}>
            <label for="isActive"></label>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div style="margin-top:18px;display:flex;gap:10px;">
    <button type="submit" class="submit-btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
      Save Changes
    </button>
    <a href="{{ route('admin.faqs.index') }}" class="back-btn" style="text-decoration:none;">Cancel</a>
  </div>
</form>
@endsection
