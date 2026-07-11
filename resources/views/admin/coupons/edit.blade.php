@extends('layouts.admin')

@section('sidebar_coupons', 'active')
@section('page_title', 'Edit Coupon')
@section('page_subtitle', 'Update coupon settings')

@push('page_styles')
<style>
    .back-btn{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 16px;background:var(--surface2);color:var(--text2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;font-weight:600;cursor:pointer;transition:all var(--ease);font-family:var(--font);text-decoration:none;}
    .back-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
    .back-btn svg{width:13px;height:13px;}

    .alert-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.22);color:#b91c1c;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;}
    .alert-error svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}

    .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;margin-bottom:16px;}
    .card-head{display:flex;align-items:center;gap:10px;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);}
    .card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .card-head-icon svg{width:14px;height:14px;}
    .card-head-title{font-size:11.5px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
    .card-body{padding:22px;}

    .field{margin-bottom:20px;}
    .field:last-child{margin-bottom:0;}
    .f-label{display:block;font-size:11.5px;font-weight:600;color:var(--text2);margin-bottom:7px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
    .f-label .req{color:var(--red);margin-left:2px;}
    .f-input,.f-select{width:100%;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color .2s,box-shadow .2s,background .2s;}
    .f-input:focus,.f-select:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
    .f-input.err{border-color:var(--red);}
    .f-hint{font-size:11px;color:var(--text3);margin-top:5px;line-height:1.5;}
    .f-error{font-size:11.5px;color:var(--red);margin-top:5px;font-family:var(--mono);}
    .f-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
    @media(max-width:680px){.f-row{grid-template-columns:1fr;}}

    .toggle-row{display:flex;align-items:center;justify-content:space-between;padding:4px 0;}
    .toggle-lbl{font-size:13px;font-weight:600;color:var(--text);}
    .toggle-sub{font-size:11.5px;color:var(--text3);margin-top:2px;}
    .sw{position:relative;flex-shrink:0;}
    .sw input{position:absolute;opacity:0;width:0;height:0;}
    .sw label{display:block;width:46px;height:26px;border-radius:100px;background:var(--border2);cursor:pointer;position:relative;transition:background .2s;}
    .sw label::after{content:'';position:absolute;width:20px;height:20px;border-radius:50%;background:#fff;top:3px;left:3px;transition:transform .25s cubic-bezier(.4,0,.2,1);box-shadow:0 1px 4px rgba(0,0,0,.2);}
    .sw input:checked+label{background:var(--a);}
    .sw input:checked+label::after{transform:translateX(20px);}

    .submit-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:13px 20px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:14px;font-weight:700;cursor:pointer;font-family:var(--mono);letter-spacing:-.01em;transition:opacity .2s,transform .15s;box-shadow:0 4px 18px rgba(37,99,235,.35);}
    .submit-btn:hover{opacity:.88;transform:translateY(-1px);}
</style>
@endpush

@section('content')
<div style="margin-bottom:18px;">
    <a href="{{ route('admin.coupons.index') }}" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        All Coupons
    </a>
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

<button type="submit" class="submit-btn">Update Coupon</button>
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
