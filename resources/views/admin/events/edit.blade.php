{{-- resources/views/admin/events/edit.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Edit Event — DonateBazaar Admin</title>
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
.sc-teal{background:var(--green-lt);color:#059669;}
.sc-blue{background:var(--blue-lt);color:var(--blue);}
.s-divider{height:1px;background:var(--sb-border);margin:10px 18px;}
.s-bottom{margin-top:auto;padding:10px 10px 20px;border-top:1px solid var(--sb-border);}

/* ── MAIN ── */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* ── TOPBAR ── */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:62px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:200;gap:14px;}
.tb-left{display:flex;align-items:center;gap:12px;}
.tb-left h1{font-family:var(--mono);font-size:17px;font-weight:700;color:var(--text);letter-spacing:-.02em;}
.tb-left p{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.tb-back{display:flex;align-items:center;gap:6px;padding:6px 12px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);transition:all var(--ease);cursor:pointer;text-decoration:none;}
.tb-back:hover{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.3);}
.tb-back svg{width:13px;height:13px;}
.tb-right{display:flex;align-items:center;gap:8px;}
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

/* ── FORM LAYOUT ── */
.form-grid{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
@media(max-width:900px){.form-grid{grid-template-columns:1fr;}}

/* ── CARDS ── */
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

/* ── FORM FIELDS ── */
.field{margin-bottom:18px;}
.field:last-child{margin-bottom:0;}
.field-label{font-size:11.5px;font-weight:600;color:var(--text2);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;margin-bottom:7px;display:flex;align-items:center;gap:6px;}
.field-label .req{color:var(--red);font-size:13px;}
.field-hint{font-size:11px;color:var(--text3);margin-top:5px;line-height:1.5;}
.inp{width:100%;height:42px;background:var(--surface2);border:1.5px solid var(--border2);border-radius:var(--r-sm);padding:0 14px;font-size:13.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.inp:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.inp::placeholder{color:var(--text3);}
.inp-error{border-color:var(--red) !important;box-shadow:0 0 0 3px rgba(240,68,68,.12) !important;}
.textarea{width:100%;min-height:110px;resize:vertical;background:var(--surface2);border:1.5px solid var(--border2);border-radius:var(--r-sm);padding:12px 14px;font-size:13.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);line-height:1.6;}
.textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.textarea::placeholder{color:var(--text3);}
.sel{width:100%;height:42px;background:var(--surface2);border:1.5px solid var(--border2);border-radius:var(--r-sm);padding:0 14px;font-size:13.5px;color:var(--text);font-family:var(--font);outline:none;cursor:pointer;transition:border-color var(--ease),box-shadow var(--ease);}
.sel:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}

/* ── CHAR COUNTER ── */
.char-wrap{position:relative;}
.char-count{position:absolute;bottom:10px;right:12px;font-size:10px;font-family:var(--mono);color:var(--text3);}

/* ── COVER UPLOAD ── */
.upload-zone{border:2px dashed var(--border2);border-radius:var(--r-sm);padding:28px 20px;text-align:center;cursor:pointer;transition:all .2s ease;position:relative;overflow:hidden;background:var(--surface2);}
.upload-zone:hover{border-color:var(--a);background:var(--a-lt);}
.upload-zone.has-preview{border-style:solid;border-color:var(--a);padding:0;}
.upload-zone input{position:absolute;inset:0;opacity:0;cursor:pointer;}
.upload-icon{width:44px;height:44px;border-radius:12px;background:var(--a-lt);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;}
.upload-icon svg{width:20px;height:20px;color:var(--a);}
.upload-text{font-size:13px;font-weight:600;color:var(--text2);}
.upload-sub{font-size:11px;color:var(--text3);margin-top:4px;}
.upload-preview{width:100%;height:160px;object-fit:cover;border-radius:calc(var(--r-sm) - 2px);display:none;}
.upload-preview.show{display:block;}
.upload-overlay{position:absolute;inset:0;background:rgba(0,0,0,.4);display:none;align-items:center;justify-content:center;border-radius:calc(var(--r-sm) - 2px);}
.upload-zone.has-preview:hover .upload-overlay{display:flex;}
.upload-overlay span{color:#fff;font-size:12px;font-weight:600;font-family:var(--mono);}

/* ── TOGGLE SWITCH ── */
.toggle-row{display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border);}
.toggle-row:last-child{border-bottom:none;}
.toggle-info .toggle-name{font-size:13px;font-weight:600;color:var(--text);}
.toggle-info .toggle-desc{font-size:11px;color:var(--text3);margin-top:2px;}
.toggle{position:relative;width:42px;height:24px;flex-shrink:0;}
.toggle input{position:absolute;opacity:0;width:0;height:0;}
.toggle-slider{position:absolute;inset:0;border-radius:100px;background:var(--surface3);border:1.5px solid var(--border2);cursor:pointer;transition:all .25s;}
.toggle-slider::after{content:'';position:absolute;width:16px;height:16px;border-radius:50%;background:#fff;left:3px;top:2px;transition:transform .25s;box-shadow:0 2px 4px rgba(0,0,0,.15);}
.toggle input:checked + .toggle-slider{background:var(--a);border-color:var(--a);}
.toggle input:checked + .toggle-slider::after{transform:translateX(18px);}

/* ── SIDEBAR SUMMARY CARD ── */
.summary-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;position:sticky;top:82px;animation:fadeUp .4s .1s ease both;}
.summary-card+.summary-card{margin-top:14px;}
.summary-header{padding:16px 18px;border-bottom:1px solid var(--border);background:var(--surface2);}
.summary-title{font-family:var(--mono);font-size:13px;font-weight:700;color:var(--text);}
.summary-body{padding:16px 18px;}
.summary-item{display:flex;flex-direction:column;gap:3px;margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid var(--border);}
.summary-item:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0;}
.summary-key{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);}
.summary-val{font-size:13px;font-weight:500;color:var(--text);font-family:var(--mono);}
.summary-val.empty{color:var(--text3);font-style:italic;font-weight:400;}
.summary-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:6px;font-size:10px;font-weight:700;font-family:var(--mono);}
.sb-draft{background:var(--amber-lt);color:#b45309;}
.sb-active{background:var(--green-lt);color:#059669;}
.sb-publish{background:var(--green-lt);color:#059669;}

/* ── STATUS SELECT ── */
.status-opts{display:flex;gap:8px;flex-wrap:wrap;}
.status-opt{flex:1;min-width:90px;border:2px solid var(--border2);border-radius:var(--r-sm);padding:10px 14px;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:8px;font-size:12.5px;font-weight:600;font-family:var(--mono);color:var(--text2);background:var(--surface2);}
.status-opt:hover{border-color:var(--a);background:var(--a-lt);color:var(--a);}
.status-opt.selected-status{border-color:var(--a);background:var(--a-lt);color:var(--a);}
.status-opt.sel-draft.selected-status{border-color:var(--amber);background:var(--amber-lt);color:#b45309;}
.status-opt.sel-active.selected-status{border-color:var(--green);background:var(--green-lt);color:#059669;}
.status-opt.sel-cancelled.selected-status{border-color:var(--red);background:var(--red-lt);color:var(--red);}
.status-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.dot-draft{background:var(--amber);}
.dot-active{background:var(--green);}
.dot-cancelled{background:var(--red);}

/* ── FLASH ── */
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);}
.flash-success{background:var(--green-lt);border:1px solid rgba(5,196,138,.25);color:#059669;}
.flash svg{width:14px;height:14px;flex-shrink:0;}

/* ── ACTION BUTTONS ── */
.btn{display:inline-flex;align-items:center;gap:8px;padding:11px 22px;border-radius:var(--r-sm);font-size:13px;font-weight:600;font-family:var(--font);cursor:pointer;border:none;transition:all var(--ease);}
.btn svg{width:14px;height:14px;}
.btn-publish{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 18px rgba(110,86,247,.4);}
.btn-publish:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(110,86,247,.5);}
.btn-draft{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.3);}
.btn-draft:hover{background:rgba(245,158,11,.2);transform:translateY(-1px);}
.btn-ghost{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-ghost:hover{background:var(--surface3);color:var(--text);}
.btn-danger{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.25);}
.btn-danger:hover{background:rgba(240,68,68,.18);}
.btn-sm{padding:8px 16px;font-size:12px;}
.action-bar{display:flex;gap:10px;align-items:center;flex-wrap:wrap;padding-top:20px;border-top:1px solid var(--border);margin-top:24px;}

/* ── DANGER ZONE ── */
.danger-zone{border:1.5px solid rgba(240,68,68,.25);border-radius:var(--r);overflow:hidden;margin-top:16px;animation:fadeUp .4s .2s ease both;}
.danger-zone-header{padding:14px 20px;background:var(--red-lt);border-bottom:1.5px solid rgba(240,68,68,.15);}
.danger-zone-title{font-family:var(--mono);font-size:12px;font-weight:700;color:var(--red);text-transform:uppercase;letter-spacing:.1em;}
.danger-zone-body{padding:18px 20px;background:var(--surface);}
.danger-zone-body p{font-size:12.5px;color:var(--text2);line-height:1.6;margin-bottom:14px;}

/* ── CURRENT IMAGE THUMB ── */
.current-img-wrap{margin-bottom:14px;}
.current-img{width:100%;height:150px;object-fit:cover;border-radius:var(--r-sm);border:1px solid var(--border2);}
.current-img-label{font-size:10.5px;color:var(--text3);font-family:var(--mono);margin-top:6px;}

/* ── ERROR ── */
.error-msg{font-size:11px;color:var(--red);margin-top:5px;font-family:var(--mono);display:none;}
.error-msg.show{display:block;}

/* ── UTILS ── */
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}}
@media(max-width:600px){.topbar{padding:0 16px}.body{padding:14px 14px 48px}.row-2,.row-3{grid-template-columns:1fr}}
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
      <a href="{{ route('admin.events.index') }}" class="tb-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Back
      </a>
      <div class="tb-left" style="margin-left:4px;">
        <h1>Edit Event</h1>
        <p>#{{ $event->id }} · {{ $event->created_at->format('d M Y') }}</p>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:8px;">
      <a href="{{ route('admin.events.show', $event) }}" class="tb-back" style="color:var(--a);border-color:rgba(110,86,247,.25);background:var(--a-lt);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        View Event
      </a>
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
    </div>
  </header>

  <div class="body">

    @if(session('success'))
    <div class="flash flash-success">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="flash flash-error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      Please fix the errors below before saving.
    </div>
    @endif

    <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" id="editForm">
      @csrf
      @method('PUT')

      <div class="form-grid">

        {{-- ── LEFT: FORM SECTIONS ── --}}
        <div>

          {{-- ── BASIC INFO ── --}}
          <div class="card">
            <div class="card-header">
              <div class="card-icon ci-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </div>
              <div>
                <div class="card-title">Event Information</div>
                <div class="card-subtitle">Core details about this event</div>
              </div>
            </div>
            <div class="card-body">

              <div class="field">
                <label class="field-label">Event Title <span class="req">*</span></label>
                <input type="text" name="title" class="inp {{ $errors->has('title') ? 'inp-error' : '' }}"
                  placeholder="e.g. Annual Fundraising Gala 2025"
                  value="{{ old('title', $event->title) }}" maxlength="255" id="titleInp">
                @error('title')<div class="error-msg show">{{ $message }}</div>@enderror
              </div>

              <div class="field">
                <label class="field-label">Description <span class="req">*</span></label>
                <div class="char-wrap">
                  <textarea name="description" class="textarea {{ $errors->has('description') ? 'inp-error' : '' }}"
                    placeholder="Describe what this event is about…"
                    maxlength="2000" id="descInp" rows="5">{{ old('description', $event->description) }}</textarea>
                  <span class="char-count" id="descCount">{{ strlen(old('description', $event->description ?? '')) }} / 2000</span>
                </div>
                @error('description')<div class="error-msg show">{{ $message }}</div>@enderror
              </div>

              <div class="row-2">
                <div class="field">
                  <label class="field-label">Category <span class="req">*</span></label>
                  <select name="category_id" class="sel {{ $errors->has('category_id') ? 'inp-error' : '' }}">
                    <option value="">Select category…</option>
                    @foreach($categories as $cat)
                      <option value="{{ $cat->id }}" {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->emoji ?? '' }} {{ $cat->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('category_id')<div class="error-msg show">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                  <label class="field-label">Campaign <span class="req">*</span></label>
                  <select name="campaign_id" class="sel {{ $errors->has('campaign_id') ? 'inp-error' : '' }}" id="campaignSel">
                    <option value="">Select campaign…</option>
                    @foreach($campaigns as $camp)
                      <option value="{{ $camp->id }}" {{ old('campaign_id', $event->campaign_id) == $camp->id ? 'selected' : '' }}>
                        {{ $camp->title }}
                      </option>
                    @endforeach
                  </select>
                  @error('campaign_id')<div class="error-msg show">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="row-2">
                <div class="field">
                  <label class="field-label">Event Date <span class="req">*</span></label>
                  <input type="date" name="event_date" class="inp {{ $errors->has('event_date') ? 'inp-error' : '' }}"
                    value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}">
                  @error('event_date')<div class="error-msg show">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                  <label class="field-label">Location / Venue</label>
                  <input type="text" name="location" class="inp"
                    placeholder="e.g. Mumbai Convention Centre"
                    value="{{ old('location', $event->location) }}" maxlength="255">
                </div>
              </div>

              <div class="row-3">
                <div class="field">
                  <label class="field-label">Start Time</label>
                  <input type="time" name="start_time" class="inp" value="{{ old('start_time', $event->start_time) }}">
                </div>
                <div class="field">
                  <label class="field-label">End Time</label>
                  <input type="time" name="end_time" class="inp" value="{{ old('end_time', $event->end_time) }}">
                </div>
                <div class="field">
                  <label class="field-label">Max Participants</label>
                  <input type="number" name="max_participants" class="inp"
                    placeholder="Unlimited" min="1"
                    value="{{ old('max_participants', $event->max_participants) }}">
                  <div class="field-hint">Leave blank for unlimited</div>
                </div>
              </div>

              <div class="field">
                <label class="field-label">Fundraising Goal (₹) <span class="req">*</span></label>
                <input type="number" name="goal_amount" class="inp {{ $errors->has('goal_amount') ? 'inp-error' : '' }}"
                  placeholder="e.g. 100000" min="1" step="0.01"
                  value="{{ old('goal_amount', $event->goal_amount) }}" id="goalInp">
                @error('goal_amount')<div class="error-msg show">{{ $message }}</div>@enderror
              </div>

            </div>
          </div>

          {{-- ── STATUS ── --}}
          <div class="card" style="animation-delay:.05s;">
            <div class="card-header">
              <div class="card-icon ci-purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <div>
                <div class="card-title">Event Status</div>
                <div class="card-subtitle">Control visibility and registration availability</div>
              </div>
            </div>
            <div class="card-body">
              <input type="hidden" name="status" id="statusHidden" value="{{ old('status', $event->status) }}">
              <div class="status-opts">
                <div class="status-opt sel-draft {{ old('status', $event->status) === 'draft' ? 'selected-status' : '' }}" onclick="setStatus('draft')">
                  <span class="status-dot dot-draft"></span> Draft
                </div>
                <div class="status-opt sel-active {{ old('status', $event->status) === 'active' ? 'selected-status' : '' }}" onclick="setStatus('active')">
                  <span class="status-dot dot-active"></span> Active
                </div>
                <div class="status-opt sel-cancelled {{ old('status', $event->status) === 'cancelled' ? 'selected-status' : '' }}" onclick="setStatus('cancelled')">
                  <span class="status-dot dot-cancelled"></span> Cancelled
                </div>
              </div>
            </div>
          </div>

          {{-- ── COVER IMAGE ── --}}
          <div class="card" style="animation-delay:.1s;">
            <div class="card-header">
              <div class="card-icon ci-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
              </div>
              <div>
                <div class="card-title">Cover Image</div>
                <div class="card-subtitle">Recommended: 1200×600px, max 2MB</div>
              </div>
            </div>
            <div class="card-body">
              @if($event->cover_image)
              <div class="current-img-wrap">
                <img src="{{ asset('storage/'.$event->cover_image) }}" class="current-img" alt="Current cover">
                <div class="current-img-label">Current cover image — upload a new one to replace</div>
              </div>
              @endif
              <div class="upload-zone {{ $event->cover_image ? '' : '' }}" id="uploadZone">
                <input type="file" name="cover_image" id="coverInput" accept="image/*" onchange="previewImage(this)">
                <img src="" alt="Preview" class="upload-preview" id="uploadPreview">
                <div class="upload-overlay"><span>Click to change</span></div>
                <div id="uploadPlaceholder">
                  <div class="upload-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  </div>
                  <div class="upload-text">{{ $event->cover_image ? 'Click to replace cover image' : 'Click to upload cover image' }}</div>
                  <div class="upload-sub">PNG, JPG, WEBP — max 2MB</div>
                </div>
              </div>
            </div>
          </div>

          {{-- ── SETTINGS ── --}}
          <div class="card" style="animation-delay:.15s;">
            <div class="card-header">
              <div class="card-icon ci-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
              </div>
              <div>
                <div class="card-title">Event Settings</div>
                <div class="card-subtitle">Control registration and visibility options</div>
              </div>
            </div>
            <div class="card-body">
              <div class="toggle-row">
                <div class="toggle-info">
                  <div class="toggle-name">Allow Registrations</div>
                  <div class="toggle-desc">Participants can register for this event</div>
                </div>
                <label class="toggle">
                  <input type="checkbox" name="allow_registrations" value="1" {{ $event->allow_registrations ? 'checked' : '' }}>
                  <span class="toggle-slider"></span>
                </label>
              </div>
              <div class="toggle-row">
                <div class="toggle-info">
                  <div class="toggle-name">Show on Campaign Page</div>
                  <div class="toggle-desc">Display this event on the linked campaign page</div>
                </div>
                <label class="toggle">
                  <input type="checkbox" name="show_on_campaign" value="1" {{ $event->show_on_campaign ? 'checked' : '' }}>
                  <span class="toggle-slider"></span>
                </label>
              </div>
            </div>
          </div>

          {{-- ── ACTION BAR ── --}}
          <div class="action-bar" style="animation:fadeUp .4s .2s ease both;">
            <a href="{{ route('admin.events.index') }}" class="btn btn-ghost">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
              Cancel
            </a>
            <button type="submit" class="btn btn-publish" style="margin-left:auto;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
              Save Changes
            </button>
          </div>

          {{-- ── DANGER ZONE ── --}}
          <!-- <div class="danger-zone">
            <div class="danger-zone-header">
              <div class="danger-zone-title">⚠ Danger Zone</div>
            </div>
            <div class="danger-zone-body">
              <p>Deleting this event is permanent and cannot be undone. All registrations and associated data will be lost.</p>
              <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Permanently delete this event? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  Delete Event
                </button>
              </form>
            </div>
          </div> -->

        </div>

        {{-- ── RIGHT: LIVE SUMMARY ── --}}
        <div>
          <div class="summary-card">
            <div class="summary-header">
              <div class="summary-title"> Event Summary</div>
            </div>
            <div class="summary-body">
              <div class="summary-item">
                <div class="summary-key">Status</div>
                <span class="summary-badge {{ $event->status === 'active' ? 'sb-active' : ($event->status === 'cancelled' ? '' : 'sb-draft') }}"
                  id="summaryStatusBadge"
                  style="{{ $event->status === 'cancelled' ? 'background:var(--red-lt);color:var(--red);' : '' }}">
                  {{ ucfirst($event->status) }}
                </span>
              </div>
              <div class="summary-item">
                <div class="summary-key">Event ID</div>
                <div class="summary-val">#{{ $event->id }}</div>
              </div>
              <div class="summary-item">
                <div class="summary-key">Category</div>
                <div class="summary-val">{{ $event->campaign?->category ? (($event->campaign->category->emoji ?? '').' '.$event->campaign->category->name) : '—' }}</div>
              </div>
              <div class="summary-item">
                <div class="summary-key">Campaign</div>
                <div class="summary-val" id="sum-campaign">{{ $event->campaign->title ?? '—' }}</div>
              </div>
              <div class="summary-item">
                <div class="summary-key">Title</div>
                <div class="summary-val" id="sum-title">{{ $event->title }}</div>
              </div>
              <div class="summary-item">
                <div class="summary-key">Date</div>
                <div class="summary-val">{{ $event->event_date?->format('d M Y') ?? '—' }}</div>
              </div>
              <div class="summary-item">
                <div class="summary-key">Goal</div>
                <div class="summary-val">₹{{ number_format($event->goal_amount, 0) }}</div>
              </div>
              <div class="summary-item">
                <div class="summary-key">Max Participants</div>
                <div class="summary-val">{{ $event->max_participants ? $event->max_participants.' max' : 'Unlimited' }}</div>
              </div>
              <div class="summary-item">
                <div class="summary-key">Created</div>
                <div class="summary-val" style="font-size:11px;">{{ $event->created_at->format('d M Y, H:i') }}</div>
              </div>
            </div>
            <div style="padding:0 18px 16px;">
              <a href="{{ route('admin.events.show', $event) }}" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View Full Details
              </a>
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

/* ── Avatar dropdown ── */
window.toggleDD = function(){ document.getElementById('avDD').classList.toggle('open'); };
document.addEventListener('click', function(e){
  var w = document.getElementById('avWrap');
  if (w && !w.contains(e.target)) document.getElementById('avDD').classList.remove('open');
});

/* ── Status selector ── */
window.setStatus = function(val) {
  document.getElementById('statusHidden').value = val;
  document.querySelectorAll('.status-opt').forEach(function(o){ o.classList.remove('selected-status'); });
  document.querySelector('.sel-' + val).classList.add('selected-status');
  var badge = document.getElementById('summaryStatusBadge');
  var map = { draft:'sb-draft', active:'sb-active', cancelled:'' };
  badge.className = 'summary-badge ' + (map[val] || '');
  if (val === 'cancelled') badge.style.cssText = 'background:var(--red-lt);color:var(--red);';
  else badge.style.cssText = '';
  badge.textContent = val.charAt(0).toUpperCase() + val.slice(1);
};

/* ── Description char counter ── */
var descEl = document.getElementById('descInp');
var descCount = document.getElementById('descCount');
if (descEl) {
  descEl.addEventListener('input', function(){
    descCount.textContent = this.value.length + ' / 2000';
  });
}

/* ── Cover image preview ── */
window.previewImage = function(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var preview = document.getElementById('uploadPreview');
    var zone = document.getElementById('uploadZone');
    var placeholder = document.getElementById('uploadPlaceholder');
    preview.src = e.target.result;
    preview.classList.add('show');
    placeholder.style.display = 'none';
    zone.classList.add('has-preview');
  };
  reader.readAsDataURL(input.files[0]);
};

})();
</script>
</body>
</html>