@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/jobs.css')
@endpush


@section('sidebar_volunteer_applications', 'active')
@section('page_title', 'Volunteer Applications')
@section('page_subtitle', 'Review and manage volunteer applications')

@section('content')

    <div class="hero">
      <div class="hero-left">
        <div class="hero-tag"><span class="hero-tag-dot"></span>Volunteers</div>
        <div class="hero-name">Volunteer Applications</div>
        <div class="hero-sub">Review, approve or reject volunteer applications submitted through the platform.</div>
        <div class="hero-badges">
          <span class="hero-badge hb-purple">{{ $stats['total'] }} total</span>
          @if($stats['pending'] > 0)
            <span class="hero-badge hb-amber">{{ $stats['pending'] }} pending</span>
          @endif
          @if($stats['approved'] > 0)
            <span class="hero-badge hb-green">{{ $stats['approved'] }} approved</span>
          @endif
          @if($stats['rejected'] > 0)
            <span class="hero-badge hb-red">{{ $stats['rejected'] }} rejected</span>
          @endif
        </div>
      </div>
      <div class="hero-right">
        <x-button variant="primary" href="{{ route('admin.volunteers.index') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
          All Volunteers
        </x-button>
      </div>
    </div>

    <div class="stats-grid">
      <div class="stat">
        <div class="stat-icon si-purple">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Total</div>
          <div class="stat-val sv-purple">{{ $stats['total'] }}</div>
          <div class="stat-foot">All applications</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-amber">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Pending</div>
          <div class="stat-val sv-amber">{{ $stats['pending'] }}</div>
          <div class="stat-foot">Awaiting review</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-green">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Approved</div>
          <div class="stat-val sv-green">{{ $stats['approved'] }}</div>
          <div class="stat-foot">Verified volunteers</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-red">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Rejected</div>
          <div class="stat-val sv-red">{{ $stats['rejected'] }}</div>
          <div class="stat-foot">Not selected</div>
        </div>
      </div>
    </div>

    @if(session('success'))
    <div class="flash flash-success">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flash flash-error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      {{ session('error') }}
    </div>
    @endif

    <form method="GET" action="{{ route('admin.volunteer_applications.index') }}" class="filter-bar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;color:var(--text3);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
      <input class="filter-inp" type="text" name="search" placeholder="Search applicant name or email…" value="{{ request('search') }}">
      <select class="filter-sel" name="status">
        <option value="">Pending (default)</option>
        <option value="pending"  @selected(request('status') === 'pending')>Pending</option>
        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
      </select>
      <x-button variant="secondary" type="submit" class="filter-btn">Apply Filters</x-button>
      @if(request('search') || request('status'))
        <a href="{{ route('admin.volunteer_applications.index') }}" class="filter-clear">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          Clear
        </a>
      @endif
    </form>

    <div class="sec-hdr">
      <div class="sec-ttl">
        {{ request('status') ? ucfirst(request('status')) : 'Pending' }} Applications
      </div>
      <div class="sec-right" style="font-size:12px;color:var(--text3);font-family:var(--mono);">
        {{ $applications->total() }} result{{ $applications->total() !== 1 ? 's' : '' }}
      </div>
    </div>

    <div class="table-card">
      <div class="table-wrap">
        <table id="appTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Applicant</th>
              <th>Campaign</th>
              <th>Status</th>
              <th>Applied</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($applications as $app)
            <tr>
              <td class="cell-id">{{ $app->id }}</td>
              <td>
                <div class="applicant-name">{{ $app->volunteer?->user?->name ?? '—' }}</div>
                <div class="applicant-email">{{ $app->volunteer?->user?->email ?? '—' }}</div>
              </td>
              <td>
                <div class="job-name">{{ $app->campaign?->title ?? 'General' }}</div>
              </td>
              <td>
                @php
                  $statusClass = match($app->status) {
                    'approved' => 'b-shortlisted',
                    'rejected' => 'b-rejected',
                    default    => 'b-pending',
                  };
                @endphp
                <span class="badge {{ $statusClass }}">{{ $app->status }}</span>
              </td>
              <td class="cell-date">{{ $app->created_at->format('d M Y') }}</td>
              <td>
                <div style="display:flex;gap:5px;flex-wrap:nowrap">
                  <a href="{{ route('admin.volunteer_applications.show', $app) }}" class="act-link">
                    Review
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                  </a>
                  @if($app->status === 'pending')
                    <form method="POST" action="{{ route('admin.volunteer_applications.approve', $app) }}" style="display:inline">
                      @csrf
                      <x-button variant="primary" type="submit" class="act-approve">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Approve
                      </x-button>
                    </form>
                    <form method="POST" action="{{ route('admin.volunteer_applications.reject', $app) }}" style="display:inline">
                      @csrf
                      <x-button variant="destructive" type="submit" class="act-reject">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reject
                      </x-button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
            @empty
            <tr class="empty-row">
              <td colspan="6">
                <div class="empty-inner">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  <strong>No applications found</strong>
                  <span>No applications match your current filter.</span>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="pagination-wrap">{{ $applications->links('vendor.pagination.admin') }}</div>

@endsection

