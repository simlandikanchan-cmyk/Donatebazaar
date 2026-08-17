@extends('layouts.app')

@section('title', 'Success Stories & Impact')
@section('meta_description', 'See the real impact of campaigns completed on DonateBazaar. Stories of change, powered by donors like you.')

@section('content')
<div class="impact-page">
    {{-- ═══════════════════ HERO ═══════════════════ --}}
    <div class="impact-hero">
        <div class="impact-hero-bg"></div>
        <div class="impact-hero-glow"></div>
        <div class="impact-hero-inner">
            <div class="impact-hero-eyebrow">Success Stories</div>
            <h1>Real Impact,<br><em>Real Stories</em></h1>
            <p>Every completed campaign represents lives changed, communities strengthened, and hope restored.</p>
            <div class="impact-hero-btns">
                <x-button variant="primary" href="{{ route('all.campaigns') }}">
                    Donate Now
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </x-button>
                <x-button variant="outline" href="{{ route('campaign.create') }}">
                    Start Fundraiser
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                </x-button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════ STATS BAND ═══════════════════ --}}
    <div class="impact-stats reveal">
        <div class="impact-stats-inner">
            <div class="impact-stat">
                <span class="impact-stat-num counter" data-target="{{ $totalRaised }}" data-prefix="₹" data-suffix="+">₹0+</span>
                <span class="impact-stat-label">Total Raised</span>
            </div>
            <div class="impact-stat-divider"></div>
            <div class="impact-stat">
                <span class="impact-stat-num counter" data-target="{{ $totalCampaigns }}">0</span>
                <span class="impact-stat-label">Campaigns Completed</span>
            </div>
            <div class="impact-stat-divider"></div>
            <div class="impact-stat">
                <span class="impact-stat-num counter" data-target="{{ $livesImpacted }}" data-suffix="+">0+</span>
                <span class="impact-stat-label">Lives Impacted</span>
            </div>
            <div class="impact-stat-divider"></div>
            <div class="impact-stat">
                <span class="impact-stat-num counter" data-target="{{ $totalDonors }}" data-suffix="+">0+</span>
                <span class="impact-stat-label">Donors</span>
            </div>
            <div class="impact-stat-divider"></div>
            <div class="impact-stat">
                <span class="impact-stat-num counter" data-target="{{ (int) $statesCovered }}" data-suffix="+">0+</span>
                <span class="impact-stat-label">States Reached</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════ FEATURED ═══════════════════ --}}
    @if($featured)
    <div class="impact-featured-wrap reveal">
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
                <a href="{{ route('campaign.public', ['category' => $featured->category->slug ?? 'general', 'slug' => $featured->slug]) }}" class="btn btn-accent">
                    View Story
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════ COMPLETED CAMPAIGNS GRID ═══════════════════ --}}
    <div class="impact-grid-wrap reveal">
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
            <x-button variant="primary" href="{{ route('all.campaigns') }}">Support Active Campaigns</x-button>
        </div>
        @else
        <div class="impact-grid">
            @foreach($completedCampaigns as $index => $c)
            <a href="{{ route('campaign.public', ['category' => $c->category->slug ?? 'general', 'slug' => $c->slug]) }}" class="impact-card" style="animation-delay:{{ $index * 0.06 }}s">
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
    <div class="impact-cta reveal">
        <div class="impact-cta-glow"></div>
        <div class="impact-cta-inner">
            <h2>Want to Create Your Own Impact?</h2>
            <p>Start a campaign and join the community of changemakers.</p>
            <div class="impact-cta-actions">
                <x-button variant="primary" href="{{ route('campaign.create') }}">Start a Campaign</x-button>
                <x-button variant="outline" href="{{ route('all.campaigns') }}">Support a Campaign</x-button>
            </div>
        </div>
    </div>
</div>

{{-- Scroll-to-top button --}}
<button class="impact-scroll-top" id="scrollTopBtn" aria-label="Scroll to top">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>
@endsection

@push('styles') @vite(['resources/css/public/impact.css']) @endpush

@push('scripts') @vite(['resources/js/public/impact.js']) @endpush
