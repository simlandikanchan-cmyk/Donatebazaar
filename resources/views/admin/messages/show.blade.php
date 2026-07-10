@extends('layouts.admin')

@section('sidebar_messages', 'active')
@section('page_title', $message->subject ?? 'Message')
@section('page_subtitle', 'Message details')

@push('page_styles')
<style>
.breadcrumb{display:flex;align-items:center;gap:6px;margin-bottom:20px;animation:fadeUp .35s ease both}
.breadcrumb a{font-size:11.5px;color:var(--text3);font-family:var(--mono);transition:color var(--ease)}
.breadcrumb a:hover{color:var(--a)}
.breadcrumb .bc-sep{font-size:11.5px;color:var(--text3);font-family:var(--mono)}
.breadcrumb .bc-cur{font-size:11.5px;color:var(--text2);font-weight:600;font-family:var(--mono)}
.page-hdr{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:22px;flex-wrap:wrap;animation:fadeUp .4s .05s ease both}
.page-hdr-left h2{font-family:var(--mono);font-size:20px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1.2}
.page-hdr-left p{font-size:12px;color:var(--text3);margin-top:4px}
.back-btn{display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border-radius:var(--r-sm);background:var(--surface);border:1px solid var(--border2);color:var(--text2);font-size:12.5px;font-weight:600;transition:all var(--ease);font-family:var(--font);text-decoration:none}
.back-btn:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);transform:translateX(-2px)}
.back-btn svg{width:13px;height:13px}
.detail-grid{display:grid;grid-template-columns:1fr 300px;gap:18px;align-items:start}
.detail-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .10s ease both}
.dc-head{padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;background:var(--surface2)}
.dc-head-left{display:flex;align-items:center;gap:13px}
.sender-av{width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:18px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);box-shadow:0 4px 14px rgba(37,99,235,.3)}
.sender-name{font-size:15px;font-weight:700;color:var(--text);line-height:1.3}
.sender-email{font-size:11px;color:var(--text3);font-family:var(--mono);margin-top:2px}
.dc-body{padding:24px 22px}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:22px}
.info-box{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:14px 16px}
.info-lbl{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.10em;font-family:var(--mono);margin-bottom:6px}
.info-val{font-size:13px;font-weight:600;color:var(--text);line-height:1.4}
.info-val a{color:var(--a)}
.info-val a:hover{text-decoration:underline}
.msg-lbl{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.10em;font-family:var(--mono);margin-bottom:10px;display:flex;align-items:center;gap:8px}
.msg-lbl::after{content:'';flex:1;height:1px;background:var(--border)}
.msg-box{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:18px 20px;line-height:1.8;color:var(--text2);font-size:13.5px;white-space:pre-line}
.dc-foot{display:flex;align-items:center;justify-content:space-between;padding:14px 22px;border-top:1px solid var(--border);background:var(--surface2);gap:10px;flex-wrap:wrap}
.act-btn{display:inline-flex;align-items:center;gap:5px;padding:9px 16px;border-radius:var(--r-xs);font-size:12px;font-weight:600;cursor:pointer;border:1px solid transparent;transition:all var(--ease);white-space:nowrap;font-family:var(--font);text-decoration:none}
.act-btn:hover{transform:translateY(-1px)}
.act-btn:active{transform:scale(.96)}
.act-btn svg{width:13px;height:13px}
.ab-reply{background:var(--a-lt);color:var(--a);border-color:rgba(37,99,235,.2)}
.ab-reply:hover{background:var(--a);color:#fff;box-shadow:0 4px 14px rgba(37,99,235,.35)}
.ab-delete{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.2)}
.ab-delete:hover{background:var(--red);color:#fff;box-shadow:0 4px 14px rgba(240,68,68,.3)}
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:4px 9px;border-radius:7px;text-transform:uppercase;letter-spacing:.07em;font-family:var(--mono)}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0}
.b-new{background:rgba(59,130,246,.12);color:#1d4ed8;border:1px solid rgba(59,130,246,.2)}
.b-read{background:rgba(5,196,138,.12);color:#065f46;border:1px solid rgba(5,196,138,.2)}
[data-theme="dark"] .b-new{color:#93c5fd}
[data-theme="dark"] .b-read{color:#34d399}
.side-panel{display:flex;flex-direction:column;gap:16px}
.side-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both}
.side-card:nth-child(1){animation-delay:.15s}
.side-card:nth-child(2){animation-delay:.22s}
.sc-head{padding:12px 18px;border-bottom:1px solid var(--border);background:var(--surface2);font-family:var(--mono);font-size:10.5px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.09em}
.sc-body{padding:4px 0}
.sc-row{display:flex;flex-direction:column;gap:3px;padding:11px 18px;border-bottom:1px solid var(--border)}
.sc-row:last-child{border-bottom:none}
.sc-key{font-size:10px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em}
.sc-val{font-size:13px;font-weight:600;color:var(--text);word-break:break-all;line-height:1.4}
.sc-val.muted{color:var(--text2);font-weight:400}
.qa-btn{display:flex;align-items:center;gap:10px;width:100%;padding:11px 18px;border:none;background:transparent;color:var(--text2);font-size:13px;font-weight:500;text-align:left;transition:background var(--ease),color var(--ease);cursor:pointer;text-decoration:none;border-bottom:1px solid var(--border)}
.qa-btn:last-child{border-bottom:none}
.qa-btn:hover{background:var(--surface2);color:var(--text)}
.qa-btn.danger:hover{background:var(--red-lt);color:var(--red)}
.qa-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.qa-icon svg{width:13px;height:13px}
.qi-purple{background:var(--a-lt);color:var(--a)}
.qi-gray{background:var(--surface3);color:var(--text3)}
.qi-red{background:var(--red-lt);color:var(--red)}
.reply-modal{position:fixed;inset:0;z-index:1000;display:flex;align-items:center;justify-content:center;padding:20px}
.reply-backdrop{position:absolute;inset:0;background:rgba(10,11,20,.55);backdrop-filter:blur(2px);animation:fadeIn .2s ease both}
.reply-card{position:relative;width:100%;max-width:560px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh-lg,0 20px 60px rgba(0,0,0,.3));overflow:hidden;animation:fadeUp .25s ease both}
.reply-head{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:16px 20px;border-bottom:1px solid var(--border);background:var(--surface2)}
.reply-head h3{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text)}
.reply-x{width:30px;height:30px;border-radius:8px;border:none;background:var(--surface3);color:var(--text3);cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;transition:all var(--ease)}
.reply-x:hover{background:var(--red-lt);color:var(--red)}
.reply-body{padding:18px 20px;display:flex;flex-direction:column;gap:14px;max-height:60vh;overflow-y:auto}
.r-field{display:flex;flex-direction:column;gap:6px}
.r-field label{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono)}
.r-field input,.r-field textarea{width:100%;padding:10px 12px;border-radius:var(--r-xs);border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-size:13px;font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);resize:vertical}
.r-field input:focus,.r-field textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface)}
.r-field input[readonly]{color:var(--text3);font-family:var(--mono)}
.reply-quote{background:var(--surface2);border:1px solid var(--border);border-left:3px solid var(--a);border-radius:var(--r-xs);padding:12px 14px}
.rq-lbl{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);color:var(--text3);margin-bottom:6px}
.rq-text{font-size:12.5px;line-height:1.6;color:var(--text2);white-space:pre-line;max-height:140px;overflow-y:auto}
.reply-foot{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:14px 20px;border-top:1px solid var(--border);background:var(--surface2);flex-wrap:wrap}
.reply-mailto{font-size:12px;font-weight:600;color:var(--a);text-decoration:none}
.reply-mailto:hover{text-decoration:underline}
.reply-actions{display:flex;align-items:center;gap:8px}
.reply-cancel{height:38px;padding:0 16px;border-radius:var(--r-xs);border:1px solid var(--border2);background:transparent;color:var(--text2);font-size:12.5px;font-weight:600;cursor:pointer;font-family:var(--font);transition:all var(--ease)}
.reply-cancel:hover{background:var(--surface3);color:var(--text)}
.reply-send{height:38px;padding:0 18px;border-radius:var(--r-xs);border:none;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:12.5px;font-weight:600;cursor:pointer;font-family:var(--font);display:inline-flex;align-items:center;gap:6px;box-shadow:0 4px 14px rgba(37,99,235,.35);transition:all var(--ease)}
.reply-send:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(37,99,235,.45)}
.reply-send svg{width:14px;height:14px;flex-shrink:0}
.reply-send:disabled{opacity:.6;cursor:not-allowed;transform:none}
@media(max-width:960px){.detail-grid{grid-template-columns:1fr}.side-panel{flex-direction:row;flex-wrap:wrap}.side-card{flex:1;min-width:240px}}
@media(max-width:600px){.info-grid{grid-template-columns:1fr}.dc-foot{flex-direction:column;align-items:stretch}.act-btn{justify-content:center}}
</style>
@endpush

@section('content')
<div class="breadcrumb">
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <span class="bc-sep">/</span>
  <a href="{{ route('admin.messages') }}">Messages</a>
  <span class="bc-sep">/</span>
  <span class="bc-cur">{{ $message->name }}</span>
</div>

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Message from {{ $message->name }}</h2>
    <p>Received {{ $message->created_at->diffForHumans() }} &middot; {{ $message->created_at->format('d M Y, h:i A') }}</p>
  </div>
  <a href="{{ route('admin.messages') }}" class="back-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    Back to Messages
  </a>
</div>

@php $isRead = (bool) $message->is_read; @endphp
<div class="detail-grid">

  <div class="detail-card">
    <div class="dc-head">
      <div class="dc-head-left">
        <div class="sender-av">{{ strtoupper(substr($message->name??'U',0,1)) }}</div>
        <div>
          <div class="sender-name">{{ $message->name }}</div>
          <div class="sender-email">{{ $message->email }}</div>
        </div>
      </div>
      <span class="badge b-{{ $isRead ? 'read' : 'new' }}">
        <span class="badge-dot"></span>{{ $isRead ? 'Read' : 'New' }}
      </span>
    </div>

    <div class="dc-body">
      <div class="info-grid">
        <div class="info-box">
          <div class="info-lbl">Sender Name</div>
          <div class="info-val">{{ $message->name }}</div>
        </div>
        <div class="info-box">
          <div class="info-lbl">Email Address</div>
          <div class="info-val">
            <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
          </div>
        </div>
        <div class="info-box">
          <div class="info-lbl">Subject</div>
          <div class="info-val">{{ $message->subject ?? '—' }}</div>
        </div>
        <div class="info-box">
          <div class="info-lbl">Received On</div>
          <div class="info-val">{{ $message->created_at->format('d M Y · h:i A') }}</div>
        </div>
      </div>

      <div class="msg-lbl">Full Message</div>
      <div class="msg-box">{{ $message->message }}</div>
    </div>

    <div class="dc-foot">
      <button type="button" class="act-btn ab-reply reply-open">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
        Reply via Email
      </button>
      <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST" style="display:inline;">
        @csrf @method('DELETE')
        <button type="submit" class="act-btn ab-delete" onclick="return confirm('Delete this message permanently?')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
          Delete Message
        </button>
      </form>
    </div>
  </div>

  <div class="side-panel">

    <div class="side-card">
      <div class="sc-head">Message Info</div>
      <div class="sc-body">
        <div class="sc-row">
          <div class="sc-key">Message ID</div>
          <div class="sc-val">#{{ $message->id }}</div>
        </div>
        <div class="sc-row">
          <div class="sc-key">Status</div>
          <div class="sc-val">
            <span class="badge b-{{ $isRead ? 'read' : 'new' }}">
              <span class="badge-dot"></span>{{ $isRead ? 'Read' : 'Unread' }}
            </span>
          </div>
        </div>
        <div class="sc-row">
          <div class="sc-key">Received</div>
          <div class="sc-val muted">{{ $message->created_at->format('d M Y') }}</div>
        </div>
        <div class="sc-row">
          <div class="sc-key">Time</div>
          <div class="sc-val muted">{{ $message->created_at->format('h:i A') }}</div>
        </div>
        <div class="sc-row">
          <div class="sc-key">Relative</div>
          <div class="sc-val muted">{{ $message->created_at->diffForHumans() }}</div>
        </div>
      </div>
    </div>

    <div class="side-card">
      <div class="sc-head">Quick Actions</div>
      <div class="sc-body">
        <button type="button" class="qa-btn reply-open">
          <span class="qa-icon qi-purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
          </span>
          Reply via Email
        </button>
        <button type="button" class="qa-btn" id="toggleReadBtn">
          <span class="qa-icon qi-gray">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9 6 9-6"/></svg>
          </span>
          Mark as Unread
        </button>
        <a href="{{ route('admin.messages') }}" class="qa-btn">
          <span class="qa-icon qi-gray">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
          </span>
          All Messages
        </a>
        <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST">
          @csrf @method('DELETE')
          <button type="submit" class="qa-btn danger" style="width:100%;" onclick="return confirm('Delete this message permanently?')">
            <span class="qa-icon qi-red">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
            </span>
            Delete Message
          </button>
        </form>
      </div>
    </div>

  </div>
</div>

<div class="reply-modal" id="replyModal" style="display:none;">
  <div class="reply-backdrop" data-reply-close></div>
  <div class="reply-card">
    <div class="reply-head">
      <h3>Reply to {{ $message->name }}</h3>
      <button type="button" class="reply-x" data-reply-close aria-label="Close">✕</button>
    </div>
    <div class="reply-body">
      <div class="r-field">
        <label>To</label>
        <input type="text" value="{{ $message->email }}" readonly>
      </div>
      <div class="r-field">
        <label>Subject</label>
        <input type="text" id="replySubject" value="Re: {{ $message->subject ?? 'Your message' }}">
      </div>
      <div class="r-field">
        <label>Message</label>
        <textarea id="replyBody" rows="6" placeholder="Write your reply…"></textarea>
      </div>
      <div class="reply-quote">
        <div class="rq-lbl">Original message</div>
        <div class="rq-text">{{ $message->message }}</div>
      </div>
    </div>
    <div class="reply-foot">
      <a class="reply-mailto" href="mailto:{{ $message->email }}">Open in email app instead</a>
      <div class="reply-actions">
        <button type="button" class="reply-cancel" data-reply-close>Cancel</button>
        <button type="button" class="reply-send" id="replySend">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
          Send Reply
        </button>
      </div>
    </div>
  </div>
</div>

@push('page_scripts')
<script>
(function(){
  'use strict';

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
    var url = "{{ route('admin.messages.toggle-read', $message->id) }}";
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

    fetch("{{ route('admin.messages.reply', $message->id) }}", {
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
})();
</script>
@endpush
@endsection
