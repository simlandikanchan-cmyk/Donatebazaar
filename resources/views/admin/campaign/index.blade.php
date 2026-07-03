<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>All Campaigns — DonateBazaar Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
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
  --pink:#ec4899;--pink-lt:rgba(236,72,153,.10);
  --gray:#6b7280;
  --font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;
  --r:18px;--r-sm:12px;--r-xs:8px;
  --sh:0 1px 3px rgba(0,0,0,.05),0 4px 24px rgba(0,0,0,.04);
  --sh-md:0 4px 20px rgba(0,0,0,.08),0 1px 4px rgba(0,0,0,.04);
  --sh-lg:0 6px 20px rgba(0,0,0,.16);
  --ease:.18s ease;--sb-w:268px;
  --radius:14px;--radius-sm:9px;
  --shadow:var(--sh);--shadow-lg:var(--sh-lg);--tr:.2s ease;
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
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;}
body{font-family:var(--font);background:var(--bg);color:var(--text);line-height:1.55;-webkit-font-smoothing:antialiased;overflow-x:hidden;transition:background .2s,color .2s;font-size:14px;}
a{text-decoration:none;color:inherit;}
button{cursor:pointer;font-family:var(--font);}
svg{display:block;flex-shrink:0;}
.shell{display:flex;min-height:100vh;}
.sidebar{width:var(--sb-w);flex-shrink:0;background:var(--sb-bg);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:400;overflow-y:auto;overflow-x:hidden;border-right:1px solid var(--sb-border);box-shadow:2px 0 16px rgba(0,0,0,.06);transition:transform .3s cubic-bezier(.4,0,.2,1);}
.sidebar::-webkit-scrollbar{width:0;}
.s-logo{display:flex;align-items:center;gap:12px;padding:26px 22px 22px;border-bottom:1px solid var(--sb-border);}
.s-logo-mark{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--a),var(--a2));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 18px rgba(110,86,247,.4);}
.s-logo-mark svg{width:20px;height:20px;color:#fff;}
.s-logo-name{font-family:var(--mono);font-size:17px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1.1;}
.s-logo-tag{font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.16em;font-family:var(--mono);}
.s-admin-pill{margin:14px 12px 4px;padding:10px 14px;background:linear-gradient(135deg,rgba(110,86,247,.08),rgba(155,109,255,.05));border:1px solid rgba(110,86,247,.15);border-radius:var(--r-sm);display:flex;align-items:center;gap:10px;}
.s-av{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
.s-admin-name{font-size:12.5px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.s-admin-role{font-size:10px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.s-online{width:7px;height:7px;border-radius:50%;background:var(--green);margin-left:auto;flex-shrink:0;box-shadow:0 0 0 2.5px rgba(5,196,138,.2);}
.s-section{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.18em;padding:20px 22px 6px;font-family:var(--mono);}
.s-nav{padding:2px 10px;}
.s-link{display:flex;align-items:center;gap:11px;padding:9px 12px;border-radius:var(--r-xs);color:var(--sb-txt);font-size:13px;font-weight:500;transition:background var(--ease),color var(--ease);margin-bottom:1px;border:none;background:transparent;width:100%;cursor:pointer;position:relative;font-family:var(--font);}
.s-link:hover{background:var(--a-lt);color:var(--a);}
.s-link.active{background:var(--sb-act);color:var(--a);font-weight:600;}
.s-link.active::before{content:'';position:absolute;left:0;top:22%;bottom:22%;width:3px;border-radius:0 3px 3px 0;background:var(--a);}
.s-ico{width:15px;height:15px;flex-shrink:0;opacity:.65;}
.s-link:hover .s-ico,.s-link.active .s-ico{opacity:1;}
.s-divider{height:1px;background:var(--sb-border);margin:10px 18px;}
.s-bottom{margin-top:auto;padding:10px 10px 20px;border-top:1px solid var(--sb-border);}
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;}
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 24px;height:62px;background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100;gap:14px;flex-shrink:0;}
.topbar-left{display:flex;align-items:center;gap:10px;}
.topbar-title h1{font-family:var(--mono);font-size:17px;font-weight:800;color:var(--text);letter-spacing:-.02em;}
.topbar-title p{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--mono);}
.topbar-right{display:flex;align-items:center;gap:7px;}
.icon-btn{width:34px;height:34px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--text2);transition:background var(--ease),color var(--ease),border-color var(--ease);}
.icon-btn:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);}
.icon-btn svg{width:14px;height:14px;}
.theme-toggle{position:relative;}
.theme-toggle input{position:absolute;opacity:0;width:0;height:0;}
.theme-toggle label{display:flex;align-items:center;justify-content:space-between;width:52px;height:28px;border-radius:100px;background:var(--surface2);border:1px solid var(--border2);cursor:pointer;padding:4px;position:relative;}
.theme-toggle label::after{content:'';width:18px;height:18px;border-radius:50%;background:var(--a);position:absolute;left:5px;transition:transform .3s cubic-bezier(.4,0,.2,1);box-shadow:0 2px 6px rgba(110,86,247,.4);}
.theme-toggle input:checked+label::after{transform:translateX(23px);}
.theme-icons{display:flex;justify-content:space-between;width:100%;position:relative;z-index:1;padding:0 2px;}
.theme-icons svg{width:11px;height:11px;color:var(--text3);}
.top-avatar{width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;font-family:var(--mono);cursor:pointer;flex-shrink:0;box-shadow:0 2px 10px rgba(110,86,247,.38);overflow:hidden;}
.hamburger{display:none;width:34px;height:34px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);align-items:center;justify-content:center;color:var(--text2);flex-shrink:0;}
.hamburger svg{width:15px;height:15px;}
.body{padding:22px 24px 60px;flex:1;}
.toast-container{position:fixed;top:18px;right:18px;z-index:9999;display:flex;flex-direction:column;gap:9px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:9px;padding:12px 14px;border-radius:12px;font-size:12.5px;font-weight:500;color:#fff;min-width:260px;max-width:360px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s cubic-bezier(.4,0,.2,1) both;}
.toast-success{background:linear-gradient(135deg,#059669,#10b981);}
.toast-error{background:linear-gradient(135deg,#dc2626,#ef4444);}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-x{margin-left:auto;width:17px;height:17px;border-radius:4px;background:rgba(255,255,255,.2);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}
.flash-success{background:rgba(16,185,129,.09);border:1px solid rgba(16,185,129,.25);color:#065f46;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.flash-error{background:rgba(239,68,68,.09);border:1px solid rgba(239,68,68,.25);color:#dc2626;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
[data-theme="dark"] .flash-success{color:#34d399;}[data-theme="dark"] .flash-error{color:#f87171;}
.page-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;}
.page-head h2{font-family:var(--mono);font-size:20px;font-weight:800;color:var(--text);}
.page-head p{font-size:12px;color:var(--text3);margin-top:3px;font-family:var(--mono);}
.stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:10px;margin-bottom:20px;}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px 16px;box-shadow:var(--sh);display:flex;align-items:center;gap:12px;}
.stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:16px;height:16px;}
.stat-num{font-family:var(--mono);font-size:22px;font-weight:800;color:var(--text);line-height:1;}
.stat-lbl{font-size:10.5px;color:var(--text3);margin-top:3px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--sh);overflow:hidden;}
.card-body{padding:0;}
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;font-size:13px;}
thead{background:var(--surface2);}
th{text-align:left;padding:11px 16px;font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);border-bottom:1px solid var(--border);white-space:nowrap;}
td{padding:12px 16px;border-bottom:1px solid var(--border);color:var(--text2);vertical-align:middle;}
tr:hover td{background:var(--surface2);}
tr:last-child td{border-bottom:none;}
.cell-title{font-weight:600;color:var(--text);font-family:var(--mono);font-size:12.5px;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:block;}
.cell-sub{font-size:10.5px;color:var(--text3);margin-top:2px;font-family:var(--mono);}
.badge{display:inline-flex;align-items:center;gap:4px;font-size:9.5px;font-weight:700;padding:3px 8px;border-radius:100px;text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);white-space:nowrap;}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
.b-pending{background:rgba(245,158,11,.15);color:#b45309;border:1px solid rgba(245,158,11,.30);}
.b-active{background:rgba(16,185,129,.15);color:#065f46;border:1px solid rgba(16,185,129,.30);}
.b-paused{background:rgba(99,102,241,.15);color:#3730a3;border:1px solid rgba(99,102,241,.30);}
.b-rejected{background:rgba(239,68,68,.15);color:#991b1b;border:1px solid rgba(239,68,68,.30);}
.b-expired{background:rgba(107,114,128,.15);color:#374151;border:1px solid rgba(107,114,128,.30);}
.b-completed{background:rgba(59,130,246,.15);color:#1e40af;border:1px solid rgba(59,130,246,.30);}
[data-theme="dark"] .b-pending{color:#fbbf24;}[data-theme="dark"] .b-active{color:#34d399;}[data-theme="dark"] .b-paused{color:#a5b4fc;}[data-theme="dark"] .b-rejected{color:#f87171;}[data-theme="dark"] .b-expired{color:#9ca3af;}[data-theme="dark"] .b-completed{color:#93c5fd;}
.actions-cell{display:flex;align-items:center;gap:4px;}
.action-link{display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:6px;font-size:11px;font-weight:600;transition:background var(--ease),color var(--ease);white-space:nowrap;}
.action-link:hover{background:var(--a-lt);color:var(--a);}
.action-link svg{width:12px;height:12px;}
.pagination-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-top:1px solid var(--border);flex-wrap:wrap;gap:10px;}
.pagination-info{font-size:12px;color:var(--text3);}
.pagination-links{display:flex;gap:4px;}
.pagination-links a,.pagination-links span{display:inline-flex;align-items:center;justify-content:center;min-width:30px;height:30px;padding:0 6px;border-radius:6px;font-size:12px;font-weight:600;border:1px solid var(--border2);background:var(--surface2);color:var(--text2);transition:all var(--ease);}
.pagination-links a:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);}
.pagination-links .active{background:var(--a);color:#fff;border-color:var(--a);}
.empty-state{padding:48px 20px;text-align:center;}
.empty-state svg{width:36px;height:36px;color:var(--text3);opacity:.25;margin:0 auto 10px;display:block;}
.empty-state p{font-size:13px;color:var(--text3);}
@keyframes toastIn{from{opacity:0;transform:translateX(20px) scale(.96);}to{opacity:1;transform:none;}}
@media(max-width:860px){.sidebar{transform:translateX(-100%);}.sidebar.open{transform:translateX(0);}.main{margin-left:0;}.hamburger{display:flex;}.body{padding:14px 14px 60px;}.stats-row{grid-template-columns:repeat(2,1fr);}}
@media(max-width:600px){.topbar{padding:0 14px;}.stats-row{grid-template-columns:1fr;}}
</style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<div class="shell">

@php
$stateColors = [
    'active'    => ['class' => 'b-active',    'label' => 'Active'],
    'pending'   => ['class' => 'b-pending',   'label' => 'Pending'],
    'paused'    => ['class' => 'b-paused',    'label' => 'Paused'],
    'rejected'  => ['class' => 'b-rejected',  'label' => 'Rejected'],
    'expired'   => ['class' => 'b-expired',   'label' => 'Expired'],
    'completed' => ['class' => 'b-completed', 'label' => 'Completed'],
];
@endphp

{{-- SIDEBAR --}}
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
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:9px;">
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
        <a href="{{ url('/admin/dashboard') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
    </nav>

    <div class="s-section">Campaigns</div>
    <nav class="s-nav">
        <a href="{{ route('admin.campaign.index') }}" class="s-link active">
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
    </nav>

    <div class="s-section">Job Board</div>
    <nav class="s-nav">
        <a href="{{ route('admin.job_posts.index') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            All Job Posts
        </a>
        <a href="{{ route('admin.job_posts.create') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Post a Job
        </a>
        <a href="{{ route('admin.job_post_applications.index') }}" class="s-link">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
            Job Applicants
        </a>
    </nav>

    <div class="s-divider"></div>

    <div class="s-bottom">
        <a href="{{ url('/admin/profile') }}" class="s-link" style="color:var(--a);margin-bottom:2px;">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            My Profile
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('lf').submit();" class="s-link" style="color:var(--red);">
            <svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
        <form id="lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>
</aside>

{{-- MAIN --}}
<div class="main">
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="topbar-title">
                <h1>All Campaigns</h1>
                <p>Manage and review all fundraiser campaigns</p>
            </div>
        </div>
        <div class="topbar-right">
            <button class="icon-btn" title="Notifications">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </button>
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
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:9px;">
                @else
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                @endif
            </div>
        </div>
    </header>

    <div class="body">

        @if(session('success'))
        <div class="flash-success">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flash-error">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif

        <div class="page-head">
            <div>
                <h2>Campaigns</h2>
                <p>{{ $campaigns->total() }} total campaigns</p>
            </div>
        </div>

        {{-- Stats row --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--a-lt);color:var(--a);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div><div class="stat-num">{{ $cntActive }}</div><div class="stat-lbl">Active</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--amber-lt);color:var(--amber);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div><div class="stat-num">{{ $cntPending }}</div><div class="stat-lbl">Pending</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(99,102,241,.12);color:#6366f1;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div><div class="stat-num">{{ $cntPaused }}</div><div class="stat-lbl">Paused</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--red-lt);color:var(--red);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div><div class="stat-num">{{ $cntRejected }}</div><div class="stat-lbl">Rejected</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(107,114,128,.12);color:var(--gray);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div><div class="stat-num">{{ $cntExpired }}</div><div class="stat-lbl">Expired</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--blue-lt);color:var(--blue);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div><div class="stat-num">{{ $cntCompleted }}</div><div class="stat-lbl">Completed</div></div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-body">
                @if($campaigns->count() > 0)
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Campaign</th>
                                <th>User</th>
                                <th>Category</th>
                                <th>Goal</th>
                                <th>Raised</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($campaigns as $campaign)
                            <tr>
                                <td>
                                    <span class="cell-title">{{ Str::limit($campaign->title, 40) }}</span>
                                </td>
                                <td>
                                    <span class="cell-title" style="font-size:12px;">{{ $campaign->user?->name ?? '—' }}</span>
                                    <span class="cell-sub">{{ $campaign->user?->email ?? '' }}</span>
                                </td>
                                <td>
                                    <span class="cell-sub" style="font-size:11px;color:var(--text);font-weight:500;">{{ $campaign->category?->name ?? '—' }}</span>
                                </td>
                                <td>
                                    <span style="font-family:var(--mono);font-size:12px;font-weight:600;color:var(--text);">₹{{ number_format($campaign->goal_amount) }}</span>
                                </td>
                                <td>
                                    <span style="font-family:var(--mono);font-size:12px;font-weight:600;color:var(--green);">₹{{ number_format($campaign->raised_amount) }}</span>
                                </td>
                                <td>
                                    @php $s = $stateColors[$campaign->campaign_state] ?? ['class' => 'b-pending', 'label' => 'Unknown']; @endphp
                                    <span class="badge {{ $s['class'] }}">
                                        <span class="badge-dot"></span>
                                        {{ $s['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="cell-sub">{{ $campaign->created_at->format('d M Y') }}</span>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="action-link">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            View
                                        </a>
                                        <a href="{{ route('admin.campaign.edit', $campaign->id) }}" class="action-link">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrap">
                    <div class="pagination-info">
                        Showing {{ $campaigns->firstItem() }}–{{ $campaigns->lastItem() }} of {{ $campaigns->total() }}
                    </div>
                    <div class="pagination-links">
                        {{ $campaigns->onEachSide(1)->links() }}
                    </div>
                </div>
                @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <p>No campaigns found.</p>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

</div>

<script>
document.getElementById('hamburger')?.addEventListener('click', function(){
  document.getElementById('sidebar').classList.toggle('open');
});

var tc = document.getElementById('themeToggle');
var t = localStorage.getItem('admintheme');
if(t === 'dark'){ document.documentElement.setAttribute('data-theme','dark'); tc.checked = true; }
tc?.addEventListener('change', function(){
  var d = this.checked ? 'dark' : 'light';
  document.documentElement.setAttribute('data-theme', d);
  localStorage.setItem('admintheme', d);
});

@if(session('success'))
  showToast('{{ session('success') }}', 'toast-success');
@endif
@if(session('error'))
  showToast('{{ session('error') }}', 'toast-error');
@endif

function showToast(msg, cls){
  var el = document.createElement('div');
  el.className = 'toast ' + cls;
  el.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' +
    msg + '<button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
  document.getElementById('toastContainer').appendChild(el);
  setTimeout(function(){ el.remove(); }, 4000);
}
</script>

</body>
</html>