<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Edit Category — DonateBazaar Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ═══ TOKENS — identical to index ═══ */
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

/* ═══ SIDEBAR — exact match to index ═══ */
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
.back-btn{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 16px;background:var(--surface2);color:var(--text2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;font-weight:600;cursor:pointer;transition:all var(--ease);font-family:var(--font);}
.back-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.back-btn svg{width:13px;height:13px;}
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

/* ═══ BODY ═══ */
.body{padding:26px 28px 56px;flex:1;}

/* ═══ BREADCRUMB ═══ */
.breadcrumb{display:flex;align-items:center;gap:7px;font-size:11.5px;color:var(--text3);font-family:var(--mono);margin-bottom:22px;animation:fadeUp .3s ease both;}
.breadcrumb a{color:var(--text3);transition:color var(--ease);}
.breadcrumb a:hover{color:var(--a);}
.breadcrumb svg{width:10px;height:10px;flex-shrink:0;}
.breadcrumb span{color:var(--text2);}

/* ═══ ALERTS ═══ */
.alert-ok{background:rgba(5,196,138,.08);border:1px solid rgba(5,196,138,.22);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease;}
[data-theme="dark"] .alert-ok{color:#6ee7b7;}
.alert-ok svg{width:15px;height:15px;flex-shrink:0;}
.alert-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.22);color:#b91c1c;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;animation:fadeUp .3s ease;}
.alert-error svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}
[data-theme="dark"] .alert-error{color:#f87171;}

/* ═══ LAYOUT ═══ */
.page-grid{display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;}
@media(max-width:960px){.page-grid{grid-template-columns:1fr;}}

/* ═══ CARD ═══ */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;margin-bottom:16px;}
.card:nth-child(1){animation-delay:.05s;} .card:nth-child(2){animation-delay:.10s;} .card:nth-child(3){animation-delay:.15s;}
.card-head{display:flex;align-items:center;gap:10px;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:11.5px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-body{padding:22px;}

/* Modified badge */
.mod-badge{display:none;margin-left:auto;font-size:10px;font-weight:700;padding:2px 8px;border-radius:100px;background:var(--amber-lt);color:var(--amber);border:1px solid rgba(245,158,11,.2);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;align-items:center;gap:4px;}
.mod-badge svg{width:9px;height:9px;}
.mod-badge.show{display:inline-flex;}

/* ═══ FIELDS ═══ */
.field{margin-bottom:20px;}
.field:last-child{margin-bottom:0;}
.f-label{display:block;font-size:11.5px;font-weight:600;color:var(--text2);margin-bottom:7px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.f-label .req{color:var(--red);margin-left:2px;}
.f-input{width:100%;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color .2s,box-shadow .2s,background .2s;}
.f-input::placeholder{color:var(--text3);}
.f-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
.f-input.err{border-color:var(--red);}
.f-hint{font-size:11px;color:var(--text3);margin-top:5px;line-height:1.5;}
.f-error{font-size:11.5px;color:var(--red);margin-top:5px;font-family:var(--mono);}

/* ═══ TOGGLE ═══ */
.toggle-row{display:flex;align-items:center;justify-content:space-between;padding:4px 0;}
.toggle-lbl{font-size:13px;font-weight:600;color:var(--text);}
.toggle-sub{font-size:11.5px;color:var(--text3);margin-top:2px;}
.sw{position:relative;flex-shrink:0;}
.sw input{position:absolute;opacity:0;width:0;height:0;}
.sw label{display:block;width:46px;height:26px;border-radius:100px;background:var(--border2);cursor:pointer;position:relative;transition:background .2s;}
.sw label::after{content:'';position:absolute;width:20px;height:20px;border-radius:50%;background:#fff;top:3px;left:3px;transition:transform .25s cubic-bezier(.4,0,.2,1);box-shadow:0 1px 4px rgba(0,0,0,.2);}
.sw input:checked+label{background:var(--a);}
.sw input:checked+label::after{transform:translateX(20px);}

/* ═══ ICON GRID ═══ */
.icon-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:8px;}
.icon-tile{aspect-ratio:1;border-radius:var(--r-xs);border:1.5px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:16px;color:var(--text2);transition:all .15s;position:relative;}
.icon-tile:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);transform:translateY(-2px);}
.icon-tile.selected{border-color:var(--a);background:var(--a);color:#fff;box-shadow:0 4px 12px rgba(110,86,247,.35);}
.icon-tile.selected::after{content:'';position:absolute;top:3px;right:3px;width:7px;height:7px;border-radius:50%;background:rgba(255,255,255,.7);}
.icon-tile i{pointer-events:none;}
.icon-sel-row{margin-top:12px;padding:10px 13px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);display:flex;align-items:center;gap:10px;}
.icon-sel-prev{width:32px;height:32px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;font-size:14px;}

/* ═══ COLOR GRID ═══ */
.color-grid{display:grid;grid-template-columns:repeat(8,1fr);gap:8px;}
.c-swatch{aspect-ratio:1;border-radius:var(--r-xs);cursor:pointer;border:2.5px solid transparent;transition:all .15s;position:relative;display:flex;align-items:center;justify-content:center;}
.c-swatch:hover{transform:scale(1.12);box-shadow:var(--sh-md);}
.c-swatch.selected{border-color:var(--text);box-shadow:0 0 0 3px rgba(0,0,0,.08);}
.c-swatch.selected::after{content:'';width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.9);}
.custom-color-row{display:flex;align-items:center;gap:8px;margin-top:14px;}
.color-picker-input{width:38px;height:38px;border:1px solid var(--border2);border-radius:var(--r-xs);cursor:pointer;padding:3px;background:var(--surface2);}

/* ═══ SUBMIT ═══ */
.submit-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:13px 20px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:14px;font-weight:700;cursor:pointer;font-family:var(--mono);letter-spacing:-.01em;transition:opacity .2s,transform .15s;box-shadow:0 4px 18px rgba(110,86,247,.35);animation:fadeUp .4s .2s ease both;}
.submit-btn:hover{opacity:.88;transform:translateY(-1px);}
.submit-btn:active{transform:scale(.98);}
.submit-btn:disabled{opacity:.6;cursor:not-allowed;transform:none;}
.submit-btn svg{width:15px;height:15px;}

/* ═══ DANGER ZONE ═══ */
.danger-card{background:rgba(240,68,68,.03);border:1px solid rgba(240,68,68,.14);border-radius:var(--r);overflow:hidden;animation:fadeUp .4s .18s ease both;}
.danger-head{padding:13px 18px;background:rgba(240,68,68,.06);border-bottom:1px solid rgba(240,68,68,.12);display:flex;align-items:center;gap:8px;font-size:11px;font-weight:700;color:var(--red);text-transform:uppercase;letter-spacing:.1em;font-family:var(--mono);}
.danger-head svg{width:13px;height:13px;}
.danger-body{padding:16px 18px;}
.danger-desc{font-size:12px;color:var(--text3);margin-bottom:12px;line-height:1.6;}
.delete-btn{display:inline-flex;align-items:center;gap:7px;width:100%;justify-content:center;padding:10px 16px;background:transparent;color:var(--red);border:1px solid rgba(240,68,68,.3);border-radius:var(--r-sm);font-size:13px;font-weight:600;cursor:pointer;font-family:var(--font);transition:all .2s;}
.delete-btn:hover{background:var(--red);color:#fff;border-color:var(--red);}
.delete-btn svg{width:13px;height:13px;}

/* ═══ PREVIEW ═══ */
.preview-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;position:sticky;top:82px;animation:fadeUp .4s .15s ease both;margin-bottom:16px;}
.preview-live{padding:32px 24px;display:flex;flex-direction:column;align-items:center;text-align:center;background:var(--surface2);border-bottom:1px solid var(--border);min-height:190px;}
.prev-icon-box{width:72px;height:72px;border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff;margin-bottom:14px;transition:all .3s cubic-bezier(.4,0,.2,1);box-shadow:0 8px 28px rgba(0,0,0,.18);}
.prev-name{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text);letter-spacing:-.01em;margin-bottom:6px;transition:all .2s;}
.prev-name.empty{color:var(--text3);font-weight:400;font-style:italic;}
.prev-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:100px;font-size:10.5px;font-weight:700;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.pb-active{background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.22);}
.pb-inactive{background:rgba(100,116,139,.08);color:var(--text3);border:1px solid var(--border2);}
.preview-meta{padding:16px 20px;}
.prev-row{display:flex;align-items:center;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--border);font-size:12.5px;}
.prev-row:last-child{border-bottom:none;}
.prev-row-lbl{color:var(--text3);font-family:var(--mono);font-size:10.5px;text-transform:uppercase;letter-spacing:.07em;}
.prev-row-val{color:var(--text2);font-weight:500;display:flex;align-items:center;gap:6px;}
.prev-color-dot{width:14px;height:14px;border-radius:4px;flex-shrink:0;}
.pub-card-wrap{padding:16px 20px;border-top:1px solid var(--border);}
.pub-card-lbl{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;font-family:var(--mono);margin-bottom:12px;}
.pub-card-inner{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:14px;display:flex;align-items:center;gap:12px;transition:box-shadow var(--ease);}
.pub-card-inner:hover{box-shadow:var(--sh-md);}
.pub-icon{width:44px;height:44px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:18px;color:#fff;flex-shrink:0;}

/* ═══ MODAL ═══ */
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

::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}}
@media(max-width:600px){.topbar{padding:0 16px}.body{padding:14px 14px 48px}}
</style>
</head>
<body>

{{-- Delete modal --}}
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeModal()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Category?</h3>
    <p>This will permanently remove <strong>{{ $category->name }}</strong>. Campaigns using this category may be affected.</p>
    <div class="modal-acts">
      <button class="modal-cancel" onclick="closeModal()">Cancel</button>
      <button class="modal-del" onclick="document.getElementById('deleteForm').submit()">Yes, Delete</button>
    </div>
  </div>
</div>
<form id="deleteForm" method="POST" action="{{ route('admin.categories.destroy',$category->id) }}" style="display:none;">@csrf @method('DELETE')</form>

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
    <a href="{{ url('/admin/categories') }}" class="s-link active">
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
        <h1>Edit Category</h1>
        <p>Updating: <strong style="color:var(--text2);">{{ $category->name }}</strong></p>
      </div>
    </div>
    <div class="tb-right">
      <a href="{{ route('admin.categories.index') }}" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        All Categories
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
    <div class="breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <a href="{{ route('admin.categories.index') }}">Categories</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <span>Edit</span>
    </div>

    @if(session('success'))
    <div class="alert-ok" id="flashAlert">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert-error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <div>
        <strong>Please fix the following:</strong>
        <ul style="margin-top:4px;padding-left:16px;">
          @foreach($errors->all() as $e)<li style="font-size:12px;margin-top:2px;">{{ $e }}</li>@endforeach
        </ul>
      </div>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.update',$category->id) }}" id="catForm">
    @csrf @method('PUT')

    @php
      $curIcon=old('icon',$category->icon??'fa-heart');
      $curColor=old('color',$category->color??'#6e56f7');
      $icons=['fa-heart'=>'Heart','fa-book'=>'Book','fa-paw'=>'Paw','fa-user'=>'Person','fa-hand-holding-heart'=>'Giving','fa-stethoscope'=>'Medical','fa-graduation-cap'=>'Education','fa-globe'=>'Global','fa-child'=>'Child','fa-hands-helping'=>'Helping','fa-tree'=>'Nature','fa-home'=>'Housing','fa-water'=>'Water','fa-fire'=>'Emergency','fa-church'=>'Religion','fa-bread-slice'=>'Food','fa-wheelchair'=>'Disability','fa-music'=>'Arts'];
      $colors=['#6e56f7'=>'Purple','#9b6dff'=>'Violet','#ec4899'=>'Pink','#f04444'=>'Red','#f59e0b'=>'Amber','#05c48a'=>'Emerald','#3b82f6'=>'Blue','#06b6d4'=>'Cyan','#84cc16'=>'Lime','#f97316'=>'Orange','#64748b'=>'Slate','#0f172a'=>'Dark'];
    @endphp

    <div class="page-grid">
      {{-- LEFT --}}
      <div>
        {{-- Basic Info --}}
        <div class="card">
          <div class="card-head">
            <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <span class="card-head-title">Basic Information</span>
            <span class="mod-badge" id="modBadge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Modified</span>
          </div>
          <div class="card-body">
            <div class="field">
              <label class="f-label" for="name">Category Name <span class="req">*</span></label>
              <input id="name" name="name" type="text" value="{{ old('name',$category->name) }}"
                class="f-input {{ $errors->has('name')?'err':'' }}"
                placeholder="e.g. Medical, Education, Animal Welfare…"
                oninput="updatePreviewName(this.value);markChanged();" required>
              @error('name')<p class="f-error">{{ $message }}</p>@enderror
              <p class="f-hint">Displayed on public campaign listing pages</p>
            </div>
            <div class="field">
              <div class="toggle-row">
                <div>
                  <div class="toggle-lbl">Active</div>
                  <div class="toggle-sub">Visible on the public site for campaign creation</div>
                </div>
                <div class="sw">
                  <input type="checkbox" name="is_active" id="isActive" value="1"
                    {{ old('is_active',$category->is_active)?'checked':'' }}
                    onchange="updatePreviewStatus(this.checked);markChanged();">
                  <label for="isActive"></label>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Icon Picker --}}
        <div class="card">
          <div class="card-head">
            <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
            <span class="card-head-title">Icon</span>
          </div>
          <div class="card-body">
            <input type="hidden" name="icon" id="iconInput" value="{{ $curIcon }}">
            <p class="f-hint" style="margin-bottom:12px;">Select an icon that best represents this category</p>
            <div class="icon-grid">
              @foreach($icons as $icon=>$label)
              <div class="icon-tile {{ $curIcon===$icon?'selected':'' }}" onclick="selectIcon(this,'{{ $icon }}')" title="{{ $label }}">
                <i class="fa {{ $icon }}"></i>
              </div>
              @endforeach
            </div>
            <div class="icon-sel-row">
              <div class="icon-sel-prev" id="iconPreview"><i class="fa {{ $curIcon }}"></i></div>
              <div>
                <div style="font-size:12px;font-weight:600;color:var(--text);">Selected icon</div>
                <div id="iconName" style="font-size:11px;color:var(--text3);font-family:var(--mono);">{{ $curIcon }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Color Picker --}}
        <div class="card">
          <div class="card-head">
            <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg></div>
            <span class="card-head-title">Color</span>
          </div>
          <div class="card-body">
            <input type="hidden" name="color" id="colorInput" value="{{ $curColor }}">
            <p class="f-hint" style="margin-bottom:14px;">Used for the category icon background</p>
            <div class="color-grid">
              @foreach($colors as $hex=>$label)
              <div class="c-swatch {{ $curColor===$hex?'selected':'' }}" style="background:{{ $hex }};" onclick="selectColor(this,'{{ $hex }}')" title="{{ $label }}"></div>
              @endforeach
            </div>
            <div class="custom-color-row" style="margin-top:16px;">
              <label style="font-size:11.5px;font-weight:600;color:var(--text2);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;">Custom hex</label>
              <input type="color" id="colorPicker" value="{{ $curColor }}" class="color-picker-input" oninput="selectCustomColor(this.value)">
              <input type="text" id="hexInput" class="f-input" value="{{ $curColor }}" style="font-family:var(--mono);font-size:12px;max-width:110px;" placeholder="#6e56f7" oninput="syncHexInput(this.value)">
            </div>
          </div>
        </div>

        <button type="submit" class="submit-btn" id="submitBtn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Save Changes
        </button>
      </div>

      {{-- RIGHT --}}
      <div>
        {{-- Live Preview --}}
        <div class="preview-card">
          <div class="card-head">
            <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></div>
            <span class="card-head-title">Live Preview</span>
          </div>
          <div class="preview-live">
            <div class="prev-icon-box" id="previewBox" style="background:{{ $curColor }};"><i class="fa {{ $curIcon }}" id="previewIcon"></i></div>
            <div class="prev-name {{ $category->name?'':'empty' }}" id="previewName">{{ $category->name?:'Category name…' }}</div>
            <div style="margin-top:8px;">
              <span class="prev-badge {{ $category->is_active?'pb-active':'pb-inactive' }}" id="previewBadge">
                <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                {{ $category->is_active?'Active':'Inactive' }}
              </span>
            </div>
          </div>
          <div class="preview-meta">
            <div class="prev-row">
              <span class="prev-row-lbl">Icon</span>
              <span class="prev-row-val" id="prevMetaIcon"><i class="fa {{ $curIcon }}" style="color:var(--a);"></i> {{ $curIcon }}</span>
            </div>
            <div class="prev-row">
              <span class="prev-row-lbl">Color</span>
              <span class="prev-row-val"><span class="prev-color-dot" id="prevColorDot" style="background:{{ $curColor }};"></span><span id="prevColorHex" style="font-family:var(--mono);font-size:11.5px;">{{ $curColor }}</span></span>
            </div>
            <div class="prev-row">
              <span class="prev-row-lbl">Status</span>
              <span class="prev-row-val" id="prevMetaStatus" style="color:{{ $category->is_active?'var(--green)':'var(--text3)' }};">{{ $category->is_active?'Active':'Inactive' }}</span>
            </div>
            <div class="prev-row">
              <span class="prev-row-lbl">Slug</span>
              <span class="prev-row-val" style="font-family:var(--mono);font-size:11.5px;color:var(--text3);">{{ $category->slug }}</span>
            </div>
          </div>
          <div class="pub-card-wrap">
            <div class="pub-card-lbl">Public site card</div>
            <div class="pub-card-inner">
              <div class="pub-icon" id="pubIconBox" style="background:{{ $curColor }};"><i class="fa {{ $curIcon }}" id="pubIcon"></i></div>
              <div>
                <div style="font-size:13px;font-weight:600;color:var(--text);" id="pubName">{{ $category->name }}</div>
                <div style="font-size:11.5px;color:var(--text3);margin-top:2px;">{{ $category->campaigns_count??0 }} campaigns</div>
              </div>
              <svg style="margin-left:auto;width:14px;height:14px;color:var(--text3);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </div>
          </div>
        </div>

        {{-- Danger Zone --}}
        <div class="danger-card">
          <div class="danger-head">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Danger Zone
          </div>
          <div class="danger-body">
            <p class="danger-desc">Deleting this category is permanent and cannot be undone. Campaigns assigned to it may lose their category reference.</p>
            <button type="button" class="delete-btn" onclick="document.getElementById('deleteOverlay').classList.add('open')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              Delete "{{ $category->name }}"
            </button>
          </div>
        </div>
      </div>
    </div>
    </form>
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

/* Flash dismiss */
(function(){var a=document.getElementById('flashAlert');if(!a)return;setTimeout(function(){a.style.transition='opacity .4s,transform .4s';a.style.opacity='0';a.style.transform='translateY(-6px)';setTimeout(function(){a.remove();},400);},4000);})();

/* Preview state */
var state={
  icon:'{{ old("icon",$category->icon??'fa-heart') }}',
  color:'{{ old("color",$category->color??'#6e56f7') }}',
  name:'{{ old("name",$category->name) }}',
  active:{{ old('is_active',$category->is_active)?'true':'false' }}
};

function updatePreview(){
  var c=state.color,ic=state.icon,nm=state.name||'';
  document.getElementById('previewBox').style.background=c;
  document.getElementById('previewIcon').className='fa '+ic;
  var el=document.getElementById('previewName');
  if(nm){el.textContent=nm;el.classList.remove('empty');}else{el.textContent='Category name…';el.classList.add('empty');}
  var badge=document.getElementById('previewBadge');
  if(state.active){badge.className='prev-badge pb-active';badge.innerHTML='<span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Active';document.getElementById('prevMetaStatus').style.color='var(--green)';document.getElementById('prevMetaStatus').textContent='Active';}
  else{badge.className='prev-badge pb-inactive';badge.innerHTML='<span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Inactive';document.getElementById('prevMetaStatus').style.color='var(--text3)';document.getElementById('prevMetaStatus').textContent='Inactive';}
  document.getElementById('prevMetaIcon').innerHTML='<i class="fa '+ic+'" style="color:var(--a);"></i> '+ic;
  document.getElementById('prevColorDot').style.background=c;
  document.getElementById('prevColorHex').textContent=c;
  document.getElementById('pubIconBox').style.background=c;
  document.getElementById('pubIcon').className='fa '+ic;
  document.getElementById('pubName').textContent=nm||'Category name';
}

window.markChanged=function(){document.getElementById('modBadge').classList.add('show');};
window.updatePreviewName=function(v){state.name=v.trim();updatePreview();};
window.updatePreviewStatus=function(v){state.active=v;updatePreview();};
window.selectIcon=function(el,icon){state.icon=icon;document.getElementById('iconInput').value=icon;document.querySelectorAll('.icon-tile').forEach(function(t){t.classList.remove('selected');});el.classList.add('selected');document.getElementById('iconPreview').innerHTML='<i class="fa '+icon+'"></i>';document.getElementById('iconName').innerText=icon;markChanged();updatePreview();};
window.selectColor=function(el,hex){state.color=hex;document.getElementById('colorInput').value=hex;document.getElementById('colorPicker').value=hex;document.getElementById('hexInput').value=hex;document.querySelectorAll('.c-swatch').forEach(function(s){s.classList.remove('selected');});el.classList.add('selected');markChanged();updatePreview();};
window.selectCustomColor=function(hex){state.color=hex;document.getElementById('colorInput').value=hex;document.getElementById('hexInput').value=hex;document.querySelectorAll('.c-swatch').forEach(function(s){s.classList.remove('selected');});markChanged();updatePreview();};
window.syncHexInput=function(val){if(/^#[0-9a-fA-F]{6}$/.test(val)){state.color=val;document.getElementById('colorInput').value=val;document.getElementById('colorPicker').value=val;document.querySelectorAll('.c-swatch').forEach(function(s){s.classList.remove('selected');});markChanged();updatePreview();}};

/* Submit */
document.getElementById('catForm').addEventListener('submit',function(){var b=document.getElementById('submitBtn');b.disabled=true;b.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Saving…';});

/* Modal */
window.closeModal=function(){document.getElementById('deleteOverlay').classList.remove('open');};
document.getElementById('deleteOverlay').addEventListener('click',function(e){if(e.target===this)closeModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeModal();});

updatePreview();
})();
</script>
</body>
</html>