@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush


@section('sidebar_coupons', 'active')
@section('page_title', 'Edit Coupon')
@section('page_subtitle', 'Update coupon settings')

@section('content')
<div style="margin-bottom:18px;">
    <x-button variant="secondary" href="{{ route('admin.coupons.index') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        All Coupons
    </x-button>
</div>

@if($errors->any())
<div class="alert-error">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <div>
        <strong>Please fix the following:</strong>
        <ul style="margin-top:4px;padding-left:16px;">
            @foreach($errors->all() as $e)<li style="font-size:12px;margin-top:2px;">{{ $e }}</li>@endforeach
        </ul>
    </div>
</div>
@endif

<form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
@csrf
@method('PATCH')

<div class="card">
    <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2M15 11v2M15 17v2M5 5h14a2 2 0 012 2v3a2 2 0 010 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 010-4V7a2 2 0 012-2z"/></svg></div>
        <span class="card-head-title">Coupon Details — {{ $coupon->code }}</span>
    </div>
    <div class="card-body">
        <div class="field">
            <label class="f-label" for="code">Coupon Code <span class="req">*</span></label>
            <input id="code" name="code" type="text" value="{{ old('code', $coupon->code) }}"
                   class="f-input {{ $errors->has('code')?'err':'' }}"
                   style="text-transform:uppercase;" required>
            @error('code')<p class="f-error">{{ $message }}</p>@enderror
        </div>

        <div class="f-row">
            <div class="field">
                <label class="f-label" for="discount_type">Discount Type <span class="req">*</span></label>
                <select id="discount_type" name="discount_type" class="f-select" onchange="toggleMaxDiscount()" required>
                    <option value="fixed" @selected(old('discount_type', $coupon->discount_type)==='fixed')>Fixed amount (₹)</option>
                    <option value="percent" @selected(old('discount_type', $coupon->discount_type)==='percent')>Percentage (%)</option>
                </select>
            </div>
            <div class="field">
                <label class="f-label" for="discount_value">Discount Value <span class="req">*</span></label>
                <input id="discount_value" name="discount_value" type="number" step="0.01" min="0"
                       value="{{ old('discount_value', $coupon->discount_value) }}" class="f-input" required>
                <p class="f-hint" id="valueHint"></p>
            </div>
        </div>

        <div class="field" id="maxDiscountField">
            <label class="f-label" for="max_discount">Max Discount (₹)</label>
            <input id="max_discount" name="max_discount" type="number" step="0.01" min="0"
                   value="{{ old('max_discount', $coupon->max_discount) }}" class="f-input">
        </div>

        <div class="f-row">
            <div class="field">
                <label class="f-label" for="min_amount">Minimum Amount (₹)</label>
                <input id="min_amount" name="min_amount" type="number" step="0.01" min="0"
                       value="{{ old('min_amount', $coupon->min_amount) }}" class="f-input">
            </div>
            <div class="field">
                <label class="f-label" for="usage_limit">Usage Limit</label>
                <input id="usage_limit" name="usage_limit" type="number" min="1"
                       value="{{ old('usage_limit', $coupon->usage_limit) }}" class="f-input">
                <p class="f-hint">Used {{ $coupon->used_count }} times so far.</p>
            </div>
        </div>

        <div class="f-row">
            <div class="field">
                <label class="f-label" for="user_id">Assign to User</label>
                <select id="user_id" name="user_id" class="f-select">
                    <option value="">Public (anyone can use)</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected(old('user_id', $coupon->user_id)==$u->id)>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label class="f-label" for="campaign_id">Restrict to Campaign</label>
                <select id="campaign_id" name="campaign_id" class="f-select">
                    <option value="">Any campaign</option>
                    @foreach($campaigns as $c)
                    <option value="{{ $c->id }}" @selected(old('campaign_id', $coupon->campaign_id)==$c->id)>{{ $c->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="field">
            <label class="f-label" for="expires_at">Expiry Date</label>
            <input id="expires_at" name="expires_at" type="date" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}" class="f-input">
        </div>

        <div class="field">
            <div class="toggle-row">
                <div>
                    <div class="toggle-lbl">Active</div>
                    <div class="toggle-sub">Inactive coupons cannot be redeemed.</div>
                </div>
                <div class="sw">
                    <input type="checkbox" name="is_active" id="isActive" value="1" @checked(old('is_active', $coupon->is_active))>
                    <label for="isActive"></label>
                </div>
            </div>
        </div>
    </div>
</div>

<x-button variant="primary" type="submit">Update Coupon</x-button>
</form>

@endsection

@push('page_scripts')
<script>
function toggleMaxDiscount(){
    var type = document.getElementById('discount_type').value;
    var field = document.getElementById('maxDiscountField');
    var hint = document.getElementById('valueHint');
    if(type === 'percent'){
        field.style.display = 'block';
        hint.textContent = 'Percentage of the donation amount.';
    } else {
        field.style.display = 'none';
        hint.textContent = 'Amount in ₹ to deduct.';
    }
}
toggleMaxDiscount();
</script>
@endpush
