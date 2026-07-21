@extends('layouts.admin')

@section('sidebar_wallets', 'active')
@section('page_title', 'Wallet #' . $wallet->id . ' Ledger')
@section('page_subtitle', optional($wallet->owner)->name . ' (' . class_basename($wallet->owner_type) . ')')

@section('content')

@if(session('success'))
  <div style="padding:12px 16px;border-radius:12px;background:var(--green-lt);color:var(--green);font-size:13px;font-weight:500;margin-bottom:16px;">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div style="padding:12px 16px;border-radius:12px;background:var(--red-lt);color:var(--red);font-size:13px;font-weight:500;margin-bottom:16px;">{{ session('error') }}</div>
@endif

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px;">
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Balance</div><div class="stat-val sv-green">₹{{ number_format($wallet->balance, 2) }}</div><div class="stat-foot">{{ $wallet->currency }}</div></div>
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

<div class="chart-card" style="margin-bottom:24px;">
  <div class="chart-hdr">
    <div>
      <div class="chart-ttl">Manual Adjustment</div>
      <div class="chart-sub">Credit or debit this wallet directly</div>
    </div>
  </div>
  <form method="POST" action="{{ route('admin.wallets.adjust', $wallet) }}" style="padding:16px;display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
    @csrf
    <div>
      <label style="display:block;font-size:11px;color:var(--text3);margin-bottom:4px;font-weight:500;">Direction</label>
      <select name="direction" style="padding:7px 12px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:13px;">
        <option value="credit">Credit (+)</option>
        <option value="debit">Debit (−)</option>
      </select>
    </div>
    <div>
      <label style="display:block;font-size:11px;color:var(--text3);margin-bottom:4px;font-weight:500;">Amount</label>
      <input type="number" step="0.01" name="amount" placeholder="Amount" required
             style="padding:7px 12px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:13px;width:140px;">
    </div>
    <div>
      <label style="display:block;font-size:11px;color:var(--text3);margin-bottom:4px;font-weight:500;">Reason</label>
      <input type="text" name="notes" placeholder="Reason (required)" required
             style="padding:7px 12px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:13px;width:220px;">
    </div>
    <button type="submit" style="padding:7px 18px;border-radius:8px;border:none;background:var(--a);color:#fff;font-size:13px;font-weight:600;cursor:pointer;">Apply</button>
  </form>
</div>

@if($payoutAccounts->isNotEmpty())
<div class="chart-card" style="margin-bottom:24px;">
  <div class="chart-hdr">
    <div>
      <div class="chart-ttl">Payout Accounts</div>
      <div class="chart-sub">Bank/UPI accounts for this organization</div>
    </div>
  </div>
  <div style="padding:16px;">
    @foreach($payoutAccounts as $pa)
    <div style="display:flex;gap:16px;flex-wrap:wrap;@if(!$loop->first) margin-top:14px;padding-top:14px;border-top:1px solid var(--border);@endif">
      <div style="display:flex;align-items:center;gap:8px;width:100%;margin-bottom:4px;">
        <span style="font-size:11px;color:var(--text3);font-weight:500;">Account Holder</span>
        <span style="font-size:13px;font-weight:600;">{{ $pa->account_holder_name }}</span>
        @if($pa->is_verified)
          <span style="font-size:10px;padding:1px 7px;border-radius:4px;background:var(--green-lt);color:var(--green);font-weight:600;">Verified</span>
          <form method="POST" action="{{ route('admin.payout-accounts.unverify', $pa) }}" style="display:inline;">
            @csrf
            <button type="submit" style="font-size:10px;padding:2px 8px;border-radius:4px;border:1px solid var(--red);background:transparent;color:var(--red);font-weight:600;cursor:pointer;">Unverify</button>
          </form>
        @else
          <span style="font-size:10px;padding:1px 7px;border-radius:4px;background:var(--yellow-lt);color:var(--yellow);font-weight:600;">Pending</span>
          <form method="POST" action="{{ route('admin.payout-accounts.verify', $pa) }}" style="display:inline;">
            @csrf
            <button type="submit" style="font-size:10px;padding:2px 8px;border-radius:4px;border:1px solid var(--green);background:transparent;color:var(--green);font-weight:600;cursor:pointer;">Mark Verified</button>
          </form>
        @endif
      </div>
      @if($pa->bank_name)
      <div style="padding:8px 12px;background:var(--bg);border-radius:10px;flex:1;min-width:140px;">
        <div style="font-size:11px;color:var(--text3);font-weight:500;margin-bottom:2px;">Bank Name</div>
        <div style="font-size:13px;">{{ $pa->bank_name }}</div>
      </div>
      <div style="padding:8px 12px;background:var(--bg);border-radius:10px;flex:1;min-width:140px;">
        <div style="font-size:11px;color:var(--text3);font-weight:500;margin-bottom:2px;">Account Number</div>
        <div style="font-size:13px;font-family:var(--mono);">{{ $pa->masked_account_number }}</div>
      </div>
      @endif
      @if($pa->upi_id)
      <div style="padding:8px 12px;background:var(--bg);border-radius:10px;flex:1;min-width:140px;">
        <div style="font-size:11px;color:var(--text3);font-weight:500;margin-bottom:2px;">UPI ID</div>
        <div style="font-size:13px;font-family:var(--mono);">{{ $pa->upi_id }}</div>
      </div>
      @endif
      @if($pa->verified_at)
        <div style="font-size:11px;color:var(--text3);margin-top:4px;width:100%;">
          Verified {{ $pa->verified_at->format('d M Y H:i') }}
          @if($pa->verifiedBy)
            by {{ $pa->verifiedBy->name }}
          @endif
        </div>
      @endif
    </div>
    @endforeach
  </div>
</div>
@endif

<div class="chart-card">
  <div class="chart-hdr">
    <div>
      <div class="chart-ttl">Transaction History</div>
      <div class="chart-sub">Full ledger for this wallet</div>
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
      <thead>
        <tr style="border-bottom:1px solid var(--border);">
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">Date</th>
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">Type</th>
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">Source</th>
          <th style="padding:12px;text-align:right;color:var(--text3);font-weight:500;">Amount</th>
          <th style="padding:12px;text-align:right;color:var(--text3);font-weight:500;">Balance After</th>
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">Reference</th>
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">Notes</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $tx)
          @php
            $txColors = ['credit' => 'var(--green)', 'debit' => 'var(--red)', 'adjustment' => 'var(--a)'];
            $txColor = $txColors[$tx->type] ?? 'var(--text)';
          @endphp
          <tr style="border-bottom:1px solid var(--border);">
            <td style="padding:12px;font-family:var(--mono);font-size:12px;">{{ $tx->created_at->format('Y-m-d H:i') }}</td>
            <td style="padding:12px;"><span style="color:{{ $txColor }};font-weight:600;">{{ ucfirst($tx->type) }}</span></td>
            <td style="padding:12px;color:var(--text3);">{{ $tx->source ?? '—' }}</td>
            <td style="padding:12px;text-align:right;font-family:var(--mono);font-weight:600;">₹{{ number_format($tx->amount, 2) }}</td>
            <td style="padding:12px;text-align:right;font-family:var(--mono);">₹{{ number_format($tx->balance_after, 2) }}</td>
            <td style="padding:12px;font-family:var(--mono);font-size:12px;">{{ class_basename($tx->reference_type) }} #{{ $tx->reference_id }}</td>
            <td style="padding:12px;color:var(--text3);">{{ $tx->notes ?? '—' }}</td>
          </tr>
        @empty
          <tr><td colspan="7" style="padding:24px;text-align:center;color:var(--text3);">No transactions.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="padding:12px;">{{ $transactions->links('vendor.pagination.admin') }}</div>
</div>

@endsection
