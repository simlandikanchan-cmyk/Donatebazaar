@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush


@section('sidebar_coupons', 'active')
@section('page_title', 'Coupons')
@section('page_subtitle', 'Manage discount coupons & promo codes')

@section('content')

{{-- Stats --}}
<div class="cp-stats-grid">
    @foreach([
        ['label'=>'Total',   'val'=>$stats['total'],   'color'=>'#6366f1'],
        ['label'=>'Active',  'val'=>$stats['active'],  'color'=>'#10b981'],
        ['label'=>'Expired', 'val'=>$stats['expired'], 'color'=>'#9ca3af'],
    ] as $s)
    <div class="cp-stat-card">
        <div class="cp-stat-label">{{ $s['label'] }}</div>
        <div class="cp-stat-value" style="color:{{ $s['color'] }};">{{ $s['val'] }}</div>
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

    <x-button variant="secondary" type="submit">Search</x-button>

    @if(($search ?? '') || ($status ?? 'all') !== 'all')
    <x-button variant="primary" href="{{ route('admin.coupons.index') }}" class="cp-btn-clear">Clear filters</x-button>
    @endif

    <x-button variant="secondary" href="{{ route('admin.coupons.create') }}">+ New Coupon</x-button>
</form>

@if(session('success'))
<div class="alert-ok">{{ session('success') }}</div>
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
                $isExpired = $cp->expires_at && \Carbon\Carbon::parse($cp->expires_at)->endOfDay()->isPast();
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
                            <x-button variant="outline" href="{{ route('admin.coupons.edit', $cp) }}">Edit</x-button>
                            @if($cp->is_active)
                            <form method="POST" action="{{ route('admin.coupons.destroy', $cp) }}" onsubmit="return confirm('Deactivate this coupon?');">
                                @csrf @method('DELETE')
                                <x-button variant="secondary" type="submit" class="cp-action-cancel">Deactivate</x-button>
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
