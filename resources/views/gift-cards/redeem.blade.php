@extends('layouts.user')

@section('page_title', 'Redeem Gift Card')
@section('page_subtitle', 'Turn your code into a donation')

@push('page_styles')
<style>
.gr-wrap{max-width:560px;width:100%;margin:0 auto;}
.gr-head{text-align:center;margin-bottom:28px;}
.gr-eyebrow{font-size:11px;color:var(--text3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px;}
.gr-head h1{font-size:27px;font-weight:700;color:var(--text);margin-bottom:8px;letter-spacing:-0.02em;line-height:1.2;}
.gr-head p{font-size:14px;color:var(--text2);line-height:1.5;margin:0;}

.gr-card{background:var(--surface);border-radius:16px;border:1px solid var(--border);padding:20px;margin-bottom:16px;}
.gr-card-step{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px;display:flex;align-items:center;gap:8px;}
.gr-card-step .s-num{width:20px;height:20px;border-radius:50%;background:var(--accent);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:10px;font-family:var(--font-mono);font-weight:700;flex-shrink:0;}

.gr-code-row{display:flex;gap:10px;}
.gr-code-input{flex:1;height:44px;border-radius:9px;border:1.5px solid var(--border2);padding:0 14px;font-size:14px;font-family:var(--font-mono);letter-spacing:.05em;text-transform:uppercase;outline:none;background:var(--surface2);color:var(--text);transition:border-color var(--tr),box-shadow var(--tr);}
.gr-code-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.gr-code-btn{padding:0 22px;height:44px;background:var(--accent);color:#fff;border:none;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;transition:opacity var(--tr);}
.gr-code-btn:hover{opacity:.9;}
.gr-code-btn:disabled{opacity:.6;cursor:not-allowed;}
.gr-status{margin-top:10px;font-size:13px;}

.gr-camp-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;max-height:360px;overflow-y:auto;}
.gr-camp-card{border:2px solid var(--border);border-radius:12px;overflow:hidden;cursor:pointer;transition:border-color .15s;background:var(--surface);}
.gr-camp-card:hover{border-color:var(--accent-glow);}
.gr-camp-card.selected{border-color:var(--accent);}
.gr-camp-thumb{height:80px;background:var(--surface2);background-size:cover;background-position:center;}
.gr-camp-info{padding:10px;}
.gr-camp-title{font-size:12px;font-weight:600;color:var(--text);line-height:1.4;margin-bottom:4px;}
.gr-camp-stats{font-size:10px;color:var(--text3);}

.gr-field{margin-bottom:12px;}
.gr-field:last-of-type{margin-bottom:16px;}
.gr-field label{font-size:12px;color:var(--text2);display:block;margin-bottom:5px;font-weight:600;}
.gr-field input{width:100%;height:38px;border-radius:9px;border:1.5px solid var(--border2);padding:0 12px;font-size:13px;outline:none;background:var(--surface2);color:var(--text);transition:border-color var(--tr),box-shadow var(--tr);}
.gr-field input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.gr-field-hint{font-size:11px;color:var(--text3);margin-top:5px;}

.gr-redeem-btn{width:100%;padding:14px;background:var(--accent);color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:600;cursor:pointer;transition:opacity var(--tr);}
.gr-redeem-btn:disabled{opacity:.5;cursor:not-allowed;}
.gr-redeem-btn:not(:disabled):hover{opacity:.9;}

.gr-foot{text-align:center;margin-top:16px;font-size:12px;color:var(--text3);}
.gr-foot a{color:var(--accent);font-weight:600;text-decoration:none;}
.gr-foot a:hover{text-decoration:underline;}

.alert-error{background:#fee2e2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:16px;}
.alert-error p{margin:0;}

@media (max-width:640px){
    .gr-head h1{font-size:22px;}
    .gr-camp-grid{grid-template-columns:1fr;max-height:none;gap:8px;}
    .gr-card{padding:14px;}
    .gr-code-row{flex-direction:column;gap:8px;}
    .gr-code-btn{height:40px;}
    .gr-card-step{gap:6px;}
}
@media (max-width:480px){
    .gr-head{margin-bottom:20px;}
    .gr-head h1{font-size:20px;}
    .gr-head p{font-size:13px;}
    .gr-card-step{font-size:11px;gap:5px;}
    .gr-card-step .s-num{width:18px;height:18px;font-size:9px;}
    .gr-camp-thumb{height:60px;}
    .gr-camp-info{padding:8px 10px;}
    .gr-camp-title{font-size:11px;}
}
</style>
@endpush

@section('content')
<div class="gr-wrap">

    <div class="gr-head">
        <div class="gr-eyebrow">DonateBazaar</div>
        <h1>Redeem Your Gift Card</h1>
        <p>Enter your code and turn it into a donation for a cause you love.</p>
    </div>

    @if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
    <div class="alert-error">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <div class="gr-card">
        <div class="gr-card-step"><span class="s-num">1</span>Gift card code</div>
        <div class="gr-code-row">
            <input type="text" id="giftCode" placeholder="DNBZ-XXXX-XXXX" maxlength="20" class="gr-code-input">
            <button onclick="checkCode()" id="checkBtn" class="gr-code-btn">Check</button>
        </div>
        <div id="codeStatus" class="gr-status"></div>
    </div>

    <div id="redeemFormWrap" style="display:none;">

        <div class="gr-card">
            <div class="gr-card-step"><span class="s-num">2</span>Choose a campaign</div>
            <div class="gr-camp-grid" id="campGrid">
                @forelse($campaigns as $c)
                <div onclick="selectCampaign({{ $c->id }}, this)" id="camp-{{ $c->id }}" class="gr-camp-card">
                    <div class="gr-camp-thumb" @if($c->cover_image) style="background-image:url('{{ asset('storage/'.$c->cover_image) }}')" @endif></div>
                    <div class="gr-camp-info">
                        <div class="gr-camp-title">{{ \Illuminate\Support\Str::limit($c->title, 40) }}</div>
                        <div class="gr-camp-stats">₹{{ number_format($c->raised_amount) }} raised of ₹{{ number_format($c->goal_amount) }}</div>
                    </div>
                </div>
                @empty
                <p style="font-size:13px;color:var(--text3);grid-column:1/-1;">No active campaigns available right now.</p>
                @endforelse
            </div>
        </div>

        <div class="gr-card">
            <div class="gr-card-step"><span class="s-num">3</span>Your details</div>
            <form method="POST" action="{{ route('gift-cards.redeem.submit') }}" id="redeemForm">
                @csrf
                <input type="hidden" name="code" id="hiddenCode">
                <input type="hidden" name="campaign_id" id="hiddenCampaignId">

                <div class="gr-field">
                    <label>Your name *</label>
                    <input type="text" name="donor_name" required>
                </div>
                <div class="gr-field">
                    <label>Your email *</label>
                    <input type="email" name="donor_email" id="donorEmail" required>
                    <p class="gr-field-hint" id="emailHint"></p>
                </div>

                <button type="submit" id="redeemBtn" disabled class="gr-redeem-btn">
                    Select a campaign to continue
                </button>
            </form>
        </div>

    </div>

    <p class="gr-foot">
        Don't have a gift card yet?
        <a href="{{ route('gift-cards.index') }}">Buy one here</a>
    </p>

</div>
@endsection

@push('page_scripts')
<script>
var validatedCode = null;
var selectedCampaignId = null;

function checkCode() {
    var code = document.getElementById('giftCode').value.trim().toUpperCase();
    var statusEl = document.getElementById('codeStatus');
    var btn = document.getElementById('checkBtn');

    if (!code) {
        statusEl.innerHTML = '<span style="color:#b91c1c;">Please enter a code.</span>';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Checking…';
    statusEl.innerHTML = '';

    fetch('{{ route("gift-cards.validate-code") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ code: code })
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
        btn.disabled = false;
        btn.textContent = 'Check';

        if (data.valid) {
            validatedCode = data.code;
            statusEl.innerHTML = '<span style="color:#059669;">✓ Valid! This gift card is worth ₹' + Number(data.amount).toLocaleString('en-IN') + '.</span>';
            document.getElementById('hiddenCode').value = data.code;
            document.getElementById('redeemFormWrap').style.display = 'block';

            var emailInput = document.getElementById('donorEmail');
            emailInput.value = data.recipient_email;
            emailInput.readOnly = true;
            emailInput.style.background = 'var(--surface2)';
            emailInput.style.cursor = 'not-allowed';
            document.getElementById('emailHint').textContent = 'This gift card can only be redeemed using the email it was sent to.';
        } else {
            validatedCode = null;
            statusEl.innerHTML = '<span style="color:#b91c1c;">' + (data.message || 'Invalid code.') + '</span>';
            document.getElementById('redeemFormWrap').style.display = 'none';

            var emailInput = document.getElementById('donorEmail');
            emailInput.value = '';
            emailInput.readOnly = false;
            emailInput.style.background = '';
            emailInput.style.cursor = '';
            document.getElementById('emailHint').textContent = '';
        }
    })
    .catch(function(){
        btn.disabled = false;
        btn.textContent = 'Check';
        statusEl.innerHTML = '<span style="color:#b91c1c;">Something went wrong. Please try again.</span>';
    });
}

function selectCampaign(id, el) {
    selectedCampaignId = id;
    document.getElementById('hiddenCampaignId').value = id;

    document.querySelectorAll('.gr-camp-card').forEach(function(c){
        c.classList.remove('selected');
    });
    el.classList.add('selected');

    var btn = document.getElementById('redeemBtn');
    btn.disabled = false;
    btn.textContent = 'Redeem Gift Card & Donate';
}

document.getElementById('giftCode').addEventListener('keypress', function(e){
    if (e.key === 'Enter') {
        e.preventDefault();
        checkCode();
    }
});
</script>
@endpush