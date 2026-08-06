@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush


@section('sidebar_gift_cards', 'active')
@section('page_title', 'Gift Card #'.$giftCard->code)
@section('page_subtitle', 'View and manage gift card details')

@section('content')
<div class="breadcrumb">
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <a href="{{ route('admin.gift-cards.index') }}">Gift Cards</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <span>#{{ $giftCard->code }}</span>
</div>

@if(session('success'))
<div class="flash flash-ok">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="flash flash-err">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('error') }}
</div>
@endif

<div class="detail-grid gc-grid">

  {{-- Gift Card Info --}}
  <div class="detail-card">
    <div class="detail-hdr">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="8" width="18" height="12" rx="2"/><path d="M12 8V4m0 0H9m3 0h3M7 12h10"/></svg>
      <span>Gift Card Details</span>
    </div>
    <div class="detail-row"><span class="detail-lbl">Code</span><span class="detail-val code-val">{{ $giftCard->code }}</span></div>
    <div class="detail-row"><span class="detail-lbl">Amount</span><span class="detail-val amount-val">₹{{ number_format($giftCard->amount, 0) }}</span></div>
    <div class="detail-row"><span class="detail-lbl">Theme</span><span class="detail-val" style="text-transform:capitalize;">{{ $giftCard->theme }}</span></div>
    <div class="detail-row"><span class="detail-lbl">Status</span><span class="detail-val"><span class="badge b-{{ $giftCard->status }}">{{ $giftCard->status }}</span></span></div>
    <div class="detail-row"><span class="detail-lbl">Payment</span><span class="detail-val"><span class="badge b-{{ $giftCard->payment_status === 'completed' ? 'redeemed' : 'cancelled' }}">{{ $giftCard->payment_status }}</span></span></div>
  </div>

  {{-- Sender / Recipient --}}
  <div class="detail-card">
    <div class="detail-hdr">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
      <span>People</span>
    </div>
    <div class="detail-row"><span class="detail-lbl">Sender</span><span class="detail-val" style="font-family:var(--font);">{{ $giftCard->sender_name }}<br><span style="font-size:11px;color:var(--text3);font-family:var(--mono);">{{ $giftCard->sender_email }}</span></span></div>
    <div class="detail-row"><span class="detail-lbl">Recipient</span><span class="detail-val" style="font-family:var(--font);">{{ $giftCard->recipient_name }}<br><span style="font-size:11px;color:var(--text3);font-family:var(--mono);">{{ $giftCard->recipient_email }}</span></span></div>
  </div>

  {{-- Dates --}}
  <div class="detail-card">
    <div class="detail-hdr">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
      <span>Timeline</span>
    </div>
    <div class="detail-row"><span class="detail-lbl">Sent</span><span class="detail-val">{{ $giftCard->send_at ? $giftCard->send_at->format('d M Y, H:i') : '—' }}</span></div>
    <div class="detail-row"><span class="detail-lbl">Expires</span><span class="detail-val">{{ $giftCard->expires_at ? $giftCard->expires_at->format('d M Y') : '—' }}</span></div>
    <div class="detail-row"><span class="detail-lbl">Redeemed</span><span class="detail-val">{{ $giftCard->redeemed_at ? $giftCard->redeemed_at->format('d M Y, H:i') : '—' }}</span></div>
  </div>

  {{-- Redemption --}}
  <div class="detail-card">
    <div class="detail-hdr">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <span>Redemption</span>
    </div>
    <div class="detail-row"><span class="detail-lbl">Redeemed By</span><span class="detail-val">{{ $giftCard->redeemedBy->name ?? '—' }}<br><span style="font-size:11px;color:var(--text3);font-family:var(--mono);">{{ $giftCard->redeemedBy->email ?? '' }}</span></span></div>
    <div class="detail-row"><span class="detail-lbl">Campaign</span><span class="detail-val">{{ $giftCard->campaign->title ?? '—' }}</span></div>
  </div>

  {{-- Message --}}
  @if($giftCard->message)
  <div class="detail-card full">
    <div class="detail-hdr">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
      <span>Message</span>
    </div>
    <div class="message-box">{{ $giftCard->message }}</div>
  </div>
  @endif

  {{-- Admin Notes --}}
  @if($giftCard->admin_note)
  <div class="detail-card full">
    <div class="detail-hdr">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      <span>Admin Note</span>
    </div>
    <div class="message-box" style="font-style:normal;">{{ $giftCard->admin_note }}</div>
  </div>
  @endif

  {{-- Actions --}}
  <div class="detail-card full">
    <div class="detail-hdr">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
      <span>Actions</span>
    </div>

    <div class="actions">
      <x-button variant="secondary" href="{{ route('admin.gift-cards.index') }}" class="ab-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to List
      </x-button>
      @if($giftCard->isPaid() && !$giftCard->isRedeemed())
      <form method="POST" action="{{ route('admin.gift-cards.resend', $giftCard->id) }}" style="display:inline;">
        @csrf
        <x-button variant="secondary" type="submit" class="ab-resend">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
          Resend Email
        </x-button>
      </form>
      @endif
      @if(!$giftCard->isRedeemed())
      <form method="POST" action="{{ route('admin.gift-cards.destroy', $giftCard->id) }}" style="display:inline;" onsubmit="return confirm('Cancel this gift card?')">
        @csrf @method('DELETE')
        <x-button variant="secondary" type="submit" class="ab-cancel">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
          Cancel Card
        </x-button>
      </form>
      @endif
    </div>

    {{-- Update Status --}}
    <form method="POST" action="{{ route('admin.gift-cards.status', $giftCard->id) }}" class="status-form">
      @csrf
      <span style="font-size:12.5px;font-weight:600;color:var(--text2);">Change Status:</span>
      <select name="status">
        @foreach(['pending','sent','redeemed','expired','cancelled'] as $s)
        <option value="{{ $s }}" @selected($giftCard->status === $s)>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
      <input type="text" name="admin_note" placeholder="Admin note (optional)" style="flex:1;min-width:160px;height:36px;padding:0 12px;border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);background:var(--surface2);outline:none;">
      <button type="submit">Update</button>
    </form>
  </div>

</div>
@endsection
