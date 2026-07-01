<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Recurring Donations — DonateBazaar</title>
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

/* SIDEBAR (identical to KYC dashboard) */
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

.status-chip{display:inline-flex;align-items:center;gap:6px;padding:4px 11px;border-radius:100px;font-size:10px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;font-family:var(--font-mono);white-space:nowrap;}
.status-chip .dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;}
.chip-active   { background:rgba(16,185,129,0.12); color:#10b981; border:1px solid rgba(16,185,129,0.25); }
.chip-paused   { background:rgba(245,158,11,0.12); color:#d97706; border:1px solid rgba(245,158,11,0.3); }
.chip-cancelled{ background:rgba(239,68,68,0.12);  color:#ef4444; border:1px solid rgba(239,68,68,0.25); }
[data-theme="dark"] .chip-paused{color:#fbbf24;}

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
.card-body{padding:18px;}

/* STAT GRID */
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:16px 18px;display:flex;align-items:center;gap:13px;animation:fadeUp .4s both;cursor:pointer;transition:border-color var(--tr),transform var(--tr);}
.stat-card:hover{transform:translateY(-1px);border-color:var(--border2);}
.stat-card.is-active{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:17px;height:17px;}
.ic-indigo{background:rgba(99,102,241,0.12);color:var(--accent);}
.ic-green{background:rgba(16,185,129,0.12);color:var(--green);}
.ic-yellow{background:rgba(245,158,11,0.12);color:var(--yellow);}
.ic-red{background:rgba(239,68,68,0.12);color:var(--red);}
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

/* RECURRING LIST */
.rd-list{display:flex;flex-direction:column;gap:10px;}
.rd-row{display:flex;align-items:center;gap:14px;padding:14px 16px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);box-shadow:var(--shadow);transition:border-color var(--tr),transform var(--tr);animation:fadeUp .4s both;}
.rd-row:hover{border-color:var(--border2);transform:translateY(-1px);}
.rd-avatar{width:42px;height:42px;border-radius:11px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.rd-avatar svg{width:18px;height:18px;}
.rd-info{flex:1;min-width:0;}
.rd-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-0.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.rd-meta{display:flex;align-items:center;gap:12px;margin-top:5px;flex-wrap:wrap;}
.rd-meta-item{font-size:11px;color:var(--text3);font-family:var(--font-mono);display:flex;align-items:center;gap:4px;}
.rd-meta-item svg{width:11px;height:11px;flex-shrink:0;}
.rd-meta-item strong{color:var(--text2);font-weight:600;}
.rd-amount{font-size:15px;font-weight:800;color:var(--accent);font-family:var(--font-mono);}
.rd-chips{display:flex;align-items:center;gap:7px;flex-shrink:0;}
.rd-actions{display:flex;align-items:center;gap:7px;flex-shrink:0;}
.rd-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:var(--radius-sm);font-size:11.5px;font-weight:600;font-family:var(--font);border:1px solid transparent;cursor:pointer;transition:opacity var(--tr),transform var(--tr);white-space:nowrap;}
.rd-btn:hover{opacity:0.86;transform:translateY(-1px);}
.rd-btn svg{width:12px;height:12px;}
.rd-btn-pause{background:var(--yellow);color:#fff;box-shadow:0 4px 14px rgba(245,158,11,0.25);}
.rd-btn-resume{background:var(--green);color:#fff;box-shadow:0 4px 14px rgba(16,185,129,0.25);}
.rd-btn-cancel{background:var(--surface2);color:var(--red);border-color:rgba(239,68,68,0.25);}
.rd-btn-cancel:hover{background:rgba(239,68,68,0.08);}

/* EMPTY STATES */
.empty-state{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;padding:60px 20px;text-align:center;}
.empty-state svg{width:48px;height:48px;color:var(--text3);opacity:0.25;}
.empty-state h3{font-size:15px;font-weight:700;color:var(--text2);}
.empty-state p{font-size:12px;color:var(--text3);max-width:320px;line-height:1.6;}
.empty-state .rd-btn{padding:10px 20px;font-size:12.5px;}

/* PAGINATION (Bootstrap paginator wrapped in our tokens) */
.rd-pagination{display:flex;justify-content:center;margin-top:22px;}
.rd-pagination :is(.pagination){display:flex;gap:6px;list-style:none;flex-wrap:wrap;}
.rd-pagination :is(.page-item .page-link){display:flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;border-radius:var(--radius-sm);border:1px solid var(--border2);background:var(--surface);color:var(--text2);font-size:12px;font-weight:600;font-family:var(--font-mono);transition:all var(--tr);}
.rd-pagination :is(.page-item .page-link:hover){border-color:var(--accent);color:var(--accent);}
.rd-pagination :is(.page-item.active .page-link){background:var(--accent);border-color:var(--accent);color:#fff;}
.rd-pagination :is(.page-item.disabled .page-link){opacity:.4;cursor:not-allowed;}

/* ANIMATIONS */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:none;}}
@keyframes toastIn{from{opacity:0;transform:translateX(20px) scale(0.96);}to{opacity:1;transform:none;}}

/* RESPONSIVE */
@media(max-width:960px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:860px){
    .sidebar{transform:translateX(-100%);}.sidebar.open{transform:translateX(0);}.main{margin-left:0;}.hamburger{display:flex;}.body{padding:14px 14px 60px;}
    .rd-row{flex-wrap:wrap;}
    .rd-info{flex-basis:100%;order:1;}
    .rd-chips{order:2;}
    .rd-actions{order:3;margin-left:auto;}
}
@media(max-width:600px){.topbar{padding:0 14px;}.stat-grid{grid-template-columns:1fr 1fr;}.filter-bar{flex-direction:column;align-items:stretch;}.search-wrap{min-width:0;}}
@media(max-width:480px){.stat-grid{grid-template-columns:1fr;}}
</style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<div class="shell">

{{-- ══════════════════════════════════════════
     SIDEBAR (identical structure/tokens to the KYC dashboard)
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

    <div class="s-label">Identity & KYC</div>
    <nav class="s-nav">
        <a href="{{ url('/user/kyc') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            KYC Status
        </a>
    </nav>

    {{-- ── Recurring Donations (active on this page) ── --}}
    <div class="s-label">Donations</div>
    <nav class="s-nav">
        <a href="{{ route('recurring.index') }}" class="s-link active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Recurring Donations
            @if($recurringCount > 0)<span class="s-badge ok">{{ $recurringCount }}</span>@endif
        </a>
    </nav>

    <div class="s-divider"></div>

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
                <h1>Recurring Donations</h1>
                <p>Track, manage and control all your recurring contribution plans</p>
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
            $rdAll       = $recurring->getCollection() ?? collect();
            $rdTotal     = $recurring->total();
            $rdActive    = $rdAll->where('status','active')->count();
            $rdPaused    = $rdAll->where('status','paused')->count();
            $rdCancelled = $rdAll->where('status','cancelled')->count();
        @endphp

        {{-- ══ STAT CARDS (double as filters) ══ --}}
        <div class="stat-grid">
            <div class="stat-card is-active" data-filter="all">
                <div class="stat-icon ic-indigo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $rdTotal }}</div>
                    <div class="stat-lbl">All Plans</div>
                </div>
            </div>
            <div class="stat-card" data-filter="active">
                <div class="stat-icon ic-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $rdActive }}</div>
                    <div class="stat-lbl">Active</div>
                </div>
            </div>
            <div class="stat-card" data-filter="paused">
                <div class="stat-icon ic-yellow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $rdPaused }}</div>
                    <div class="stat-lbl">Paused</div>
                </div>
            </div>
            <div class="stat-card" data-filter="cancelled">
                <div class="stat-icon ic-red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $rdCancelled }}</div>
                    <div class="stat-lbl">Cancelled</div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="toast toast-success" style="position:static;margin-bottom:16px;animation:none;max-width:none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="toast toast-error" style="position:static;margin-bottom:16px;animation:none;max-width:none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if($rdTotal > 0)
        {{-- ══ FILTER BAR ══ --}}
        <div class="filter-bar">
            <div class="filter-tabs" id="filterTabs">
                <button class="filter-tab active" data-filter="all">All</button>
                <button class="filter-tab" data-filter="active">Active</button>
                <button class="filter-tab" data-filter="paused">Paused</button>
                <button class="filter-tab" data-filter="cancelled">Cancelled</button>
            </div>
            <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-3.5-3.5"/></svg>
                <input type="text" class="search-input" id="searchInput" placeholder="Search by campaign…">
            </div>
        </div>
        @endif

        {{-- ══ RECURRING DONATIONS LIST ══ --}}
        @if($rdTotal > 0)
        <div class="rd-list" id="rdList">
            @foreach($recurring as $donation)
                <div class="rd-row" data-status="{{ $donation->status }}" data-title="{{ strtolower($donation->campaign->title ?? 'campaign') }}">

                    <div class="rd-avatar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-4.35-9-8.5C1 8 3.5 4 7.5 4c2.04 0 3.04 1 4.5 2.5C13.46 5 14.46 4 16.5 4 20.5 4 23 8 21 12.5 19 16.65 12 21 12 21z"/></svg>
                    </div>

                    <div class="rd-info">
                        <div class="rd-title">{{ $donation->campaign->title ?? 'Campaign' }}</div>
                        <div class="rd-meta">
                            <span class="rd-amount">₹{{ number_format($donation->amount, 2) }}</span>
                            <span class="rd-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <strong>{{ ucfirst($donation->frequency) }}</strong>
                            </span>
                            <span class="rd-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                {{ $donation->billing_count }} payment{{ $donation->billing_count == 1 ? '' : 's' }} made
                            </span>
                            @if($donation->status !== 'cancelled')
                            <span class="rd-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                Next: <strong>{{ optional($donation->next_billing_date)?->format('d M Y') ?? '—' }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="rd-chips">
                        <span class="status-chip chip-{{ $donation->status }}">
                            <span class="dot"></span>{{ ucfirst($donation->status) }}
                        </span>
                    </div>

                    <div class="rd-actions">
                        @if($donation->status === 'active')
                        <form action="{{ route('recurring.pause', $donation->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rd-btn rd-btn-pause">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                                Pause
                            </button>
                        </form>
                        @endif

                        @if($donation->status === 'paused')
                        <form action="{{ route('recurring.resume', $donation->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rd-btn rd-btn-resume">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                Resume
                            </button>
                        </form>
                        @endif

                        @if($donation->status !== 'cancelled')
                        <form action="{{ route('recurring.cancel', $donation->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rd-btn rd-btn-cancel" onclick="return confirm('Cancel this recurring donation?')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                Cancel
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($recurring->hasPages())
        <div class="rd-pagination">
            {{ $recurring->links() }}
        </div>
        @endif

        {{-- No-results state for filters/search (hidden by default) --}}
        <div class="empty-state" id="noResults" style="display:none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-3.5-3.5"/></svg>
            <h3>No matching plans</h3>
            <p>Try a different filter or search term.</p>
        </div>

        @else
        {{-- ══ NO RECURRING DONATIONS AT ALL ══ --}}
        <div class="card">
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-4.35-9-8.5C1 8 3.5 4 7.5 4c2.04 0 3.04 1 4.5 2.5C13.46 5 14.46 4 16.5 4 20.5 4 23 8 21 12.5 19 16.65 12 21 12 21z"/></svg>
                <h3>No Recurring Donations Yet</h3>
                <p>Start supporting campaigns with recurring contributions and see them tracked here.</p>
                <a href="/all-campaigns" class="rd-btn" style="background:var(--accent);color:#fff;box-shadow:0 4px 14px rgba(99,102,241,0.25);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    Explore Campaigns
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

/* ── Filtering + search (client-side, current page only — pairs with server pagination) ── */
var currentFilter = 'all';
var searchInput = document.getElementById('searchInput');
var rows = Array.prototype.slice.call(document.querySelectorAll('.rd-row'));
var noResults = document.getElementById('noResults');

function matchesFilter(status, filter) {
    if (filter === 'all') return true;
    return status === filter;
}

function applyFilters() {
    var term = (searchInput?.value || '').trim().toLowerCase();
    var visibleCount = 0;
    rows.forEach(function(row){
        var status = row.getAttribute('data-status');
        var title = row.getAttribute('data-title') || '';
        var show = matchesFilter(status, currentFilter) && title.indexOf(term) !== -1;
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
        setActiveStatCard(filter);
        applyFilters();
    });
});
searchInput?.addEventListener('input', applyFilters);
</script>
</body>
</html>