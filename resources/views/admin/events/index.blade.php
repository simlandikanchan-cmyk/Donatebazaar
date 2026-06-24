{{-- resources/views/admin/events/index.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Events Management — DonateBazaar Admin</title>
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
.sc-blue{background:var(--blue-lt);color:var(--blue);}
.s-divider{height:1px;background:var(--sb-border);margin:10px 18px;}
.s-bottom{margin-top:auto;padding:10px 10px 20px;border-top:1px solid var(--sb-border);}

/* ── MAIN ── */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* ── TOPBAR ── */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:62px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:200;gap:14px;}
.tb-left h1{font-family:var(--mono);font-size:17px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.tb-left p{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.tb-right{display:flex;align-items:center;gap:8px;}
.search-wrap{position:relative;}
.search-wrap .s-icon-inp{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;}
.search-inp{width:220px;height:36px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:0 12px 0 33px;font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease),width var(--ease);}
.search-inp::placeholder{color:var(--text3);}
.search-inp:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);width:260px;}
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

/* ── PAGE BODY ── */
.body{padding:26px 28px 56px;flex:1;}

/* ── HERO ── */
.hero{border-radius:22px;padding:28px 32px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:20px;position:relative;overflow:hidden;animation:fadeUp .4s ease both;background:#07080f;}
.hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% -10%,rgba(110,86,247,.55) 0%,transparent 60%),radial-gradient(ellipse 50% 60% at 15% 110%,rgba(155,109,255,.35) 0%,transparent 55%);}
.hero::after{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);background-size:32px 32px;}
.hero-left{position:relative;z-index:2;}
.hero-tag{display:inline-flex;align-items:center;gap:6px;font-size:10px;font-weight:600;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.14em;font-family:var(--mono);margin-bottom:10px;}
.hero-tag-dot{width:6px;height:6px;border-radius:50%;background:var(--green);animation:pulse 2s ease infinite;}
@keyframes pulse{0%,100%{box-shadow:0 0 0 0 rgba(5,196,138,.5)}50%{box-shadow:0 0 0 6px rgba(5,196,138,0)}}
.hero-name{font-family:var(--mono);font-size:26px;font-weight:800;color:#fff;letter-spacing:-.03em;line-height:1.1;background:linear-gradient(135deg,#fff 30%,rgba(184,169,255,.85));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.hero-sub{font-size:13.5px;color:rgba(255,255,255,.52);margin-top:6px;max-width:420px;line-height:1.6;}
.hero-badges{display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;}
.hero-badge{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);}
.hb-green{background:rgba(5,196,138,.2);color:#6ee7b7;border:1px solid rgba(5,196,138,.3);}
.hb-amber{background:rgba(245,158,11,.2);color:#fde68a;border:1px solid rgba(245,158,11,.3);}
.hb-purple{background:rgba(110,86,247,.2);color:#c4b5fd;border:1px solid rgba(110,86,247,.3);}
.hb-red{background:rgba(240,68,68,.2);color:#fca5a5;border:1px solid rgba(240,68,68,.3);}
.hb-blue{background:rgba(59,130,246,.2);color:#93c5fd;border:1px solid rgba(59,130,246,.3);}
.hero-right{position:relative;z-index:2;display:flex;gap:10px;flex-wrap:wrap;}
.hero-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:var(--r-sm);font-size:13px;font-weight:600;text-decoration:none;font-family:var(--font);transition:all var(--ease);cursor:pointer;border:none;}
.hero-btn svg{width:14px;height:14px;}
.hero-btn-primary{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 20px rgba(110,86,247,.45);}
.hero-btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(110,86,247,.55);}
.hero-btn-ghost{background:rgba(255,255,255,.1);color:rgba(255,255,255,.85);border:1px solid rgba(255,255,255,.15);}
.hero-btn-ghost:hover{background:rgba(255,255,255,.18);transform:translateY(-2px);}

/* ── STATS ── */
.stats-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px;}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:20px 22px;box-shadow:var(--sh);display:flex;align-items:flex-start;gap:15px;transition:transform var(--ease),box-shadow var(--ease);animation:fadeUp .4s ease both;cursor:default;position:relative;overflow:hidden;}
.stat:hover{transform:translateY(-3px);box-shadow:var(--sh-md);}
.stat::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2.5px;border-radius:0 0 var(--r) var(--r);opacity:0;transition:opacity var(--ease);}
.stat:hover::after{opacity:1;}
.stat:nth-child(1){animation-delay:.05s;}.stat:nth-child(1)::after{background:linear-gradient(90deg,var(--blue),#6366f1);}
.stat:nth-child(2){animation-delay:.10s;}.stat:nth-child(2)::after{background:linear-gradient(90deg,var(--amber),#f97316);}
.stat:nth-child(3){animation-delay:.15s;}.stat:nth-child(3)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat:nth-child(4){animation-delay:.20s;}.stat:nth-child(4)::after{background:linear-gradient(90deg,var(--red),#f87171);}
.stat:nth-child(5){animation-delay:.25s;}.stat:nth-child(5)::after{background:linear-gradient(90deg,var(--a),var(--a2));}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:19px;height:19px;}
.si-blue{background:var(--blue-lt);color:var(--blue);}
.si-amber{background:var(--amber-lt);color:var(--amber);}
.si-green{background:var(--green-lt);color:var(--green);}
.si-red{background:var(--red-lt);color:var(--red);}
.si-purple{background:var(--a-lt);color:var(--a);}
.stat-body{flex:1;min-width:0;}
.stat-lbl{font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:6px;}
.stat-val{font-family:var(--mono);font-size:2rem;font-weight:800;line-height:1;letter-spacing:-.03em;}
.sv-blue{color:var(--blue);}
.sv-amber{color:var(--amber);}
.sv-green{color:var(--green);}
.sv-red{color:var(--red);}
.sv-purple{color:var(--a);}
.stat-foot{font-size:11px;color:var(--text3);margin-top:5px;}

/* ── SECTION HEADER ── */
.sec-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:12px;}
.sec-ttl{font-family:var(--mono);font-size:18px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.sec-right{display:flex;align-items:center;gap:8px;}

/* ── FILTER BAR ── */
.filter-bar{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:16px 20px;box-shadow:var(--sh);margin-bottom:20px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;animation:fadeUp .4s .1s ease both;}
.filter-inp,.filter-sel{height:36px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:0 12px;font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.filter-inp{width:200px;}
.filter-inp:focus,.filter-sel:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.filter-inp::placeholder{color:var(--text3);}
.filter-sel{cursor:pointer;min-width:130px;}
.filter-date{width:150px;}
.filter-btn{height:36px;padding:0 18px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;font-family:var(--font);cursor:pointer;transition:opacity var(--ease),transform var(--ease);box-shadow:0 3px 10px rgba(110,86,247,.3);}
.filter-btn:hover{opacity:.88;transform:translateY(-1px);}
.filter-clear{height:36px;padding:0 14px;background:transparent;border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;color:var(--text3);font-family:var(--font);cursor:pointer;transition:all var(--ease);text-decoration:none;display:inline-flex;align-items:center;gap:5px;}
.filter-clear:hover{border-color:var(--red);color:var(--red);}

/* ── TABLE CARD ── */
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .18s ease both;}
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead{background:var(--surface2);border-bottom:1px solid var(--border);}
thead th{padding:12px 16px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--text3);font-family:var(--mono);white-space:nowrap;}
tbody td{padding:14px 16px;border-bottom:1px solid var(--border);vertical-align:middle;}
tbody tr:last-child td{border-bottom:none;}
tbody tr{transition:background var(--ease);}
tbody tr:hover{background:var(--surface2);}

/* ── TABLE CELLS ── */
.cell-id{font-family:var(--mono);font-size:11px;color:var(--text3);font-weight:500;}
.event-title{font-size:13.5px;font-weight:600;color:var(--text);line-height:1.2;}
.event-goal{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--mono);}
.campaign-name{font-size:13px;font-weight:500;color:var(--text2);}
.organizer-name{font-size:13px;font-weight:500;color:var(--text2);}
.cell-date{font-family:var(--mono);font-size:11.5px;color:var(--text3);}
.participants-val{font-family:var(--mono);font-size:12px;font-weight:600;color:var(--text2);}
.participants-total{color:var(--text3);font-weight:400;}

/* ── STATUS BADGES ── */
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3.5px 9px;border-radius:7px;text-transform:uppercase;letter-spacing:.07em;font-family:var(--mono);}
.b-active{background:rgba(5,196,138,.15);color:#059669;border:1px solid rgba(5,196,138,.25);}
.b-pending{background:rgba(245,158,11,.15);color:#b45309;border:1px solid rgba(245,158,11,.25);}
.b-completed{background:rgba(59,130,246,.15);color:#1d4ed8;border:1px solid rgba(59,130,246,.25);}
.b-cancelled{background:rgba(240,68,68,.15);color:var(--red);border:1px solid rgba(240,68,68,.25);}
.b-expired{background:rgba(107,114,128,.15);color:#374151;border:1px solid rgba(107,114,128,.25);}
[data-theme="dark"] .b-active{color:#34d399;}
[data-theme="dark"] .b-pending{color:#fde68a;}
[data-theme="dark"] .b-completed{color:#93c5fd;}
[data-theme="dark"] .b-cancelled{color:#f87171;}
[data-theme="dark"] .b-expired{color:#9ca3af;}

/* ── STATUS DOT ── */
.status-dot{width:6px;height:6px;border-radius:50%;flex-shrink:0;}
.dot-active{background:var(--green);}
.dot-pending{background:var(--amber);}
.dot-completed{background:var(--blue);}
.dot-cancelled{background:var(--red);}
.dot-expired{background:var(--gray);}

/* ── ACTION BUTTONS ── */
.act-wrap{display:flex;gap:6px;align-items:center;}
.act-link{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:500;color:var(--text2);background:var(--surface2);border:1px solid var(--border2);transition:all var(--ease);text-decoration:none;}
.act-link:hover{background:var(--surface3);color:var(--text);transform:translateY(-1px);}
.act-link svg{width:11px;height:11px;}
.act-edit{color:var(--a);background:var(--a-lt);border-color:rgba(110,86,247,.2);}
.act-edit:hover{background:var(--a);color:#fff;border-color:var(--a);}

/* ── EMPTY STATE ── */
.empty-row td{text-align:center;padding:56px 20px;}
.empty-inner{display:flex;flex-direction:column;align-items:center;gap:10px;}
.empty-inner svg{width:48px;height:48px;color:var(--text3);opacity:.25;}
.empty-inner strong{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2);}
.empty-inner span{font-size:13px;color:var(--text3);}

/* ── PAGINATION ── */
.pagination-wrap{margin-top:20px;}

/* ── FLASH ── */
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-success{background:rgba(5,196,138,.1);border:1px solid rgba(5,196,138,.25);color:#059669;}
.flash-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);}

/* ── UTILS ── */
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}.search-wrap{display:none}.stats-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.topbar{padding:0 16px}.body{padding:14px 14px 48px}.stats-grid{grid-template-columns:1fr 1fr}.filter-bar{flex-direction:column;align-items:stretch}.filter-inp,.filter-sel,.filter-date{width:100%}}
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
    <a href="{{ route('admin.events.index') }}" class="s-link active">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      Events
      <span class="s-chip sc-blue">{{ \App\Models\Event::count() }}</span>
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
        <h1>Events Management</h1>
        <p>{{ now()->format('l, d F Y') }}</p>
      </div>
    </div>
    <div class="tb-right">
      <div class="search-wrap">
        <svg class="s-icon-inp" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input class="search-inp" type="text" placeholder="Search events…" autocomplete="off" id="liveSearch">
      </div>
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

    {{-- ── HERO ── --}}
    <div class="hero">
      <div class="hero-left">
        <div class="hero-tag"><span class="hero-tag-dot"></span>Campaigns</div>
        <div class="hero-name">Events Management</div>
        <div class="hero-sub">Manage all campaign events — monitor status, participants, and goals across every organizer.</div>
        <div class="hero-badges">
          <span class="hero-badge hb-purple">{{ $events->total() }} total</span>
          @if(isset($stats['active']) && $stats['active'] > 0)
            <span class="hero-badge hb-green">● {{ $stats['active'] }} active</span>
          @endif
          @if(isset($stats['pending']) && $stats['pending'] > 0)
            <span class="hero-badge hb-amber">⏱ {{ $stats['pending'] }} pending</span>
          @endif
          @if(isset($stats['completed']) && $stats['completed'] > 0)
            <span class="hero-badge hb-blue">✓ {{ $stats['completed'] }} completed</span>
          @endif
          @if(isset($stats['cancelled']) && $stats['cancelled'] > 0)
            <span class="hero-badge hb-red">✕ {{ $stats['cancelled'] }} cancelled</span>
          @endif
        </div>
      </div>
      <div class="hero-right">
        <a href="{{ url('/admin/events/create') }}" class="hero-btn hero-btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          New Event
        </a>
        <a href="{{ url('/admin/campaigns') }}" class="hero-btn hero-btn-ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          All Campaigns
        </a>
      </div>
    </div>

    {{-- ── STATS ── --}}
    <div class="stats-grid">
      <div class="stat">
        <div class="stat-icon si-blue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Total</div>
          <div class="stat-val sv-blue">{{ $stats['total'] ?? $events->total() }}</div>
          <div class="stat-foot">All events</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-green">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Active</div>
          <div class="stat-val sv-green">{{ $stats['active'] ?? 0 }}</div>
          <div class="stat-foot">Live now</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-amber">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Pending</div>
          <div class="stat-val sv-amber">{{ $stats['pending'] ?? 0 }}</div>
          <div class="stat-foot">Awaiting start</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-purple">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Completed</div>
          <div class="stat-val sv-purple">{{ $stats['completed'] ?? 0 }}</div>
          <div class="stat-foot">Finished</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-red">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Cancelled</div>
          <div class="stat-val sv-red">{{ $stats['cancelled'] ?? 0 }}</div>
          <div class="stat-foot">Not proceeding</div>
        </div>
      </div>
    </div>

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

    {{-- ── FILTER BAR ── --}}
    <form method="GET" action="{{ route('admin.events.index') }}" class="filter-bar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;color:var(--text3);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
      <input class="filter-inp" type="text" name="search" placeholder="Search events…" value="{{ request('search') }}">
      <select class="filter-sel" name="status">
        <option value="">All statuses</option>
        @foreach(['pending','active','completed','cancelled','expired'] as $status)
          <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
      </select>
      <input class="filter-inp filter-date" type="date" name="date" value="{{ request('date') }}">
      <button type="submit" class="filter-btn">Apply Filters</button>
      @if(request('search') || request('status') || request('date'))
        <a href="{{ route('admin.events.index') }}" class="filter-clear">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          Clear
        </a>
      @endif
    </form>

    {{-- ── TABLE ── --}}
    <div class="sec-hdr">
      <div class="sec-ttl">Events</div>
      <div class="sec-right" style="font-size:12px;color:var(--text3);font-family:var(--mono);">
        {{ $events->total() }} result{{ $events->total() !== 1 ? 's' : '' }}
      </div>
    </div>

    <div class="table-card">
      <div class="table-wrap">
        <table id="eventsTable">
          <thead>
            <tr>
              <th>Sl No</th>
              <th>Event</th>
              <th>Campaign</th>
              <th>Organizer</th>
              <th>Date</th>
              <th>Status</th>
              <th>Participants</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($events as $event)
            <tr data-name="{{ strtolower($event->title) }}">
              <td class="cell-id">{{ $event->id }}</td>
              <td>
                <div class="event-title">{{ $event->title }}</div>
                <div class="event-goal">Goal: ₹{{ number_format($event->goal_amount, 2) }}</div>
              </td>
              <td>
                <div class="campaign-name">{{ $event->campaign->title ?? '—' }}</div>
              </td>
              <td>
                <div class="organizer-name">{{ $event->user->name ?? '—' }}</div>
              </td>
              <td class="cell-date">{{ $event->event_date?->format('d M Y') ?? '—' }}</td>
              <td>
                @php
                  $badgeClass = match($event->status) {
                    'active'    => 'b-active',
                    'completed' => 'b-completed',
                    'cancelled' => 'b-cancelled',
                    'expired'   => 'b-expired',
                    default     => 'b-pending',
                  };
                  $dotClass = match($event->status) {
                    'active'    => 'dot-active',
                    'completed' => 'dot-completed',
                    'cancelled' => 'dot-cancelled',
                    'expired'   => 'dot-expired',
                    default     => 'dot-pending',
                  };
                @endphp
                <span class="badge {{ $badgeClass }}">
                  <span class="status-dot {{ $dotClass }}"></span>
                  {{ ucfirst($event->status) }}
                </span>
              </td>
              <td>
                <span class="participants-val">
                  {{ $event->registered_count }}<span class="participants-total"> / {{ $event->max_participants ?: '∞' }}</span>
                </span>
              </td>
              <td>
                <div class="act-wrap">
                  <a href="{{ route('admin.events.show', $event->id) }}" class="act-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View
                  </a>
                  <a href="{{ route('admin.events.edit', $event->id) }}" class="act-link act-edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                  </a>
                </div>
              </td>
            </tr>
            @empty
            <tr class="empty-row">
              <td colspan="8">
                <div class="empty-inner">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  <strong>No events found</strong>
                  <span>No events match your current filters.</span>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="pagination-wrap">{{ $events->links() }}</div>

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
document.getElementById('hamburger').addEventListener('click', function(){
  document.getElementById('sidebar').classList.toggle('open');
});
document.addEventListener('click', function(e){
  var sb = document.getElementById('sidebar');
  if (window.innerWidth <= 860 && !sb.contains(e.target) && !document.getElementById('hamburger').contains(e.target))
    sb.classList.remove('open');
});

/* ── Avatar dropdown ── */
window.toggleDD = function(){ document.getElementById('avDD').classList.toggle('open'); };
document.addEventListener('click', function(e){
  var w = document.getElementById('avWrap');
  if (w && !w.contains(e.target)) document.getElementById('avDD').classList.remove('open');
});

/* ── Live search ── */
var searchEl = document.getElementById('liveSearch');
if (searchEl) {
  var st;
  searchEl.addEventListener('input', function(){
    clearTimeout(st);
    var q = this.value.toLowerCase().trim();
    st = setTimeout(function(){
      document.querySelectorAll('#eventsTable tbody tr[data-name]').forEach(function(row){
        row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
      });
    }, 160);
  });
}

})();
</script>
</body>
</html>