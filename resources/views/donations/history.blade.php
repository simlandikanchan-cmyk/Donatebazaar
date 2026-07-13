@extends('layouts.user')

@section('page_title', 'Donation History')
@section('page_subtitle', 'All your contributions in one place')

@section('content')
@php
    $dhAll       = $donations->total();
    $dhCompleted = $completedCount;
    $dhPending   = $pendingCount;
    $dhTotal     = $totalDonated;
@endphp

<div class="dh-stats">
    <div class="dh-stat is-active" data-filter="all">
        <div class="dh-stat-icon si-indigo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
            <div class="dh-stat-num">{{ $dhAll }}</div>
            <div class="dh-stat-lbl">All Donations</div>
        </div>
    </div>
    <div class="dh-stat" data-filter="completed">
        <div class="dh-stat-icon si-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="dh-stat-num">{{ $dhCompleted }}</div>
            <div class="dh-stat-lbl">Completed</div>
        </div>
    </div>
    <div class="dh-stat" data-filter="pending">
        <div class="dh-stat-icon si-yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
            <div class="dh-stat-num">{{ $dhPending }}</div>
            <div class="dh-stat-lbl">Pending</div>
        </div>
    </div>
    <div class="dh-stat" data-filter="total">
        <div class="dh-stat-icon si-pink">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="dh-stat-num">₹{{ number_format($dhTotal, 0) }}</div>
            <div class="dh-stat-lbl">Total Donated</div>
        </div>
    </div>
</div>

@if($dhAll > 0)
<div class="dh-filter-bar">
    <div class="dh-filter-tabs" id="filterTabs">
        <button class="dh-filter-tab active" data-filter="all">All</button>
        <button class="dh-filter-tab" data-filter="completed">Completed</button>
        <button class="dh-filter-tab" data-filter="pending">Pending</button>
        <button class="dh-filter-tab" data-filter="failed">Failed</button>
        <button class="dh-filter-tab" data-filter="refunded">Refunded</button>
    </div>
    <div class="dh-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-3.5-3.5"/></svg>
        <input type="text" class="dh-search-input" id="searchInput" placeholder="Search by campaign…">
    </div>
</div>

<div class="dh-list" id="dhList">
    @foreach($donations as $donation)
    <div class="dh-row" data-status="{{ $donation->payment_status }}" data-title="{{ strtolower($donation->campaign->title ?? '') }}">
        <div class="dh-avatar">
            @if($donation->campaign && $donation->campaign->cover_image)
                <img src="{{ asset('storage/'.$donation->campaign->cover_image) }}" alt="">
            @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            @endif
        </div>
        <div class="dh-info">
            <div class="dh-title">{{ $donation->campaign->title ?? 'Campaign' }}</div>
            <div class="dh-meta">
                <span class="dh-amount">₹{{ number_format($donation->total_amount, 2) }}</span>
                @if($donation->donation_type === 'product')
                <span class="dh-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Product
                </span>
                @endif
                <span class="dh-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ $donation->created_at->format('d M Y, h:i A') }}
                </span>
                @if($donation->receipt_number)
                <span class="dh-meta-item" style="font-family:var(--mono);font-size:10px;color:var(--text3);">
                    #{{ $donation->receipt_number }}
                </span>
                @endif
            </div>
        </div>
        <div class="dh-chips">
            <span class="dh-chip chip-{{ $donation->payment_status }}">
                <span class="dot"></span>{{ ucfirst($donation->payment_status) }}
            </span>
        </div>
        <div class="dh-actions">
            @if($donation->payment_status === 'completed')
            <a href="{{ route('donation.receipt', $donation->id) }}" class="btn btn-accent" target="_blank">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Receipt
            </a>
            @endif
            <a href="{{ $donation->campaign?->slug ? url('/campaigns/'.($donation->campaign->category->slug ?? 'general').'/'.$donation->campaign->slug) : '#' }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
            </a>
        </div>
    </div>
    @endforeach
</div>

@if($donations->hasPages())
<div class="dh-pagination">
    {{ $donations->links() }}
</div>
@endif

<div class="dh-empty" id="noResults" style="display:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-3.5-3.5"/></svg>
    <h3>No matching donations</h3>
    <p>Try a different filter or search term.</p>
</div>

@else
<div class="dh-empty">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    <h3>No Donations Yet</h3>
    <p>When you donate to a campaign, it will appear here with receipt details.</p>
    <a href="{{ route('all.campaigns') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Explore Campaigns
    </a>
</div>
@endif

@endsection

@push('page_styles')
<style>
.dh-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
.dh-stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);padding:16px 18px;display:flex;align-items:center;gap:13px;animation:fadeUp .4s both;cursor:pointer;transition:border-color var(--ease),transform var(--ease);}
.dh-stat:hover{transform:translateY(-1px);border-color:var(--border2);}
.dh-stat.is-active{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.dh-stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.dh-stat-icon svg{width:17px;height:17px;}
.si-indigo{background:rgba(99,102,241,0.12);color:var(--accent);}
.si-green{background:rgba(16,185,129,0.12);color:var(--green);}
.si-yellow{background:rgba(245,158,11,0.12);color:var(--yellow);}
.si-pink{background:rgba(236,72,153,0.12);color:var(--pink);}
.dh-stat-num{font-size:21px;font-weight:800;color:var(--text);letter-spacing:-0.02em;line-height:1.1;}
.dh-stat-lbl{font-size:10.5px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:0.06em;margin-top:2px;}

.dh-filter-bar{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:14px;}
.dh-filter-tabs{display:flex;align-items:center;gap:5px;flex-wrap:wrap;}
.dh-filter-tab{padding:7px 13px;border-radius:100px;font-size:11.5px;font-weight:600;font-family:var(--font);border:1px solid var(--border2);background:var(--surface);color:var(--text2);transition:all var(--ease);white-space:nowrap;cursor:pointer;}
.dh-filter-tab:hover{border-color:var(--accent);color:var(--accent);}
.dh-filter-tab.active{background:var(--accent);border-color:var(--accent);color:#fff;}
.dh-search{position:relative;min-width:220px;flex-shrink:0;}
.dh-search svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text3);}
.dh-search-input{width:100%;padding:8px 12px 8px 32px;border-radius:100px;border:1.5px solid var(--border2);background:var(--surface);color:var(--text);font-family:var(--font);font-size:12.5px;outline:none;transition:border-color var(--ease);}
.dh-search-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.dh-search-input::placeholder{color:var(--text3);}

.dh-list{display:flex;flex-direction:column;gap:10px;}
.dh-row{display:flex;align-items:center;gap:14px;padding:14px 16px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r-sm);box-shadow:var(--sh);transition:border-color var(--ease),transform var(--ease);animation:fadeUp .4s both;}
.dh-row:hover{border-color:var(--border2);transform:translateY(-1px);}
.dh-avatar{width:42px;height:42px;border-radius:11px;background:var(--surface2);color:var(--text3);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
.dh-avatar img{width:100%;height:100%;object-fit:cover;}
.dh-avatar svg{width:18px;height:18px;opacity:.5;}
.dh-info{flex:1;min-width:0;}
.dh-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-0.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.dh-meta{display:flex;align-items:center;gap:12px;margin-top:5px;flex-wrap:wrap;}
.dh-meta-item{font-size:11px;color:var(--text3);font-family:var(--mono);display:flex;align-items:center;gap:4px;}
.dh-meta-item svg{width:11px;height:11px;flex-shrink:0;}
.dh-amount{font-size:15px;font-weight:800;color:var(--accent);font-family:var(--mono);}
.dh-chips{display:flex;align-items:center;gap:7px;flex-shrink:0;}
.dh-chip{display:inline-flex;align-items:center;gap:6px;padding:4px 11px;border-radius:100px;font-size:10px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;font-family:var(--mono);white-space:nowrap;}
.dh-chip .dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;}
.chip-completed{background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.25);}
.chip-pending{background:rgba(245,158,11,0.12);color:#d97706;border:1px solid rgba(245,158,11,0.3);}
.chip-failed{background:rgba(239,68,68,0.12);color:#ef4444;border:1px solid rgba(239,68,68,0.25);}
.chip-refunded{background:rgba(107,114,128,0.12);color:#6b7280;border:1px solid rgba(107,114,128,0.25);}
[data-theme="dark"] .chip-pending{color:#fbbf24;}
[data-theme="dark"] .chip-refunded{color:#9ca3af;}

.dh-actions{display:flex;align-items:center;gap:7px;flex-shrink:0;flex-wrap:wrap;justify-content:flex-end;}

.dh-empty{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;padding:60px 20px;text-align:center;}
.dh-empty svg{width:48px;height:48px;color:var(--text3);opacity:0.25;}
.dh-empty h3{font-size:15px;font-weight:700;color:var(--text2);}
.dh-empty p{font-size:12px;color:var(--text3);max-width:320px;line-height:1.6;}
.dh-empty .btn{padding:10px 20px;font-size:12.5px;}
.dh-empty .btn svg{width:13px;height:13px;opacity:1;}
.dh-pagination{display:flex;justify-content:center;margin-top:22px;}
.dh-pagination :is(.pagination){display:flex;gap:6px;list-style:none;flex-wrap:wrap;}
.dh-pagination :is(.page-item .page-link){display:flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface);color:var(--text2);font-size:12px;font-weight:600;font-family:var(--mono);transition:all var(--ease);}
.dh-pagination :is(.page-item .page-link:hover){border-color:var(--accent);color:var(--accent);}
.dh-pagination :is(.page-item.active .page-link){background:var(--accent);border-color:var(--accent);color:#fff;}
.dh-pagination :is(.page-item.disabled .page-link){opacity:.4;cursor:not-allowed;}

@media(max-width:960px){.dh-stats{grid-template-columns:repeat(2,1fr);}}
@media(max-width:860px){
    .dh-row{flex-wrap:wrap;}
    .dh-info{flex-basis:100%;order:1;}
    .dh-chips{order:2;}
    .dh-actions{order:3;margin-left:auto;}
}
@media(max-width:600px){.dh-stats{grid-template-columns:1fr 1fr;}.dh-filter-bar{flex-direction:column;align-items:stretch;}.dh-search{min-width:0;}}
@media(max-width:480px){.dh-stats{grid-template-columns:1fr;}}
</style>
@endpush

@push('page_scripts')
<script>
var currentFilter = 'all';
var searchInput = document.getElementById('searchInput');
var rows = Array.prototype.slice.call(document.querySelectorAll('.dh-row'));
var noResults = document.getElementById('noResults');

function applyFilters() {
    var term = (searchInput?.value || '').trim().toLowerCase();
    var visibleCount = 0;
    rows.forEach(function(row){
        var status = row.getAttribute('data-status');
        var title = row.getAttribute('data-title') || '';
        var show = (currentFilter === 'all' || status === currentFilter) && title.indexOf(term) !== -1;
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    if (noResults) noResults.style.display = (visibleCount === 0 && rows.length > 0) ? 'flex' : 'none';
}

document.querySelectorAll('.dh-stat').forEach(function(card){
    card.addEventListener('click', function(){
        var filter = this.getAttribute('data-filter');
        currentFilter = filter;
        document.querySelectorAll('.dh-stat').forEach(function(c){ c.classList.toggle('is-active', c.getAttribute('data-filter') === filter); });
        document.querySelectorAll('.dh-filter-tab').forEach(function(t){ t.classList.toggle('active', t.getAttribute('data-filter') === filter); });
        applyFilters();
    });
});

document.querySelectorAll('.dh-filter-tab').forEach(function(tab){
    tab.addEventListener('click', function(){
        var filter = this.getAttribute('data-filter');
        currentFilter = filter;
        document.querySelectorAll('.dh-filter-tab').forEach(function(t){ t.classList.toggle('active', t.getAttribute('data-filter') === filter); });
        document.querySelectorAll('.dh-stat').forEach(function(c){ c.classList.toggle('is-active', c.getAttribute('data-filter') === filter); });
        applyFilters();
    });
});

searchInput?.addEventListener('input', applyFilters);
</script>
@endpush
