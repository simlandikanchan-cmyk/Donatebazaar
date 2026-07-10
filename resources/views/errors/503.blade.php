@extends('layouts.app')

@push('styles')
<style>
:root {
    --ink:         #1a1a2e;
    --ink2:        #4a4870;
    --ink3:        #9896c0;
    --bg:          #f0f2f8;
    --surface:     #ffffff;
    --border:      rgba(37,99,235,0.14);
    --font-mono:   'DM Mono', monospace;
    --font:        'DM Sans', sans-serif;
    --dark-bg:     linear-gradient(160deg, #0d0e1a 0%, #0f172a 50%, #042f2e 100%);
    --dark-ring:   rgba(37,99,235,0.11);
}

#error-root {
    margin-left:  calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
    width: 100vw;
    overflow-x: hidden;
}

.e-hero {
    position: relative;
    overflow: hidden;
    background: var(--dark-bg);
    padding: 120px 24px 100px;
    text-align: center;
    width: 100%;
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.e-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(37,99,235,0.045) 1px, transparent 1px),
        linear-gradient(90deg, rgba(37,99,235,0.045) 1px, transparent 1px);
    background-size: 36px 36px;
    pointer-events: none;
    z-index: 0;
}

.e-glows {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 55% 60% at 15% 50%, rgba(37,99,235,0.26) 0%, transparent 65%),
        radial-gradient(ellipse 45% 55% at 85% 30%, rgba(13,148,136,0.20) 0%, transparent 65%);
    pointer-events: none;
    z-index: 0;
}

.e-ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid var(--dark-ring);
    pointer-events: none;
    z-index: 1;
}
.e-ring-1 { width: 500px; height: 500px; top: -200px; right: -180px; }
.e-ring-2 { width: 340px; height: 340px; top: -120px; right: -80px; border-color: rgba(37,99,235,0.07); }
.e-ring-3 { width: 400px; height: 400px; bottom: -160px; left: -150px; border-color: rgba(13,148,136,0.07); }

.e-inner {
    position: relative;
    z-index: 3;
    max-width: 560px;
}

.e-icon {
    margin-bottom: 16px;
}
.e-icon svg {
    width: 56px; height: 56px;
    stroke: var(--accent);
    fill: none;
    stroke-width: 1.5;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.e-sub {
    font-family: var(--font-mono);
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--accent);
    margin-bottom: 14px;
}

.e-title {
    font-family: var(--font-mono);
    font-size: clamp(22px, 3vw, 30px);
    font-weight: 500;
    color: #fff;
    line-height: 1.3;
    letter-spacing: -0.02em;
    margin-bottom: 12px;
}

.e-desc {
    font-family: var(--font);
    font-size: 15px;
    color: rgba(255,255,255,0.50);
    line-height: 1.65;
    margin-bottom: 8px;
}

.e-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 28px;
}

.btn-home {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    border-radius: 12px;
    font-family: var(--font-mono);
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.02em;
    text-decoration: none;
    transition: all 0.25s;
    cursor: pointer;
    border: none;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff;
}
.btn-home:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(37,99,235,0.35);
}

@media (max-width: 520px) {
    .e-hero { padding: 100px 20px 80px; min-height: 60vh; }
    .e-actions { flex-direction: column; width: 100%; }
    .btn-home { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')
<div id="error-root">
    <div class="e-hero">
        <div class="e-ring e-ring-1"></div>
        <div class="e-ring e-ring-2"></div>
        <div class="e-ring e-ring-3"></div>
        <div class="e-glows"></div>
        <div class="e-fade"></div>

        <div class="e-inner">
            <div class="e-icon">
                <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            </div>
            <div class="e-sub">We'll Be Back Soon</div>
            <h1 class="e-title">Scheduled Maintenance</h1>
            <p class="e-desc">We're making things better. The site is temporarily down for scheduled maintenance.</p>
            <p class="e-desc">Please check back in a little while.</p>
            <div class="e-actions">
                <a href="/" class="btn-home">
                    <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;"><path d="M3 12l9-9 9 9"/><path d="M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/></svg>
                    Try Again
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
