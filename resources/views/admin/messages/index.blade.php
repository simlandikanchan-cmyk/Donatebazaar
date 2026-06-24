<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Contact Messages — DonateBazaar Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
/* ── DESIGN TOKENS (matches dashboard) ── */
:root{
  --bg:#f4f5fb;--surface:#fff;--surface2:#f8f9fe;--surface3:#eef0fa;
  --border:rgba(0,0,0,.06);--border2:rgba(0,0,0,.10);
  --text:#0a0b14;--text2:#454863;--text3:#9096b4;
  --sb-bg:#ffffff;--sb-txt:#5a5f7a;--sb-act:rgba(110,86,247,.10);
  --sb-border:rgba(0,0,0,.08);
  --a:#6e56f7;--a2:#9b6dff;--a-lt:rgba(110,86,247,.10);--a-glow:rgba(110,86,247,.22);
  --green:#05c48a;--green-lt:rgba(5,196,138,.10);
  --amber:#f59e0b;--amber-lt:rgba(245,158,11,.10);
  --red:#f04444;--red-lt:rgba(240,68,68,.10);
  --blue:#3b82f6;--blue-lt:rgba(59,130,246,.10);
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
  --sb-bg:#050609;--sb-txt:rgba(255,255,255,.48);--sb-act:rgba(110,86,247,.22);
  --sb-border:rgba(255,255,255,.03);
  --a-glow:rgba(110,86,247,.30);
  --sh:0 1px 3px rgba(0,0,0,.35),0 4px 24px rgba(0,0,0,.25);
  --sh-md:0 4px 20px rgba(0,0,0,.4),0 1px 4px rgba(0,0,0,.25);
  --sh-lg:0 12px 48px rgba(0,0,0,.6);
}

/* ── RESET ── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;}
body{font-family:var(--font);background:var(--bg);color:var(--text);line-height:1.55;-webkit-font-smoothing:antialiased;overflow-x:hidden;transition:background .2s,color .2s;}
a{text-decoration:none;color:inherit;}
button{cursor:pointer;font-family:var(--font);}
svg{display:block;flex-shrink:0;}

/* ── LAYOUT ── */
.shell{display:flex;min-height:100vh;}

/* ── SIDEBAR ── */
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
  color:var(--sb-txt);font-size:13px;font-weight:500;
  transition:background var(--ease),color var(--ease);
  margin-bottom:1px;border:none;background:transparent;
  width:100%;text-align:left;cursor:pointer;position:relative;
}
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

/* ── PAGE BODY ── */
.body{padding:26px 28px 56px;flex:1;}

/* ── STATS ── */
.stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:20px 22px;box-shadow:var(--sh);display:flex;align-items:flex-start;gap:15px;transition:transform var(--ease),box-shadow var(--ease);animation:fadeUp .4s ease both;cursor:default;position:relative;overflow:hidden;}
.stat:hover{transform:translateY(-3px);box-shadow:var(--sh-md);}
.stat::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2.5px;border-radius:0 0 var(--r) var(--r);opacity:0;transition:opacity var(--ease);}
.stat:hover::after{opacity:1;}
.stat:nth-child(1){animation-delay:.05s;}.stat:nth-child(1)::after{background:linear-gradient(90deg,var(--blue),#6366f1);}
.stat:nth-child(2){animation-delay:.10s;}.stat:nth-child(2)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat:nth-child(3){animation-delay:.15s;}.stat:nth-child(3)::after{background:linear-gradient(90deg,var(--a),var(--a2));}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:19px;height:19px;}
.si-blue{background:var(--blue-lt);color:var(--blue);}
.si-green{background:var(--green-lt);color:var(--green);}
.si-purple{background:var(--a-lt);color:var(--a);}
.stat-body{flex:1;min-width:0;}
.stat-lbl{font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:6px;}
.stat-val{font-family:var(--mono);font-size:2rem;font-weight:800;line-height:1;letter-spacing:-.03em;}
.sv-blue{color:var(--blue);}.sv-green{color:var(--green);}.sv-purple{color:var(--a);}
.stat-foot{font-size:11px;color:var(--text3);margin-top:5px;}

/* ── FLASH ── */
.flash-ok{background:rgba(5,196,138,.09);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px;}
[data-theme="dark"] .flash-ok{color:#34d399;}
.flash-ok svg{width:15px;height:15px;flex-shrink:0;}

/* ── SECTION HEADER ── */
.sec-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:12px;animation:fadeUp .4s .2s ease both;}
.sec-ttl{font-family:var(--mono);font-size:18px;font-weight:700;color:var(--text);letter-spacing:-.02em;}

/* ── FILTER TABS ── */
.ftabs{display:flex;gap:2px;background:var(--surface2);border:1px solid var(--border);padding:4px;border-radius:14px;}
.ftab{padding:5px 13px;border-radius:10px;font-size:12px;font-weight:500;cursor:pointer;border:none;background:transparent;color:var(--text3);transition:all var(--ease);display:inline-flex;align-items:center;gap:5px;font-family:var(--font);white-space:nowrap;}
.ftab:hover{color:var(--a);}
.ftab.on{background:var(--surface);color:var(--a);font-weight:700;box-shadow:0 1px 8px rgba(110,86,247,.14);}
.ftab .cnt{display:inline-flex;align-items:center;justify-content:center;min-width:17px;height:17px;border-radius:100px;font-size:10px;padding:0 4px;background:var(--a-lt);color:var(--a);font-weight:700;font-family:var(--mono);}
.ftab.on .cnt{background:var(--a);color:#fff;}

/* ── FILTER BAR ── */
.filter-bar{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:14px 18px;margin-bottom:16px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;box-shadow:var(--sh);animation:fadeUp .4s .18s ease both;}
.filter-group{display:flex;align-items:center;gap:6px;flex-shrink:0;}
.filter-lbl{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);white-space:nowrap;}
.filter-sel{height:32px;padding:0 26px 0 10px;border-radius:var(--r-xs);border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-size:12px;font-family:var(--font);outline:none;cursor:pointer;transition:border-color var(--ease),box-shadow var(--ease);appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2.5'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;}
.filter-sel:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.filter-date{height:32px;padding:0 10px;border-radius:var(--r-xs);border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-size:12px;font-family:var(--mono);outline:none;cursor:pointer;transition:border-color var(--ease),box-shadow var(--ease);}
.filter-date:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.filter-date::-webkit-calendar-picker-indicator{opacity:.4;cursor:pointer;}
[data-theme="dark"] .filter-date::-webkit-calendar-picker-indicator{filter:invert(1);opacity:.4;}
.filter-div{width:1px;height:22px;background:var(--border2);flex-shrink:0;}
.filter-reset{margin-left:auto;display:inline-flex;align-items:center;gap:5px;height:32px;padding:0 12px;border-radius:var(--r-xs);border:1px solid var(--border2);background:transparent;color:var(--text3);font-size:11.5px;font-weight:600;cursor:pointer;transition:all var(--ease);font-family:var(--font);white-space:nowrap;}
.filter-reset:hover{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.3);}
.filter-reset svg{width:11px;height:11px;}

/* ── TABLE CARD ── */
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .25s ease both;}
.table-scroll{overflow-x:auto;}
.table-scroll::-webkit-scrollbar{height:5px;}
.table-scroll::-webkit-scrollbar-track{background:var(--surface2);}
.table-scroll::-webkit-scrollbar-thumb{background:rgba(110,86,247,.35);border-radius:10px;}
.table-scroll::-webkit-scrollbar-thumb:hover{background:var(--a);}
table{width:100%;min-width:700px;border-collapse:collapse;}
thead tr{border-bottom:1px solid var(--border);}
thead th{padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);background:var(--surface2);white-space:nowrap;}
thead th.sortable{cursor:pointer;user-select:none;transition:color var(--ease);}
thead th.sortable:hover{color:var(--a);}
.sort-arrows{display:inline-flex;flex-direction:column;gap:1px;margin-left:4px;vertical-align:middle;opacity:.4;}
thead th.sort-asc .sort-arrows,thead th.sort-desc .sort-arrows{opacity:1;color:var(--a);}
.sort-arrows svg{width:7px;height:7px;}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
tbody tr.row-hidden{display:none;}
tbody td{padding:14px 16px;font-size:13px;color:var(--text2);white-space:nowrap;vertical-align:middle;}

/* ── SENDER CELL ── */
.sender-cell{display:flex;align-items:center;gap:11px;}
.row-av{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);}
.sender-name{font-size:13px;font-weight:600;color:var(--text);line-height:1.3;}
.sender-email{font-size:10.5px;color:var(--text3);font-family:var(--mono);margin-top:1px;}

/* ── MESSAGE CELL ── */
td.msg-cell{white-space:normal;max-width:340px;font-size:12.5px;line-height:1.55;color:var(--text2);}

/* ── DATE CELL ── */
td.date-cell{font-family:var(--mono);font-size:11.5px;color:var(--text3);}
.date-ago{font-size:10px;color:var(--text3);margin-top:2px;font-family:var(--mono);}

/* ── BADGES ── */
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:4px 9px;border-radius:7px;text-transform:uppercase;letter-spacing:.07em;font-family:var(--mono);}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
.b-new{background:rgba(59,130,246,.12);color:#1d4ed8;border:1px solid rgba(59,130,246,.2);}
.b-read{background:rgba(5,196,138,.12);color:#065f46;border:1px solid rgba(5,196,138,.2);}
[data-theme="dark"] .b-new{color:#93c5fd;}
[data-theme="dark"] .b-read{color:#34d399;}

/* ── ACTION BUTTONS ── */
.actions{display:flex;align-items:center;gap:5px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:6px 11px;border-radius:var(--r-xs);font-size:11px;font-weight:600;cursor:pointer;border:1px solid transparent;transition:all var(--ease);white-space:nowrap;font-family:var(--font);text-decoration:none;}
.act-btn:hover{transform:translateY(-1px);}
.act-btn:active{transform:scale(.96);}
.act-btn svg{width:11px;height:11px;}
.ab-view{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.2);}
.ab-view:hover{background:var(--a);color:#fff;box-shadow:0 4px 14px rgba(110,86,247,.35);}
.ab-delete{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.2);}
.ab-delete:hover{background:var(--red);color:#fff;box-shadow:0 4px 14px rgba(240,68,68,.3);}

/* ── EMPTY STATES ── */
.empty-row td{padding:60px 20px;text-align:center;}
.empty-wrap{display:flex;flex-direction:column;align-items:center;gap:8px;color:var(--text3);}
.empty-wrap svg{width:44px;height:44px;opacity:.2;}
.empty-wrap strong{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2);}
.empty-wrap p{font-size:13px;}

/* ── TABLE FOOTER ── */
.table-footer{display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-top:1px solid var(--border);background:var(--surface2);}
.tfoot-info{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.tfoot-info strong{color:var(--text);font-weight:600;}
.tfoot-total{font-size:11px;color:var(--text3);font-family:var(--mono);}

/* ── PAGINATION ── */
.pagination-wrap{margin-top:20px;display:flex;align-items:center;justify-content:center;gap:10px;}
.pg-pages{display:flex;align-items:center;gap:6px;}
.pg-page,.pg-arrow{width:38px;height:38px;border-radius:var(--r-sm);border:1px solid var(--border);background:var(--surface);color:var(--text2);display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:14px;font-weight:700;transition:all var(--ease);box-shadow:var(--sh);}
.pg-page:hover,.pg-arrow:hover{background:var(--a-lt);border-color:var(--a);color:var(--a);transform:translateY(-2px);}
.pg-page.active{background:linear-gradient(135deg,var(--a),var(--a2));border-color:transparent;color:#fff;box-shadow:0 8px 20px rgba(110,86,247,.35);}
.pg-arrow.disabled{opacity:.3;pointer-events:none;}

/* ── TOAST ── */
.toast-wrap{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:270px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s ease both;}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-ok{background:linear-gradient(135deg,#059669,#10b981);}
.toast-x{margin-left:auto;width:18px;height:18px;border-radius:5px;background:rgba(255,255,255,.22);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}

/* ── SCROLLBAR ── */
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}

/* ── ANIMATIONS ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
@keyframes toastIn{from{opacity:0;transform:translateX(18px) scale(.96)}to{opacity:1;transform:none}}

/* ── RESPONSIVE ── */
@media(max-width:1024px){.stats-grid{grid-template-columns:1fr 1fr 1fr;}}
@media(max-width:860px){
  .sidebar{transform:translateX(-100%);}.sidebar.open{transform:translateX(0);}
  .main{margin-left:0;}.hamburger{display:flex;}
  .search-wrap{display:none;}
}
@media(max-width:600px){
  .topbar{padding:0 16px;}.body{padding:14px 14px 48px;}
  .stats-grid{grid-template-columns:1fr 1fr;}
  .stat-val{font-size:1.5rem;}
}
</style>
</head>
<body>

<div class="toast-wrap" id="toastWrap"></div>

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
    <a href="{{ url('/admin/partnerships') }}" class="s-link">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
      Partnerships
      @php $cntPend = isset($partnerships) ? $partnerships->where('status','pending')->count() : 0; @endphp
      @if($cntPend > 0)<span class="s-chip sc-amber">{{ $cntPend }}</span>@endif
    </a>
    <a href="{{ url('/admin/messages') }}" class="s-link active">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      Messages
      <span class="s-chip sc-amber" id="sidebarUnread" style="display:none;"></span>
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
        <h1>Contact Messages</h1>
        <p>Manage user inquiries and support messages</p>
      </div>
    </div>
    <div class="tb-right">
      <div class="search-wrap">
        <svg class="s-icon-inp" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input class="search-inp" id="searchInput" type="text" placeholder="Search messages…" autocomplete="off">
      </div>
      <button class="tb-btn" title="Notifications">
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

  <div class="body">

    @php
      $cntTotal = $messages->total();
      $cntRead  = $messages->filter(fn($m) => isset($m->read_at) && $m->read_at)->count();
      $cntNew   = $cntTotal - $cntRead;
      $cntToday = $messages->filter(fn($m) => $m->created_at->isToday())->count();
    @endphp

    {{-- ── STATS ── --}}
    <div class="stats-grid">
      <div class="stat">
        <div class="stat-icon si-blue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Total Messages</div>
          <div class="stat-val sv-blue">{{ $cntTotal }}</div>
          <div class="stat-foot">All time received</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-green">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Read</div>
          <div class="stat-val sv-green">{{ $cntRead }}</div>
          <div class="stat-foot">Already reviewed</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-purple">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Today</div>
          <div class="stat-val sv-purple">{{ $cntToday }}</div>
          <div class="stat-foot">Received today</div>
        </div>
      </div>
    </div>

    {{-- ── FLASH MESSAGE ── --}}
    @if(session('success'))
    <div class="flash-ok">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
    @endif

    {{-- ── SECTION HEADER + TABS ── --}}
    <div class="sec-hdr">
      <div class="sec-ttl">All Messages</div>
      <div class="ftabs" id="ftabs">
        <button class="ftab on" data-filter="all">All <span class="cnt" id="cntVis">{{ $cntTotal }}</span></button>
        <button class="ftab" data-filter="new">Unread <span class="cnt">{{ $cntNew }}</span></button>
        <button class="ftab" data-filter="read">Read <span class="cnt">{{ $cntRead }}</span></button>
      </div>
    </div>

    {{-- ── FILTER BAR ── --}}
    <div class="filter-bar">
      <div class="filter-group">
        <span class="filter-lbl">Period</span>
        <select class="filter-sel" id="filterPeriod">
          <option value="all">All time</option>
          <option value="today">Today</option>
          <option value="yesterday">Yesterday</option>
          <option value="week">This week</option>
          <option value="month">This month</option>
          <option value="custom">Custom…</option>
        </select>
      </div>

      <div class="filter-group" id="customDateGroup" style="display:none;">
        <span class="filter-lbl">From</span>
        <input type="date" class="filter-date" id="dateFrom">
        <span class="filter-lbl">To</span>
        <input type="date" class="filter-date" id="dateTo">
      </div>

      <div class="filter-div"></div>

      <div class="filter-group">
        <span class="filter-lbl">Sort</span>
        <select class="filter-sel" id="filterSort">
          <option value="newest">Newest first</option>
          <option value="oldest">Oldest first</option>
          <option value="name_az">Name A → Z</option>
          <option value="name_za">Name Z → A</option>
        </select>
      </div>

      <div class="filter-div"></div>

      <div class="filter-group">
        <span class="filter-lbl">Subject</span>
        <select class="filter-sel" id="filterSubject">
          <option value="all">Any</option>
          <option value="has">Has subject</option>
          <option value="none">No subject</option>
        </select>
      </div>

      <button class="filter-reset" id="filterReset">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Reset
      </button>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="table-card">
      <div class="table-scroll">
        <table>
          <thead>
            <tr>
              <th>Sender</th>
              <th>Message</th>
              <th class="sortable" id="thDate">
                Date
                <span class="sort-arrows">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg>
                </span>
              </th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="tbody">
            @forelse($messages as $msg)
            @php
              $init    = strtoupper(substr($msg->name ?? 'U', 0, 1));
              $isRead  = isset($msg->read_at) && $msg->read_at;
              $status  = $isRead ? 'read' : 'new';
              $hasSubj = !empty($msg->subject) ? 'has' : 'none';
              $srch    = strtolower(($msg->name ?? '').' '.($msg->email ?? '').' '.($msg->message ?? ''));
            @endphp
            <tr data-status="{{ $status }}"
                data-search="{{ $srch }}"
                data-subject="{{ $hasSubj }}"
                data-ts="{{ $msg->created_at->timestamp }}"
                data-name="{{ strtolower($msg->name ?? '') }}"
                data-datestr="{{ $msg->created_at->format('Y-m-d') }}">
              <td>
                <div class="sender-cell">
                  <div class="row-av">{{ $init }}</div>
                  <div>
                    <div class="sender-name">{{ $msg->name }}</div>
                    <div class="sender-email">{{ $msg->email }}</div>
                  </div>
                </div>
              </td>
              <td class="msg-cell">{{ \Illuminate\Support\Str::limit($msg->message, 80) }}</td>
              <td class="date-cell">
                {{ $msg->created_at->format('d M Y') }}
                <div class="date-ago">{{ $msg->created_at->diffForHumans() }}</div>
              </td>
              <td>
                <span class="badge b-{{ $status }}">
                  <span class="badge-dot"></span>{{ $isRead ? 'Read' : 'New' }}
                </span>
              </td>
              <td>
                <div class="actions">
                  <a href="{{ route('admin.messages.show', $msg->id) }}" class="act-btn ab-view">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View
                  </a>
                  <form action="{{ route('admin.messages.delete', $msg->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="act-btn ab-delete" onclick="return confirm('Delete this message?')">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                      Delete
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr class="empty-row">
              <td colspan="5">
                <div class="empty-wrap">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  <strong>No messages yet</strong>
                  <p>When users send messages they'll appear here.</p>
                </div>
              </td>
            </tr>
            @endforelse
            <tr id="noResultsRow" style="display:none;">
              <td colspan="5">
                <div class="empty-wrap">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                  <strong>No results found</strong>
                  <p>Try adjusting your filters or search query.</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="table-footer">
        <div class="tfoot-info">Showing <strong id="cntVisF">{{ $cntTotal }}</strong> of <strong>{{ $cntTotal }}</strong> messages</div>
        <div class="tfoot-total">{{ $cntTotal }} total</div>
      </div>
    </div>

    {{-- ── PAGINATION ── --}}
    @if($messages->lastPage() > 1)
    <div class="pagination-wrap">
      @if($messages->onFirstPage())
        <span class="pg-arrow disabled">‹</span>
      @else
        <a href="{{ $messages->previousPageUrl() }}" class="pg-arrow">‹</a>
      @endif
      <div class="pg-pages">
        @for($i = 1; $i <= $messages->lastPage(); $i++)
          @if($i === $messages->currentPage())
            <span class="pg-page active">{{ $i }}</span>
          @else
            <a href="{{ $messages->url($i) }}" class="pg-page">{{ $i }}</a>
          @endif
        @endfor
      </div>
      @if($messages->hasMorePages())
        <a href="{{ $messages->nextPageUrl() }}" class="pg-arrow">›</a>
      @else
        <span class="pg-arrow disabled">›</span>
      @endif
    </div>
    @endif

  </div>{{-- /body --}}
</div>{{-- /main --}}
</div>{{-- /shell --}}

<script>
(function(){
'use strict';

/* ── THEME ── */
var html = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved = localStorage.getItem('adminTheme') || 'light';
if(saved === 'dark'){ html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
  var t = this.checked ? 'dark' : 'light';
  html.setAttribute('data-theme', t);
  localStorage.setItem('adminTheme', t);
});

/* ── SIDEBAR TOGGLE ── */
var sidebar = document.getElementById('sidebar');
document.getElementById('hamburger').addEventListener('click', function(){
  sidebar.classList.toggle('open');
});
document.addEventListener('click', function(e){
  if(window.innerWidth <= 860 && !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target)){
    sidebar.classList.remove('open');
  }
});

/* ── SIDEBAR UNREAD CHIP ── */
(function(){
  var rows = document.querySelectorAll('#tbody tr[data-status="new"]');
  var chip = document.getElementById('sidebarUnread');
  if(chip && rows.length > 0){
    chip.textContent = rows.length;
    chip.style.display = '';
  }
})();

/* ── FILTER STATE ── */
var activeFilter = 'all';
var activeSort   = 'newest';
var activePeriod = 'all';
var activeSubj   = 'all';
var dateFrom = '', dateTo = '';
var rows  = Array.from(document.querySelectorAll('#tbody tr[data-status]'));
var tbody = document.getElementById('tbody');
var noRow = document.getElementById('noResultsRow');

/* ── DATE HELPERS ── */
function todayStr(){ return new Date().toISOString().slice(0,10); }
function yesterdayStr(){ var d=new Date(); d.setDate(d.getDate()-1); return d.toISOString().slice(0,10); }
function weekStartStr(){ var d=new Date(); d.setDate(d.getDate()-d.getDay()); return d.toISOString().slice(0,10); }
function monthStartStr(){ var d=new Date(); return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-01'; }
function getPeriodRange(p){
  var t = todayStr();
  if(p==='today')     return [t,t];
  if(p==='yesterday') return [yesterdayStr(),yesterdayStr()];
  if(p==='week')      return [weekStartStr(),t];
  if(p==='month')     return [monthStartStr(),t];
  if(p==='custom')    return [dateFrom,dateTo];
  return ['',''];
}

/* ── APPLY ALL FILTERS ── */
function applyFilters(){
  var q     = document.getElementById('searchInput').value.toLowerCase().trim();
  var range = getPeriodRange(activePeriod);
  var from  = range[0], to = range[1];
  var vis   = 0;

  rows.forEach(function(r){
    var mF    = activeFilter === 'all' || r.dataset.status === activeFilter;
    var mS    = !q || (r.dataset.search || '').includes(q);
    var mSubj = activeSubj === 'all' || r.dataset.subject === activeSubj;
    var mDate = true;
    if(from && to){
      var ds = r.dataset.datestr || '';
      mDate = ds >= from && ds <= to;
    } else if(from){
      mDate = (r.dataset.datestr || '') >= from;
    } else if(to){
      mDate = (r.dataset.datestr || '') <= to;
    }
    var show = mF && mS && mSubj && mDate;
    r.classList.toggle('row-hidden', !show);
    if(show) vis++;
  });

  sortRows();

  var e1 = document.getElementById('cntVis');
  var e2 = document.getElementById('cntVisF');
  if(e1) e1.textContent = vis;
  if(e2) e2.textContent = vis;
  if(noRow) noRow.style.display = (vis === 0 && rows.length > 0) ? '' : 'none';
}

/* ── SORT ── */
function sortRows(){
  var visible = rows.filter(function(r){ return !r.classList.contains('row-hidden'); });
  visible.sort(function(a,b){
    if(activeSort === 'newest')  return Number(b.dataset.ts) - Number(a.dataset.ts);
    if(activeSort === 'oldest')  return Number(a.dataset.ts) - Number(b.dataset.ts);
    if(activeSort === 'name_az') return (a.dataset.name||'').localeCompare(b.dataset.name||'');
    if(activeSort === 'name_za') return (b.dataset.name||'').localeCompare(a.dataset.name||'');
    return 0;
  });
  visible.forEach(function(r){ tbody.appendChild(r); });
  if(noRow) tbody.appendChild(noRow);
}

/* ── STATUS TABS ── */
document.querySelectorAll('.ftab').forEach(function(tab){
  tab.addEventListener('click', function(){
    document.querySelectorAll('.ftab').forEach(function(t){ t.classList.remove('on'); });
    this.classList.add('on');
    activeFilter = this.dataset.filter;
    applyFilters();
  });
});

/* ── SEARCH ── */
var st;
document.getElementById('searchInput').addEventListener('input', function(){
  clearTimeout(st);
  st = setTimeout(applyFilters, 180);
});

/* ── PERIOD ── */
document.getElementById('filterPeriod').addEventListener('change', function(){
  activePeriod = this.value;
  var g = document.getElementById('customDateGroup');
  if(this.value === 'custom'){
    g.style.display = 'flex';
  } else {
    g.style.display = 'none';
    dateFrom = ''; dateTo = '';
  }
  applyFilters();
});

/* ── CUSTOM DATES ── */
document.getElementById('dateFrom').addEventListener('change', function(){ dateFrom = this.value; applyFilters(); });
document.getElementById('dateTo').addEventListener('change',   function(){ dateTo   = this.value; applyFilters(); });

/* ── SORT SELECT ── */
document.getElementById('filterSort').addEventListener('change', function(){ activeSort = this.value; applyFilters(); });

/* ── SUBJECT SELECT ── */
document.getElementById('filterSubject').addEventListener('change', function(){ activeSubj = this.value; applyFilters(); });

/* ── RESET ── */
document.getElementById('filterReset').addEventListener('click', function(){
  activeFilter = 'all'; activePeriod = 'all'; activeSort = 'newest'; activeSubj = 'all';
  dateFrom = ''; dateTo = '';
  document.getElementById('filterPeriod').value  = 'all';
  document.getElementById('filterSort').value    = 'newest';
  document.getElementById('filterSubject').value = 'all';
  document.getElementById('searchInput').value   = '';
  document.getElementById('customDateGroup').style.display = 'none';
  document.getElementById('dateFrom').value = '';
  document.getElementById('dateTo').value   = '';
  document.querySelectorAll('.ftab').forEach(function(t){ t.classList.remove('on'); });
  document.querySelector('.ftab[data-filter="all"]').classList.add('on');
  applyFilters();
});

/* ── TOAST ── */
function toast(msg, type){
  var icons = {
    success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
  };
  var el = document.createElement('div');
  el.className = 'toast toast-ok';
  el.innerHTML = (icons[type]||icons.success) + '<span>' + msg + '</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
  document.getElementById('toastWrap').appendChild(el);
  setTimeout(function(){
    el.style.transition = 'opacity .3s,transform .3s';
    el.style.opacity = '0';
    el.style.transform = 'translateX(20px)';
    setTimeout(function(){ el.remove(); }, 300);
  }, 4200);
}

@if(session('success'))
  setTimeout(function(){ toast(@json(session('success')), 'success'); }, 200);
@endif

})();
</script>
</body>
</html>