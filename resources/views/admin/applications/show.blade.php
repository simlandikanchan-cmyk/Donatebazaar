<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Application Details — DonateBazaar Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
/* ─── TOKENS ─────────────────────────────────────────────────────────────── */
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

/* ─── RESET ──────────────────────────────────────────────────────────────── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;}
body{font-family:var(--font);background:var(--bg);color:var(--text);line-height:1.55;-webkit-font-smoothing:antialiased;overflow-x:hidden;transition:background .2s,color .2s;}
a{text-decoration:none;color:inherit;}

/* ─── LAYOUT ─────────────────────────────────────────────────────────────── */
.shell{display:flex;min-height:100vh;}

/* ─── SIDEBAR ────────────────────────────────────────────────────────────── */
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

/* ─── MAIN ───────────────────────────────────────────────────────────────── */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* ─── TOPBAR ─────────────────────────────────────────────────────────────── */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:62px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:200;gap:14px;}
.tb-left{display:flex;align-items:center;gap:12px;}
.tb-breadcrumb{display:flex;align-items:center;gap:6px;font-family:var(--mono);font-size:13px;}
.tb-breadcrumb a{color:var(--text3);transition:color var(--ease);}
.tb-breadcrumb a:hover{color:var(--a);}
.tb-breadcrumb .sep{color:var(--text3);font-size:10px;}
.tb-breadcrumb .cur{color:var(--text);font-weight:700;}
.tb-sub{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.tb-right{display:flex;align-items:center;gap:8px;}

.tb-btn{width:36px;height:36px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text2);flex-shrink:0;transition:all var(--ease);text-decoration:none;}
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

/* ─── BODY ───────────────────────────────────────────────────────────────── */
.body{padding:26px 28px 56px;flex:1;}

/* ─── HERO CARD ──────────────────────────────────────────────────────────── */
.hero-card{
  background:var(--surface);border:1px solid var(--border);border-radius:var(--r);
  padding:28px 30px;box-shadow:var(--sh);margin-bottom:20px;
  display:flex;align-items:flex-start;justify-content:space-between;gap:20px;
  animation:fadeUp .35s ease both;position:relative;overflow:hidden;
}
.hero-card::before{
  content:'';position:absolute;top:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,var(--a),var(--a2));
  border-radius:var(--r) var(--r) 0 0;
}
.hero-left{display:flex;align-items:center;gap:18px;min-width:0;}
.hero-av{
  width:58px;height:58px;border-radius:16px;flex-shrink:0;
  background:linear-gradient(135deg,var(--a),var(--a2));
  display:flex;align-items:center;justify-content:center;
  font-family:var(--mono);font-size:22px;font-weight:800;color:#fff;
  box-shadow:0 4px 18px rgba(110,86,247,.35);
}
.hero-title{font-family:var(--mono);font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.03em;line-height:1.2;}
.hero-sub{font-size:12px;color:var(--text3);margin-top:5px;font-family:var(--mono);}
.hero-meta{display:flex;align-items:center;gap:14px;margin-top:10px;flex-wrap:wrap;}
.hero-meta-item{display:flex;align-items:center;gap:5px;font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.hero-meta-item svg{width:12px;height:12px;flex-shrink:0;}
.hero-right{display:flex;flex-direction:column;align-items:flex-end;gap:12px;flex-shrink:0;}

/* ─── BADGE ──────────────────────────────────────────────────────────────── */
.badge{display:inline-flex;align-items:center;gap:5px;font-size:10px;font-weight:700;padding:5px 12px;border-radius:7px;text-transform:uppercase;letter-spacing:.07em;font-family:var(--mono);}
.badge::before{content:'';width:6px;height:6px;border-radius:50%;background:currentColor;opacity:.7;}
.b-pending{background:rgba(245,158,11,.15);color:#b45309;border:1px solid rgba(245,158,11,.25);}
.b-review{background:rgba(59,130,246,.15);color:#1d4ed8;border:1px solid rgba(59,130,246,.25);}
.b-approved{background:rgba(5,196,138,.15);color:#047857;border:1px solid rgba(5,196,138,.25);}
.b-rejected{background:rgba(240,68,68,.15);color:#b91c1c;border:1px solid rgba(240,68,68,.25);}
[data-theme="dark"] .b-pending{color:#fbbf24;}
[data-theme="dark"] .b-review{color:#93c5fd;}
[data-theme="dark"] .b-approved{color:#6ee7b7;}
[data-theme="dark"] .b-rejected{color:#fca5a5;}

/* ─── SECTION HEADER ─────────────────────────────────────────────────────── */
.sec-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px;}
.sec-ttl{font-family:var(--mono);font-size:13px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.08em;}

/* ─── DETAILS GRID ───────────────────────────────────────────────────────── */
.details-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:24px;box-shadow:var(--sh);margin-bottom:20px;animation:fadeUp .4s .08s ease both;}
.details-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;}
.info-box{
  background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);
  padding:16px 18px;transition:border-color var(--ease),box-shadow var(--ease);
}
.info-box:hover{border-color:rgba(110,86,247,.25);box-shadow:0 0 0 3px var(--a-lt);}
.info-label{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;margin-bottom:7px;font-family:var(--mono);}
.info-value{font-size:14px;font-weight:600;color:var(--text);line-height:1.5;word-break:break-word;font-family:var(--mono);}
.info-value.empty{color:var(--text3);font-weight:400;}

/* ─── ACTIONS CARD ───────────────────────────────────────────────────────── */
.actions-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:22px 24px;box-shadow:var(--sh);display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;animation:fadeUp .4s .16s ease both;}
.actions-left{font-size:12.5px;color:var(--text3);font-family:var(--mono);}
.actions-left strong{display:block;font-size:13px;font-weight:700;color:var(--text2);margin-bottom:2px;}
.actions-right{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}

/* ─── BUTTONS ────────────────────────────────────────────────────────────── */
.c-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:10px 18px;border-radius:var(--r-xs);font-size:13px;font-weight:600;cursor:pointer;border:1px solid transparent;transition:all var(--ease);text-decoration:none;font-family:var(--font);white-space:nowrap;}
.c-btn svg{width:13px;height:13px;}
.c-btn:active{transform:scale(.96);}
.c-btn-back{background:var(--surface2);color:var(--text2);border-color:var(--border2);}
.c-btn-back:hover{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.25);transform:translateY(-1px);}
.c-btn-approve{background:var(--green-lt);color:var(--green);border-color:rgba(5,196,138,.2);}
.c-btn-approve:hover{background:var(--green);color:#fff;border-color:var(--green);transform:translateY(-1px);box-shadow:0 4px 14px rgba(5,196,138,.3);}
.c-btn-reject{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.2);}
.c-btn-reject:hover{background:var(--red);color:#fff;border-color:var(--red);transform:translateY(-1px);box-shadow:0 4px 14px rgba(240,68,68,.3);}

/* ─── ALERT ──────────────────────────────────────────────────────────────── */
.alert-success{background:#ecfdf5;border:1px solid rgba(5,196,138,.3);color:#047857;padding:14px 18px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
[data-theme="dark"] .alert-success{background:rgba(5,196,138,.1);border-color:rgba(5,196,138,.2);color:#6ee7b7;}
.alert-success svg{width:16px;height:16px;flex-shrink:0;color:var(--green);}

/* ─── REJECT MODAL ───────────────────────────────────────────────────────── */
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
.chip-red:hover,.chip-red.on{border-color:var(--red);background:var(--red-lt);color:#b91c1c;}
[data-theme="dark"] .chip-red:hover,[data-theme="dark"] .chip-red.on{color:#fca5a5;}
.modal-ta{width:100%;border:1px solid var(--border2);border-radius:12px;padding:12px 14px;font-size:13px;color:var(--text);resize:none;font-family:var(--font);background:var(--surface2);outline:none;transition:border-color var(--ease),box-shadow var(--ease);line-height:1.5;}
.modal-ta:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
.modal-err{font-size:11.5px;color:var(--red);margin-top:7px;display:none;font-family:var(--mono);font-weight:600;}
.modal-acts{display:flex;gap:9px;margin-top:18px;}
.modal-btn{flex:1;padding:12px;border-radius:11px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all var(--ease);font-family:var(--font);}
.modal-btn:hover{transform:translateY(-1px);}
.modal-cancel{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.modal-red{background:linear-gradient(135deg,var(--red),#dc2626);color:#fff;box-shadow:0 4px 16px rgba(240,68,68,.3);}

/* ─── TOAST ──────────────────────────────────────────────────────────────── */
.toast-wrap{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:270px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s ease both;}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-ok{background:linear-gradient(135deg,#059669,#10b981);}
.toast-err{background:linear-gradient(135deg,#dc2626,#f04444);}
.toast-x{margin-left:auto;width:18px;height:18px;border-radius:5px;background:rgba(255,255,255,.22);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}

/* ─── SCROLLBAR ──────────────────────────────────────────────────────────── */
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}

/* ─── ANIMATIONS ─────────────────────────────────────────────────────────── */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
@keyframes toastIn{from{opacity:0;transform:translateX(18px) scale(.96)}to{opacity:1;transform:none}}

/* ─── RESPONSIVE ─────────────────────────────────────────────────────────── */
@media(max-width:1100px){.details-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:860px){
  .sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex;}
}
@media(max-width:680px){
  .details-grid{grid-template-columns:1fr;}
  .hero-card{flex-direction:column;}
  .hero-right{flex-direction:row;align-items:center;}
  .actions-card{flex-direction:column;align-items:flex-start;}
}
@media(max-width:600px){
  .topbar{padding:0 16px;}
  .body{padding:14px 14px 48px;}
  .hero-left{flex-direction:column;align-items:flex-start;}
  .hero-av{width:48px;height:48px;font-size:18px;}
  .hero-title{font-size:18px;}
  .c-btn{width:100%;}
  .actions-right{width:100%;flex-direction:column;}
}
</style>
</head>
<body>

<div class="toast-wrap" id="toastWrap"></div>

<div class="shell">

{{-- ─── SIDEBAR ────────────────────────────────────────────────────────── --}}
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
    <a href="{{ route('admin.dashboard') }}" class="s-link">
      <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>
  </nav>

  <div class="s-section">Campaigns</div>
  <nav class="s-nav">
    <a href="{{ route('admin.dashboard') }}#cGrid" class="s-link">
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
    <a href="{{ url('/admin/applications') }}" class="s-link active">
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

{{-- ─── MAIN ───────────────────────────────────────────────────────────── --}}
<div class="main">

  {{-- TOPBAR --}}
  <header class="topbar">
    <div style="display:flex;align-items:center;gap:10px;">
      <button class="hamburger" id="hamburger" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <div>
        <div class="tb-breadcrumb">
          <a href="{{ url('/admin/applications') }}">Applications</a>
          <span class="sep">›</span>
          <span class="cur">{{ Str::limit($application->name, 28) }}</span>
        </div>
        <div class="tb-sub">Reviewing NGO application details</div>
      </div>
    </div>
    <div class="tb-right">
      {{-- Back button in topbar for quick nav --}}
      <a href="{{ route('admin.applications') }}" class="tb-btn" title="Back to Applications">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
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

  {{-- BODY --}}
  <div class="body">

    {{-- FLASH --}}
    @if(session('success'))
    <div class="alert-success">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
    @endif

    {{-- HERO CARD --}}
    <div class="hero-card">
      <div class="hero-left">
        <div class="hero-av">{{ strtoupper(substr($application->name, 0, 1)) }}</div>
        <div>
          <div class="hero-title">{{ $application->name }}</div>
          <div class="hero-sub">Submitted NGO Application · #{{ $application->id }}</div>
          <div class="hero-meta">
            @if($application->city || $application->state)
            <div class="hero-meta-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              {{ $application->city }}{{ $application->city && $application->state ? ', ' : '' }}{{ $application->state }}
            </div>
            @endif
            @if($application->submitted_at)
            <div class="hero-meta-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              {{ $application->submitted_at->format('d M Y') }}
            </div>
            @endif
            @if($application->organization_type)
            <div class="hero-meta-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              {{ $application->organization_type }}
            </div>
            @endif
          </div>
        </div>
      </div>
      <div class="hero-right">
        @if($application->status === 'pending')
          <span class="badge b-pending">Pending</span>
        @elseif($application->status === 'under_review')
          <span class="badge b-review">Under Review</span>
        @elseif($application->status === 'approved')
          <span class="badge b-approved">Approved</span>
        @elseif($application->status === 'rejected')
          <span class="badge b-rejected">Rejected</span>
        @endif
      </div>
    </div>

    {{-- DETAILS GRID --}}
    <div class="details-card">
      <div class="sec-hdr" style="margin-bottom:18px;">
        <span class="sec-ttl">Application Details</span>
      </div>
      <div class="details-grid">

        <div class="info-box">
          <div class="info-label">Organization Type</div>
          <div class="info-value {{ !$application->organization_type ? 'empty' : '' }}">
            {{ $application->organization_type ?? '—' }}
          </div>
        </div>

        <div class="info-box">
          <div class="info-label">Registration Number</div>
          <div class="info-value {{ !$application->registration_number ? 'empty' : '' }}">
            {{ $application->registration_number ?? '—' }}
          </div>
        </div>

        <div class="info-box">
          <div class="info-label">Founder Name</div>
          <div class="info-value {{ !$application->founder_name ? 'empty' : '' }}">
            {{ $application->founder_name ?? '—' }}
          </div>
        </div>

        <div class="info-box">
          <div class="info-label">Contact Name</div>
          <div class="info-value {{ !$application->contact_name ? 'empty' : '' }}">
            {{ $application->contact_name ?? '—' }}
          </div>
        </div>

        <div class="info-box">
          <div class="info-label">Contact Email</div>
          <div class="info-value {{ !$application->contact_email ? 'empty' : '' }}">
            {{ $application->contact_email ?? '—' }}
          </div>
        </div>

        <div class="info-box">
          <div class="info-label">Phone Number</div>
          <div class="info-value {{ !$application->contact_phone ? 'empty' : '' }}">
            {{ $application->contact_phone ?? '—' }}
          </div>
        </div>

        <div class="info-box">
          <div class="info-label">City</div>
          <div class="info-value {{ !$application->city ? 'empty' : '' }}">
            {{ $application->city ?? '—' }}
          </div>
        </div>

        <div class="info-box">
          <div class="info-label">State</div>
          <div class="info-value {{ !$application->state ? 'empty' : '' }}">
            {{ $application->state ?? '—' }}
          </div>
        </div>

        <div class="info-box">
          <div class="info-label">Pincode</div>
          <div class="info-value {{ !$application->pincode ? 'empty' : '' }}">
            {{ $application->pincode ?? '—' }}
          </div>
        </div>

        <div class="info-box" style="grid-column:1/-1;">
          <div class="info-label">Website</div>
          <div class="info-value {{ !$application->website ? 'empty' : '' }}">
            @if($application->website)
              <a href="{{ $application->website }}" target="_blank" rel="noopener"
                 style="color:var(--a);text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                {{ $application->website }}
                <svg style="width:11px;height:11px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              </a>
            @else
              —
            @endif
          </div>
        </div>

      </div>
    </div>

    {{-- ACTIONS CARD --}}
    <div class="actions-card">
      <div class="actions-left">
        <strong>Application Actions</strong>
        @if($application->status === 'pending' || $application->status === 'under_review')
          Approve to onboard this NGO, or reject with a reason.
        @else
          This application has been <strong style="color:var(--text);">{{ $application->status }}</strong>.
        @endif
      </div>
      <div class="actions-right">
        <a href="{{ route('admin.applications') }}" class="c-btn c-btn-back">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          Back to List
        </a>
        @if($application->status === 'pending' || $application->status === 'under_review')
          <form method="POST" action="{{ route('admin.applications.approve', $application->id) }}" onsubmit="return handleSub(this,'Approving…')">
            @csrf
            <button type="submit" class="c-btn c-btn-approve">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Approve Application
            </button>
          </form>
          <button type="button" class="c-btn c-btn-reject" onclick="openReject({{ $application->id }})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Reject Application
          </button>
        @endif
      </div>
    </div>

  </div>
</div>
</div>

{{-- ─── REJECT MODAL ────────────────────────────────────────────────────── --}}
<div id="rejectOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeReject()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="modal-head">
      <div class="modal-ico" style="background:var(--red-lt);">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div>
        <div class="modal-ttl">Reject Application</div>
        <div class="modal-sub">Reason will be shown to the applicant</div>
      </div>
    </div>
    <form id="rejectForm" method="POST">
      @csrf
      <div class="modal-lbl">Select or write a reason <span>*</span></div>
      <div class="chips">
        <button type="button" class="chip chip-red" data-r="Incomplete or missing documentation">Incomplete docs</button>
        <button type="button" class="chip chip-red" data-r="Organization does not meet eligibility criteria">Not eligible</button>
        <button type="button" class="chip chip-red" data-r="Fraudulent or misleading information provided">Fraudulent info</button>
        <button type="button" class="chip chip-red" data-r="Duplicate application already exists">Duplicate</button>
        <button type="button" class="chip chip-red" data-r="Violation of platform terms and conditions">Terms violation</button>
      </div>
      <textarea id="rejectReason" name="reason" rows="3" placeholder="Or type a custom reason…" class="modal-ta"></textarea>
      <p id="rejectErr" class="modal-err">⚠ Please provide a reason before rejecting.</p>
      <div class="modal-acts">
        <button type="button" onclick="closeReject()" class="modal-btn modal-cancel">Cancel</button>
        <button type="submit" id="rejectBtn" class="modal-btn modal-red">✕ Reject Application</button>
      </div>
    </form>
  </div>
</div>

<script>
(function(){
'use strict';

/* THEME */
var html=document.documentElement,toggle=document.getElementById('themeToggle');
var saved=localStorage.getItem('adminTheme')||'light';
if(saved==='dark'){html.setAttribute('data-theme','dark');toggle.checked=true;}
toggle.addEventListener('change',function(){
  var t=this.checked?'dark':'light';
  html.setAttribute('data-theme',t);
  localStorage.setItem('adminTheme',t);
});

/* HAMBURGER */
document.getElementById('hamburger').addEventListener('click',function(){
  document.getElementById('sidebar').classList.toggle('open');
});
document.addEventListener('click',function(e){
  var sb=document.getElementById('sidebar');
  if(window.innerWidth<=860&&!sb.contains(e.target)&&!document.getElementById('hamburger').contains(e.target))
    sb.classList.remove('open');
});

/* AVATAR DROPDOWN */
window.toggleDD=function(){document.getElementById('avDD').classList.toggle('open');};
document.addEventListener('click',function(e){
  var w=document.getElementById('avWrap');
  if(w&&!w.contains(e.target))document.getElementById('avDD').classList.remove('open');
});

/* TOAST */
function toast(msg,type){
  var icons={
    success:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    error:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
  };
  var t=document.createElement('div');
  t.className='toast toast-'+(type==='success'?'ok':'err');
  t.innerHTML=(icons[type]||'')+'<span>'+msg+'</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
  document.getElementById('toastWrap').appendChild(t);
  setTimeout(function(){t.style.transition='opacity .3s,transform .3s';t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(function(){t.remove();},300);},4200);
}
@if(session('success')) setTimeout(function(){toast(@json(session('success')),'success');},200); @endif
@if(session('error'))   setTimeout(function(){toast(@json(session('error')),'error');},200);   @endif

/* REJECT MODAL */
window.openReject=function(id){
  document.getElementById('rejectForm').action='/admin/applications/'+id+'/reject';
  document.getElementById('rejectReason').value='';
  document.getElementById('rejectErr').style.display='none';
  var btn=document.getElementById('rejectBtn');btn.disabled=false;btn.innerHTML='✕ Reject Application';
  document.querySelectorAll('.chip-red').forEach(function(c){c.classList.remove('on');});
  document.getElementById('rejectOverlay').classList.add('open');
  setTimeout(function(){document.getElementById('rejectReason').focus();},80);
};
window.closeReject=function(){document.getElementById('rejectOverlay').classList.remove('open');};

document.querySelectorAll('.chip-red').forEach(function(btn){
  btn.addEventListener('click',function(){
    document.querySelectorAll('.chip-red').forEach(function(b){b.classList.remove('on');});
    this.classList.add('on');
    document.getElementById('rejectReason').value=this.dataset.r;
    document.getElementById('rejectErr').style.display='none';
  });
});

document.getElementById('rejectForm').addEventListener('submit',function(e){
  if(!document.getElementById('rejectReason').value.trim()){
    e.preventDefault();document.getElementById('rejectErr').style.display='block';return;
  }
  var btn=document.getElementById('rejectBtn');btn.disabled=true;btn.innerHTML='Rejecting…';
});

document.getElementById('rejectOverlay').addEventListener('click',function(e){if(e.target===this)closeReject();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeReject();});

/* FORM SUBMIT HANDLER */
window.handleSub=function(form,txt){
  form.querySelectorAll('button[type=submit]').forEach(function(b){b.disabled=true;b.textContent=txt;});
  return true;
};

})();
</script>

</body>
</html>