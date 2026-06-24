{{-- resources/views/profile/show.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>My Profile — DonateBazaar</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
/* ─────────────────────────────────────────
   TOKENS
───────────────────────────────────────── */
:root{
  --bg:#f2f3f9;--surface:#fff;--surface2:#f8f9fe;--surface3:#eef0fa;
  --border:rgba(0,0,0,.06);--border2:rgba(0,0,0,.10);
  --text:#0a0b14;--text2:#454863;--text3:#9096b4;
  --sb-bg:#fff;--sb-txt:#5a5f7a;--sb-act:rgba(110,86,247,.10);--sb-border:rgba(0,0,0,.07);
  --a:#6e56f7;--a2:#9b6dff;--a-lt:rgba(110,86,247,.09);--a-glow:rgba(110,86,247,.20);
  --green:#05c48a;--green-lt:rgba(5,196,138,.10);
  --amber:#f59e0b;--amber-lt:rgba(245,158,11,.10);
  --red:#f04444;--red-lt:rgba(240,68,68,.09);
  --blue:#3b82f6;--blue-lt:rgba(59,130,246,.09);
  --font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;
  --r:16px;--r-sm:11px;--r-xs:7px;
  --sh:0 1px 3px rgba(0,0,0,.05),0 4px 20px rgba(0,0,0,.04);
  --sh-md:0 4px 20px rgba(0,0,0,.09),0 1px 4px rgba(0,0,0,.04);
  --sh-lg:0 8px 32px rgba(0,0,0,.15);
  --ease:.18s ease;--sb-w:260px;
}
[data-theme="dark"]{
  --bg:#07080f;--surface:#0e0f1e;--surface2:#141525;--surface3:#1a1c2e;
  --border:rgba(255,255,255,.055);--border2:rgba(255,255,255,.09);
  --text:#eef0ff;--text2:#9ba3c8;--text3:#4c5272;
  --sb-bg:#09090f;--sb-txt:rgba(255,255,255,.45);--sb-act:rgba(110,86,247,.22);--sb-border:rgba(255,255,255,.03);
  --a-glow:rgba(110,86,247,.28);
  --sh:0 1px 3px rgba(0,0,0,.35),0 4px 24px rgba(0,0,0,.28);
  --sh-md:0 4px 20px rgba(0,0,0,.45),0 1px 4px rgba(0,0,0,.25);
  --sh-lg:0 12px 48px rgba(0,0,0,.65);
}

/* ─────────────────────────────────────────
   RESET & BASE
───────────────────────────────────────── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;}
body{font-family:var(--font);background:var(--bg);color:var(--text);line-height:1.55;-webkit-font-smoothing:antialiased;overflow-x:hidden;transition:background .2s,color .2s;}
a{text-decoration:none;color:inherit;}
button{font-family:var(--font);}
img{display:block;}

/* ─────────────────────────────────────────
   LAYOUT SHELL
───────────────────────────────────────── */
.shell{display:flex;min-height:100vh;}

/* ─────────────────────────────────────────
   SIDEBAR
───────────────────────────────────────── */
.sidebar{
  width:var(--sb-w);flex-shrink:0;
  background:var(--sb-bg);
  display:flex;flex-direction:column;
  position:fixed;top:0;left:0;bottom:0;z-index:400;
  overflow-y:auto;overflow-x:hidden;
  border-right:1px solid var(--sb-border);
  box-shadow:2px 0 20px rgba(0,0,0,.05);
  transition:transform .3s cubic-bezier(.4,0,.2,1);
}
.sidebar::-webkit-scrollbar{width:0;}

.s-logo{display:flex;align-items:center;gap:12px;padding:24px 20px 20px;border-bottom:1px solid var(--sb-border);}
.s-logo-mark{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,var(--a),var(--a2));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 14px rgba(110,86,247,.38);}
.s-logo-mark svg{width:18px;height:18px;color:#fff;}
.s-logo-name{font-family:var(--mono);font-size:16px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1.1;}
.s-logo-tag{font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.16em;font-family:var(--mono);}

.s-user-pill{margin:12px 10px 4px;padding:10px 12px;background:linear-gradient(135deg,rgba(110,86,247,.07),rgba(155,109,255,.04));border:1px solid rgba(110,86,247,.13);border-radius:var(--r-sm);display:flex;align-items:center;gap:10px;}
.s-uav{width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
.s-uav img{width:100%;height:100%;object-fit:cover;}
.s-uname{font-size:12px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.s-urole{font-size:10px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.s-online{width:7px;height:7px;border-radius:50%;background:var(--green);margin-left:auto;flex-shrink:0;box-shadow:0 0 0 2px rgba(5,196,138,.22);}

.s-section{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.18em;padding:18px 20px 5px;font-family:var(--mono);}
.s-nav{padding:2px 8px;}
.s-link{display:flex;align-items:center;gap:10px;padding:8px 12px;border-radius:var(--r-xs);color:var(--sb-txt);font-size:12.5px;font-weight:500;text-decoration:none;transition:background var(--ease),color var(--ease);margin-bottom:1px;border:none;background:transparent;width:100%;text-align:left;cursor:pointer;position:relative;font-family:var(--font);}
.s-link:hover{background:var(--a-lt);color:var(--a);}
.s-link.active{background:var(--sb-act);color:var(--a);font-weight:600;}
.s-link.active::before{content:'';position:absolute;left:0;top:20%;bottom:20%;width:3px;border-radius:0 3px 3px 0;background:var(--a);}
.s-ico{width:14px;height:14px;flex-shrink:0;opacity:.6;}
.s-link:hover .s-ico,.s-link.active .s-ico{opacity:1;}
.s-chip{margin-left:auto;font-size:10px;font-weight:700;padding:2px 7px;border-radius:100px;font-family:var(--mono);}
.sc-purple{background:var(--a-lt);color:var(--a);}
.sc-amber{background:var(--amber-lt);color:#b45309;}
.sc-teal{background:var(--green-lt);color:#059669;}
.s-divider{height:1px;background:var(--sb-border);margin:8px 16px;}
.s-bottom{margin-top:auto;padding:8px 8px 18px;border-top:1px solid var(--sb-border);}

/* ─────────────────────────────────────────
   MAIN AREA
───────────────────────────────────────── */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* ─────────────────────────────────────────
   TOPBAR
───────────────────────────────────────── */
.topbar{
  display:flex;align-items:center;justify-content:space-between;
  padding:0 28px;height:60px;
  background:var(--surface);border-bottom:1px solid var(--border);
  position:sticky;top:0;z-index:200;gap:14px;
}
.tb-left{display:flex;align-items:center;gap:10px;min-width:0;}
.tb-title{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.tb-sub{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.tb-right{display:flex;align-items:center;gap:8px;flex-shrink:0;}

/* Theme toggle */
.theme-wrap{position:relative;}
.theme-wrap input{position:absolute;opacity:0;width:0;height:0;}
.theme-wrap label{display:flex;align-items:center;justify-content:space-between;width:50px;height:27px;border-radius:100px;background:var(--surface2);border:1px solid var(--border2);cursor:pointer;padding:4px;position:relative;}
.theme-wrap label::after{content:'';width:17px;height:17px;border-radius:50%;background:var(--a);position:absolute;left:5px;transition:transform .3s cubic-bezier(.4,0,.2,1);box-shadow:0 2px 6px rgba(110,86,247,.35);}
.theme-wrap input:checked + label::after{transform:translateX(22px);}
.ti{display:flex;justify-content:space-between;width:100%;position:relative;z-index:1;padding:0 2px;}
.ti svg{width:10px;height:10px;color:var(--text3);}

/* Avatar dropdown */
.av-wrap{position:relative;}
.t-av{width:34px;height:34px;border-radius:var(--r-sm);background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:12px;font-weight:700;font-family:var(--mono);display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;overflow:hidden;box-shadow:0 2px 10px rgba(110,86,247,.35);}
.t-av img{width:100%;height:100%;object-fit:cover;}
.av-dd{position:absolute;top:calc(100% + 10px);right:0;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r);box-shadow:var(--sh-lg);min-width:210px;z-index:9999;display:none;animation:ddIn .16s ease;}
.av-dd.open{display:block;}
@keyframes ddIn{from{opacity:0;transform:translateY(-6px) scale(.97)}to{opacity:1;transform:none}}
.dd-hdr{padding:13px 15px;border-bottom:1px solid var(--border);}
.dd-name{font-size:13px;font-weight:700;color:var(--text);font-family:var(--mono);}
.dd-email{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--mono);}
.dd-item{display:flex;align-items:center;gap:9px;padding:9px 15px;font-size:12.5px;font-weight:500;color:var(--text2);cursor:pointer;transition:background var(--ease);text-decoration:none;border:none;background:transparent;width:100%;text-align:left;}
.dd-item:hover{background:var(--surface2);color:var(--text);}
.dd-item svg{width:13px;height:13px;color:var(--text3);flex-shrink:0;}
.dd-item.accent{color:var(--a);}.dd-item.accent svg{color:var(--a);}
.dd-item.danger{color:var(--red);}.dd-item.danger svg{color:var(--red);}
.dd-sep{height:1px;background:var(--border);margin:3px 0;}

/* Hamburger */
.hamburger{display:none;width:34px;height:34px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);cursor:pointer;color:var(--text2);align-items:center;justify-content:center;flex-shrink:0;}
.hamburger svg{width:14px;height:14px;}

/* ─────────────────────────────────────────
   PAGE BODY
───────────────────────────────────────── */
.body{padding:22px 24px 60px;flex:1;}

/* ─────────────────────────────────────────
   FLASH MESSAGES
───────────────────────────────────────── */
.flash{display:flex;align-items:center;gap:10px;padding:12px 15px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:16px;animation:fadeUp .3s ease both;}
.flash svg{width:14px;height:14px;flex-shrink:0;}
.flash-success{background:var(--green-lt);color:var(--green);border:1px solid rgba(5,196,138,.22);}
.flash-error{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.22);}

/* ─────────────────────────────────────────
   COVER HERO CARD  ← FIXED
   Root causes of blank cover:
   1. cover-bg needs a min-height so it doesn't collapse to 0
   2. cover-card must NOT clip children that position:relative need
   3. hero-inner uses negative margin-top to overlay the avatar
───────────────────────────────────────── */
.cover-card{
  position:relative;
  border-radius:20px;
  background:var(--surface);
  border:1px solid var(--border);
  box-shadow:var(--sh);
  margin-bottom:18px;
  animation:fadeUp .4s ease both;
  /* CRITICAL FIX: overflow visible so avatar ring isn't clipped */
  overflow:visible;
}

/* The coloured cover strip */
.cover-bg{
  height:190px;
  position:relative;
  overflow:hidden;
  background:#07080f;
  border-radius:19px 19px 0 0; /* match card radius minus border */
  /* CRITICAL FIX: explicit display so it never collapses */
  display:block;
  min-height:190px;
}
.cover-bg::before{
  content:'';position:absolute;inset:0;
  background:
    radial-gradient(ellipse 70% 90% at 80% -10%,rgba(110,86,247,.55) 0%,transparent 60%),
    radial-gradient(ellipse 50% 60% at 10% 120%,rgba(155,109,255,.35) 0%,transparent 55%),
    radial-gradient(ellipse 40% 50% at 50% 50%,rgba(59,130,246,.12) 0%,transparent 60%);
  z-index:0;
}
.cover-bg::after{
  content:'';position:absolute;inset:0;
  background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);
  background-size:36px 36px;
  z-index:0;
}
.cover-bg > img.cover-img{
  width:100%;height:100%;
  object-fit:cover;
  display:block;
  position:relative;z-index:1;
}

.cover-edit-btn{
  position:absolute;bottom:12px;right:14px;z-index:10;
  display:flex;align-items:center;gap:6px;
  padding:6px 14px;border-radius:var(--r-sm);
  background:rgba(0,0,0,.42);border:1px solid rgba(255,255,255,.18);
  color:#fff;font-size:12px;font-weight:600;cursor:pointer;
  backdrop-filter:blur(8px);font-family:var(--font);
  transition:background var(--ease);
}
.cover-edit-btn:hover{background:rgba(0,0,0,.65);}
.cover-edit-btn svg{width:12px;height:12px;}

/* Hero lower section */
.hero-inner{
  padding:0 22px 20px;
  display:flex;align-items:flex-end;justify-content:space-between;
  flex-wrap:wrap;gap:14px;
  /* CRITICAL FIX: no overflow clipping on this element */
}

/* Avatar group */
.hero-av-group{display:flex;align-items:flex-end;gap:16px;flex-wrap:wrap;}
.profile-av-wrap{
  position:relative;
  /* CRITICAL FIX: negative margin pulls avatar up over the cover strip */
  margin-top:-44px;
  flex-shrink:0;
  /* ensure it sits above siblings */
  z-index:2;
}
.av-ring{
  width:82px;height:82px;border-radius:20px;
  border:4px solid var(--surface);
  background:linear-gradient(135deg,var(--a),var(--a2));
  display:flex;align-items:center;justify-content:center;
  font-size:26px;font-weight:800;color:#fff;font-family:var(--mono);
  overflow:hidden;
  box-shadow:0 6px 20px rgba(110,86,247,.32);
}
.av-ring img{width:100%;height:100%;object-fit:cover;}
.av-cam{
  position:absolute;bottom:-3px;right:-3px;
  width:24px;height:24px;border-radius:7px;
  background:var(--surface);border:1px solid var(--border2);
  display:flex;align-items:center;justify-content:center;
  cursor:pointer;color:var(--text2);
  transition:all var(--ease);box-shadow:var(--sh);
}
.av-cam:hover{background:var(--a);color:#fff;border-color:var(--a);}
.av-cam svg{width:11px;height:11px;}

.hero-meta{padding-bottom:3px;}
.hero-name{font-family:var(--mono);font-size:21px;font-weight:800;color:var(--text);letter-spacing:-.03em;margin-top:8px;line-height:1.15;}
.hero-handle{font-size:11.5px;color:var(--text3);font-family:var(--mono);margin-top:3px;}
.hero-badges{display:flex;gap:5px;margin-top:7px;flex-wrap:wrap;}
.hbadge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);}
.hb-role{background:var(--a-lt);color:var(--a);border:1px solid rgba(110,86,247,.18);}
.hb-verified{background:var(--green-lt);color:var(--green);border:1px solid rgba(5,196,138,.18);}
.hb-unverified{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.18);}

.hero-actions{display:flex;gap:7px;align-items:center;padding-bottom:3px;flex-shrink:0;}

/* Stat pills strip */
.stat-pills{
  display:flex;gap:0;
  border-top:1px solid var(--border);
  border-radius:0 0 18px 18px;
  overflow:hidden;
}
.stat-pill{
  flex:1;display:flex;flex-direction:column;align-items:center;
  padding:13px 8px;cursor:pointer;
  transition:background var(--ease);
  border-right:1px solid var(--border);
  position:relative;
}
.stat-pill:last-child{border-right:none;}
.stat-pill:hover{background:var(--surface2);}
.stat-pill.active{background:var(--a-lt);}
.stat-pill.active::after{content:'';position:absolute;bottom:0;left:20%;right:20%;height:2px;background:var(--a);border-radius:2px 2px 0 0;}
.sp-val{font-family:var(--mono);font-size:17px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1;}
.sp-lbl{font-size:9.5px;font-weight:600;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:.07em;margin-top:3px;}
.sp-icon{display:flex;align-items:center;justify-content:center;height:17px;}

/* ─────────────────────────────────────────
   CONTENT GRID
───────────────────────────────────────── */
.profile-grid{display:grid;grid-template-columns:290px 1fr;gap:16px;align-items:start;}

/* ─────────────────────────────────────────
   CARDS
───────────────────────────────────────── */
.card{
  background:var(--surface);border:1px solid var(--border);
  border-radius:var(--r);box-shadow:var(--sh);
  margin-bottom:14px;overflow:hidden;
  animation:fadeUp .4s ease both;
  transition:box-shadow var(--ease);
}
.card:hover{box-shadow:var(--sh-md);}
.card-head{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid var(--border);}
.card-head-left{display:flex;align-items:center;gap:9px;}
.card-ico{width:30px;height:30px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-ico svg{width:14px;height:14px;}
.card-ttl{font-family:var(--mono);font-size:13.5px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-sub{font-size:10px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.card-edit-btn{font-size:11.5px;font-weight:600;color:var(--a);padding:5px 10px;border-radius:var(--r-xs);border:1px solid rgba(110,86,247,.18);background:var(--a-lt);cursor:pointer;transition:all var(--ease);}
.card-edit-btn:hover{background:var(--a);color:#fff;}
.card-body{padding:15px 16px;}

/* Info rows */
.info-row{display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--surface3);}
.info-row:last-child{border-bottom:none;padding-bottom:0;}
.ir-left{display:flex;align-items:center;gap:7px;font-size:12px;color:var(--text3);}
.ir-left svg{width:12px;height:12px;flex-shrink:0;}
.ir-val{font-size:11.5px;font-weight:600;color:var(--text);font-family:var(--mono);}

/* Activity stats grid */
.activity-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.act-stat{border-radius:var(--r-sm);padding:13px;text-align:center;}
.act-stat-val{font-family:var(--mono);font-size:20px;font-weight:800;letter-spacing:-.02em;line-height:1;}
.act-stat-lbl{font-size:9.5px;font-weight:700;font-family:var(--mono);text-transform:uppercase;letter-spacing:.07em;margin-top:3px;}

/* ─────────────────────────────────────────
   TABS
───────────────────────────────────────── */
.tab-bar{
  display:flex;gap:2px;
  background:var(--surface2);border:1px solid var(--border);
  padding:3px;border-radius:13px;
  margin-bottom:16px;
}
.tab-btn{
  flex:1;padding:7px 12px;border-radius:10px;
  font-size:12px;font-weight:600;cursor:pointer;
  border:none;background:transparent;color:var(--text3);
  transition:all var(--ease);font-family:var(--font);
  display:flex;align-items:center;justify-content:center;gap:5px;
  white-space:nowrap;
}
.tab-btn:hover{color:var(--a);}
.tab-btn.on{background:var(--surface);color:var(--a);box-shadow:0 1px 6px rgba(110,86,247,.12);}
.tab-cnt{display:inline-flex;align-items:center;justify-content:center;min-width:17px;height:17px;border-radius:100px;font-size:9.5px;padding:0 4px;background:var(--a-lt);color:var(--a);font-weight:700;font-family:var(--mono);}
.tab-btn.on .tab-cnt{background:var(--a);color:#fff;}
.tab-content{display:none;}
.tab-content.on{display:block;}

/* ─────────────────────────────────────────
   CAMPAIGN CARDS
───────────────────────────────────────── */
.camp-card{
  background:var(--surface);border:1px solid var(--border);
  border-radius:var(--r);overflow:hidden;box-shadow:var(--sh);
  margin-bottom:14px;
  transition:transform var(--ease),box-shadow var(--ease),border-color var(--ease);
  animation:fadeUp .4s ease both;
}
.camp-card:hover{transform:translateY(-3px);box-shadow:var(--sh-md);border-color:rgba(110,86,247,.15);}
.camp-thumb{position:relative;}
.camp-thumb img{width:100%;height:170px;object-fit:cover;display:block;}
.camp-placeholder{width:100%;height:130px;background:var(--surface2);display:flex;align-items:center;justify-content:center;border-bottom:1px solid var(--border);}
.camp-placeholder svg{width:26px;height:26px;color:var(--text3);opacity:.22;}
.camp-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.48) 0%,transparent 55%);}
.camp-badge-wrap{position:absolute;top:10px;left:10px;display:flex;gap:5px;flex-wrap:wrap;}
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3px 8px;border-radius:6px;text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);}
.b-active{background:rgba(5,196,138,.85);color:#fff;}
.b-pending{background:rgba(245,158,11,.85);color:#fff;}
.b-rejected{background:rgba(240,68,68,.85);color:#fff;}
.b-inactive{background:rgba(107,114,128,.75);color:#fff;}
.b-paused{background:rgba(110,86,247,.85);color:#fff;}
.camp-body{padding:14px 15px 15px;}
.camp-title{font-size:14.5px;font-weight:700;color:var(--text);font-family:var(--mono);letter-spacing:-.01em;margin-bottom:5px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.3;}
.camp-desc{font-size:12px;color:var(--text2);line-height:1.6;margin-bottom:12px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.prog{margin-bottom:12px;}
.prog-nums{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:6px;}
.prog-raised{font-family:var(--mono);font-size:15px;font-weight:800;color:var(--text);letter-spacing:-.02em;}
.prog-goal{font-size:11px;color:var(--text3);font-family:var(--mono);}
.prog-bar{width:100%;background:var(--surface3);border-radius:100px;height:5px;overflow:hidden;margin-bottom:4px;}
.prog-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--a),var(--a2));transition:width .9s ease;}
.prog-fill-gray{background:linear-gradient(90deg,#94a3b8,#64748b);}
.prog-pct{font-size:10px;color:var(--a);font-weight:600;font-family:var(--mono);}
.camp-footer{display:flex;align-items:center;justify-content:space-between;padding:10px 15px;border-top:1px solid var(--border);flex-wrap:wrap;gap:7px;background:var(--surface2);}
.cf-meta{display:flex;align-items:center;gap:12px;font-size:11px;color:var(--text3);}
.cf-meta span{display:flex;align-items:center;gap:4px;font-weight:500;}
.cf-meta svg{width:11px;height:11px;}
.cf-actions{display:flex;gap:5px;}

/* ─────────────────────────────────────────
   BUTTONS
───────────────────────────────────────── */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:8px 15px;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;cursor:pointer;border:1px solid var(--border2);background:var(--surface2);color:var(--text2);font-family:var(--font);transition:all var(--ease);text-decoration:none;white-space:nowrap;}
.btn:hover{background:var(--surface3);color:var(--text);}
.btn svg{width:12px;height:12px;}
.btn:active{transform:scale(.97);}
.btn-sm{padding:6px 10px;font-size:11.5px;}
.btn-primary{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;box-shadow:0 4px 14px rgba(110,86,247,.32);}
.btn-primary:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(110,86,247,.45);color:#fff;}
.btn-ghost{background:transparent;color:var(--text2);border-color:var(--border2);}
.btn-ghost:hover{background:var(--surface2);color:var(--text);}
.btn-danger{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.18);}
.btn-danger:hover{background:var(--red);color:#fff;}

/* ─────────────────────────────────────────
   FORMS
───────────────────────────────────────── */
.field{margin-bottom:14px;}
.field:last-child{margin-bottom:0;}
.field label{display:block;font-size:9.5px;font-weight:700;color:var(--text3);margin-bottom:5px;letter-spacing:.1em;text-transform:uppercase;font-family:var(--mono);}
.field input,.field textarea,.field select{width:100%;border:1px solid var(--border2);border-radius:var(--r-sm);padding:9px 12px;font-family:var(--font);font-size:13px;color:var(--text);background:var(--surface2);outline:none;resize:vertical;transition:border-color var(--ease),background var(--ease),box-shadow var(--ease);}
.field input:focus,.field textarea:focus,.field select:focus{border-color:var(--a);background:var(--surface);box-shadow:0 0 0 3px var(--a-glow);}
.field input::placeholder,.field textarea::placeholder{color:var(--text3);}
.field input[readonly]{opacity:.45;cursor:not-allowed;}
.field-err{font-size:10.5px;color:var(--red);margin-top:4px;font-family:var(--mono);font-weight:600;}
.field-hint{font-size:10.5px;color:var(--text3);margin-top:3px;}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.pw-wrap{position:relative;}
.pw-wrap input{padding-right:40px;}
.pw-eye{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text3);display:flex;padding:0;transition:color var(--ease);}
.pw-eye:hover{color:var(--text2);}
.pw-eye svg{width:14px;height:14px;}
.save-btn{width:100%;padding:10px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:13px;font-weight:700;cursor:pointer;font-family:var(--font);transition:all var(--ease);box-shadow:0 4px 14px rgba(110,86,247,.28);}
.save-btn:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(110,86,247,.4);}
.save-btn.ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border2);box-shadow:none;}
.save-btn.ghost:hover{background:var(--surface3);transform:none;}
.save-btn.danger{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.18);box-shadow:none;}
.save-btn.danger:hover{background:var(--red);color:#fff;transform:none;}

/* ─────────────────────────────────────────
   ACCOUNT DETAIL GRID
───────────────────────────────────────── */
.acct-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.acct-item{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:11px 13px;}
.acct-lbl{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:4px;}
.acct-val{font-size:12.5px;font-weight:600;color:var(--text);}

/* ─────────────────────────────────────────
   SETTINGS ROWS
───────────────────────────────────────── */
.setting-row{display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--border);}
.setting-row:last-child{border-bottom:none;}
.setting-lbl{font-size:13px;font-weight:500;color:var(--text);}
.setting-sub{font-size:11px;color:var(--text3);margin-top:2px;}
.setting-row select{padding:6px 10px;border:1px solid var(--border2);border-radius:var(--r-xs);font-size:12px;background:var(--surface2);color:var(--text);font-family:var(--font);outline:none;}
/* Toggle switch */
.toggle-sw{position:relative;width:40px;height:22px;flex-shrink:0;}
.toggle-sw input{opacity:0;width:0;height:0;position:absolute;}
.toggle-track{display:block;width:100%;height:100%;border-radius:100px;background:var(--surface3);border:1px solid var(--border2);cursor:pointer;transition:background .2s,border-color .2s;position:relative;}
.toggle-track::after{content:'';position:absolute;width:16px;height:16px;border-radius:50%;background:#fff;top:2px;left:2px;transition:transform .2s;box-shadow:0 1px 4px rgba(0,0,0,.2);}
.toggle-sw input:checked ~ .toggle-track{background:var(--a);border-color:var(--a);}
.toggle-sw input:checked ~ .toggle-track::after{transform:translateX(18px);}

/* ─────────────────────────────────────────
   EMPTY STATE
───────────────────────────────────────── */
.empty-state{text-align:center;padding:44px 20px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);}
.empty-icon{width:52px;height:52px;border-radius:15px;background:var(--a-lt);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;}
.empty-icon svg{width:22px;height:22px;color:var(--a);}
.empty-state h3{font-family:var(--mono);font-size:16px;font-weight:700;color:var(--text);margin-bottom:5px;}
.empty-state p{font-size:12.5px;color:var(--text3);margin-bottom:18px;}

/* ─────────────────────────────────────────
   TOAST
───────────────────────────────────────── */
.toast-wrap{position:fixed;top:18px;right:18px;z-index:9999;display:flex;flex-direction:column;gap:7px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:9px;padding:12px 15px;border-radius:13px;font-size:13px;font-weight:500;color:#fff;min-width:260px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .28s ease both;}
.toast svg{width:14px;height:14px;flex-shrink:0;}
.toast-ok{background:linear-gradient(135deg,#059669,#10b981);}
.toast-err{background:linear-gradient(135deg,#dc2626,#f04444);}
.toast-x{margin-left:auto;width:18px;height:18px;border-radius:5px;background:rgba(255,255,255,.2);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}

/* ─────────────────────────────────────────
   UPLOAD MODAL
───────────────────────────────────────── */
.overlay{display:none;position:fixed;inset:0;z-index:9998;background:rgba(4,5,14,.65);backdrop-filter:blur(12px);align-items:center;justify-content:center;padding:20px;}
.overlay.open{display:flex;}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:20px;box-shadow:var(--sh-lg);width:100%;max-width:380px;padding:22px;position:relative;animation:modalIn .2s ease;}
.modal-x{position:absolute;top:14px;right:14px;width:26px;height:26px;border-radius:8px;border:1px solid var(--border2);background:var(--surface2);cursor:pointer;color:var(--text2);display:flex;align-items:center;justify-content:center;transition:all var(--ease);}
.modal-x:hover{background:var(--border2);transform:rotate(90deg);}
.modal-x svg{width:10px;height:10px;}
.modal-ttl{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text);margin-bottom:2px;letter-spacing:-.02em;}
.modal-sub{font-size:12px;color:var(--text3);margin-bottom:14px;}
.modal-preview{width:100%;max-height:190px;object-fit:cover;border-radius:var(--r-sm);margin-bottom:14px;border:1px solid var(--border);}
.modal-acts{display:flex;gap:8px;}
.modal-btn{flex:1;padding:10px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;border:none;font-family:var(--font);transition:all var(--ease);}
.modal-btn:hover{transform:translateY(-1px);}
.modal-cancel{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.modal-confirm{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 14px rgba(110,86,247,.28);}

/* ─────────────────────────────────────────
   SCROLLBARS
───────────────────────────────────────── */
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}

/* ─────────────────────────────────────────
   ANIMATIONS
───────────────────────────────────────── */
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
@keyframes toastIn{from{opacity:0;transform:translateX(16px) scale(.96)}to{opacity:1;transform:none}}
@keyframes modalIn{from{opacity:0;transform:scale(.95) translateY(10px)}to{opacity:1;transform:none}}

/* ─────────────────────────────────────────
   RESPONSIVE
───────────────────────────────────────── */
@media(max-width:1100px){.profile-grid{grid-template-columns:250px 1fr;}}
@media(max-width:900px){.profile-grid{grid-template-columns:1fr;}.acct-grid{grid-template-columns:1fr;}.two-col{grid-template-columns:1fr;}}
@media(max-width:860px){.sidebar{transform:translateX(-100%);}.sidebar.open{transform:translateX(0);}.main{margin-left:0;}.hamburger{display:flex;}}
@media(max-width:640px){.topbar,.body{padding-left:14px;padding-right:14px;}.stat-pills{flex-wrap:wrap;}.stat-pill{flex:1 1 33%;}.hero-inner{padding:0 14px 16px;}.hero-name{font-size:18px;}.tab-btn span{display:none;}}
</style>
</head>
<body>

{{-- Toast container --}}
<div class="toast-wrap" id="toastWrap"></div>

{{-- Hidden upload forms --}}
<form id="avatarForm" action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" style="display:none">
  @csrf <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp">
</form>
<form id="coverForm" action="{{ route('profile.cover') }}" method="POST" enctype="multipart/form-data" style="display:none">
  @csrf <input type="file" name="cover_image" id="coverInput" accept="image/jpeg,image/png,image/webp">
</form>

{{-- Upload preview modal --}}
<div class="overlay" id="uploadModal">
  <div class="modal">
    <button type="button" class="modal-x" onclick="cancelUpload()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
    <div class="modal-ttl" id="modalTitle">Confirm upload</div>
    <div class="modal-sub">Does this look good?</div>
    <img class="modal-preview" id="modalPreviewImg" src="" alt="Preview">
    <div class="modal-acts">
      <button type="button" class="modal-btn modal-cancel" onclick="cancelUpload()">Cancel</button>
      <button type="button" class="modal-btn modal-confirm" id="confirmUploadBtn">Upload</button>
    </div>
  </div>
</div>

@php
  $campaignCount = 0;
  $donationCount = 0;
  $donationTotal = 0;
  try { if(method_exists($user,'campaigns')) $campaignCount = $user->campaigns()->count(); } catch(\Throwable $e){}
  try { if(method_exists($user,'donations')) { $donationCount = $user->donations()->count(); $donationTotal = $user->donations()->sum('amount'); } } catch(\Throwable $e){}
  $userCampaigns = collect();
  try { if(method_exists($user,'campaigns')) $userCampaigns = $user->campaigns()->latest()->get(); } catch(\Throwable $e){}
@endphp

<div class="shell">

  {{-- ═══════════════════════════════════════
       SIDEBAR
  ═══════════════════════════════════════ --}}
  <aside class="sidebar" id="sidebar">
    <div class="s-logo">
      <div class="s-logo-mark">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
      </div>
      <div>
        <div class="s-logo-name">DonateBazaar</div>
        <div class="s-logo-tag">My Profile</div>
      </div>
    </div>

    <div class="s-user-pill">
      <div class="s-uav">
        @if($user->avatar)
          <img src="{{ asset('storage/'.$user->avatar) }}" alt="">
        @else
          {{ strtoupper(substr($user->name,0,1)) }}
        @endif
      </div>
      <div style="flex:1;overflow:hidden;">
        <div class="s-uname">{{ $user->name }}</div>
        <div class="s-urole">{{ ucfirst($user->role ?? 'Donor') }}</div>
      </div>
      <div class="s-online"></div>
    </div>

    <div class="s-section">Navigate</div>
    <nav class="s-nav">
      <a href="{{ $user->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="s-link">
        <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        Dashboard
      </a>
      @if(Route::has('campaigns.create'))
      <a href="{{ route('campaigns.create') }}" class="s-link">
        <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
        </svg>
        New Campaign
      </a>
      @endif
    </nav>

    <div class="s-section">Profile</div>
    <nav class="s-nav">
      <button class="s-link active" id="sl-campaigns" onclick="switchTab('campaigns')">
        <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
          <line x1="4" y1="22" x2="4" y2="15"/>
        </svg>
        Campaigns
        <span class="s-chip sc-purple">{{ $campaignCount }}</span>
      </button>
      <button class="s-link" id="sl-about" onclick="switchTab('about')">
        <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        About &amp; Edit
      </button>
      <button class="s-link" id="sl-settings" onclick="switchTab('settings')">
        <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="3"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
        </svg>
        Settings
      </button>
    </nav>

    <div class="s-divider"></div>
    <div class="s-bottom">
      <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('lf').submit();" class="s-link" style="color:var(--red);">
        <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
        </svg>
        Sign Out
      </a>
      <form id="lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>
  </aside>

  {{-- ═══════════════════════════════════════
       MAIN
  ═══════════════════════════════════════ --}}
  <div class="main">

    {{-- TOPBAR --}}
    <header class="topbar">
      <div class="tb-left">
        <button class="hamburger" id="hamburger" aria-label="Menu">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <div>
          <div class="tb-title">My Profile</div>
          <div class="tb-sub">{{ $user->email }}</div>
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
            @if($user->avatar)<img src="{{ asset('storage/'.$user->avatar) }}" alt="">@else{{ strtoupper(substr($user->name,0,1)) }}@endif
          </div>
          <div class="av-dd" id="avDD">
            <div class="dd-hdr">
              <div class="dd-name">{{ $user->name }}</div>
              <div class="dd-email">{{ $user->email }}</div>
            </div>
            <button type="button" class="dd-item accent" onclick="switchTab('about');document.getElementById('avDD').classList.remove('open')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit Profile
            </button>
            <div class="dd-sep"></div>
            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('lf').submit();" class="dd-item danger">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
              Sign Out
            </a>
          </div>
        </div>
      </div>
    </header>

    {{-- PAGE BODY --}}
    <div class="body">

      {{-- Flash messages --}}
      @if(session('success'))
      <div class="flash flash-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
      </div>
      @endif
      @if(session('error'))
      <div class="flash flash-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ session('error') }}
      </div>
      @endif
      @if($errors->any())
      <div class="flash flash-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ $errors->first() }}
      </div>
      @endif

      {{-- ═══════════════════════════════════
           COVER / HERO CARD
           FIXED: overflow:visible on .cover-card,
           explicit min-height on .cover-bg,
           border-radius on cover-bg instead of parent clip
      ═══════════════════════════════════ --}}
      <div class="cover-card">

        {{-- Cover strip --}}
        <div class="cover-bg" id="coverBg">
          @if($user->cover_image)
            <img class="cover-img" src="{{ asset('storage/'.$user->cover_image) }}" id="coverImg" alt="Cover">
          @else
            <img class="cover-img" src="" id="coverImg" style="display:none;" alt="">
          @endif
          <button type="button" class="cover-edit-btn" onclick="document.getElementById('coverInput').click()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
              <circle cx="12" cy="13" r="4"/>
            </svg>
            {{ $user->cover_image ? 'Edit cover' : 'Add cover' }}
          </button>
        </div>

        {{-- Hero inner --}}
        <div class="hero-inner">
          <div class="hero-av-group">
            {{-- Avatar with camera button --}}
            <div class="profile-av-wrap">
              <div class="av-ring" id="avatarRing">
                @if($user->avatar)
                  <img src="{{ asset('storage/'.$user->avatar) }}" id="avatarImg" alt="">
                @else
                  <span id="avatarInitials">{{ strtoupper(substr($user->name,0,1)) }}</span>
                  <img src="" id="avatarImg" style="display:none;" alt="">
                @endif
              </div>
              <button type="button" class="av-cam" onclick="document.getElementById('avatarInput').click()" title="Change photo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                  <circle cx="12" cy="13" r="4"/>
                </svg>
              </button>
            </div>

            {{-- Name & badges --}}
            <div class="hero-meta">
              <div class="hero-name">{{ $user->name }}</div>
              <div class="hero-handle">&#64;{{ strtolower(str_replace(' ','_',$user->name)) }} · Joined {{ $user->created_at->format('M Y') }}</div>
              <div class="hero-badges">
                <span class="hbadge hb-role">
                  <svg width="9" height="9" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                  {{ ucfirst($user->role ?? 'Donor') }}
                </span>
                @if($user->email_verified_at)
                  <span class="hbadge hb-verified">✓ Verified</span>
                @else
                  <span class="hbadge hb-unverified">! Unverified</span>
                @endif
              </div>
            </div>
          </div>

          {{-- Hero action buttons --}}
          <div class="hero-actions">
            <button class="btn btn-ghost" onclick="switchTab('about')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit Profile
            </button>
            @if(Route::has('campaigns.create'))
            <a href="{{ route('campaigns.create') }}" class="btn btn-primary">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              New Campaign
            </a>
            @endif
          </div>
        </div>

        {{-- Stat pills strip --}}
        <div class="stat-pills">
          <div class="stat-pill active" id="pill-campaigns" onclick="switchTab('campaigns')">
            <div class="sp-val">{{ $campaignCount }}</div>
            <div class="sp-lbl">Campaigns</div>
          </div>
          <div class="stat-pill" id="pill-donations">
            <div class="sp-val">{{ $donationCount }}</div>
            <div class="sp-lbl">Donations</div>
          </div>
          <div class="stat-pill" id="pill-raised">
            <div class="sp-val" style="font-size:14px;">₹{{ number_format($donationTotal) }}</div>
            <div class="sp-lbl">Raised</div>
          </div>
          <div class="stat-pill" id="pill-about" onclick="switchTab('about')">
            <div class="sp-icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div class="sp-lbl">Edit Info</div>
          </div>
          <div class="stat-pill" id="pill-settings" onclick="switchTab('settings')">
            <div class="sp-icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="2"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
            </div>
            <div class="sp-lbl">Settings</div>
          </div>
        </div>
      </div>{{-- /cover-card --}}

      {{-- ═══════════════════════════════════
           PROFILE GRID
      ═══════════════════════════════════ --}}
      <div class="profile-grid">

        {{-- ── LEFT SIDEBAR ── --}}
        <div>

          {{-- Intro card --}}
          <div class="card" style="animation-delay:.06s;">
            <div class="card-head">
              <div class="card-head-left">
                <div class="card-ico" style="background:var(--a-lt);">
                  <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                  <div class="card-ttl">Intro</div>
                  <div class="card-sub">Public info</div>
                </div>
              </div>
              <button class="card-edit-btn" onclick="switchTab('about')">Edit</button>
            </div>
            <div class="card-body">
              @if($user->bio)
              <p style="font-size:12.5px;color:var(--text2);line-height:1.7;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid var(--border);">{{ $user->bio }}</p>
              @endif
              <div class="info-row">
                <span class="ir-left"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Role</span>
                <span class="ir-val">{{ ucfirst($user->role ?? 'Donor') }}</span>
              </div>
              @if($user->phone)
              <div class="info-row">
                <span class="ir-left"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.63A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z"/></svg>Phone</span>
                <span class="ir-val">{{ $user->phone }}</span>
              </div>
              @endif
              <div class="info-row">
                <span class="ir-left"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>Email</span>
                <span class="ir-val" style="font-size:10.5px;max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $user->email }}</span>
              </div>
              <div class="info-row">
                <span class="ir-left"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Joined</span>
                <span class="ir-val">{{ $user->created_at->format('d M Y') }}</span>
              </div>
              <div class="info-row">
                <span class="ir-left"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>Verified</span>
                <span class="ir-val" style="color:{{ $user->email_verified_at ? 'var(--green)' : 'var(--red)' }}">
                  {{ $user->email_verified_at ? 'Yes ✓' : 'No' }}
                </span>
              </div>
            </div>
          </div>

          {{-- Activity card --}}
          <div class="card" style="animation-delay:.1s;">
            <div class="card-head">
              <div class="card-head-left">
                <div class="card-ico" style="background:var(--green-lt);">
                  <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div>
                  <div class="card-ttl">Activity</div>
                  <div class="card-sub">Your impact stats</div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="activity-grid">
                <div class="act-stat" style="background:var(--a-lt);border:1px solid rgba(110,86,247,.14);">
                  <div class="act-stat-val" style="color:var(--a);">{{ $campaignCount }}</div>
                  <div class="act-stat-lbl" style="color:var(--a);">Campaigns</div>
                </div>
                <div class="act-stat" style="background:var(--green-lt);border:1px solid rgba(5,196,138,.14);">
                  <div class="act-stat-val" style="color:var(--green);">{{ $donationCount }}</div>
                  <div class="act-stat-lbl" style="color:var(--green);">Donations</div>
                </div>
              </div>
              <div class="act-stat" style="background:var(--amber-lt);border:1px solid rgba(245,158,11,.14);border-radius:var(--r-sm);padding:13px;text-align:center;margin-top:8px;">
                <div class="act-stat-val" style="color:var(--amber);">₹{{ number_format($donationTotal) }}</div>
                <div class="act-stat-lbl" style="color:var(--amber);">Total Raised</div>
              </div>
            </div>
          </div>

        </div>{{-- /left --}}

        {{-- ── RIGHT: TAB CONTENT ── --}}
        <div>
          {{-- Tab bar --}}
          <div class="tab-bar">
            <button class="tab-btn on" id="tb-campaigns" onclick="switchTab('campaigns')">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
              <span>Campaigns</span> <span class="tab-cnt">{{ $campaignCount }}</span>
            </button>
            <button class="tab-btn" id="tb-about" onclick="switchTab('about')">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              <span>About &amp; Edit</span>
            </button>
            <button class="tab-btn" id="tb-settings" onclick="switchTab('settings')">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
              <span>Settings</span>
            </button>
          </div>

          {{-- ══ CAMPAIGNS TAB ══ --}}
          <div id="tc-campaigns" class="tab-content on">
            @forelse($userCampaigns as $i => $campaign)
            @php
              $goal     = $campaign->goal_amount ?? $campaign->goal ?? 0;
              $raised   = $campaign->raised_amount ?? $campaign->raised ?? 0;
              $pct      = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
              $status   = $campaign->status ?? 'active';
              $deadline = isset($campaign->end_date) ? \Carbon\Carbon::parse($campaign->end_date) : null;
              $daysLeft = $deadline ? max(0, now()->diffInDays($deadline, false)) : null;
              $donorCnt = 0;
              try { $donorCnt = $campaign->donations()->count(); } catch(\Throwable $e){ $donorCnt = $campaign->donors_count ?? 0; }
              $campId   = $campaign->id ?? '';
              $statusClass = match($status) { 'active'=>'b-active','pending'=>'b-pending','rejected'=>'b-rejected','paused'=>'b-paused', default=>'b-inactive' };
            @endphp
            <div class="camp-card" style="animation-delay:{{ $i * 0.05 }}s;">
              <div class="camp-thumb">
                @if(!empty($campaign->cover_image))
                  <img src="{{ asset('storage/'.$campaign->cover_image) }}" alt="{{ $campaign->title }}">
                  <div class="camp-overlay"></div>
                @elseif(!empty($campaign->image))
                  <img src="{{ asset('storage/'.$campaign->image) }}" alt="{{ $campaign->title }}">
                  <div class="camp-overlay"></div>
                @else
                  <div class="camp-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                  </div>
                @endif
                <div class="camp-badge-wrap">
                  <span class="badge {{ $statusClass }}">{{ ucfirst($status) }}</span>
                  @if($daysLeft !== null && $daysLeft <= 7 && $status === 'active')
                    <span class="badge" style="background:rgba(240,68,68,.85);color:#fff;">
                      {{ $daysLeft == 0 ? 'Last day!' : $daysLeft.' day'.($daysLeft!=1?'s':'').' left' }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="camp-body">
                <div class="camp-title">{{ $campaign->title }}</div>
                @if(!empty($campaign->description))
                  <div class="camp-desc">{{ $campaign->description }}</div>
                @endif
                @if($goal > 0)
                <div class="prog">
                  <div class="prog-nums">
                    <span class="prog-raised">₹{{ number_format($raised) }}</span>
                    <span class="prog-goal">of ₹{{ number_format($goal) }}</span>
                  </div>
                  <div class="prog-bar">
                    <div class="prog-fill {{ in_array($status,['inactive','expired','completed']) ? 'prog-fill-gray' : '' }}" style="width:{{ $pct }}%"></div>
                  </div>
                  <div class="prog-pct" style="{{ in_array($status,['inactive','expired','completed']) ? 'color:#64748b' : '' }}">{{ $pct }}% funded</div>
                </div>
                @endif
              </div>
              <div class="camp-footer">
                <div class="cf-meta">
                  <span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    {{ $donorCnt }} donor{{ $donorCnt !== 1 ? 's' : '' }}
                  </span>
                  @if($deadline)
                  <span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ $deadline->format('d M Y') }}
                  </span>
                  @endif
                </div>
                <div class="cf-actions">
                  <button class="btn btn-sm" type="button"
                    onclick="if(navigator.clipboard){navigator.clipboard.writeText(window.location.origin+'/campaigns/{{ $campId }}').then(function(){toast('Link copied!','ok')})}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                    Share
                  </button>
                  @if(Route::has('campaigns.edit') && isset($campaign->id))
                  <a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn btn-sm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                  </a>
                  @endif
                  @if(Route::has('campaigns.show') && isset($campaign->id))
                  <a href="{{ route('campaigns.show', $campaign->id) }}" class="btn btn-sm btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View
                  </a>
                  @endif
                </div>
              </div>
            </div>
            @empty
            <div class="empty-state">
              <div class="empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
              </div>
              <h3>No campaigns yet</h3>
              <p>Start your first campaign and make a real difference.</p>
              @if(Route::has('campaigns.create'))
                <a href="{{ route('campaigns.create') }}" class="btn btn-primary" style="display:inline-flex;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  Start a Campaign
                </a>
              @endif
            </div>
            @endforelse
          </div>

          {{-- ══ ABOUT & EDIT TAB ══ --}}
          <div id="tc-about" class="tab-content">

            {{-- Personal info --}}
            <div class="card" style="margin-bottom:14px;">
              <div class="card-head">
                <div class="card-head-left">
                  <div class="card-ico" style="background:var(--a-lt);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </div>
                  <div>
                    <div class="card-ttl">Personal Info</div>
                    <div class="card-sub">Update your public profile</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                  @csrf @method('PATCH')
                  <div class="two-col">
                    <div class="field">
                      <label>Full name</label>
                      <input type="text" name="name" value="{{ old('name',$user->name) }}" placeholder="Your full name" required>
                      @error('name')<div class="field-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                      <label>Phone number</label>
                      <input type="text" name="phone" value="{{ old('phone',$user->phone) }}" placeholder="+91 XXXXX XXXXX">
                      @error('phone')<div class="field-err">{{ $message }}</div>@enderror
                    </div>
                  </div>
                  <div class="field">
                    <label>Email address</label>
                    <input type="email" value="{{ $user->email }}" readonly>
                    <div class="field-hint">Email cannot be changed from here.</div>
                  </div>
                  <div class="field">
                    <label>Bio</label>
                    <textarea name="bio" rows="3" placeholder="Tell people a little about yourself...">{{ old('bio',$user->bio) }}</textarea>
                    @error('bio')<div class="field-err">{{ $message }}</div>@enderror
                  </div>
                  <button type="submit" class="save-btn">Save Changes</button>
                </form>
              </div>
            </div>

            {{-- Change password --}}
            <div class="card" style="margin-bottom:14px;">
              <div class="card-head">
                <div class="card-head-left">
                  <div class="card-ico" style="background:var(--amber-lt);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/></svg>
                  </div>
                  <div>
                    <div class="card-ttl">Change Password</div>
                    <div class="card-sub">Keep your account secure</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form action="{{ route('profile.password') }}" method="POST">
                  @csrf
                  <div class="field">
                    <label>Current Password</label>
                    <div class="pw-wrap">
                      <input type="password" name="current_password" id="pw-cur" placeholder="Enter current password">
                      <button type="button" class="pw-eye" onclick="toggleEye('pw-cur',this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                      </button>
                    </div>
                    @error('current_password')<div class="field-err">{{ $message }}</div>@enderror
                  </div>
                  <div class="two-col">
                    <div class="field">
                      <label>New Password</label>
                      <div class="pw-wrap">
                        <input type="password" name="password" id="pw-new" placeholder="Min 8 characters">
                        <button type="button" class="pw-eye" onclick="toggleEye('pw-new',this)">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                      </div>
                      @error('password')<div class="field-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                      <label>Confirm Password</label>
                      <input type="password" name="password_confirmation" placeholder="Repeat new password">
                    </div>
                  </div>
                  <button type="submit" class="save-btn ghost">Update Password</button>
                </form>
              </div>
            </div>

            {{-- Account details (read-only) --}}
            <div class="card">
              <div class="card-head">
                <div class="card-head-left">
                  <div class="card-ico" style="background:var(--blue-lt);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                  </div>
                  <div>
                    <div class="card-ttl">Account Details</div>
                    <div class="card-sub">Your account metadata</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="acct-grid">
                  <div class="acct-item">
                    <div class="acct-lbl">Member Since</div>
                    <div class="acct-val">{{ $user->created_at->format('d M Y') }}</div>
                  </div>
                  <div class="acct-item">
                    <div class="acct-lbl">Account Role</div>
                    <div class="acct-val">{{ ucfirst($user->role ?? 'Donor') }}</div>
                  </div>
                  <div class="acct-item">
                    <div class="acct-lbl">Email Verified</div>
                    <div class="acct-val" style="color:{{ $user->email_verified_at ? 'var(--green)' : 'var(--red)' }}">
                      {{ $user->email_verified_at ? 'Verified ✓' : 'Not verified' }}
                    </div>
                  </div>
                  <div class="acct-item">
                    <div class="acct-lbl">Account Status</div>
                    <div class="acct-val">{{ ucfirst($user->status ?? 'Active') }}</div>
                  </div>
                </div>
              </div>
            </div>

          </div>{{-- /tc-about --}}

          {{-- ══ SETTINGS TAB ══ --}}
          <div id="tc-settings" class="tab-content">

            {{-- Privacy & notifications --}}
            <div class="card" style="margin-bottom:14px;">
              <div class="card-head">
                <div class="card-head-left">
                  <div class="card-ico" style="background:var(--a-lt);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/></svg>
                  </div>
                  <div>
                    <div class="card-ttl">Privacy Settings</div>
                    <div class="card-sub">Control your visibility</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="setting-row">
                  <div>
                    <div class="setting-lbl">Profile visibility</div>
                    <div class="setting-sub">Who can view your profile page</div>
                  </div>
                  <select>
                    <option>Everyone</option>
                    <option>Only me</option>
                  </select>
                </div>
                <div class="setting-row">
                  <div>
                    <div class="setting-lbl">Email notifications</div>
                    <div class="setting-sub">Receive updates about your campaigns</div>
                  </div>
                  <div class="toggle-sw">
                    <input type="checkbox" id="ts-notif" checked>
                    <div class="toggle-track" onclick="document.getElementById('ts-notif').click()"></div>
                  </div>
                </div>
                <div class="setting-row">
                  <div>
                    <div class="setting-lbl">Show donation history</div>
                    <div class="setting-sub">Make donations publicly visible</div>
                  </div>
                  <div class="toggle-sw">
                    <input type="checkbox" id="ts-dh">
                    <div class="toggle-track" onclick="document.getElementById('ts-dh').click()"></div>
                  </div>
                </div>
                <div class="setting-row">
                  <div>
                    <div class="setting-lbl">Campaign updates</div>
                    <div class="setting-sub">Get notified about campaigns you follow</div>
                  </div>
                  <div class="toggle-sw">
                    <input type="checkbox" id="ts-cu" checked>
                    <div class="toggle-track" onclick="document.getElementById('ts-cu').click()"></div>
                  </div>
                </div>
                <div style="margin-top:16px;">
                  <button class="save-btn" type="button" onclick="toast('Settings saved!','ok')">Save Settings</button>
                </div>
              </div>
            </div>

            {{-- Danger zone --}}
            <div class="card" style="border-color:rgba(240,68,68,.2);">
              <div class="card-head" style="border-color:rgba(240,68,68,.12);">
                <div class="card-head-left">
                  <div class="card-ico" style="background:var(--red-lt);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                  </div>
                  <div>
                    <div class="card-ttl" style="color:var(--red);">Danger Zone</div>
                    <div class="card-sub">Irreversible actions</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <p style="font-size:12.5px;color:var(--text2);margin-bottom:16px;line-height:1.7;">These actions are permanent and cannot be undone. Please be absolutely certain before proceeding.</p>
                <button class="save-btn danger" type="button" onclick="toast('Account deletion requires a confirmation email.','err')">
                  Delete My Account
                </button>
              </div>
            </div>

          </div>{{-- /tc-settings --}}

        </div>{{-- /right --}}
      </div>{{-- /profile-grid --}}
    </div>{{-- /.body --}}
  </div>{{-- /.main --}}
</div>{{-- /.shell --}}

<script>
(function(){
'use strict';

/* ── Theme ── */
var html   = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved  = localStorage.getItem('adminTheme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
  var t = this.checked ? 'dark' : 'light';
  html.setAttribute('data-theme', t);
  localStorage.setItem('adminTheme', t);
});

/* ── Sidebar hamburger ── */
var sidebar = document.getElementById('sidebar');
document.getElementById('hamburger').addEventListener('click', function(e){
  e.stopPropagation();
  sidebar.classList.toggle('open');
});
document.addEventListener('click', function(e){
  if (window.innerWidth <= 860 && sidebar.classList.contains('open') &&
      !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target))
    sidebar.classList.remove('open');
});

/* ── Avatar dropdown ── */
window.toggleDD = function(){ document.getElementById('avDD').classList.toggle('open'); };
document.addEventListener('click', function(e){
  var w = document.getElementById('avWrap');
  if (w && !w.contains(e.target)) document.getElementById('avDD').classList.remove('open');
});

/* ── Toast system ── */
window.toast = function(msg, type){
  var icons = {
    ok:  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    err: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
  };
  var t = document.createElement('div');
  t.className = 'toast toast-' + (type === 'ok' ? 'ok' : 'err');
  t.innerHTML = (icons[type]||icons.ok) + '<span>' + msg + '</span>' +
                '<button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
  document.getElementById('toastWrap').appendChild(t);
  setTimeout(function(){
    t.style.transition = 'opacity .3s,transform .3s';
    t.style.opacity = '0'; t.style.transform = 'translateX(20px)';
    setTimeout(function(){ t.remove(); }, 320);
  }, 4000);
};
@if(session('success')) setTimeout(function(){ toast(@json(session('success')),'ok'); }, 200); @endif
@if(session('error'))   setTimeout(function(){ toast(@json(session('error')),'err'); }, 200); @endif

/* ── Tab switching ── */
var TABS = ['campaigns','about','settings'];
window.switchTab = function(name){
  TABS.forEach(function(t){
    var tc   = document.getElementById('tc-' + t);
    var tb   = document.getElementById('tb-' + t);
    var sl   = document.getElementById('sl-' + t);
    var pill = document.getElementById('pill-' + t);
    if (tc)   tc.className   = 'tab-content'  + (t === name ? ' on' : '');
    if (tb)   tb.className   = 'tab-btn'       + (t === name ? ' on' : '');
    if (sl)   sl.className   = 's-link'        + (t === name ? ' active' : '');
    if (pill) pill.className = 'stat-pill'     + (t === name ? ' active' : '');
  });
  window.scrollTo({ top:0, behavior:'smooth' });
};

/* ── Auto-open about tab on validation errors ── */
@if($errors->any())
  @if($errors->has('current_password')||$errors->has('password')||$errors->has('name')||$errors->has('phone')||$errors->has('bio'))
    switchTab('about');
  @endif
@endif

/* ── Upload handling ── */
var activeUploadForm = null;
var uploadModal = document.getElementById('uploadModal');

document.getElementById('avatarInput').addEventListener('change', function(){
  var file = this.files[0]; if (!file) return;
  if (file.size > 2*1024*1024){ toast('Avatar must be under 2 MB','err'); this.value=''; return; }
  activeUploadForm = document.getElementById('avatarForm');
  /* Live preview in avatar ring */
  var liveImg  = document.getElementById('avatarImg');
  var initials = document.getElementById('avatarInitials');
  liveImg.src  = URL.createObjectURL(file);
  liveImg.style.display = 'block';
  if (initials) initials.style.display = 'none';
  /* Also update topbar + sidebar avatars */
  document.querySelectorAll('.t-av img, .s-uav img').forEach(function(el){ el.src = liveImg.src; });
  openUploadModal(file, 'Update profile photo');
});

document.getElementById('coverInput').addEventListener('change', function(){
  var file = this.files[0]; if (!file) return;
  if (file.size > 5*1024*1024){ toast('Cover must be under 5 MB','err'); this.value=''; return; }
  activeUploadForm = document.getElementById('coverForm');
  var liveImg = document.getElementById('coverImg');
  liveImg.src = URL.createObjectURL(file);
  liveImg.style.display = 'block';
  openUploadModal(file, 'Update cover photo');
});

function openUploadModal(file, title){
  var reader = new FileReader();
  reader.onload = function(e){
    document.getElementById('modalPreviewImg').src = e.target.result;
    document.getElementById('modalTitle').textContent = title;
    uploadModal.classList.add('open');
    document.body.style.overflow = 'hidden';
  };
  reader.readAsDataURL(file);
}

document.getElementById('confirmUploadBtn').addEventListener('click', function(){
  if (activeUploadForm) activeUploadForm.submit();
});

window.cancelUpload = function(){
  uploadModal.classList.remove('open');
  document.body.style.overflow = '';
  document.getElementById('avatarInput').value = '';
  document.getElementById('coverInput').value  = '';
  activeUploadForm = null;
};
uploadModal.addEventListener('click', function(e){ if (e.target === uploadModal) cancelUpload(); });
document.addEventListener('keydown', function(e){ if (e.key === 'Escape') cancelUpload(); });

/* ── Password eye toggle ── */
window.toggleEye = function(inputId, btn){
  var input = document.getElementById(inputId);
  var isText = input.type === 'text';
  input.type = isText ? 'password' : 'text';
  btn.querySelector('svg').innerHTML = isText
    ? '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>'
    : '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
};

})();
</script>
</body>
</html>