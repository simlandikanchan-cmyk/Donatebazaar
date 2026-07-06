@extends('layouts.user')

@section('page_title', 'Gift Cards')
@section('page_subtitle', 'Buy & send gift cards')

@push('page_styles')
<style>
.gc-wrap{max-width:840px;width:100%;margin:0 auto;}

/* ── Hero ── */
.gc-hero{background:linear-gradient(135deg,var(--accent-glow),rgba(139,92,246,0.08));border:1px solid var(--border);border-radius:var(--radius);padding:28px 32px;margin-bottom:24px;display:flex;align-items:center;gap:20px;animation:fadeUp .4s both;}
.gc-hero-icon{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.gc-hero-icon svg{width:24px;height:24px;}
.gc-hero h1{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.02em;margin-bottom:4px;}
.gc-hero p{font-size:13px;color:var(--text3);line-height:1.6;margin:0;}
.gc-hero-body{flex:1;min-width:0;}
.gc-hero-extra{margin-left:auto;font-size:12px;color:var(--text3);}
.gc-hero-extra a{color:var(--accent);font-weight:600;text-decoration:none;}
.gc-hero-extra a:hover{text-decoration:underline;}

/* ── Two-column layout ── */
.gc-cols{display:flex;gap:20px;align-items:flex-start;}
.gc-left{flex:1;min-width:0;}
.gc-right{width:320px;flex-shrink:0;position:sticky;top:90px;}

/* ── Section card ── */
.gc-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;margin-bottom:16px;animation:fadeUp .4s both;transition:box-shadow var(--tr);}
.gc-card:hover{box-shadow:var(--shadow-lg);}
.gc-card-hdr{display:flex;align-items:center;gap:8px;margin-bottom:14px;}
.gc-card-hdr svg{width:14px;height:14px;color:var(--accent);flex-shrink:0;}
.gc-card-label{font-family:var(--font-mono);font-size:10.5px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.09em;}

/* ── Theme picker (2×2) ── */
.gc-theme-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.gc-theme-swatch{border-radius:var(--radius-sm);padding:16px 14px;cursor:pointer;border:2px solid transparent;transition:border-color var(--tr),transform var(--tr),box-shadow var(--tr);position:relative;}
.gc-theme-swatch:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,0.08);}
.gc-theme-swatch.selected{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.gc-theme-brand{font-family:var(--font-mono);font-size:9px;font-weight:600;letter-spacing:0.09em;margin-bottom:5px;text-transform:uppercase;}
.gc-theme-amt{font-family:var(--font-mono);font-size:19px;font-weight:800;}
.gc-theme-tag{font-size:10px;opacity:0.55;margin-top:3px;font-weight:500;}
.gc-theme-check{display:none;position:absolute;top:8px;right:8px;width:18px;height:18px;border-radius:50%;background:var(--accent);align-items:center;justify-content:center;box-shadow:0 2px 8px var(--accent-glow);}
.gc-theme-swatch.selected .gc-theme-check{display:flex;}

/* ── Amount pills ── */
.gc-amt-pills{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px;}
.gc-amt-pill{padding:7px 15px;border-radius:100px;border:1.5px solid var(--border2);background:var(--surface2);font-family:var(--font);font-size:12px;font-weight:600;color:var(--text2);cursor:pointer;transition:all var(--tr);}
.gc-amt-pill:hover{border-color:var(--accent);color:var(--accent);}
.gc-amt-pill.active{background:var(--accent);border-color:var(--accent);color:#fff;box-shadow:0 3px 12px var(--accent-glow);}
.gc-custom-row{display:flex;align-items:center;gap:10px;}
.gc-custom-row span{font-size:12px;color:var(--text3);font-weight:500;white-space:nowrap;}

/* ── Fields ── */
.gc-field-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;}
.gc-field label{display:block;font-size:11.5px;font-weight:600;color:var(--text2);margin-bottom:5px;}
.gc-field input,.gc-field textarea{width:100%;border-radius:var(--radius-sm);border:1.5px solid var(--border2);background:var(--surface2);padding:0 12px;height:38px;font-family:var(--font);font-size:13px;color:var(--text);outline:none;transition:border-color var(--tr),box-shadow var(--tr),background var(--tr);}
.gc-field textarea{height:auto;padding:9px 12px;line-height:1.6;resize:vertical;}
.gc-field input:focus,.gc-field textarea:focus{border-color:var(--accent);background:var(--surface);box-shadow:0 0 0 3px var(--accent-glow);}
.gc-field input::placeholder,.gc-field textarea::placeholder{color:var(--text3);}

/* ── Preview (right column) ── */
.gc-preview-card{border-radius:var(--radius);padding:24px;margin-bottom:14px;position:relative;overflow:hidden;box-shadow:var(--shadow);transition:background var(--tr);}
.gc-preview-brand{font-family:var(--font-mono);font-size:10px;font-weight:600;letter-spacing:0.09em;margin-bottom:6px;text-transform:uppercase;}
.gc-preview-amt{font-family:var(--font-mono);font-size:36px;font-weight:800;letter-spacing:-0.03em;}
.gc-preview-to{font-size:13px;opacity:0.72;margin-top:5px;font-weight:500;}
.gc-preview-code{font-family:var(--font-mono);font-size:10px;letter-spacing:0.13em;opacity:0.38;margin-top:10px;}
.gc-preview-msg{font-size:12.5px;color:var(--text3);font-style:italic;line-height:1.6;margin-top:2px;}
.gc-preview-sticker{position:absolute;bottom:-16px;right:-16px;width:80px;height:80px;border-radius:50%;opacity:0.06;background:currentColor;}
.gc-preview-sticker2{position:absolute;top:-12px;right:30px;width:40px;height:40px;border-radius:50%;opacity:0.05;background:currentColor;}

/* ── Trust chips ── */
.gc-trust-row{display:flex;gap:8px;flex-wrap:wrap;}
.gc-trust-chip{display:inline-flex;align-items:center;gap:5px;padding:5px 10px;border-radius:100px;font-size:10px;font-weight:600;font-family:var(--font-mono);letter-spacing:0.02em;background:var(--surface2);border:1px solid var(--border2);color:var(--text3);}
.gc-trust-chip svg{width:11px;height:11px;flex-shrink:0;color:var(--accent);}

/* ── Buy button ── */
.gc-buy-wrap{padding-top:12px;}
.gc-buy-btn{width:100%;padding:14px;border:none;border-radius:12px;font-family:var(--font-mono);font-size:14px;font-weight:600;color:#fff;cursor:pointer;background:linear-gradient(135deg,var(--accent),var(--accent2));box-shadow:0 4px 18px var(--accent-glow);transition:opacity var(--tr),transform var(--tr),box-shadow var(--tr);}
.gc-buy-btn:hover:not(:disabled){opacity:0.92;transform:translateY(-1px);box-shadow:0 8px 26px var(--accent-glow);}
.gc-buy-btn:disabled{cursor:not-allowed;opacity:0.75;}

/* ── Redeem footer (shown on mobile, hidden on desktop) ── */
.gc-foot-mobile{text-align:center;margin-top:12px;font-size:12px;color:var(--text3);display:none;}
.gc-foot-mobile a{color:var(--accent);font-weight:600;text-decoration:none;}

/* ── Animation ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:none;}}
.gc-card:nth-of-type(1){animation-delay:.05s;}
.gc-card:nth-of-type(2){animation-delay:.10s;}
.gc-card:nth-of-type(3){animation-delay:.15s;}

@media (max-width:900px){
    .gc-cols{flex-direction:column;}
    .gc-right{width:100%;position:static;}
}
@media (max-width:768px){
    .gc-hero{flex-wrap:wrap;gap:14px;}
    .gc-hero-body{flex:1 1 100%;}
    .gc-hero-extra{margin-left:0;}
}
@media (max-width:600px){
    .gc-hero{padding:18px 16px;}
    .gc-hero h1{font-size:18px;line-height:1.3;}
    .gc-hero-extra{width:100%;margin-left:0;margin-top:6px;text-align:left;display:none;}
    .gc-field-row{grid-template-columns:1fr;}
    .gc-theme-grid{grid-template-columns:1fr 1fr;gap:8px;}
    .gc-theme-swatch{padding:12px 10px;}
    .gc-card{padding:16px;}
    .gc-amt-pills{gap:5px;}
    .gc-amt-pill{padding:6px 12px;font-size:11px;}
    .gc-right .gc-card{margin-bottom:12px;}
    .gc-preview-amt{font-size:28px;}
    .gc-foot-mobile{display:block;}
}
</style>
@endpush

@section('content')
<div class="gc-wrap">

    {{-- Hero --}}
    <div class="gc-hero">
        <div class="gc-hero-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="12" y1="12" x2="12" y2="17"/><line x1="9.5" y1="14.5" x2="14.5" y2="14.5"/></svg>
        </div>
        <div class="gc-hero-body">
            <h1>Gift the power of giving</h1>
            <p>Send a digital gift card — the recipient donates to any campaign they love.</p>
        </div>
        <div class="gc-hero-extra">
            <a href="{{ route('gift-cards.redeem') }}">Redeem a card →</a>
        </div>
    </div>

    <div class="gc-cols">

        {{-- Left column — Form --}}
        <div class="gc-left">

            {{-- Theme picker --}}
            <div class="gc-card">
                <div class="gc-card-hdr">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="21.17" y1="8" x2="12" y2="8"/><line x1="3.95" y1="6.06" x2="8.54" y2="14"/><line x1="10.88" y1="21.94" x2="15.46" y2="14"/></svg>
                    <span class="gc-card-label">Card design</span>
                </div>
                <div class="gc-theme-grid" id="themeGrid">
                    @foreach(['purple'=>['bg'=>'#EEEDFE','text'=>'#26215C','brand'=>'#3C3489'],'teal'=>['bg'=>'#E1F5EE','text'=>'#04342C','brand'=>'#085041'],'coral'=>['bg'=>'#FAECE7','text'=>'#4A1B0C','brand'=>'#712B13'],'blue'=>['bg'=>'#E6F1FB','text'=>'#042C53','brand'=>'#0C447C']] as $theme => $t)
                    <div onclick="selectTheme('{{ $theme }}')" id="card-{{ $theme }}" class="gc-theme-swatch {{ $theme === 'purple' ? 'selected' : '' }}"
                         style="background:{{ $t['bg'] }};">
                        <div class="gc-theme-brand" style="color:{{ $t['brand'] }};">DONATEBAZAAR</div>
                        <div class="gc-theme-amt" style="color:{{ $t['text'] }};" id="preview-amt-{{ $theme }}">₹500</div>
                        <div class="gc-theme-tag" style="color:{{ $t['text'] }};">Gift Card</div>
                        <div id="check-{{ $theme }}" class="gc-theme-check">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Amount --}}
            <div class="gc-card">
                <div class="gc-card-hdr">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="gc-card-label">Amount</span>
                </div>
                <div class="gc-amt-pills" id="amtPills">
                    @foreach([100,250,500,1000,2000,5000,10000,20000] as $a)
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
                <div class="gc-card-hdr">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="gc-card-label">Details</span>
                </div>
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
                <div class="gc-field" style="margin-bottom:10px;">
                    <label>Personal message</label>
                    <textarea id="gcMessage" placeholder="Write a heartfelt message…" rows="2" oninput="updateLivePreview()"></textarea>
                </div>
                <div class="gc-field">
                    <label>Send on date</label>
                    <input type="date" id="sendAt">
                </div>
            </div>

        </div>

        {{-- Right column — Preview + Trust --}}
        <div class="gc-right">
            <div class="gc-card">
                <div class="gc-card-hdr">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span class="gc-card-label">Preview</span>
                </div>
                <div id="liveCard" class="gc-preview-card" style="background:#EEEDFE;">
                    <div class="gc-preview-brand" style="color:#3C3489;">DONATEBAZAAR</div>
                    <div id="liveAmt" class="gc-preview-amt" style="color:#26215C;">₹500</div>
                    <div id="liveTo" class="gc-preview-to" style="color:#26215C;">For: —</div>
                    <div class="gc-preview-code" style="color:#26215C;">DNBZ-XXXX-XXXX</div>
                    <div class="gc-preview-sticker" style="color:#26215C;"></div>
                    <div class="gc-preview-sticker2" style="color:#26215C;"></div>
                </div>
                <div id="liveMsg" class="gc-preview-msg">Your message will appear here.</div>
            </div>

            <div class="gc-trust-row">
                <span class="gc-trust-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    Secure payment
                </span>
                <span class="gc-trust-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Instant delivery
                </span>
                <span class="gc-trust-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-.71-8"/></svg>
                    Never expires
                </span>
            </div>
        </div>

    </div>

    {{-- Buy button --}}
    <div class="gc-buy-wrap">
        <button id="buyBtn" onclick="initiatePurchase()" class="gc-buy-btn">
            Purchase &amp; Send Gift Card — ₹<span id="btnAmt">500</span>
        </button>
    </div>

    {{-- Mobile redeem link --}}
    <p class="gc-foot-mobile">
        Already have a gift card?
        <a href="{{ route('gift-cards.redeem') }}">Redeem it here</a>
    </p>

</div>
@endsection

@push('page_scripts')
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
    document.querySelectorAll('.gc-theme-swatch').forEach(function(c){ c.classList.remove('selected'); });
    document.getElementById('card-'+t).classList.add('selected');
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
@endpush