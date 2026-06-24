<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Add Product — DonateBazaar Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
.shell{display:flex;min-height:100vh;}

/* SIDEBAR */
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
.s-link{display:flex;align-items:center;gap:11px;padding:9px 12px;border-radius:var(--r-xs);color:var(--sb-txt);font-size:13px;font-weight:500;text-decoration:none;transition:background var(--ease),color var(--ease);margin-bottom:1px;border:none;background:transparent;width:100%;text-align:left;cursor:pointer;position:relative;font-family:var(--font);}
.s-link:hover{background:var(--a-lt);color:var(--a);}
.s-link.active{background:var(--sb-act);color:var(--a);font-weight:600;}
.s-link.active::before{content:'';position:absolute;left:0;top:22%;bottom:22%;width:3px;border-radius:0 3px 3px 0;background:var(--a);}
.s-ico{width:15px;height:15px;flex-shrink:0;opacity:.65;}
.s-link:hover .s-ico,.s-link.active .s-ico{opacity:1;}
.s-chip{margin-left:auto;font-size:10px;font-weight:700;padding:2px 7px;border-radius:100px;font-family:var(--mono);}
.sc-purple{background:var(--a-lt);color:var(--a);}
.sc-green{background:var(--green-lt);color:#059669;}
.sc-amber{background:var(--amber-lt);color:#b45309;}
.sc-teal{background:var(--green-lt);color:#059669;}
.s-divider{height:1px;background:var(--sb-border);margin:10px 18px;}
.s-bottom{margin-top:auto;padding:10px 10px 20px;border-top:1px solid var(--sb-border);}

/* MAIN */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* TOPBAR */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:62px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:200;gap:14px;}
.tb-left h1{font-family:var(--mono);font-size:17px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.tb-left p{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.tb-right{display:flex;align-items:center;gap:8px;}
.tb-btn{width:36px;height:36px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text2);flex-shrink:0;transition:all var(--ease);}
.tb-btn:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);}
.tb-btn svg{width:15px;height:15px;}
.back-btn{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 16px;background:var(--surface2);color:var(--text2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;font-weight:600;cursor:pointer;transition:all var(--ease);font-family:var(--font);}
.back-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.back-btn svg{width:13px;height:13px;}
.theme-wrap{position:relative;}
.theme-wrap input{position:absolute;opacity:0;width:0;height:0;}
.theme-wrap label{display:flex;align-items:center;justify-content:space-between;width:52px;height:28px;border-radius:100px;background:var(--surface2);border:1px solid var(--border2);cursor:pointer;padding:4px;position:relative;}
.theme-wrap label::after{content:'';width:18px;height:18px;border-radius:50%;background:var(--a);position:absolute;left:5px;transition:transform .3s cubic-bezier(.4,0,.2,1);box-shadow:0 2px 6px rgba(110,86,247,.4);}
.theme-wrap input:checked+label::after{transform:translateX(23px);}
.ti{display:flex;justify-content:space-between;width:100%;position:relative;z-index:1;padding:0 2px;}
.ti svg{width:11px;height:11px;color:var(--text3);}
.t-av{width:36px;height:36px;border-radius:var(--r-sm);background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;box-shadow:0 2px 10px rgba(110,86,247,.38);}
.hamburger{display:none;width:36px;height:36px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);cursor:pointer;color:var(--text2);align-items:center;justify-content:center;flex-shrink:0;}
.hamburger svg{width:15px;height:15px;}

/* BODY */
.body{padding:26px 28px 56px;flex:1;}

/* BREADCRUMB */
.breadcrumb{display:flex;align-items:center;gap:7px;font-size:11.5px;color:var(--text3);font-family:var(--mono);margin-bottom:22px;animation:fadeUp .3s ease both;}
.breadcrumb a{color:var(--text3);transition:color var(--ease);}
.breadcrumb a:hover{color:var(--a);}
.breadcrumb svg{width:10px;height:10px;flex-shrink:0;}
.breadcrumb span{color:var(--text2);}

/* ALERT */
.alert-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.22);color:#b91c1c;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;animation:fadeUp .3s ease;}
.alert-error svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}
[data-theme="dark"] .alert-error{color:#f87171;}

/* LAYOUT */
.page-grid{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;}
@media(max-width:960px){.page-grid{grid-template-columns:1fr;}}

/* CARD */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;margin-bottom:16px;animation:fadeUp .4s ease both;}
.card:nth-child(1){animation-delay:.05s;}.card:nth-child(2){animation-delay:.10s;}.card:nth-child(3){animation-delay:.15s;}.card:nth-child(4){animation-delay:.20s;}
.card-head{display:flex;align-items:center;gap:10px;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:11.5px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-body{padding:22px;}

/* FIELDS */
.field{margin-bottom:20px;}
.field:last-child{margin-bottom:0;}
.field-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;}
.field-row:last-child{margin-bottom:0;}
.f-label{display:block;font-size:11.5px;font-weight:600;color:var(--text2);margin-bottom:7px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.f-label .req{color:var(--red);margin-left:2px;}
.f-input,.f-select,.f-textarea{width:100%;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color .2s,box-shadow .2s,background .2s;}
.f-input::placeholder,.f-textarea::placeholder{color:var(--text3);}
.f-input:focus,.f-select:focus,.f-textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
.f-input.err,.f-select.err,.f-textarea.err{border-color:var(--red);}
.f-select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:34px;cursor:pointer;}
.f-textarea{resize:vertical;min-height:90px;line-height:1.6;}
.f-hint{font-size:11px;color:var(--text3);margin-top:5px;line-height:1.5;}
.f-error{font-size:11.5px;color:var(--red);margin-top:5px;font-family:var(--mono);}

/* TOGGLE */
.toggle-row{display:flex;align-items:center;justify-content:space-between;padding:4px 0;}
.toggle-lbl{font-size:13px;font-weight:600;color:var(--text);}
.toggle-sub{font-size:11.5px;color:var(--text3);margin-top:2px;}
.sw{position:relative;flex-shrink:0;}
.sw input{position:absolute;opacity:0;width:0;height:0;}
.sw label{display:block;width:46px;height:26px;border-radius:100px;background:var(--border2);cursor:pointer;position:relative;transition:background .2s;}
.sw label::after{content:'';position:absolute;width:20px;height:20px;border-radius:50%;background:#fff;top:3px;left:3px;transition:transform .25s cubic-bezier(.4,0,.2,1);box-shadow:0 1px 4px rgba(0,0,0,.2);}
.sw input:checked+label{background:var(--a);}
.sw input:checked+label::after{transform:translateX(20px);}

/* IMAGE UPLOAD */
.upload-zone{border:2px dashed var(--border2);border-radius:var(--r-sm);padding:28px 20px;text-align:center;cursor:pointer;transition:all .2s;position:relative;background:var(--surface2);}
.upload-zone:hover,.upload-zone.drag{border-color:var(--a);background:var(--a-lt);}
.upload-zone input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}
.upload-ico{width:44px;height:44px;border-radius:12px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
.upload-ico svg{width:20px;height:20px;}
.upload-title{font-size:13px;font-weight:600;color:var(--text);margin-bottom:4px;}
.upload-sub{font-size:11.5px;color:var(--text3);}
.img-preview-wrap{display:none;flex-direction:column;align-items:center;gap:10px;}
.img-preview{width:100px;height:100px;border-radius:var(--r-sm);object-fit:cover;border:1px solid var(--border2);box-shadow:var(--sh);}
.img-remove{font-size:11.5px;color:var(--red);cursor:pointer;background:none;border:none;font-family:var(--font);font-weight:500;padding:4px 8px;border-radius:6px;transition:background var(--ease);}
.img-remove:hover{background:var(--red-lt);}

/* SUBMIT */
.submit-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:13px 20px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:14px;font-weight:700;cursor:pointer;font-family:var(--mono);letter-spacing:-.01em;transition:opacity .2s,transform .15s;box-shadow:0 4px 18px rgba(110,86,247,.35);animation:fadeUp .4s .25s ease both;}
.submit-btn:hover{opacity:.88;transform:translateY(-1px);}
.submit-btn:active{transform:scale(.98);}
.submit-btn:disabled{opacity:.6;cursor:not-allowed;transform:none;}
.submit-btn svg{width:15px;height:15px;}

/* PREVIEW CARD */
.preview-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;position:sticky;top:82px;animation:fadeUp .4s .15s ease both;}
.preview-live{padding:28px 20px;display:flex;flex-direction:column;align-items:center;text-align:center;background:var(--surface2);border-bottom:1px solid var(--border);min-height:180px;gap:10px;}
.prev-img-box{width:80px;height:80px;border-radius:16px;background:var(--a-lt);display:flex;align-items:center;justify-content:center;color:var(--a);font-size:28px;box-shadow:var(--sh-md);overflow:hidden;flex-shrink:0;}
.prev-img-box img{width:100%;height:100%;object-fit:cover;}
.prev-prod-name{font-family:var(--mono);font-size:14px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.prev-prod-name.empty{color:var(--text3);font-weight:400;font-style:italic;}
.prev-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:100px;font-size:10.5px;font-weight:700;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.pb-active{background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.22);}
.pb-inactive{background:rgba(100,116,139,.08);color:var(--text3);border:1px solid var(--border2);}
.preview-meta{padding:14px 20px;}
.prev-row{display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:12px;}
.prev-row:last-child{border-bottom:none;}
.prev-row-lbl{color:var(--text3);font-family:var(--mono);font-size:10px;text-transform:uppercase;letter-spacing:.07em;}
.prev-row-val{color:var(--text2);font-weight:600;font-family:var(--mono);font-size:11.5px;}

/* TOAST */
.toast-wrap{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:270px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s ease both;}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-ok{background:linear-gradient(135deg,#059669,#10b981);}

::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@keyframes toastIn{from{opacity:0;transform:translateX(18px) scale(.96)}to{opacity:1;transform:none}}
@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}}
@media(max-width:700px){.field-row{grid-template-columns:1fr;}.page-grid{grid-template-columns:1fr;}}
@media(max-width:600px){.topbar{padding:0 16px}.body{padding:14px 14px 48px}}
</style>
</head>
<body>

<div class="toast-wrap" id="toastWrap"></div>

<div class="shell">

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="s-logo">
    <div class="s-logo-mark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
    <div>
      <div class="s-logo-name">DonateBazaar</div>
      <div class="s-logo-tag">Admin Portal</div>
    </div>
  </div>
  <div class="s-admin-pill">
    <div class="s-av">
      @if(auth()->user()->avatar)<img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
      @else{{ strtoupper(substr(auth()->user()->name??'A',0,1)) }}@endif
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
    <a href="{{ url('/admin/category-products') }}" class="s-link active">
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

<!-- MAIN -->
<div class="main">
  <header class="topbar">
    <div style="display:flex;align-items:center;gap:10px;">
      <button class="hamburger" id="hamburger" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <div class="tb-left">
        <h1>Add Product</h1>
        <p>Create a new category fundraising product</p>
      </div>
    </div>
    <div class="tb-right">
      <a href="{{ route('admin.category-products.index') }}" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        All Products
      </a>
      <button class="tb-btn" title="Notifications">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
      </button>
      <div class="theme-wrap">
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
    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <a href="{{ route('admin.category-products.index') }}">Category Products</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <span>Add Product</span>
    </div>

    @if($errors->any())
    <div class="alert-error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <div>
        <strong>Please fix the following:</strong>
        <ul style="margin-top:4px;padding-left:16px;">
          @foreach($errors->all() as $e)<li style="font-size:12px;margin-top:2px;">{{ $e }}</li>@endforeach
        </ul>
      </div>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.category-products.store') }}" enctype="multipart/form-data" id="prodForm">
    @csrf

    <div class="page-grid">
      <!-- LEFT -->
      <div>

        <!-- Basic Info -->
        <div class="card">
          <div class="card-head">
            <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <span class="card-head-title">Basic Information</span>
          </div>
          <div class="card-body">
            <div class="field">
              <label class="f-label" for="name">Product Name <span class="req">*</span></label>
              <input id="name" name="name" type="text" value="{{ old('name') }}"
                class="f-input {{ $errors->has('name')?'err':'' }}"
                placeholder="e.g. Awareness T-Shirt, Donation Kit…"
                oninput="updatePreview()" required>
              @error('name')<p class="f-error">{{ $message }}</p>@enderror
            </div>
            <div class="field">
              <label class="f-label" for="description">Description</label>
              <textarea id="description" name="description" class="f-textarea {{ $errors->has('description')?'err':'' }}"
                placeholder="Brief description of this product…" rows="3">{{ old('description') }}</textarea>
              @error('description')<p class="f-error">{{ $message }}</p>@enderror
            </div>
            <div class="field">
              <div class="toggle-row">
                <div>
                  <div class="toggle-lbl">Active</div>
                  <div class="toggle-sub">Make this product visible on the public site</div>
                </div>
                <div class="sw">
                  <input type="checkbox" name="is_active" id="isActive" value="1" checked onchange="updatePreview()">
                  <label for="isActive"></label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Category & Type -->
        <div class="card">
          <div class="card-head">
            <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg></div>
            <span class="card-head-title">Category & Type</span>
          </div>
          <div class="card-body">
            <div class="field-row">
              <div>
                <label class="f-label" for="category_id">Category <span class="req">*</span></label>
                <select id="category_id" name="category_id" class="f-select {{ $errors->has('category_id')?'err':'' }}" onchange="updatePreview()" required>
                  <option value="">Select category…</option>
                  @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                  @endforeach
                </select>
                @error('category_id')<p class="f-error">{{ $message }}</p>@enderror
              </div>
              <div>
                <label class="f-label" for="product_type">Product Type <span class="req">*</span></label>
                <select id="product_type" name="product_type" class="f-select {{ $errors->has('product_type')?'err':'' }}" onchange="updatePreview()" required>
                  <option value="">Select type…</option>
                  <option value="physical" {{ old('product_type')=='physical'?'selected':'' }}>Physical</option>
                  <option value="digital" {{ old('product_type')=='digital'?'selected':'' }}>Digital</option>
                  <option value="service" {{ old('product_type')=='service'?'selected':'' }}>Service</option>
                  <option value="bundle" {{ old('product_type')=='bundle'?'selected':'' }}>Bundle</option>
                </select>
                @error('product_type')<p class="f-error">{{ $message }}</p>@enderror
              </div>
            </div>
          </div>
        </div>

        <!-- Pricing & Stock -->
        <div class="card">
          <div class="card-head">
            <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <span class="card-head-title">Pricing & Stock</span>
          </div>
          <div class="card-body">
            <div class="field-row">
              <div>
                <label class="f-label" for="price">Price (₹) <span class="req">*</span></label>
                <input id="price" name="price" type="number" step="0.01" min="0"
                  value="{{ old('price') }}"
                  class="f-input {{ $errors->has('price')?'err':'' }}"
                  placeholder="0.00" oninput="updatePreview()" required>
                @error('price')<p class="f-error">{{ $message }}</p>@enderror
              </div>
              <div>
                <label class="f-label" for="stock">Stock Quantity <span class="req">*</span></label>
                <input id="stock" name="stock" type="number" min="0"
                  value="{{ old('stock',0) }}"
                  class="f-input {{ $errors->has('stock')?'err':'' }}"
                  placeholder="0" oninput="updatePreview()" required>
                @error('stock')<p class="f-error">{{ $message }}</p>@enderror
                <p class="f-hint">Set to 0 for unlimited / digital products</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Image Upload -->
        <div class="card">
          <div class="card-head">
            <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
            <span class="card-head-title">Product Image</span>
          </div>
          <div class="card-body">
            <div class="upload-zone" id="uploadZone">
              <input type="file" name="image" id="imageInput" accept="image/*" onchange="handleImageChange(this)">
              <div id="uploadPrompt">
                <div class="upload-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg></div>
                <div class="upload-title">Drop image here or click to browse</div>
                <div class="upload-sub">PNG, JPG, WEBP — max 2MB</div>
              </div>
              <div class="img-preview-wrap" id="imgPreviewWrap">
                <img src="" alt="Preview" class="img-preview" id="imgPreview">
                <button type="button" class="img-remove" onclick="removeImage()">✕ Remove image</button>
              </div>
            </div>
            @error('image')<p class="f-error" style="margin-top:8px;">{{ $message }}</p>@enderror
          </div>
        </div>

        <button type="submit" class="submit-btn" id="submitBtn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Create Product
        </button>

      </div>

      <!-- RIGHT — Preview -->
      <div>
        <div class="preview-card">
          <div class="card-head">
            <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></div>
            <span class="card-head-title">Live Preview</span>
          </div>
          <div class="preview-live">
            <div class="prev-img-box" id="prevImgBox">
              <i class="fa fa-box" id="prevImgIcon"></i>
              <img src="" id="prevImgEl" style="display:none;width:100%;height:100%;object-fit:cover;">
            </div>
            <div class="prev-prod-name empty" id="prevName">Product name…</div>
            <span class="prev-badge pb-active" id="prevBadge"><span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Active</span>
          </div>
          <div class="preview-meta">
            <div class="prev-row">
              <span class="prev-row-lbl">Category</span>
              <span class="prev-row-val" id="prevCat">—</span>
            </div>
            <div class="prev-row">
              <span class="prev-row-lbl">Type</span>
              <span class="prev-row-val" id="prevType">—</span>
            </div>
            <div class="prev-row">
              <span class="prev-row-lbl">Price</span>
              <span class="prev-row-val" id="prevPrice">₹0.00</span>
            </div>
            <div class="prev-row">
              <span class="prev-row-lbl">Stock</span>
              <span class="prev-row-val" id="prevStock">0</span>
            </div>
            <div class="prev-row">
              <span class="prev-row-lbl">Status</span>
              <span class="prev-row-val" id="prevStatus" style="color:var(--green);">Active</span>
            </div>
          </div>
        </div>
      </div>

    </div>
    </form>
  </div>
</div>
</div>

<script>
(function(){
'use strict';
var html=document.documentElement,toggle=document.getElementById('themeToggle');
var saved=localStorage.getItem('adminTheme')||'light';
if(saved==='dark'){html.setAttribute('data-theme','dark');toggle.checked=true;}
toggle.addEventListener('change',function(){var t=this.checked?'dark':'light';html.setAttribute('data-theme',t);localStorage.setItem('adminTheme',t);});
var sb=document.getElementById('sidebar');
document.getElementById('hamburger').addEventListener('click',function(){sb.classList.toggle('open');});
document.addEventListener('click',function(e){if(window.innerWidth<=860&&!sb.contains(e.target)&&!document.getElementById('hamburger').contains(e.target))sb.classList.remove('open');});

window.updatePreview=function(){
  var name=document.getElementById('name').value.trim();
  var active=document.getElementById('isActive').checked;
  var catSel=document.getElementById('category_id');
  var catText=catSel.options[catSel.selectedIndex]?catSel.options[catSel.selectedIndex].text:'—';
  var typeSel=document.getElementById('product_type');
  var typeText=typeSel.value?typeSel.value.charAt(0).toUpperCase()+typeSel.value.slice(1):'—';
  var price=parseFloat(document.getElementById('price').value)||0;
  var stock=parseInt(document.getElementById('stock').value)||0;

  var nameEl=document.getElementById('prevName');
  nameEl.textContent=name||'Product name…';
  nameEl.classList.toggle('empty',!name);

  var badge=document.getElementById('prevBadge');
  var statusEl=document.getElementById('prevStatus');
  if(active){
    badge.className='prev-badge pb-active';
    badge.innerHTML='<span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Active';
    statusEl.textContent='Active';statusEl.style.color='var(--green)';
  } else {
    badge.className='prev-badge pb-inactive';
    badge.innerHTML='<span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Inactive';
    statusEl.textContent='Inactive';statusEl.style.color='var(--text3)';
  }
  document.getElementById('prevCat').textContent=catText==='Select category…'?'—':catText;
  document.getElementById('prevType').textContent=typeText;
  document.getElementById('prevPrice').textContent='₹'+price.toFixed(2);
  document.getElementById('prevStock').textContent=stock;
};

window.handleImageChange=function(input){
  if(!input.files||!input.files[0])return;
  var reader=new FileReader();
  reader.onload=function(e){
    document.getElementById('prevImgIcon').style.display='none';
    var el=document.getElementById('prevImgEl');
    el.src=e.target.result;el.style.display='block';
    document.getElementById('uploadPrompt').style.display='none';
    document.getElementById('imgPreviewWrap').style.display='flex';
    document.getElementById('imgPreview').src=e.target.result;
  };
  reader.readAsDataURL(input.files[0]);
};

window.removeImage=function(){
  document.getElementById('imageInput').value='';
  document.getElementById('prevImgIcon').style.display='';
  document.getElementById('prevImgEl').style.display='none';
  document.getElementById('uploadPrompt').style.display='';
  document.getElementById('imgPreviewWrap').style.display='none';
};

/* drag highlight */
var zone=document.getElementById('uploadZone');
zone.addEventListener('dragover',function(e){e.preventDefault();zone.classList.add('drag');});
zone.addEventListener('dragleave',function(){zone.classList.remove('drag');});
zone.addEventListener('drop',function(){zone.classList.remove('drag');});

document.getElementById('prodForm').addEventListener('submit',function(){
  var btn=document.getElementById('submitBtn');
  btn.disabled=true;
  btn.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Creating…';
});

updatePreview();
})();
</script>
</body>
</html>