@extends('layouts.app')

@section('content')
<div style="min-height:100vh;background:#f4f5fb;padding:40px 16px;">
<div style="max-width:560px;margin:0 auto;">

    <div style="text-align:center;margin-bottom:32px;">
        <p style="font-size:11px;color:#9ca3af;letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px;">DonateBazaar</p>
        <h1 style="font-size:28px;font-weight:700;color:#0f1117;margin-bottom:8px;">Gift the power of giving</h1>
        <p style="font-size:14px;color:#6b7280;">Send a digital gift card — the recipient donates to any campaign they love.</p>
    </div>

    {{-- Card theme picker --}}
    <div style="background:#fff;border-radius:16px;border:1px solid rgba(0,0,0,0.07);padding:20px;margin-bottom:16px;">
        <p style="font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px;">Choose a card design</p>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;" id="themeGrid">
            @foreach(['purple'=>['bg'=>'#EEEDFE','text'=>'#26215C','brand'=>'#3C3489'],'teal'=>['bg'=>'#E1F5EE','text'=>'#04342C','brand'=>'#085041'],'coral'=>['bg'=>'#FAECE7','text'=>'#4A1B0C','brand'=>'#712B13'],'blue'=>['bg'=>'#E6F1FB','text'=>'#042C53','brand'=>'#0C447C']] as $theme => $t)
            <div onclick="selectTheme('{{ $theme }}')" id="card-{{ $theme }}"
                 style="border-radius:12px;padding:14px 12px;cursor:pointer;border:2px solid transparent;background:{{ $t['bg'] }};transition:border-color .15s,transform .15s;position:relative;">
                <div style="font-size:9px;font-weight:600;letter-spacing:.06em;color:{{ $t['brand'] }};margin-bottom:6px;">DONATEBAZAAR</div>
                <div style="font-size:18px;font-weight:700;color:{{ $t['text'] }};" id="preview-amt-{{ $theme }}">₹500</div>
                <div style="font-size:9px;color:{{ $t['text'] }};opacity:.6;margin-top:4px;">Gift Card</div>
                <div id="check-{{ $theme }}" style="display:none;position:absolute;top:7px;right:7px;width:16px;height:16px;border-radius:50%;background:#6366f1;align-items:center;justify-content:center;">
                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Amount --}}
    <div style="background:#fff;border-radius:16px;border:1px solid rgba(0,0,0,0.07);padding:20px;margin-bottom:16px;">
        <p style="font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px;">Select amount</p>
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;" id="amtPills">
            @foreach([100,250,500,1000,2000,5000,10000,20000,30000,40000,50000] as $a)
            <button onclick="setAmt({{ $a }}, this)"
                    style="padding:8px 18px;border-radius:9px;border:1px solid rgba(0,0,0,0.10);background:#f4f5fb;font-size:13px;font-weight:500;cursor:pointer;transition:all .15s;{{ $a===500 ? 'background:#6366f1;color:#fff;border-color:#6366f1;' : 'color:#0f1117;' }}">
                ₹{{ number_format($a) }}
            </button>
            @endforeach
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:13px;color:#6b7280;">Custom:</span>
            <input type="number" id="customAmt" placeholder="Enter ₹ amount" min="100"
                   style="flex:1;height:36px;border-radius:9px;border:1px solid rgba(0,0,0,0.10);padding:0 12px;font-size:13px;outline:none;"
                   oninput="setCustomAmt(this.value)">
        </div>
    </div>

    {{-- Details --}}
    <div style="background:#fff;border-radius:16px;border:1px solid rgba(0,0,0,0.07);padding:20px;margin-bottom:16px;">
        <p style="font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px;">Card details</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:5px;">Your name *</label>
                <input type="text" id="senderName" placeholder="Your name"
                       style="width:100%;height:38px;border-radius:9px;border:1px solid rgba(0,0,0,0.10);padding:0 12px;font-size:13px;outline:none;">
            </div>
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:5px;">Your email *</label>
                <input type="email" id="senderEmail" placeholder="your@email.com"
                       style="width:100%;height:38px;border-radius:9px;border:1px solid rgba(0,0,0,0.10);padding:0 12px;font-size:13px;outline:none;">
            </div>
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:5px;">Recipient name *</label>
                <input type="text" id="recipientName" placeholder="Their name" oninput="updateLivePreview()"
                       style="width:100%;height:38px;border-radius:9px;border:1px solid rgba(0,0,0,0.10);padding:0 12px;font-size:13px;outline:none;">
            </div>
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:5px;">Recipient email *</label>
                <input type="email" id="recipientEmail" placeholder="their@email.com"
                       style="width:100%;height:38px;border-radius:9px;border:1px solid rgba(0,0,0,0.10);padding:0 12px;font-size:13px;outline:none;">
            </div>
        </div>
        <div style="margin-bottom:12px;">
            <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:5px;">Personal message</label>
            <textarea id="gcMessage" placeholder="Write a heartfelt message…" rows="3" oninput="updateLivePreview()"
                      style="width:100%;border-radius:9px;border:1px solid rgba(0,0,0,0.10);padding:10px 12px;font-size:13px;outline:none;resize:vertical;font-family:inherit;"></textarea>
        </div>
        <div>
            <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:5px;">Send on date</label>
            <input type="date" id="sendAt"
                   style="width:100%;height:38px;border-radius:9px;border:1px solid rgba(0,0,0,0.10);padding:0 12px;font-size:13px;outline:none;">
        </div>
    </div>

    {{-- Live Preview --}}
    <div style="background:#fff;border-radius:16px;border:1px solid rgba(0,0,0,0.07);padding:20px;margin-bottom:16px;">
        <p style="font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px;">Preview</p>
        <div id="liveCard" style="border-radius:14px;padding:20px;background:#EEEDFE;margin-bottom:12px;">
            <div style="font-size:10px;font-weight:600;letter-spacing:.08em;color:#3C3489;margin-bottom:6px;">DONATEBAZAAR</div>
            <div id="liveAmt" style="font-size:28px;font-weight:700;color:#26215C;">₹500</div>
            <div id="liveTo" style="font-size:12px;color:#26215C;opacity:.7;margin-top:4px;">For: —</div>
            <div style="font-size:10px;font-family:monospace;letter-spacing:.12em;color:#26215C;opacity:.45;margin-top:8px;">DNBZ-XXXX-XXXX</div>
        </div>
        <div id="liveMsg" style="font-size:13px;color:#6b7280;font-style:italic;line-height:1.6;">Your message will appear here.</div>
    </div>

    {{-- Buy button --}}
    <button id="buyBtn" onclick="initiatePurchase()"
            style="width:100%;padding:14px;background:#6366f1;color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:600;cursor:pointer;transition:opacity .15s;">
        Purchase & Send Gift Card — ₹<span id="btnAmt">500</span>
    </button>

    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;margin-top:16px;">
        @foreach(['Lock Secure payment','Mail Instant delivery','Refresh Never expires'] as $t)
        @php [$icon,$label] = explode(' ',$t,2); @endphp
        <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:#9ca3af;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                @if($icon==='Lock')<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                @elseif($icon==='Mail')<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                @else<polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-.71-8"/>
                @endif
            </svg>
            {{ $label }}
        </div>
        @endforeach
    </div>

    <p style="text-align:center;margin-top:16px;font-size:12px;color:#9ca3af;">
        Already have a gift card?
        <a href="{{ route('gift-cards.redeem') }}" style="color:#6366f1;font-weight:600;text-decoration:none;">Redeem it here</a>
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
    document.querySelectorAll('#themeGrid > div').forEach(function(c){ c.style.borderColor='transparent'; c.style.transform='none'; });
    document.querySelectorAll('[id^="check-"]').forEach(function(c){ c.style.display='none'; });
    document.getElementById('card-'+t).style.borderColor='#6366f1';
    document.getElementById('card-'+t).style.transform='translateY(-2px)';
    document.getElementById('check-'+t).style.display='flex';
    updateLivePreview();
}

function setAmt(amt, btn) {
    currentAmt = amt;
    document.querySelectorAll('#amtPills button').forEach(function(b){
        b.style.background='#f4f5fb'; b.style.color='#0f1117'; b.style.borderColor='rgba(0,0,0,0.10)';
    });
    btn.style.background='#6366f1'; btn.style.color='#fff'; btn.style.borderColor='#6366f1';
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
    document.querySelectorAll('#amtPills button').forEach(function(b){
        b.style.background='#f4f5fb';b.style.color='#0f1117';b.style.borderColor='rgba(0,0,0,0.10)';
    });
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
            modal:         {ondismiss:function(){ btn.disabled=false; btn.innerHTML='Purchase & Send Gift Card — ₹'+currentAmt.toLocaleString('en-IN'); }},
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
                        btn.style.background='#10b981';
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