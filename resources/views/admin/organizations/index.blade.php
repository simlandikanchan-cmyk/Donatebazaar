@push('page_css')
@vite('resources/css/admin/entries/organizations.css')
@endpush

@extends('layouts.admin')

@section('page_title', 'NGOs')
@section('page_subtitle', 'Browse all registered organizations')
@section('sidebar_organizations', 'active')

@push('page_styles')
<style>
.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.toolbar-left{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.search-wrap{position:relative;}
.search-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;}
.search-input{width:220px;height:36px;padding:0 12px 0 33px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease),width .3s ease;}
.search-input::placeholder{color:var(--text3);}
.search-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);width:260px;}
.select-wrap{position:relative;}
.select-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;z-index:1;}
.filter-select{height:36px;padding:0 30px 0 33px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text2);font-family:var(--font);outline:none;cursor:pointer;transition:all var(--ease);appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 9px center;background-size:13px;}
.filter-select:hover,.filter-select:focus{border-color:var(--a);color:var(--a);background-color:var(--a-lt);box-shadow:0 0 0 3px var(--a-glow);}
.toolbar-right{display:flex;align-items:center;gap:8px;}
.export-btn{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 14px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);text-decoration:none;cursor:pointer;font-family:var(--font);transition:all var(--ease);}
.export-btn:hover{border-color:var(--green);color:var(--green);background:rgba(5,196,138,.06);}
.export-btn svg{width:13px;height:13px;}

.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .15s ease both;}
.card-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.card-head-left{display:flex;align-items:center;gap:10px;}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);}

.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{padding:10px 16px;text-align:left;font-size:10px;font-family:var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--text3);background:var(--surface2);border-bottom:1px solid var(--border);font-weight:500;white-space:nowrap;}
thead th:last-child{text-align:right;}
tbody td{padding:12px 16px;font-size:13px;color:var(--text2);border-bottom:1px solid var(--border);vertical-align:middle;}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
.ngo-name{font-weight:600;color:var(--text);}
.ngo-sub{font-size:11px;color:var(--text3);}
.ngo-link{color:var(--a);text-decoration:none;}
.ngo-link:hover{text-decoration:underline;}
.view-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.2);border-radius:var(--r-sm);font-size:12px;font-weight:500;text-decoration:none;transition:all var(--ease);white-space:nowrap;cursor:pointer;font-family:var(--font);line-height:1.3;}
.view-btn:hover{background:var(--a);color:#fff;box-shadow:0 4px 14px rgba(37,99,235,.35);}
.view-btn svg{width:13px;height:13px;flex-shrink:0;}
.pill{display:inline-flex;align-items:center;padding:2px 9px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.04em;}
.pill-pending{background:rgba(245,158,11,.12);color:#d9870b;}
.pill-under_review{background:rgba(99,102,241,.12);color:#6366f1;}
.pill-approved{background:rgba(5,196,138,.12);color:#059d6e;}
.pill-rejected{background:rgba(239,68,68,.12);color:#ef4444;}
.pill-draft{background:rgba(148,163,184,.14);color:#64748b;}
.empty-state{padding:48px 20px;text-align:center;color:var(--text3);}
.empty-state svg{width:40px;height:40px;margin-bottom:10px;opacity:.5;}

.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:16px 18px;position:relative;overflow:hidden;animation:fadeUp .4s ease both;display:flex;align-items:center;gap:14px;}
.stat::after{content:'';position:absolute;top:0;left:0;right:0;height:3px;opacity:.6;}
.stat:nth-child(1){animation-delay:.05s;}.stat:nth-child(1)::after{background:linear-gradient(90deg,var(--amber),#fbbf24);}
.stat:nth-child(2){animation-delay:.10s;}.stat:nth-child(2)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat:nth-child(3){animation-delay:.15s;}.stat:nth-child(3)::after{background:linear-gradient(90deg,var(--red),#f87171);}
.stat:nth-child(4){animation-delay:.20s;}.stat:nth-child(4)::after{background:linear-gradient(90deg,var(--a),var(--a2));}
.stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:16px;height:16px;}
.si-amber{background:rgba(245,158,11,.10);color:var(--amber);}
.si-green{background:rgba(5,196,138,.10);color:var(--green);}
.si-red{background:rgba(240,68,68,.08);color:var(--red);}
.si-a{background:var(--a-lt);color:var(--a);}
.stat-body{flex:1;min-width:0;}
.stat-lbl{font-size:10px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;color:var(--text3);margin-bottom:2px;}
.stat-val{font-size:20px;font-weight:800;font-family:var(--mono);letter-spacing:-.03em;line-height:1.1;margin-bottom:1px;}
.sv-amber{color:var(--amber);}
.sv-green{color:var(--green);}
.sv-red{color:var(--red);}
.sv-a{color:var(--a);}
.stat-foot{font-size:10px;color:var(--text3);}
@media(max-width:860px){.stats-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:640px){.toolbar{flex-direction:column;align-items:stretch}.toolbar-left{flex-wrap:wrap;gap:6px}.search-wrap{flex:1;min-width:0}.search-input{width:100%}.search-input:focus{width:100%}.select-wrap{flex:1;min-width:0}.filter-select{width:100%}}
@media(max-width:540px){.stat{padding:12px 14px}.stat-icon{width:34px;height:34px;border-radius:9px}.stat-icon svg{width:14px;height:14px}.stat-val{font-size:16px}.card-head{flex-direction:column;align-items:flex-start;gap:6px}}
@media(max-width:480px){.stats-grid{grid-template-columns:1fr}}
@media(max-width:380px){
  .stats-grid{grid-template-columns:1fr;gap:8px;}
  .stat{padding:10px 12px;gap:8px;}
  .stat-icon{width:30px;height:30px;border-radius:8px;}
  .stat-icon svg{width:13px;height:13px;}
  .stat-val{font-size:1.1rem;}
  .toolbar{gap:8px;}
  .toolbar-left{flex-direction:column;align-items:stretch;gap:6px;}
  .search-input,.search-input:focus{width:100%;}
  .select-wrap{width:100%;}
  .filter-select{width:100%;}
  .toolbar-right{width:100%;}
  .export-btn{width:100%;justify-content:center;}
  .card-head{padding:10px 14px;}
  td{padding:10px 12px;font-size:12px;}
  thead th{padding:8px 12px;font-size:9px;}
}
.alert-ok{background:rgba(5,196,138,.08);border:1px solid rgba(5,196,138,.22);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease;}
.alert-ok svg{width:15px;height:15px;flex-shrink:0;}
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert-ok" id="flashAlert">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

{{-- HERO --}}
<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>NGOs</div>
    <div class="hero-name">Organizations</div>
    <div class="hero-sub">Browse all registered organizations and track their onboarding status.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-blue">Total {{ $organizations->total() }}</span>
      <span class="hero-badge hb-amber">Pending {{ $cntPending }}</span>
      <span class="hero-badge hb-purple">Under Review {{ $cntReview }}</span>
      <span class="hero-badge hb-green">Approved {{ $cntApproved }}</span>
      <span class="hero-badge hb-red">Rejected {{ $cntRejected }}</span>
    </div>
  </div>
  <div class="hero-right">
    <a href="{{ route('admin.organizations.create') }}" class="hero-btn hero-btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Onboard NGO
    </a>
  </div>
</div>

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
              <a class="btn btn-secondary act-btn ab-view" href="{{ route('admin.applications.show', $org->id) }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
              </a>
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
