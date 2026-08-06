@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/jobs.css')
@endpush


@section('sidebar_volunteer_assignments', 'active')
@section('page_title', 'Volunteer Assignments')
@section('page_subtitle', 'Manage volunteer assignments to events & campaigns')

@section('topbar_left')
  <x-button variant="primary" href="{{ route('admin.volunteer_assignments.create') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    New Assignment
  </x-button>
@endsection

@section('content')
<div class="toolbar">
  <form method="GET" action="{{ route('admin.volunteer_assignments.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
    <div class="search-wrap">
      <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="search-input" name="search" value="{{ request('search') }}" placeholder="Search volunteer or role…">
    </div>
    <select class="filter-btn" name="status" onchange="this.form.submit()">
      <option value="">All statuses</option>
      <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
      <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Completed</option>
      <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Cancelled</option>
    </select>
    <x-button variant="secondary" type="submit" class="filter-btn">Filter</x-button>
    @if(request('search') || request('status'))
      <x-button variant="secondary" href="{{ route('admin.volunteer_assignments.index') }}" class="filter-btn">Clear</x-button>
    @endif
  </form>
</div>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div>
    <span class="card-head-title">Assignments</span>
    <span class="card-head-count">{{ $assignments->total() }} total · {{ $stats['active'] }} active</span>
  </div>

  @if($assignments->isEmpty())
    <div class="empty-state">
      <div class="empty-icon-wrap">🤝</div>
      <h3>No assignments yet</h3>
      <p>Assign volunteers to events or campaigns to track their work.</p>
      <x-button variant="primary" href="{{ route('admin.volunteer_assignments.create') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>New Assignment
      </x-button>
    </div>
  @else
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:50px;">#</th>
            <th>Volunteer</th>
            <th>Assigned To</th>
            <th>Role</th>
            <th>Period</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($assignments as $a)
          <tr>
            <td><span style="font-size:11.5px;color:var(--text3);font-family:var(--mono);">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
            <td>
              <div class="vol-name">{{ $a->volunteer->user->name ?? 'Volunteer #'.$a->volunteer_id }}</div>
              <div class="vol-sub">{{ $a->volunteer->user->email ?? '' }}</div>
            </td>
            <td>
              <div class="target-cell">
                @if($a->event)📅 {{ $a->event->title }}@endif
                @if($a->campaign)🎯 {{ $a->campaign->title }}@endif
                @if(!$a->event && !$a->campaign)<span style="color:var(--text3);">—</span>@endif
              </div>
            </td>
            <td><span class="role-pill">{{ $a->role }}</span></td>
            <td>
              <span style="font-size:12px;color:var(--text3);">
                {{ $a->start_date?->format('M d, Y') ?? '—' }}
                @if($a->end_date) → {{ $a->end_date->format('M d, Y') }}@endif
              </span>
            </td>
            <td>
              <span class="status-pill s-{{ $a->status }}">
                <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                {{ ucfirst($a->status) }}
              </span>
            </td>
            <td>
              <div class="actions">
                <a href="{{ route('admin.volunteer_assignments.edit', $a->id) }}" class="btn btn-secondary act-btn act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
                <form method="POST" action="{{ route('admin.volunteer_assignments.destroy', $a->id) }}" onsubmit="return confirm('Remove this assignment?');">@csrf @method('DELETE')
                  <x-button variant="destructive" type="submit" class="act-del"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg></x-button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($assignments->hasPages())
    <div class="pagination-wrap">
      <span class="page-info">Showing {{ $assignments->firstItem() }}–{{ $assignments->lastItem() }} of {{ $assignments->total() }}</span>
      <div class="page-btns">
        @if($assignments->onFirstPage())<span class="page-btn" style="opacity:.4;cursor:not-allowed;">¹</span>
        @else<a href="{{ $assignments->previousPageUrl() }}" class="page-btn">¹</a>@endif
        @foreach($assignments->getUrlRange(1,$assignments->lastPage()) as $page=>$url)
          <x-button variant="secondary" href="{{ $url }}" class="page-btn {{ $assignments->currentPage()==$page?'cur':'' }}">{{ $page }}</x-button>
        @endforeach
        @if($assignments->hasMorePages())<a href="{{ $assignments->nextPageUrl() }}" class="page-btn">›</a>
        @else<span class="page-btn" style="opacity:.4;cursor:not-allowed;">›</span>@endif
      </div>
    </div>
    @endif
  @endif
</div>
@endsection
