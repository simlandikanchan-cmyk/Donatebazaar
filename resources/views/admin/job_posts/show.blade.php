{{-- resources/views/admin/job_posts/show.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $jobPost->title }} — DonateBazaar Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
/* ══════════════════════════════════════════
   TOKENS — identical to job_posts/index
══════════════════════════════════════════ */
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
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* ══════════════════════════════════════════
   SIDEBAR — light (same as index)
══════════════════════════════════════════ */
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

/* ══════════════════════════════════════════
   TOPBAR
══════════════════════════════════════════ */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:62px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:200;gap:14px;}
.tb-left h1{font-family:var(--mono);font-size:17px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.tb-left p{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.tb-right{display:flex;align-items:center;gap:8px;}
.theme-wrap{position:relative;}
.theme-wrap input{position:absolute;opacity:0;width:0;height:0;}
.theme-wrap label{display:flex;align-items:center;justify-content:space-between;width:52px;height:28px;border-radius:100px;background:var(--surface2);border:1px solid var(--border2);cursor:pointer;padding:4px;position:relative;}
.theme-wrap label::after{content:'';width:18px;height:18px;border-radius:50%;background:var(--a);position:absolute;left:5px;transition:transform .3s cubic-bezier(.4,0,.2,1);box-shadow:0 2px 6px rgba(110,86,247,.4);}
.theme-wrap input:checked+label::after{transform:translateX(23px);}
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

/* ══════════════════════════════════════════
   PAGE BODY
══════════════════════════════════════════ */
.body{padding:26px 28px 56px;flex:1;}

/* ── BREADCRUMB ── */
.breadcrumb{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text3);font-family:var(--mono);margin-bottom:22px;animation:fadeUp .3s ease both;}
.breadcrumb a{color:var(--text3);transition:color var(--ease);}
.breadcrumb a:hover{color:var(--a);}
.breadcrumb svg{width:12px;height:12px;flex-shrink:0;}
.breadcrumb .cur{color:var(--text2);font-weight:600;}

/* ── PAGE ACTIONS ROW ── */
.page-actions{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:24px;flex-wrap:wrap;animation:fadeUp .35s ease both;}
.page-actions-right{display:flex;gap:8px;align-items:center;flex-wrap:wrap;}

/* ── HERO BANNER (mirrors index hero) ── */
.hero{border-radius:22px;padding:28px 32px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:20px;position:relative;overflow:hidden;animation:fadeUp .4s .04s ease both;background:#07080f;}
.hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% -10%,rgba(110,86,247,.55) 0%,transparent 60%),radial-gradient(ellipse 50% 60% at 15% 110%,rgba(155,109,255,.35) 0%,transparent 55%);}
.hero::after{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);background-size:32px 32px;}
.hero-left{position:relative;z-index:2;flex:1;min-width:0;}
.hero-tag{display:inline-flex;align-items:center;gap:6px;font-size:10px;font-weight:600;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.14em;font-family:var(--mono);margin-bottom:10px;}
.hero-tag-dot{width:6px;height:6px;border-radius:50%;background:var(--green);animation:pulse 2s ease infinite;}
@keyframes pulse{0%,100%{box-shadow:0 0 0 0 rgba(5,196,138,.5)}50%{box-shadow:0 0 0 6px rgba(5,196,138,0)}}
.hero-name{font-family:var(--mono);font-size:24px;font-weight:800;color:#fff;letter-spacing:-.03em;line-height:1.15;margin-bottom:10px;background:linear-gradient(135deg,#fff 30%,rgba(184,169,255,.85));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.hero-meta{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px;}
.hero-chip{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);}
.hc-type{background:var(--a-lt);border:1px solid rgba(110,86,247,.3);color:#c4b5fd;}
.hc-loc{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);color:rgba(255,255,255,.75);}
.hc-sal{background:rgba(5,196,138,.2);border:1px solid rgba(5,196,138,.3);color:#6ee7b7;}
.hc-exp{background:rgba(59,130,246,.2);border:1px solid rgba(59,130,246,.3);color:#93c5fd;}
.hc-vac{background:rgba(245,158,11,.2);border:1px solid rgba(245,158,11,.3);color:#fde68a;}
.hero-chip svg{width:11px;height:11px;}
.hero-badges{display:flex;gap:8px;flex-wrap:wrap;}
.hero-badge{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);}
.hb-active{background:rgba(5,196,138,.2);color:#6ee7b7;border:1px solid rgba(5,196,138,.3);}
.hb-draft{background:rgba(107,114,128,.2);color:#d1d5db;border:1px solid rgba(107,114,128,.3);}
.hb-closed{background:rgba(240,68,68,.2);color:#fca5a5;border:1px solid rgba(240,68,68,.3);}
.hero-right{position:relative;z-index:2;display:flex;gap:10px;flex-shrink:0;flex-wrap:wrap;}
.hero-stat-card{padding:14px 20px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:var(--r-sm);text-align:center;min-width:96px;}
.hsc-val{font-family:var(--mono);font-size:26px;font-weight:800;line-height:1;letter-spacing:-.02em;}
.hsc-lbl{font-size:10px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.1em;margin-top:4px;color:rgba(255,255,255,.5);}

/* ── STAT STRIP ── */
.stat-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;box-shadow:var(--sh);display:flex;align-items:flex-start;gap:14px;transition:transform var(--ease),box-shadow var(--ease);animation:fadeUp .4s ease both;position:relative;overflow:hidden;}
.stat:hover{transform:translateY(-3px);box-shadow:var(--sh-md);}
.stat::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2.5px;border-radius:0 0 var(--r) var(--r);opacity:0;transition:opacity var(--ease);}
.stat:hover::after{opacity:1;}
.stat:nth-child(1){animation-delay:.08s;}.stat:nth-child(1)::after{background:linear-gradient(90deg,var(--amber),#f97316);}
.stat:nth-child(2){animation-delay:.13s;}.stat:nth-child(2)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat:nth-child(3){animation-delay:.18s;}.stat:nth-child(3)::after{background:linear-gradient(90deg,var(--red),#f87171);}
.stat:nth-child(4){animation-delay:.23s;}.stat:nth-child(4)::after{background:linear-gradient(90deg,var(--blue),#6366f1);}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:19px;height:19px;}
.si-amber{background:var(--amber-lt);color:var(--amber);}
.si-green{background:var(--green-lt);color:var(--green);}
.si-red{background:var(--red-lt);color:var(--red);}
.si-blue{background:var(--blue-lt);color:var(--blue);}
.stat-body{flex:1;min-width:0;}
.stat-lbl{font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:5px;}
.stat-val{font-family:var(--mono);font-size:2rem;font-weight:800;line-height:1;letter-spacing:-.03em;}
.sv-amber{color:var(--amber);}
.sv-green{color:var(--green);}
.sv-red{color:var(--red);}
.sv-blue{color:var(--blue);}
.stat-foot{font-size:11px;color:var(--text3);margin-top:4px;}

/* ── CONTENT GRID ── */
.content-grid{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;}

/* ── CARDS ── */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;}
.card+.card{margin-top:16px;}
.card-header{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:16px 22px;border-bottom:1px solid var(--border);}
.card-header-left{display:flex;align-items:center;gap:10px;}
.card-hico{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-hico svg{width:16px;height:16px;}
.ci-purple{background:var(--a-lt);color:var(--a);}
.ci-amber{background:var(--amber-lt);color:var(--amber);}
.ci-blue{background:var(--blue-lt);color:var(--blue);}
.ci-red{background:var(--red-lt);color:var(--red);}
.ci-green{background:var(--green-lt);color:var(--green);}
.card-title{font-family:var(--mono);font-size:13.5px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-sub{font-size:11px;color:var(--text3);font-family:var(--mono);margin-top:1px;}
.card-body{padding:22px;}

/* ── DESCRIPTION ── */
.desc-body{font-size:14px;color:var(--text2);line-height:1.85;white-space:pre-wrap;word-break:break-word;}

/* ── BADGES ── */
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3.5px 9px;border-radius:7px;text-transform:uppercase;letter-spacing:.07em;font-family:var(--mono);white-space:nowrap;}
.b-active{background:rgba(5,196,138,.85);color:#fff;}
.b-draft{background:rgba(107,114,128,.75);color:#fff;}
.b-closed{background:rgba(240,68,68,.85);color:#fff;}
.b-pending{background:rgba(245,158,11,.85);color:#fff;}
.b-shortlisted{background:rgba(5,196,138,.85);color:#fff;}
.b-rejected{background:rgba(240,68,68,.85);color:#fff;}
.b-hired{background:rgba(110,86,247,.85);color:#fff;}

/* ── TABLE ── */
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead{background:var(--surface2);border-bottom:1px solid var(--border);}
thead th{padding:10px 14px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--text3);font-family:var(--mono);white-space:nowrap;}
thead th:first-child{padding-left:22px;}
thead th:last-child{padding-right:22px;text-align:right;}
tbody td{padding:13px 14px;border-bottom:1px solid var(--border);vertical-align:middle;font-size:13px;}
tbody td:first-child{padding-left:22px;}
tbody td:last-child{padding-right:22px;}
tbody tr:last-child td{border-bottom:none;}
tbody tr{transition:background var(--ease);}
tbody tr:hover{background:var(--surface2);}
.td-mono{font-family:var(--mono);font-size:11.5px;color:var(--text3);}
.td-name{font-weight:600;color:var(--text);}
.td-sub{font-size:11px;color:var(--text3);font-family:var(--mono);margin-top:1px;}

/* ── ACTION BUTTONS ── */
.act-btns{display:flex;align-items:center;justify-content:flex-end;gap:4px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:11.5px;font-weight:500;cursor:pointer;border:1px solid transparent;transition:all var(--ease);text-decoration:none;font-family:var(--font);white-space:nowrap;}
.act-btn svg{width:11px;height:11px;}
.ab-view{background:var(--surface2);color:var(--text2);border-color:var(--border2);}
.ab-view:hover{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.2);}
.ab-download{background:var(--green-lt);color:var(--green);border-color:rgba(5,196,138,.2);}
.ab-download:hover{background:var(--green);color:#fff;border-color:var(--green);}

/* ── EMPTY STATE ── */
.empty-state{padding:48px 20px;text-align:center;}
.empty-icon{width:52px;height:52px;border-radius:14px;background:var(--surface2);border:1px solid var(--border2);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;}
.empty-icon svg{width:22px;height:22px;color:var(--text3);}
.empty-ttl{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2);margin-bottom:5px;}
.empty-sub{font-size:13px;color:var(--text3);}

/* ── SIDEBAR STACK ── */
.side-stack{display:flex;flex-direction:column;gap:16px;position:sticky;top:80px;}

/* ── SIDE CARD ── */
.side-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;}

/* ── INFO ROWS ── */
.info-list{padding:0 18px;}
.info-row{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:10px 0;border-bottom:1px solid var(--border);}
.info-row:last-child{border-bottom:none;}
.info-lbl{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.info-val{font-size:12.5px;font-weight:600;color:var(--text2);text-align:right;}
.info-val.green{color:var(--green);}
.info-val.amber{color:var(--amber);}
.info-val.red{color:var(--red);}

/* ── ACTIONS ── */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:11px 20px;border-radius:var(--r-sm);font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all var(--ease);font-family:var(--font);width:100%;}
.btn:active{transform:scale(.97);}
.btn svg{width:14px;height:14px;}
.btn-primary{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 18px rgba(110,86,247,.35);}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(110,86,247,.45);}
.btn-secondary{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-secondary:hover{background:var(--surface3);color:var(--text);}
.btn-danger{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.2);}
.btn-danger:hover{background:var(--red);color:#fff;border-color:var(--red);}
.btn-back{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;border:1px solid var(--border2);background:var(--surface);color:var(--text2);cursor:pointer;transition:all var(--ease);text-decoration:none;font-family:var(--font);}
.btn-back:hover{background:var(--surface2);color:var(--text);}
.btn-back svg{width:13px;height:13px;}
.btn-edit{background:var(--a-lt);color:var(--a);border:1px solid rgba(110,86,247,.2);}
.btn-edit:hover{background:var(--a);color:#fff;border-color:var(--a);}
.btn-stack{display:flex;flex-direction:column;gap:8px;padding:18px;}

/* ── DANGER ZONE ── */
.danger-zone{background:linear-gradient(135deg,rgba(240,68,68,.05),rgba(240,68,68,.02));border:1px solid rgba(240,68,68,.18);border-radius:var(--r);padding:18px;animation:fadeUp .4s .18s ease both;}
.danger-hdr{display:flex;align-items:center;gap:8px;margin-bottom:8px;}
.danger-hdr svg{width:14px;height:14px;color:var(--red);}
.danger-hdr span{font-size:11px;font-weight:700;color:var(--red);font-family:var(--mono);text-transform:uppercase;letter-spacing:.1em;}
.danger-desc{font-size:12px;color:var(--text3);line-height:1.5;margin-bottom:12px;}

/* ── TOAST ── */
.toast-wrap{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:270px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s ease both;}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-ok{background:linear-gradient(135deg,#059669,#10b981);}
.toast-err{background:linear-gradient(135deg,#dc2626,#f04444);}
.toast-x{margin-left:auto;width:18px;height:18px;border-radius:5px;background:rgba(255,255,255,.22);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}

/* ── DELETE MODAL ── */
.overlay{display:none;position:fixed;inset:0;z-index:9998;background:rgba(4,5,14,.65);backdrop-filter:blur(12px);align-items:center;justify-content:center;padding:20px;}
.overlay.open{display:flex;}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:22px;box-shadow:var(--sh-lg);width:100%;max-width:400px;padding:26px;position:relative;animation:modalIn .2s ease;}
@keyframes modalIn{from{opacity:0;transform:scale(.95) translateY(12px)}to{opacity:1;transform:none}}
.modal-x{position:absolute;top:16px;right:16px;width:28px;height:28px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);cursor:pointer;color:var(--text2);display:flex;align-items:center;justify-content:center;transition:all var(--ease);}
.modal-x:hover{background:var(--border2);transform:rotate(90deg);}
.modal-x svg{width:11px;height:11px;}
.modal-head{display:flex;align-items:center;gap:14px;margin-bottom:16px;}
.modal-ico{width:44px;height:44px;border-radius:13px;background:var(--red-lt);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.modal-ico svg{width:21px;height:21px;color:var(--red);}
.modal-ttl{font-family:var(--mono);font-size:16px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.modal-sub{font-size:12px;color:var(--text3);margin-top:2px;}
.modal-body{font-size:13.5px;color:var(--text2);line-height:1.6;margin-bottom:20px;}
.modal-body strong{color:var(--text);}
.modal-acts{display:flex;gap:9px;}
.modal-btn{flex:1;padding:12px;border-radius:11px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all var(--ease);font-family:var(--font);}
.modal-cancel{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.modal-red{background:linear-gradient(135deg,var(--red),#dc2626);color:#fff;box-shadow:0 4px 16px rgba(240,68,68,.3);}

/* ── UTILS ── */
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
@keyframes toastIn{from{opacity:0;transform:translateX(18px) scale(.96)}to{opacity:1;transform:none}}

/* ── RESPONSIVE ── */
@media(max-width:1100px){.content-grid{grid-template-columns:1fr}.side-stack{position:static}}
@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}.stat-strip{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.topbar{padding:0 16px}.body{padding:14px 14px 48px}.stat-strip{grid-template-columns:1fr 1fr}.hero{flex-direction:column}.hero-right{width:100%;flex-direction:row}.hero-stat-card{flex:1}}
</style>
</head>
<body>

<div class="toast-wrap" id="toastWrap"></div>

{{-- ══════ DELETE MODAL ══════ --}}
<div id="deleteOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeDelete()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="modal-head">
      <div class="modal-ico">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
      </div>
      <div>
        <div class="modal-ttl">Delete Job Post</div>
        <div class="modal-sub">This action cannot be undone</div>
      </div>
    </div>
    <div class="modal-body">
      Are you sure you want to permanently delete <strong>"{{ $jobPost->title }}"</strong>?
      All <strong>{{ $jobPost->applications()->count() }} application(s)</strong> linked to this post will also be removed.
    </div>
    <div class="modal-acts">
      <button type="button" onclick="closeDelete()" class="modal-btn modal-cancel">Cancel</button>
      <form action="{{ route('admin.job_posts.destroy', $jobPost->id) }}" method="POST" style="flex:1;">
        @csrf @method('DELETE')
        <button type="submit" class="modal-btn modal-red" style="width:100%;">🗑 Delete Permanently</button>
      </form>
    </div>
  </div>
</div>

<div class="shell">

{{-- ══════ SIDEBAR ══════ --}}
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
    <a href="{{ route('admin.job_posts.index') }}" class="s-link active">
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

{{-- ══════ MAIN ══════ --}}
<div class="main">

  <header class="topbar">
    <div style="display:flex;align-items:center;gap:10px;">
      <button class="hamburger" id="hamburger" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <div class="tb-left">
        <h1>Job Post Details</h1>
        <p>Job Board › #{{ $jobPost->id }} › {{ Str::limit($jobPost->title, 36) }}</p>
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
          <a href="{{ route('profile.edit') }}" class="dd-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Profile
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

    {{-- BREADCRUMB --}}
    <div class="breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <a href="{{ route('admin.job_posts.index') }}">Job Posts</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <span class="cur">{{ Str::limit($jobPost->title, 40) }}</span>
    </div>

    {{-- PAGE ACTIONS --}}
    <div class="page-actions">
      <a href="{{ route('admin.job_posts.index') }}" class="btn-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        All Listings
      </a>
      <div class="page-actions-right">
        <a href="{{ route('admin.job_posts.edit', $jobPost->id) }}" class="btn btn-edit" style="width:auto;padding:9px 18px;font-size:12.5px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          Edit Post
        </a>
        <button type="button" onclick="openDelete()" class="btn btn-danger" style="width:auto;padding:9px 18px;font-size:12.5px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete
        </button>
      </div>
    </div>

    {{-- HERO --}}
    @php
      $isExpired = $jobPost->application_deadline && \Carbon\Carbon::parse($jobPost->application_deadline)->isPast();
      $statusKey = ($jobPost->status === 'closed' || $isExpired) ? 'closed' : ($jobPost->status === 'draft' ? 'draft' : 'active');
      $appCount  = $jobPost->applications()->count();
      $pendCount = $jobPost->applications()->where('status','pending')->count();
      $accCount  = $jobPost->applications()->where('status','shortlisted')->orWhere('status','accepted')->count();
      $rejCount  = $jobPost->applications()->where('status','rejected')->count();
    @endphp
    <div class="hero">
      <div class="hero-left">
        <div class="hero-tag"><span class="hero-tag-dot"></span>Job Board · #{{ $jobPost->id }}</div>
        <div class="hero-name">{{ $jobPost->title }}</div>
        <div class="hero-meta">
          @if($jobPost->type)
          <span class="hero-chip hc-type">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            {{ ucfirst($jobPost->type) }}
          </span>
          @endif
          @if($jobPost->location)
          <span class="hero-chip hc-loc">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $jobPost->location }}
          </span>
          @endif
          @if($jobPost->salary)
          <span class="hero-chip hc-sal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $jobPost->salary }}
          </span>
          @endif
          @if($jobPost->experience_required)
          <span class="hero-chip hc-exp">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            {{ $jobPost->experience_required }}
          </span>
          @endif
          @if($jobPost->vacancies)
          <span class="hero-chip hc-vac">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
            {{ $jobPost->vacancies }} {{ Str::plural('vacancy', $jobPost->vacancies) }}
          </span>
          @endif
        </div>
        <div class="hero-badges">
          @if($statusKey === 'active')
            <span class="hero-badge hb-active">● Active</span>
          @elseif($statusKey === 'draft')
            <span class="hero-badge hb-draft">✎ Draft</span>
          @else
            <span class="hero-badge hb-closed">✕ Closed</span>
          @endif
          @if($jobPost->featured)<span class="hero-badge" style="background:rgba(245,158,11,.2);color:#fde68a;border:1px solid rgba(245,158,11,.3);">★ Featured</span>@endif
          @if($jobPost->is_remote)<span class="hero-badge" style="background:rgba(110,86,247,.2);color:#c4b5fd;border:1px solid rgba(110,86,247,.3);">🌐 Remote</span>@endif
          <span class="hero-badge" style="background:rgba(255,255,255,.06);color:rgba(255,255,255,.5);border:1px solid rgba(255,255,255,.1);">Posted {{ $jobPost->created_at->format('d M Y') }}</span>
        </div>
      </div>
      <div class="hero-right">
        <div class="hero-stat-card">
          <div class="hsc-val" style="color:var(--amber);">{{ $appCount }}</div>
          <div class="hsc-lbl">Applications</div>
        </div>
        <div class="hero-stat-card">
          <div class="hsc-val" style="color:var(--green);">{{ $accCount }}</div>
          <div class="hsc-lbl">Shortlisted</div>
        </div>
      </div>
    </div>

    {{-- STAT STRIP --}}
    <div class="stat-strip">
      <div class="stat" style="animation-delay:.08s;">
        <div class="stat-icon si-amber">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Total</div>
          <div class="stat-val sv-amber">{{ $appCount }}</div>
          <div class="stat-foot">All applications</div>
        </div>
      </div>
      <div class="stat" style="animation-delay:.13s;">
        <div class="stat-icon si-green">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Shortlisted</div>
          <div class="stat-val sv-green">{{ $accCount }}</div>
          <div class="stat-foot">Moving forward</div>
        </div>
      </div>
      <div class="stat" style="animation-delay:.18s;">
        <div class="stat-icon si-red">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Rejected</div>
          <div class="stat-val sv-red">{{ $rejCount }}</div>
          <div class="stat-foot">Not selected</div>
        </div>
      </div>
      <div class="stat" style="animation-delay:.23s;">
        <div class="stat-icon si-blue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Pending</div>
          <div class="stat-val sv-blue">{{ $pendCount }}</div>
          <div class="stat-foot">Awaiting review</div>
        </div>
      </div>
    </div>

    {{-- CONTENT GRID --}}
    <div class="content-grid">

      {{-- LEFT --}}
      <div>

        {{-- DESCRIPTION --}}
        <div class="card" style="animation-delay:.10s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico ci-purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h10"/></svg>
              </div>
              <div>
                <div class="card-title">Job Description</div>
                <div class="card-sub">Full listing content</div>
              </div>
            </div>
          </div>
          <div class="card-body">
            @if($jobPost->description)
              <div class="desc-body">{{ $jobPost->description }}</div>
            @else
              <div style="color:var(--text3);font-size:13px;font-style:italic;">No description provided.</div>
            @endif
          </div>
        </div>

        {{-- APPLICANTS TABLE --}}
        <div class="card" style="animation-delay:.14s;margin-top:16px;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico ci-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
              </div>
              <div>
                <div class="card-title">Applicants</div>
                <div class="card-sub">{{ $appCount }} submission{{ $appCount !== 1 ? 's' : '' }}</div>
              </div>
            </div>
            @if($appCount > 0)
            <a href="{{ route('admin.job_post_applications.index', ['job_id' => $jobPost->id]) }}" class="btn btn-secondary" style="width:auto;padding:7px 14px;font-size:12px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              View All
            </a>
            @endif
          </div>

          @if($appCount > 0)
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Applicant</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Applied</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($jobPost->applications()->latest()->take(10)->get() as $i => $app)
                @php
                  $aBadge = match($app->status ?? 'pending') {
                    'shortlisted','accepted' => 'b-shortlisted',
                    'rejected'               => 'b-rejected',
                    'hired'                  => 'b-hired',
                    default                  => 'b-pending',
                  };
                @endphp
                <tr>
                  <td class="td-mono">{{ $i + 1 }}</td>
                  <td>
                    <div class="td-name">{{ $app->name ?? 'N/A' }}</div>
                    @if($app->phone)<div class="td-sub">{{ $app->phone }}</div>@endif
                  </td>
                  <td class="td-mono" style="font-size:12px;">{{ $app->email ?? '—' }}</td>
                  <td><span class="badge {{ $aBadge }}">{{ ucfirst($app->status ?? 'pending') }}</span></td>
                  <td class="td-mono">{{ $app->created_at->format('d M Y') }}</td>
                  <td>
                    <div class="act-btns">
                      <a href="{{ route('admin.job_post_applications.show', $app->id) }}" class="act-btn ab-view">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Review</span>
                      </a>
                      @if($app->cv_path)
                      <a href="{{ route('admin.job_post_applications.downloadCv', $app) }}" class="act-btn ab-download" target="_blank">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>CV</span>
                      </a>
                      @endif
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @if($appCount > 10)
          <div style="padding:14px 22px;border-top:1px solid var(--border);text-align:center;">
            <a href="{{ route('admin.job_post_applications.index', ['job_id' => $jobPost->id]) }}" style="font-size:12.5px;color:var(--a);font-weight:600;">
              View all {{ $appCount }} applicants →
            </a>
          </div>
          @endif
          @else
          <div class="empty-state">
            <div class="empty-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
            </div>
            <div class="empty-ttl">No applications yet</div>
            <div class="empty-sub">Applications will appear here once candidates apply.</div>
          </div>
          @endif
        </div>

      </div>{{-- /left --}}

      {{-- RIGHT --}}
      <div class="side-stack">

        {{-- QUICK ACTIONS --}}
        <div class="side-card" style="animation-delay:.10s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico ci-purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
              </div>
              <div class="card-title">Quick Actions</div>
            </div>
          </div>
          <div class="btn-stack">
            <a href="{{ route('admin.job_posts.edit', $jobPost->id) }}" class="btn btn-primary">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit This Post
            </a>
            <a href="{{ route('admin.job_post_applications.index', ['job_id' => $jobPost->id]) }}" class="btn btn-secondary">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
              View All Applicants
              @if($appCount)<span class="s-chip sc-amber" style="margin-left:auto;">{{ $appCount }}</span>@endif
            </a>
            <a href="{{ route('admin.job_posts.create') }}" class="btn btn-secondary">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
              Post Another Job
            </a>
          </div>
        </div>

        {{-- POST DETAILS --}}
        <div class="side-card" style="animation-delay:.14s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico ci-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <div class="card-title">Post Details</div>
            </div>
          </div>
          <div class="info-list">
            <div class="info-row">
              <span class="info-lbl">Status</span>
              <span class="badge {{ $statusKey === 'active' ? 'b-active' : ($statusKey === 'draft' ? 'b-draft' : 'b-closed') }}">{{ ucfirst($jobPost->status ?? 'draft') }}</span>
            </div>
            <div class="info-row">
              <span class="info-lbl">Post ID</span>
              <span class="info-val">#{{ $jobPost->id }}</span>
            </div>
            <div class="info-row">
              <span class="info-lbl">Type</span>
              <span class="info-val">{{ $jobPost->type ? ucfirst($jobPost->type) : '—' }}</span>
            </div>
            <div class="info-row">
              <span class="info-lbl">Location</span>
              <span class="info-val">{{ $jobPost->location ?: '—' }}</span>
            </div>
            <div class="info-row">
              <span class="info-lbl">Salary</span>
              <span class="info-val green">{{ $jobPost->salary ?: 'Undisclosed' }}</span>
            </div>
            <div class="info-row">
              <span class="info-lbl">Experience</span>
              <span class="info-val">{{ $jobPost->experience_required ?: 'Any' }}</span>
            </div>
            <div class="info-row">
              <span class="info-lbl">Vacancies</span>
              <span class="info-val amber">{{ $jobPost->vacancies ?: '—' }}</span>
            </div>
            @if($jobPost->department)
            <div class="info-row">
              <span class="info-lbl">Department</span>
              <span class="info-val">{{ $jobPost->department }}</span>
            </div>
            @endif
            <div class="info-row">
              <span class="info-lbl">Remote</span>
              <span class="info-val {{ $jobPost->is_remote ? 'green' : '' }}">{{ $jobPost->is_remote ? 'Yes' : 'No' }}</span>
            </div>
            <div class="info-row">
              <span class="info-lbl">Featured</span>
              <span class="info-val {{ $jobPost->featured ? 'amber' : '' }}">{{ $jobPost->featured ? '★ Yes' : 'No' }}</span>
            </div>
            @if($jobPost->application_deadline)
            <div class="info-row">
              <span class="info-lbl">Deadline</span>
              <span class="info-val {{ $isExpired ? 'red' : 'amber' }}">
                {{ \Carbon\Carbon::parse($jobPost->application_deadline)->format('d M Y') }}
                {{ $isExpired ? '· Expired' : '' }}
              </span>
            </div>
            @endif
            <div class="info-row">
              <span class="info-lbl">Posted</span>
              <span class="info-val">{{ $jobPost->created_at->format('d M Y') }}</span>
            </div>
            <div class="info-row">
              <span class="info-lbl">Updated</span>
              <span class="info-val">{{ $jobPost->updated_at->diffForHumans() }}</span>
            </div>
          </div>
        </div>

        {{-- DANGER ZONE --}}
        <div class="danger-zone">
          <div class="danger-hdr">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>Danger Zone</span>
          </div>
          <div class="danger-desc">Permanently delete this job post and all {{ $appCount }} linked application(s). Cannot be undone.</div>
          <button type="button" onclick="openDelete()" class="btn btn-danger" style="width:100%;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete Job Post
          </button>
        </div>

      </div>{{-- /side-stack --}}
    </div>{{-- /content-grid --}}

  </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

<script>
(function(){
'use strict';

/* ── Theme ── */
var html = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved = localStorage.getItem('adminTheme') || 'light';
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

/* ── Avatar dropdown ── */
window.toggleDD = function(){ document.getElementById('avDD').classList.toggle('open'); };
document.addEventListener('click', function(e){
  var w = document.getElementById('avWrap');
  if (w && !w.contains(e.target)) document.getElementById('avDD').classList.remove('open');
});

/* ── Delete modal ── */
window.openDelete  = function(){ document.getElementById('deleteOverlay').classList.add('open'); };
window.closeDelete = function(){ document.getElementById('deleteOverlay').classList.remove('open'); };
document.getElementById('deleteOverlay').addEventListener('click', function(e){ if(e.target === this) closeDelete(); });
document.addEventListener('keydown', function(e){ if(e.key === 'Escape') closeDelete(); });

/* ── Toast ── */
function toast(msg, type){
  var t = document.createElement('div');
  t.className = 'toast toast-' + (type === 'success' ? 'ok' : 'err');
  var ok  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
  var err = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
  t.innerHTML = (type === 'success' ? ok : err) + '<span>' + msg + '</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
  document.getElementById('toastWrap').appendChild(t);
  setTimeout(function(){
    t.style.cssText = 'opacity:0;transform:translateX(20px);transition:opacity .3s,transform .3s;';
    setTimeout(function(){ t.remove(); }, 300);
  }, 4200);
}
@if(session('success')) setTimeout(function(){ toast(@json(session('success')),'success'); }, 200); @endif
@if(session('error'))   setTimeout(function(){ toast(@json(session('error')),'error'); }, 200);   @endif

})();
</script>
</body>
</html>