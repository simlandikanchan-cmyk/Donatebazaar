@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush

@extends('layouts.admin')

@section('sidebar_wallets', 'active')
@section('page_title', 'Wallet #' . $wallet->id)
@section('page_subtitle', optional($wallet->owner)->name . ' · ' . class_basename($wallet->owner_type))

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
    <div class="hero-tag"><span class="hero-tag-dot"></span>Wallet Ledger</div>
    <div class="hero-name">#{{ $wallet->id }}</div>
    <div class="hero-sub">{{ optional($wallet->owner)->name ?? 'Unknown' }} · {{ class_basename($wallet->owner_type) }}</div>
    <div class="hero-badges">
      <span class="hero-badge hb-teal">{{ $wallet->currency }}</span>
      <span class="hero-badge hb-purple">{{ $txStats['total'] }} transactions</span>
    </div>
  </div>
  <div class="hero-right">
    <div class="hero-actions">
      <a href="{{ route('admin.wallets.index') }}" class="hero-btn hero-btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/></svg>
        Wallets
      </a>
      @php $isOrg = $wallet->owner_type === App\Models\Organization::class; @endphp
      @if($isOrg && $wallet->owner)
        <a href="{{ route('admin.organizations.show', $wallet->owner) }}" class="hero-btn hero-btn-ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          View Org
        </a>
      @endif
    </div>
  </div>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px;">
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Balance</div><div class="stat-val sv-green">₹{{ number_format($wallet->balance, 2) }}</div><div class="stat-foot">{{ $wallet->currency }} · Available</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Reserved</div><div class="stat-val sv-amber">₹{{ number_format($wallet->reserved_balance, 2) }}</div><div class="stat-foot">Hold window</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-pink"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Locked in Settlement</div><div class="stat-val sv-pink">₹{{ number_format($wallet->pending_settlement_balance, 2) }}</div><div class="stat-foot">Pending approval</div></div>
  </div>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
  <div class="stat">
    <div class="stat-icon si-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total Txns</div><div class="stat-val sv-teal">{{ $txStats['total'] }}</div><div class="stat-foot">All time</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Credits</div><div class="stat-val sv-green">{{ $txStats['credits'] }}</div><div class="stat-foot">₹{{ number_format($txStats['total_credited'], 2) }} in</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m4 0h1M3 10l2-5h14l2 5v9a1 1 0 01-1 1H4a1 1 0 01-1-1v-9z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Debits</div><div class="stat-val sv-red">{{ $txStats['debits'] }}</div><div class="stat-foot">₹{{ number_format($txStats['total_debited'], 2) }} out</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Owner</div><div class="stat-val sv-blue" style="font-size:1.1rem;">{{ class_basename($wallet->owner_type) }}</div><div class="stat-foot">#{{ $wallet->owner_id }}</div></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:24px;align-items:start;">
  <div class="chart-card">
    <div class="chart-hdr">
      <div>
        <div class="chart-ttl">Manual Adjustment</div>
        <div class="chart-sub">Credit or debit this wallet directly</div>
      </div>
    </div>
    <form method="POST" action="{{ route('admin.wallets.adjust', $wallet) }}" class="wa-form" style="padding:16px;">
      @csrf
      <div class="wa-field">
        <label>Direction</label>
        <select name="direction" class="wa-select">
          <option value="credit">Credit (+)</option>
          <option value="debit">Debit (−)</option>
        </select>
      </div>
      <div class="wa-field">
        <label>Amount</label>
        <input type="number" step="0.01" name="amount" placeholder="0.00" required class="wa-input">
      </div>
      <div class="wa-field">
        <label>Reason</label>
        <input type="text" name="notes" placeholder="Reason (required)" required class="wa-input">
      </div>
      <div class="wa-field" style="align-self:end;">
        <button type="submit" class="wa-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Apply
        </button>
      </div>
    </form>
  </div>

  @if($isOrg && $payoutAccounts->isNotEmpty())
  <div class="chart-card">
    <div class="chart-hdr">
      <div>
        <div class="chart-ttl">Payout Accounts</div>
        <div class="chart-sub">Bank/UPI accounts for this organization</div>
      </div>
    </div>
    <div style="padding:16px;">
      @foreach($payoutAccounts as $pa)
      <div style="display:flex;gap:12px;flex-wrap:wrap;@if(!$loop->first) margin-top:12px;padding-top:12px;border-top:1px solid var(--border);@endif">
        <div style="width:100%;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
          <span style="font-size:13px;font-weight:600;">{{ $pa->account_holder_name }}</span>
          @if($pa->is_verified)
            <span class="pill pill-approved">Verified</span>
            <form method="POST" action="{{ route('admin.payout-accounts.unverify', $pa) }}" style="display:inline;">
              @csrf
              <button type="submit" class="act-btn act-del" style="height:auto;padding:3px 10px;font-size:11px;">Unverify</button>
            </form>
          @else
            <span class="pill pill-pending">Pending</span>
            <form method="POST" action="{{ route('admin.payout-accounts.verify', $pa) }}" style="display:inline;">
              @csrf
              <button type="submit" class="act-btn ab-view" style="height:auto;padding:3px 10px;font-size:11px;">Mark Verified</button>
            </form>
          @endif
        </div>
        @if($pa->bank_name)
        <div style="padding:8px 12px;background:var(--bg);border-radius:10px;flex:1;min-width:120px;">
          <div style="font-size:10px;color:var(--text3);font-weight:500;margin-bottom:2px;text-transform:uppercase;letter-spacing:.05em;font-family:var(--mono);">Bank</div>
          <div style="font-size:12px;font-weight:600;">{{ $pa->bank_name }}</div>
        </div>
        <div style="padding:8px 12px;background:var(--bg);border-radius:10px;flex:1;min-width:120px;">
          <div style="font-size:10px;color:var(--text3);font-weight:500;margin-bottom:2px;text-transform:uppercase;letter-spacing:.05em;font-family:var(--mono);">Account</div>
          <div style="font-size:12px;font-family:var(--mono);">{{ $pa->masked_account_number }}</div>
        </div>
        @endif
        @if($pa->upi_id)
        <div style="padding:8px 12px;background:var(--bg);border-radius:10px;flex:1;min-width:120px;">
          <div style="font-size:10px;color:var(--text3);font-weight:500;margin-bottom:2px;text-transform:uppercase;letter-spacing:.05em;font-family:var(--mono);">UPI ID</div>
          <div style="font-size:12px;font-family:var(--mono);">{{ $pa->upi_id }}</div>
        </div>
        @endif
        @if($pa->verified_at)
          <div style="font-size:11px;color:var(--text3);width:100%;margin-top:2px;">
            Verified {{ $pa->verified_at->format('d M Y H:i') }}
            @if($pa->verifiedBy) by {{ $pa->verifiedBy->name }} @endif
          </div>
        @endif
      </div>
      @endforeach
    </div>
  </div>
  @endif
</div>

<div class="table-card">
  <div class="table-card-head">
    <div class="table-card-head-left">
      <div class="table-card-icon" style="background:var(--a-lt);color:var(--a);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
      </div>
      <div>
        <div class="table-card-title">Transaction History</div>
        <div class="table-card-sub">Full ledger for this wallet</div>
      </div>
    </div>
  </div>
  <div class="table-scroll">
    <table id="txTable">
      <thead>
        <tr>
          <th>Date</th>
          <th>Type</th>
          <th>Source</th>
          <th style="text-align:right">Amount</th>
          <th style="text-align:right">Balance After</th>
          <th>Reference</th>
          <th>Notes</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $tx)
          @php
            $txTypeConfig = [
              'credit' => ['color' => 'var(--green)', 'bg' => 'var(--green-lt)', 'border' => 'rgba(5,196,138,.2)', 'label' => 'Credit'],
              'debit' => ['color' => 'var(--red)', 'bg' => 'var(--red-lt)', 'border' => 'rgba(240,68,68,.2)', 'label' => 'Debit'],
              'adjustment' => ['color' => 'var(--a)', 'bg' => 'var(--a-lt)', 'border' => 'rgba(37,99,235,.2)', 'label' => 'Adjustment'],
            ];
            $txC = $txTypeConfig[$tx->type] ?? ['color' => 'var(--text)', 'bg' => 'var(--surface2)', 'border' => 'var(--border)', 'label' => ucfirst($tx->type)];
          @endphp
          <tr>
            <td class="cell-date" data-label="Date">
              {{ $tx->created_at->format('Y-m-d') }}
              <div class="cell-date-sub">{{ $tx->created_at->format('H:i') }}</div>
            </td>
            <td data-label="Type">
              <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:100px;font-size:11px;font-weight:700;font-family:var(--mono);background:{{ $txC['bg'] }};color:{{ $txC['color'] }};border:1px solid {{ $txC['border'] }};">{{ $txC['label'] }}</span>
            </td>
            <td data-label="Source" style="color:var(--text3);">{{ ucfirst(str_replace('_', ' ', $tx->source ?? '—')) }}</td>
            <td data-label="Amount" class="td-mono" style="text-align:right;font-weight:700;color:{{ $tx->type === 'credit' ? 'var(--green)' : ($tx->type === 'debit' ? 'var(--red)' : 'var(--a)') }};">₹{{ number_format($tx->amount, 2) }}</td>
            <td data-label="Balance After" class="td-mono" style="text-align:right;">₹{{ number_format($tx->balance_after, 2) }}</td>
            <td data-label="Reference" class="cell-mono" style="font-size:11px;">
              @if($tx->reference_type && $tx->reference_id)
                {{ class_basename($tx->reference_type) }} #{{ $tx->reference_id }}
              @else
                <span style="color:var(--text3);">—</span>
              @endif
            </td>
            <td data-label="Notes" style="color:var(--text3);font-size:12px;">{{ $tx->notes ?? '—' }}</td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="7">
              <div class="empty-inner">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <strong>No transactions yet</strong>
                <span>This wallet has no transaction history.</span>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="table-footer">
    <div class="tfoot-info">
      Showing <strong>{{ $transactions->firstItem() ?? 0 }}</strong>–<strong>{{ $transactions->lastItem() ?? 0 }}</strong> of <strong>{{ $transactions->total() }}</strong> transactions
    </div>
    {{ $transactions->links('vendor.pagination.admin') }}
  </div>
</div>

@push('page_styles')
<style>
@media(max-width:1024px){
  .stats-grid{grid-template-columns:repeat(2,1fr)!important}
}
@media(max-width:768px){
  .stats-grid{grid-template-columns:1fr!important}
  .hero-name{font-size:20px}
  .hero-sub{font-size:12px}
  #txTable thead{display:none}
  #txTable tbody tr{display:flex;flex-direction:column;padding:14px 16px;border-bottom:1px solid var(--border);gap:8px}
  #txTable tbody tr td{padding:0;border:none;display:flex;align-items:center;gap:8px}
  #txTable tbody tr td::before{content:attr(data-label);font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);min-width:75px;flex-shrink:0}
  #txTable td[data-label="Actions"]{flex-wrap:wrap}
  #txTable td[data-label="Actions"]::before{content:"Actions";min-width:auto;margin-right:auto}
}
@media(max-width:640px){
  .hero-actions{width:100%}
  .hero-btn{flex:1;justify-content:center}
}
</style>
@endpush

@endsection
