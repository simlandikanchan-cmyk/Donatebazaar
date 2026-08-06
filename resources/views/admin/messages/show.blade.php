@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/messages.css')
@endpush


@section('sidebar_messages', 'active')
@section('page_title', $message->subject ?? 'Message')
@section('page_subtitle', 'Message details')

@section('content')
@php
  $isRead = (bool) $message->is_read;
  $initials = strtoupper(substr($message->name ?? 'U', 0, 1));
@endphp
<div class="msg-show">

  <!-- Hero Header -->
  <div class="msg-hero">
    <a href="{{ route('admin.messages') }}" class="msg-hero-back" aria-label="Back to messages">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div class="msg-hero-avatar">{{ $initials }}</div>
    <div class="msg-hero-body">
      <h1 class="msg-hero-title">{{ $message->subject ?? 'Message' }}</h1>
      <div class="msg-hero-meta">
        <span class="msg-hero-from">{{ $message->name }} &lt;{{ $message->email }}&gt;</span>
        <span class="msg-hero-sep" aria-hidden="true">·</span>
        <time datetime="{{ $message->created_at->toIso8601String() }}">{{ $message->created_at->format('d M Y · h:i A') }}</time>
        <span class="msg-hero-sep" aria-hidden="true">·</span>
        <span class="msg-hero-relative">{{ $message->created_at->diffForHumans() }}</span>
      </div>
    </div>
    <div class="msg-hero-actions">
      <button type="button" class="msg-star-btn" id="starBtn" aria-label="Star message" title="Star">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
      </button>
      <x-button variant="primary" type="button" class="reply-open" size="sm">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
        Reply
      </x-button>
    </div>
  </div>

  <!-- Main Content -->
  <div class="msg-content-grid">

    <!-- Left Column: Message -->
    <div class="msg-main">
      <div class="msg-body-card">
        <div class="msg-body-content">{{ $message->message }}</div>
      </div>
    </div>

    <!-- Right Column: Sidebar -->
    <aside class="msg-sidebar">

      <!-- Quick Actions -->
      <div class="msg-sidebar-card msg-actions-card">
        <div class="msg-sidebar-header">Actions</div>
        <div class="msg-sidebar-body">
          <button type="button" class="msg-action-btn msg-action-primary reply-open">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            Reply via Email
          </button>
          <button type="button" class="msg-action-btn msg-action-secondary" id="toggleReadBtn">
            @if($isRead)
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9 6 9-6"/></svg>
              Mark as Unread
            @else
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Mark as Read
            @endif
          </button>
          <a href="{{ route('admin.messages') }}" class="msg-action-btn msg-action-ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            All Messages
          </a>
          <div class="msg-sidebar-divider"></div>
          <button type="button" class="msg-action-btn msg-action-danger" onclick="if(confirm('Delete this message?')) document.getElementById('deleteForm').submit();">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
            Delete Message
          </button>
          <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST" id="deleteForm" style="display:none;">@csrf @method('DELETE')</form>
        </div>
      </div>

      <!-- Message Details -->
      <div class="msg-sidebar-card msg-meta-card">
        <div class="msg-sidebar-header">Details</div>
        <div class="msg-sidebar-body">
          <div class="msg-detail-row">
            <div class="msg-detail-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div class="msg-detail-content">
              <div class="msg-detail-label">From</div>
              <div class="msg-detail-value">{{ $message->name }}</div>
              <div class="msg-detail-sub">{{ $message->email }}</div>
            </div>
          </div>
          <div class="msg-detail-row">
            <div class="msg-detail-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="msg-detail-content">
              <div class="msg-detail-label">Received</div>
              <div class="msg-detail-value">{{ $message->created_at->format('d M Y · h:i A') }}</div>
              <div class="msg-detail-sub">{{ $message->created_at->diffForHumans() }}</div>
            </div>
          </div>
          @if($message->subject)
          <div class="msg-detail-row">
            <div class="msg-detail-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
            </div>
            <div class="msg-detail-content">
              <div class="msg-detail-label">Subject</div>
              <div class="msg-detail-value">{{ $message->subject }}</div>
            </div>
          </div>
          @endif
          <div class="msg-detail-row">
            <div class="msg-detail-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="msg-detail-content">
              <div class="msg-detail-label">Status</div>
              <div class="msg-detail-value">
                <span class="msg-status-badge msg-status-{{ $isRead ? 'read' : 'unread' }}">
                  <span class="msg-status-dot"></span>
                  {{ $isRead ? 'Read' : 'Unread' }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

    </aside>
  </div>
</div>

<!-- Reply Modal -->
<div class="reply-modal" id="replyModal" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="replyModalTitle">
  <div class="reply-backdrop" data-reply-close></div>
  <div class="reply-card">
    <div class="reply-head">
      <h3 id="replyModalTitle">Reply to {{ $message->name }}</h3>
      <button type="button" class="reply-x" data-reply-close aria-label="Close reply modal">✕</button>
    </div>
    <div class="reply-body">
      <div class="r-field">
        <label for="replyTo">To</label>
        <input type="email" id="replyTo" value="{{ $message->email }}" readonly>
      </div>
      <div class="r-field">
        <label for="replySubject">Subject</label>
        <input type="text" id="replySubject" value="Re: {{ $message->subject ?? 'Your message' }}">
      </div>
      <div class="r-field">
        <label for="replyBody">Message</label>
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
        <x-button variant="secondary" type="button" class="reply-cancel">Cancel</x-button>
        <x-button variant="primary" type="button" class="reply-send" id="replySend">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
          Send Reply
        </x-button>
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

  /* star toggle */
  var starBtn = document.getElementById('starBtn');
  if(starBtn){
    starBtn.addEventListener('click', function(){
      var active = starBtn.classList.toggle('is-starred');
      starBtn.setAttribute('aria-label', active ? 'Unstar message' : 'Star message');
      starBtn.setAttribute('title', active ? 'Unstar' : 'Star');
      toast(active ? 'Message starred.' : 'Star removed.', 'success');
    });
  }

  /* mark as read / unread */
  var toggleBtn = document.getElementById('toggleReadBtn');
  if(toggleBtn){
    var url = "{{ route('admin.messages.toggle-read', $message->id) }}";
    toggleBtn.addEventListener('click', function(){
      fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' } })
      .then(function(r){ return r.json(); })
      .then(function(d){
        if(!d.ok) return;
        var badges = document.querySelectorAll('.msg-status-badge');
        badges.forEach(function(b){
          b.className = 'msg-status-badge msg-status-' + (d.is_read ? 'read' : 'unread');
          b.innerHTML = '<span class="msg-status-dot"></span>' + (d.is_read ? 'Read' : 'Unread');
        });
        var icon = toggleBtn.querySelector('svg');
        var label = toggleBtn.childNodes[toggleBtn.childNodes.length - 1];
        if(d.is_read){
          icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9 6 9-6"/>';
          label.textContent = ' Mark as Unread';
        } else {
          icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
          label.textContent = ' Mark as Read';
        }
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
