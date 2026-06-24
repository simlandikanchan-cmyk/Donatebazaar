@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap');

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}

:root{
  --purple-deep:#4c1d95;--purple-main:#7c3aed;--purple-mid:#8b5cf6;
  --purple-light:#a78bfa;--purple-pale:#ede9fe;--purple-mist:#f5f3ff;
  --indigo-main:#4f46e5;--indigo-light:#c7d2fe;--indigo-pale:#e0e7ff;
  --white:#ffffff;--ink:#1e1b4b;--ink-mid:#3730a3;--ink-soft:#6d6aaf;
  --ink-muted:#a5a3c8;--border:#ddd6fe;--surface:#faf9ff;
  --danger:#e24b4a;--danger-bg:#fef2f2;
  --amber:#d97706;--amber-bg:#fffbeb;--amber-border:#fcd34d;
}

body{font-family:'DM Sans',sans-serif;}

.page-shell{
  min-height:100vh;
  background:radial-gradient(ellipse 80% 60% at 20% 0%,rgba(167,139,250,.22) 0%,transparent 60%),
             radial-gradient(ellipse 60% 50% at 80% 100%,rgba(99,102,241,.18) 0%,transparent 55%),
             linear-gradient(160deg,#f0ebff 0%,#eef2ff 50%,#f3e8ff 100%);
  padding:48px 16px 80px;position:relative;overflow:hidden;
}
.page-shell::before,.page-shell::after{content:'';position:fixed;border-radius:50%;filter:blur(80px);pointer-events:none;z-index:0;}
.page-shell::before{width:420px;height:420px;top:-100px;left:-120px;background:rgba(139,92,246,.14);}
.page-shell::after{width:360px;height:360px;bottom:-80px;right:-100px;background:rgba(99,102,241,.12);}

.shell-inner{position:relative;z-index:1;max-width:720px;margin:0 auto;}

.page-header{text-align:center;margin-bottom:40px;}
.page-eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.2);border-radius:100px;padding:6px 16px;font-size:12px;font-weight:500;letter-spacing:.08em;color:var(--purple-main);text-transform:uppercase;margin-bottom:20px;}
.page-eyebrow span{width:6px;height:6px;border-radius:50%;background:var(--purple-main);display:inline-block;animation:pulse-dot 2s ease infinite;}
@keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.7)}}
.page-title{font-family:'DM Mono',monospace;font-size:clamp(2rem,3vw,3rem);font-weight:600;background:linear-gradient(135deg,var(--purple-deep) 0%,var(--purple-main) 50%,var(--indigo-main) 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.2;margin-bottom:12px;}
.page-subtitle{font-size:15px;color:var(--ink-soft);font-weight:300;line-height:1.6;}

.stepper-wrap{display:flex;align-items:flex-start;justify-content:center;margin-bottom:32px;position:relative;}
.stepper-item{display:flex;flex-direction:column;align-items:center;flex:1;max-width:100px;position:relative;}
.stepper-item:not(:last-child)::after{content:'';position:absolute;top:17px;left:calc(50% + 17px);width:calc(100% - 34px);height:2px;background:#b3a2ff;transition:background .4s;}
.stepper-item.done:not(:last-child)::after{background:linear-gradient(90deg,var(--purple-main),var(--purple-light));}
.stepper-dot{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;border:2px solid var(--border);background:var(--white);color:#7e66db;transition:all .35s cubic-bezier(.4,0,.2,1);position:relative;z-index:1;}
.stepper-dot.active{background:var(--white);border-color:var(--purple-main);color:var(--purple-main);box-shadow:0 0 0 5px rgba(124,58,237,.12),0 4px 12px rgba(124,58,237,.2);transform:scale(1.08);}
.stepper-dot.done{background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));border-color:var(--purple-main);color:var(--white);box-shadow:0 4px 12px rgba(124,58,237,.35);}
.stepper-label{font-size:10px;font-weight:400;color:var(--ink-muted);margin-top:8px;letter-spacing:.04em;text-align:center;transition:color .3s;}
.stepper-label.active{color:var(--purple-main);font-weight:600;}
.stepper-label.done{color:var(--ink-soft);}

.progress-track{height:3px;background:var(--indigo-pale);overflow:hidden;}
.progress-fill{height:100%;background:linear-gradient(90deg,var(--purple-main),var(--purple-mid),var(--indigo-main));border-radius:0 3px 3px 0;transition:width .5s cubic-bezier(.4,0,.2,1);}

.form-card{background:rgba(255,255,255,.88);backdrop-filter:blur(24px) saturate(1.4);-webkit-backdrop-filter:blur(24px) saturate(1.4);border-radius:28px;border:1px solid rgba(255,255,255,.75);box-shadow:0 2px 0 rgba(124,58,237,.06) inset,0 30px 80px rgba(109,40,217,.1),0 8px 24px rgba(99,102,241,.08);overflow:hidden;}
.card-header-bar{padding:28px 36px 24px;border-bottom:1px solid var(--indigo-pale);background:linear-gradient(to right,rgba(245,243,255,.8),rgba(238,242,255,.8));}
.step-badge{display:inline-block;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--purple-main);background:var(--purple-pale);padding:3px 10px;border-radius:100px;margin-bottom:10px;}
.step-heading{font-family:'DM Mono',monospace;font-size:1.4rem;text-transform:capitalize;font-weight:600;color:var(--ink);margin-bottom:4px;}
.step-sub{font-size:13px;color:var(--ink-soft);font-weight:300;}

.card-body{padding:32px 36px 36px;}

.step-panel{display:none;}
.step-panel.active{display:block;animation:slideIn .38s cubic-bezier(.4,0,.2,1);}
@keyframes slideIn{from{opacity:0;transform:translateX(18px)}to{opacity:1;transform:translateX(0)}}

.field-stack{display:flex;flex-direction:column;gap:22px;}
.field-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
.field-grid-3{display:grid;grid-template-columns:2fr 1fr 1fr;gap:14px;}
@media(max-width:560px){.field-grid,.field-grid-3{grid-template-columns:1fr;}}

.field-wrap{display:flex;flex-direction:column;gap:7px;}
.field-label{font-size:12px;font-weight:600;color:var(--ink-soft);letter-spacing:.07em;text-transform:uppercase;}
.field-label span{color:var(--purple-main);margin-left:2px;}
.field-hint{font-size:11px;color:var(--ink-muted);}
.char-counter{font-size:11px;color:var(--ink-muted);text-align:right;}

.field-input{width:100%;padding:13px 16px;border:1.5px solid var(--border);border-radius:14px;background:var(--surface);font-family:'DM Sans',sans-serif;font-size:14px;font-weight:400;color:var(--ink);outline:none;transition:border-color .2s,box-shadow .2s,background .2s;-webkit-appearance:none;}
.field-input:hover{border-color:var(--purple-light);}
.field-input:focus{border-color:var(--purple-main);box-shadow:0 0 0 4px rgba(124,58,237,.12);background:var(--white);}
.field-input::placeholder{color:var(--ink-muted);font-weight:300;}

.input-prefix-wrap{position:relative;}
.input-prefix{position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:15px;color:var(--purple-light);font-weight:500;pointer-events:none;}
.input-prefix-wrap .field-input{padding-left:32px;}

textarea.field-input{resize:vertical;min-height:100px;line-height:1.6;}

.toggle-set{display:flex;flex-direction:column;gap:12px;}
.toggle-card{display:flex;align-items:center;gap:14px;padding:16px 18px;border:1.5px solid var(--border);border-radius:16px;background:var(--surface);cursor:pointer;transition:border-color .2s,background .2s,box-shadow .2s;user-select:none;}
.toggle-card:hover{border-color:var(--purple-light);background:var(--purple-mist);}
.toggle-card:has(input:checked){border-color:var(--purple-main);background:var(--white);box-shadow:0 0 0 3px rgba(124,58,237,.08);}
.toggle-icon{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:var(--purple-pale);flex-shrink:0;transition:background .2s;}
.toggle-card:has(input:checked) .toggle-icon{background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));}
.toggle-icon svg{width:18px;height:18px;color:var(--purple-main);transition:color .2s;}
.toggle-card:has(input:checked) .toggle-icon svg{color:white;}
.toggle-text{flex:1;}
.toggle-title{font-size:14px;font-weight:500;color:var(--ink);}
.toggle-desc{font-size:12px;color:var(--ink-soft);margin-top:2px;}
.toggle-track{width:42px;height:24px;background:var(--border);border-radius:12px;position:relative;transition:background .2s;flex-shrink:0;}
.toggle-card:has(input:checked) .toggle-track{background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));}
.toggle-thumb{position:absolute;top:4px;left:4px;width:16px;height:16px;background:var(--white);border-radius:50%;transition:transform .22s cubic-bezier(.4,0,.2,1);box-shadow:0 2px 4px rgba(109,40,217,.2);}
.toggle-card:has(input:checked) .toggle-thumb{transform:translateX(18px);}
.toggle-input{display:none;}

.section-title{font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);display:flex;align-items:center;gap:10px;}
.section-title::after{content:'';flex:1;height:1px;background:var(--indigo-pale);}

.upload-zone{border:2px dashed var(--border);border-radius:20px;padding:48px 32px;text-align:center;background:var(--purple-mist);cursor:pointer;transition:border-color .25s,background .25s;position:relative;overflow:hidden;}
.upload-zone::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at center,rgba(167,139,250,.1) 0%,transparent 70%);pointer-events:none;}
.upload-zone:hover{border-color:var(--purple-main);background:var(--purple-pale);}
.upload-zone.required-error{border-color:var(--danger);background:var(--danger-bg);}
.upload-zone input{display:none;}
.upload-icon{width:64px;height:64px;border-radius:20px;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));display:flex;align-items:center;justify-content:center;margin:0 auto 16px;box-shadow:0 8px 24px rgba(124,58,237,.3);}
.upload-icon svg{width:28px;height:28px;color:white;}
.upload-title{font-size:15px;font-weight:500;color:var(--ink);margin-bottom:6px;}
.upload-hint{font-size:12px;color:var(--ink-muted);}
.upload-btn{display:inline-block;margin-top:16px;padding:8px 20px;border-radius:100px;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.25);color:var(--purple-main);font-size:13px;font-weight:500;transition:background .2s;}
.upload-zone:hover .upload-btn{background:rgba(124,58,237,.2);}
#imagePreview{display:none;}
#imagePreview.show{display:block;}
#previewImg{width:100%;max-height:240px;object-fit:cover;border-radius:14px;border:2px solid var(--border);}
#fileName{font-size:12px;color:var(--ink-soft);margin-top:10px;}
.change-img-btn{display:inline-block;margin-top:12px;font-size:12px;color:var(--purple-main);background:var(--purple-pale);padding:5px 14px;border-radius:100px;cursor:pointer;}

/* UPDATES */
.updates-info-bar{display:flex;align-items:flex-start;gap:14px;padding:16px 18px;background:var(--purple-mist);border:1px solid var(--border);border-radius:16px;margin-bottom:20px;}
.updates-info-icon{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.updates-info-icon svg{width:17px;height:17px;}
.updates-info-text .title{font-size:13px;font-weight:600;color:var(--ink);margin-bottom:3px;}
.updates-info-text .sub{font-size:12px;color:var(--ink-soft);line-height:1.6;}
.update-entries{display:flex;flex-direction:column;gap:12px;margin-bottom:14px;}
.update-entry{background:var(--surface);border:1.5px solid var(--border);border-radius:16px;padding:18px 20px;transition:border-color .2s,box-shadow .2s;animation:slideIn .3s ease;}
.update-entry:hover{border-color:var(--purple-light);box-shadow:0 4px 16px rgba(124,58,237,.08);}
.update-entry-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;}
.update-entry-num{display:inline-flex;align-items:center;gap:8px;font-size:12px;font-weight:600;color:var(--purple-main);background:var(--purple-pale);padding:5px 14px;border-radius:100px;}
.update-entry-num svg{width:13px;height:13px;}
.remove-update-btn{width:32px;height:32px;border-radius:50%;border:1.5px solid #fecaca;background:#fef2f2;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .2s,border-color .2s;flex-shrink:0;}
.remove-update-btn:hover{background:#fee2e2;border-color:var(--danger);}
.remove-update-btn svg{width:13px;height:13px;color:var(--danger);}
.doc-attach-row{display:flex;align-items:center;gap:10px;margin-top:4px;}
.doc-attach-label{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border:1.5px dashed var(--border);border-radius:12px;font-size:12px;font-weight:500;color:var(--ink-soft);cursor:pointer;background:var(--white);transition:.2s;}
.doc-attach-label:hover{border-color:var(--purple-main);color:var(--purple-main);}
.doc-attach-label svg{width:14px;height:14px;}
.doc-filename{font-size:12px;color:var(--ink-soft);font-style:italic;}
.doc-preview-tag{display:inline-flex;align-items:center;gap:6px;background:var(--purple-pale);color:var(--purple-deep);padding:4px 12px;border-radius:100px;font-size:11px;font-weight:500;}
.add-update-btn{display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:15px;border:2px dashed var(--border);border-radius:16px;background:transparent;color:var(--purple-main);font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;cursor:pointer;transition:border-color .2s,background .2s;}
.add-update-btn:hover{border-color:var(--purple-main);background:var(--purple-mist);}
.add-update-btn svg{width:18px;height:18px;}

/* PRODUCTS */
.suggestions-label{font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-soft);margin-bottom:10px;}
.suggestions-wrap{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px;}
.suggestion-pill{display:inline-flex;align-items:center;gap:8px;padding:6px 14px 6px 6px;border-radius:100px;border:1.5px solid var(--border);background:var(--white);font-size:12px;font-weight:500;color:var(--ink);cursor:pointer;transition:background .15s,border-color .15s;font-family:'DM Sans',sans-serif;}
.suggestion-pill:hover{background:var(--purple-pale);border-color:var(--purple-main);color:var(--purple-deep);}
.suggestion-pill-img{width:26px;height:26px;border-radius:50%;object-fit:cover;border:1px solid var(--border);flex-shrink:0;}
.suggestion-pill-placeholder{width:26px;height:26px;border-radius:50%;background:var(--purple-pale);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.suggestion-pill-placeholder svg{width:12px;height:12px;color:var(--purple-main);}

.product-list{display:flex;flex-direction:column;gap:16px;margin-bottom:16px;}
.product-item{background:var(--surface);border:1.5px solid var(--border);border-radius:20px;padding:20px 22px;transition:border-color .2s,box-shadow .2s;animation:slideIn .3s ease;}
.product-item:hover{border-color:var(--purple-light);box-shadow:0 4px 16px rgba(124,58,237,.08);}
.product-item-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;}
.product-item-num{display:inline-flex;align-items:center;gap:8px;font-size:12px;font-weight:600;color:var(--purple-main);background:var(--purple-pale);padding:5px 14px;border-radius:100px;}
.product-item-num svg{width:13px;height:13px;}
.remove-product-btn{width:32px;height:32px;border-radius:50%;border:1.5px solid #fecaca;background:#fef2f2;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .2s,border-color .2s;flex-shrink:0;}
.remove-product-btn:hover{background:#fee2e2;border-color:var(--danger);}
.remove-product-btn svg{width:13px;height:13px;color:var(--danger);}
.product-img-preview{width:72px;height:72px;border-radius:12px;object-fit:cover;border:1px solid var(--border);margin-bottom:14px;display:block;}
.product-subtotal-row{display:flex;justify-content:space-between;align-items:center;margin-top:14px;padding-top:12px;border-top:1px dashed var(--border);}
.product-subtotal-label{font-size:12px;color:var(--ink-soft);}
.product-subtotal-value{font-size:14px;font-weight:600;color:var(--purple-main);}
.grand-total-card{display:flex;justify-content:space-between;align-items:center;padding:18px 22px;margin-top:16px;background:linear-gradient(135deg,var(--purple-pale),var(--indigo-pale));border:1.5px solid var(--purple-light);border-radius:18px;display:none;}
.grand-total-left{font-size:13px;font-weight:500;color:var(--ink-mid);}
.grand-total-sub{font-size:11px;font-weight:400;color:var(--ink-soft);margin-top:2px;}
.grand-total-amount{font-size:24px;font-weight:700;color:var(--purple-deep);}
.add-product-btn{display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:15px;border:2px dashed var(--border);border-radius:16px;background:transparent;color:var(--purple-main);font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;cursor:pointer;transition:border-color .2s,background .2s;}
.add-product-btn:hover{border-color:var(--purple-main);background:var(--purple-mist);}
.add-product-btn svg{width:18px;height:18px;}

/* REVIEW */
.review-card{border-radius:18px;border:1px solid var(--indigo-pale);overflow:hidden;margin-bottom:16px;}
.review-card-header{padding:14px 20px;background:linear-gradient(135deg,var(--purple-mist),var(--indigo-pale));border-bottom:1px solid var(--indigo-pale);}
.review-card-title{font-size:12px;font-weight:600;color:var(--ink-mid);text-transform:uppercase;letter-spacing:.08em;}
.review-row{display:flex;justify-content:space-between;align-items:flex-start;padding:13px 20px;border-bottom:1px solid rgba(224,231,255,.6);gap:12px;}
.review-row:last-child{border-bottom:none;}
.review-label{font-size:12px;color:var(--ink-soft);font-weight:500;flex-shrink:0;}
.review-value{font-size:13px;color:var(--ink);font-weight:500;text-align:right;word-break:break-word;}
.review-product-pill{display:inline-flex;align-items:center;gap:6px;background:var(--purple-pale);color:var(--purple-deep);padding:5px 12px;border-radius:100px;font-size:12px;font-weight:500;margin:4px 4px 4px 0;}
.review-update-pill{display:inline-flex;align-items:center;gap:6px;background:#e0e7ff;color:#3730a3;padding:5px 12px;border-radius:100px;font-size:12px;font-weight:500;margin:4px 4px 4px 0;}
.review-products-body,.review-updates-body{padding:14px 20px;}
.review-notice{background:linear-gradient(135deg,var(--purple-pale),var(--indigo-pale));border:1px solid var(--indigo-light);border-radius:16px;padding:18px 20px;display:flex;align-items:flex-start;gap:14px;margin-top:16px;}
.review-notice-icon{width:34px;height:34px;border-radius:10px;background:rgba(124,58,237,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.review-notice-icon svg{width:16px;height:16px;color:var(--purple-main);}
.review-notice-text{font-size:13px;color:var(--ink-soft);line-height:1.7;}
.grand-summary-card{border-radius:20px;border:2px solid var(--purple-main);overflow:hidden;margin-top:20px;background:linear-gradient(135deg,var(--purple-mist),var(--indigo-pale));}
.grand-summary-header{padding:14px 20px;background:linear-gradient(135deg,var(--purple-pale),var(--indigo-pale));border-bottom:1px solid var(--indigo-light);font-size:12px;font-weight:600;color:var(--ink-mid);text-transform:uppercase;letter-spacing:.08em;display:flex;align-items:center;gap:8px;}
.grand-summary-header svg{width:14px;height:14px;color:var(--purple-main);}
.grand-summary-row{display:flex;justify-content:space-between;align-items:center;padding:13px 20px;border-bottom:1px solid rgba(224,231,255,.7);font-size:13px;}
.grand-summary-row:last-child{border-bottom:none;}
.grand-summary-row .lbl{color:var(--ink-soft);}
.grand-summary-row .val{font-weight:600;color:var(--ink);}
.grand-summary-total-row{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));}
.grand-summary-total-row .lbl{font-size:13px;font-weight:600;color:rgba(255,255,255,.85);}
.grand-summary-total-row .val{font-size:22px;font-weight:700;color:#fff;}

/* ── KYC BRIDGE STEP 7 ── */
.kyc-bridge-icon{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));display:flex;align-items:center;justify-content:center;margin:0 auto 24px;box-shadow:0 12px 32px rgba(124,58,237,.35);position:relative;}
.kyc-bridge-icon::after{content:'';position:absolute;inset:-6px;border-radius:50%;border:2px dashed rgba(124,58,237,.3);animation:spinRing 12s linear infinite;}
@keyframes spinRing{to{transform:rotate(360deg)}}
.kyc-bridge-icon svg{width:36px;height:36px;color:#fff;}
.kyc-bridge-title{font-family:'DM Mono',monospace;font-size:1.3rem;font-weight:600;color:var(--ink);margin-bottom:10px;text-align:center;}
.kyc-bridge-sub{font-size:13px;color:var(--ink-soft);line-height:1.7;text-align:center;max-width:420px;margin:0 auto 32px;}
.kyc-step-row{display:flex;align-items:center;gap:14px;padding:14px 18px;background:var(--purple-mist);border:1px solid var(--border);border-radius:14px;}
.kyc-step-num{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));color:#fff;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.kyc-step-title{font-size:13px;font-weight:600;color:var(--ink);}
.kyc-step-desc{font-size:12px;color:var(--ink-soft);margin-top:2px;}
.kyc-note{display:flex;align-items:flex-start;gap:10px;padding:14px 16px;background:rgba(224,231,255,.5);border:1px solid var(--indigo-light);border-radius:12px;font-size:12px;color:var(--ink-soft);line-height:1.6;margin-top:20px;}
.kyc-note svg{width:16px;height:16px;color:var(--indigo-main);flex-shrink:0;margin-top:1px;}

/* NAV */
.form-nav{display:flex;justify-content:space-between;align-items:center;margin-top:36px;padding-top:28px;border-top:1px solid var(--indigo-pale);}
.btn-back{display:inline-flex;align-items:center;gap:8px;background:transparent;color:var(--ink-soft);border:1.5px solid var(--border);padding:13px 22px;border-radius:14px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:400;cursor:pointer;transition:border-color .2s,color .2s,background .2s;}
.btn-back:hover{border-color:var(--purple-light);color:var(--purple-main);background:var(--purple-mist);}
.btn-back svg{width:15px;height:15px;}
.btn-next{display:inline-flex;align-items:center;gap:10px;background:linear-gradient(135deg,var(--purple-main) 0%,var(--indigo-main) 100%);color:var(--white);border:none;padding:14px 32px;border-radius:14px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;cursor:pointer;transition:opacity .2s,transform .2s,box-shadow .2s;box-shadow:0 6px 20px rgba(124,58,237,.4),0 2px 6px rgba(79,70,229,.3);letter-spacing:.02em;position:relative;overflow:hidden;}
.btn-next::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.15),transparent);border-radius:inherit;}
.btn-next:hover{opacity:.93;transform:translateY(-2px);box-shadow:0 10px 28px rgba(124,58,237,.45);}
.btn-next:active{transform:scale(.97);}
.btn-next svg{width:15px;height:15px;}

.skip-note{display:flex;align-items:flex-start;gap:10px;padding:14px 16px;margin-top:16px;background:rgba(224,231,255,.4);border:1px solid var(--indigo-light);border-radius:12px;font-size:12px;color:var(--ink-soft);line-height:1.6;}
.skip-note svg{width:16px;height:16px;color:var(--indigo-main);flex-shrink:0;margin-top:1px;}

.step-pill{font-size:12px;color:var(--ink-muted);background:var(--indigo-pale);padding:4px 14px;border-radius:100px;display:block;text-align:center;margin:20px auto 0;width:fit-content;}
.error-box{background:var(--danger-bg);border:1px solid rgba(226,75,74,.25);border-left:4px solid var(--danger);color:#991b1b;padding:14px 18px;border-radius:14px;font-size:13px;margin-bottom:24px;line-height:1.6;}
.error-box ul{padding-left:18px;}

.toast-alert{position:fixed;top:24px;left:50%;transform:translateX(-50%);padding:14px 24px;border-radius:14px;font-size:14px;z-index:9999;box-shadow:0 8px 24px rgba(76,29,149,.4);animation:toastIn .3s ease;font-family:'DM Sans',sans-serif;display:flex;align-items:center;gap:10px;max-width:420px;text-align:center;}
.toast-alert.purple{background:var(--purple-deep);color:#fff;}
@keyframes toastIn{from{opacity:0;transform:translateX(-50%) translateY(-10px)}to{opacity:1;transform:translateX(-50%) translateY(0)}}

/* SUCCESS */
.success-overlay{display:none;position:fixed;inset:0;background:rgba(30,27,75,.55);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);z-index:10000;align-items:center;justify-content:center;padding:20px;}
.success-overlay.show{display:flex;animation:overlayIn .35s ease;}
@keyframes overlayIn{from{opacity:0}to{opacity:1}}
.success-modal{background:#fff;border-radius:28px;border:1px solid rgba(255,255,255,.9);box-shadow:0 40px 100px rgba(76,29,149,.25),0 8px 32px rgba(99,102,241,.15);max-width:480px;width:100%;text-align:center;padding:48px 40px 40px;animation:modalIn .4s cubic-bezier(.34,1.56,.64,1);position:relative;overflow:hidden;}
.success-modal::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--purple-main),var(--purple-mid),var(--indigo-main));}
@keyframes modalIn{from{opacity:0;transform:scale(.88) translateY(24px)}to{opacity:1;transform:scale(1) translateY(0)}}
.success-icon-ring{width:88px;height:88px;border-radius:50%;background:linear-gradient(135deg,#ede9fe,#e0e7ff);border:2px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;position:relative;}
.success-icon-ring::after{content:'';position:absolute;inset:-6px;border-radius:50%;border:2px dashed rgba(124,58,237,.25);animation:spinRing 12s linear infinite;}
.success-icon-inner{width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(124,58,237,.4);}
.success-icon-inner svg{width:28px;height:28px;color:#fff;}
.success-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.2);border-radius:100px;padding:5px 14px;font-size:11px;font-weight:600;letter-spacing:.1em;color:var(--purple-main);text-transform:uppercase;margin-bottom:14px;}
.success-badge span{width:5px;height:5px;border-radius:50%;background:var(--purple-main);display:inline-block;}
.success-title{font-family:'DM Mono',monospace;font-size:1.75rem;font-weight:600;color:var(--ink);margin-bottom:10px;line-height:1.25;}
.success-subtitle{font-size:14px;color:var(--ink-soft);font-weight:300;line-height:1.7;margin-bottom:32px;}
.success-subtitle strong{color:var(--purple-main);font-weight:600;}
.success-steps{display:flex;background:var(--purple-mist);border:1px solid var(--border);border-radius:18px;overflow:hidden;margin-bottom:30px;text-align:left;}
.success-step{flex:1;padding:14px 16px;border-right:1px solid var(--border);display:flex;align-items:flex-start;gap:10px;}
.success-step:last-child{border-right:none;}
.success-step-num{width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));color:#fff;font-size:11px;font-weight:600;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.success-step-text{font-size:11px;color:var(--ink-soft);line-height:1.5;}
.success-step-text strong{display:block;font-size:12px;font-weight:600;color:var(--ink);margin-bottom:2px;}
.success-actions{display:flex;flex-direction:column;gap:12px;}
.btn-kyc-now{display:flex;align-items:center;justify-content:center;gap:10px;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));color:#fff;text-decoration:none;padding:15px 28px;border-radius:14px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;letter-spacing:.02em;box-shadow:0 6px 20px rgba(124,58,237,.4);transition:opacity .2s,transform .2s,box-shadow .2s;position:relative;overflow:hidden;border:none;cursor:pointer;width:100%;}
.btn-kyc-now::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.15),transparent);}
.btn-kyc-now:hover{opacity:.92;transform:translateY(-2px);box-shadow:0 10px 28px rgba(124,58,237,.45);}
.btn-close-popup{display:flex;align-items:center;justify-content:center;gap:8px;background:transparent;color:var(--ink-soft);border:1.5px solid var(--border);padding:13px 28px;border-radius:14px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:400;cursor:pointer;transition:border-color .2s,color .2s,background .2s;width:100%;}
.btn-close-popup:hover{border-color:var(--purple-light);color:var(--purple-main);background:var(--purple-mist);}
.success-confetti-row{display:flex;justify-content:center;gap:6px;margin-bottom:28px;}
.confetti-dot{width:8px;height:8px;border-radius:50%;animation:confettiBounce .6s ease infinite alternate;}
.confetti-dot:nth-child(1){background:#7c3aed;animation-delay:0s;}
.confetti-dot:nth-child(2){background:#4f46e5;animation-delay:.1s;}
.confetti-dot:nth-child(3){background:#a78bfa;animation-delay:.2s;}
.confetti-dot:nth-child(4){background:#6366f1;animation-delay:.3s;}
.confetti-dot:nth-child(5){background:#8b5cf6;animation-delay:.4s;}
@keyframes confettiBounce{from{transform:translateY(0)}to{transform:translateY(-8px)}}
</style>

{{-- SUCCESS POPUP --}}
<div class="success-overlay" id="successOverlay">
  <div class="success-modal">
    <div class="success-icon-ring">
      <div class="success-icon-inner">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
      </div>
    </div>
    <div class="success-badge"><span></span> Campaign submitted</div>
    <h2 class="success-title">Almost there!</h2>
    <p class="success-subtitle">Your campaign is saved. Now complete <strong>KYC verification</strong> to activate it and start receiving funds.</p>
    <div class="success-confetti-row">
      <div class="confetti-dot"></div><div class="confetti-dot"></div>
      <div class="confetti-dot"></div><div class="confetti-dot"></div><div class="confetti-dot"></div>
    </div>
    <div class="success-steps">
      <div class="success-step"><div class="success-step-num">1</div><div class="success-step-text"><strong>Submitted</strong>Campaign saved</div></div>
      <div class="success-step"><div class="success-step-num">2</div><div class="success-step-text"><strong>KYC Now</strong>Upload your ID</div></div>
      <div class="success-step"><div class="success-step-num">3</div><div class="success-step-text"><strong>Goes live</strong>After approval</div></div>
    </div>
    <div class="success-actions">
      {{-- This href will be set dynamically by JS after form submit gives us the campaign id --}}
      <button type="button" class="btn-kyc-now" id="btnKycNow" onclick="goToKyc()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        Complete KYC Verification
      </button>
      <button type="button" class="btn-close-popup" onclick="closeSuccessPopup()">Do it later from dashboard</button>
    </div>
  </div>
</div>

<div class="page-shell">
  <div class="shell-inner">

    <div class="page-header">
      <div class="page-eyebrow"><span></span> New Campaign</div>
      <h1 class="page-title">Launch Your Fundraiser</h1>
      <p class="page-subtitle">Share your story, set a goal, and start making an impact today.</p>
    </div>

    {{-- STEPPER: 6 steps (KYC removed — it's on its own page) --}}
    <div class="stepper-wrap">
      @foreach([['1','Basics'],['2','Details'],['3','Updates'],['4','Media'],['5','Products'],['6','Review']] as $i => [$num,$label])
      <div class="stepper-item {{ $i===0?'active':'' }}" id="sitem-{{ $num }}">
        <div class="stepper-dot {{ $i===0?'active':'' }}" id="dot-{{ $num }}">{{ $num }}</div>
        <span class="stepper-label {{ $i===0?'active':'' }}" id="label-{{ $num }}">{{ $label }}</span>
      </div>
      @endforeach
    </div>

    @if($errors->any())
    <div class="error-box">
      <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="form-card">
      <div class="progress-track">
        <div class="progress-fill" id="progressBar" style="width:16.6%;"></div>
      </div>

      <form action="{{ route('campaign.store') }}" method="POST" enctype="multipart/form-data" id="campaignForm">
        @csrf

        <div class="card-header-bar">
          <div class="step-badge" id="stepBadge">Step 1 of 6</div>
          <div class="step-heading" id="stepHeading">Campaign basics</div>
          <p class="step-sub" id="stepSub">Start with the essential information about your campaign.</p>
        </div>

        <div class="card-body">

          {{-- STEP 1: Basics --}}
          <div class="step-panel active" id="step-1">
            <div class="field-stack">
              <div class="field-wrap">
                <label class="field-label">Campaign title <span>*</span></label>
                <input type="text" name="title" class="field-input" value="{{ old('title') }}" placeholder="e.g. Help rebuild our community library" maxlength="100" id="titleInput">
                <div class="char-counter"><span id="titleCount">0</span> / 100</div>
              </div>
              <div class="field-wrap">
                <label class="field-label">Goal amount <span>*</span></label>
                <div class="input-prefix-wrap">
                  <span class="input-prefix">₹</span>
                  <input type="text" id="goalAmount" name="goal_amount" class="field-input" value="{{ old('goal_amount') }}" placeholder="5,00,000">
                </div>
                <p class="field-hint">Enter the total amount you need to raise in Indian Rupees</p>
              </div>
              <div class="field-wrap">
                <label class="field-label">Category <span>*</span></label>
                <select name="category_id" id="categorySelect" class="field-input">
                  <option value="">Select a category</option>
                  @foreach($categories as $category)
                  <option value="{{ $category->id }}" data-id="{{ $category->id }}" {{ old('category_id')==$category->id?'selected':'' }}>{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="field-wrap">
                <label class="field-label">Campaign description <span>*</span></label>
                <textarea name="description" class="field-input" rows="5" placeholder="Tell people why this campaign matters..." maxlength="1000" id="descInput">{{ old('description') }}</textarea>
                <div class="char-counter"><span id="descCount">0</span> / 1000</div>
              </div>
            </div>
          </div>

          {{-- STEP 2: Details --}}
          <div class="step-panel" id="step-2">
            <div class="field-stack">
              <div class="section-title">Location &amp; links</div>
              <div class="field-wrap">
                <label class="field-label">Location</label>
                <input type="text" name="location" class="field-input" value="{{ old('location') }}" placeholder="e.g. Mumbai, Maharashtra">
              </div>
              <div class="field-wrap">
                <label class="field-label">Video URL</label>
                <input type="url" name="video_url" class="field-input" value="{{ old('video_url') }}" placeholder="https://youtube.com/...">
                <p class="field-hint">A video can increase donations by up to 4×</p>
              </div>
              <div class="section-title">Campaign duration</div>
              <div class="field-grid">
                <div class="field-wrap">
                  <label class="field-label">Start date</label>
                  <input type="date" name="start_date" class="field-input" value="{{ old('start_date') }}">
                </div>
                <div class="field-wrap">
                  <label class="field-label">End date</label>
                  <input type="date" name="end_date" class="field-input" value="{{ old('end_date') }}">
                </div>
              </div>
              <div class="section-title">Campaign options</div>
              <div class="toggle-set">
                <label class="toggle-card">
                  <input type="checkbox" class="toggle-input" name="is_featured" {{ old('is_featured')?'checked':'' }}>
                  <div class="toggle-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></div>
                  <div class="toggle-text"><div class="toggle-title">Featured campaign</div><div class="toggle-desc">Shown prominently on the homepage</div></div>
                  <div class="toggle-track"><div class="toggle-thumb"></div></div>
                </label>
                <label class="toggle-card">
                  <input type="checkbox" class="toggle-input" name="is_urgent" {{ old('is_urgent')?'checked':'' }}>
                  <div class="toggle-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                  <div class="toggle-text"><div class="toggle-title">Urgent campaign</div><div class="toggle-desc">Adds a red urgent badge — use only when time-sensitive</div></div>
                  <div class="toggle-track"><div class="toggle-thumb"></div></div>
                </label>
              </div>
            </div>
          </div>

          {{-- STEP 3: Updates & Documents --}}
          <div class="step-panel" id="step-3">
            <div class="updates-info-bar">
              <div class="updates-info-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="width:17px;height:17px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              </div>
              <div class="updates-info-text">
                <div class="title">Campaign updates &amp; documents <span style="color:var(--danger);font-size:11px;font-weight:700;margin-left:6px;">● Required</span></div>
                <div class="sub">Add at least one update or supporting document. Donors trust campaigns with transparent records.</div>
              </div>
            </div>
            <div id="updateEntries" class="update-entries"></div>
            <button type="button" class="add-update-btn" id="addUpdateBtn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
              Add update or document
            </button>
            <div class="skip-note" style="background:rgba(254,242,242,.6);border-color:#fecaca;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--danger);"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
              <span><strong style="color:var(--danger);">Required:</strong> You must add at least one update with a title and description before continuing.</span>
            </div>
          </div>

          {{-- STEP 4: Media --}}
          <div class="step-panel" id="step-4">
            <div class="upload-zone" id="uploadZone" onclick="document.getElementById('coverInput').click()">
              <input type="file" id="coverInput" name="cover_image" accept="image/*">
              <div id="uploadPrompt">
                <div class="upload-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg></div>
                <div class="upload-title">Drop your cover image here <span style="color:var(--danger);">*</span></div>
                <div class="upload-hint">or click to browse from your device</div>
                <div class="upload-btn">Choose file</div>
                <div style="font-size:11px;color:var(--ink-muted);margin-top:12px;">JPG or PNG · Max 2MB · Min 1200×630px recommended</div>
              </div>
              <div id="imagePreview">
                <img id="previewImg" src="" alt="Cover preview">
                <div id="fileName"></div>
                <div><span class="change-img-btn">Change image</span></div>
              </div>
            </div>
            <p style="font-size:12px;color:var(--ink-muted);margin-top:14px;line-height:1.6;text-align:center;">
              <strong style="color:var(--danger);">Required.</strong> Campaigns with a compelling cover image raise <strong style="color:var(--purple-main);">3× more</strong> on average.
            </p>
          </div>

          {{-- STEP 5: Products --}}
          <div class="step-panel" id="step-5">
            <div style="display:flex;align-items:flex-start;gap:14px;padding:16px 18px;background:var(--purple-mist);border:1px solid var(--border);border-radius:16px;margin-bottom:20px;">
              <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="width:17px;height:17px;"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
              </div>
              <div>
                <div style="font-size:13px;font-weight:600;color:var(--ink);margin-bottom:3px;">Fundraiser products</div>
                <div style="font-size:12px;color:var(--ink-soft);line-height:1.6;">Pick from admin suggestions for your category or add your own custom products.</div>
              </div>
            </div>
            <div id="suggestionsSection">
              <div class="suggestions-label">Suggested for your category</div>
              <div class="suggestions-wrap" id="suggestionsWrap">
                <span style="font-size:12px;color:var(--ink-muted);">Select a category in Step 1 to see suggestions.</span>
              </div>
            </div>
            <div id="productList" class="product-list"></div>
            <button type="button" class="add-product-btn" id="addProductBtn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
              Add a custom product
            </button>
            <div class="grand-total-card" id="grandTotalCard">
              <div>
                <div class="grand-total-left">Total product value</div>
                <div class="grand-total-sub" id="grandTotalSub"></div>
              </div>
              <div class="grand-total-amount" id="grandTotalAmount">₹0</div>
            </div>
            <div class="skip-note">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
              Optional. You can add products after approval from your dashboard.
            </div>
          </div>

          {{-- STEP 6: Review --}}
          <div class="step-panel" id="step-6">
            <div class="review-card">
              <div class="review-card-header"><div class="review-card-title">Campaign summary</div></div>
              <div class="review-row"><span class="review-label">Title</span><span class="review-value" id="rv-title">—</span></div>
              <div class="review-row"><span class="review-label">Goal amount</span><span class="review-value" id="rv-goal">—</span></div>
              <div class="review-row"><span class="review-label">Category</span><span class="review-value" id="rv-category">—</span></div>
              <div class="review-row"><span class="review-label">Location</span><span class="review-value" id="rv-location">—</span></div>
              <div class="review-row"><span class="review-label">Duration</span><span class="review-value" id="rv-dates">—</span></div>
              <div class="review-row"><span class="review-label">Cover image</span><span class="review-value" id="rv-image">Not uploaded</span></div>
            </div>
            <div class="review-card" id="rvUpdatesCard" style="display:none;">
              <div class="review-card-header"><div class="review-card-title">Updates &amp; documents <span id="rvUpdateCount" style="font-weight:400;color:var(--ink-soft);"></span></div></div>
              <div class="review-updates-body" id="rvUpdatesBody"></div>
            </div>
            <div class="review-card" id="rvProductsCard" style="display:none;">
              <div class="review-card-header"><div class="review-card-title">Products <span id="rvProductCount" style="font-weight:400;color:var(--ink-soft);"></span></div></div>
              <div class="review-products-body" id="rvProductsBody"></div>
              <div class="review-row" style="background:var(--purple-mist);">
                <span class="review-label" style="font-weight:600;color:var(--ink-mid);">Total product value</span>
                <span class="review-value" style="color:var(--purple-deep);font-size:15px;" id="rv-products-total">₹0</span>
              </div>
            </div>
            <div class="grand-summary-card" id="grandSummaryCard">
              <div class="grand-summary-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 7H6a2 2 0 00-2 2v9a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-3M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M9 7h6"/></svg>
                Campaign financial summary
              </div>
              <div class="grand-summary-row"><span class="lbl">Fundraising goal</span><span class="val" id="gs-goal">—</span></div>
              <div class="grand-summary-row" id="gs-products-row" style="display:none;"><span class="lbl">Total product value</span><span class="val" id="gs-products">₹0</span></div>
              <div class="grand-summary-total-row"><span class="lbl">Combined total</span><span class="val" id="gs-combined">—</span></div>
            </div>

            {{-- KYC next-step notice (replaces old KYC form) --}}
            <div class="review-notice" style="margin-top:16px;">
              <div class="review-notice-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              </div>
              <div class="review-notice-text">
                After submitting you'll be taken to <strong>KYC verification</strong> — upload your Aadhaar/PAN/Passport to activate your campaign. It only takes 2 minutes.
              </div>
            </div>
          </div>

          <div class="form-nav">
            <button type="button" class="btn-back" id="btnBack" style="display:none;" onclick="changeStep(-1)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
              Back
            </button>
            <div style="flex:1;"></div>
            <button type="button" class="btn-next" id="btnNext" onclick="changeStep(1)">
              Continue
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
            <button type="button" class="btn-next" id="btnSubmit" style="display:none;" onclick="handleSubmit()">
              Submit &amp; verify identity
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </button>
          </div>

        </div>
      </form>
    </div>

    <span class="step-pill">Step <span id="stepCounter">1</span> of 6</span>

  </div>
</div>

{{-- Pass admin category products to JS --}}
<script>
var categoryProductsMap = {};
@foreach($categoryProducts as $product)
  @if($product->is_active)
  (function(){
    var cid = '{{ $product->category_id }}';
    if (!categoryProductsMap[cid]) categoryProductsMap[cid] = [];
    categoryProductsMap[cid].push({
      id:    {{ $product->id }},
      name:  @json($product->name),
      price: {{ (float) $product->price }},
      desc:  @json($product->description ?? ''),
      stock: {{ (int) ($product->stock ?? 10) }},
      image: @json($product->image_url ?? ''),
    });
  })();
  @endif
@endforeach

/* KYC route base — controller will redirect here after store() */
var kycRouteBase = '{{ url('/kyc/upload') }}';
</script>

<script>
var currentStep  = 1;
var totalSteps   = 6;   /* KYC is now its own page — only 6 steps here */
var productCount = 0;
var updateCount  = 0;

var stepMeta = {
  1:{badge:'Step 1 of 6',heading:'Campaign basics',         sub:'Start with the essential information about your campaign.'},
  2:{badge:'Step 2 of 6',heading:'Additional details',      sub:"Help donors understand where, when, and how you'll fundraise."},
  3:{badge:'Step 3 of 6',heading:'Updates & documents',     sub:'At least one update with title & description is required.'},
  4:{badge:'Step 4 of 6',heading:'Cover image',             sub:'A great image makes your campaign stand out and builds trust.'},
  5:{badge:'Step 5 of 6',heading:'Fundraiser products',     sub:'Optional — add products donors can purchase to support your cause.'},
  6:{badge:'Step 6 of 6',heading:'Review & submit',         sub:'Almost there — check everything, then submit to begin KYC.'},
};
var progressMap = {1:'16.6%',2:'33.2%',3:'49.8%',4:'66.4%',5:'83%',6:'100%'};

function changeStep(dir){
  if(dir===1 && !validateStep(currentStep)) return;
  var prev = currentStep;
  currentStep = Math.max(1, Math.min(totalSteps, currentStep+dir));
  if(currentStep===6) populateReview();
  if(currentStep===5) renderSuggestions();

  document.getElementById('step-'+prev).classList.remove('active');
  document.getElementById('step-'+currentStep).classList.add('active');
  updateDots(prev, currentStep);
  updateNav();
  updateHeader();
  document.getElementById('progressBar').style.width = progressMap[currentStep];
  document.getElementById('stepCounter').textContent = currentStep;
  window.scrollTo({top:0,behavior:'smooth'});
}

function updateHeader(){
  var m = stepMeta[currentStep];
  document.getElementById('stepBadge').textContent   = m.badge;
  document.getElementById('stepHeading').textContent = m.heading;
  document.getElementById('stepSub').textContent     = m.sub;
}

function updateDots(prev, current){
  var pd=document.getElementById('dot-'+prev), pl=document.getElementById('label-'+prev), pi=document.getElementById('sitem-'+prev);
  if(current>prev){
    pd.classList.remove('active'); pd.classList.add('done');
    pd.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;"><path d="M20 6L9 17l-5-5"/></svg>';
    pl.classList.remove('active'); pl.classList.add('done'); pi.classList.remove('active');
  } else {
    pd.classList.remove('done','active'); pd.classList.add('active'); pd.textContent=prev;
    pl.classList.remove('active','done'); pl.classList.add('active');
  }
  var cd=document.getElementById('dot-'+current), cl=document.getElementById('label-'+current);
  cd.classList.remove('done'); cd.classList.add('active'); cd.textContent=current;
  cl.classList.remove('done'); cl.classList.add('active');
  document.getElementById('sitem-'+current).classList.add('active');
}

function updateNav(){
  document.getElementById('btnBack').style.display   = currentStep>1?'inline-flex':'none';
  document.getElementById('btnNext').style.display   = currentStep<totalSteps?'inline-flex':'none';
  document.getElementById('btnSubmit').style.display = currentStep===totalSteps?'inline-flex':'none';
}

/* ── VALIDATION ── */
function validateStep(step){
  if(step===1){
    if(!document.querySelector('[name=title]').value.trim())       {showToast('Please enter a campaign title.'); return false;}
    if(!document.getElementById('goalAmount').value.trim())        {showToast('Please enter a goal amount.'); return false;}
    if(!document.querySelector('[name=category_id]').value)        {showToast('Please select a category.'); return false;}
    if(!document.querySelector('[name=description]').value.trim()) {showToast('Please enter a campaign description.'); return false;}
  }
  if(step===3){
    var entries=document.querySelectorAll('.update-entry');
    if(entries.length===0){ showToast('Please add at least one update or document before continuing.'); return false; }
    var hasValid=false;
    for(var i=0;i<entries.length;i++){
      var title=entries[i].querySelector('[data-ufield="title"]').value.trim();
      var body=entries[i].querySelector('[data-ufield="body"]').value.trim();
      if(title && body){ hasValid=true; }
      if(body && !title){ showToast('Please enter a title for update #'+(i+1)+'.'); return false; }
      if(title && !body){ showToast('Please enter a description for update #'+(i+1)+'.'); return false; }
    }
    if(!hasValid){ showToast('Each update needs both a title and a description.'); return false; }
  }
  if(step===4){
    var coverInput=document.getElementById('coverInput');
    if(!coverInput.files || coverInput.files.length===0){
      document.getElementById('uploadZone').classList.add('required-error');
      showToast('Please upload a cover image before continuing.');
      return false;
    }
  }
  if(step===5){
    var items=document.querySelectorAll('.product-item');
    for(var i=0;i<items.length;i++){
      var item=items[i];
      var name=item.querySelector('[data-field="name"]').value.trim();
      var price=item.querySelector('[data-field="price"]').value.trim();
      var qty=item.querySelector('[data-field="stock"]').value.trim();
      if(name && !price){showToast('Enter a price for "'+name+'".'); return false;}
      if(name && !qty)  {showToast('Enter a quantity for "'+name+'".'); return false;}
      if(price && !name){showToast('Enter a name for the product with price ₹'+price+'.'); return false;}
    }
  }
  return true;
}

/* ── TOAST ── */
function showToast(html, type, duration){
  type = type||'purple'; duration = duration||3000;
  var el=document.createElement('div');
  el.className='toast-alert '+type;
  el.innerHTML=html;
  document.body.appendChild(el);
  setTimeout(function(){el.style.opacity='0';el.style.transition='opacity .4s';},duration);
  setTimeout(function(){if(el.parentNode)el.remove();},duration+450);
}

/* ── SUBMIT ── */
/* After the form posts, the controller returns JSON {campaign_id: X}
   OR does a redirect — we handle both approaches below.
   If your controller returns JSON, use the fetch approach.
   If your controller does a standard redirect to /kyc/upload/{id}, just submit normally. */
function handleSubmit(){
  document.getElementById('goalAmount').value =
    document.getElementById('goalAmount').value.replace(/,/g,'');

  /* Show the success popup immediately */
  document.getElementById('successOverlay').classList.add('show');

  /* Submit the form after a short delay so the modal is visible */
  setTimeout(function(){
    document.getElementById('campaignForm').submit();
  }, 1800);
}

/* Called when user clicks "Complete KYC Verification" in the popup.
   Since the page will have been redirected by the controller already,
   this button is mainly a fallback. The controller handles the redirect. */
function goToKyc(){
  /* If controller redirected, this won't be reached.
     If you want JS-side navigation as fallback: */
  window.location.href = kycRouteBase;
}

function closeSuccessPopup(){
  var o=document.getElementById('successOverlay');
  o.style.opacity='0'; o.style.transition='opacity .3s';
  setTimeout(function(){o.classList.remove('show');o.style.opacity='';o.style.transition='';},300);
}
document.getElementById('successOverlay').addEventListener('click',function(e){if(e.target===this)closeSuccessPopup();});

/* ── UPDATES ── */
document.getElementById('addUpdateBtn').addEventListener('click',function(){addUpdate();});
function addUpdate(){
  updateCount++;
  var id=updateCount, fid='docFile-'+id, fnid='docName-'+id;
  var html='<div class="update-entry" id="update-'+id+'">'+
    '<div class="update-entry-header">'+
      '<span class="update-entry-num"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Update '+id+'</span>'+
      '<button type="button" class="remove-update-btn" onclick="removeUpdate('+id+')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>'+
    '</div>'+
    '<div class="field-stack">'+
      '<div class="field-wrap"><label class="field-label">Update title <span>*</span></label><input type="text" name="updates['+id+'][title]" data-ufield="title" class="field-input" placeholder="e.g. Week 2 progress report"></div>'+
      '<div class="field-wrap"><label class="field-label">Update body <span>*</span></label><textarea name="updates['+id+'][body]" data-ufield="body" class="field-input" rows="3" placeholder="Share your progress, milestones, or any relevant information..."></textarea></div>'+
      '<div class="field-wrap"><label class="field-label">Attach document <span style="color:var(--ink-muted);font-weight:400;text-transform:none;letter-spacing:0;">(optional)</span></label>'+
        '<div class="doc-attach-row">'+
          '<label class="doc-attach-label" for="'+fid+'"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>Attach file</label>'+
          '<input type="file" id="'+fid+'" name="updates['+id+'][document]" accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx" style="display:none" onchange="showDocName('+id+',this)">'+
          '<span class="doc-filename" id="'+fnid+'">No file chosen</span>'+
        '</div>'+
      '</div>'+
    '</div></div>';
  document.getElementById('updateEntries').insertAdjacentHTML('beforeend',html);
}
function removeUpdate(id){
  var el=document.getElementById('update-'+id); if(!el)return;
  el.style.opacity='0'; el.style.transform='scale(.97)'; el.style.transition='all .25s';
  setTimeout(function(){el.remove();},260);
}
function showDocName(id,input){
  var nameEl=document.getElementById('docName-'+id);
  if(input.files&&input.files[0]){
    var f=input.files[0];
    nameEl.innerHTML='<span class="doc-preview-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>'+f.name+'</span>';
  } else {
    nameEl.textContent='No file chosen';
  }
}

/* ── PRODUCTS ── */
function renderSuggestions(){
  var wrap=document.getElementById('suggestionsWrap');
  wrap.innerHTML='';
  var catEl=document.getElementById('categorySelect');
  var catId=catEl.value;
  var list=categoryProductsMap[catId];
  if(!list||list.length===0){
    wrap.innerHTML='<span style="font-size:12px;color:var(--ink-muted);">No admin products found for this category. Add your own below.</span>';
    return;
  }
  list.forEach(function(s){
    var pill=document.createElement('button');
    pill.type='button';
    pill.className='suggestion-pill';
    var imgHtml = s.image
      ? '<img src="'+s.image+'" class="suggestion-pill-img" onerror="this.style.display=\'none\'">'
      : '<span class="suggestion-pill-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg></span>';
    pill.innerHTML = imgHtml + s.name + ' &middot; ₹' + s.price.toLocaleString('en-IN');
    pill.onclick = function(){ addProduct(s.id, s.name, s.price, s.desc, s.stock, s.image); };
    wrap.appendChild(pill);
  });
}

function addProduct(categoryProductId, name, price, desc, stock, image){
  categoryProductId = categoryProductId || '';
  image = image || '';
  productCount++;
  var id = productCount;
  var imgPreviewHtml = image ? '<img src="'+image+'" class="product-img-preview" onerror="this.style.display=\'none\'">' : '';
  var html='<div class="product-item" id="product-'+id+'">'+
    '<div class="product-item-header">'+
      '<span class="product-item-num"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>Product '+id+'</span>'+
      '<button type="button" class="remove-product-btn" onclick="removeProduct('+id+')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>'+
    '</div>'+
    imgPreviewHtml+
    '<input type="hidden" name="products['+id+'][category_product_id]" value="'+categoryProductId+'">'+
    '<div class="field-stack">'+
      '<div class="field-wrap"><label class="field-label">Product name <span>*</span></label><input type="text" name="products['+id+'][name]" data-field="name" class="field-input" placeholder="e.g. Handmade bracelet" value="'+(name||'')+'"></div>'+
      '<div class="field-wrap"><label class="field-label">Description</label><textarea name="products['+id+'][description]" data-field="description" class="field-input" rows="2" placeholder="Brief description...">'+(desc||'')+'</textarea></div>'+
      '<div class="field-grid-3">'+
        '<div class="field-wrap"><label class="field-label">Price (₹) <span>*</span></label><div class="input-prefix-wrap"><span class="input-prefix">₹</span><input type="number" name="products['+id+'][price]" data-field="price" class="field-input" placeholder="199" min="0" step="1" value="'+(price||'')+'" oninput="recalcProduct('+id+')"></div></div>'+
        '<div class="field-wrap"><label class="field-label">Quantity <span>*</span></label><input type="number" name="products['+id+'][stock]" data-field="stock" class="field-input" placeholder="10" min="1" step="1" value="'+(stock||'')+'" oninput="recalcProduct('+id+')"></div>'+
        '<div class="field-wrap"><label class="field-label">Status</label><select name="products['+id+'][is_active]" class="field-input"><option value="1">Active</option><option value="0">Hidden</option></select></div>'+
      '</div>'+
      '<div class="product-subtotal-row"><span class="product-subtotal-label">Subtotal (price × qty)</span><span class="product-subtotal-value" id="subtotal-'+id+'">₹0</span></div>'+
    '</div></div>';
  document.getElementById('productList').insertAdjacentHTML('beforeend',html);
  if(price) recalcProduct(id);
  updateGrandTotal();
}

function removeProduct(id){
  var el=document.getElementById('product-'+id); if(!el)return;
  el.style.opacity='0'; el.style.transform='scale(.97)'; el.style.transition='all .25s';
  setTimeout(function(){el.remove();updateGrandTotal();},260);
}
function recalcProduct(id){
  var item=document.getElementById('product-'+id); if(!item)return;
  var price=parseFloat(item.querySelector('[data-field="price"]').value)||0;
  var qty=parseFloat(item.querySelector('[data-field="stock"]').value)||0;
  document.getElementById('subtotal-'+id).textContent='₹'+Math.round(price*qty).toLocaleString('en-IN');
  updateGrandTotal();
}
function updateGrandTotal(){
  var items=document.querySelectorAll('.product-item');
  var grand=0,count=0;
  items.forEach(function(item){
    var price=parseFloat(item.querySelector('[data-field="price"]').value)||0;
    var qty=parseFloat(item.querySelector('[data-field="stock"]').value)||0;
    if(price>0&&qty>0){grand+=price*qty;count++;}
  });
  var card=document.getElementById('grandTotalCard');
  if(items.length===0){card.style.display='none';return;}
  card.style.display='flex';
  document.getElementById('grandTotalAmount').textContent='₹'+Math.round(grand).toLocaleString('en-IN');
  document.getElementById('grandTotalSub').textContent=count+' product'+(count!==1?'s':'')+' with price & qty filled';
}

/* ── REVIEW ── */
function getGoalRaw(){return parseFloat(document.getElementById('goalAmount').value.replace(/,/g,''))||0;}
function populateReview(){
  var catEl=document.querySelector('[name=category_id]');
  var start=document.querySelector('[name=start_date]').value;
  var end=document.querySelector('[name=end_date]').value;
  var fileEl=document.getElementById('coverInput');
  var goalRaw=getGoalRaw();
  document.getElementById('rv-title').textContent    = document.querySelector('[name=title]').value||'—';
  document.getElementById('rv-goal').textContent     = goalRaw?'₹'+Math.round(goalRaw).toLocaleString('en-IN'):'—';
  document.getElementById('rv-category').textContent = catEl.options[catEl.selectedIndex]?catEl.options[catEl.selectedIndex].text:'—';
  document.getElementById('rv-location').textContent = document.querySelector('[name=location]').value||'—';
  document.getElementById('rv-dates').textContent    = (start&&end)?start+' → '+end:(start||end||'—');
  document.getElementById('rv-image').textContent    = (fileEl.files&&fileEl.files.length)?fileEl.files[0].name:'Not uploaded';

  var updateEntries=document.querySelectorAll('.update-entry');
  var rvUpdatesCard=document.getElementById('rvUpdatesCard');
  if(updateEntries.length>0){
    rvUpdatesCard.style.display='';
    document.getElementById('rvUpdateCount').textContent='('+updateEntries.length+')';
    document.getElementById('rvUpdatesBody').innerHTML=Array.from(updateEntries).map(function(entry){
      var title=entry.querySelector('[data-ufield="title"]').value.trim();
      var fi=entry.querySelector('input[type="file"]');
      var docName=(fi&&fi.files&&fi.files[0])?fi.files[0].name:'';
      if(!title) return '';
      return '<span class="review-update-pill">'+title+(docName?' · 📎'+docName:'')+'</span>';
    }).join('');
  } else { rvUpdatesCard.style.display='none'; }

  var items=document.querySelectorAll('.product-item');
  var products=[],productTotal=0;
  items.forEach(function(item){
    var name=item.querySelector('[data-field="name"]').value.trim();
    var price=parseFloat(item.querySelector('[data-field="price"]').value)||0;
    var qty=parseFloat(item.querySelector('[data-field="stock"]').value)||0;
    if(name){products.push({name:name,price:price,qty:qty});productTotal+=price*qty;}
  });
  var rvCard=document.getElementById('rvProductsCard');
  if(products.length>0){
    rvCard.style.display='';
    document.getElementById('rvProductCount').textContent='('+products.length+')';
    document.getElementById('rv-products-total').textContent='₹'+Math.round(productTotal).toLocaleString('en-IN');
    document.getElementById('rvProductsBody').innerHTML=products.map(function(p){
      return '<span class="review-product-pill">'+p.name+(p.price?' · ₹'+p.price.toLocaleString('en-IN'):'')+(p.qty?' · qty '+p.qty:'')+(p.price&&p.qty?' · <strong>₹'+Math.round(p.price*p.qty).toLocaleString('en-IN')+'</strong>':'')+'</span>';
    }).join('');
  } else { rvCard.style.display='none'; }

  var combined=goalRaw+productTotal;
  document.getElementById('gs-goal').textContent='₹'+Math.round(goalRaw).toLocaleString('en-IN');
  document.getElementById('gs-combined').textContent='₹'+Math.round(combined).toLocaleString('en-IN');
  var gsRow=document.getElementById('gs-products-row');
  if(productTotal>0){gsRow.style.display='flex';document.getElementById('gs-products').textContent='₹'+Math.round(productTotal).toLocaleString('en-IN');}
  else{gsRow.style.display='none';}
}

/* ── UTILITIES ── */
var goalInput=document.getElementById('goalAmount');
goalInput.addEventListener('input',function(e){
  var v=e.target.value.replace(/,/g,'');
  if(!v) return;
  var n=parseInt(v);
  if(!isNaN(n)) e.target.value=n.toLocaleString('en-IN');
});
goalInput.addEventListener('keypress',function(e){if(!/[0-9]/.test(e.key))e.preventDefault();});

var titleInput=document.getElementById('titleInput');
var descInput=document.getElementById('descInput');
if(titleInput) titleInput.addEventListener('input',function(){document.getElementById('titleCount').textContent=titleInput.value.length;});
if(descInput)  descInput.addEventListener('input', function(){document.getElementById('descCount').textContent=descInput.value.length;});

document.getElementById('coverInput').addEventListener('change',function(e){
  var file=e.target.files[0]; if(!file) return;
  document.getElementById('uploadZone').classList.remove('required-error');
  var reader=new FileReader();
  reader.onload=function(ev){
    document.getElementById('previewImg').src=ev.target.result;
    document.getElementById('fileName').textContent=file.name+' · '+(file.size/1024).toFixed(0)+' KB';
    document.getElementById('uploadPrompt').style.display='none';
    document.getElementById('imagePreview').classList.add('show');
  };
  reader.readAsDataURL(file);
});

document.getElementById('addProductBtn').addEventListener('click',function(){
  addProduct('','','','','','');
});
</script>

@endsection