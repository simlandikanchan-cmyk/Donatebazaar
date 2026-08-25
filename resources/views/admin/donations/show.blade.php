@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush

@extends('layouts.admin')

@section('sidebar_donations', 'active')
<!-- @section('page_title', 'Donation #{{ $donation->id }}') -->
@section('page_subtitle', 'Donation detail & refund history')

@push('page_styles')
@vite('resources/css/admin/pages/donations-show.css')
<style>
@media(max-width:860px){
  .dn-grid{grid-template-columns:1fr!important}
  .dn-grid .table-card{padding:14px 16px!important}
  .hero-right{width:100%;margin-top:14px}
  .hero-right .hero-btn{width:100%;justify-content:center}
}
@media(max-width:640px){
  .table-card .dn-grid .dn-kv{font-size:12px}
}
</style>
@endpush

@section('content')

@if(session('success'))
<div style="background:rgba(5,196,138,.09);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:rgba(240,68,68,.09);border:1px solid rgba(240,68,68,.25);color:#7f1d1d;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('error') }}
</div>
@endif
@if(session('info'))
<div style="background:rgba(59,130,246,.09);border:1px solid rgba(59,130,246,.25);color:#1e40af;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('info') }}
</div>
@endif

<div style="margin-bottom:18px;">
  <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary act-btn ab-view" style="text-decoration:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
    <span>Back to donations</span>
  </a>
</div>

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Donation</div>
    <div class="hero-name">₹{{ number_format($donation->total_amount, 2) }}</div>
    <div class="hero-sub">
      @switch($donation->payment_status)
        @case('completed')<span class="dn-badge dn-completed">● Completed</span>@break
        @case('pending')<span class="dn-badge dn-pending">● Pending</span>@break
        @case('failed')<span class="dn-badge dn-failed">● Failed</span>@break
        @case('refunded')<span class="dn-badge dn-refunded">↺ Refunded</span>@break
      @endswitch
      @if($donation->is_refunded)<span class="dn-badge dn-yes">✓ Refunded</span>@endif
      @if($donation->is_anonymous)<span class="dn-badge dn-no">Anonymous</span>@endif
    </div>
  </div>
  <div class="hero-right">
    @if($donation->payment_status === 'completed' && !$donation->is_refunded)
      <button type="button" data-action="open-refund" data-id="{{ $donation->id }}" data-donor="{{ addslashes($donation->donor_name ?? 'this donation') }}" data-amount="{{ $donation->total_amount }}" class="hero-btn hero-btn-primary" style="background:var(--amber);border-color:var(--amber);color:#fff">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m4 0h1M3 10l2-5h14l2 5v9a1 1 0 01-1 1H4a1 1 0 01-1-1v-9z"/></svg>
        Refund Donation
      </button>
    @else
      <span class="hero-badge hb-gray">Not refundable</span>
    @endif
  </div>
</div>

<div class="dn-grid" style="margin-bottom:18px;">
  <div class="table-card" style="padding:18px 20px;">
    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);margin-bottom:12px;">Donor</div>
    <div class="dn-kv"><span class="k">Name</span><span class="v">{{ $donation->is_anonymous ? 'Anonymous' : ($donation->donor_name ?? ($donation->user->name ?? 'Guest')) }}</span></div>
    <div class="dn-kv" style="margin-top:10px;"><span class="k">Email</span><span class="v">{{ $donation->donor_email ?? ($donation->user->email ?? '—') }}</span></div>
    <div class="dn-kv" style="margin-top:10px;"><span class="k">Phone</span><span class="v">{{ $donation->donor_phone ?? '—' }}</span></div>
  </div>
  <div class="table-card" style="padding:18px 20px;">
    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);margin-bottom:12px;">Campaign</div>
    <div class="dn-kv"><span class="k">Title</span><span class="v">{{ $donation->campaign->title ?? 'General / Direct' }}</span></div>
    <div class="dn-kv" style="margin-top:10px;"><span class="k">Type</span><span class="v">{{ ucfirst($donation->donation_type) }}</span></div>
    <div class="dn-kv" style="margin-top:10px;"><span class="k">Coupon</span><span class="v">{{ $donation->coupon_code ?? '—' }}</span></div>
  </div>
  <div class="table-card" style="padding:18px 20px;">
    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);margin-bottom:12px;">Amounts</div>
    <div class="dn-kv"><span class="k">Total</span><span class="v" style="color:var(--green)">₹{{ number_format($donation->total_amount, 2) }}</span></div>
    <div class="dn-kv" style="margin-top:10px;"><span class="k">Platform Fee</span><span class="v">₹{{ number_format($donation->platform_fee, 2) }}</span></div>
    <div class="dn-kv" style="margin-top:10px;"><span class="k">Net to Campaign</span><span class="v">₹{{ number_format($donation->net_amount, 2) }}</span></div>
    @if($donation->discount_amount > 0)
      <div class="dn-kv" style="margin-top:10px;"><span class="k">Discount</span><span class="v">−₹{{ number_format($donation->discount_amount, 2) }}</span></div>
    @endif
  </div>
</div>

<div class="table-card" style="padding:18px 20px;margin-bottom:18px;">
  <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);margin-bottom:12px;">Payment</div>
  <div class="dn-grid">
    <div class="dn-kv"><span class="k">Payment ID</span><span class="v" style="font-family:var(--mono)">{{ $donation->payment_id ?? '—' }}</span></div>
    <div class="dn-kv"><span class="k">Order ID</span><span class="v" style="font-family:var(--mono)">{{ $donation->order_id ?? '—' }}</span></div>
    <div class="dn-kv"><span class="k">Gateway</span><span class="v">{{ ucfirst($donation->payment_gateway ?? 'razorpay') }}</span></div>
    <div class="dn-kv"><span class="k">Currency</span><span class="v">{{ $donation->currency }}</span></div>
    <div class="dn-kv"><span class="k">Receipt</span><span class="v" style="font-family:var(--mono)">{{ $donation->receipt_number ?? '—' }}</span></div>
    <div class="dn-kv"><span class="k">Paid At</span><span class="v">{{ $donation->paid_at ? $donation->paid_at->format('d M Y H:i') : '—' }}</span></div>
    <div class="dn-kv"><span class="k">Refunded At</span><span class="v">{{ $donation->refunded_at ? $donation->refunded_at->format('d M Y H:i') : '—' }}</span></div>
    <div class="dn-kv"><span class="k">Signature</span><span class="v" style="font-family:var(--mono);font-size:11px;">{{ $donation->signature ? 'present' : '—' }}</span></div>
    <div class="dn-kv"><span class="k">Created</span><span class="v">{{ $donation->created_at->format('d M Y H:i') }}</span></div>
  </div>
  @if($donation->message)
    <div class="dn-kv" style="margin-top:14px;"><span class="k">Donor Message</span><span class="v" style="font-weight:400;white-space:pre-wrap">{{ $donation->message }}</span></div>
  @endif
</div>

@if($donation->donation_type === 'product' && $donation->items->isNotEmpty())
<div class="sec-hdr">
  <div class="sec-ttl">Purchased Products</div>
  <div style="font-size:12px;color:var(--text3);font-family:var(--mono);">{{ $donation->items->count() }} item(s)</div>
</div>

<div class="table-card">
  <div class="table-scroll">
    <table style="min-width:640px">
      <thead>
        <tr>
          <th>Product</th>
          <th>Campaign</th>
          <th>Unit Price</th>
          <th>Qty</th>
          <th>Line Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($donation->items as $item)
        <tr>
          <td data-label="Product">
            @if($item->product)
              <div style="display:flex;align-items:center;gap:10px;">
                @if($item->product->image)
                  <img src="{{ asset('storage/' . $item->product->image) }}" alt="" style="width:36px;height:36px;border-radius:8px;object-fit:cover;border:1px solid var(--border2);">
                @endif
                <span style="font-weight:600;">{{ $item->product->name }}</span>
              </div>
            @else
              <span style="color:var(--text3);">Product #{{ $item->product_id }} (removed)</span>
            @endif
          </td>
          <td data-label="Campaign" style="font-size:12.5px;">{{ $item->product?->campaign?->title ?? '—' }}</td>
          <td data-label="Unit Price" class="cell-mono">₹{{ number_format($item->price, 2) }}</td>
          <td data-label="Qty" class="cell-mono">{{ $item->quantity }}</td>
          <td data-label="Line Total" class="cell-mono" style="font-weight:600;color:var(--green)">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" style="text-align:right;font-weight:700;color:var(--text2);">Total</td>
          <td class="cell-mono" style="font-weight:700;color:var(--green)">₹{{ number_format($donation->items->sum(fn($i) => $i->price * $i->quantity), 2) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
@endif

<div class="sec-hdr">
  <div class="sec-ttl">Refund History</div>
  <div style="font-size:12px;color:var(--text3);font-family:var(--mono);">{{ $donation->refunds->count() }} record(s)</div>
</div>

<div class="table-card">
  <div class="table-scroll">
    <table style="min-width:640px">
      <thead>
        <tr>
          <th>Refund ID</th>
          <th>Gateway Refund ID</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Reason</th>
          <th>Processed At</th>
        </tr>
      </thead>
      <tbody>
        @forelse($donation->refunds as $r)
        <tr>
          <td class="cell-id" data-label="Refund ID">#{{ $r->id }}</td>
          <td data-label="Gateway" class="cell-mono" style="font-size:11.5px;">{{ $r->gateway_refund_id ?? '—' }}</td>
          <td data-label="Amount" class="cell-mono">₹{{ number_format($r->amount, 2) }}</td>
          <td data-label="Status">
            @switch($r->status)
              @case('processed')<span class="dn-badge dn-processed">✓ Processed</span>@break
              @case('failed')<span class="dn-badge dn-failedr">✕ Failed</span>@break
              @case('pending')<span class="dn-badge dn-pendingr">● Pending</span>@break
            @endswitch
          </td>
          <td data-label="Reason" style="font-size:12px;color:var(--text2);max-width:260px;">{{ $r->reason ?? '—' }}</td>
          <td data-label="Processed" class="cell-date">{{ $r->processed_at ? $r->processed_at->format('d M Y H:i') : '—' }}</td>
        </tr>
        @empty
        <tr class="empty-row">
          <td colspan="6" style="text-align:center;padding:40px 20px;">
            <div class="empty-inner">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m4 0h1M3 10l2-5h14l2 5v9a1 1 0 01-1 1H4a1 1 0 01-1-1v-9z"/></svg>
              <strong>No refunds yet</strong>
              <span>This donation has not been refunded.</span>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Refund confirmation modal --}}
<div id="refundOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-modal" data-target="#refundOverlay">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="modal-head">
      <div class="modal-ico" style="background:var(--amber-lt);">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
      </div>
      <div>
        <div class="modal-ttl">Confirm Refund</div>
        <div class="modal-sub">This is a real financial action</div>
      </div>
    </div>
    <div class="modal-body">
      Refund <strong id="refundAmount" style="font-family:var(--mono)">₹0.00</strong> for <strong id="refundDonor">"donation"</strong>? The full amount will be returned to the donor via Razorpay. This cannot be undone.
      <textarea id="refundReason" name="reason" rows="2" placeholder="Reason (optional)…" style="width:100%;margin-top:12px;padding:8px 10px;border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;font-family:var(--font);background:var(--surface2);color:var(--text);resize:vertical"></textarea>
    </div>
    <div class="modal-acts">
      <button type="button" data-action="close-modal" data-target="#refundOverlay" class="btn btn-secondary modal-btn modal-cancel">Cancel</button>
      <form id="refundForm" method="POST" style="flex:1;">
        @csrf
        <button type="submit" class="btn btn-red modal-btn modal-red">↺ Confirm Refund</button>
      </form>
    </div>
  </div>
</div>

{{-- Page data for donations-show.js --}}
@php
    $donationsShowData = [
        'refundUrl' => route('admin.donations.refund', ':id'),
        'success' => session('success'),
        'error' => session('error'),
        'info' => session('info'),
    ];
@endphp
<script type="application/json" id="donationsShowData">@json($donationsShowData)</script>

@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/donations-show.js')
@endpush
