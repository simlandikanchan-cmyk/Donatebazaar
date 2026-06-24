{{-- resources/views/admin/events/show.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $event->title }} — DonateBazaar Admin</title>
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
  --gray:#6b7280;--gray-lt:rgba(107,114,128,.10);
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
.s-link{display:flex;align-items:center;gap:11px;padding:9px 12px;border-radius:var(--r-xs);color:var(--sb-txt);font-size:13px;font-weight:500;text-decoration:none;transition:background var(--ease),color var(--ease);margin-bottom:1px;border:none;background:transparent;width:100%;text-align:left;cursor:pointer;position:relative;font-family:var(--font);}
.s-link:hover{background:var(--a-lt);color:var(--a);}
.s-link.active{background:var(--sb-act);color:var(--a);font-weight:600;}
.s-link.active::before{content:'';position:absolute;left:0;top:22%;bottom:22%;width:3px;border-radius:0 3px 3px 0;background:var(--a);}
.s-ico{width:15px;height:15px;flex-shrink:0;opacity:.65;}
.s-link:hover .s-ico,.s-link.active .s-ico{opacity:1;}
.s-chip{margin-left:auto;font-size:10px;font-weight:700;padding:2px 7px;border-radius:100px;font-family:var(--mono);}
.sc-purple{background:var(--a-lt);color:var(--a);}
.sc-amber{background:var(--amber-lt);color:#b45309;}
.sc-teal{background:var(--green-lt);color:#059669;}
.sc-blue{background:var(--blue-lt);color:var(--blue);}
.s-divider{height:1px;background:var(--sb-border);margin:10px 18px;}
.s-bottom{margin-top:auto;padding:10px 10px 20px;border-top:1px solid var(--sb-border);}
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:62px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:200;gap:14px;}
.tb-left{display:flex;align-items:center;gap:10px;min-width:0;}
.tb-left h1{font-family:var(--mono);font-size:16px;font-weight:700;color:var(--text);letter-spacing:-.02em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:280px;}
.tb-left p{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.tb-back{display:flex;align-items:center;gap:6px;padding:6px 12px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);transition:all var(--ease);text-decoration:none;flex-shrink:0;}
.tb-back:hover{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.3);}
.tb-back svg{width:13px;height:13px;}
.tb-right{display:flex;align-items:center;gap:8px;flex-shrink:0;}
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
.body{padding:26px 28px 56px;flex:1;}
.status-banner{border-radius:var(--r-sm);padding:16px 20px;margin-bottom:22px;display:flex;align-items:center;gap:14px;animation:fadeUp .35s ease both;}
.status-banner svg{width:18px;height:18px;flex-shrink:0;}
.sb-pending{background:rgba(110,86,247,.07);border:1px solid rgba(110,86,247,.25);}
.sb-draft{background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);}
.sb-active{background:rgba(5,196,138,.08);border:1px solid rgba(5,196,138,.25);}
.sb-cancelled{background:var(--red-lt);border:1px solid rgba(240,68,68,.2);}
.sb-expired{background:var(--gray-lt);border:1px solid rgba(107,114,128,.2);}
.sb-completed{background:var(--blue-lt);border:1px solid rgba(59,130,246,.2);}
.sb-text{flex:1;}
.sb-title{font-size:13.5px;font-weight:700;}
.sb-sub{font-size:12px;margin-top:2px;}
.sb-pending .sb-title{color:#5b21b6;}.sb-pending .sb-sub{color:var(--a);}
.sb-draft .sb-title{color:#92400e;}.sb-draft .sb-sub{color:#b45309;}
.sb-active .sb-title{color:#065f46;}.sb-active .sb-sub{color:#059669;}
.sb-cancelled .sb-title,.sb-cancelled .sb-sub{color:var(--red);}
.sb-expired .sb-title,.sb-completed .sb-title{color:var(--text2);}
.sb-expired .sb-sub,.sb-completed .sb-sub{color:var(--text3);}
[data-theme="dark"] .sb-pending .sb-title{color:#c4b5fd;}
[data-theme="dark"] .sb-pending .sb-sub{color:#a78bfa;}
[data-theme="dark"] .sb-draft .sb-title{color:#fde68a;}
[data-theme="dark"] .sb-draft .sb-sub{color:#fde68a;opacity:.7;}
[data-theme="dark"] .sb-active .sb-title{color:#6ee7b7;}
[data-theme="dark"] .sb-active .sb-sub{color:#6ee7b7;opacity:.7;}
.show-grid{display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;}
@media(max-width:900px){.show-grid{grid-template-columns:1fr;}}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;}
.card+.card{margin-top:16px;}
.card-header{padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px;}
.card-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:16px;height:16px;}
.ci-purple{background:var(--a-lt);color:var(--a);}
.ci-green{background:var(--green-lt);color:var(--green);}
.ci-amber{background:var(--amber-lt);color:var(--amber);}
.ci-blue{background:var(--blue-lt);color:var(--blue);}
.ci-red{background:var(--red-lt);color:var(--red);}
.card-title{font-family:var(--mono);font-size:14px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-subtitle{font-size:11px;color:var(--text3);margin-top:2px;}
.card-body{padding:22px;}
.event-cover{width:100%;height:260px;object-fit:cover;display:block;}
.event-cover-placeholder{width:100%;height:160px;background:linear-gradient(135deg,var(--a-lt),var(--surface3));display:flex;align-items:center;justify-content:center;}
.event-cover-placeholder svg{width:44px;height:44px;color:var(--a);opacity:.25;}
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:100px;font-size:11px;font-weight:700;font-family:var(--mono);}
.sp-dot{width:6px;height:6px;border-radius:50%;flex-shrink:0;}
.pill-active{background:var(--green-lt);color:#059669;}.pill-active .sp-dot{background:var(--green);}
.pill-draft{background:var(--amber-lt);color:#b45309;}.pill-draft .sp-dot{background:var(--amber);}
.pill-cancelled{background:var(--red-lt);color:var(--red);}.pill-cancelled .sp-dot{background:var(--red);}
.pill-expired{background:var(--gray-lt);color:var(--gray);}.pill-expired .sp-dot{background:var(--gray);}
.pill-completed{background:var(--blue-lt);color:var(--blue);}.pill-completed .sp-dot{background:var(--blue);}
.pill-pending{background:var(--a-lt);color:var(--a);}.pill-pending .sp-dot{background:var(--a);}
[data-theme="dark"] .pill-active{color:#34d399;}
[data-theme="dark"] .pill-pending{color:#c4b5fd;}
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:0;}
.detail-item{padding:13px 0;border-bottom:1px solid var(--border);}
.detail-item:nth-last-child(-n+2){border-bottom:none;}
.detail-item:nth-child(odd){padding-right:18px;}
.detail-key{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:4px;}
.detail-val{font-size:13.5px;font-weight:500;color:var(--text);}
.detail-val.muted{color:var(--text3);font-style:italic;font-weight:400;}
.stat-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;}
.stat-mini{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-sm);padding:16px 18px;box-shadow:var(--sh);animation:fadeUp .4s ease both;}
.stat-mini:nth-child(1){animation-delay:.04s;}
.stat-mini:nth-child(2){animation-delay:.08s;}
.stat-mini:nth-child(3){animation-delay:.12s;}
.stat-mini-lbl{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:6px;}
.stat-mini-val{font-size:1.6rem;font-weight:800;color:var(--text);font-family:var(--mono);letter-spacing:-.02em;line-height:1;}
.stat-mini-sub{font-size:11px;color:var(--text3);margin-top:5px;}
.progress-label{display:flex;justify-content:space-between;font-size:11px;font-family:var(--mono);color:var(--text3);margin-bottom:6px;}
.progress-bar{height:7px;background:var(--surface3);border-radius:100px;overflow:hidden;}
.progress-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--a),var(--green));}

/* ── SETTING ROW ── */
.setting-row{display:flex;align-items:center;gap:12px;padding:14px 0;border-bottom:1px solid var(--border);}
.setting-row:last-child{border-bottom:none;}
.setting-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.setting-icon svg{width:13px;height:13px;}
.setting-info{flex:1;}
.setting-name{font-size:13px;font-weight:600;color:var(--text);}
.setting-desc{font-size:11px;color:var(--text3);margin-top:1px;}

/* ── TOGGLE SWITCH — fixed structure ── */
.toggle-wrap{position:relative;display:inline-block;width:46px;height:26px;flex-shrink:0;cursor:pointer;}
.toggle-wrap input{position:absolute;opacity:0;width:0;height:0;pointer-events:none;}
.toggle-track{position:absolute;inset:0;border-radius:100px;background:var(--surface3);border:1.5px solid var(--border2);transition:background .25s,border-color .25s;cursor:pointer;}
.toggle-track::after{content:'';position:absolute;width:18px;height:18px;border-radius:50%;background:#fff;top:2px;left:2px;transition:transform .25s;box-shadow:0 2px 4px rgba(0,0,0,.18);}
.toggle-wrap input:checked ~ .toggle-track{background:var(--a);border-color:var(--a);}
.toggle-wrap input:checked ~ .toggle-track::after{transform:translateX(20px);}

.btn{display:inline-flex;align-items:center;gap:8px;padding:11px 22px;border-radius:var(--r-sm);font-size:13px;font-weight:600;font-family:var(--font);cursor:pointer;border:none;transition:all var(--ease);text-decoration:none;white-space:nowrap;}
.btn svg{width:14px;height:14px;}
.btn-sm{padding:7px 14px;font-size:12px;}
.btn-approve{background:linear-gradient(135deg,var(--green),#059669);color:#fff;box-shadow:0 4px 18px rgba(5,196,138,.4);}
.btn-approve:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(5,196,138,.5);}
.btn-publish{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 18px rgba(110,86,247,.4);}
.btn-publish:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(110,86,247,.5);}
.btn-draft{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.3);}
.btn-draft:hover{background:rgba(245,158,11,.2);}
.btn-edit{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-edit:hover{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.3);}
.btn-danger{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.2);}
.btn-danger:hover{background:rgba(240,68,68,.16);}
.btn-reject{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.2);}
.btn-reject:hover{background:rgba(240,68,68,.16);}
.show-sidebar{position:sticky;top:82px;}
.show-sidebar .card+.card{margin-top:14px;}
.summary-hdr{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--surface2);}
.summary-hdr-title{font-family:var(--mono);font-size:13px;font-weight:700;color:var(--text);}
.summary-body{padding:14px 18px;}
.summary-row{display:flex;flex-direction:column;gap:3px;padding:10px 0;border-bottom:1px solid var(--border);}
.summary-row:last-child{border-bottom:none;padding-bottom:0;}
.summary-row:first-child{padding-top:0;}
.summary-key{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.summary-val{font-size:12.5px;font-weight:500;color:var(--text);font-family:var(--mono);}
.action-zone{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .1s ease both;}
.action-zone-header{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--surface2);}
.action-zone-title{font-family:var(--mono);font-size:13px;font-weight:700;color:var(--text);}
.action-zone-body{padding:16px 18px;display:flex;flex-direction:column;gap:8px;}
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-success{background:var(--green-lt);border:1px solid rgba(5,196,138,.25);color:#059669;}
.flash svg{width:14px;height:14px;flex-shrink:0;}
.campaign-mini{display:flex;align-items:center;gap:12px;padding:12px;background:var(--surface2);border-radius:var(--r-sm);border:1px solid var(--border2);}
.campaign-mini-thumb{width:44px;height:44px;border-radius:9px;object-fit:cover;flex-shrink:0;background:var(--a-lt);}
.campaign-mini-placeholder{width:44px;height:44px;border-radius:9px;background:var(--a-lt);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.campaign-mini-placeholder svg{width:18px;height:18px;color:var(--a);opacity:.5;}
.campaign-mini-info{flex:1;min-width:0;}
.campaign-mini-title{font-size:13px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.campaign-mini-meta{font-size:10.5px;color:var(--text3);margin-top:2px;font-family:var(--mono);}
.desc-block{font-size:13.5px;color:var(--text2);line-height:1.75;white-space:pre-line;}
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}}
@media(max-width:700px){.stat-row{grid-template-columns:1fr 1fr}.detail-grid{grid-template-columns:1fr}}
@media(max-width:600px){.topbar{padding:0 16px}.body{padding:14px 14px 48px}}
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
    <div class="tb-left">
      <button class="hamburger" id="hamburger" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <a href="{{ route('admin.events.index') }}" class="tb-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Events
      </a>
      <div style="margin-left:2px;">
        <h1>{{ $event->title }}</h1>
        <p>#{{ $event->id }} · Created {{ $event->created_at->format('d M Y') }}</p>
      </div>
    </div>
    <div class="tb-right">
      <a href="{{ route('admin.events.edit', $event) }}" class="tb-back" style="color:var(--a);border-color:rgba(110,86,247,.25);background:var(--a-lt);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit
      </a>
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
        <div class="t-av" onclick="toggleDD()">
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

    @if(session('success'))
    <div class="flash flash-success">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
    @endif

    {{-- STATUS BANNER --}}
    @php
      $statusClass = match($event->status) {
        'active'    => 'sb-active',
        'pending'   => 'sb-pending',
        'draft'     => 'sb-draft',
        'cancelled' => 'sb-cancelled',
        'expired'   => 'sb-expired',
        'completed' => 'sb-completed',
        default     => 'sb-draft',
      };
    @endphp
    <div class="status-banner {{ $statusClass }}">
      @if($event->status === 'pending')
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="sb-text">
          <div class="sb-title">This event is awaiting approval</div>
          <div class="sb-sub">Review the event details and approve or reject it.</div>
        </div>
        <div style="display:flex;gap:8px;flex-shrink:0;">
          <form method="POST" action="{{ route('admin.events.approve', $event) }}">
            @csrf
            <button type="submit" class="btn btn-approve btn-sm">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Approve
            </button>
          </form>
          <form method="POST" action="{{ route('admin.events.reject', $event) }}">
            @csrf
            <button type="submit" class="btn btn-reject btn-sm">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
              Reject
            </button>
          </form>
        </div>
      @elseif($event->status === 'draft')
        <svg viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
        <div class="sb-text">
          <div class="sb-title">This event is saved as Draft</div>
          <div class="sb-sub">Not visible to the public. Edit anything you need, then publish when ready.</div>
        </div>
        <form method="POST" action="{{ route('admin.events.publish', $event) }}" style="flex-shrink:0;">
          @csrf
          <button type="submit" class="btn btn-publish btn-sm">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Publish Now
          </button>
        </form>
      @elseif($event->status === 'active')
        <svg viewBox="0 0 24 24" fill="none" stroke="#05c48a" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="sb-text">
          <div class="sb-title">This event is Live</div>
          <div class="sb-sub">Publicly visible and accepting registrations.</div>
        </div>
        <form method="POST" action="{{ route('admin.events.draft', $event) }}" style="flex-shrink:0;">
          @csrf
          <button type="submit" class="btn btn-draft btn-sm">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            Revert to Draft
          </button>
        </form>
      @elseif($event->status === 'completed')
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="sb-text">
          <div class="sb-title">This event has been completed</div>
          <div class="sb-sub">The event date has passed and it has been marked as completed.</div>
        </div>
      @elseif($event->status === 'cancelled')
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="sb-text">
          <div class="sb-title">This event has been cancelled</div>
          <div class="sb-sub">You can restore it by publishing again from the edit page.</div>
        </div>
      @else
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--gray)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <div class="sb-text">
          <div class="sb-title">Status: {{ ucfirst($event->status) }}</div>
          <div class="sb-sub">Edit the event to change its status.</div>
        </div>
      @endif
    </div>

    {{-- STATS --}}
    @php
      $raised   = $event->raised_amount ?? 0;
      $goal     = $event->goal_amount ?? 1;
      $pct      = $goal > 0 ? min(100, round($raised / $goal * 100)) : 0;
      $regCount = $event->registered_count ?? 0;
      $days     = $event->event_date ? now()->diffInDays($event->event_date, false) : null;
    @endphp
    <div class="stat-row">
      <div class="stat-mini">
        <div class="stat-mini-lbl">Raised</div>
        <div class="stat-mini-val" style="color:var(--green);">₹{{ number_format($raised, 0) }}</div>
        <div style="margin-top:8px;">
          <div class="progress-label"><span>{{ $pct }}%</span><span>of ₹{{ number_format($goal, 0) }}</span></div>
          <div class="progress-bar"><div class="progress-fill" style="width:{{ $pct }}%;"></div></div>
        </div>
      </div>
      <div class="stat-mini">
        <div class="stat-mini-lbl">Registrations</div>
        <div class="stat-mini-val">{{ $regCount }}</div>
        <div class="stat-mini-sub">
          @if($event->max_participants) of {{ $event->max_participants }} max
          @else No limit
          @endif
        </div>
      </div>
      <div class="stat-mini">
        <div class="stat-mini-lbl">Days Away</div>
        <div class="stat-mini-val" style="{{ $days !== null && $days < 0 ? 'color:var(--text3)' : '' }}">
          {{ $days === null ? '—' : ($days < 0 ? 'Past' : ($days === 0 ? 'Today' : $days)) }}
        </div>
        <div class="stat-mini-sub">{{ $event->event_date?->format('d M Y') ?? 'No date set' }}</div>
      </div>
    </div>

    <div class="show-grid">

      {{-- LEFT COLUMN --}}
      <div>

        {{-- Cover + Title --}}
        <div class="card">
          @if($event->cover_image)
            <img src="{{ asset('storage/'.$event->cover_image) }}" class="event-cover" alt="">
          @else
            <div class="event-cover-placeholder">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
          @endif
          <div class="card-body">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:14px;">
              <div>
                <h2 style="font-family:var(--mono);font-size:20px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1.2;margin-bottom:8px;">{{ $event->title }}</h2>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                  @if($event->campaign?->category)
                    <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:var(--a-lt);border:1px solid rgba(110,86,247,.2);border-radius:100px;font-size:11px;font-weight:700;color:var(--a);font-family:var(--mono);">
                      {{ $event->campaign->category->emoji ?? '' }} {{ $event->campaign->category->name }}
                    </span>
                  @endif
                  <span class="status-pill pill-{{ $event->status }}">
                    <span class="sp-dot"></span>{{ ucfirst($event->status) }}
                  </span>
                </div>
              </div>
              <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-edit btn-sm" style="flex-shrink:0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </a>
            </div>
            @if($event->description)
              <div style="border-top:1px solid var(--border);padding-top:16px;">
                <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:10px;">Description</div>
                <div class="desc-block">{{ $event->description }}</div>
              </div>
            @endif
          </div>
        </div>

        {{-- Event Details --}}
        <div class="card" style="animation-delay:.05s;">
          <div class="card-header">
            <div class="card-icon ci-amber">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
              <div class="card-title">Event Details</div>
              <div class="card-subtitle">Date, time, location, and participation</div>
            </div>
          </div>
          <div class="card-body">
            <div class="detail-grid">
              <div class="detail-item">
                <div class="detail-key">Event Date</div>
                <div class="detail-val">{{ $event->event_date?->format('l, d F Y') ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="detail-key">Location / Venue</div>
<div class="detail-val {{ $event->location ? '' : 'muted' }}">
    {{ $event->location ?: 'Not specified' }}
</div>
              </div>
              <div class="detail-item">
                <div class="detail-key">Start Time</div>
                <div class="detail-val {{ $event->start_time ? '' : 'muted' }}">
                  {{ $event->start_time ? date('g:i A', strtotime($event->start_time)) : 'Not set' }}
                </div>
              </div>
              <div class="detail-item">
                <div class="detail-key">End Time</div>
                <div class="detail-val {{ $event->end_time ? '' : 'muted' }}">
                  {{ $event->end_time ? date('g:i A', strtotime($event->end_time)) : 'Not set' }}
                </div>
              </div>
              <div class="detail-item">
                <div class="detail-key">Fundraising Goal</div>
                <div class="detail-val" style="color:var(--a);font-weight:700;">₹{{ number_format($event->goal_amount, 0) }}</div>
              </div>
              <div class="detail-item">
                <div class="detail-key">Max Participants</div>
                <div class="detail-val">{{ $event->max_participants ? number_format($event->max_participants) : 'Unlimited' }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Linked Campaign --}}
        <div class="card" style="animation-delay:.1s;">
          <div class="card-header">
            <div class="card-icon ci-green">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
              <div class="card-title">Linked Campaign</div>
              <div class="card-subtitle">The campaign this event is associated with</div>
            </div>
          </div>
          <div class="card-body">
            @if($event->campaign)
              <div class="campaign-mini">
                @if($event->campaign->cover_image)
                  <img src="{{ asset('storage/'.$event->campaign->cover_image) }}" class="campaign-mini-thumb" alt="">
                @else
                  <div class="campaign-mini-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                  </div>
                @endif
                <div class="campaign-mini-info">
                  <div class="campaign-mini-title">{{ $event->campaign->title }}</div>
                  <div class="campaign-mini-meta">Goal ₹{{ number_format($event->campaign->goal_amount ?? 0) }} · {{ ucfirst($event->campaign->campaign_state ?? 'active') }}</div>
                </div>
                <a href="{{ url('/admin/campaigns/'.$event->campaign->id) }}" class="btn btn-edit btn-sm">
                  View <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
              </div>
            @else
              <div style="text-align:center;padding:28px;color:var(--text3);font-size:13px;">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 8px;display:block;opacity:.2;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                No campaign linked
              </div>
            @endif
          </div>
        </div>

        {{-- EVENT SETTINGS — fixed toggle structure --}}
        <div class="card" style="animation-delay:.14s;">
          <div class="card-header">
            <div class="card-icon ci-purple">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <div>
              <div class="card-title">Event Settings</div>
              <div class="card-subtitle">Toggle settings — changes save instantly</div>
            </div>
          </div>
          <div class="card-body">

            {{-- Allow Registrations --}}
            <div class="setting-row">
              <div class="setting-icon ci-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
              </div>
              <div class="setting-info">
                <div class="setting-name">Allow Registrations</div>
                <div class="setting-desc">Participants can sign up for this event</div>
              </div>
              <form method="POST" action="{{ route('admin.events.toggleSetting', $event) }}" id="form_allow_reg">
                @csrf
                <input type="hidden" name="field" value="allow_registrations">
                <div class="toggle-wrap" onclick="document.getElementById('chk_allow_reg').click()">
                  <input type="checkbox" id="chk_allow_reg"
                         onchange="document.getElementById('form_allow_reg').submit()"
                         {{ $event->allow_registrations ? 'checked' : '' }}>
                  <div class="toggle-track"></div>
                </div>
              </form>
            </div>

            {{-- Show on Campaign Page --}}
            <div class="setting-row">
              <div class="setting-icon ci-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </div>
              <div class="setting-info">
                <div class="setting-name">Show on Campaign Page</div>
                <div class="setting-desc">Display this event on the linked campaign</div>
              </div>
              <form method="POST" action="{{ route('admin.events.toggleSetting', $event) }}" id="form_show_campaign">
                @csrf
                <input type="hidden" name="field" value="show_on_campaign">
                <div class="toggle-wrap" onclick="document.getElementById('chk_show_campaign').click()">
                  <input type="checkbox" id="chk_show_campaign"
                         onchange="document.getElementById('form_show_campaign').submit()"
                         {{ $event->show_on_campaign ? 'checked' : '' }}>
                  <div class="toggle-track"></div>
                </div>
              </form>
            </div>

            {{-- Send Notification Email --}}
            <div class="setting-row">
              <div class="setting-icon ci-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
              </div>
              <div class="setting-info">
                <div class="setting-name">Send Notification Email</div>
                <div class="setting-desc">Notify campaign followers when this event is published</div>
              </div>
              <form method="POST" action="{{ route('admin.events.toggleSetting', $event) }}" id="form_send_notif">
                @csrf
                <input type="hidden" name="field" value="send_notification">
                <div class="toggle-wrap" onclick="document.getElementById('chk_send_notif').click()">
                  <input type="checkbox" id="chk_send_notif"
                         onchange="document.getElementById('form_send_notif').submit()"
                         {{ ($event->send_notification ?? false) ? 'checked' : '' }}>
                  <div class="toggle-track"></div>
                </div>
              </form>
            </div>

          </div>
        </div>

        {{-- Bottom action row --}}
        <div style="display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;animation:fadeUp .4s .18s ease both;align-items:center;">
          <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-publish">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Event
          </a>
          <a href="{{ route('admin.events.index') }}" class="btn btn-edit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            All Events
          </a>
          <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                onsubmit="return confirm('Permanently delete \'{{ addslashes($event->title) }}\'? This cannot be undone.')"
                style="margin-left:auto;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              Delete Event
            </button>
          </form>
        </div>

      </div>{{-- /left --}}

      {{-- RIGHT SIDEBAR --}}
      <div class="show-sidebar">

        {{-- Action Zone --}}
        <div class="action-zone">
          <div class="action-zone-header">
            <div class="action-zone-title">Actions</div>
          </div>
          <div class="action-zone-body">

            @if($event->status === 'pending')
              <form method="POST" action="{{ route('admin.events.approve', $event) }}">
                @csrf
                <button type="submit" class="btn btn-approve" style="width:100%;justify-content:center;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  Approve Event
                </button>
              </form>
              <form method="POST" action="{{ route('admin.events.reject', $event) }}">
                @csrf
                <button type="submit" class="btn btn-reject" style="width:100%;justify-content:center;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                  Reject Event
                </button>
              </form>
              <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-edit" style="width:100%;justify-content:center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Event
              </a>
            @elseif($event->status === 'draft')
              <form method="POST" action="{{ route('admin.events.publish', $event) }}">
                @csrf
                <button type="submit" class="btn btn-publish" style="width:100%;justify-content:center;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  Publish Event
                </button>
              </form>
              <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-edit" style="width:100%;justify-content:center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Continue Editing
              </a>
            @elseif($event->status === 'active')
              <form method="POST" action="{{ route('admin.events.draft', $event) }}">
                @csrf
                <button type="submit" class="btn btn-draft" style="width:100%;justify-content:center;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                  Revert to Draft
                </button>
              </form>
              <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-edit" style="width:100%;justify-content:center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Event
              </a>
            @else
              <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-publish" style="width:100%;justify-content:center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Event
              </a>
            @endif

            <div style="height:1px;background:var(--border);margin:2px 0;"></div>
            <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                  onsubmit="return confirm('Permanently delete this event?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete Event
              </button>
            </form>
          </div>
        </div>

        {{-- Summary card --}}
        <div class="card" style="animation-delay:.08s;">
          <div class="summary-hdr"><div class="summary-hdr-title">Event Summary</div></div>
          <div class="summary-body">
            <div class="summary-row">
              <div class="summary-key">Status</div>
              <span class="status-pill pill-{{ $event->status }}">
                <span class="sp-dot"></span>{{ ucfirst($event->status) }}
              </span>
            </div>
            <div class="summary-row">
              <div class="summary-key">Event ID</div>
              <div class="summary-val">#{{ $event->id }}</div>
            </div>
            <div class="summary-row">
              <div class="summary-key">Category</div>
              <div class="summary-val">{{ $event->campaign?->category ? (($event->campaign->category->emoji ?? '').' '.$event->campaign->category->name) : '—' }}</div>
            </div>
            <div class="summary-row">
              <div class="summary-key">Campaign</div>
              <div class="summary-val" style="font-size:11.5px;line-height:1.3;">{{ $event->campaign->title ?? '—' }}</div>
            </div>
            <div class="summary-row">
              <div class="summary-key">Date</div>
              <div class="summary-val">{{ $event->event_date?->format('d M Y') ?? '—' }}</div>
            </div>
            <div class="summary-row">
              <div class="summary-key">Time</div>
              <div class="summary-val">
                @if($event->start_time)
                  {{ date('g:i A', strtotime($event->start_time)) }}
                  @if($event->end_time) – {{ date('g:i A', strtotime($event->end_time)) }}@endif
                @else —
                @endif
              </div>
            </div>
            <div class="summary-row">
              <div class="summary-key">Goal</div>
              <div class="summary-val" style="color:var(--a);">₹{{ number_format($event->goal_amount, 0) }}</div>
            </div>
            <div class="summary-row">
              <div class="summary-key">Created</div>
              <div class="summary-val" style="font-size:11px;">{{ $event->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div class="summary-row">
              <div class="summary-key">Last Updated</div>
              <div class="summary-val" style="font-size:11px;">{{ $event->updated_at->format('d M Y, H:i') }}</div>
            </div>
          </div>
        </div>

        {{-- Fundraising card --}}
        <div class="card" style="animation-delay:.14s;">
          <div class="summary-hdr"><div class="summary-hdr-title">Fundraising</div></div>
          <div class="summary-body">
            <div class="summary-row">
              <div class="summary-key">Raised</div>
              <div class="summary-val" style="color:var(--green);font-size:15px;">₹{{ number_format($raised, 0) }}</div>
            </div>
            <div class="summary-row">
              <div class="summary-key">Progress</div>
              <div style="margin-top:4px;">
                <div class="progress-label"><span style="color:var(--text);">{{ $pct }}%</span><span>₹{{ number_format($goal, 0) }} goal</span></div>
                <div class="progress-bar"><div class="progress-fill" style="width:{{ $pct }}%;"></div></div>
              </div>
            </div>
            <div class="summary-row">
              <div class="summary-key">Registrations</div>
              <div class="summary-val">{{ $regCount }}
                @if($event->max_participants)<span style="font-size:11px;color:var(--text3);"> / {{ $event->max_participants }}</span>@endif
              </div>
            </div>
          </div>
        </div>

      </div>{{-- /sidebar --}}
    </div>{{-- /show-grid --}}
  </div>{{-- /body --}}
</div>{{-- /main --}}
</div>{{-- /shell --}}

<script>
(function(){
'use strict';
var html   = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved  = localStorage.getItem('adminTheme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
  var t = this.checked ? 'dark' : 'light';
  html.setAttribute('data-theme', t);
  localStorage.setItem('adminTheme', t);
});
document.getElementById('hamburger').addEventListener('click', function(){
  document.getElementById('sidebar').classList.toggle('open');
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