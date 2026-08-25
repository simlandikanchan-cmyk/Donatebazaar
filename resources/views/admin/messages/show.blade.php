@push('page_styles')
@vite('resources/css/admin/entries/messages.css')
<style>
@media(max-width:860px){
  .detail-grid{grid-template-columns:1fr!important}
  .side-panel{width:100%}
}
@media(max-width:640px){
  .info-grid{grid-template-columns:1fr!important}
  .dc-foot{flex-wrap:wrap}
  .dc-foot .btn{flex:1;min-width:140px;justify-content:center}
}
</style>
@endpush

@extends('layouts.admin')

@section('sidebar_messages', 'active')
@section('page_title', $message->subject ?? 'Message')
@section('page_subtitle', 'Message details')

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
  <a href="{{ route('admin.messages') }}" class="btn btn-secondary back-btn">
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
      <button type="button" class="btn btn-secondary act-btn ab-reply reply-open">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
        Reply via Email
      </button>
      <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this message? This cannot be undone.');">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-red act-btn ab-delete" title="Delete">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
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
        <button type="button" class="btn btn-secondary qa-btn reply-open">
          <span class="qa-icon qi-purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
          </span>
          Reply via Email
        </button>
        <button type="button" class="btn btn-secondary qa-btn" id="toggleReadBtn">
          <span class="qa-icon qi-gray">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9 6 9-6"/></svg>
          </span>
          Mark as Unread
        </button>
        <a href="{{ route('admin.messages') }}" class="btn btn-secondary qa-btn">
          <span class="qa-icon qi-gray">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
          </span>
          All Messages
        </a>
        <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-red act-btn ab-delete" data-action="confirm-delete" data-confirm="Delete this message permanently?">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
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
        <button type="button" class="btn btn-secondary reply-cancel" data-reply-close>Cancel</button>
        <button type="button" class="btn btn-primary reply-send" id="replySend">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
          Send Reply
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Page data for messages-show.js --}}
<script type="application/json" id="messagesShowData">
@php
    $messagesShowData = [
        'toggleUrl' => route('admin.messages.toggle-read', $message->id),
        'replyUrl'  => route('admin.messages.reply', $message->id),
    ];
@endphp
@json($messagesShowData)
</script>
@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/messages-show.js')
@endpush

@push('page_styles')
@vite('resources/css/admin/pages/messages-show.css')
<style>
@media(max-width:860px){.detail-grid{grid-template-columns:1fr}}
.reply-modal{max-width:calc(100% - 32px)}
</style>
@endpush
