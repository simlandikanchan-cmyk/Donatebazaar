{{-- resources/views/admin/job_posts/create.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Post a Job — DonateBazaar Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
/* ══════════════════════════════════════════════
   DESIGN TOKENS — light sidebar (shared across all admin pages)
══════════════════════════════════════════════ */
:root {
  --bg:#f4f5fb; --surface:#fff; --surface2:#f8f9fe; --surface3:#eef0fa;
  --border:rgba(0,0,0,.06); --border2:rgba(0,0,0,.10);
  --text:#0a0b14; --text2:#454863; --text3:#9096b4;
  /* sidebar — LIGHT */
  --sb-bg:#ffffff; --sb-txt:#5a5f7a; --sb-act:rgba(110,86,247,.10); --sb-border:rgba(0,0,0,.08);
  --a:#6e56f7; --a2:#9b6dff; --a-lt:rgba(110,86,247,.10); --a-glow:rgba(110,86,247,.22);
  --green:#05c48a; --green-lt:rgba(5,196,138,.10);
  --amber:#f59e0b; --amber-lt:rgba(245,158,11,.10);
  --red:#f04444;   --red-lt:rgba(240,68,68,.10);
  --blue:#3b82f6;  --blue-lt:rgba(59,130,246,.10);
  --pink:#ec4899;  --pink-lt:rgba(236,72,153,.10);
  --font:'DM Sans',sans-serif; --mono:'DM Mono',monospace;
  --r:18px; --r-sm:12px; --r-xs:8px;
  --sh:0 1px 3px rgba(0,0,0,.05),0 4px 24px rgba(0,0,0,.04);
  --sh-md:0 4px 20px rgba(0,0,0,.08),0 1px 4px rgba(0,0,0,.04);
  --sh-lg:0 6px 20px rgba(0,0,0,.16);
  --ease:.18s ease; --sb-w:268px;
}
[data-theme="dark"] {
  --bg:#070810; --surface:#0f1020; --surface2:#161728; --surface3:#1d1f35;
  --border:rgba(255,255,255,.055); --border2:rgba(255,255,255,.09);
  --text:#eef0ff; --text2:#9ba3c8; --text3:#4c5272;
  --sb-bg:#050609; --sb-txt:rgba(255,255,255,.48); --sb-act:rgba(110,86,247,.22); --sb-border:rgba(255,255,255,.03);
  --a-glow:rgba(110,86,247,.30);
  --sh:0 1px 3px rgba(0,0,0,.35),0 4px 24px rgba(0,0,0,.25);
  --sh-md:0 4px 20px rgba(0,0,0,.4),0 1px 4px rgba(0,0,0,.25);
  --sh-lg:0 12px 48px rgba(0,0,0,.6);
}

/* ── RESET ── */
*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
html,body { height:100%; }
body { font-family:var(--font); background:var(--bg); color:var(--text); line-height:1.55;
       -webkit-font-smoothing:antialiased; overflow-x:hidden; transition:background .2s,color .2s; }
a { text-decoration:none; color:inherit; }

/* ── LAYOUT ── */
.shell { display:flex; min-height:100vh; }
.main  { margin-left:var(--sb-w); flex:1; min-width:0; display:flex; flex-direction:column; min-height:100vh; }

/* ══════════════════════════════════════════════
   SIDEBAR — light
══════════════════════════════════════════════ */
.sidebar { width:var(--sb-w); flex-shrink:0; background:var(--sb-bg); display:flex; flex-direction:column;
           position:fixed; top:0; left:0; bottom:0; z-index:400; overflow-y:auto; overflow-x:hidden;
           border-right:1px solid var(--sb-border); box-shadow:2px 0 16px rgba(0,0,0,.06);
           transition:transform .3s cubic-bezier(.4,0,.2,1); }
.sidebar::-webkit-scrollbar { width:0; }

.s-logo       { display:flex; align-items:center; gap:12px; padding:26px 22px 22px; border-bottom:1px solid var(--sb-border); }
.s-logo-mark  { width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,var(--a),var(--a2));
                display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 18px rgba(110,86,247,.4); }
.s-logo-mark svg { width:20px; height:20px; color:#fff; }
.s-logo-name  { font-family:var(--mono); font-size:17px; font-weight:800; color:var(--text); letter-spacing:-.02em; line-height:1.1; }
.s-logo-tag   { font-size:9px; color:var(--text3); text-transform:uppercase; letter-spacing:.16em; font-family:var(--mono); }

.s-admin-pill { margin:14px 12px 4px; padding:10px 14px;
                background:linear-gradient(135deg,rgba(110,86,247,.08),rgba(155,109,255,.05));
                border:1px solid rgba(110,86,247,.15); border-radius:var(--r-sm);
                display:flex; align-items:center; gap:10px; }
.s-av         { width:34px; height:34px; border-radius:9px; background:linear-gradient(135deg,var(--a),var(--a2));
                color:#fff; font-size:13px; font-weight:700; display:flex; align-items:center;
                justify-content:center; flex-shrink:0; overflow:hidden; }
.s-av img     { width:100%; height:100%; object-fit:cover; }
.s-admin-name { font-size:12.5px; font-weight:600; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.s-admin-role { font-size:10px; color:var(--text3); margin-top:1px; font-family:var(--mono); }
.s-online     { width:7px; height:7px; border-radius:50%; background:var(--green); margin-left:auto;
                flex-shrink:0; box-shadow:0 0 0 2.5px rgba(5,196,138,.2); }

.s-section { font-size:9px; font-weight:700; color:var(--text3); text-transform:uppercase;
             letter-spacing:.18em; padding:20px 22px 6px; font-family:var(--mono); }
.s-nav     { padding:2px 10px; }
.s-link    { display:flex; align-items:center; gap:11px; padding:9px 12px; border-radius:var(--r-xs);
             color:var(--sb-txt); font-size:13px; font-weight:500; text-decoration:none;
             transition:background var(--ease),color var(--ease); margin-bottom:1px;
             border:none; background:transparent; width:100%; text-align:left; cursor:pointer;
             position:relative; font-family:var(--font); }
.s-link:hover { background:var(--a-lt); color:var(--a); }
.s-link.active { background:var(--sb-act); color:var(--a); font-weight:600; }
.s-link.active::before { content:''; position:absolute; left:0; top:22%; bottom:22%;
                          width:3px; border-radius:0 3px 3px 0; background:var(--a); }
.s-ico { width:15px; height:15px; flex-shrink:0; opacity:.65; }
.s-link:hover .s-ico, .s-link.active .s-ico { opacity:1; }
.s-chip    { margin-left:auto; font-size:10px; font-weight:700; padding:2px 7px; border-radius:100px; font-family:var(--mono); }
.sc-teal   { background:var(--green-lt); color:#059669; }
.sc-amber  { background:var(--amber-lt); color:#b45309; }
.s-divider { height:1px; background:var(--sb-border); margin:10px 18px; }
.s-bottom  { margin-top:auto; padding:10px 10px 20px; border-top:1px solid var(--sb-border); }

/* ══════════════════════════════════════════════
   TOPBAR
══════════════════════════════════════════════ */
.topbar    { display:flex; align-items:center; justify-content:space-between; padding:0 28px; height:62px;
             background:var(--surface); border-bottom:1px solid var(--border); position:sticky; top:0; z-index:200; gap:14px; }
.tb-left h1 { font-family:var(--mono); font-size:17px; font-weight:700; color:var(--text); letter-spacing:-.02em; }
.tb-left p  { font-size:11px; color:var(--text3); margin-top:1px; font-family:var(--mono); }
.tb-right   { display:flex; align-items:center; gap:8px; }

.theme-wrap       { position:relative; }
.theme-wrap input { position:absolute; opacity:0; width:0; height:0; }
.theme-wrap label { display:flex; align-items:center; justify-content:space-between; width:52px; height:28px;
                    border-radius:100px; background:var(--surface2); border:1px solid var(--border2);
                    cursor:pointer; padding:4px; position:relative; }
.theme-wrap label::after { content:''; width:18px; height:18px; border-radius:50%; background:var(--a);
                            position:absolute; left:5px; transition:transform .3s cubic-bezier(.4,0,.2,1);
                            box-shadow:0 2px 6px rgba(110,86,247,.4); }
.theme-wrap input:checked + label::after { transform:translateX(23px); }
.ti     { display:flex; justify-content:space-between; width:100%; position:relative; z-index:1; padding:0 2px; }
.ti svg { width:11px; height:11px; color:var(--text3); }

.av-wrap  { position:relative; }
.t-av     { width:36px; height:36px; border-radius:var(--r-sm); background:linear-gradient(135deg,var(--a),var(--a2));
            color:#fff; font-size:13px; font-weight:700; font-family:var(--mono); display:flex;
            align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; overflow:hidden;
            box-shadow:0 2px 10px rgba(110,86,247,.38); }
.t-av img { width:100%; height:100%; object-fit:cover; }
.av-dd    { position:absolute; top:calc(100% + 10px); right:0; background:var(--surface);
            border:1px solid var(--border2); border-radius:var(--r); box-shadow:var(--sh-lg);
            min-width:215px; z-index:9999; display:none; animation:ddIn .18s ease; }
.av-dd.open { display:block; }
@keyframes ddIn { from{opacity:0;transform:translateY(-6px) scale(.97)} to{opacity:1;transform:none} }
.dd-hdr   { padding:14px 16px; border-bottom:1px solid var(--border); }
.dd-name  { font-size:13.5px; font-weight:700; color:var(--text); font-family:var(--mono); }
.dd-email { font-size:11px; color:var(--text3); margin-top:2px; font-family:var(--mono); }
.dd-item  { display:flex; align-items:center; gap:10px; padding:9px 16px; font-size:12.5px;
            font-weight:500; color:var(--text2); cursor:pointer; transition:background var(--ease); text-decoration:none; }
.dd-item:hover { background:var(--surface2); color:var(--text); }
.dd-item svg { width:13px; height:13px; color:var(--text3); flex-shrink:0; }
.dd-item.accent { color:var(--a); } .dd-item.accent svg { color:var(--a); }
.dd-item.danger { color:var(--red); } .dd-item.danger svg { color:var(--red); }
.dd-sep   { height:1px; background:var(--border); margin:3px 0; }

.hamburger { display:none; width:36px; height:36px; border-radius:var(--r-sm); border:1px solid var(--border2);
             background:var(--surface2); cursor:pointer; color:var(--text2); align-items:center;
             justify-content:center; flex-shrink:0; }
.hamburger svg { width:15px; height:15px; }

/* ══════════════════════════════════════════════
   PAGE BODY
══════════════════════════════════════════════ */
.body { padding:26px 28px 56px; flex:1; }

/* ── BREADCRUMB ── */
.breadcrumb     { display:flex; align-items:center; gap:6px; font-size:12px; color:var(--text3);
                  font-family:var(--mono); margin-bottom:22px; animation:fadeUp .3s ease both; }
.breadcrumb a   { color:var(--text3); transition:color var(--ease); }
.breadcrumb a:hover { color:var(--a); }
.breadcrumb svg { width:12px; height:12px; flex-shrink:0; }
.breadcrumb span { color:var(--text2); font-weight:600; }

/* ── HERO ── */
.hero { border-radius:22px; padding:28px 32px; margin-bottom:28px; display:flex;
        align-items:center; justify-content:space-between; gap:20px;
        position:relative; overflow:hidden; animation:fadeUp .4s ease both; background:#07080f; }
.hero::before { content:''; position:absolute; inset:0;
  background:radial-gradient(ellipse 60% 80% at 80% -10%,rgba(5,196,138,.45) 0%,transparent 60%),
             radial-gradient(ellipse 50% 60% at 10% 110%,rgba(110,86,247,.35) 0%,transparent 55%); }
.hero::after { content:''; position:absolute; inset:0;
  background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),
                   linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);
  background-size:32px 32px; }
.hero-left { position:relative; z-index:2; }
.hero-tag  { display:inline-flex; align-items:center; gap:6px; font-size:10px; font-weight:600;
             color:rgba(255,255,255,.55); text-transform:uppercase; letter-spacing:.14em;
             font-family:var(--mono); margin-bottom:10px; }
.hero-tag-dot { width:6px; height:6px; border-radius:50%; background:var(--green); animation:pulse 2s ease infinite; }
@keyframes pulse { 0%,100%{box-shadow:0 0 0 0 rgba(5,196,138,.5)} 50%{box-shadow:0 0 0 6px rgba(5,196,138,0)} }
.hero-name { font-family:var(--mono); font-size:26px; font-weight:800; letter-spacing:-.03em; line-height:1.1;
             background:linear-gradient(135deg,#fff 30%,rgba(184,169,255,.85));
             -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.hero-sub  { font-size:13.5px; color:rgba(255,255,255,.52); margin-top:6px; max-width:420px; line-height:1.6; }
.hero-right { position:relative; z-index:2; display:flex; gap:10px; flex-wrap:wrap; }
.hero-btn   { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border-radius:var(--r-sm);
              font-size:13px; font-weight:600; text-decoration:none; font-family:var(--font);
              transition:all var(--ease); cursor:pointer; border:none; }
.hero-btn svg { width:14px; height:14px; }
.hero-btn-ghost { background:rgba(255,255,255,.1); color:rgba(255,255,255,.85); border:1px solid rgba(255,255,255,.15); }
.hero-btn-ghost:hover { background:rgba(255,255,255,.18); transform:translateY(-2px); }

/* ══════════════════════════════════════════════
   FORM LAYOUT
══════════════════════════════════════════════ */
.form-layout { display:grid; grid-template-columns:1fr 320px; gap:20px; align-items:start; }

/* ══════════════════════════════════════════════
   CARDS
══════════════════════════════════════════════ */
.card     { background:var(--surface); border:1px solid var(--border); border-radius:var(--r);
            padding:26px; box-shadow:var(--sh); animation:fadeUp .4s ease both; }
.card + .card { margin-top:16px; }
.card-hdr { display:flex; align-items:center; gap:12px; margin-bottom:22px;
            padding-bottom:18px; border-bottom:1px solid var(--border); }
.card-ico { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.card-ico svg { width:17px; height:17px; }
.ci-teal   { background:rgba(5,196,138,.12);  color:#05c48a; }
.ci-purple { background:var(--a-lt);           color:var(--a); }
.ci-amber  { background:var(--amber-lt);        color:var(--amber); }
.ci-blue   { background:var(--blue-lt);         color:var(--blue); }
.card-ttl  { font-family:var(--mono); font-size:14.5px; font-weight:700; color:var(--text); letter-spacing:-.01em; }
.card-sub  { font-size:11px; color:var(--text3); margin-top:2px; font-family:var(--mono); }

/* ══════════════════════════════════════════════
   FORM FIELDS
══════════════════════════════════════════════ */
.field           { margin-bottom:20px; }
.field:last-child { margin-bottom:0; }
.field-row       { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

label.lbl { display:block; font-size:12px; font-weight:600; color:var(--text2); margin-bottom:8px;
            font-family:var(--mono); letter-spacing:.04em; text-transform:uppercase; }
label.lbl .req { color:var(--red); margin-left:2px; }
label.lbl .counter { float:right; font-size:11px; color:var(--text3); font-weight:400;
                     text-transform:none; letter-spacing:0; transition:color var(--ease); }
label.lbl .counter.warn { color:var(--amber); }
label.lbl .counter.over { color:var(--red); }

.inp, .sel, .ta {
  width:100%; padding:11px 14px; border:1px solid var(--border2); border-radius:var(--r-sm);
  font-size:13.5px; color:var(--text); font-family:var(--font); background:var(--surface2);
  outline:none; transition:border-color var(--ease),box-shadow var(--ease),background var(--ease); line-height:1.5;
}
.inp:focus, .sel:focus, .ta:focus {
  border-color:var(--a); box-shadow:0 0 0 3px var(--a-glow); background:var(--surface);
}
.inp::placeholder, .ta::placeholder { color:var(--text3); }
.sel { cursor:pointer; appearance:none;
       background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
       background-repeat:no-repeat; background-position:right 12px center; background-size:14px; padding-right:38px; }
.ta { resize:vertical; min-height:120px; }
.inp.err, .sel.err, .ta.err { border-color:var(--red); box-shadow:0 0 0 3px rgba(240,68,68,.12); }

/* input with icon */
.inp-wrap { position:relative; }
.inp-wrap .inp-icon { position:absolute; left:13px; top:50%; transform:translateY(-50%);
                      width:15px; height:15px; color:var(--text3); pointer-events:none; }
.inp-wrap .inp { padding-left:40px; }

.field-hint  { font-size:11px; color:var(--text3); margin-top:6px; font-family:var(--mono); line-height:1.5; }
.field-error { font-size:11.5px; color:var(--red); margin-top:6px; font-family:var(--mono); font-weight:600; display:none; }
.field-error.show { display:block; }

/* ── TOGGLE ROW ── */
.toggle-row { display:flex; align-items:center; justify-content:space-between; padding:14px 16px;
              background:var(--surface2); border:1px solid var(--border2); border-radius:var(--r-sm); gap:14px; }
.toggle-row-title { font-size:13.5px; font-weight:600; color:var(--text); }
.toggle-row-sub   { font-size:11px; color:var(--text3); margin-top:2px; font-family:var(--mono); }
.toggle-switch    { position:relative; flex-shrink:0; }
.toggle-switch input { position:absolute; opacity:0; width:0; height:0; }
.toggle-switch label { display:block; width:44px; height:24px; border-radius:100px;
                       background:var(--border2); cursor:pointer; position:relative; transition:background .2s; }
.toggle-switch label::after { content:''; position:absolute; top:3px; left:3px; width:18px; height:18px;
                               border-radius:50%; background:#fff;
                               transition:transform .2s cubic-bezier(.4,0,.2,1);
                               box-shadow:0 1px 4px rgba(0,0,0,.18); }
.toggle-switch input:checked + label           { background:var(--a); }
.toggle-switch input:checked + label::after    { transform:translateX(20px); }
.toggle-switch input.amber-tog:checked + label { background:var(--amber); }
.toggle-row.active-toggle        { background:var(--a-lt);    border-color:rgba(110,86,247,.25); }
.toggle-row.active-toggle-amber  { background:var(--amber-lt); border-color:rgba(245,158,11,.25); }

/* ── STATUS PILLS ── */
.status-pills { display:flex; gap:8px; flex-wrap:wrap; }
.status-pill  { display:flex; align-items:center; gap:8px; padding:10px 16px; border-radius:var(--r-sm);
                border:1.5px solid var(--border2); background:var(--surface2); cursor:pointer;
                transition:all .15s; flex:1; min-width:80px; }
.status-pill input { display:none; }
.sp-dot   { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
.sp-lbl   { font-size:12.5px; font-weight:600; color:var(--text2); font-family:var(--font); }
.sp-sub   { font-size:10px; color:var(--text3); font-family:var(--mono); margin-top:1px; }
.status-pill:has(input:checked) { border-color:currentColor; }
.sp-draft:has(input:checked)   { background:rgba(107,114,128,.08); border-color:rgba(107,114,128,.35); color:#6b7280; }
.sp-draft:has(input:checked) .sp-lbl { color:#6b7280; }
.sp-draft .sp-dot  { background:#6b7280; }
.sp-active:has(input:checked)  { background:var(--green-lt); border-color:rgba(5,196,138,.35); }
.sp-active:has(input:checked) .sp-lbl { color:var(--green); }
.sp-active .sp-dot { background:var(--green); }
.sp-closed:has(input:checked)  { background:var(--red-lt); border-color:rgba(240,68,68,.3); }
.sp-closed:has(input:checked) .sp-lbl { color:var(--red); }
.sp-closed .sp-dot { background:var(--red); }

/* ── TAGS INPUT ── */
.tags-wrap { display:flex; flex-wrap:wrap; gap:6px; padding:9px 12px; min-height:46px;
             border:1px solid var(--border2); border-radius:var(--r-sm); background:var(--surface2);
             cursor:text; transition:border-color var(--ease),box-shadow var(--ease),background var(--ease); }
.tags-wrap:focus-within { border-color:var(--a); box-shadow:0 0 0 3px var(--a-glow); background:var(--surface); }
.tag-item { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:100px;
            background:var(--a-lt); border:1px solid rgba(110,86,247,.2); color:var(--a);
            font-size:12px; font-weight:600; font-family:var(--mono); animation:tagIn .15s ease; }
@keyframes tagIn { from{opacity:0;transform:scale(.85)} to{opacity:1;transform:none} }
.tag-remove { width:14px; height:14px; border-radius:50%; background:rgba(110,86,247,.2);
              border:none; color:var(--a); cursor:pointer; font-size:9px;
              display:flex; align-items:center; justify-content:center; transition:background var(--ease); flex-shrink:0; }
.tag-remove:hover { background:rgba(240,68,68,.2); color:var(--red); }
.tag-input { border:none; outline:none; background:transparent; font-size:13px; color:var(--text);
             font-family:var(--font); min-width:120px; flex:1; padding:2px 0; }
.tag-input::placeholder { color:var(--text3); }

/* ── SLUG PREVIEW ── */
.slug-preview { display:flex; align-items:center; gap:8px; margin-top:8px; padding:8px 12px;
                background:var(--surface2); border:1px solid var(--border); border-radius:var(--r-xs);
                font-family:var(--mono); font-size:11px; color:var(--text3); overflow:hidden; }
.slug-base { color:var(--text3); flex-shrink:0; }
.slug-val  { color:var(--a); font-weight:600; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.slug-lock { margin-left:auto; flex-shrink:0; padding:3px 8px; border-radius:5px;
             font-size:10px; font-weight:700; border:1px solid var(--border2); background:var(--surface);
             color:var(--text3); cursor:pointer; font-family:var(--mono); transition:all var(--ease); }
.slug-lock:hover { color:var(--a); border-color:var(--a); }

/* ── VACANCIES BADGE ── */
.num-wrap { position:relative; }
.num-wrap .inp { padding-right:52px; }
.num-badge { position:absolute; right:10px; top:50%; transform:translateY(-50%);
             font-size:10px; font-weight:700; font-family:var(--mono); color:var(--text3);
             background:var(--surface3); border:1px solid var(--border2); border-radius:5px;
             padding:2px 6px; pointer-events:none; }

/* ══════════════════════════════════════════════
   STICKY SIDEBAR PANEL
══════════════════════════════════════════════ */
.side-stack { display:flex; flex-direction:column; gap:16px; position:sticky; top:80px; }

.preview-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--r);
                padding:20px; box-shadow:var(--sh); animation:fadeUp .4s .08s ease both; }
.preview-hdr  { display:flex; align-items:center; gap:8px; margin-bottom:14px; }
.preview-hdr svg { width:14px; height:14px; color:var(--text3); }
.preview-hdr span { font-size:12px; font-weight:700; color:var(--text2); font-family:var(--mono);
                    text-transform:uppercase; letter-spacing:.08em; }
.prev-title { font-family:var(--mono); font-size:16px; font-weight:700; color:var(--text); margin-bottom:6px; min-height:24px; }
.prev-meta  { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:10px; }
.prev-chip  { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600;
              padding:4px 10px; border-radius:100px; font-family:var(--mono);
              background:var(--surface2); border:1px solid var(--border2); color:var(--text3); }
.prev-chip svg { width:10px; height:10px; }
.prev-chip.remote-chip   { background:var(--a-lt);    border-color:rgba(110,86,247,.25); color:var(--a); }
.prev-chip.featured-chip { background:var(--amber-lt); border-color:rgba(245,158,11,.25); color:var(--amber); }
.prev-desc   { font-size:12px; color:var(--text3); line-height:1.6; min-height:36px; }
.prev-status-row { display:flex; align-items:center; justify-content:space-between;
                   margin-top:12px; padding-top:12px; border-top:1px solid var(--border); }
.prev-dot  { width:8px; height:8px; border-radius:50%; margin-right:5px; }
.prev-lbl  { font-size:11px; font-weight:600; font-family:var(--mono); }

.tips-card { background:linear-gradient(135deg,rgba(110,86,247,.06),rgba(155,109,255,.03));
             border:1px solid rgba(110,86,247,.15); border-radius:var(--r); padding:18px;
             animation:fadeUp .4s .12s ease both; }
.tips-hdr  { display:flex; align-items:center; gap:8px; margin-bottom:12px; }
.tips-hdr svg  { width:15px; height:15px; color:var(--a); }
.tips-hdr span { font-size:12px; font-weight:700; color:var(--a); font-family:var(--mono);
                 text-transform:uppercase; letter-spacing:.08em; }
.tip-item   { display:flex; align-items:flex-start; gap:8px; margin-bottom:9px;
              font-size:12px; color:var(--text2); line-height:1.5; }
.tip-item:last-child { margin-bottom:0; }
.tip-num    { width:16px; height:16px; border-radius:5px; background:var(--a-lt); color:var(--a);
              font-size:9px; font-weight:700; display:flex; align-items:center; justify-content:center;
              flex-shrink:0; margin-top:1px; font-family:var(--mono); }

/* ══════════════════════════════════════════════
   SUBMIT ROW & BUTTONS
══════════════════════════════════════════════ */
.submit-row  { display:flex; align-items:center; justify-content:space-between; gap:12px;
               margin-top:20px; padding-top:20px; border-top:1px solid var(--border); flex-wrap:wrap; }
.submit-info { font-size:12px; color:var(--text3); font-family:var(--mono); }
.submit-info .req { color:var(--red); }
.submit-btns { display:flex; gap:9px; }

.btn { display:inline-flex; align-items:center; gap:7px; padding:11px 22px; border-radius:var(--r-sm);
       font-size:13px; font-weight:600; cursor:pointer; border:none; transition:all var(--ease); font-family:var(--font); }
.btn:active { transform:scale(.97); }
.btn svg { width:14px; height:14px; }
.btn-secondary { background:var(--surface2); color:var(--text2); border:1px solid var(--border2); }
.btn-secondary:hover { background:var(--surface3); color:var(--text); }
.btn-draft { background:rgba(107,114,128,.12); color:#6b7280; border:1px solid rgba(107,114,128,.22); }
.btn-draft:hover { background:rgba(107,114,128,.2); border-color:rgba(107,114,128,.35); }
.btn-primary { background:#6366f1; color:#fff; box-shadow:0 4px 18px rgba(99,102,241,.3); }
.btn-primary:hover { transform:translateY(-2px); box-shadow:0 8px 28px rgba(99,102,241,.45); }
.btn-primary:disabled { opacity:.6; cursor:not-allowed; transform:none; box-shadow:none; }

/* ══════════════════════════════════════════════
   ALERTS & TOAST
══════════════════════════════════════════════ */
.alert { display:flex; align-items:flex-start; gap:12px; padding:14px 16px; border-radius:var(--r-sm);
         margin-bottom:20px; font-size:13px; animation:fadeUp .3s ease both; }
.alert svg { width:16px; height:16px; flex-shrink:0; margin-top:1px; }
.alert-error { background:var(--red-lt); border:1px solid rgba(240,68,68,.22); color:var(--red); }
.alert ul { padding-left:16px; margin-top:6px; }
.alert ul li { margin-bottom:3px; font-size:12px; }

.toast-wrap { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:8px; pointer-events:none; }
.toast { display:flex; align-items:center; gap:10px; padding:13px 16px; border-radius:14px; font-size:13px;
         font-weight:500; color:#fff; min-width:270px; box-shadow:var(--sh-lg); pointer-events:all; animation:toastIn .3s ease both; }
.toast svg { width:15px; height:15px; flex-shrink:0; }
.toast-ok  { background:linear-gradient(135deg,#059669,#10b981); }
.toast-err { background:linear-gradient(135deg,#dc2626,#f04444); }
.toast-x   { margin-left:auto; width:18px; height:18px; border-radius:5px; background:rgba(255,255,255,.22);
             border:none; cursor:pointer; color:#fff; font-size:11px; display:flex; align-items:center; justify-content:center; }

/* ── KEYFRAMES ── */
@keyframes fadeUp  { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:none} }
@keyframes toastIn { from{opacity:0;transform:translateX(18px) scale(.96)} to{opacity:1;transform:none} }
@keyframes spin    { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }

/* ── SCROLLBARS ── */
::-webkit-scrollbar { width:5px; height:5px; }
::-webkit-scrollbar-track { background:transparent; }
::-webkit-scrollbar-thumb { background:var(--border2); border-radius:100px; }

/* ── RESPONSIVE ── */
@media(max-width:1100px) { .form-layout{grid-template-columns:1fr;} .side-stack{position:static;} }
@media(max-width:860px)  { .sidebar{transform:translateX(-100%)} .sidebar.open{transform:translateX(0)} .main{margin-left:0} .hamburger{display:flex} }
@media(max-width:600px)  { .topbar{padding:0 16px} .body{padding:14px 14px 48px} .field-row{grid-template-columns:1fr} .hero{flex-direction:column} }
</style>
</head>
<body>

<div class="toast-wrap" id="toastWrap"></div>

<div class="shell">

{{-- ══════════ SIDEBAR ══════════ --}}
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
      @if(auth()->user()->avatar)<img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
      @else{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}@endif
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
    <a href="{{ route('admin.job_posts.create') }}" class="s-link active">
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

{{-- ══════════ MAIN ══════════ --}}
<div class="main">
  <header class="topbar">
    <div style="display:flex;align-items:center;gap:10px;">
      <button class="hamburger" id="hamburger" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <div class="tb-left">
        <h1>Post a New Job</h1>
        <p>Job Board › Create Listing</p>
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
          @if(auth()->user()->avatar)<img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
          @else{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}@endif
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

    <div class="breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <a href="{{ route('admin.job_posts.index') }}">Job Posts</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <span>Create New</span>
    </div>

    {{-- ══════════ HERO ══════════ --}}
    <div class="hero">
      <div class="hero-left">
        <div class="hero-tag"><span class="hero-tag-dot"></span>Job Board</div>
        <div class="hero-name">Post a New Job</div>
        <div class="hero-sub">Fill in the details below to publish a listing on the DonateBazaar Job Board.</div>
      </div>
      <div class="hero-right">
        <a href="{{ route('admin.job_posts.index') }}" class="hero-btn hero-btn-ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          Back to Listings
        </a>
      </div>
    </div>

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
    <div class="alert alert-error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      <div>
        <strong>Please fix the following errors:</strong>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    </div>
    @endif

    {{-- ══════════ FORM ══════════ --}}
    <form id="jobForm" action="{{ route('admin.job_posts.store') }}" method="POST" novalidate>
      @csrf

      <div class="form-layout">

        {{-- ── LEFT: MAIN FIELDS ── --}}
        <div>

          {{-- ════ CARD 1: BASIC INFO ════ --}}
          <div class="card" style="animation-delay:.05s;">
            <div class="card-hdr">
              <div class="card-ico ci-teal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
              <div>
                <div class="card-ttl">Basic Information</div>
                <div class="card-sub">Core job details visible to all applicants</div>
              </div>
            </div>

            {{-- Job Title --}}
            <div class="field">
              <label class="lbl" for="title">
                Job Title <span class="req">*</span>
                <span class="counter" id="titleCounter">0 / 150</span>
              </label>
              <div class="inp-wrap">
                <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <input type="text" id="title" name="title" class="inp @error('title') err @enderror"
                  placeholder="e.g. Senior Product Designer" value="{{ old('title') }}"
                  maxlength="150" autocomplete="off" required>
              </div>
              @error('title')<p class="field-error show">{{ $message }}</p>@enderror
              <p class="field-hint">Be specific — a clear title attracts better candidates.</p>
            </div>

            {{-- Slug --}}
            <div class="field">
              <label class="lbl" for="slug">URL Slug <span class="req">*</span></label>
              <div class="inp-wrap">
                <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                <input type="text" id="slug" name="slug" class="inp @error('slug') err @enderror"
                  placeholder="auto-generated-from-title" value="{{ old('slug') }}"
                  maxlength="255" autocomplete="off">
              </div>
              @error('slug')<p class="field-error show">{{ $message }}</p>@enderror
              <div class="slug-preview">
                <span class="slug-base">/jobs/</span>
                <span class="slug-val" id="slugDisplay">your-job-slug-here</span>
                <button type="button" class="slug-lock" id="slugLockBtn">Auto</button>
              </div>
              <p class="field-hint">Auto-generated from title. Edit manually to customise the URL.</p>
            </div>

            {{-- Type + Department --}}
            <div class="field-row field">
              <div>
                <label class="lbl" for="type">Job Type <span class="req">*</span></label>
                <select id="type" name="type" class="sel @error('type') err @enderror" required>
                  <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select type…</option>
                  @foreach(['full-time'=>'Full-time','part-time'=>'Part-time','contract'=>'Contract','internship'=>'Internship','volunteer'=>'Volunteer','freelance'=>'Freelance','remote'=>'Remote'] as $val=>$lbl)
                    <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                  @endforeach
                </select>
                @error('type')<p class="field-error show">{{ $message }}</p>@enderror
              </div>
              <div>
                <label class="lbl" for="department">Department</label>
                <div class="inp-wrap">
                  <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                  <input type="text" id="department" name="department" class="inp @error('department') err @enderror"
                    placeholder="e.g. Engineering, Marketing" value="{{ old('department') }}" autocomplete="off">
                </div>
                @error('department')<p class="field-error show">{{ $message }}</p>@enderror
              </div>
            </div>

            {{-- Location + Experience --}}
            <div class="field-row field">
              <div>
                <label class="lbl" for="location">Location</label>
                <div class="inp-wrap">
                  <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  <input type="text" id="location" name="location" class="inp @error('location') err @enderror"
                    placeholder="e.g. Mumbai, India" value="{{ old('location') }}" autocomplete="off">
                </div>
                @error('location')<p class="field-error show">{{ $message }}</p>@enderror
              </div>
              <div>
                <label class="lbl" for="experience_required">Experience Required</label>
                <div class="inp-wrap">
                  <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                  <input type="text" id="experience_required" name="experience_required"
                    class="inp @error('experience_required') err @enderror"
                    placeholder="e.g. 2–4 years, Fresher OK" value="{{ old('experience_required') }}" autocomplete="off">
                </div>
                @error('experience_required')<p class="field-error show">{{ $message }}</p>@enderror
                <p class="field-hint">Leave blank if open to all experience levels.</p>
              </div>
            </div>

            {{-- Salary + Vacancies --}}
            <div class="field-row field">
              <div>
                <label class="lbl" for="salary">Salary / Compensation</label>
                <div class="inp-wrap">
                  <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  <input type="text" id="salary" name="salary" class="inp @error('salary') err @enderror"
                    placeholder="e.g. ₹6–10 LPA" value="{{ old('salary') }}" autocomplete="off">
                </div>
                @error('salary')<p class="field-error show">{{ $message }}</p>@enderror
                <p class="field-hint">Ranges perform better than fixed figures.</p>
              </div>
              <div>
                <label class="lbl" for="vacancies">No. of Vacancies</label>
                <div class="num-wrap">
                  <input type="number" id="vacancies" name="vacancies" class="inp @error('vacancies') err @enderror"
                    placeholder="1" value="{{ old('vacancies', 1) }}" min="1" max="9999" autocomplete="off">
                  <span class="num-badge" id="vacancyBadge">open</span>
                </div>
                @error('vacancies')<p class="field-error show">{{ $message }}</p>@enderror
                <p class="field-hint">Total open positions for this role.</p>
              </div>
            </div>

            {{-- Application Deadline --}}
            <div class="field">
              <label class="lbl" for="application_deadline">Application Deadline</label>
              <div class="inp-wrap">
                <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <input type="date" id="application_deadline" name="application_deadline"
                  class="inp @error('application_deadline') err @enderror"
                  value="{{ old('application_deadline') }}" min="{{ date('Y-m-d') }}">
              </div>
              @error('application_deadline')<p class="field-error show">{{ $message }}</p>@enderror
              <p class="field-hint">Leave blank for a rolling / no-deadline listing.</p>
            </div>

            {{-- Remote Toggle --}}
            <div class="field">
              <label class="lbl">Remote Work</label>
              <label class="toggle-row" id="remoteRow">
                <div>
                  <div class="toggle-row-title">This is a remote position</div>
                  <div class="toggle-row-sub">Enables the "Remote" badge on the listing</div>
                </div>
                <div class="toggle-switch">
                  <input type="checkbox" id="is_remote" name="is_remote" value="1" {{ old('is_remote') ? 'checked' : '' }}>
                  <label for="is_remote"></label>
                </div>
              </label>
            </div>

            {{-- Featured Toggle --}}
            <div class="field">
              <label class="lbl">Featured Listing</label>
              <label class="toggle-row" id="featuredRow">
                <div>
                  <div class="toggle-row-title">Mark as a featured job</div>
                  <div class="toggle-row-sub">Pinned at the top of the board with a ★ badge</div>
                </div>
                <div class="toggle-switch">
                  <input type="checkbox" id="featured" name="featured" value="1" class="amber-tog" {{ old('featured') ? 'checked' : '' }}>
                  <label for="featured" id="featuredLabel"></label>
                </div>
              </label>
            </div>
          </div>{{-- /card 1 --}}

          {{-- ════ CARD 2: DESCRIPTION ════ --}}
          <div class="card" style="animation-delay:.10s;">
            <div class="card-hdr">
              <div class="card-ico ci-purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h10"/></svg>
              </div>
              <div>
                <div class="card-ttl">Job Description</div>
                <div class="card-sub">Detailed overview, responsibilities &amp; requirements</div>
              </div>
            </div>

            <div class="field">
              <label class="lbl" for="description">
                Description <span class="req">*</span>
                <span class="counter" id="descCounter" style="font-weight:400;">0 chars</span>
              </label>
              <textarea id="description" name="description"
                class="ta @error('description') err @enderror" rows="10"
                placeholder="Describe the role, key responsibilities, required qualifications, benefits…"
                required>{{ old('description') }}</textarea>
              @error('description')<p class="field-error show">{{ $message }}</p>@enderror
              <p class="field-hint">Tip: Use sections like "About the Role", "Responsibilities", "Requirements", and "Benefits".</p>
            </div>

            {{-- Skills Tags --}}
            <div class="field">
              <label class="lbl" for="skillTagInput">Required Skills</label>
              <div class="tags-wrap" id="tagsWrap" onclick="document.getElementById('skillTagInput').focus()">
                <input type="text" id="skillTagInput" placeholder="Type a skill and press Enter or comma…"
                  class="tag-input" autocomplete="off">
              </div>
              <input type="hidden" name="skills" id="skillsHidden"
                value="{{ old('skills') ? (is_array(old('skills')) ? implode(',', old('skills')) : old('skills')) : '' }}">
              @error('skills')<p class="field-error show">{{ $message }}</p>@enderror
              <p class="field-hint">Press <kbd style="font-family:var(--mono);font-size:10px;padding:1px 5px;border:1px solid var(--border2);border-radius:4px;">Enter</kbd> or <kbd style="font-family:var(--mono);font-size:10px;padding:1px 5px;border:1px solid var(--border2);border-radius:4px;">,</kbd> after each skill.</p>
            </div>
          </div>{{-- /card 2 --}}

          {{-- ════ CARD 3: SEO ════ --}}
          <div class="card" style="animation-delay:.13s;">
            <div class="card-hdr">
              <div class="card-ico ci-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              </div>
              <div>
                <div class="card-ttl">SEO &amp; Meta</div>
                <div class="card-sub">Improve discoverability in search engines (optional)</div>
              </div>
            </div>

            <div class="field">
              <label class="lbl" for="meta_title">
                Meta Title
                <span class="counter" id="metaTitleCounter" style="font-weight:400;">0 / 70</span>
              </label>
              <div class="inp-wrap">
                <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
                <input type="text" id="meta_title" name="meta_title" class="inp @error('meta_title') err @enderror"
                  placeholder="e.g. Senior Product Designer at DonateBazaar | Remote"
                  value="{{ old('meta_title') }}" maxlength="70" autocomplete="off">
              </div>
              @error('meta_title')<p class="field-error show">{{ $message }}</p>@enderror
              <p class="field-hint">Defaults to the job title if left blank. Keep under 60 chars for best results.</p>
            </div>

            <div class="field">
              <label class="lbl" for="meta_description">
                Meta Description
                <span class="counter" id="metaDescCounter" style="font-weight:400;">0 / 160</span>
              </label>
              <textarea id="meta_description" name="meta_description"
                class="ta @error('meta_description') err @enderror" rows="3"
                placeholder="A short summary shown in Google search results…"
                maxlength="160" style="min-height:80px;">{{ old('meta_description') }}</textarea>
              @error('meta_description')<p class="field-error show">{{ $message }}</p>@enderror
              <p class="field-hint">Aim for 120–160 characters. Describe who the role is for and what makes it exciting.</p>
            </div>
          </div>{{-- /card 3 --}}

          {{-- ════ CARD 4: STATUS & SUBMIT ════ --}}
          <div class="card" style="animation-delay:.16s;">
            <div class="card-hdr">
              <div class="card-ico ci-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <div>
                <div class="card-ttl">Publication Status</div>
                <div class="card-sub">Control visibility of this job listing</div>
              </div>
            </div>

            <div class="field">
              <label class="lbl">Status <span class="req">*</span></label>
              <div class="status-pills">
                <label class="status-pill sp-draft">
                  <input type="radio" name="status" value="draft" {{ old('status','draft') === 'draft' ? 'checked' : '' }}>
                  <div class="sp-dot"></div>
                  <div><div class="sp-lbl">Draft</div><div class="sp-sub">Not visible yet</div></div>
                </label>
                <label class="status-pill sp-active">
                  <input type="radio" name="status" value="active" {{ old('status') === 'active' ? 'checked' : '' }}>
                  <div class="sp-dot"></div>
                  <div><div class="sp-lbl">Active</div><div class="sp-sub">Live &amp; accepting</div></div>
                </label>
                <label class="status-pill sp-closed">
                  <input type="radio" name="status" value="closed" {{ old('status') === 'closed' ? 'checked' : '' }}>
                  <div class="sp-dot"></div>
                  <div><div class="sp-lbl">Closed</div><div class="sp-sub">No longer hiring</div></div>
                </label>
              </div>
              @error('status')<p class="field-error show">{{ $message }}</p>@enderror
            </div>

            <div class="submit-row">
              <div class="submit-info">Fields marked <span class="req">*</span> are required</div>
              <div class="submit-btns">
                <a href="{{ route('admin.job_posts.index') }}" class="btn btn-secondary">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                  Discard
                </a>
                <button type="submit" name="_action" value="draft" class="btn btn-draft" id="draftBtn">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                  Save Draft
                </button>
                <button type="submit" name="_action" value="publish" class="btn btn-primary" id="publishBtn">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  Publish Job
                </button>
              </div>
            </div>
          </div>{{-- /card 4 --}}

        </div>{{-- /left col --}}

        {{-- ── RIGHT: STICKY PANEL ── --}}
        <div class="side-stack">

          {{-- LIVE PREVIEW --}}
          <div class="preview-card">
            <div class="preview-hdr">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              <span>Live Preview</span>
            </div>
            <div class="prev-title" id="prevTitle">Job title will appear here</div>
            <div class="prev-meta">
              <span class="prev-chip" id="prevType"     style="display:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg><span id="prevTypeVal"></span></span>
              <span class="prev-chip" id="prevDept"     style="display:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg><span id="prevDeptVal"></span></span>
              <span class="prev-chip" id="prevLoc"      style="display:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span id="prevLocVal"></span></span>
              <span class="prev-chip" id="prevSal"      style="display:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span id="prevSalVal"></span></span>
              <span class="prev-chip" id="prevVac"      style="display:none;"><span id="prevVacVal"></span></span>
              <span class="prev-chip remote-chip"   id="prevRemote"   style="display:none;">Remote</span>
              <span class="prev-chip featured-chip" id="prevFeatured" style="display:none;">★ Featured</span>
              <span class="prev-chip" id="prevDeadline" style="display:none;"><span id="prevDeadlineVal"></span></span>
            </div>
            <div class="prev-desc" id="prevDesc">Description preview will appear here…</div>
            <div class="prev-status-row">
              <div style="display:flex;align-items:center;">
                <div class="prev-dot" id="prevDot" style="background:#6b7280;"></div>
                <span class="prev-lbl" id="prevStatus" style="color:#6b7280;">Draft</span>
              </div>
              <span style="font-size:11px;color:var(--text3);font-family:var(--mono);">Preview</span>
            </div>
          </div>

          {{-- TIPS --}}
          <div class="tips-card">
            <div class="tips-hdr">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
              <span>Posting Tips</span>
            </div>
            @foreach([
              ['Use a specific job title — avoid internal codes or vague titles like "Associate".'],
              ['Include a salary range — listings with pay details get 3× more applicants.'],
              ['Add skills tags to help candidates and search engines match the role.'],
              ['Set an application deadline to create urgency and manage inbox volume.'],
              ['Toggle Featured to pin this listing at the top of the job board.'],
            ] as $idx => $tip)
            <div class="tip-item">
              <div class="tip-num">{{ $idx + 1 }}</div>
              <div>{{ $tip[0] }}</div>
            </div>
            @endforeach
          </div>

          {{-- QUICK LINKS --}}
          <div class="preview-card" style="animation-delay:.16s;">
            <div class="preview-hdr">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
              <span>Quick Links</span>
            </div>
            <div style="display:flex;flex-direction:column;gap:4px;margin-top:4px;">
              <a href="{{ route('admin.job_posts.index') }}" class="s-link" style="padding:8px 10px;font-size:12.5px;">
                <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h10"/></svg>
                All Job Posts
              </a>
              <a href="{{ route('admin.job_post_applications.index') }}" class="s-link" style="padding:8px 10px;font-size:12.5px;">
                <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                Job Applicants
              </a>
              <a href="{{ route('admin.dashboard') }}" class="s-link" style="padding:8px 10px;font-size:12.5px;">
                <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
              </a>
            </div>
          </div>

        </div>{{-- /side-stack --}}
      </div>{{-- /form-layout --}}
    </form>

  </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

<script>
(function () {
  'use strict';

  /* ── Theme ── */
  var html   = document.documentElement;
  var toggle = document.getElementById('themeToggle');
  var saved  = localStorage.getItem('adminTheme') || 'light';
  if (saved === 'dark') { html.setAttribute('data-theme', 'dark'); toggle.checked = true; }
  toggle.addEventListener('change', function () {
    var t = this.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', t);
    localStorage.setItem('adminTheme', t);
  });

  /* ── Hamburger ── */
  var sidebar = document.getElementById('sidebar');
  document.getElementById('hamburger').addEventListener('click', function () { sidebar.classList.toggle('open'); });
  document.addEventListener('click', function (e) {
    if (window.innerWidth <= 860 && !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target))
      sidebar.classList.remove('open');
  });

  /* ── Avatar dropdown ── */
  window.toggleDD = function () { document.getElementById('avDD').classList.toggle('open'); };
  document.addEventListener('click', function (e) {
    var w = document.getElementById('avWrap');
    if (w && !w.contains(e.target)) document.getElementById('avDD').classList.remove('open');
  });

  /* ── Toast ── */
  function toast(msg, type) {
    var t  = document.createElement('div');
    t.className = 'toast toast-' + (type === 'success' ? 'ok' : 'err');
    var ok  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    var err = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    t.innerHTML = (type === 'success' ? ok : err) + '<span>' + msg + '</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
    document.getElementById('toastWrap').appendChild(t);
    setTimeout(function () {
      t.style.transition = 'opacity .3s,transform .3s'; t.style.opacity = '0'; t.style.transform = 'translateX(20px)';
      setTimeout(function () { t.remove(); }, 300);
    }, 4200);
  }
  @if(session('success')) setTimeout(function(){ toast(@json(session('success')), 'success'); }, 200); @endif
  @if(session('error'))   setTimeout(function(){ toast(@json(session('error')),   'error');   }, 200); @endif

  /* ─────────────────────────────────────────
     SLUG AUTO-GENERATION
  ───────────────────────────────────────── */
  var titleInp  = document.getElementById('title');
  var slugInp   = document.getElementById('slug');
  var slugDisp  = document.getElementById('slugDisplay');
  var slugBtn   = document.getElementById('slugLockBtn');
  var slugAuto  = true;

  function toSlug(s) {
    return s.toLowerCase().replace(/[^a-z0-9\s-]/g,'').trim()
            .replace(/\s+/g,'-').replace(/-+/g,'-').slice(0,255);
  }
  function refreshSlug() {
    slugDisp.textContent = slugInp.value || toSlug(titleInp.value) || 'your-job-slug-here';
  }
  titleInp.addEventListener('input', function () {
    if (slugAuto) { slugInp.value = toSlug(this.value); refreshSlug(); }
  });
  slugInp.addEventListener('input', function () {
    slugAuto = false; slugBtn.textContent = 'Manual';
    slugBtn.style.cssText = 'color:var(--amber);border-color:var(--amber);';
    refreshSlug();
  });
  slugBtn.addEventListener('click', function () {
    slugAuto = !slugAuto;
    if (slugAuto) { slugInp.value = toSlug(titleInp.value); this.textContent = 'Auto'; this.style.cssText = ''; }
    else          { this.textContent = 'Manual'; this.style.cssText = 'color:var(--amber);border-color:var(--amber);'; }
    refreshSlug();
  });
  @if(old('slug')) slugAuto = false; slugBtn.textContent = 'Manual'; slugBtn.style.cssText = 'color:var(--amber);border-color:var(--amber);'; @endif
  refreshSlug();

  /* ── Toggle rows ── */
  var remoteChk  = document.getElementById('is_remote');
  var remoteRow  = document.getElementById('remoteRow');
  var featChk    = document.getElementById('featured');
  var featRow    = document.getElementById('featuredRow');
  var featLabel  = document.getElementById('featuredLabel');

  function syncRemote()   { remoteRow.classList.toggle('active-toggle',       remoteChk.checked); }
  function syncFeatured() {
    featRow.classList.toggle('active-toggle-amber', featChk.checked);
    featLabel.style.background = featChk.checked ? 'var(--amber)' : '';
  }
  remoteChk.addEventListener('change', syncRemote);
  featChk.addEventListener('change',   syncFeatured);
  syncRemote(); syncFeatured();

  /* ── Vacancies badge ── */
  var vacInp   = document.getElementById('vacancies');
  var vacBadge = document.getElementById('vacancyBadge');
  function syncVac() {
    var v = parseInt(vacInp.value, 10);
    vacBadge.textContent = (!isNaN(v) && v > 0) ? v + ' open' : 'open';
  }
  vacInp.addEventListener('input', syncVac); syncVac();

  /* ─────────────────────────────────────────
     SKILLS TAGS
  ───────────────────────────────────────── */
  var tagsWrap    = document.getElementById('tagsWrap');
  var skillInput  = document.getElementById('skillTagInput');
  var skillHidden = document.getElementById('skillsHidden');
  var skills      = [];

  (function hydrate() {
    var raw = skillHidden.value.trim();
    if (!raw) return;
    raw.split(',').map(function (s) { return s.trim(); }).filter(Boolean).forEach(function (s) { addTag(s); });
  }());

  function addTag(val) {
    val = val.trim();
    if (!val || skills.indexOf(val) !== -1) return;
    skills.push(val);
    var span = document.createElement('span');
    span.className = 'tag-item';
    span.innerHTML = val + '<button type="button" class="tag-remove">✕</button>';
    span.querySelector('.tag-remove').addEventListener('click', function () {
      skills.splice(skills.indexOf(val), 1); span.remove(); syncSkills();
    });
    tagsWrap.insertBefore(span, skillInput);
    syncSkills();
  }
  function syncSkills() { skillHidden.value = skills.join(','); }

  skillInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ',') {
      e.preventDefault();
      var v = this.value.replace(/,/g,'').trim();
      if (v) { addTag(v); this.value = ''; }
    }
    if (e.key === 'Backspace' && !this.value && skills.length) {
      var items = tagsWrap.querySelectorAll('.tag-item');
      if (items.length) { skills.pop(); items[items.length - 1].remove(); syncSkills(); }
    }
  });
  skillInput.addEventListener('blur', function () {
    var v = this.value.replace(/,/g,'').trim();
    if (v) { addTag(v); this.value = ''; }
  });

  /* ─────────────────────────────────────────
     LIVE PREVIEW
  ───────────────────────────────────────── */
  var typeInp     = document.getElementById('type');
  var deptInp     = document.getElementById('department');
  var locInp      = document.getElementById('location');
  var salInp      = document.getElementById('salary');
  var descInp     = document.getElementById('description');
  var dlInp       = document.getElementById('application_deadline');

  var statusColors = { draft:'#6b7280', active:'#05c48a', closed:'#f04444' };
  var statusLabels = { draft:'Draft', active:'Active', closed:'Closed' };

  function fmtDate(v) {
    if (!v) return '';
    var d = new Date(v + 'T00:00:00');
    return d.toLocaleDateString('en-IN', { day:'numeric', month:'short', year:'numeric' });
  }

  function chip(elId, val, transform) {
    var el = document.getElementById(elId);
    var valEl = document.getElementById(elId + 'Val');
    if (val) { if (valEl) valEl.textContent = transform ? transform(val) : val; el.style.display = 'inline-flex'; }
    else      { el.style.display = 'none'; }
  }

  function updatePreview() {
    var t = titleInp.value.trim();
    var prev = document.getElementById('prevTitle');
    prev.textContent = t || 'Job title will appear here';
    prev.style.color = t ? '' : 'var(--text3)';

    chip('prevType', typeInp.value);
    chip('prevDept', deptInp.value.trim());
    chip('prevLoc',  locInp.value.trim());
    chip('prevSal',  salInp.value.trim());
    var v = parseInt(vacInp.value, 10);
    chip('prevVac', (!isNaN(v) && v > 0) ? v + ' ' + (v === 1 ? 'vacancy' : 'vacancies') : '', null);

    document.getElementById('prevRemote').style.display   = remoteChk.checked ? 'inline-flex' : 'none';
    document.getElementById('prevFeatured').style.display = featChk.checked   ? 'inline-flex' : 'none';
    chip('prevDeadline', dlInp.value, function (v) { return 'Deadline: ' + fmtDate(v); });

    var d = descInp.value.trim();
    var prevDesc = document.getElementById('prevDesc');
    prevDesc.textContent = d ? (d.length > 160 ? d.slice(0,160) + '…' : d) : 'Description preview will appear here…';
    prevDesc.style.color = d ? 'var(--text2)' : '';

    var sv = (document.querySelector('input[name="status"]:checked') || {}).value || 'draft';
    document.getElementById('prevDot').style.background  = statusColors[sv];
    document.getElementById('prevStatus').textContent    = statusLabels[sv];
    document.getElementById('prevStatus').style.color    = statusColors[sv];
  }

  [titleInp, deptInp, locInp, salInp, vacInp, descInp].forEach(function (el) { el.addEventListener('input', updatePreview); });
  [typeInp, remoteChk, featChk, dlInp].forEach(function (el) { el.addEventListener('change', updatePreview); });
  document.querySelectorAll('input[name="status"]').forEach(function (r) { r.addEventListener('change', updatePreview); });
  updatePreview();

  /* ─────────────────────────────────────────
     CHARACTER COUNTERS (shared helper)
  ───────────────────────────────────────── */
  function attachCounter(inputId, counterId, max) {
    var inp = document.getElementById(inputId);
    var cnt = document.getElementById(counterId);
    if (!inp || !cnt) return;
    function update() {
      var len = inp.value.length;
      cnt.textContent = max ? len + ' / ' + max : len + ' chars';
      cnt.className   = 'counter' + (max && len > max * 0.9 ? (len >= max ? ' over' : ' warn') : '');
    }
    inp.addEventListener('input', update); update();
  }
  attachCounter('title',            'titleCounter',    150);
  attachCounter('description',      'descCounter',     null);
  attachCounter('meta_title',       'metaTitleCounter', 70);
  attachCounter('meta_description', 'metaDescCounter', 160);

  /* ─────────────────────────────────────────
     FORM SUBMIT
  ───────────────────────────────────────── */
  var jobForm    = document.getElementById('jobForm');
  var publishBtn = document.getElementById('publishBtn');
  var draftBtn   = document.getElementById('draftBtn');

  jobForm.addEventListener('submit', function (e) {
    var action = (document.activeElement && document.activeElement.name === '_action')
      ? document.activeElement.value : 'publish';

    if (action === 'publish') {
      var ar = document.querySelector('input[name="status"][value="active"]');
      if (ar) ar.checked = true;
    }

    /* ensure slug */
    if (!slugInp.value.trim() && titleInp.value.trim())
      slugInp.value = toSlug(titleInp.value);

    /* flush tag input */
    var raw = skillInput.value.replace(/,/g,'').trim();
    if (raw) { addTag(raw); skillInput.value = ''; }

    /* validation */
    var valid = true;
    [[titleInp, true], [slugInp, true], [typeInp, true], [descInp, true]].forEach(function (pair) {
      var el = pair[0], req = pair[1];
      if (req && !el.value.trim()) { el.classList.add('err'); valid = false; }
      else el.classList.remove('err');
    });

    if (!valid) { e.preventDefault(); toast('Please fill in all required fields.', 'error'); return; }

    publishBtn.disabled = draftBtn.disabled = true;
    publishBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin .7s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Publishing…';
  });

}());
</script>
</body>
</html>