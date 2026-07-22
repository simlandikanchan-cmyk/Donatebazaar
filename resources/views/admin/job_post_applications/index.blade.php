@extends('layouts.admin')

@section('sidebar_job_applicants', 'active')
@section('page_title', 'Job Applications')
@section('page_subtitle', 'Review job applications')

@section('content')

    {{-- ── HERO ── --}}
    <div class="hero">
      <div class="hero-left">
        <div class="hero-tag"><span class="hero-tag-dot"></span>Job Board</div>
        <div class="hero-name">Job Applicants</div>
        <div class="hero-sub">Review and manage all submitted applications across every job post on DonateBazaar.</div>
        <div class="hero-badges">
          <span class="hero-badge hb-purple">{{ $stats['total'] }} total</span>
          @if($stats['pending'] > 0)
            <span class="hero-badge hb-amber">⏱ {{ $stats['pending'] }} pending</span>
          @endif
          @if($stats['shortlisted'] > 0)
            <span class="hero-badge hb-green">✓ {{ $stats['shortlisted'] }} shortlisted</span>
          @endif
          @if($stats['rejected'] > 0)
            <span class="hero-badge hb-red">✕ {{ $stats['rejected'] }} rejected</span>
          @endif
        </div>
      </div>
      <div class="hero-right">
        <a href="{{ route('admin.job_posts.create') }}" class="hero-btn hero-btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Post a Job
        </a>
        <a href="{{ route('admin.job_posts.index') }}" class="hero-btn hero-btn-ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          All Jobs
        </a>
      </div>
    </div>

    {{-- ── STATS ── --}}
    <div class="stats-grid">
      <div class="stat">
        <div class="stat-icon si-blue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Total</div>
          <div class="stat-val sv-blue">{{ $stats['total'] }}</div>
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
          <div class="stat-lbl">Shortlisted</div>
          <div class="stat-val sv-green">{{ $stats['shortlisted'] }}</div>
          <div class="stat-foot">Moved forward</div>
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

    {{-- ── FLASH ── --}}
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

    {{-- ── FILTER BAR ── --}}
    <form method="GET" action="{{ route('admin.job_post_applications.index') }}" class="filter-bar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;color:var(--text3);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
      <input class="filter-inp" type="text" name="search" placeholder="Search applicant name…" value="{{ request('search') }}">
      <select class="filter-sel" name="job_id">
        <option value="">All job posts</option>
        @foreach($jobPosts as $jp)
          <option value="{{ $jp->id }}" @selected(request('job_id') == $jp->id)>{{ $jp->title }}</option>
        @endforeach
      </select>
      <select class="filter-sel" name="status">
        <option value="">All statuses</option>
        <option value="pending"     @selected(request('status') === 'pending')>Pending</option>
        <option value="shortlisted" @selected(request('status') === 'shortlisted')>Shortlisted</option>
        <option value="rejected"    @selected(request('status') === 'rejected')>Rejected</option>
        <option value="hired"       @selected(request('status') === 'hired')>Hired</option>
      </select>
      <button type="submit" class="filter-btn">Apply Filters</button>
      @if(request('search') || request('status') || request('job_id'))
        <a href="{{ route('admin.job_post_applications.index') }}" class="filter-clear">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          Clear
        </a>
      @endif
    </form>

    {{-- ── TABLE ── --}}
    <div class="sec-hdr">
      <div class="sec-ttl">All Job Applicants</div>
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
              <th>Job Post</th>
              <th>Status</th>
              <th>CV</th>
              <th>Applied</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($applications as $app)
            <tr data-name="{{ strtolower($app->name) }} {{ strtolower($app->email) }}">
              <td class="cell-id">{{ $app->id }}</td>
              <td>
                <div class="applicant-name">{{ $app->name }}</div>
                <div class="applicant-email">{{ $app->email }}</div>
              </td>
              <td>
                <div class="job-name">{{ $app->jobPost?->title ?? '—' }}</div>
                <div class="job-type">{{ ucfirst($app->jobPost?->type ?? '') }}</div>
              </td>
              <td>
                @php
                  $statusClass = match($app->status) {
                    'shortlisted' => 'b-shortlisted',
                    'rejected'    => 'b-rejected',
                    'hired'       => 'b-hired',
                    default       => 'b-pending',
                  };
                @endphp
                <span class="badge {{ $statusClass }}">{{ $app->status }}</span>
              </td>
              <td>
                @if($app->cv_path)
                  <a href="{{ route('admin.job_post_applications.downloadCv', $app) }}" class="cv-link" target="_blank">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    CV
                  </a>
                @else
                  <span class="no-cv">—</span>
                @endif
              </td>
              <td class="cell-date">{{ $app->created_at->format('d M Y') }}</td>
              <td>
                <a href="{{ route('admin.job_post_applications.show', $app) }}" class="act-link">
                  Review
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
              </td>
            </tr>
            @empty
            <tr class="empty-row">
              <td colspan="7">
                <div class="empty-inner">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                  <strong>No applications found</strong>
                  <span>No applications match your current filter or search.</span>
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

@push('page_styles')
<style>
.stats-grid{grid-template-columns:repeat(4,1fr);}
@media(max-width:860px){.stats-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:600px){.stats-grid{grid-template-columns:1fr;}}

.stat:nth-child(1){animation-delay:.05s;}.stat:nth-child(1)::after{background:linear-gradient(90deg,var(--blue),#6366f1);}
.stat:nth-child(2){animation-delay:.10s;}.stat:nth-child(2)::after{background:linear-gradient(90deg,var(--amber),#f97316);}
.stat:nth-child(3){animation-delay:.15s;}.stat:nth-child(3)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat:nth-child(4){animation-delay:.20s;}.stat:nth-child(4)::after{background:linear-gradient(90deg,var(--red),#f87171);}

.si-red{background:var(--red-lt);color:var(--red);}
.sv-red{color:var(--red);}

/* ── FILTER BAR ── */
.filter-bar{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:16px 20px;box-shadow:var(--sh);margin-bottom:20px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;animation:fadeUp .4s .1s ease both;}
.filter-inp,.filter-sel{height:36px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:0 12px;font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.filter-inp{width:200px;}
.filter-inp:focus,.filter-sel:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.filter-inp::placeholder{color:var(--text3);}
.filter-sel{cursor:pointer;min-width:140px;}
.filter-btn{height:36px;padding:0 18px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;font-family:var(--font);cursor:pointer;transition:opacity var(--ease),transform var(--ease);box-shadow:0 3px 10px rgba(37,99,235,.3);}
.filter-btn:hover{opacity:.88;transform:translateY(-1px);}
.filter-clear{height:36px;padding:0 14px;background:transparent;border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;color:var(--text3);font-family:var(--font);cursor:pointer;transition:all var(--ease);text-decoration:none;display:inline-flex;align-items:center;gap:5px;}
.filter-clear:hover{border-color:var(--red);color:var(--red);}

/* ── TABLE CARD ── */
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .18s ease both;}
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead{background:var(--surface2);border-bottom:1px solid var(--border);}
thead th{padding:12px 16px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--text3);font-family:var(--mono);white-space:nowrap;}
tbody td{padding:14px 16px;border-bottom:1px solid var(--border);vertical-align:middle;}
tbody tr:last-child td{border-bottom:none;}
tbody tr{transition:background var(--ease);}
tbody tr:hover{background:var(--surface2);}

/* ── TABLE CELLS ── */
.cell-id{font-family:var(--mono);font-size:11px;color:var(--text3);font-weight:500;}
.applicant-name{font-size:13.5px;font-weight:600;color:var(--text);line-height:1.2;}
.applicant-email{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--mono);}
.job-name{font-size:13px;font-weight:600;color:var(--text);}
.job-type{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--mono);text-transform:capitalize;}
.cell-date{font-family:var(--mono);font-size:11.5px;color:var(--text3);}

/* ── BADGES ── */
.b-shortlisted{background:rgba(5,196,138,.85);color:#fff;}
.b-hired{background:rgba(37,99,235,.85);color:#fff;}

/* ── ACTION BUTTONS ── */
.act-link{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:500;color:var(--a);background:var(--a-lt);border:1px solid rgba(37,99,235,.2);transition:all var(--ease);text-decoration:none;}
.act-link:hover{background:var(--a);color:#fff;border-color:var(--a);transform:translateY(-1px);}
.act-link svg{width:11px;height:11px;}
.cv-link{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:500;color:var(--green);background:var(--green-lt);border:1px solid rgba(5,196,138,.2);transition:all var(--ease);text-decoration:none;}
.cv-link:hover{background:var(--green);color:#fff;border-color:var(--green);transform:translateY(-1px);}
.cv-link svg{width:11px;height:11px;}
.no-cv{font-size:11px;color:var(--text3);font-family:var(--mono);}

/* ── EMPTY STATE ── */
.empty-row td{text-align:center;padding:56px 20px;}
.empty-inner{display:flex;flex-direction:column;align-items:center;gap:10px;}
.empty-inner svg{width:48px;height:48px;color:var(--text3);opacity:.25;}
.empty-inner strong{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2);}
.empty-inner span{font-size:13px;color:var(--text3);}

/* ── FLASH ── */
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-success{background:rgba(5,196,138,.1);border:1px solid rgba(5,196,138,.25);color:#059669;}
.flash-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);}
[data-theme="dark"] .flash-success{color:#34d399;}
[data-theme="dark"] .flash-error{color:#f87171;}
.flash svg{width:14px;height:14px;flex-shrink:0;}

/* ── RESPONSIVE ── */
@media(max-width:960px){.stats-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:860px){.search-wrap{display:none}}
@media(max-width:600px){
  .filter-bar{flex-direction:column;align-items:stretch}
  .filter-inp,.filter-sel{width:100%}
  .filter-btn,.filter-clear{width:100%;justify-content:center}
}
@media(max-width:480px){
  .stats-grid{grid-template-columns:repeat(2,1fr);gap:8px}
  .stat{padding:14px 12px}
  .stat-icon{width:36px;height:36px}
  .stat-value{font-size:clamp(16px,4.5vw,18px)}
  .stat-label{font-size:10px}
  .table-wrap{padding:0}
  .table th,.table td{padding:8px 6px;font-size:11px}
  .table .col-created{display:none}
}
@media(max-width:380px){
  .stats-grid{grid-template-columns:1fr 1fr;gap:6px}
  .stat{padding:10px 8px}
  .stat-icon{width:30px;height:30px}
  .stat-value{font-size:clamp(14px,4vw,16px)}
  .stat-label{font-size:9px}
  .filter-bar{padding:12px 10px}
  .filter-inp,.filter-sel{font-size:11px;height:32px}
  .table .col-email,.table .col-status{display:none}
  .empty-state{padding:30px 16px}
  .flash{font-size:12px;padding:10px 12px}
  .applicant-name{font-size:11px}
  .applicant-email{font-size:10px}
  .job-name{font-size:11px}
  .cell-date{font-size:10px}
  .act-link{padding:3px 6px;font-size:10px}
}
</style>
@endpush

@push('page_scripts')
<script>
(function(){
'use strict';
var searchEl = document.getElementById('liveSearch');
if (searchEl) {
  var st;
  searchEl.addEventListener('input', function(){
    clearTimeout(st);
    var q = this.value.toLowerCase().trim();
    st = setTimeout(function(){
      document.querySelectorAll('#appTable tbody tr[data-name]').forEach(function(row){
        row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
      });
    }, 160);
  });
}
})();
</script>
@endpush
