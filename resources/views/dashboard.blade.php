<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard — DonateBazaar</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
/* ════════════════════════════════════════
   DESIGN TOKENS
════════════════════════════════════════ */
:root {
    --bg:           #f0f2f9;
    --surface:      #ffffff;
    --surface2:     #f7f8fd;
    --surface3:     #eef0f9;
    --border:       rgba(0,0,0,.055);
    --border2:      rgba(0,0,0,.09);
    --text:         #0d0f1a;
    --text2:        #4a5268;
    --text3:        #9198b0;
    --sb-bg:        #0c0d1c;
    --sb-txt:       rgba(255,255,255,.60);
    --sb-act:       rgba(99,102,241,.16);
    --accent:       #6366f1;
    --accent2:      #8b5cf6;
    --accent-lt:    rgba(99,102,241,.10);
    --accent-glow:  rgba(99,102,241,.20);
    --green:        #10b981;
    --green-lt:     rgba(16,185,129,.10);
    --yellow:       #f59e0b;
    --yellow-lt:    rgba(245,158,11,.10);
    --red:          #ef4444;
    --red-lt:       rgba(239,68,68,.10);
    --pink:         #ec4899;
    --pink-lt:      rgba(236,72,153,.10);
    --blue:         #3b82f6;
    --blue-lt:      rgba(59,130,246,.10);
    --gray:         #6b7280;
    --gray-lt:      rgba(107,114,128,.10);
    --font:         'DM Sans', sans-serif;
    --mono:         'DM Mono', monospace;
    --r:            16px;
    --r-sm:         10px;
    --r-xs:         7px;
    --sh:           0 1px 4px rgba(0,0,0,.06), 0 4px 20px rgba(0,0,0,.04);
    --sh-md:        0 4px 16px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
    --sh-lg:        0 10px 40px rgba(0,0,0,.12);
    --ease:         .2s ease;
    --sb-w:         260px;
}
[data-theme="dark"] {
    --bg:           #080910;
    --surface:      #111220;
    --surface2:     #181929;
    --surface3:     #1e2033;
    --border:       rgba(255,255,255,.055);
    --border2:      rgba(255,255,255,.09);
    --text:         #eef0ff;
    --text2:        #9ba3c4;
    --text3:        #505878;
    --sb-bg:        #06070f;
    --sb-txt:       rgba(255,255,255,.50);
    --sb-act:       rgba(99,102,241,.20);
    --accent-glow:  rgba(99,102,241,.28);
    --sh:           0 1px 4px rgba(0,0,0,.3), 0 4px 20px rgba(0,0,0,.2);
    --sh-md:        0 4px 16px rgba(0,0,0,.35), 0 1px 4px rgba(0,0,0,.2);
    --sh-lg:        0 10px 40px rgba(0,0,0,.5);
}

/* ════════════════════════════════════════
   RESET
════════════════════════════════════════ */
*,*::before,*::after { box-sizing: border-box; margin: 0; padding: 0; }
html,body { height: 100%; }
body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.55; -webkit-font-smoothing: antialiased; overflow-x: hidden; transition: background var(--ease), color var(--ease); }
a { text-decoration: none; color: inherit; }

/* ════════════════════════════════════════
   LAYOUT
════════════════════════════════════════ */
.shell { display: flex; min-height: 100vh; }

/* ════════════════════════════════════════
   SIDEBAR
════════════════════════════════════════ */
.sidebar {
    width: var(--sb-w); flex-shrink: 0;
    background: var(--sb-bg);
    display: flex; flex-direction: column;
    position: fixed; top: 0; left: 0; bottom: 0;
    z-index: 300; overflow-y: auto; overflow-x: hidden;
    border-right: 1px solid rgba(255,255,255,.03);
    transition: transform .3s cubic-bezier(.4,0,.2,1);
}
.sidebar::-webkit-scrollbar { width: 0; }

.s-logo {
    display: flex; align-items: center; gap: 11px;
    padding: 24px 20px 20px;
    border-bottom: 1px solid rgba(255,255,255,.04);
}
.s-logo-mark {
    width: 38px; height: 38px; border-radius: 11px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    box-shadow: 0 4px 16px rgba(99,102,241,.4);
}
.s-logo-mark svg { width: 19px; height: 19px; color: #fff; }
.s-logo-name { font-size: 16px; font-weight: 700; color: #fff; letter-spacing: -.02em; line-height: 1.2; }
.s-logo-tag  { font-size: 9px; color: rgba(255,255,255,.28); text-transform: uppercase; letter-spacing: .14em; }

.s-user {
    margin: 14px 12px 6px;
    padding: 11px 13px;
    background: rgba(255,255,255,.03);
    border: 1px solid rgba(255,255,255,.06);
    border-radius: var(--r-sm);
    display: flex; align-items: center; gap: 10px;
}
.s-avatar {
    width: 34px; height: 34px; border-radius: 9px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; overflow: hidden;
}
.s-avatar img { width: 100%; height: 100%; object-fit: cover; }
.s-user-name { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,.88); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.s-user-role { font-size: 10px; color: rgba(255,255,255,.30); margin-top: 1px; }
.s-user-dot  { width: 7px; height: 7px; border-radius: 50%; background: var(--green); margin-left: auto; flex-shrink: 0; box-shadow: 0 0 0 2px rgba(16,185,129,.2); }

/* ── KYC Banner ── */
.kyc-banner {
    margin: 8px 12px;
    padding: 10px 12px;
    border-radius: var(--r-sm);
    display: flex; align-items: flex-start; gap: 9px;
    font-size: 11.5px; line-height: 1.4;
}
.kyc-banner svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; }
.kyc-banner-title { font-weight: 700; font-size: 11px; margin-bottom: 2px; }
.kyc-banner a { font-weight: 600; text-decoration: underline; }
.kyc-warn  { background: rgba(245,158,11,.12); border: 1px solid rgba(245,158,11,.22); color: #fbbf24; }
.kyc-warn a { color: #fbbf24; }
.kyc-info  { background: rgba(99,102,241,.10); border: 1px solid rgba(99,102,241,.20); color: #a5b4fc; }
.kyc-info a { color: #a5b4fc; }
.kyc-ok    { background: rgba(16,185,129,.10); border: 1px solid rgba(16,185,129,.20); color: #6ee7b7; }
.kyc-error { background: rgba(239,68,68,.10); border: 1px solid rgba(239,68,68,.20); color: #f87171; }
.kyc-error a { color: #f87171; }

.s-label {
    font-size: 9px; font-weight: 700;
    color: rgba(255,255,255,.20);
    text-transform: uppercase; letter-spacing: .16em;
    padding: 18px 20px 6px; font-family: var(--mono);
}
.s-nav { padding: 2px 10px; }
.s-link {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 12px; border-radius: var(--r-xs);
    color: var(--sb-txt); font-size: 13px; font-weight: 500;
    text-decoration: none; transition: background var(--ease), color var(--ease);
    margin-bottom: 1px; border: none; background: transparent;
    width: 100%; text-align: left; cursor: pointer; position: relative;
    font-family: var(--font);
}
.s-link:hover  { background: rgba(255,255,255,.05); color: rgba(255,255,255,.88); }
.s-link.active { background: var(--sb-act); color: #a5b4fc; }
.s-link.active::before {
    content: ''; position: absolute;
    left: 0; top: 25%; bottom: 25%;
    width: 3px; border-radius: 0 3px 3px 0;
    background: var(--accent);
}
.s-icon { width: 15px; height: 15px; flex-shrink: 0; opacity: .75; }
.s-link.active .s-icon { opacity: 1; }
.s-badge {
    margin-left: auto; font-size: 10px; font-weight: 700;
    padding: 2px 7px; border-radius: 100px;
    background: rgba(99,102,241,.22); color: #a5b4fc;
    font-family: var(--mono);
}
.s-badge.ok   { background: rgba(16,185,129,.18);  color: #34d399; }
.s-badge.warn { background: rgba(245,158,11,.18);  color: #fbbf24; }
.s-badge.info { background: rgba(59,130,246,.18);  color: #60a5fa; }
.s-badge.err  { background: rgba(239,68,68,.18);   color: #f87171; }

.s-sub { padding: 2px 10px 2px 28px; }
.s-sub-link {
    display: flex; align-items: center; gap: 8px;
    padding: 6px 10px; border-radius: var(--r-xs);
    color: rgba(255,255,255,.35); font-size: 12px; font-weight: 500;
    text-decoration: none; transition: all var(--ease); margin-bottom: 1px;
}
.s-sub-link:hover { background: rgba(255,255,255,.04); color: rgba(255,255,255,.75); }
.s-sub-dot { width: 4px; height: 4px; border-radius: 50%; background: currentColor; flex-shrink: 0; opacity: .6; }
.s-divider { height: 1px; background: rgba(255,255,255,.04); margin: 10px 16px; }
.s-bottom { margin-top: auto; padding: 10px 10px 18px; border-top: 1px solid rgba(255,255,255,.04); }

/* ════════════════════════════════════════
   MAIN
════════════════════════════════════════ */
.main { margin-left: var(--sb-w); flex: 1; min-width: 0; display: flex; flex-direction: column; min-height: 100vh; }

/* ════════════════════════════════════════
   TOPBAR
════════════════════════════════════════ */
.topbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 28px; height: 60px;
    background: var(--surface); border-bottom: 1px solid var(--border);
    position: sticky; top: 0; z-index: 100; gap: 14px;
}
.topbar-left h1 { font-size: 16px; font-weight: 700; color: var(--text); letter-spacing: -.02em; }
.topbar-left p  { font-size: 11px; color: var(--text3); margin-top: 1px; }
.topbar-right   { display: flex; align-items: center; gap: 8px; }

.search-wrap { position: relative; }
.search-wrap svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); width: 13px; height: 13px; color: var(--text3); pointer-events: none; }
.search-input {
    width: 220px; height: 34px;
    background: var(--surface2); border: 1px solid var(--border2); border-radius: var(--r-sm);
    padding: 0 12px 0 32px; font-size: 12.5px; color: var(--text);
    font-family: var(--font); outline: none;
    transition: border-color var(--ease), box-shadow var(--ease), width var(--ease);
}
.search-input::placeholder { color: var(--text3); }
.search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); width: 260px; }

/* Sort select — added, was missing */
.sort-sel {
    height: 34px; padding: 0 10px;
    border: 1px solid var(--border2); border-radius: var(--r-sm);
    font-size: 12.5px; color: var(--text); font-family: var(--font);
    background: var(--surface2); outline: none; cursor: pointer;
    transition: border-color var(--ease);
}
.sort-sel:focus { border-color: var(--accent); }

.tb-btn {
    width: 34px; height: 34px; border-radius: var(--r-sm);
    border: 1px solid var(--border2); background: var(--surface2);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--text2); flex-shrink: 0;
    transition: all var(--ease); position: relative;
}
.tb-btn:hover { background: var(--accent-lt); color: var(--accent); border-color: var(--accent); }
.tb-btn svg { width: 14px; height: 14px; }
.notif-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--red); position: absolute; top: 6px; right: 6px; border: 1.5px solid var(--surface); }

.theme-toggle { position: relative; }
.theme-toggle input { position: absolute; opacity: 0; width: 0; height: 0; }
.theme-toggle label {
    display: flex; align-items: center; justify-content: space-between;
    width: 50px; height: 26px; border-radius: 100px;
    background: var(--surface2); border: 1px solid var(--border2);
    cursor: pointer; padding: 3px; position: relative;
}
.theme-toggle label::after {
    content: ''; width: 18px; height: 18px; border-radius: 50%;
    background: var(--accent); position: absolute; left: 4px;
    transition: transform .3s cubic-bezier(.4,0,.2,1);
    box-shadow: 0 2px 6px rgba(99,102,241,.35);
}
.theme-toggle input:checked + label::after { transform: translateX(23px); }
.theme-icons { display: flex; justify-content: space-between; width: 100%; position: relative; z-index: 1; padding: 0 2px; }
.theme-icons svg { width: 11px; height: 11px; color: var(--text3); }

/* Avatar dropdown — added, was missing */
.av-wrap { position: relative; }
.t-avatar {
    width: 34px; height: 34px; border-radius: var(--r-sm);
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0; overflow: hidden;
    box-shadow: 0 2px 8px rgba(99,102,241,.35);
}
.t-avatar img { width: 100%; height: 100%; object-fit: cover; }
.av-dd {
    position: absolute; top: calc(100% + 10px); right: 0;
    background: var(--surface); border: 1px solid var(--border2);
    border-radius: var(--r); box-shadow: var(--sh-lg);
    min-width: 210px; z-index: 9999;
    display: none; animation: ddIn .18s ease;
}
.av-dd.open { display: block; }
@keyframes ddIn { from { opacity:0; transform:translateY(-6px) scale(.97); } to { opacity:1; transform:none; } }
.dd-hdr { padding: 14px 16px; border-bottom: 1px solid var(--border); }
.dd-name  { font-size: 13.5px; font-weight: 700; color: var(--text); }
.dd-email { font-size: 11px; color: var(--text3); margin-top: 2px; font-family: var(--mono); }
.dd-item  {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 16px; font-size: 12.5px; font-weight: 500;
    color: var(--text2); cursor: pointer; transition: background var(--ease);
    text-decoration: none;
}
.dd-item:hover  { background: var(--surface2); color: var(--text); }
.dd-item svg    { width: 13px; height: 13px; color: var(--text3); flex-shrink: 0; }
.dd-item.accent { color: var(--accent); } .dd-item.accent svg { color: var(--accent); }
.dd-item.danger { color: var(--red); }   .dd-item.danger svg { color: var(--red); }
.dd-sep { height: 1px; background: var(--border); margin: 3px 0; }

.create-btn {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--accent); color: #fff;
    padding: 7px 14px; border-radius: var(--r-sm);
    font-size: 12.5px; font-weight: 600; text-decoration: none; white-space: nowrap;
    transition: opacity var(--ease), transform var(--ease); font-family: var(--font);
    box-shadow: 0 3px 12px rgba(99,102,241,.35);
}
.create-btn:hover { opacity: .88; transform: translateY(-1px); }
.create-btn svg { width: 13px; height: 13px; }

.hamburger {
    display: none; width: 34px; height: 34px; border-radius: var(--r-sm);
    border: 1px solid var(--border2); background: var(--surface2); cursor: pointer;
    color: var(--text2); align-items: center; justify-content: center; flex-shrink: 0;
}
.hamburger svg { width: 15px; height: 15px; }

/* ════════════════════════════════════════
   PAGE BODY
════════════════════════════════════════ */
.body { padding: 24px 28px 56px; flex: 1; }

/* ════════════════════════════════════════
   WELCOME BANNER
════════════════════════════════════════ */
.welcome-banner {
    background:
        radial-gradient(ellipse at 70% 10%, rgba(124,109,250,.25) 0%, transparent 55%),
        radial-gradient(ellipse at 20% 90%, rgba(155,89,245,.18) 0%, transparent 50%),
        linear-gradient(160deg, #0d0e1a 0%, #13122b 45%, #1a1040 100%);
    border-radius: 20px; padding: 26px 30px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; position: relative; overflow: hidden;
    box-shadow: 0 10px 50px rgba(0,0,0,.45);
    animation: fadeUp .4s ease both;
}
.welcome-banner::before {
    content: ''; position: absolute;
    width: 220px; height: 220px;
    background: radial-gradient(circle, rgba(139,92,246,.35), transparent 70%);
    top: -60px; right: -60px; filter: blur(40px);
}
.welcome-banner::after {
    content: ''; position: absolute;
    width: 180px; height: 180px;
    background: radial-gradient(circle, rgba(99,102,241,.25), transparent 70%);
    bottom: -50px; left: -50px; filter: blur(35px);
}
.wb-left { position: relative; z-index: 1; }
.wb-tag  {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 10px; font-weight: 600; color: rgba(255,255,255,.55);
    text-transform: uppercase; letter-spacing: .1em; margin-bottom: 8px;
    font-family: var(--mono);
}
.wb-tag-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green); animation: pulse 2s ease infinite; }
@keyframes pulse { 0%,100% { box-shadow: 0 0 0 0 rgba(16,185,129,.5); } 50% { box-shadow: 0 0 0 6px rgba(16,185,129,0); } }
.wb-name { font-size: 24px; font-weight: 700; color: #fff; letter-spacing: -.02em; line-height: 1.2; margin-bottom: 4px; }
.wb-sub  { font-size: 13px; color: rgba(255,255,255,.58); margin-bottom: 14px; }
.wb-badges { display: flex; gap: 8px; flex-wrap: wrap; }
.wb-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 100px;
    font-size: 11px; font-weight: 600; font-family: var(--mono);
}
.wbb-green  { background: rgba(16,185,129,.2);  color: #6ee7b7; border: 1px solid rgba(16,185,129,.3); }
.wbb-yellow { background: rgba(245,158,11,.2);  color: #fde68a; border: 1px solid rgba(245,158,11,.3); }
.wbb-purple { background: rgba(99,102,241,.2);  color: #c4b5fd; border: 1px solid rgba(99,102,241,.3); }
.wbb-red    { background: rgba(239,68,68,.2);   color: #fca5a5; border: 1px solid rgba(239,68,68,.3); }
.wb-right { position: relative; z-index: 1; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.wb-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: var(--r-sm);
    font-size: 13px; font-weight: 600; text-decoration: none;
    font-family: var(--font); border: none; cursor: pointer;
    transition: all var(--ease);
}
.wb-btn svg { width: 14px; height: 14px; }
.wb-btn-primary { background: linear-gradient(135deg, var(--accent), var(--accent2)); color: #fff; box-shadow: 0 4px 20px rgba(99,102,241,.45); }
.wb-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(99,102,241,.55); }
.wb-btn-ghost { background: rgba(255,255,255,.1); color: rgba(255,255,255,.85); border: 1px solid rgba(255,255,255,.15); }
.wb-btn-ghost:hover { background: rgba(255,255,255,.18); transform: translateY(-2px); }
.wb-btn-yellow { background: rgba(245,158,11,.2); color: #fde68a; border: 1px solid rgba(245,158,11,.3); }
.wb-btn-yellow:hover { background: rgba(245,158,11,.35); transform: translateY(-2px); }

/* ════════════════════════════════════════
   STATS GRID  (5 cards like admin)
════════════════════════════════════════ */
.stats-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; margin-bottom: 22px; }
.stat-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--r);
    padding: 18px 20px; box-shadow: var(--sh);
    display: flex; align-items: flex-start; gap: 14px;
    transition: transform var(--ease), box-shadow var(--ease);
    animation: fadeUp .4s ease both; cursor: default;
    position: relative; overflow: hidden;
}
.stat-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2.5px; border-radius: 0 0 var(--r) var(--r); opacity: 0; transition: opacity var(--ease); }
.stat-card:hover { transform: translateY(-3px); box-shadow: var(--sh-md); }
.stat-card:hover::after { opacity: 1; }
.stat-card:nth-child(1){ animation-delay:.05s } .stat-card:nth-child(1)::after { background: var(--accent); }
.stat-card:nth-child(2){ animation-delay:.10s } .stat-card:nth-child(2)::after { background: var(--pink); }
.stat-card:nth-child(3){ animation-delay:.15s } .stat-card:nth-child(3)::after { background: var(--green); }
.stat-card:nth-child(4){ animation-delay:.20s } .stat-card:nth-child(4)::after { background: var(--yellow); }
.stat-card:nth-child(5){ animation-delay:.25s } .stat-card:nth-child(5)::after { background: linear-gradient(90deg,var(--blue),#6366f1); }

.stat-icon-wrap { width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-icon-wrap svg { width: 18px; height: 18px; }
.si-indigo { background: var(--accent-lt); color: var(--accent); }
.si-pink   { background: var(--pink-lt);   color: var(--pink); }
.si-green  { background: var(--green-lt);  color: var(--green); }
.si-yellow { background: var(--yellow-lt); color: var(--yellow); }
.si-blue   { background: var(--blue-lt);   color: var(--blue); }

.stat-info { flex: 1; min-width: 0; }
.stat-label { font-size: 10.5px; font-weight: 600; color: var(--text3); text-transform: uppercase; letter-spacing: .07em; font-family: var(--mono); margin-bottom: 6px; }
.stat-val   { font-size: 1.75rem; font-weight: 700; line-height: 1; letter-spacing: -.03em; }
.stat-foot  { font-size: 11px; color: var(--text3); margin-top: 5px; }
.stat-foot a { color: var(--green); font-weight: 600; }
.stat-foot a:hover { text-decoration: underline; }
.sv-indigo { color: var(--accent); }
.sv-pink   { color: var(--pink); }
.sv-green  { color: var(--green); }
.sv-yellow { color: var(--yellow); }
.sv-blue   { color: var(--blue); }

/* ════════════════════════════════════════
   ANALYTICS ROW
════════════════════════════════════════ */
.analytics-row { display: grid; grid-template-columns: 1fr 280px; gap: 14px; margin-bottom: 22px; }
.chart-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--r); padding: 22px;
    box-shadow: var(--sh); animation: fadeUp .4s .22s ease both;
}
.chart-card-hdr { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; gap: 10px; }
.chart-title { font-size: 14px; font-weight: 700; color: var(--text); letter-spacing: -.01em; }
.chart-sub   { font-size: 11px; color: var(--text3); margin-top: 2px; }
.chart-legend { display: flex; align-items: center; gap: 5px; font-size: 11px; color: var(--text3); font-family: var(--mono); white-space: nowrap; }
.chart-legend-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--accent); flex-shrink: 0; }
.chart-wrap { position: relative; height: 180px; }

.qs-panel {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--r); padding: 22px;
    box-shadow: var(--sh); display: flex; flex-direction: column; gap: 6px;
    animation: fadeUp .4s .28s ease both;
}
.qs-title { font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 14px; letter-spacing: -.01em; }
.qs-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 12px; border-radius: var(--r-sm);
    background: var(--surface2); border: 1px solid var(--border);
    transition: background var(--ease), transform var(--ease); cursor: pointer;
}
.qs-row:hover { background: var(--surface3); transform: translateX(3px); }
.qs-row-left { display: flex; align-items: center; gap: 9px; }
.qs-dot   { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.qs-label { font-size: 12.5px; font-weight: 500; color: var(--text2); }
.qs-val   { font-size: 14px; font-weight: 700; color: var(--text); font-family: var(--mono); }
.qs-progress { margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--border); }
.qs-prog-label { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 7px; }
.qs-prog-label span:first-child { color: var(--text3); }
.qs-prog-label span:last-child  { font-weight: 700; color: var(--accent); font-family: var(--mono); }
.qs-prog-bar  { height: 6px; background: var(--surface3); border-radius: 100px; overflow: hidden; }
.qs-prog-fill { height: 100%; border-radius: 100px; background: linear-gradient(90deg, var(--accent), var(--accent2)); transition: width 1.2s cubic-bezier(.4,0,.2,1); }

/* ════════════════════════════════════════
   QUICK NAV  (was missing entirely)
════════════════════════════════════════ */
.qnav { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; margin-bottom: 24px; }
.qnav-card {
    display: flex; flex-direction: column; align-items: center; gap: 10px;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--r); padding: 18px 10px; text-align: center;
    transition: transform var(--ease), box-shadow var(--ease), border-color var(--ease);
    box-shadow: var(--sh); animation: fadeUp .4s ease both;
    cursor: pointer; text-decoration: none;
    position: relative; overflow: hidden;
}
.qnav-card::before { content: ''; position: absolute; inset: 0; opacity: 0; background: radial-gradient(ellipse at 50% 0%, var(--qc, rgba(99,102,241,.08)) 0%, transparent 70%); transition: opacity var(--ease); }
.qnav-card:hover { transform: translateY(-4px); box-shadow: var(--sh-md); border-color: rgba(99,102,241,.2); }
.qnav-card:hover::before { opacity: 1; }
.qnav-ico { width: 40px; height: 40px; border-radius: 11px; display: flex; align-items: center; justify-content: center; transition: transform var(--ease); }
.qnav-card:hover .qnav-ico { transform: scale(1.12); }
.qnav-ico svg { width: 18px; height: 18px; }
.qnav-lbl { font-size: 12px; font-weight: 600; color: var(--text); }
.qnav-sub { font-size: 10px; color: var(--text3); }

/* ════════════════════════════════════════
   SECTION HEADER
════════════════════════════════════════ */
.sec-hdr   { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 12px; }
.sec-title { font-size: 16px; font-weight: 700; color: var(--text); letter-spacing: -.02em; }
.sec-right { display: flex; align-items: center; gap: 8px; }

.ftabs { display: flex; gap: 2px; background: var(--surface2); border: 1px solid var(--border); padding: 3px; border-radius: 12px; flex-wrap: wrap; }
.ftab {
    padding: 5px 12px; border-radius: 9px;
    font-size: 12px; font-weight: 500; cursor: pointer;
    border: none; background: transparent; color: var(--text3);
    transition: all var(--ease); display: inline-flex; align-items: center; gap: 5px;
    font-family: var(--font); white-space: nowrap;
}
.ftab:hover { color: var(--accent); }
.ftab.on { background: var(--surface); color: var(--accent); font-weight: 600; box-shadow: 0 1px 6px rgba(99,102,241,.12); }
.ftab .cnt { display: inline-flex; align-items: center; justify-content: center; min-width: 16px; height: 16px; border-radius: 100px; font-size: 10px; padding: 0 4px; background: var(--accent-lt); color: var(--accent); font-weight: 700; font-family: var(--mono); }

.view-toggle { display: flex; gap: 2px; background: var(--surface2); border: 1px solid var(--border); padding: 3px; border-radius: var(--r-sm); }
.vt-btn { width: 28px; height: 28px; border-radius: 6px; border: none; background: transparent; cursor: pointer; color: var(--text3); display: flex; align-items: center; justify-content: center; transition: all var(--ease); }
.vt-btn.on { background: var(--surface); color: var(--accent); box-shadow: 0 1px 4px rgba(0,0,0,.06); }
.vt-btn svg { width: 13px; height: 13px; }

/* ════════════════════════════════════════
   BADGES
════════════════════════════════════════ */
.badge { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: .06em; font-family: var(--mono); }
.b-active   { background: rgba(16,185,129,.85);  color: #fff; }
.b-pending  { background: rgba(245,158,11,.85);  color: #fff; }
.b-rejected { background: rgba(239,68,68,.85);   color: #fff; }
.b-paused   { background: rgba(99,102,241,.85);  color: #fff; }
.b-expired  { background: rgba(107,114,128,.75); color: #fff; }
.b-inactive { background: rgba(59,130,246,.75);  color: #fff; }
.b-default  { background: rgba(107,114,128,.7);  color: #fff; }

/* ════════════════════════════════════════
   CAMPAIGN CARDS — GRID VIEW
════════════════════════════════════════ */
.c-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr)); gap: 16px; }
.c-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--r);
    overflow: hidden; display: flex; flex-direction: column;
    box-shadow: var(--sh); transition: transform var(--ease), box-shadow var(--ease), border-color var(--ease);
    animation: fadeUp .4s ease both;
}
.c-card:hover { transform: translateY(-4px); box-shadow: var(--sh-md); border-color: rgba(99,102,241,.18); }

.c-thumb { position: relative; flex-shrink: 0; }
.c-thumb img { width: 100%; height: 165px; object-fit: cover; display: block; }
.c-thumb-placeholder { width: 100%; height: 165px; display: flex; align-items: center; justify-content: center; background: var(--surface2); }
.c-thumb-placeholder svg { width: 32px; height: 32px; color: var(--text3); opacity: .4; }
.c-thumb-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,.45) 0%, transparent 50%); }
.c-badge-wrap { position: absolute; top: 10px; left: 10px; }

/* Reason banners */
.reason { padding: 8px 10px; border-radius: 8px; margin-bottom: 11px; border: 1px solid transparent; }
.reason-y { background: var(--yellow-lt); border-color: rgba(245,158,11,.2); }
.reason-r { background: var(--red-lt);    border-color: rgba(239,68,68,.2); }
.reason-b { background: var(--blue-lt);   border-color: rgba(59,130,246,.2); }
.reason-g { background: var(--gray-lt);   border-color: rgba(107,114,128,.2); }
.reason-lbl { font-size: 10px; font-weight: 700; margin-bottom: 2px; font-family: var(--mono); }
.reason-y .reason-lbl { color: #b45309; } .reason-r .reason-lbl { color: #b91c1c; }
.reason-b .reason-lbl { color: #1d4ed8; } .reason-g .reason-lbl { color: #374151; }
.reason-txt { font-size: 11.5px; line-height: 1.4; }
.reason-y .reason-txt { color: #92400e; } .reason-r .reason-txt { color: #991b1b; }
.reason-b .reason-txt { color: #1e40af; } .reason-g .reason-txt { color: #4b5563; }
[data-theme="dark"] .reason-y .reason-lbl { color: #fbbf24; } [data-theme="dark"] .reason-y .reason-txt { color: #fde68a; }
[data-theme="dark"] .reason-r .reason-lbl { color: #fca5a5; } [data-theme="dark"] .reason-r .reason-txt { color: #fecaca; }
[data-theme="dark"] .reason-b .reason-lbl { color: #60a5fa; } [data-theme="dark"] .reason-b .reason-txt { color: #bfdbfe; }
[data-theme="dark"] .reason-g .reason-lbl { color: #9ca3af; } [data-theme="dark"] .reason-g .reason-txt { color: #d1d5db; }

.c-body { padding: 15px 16px; flex: 1; display: flex; flex-direction: column; }
.c-title { font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.45; }

.prog-wrap { margin-bottom: 13px; }
.prog-numbers { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 7px; }
.prog-raised { font-size: 15px; font-weight: 700; color: var(--text); letter-spacing: -.02em; }
.prog-goal   { font-size: 11px; color: var(--text3); font-family: var(--mono); }
.prog-bar    { width: 100%; background: var(--surface2); border-radius: 100px; height: 5px; overflow: hidden; margin-bottom: 4px; }
.prog-fill   { height: 100%; border-radius: 100px; background: linear-gradient(90deg, var(--accent), var(--accent2)); transition: width .8s ease; }
.prog-meta   { display: flex; justify-content: space-between; font-size: 10.5px; font-family: var(--mono); }
.prog-pct    { color: var(--accent); font-weight: 600; }

/* Campaign action buttons */
.c-actions { display: flex; gap: 7px; margin-top: auto; }
.c-btn {
    flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 5px;
    padding: 8px 10px; border-radius: var(--r-sm);
    font-size: 12px; font-weight: 600; cursor: pointer; border: 1px solid transparent;
    transition: all var(--ease); text-decoration: none; font-family: var(--font); white-space: nowrap;
}
.c-btn:active { transform: scale(.96); }
.c-btn svg { width: 12px; height: 12px; }
.c-btn-view   { background: var(--accent-lt); color: var(--accent); border-color: rgba(99,102,241,.18); }
.c-btn-view:hover   { background: var(--accent); color: #fff; border-color: var(--accent); transform: translateY(-1px); }
.c-btn-edit   { background: var(--surface2); color: var(--text2); border-color: var(--border2); }
.c-btn-edit:hover   { background: var(--surface3); color: var(--text); transform: translateY(-1px); }
/* Pause / Resume — were missing */
.c-btn-pause  { background: var(--yellow-lt); color: var(--yellow); border-color: rgba(245,158,11,.2); }
.c-btn-pause:hover  { background: var(--yellow); color: #fff; border-color: var(--yellow); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(245,158,11,.3); }
.c-btn-resume { background: var(--green-lt); color: var(--green); border-color: rgba(16,185,129,.2); }
.c-btn-resume:hover { background: var(--green); color: #fff; border-color: var(--green); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(16,185,129,.3); }

/* ════════════════════════════════════════
   CAMPAIGN CARDS — LIST VIEW
════════════════════════════════════════ */
.c-list { display: flex; flex-direction: column; gap: 8px; }
.c-list-item {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-sm);
    padding: 14px 18px; display: flex; align-items: center; gap: 14px;
    box-shadow: 0 1px 2px rgba(0,0,0,.05);
    transition: border-color var(--ease), box-shadow var(--ease);
    animation: fadeUp .3s ease both;
}
.c-list-item:hover { border-color: rgba(99,102,241,.18); box-shadow: var(--sh); }
.c-list-thumb { width: 52px; height: 40px; border-radius: 7px; overflow: hidden; flex-shrink: 0; background: var(--surface2); display: flex; align-items: center; justify-content: center; }
.c-list-thumb img { width: 100%; height: 100%; object-fit: cover; }
.c-list-thumb svg { width: 18px; height: 18px; color: var(--text3); opacity: .4; }
.c-list-info { flex: 1; min-width: 0; }
.c-list-title { font-size: 13.5px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px; }
.c-list-sub   { font-size: 11.5px; color: var(--text3); display: flex; align-items: center; gap: 6px; }
.c-list-dot   { width: 3px; height: 3px; border-radius: 50%; background: var(--text3); }
.c-list-prog  { width: 120px; flex-shrink: 0; }
.c-list-pct   { font-size: 12px; font-weight: 700; color: var(--accent); font-family: var(--mono); margin-bottom: 4px; }
.c-list-bar   { height: 4px; background: var(--surface2); border-radius: 100px; overflow: hidden; }
.c-list-fill  { height: 100%; border-radius: 100px; background: linear-gradient(90deg, var(--accent), var(--accent2)); }
.c-list-badge { flex-shrink: 0; }
.c-list-actions { display: flex; gap: 6px; flex-shrink: 0; }
.c-list-btn {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 5px 11px; border-radius: 7px;
    font-size: 11.5px; font-weight: 500; cursor: pointer;
    border: 1px solid var(--border2); background: var(--surface2); color: var(--text2);
    transition: all var(--ease); text-decoration: none; font-family: var(--font);
}
.c-list-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-lt); }
.c-list-btn svg { width: 11px; height: 11px; }

/* ════════════════════════════════════════
   RECURRING DONATIONS SECTION (new)
════════════════════════════════════════ */
.rec-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--r);
    padding: 20px; box-shadow: var(--sh);
    display: flex; align-items: center; gap: 16px;
    transition: transform var(--ease), box-shadow var(--ease);
    animation: fadeUp .4s ease both;
}
.rec-card:hover { transform: translateY(-2px); box-shadow: var(--sh-md); }
.rec-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.rec-icon svg { width: 19px; height: 19px; }
.rec-info { flex: 1; min-width: 0; }
.rec-title { font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.rec-sub   { font-size: 12px; color: var(--text3); font-family: var(--mono); }
.rec-amount { text-align: right; flex-shrink: 0; }
.rec-amt-val { font-size: 16px; font-weight: 700; color: var(--accent); font-family: var(--mono); display: block; }
.rec-amt-freq { font-size: 10px; color: var(--text3); font-family: var(--mono); }
.rec-actions { display: flex; gap: 6px; flex-shrink: 0; }
.rec-btn { display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; border-radius: 7px; font-size: 11px; font-weight: 500; cursor: pointer; border: 1px solid var(--border2); background: var(--surface2); color: var(--text2); transition: all var(--ease); font-family: var(--font); }
.rec-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-lt); }
.rec-btn svg { width: 11px; height: 11px; }

/* ════════════════════════════════════════
   EMPTY STATE
════════════════════════════════════════ */
.empty-state { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); padding: 56px 24px; text-align: center; box-shadow: var(--sh); animation: fadeUp .4s ease both; }
.empty-icon  { width: 60px; height: 60px; border-radius: var(--r); background: var(--accent-lt); border: 1px solid rgba(99,102,241,.15); display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; }
.empty-icon svg { width: 26px; height: 26px; color: var(--accent); }
.empty-title { font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 7px; }
.empty-sub   { font-size: 13px; color: var(--text3); max-width: 280px; margin: 0 auto 22px; line-height: 1.6; }
.empty-btn   { display: inline-flex; align-items: center; gap: 6px; background: var(--accent); color: #fff; padding: 10px 22px; border-radius: var(--r-sm); font-size: 13px; font-weight: 600; text-decoration: none; box-shadow: 0 4px 14px rgba(99,102,241,.3); transition: opacity var(--ease), transform var(--ease); }
.empty-btn:hover { opacity: .88; transform: translateY(-1px); }
.empty-btn svg { width: 14px; height: 14px; }

#noResults { display: none; text-align: center; padding: 32px 20px; color: var(--text3); font-size: 13px; }

/* ════════════════════════════════════════
   TOAST
════════════════════════════════════════ */
.toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; pointer-events: none; }
.toast { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 500; color: #fff; min-width: 260px; box-shadow: var(--sh-lg); pointer-events: all; animation: toastIn .3s ease both; }
.toast svg { width: 15px; height: 15px; flex-shrink: 0; }
.toast-success { background: linear-gradient(135deg, #059669, #10b981); }
.toast-error   { background: linear-gradient(135deg, #dc2626, #ef4444); }
.toast-close   { margin-left: auto; width: 18px; height: 18px; border-radius: 4px; background: rgba(255,255,255,.2); border: none; cursor: pointer; color: #fff; font-size: 11px; display: flex; align-items: center; justify-content: center; }

@keyframes fadeUp  { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
@keyframes toastIn { from { opacity:0; transform:translateX(16px) scale(.96); } to { opacity:1; transform:none; } }

/* ════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════ */
@media (max-width: 1280px) { .stats-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 1100px) { .analytics-row { grid-template-columns: 1fr; } .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 860px)  { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } .main { margin-left: 0; } .hamburger { display: flex; } .search-wrap, .sort-sel { display: none; } .welcome-banner { flex-direction: column; align-items: flex-start; } }
@media (max-width: 600px)  { .topbar { padding: 0 16px; } .body { padding: 14px 14px 48px; } .stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; } .c-grid { grid-template-columns: 1fr; } .stat-val { font-size: 1.4rem; } .qnav { grid-template-columns: repeat(2, 1fr); } }
</style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<div class="shell">

{{-- ══════════════════════════════════════════
     SIDEBAR
══════════════════════════════════════════ --}}
<aside class="sidebar" id="sidebar">

    <div class="s-logo">
        <div class="s-logo-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div>
            <div class="s-logo-name">DonateBazaar</div>
            <div class="s-logo-tag">My Portal</div>
        </div>
    </div>

    <div class="s-user">
        <div class="s-avatar">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
            @else
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            @endif
        </div>
        <div style="flex:1;overflow:hidden;">
            <div class="s-user-name">{{ auth()->user()->name ?? 'User' }}</div>
            <div class="s-user-role">Fundraiser</div>
        </div>
        <div class="s-user-dot"></div>
    </div>

    {{-- ── KYC Status Banner ── --}}
    @php $kyc = auth()->user()->kycVerification; @endphp
    @if(!$kyc)
        <div class="kyc-banner kyc-warn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <div>
                <div class="kyc-banner-title">KYC Required</div>
                Submit documents so campaigns go live.
                <br><a href="{{ url('/user/kyc') }}">Submit KYC →</a>
            </div>
        </div>
    @elseif($kyc->status === 'pending')
        <div class="kyc-banner kyc-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <div>
                <div class="kyc-banner-title">KYC Under Review</div>
                Campaigns live once KYC is approved.
            </div>
        </div>
    @elseif($kyc->status === 'approved')
        <div class="kyc-banner kyc-ok">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><div class="kyc-banner-title">KYC Verified ✓</div>Your account is fully verified.</div>
        </div>
    @elseif($kyc->status === 'rejected')
        <div class="kyc-banner kyc-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <div>
                <div class="kyc-banner-title">KYC Rejected</div>
                Re-submit correct documents.
                <br><a href="{{ url('/user/kyc') }}">Re-submit →</a>
            </div>
        </div>
    @endif

    <div class="s-label">Overview</div>
    <nav class="s-nav">
        <a href="{{ url('/user/dashboard') }}" class="s-link {{ request()->is('user/dashboard') && !request()->is('user/dashboard/*') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="{{ route('profile.show') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            My Profile
        </a>
    </nav>

    <div class="s-label">Campaigns</div>
    @php
        $countAll      = $campaigns->count();
        $countActive   = $campaigns->where('campaign_state','active')->count();
        $countInactive = $campaigns->where('campaign_state','inactive')->count();
        $countPending  = $campaigns->where('campaign_state','pending')->count();
        $countPaused   = $campaigns->where('campaign_state','paused')->count();
        $countRejected = $campaigns->where('campaign_state','rejected')->count();
        $countExpired  = $campaigns->where('campaign_state','expired')->count();
    @endphp
    <nav class="s-nav">
        <a href="{{ route('campaign.create') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Campaign
        </a>
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            All Campaigns
            @if($countAll > 0)<span class="s-badge">{{ $countAll }}</span>@endif
        </a>
        @if($countActive > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" onclick="setFilter('active')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Active
            <span class="s-badge ok">{{ $countActive }}</span>
        </a>
        @endif
        @if($countPending > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" onclick="setFilter('pending')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Pending
            <span class="s-badge warn">{{ $countPending }}</span>
        </a>
        @endif
        @if($countPaused > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" onclick="setFilter('paused')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Paused
            <span class="s-badge">{{ $countPaused }}</span>
        </a>
        @endif
        @if($countRejected > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" onclick="setFilter('rejected')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Rejected
            <span class="s-badge err">{{ $countRejected }}</span>
        </a>
        @endif
        @if($countExpired > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link" onclick="setFilter('expired')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Expired
            <span class="s-badge">{{ $countExpired }}</span>
        </a>
        @endif
    </nav>

    {{-- ── KYC Navigation ── --}}
    <div class="s-label">Identity & KYC</div>
    <nav class="s-nav">
        @if(!$kyc || $kyc->status === 'rejected')
        <a href="{{ url('/user/kyc') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Submit KYC
            <span class="s-badge warn">Action needed</span>
        </a>
        @else
        <a href="{{ url('/user/kyc') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            KYC Status
            <span class="s-badge {{ $kyc->status === 'approved' ? 'ok' : 'warn' }}">{{ ucfirst($kyc->status) }}</span>
        </a>
        @endif
    </nav>

    {{-- ── Recurring Donations ── --}}
    <div class="s-label">Donations</div>
    <nav class="s-nav">
        <a href="{{ route('recurring.index') }}" class="s-link {{ request()->is('my-recurring-donations') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Recurring Donations
            @if(isset($recurringCount) && $recurringCount > 0)<span class="s-badge ok">{{ $recurringCount }}</span>@endif
        </a>
    </nav>

    <div class="s-divider"></div>

    {{-- ── Blogs ── --}}
    @php
        $userBlogs      = auth()->user()->blogs ?? collect();
        $blogTotal      = $userBlogs->count();
        $blogPublished  = $userBlogs->where('status','approved')->count();
        $blogDraft      = $userBlogs->where('status','draft')->count();
        $blogPending    = $userBlogs->where('status','pending')->count();
    @endphp
    <div class="s-label">Blogs</div>
    <nav class="s-nav">
        <a href="{{ url('/user/dashboard/blogs') }}" class="s-link {{ request()->is('user/dashboard/blogs') && !request()->is('user/dashboard/blogs/create') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            My Blogs
            @if($blogTotal > 0)<span class="s-badge">{{ $blogTotal }}</span>@endif
        </a>
        <a href="{{ url('/user/dashboard/blogs/create') }}" class="s-link {{ request()->is('user/dashboard/blogs/create') ? 'active' : '' }}">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Write a Blog
        </a>
    </nav>
    @if($blogTotal > 0)
    <div class="s-sub">
        @if($blogPublished > 0)
        <a href="{{ url('/user/dashboard/blogs?status=approved') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>Published
            <span style="margin-left:auto;font-size:10px;color:var(--green);font-family:var(--mono);">{{ $blogPublished }}</span>
        </a>
        @endif
        @if($blogDraft > 0)
        <a href="{{ url('/user/dashboard/blogs?status=draft') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>Drafts
            <span style="margin-left:auto;font-size:10px;color:var(--yellow);font-family:var(--mono);">{{ $blogDraft }}</span>
        </a>
        @endif
        @if($blogPending > 0)
        <a href="{{ url('/user/dashboard/blogs?status=pending') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>In Review
            <span style="margin-left:auto;font-size:10px;color:var(--text3);font-family:var(--mono);">{{ $blogPending }}</span>
        </a>
        @endif
    </div>
    @endif

    <div class="s-bottom">
        <a href="{{ route('profile.show') }}" class="s-link" style="color:rgba(165,180,252,.75);margin-bottom:2px;">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            My Profile
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('__lf').submit();" class="s-link" style="color:rgba(248,113,113,.75);">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
        <form id="__lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>

</aside>

{{-- ══════════════════════════════════════════
     MAIN
══════════════════════════════════════════ --}}
<div class="main">

    <header class="topbar">
        <div style="display:flex;align-items:center;gap:10px;">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="topbar-left">
                <h1>Dashboard</h1>
                <p>{{ now()->format('l, d F Y') }}</p>
            </div>
        </div>
        <div class="topbar-right">
            <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input class="search-input" id="searchInput" type="text" placeholder="Search campaigns…" autocomplete="off">
            </div>
            {{-- Sort select — was missing --}}
            <select class="sort-sel" id="sortSelect">
                <option value="">Sort by…</option>
                <option value="amount-desc">Amount ↓</option>
                <option value="amount-asc">Amount ↑</option>
                <option value="date-desc">Newest first</option>
                <option value="date-asc">Oldest first</option>
            </select>
            <button class="tb-btn" title="Notifications">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if($countPending > 0 || $countRejected > 0)<span class="notif-dot"></span>@endif
            </button>
            <div class="theme-toggle">
                <input type="checkbox" id="themeToggle">
                <label for="themeToggle">
                    <div class="theme-icons">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    </div>
                </label>
            </div>
            <a href="{{ route('campaign.create') }}" class="create-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Campaign
            </a>
            {{-- Avatar dropdown — was missing --}}
            <div class="av-wrap" id="avWrap">
                <div class="t-avatar" onclick="toggleDD()" title="Account">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    @endif
                </div>
                <div class="av-dd" id="avDD">
                    <div class="dd-hdr">
                        <div class="dd-name">{{ auth()->user()->name ?? 'User' }}</div>
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
                    <a href="{{ route('recurring.index') }}" class="dd-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Recurring Donations
                    </a>
                    <div class="dd-sep"></div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('__lf').submit();" class="dd-item danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign Out
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="body">

        @php
            $totalRaised  = $campaigns->sum('raised_amount');
            $totalGoal    = $campaigns->sum('goal_amount');
            $overallPct   = $totalGoal > 0 ? min(100, round(($totalRaised / $totalGoal) * 100)) : 0;
            $totalDonors  = $campaigns->sum('donors_count') ?? 0;
            $hour         = now()->hour;
            $greeting     = $hour < 12 ? 'morning' : ($hour < 17 ? 'afternoon' : 'evening');
        @endphp

        {{-- ══ WELCOME BANNER ══ --}}
        <div class="welcome-banner">
            <div class="wb-left">
                <div class="wb-tag">
                    <span class="wb-tag-dot"></span>
                    Good {{ $greeting }}, Fundraiser
                </div>
                <div class="wb-name">{{ auth()->user()->name }} 👋</div>
                <div class="wb-sub">Here's what's happening with your campaigns today.</div>
                <div class="wb-badges">
                    @if($countActive > 0)
                        <span class="wb-badge wbb-green">✓ {{ $countActive }} live</span>
                    @endif
                    @if($countPending > 0)
                        <span class="wb-badge wbb-yellow">⏱ {{ $countPending }} pending review</span>
                    @endif
                    @if($countRejected > 0)
                        <span class="wb-badge wbb-red">✕ {{ $countRejected }} rejected</span>
                    @endif
                    @if($overallPct > 0)
                        <span class="wb-badge wbb-purple">{{ $overallPct }}% overall funded</span>
                    @endif
                    @if($countAll === 0)
                        <span class="wb-badge wbb-purple">Get started — create your first campaign</span>
                    @endif
                </div>
            </div>
            <div class="wb-right">
                <a href="{{ route('campaign.create') }}" class="wb-btn wb-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    New Campaign
                </a>
                @if(!$kyc || $kyc->status !== 'approved')
                <a href="{{ url('/user/kyc') }}" class="wb-btn wb-btn-yellow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    {{ $kyc ? 'KYC '.ucfirst($kyc->status) : 'Submit KYC' }}
                </a>
                @endif
                <a href="{{ route('profile.show') }}" class="wb-btn wb-btn-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    My Profile
                </a>
            </div>
        </div>

        {{-- ══ STATS (5 cards — was 4) ══ --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon-wrap si-indigo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Total Raised</div>
                    <div class="stat-val sv-indigo">₹{{ number_format($totalRaised, 0) }}</div>
                    <div class="stat-foot">{{ $overallPct }}% of total goal</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-wrap si-pink">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Total Goal</div>
                    <div class="stat-val sv-pink">₹{{ number_format($totalGoal, 0) }}</div>
                    <div class="stat-foot">Across {{ $countAll }} campaigns</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-wrap si-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Active Campaigns</div>
                    <div class="stat-val sv-green">{{ $countActive }}</div>
                    <div class="stat-foot">Live &amp; accepting donations</div>
                </div>
            </div>
            {{-- Donors count — was missing --}}
            <div class="stat-card">
                <div class="stat-icon-wrap si-yellow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Total Donors</div>
                    <div class="stat-val sv-yellow">{{ number_format($totalDonors) }}</div>
                    <div class="stat-foot">People who supported you</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-wrap si-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-label">All Campaigns</div>
                    <div class="stat-val sv-blue">{{ $countAll }}</div>
                    <div class="stat-foot"><a href="{{ route('campaign.create') }}">+ Create new →</a></div>
                </div>
            </div>
        </div>

        {{-- ══ ANALYTICS ROW ══ --}}
        <div class="analytics-row">
            <div class="chart-card">
                <div class="chart-card-hdr">
                    <div>
                        <div class="chart-title">Fundraising Overview</div>
                        <div class="chart-sub">Monthly funds raised — last 12 months</div>
                    </div>
                    <div class="chart-legend">
                        <div class="chart-legend-dot"></div>
                        Amount Raised (₹)
                    </div>
                </div>
                <div class="chart-wrap"><canvas id="fundChart"></canvas></div>
            </div>

            <div class="qs-panel">
                <div class="qs-title">Campaign Status</div>
                @php
                    $qsRows = [
                        ['var(--green)',  'Active',         $countActive],
                        ['var(--blue)',   'Awaiting Review',$countInactive],
                        ['var(--yellow)', 'Pending',        $countPending],
                        ['var(--accent)', 'Paused',         $countPaused],
                        ['var(--red)',    'Rejected',       $countRejected],
                        ['var(--gray)',   'Expired',        $countExpired],
                    ];
                @endphp
                @foreach($qsRows as [$color, $label, $val])
                <div class="qs-row" onclick="setFilter('{{ strtolower(str_replace(' ','-',$label)) }}')">
                    <div class="qs-row-left">
                        <div class="qs-dot" style="background:{{ $color }}"></div>
                        <span class="qs-label">{{ $label }}</span>
                    </div>
                    <span class="qs-val">{{ $val }}</span>
                </div>
                @endforeach
                <div class="qs-progress">
                    <div class="qs-prog-label">
                        <span>Overall funding progress</span>
                        <span>{{ $overallPct }}%</span>
                    </div>
                    <div class="qs-prog-bar">
                        <div class="qs-prog-fill" id="overallBar" style="width:0%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ QUICK NAV (was missing entirely) ══ --}}
        <div class="qnav">
            @php
            $navItems = [
                ['url'=> route('campaign.create'),       'lbl'=>'New Campaign',     'sub'=>'Start fundraising', 'delay'=>'.05s','bg'=>'var(--accent-lt)',          'color'=>'var(--accent)',  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>'],
                ['url'=> url('/user/dashboard').'#cGrid','lbl'=>'All Campaigns',    'sub'=>$countAll.' total',   'delay'=>'.10s','bg'=>'var(--green-lt)',            'color'=>'var(--green)',   'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
                ['url'=> route('profile.show'),           'lbl'=>'My Profile',       'sub'=>'View & edit',       'delay'=>'.15s','bg'=>'rgba(59,130,246,.10)',       'color'=>'var(--blue)',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],

                ['url'=> url('/user/kyc'),                'lbl'=>'KYC Status',       'sub'=>$kyc ? ucfirst($kyc->status) : 'Not submitted','delay'=>'.25s','bg'=>'var(--yellow-lt)', 'color'=>'var(--yellow)',  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'],
                ['url'=> route('recurring.index'),        'lbl'=>'Recurring',        'sub'=>'Manage donations',  'delay'=>'.30s','bg'=>'var(--pink-lt)',             'color'=>'var(--pink)',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'],
                ['url'=> url('/user/dashboard/blogs'),    'lbl'=>'My Blogs',         'sub'=>$blogTotal.' posts', 'delay'=>'.35s','bg'=>'rgba(245,158,11,.10)',       'color'=>'var(--yellow)',  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>'],
                ['url'=> url('/user/dashboard/blogs/create'),'lbl'=>'Write Blog',   'sub'=>'New post',          'delay'=>'.40s','bg'=>'rgba(16,185,129,.10)',       'color'=>'var(--green)',   'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'],
                ['url'=> route('gift-cards.index'),       'lbl'=>'Gift Cards',       'sub'=>'Buy & redeem',      'delay'=>'.45s','bg'=>'rgba(236,72,153,.10)',       'color'=>'var(--pink)',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>'],
            ];
            @endphp
            @foreach($navItems as $item)
            <a href="{{ $item['url'] }}" class="qnav-card" style="animation-delay:{{ $item['delay'] }};--qc:{{ $item['bg'] }};">
                <div class="qnav-ico" style="background:{{ $item['bg'] }};color:{{ $item['color'] }};">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $item['icon'] !!}</svg>
                </div>
                <div>
                    <div class="qnav-lbl">{{ $item['lbl'] }}</div>
                    <div class="qnav-sub">{{ $item['sub'] }}</div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- ══ CAMPAIGNS SECTION ══ --}}
        <div class="sec-hdr" id="cGrid">
            <div class="sec-title">Your Campaigns</div>
            <div class="sec-right">
                <div class="ftabs" id="ftabs">
                    <button class="ftab on" data-filter="all">All <span class="cnt">{{ $countAll }}</span></button>
                    <button class="ftab" data-filter="active">Active <span class="cnt">{{ $countActive }}</span></button>
                    <button class="ftab" data-filter="inactive">Awaiting <span class="cnt">{{ $countInactive }}</span></button>
                    <button class="ftab" data-filter="pending">Pending <span class="cnt">{{ $countPending }}</span></button>
                    <button class="ftab" data-filter="paused">Paused <span class="cnt">{{ $countPaused }}</span></button>
                    <button class="ftab" data-filter="rejected">Rejected <span class="cnt">{{ $countRejected }}</span></button>
                    <button class="ftab" data-filter="expired">Expired <span class="cnt">{{ $countExpired }}</span></button>
                </div>
                <div class="view-toggle">
                    <button class="vt-btn on" id="btnGrid" title="Grid view">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    </button>
                    <button class="vt-btn" id="btnList" title="List view">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="noResults">No campaigns match this filter.</div>

        @if($campaigns->count() > 0)

        {{-- GRID VIEW --}}
        <div class="c-grid" id="campaignGrid">
            @foreach($campaigns as $i => $campaign)
            @php
                $state = $campaign->campaign_state;
                if      ($state === 'active')   { $fv='active';   $bc='b-active';   $bl='Active'; }
                elseif  ($state === 'paused')   { $fv='paused';   $bc='b-paused';   $bl='Paused'; }
                elseif  ($state === 'rejected') { $fv='rejected'; $bc='b-rejected'; $bl='Rejected'; }
                elseif  ($state === 'expired')  { $fv='expired';  $bc='b-expired';  $bl='Expired'; }
                elseif  ($state === 'inactive') { $fv='inactive'; $bc='b-inactive'; $bl='Under Review'; }
                elseif  ($state === 'pending')  { $fv='pending';  $bc='b-pending';  $bl='Pending'; }
                else                            { $fv='other';    $bc='b-default';  $bl=ucfirst($state ?? 'Draft'); }
                $raised = $campaign->raised_amount ?? 0;
                $goal   = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
                $pct    = min(100, round(($raised / $goal) * 100));
            @endphp
            <div class="c-card"
                 data-filter="{{ $fv }}"
                 data-title="{{ strtolower($campaign->title) }}"
                 data-amount="{{ $campaign->goal_amount }}"
                 data-date="{{ $campaign->created_at }}"
                 style="animation-delay:{{ $i * .04 }}s">
                <div class="c-thumb">
                    @if($campaign->cover_image)
                        <img src="{{ asset('storage/'.$campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
                        <div class="c-thumb-overlay"></div>
                    @else
                        <div class="c-thumb-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                    @endif
                    <div class="c-badge-wrap"><span class="badge {{ $bc }}">{{ $bl }}</span></div>
                </div>
                <div class="c-body">
                    <div class="c-title">{{ $campaign->title }}</div>

                    @if($fv === 'inactive')
                    <div class="reason reason-b">
                        <div class="reason-lbl">⏳ Awaiting admin review</div>
                        <div class="reason-txt">Your campaign will go live once approved.</div>
                    </div>
                    @elseif($fv === 'pending')
                    <div class="reason reason-b">
                        <div class="reason-lbl">Pending submission</div>
                        <div class="reason-txt">Waiting to be reviewed by an admin.</div>
                    </div>
                    @elseif($fv === 'rejected' && $campaign->rejection_reason)
                    <div class="reason reason-r">
                        <div class="reason-lbl">✕ Rejection reason</div>
                        <div class="reason-txt">{{ $campaign->rejection_reason }}</div>
                    </div>
                    @elseif($fv === 'paused' && $campaign->pause_reason)
                    <div class="reason reason-y">
                        <div class="reason-lbl">⏸ Pause reason</div>
                        <div class="reason-txt">{{ $campaign->pause_reason }}</div>
                    </div>
                    @elseif($fv === 'expired')
                    <div class="reason reason-g">
                        <div class="reason-lbl">Expired</div>
                        <div class="reason-txt">This campaign has ended. Create a new one to continue.</div>
                    </div>
                    @endif

                    <div class="prog-wrap">
                        <div class="prog-numbers">
                            <span class="prog-raised">₹{{ number_format($raised) }}</span>
                            <span class="prog-goal">of ₹{{ number_format($campaign->goal_amount) }}</span>
                        </div>
                        <div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
                        <div class="prog-meta"><span class="prog-pct">{{ $pct }}% funded</span></div>
                    </div>

                    {{-- Action buttons with Pause/Resume — was missing --}}
                    <div class="c-actions">
                        <a href="{{ route('campaign.show', $campaign->id) }}" class="c-btn c-btn-view">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View
                        </a>
                        <a href="{{ route('campaign.edit', $campaign->id) }}" class="c-btn c-btn-edit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        @if($fv === 'active')
                        <form action="{{ route('campaign.pause', $campaign->id) }}" method="POST" style="flex:1;" onsubmit="return handleSub(this,'Pausing…')">
                            @csrf
                            <button class="c-btn c-btn-pause" style="width:100%;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pause
                            </button>
                        </form>
                        @elseif($fv === 'paused')
                        <form action="{{ route('campaign.resume', $campaign->id) }}" method="POST" style="flex:1;" onsubmit="return handleSub(this,'Resuming…')">
                            @csrf
                            <button class="c-btn c-btn-resume" style="width:100%;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Resume
                            </button>
                        </form>
                        @elseif($fv === 'rejected')
                        <form action="{{ route('campaign.resubmit', $campaign->id) }}" method="POST" style="flex:1;" onsubmit="return handleSub(this,'Resubmitting…')">
                            @csrf
                            <button class="c-btn c-btn-resume" style="width:100%;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Resubmit
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- LIST VIEW --}}
        <div class="c-list" id="campaignList" style="display:none;">
            @foreach($campaigns as $i => $campaign)
            @php
                $state = $campaign->campaign_state;
                if      ($state === 'active')   { $fv='active';   $bc='b-active';   $bl='Active'; }
                elseif  ($state === 'paused')   { $fv='paused';   $bc='b-paused';   $bl='Paused'; }
                elseif  ($state === 'rejected') { $fv='rejected'; $bc='b-rejected'; $bl='Rejected'; }
                elseif  ($state === 'expired')  { $fv='expired';  $bc='b-expired';  $bl='Expired'; }
                elseif  ($state === 'inactive') { $fv='inactive'; $bc='b-inactive'; $bl='Under Review'; }
                elseif  ($state === 'pending')  { $fv='pending';  $bc='b-pending';  $bl='Pending'; }
                else                            { $fv='other';    $bc='b-default';  $bl=ucfirst($state ?? 'Draft'); }
                $raised = $campaign->raised_amount ?? 0;
                $goal   = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
                $pct    = min(100, round(($raised / $goal) * 100));
            @endphp
            <div class="c-list-item"
                 data-filter="{{ $fv }}"
                 data-title="{{ strtolower($campaign->title) }}"
                 data-amount="{{ $campaign->goal_amount }}"
                 data-date="{{ $campaign->created_at }}"
                 style="animation-delay:{{ $i * .03 }}s">
                <div class="c-list-thumb">
                    @if($campaign->cover_image)
                        <img src="{{ asset('storage/'.$campaign->cover_image) }}" alt="{{ $campaign->title }}">
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    @endif
                </div>
                <div class="c-list-info">
                    <div class="c-list-title">{{ $campaign->title }}</div>
                    <div class="c-list-sub">
                        <span>₹{{ number_format($raised) }} raised</span>
                        <span class="c-list-dot"></span>
                        <span>of ₹{{ number_format($campaign->goal_amount) }}</span>
                    </div>
                </div>
                <div class="c-list-prog">
                    <div class="c-list-pct">{{ $pct }}%</div>
                    <div class="c-list-bar"><div class="c-list-fill" style="width:{{ $pct }}%"></div></div>
                </div>
                <div class="c-list-badge"><span class="badge {{ $bc }}">{{ $bl }}</span></div>
                <div class="c-list-actions">
                    <a href="{{ route('campaign.show', $campaign->id) }}" class="c-list-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>View
                    </a>
                    <a href="{{ route('campaign.edit', $campaign->id) }}" class="c-list-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>Edit
                    </a>
                    @if($fv === 'active')
                    <form action="{{ route('campaign.pause', $campaign->id) }}" method="POST" onsubmit="return handleSub(this,'…')">
                        @csrf
                        <button class="c-list-btn" style="border-color:rgba(245,158,11,.3);color:var(--yellow);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Pause
                        </button>
                    </form>
                    @elseif($fv === 'paused')
                    <form action="{{ route('campaign.resume', $campaign->id) }}" method="POST" onsubmit="return handleSub(this,'…')">
                        @csrf
                        <button class="c-list-btn" style="border-color:rgba(16,185,129,.3);color:var(--green);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Resume
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @else
        <div class="empty-state">
            <div class="empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="empty-title">Start your first fundraiser</div>
            <div class="empty-sub">Create a campaign and start making a difference in the world today.</div>
            <a href="{{ route('campaign.create') }}" class="empty-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Create Campaign
            </a>
        </div>
        @endif

        {{-- ══ RECURRING DONATIONS SECTION (new) ══ --}}
        @if(isset($recurringDonations) && $recurringDonations->count() > 0)
        <div class="sec-hdr" style="margin-top:32px;">
            <div class="sec-title">Recurring Donations</div>
            <div class="sec-right">
                <a href="{{ route('recurring.index') }}" style="font-size:12.5px;color:var(--accent);font-weight:600;">View all →</a>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($recurringDonations->take(3) as $rd)
            <div class="rec-card">
                <div class="rec-icon" style="background:var(--accent-lt);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div class="rec-info">
                    <div class="rec-title">{{ $rd->campaign->title ?? 'Campaign' }}</div>
                    <div class="rec-sub">Next: {{ $rd->next_payment_at ? \Carbon\Carbon::parse($rd->next_payment_at)->format('d M Y') : 'N/A' }} · {{ ucfirst($rd->status) }}</div>
                </div>
                <div class="rec-amount">
                    <span class="rec-amt-val">₹{{ number_format($rd->amount) }}</span>
                    <span class="rec-amt-freq">/ {{ $rd->frequency }}</span>
                </div>
                <div class="rec-actions">
                    @if($rd->status === 'active')
                    <form action="{{ route('recurring.pause', $rd->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="rec-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6"/></svg>Pause
                        </button>
                    </form>
                    @elseif($rd->status === 'paused')
                    <form action="{{ route('recurring.resume', $rd->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="rec-btn" style="color:var(--green);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>Resume
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('recurring.cancel', $rd->id) }}" method="POST" onsubmit="return confirm('Cancel this recurring donation?')">
                        @csrf @method('PATCH')
                        <button class="rec-btn" style="color:var(--red);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Cancel
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

<script>
(function(){
'use strict';

/* ── Dark mode ── */
var html   = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved  = localStorage.getItem('theme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
    var t = this.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', t);
    localStorage.setItem('theme', t);
    setTimeout(renderChart, 50);
});

/* ── Hamburger ── */
var sidebar = document.getElementById('sidebar');
document.getElementById('hamburger').addEventListener('click', function(){
    sidebar.classList.toggle('open');
});
document.addEventListener('click', function(e){
    if (window.innerWidth <= 860 && !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target))
        sidebar.classList.remove('open');
});

/* ── Avatar dropdown (was missing) ── */
window.toggleDD = function(){ document.getElementById('avDD').classList.toggle('open'); };
document.addEventListener('click', function(e){
    var wrap = document.getElementById('avWrap');
    if (wrap && !wrap.contains(e.target)) document.getElementById('avDD').classList.remove('open');
});

/* ── Animate overall funding bar (was missing) ── */
setTimeout(function(){
    var bar = document.getElementById('overallBar');
    if (bar) bar.style.width = '{{ $overallPct }}%';
}, 700);

/* ── Toast ── */
function toast(msg, type){
    var t = document.createElement('div');
    t.className = 'toast toast-' + (type || 'success');
    t.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>' + msg + '</span><button class="toast-close" onclick="this.parentElement.remove()">✕</button>';
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(function(){ t.remove(); }, 4500);
}
@if(session('success')) setTimeout(function(){ toast(@json(session('success')),'success'); }, 200); @endif
@if(session('error'))   setTimeout(function(){ toast(@json(session('error')),'error'); },   200); @endif

/* ── Filter + Search + Sort ── */
var activeFilter = 'all', searchQ = '', sortVal = '';

function getCards(){
    var isGrid = document.getElementById('campaignGrid').style.display !== 'none';
    return Array.from(document.querySelectorAll(isGrid ? '#campaignGrid .c-card' : '#campaignList .c-list-item'));
}

function applyFilters(){
    var all = Array.from(document.querySelectorAll('#campaignGrid .c-card, #campaignList .c-list-item'));

    /* Sort */
    if (sortVal) {
        var grids = Array.from(document.querySelectorAll('#campaignGrid .c-card'));
        var lists = Array.from(document.querySelectorAll('#campaignList .c-list-item'));
        [grids, lists].forEach(function(arr){
            if (!arr.length) return;
            if (sortVal === 'amount-desc') arr.sort(function(a,b){ return +b.dataset.amount - +a.dataset.amount; });
            if (sortVal === 'amount-asc')  arr.sort(function(a,b){ return +a.dataset.amount - +b.dataset.amount; });
            if (sortVal === 'date-desc')   arr.sort(function(a,b){ return new Date(b.dataset.date) - new Date(a.dataset.date); });
            if (sortVal === 'date-asc')    arr.sort(function(a,b){ return new Date(a.dataset.date) - new Date(b.dataset.date); });
            var parent = arr[0].parentElement;
            arr.forEach(function(c){ parent.appendChild(c); });
        });
    }

    var visible = 0;
    all.forEach(function(c){
        var mF = activeFilter === 'all' || c.dataset.filter === activeFilter;
        var mS = !searchQ || (c.dataset.title || '').includes(searchQ);
        c.style.display = (mF && mS) ? '' : 'none';
        if (mF && mS) visible++;
    });
    document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
}

document.querySelectorAll('.ftab').forEach(function(tab){
    tab.addEventListener('click', function(){
        document.querySelectorAll('.ftab').forEach(function(t){ t.classList.remove('on'); });
        this.classList.add('on');
        activeFilter = this.dataset.filter;
        applyFilters();
    });
});

window.setFilter = function(f){
    activeFilter = f;
    document.querySelectorAll('.ftab').forEach(function(t){ t.classList.toggle('on', t.dataset.filter === f); });
    applyFilters();
    var el = document.getElementById('cGrid');
    if (el) el.scrollIntoView({ behavior:'smooth', block:'start' });
};

var searchTimeout;
document.getElementById('searchInput').addEventListener('input', function(){
    clearTimeout(searchTimeout);
    searchQ = this.value.toLowerCase().trim();
    searchTimeout = setTimeout(applyFilters, 180);
});

document.getElementById('sortSelect').addEventListener('change', function(){
    sortVal = this.value;
    applyFilters();
});

/* ── View toggle ── */
var grid    = document.getElementById('campaignGrid');
var list    = document.getElementById('campaignList');
var btnGrid = document.getElementById('btnGrid');
var btnList = document.getElementById('btnList');

btnGrid.addEventListener('click', function(){
    grid.style.display = ''; list.style.display = 'none';
    btnGrid.classList.add('on'); btnList.classList.remove('on');
    applyFilters();
});
btnList.addEventListener('click', function(){
    grid.style.display = 'none'; list.style.display = '';
    btnList.classList.add('on'); btnGrid.classList.remove('on');
    applyFilters();
});

/* ── Form submit helper ── */
window.handleSub = function(form, txt){
    form.querySelectorAll('button[type=submit]').forEach(function(b){ b.disabled = true; b.textContent = txt; });
    return true;
};

/* ── Chart ── */
var fundChart;
function renderChart(){
    var isDark    = html.getAttribute('data-theme') === 'dark';
    var gridColor = isDark ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.04)';
    var lblColor  = isDark ? 'rgba(255,255,255,.35)' : 'rgba(0,0,0,.35)';
    var tipBg     = isDark ? '#1e2033' : '#fff';
    var tipTx     = isDark ? '#eef0ff' : '#111';

    Chart.defaults.color       = lblColor;
    Chart.defaults.font.family = "'DM Mono', monospace";
    Chart.defaults.font.size   = 10.5;

    var ctx = document.getElementById('fundChart');
    if (!ctx) return;
    if (fundChart) fundChart.destroy();

    var monthlyData = @json($monthlyData ?? []);
    var labels = Object.keys(monthlyData);
    var values = Object.values(monthlyData);

    var cctx = ctx.getContext('2d');
    var grad = cctx.createLinearGradient(0, 0, 0, 180);
    grad.addColorStop(0, 'rgba(99,102,241,.20)');
    grad.addColorStop(1, 'rgba(99,102,241,0)');

    fundChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Amount Raised (₹)',
                data: values,
                borderColor: '#6366f1', backgroundColor: grad,
                borderWidth: 2.5, fill: true, tension: .45,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: tipBg, pointBorderWidth: 2,
                pointRadius: 4, pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tipBg, titleColor: tipTx, bodyColor: tipTx,
                    borderColor: gridColor, borderWidth: 1, padding: 12, cornerRadius: 10,
                    callbacks: { label: function(c){ return ' ₹' + Number(c.parsed.y).toLocaleString('en-IN'); } }
                }
            },
            scales: {
                x: { grid:{ color:gridColor }, border:{ dash:[3,3] } },
                y: { grid:{ color:gridColor }, border:{ dash:[3,3] }, ticks:{ callback: function(v){ return '₹'+Number(v).toLocaleString('en-IN'); } } }
            }
        }
    });
}
renderChart();

})();
</script>

</body>
</html>