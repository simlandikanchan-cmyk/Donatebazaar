<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>KYC Verification — DonateBazaar</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
    --bg:#f4f5fb; --surface:#ffffff; --surface2:#f0f2fa;
    --border:rgba(0,0,0,0.07); --border2:rgba(0,0,0,0.11);
    --text:#0f1117; --text2:#4b5563; --text3:#9ca3af;
    --sidebar-bg:#0d0e1a; --sidebar-text:rgba(255,255,255,0.60);
    --sidebar-act:rgba(120,119,255,0.18);
    --accent:#6366f1; --accent2:#8b5cf6; --accent-glow:rgba(99,102,241,0.16);
    --green:#10b981; --yellow:#f59e0b; --red:#ef4444; --blue:#3b82f6;
    --font:'DM Sans',sans-serif; --font-mono:'DM Mono',monospace;
    --radius:14px; --radius-sm:9px;
    --shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.04);
    --shadow-lg:0 8px 40px rgba(0,0,0,0.12); --tr:0.2s ease;
}
[data-theme="dark"] {
    --bg:#0b0c14; --surface:#13141f; --surface2:#1a1b2e;
    --border:rgba(255,255,255,0.06); --border2:rgba(255,255,255,0.10);
    --text:#f0f1ff; --text2:#a5b4c8; --text3:#5a6579;
    --sidebar-bg:#07080f; --sidebar-text:rgba(255,255,255,0.50);
    --sidebar-act:rgba(120,119,255,0.22); --accent-glow:rgba(99,102,241,0.25);
    --shadow:0 1px 3px rgba(0,0,0,0.3),0 4px 16px rgba(0,0,0,0.2);
    --shadow-lg:0 8px 40px rgba(0,0,0,0.5);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;}
body{font-family:var(--font);background:var(--bg);color:var(--text);line-height:1.5;-webkit-font-smoothing:antialiased;font-size:14px;transition:background var(--tr),color var(--tr);overflow-x:hidden;}
a{text-decoration:none;color:inherit;} button{cursor:pointer;font-family:var(--font);} svg{display:block;flex-shrink:0;}

/* SHELL */
.shell{display:flex;min-height:100vh;}

/* SIDEBAR (full version, ported from dashboard) */
.sidebar{width:260px;flex-shrink:0;background:var(--sidebar-bg);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:300;overflow-y:auto;overflow-x:hidden;border-right:1px solid rgba(255,255,255,0.04);transition:transform .3s cubic-bezier(.4,0,.2,1);scrollbar-width:none;}
.sidebar::-webkit-scrollbar{display:none;}
.s-logo{display:flex;align-items:center;gap:11px;padding:24px 20px 20px;border-bottom:1px solid rgba(255,255,255,0.04);}
.s-logo-mark{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(99,102,241,0.4);flex-shrink:0;}
.s-logo-mark svg{width:19px;height:19px;color:#fff;}
.s-logo-name{font-size:16px;font-weight:700;color:#fff;letter-spacing:-0.02em;line-height:1.2;}
.s-logo-tag{font-size:9px;color:rgba(255,255,255,0.28);text-transform:uppercase;letter-spacing:0.14em;}

.s-user{margin:14px 12px 6px;padding:11px 13px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:var(--radius-sm);display:flex;align-items:center;gap:10px;}
.s-avatar{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
.s-avatar img{width:100%;height:100%;object-fit:cover;}
.s-user-name{font-size:12.5px;font-weight:600;color:rgba(255,255,255,0.88);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.s-user-role{font-size:10px;color:rgba(255,255,255,0.30);margin-top:1px;}
.s-user-dot{width:7px;height:7px;border-radius:50%;background:var(--green);margin-left:auto;flex-shrink:0;box-shadow:0 0 0 2px rgba(16,185,129,0.2);}

/* KYC Banner */
.kyc-banner{margin:8px 12px;padding:10px 12px;border-radius:var(--radius-sm);display:flex;align-items:flex-start;gap:9px;font-size:11.5px;line-height:1.4;}
.kyc-banner svg{width:14px;height:14px;flex-shrink:0;margin-top:1px;}
.kyc-banner-title{font-weight:700;font-size:11px;margin-bottom:2px;}
.kyc-banner a{font-weight:600;text-decoration:underline;}
.kyc-warn{background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.22);color:#fbbf24;}
.kyc-warn a{color:#fbbf24;}
.kyc-info{background:rgba(99,102,241,0.10);border:1px solid rgba(99,102,241,0.20);color:#a5b4fc;}
.kyc-info a{color:#a5b4fc;}
.kyc-ok{background:rgba(16,185,129,0.10);border:1px solid rgba(16,185,129,0.20);color:#6ee7b7;}
.kyc-error{background:rgba(239,68,68,0.10);border:1px solid rgba(239,68,68,0.20);color:#f87171;}
.kyc-error a{color:#f87171;}

.s-label{font-size:9px;font-weight:700;color:rgba(255,255,255,0.20);text-transform:uppercase;letter-spacing:0.16em;padding:18px 20px 6px;font-family:var(--font-mono);}
.s-nav{padding:2px 10px;}
.s-link{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:7px;color:var(--sidebar-text);font-size:13px;font-weight:500;transition:background var(--tr),color var(--tr);margin-bottom:1px;border:none;background:transparent;width:100%;text-align:left;position:relative;white-space:nowrap;font-family:var(--font);cursor:pointer;}
.s-link:hover{background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.88);}
.s-link.active{background:var(--sidebar-act);color:#a5b4fc;}
.s-link.active::before{content:'';position:absolute;left:0;top:25%;bottom:25%;width:3px;border-radius:0 3px 3px 0;background:var(--accent);}
.s-icon{width:15px;height:15px;opacity:0.75;flex-shrink:0;}
.s-link.active .s-icon{opacity:1;}
.s-badge{margin-left:auto;font-size:10px;font-weight:700;padding:2px 7px;border-radius:100px;background:rgba(99,102,241,0.22);color:#a5b4fc;font-family:var(--font-mono);}
.s-badge.ok{background:rgba(16,185,129,0.18);color:#34d399;}
.s-badge.warn{background:rgba(245,158,11,0.18);color:#fbbf24;}
.s-badge.info{background:rgba(59,130,246,0.18);color:#60a5fa;}
.s-badge.err{background:rgba(239,68,68,0.18);color:#f87171;}

.s-sub{padding:2px 10px 2px 28px;}
.s-sub-link{display:flex;align-items:center;gap:8px;padding:6px 10px;border-radius:7px;color:rgba(255,255,255,0.35);font-size:12px;font-weight:500;transition:all var(--tr);margin-bottom:1px;}
.s-sub-link:hover{background:rgba(255,255,255,0.04);color:rgba(255,255,255,0.75);}
.s-sub-dot{width:4px;height:4px;border-radius:50%;background:currentColor;flex-shrink:0;opacity:0.6;}
.s-divider{height:1px;background:rgba(255,255,255,0.04);margin:10px 16px;}
.s-bottom{margin-top:auto;padding:10px 10px 18px;border-top:1px solid rgba(255,255,255,0.04);}

/* MAIN */
.main{margin-left:260px;flex:1;min-width:0;display:flex;flex-direction:column;}

/* TOPBAR */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 24px;height:60px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100;gap:14px;flex-shrink:0;}
.topbar-left{display:flex;align-items:center;gap:10px;}
.topbar-back{display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid var(--border2);background:var(--surface2);color:var(--text2);transition:all var(--tr);flex-shrink:0;}
.topbar-back:hover{background:var(--accent-glow);color:var(--accent);border-color:var(--accent);}
.topbar-back svg{width:13px;height:13px;}
.topbar-title h1{font-size:17px;font-weight:800;color:var(--text);letter-spacing:-0.02em;}
.topbar-title p{font-size:11px;color:var(--text3);margin-top:1px;}
.topbar-right{display:flex;align-items:center;gap:7px;}
.icon-btn{width:34px;height:34px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--text2);position:relative;transition:background var(--tr),color var(--tr),border-color var(--tr);}
.icon-btn:hover{background:var(--accent-glow);color:var(--accent);border-color:var(--accent);}
.icon-btn svg{width:14px;height:14px;}
.theme-toggle{position:relative;}
.theme-toggle input{position:absolute;opacity:0;width:0;height:0;}
.theme-toggle label{display:flex;align-items:center;justify-content:space-between;width:50px;height:26px;border-radius:100px;background:var(--surface2);border:1px solid var(--border2);cursor:pointer;padding:3px 4px;position:relative;}
.theme-toggle label::after{content:'';width:18px;height:18px;border-radius:50%;background:var(--accent);position:absolute;left:4px;transition:transform 0.3s cubic-bezier(.4,0,.2,1);box-shadow:0 2px 6px rgba(99,102,241,0.4);}
.theme-toggle input:checked+label::after{transform:translateX(22px);}
.theme-icons{display:flex;justify-content:space-between;width:100%;position:relative;z-index:1;}
.theme-icons svg{width:11px;height:11px;color:var(--text3);}
.top-avatar{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;overflow:hidden;}
.top-avatar img{width:100%;height:100%;object-fit:cover;}
.hamburger{display:none;width:34px;height:34px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);align-items:center;justify-content:center;color:var(--text2);flex-shrink:0;}
.hamburger svg{width:15px;height:15px;}

/* BODY */
.body{padding:22px 24px 60px;flex:1;}

/* BADGES / CHIPS */
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3px 9px;border-radius:100px;text-transform:uppercase;letter-spacing:0.06em;font-family:var(--font-mono);white-space:nowrap;}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
.b-pending{background:rgba(245,158,11,0.15);color:#b45309;border:1px solid rgba(245,158,11,0.30);}
.b-approved{background:rgba(16,185,129,0.15);color:#065f46;border:1px solid rgba(16,185,129,0.30);}
.b-rejected{background:rgba(239,68,68,0.15);color:#991b1b;border:1px solid rgba(239,68,68,0.30);}
.b-none{background:var(--surface2);color:var(--text3);border:1px solid var(--border2);}
[data-theme="dark"] .b-pending{color:#fbbf24;} [data-theme="dark"] .b-approved{color:#34d399;} [data-theme="dark"] .b-rejected{color:#f87171;}

.kyc-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-size:10.5px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;font-family:var(--font-mono);white-space:nowrap;}
.kyc-chip-none     { background:var(--surface2); color:var(--text3); border:1px solid var(--border2); }
.kyc-chip-pending  { background:rgba(245,158,11,0.12); color:#f59e0b; border:1px solid rgba(245,158,11,0.3); }
.kyc-chip-approved { background:rgba(16,185,129,0.12); color:#10b981; border:1px solid rgba(16,185,129,0.3); }
.kyc-chip-rejected { background:rgba(239,68,68,0.12);  color:#ef4444; border:1px solid rgba(239,68,68,0.3); }

.status-chip{display:inline-flex;align-items:center;gap:6px;padding:4px 11px;border-radius:100px;font-size:10px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;font-family:var(--font-mono);white-space:nowrap;}
.status-chip .dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;}
.chip-active  { background:rgba(16,185,129,0.12); color:#10b981; border:1px solid rgba(16,185,129,0.25); }
.chip-paused  { background:rgba(99,102,241,0.12); color:#818cf8; border:1px solid rgba(99,102,241,0.25); }
.chip-pending { background:rgba(16,185,129,0.12); color:#10b981; border:1px solid rgba(16,185,129,0.3); }
.chip-rejected{ background:rgba(239,68,68,0.12);  color:#ef4444; border:1px solid rgba(239,68,68,0.25); }

/* TOAST */
.toast-container{position:fixed;top:18px;right:18px;z-index:9999;display:flex;flex-direction:column;gap:9px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:9px;padding:12px 14px;border-radius:12px;font-size:12.5px;font-weight:500;color:#fff;min-width:260px;max-width:360px;box-shadow:var(--shadow-lg);pointer-events:all;animation:toastIn .3s cubic-bezier(.4,0,.2,1) both;}
.toast-success{background:linear-gradient(135deg,#059669,#10b981);}
.toast-error{background:linear-gradient(135deg,#dc2626,#ef4444);}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-x{margin-left:auto;width:17px;height:17px;border-radius:4px;background:rgba(255,255,255,0.2);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}

/* CARD */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;animation:fadeUp .4s both;}
.card+.card{margin-top:16px;}
.card-header{padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;}
.card-header-left{display:flex;align-items:center;gap:10px;}
.card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:14px;height:14px;}
.ic-indigo{background:rgba(99,102,241,0.12);color:var(--accent);}
.ic-green{background:rgba(16,185,129,0.12);color:var(--green);}
.ic-yellow{background:rgba(245,158,11,0.12);color:var(--yellow);}
.ic-red{background:rgba(239,68,68,0.12);color:var(--red);}
.card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-0.01em;}
.card-sub{font-size:11px;color:var(--text3);margin-top:1px;}
.card-body{padding:18px;}

/* STAT GRID */
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:16px 18px;display:flex;align-items:center;gap:13px;animation:fadeUp .4s both;cursor:pointer;transition:border-color var(--tr),transform var(--tr);}
.stat-card:hover{transform:translateY(-1px);border-color:var(--border2);}
.stat-card.is-active{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:17px;height:17px;}
.stat-num{font-size:21px;font-weight:800;color:var(--text);letter-spacing:-0.02em;line-height:1.1;}
.stat-lbl{font-size:10.5px;color:var(--text3);font-family:var(--font-mono);text-transform:uppercase;letter-spacing:0.06em;margin-top:2px;}

/* FILTER BAR */
.filter-bar{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:14px;}
.filter-tabs{display:flex;align-items:center;gap:5px;flex-wrap:wrap;}
.filter-tab{padding:7px 13px;border-radius:100px;font-size:11.5px;font-weight:600;font-family:var(--font);border:1px solid var(--border2);background:var(--surface);color:var(--text2);transition:all var(--tr);white-space:nowrap;}
.filter-tab:hover{border-color:var(--accent);color:var(--accent);}
.filter-tab.active{background:var(--accent);border-color:var(--accent);color:#fff;}
.search-wrap{position:relative;min-width:220px;flex-shrink:0;}
.search-wrap svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text3);}
.search-input{width:100%;padding:8px 12px 8px 32px;border-radius:100px;border:1.5px solid var(--border2);background:var(--surface);color:var(--text);font-family:var(--font);font-size:12.5px;outline:none;transition:border-color var(--tr);}
.search-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.search-input::placeholder{color:var(--text3);}

/* CAMPAIGN LIST */
.campaign-list{display:flex;flex-direction:column;gap:10px;}
.campaign-row{display:flex;align-items:center;gap:14px;padding:14px 16px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);box-shadow:var(--shadow);transition:border-color var(--tr),transform var(--tr);animation:fadeUp .4s both;}
.campaign-row:hover{border-color:var(--border2);transform:translateY(-1px);}
.row-avatar{width:42px;height:42px;border-radius:11px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;font-size:15px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.row-info{flex:1;min-width:0;}
.row-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-0.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.row-meta{display:flex;align-items:center;gap:8px;margin-top:3px;flex-wrap:wrap;}
.row-meta-item{font-size:11px;color:var(--text3);font-family:var(--font-mono);display:flex;align-items:center;gap:4px;}
.row-meta-item svg{width:11px;height:11px;}
.row-chips{display:flex;align-items:center;gap:7px;flex-shrink:0;}
.row-actions{display:flex;align-items:center;gap:7px;flex-shrink:0;}
.row-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:var(--radius-sm);font-size:11.5px;font-weight:600;font-family:var(--font);border:1px solid transparent;cursor:pointer;transition:opacity var(--tr),transform var(--tr);white-space:nowrap;}
.row-btn:hover{opacity:0.86;transform:translateY(-1px);}
.row-btn svg{width:12px;height:12px;}
.row-btn-accent{background:var(--accent);color:#fff;box-shadow:0 4px 14px rgba(99,102,241,0.25);}
.row-btn-yellow{background:var(--yellow);color:#fff;box-shadow:0 4px 14px rgba(245,158,11,0.25);}
.row-btn-ghost{background:var(--surface2);color:var(--text2);border-color:var(--border2);}
.row-link-icon{width:32px;height:32px;border-radius:8px;border:1px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--text2);transition:all var(--tr);flex-shrink:0;}
.row-link-icon:hover{background:var(--accent-glow);color:var(--accent);border-color:var(--accent);}
.row-link-icon svg{width:13px;height:13px;}

/* EMPTY STATES */
.empty-state{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;padding:60px 20px;text-align:center;}
.empty-state svg{width:48px;height:48px;color:var(--text3);opacity:0.25;}
.empty-state h3{font-size:15px;font-weight:700;color:var(--text2);}
.empty-state p{font-size:12px;color:var(--text3);max-width:320px;line-height:1.6;}
.empty-state .row-btn{padding:10px 20px;font-size:12.5px;}

/* ANIMATIONS */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:none;}}
@keyframes toastIn{from{opacity:0;transform:translateX(20px) scale(0.96);}to{opacity:1;transform:none;}}

/* RESPONSIVE */
@media(max-width:960px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:860px){
    .sidebar{transform:translateX(-100%);}.sidebar.open{transform:translateX(0);}.main{margin-left:0;}.hamburger{display:flex;}.body{padding:14px 14px 60px;}
    .campaign-row{flex-wrap:wrap;}
    .row-info{flex-basis:100%;order:1;}
    .row-chips{order:2;}
    .row-actions{order:3;margin-left:auto;}
}
@media(max-width:600px){.topbar{padding:0 14px;}.stat-grid{grid-template-columns:1fr 1fr;}.filter-bar{flex-direction:column;align-items:stretch;}.search-wrap{min-width:0;}}
@media(max-width:480px){.stat-grid{grid-template-columns:1fr;}}
</style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<div class="shell">

{{-- ══════════════════════════════════════════
     SIDEBAR (full version — ported from dashboard)
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
    @php $sidebarKyc = auth()->user()->kycVerification; @endphp
    @if(!$sidebarKyc)
        <div class="kyc-banner kyc-warn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <div>
                <div class="kyc-banner-title">KYC Required</div>
                Submit documents so campaigns go live.
                <br><a href="{{ url('/user/kyc') }}">Submit KYC →</a>
            </div>
        </div>
    @elseif($sidebarKyc->status === 'pending')
        <div class="kyc-banner kyc-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <div>
                <div class="kyc-banner-title">KYC Under Review</div>
                Campaigns live once KYC is approved.
            </div>
        </div>
    @elseif($sidebarKyc->status === 'approved')
        <div class="kyc-banner kyc-ok">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><div class="kyc-banner-title">KYC Verified ✓</div>Your account is fully verified.</div>
        </div>
    @elseif($sidebarKyc->status === 'rejected')
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
        <a href="{{ url('/user/dashboard') }}" class="s-link">
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
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Active
            <span class="s-badge ok">{{ $countActive }}</span>
        </a>
        @endif
        @if($countPending > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Pending
            <span class="s-badge warn">{{ $countPending }}</span>
        </a>
        @endif
        @if($countPaused > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Paused
            <span class="s-badge">{{ $countPaused }}</span>
        </a>
        @endif
        @if($countRejected > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Rejected
            <span class="s-badge err">{{ $countRejected }}</span>
        </a>
        @endif
        @if($countExpired > 0)
        <a href="{{ url('/user/dashboard') }}#cGrid" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Expired
            <span class="s-badge">{{ $countExpired }}</span>
        </a>
        @endif
    </nav>

    {{-- ── KYC Navigation (active on this page) ── --}}
    <div class="s-label">Identity & KYC</div>
    <nav class="s-nav">
        @if(!$sidebarKyc || $sidebarKyc->status === 'rejected')
        <a href="{{ url('/user/kyc') }}" class="s-link active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Submit KYC
            <span class="s-badge warn">Action needed</span>
        </a>
        @else
        <a href="{{ url('/user/kyc') }}" class="s-link active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            KYC Status
            <span class="s-badge {{ $sidebarKyc->status === 'approved' ? 'ok' : 'warn' }}">{{ ucfirst($sidebarKyc->status) }}</span>
        </a>
        @endif
    </nav>

    {{-- ── Recurring Donations ── --}}
    <div class="s-label">Donations</div>
    <nav class="s-nav">
        <a href="{{ route('recurring.index') }}" class="s-link">
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
        <a href="{{ url('/user/dashboard/blogs') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            My Blogs
            @if($blogTotal > 0)<span class="s-badge">{{ $blogTotal }}</span>@endif
        </a>
        <a href="{{ url('/user/dashboard/blogs/create') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Write a Blog
        </a>
    </nav>
    @if($blogTotal > 0)
    <div class="s-sub">
        @if($blogPublished > 0)
        <a href="{{ url('/user/dashboard/blogs?status=approved') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>Published
            <span style="margin-left:auto;font-size:10px;color:var(--green);font-family:var(--font-mono);">{{ $blogPublished }}</span>
        </a>
        @endif
        @if($blogDraft > 0)
        <a href="{{ url('/user/dashboard/blogs?status=draft') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>Drafts
            <span style="margin-left:auto;font-size:10px;color:var(--yellow);font-family:var(--font-mono);">{{ $blogDraft }}</span>
        </a>
        @endif
        @if($blogPending > 0)
        <a href="{{ url('/user/dashboard/blogs?status=pending') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>In Review
            <span style="margin-left:auto;font-size:10px;color:var(--text3);font-family:var(--font-mono);">{{ $blogPending }}</span>
        </a>
        @endif
    </div>
    @endif

    <div class="s-bottom">
        <a href="{{ route('profile.show') }}" class="s-link" style="color:rgba(165,180,252,0.75);margin-bottom:2px;">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            My Profile
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('lf').submit();" class="s-link" style="color:rgba(248,113,113,0.75);">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
        <form id="lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>

</aside>

{{-- ══ MAIN ══ --}}
<div class="main">

    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="{{ url('/user/dashboard') }}" class="topbar-back" title="Back to Dashboard">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </a>
            <div class="topbar-title">
                <h1>KYC Verification</h1>
                <p>Manage identity verification across all your campaigns</p>
            </div>
        </div>
        <div class="topbar-right">
            <div class="theme-toggle" title="Toggle dark mode">
                <input type="checkbox" id="themeToggle">
                <label for="themeToggle">
                    <div class="theme-icons">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    </div>
                </label>
            </div>
            <div class="top-avatar">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
                @else
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                @endif
            </div>
        </div>
    </header>

    <div class="body">

        @php
            $total              = $campaigns->count();
            $verifiedCount      = $campaigns->filter(fn($c) => optional($c->kyc)->status === 'approved')->count();
            $pendingCount       = $campaigns->filter(fn($c) => optional($c->kyc)->status === 'pending')->count();
            $rejectedCount      = $campaigns->filter(fn($c) => optional($c->kyc)->status === 'rejected')->count();
            $notSubmittedCount  = $campaigns->filter(fn($c) => !$c->kyc)->count();
        @endphp

        {{-- ══ STAT CARDS (double as filters) ══ --}}
        <div class="stat-grid">
            <div class="stat-card is-active" data-filter="all">
                <div class="stat-icon ic-indigo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $total }}</div>
                    <div class="stat-lbl">All Campaigns</div>
                </div>
            </div>
            <div class="stat-card" data-filter="approved">
                <div class="stat-icon ic-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $verifiedCount }}</div>
                    <div class="stat-lbl">Verified</div>
                </div>
            </div>
            <div class="stat-card" data-filter="pending">
                <div class="stat-icon ic-yellow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $pendingCount }}</div>
                    <div class="stat-lbl">Pending Review</div>
                </div>
            </div>
            <div class="stat-card" data-filter="action">
                <div class="stat-icon ic-red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $rejectedCount + $notSubmittedCount }}</div>
                    <div class="stat-lbl">Needs Attention</div>
                </div>
            </div>
        </div>

        @if($total > 0)
        {{-- ══ FILTER BAR ══ --}}
        <div class="filter-bar">
            <div class="filter-tabs" id="filterTabs">
                <button class="filter-tab active" data-filter="all">All</button>
                <button class="filter-tab" data-filter="approved">Verified</button>
                <button class="filter-tab" data-filter="pending">Pending</button>
                <button class="filter-tab" data-filter="rejected">Rejected</button>
                <button class="filter-tab" data-filter="none">Not Submitted</button>
            </div>
            <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-3.5-3.5"/></svg>
                <input type="text" class="search-input" id="searchInput" placeholder="Search campaigns…">
            </div>
        </div>
        @endif

        {{-- ══ CAMPAIGN LIST ══ --}}
        @if($total > 0)
        <div class="campaign-list" id="campaignList">
            @foreach($campaigns as $campaign)
                @php
                    $kyc = $campaign->kyc;
                    $kStatus = $kyc->status ?? 'none';
                @endphp
                <div class="campaign-row" data-kyc="{{ $kStatus }}" data-title="{{ strtolower($campaign->title) }}">
                    <div class="row-avatar">{{ strtoupper(substr($campaign->title, 0, 1)) }}</div>

                    <div class="row-info">
                        <div class="row-title">{{ $campaign->title }}</div>
                        <div class="row-meta">
                            <span class="row-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $campaign->created_at->format('d M Y') }}
                            </span>
                            @if($kyc)
                            <span class="row-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5"/></svg>
                                Submitted {{ $kyc->created_at->diffForHumans() }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row-chips">
                        <span class="status-chip chip-{{ in_array($campaign->campaign_state, ['approved','live','active']) ? 'active' : ($campaign->campaign_state ?? 'pending') }}">
                            <span class="dot"></span>{{ ucfirst($campaign->campaign_state ?? 'Draft') }}
                        </span>
                        <span class="kyc-chip kyc-chip-{{ $kStatus }}">
                            @if($kStatus === 'none') Not Submitted
                            @elseif($kStatus === 'pending') Pending
                            @elseif($kStatus === 'approved') ✓ Verified
                            @else ✗ Rejected
                            @endif
                        </span>
                    </div>

                    <div class="row-actions">
                        @if($kStatus === 'none')
                            <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="row-btn row-btn-accent">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload KYC
                            </a>
                        @elseif($kStatus === 'rejected')
                            <a href="{{ route('kyc.upload.form', $campaign->id) }}" class="row-btn row-btn-yellow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Re-upload
                            </a>
                            <a href="{{ route('kyc.view', $campaign->id) }}" class="row-link-icon" title="View Submission">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        @else
                            <a href="{{ route('kyc.view', $campaign->id) }}" class="row-btn row-btn-ghost">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ $kStatus === 'pending' ? 'View Submission' : 'View Documents' }}
                            </a>
                        @endif
                        <a href="{{ route('campaign.show', $campaign->id) }}" class="row-link-icon" title="Campaign Overview">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- No-results state for filters/search (hidden by default) --}}
        <div class="empty-state" id="noResults" style="display:none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-3.5-3.5"/></svg>
            <h3>No matching campaigns</h3>
            <p>Try a different filter or search term.</p>
        </div>

        @else
        {{-- ══ NO CAMPAIGNS AT ALL ══ --}}
        <div class="card">
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <h3>No campaigns yet</h3>
                <p>Create your first campaign to start the KYC verification process and get approved for fundraising.</p>
                <a href="{{ route('campaign.create') }}" class="row-btn row-btn-accent">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Create Campaign
                </a>
            </div>
        </div>
        @endif

    </div>{{-- /body --}}
</div>{{-- /main --}}
</div>{{-- /shell --}}

<script>
/* ── Dark mode ── */
var html = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved = localStorage.getItem('theme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
    var t = this.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', t);
    localStorage.setItem('theme', t);
});

/* ── Hamburger ── */
var sidebar = document.getElementById('sidebar');
document.getElementById('hamburger')?.addEventListener('click', function(){ sidebar.classList.toggle('open'); });
document.addEventListener('click', function(e){
    if (window.innerWidth <= 860 && !sidebar.contains(e.target) && !document.getElementById('hamburger')?.contains(e.target))
        sidebar.classList.remove('open');
});

/* ── Filtering + search ── */
var currentFilter = 'all';
var searchInput = document.getElementById('searchInput');
var rows = Array.prototype.slice.call(document.querySelectorAll('.campaign-row'));
var noResults = document.getElementById('noResults');

function matchesFilter(kyc, filter) {
    if (filter === 'all') return true;
    if (filter === 'action') return kyc === 'rejected' || kyc === 'none';
    return kyc === filter;
}

function applyFilters() {
    var term = (searchInput?.value || '').trim().toLowerCase();
    var visibleCount = 0;
    rows.forEach(function(row){
        var kyc = row.getAttribute('data-kyc');
        var title = row.getAttribute('data-title') || '';
        var show = matchesFilter(kyc, currentFilter) && title.indexOf(term) !== -1;
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    if (noResults) noResults.style.display = (visibleCount === 0 && rows.length > 0) ? 'flex' : 'none';
}

function setActiveStatCard(filter) {
    document.querySelectorAll('.stat-card').forEach(function(card){
        card.classList.toggle('is-active', card.getAttribute('data-filter') === filter);
    });
}
function setActiveTab(filter) {
    document.querySelectorAll('.filter-tab').forEach(function(tab){
        tab.classList.toggle('active', tab.getAttribute('data-filter') === filter);
    });
}

document.querySelectorAll('.stat-card').forEach(function(card){
    card.addEventListener('click', function(){
        var filter = this.getAttribute('data-filter');
        currentFilter = filter;
        setActiveStatCard(filter);
        setActiveTab(filter);
        applyFilters();
    });
});
document.querySelectorAll('.filter-tab').forEach(function(tab){
    tab.addEventListener('click', function(){
        var filter = this.getAttribute('data-filter');
        currentFilter = filter;
        setActiveTab(filter);
        setActiveStatCard(filter === 'rejected' || filter === 'none' ? 'action' : filter);
        applyFilters();
    });
});
searchInput?.addEventListener('input', applyFilters);

/* ── Toast flash ── */
@if(session('success'))
    window.addEventListener('DOMContentLoaded', function(){
        var t = document.createElement('div');
        t.className = 'toast toast-success';
        t.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>{{ addslashes(session('success')) }}</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(function(){ t.remove(); }, 4500);
    });
@endif
@if(session('error'))
    window.addEventListener('DOMContentLoaded', function(){
        var t = document.createElement('div');
        t.className = 'toast toast-error';
        t.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>{{ addslashes(session('error')) }}</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(function(){ t.remove(); }, 4500);
    });
@endif
</script>
</body>
</html>