@extends('layouts.user')

@section('page_title', 'KYC Verification')
@section('page_subtitle', 'Manage identity verification across all your campaigns')

@push('page_styles')
<style>
.kyc-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-size:10.5px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;font-family:var(--mono);white-space:nowrap;}
.kyc-chip-none{background:var(--surface2);color:var(--text3);border:1px solid var(--border2);}
.kyc-chip-pending{background:rgba(245,158,11,0.12);color:#f59e0b;border:1px solid rgba(245,158,11,0.3);}
.kyc-chip-approved{background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.3);}
.kyc-chip-rejected{background:rgba(239,68,68,0.12);color:#ef4444;border:1px solid rgba(239,68,68,0.3);}
.status-chip{display:inline-flex;align-items:center;gap:6px;padding:4px 11px;border-radius:100px;font-size:10px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;font-family:var(--mono);white-space:nowrap;}
.status-chip .dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;}
.chip-active{background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.25);}
.chip-paused{background:rgba(99,102,241,0.12);color:#818cf8;border:1px solid rgba(99,102,241,0.25);}
.chip-pending{background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.3);}
.chip-rejected{background:rgba(239,68,68,0.12);color:#ef4444;border:1px solid rgba(239,68,68,0.25);}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;animation:fadeUp .4s both;}
.card+.card{margin-top:16px;}
.card-header{padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;}
.card-header-left{display:flex;align-items:center;gap:10px;}
.card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:14px;height:14px;}
.ic-indigo{background:rgba(99,102,241,0.12);color:var(--accent);}
.ic-green{background:rgba(16,185,129,0.12);color:var(--green);}
.ic-yellow{background:rgba(245,158,11,0.12);color:var(--yellow);}
.ic-red{background:rgba(239,68,68,0.12);color:var(--red);}
.card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-0.01em;}
.card-sub{font-size:11px;color:var(--text3);margin-top:1px;}
.card-body{padding:18px;}
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:16px 18px;display:flex;align-items:center;gap:13px;animation:fadeUp .4s both;cursor:pointer;transition:border-color var(--tr),transform var(--tr);}
.stat-card:hover{transform:translateY(-1px);border-color:var(--border2);}
.stat-card.is-active{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:17px;height:17px;}
.stat-num{font-size:21px;font-weight:800;color:var(--text);letter-spacing:-0.02em;line-height:1.1;}
.stat-lbl{font-size:10.5px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:0.06em;margin-top:2px;}
.filter-bar{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:14px;}
.filter-tabs{display:flex;align-items:center;gap:5px;flex-wrap:wrap;}
.filter-tab{padding:7px 13px;border-radius:100px;font-size:11.5px;font-weight:600;font-family:var(--font);border:1px solid var(--border2);background:var(--surface);color:var(--text2);transition:all var(--tr);white-space:nowrap;}
.filter-tab:hover{border-color:var(--accent);color:var(--accent);}
.filter-tab.active{background:var(--accent);border-color:var(--accent);color:#fff;}
.campaign-list{display:flex;flex-direction:column;gap:10px;}
.campaign-row{display:flex;align-items:center;gap:14px;padding:14px 16px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);box-shadow:var(--shadow);transition:border-color var(--tr),transform var(--tr);animation:fadeUp .4s both;}
.campaign-row:hover{border-color:var(--border2);transform:translateY(-1px);}
.row-avatar{width:42px;height:42px;border-radius:11px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;font-size:15px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.row-info{flex:1;min-width:0;}
.row-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-0.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.row-meta{display:flex;align-items:center;gap:8px;margin-top:3px;flex-wrap:wrap;}
.row-meta-item{font-size:11px;color:var(--text3);font-family:var(--mono);display:flex;align-items:center;gap:4px;}
.row-meta-item svg{width:11px;height:11px;}
.row-chips{display:flex;align-items:center;gap:7px;flex-shrink:0;}
.row-actions{display:flex;align-items:center;gap:7px;flex-shrink:0;}
.row-link-icon{width:32px;height:32px;border-radius:8px;border:1px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--text2);transition:all var(--tr);flex-shrink:0;}
.row-link-icon:hover{background:var(--accent-glow);color:var(--accent);border-color:var(--accent);}
.row-link-icon svg{width:13px;height:13px;}
.empty-state{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;padding:60px 20px;text-align:center;}
.empty-state svg{width:48px;height:48px;color:var(--text3);opacity:0.25;}
.empty-state h3{font-size:15px;font-weight:700;color:var(--text2);}
.empty-state p{font-size:12px;color:var(--text3);max-width:320px;line-height:1.6;}
.empty-state .btn{padding:10px 20px;font-size:12.5px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:none;}}
@media(max-width:960px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:600px){.filter-bar{flex-direction:column;align-items:stretch;}}
@media(max-width:480px){.stat-grid{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')
@php
    $total              = $campaigns->count();
    $verifiedCount      = $campaigns->filter(fn($c) => optional($c->kyc)->status === 'approved')->count();
    $pendingCount       = $campaigns->filter(fn($c) => optional($c->kyc)->status === 'pending')->count();
    $rejectedCount      = $campaigns->filter(fn($c) => optional($c->kyc)->status === 'rejected')->count();
    $notSubmittedCount  = $campaigns->filter(fn($c) => !$c->kyc)->count();
@endphp

{{-- ══ STAT CARDS (double as filters) ══ --}}
<div class="stat-grid">
    <div class="stat-card is-active" data-filter="all">
        <div class="stat-icon ic-indigo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div>
            <div class="stat-num">{{ $total }}</div>
            <div class="stat-lbl">All Campaigns</div>
        </div>
    </div>
    <div class="stat-card" data-filter="approved">
        <div class="stat-icon ic-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="stat-num">{{ $verifiedCount }}</div>
            <div class="stat-lbl">Verified</div>
        </div>
    </div>
    <div class="stat-card" data-filter="pending">
        <div class="stat-icon ic-yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
        </div>
        <div>
            <div class="stat-num">{{ $pendingCount }}</div>
            <div class="stat-lbl">Pending Review</div>
        </div>
    </div>
    <div class="stat-card" data-filter="action">
        <div class="stat-icon ic-red">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        </div>
        <div>
            <div class="stat-num">{{ $rejectedCount + $notSubmittedCount }}</div>
            <div class="stat-lbl">Needs Attention</div>
        </div>
    </div>
</div>

@if($total > 0)
{{-- ══ FILTER BAR ══ --}}
<div class="filter-bar">
    <div class="filter-tabs" id="filterTabs">
        <button class="filter-tab active" data-filter="all">All</button>
        <button class="filter-tab" data-filter="approved">Verified</button>
        <button class="filter-tab" data-filter="pending">Pending</button>
        <button class="filter-tab" data-filter="rejected">Rejected</button>
        <button class="filter-tab" data-filter="none">Not Submitted</button>
    </div>
    <div class="search-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-3.5-3.5"/></svg>
        <input type="text" class="search-input" id="searchInput" placeholder="Search campaigns…">
    </div>
</div>
@endif

{{-- ══ CAMPAIGN LIST ══ --}}
@if($total > 0)
<div class="campaign-list" id="campaignList">
    @foreach($campaigns as $campaign)
        @php
            $kyc = $campaign->kyc;
            $kStatus = $kyc->status ?? 'none';
        @endphp
        <div class="campaign-row" data-kyc="{{ $kStatus }}" data-title="{{ strtolower($campaign->title) }}">
            <div class="row-avatar">{{ strtoupper(substr($campaign->title, 0, 1)) }}</div>

            <div class="row-info">
                <div class="row-title">{{ $campaign->title }}</div>
                <div class="row-meta">
                    <span class="row-meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $campaign->created_at->format('d M Y') }}
                    </span>
                    @if($kyc)
                    <span class="row-meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5"/></svg>
                        Submitted {{ $kyc->created_at->diffForHumans() }}
                    </span>
                    @endif
                </div>
            </div>

            <div class="row-chips">
                <span class="status-chip chip-{{ in_array($campaign->campaign_state, ['approved','live','active']) ? 'active' : ($campaign->campaign_state ?? 'pending') }}">
                    <span class="dot"></span>{{ ucfirst($campaign->campaign_state ?? 'Draft') }}
                </span>
                <span class="kyc-chip kyc-chip-{{ $kStatus }}">
                    @if($kStatus === 'none') Not Submitted
                    @elseif($kStatus === 'pending') Pending
                    @elseif($kStatus === 'approved') ✓ Verified
                    @else ✗ Rejected
                    @endif
                </span>
            </div>

            <div class="row-actions">
                @if($kStatus === 'none')
                    <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Upload KYC
                    </a>
                @elseif($kStatus === 'rejected')
                    <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="btn btn-yellow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Re-upload
                    </a>
                    <a href="{{ route('kyc.view', $campaign->id) }}" class="row-link-icon" title="View Submission">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                @else
                    <a href="{{ route('kyc.view', $campaign->id) }}" class="btn btn-secondary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        {{ $kStatus === 'pending' ? 'View Submission' : 'View Documents' }}
                    </a>
                @endif
                <a href="{{ route('campaign.show', $campaign->id) }}" class="row-link-icon" title="Campaign Overview">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </a>
            </div>
        </div>
    @endforeach
</div>

<div class="empty-state" id="noResults" style="display:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-3.5-3.5"/></svg>
    <h3>No matching campaigns</h3>
    <p>Try a different filter or search term.</p>
</div>

@else
{{-- ══ NO CAMPAIGNS AT ALL ══ --}}
<div class="card">
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        <h3>No campaigns yet</h3>
        <p>Create your first campaign to start the KYC verification process and get approved for fundraising.</p>
        <a href="{{ route('campaign.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Create Campaign
        </a>
    </div>
</div>
@endif
@endsection

@push('page_scripts')
<script>
var currentFilter = 'all';
var searchInput = document.getElementById('searchInput');
var rows = Array.prototype.slice.call(document.querySelectorAll('.campaign-row'));
var noResults = document.getElementById('noResults');

function matchesFilter(kyc, filter) {
    if (filter === 'all') return true;
    if (filter === 'action') return kyc === 'rejected' || kyc === 'none';
    return kyc === filter;
}

function applyFilters() {
    var term = (searchInput?.value || '').trim().toLowerCase();
    var visibleCount = 0;
    rows.forEach(function(row){
        var kyc = row.getAttribute('data-kyc');
        var title = row.getAttribute('data-title') || '';
        var show = matchesFilter(kyc, currentFilter) && title.indexOf(term) !== -1;
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    if (noResults) noResults.style.display = (visibleCount === 0 && rows.length > 0) ? 'flex' : 'none';
}

function setActiveStatCard(filter) {
    document.querySelectorAll('.stat-card').forEach(function(card){
        card.classList.toggle('is-active', card.getAttribute('data-filter') === filter);
    });
}
function setActiveTab(filter) {
    document.querySelectorAll('.filter-tab').forEach(function(tab){
        tab.classList.toggle('active', tab.getAttribute('data-filter') === filter);
    });
}

document.querySelectorAll('.stat-card').forEach(function(card){
    card.addEventListener('click', function(){
        var filter = this.getAttribute('data-filter');
        currentFilter = filter;
        setActiveStatCard(filter);
        setActiveTab(filter);
        applyFilters();
    });
});
document.querySelectorAll('.filter-tab').forEach(function(tab){
    tab.addEventListener('click', function(){
        var filter = this.getAttribute('data-filter');
        currentFilter = filter;
        setActiveTab(filter);
        setActiveStatCard(filter === 'rejected' || filter === 'none' ? 'action' : filter);
        applyFilters();
    });
});
searchInput?.addEventListener('input', applyFilters);
</script>
@endpush
