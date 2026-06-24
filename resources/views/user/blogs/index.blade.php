<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Blogs — DonateBazaar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* ══════════════════════════════════════════════
   DESIGN TOKENS
══════════════════════════════════════════════ */
:root {
    --bg:           #f0f1f9;
    --bg2:          #e8eaf6;
    --surface:      #ffffff;
    --surface2:     #f5f6fd;
    --surface3:     #ecedf8;
    --border:       rgba(99,102,241,0.08);
    --border2:      rgba(99,102,241,0.14);
    --text:         #0c0d1a;
    --text2:        #3d4066;
    --text3:        #8b8fb5;
    --accent:       #5b5ef4;
    --accent2:      #8b5cf6;
    --accent3:      #06b6d4;
    --accent-glow:  rgba(91,94,244,0.15);
    --green:        #10b981;
    --yellow:       #f59e0b;
    --red:          #ef4444;
    --sidebar-bg:   #07081a;
    --sidebar-w:    260px;
    --font:         'DM Sans', sans-serif;
    --font-display: 'DM Mono', sans-serif;
    --font-mono:    'DM Mono', monospace;
    --radius:       16px;
    --radius-sm:    10px;
    --radius-xs:    6px;
    --shadow:       0 1px 3px rgba(0,0,0,0.04), 0 4px 20px rgba(99,102,241,0.06);
    --shadow-md:    0 4px 24px rgba(99,102,241,0.10);
    --shadow-lg:    0 12px 48px rgba(99,102,241,0.16);
    --transition:   0.22s cubic-bezier(0.4,0,0.2,1);
}

[data-theme="dark"] {
    --bg:           #080919;
    --bg2:          #0d0f24;
    --surface:      #10122a;
    --surface2:     #161833;
    --surface3:     #1c1f3d;
    --border:       rgba(99,102,241,0.10);
    --border2:      rgba(99,102,241,0.18);
    --text:         #e8eaff;
    --text2:        #9ba3d4;
    --text3:        #4a5080;
    --sidebar-bg:   #050614;
    --accent-glow:  rgba(91,94,244,0.22);
    --shadow:       0 1px 3px rgba(0,0,0,0.3), 0 4px 20px rgba(0,0,0,0.2);
    --shadow-md:    0 4px 24px rgba(0,0,0,0.3);
    --shadow-lg:    0 12px 48px rgba(0,0,0,0.5);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; }
body {
    font-family: var(--font);
    background: var(--bg);
    color: var(--text);
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
    transition: background var(--transition), color var(--transition);
}
a { text-decoration: none; color: inherit; }

/* ══ SHELL ══ */
.shell { display: flex; min-height: 100vh; }

/* ══════════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════════ */
.sidebar {
    width: var(--sidebar-w);
    flex-shrink: 0;
    background: var(--sidebar-bg);
    position: fixed; top: 0; left: 0; bottom: 0;
    z-index: 300;
    display: flex; flex-direction: column;
    overflow-y: auto; overflow-x: hidden;
    transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
    border-right: 1px solid rgba(255,255,255,0.03);
}
.sidebar::-webkit-scrollbar { width: 2px; }
.sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.06); }

/* Logo */
.s-logo {
    display: flex; align-items: center; gap: 11px;
    padding: 24px 20px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.04);
    flex-shrink: 0;
}
.s-logo-mark {
    width: 38px; height: 38px; border-radius: 11px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 16px rgba(91,94,244,0.4); flex-shrink: 0;
}
.s-logo-mark svg { width: 18px; height: 18px; color: #fff; }
.s-logo-name { font-family: var(--font-display); font-size: 16px; font-weight: 700; color: #fff; letter-spacing: -0.01em; }
.s-logo-tag  { font-size: 9px; color: rgba(255,255,255,0.25); text-transform: uppercase; letter-spacing: 0.14em; margin-top: 1px; font-family: var(--font-mono); }

/* User pill */
.s-user {
    margin: 14px 12px 6px;
    padding: 11px 13px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: var(--radius-sm);
    display: flex; align-items: center; gap: 10px;
    flex-shrink: 0;
    transition: background var(--transition);
}
.s-user:hover { background: rgba(255,255,255,0.07); }
.s-avatar {
    width: 34px; height: 34px; border-radius: 9px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-family: var(--font-display);
}
.s-user-name { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,0.85); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.s-user-role { font-size: 10px; color: rgba(255,255,255,0.3); margin-top: 1px; font-family: var(--font-mono); }

/* Nav */
.s-section { padding: 20px 12px 4px; }
.s-label {
    font-size: 9px; font-weight: 700; color: rgba(255,255,255,0.2);
    text-transform: uppercase; letter-spacing: 0.18em;
    padding: 0 8px 8px; font-family: var(--font-mono);
}
.s-link {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 12px; border-radius: 10px;
    color: rgba(255,255,255,0.45); font-size: 13px; font-weight: 500;
    transition: background var(--transition), color var(--transition);
    margin-bottom: 2px; border: none; background: transparent;
    width: 100%; text-align: left; cursor: pointer;
    position: relative; letter-spacing: -0.01em;
}
.s-link:hover  { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.85); }
.s-link.active {
    background: linear-gradient(90deg, rgba(91,94,244,0.2) 0%, rgba(91,94,244,0.06) 100%);
    color: #a5b4fc;
    border: 1px solid rgba(91,94,244,0.2);
}
.s-link.active::before {
    content: ''; position: absolute; left: 0; top: 25%; bottom: 25%;
    width: 3px; border-radius: 0 3px 3px 0;
    background: linear-gradient(180deg, var(--accent), var(--accent2));
}
.s-icon { width: 15px; height: 15px; flex-shrink: 0; opacity: 0.9; }
.s-badge {
    margin-left: auto; font-size: 9.5px; font-weight: 700;
    padding: 2px 7px; border-radius: 100px;
    background: rgba(91,94,244,0.2); color: #a5b4fc;
    font-family: var(--font-mono); border: 1px solid rgba(91,94,244,0.25);
}

/* Sub-links */
.s-sub { padding: 2px 12px 4px 32px; }
.s-sub-link {
    display: flex; align-items: center; gap: 8px;
    padding: 6px 10px; border-radius: 8px;
    color: rgba(255,255,255,0.3); font-size: 11.5px; font-weight: 500;
    transition: all var(--transition); margin-bottom: 1px;
    cursor: pointer;
}
.s-sub-link:hover { color: rgba(255,255,255,0.7); background: rgba(255,255,255,0.04); }
.s-sub-link.active { color: #a5b4fc; }
.s-dot { width: 4px; height: 4px; border-radius: 50%; background: currentColor; flex-shrink: 0; opacity: 0.5; }
.s-sub-link.active .s-dot { opacity: 1; }
.s-count { margin-left: auto; font-size: 10px; font-family: var(--font-mono); opacity: 0.7; }

.s-divider { height: 1px; background: rgba(255,255,255,0.04); margin: 10px 16px; }
.s-bottom { margin-top: auto; padding: 10px 12px 18px; border-top: 1px solid rgba(255,255,255,0.04); flex-shrink: 0; }
.s-signout { color: rgba(248,113,113,0.6) !important; }
.s-signout:hover { color: rgba(248,113,113,0.9) !important; background: rgba(239,68,68,0.08) !important; }

/* ══════════════════════════════════════════════
   MAIN AREA
══════════════════════════════════════════════ */
.main { margin-left: var(--sidebar-w); flex: 1; min-width: 0; display: flex; flex-direction: column; min-height: 100vh; }

/* Topbar */
.topbar {
    height: 62px; padding: 0 32px;
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 100; gap: 12px;
    flex-shrink: 0;
}
.topbar-title { font-family: var(--font-display); font-size: 17px; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }
.topbar-sub   { font-size: 11px; color: var(--text3); margin-top: 1px; font-family: var(--font-mono); }
.topbar-right { display: flex; align-items: center; gap: 10px; }

/* Dark toggle */
.dark-toggle { position: relative; }
.dark-toggle input { position: absolute; opacity: 0; width: 0; height: 0; }
.dark-toggle label {
    display: flex; align-items: center; width: 50px; height: 26px;
    border-radius: 100px; background: var(--surface2); border: 1px solid var(--border2);
    cursor: pointer; padding: 3px; position: relative;
    transition: background var(--transition);
}
.dark-toggle label::after {
    content: ''; width: 18px; height: 18px; border-radius: 50%;
    background: var(--accent); position: absolute; left: 4px;
    transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
    box-shadow: 0 2px 6px rgba(91,94,244,0.4);
}
.dark-toggle input:checked + label::after { transform: translateX(22px); }
.dark-icons { display: flex; justify-content: space-between; width: 100%; padding: 0 1px; position: relative; z-index: 1; }
.dark-icons svg { width: 11px; height: 11px; color: var(--text3); }

.t-btn {
    width: 36px; height: 36px; border-radius: 10px;
    border: 1px solid var(--border2); background: var(--surface2);
    color: var(--text2); cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all var(--transition);
}
.t-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-glow); }
.t-btn svg { width: 15px; height: 15px; }

.t-avatar {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-family: var(--font-display);
    box-shadow: 0 3px 10px rgba(91,94,244,0.3);
}

.hamburger {
    display: none; width: 36px; height: 36px; border-radius: 10px;
    border: 1px solid var(--border2); background: var(--surface2);
    cursor: pointer; color: var(--text2);
    align-items: center; justify-content: center; flex-shrink: 0;
}
.hamburger svg { width: 16px; height: 16px; }

/* ══════════════════════════════════════════════
   BODY
══════════════════════════════════════════════ */
.body { padding: 32px 36px 80px; flex: 1; }

/* ── Page header ── */
.page-hdr {
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 16px;
    margin-bottom: 28px; flex-wrap: wrap;
}
.page-hdr-title {
    font-family: var(--font-display);
    font-size: 26px; font-weight: 800;
    color: var(--text); letter-spacing: -0.03em;
    line-height: 1.1;
}
.page-hdr-sub { font-size: 12.5px; color: var(--text3); margin-top: 5px; font-family: var(--font-mono); }

.btn-new {
    display: inline-flex; align-items: center; gap: 8px;
    height: 40px; padding: 0 20px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; border-radius: var(--radius-sm);
    font-size: 13px; font-weight: 600; font-family: var(--font);
    border: none; cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 16px rgba(91,94,244,0.35);
    transition: opacity var(--transition), transform var(--transition), box-shadow var(--transition);
    letter-spacing: -0.01em;
}
.btn-new:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(91,94,244,0.45); }
.btn-new svg { width: 14px; height: 14px; }

/* ── Stats row ── */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 28px;
}
.stat-card {
    background: var(--surface);
    border: 1px solid var(--border2);
    border-radius: var(--radius);
    padding: 18px 20px;
    display: flex; align-items: center; gap: 14px;
    box-shadow: var(--shadow);
    transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
    cursor: pointer;
    position: relative; overflow: hidden;
}
.stat-card::before {
    content: ''; position: absolute;
    top: 0; left: 0; right: 0; height: 2px;
    background: var(--sc-color, var(--accent));
    transform: scaleX(0); transform-origin: left;
    transition: transform var(--transition);
}
.stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.stat-card:hover::before { transform: scaleX(1); }
.stat-card.active-filter { border-color: var(--sc-color, var(--accent)); }
.stat-card.active-filter::before { transform: scaleX(1); }

.stat-icon {
    width: 42px; height: 42px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; background: var(--sc-bg, rgba(91,94,244,0.08));
}
.stat-icon svg { width: 18px; height: 18px; color: var(--sc-color, var(--accent)); }
.stat-val { font-family: var(--font-display); font-size: 24px; font-weight: 800; color: var(--text); line-height: 1; margin-bottom: 2px; letter-spacing: -0.03em; }
.stat-lbl { font-size: 11px; color: var(--text3); font-family: var(--font-mono); font-weight: 500; }

/* ── Filter bar ── */
.filter-bar {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; margin-bottom: 24px; flex-wrap: wrap;
}
.ftabs {
    display: flex; gap: 3px;
    background: var(--surface); border: 1px solid var(--border2);
    padding: 4px; border-radius: 12px; width: fit-content;
}
.ftab {
    padding: 7px 16px; border-radius: 9px;
    font-size: 12.5px; font-weight: 600; cursor: pointer;
    border: none; background: transparent; color: var(--text3);
    transition: all var(--transition); font-family: var(--font);
    display: inline-flex; align-items: center; gap: 6px;
    white-space: nowrap; letter-spacing: -0.01em;
}
.ftab:hover { color: var(--accent); background: var(--accent-glow); }
.ftab.on {
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; box-shadow: 0 3px 12px rgba(91,94,244,0.35);
}
.ftab.on .cnt { background: rgba(255,255,255,0.2); color: #fff; }
.cnt {
    font-size: 10px; font-weight: 700; min-width: 18px; height: 18px;
    border-radius: 100px; padding: 0 5px;
    background: rgba(91,94,244,0.1); color: var(--accent);
    display: inline-flex; align-items: center; justify-content: center;
    font-family: var(--font-mono);
}

.search-wrap {
    position: relative; flex: 1; max-width: 280px;
}
.search-wrap svg {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    width: 14px; height: 14px; color: var(--text3); pointer-events: none;
}
.search-input {
    width: 100%; height: 38px; padding: 0 14px 0 36px;
    background: var(--surface); border: 1px solid var(--border2);
    border-radius: var(--radius-sm); font-family: var(--font);
    font-size: 13px; color: var(--text); outline: none;
    transition: border-color var(--transition), box-shadow var(--transition);
}
.search-input::placeholder { color: var(--text3); }
.search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }

/* ── Blog Grid ── */
.blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

/* ── Blog Card ── */
.blog-card {
    background: var(--surface);
    border: 1px solid var(--border2);
    border-radius: var(--radius);
    overflow: hidden;
    display: flex; flex-direction: column;
    box-shadow: var(--shadow);
    transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
    animation: cardIn 0.4s cubic-bezier(0.4,0,0.2,1) both;
}
@keyframes cardIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
.blog-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: rgba(91,94,244,0.3); }
.blog-card.hidden { display: none !important; }

/* Cover */
.blog-cover { position: relative; height: 175px; overflow: hidden; flex-shrink: 0; }
.blog-cover img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; display: block; }
.blog-card:hover .blog-cover img { transform: scale(1.06); }
.cover-placeholder {
    width: 100%; height: 100%;
    background: linear-gradient(135deg,
        rgba(91,94,244,0.06) 0%,
        rgba(139,92,246,0.06) 50%,
        rgba(6,182,212,0.04) 100%);
    display: flex; align-items: center; justify-content: center;
}
.cover-placeholder svg { width: 32px; height: 32px; color: var(--accent); opacity: 0.25; }

/* Status badge */
.status-badge {
    position: absolute; top: 12px; left: 12px;
    font-size: 9.5px; font-weight: 700; padding: 4px 11px;
    border-radius: 100px; font-family: var(--font-mono);
    letter-spacing: 0.08em; text-transform: uppercase;
    backdrop-filter: blur(8px);
}
.s-draft    { background: rgba(0,0,0,0.5);         color: #d1d5db; border: 1px solid rgba(255,255,255,0.1); }
.s-pending  { background: rgba(245,158,11,0.15);   color: #d97706; border: 1px solid rgba(245,158,11,0.3); }
/* .s-published{ background: rgba(16,185,129,0.15);   color: #059669; border: 1px solid rgba(16,185,129,0.3); } */
.s-rejected { background: rgba(239,68,68,0.15);    color: #dc2626; border: 1px solid rgba(239,68,68,0.3); }
.s-published {
    background: rgb(255 255 255);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

/* Category chip */
.cat-chip {
    position: absolute; bottom: 12px; right: 12px;
    font-size: 9.5px; font-weight: 600; padding: 3px 9px;
    border-radius: 100px; font-family: var(--font-mono);
    background: rgba(0,0,0,0.45); color: rgba(255,255,255,0.8);
    backdrop-filter: blur(6px); letter-spacing: 0.06em;
}

/* Card body */
.blog-body { padding: 18px 20px 20px; flex: 1; display: flex; flex-direction: column; }
.blog-title {
    font-family: var(--font-display); font-size: 15px; font-weight: 700;
    color: var(--text); line-height: 1.4; margin-bottom: 8px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    letter-spacing: -0.02em;
}
.blog-excerpt {
    font-size: 12.5px; color: var(--text3); line-height: 1.65;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    font-weight: 300; flex: 1; margin-bottom: 16px;
}

/* Card footer */
.blog-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 14px; border-top: 1px solid var(--border);
    margin-top: auto; gap: 8px;
}
.blog-date { font-size: 10.5px; color: var(--text3); font-family: var(--font-mono); }
.blog-actions { display: flex; align-items: center; gap: 6px; }

.action-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: var(--radius-xs);
    font-size: 11.5px; font-weight: 600; cursor: pointer;
    border: 1px solid transparent; text-decoration: none;
    transition: all var(--transition); font-family: var(--font);
    white-space: nowrap; letter-spacing: -0.01em;
}
.action-btn svg { width: 11px; height: 11px; }
.btn-view { background: var(--accent-glow); color: var(--accent); border-color: rgba(91,94,244,0.2); }
.btn-view:hover { background: var(--accent); color: #fff; border-color: transparent; transform: translateY(-1px); }
.btn-edit { background: var(--surface2); color: var(--text2); border-color: var(--border2); }
.btn-edit:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-glow); transform: translateY(-1px); }

/* ── Empty state ── */
.empty-state {
    display: none;
    text-align: center; padding: 80px 32px;
    background: var(--surface); border: 1px solid var(--border2);
    border-radius: var(--radius); box-shadow: var(--shadow);
}
.empty-icon-wrap {
    width: 72px; height: 72px; border-radius: 20px;
    background: var(--accent-glow); border: 1px solid rgba(91,94,244,0.2);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
}
.empty-icon-wrap svg { width: 30px; height: 30px; color: var(--accent); }
.empty-title {
    font-family: var(--font-display); font-size: 20px; font-weight: 800;
    color: var(--text); margin-bottom: 8px; letter-spacing: -0.02em;
}
.empty-sub { font-size: 13.5px; color: var(--text3); max-width: 320px; margin: 0 auto 24px; line-height: 1.65; font-weight: 300; }

/* ══ OVERLAY ══ */
.overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 250; backdrop-filter: blur(3px); }
.overlay.show { display: block; }

/* ══ TOAST ══ */
.toast-wrap { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; pointer-events: none; }
.toast {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 16px; border-radius: 14px;
    font-size: 13px; font-weight: 500; color: #fff;
    min-width: 270px; max-width: 360px;
    box-shadow: var(--shadow-lg); pointer-events: all;
    animation: toastIn 0.35s cubic-bezier(0.4,0,0.2,1) both;
}
.toast-success { background: linear-gradient(135deg, #059669, #10b981); }
.toast-error   { background: linear-gradient(135deg, #dc2626, #ef4444); }
.toast svg { width: 16px; height: 16px; flex-shrink: 0; }
.toast-close { margin-left: auto; width: 20px; height: 20px; border-radius: 5px; background: rgba(255,255,255,0.2); border: none; cursor: pointer; color: #fff; font-size: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

@keyframes toastIn { from { opacity: 0; transform: translateX(20px) scale(0.96); } to { opacity: 1; transform: translateX(0) scale(1); } }

/* ══ RESPONSIVE ══ */
@media (max-width: 900px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); box-shadow: 20px 0 60px rgba(0,0,0,0.4); }
    .main { margin-left: 0; }
    .hamburger { display: flex; }
    .body { padding: 20px 20px 60px; }
    .stats-row { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 560px) {
    .blog-grid { grid-template-columns: 1fr; }
    .stats-row { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .filter-bar { flex-direction: column; align-items: stretch; }
    .search-wrap { max-width: 100%; }
}
</style>
</head>
<body>

<div class="toast-wrap" id="toastWrap"></div>
<div class="overlay" id="overlay"></div>

@php
    $blogTotal     = $blogs->count();
    $blogPublished = $blogs->where('status', 'published')->count();
    $blogDraft     = $blogs->where('status', 'draft')->count();
    $blogPending   = $blogs->where('status', 'pending')->count();
    $blogRejected  = $blogs->where('status', 'rejected')->count();
@endphp

<div class="shell">

{{-- ══ SIDEBAR ══ --}}
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
        <div class="s-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
        <div style="overflow:hidden;">
            <div class="s-user-name">{{ auth()->user()->name ?? 'User' }}</div>
            <div class="s-user-role">Fundraiser</div>
        </div>
    </div>

    <div class="s-section">
        <div class="s-label">Navigation</div>
        <a href="{{ url('/user/dashboard') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="{{ route('campaign.create') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Campaign
        </a>
    </div>

    <div class="s-divider"></div>

    <div class="s-section">
        <div class="s-label">Blogs</div>
        <a href="{{ url('/user/dashboard/blogs') }}" class="s-link active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            My Blogs
            @if($blogTotal > 0)<span class="s-badge">{{ $blogTotal }}</span>@endif
        </a>
        <a href="{{ url('/user/dashboard/blogs/create') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Write a Blog
        </a>
    </div>

    @if($blogTotal > 0)
    <div class="s-sub">
        @if($blogPublished > 0)
        <span class="s-sub-link" data-filter="published">
            <span class="s-dot"></span> Published
            <span class="s-count" style="color:var(--green)">{{ $blogPublished }}</span>
        </span>
        @endif
        @if($blogPending > 0)
        <span class="s-sub-link" data-filter="pending">
            <span class="s-dot"></span> In Review
            <span class="s-count" style="color:var(--yellow)">{{ $blogPending }}</span>
        </span>
        @endif
        @if($blogDraft > 0)
        <span class="s-sub-link" data-filter="draft">
            <span class="s-dot"></span> Drafts
            <span class="s-count" style="color:var(--text3)">{{ $blogDraft }}</span>
        </span>
        @endif
        @if($blogRejected > 0)
        <span class="s-sub-link" data-filter="rejected">
            <span class="s-dot"></span> Rejected
            <span class="s-count" style="color:var(--red)">{{ $blogRejected }}</span>
        </span>
        @endif
    </div>
    @endif

    <div class="s-bottom">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault();document.getElementById('__lf').submit();"
           class="s-link s-signout">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
        <form id="__lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>
</aside>

{{-- ══ MAIN ══ --}}
<div class="main">

    <header class="topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <div class="topbar-title">My Blogs</div>
                <div class="topbar-sub" id="topbarSub">{{ $blogTotal }} post{{ $blogTotal !== 1 ? 's' : '' }} total</div>
            </div>
        </div>
        <div class="topbar-right">
            <div class="dark-toggle" title="Toggle dark mode">
                <input type="checkbox" id="themeToggle">
                <label for="themeToggle">
                    <div class="dark-icons">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    </div>
                </label>
            </div>
            <div class="t-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
        </div>
    </header>

    <div class="body">

        {{-- Page Header --}}
        <div class="page-hdr">
            <div>
                <div class="page-hdr-title">My Blogs</div>
                <div class="page-hdr-sub" id="subLabel">{{ $blogTotal }} post{{ $blogTotal !== 1 ? 's' : '' }} total</div>
            </div>
            <a href="{{ url('/user/dashboard/blogs/create') }}" class="btn-new">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Write a Blog
            </a>
        </div>

        {{-- Stats Row --}}
        <div class="stats-row">
            <div class="stat-card" data-filter="all"
                 style="--sc-color:#5b5ef4;--sc-bg:rgba(91,94,244,0.08);">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <div>
                    <div class="stat-val">{{ $blogTotal }}</div>
                    <div class="stat-lbl">Total Posts</div>
                </div>
            </div>
            <div class="stat-card" data-filter="published"
                 style="--sc-color:#10b981;--sc-bg:rgba(16,185,129,0.08);">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="stat-val" style="color:var(--green)">{{ $blogPublished }}</div>
                    <div class="stat-lbl">Published</div>
                </div>
            </div>
            <div class="stat-card" data-filter="pending"
                 style="--sc-color:#f59e0b;--sc-bg:rgba(245,158,11,0.08);">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="stat-val" style="color:var(--yellow)">{{ $blogPending }}</div>
                    <div class="stat-lbl">In Review</div>
                </div>
            </div>
            <div class="stat-card" data-filter="draft"
                 style="--sc-color:#8b5cf6;--sc-bg:rgba(139,92,246,0.08);">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <div class="stat-val" style="color:var(--accent2)">{{ $blogDraft }}</div>
                    <div class="stat-lbl">Drafts</div>
                </div>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="filter-bar">
            <div class="ftabs">
                <button class="ftab on" data-filter="all">All <span class="cnt">{{ $blogTotal }}</span></button>
                <button class="ftab" data-filter="published">Published <span class="cnt">{{ $blogPublished }}</span></button>
                <button class="ftab" data-filter="pending">In Review <span class="cnt">{{ $blogPending }}</span></button>
                <button class="ftab" data-filter="draft">Drafts <span class="cnt">{{ $blogDraft }}</span></button>
                @if($blogRejected > 0)
                <button class="ftab" data-filter="rejected">Rejected <span class="cnt">{{ $blogRejected }}</span></button>
                @endif
            </div>

            <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/></svg>
                <input type="text" class="search-input" id="searchInput" placeholder="Search your blogs…">
            </div>
        </div>

        {{-- Blog Grid --}}
        <div class="blog-grid" id="blogGrid">
            @forelse($blogs as $blog)
            <div class="blog-card"
                 data-status="{{ $blog->status }}"
                 data-title="{{ strtolower($blog->title) }}"
                 data-excerpt="{{ strtolower($blog->excerpt ?? '') }}">

                <div class="blog-cover">
                    @if($blog->cover_image)
                        <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="{{ $blog->title }}" loading="lazy">
                    @else
                        <div class="cover-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                    @endif
                    <span class="status-badge s-{{ $blog->status }}">
                        {{ $blog->status === 'published' ? '● Published' : ucfirst($blog->status) }}
                    </span>
                    @if($blog->category ?? false)
                    <span class="cat-chip">{{ $blog->category->name }}</span>
                    @endif
                </div>

                <div class="blog-body">
                    <div class="blog-title">{{ $blog->title }}</div>
                    @if($blog->excerpt)
                    <div class="blog-excerpt">{{ $blog->excerpt }}</div>
                    @endif
                    <div class="blog-footer">
                        <span class="blog-date">{{ $blog->created_at->format('d M Y') }}</span>
                        <div class="blog-actions">
                            @if(in_array($blog->status, ['draft','rejected']))
                            <a href="{{ route('user.blogs.edit', $blog) }}" class="action-btn btn-edit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            @endif
                            <a href="{{ route('user.blogs.show', $blog) }}" class="action-btn btn-view">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
        </div>

        {{-- Empty State --}}
        <div class="empty-state" id="emptyState">
            <div class="empty-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <div class="empty-title" id="emptyTitle">No blogs yet</div>
            <p class="empty-sub" id="emptySub">Start writing your first blog post to share your story with the world.</p>
            <a href="{{ url('/user/dashboard/blogs/create') }}" class="btn-new" id="emptyBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Write Your First Blog
            </a>
        </div>

    </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

<script>
/* ── Theme ── */
(function(){
    var html   = document.documentElement;
    var toggle = document.getElementById('themeToggle');
    var saved  = localStorage.getItem('db_theme') || 'light';
    if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
    toggle.addEventListener('change', function(){
        var t = this.checked ? 'dark' : 'light';
        html.setAttribute('data-theme', t);
        localStorage.setItem('db_theme', t);
    });
})();

/* ── Sidebar / Hamburger ── */
var sidebar = document.getElementById('sidebar');
var overlay = document.getElementById('overlay');
document.getElementById('hamburger').addEventListener('click', function(){
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
});
overlay.addEventListener('click', function(){
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
});

/* ── Filter & Search ── */
var currentFilter = 'all';
var currentSearch = '';

var emptyTitles = {
    all:'No blogs yet', published:'No published blogs',
    pending:'No blogs in review', draft:'No draft blogs', rejected:'No rejected blogs'
};
var emptySubs = {
    all:'Start writing your first blog post to share your story with the world.',
    published:'Your published blogs will appear here once approved.',
    pending:'Submit a blog to have it reviewed by the admin team.',
    draft:'Save a blog as draft and come back to finish it later.',
    rejected:'Edit and resubmit any rejected blogs.'
};

function applyFilter(filter, search) {
    currentFilter = filter || currentFilter;
    currentSearch = (search !== undefined) ? search : currentSearch;

    var cards   = document.querySelectorAll('#blogGrid .blog-card');
    var visible = 0;

    cards.forEach(function(card) {
        var matchFilter = currentFilter === 'all' || card.dataset.status === currentFilter;
        var matchSearch = !currentSearch ||
            card.dataset.title.includes(currentSearch) ||
            card.dataset.excerpt.includes(currentSearch);
        var show = matchFilter && matchSearch;
        card.classList.toggle('hidden', !show);
        if (show) visible++;
    });

    /* Tab active */
    document.querySelectorAll('.ftab').forEach(function(b){
        b.classList.toggle('on', b.dataset.filter === currentFilter);
    });

    /* Stat card active */
    document.querySelectorAll('.stat-card[data-filter]').forEach(function(c){
        c.classList.toggle('active-filter', c.dataset.filter === currentFilter);
    });

    /* Sidebar sub active */
    document.querySelectorAll('.s-sub-link[data-filter]').forEach(function(l){
        l.classList.toggle('active', l.dataset.filter === currentFilter);
    });

    /* Subtitle */
    var labels = { all:'total', published:'published', pending:'in review', draft:'drafts', rejected:'rejected' };
    var txt = visible + ' post' + (visible !== 1 ? 's' : '') + ' ' + (labels[currentFilter] || '');
    document.getElementById('subLabel').textContent = txt;
    document.getElementById('topbarSub').textContent = txt;

    /* Empty state */
    var empty = document.getElementById('emptyState');
    document.getElementById('emptyTitle').textContent = emptyTitles[currentFilter] || emptyTitles.all;
    document.getElementById('emptySub').textContent   = emptySubs[currentFilter]   || emptySubs.all;
    document.getElementById('emptyBtn').style.display = currentFilter === 'all' ? '' : 'none';
    empty.style.display = visible === 0 ? 'block' : 'none';
}

/* Wire tabs, stat cards, sidebar sub-links */
document.querySelectorAll('[data-filter]').forEach(function(el){
    el.addEventListener('click', function(){ applyFilter(this.dataset.filter); });
});

/* Search */
document.getElementById('searchInput').addEventListener('input', function(){
    applyFilter(null, this.value.trim().toLowerCase());
});

/* Init */
document.addEventListener('DOMContentLoaded', function(){
    applyFilter('all', '');

    /* Stagger card animations */
    document.querySelectorAll('.blog-card').forEach(function(card, i){
        card.style.animationDelay = (i * 0.06) + 's';
    });
});

/* ── Toast ── */
function showToast(type, msg) {
    var icon = type === 'success'
        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    var t = document.createElement('div');
    t.className = 'toast toast-' + type;
    t.innerHTML = icon + '<span>' + msg + '</span><button class="toast-close" onclick="this.parentElement.remove()">✕</button>';
    document.getElementById('toastWrap').appendChild(t);
    setTimeout(function(){ t.remove(); }, 4500);
}

@if(session('success'))
    document.addEventListener('DOMContentLoaded', function(){ showToast('success', '{{ addslashes(session('success')) }}'); });
@endif
@if(session('error'))
    document.addEventListener('DOMContentLoaded', function(){ showToast('error', '{{ addslashes(session('error')) }}'); });
@endif
</script>

</body>
</html>