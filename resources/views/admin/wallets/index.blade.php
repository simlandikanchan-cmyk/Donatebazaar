@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@vite('resources/css/admin/pages/wallets-index.css')
@endpush

@extends('layouts.admin')

@section('sidebar_wallets', 'active')
@section('page_title', 'Wallets')
@section('page_subtitle', 'All user & organization wallets')

@section('content')

@if(session('success'))
  <div style="display:flex;align-items:center;gap:8px;padding:12px 16px;border-radius:var(--r-sm);background:var(--green-lt);border:1px solid rgba(5,196,138,.2);color:var(--green);font-size:13px;font-weight:500;margin-bottom:18px;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div style="display:flex;align-items:center;gap:8px;padding:12px 16px;border-radius:var(--r-sm);background:var(--red-lt);border:1px solid rgba(240,68,68,.2);color:var(--red);font-size:13px;font-weight:500;margin-bottom:18px;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('error') }}
  </div>
@endif

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Finance</div>
    <div class="hero-name">Wallets</div>
    <div class="hero-sub">Monitor balances, reserved funds, and settlement locks across all users and organizations.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-teal">{{ $stats['total'] }} total</span>
      <span class="hero-badge hb-primary">{{ $stats['users'] }} users</span>
      <span class="hero-badge hb-amber">{{ $stats['organizations'] }} organizations</span>
    </div>
  </div>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
  <div class="stat">
    <div class="stat-icon si-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total Wallets</div><div class="stat-val sv-teal">{{ $stats['total'] }}</div><div class="stat-foot">All owners</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total Balance</div><div class="stat-val sv-green">₹{{ number_format($stats['total_balance'], 2) }}</div><div class="stat-foot">Across all wallets</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">User Wallets</div><div class="stat-val sv-primary">{{ $stats['users'] }}</div><div class="stat-foot">Individual donors</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Org Wallets</div><div class="stat-val sv-blue">{{ $stats['organizations'] }}</div><div class="stat-foot">NGOs & partners</div></div>
  </div>
</div>

<div class="table-card">
  <div class="card-head">
    <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
    <span class="card-head-title">All Wallets</span>
    <span class="card-head-count">Manage wallet balances and settings</span>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-left:auto;">
      <div class="ftabs" id="walletFilterTabs" style="display:flex;gap:6px;">
        <button class="ftab {{ $filter === 'all' ? 'on' : '' }}" data-action="navigate" data-href="?filter=all">All <span class="cnt">{{ $stats['total'] }}</span></button>
        <button class="ftab {{ $filter === 'users' ? 'on' : '' }}" data-action="navigate" data-href="?filter=users">Users <span class="cnt">{{ $stats['users'] }}</span></button>
        <button class="ftab {{ $filter === 'organizations' ? 'on' : '' }}" data-action="navigate" data-href="?filter=organizations">Orgs <span class="cnt">{{ $stats['organizations'] }}</span></button>
      </div>
      <form method="GET" action="{{ route('admin.wallets.index') }}" style="display:flex;gap:8px;align-items:center;">
        <div class="swrap">
          <svg class="sico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Search owner name…" class="sinp" style="width:200px;">
        </div>
        @if(request('filter') && request('filter') !== 'all')
          <input type="hidden" name="filter" value="{{ request('filter') }}">
        @endif
        <button type="submit" class="cp-btn-primary" style="height:38px;">Search</button>
      </form>
    </div>
  </div>
  <div class="table-scroll">
    <table id="walletsTable" style="min-width:640px">
      <thead>
        <tr>
          <th style="width:60px">ID</th>
          <th>Owner</th>
          <th>Type</th>
          <th style="text-align:right">Balance</th>
          <th style="text-align:right">Reserved</th>
          <th style="text-align:right">Locked</th>
          <th style="text-align:center">Currency</th>
          <th style="text-align:right;width:100px">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($wallets as $w)
          @php
            $ownerType = class_basename($w->owner_type);
            $isUser = $w->owner_type === App\Models\User::class;
            $filterAttr = $isUser ? 'users' : 'organizations';
          @endphp
          <tr data-owner-type="{{ $filterAttr }}">
            <td class="cell-id" data-label="ID">{{ $w->id }}</td>
            <td data-label="Owner">
              <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;border-radius:8px;background:{{ $isUser ? 'var(--a-lt)' : 'var(--amber-lt)' }};color:{{ $isUser ? 'var(--a)' : 'var(--amber)' }};display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;font-family:var(--mono);flex-shrink:0;">
                  {{ strtoupper(substr(optional($w->owner)->name ?? '?', 0, 1)) }}
                </div>
                <div style="min-width:0;">
                  <div style="font-size:13px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ optional($w->owner)->name ?? '—' }}</div>
                </div>
              </div>
            </td>
            <td data-label="Type">
              @if($isUser)
                <span class="pill role-pill">User</span>
              @else
                <span class="pill cat-pill">Org</span>
              @endif
            </td>
            <td data-label="Balance" class="td-mono" style="text-align:right;color:var(--green);font-weight:700;">₹{{ number_format($w->balance, 2) }}</td>
            <td data-label="Reserved" class="td-mono" style="text-align:right;color:var(--amber);">₹{{ number_format($w->reserved_balance, 2) }}</td>
            <td data-label="Locked" class="td-mono" style="text-align:right;color:var(--pink);">₹{{ number_format($w->pending_settlement_balance, 2) }}</td>
            <td data-label="Currency" class="cell-mono" style="text-align:center;">{{ $w->currency }}</td>
            <td data-label="Actions" style="text-align:right;">
              <div class="action-btns">
                <a href="{{ route('admin.wallets.show', $w) }}" class="act-btn ab-view" title="View ledger">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                <form method="POST" action="{{ route('admin.wallets.destroy', $w) }}" style="display:inline-flex;gap:4px;align-items:center;" data-confirm="Delete this wallet? This cannot be undone.">
                  @csrf @method('DELETE')
                  <button type="submit" class="act-btn ab-delete" title="Delete">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="8">
              <div class="empty-inner">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <strong>No wallets found</strong>
                <span>Try adjusting your search or filters.</span>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div id="noResults" style="display:none;">
      <div class="empty-inner" style="padding:48px 20px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        <strong>No matching wallets</strong>
        <span>Try a different search term or filter.</span>
      </div>
    </div>
  </div>
  <div class="table-footer">
    <div class="tfoot-info">
      Showing <strong>{{ $wallets->firstItem() ?? 0 }}</strong>–<strong>{{ $wallets->lastItem() ?? 0 }}</strong> of <strong>{{ $wallets->total() }}</strong> wallets
    </div>
    {{ $wallets->links('vendor.pagination.admin') }}
  </div>
</div>

@push('page_scripts')
@vite('resources/js/admin/entries/wallets-index.js')
@endpush

@endsection
