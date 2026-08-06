@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush


@section('sidebar_fundraiser_levels', 'active')
@section('page_title', 'Fundraiser Levels')
@section('page_subtitle', 'Configure fundraiser ')

@section('topbar_left')
  <x-button variant="secondary" href="{{ route('admin.dashboard') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/></svg>
    Dashboard
  </x-button>
  <x-button variant="primary" href="{{ route('admin.fundraiser-levels.create') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    Add Level
  </x-button>
@endsection

@section('content')
<div class="main-card">
  <div class="card-head">
    <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/></svg></div>
    <span class="card-head-title">Progression</span>
    <span class="card-head-count">{{ $levels->count() }} levels · {{ $stats['approval'] }} need approval</span>
  </div>

  @if($levels->isEmpty())
    <div class="empty-state">
      <div class="empty-icon-wrap">🏅</div>
      <h3>No levels configured</h3>
    </div>
  @else
    <div class="level-list">
      @foreach($levels as $level)
      <div class="level-card">
        <div class="level-badge" style="background:{{ $level->badge_color ?? '#6366f1' }};">{{ $level->level_number }}</div>
        <div class="level-main">
          <div class="level-name">
            {{ $level->level_name }}
            @if($level->is_default)<span class="def-pill">Default</span>@endif
          </div>
          <div class="level-desc">{{ $level->description ?? '—' }}</div>
          <div class="level-attrs">
            <span class="attr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>Max goal ₹{{ number_format($level->max_goal_amount, 0) }}</span>
            <span class="attr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>{{ $level->max_active_campaigns }} active</span>
            <span class="attr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>{{ $level->min_campaigns_completed }} completed</span>
            <span class="attr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>{{ $level->min_raised_percent }}% raised</span>
            <span class="attr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>KYC: {{ ucfirst($level->kyc_requirement) }}</span>
            <span class="apr-pill ap-{{ $level->requires_admin_approval?'yes':'no' }}">{{ $level->requires_admin_approval?'Approval req.':'Auto-upgrade' }}</span>
          </div>
        </div>
        <div class="actions">
          <a href="{{ route('admin.fundraiser-levels.edit', $level->id) }}" class="btn btn-secondary act-btn act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
          <form method="POST" action="{{ route('admin.fundraiser-levels.destroy', $level->id) }}" onsubmit="return confirm('Delete this level?');">@csrf @method('DELETE')
            <x-button variant="destructive" type="submit" class="act-del"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg></x-button>
          </form>
        </div>
      </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
