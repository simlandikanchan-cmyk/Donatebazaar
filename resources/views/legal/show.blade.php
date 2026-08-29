@extends('layouts.app')

@section('title', $page->title)
@section('meta_description', 'Read our ' . strtolower($page->title) . ' for DonateBazaar.')

@section('content')
<div class="legal-page">
    {{-- Hero Section --}}
    <div class="legal-hero">
        <div class="legal-hero-pattern"></div>
        <div class="legal-hero-inner">
            <div class="legal-hero-badge">Legal</div>
            <h1>{{ $page->title }}</h1>
            <p class="legal-hero-meta">
                <span class="legal-hero-date">Last updated: {{ $page->updated_at?->format('F d, Y') ?? date('F d, Y') }}</span>
                <span class="legal-hero-dot">•</span>
                <span class="legal-hero-read">5 min read</span>
            </p>
        </div>
    </div>

    {{-- Main Content Area --}}
    <div class="legal-container">
        {{-- Sidebar Navigation --}}
        <aside class="legal-sidebar">
            <div class="legal-sidebar-sticky">
                <nav class="legal-nav">
                    <div class="legal-nav-title">On this page</div>
                    <div class="legal-nav-links" id="legalNavLinks">
                        {{-- Populated by JavaScript --}}
                    </div>
                </nav>

                <div class="legal-sidebar-help">
                    <div class="legal-help-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div class="legal-help-text">
                        <strong>Have questions?</strong>
                        <span>Contact our support team</span>
                    </div>
                    <a href="{{ route('faq') }}" class="legal-help-btn">Get Help</a>
                </div>
            </div>
        </aside>

        {{-- Content --}}
        <main class="legal-content" id="legalContent">
            {!! $page->content !!}
        </main>
    </div>

    {{-- Footer CTA --}}
    <div class="legal-footer">
        <div class="legal-footer-inner">
            <h3>Ready to make a difference?</h3>
            <p>Join thousands of donors supporting causes across India.</p>
            <div class="legal-footer-actions">
                <a href="{{ route('campaign.create') }}" class="legal-footer-btn primary">
                    Start a Campaign
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('home') }}" class="legal-footer-btn secondary">Browse Campaigns</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('legalContent');
    const navLinks = document.getElementById('legalNavLinks');
    const headings = content.querySelectorAll('h2, h3');

    if (headings.length === 0) {
        navLinks.innerHTML = '<span class="legal-nav-empty">No sections available</span>';
        return;
    }

    headings.forEach((heading, index) => {
        const id = 'section-' + index;
        heading.id = id;

        const link = document.createElement('a');
        link.href = '#' + id;
        link.className = 'legal-nav-link';
        link.dataset.target = id;

        if (heading.tagName === 'H2') {
            link.classList.add('legal-nav-link-h2');
        } else {
            link.classList.add('legal-nav-link-h3');
        }

        link.textContent = heading.textContent;
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.getElementById(this.dataset.target);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });

        navLinks.appendChild(link);
    });

    // Active section highlighting on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                document.querySelectorAll('.legal-nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                const activeLink = document.querySelector(`[data-target="${entry.target.id}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }
        });
    }, { rootMargin: '-20% 0px -70% 0px' });

    headings.forEach(heading => observer.observe(heading));
});
</script>
@endpush

@push('styles') @vite(['resources/css/public/legal.css']) @endpush
