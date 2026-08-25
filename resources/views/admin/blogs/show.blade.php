@extends('layouts.admin')

@section('sidebar_blogs', 'active')
@section('page_title', 'Review Post')
@section('page_subtitle', Str::limit($blog->title ?? 'Blog post', 50))

@push('page_styles')
@vite('resources/css/admin/entries/blogs-show.css')
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
        {!! nl2br($blog->content) !!}
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
          <button type="button" class="btn btn-red act-full af-reject" data-action="open-reject-modal">
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
        <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" onsubmit="return confirm('Delete \'{{ addslashes($blog->title) }}\' permanently? This cannot be undone.');">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-red act-btn ab-delete">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            Delete Post
          </button>
        </form>
      </div>
    </div>
  </div>
</aside>
</div>{{-- /.review-layout --}}

<div class="modal-backdrop" id="rejectModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
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
        <button type="button" class="btn btn-secondary modal-cancel" data-action="close-reject-modal">Cancel</button>
        <button type="button" class="btn btn-red modal-confirm" data-action="submit-reject">Reject post</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('page_styles')
<style>
@media(max-width:960px){
  .review-layout{grid-template-columns:1fr!important}
  .review-panel{order:-1}
}
@media(max-width:640px){
  .cover-wrap img{max-height:220px;object-fit:cover;width:100%}
  .blog-prose{font-size:14px;line-height:1.7}
  .engage-strip{flex-wrap:wrap}
  .es-divider{display:none}
}
</style>
@endpush

@push('page_scripts')
@vite('resources/js/admin/entries/blogs-show.js')
@endpush
