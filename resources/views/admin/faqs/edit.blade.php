@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush


@section('sidebar_faqs', 'active')
@section('page_title', 'Edit FAQ')
@section('page_subtitle', 'Update this frequently asked question')

@section('topbar_left')
  <x-button variant="secondary" href="{{ route('admin.faqs.index') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
    All FAQs
  </x-button>
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
    <x-button variant="primary" type="submit">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
      Save Changes
    </x-button>
    <x-button variant="secondary" href="{{ route('admin.faqs.index') }}">Cancel</x-button>
  </div>
</form>
@endsection
