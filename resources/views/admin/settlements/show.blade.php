@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush

@extends('layouts.admin')

@section('sidebar_settlements', 'active')
@section('page_title', 'Settlement #' . $settlement->id)
@section('page_subtitle', optional($org)->name . ' — Review payout request')

@push('page_styles')
<style>
/* ── settlement-specific badges / alerts (view-scoped, matches admin.css tokens) ── */
.st-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:100px;font-size:10.5px;font-weight:700;font-family:var(--mono);white-space:nowrap;border:1px solid transparent}
.st-badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0}
.st-pending{background:var(--amber-lt);color:var(--amber);border-color:rgba(245,158,11,.25)}
.st-approved{background:var(--a-lt);color:var(--a);border-color:rgba(37,99,235,.22)}
.st-processing{background:rgba(59,130,246,.12);color:var(--blue);border-color:rgba(59,130,246,.25)}
.st-paid{background:rgba(5,196,138,.12);color:#059c7f;border-color:rgba(5,196,138,.25)}
.st-rejected{background:rgba(240,68,68,.12);color:var(--red);border-color:rgba(240,68,68,.25)}
.st-failed{background:rgba(240,68,68,.12);color:var(--red);border-color:rgba(240,68,68,.25)}
.st-alert{display:flex;align-items:flex-start;gap:10px;padding:14px 16px;border-radius:var(--r-sm);margin-bottom:18px;border:1px solid}
.st-alert svg{width:17px;height:17px;flex-shrink:0;margin-top:2px}
.st-alert-title{font-weight:600;font-size:13px;margin-bottom:3px}
.st-alert-text{font-size:12.5px;line-height:1.6}
.st-alert-warn{background:var(--amber-lt);border-color:rgba(245,158,11,.3);color:var(--amber)}
.st-alert-warn .st-alert-text{color:var(--text2)}
.st-alert-err{background:var(--red-lt);border-color:rgba(240,68,68,.3);color:var(--red)}
.st-alert-err .st-alert-text{color:var(--text2)}
.st-alert-list{margin:4px 0 0;padding-left:18px;font-size:12.5px;color:var(--text2)}
.st-alert-list li{margin-bottom:2px}
.st-alert-mono{font-family:var(--mono);font-size:12px}
.st-tl{display:flex;flex-direction:column}
.st-tl-row{display:flex;align-items:center;gap:12px;padding:10px 16px;border-bottom:1px solid var(--border)}
.st-tl-row:last-child{border-bottom:none}
.st-tl-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.st-tl-lbl{font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);width:150px;flex-shrink:0}
.st-tl-val{font-size:12.5px;font-weight:600;color:var(--text);font-family:var(--mono);margin-left:auto;text-align:right}
.st-tl-val.muted{color:var(--text3);font-weight:500}
.st-kv{display:flex;flex-direction:column;gap:2px}
.st-kv .k{font-size:10px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em}
.st-kv .v{font-size:13px;color:var(--text);font-weight:600;word-break:break-word}
.modal-green{background:linear-gradient(135deg,var(--green),var(--success-mid-3));color:var(--white);box-shadow:0 4px 16px rgba(5,196,138,.3);}
.st-pa{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:12px}
.st-pa-name{font-size:14px;font-weight:700;color:var(--text)}
.st-pa-acc{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:12px 14px;display:flex;flex-direction:column;gap:2px;margin-top:10px}
@media(max-width:640px){.st-tl-lbl{width:110px}}
</style>
@endpush

@section('content')

@php
  $statusLabels = [
    'pending_approval' => 'Pending Approval',
    'approved' => 'Approved',
    'processing' => 'Processing',
    'paid' => 'Paid',
    'rejected' => 'Rejected',
    'failed' => 'Failed',
  ];
  $badgeClass = [
    'pending_approval' => 'st-pending',
    'approved' => 'st-approved',
    'processing' => 'st-processing',
    'paid' => 'st-paid',
    'rejected' => 'st-rejected',
    'failed' => 'st-failed',
  ];
  $bc = $badgeClass[$settlement->status] ?? 'st-approved';
@endphp

<div style="margin-bottom:18px;">
  <a href="{{ route('admin.settlements.index', ['status' => $settlement->status]) }}" class="btn btn-secondary act-btn ab-view" style="text-decoration:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
    <span>Back to settlements</span>
  </a>
</div>

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Settlement</div>
    <div class="hero-name">₹{{ number_format($settlement->net_amount, 2) }}</div>
    <div class="hero-sub" style="flex-wrap:wrap;">
      <span class="st-badge {{ $bc }}">{{ $statusLabels[$settlement->status] ?? $settlement->status }}</span>
      <span class="hero-badge hb-gray">{{ $settlement->settlementItems->count() }} donation item(s)</span>
      @if(!empty($flags))
        <span class="hero-badge hb-amber">⚠ {{ count($flags) }} flag(s)</span>
      @endif
    </div>
  </div>
  @if($settlement->isPendingApproval())
    <div class="hero-right">
      <button type="button" onclick="openApprove()" class="hero-btn hero-btn-primary" style="background:linear-gradient(135deg,var(--green),var(--success-mid-3));box-shadow:0 4px 20px rgba(5,196,138,.4);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        Approve Settlement
      </button>
      <button type="button" onclick="openReject()" class="hero-btn hero-btn-ghost" style="color:var(--red);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        Reject
      </button>
    </div>
  @else
    <div class="hero-right">
      <span class="hero-badge hb-gray">{{ $statusLabels[$settlement->status] ?? $settlement->status }} — no action available</span>
    </div>
  @endif
</div>

<div class="stats-grid" style="grid-template-columns:repeat(4,1fr)">
  <div class="stat">
    <div class="stat-icon si-gray"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Organization</div><div class="stat-val sv-gray" style="font-size:16px;">{{ optional($org)->name ?? '—' }}</div><div class="stat-foot">Org ID: {{ $org?->id ?? '—' }}</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Status</div><div class="stat-val sv-amber" style="font-size:16px;">{{ $statusLabels[$settlement->status] ?? $settlement->status }}</div><div class="stat-foot">Created {{ $settlement->created_at->format('d M Y H:i') }}</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Net Amount</div><div class="stat-val sv-green" style="font-size:16px;">₹{{ number_format($settlement->net_amount, 2) }}</div><div class="stat-foot">Gross: ₹{{ number_format($settlement->gross_amount, 2) }} · Fee: ₹{{ number_format($settlement->platform_fee, 2) }}</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-a"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Gateway Reference</div><div class="stat-val sv-a" style="font-size:14px;font-family:var(--mono);">#{{ $settlement->id }}</div><div class="stat-foot">{{ $settlement->gateway_reference ?? 'No gateway ref yet' }}</div></div>
  </div>
</div>

@if(!empty($flags))
  <div class="st-alert st-alert-warn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    <div style="flex:1;min-width:0;">
      <div class="st-alert-title">Needs extra scrutiny</div>
      <div class="st-alert-text">This settlement was flagged by the risk checks. Review carefully before approving.</div>
      <ul class="st-alert-list">
        @foreach($flags as $f)<li>{{ $f }}</li>@endforeach
      </ul>
    </div>
  </div>
@endif

@if($settlement->rejection_reason)
  <div class="st-alert st-alert-err">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    <div style="flex:1;min-width:0;">
      <div class="st-alert-title">Rejection reason</div>
      <div class="st-alert-text">{{ $settlement->rejection_reason }}</div>
    </div>
  </div>
@endif

@if($settlement->failed_reason)
  <div class="st-alert st-alert-err">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <div style="flex:1;min-width:0;">
      <div class="st-alert-title">Payout failure reason</div>
      <div class="st-alert-text st-alert-mono">{{ $settlement->failed_reason }}</div>
      <div class="st-alert-text" style="margin-top:4px;font-size:11px;color:var(--text3);">Funds have been returned to the wallet balance.</div>
    </div>
  </div>
@endif

<div class="sec-hdr">
  <div class="sec-ttl">Settlement Items</div>
  <div style="font-size:12px;color:var(--text3);font-family:var(--mono);">{{ $settlement->settlementItems->count() }} donation(s)</div>
</div>

<div class="table-card" style="margin-bottom:20px;">
  <div class="table-scroll">
    <table>
      <thead>
        <tr>
          <th>Donation</th>
          <th>Campaign</th>
          <th style="text-align:right">Amount</th>
        </tr>
      </thead>
      <tbody>
        @forelse($settlement->settlementItems as $item)
          <tr>
            <td class="cell-id">
              <a href="{{ route('admin.donations.show', $item->donation_id) }}" style="color:var(--a);text-decoration:none;">#{{ $item->donation_id }}</a>
            </td>
            <td style="font-weight:500;">
              @if($item->donation?->campaign)
                <a href="{{ route('admin.campaign.show', $item->donation->campaign) }}" style="color:var(--a);text-decoration:none;">{{ $item->donation->campaign->title }}</a>
              @else
                <span style="color:var(--text3);font-size:12px;">—</span>
              @endif
            </td>
            <td class="cell-mono" style="text-align:right;font-weight:700;">₹{{ number_format($item->amount, 2) }}</td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="3">
              <div class="empty-inner">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <strong>No items in this settlement</strong>
                <span>No donations were attached to this payout request.</span>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
      @if($settlement->settlementItems->isNotEmpty())
        <tfoot>
          <tr>
            <td colspan="2" style="text-align:right;font-weight:700;color:var(--text2);">Gross Total</td>
            <td class="cell-mono" style="text-align:right;font-weight:700;color:var(--green);">₹{{ number_format($settlement->settlementItems->sum('amount'), 2) }}</td>
          </tr>
        </tfoot>
      @endif
    </table>
  </div>
</div>

<div class="content-grid" style="grid-template-columns:1fr 340px;gap:18px;margin-bottom:20px;">
  <div>
    <div class="sec-hdr">
      <div class="sec-ttl">Payout Account</div>
    </div>
    <div class="table-card" style="padding:20px;">
      @if($payoutAccounts->isNotEmpty())
        @foreach($payoutAccounts as $pa)
          <div style="@if(!$loop->first) margin-top:18px;padding-top:18px;border-top:1px solid var(--border);@endif">
            <div class="st-pa">
              <span class="st-pa-name">{{ $pa->account_holder_name }}</span>
              @if($pa->is_verified)
                <span class="dn-badge dn-completed">✓ Verified</span>
                <form method="POST" action="{{ route('admin.payout-accounts.unverify', $pa) }}" style="display:inline;">
                  @csrf
                  <button type="submit" class="btn btn-secondary act-btn" style="padding:4px 10px;font-size:11px;color:var(--red);border-color:rgba(240,68,68,.3);">Unverify</button>
                </form>
              @else
                <span class="dn-badge dn-pending">● Pending</span>
                <form method="POST" action="{{ route('admin.payout-accounts.verify', $pa) }}" style="display:inline;">
                  @csrf
                  <button type="submit" class="btn btn-secondary act-btn" style="padding:4px 10px;font-size:11px;color:var(--green);border-color:rgba(5,196,138,.3);">Mark Verified</button>
                </form>
              @endif
            </div>
            <div class="dn-grid" style="margin-top:10px;">
              @if($pa->bank_name)
                <div class="st-kv"><span class="k">Bank Name</span><span class="v">{{ $pa->bank_name }}</span></div>
                <div class="st-kv"><span class="k">Account Number</span><span class="v" style="font-family:var(--mono);">{{ $pa->masked_account_number }}</span></div>
              @endif
              @if($pa->upi_id)
                <div class="st-kv"><span class="k">UPI ID</span><span class="v" style="font-family:var(--mono);">{{ $pa->upi_id }}</span></div>
              @endif
            </div>
          </div>
        @endforeach
        @if(!$payout)
          <div class="st-alert st-alert-warn" style="margin:14px 0 0;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <div style="flex:1;min-width:0;">
              <div class="st-alert-title">No verified account</div>
              <div class="st-alert-text">None of these accounts are verified yet. Verify before approving the payout.</div>
            </div>
          </div>
        @endif
      @else
        <div class="empty-inner" style="padding:24px 10px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:38px;height:38px;color:var(--text3);opacity:.25;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
          <strong>No payout account on file</strong>
          <span>The fundraiser needs to add bank or UPI details from their wallet dashboard.</span>
        </div>
      @endif
    </div>
  </div>

  <div>
    <div class="sec-hdr">
      <div class="sec-ttl">Timeline</div>
    </div>
    <div class="table-card">
      <div class="st-tl">
        @php
          $events = [
            ['Created', $settlement->created_at, 'var(--a)'],
            ['Approved', $settlement->approved_at, 'var(--a)'],
            ['Processing', $settlement->processed_at, 'var(--blue)'],
            ['Paid', $settlement->paid_at, 'var(--green)'],
            ['Rejected', $settlement->rejected_at, 'var(--red)'],
            ['Failed', $settlement->failed_at, 'var(--red)'],
          ];
        @endphp
        @foreach($events as [$label, $when, $color])
          <div class="st-tl-row">
            <span class="st-tl-dot" style="background:{{ $when ? $color : 'var(--border2)' }};"></span>
            <span class="st-tl-lbl">{{ $label }}</span>
            <span class="st-tl-val {{ $when ? '' : 'muted' }}">{{ $when ? $when->format('d M Y H:i') : '—' }}</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

@if($settlement->isPendingApproval())
  {{-- Approve confirmation modal --}}
  <div id="approveOverlay" class="overlay" role="dialog" aria-modal="true">
    <div class="modal">
      <button type="button" class="modal-x" onclick="closeApprove()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
      <div class="modal-head">
        <div class="modal-ico" style="background:var(--green-lt);">
          <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div>
          <div class="modal-ttl">Approve Settlement</div>
          <div class="modal-sub">This is a real financial action</div>
        </div>
      </div>
      <div class="modal-body">
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:12px 14px;margin-bottom:12px;display:flex;flex-direction:column;gap:6px;">
          <div class="dn-kv" style="flex-direction:row;justify-content:space-between;"><span class="k">Gross amount</span><span class="v" style="font-family:var(--mono)">₹{{ number_format($settlement->gross_amount, 2) }}</span></div>
          <div class="dn-kv" style="flex-direction:row;justify-content:space-between;"><span class="k">Platform fee</span><span class="v" style="font-family:var(--mono)">−₹{{ number_format($settlement->platform_fee, 2) }}</span></div>
          <div class="dn-kv" style="flex-direction:row;justify-content:space-between;border-top:1px solid var(--border);padding-top:6px;"><span class="k">Net payout</span><span class="v" style="font-family:var(--mono);color:var(--green);font-weight:800;">₹{{ number_format($settlement->net_amount, 2) }}</span></div>
        </div>
        @if(!empty($flags))
          <div class="st-alert st-alert-warn" style="margin:0 0 10px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <div style="flex:1;min-width:0;">
              <div class="st-alert-title" style="font-size:12px;">Flagged for review — {{ count($flags) }} risk flag(s)</div>
              <ul class="st-alert-list" style="font-size:11.5px;">@foreach($flags as $f)<li>{{ $f }}</li>@endforeach</ul>
            </div>
          </div>
        @endif
        Approving will lock and debit the funds, then start the payout to <strong>{{ optional($payout)->account_holder_name ?? optional($org)->name }}</strong>. This cannot be undone.
      </div>
      <div class="modal-acts">
        <button type="button" onclick="closeApprove()" class="btn btn-secondary modal-btn modal-cancel">Cancel</button>
        <form method="POST" action="{{ route('admin.settlements.approve', $settlement) }}" style="flex:1;">
          @csrf
          <button type="submit" class="btn modal-btn modal-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Confirm Approve
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- Reject modal --}}
  <div id="rejectOverlay" class="overlay" role="dialog" aria-modal="true">
    <div class="modal">
      <button type="button" class="modal-x" onclick="closeReject()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
      <div class="modal-head">
        <div class="modal-ico modal-ico--destructive">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <div>
          <div class="modal-ttl">Reject Settlement</div>
          <div class="modal-sub">Funds will be returned to the wallet balance</div>
        </div>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('admin.settlements.reject', $settlement) }}">
          @csrf
          <div class="chips">
            <button type="button" class="chip chip-red" onclick="setReason(this.textContent)">KYC not verified</button>
            <button type="button" class="chip chip-red" onclick="setReason(this.textContent)">Bank details mismatch</button>
            <button type="button" class="chip chip-red" onclick="setReason(this.textContent)">Suspicious activity</button>
            <button type="button" class="chip chip-red" onclick="setReason(this.textContent)">Duplicate request</button>
          </div>
          <div class="modal-lbl">Reason <span>*</span></div>
          <textarea id="rejectReason" name="reason" class="modal-ta" rows="3" placeholder="Required — explain why this settlement is rejected…" oninput="document.getElementById('rejectBtn').disabled = this.value.trim() === '';"></textarea>
          <div class="modal-acts">
            <button type="button" onclick="closeReject()" class="btn btn-secondary modal-btn modal-cancel">Cancel</button>
            <button type="submit" id="rejectBtn" class="btn btn-red modal-btn modal-red" disabled>↺ Confirm Reject</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endif

@endsection

@push('page_scripts')
<script>
(function () {
  'use strict';
  var approveOverlay = document.getElementById('approveOverlay');
  var rejectOverlay = document.getElementById('rejectOverlay');

  window.openApprove = function () { if (approveOverlay) approveOverlay.classList.add('open'); };
  window.closeApprove = function () { if (approveOverlay) approveOverlay.classList.remove('open'); };
  window.openReject = function () { if (rejectOverlay) rejectOverlay.classList.add('open'); };
  window.closeReject = function () { if (rejectOverlay) rejectOverlay.classList.remove('open'); };
  window.setReason = function (text) {
    var ta = document.getElementById('rejectReason');
    if (!ta) return;
    ta.value = text;
    ta.dispatchEvent(new Event('input'));
    ta.focus();
  };
  if (approveOverlay) approveOverlay.addEventListener('click', function (e) { if (e.target === this) closeApprove(); });
  if (rejectOverlay) rejectOverlay.addEventListener('click', function (e) { if (e.target === this) closeReject(); });
}());
</script>
@endpush