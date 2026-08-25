/* ═══════════════════════════════════════════════════════════════════
   Admin Messages Show page — extracted from inline <script>.
   Reads server data from #messagesShowData (JSON) injected by the blade.
   ═══════════════════════════════════════════════════════════════════ */

import { toast as showToast } from '../../shared/toast.js';
import { csrfFetch } from '../../shared/api.js';

(function(){
  'use strict';

  var pageData = JSON.parse(document.getElementById('messagesShowData').textContent);

  /* mark as read / unread */
  var btn = document.getElementById('toggleReadBtn');
  if(btn){
    var url = pageData.toggleUrl;
    btn.addEventListener('click', function(){
      csrfFetch(url, { method: 'POST', headers: { 'Accept':'application/json' } })
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
    if(!body){ showToast('Please write a reply message.', 'error', { duration: 4200 }); return; }

    sendBtn.disabled = true;
    sendBtn.textContent = 'Sending…';

    csrfFetch(pageData.replyUrl, {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'Accept':'application/json' },
      body: JSON.stringify({ subject: subject, body: body })
    })
    .then(function(r){ return r.json().then(function(d){ return { ok: r.ok, d: d }; }); })
    .then(function(res){
      if(res.ok && res.d && res.d.ok){
        closeReply();
        showToast(res.d.message || 'Reply sent.', 'success', { duration: 4200 });
      } else {
        sendBtn.disabled = false;
        sendBtn.innerHTML = sendHtml;
        showToast((res.d && res.d.message) || 'Failed to send reply.', 'error', { duration: 4200 });
      }
    })
    .catch(function(){
      sendBtn.disabled = false;
      sendBtn.innerHTML = sendHtml;
      showToast('Network error.', 'error', { duration: 4200 });
    });
  });

})();