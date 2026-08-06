@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush


@section('sidebar_success_stories', 'active')
@section('page_title', 'Success Stories')
@section('page_subtitle', 'Curate completed campaigns featured on the Impact page')

@section('content')
<div class="toolbar">
  <form method="GET" action="{{ route('admin.success-stories.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
    <div class="search-wrap">
      <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="search-input" name="search" value="{{ request('search') }}" placeholder="Search campaigns…">
    </div>
    <select class="filter-btn" name="featured" onchange="this.form.submit()">
      <option value="">All completed</option>
      <option value="1" {{ request('featured')==='1'?'selected':'' }}>Featured only</option>
      <option value="0" {{ request('featured')==='0'?'selected':'' }}>Not featured</option>
    </select>
    <x-button variant="secondary" type="submit" class="filter-btn">Filter</x-button>
    @if(request('search') || request('featured')!==null)
      <x-button variant="secondary" href="{{ route('admin.success-stories.index') }}" class="filter-btn">Clear</x-button>
    @endif
  </form>
</div>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg></div>
    <span class="card-head-title">Completed Campaigns</span>
    <span class="card-head-count">{{ $campaigns->total() }} completed · {{ $stats['featured'] }} featured</span>
  </div>

  @if($campaigns->isEmpty())
    <div class="empty-state">
      <div class="empty-icon-wrap">🏆</div>
      <h3>No completed campaigns yet</h3>
      <p>Completed campaigns can be featured on the public Impact / Success Stories page.</p>
    </div>
  @else
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:50px;">#</th>
            <th>Campaign</th>
            <th>Category</th>
            <th>Raised</th>
            <th>Featured</th>
            <th style="text-align:right;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($campaigns as $c)
          <tr>
            <td><span style="font-size:11.5px;color:var(--text3);font-family:var(--mono);">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
            <td>
              <div class="camp-cell">
                @if($c->cover_image)
                  <img src="{{ asset('storage/'.$c->cover_image) }}" class="camp-img" alt="">
                @else
                  <div class="camp-img" style="display:flex;align-items:center;justify-content:center;color:var(--text3);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
                @endif
                <div>
                  <div class="camp-name">{{ Str::limit($c->title, 50) }}</div>
                  <div class="camp-sub">{{ $c->location ?? '—' }}</div>
                </div>
              </div>
            </td>
            <td>@if($c->category)<span class="cat-pill">{{ $c->category->name }}</span>@else — @endif</td>
            <td>
              <div class="raised">₹{{ number_format($c->raised_amount, 0) }}</div>
              <div class="goal">of ₹{{ number_format($c->goal_amount, 0) }}</div>
            </td>
            <td>
              <span style="display:inline-flex;{{ $c->is_featured ? 'color:#d97706;' : 'opacity:.3;color:var(--text3);' }}">
                <svg viewBox="0 0 24 24" fill="{{ $c->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
              </span>
            </td>
            <td>
              <div class="actions">
                <form method="POST" action="{{ route('admin.success-stories.toggle', $c->id) }}">@csrf
                  <input type="hidden" name="is_featured" value="{{ $c->is_featured ? 0 : 1 }}">
                  <x-button variant="secondary" type="submit" class="toggle-btn {{ $c->is_featured?'toggle-on':'toggle-off' }}">
                    @if($c->is_featured)
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.36 6.64a9 9 0 11-12.73 0M12 2v10"/></svg>Unfeature
                    @else
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>Feature
                    @endif
                  </x-button>
                </form>
                <a href="{{ route('campaign.public', ['category' => $c->category?->slug ?? 'campaign', 'slug' => $c->slug]) }}" target="_blank" class="btn btn-secondary toggle-btn toggle-off" title="View">View</a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($campaigns->hasPages())
    <div class="pagination-wrap">
      <span class="page-info">Showing {{ $campaigns->firstItem() }}–{{ $campaigns->lastItem() }} of {{ $campaigns->total() }}</span>
      <div class="page-btns">
        @if($campaigns->onFirstPage())<span class="page-btn" style="opacity:.4;cursor:not-allowed;">¹</span>
        @else<a href="{{ $campaigns->previousPageUrl() }}" class="page-btn">¹</a>@endif
        @foreach($campaigns->getUrlRange(1,$campaigns->lastPage()) as $page=>$url)
          <x-button variant="secondary" href="{{ $url }}" class="page-btn {{ $campaigns->currentPage()==$page?'cur':'' }}">{{ $page }}</x-button>
        @endforeach
        @if($campaigns->hasMorePages())<a href="{{ $campaigns->nextPageUrl() }}" class="page-btn">›</a>
        @else<span class="page-btn" style="opacity:.4;cursor:not-allowed;">›</span>@endif
      </div>
    </div>
    @endif
  @endif
</div>
@endsection
