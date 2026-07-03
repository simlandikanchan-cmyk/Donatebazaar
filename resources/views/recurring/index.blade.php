@extends('layouts.user')

@section('page_title', 'Recurring Donations')
@section('page_subtitle', 'Track, manage and control all your recurring contribution plans')

@section('content')
    @php
        $rdAll       = $recurring->getCollection() ?? collect();
        $rdTotal     = $recurring->total();
        $rdActive    = $rdAll->where('status','active')->count();
        $rdPaused    = $rdAll->where('status','paused')->count();
        $rdCancelled = $rdAll->where('status','cancelled')->count();
    @endphp

    <div class="stat-grid">
        <div class="stat-card is-active" data-filter="all">
            <div class="stat-icon ic-indigo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <div>
                <div class="stat-num">{{ $rdTotal }}</div>
                <div class="stat-lbl">All Plans</div>
            </div>
        </div>
        <div class="stat-card" data-filter="active">
            <div class="stat-icon ic-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="stat-num">{{ $rdActive }}</div>
                <div class="stat-lbl">Active</div>
            </div>
        </div>
        <div class="stat-card" data-filter="paused">
            <div class="stat-icon ic-yellow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="stat-num">{{ $rdPaused }}</div>
                <div class="stat-lbl">Paused</div>
            </div>
        </div>
        <div class="stat-card" data-filter="cancelled">
            <div class="stat-icon ic-red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div>
                <div class="stat-num">{{ $rdCancelled }}</div>
                <div class="stat-lbl">Cancelled</div>
            </div>
        </div>
    </div>

    @if($rdTotal > 0)
    <div class="filter-bar">
        <div class="filter-tabs" id="filterTabs">
            <button class="filter-tab active" data-filter="all">All</button>
            <button class="filter-tab" data-filter="active">Active</button>
            <button class="filter-tab" data-filter="paused">Paused</button>
            <button class="filter-tab" data-filter="cancelled">Cancelled</button>
        </div>
        <div class="search-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-3.5-3.5"/></svg>
            <input type="text" class="search-input" id="searchInput" placeholder="Search by campaign…">
        </div>
    </div>
    @endif

    @if($rdTotal > 0)
    <div class="rd-list" id="rdList">
        @foreach($recurring as $donation)
            <div class="rd-row" data-status="{{ $donation->status }}" data-title="{{ strtolower($donation->campaign->title ?? 'campaign') }}">

                <div class="rd-avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-4.35-9-8.5C1 8 3.5 4 7.5 4c2.04 0 3.04 1 4.5 2.5C13.46 5 14.46 4 16.5 4 20.5 4 23 8 21 12.5 19 16.65 12 21 12 21z"/></svg>
                </div>

                <div class="rd-info">
                    <div class="rd-title">{{ $donation->campaign->title ?? 'Campaign' }}</div>
                    <div class="rd-meta">
                        <span class="rd-amount">₹{{ number_format($donation->amount, 2) }}</span>
                        <span class="rd-meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <strong>{{ ucfirst($donation->frequency) }}</strong>
                        </span>
                        <span class="rd-meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            {{ $donation->billing_count }} payment{{ $donation->billing_count == 1 ? '' : 's' }} made
                        </span>
                        @if($donation->status !== 'cancelled')
                        <span class="rd-meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Next: <strong>{{ optional($donation->next_billing_date)?->format('d M Y') ?? '—' }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="rd-chips">
                    <span class="status-chip chip-{{ $donation->status }}">
                        <span class="dot"></span>{{ ucfirst($donation->status) }}
                    </span>
                </div>

                <div class="rd-actions">
                    @if($donation->status === 'active')
                    <form action="{{ route('recurring.pause', $donation->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-yellow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                            Pause
                        </button>
                    </form>
                    @endif

                    @if($donation->status === 'paused')
                    <form action="{{ route('recurring.resume', $donation->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            Resume
                        </button>
                    </form>
                    @endif

                    @if($donation->status !== 'cancelled')
                    <form action="{{ route('recurring.cancel', $donation->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-red" onclick="return confirm('Cancel this recurring donation?')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            Cancel
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if($recurring->hasPages())
    <div class="rd-pagination">
        {{ $recurring->links() }}
    </div>
    @endif

    <div class="empty-state" id="noResults" style="display:none;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-3.5-3.5"/></svg>
        <h3>No matching plans</h3>
        <p>Try a different filter or search term.</p>
    </div>

    @else
    <div class="card">
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-4.35-9-8.5C1 8 3.5 4 7.5 4c2.04 0 3.04 1 4.5 2.5C13.46 5 14.46 4 16.5 4 20.5 4 23 8 21 12.5 19 16.65 12 21 12 21z"/></svg>
            <h3>No Recurring Donations Yet</h3>
            <p>Start supporting campaigns with recurring contributions and see them tracked here.</p>
            <a href="/all-campaigns" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                Explore Campaigns
            </a>
        </div>
    </div>
    @endif
@endsection

@push('page_styles')
<style>
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;animation:fadeUp .4s both;}
.card+.card{margin-top:16px;}
.card-body{padding:18px;}

.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:16px 18px;display:flex;align-items:center;gap:13px;animation:fadeUp .4s both;cursor:pointer;transition:border-color var(--tr),transform var(--tr);}
.stat-card:hover{transform:translateY(-1px);border-color:var(--border2);}
.stat-card.is-active{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:17px;height:17px;}
.ic-indigo{background:rgba(99,102,241,0.12);color:var(--accent);}
.ic-green{background:rgba(16,185,129,0.12);color:var(--green);}
.ic-yellow{background:rgba(245,158,11,0.12);color:var(--yellow);}
.ic-red{background:rgba(239,68,68,0.12);color:var(--red);}
.stat-num{font-size:21px;font-weight:800;color:var(--text);letter-spacing:-0.02em;line-height:1.1;}
.stat-lbl{font-size:10.5px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:0.06em;margin-top:2px;}

.filter-bar{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:14px;}
.filter-tabs{display:flex;align-items:center;gap:5px;flex-wrap:wrap;}
.filter-tab{padding:7px 13px;border-radius:100px;font-size:11.5px;font-weight:600;font-family:var(--font);border:1px solid var(--border2);background:var(--surface);color:var(--text2);transition:all var(--tr);white-space:nowrap;}
.filter-tab:hover{border-color:var(--accent);color:var(--accent);}
.filter-tab.active{background:var(--accent);border-color:var(--accent);color:#fff;}
.search-wrap{position:relative;min-width:220px;flex-shrink:0;}
.search-wrap svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text3);}
.search-input{width:100%;padding:8px 12px 8px 32px;border-radius:100px;border:1.5px solid var(--border2);background:var(--surface);color:var(--text);font-family:var(--font);font-size:12.5px;outline:none;transition:border-color var(--tr);}
.search-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.search-input::placeholder{color:var(--text3);}

.rd-list{display:flex;flex-direction:column;gap:10px;}
.rd-row{display:flex;align-items:center;gap:14px;padding:14px 16px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);box-shadow:var(--shadow);transition:border-color var(--tr),transform var(--tr);animation:fadeUp .4s both;}
.rd-row:hover{border-color:var(--border2);transform:translateY(-1px);}
.rd-avatar{width:42px;height:42px;border-radius:11px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.rd-avatar svg{width:18px;height:18px;}
.rd-info{flex:1;min-width:0;}
.rd-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-0.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.rd-meta{display:flex;align-items:center;gap:12px;margin-top:5px;flex-wrap:wrap;}
.rd-meta-item{font-size:11px;color:var(--text3);font-family:var(--mono);display:flex;align-items:center;gap:4px;}
.rd-meta-item svg{width:11px;height:11px;flex-shrink:0;}
.rd-meta-item strong{color:var(--text2);font-weight:600;}
.rd-amount{font-size:15px;font-weight:800;color:var(--accent);font-family:var(--mono);}
.rd-chips{display:flex;align-items:center;gap:7px;flex-shrink:0;}
.rd-actions{display:flex;align-items:center;gap:7px;flex-shrink:0;flex-wrap:wrap;justify-content:flex-end;}
.status-chip{display:inline-flex;align-items:center;gap:6px;padding:4px 11px;border-radius:100px;font-size:10px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;font-family:var(--mono);white-space:nowrap;}
.status-chip .dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;}
.chip-active{background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.25);}
.chip-paused{background:rgba(245,158,11,0.12);color:#d97706;border:1px solid rgba(245,158,11,0.3);}
.chip-cancelled{background:rgba(239,68,68,0.12);color:#ef4444;border:1px solid rgba(239,68,68,0.25);}
[data-theme="dark"] .chip-paused{color:#fbbf24;}

.empty-state{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;padding:60px 20px;text-align:center;}
.empty-state svg{width:48px;height:48px;color:var(--text3);opacity:0.25;}
.empty-state h3{font-size:15px;font-weight:700;color:var(--text2);}
.empty-state p{font-size:12px;color:var(--text3);max-width:320px;line-height:1.6;}
.rd-pagination{display:flex;justify-content:center;margin-top:22px;}
.rd-pagination :is(.pagination){display:flex;gap:6px;list-style:none;flex-wrap:wrap;}
.rd-pagination :is(.page-item .page-link){display:flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;border-radius:var(--radius-sm);border:1px solid var(--border2);background:var(--surface);color:var(--text2);font-size:12px;font-weight:600;font-family:var(--mono);transition:all var(--tr);}
.rd-pagination :is(.page-item .page-link:hover){border-color:var(--accent);color:var(--accent);}
.rd-pagination :is(.page-item.active .page-link){background:var(--accent);border-color:var(--accent);color:#fff;}
.rd-pagination :is(.page-item.disabled .page-link){opacity:.4;cursor:not-allowed;}

@media(max-width:960px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:860px){
    .rd-row{flex-wrap:wrap;}
    .rd-info{flex-basis:100%;order:1;}
    .rd-chips{order:2;}
    .rd-actions{order:3;margin-left:auto;}
}
@media(max-width:600px){.stat-grid{grid-template-columns:1fr 1fr;}.filter-bar{flex-direction:column;align-items:stretch;}.search-wrap{min-width:0;}}
@media(max-width:480px){.stat-grid{grid-template-columns:1fr;}}
</style>
@endpush

@push('page_scripts')
<script>
var currentFilter = 'all';
var searchInput = document.getElementById('searchInput');
var rows = Array.prototype.slice.call(document.querySelectorAll('.rd-row'));
var noResults = document.getElementById('noResults');

function matchesFilter(status, filter) {
    if (filter === 'all') return true;
    return status === filter;
}

function applyFilters() {
    var term = (searchInput?.value || '').trim().toLowerCase();
    var visibleCount = 0;
    rows.forEach(function(row){
        var status = row.getAttribute('data-status');
        var title = row.getAttribute('data-title') || '';
        var show = matchesFilter(status, currentFilter) && title.indexOf(term) !== -1;
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
        setActiveStatCard(filter);
        applyFilters();
    });
});

searchInput?.addEventListener('input', applyFilters);
</script>
@endpush
