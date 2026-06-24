@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Outfit:wght@300;400;500;600&display=swap');

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

:root {
    --purple-deep:  #4c1d95;
    --purple-main:  #7c3aed;
    --purple-mid:   #8b5cf6;
    --purple-light: #a78bfa;
    --purple-pale:  #ede9fe;
    --purple-mist:  #f5f3ff;
    --indigo-main:  #4f46e5;
    --indigo-light: #c7d2fe;
    --indigo-pale:  #e0e7ff;
    --white:        #ffffff;
    --ink:          #1e1b4b;
    --ink-mid:      #3730a3;
    --ink-soft:     #6d6aaf;
    --ink-muted:    #a5a3c8;
    --border:       #ddd6fe;
    --surface:      #faf9ff;
    --danger:       #e24b4a;
    --danger-bg:    #fef2f2;
    --success:      #059669;
    --success-pale: #d1fae5;
    --gold:         #d97706;
    --gold-pale:    #fef3c7;
}

body{font-family:'Outfit',sans-serif;color:var(--ink)}

/* ── Page shell ── */
.page-shell {
    min-height:100vh;
    background:
        radial-gradient(ellipse 80% 60% at 20% 0%,rgba(167,139,250,.22) 0%,transparent 60%),
        radial-gradient(ellipse 60% 50% at 80% 100%,rgba(99,102,241,.18) 0%,transparent 55%),
        linear-gradient(160deg,#f0ebff 0%,#eef2ff 50%,#f3e8ff 100%);
    position:relative;overflow-x:hidden;
}
.page-shell::before,.page-shell::after{
    content:'';position:fixed;border-radius:50%;
    filter:blur(80px);pointer-events:none;z-index:0;
}
.page-shell::before{width:420px;height:420px;top:-100px;left:-120px;background:rgba(139,92,246,.14)}
.page-shell::after {width:360px;height:360px;bottom:-80px;right:-100px;background:rgba(99,102,241,.12)}

.pg-grid{
    display:grid;
    grid-template-columns:300px 1fr 280px;
    max-width:1280px;margin:0 auto;
    position:relative;z-index:1;min-height:100vh;
}

/* ══════════════════════════
   TOAST SYSTEM
══════════════════════════ */
.toast-stack{
    position:fixed;top:24px;right:24px;
    z-index:9999;
    display:flex;flex-direction:column;gap:10px;
    pointer-events:none;
    max-width:360px;width:calc(100vw - 48px);
}
.toast{
    display:flex;align-items:flex-start;gap:12px;
    padding:14px 16px;border-radius:16px;
    font-size:13.5px;font-weight:500;
    pointer-events:all;
    position:relative;overflow:hidden;
    box-shadow:0 8px 32px rgba(0,0,0,0.18),0 2px 8px rgba(0,0,0,0.10);
    animation:toastIn .35s cubic-bezier(.34,1.56,.64,1) both;
    will-change:transform,opacity;
    font-family:'Outfit',sans-serif;
}
.toast.dismissing{animation:toastOut .3s ease forwards}
/* Progress bar */
.toast::after{
    content:'';position:absolute;bottom:0;left:0;height:3px;
    background:rgba(255,255,255,0.4);
    animation:toastProgress var(--toast-dur,5s) linear forwards;
    border-radius:0 0 16px 16px;
    transform-origin:left;
}
.toast-success{
    background:linear-gradient(135deg,#065f46,#059669);
    color:#fff;border:1px solid rgba(255,255,255,0.15);
}
.toast-error{
    background:linear-gradient(135deg,#991b1b,#dc2626);
    color:#fff;border:1px solid rgba(255,255,255,0.15);
}
.toast-warning{
    background:linear-gradient(135deg,#92400e,#d97706);
    color:#fff;border:1px solid rgba(255,255,255,0.15);
}
.toast-info{
    background:linear-gradient(135deg,#1e1b4b,#4f46e5);
    color:#fff;border:1px solid rgba(255,255,255,0.15);
}
.toast-icon{
    width:36px;height:36px;border-radius:10px;
    background:rgba(255,255,255,0.18);
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;
}
.toast-icon svg{width:18px;height:18px}
.toast-body{flex:1;min-width:0}
.toast-title{font-weight:600;font-size:14px;line-height:1.3;margin-bottom:3px}
.toast-msg{font-size:12.5px;opacity:.88;line-height:1.5}
.toast-close{
    width:26px;height:26px;border-radius:7px;flex-shrink:0;
    background:rgba(255,255,255,0.15);border:none;cursor:pointer;
    color:rgba(255,255,255,0.8);font-size:14px;
    display:flex;align-items:center;justify-content:center;
    transition:background .15s;font-family:inherit;
    margin-top:1px;
}
.toast-close:hover{background:rgba(255,255,255,0.28)}

@keyframes toastIn{
    from{opacity:0;transform:translateX(100%) scale(.9)}
    to  {opacity:1;transform:translateX(0) scale(1)}
}
@keyframes toastOut{
    to{opacity:0;transform:translateX(100%) scale(.9);height:0;padding:0;margin:0}
}
@keyframes toastProgress{from{transform:scaleX(1)} to{transform:scaleX(0)}}

/* ── Submit button loading state ── */
.btn-submit{
    display:inline-flex;align-items:center;gap:10px;
    background:linear-gradient(135deg,var(--purple-main) 0%,var(--indigo-main) 100%);
    color:#fff;border:none;padding:14px 32px;border-radius:14px;
    font-family:'Outfit',sans-serif;font-size:14px;font-weight:500;
    cursor:pointer;
    transition:opacity .2s,transform .2s,box-shadow .2s;
    box-shadow:0 6px 20px rgba(124,58,237,.4);
    letter-spacing:.02em;position:relative;overflow:hidden;
    min-width:220px;justify-content:center;
}
.btn-submit::before{
    content:'';position:absolute;inset:0;
    background:linear-gradient(135deg,rgba(255,255,255,.15),transparent);
    border-radius:inherit;
}
.btn-submit:hover:not(:disabled){
    opacity:.93;transform:translateY(-2px);
    box-shadow:0 10px 28px rgba(124,58,237,.45);
}
.btn-submit:disabled{
    opacity:.7;cursor:not-allowed;transform:none;
}
.btn-submit svg{width:15px;height:15px;flex-shrink:0}
.btn-spinner{
    width:16px;height:16px;border-radius:50%;
    border:2px solid rgba(255,255,255,.35);
    border-top-color:#fff;
    animation:spin .6s linear infinite;
    flex-shrink:0;
}
@keyframes spin{to{transform:rotate(360deg)}}

/* ── Left column ── */
.pg-left{
    padding:56px 28px 56px 44px;
    display:flex;flex-direction:column;
    position:sticky;top:0;height:100vh;overflow-y:auto;
}
.pg-left::-webkit-scrollbar{width:0}

.brand{display:flex;align-items:center;gap:10px;margin-bottom:44px}
.brand-icon{width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));display:flex;align-items:center;justify-content:center}
.brand-icon svg{width:18px;height:18px;color:#fff}
.brand-name{font-family:'DM Mono',monospace; font-size:18px;font-weight:600;color:var(--ink)}

.trust-eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.2);border-radius:100px;padding:5px 14px;font-size:11px;font-weight:500;letter-spacing:.08em;color:var(--purple-main);text-transform:uppercase;margin-bottom:18px}
.trust-eyebrow span{width:6px;height:6px;border-radius:50%;background:var(--purple-main);display:inline-block;animation:pulse-dot 2s ease infinite}
@keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.7)}}

.trust-headline{font-family:'DM Mono',monospace; text-transform: capitalize; font-size:clamp(1.5rem,2vw,2rem);font-weight:600;background:linear-gradient(135deg,var(--purple-deep) 0%,var(--purple-main) 50%,var(--indigo-main) 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.2;margin-bottom:14px}
.trust-body{font-size:13px;color:var(--ink-soft);font-weight:300;line-height:1.7;margin-bottom:28px}

.trust-signals{display:flex;flex-direction:column;gap:10px;margin-bottom:24px}
.ts-item{display:flex;align-items:flex-start;gap:12px;padding:13px 14px;background:rgba(255,255,255,.7);border:1px solid rgba(221,214,254,.8);border-radius:14px;backdrop-filter:blur(8px);transition:border-color .2s,transform .2s}
.ts-item:hover{border-color:var(--purple-light);transform:translateX(3px)}
.ts-icon{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ts-icon-purple{background:var(--purple-pale);color:var(--purple-main)}
.ts-icon-green {background:var(--success-pale);color:var(--success)}
.ts-icon-gold  {background:var(--gold-pale);color:var(--gold)}
.ts-icon svg{width:15px;height:15px}
.ts-text strong{display:block;font-size:12px;font-weight:600;color:var(--ink);margin-bottom:2px}
.ts-text span  {font-size:11px;color:var(--ink-soft);line-height:1.5}

.stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);border-radius:14px;overflow:hidden;border:1px solid var(--border);margin-bottom:20px}
.st-cell{background:rgba(255,255,255,.85);padding:13px 8px;text-align:center}
.st-val{font-family:'Playfair Display',serif;font-size:19px;font-weight:600;color:var(--ink)}
.st-key{font-size:11px;color:var(--ink-muted);margin-top:2px}

.partner-wrap{display:flex;flex-direction:column;gap:10px}
.partner-label{font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted)}
.partner-chips{display:flex;flex-wrap:wrap;gap:7px}
.partner-chip{padding:5px 11px;background:rgba(255,255,255,.7);border:1px solid var(--border);border-radius:100px;font-size:11px;font-weight:500;color:var(--ink-soft)}

/* ── Center column ── */
.pg-center{
    border-left:1px solid rgba(221,214,254,.6);
    border-right:1px solid rgba(221,214,254,.6);
    padding:56px 48px 80px;
    background:rgba(255,255,255,.55);
    backdrop-filter:blur(24px);
}

.page-eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.2);border-radius:100px;padding:6px 16px;font-size:12px;font-weight:500;letter-spacing:.08em;color:var(--purple-main);text-transform:uppercase;margin-bottom:20px}
.page-eyebrow span{width:6px;height:6px;border-radius:50%;background:var(--purple-main);display:inline-block;animation:pulse-dot 2s ease infinite}
.page-title{font-family:'DM MONO',monospace; text-transform: capitalize; font-size:clamp(1.8rem,2.4vw,2.3rem);font-weight:600;background:linear-gradient(135deg,var(--purple-deep) 0%,var(--purple-main) 50%,var(--indigo-main) 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.2;margin-bottom:10px}
.page-subtitle{font-size:14px;color:var(--ink-soft);font-weight:300;line-height:1.6;margin-bottom:36px}

/* ── Stepper ── */
.stepper-wrap{display:flex;align-items:flex-start;margin-bottom:36px;max-width:380px}
.stepper-item{display:flex;flex-direction:column;align-items:center;flex:1;max-width:130px;position:relative}
.stepper-item:not(:last-child)::after{content:'';position:absolute;top:17px;left:calc(50% + 17px);width:calc(100% - 34px);height:2px;background:var(--indigo-pale)}
.stepper-item.s-done:not(:last-child)::after{background:linear-gradient(90deg,var(--purple-main),var(--purple-light))}
.stepper-dot{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;border:2px solid var(--border);background:var(--white);color:var(--ink-muted);transition:all .35s cubic-bezier(.4,0,.2,1);position:relative;z-index:1}
.stepper-dot.s-active{background:var(--white);border-color:var(--purple-main);color:var(--purple-main);box-shadow:0 0 0 5px rgba(124,58,237,.12),0 4px 12px rgba(124,58,237,.2);transform:scale(1.08)}
.stepper-dot.s-done  {background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));border-color:var(--purple-main);color:var(--white);box-shadow:0 4px 12px rgba(124,58,237,.35)}
.stepper-label{font-size:10px;font-weight:400;color:var(--ink-muted);margin-top:8px;letter-spacing:.04em;text-align:center}
.stepper-label.s-active{color:var(--purple-main);font-weight:600}
.stepper-label.s-done  {color:var(--ink-soft)}

/* ── Form card ── */
.form-card{background:rgba(255,255,255,.88);backdrop-filter:blur(24px) saturate(1.4);border-radius:28px;border:1px solid rgba(255,255,255,.75);box-shadow:0 2px 0 rgba(124,58,237,.06) inset,0 30px 80px rgba(109,40,217,.1),0 8px 24px rgba(99,102,241,.08);overflow:hidden}
.card-header-bar{padding:26px 36px 22px;border-bottom:1px solid var(--indigo-pale);background:linear-gradient(to right,rgba(245,243,255,.8),rgba(238,242,255,.8))}
.step-badge{display:inline-block;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--purple-main);background:var(--purple-pale);padding:3px 10px;border-radius:100px;margin-bottom:10px}
.step-heading{font-family:'DM MONO',monospace; text-transform: capitalize; font-size:1.3rem;font-weight:600;color:var(--ink);margin-bottom:4px}
.step-sub{font-size:13px;color:var(--ink-soft);font-weight:300}
.card-body{padding:32px 36px 36px}

/* ── Fields ── */
.section-title{font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);display:flex;align-items:center;gap:10px;margin-bottom:18px}
.section-title::after{content:'';flex:1;height:1px;background:var(--indigo-pale)}
.field-stack{display:flex;flex-direction:column;gap:22px}
.field-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.field-wrap{display:flex;flex-direction:column;gap:7px}
.field-label{font-size:12px;font-weight:600;color:var(--ink-soft);letter-spacing:.07em;text-transform:uppercase}
.field-label .req{color:var(--purple-main);margin-left:2px}
.field-label .opt{color:var(--ink-muted);font-weight:400;font-size:10px;margin-left:4px;text-transform:none;letter-spacing:0}
.field-hint{font-size:11px;color:var(--ink-muted)}

.field-input{
    width:100%;padding:13px 16px;
    border:1.5px solid var(--border);border-radius:14px;
    background:var(--surface);font-family:'Outfit',sans-serif;
    font-size:14px;font-weight:400;color:var(--ink);
    outline:none;
    transition:border-color .2s,box-shadow .2s,background .2s;
    -webkit-appearance:none;
}
.field-input:hover{border-color:var(--purple-light)}
.field-input:focus{border-color:var(--purple-main);box-shadow:0 0 0 4px rgba(124,58,237,.12);background:var(--white)}
.field-input.is-error{border-color:var(--danger);box-shadow:0 0 0 3px rgba(226,75,74,.1)}
.field-input::placeholder{color:var(--ink-muted);font-weight:300}
textarea.field-input{resize:vertical;min-height:110px;line-height:1.6}

/* char counter */
.field-footer{display:flex;justify-content:space-between;align-items:center;margin-top:5px}
.char-count{font-size:11px;color:var(--ink-muted);font-family:monospace;transition:color .2s}
.char-count.warn{color:var(--gold)}
.char-count.over{color:var(--danger)}

/* ── Partnership type cards ── */
.ptype-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.ptype-option{display:none}
.ptype-label{
    display:flex;flex-direction:column;align-items:center;gap:10px;
    padding:16px 10px;border:1.5px solid var(--border);border-radius:16px;
    cursor:pointer;text-align:center;
    transition:all .2s;background:var(--surface);user-select:none;
}
.ptype-label:hover{border-color:var(--purple-light);background:var(--purple-mist);transform:translateY(-2px)}
.ptype-option:checked + .ptype-label{
    border-color:var(--purple-main);background:var(--white);
    box-shadow:0 0 0 3px rgba(124,58,237,.1),0 4px 16px rgba(124,58,237,.1);
    transform:translateY(-2px);
}
.ptype-icon-wrap{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:var(--purple-pale);color:var(--purple-main);transition:background .2s,color .2s}
.ptype-icon-wrap svg{width:18px;height:18px}
.ptype-option:checked + .ptype-label .ptype-icon-wrap{background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));color:white}
.ptype-text{font-size:12px;font-weight:500;color:var(--ink);line-height:1.3}

/* ── Upload zone ── */
.upload-zone{
    border:2px dashed var(--border);border-radius:16px;
    padding:32px 24px;text-align:center;
    background:var(--purple-mist);cursor:pointer;
    transition:border-color .25s,background .25s,transform .2s;
}
.upload-zone:hover{border-color:var(--purple-main);background:var(--purple-pale);transform:scale(1.01)}
.upload-zone.has-file{border-style:solid;border-color:var(--success);background:#f0fdf4}
.upload-zone input{display:none}
.upload-icon-wrap{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));display:flex;align-items:center;justify-content:center;margin:0 auto 12px;box-shadow:0 6px 18px rgba(124,58,237,.3)}
.upload-icon-wrap svg{width:20px;height:20px;color:white}
.upload-title{font-size:14px;font-weight:500;color:var(--ink);margin-bottom:4px}
.upload-hint {font-size:12px;color:var(--ink-muted)}
.upload-pill {display:inline-block;margin-top:12px;padding:6px 18px;border-radius:100px;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.25);color:var(--purple-main);font-size:12px;font-weight:500;transition:background .2s}
.upload-zone:hover .upload-pill{background:rgba(124,58,237,.18)}
.upload-success{display:none;flex-direction:column;align-items:center;gap:6px}
.upload-success.show{display:flex}
.upload-success-icon{width:44px;height:44px;border-radius:12px;background:rgba(5,150,105,.12);color:var(--success);display:flex;align-items:center;justify-content:center;margin-bottom:4px}
.upload-success-icon svg{width:22px;height:22px}
.upload-fname{font-size:13px;font-weight:600;color:var(--success)}
.upload-fsize{font-size:11px;color:var(--ink-muted)}
.upload-change{font-size:11px;color:var(--purple-main);cursor:pointer;text-decoration:underline;margin-top:4px}

/* ── Privacy note ── */
.privacy-note{display:flex;align-items:flex-start;gap:10px;padding:14px 16px;background:rgba(224,231,255,.4);border:1px solid var(--indigo-light);border-radius:12px;font-size:12px;color:var(--ink-soft);line-height:1.6}
.privacy-note svg{width:16px;height:16px;color:var(--indigo-main);flex-shrink:0;margin-top:1px}

/* ── Form nav ── */
.form-nav{display:flex;justify-content:flex-end;align-items:center;margin-top:36px;padding-top:28px;border-top:1px solid var(--indigo-pale);gap:16px;flex-wrap:wrap}
.submit-note{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--ink-muted)}
.submit-note svg{width:13px;height:13px}

/* ══ Review card ══ */
.review-card{background:rgba(255,255,255,.9);backdrop-filter:blur(24px) saturate(1.4);border-radius:28px;border:1px solid rgba(255,255,255,.8);box-shadow:0 30px 80px rgba(109,40,217,.1),0 8px 24px rgba(99,102,241,.08);overflow:hidden;animation:fadeSlide .45s cubic-bezier(.4,0,.2,1)}
@keyframes fadeSlide{from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)}}

.review-status-bar{padding:28px 36px 24px;border-bottom:1px solid var(--indigo-pale);background:linear-gradient(to right,rgba(245,243,255,.9),rgba(238,242,255,.9));display:flex;align-items:flex-start;gap:18px}
.review-status-icon{width:52px;height:52px;border-radius:16px;flex-shrink:0;display:flex;align-items:center;justify-content:center}
.icon-pending {background:var(--gold-pale); color:var(--gold)}
.icon-approved{background:var(--success-pale);color:var(--success)}
.icon-rejected{background:var(--danger-bg);  color:var(--danger)}
.review-status-icon svg{width:24px;height:24px}
.review-status-text h3{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:600;color:var(--ink);margin-bottom:4px}
.review-status-text p {font-size:13px;color:var(--ink-soft);font-weight:300;line-height:1.6}

.status-pill{display:inline-flex;align-items:center;gap:6px;padding:5px 13px;border-radius:100px;font-size:11px;font-weight:600;letter-spacing:.04em;margin-top:10px}
.status-pill-dot{width:6px;height:6px;border-radius:50%}
.pill-pending {background:var(--gold-pale); color:var(--gold)}
.pill-pending .status-pill-dot{background:var(--gold);animation:pulse-dot 1.5s ease infinite}
.pill-approved{background:var(--success-pale);color:var(--success)}
.pill-approved .status-pill-dot{background:var(--success)}
.pill-rejected{background:var(--danger-bg);  color:var(--danger)}
.pill-rejected .status-pill-dot{background:var(--danger)}

.review-body{padding:28px 36px 32px}
.rv-section{font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);display:flex;align-items:center;gap:10px;margin-bottom:16px;margin-top:28px}
.rv-section:first-child{margin-top:0}
.rv-section::after{content:'';flex:1;height:1px;background:var(--indigo-pale)}
.rv-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:4px}
.rv-field{background:var(--surface);border:1px solid var(--indigo-pale);border-radius:12px;padding:13px 15px}
.rv-field.full{grid-column:1/-1}
.rv-field-label{font-size:10px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:5px}
.rv-field-value{font-size:13px;font-weight:500;color:var(--ink);line-height:1.5}
.rv-field-value.empty{color:var(--ink-muted);font-weight:300;font-style:italic}
.rv-field-value a{color:var(--purple-main);text-decoration:none}
.type-badge{display:inline-flex;align-items:center;gap:7px;padding:5px 13px;border-radius:100px;background:var(--purple-pale);color:var(--purple-deep);font-size:12px;font-weight:600}
.type-badge svg{width:13px;height:13px}
.approved-highlight{display:flex;align-items:flex-start;gap:14px;padding:16px 18px;background:linear-gradient(135deg,var(--success-pale),#ecfdf5);border:1.5px solid rgba(5,150,105,.2);border-radius:16px;margin-bottom:24px}
.approved-highlight-icon{width:40px;height:40px;border-radius:12px;background:rgba(5,150,105,.15);color:var(--success);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.approved-highlight-icon svg{width:20px;height:20px}
.approved-highlight h4{font-size:14px;font-weight:600;color:var(--success);margin-bottom:4px}
.approved-highlight p {font-size:13px;color:#047857;line-height:1.6;font-weight:300}
.admin-feedback{padding:16px 18px;background:var(--danger-bg);border:1px solid rgba(226,75,74,.2);border-left:4px solid var(--danger);border-radius:14px;font-size:13px;color:#991b1b;line-height:1.7;margin-bottom:4px}

.timeline{display:flex;flex-direction:column}
.tl-item{display:flex;align-items:flex-start;gap:16px;padding-bottom:20px;position:relative}
.tl-item:last-child{padding-bottom:0}
.tl-item:not(:last-child)::before{content:'';position:absolute;left:15px;top:34px;width:2px;height:calc(100% - 14px);background:var(--indigo-pale)}
.tl-dot{width:32px;height:32px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;border:2px solid var(--border);background:var(--white);color:var(--ink-muted);position:relative;z-index:1}
.tl-dot.tl-done  {background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));border-color:var(--purple-main);color:white}
.tl-dot.tl-active{border-color:var(--gold);background:var(--gold-pale);color:var(--gold)}
.tl-dot svg{width:14px;height:14px}
.tl-content{padding-top:4px}
.tl-title{font-size:13px;font-weight:600;color:var(--ink);margin-bottom:3px;display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.tl-inprogress{font-size:10px;color:var(--gold);background:var(--gold-pale);padding:2px 8px;border-radius:100px;font-weight:600}
.tl-action    {font-size:10px;color:var(--purple-main);background:var(--purple-pale);padding:2px 8px;border-radius:100px;font-weight:600}
.tl-desc{font-size:12px;color:var(--ink-soft);line-height:1.6}

/* ── Right column ── */
.pg-right{padding:56px 44px 56px 28px;display:flex;flex-direction:column;gap:14px;position:sticky;top:0;height:100vh;overflow-y:auto}
.pg-right::-webkit-scrollbar{width:0}

.testimonial-card{background:rgba(255,255,255,.85);border:1px solid rgba(221,214,254,.8);border-radius:20px;padding:22px;backdrop-filter:blur(12px);box-shadow:0 4px 20px rgba(124,58,237,.06);position:relative;transition:transform .2s,box-shadow .2s}
.testimonial-card:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(124,58,237,.1)}
.quote-mark{font-family:'Playfair Display',serif;font-size:60px;line-height:1;color:var(--purple-main);opacity:.15;position:absolute;top:6px;left:18px}
.testimonial-text{font-size:13px;line-height:1.75;color:var(--ink);padding-top:18px;margin-bottom:14px;font-style:italic;font-weight:300}
.testimonial-author{display:flex;align-items:center;gap:10px}
.t-avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;flex-shrink:0}
.t-name{font-size:13px;font-weight:600;color:var(--ink)}
.t-role{font-size:11px;color:var(--ink-soft)}

.faq-card{background:rgba(255,255,255,.85);border:1px solid rgba(221,214,254,.8);border-radius:20px;padding:20px;backdrop-filter:blur(12px)}
.faq-header{font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:14px;display:flex;align-items:center;gap:8px}
.faq-header svg{width:13px;height:13px;color:var(--purple-main)}
.faq-item{border-top:1px solid var(--indigo-pale);padding:12px 0}
.faq-item:first-of-type{border-top:none}
.faq-q{font-size:13px;font-weight:500;color:var(--ink);margin-bottom:5px;display:flex;align-items:flex-start;gap:8px}
.faq-q-badge{font-size:9px;font-weight:700;background:var(--purple-pale);color:var(--purple-main);border-radius:4px;padding:2px 5px;flex-shrink:0;margin-top:2px;letter-spacing:.04em}
.faq-a{font-size:12px;color:var(--ink-soft);line-height:1.6;padding-left:26px}

/* ── Responsive ── */
@media(max-width:1100px){.pg-grid{grid-template-columns:280px 1fr}.pg-right{display:none}}
@media(max-width:780px) {.pg-grid{grid-template-columns:1fr}.pg-left{display:none}.pg-center{padding:40px 24px 60px}}
@media(max-width:560px) {.field-grid,.ptype-grid{grid-template-columns:1fr}.rv-grid{grid-template-columns:1fr}.form-nav{flex-direction:column-reverse;align-items:stretch}.btn-submit{width:100%}}
</style>

{{-- ══ TOAST CONTAINER ══ --}}
<div class="toast-stack" id="toastStack" role="status" aria-live="polite" aria-atomic="false"></div>

<div class="page-shell">
<div class="pg-grid">

{{-- ══ LEFT ══ --}}
<aside class="pg-left">
    <div class="brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <span class="brand-name">DonateBazar</span>
    </div>
    <div class="trust-eyebrow"><span></span> Partnership Program</div>
    <h2 class="trust-headline">Make an impact<br>that truly matters</h2>
    <p class="trust-body">Join hundreds of organisations already creating measurable social change. Built on transparency, accountability, and shared values.</p>
    <div class="trust-signals">
        <div class="ts-item">
            <div class="ts-icon ts-icon-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
            <div class="ts-text"><strong>100% Verified Partners</strong><span>Every partner is KYC-verified and audited before funds are released.</span></div>
        </div>
        <div class="ts-item">
            <div class="ts-icon ts-icon-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
            <div class="ts-text"><strong>Full Transparency Reports</strong><span>Quarterly impact reports delivered to all partners without exception.</span></div>
        </div>
        <div class="ts-item">
            <div class="ts-icon ts-icon-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            <div class="ts-text"><strong>Dedicated Support</strong><span>A relationship manager responds within 48 hours of your inquiry.</span></div>
        </div>
    </div>
    <div class="stats-row">
        <div class="st-cell"><div class="st-val">240+</div><div class="st-key">Partners</div></div>
        <div class="st-cell"><div class="st-val">₹4.2Cr</div><div class="st-key">Raised</div></div>
        <div class="st-cell"><div class="st-val">18</div><div class="st-key">States</div></div>
    </div>
    <div class="partner-wrap">
        <span class="partner-label">Trusted by</span>
        <div class="partner-chips">
            <span class="partner-chip">Tata Trusts</span>
            <span class="partner-chip">Give India</span>
            <span class="partner-chip">HDFC CSR</span>
            <span class="partner-chip">Infosys Foundation</span>
        </div>
    </div>
</aside>

{{-- ══ CENTER ══ --}}
<main class="pg-center">

    <div class="page-eyebrow"><span></span> Apply Now</div>
    <h1 class="page-title">Apply for Partnership</h1>
    <p class="page-subtitle">Fill in your details — our team responds within 2 business days.</p>

    @if(isset($partnership) && $partnership)

        {{-- ── REVIEW STATE ── --}}
        <div class="stepper-wrap">
            <div class="stepper-item s-done">
                <div class="stepper-dot s-done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px"><path d="M20 6L9 17l-5-5"/></svg></div>
                <span class="stepper-label s-done">Your Info</span>
            </div>
            <div class="stepper-item s-done">
                <div class="stepper-dot s-done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px"><path d="M20 6L9 17l-5-5"/></svg></div>
                <span class="stepper-label s-done">Organisation</span>
            </div>
            <div class="stepper-item">
                <div class="stepper-dot {{ $partnership->status === 'approved' ? 's-done' : 's-active' }}">
                    @if($partnership->status === 'approved')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px"><path d="M20 6L9 17l-5-5"/></svg>
                    @else 3 @endif
                </div>
                <span class="stepper-label s-active">Review</span>
            </div>
        </div>

        <div class="review-card">
            <div class="review-status-bar">
                <div class="review-status-icon icon-{{ $partnership->status }}">
                    @if($partnership->status === 'approved')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    @elseif($partnership->status === 'rejected')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    @endif
                </div>
                <div class="review-status-text">
                    @if($partnership->status === 'approved')
                        <h3>Partnership Approved</h3>
                        <p>Congratulations — your request has been approved. Welcome to the DonateBazar partner network.</p>
                    @elseif($partnership->status === 'rejected')
                        <h3>Request Not Approved</h3>
                        <p>Your request could not be approved at this time. Review the feedback below and consider reapplying.</p>
                    @else
                        <h3>Request Under Review</h3>
                        <p>Your partnership request is being reviewed. We will respond within 2 business days.</p>
                    @endif
                    <div class="status-pill pill-{{ $partnership->status }}">
                        <span class="status-pill-dot"></span>
                        @if($partnership->status === 'approved') Approved
                        @elseif($partnership->status === 'rejected') Not Approved
                        @else Pending Review
                        @endif
                    </div>
                </div>
            </div>
            <div class="review-body">
                @if($partnership->status === 'approved')
                <div class="approved-highlight">
                    <div class="approved-highlight-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0 1 12 2.944a11.955 11.955 0 0 1-8.618 3.04A12.02 12.02 0 0 0 3 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.082-.132-2.135-.382-3.042z"/></svg>
                    </div>
                    <div>
                        <h4>Your partnership is now active</h4>
                        <p>A dedicated relationship manager has been assigned. Check your email for onboarding instructions.</p>
                    </div>
                </div>
                @endif
                @if($partnership->status === 'rejected' && $partnership->admin_note)
                <div class="rv-section">Admin feedback</div>
                <div class="admin-feedback">{{ $partnership->admin_note }}</div>
                @endif
                <div class="rv-section">Your submitted details</div>
                <div class="rv-grid">
                    <div class="rv-field"><div class="rv-field-label">Full Name</div><div class="rv-field-value">{{ $partnership->name }}</div></div>
                    <div class="rv-field"><div class="rv-field-label">Email</div><div class="rv-field-value">{{ $partnership->email }}</div></div>
                    <div class="rv-field"><div class="rv-field-label">Phone</div><div class="rv-field-value {{ !$partnership->phone?'empty':'' }}">{{ $partnership->phone ?: 'Not provided' }}</div></div>
                    <div class="rv-field"><div class="rv-field-label">Organisation</div><div class="rv-field-value">{{ $partnership->organization_name }}</div></div>
                    <div class="rv-field"><div class="rv-field-label">Website</div><div class="rv-field-value {{ !$partnership->website?'empty':'' }}">@if($partnership->website)<a href="{{ $partnership->website }}" target="_blank">{{ $partnership->website }}</a>@else Not provided @endif</div></div>
                    <div class="rv-field"><div class="rv-field-label">Type</div><div class="rv-field-value"><span class="type-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3H8l-2 4h12l-2-4z"/></svg>{{ ucwords(str_replace('_',' ',$partnership->partnership_type ?? 'Not selected')) }}</span></div></div>
                    @if($partnership->message)<div class="rv-field full"><div class="rv-field-label">Proposal</div><div class="rv-field-value" style="white-space:pre-line">{{ $partnership->message }}</div></div>@endif
                    <div class="rv-field"><div class="rv-field-label">Document</div><div class="rv-field-value {{ !$partnership->document?'empty':'' }}">@if($partnership->document)<a href="{{ asset($partnership->document) }}" target="_blank" style="display:inline-flex;align-items:center;gap:6px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>View document</a>@else Not uploaded @endif</div></div>
                    <div class="rv-field"><div class="rv-field-label">Submitted</div><div class="rv-field-value">{{ $partnership->created_at->format('d M Y, h:i A') }}</div></div>
                </div>
                @if($partnership->status === 'pending')
                <div class="rv-section">What happens next</div>
                <div class="timeline">
                    <div class="tl-item"><div class="tl-dot tl-done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></div><div class="tl-content"><div class="tl-title">Request submitted</div><div class="tl-desc">Received on {{ $partnership->created_at->format('d M Y') }}.</div></div></div>
                    <div class="tl-item"><div class="tl-dot tl-active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><div class="tl-content"><div class="tl-title">Under review <span class="tl-inprogress">In progress</span></div><div class="tl-desc">Our team is reviewing your documents. Typically 1–2 business days.</div></div></div>
                    <div class="tl-item"><div class="tl-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div><div class="tl-content"><div class="tl-title">Decision email</div><div class="tl-desc">Sent to <strong>{{ $partnership->email }}</strong> once complete.</div></div></div>
                    <div class="tl-item"><div class="tl-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><div class="tl-content"><div class="tl-title">Onboarding call</div><div class="tl-desc">If approved, a relationship manager will schedule your onboarding.</div></div></div>
                </div>
                @endif
                @if($partnership->status === 'approved')
                <div class="rv-section">Your next steps</div>
                <div class="timeline">
                    <div class="tl-item"><div class="tl-dot tl-done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></div><div class="tl-content"><div class="tl-title">Check your email</div><div class="tl-desc">Onboarding instructions sent to <strong>{{ $partnership->email }}</strong>.</div></div></div>
                    <div class="tl-item"><div class="tl-dot tl-active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.77 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 17z"/></svg></div><div class="tl-content"><div class="tl-title">Schedule call <span class="tl-action">Action needed</span></div><div class="tl-desc">Your relationship manager will reach out for a 30-min onboarding session.</div></div></div>
                    <div class="tl-item"><div class="tl-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div><div class="tl-content"><div class="tl-title">Sign partnership agreement</div><div class="tl-desc">A digital agreement activates your full partner dashboard.</div></div></div>
                </div>
                @endif
            </div>
        </div>

    @else

        {{-- ── FORM STATE ── --}}
        <div class="stepper-wrap">
            <div class="stepper-item s-done">
                <div class="stepper-dot s-done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px"><path d="M20 6L9 17l-5-5"/></svg></div>
                <span class="stepper-label s-done">Your Info</span>
            </div>
            <div class="stepper-item">
                <div class="stepper-dot s-active">2</div>
                <span class="stepper-label s-active">Organisation</span>
            </div>
            <div class="stepper-item">
                <div class="stepper-dot">3</div>
                <span class="stepper-label">Review</span>
            </div>
        </div>

        <div class="form-card">
            <div class="card-header-bar">
                <div class="step-badge">Partnership Request</div>
                <div class="step-heading">Tell us about yourself</div>
                <p class="step-sub">Provide your contact and organisation details so we can reach out.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('partnership.store') }}" method="POST" enctype="multipart/form-data" id="partnerForm">
@csrf

<div class="field-stack">

    {{-- CONTACT --}}
    <div class="section-title">Contact information</div>

    <div class="field-grid">
        <div class="field-wrap">
            <label class="field-label">Full name *</label>
            <input type="text" name="name" class="field-input" value="{{ old('name') }}" required>
        </div>

        <div class="field-wrap">
            <label class="field-label">Email *</label>
            <input type="email" name="email" class="field-input" value="{{ old('email') }}" required>
        </div>
    </div>

    <div class="field-wrap">
        <label class="field-label">Phone *</label>
        <input type="text" name="phone" class="field-input" value="{{ old('phone') }}" required>
    </div>


    {{-- ORGANIZATION --}}
    <div class="section-title">Organisation details</div>

    <div class="field-grid">
        <div class="field-wrap">
            <label class="field-label">Organisation name *</label>
            <input type="text" name="organization_name" class="field-input" value="{{ old('organization_name') }}" required>
        </div>

        <div class="field-wrap">
            <label class="field-label">Website</label>
            <input type="url" name="website" class="field-input" value="{{ old('website') }}">
        </div>
    </div>

    {{-- 🔥 NEW --}}
    <div class="field-grid">
        <div class="field-wrap">
            <label class="field-label">Organisation Type</label>
            <select name="organization_type" class="field-input">
                <option value="">Select</option>
                <option value="ngo">NGO</option>
                <option value="company">Company</option>
                <option value="startup">Startup</option>
                <option value="individual">Individual</option>
            </select>
        </div>

        <div class="field-wrap">
            <label class="field-label">Team Size</label>
            <select name="organization_size" class="field-input">
                <option value="">Select</option>
                <option value="1-10">1-10</option>
                <option value="10-50">10-50</option>
                <option value="50-200">50-200</option>
                <option value="200+">200+</option>
            </select>
        </div>
    </div>

    <div class="field-wrap">
        <label class="field-label">Location</label>
        <input type="text" name="location" class="field-input" placeholder="City, Country">
    </div>


    {{-- PARTNERSHIP TYPE --}}
    <div class="section-title">Partnership type</div>

<select name="partnership_type" class="field-input" required>
    <option value="">Select type</option>
    <option value="csr" {{ old('partnership_type') == 'csr' ? 'selected' : '' }}>CSR</option>
    <option value="event" {{ old('partnership_type') == 'event' ? 'selected' : '' }}>Event</option>
    <option value="product" {{ old('partnership_type') == 'product' ? 'selected' : '' }}>Product</option>
    <option value="corporate" {{ old('partnership_type') == 'corporate' ? 'selected' : '' }}>Corporate</option>
    <option value="media" {{ old('partnership_type') == 'media' ? 'selected' : '' }}>Media</option>
    <option value="other" {{ old('partnership_type') == 'other' ? 'selected' : '' }}>Other</option>
</select>


    {{--  NEW INTENT --}}
    <div class="section-title">Partnership goal</div>

    <div class="field-grid">
        <div class="field-wrap">
            <label class="field-label">Goal</label>
            <select name="goal" class="field-input">
                <option value="">Select</option>
                <option value="funding">Funding</option>
                <option value="collaboration">Collaboration</option>
                <option value="listing">Platform Listing</option>
                <option value="csr">CSR</option>
            </select>
        </div>

        <div class="field-wrap">
            <label class="field-label">Timeline</label>
            <select name="timeline" class="field-input">
                <option value="">Select</option>
                <option value="immediate">Immediate</option>
                <option value="1_month">1 Month</option>
                <option value="flexible">Flexible</option>
            </select>
        </div>
    </div>


    {{-- PROPOSAL --}}
    <div class="section-title">Proposal</div>

    <div class="field-wrap">
        <textarea name="message" class="field-input" rows="5"
        placeholder="Describe your partnership idea...">{{ old('message') }}</textarea>
    </div>


    {{-- DOCUMENT --}}
    <div class="field-wrap">
        <label class="field-label">Upload Document</label>
        <input type="file" name="document" class="field-input">
    </div>


    {{-- SUBMIT --}}
    <div class="form-nav">
        <button type="submit" class="btn-submit">
            Submit Partnership Request
        </button>
    </div>

</div>
</form>
            </div>
        </div>

    @endif

</main>

{{-- ══ RIGHT ══ --}}
<aside class="pg-right">
    <div class="testimonial-card">
        <div class="quote-mark">"</div>
        <p class="testimonial-text">Partnering with DonateBazar gave our CSR program real credibility. The impact reports are detailed and our employees feel genuinely proud of where the money goes.</p>
        <div class="testimonial-author">
            <div class="t-avatar">R</div>
            <div><div class="t-name">Riya Menon</div><div class="t-role">CSR Head, Infosys Foundation</div></div>
        </div>
    </div>
    <div class="testimonial-card">
        <div class="quote-mark">"</div>
        <p class="testimonial-text">The onboarding was seamless. Within a week, our donation drive was live and we could track exactly where every rupee was going in real time.</p>
        <div class="testimonial-author">
            <div class="t-avatar" style="background:linear-gradient(135deg,#059669,#10b981)">A</div>
            <div><div class="t-name">Arjun Kapoor</div><div class="t-role">Director, Tata Trusts</div></div>
        </div>
    </div>
    <div class="faq-card">
        <div class="faq-header"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Common Questions</div>
        <div class="faq-item"><div class="faq-q"><span class="faq-q-badge">Q</span> How long does approval take?</div><div class="faq-a">Our team reviews applications within 2 business days and schedules a call to discuss next steps.</div></div>
        <div class="faq-item"><div class="faq-q"><span class="faq-q-badge">Q</span> Is there a minimum commitment?</div><div class="faq-a">No. Partnerships are flexible — you define the scope, timeline, and contribution level.</div></div>
        <div class="faq-item"><div class="faq-q"><span class="faq-q-badge">Q</span> What do we get in return?</div><div class="faq-a">Co-branding, impact certificates, quarterly reports, and a dedicated relationship manager.</div></div>
        <div class="faq-item"><div class="faq-q"><span class="faq-q-badge">Q</span> Is my data secure?</div><div class="faq-a">All submissions are encrypted. We never share partner data with third parties.</div></div>
    </div>
</aside>

</div>
</div>

<script>
(function(){
'use strict';

/* ═══════════════════════════════════════
   TOAST ENGINE
═══════════════════════════════════════ */
var stack = document.getElementById('toastStack');

function toast(opts){
    /* opts: { type, title, message, duration } */
    var type     = opts.type    || 'info';
    var title    = opts.title   || '';
    var message  = opts.message || '';
    var duration = opts.duration || 5000;

    var icons = {
        success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>',
        error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        info:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
    };

    var t = document.createElement('div');
    t.className = 'toast toast-' + type;
    t.style.setProperty('--toast-dur', (duration/1000) + 's');
    t.setAttribute('role','alert');
    t.innerHTML =
        '<div class="toast-icon">' + (icons[type]||icons.info) + '</div>' +
        '<div class="toast-body">' +
            (title   ? '<div class="toast-title">'+ title   +'</div>' : '') +
            (message ? '<div class="toast-msg">'  + message +'</div>' : '') +
        '</div>' +
        '<button class="toast-close" aria-label="Dismiss">✕</button>';

    t.querySelector('.toast-close').addEventListener('click', function(){ dismiss(t); });
    stack.appendChild(t);

    var timer = setTimeout(function(){ dismiss(t); }, duration);
    t._timer = timer;

    /* pause on hover */
    t.addEventListener('mouseenter', function(){ clearTimeout(t._timer); t.style.setProperty('--toast-dur','0s'); t.style.animationPlayState='paused'; });
    t.addEventListener('mouseleave', function(){ t._timer = setTimeout(function(){ dismiss(t); }, 2000); });
}

function dismiss(t){
    if (!t.parentNode) return;
    t.classList.add('dismissing');
    setTimeout(function(){ if(t.parentNode) t.parentNode.removeChild(t); }, 320);
}

/* expose globally */
window._toast = toast;

/* ═══════════════════════════════════════
   FLASH MESSAGES FROM SERVER
═══════════════════════════════════════ */
@if(session('success'))
    setTimeout(function(){
        toast({ type:'success', title:'Request Submitted!', message: @json(session('success')), duration:6000 });
    }, 300);
@endif

@if(session('error'))
    setTimeout(function(){
        toast({ type:'error', title:'Something went wrong', message: @json(session('error')), duration:7000 });
    }, 300);
@endif

@if($errors->any())
    setTimeout(function(){
        toast({
            type: 'error',
            title: 'Please fix {{ $errors->count() }} error{{ $errors->count() > 1 ? "s" : "" }}',
            message: 'Check the form fields highlighted below.',
            duration: 8000
        });
    }, 300);
@endif

/* ═══════════════════════════════════════
   FORM SUBMIT — loading state + validation toast
═══════════════════════════════════════ */
var form      = document.getElementById('partnerForm');
var submitBtn = document.getElementById('submitBtn');
var submitTxt = document.getElementById('submitBtnText');

if (form) {
    form.addEventListener('submit', function(e){

        /* ── Client-side required check ── */
        var required = ['name','email','phone'];
        var missing  = [];
        required.forEach(function(id){
            var el = document.getElementById(id);
            if (el && !el.value.trim()) {
                el.classList.add('is-error');
                missing.push(el.previousElementSibling
                    ? el.previousElementSibling.textContent.replace('*','').trim()
                    : id);
            } else if (el) {
                el.classList.remove('is-error');
            }
        });

        /* partnership type check */
        // var ptSelected = form.querySelector('input[name="partnership_type"]:checked');
        // if (!ptSelected) missing.push('Partnership type');

             var ptSelected = form.querySelector('select[name="partnership_type"]');

             if (!ptSelected || ptSelected.value.trim() === '') {
             if (ptSelected) {
             ptSelected.classList.add('is-error');
             }

             missing.push('Partnership type');
             } else 

             {

             ptSelected.classList.remove('is-error');


             }

        /* message check */
        var msg = document.getElementById('f_message');
        if (msg && !msg.value.trim()) {
            msg.classList.add('is-error');
            missing.push('Proposal');
        }

        if (missing.length > 0) {
            e.preventDefault();
            toast({
                type: 'warning',
                title: 'Required fields missing',
                message: missing.join(', '),
                duration: 6000
            });


            /* scroll to first error */
            var firstErr = form.querySelector('.is-error');
            if (firstErr) firstErr.scrollIntoView({ behavior:'smooth', block:'center' });
            return;

        }

        /* ── File size check ── */

        var docInput = document.getElementById('docInput');
        if (docInput && docInput.files[0] && docInput.files[0].size > 2 * 1024 * 1024) {
            e.preventDefault();
            toast({ type:'error', title:'File too large', message:'Please upload a document under 2MB.', duration:6000 });
            return;
        }

        /* ── All good: show loading state ── */

        submitBtn.disabled = true;
        submitBtn.innerHTML =
            '<div class="btn-spinner"></div>' +
            '<span>Submitting…</span>';

        toast({
            type: 'info',
            title: 'Sending your request…',
            message: 'Please wait while we process your submission.',
            duration: 10000
        });
    });

    /* Remove error highlight on input */

    form.querySelectorAll('.field-input').forEach(function(el){
        el.addEventListener('input', function(){
            this.classList.remove('is-error');
        });
    });
}

/* ═══════════════════════════════════════
   CHAR COUNTER
═══════════════════════════════════════ */
window.updateCharCount = function(el, max, counterId){
    var len   = el.value.length;
    var el2   = document.getElementById(counterId);
    if (!el2) return;
    el2.textContent = len + ' / ' + max;
    el2.className   = 'char-count';
    if (len > max * 0.9) el2.classList.add('warn');
    if (len >= max)      el2.classList.add('over');
};

/* init on load */

var msgEl = document.getElementById('f_message');
if (msgEl) updateCharCount(msgEl, 1000, 'msgCount');

/* ═══════════════════════════════════════
   FILE UPLOAD
═══════════════════════════════════════ */

var docInput = document.getElementById('docInput');
if (docInput) {
    docInput.addEventListener('change', function(){
        var file = this.files[0];
        if (!file) return;

        /* size guard */
        if (file.size > 2 * 1024 * 1024) {
            toast({ type:'error', title:'File too large', message: file.name + ' exceeds 2MB limit.', duration:6000 });
            this.value = '';
            return;
        }

        document.getElementById('uploadPrompt').style.display  = 'none';
        document.getElementById('uploadFname').textContent      = file.name;
        document.getElementById('uploadFsize').textContent      = (file.size / 1024).toFixed(0) + ' KB';
        document.getElementById('uploadSuccess').classList.add('show');
        document.getElementById('uploadZone').classList.add('has-file');

        toast({ type:'success', title:'File attached', message: file.name + ' is ready to upload.', duration:4000 });
    });
}

window.clearFile = function(e){
    e.stopPropagation();
    var inp = document.getElementById('docInput');
    if (inp) inp.value = '';
    document.getElementById('uploadPrompt').style.display = '';
    document.getElementById('uploadSuccess').classList.remove('show');
    document.getElementById('uploadZone').classList.remove('has-file');
};

})();
</script>

@endsection