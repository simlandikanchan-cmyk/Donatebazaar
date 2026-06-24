<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Review Post — DonateBazaar Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
/* ══ DESIGN TOKENS ══ */
:root {
  --bg:#f4f5fb; --surface:#fff; --surface2:#f8f9fe; --surface3:#eef0fa;
  --border:rgba(0,0,0,.06); --border2:rgba(0,0,0,.10);
  --text:#0a0b14; --text2:#454863; --text3:#9096b4;
  --sb-bg:#ffffff; --sb-txt:#5a5f7a; --sb-act:rgba(110,86,247,.10); --sb-border:rgba(0,0,0,.08);
  --a:#6e56f7; --a2:#9b6dff; --a-lt:rgba(110,86,247,.10); --a-glow:rgba(110,86,247,.22);
  --green:#05c48a; --green-lt:rgba(5,196,138,.10);
  --amber:#f59e0b; --amber-lt:rgba(245,158,11,.10);
  --red:#f04444; --red-lt:rgba(240,68,68,.10);
  --blue:#3b82f6; --blue-lt:rgba(59,130,246,.10);
  --pink:#ec4899;
  --font:'DM Sans',sans-serif; --mono:'DM Mono',monospace;
  --r:18px; --r-sm:12px; --r-xs:8px;
  --sh:0 1px 3px rgba(0,0,0,.05),0 4px 24px rgba(0,0,0,.04);
  --sh-md:0 4px 20px rgba(0,0,0,.08); --sh-lg:0 12px 48px rgba(0,0,0,.14);
  --ease:.18s ease; --sb-w:268px;
  --orange:#f97316; --orange-lt:rgba(249,115,22,.10);
}
[data-theme="dark"] {
  --bg:#070810; --surface:#0f1020; --surface2:#161728; --surface3:#1d1f35;
  --border:rgba(255,255,255,.055); --border2:rgba(255,255,255,.09);
  --text:#eef0ff; --text2:#9ba3c8; --text3:#4c5272;
  --sb-bg:#050609; --sb-txt:rgba(255,255,255,.48); --sb-act:rgba(110,86,247,.22); --sb-border:rgba(255,255,255,.03);
  --a-glow:rgba(110,86,247,.30);
  --sh:0 1px 3px rgba(0,0,0,.35),0 4px 24px rgba(0,0,0,.25);
  --sh-md:0 4px 20px rgba(0,0,0,.4); --sh-lg:0 12px 48px rgba(0,0,0,.6);
}

/* ══ RESET ══ */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;}
body{font-family:var(--font);background:var(--bg);color:var(--text);line-height:1.55;-webkit-font-smoothing:antialiased;overflow-x:hidden;transition:background .2s,color .2s;font-size:14px;}
a{text-decoration:none;color:inherit;}
button{cursor:pointer;font-family:var(--font);}
svg{display:block;flex-shrink:0;}
img{max-width:100%;display:block;}

/* ══ LAYOUT ══ */
.shell{display:flex;min-height:100vh;}

/* ══ SIDEBAR ══ */
.sidebar{
  width:var(--sb-w);flex-shrink:0;background:var(--sb-bg);
  display:flex;flex-direction:column;
  position:fixed;top:0;left:0;bottom:0;z-index:400;
  overflow-y:auto;overflow-x:hidden;
  border-right:1px solid var(--sb-border);
  box-shadow:2px 0 16px rgba(0,0,0,.06);
  transition:transform .3s cubic-bezier(.4,0,.2,1);
  scrollbar-width:none;
}
.sidebar::-webkit-scrollbar{width:0;}
.s-logo{display:flex;align-items:center;gap:12px;padding:26px 22px 22px;border-bottom:1px solid var(--sb-border);}
.s-logo-mark{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--a),var(--a2));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 18px rgba(110,86,247,.4);}
.s-logo-mark svg{width:20px;height:20px;color:#fff;}
.s-logo-name{font-family:var(--mono);font-size:17px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1.1;}
.s-logo-tag{font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.16em;font-family:var(--mono);}

.s-admin-pill{margin:14px 12px 4px;padding:10px 14px;background:linear-gradient(135deg,rgba(110,86,247,.08),rgba(155,109,255,.05));border:1px solid rgba(110,86,247,.15);border-radius:var(--r-sm);display:flex;align-items:center;gap:10px;}
.s-av{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;font-family:var(--mono);}
.s-av img{width:100%;height:100%;object-fit:cover;}
.s-admin-name{font-size:12.5px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;flex:1;}
.s-admin-role{font-size:10px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.s-online{width:7px;height:7px;border-radius:50%;background:var(--green);flex-shrink:0;box-shadow:0 0 0 2.5px rgba(5,196,138,.2);}

.s-section{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.18em;padding:20px 22px 6px;font-family:var(--mono);}
.s-nav{padding:2px 10px;}
.s-link{display:flex;align-items:center;gap:11px;padding:9px 12px;border-radius:var(--r-xs);color:var(--sb-txt);font-size:13px;font-weight:500;transition:background var(--ease),color var(--ease);margin-bottom:1px;border:none;background:transparent;width:100%;text-align:left;cursor:pointer;position:relative;text-decoration:none;}
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

/* ══ MAIN ══ */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* ══ TOPBAR ══ */
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
.btn-back{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 14px;background:var(--surface2);color:var(--text2);border-radius:var(--r-sm);font-size:12px;font-weight:600;border:1px solid var(--border2);transition:all var(--ease);text-decoration:none;}
.btn-back:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.btn-back svg{width:13px;height:13px;}

/* ══ BODY ══ */
.body{padding:26px 28px 56px;display:flex;gap:20px;align-items:flex-start;}

/* ══ MAIN CONTENT ══ */
.review-content{flex:1;min-width:0;animation:fadeUp .4s .05s both;}
.content-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;}
.cover-wrap{aspect-ratio:16/7;background:var(--surface2);overflow:hidden;border-bottom:1px solid var(--border);position:relative;}
.cover-wrap img{width:100%;height:100%;object-fit:cover;}
.cover-placeholder{width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;color:var(--text3);}
.cover-placeholder svg{width:32px;height:32px;opacity:.3;}
.cover-placeholder span{font-size:12px;font-family:var(--mono);}
.prose-area{padding:28px 32px 36px;}
.blog-cat-tag{font-family:var(--mono);font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--a);margin-bottom:10px;}
.blog-title{font-family:'DM MONO',monospace;font-size:clamp(22px,2.8vw,30px);font-weight:800;line-height:1.2;color:var(--text);margin-bottom:14px;letter-spacing:-.01em;text-transform:capitalize;}
.blog-byline{display:flex;align-items:center;gap:10px;padding-bottom:18px;border-bottom:1px solid var(--border);margin-bottom:20px;flex-wrap:wrap;}
.byline-av{width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);}
.byline-text{font-size:12.5px;color:var(--text2);}
.byline-text strong{color:var(--text);font-weight:600;}
.byline-sep{width:3px;height:3px;border-radius:50%;background:var(--text3);display:inline-block;margin:0 6px;vertical-align:middle;}

/* Engagement strip */
.engage-strip{display:flex;align-items:center;gap:16px;padding:11px 16px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);margin-bottom:22px;flex-wrap:wrap;}
.es-item{display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text2);}
.es-item svg{width:13px;height:13px;flex-shrink:0;}
.es-item strong{font-weight:700;color:var(--text);}
.es-divider{width:1px;height:14px;background:var(--border2);flex-shrink:0;}

.blog-excerpt{background:var(--a-lt);border-left:3px solid var(--a);border-radius:0 var(--r-sm) var(--r-sm) 0;padding:14px 18px;margin-bottom:22px;font-size:15px;font-style:italic;color:var(--text2);line-height:1.75;}
.blog-prose{font-size:15px;line-height:1.85;color:var(--text2);}
.blog-prose p{margin-bottom:1.25rem;}

/* ══ RIGHT PANEL ══ */
.review-panel{width:276px;flex-shrink:0;display:flex;flex-direction:column;gap:14px;position:sticky;top:80px;}

.panel-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s both;}
.panel-card:nth-child(1){animation-delay:.05s}
.panel-card:nth-child(2){animation-delay:.10s}
.panel-card:nth-child(3){animation-delay:.15s}
.panel-card:nth-child(4){animation-delay:.20s}
.panel-card:nth-child(5){animation-delay:.25s}
.panel-card:nth-child(6){animation-delay:.30s}

.panel-head{padding:12px 16px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.panel-head-title{font-family:var(--mono);font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;}
.panel-head-live{display:inline-flex;align-items:center;gap:4px;font-size:9.5px;font-weight:600;color:var(--green);font-family:var(--mono);}
.panel-head-live::before{content:'';width:6px;height:6px;border-radius:50%;background:var(--green);animation:pulse 1.8s infinite;}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1);}50%{opacity:.5;transform:scale(.85);}}
.panel-body{padding:16px;}

/* Meta rows */
.meta-row{display:flex;flex-direction:column;gap:2px;margin-bottom:12px;}
.meta-row:last-child{margin-bottom:0;}
.meta-key{font-family:var(--mono);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);}
.meta-val{font-size:13px;font-weight:600;color:var(--text);margin-top:2px;}
.meta-val.muted{font-weight:400;color:var(--text2);}

/* Badges */
.badge{display:inline-flex;align-items:center;gap:4px;font-family:var(--mono);font-size:10px;font-weight:700;padding:3px 9px;border-radius:100px;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
.b-pending{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.3);}
.b-approved{background:var(--green-lt);color:#065f46;border:1px solid rgba(5,196,138,.3);}
.b-rejected{background:var(--red-lt);color:#991b1b;border:1px solid rgba(240,68,68,.3);}
[data-theme="dark"] .b-pending{color:var(--amber);}
[data-theme="dark"] .b-approved{color:#34d399;}
[data-theme="dark"] .b-rejected{color:#f87171;}
.cat-tag{display:inline-block;padding:3px 10px;border-radius:7px;font-size:11px;font-weight:600;background:var(--a-lt);color:var(--a);border:1px solid rgba(110,86,247,.18);}

/* Engagement 2×2 grid */
.eng-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.eng-box{background:var(--surface2);border:1px solid var(--border);border-radius:11px;padding:12px 14px;display:flex;flex-direction:column;gap:6px;transition:transform var(--ease),box-shadow var(--ease);}
.eng-box:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.08);}
.eng-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.eng-icon svg{width:14px;height:14px;}
.ei-blue{background:var(--blue-lt);color:var(--blue);}
.ei-pink{background:rgba(236,72,153,.12);color:var(--pink);}
.ei-green{background:var(--green-lt);color:var(--green);}
.ei-yellow{background:var(--amber-lt);color:var(--amber);}
.eng-num{font-family:var(--mono);font-size:1.45rem;font-weight:800;line-height:1;letter-spacing:-.03em;}
.en-blue{color:var(--blue);}
.en-pink{color:var(--pink);}
.en-green{color:var(--green);}
.en-yellow{color:var(--amber);}
.eng-label{font-family:var(--mono);font-size:9.5px;color:var(--text3);text-transform:uppercase;letter-spacing:.07em;}

/* Distribution bars */
.eng-bar-wrap{margin-top:4px;}
.eng-bar-label{display:flex;justify-content:space-between;font-size:10px;font-family:var(--mono);color:var(--text3);margin-bottom:4px;}
.eng-bar-track{height:5px;background:var(--surface2);border-radius:100px;overflow:hidden;border:1px solid var(--border);}
.eng-bar-fill{height:100%;border-radius:100px;transition:width 1s cubic-bezier(.4,0,.2,1);}
.fill-blue{background:var(--blue);}
.fill-pink{background:var(--pink);}
.fill-green{background:var(--green);}
.fill-yellow{background:var(--amber);}

/* Content stats */
.stat-pair{display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.stat-box{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:10px 12px;text-align:center;}
.stat-box .sn{font-family:var(--mono);font-size:1.4rem;font-weight:800;color:var(--a);line-height:1;letter-spacing:-.02em;}
.stat-box .sl{font-size:9.5px;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);margin-top:3px;}

/* Rejection reason */
.reject-reason-box{background:var(--red-lt);border:1px solid rgba(240,68,68,.2);border-radius:var(--r-sm);padding:10px 12px;font-size:12.5px;color:var(--red);line-height:1.55;}
[data-theme="dark"] .reject-reason-box{color:#f87171;}

/* Comments mini list */
.comment-list{display:flex;flex-direction:column;gap:10px;}
.comment-item{display:flex;gap:8px;align-items:flex-start;}
.comment-ava{width:26px;height:26px;border-radius:7px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);}
.comment-body{flex:1;min-width:0;}
.comment-name{font-size:11.5px;font-weight:600;color:var(--text);line-height:1.2;}
.comment-text{font-size:11px;color:var(--text3);margin-top:2px;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;}
.comment-time{font-family:var(--mono);font-size:9.5px;color:var(--text3);margin-top:3px;}
.no-comments{font-family:var(--mono);font-size:12px;color:var(--text3);text-align:center;padding:10px 0;}

/* Action buttons */
.action-stack{display:flex;flex-direction:column;gap:7px;}
.act-full{display:flex;align-items:center;justify-content:center;gap:7px;width:100%;padding:10px 14px;border-radius:var(--r-sm);font-size:13px;font-weight:600;cursor:pointer;border:1px solid transparent;transition:opacity var(--ease),transform var(--ease);font-family:var(--font);text-decoration:none;}
.act-full:hover{opacity:.88;}
.act-full:active{transform:scale(.98);}
.act-full svg{width:13px;height:13px;}
.af-approve{background:var(--green);color:#fff;border-color:var(--green);}
.af-reject{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.25);}
[data-theme="dark"] .af-reject{color:#f87171;}
.af-edit{background:var(--surface2);color:var(--text2);border-color:var(--border2);}
.af-edit:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);}

/* ══ TOAST ══ */
.toast-container{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:13px;font-size:13px;font-weight:500;color:#fff;min-width:260px;max-width:360px;box-shadow:var(--sh-lg);pointer-events:all;animation:fadeUp .35s cubic-bezier(.4,0,.2,1) both;position:relative;overflow:hidden;}
.toast::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:rgba(255,255,255,.3);transform-origin:left;animation:toastProg 4s linear forwards;}
.toast-success{background:linear-gradient(135deg,#059669,#05c48a);}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-x{margin-left:auto;width:18px;height:18px;border-radius:4px;background:rgba(255,255,255,.2);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}

/* ══ MODAL ══ */
.modal-backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(4px);z-index:500;align-items:center;justify-content:center;}
.modal-backdrop.open{display:flex;}
.modal-box{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:24px;width:420px;max-width:90vw;box-shadow:var(--sh-lg);animation:modalIn .2s ease;}
@keyframes modalIn{from{opacity:0;transform:translateY(12px) scale(.97);}to{opacity:1;transform:none;}}
.modal-icon{width:44px;height:44px;background:var(--red-lt);border-radius:11px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;}
.modal-icon svg{width:20px;height:20px;color:var(--red);}
.modal-title{font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:var(--text);margin-bottom:4px;}
.modal-sub{font-size:12.5px;color:var(--text3);margin-bottom:16px;}
.modal-textarea{width:100%;min-height:100px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-family:var(--font);font-size:13px;color:var(--text);resize:vertical;outline:none;transition:border-color var(--ease);}
.modal-textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.modal-textarea::placeholder{color:var(--text3);}
.modal-error{font-family:var(--mono);font-size:11.5px;color:var(--red);margin-top:5px;display:none;}
.modal-footer{display:flex;gap:8px;margin-top:16px;justify-content:flex-end;}
.modal-cancel{padding:7px 14px;border:1px solid var(--border2);background:var(--surface2);border-radius:var(--r-xs);font-family:var(--font);font-size:12.5px;font-weight:500;cursor:pointer;color:var(--text2);transition:background var(--ease);}
.modal-cancel:hover{background:var(--border);}
.modal-confirm{padding:7px 16px;background:var(--red);color:#fff;border:none;border-radius:var(--r-xs);font-family:var(--font);font-size:12.5px;font-weight:600;cursor:pointer;transition:opacity var(--ease);}
.modal-confirm:hover{opacity:.85;}

/* ══ ANIMATIONS ══ */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:none;}}
@keyframes toastProg{from{transform:scaleX(1);}to{transform:scaleX(0);}}

/* ══ RESPONSIVE ══ */
@media(max-width:900px){
  .body{flex-direction:column;}
  .review-panel{width:100%;position:static;display:grid;grid-template-columns:repeat(2,1fr);}
}
@media(max-width:860px){
  .sidebar{transform:translateX(-100%);}
  .sidebar.open{transform:translateX(0);}
  .main{margin-left:0;}
  .hamburger{display:flex;}
}
@media(max-width:600px){
  .topbar{padding:0 14px;}
  .body{padding:14px;}
  .prose-area{padding:18px;}
  .review-panel{grid-template-columns:1fr;}
}
</style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<div class="shell">

{{-- ══ SIDEBAR ══ --}}
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
      @if(auth()->user()->avatar ?? null)
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
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault();document.getElementById('__lf').submit();"
       class="s-link" style="color:var(--red);">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
      Sign Out
    </a>
    <form id="__lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
  </div>
</aside>

{{-- ══ MAIN ══ --}}
<div class="main">

  {{-- ══ TOPBAR ══ --}}
  <header class="topbar">
    <div style="display:flex;align-items:center;gap:10px;">
      <button class="hamburger" id="hamburger" aria-label="Open menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <div class="tb-left">
        <h1>Review Post</h1>
        <p>{{ Str::limit($blog->title ?? 'Blog post', 50) }}</p>
      </div>
    </div>
    <div class="tb-right">
      <a href="{{ route('admin.blogs.index') }}" class="btn-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        All Posts
      </a>
      <button class="tb-btn" title="Notifications" aria-label="Notifications">
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
      <div class="t-av">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
    </div>
  </header>

  {{-- ══ BODY ══ --}}
  <div class="body">

    @php
      /* ── Category resolution ── */
      $categoryName = null;
      if (!empty($blog->category)) {
        if (is_string($blog->category)) {
          $decoded = json_decode($blog->category, true);
          $categoryName = is_array($decoded) ? ($decoded['name'] ?? $blog->category) : $blog->category;
        } elseif (is_object($blog->category)) {
          $categoryName = $blog->category->name ?? null;
        } elseif (is_array($blog->category)) {
          $categoryName = $blog->category['name'] ?? null;
        }
      }

      /* ── Content stats ── */
      $wordCount = str_word_count(strip_tags($blog->content ?? ''));
      $readTime  = max(1, (int) round($wordCount / 200));

      /* ── Engagement stats (safe accessors) ── */
      $views    = (int) ($blog->views_count    ?? (method_exists($blog, 'views')    ? $blog->views()->count()    : 0));
      $likes    = (int) ($blog->likes_count    ?? (method_exists($blog, 'likes')    ? $blog->likes()->count()    : 0));
      $comments = (int) ($blog->comments_count ?? (method_exists($blog, 'comments') ? $blog->comments()->count() : 0));
      $shares   = (int) ($blog->shares_count   ?? (method_exists($blog, 'shares')   ? $blog->shares()->count()   : 0));

      $total     = max(1, $views + $likes + $comments + $shares);
      $pViews    = (int) round($views    / $total * 100);
      $pLikes    = (int) round($likes    / $total * 100);
      $pComments = (int) round($comments / $total * 100);
      $pShares   = (int) round($shares   / $total * 100);

      /* ── Recent comments ── */
      $recentComments = method_exists($blog, 'comments')
        ? $blog->comments()->latest()->limit(3)->get()
        : collect();
    @endphp

    {{-- ══ MAIN BLOG CONTENT ══ --}}
    <div class="review-content">
      <div class="content-card">

        {{-- Cover image --}}
        <div class="cover-wrap">
          @if(!empty($blog->cover_image))
            <img src="{{ asset('storage/'.ltrim($blog->cover_image, '/')) }}"
                 alt="{{ e($blog->title) }}"
                 loading="lazy">
          @else
            <div class="cover-placeholder">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
              <span>No cover image uploaded</span>
            </div>
          @endif
        </div>

        <div class="prose-area">
          @if($categoryName)
            <div class="blog-cat-tag">{{ $categoryName }}</div>
          @endif

          <h1 class="blog-title">{{ $blog->title }}</h1>

          <div class="blog-byline">
            <div class="byline-av">{{ strtoupper(substr($blog->author->name ?? 'U', 0, 2)) }}</div>
            <span class="byline-text">
              <strong>{{ $blog->author->name ?? 'Unknown' }}</strong>
              <span class="byline-sep"></span>
              {{ $blog->created_at->format('d M Y') }}
              <span class="byline-sep"></span>
              {{ $readTime }} min read
            </span>
          </div>

          {{-- Engagement strip --}}
          <div class="engage-strip">
            <div class="es-item" style="color:var(--blue);">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              <strong>{{ number_format($views) }}</strong>&nbsp;views
            </div>
            <div class="es-divider"></div>
            <div class="es-item" style="color:var(--pink);">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
              <strong>{{ number_format($likes) }}</strong>&nbsp;likes
            </div>
            <div class="es-divider"></div>
            <div class="es-item" style="color:var(--green);">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
              <strong>{{ number_format($comments) }}</strong>&nbsp;comments
            </div>
            <div class="es-divider"></div>
            <div class="es-item" style="color:var(--amber);">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
              <strong>{{ number_format($shares) }}</strong>&nbsp;shares
            </div>
          </div>

          @if(!empty($blog->excerpt))
            <blockquote class="blog-excerpt">{{ $blog->excerpt }}</blockquote>
          @endif

          <div class="blog-prose">
            {!! nl2br(e($blog->content)) !!}
          </div>
        </div>
      </div>
    </div>

    {{-- ══ RIGHT PANEL ══ --}}
    <aside class="review-panel">

      {{-- Post Details --}}
      <div class="panel-card">
        <div class="panel-head">
          <span class="panel-head-title">Post Details</span>
        </div>
        <div class="panel-body">
          <div class="meta-row">
            <span class="meta-key">Status</span>
            <div style="margin-top:4px;">
              @php $status = $blog->status ?? 'pending'; @endphp
              <span class="badge b-{{ $status }}">
                <span class="badge-dot"></span>{{ ucfirst($status) }}
              </span>
            </div>
          </div>
          <div class="meta-row">
            <span class="meta-key">Author</span>
            <span class="meta-val">{{ $blog->author->name ?? 'Unknown' }}</span>
            <span class="meta-val muted">{{ e($blog->author->email ?? '') }}</span>
          </div>
          <div class="meta-row">
            <span class="meta-key">Submitted</span>
            <span class="meta-val">{{ $blog->created_at->format('d M Y') }}</span>
            <span class="meta-val muted">{{ $blog->created_at->diffForHumans() }}</span>
          </div>
          @if($categoryName)
          <div class="meta-row">
            <span class="meta-key">Category</span>
            <div style="margin-top:4px;"><span class="cat-tag">{{ $categoryName }}</span></div>
          </div>
          @endif
        </div>
      </div>

      {{-- Engagement Stats --}}
      <div class="panel-card">
        <div class="panel-head">
          <span class="panel-head-title">Engagement</span>
          <span class="panel-head-live">Live</span>
        </div>
        <div class="panel-body">
          <div class="eng-grid">
            <div class="eng-box">
              <div class="eng-icon ei-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></div>
              <div class="eng-num en-blue">{{ number_format($views) }}</div>
              <div class="eng-label">Views</div>
            </div>
            <div class="eng-box">
              <div class="eng-icon ei-pink"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
              <div class="eng-num en-pink">{{ number_format($likes) }}</div>
              <div class="eng-label">Likes</div>
            </div>
            <div class="eng-box">
              <div class="eng-icon ei-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></div>
              <div class="eng-num en-green">{{ number_format($comments) }}</div>
              <div class="eng-label">Comments</div>
            </div>
            <div class="eng-box">
              <div class="eng-icon ei-yellow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg></div>
              <div class="eng-num en-yellow">{{ number_format($shares) }}</div>
              <div class="eng-label">Shares</div>
            </div>
          </div>
          <div style="margin-top:14px;display:flex;flex-direction:column;gap:9px;">
            @foreach([
              ['label'=>'Views',    'color'=>'var(--blue)',  'fill'=>'fill-blue',  'pct'=>$pViews],
              ['label'=>'Likes',    'color'=>'var(--pink)',  'fill'=>'fill-pink',  'pct'=>$pLikes],
              ['label'=>'Comments', 'color'=>'var(--green)', 'fill'=>'fill-green', 'pct'=>$pComments],
              ['label'=>'Shares',   'color'=>'var(--amber)', 'fill'=>'fill-yellow','pct'=>$pShares],
            ] as $bar)
            <div class="eng-bar-wrap">
              <div class="eng-bar-label">
                <span style="color:{{ $bar['color'] }};">{{ $bar['label'] }}</span>
                <span>{{ $bar['pct'] }}%</span>
              </div>
              <div class="eng-bar-track">
                <div class="eng-bar-fill {{ $bar['fill'] }}" style="width:0%" data-width="{{ $bar['pct'] }}%"></div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Content Stats --}}
      <div class="panel-card">
        <div class="panel-head"><span class="panel-head-title">Content Stats</span></div>
        <div class="panel-body">
          <div class="stat-pair">
            <div class="stat-box">
              <div class="sn">{{ number_format($wordCount) }}</div>
              <div class="sl">Words</div>
            </div>
            <div class="stat-box">
              <div class="sn">{{ $readTime }}m</div>
              <div class="sl">Read time</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Recent Comments --}}
      <div class="panel-card">
        <div class="panel-head">
          <span class="panel-head-title">Recent Comments</span>
          <span style="font-family:var(--mono);font-size:10px;color:var(--text3);">{{ number_format($comments) }} total</span>
        </div>
        <div class="panel-body">
          @if($recentComments->isNotEmpty())
            <div class="comment-list">
              @foreach($recentComments as $c)
              <div class="comment-item">
                <div class="comment-ava">{{ strtoupper(substr($c->user->name ?? $c->name ?? 'U', 0, 1)) }}</div>
                <div class="comment-body">
                  <div class="comment-name">{{ $c->user->name ?? $c->name ?? 'Anonymous' }}</div>
                  <div class="comment-text">{{ $c->body ?? $c->content ?? $c->comment ?? '' }}</div>
                  <div class="comment-time">{{ $c->created_at->diffForHumans() }}</div>
                </div>
              </div>
              @endforeach
            </div>
          @else
            <p class="no-comments">No comments yet.</p>
          @endif
        </div>
      </div>

      {{-- Rejection Reason (only when rejected) --}}
      @if(($blog->status ?? '') === 'rejected' && !empty($blog->rejection_reason))
      <div class="panel-card">
        <div class="panel-head"><span class="panel-head-title">Rejection Reason</span></div>
        <div class="panel-body">
          <div class="reject-reason-box">{{ $blog->rejection_reason }}</div>
        </div>
      </div>
      @endif

      {{-- Actions --}}
      <div class="panel-card">
        <div class="panel-head"><span class="panel-head-title">Actions</span></div>
        <div class="panel-body">
          <div class="action-stack">
            @if($blog->status === 'pending')
              <form method="POST" action="{{ route('admin.blogs.approve', $blog) }}">
                @csrf
                <button type="submit" class="act-full af-approve">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  Approve Post
                </button>
              </form>
              <button type="button" class="act-full af-reject" onclick="openRejectModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Reject Post
              </button>
            @elseif($blog->status === 'approved')
              <div class="act-full" style="background:var(--green-lt);color:var(--green);border:1px solid rgba(5,196,138,.3);cursor:default;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Already Approved
              </div>
            @elseif($blog->status === 'rejected')
              <form method="POST" action="{{ route('admin.blogs.approve', $blog) }}">
                @csrf
                <button type="submit" class="act-full af-approve">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  Re-Approve Post
                </button>
              </form>
            @endif
            <a href="{{ route('admin.blogs.edit', $blog) }}" class="act-full af-edit">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Edit Post
            </a>
          </div>
        </div>
      </div>

    </aside>
  </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

{{-- ══ REJECT MODAL ══ --}}
<div class="modal-backdrop" id="rejectModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle"
     onclick="if(event.target===this)closeRejectModal()">
  <div class="modal-box">
    <div class="modal-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    </div>
    <h2 class="modal-title" id="modalTitle">Reject this post?</h2>
    <p class="modal-sub">Please provide a reason — it will be shared with the author.</p>
    <form method="POST" action="{{ route('admin.blogs.reject', $blog) }}" id="rejectForm">
      @csrf
      <textarea name="reason" id="reject_reason" class="modal-textarea"
                placeholder="e.g. Content doesn't meet our editorial guidelines…"
                maxlength="1000" aria-describedby="reject-error"></textarea>
      <p class="modal-error" id="reject-error" role="alert">A reason is required before rejecting.</p>
      <div class="modal-footer">
        <button type="button" class="modal-cancel" onclick="closeRejectModal()">Cancel</button>
        <button type="button" class="modal-confirm" onclick="submitReject()">Reject post</button>
      </div>
    </form>
  </div>
</div>

<script>
(function () {
  'use strict';

  /* ── THEME ── */
  var html    = document.documentElement;
  var toggle  = document.getElementById('themeToggle');
  var saved   = localStorage.getItem('adminTheme') || 'light';
  if (saved === 'dark') { html.setAttribute('data-theme', 'dark'); toggle.checked = true; }
  toggle.addEventListener('change', function () {
    var t = this.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', t);
    localStorage.setItem('adminTheme', t);
  });

  /* ── SIDEBAR TOGGLE ── */
  var sidebar  = document.getElementById('sidebar');
  var hamburger = document.getElementById('hamburger');
  hamburger.addEventListener('click', function () { sidebar.classList.toggle('open'); });
  document.addEventListener('click', function (e) {
    if (window.innerWidth <= 860 && !sidebar.contains(e.target) && !hamburger.contains(e.target)) {
      sidebar.classList.remove('open');
    }
  });

  /* ── ANIMATE ENGAGEMENT BARS ── */
  window.addEventListener('load', function () {
    setTimeout(function () {
      document.querySelectorAll('.eng-bar-fill[data-width]').forEach(function (el) {
        el.style.width = el.dataset.width;
      });
    }, 300);
  });

  /* ── SUCCESS TOAST ── */
  @if(session('success'))
  (function () {
    var c  = document.getElementById('toastContainer');
    var el = document.createElement('div');
    el.className = 'toast toast-success';
    el.innerHTML =
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' +
      '<span>' + {{ Js::from(session('success')) }} + '</span>' +
      '<button class="toast-x" onclick="this.parentElement.remove()" aria-label="Dismiss">×</button>';
    c.appendChild(el);
    setTimeout(function () { el.remove(); }, 4000);
  }());
  @endif

})();

/* ── MODAL ── */
function openRejectModal() {
  document.getElementById('rejectModal').classList.add('open');
  setTimeout(function () { document.getElementById('reject_reason').focus(); }, 180);
}

function closeRejectModal() {
  document.getElementById('rejectModal').classList.remove('open');
  document.getElementById('reject_reason').value = '';
  document.getElementById('reject-error').style.display = 'none';
}

function submitReject() {
  var reason = document.getElementById('reject_reason').value.trim();
  var errEl  = document.getElementById('reject-error');
  if (!reason) {
    errEl.style.display = 'block';
    document.getElementById('reject_reason').focus();
    return;
  }
  errEl.style.display = 'none';
  document.getElementById('rejectForm').submit();
}

document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') closeRejectModal();
});
</script>
</body>
</html>