@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush


@section('sidebar_subscribers', 'active')
@section('page_title', 'Newsletter')
@section('page_subtitle', 'Manage newsletter subscribers & unsubscribes')

@section('topbar_left')
  <x-button variant="primary" href="{{ route('admin.subscribers.export') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
    Export CSV
  </x-button>
@endsection

@section('content')
<div class="toolbar">
  <form method="GET" action="{{ route('admin.subscribers.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
    <div class="search-wrap">
      <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="search-input" name="search" value="{{ request('search') }}" placeholder="Search email…">
    </div>
    <select class="filter-btn" name="status" onchange="this.form.submit()">
      <option value="">All statuses</option>
      <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
      <option value="unsubscribed" {{ request('status')==='unsubscribed'?'selected':'' }}>Unsubscribed</option>
    </select>
    <x-button variant="secondary" type="submit" class="filter-btn">Filter</x-button>
    @if(request('search') || request('status'))
      <x-button variant="secondary" href="{{ route('admin.subscribers.index') }}" class="filter-btn">Clear</x-button>
    @endif
  </form>
</div>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
    <span class="card-head-title">Subscribers</span>
    <span class="card-head-count">{{ $subscribers->total() }} total · {{ $stats['active'] }} active</span>
  </div>

  @if($subscribers->isEmpty())
    <div class="empty-state">
      <div class="empty-icon-wrap">✉️</div>
      <h3>No subscribers found</h3>
      <p>When visitors subscribe to the newsletter they'll appear here.</p>
    </div>
  @else
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:50px;">#</th>
            <th>Email</th>
            <th>Status</th>
            <th>Subscribed</th>
            <th>Unsubscribed</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($subscribers as $sub)
          <tr>
            <td><span style="font-size:11.5px;color:var(--text3);font-family:var(--mono);">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
            <td><span class="email-cell">{{ $sub->email }}</span></td>
            <td>
              <span class="status-pill {{ $sub->unsubscribed_at?'s-inactive':'s-active' }}">
                <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                {{ $sub->unsubscribed_at?'Unsubscribed':'Active' }}
              </span>
            </td>
            <td><span class="meta-cell">{{ $sub->subscribed_at?->format('M d, Y') ?? '—' }}</span></td>
            <td><span class="meta-cell">{{ $sub->unsubscribed_at?->format('M d, Y') ?? '—' }}</span></td>
            <td>
              <div class="actions">
                @if($sub->unsubscribed_at)
                  <form method="POST" action="{{ route('admin.subscribers.resubscribe', $sub->id) }}">@csrf
                    <x-button variant="secondary" type="submit" class="act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>Resubscribe</x-button>
                  </form>
                @else
                  <form method="POST" action="{{ route('admin.subscribers.unsubscribe', $sub->id) }}">@csrf
                    <x-button variant="secondary" type="submit" class="act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.36 6.64a9 9 0 11-12.73 0M12 2v10"/></svg>Unsubscribe</x-button>
                  </form>
                @endif
                <form method="POST" action="{{ route('admin.subscribers.destroy', $sub->id) }}" onsubmit="return confirm('Remove this subscriber permanently?');">@csrf @method('DELETE')
                  <x-button variant="destructive" type="submit" class="act-del"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg></x-button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($subscribers->hasPages())
    <div class="pagination-wrap">
      <span class="page-info">Showing {{ $subscribers->firstItem() }}–{{ $subscribers->lastItem() }} of {{ $subscribers->total() }}</span>
      <div class="page-btns">
        @if($subscribers->onFirstPage())<span class="page-btn" style="opacity:.4;cursor:not-allowed;">¹</span>
        @else<a href="{{ $subscribers->previousPageUrl() }}" class="page-btn">¹</a>@endif
        @foreach($subscribers->getUrlRange(1,$subscribers->lastPage()) as $page=>$url)
          <x-button variant="secondary" href="{{ $url }}" class="page-btn {{ $subscribers->currentPage()==$page?'cur':'' }}">{{ $page }}</x-button>
        @endforeach
        @if($subscribers->hasMorePages())<a href="{{ $subscribers->nextPageUrl() }}" class="page-btn">›</a>
        @else<span class="page-btn" style="opacity:.4;cursor:not-allowed;">›</span>@endif
      </div>
    </div>
    @endif
  @endif
</div>
@endsection
