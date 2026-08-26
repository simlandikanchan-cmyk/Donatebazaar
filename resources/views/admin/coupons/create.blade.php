@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@vite('resources/css/admin/pages/coupons-form.css')
@endpush

@extends('layouts.admin')

@section('sidebar_coupons', 'active')
@section('page_title', 'Create Coupon')
@section('page_subtitle', 'Add a new discount coupon or promo code')

@section('content')
<div class="coupons-back-wrap">
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        All Coupons
    </a>
</div>

@if($errors->any())
<div class="alert-error">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <div>
        <strong>Please fix the following:</strong>
        <ul class="coupons-err-list">
            @foreach($errors->all() as $e)<li class="coupons-err-item">{{ $e }}</li>@endforeach
        </ul>
    </div>
</div>
@endif

<form method="POST" action="{{ route('admin.coupons.store') }}">
@csrf

<div class="card">
    <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2M15 11v2M15 17v2M5 5h14a2 2 0 012 2v3a2 2 0 010 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 010-4V7a2 2 0 012-2z"/></svg></div>
        <span class="card-head-title">Coupon Details</span>
    </div>
    <div class="card-body">
        <div class="field">
            <label class="f-label" for="code">Coupon Code <span class="req">*</span></label>
            <input id="code" name="code" type="text" value="{{ old('code') }}"
                   class="f-input coupons-code-input {{ $errors->has('code')?'err':'' }}" placeholder="e.g. SAVE500" required>
            @error('code')<p class="f-error">{{ $message }}</p>@enderror
            <p class="f-hint">Unique code donors will enter at checkout.</p>
        </div>

        <div class="f-row">
            <div class="field">
                <label class="f-label" for="discount_type">Discount Type <span class="req">*</span></label>
                <select id="discount_type" name="discount_type" class="f-select" required>
                    <option value="fixed" @selected(old('discount_type','fixed')==='fixed')>Fixed amount (₹)</option>
                    <option value="percent" @selected(old('discount_type')==='percent')>Percentage (%)</option>
                </select>
                @error('discount_type')<p class="f-error">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label class="f-label" for="discount_value">Discount Value <span class="req">*</span></label>
                <input id="discount_value" name="discount_value" type="number" step="0.01" min="0"
                       value="{{ old('discount_value') }}" class="f-input {{ $errors->has('discount_value')?'err':'' }}" required>
                @error('discount_value')<p class="f-error">{{ $message }}</p>@enderror
                <p class="f-hint" id="valueHint">Amount in ₹ to deduct.</p>
            </div>
        </div>

        <div class="field" id="maxDiscountField" style="display:none;">
            <label class="f-label" for="max_discount">Max Discount (₹)</label>
            <input id="max_discount" name="max_discount" type="number" step="0.01" min="0"
                   value="{{ old('max_discount') }}" class="f-input">
            <p class="f-hint">Cap the discount for percentage coupons (leave empty for no cap).</p>
        </div>

        <div class="f-row">
            <div class="field">
                <label class="f-label" for="min_amount">Minimum Amount (₹)</label>
                <input id="min_amount" name="min_amount" type="number" step="0.01" min="0"
                       value="{{ old('min_amount') }}" class="f-input">
                <p class="f-hint">Minimum donation required to use this coupon.</p>
            </div>
            <div class="field">
                <label class="f-label" for="usage_limit">Usage Limit</label>
                <input id="usage_limit" name="usage_limit" type="number" min="1"
                       value="{{ old('usage_limit') }}" class="f-input">
                <p class="f-hint">Max total redemptions (leave empty for unlimited).</p>
            </div>
        </div>

        <div class="f-row">
            <div class="field">
                <label class="f-label" for="user_id">Assign to User</label>
                <select id="user_id" name="user_id" class="f-select">
                    <option value="">Public (anyone can use)</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected(old('user_id')==$u->id)>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
                <p class="f-hint">Leave empty for a public promo code. If set, only this user can redeem it.</p>
            </div>
            <div class="field">
                <label class="f-label" for="campaign_id">Restrict to Campaign</label>
                <select id="campaign_id" name="campaign_id" class="f-select">
                    <option value="">Any campaign</option>
                    @foreach($campaigns as $c)
                    <option value="{{ $c->id }}" @selected(old('campaign_id')==$c->id)>{{ $c->title }}</option>
                    @endforeach
                </select>
                <p class="f-hint">Leave empty to allow use on any campaign.</p>
            </div>
        </div>

        <div class="field">
            <label class="f-label" for="expires_at">Expiry Date</label>
            <input id="expires_at" name="expires_at" type="date" value="{{ old('expires_at') }}" class="f-input">
            <p class="f-hint">Optional. Leave empty for no expiry.</p>
        </div>

        <div class="field">
            <div class="toggle-row">
                <div>
                    <div class="toggle-lbl">Active</div>
                    <div class="toggle-sub">Inactive coupons cannot be redeemed.</div>
                </div>
                <div class="sw">
                    <input type="checkbox" name="is_active" id="isActive" value="1" checked>
                    <label for="isActive"></label>
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="submit-btn">Create Coupon</button>
</form>

@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/coupons-create.js')
@endpush
