@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/jobs.css')
@endpush


@section('sidebar_volunteers', 'active')
@section('page_title', $volunteer->user?->name ?? 'Volunteer #'.$volunteer->id)
@section('page_subtitle', 'Volunteer profile details')

@section('content')

@if(session('success'))
<div style="background:rgba(5,196,138,.09);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  {{ session('error') }}
</div>
@endif

<div class="hero-card">
  <div class="hero-left">
    <div class="hero-av">
      {{ strtoupper(substr($volunteer->user?->name ?? '?', 0, 1)) }}
    </div>
    <div>
      <div class="hero-title">{{ $volunteer->user?->name ?? 'Unknown User' }}</div>
      <div class="hero-sub">{{ $volunteer->user?->email ?? '—' }}</div>
      <div class="hero-meta">
        <span class="hero-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
          {{ $volunteer->phone ?? '—' }}
        </span>
        <span class="hero-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          {{ collect([$volunteer->city, $volunteer->state, $volunteer->country])->filter()->join(', ') ?: '—' }}
        </span>
        <span class="hero-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          Registered {{ $volunteer->created_at->format('d M Y') }}
        </span>
        @if($volunteer->availability)
        <span class="hero-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          {{ str_replace('_', ' ', ucfirst($volunteer->availability)) }}
        </span>
        @endif
      </div>
    </div>
  </div>
  <div class="hero-right" style="display:flex;flex-direction:column;align-items:flex-end;gap:12px;flex-shrink:0">
    <span class="badge {{ $volunteer->is_verified ? 'b-shortlisted' : 'b-rejected' }}" style="font-size:13px;padding:6px 16px">
      {{ $volunteer->is_verified ? 'Verified' : 'Unverified' }}
    </span>
  </div>
</div>

<div class="detail-card">
  <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:var(--text3);font-family:var(--mono);margin-bottom:16px">Profile Details</div>
  <div class="detail-grid">
    <div class="info-box">
      <div class="info-label">Full Name</div>
      <div class="info-value">{{ $volunteer->user?->name ?? '—' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">Email</div>
      <div class="info-value">{{ $volunteer->user?->email ?? '—' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">Phone</div>
      <div class="info-value">{{ $volunteer->phone ?? '—' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">City</div>
      <div class="info-value">{{ $volunteer->city ?? '—' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">State</div>
      <div class="info-value">{{ $volunteer->state ?? '—' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">Country</div>
      <div class="info-value">{{ $volunteer->country ?? 'India' }}</div>
    </div>
    <div class="info-box">
      <div class="info-label">Availability</div>
      <div class="info-value">{{ $volunteer->availability ? str_replace('_', ' ', ucfirst($volunteer->availability)) : '—' }}</div>
    </div>
    <div class="info-box" style="grid-column:span 2">
      <div class="info-label">Skills</div>
      <div class="info-value">
        @if($volunteer->skills && is_array($volunteer->skills) && count($volunteer->skills))
          <div class="skills-list">
            @foreach($volunteer->skills as $skill)
              <span class="skill-tag">{{ $skill }}</span>
            @endforeach
          </div>
        @else
          <span class="empty">No skills listed</span>
        @endif
      </div>
    </div>
    @if($volunteer->bio)
    <div class="info-box" style="grid-column:span 3">
      <div class="info-label">Bio</div>
      <div class="info-value">{{ $volunteer->bio }}</div>
    </div>
    @endif
  </div>
</div>

<div class="detail-card">
  <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:var(--text3);font-family:var(--mono);margin-bottom:16px">Applications History</div>
  @if($volunteer->applications && $volunteer->applications->count())
    <div class="table-card" style="box-shadow:none;border:1px solid var(--border);margin-bottom:0">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Campaign</th>
              <th>Status</th>
              <th>Message</th>
              <th>Applied</th>
            </tr>
          </thead>
          <tbody>
            @foreach($volunteer->applications as $app)
            <tr>
              <td class="cell-date">{{ $app->id }}</td>
              <td>{{ $app->campaign?->title ?? 'General Application' }}</td>
              <td>
                <span class="badge {{ $app->status === 'approved' ? 'b-shortlisted' : ($app->status === 'rejected' ? 'b-rejected' : 'b-pending') }}">
                  {{ ucfirst($app->status) }}
                </span>
              </td>
              <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--text3);font-size:12px">{{ $app->message ?? '—' }}</td>
              <td class="cell-date">{{ $app->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @else
    <div style="text-align:center;padding:40px 20px;color:var(--text3);font-family:var(--mono);font-size:13px">No applications found for this volunteer.</div>
  @endif
</div>

<div class="detail-card">
  <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:var(--text3);font-family:var(--mono);margin-bottom:16px">Assignments</div>
  @if($volunteer->assignments && $volunteer->assignments->count())
    <div class="table-card" style="box-shadow:none;border:1px solid var(--border);margin-bottom:0">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Role</th>
              <th>Campaign / Event</th>
              <th>Period</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($volunteer->assignments as $as)
            <tr>
              <td class="cell-date">{{ $as->id }}</td>
              <td>{{ $as->role ?? '—' }}</td>
              <td>{{ $as->campaign?->title ?? $as->event?->title ?? '—' }}</td>
              <td class="cell-date">
                @if($as->start_date)
                  {{ $as->start_date->format('d M') }}
                  @if($as->end_date) – {{ $as->end_date->format('d M Y') }} @endif
                @else
                  —
                @endif
              </td>
              <td>
                <span class="badge {{ $as->status === 'active' ? 'b-shortlisted' : ($as->status === 'completed' ? 'b-hired' : 'b-pending') }}">
                  {{ ucfirst($as->status ?? 'unknown') }}
                </span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @else
    <div style="text-align:center;padding:40px 20px;color:var(--text3);font-family:var(--mono);font-size:13px">No assignments yet for this volunteer.</div>
  @endif
</div>

@if($volunteer->is_verified)
<div class="detail-card">
  <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:var(--text3);font-family:var(--mono);margin-bottom:16px">Assign to Event</div>
  <form id="assignForm" method="POST">
    @csrf
    <input type="hidden" name="volunteer_id" value="{{ $volunteer->id }}">
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:12px;align-items:end">
      <div>
        <div class="info-label" style="margin-bottom:6px">Event</div>
        <select id="assignEvent" class="filter-sel" required style="width:100%">
          <option value="">— Select Event —</option>
          @foreach($events as $ev)
            <option value="{{ $ev->id }}"
              data-date="{{ $ev->event_date->format('Y-m-d') }}"
              @if($ev->event_date->isToday()) data-today="1" @endif
            >{{ $ev->title }} ({{ $ev->event_date->format('d M') }})</option>
          @endforeach
        </select>
      </div>
      <div>
        <div class="info-label" style="margin-bottom:6px">Role</div>
        <input class="filter-inp" name="role" placeholder="e.g. Coordinator" style="width:100%">
      </div>
      <div>
        <div class="info-label" style="margin-bottom:6px">Start Date</div>
        <input class="filter-inp" name="start_date" type="date" id="assignStart" style="width:100%">
      </div>
      <div>
        <div class="info-label" style="margin-bottom:6px">End Date</div>
        <input class="filter-inp" name="end_date" type="date" id="assignEnd" style="width:100%">
      </div>
    </div>
    <div style="margin-top:14px;display:flex;align-items:center;gap:12px">
      <x-button variant="primary" type="submit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Assign to Event
      </x-button>
      <span style="font-size:12px;color:var(--text3);font-family:var(--mono)" id="assignHint">Select an event above</span>
    </div>
  </form>
</div>

@push('page_scripts')
<script>
(function(){
  var sel = document.getElementById('assignEvent');
  var btn = document.getElementById('assignBtn');
  var hint = document.getElementById('assignHint');
  var form = document.getElementById('assignForm');
  var startInp = document.getElementById('assignStart');
  var endInp = document.getElementById('assignEnd');

  if (sel) {
    sel.addEventListener('change', function(){
      var val = this.value;
      if (val) {
        var opt = this.options[this.selectedIndex];
        btn.disabled = false;
        hint.textContent = 'Will be assigned to event #' + val;
        form.action = '{{ url('admin/events') }}/' + val + '/assign-volunteer';
        var d = opt.getAttribute('data-date');
        if (d) {
          startInp.value = d;
          endInp.value = d;
        }
      } else {
        btn.disabled = true;
        hint.textContent = 'Select an event above';
        form.action = '';
        startInp.value = '';
        endInp.value = '';
      }
    });
  }
})();
</script>
@endpush
@else
<div class="detail-card" style="background:var(--amber-lt);border-color:rgba(245,158,11,.25)">
  <div style="display:flex;align-items:center;gap:12px">
    <svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2" style="width:20px;height:20px;flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <div>
      <div style="font-weight:700;font-size:13px;color:var(--text);font-family:var(--mono)">Not Verified</div>
      <div style="font-size:12px;color:var(--text2);margin-top:2px">Only verified volunteers can be assigned to events. Approve their application first.</div>
    </div>
  </div>
</div>
@endif

<div style="margin-top:10px">
  <a href="{{ route('admin.volunteers.index') }}" class="filter-clear" style="display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 16px;border-radius:var(--r-sm);border:1px solid var(--border2);font-size:12.5px;color:var(--text3);text-decoration:none;transition:all var(--ease)">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
    Back to All Volunteers
  </a>
</div>

@endsection

