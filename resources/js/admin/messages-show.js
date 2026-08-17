/* ═══════════════════════════════════════════════════════════════════
   Admin Messages Show page — extracted from inline <script>.
   Reads server data from #messagesShowData (JSON) injected by the blade.
   ═══════════════════════════════════════════════════════════════════ */

(function(){
  'use strict';

  var pageData = JSON.parse(document.getElementById('messagesShowData').textContent);

  var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  function toast(msg, type){
    var icons = {
      success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
      error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
    };
    var el = document.createElement('div');
    el.className = 'toast ' + (type === 'error' ? 'toast-err' : 'toast-ok');
    el.innerHTML = (icons[type]||icons.success) + '<span>' + msg + '</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
    document.getElementById('toastWrap').appendChild(el);
    setTimeout(function(){
      el.style.transition = 'opacity .3s,transform .3s';
      el.style.opacity = '0';
      el.style.transform = 'translateX(20px)';
      setTimeout(function(){ el.remove(); }, 300);
    }, 4200);
  }

  /* mark as read / unread */
  var btn = document.getElementById('toggleReadBtn');
  if(btn){
    var url = pageData.toggleUrl;
    btn.addEventListener('click', function(){
      fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' } })
      .then(function(r){ return r.json(); })
      .then(function(d){
        if(!d.ok) return;
        var badges = document.querySelectorAll('.badge');
        badges.forEach(function(b){
          b.className = 'badge b-' + (d.is_read ? 'read' : 'new');
          b.innerHTML = '<span class="badge-dot"></span>' + (d.is_read ? 'Read' : 'New');
        });
        btn.querySelector('span.qa-icon').innerHTML = d.is_read
          ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9 6 9-6"/></svg>'
          : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        btn.childNodes[btn.childNodes.length-1].textContent = d.is_read ? ' Mark as Unread' : ' Mark as Read';

        var chip = document.getElementById('sidebarUnread');
        if(chip){
          var cur = parseInt(chip.textContent, 10) || 0;
          cur = d.is_read ? Math.max(0, cur - 1) : cur + 1;
          if(cur > 0){ chip.textContent = cur; chip.style.display = ''; } else { chip.style.display = 'none'; }
        }
      });
    });
  }

  /* reply composer */
  var modal = document.getElementById('replyModal');
  var sendBtn = document.getElementById('replySend');
  var sendHtml = sendBtn.innerHTML;

  document.querySelectorAll('.reply-open').forEach(function(b){
    b.addEventListener('click', function(){
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
      setTimeout(function(){ var t = document.getElementById('replyBody'); if(t) t.focus(); }, 60);
    });
  });

  function closeReply(){
    modal.style.display = 'none';
    document.body.style.overflow = '';
    sendBtn.disabled = false;
    sendBtn.innerHTML = sendHtml;
  }
  modal.querySelectorAll('[data-reply-close]').forEach(function(el){
    el.addEventListener('click', closeReply);
  });
  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape' && modal.style.display === 'flex') closeReply();
  });

  sendBtn.addEventListener('click', function(){
    var subject = document.getElementById('replySubject').value.trim();
    var body    = document.getElementById('replyBody').value.trim();
    if(!body){ toast('Please write a reply message.', 'error'); return; }

    sendBtn.disabled = true;
    sendBtn.textContent = 'Sending…';

    fetch(pageData.replyUrl, {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
      body: JSON.stringify({ subject: subject, body: body })
    })
    .then(function(r){ return r.json().then(function(d){ return { ok: r.ok, d: d }; }); })
    .then(function(res){
      if(res.ok && res.d && res.d.ok){
        closeReply();
        toast(res.d.message || 'Reply sent.', 'success');
      } else {
        sendBtn.disabled = false;
        sendBtn.innerHTML = sendHtml;
        toast((res.d && res.d.message) || 'Failed to send reply.', 'error');
      }
    })
    .catch(function(){
      sendBtn.disabled = false;
      sendBtn.innerHTML = sendHtml;
      toast('Network error.', 'error');
    });
  });

  /* ── delegated handlers for data-action attributes ── */

  /* delete confirm buttons (was onclick="return confirm('Delete this message permanently?')") */
  document.addEventListener('click', function(e){
    var el = e.target.closest('[data-action="confirm-delete"]');
    if(!el) return;
    if(!confirm(el.getAttribute('data-confirm'))) e.preventDefault();
  });
})();