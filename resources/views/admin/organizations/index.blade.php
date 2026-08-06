@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/organizations.css')
@endpush


@section('page_title', 'NGOs')
@section('page_subtitle', 'Browse all registered organizations')
@section('sidebar_organizations', 'active')

@section('content')

@if(session('success'))
<div class="alert-ok" id="flashAlert">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

{{-- STATS --}}
<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Pending</div>
      <div class="stat-val sv-amber">{{ $cntPending }}</div>
      <div class="stat-foot">Awaiting review</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-a">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Under Review</div>
      <div class="stat-val sv-a">{{ $cntReview }}</div>
      <div class="stat-foot">Being evaluated</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Approved</div>
      <div class="stat-val sv-green">{{ $cntApproved }}</div>
      <div class="stat-foot">NGOs onboarded</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Rejected</div>
      <div class="stat-val sv-red">{{ $cntRejected }}</div>
      <div class="stat-foot">Declined</div>
    </div>
  </div>
</div>

{{-- TOOLBAR --}}
<form id="filterForm" method="GET" action="{{ route('admin.organizations.index') }}" style="margin-bottom:0;">
  <div class="toolbar">
    <div class="toolbar-left">
      <div class="search-wrap">
        <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" class="search-input" name="search" value="{{ $search }}" placeholder="Search name, email, org…" oninput="autoSubmit()">
      </div>
      <div class="select-wrap">
        <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        <select class="filter-select" name="status" onchange="this.form.submit()">
          <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
          <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="under_review" {{ $status === 'under_review' ? 'selected' : '' }}>Under Review</option>
          <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
          <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
      </div>
      <input type="hidden" name="sort" value="{{ $sort }}">
      <input type="hidden" name="direction" value="{{ $dir }}">
    </div>
    <div class="toolbar-right">
      <x-button variant="primary" href="{{ route('admin.organizations.create') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Onboard NGO
      </x-button>
    </div>
  </div>
</form>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-left">
      <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div>
      <span class="card-head-title">All NGOs</span>
    </div>
    <span class="card-head-count">{{ $organizations->total() }} total</span>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#ID</th>
          <th>Organization</th>
          <th>Applicant</th>
          <th>Type</th>
          <th>Contact</th>
          <th>Submitted</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($organizations as $org)
          <tr>
            <td class="ngo-sub">#{{ $org->id }}</td>
            <td>
              <div class="ngo-name">{{ $org->name }}</div>
              <div class="ngo-sub">{{ $org->city ?? '—' }}{{ $org->state ? ', '.$org->state : '' }}</div>
            </td>
            <td>
              <div>{{ $org->contact_name ?? '—' }}</div>
              <div class="ngo-sub">{{ $org->contact_email ?? '—' }}</div>
            </td>
            <td>{{ $org->organization_type ?? '—' }}</td>
            <td>{{ $org->contact_phone ?? '—' }}</td>
            <td>{{ $org->submitted_at ? $org->submitted_at->format('d M Y') : '—' }}</td>
            <td>
              @php
                $pill = match($org->status) {
                  'approved'     => 'pill-approved',
                  'pending'      => 'pill-pending',
                  'under_review' => 'pill-under_review',
                  'rejected'     => 'pill-rejected',
                  default        => 'pill-draft',
                };
              @endphp
              <span class="pill {{ $pill }}">{{ str_replace('_', ' ', $org->status) }}</span>
            </td>
            <td>
              <x-button variant="primary" href="{{ route('admin.applications.show', $org->id) }}" class="btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
              </x-button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8">
              <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                <div>No NGOs found.</div>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($organizations->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border);">
      {{ $organizations->links('vendor.pagination.admin') }}
    </div>
  @endif
</div>

@endsection

@push('page_scripts')
<script>
let _t;
function autoSubmit(){clearTimeout(_t);_t=setTimeout(()=>document.getElementById('filterForm').submit(),400);}
</script>
@endpush
