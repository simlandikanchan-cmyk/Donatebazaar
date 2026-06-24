<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Admin — DonateBazaar</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
:root{
  --bg:#f4f5fb;--surface:#fff;--surface2:#f8f9fe;--surface3:#eef0fa;
  --border:rgba(0,0,0,.06);--border2:rgba(0,0,0,.10);
  --text:#0a0b14;--text2:#454863;--text3:#9096b4;
  /* ── LIGHT SIDEBAR ── */
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
}
[data-theme="dark"]{
  --bg:#070810;--surface:#0f1020;--surface2:#161728;--surface3:#1d1f35;
  --border:rgba(255,255,255,.055);--border2:rgba(255,255,255,.09);
  --text:#eef0ff;--text2:#9ba3c8;--text3:#4c5272;
  /* dark sidebar stays dark */
  --sb-bg:#050609;--sb-txt:rgba(255,255,255,.48);--sb-act:rgba(110,86,247,.22);
  --sb-border:rgba(255,255,255,.03);
  --a-glow:rgba(110,86,247,.30);
  --sh:0 1px 3px rgba(0,0,0,.35),0 4px 24px rgba(0,0,0,.25);
  --sh-md:0 4px 20px rgba(0,0,0,.4),0 1px 4px rgba(0,0,0,.25);
  --sh-lg:0 12px 48px rgba(0,0,0,.6);
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;}
body{font-family:var(--font);background:var(--bg);color:var(--text);line-height:1.55;-webkit-font-smoothing:antialiased;overflow-x:hidden;transition:background .2s,color .2s;}
a{text-decoration:none;color:inherit;}

/* LAYOUT */
.shell{display:flex;min-height:100vh;}

/* SIDEBAR */
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
  color:var(--sb-txt);font-size:13px;font-weight:500;text-decoration:none;
  transition:background var(--ease),color var(--ease);
  margin-bottom:1px;border:none;background:transparent;
  width:100%;text-align:left;cursor:pointer;position:relative;font-family:var(--font);
}
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

/* MAIN */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* TOPBAR */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:62px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:200;gap:14px;}
.tb-left h1{font-family:var(--mono);font-size:17px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.tb-left p{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.tb-right{display:flex;align-items:center;gap:8px;}

.search-wrap{position:relative;}
.search-wrap .s-icon-inp{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;}
.search-inp{width:220px;height:36px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:0 12px 0 33px;font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease),width var(--ease);}
.search-inp::placeholder{color:var(--text3);}
.search-inp:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);width:260px;}

.sort-sel{height:36px;padding:0 10px;border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);background:var(--surface2);outline:none;cursor:pointer;transition:border-color var(--ease);}
.sort-sel:focus{border-color:var(--a);}

.tb-btn{width:36px;height:36px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text2);flex-shrink:0;transition:all var(--ease);position:relative;}
.tb-btn:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);}
.tb-btn svg{width:15px;height:15px;}
.notif-dot{width:6px;height:6px;border-radius:50%;background:var(--red);position:absolute;top:7px;right:7px;border:1.5px solid var(--surface);}

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

/* PAGE BODY */
.body{padding:26px 28px 56px;flex:1;}

/* HERO */
.hero{border-radius:22px;padding:28px 32px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:20px;position:relative;overflow:hidden;animation:fadeUp .4s ease both;background:#07080f;}
.hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% -10%,rgba(110,86,247,.55) 0%,transparent 60%),radial-gradient(ellipse 50% 60% at 15% 110%,rgba(155,109,255,.35) 0%,transparent 55%),radial-gradient(ellipse 40% 50% at 50% 50%,rgba(59,130,246,.12) 0%,transparent 60%);}
.hero::after{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);background-size:32px 32px;}
.hero-left{position:relative;z-index:2;}
.hero-tag{display:inline-flex;align-items:center;gap:6px;font-size:10px;font-weight:600;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.14em;font-family:var(--mono);margin-bottom:10px;}
.hero-tag-dot{width:6px;height:6px;border-radius:50%;background:var(--green);animation:pulse 2s ease infinite;}
@keyframes pulse{0%,100%{box-shadow:0 0 0 0 rgba(5,196,138,.5)}50%{box-shadow:0 0 0 6px rgba(5,196,138,0)}}
.hero-name{font-family:var(--mono);font-size:28px;font-weight:800;color:#fff;letter-spacing:-.03em;line-height:1.1;background:linear-gradient(135deg,#fff 30%,rgba(184,169,255,.85));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.hero-sub{font-size:13.5px;color:rgba(255,255,255,.52);margin-top:6px;max-width:380px;line-height:1.6;}
.hero-badges{display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;}
.hero-badge{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);}
.hb-amber{background:rgba(245,158,11,.2);color:#fde68a;border:1px solid rgba(245,158,11,.3);}
.hb-green{background:rgba(5,196,138,.2);color:#6ee7b7;border:1px solid rgba(5,196,138,.3);}
.hb-purple{background:rgba(110,86,247,.2);color:#c4b5fd;border:1px solid rgba(110,86,247,.3);}
.hb-teal{background:rgba(5,196,138,.2);color:#6ee7b7;border:1px solid rgba(5,196,138,.3);}
.hero-right{position:relative;z-index:2;display:flex;gap:10px;flex-wrap:wrap;}
.hero-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:var(--r-sm);font-size:13px;font-weight:600;text-decoration:none;font-family:var(--font);transition:all var(--ease);cursor:pointer;border:none;}
.hero-btn-primary{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 20px rgba(110,86,247,.45);}
.hero-btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(110,86,247,.55);}
.hero-btn-ghost{background:rgba(255,255,255,.1);color:rgba(255,255,255,.85);border:1px solid rgba(255,255,255,.15);}
.hero-btn-ghost:hover{background:rgba(255,255,255,.18);transform:translateY(-2px);}
.hero-btn-teal{background:rgba(5,196,138,.2);color:#6ee7b7;border:1px solid rgba(5,196,138,.3);}
.hero-btn-teal:hover{background:rgba(5,196,138,.35);transform:translateY(-2px);}
.hero-btn svg{width:14px;height:14px;}

/* STATS */
.stats-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px;}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:20px 22px;box-shadow:var(--sh);display:flex;align-items:flex-start;gap:15px;transition:transform var(--ease),box-shadow var(--ease);animation:fadeUp .4s ease both;cursor:default;position:relative;overflow:hidden;}
.stat:hover{transform:translateY(-3px);box-shadow:var(--sh-md);}
.stat::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2.5px;border-radius:0 0 var(--r) var(--r);opacity:0;transition:opacity var(--ease);}
.stat:hover::after{opacity:1;}
.stat:nth-child(1){animation-delay:.05s;}.stat:nth-child(1)::after{background:linear-gradient(90deg,var(--a),var(--a2));}
.stat:nth-child(2){animation-delay:.10s;}.stat:nth-child(2)::after{background:linear-gradient(90deg,var(--amber),#f97316);}
.stat:nth-child(3){animation-delay:.15s;}.stat:nth-child(3)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat:nth-child(4){animation-delay:.20s;}.stat:nth-child(4)::after{background:linear-gradient(90deg,var(--blue),#6366f1);}
.stat:nth-child(5){animation-delay:.25s;}.stat:nth-child(5)::after{background:linear-gradient(90deg,var(--green),#10b981);}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:19px;height:19px;}
.si-a{background:var(--a-lt);color:var(--a);}
.si-amber{background:var(--amber-lt);color:var(--amber);}
.si-green{background:var(--green-lt);color:var(--green);}
.si-blue{background:var(--blue-lt);color:var(--blue);}
.si-teal{background:rgba(5,196,138,.12);color:#05c48a;}
.stat-body{flex:1;min-width:0;}
.stat-lbl{font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:6px;}
.stat-val{font-family:var(--mono);font-size:2rem;font-weight:800;line-height:1;letter-spacing:-.03em;}
.sv-a{color:var(--a);}.sv-amber{color:var(--amber);}.sv-green{color:var(--green);}.sv-blue{color:var(--blue);}.sv-teal{color:#05c48a;}
.stat-foot{font-size:11px;color:var(--text3);margin-top:5px;}
.stat-foot a{color:#05c48a;font-weight:600;}.stat-foot a:hover{text-decoration:underline;}

/* ANALYTICS */
.analytics-row{display:grid;grid-template-columns:1fr 290px;gap:14px;margin-bottom:24px;}
.chart-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:24px;box-shadow:var(--sh);animation:fadeUp .4s .22s ease both;}
.chart-hdr{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:22px;gap:12px;}
.chart-ttl{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.chart-sub{font-size:11px;color:var(--text3);margin-top:3px;font-family:var(--mono);}
.chart-legend{display:flex;align-items:center;gap:14px;}
.leg-item{display:flex;align-items:center;gap:5px;font-size:11px;color:var(--text3);font-family:var(--mono);}
.leg-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.chart-wrap{position:relative;height:190px;}
.status-panel{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:24px;box-shadow:var(--sh);display:flex;flex-direction:column;gap:6px;animation:fadeUp .4s .28s ease both;}
.sp-ttl{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text);margin-bottom:14px;letter-spacing:-.01em;}
.sp-row{display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border-radius:var(--r-sm);background:var(--surface2);border:1px solid var(--border);transition:background var(--ease),transform var(--ease);cursor:pointer;}
.sp-row:hover{background:var(--surface3);transform:translateX(4px);}
.sp-left{display:flex;align-items:center;gap:9px;}
.sp-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.sp-label{font-size:12.5px;font-weight:500;color:var(--text2);}
.sp-val{font-size:14px;font-weight:700;color:var(--text);font-family:var(--mono);}
.sp-prog{margin-top:14px;padding-top:14px;border-top:1px solid var(--border);}
.sp-prog-lbl{display:flex;justify-content:space-between;font-size:11px;margin-bottom:8px;}
.sp-prog-lbl span:first-child{color:var(--text3);}
.sp-prog-lbl span:last-child{font-weight:700;color:var(--a);font-family:var(--mono);}
.sp-bar{height:6px;background:var(--surface3);border-radius:100px;overflow:hidden;}
.sp-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--a),var(--a2));transition:width 1.2s cubic-bezier(.4,0,.2,1);}

/* QUICK NAV */
.qnav{display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:12px;margin-bottom:24px;}
.qnav-card{display:flex;flex-direction:column;align-items:center;gap:10px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:18px 10px;text-align:center;transition:transform var(--ease),box-shadow var(--ease),border-color var(--ease);box-shadow:var(--sh);animation:fadeUp .4s ease both;cursor:pointer;text-decoration:none;position:relative;overflow:hidden;}
.qnav-card::before{content:'';position:absolute;inset:0;opacity:0;background:radial-gradient(ellipse at 50% 0%,var(--qc,rgba(110,86,247,.08)) 0%,transparent 70%);transition:opacity var(--ease);}
.qnav-card:hover{transform:translateY(-4px);box-shadow:var(--sh-md);border-color:rgba(110,86,247,.2);}
.qnav-card:hover::before{opacity:1;}
.qnav-ico{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;transition:transform var(--ease);}
.qnav-card:hover .qnav-ico{transform:scale(1.12);}
.qnav-ico svg{width:18px;height:18px;}
.qnav-lbl{font-size:12px;font-weight:600;color:var(--text);}
.qnav-sub{font-size:10px;color:var(--text3);}

/* SECTION HEADER */
.sec-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:12px;}
.sec-ttl{font-family:var(--mono);font-size:18px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.sec-right{display:flex;align-items:center;gap:8px;}
.ftabs{display:flex;gap:2px;background:var(--surface2);border:1px solid var(--border);padding:4px;border-radius:14px;flex-wrap:wrap;}
.ftab{padding:5px 13px;border-radius:10px;font-size:12px;font-weight:500;cursor:pointer;border:none;background:transparent;color:var(--text3);transition:all var(--ease);display:inline-flex;align-items:center;gap:5px;font-family:var(--font);white-space:nowrap;}
.ftab:hover{color:var(--a);}
.ftab.on{background:var(--surface);color:var(--a);font-weight:700;box-shadow:0 1px 8px rgba(110,86,247,.14);}
.ftab .cnt{display:inline-flex;align-items:center;justify-content:center;min-width:17px;height:17px;border-radius:100px;font-size:10px;padding:0 4px;background:var(--a-lt);color:var(--a);font-weight:700;font-family:var(--mono);}
.ftab.on .cnt{background:var(--a);color:#fff;}

/* BADGES */
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3.5px 9px;border-radius:7px;text-transform:uppercase;letter-spacing:.07em;font-family:var(--mono);}
.b-active{background:rgba(5,196,138,.85);color:#fff;}
.b-pending{background:rgba(245,158,11,.85);color:#fff;}
.b-rejected{background:rgba(240,68,68,.85);color:#fff;}
.b-paused{background:rgba(110,86,247,.85);color:#fff;}
.b-inactive{background:rgba(107,114,128,.75);color:#fff;}

/* CAMPAIGN CARDS */
.c-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;}
.c-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;display:flex;flex-direction:column;box-shadow:var(--sh);transition:transform var(--ease),box-shadow var(--ease),border-color var(--ease);animation:fadeUp .4s ease both;}
.c-card:hover{transform:translateY(-5px);box-shadow:var(--sh-md);border-color:rgba(110,86,247,.2);}
.c-thumb{position:relative;flex-shrink:0;}
.c-thumb img{width:100%;height:168px;object-fit:cover;display:block;}
.c-placeholder{width:100%;height:168px;display:flex;align-items:center;justify-content:center;background:var(--surface2);}
.c-placeholder svg{width:30px;height:30px;color:var(--text3);opacity:.3;}
.c-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.48) 0%,transparent 52%);}
.c-badge-pos{position:absolute;top:11px;left:11px;}
.c-user{display:flex;align-items:center;gap:9px;padding:9px 14px;background:var(--surface2);border-bottom:1px solid var(--border);}
.c-uav{width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:11px;font-weight:700;font-family:var(--mono);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.c-uname{font-size:12px;font-weight:600;color:var(--text);line-height:1.2;}
.c-uemail{font-size:10px;color:var(--text3);font-family:var(--mono);}
.c-body{padding:15px 16px 16px;flex:1;display:flex;flex-direction:column;}
.c-title{font-size:14px;font-weight:600;color:var(--text);margin-bottom:12px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.45;}

.reason{padding:9px 11px;border-radius:9px;margin-bottom:12px;border:1px solid transparent;}
.reason-amber{background:var(--amber-lt);border-color:rgba(245,158,11,.22);}
.reason-red{background:var(--red-lt);border-color:rgba(240,68,68,.22);}
.reason-lbl{font-size:10px;font-weight:700;margin-bottom:3px;font-family:var(--mono);}
.reason-amber .reason-lbl{color:#b45309;}.reason-red .reason-lbl{color:#b91c1c;}
.reason-txt{font-size:11.5px;line-height:1.45;}
.reason-amber .reason-txt{color:#92400e;}.reason-red .reason-txt{color:#991b1b;}
[data-theme="dark"] .reason-amber .reason-lbl{color:#fbbf24;}[data-theme="dark"] .reason-amber .reason-txt{color:#fde68a;}
[data-theme="dark"] .reason-red .reason-lbl{color:#fca5a5;}[data-theme="dark"] .reason-red .reason-txt{color:#fecaca;}

.prog{margin-bottom:14px;}
.prog-nums{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:7px;}
.prog-raised{font-family:var(--mono);font-size:16px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.prog-goal{font-size:11px;color:var(--text3);font-family:var(--mono);}
.prog-bar{width:100%;background:var(--surface3);border-radius:100px;height:5px;overflow:hidden;margin-bottom:5px;}
.prog-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--a),var(--a2));transition:width .9s ease;}
.prog-fill-red{background:linear-gradient(90deg,#f87171,var(--red));}
.prog-fill-gray{background:linear-gradient(90deg,#94a3b8,#64748b);}
.prog-pct{font-size:10.5px;color:var(--a);font-weight:600;font-family:var(--mono);}

.c-actions{display:flex;gap:6px;margin-top:auto;}
.c-btn{flex:1;display:inline-flex;align-items:center;justify-content:center;gap:5px;padding:8px 10px;border-radius:var(--r-sm);font-size:12px;font-weight:600;cursor:pointer;border:1px solid transparent;transition:all var(--ease);text-decoration:none;font-family:var(--font);white-space:nowrap;}
.c-btn svg{width:12px;height:12px;}
.c-btn:active{transform:scale(.96);}
.c-btn-approve{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.2);}
.c-btn-approve:hover{background:var(--a);color:#fff;border-color:var(--a);transform:translateY(-1px);box-shadow:0 6px 18px rgba(110,86,247,.35);}
.c-btn-reject{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.2);}
.c-btn-reject:hover{background:var(--red);color:#fff;border-color:var(--red);transform:translateY(-1px);box-shadow:0 6px 18px rgba(240,68,68,.3);}
.c-btn-pause{background:var(--amber-lt);color:var(--amber);border-color:rgba(245,158,11,.2);}
.c-btn-pause:hover{background:var(--amber);color:#fff;border-color:var(--amber);transform:translateY(-1px);box-shadow:0 6px 18px rgba(245,158,11,.3);}
.c-btn-resume{background:var(--green-lt);color:var(--green);border-color:rgba(5,196,138,.2);}
.c-btn-resume:hover{background:var(--green);color:#fff;border-color:var(--green);transform:translateY(-1px);box-shadow:0 6px 18px rgba(5,196,138,.3);}
.c-btn-view{background:var(--surface2);color:var(--text2);border-color:var(--border2);}
.c-btn-view:hover{background:var(--surface3);color:var(--text);transform:translateY(-1px);}

/* NO RESULTS */
#noResults{display:none;text-align:center;padding:48px 20px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);animation:fadeUp .4s ease both;}
#noResults svg{width:48px;height:48px;margin:0 auto 14px;display:block;opacity:.22;}
#noResults strong{display:block;font-family:var(--mono);font-size:16px;font-weight:700;color:var(--text2);margin-bottom:5px;}
#noResults span{font-size:13px;color:var(--text3);}

/* MODALS */
.overlay{display:none;position:fixed;inset:0;z-index:9998;background:rgba(4,5,14,.65);backdrop-filter:blur(12px);align-items:center;justify-content:center;padding:20px;}
.overlay.open{display:flex;}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:22px;box-shadow:var(--sh-lg);width:100%;max-width:430px;padding:26px;position:relative;animation:modalIn .2s ease;}
@keyframes modalIn{from{opacity:0;transform:scale(.95) translateY(12px)}to{opacity:1;transform:none}}
.modal-x{position:absolute;top:16px;right:16px;width:28px;height:28px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);cursor:pointer;color:var(--text2);display:flex;align-items:center;justify-content:center;transition:all var(--ease);}
.modal-x:hover{background:var(--border2);transform:rotate(90deg);}
.modal-x svg{width:11px;height:11px;}
.modal-head{display:flex;align-items:center;gap:14px;margin-bottom:20px;}
.modal-ico{width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.modal-ico svg{width:21px;height:21px;}
.modal-ttl{font-family:var(--mono);font-size:16px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.modal-sub{font-size:12px;color:var(--text3);margin-top:3px;}
.modal-lbl{font-size:11.5px;font-weight:600;color:var(--text2);margin-bottom:10px;}
.modal-lbl span{color:var(--red);}
.chips{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px;}
.chip{font-size:12px;padding:6px 13px;border-radius:100px;border:1px solid var(--border2);color:var(--text2);background:transparent;cursor:pointer;transition:all .15s;font-family:var(--font);font-weight:500;}
.chip-amber:hover,.chip-amber.on{border-color:var(--amber);background:var(--amber-lt);color:#b45309;}
.chip-red:hover,.chip-red.on{border-color:var(--red);background:var(--red-lt);color:#b91c1c;}
[data-theme="dark"] .chip-amber:hover,[data-theme="dark"] .chip-amber.on{color:#fbbf24;}
[data-theme="dark"] .chip-red:hover,[data-theme="dark"] .chip-red.on{color:#fca5a5;}
.modal-ta{width:100%;border:1px solid var(--border2);border-radius:12px;padding:12px 14px;font-size:13px;color:var(--text);resize:none;font-family:var(--font);background:var(--surface2);outline:none;transition:border-color var(--ease),box-shadow var(--ease);line-height:1.5;}
.modal-ta:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
.modal-err{font-size:11.5px;color:var(--red);margin-top:7px;display:none;font-family:var(--mono);font-weight:600;}
.modal-acts{display:flex;gap:9px;margin-top:18px;}
.modal-btn{flex:1;padding:12px;border-radius:11px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all var(--ease);font-family:var(--font);}
.modal-btn:hover{transform:translateY(-1px);}
.modal-cancel{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.modal-amber{background:linear-gradient(135deg,var(--amber),#d97706);color:#fff;box-shadow:0 4px 16px rgba(245,158,11,.3);}
.modal-red{background:linear-gradient(135deg,var(--red),#dc2626);color:#fff;box-shadow:0 4px 16px rgba(240,68,68,.3);}

/* TOAST */
.toast-wrap{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:270px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s ease both;}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-ok{background:linear-gradient(135deg,#059669,#10b981);}
.toast-err{background:linear-gradient(135deg,#dc2626,#f04444);}
.toast-warn{background:linear-gradient(135deg,#b45309,#f59e0b);}
.toast-x{margin-left:auto;width:18px;height:18px;border-radius:5px;background:rgba(255,255,255,.22);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}

.pagination-wrap{margin-top:24px;}

::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}

@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
@keyframes toastIn{from{opacity:0;transform:translateX(18px) scale(.96)}to{opacity:1;transform:none}}

@media(max-width:1200px){.analytics-row{grid-template-columns:1fr}.qnav{grid-template-columns:repeat(4,1fr)}.stats-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}.search-wrap{display:none}.hero{flex-direction:column;align-items:flex-start}.hero-right{width:100%}.stats-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.topbar{padding:0 16px}.body{padding:14px 14px 48px}.stats-grid{grid-template-columns:1fr 1fr}.c-grid{grid-template-columns:1fr}.qnav{grid-template-columns:repeat(2,1fr)}.stat-val{font-size:1.5rem}}
</style>
</head>
<body>

<div class="toast-wrap" id="toastWrap"></div>

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
        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="">
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
    <a href="{{ route('admin.dashboard') }}" class="s-link active">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>
  </nav>

  <div class="s-section">Campaigns</div>
  <nav class="s-nav">
    <a href="#cGrid" class="s-link" onclick="setFilter('all');return false;">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
      All Campaigns
      <span class="s-chip sc-purple">{{ $totalCampaigns }}</span>
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

<div class="main">
  <header class="topbar">
    <div style="display:flex;align-items:center;gap:10px;">
      <button class="hamburger" id="hamburger" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <div class="tb-left">
        <h1>Dashboard</h1>
        <p>{{ now()->format('l, d F Y') }}</p>
      </div>
    </div>
    <div class="tb-right">
      <div class="search-wrap">
        <svg class="s-icon-inp" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input class="search-inp" id="searchInput" type="text" placeholder="Search campaigns…" autocomplete="off">
      </div>
      <select class="sort-sel" id="sortSelect">
        <option value="">Sort by…</option>
        <option value="amount-desc">Amount ↓</option>
        <option value="amount-asc">Amount ↑</option>
        <option value="date-desc">Newest first</option>
        <option value="date-asc">Oldest first</option>
      </select>
      <button class="tb-btn" title="Notifications">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @if($cntPending > 0)<span class="notif-dot"></span>@endif
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
      <div class="av-wrap" id="avWrap">
        <div class="t-av" onclick="toggleDD()" title="Account">
          @if(auth()->user()->avatar)
            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="">
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
    @php
      $hour = now()->hour;
      $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
      $approvalRate = $totalCampaigns > 0 ? round(($cntActive / $totalCampaigns) * 100) : 0;
      $activeJobs = \App\Models\JobPost::where('status','active')->count();
      $totalApplicants = \App\Models\JobPostApplication::count();
    @endphp

    <div class="hero">
      <div class="hero-left">
        <div class="hero-tag"><span class="hero-tag-dot"></span>{{ $greeting }}, Administrator</div>
        <div class="hero-name">{{ auth()->user()->name ?? 'Admin' }} 👋</div>
        <div class="hero-sub">Here's your platform overview for today. Manage campaigns, job posts, and keep DonateBazaar running smoothly.</div>
        <div class="hero-badges">
          @if($cntPending > 0)
            <span class="hero-badge hb-amber">⏱ {{ $cntPending }} awaiting review</span>
          @else
            <span class="hero-badge hb-green">✓ All caught up</span>
          @endif
          <span class="hero-badge hb-green">{{ $cntActive }} active campaigns</span>
          <span class="hero-badge hb-purple">{{ $approvalRate }}% approval rate</span>
          <span class="hero-badge hb-teal">{{ $activeJobs }} open jobs</span>
        </div>
      </div>
      <div class="hero-right">
        @if($cntPending > 0)
        <button onclick="setFilter('pending')" class="hero-btn hero-btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Review Pending
        </button>
        @endif
        <a href="{{ route('admin.job_posts.create') }}" class="hero-btn hero-btn-teal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Post a Job
        </a>
        <a href="{{ route('profile.show') }}" class="hero-btn hero-btn-ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          My Profile
        </a>
      </div>
    </div>

    <div class="stats-grid">
      <div class="stat">
        <div class="stat-icon si-a"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Total Campaigns</div><div class="stat-val sv-a">{{ $totalCampaigns }}</div><div class="stat-foot">All time on platform</div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Pending Review</div><div class="stat-val sv-amber">{{ $cntPending }}</div><div class="stat-foot">Awaiting your decision</div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Active Now</div><div class="stat-val sv-green">{{ $cntActive }}</div><div class="stat-foot">Currently running live</div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Approval Rate</div><div class="stat-val sv-blue">{{ $approvalRate }}%</div><div class="stat-foot">Of all campaigns</div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Active Jobs</div><div class="stat-val sv-teal">{{ $activeJobs }}</div><div class="stat-foot"><a href="{{ route('admin.job_posts.create') }}">+ Post new job →</a></div></div>
      </div>
    </div>

    

    <div class="analytics-row">
      <div class="chart-card">
        <div class="chart-hdr">
          <div>
            <div class="chart-ttl">Campaign Activity</div>
            <div class="chart-sub">Monthly overview — last 12 months</div>
          </div>
          <div class="chart-legend">
            <div class="leg-item"><div class="leg-dot" style="background:#6e56f7"></div>Total</div>
            <div class="leg-item"><div class="leg-dot" style="background:#05c48a"></div>Approved</div>
          </div>
        </div>
        <div class="chart-wrap"><canvas id="lineChart"></canvas></div>
      </div>
      <div class="status-panel">
        <div class="sp-ttl">Status Breakdown</div>
        <div class="sp-row" onclick="setFilter('active')"><div class="sp-left"><div class="sp-dot" style="background:var(--green)"></div><span class="sp-label">Active</span></div><span class="sp-val">{{ $cntActive }}</span></div>
        <div class="sp-row" onclick="setFilter('pending')"><div class="sp-left"><div class="sp-dot" style="background:var(--amber)"></div><span class="sp-label">Pending</span></div><span class="sp-val">{{ $cntPending }}</span></div>
        <div class="sp-row" onclick="setFilter('paused')"><div class="sp-left"><div class="sp-dot" style="background:var(--a)"></div><span class="sp-label">Paused</span></div><span class="sp-val">{{ $cntPaused }}</span></div>
        <div class="sp-row" onclick="setFilter('rejected')"><div class="sp-left"><div class="sp-dot" style="background:var(--red)"></div><span class="sp-label">Rejected</span></div><span class="sp-val">{{ $cntRejected }}</span></div>
        <div class="sp-row" onclick="setFilter('inactive')"><div class="sp-left"><div class="sp-dot" style="background:var(--gray)"></div><span class="sp-label">Inactive / Expired</span></div><span class="sp-val">{{ $cntExpired + $cntCompleted }}</span></div>
        <div class="sp-prog">
          <div class="sp-prog-lbl"><span>Approval rate</span><span>{{ $approvalRate }}%</span></div>
          <div class="sp-bar"><div class="sp-fill" id="approvalBar" style="width:0%"></div></div>
        </div>
      </div>
    </div>

    <div class="qnav">
      @php
        $navItems = [
          ['url'=>url('/admin/categories'),                    'lbl'=>'Categories',    'sub'=>'Manage all',     'delay'=>'.05s','bg'=>'var(--a-lt)',           'color'=>'var(--a)',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>'],
          ['url'=>url('/admin/partnerships'),                  'lbl'=>'Partnerships',  'sub'=>'View requests',  'delay'=>'.10s','bg'=>'var(--green-lt)',        'color'=>'var(--green)','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>'],
          ['url'=>url('/admin/messages'),                      'lbl'=>'Messages',      'sub'=>'View all',       'delay'=>'.15s','bg'=>'rgba(249,115,22,.10)',   'color'=>'#f97316',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
          ['url'=>url('/admin/blogs'),                         'lbl'=>'Blogs',         'sub'=>'Manage posts',   'delay'=>'.20s','bg'=>'rgba(168,85,247,.10)',   'color'=>'#a855f7',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>'],
          ['url'=>url('/admin/applications'),                  'lbl'=>'Applications',  'sub'=>'NGO requests',   'delay'=>'.25s','bg'=>'var(--blue-lt)',         'color'=>'var(--blue)', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
          ['url'=>route('admin.job_posts.index'),              'lbl'=>'Job Posts',     'sub'=>'All listings',   'delay'=>'.30s','bg'=>'rgba(5,196,138,.10)',    'color'=>'#05c48a',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
          ['url'=>route('admin.job_posts.create'),             'lbl'=>'Post a Job',    'sub'=>'Create listing', 'delay'=>'.35s','bg'=>'rgba(245,158,11,.10)',   'color'=>'#f59e0b',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>'],
          ['url'=>route('admin.job_post_applications.index'),  'lbl'=>'Applicants',    'sub'=>'Job applicants', 'delay'=>'.40s','bg'=>'rgba(236,72,153,.10)',   'color'=>'#ec4899',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>'],
          ['url'=>route('profile.show'),                       'lbl'=>'My Profile',    'sub'=>'View & edit',    'delay'=>'.45s','bg'=>'var(--pink-lt)',         'color'=>'var(--pink)', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
        ];
      @endphp
      @foreach($navItems as $item)
      <a href="{{ $item['url'] }}" class="qnav-card" style="animation-delay:{{ $item['delay'] }};--qc:{{ $item['bg'] }};">
        <div class="qnav-ico" style="background:{{ $item['bg'] }};color:{{ $item['color'] }};"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $item['icon'] !!}</svg></div>
        <div><div class="qnav-lbl">{{ $item['lbl'] }}</div><div class="qnav-sub">{{ $item['sub'] }}</div></div>
      </a>
      @endforeach
    </div>

    @php $cntAll = $cntPending + $cntActive + $cntPaused + $cntRejected + $cntExpired + $cntCompleted; @endphp
    <div class="sec-hdr" id="cGrid">
      <div class="sec-ttl">All Campaigns</div>
      <div class="sec-right">
        <div class="ftabs" id="ftabs">
          <button class="ftab on" data-filter="all">All <span class="cnt">{{ $cntAll }}</span></button>
          <button class="ftab" data-filter="pending">Pending <span class="cnt">{{ $cntPending }}</span></button>
          <button class="ftab" data-filter="active">Active <span class="cnt">{{ $cntActive }}</span></button>
          <button class="ftab" data-filter="paused">Paused <span class="cnt">{{ $cntPaused }}</span></button>
          <button class="ftab" data-filter="inactive">Inactive <span class="cnt">{{ $cntExpired + $cntCompleted }}</span></button>
          <button class="ftab" data-filter="rejected">Rejected <span class="cnt">{{ $cntRejected }}</span></button>
        </div>
      </div>
    </div>

    <div id="noResults">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <strong>No campaigns found</strong>
      <span>No campaigns match your current filter or search.</span>
    </div>

    <div class="c-grid" id="campaignGrid">

      {{-- PENDING --}}
      @foreach($pendingCampaigns as $i => $c)
      @php
        $raised=$c->raised_amount??0;$goal=$c->goal_amount>0?$c->goal_amount:1;$pct=min(100,round(($raised/$goal)*100));
        $uName=$c->user?->name??'Unknown';$uEmail=$c->user?->email??'';$uInit=strtoupper(substr($uName,0,1));
      @endphp
      <div class="c-card" data-filter="pending" data-title="{{ strtolower($c->title) }}" data-amount="{{ $c->goal_amount }}" data-date="{{ $c->created_at }}" style="animation-delay:{{ $i*0.04 }}s">
        <div class="c-thumb">
          @if($c->cover_image)<img src="{{ asset('storage/'.$c->cover_image) }}" alt="{{ $c->title }}" loading="lazy"><div class="c-overlay"></div>
          @else<div class="c-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>@endif
          <div class="c-badge-pos"><span class="badge b-pending">Pending</span></div>
        </div>
        <div class="c-user">
          <div class="c-uav">{{ $uInit }}</div>
          <div><div class="c-uname">{{ $uName }}</div>@if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif</div>
        </div>
        <div class="c-body">
          <div class="c-title">{{ $c->title }}</div>
          <div class="prog">
            <div class="prog-nums"><span class="prog-raised">₹{{ number_format($raised) }}</span><span class="prog-goal">of ₹{{ number_format($c->goal_amount) }}</span></div>
            <div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
            <div class="prog-pct">{{ $pct }}% funded</div>
          </div>
          <div class="c-actions">
            <form action="{{ route('admin.campaign.approve',$c->id) }}" method="POST" style="flex:1;" onsubmit="return handleSub(this,'Approving…')">
              @csrf<button class="c-btn c-btn-approve" style="width:100%;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Approve</button>
            </form>
            <button type="button" onclick="openReject({{ $c->id }})" class="c-btn c-btn-reject"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Reject</button>
            <a href="{{ route('admin.campaign.show',$c->id) }}" class="c-btn c-btn-view"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
          </div>
        </div>
      </div>
      @endforeach

      {{-- ACTIVE + PAUSED --}}
      @foreach($activeCampaigns as $i => $c)
      @php
        $raised=$c->raised_amount??0;$goal=$c->goal_amount>0?$c->goal_amount:1;$pct=min(100,round(($raised/$goal)*100));
        $isPaused=($c->campaign_state==='paused');$fv=$isPaused?'paused':'active';
        $uName=$c->user?->name??'Unknown';$uEmail=$c->user?->email??'';$uInit=strtoupper(substr($uName,0,1));
      @endphp
      <div class="c-card" data-filter="{{ $fv }}" data-title="{{ strtolower($c->title) }}" data-amount="{{ $c->goal_amount }}" data-date="{{ $c->created_at }}" style="animation-delay:{{ $i*0.04 }}s">
        <div class="c-thumb">
          @if($c->cover_image)<img src="{{ asset('storage/'.$c->cover_image) }}" alt="{{ $c->title }}" loading="lazy"><div class="c-overlay"></div>
          @else<div class="c-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>@endif
          <div class="c-badge-pos"><span class="badge {{ $isPaused?'b-paused':'b-active' }}">{{ $isPaused?'Paused':'Active' }}</span></div>
        </div>
        <div class="c-user">
          <div class="c-uav">{{ $uInit }}</div>
          <div><div class="c-uname">{{ $uName }}</div>@if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif</div>
        </div>
        <div class="c-body">
          <div class="c-title">{{ $c->title }}</div>
          @if($isPaused && $c->pause_reason)<div class="reason reason-amber"><div class="reason-lbl">⏸ PAUSE REASON</div><div class="reason-txt">{{ $c->pause_reason }}</div></div>@endif
          <div class="prog">
            <div class="prog-nums"><span class="prog-raised">₹{{ number_format($raised) }}</span><span class="prog-goal">of ₹{{ number_format($c->goal_amount) }}</span></div>
            <div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
            <div class="prog-pct">{{ $pct }}% funded</div>
          </div>
          <div class="c-actions">
            @if(!$isPaused)
            <button type="button" onclick="openPause({{ $c->id }})" class="c-btn c-btn-pause" style="flex:1;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Pause</button>
            @else
            <form action="{{ route('admin.campaign.resume',$c->id) }}" method="POST" style="flex:1;" onsubmit="return handleSub(this,'Resuming…')">
              @csrf<button class="c-btn c-btn-resume" style="width:100%;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Resume</button>
            </form>
            @endif
            <a href="{{ route('admin.campaign.show',$c->id) }}" class="c-btn c-btn-view" style="flex:1;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>View</a>
          </div>
        </div>
      </div>
      @endforeach

      {{-- REJECTED --}}
      @foreach($rejectedCampaigns as $i => $c)
      @php
        $raised=$c->raised_amount??0;$goal=$c->goal_amount>0?$c->goal_amount:1;$pct=min(100,round(($raised/$goal)*100));
        $uName=$c->user?->name??'Unknown';$uEmail=$c->user?->email??'';$uInit=strtoupper(substr($uName,0,1));
      @endphp
      <div class="c-card" data-filter="rejected" data-title="{{ strtolower($c->title) }}" data-amount="{{ $c->goal_amount }}" data-date="{{ $c->created_at }}" style="animation-delay:{{ $i*0.04 }}s">
        <div class="c-thumb">
          @if($c->cover_image)<img src="{{ asset('storage/'.$c->cover_image) }}" alt="{{ $c->title }}" loading="lazy"><div class="c-overlay"></div>
          @else<div class="c-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>@endif
          <div class="c-badge-pos"><span class="badge b-rejected">Rejected</span></div>
        </div>
        <div class="c-user">
          <div class="c-uav" style="background:linear-gradient(135deg,#f04444,#dc2626);">{{ $uInit }}</div>
          <div><div class="c-uname">{{ $uName }}</div>@if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif</div>
        </div>
        <div class="c-body">
          <div class="c-title">{{ $c->title }}</div>
          @if($c->rejection_reason)<div class="reason reason-red"><div class="reason-lbl">✕ REJECTION REASON</div><div class="reason-txt">{{ $c->rejection_reason }}</div></div>@endif
          <div class="prog">
            <div class="prog-nums"><span class="prog-raised">₹{{ number_format($raised) }}</span><span class="prog-goal">of ₹{{ number_format($c->goal_amount) }}</span></div>
            <div class="prog-bar"><div class="prog-fill prog-fill-red" style="width:{{ $pct }}%"></div></div>
            <div class="prog-pct" style="color:var(--red)">{{ $pct }}% funded</div>
          </div>
          <div class="c-actions">
            <a href="{{ route('admin.campaign.show',$c->id) }}" class="c-btn c-btn-view" style="flex:1;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>View Details</a>
          </div>
        </div>
      </div>
      @endforeach

      {{-- INACTIVE --}}
      @foreach($inactiveCampaigns as $i => $c)
      @php
        $raised=$c->raised_amount??0;$goal=$c->goal_amount>0?$c->goal_amount:1;$pct=min(100,round(($raised/$goal)*100));
        $uName=$c->user?->name??'Unknown';$uEmail=$c->user?->email??'';$uInit=strtoupper(substr($uName,0,1));
      @endphp
      <div class="c-card" data-filter="inactive" data-title="{{ strtolower($c->title) }}" data-amount="{{ $c->goal_amount }}" data-date="{{ $c->created_at }}" style="animation-delay:{{ $i*0.04 }}s">
        <div class="c-thumb">
          @if($c->cover_image)<img src="{{ asset('storage/'.$c->cover_image) }}" alt="{{ $c->title }}" loading="lazy"><div class="c-overlay"></div>
          @else<div class="c-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>@endif
          <div class="c-badge-pos"><span class="badge b-inactive">{{ $c->status==='completed'?'Completed':'Inactive' }}</span></div>
        </div>
        <div class="c-user">
          <div class="c-uav" style="background:linear-gradient(135deg,#64748b,#475569);">{{ $uInit }}</div>
          <div><div class="c-uname">{{ $uName }}</div>@if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif</div>
        </div>
        <div class="c-body">
          <div class="c-title">{{ $c->title }}</div>
          <div class="prog">
            <div class="prog-nums"><span class="prog-raised">₹{{ number_format($raised) }}</span><span class="prog-goal">of ₹{{ number_format($c->goal_amount) }}</span></div>
            <div class="prog-bar"><div class="prog-fill prog-fill-gray" style="width:{{ $pct }}%"></div></div>
            <div class="prog-pct" style="color:#64748b">{{ $pct }}% funded</div>
          </div>
          <div class="c-actions">
            <a href="{{ route('admin.campaign.show',$c->id) }}" class="c-btn c-btn-view" style="flex:1;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>View Details</a>
          </div>
        </div>
      </div>
      @endforeach

    </div>

    <div class="pagination-wrap">{{ $activeCampaigns->links() }}</div>
  </div>
</div>
</div>

{{-- PAUSE MODAL --}}
<div id="pauseOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closePause()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-head">
      <div class="modal-ico" style="background:var(--amber-lt);"><svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
      <div><div class="modal-ttl">Pause Campaign</div><div class="modal-sub">Reason will be shown to the campaign owner</div></div>
    </div>
    <form id="pauseForm" method="POST">
      @csrf
      <div class="modal-lbl">Select or write a reason <span>*</span></div>
      <div class="chips">
        <button type="button" class="chip chip-amber" data-r="Suspicious activity detected">Suspicious activity</button>
        <button type="button" class="chip chip-amber" data-r="Incomplete or missing documents">Missing documents</button>
        <button type="button" class="chip chip-amber" data-r="Under review by admin team">Under review</button>
        <button type="button" class="chip chip-amber" data-r="Violation of platform guidelines">Policy violation</button>
        <button type="button" class="chip chip-amber" data-r="Awaiting additional verification">Pending verification</button>
      </div>
      <textarea id="pauseReason" name="reason" rows="3" placeholder="Or type a custom reason…" class="modal-ta"></textarea>
      <p id="pauseErr" class="modal-err">⚠ Please provide a reason before pausing.</p>
      <div class="modal-acts">
        <button type="button" onclick="closePause()" class="modal-btn modal-cancel">Cancel</button>
        <button type="submit" id="pauseBtn" class="modal-btn modal-amber">⏸ Pause Campaign</button>
      </div>
    </form>
  </div>
</div>

{{-- REJECT MODAL --}}
<div id="rejectOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeReject()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-head">
      <div class="modal-ico" style="background:var(--red-lt);"><svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
      <div><div class="modal-ttl">Reject Campaign</div><div class="modal-sub">Reason will be shown to the campaign owner</div></div>
    </div>
    <form id="rejectForm" method="POST">
      @csrf
      <div class="modal-lbl">Select or write a reason <span>*</span></div>
      <div class="chips">
        <button type="button" class="chip chip-red" data-r="Fraudulent or misleading content">Fraudulent content</button>
        <button type="button" class="chip chip-red" data-r="Incomplete campaign information">Incomplete info</button>
        <button type="button" class="chip chip-red" data-r="Violation of platform terms">Terms violation</button>
        <button type="button" class="chip chip-red" data-r="Duplicate campaign detected">Duplicate campaign</button>
        <button type="button" class="chip chip-red" data-r="Insufficient documentation provided">Insufficient docs</button>
      </div>
      <textarea id="rejectReason" name="reason" rows="3" placeholder="Or type a custom reason…" class="modal-ta"></textarea>
      <p id="rejectErr" class="modal-err">⚠ Please provide a reason before rejecting.</p>
      <div class="modal-acts">
        <button type="button" onclick="closeReject()" class="modal-btn modal-cancel">Cancel</button>
        <button type="submit" id="rejectBtn" class="modal-btn modal-red">✕ Reject Campaign</button>
      </div>
    </form>
  </div>
</div>

<script>
(function(){
'use strict';

var html=document.documentElement,toggle=document.getElementById('themeToggle');
var saved=localStorage.getItem('adminTheme')||'light';
if(saved==='dark'){html.setAttribute('data-theme','dark');toggle.checked=true;}
toggle.addEventListener('change',function(){
  var t=this.checked?'dark':'light';
  html.setAttribute('data-theme',t);
  localStorage.setItem('adminTheme',t);
  setTimeout(renderChart,60);
});

document.getElementById('hamburger').addEventListener('click',function(){
  document.getElementById('sidebar').classList.toggle('open');
});
document.addEventListener('click',function(e){
  var sb=document.getElementById('sidebar');
  if(window.innerWidth<=860&&!sb.contains(e.target)&&!document.getElementById('hamburger').contains(e.target))
    sb.classList.remove('open');
});

window.toggleDD=function(){document.getElementById('avDD').classList.toggle('open');};
document.addEventListener('click',function(e){
  var w=document.getElementById('avWrap');
  if(w&&!w.contains(e.target))document.getElementById('avDD').classList.remove('open');
});

setTimeout(function(){
  var b=document.getElementById('approvalBar');
  if(b)b.style.width='{{ $approvalRate??0 }}%';
},700);

function toast(msg,type){
  type=type||'success';
  var icons={
    success:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    error:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    warn:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
  };
  var t=document.createElement('div');
  t.className='toast toast-'+(type==='success'?'ok':type==='error'?'err':'warn');
  t.innerHTML=(icons[type]||'')+'<span>'+msg+'</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
  document.getElementById('toastWrap').appendChild(t);
  setTimeout(function(){t.style.transition='opacity .3s,transform .3s';t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(function(){t.remove();},300);},4200);
}
@if(session('success')) setTimeout(function(){toast(@json(session('success')),'success');},200); @endif
@if(session('error'))   setTimeout(function(){toast(@json(session('error')),'error');},200);   @endif
@if(session('warning')) setTimeout(function(){toast(@json(session('warning')),'warn');},200);  @endif

var cards=Array.from(document.querySelectorAll('#campaignGrid .c-card'));
var activeFilter='all',searchQ='',sortVal='';

function applyFilters(){
  var sorted=cards.slice();
  if(sortVal==='amount-desc')sorted.sort(function(a,b){return +b.dataset.amount - +a.dataset.amount;});
  else if(sortVal==='amount-asc')sorted.sort(function(a,b){return +a.dataset.amount - +b.dataset.amount;});
  else if(sortVal==='date-desc')sorted.sort(function(a,b){return new Date(b.dataset.date)-new Date(a.dataset.date);});
  else if(sortVal==='date-asc')sorted.sort(function(a,b){return new Date(a.dataset.date)-new Date(b.dataset.date);});
  var grid=document.getElementById('campaignGrid');
  sorted.forEach(function(c){grid.appendChild(c);});
  var visible=0;
  cards.forEach(function(c){
    var mf=activeFilter==='all'||c.dataset.filter===activeFilter;
    var ms=!searchQ||(c.dataset.title||'').includes(searchQ);
    c.style.display=(mf&&ms)?'':'none';
    if(mf&&ms)visible++;
  });
  document.getElementById('noResults').style.display=visible>0?'none':'block';
}

document.querySelectorAll('.ftab').forEach(function(tab){
  tab.addEventListener('click',function(){
    document.querySelectorAll('.ftab').forEach(function(t){t.classList.remove('on');});
    this.classList.add('on');activeFilter=this.dataset.filter;applyFilters();
  });
});

window.setFilter=function(f){
  activeFilter=f;
  document.querySelectorAll('.ftab').forEach(function(t){t.classList.toggle('on',t.dataset.filter===f);});
  applyFilters();
  var el=document.getElementById('cGrid');
  if(el)el.scrollIntoView({behavior:'smooth',block:'start'});
};

var st;
document.getElementById('searchInput').addEventListener('input',function(){
  clearTimeout(st);searchQ=this.value.toLowerCase().trim();st=setTimeout(applyFilters,180);
});
document.getElementById('sortSelect').addEventListener('change',function(){sortVal=this.value;applyFilters();});

function openPause(id){
  document.getElementById('pauseForm').action='/admin/campaign/'+id+'/pause';
  document.getElementById('pauseReason').value='';
  document.getElementById('pauseErr').style.display='none';
  var btn=document.getElementById('pauseBtn');btn.disabled=false;btn.innerHTML='⏸ Pause Campaign';
  document.querySelectorAll('.chip-amber').forEach(function(c){c.classList.remove('on');});
  document.getElementById('pauseOverlay').classList.add('open');
  setTimeout(function(){document.getElementById('pauseReason').focus();},80);
}
function closePause(){document.getElementById('pauseOverlay').classList.remove('open');}
window.openPause=openPause;window.closePause=closePause;

document.querySelectorAll('.chip-amber').forEach(function(btn){
  btn.addEventListener('click',function(){
    document.querySelectorAll('.chip-amber').forEach(function(b){b.classList.remove('on');});
    this.classList.add('on');document.getElementById('pauseReason').value=this.dataset.r;
    document.getElementById('pauseErr').style.display='none';
  });
});
document.getElementById('pauseForm').addEventListener('submit',function(e){
  if(!document.getElementById('pauseReason').value.trim()){e.preventDefault();document.getElementById('pauseErr').style.display='block';return;}
  var btn=document.getElementById('pauseBtn');btn.disabled=true;btn.innerHTML='Pausing…';
});

function openReject(id){
  document.getElementById('rejectForm').action='/admin/campaign/'+id+'/reject';
  document.getElementById('rejectReason').value='';
  document.getElementById('rejectErr').style.display='none';
  var btn=document.getElementById('rejectBtn');btn.disabled=false;btn.innerHTML='✕ Reject Campaign';
  document.querySelectorAll('.chip-red').forEach(function(c){c.classList.remove('on');});
  document.getElementById('rejectOverlay').classList.add('open');
  setTimeout(function(){document.getElementById('rejectReason').focus();},80);
}
function closeReject(){document.getElementById('rejectOverlay').classList.remove('open');}
window.openReject=openReject;window.closeReject=closeReject;

document.querySelectorAll('.chip-red').forEach(function(btn){
  btn.addEventListener('click',function(){
    document.querySelectorAll('.chip-red').forEach(function(b){b.classList.remove('on');});
    this.classList.add('on');document.getElementById('rejectReason').value=this.dataset.r;
    document.getElementById('rejectErr').style.display='none';
  });
});
document.getElementById('rejectForm').addEventListener('submit',function(e){
  if(!document.getElementById('rejectReason').value.trim()){e.preventDefault();document.getElementById('rejectErr').style.display='block';return;}
  var btn=document.getElementById('rejectBtn');btn.disabled=true;btn.innerHTML='Rejecting…';
});

document.getElementById('pauseOverlay').addEventListener('click',function(e){if(e.target===this)closePause();});
document.getElementById('rejectOverlay').addEventListener('click',function(e){if(e.target===this)closeReject();});
document.addEventListener('keydown',function(e){if(e.key==='Escape'){closePause();closeReject();}});

window.handleSub=function(form,txt){
  form.querySelectorAll('button[type=submit]').forEach(function(b){b.disabled=true;b.textContent=txt;});
  return true;
};

var lineChart;
var chartLabels=@json($chartLabels);
var chartTotal=@json($chartTotal);
var chartApproved=@json($chartActive);

function renderChart(){
  var canvas=document.getElementById('lineChart');
  if(!canvas||typeof Chart==='undefined')return;
  var isDark=html.getAttribute('data-theme')==='dark';
  var gridCol=isDark?'rgba(255,255,255,.05)':'rgba(0,0,0,.04)';
  var lblCol=isDark?'rgba(255,255,255,.32)':'rgba(0,0,0,.32)';
  var tipBg=isDark?'#1d1f35':'#fff';
  var tipTx=isDark?'#eef0ff':'#0a0b14';
  Chart.defaults.font.family="'DM Mono',monospace";
  Chart.defaults.font.size=10.5;
  if(lineChart){lineChart.destroy();lineChart=null;}
  var ctx=canvas.getContext('2d');
  var g1=ctx.createLinearGradient(0,0,0,190);
  g1.addColorStop(0,'rgba(110,86,247,.22)');g1.addColorStop(1,'rgba(110,86,247,0)');
  var g2=ctx.createLinearGradient(0,0,0,190);
  g2.addColorStop(0,'rgba(5,196,138,.18)');g2.addColorStop(1,'rgba(5,196,138,0)');
  lineChart=new Chart(ctx,{
    type:'line',
    data:{
      labels:chartLabels,
      datasets:[
        {label:'Total Campaigns',data:chartTotal,borderColor:'#6e56f7',backgroundColor:g1,borderWidth:2.5,pointRadius:4,tension:.45,fill:true,pointBackgroundColor:'#6e56f7',pointBorderColor:tipBg,pointBorderWidth:2,pointHoverRadius:6},
        {label:'Approved',data:chartApproved,borderColor:'#05c48a',backgroundColor:g2,borderWidth:2.5,pointRadius:4,tension:.45,fill:true,pointBackgroundColor:'#05c48a',pointBorderColor:tipBg,pointBorderWidth:2,pointHoverRadius:6}
      ]
    },
    options:{
      responsive:true,maintainAspectRatio:false,
      interaction:{intersect:false,mode:'index'},
      plugins:{
        legend:{display:false},
        tooltip:{backgroundColor:tipBg,titleColor:tipTx,bodyColor:tipTx,borderColor:gridCol,borderWidth:1,padding:13,cornerRadius:11,titleFont:{size:11,weight:'700'},bodyFont:{size:11}}
      },
      scales:{
        x:{grid:{color:gridCol},border:{dash:[3,3],display:false},ticks:{color:lblCol}},
        y:{grid:{color:gridCol},border:{dash:[3,3],display:false},beginAtZero:true,ticks:{stepSize:1,precision:0,color:lblCol}}
      },
      animation:{duration:900,easing:'easeOutQuart'}
    }
  });
}

renderChart();
})();
</script>
</body>
</html>