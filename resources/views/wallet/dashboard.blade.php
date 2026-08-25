@extends('layouts.user')

@section('page_title', 'My Wallet')
@section('page_subtitle', ' history & payout requests')

@section('content')
@php
$icoWalletBal  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>';
$icoReserved   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
$icoLocked     = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>';
@endphp

<div class="wallet-stats-grid">
    <x-stat-card color="green" label="Available Balance" value="₹{{ number_format($wallet->balance, 2) }}" footer="{{ $wallet->currency }}" :icon="$icoWalletBal" />
    <x-stat-card color="yellow" label="Reserved (hold window)" value="₹{{ number_format($wallet->reserved_balance, 2) }}" footer="Released after the reserve period" :icon="$icoReserved" />
    <x-stat-card color="pink" label="Locked in Settlement" value="₹{{ number_format($wallet->pending_settlement_balance, 2) }}" footer="Locked until payout or refund" :icon="$icoLocked" />
</div>

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
                                @if($ps->status === 'manual_review')
                                    <span class="badge b-pending" style="font-size:10px;padding:2px 8px;margin-left:8px;">Manual review</span>
                                @elseif($ps->isPendingApproval())
                                    <span class="badge b-pending" style="font-size:10px;padding:2px 8px;margin-left:8px;">Pending approval</span>
                                @elseif($ps->isAutoApproved())
                                    <span class="badge b-active" style="font-size:10px;padding:2px 8px;margin-left:8px;">Approved — payout in progress</span>
                                @elseif($ps->status === 'failed')
                                    <span class="badge" style="font-size:10px;padding:2px 8px;margin-left:8px;background:var(--red-lt);color:var(--red);">Payout failed</span>
                                @endif
                            </div>
                            <div class="activity-amt">₹{{ number_format($ps->net_amount, 2) }}</div>
                        </div>
                        @if($ps->rejection_reason)
                            <div class="activity-sub" style="color:var(--red);">Rejected: {{ $ps->rejection_reason }}</div>
                        @endif
                        @if($ps->status === 'failed' && $ps->payoutAttempt->first()?->error_message)
                            <div class="activity-sub" style="color:var(--red);">Failed: {{ $ps->payoutAttempt->first()->error_message }}</div>
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
            <div class="wallet-pa-grid">
                @foreach($payoutAccounts as $pa)
                    <div class="wallet-pa-item">
                        <div class="wallet-pa-top">
                            <span class="wallet-pa-name">{{ $pa->account_holder_name }}</span>
                            @if($pa->is_verified)
                                <span class="wallet-pa-badge verified">Verified</span>
                            @else
                                <span class="wallet-pa-badge">Pending</span>
                            @endif
                        </div>
                        @if($pa->bank_name)
                            <div class="wallet-pa-detail">{{ $pa->bank_name }} — {{ $pa->masked_account_number }}</div>
                        @endif
                        @if($pa->upi_id)
                            <div class="wallet-pa-detail">UPI: {{ $pa->upi_id }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('dashboard.wallet.payout-account') }}" class="wallet-pa-form">
            @csrf
            <input type="text" name="account_holder_name" placeholder="Account Holder Name" required class="wallet-input">
            <input type="text" name="bank_name" placeholder="Bank Name" class="wallet-input">
            <input type="text" name="account_number" placeholder="Account Number" class="wallet-input">
            <input type="text" name="ifsc_code" placeholder="IFSC Code" class="wallet-input">
            <input type="text" name="upi_id" placeholder="UPI ID (or leave blank)" class="wallet-input">
            <x-button variant="primary" type="submit">Save</x-button>
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
            <div class="wallet-table-wrap">
                <table class="wallet-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Campaign</th>
                            <th class="hide-mobile">Paid At</th>
                            <th class="text-right">Net Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eligible as $d)
                            <tr>
                                <td><input type="checkbox" name="donation_ids[]" value="{{ $d->id }}"></td>
                                <td data-label="Campaign">{{ $d->campaign->title ?? '—' }}</td>
                                <td class="hide-mobile" data-label="Paid At">{{ $d->paid_at?->format('Y-m-d') }}</td>
                                <td class="text-right mono" data-label="Amount">₹{{ number_format($d->net_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <x-button variant="primary" type="submit">Request Payout</x-button>
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
    <div class="wallet-table-wrap" style="padding:16px;">
        <table class="wallet-table tx-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th class="hide-mobile">Source</th>
                    <th class="text-right">Amount</th>
                    <th class="hide-tablet">Balance</th>
                    <th class="hide-mobile">Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    @php
                        $txColors = ['credit' => 'var(--green)', 'debit' => 'var(--red)', 'adjustment' => 'var(--accent)'];
                        $txColor = $txColors[$tx->type] ?? 'var(--text)';
                    @endphp
                    <tr>
                        <td data-label="Date" class="mono-sm">{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                        <td data-label="Type"><span style="color:{{ $txColor }};font-weight:600;">{{ ucfirst($tx->type) }}</span></td>
                        <td class="hide-mobile" data-label="Source">{{ $tx->source ?? '—' }}</td>
                        <td class="text-right mono" data-label="Amount">₹{{ number_format($tx->amount, 2) }}</td>
                        <td class="hide-tablet text-right mono" data-label="Balance">₹{{ number_format($tx->balance_after, 2) }}</td>
                        <td class="hide-mobile" data-label="Notes" style="color:var(--text3);">{{ $tx->notes ?? '—' }}</td>
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
