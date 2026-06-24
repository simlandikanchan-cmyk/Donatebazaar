<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Blogs — DonateBazaar Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#f4f5fb;--surface:#fff;--surface2:#f8f9fe;--surface3:#eef0fa;
  --border:rgba(0,0,0,.06);--border2:rgba(0,0,0,.10);
  --text:#0a0b14;--text2:#454863;--text3:#9096b4;
  --sb-bg:#ffffff;--sb-txt:#5a5f7a;--sb-act:rgba(110,86,247,.10);--sb-border:rgba(0,0,0,.08);
  --a:#6e56f7;--a2:#9b6dff;--a-lt:rgba(110,86,247,.10);--a-glow:rgba(110,86,247,.22);
  --green:#05c48a;--green-lt:rgba(5,196,138,.10);
  --amber:#f59e0b;--amber-lt:rgba(245,158,11,.10);
  --red:#f04444;--red-lt:rgba(240,68,68,.10);
  --blue:#3b82f6;--blue-lt:rgba(59,130,246,.10);
  --font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;
  --r:18px;--r-sm:12px;--r-xs:8px;
  --sh:0 1px 3px rgba(0,0,0,.05),0 4px 24px rgba(0,0,0,.04);
  --sh-md:0 4px 20px rgba(0,0,0,.08),0 1px 4px rgba(0,0,0,.04);
  --sh-lg:0 12px 48px rgba(0,0,0,.14);
  --ease:.18s ease;--sb-w:268px;
}
[data-theme="dark"]{
  --bg:#070810;--surface:#0f1020;--surface2:#161728;--surface3:#1d1f35;
  --border:rgba(255,255,255,.055);--border2:rgba(255,255,255,.09);
  --text:#eef0ff;--text2:#9ba3c8;--text3:#4c5272;
  --sb-bg:#050609;--sb-txt:rgba(255,255,255,.48);--sb-act:rgba(110,86,247,.22);--sb-border:rgba(255,255,255,.03);
  --a-glow:rgba(110,86,247,.30);
  --sh:0 1px 3px rgba(0,0,0,.35),0 4px 24px rgba(0,0,0,.25);
  --sh-md:0 4px 20px rgba(0,0,0,.4),0 1px 4px rgba(0,0,0,.25);
  --sh-lg:0 12px 48px rgba(0,0,0,.6);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;}
body{font-family:var(--font);background:var(--bg);color:var(--text);line-height:1.55;-webkit-font-smoothing:antialiased;overflow-x:hidden;transition:background .2s,color .2s;}
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
.s-av img{width:100%;height:100%;object-fit:cover;}
.s-admin-name{font-size:12.5px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.s-admin-role{font-size:10px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.s-online{width:7px;height:7px;border-radius:50%;background:var(--green);margin-left:auto;flex-shrink:0;box-shadow:0 0 0 2.5px rgba(5,196,138,.2);}
.s-section{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.18em;padding:20px 22px 6px;font-family:var(--mono);}
.s-nav{padding:2px 10px;}
.s-link{display:flex;align-items:center;gap:11px;padding:9px 12px;border-radius:var(--r-xs);color:var(--sb-txt);font-size:13px;font-weight:500;transition:background var(--ease),color var(--ease);margin-bottom:1px;border:none;background:transparent;width:100%;text-align:left;cursor:pointer;position:relative;}
.s-link:hover{background:var(--a-lt);color:var(--a);}
.s-link.active{background:var(--sb-act);color:var(--a);font-weight:600;}
.s-link.active::before{content:'';position:absolute;left:0;top:22%;bottom:22%;width:3px;border-radius:0 3px 3px 0;background:var(--a);}
.s-ico{width:15px;height:15px;flex-shrink:0;opacity:.65;}
.s-link:hover .s-ico,.s-link.active .s-ico{opacity:1;}
.s-chip{margin-left:auto;font-size:10px;font-weight:700;padding:2px 7px;border-radius:100px;font-family:var(--mono);}
.sc-purple{background:var(--a-lt);color:var(--a);}
.sc-blue{background:var(--blue-lt);color:var(--blue);}
.sc-green{background:var(--green-lt);color:#059669;}
.sc-amber{background:var(--amber-lt);color:#b45309;}
.sc-teal{background:var(--green-lt);color:#059669;}
[data-theme="dark"] .sc-amber{color:var(--amber);}
[data-theme="dark"] .sc-teal{color:var(--green);}
.s-divider{height:1px;background:var(--sb-border);margin:10px 18px;}
.s-bottom{margin-top:auto;padding:10px 10px 20px;border-top:1px solid var(--sb-border);}
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:62px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:200;gap:14px;}
.tb-left h1{font-family:var(--mono);font-size:17px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.tb-left p{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.tb-right{display:flex;align-items:center;gap:8px;}
.tb-btn{width:36px;height:36px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text2);flex-shrink:0;transition:all var(--ease);position:relative;}
.tb-btn:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);}
.tb-btn svg{width:15px;height:15px;}
.notif-dot{width:6px;height:6px;border-radius:50%;background:var(--red);position:absolute;top:7px;right:7px;border:1.5px solid var(--surface);}
.theme-wrap{position:relative;}
.theme-wrap input{position:absolute;opacity:0;width:0;height:0;}
.theme-wrap label{display:flex;align-items:center;justify-content:space-between;width:52px;height:28px;border-radius:100px;background:var(--surface2);border:1px solid var(--border2);cursor:pointer;padding:4px;position:relative;}
.theme-wrap label::after{content:'';width:18px;height:18px;border-radius:50%;background:var(--a);position:absolute;left:5px;transition:transform .3s cubic-bezier(.4,0,.2,1);box-shadow:0 2px 6px rgba(110,86,247,.4);}
.theme-wrap input:checked+label::after{transform:translateX(23px);}
.ti{display:flex;justify-content:space-between;width:100%;position:relative;z-index:1;padding:0 2px;}
.ti svg{width:11px;height:11px;color:var(--text3);}
.t-av{width:36px;height:36px;border-radius:var(--r-sm);background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;font-family:var(--mono);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 10px rgba(110,86,247,.38);}
.hamburger{display:none;width:36px;height:36px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);cursor:pointer;color:var(--text2);align-items:center;justify-content:center;flex-shrink:0;}
.hamburger svg{width:15px;height:15px;}
.btn-create{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 16px;background:var(--a);color:#fff;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;border:none;transition:opacity var(--ease),box-shadow var(--ease);font-family:var(--font);}
.btn-create:hover{opacity:.88;box-shadow:0 4px 14px rgba(110,86,247,.35);}
.btn-create svg{width:13px;height:13px;}
.search-wrap{position:relative;width:230px;}
.search-wrap svg{position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;}
.search-input{width:100%;height:36px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:0 12px 0 32px;font-size:12px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.search-input::placeholder{color:var(--text3);}
.search-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.body{padding:26px 28px 56px;flex:1;}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;animation:fadeUp .4s ease both;}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;box-shadow:var(--sh);display:flex;align-items:center;gap:14px;transition:transform var(--ease),box-shadow var(--ease);}
.stat-card:hover{transform:translateY(-2px);box-shadow:var(--sh-md);}
.stat-card:nth-child(1){animation-delay:.05s;}.stat-card:nth-child(2){animation-delay:.10s;}.stat-card:nth-child(3){animation-delay:.15s;}.stat-card:nth-child(4){animation-delay:.20s;}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:18px;height:18px;}
.si-blue{background:var(--blue-lt);color:var(--blue);}
.si-amber{background:var(--amber-lt);color:var(--amber);}
.si-green{background:var(--green-lt);color:var(--green);}
.si-red{background:var(--red-lt);color:var(--red);}
.stat-num{font-family:var(--mono);font-size:1.9rem;font-weight:800;line-height:1;letter-spacing:-.03em;}
.sv-blue{color:var(--blue);}
.sv-amber{color:var(--amber);}
.sv-green{color:var(--green);}
.sv-red{color:var(--red);}
.stat-name{font-size:10px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:.07em;margin-top:3px;}
.flash-success{background:var(--green-lt);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:11px 14px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
[data-theme="dark"] .flash-success{color:var(--green);}
.sec-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px;animation:fadeUp .4s .2s ease both;}
.sec-title{font-family:var(--mono);font-size:15px;font-weight:800;color:var(--text);letter-spacing:-.02em;}
.sec-right{display:flex;align-items:center;gap:8px;}
.ftabs{display:flex;gap:2px;background:var(--surface2);border:1px solid var(--border);padding:3px;border-radius:var(--r-sm);}
.ftab{padding:5px 13px;border-radius:var(--r-xs);font-size:11.5px;font-weight:500;cursor:pointer;border:none;background:transparent;color:var(--text3);transition:background var(--ease),color var(--ease),box-shadow var(--ease);display:inline-flex;align-items:center;gap:5px;font-family:var(--font);white-space:nowrap;}
.ftab:hover{color:var(--a);}
.ftab.on{background:var(--surface);color:var(--a);font-weight:600;box-shadow:0 1px 6px rgba(110,86,247,.14);}
.fcnt{display:inline-flex;align-items:center;justify-content:center;min-width:16px;height:16px;border-radius:100px;font-size:9.5px;padding:0 3px;background:var(--a-lt);color:var(--a);font-weight:700;font-family:var(--mono);}
.sort-select{height:34px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-xs);padding:0 28px 0 10px;font-size:11.5px;color:var(--text2);font-family:var(--font);outline:none;cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;transition:border-color var(--ease);}
.sort-select:focus{border-color:var(--a);}
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .25s ease both;}
.table-scroll{overflow-x:auto;}
.table-scroll::-webkit-scrollbar{height:5px;}
.table-scroll::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
table{width:100%;min-width:860px;border-collapse:collapse;}
thead tr{border-bottom:2px solid var(--border);}
thead th{padding:11px 14px;text-align:left;font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);background:var(--surface2);white-space:nowrap;}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
tbody tr.row-hidden{display:none;}
tbody td{padding:13px 14px;font-size:13px;color:var(--text2);vertical-align:middle;}
.title-cell{display:flex;align-items:center;gap:10px;}
.blog-thumb{width:44px;height:34px;border-radius:7px;overflow:hidden;flex-shrink:0;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;}
.blog-thumb img{width:100%;height:100%;object-fit:cover;}
.blog-thumb svg{width:14px;height:14px;color:var(--border2);}
.title-primary{font-size:13px;font-weight:600;color:var(--text);line-height:1.35;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:260px;}
.title-id{font-size:10px;color:var(--text3);font-family:var(--mono);margin-top:1px;}
.author-cell{display:flex;align-items:center;gap:7px;}
.author-av{width:26px;height:26px;border-radius:7px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);}
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3px 8px;border-radius:7px;text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);white-space:nowrap;}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
/* STATUS BADGES — aligned to actual DB values: pending, published, rejected, draft, archived, flagged */
.b-pending{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.25);}
.b-published{background:var(--green-lt);color:#065f46;border:1px solid rgba(5,196,138,.25);}
.b-rejected{background:var(--red-lt);color:#991b1b;border:1px solid rgba(240,68,68,.25);}
.b-draft{background:var(--surface3);color:var(--text3);border:1px solid var(--border2);}
.b-archived{background:var(--blue-lt);color:#1e40af;border:1px solid rgba(59,130,246,.25);}
.b-flagged{background:#fdf2f8;color:#9d174d;border:1px solid rgba(236,72,153,.25);}
[data-theme="dark"] .b-pending{color:var(--amber);}
[data-theme="dark"] .b-published{color:var(--green);}
[data-theme="dark"] .b-rejected{color:var(--red);}
[data-theme="dark"] .b-draft{color:var(--text2);}
[data-theme="dark"] .b-archived{color:var(--blue);}
[data-theme="dark"] .b-flagged{color:#f9a8d4;}
.cat-tag{display:inline-block;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:500;background:var(--a-lt);color:var(--a);border:1px solid rgba(110,86,247,.18);}
td.date-cell{font-family:var(--mono);font-size:11.5px;color:var(--text3);}
.date-ago{font-size:10.5px;margin-top:2px;}
.actions{display:flex;align-items:center;gap:5px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:11px;font-weight:600;cursor:pointer;border:1px solid transparent;transition:opacity var(--ease),transform var(--ease);white-space:nowrap;font-family:var(--font);text-decoration:none;}
.act-btn:hover{opacity:.82;transform:scale(.97);}
.act-btn svg{width:11px;height:11px;}
.ab-view{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.2);}
.ab-edit{background:var(--amber-lt);color:var(--amber);border-color:rgba(245,158,11,.2);}
.ab-delete{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.2);padding:5px 8px;}
.empty-row td{padding:56px 20px;text-align:center;}
.empty-wrap{display:flex;flex-direction:column;align-items:center;gap:8px;color:var(--text3);}
.empty-wrap svg{width:40px;height:40px;opacity:.25;}
.empty-wrap p{font-size:13px;}
.table-footer{display:flex;align-items:center;justify-content:space-between;padding:11px 16px;border-top:1px solid var(--border);background:var(--surface2);}
.tfoot-count{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.tfoot-count strong{color:var(--text);font-weight:600;}
.pagination-wrap{margin-top:14px;}
.toast-container{position:fixed;top:18px;right:18px;z-index:9999;display:flex;flex-direction:column;gap:9px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:9px;padding:12px 14px;border-radius:12px;font-size:12.5px;font-weight:500;color:#fff;min-width:260px;max-width:360px;box-shadow:var(--sh-lg);pointer-events:all;animation:fadeUp .3s ease both;position:relative;overflow:hidden;}
.toast::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:rgba(255,255,255,.3);transform-origin:left;animation:toastProg 4s linear forwards;}
.toast-success{background:linear-gradient(135deg,#059669,#05c48a);}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-x{margin-left:auto;width:17px;height:17px;border-radius:4px;background:rgba(255,255,255,.2);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
@keyframes toastProg{from{transform:scaleX(1);}to{transform:scaleX(0);}}
@media(max-width:860px){
  .sidebar{transform:translateX(-100%);}.sidebar.open{transform:translateX(0);}
  .main{margin-left:0;}.hamburger{display:flex;}
}
@media(max-width:600px){
  .topbar{padding:0 16px;}.body{padding:14px 14px 48px;}
  .stats-grid{grid-template-columns:repeat(2,1fr);gap:8px;}
  .search-wrap{display:none;}
}
</style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<div class="shell">

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
        <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
      @else
        {{ strtoupper(substr(auth()->user()->name??'A',0,1)) }}
      @endif
    </div>
    <div style="flex:1;overflow:hidden;">
      <div class="s-admin-name">{{ auth()->user()->name??'Admin' }}</div>
      <div class="s-admin-role">Administrator</div>
    </div>
    <div class="s-online"></div>
  </div>

  <div class="s-section">Overview</div>
  <nav class="s-nav">
    <a href="{{ route('admin.dashboard') }}" class="s-link">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>
  </nav>

  <div class="s-section">Campaigns</div>
  <nav class="s-nav">
    <a href="{{ route('admin.dashboard') }}" class="s-link">
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
      @php $cntPend = isset($partnerships) ? $partnerships->where('status','pending')->count() : 0; @endphp
      @if($cntPend > 0)<span class="s-chip sc-amber">{{ $cntPend }}</span>@endif
    </a>
    <a href="{{ url('/admin/messages') }}" class="s-link">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      Messages
    </a>
    <a href="{{ url('/admin/blogs') }}" class="s-link active">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
      Blogs
    </a>
    <a href="{{ url('/admin/applications') }}" class="s-link">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Applications
    </a>
  </nav>

  <div class="s-section">Job Board</div>
  <nav class="s-nav">
    <a href="{{ route('admin.job_posts.index') }}" class="s-link">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      All Job Posts
      <span class="s-chip sc-teal">{{ \App\Models\JobPost::count() }}</span>
    </a>
    <a href="{{ route('admin.job_posts.create') }}" class="s-link">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Post a Job
    </a>
    <a href="{{ route('admin.job_post_applications.index') }}" class="s-link">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
      Job Applicants
      <span class="s-chip sc-amber">{{ \App\Models\JobPostApplication::count() }}</span>
    </a>
  </nav>

  <div class="s-divider"></div>

  <div class="s-bottom">
    <a href="{{ route('profile.show') }}" class="s-link" style="color:var(--a);margin-bottom:2px;">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      My Profile
    </a>
    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('lf').submit();" class="s-link" style="color:var(--red);">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
      Sign Out
    </a>
    <form id="lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
  </div>
</aside>


<div class="main">

  <header class="topbar">
    <div style="display:flex;align-items:center;gap:10px;">
      <button class="hamburger" id="hamburger" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <div class="tb-left">
        <h1>Blog Posts</h1>
        <p>Manage and review</p>
      </div>
    </div>
    <div class="tb-right">
      <div class="search-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input class="search-input" id="searchInput" type="text" placeholder="Search blogs…" autocomplete="off">
      </div>
      <button class="tb-btn" title="Notifications">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        <span class="notif-dot"></span>
      </button>
      <div class="theme-wrap" title="Toggle dark mode">
        <input type="checkbox" id="themeToggle">
        <label for="themeToggle">
          <div class="ti">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
          </div>
        </label>
      </div>
      <a href="{{ route('admin.blogs.create') }}" class="btn-create">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Post
      </a>
      <div class="t-av">{{ strtoupper(substr(auth()->user()->name??'A',0,1)) }}</div>
    </div>
  </header>

  <div class="body">

    @php
     
      $cntPending   = $pendingCount   ?? 0;
      $cntPublished = $publishedCount ?? 0;
      $cntRejected  = $rejectedCount  ?? 0;
      $cntTotal     = $cntPending + $cntPublished + $cntRejected;
      $activeStatus = request('status', 'all');
      $activeSort   = request('sort',   'latest');
    @endphp

    {{-- ── STATS — 4 cards: Total / Pending / Published / Rejected ── --}}
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon si-blue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        </div>
        <div>
          <div class="stat-num sv-blue">{{ $cntTotal }}</div>
          <div class="stat-name">Total</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon si-amber">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
          <div class="stat-num sv-amber">{{ $cntPending }}</div>
          <div class="stat-name">Pending</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon si-green">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <div class="stat-num sv-green">{{ $cntPublished }}</div>
          <div class="stat-name">Published</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon si-red">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <div class="stat-num sv-red">{{ $cntRejected }}</div>
          <div class="stat-name">Rejected</div>
        </div>
      </div>
    </div>

    @if(session('success'))
    <div class="flash-success">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
    @endif

    {{-- ── SECTION HEADER ── --}}
    <div class="sec-header">
      <div class="sec-title">All Blog Posts</div>
      <div class="sec-right">
        <select class="sort-select" id="sortSelect">
          <option value="latest" {{ $activeSort === 'latest' ? 'selected' : '' }}>Latest first</option>
          <option value="oldest" {{ $activeSort === 'oldest' ? 'selected' : '' }}>Oldest first</option>
          <option value="title"  {{ $activeSort === 'title'  ? 'selected' : '' }}>Title A–Z</option>
        </select>
        {{-- Tabs use the real status values stored in the DB --}}
        <div class="ftabs" id="ftabs">
          <button class="ftab {{ $activeStatus === 'all'       ? 'on' : '' }}" data-status="all">All <span class="fcnt">{{ $cntTotal }}</span></button>
          <button class="ftab {{ $activeStatus === 'pending'   ? 'on' : '' }}" data-status="pending">Pending <span class="fcnt">{{ $cntPending }}</span></button>
          <button class="ftab {{ $activeStatus === 'published' ? 'on' : '' }}" data-status="published">Published <span class="fcnt">{{ $cntPublished }}</span></button>
          <button class="ftab {{ $activeStatus === 'rejected'  ? 'on' : '' }}" data-status="rejected">Rejected <span class="fcnt">{{ $cntRejected }}</span></button>
        </div>
      </div>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="table-card">
      <div class="table-scroll">
        <table>
          <thead>
            <tr>
              <th>Post</th>
              <th>Author</th>
              <th>Category</th>
              <th>Status</th>
              <th>Published</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="tbody">
            @forelse($blogs as $blog)
            @php
              $status = $blog->status ?? 'draft';
              $catName = null;
              if (!empty($blog->category)) {
                if (is_string($blog->category)) {
                  $dec = json_decode($blog->category);
                  $catName = (json_last_error() === JSON_ERROR_NONE && isset($dec->name)) ? $dec->name : $blog->category;
                } elseif (is_object($blog->category)) {
                  $catName = $blog->category->name ?? null;
                } elseif (is_array($blog->category)) {
                  $catName = $blog->category['name'] ?? null;
                }
              }
              $srch = strtolower(($blog->title ?? '') . ' ' . ($blog->author->name ?? '') . ' ' . ($catName ?? '') . ' ' . $status);
            @endphp
            <tr data-status="{{ $status }}" data-search="{{ $srch }}">
              <td>
                <div class="title-cell">
                  <div class="blog-thumb">
                    @if(!empty($blog->cover_image))
                      <img src="{{ $blog->cover_image_url ?? asset('storage/'.$blog->cover_image) }}" alt="{{ $blog->title }}">
                    @else
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    @endif
                  </div>
                  <div>
                    <div class="title-primary" title="{{ $blog->title }}">{{ $blog->title }}</div>
                    <div class="title-id">#{{ $blog->id }}</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="author-cell">
                  <div class="author-av">{{ strtoupper(substr($blog->author->name ?? 'U', 0, 2)) }}</div>
                  <span style="font-size:12.5px;font-weight:500;color:var(--text);">{{ $blog->author->name ?? 'Unknown' }}</span>
                </div>
              </td>
              <td>
                @if($catName)
                  <span class="cat-tag">{{ $catName }}</span>
                @else
                  <span style="color:var(--text3);font-size:12px;">—</span>
                @endif
              </td>
              <td>
                {{-- badge class matches actual DB status value --}}
                <span class="badge b-{{ $status }}">
                  <span class="badge-dot"></span>{{ ucfirst($status) }}
                </span>
              </td>
              <td class="date-cell">
                {{ $blog->created_at->format('d M Y') }}
                <div class="date-ago">{{ $blog->created_at->diffForHumans() }}</div>
              </td>
              <td>
                <div class="actions">
                  <a href="{{ route('admin.blogs.show', $blog) }}" class="act-btn ab-view">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Review
                  </a>
                  <a href="{{ route('admin.blogs.edit', $blog) }}" class="act-btn ab-edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                  </a>
                  <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" style="display:inline;" onsubmit="return confirm('Delete \'{{ addslashes($blog->title) }}\'?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="act-btn ab-delete" title="Delete">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr class="empty-row">
              <td colspan="6">
                <div class="empty-wrap">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                  <p>No blog posts found.</p>
                </div>
              </td>
            </tr>
            @endforelse
            <tr id="noResultsRow" style="display:none;">
              <td colspan="6">
                <div class="empty-wrap">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                  <p>No results match your search.</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="table-footer">
        <div class="tfoot-count">Showing <strong id="cntVisF">{{ $blogs->total() }}</strong> of <strong>{{ $cntTotal }}</strong> results</div>
        <div style="font-size:11px;color:var(--text3);font-family:var(--mono);">Total {{ $cntTotal }} posts</div>
      </div>
    </div>

    @if($blogs->hasPages())
    <div class="pagination-wrap">{{ $blogs->appends(request()->query())->links() }}</div>
    @endif

  </div>
</div>
</div>

<script>
(function(){
'use strict';

/* ── THEME ── */
var html = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved = localStorage.getItem('adminTheme') || 'light';
if(saved === 'dark'){ html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
  var t = this.checked ? 'dark' : 'light';
  html.setAttribute('data-theme', t);
  localStorage.setItem('adminTheme', t);
});

/* ── SIDEBAR TOGGLE ── */
var sidebar = document.getElementById('sidebar');
document.getElementById('hamburger').addEventListener('click', function(){
  sidebar.classList.toggle('open');
});
document.addEventListener('click', function(e){
  if(window.innerWidth <= 860 && !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target)){
    sidebar.classList.remove('open');
  }
});

/* ── CLIENT-SIDE SEARCH (within the current server-filtered page) ── */
var rows = Array.from(document.querySelectorAll('#tbody tr[data-status]'));
var noRow = document.getElementById('noResultsRow');

function applySearch(){
  var q = document.getElementById('searchInput').value.toLowerCase().trim();
  var vis = 0;
  rows.forEach(function(r){
    var show = !q || (r.dataset.search || '').includes(q);
    r.classList.toggle('row-hidden', !show);
    if(show) vis++;
  });
  var e2 = document.getElementById('cntVisF');
  if(e2) e2.textContent = vis;
  if(noRow) noRow.style.display = (vis === 0 && rows.length > 0) ? '' : 'none';
}
applySearch();

var st;
document.getElementById('searchInput').addEventListener('input', function(){
  clearTimeout(st);
  st = setTimeout(applySearch, 180);
});

/* ── FILTER TABS → SERVER ── */
document.querySelectorAll('.ftab').forEach(function(tab){
  tab.addEventListener('click', function(){
    var url = new URL(window.location.href);
    url.searchParams.set('status', this.dataset.status);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
  });
});

/* ── SORT → SERVER (preserves active status filter) ── */
document.getElementById('sortSelect').addEventListener('change', function(){
  var url = new URL(window.location.href);
  url.searchParams.set('sort', this.value);
  url.searchParams.set('status', '{{ $activeStatus }}');
  url.searchParams.set('page', 1);
  window.location.href = url.toString();
});

/* ── TOAST ── */
@if(session('success'))
(function(){
  var c = document.getElementById('toastContainer');
  var el = document.createElement('div');
  el.className = 'toast toast-success';
  el.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>'+@json(session('success'))+'</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
  c.appendChild(el);
  setTimeout(function(){ el.style.animation='fadeUp .3s ease reverse forwards'; setTimeout(function(){ el.remove(); },300); }, 4000);
}());
@endif

})();
</script>
</body>
</html>