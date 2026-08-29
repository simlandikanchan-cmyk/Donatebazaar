
@extends('layouts.admin')

@section('sidebar_volunteers', 'active')
@section('page_title', 'All Volunteers')
@section('page_subtitle', 'Manage volunteer profiles and their application status')

@section('content')

    <div class="hero">
      <div class="hero-left">
        <div class="hero-tag"><span class="hero-tag-dot"></span>Volunteers</div>
        <div class="hero-name">All Volunteers</div>
        <div class="hero-sub">View, search and manage everyone who has registered as a volunteer on the platform.</div>
        <div class="hero-badges">
          <span class="hero-badge hb-primary">{{ $stats['total'] }} total</span>
          <span class="hero-badge hb-green">{{ $stats['verified'] }} verified</span>
          @if($stats['pending'] > 0)
            <span class="hero-badge hb-amber">{{ $stats['pending'] }} pending applications</span>
          @endif
        </div>
      </div>
      <div class="hero-right">
        <a href="{{ route('admin.volunteer_applications.index') }}" class="hero-btn hero-btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Pending Applications
        </a>
        <a href="{{ route('volunteer.apply') }}" class="hero-btn hero-btn-ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Public Apply Page
        </a>
      </div>
    </div>

    <div class="stats-grid">
      <div class="stat">
        <div class="stat-icon si-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Total</div>
          <div class="stat-val sv-primary">{{ $stats['total'] }}</div>
          <div class="stat-foot">All volunteers</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-green">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Verified</div>
          <div class="stat-val sv-green">{{ $stats['verified'] }}</div>
          <div class="stat-foot">Approved applications</div>
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
        <div class="stat-icon si-blue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Unverified</div>
          <div class="stat-val sv-blue">{{ $stats['total'] - $stats['verified'] }}</div>
          <div class="stat-foot">Not yet approved</div>
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

    <form method="GET" action="{{ route('admin.volunteers.index') }}" class="filter-bar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;color:var(--text3);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
      <input class="filter-inp" type="text" name="search" placeholder="Search by name, email, city or phone…" value="{{ request('search') }}">
      <select class="filter-sel" id="filter-state" name="state">
        <option value="">All states</option>
      </select>
      <div class="filter-city-wrap">
        <input class="filter-inp" type="text" id="filter-city" name="city" placeholder="Filter by city…" value="{{ request('city') }}" autocomplete="off">
        <div id="city-suggestions" class="vol-city-suggest" style="position:absolute;top:100%;left:0;right:0;z-index:30;background:var(--surface);border:1px solid var(--border);border-top:none;border-radius:0 0 var(--r-sm) var(--r-sm);max-height:220px;overflow-y:auto;display:none;"></div>
      </div>
      <button type="submit" class="filter-btn">Search</button>
      @if(request('search'))
        <a href="{{ route('admin.volunteers.index') }}" class="filter-clear">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          Clear
        </a>
      @endif
    </form>

    <div class="sec-hdr">
      <div class="sec-ttl">All Volunteers</div>
      <div class="sec-right" style="font-size:12px;color:var(--text3);font-family:var(--mono);">
        {{ $volunteers->total() }} result{{ $volunteers->total() !== 1 ? 's' : '' }}
      </div>
    </div>

    <div class="table-card">
      <div class="table-wrap">
        <table id="volTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Volunteer</th>
              <th>Phone</th>
              <th>Country</th>
              <th>State</th>
              <th>City</th>
              <th>State</th>
              <th>Availability</th>
              <th>Verified</th>
              <th>Registered</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($volunteers as $v)
            <tr>
              <td class="cell-id">{{ $v->id }}</td>
              <td>
                <div class="applicant-name">{{ $v->user?->name ?? '—' }}</div>
                <div class="applicant-email">{{ $v->user?->email ?? '—' }}</div>
              </td>
              <td class="cell-date">{{ $v->phone ?? '—' }}</td>
              <td>{{ $v->country ?? 'India' }}</td>
              <td>{{ $v->state ?? '—' }}</td>
              <td>{{ $v->city ?? '—' }}</td>
              <td>{{ $v->state ?? '—' }}</td>
              <td>
                @if($v->availability)
                  <span class="badge b-pending">{{ str_replace('_', ' ', ucfirst($v->availability)) }}</span>
                @else
                  <span class="no-cv">—</span>
                @endif
              </td>
              <td>
                @if($v->is_verified)
                  <span class="badge b-shortlisted">Verified</span>
                @else
                  <span class="badge b-rejected">Unverified</span>
                @endif
              </td>
              <td class="cell-date">{{ $v->created_at->format('d M Y') }}</td>
              <td>
                <a href="{{ route('admin.volunteers.show', $v) }}" class="act-link">
                  View
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                <form method="POST" action="{{ route('admin.volunteers.destroy', $v) }}" style="display:inline;" data-confirm="Delete this volunteer? This cannot be undone.">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-red act-btn ab-delete" title="Delete" style="margin-left:8px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
                  </button>
                </form>
              </td>
            </tr>
            @empty
            <tr class="empty-row">
              <td colspan="10">
                <div class="empty-inner">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                  <strong>No volunteers found</strong>
                  <span>No volunteers match your current search.</span>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="pagination-wrap">{{ $volunteers->links('vendor.pagination.admin') }}</div>

{{-- Page data for volunteers-index.js --}}
@php
    $volunteersIndexData = [
        'statesCities' => json_decode(file_get_contents(resource_path('js/data/in-states-cities.json')), true),
        'selectedState' => request('state'),
    ];
@endphp
<script type="application/json" id="volunteersIndexData">@json($volunteersIndexData)</script>

@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/volunteers-index.js')
@endpush

@push('page_css')
@vite('resources/css/admin/entries/volunteers.css')
@vite('resources/css/admin/pages/volunteers-index.css')
@endpush

@push('page_styles')
<style>
@media(max-width:860px){
  .stats-grid{grid-template-columns:repeat(2,1fr)!important}
}
@media(max-width:480px){
  .stats-grid{grid-template-columns:1fr!important}
}
@media(max-width:640px){
  .table-wrap{min-width:640px;overflow-x:auto}
  .hero-right{width:100%;margin-top:14px}
  .hero-right .hero-btn{width:100%;justify-content:center}
}
</style>
@endpush
@endpush
