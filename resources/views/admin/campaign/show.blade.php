<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $campaign->title }} — DonateBazaar Admin</title>
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
.shell{display:flex;min-height:100vh;}
.sidebar{width:var(--sb-w);flex-shrink:0;background:var(--sb-bg);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:400;overflow-y:auto;overflow-x:hidden;border-right:1px solid var(--sb-border);box-shadow:2px 0 16px rgba(0,0,0,.06);transition:transform .3s cubic-bezier(.4,0,.2,1);}
.sidebar::-webkit-scrollbar{width:0;}
.s-logo{display:flex;align-items:center;gap:12px;padding:26px 22px 22px;border-bottom:1px solid var(--sb-border);}
.s-logo-mark{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--a),var(--a2));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 18px rgba(110,86,247,.4);}
.s-logo-mark svg{width:20px;height:20px;color:#fff;}
.s-logo-name{font-family:var(--mono);font-size:17px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1.1;}
.s-logo-tag{font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.16em;font-family:var(--mono);}
.s-admin-pill{margin:14px 12px 4px;padding:10px 14px;background:linear-gradient(135deg,rgba(110,86,247,.08),rgba(155,109,255,.05));border:1px solid rgba(110,86,247,.15);border-radius:var(--r-sm);display:flex;align-items:center;gap:10px;}
.s-av{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
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
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;}
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
.top-avatar{width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;font-family:var(--mono);cursor:pointer;flex-shrink:0;box-shadow:0 2px 10px rgba(110,86,247,.38);overflow:hidden;}
.hamburger{display:none;width:34px;height:34px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);align-items:center;justify-content:center;color:var(--text2);flex-shrink:0;}
.hamburger svg{width:15px;height:15px;}
.body{padding:22px 24px 60px;flex:1;}
.toast-container{position:fixed;top:18px;right:18px;z-index:9999;display:flex;flex-direction:column;gap:9px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:9px;padding:12px 14px;border-radius:12px;font-size:12.5px;font-weight:500;color:#fff;min-width:260px;max-width:360px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s cubic-bezier(.4,0,.2,1) both;}
.toast-success{background:linear-gradient(135deg,#059669,#10b981);}
.toast-error{background:linear-gradient(135deg,#dc2626,#ef4444);}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-x{margin-left:auto;width:17px;height:17px;border-radius:4px;background:rgba(255,255,255,.2);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}
.page-grid{display:grid;grid-template-columns:1fr 308px;gap:20px;align-items:start;}
.right-col{position:sticky;top:80px;display:flex;flex-direction:column;gap:16px;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s both;}
.card:nth-child(1){animation-delay:.05s;}.card:nth-child(2){animation-delay:.10s;}.card:nth-child(3){animation-delay:.15s;}.card:nth-child(4){animation-delay:.20s;}.card:nth-child(5){animation-delay:.25s;}.card:nth-child(6){animation-delay:.30s;}.card:nth-child(7){animation-delay:.35s;}
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
.ic-amber{background:rgba(245,158,11,.12);color:#b45309;}
.card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-.01em;font-family:var(--mono);}
.card-sub{font-size:11px;color:var(--text3);margin-top:1px;}
.card-body{padding:18px;}
.cover-wrap{position:relative;overflow:hidden;}
.cover-wrap img{width:100%;height:320px;object-fit:cover;display:block;transition:transform .6s ease;}
.cover-wrap:hover img{transform:scale(1.02);}
.cover-placeholder{width:100%;height:240px;background:var(--surface2);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;}
.cover-placeholder svg{width:36px;height:36px;color:var(--text3);opacity:.3;}
.cover-placeholder span{font-size:12px;color:var(--text3);}
.cover-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.55) 0%,transparent 55%);pointer-events:none;}
.cover-meta{position:absolute;bottom:14px;left:16px;right:16px;display:flex;align-items:flex-end;justify-content:space-between;}
.cover-title{font-family:var(--mono);font-size:18px;font-weight:800;color:#fff;letter-spacing:-.02em;line-height:1.3;text-shadow:0 1px 4px rgba(0,0,0,.4);}
.cover-created{font-size:10.5px;color:rgba(255,255,255,.6);font-family:var(--mono);margin-top:3px;}
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3px 9px;border-radius:100px;text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);white-space:nowrap;}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
.b-pending  {background:rgba(245,158,11,.15);color:#b45309;border:1px solid rgba(245,158,11,.30);}
.b-active   {background:rgba(16,185,129,.15);color:#065f46;border:1px solid rgba(16,185,129,.30);}
.b-approved {background:rgba(16,185,129,.15);color:#065f46;border:1px solid rgba(16,185,129,.30);}
.b-rejected {background:rgba(239,68,68,.15);color:#991b1b;border:1px solid rgba(239,68,68,.30);}
.b-paused   {background:rgba(99,102,241,.15);color:#3730a3;border:1px solid rgba(99,102,241,.30);}
.b-expired  {background:rgba(107,114,128,.15);color:#374151;border:1px solid rgba(107,114,128,.30);}
.b-completed{background:rgba(59,130,246,.15);color:#1e40af;border:1px solid rgba(59,130,246,.30);}
[data-theme="dark"] .b-pending  {color:#fbbf24;}[data-theme="dark"] .b-active{color:#34d399;}[data-theme="dark"] .b-approved{color:#34d399;}[data-theme="dark"] .b-rejected{color:#f87171;}[data-theme="dark"] .b-paused{color:#a5b4fc;}[data-theme="dark"] .b-expired{color:#9ca3af;}[data-theme="dark"] .b-completed{color:#93c5fd;}
.prog-numbers{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:9px;}
.prog-raised{font-size:26px;font-weight:800;color:var(--a);letter-spacing:-.03em;font-family:var(--mono);line-height:1;}
.prog-goal{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.prog-bar{width:100%;background:var(--surface2);border-radius:100px;height:5px;overflow:hidden;margin-bottom:5px;border:1px solid var(--border);}
.prog-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--a),var(--a2));transition:width 1.2s ease;}
.prog-pct{font-size:10.5px;color:var(--text3);font-family:var(--mono);}
.mini-stats{display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-top:14px;}
.mini-stat{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:11px;text-align:center;}
.mini-stat-val{font-size:17px;font-weight:800;color:var(--text);font-family:var(--mono);line-height:1;}
.mini-stat-lbl{font-size:9.5px;color:var(--text3);margin-top:4px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.desc-text{font-size:13.5px;color:var(--text2);line-height:1.75;}
.kyc-notice{border-radius:var(--r-sm);padding:11px 13px;font-size:12.5px;margin-bottom:12px;}
.kyc-notice-red   {background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#dc2626;}
.kyc-notice-yellow{background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);color:#b45309;}
.kyc-notice-green {background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);color:#065f46;}
[data-theme="dark"] .kyc-notice-red{color:#f87171;}[data-theme="dark"] .kyc-notice-yellow{color:#fbbf24;}[data-theme="dark"] .kyc-notice-green{color:#34d399;}
.kyc-notice-title{font-weight:700;margin-bottom:3px;font-size:11.5px;font-family:var(--mono);}
.kyc-notice-body{font-size:11.5px;opacity:.9;}
.kyc-doc-row{display:flex;align-items:center;gap:10px;padding:10px 12px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);margin-bottom:12px;}
.kyc-doc-icon{width:32px;height:32px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:15px;}
.kyc-doc-type{font-size:12px;font-weight:700;color:var(--text);}
.kyc-doc-num{font-size:10.5px;color:var(--text3);font-family:var(--mono);}
.kyc-doc-preview{border:1px solid var(--border2);border-radius:var(--r-sm);overflow:hidden;background:var(--surface2);margin-top:4px;}
.kyc-doc-preview-header{display:flex;align-items:center;justify-content:space-between;padding:9px 12px;border-bottom:1px solid var(--border);font-size:11.5px;font-weight:700;color:var(--text2);font-family:var(--mono);}
.kyc-doc-preview-header-left{display:flex;align-items:center;gap:6px;}
.kyc-doc-preview-actions{display:flex;gap:6px;}
.kyc-doc-btn{display:inline-flex;align-items:center;gap:4px;font-size:10.5px;font-weight:600;padding:3px 9px;border-radius:6px;font-family:var(--font);transition:background var(--ease);border:none;cursor:pointer;text-decoration:none;}
.kyc-doc-btn-view{background:var(--a-lt);color:var(--a);border:1px solid rgba(110,86,247,.20);}
.kyc-doc-btn-view:hover{background:rgba(110,86,247,.20);}
.kyc-doc-btn-dl{background:var(--green-lt);color:var(--green);border:1px solid rgba(5,196,138,.20);}
.kyc-doc-btn-dl:hover{background:rgba(5,196,138,.20);}
.kyc-doc-btn svg{width:10px;height:10px;flex-shrink:0;}
.kyc-doc-preview-img{width:100%;max-height:340px;object-fit:contain;display:block;cursor:zoom-in;}
.kyc-doc-preview-iframe{width:100%;height:340px;border:none;display:block;}
.kyc-doc-preview-fallback{padding:20px;text-align:center;font-size:12px;color:var(--text3);}
.kyc-pill{display:flex;align-items:center;justify-content:space-between;padding:9px 12px;border-radius:var(--r-sm);font-size:12px;font-weight:600;margin-bottom:11px;}
.kyc-pill-pending {background:rgba(245,158,11,.10);border:1px solid rgba(245,158,11,.25);color:#b45309;}
.kyc-pill-approved{background:rgba(16,185,129,.10);border:1px solid rgba(16,185,129,.25);color:#065f46;}
.kyc-pill-rejected{background:rgba(239,68,68,.10);border:1px solid rgba(239,68,68,.25);color:#dc2626;}
.kyc-pill-none    {background:rgba(239,68,68,.10);border:1px solid rgba(239,68,68,.25);color:#dc2626;}
[data-theme="dark"] .kyc-pill-pending{color:#fbbf24;}[data-theme="dark"] .kyc-pill-approved{color:#34d399;}[data-theme="dark"] .kyc-pill-rejected{color:#f87171;}[data-theme="dark"] .kyc-pill-none{color:#f87171;}
.info-row{display:flex;justify-content:space-between;align-items:center;font-size:12px;padding:9px 0;}
.info-row+.info-row{border-top:1px solid var(--border);}
.info-row-lbl{color:var(--text3);font-family:var(--mono);letter-spacing:.04em;font-size:10.5px;}
.status-section{padding:16px;}
.status-section-label{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;font-family:var(--mono);margin-bottom:10px;}
.status-chips{display:flex;align-items:center;gap:7px;margin-bottom:12px;flex-wrap:wrap;}
.status-chip-lg{display:inline-flex;align-items:center;gap:5px;padding:5px 13px;border-radius:100px;font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;font-family:var(--mono);}
.chip-active   {background:rgba(16,185,129,.12);color:#10b981;border:1px solid rgba(16,185,129,.25);}
.chip-paused   {background:rgba(110,86,247,.12);color:#818cf8;border:1px solid rgba(110,86,247,.25);}
.chip-pending  {background:rgba(245,158,11,.12);color:#f59e0b;border:1px solid rgba(245,158,11,.25);}
.chip-rejected {background:rgba(239,68,68,.12);color:#ef4444;border:1px solid rgba(239,68,68,.25);}
.chip-expired  {background:rgba(107,114,128,.12);color:#6b7280;border:1px solid rgba(107,114,128,.25);}
.chip-completed{background:rgba(59,130,246,.12);color:#3b82f6;border:1px solid rgba(59,130,246,.25);}
.chip-dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;}
.action-btn{display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:10px 16px;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;cursor:pointer;border:1px solid transparent;font-family:var(--font);transition:opacity var(--ease),transform var(--ease),box-shadow var(--ease);text-decoration:none;letter-spacing:.01em;}
.action-btn:hover{opacity:.88;transform:translateY(-1px);}
.action-btn svg{width:13px;height:13px;}
.action-btn+.action-btn{margin-top:8px;}
.btn-accent{background:var(--a);color:#fff;border-color:var(--a);box-shadow:0 4px 14px rgba(110,86,247,.28);}
.btn-green {background:var(--green);color:#fff;border-color:var(--green);box-shadow:0 4px 14px rgba(5,196,138,.28);}
.btn-red   {background:rgba(240,68,68,.1);color:#b91c1c;border-color:rgba(240,68,68,.25);box-shadow:0 4px 14px rgba(255,255,255,.08);}
.btn-yellow{background:rgba(245,158,11,.08);color:var(--amber);border-color:rgba(245,158,11,.3);box-shadow:0 4px 14px rgba(255,255,255,.08);}
.btn-ghost {background:var(--surface2);color:var(--text2);border-color:var(--border2);}
.btn-disabled{background:var(--surface2);color:var(--text3);border-color:var(--border);opacity:.5;cursor:not-allowed;transform:none !important;box-shadow:none !important;}
[data-theme="dark"] .btn-red{color:#f87171;}
.events-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:13px;}
.event-card{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:15px;transition:transform var(--ease),box-shadow var(--ease),border-color var(--ease);}
.event-card:hover{transform:translateY(-3px);box-shadow:0 10px 32px rgba(110,86,247,.10);border-color:rgba(110,86,247,.2);}
.event-badge{display:inline-flex;align-items:font-size:10px;font-weight:700;padding:3px 8px;border-radius:100px;text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);margin-bottom:9px;}
.ev-approved{background:rgba(16,185,129,.12);color:#10b981;border:1px solid rgba(16,185,129,.25);}
.ev-pending {background:rgba(245,158,11,.12);color:#f59e0b;border:1px solid rgba(245,158,11,.25);}
.ev-default {background:rgba(107,114,128,.10);color:var(--text3);border:1px solid var(--border2);}
.event-title{font-size:13px;font-weight:700;color:var(--text);margin-bottom:3px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-family:var(--mono);}
.event-date{font-size:11px;color:var(--text3);font-family:var(--mono);margin-bottom:7px;}
.event-desc{font-size:12px;color:var(--text2);line-height:1.6;margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.event-link{font-size:11px;font-weight:600;color:var(--a);text-decoration:none;display:inline-flex;align-items:center;gap:4px;}
.event-link:hover{opacity:.75;}
.empty-state{padding:36px 20px;text-align:center;background:var(--surface2);border-radius:var(--r-sm);}
.empty-state svg{width:32px;height:32px;color:var(--text3);opacity:.25;margin:0 auto 9px;display:block;}
.empty-state p{font-size:12.5px;color:var(--text3);}
.flash-success{background:rgba(16,185,129,.09);border:1px solid rgba(16,185,129,.25);color:#065f46;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.flash-error  {background:rgba(239,68,68,.09);border:1px solid rgba(239,68,68,.25);color:#dc2626;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
[data-theme="dark"] .flash-success{color:#34d399;}[data-theme="dark"] .flash-error{color:#f87171;}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:500;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px);opacity:0;pointer-events:none;transition:opacity var(--ease);}
.modal-overlay.show{opacity:1;pointer-events:all;}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:var(--r);padding:24px;max-width:380px;width:90%;box-shadow:var(--sh-lg);transform:scale(.95);transition:transform var(--ease);}
.modal-overlay.show .modal{transform:scale(1);}
.modal-title{font-family:var(--mono);font-size:16px;font-weight:800;color:var(--text);margin-bottom:7px;}
.modal-body{font-size:13px;color:var(--text2);line-height:1.6;margin-bottom:18px;}
.modal-actions{display:flex;gap:8px;}
.modal-actions .action-btn{flex:1;margin:0;}

/* ── NEW: KYC Multi-doc grid ── */
.kyc-docs-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;}
@media(max-width:640px){.kyc-docs-grid{grid-template-columns:1fr;}}
.kyc-doc-tile{border:1px solid var(--border2);border-radius:var(--r-sm);overflow:hidden;background:var(--surface2);}
.kyc-doc-tile-header{display:flex;align-items:center;justify-content:space-between;padding:9px 12px;border-bottom:1px solid var(--border);background:var(--surface);}
.kyc-doc-tile-label{display:flex;align-items:center;gap:6px;font-size:11px;font-weight:700;color:var(--text2);font-family:var(--mono);}
.kyc-doc-tile-label svg{width:12px;height:12px;}
.kyc-doc-tile-img{width:100%;height:160px;object-fit:cover;display:block;cursor:zoom-in;transition:opacity .2s;}
.kyc-doc-tile-img:hover{opacity:.85;}
.kyc-doc-tile-pdf{width:100%;height:160px;border:none;display:block;}
.kyc-doc-tile-missing{height:90px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;}
.kyc-doc-tile-missing svg{width:22px;height:22px;color:var(--text3);opacity:.3;}
.kyc-doc-tile-missing span{font-size:11px;color:var(--text3);}
.kyc-doc-tile-actions{display:flex;gap:6px;padding:8px 10px;border-top:1px solid var(--border);}
.kyc-selfie-wrap{display:flex;gap:12px;align-items:flex-start;margin-bottom:16px;}
.kyc-selfie-img{width:120px;height:120px;object-fit:cover;border-radius:var(--r-sm);border:2px solid var(--border2);flex-shrink:0;cursor:zoom-in;}
.kyc-selfie-missing{width:120px;height:120px;background:var(--surface2);border:1px dashed var(--border2);border-radius:var(--r-sm);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;flex-shrink:0;}
.kyc-selfie-missing svg{width:24px;height:24px;color:var(--text3);opacity:.3;}
.kyc-selfie-missing span{font-size:10px;color:var(--text3);}
.kyc-selfie-info{flex:1;}
.kyc-selfie-title{font-size:12px;font-weight:700;color:var(--text);font-family:var(--mono);margin-bottom:6px;}
.kyc-selfie-sub{font-size:11px;color:var(--text3);line-height:1.6;}

/* ── NEW: Bank account section ── */
.bank-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
@media(max-width:640px){.bank-grid{grid-template-columns:1fr;}}
.bank-field{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:10px 12px;}
.bank-field-lbl{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;font-family:var(--mono);margin-bottom:4px;}
.bank-field-val{font-size:13px;font-weight:600;color:var(--text);font-family:var(--mono);}
.bank-field-val.empty{color:var(--text3);font-style:italic;font-family:var(--font);font-weight:400;font-size:12px;}

/* ── NEW: Updates section ── */
.updates-list{display:flex;flex-direction:column;gap:10px;}
.update-item{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:13px 15px;transition:border-color var(--tr);}
.update-item:hover{border-color:rgba(110,86,247,.2);}
.update-item-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:5px;}
.update-item-title{font-size:13px;font-weight:700;color:var(--text);font-family:var(--mono);}
.update-item-date{font-size:10px;color:var(--text3);font-family:var(--mono);}
.update-item-body{font-size:12.5px;color:var(--text2);line-height:1.65;margin-bottom:8px;}
.update-doc-pill{display:inline-flex;align-items:center;gap:5px;background:var(--a-lt);color:var(--a);border:1px solid rgba(110,86,247,.15);border-radius:100px;padding:3px 10px;font-size:10.5px;font-weight:600;text-decoration:none;font-family:var(--mono);transition:background var(--ease);}
.update-doc-pill:hover{background:rgba(110,86,247,.18);}
.update-doc-pill svg{width:10px;height:10px;}

@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:none;}}
@keyframes toastIn{from{opacity:0;transform:translateX(20px) scale(.96);}to{opacity:1;transform:none;}}
@media(max-width:960px){.page-grid{grid-template-columns:1fr;}.right-col{position:static;}}
@media(max-width:860px){.sidebar{transform:translateX(-100%);}.sidebar.open{transform:translateX(0);}.main{margin-left:0;}.hamburger{display:flex;}.body{padding:14px 14px 60px;}}
@media(max-width:600px){.topbar{padding:0 14px;}.events-grid{grid-template-columns:1fr;}}
</style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

{{-- Reject Modal --}}
<div class="modal-overlay" id="rejectModal">
    <div class="modal">
        <div class="modal-title">Reject Campaign</div>
        <p class="modal-body">Please provide a reason for rejecting this campaign. This will be shown to the fundraiser.</p>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="rejection_reason" placeholder="Rejection reason (optional)…"
                style="width:100%;min-height:80px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-family:var(--font);font-size:13px;padding:10px 12px;outline:none;resize:vertical;margin-bottom:14px;transition:border-color var(--ease);"
                onfocus="this.style.borderColor='var(--a)'" onblur="this.style.borderColor='var(--border2)'"></textarea>
            <div class="modal-actions">
                <button type="button" class="action-btn btn-ghost" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="action-btn btn-red">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>

<div class="shell">

@php
    $kyc   = $campaign->user->kycVerification ?? null;
    $state = $campaign->campaign_state;

    $chipClass = match($state) {
        'active'    => 'chip-active',
        'paused'    => 'chip-paused',
        'pending'   => 'chip-pending',
        'rejected'  => 'chip-rejected',
        'expired'   => 'chip-expired',
        'completed' => 'chip-completed',
        default     => 'chip-pending',
    };
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
    $remaining  = max(0, $campaign->goal_amount - $raised);

    /* legacy single-doc KYC */
    $kycDocUrl = $kyc?->document_url ? route('admin.kyc.document', $kyc->id) : null;
    $kycExt    = $kyc?->document_url ? strtolower(pathinfo($kyc->document_url, PATHINFO_EXTENSION)) : null;
    $kycIsPdf  = $kycExt === 'pdf';
    $kycIsImg  = in_array($kycExt, ['jpg','jpeg','png','webp','gif']);

    /* new multi-doc KYC fields — adjust attribute names to match your model */
    $kycAadhaarUrl = $kyc?->aadhaar_url  ? asset('storage/'.$kyc->aadhaar_url)  : null;
    $kycPanUrl     = $kyc?->pan_url      ? asset('storage/'.$kyc->pan_url)      : null;
    $kycSelfieUrl  = $kyc?->selfie_url   ? asset('storage/'.$kyc->selfie_url)   : null;

    $isImg = fn($url) => $url && preg_match('/\.(jpe?g|png|webp|gif)$/i', $url);
    $isPdf = fn($url) => $url && str_ends_with(strtolower($url), '.pdf');

    /* bank details */
    $bankName   = $kyc?->kyc_bank_name    ?? null;
    $bankAcc    = $kyc?->kyc_account_number ?? null;
    $bankIfsc   = $kyc?->kyc_ifsc          ?? null;
    $bankHolder = $kyc?->kyc_account_name  ?? null;

    /* campaign updates */
    $updates = $campaign->updates ?? collect();
@endphp

{{-- SIDEBAR --}}
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

   <a href="{{ url('/admin/category-products') }}" class="s-link">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
      Products
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

{{-- MAIN --}}
<div class="main">
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="{{ url('/admin/dashboard') }}" class="topbar-back" title="Back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </a>
            <div class="topbar-title">
                <h1>{{ Str::limit($campaign->title, 38) }}</h1>
                <p>Campaign overview &amp; admin controls</p>
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

        <div class="page-grid">

            {{-- LEFT COLUMN --}}
            <div>

                {{-- Cover --}}
                <div class="card">
                    <div class="cover-wrap">
                        @if($campaign->cover_image)
                            <img src="{{ asset('storage/' . $campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
                            <div class="cover-overlay"></div>
                            <div class="cover-meta">
                                <div>
                                    <div class="cover-title">{{ Str::limit($campaign->title, 50) }}</div>
                                    <div class="cover-created">Created {{ $campaign->created_at->diffForHumans() }}</div>
                                </div>
                                <span class="badge" style="backdrop-filter:blur(8px);background:rgba(0,0,0,.35);border:1px solid rgba(255,255,255,.15);color:#fff;">
                                    <span class="badge-dot" style="background:#fff;"></span>
                                    {{ $chipLabel }}
                                </span>
                            </div>
                        @else
                            <div class="cover-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                <span>No cover image</span>
                            </div>
                            <div style="padding:14px 18px;border-bottom:1px solid var(--border);">
                                <div style="font-family:var(--mono);font-size:18px;font-weight:800;color:var(--text);letter-spacing:-.02em;margin-bottom:3px;">{{ $campaign->title }}</div>
                                <div style="font-size:11px;color:var(--text3);font-family:var(--mono);">Created {{ $campaign->created_at->diffForHumans() }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- About --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">About This Campaign</div>
                                <div class="card-sub">Campaign description</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="desc-text">{{ $campaign->description }}</p>
                    </div>
                </div>

                {{-- ── NEW: Campaign Updates & Documents ── --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-yellow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Updates &amp; Documents</div>
                                <div class="card-sub">{{ $updates->count() }} update{{ $updates->count() !== 1 ? 's' : '' }} submitted</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($updates->count() > 0)
                            <div class="updates-list">
                                @foreach($updates as $update)
                                <div class="update-item">
                                    <div class="update-item-header">
                                        <div class="update-item-title">{{ $update->title }}</div>
                                        <div class="update-item-date">{{ \Carbon\Carbon::parse($update->created_at)->format('d M Y') }}</div>
                                    </div>
                                    @if($update->body)
                                    <div class="update-item-body">{{ $update->body }}</div>
                                    @endif
                                    @if($update->document_url)
                                    <a href="{{ asset('storage/'.$update->document_url) }}" target="_blank" class="update-doc-pill">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        View attached document
                                    </a>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p>No updates or documents submitted for this campaign.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ── NEW: KYC Identity Documents ── --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-blue">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/></svg>
                            </div>
                            <div>
                                <div class="card-title">KYC Verification</div>
                                <div class="card-sub">Identity documents &amp; bank details</div>
                            </div>
                        </div>
                        @if($kyc)
                        <span class="badge b-{{ $kyc->status }}">
                            <span class="badge-dot"></span>
                            {{ ucfirst($kyc->status) }}
                        </span>
                        @endif
                    </div>
                    <div class="card-body">

                        @if(! $kyc)
                            <div class="kyc-notice kyc-notice-red">
                                <div class="kyc-notice-title">⚠ KYC Not Submitted</div>
                                <p class="kyc-notice-body">This user has not submitted any KYC documents. The campaign cannot be approved until KYC is verified.</p>
                            </div>

                        @else

                            {{-- Status banner --}}
                            @if($kyc->status === 'pending')
                                <div class="kyc-notice kyc-notice-yellow">
                                    <div class="kyc-notice-title"> KYC Under Review</div>
                                    <p class="kyc-notice-body">Documents submitted on {{ $kyc->created_at->format('d M Y') }}. Awaiting admin verification.</p>
                                </div>
                            @elseif($kyc->status === 'approved')
                                <div class="kyc-notice kyc-notice-green">
                                    <div class="kyc-notice-title">✓ KYC Approved</div>
                                    <p class="kyc-notice-body">Identity verified @if($kyc->verified_at)on {{ \Carbon\Carbon::parse($kyc->verified_at)->format('d M Y') }}@endif. Eligible for campaign approval.</p>
                                </div>
                            @elseif($kyc->status === 'rejected')
                                <div class="kyc-notice kyc-notice-red">
                                    <div class="kyc-notice-title">✗ KYC Rejected</div>
                                    <p class="kyc-notice-body">{{ $kyc->rejection_reason ?? 'Documents were rejected.' }}</p>
                                </div>
                            @endif

                            {{-- ── Aadhaar + PAN side-by-side ── --}}
                            <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--mono);margin-bottom:10px;">Identity Documents</div>
                            <div class="kyc-docs-grid">

                                {{-- Aadhaar --}}
                                <div class="kyc-doc-tile">
                                    <div class="kyc-doc-tile-header">
                                        <span class="kyc-doc-tile-label">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h6"/></svg>
                                            Aadhaar Card
                                        </span>
                                        @if($kycAadhaarUrl)
                                        <div style="display:flex;gap:5px;">
                                            <a href="{{ $kycAadhaarUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                Open
                                            </a>
                                            <a href="{{ $kycAadhaarUrl }}" download class="kyc-doc-btn kyc-doc-btn-dl">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                DL
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                    @if($kycAadhaarUrl)
                                        @if($isImg($kycAadhaarUrl))
                                            <a href="{{ $kycAadhaarUrl }}" target="_blank">
                                                <img src="{{ $kycAadhaarUrl }}" alt="Aadhaar" loading="lazy" class="kyc-doc-tile-img">
                                            </a>
                                        @elseif($isPdf($kycAadhaarUrl))
                                            <iframe src="{{ $kycAadhaarUrl }}" class="kyc-doc-tile-pdf" title="Aadhaar PDF"></iframe>
                                        @else
                                            <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg><span>Preview unavailable</span></div>
                                        @endif
                                    @else
                                        <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span>Not uploaded</span></div>
                                    @endif
                                </div>

                                {{-- PAN --}}
                                <div class="kyc-doc-tile">
                                    <div class="kyc-doc-tile-header">
                                        <span class="kyc-doc-tile-label">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h4"/></svg>
                                            PAN Card
                                        </span>
                                        @if($kycPanUrl)
                                        <div style="display:flex;gap:5px;">
                                            <a href="{{ $kycPanUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                Open
                                            </a>
                                            <a href="{{ $kycPanUrl }}" download class="kyc-doc-btn kyc-doc-btn-dl">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                DL
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                    @if($kycPanUrl)
                                        @if($isImg($kycPanUrl))
                                            <a href="{{ $kycPanUrl }}" target="_blank">
                                                <img src="{{ $kycPanUrl }}" alt="PAN" loading="lazy" class="kyc-doc-tile-img">
                                            </a>
                                        @elseif($isPdf($kycPanUrl))
                                            <iframe src="{{ $kycPanUrl }}" class="kyc-doc-tile-pdf" title="PAN PDF"></iframe>
                                        @else
                                            <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg><span>Preview unavailable</span></div>
                                        @endif
                                    @else
                                        <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span>Not uploaded</span></div>
                                    @endif
                                </div>

                            </div>{{-- /kyc-docs-grid --}}

                            {{-- ── Selfie with ID ── --}}
                            <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--mono);margin-bottom:10px;margin-top:6px;">Selfie Verification</div>
                            <div class="kyc-selfie-wrap">
                                @if($kycSelfieUrl)
                                    <a href="{{ $kycSelfieUrl }}" target="_blank">
                                        <img src="{{ $kycSelfieUrl }}" alt="Selfie with ID" loading="lazy" class="kyc-selfie-img">
                                    </a>
                                @else
                                    <div class="kyc-selfie-missing">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"/></svg>
                                        <span>Not uploaded</span>
                                    </div>
                                @endif
                                <div class="kyc-selfie-info">
                                    <div class="kyc-selfie-title">Selfie with ID Document</div>
                                    <div class="kyc-selfie-sub">Applicant must appear holding their Aadhaar or PAN card next to their face. Used to cross-verify identity against submitted documents.</div>
                                    @if($kycSelfieUrl)
                                    <div style="margin-top:10px;display:flex;gap:6px;">
                                        <a href="{{ $kycSelfieUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            View full size
                                        </a>
                                        <a href="{{ $kycSelfieUrl }}" download class="kyc-doc-btn kyc-doc-btn-dl">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Download
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- ── Bank Account Details ── --}}
                            <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--mono);margin-bottom:10px;margin-top:6px;padding-top:14px;border-top:1px solid var(--border);">Bank Account Details</div>
                            <div class="bank-grid">
                                <div class="bank-field">
                                    <div class="bank-field-lbl">Account Holder</div>
                                    @if($bankHolder)
                                        <div class="bank-field-val">{{ $bankHolder }}</div>
                                    @else
                                        <div class="bank-field-val empty">Not provided</div>
                                    @endif
                                </div>
                                <div class="bank-field">
                                    <div class="bank-field-lbl">Bank Name</div>
                                    @if($bankName)
                                        <div class="bank-field-val">{{ $bankName }}</div>
                                    @else
                                        <div class="bank-field-val empty">Not provided</div>
                                    @endif
                                </div>
                                <div class="bank-field">
                                    <div class="bank-field-lbl">Account Number</div>
                                    @if($bankAcc)
                                        <div class="bank-field-val" style="letter-spacing:.08em;">
                                            <span id="accNum" style="filter:blur(4px);cursor:pointer;transition:filter .2s;" onclick="this.style.filter='none';document.getElementById('accReveal').style.display='none';">{{ $bankAcc }}</span>
                                            <span id="accReveal" style="font-size:10px;color:var(--a);cursor:pointer;font-family:var(--font);font-weight:500;" onclick="document.getElementById('accNum').style.filter='none';this.style.display='none';">click to reveal</span>
                                        </div>
                                    @else
                                        <div class="bank-field-val empty">Not provided</div>
                                    @endif
                                </div>
                                <div class="bank-field">
                                    <div class="bank-field-lbl">IFSC Code</div>
                                    @if($bankIfsc)
                                        <div class="bank-field-val" style="letter-spacing:.1em;">{{ strtoupper($bankIfsc) }}</div>
                                    @else
                                        <div class="bank-field-val empty">Not provided</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Legacy single doc fallback --}}
                            @if($kycDocUrl && !$kycAadhaarUrl && !$kycPanUrl)
                            <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border);">
                                <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--mono);margin-bottom:10px;">Legacy Document</div>
                                <div class="kyc-doc-row">
                                    <div class="kyc-doc-icon">📄</div>
                                    <div>
                                        <div class="kyc-doc-type">{{ ucfirst(str_replace('_', ' ', $kyc->document_type ?? 'Document')) }}</div>
                                        <div class="kyc-doc-num">{{ $kyc->document_number ?? '' }}</div>
                                    </div>
                                </div>
                                <div class="kyc-doc-preview">
                                    <div class="kyc-doc-preview-header">
                                        <div class="kyc-doc-preview-header-left">
                                            @if($kycIsPdf)
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--red);"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                PDF Document
                                            @else
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--blue);"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                                Image Document
                                            @endif
                                        </div>
                                        <div class="kyc-doc-preview-actions">
                                            <a href="{{ $kycDocUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                View
                                            </a>
                                            <a href="{{ $kycDocUrl }}?download=1" download class="kyc-doc-btn kyc-doc-btn-dl">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                    @if($kycIsImg)
                                        <a href="{{ $kycDocUrl }}" target="_blank">
                                            <img src="{{ $kycDocUrl }}" alt="KYC document" loading="lazy" class="kyc-doc-preview-img">
                                        </a>
                                    @elseif($kycIsPdf)
                                        <iframe src="{{ $kycDocUrl }}" class="kyc-doc-preview-iframe" title="KYC PDF Document"></iframe>
                                    @else
                                        <div class="kyc-doc-preview-fallback">Preview not available. Use View or Download above.</div>
                                    @endif
                                </div>
                            </div>
                            @endif

                        @endif
                    </div>
                </div>

                {{-- Events --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-yellow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Campaign Events</div>
                                <div class="card-sub">{{ $campaign->events->count() }} event{{ $campaign->events->count() !== 1 ? 's' : '' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($campaign->events->count() > 0)
                        <div class="events-grid">
                            @foreach($campaign->events as $event)
                            @php
                                $evCls = match($event->status) {
                                    'approved' => 'ev-approved',
                                    'pending'  => 'ev-pending',
                                    default    => 'ev-default',
                                };
                            @endphp
                            <div class="event-card">
                                <span class="event-badge {{ $evCls }}">{{ ucfirst($event->status) }}</span>
                                <div class="event-title">{{ $event->title }}</div>
                                <div class="event-date">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</div>
                                <div class="event-desc">{{ Str::limit($event->description, 100) }}</div>
                                <a href="{{ route('admin.events.show', $event->id) }}" class="event-link">
                                    View details
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:10px;height:10px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p>No events have been created for this campaign.</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>{{-- /left --}}

            {{-- RIGHT COLUMN --}}
            <div class="right-col">

                {{-- Status + Actions --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Status &amp; Actions</div>
                            </div>
                        </div>
                    </div>
                    <div class="status-section">

                        <div class="status-section-label">CAMPAIGN STATE</div>
                        <div class="status-chips">
                            <span class="status-chip-lg {{ $chipClass }}">
                                <span class="chip-dot"></span>
                                {{ $chipLabel }}
                            </span>
                        </div>

                        <div class="kyc-pill kyc-pill-{{ $kyc?->status ?? 'none' }}">
                            @if(! $kyc) <span>⚠ KYC Not Submitted</span>
                            @elseif($kyc->status === 'pending') <span> KYC Pending Review</span>
                            @elseif($kyc->status === 'approved') <span>✓ KYC Approved</span>
                            @elseif($kyc->status === 'rejected') <span>✗ KYC Rejected</span>
                            @endif
                        </div>

                        @if($kyc && $kyc->status === 'pending')
                            <form action="{{ route('admin.kyc.approve', $kyc->id) }}" method="POST" style="margin-bottom:8px;">
                                @csrf
                                <button type="submit" class="action-btn btn-accent">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Approve KYC
                                </button>
                            </form>
                        @endif

                        @if($state === 'pending')
                            @if($kyc && $kyc->status === 'approved')
                                <form action="{{ route('admin.campaign.approve', $campaign->id) }}" method="POST" style="margin-bottom:8px;">
                                    @csrf
                                    <button type="submit" class="action-btn btn-green">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Approve Campaign
                                    </button>
                                </form>
                            @else
                                <button type="button" class="action-btn btn-disabled" disabled title="KYC must be approved first">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Approve Campaign
                                </button>
                                <p style="font-size:10.5px;color:var(--amber);margin-top:5px;margin-bottom:8px;font-family:var(--mono);">⚠ KYC must be approved before approving campaign</p>
                            @endif
                            <button type="button" class="action-btn btn-red" onclick="openRejectModal({{ $campaign->id }})">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Reject Campaign
                            </button>

                        @elseif($state === 'active')
                            <button type="button" class="action-btn btn-red" onclick="openRejectModal({{ $campaign->id }})">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Reject Campaign
                            </button>
                            <form action="{{ route('admin.campaign.pause', $campaign->id) }}" method="POST" style="margin-top:8px;">
                                @csrf
                                <button type="submit" class="action-btn btn-yellow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>
                                    Pause Campaign
                                </button>
                            </form>

                        @elseif($state === 'paused')
                            <form action="{{ route('admin.campaign.resume', $campaign->id) }}" method="POST" style="margin-bottom:8px;">
                                @csrf
                                <button type="submit" class="action-btn btn-green">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    Resume Campaign
                                </button>
                            </form>
                            <button type="button" class="action-btn btn-red" onclick="openRejectModal({{ $campaign->id }})">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Reject Campaign
                            </button>

                        @elseif($state === 'rejected')
                            <form action="{{ route('admin.campaign.approve', $campaign->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="action-btn btn-green">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Re-approve Campaign
                                </button>
                            </form>

                        @elseif($state === 'expired' || $state === 'completed')
                            <div style="padding:10px 12px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;color:var(--text3);text-align:center;">
                                This campaign is {{ $chipLabel }} and no further actions are available.
                            </div>
                        @endif

                        <a href="{{ url('/admin/dashboard') }}" class="action-btn btn-ghost" style="margin-top:12px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                            Back to Dashboard
                        </a>

                    </div>
                </div>

                {{-- Fundraising --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Fundraising</div>
                                <div class="card-sub">Current progress</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="prog-numbers">
                            <div class="prog-raised">₹{{ number_format($raised) }}</div>
                            <div class="prog-goal">of ₹{{ number_format($campaign->goal_amount) }}</div>
                        </div>
                        <div class="prog-bar">
                            <div class="prog-fill" style="width:{{ $percentage }}%"></div>
                        </div>
                        <div class="prog-pct">{{ $percentage }}% funded</div>
                        <div class="mini-stats">
                            <div class="mini-stat">
                                <div class="mini-stat-val">{{ $percentage }}%</div>
                                <div class="mini-stat-lbl">Completed</div>
                            </div>
                            <div class="mini-stat">
                                <div class="mini-stat-val" style="font-size:14px;">₹{{ number_format($remaining) }}</div>
                                <div class="mini-stat-lbl">Remaining</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Campaign Info --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-pink">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Campaign Info</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding-top:10px;padding-bottom:10px;">
                        <div class="info-row">
                            <span class="info-row-lbl">STATE</span>
                            <span class="badge b-{{ $state }}"><span class="badge-dot"></span>{{ $chipLabel }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-lbl">KYC</span>
                            <span style="font-size:11px;font-weight:700;color:{{ $kyc?->status === 'approved' ? 'var(--green)' : ($kyc?->status === 'pending' ? 'var(--amber)' : 'var(--red)') }};">
                                @if(!$kyc) ⚠ Not Submitted
                                @elseif($kyc->status === 'pending') ✓ Pending
                                @elseif($kyc->status === 'approved') ✓ Verified
                                @else ✗ Rejected
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-lbl">UPDATES</span>
                            <span style="font-weight:700;color:var(--text);font-family:var(--mono);">{{ $updates->count() }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-lbl">FUNDRAISER</span>
                            <span style="font-size:11.5px;font-weight:600;color:var(--text);">{{ $campaign->user->name ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-lbl">CATEGORY</span>
                            <span style="font-size:11.5px;font-weight:600;color:var(--text);">{{ $campaign->category->name ?? '—' }}</span>
                        </div>
                        @if($campaign->end_date)
                        <div class="info-row">
                            <span class="info-row-lbl">END DATE</span>
                            <span style="font-size:11px;font-weight:600;color:{{ now()->gt($campaign->end_date) ? 'var(--red)' : 'var(--text2)' }};">
                                {{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y') }}
                                @if(now()->gt($campaign->end_date))<span style="font-size:9px;"> (expired)</span>@endif
                            </span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-row-lbl">EVENTS</span>
                            <span style="font-weight:700;color:var(--text);font-family:var(--mono);">{{ $campaign->events->count() }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-lbl">CREATED</span>
                            <span style="font-weight:600;color:var(--text2);font-size:11px;">{{ $campaign->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

            </div>{{-- /right-col --}}
        </div>{{-- /page-grid --}}
    </div>{{-- /body --}}
</div>{{-- /main --}}
</div>{{-- /shell --}}

<script>
var html = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved = localStorage.getItem('adminTheme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
    var t = this.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', t);
    localStorage.setItem('adminTheme', t);
});

var sidebar = document.getElementById('sidebar');
document.getElementById('hamburger').addEventListener('click', function(){
    sidebar.classList.toggle('open');
});
document.addEventListener('click', function(e){
    if (window.innerWidth <= 860 && !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target))
        sidebar.classList.remove('open');
});

function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/campaigns/' + id + '/reject';
    document.getElementById('rejectModal').classList.add('show');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('show');
}
document.getElementById('rejectModal').addEventListener('click', function(e){
    if (e.target === this) closeRejectModal();
});
document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') closeRejectModal();
});

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
</script>
</body>
</html>
