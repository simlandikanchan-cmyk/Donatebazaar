@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush


@section('sidebar_faqs', 'active')
@section('page_title', 'FAQ')
@section('page_subtitle', 'Manage frequently asked questions')

@section('topbar_left')
  <x-button variant="primary" href="{{ route('admin.faqs.create') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    Add FAQ
  </x-button>
@endsection

@section('content')
<div class="toolbar">
  <form method="GET" action="{{ route('admin.faqs.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
    <div class="search-wrap">
      <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="search-input" name="search" value="{{ request('search') }}" placeholder="Search questions…">
    </div>
    <select class="filter-btn" name="category" onchange="this.form.submit()">
      <option value="">All categories</option>
      @foreach($categories as $c)
        <option value="{{ $c }}" {{ request('category')===$c?'selected':'' }}>{{ $c }}</option>
      @endforeach
    </select>
    <x-button variant="secondary" type="submit" class="filter-btn">Filter</x-button>
    @if(request('search') || request('category'))
      <x-button variant="secondary" href="{{ route('admin.faqs.index') }}" class="filter-btn">Clear</x-button>
    @endif
  </form>
</div>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-2 2.25-3.5 4.772-3.5 2.771 0 5 2.462 5 5.5 0 1.845-.98 3.46-2.448 4.5M12 21v.01M9.5 16.5a9.5 9.5 0 01-3.5-7c0-3.866 3.134-7 7-7s7 3.134 7 7a9.46 9.46 0 01-2.5 6.5"/></svg></div>
    <span class="card-head-title">All FAQs</span>
    <span class="card-head-count">{{ $faqs->total() }} total · {{ $stats['active'] }} active</span>
  </div>

  @if($faqs->isEmpty())
    <div class="empty-state">
      <div class="empty-icon-wrap">❓</div>
      <h3>No FAQs found</h3>
      <p>Create your first frequently asked question.</p>
      <x-button variant="primary" href="{{ route('admin.faqs.create') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Add FAQ
      </x-button>
    </div>
  @else
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:50px;">#</th>
            <th>Category</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Order</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($faqs as $faq)
          <tr>
            <td><span style="font-size:11.5px;color:var(--text3);font-family:var(--mono);">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
            <td><span class="cat-pill">{{ $faq->category }}</span></td>
            <td><div class="q-text">{{ $faq->question }}</div></td>
            <td><div class="a-text">{{ strip_tags($faq->answer) }}</div></td>
            <td><span style="font-family:var(--mono);font-size:12px;color:var(--text3);">{{ $faq->sort_order }}</span></td>
            <td>
              <span class="status-pill {{ $faq->is_active?'s-active':'s-inactive' }}">
                <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                {{ $faq->is_active?'Active':'Hidden' }}
              </span>
            </td>
            <td>
              <div class="actions">
                <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-secondary act-btn act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
                <form method="POST" action="{{ route('admin.faqs.destroy', $faq->id) }}" onsubmit="return confirm('Delete this FAQ?');">
                  @csrf @method('DELETE')
                  <x-button variant="destructive" type="submit" class="act-del"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete</x-button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($faqs->hasPages())
    <div class="pagination-wrap">
      <span class="page-info">Showing {{ $faqs->firstItem() }}–{{ $faqs->lastItem() }} of {{ $faqs->total() }}</span>
      <div class="page-btns">
        @if($faqs->onFirstPage())<span class="page-btn" style="opacity:.4;cursor:not-allowed;">¹</span>
        @else<a href="{{ $faqs->previousPageUrl() }}" class="page-btn">¹</a>@endif
        @foreach($faqs->getUrlRange(1,$faqs->lastPage()) as $page=>$url)
          <x-button variant="secondary" href="{{ $url }}" class="page-btn {{ $faqs->currentPage()==$page?'cur':'' }}">{{ $page }}</x-button>
        @endforeach
        @if($faqs->hasMorePages())<a href="{{ $faqs->nextPageUrl() }}" class="page-btn">›</a>
        @else<span class="page-btn" style="opacity:.4;cursor:not-allowed;">›</span>@endif
      </div>
    </div>
    @endif
  @endif
</div>
@endsection
