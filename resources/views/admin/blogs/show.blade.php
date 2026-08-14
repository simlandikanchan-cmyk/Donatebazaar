@extends('layouts.admin')

@section('sidebar_blogs', 'active')
@section('page_title', 'Review Post')
@section('page_subtitle', Str::limit($blog->title ?? 'Blog post', 50))

@push('page_styles')
<style>
/* ── show page-specific ── */
.btn-back{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 14px;background:var(--surface2);color:var(--text2);border-radius:var(--r-sm);font-size:12px;font-weight:600;border:1px solid var(--border2);transition:all var(--ease);text-decoration:none;}
.btn-back:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.btn-back svg{width:13px;height:13px;}
.body{padding:26px 28px 56px;}
.review-layout{display:flex;gap:20px;align-items:flex-start;}
.review-content{flex:1;min-width:0;animation:fadeUp .4s .05s both;}
.content-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;}
.cover-wrap{aspect-ratio:16/7;background:var(--surface2);overflow:hidden;border-bottom:1px solid var(--border);position:relative;}
.cover-wrap img{width:100%;height:100%;object-fit:cover;}
.cover-placeholder{width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;color:var(--text3);}
.cover-placeholder svg{width:32px;height:32px;opacity:.3;}
.cover-placeholder span{font-size:12px;font-family:var(--mono);}
.prose-area{padding:28px 32px 36px;}
.blog-cat-tag{font-family:var(--mono);font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--a);margin-bottom:10px;}
.blog-title{font-family:'DM Mono',monospace;font-size:clamp(22px,2.8vw,30px);font-weight:800;line-height:1.2;color:var(--text);margin-bottom:14px;letter-spacing:-.01em;text-transform:capitalize;}
.blog-byline{display:flex;align-items:center;gap:10px;padding-bottom:18px;border-bottom:1px solid var(--border);margin-bottom:20px;flex-wrap:wrap;}
.byline-av{width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);}
.byline-text{font-size:12.5px;color:var(--text2);}
.byline-text strong{color:var(--text);font-weight:600;}
.byline-sep{width:3px;height:3px;border-radius:50%;background:var(--text3);display:inline-block;margin:0 6px;vertical-align:middle;}
.engage-strip{display:flex;align-items:center;gap:16px;padding:11px 16px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);margin-bottom:22px;flex-wrap:wrap;}
.es-item{display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text2);}
.es-item svg{width:13px;height:13px;flex-shrink:0;}
.es-item strong{font-weight:700;color:var(--text);}
.es-divider{width:1px;height:14px;background:var(--border2);flex-shrink:0;}
.blog-excerpt{background:var(--a-lt);border-left:3px solid var(--a);border-radius:0 var(--r-sm) var(--r-sm) 0;padding:14px 18px;margin-bottom:22px;font-size:15px;font-style:italic;color:var(--text2);line-height:1.75;}
.blog-prose{font-size:15px;line-height:1.85;color:var(--text2);}
.blog-prose p{margin-bottom:1.25rem;}
.review-panel{width:276px;flex-shrink:0;display:flex;flex-direction:column;gap:14px;position:sticky;top:80px;}
.panel-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s both;}
.panel-card:nth-child(1){animation-delay:.05s}.panel-card:nth-child(2){animation-delay:.10s}.panel-card:nth-child(3){animation-delay:.15s}.panel-card:nth-child(4){animation-delay:.20s}.panel-card:nth-child(5){animation-delay:.25s}.panel-card:nth-child(6){animation-delay:.30s}
.panel-head{padding:12px 16px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.panel-head-title{font-family:var(--mono);font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;}
.panel-head-live{display:inline-flex;align-items:center;gap:4px;font-size:9.5px;font-weight:600;color:var(--green);font-family:var(--mono);}
.panel-head-live::before{content:'';width:6px;height:6px;border-radius:50%;background:var(--green);animation:pulse 1.8s infinite;}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.85)}}
.panel-body{padding:16px;}
.meta-row{display:flex;flex-direction:column;gap:2px;margin-bottom:12px;}
.meta-row:last-child{margin-bottom:0;}
.meta-key{font-family:var(--mono);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);}
.meta-val{font-size:13px;font-weight:600;color:var(--text);margin-top:2px;}
.meta-val.muted{font-weight:400;color:var(--text2);}
.b-pending{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.3);}
.b-approved{background:var(--green-lt);color:#065f46;border:1px solid rgba(5,196,138,.3);}
.b-rejected{background:var(--red-lt);color:#991b1b;border:1px solid rgba(240,68,68,.3);}
[data-theme="dark"] .b-pending{color:var(--amber);}
[data-theme="dark"] .b-approved{color:#34d399;}
[data-theme="dark"] .b-rejected{color:#f87171;}
.cat-tag{display:inline-block;padding:3px 10px;border-radius:7px;font-size:11px;font-weight:600;background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.18);}
.eng-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.eng-box{background:var(--surface2);border:1px solid var(--border);border-radius:11px;padding:12px 14px;display:flex;flex-direction:column;gap:6px;transition:transform var(--ease),box-shadow var(--ease);}
.eng-box:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.08);}
.eng-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.eng-icon svg{width:14px;height:14px;}
.ei-blue{background:var(--blue-lt);color:var(--blue);}
.ei-pink{background:rgba(236,72,153,.12);color:#ec4899;}
.ei-green{background:var(--green-lt);color:var(--green);}
.ei-yellow{background:var(--amber-lt);color:var(--amber);}
.eng-num{font-family:var(--mono);font-size:1.45rem;font-weight:800;line-height:1;letter-spacing:-.03em;}
.en-blue{color:var(--blue);}.en-pink{color:#ec4899;}.en-green{color:var(--green);}.en-yellow{color:var(--amber);}
.eng-label{font-family:var(--mono);font-size:9.5px;color:var(--text3);text-transform:uppercase;letter-spacing:.07em;}
.eng-bar-wrap{margin-top:4px;}
.eng-bar-label{display:flex;justify-content:space-between;font-size:10px;font-family:var(--mono);color:var(--text3);margin-bottom:4px;}
.eng-bar-track{height:5px;background:var(--surface2);border-radius:100px;overflow:hidden;border:1px solid var(--border);}
.eng-bar-fill{height:100%;border-radius:100px;transition:width 1s cubic-bezier(.4,0,.2,1);}
.fill-blue{background:var(--blue);}.fill-pink{background:#ec4899;}.fill-green{background:var(--green);}.fill-yellow{background:var(--amber);}
.stat-pair{display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.stat-box{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:10px 12px;text-align:center;}
.stat-box .sn{font-family:var(--mono);font-size:1.4rem;font-weight:800;color:var(--a);line-height:1;letter-spacing:-.02em;}
.stat-box .sl{font-size:9.5px;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);margin-top:3px;}
.reject-reason-box{background:var(--red-lt);border:1px solid rgba(240,68,68,.2);border-radius:var(--r-sm);padding:10px 12px;font-size:12.5px;color:var(--red);line-height:1.55;}
[data-theme="dark"] .reject-reason-box{color:#f87171;}
.comment-list{display:flex;flex-direction:column;gap:10px;}
.comment-item{display:flex;gap:8px;align-items:flex-start;}
.comment-ava{width:26px;height:26px;border-radius:7px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);}
.comment-body{flex:1;min-width:0;}
.comment-name{font-size:11.5px;font-weight:600;color:var(--text);line-height:1.2;}
.comment-text{font-size:11px;color:var(--text3);margin-top:2px;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;}
.comment-time{font-family:var(--mono);font-size:9.5px;color:var(--text3);margin-top:3px;}
.no-comments{font-family:var(--mono);font-size:12px;color:var(--text3);text-align:center;padding:10px 0;}
.action-stack{display:flex;flex-direction:column;gap:7px;}
.act-full{display:flex;align-items:center;justify-content:center;gap:7px;width:100%;padding:10px 14px;border-radius:var(--r-sm);font-size:13px;font-weight:600;cursor:pointer;border:1px solid transparent;transition:opacity var(--ease),transform var(--ease);font-family:var(--font);text-decoration:none;}
.act-full:hover{opacity:.88;}
.act-full:active{transform:scale(.98);}
.act-full svg{width:13px;height:13px;}
.af-approve{background:var(--green);color:#fff;border-color:var(--green);}
.af-reject{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.25);}
[data-theme="dark"] .af-reject{color:#f87171;}
.af-edit{background:var(--surface2);color:var(--text2);border-color:var(--border2);}
.af-edit:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);}
.af-feature{background:var(--amber-lt);color:var(--amber);border-color:rgba(245,158,11,.3);}
.af-feature:hover{background:var(--amber);color:#fff;border-color:var(--amber);}
.af-archive{background:var(--blue-lt);color:var(--blue);border-color:rgba(59,130,246,.3);}
.af-archive:hover{background:var(--blue);color:#fff;border-color:var(--blue);}
.modal-backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(4px);z-index:500;align-items:center;justify-content:center;}
.modal-backdrop.open{display:flex;}
.modal-box{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:24px;width:420px;max-width:90vw;box-shadow:var(--sh-lg);animation:modalIn .2s ease;}
@keyframes modalIn{from{opacity:0;transform:translateY(12px) scale(.97)}to{opacity:1;transform:none}}
.modal-icon{width:44px;height:44px;background:var(--red-lt);border-radius:11px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;}
.modal-icon svg{width:20px;height:20px;color:var(--red);}
.modal-title{font-family:'DM Mono',monospace;font-size:18px;font-weight:800;color:var(--text);margin-bottom:4px;}
.modal-sub{font-size:12.5px;color:var(--text3);margin-bottom:16px;}
.modal-textarea{width:100%;min-height:100px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-family:var(--font);font-size:13px;color:var(--text);resize:vertical;outline:none;transition:border-color var(--ease);}
.modal-textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.modal-textarea::placeholder{color:var(--text3);}
.modal-error{font-family:var(--mono);font-size:11.5px;color:var(--red);margin-top:5px;display:none;}
.modal-footer{display:flex;gap:8px;margin-top:16px;justify-content:flex-end;}
.modal-cancel{padding:7px 14px;border:1px solid var(--border2);background:var(--surface2);border-radius:var(--r-xs);font-family:var(--font);font-size:12.5px;font-weight:500;cursor:pointer;color:var(--text2);transition:background var(--ease);}
.modal-cancel:hover{background:var(--border);}
.modal-confirm{padding:7px 16px;background:var(--red);color:#fff;border:none;border-radius:var(--r-xs);font-family:var(--font);font-size:12.5px;font-weight:600;cursor:pointer;transition:opacity var(--ease);}
.modal-confirm:hover{opacity:.85;}
@media(max-width:900px){.review-layout{flex-direction:column;}.review-panel{width:100%;position:static;display:grid;grid-template-columns:repeat(2,1fr);}}
@media(max-width:600px){.review-panel{grid-template-columns:1fr;}}
@media(max-width:480px){.body{padding:16px 14px 40px}.prose-area{padding:18px 16px 24px}.engage-strip{flex-direction:column;align-items:flex-start;gap:8px}.es-divider{display:none}.blog-byline{flex-direction:column;align-items:flex-start}.blog-title{font-size:clamp(18px,5vw,22px)}.eng-grid{grid-template-columns:1fr 1fr}.stat-pair{grid-template-columns:1fr 1fr}.cover-wrap{aspect-ratio:16/9}}
@media(max-width:380px){.body{padding:12px 10px 32px}.prose-area{padding:14px 12px 20px}.blog-title{font-size:clamp(16px,5vw,20px)}.blog-prose{font-size:13px}.engage-strip{padding:10px 14px}.eng-box{padding:10px 12px}.eng-num{font-size:1.2rem}.stat-box .sn{font-size:1.1rem}.modal-box{padding:18px}.modal-title{font-size:15px}}
</style>
@endpush
@section('content')
@php
  $categoryName = null;
  if (!empty($blog->category)) {
    if (is_string($blog->category)) {
      $decoded = json_decode($blog->category, true);
      $categoryName = is_array($decoded) ? ($decoded['name'] ?? $blog->category) : $blog->category;
    } elseif (is_object($blog->category)) {
      $categoryName = $blog->category->name ?? null;
    } elseif (is_array($blog->category)) {
      $categoryName = $blog->category['name'] ?? null;
    }
  }

  $wordCount = str_word_count(strip_tags($blog->content ?? ''));
  $readTime  = max(1, (int) round($wordCount / 200));

  $views    = (int) ($blog->views_count    ?? (method_exists($blog, 'views')    ? $blog->views()->count()    : 0));
  $likes    = (int) ($blog->likes_count    ?? (method_exists($blog, 'likes')    ? $blog->likes()->count()    : 0));
  $comments = (int) ($blog->comments_count ?? (method_exists($blog, 'comments') ? $blog->comments()->count() : 0));
  $shares   = (int) ($blog->shares_count   ?? (method_exists($blog, 'shares')   ? $blog->shares()->count()   : 0));

  $total     = max(1, $views + $likes + $comments + $shares);
  $pViews    = (int) round($views    / $total * 100);
  $pLikes    = (int) round($likes    / $total * 100);
  $pComments = (int) round($comments / $total * 100);
  $pShares   = (int) round($shares   / $total * 100);

  $recentComments = method_exists($blog, 'comments')
    ? $blog->comments()->latest()->limit(3)->get()
    : collect();
@endphp

{{-- HERO --}}
<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Blog Review</div>
    <div class="hero-name">{{ Str::limit($blog->title ?? 'Blog Post', 60) }}</div>
    <div class="hero-sub">Submitted {{ $blog->created_at->format('d M Y') }} by {{ $blog->author->name ?? 'Unknown' }} · {{ $readTime }} min read</div>
    <div class="hero-badges">
      @php
        $statusKey = $blog->status ?? 'pending';
        $heroMap = [
          'published' => 'hb-green',
          'approved'  => 'hb-green',
          'pending'   => 'hb-amber',
          'rejected'  => 'hb-red',
          'archived'  => 'hb-blue',
          'flagged'   => 'hb-purple',
          'draft'     => 'hb-gray',
        ];
      @endphp
      <span class="hero-badge {{ $heroMap[$statusKey] ?? 'hb-gray' }}">{{ ucfirst($statusKey) }}</span>
      @if($blog->is_featured)
        <span class="hero-badge hb-amber">
          <svg viewBox="0 0 24 24" fill="currentColor" stroke="none" style="width:10px;height:10px;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          Featured
        </span>
      @endif
      <span class="hero-badge hb-blue">{{ number_format($views) }} views</span>
      <span class="hero-badge hb-gray">{{ $wordCount }} words</span>
      @if($categoryName)
        <span class="hero-badge hb-purple">{{ $categoryName }}</span>
      @endif
    </div>
  </div>
  <div class="hero-right">
    <a href="{{ route('admin.blogs.index') }}" class="hero-btn hero-btn-ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Back to Posts
    </a>
  </div>
</div>

<div class="review-layout">
<div class="review-content">
  <div class="content-card">
    <div class="cover-wrap">
      @if(!empty($blog->cover_image))
        <img src="{{ asset('storage/'.ltrim($blog->cover_image, '/')) }}"
             alt="{{ e($blog->title) }}"
             loading="lazy">
      @else
        <div class="cover-placeholder">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          <span>No cover image uploaded</span>
        </div>
      @endif
    </div>

    <div class="prose-area">
      @if($categoryName)
        <div class="blog-cat-tag">{{ $categoryName }}</div>
      @endif

      <h1 class="blog-title">{{ $blog->title }}</h1>

      <div class="blog-byline">
        <div class="byline-av">{{ strtoupper(substr($blog->author->name ?? 'U', 0, 2)) }}</div>
        <span class="byline-text">
          <strong>{{ $blog->author->name ?? 'Unknown' }}</strong>
          <span class="byline-sep"></span>
          {{ $blog->created_at->format('d M Y') }}
          <span class="byline-sep"></span>
          {{ $readTime }} min read
        </span>
      </div>

      <div class="engage-strip">
        <div class="es-item" style="color:var(--blue);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          <strong>{{ number_format($views) }}</strong>&nbsp;views
        </div>
        <div class="es-divider"></div>
        <div class="es-item" style="color:var(--pink);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
          <strong>{{ number_format($likes) }}</strong>&nbsp;likes
        </div>
        <div class="es-divider"></div>
        <div class="es-item" style="color:var(--green);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
          <strong>{{ number_format($comments) }}</strong>&nbsp;comments
        </div>
        <div class="es-divider"></div>
        <div class="es-item" style="color:var(--amber);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
          <strong>{{ number_format($shares) }}</strong>&nbsp;shares
        </div>
      </div>

      @if(!empty($blog->excerpt))
        <blockquote class="blog-excerpt">{{ $blog->excerpt }}</blockquote>
      @endif

      <div class="blog-prose">
        {!! nl2br(e($blog->content)) !!}
      </div>
    </div>
  </div>
</div>

<aside class="review-panel">
  <div class="panel-card">
    <div class="panel-head">
      <span class="panel-head-title">Post Details</span>
    </div>
    <div class="panel-body">
      <div class="meta-row">
        <span class="meta-key">Status</span>
        <div style="margin-top:4px;">
          @php $status = $blog->status ?? 'pending'; @endphp
          <span class="badge b-{{ $status }}">
            <span class="badge-dot"></span>{{ ucfirst($status) }}
          </span>
        </div>
      </div>
      <div class="meta-row">
        <span class="meta-key">Author</span>
        <span class="meta-val">{{ $blog->author->name ?? 'Unknown' }}</span>
        <span class="meta-val muted">{{ e($blog->author->email ?? '') }}</span>
      </div>
      <div class="meta-row">
        <span class="meta-key">Submitted</span>
        <span class="meta-val">{{ $blog->created_at->format('d M Y') }}</span>
        <span class="meta-val muted">{{ $blog->created_at->diffForHumans() }}</span>
      </div>
      @if($categoryName)
      <div class="meta-row">
        <span class="meta-key">Category</span>
        <div style="margin-top:4px;"><span class="cat-tag">{{ $categoryName }}</span></div>
      </div>
      @endif
    </div>
  </div>

  <div class="panel-card">
    <div class="panel-head">
      <span class="panel-head-title">Engagement</span>
      <span class="panel-head-live">Live</span>
    </div>
    <div class="panel-body">
      <div class="eng-grid">
        <div class="eng-box">
          <div class="eng-icon ei-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></div>
          <div class="eng-num en-blue">{{ number_format($views) }}</div>
          <div class="eng-label">Views</div>
        </div>
        <div class="eng-box">
          <div class="eng-icon ei-pink"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
          <div class="eng-num en-pink">{{ number_format($likes) }}</div>
          <div class="eng-label">Likes</div>
        </div>
        <div class="eng-box">
          <div class="eng-icon ei-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></div>
          <div class="eng-num en-green">{{ number_format($comments) }}</div>
          <div class="eng-label">Comments</div>
        </div>
        <div class="eng-box">
          <div class="eng-icon ei-yellow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg></div>
          <div class="eng-num en-yellow">{{ number_format($shares) }}</div>
          <div class="eng-label">Shares</div>
        </div>
      </div>
      <div style="margin-top:14px;display:flex;flex-direction:column;gap:9px;">
        @foreach([
          ['label'=>'Views',    'color'=>'var(--blue)',  'fill'=>'fill-blue',  'pct'=>$pViews],
          ['label'=>'Likes',    'color'=>'var(--pink)',  'fill'=>'fill-pink',  'pct'=>$pLikes],
          ['label'=>'Comments', 'color'=>'var(--green)', 'fill'=>'fill-green', 'pct'=>$pComments],
          ['label'=>'Shares',   'color'=>'var(--amber)', 'fill'=>'fill-yellow','pct'=>$pShares],
        ] as $bar)
        <div class="eng-bar-wrap">
          <div class="eng-bar-label">
            <span style="color:{{ $bar['color'] }};">{{ $bar['label'] }}</span>
            <span>{{ $bar['pct'] }}%</span>
          </div>
          <div class="eng-bar-track">
            <div class="eng-bar-fill {{ $bar['fill'] }}" style="width:0%" data-width="{{ $bar['pct'] }}%"></div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="panel-card">
    <div class="panel-head"><span class="panel-head-title">Content Stats</span></div>
    <div class="panel-body">
      <div class="stat-pair">
        <div class="stat-box">
          <div class="sn">{{ number_format($wordCount) }}</div>
          <div class="sl">Words</div>
        </div>
        <div class="stat-box">
          <div class="sn">{{ $readTime }}m</div>
          <div class="sl">Read time</div>
        </div>
      </div>
    </div>
  </div>

  <div class="panel-card">
    <div class="panel-head">
      <span class="panel-head-title">Recent Comments</span>
      <span style="font-family:var(--mono);font-size:10px;color:var(--text3);">{{ number_format($comments) }} total</span>
    </div>
    <div class="panel-body">
      @if($recentComments->isNotEmpty())
        <div class="comment-list">
          @foreach($recentComments as $c)
          <div class="comment-item">
            <div class="comment-ava">{{ strtoupper(substr($c->user->name ?? $c->name ?? 'U', 0, 1)) }}</div>
            <div class="comment-body">
              <div class="comment-name">{{ $c->user->name ?? $c->name ?? 'Anonymous' }}</div>
              <div class="comment-text">{{ $c->body ?? $c->content ?? $c->comment ?? '' }}</div>
              <div class="comment-time">{{ $c->created_at->diffForHumans() }}</div>
            </div>
          </div>
          @endforeach
        </div>
      @else
        <p class="no-comments">No comments yet.</p>
      @endif
    </div>
  </div>

  @if(($blog->status ?? '') === 'rejected' && !empty($blog->rejection_reason))
  <div class="panel-card">
    <div class="panel-head"><span class="panel-head-title">Rejection Reason</span></div>
    <div class="panel-body">
      <div class="reject-reason-box">{{ $blog->rejection_reason }}</div>
    </div>
  </div>
  @endif

  <div class="panel-card">
    <div class="panel-head"><span class="panel-head-title">Actions</span></div>
    <div class="panel-body">
      <div class="action-stack">
        @if($blog->status === 'pending')
          <form method="POST" action="{{ route('admin.blogs.approve', $blog) }}">
            @csrf
            <button type="submit" class="btn btn-green act-full af-approve">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              Approve Post
            </button>
          </form>
          <button type="button" class="btn btn-red act-full af-reject" onclick="openRejectModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Reject Post
          </button>
        @elseif($blog->status === 'approved')
          <div class="act-full" style="background:var(--green-lt);color:var(--green);border:1px solid rgba(5,196,138,.3);cursor:default;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Already Approved
          </div>
        @elseif($blog->status === 'rejected')
          <form method="POST" action="{{ route('admin.blogs.approve', $blog) }}">
            @csrf
            <button type="submit" class="btn btn-green act-full af-approve">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              Re-Approve Post
            </button>
          </form>
        @endif
        <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-secondary act-full af-edit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit Post
        </a>
        <form method="POST" action="{{ route('admin.blogs.feature', $blog) }}">
          @csrf
          <button type="submit" class="btn act-full af-feature">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            {{ $blog->is_featured ? 'Unfeature Post' : 'Feature Post' }}
          </button>
        </form>
        <form method="POST" action="{{ route('admin.blogs.archive', $blog) }}" onsubmit="return confirm('Archive \'{{ addslashes($blog->title) }}\'?')">
          @csrf
          <button type="submit" class="btn act-full af-archive">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8v13H3V8m2-4h14a2 2 0 012 2v2H3V6a2 2 0 012-2z"/></svg>
            Archive Post
          </button>
        </form>
      </div>
    </div>
  </div>
</aside>
</div>{{-- /.review-layout --}}

<div class="modal-backdrop" id="rejectModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle"
     onclick="if(event.target===this)closeRejectModal()">
  <div class="modal-box">
    <div class="modal-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    </div>
    <h2 class="modal-title" id="modalTitle">Reject this post?</h2>
    <p class="modal-sub">Please provide a reason — it will be shared with the author.</p>
    <form method="POST" action="{{ route('admin.blogs.reject', $blog) }}" id="rejectForm">
      @csrf
      <textarea name="reason" id="reject_reason" class="modal-textarea"
                placeholder="e.g. Content doesn't meet our editorial guidelines…"
                maxlength="1000" aria-describedby="reject-error"></textarea>
      <p class="modal-error" id="reject-error" role="alert">A reason is required before rejecting.</p>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary modal-cancel" onclick="closeRejectModal()">Cancel</button>
        <button type="button" class="btn btn-red modal-confirm" onclick="submitReject()">Reject post</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('page_scripts')
<script>
(function () {
  'use strict';

  var html   = document.documentElement;
  var toggle = document.getElementById('themeToggle');

  window.addEventListener('load', function () {
    setTimeout(function () {
      document.querySelectorAll('.eng-bar-fill[data-width]').forEach(function (el) {
        el.style.width = el.dataset.width;
      });
    }, 300);
  });

})();

function openRejectModal() {
  document.getElementById('rejectModal').classList.add('open');
  setTimeout(function () { document.getElementById('reject_reason').focus(); }, 180);
}

function closeRejectModal() {
  document.getElementById('rejectModal').classList.remove('open');
  document.getElementById('reject_reason').value = '';
  document.getElementById('reject-error').style.display = 'none';
}

function submitReject() {
  var reason = document.getElementById('reject_reason').value.trim();
  var errEl  = document.getElementById('reject-error');
  if (!reason) {
    errEl.style.display = 'block';
    document.getElementById('reject_reason').focus();
    return;
  }
  errEl.style.display = 'none';
  document.getElementById('rejectForm').submit();
}

document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') closeRejectModal();
});
</script>
@endpush
