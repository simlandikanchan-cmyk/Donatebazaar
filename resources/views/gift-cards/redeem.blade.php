@extends('layouts.user')

@section('page_title', 'Redeem Gift Card')
@section('page_subtitle', 'Turn your code into a donation')

@section('content')
<div style="max-width:560px;">

    <div style="text-align:center;margin-bottom:32px;">
        <p style="font-size:11px;color:var(--text3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px;">DonateBazaar</p>
        <h1 style="font-size:28px;font-weight:700;color:var(--text);margin-bottom:8px;">Redeem Your Gift Card</h1>
        <p style="font-size:14px;color:var(--text2);">Enter your code and turn it into a donation for a cause you love.</p>
    </div>

    @if(session('error'))
    <div style="background:#fee2e2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:16px;">
        {{ session('error') }}
    </div>
    @endif

    @if ($errors->any())
    <div style="background:#fee2e2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:16px;">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- Step 1: Enter code --}}
    <div style="background:var(--surface);border-radius:16px;border:1px solid var(--border);padding:20px;margin-bottom:16px;">
        <p style="font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px;">Step 1 · Gift card code</p>

        <div style="display:flex;gap:10px;">
            <input type="text" id="giftCode" placeholder="DNBZ-XXXX-XXXX" maxlength="20"
                   style="flex:1;height:44px;border-radius:9px;border:1px solid var(--border2);padding:0 14px;font-size:14px;font-family:var(--font-mono);letter-spacing:.05em;text-transform:uppercase;outline:none;background:var(--surface2);color:var(--text);">
            <button onclick="checkCode()" id="checkBtn"
                    style="padding:0 22px;background:var(--accent);color:#fff;border:none;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;">
                Check
            </button>
        </div>

        <div id="codeStatus" style="margin-top:10px;font-size:13px;"></div>
    </div>

    {{-- Step 2: Choose campaign + your details (hidden until code is valid) --}}
    <div id="redeemFormWrap" style="display:none;">

        <div style="background:var(--surface);border-radius:16px;border:1px solid var(--border);padding:20px;margin-bottom:16px;">
            <p style="font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px;">Step 2 · Choose a campaign</p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;max-height:360px;overflow-y:auto;">
                @forelse($campaigns as $c)
                <div onclick="selectCampaign({{ $c->id }}, this)" id="camp-{{ $c->id }}"
                     style="border:2px solid var(--border);border-radius:12px;overflow:hidden;cursor:pointer;transition:border-color .15s;background:var(--surface);">
                    <div style="height:80px;background:var(--surface2);background-image:url('{{ $c->cover_image ? asset('storage/'.$c->cover_image) : '' }}');background-size:cover;background-position:center;"></div>
                    <div style="padding:10px;">
                        <div style="font-size:12px;font-weight:600;color:var(--text);line-height:1.4;margin-bottom:4px;">
                            {{ \Illuminate\Support\Str::limit($c->title, 40) }}
                        </div>
                        <div style="font-size:10px;color:var(--text3);">
                            ₹{{ number_format($c->raised_amount) }} raised of ₹{{ number_format($c->goal_amount) }}
                        </div>
                    </div>
                </div>
                @empty
                <p style="font-size:13px;color:var(--text3);grid-column:1/-1;">No active campaigns available right now.</p>
                @endforelse
            </div>
        </div>

        <div style="background:var(--surface);border-radius:16px;border:1px solid var(--border);padding:20px;margin-bottom:16px;">
            <p style="font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px;">Step 3 · Your details</p>

            <form method="POST" action="{{ route('gift-cards.redeem.submit') }}" id="redeemForm">
                @csrf
                <input type="hidden" name="code" id="hiddenCode">
                <input type="hidden" name="campaign_id" id="hiddenCampaignId">

                <div style="margin-bottom:12px;">
                    <label style="font-size:12px;color:var(--text2);display:block;margin-bottom:5px;">Your name *</label>
                    <input type="text" name="donor_name" required
                           style="width:100%;height:38px;border-radius:9px;border:1px solid var(--border2);padding:0 12px;font-size:13px;outline:none;background:var(--surface2);color:var(--text);">
                </div>
                <div style="margin-bottom:16px;">
                    <label style="font-size:12px;color:var(--text2);display:block;margin-bottom:5px;">Your email *</label>
                    <input type="email" name="donor_email" id="donorEmail" required
                           style="width:100%;height:38px;border-radius:9px;border:1px solid var(--border2);padding:0 12px;font-size:13px;outline:none;background:var(--surface2);color:var(--text);">
                    <p style="font-size:11px;color:var(--text3);margin-top:5px;" id="emailHint"></p>
                </div>

                <button type="submit" id="redeemBtn" disabled
                        style="width:100%;padding:14px;background:var(--accent);color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:600;cursor:pointer;opacity:.5;">
                    Select a campaign to continue
                </button>
            </form>
        </div>

    </div>

    <p style="text-align:center;margin-top:16px;font-size:12px;color:var(--text3);">
        Don't have a gift card yet?
        <a href="{{ route('gift-cards.index') }}" style="color:var(--accent);font-weight:600;text-decoration:none;">Buy one here</a>
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

    document.querySelectorAll('[id^="camp-"]').forEach(function(c){
        c.style.borderColor = 'var(--border)';
    });
    el.style.borderColor = 'var(--accent)';

    var btn = document.getElementById('redeemBtn');
    btn.disabled = false;
    btn.style.opacity = '1';
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