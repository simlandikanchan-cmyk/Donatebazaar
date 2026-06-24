<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Partnership Detail — DonateBazaar Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ─── TOKENS (identical to Categories page) ─────────────────────────── */
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

/* ─── SIDEBAR (copy-exact from Categories) ──────────────────────────── */
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

/* ─── MAIN ───────────────────────────────────────────────────────────── */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* ─── TOPBAR ─────────────────────────────────────────────────────────── */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:62px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:200;gap:14px;}
.tb-left h1{font-family:var(--mono);font-size:17px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.tb-left p{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.tb-right{display:flex;align-items:center;gap:8px;}
.tb-btn{width:36px;height:36px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text2);flex-shrink:0;transition:all var(--ease);}
.tb-btn:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);}
.tb-btn svg{width:15px;height:15px;}
.theme-wrap{position:relative;}
.theme-wrap input{position:absolute;opacity:0;width:0;height:0;}
.theme-wrap label{display:flex;align-items:center;justify-content:space-between;width:52px;height:28px;border-radius:100px;background:var(--surface2);border:1px solid var(--border2);cursor:pointer;padding:4px;position:relative;}
.theme-wrap label::after{content:'';width:18px;height:18px;border-radius:50%;background:var(--a);position:absolute;left:5px;transition:transform .3s cubic-bezier(.4,0,.2,1);box-shadow:0 2px 6px rgba(110,86,247,.4);}
.theme-wrap input:checked + label::after{transform:translateX(23px);}
.ti{display:flex;justify-content:space-between;width:100%;position:relative;z-index:1;padding:0 2px;}
.ti svg{width:11px;height:11px;color:var(--text3);}
.t-av{width:36px;height:36px;border-radius:var(--r-sm);background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;overflow:hidden;box-shadow:0 2px 10px rgba(110,86,247,.38);}
.hamburger{display:none;width:36px;height:36px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);cursor:pointer;color:var(--text2);align-items:center;justify-content:center;flex-shrink:0;}
.hamburger svg{width:15px;height:15px;}

/* ─── BODY ───────────────────────────────────────────────────────────── */
.body{padding:26px 28px 56px;flex:1;}

/* ─── ALERT ──────────────────────────────────────────────────────────── */
.alert-ok{background:rgba(5,196,138,.08);border:1px solid rgba(5,196,138,.22);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease;}
[data-theme="dark"] .alert-ok{color:#6ee7b7;}
.alert-ok svg{width:15px;height:15px;flex-shrink:0;}

/* ─── BACK BTN ───────────────────────────────────────────────────────── */
.back-btn{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 14px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;font-weight:600;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all var(--ease);margin-bottom:20px;text-decoration:none;}
.back-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.back-btn svg{width:13px;height:13px;}

/* ─── PAGE HEADER ────────────────────────────────────────────────────── */
.page-hdr{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:22px;flex-wrap:wrap;animation:fadeUp .4s ease both;}
.page-hdr-left{display:flex;align-items:center;gap:14px;}
.page-av{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:20px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);box-shadow:0 4px 18px rgba(110,86,247,.35);}
.page-name{font-family:var(--mono);font-size:20px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1.2;}
.page-meta{font-size:11.5px;color:var(--text3);margin-top:3px;font-family:var(--mono);}
.page-hdr-right{display:flex;align-items:center;gap:8px;flex-shrink:0;flex-wrap:wrap;}

/* ─── STATUS / PRIORITY PILLS ────────────────────────────────────────── */
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;}
.s-pending{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.22);}
.s-approved{background:var(--green-lt);color:#059669;border:1px solid rgba(5,196,138,.22);}
.s-rejected{background:var(--red-lt);color:#b91c1c;border:1px solid rgba(240,68,68,.22);}
[data-theme="dark"] .s-pending{color:var(--amber);}
[data-theme="dark"] .s-approved{color:var(--green);}
[data-theme="dark"] .s-rejected{color:var(--red);}
.status-dot{width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;}
.pri-pill{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;}
.pri-high{background:var(--green-lt);color:#059669;border:1px solid rgba(5,196,138,.22);}
.pri-medium{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.22);}
.pri-low{background:var(--red-lt);color:#b91c1c;border:1px solid rgba(240,68,68,.22);}
[data-theme="dark"] .pri-high{color:var(--green);}
[data-theme="dark"] .pri-medium{color:var(--amber);}
[data-theme="dark"] .pri-low{color:var(--red);}
.score-badge{font-size:11px;font-weight:700;font-family:var(--mono);padding:5px 10px;border-radius:100px;background:var(--surface2);border:1px solid var(--border2);color:var(--text3);}

/* ─── MAIN CARD (same shell as Categories) ───────────────────────────── */
.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .1s ease both;}
.card-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.card-head-left{display:flex;align-items:center;gap:10px;}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}

/* ─── CARD SECTIONS ──────────────────────────────────────────────────── */
.card-section{padding:22px 24px;border-bottom:1px solid var(--border);}
.card-section:last-child{border-bottom:none;}
.section-label{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.18em;font-family:var(--mono);margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.section-label svg{width:12px;height:12px;opacity:.7;}

/* ─── INFO GRID ──────────────────────────────────────────────────────── */
.info-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:0;}
.info-item{padding:14px 18px;border-right:1px solid var(--border);border-bottom:1px solid var(--border);}
.info-item:nth-child(3n){border-right:none;}
.info-item:nth-last-child(-n+3){border-bottom:none;}
.info-lbl{font-size:9.5px;font-family:var(--mono);color:var(--text3);text-transform:uppercase;letter-spacing:.09em;margin-bottom:5px;}
.info-val{font-size:13px;font-weight:500;color:var(--text);line-height:1.4;}
.info-val a{color:var(--a);}
.info-val a:hover{text-decoration:underline;}
.info-val.mono{font-family:var(--mono);font-size:12.5px;}
.info-val.muted{color:var(--text3);font-style:italic;}
.info-val.accent{color:var(--a);font-weight:600;}

/* ─── PROPOSAL MSG BOX ───────────────────────────────────────────────── */
.msg-box{background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:18px 20px;font-size:13.5px;color:var(--text2);line-height:1.75;}

/* ─── DOCUMENT BTN ───────────────────────────────────────────────────── */
.doc-btn{display:inline-flex;align-items:center;gap:8px;height:38px;padding:0 16px;background:var(--a-lt);color:var(--a);border:1px solid rgba(110,86,247,.20);border-radius:var(--r-sm);font-size:12.5px;font-weight:600;font-family:var(--font);transition:all var(--ease);text-decoration:none;}
.doc-btn:hover{background:var(--a);color:#fff;transform:translateY(-1px);box-shadow:0 4px 14px rgba(110,86,247,.3);}
.doc-btn svg{width:13px;height:13px;}

/* ─── REVIEW META ROW ────────────────────────────────────────────────── */
.review-row{display:flex;align-items:center;gap:28px;flex-wrap:wrap;}
.review-item{display:flex;flex-direction:column;gap:4px;}
.review-lbl{font-size:9.5px;font-family:var(--mono);color:var(--text3);text-transform:uppercase;letter-spacing:.09em;}
.review-val{font-size:13px;font-weight:600;color:var(--text);}
.review-val.pending{color:var(--amber);}
.review-val.empty{color:var(--text3);font-weight:400;font-style:italic;}

/* ─── ADMIN FORM ─────────────────────────────────────────────────────── */
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;}
.form-group{display:flex;flex-direction:column;gap:6px;}
.form-lbl{font-size:10px;font-family:var(--mono);color:var(--text3);text-transform:uppercase;letter-spacing:.09em;}
.form-select,.form-textarea{width:100%;border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-size:13px;font-family:var(--font);color:var(--text);background:var(--surface2);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.form-select:focus,.form-textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.form-textarea{resize:vertical;line-height:1.6;}
.submit-btn{display:inline-flex;align-items:center;gap:7px;height:40px;padding:0 20px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:13px;font-weight:600;cursor:pointer;font-family:var(--font);transition:opacity var(--ease),transform var(--ease);box-shadow:0 4px 14px rgba(110,86,247,.3);}
.submit-btn:hover{opacity:.88;transform:translateY(-1px);}
.submit-btn svg{width:13px;height:13px;}

::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}

@keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}}
@media(max-width:640px){.info-grid{grid-template-columns:1fr 1fr}.info-item:nth-child(3n){border-right:1px solid var(--border)}.info-item:nth-child(2n){border-right:none}.info-item:nth-last-child(-n+3){border-bottom:1px solid var(--border)}.info-item:nth-last-child(-n+2){border-bottom:none}.form-row{grid-template-columns:1fr}.page-hdr{flex-direction:column;gap:12px}.body{padding:14px 14px 48px}}
</style>
</head>
<body>

<div class="shell">

{{-- ═══════════════════════════════ SIDEBAR ═══════════════════════════════ --}}
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

    
    <a href="{{ url('/admin/partnerships') }}" class="s-link active">
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

{{-- ═══════════════════════════════ MAIN ══════════════════════════════════ --}}
<div class="main">
  <header class="topbar">
    <div style="display:flex;align-items:center;gap:10px;">
      <button class="hamburger" id="hamburger" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <div class="tb-left">
        <h1>Partnership Detail</h1>
        <p>Review complete partnership request details</p>
      </div>
    </div>
    <div class="tb-right">
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

    @if(session('success'))
    <div class="alert-ok" id="flashAlert">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
    @endif

    {{-- BACK --}}
    <a href="{{ route('admin.partnership.index') }}" class="back-btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Partnerships
    </a>

    @php
      $init   = strtoupper(substr($partnership->name ?? 'A', 0, 1));
      $score  = $partnership->priority_score ?? 0;
      $priCls = $score >= 30 ? 'pri-high'   : ($score >= 10 ? 'pri-medium' : 'pri-low');
      $priLbl = $score >= 30 ? 'High'        : ($score >= 10 ? 'Medium'    : 'Low');
    @endphp

    {{-- PAGE HEADER --}}
    <div class="page-hdr">
      <div class="page-hdr-left">
        <div class="page-av">{{ $init }}</div>
        <div>
          <div class="page-name">{{ $partnership->name }}</div>
          <div class="page-meta">
            #{{ $partnership->id }}
            &nbsp;·&nbsp;
            {{ $partnership->organization_name ?? 'No Organisation' }}
            &nbsp;·&nbsp;
            Submitted {{ \Carbon\Carbon::parse($partnership->created_at)->format('d M Y') }}
          </div>
        </div>
      </div>
      <div class="page-hdr-right">
        <span class="pri-pill {{ $priCls }}"><span class="status-dot"></span>{{ $priLbl }} Priority</span>
        <span class="score-badge">Score: {{ $score }}</span>
        @if($partnership->status === 'pending')
          <span class="status-pill s-pending"><span class="status-dot"></span> Pending</span>
        @elseif($partnership->status === 'approved')
          <span class="status-pill s-approved"><span class="status-dot"></span> Approved</span>
        @else
          <span class="status-pill s-rejected"><span class="status-dot"></span> Rejected</span>
        @endif
      </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="main-card">

      {{-- ── Contact Information ─────────────────────────────────────────── --}}
      <div class="card-section">
        <div class="section-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          Contact Information
        </div>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-lbl">Full Name</div>
            <div class="info-val">{{ $partnership->name }}</div>
          </div>
          <div class="info-item">
            <div class="info-lbl">Email Address</div>
            <div class="info-val"><a href="mailto:{{ $partnership->email }}">{{ $partnership->email }}</a></div>
          </div>
          <div class="info-item">
            <div class="info-lbl">Phone</div>
            <div class="info-val {{ $partnership->phone ? '' : 'muted' }}">{{ $partnership->phone ?? 'Not provided' }}</div>
          </div>
        </div>
      </div>

      {{-- ── Organisation Details ────────────────────────────────────────── --}}
      <div class="card-section">
        <div class="section-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          Organisation Details
        </div>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-lbl">Organisation Name</div>
            <div class="info-val" style="font-weight:600;">{{ $partnership->organization_name ?? '—' }}</div>
          </div>
          <div class="info-item">
            <div class="info-lbl">Website</div>
            <div class="info-val">
              @if($partnership->website)
                <a href="{{ $partnership->website }}" target="_blank" rel="noopener">{{ $partnership->website }}</a>
              @else
                <span class="muted">Not provided</span>
              @endif
            </div>
          </div>
          <div class="info-item">
            <div class="info-lbl">Organisation Type</div>
            <div class="info-val">{{ $partnership->organization_type ?? '—' }}</div>
          </div>
          <div class="info-item">
            <div class="info-lbl">Team Size</div>
            <div class="info-val mono">{{ $partnership->organization_size ?? '—' }}</div>
          </div>
          <div class="info-item">
            <div class="info-lbl">Location</div>
            <div class="info-val">{{ $partnership->location ?? '—' }}</div>
          </div>
        </div>
      </div>

      {{-- ── Partnership Details ──────────────────────────────────────────── --}}
      <div class="card-section">
        <div class="section-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
          Partnership Details
        </div>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-lbl">Partnership Type</div>
            <div class="info-val accent">{{ ucfirst($partnership->partnership_type ?? '—') }}</div>
          </div>
          <div class="info-item">
            <div class="info-lbl">Goal</div>
            <div class="info-val">{{ ucfirst($partnership->goal ?? '—') }}</div>
          </div>
          <div class="info-item">
            <div class="info-lbl">Timeline</div>
            <div class="info-val">{{ ucfirst(str_replace('_', ' ', $partnership->timeline ?? 'N/A')) }}</div>
          </div>
          <div class="info-item">
            <div class="info-lbl">Priority Score</div>
            <div class="info-val">
              <span class="pri-pill {{ $priCls }}" style="font-size:10px;padding:3px 9px;">
                <span class="status-dot"></span>{{ $priLbl }}
              </span>
              <span class="score-badge" style="font-size:10.5px;padding:3px 8px;margin-left:4px;">{{ $score }}</span>
            </div>
          </div>
        </div>
      </div>

      {{-- ── Proposal Message ────────────────────────────────────────────── --}}
      <div class="card-section">
        <div class="section-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
          Proposal Message
        </div>
        <div class="msg-box">{{ $partnership->message ?? 'No message provided.' }}</div>
      </div>

      {{-- ── Attached Document (conditional) ────────────────────────────── --}}
      @if($partnership->document)
      <div class="card-section">
        <div class="section-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Attached Document
        </div>
        <a href="{{ asset('storage/'.$partnership->document) }}" class="doc-btn" target="_blank" rel="noopener">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
          Download Document
        </a>
      </div>
      @endif

      {{-- ── Review Status ───────────────────────────────────────────────── --}}
      <div class="card-section">
        <div class="section-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
          Review Status
        </div>
        <div class="review-row">
          <div class="review-item">
            <div class="review-lbl">Current Status</div>
            <div class="review-val">
              @if($partnership->status === 'pending')
                <span class="status-pill s-pending" style="font-size:10px;padding:3px 10px;"><span class="status-dot"></span> Pending</span>
              @elseif($partnership->status === 'approved')
                <span class="status-pill s-approved" style="font-size:10px;padding:3px 10px;"><span class="status-dot"></span> Approved</span>
              @else
                <span class="status-pill s-rejected" style="font-size:10px;padding:3px 10px;"><span class="status-dot"></span> Rejected</span>
              @endif
            </div>
          </div>
          <div class="review-item">
            <div class="review-lbl">Reviewed By</div>
            @if($partnership->reviewer)
              <div class="review-val">{{ $partnership->reviewer->name }}</div>
            @else
              <div class="review-val empty">Not reviewed yet</div>
            @endif
          </div>
          <div class="review-item">
            <div class="review-lbl">Reviewed At</div>
            @if($partnership->reviewed_at)
              <div class="review-val">{{ \Carbon\Carbon::parse($partnership->reviewed_at)->format('d M Y, h:i A') }}</div>
            @else
              <div class="review-val pending">Pending</div>
            @endif
          </div>
          <div class="review-item">
            <div class="review-lbl">Submitted At</div>
            <div class="review-val">{{ \Carbon\Carbon::parse($partnership->created_at)->format('d M Y, h:i A') }}</div>
          </div>
        </div>
      </div>

      {{-- ── Admin Actions ───────────────────────────────────────────────── --}}
      <div class="card-section">
        <div class="section-label">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          Admin Actions
        </div>
        <form method="POST" action="{{ route('admin.partnership.update', $partnership->id) }}">
          @csrf
          <div class="form-row">
            <div class="form-group">
              <label class="form-lbl">Update Status</label>
              <select name="status" class="form-select" required>
                <option value="">Select new status…</option>
                <option value="approved" {{ $partnership->status == 'approved' ? 'selected' : '' }}>✓ Approve</option>
                <option value="rejected" {{ $partnership->status == 'rejected' ? 'selected' : '' }}>✗ Reject</option>
                <option value="pending"  {{ $partnership->status == 'pending'  ? 'selected' : '' }}>↩ Reset to Pending</option>
              </select>
            </div>
          </div>
          <div class="form-group" style="margin-bottom:16px;">
            <label class="form-lbl">Admin Notes (internal)</label>
            <textarea name="admin_notes" class="form-textarea" rows="5"
              placeholder="Write internal review notes…">{{ $partnership->admin_notes }}</textarea>
          </div>
          <button type="submit" class="submit-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Update Partnership Status
          </button>
        </form>
      </div>

    </div>{{-- /main-card --}}
  </div>{{-- /body --}}
</div>{{-- /main --}}
</div>{{-- /shell --}}

<script>
(function(){
'use strict';
var html=document.documentElement,toggle=document.getElementById('themeToggle');
var saved=localStorage.getItem('adminTheme')||'light';
if(saved==='dark'){html.setAttribute('data-theme','dark');toggle.checked=true;}
toggle.addEventListener('change',function(){
  var t=this.checked?'dark':'light';
  html.setAttribute('data-theme',t);localStorage.setItem('adminTheme',t);
});
var sb=document.getElementById('sidebar');
document.getElementById('hamburger').addEventListener('click',function(){sb.classList.toggle('open');});
document.addEventListener('click',function(e){
  if(window.innerWidth<=860&&!sb.contains(e.target)&&!document.getElementById('hamburger').contains(e.target))sb.classList.remove('open');
});
(function(){
  var a=document.getElementById('flashAlert');if(!a)return;
  setTimeout(function(){a.style.transition='opacity .4s,transform .4s';a.style.opacity='0';a.style.transform='translateY(-6px)';setTimeout(function(){a.remove();},400);},4000);
})();
})();
</script>
</body>
</html>