<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Message Details — DonateBazaar Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
/* ── DESIGN TOKENS ── */
:root{
  --bg:#f4f5fb;
  --surface:#fff;
  --surface2:#f8f9fe;
  --surface3:#eef0fa;
  --border:rgba(0,0,0,.06);
  --border2:rgba(0,0,0,.10);
  --text:#0a0b14;
  --text2:#454863;
  --text3:#9096b4;
  --sb-bg:#ffffff;
  --sb-txt:#5a5f7a;
  --sb-act:rgba(110,86,247,.10);
  --sb-border:rgba(0,0,0,.08);
  --a:#6e56f7;--a2:#9b6dff;
  --a-lt:rgba(110,86,247,.10);
  --a-glow:rgba(110,86,247,.22);
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
  --bg:#070810;
  --surface:#0f1020;
  --surface2:#161728;
  --surface3:#1d1f35;
  --border:rgba(255,255,255,.055);
  --border2:rgba(255,255,255,.09);
  --text:#eef0ff;
  --text2:#9ba3c8;
  --text3:#4c5272;
  --sb-bg:#050609;
  --sb-txt:rgba(255,255,255,.48);
  --sb-act:rgba(110,86,247,.22);
  --sb-border:rgba(255,255,255,.03);
  --a-glow:rgba(110,86,247,.30);
  --sh:0 1px 3px rgba(0,0,0,.35),0 4px 24px rgba(0,0,0,.25);
  --sh-md:0 4px 20px rgba(0,0,0,.4),0 1px 4px rgba(0,0,0,.25);
  --sh-lg:0 12px 48px rgba(0,0,0,.6);
}

/* ── RESET ── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;}
body{font-family:var(--font);background:var(--bg);color:var(--text);line-height:1.55;-webkit-font-smoothing:antialiased;overflow-x:hidden;transition:background .2s,color .2s;}
a{text-decoration:none;color:inherit;}
button{cursor:pointer;font-family:var(--font);}
svg{display:block;flex-shrink:0;}

/* ── LAYOUT ── */
.shell{display:flex;min-height:100vh;}

/* ── SIDEBAR (exact copy from messages.blade.php) ── */
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
.s-admin-pill{
  margin:14px 12px 4px;padding:10px 14px;
  background:linear-gradient(135deg,rgba(110,86,247,.08),rgba(155,109,255,.05));
  border:1px solid rgba(110,86,247,.15);border-radius:var(--r-sm);
  display:flex;align-items:center;gap:10px;
}
.s-av{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
.s-av img{width:100%;height:100%;object-fit:cover;}
.s-admin-name{font-size:12.5px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.s-admin-role{font-size:10px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.s-online{width:7px;height:7px;border-radius:50%;background:var(--green);margin-left:auto;flex-shrink:0;box-shadow:0 0 0 2.5px rgba(5,196,138,.2);}
.s-section{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.18em;padding:20px 22px 6px;font-family:var(--mono);}
.s-nav{padding:2px 10px;}
.s-link{
  display:flex;align-items:center;gap:11px;padding:9px 12px;border-radius:var(--r-xs);
  color:var(--sb-txt);font-size:13px;font-weight:500;
  transition:background var(--ease),color var(--ease);
  margin-bottom:1px;border:none;background:transparent;
  width:100%;text-align:left;cursor:pointer;position:relative;
}
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

/* ── MAIN ── */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* ── TOPBAR ── */
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

/* ── PAGE BODY ── */
.body{padding:26px 28px 56px;flex:1;}

/* ── BREADCRUMB ── */
.breadcrumb{display:flex;align-items:center;gap:6px;margin-bottom:20px;animation:fadeUp .35s ease both;}
.breadcrumb a{font-size:11.5px;color:var(--text3);font-family:var(--mono);transition:color var(--ease);}
.breadcrumb a:hover{color:var(--a);}
.breadcrumb .bc-sep{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.breadcrumb .bc-cur{font-size:11.5px;color:var(--text2);font-weight:600;font-family:var(--mono);}

/* ── PAGE HEADER ── */
.page-hdr{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:22px;flex-wrap:wrap;animation:fadeUp .4s .05s ease both;}
.page-hdr-left h2{font-family:var(--mono);font-size:20px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1.2;}
.page-hdr-left p{font-size:12px;color:var(--text3);margin-top:4px;}
.back-btn{display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border-radius:var(--r-sm);background:var(--surface);border:1px solid var(--border2);color:var(--text2);font-size:12.5px;font-weight:600;transition:all var(--ease);font-family:var(--font);text-decoration:none;}
.back-btn:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);transform:translateX(-2px);}
.back-btn svg{width:13px;height:13px;}

/* ── DETAIL GRID ── */
.detail-grid{display:grid;grid-template-columns:1fr 300px;gap:18px;align-items:start;}

/* ── DETAIL CARD ── */
.detail-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .10s ease both;}

/* Card header */
.dc-head{padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;background:var(--surface2);}
.dc-head-left{display:flex;align-items:center;gap:13px;}
.sender-av{width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:18px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);box-shadow:0 4px 14px rgba(110,86,247,.3);}
.sender-name{font-size:15px;font-weight:700;color:var(--text);line-height:1.3;}
.sender-email{font-size:11px;color:var(--text3);font-family:var(--mono);margin-top:2px;}

/* Card body */
.dc-body{padding:24px 22px;}

/* Info grid */
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:22px;}
.info-box{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:14px 16px;}
.info-lbl{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.10em;font-family:var(--mono);margin-bottom:6px;}
.info-val{font-size:13px;font-weight:600;color:var(--text);line-height:1.4;}
.info-val a{color:var(--a);}
.info-val a:hover{text-decoration:underline;}

/* Message body */
.msg-lbl{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.10em;font-family:var(--mono);margin-bottom:10px;display:flex;align-items:center;gap:8px;}
.msg-lbl::after{content:'';flex:1;height:1px;background:var(--border);}
.msg-box{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:18px 20px;line-height:1.8;color:var(--text2);font-size:13.5px;white-space:pre-line;}

/* Card footer */
.dc-foot{display:flex;align-items:center;justify-content:space-between;padding:14px 22px;border-top:1px solid var(--border);background:var(--surface2);gap:10px;flex-wrap:wrap;}
.act-btn{display:inline-flex;align-items:center;gap:5px;padding:9px 16px;border-radius:var(--r-xs);font-size:12px;font-weight:600;cursor:pointer;border:1px solid transparent;transition:all var(--ease);white-space:nowrap;font-family:var(--font);text-decoration:none;}
.act-btn:hover{transform:translateY(-1px);}
.act-btn:active{transform:scale(.96);}
.act-btn svg{width:13px;height:13px;}
.ab-reply{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.2);}
.ab-reply:hover{background:var(--a);color:#fff;box-shadow:0 4px 14px rgba(110,86,247,.35);}
.ab-delete{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.2);}
.ab-delete:hover{background:var(--red);color:#fff;box-shadow:0 4px 14px rgba(240,68,68,.3);}

/* ── BADGE ── */
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:4px 9px;border-radius:7px;text-transform:uppercase;letter-spacing:.07em;font-family:var(--mono);}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
.b-new{background:rgba(59,130,246,.12);color:#1d4ed8;border:1px solid rgba(59,130,246,.2);}
.b-read{background:rgba(5,196,138,.12);color:#065f46;border:1px solid rgba(5,196,138,.2);}
[data-theme="dark"] .b-new{color:#93c5fd;}
[data-theme="dark"] .b-read{color:#34d399;}

/* ── SIDE PANEL ── */
.side-panel{display:flex;flex-direction:column;gap:16px;}
.side-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;}
.side-card:nth-child(1){animation-delay:.15s;}
.side-card:nth-child(2){animation-delay:.22s;}

.sc-head{padding:12px 18px;border-bottom:1px solid var(--border);background:var(--surface2);font-family:var(--mono);font-size:10.5px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;}
.sc-body{padding:4px 0;}

/* Info rows */
.sc-row{display:flex;flex-direction:column;gap:3px;padding:11px 18px;border-bottom:1px solid var(--border);}
.sc-row:last-child{border-bottom:none;}
.sc-key{font-size:10px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;}
.sc-val{font-size:13px;font-weight:600;color:var(--text);word-break:break-all;line-height:1.4;}
.sc-val.muted{color:var(--text2);font-weight:400;}

/* Quick action buttons */
.qa-btn{display:flex;align-items:center;gap:10px;width:100%;padding:11px 18px;border:none;background:transparent;color:var(--text2);font-size:13px;font-weight:500;text-align:left;transition:background var(--ease),color var(--ease);cursor:pointer;text-decoration:none;border-bottom:1px solid var(--border);}
.qa-btn:last-child{border-bottom:none;}
.qa-btn:hover{background:var(--surface2);color:var(--text);}
.qa-btn.danger:hover{background:var(--red-lt);color:var(--red);}
.qa-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.qa-icon svg{width:13px;height:13px;}
.qi-purple{background:var(--a-lt);color:var(--a);}
.qi-gray{background:var(--surface3);color:var(--text3);}
.qi-red{background:var(--red-lt);color:var(--red);}

/* ── SCROLLBAR ── */
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}

/* ── ANIMATIONS ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}

/* ── RESPONSIVE ── */
@media(max-width:960px){
  .detail-grid{grid-template-columns:1fr;}
  .side-panel{flex-direction:row;flex-wrap:wrap;}
  .side-card{flex:1;min-width:240px;}
}
@media(max-width:860px){
  .sidebar{transform:translateX(-100%);}.sidebar.open{transform:translateX(0);}
  .main{margin-left:0;}.hamburger{display:flex;}
}
@media(max-width:600px){
  .topbar{padding:0 16px;}.body{padding:14px 14px 48px;}
  .info-grid{grid-template-columns:1fr;}
  .dc-foot{flex-direction:column;align-items:stretch;}
  .act-btn{justify-content:center;}
}
</style>
</head>
<body>

<div class="shell">

{{-- ── SIDEBAR (identical to messages.blade.php) ── --}}
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
    <a href="{{ url('/admin/messages') }}" class="s-link active">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      Messages
    </a>
    <a href="{{ url('/admin/blogs') }}" class="s-link">
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


{{-- ── MAIN ── --}}
<div class="main">

  <header class="topbar">
    <div style="display:flex;align-items:center;gap:10px;">
      <button class="hamburger" id="hamburger" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <div class="tb-left">
        <h1>Message Details</h1>
        <p>View full customer inquiry</p>
      </div>
    </div>
    <div class="tb-right">
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
      <div class="t-av">{{ strtoupper(substr(auth()->user()->name??'A',0,1)) }}</div>
    </div>
  </header>

  <div class="body">

    {{-- ── BREADCRUMB ── --}}
    <div class="breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <span class="bc-sep">/</span>
      <a href="{{ route('admin.messages') }}">Messages</a>
      <span class="bc-sep">/</span>
      <span class="bc-cur">{{ $message->name }}</span>
    </div>

    {{-- ── PAGE HEADER ── --}}
    <div class="page-hdr">
      <div class="page-hdr-left">
        <h2>Message from {{ $message->name }}</h2>
        <p>Received {{ $message->created_at->diffForHumans() }} &middot; {{ $message->created_at->format('d M Y, h:i A') }}</p>
      </div>
      <a href="{{ route('admin.messages') }}" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Back to Messages
      </a>
    </div>

    {{-- ── DETAIL GRID ── --}}
    @php $isRead = isset($message->read_at) && $message->read_at; @endphp
    <div class="detail-grid">

      {{-- ── LEFT: MAIN CARD ── --}}
      <div class="detail-card">

        {{-- Header --}}
        <div class="dc-head">
          <div class="dc-head-left">
            <div class="sender-av">{{ strtoupper(substr($message->name??'U',0,1)) }}</div>
            <div>
              <div class="sender-name">{{ $message->name }}</div>
              <div class="sender-email">{{ $message->email }}</div>
            </div>
          </div>
          <span class="badge b-{{ $isRead ? 'read' : 'new' }}">
            <span class="badge-dot"></span>{{ $isRead ? 'Read' : 'New' }}
          </span>
        </div>

        {{-- Body --}}
        <div class="dc-body">
          <div class="info-grid">
            <div class="info-box">
              <div class="info-lbl">Sender Name</div>
              <div class="info-val">{{ $message->name }}</div>
            </div>
            <div class="info-box">
              <div class="info-lbl">Email Address</div>
              <div class="info-val">
                <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
              </div>
            </div>
            <div class="info-box">
              <div class="info-lbl">Subject</div>
              <div class="info-val">{{ $message->subject ?? '—' }}</div>
            </div>
            <div class="info-box">
              <div class="info-lbl">Received On</div>
              <div class="info-val">{{ $message->created_at->format('d M Y · h:i A') }}</div>
            </div>
          </div>

          <div class="msg-lbl">Full Message</div>
          <div class="msg-box">{{ $message->message }}</div>
        </div>

        {{-- Footer actions --}}
        <div class="dc-foot">
          <a href="mailto:{{ $message->email }}" class="act-btn ab-reply">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            Reply via Email
          </a>
          <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="act-btn ab-delete" onclick="return confirm('Delete this message permanently?')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
              Delete Message
            </button>
          </form>
        </div>
      </div>

      {{-- ── RIGHT: SIDE PANEL ── --}}
      <div class="side-panel">

        {{-- Message Info --}}
        <div class="side-card">
          <div class="sc-head">Message Info</div>
          <div class="sc-body">
            <div class="sc-row">
              <div class="sc-key">Message ID</div>
              <div class="sc-val">#{{ $message->id }}</div>
            </div>
            <div class="sc-row">
              <div class="sc-key">Status</div>
              <div class="sc-val">
                <span class="badge b-{{ $isRead ? 'read' : 'new' }}">
                  <span class="badge-dot"></span>{{ $isRead ? 'Read' : 'Unread' }}
                </span>
              </div>
            </div>
            <div class="sc-row">
              <div class="sc-key">Received</div>
              <div class="sc-val muted">{{ $message->created_at->format('d M Y') }}</div>
            </div>
            <div class="sc-row">
              <div class="sc-key">Time</div>
              <div class="sc-val muted">{{ $message->created_at->format('h:i A') }}</div>
            </div>
            <div class="sc-row">
              <div class="sc-key">Relative</div>
              <div class="sc-val muted">{{ $message->created_at->diffForHumans() }}</div>
            </div>
            @if($isRead)
            <div class="sc-row">
              <div class="sc-key">Read At</div>
              <div class="sc-val muted">{{ \Carbon\Carbon::parse($message->read_at)->format('d M Y, h:i A') }}</div>
            </div>
            @endif
          </div>
        </div>

        {{-- Quick Actions --}}
        <div class="side-card">
          <div class="sc-head">Quick Actions</div>
          <div class="sc-body">
            <a href="mailto:{{ $message->email }}" class="qa-btn">
              <span class="qa-icon qi-purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              </span>
              Reply via Email
            </a>
            <a href="{{ route('admin.messages') }}" class="qa-btn">
              <span class="qa-icon qi-gray">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
              </span>
              All Messages
            </a>
            <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST">
              @csrf @method('DELETE')
              <button type="submit" class="qa-btn danger" style="width:100%;" onclick="return confirm('Delete this message permanently?')">
                <span class="qa-icon qi-red">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                </span>
                Delete Message
              </button>
            </form>
          </div>
        </div>

      </div>{{-- /side-panel --}}
    </div>{{-- /detail-grid --}}

  </div>{{-- /body --}}
</div>{{-- /main --}}
</div>{{-- /shell --}}

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

})();
</script>
</body>
</html>