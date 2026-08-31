@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush

@extends('layouts.admin')

@section('sidebar_coupons', 'active')
@section('page_title', 'Coupons')
@section('page_subtitle', 'Manage discount coupons & promo codes')

@section('content')

<style>
    .cp-stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:12px; margin-bottom:24px; }
    .cp-stat-card { background:var(--surface); border:1px solid var(--border); border-radius:14px; padding:16px 18px; display:flex; align-items:center; gap:14px; }
    .cp-stat-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .cp-stat-icon svg { width:20px; height:20px; }
    .cp-stat-label { font-size:10px; color:var(--text3); text-transform:uppercase; letter-spacing:.08em; font-family:var(--font-mono); }
    .cp-stat-value { font-size:22px; font-weight:700; font-family:var(--font-mono); margin-top:4px; }

    .cp-filter-form { display:flex; gap:10px; margin-bottom:18px; flex-wrap:wrap; align-items:center; }
    .cp-visually-hidden { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }
    .cp-input, .cp-select { height:36px; border-radius:9px; border:1px solid var(--border2); background:var(--surface2); padding:0 12px; font-size:12.5px; color:var(--text); outline:none; }
    .cp-input { flex:1; min-width:200px; }
    .cp-btn { height:36px; padding:0 18px; border:none; border-radius:9px; font-size:12.5px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; }
    .cp-btn-primary { background:#6366f1; color:#fff; }
    .cp-btn-clear { background:var(--surface2); color:var(--text2); border:1px solid var(--border2); text-decoration:none; display:inline-flex; align-items:center; padding:0 16px; }

    .cp-flash-success { background:rgba(16,185,129,0.09); border:1px solid rgba(16,185,129,0.25); color:#065f46; padding:10px 14px; border-radius:10px; font-size:13px; margin-bottom:16px; }

    .cp-table-wrap { background:var(--surface); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
    .cp-scroll { overflow-x:auto; }
    .cp-table { width:100%; border-collapse:collapse; min-width:880px; }
    .cp-table thead th { padding:10px 14px; font-size:10px; font-weight:700; color:var(--text3); text-transform:uppercase; letter-spacing:.08em; text-align:left; white-space:nowrap; position:sticky; top:0; background:var(--surface2); border-bottom:1px solid var(--border); z-index:1; }
    .cp-table tbody tr { border-bottom:1px solid var(--border); }
    .cp-table td { padding:12px 14px; vertical-align:middle; }

    .cp-code { font-family:monospace; font-size:12px; color:var(--text); font-weight:600; }
    .cp-amount { font-weight:600; color:#10b981; }
    .cp-primary-name { font-size:12.5px; font-weight:500; color:var(--text); }
    .cp-secondary { font-size:10.5px; color:var(--text3); }
    .cp-mono { font-size:11.5px; color:var(--text3); font-family:monospace; white-space:nowrap; }
    .cp-badge { font-size:10px; font-weight:700; padding:3px 8px; border-radius:100px; text-transform:uppercase; font-family:monospace; }
    .cp-actions { display:inline-flex; gap:5px; align-items:center; }
    .cp-actions form { display:inline-flex !important; margin:0 !important; padding:0 !important; border:none; }
    .cp-action-btn {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        padding: 15px 40px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid transparent;
        appearance: none;
        background: none;
    }
    .cp-action-view { background:rgba(99,102,241,0.10); color:#6366f1; border-color:rgba(99,102,241,0.2); }
    .cp-action-cancel { background:rgba(239,68,68,0.10); color:#991b1b; border-color:rgba(239,68,68,0.2); }
    .cp-empty { padding:48px; text-align:center; color:var(--text3); font-size:13px; }
    .cp-pagination { padding:12px 16px; border-top:1px solid var(--border); }
</style>

{{-- Stats --}}
<div class="cp-stats-grid">
    @foreach([
        ['label'=>'Total',   'val'=>$stats['total'],   'color'=>'#6366f1', 'icon'=>'<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 6v6m0 0v6m0-6h6m-6 0H6\"/></svg>'],
        ['label'=>'Active',  'val'=>$stats['active'],  'color'=>'#10b981', 'icon'=>'<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\"/></svg>'],
        ['label'=>'Expired', 'val'=>$stats['expired'], 'color'=>'#9ca3af', 'icon'=>'<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><circle cx=\"12\" cy=\"12\" r=\"10\"/><polyline points=\"12 6 12 12 16 14\"/></svg>'],
    ] as $s)
    <div class="cp-stat-card">
        <div class="cp-stat-icon" style="background:{{ $s['color'] }}15;color:{{ $s['color'] }};">{!! $s['icon'] !!}</div>
        <div>
            <div class="cp-stat-label">{{ $s['label'] }}</div>
            <div class="cp-stat-value" style="color:{{ $s['color'] }};">{{ $s['val'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" class="cp-filter-form" role="search">
    <label for="cp-search" class="cp-visually-hidden">Search coupons</label>
    <input id="cp-search" class="cp-input" type="text" name="search" value="{{ $search ?? '' }}"
           placeholder="Search coupon code…">

    <label for="cp-status" class="cp-visually-hidden">Filter by status</label>
    <select id="cp-status" class="cp-select" name="status" onchange="this.form.submit()">
        @foreach(['all'=>'All','active'=>'Active','inactive'=>'Inactive','expired'=>'Expired'] as $k=>$l)
        <option value="{{ $k }}" @selected(($status ?? 'all') === $k)>{{ $l }}</option>
        @endforeach
    </select>

    <button type="submit" class="cp-btn cp-btn-primary">Search</button>

    @if(($search ?? '') || ($status ?? 'all') !== 'all')
    <a href="{{ route('admin.coupons.index') }}" class="cp-btn-clear">Clear filters</a>
    @endif

    <a href="{{ route('admin.coupons.create') }}" class="cp-btn cp-btn-primary" style="margin-left:auto;background:#10b981;">+ New Coupon</a>
</form>

@if(session('success'))
<div class="cp-flash-success">{{ session('success') }}</div>
@endif

<div class="cp-table-wrap">
    <div class="cp-scroll">
        <table class="cp-table">
            <thead>
                <tr>
                    @foreach(['Code','Discount','Scope','Min Amount','Usage','Expires','Status','Actions'] as $h)
                    <th scope="col">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $cp)
                @php
                $isExpired = $cp->expires_at && $cp->expires_at->endOfDay()->isPast();
                $statusBadge = !$cp->is_active
                    ? ['bg'=>'rgba(239,68,68,0.15)','color'=>'#991b1b','txt'=>'Inactive']
                    : ($isExpired
                        ? ['bg'=>'rgba(156,163,175,0.15)','color'=>'#6b7280','txt'=>'Expired']
                        : ['bg'=>'rgba(16,185,129,0.15)','color'=>'#065f46','txt'=>'Active']);
                $scope = $cp->user_id
                    ? ($cp->user ? $cp->user->name : 'User #'.$cp->user_id)
                    : ($cp->campaign_id ? ($cp->campaign ? $cp->campaign->title : 'Campaign #'.$cp->campaign_id) : 'Public');
                $discountLabel = $cp->discount_type === 'percent'
                    ? $cp->discount_value.'%' . ($cp->max_discount ? ' (max ₹'.number_format($cp->max_discount,0).')' : '')
                    : '₹'.number_format($cp->discount_value,0);
                @endphp
                <tr>
                    <td class="cp-code">{{ $cp->code }}</td>
                    <td class="cp-amount">{{ $discountLabel }}</td>
                    <td>
                        <div class="cp-primary-name">{{ $scope }}</div>
                        <div class="cp-secondary">{{ $cp->user_id ? 'User-specific' : ($cp->campaign_id ? 'Campaign-specific' : 'Public promo') }}</div>
                    </td>
                    <td class="cp-mono">{{ $cp->min_amount ? '₹'.number_format($cp->min_amount,0) : '—' }}</td>
                    <td class="cp-mono">{{ $cp->used_count }} / {{ $cp->usage_limit ?? '∞' }}</td>
                    <td class="cp-mono">{{ $cp->expires_at ? $cp->expires_at->format('d M Y') : '—' }}</td>
                    <td><span class="cp-badge" style="background:{{ $statusBadge['bg'] }};color:{{ $statusBadge['color'] }};">{{ $statusBadge['txt'] }}</span></td>
                    <td>
                        <div class="cp-actions">
                            <a href="{{ route('admin.coupons.edit', $cp) }}" class="cp-action-btn cp-action-view">Edit</a>
                            @if($cp->is_active)
                            <form method="POST" action="{{ route('admin.coupons.destroy', $cp) }}" data-confirm="Deactivate this coupon?" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="cp-action-btn cp-action-cancel">Deactivate</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="cp-empty">No coupons found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($coupons->hasPages())
    <div class="cp-pagination">{{ $coupons->links('vendor.pagination.admin') }}</div>
    @endif
</div>

@endsection
