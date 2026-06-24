@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap');

:root {
    --accent:      #7c6dfa;
    --accent2:     #9b59f5;
    --accent-soft: rgba(124,109,250,0.12);
    --accent-border: rgba(124,109,250,0.22);
    --green:       #7effc4;
    --green-soft:  rgba(126,255,196,0.10);
    --green-border:rgba(126,255,196,0.20);
    --ink:         #1a1a2e;
    --ink2:        #4a4870;
    --ink3:        #9896c0;
    --bg:          #f0f2f8;
    --surface:     #ffffff;
    --border:      rgba(124,109,250,0.14);
    --border-hover:rgba(124,109,250,0.30);
    --radius:      18px;
    --radius-sm:   12px;
    --font-mono:   'DM Mono', monospace;
    --font:        'DM Sans', sans-serif;
    --dark-bg:     linear-gradient(160deg, #0d0e1a 0%, #13122b 50%, #1a1040 100%);
    --dark-ring:   rgba(124,109,250,0.11);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: var(--font);
    background: var(--bg);
    color: var(--ink);
    -webkit-font-smoothing: antialiased;
}

/* ════════════════════════════════════════
   PAGE WRAPPER — ensures hero is full-width
   even inside layout containers
════════════════════════════════════════ */
#contact-page-root {
    /* Break out of any layout padding/max-width */
    margin-left:  calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
    width: 100vw;
    overflow-x: hidden;
}

/* ════════════════════════════════════════
   HERO — dark panel
════════════════════════════════════════ */
.c-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(160deg, #0d0e1a 0%, #13122b 50%, #1a1040 100%);
    padding: 88px 24px 76px;
    text-align: center;
    width: 100%;
}

/* grid overlay */
.c-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(124,109,250,0.045) 1px, transparent 1px),
        linear-gradient(90deg, rgba(124,109,250,0.045) 1px, transparent 1px);
    background-size: 36px 36px;
    pointer-events: none;
    z-index: 0;
}

/* radial glows */
.c-hero-glows {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 55% 60% at 15% 50%, rgba(124,109,250,0.26) 0%, transparent 65%),
        radial-gradient(ellipse 45% 55% at 85% 30%, rgba(155,89,245,0.20) 0%, transparent 65%);
    pointer-events: none;
    z-index: 0;
}

/* fade into page bg */
.hero-fade {
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 64px;
    background: linear-gradient(to top, var(--bg), transparent);
    pointer-events: none;
    z-index: 2;
}

/* decorative rings */
.hero-ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid var(--dark-ring);
    pointer-events: none;
    z-index: 1;
}
.hero-ring-1 { width: 440px; height: 440px; top: -140px; right: -120px; }
.hero-ring-2 { width: 290px; height: 290px; top:  -70px; right:  -50px; border-color: rgba(124,109,250,0.07); }
.hero-ring-3 { width: 360px; height: 360px; bottom: -130px; left: -120px; border-color: rgba(155,89,245,0.07); }

.hero-inner {
    position: relative;
    z-index: 3;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(124,109,250,0.14);
    border: 1px solid rgba(124,109,250,0.28);
    border-radius: 50px;
    padding: 5px 16px;
    font-family: var(--font-mono);
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 0.10em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.80);
    margin-bottom: 22px;
}
.hero-badge-dot {
    width: 6px; height: 6px;
    background: var(--green);
    border-radius: 50%;
    animation: blink 2s ease-in-out infinite;
}
@keyframes blink {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.30; }
}

.c-hero h1 {
    font-family: var(--font-mono);
    font-size: clamp(34px, 5.5vw, 54px);
    font-weight: 500;
    color: #fff;
    line-height: 1.18;
    letter-spacing: -0.03em;
    margin-bottom: 14px;
}
.c-hero h1 .dim { color: rgba(255,255,255,0.42); }

.c-hero p {
    font-family: var(--font);
    color: rgba(255,255,255,0.50);
    font-size: 15px;
    font-weight: 400;
    line-height: 1.6;
}

/* ════════════════════════════════════════
   LAYOUT WRAPPER
════════════════════════════════════════ */
.c-wrap {
    max-width: 1120px;
    margin: 0 auto;
    padding: 52px 24px 88px;
}

/* ════════════════════════════════════════
   TRUST BAR
════════════════════════════════════════ */
.trust-bar {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 48px;
}

.trust-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 22px 20px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    position: relative;
    overflow: hidden;
}
.trust-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--accent), var(--accent2));
    opacity: 0;
    transition: opacity 0.2s;
}
.trust-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 32px rgba(124,109,250,0.14);
    border-color: var(--border-hover);
}
.trust-card:hover::before { opacity: 1; }

.trust-icon-wrap {
    width: 48px; height: 48px;
    border-radius: 13px;
    background: var(--accent-soft);
    border: 1px solid var(--accent-border);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}


 .trust-icon-wrap svg {
    width: 20px; height: 20px;
    stroke: var(--accent);
    fill: none;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
 }



 .trust-num {
    font-family: var(--font-mono);
    font-size: 26px;
    font-weight: 500;
    color: var(--ink);
    letter-spacing: -0.03em;
    line-height: 1;
    margin-bottom: 3px;
    text-align: left;
}
.trust-num span {
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.trust-label {
    font-size: 12.5px;
    color: var(--ink3);
    font-weight: 500;
}

/* ════════════════════════════════════════
   MAIN GRID
════════════════════════════════════════ */
.c-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 22px;
    align-items: start;
}

/* ════════════════════════════════════════
   CARDS — light surface
════════════════════════════════════════ */
.c-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: 0 4px 28px rgba(124,109,250,0.08);
    overflow: hidden;
    transition: box-shadow 0.2s;
}
.c-card:hover { box-shadow: 0 8px 40px rgba(124,109,250,0.14); }

.card-top-bar {
    height: 3px;
    background: linear-gradient(90deg, var(--accent) 0%, var(--accent2) 60%, #c084fc 100%);
}

.c-card-body { padding: 30px 28px 28px; }

.c-card-title {
    font-family: var(--font-mono);
    font-size: 17px;
    font-weight: 500;
    letter-spacing: -0.02em;
    color: var(--ink);
    margin-bottom: 26px;
}

/* ── Contact info rows ── */
.info-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid var(--border);
    transition: transform 0.2s;
    cursor: default;
}
.info-row:last-of-type { border-bottom: none; }
.info-row:hover { transform: translateX(5px); }

.info-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    background: var(--accent-soft);
    border: 1px solid var(--accent-border);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: background 0.2s;
}
.info-row:hover .info-icon { background: rgba(124,109,250,0.2); }
.info-icon svg {
    width: 16px; height: 16px;
    stroke: var(--accent);
    fill: none;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}
.info-label {
    font-family: var(--font-mono);
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.09em;
    color: var(--ink3);
    margin-bottom: 3px;
}
.info-val {
    font-family: var(--font);
    font-size: 14px;
    color: var(--ink);
    font-weight: 500;
}

.verified-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: var(--green-soft);
    border: 1px solid var(--green-border);
    border-radius: 99px;
    padding: 2px 8px;
    font-family: var(--font-mono);
    font-size: 9.5px;
    font-weight: 500;
    color: #2dd4a4;
    letter-spacing: 0.05em;
    margin-left: auto;
    flex-shrink: 0;
}
.verified-badge::before {
    content: '';
    width: 5px; height: 5px;
    border-radius: 50%;
    background: var(--green);
    animation: blink 2s ease-in-out infinite;
}

/* ── Social strip ── */
.social-strip {
    display: flex;
    gap: 8px;
    margin-top: 22px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}
.soc {
    width: 38px; height: 38px;
    border-radius: 10px;
    border: 1px solid var(--accent-border);
    background: var(--accent-soft);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: background 0.2s, border-color 0.2s, transform 0.2s;
    text-decoration: none;
}
.soc:hover {
    background: var(--accent);
    border-color: var(--accent);
    transform: translateY(-2px);
}
.soc svg { width: 14px; height: 14px; fill: var(--ink2); transition: fill 0.2s; }
.soc:hover svg { fill: #fff; }

.security-note {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--green-soft);
    border: 1px solid var(--green-border);
    border-radius: 10px;
    padding: 10px 14px;
    margin-top: 20px;
    font-size: 12px;
    color: #2dd4a4;
    font-weight: 500;
}
.security-note svg {
    width: 13px; height: 13px;
    stroke: #2dd4a4;
    fill: none;
    stroke-width: 2.2;
    stroke-linecap: round;
    stroke-linejoin: round;
    flex-shrink: 0;
}

/* ── Form fields ── */
.field-group { margin-bottom: 16px; }
.field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 13px;
}

.field-label {
    display: block;
    font-family: var(--font);
    font-size: 12.5px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 7px;
}
.field-label .req { color: var(--accent); margin-left: 2px; }

.field {
    width: 100%;
    padding: 12px 14px;
    border-radius: var(--radius-sm);
    border: 1.5px solid rgba(0,0,0,0.09);
    background: #f9fafb;
    font-family: var(--font);
    font-size: 13.5px;
    color: var(--ink);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}
.field::placeholder { color: #c4c9d4; font-size: 13px; }
.field:focus {
    border-color: var(--accent);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(124,109,250,0.12);
}
textarea.field {
    resize: vertical;
    min-height: 116px;
    line-height: 1.65;
}

/* ── Submit button ── */
.btn-send {
    width: 100%;
    height: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: var(--font-mono);
    font-size: 13.5px;
    font-weight: 500;
    color: white;
    background: linear-gradient(135deg, #6c5ff5 0%, #9b59f5 100%);
    border: none;
    border-radius: 11px;
    cursor: pointer;
    letter-spacing: 0.01em;
    box-shadow: 0 4px 22px rgba(124,109,250,0.38);
    transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
    margin-top: 4px;
}
.btn-send:hover {
    opacity: 0.91;
    transform: translateY(-1px);
    box-shadow: 0 8px 30px rgba(124,109,250,0.50);
}
.btn-send:active { transform: translateY(0); }
.btn-send svg {
    width: 15px; height: 15px;
    stroke: #fff; fill: none;
    stroke-width: 2.2;
    stroke-linecap: round; stroke-linejoin: round;
    flex-shrink: 0;
}

.form-footer-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 13px;
    font-size: 12px;
    color: var(--ink3);
}
.form-footer-note svg {
    width: 12px; height: 12px;
    stroke: var(--ink3);
    fill: none;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
    flex-shrink: 0;
}

/* ── Alerts ── */
.alert {
    padding: 12px 14px;
    border-radius: var(--radius-sm);
    font-size: 13.5px;
    margin-bottom: 18px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-family: var(--font);
}
.alert-success {
    background: var(--green-soft);
    border: 1px solid var(--green-border);
    color: #2dd4a4;
    font-weight: 500;
}
.alert-error {
    background: rgba(239,68,68,0.06);
    border: 1px solid rgba(239,68,68,0.20);
    color: #dc2626;
}
.alert svg {
    width: 15px; height: 15px;
    fill: currentColor;
    flex-shrink: 0;
    margin-top: 1px;
}

/* ════════════════════════════════════════
   FAQ SECTION
════════════════════════════════════════ */
.faq-section { margin-top: 72px; }

.section-header {
    text-align: center;
    margin-bottom: 40px;
}
.section-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--accent-soft);
    border: 1px solid var(--accent-border);
    border-radius: 99px;
    padding: 5px 14px;
    font-family: var(--font-mono);
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 0.09em;
    text-transform: uppercase;
    color: var(--accent);
    margin-bottom: 14px;
}
.section-tag::before {
    content: '';
    width: 5px; height: 5px;
    border-radius: 50%;
    background: var(--accent);
    animation: blink 2s ease-in-out infinite;
}
.section-header h2 {
    font-family: var(--font-mono);
    font-size: clamp(26px, 3.5vw, 36px);
    font-weight: 500;
    color: var(--ink);
    letter-spacing: -0.03em;
    margin-bottom: 10px;
}
.section-header h2 .dim { color: var(--ink3); }
.section-header p {
    font-size: 14px;
    color: var(--ink3);
    line-height: 1.65;
}

.faq-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    max-width: 920px;
    margin: 0 auto;
}

.faq-item {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    overflow: hidden;
    transition: box-shadow 0.2s, border-color 0.2s;
}
.faq-item:hover {
    box-shadow: 0 4px 22px rgba(124,109,250,0.11);
    border-color: var(--border-hover);
}
.faq-item.open {
    border-color: rgba(124,109,250,0.32);
    box-shadow: 0 4px 22px rgba(124,109,250,0.13);
}

.faq-q {
    width: 100%;
    background: none;
    border: none;
    padding: 17px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    cursor: pointer;
    font-family: var(--font);
    font-size: 13.5px;
    font-weight: 600;
    color: var(--ink);
    text-align: left;
    transition: background 0.15s;
}
.faq-q:hover { background: #f9f8ff; }

.faq-icon {
    width: 26px; height: 26px;
    border-radius: 8px;
    background: var(--accent-soft);
    border: 1px solid var(--accent-border);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: background 0.2s, border-color 0.2s, transform 0.3s;
}
.faq-icon svg {
    width: 10px; height: 10px;
    stroke: var(--accent);
    fill: none;
    stroke-width: 2.5;
    stroke-linecap: round;
    transition: transform 0.3s;
}
.faq-item.open .faq-icon {
    background: var(--accent);
    border-color: var(--accent);
}
.faq-item.open .faq-icon svg {
    stroke: #fff;
    transform: rotate(45deg);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.35s cubic-bezier(0.4,0,0.2,1);
}
.faq-answer-inner {
    padding: 14px 18px 18px;
    font-size: 13.5px;
    color: var(--ink2);
    line-height: 1.68;
    border-top: 1px solid var(--border);
}
.faq-item.open .faq-answer { max-height: 220px; }

/* ════════════════════════════════════════
   MAP STRIP
════════════════════════════════════════ */
.map-strip {
    margin-top: 64px;
    border-radius: var(--radius);
    overflow: hidden;
    border: 1px solid var(--border);
    box-shadow: 0 4px 28px rgba(124,109,250,0.08);
    position: relative;
}
.map-header {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.map-header-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--green);
    animation: blink 2s ease-in-out infinite;
}
.map-header-text {
    font-family: var(--font-mono);
    font-size: 11.5px;
    font-weight: 500;
    color: var(--ink2);
    letter-spacing: 0.04em;
}
.map-header-badge {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--accent-soft);
    border: 1px solid var(--accent-border);
    border-radius: 99px;
    padding: 3px 10px;
    font-family: var(--font-mono);
    font-size: 10px;
    color: var(--accent);
    font-weight: 500;
    letter-spacing: 0.06em;
}
.map-strip iframe {
    width: 100%;
    height: 280px;
    display: block;
    border: none;
    filter: grayscale(15%) hue-rotate(220deg) saturate(0.7);
}

/* ════════════════════════════════════════
   ANIMATIONS
════════════════════════════════════════ */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(22px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim { opacity: 0; animation: fadeUp 0.6s ease forwards; }
.anim-1 { animation-delay: 0.08s; }
.anim-2 { animation-delay: 0.18s; }
.anim-3 { animation-delay: 0.30s; }
.anim-4 { animation-delay: 0.42s; }
.anim-5 { animation-delay: 0.54s; }

/* ════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════ */
@media (max-width: 860px) {
    .c-grid { grid-template-columns: 1fr; }
    .trust-bar { grid-template-columns: 1fr; gap: 10px; }
    .faq-grid { grid-template-columns: 1fr; }
}
@media (max-width: 520px) {
    .field-row { grid-template-columns: 1fr; }
    .c-card-body { padding: 22px 18px; }
    .c-hero { padding: 64px 20px 56px; }
}
</style>

<div id="contact-page-root">

    <!-- ── HERO ── -->
    <div class="c-hero">
        <div class="hero-ring hero-ring-1"></div>
        <div class="hero-ring hero-ring-2"></div>
        <div class="hero-ring hero-ring-3"></div>
        <div class="c-hero-glows"></div>
        <div class="hero-fade"></div>

        <div class="hero-inner">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                Support Center
            </div>
            <h1>Let's Talk<br><span class="dim">Together</span></h1>
            <p>We're here to help 24×7 — reach out anytime</p>
        </div>
    </div>

    <!-- ── MAIN CONTENT ── -->
    <div class="c-wrap">

        <!-- TRUST BAR -->
        <div class="trust-bar anim anim-1">
            <div class="trust-card">
                <div class="trust-icon-wrap">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <div class="trust-num"><span>24×7</span></div>
                    <div class="trust-label">Support Available</div>
                </div>
            </div>
            <div class="trust-card">
                <div class="trust-icon-wrap">
                    <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <div class="trust-num"><span>98%</span></div>
                    <div class="trust-label">Satisfaction Rate</div>
                </div>
            </div>
            <div class="trust-card">
                <div class="trust-icon-wrap">
                    <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <div>
                    <div class="trust-num"><span>&lt;2h</span></div>
                    <div class="trust-label">Avg. Response Time</div>
                </div>
            </div>
        </div>

        <!-- GRID -->
        <div class="c-grid">

            <!-- LEFT: INFO CARD -->
            <div class="c-card anim anim-2">
                <div class="card-top-bar"></div>
                <div class="c-card-body">
                    <h2 class="c-card-title">Contact Info</h2>

                    <div class="info-row">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
                        </div>
                        <div>
                            <div class="info-label">Email</div>
                            <div class="info-val">info@donatebazar.com</div>
                        </div>
                        <div class="verified-badge">Verified</div>
                    </div>

                    <div class="info-row">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.82 19.79 19.79 0 010 1.18 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92v2z"/></svg>
                        </div>
                        <div>
                            <div class="info-label">Phone</div>
                            <div class="info-val">+91 98765 43210</div>
                        </div>
                        <div class="verified-badge">Active</div>
                    </div>

                    <div class="info-row">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <div class="info-label">Address</div>
                            <div class="info-val">Ahmedabad, Gujarat, India</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <div class="info-label">Working Hours</div>
                            <div class="info-val">Mon – Sat, 9 am – 7 pm</div>
                        </div>
                    </div>

                    <!-- social -->
                    <div class="social-strip">
                        <a href="#" class="soc" title="Twitter / X">
                            <svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="soc" title="Instagram">
                            <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162S8.597 18.163 12 18.163s6.162-2.759 6.162-6.162S15.403 5.838 12 5.838zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="soc" title="Facebook">
                            <svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="soc" title="LinkedIn">
                            <svg viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>

                    <!-- trust note -->
                    <div class="security-note">
                        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Your data is encrypted and never shared with third parties
                    </div>
                </div>
            </div>

            <!-- RIGHT: FORM CARD -->
            <div class="c-card anim anim-3">
                <div class="card-top-bar"></div>
                <div class="c-card-body">
                    <h2 class="c-card-title">Send a Message</h2>

                    @if(session('success'))
                    <div class="alert alert-success">
                        <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('success') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-error">
                        <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <div>@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
                    </div>
                    @endif

                    <form method="POST" action="/contact">
                        @csrf

                        <div class="field-row">
                            <div class="field-group">
                                <label class="field-label" for="name">Full Name <span class="req">*</span></label>
                                <input id="name" name="name" type="text" class="field"
                                       placeholder="Enter Your Full Name"
                                       autocomplete="name"
                                       value="{{ old('name') }}" required>
                            </div>
                            <div class="field-group">
                                <label class="field-label" for="email">Email Address <span class="req">*</span></label>
                                <input id="email" name="email" type="email" class="field"
                                       placeholder="Enter Your Email Address"
                                       autocomplete="email"
                                       value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="subject">Subject</label>
                            <input id="subject" name="subject" type="text" class="field"
                                   placeholder="How can we help you?"
                                   autocomplete="off"
                                   value="{{ old('subject') }}">
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="message">Message <span class="req">*</span></label>
                            <textarea id="message" name="message" class="field"
                                      rows="5"
                                      autocomplete="off"
                                      placeholder="Write your message here…"
                                      required>{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="btn-send">
                            <svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Send Message
                        </button>

                        <p class="form-footer-note">
                            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            256-bit encrypted · We reply within 2 hours
                        </p>
                    </form>
                </div>
            </div>

        </div><!-- /c-grid -->

        <!-- FAQ -->
        <div class="faq-section anim anim-4">
            <div class="section-header">
                <div class="section-tag">FAQ</div>
                <h2>Frequently Asked <span class="dim">Questions</span></h2>
                <p>Everything you need to know about donating on DonateBazaar</p>
            </div>

            <div class="faq-grid">
                @foreach([
                    'How can I make a donation?' => 'You can donate securely via UPI, credit/debit cards, net banking, and other trusted payment gateways. Just visit any active campaign and click the Donate button.',
                    'Is my donation 100% secure?' => 'Absolutely. All transactions are end-to-end encrypted and processed through PCI-DSS compliant gateways. Your financial data is never stored on our servers.',
                    'Where does my money go?' => 'Every rupee goes directly to the verified campaign you choose. We publish transparent fund allocation reports accessible to all donors at any time.',
                    'Can I track my donation?' => 'Yes! Once logged in, your dashboard shows all donations, downloadable receipts, and real-time progress updates from the campaigns you\'ve supported.',
                    'Can I get a tax receipt?' => 'Yes, all donations qualify for 80G tax deductions. Your certificate is auto-generated and emailed to your registered address within minutes.',
                    'How do I contact support urgently?' => 'For urgent matters, call us at +91 98765 43210 or email info@donatebazar.com. Our team responds within 2 hours on business days.',
                ] as $question => $answer)
                <div class="faq-item">
                    <button class="faq-q" onclick="toggleFAQ(this)" type="button">
                        {{ $question }}
                        <span class="faq-icon">
                            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                        </span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">{{ $answer }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- MAP -->
        <div class="map-strip anim anim-5">
            <div class="map-header">
                <span class="map-header-dot"></span>
                <span class="map-header-text">Ahmedabad, Gujarat, India</span>
                <span class="map-header-badge">
                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Our Office
                </span>
            </div>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d235013.4554527093!2d72.41493582578125!3d23.020468899999993!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1700000000000"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                sandbox="allow-scripts allow-same-origin allow-popups allow-forms"
                crossorigin="anonymous"
                title="Office Location — Ahmedabad, Gujarat">
            </iframe>
        </div>

    </div><!-- /c-wrap -->

</div><!-- /contact-page-root -->

<script>
function toggleFAQ(btn) {
    const item = btn.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(el => el.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
}
</script>

@endsection