@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@vite('resources/css/admin/pages/donations-index.css')
@endpush

@extends('layouts.admin')

@section('sidebar_donations', 'active')
@section('page_title', 'Donations')
@section('page_subtitle', 'All donations across campaigns')

@push('page_styles')
@endpush

@section('content')

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Finance</div>
    <div class="hero-name">Donations</div>
    <div class="hero-sub">Browse, filter, and refund donations. Refunds are real financial actions — confirm before proceeding.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-teal">{{ $counts['total'] }} total</span>
      @if($counts['refundable'] > 0)
        <span class="hero-badge hb-amber">● {{ $counts['refundable'] }} refundable</span>
      @endif
      @if($counts['refunded'] > 0)
        <span class="hero-badge hb-gray">↺ {{ $counts['refunded'] }} refunded</span>
      @endif
    </div>
  </div>
</div>

<div class="stats-grid don-stats-grid" style="grid-template-columns:repeat(4,1fr)">
  <div class="stat" data-action="navigate" data-href="?filter=all" style="cursor:pointer">
    <div class="stat-icon si-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total</div><div class="stat-val sv-teal">{{ $counts['total'] }}</div><div class="stat-foot">All donations</div></div>
  </div>
  <div class="stat" data-action="navigate" data-href="?filter=completed" style="cursor:pointer">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Completed</div><div class="stat-val sv-green">{{ $counts['completed'] }}</div><div class="stat-foot">Paid</div></div>
  </div>
  <div class="stat" data-action="navigate" data-href="?filter=refundable" style="cursor:pointer">
    <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m4 0h1M3 10l2-5h14l2 5v9a1 1 0 01-1 1H4a1 1 0 01-1-1v-9z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Refundable</div><div class="stat-val sv-amber">{{ $counts['refundable'] }}</div><div class="stat-foot">Completed & not refunded</div></div>
  </div>
  <div class="stat" data-action="navigate" data-href="?filter=refunded" style="cursor:pointer">
    <div class="stat-icon si-gray"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m4 0h1M3 10l2-5h14l2 5v9a1 1 0 01-1 1H4a1 1 0 01-1-1v-9z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Refunded</div><div class="stat-val sv-gray">{{ $counts['refunded'] }}</div><div class="stat-foot">Money returned</div></div>
  </div>
</div>

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

<div class="filter-row">
  <div class="ftabs" id="ftabs">
    <button class="ftab on" data-filter="all">All <span class="cnt">{{ $counts['total'] }}</span></button>
    <button class="ftab" data-filter="completed">Completed <span class="cnt">{{ $counts['completed'] }}</span></button>
    <button class="ftab" data-filter="refundable">Refundable <span class="cnt">{{ $counts['refundable'] }}</span></button>
    <button class="ftab" data-filter="refunded">Refunded <span class="cnt">{{ $counts['refunded'] }}</span></button>
  </div>
  <select class="ftab-select" onchange="var btn=document.querySelector('.ftab[data-filter=&quot;'+this.value+'&quot;]');if(btn)btn.click();">
    <option value="all">All ({{ $counts['total'] }})</option>
    <option value="completed">Completed ({{ $counts['completed'] }})</option>
    <option value="refundable">Refundable ({{ $counts['refundable'] }})</option>
    <option value="refunded">Refunded ({{ $counts['refunded'] }})</option>
  </select>
  <div class="filter-right">
    <select class="sort-sel" id="campaignSelect">
      <option value="">All campaigns</option>
      @foreach($campaigns as $id => $title)
        <option value="{{ $id }}" {{ request('campaign_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
      @endforeach
    </select>
    <div class="swrap">
      <svg class="sico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" id="searchInput" class="sinp" placeholder="Search donor, receipt, payment id…" value="{{ request('q') }}">
    </div>
  </div>
</div>

<div class="sec-hdr">
  <div class="sec-ttl">Donations</div>
  <div style="font-size:12px;color:var(--text3);font-family:var(--mono);">
    Showing <strong style="color:var(--text);">{{ $donations->firstItem() }}</strong>–<strong style="color:var(--text);">{{ $donations->lastItem() }}</strong> of <strong style="color:var(--text);">{{ $donations->total() }}</strong>
  </div>
</div>

<div class="table-card">
  <div class="table-scroll">
    <table id="donationTable">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Donor</th>
          <th>Campaign</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Refunded</th>
          <th>Date</th>
          <th style="text-align:right">Actions</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        @forelse($donations as $i => $d)
        @php
          $rowFilter = ($d->payment_status === 'completed' && !$d->is_refunded) ? 'refundable' : $d->payment_status;
        @endphp
        <tr
          data-filter="{{ $rowFilter }}"
          data-campaign="{{ $d->campaign_id ?? '' }}"
          data-donor="{{ strtolower($d->donor_name ?? ($d->user->name ?? '')) }} {{ strtolower($d->donor_email ?? '') }}"
          data-date="{{ $d->created_at }}"
        >
          <td class="cell-id" data-label="#">{{ $donations->firstItem() + $i }}</td>

          <td data-label="Donor">
            @if($d->is_anonymous)
              <span class="dn-anon">Anonymous</span>
            @else
              <div style="font-size:13px;font-weight:600;color:var(--text)">{{ $d->donor_name ?? ($d->user->name ?? 'Guest') }}</div>
              <div class="cell-date-sub">{{ $d->donor_email ?? ($d->user->email ?? '') }}</div>
            @endif
          </td>

          <td data-label="Campaign">
            @if($d->campaign)
              <div style="font-size:13px;font-weight:600;color:var(--text)">{{ $d->campaign->title }}</div>
            @else
              <span style="color:var(--text3);font-size:12px;">General / Direct</span>
            @endif
          </td>

          <td data-label="Amount" class="cell-mono" style="color:var(--green)">₹{{ number_format($d->total_amount, 2) }}</td>

          <td data-label="Status">
            @switch($d->payment_status)
              @case('completed')<span class="dn-badge dn-completed">● Completed</span>@break
              @case('pending')<span class="dn-badge dn-pending">● Pending</span>@break
              @case('failed')<span class="dn-badge dn-failed">● Failed</span>@break
              @case('refunded')<span class="dn-badge dn-refunded">↺ Refunded</span>@break
            @endswitch
          </td>

          <td data-label="Refunded">
            @if($d->is_refunded)
              <span class="dn-badge dn-yes">✓ Yes</span>
            @else
              <span class="dn-badge dn-no">— No</span>
            @endif
          </td>

          <td data-label="Date" class="cell-date">
            {{ $d->created_at->format('d M Y') }}
            <div class="cell-date-sub">{{ $d->created_at->format('H:i') }}</div>
          </td>

          <td data-label="Actions">
            <div class="act-btns">
              <a href="{{ route('admin.donations.show', $d->id) }}" class="btn btn-secondary act-btn ab-view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>View</span>
              </a>
              @if($d->payment_status === 'completed' && !$d->is_refunded)
                <button type="button" data-action="open-refund" data-id="{{ $d->id }}" data-donor="{{ addslashes($d->donor_name ?? 'this donation') }}" data-amount="{{ $d->total_amount }}" class="act-btn ab-refund">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m4 0h1M3 10l2-5h14l2 5v9a1 1 0 01-1 1H4a1 1 0 01-1-1v-9z"/></svg>
                  <span>Refund</span>
                </button>
              @endif
              <form method="POST" action="{{ route('admin.donations.destroy', $d->id) }}" style="display:inline;" data-confirm="Delete this donation record? This cannot be undone.">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-red act-btn ab-delete" title="Delete">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr class="empty-row">
          <td colspan="8" style="text-align:center;padding:56px 20px;">
            <div class="empty-inner">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
              <strong>No donations found</strong>
              <span>Try adjusting your filters or search.</span>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    <div id="noResults"></div>
  </div>

  <div class="pagination-wrap" style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-top:1px solid var(--border);flex-wrap:wrap;gap:10px;">
    <div style="font-size:12px;color:var(--text3);font-family:var(--mono);">
      Showing <strong style="color:var(--text);">{{ $donations->firstItem() }}</strong>–<strong style="color:var(--text);">{{ $donations->lastItem() }}</strong> of <strong style="color:var(--text);">{{ $donations->total() }}</strong>
    </div>
    {{ $donations->onEachSide(1)->links('vendor.pagination.admin') }}
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

@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/donations-index.js')
@endpush
