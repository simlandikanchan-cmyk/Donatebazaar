{{-- resources/views/admin/events/create.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Create Event — DonateBazaar Admin</title>
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

/* ── PROGRESS STEPPER ── */
.stepper{display:flex;align-items:center;gap:0;margin-bottom:28px;animation:fadeUp .4s ease both;}
.step{display:flex;align-items:center;gap:10px;padding:12px 20px;border-radius:var(--r);font-size:13px;font-weight:600;font-family:var(--mono);transition:all .3s ease;cursor:pointer;position:relative;}
.step-num{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;transition:all .3s ease;}
.step-text{line-height:1.2;}
.step-label{font-size:12px;font-weight:700;}
.step-sublabel{font-size:10px;font-weight:400;color:var(--text3);margin-top:1px;}
/* idle */
.step-idle .step-num{background:var(--surface3);color:var(--text3);border:2px solid var(--border2);}
.step-idle .step-label{color:var(--text3);}
/* active */
.step-active .step-num{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 14px rgba(110,86,247,.4);}
.step-active .step-label{color:var(--a);}
.step-active .step-sublabel{color:var(--a);opacity:.7;}
.step-active{background:var(--a-lt);border:1px solid rgba(110,86,247,.2);}
/* done */
.step-done .step-num{background:var(--green);color:#fff;box-shadow:0 4px 14px rgba(5,196,138,.3);}
.step-done .step-label{color:var(--green);}
.step-done{background:var(--green-lt);border:1px solid rgba(5,196,138,.2);}
/* connector */
.step-connector{flex:1;height:2px;background:var(--border2);border-radius:2px;margin:0 4px;position:relative;overflow:hidden;}
.step-connector-fill{position:absolute;inset:0;background:linear-gradient(90deg,var(--green),var(--a));border-radius:2px;transform:scaleX(0);transform-origin:left;transition:transform .4s cubic-bezier(.4,0,.2,1);}
.step-connector-fill.filled{transform:scaleX(1);}

/* ── FORM LAYOUT ── */
.form-grid{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
@media(max-width:900px){.form-grid{grid-template-columns:1fr;}}

/* ── CARDS ── */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;}
.card-header{padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px;}
.card-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:16px;height:16px;}
.ci-purple{background:var(--a-lt);color:var(--a);}
.ci-green{background:var(--green-lt);color:var(--green);}
.ci-amber{background:var(--amber-lt);color:var(--amber);}
.ci-blue{background:var(--blue-lt);color:var(--blue);}
.card-title{font-family:var(--mono);font-size:14px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-subtitle{font-size:11px;color:var(--text3);margin-top:2px;}
.card-body{padding:22px;}
.card-body + .card-body{padding-top:0;}

/* ── STEP PANELS ── */
.step-panel{display:none;}
.step-panel.active{display:block;animation:fadeUp .3s ease both;}

/* ── CATEGORY GRID ── */
.cat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px;}
.cat-card{border:2px solid var(--border2);border-radius:var(--r-sm);padding:14px 12px;cursor:pointer;transition:all .2s ease;display:flex;flex-direction:column;align-items:center;gap:8px;text-align:center;background:var(--surface2);position:relative;overflow:hidden;}
.cat-card:hover{border-color:var(--a);background:var(--a-lt);transform:translateY(-2px);}
.cat-card.selected{border-color:var(--a);background:var(--a-lt);box-shadow:0 0 0 4px var(--a-glow);}
.cat-card.selected::after{content:'✓';position:absolute;top:6px;right:8px;font-size:10px;font-weight:700;color:var(--a);font-family:var(--mono);}
.cat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;}
.cat-name{font-size:12px;font-weight:600;color:var(--text);font-family:var(--mono);}
.cat-count{font-size:10px;color:var(--text3);}
.cat-search{width:100%;height:38px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:0 12px 0 36px;font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);margin-bottom:14px;}
.cat-search:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.cat-search-wrap{position:relative;}
.cat-search-wrap svg{position:absolute;left:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text3);pointer-events:none;}

/* ── CAMPAIGN PICKER ── */
.campaign-list{display:flex;flex-direction:column;gap:8px;max-height:340px;overflow-y:auto;}
.campaign-list::-webkit-scrollbar{width:4px;}
.campaign-list::-webkit-scrollbar-thumb{background:var(--border2);border-radius:4px;}
.campaign-item{border:2px solid var(--border2);border-radius:var(--r-sm);padding:14px 16px;cursor:pointer;transition:all .2s ease;display:flex;align-items:center;gap:14px;background:var(--surface2);}
.campaign-item:hover{border-color:var(--a);background:var(--a-lt);}
.campaign-item.selected{border-color:var(--a);background:var(--a-lt);box-shadow:0 0 0 3px var(--a-glow);}
.campaign-thumb{width:44px;height:44px;border-radius:10px;object-fit:cover;flex-shrink:0;background:var(--surface3);}
.campaign-thumb-placeholder{width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,var(--a-lt),var(--surface3));display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.campaign-thumb-placeholder svg{width:18px;height:18px;color:var(--a);opacity:.6;}
.campaign-info{flex:1;min-width:0;}
.campaign-title{font-size:13px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.campaign-meta{font-size:11px;color:var(--text3);margin-top:3px;font-family:var(--mono);}
.campaign-check{width:20px;height:20px;border-radius:50%;border:2px solid var(--border2);flex-shrink:0;display:flex;align-items:center;justify-content:center;transition:all .2s;}
.campaign-item.selected .campaign-check{background:var(--a);border-color:var(--a);}
.campaign-item.selected .campaign-check svg{display:block;}
.campaign-check svg{display:none;width:10px;height:10px;color:#fff;}
.no-campaigns{text-align:center;padding:32px 20px;color:var(--text3);font-size:13px;}
.no-campaigns svg{width:36px;height:36px;margin:0 auto 8px;display:block;opacity:.25;}

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

/* ── CHAR COUNTER ── */
.char-wrap{position:relative;}
.char-count{position:absolute;bottom:10px;right:12px;font-size:10px;font-family:var(--mono);color:var(--text3);}

/* ── SIDEBAR SUMMARY CARD ── */
.summary-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;position:sticky;top:82px;animation:fadeUp .4s .1s ease both;}
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
.sb-publish{background:var(--green-lt);color:#059669;}

/* ── DRAFT BANNER ── */
.draft-banner{background:linear-gradient(135deg,rgba(245,158,11,.08),rgba(245,158,11,.04));border:1px solid rgba(245,158,11,.25);border-radius:var(--r-sm);padding:14px 18px;display:flex;align-items:flex-start;gap:12px;margin-bottom:18px;}
.draft-banner svg{width:16px;height:16px;color:var(--amber);flex-shrink:0;margin-top:1px;}
.draft-banner-text{font-size:12.5px;color:var(--text2);line-height:1.6;}
.draft-banner-text strong{color:var(--amber);font-family:var(--mono);}

/* ── ACTION BUTTONS ── */
.action-bar{display:flex;gap:10px;align-items:center;flex-wrap:wrap;}
.btn{display:inline-flex;align-items:center;gap:8px;padding:11px 22px;border-radius:var(--r-sm);font-size:13px;font-weight:600;font-family:var(--font);cursor:pointer;border:none;transition:all var(--ease);}
.btn svg{width:14px;height:14px;}
.btn-publish{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 18px rgba(110,86,247,.4);}
.btn-publish:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(110,86,247,.5);}
.btn-draft{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.3);}
.btn-draft:hover{background:rgba(245,158,11,.2);transform:translateY(-1px);}
.btn-ghost{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-ghost:hover{background:var(--surface3);color:var(--text);}
.btn-next{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 18px rgba(110,86,247,.35);}
.btn-next:hover{transform:translateY(-2px);}
.btn-back-step{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-back-step:hover{background:var(--surface3);}
.btn-sm{padding:8px 16px;font-size:12px;}

/* ── STEP NAV ── */
.step-nav{display:flex;justify-content:space-between;align-items:center;margin-top:24px;padding-top:18px;border-top:1px solid var(--border);}

/* ── TAGS INPUT ── */
.tags-wrap{display:flex;flex-wrap:wrap;gap:6px;min-height:42px;background:var(--surface2);border:1.5px solid var(--border2);border-radius:var(--r-sm);padding:6px 10px;cursor:text;transition:border-color var(--ease),box-shadow var(--ease);}
.tags-wrap:focus-within{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.tag-pill{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:var(--a-lt);border:1px solid rgba(110,86,247,.2);border-radius:100px;font-size:11px;font-weight:600;color:var(--a);font-family:var(--mono);}
.tag-pill button{background:none;border:none;cursor:pointer;color:var(--a);font-size:14px;line-height:1;padding:0;display:flex;align-items:center;}
.tags-inp{border:none;outline:none;background:transparent;font-size:13px;color:var(--text);font-family:var(--font);min-width:120px;flex:1;}

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

/* ── FLASH ── */
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);}
.flash svg{width:14px;height:14px;flex-shrink:0;}

/* ── SELECTED BADGE ── */
.selected-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;background:var(--a-lt);border:1px solid rgba(110,86,247,.2);border-radius:var(--r-xs);font-size:12px;font-weight:600;color:var(--a);font-family:var(--mono);margin-bottom:14px;}
.selected-badge svg{width:12px;height:12px;}

/* ── UTILS ── */
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
.error-msg{font-size:11px;color:var(--red);margin-top:5px;font-family:var(--mono);display:none;}
.error-msg.show{display:block;}
@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}}
@media(max-width:600px){.topbar{padding:0 16px}.body{padding:14px 14px 48px}.row-2,.row-3{grid-template-columns:1fr}.stepper{gap:4px}.step{padding:8px 12px}}
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
        <h1>Create Event</h1>
        <p>{{ now()->format('l, d F Y') }}</p>
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

    {{-- ── STEPPER ── --}}
    <div class="stepper" id="stepper">
      <div class="step step-active" id="step-tab-1" onclick="goStep(1)">
        <div class="step-num">1</div>
        <div class="step-text">
          <div class="step-label">Category</div>
          <div class="step-sublabel">Pick a category</div>
        </div>
      </div>
      <div class="step-connector"><div class="step-connector-fill" id="conn-1"></div></div>
      <div class="step step-idle" id="step-tab-2" onclick="goStep(2)">
        <div class="step-num">2</div>
        <div class="step-text">
          <div class="step-label">Campaign</div>
          <div class="step-sublabel">Link a campaign</div>
        </div>
      </div>
      <div class="step-connector"><div class="step-connector-fill" id="conn-2"></div></div>
      <div class="step step-idle" id="step-tab-3" onclick="goStep(3)">
        <div class="step-num">3</div>
        <div class="step-text">
          <div class="step-label">Event Details</div>
          <div class="step-sublabel">Fill in the info</div>
        </div>
      </div>
      <div class="step-connector"><div class="step-connector-fill" id="conn-3"></div></div>
      <div class="step step-idle" id="step-tab-4" onclick="goStep(4)">
        <div class="step-num">4</div>
        <div class="step-text">
          <div class="step-label">Review & Publish</div>
          <div class="step-sublabel">Draft or go live</div>
        </div>
      </div>
    </div>

    @if ($errors->any())
    <div class="flash flash-error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      Please fix the errors below before submitting.
    </div>
    @endif

    {{-- THE FORM -- wraps all steps --}}
    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" id="eventForm">
      @csrf
      {{-- Hidden status field controlled by buttons --}}
      <input type="hidden" name="status" id="statusField" value="draft">

      <div class="form-grid">
        {{-- ── LEFT: STEP PANELS ── --}}
        <div>

          {{-- ───────── STEP 1: CATEGORY ───────── --}}
          <div class="step-panel active" id="panel-1">
            <div class="card">
              <div class="card-header">
                <div class="card-icon ci-purple">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
                </div>
                <div>
                  <div class="card-title">Choose a Category</div>
                  <div class="card-subtitle">Select the category this event belongs to — campaigns will filter accordingly</div>
                </div>
              </div>
              <div class="card-body">
                <div class="cat-search-wrap">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                  <input type="text" class="cat-search" id="catSearch" placeholder="Search categories…" autocomplete="off">
                </div>
                <input type="hidden" name="category_id" id="categoryInput" value="{{ old('category_id') }}">
                <div class="cat-grid" id="catGrid">
                  @forelse($categories as $cat)
                  <div class="cat-card {{ old('category_id') == $cat->id ? 'selected' : '' }}"
                       data-id="{{ $cat->id }}"
                       data-name="{{ strtolower($cat->name) }}"
                       onclick="selectCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ $cat->emoji ?? '' }}')">
                    <div class="cat-icon" style="background:{{ $cat->color ?? 'var(--a-lt)' }}20;">
                      {{ $cat->emoji ?? '📁' }}
                    </div>
                    <div class="cat-name">{{ $cat->name }}</div>
                    <div class="cat-count">{{ $cat->campaigns_count ?? 0 }} campaigns</div>
                  </div>
                  @empty
                  <div style="grid-column:1/-1;text-align:center;padding:32px;color:var(--text3);font-size:13px;">
                    No categories found.
                  </div>
                  @endforelse
                </div>
                @error('category_id')
                  <div class="error-msg show">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="step-nav">
              <span></span>
              <button type="button" class="btn btn-next" onclick="nextStep(1)">
                Next: Pick Campaign
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </button>
            </div>
          </div>

          {{-- ───────── STEP 2: CAMPAIGN ───────── --}}
          <div class="step-panel" id="panel-2">
            <div class="card">
              <div class="card-header">
                <div class="card-icon ci-green">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                  <div class="card-title">Select Campaign</div>
                  <div class="card-subtitle" id="campaignSubtitle">Showing campaigns for selected category</div>
                </div>
              </div>
              <div class="card-body">
                <div id="selectedCatBadge" style="display:none;">
                  <div class="selected-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
                    <span id="selectedCatName"></span>
                  </div>
                </div>
                <input type="hidden" name="campaign_id" id="campaignInput" value="{{ old('campaign_id') }}">
                <div class="campaign-list" id="campaignList">
                  <div class="no-campaigns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Select a category first to see campaigns
                  </div>
                </div>
                @error('campaign_id')
                  <div class="error-msg show" style="margin-top:8px;">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="step-nav">
              <button type="button" class="btn btn-back-step" onclick="prevStep(2)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Back
              </button>
              <button type="button" class="btn btn-next" onclick="nextStep(2)">
                Next: Event Details
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </button>
            </div>
          </div>

          {{-- ───────── STEP 3: EVENT DETAILS ───────── --}}
          <div class="step-panel" id="panel-3">
            <div class="card" style="animation-delay:.05s;">
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
                    value="{{ old('title') }}" maxlength="255" id="titleInp">
                  @error('title')<div class="error-msg show">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                  <label class="field-label">Description <span class="req">*</span></label>
                  <div class="char-wrap">
                    <textarea name="description" class="textarea {{ $errors->has('description') ? 'inp-error' : '' }}"
                      placeholder="Describe what this event is about, who should attend, and what attendees can expect…"
                      maxlength="2000" id="descInp" rows="5">{{ old('description') }}</textarea>
                    <span class="char-count" id="descCount">0 / 2000</span>
                  </div>
                  @error('description')<div class="error-msg show">{{ $message }}</div>@enderror
                </div>

                <div class="row-2">
                  <div class="field">
                    <label class="field-label">Event Date <span class="req">*</span></label>
                    <input type="date" name="event_date" class="inp {{ $errors->has('event_date') ? 'inp-error' : '' }}"
                      value="{{ old('event_date') }}" min="{{ date('Y-m-d') }}">
                    @error('event_date')<div class="error-msg show">{{ $message }}</div>@enderror
                  </div>
                  <div class="field">
                    <label class="field-label">Location / Venue</label>
                    <input type="text" name="location" class="inp"
                      placeholder="e.g. Mumbai Convention Centre"
                      value="{{ old('location') }}" maxlength="255">
                  </div>
                </div>

                <div class="row-3">
                  <div class="field">
                    <label class="field-label">Start Time</label>
                    <input type="time" name="start_time" class="inp" value="{{ old('start_time') }}">
                  </div>
                  <div class="field">
                    <label class="field-label">End Time</label>
                    <input type="time" name="end_time" class="inp" value="{{ old('end_time') }}">
                  </div>
                  <div class="field">
                    <label class="field-label">Max Participants</label>
                    <input type="number" name="max_participants" class="inp"
                      placeholder="Unlimited" min="1"
                      value="{{ old('max_participants') }}">
                    <div class="field-hint">Leave blank for unlimited</div>
                  </div>
                </div>

                <div class="field">
                  <label class="field-label">Fundraising Goal (₹) <span class="req">*</span></label>
                  <input type="number" name="goal_amount" class="inp {{ $errors->has('goal_amount') ? 'inp-error' : '' }}"
                    placeholder="e.g. 100000" min="1" step="0.01"
                    value="{{ old('goal_amount') }}" id="goalInp">
                  @error('goal_amount')<div class="error-msg show">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>

            {{-- Cover Image --}}
            <div class="card" style="margin-top:16px;animation-delay:.1s;">
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
                <div class="upload-zone" id="uploadZone">
                  <input type="file" name="cover_image" id="coverInput" accept="image/*" onchange="previewImage(this)">
                  <img src="" alt="Preview" class="upload-preview" id="uploadPreview">
                  <div class="upload-overlay"><span>Click to change</span></div>
                  <div id="uploadPlaceholder">
                    <div class="upload-icon">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="upload-text">Click to upload cover image</div>
                    <div class="upload-sub">PNG, JPG, WEBP — max 2MB</div>
                  </div>
                </div>
              </div>
            </div>

            <div class="step-nav">
              <button type="button" class="btn btn-back-step" onclick="prevStep(3)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Back
              </button>
              <button type="button" class="btn btn-next" onclick="nextStep(3)">
                Next: Review
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </button>
            </div>
          </div>

          {{-- ───────── STEP 4: REVIEW & PUBLISH ───────── --}}
          <div class="step-panel" id="panel-4">
            <div class="card" style="animation-delay:.05s;">
              <div class="card-header">
                <div class="card-icon ci-purple">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                  <div class="card-title">Review Your Event</div>
                  <div class="card-subtitle">Check all details before publishing or saving as draft</div>
                </div>
              </div>
              <div class="card-body">
                {{-- Review preview --}}
                <div id="reviewContent" style="display:flex;flex-direction:column;gap:10px;">
                  <div style="background:var(--surface2);border-radius:var(--r-sm);padding:16px 18px;">
                    <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:12px;">Event Overview</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;" id="reviewGrid">
                      <div><div style="font-size:10px;color:var(--text3);font-family:var(--mono);margin-bottom:3px;">CATEGORY</div><div style="font-size:13px;font-weight:600;color:var(--text);" id="rv-cat">—</div></div>
                      <div><div style="font-size:10px;color:var(--text3);font-family:var(--mono);margin-bottom:3px;">CAMPAIGN</div><div style="font-size:13px;font-weight:600;color:var(--text);" id="rv-campaign">—</div></div>
                      <div><div style="font-size:10px;color:var(--text3);font-family:var(--mono);margin-bottom:3px;">EVENT TITLE</div><div style="font-size:13px;font-weight:600;color:var(--text);" id="rv-title">—</div></div>
                      <div><div style="font-size:10px;color:var(--text3);font-family:var(--mono);margin-bottom:3px;">DATE</div><div style="font-size:13px;font-weight:600;color:var(--text);" id="rv-date">—</div></div>
                      <div><div style="font-size:10px;color:var(--text3);font-family:var(--mono);margin-bottom:3px;">GOAL AMOUNT</div><div style="font-size:13px;font-weight:600;color:var(--text);" id="rv-goal">—</div></div>
                      <div><div style="font-size:10px;color:var(--text3);font-family:var(--mono);margin-bottom:3px;">PARTICIPANTS</div><div style="font-size:13px;font-weight:600;color:var(--text);" id="rv-participants">—</div></div>
                    </div>
                  </div>
                </div>

                {{-- Draft banner --}}
                <div class="draft-banner" style="margin-top:18px;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  <div class="draft-banner-text">
                    <strong>Not ready yet?</strong> Save as <strong>Draft</strong> — you can edit every detail and publish whenever you're ready. Drafts are invisible to the public until you publish them.
                  </div>
                </div>

                {{-- Settings toggles --}}
                <div style="margin-top:18px;">
                  <div style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:10px;">Event Settings</div>
                  <div class="toggle-row">
                    <div class="toggle-info">
                      <div class="toggle-name">Allow Registrations</div>
                      <div class="toggle-desc">Participants can register for this event</div>
                    </div>
                    <label class="toggle">
                      <input type="checkbox" name="allow_registrations" value="1" checked>
                      <span class="toggle-slider"></span>
                    </label>
                  </div>
                  <div class="toggle-row">
                    <div class="toggle-info">
                      <div class="toggle-name">Show on Campaign Page</div>
                      <div class="toggle-desc">Display this event on the linked campaign page</div>
                    </div>
                    <label class="toggle">
                      <input type="checkbox" name="show_on_campaign" value="1" checked>
                      <span class="toggle-slider"></span>
                    </label>
                  </div>
                  <div class="toggle-row">
                    <div class="toggle-info">
                      <div class="toggle-name">Send Notification Email</div>
                      <div class="toggle-desc">Notify campaign followers when published</div>
                    </div>
                    <label class="toggle">
                      <input type="checkbox" name="send_notification" value="1">
                      <span class="toggle-slider"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            {{-- Action bar --}}
            <div class="step-nav" style="flex-wrap:wrap;gap:10px;">
              <button type="button" class="btn btn-back-step" onclick="prevStep(4)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Back to Edit
              </button>
              <div class="action-bar">
                <button type="submit" class="btn btn-draft" onclick="setStatus('draft')">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                  Save as Draft
                </button>
                <button type="submit" class="btn btn-publish" onclick="setStatus('active')">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  Publish Event
                </button>
              </div>
            </div>
          </div>

        </div>

        {{-- ── RIGHT: LIVE SUMMARY ── --}}
        <div>
          <div class="summary-card">
            <div class="summary-header">
              <div class="summary-title">Event Summary</div>
            </div>
            <div class="summary-body">
              <div class="summary-item">
                <div class="summary-key">Status</div>
                <span class="summary-badge sb-draft" id="summaryStatus">Draft</span>
              </div>
              <div class="summary-item">
                <div class="summary-key">Category</div>
                <div class="summary-val empty" id="sum-cat">Not selected</div>
              </div>
              <div class="summary-item">
                <div class="summary-key">Campaign</div>
                <div class="summary-val empty" id="sum-campaign">Not selected</div>
              </div>
              <div class="summary-item">
                <div class="summary-key">Title</div>
                <div class="summary-val empty" id="sum-title">Untitled event</div>
              </div>
              <div class="summary-item">
                <div class="summary-key">Date</div>
                <div class="summary-val empty" id="sum-date">Not set</div>
              </div>
              <div class="summary-item">
                <div class="summary-key">Goal</div>
                <div class="summary-val empty" id="sum-goal">Not set</div>
              </div>
              <div class="summary-item">
                <div class="summary-key">Max Participants</div>
                <div class="summary-val empty" id="sum-participants">Unlimited</div>
              </div>
            </div>
            <div style="padding:0 18px 16px;">
              <div style="font-size:11px;color:var(--text3);line-height:1.5;font-family:var(--mono);">
                Complete all steps and publish to make this event live, or save as draft to continue later.
              </div>
            </div>
          </div>
        </div>

      </div>{{-- /.form-grid --}}
    </form>

  </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

{{-- Campaigns data (passed from controller) --}}
<script>
var campaignsData = @json($campaignsByCategory ?? []);
// Shape: { category_id: [{id, title, cover_image, goal_amount, status}, ...] }
</script>

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

/* ── Step state ── */
var currentStep = 1;
var state = { categoryId: null, categoryName: '', campaignId: null, campaignName: '' };

/* ── Stepper UI ── */
function updateStepper(step) {
  for (var i = 1; i <= 4; i++) {
    var tab = document.getElementById('step-tab-' + i);
    tab.className = 'step ' + (i < step ? 'step-done' : i === step ? 'step-active' : 'step-idle');
    if (i < step) {
      tab.querySelector('.step-num').innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
    } else {
      tab.querySelector('.step-num').textContent = i;
    }
  }
  for (var c = 1; c <= 3; c++) {
    var fill = document.getElementById('conn-' + c);
    fill.className = 'step-connector-fill' + (c < step ? ' filled' : '');
  }
}

function showPanel(step) {
  document.querySelectorAll('.step-panel').forEach(function(p){ p.classList.remove('active'); });
  document.getElementById('panel-' + step).classList.add('active');
  updateStepper(step);
  currentStep = step;
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

window.goStep = function(step) {
  if (step < currentStep) showPanel(step); // allow going back freely
};

/* ── Next / Prev ── */
window.nextStep = function(from) {
  if (from === 1) {
    if (!state.categoryId) { alert('Please select a category first.'); return; }
    loadCampaigns(state.categoryId);
    showPanel(2);
  } else if (from === 2) {
    if (!state.campaignId) { alert('Please select a campaign first.'); return; }
    showPanel(3);
  } else if (from === 3) {
    if (!validateStep3()) return;
    populateReview();
    showPanel(4);
  }
};

window.prevStep = function(from) { showPanel(from - 1); };

/* ── Step 3 validation ── */
function validateStep3() {
  var title = document.querySelector('[name="title"]').value.trim();
  var desc  = document.querySelector('[name="description"]').value.trim();
  var date  = document.querySelector('[name="event_date"]').value;
  var goal  = document.querySelector('[name="goal_amount"]').value;
  if (!title) { alert('Event title is required.'); return false; }
  if (!desc)  { alert('Description is required.'); return false; }
  if (!date)  { alert('Event date is required.'); return false; }
  if (!goal || parseFloat(goal) <= 0) { alert('A valid goal amount is required.'); return false; }
  return true;
}

/* ── Category selection ── */
window.selectCategory = function(id, name, emoji) {
  state.categoryId   = id;
  state.categoryName = emoji + ' ' + name;
  document.getElementById('categoryInput').value = id;
  document.querySelectorAll('.cat-card').forEach(function(c){ c.classList.remove('selected'); });
  document.querySelector('.cat-card[data-id="' + id + '"]').classList.add('selected');
  updateSummary();
};

/* ── Category search ── */
document.getElementById('catSearch').addEventListener('input', function(){
  var q = this.value.toLowerCase();
  document.querySelectorAll('.cat-card').forEach(function(c){
    c.style.display = (!q || c.dataset.name.includes(q)) ? '' : 'none';
  });
});

/* ── Load campaigns by category ── */
/* ── Load campaigns by category ── */
function loadCampaigns(catId) {
  var list = document.getElementById('campaignList');
  var campaigns = campaignsData[catId] || [];

  // Update subtitle
  document.getElementById('campaignSubtitle').textContent =
    campaigns.length + ' campaign' + (campaigns.length !== 1 ? 's' : '') + ' in ' + state.categoryName;

  // Show category badge
  var badge = document.getElementById('selectedCatBadge');
  badge.style.display = 'block';
  document.getElementById('selectedCatName').textContent = state.categoryName;

  if (!campaigns.length) {
    list.innerHTML = '<div class="no-campaigns"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>No active campaigns found for this category</div>';
    return;
  }

  var html = '';
  campaigns.forEach(function(c) {
    var thumb = c.cover_image
      ? '<img src="/storage/' + c.cover_image + '" class="campaign-thumb" alt="">'
      : '<div class="campaign-thumb-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>';
    var goal = c.goal_amount ? '₹' + Number(c.goal_amount).toLocaleString('en-IN') : '';
    html += '<div class="campaign-item" data-id="' + c.id + '" data-name="' + escHtml(c.title) + '" onclick="selectCampaign(' + c.id + ', \'' + escJs(c.title) + '\')">'
      + thumb
      + '<div class="campaign-info"><div class="campaign-title">' + escHtml(c.title) + '</div>'
      + '<div class="campaign-meta">' + (goal ? goal + ' goal' : '') + ' · active</div></div>'
      + '<div class="campaign-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>'
      + '</div>';
  });
  list.innerHTML = html;
}

/* ── Campaign selection ── */
window.selectCampaign = function(id, name) {
  state.campaignId   = id;
  state.campaignName = name;
  document.getElementById('campaignInput').value = id;
  document.querySelectorAll('.campaign-item').forEach(function(c){ c.classList.remove('selected'); });
  var el = document.querySelector('.campaign-item[data-id="' + id + '"]');
  if (el) el.classList.add('selected');
  updateSummary();
};

/* ── Live summary ── */
function updateSummary() {
  setText('sum-cat',          state.categoryName || null);
  setText('sum-campaign',     state.campaignName || null);
  setText('sum-title',        document.querySelector('[name="title"]').value.trim() || null);
  setText('sum-date',         document.querySelector('[name="event_date"]').value || null);
  var g = document.querySelector('[name="goal_amount"]').value;
  setText('sum-goal',         g ? '₹' + Number(g).toLocaleString('en-IN') : null);
  var mp = document.querySelector('[name="max_participants"]').value;
  setText('sum-participants', mp ? mp + ' max' : 'Unlimited');
}

function setText(id, val) {
  var el = document.getElementById(id);
  if (!el) return;
  if (val) { el.textContent = val; el.classList.remove('empty'); }
  else      { el.textContent = el.dataset.empty || 'Not set'; el.classList.add('empty'); }
}

/* ── Populate review panel ── */
function populateReview() {
  document.getElementById('rv-cat').textContent          = state.categoryName || '—';
  document.getElementById('rv-campaign').textContent     = state.campaignName || '—';
  document.getElementById('rv-title').textContent        = document.querySelector('[name="title"]').value || '—';
  document.getElementById('rv-date').textContent         = document.querySelector('[name="event_date"]').value || '—';
  var g  = document.querySelector('[name="goal_amount"]').value;
  document.getElementById('rv-goal').textContent         = g ? '₹' + Number(g).toLocaleString('en-IN') : '—';
  var mp = document.querySelector('[name="max_participants"]').value;
  document.getElementById('rv-participants').textContent = mp ? mp + ' max' : 'Unlimited';
}

/* ── Live summary listeners ── */
['[name="title"]','[name="event_date"]','[name="goal_amount"]','[name="max_participants"]'].forEach(function(sel){
  var el = document.querySelector(sel);
  if (el) el.addEventListener('input', updateSummary);
});

/* ── Status control ── */
window.setStatus = function(val) {
  document.getElementById('statusField').value = val;
  var badge = document.getElementById('summaryStatus');
  if (val === 'active') {
    badge.className = 'summary-badge sb-publish';
    badge.textContent = 'Active';
  } else {
    badge.className = 'summary-badge sb-draft';
    badge.textContent = 'Draft';
  }
};

/* ── Cover image preview ── */
window.previewImage = function(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var preview  = document.getElementById('uploadPreview');
    var zone     = document.getElementById('uploadZone');
    var placeholder = document.getElementById('uploadPlaceholder');
    preview.src = e.target.result;
    preview.classList.add('show');
    placeholder.style.display = 'none';
    zone.classList.add('has-preview');
  };
  reader.readAsDataURL(input.files[0]);
};

/* ── Description char counter ── */
var descEl    = document.getElementById('descInp');
var descCount = document.getElementById('descCount');
if (descEl) {
  descEl.addEventListener('input', function(){
    descCount.textContent = this.value.length + ' / 2000';
  });
}

/* ── Helper utils ── */
function escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function escJs(str) {
  return String(str).replace(/\\/g,'\\\\').replace(/'/g,"\\'");
}

/* ── Init summary empty labels ── */
['sum-cat','sum-campaign','sum-title','sum-date','sum-goal'].forEach(function(id){
  var el = document.getElementById(id);
  if (el) el.dataset.empty = el.textContent;
});

/* ── If old() values exist (after validation fail), restore state ── */
(function restoreOldValues(){
  var oldCatId   = document.getElementById('categoryInput').value;
  var oldCampId  = document.getElementById('campaignInput').value;
  if (oldCatId) {
    var catCard = document.querySelector('.cat-card[data-id="' + oldCatId + '"]');
    if (catCard) {
      var emoji = catCard.querySelector('.cat-icon').textContent.trim();
      var name  = catCard.querySelector('.cat-name').textContent.trim();
      state.categoryId   = parseInt(oldCatId);
      state.categoryName = emoji + ' ' + name;
      catCard.classList.add('selected');
    }
  }
  if (oldCampId) {
    // Campaign will reload when navigating to step 2
    state.campaignId = parseInt(oldCampId);
  }
  updateSummary();
})();

})();
</script>
</body>
</html>