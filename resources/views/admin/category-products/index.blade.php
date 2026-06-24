<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Category Products — DonateBazaar Admin</title>
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

/* ═══ SIDEBAR ═══ */
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

/* ═══ MAIN ═══ */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* ═══ TOPBAR ═══ */
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
.theme-wrap input:checked+label::after{transform:translateX(23px);}
.ti{display:flex;justify-content:space-between;width:100%;position:relative;z-index:1;padding:0 2px;}
.ti svg{width:11px;height:11px;color:var(--text3);}
.t-av{width:36px;height:36px;border-radius:var(--r-sm);background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;overflow:hidden;box-shadow:0 2px 10px rgba(110,86,247,.38);}
.hamburger{display:none;width:36px;height:36px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);cursor:pointer;color:var(--text2);align-items:center;justify-content:center;flex-shrink:0;}
.hamburger svg{width:15px;height:15px;}
.add-btn{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 16px;background:var(--a);color:#fff;border:none;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;text-decoration:none;cursor:pointer;transition:opacity .2s,transform .15s;font-family:var(--font);box-shadow:0 4px 14px rgba(110,86,247,.3);}
.add-btn:hover{opacity:.88;transform:translateY(-1px);}
.add-btn svg{width:13px;height:13px;}

/* ═══ BODY ═══ */
.body{padding:26px 28px 56px;flex:1;}

/* ═══ STATS ═══ */
.stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:22px;}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:20px 22px;box-shadow:var(--sh);display:flex;align-items:flex-start;gap:15px;transition:transform var(--ease),box-shadow var(--ease);animation:fadeUp .4s ease both;cursor:default;position:relative;overflow:hidden;}
.stat:hover{transform:translateY(-3px);box-shadow:var(--sh-md);}
.stat::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2.5px;border-radius:0 0 var(--r) var(--r);opacity:0;transition:opacity var(--ease);}
.stat:hover::after{opacity:1;}
.stat:nth-child(1){animation-delay:.05s;}.stat:nth-child(1)::after{background:linear-gradient(90deg,var(--a),var(--a2));}
.stat:nth-child(2){animation-delay:.10s;}.stat:nth-child(2)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat:nth-child(3){animation-delay:.15s;}.stat:nth-child(3)::after{background:linear-gradient(90deg,var(--red),#f87171);}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:19px;height:19px;}
.si-a{background:var(--a-lt);color:var(--a);}
.si-green{background:var(--green-lt);color:var(--green);}
.si-red{background:var(--red-lt);color:var(--red);}
.stat-body{flex:1;min-width:0;}
.stat-lbl{font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:6px;}
.stat-val{font-family:var(--mono);font-size:2rem;font-weight:800;line-height:1;letter-spacing:-.03em;}
.sv-a{color:var(--a);}.sv-green{color:var(--green);}.sv-red{color:var(--red);}
.stat-foot{font-size:11px;color:var(--text3);margin-top:5px;}

/* ═══ TOOLBAR ═══ */
.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.toolbar-left{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.search-wrap{position:relative;}
.search-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;}
.search-input{width:220px;height:36px;padding:0 12px 0 33px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease),width .3s ease;}
.search-input::placeholder{color:var(--text3);}
.search-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);width:260px;}
.filter-btn{display:inline-flex;align-items:center;gap:5px;height:36px;padding:0 13px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all var(--ease);}
.filter-btn:hover,.filter-btn.on{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.cnt-badge{background:var(--a);color:#fff;font-size:9.5px;font-weight:700;padding:1px 5px;border-radius:100px;font-family:var(--mono);}

/* ═══ MAIN CARD ═══ */
.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .15s ease both;}
.card-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.card-head-left{display:flex;align-items:center;gap:10px;}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);background:var(--surface);border:1px solid var(--border2);padding:2px 8px;border-radius:100px;}

/* ═══ TABLE ═══ */
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{padding:10px 18px;text-align:left;font-size:10px;font-family:var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--text3);background:var(--surface2);border-bottom:1px solid var(--border);font-weight:500;white-space:nowrap;}
thead th:last-child{text-align:right;}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
td{padding:13px 18px;font-size:13px;vertical-align:middle;}
.serial{font-size:11.5px;color:var(--text3);font-family:var(--mono);}

/* Product image / placeholder */
.prod-img{width:52px;height:52px;border-radius:12px;object-fit:cover;border:1px solid var(--border);}
.prod-placeholder{width:52px;height:52px;border-radius:12px;background:var(--a-lt);display:flex;align-items:center;justify-content:center;color:var(--a);font-size:18px;flex-shrink:0;border:1px solid rgba(110,86,247,.15);}

/* Product name cell */
.prod-name{font-weight:600;color:var(--text);font-size:13.5px;margin-bottom:2px;}
.prod-desc{font-size:11.5px;color:var(--text3);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:200px;}

/* Type badge */
.type-pill{display:inline-flex;align-items:center;height:22px;padding:0 9px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.04em;background:var(--blue-lt);color:var(--blue);border:1px solid rgba(59,130,246,.18);}

/* Price */
.price-val{font-family:var(--mono);font-size:13px;font-weight:700;color:var(--text);}

/* Stock */
.stock-val{font-family:var(--mono);font-size:12.5px;color:var(--text2);}
.stock-low{color:var(--amber);}
.stock-out{color:var(--red);}

/* Status pill */
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;}
.s-active{background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.22);}
.s-inactive{background:rgba(100,116,139,.08);color:var(--text3);border:1px solid var(--border2);}
.status-dot{width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;}

/* Actions */
.actions{display:flex;align-items:center;justify-content:flex-end;gap:5px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:500;text-decoration:none;border:1px solid transparent;transition:all .15s;cursor:pointer;font-family:var(--font);white-space:nowrap;}
.act-btn svg{width:11px;height:11px;}
.act-edit{background:var(--blue-lt);color:var(--blue);border-color:rgba(59,130,246,.18);}
.act-edit:hover{background:var(--blue);color:#fff;transform:translateY(-1px);}
.act-del{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.18);}
.act-del:hover{background:var(--red);color:#fff;transform:translateY(-1px);}

/* Empty state */
.empty-state{padding:72px 24px;text-align:center;}
.empty-icon-wrap{width:72px;height:72px;border-radius:20px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:26px;margin:0 auto 18px;animation:float 3s ease-in-out infinite;}
.empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
.empty-state p{font-size:13px;color:var(--text3);margin-bottom:20px;}

/* Alert */
.alert-ok{background:rgba(5,196,138,.08);border:1px solid rgba(5,196,138,.22);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease;}
[data-theme="dark"] .alert-ok{color:#6ee7b7;}
.alert-ok svg{width:15px;height:15px;flex-shrink:0;}

/* Pagination */
.pagination-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--border);background:var(--surface2);flex-wrap:wrap;gap:10px;}
.page-info{font-size:12px;color:var(--text3);font-family:var(--mono);}
.page-btns{display:flex;gap:4px;}
.page-btn{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11.5px;font-weight:600;border:1px solid var(--border2);background:var(--surface);color:var(--text2);cursor:pointer;transition:all .15s;font-family:var(--mono);text-decoration:none;}
.page-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.page-btn.cur{background:var(--a);border-color:var(--a);color:#fff;}
.page-btn svg{width:12px;height:12px;}

/* Modal */
.overlay{display:none;position:fixed;inset:0;z-index:9998;background:rgba(4,5,14,.65);backdrop-filter:blur(12px);align-items:center;justify-content:center;padding:20px;}
.overlay.open{display:flex;}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:22px;box-shadow:var(--sh-lg);width:100%;max-width:390px;padding:28px;position:relative;animation:modalIn .2s ease;}
@keyframes modalIn{from{opacity:0;transform:scale(.95) translateY(12px)}to{opacity:1;transform:none}}
.modal-x{position:absolute;top:16px;right:16px;width:28px;height:28px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);cursor:pointer;color:var(--text2);display:flex;align-items:center;justify-content:center;transition:all var(--ease);}
.modal-x:hover{background:var(--border2);transform:rotate(90deg);}
.modal-x svg{width:11px;height:11px;}
.modal-ico{width:48px;height:48px;border-radius:14px;background:var(--red-lt);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;}
.modal-ico svg{width:22px;height:22px;color:var(--red);}
.modal h3{font-size:16px;font-weight:700;color:var(--text);text-align:center;margin-bottom:8px;font-family:var(--mono);}
.modal p{font-size:13px;color:var(--text3);text-align:center;line-height:1.6;margin-bottom:22px;}
.modal-acts{display:flex;gap:10px;}
.modal-cancel{flex:1;height:40px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);font-size:13px;font-weight:600;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all var(--ease);}
.modal-cancel:hover{background:var(--surface3);}
.modal-del{flex:1;height:40px;border-radius:var(--r-sm);border:none;background:linear-gradient(135deg,var(--red),#dc2626);font-size:13px;font-weight:600;color:#fff;cursor:pointer;font-family:var(--font);transition:opacity var(--ease);box-shadow:0 4px 16px rgba(240,68,68,.3);}
.modal-del:hover{opacity:.88;}

/* Toast */
.toast-wrap{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:270px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s ease both;}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-ok{background:linear-gradient(135deg,#059669,#10b981);}
.toast-err{background:linear-gradient(135deg,#dc2626,#f04444);}

::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
@keyframes toastIn{from{opacity:0;transform:translateX(18px) scale(.96)}to{opacity:1;transform:none}}
@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}}
@media(max-width:600px){.topbar{padding:0 16px}.body{padding:14px 14px 48px}.stats-grid{grid-template-columns:1fr 1fr}}
</style>
</head>
<body>

<div class="toast-wrap" id="toastWrap"></div>

<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeModal()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Product?</h3>
    <p>This will permanently remove <strong id="modalProdName"></strong>. This action cannot be undone.</p>
    <div class="modal-acts">
      <button class="modal-cancel" onclick="closeModal()">Cancel</button>
      <button class="modal-del" onclick="confirmDelete()">Yes, Delete</button>
    </div>
  </div>
</div>
<form id="deleteForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>

<div class="shell">

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
      <span class="s-chip sc-purple">{{ $products->total() ?? $products->count() }}</span>
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
        <h1>Category Products</h1>
        <p>Manage fundraising products</p>
      </div>
    </div>
    <div class="tb-right">
      <a href="{{ route('admin.category-products.create') }}" class="add-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add Product
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
    @if(session('success'))
    <div class="alert-ok" id="flashAlert">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
    @endif

    @php
      $total=$products->total()??$products->count();
      $active=$products->where('is_active',1)->count();
      $inactive=$total-$active;
    @endphp

    {{-- STATS --}}
    <div class="stats-grid">
      <div class="stat">
        <div class="stat-icon si-a"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Total Products</div><div class="stat-val sv-a">{{ $total }}</div><div class="stat-foot">All products</div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Active</div><div class="stat-val sv-green">{{ $active }}</div><div class="stat-foot">Visible to public</div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Inactive</div><div class="stat-val sv-red">{{ $inactive }}</div><div class="stat-foot">Hidden from public</div></div>
      </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="toolbar">
      <div class="toolbar-left">
        <div class="search-wrap">
          <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input type="text" class="search-input" id="searchInput" placeholder="Search products…" oninput="filterRows()">
        </div>
        <button class="filter-btn on" id="fAll" onclick="setFilter('all',this)">All <span class="cnt-badge">{{ $total }}</span></button>
        <button class="filter-btn" id="fActive" onclick="setFilter('active',this)">
          <span style="width:6px;height:6px;border-radius:50%;background:var(--green);display:inline-block;"></span>Active
        </button>
        <button class="filter-btn" id="fInactive" onclick="setFilter('inactive',this)">Inactive</button>
      </div>
    </div>

    {{-- TABLE --}}
    <div class="main-card">
      <div class="card-head">
        <div class="card-head-left">
          <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
          <span class="card-head-title">All Products</span>
        </div>
        <span class="card-head-count" id="visibleCount">{{ $total }} total</span>
      </div>

      @if($products->isEmpty())
      <div class="empty-state">
        <div class="empty-icon-wrap">📦</div>
        <h3>No products yet</h3>
        <p>Add your first category product to get started</p>
        <a href="{{ route('admin.category-products.create') }}" class="add-btn" style="margin:0 auto;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Add Product
        </a>
      </div>
      @else
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th style="width:50px;">#</th>
              <th style="width:70px;">Image</th>
              <th>Product</th>
              <th>Category</th>
              <th>Type</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            @forelse($products as $product)
            <tr class="prod-row"
              data-name="{{ strtolower($product->name) }}"
              data-status="{{ $product->is_active?'active':'inactive' }}"
              style="animation:fadeUp 0.35s {{ $loop->index*0.04 }}s ease both;opacity:0;animation-fill-mode:both;">
              <td><span class="serial">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
              <td>
                @if($product->image)
                  <img src="{{ asset('storage/'.$product->image) }}" class="prod-img" alt="{{ $product->name }}">
                @else
                  <div class="prod-placeholder"><i class="fa fa-box"></i></div>
                @endif
              </td>
              <td>
                <div class="prod-name">{{ $product->name }}</div>
                <div class="prod-desc">{{ $product->description }}</div>
              </td>
              <td>
                <span style="font-size:12.5px;color:var(--text2);font-weight:500;">{{ $product->category->name??'—' }}</span>
              </td>
              <td><span class="type-pill">{{ ucfirst($product->product_type) }}</span></td>
              <td><span class="price-val">₹{{ number_format($product->price,2) }}</span></td>
              <td>
                <span class="stock-val {{ $product->stock==0?'stock-out':($product->stock<10?'stock-low':'') }}">
                  {{ $product->stock }}
                </span>
              </td>
              <td>
                @if($product->is_active)
                  <span class="status-pill s-active"><span class="status-dot"></span> Active</span>
                @else
                  <span class="status-pill s-inactive"><span class="status-dot"></span> Inactive</span>
                @endif
              </td>
              <td>
                <div class="actions">
                  <a href="{{ route('admin.category-products.edit',$product->id) }}" class="act-btn act-edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit
                  </a>
                  <button type="button" class="act-btn act-del"
                    onclick="openModal('{{ $product->id }}','{{ addslashes($product->name) }}','{{ route('admin.category-products.destroy',$product->id) }}')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
                  </button>
                </div>
              </td>
            </tr>
            @empty
            <tr><td colspan="9">
              <div class="empty-state"><div class="empty-icon-wrap">📦</div><h3>No products found</h3><p>Try adjusting your search or filters</p></div>
            </td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
      <div class="pagination-wrap">
        <span class="page-info">Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}</span>
        <div class="page-btns">
          @if($products->onFirstPage())
            <span class="page-btn" style="opacity:.4;cursor:not-allowed;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></span>
          @else
            <a href="{{ $products->previousPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
          @endif
          @foreach($products->getUrlRange(1,$products->lastPage()) as $page=>$url)
            <a href="{{ $url }}" class="page-btn {{ $products->currentPage()==$page?'cur':'' }}">{{ $page }}</a>
          @endforeach
          @if($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
          @else
            <span class="page-btn" style="opacity:.4;cursor:not-allowed;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
          @endif
        </div>
      </div>
      @endif
      @endif
    </div>
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

(function(){var a=document.getElementById('flashAlert');if(!a)return;setTimeout(function(){a.style.transition='opacity .4s,transform .4s';a.style.opacity='0';a.style.transform='translateY(-6px)';setTimeout(function(){a.remove();},400);},4000);})();

var activeFilter='all';
window.setFilter=function(f,btn){
  activeFilter=f;
  document.querySelectorAll('.filter-btn').forEach(function(b){b.classList.remove('on');});
  if(btn)btn.classList.add('on');
  filterRows();
};

window.filterRows=function(){
  var q=document.getElementById('searchInput').value.toLowerCase().trim();
  var rows=document.querySelectorAll('.prod-row');
  var visible=0;
  rows.forEach(function(r){
    var ok=(!q||(r.getAttribute('data-name')||'').includes(q))&&(activeFilter==='all'||r.getAttribute('data-status')===activeFilter);
    r.style.display=ok?'':'none';
    if(ok)visible++;
  });
  document.getElementById('visibleCount').textContent=visible+' total';
};

var pendingUrl=null;
window.openModal=function(id,name,url){
  pendingUrl=url;
  document.getElementById('modalProdName').textContent='"'+name+'"';
  document.getElementById('deleteOverlay').classList.add('open');
};
window.closeModal=function(){document.getElementById('deleteOverlay').classList.remove('open');pendingUrl=null;};
window.confirmDelete=function(){if(!pendingUrl)return;var f=document.getElementById('deleteForm');f.action=pendingUrl;f.submit();};
document.getElementById('deleteOverlay').addEventListener('click',function(e){if(e.target===this)closeModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeModal();});
})();
</script>
</body>
</html>