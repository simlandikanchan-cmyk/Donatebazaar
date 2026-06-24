<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Edit — {{ $campaign->title }} — DonateBazaar Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#f4f5fb;--surface:#fff;--surface2:#f8f9fe;--surface3:#eef0fa;
  --border:rgba(0,0,0,.06);--border2:rgba(0,0,0,.10);
  --text:#0a0b14;--text2:#454863;--text3:#9096b4;
  --sb-bg:#ffffff;--sb-txt:#5a5f7a;--sb-act:rgba(110,86,247,.10);
  --sb-border:rgba(0,0,0,.08);
  --a:#6e56f7;--a2:#9b6dff;--a-lt:rgba(110,86,247,.10);--a-glow:rgba(110,86,247,.22);
  --green:#05c48a;--green-lt:rgba(5,196,138,.10);
  --amber:#f59e0b;--amber-lt:rgba(245,158,11,.10);
  --red:#f04444;--red-lt:rgba(240,68,68,.10);
  --blue:#3b82f6;--blue-lt:rgba(59,130,246,.10);
  --pink:#ec4899;--pink-lt:rgba(236,72,153,.10);
  --gray:#6b7280;
  --font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;
  --r:18px;--r-sm:12px;--r-xs:8px;
  --sh:0 1px 3px rgba(0,0,0,.05),0 4px 24px rgba(0,0,0,.04);
  --sh-md:0 4px 20px rgba(0,0,0,.08),0 1px 4px rgba(0,0,0,.04);
  --sh-lg:0 6px 20px rgba(0,0,0,.16);
  --ease:.18s ease;--sb-w:268px;
  --accent:#6e56f7;--accent2:#9b6dff;--accent-glow:rgba(110,86,247,.22);
  --radius:14px;--radius-sm:9px;
  --shadow:var(--sh);--shadow-lg:var(--sh-lg);--tr:.2s ease;
}
[data-theme="dark"]{
  --bg:#070810;--surface:#0f1020;--surface2:#161728;--surface3:#1d1f35;
  --border:rgba(255,255,255,.055);--border2:rgba(255,255,255,.09);
  --text:#eef0ff;--text2:#9ba3c8;--text3:#4c5272;
  --sb-bg:#050609;--sb-txt:rgba(255,255,255,.48);--sb-act:rgba(110,86,247,.22);
  --sb-border:rgba(255,255,255,.03);
  --a-glow:rgba(110,86,247,.30);
  --sh:0 1px 3px rgba(0,0,0,.35),0 4px 24px rgba(0,0,0,.25);
  --sh-md:0 4px 20px rgba(0,0,0,.4),0 1px 4px rgba(0,0,0,.25);
  --sh-lg:0 12px 48px rgba(0,0,0,.6);
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;}
body{font-family:var(--font);background:var(--bg);color:var(--text);line-height:1.55;-webkit-font-smoothing:antialiased;overflow-x:hidden;transition:background .2s,color .2s;font-size:14px;}
a{text-decoration:none;color:inherit;}
button{cursor:pointer;font-family:var(--font);}
svg{display:block;flex-shrink:0;}

/* SHELL */
.shell{display:flex;min-height:100vh;}

/* ══ SIDEBAR ══ */
.sidebar{
  width:var(--sb-w);flex-shrink:0;background:var(--sb-bg);
  display:flex;flex-direction:column;
  position:fixed;top:0;left:0;bottom:0;z-index:400;
  overflow-y:auto;overflow-x:hidden;
  border-right:1px solid var(--sb-border);
  box-shadow:2px 0 16px rgba(0,0,0,.06);
  transition:transform .3s cubic-bezier(.4,0,.2,1);
}
.sidebar::-webkit-scrollbar{width:0;}
.s-logo{display:flex;align-items:center;gap:12px;padding:26px 22px 22px;border-bottom:1px solid var(--sb-border);}
.s-logo-mark{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--a),var(--a2));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 18px rgba(110,86,247,.4);}
.s-logo-mark svg{width:20px;height:20px;color:#fff;}
.s-logo-name{font-family:var(--mono);font-size:17px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1.1;}
.s-logo-tag{font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.16em;font-family:var(--mono);}
.s-admin-pill{margin:14px 12px 4px;padding:10px 14px;background:linear-gradient(135deg,rgba(110,86,247,.08),rgba(155,109,255,.05));border:1px solid rgba(110,86,247,.15);border-radius:var(--r-sm);display:flex;align-items:center;gap:10px;}
.s-av{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.s-admin-name{font-size:12.5px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.s-admin-role{font-size:10px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.s-online{width:7px;height:7px;border-radius:50%;background:var(--green);margin-left:auto;flex-shrink:0;box-shadow:0 0 0 2.5px rgba(5,196,138,.2);}
.s-section{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.18em;padding:20px 22px 6px;font-family:var(--mono);}
.s-nav{padding:2px 10px;}
.s-link{display:flex;align-items:center;gap:11px;padding:9px 12px;border-radius:var(--r-xs);color:var(--sb-txt);font-size:13px;font-weight:500;text-decoration:none;transition:background var(--ease),color var(--ease);margin-bottom:1px;border:none;background:transparent;width:100%;text-align:left;cursor:pointer;position:relative;font-family:var(--font);}
.s-link:hover{background:var(--a-lt);color:var(--a);}
.s-link.active{background:var(--sb-act);color:var(--a);font-weight:600;}
.s-link.active::before{content:'';position:absolute;left:0;top:22%;bottom:22%;width:3px;border-radius:0 3px 3px 0;background:var(--a);}
.s-ico{width:15px;height:15px;flex-shrink:0;opacity:.65;}
.s-link:hover .s-ico,.s-link.active .s-ico{opacity:1;}
.s-divider{height:1px;background:var(--sb-border);margin:10px 18px;}
.s-bottom{margin-top:auto;padding:10px 10px 20px;border-top:1px solid var(--sb-border);}

/* MAIN */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;}

/* TOPBAR */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 24px;height:62px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100;gap:14px;flex-shrink:0;}
.topbar-left{display:flex;align-items:center;gap:10px;}
.topbar-back{display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid var(--border2);background:var(--surface2);color:var(--text2);transition:all var(--tr);flex-shrink:0;}
.topbar-back:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);}
.topbar-back svg{width:13px;height:13px;}
.topbar-title h1{font-family:var(--mono);font-size:17px;font-weight:800;color:var(--text);letter-spacing:-.02em;}
.topbar-title p{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.topbar-right{display:flex;align-items:center;gap:7px;}
.icon-btn{width:34px;height:34px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--text2);position:relative;transition:background var(--ease),color var(--ease),border-color var(--ease);}
.icon-btn:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);}
.icon-btn svg{width:14px;height:14px;}
.icon-btn .dot{width:6px;height:6px;border-radius:50%;background:var(--red);position:absolute;top:5px;right:5px;border:2px solid var(--surface);}
.theme-toggle{position:relative;}
.theme-toggle input{position:absolute;opacity:0;width:0;height:0;}
.theme-toggle label{display:flex;align-items:center;justify-content:space-between;width:52px;height:28px;border-radius:100px;background:var(--surface2);border:1px solid var(--border2);cursor:pointer;padding:4px;position:relative;}
.theme-toggle label::after{content:'';width:18px;height:18px;border-radius:50%;background:var(--a);position:absolute;left:5px;transition:transform .3s cubic-bezier(.4,0,.2,1);box-shadow:0 2px 6px rgba(110,86,247,.4);}
.theme-toggle input:checked+label::after{transform:translateX(23px);}
.theme-icons{display:flex;justify-content:space-between;width:100%;position:relative;z-index:1;padding:0 2px;}
.theme-icons svg{width:11px;height:11px;color:var(--text3);}
.top-avatar{width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;font-family:var(--mono);cursor:pointer;flex-shrink:0;box-shadow:0 2px 10px rgba(110,86,247,.38);}
.hamburger{display:none;width:34px;height:34px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);align-items:center;justify-content:center;color:var(--text2);flex-shrink:0;}
.hamburger svg{width:15px;height:15px;}

/* BODY */
.body{padding:22px 24px 60px;flex:1;}

/* TOAST */
.toast-container{position:fixed;top:18px;right:18px;z-index:9999;display:flex;flex-direction:column;gap:9px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:9px;padding:12px 14px;border-radius:12px;font-size:12.5px;font-weight:500;color:#fff;min-width:260px;max-width:360px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s cubic-bezier(.4,0,.2,1) both;}
.toast-success{background:linear-gradient(135deg,#059669,#10b981);}
.toast-error{background:linear-gradient(135deg,#dc2626,#ef4444);}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-x{margin-left:auto;width:17px;height:17px;border-radius:4px;background:rgba(255,255,255,.2);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}

/* PAGE GRID */
.page-grid{display:grid;grid-template-columns:1fr 308px;gap:20px;align-items:start;}
.right-col{position:sticky;top:80px;display:flex;flex-direction:column;gap:16px;}

/* CARD */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s both;}
.card:nth-child(1){animation-delay:.05s;}.card:nth-child(2){animation-delay:.10s;}.card:nth-child(3){animation-delay:.15s;}.card:nth-child(4){animation-delay:.20s;}
.card+.card{margin-top:16px;}
.card-header{padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;}
.card-header-left{display:flex;align-items:center;gap:10px;}
.card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:14px;height:14px;}
.ic-indigo{background:var(--a-lt);color:var(--a);}
.ic-green{background:var(--green-lt);color:var(--green);}
.ic-yellow{background:var(--amber-lt);color:var(--amber);}
.ic-pink{background:var(--pink-lt);color:var(--pink);}
.ic-red{background:var(--red-lt);color:var(--red);}
.ic-blue{background:var(--blue-lt);color:var(--blue);}
.card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-.01em;font-family:var(--mono);}
.card-sub{font-size:11px;color:var(--text3);margin-top:1px;}
.card-body{padding:18px;}

/* BADGES */
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3px 9px;border-radius:100px;text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);white-space:nowrap;}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
.b-pending  {background:rgba(245,158,11,.15);color:#b45309;border:1px solid rgba(245,158,11,.30);}
.b-active   {background:rgba(16,185,129,.15);color:#065f46;border:1px solid rgba(16,185,129,.30);}
.b-approved {background:rgba(16,185,129,.15);color:#065f46;border:1px solid rgba(16,185,129,.30);}
.b-rejected {background:rgba(239,68,68,.15);color:#991b1b;border:1px solid rgba(239,68,68,.30);}
.b-paused   {background:rgba(99,102,241,.15);color:#3730a3;border:1px solid rgba(99,102,241,.30);}
.b-expired  {background:rgba(107,114,128,.15);color:#374151;border:1px solid rgba(107,114,128,.30);}
.b-completed{background:rgba(59,130,246,.15);color:#1e40af;border:1px solid rgba(59,130,246,.30);}
[data-theme="dark"] .b-pending  {color:#fbbf24;}
[data-theme="dark"] .b-active   {color:#34d399;}
[data-theme="dark"] .b-approved {color:#34d399;}
[data-theme="dark"] .b-rejected {color:#f87171;}
[data-theme="dark"] .b-paused   {color:#a5b4fc;}
[data-theme="dark"] .b-expired  {color:#9ca3af;}
[data-theme="dark"] .b-completed{color:#93c5fd;}

/* FLASH */
.flash-success{background:rgba(16,185,129,.09);border:1px solid rgba(16,185,129,.25);color:#065f46;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.flash-error  {background:rgba(239,68,68,.09);border:1px solid rgba(239,68,68,.25);color:#dc2626;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
[data-theme="dark"] .flash-success{color:#34d399;}
[data-theme="dark"] .flash-error  {color:#f87171;}

/* ══ FORM ELEMENTS ══ */
.form-group{margin-bottom:18px;}
.form-label{display:block;font-size:10.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--mono);margin-bottom:7px;}
.form-label span.req{color:var(--red);margin-left:2px;}
.form-input,
.form-select,
.form-textarea{
  width:100%;
  padding:10px 13px;
  background:var(--surface2);
  border:1.5px solid var(--border2);
  border-radius:var(--r-sm);
  color:var(--text);
  font-family:var(--font);
  font-size:13.5px;
  outline:none;
  transition:border-color var(--ease),box-shadow var(--ease),background var(--ease);
  -webkit-appearance:none;
  appearance:none;
}
.form-input::placeholder,.form-textarea::placeholder{color:var(--text3);}
.form-input:focus,
.form-select:focus,
.form-textarea:focus{
  border-color:var(--a);
  background:var(--surface);
  box-shadow:0 0 0 3px var(--a-lt);
}
.form-input.is-invalid,
.form-select.is-invalid,
.form-textarea.is-invalid{
  border-color:var(--red);
  box-shadow:0 0 0 3px rgba(240,68,68,.10);
}
.form-textarea{resize:vertical;min-height:130px;line-height:1.7;}
.form-select{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:34px;cursor:pointer;}
.form-hint{font-size:11px;color:var(--text3);margin-top:5px;font-family:var(--mono);}
.form-error{font-size:11px;color:var(--red);margin-top:5px;font-family:var(--mono);display:flex;align-items:center;gap:4px;}
.form-error::before{content:'✕';font-size:9px;}

/* INPUT ROW (two columns) */
.input-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}

/* COVER UPLOAD */
.cover-upload-zone{
  position:relative;border-radius:var(--r-sm);overflow:hidden;
  border:2px dashed var(--border2);
  transition:border-color var(--ease),background var(--ease);
  cursor:pointer;
}
.cover-upload-zone:hover,.cover-upload-zone.drag-over{
  border-color:var(--a);background:var(--a-lt);
}
.cover-upload-zone input[type="file"]{
  position:absolute;inset:0;opacity:0;cursor:pointer;z-index:2;width:100%;height:100%;
}
.cover-upload-placeholder{
  padding:28px 20px;text-align:center;display:flex;flex-direction:column;align-items:center;gap:9px;
}
.cover-upload-icon{
  width:44px;height:44px;border-radius:12px;
  background:var(--a-lt);color:var(--a);
  display:flex;align-items:center;justify-content:center;
  margin:0 auto;
}
.cover-upload-icon svg{width:20px;height:20px;}
.cover-upload-text{font-size:13px;font-weight:600;color:var(--text2);}
.cover-upload-hint{font-size:11px;color:var(--text3);font-family:var(--mono);}

.cover-preview-wrap{position:relative;}
.cover-preview-img{width:100%;height:200px;object-fit:cover;display:block;}
.cover-preview-overlay{
  position:absolute;inset:0;
  background:rgba(0,0,0,.45);
  display:flex;align-items:center;justify-content:center;gap:8px;
  opacity:0;transition:opacity var(--ease);
}
.cover-preview-wrap:hover .cover-preview-overlay{opacity:1;}
.cover-preview-btn{
  display:inline-flex;align-items:center;gap:5px;
  padding:7px 13px;border-radius:8px;font-size:12px;font-weight:600;
  border:none;cursor:pointer;font-family:var(--font);transition:opacity var(--ease);
}
.cover-preview-btn:hover{opacity:.82;}
.cover-preview-btn-change{background:#fff;color:#0a0b14;}
.cover-preview-btn-remove{background:rgba(240,68,68,.85);color:#fff;}
.cover-preview-btn svg{width:11px;height:11px;}

/* CHAR COUNT */
.char-count{float:right;font-size:10.5px;font-family:var(--mono);color:var(--text3);transition:color var(--ease);}
.char-count.warn{color:var(--amber);}
.char-count.over{color:var(--red);}

/* ACTION BUTTONS */
.action-btn{display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:10px 16px;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;cursor:pointer;border:1px solid transparent;font-family:var(--font);transition:opacity var(--ease),transform var(--ease),box-shadow var(--ease);text-decoration:none;letter-spacing:.01em;}
.action-btn:hover{opacity:.88;transform:translateY(-1px);}
.action-btn svg{width:13px;height:13px;}
.action-btn+.action-btn{margin-top:8px;}
.btn-accent{background:var(--a);color:#fff;border-color:var(--a);box-shadow:0 4px 14px rgba(110,86,247,.28);}
.btn-green {background:var(--green);color:#fff;border-color:var(--green);box-shadow:0 4px 14px rgba(5,196,138,.28);}
.btn-red   {background:rgba(240,68,68,.1);color:#b91c1c;border-color:rgba(240,68,68,.25);}
.btn-ghost {background:var(--surface2);color:var(--text2);border-color:var(--border2);}
[data-theme="dark"] .btn-red{color:#f87171;}

/* STATUS SECTION */
.status-section-label{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;font-family:var(--mono);margin-bottom:10px;}

/* INFO ROW */
.info-row{display:flex;justify-content:space-between;align-items:center;font-size:12px;padding:9px 0;}
.info-row+.info-row{border-top:1px solid var(--border);}
.info-row-lbl{color:var(--text3);font-family:var(--mono);letter-spacing:.04em;font-size:10.5px;}

/* UNSAVED INDICATOR */
.unsaved-dot{width:7px;height:7px;border-radius:50%;background:var(--amber);display:inline-block;margin-right:5px;animation:pulse 1.8s ease-in-out infinite;vertical-align:middle;}
.unsaved-bar{background:rgba(245,158,11,.09);border:1px solid rgba(245,158,11,.25);border-radius:var(--r-sm);padding:10px 13px;font-size:11.5px;font-weight:500;color:#92400e;display:none;align-items:center;gap:8px;margin-bottom:14px;}
.unsaved-bar.show{display:flex;}
[data-theme="dark"] .unsaved-bar{color:#fbbf24;}

/* SECTION DIVIDER */
.section-divider{display:flex;align-items:center;gap:10px;margin:22px 0 18px;}
.section-divider-line{flex:1;height:1px;background:var(--border);}
.section-divider-label{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;font-family:var(--mono);white-space:nowrap;}

/* MODAL */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:500;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px);opacity:0;pointer-events:none;transition:opacity var(--ease);}
.modal-overlay.show{opacity:1;pointer-events:all;}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:var(--r);padding:24px;max-width:380px;width:90%;box-shadow:var(--sh-lg);transform:scale(.95);transition:transform var(--ease);}
.modal-overlay.show .modal{transform:scale(1);}
.modal-title{font-family:var(--mono);font-size:16px;font-weight:800;color:var(--text);margin-bottom:7px;}
.modal-body{font-size:13px;color:var(--text2);line-height:1.6;margin-bottom:18px;}
.modal-actions{display:flex;gap:8px;}
.modal-actions .action-btn{flex:1;margin:0;}

/* ANIMATIONS */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:none;}}
@keyframes toastIn{from{opacity:0;transform:translateX(20px) scale(.96);}to{opacity:1;transform:none;}}
@keyframes pulse{0%,100%{opacity:1;}50%{opacity:.4;}}

/* RESPONSIVE */
@media(max-width:960px){.page-grid{grid-template-columns:1fr;}.right-col{position:static;}.input-row{grid-template-columns:1fr;}}
@media(max-width:860px){.sidebar{transform:translateX(-100%);}.sidebar.open{transform:translateX(0);}.main{margin-left:0;}.hamburger{display:flex;}.body{padding:14px 14px 60px;}}
@media(max-width:600px){.topbar{padding:0 14px;}}
</style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

{{-- Discard Confirm Modal --}}
<div class="modal-overlay" id="discardModal">
    <div class="modal">
        <div class="modal-title">Discard Changes?</div>
        <p class="modal-body">You have unsaved changes. Are you sure you want to leave? All edits will be lost.</p>
        <div class="modal-actions">
            <button type="button" class="action-btn btn-ghost" onclick="closeDiscardModal()">Keep Editing</button>
            <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="action-btn btn-red" id="discardConfirmBtn">Discard</a>
        </div>
    </div>
</div>

<div class="shell">

@php
    $state = $campaign->campaign_state;
    $chipLabel = match($state) {
        'active'    => 'Active',
        'paused'    => 'Paused',
        'pending'   => 'Pending',
        'rejected'  => 'Rejected',
        'expired'   => 'Expired',
        'completed' => 'Completed',
        default     => ucfirst($state ?? 'Unknown'),
    };
    $raised     = $campaign->raised_amount ?? 0;
    $goal       = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
    $percentage = min(100, round(($raised / $goal) * 100));
@endphp

{{-- ══ SIDEBAR ══ --}}
<aside class="sidebar" id="sidebar">
    <div class="s-logo">
        <div class="s-logo-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div>
            <div class="s-logo-name">DonateBazaar</div>
            <div class="s-logo-tag">Admin Portal</div>
        </div>
    </div>

    <div class="s-admin-pill">
        <div class="s-av">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:9px;">
            @else
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            @endif
        </div>
        <div style="flex:1;overflow:hidden;">
            <div class="s-admin-name">{{ auth()->user()->name ?? 'Admin' }}</div>
            <div class="s-admin-role">Administrator</div>
        </div>
        <div class="s-online"></div>
    </div>

    <div class="s-section">Overview</div>
    <nav class="s-nav">
        <a href="{{ url('/admin/dashboard') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
    </nav>

    <div class="s-section">Campaigns</div>
    <nav class="s-nav">
        <a href="{{ url('/admin/dashboard') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            All Campaigns
        </a>
    </nav>

    <div class="s-section">Manage</div>
    <nav class="s-nav">
        <a href="{{ url('/admin/categories') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
            Categories
        </a>
        <a href="{{ url('/admin/partnerships') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
            Partnerships
        </a>
        <a href="{{ url('/admin/messages') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Messages
        </a>
        <a href="{{ url('/admin/blogs') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            Blogs
        </a>
    </nav>

    <div class="s-section">Job Board</div>
    <nav class="s-nav">
        <a href="{{ route('admin.job_posts.index') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            All Job Posts
        </a>
        <a href="{{ route('admin.job_posts.create') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Post a Job
        </a>
        <a href="{{ route('admin.job_post_applications.index') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
            Job Applicants
        </a>
    </nav>

    <div class="s-divider"></div>

    <div class="s-bottom">
        <a href="{{ url('/admin/profile') }}" class="s-link" style="color:var(--a);margin-bottom:2px;">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            My Profile
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('lf').submit();" class="s-link" style="color:var(--red);">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
        <form id="lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>
</aside>

{{-- ══ MAIN ══ --}}
<div class="main">

    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="topbar-back" title="Back to Campaign" id="backBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </a>
            <div class="topbar-title">
                <h1>Edit Campaign</h1>
                <p>{{ Str::limit($campaign->title, 42) }}</p>
            </div>
        </div>
        <div class="topbar-right">
            <span class="badge b-{{ $state }}">
                <span class="badge-dot"></span>
                {{ $chipLabel }}
            </span>
            <button class="icon-btn" title="Notifications">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span class="dot"></span>
            </button>
            <div class="theme-toggle" title="Toggle dark mode">
                <input type="checkbox" id="themeToggle">
                <label for="themeToggle">
                    <div class="theme-icons">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    </div>
                </label>
            </div>
            <div class="top-avatar">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:9px;">
                @else
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                @endif
            </div>
        </div>
    </header>

    <div class="body">

        @if(session('success'))
        <div class="flash-success">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flash-error">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Unsaved indicator --}}
        <div class="unsaved-bar" id="unsavedBar">
            <span class="unsaved-dot"></span>
            You have unsaved changes
        </div>

        <form
            id="editForm"
            action="{{ route('admin.campaign.update', $campaign->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PUT')

            <div class="page-grid">

                {{-- ════ LEFT COLUMN ════ --}}
                <div>

                    {{-- Cover Image --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-icon ic-indigo">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                </div>
                                <div>
                                    <div class="card-title">Cover Image</div>
                                    <div class="card-sub">Recommended: 1200×630px, max 5 MB</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group" style="margin-bottom:0;">
                                <div class="cover-upload-zone" id="coverZone">
                                    {{-- Hidden: shown when existing cover present --}}
                                    <div class="cover-preview-wrap" id="coverPreviewWrap"
                                        style="{{ $campaign->cover_image ? '' : 'display:none;' }}">
                                        <img
                                            id="coverPreviewImg"
                                            src="{{ $campaign->cover_image ? asset('storage/' . $campaign->cover_image) : '' }}"
                                            alt="Cover preview"
                                            class="cover-preview-img"
                                        >
                                        <div class="cover-preview-overlay">
                                            <label for="coverInput" class="cover-preview-btn cover-preview-btn-change" style="cursor:pointer;">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                Change
                                            </label>
                                            <button type="button" class="cover-preview-btn cover-preview-btn-remove" onclick="removeCover()">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Remove
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Placeholder: shown when no cover --}}
                                    <div class="cover-upload-placeholder" id="coverPlaceholder"
                                        style="{{ $campaign->cover_image ? 'display:none;' : '' }}">
                                        <div class="cover-upload-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        </div>
                                        <div class="cover-upload-text">Click or drag to upload a cover</div>
                                        <div class="cover-upload-hint">JPG, PNG, WEBP — Max 5 MB</div>
                                    </div>

                                    <input
                                        type="file"
                                        id="coverInput"
                                        name="cover_image"
                                        accept="image/*"
                                        style="position:absolute;inset:0;opacity:0;cursor:pointer;z-index:2;width:100%;height:100%;"
                                    >
                                </div>
                                {{-- Hidden flag to signal removal --}}
                                <input type="hidden" name="remove_cover" id="removeCoverFlag" value="0">
                                @error('cover_image')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Basic Info --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-icon ic-indigo">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </div>
                                <div>
                                    <div class="card-title">Basic Information</div>
                                    <div class="card-sub">Core campaign details</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            {{-- Title --}}
                            <div class="form-group">
                                <label class="form-label" for="title">
                                    Campaign Title <span class="req">*</span>
                                    <span class="char-count" id="titleCount">{{ strlen(old('title', $campaign->title)) }}/120</span>
                                </label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    class="form-input @error('title') is-invalid @enderror"
                                    value="{{ old('title', $campaign->title) }}"
                                    placeholder="Enter campaign title…"
                                    maxlength="120"
                                    required
                                    autocomplete="off"
                                >
                                @error('title')
                                    <div class="form-error">{{ $message }}</div>
                                @else
                                    <div class="form-hint">Keep it concise and compelling.</div>
                                @enderror
                            </div>

                            {{-- Category & Status row --}}
                            <div class="input-row">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label class="form-label" for="category_id">Category <span class="req">*</span></label>
                                    <select
                                        id="category_id"
                                        name="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">Select category…</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('category_id', $campaign->category_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label class="form-label" for="campaign_state">Status</label>
                                    <select
                                        id="campaign_state"
                                        name="campaign_state"
                                        class="form-select @error('campaign_state') is-invalid @enderror"
                                    >
                                        @foreach(['pending','active','paused','rejected','expired','completed'] as $s)
                                            <option value="{{ $s }}"
                                                {{ old('campaign_state', $campaign->campaign_state) === $s ? 'selected' : '' }}>
                                                {{ ucfirst($s) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('campaign_state')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-icon ic-blue">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <div class="card-title">Description</div>
                                    <div class="card-sub">Campaign story &amp; details</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" for="description">
                                    Description <span class="req">*</span>
                                    <span class="char-count" id="descCount">{{ strlen(old('description', $campaign->description)) }}/5000</span>
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    class="form-textarea @error('description') is-invalid @enderror"
                                    placeholder="Describe what this campaign is about, its goals, and how funds will be used…"
                                    maxlength="5000"
                                    required
                                    style="min-height:180px;"
                                >{{ old('description', $campaign->description) }}</textarea>
                                @error('description')
                                    <div class="form-error">{{ $message }}</div>
                                @else
                                    <div class="form-hint">Be clear and transparent to build donor trust.</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Financials & Dates --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-icon ic-green">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <div class="card-title">Financials &amp; Dates</div>
                                    <div class="card-sub">Funding goal and timeline</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="input-row">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label class="form-label" for="goal_amount">Goal Amount (₹) <span class="req">*</span></label>
                                    <input
                                        type="number"
                                        id="goal_amount"
                                        name="goal_amount"
                                        class="form-input @error('goal_amount') is-invalid @enderror"
                                        value="{{ old('goal_amount', $campaign->goal_amount) }}"
                                        placeholder="e.g. 100000"
                                        min="1"
                                        step="1"
                                        required
                                    >
                                    @error('goal_amount')
                                        <div class="form-error">{{ $message }}</div>
                                    @else
                                        <div class="form-hint">Minimum ₹1</div>
                                    @enderror
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label class="form-label" for="end_date">End Date</label>
                                    <input
                                        type="date"
                                        id="end_date"
                                        name="end_date"
                                        class="form-input @error('end_date') is-invalid @enderror"
                                        value="{{ old('end_date', $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->format('Y-m-d') : '') }}"
                                    >
                                    @error('end_date')
                                        <div class="form-error">{{ $message }}</div>
                                    @else
                                        <div class="form-hint">Leave empty for no deadline.</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- /left --}}

                {{-- ════ RIGHT COLUMN ════ --}}
                <div class="right-col">

                    {{-- Save Actions --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-icon ic-green">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                </div>
                                <div>
                                    <div class="card-title">Save Changes</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="padding-top:14px;padding-bottom:14px;">
                            <button type="submit" class="action-btn btn-accent" id="saveBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                Save Changes
                            </button>
                            <button type="button" class="action-btn btn-ghost" id="discardBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Discard &amp; Go Back
                            </button>
                            <p style="font-size:10.5px;color:var(--text3);margin-top:10px;font-family:var(--mono);text-align:center;line-height:1.6;">
                                Changes are saved immediately.<br>This cannot be undone.
                            </p>
                        </div>
                    </div>

                    {{-- Current Progress (read-only) --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-icon ic-indigo">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </div>
                                <div>
                                    <div class="card-title">Current Progress</div>
                                    <div class="card-sub">Read-only live snapshot</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:9px;">
                                <div style="font-size:24px;font-weight:800;color:var(--a);letter-spacing:-.03em;font-family:var(--mono);line-height:1;">₹{{ number_format($raised) }}</div>
                                <div style="font-size:11.5px;color:var(--text3);font-family:var(--mono);">of ₹{{ number_format($campaign->goal_amount) }}</div>
                            </div>
                            <div style="width:100%;background:var(--surface2);border-radius:100px;height:5px;overflow:hidden;margin-bottom:5px;border:1px solid var(--border);">
                                <div style="height:100%;border-radius:100px;background:linear-gradient(90deg,var(--a),var(--a2));width:{{ $percentage }}%;transition:width 1.2s ease;"></div>
                            </div>
                            <div style="font-size:10.5px;color:var(--text3);font-family:var(--mono);">{{ $percentage }}% funded</div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-top:14px;">
                                <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:11px;text-align:center;">
                                    <div style="font-size:17px;font-weight:800;color:var(--text);font-family:var(--mono);line-height:1;">{{ $percentage }}%</div>
                                    <div style="font-size:9.5px;color:var(--text3);margin-top:4px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;">Funded</div>
                                </div>
                                <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:11px;text-align:center;">
                                    <div style="font-size:14px;font-weight:800;color:var(--text);font-family:var(--mono);line-height:1;">₹{{ number_format(max(0, $campaign->goal_amount - $raised)) }}</div>
                                    <div style="font-size:9.5px;color:var(--text3);margin-top:4px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;">Remaining</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Campaign Meta (read-only) --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-icon ic-pink">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <div class="card-title">Campaign Meta</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="padding-top:10px;padding-bottom:10px;">
                            <div class="info-row">
                                <span class="info-row-lbl">FUNDRAISER</span>
                                <span style="font-size:11.5px;font-weight:600;color:var(--text);">{{ $campaign->user->name ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-lbl">EMAIL</span>
                                <span style="font-size:11px;color:var(--text2);font-family:var(--mono);">{{ $campaign->user->email ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-lbl">CAMPAIGN ID</span>
                                <span style="font-size:11px;color:var(--text3);font-family:var(--mono);">#{{ $campaign->id }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-lbl">CREATED</span>
                                <span style="font-size:11px;color:var(--text2);">{{ $campaign->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-lbl">LAST UPDATED</span>
                                <span style="font-size:11px;color:var(--text2);">{{ $campaign->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-lbl">EVENTS</span>
                                <span style="font-weight:700;color:var(--text);font-family:var(--mono);">{{ $campaign->events->count() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- View Campaign Link --}}
                    <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="action-btn btn-ghost" style="text-decoration:none;display:flex;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        View Campaign Page
                    </a>

                </div>{{-- /right-col --}}

            </div>{{-- /page-grid --}}
        </form>

    </div>{{-- /body --}}
</div>{{-- /main --}}
</div>{{-- /shell --}}

<script>
/* ── Dark mode ── */
var html   = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved  = localStorage.getItem('adminTheme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
    var t = this.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', t);
    localStorage.setItem('adminTheme', t);
});

/* ── Hamburger ── */
var sidebar = document.getElementById('sidebar');
document.getElementById('hamburger').addEventListener('click', function(){
    sidebar.classList.toggle('open');
});
document.addEventListener('click', function(e){
    if (window.innerWidth <= 860 && !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target))
        sidebar.classList.remove('open');
});

/* ── Char counters ── */
function makeCounter(inputId, countId, max) {
    var el  = document.getElementById(inputId);
    var cnt = document.getElementById(countId);
    if (!el || !cnt) return;
    function update() {
        var len = el.value.length;
        cnt.textContent = len + '/' + max;
        cnt.className = 'char-count' + (len > max * .9 ? (len >= max ? ' over' : ' warn') : '');
    }
    el.addEventListener('input', update);
    update();
}
makeCounter('title',       'titleCount', 120);
makeCounter('description', 'descCount',  5000);

/* ── Cover image preview ── */
var coverInput       = document.getElementById('coverInput');
var coverZone        = document.getElementById('coverZone');
var coverPreviewWrap = document.getElementById('coverPreviewWrap');
var coverPreviewImg  = document.getElementById('coverPreviewImg');
var coverPlaceholder = document.getElementById('coverPlaceholder');
var removeCoverFlag  = document.getElementById('removeCoverFlag');

coverInput.addEventListener('change', function(){
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e){
        coverPreviewImg.src = e.target.result;
        coverPreviewWrap.style.display = '';
        coverPlaceholder.style.display = 'none';
        removeCoverFlag.value = '0';
        markDirty();
    };
    reader.readAsDataURL(file);
});

function removeCover() {
    coverInput.value = '';
    coverPreviewImg.src = '';
    coverPreviewWrap.style.display = 'none';
    coverPlaceholder.style.display = '';
    removeCoverFlag.value = '1';
    markDirty();
}

/* Drag-over styling */
coverZone.addEventListener('dragover', function(e){ e.preventDefault(); this.classList.add('drag-over'); });
coverZone.addEventListener('dragleave', function(){ this.classList.remove('drag-over'); });
coverZone.addEventListener('drop', function(e){
    e.preventDefault(); this.classList.remove('drag-over');
    var file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        var dt = new DataTransfer();
        dt.items.add(file);
        coverInput.files = dt.files;
        coverInput.dispatchEvent(new Event('change'));
    }
});

/* ── Unsaved changes tracker ── */
var isDirty = false;
var unsavedBar = document.getElementById('unsavedBar');

function markDirty() {
    if (!isDirty) {
        isDirty = true;
        unsavedBar.classList.add('show');
    }
}

var formFields = document.querySelectorAll('#editForm input, #editForm textarea, #editForm select');
formFields.forEach(function(f) {
    f.addEventListener('change', markDirty);
    f.addEventListener('input',  markDirty);
});

/* Clear dirty flag on save */
document.getElementById('editForm').addEventListener('submit', function(){
    isDirty = false;
});

/* ── Discard modal ── */
document.getElementById('discardBtn').addEventListener('click', function(){
    if (isDirty) {
        document.getElementById('discardModal').classList.add('show');
    } else {
        window.location.href = '{{ route('admin.campaign.show', $campaign->id) }}';
    }
});

/* Intercept back button when dirty */
document.getElementById('backBtn').addEventListener('click', function(e){
    if (isDirty) {
        e.preventDefault();
        document.getElementById('discardModal').classList.add('show');
    }
});

function closeDiscardModal() {
    document.getElementById('discardModal').classList.remove('show');
}

document.getElementById('discardModal').addEventListener('click', function(e){
    if (e.target === this) closeDiscardModal();
});
document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') closeDiscardModal();
});

/* Warn on browser navigation away */
window.addEventListener('beforeunload', function(e){
    if (isDirty) {
        e.preventDefault();
        e.returnValue = '';
    }
});

/* ── Save button loading state ── */
document.getElementById('editForm').addEventListener('submit', function(){
    var btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;animation:spin .7s linear infinite;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 018-8v4l3-3-3-3v4A10 10 0 002 12h2z"/></svg> Saving…';
    btn.style.opacity = '.75';
});

/* ── Toast ── */
function showToast(msg, type) {
    var c = document.getElementById('toastContainer');
    var t = document.createElement('div');
    t.className = 'toast toast-' + type;
    var icon = type === 'success'
        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    t.innerHTML = icon + '<span>' + msg + '</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
    c.appendChild(t);
    setTimeout(function(){
        t.style.transition = 'opacity .3s, transform .3s';
        t.style.opacity = '0';
        t.style.transform = 'translateX(20px)';
        setTimeout(function(){ t.remove(); }, 300);
    }, 4500);
}
@if(session('success'))
window.addEventListener('DOMContentLoaded', function(){ showToast(@json(session('success')), 'success'); });
@endif
@if(session('error'))
window.addEventListener('DOMContentLoaded', function(){ showToast(@json(session('error')), 'error'); });
@endif

/* ── Spin keyframe for save button ── */
var style = document.createElement('style');
style.textContent = '@keyframes spin{to{transform:rotate(360deg);}}';
document.head.appendChild(style);
</script>
</body>
</html>