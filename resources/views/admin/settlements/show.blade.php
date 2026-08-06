@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush


@section('sidebar_settlements', 'active')
@section('page_title', 'Settlement #' . $settlement->id)
@section('page_subtitle', optional($org)->name . ' — Review payout request')

@section('content')

@if(session('success'))
  <div style="padding:12px 16px;border-radius:12px;background:var(--green-lt);color:var(--green);font-size:13px;font-weight:500;margin-bottom:16px;">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div style="padding:12px 16px;border-radius:12px;background:var(--red-lt);color:var(--red);font-size:13px;font-weight:500;margin-bottom:16px;">{{ session('error') }}</div>
@endif

@php
  $statusColors = [
    'pending_approval' => 'var(--amber)',
    'approved' => 'var(--a)',
    'paid' => 'var(--green)',
    'rejected' => 'var(--red)',
    'processing' => 'var(--a)',
    'failed' => 'var(--red)',
  ];
  $statusLabels = [
    'pending_approval' => 'Pending Approval',
    'approved' => 'Approved',
    'paid' => 'Paid',
    'rejected' => 'Rejected',
    'processing' => 'Processing',
    'failed' => 'Failed',
  ];
  $sc = $statusColors[$settlement->status] ?? 'var(--text3)';
@endphp

<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-gray"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Organization</div><div class="stat-val sv-gray" style="font-size:14px;">{{ optional($org)->name ?? '—' }}</div><div class="stat-foot">ID: {{ $org?->id ?? '—' }}</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon" style="background:{{ $sc }}18;color:{{ $sc }};"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Status</div><div class="stat-val" style="font-size:14px;color:{{ $sc }};">{{ $statusLabels[$settlement->status] ?? $settlement->status }}</div><div class="stat-foot">Created {{ $settlement->created_at->format('d M Y') }}</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Gross / Fee / Net</div><div class="stat-val sv-green" style="font-size:14px;">₹{{ number_format($settlement->net_amount, 2) }}</div><div class="stat-foot">Gross: ₹{{ number_format($settlement->gross_amount, 2) }} · Fee: ₹{{ number_format($settlement->platform_fee, 2) }}</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Gateway Reference</div>    <div class="stat-val sv-amber" style="font-size:14px;font-family:var(--mono);">{{ $settlement->gateway_reference ?? '—' }}</div>
    <div class="stat-foot">
      @if($settlement->paid_at)
        Paid: {{ $settlement->paid_at->format('d M Y H:i') }}
      @elseif($settlement->failed_at)
        Failed: {{ $settlement->failed_at->format('d M Y H:i') }}
      @elseif($settlement->processed_at)
        Processing since: {{ $settlement->processed_at->format('d M Y H:i') }}
      @elseif($settlement->approved_at)
        Approved: {{ $settlement->approved_at->format('d M Y H:i') }}
      @else
        Not yet paid
      @endif
    </div></div>
  </div>
</div>

@if(!empty($flags))
  <div style="background:var(--red-lt);border:1px solid var(--red);border-radius:12px;padding:16px;margin-bottom:24px;">
    <div style="display:flex;align-items:flex-start;gap:10px;">
      <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2" style="width:20px;height:20px;flex-shrink:0;margin-top:2px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
      </svg>
      <div>
        <div style="font-weight:600;color:var(--red);font-size:13px;margin-bottom:4px;">⚠ Needs extra scrutiny</div>
        <ul style="margin:0;padding-left:18px;font-size:12.5px;color:var(--text2);">
          @foreach($flags as $f)<li style="margin-bottom:2px;">{{ $f }}</li>@endforeach
        </ul>
      </div>
    </div>
  </div>
@endif

@if($settlement->rejection_reason)
  <div style="background:var(--red-lt);border:1px solid var(--red);border-radius:12px;padding:14px 16px;margin-bottom:24px;display:flex;align-items:flex-start;gap:10px;">
    <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    <div>
      <div style="font-weight:600;color:var(--red);font-size:13px;">Rejection reason</div>
      <div style="font-size:12.5px;color:var(--text2);margin-top:2px;">{{ $settlement->rejection_reason }}</div>
    </div>
  </div>
@endif

@if($settlement->failed_reason)
  <div style="background:var(--red-lt);border:1px solid var(--red);border-radius:12px;padding:14px 16px;margin-bottom:24px;display:flex;align-items:flex-start;gap:10px;">
    <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <div>
      <div style="font-weight:600;color:var(--red);font-size:13px;">Payout failure reason</div>
      <div style="font-size:12.5px;color:var(--text2);margin-top:2px;font-family:var(--mono);">{{ $settlement->failed_reason }}</div>
      <div style="font-size:11px;color:var(--text3);margin-top:4px;">Funds have been returned to the wallet balance.</div>
    </div>
  </div>
@endif

<div class="chart-card" style="margin-bottom:24px;">
  <div class="chart-hdr">
    <div>
      <div class="chart-ttl">Settlement Items</div>
      <div class="chart-sub">Donations included in this payout request</div>
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
      <thead>
        <tr style="border-bottom:1px solid var(--border);">
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">Donation</th>
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">Campaign</th>
          <th style="padding:12px;text-align:right;color:var(--text3);font-weight:500;">Amount</th>
        </tr>
      </thead>
      <tbody>
        @foreach($settlement->settlementItems as $item)
          <tr style="border-bottom:1px solid var(--border);">
            <td style="padding:12px;font-family:var(--mono);font-size:12px;">
              <a href="{{ route('admin.donations.show', $item->donation_id) }}" style="color:var(--a);text-decoration:none;">#{{ $item->donation_id }}</a>
            </td>
            <td style="padding:12px;font-weight:500;">
              @if($item->donation?->campaign)
                <a href="{{ route('admin.campaign.show', $item->donation->campaign) }}" style="color:var(--a);text-decoration:none;">{{ $item->donation->campaign->title }}</a>
              @else
                —
              @endif
            </td>
            <td style="padding:12px;text-align:right;font-family:var(--mono);font-weight:600;">₹{{ number_format($item->amount, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="chart-card" style="margin-bottom:24px;">
  <div class="chart-hdr">
    <div>
      <div class="chart-ttl">Payout Account</div>
      <div class="chart-sub">Bank details for this settlement</div>
    </div>
  </div>
  <div style="padding:16px;">
    @if($payoutAccounts->isNotEmpty())
      @foreach($payoutAccounts as $pa)
      <div style="display:flex;gap:16px;flex-wrap:wrap;@if(!$loop->first) margin-top:16px;padding-top:16px;border-top:1px solid var(--border);@endif">
        <div style="display:flex;align-items:center;gap:8px;width:100%;margin-bottom:4px;">
          <span style="font-size:11px;color:var(--text3);font-weight:500;">Account Holder</span>
          <span style="font-size:13px;font-weight:600;">{{ $pa->account_holder_name }}</span>
          @if($pa->is_verified)
            <span style="font-size:10px;padding:1px 7px;border-radius:4px;background:var(--green-lt);color:var(--green);font-weight:600;">Verified</span>
            <form method="POST" action="{{ route('admin.payout-accounts.unverify', $pa) }}" style="display:inline;">
              @csrf
              <x-button type="submit" variant="outline" size="sm">Unverify</x-button>
            </form>
          @else
            <span style="font-size:10px;padding:1px 7px;border-radius:4px;background:var(--yellow-lt);color:var(--yellow);font-weight:600;">Pending</span>
            <form method="POST" action="{{ route('admin.payout-accounts.verify', $pa) }}" style="display:inline;">
              @csrf
              <x-button type="submit" variant="outline" size="sm">Mark Verified</x-button>
            </form>
          @endif
        </div>
        @if($pa->bank_name)
        <div style="padding:8px 12px;background:var(--bg);border-radius:10px;flex:1;min-width:140px;">
          <div style="font-size:11px;color:var(--text3);font-weight:500;margin-bottom:2px;">Bank Name</div>
          <div style="font-size:13px;word-break:break-word;">{{ $pa->bank_name }}</div>
        </div>
        <div style="padding:8px 12px;background:var(--bg);border-radius:10px;flex:1;min-width:140px;">
          <div style="font-size:11px;color:var(--text3);font-weight:500;margin-bottom:2px;">Account Number</div>
          <div style="font-size:13px;font-family:var(--mono);word-break:break-word;">{{ $pa->masked_account_number }}</div>
        </div>
        @endif
        @if($pa->upi_id)
        <div style="padding:8px 12px;background:var(--bg);border-radius:10px;flex:1;min-width:140px;">
          <div style="font-size:11px;color:var(--text3);font-weight:500;margin-bottom:2px;">UPI ID</div>
          <div style="font-size:13px;font-family:var(--mono);word-break:break-word;">{{ $pa->upi_id }}</div>
        </div>
        @endif
      </div>
      @endforeach
      @if(!$payout)
        <p style="color:var(--yellow);font-size:12px;margin-top:12px;">⚠ None of these accounts are verified yet.</p>
      @endif
    @else
      <p style="color:var(--text3);font-size:13px;">No payout account on file. The fundraiser needs to add bank or UPI details from their wallet dashboard.</p>
    @endif
  </div>
</div>

@if($settlement->isPendingApproval())
  @php
    $approveConfirm = !empty($flags)
      ? 'This settlement is flagged for review. Are you sure you want to approve it? This will lock and debit the funds.'
      : 'Approve this settlement? This will lock and debit the funds.';
  @endphp
  <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:8px;">
    <form method="POST" action="{{ route('admin.settlements.approve', $settlement) }}"
          onsubmit="return confirm(@json($approveConfirm));">
      @csrf
      <x-button type="submit" variant="primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        Approve Settlement
      </x-button>
    </form>
    <form method="POST" action="{{ route('admin.settlements.reject', $settlement) }}" style="display:flex;gap:8px;align-items:flex-start;flex-wrap:wrap;"
          onsubmit="return confirm('Reject this settlement and return funds to balance?');">
      @csrf
      <textarea name="reason" id="rejectReason" rows="2" placeholder="Rejection reason (required)" required
              oninput="document.getElementById('rejectBtn').disabled = this.value.trim() === '';"
             style="padding:9px 14px;border-radius:10px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:13px;width:260px;max-width:100%;resize:vertical;font-family:inherit;"></textarea>
      <x-button type="submit" id="rejectBtn" variant="destructive" disabled>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        Reject
      </x-button>
    </form>
  </div>
@endif

@endsection
