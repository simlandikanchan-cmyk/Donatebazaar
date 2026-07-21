@extends('layouts.user')

@section('page_title', 'My Wallet')
@section('page_subtitle', 'Balances, transaction history & payout requests')

@section('content')

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card">
        <div class="stat-icon-wrap si-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Available Balance</div>
            <div class="stat-val sv-green">₹{{ number_format($wallet->balance, 2) }}</div>
            <div class="stat-foot">{{ $wallet->currency }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Reserved (hold window)</div>
            <div class="stat-val sv-yellow">₹{{ number_format($wallet->reserved_balance, 2) }}</div>
            <div class="stat-foot">Released after the reserve period</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-pink">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Locked in Settlement</div>
            <div class="stat-val sv-pink">₹{{ number_format($wallet->pending_settlement_balance, 2) }}</div>
            <div class="stat-foot">Pending admin approval</div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:16px;padding:12px 16px;border-radius:12px;background:var(--green-lt);color:var(--green);font-size:13px;font-weight:500;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error" style="margin-bottom:16px;padding:12px 16px;border-radius:12px;background:var(--red-lt);color:var(--red);font-size:13px;font-weight:500;">{{ session('error') }}</div>
@endif

@if($pendingSettlements->isNotEmpty())
    <div class="activity-card" style="margin-bottom:24px;">
        <div class="activity-hdr">
            <div class="activity-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Pending Payout Requests
            </div>
        </div>
        <div class="activity-list">
            @foreach($pendingSettlements as $ps)
                <div class="activity-item">
                    <div class="activity-body">
                        <div class="activity-body-top">
                            <div class="activity-lbl">
                                Request #{{ $ps->id }}
                                @if($ps->isPendingApproval())
                                    <span class="badge b-pending" style="font-size:10px;padding:2px 8px;margin-left:8px;">Pending approval</span>
                                @elseif($ps->isApproved())
                                    <span class="badge b-active" style="font-size:10px;padding:2px 8px;margin-left:8px;">Approved — payout in progress</span>
                                @endif
                            </div>
                            <div class="activity-amt">₹{{ number_format($ps->net_amount, 2) }}</div>
                        </div>
                        @if($ps->rejection_reason)
                            <div class="activity-sub" style="color:var(--red);">Rejected: {{ $ps->rejection_reason }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div class="chart-card" style="margin-bottom:24px;">
    <div class="chart-card-hdr">
        <div>
            <div class="chart-title">Payout Account</div>
            <div class="chart-sub">Add your bank or UPI details where payouts will be sent.</div>
        </div>
    </div>
    <div style="padding:0 16px 16px;">
        @if($payoutAccounts->isNotEmpty())
            <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:14px;">
                @foreach($payoutAccounts as $pa)
                    <div style="flex:1;min-width:200px;padding:12px 16px;background:var(--surface2);border-radius:10px;border:1px solid var(--border);">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                            <span style="font-size:12px;font-weight:600;">{{ $pa->account_holder_name }}</span>
                            @if($pa->is_verified)
                                <span style="font-size:10px;padding:2px 6px;border-radius:4px;background:var(--green-lt);color:var(--green);font-weight:600;">Verified</span>
                            @else
                                <span style="font-size:10px;padding:2px 6px;border-radius:4px;background:var(--yellow-lt);color:var(--yellow);font-weight:600;">Pending</span>
                            @endif
                        </div>
                        @if($pa->bank_name)
                            <div style="font-size:12px;color:var(--text3);">{{ $pa->bank_name }} — {{ $pa->masked_account_number }}</div>
                        @endif
                        @if($pa->upi_id)
                            <div style="font-size:12px;color:var(--text3);">UPI: {{ $pa->upi_id }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('dashboard.wallet.payout-account') }}" style="display:flex;flex-wrap:wrap;gap:10px;">
            @csrf
            <input type="text" name="account_holder_name" placeholder="Account Holder Name" required
                   style="flex:1;min-width:160px;padding:9px 14px;border-radius:10px;border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-size:13px;font-family:var(--font);outline:none;">
            <input type="text" name="bank_name" placeholder="Bank Name"
                   style="flex:1;min-width:140px;padding:9px 14px;border-radius:10px;border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-size:13px;font-family:var(--font);outline:none;">
            <input type="text" name="account_number" placeholder="Account Number"
                   style="flex:1;min-width:140px;padding:9px 14px;border-radius:10px;border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-size:13px;font-family:var(--font);outline:none;">
            <input type="text" name="ifsc_code" placeholder="IFSC Code"
                   style="flex:1;min-width:100px;padding:9px 14px;border-radius:10px;border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-size:13px;font-family:var(--font);outline:none;">
            <input type="text" name="upi_id" placeholder="UPI ID (or leave blank)"
                   style="flex:1;min-width:140px;padding:9px 14px;border-radius:10px;border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-size:13px;font-family:var(--font);outline:none;">
            <button type="submit" class="btn btn-primary" style="align-self:flex-end;">Save</button>
        </form>
    </div>
</div>

<div class="chart-card" style="margin-bottom:24px;">
    <div class="chart-card-hdr">
        <div>
            <div class="chart-title">Request a Payout</div>
            <div class="chart-sub">Select eligible (matured, unsettled) donations to include in your payout request. Funds are locked and sent for admin approval.</div>
        </div>
    </div>
    @if($eligible->isEmpty())
        <p style="padding:16px;text-align:center;color:var(--text3);font-size:13px;">No eligible donations available for payout right now.</p>
    @else
        <form method="POST" action="{{ route('dashboard.wallet.request') }}" style="padding:16px;">
            @csrf
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="border-bottom:1px solid var(--border);">
                            <th style="padding:10px 12px;text-align:left;color:var(--text3);font-weight:500;"></th>
                            <th style="padding:10px 12px;text-align:left;color:var(--text3);font-weight:500;">Campaign</th>
                            <th style="padding:10px 12px;text-align:left;color:var(--text3);font-weight:500;">Paid At</th>
                            <th style="padding:10px 12px;text-align:right;color:var(--text3);font-weight:500;">Net Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eligible as $d)
                            <tr style="border-bottom:1px solid var(--border);">
                                <td style="padding:10px 12px;"><input type="checkbox" name="donation_ids[]" value="{{ $d->id }}"></td>
                                <td style="padding:10px 12px;">{{ $d->campaign->title ?? '—' }}</td>
                                <td style="padding:10px 12px;">{{ $d->paid_at?->format('Y-m-d') }}</td>
                                <td style="padding:10px 12px;text-align:right;font-family:var(--mono);font-weight:600;">₹{{ number_format($d->net_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:16px;">Request Payout</button>
        </form>
    @endif
</div>

<div class="chart-card">
    <div class="chart-card-hdr">
        <div>
            <div class="chart-title">Transaction History</div>
            <div class="chart-sub">All wallet transactions</div>
        </div>
    </div>
    <div style="overflow-x:auto;padding:16px;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="border-bottom:1px solid var(--border);">
                    <th style="padding:10px 12px;text-align:left;color:var(--text3);font-weight:500;">Date</th>
                    <th style="padding:10px 12px;text-align:left;color:var(--text3);font-weight:500;">Type</th>
                    <th style="padding:10px 12px;text-align:left;color:var(--text3);font-weight:500;">Source</th>
                    <th style="padding:10px 12px;text-align:right;color:var(--text3);font-weight:500;">Amount</th>
                    <th style="padding:10px 12px;text-align:right;color:var(--text3);font-weight:500;">Balance After</th>
                    <th style="padding:10px 12px;text-align:left;color:var(--text3);font-weight:500;">Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:10px 12px;font-family:var(--mono);font-size:12px;">{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                        <td style="padding:10px 12px;">
                            @php
                                $txColors = ['credit' => 'var(--green)', 'debit' => 'var(--red)', 'adjustment' => 'var(--a)'];
                                $txColor = $txColors[$tx->type] ?? 'var(--text)';
                            @endphp
                            <span style="color:{{ $txColor }};font-weight:600;">{{ ucfirst($tx->type) }}</span>
                        </td>
                        <td style="padding:10px 12px;">{{ $tx->source ?? '—' }}</td>
                        <td style="padding:10px 12px;text-align:right;font-family:var(--mono);font-weight:600;">₹{{ number_format($tx->amount, 2) }}</td>
                        <td style="padding:10px 12px;text-align:right;font-family:var(--mono);">₹{{ number_format($tx->balance_after, 2) }}</td>
                        <td style="padding:10px 12px;color:var(--text3);">{{ $tx->notes ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:24px;text-align:center;color:var(--text3);font-size:13px;">No transactions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:0 16px 16px;">
        <div class="pagination-wrap">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

@endsection
