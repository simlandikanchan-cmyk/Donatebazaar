@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush

@extends('layouts.admin')

@section('sidebar_gift_cards', 'active')
@section('page_title', 'Gift Cards')
@section('page_subtitle', 'Manage gift card orders')

@push('page_styles')
<style>
/* ── action buttons (text pills, NOT the 32px icon .gc-action-btn) ── */
.gc-amount{color:var(--green)}
.ab-view{background:var(--a-lt);color:var(--a);border-color:rgba(37,99,235,.2)}
.ab-view:hover{background:var(--a);color:#fff;box-shadow:0 4px 14px rgba(37,99,235,.35)}
.ab-resend{background:rgba(59,130,246,.1);color:#1d4ed8;border-color:rgba(59,130,246,.2)}
.ab-resend:hover{background:#1d4ed8;color:#fff;border-color:#1d4ed8}
.ab-cancel{background:rgba(239,68,68,.1);color:#991b1b;border-color:rgba(239,68,68,.2)}
.ab-cancel:hover{background:#dc2626;color:#fff;border-color:#dc2626}

/* ── filter form: stretch search input, keep buttons snug ── */
.gc-filter-form{align-items:center}
.gc-filter-form .swrap{flex:2;min-width:220px;position:relative}
.gc-filter-form .sinp{width:100%}
.gc-filter-form .sinp:focus{width:100%}
.gc-filter-form .gc-select{flex:0 1 160px}
.gc-filter-form .gc-btn-primary,.gc-filter-form .gc-btn-clear{flex:0 0 auto}

/* ── responsive stats ── */
@media(max-width:960px){.gc-stats-grid{grid-template-columns:repeat(3,1fr)!important}}
@media(max-width:640px){.gc-stats-grid{grid-template-columns:repeat(2,1fr)!important;gap:12px}}
@media(max-width:440px){.gc-stats-grid{grid-template-columns:1fr!important}}

/* ── table scroll ── */
@media(max-width:960px){
  .gc-table{min-width:720px}
}
@media(max-width:640px){
  .gc-scroll{overflow-x:auto}
  .gc-actions{flex-direction:column;gap:4px}
  .gc-actions .btn{width:100%;justify-content:center}
}
</style>
@endpush

@section('content')

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Finance</div>
    <div class="hero-name">Gift Cards</div>
    <div class="hero-sub">Track, resend, and manage gift card orders from donors.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-teal">{{ $stats['total'] }} total</span>
      @if($stats['pending'] > 0)
        <span class="hero-badge hb-amber">● {{ $stats['pending'] }} pending</span>
      @endif
      @if($stats['redeemed'] > 0)
        <span class="hero-badge hb-green">✓ {{ $stats['redeemed'] }} redeemed</span>
      @endif
      <span class="hero-badge hb-purple">₹{{ number_format($stats['revenue'], 0) }} revenue</span>
    </div>
  </div>
</div>

{{-- Stats --}}
<div class="gc-stats-grid">
  <div class="stat" onclick="location.href='{{ route('admin.gift-cards.index') }}'" style="cursor:pointer">
    <div class="stat-icon si-gray"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total</div><div class="stat-val sv-gray">{{ $stats['total'] }}</div><div class="stat-foot">All gift cards</div></div>
  </div>
  <div class="stat" onclick="location.href='{{ route('admin.gift-cards.index', ['status' => 'pending']) }}'" style="cursor:pointer">
    <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Pending</div><div class="stat-val sv-amber">{{ $stats['pending'] }}</div><div class="stat-foot">Awaiting send</div></div>
  </div>
  <div class="stat" onclick="location.href='{{ route('admin.gift-cards.index', ['status' => 'sent']) }}'" style="cursor:pointer">
    <div class="stat-icon si-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Sent</div><div class="stat-val sv-blue">{{ $stats['sent'] }}</div><div class="stat-foot">Email delivered</div></div>
  </div>
  <div class="stat" onclick="location.href='{{ route('admin.gift-cards.index', ['status' => 'redeemed']) }}'" style="cursor:pointer">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Redeemed</div><div class="stat-val sv-green">{{ $stats['redeemed'] }}</div><div class="stat-foot">Used by recipient</div></div>
  </div>
  <div class="stat" onclick="location.href='{{ route('admin.gift-cards.index', ['status' => 'expired']) }}'" style="cursor:pointer">
    <div class="stat-icon si-slate"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Expired</div><div class="stat-val sv-slate">{{ $stats['expired'] }}</div><div class="stat-foot">Past validity</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Revenue</div><div class="stat-val sv-green">₹{{ number_format($stats['revenue'], 0) }}</div><div class="stat-foot">Paid orders</div></div>
  </div>
</div>

{{-- Filters --}}
<form method="GET" class="gc-filter-form" role="search">
  <label for="gc-search" class="gc-visually-hidden">Search gift cards</label>
  <div class="swrap">
    <svg class="sico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    <input id="gc-search" class="sinp" type="text" name="search" value="{{ $search }}"
           placeholder="Search code, name, email…">
  </div>

  <label for="gc-status" class="gc-visually-hidden">Filter by status</label>
  <select id="gc-status" class="gc-select" name="status" onchange="this.form.submit()">
    @foreach(['all','pending','sent','redeemed','expired','cancelled'] as $s)
    <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
    @endforeach
  </select>

  <button type="submit" class="gc-btn-primary">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    Search
  </button>

  @if($search || $status !== 'all')
  <a href="{{ route('admin.gift-cards.index') }}" class="gc-btn-clear">Clear filters</a>
  @endif
</form>

{{-- Flash --}}
@if(session('success'))
<div class="gc-flash-success">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

{{-- Table --}}
<div class="gc-table-wrap">
  <div class="gc-scroll">
    <table class="gc-table">
      <thead>
        <tr>
          @foreach(['Code','Amount','Sender','Recipient','Theme','Status','Payment','Send Date','Actions'] as $h)
          <th scope="col">{{ $h }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @forelse($giftCards as $gc)
        @php
        $statusColors = [
            'pending'   => ['bg'=>'rgba(245,158,11,0.15)','color'=>'#b45309'],
            'sent'      => ['bg'=>'rgba(59,130,246,0.15)', 'color'=>'#1d4ed8'],
            'redeemed'  => ['bg'=>'rgba(16,185,129,0.15)', 'color'=>'#065f46'],
            'expired'   => ['bg'=>'rgba(156,163,175,0.15)','color'=>'#6b7280'],
            'cancelled' => ['bg'=>'rgba(239,68,68,0.15)',  'color'=>'#991b1b'],
        ];
        $sc = $statusColors[$gc->status] ?? $statusColors['pending'];
        $themeColors = ['purple'=>'#6366f1','teal'=>'#10b981','coral'=>'#ef4444','blue'=>'#3b82f6'];
        $paymentOk = $gc->payment_status === 'completed';
        @endphp
        <tr>
          <td class="gc-code">{{ $gc->code }}</td>
          <td class="gc-amount">₹{{ number_format($gc->amount, 0) }}</td>
          <td>
            <div class="gc-primary-name">{{ $gc->sender_name }}</div>
            <div class="gc-secondary-email">{{ $gc->sender_email }}</div>
          </td>
          <td>
            <div class="gc-primary-name">{{ $gc->recipient_name }}</div>
            <div class="gc-secondary-email">{{ $gc->recipient_email }}</div>
          </td>
          <td>
            <span class="gc-theme-dot" style="background:{{ $themeColors[$gc->theme] ?? '#6366f1' }};"></span>
            <span class="gc-theme-label">{{ ucfirst($gc->theme) }}</span>
          </td>
          <td>
            <span class="gc-badge" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};">{{ $gc->status }}</span>
          </td>
          <td>
            <span class="gc-badge" style="background:{{ $paymentOk ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.12)' }};color:{{ $paymentOk ? '#065f46' : '#991b1b' }};">
              {{ $gc->payment_status }}
            </span>
          </td>
          <td class="gc-date">{{ $gc->send_at->format('d M Y') }}</td>
          <td>
            <div class="gc-actions">
              <a href="{{ route('admin.gift-cards.show', $gc->id) }}"
                 class="btn btn-secondary act-btn ab-view"
                 aria-label="View gift card {{ $gc->code }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
              </a>
              @if($gc->isPaid() && !$gc->isRedeemed())
              <form method="POST" action="{{ route('admin.gift-cards.resend', $gc->id) }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-secondary act-btn ab-resend"
                        aria-label="Resend gift card {{ $gc->code }}">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                  Resend
                </button>
              </form>
              @endif
              @if(!$gc->isRedeemed())
              <form method="POST" action="{{ route('admin.gift-cards.destroy', $gc->id) }}"
                    onsubmit="return confirm('Cancel this gift card?')" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-secondary act-btn ab-cancel"
                        aria-label="Cancel gift card {{ $gc->code }}">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                  Cancel
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" class="gc-empty">
            @if($search || $status !== 'all')
              No gift cards match your filters. <a href="{{ route('admin.gift-cards.index') }}">Clear filters</a> to see all.
            @else
              No gift cards found.
            @endif
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($giftCards->hasPages())
  <div class="gc-pagination" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <span class="tfoot-info" style="font-size:12px;color:var(--text3);font-family:var(--mono);">
      Showing <strong style="color:var(--text);">{{ $giftCards->firstItem() }}</strong>–<strong style="color:var(--text);">{{ $giftCards->lastItem() }}</strong> of <strong style="color:var(--text);">{{ $giftCards->total() }}</strong>
    </span>
    {{ $giftCards->appends(request()->query())->links('vendor.pagination.admin') }}
  </div>
  @endif
</div>
@endsection