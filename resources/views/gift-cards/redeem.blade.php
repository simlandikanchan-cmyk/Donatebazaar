@extends('layouts.user')

@section('page_title', 'Redeem Gift Card')
@section('page_subtitle', 'Turn your code into a donation')

@push('page_styles')
<style>
@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:none;}}

.gr-wrap{max-width:640px;width:100%;margin:0 auto;}

/* ── Hero ── */
.gr-hero{display:flex;align-items:center;gap:18px;background:linear-gradient(135deg,var(--accent-glow),rgba(124,58,237,.08) 60%,transparent);border:1px solid var(--border);border-radius:var(--radius);padding:24px 26px;margin-bottom:22px;position:relative;overflow:hidden;animation:fadeUp .45s both;}
.gr-hero::after{content:"";position:absolute;inset:0;background-image:radial-gradient(rgba(37,99,235,.07) 1px,transparent 1px);background-size:18px 18px;mask-image:linear-gradient(135deg,transparent 55%,#000);pointer-events:none;}
.gr-hero-icon{position:relative;z-index:1;width:54px;height:54px;border-radius:16px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 22px var(--accent-glow);flex-shrink:0;}
.gr-hero-icon svg{width:26px;height:26px;}
.gr-hero-body{position:relative;z-index:1;min-width:0;}
.gr-eyebrow{font-family:var(--font-mono);font-size:10px;font-weight:600;color:var(--accent);text-transform:uppercase;letter-spacing:.14em;margin-bottom:5px;display:block;}
.gr-hero-body h1{font-size:24px;font-weight:800;color:var(--text);letter-spacing:-0.02em;line-height:1.2;margin:0 0 6px;}
.gr-hero-body p{font-size:13px;color:var(--text3);line-height:1.6;margin:0;}
.gr-hero-badge{position:relative;z-index:1;margin-left:auto;flex-shrink:0;font-family:var(--font-mono);font-size:10px;font-weight:600;color:#15803d;background:rgba(22,163,74,.1);border:1px solid rgba(22,163,74,.25);padding:6px 11px;border-radius:100px;letter-spacing:.04em;white-space:nowrap;}

/* ── Stepper ── */
.gr-stepper{display:flex;align-items:flex-start;gap:0;margin-bottom:18px;animation:fadeUp .45s .05s both;}
.gr-step-node{flex:0 0 auto;display:flex;flex-direction:column;align-items:center;gap:7px;background:none;border:none;padding:6px 2px;cursor:default;font-family:var(--font);}
.gr-step-dot{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:var(--font-mono);font-size:12px;font-weight:700;color:var(--text3);background:var(--surface);border:1.5px solid var(--border2);transition:all var(--transition);}
.gr-step-node.done{cursor:pointer;}
.gr-step-node.done .gr-step-dot{background:var(--accent-lt);border-color:var(--accent);color:var(--accent);}
.gr-step-node.active .gr-step-dot{background:linear-gradient(135deg,var(--accent),var(--accent2));border-color:transparent;color:#fff;box-shadow:0 4px 14px var(--accent-glow);transform:scale(1.05);}
.gr-step-node.active .gr-step-label{color:var(--text);font-weight:700;}
.gr-step-node.done .gr-step-label{color:var(--accent);}
.gr-step-label{font-size:10.5px;font-weight:600;color:var(--text3);letter-spacing:.03em;text-transform:uppercase;white-space:nowrap;transition:color var(--transition);}
.gr-step-line{flex:1;height:2.5px;border-radius:99px;background:var(--border2);margin-top:20px;overflow:hidden;transition:background var(--transition);}
.gr-step-line.active{background:linear-gradient(90deg,var(--accent),var(--accent2));}

/* ── Progress bar ── */
.gr-progress{margin-bottom:22px;animation:fadeUp .45s .1s both;}
.gr-progress-bar{height:6px;border-radius:99px;background:var(--surface3);overflow:hidden;margin-bottom:9px;}
.gr-progress-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,var(--accent),var(--accent2));width:25%;transition:width .4s cubic-bezier(.65,0,.35,1);box-shadow:0 0 12px var(--accent-glow);}
.gr-progress-meta{display:flex;align-items:baseline;justify-content:space-between;gap:8px;}
.gr-progress-step{font-size:10.5px;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.08em;font-family:var(--font-mono);white-space:nowrap;}
.gr-progress-title{font-size:12px;color:var(--text3);font-weight:600;text-align:right;}

/* ── Wizard steps ── */
.gr-wizard{position:relative;}
.gr-step{display:none;}
.gr-step.slide-in-right{animation:slideInRight .32s cubic-bezier(.22,1,.36,1) both;}
.gr-step.slide-in-left{animation:slideInLeft .32s cubic-bezier(.22,1,.36,1) both;}
@keyframes slideInRight{from{opacity:0;transform:translateX(18px);}to{opacity:1;transform:translateX(0);}}
@keyframes slideInLeft{from{opacity:0;transform:translateX(-18px);}to{opacity:1;transform:translateX(0);}}
@media (prefers-reduced-motion:reduce){
    .gr-step.slide-in-right,.gr-step.slide-in-left{animation:none;}
    .gr-hero,.gr-stepper,.gr-progress{animation:none;}
}

.gr-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:22px;margin-bottom:16px;}
.gr-card-step{margin-bottom:18px;display:flex;align-items:center;gap:10px;}
.gr-card-step .s-num{width:26px;height:26px;border-radius:8px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-family:var(--font-mono);font-weight:700;box-shadow:0 4px 12px var(--accent-glow);flex-shrink:0;}
.gr-card-step .s-txt{font-family:var(--font-mono);font-size:11px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;}

/* ── Code input ── */
.gr-code-row{display:flex;gap:10px;}
.gr-code-input{flex:1;height:54px;border-radius:12px;border:1.5px solid var(--border2);padding:0 16px;font-size:17px;font-family:var(--font-mono);letter-spacing:.14em;text-transform:uppercase;text-align:center;outline:none;background:var(--surface2);color:var(--text);transition:border-color var(--transition),box-shadow var(--transition),background var(--transition);}
.gr-code-input::placeholder{color:var(--text3);letter-spacing:.05em;font-size:14px;text-transform:none;}
.gr-code-input:focus{border-color:var(--accent);background:var(--surface);box-shadow:0 0 0 4px var(--accent-glow);}
.gr-code-btn{height:54px;padding:0 24px;border-radius:12px;flex-shrink:0;}
.gr-code-btn:disabled{opacity:.55;}
.gr-code-hint{display:flex;align-items:center;gap:6px;font-size:11.5px;color:var(--text3);margin-top:10px;}
.gr-code-hint svg{width:13px;height:13px;flex-shrink:0;color:var(--accent);}

/* ── Status message ── */
.gr-status{display:none;margin-top:12px;padding:11px 13px;border-radius:11px;font-size:13px;line-height:1.5;align-items:center;gap:9px;}
.gr-status.show{display:flex;}
.gr-status.ok{background:rgba(22,163,74,.09);border:1px solid rgba(22,163,74,.22);color:#15803d;}
.gr-status.err{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.22);color:#b91c1c;}
.gr-status.info{background:var(--surface2);border:1px solid var(--border);color:var(--text2);}
.gr-status strong{font-weight:700;}
.gr-status svg{width:16px;height:16px;flex-shrink:0;}
.gr-spin{width:15px;height:15px;border-radius:50%;border:2px solid var(--border2);border-top-color:var(--accent);animation:spin .6s linear infinite;flex-shrink:0;}
@keyframes spin{to{transform:rotate(360deg);}}

/* ── How it works ── */
.hiw{border:1px dashed var(--border2);border-radius:var(--radius);padding:20px 16px;background:var(--surface);margin-top:6px;}
.hiw-title{font-family:var(--font-mono);font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;margin:0 0 18px;text-align:center;}
.hiw-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
.hiw-item{text-align:center;position:relative;}
.hiw-item + .hiw-item::before{content:"";position:absolute;left:-9px;top:22px;width:18px;height:1px;background:var(--border2);}
.hiw-icon{width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,var(--accent-glow),rgba(124,58,237,.12));color:var(--accent);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;border:1px solid rgba(37,99,235,.15);}
.hiw-item-title{font-size:12.5px;font-weight:700;color:var(--text);margin-bottom:4px;}
.hiw-item-desc{font-size:11.5px;color:var(--text3);line-height:1.55;}

/* ── Campaign search ── */
.gr-search{position:relative;margin-bottom:14px;}
.gr-search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text3);pointer-events:none;width:14px;height:14px;}
.gr-search input{width:100%;height:42px;border-radius:11px;border:1.5px solid var(--border2);padding:0 12px 0 36px;font-size:13px;outline:none;background:var(--surface2);color:var(--text);transition:border-color var(--transition),box-shadow var(--transition),background var(--transition);}
.gr-search input:focus{border-color:var(--accent);background:var(--surface);box-shadow:0 0 0 4px var(--accent-glow);}
.gr-search-empty{display:none;padding:18px;text-align:center;font-size:12.5px;color:var(--text3);background:var(--surface2);border:1px dashed var(--border2);border-radius:12px;}

/* ── Campaign grid ── */
.gr-camp-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;max-height:400px;overflow-y:auto;padding-right:4px;}
.gr-camp-card{position:relative;border:1.5px solid var(--border2);border-radius:14px;overflow:hidden;cursor:pointer;transition:border-color var(--transition),transform var(--transition),box-shadow var(--transition);background:var(--surface);}
.gr-camp-card:hover{border-color:var(--accent);transform:translateY(-3px);box-shadow:var(--shadow-md);}
.gr-camp-card.selected{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.gr-camp-card:focus-visible{outline:2px solid var(--accent);outline-offset:2px;}
.gr-camp-card.just-selected{animation:pop .32s ease;}
@keyframes pop{0%{transform:scale(1);}45%{transform:scale(1.035);}100%{transform:scale(1);}}
@media (prefers-reduced-motion:reduce){.gr-camp-card.just-selected{animation:none;}}
.gr-camp-check{position:absolute;top:10px;left:10px;width:22px;height:22px;border-radius:50%;background:var(--accent);color:#fff;display:none;align-items:center;justify-content:center;font-size:11px;box-shadow:0 2px 10px var(--accent-glow);z-index:3;}
.gr-camp-card.selected .gr-camp-check{display:flex;}
.gr-camp-thumb-wrap{height:96px;overflow:hidden;position:relative;background:linear-gradient(135deg,var(--surface2),var(--surface3));}
.gr-camp-thumb{width:100%;height:100%;background-size:cover;background-position:center;display:flex;align-items:center;justify-content:center;color:var(--text3);transition:transform .4s ease;}
.gr-camp-card:hover .gr-camp-thumb{transform:scale(1.07);}
.gr-camp-ring{position:absolute;top:10px;right:10px;width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;z-index:2;box-shadow:0 2px 8px rgba(0,0,0,.18);}
.gr-camp-ring-inner{width:30px;height:30px;border-radius:50%;background:var(--surface);display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:var(--text);font-family:var(--font-mono);}
.gr-camp-info{padding:12px 12px 13px;}
.gr-camp-title{font-size:13px;font-weight:700;color:var(--text);line-height:1.35;margin-bottom:7px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.gr-camp-amt{font-family:var(--font-mono);font-size:15px;font-weight:700;color:var(--text);}
.gr-camp-goal{font-size:11px;color:var(--text3);font-family:var(--font);font-weight:500;}
.gr-camp-bar{height:4px;border-radius:99px;background:var(--surface3);margin-top:9px;overflow:hidden;}
.gr-camp-bar-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,var(--accent),var(--accent2));}

/* ── Details fields ── */
.gr-field{margin-bottom:16px;}
.gr-field:last-of-type{margin-bottom:0;}
.gr-field label{font-size:12px;color:var(--text2);display:block;margin-bottom:7px;font-weight:600;}
.gr-field label .req{color:var(--accent);}
.gr-field input{width:100%;height:46px;border-radius:11px;border:1.5px solid var(--border2);padding:0 14px;font-size:14px;outline:none;background:var(--surface2);color:var(--text);transition:border-color var(--transition),box-shadow var(--transition),background var(--transition);}
.gr-field input:focus{border-color:var(--accent);background:var(--surface);box-shadow:0 0 0 4px var(--accent-glow);}
.gr-field input::placeholder{color:var(--text3);}
.gr-field-hint{font-size:11.5px;color:var(--text3);margin-top:7px;line-height:1.5;}
.gr-field-hint strong{color:var(--accent);font-weight:600;}

/* ── Step actions ── */
.gr-step-actions{display:flex;gap:10px;margin-top:6px;}
.gr-btn-secondary{flex:0 0 auto;padding:0 20px;height:50px;background:transparent;color:var(--text2);border:1.5px solid var(--border2);border-radius:12px;font-size:14px;font-weight:600;cursor:pointer;transition:background var(--transition),border-color var(--transition),color var(--transition);}
.gr-btn-secondary:hover{background:var(--surface2);border-color:var(--accent);color:var(--accent);}
.gr-btn-primary{flex:1;padding:0 16px;height:50px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;transition:opacity var(--transition),transform var(--transition),box-shadow var(--transition);box-shadow:0 6px 20px var(--accent-glow);}
.gr-btn-primary:disabled{opacity:.5;cursor:not-allowed;box-shadow:none;transform:none;}
.gr-btn-primary:not(:disabled):hover{transform:translateY(-1px);box-shadow:0 10px 28px var(--accent-glow);}

/* ── Review step ── */
.gr-review-camp{display:flex;align-items:center;gap:14px;padding:14px;background:var(--surface2);border-radius:13px;margin-bottom:16px;border:1px solid var(--border);}
.gr-review-camp-thumb{width:56px;height:56px;border-radius:11px;background-size:cover;background-position:center;background-color:var(--surface);flex-shrink:0;border:1px solid var(--border);}
.gr-review-camp-title{font-size:14px;font-weight:700;color:var(--text);line-height:1.35;}
.gr-review-camp-sub{font-size:10px;color:var(--text3);margin-top:3px;font-family:var(--font-mono);letter-spacing:.08em;text-transform:uppercase;}
.gr-review-row{display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border);gap:10px;}
.gr-review-row:last-of-type{border-bottom:none;}
.gr-review-label{font-size:12px;color:var(--text3);}
.gr-review-value{font-size:13.5px;color:var(--text);font-weight:600;text-align:right;}
.gr-review-value.mono{font-family:var(--font-mono);letter-spacing:.06em;}
.gr-review-change{font-size:11px;color:var(--accent);font-weight:700;cursor:pointer;background:none;border:none;padding:0;flex-shrink:0;transition:opacity var(--transition);}
.gr-review-change:hover{opacity:.75;}
.gr-review-total{display:flex;align-items:center;justify-content:space-between;padding:16px 18px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:13px;margin-top:16px;box-shadow:0 8px 24px var(--accent-glow);}
.gr-review-total-label{font-size:12px;font-weight:600;color:rgba(255,255,255,.85);}
.gr-review-total-amt{font-size:24px;font-weight:800;color:#fff;font-family:var(--font-mono);}

/* ── Footer link ── */
.gr-foot{display:flex;align-items:center;justify-content:center;gap:6px;margin-top:20px;font-size:12.5px;color:var(--text3);}
.gr-foot a{color:var(--accent);font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px;transition:gap var(--transition);}
.gr-foot a:hover{text-decoration:underline;gap:7px;}

/* ── Alert ── */
.alert-error{display:flex;align-items:flex-start;gap:10px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.22);color:#b91c1c;padding:12px 14px;border-radius:12px;font-size:13px;line-height:1.5;margin-bottom:16px;animation:fadeUp .3s both;}
.alert-error svg{width:16px;height:16px;flex-shrink:0;margin-top:1px;}
.alert-error p{margin:0;}
.alert-error div p + p{margin-top:4px;}

/* ── Responsive ── */
@media (max-width:640px){
    .gr-hero{padding:18px 16px;gap:14px;}
    .gr-hero-body h1{font-size:20px;}
    .gr-hero-badge{display:none;}
    .gr-card{padding:16px;}
    .gr-camp-grid{grid-template-columns:1fr;max-height:none;}
    .gr-code-row{flex-direction:column;gap:10px;}
    .gr-code-btn{width:100%;}
    .hiw-grid{grid-template-columns:1fr;gap:14px;}
    .hiw-item{text-align:left;display:flex;align-items:flex-start;gap:12px;}
    .hiw-item + .hiw-item::before{display:none;}
    .hiw-icon{margin:0;flex-shrink:0;}
}
@media (max-width:440px){
    .gr-step-label{display:none;}
    .gr-progress-title{display:none;}
}
</style>
@endpush

@section('content')
<div class="gr-wrap">

    {{-- Hero --}}
    <div class="gr-hero">
        <div class="gr-hero-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="8" width="20" height="13" rx="3"/><path d="M2 11h20M12 8v13"/><path d="M12 8c-2-4-7-3.5-7-1s3 1.2 7 1c4 .2 7-1.5 7-1s-5-3-7 1z"/></svg>
        </div>
        <div class="gr-hero-body">
            <span class="gr-eyebrow">DonateBazaar</span>
            <h1>Redeem Your Gift Card</h1>
            <p>Turn your code into a donation for a cause you love — it takes under a minute.</p>
        </div>
        <div class="gr-hero-badge">Secure &amp; instant</div>
    </div>

    {{-- Stepper --}}
    <nav class="gr-stepper" id="grStepper" aria-label="Redeem progress">
        <button type="button" class="gr-step-node active" data-node="1" tabindex="-1">
            <span class="gr-step-dot">1</span>
            <span class="gr-step-label">Code</span>
        </button>
        <span class="gr-step-line" data-line="1"></span>
        <button type="button" class="gr-step-node" data-node="2" tabindex="-1">
            <span class="gr-step-dot">2</span>
            <span class="gr-step-label">Campaign</span>
        </button>
        <span class="gr-step-line" data-line="2"></span>
        <button type="button" class="gr-step-node" data-node="3" tabindex="-1">
            <span class="gr-step-dot">3</span>
            <span class="gr-step-label">Details</span>
        </button>
        <span class="gr-step-line" data-line="3"></span>
        <button type="button" class="gr-step-node" data-node="4" tabindex="-1">
            <span class="gr-step-dot">4</span>
            <span class="gr-step-label">Review</span>
        </button>
    </nav>

    <div class="gr-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25">
        <div class="gr-progress-bar"><div class="gr-progress-fill" id="progressFill"></div></div>
        <div class="gr-progress-meta">
            <span class="gr-progress-step" id="progressStepLabel">Step 1 of 4</span>
            <span class="gr-progress-title" id="progressTitleLabel">Enter your gift card code</span>
        </div>
    </div>

    @if(session('error'))
    <div class="alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('gift-cards.redeem.submit') }}" id="redeemForm">
        @csrf
        <input type="hidden" name="code" id="hiddenCode">
        <input type="hidden" name="campaign_id" id="hiddenCampaignId">

        <div class="gr-wizard" id="wizard">

            {{-- STEP 1 — Code --}}
            <section class="gr-step" data-step="1" aria-current="step">
                <div class="gr-card">
                    <div class="gr-card-step"><span class="s-num">1</span><span class="s-txt">Gift card code</span></div>
                    <div class="gr-code-row">
                        <input type="text" id="giftCode" placeholder="DNBZ-XXXX-XXXX" maxlength="14" class="gr-code-input" autocomplete="off" inputmode="text" aria-label="Gift card code">
                        <x-button variant="secondary" type="button" id="checkBtn" onclick="checkCode()" class="gr-code-btn">Check</x-button>
                    </div>
                    <p class="gr-code-hint">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                        Format: XXXX-XXXX-XXXX &mdash; we'll check it automatically once complete.
                    </p>
                    <div id="codeStatus" class="gr-status" role="status" aria-live="polite"></div>
                </div>

                <div class="hiw">
                    <div class="hiw-title">How it works</div>
                    <div class="hiw-grid">
                        <div class="hiw-item">
                            <div class="hiw-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="8" width="18" height="13" rx="1"/><path d="M12 8v13M3 12h18M12 8c-1.5-4-6-4-6-1s3 1 6 1c3 0 6 2 6-1s-4.5-3-6 1z"/></svg></div>
                            <div class="hiw-item-title">Enter your code</div>
                            <div class="hiw-item-desc">Type in the code from your gift card and we'll check its value.</div>
                        </div>
                        <div class="hiw-item">
                            <div class="hiw-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 1 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg></div>
                            <div class="hiw-item-title">Pick a cause</div>
                            <div class="hiw-item-desc">Choose from active campaigns and see exactly where it goes.</div>
                        </div>
                        <div class="hiw-item">
                            <div class="hiw-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></div>
                            <div class="hiw-item-title">We donate instantly</div>
                            <div class="hiw-item-desc">Your gift card value is transferred to the campaign right away.</div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- STEP 2 — Campaign --}}
            <section class="gr-step" data-step="2">
                <div class="gr-card">
                    <div class="gr-card-step"><span class="s-num">2</span><span class="s-txt">Choose a campaign</span></div>

                    @if($campaigns->count() > 6)
                    <div class="gr-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="campSearch" placeholder="Search campaigns…" oninput="filterCampaigns(this.value)">
                    </div>
                    @endif

                    <div class="gr-camp-grid" id="campGrid">
                        @forelse($campaigns as $c)
                        @php $pct = $c->goal_amount > 0 ? min(100, round(($c->raised_amount / $c->goal_amount) * 100)) : 0; @endphp
                        <div onclick="selectCampaign({{ $c->id }}, this, '{{ addslashes($c->title) }}', {{ $c->raised_amount }}, {{ $c->goal_amount }}, '{{ $c->cover_image ? addslashes(asset('storage/'.$c->cover_image)) : '' }}')"
                             id="camp-{{ $c->id }}"
                             class="gr-camp-card"
                             data-title="{{ strtolower($c->title) }}"
                             style="--pct:{{ $pct }}"
                             role="button" tabindex="0" aria-pressed="false">
                            <div class="gr-camp-check">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="gr-camp-thumb-wrap">
                                <div class="gr-camp-thumb" @if($c->cover_image) style="background-image:url('{{ asset('storage/'.$c->cover_image) }}')" @endif>
                                    @if(!$c->cover_image)
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 7h16v13H4z"/><path d="M4 7l8-4 8 4"/></svg>
                                    @endif
                                </div>
                                <div class="gr-camp-ring" style="background:conic-gradient(var(--accent) calc({{ $pct }}*1%), rgba(255,255,255,.6) 0)">
                                    <div class="gr-camp-ring-inner">{{ $pct }}%</div>
                                </div>
                            </div>
                            <div class="gr-camp-info">
                                <div class="gr-camp-title">{{ \Illuminate\Support\Str::limit($c->title, 40) }}</div>
                                <div class="gr-camp-amt">₹{{ number_format($c->raised_amount) }} <span class="gr-camp-goal">of ₹{{ number_format($c->goal_amount) }} goal</span></div>
                                <div class="gr-camp-bar"><div class="gr-camp-bar-fill" style="width:{{ $pct }}%"></div></div>
                            </div>
                        </div>
                        @empty
                        <p style="font-size:13px;color:var(--text3);grid-column:1/-1;">No active campaigns available right now.</p>
                        @endforelse
                    </div>
                    <p class="gr-search-empty" id="searchEmpty">No campaigns match your search.</p>
                </div>

                <div class="gr-step-actions">
                    <x-button variant="secondary" type="button" class="gr-btn-secondary" onclick="gotoStep(1)">Back</x-button>
                    <x-button variant="primary" type="button" id="step2NextBtn" onclick="gotoStep(3)" disabled>Continue</x-button>
                </div>
            </section>

            {{-- STEP 3 — Details --}}
            <section class="gr-step" data-step="3">
                <div class="gr-card">
                    <div class="gr-card-step"><span class="s-num">3</span><span class="s-txt">Your details</span></div>

                    <div class="gr-field">
                        <label for="donorName">Your name <span class="req">*</span></label>
                        <input type="text" name="donor_name" id="donorName" placeholder="e.g. Ananya Sharma" required>
                    </div>
                    <div class="gr-field">
                        <label for="donorEmail">Your email <span class="req">*</span></label>
                        <input type="email" name="donor_email" id="donorEmail" placeholder="you@example.com" required>
                        <p class="gr-field-hint" id="emailHint"></p>
                    </div>
                </div>

                <div class="gr-step-actions">
                    <x-button variant="secondary" type="button" class="gr-btn-secondary" onclick="gotoStep(2)">Back</x-button>
                    <x-button variant="primary" type="button" onclick="tryGotoReview()">Review donation</x-button>
                </div>
            </section>

            {{-- STEP 4 — Review --}}
            <section class="gr-step" data-step="4">
                <div class="gr-card">
                    <div class="gr-card-step"><span class="s-num">4</span><span class="s-txt">Review &amp; confirm</span></div>

                    <div class="gr-review-camp">
                        <div class="gr-review-camp-thumb" id="reviewCampImg"></div>
                        <div>
                            <div class="gr-review-camp-title" id="reviewCampaign"></div>
                            <div class="gr-review-camp-sub">Your chosen campaign</div>
                        </div>
                    </div>

                    <div class="gr-review-row">
                        <span class="gr-review-label">Gift card code</span>
                        <span class="gr-review-value mono" id="reviewCode"></span>
                    </div>
                    <div class="gr-review-row">
                        <span class="gr-review-label">Donor name</span>
                        <span class="gr-review-value" id="reviewName"></span>
                        <x-button variant="secondary" type="button" class="gr-review-change" onclick="gotoStep(3)">Change</x-button>
                    </div>
                    <div class="gr-review-row">
                        <span class="gr-review-label">Donor email</span>
                        <span class="gr-review-value" id="reviewEmail"></span>
                    </div>

                    <div class="gr-review-total">
                        <span class="gr-review-total-label">Total donation</span>
                        <span class="gr-review-total-amt" id="reviewAmount"></span>
                    </div>
                </div>

                <div class="gr-step-actions">
                    <x-button variant="secondary" type="button" class="gr-btn-secondary" onclick="gotoStep(3)">Back</x-button>
                    <x-button variant="primary" type="submit" id="redeemBtn">Redeem Gift Card &amp; Donate</x-button>
                </div>
            </section>

        </div>
    </form>

    <p class="gr-foot">
        Don't have a gift card yet?
        <a href="{{ route('gift-cards.index') }}">Buy one here <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
    </p>

</div>
@endsection

@push('page_scripts')
<script>
var validatedCode = null;
var selectedCampaignId = null;
var selectedCampaignTitle = '';
var selectedCampaignImg = '';
var giftAmount = 0;
var currentStep = 1;
var totalSteps = 4;
var stepTitles = ['Enter your gift card code', 'Choose a campaign', 'Your details', 'Review & confirm'];

var ICON_CHECK = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m8.5 12 2.6 2.6 4.9-5.4"/></svg>';
var ICON_ERROR = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 9 6 6M15 9l-6 6"/></svg>';
var ICON_CHECKMINI = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="m5 12 5 5 9-10"/></svg>';

function setStatus(state, html) {
    var el = document.getElementById('codeStatus');
    el.className = 'gr-status';
    if (state) { el.classList.add(state, 'show'); }
    el.innerHTML = html;
}

function updateProgress() {
    var pct = Math.round((currentStep / totalSteps) * 100);
    document.getElementById('progressFill').style.width = pct + '%';
    document.getElementById('progressStepLabel').textContent = 'Step ' + currentStep + ' of ' + totalSteps;
    document.getElementById('progressTitleLabel').textContent = stepTitles[currentStep - 1];
    document.querySelector('.gr-progress').setAttribute('aria-valuenow', pct);

    document.querySelectorAll('.gr-step-node').forEach(function (d) {
        var n = parseInt(d.dataset.node, 10);
        var isDone = n < currentStep;
        var isActive = n === currentStep;
        d.classList.toggle('done', isDone);
        d.classList.toggle('active', isActive);
        d.querySelector('.gr-step-dot').innerHTML = isDone ? ICON_CHECKMINI : n;
        d.setAttribute('aria-current', isActive ? 'step' : 'false');
        if (isDone) {
            d.onclick = function () { gotoStep(n); };
        } else {
            d.onclick = null;
        }
    });
    document.querySelectorAll('.gr-step-line').forEach(function (l) {
        l.classList.toggle('active', parseInt(l.dataset.line, 10) < currentStep);
    });
}

function showStep(n, direction) {
    document.querySelectorAll('.gr-step').forEach(function (s) {
        var stepNum = parseInt(s.dataset.step, 10);
        if (stepNum === n) {
            s.style.display = 'block';
            s.classList.remove('slide-in-left', 'slide-in-right');
            void s.offsetWidth;
            s.classList.add(direction === 'back' ? 'slide-in-left' : 'slide-in-right');
            s.setAttribute('aria-current', 'step');
        } else {
            s.style.display = 'none';
            s.removeAttribute('aria-current');
        }
    });
    currentStep = n;
    updateProgress();
    var wiz = document.getElementById('wizard');
    var top = wiz.getBoundingClientRect().top + window.scrollY - 20;
    window.scrollTo({ top: top, behavior: 'smooth' });
}

function gotoStep(n) {
    if (n === currentStep) return;
    showStep(n, n < currentStep ? 'back' : 'forward');
}

function formatCode(raw) {
    raw = raw.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 12);
    var groups = raw.match(/.{1,4}/g) || [];
    return groups.join('-');
}

var giftCodeInput = document.getElementById('giftCode');

giftCodeInput.addEventListener('input', function (e) {
    e.target.value = formatCode(e.target.value);
    if (/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/.test(e.target.value)) {
        checkCode();
    }
});

giftCodeInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        checkCode();
    }
});

function checkCode() {
    var code = giftCodeInput.value.trim().toUpperCase();
    var btn = document.getElementById('checkBtn');

    if (!code) {
        setStatus('err', ICON_ERROR + '<span>Please enter a code.</span>');
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Checking…';
    setStatus('info', '<span class="gr-spin"></span><span>Checking your code…</span>');

    fetch('{{ route("gift-cards.validate-code") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ code: code })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        btn.disabled = false;
        btn.textContent = 'Check';

        if (data.valid) {
            validatedCode = data.code;
            giftAmount = Number(data.amount);
            setStatus('ok', ICON_CHECK + '<span>Valid! This gift card is worth <strong>₹' + giftAmount.toLocaleString('en-IN') + '</strong>.</span>');
            document.getElementById('hiddenCode').value = data.code;

            var emailInput = document.getElementById('donorEmail');
            emailInput.readOnly = false;
            emailInput.style.background = '';
            emailInput.style.cursor = '';
            document.getElementById('emailHint').innerHTML = 'This gift card was sent to <strong>' + data.recipient_email_masked + '</strong> — enter your full email above.';

            setTimeout(function () { gotoStep(2); }, 350);
        } else {
            validatedCode = null;
            setStatus('err', ICON_ERROR + '<span>' + (data.message || 'Invalid code.') + '</span>');

            var emailInput = document.getElementById('donorEmail');
            emailInput.value = '';
            emailInput.readOnly = false;
            emailInput.style.background = '';
            emailInput.style.cursor = '';
            document.getElementById('emailHint').textContent = '';
        }
    })
    .catch(function () {
        btn.disabled = false;
        btn.textContent = 'Check';
        setStatus('err', ICON_ERROR + '<span>Something went wrong. Please try again.</span>');
    });
}

function selectCampaign(id, el, title, raised, goal, imageUrl) {
    selectedCampaignId = id;
    selectedCampaignTitle = title;
    selectedCampaignImg = imageUrl;
    document.getElementById('hiddenCampaignId').value = id;

    document.querySelectorAll('.gr-camp-card').forEach(function (c) {
        c.classList.remove('selected', 'just-selected');
        c.setAttribute('aria-pressed', 'false');
    });
    el.classList.add('selected', 'just-selected');
    el.setAttribute('aria-pressed', 'true');

    document.getElementById('step2NextBtn').disabled = false;
}

function filterCampaigns(query) {
    query = query.trim().toLowerCase();
    var cards = document.querySelectorAll('.gr-camp-card');
    var visibleCount = 0;
    cards.forEach(function (card) {
        var match = card.dataset.title.indexOf(query) !== -1;
        card.style.display = match ? '' : 'none';
        if (match) visibleCount++;
    });
    document.getElementById('searchEmpty').style.display = visibleCount === 0 ? 'block' : 'none';
}

function tryGotoReview() {
    var name = document.getElementById('donorName');
    var email = document.getElementById('donorEmail');
    if (!name.value.trim()) { name.reportValidity(); name.focus(); return; }
    if (!email.value.trim()) { email.reportValidity(); email.focus(); return; }

    document.getElementById('reviewCode').textContent = validatedCode || '';
    document.getElementById('reviewCampaign').textContent = selectedCampaignTitle;
    document.getElementById('reviewCampImg').style.backgroundImage = selectedCampaignImg ? "url('" + selectedCampaignImg + "')" : 'none';
    document.getElementById('reviewName').textContent = name.value.trim();
    document.getElementById('reviewEmail').textContent = email.value.trim();
    document.getElementById('reviewAmount').textContent = '₹' + giftAmount.toLocaleString('en-IN');

    gotoStep(4);
}

// Keyboard support for campaign cards
document.addEventListener('keydown', function (e) {
    if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('gr-camp-card')) {
        e.preventDefault();
        e.target.click();
    }
});

// Prevent double submission
document.getElementById('redeemForm').addEventListener('submit', function () {
    var btn = document.getElementById('redeemBtn');
    if (btn.dataset.submitted === 'true') { event.preventDefault(); return; }
    btn.dataset.submitted = 'true';
    btn.disabled = true;
    btn.textContent = 'Processing…';
});

showStep(1, 'forward');
</script>
@endpush
