
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $jobPostApplication->name }} — Application Review</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
/* ── TOKENS ── */
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
  --pink:#ec4899;--pink-lt:rgba(236,72,153,.10);
  --gray:#6b7280;
  --font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;
  --r:18px;--r-sm:12px;--r-xs:8px;
  --sh:0 1px 3px rgba(0,0,0,.05),0 4px 24px rgba(0,0,0,.04);
  --sh-md:0 4px 20px rgba(0,0,0,.08),0 1px 4px rgba(0,0,0,.04);
  --sh-lg:0 6px 20px rgba(0,0,0,.16);
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

/* ── LAYOUT ── */
.shell{display:flex;min-height:100vh;}

/* ── SIDEBAR ── */
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
.sc-red{background:var(--red-lt);color:var(--red);}
.sc-teal{background:var(--green-lt);color:#059669;}
.s-divider{height:1px;background:var(--sb-border);margin:10px 18px;}
.s-bottom{margin-top:auto;padding:10px 10px 20px;border-top:1px solid var(--sb-border);}

/* ── MAIN ── */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* ── TOPBAR ── */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:62px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:200;gap:14px;}
.tb-left{display:flex;align-items:center;gap:14px;}
.back-btn{display:inline-flex;align-items:center;gap:7px;height:34px;padding:0 13px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);font-size:12.5px;font-weight:500;color:var(--text2);font-family:var(--font);cursor:pointer;transition:all var(--ease);text-decoration:none;}
.back-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.back-btn svg{width:13px;height:13px;}
.tb-title h1{font-family:var(--mono);font-size:16px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.tb-title p{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
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
.av-wrap{position:relative;}
.t-av{width:36px;height:36px;border-radius:var(--r-sm);background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;font-family:var(--mono);display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;overflow:hidden;box-shadow:0 2px 10px rgba(110,86,247,.38);}
.t-av img{width:100%;height:100%;object-fit:cover;}
.av-dd{position:absolute;top:calc(100% + 10px);right:0;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r);box-shadow:var(--sh-lg);min-width:215px;z-index:9999;display:none;animation:ddIn .18s ease;}
.av-dd.open{display:block;}
@keyframes ddIn{from{opacity:0;transform:translateY(-6px) scale(.97)}to{opacity:1;transform:none}}
.dd-hdr{padding:14px 16px;border-bottom:1px solid var(--border);}
.dd-name{font-size:13.5px;font-weight:700;color:var(--text);font-family:var(--mono);}
.dd-email{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--mono);}
.dd-item{display:flex;align-items:center;gap:10px;padding:9px 16px;font-size:12.5px;font-weight:500;color:var(--text2);cursor:pointer;transition:background var(--ease);text-decoration:none;}
.dd-item:hover{background:var(--surface2);color:var(--text);}
.dd-item svg{width:13px;height:13px;color:var(--text3);flex-shrink:0;}
.dd-item.accent{color:var(--a);}.dd-item.accent svg{color:var(--a);}
.dd-item.danger{color:var(--red);}.dd-item.danger svg{color:var(--red);}
.dd-sep{height:1px;background:var(--border);margin:3px 0;}
.hamburger{display:none;width:36px;height:36px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);cursor:pointer;color:var(--text2);align-items:center;justify-content:center;flex-shrink:0;}
.hamburger svg{width:15px;height:15px;}

/* ── BODY ── */
.body{padding:26px 28px 56px;flex:1;}

/* ── HERO STRIP ── */
.hero-strip{
  border-radius:18px;padding:22px 28px;margin-bottom:24px;
  display:flex;align-items:center;justify-content:space-between;
  gap:16px;position:relative;overflow:hidden;
  background:#07080f;animation:fadeUp .35s ease both;
}
.hero-strip::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 55% 90% at 85% -10%,rgba(110,86,247,.50) 0%,transparent 60%),radial-gradient(ellipse 40% 60% at 10% 110%,rgba(155,109,255,.30) 0%,transparent 55%);}
.hero-strip::after{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:28px 28px;}
.hs-left{position:relative;z-index:2;display:flex;align-items:center;gap:16px;}
.hs-avatar{width:54px;height:54px;border-radius:14px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:20px;font-weight:800;font-family:var(--mono);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 18px rgba(110,86,247,.45);}
.hs-name{font-family:var(--mono);font-size:20px;font-weight:800;color:#fff;letter-spacing:-.02em;line-height:1.15;background:linear-gradient(135deg,#fff 30%,rgba(184,169,255,.85));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.hs-sub{font-size:12.5px;color:rgba(255,255,255,.5);margin-top:3px;font-family:var(--mono);}
.hs-right{position:relative;z-index:2;display:flex;align-items:center;gap:10px;flex-wrap:wrap;}

/* ── BADGES ── */
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3.5px 9px;border-radius:7px;text-transform:uppercase;letter-spacing:.07em;font-family:var(--mono);}
.b-pending{background:rgba(245,158,11,.85);color:#fff;}
.b-shortlisted{background:rgba(5,196,138,.85);color:#fff;}
.b-rejected{background:rgba(240,68,68,.85);color:#fff;}
.b-hired{background:rgba(110,86,247,.85);color:#fff;}

/* ── GRID ── */
.content-grid{display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start;}

/* ── CARDS ── */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;}
.card-header{padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;}
.card-header-left{display:flex;align-items:center;gap:10px;}
.card-hico{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-hico svg{width:14px;height:14px;}
.card-title{font-family:var(--mono);font-size:12px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-title-sm{font-size:10px;color:var(--text3);font-family:var(--mono);font-weight:600;text-transform:uppercase;letter-spacing:.1em;}
.card-link{font-size:12px;color:var(--a);font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:4px;transition:opacity var(--ease);}
.card-link:hover{opacity:.75;}
.card-link svg{width:11px;height:11px;}
.card-body{padding:20px;}
.card-body + .card-body{border-top:1px solid var(--border);}

/* ── FIELD ROWS ── */
.field{margin-bottom:16px;}
.field:last-child{margin-bottom:0;}
.field-lbl{font-family:var(--mono);font-size:9.5px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--text3);margin-bottom:6px;}
.field-val{font-size:13.5px;color:var(--text);line-height:1.5;}
.field-val a{color:var(--a);font-weight:500;}
.field-val a:hover{text-decoration:underline;}
.field-divider{height:1px;background:var(--border);margin:16px 0;}

/* ── COVER LETTER ── */
.cover-letter{font-size:13.5px;line-height:1.85;color:var(--text2);white-space:pre-wrap;border-left:3px solid var(--a-lt);padding-left:16px;}

/* ── JOB CHIPS ── */
.job-chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px;}
.job-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-size:11px;font-weight:500;color:var(--text2);background:var(--surface2);border:1px solid var(--border);font-family:var(--mono);}
.job-chip svg{width:11px;height:11px;color:var(--text3);}

/* ── CV BUTTON ── */
.cv-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border-radius:var(--r-sm);background:var(--green-lt);border:1px solid rgba(5,196,138,.25);color:var(--green);font-size:13px;font-weight:600;text-decoration:none;transition:all var(--ease);font-family:var(--font);}
.cv-btn:hover{background:var(--green);color:#fff;border-color:var(--green);transform:translateY(-1px);box-shadow:0 4px 14px rgba(5,196,138,.3);}
.cv-btn svg{width:15px;height:15px;}

/* ── FORM ── */
.form-group{margin-bottom:16px;}
.form-group:last-child{margin-bottom:0;}
.form-lbl{font-family:var(--mono);font-size:9.5px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--text3);margin-bottom:8px;display:block;}
.form-select,.form-textarea{width:100%;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.form-select:focus,.form-textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.form-textarea{resize:vertical;min-height:100px;line-height:1.55;}
.form-select option{background:var(--surface);}
.btn-save{width:100%;padding:12px;border-radius:var(--r-sm);background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;font-size:13.5px;font-weight:600;font-family:var(--font);cursor:pointer;transition:all var(--ease);box-shadow:0 4px 16px rgba(110,86,247,.3);}
.btn-save:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(110,86,247,.45);}
.btn-save:active{transform:scale(.98);}

/* ── STATUS PREVIEW ── */
.status-preview{display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border-radius:var(--r-sm);background:var(--surface2);border:1px solid var(--border);margin-bottom:16px;}
.sp-label{font-size:11px;color:var(--text3);font-family:var(--mono);}

/* ── TIMELINE ── */
.timeline{display:flex;flex-direction:column;gap:0;}
.tl-item{display:flex;gap:12px;padding:12px 0;}
.tl-item:not(:last-child){border-bottom:1px solid var(--border);}
.tl-dot{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;}
.tl-dot svg{width:13px;height:13px;}
.tl-label{font-size:12.5px;font-weight:600;color:var(--text);line-height:1.3;}
.tl-time{font-size:11px;color:var(--text3);font-family:var(--mono);margin-top:2px;}

/* ── FLASH ── */
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-success{background:rgba(5,196,138,.10);border:1px solid rgba(5,196,138,.25);color:#059669;}
.flash-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);}
[data-theme="dark"] .flash-success{color:#34d399;}
[data-theme="dark"] .flash-error{color:#f87171;}
.flash svg{width:14px;height:14px;flex-shrink:0;}

/* ── UTILS ── */
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}.content-grid{grid-template-columns:1fr}}
@media(max-width:600px){.topbar{padding:0 14px}.body{padding:14px 14px 48px}.hs-avatar{width:42px;height:42px;font-size:16px}.hs-name{font-size:16px}}
</style>
</head>
<body>
<div class="shell">

{{-- ── SIDEBAR ── --}}
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
    <a href="{{ route('admin.dashboard') }}" class="s-link">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>
  </nav>

  <div class="s-section">Campaigns</div>
  <nav class="s-nav">
    <a href="{{ url('/admin/campaigns') }}" class="s-link">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
      All Campaigns
      <span class="s-chip sc-purple">{{ \App\Models\Campaign::count() }}</span>
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
    <a href="{{ route('admin.job_post_applications.index') }}" class="s-link active">
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
    <div class="tb-left">
      <button class="hamburger" id="hamburger" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <a href="{{ route('admin.job_post_applications.index') }}" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        All Applications
      </a>
      <div class="tb-title">
        <h1>Application Review</h1>
        <p>{{ $jobPostApplication->jobPost->title }}</p>
      </div>
    </div>
    <div class="tb-right">
      <div class="theme-wrap">
        <input type="checkbox" id="themeToggle">
        <label for="themeToggle">
          <div class="ti">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
          </div>
        </label>
      </div>
      <div class="av-wrap" id="avWrap">
        <div class="t-av" onclick="toggleDD()" title="Account">
          @if(auth()->user()->avatar)
            <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
          @else
            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
          @endif
        </div>
        <div class="av-dd" id="avDD">
          <div class="dd-hdr">
            <div class="dd-name">{{ auth()->user()->name ?? 'Admin' }}</div>
            <div class="dd-email">{{ auth()->user()->email ?? '' }}</div>
          </div>
          <a href="{{ route('profile.show') }}" class="dd-item accent">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            View Profile
          </a>
          <div class="dd-sep"></div>
          <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('lf').submit();" class="dd-item danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
          </a>
        </div>
      </div>
    </div>
  </header>

  <div class="body">

    {{-- ── FLASH ── --}}
    @if(session('success'))
    <div class="flash flash-success">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flash flash-error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      {{ session('error') }}
    </div>
    @endif

    {{-- ── HERO STRIP ── --}}
    <div class="hero-strip">
      <div class="hs-left">
        <div class="hs-avatar">{{ strtoupper(substr($jobPostApplication->name, 0, 1)) }}</div>
        <div>
          <div class="hs-name">{{ $jobPostApplication->name }}</div>
          <div class="hs-sub">Applied {{ $jobPostApplication->created_at->diffForHumans() }} · {{ $jobPostApplication->created_at->format('d M Y, h:i A') }}</div>
        </div>
      </div>
      <div class="hs-right">
        <span class="badge b-{{ $jobPostApplication->status }}">{{ $jobPostApplication->status }}</span>
        @if($jobPostApplication->cv_path)
        <a href="{{ route('admin.job_post_applications.downloadCv', $jobPostApplication) }}" class="cv-btn" style="padding:8px 14px;font-size:12px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Download CV
        </a>
        @endif
      </div>
    </div>

    {{-- ── CONTENT GRID ── --}}
    <div class="content-grid">

      {{-- LEFT COLUMN --}}
      <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Contact Info --}}
        <div class="card" style="animation-delay:.05s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--a-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              </div>
              <span class="card-title">Applicant Details</span>
            </div>
          </div>
          <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
              <div class="field">
                <div class="field-lbl">Full Name</div>
                <div class="field-val">{{ $jobPostApplication->name }}</div>
              </div>
              <div class="field">
                <div class="field-lbl">Email Address</div>
                <div class="field-val"><a href="mailto:{{ $jobPostApplication->email }}">{{ $jobPostApplication->email }}</a></div>
              </div>
              @if($jobPostApplication->phone)
              <div class="field">
                <div class="field-lbl">Phone</div>
                <div class="field-val"><a href="tel:{{ $jobPostApplication->phone }}">{{ $jobPostApplication->phone }}</a></div>
              </div>
              @endif
              <div class="field">
                <div class="field-lbl">Applied On</div>
                <div class="field-val">{{ $jobPostApplication->created_at->format('d M Y \a\t h:i A') }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Cover Letter --}}
        @if($jobPostApplication->cover_letter)
        <div class="card" style="animation-delay:.10s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--blue-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
              <span class="card-title">Cover Letter</span>
            </div>
          </div>
          <div class="card-body">
            <p class="cover-letter">{{ $jobPostApplication->cover_letter }}</p>
          </div>
        </div>
        @endif

        {{-- Job Post --}}
        <div class="card" style="animation-delay:.15s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--green-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              </div>
              <span class="card-title">Applied For</span>
            </div>
            <a href="{{ route('admin.job_posts.show', $jobPostApplication->jobPost) }}" class="card-link">
              View Post
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>
          <div class="card-body">
            <div class="field-val" style="font-size:15px;font-weight:700;">{{ $jobPostApplication->jobPost->title }}</div>
            <div class="job-chips">
              <span class="job-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ ucfirst($jobPostApplication->jobPost->type) }}
              </span>
              @if($jobPostApplication->jobPost->location)
              <span class="job-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $jobPostApplication->jobPost->location }}
              </span>
              @endif
              @if($jobPostApplication->jobPost->salary)
              <span class="job-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                ₹{{ $jobPostApplication->jobPost->salary }}
              </span>
              @endif
            </div>
          </div>
        </div>

      </div>

      {{-- RIGHT COLUMN --}}
      <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Review & Decision --}}
        <div class="card" style="animation-delay:.08s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--amber-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
              </div>
              <span class="card-title">Review & Decision</span>
            </div>
          </div>
          <div class="card-body">
            <div class="status-preview">
              <span class="sp-label">Current Status</span>
              <span class="badge b-{{ $jobPostApplication->status }}">{{ $jobPostApplication->status }}</span>
            </div>
            <form method="POST" action="{{ route('admin.job_post_applications.updateStatus', $jobPostApplication) }}">
              @csrf
              @method('PATCH')
              <div class="form-group">
                <label class="form-lbl">Update Status</label>
                <select name="status" class="form-select">
                  @foreach(['pending','shortlisted','rejected','hired'] as $s)
                    <option value="{{ $s }}" @selected($jobPostApplication->status === $s)>{{ ucfirst($s) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label class="form-lbl">Admin Notes</label>
                <textarea name="admin_notes" class="form-textarea" placeholder="Internal notes about this applicant…">{{ old('admin_notes', $jobPostApplication->admin_notes) }}</textarea>
              </div>
              <button type="submit" class="btn-save">Save Decision</button>
            </form>
          </div>
        </div>

        {{-- CV Download (if not already in hero) --}}
        @if($jobPostApplication->cv_path)
        <div class="card" style="animation-delay:.12s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--green-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
              <span class="card-title">Resume / CV</span>
            </div>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.job_post_applications.downloadCv', $jobPostApplication) }}" class="cv-btn" style="width:100%;justify-content:center;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              Download CV
            </a>
          </div>
        </div>
        @endif

        {{-- Activity Timeline --}}
        <div class="card" style="animation-delay:.16s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--a-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <span class="card-title">Timeline</span>
            </div>
          </div>
          <div class="card-body" style="padding:0;">
            <div class="timeline" style="padding:0 20px;">
              <div class="tl-item">
                <div class="tl-dot" style="background:var(--a-lt);">
                  <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                  <div class="tl-label">Application submitted</div>
                  <div class="tl-time">{{ $jobPostApplication->created_at->format('d M Y, h:i A') }}</div>
                </div>
              </div>
              @if($jobPostApplication->updated_at && $jobPostApplication->updated_at->ne($jobPostApplication->created_at))
              <div class="tl-item">
                <div class="tl-dot" style="background:var(--amber-lt);">
                  <svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                  <div class="tl-label">Status updated to <strong>{{ $jobPostApplication->status }}</strong></div>
                  <div class="tl-time">{{ $jobPostApplication->updated_at->format('d M Y, h:i A') }}</div>
                </div>
              </div>
              @endif
              <div class="tl-item" style="border-bottom:none;">
                @php
                  $isResolved = in_array($jobPostApplication->status, ['shortlisted','rejected','hired']);
                @endphp
                <div class="tl-dot" style="background:{{ $isResolved ? 'var(--green-lt)' : 'var(--surface3)' }};">
                  <svg viewBox="0 0 24 24" fill="none" stroke="{{ $isResolved ? 'var(--green)' : 'var(--text3)' }}" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                  <div class="tl-label" style="{{ $isResolved ? '' : 'color:var(--text3)' }}">{{ $isResolved ? 'Review complete' : 'Awaiting decision' }}</div>
                  <div class="tl-time">{{ $isResolved ? $jobPostApplication->updated_at->format('d M Y') : 'Pending' }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>{{-- /.content-grid --}}

  </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

<script>
(function(){
'use strict';
var html = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved = localStorage.getItem('adminTheme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
  var t = this.checked ? 'dark' : 'light';
  html.setAttribute('data-theme', t);
  localStorage.setItem('adminTheme', t);
});
document.getElementById('hamburger').addEventListener('click', function(){
  document.getElementById('sidebar').classList.toggle('open');
});
document.addEventListener('click', function(e){
  var sb = document.getElementById('sidebar');
  if (window.innerWidth <= 860 && !sb.contains(e.target) && !document.getElementById('hamburger').contains(e.target))
    sb.classList.remove('open');
});
window.toggleDD = function(){ document.getElementById('avDD').classList.toggle('open'); };
document.addEventListener('click', function(e){
  var w = document.getElementById('avWrap');
  if (w && !w.contains(e.target)) document.getElementById('avDD').classList.remove('open');
});
})();
</script>
</body>
</html>