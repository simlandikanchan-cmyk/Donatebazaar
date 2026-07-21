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
          <span class="hero-badge hb-purple">{{ $stats['total'] }} total</span>
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
        <div class="stat-icon si-purple">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Total</div>
          <div class="stat-val sv-purple">{{ $stats['total'] }}</div>
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
        <table>
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
              </td>
            </tr>
            @empty
            <tr class="empty-row">
<<<<<<< HEAD
              <td colspan="10">
=======
                <td colspan="9">
>>>>>>> origin/master
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

@endsection

@push('page_scripts')
<script>
(function () {
  const statesCities = @json(json_decode(file_get_contents(resource_path('js/data/in-states-cities.json')), true));

  const stateSel  = document.getElementById('filter-state');
  const cityInp   = document.getElementById('filter-city');
  const box       = document.getElementById('city-suggestions');
  if (!stateSel || !cityInp || !box) return;

  Object.keys(statesCities).sort().forEach(state => {
    const opt = document.createElement('option');
    opt.value = state;
    opt.textContent = state;
    if (state === @json(request('state'))) opt.selected = true;
    stateSel.appendChild(opt);
  });

  function pool() {
    const s = stateSel.value;
    return (s && statesCities[s]) ? statesCities[s] : Object.values(statesCities).flat();
  }

  function render(q) {
    const list = pool().filter(n => n.toLowerCase().startsWith(q.toLowerCase())).slice(0, 12);
    box.innerHTML = '';
    if (!list.length) { box.style.display = 'none'; return; }
    list.forEach(name => {
      const el = document.createElement('div');
      el.className = 'city-suggestion';
      el.style.padding = '9px 12px';
      el.style.cursor = 'pointer';
      el.style.fontSize = '12.5px';
      el.textContent = name;
      el.addEventListener('mousedown', e => {
        e.preventDefault();
        cityInp.value = name;
        box.style.display = 'none';
      });
      box.appendChild(el);
    });
    box.style.display = 'block';
  }

  cityInp.addEventListener('input', () => render(cityInp.value.trim()));
  cityInp.addEventListener('focus', () => render(cityInp.value.trim()));
  stateSel.addEventListener('change', () => { cityInp.value = ''; render(''); });
  document.addEventListener('click', e => {
    if (!box.contains(e.target) && e.target !== cityInp) box.style.display = 'none';
  });
})();
</script>
@endpush

@push('page_styles')
<style>
.stats-grid{grid-template-columns:repeat(4,1fr);}

.stat:nth-child(1){animation-delay:.05s;}.stat:nth-child(1)::after{background:linear-gradient(90deg,var(--a),#6366f1);}
.stat:nth-child(2){animation-delay:.10s;}.stat:nth-child(2)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat:nth-child(3){animation-delay:.15s;}.stat:nth-child(3)::after{background:linear-gradient(90deg,var(--amber),#f97316);}
.stat:nth-child(4){animation-delay:.20s;}.stat:nth-child(4)::after{background:linear-gradient(90deg,var(--blue),#6366f1);}

.filter-bar{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:16px 20px;box-shadow:var(--sh);margin-bottom:20px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;animation:fadeUp .4s .1s ease both;}
.filter-inp,.filter-sel{height:36px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:0 12px;font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.filter-inp{width:320px;}
.filter-city-wrap{position:relative;display:flex;}
.filter-inp:focus,.filter-sel:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.filter-inp::placeholder{color:var(--text3);}
.filter-btn{height:36px;padding:0 18px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;font-family:var(--font);cursor:pointer;transition:opacity var(--ease),transform var(--ease);box-shadow:0 3px 10px rgba(37,99,235,.3);}
.filter-btn:hover{opacity:.88;transform:translateY(-1px);}
.filter-clear{height:36px;padding:0 14px;background:transparent;border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;color:var(--text3);font-family:var(--font);cursor:pointer;transition:all var(--ease);text-decoration:none;display:inline-flex;align-items:center;gap:5px;}
.filter-clear:hover{border-color:var(--red);color:var(--red);}
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-success{background:rgba(5,196,138,.1);border:1px solid rgba(5,196,138,.25);color:#059669;}
.flash-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);}
[data-theme="dark"] .flash-success{color:#34d399;}
[data-theme="dark"] .flash-error{color:#f87171;}
.flash svg{width:14px;height:14px;flex-shrink:0;}

.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .18s ease both;}
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead{background:var(--surface2);border-bottom:1px solid var(--border);}
thead th{padding:12px 16px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--text3);font-family:var(--mono);white-space:nowrap;}
tbody td{padding:14px 16px;border-bottom:1px solid var(--border);vertical-align:middle;}
tbody tr:last-child td{border-bottom:none;}
tbody tr{transition:background var(--ease);}
tbody tr:hover{background:var(--surface2);}
.cell-id{font-family:var(--mono);font-size:11px;color:var(--text3);font-weight:500;}
.applicant-name{font-size:13.5px;font-weight:600;color:var(--text);line-height:1.2;}
.applicant-email{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--mono);}
.cell-date{font-family:var(--mono);font-size:11.5px;color:var(--text3);}
.b-shortlisted{background:rgba(5,196,138,.85);color:#fff;}
.b-rejected{background:rgba(240,68,68,.12);color:var(--red);border:1px solid rgba(240,68,68,.22);}
.b-pending{background:rgba(245,158,11,.12);color:#b45309;border:1px solid rgba(245,158,11,.22);}
[data-theme="dark"] .b-pending{color:#fbbf24;}
[data-theme="dark"] .b-rejected{color:#f87171;}

.act-link{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:500;color:var(--a);background:var(--a-lt);border:1px solid rgba(37,99,235,.2);transition:all var(--ease);text-decoration:none;}
.act-link:hover{background:var(--a);color:#fff;border-color:var(--a);transform:translateY(-1px);}
.act-link svg{width:11px;height:11px;}
.no-cv{font-size:11px;color:var(--text3);font-family:var(--mono);}
.empty-row td{text-align:center;padding:56px 20px;}
.empty-inner{display:flex;flex-direction:column;align-items:center;gap:10px;}
.empty-inner svg{width:48px;height:48px;color:var(--text3);opacity:.25;}
.empty-inner strong{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2);}
.empty-inner span{font-size:13px;color:var(--text3);}
.hero-badge.hb-purple{background:rgba(37,99,235,.12);color:var(--a);border-color:rgba(37,99,235,.22);}
[data-theme="dark"] .hero-badge.hb-purple{color:#93c5fd;}

@media(max-width:860px){.search-wrap{display:none}}
<<<<<<< HEAD
@media(max-width:600px){.filter-bar{flex-direction:column;align-items:stretch}.filter-inp{width:100%}}
@media(max-width:380px){.stats-grid{grid-template-columns:1fr;gap:8px;}.stat{padding:10px 12px;}.stat-icon{width:30px;height:30px;}.stat-icon svg{width:13px;height:13px;}}
=======
@media(max-width:1100px){.stats-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:900px){.filter-inp{width:220px}.filter-city-wrap .filter-inp{width:170px}}
@media(max-width:640px){.stats-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.filter-bar{flex-direction:column;align-items:stretch}.filter-inp,.filter-city-wrap .filter-inp{width:100%}.filter-city-wrap{width:100%}.filter-btn{width:100%;justify-content:center}.filter-sel{width:100%}}
@media(max-width:540px){.stats-grid{grid-template-columns:1fr}.table-wrap td:nth-child(3),.table-wrap th:nth-child(3),.table-wrap td:nth-child(5),.table-wrap th:nth-child(5),.table-wrap td:nth-child(6),.table-wrap th:nth-child(6),.table-wrap td:nth-child(8),.table-wrap th:nth-child(8){display:none}.stat-gap{gap:8px}}
@media(max-width:480px){.table-wrap td:nth-child(7),.table-wrap th:nth-child(7){display:none}}
@media(max-width:380px){.page-hdr{padding:14px 12px}.page-hdr-left h2{font-size:clamp(15px,4.5vw,17px)}.stats-grid{grid-template-columns:1fr;gap:6px}.stat-card{padding:10px 12px;gap:8px}.stat-num{font-size:clamp(16px,4.5vw,18px)}.stat-lbl{font-size:9px}.filter-bar{padding:12px 10px;gap:6px}.filter-inp,.filter-sel,.filter-btn{height:32px;font-size:11px}.table-wrap td:nth-child(4),.table-wrap th:nth-child(4){display:none}.table td,.table th{padding:7px 5px;font-size:10px}.select-wrap .si{display:none}.filter-select{padding:0 22px 0 8px;background-position:right 5px center;background-size:11px}.pagination-wrap{flex-direction:column;gap:8px;padding:12px 14px}}
>>>>>>> origin/master
</style>
@endpush
