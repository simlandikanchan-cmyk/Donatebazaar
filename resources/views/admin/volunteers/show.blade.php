@extends('layouts.admin')

@section('sidebar_volunteers', 'active')
@section('page_title', $volunteer->user?->name ?? 'Volunteer #'.$volunteer->id)
@section('page_subtitle', 'Volunteer profile details')

@push('page_styles')
<style>
.hero-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:28px 30px;box-shadow:var(--sh);margin-bottom:20px;display:flex;align-items:flex-start;justify-content:space-between;gap:20px;animation:fadeUp .35s ease both;position:relative;overflow:hidden}
.hero-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--a),var(--a2));border-radius:var(--r) var(--r) 0 0}
.hero-left{display:flex;align-items:center;gap:18px;min-width:0}
.hero-av{width:58px;height:58px;border-radius:16px;flex-shrink:0;background:linear-gradient(135deg,var(--a),var(--a2));display:flex;align-items:center;justify-content:center;font-family:var(--mono);font-size:22px;font-weight:800;color:#fff;box-shadow:0 4px 18px rgba(37,99,235,.35)}
.hero-title{font-family:var(--mono);font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.03em;line-height:1.2}
.hero-sub{font-size:12px;color:var(--text3);margin-top:5px;font-family:var(--mono)}
.hero-meta{display:flex;align-items:center;gap:14px;margin-top:10px;flex-wrap:wrap}
.hero-meta-item{display:flex;align-items:center;gap:5px;font-size:11.5px;color:var(--text3);font-family:var(--mono)}
.hero-meta-item svg{width:12px;height:12px;flex-shrink:0}

.detail-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:24px;box-shadow:var(--sh);margin-bottom:20px;animation:fadeUp .4s ease both}
.detail-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.info-box{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:16px 18px;transition:border-color var(--ease),box-shadow var(--ease)}
.info-box:hover{border-color:rgba(37,99,235,.25);box-shadow:0 0 0 3px var(--a-lt)}
.info-label{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;margin-bottom:7px;font-family:var(--mono)}
.info-value{font-size:14px;font-weight:600;color:var(--text);line-height:1.5;word-break:break-word;font-family:var(--mono)}
.info-value.empty{color:var(--text3);font-weight:400}

.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .18s ease both;margin-bottom:20px}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
thead{background:var(--surface2);border-bottom:1px solid var(--border)}
thead th{padding:12px 16px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--text3);font-family:var(--mono);white-space:nowrap}
tbody td{padding:14px 16px;border-bottom:1px solid var(--border);vertical-align:middle}
tbody tr:last-child td{border-bottom:none}
tbody tr{transition:background var(--ease)}
tbody tr:hover{background:var(--surface2)}
.cell-date{font-family:var(--mono);font-size:11.5px;color:var(--text3)}

.skills-list{display:flex;flex-wrap:wrap;gap:6px}
.skill-tag{padding:3px 10px;border-radius:100px;font-size:10.5px;font-weight:500;font-family:var(--mono);background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.15)}
</style>
@endpush

@section('content')

@if(session('success'))
<div class="flash flash-ok" style="background:rgba(5,196,138,.09);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="flash" style="background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px">
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
      <button type="submit" class="assign-btn" id="assignBtn" disabled>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Assign to Event
      </button>
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

@push('page_styles')
<style>
.b-shortlisted{background:rgba(5,196,138,.85);color:#fff;padding:4px 12px;border-radius:6px;font-size:11px;font-weight:600;font-family:var(--mono);display:inline-block}
.b-rejected{background:rgba(240,68,68,.12);color:var(--red);border:1px solid rgba(240,68,68,.22);padding:4px 12px;border-radius:6px;font-size:11px;font-weight:600;font-family:var(--mono);display:inline-block}
.b-pending{background:rgba(245,158,11,.12);color:#b45309;border:1px solid rgba(245,158,11,.22);padding:4px 12px;border-radius:6px;font-size:11px;font-weight:600;font-family:var(--mono);display:inline-block}
.b-hired{background:rgba(37,99,235,.85);color:#fff;padding:4px 12px;border-radius:6px;font-size:11px;font-weight:600;font-family:var(--mono);display:inline-block}
[data-theme="dark"] .b-pending{color:#fbbf24}
[data-theme="dark"] .b-rejected{color:#f87171}
.filter-clear:hover{border-color:var(--a);color:var(--a)}
.filter-sel,.filter-inp{height:36px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:0 12px;font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease)}
.filter-sel:focus,.filter-inp:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow)}
.filter-sel{cursor:pointer;min-width:0}
.filter-inp::placeholder{color:var(--text3)}
.assign-btn{display:inline-flex;align-items:center;gap:8px;height:40px;padding:0 24px;border:none;border-radius:var(--r-sm);font-size:13px;font-weight:600;font-family:var(--font);cursor:pointer;transition:all var(--ease);background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 14px rgba(37,99,235,.35)}
.assign-btn:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 6px 20px rgba(37,99,235,.45)}
.assign-btn:active:not(:disabled){transform:translateY(0)}
.assign-btn:disabled{opacity:.4;cursor:not-allowed;box-shadow:none;transform:none}
@media(max-width:900px){.hero-card{flex-direction:column;padding:22px 24px}.hero-right{align-items:flex-start!important;width:100%}#assignForm>div:first-child{grid-template-columns:1fr 1fr!important}}
@media(max-width:768px){.detail-grid{grid-template-columns:1fr 1fr}.info-box[style*="span 2"],.info-box[style*="span 3"]{grid-column:span 1!important}.hero-title{font-size:18px}.hero-av{width:46px;height:46px;font-size:17px;border-radius:13px}}
@media(max-width:600px){.detail-grid{grid-template-columns:1fr}#assignForm>div:first-child{grid-template-columns:1fr!important}.hero-card{padding:18px 16px}.hero-meta{gap:8px}.hero-meta-item{font-size:10.5px}}
@media(max-width:540px){.table-wrap td:nth-child(4),.table-wrap th:nth-child(4){display:none}.detail-card{padding:18px 16px}.detail-grid{gap:10px}}
@media(max-width:480px){.hero-av{width:38px;height:38px;font-size:14px;border-radius:11px}.hero-title{font-size:16px}}
@media(max-width:380px){.hero-card{padding:14px 12px}.hero-av{width:34px;height:34px;font-size:13px;border-radius:10px}.hero-title{font-size:15px}.hero-sub{font-size:10px}.hero-meta{gap:6px}.hero-meta-item{font-size:9px}.hero-right{gap:6px}.hero-right .btn{width:100%;justify-content:center;font-size:11px;padding:8px 12px}.detail-card{padding:14px 12px}.detail-card h3{font-size:13px}.detail-grid{gap:8px}.info-box{padding:10px 8px}.info-box .v{font-size:12px}.info-box .k{font-size:9px}.assign-card{padding:14px 12px}.assign-card h3{font-size:13px}#assignForm>div:first-child{grid-template-columns:1fr!important;gap:10px}#assignForm label{font-size:9px}#assignForm select,#assignForm input{font-size:11px;height:34px;padding:0 10px}.assign-actions{flex-direction:column;gap:6px}.assign-actions .btn-primary,.assign-actions .btn{width:100%;justify-content:center}.table-wrap td:nth-child(3),.table-wrap th:nth-child(3){display:none}.table td,.table th{padding:7px 6px;font-size:10px}}
</style>
@endpush
