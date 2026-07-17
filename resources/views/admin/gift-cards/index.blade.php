@extends('layouts.admin')

@section('sidebar_gift_cards', 'active')
@section('page_title', 'Gift Cards')
@section('page_subtitle', 'Manage gift card orders')

@section('content')

<style>
    .gc-stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:12px; margin-bottom:24px; }
    .gc-stat-card { background:var(--surface); border:1px solid var(--border); border-radius:14px; padding:16px 18px; }
    .gc-stat-label { font-size:10px; color:var(--text3); text-transform:uppercase; letter-spacing:.08em; font-family:var(--font-mono); }
    .gc-stat-value { font-size:22px; font-weight:700; font-family:var(--font-mono); margin-top:4px; }

    .gc-filter-form { display:flex; gap:10px; margin-bottom:18px; flex-wrap:wrap; align-items:center; }
    .gc-visually-hidden { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }
    .gc-input, .gc-select { height:36px; border-radius:9px; border:1px solid var(--border2); background:var(--surface2); padding:0 12px; font-size:12.5px; color:var(--text); outline:none; }
    .gc-input { flex:1; min-width:200px; }
    .gc-btn { height:36px; padding:0 18px; border:none; border-radius:9px; font-size:12.5px; font-weight:600; cursor:pointer; }
    .gc-btn-primary { background:#6366f1; color:#fff; }
    .gc-btn-clear { background:var(--surface2); color:var(--text2); border:1px solid var(--border2); text-decoration:none; display:inline-flex; align-items:center; padding:0 16px; }

    .gc-flash-success { background:rgba(16,185,129,0.09); border:1px solid rgba(16,185,129,0.25); color:#065f46; padding:10px 14px; border-radius:10px; font-size:13px; margin-bottom:16px; }

    .gc-table-wrap { background:var(--surface); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
    .gc-scroll { overflow-x:auto; }
    .gc-table { width:100%; border-collapse:collapse; min-width:900px; }
    .gc-table thead th { padding:10px 14px; font-size:10px; font-weight:700; color:var(--text3); text-transform:uppercase; letter-spacing:.08em; text-align:left; white-space:nowrap; position:sticky; top:0; background:var(--surface2); border-bottom:1px solid var(--border); z-index:1; }
    .gc-table tbody tr { border-bottom:1px solid var(--border); }
    .gc-table td { padding:12px 14px; vertical-align:middle; }

    .gc-code { font-family:monospace; font-size:12px; color:var(--text); font-weight:600; }
    .gc-amount { font-weight:600; color:#10b981; }
    .gc-primary-name { font-size:12.5px; font-weight:500; color:var(--text); }
    .gc-secondary-email { font-size:10.5px; color:var(--text3); }
    .gc-theme-dot { display:inline-block; width:10px; height:10px; border-radius:50%; margin-right:5px; vertical-align:middle; }
    .gc-theme-label { font-size:12px; color:var(--text2); }
    .gc-badge { font-size:10px; font-weight:700; padding:3px 8px; border-radius:100px; text-transform:uppercase; font-family:monospace; }
    .gc-date { font-size:11.5px; color:var(--text3); font-family:monospace; white-space:nowrap; }
    .gc-actions { display:flex; gap:5px; }
    .gc-action-btn { padding:5px 10px; border-radius:7px; font-size:11px; font-weight:600; text-decoration:none; cursor:pointer; border:1px solid transparent; }
    .gc-action-view { background:rgba(99,102,241,0.10); color:#6366f1; border-color:rgba(99,102,241,0.2); }
    .gc-action-resend { background:rgba(59,130,246,0.10); color:#1d4ed8; border-color:rgba(59,130,246,0.2); }
    .gc-action-cancel { background:rgba(239,68,68,0.10); color:#991b1b; border-color:rgba(239,68,68,0.2); }
    .gc-empty { padding:48px; text-align:center; color:var(--text3); font-size:13px; }
    .gc-pagination { padding:12px 16px; border-top:1px solid var(--border); }
</style>

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

    <button type="submit" class="gc-btn gc-btn-primary">Search</button>

    @if($search || $status !== 'all')
    <a href="{{ route('admin.gift-cards.index') }}" class="gc-btn-clear">Clear filters</a>
    @endif
</form>

{{-- Flash --}}
@if(session('success'))
<div class="gc-flash-success">{{ session('success') }}</div>
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
                                <button type="submit" class="gc-action-btn gc-action-resend"
                                        aria-label="Resend gift card {{ $gc->code }}">
                                    Resend
                                </button>
                            </form>
                            @endif
                            @if(!$gc->isRedeemed())
                            <form method="POST" action="{{ route('admin.gift-cards.destroy', $gc->id) }}"
                                  onsubmit="return confirm('Cancel this gift card?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-secondary gc-action-btn gc-action-cancel"
                                        aria-label="Cancel gift card {{ $gc->code }}">
                                    Cancel
                                </button>
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