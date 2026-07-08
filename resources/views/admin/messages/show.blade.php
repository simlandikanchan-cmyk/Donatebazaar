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
.sender-av{width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:18px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);box-shadow:0 4px 14px rgba(110,86,247,.3)}
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
.ab-reply{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.2)}
.ab-reply:hover{background:var(--a);color:#fff;box-shadow:0 4px 14px rgba(110,86,247,.35)}
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
      <a href="mailto:{{ $message->email }}" class="act-btn ab-reply">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
        Reply via Email
      </a>
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
        <a href="mailto:{{ $message->email }}" class="qa-btn">
          <span class="qa-icon qi-purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          </span>
          Reply via Email
        </a>
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

@push('page_scripts')
<script>
(function(){
  'use strict';
  var btn = document.getElementById('toggleReadBtn');
  if(!btn) return;
  var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var url  = "{{ route('admin.messages.toggle-read', $message->id) }}";

  btn.addEventListener('click', function(){
    fetch(url, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' }
    })
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
})();
</script>
@endpush
@endsection
