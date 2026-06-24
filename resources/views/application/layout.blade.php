<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NGO Application — DonateBazaar</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=DM+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,::before,::after{box-sizing:border-box;margin:0;padding:0}

:root{
  --purple-deep:#3b0764;
  --purple-main:#7c3aed;
  --purple-mid:#8b5cf6;
  --purple-light:#a78bfa;
  --purple-pale:#ede9fe;
  --purple-mist:#f5f3ff;
  --indigo-main:#4f46e5;
  --indigo-light:#c7d2fe;
  --indigo-pale:#e0e7ff;
  --white:#ffffff;
  --ink:#1e1b4b;
  --ink-mid:#3730a3;
  --ink-soft:#6d6aaf;
  --ink-muted:#a5a3c8;
  --border:#ddd6fe;
  --border2:#e5e7eb;
  --surface:#faf9ff;
  --surface2:#f8f9fe;
  --green:#059669;
  --green-bg:#ecfdf5;
  --green-border:#a7f3d0;
  --danger:#dc2626;
  --danger-bg:#fef2f2;
  --font:'DM Sans',sans-serif;
  --mono:'DM Mono',monospace;
  --radius:16px;
  --radius-sm:10px;
  --shadow:0 1px 3px rgba(109,40,217,.06),0 4px 16px rgba(109,40,217,.06);
  --shadow-lg:0 8px 40px rgba(109,40,217,.14);
}

body{font-family:var(--font);color:var(--ink);-webkit-font-smoothing:antialiased;background:var(--surface2)}

/* ══════════════════════════════════════════
   PAGE SHELL
══════════════════════════════════════════ */
.page-shell{
  min-height:100vh;
  background:
    radial-gradient(ellipse 80% 55% at 15% -5%,rgba(167,139,250,.2) 0%,transparent 60%),
    radial-gradient(ellipse 55% 45% at 85% 105%,rgba(99,102,241,.16) 0%,transparent 55%),
    linear-gradient(158deg,#f0ebff 0%,#eef2ff 45%,#f3e8ff 100%);
  padding:52px 16px 96px;
  position:relative;
  overflow:hidden;
}
.page-shell::before,
.page-shell::after{
  content:'';position:fixed;border-radius:50%;
  filter:blur(90px);pointer-events:none;z-index:0;
}
.page-shell::before{width:500px;height:500px;top:-140px;left:-160px;background:rgba(139,92,246,.12)}
.page-shell::after{width:420px;height:420px;bottom:-100px;right:-120px;background:rgba(99,102,241,.1)}

.grid-dots{
  position:fixed;inset:0;pointer-events:none;z-index:0;opacity:.35;
  background-image:radial-gradient(circle,#a78bfa 1px,transparent 1px);
  background-size:32px 32px;
}

.shell-inner{position:relative;z-index:1;max-width:760px;margin:0 auto}

/* ══════════════════════════════════════════
   PAGE HEADER
══════════════════════════════════════════ */
.page-header{text-align:center;margin-bottom:44px}

.page-eyebrow{
  display:inline-flex;align-items:center;gap:8px;
  background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.18);
  border-radius:100px;padding:6px 18px;
  font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;
  color:var(--purple-main);margin-bottom:18px;font-family:var(--mono);
}
.page-eyebrow span{
  width:6px;height:6px;border-radius:50%;
  background:var(--purple-main);display:inline-block;
  animation:pulse-dot 2s ease infinite;
}
@keyframes pulse-dot{
  0%,100%{opacity:1;transform:scale(1)}
  50%{opacity:.4;transform:scale(.65)}
}

.page-title{
  font-family:var(--mono);
  font-size:clamp(2rem,3.5vw,3rem);
  font-weight:600;
  background:linear-gradient(135deg,var(--purple-deep) 0%,var(--purple-main) 45%,var(--indigo-main) 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
  line-height:1.18;margin-bottom:12px;
}
.page-subtitle{
  font-size:14.5px;color:var(--ink-soft);font-weight:300;
  line-height:1.65;max-width:480px;margin:0 auto;
}

/* ══════════════════════════════════════════
   STEPPER
══════════════════════════════════════════ */
.stepper-wrap{
  display:flex;align-items:flex-start;justify-content:center;
  margin-bottom:36px;position:relative;
}
.stepper-item{
  display:flex;flex-direction:column;align-items:center;
  flex:1;max-width:120px;position:relative;
}
.stepper-item:not(:last-child)::after{
  content:'';position:absolute;top:18px;left:calc(50% + 19px);
  width:calc(100% - 38px);height:2px;
  background:var(--border);transition:background .5s;
}
.stepper-item.done:not(:last-child)::after{
  background:linear-gradient(90deg,var(--purple-main),var(--purple-light));
}

.stepper-dot{
  width:38px;height:38px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:13px;font-weight:600;font-family:var(--mono);
  border:2px solid var(--border);background:var(--white);color:var(--ink-muted);
  transition:all .35s cubic-bezier(.4,0,.2,1);position:relative;z-index:1;
}
.stepper-dot.active{
  background:var(--white);border-color:var(--purple-main);color:var(--purple-main);
  box-shadow:0 0 0 5px rgba(124,58,237,.12),0 4px 14px rgba(124,58,237,.22);
  transform:scale(1.1);
}
.stepper-dot.done{
  background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));
  border-color:transparent;color:var(--white);
  box-shadow:0 4px 14px rgba(124,58,237,.38);
}

.stepper-label{
  font-size:10.5px;font-weight:400;color:var(--ink-muted);
  margin-top:9px;letter-spacing:.03em;text-align:center;line-height:1.3;
}
.stepper-label.active{color:var(--purple-main);font-weight:600}
.stepper-label.done{color:var(--ink-soft)}

/* ══════════════════════════════════════════
   FORM CARD
══════════════════════════════════════════ */
.form-card{
  background:rgba(255,255,255,.9);
  backdrop-filter:blur(28px) saturate(1.5);
  -webkit-backdrop-filter:blur(28px) saturate(1.5);
  border-radius:28px;border:1px solid rgba(255,255,255,.8);
  box-shadow:
    0 2px 0 rgba(124,58,237,.05) inset,
    0 32px 88px rgba(109,40,217,.11),
    0 8px 24px rgba(99,102,241,.07);
  overflow:hidden;
}

/* Progress bar */
.progress-track{height:3px;background:var(--indigo-pale);overflow:hidden}
.progress-fill{
  height:100%;
  background:linear-gradient(90deg,var(--purple-main),var(--purple-mid),var(--indigo-main));
  transition:width .55s cubic-bezier(.4,0,.2,1);
  border-radius:0 3px 3px 0;
}

/* Card header */
.card-header{
  padding:30px 38px 26px;
  border-bottom:1px solid var(--indigo-pale);
  background:linear-gradient(to right,rgba(245,243,255,.9),rgba(238,242,255,.9));
}
.step-badge{
  display:inline-block;font-size:10px;font-weight:600;letter-spacing:.1em;
  text-transform:uppercase;color:var(--purple-main);background:var(--purple-pale);
  padding:3px 11px;border-radius:100px;margin-bottom:10px;font-family:var(--mono);
}
.step-heading{font-family:var(--mono);font-size:1.45rem;font-weight:600;color:var(--ink);margin-bottom:4px}
.step-sub{font-size:13px;color:var(--ink-soft);font-weight:300}

/* Card body */
.card-body{padding:34px 38px 38px}

/* ══════════════════════════════════════════
   STEP PANELS
══════════════════════════════════════════ */
.step-panel{display:none}
.step-panel.active{display:block;animation:panelIn .38s cubic-bezier(.4,0,.2,1)}
@keyframes panelIn{
  from{opacity:0;transform:translateX(20px)}
  to{opacity:1;transform:translateX(0)}
}

/* ══════════════════════════════════════════
   FIELD COMPONENTS
══════════════════════════════════════════ */
.field-stack{display:flex;flex-direction:column;gap:24px}
.field-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.field-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px}

.field-wrap{display:flex;flex-direction:column;gap:7px}
.field-label{
  font-size:11.5px;font-weight:600;color:var(--ink-soft);
  letter-spacing:.07em;text-transform:uppercase;
  display:flex;align-items:center;gap:5px;
}
.field-label .req{color:var(--purple-main)}
.field-hint{font-size:11.5px;color:var(--ink-muted);line-height:1.5}

.field-input{
  width:100%;padding:13px 16px;
  border:1.5px solid var(--border);border-radius:14px;
  background:var(--surface);
  font-family:var(--font);font-size:14px;color:var(--ink);
  outline:none;
  transition:border-color .2s,box-shadow .2s,background .2s;
  -webkit-appearance:none;appearance:none;
}
.field-input:hover{border-color:var(--purple-light)}
.field-input:focus{
  border-color:var(--purple-main);
  box-shadow:0 0 0 4px rgba(124,58,237,.11);
  background:var(--white);
}
.field-input::placeholder{color:var(--ink-muted);font-weight:300}
textarea.field-input{resize:vertical;min-height:96px;line-height:1.65}
select.field-input{
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23a5a3c8' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 14px center;
  background-size:16px;padding-right:40px;cursor:pointer;
}

/* Prefix input */
.prefix-wrap{position:relative}
.prefix-icon{
  position:absolute;left:14px;top:50%;transform:translateY(-50%);
  font-size:14px;font-weight:500;color:var(--purple-light);
  pointer-events:none;font-family:var(--mono);
}
.prefix-wrap .field-input{padding-left:46px}

/* Error message */
.field-error{font-size:11.5px;color:var(--danger);margin-top:2px}

/* ══════════════════════════════════════════
   SECTION DIVIDER
══════════════════════════════════════════ */
.section-divider{
  display:flex;align-items:center;gap:12px;
  font-size:11px;font-weight:600;letter-spacing:.1em;
  text-transform:uppercase;color:var(--ink-muted);
}
.section-divider::after{content:'';flex:1;height:1px;background:var(--indigo-pale)}

/* ══════════════════════════════════════════
   TOGGLE / CHECKBOX CARDS
══════════════════════════════════════════ */
.toggle-set{display:flex;flex-direction:column;gap:10px}
.toggle-card{
  display:flex;align-items:center;gap:14px;padding:15px 18px;
  border:1.5px solid var(--border);border-radius:14px;background:var(--surface);
  cursor:pointer;transition:border-color .2s,background .2s,box-shadow .2s;user-select:none;
}
.toggle-card:hover{border-color:var(--purple-light);background:var(--purple-mist)}
.toggle-card:has(input:checked){
  border-color:var(--purple-main);background:var(--white);
  box-shadow:0 0 0 3px rgba(124,58,237,.08);
}
.toggle-icon{
  width:38px;height:38px;border-radius:11px;
  display:flex;align-items:center;justify-content:center;
  background:var(--purple-pale);flex-shrink:0;transition:background .2s;
}
.toggle-card:has(input:checked) .toggle-icon{
  background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));
}
.toggle-icon svg{width:17px;height:17px;color:var(--purple-main);transition:color .2s}
.toggle-card:has(input:checked) .toggle-icon svg{color:white}
.toggle-text{flex:1}
.toggle-title{font-size:14px;font-weight:500;color:var(--ink)}
.toggle-desc{font-size:12px;color:var(--ink-soft);margin-top:2px}
.toggle-track{
  width:40px;height:22px;background:var(--border);border-radius:11px;
  position:relative;transition:background .2s;flex-shrink:0;
}
.toggle-card:has(input:checked) .toggle-track{
  background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));
}
.toggle-thumb{
  position:absolute;top:3px;left:3px;width:16px;height:16px;
  background:var(--white);border-radius:50%;
  transition:transform .22s cubic-bezier(.4,0,.2,1);
  box-shadow:0 2px 5px rgba(109,40,217,.22);
}
.toggle-card:has(input:checked) .toggle-thumb{transform:translateX(18px)}
.toggle-input{display:none}

/* Expandable cert section */
.cert-expand{margin-top:12px;display:none;padding-top:14px;border-top:1px solid var(--indigo-pale)}
.cert-expand.open{display:grid;grid-template-columns:1fr 1fr;gap:14px;animation:panelIn .25s ease}

/* ══════════════════════════════════════════
   CAUSE CHIPS
══════════════════════════════════════════ */
.cause-grid{display:flex;flex-wrap:wrap;gap:10px}
.cause-chip{
  display:inline-flex;align-items:center;gap:7px;padding:9px 15px;
  border:1.5px solid var(--border);border-radius:100px;
  background:var(--white);cursor:pointer;
  font-size:12.5px;font-weight:500;color:var(--ink-soft);
  font-family:var(--font);transition:.2s;user-select:none;
}
.cause-chip:hover{border-color:var(--purple-light);color:var(--purple-main);background:var(--purple-mist)}
.cause-chip input{display:none}
.cause-chip:has(input:checked){
  background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));
  border-color:transparent;color:white;
  box-shadow:0 3px 12px rgba(124,58,237,.32);
}
.cause-chip .chip-dot{width:7px;height:7px;border-radius:50%;background:currentColor;opacity:.6}
.cause-chip:has(input:checked) .chip-dot{opacity:1;background:rgba(255,255,255,.7)}

/* ══════════════════════════════════════════
   DOCUMENT UPLOAD ROWS
══════════════════════════════════════════ */
.doc-upload-item{
  display:flex;align-items:center;justify-content:space-between;
  padding:14px 18px;border:1.5px solid var(--border);border-radius:14px;
  background:var(--surface);transition:.2s;
}
.doc-upload-item:hover{border-color:var(--purple-light)}
.doc-upload-item.uploaded{border-color:var(--green-border);background:var(--green-bg)}
.doc-left{display:flex;align-items:center;gap:12px}
.doc-icon{
  width:36px;height:36px;border-radius:10px;background:var(--purple-pale);
  display:flex;align-items:center;justify-content:center;
  flex-shrink:0;transition:background .2s;
}
.doc-upload-item.uploaded .doc-icon{background:var(--green-bg)}
.doc-icon svg{width:16px;height:16px;color:var(--purple-main)}
.doc-upload-item.uploaded .doc-icon svg{color:var(--green)}
.doc-name{font-size:13.5px;font-weight:500;color:var(--ink)}
.doc-size{font-size:11.5px;color:var(--ink-muted);margin-top:2px}
.doc-upload-btn{
  display:inline-flex;align-items:center;gap:6px;padding:7px 16px;
  border-radius:100px;border:1.5px solid var(--border);
  background:var(--white);color:var(--ink-soft);font-size:12px;font-weight:500;
  cursor:pointer;font-family:var(--font);transition:.2s;flex-shrink:0;
}
.doc-upload-btn:hover{border-color:var(--purple-main);color:var(--purple-main)}
.doc-upload-item.uploaded .doc-upload-btn{border-color:var(--green-border);color:var(--green)}

/* ══════════════════════════════════════════
   REVIEW CARDS
══════════════════════════════════════════ */
.review-section{margin-bottom:16px;border-radius:18px;border:1px solid var(--indigo-pale);overflow:hidden}
.review-section-head{
  padding:13px 20px;
  background:linear-gradient(135deg,var(--purple-mist),var(--indigo-pale));
  border-bottom:1px solid var(--indigo-pale);
  display:flex;align-items:center;gap:10px;
}
.review-section-icon{
  width:28px;height:28px;border-radius:8px;
  background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));
  display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.review-section-icon svg{width:13px;height:13px;color:#fff}
.review-section-title{font-size:11.5px;font-weight:600;color:var(--ink-mid);text-transform:uppercase;letter-spacing:.08em}
.review-row{
  display:flex;justify-content:space-between;align-items:flex-start;
  padding:12px 20px;border-bottom:1px solid rgba(224,231,255,.55);gap:14px;
}
.review-row:last-child{border-bottom:none}
.review-label{font-size:12px;color:var(--ink-soft);font-weight:500;flex-shrink:0;min-width:120px}
.review-value{font-size:13px;color:var(--ink);font-weight:500;text-align:right;word-break:break-word}
.review-chip{
  display:inline-flex;align-items:center;gap:5px;
  background:var(--purple-pale);color:var(--purple-deep);
  padding:3px 11px;border-radius:100px;font-size:11.5px;font-weight:500;
  margin:3px 3px 3px 0;
}
.review-chip-green{background:var(--green-bg);color:var(--green)}

/* Submit notice */
.submit-notice{
  display:flex;align-items:flex-start;gap:14px;padding:18px 20px;
  background:linear-gradient(135deg,var(--purple-pale),var(--indigo-pale));
  border:1px solid var(--indigo-light);border-radius:18px;margin-top:20px;
}
.submit-notice-icon{
  width:36px;height:36px;border-radius:11px;background:rgba(124,58,237,.12);
  display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.submit-notice-icon svg{width:16px;height:16px;color:var(--purple-main)}
.submit-notice-text{font-size:13px;color:var(--ink-soft);line-height:1.7}

/* ══════════════════════════════════════════
   NAV BUTTONS
══════════════════════════════════════════ */
.form-nav{
  display:flex;justify-content:space-between;align-items:center;
  margin-top:38px;padding-top:28px;border-top:1px solid var(--indigo-pale);
}
.btn-back{
  display:inline-flex;align-items:center;gap:8px;
  background:transparent;color:var(--ink-soft);
  border:1.5px solid var(--border);padding:13px 22px;border-radius:14px;
  font-family:var(--font);font-size:14px;font-weight:400;cursor:pointer;
  text-decoration:none;
  transition:border-color .2s,color .2s,background .2s;
}
.btn-back:hover{border-color:var(--purple-light);color:var(--purple-main);background:var(--purple-mist)}
.btn-back svg{width:15px;height:15px}

.btn-next{
  display:inline-flex;align-items:center;gap:10px;
  background:linear-gradient(135deg,var(--purple-main) 0%,var(--indigo-main) 100%);
  color:var(--white);border:none;padding:14px 32px;border-radius:14px;
  font-family:var(--font);font-size:14px;font-weight:500;cursor:pointer;
  box-shadow:0 6px 22px rgba(124,58,237,.42),0 2px 6px rgba(79,70,229,.28);
  transition:opacity .2s,transform .2s,box-shadow .2s;
  position:relative;overflow:hidden;letter-spacing:.02em;
}
.btn-next::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(255,255,255,.14),transparent);
}
.btn-next:hover{opacity:.93;transform:translateY(-2px);box-shadow:0 10px 30px rgba(124,58,237,.48)}
.btn-next:active{transform:scale(.97)}
.btn-next svg{width:15px;height:15px}

/* ══════════════════════════════════════════
   TOAST
══════════════════════════════════════════ */
.toast{
  position:fixed;top:24px;left:50%;
  transform:translateX(-50%) translateY(0);
  background:var(--purple-deep);color:#fff;
  padding:12px 24px;border-radius:14px;
  font-size:13.5px;z-index:9999;
  box-shadow:0 8px 28px rgba(76,29,149,.42);
  animation:toastIn .3s ease;
  font-family:var(--font);pointer-events:none;
}
@keyframes toastIn{
  from{opacity:0;transform:translateX(-50%) translateY(-10px)}
  to{opacity:1;transform:translateX(-50%) translateY(0)}
}

/* ══════════════════════════════════════════
   SUCCESS OVERLAY
══════════════════════════════════════════ */
.success-overlay{
  display:none;position:fixed;inset:0;
  background:rgba(30,27,75,.55);
  backdrop-filter:blur(7px);-webkit-backdrop-filter:blur(7px);
  z-index:10000;align-items:center;justify-content:center;padding:20px;
}
.success-overlay.show{display:flex;animation:overlayIn .35s ease}
@keyframes overlayIn{from{opacity:0}to{opacity:1}}

.success-modal{
  background:#fff;border-radius:28px;border:1px solid rgba(255,255,255,.9);
  box-shadow:0 40px 100px rgba(76,29,149,.24),0 8px 32px rgba(99,102,241,.14);
  max-width:500px;width:100%;text-align:center;padding:52px 44px 44px;
  animation:modalIn .4s cubic-bezier(.34,1.56,.64,1);
  position:relative;overflow:hidden;
}
.success-modal::before{
  content:'';position:absolute;top:0;left:0;right:0;height:4px;
  background:linear-gradient(90deg,var(--purple-main),var(--purple-mid),var(--indigo-main));
}
@keyframes modalIn{
  from{opacity:0;transform:scale(.86) translateY(26px)}
  to{opacity:1;transform:scale(1) translateY(0)}
}

.success-icon-ring{
  width:90px;height:90px;border-radius:50%;
  background:linear-gradient(135deg,#ede9fe,#e0e7ff);
  border:2px solid var(--border);
  display:flex;align-items:center;justify-content:center;
  margin:0 auto 26px;position:relative;
}
.success-icon-ring::after{
  content:'';position:absolute;inset:-7px;border-radius:50%;
  border:2px dashed rgba(124,58,237,.22);
  animation:spinRing 14s linear infinite;
}
@keyframes spinRing{to{transform:rotate(360deg)}}

.success-icon-inner{
  width:62px;height:62px;border-radius:50%;
  background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));
  display:flex;align-items:center;justify-content:center;
  box-shadow:0 8px 26px rgba(124,58,237,.42);
}
.success-icon-inner svg{width:28px;height:28px;color:#fff}

.success-title{
  font-family:var(--mono);font-size:1.85rem;font-weight:600;
  color:var(--ink);margin-bottom:10px;line-height:1.2;
}
.success-sub{
  font-size:14px;color:var(--ink-soft);font-weight:300;
  line-height:1.7;margin-bottom:32px;
}
.success-sub strong{color:var(--purple-main);font-weight:600}

.success-timeline{
  display:flex;background:var(--purple-mist);
  border:1px solid var(--border);border-radius:16px;
  overflow:hidden;margin-bottom:30px;text-align:left;
}
.success-step{
  flex:1;padding:14px 16px;border-right:1px solid var(--border);
  display:flex;align-items:flex-start;gap:10px;
}
.success-step:last-child{border-right:none}
.snum{
  width:24px;height:24px;border-radius:50%;
  background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));
  color:#fff;font-size:11px;font-weight:600;
  display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.stext{font-size:11px;color:var(--ink-soft);line-height:1.5}
.stext strong{display:block;font-size:12px;font-weight:600;color:var(--ink);margin-bottom:2px}

.confetti-row{display:flex;justify-content:center;gap:7px;margin-bottom:28px}
.cdot{width:8px;height:8px;border-radius:50%;animation:cbounce .6s ease infinite alternate}
.cdot:nth-child(1){background:#7c3aed;animation-delay:0s}
.cdot:nth-child(2){background:#4f46e5;animation-delay:.1s}
.cdot:nth-child(3){background:#a78bfa;animation-delay:.2s}
.cdot:nth-child(4){background:#6366f1;animation-delay:.3s}
.cdot:nth-child(5){background:#8b5cf6;animation-delay:.4s}
@keyframes cbounce{from{transform:translateY(0)}to{transform:translateY(-8px)}}

.btn-dashboard{
  display:flex;align-items:center;justify-content:center;gap:10px;
  background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));
  color:#fff;text-decoration:none;padding:15px 28px;border-radius:14px;
  font-family:var(--font);font-size:14px;font-weight:500;letter-spacing:.02em;
  box-shadow:0 6px 22px rgba(124,58,237,.42);
  transition:opacity .2s,transform .2s;
  margin-bottom:12px;position:relative;overflow:hidden;
}
.btn-dashboard::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(255,255,255,.14),transparent);
}
.btn-dashboard:hover{opacity:.92;transform:translateY(-2px)}

/* Step pill */
.step-pill{
  font-size:12px;color:var(--ink-muted);background:var(--indigo-pale);
  padding:4px 16px;border-radius:100px;display:block;text-align:center;
  margin:22px auto 0;width:fit-content;font-family:var(--mono);
}

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media(max-width:640px){
  .card-header,.card-body{padding-left:22px;padding-right:22px}
  .form-nav{flex-direction:column-reverse;gap:12px}
  .btn-back,.btn-next{width:100%;justify-content:center}
  .cert-expand.open{grid-template-columns:1fr}
}
@media(max-width:600px){
  .field-grid,.field-grid-3{grid-template-columns:1fr}
}
</style>
</head>
<body>

{{-- ══ SUCCESS OVERLAY ══ --}}
<div class="success-overlay" id="successOverlay">
  <div class="success-modal">
    <div class="success-icon-ring">
      <div class="success-icon-inner">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path d="M20 6L9 17l-5-5"/>
        </svg>
      </div>
    </div>
    <div class="confetti-row">
      <div class="cdot"></div><div class="cdot"></div><div class="cdot"></div>
      <div class="cdot"></div><div class="cdot"></div>
    </div>
    <h2 class="success-title">Application submitted!</h2>
    <p class="success-sub">
      Your NGO application is now <strong>under review</strong>.<br>
      Our verification team will respond within <strong>5–7 business days</strong>.
    </p>
    <div class="success-timeline">
      <div class="success-step">
        <div class="snum">1</div>
        <div class="stext"><strong>Received</strong>Application logged</div>
      </div>
      <div class="success-step">
        <div class="snum">2</div>
        <div class="stext"><strong>Verification</strong>Team reviews docs</div>
      </div>
      <div class="success-step">
        <div class="snum">3</div>
        <div class="stext"><strong>Approved</strong>Go live on platform</div>
      </div>
    </div>
    <a href="{{ route('dashboard') }}" class="btn-dashboard">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
        <rect x="3" y="3" width="7" height="7" rx="1"/>
        <rect x="14" y="3" width="7" height="7" rx="1"/>
        <rect x="14" y="14" width="7" height="7" rx="1"/>
        <rect x="3" y="14" width="7" height="7" rx="1"/>
      </svg>
      Go to dashboard
    </a>
  </div>
</div>

{{-- ══ PAGE SHELL ══ --}}
<div class="page-shell">
  <div class="grid-dots"></div>
  <div class="shell-inner">

    {{-- Header --}}
    <div class="page-header">
      <div class="page-eyebrow"><span></span> NGO Partner Application</div>
      <h1 class="page-title">Register your organisation</h1>
      <p class="page-subtitle">Join thousands of verified NGOs raising funds transparently on DonateBazaar.</p>
    </div>

    {{-- Stepper --}}
    <div class="stepper-wrap">

      {{-- Step 1 --}}
      <div class="stepper-item {{ $currentStep == 1 ? 'active' : ($currentStep > 1 ? 'done' : '') }}" id="sitem-1">
        <div class="stepper-dot {{ $currentStep == 1 ? 'active' : ($currentStep > 1 ? 'done' : '') }}">
          @if($currentStep > 1)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px"><path d="M20 6L9 17l-5-5"/></svg>
          @else
            1
          @endif
        </div>
        <span class="stepper-label {{ $currentStep == 1 ? 'active' : ($currentStep > 1 ? 'done' : '') }}">Org Info</span>
      </div>

      {{-- Step 2 --}}
      <div class="stepper-item {{ $currentStep == 2 ? 'active' : ($currentStep > 2 ? 'done' : '') }}" id="sitem-2">
        <div class="stepper-dot {{ $currentStep == 2 ? 'active' : ($currentStep > 2 ? 'done' : '') }}">
          @if($currentStep > 2)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px"><path d="M20 6L9 17l-5-5"/></svg>
          @else
            2
          @endif
        </div>
        <span class="stepper-label {{ $currentStep == 2 ? 'active' : ($currentStep > 2 ? 'done' : '') }}">Contact</span>
      </div>

      {{-- Step 3 --}}
      <div class="stepper-item {{ $currentStep == 3 ? 'active' : ($currentStep > 3 ? 'done' : '') }}" id="sitem-3">
        <div class="stepper-dot {{ $currentStep == 3 ? 'active' : ($currentStep > 3 ? 'done' : '') }}">
          @if($currentStep > 3)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px"><path d="M20 6L9 17l-5-5"/></svg>
          @else
            3
          @endif
        </div>
        <span class="stepper-label {{ $currentStep == 3 ? 'active' : ($currentStep > 3 ? 'done' : '') }}">Legal &amp; Certs</span>
      </div>

      {{-- Step 4 --}}
      <div class="stepper-item {{ $currentStep == 4 ? 'active' : '' }}" id="sitem-4">
        <div class="stepper-dot {{ $currentStep == 4 ? 'active' : '' }}">4</div>
        <span class="stepper-label {{ $currentStep == 4 ? 'active' : '' }}">Documents</span>
      </div>

    </div>

    {{-- Form Card --}}
    <div class="form-card">

      {{-- Progress bar --}}
      <div class="progress-track">
        <div class="progress-fill" style="width:{{ $currentStep * 25 }}%"></div>
      </div>

      {{-- Card header --}}
      <div class="card-header">
        <div class="step-badge">Step {{ $currentStep }} of 4</div>
        <div class="step-heading">
          @if($currentStep == 1) Organisation info
          @elseif($currentStep == 2) Contact person
          @elseif($currentStep == 3) Legal &amp; certifications
          @else Documents &amp; review
          @endif
        </div>
        <p class="step-sub">
          @if($currentStep == 1) Tell us about your organisation's core details and mission.
          @elseif($currentStep == 2) Who should we reach out to regarding this application?
          @elseif($currentStep == 3) Legal registrations, certifications, and bank details.
          @else Upload supporting documents and review before submitting.
          @endif
        </p>
      </div>

      {{-- Card body — step content injected here --}}
      <div class="card-body">

        @yield('step_content')

        {{-- Validation errors --}}
        @if ($errors->any())
          <div style="background:var(--danger-bg);border:1px solid rgba(220,38,38,.2);border-left:4px solid var(--danger);color:#991b1b;padding:14px 18px;border-radius:14px;font-size:13px;margin-bottom:24px;line-height:1.6;">
            <ul style="padding-left:18px">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Navigation --}}
        <div class="form-nav">

          {{-- Back button --}}
          @if($currentStep > 1)
            <a href="{{ route('application.step' . ($currentStep - 1)) }}" class="btn-back">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
              </svg>
              Back
            </a>
          @else
            <div></div>
          @endif

          <div style="flex:1"></div>

          {{-- Next / Submit button --}}
          @if($currentStep < 4)
            <button type="submit" form="step-form" class="btn-next">
              Save &amp; continue
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14M12 5l7 7-7 7"/>
              </svg>
            </button>
          @else
            <button type="submit" form="step-form" class="btn-next" onclick="showSuccess(event)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 13l4 4L19 7"/>
              </svg>
              Submit application
            </button>
          @endif

        </div>

      </div>{{-- /.card-body --}}
    </div>{{-- /.form-card --}}

    <span class="step-pill">Step {{ $currentStep }} of 4</span>

  </div>
</div>

<script>
  // ── Cert expand (step 3)
  function toggleCert(id, card) {
    const section = document.getElementById(id);
    const cb = card.querySelector('input[type="checkbox"]');
    setTimeout(() => {
      section.classList.toggle('open', cb.checked);
    }, 10);
  }

  // ── Doc upload feedback (step 4)
  function markUploaded(key, input) {
    if (!input.files || !input.files[0]) return;
    const item = document.getElementById('docitem-' + key);
    item.classList.add('uploaded');
    item.querySelector('.doc-size').textContent =
      input.files[0].name + ' · ' + (input.files[0].size / 1024).toFixed(0) + ' KB';
    item.querySelector('.doc-upload-btn').innerHTML = `
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
        <path d="M20 6L9 17l-5-5"/>
      </svg> Uploaded`;
  }

  // ── Success overlay (step 4 submit)
  function showSuccess(e) {
    // Show overlay briefly then let the form submit normally
    document.getElementById('successOverlay').classList.add('show');
    // Form submits after 1.6s — gives the animation time to play
    setTimeout(() => {
      document.getElementById('step-form').submit();
    }, 1600);
    e.preventDefault();
  }

  document.getElementById('successOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
      this.style.opacity = '0';
      this.style.transition = 'opacity .3s';
      setTimeout(() => {
        this.classList.remove('show');
        this.style.opacity = '';
        this.style.transition = '';
      }, 300);
    }
  });

  // ── Toast helper (available to all step views)
  function showToast(msg) {
    const el = document.createElement('div');
    el.className = 'toast';
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity .3s'; }, 2500);
    setTimeout(() => el.remove(), 2900);
  }
</script>

</body>
</html>