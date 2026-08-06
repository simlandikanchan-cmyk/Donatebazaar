@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush


@section('sidebar_gift_cards', 'active')
@section('page_title', 'Gift Cards')
@section('page_subtitle', 'Manage gift card orders')

@section('content')

{{-- Stats --}}
<div class="gc-stats-grid">
    @foreach([
        ['label'=>'Total',    'val'=>$stats['total'],    'color'=>'#6366f1'],
        ['label'=>'Pending',  'val'=>$stats['pending'],  'color'=>'#f59e0b'],
        ['label'=>'Sent',     'val'=>$stats['sent'],     'color'=>'#3b82f6'],
        ['label'=>'Redeemed', 'val'=>$stats['redeemed'], 'color'=>'#10b981'],
        ['label'=>'Expired',  'val'=>$stats['expired'],  'color'=>'#9ca3af'],
        ['label'=>'Revenue',  'val'=>'₹'.number_format($stats['revenue'],0), 'color'=>'#10b981'],
    ] as $s)
    <div class="gc-stat-card">
        <div class="gc-stat-label">{{ $s['label'] }}</div>
        <div class="gc-stat-value" style="color:{{ $s['color'] }};">{{ $s['val'] }}</div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" class="gc-filter-form" role="search">
    <label for="gc-search" class="gc-visually-hidden">Search gift cards</label>
    <input id="gc-search" class="gc-input" type="text" name="search" value="{{ $search }}"
           placeholder="Search code, name, email…">

    <label for="gc-status" class="gc-visually-hidden">Filter by status</label>
    <select id="gc-status" class="gc-select" name="status" onchange="this.form.submit()">
        @foreach(['all','pending','sent','redeemed','expired','cancelled'] as $s)
        <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>

    <x-button variant="secondary" type="submit">Search</x-button>

    @if($search || $status !== 'all')
    <x-button variant="primary" href="{{ route('admin.gift-cards.index') }}" class="gc-btn-clear">Clear filters</x-button>
    @endif
</form>

{{-- Flash --}}
@if(session('success'))
<div class="alert-ok">{{ session('success') }}</div>
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
                               class="gc-action-btn gc-action-view"
                               aria-label="View gift card {{ $gc->code }}">
                                View
                            </a>
                            @if($gc->isPaid() && !$gc->isRedeemed())
                            <form method="POST" action="{{ route('admin.gift-cards.resend', $gc->id) }}" style="display:inline;">
                                @csrf
                                <x-button variant="destructive" type="submit" class="gc-action-resend">
                                    Resend
                                </x-button>
                            </form>
                            @endif
                            @if(!$gc->isRedeemed())
                            <form method="POST" action="{{ route('admin.gift-cards.destroy', $gc->id) }}"
                                  onsubmit="return confirm('Cancel this gift card?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <x-button variant="secondary" type="submit" class="gc-action-cancel">
                                    Cancel
                                </x-button>
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
    <div class="gc-pagination">
        {{ $giftCards->appends(request()->query())->links('vendor.pagination.admin') }}
    </div>
</div>
@endsection
