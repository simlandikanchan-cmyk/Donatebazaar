@extends('layouts.app')

@section('title', 'Success Stories & Impact')
@section('meta_description', 'See the real impact of campaigns completed on DonateBazaar. Stories of change, powered by donors like you.')

@section('content')
<div class="impact-page">
    {{-- ═══════════════════ HERO ═══════════════════ --}}
    <div class="impact-hero">
        <div class="impact-hero-bg"></div>
        <div class="impact-hero-inner">
            <div class="impact-hero-eyebrow">Success Stories</div>
            <h1>Real Impact,<br><em>Real Stories</em></h1>
            <p>Every completed campaign represents lives changed, communities strengthened, and hope restored.</p>
        </div>
    </div>

    {{-- ═══════════════════ STATS BAND ═══════════════════ --}}
    <div class="impact-stats">
        <div class="impact-stats-inner">
            <div class="impact-stat">
                <span class="impact-stat-num">₹{{ number_format($totalRaised) }}+</span>
                <span class="impact-stat-label">Total Raised</span>
            </div>
            <div class="impact-stat-divider"></div>
            <div class="impact-stat">
                <span class="impact-stat-num">{{ number_format($totalCampaigns) }}</span>
                <span class="impact-stat-label">Campaigns Completed</span>
            </div>
            <div class="impact-stat-divider"></div>
            <div class="impact-stat">
                <span class="impact-stat-num">{{ number_format($livesImpacted) }}+</span>
                <span class="impact-stat-label">Lives Impacted</span>
            </div>
            <div class="impact-stat-divider"></div>
            <div class="impact-stat">
                <span class="impact-stat-num">{{ number_format($totalDonors) }}+</span>
                <span class="impact-stat-label">Donors</span>
            </div>
            <div class="impact-stat-divider"></div>
            <div class="impact-stat">
                <span class="impact-stat-num">{{ $statesCovered }}+</span>
                <span class="impact-stat-label">States Reached</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════ FEATURED ═══════════════════ --}}
    @if($featured)
    <div class="impact-featured-wrap">
        <div class="impact-featured">
            <div class="impact-featured-img">
                @if($featured->cover_image)
                <img src="{{ asset('storage/'.$featured->cover_image) }}" alt="{{ $featured->title }}">
                @else
                <div class="impact-featured-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                </div>
                @endif
                <div class="impact-featured-badge">Most Raised</div>
            </div>
            <div class="impact-featured-body">
                <div class="impact-featured-category">{{ $featured->category?->name ?? 'General' }}</div>
                <h2>{{ $featured->title }}</h2>
                <p>{{ Str::limit(strip_tags($featured->description), 200) }}</p>
                <div class="impact-featured-meta">
                    <div class="impact-featured-progress">
                        <div class="impact-featured-progress-bar" style="width:{{ min(100, round($featured->raised_amount / max($featured->goal_amount, 1) * 100)) }}%"></div>
                    </div>
                    <div class="impact-featured-stats">
                        <span><strong>₹{{ number_format($featured->raised_amount) }}</strong> raised</span>
                        <span>of ₹{{ number_format($featured->goal_amount) }} goal</span>
                    </div>
                </div>
                <a href="{{ route('campaign.public', ['category' => $featured->category->slug ?? 'general', 'slug' => $featured->slug]) }}" class="impact-featured-btn">
                    View Story
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════ COMPLETED CAMPAIGNS GRID ═══════════════════ --}}
    <div class="impact-grid-wrap">
        <div class="impact-grid-header">
            <h2>Completed Campaigns</h2>
            @if($completedCampaigns->total() > $completedCampaigns->perPage())
            <span class="impact-grid-count">{{ $completedCampaigns->firstItem() }}-{{ $completedCampaigns->lastItem() }} of {{ $completedCampaigns->total() }}</span>
            @endif
        </div>

        @if($completedCampaigns->isEmpty())
        <div class="impact-empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            <h3>No completed campaigns yet</h3>
            <p>Impact stories will appear here as campaigns reach their goals.</p>
            <a href="{{ route('all.campaigns') }}" class="impact-empty-btn">Support Active Campaigns</a>
        </div>
        @else
        <div class="impact-grid">
            @foreach($completedCampaigns as $c)
            <a href="{{ route('campaign.public', ['category' => $c->category->slug ?? 'general', 'slug' => $c->slug]) }}" class="impact-card">
                <div class="impact-card-img">
                    @if($c->cover_image)
                    <img src="{{ asset('storage/'.$c->cover_image) }}" alt="{{ $c->title }}" loading="lazy">
                    @else
                    <div class="impact-card-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    </div>
                    @endif
                    @if($c->location)
                    <div class="impact-card-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $c->location }}
                    </div>
                    @endif
                </div>
                <div class="impact-card-body">
                    @if($c->category)
                    <div class="impact-card-tag">{{ $c->category->name }}</div>
                    @endif
                    <h3 class="impact-card-title">{{ $c->title }}</h3>
                    <p class="impact-card-desc">{{ Str::limit(strip_tags($c->description), 100) }}</p>
                    <div class="impact-card-progress">
                        <div class="impact-card-progress-bar" style="width:{{ min(100, round($c->raised_amount / max($c->goal_amount, 1) * 100)) }}%"></div>
                    </div>
                    <div class="impact-card-stats">
                        <span class="impact-card-raised">₹{{ number_format($c->raised_amount) }}</span>
                        <span class="impact-card-goal">of ₹{{ number_format($c->goal_amount) }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @if($completedCampaigns->hasPages())
        <div class="impact-pagination">
            {{ $completedCampaigns->links() }}
        </div>
        @endif
        @endif
    </div>

    {{-- ═══════════════════ CTA ═══════════════════ --}}
    <div class="impact-cta">
        <div class="impact-cta-inner">
            <h2>Want to Create Your Own Impact?</h2>
            <p>Start a campaign and join the community of changemakers.</p>
            <div class="impact-cta-actions">
                <a href="{{ route('campaign.create') }}" class="impact-cta-btn">Start a Campaign</a>
                <a href="{{ route('all.campaigns') }}" class="impact-cta-btn-ghost">Support a Campaign</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.impact-page{--font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;--accent:#6366f1;--accent2:#8b5cf6;}

/* ── Hero ── */
.impact-hero{position:relative;overflow:hidden;background:linear-gradient(160deg,#0d0e1a,#0f172a 50%,#042f2e);padding:100px 24px 120px;text-align:center;}
.impact-hero-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(99,102,241,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,0.04) 1px,transparent 1px);background-size:36px 36px;pointer-events:none;}
.impact-hero-inner{position:relative;z-index:1;max-width:640px;margin:0 auto;}
.impact-hero-eyebrow{display:inline-flex;align-items:center;gap:6px;font-family:var(--mono);font-size:11px;font-weight:600;color:var(--accent);letter-spacing:.12em;text-transform:uppercase;background:rgba(99,102,241,.12);padding:6px 14px;border-radius:100px;margin-bottom:20px;}
.impact-hero-inner h1{font-family:var(--mono);font-size:clamp(30px,5vw,48px);font-weight:500;color:#fff;letter-spacing:-0.03em;line-height:1.2;}
.impact-hero-inner h1 em{color:var(--accent);font-style:normal;}
.impact-hero-inner p{font-size:15px;color:rgba(255,255,255,.5);line-height:1.7;margin-top:14px;}

/* ── Stats ── */
.impact-stats{background:#0d0e1a;padding:0 20px 48px;}
.impact-stats-inner{display:flex;align-items:center;justify-content:center;gap:0;max-width:1100px;margin:0 auto;background:rgba(255,255,255,.06);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:24px 12px;flex-wrap:wrap;}
.impact-stat{text-align:center;padding:8px 20px;min-width:140px;flex:1;}
.impact-stat-num{display:block;font-family:var(--mono);font-size:clamp(16px,2vw,22px);font-weight:700;color:#fff;letter-spacing:-0.02em;}
.impact-stat-label{display:block;font-size:11px;color:rgba(255,255,255,.45);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;margin-top:4px;}
.impact-stat-divider{width:1px;height:32px;background:rgba(255,255,255,.08);flex-shrink:0;}
@media(max-width:720px){.impact-stats-inner{flex-direction:column;padding:16px;gap:8px;}.impact-stat-divider{display:none;}.impact-stat{padding:8px 12px;}}

/* ── Featured ── */
.impact-featured-wrap{max-width:1100px;margin:48px auto 0;padding:0 20px;}
.impact-featured{display:flex;border-radius:20px;overflow:hidden;background:#fff;border:1px solid rgba(0,0,0,.06);box-shadow:0 4px 24px rgba(0,0,0,.04);}
.impact-featured-img{width:45%;min-height:300px;position:relative;overflow:hidden;flex-shrink:0;}
.impact-featured-img img{width:100%;height:100%;object-fit:cover;position:absolute;inset:0;}
.impact-featured-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f3f4f6,#e5e7eb);color:#9ca3af;}
.impact-featured-placeholder svg{width:48px;height:48px;}
.impact-featured-badge{position:absolute;top:14px;left:14px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;padding:6px 14px;border-radius:100px;}
.impact-featured-body{flex:1;padding:32px;display:flex;flex-direction:column;justify-content:center;}
.impact-featured-category{font-size:11px;font-weight:700;color:var(--accent);font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;}
.impact-featured-body h2{font-size:clamp(18px,2.5vw,26px);font-weight:700;color:#0f1117;letter-spacing:-0.02em;margin-bottom:8px;}
.impact-featured-body p{font-size:14px;color:#6b7280;line-height:1.7;margin-bottom:16px;}
.impact-featured-meta{margin-bottom:20px;}
.impact-featured-progress{height:6px;border-radius:100px;background:#e5e7eb;overflow:hidden;margin-bottom:8px;}
.impact-featured-progress-bar{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--accent),var(--accent2));}
.impact-featured-stats{display:flex;gap:16px;font-size:12px;color:#9ca3af;font-family:var(--mono);}
.impact-featured-stats strong{color:#0f1117;font-weight:700;}
.impact-featured-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:var(--accent);color:#fff;border-radius:100px;font-family:var(--mono);font-size:13px;font-weight:600;text-decoration:none;transition:opacity .2s;align-self:flex-start;}
.impact-featured-btn svg{width:16px;height:16px;}
.impact-featured-btn:hover{opacity:.85;}
@media(max-width:720px){.impact-featured{flex-direction:column;}.impact-featured-img{width:100%;min-height:200px;position:relative;}}
@media(max-width:520px){.impact-hero{padding:70px 16px 90px;}.impact-featured-body{padding:20px;}}

/* ── Grid ── */
.impact-grid-wrap{max-width:1100px;margin:48px auto 0;padding:0 20px 60px;}
.impact-grid-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.impact-grid-header h2{font-size:20px;font-weight:700;color:#0f1117;letter-spacing:-0.02em;}
.impact-grid-count{font-size:12px;color:#9ca3af;font-family:var(--mono);}

.impact-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
@media(max-width:1024px){.impact-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:600px){.impact-grid{grid-template-columns:1fr;}}

.impact-card{display:flex;flex-direction:column;border-radius:16px;overflow:hidden;background:#fff;border:1px solid rgba(0,0,0,.06);text-decoration:none;transition:transform .25s,box-shadow .25s;}
.impact-card:hover{transform:translateY(-4px);box-shadow:0 8px 32px rgba(99,102,241,.12);}
.impact-card-img{height:180px;position:relative;overflow:hidden;background:#f3f4f6;}
.impact-card-img img{width:100%;height:100%;object-fit:cover;transition:transform .4s;}
.impact-card:hover .impact-card-img img{transform:scale(1.06);}
.impact-card-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#9ca3af;}
.impact-card-placeholder svg{width:36px;height:36px;opacity:.4;}
.impact-card-location{position:absolute;bottom:10px;left:10px;display:flex;align-items:center;gap:4px;background:rgba(0,0,0,.65);backdrop-filter:blur(6px);color:#fff;font-size:10px;font-family:var(--mono);padding:4px 10px;border-radius:100px;}
.impact-card-location svg{width:12px;height:12px;}
.impact-card-body{padding:16px 18px 18px;flex:1;display:flex;flex-direction:column;}
.impact-card-tag{font-size:10px;font-weight:700;color:var(--accent);font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;}
.impact-card-title{font-size:15px;font-weight:700;color:#0f1117;letter-spacing:-0.01em;margin-bottom:6px;line-height:1.4;}
.impact-card-desc{font-size:12px;color:#6b7280;line-height:1.6;margin-bottom:auto;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.impact-card-progress{height:4px;border-radius:100px;background:#e5e7eb;overflow:hidden;margin:12px 0 8px;}
.impact-card-progress-bar{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--accent),var(--accent2));}
.impact-card-stats{display:flex;align-items:baseline;gap:6px;font-family:var(--mono);}
.impact-card-raised{font-size:14px;font-weight:700;color:#0f1117;}
.impact-card-goal{font-size:11px;color:#9ca3af;}

.impact-empty{text-align:center;padding:80px 20px;}
.impact-empty svg{width:48px;height:48px;color:#9ca3af;opacity:.25;}
.impact-empty h3{font-size:18px;font-weight:700;color:#1a1a2e;margin:14px 0 6px;}
.impact-empty p{font-size:13px;color:#6b7280;margin-bottom:20px;}
.impact-empty-btn{display:inline-flex;padding:12px 28px;background:var(--accent);color:#fff;border-radius:100px;font-family:var(--mono);font-size:13px;font-weight:600;text-decoration:none;transition:opacity .2s;}
.impact-empty-btn:hover{opacity:.85;}

.impact-pagination{margin-top:32px;display:flex;flex-direction:column;align-items:center;gap:12px;}
.impact-pagination .pagination{display:flex;gap:6px;list-style:none;padding:0;margin:0;}
.impact-pagination .page-link{display:flex;align-items:center;justify-content:center;min-width:36px;height:36px;border-radius:10px;border:1px solid #e5e7eb;font-size:13px;font-family:var(--mono);color:#6b7280;text-decoration:none;background:#fff;transition:all .2s;padding:0 10px;}
.impact-pagination .page-link:hover{border-color:var(--accent);color:var(--accent);}
.impact-pagination .active .page-link{background:var(--accent);border-color:var(--accent);color:#fff;}
.impact-pagination .disabled .page-link{opacity:.4;pointer-events:none;}
.impact-pagination .pagination-info{font-size:12px;color:#9ca3af;font-family:var(--mono);margin:0;}
.impact-pagination .pagination-info strong{font-weight:700;color:#6b7280;}

/* ── CTA ── */
.impact-cta{background:linear-gradient(160deg,#0d0e1a,#0f172a 50%,#042f2e);padding:64px 24px;text-align:center;}
.impact-cta-inner{max-width:520px;margin:0 auto;}
.impact-cta-inner h2{font-family:var(--mono);font-size:clamp(20px,3vw,28px);font-weight:500;color:#fff;letter-spacing:-0.02em;}
.impact-cta-inner p{font-size:14px;color:rgba(255,255,255,.5);margin:10px 0 24px;}
.impact-cta-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;}
.impact-cta-btn{padding:12px 28px;background:var(--accent);color:#fff;border-radius:100px;font-family:var(--mono);font-size:13px;font-weight:600;text-decoration:none;transition:opacity .2s;}
.impact-cta-btn:hover{opacity:.85;}
.impact-cta-btn-ghost{padding:12px 28px;border:1px solid rgba(255,255,255,.2);color:rgba(255,255,255,.8);border-radius:100px;font-family:var(--mono);font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;}
.impact-cta-btn-ghost:hover{border-color:var(--accent);color:#fff;}
</style>
@endpush
