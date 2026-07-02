@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
:root {
    --bg:#f4f5fb; --surface:#ffffff; --surface2:#f0f2fa;
    --border:rgba(0,0,0,0.07); --border2:rgba(0,0,0,0.11);
    --text:#0f1117; --text2:#4b5563; --text3:#9ca3af;
    --accent:#6366f1; --accent2:#8b5cf6; --accent-glow:rgba(99,102,241,0.16);
    --green:#10b981; --green-glow:rgba(16,185,129,0.14);
    --font:'DM Sans',sans-serif; --font-mono:'DM Mono',monospace;
    --radius:14px; --radius-sm:9px;
    --shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.04);
    --shadow-lg:0 8px 40px rgba(0,0,0,0.12);
    --tr:0.2s ease;
}

.gc-page{font-family:var(--font);background:var(--bg);min-height:100vh;padding:48px 16px 72px;color:var(--text);}
.gc-wrap{max-width:560px;margin:0 auto;}

/* ── Header ── */
.gc-eyebrow{display:inline-flex;align-items:center;gap:6px;font-family:var(--font-mono);font-size:10.5px;font-weight:500;letter-spacing:0.14em;text-transform:uppercase;color:var(--accent);margin-bottom:10px;}
.gc-eyebrow .dot{width:5px;height:5px;border-radius:50%;background:var(--accent);}
.gc-head{text-align:center;margin-bottom:28px;animation:fadeUp .4s both;}
.gc-head h1{font-size:27px;font-weight:800;letter-spacing:-0.02em;color:var(--text);margin-bottom:8px;}
.gc-head p{font-size:13.5px;color:var(--text3);line-height:1.6;}

/* ── Card (matches dashboard .card token) ── */
.gc-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;margin-bottom:16px;animation:fadeUp .4s both;transition:box-shadow var(--tr);}
.gc-card:hover{box-shadow:var(--shadow-lg);}
.gc-card-label{font-family:var(--font-mono);font-size:10.5px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.09em;margin-bottom:14px;}

/* ── Theme picker ── */
.gc-theme-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;}
.gc-theme-swatch{border-radius:var(--radius-sm);padding:14px 12px;cursor:pointer;border:2px solid transparent;transition:border-color var(--tr),transform var(--tr);position:relative;}
.gc-theme-swatch:hover{transform:translateY(-2px);}
.gc-theme-brand{font-family:var(--font-mono);font-size:9px;font-weight:600;letter-spacing:0.06em;margin-bottom:6px;}
.gc-theme-amt{font-family:var(--font-mono);font-size:17px;font-weight:700;}
.gc-theme-tag{font-size:9px;opacity:0.6;margin-top:4px;}
.gc-theme-check{display:none;position:absolute;top:7px;right:7px;width:16px;height:16px;border-radius:50%;background:var(--accent);align-items:center;justify-content:center;box-shadow:0 2px 8px var(--accent-glow);}

/* ── Amount pills ── */
.gc-amt-pills{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px;}
.gc-amt-pill{padding:8px 17px;border-radius:100px;border:1.5px solid var(--border2);background:var(--surface2);font-family:var(--font);font-size:12.5px;font-weight:600;color:var(--text2);cursor:pointer;transition:all var(--tr);}
.gc-amt-pill:hover{border-color:var(--accent);color:var(--accent);}
.gc-amt-pill.active{background:var(--accent);border-color:var(--accent);color:#fff;box-shadow:0 4px 14px var(--accent-glow);}
.gc-custom-row{display:flex;align-items:center;gap:10px;}
.gc-custom-row span{font-size:12.5px;color:var(--text3);font-weight:500;}

/* ── Fields ── */
.gc-field-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;}
.gc-field{margin-bottom:0;}
.gc-field label{display:block;font-size:12px;font-weight:600;color:var(--text2);margin-bottom:6px;}
.gc-field input, .gc-field textarea{
    width:100%;border-radius:var(--radius-sm);border:1.5px solid var(--border2);
    background:var(--surface2);padding:0 13px;height:40px;font-family:var(--font);
    font-size:13px;color:var(--text);outline:none;transition:border-color var(--tr),box-shadow var(--tr),background var(--tr);
}
.gc-field textarea{height:auto;padding:10px 13px;line-height:1.6;resize:vertical;font-family:var(--font);}
.gc-field input:focus, .gc-field textarea:focus{border-color:var(--accent);background:var(--surface);box-shadow:0 0 0 3px var(--accent-glow);}
.gc-field input::placeholder, .gc-field textarea::placeholder{color:var(--text3);}

/* ── Live preview ── */
.gc-preview-card{border-radius:var(--radius);padding:22px;margin-bottom:12px;position:relative;overflow:hidden;box-shadow:var(--shadow);}
.gc-preview-brand{font-family:var(--font-mono);font-size:10px;font-weight:600;letter-spacing:0.09em;margin-bottom:6px;}
.gc-preview-amt{font-family:var(--font-mono);font-size:29px;font-weight:800;letter-spacing:-0.02em;}
.gc-preview-to{font-size:12px;opacity:0.72;margin-top:4px;font-weight:500;}
.gc-preview-code{font-family:var(--font-mono);font-size:10px;letter-spacing:0.13em;opacity:0.42;margin-top:9px;}
.gc-preview-msg{font-size:12.5px;color:var(--text3);font-style:italic;line-height:1.65;}

/* ── Buy button (matches rd-btn token) ── */
.gc-buy-btn{
    width:100%;padding:14px;border:none;border-radius:12px;
    font-family:var(--font-mono);font-size:14px;font-weight:600;color:#fff;cursor:pointer;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    box-shadow:0 4px 18px var(--accent-glow);
    transition:opacity var(--tr),transform var(--tr),box-shadow var(--tr);
}
.gc-buy-btn:hover:not(:disabled){opacity:0.92;transform:translateY(-1px);box-shadow:0 8px 26px var(--accent-glow);}
.gc-buy-btn:disabled{cursor:not-allowed;opacity:0.75;}

/* ── Trust badges (matches status-chip token) ── */
.gc-trust-row{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-top:18px;}
.gc-trust-chip{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--font-mono);letter-spacing:0.02em;background:var(--surface2);border:1px solid var(--border2);color:var(--text3);}
.gc-trust-chip svg{width:12px;height:12px;flex-shrink:0;color:var(--accent);}

/* ── Footer link ── */
.gc-foot{text-align:center;margin-top:18px;font-size:12px;color:var(--text3);}
.gc-foot a{color:var(--accent);font-weight:600;text-decoration:none;}
.gc-foot a:hover{text-decoration:underline;}

/* ── Animations ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:none;}}
.gc-card:nth-of-type(1){animation-delay:.05s;}
.gc-card:nth-of-type(2){animation-delay:.10s;}
.gc-card:nth-of-type(3){animation-delay:.15s;}
.gc-card:nth-of-type(4){animation-delay:.20s;}

@media (max-width:520px){
    .gc-field-row{grid-template-columns:1fr;}
    .gc-theme-grid{grid-template-columns:repeat(2,1fr);}
}
</style>

<div class="gc-page">
<div class="gc-wrap">

    <div class="gc-head">
        <div class="gc-eyebrow"><span class="dot"></span>DonateBazaar Gift Cards</div>
        <h1>Gift the power of giving</h1>
        <p>Send a digital gift card — the recipient donates to any campaign they love.</p>
    </div>

    {{-- Card theme picker --}}
    <div class="gc-card">
        <p class="gc-card-label">Choose a card design</p>
        <div class="gc-theme-grid" id="themeGrid">
            @foreach(['purple'=>['bg'=>'#EEEDFE','text'=>'#26215C','brand'=>'#3C3489'],'teal'=>['bg'=>'#E1F5EE','text'=>'#04342C','brand'=>'#085041'],'coral'=>['bg'=>'#FAECE7','text'=>'#4A1B0C','brand'=>'#712B13'],'blue'=>['bg'=>'#E6F1FB','text'=>'#042C53','brand'=>'#0C447C']] as $theme => $t)
            <div onclick="selectTheme('{{ $theme }}')" id="card-{{ $theme }}" class="gc-theme-swatch"
                 style="background:{{ $t['bg'] }};">
                <div class="gc-theme-brand" style="color:{{ $t['brand'] }};">DONATEBAZAAR</div>
                <div class="gc-theme-amt" style="color:{{ $t['text'] }};" id="preview-amt-{{ $theme }}">₹500</div>
                <div class="gc-theme-tag" style="color:{{ $t['text'] }};">Gift Card</div>
                <div id="check-{{ $theme }}" class="gc-theme-check">
                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Amount --}}
    <div class="gc-card">
        <p class="gc-card-label">Select amount</p>
        <div class="gc-amt-pills" id="amtPills">
            @foreach([100,250,500,1000,2000,5000,10000,20000,30000,40000,50000] as $a)
            <button onclick="setAmt({{ $a }}, this)" class="gc-amt-pill {{ $a===500 ? 'active' : '' }}">
                ₹{{ number_format($a) }}
            </button>
            @endforeach
        </div>
        <div class="gc-custom-row">
            <span>Custom:</span>
            <div class="gc-field" style="flex:1;">
                <input type="number" id="customAmt" placeholder="Enter ₹ amount" min="100" oninput="setCustomAmt(this.value)">
            </div>
        </div>
    </div>

    {{-- Details --}}
    <div class="gc-card">
        <p class="gc-card-label">Card details</p>
        <div class="gc-field-row">
            <div class="gc-field">
                <label>Your name *</label>
                <input type="text" id="senderName" placeholder="Your name">
            </div>
            <div class="gc-field">
                <label>Your email *</label>
                <input type="email" id="senderEmail" placeholder="your@email.com">
            </div>
            <div class="gc-field">
                <label>Recipient name *</label>
                <input type="text" id="recipientName" placeholder="Their name" oninput="updateLivePreview()">
            </div>
            <div class="gc-field">
                <label>Recipient email *</label>
                <input type="email" id="recipientEmail" placeholder="their@email.com">
            </div>
        </div>
        <div class="gc-field" style="margin-bottom:12px;">
            <label>Personal message</label>
            <textarea id="gcMessage" placeholder="Write a heartfelt message…" rows="3" oninput="updateLivePreview()"></textarea>
        </div>
        <div class="gc-field">
            <label>Send on date</label>
            <input type="date" id="sendAt">
        </div>
    </div>

    {{-- Live Preview --}}
    <div class="gc-card">
        <p class="gc-card-label">Preview</p>
        <div id="liveCard" class="gc-preview-card" style="background:#EEEDFE;">
            <div class="gc-preview-brand" style="color:#3C3489;">DONATEBAZAAR</div>
            <div id="liveAmt" class="gc-preview-amt" style="color:#26215C;">₹500</div>
            <div id="liveTo" class="gc-preview-to" style="color:#26215C;">For: —</div>
            <div class="gc-preview-code" style="color:#26215C;">DNBZ-XXXX-XXXX</div>
        </div>
        <div id="liveMsg" class="gc-preview-msg">Your message will appear here.</div>
    </div>

    {{-- Buy button --}}
    <button id="buyBtn" onclick="initiatePurchase()" class="gc-buy-btn">
        Purchase &amp; Send Gift Card — ₹<span id="btnAmt">500</span>
    </button>

    <div class="gc-trust-row">
        <span class="gc-trust-chip">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            Secure payment
        </span>
        <span class="gc-trust-chip">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Instant delivery
        </span>
        <span class="gc-trust-chip">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-.71-8"/></svg>
            Never expires
        </span>
    </div>

    <p class="gc-foot">
        Already have a gift card?
        <a href="{{ route('gift-cards.redeem') }}">Redeem it here</a>
    </p>

</div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var currentAmt = 500;
var currentTheme = 'purple';
var themeStyles = {
    purple: {bg:'#EEEDFE',text:'#26215C',brand:'#3C3489'},
    teal:   {bg:'#E1F5EE',text:'#04342C',brand:'#085041'},
    coral:  {bg:'#FAECE7',text:'#4A1B0C',brand:'#712B13'},
    blue:   {bg:'#E6F1FB',text:'#042C53',brand:'#0C447C'}
};

function selectTheme(t) {
    currentTheme = t;
    document.querySelectorAll('.gc-theme-swatch').forEach(function(c){ c.style.borderColor='transparent'; c.style.transform='none'; });
    document.querySelectorAll('[id^="check-"]').forEach(function(c){ c.style.display='none'; });
    document.getElementById('card-'+t).style.borderColor='#6366f1';
    document.getElementById('check-'+t).style.display='flex';
    updateLivePreview();
}

function setAmt(amt, btn) {
    currentAmt = amt;
    document.querySelectorAll('.gc-amt-pill').forEach(function(b){ b.classList.remove('active'); });
    btn.classList.add('active');
    document.getElementById('customAmt').value='';
    ['purple','teal','coral','blue'].forEach(function(t){
        document.getElementById('preview-amt-'+t).textContent='₹'+amt.toLocaleString('en-IN');
    });
    document.getElementById('btnAmt').textContent=amt.toLocaleString('en-IN');
    updateLivePreview();
}

function setCustomAmt(val){
    var n=parseInt(val);
    if(!n||n<100) return;
    currentAmt=n;
    document.querySelectorAll('.gc-amt-pill').forEach(function(b){ b.classList.remove('active'); });
    ['purple','teal','coral','blue'].forEach(function(t){
        document.getElementById('preview-amt-'+t).textContent='₹'+n.toLocaleString('en-IN');
    });
    document.getElementById('btnAmt').textContent=n.toLocaleString('en-IN');
    updateLivePreview();
}

function updateLivePreview(){
    var s=themeStyles[currentTheme];
    var card=document.getElementById('liveCard');
    card.style.background=s.bg;
    card.querySelectorAll('div').forEach(function(d){ d.style.color=s.text; });
    document.getElementById('liveAmt').textContent='₹'+currentAmt.toLocaleString('en-IN');
    var to=(document.getElementById('recipientName').value||'').trim();
    document.getElementById('liveTo').textContent='For: '+(to||'—');
    var msg=(document.getElementById('gcMessage').value||'').trim();
    document.getElementById('liveMsg').textContent=msg||'Your message will appear here.';
}

function initiatePurchase(){
    var sName  = document.getElementById('senderName').value.trim();
    var sEmail = document.getElementById('senderEmail').value.trim();
    var rName  = document.getElementById('recipientName').value.trim();
    var rEmail = document.getElementById('recipientEmail').value.trim();
    var sendAt = document.getElementById('sendAt').value;

    if(!sName||!sEmail||!rName||!rEmail||!sendAt){
        alert('Please fill in all required fields.'); return;
    }

    var btn=document.getElementById('buyBtn');
    btn.disabled=true; btn.textContent='Processing…';

    fetch('{{ route("gift-cards.order") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:JSON.stringify({
            amount:currentAmt, theme:currentTheme,
            sender_name:sName, sender_email:sEmail,
            recipient_name:rName, recipient_email:rEmail,
            message:document.getElementById('gcMessage').value,
            send_at:sendAt
        })
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
        var rzp = new Razorpay({
            key:           data.razorpay_key,
            amount:        data.amount,
            currency:      'INR',
            name:          'DonateBazaar',
            description:   'Gift Card',
            order_id:      data.order_id,
            prefill:       {name:sName, email:sEmail},
            theme:         {color:'#6366f1'},
            modal:         {ondismiss:function(){ btn.disabled=false; btn.innerHTML='Purchase &amp; Send Gift Card — ₹'+currentAmt.toLocaleString('en-IN'); }},
            handler:function(response){
                btn.textContent='Verifying…';
                fetch('{{ route("gift-cards.verify") }}', {
                    method:'POST',
                    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                    body:JSON.stringify({
                        razorpay_order_id:  response.razorpay_order_id,
                        razorpay_payment_id:response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature,
                        gift_card_id:       data.gift_card_id
                    })
                })
                .then(function(r){ return r.json(); })
                .then(function(d){
                    if(d.success){
                        btn.style.background='linear-gradient(135deg,#10b981,#059669)';
                        btn.textContent='Gift card sent! Code: '+d.code;
                    } else {
                        btn.disabled=false;
                        btn.textContent='Purchase & Send Gift Card';
                        alert(d.message||'Verification failed.');
                    }
                });
            }
        });
        rzp.open();
    })
    .catch(function(){
        btn.disabled=false;
        btn.textContent='Purchase & Send Gift Card';
        alert('Something went wrong. Please try again.');
    });
}

selectTheme('purple');
var d=new Date(); d.setDate(d.getDate()+1);
document.getElementById('sendAt').value=d.toISOString().split('T')[0];
</script>
@endsection