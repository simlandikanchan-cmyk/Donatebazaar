import { csrfFetch } from '../shared/api.js';

(function () {
  'use strict';

  /* ── SERVER DATA (from #giftCardsData JSON block) ── */
  var data = {};
  (function () {
    var dataEl = document.getElementById('giftCardsData');
    if (!dataEl) return;
    try { data = JSON.parse(dataEl.textContent); } catch (e) { /* keep defaults */ }
  })();

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

    csrfFetch(data.orderUrl, {
      method:'POST',
      headers:{'Content-Type':'application/json','Accept':'application/json'},
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
          csrfFetch(data.verifyUrl, {
            method:'POST',
            headers:{'Content-Type':'application/json','Accept':'application/json'},
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

  /* ── data-action delegation (replaces inline onclick/oninput) ── */
  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    var action = el.getAttribute('data-action');
    if (action === 'select-theme') {
      selectTheme(el.getAttribute('data-theme'));
    } else if (action === 'set-amt') {
      setAmt(parseInt(el.getAttribute('data-amt'), 10), el);
    } else if (action === 'initiate-purchase') {
      initiatePurchase();
    }
  });

  document.addEventListener('input', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    var action = el.getAttribute('data-action');
    if (action === 'custom-amt') {
      setCustomAmt(el.value);
    } else if (action === 'live-preview') {
      updateLivePreview();
    }
  });

})();