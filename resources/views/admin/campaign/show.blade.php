@push('page_css')
@vite('resources/css/admin/entries/campaigns.css')
@endpush

@extends('layouts.admin')

@section('sidebar_campaigns', 'active')

@section('page_title', Str::limit($campaign->title, 38))
@section('page_subtitle', 'Campaign details')

@push('page_styles')
<style>
.page-grid{display:grid;grid-template-columns:1fr 308px;gap:20px;align-items:start;}
.right-col{position:sticky;top:80px;display:flex;flex-direction:column;gap:16px;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;}
.card+.card{margin-top:16px;}
.card-header{padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;}
.card-header-left{display:flex;align-items:center;gap:10px;}
.card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:14px;height:14px;}
.ic-indigo{background:var(--a-lt);color:var(--a);}
.ic-green{background:var(--green-lt);color:var(--green);}
.ic-yellow{background:var(--amber-lt);color:var(--amber);}
.ic-pink{background:var(--pink-lt);color:var(--pink);}
.ic-red{background:var(--red-lt);color:var(--red);}
.ic-blue{background:var(--blue-lt);color:var(--blue);}
.ic-amber{background:rgba(245,158,11,.12);color:#b45309;}
.card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-.01em;font-family:var(--font);}
.card-sub{font-size:11px;color:var(--text3);margin-top:1px;}
.card-body{padding:18px;}
.cover-wrap{position:relative;overflow:hidden;}
.cover-wrap img{width:100%;height:320px;object-fit:cover;display:block;transition:transform .6s ease;}
.cover-wrap:hover img{transform:scale(1.02);}
.cover-placeholder{width:100%;height:240px;background:var(--surface2);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;}
.cover-placeholder svg{width:36px;height:36px;color:var(--text3);opacity:.3;}
.cover-placeholder span{font-size:12px;color:var(--text3);}
.cover-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.55) 0%,transparent 55%);pointer-events:none;}
.cover-meta{position:absolute;bottom:14px;left:16px;right:16px;display:flex;align-items:flex-end;justify-content:space-between;}
.cover-title{font-family:var(--font);font-size:18px;font-weight:800;color:#fff;letter-spacing:-.02em;line-height:1.3;text-shadow:0 1px 4px rgba(0,0,0,.4);}
.cover-created{font-size:10.5px;color:rgba(255,255,255,.6);font-family:var(--mono);margin-top:3px;}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
.b-pending  {background:rgba(245,158,11,.15);color:#b45309;border:1px solid rgba(245,158,11,.30);}
.b-active   {background:rgba(16,185,129,.15);color:#065f46;border:1px solid rgba(16,185,129,.30);}
.b-approved {background:rgba(16,185,129,.15);color:#065f46;border:1px solid rgba(16,185,129,.30);}
.b-rejected {background:rgba(239,68,68,.15);color:#991b1b;border:1px solid rgba(239,68,68,.30);}
.b-paused   {background:rgba(99,102,241,.15);color:#3730a3;border:1px solid rgba(99,102,241,.30);}
.b-expired  {background:rgba(107,114,128,.15);color:#374151;border:1px solid rgba(107,114,128,.30);}
.b-completed{background:rgba(59,130,246,.15);color:#1e40af;border:1px solid rgba(59,130,246,.30);}
[data-theme="dark"] .b-pending  {color:#fbbf24;}[data-theme="dark"] .b-active{color:#34d399;}[data-theme="dark"] .b-approved{color:#34d399;}[data-theme="dark"] .b-rejected{color:#f87171;}[data-theme="dark"] .b-paused{color:#a5b4fc;}[data-theme="dark"] .b-expired{color:#9ca3af;}[data-theme="dark"] .b-completed{color:#93c5fd;}
.prog-numbers{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:9px;}
.prog-raised{font-size:26px;font-weight:800;color:var(--a);letter-spacing:-.03em;font-family:var(--mono);line-height:1;}
.prog-goal{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.prog-bar{width:100%;background:var(--surface2);border-radius:100px;height:5px;overflow:hidden;margin-bottom:5px;border:1px solid var(--border);}
.prog-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--a),var(--a2));transition:width 1.2s ease;}
.prog-pct{font-size:10.5px;color:var(--text3);font-family:var(--mono);}
.mini-stats{display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-top:14px;}
.mini-stat{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:11px;text-align:center;}
.mini-stat-val{font-size:17px;font-weight:800;color:var(--text);font-family:var(--mono);line-height:1;}
.mini-stat-lbl{font-size:9.5px;color:var(--text3);margin-top:4px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.desc-text{font-size:13.5px;color:var(--text2);line-height:1.75;}
.kyc-notice{border-radius:var(--r-sm);padding:11px 13px;font-size:12.5px;margin-bottom:12px;}
.kyc-notice-red   {background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#dc2626;}
.kyc-notice-yellow{background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);color:#b45309;}
.kyc-notice-green {background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);color:#065f46;}
[data-theme="dark"] .kyc-notice-red{color:#f87171;}[data-theme="dark"] .kyc-notice-yellow{color:#fbbf24;}[data-theme="dark"] .kyc-notice-green{color:#34d399;}
.kyc-notice-title{font-weight:700;margin-bottom:3px;font-size:11.5px;font-family:var(--mono);}
.kyc-notice-body{font-size:11.5px;opacity:.9;}
.kyc-doc-row{display:flex;align-items:center;gap:10px;padding:10px 12px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);margin-bottom:12px;}
.kyc-doc-icon{width:32px;height:32px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:15px;}
.kyc-doc-type{font-size:12px;font-weight:700;color:var(--text);}
.kyc-doc-num{font-size:10.5px;color:var(--text3);font-family:var(--mono);}
.kyc-doc-preview{border:1px solid var(--border2);border-radius:var(--r-sm);overflow:hidden;background:var(--surface2);margin-top:4px;}
.kyc-doc-preview-header{display:flex;align-items:center;justify-content:space-between;padding:9px 12px;border-bottom:1px solid var(--border);font-size:11.5px;font-weight:700;color:var(--text2);font-family:var(--mono);}
.kyc-doc-preview-header-left{display:flex;align-items:center;gap:6px;}
.kyc-doc-preview-actions{display:flex;gap:6px;}
.kyc-doc-btn{display:inline-flex;align-items:center;gap:4px;font-size:10.5px;font-weight:600;padding:3px 9px;border-radius:6px;font-family:var(--font);transition:background var(--ease);border:none;cursor:pointer;text-decoration:none;}
.kyc-doc-btn-view{background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.20);}
.kyc-doc-btn-view:hover{background:rgba(37,99,235,.20);}
.kyc-doc-btn-dl{background:var(--green-lt);color:var(--green);border:1px solid rgba(5,196,138,.20);}
.kyc-doc-btn-dl:hover{background:rgba(5,196,138,.20);}
.kyc-doc-btn svg{width:10px;height:10px;flex-shrink:0;}
.kyc-doc-preview-img{width:100%;max-height:340px;object-fit:contain;display:block;cursor:zoom-in;}
.kyc-doc-preview-iframe{width:100%;height:340px;border:none;display:block;}
.kyc-doc-preview-fallback{padding:20px;text-align:center;font-size:12px;color:var(--text3);}
.kyc-pill{display:flex;align-items:center;justify-content:space-between;padding:9px 12px;border-radius:var(--r-sm);font-size:12px;font-weight:600;margin-bottom:11px;}
.kyc-pill-pending {background:rgba(245,158,11,.10);border:1px solid rgba(245,158,11,.25);color:#b45309;}
.kyc-pill-approved{background:rgba(16,185,129,.10);border:1px solid rgba(16,185,129,.25);color:#065f46;}
.kyc-pill-rejected{background:rgba(239,68,68,.10);border:1px solid rgba(239,68,68,.25);color:#dc2626;}
.kyc-pill-none    {background:rgba(239,68,68,.10);border:1px solid rgba(239,68,68,.25);color:#dc2626;}
[data-theme="dark"] .kyc-pill-pending{color:#fbbf24;}[data-theme="dark"] .kyc-pill-approved{color:#34d399;}[data-theme="dark"] .kyc-pill-rejected{color:#f87171;}[data-theme="dark"] .kyc-pill-none{color:#f87171;}
.info-row{display:flex;justify-content:space-between;align-items:center;font-size:12px;padding:9px 0;}
.info-row+.info-row{border-top:1px solid var(--border);}
.info-row-lbl{color:var(--text3);font-family:var(--mono);letter-spacing:.04em;font-size:10.5px;}
.status-section{padding:16px;}
.status-section-label{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;font-family:var(--mono);margin-bottom:10px;}
.status-chips{display:flex;align-items:center;gap:7px;margin-bottom:12px;flex-wrap:wrap;}
.status-chip-lg{display:inline-flex;align-items:center;gap:5px;padding:5px 13px;border-radius:100px;font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;font-family:var(--mono);}
.chip-active   {background:rgba(16,185,129,.12);color:#10b981;border:1px solid rgba(16,185,129,.25);}
.chip-paused   {background:rgba(37,99,235,.12);color:#818cf8;border:1px solid rgba(37,99,235,.25);}
.chip-pending  {background:rgba(245,158,11,.12);color:#f59e0b;border:1px solid rgba(245,158,11,.25);}
.chip-rejected {background:rgba(239,68,68,.12);color:#ef4444;border:1px solid rgba(239,68,68,.25);}
.chip-expired  {background:rgba(107,114,128,.12);color:#6b7280;border:1px solid rgba(107,114,128,.25);}
.chip-completed{background:rgba(59,130,246,.12);color:#3b82f6;border:1px solid rgba(59,130,246,.25);}
.chip-dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;}
.action-btn{display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:10px 16px;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;cursor:pointer;border:1px solid transparent;font-family:var(--font);transition:opacity var(--ease),transform var(--ease),box-shadow var(--ease);text-decoration:none;letter-spacing:.01em;}
.action-btn:hover{opacity:.88;transform:translateY(-1px);}
.action-btn svg{width:13px;height:13px;}
.action-btn+.action-btn{margin-top:8px;}
.btn-accent{background:var(--a);color:#fff;border-color:var(--a);box-shadow:0 4px 14px rgba(37,99,235,.28);}
.btn-green {background:var(--green);color:#fff;border-color:var(--green);box-shadow:0 4px 14px rgba(5,196,138,.28);}
.btn-red   {background:rgba(240,68,68,.1);color:#b91c1c;border-color:rgba(240,68,68,.25);}
.btn-yellow{background:rgba(245,158,11,.08);color:var(--amber);border-color:rgba(245,158,11,.3);}
.btn-ghost {background:var(--surface2);color:var(--text2);border-color:var(--border2);}
.btn-disabled{background:var(--surface2);color:var(--text3);border-color:var(--border);opacity:.5;cursor:not-allowed;transform:none !important;box-shadow:none !important;}
[data-theme="dark"] .btn-red{color:#f87171;}
.events-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:13px;}
.event-card{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:15px;transition:transform var(--ease),box-shadow var(--ease),border-color var(--ease);}
.event-card:hover{transform:translateY(-3px);box-shadow:0 10px 32px rgba(37,99,235,.10);border-color:rgba(37,99,235,.2);}
.event-badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3px 8px;border-radius:100px;text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);margin-bottom:9px;}
.ev-approved{background:rgba(16,185,129,.12);color:#10b981;border:1px solid rgba(16,185,129,.25);}
.ev-pending {background:rgba(245,158,11,.12);color:#f59e0b;border:1px solid rgba(245,158,11,.25);}
.ev-default {background:rgba(107,114,128,.10);color:var(--text3);border:1px solid var(--border2);}
.event-title{font-size:13px;font-weight:700;color:var(--text);margin-bottom:3px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-family:var(--font);}
.event-date{font-size:11px;color:var(--text3);font-family:var(--mono);margin-bottom:7px;}
.event-desc{font-size:12px;color:var(--text2);line-height:1.6;margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.event-link{font-size:11px;font-weight:600;color:var(--a);text-decoration:none;display:inline-flex;align-items:center;gap:4px;}
.event-link:hover{opacity:.75;}
.empty-state{padding:36px 20px;text-align:center;background:var(--surface2);border-radius:var(--r-sm);}
.empty-state svg{width:32px;height:32px;color:var(--text3);opacity:.25;margin:0 auto 9px;display:block;}
.empty-state p{font-size:12.5px;color:var(--text3);}
.flash-success{background:rgba(16,185,129,.09);border:1px solid rgba(16,185,129,.25);color:#065f46;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.flash-error  {background:rgba(239,68,68,.09);border:1px solid rgba(239,68,68,.25);color:#dc2626;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
[data-theme="dark"] .flash-success{color:#34d399;}[data-theme="dark"] .flash-error{color:#f87171;}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:500;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px);opacity:0;pointer-events:none;transition:opacity var(--ease);}
.modal-overlay.show{opacity:1;pointer-events:all;}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:var(--r);padding:24px;max-width:380px;width:90%;box-shadow:var(--sh-lg);transform:scale(.95);transition:transform var(--ease);}
.modal-overlay.show .modal{transform:scale(1);}
.modal-title{font-family:var(--font);font-size:16px;font-weight:800;color:var(--text);margin-bottom:7px;}
.modal-body{font-size:13px;color:var(--text2);line-height:1.6;margin-bottom:18px;}
.modal-actions{display:flex;gap:8px;}
.modal-actions .action-btn{flex:1;margin:0;}
.kyc-docs-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;}
@media(max-width:640px){.kyc-docs-grid{grid-template-columns:1fr;}}
.kyc-doc-tile{border:1px solid var(--border2);border-radius:var(--r-sm);overflow:hidden;background:var(--surface2);}
.kyc-doc-tile-header{display:flex;align-items:center;justify-content:space-between;padding:9px 12px;border-bottom:1px solid var(--border);background:var(--surface);}
.kyc-doc-tile-label{display:flex;align-items:center;gap:6px;font-size:11px;font-weight:700;color:var(--text2);font-family:var(--mono);}
.kyc-doc-tile-label svg{width:12px;height:12px;}
.kyc-doc-tile-img{width:100%;height:160px;object-fit:cover;display:block;cursor:zoom-in;transition:opacity .2s;}
.kyc-doc-tile-img:hover{opacity:.85;}
.kyc-doc-tile-pdf{width:100%;height:160px;border:none;display:block;}
.kyc-doc-tile-missing{height:90px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;}
.kyc-doc-tile-missing svg{width:22px;height:22px;color:var(--text3);opacity:.3;}
.kyc-doc-tile-missing span{font-size:11px;color:var(--text3);}
.kyc-doc-tile-actions{display:flex;gap:6px;padding:8px 10px;border-top:1px solid var(--border);}
.kyc-selfie-wrap{display:flex;gap:12px;align-items:flex-start;margin-bottom:16px;}
.kyc-selfie-img{width:120px;height:120px;object-fit:cover;border-radius:var(--r-sm);border:2px solid var(--border2);flex-shrink:0;cursor:zoom-in;}
.kyc-selfie-missing{width:120px;height:120px;background:var(--surface2);border:1px dashed var(--border2);border-radius:var(--r-sm);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;flex-shrink:0;}
.kyc-selfie-missing svg{width:24px;height:24px;color:var(--text3);opacity:.3;}
.kyc-selfie-missing span{font-size:10px;color:var(--text3);}
.kyc-selfie-info{flex:1;}
.kyc-selfie-title{font-size:12px;font-weight:700;color:var(--text);font-family:var(--mono);margin-bottom:6px;}
.kyc-selfie-sub{font-size:11px;color:var(--text3);line-height:1.6;}
.bank-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
@media(max-width:640px){.bank-grid{grid-template-columns:1fr;}}
.bank-field{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:10px 12px;}
.bank-field-lbl{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;font-family:var(--mono);margin-bottom:4px;}
.bank-field-val{font-size:13px;font-weight:600;color:var(--text);font-family:var(--mono);}
.bank-field-val.empty{color:var(--text3);font-style:italic;font-family:var(--font);font-weight:400;font-size:12px;}
.updates-list{display:flex;flex-direction:column;gap:10px;}
.update-item{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:13px 15px;transition:border-color var(--tr);}
.update-item:hover{border-color:rgba(37,99,235,.2);}
.update-item-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:5px;}
.update-item-title{font-size:13px;font-weight:700;color:var(--text);font-family:var(--font);}
.update-item-date{font-size:10px;color:var(--text3);font-family:var(--mono);}
.update-item-body{font-size:12.5px;color:var(--text2);line-height:1.65;margin-bottom:8px;}
.update-doc-pill{display:inline-flex;align-items:center;gap:5px;background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.15);border-radius:100px;padding:3px 10px;font-size:10.5px;font-weight:600;text-decoration:none;font-family:var(--mono);transition:background var(--ease);}
.update-doc-pill:hover{background:rgba(37,99,235,.18);}
.update-doc-pill svg{width:10px;height:10px;}
@media(max-width:960px){.page-grid{grid-template-columns:1fr;}.right-col{position:static;}}
@media(max-width:600px){.events-grid{grid-template-columns:1fr;}}

/* ── Top toolbar / breadcrumb ── */
.detail-toolbar{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:18px;}
.crumbs{display:flex;align-items:center;gap:7px;font-size:12px;color:var(--text3);font-family:var(--mono);flex-wrap:wrap;}
.crumbs a{color:var(--text3);transition:color var(--ease);display:inline-flex;align-items:center;gap:5px;}
.crumbs a:hover{color:var(--a);}
.crumbs svg{width:12px;height:12px;}
.crumb-sep{opacity:.5;}
.crumb-current{color:var(--text);font-weight:600;}
.crumb-id{display:inline-flex;align-items:center;gap:4px;background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.18);border-radius:100px;padding:2px 9px;font-size:10.5px;font-weight:700;}
.top-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.tb-action{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:var(--r-sm);font-size:12px;font-weight:600;font-family:var(--font);cursor:pointer;text-decoration:none;border:1px solid var(--border2);background:var(--surface);color:var(--text2);transition:all var(--ease);}
.tb-action:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);transform:translateY(-1px);}
.tb-action svg{width:13px;height:13px;}
.tb-action.primary{background:var(--a);color:#fff;border-color:var(--a);box-shadow:0 4px 14px rgba(37,99,235,.22);}
.tb-action.primary:hover{color:#fff;opacity:.9;}

/* ── card entrance animation ── */
@keyframes cardUp{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:none;}}
.card{animation:cardUp .4s ease both;}
.card:hover{box-shadow:var(--sh-md);}

/* ── Fundraising ring ── */
.fund-ring-row{display:flex;align-items:center;gap:16px;margin-bottom:16px;}
.fund-ring-wrap{position:relative;width:104px;height:104px;flex-shrink:0;}
.fund-ring{width:104px;height:104px;transform:rotate(-90deg);}
.fund-ring-bg{fill:none;stroke:var(--surface3);stroke-width:9;}
.fund-ring-fill{fill:none;stroke:url(#ringGrad);stroke-width:9;stroke-linecap:round;transition:stroke-dashoffset 1.2s cubic-bezier(.4,0,.2,1);}
.fund-ring-center{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;}
.fund-ring-pct{font-size:22px;font-weight:800;color:var(--text);font-family:var(--mono);letter-spacing:-.03em;line-height:1;}
.fund-ring-lbl{font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;font-family:var(--mono);margin-top:3px;}
.fund-ring-side{flex:1;min-width:0;}
.fund-raised{font-size:22px;font-weight:800;color:var(--a);font-family:var(--mono);letter-spacing:-.03em;line-height:1.1;}
.fund-goal{font-size:11.5px;color:var(--text3);font-family:var(--mono);margin-top:3px;}
.fund-stats-3{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;}

/* ── Fundraiser mini-profile ── */
.fundraiser-box{display:flex;align-items:center;gap:12px;}
.fundraiser-av{width:44px;height:44px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:17px;font-weight:800;color:#fff;font-family:var(--mono);background:linear-gradient(135deg,var(--a),var(--a2));overflow:hidden;}
.fundraiser-av img{width:100%;height:100%;object-fit:cover;}
.fundraiser-meta{flex:1;min-width:0;}
.fundraiser-name{font-size:13.5px;font-weight:700;color:var(--text);font-family:var(--font);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.fundraiser-email{font-size:11px;color:var(--text3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:1px;}
.fundraiser-kyc{display:inline-flex;align-items:center;gap:4px;font-size:9.5px;font-weight:700;padding:2px 8px;border-radius:100px;margin-top:5px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;}

/* ── Zoomable image hint ── */
.zoomable{cursor:zoom-in;}

/* ── Lightbox ── */
.lightbox{position:fixed;inset:0;z-index:600;background:rgba(4,5,14,.88);backdrop-filter:blur(6px);display:flex;align-items:center;justify-content:center;padding:32px;opacity:0;pointer-events:none;transition:opacity var(--ease);}
.lightbox.show{opacity:1;pointer-events:all;}
.lightbox img{max-width:92vw;max-height:88vh;border-radius:12px;box-shadow:0 24px 80px rgba(0,0,0,.6);transform:scale(.96);transition:transform var(--ease);}
.lightbox.show img{transform:scale(1);}
.lightbox-close{position:absolute;top:20px;right:22px;width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background var(--ease),transform var(--ease);}
.lightbox-close:hover{background:rgba(255,255,255,.25);transform:rotate(90deg);}
.lightbox-close svg{width:18px;height:18px;}
</style>
@endpush

@section('content')

{{-- Reject Modal --}}
<div class="modal-overlay" id="rejectModal">
    <div class="modal">
        <div class="modal-title">Reject Campaign</div>
        <p class="modal-body">Please provide a reason for rejecting this campaign. This will be shown to the fundraiser.</p>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="rejection_reason" placeholder="Rejection reason (optional)…"
                style="width:100%;min-height:80px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-family:var(--font);font-size:13px;padding:10px 12px;outline:none;resize:vertical;margin-bottom:14px;transition:border-color var(--ease);"
                onfocus="this.style.borderColor='var(--a)'" onblur="this.style.borderColor='var(--border2)'"></textarea>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary action-btn btn-ghost" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="action-btn btn-red">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>

{{-- Image Lightbox --}}
<div class="lightbox" id="lightbox">
    <button type="button" class="lightbox-close" onclick="closeLightbox()" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
    <img src="" alt="Preview" id="lightboxImg">
</div>

@php
    $kyc   = $campaign->user->kycVerification ?? null;
    $state = $campaign->campaign_state;

    $chipClass = match($state) {
        'active'    => 'chip-active',
        'paused'    => 'chip-paused',
        'pending'   => 'chip-pending',
        'rejected'  => 'chip-rejected',
        'expired'   => 'chip-expired',
        'completed' => 'chip-completed',
        default     => 'chip-pending',
    };
    $chipLabel = match($state) {
        'active'    => 'Active',
        'paused'    => 'Paused',
        'pending'   => 'Pending',
        'rejected'  => 'Rejected',
        'expired'   => 'Expired',
        'completed' => 'Completed',
        default     => ucfirst($state ?? 'Unknown'),
    };

    $raised     = $campaign->raised_amount ?? 0;
    $goal       = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
    $percentage = min(100, round(($raised / $goal) * 100));
    $remaining  = max(0, $campaign->goal_amount - $raised);

    /* legacy single-doc KYC */
    $kycDocUrl = $kyc?->document_url ? route('admin.kyc.document', $kyc->id) : null;
    $kycExt    = $kyc?->document_url ? strtolower(pathinfo($kyc->document_url, PATHINFO_EXTENSION)) : null;
    $kycIsPdf  = $kycExt === 'pdf';
    $kycIsImg  = in_array($kycExt, ['jpg','jpeg','png','webp','gif']);

    /* new multi-doc KYC fields — adjust attribute names to match your model */
    $kycAadhaarUrl = $kyc?->aadhaar_url  ? asset('storage/'.$kyc->aadhaar_url)  : null;
    $kycPanUrl     = $kyc?->pan_url      ? asset('storage/'.$kyc->pan_url)      : null;
    $kycSelfieUrl  = $kyc?->selfie_url   ? asset('storage/'.$kyc->selfie_url)   : null;

    $isImg = fn($url) => $url && preg_match('/\.(jpe?g|png|webp|gif)$/i', $url);
    $isPdf = fn($url) => $url && str_ends_with(strtolower($url), '.pdf');

    /* bank details */
    $bankName   = $kyc?->kyc_bank_name    ?? null;
    $bankAcc    = $kyc?->kyc_account_number ?? null;
    $bankIfsc   = $kyc?->kyc_ifsc          ?? null;
    $bankHolder = $kyc?->kyc_account_name  ?? null;

    /* campaign updates */
    $updates = $campaign->updates ?? collect();

    /* fundraiser + donor stats */
    $fundraiser  = $campaign->user ?? null;
    $donorCount  = 0;
    try { $donorCount = $campaign->donations()->count(); } catch (\Throwable $e) {}
    $avgDonation = $donorCount > 0 ? round($raised / $donorCount) : 0;

    /* progress ring geometry (r = 46) */
    $ringCirc   = 2 * M_PI * 46;
    $ringOffset = $ringCirc * (1 - ($percentage / 100));

    $kycStatus = $kyc?->status ?? 'none';
@endphp

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

<div class="detail-toolbar">
    <div class="crumbs">
        <a href="{{ route('admin.dashboard') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <span class="crumb-sep">/</span>
        <a href="{{ route('admin.campaign.index') }}">Campaigns</a>
        <span class="crumb-sep">/</span>
        <span class="crumb-current">{{ Str::limit($campaign->title, 34) }}</span>
        <span class="crumb-id">#{{ $campaign->id }}</span>
    </div>
    <div class="top-actions">
        @if(Route::has('campaign.show'))
        <!-- <a href="{{ route('campaign.show', $campaign->id) }}" target="_blank" class="tb-action">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            View live
        </a> -->

        @endif
        <button type="button" class="tb-action" onclick="copyCampaignLink(this)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            Copy link
        </button>
        @if(Route::has('admin.campaign.edit'))
        <a href="{{ route('admin.campaign.edit', $campaign->id) }}" class="tb-action primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
        @endif
    </div>
</div>

<div class="page-grid">

    {{-- LEFT COLUMN --}}
    <div>

        {{-- Cover --}}
        <div class="card">
            <div class="cover-wrap">
                @if($campaign->cover_image)
                    <img src="{{ asset('storage/' . $campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
                    <div class="cover-overlay"></div>
                    <div class="cover-meta">
                        <div>
                            <div class="cover-title">{{ Str::limit($campaign->title, 50) }}</div>
                            <div class="cover-created">Created {{ $campaign->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="badge" style="backdrop-filter:blur(8px);background:rgba(0,0,0,.35);border:1px solid rgba(255,255,255,.15);color:#fff;">
                            <span class="badge-dot" style="background:#fff;"></span>
                            {{ $chipLabel }}
                        </span>
                    </div>
                @else
                    <div class="cover-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        <span>No cover image</span>
                    </div>
                    <div style="padding:14px 18px;border-bottom:1px solid var(--border);">
                        <div style="font-family:var(--mono);font-size:18px;font-weight:800;color:var(--text);letter-spacing:-.02em;margin-bottom:3px;">{{ $campaign->title }}</div>
                        <div style="font-size:11px;color:var(--text3);font-family:var(--mono);">Created {{ $campaign->created_at->diffForHumans() }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- About --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">About This Campaign</div>
                        <div class="card-sub">Campaign description</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="desc-text">{{ $campaign->description }}</p>
            </div>
        </div>

        {{-- ── NEW: Campaign Updates & Documents ── --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-yellow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Updates &amp; Documents</div>
                        <div class="card-sub">{{ $updates->count() }} update{{ $updates->count() !== 1 ? 's' : '' }} submitted</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($updates->count() > 0)
                    <div class="updates-list">
                        @foreach($updates as $update)
                        <div class="update-item">
                            <div class="update-item-header">
                                <div class="update-item-title">{{ $update->title }}</div>
                                <div class="update-item-date">{{ \Carbon\Carbon::parse($update->created_at)->format('d M Y') }}</div>
                            </div>
                            @if($update->body)
                            <div class="update-item-body">{{ $update->body }}</div>
                            @endif
                            @if($update->document_url)
                            <a href="{{ asset('storage/'.$update->document_url) }}" target="_blank" class="update-doc-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                View attached document
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p>No updates or documents submitted for this campaign.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── NEW: KYC Identity Documents ── --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/></svg>
                    </div>
                    <div>
                        <div class="card-title">KYC Verification</div>
                        <div class="card-sub">Identity documents &amp; bank details</div>
                    </div>
                </div>
                @if($kyc)
                <span class="badge b-{{ $kyc->status }}">
                    <span class="badge-dot"></span>
                    {{ ucfirst($kyc->status) }}
                </span>
                @endif
            </div>
            <div class="card-body">

                @if(! $kyc)
                    <div class="kyc-notice kyc-notice-red">
                        <div class="kyc-notice-title">⚠ KYC Not Submitted</div>
                        <p class="kyc-notice-body">This user has not submitted any KYC documents. The campaign cannot be approved until KYC is verified.</p>
                    </div>

                @else

                    {{-- Status banner --}}
                    @if($kyc->status === 'pending')
                        <div class="kyc-notice kyc-notice-yellow">
                            <div class="kyc-notice-title"> KYC Under Review</div>
                            <p class="kyc-notice-body">Documents submitted on {{ $kyc->created_at->format('d M Y') }}. Awaiting admin verification.</p>
                        </div>
                    @elseif($kyc->status === 'approved')
                        <div class="kyc-notice kyc-notice-green">
                            <div class="kyc-notice-title">✓ KYC Approved</div>
                            <p class="kyc-notice-body">Identity verified @if($kyc->verified_at)on {{ \Carbon\Carbon::parse($kyc->verified_at)->format('d M Y') }}@endif. Eligible for campaign approval.</p>
                        </div>
                    @elseif($kyc->status === 'rejected')
                        <div class="kyc-notice kyc-notice-red">
                            <div class="kyc-notice-title">✗ KYC Rejected</div>
                            <p class="kyc-notice-body">{{ $kyc->rejection_reason ?? 'Documents were rejected.' }}</p>
                        </div>
                    @endif

                    {{-- ── Aadhaar + PAN side-by-side ── --}}
                    <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--mono);margin-bottom:10px;">Identity Documents</div>
                    <div class="kyc-docs-grid">

                        {{-- Aadhaar --}}
                        <div class="kyc-doc-tile">
                            <div class="kyc-doc-tile-header">
                                <span class="kyc-doc-tile-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h6"/></svg>
                                    Aadhaar Card
                                </span>
                                @if($kycAadhaarUrl)
                                <div style="display:flex;gap:5px;">
                                    <a href="{{ $kycAadhaarUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        Open
                                    </a>
                                    <a href="{{ $kycAadhaarUrl }}" download class="kyc-doc-btn kyc-doc-btn-dl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        DL
                                    </a>
                                </div>
                                @endif
                            </div>
                            @if($kycAadhaarUrl)
                                @if($isImg($kycAadhaarUrl))
                                    <a href="{{ $kycAadhaarUrl }}" target="_blank">
                                        <img src="{{ $kycAadhaarUrl }}" alt="Aadhaar" loading="lazy" class="kyc-doc-tile-img">
                                    </a>
                                @elseif($isPdf($kycAadhaarUrl))
                                    <iframe src="{{ $kycAadhaarUrl }}" class="kyc-doc-tile-pdf" title="Aadhaar PDF"></iframe>
                                @else
                                    <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg><span>Preview unavailable</span></div>
                                @endif
                            @else
                                <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span>Not uploaded</span></div>
                            @endif
                        </div>

                        {{-- PAN --}}
                        <div class="kyc-doc-tile">
                            <div class="kyc-doc-tile-header">
                                <span class="kyc-doc-tile-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h4"/></svg>
                                    PAN Card
                                </span>
                                @if($kycPanUrl)
                                <div style="display:flex;gap:5px;">
                                    <a href="{{ $kycPanUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        Open
                                    </a>
                                    <a href="{{ $kycPanUrl }}" download class="kyc-doc-btn kyc-doc-btn-dl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        DL
                                    </a>
                                </div>
                                @endif
                            </div>
                            @if($kycPanUrl)
                                @if($isImg($kycPanUrl))
                                    <a href="{{ $kycPanUrl }}" target="_blank">
                                        <img src="{{ $kycPanUrl }}" alt="PAN" loading="lazy" class="kyc-doc-tile-img">
                                    </a>
                                @elseif($isPdf($kycPanUrl))
                                    <iframe src="{{ $kycPanUrl }}" class="kyc-doc-tile-pdf" title="PAN PDF"></iframe>
                                @else
                                    <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg><span>Preview unavailable</span></div>
                                @endif
                            @else
                                <div class="kyc-doc-tile-missing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span>Not uploaded</span></div>
                            @endif
                        </div>

                    </div>{{-- /kyc-docs-grid --}}

                    {{-- ── Selfie with ID ── --}}
                    <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--mono);margin-bottom:10px;margin-top:6px;">Selfie Verification</div>
                    <div class="kyc-selfie-wrap">
                        @if($kycSelfieUrl)
                            <a href="{{ $kycSelfieUrl }}" target="_blank">
                                <img src="{{ $kycSelfieUrl }}" alt="Selfie with ID" loading="lazy" class="kyc-selfie-img">
                            </a>
                        @else
                            <div class="kyc-selfie-missing">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"/></svg>
                                <span>Not uploaded</span>
                            </div>
                        @endif
                        <div class="kyc-selfie-info">
                            <div class="kyc-selfie-title">Selfie with ID Document</div>
                            <div class="kyc-selfie-sub">Applicant must appear holding their Aadhaar or PAN card next to their face. Used to cross-verify identity against submitted documents.</div>
                            @if($kycSelfieUrl)
                            <div style="margin-top:10px;display:flex;gap:6px;">
                                <a href="{{ $kycSelfieUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    View full size
                                </a>
                                <a href="{{ $kycSelfieUrl }}" download class="kyc-doc-btn kyc-doc-btn-dl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- ── Bank Account Details ── --}}
                    <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--mono);margin-bottom:10px;margin-top:6px;padding-top:14px;border-top:1px solid var(--border);">Bank Account Details</div>
                    <div class="bank-grid">
                        <div class="bank-field">
                            <div class="bank-field-lbl">Account Holder</div>
                            @if($bankHolder)
                                <div class="bank-field-val">{{ $bankHolder }}</div>
                            @else
                                <div class="bank-field-val empty">Not provided</div>
                            @endif
                        </div>
                        <div class="bank-field">
                            <div class="bank-field-lbl">Bank Name</div>
                            @if($bankName)
                                <div class="bank-field-val">{{ $bankName }}</div>
                            @else
                                <div class="bank-field-val empty">Not provided</div>
                            @endif
                        </div>
                        <div class="bank-field">
                            <div class="bank-field-lbl">Account Number</div>
                            @if($bankAcc)
                                <div class="bank-field-val" style="letter-spacing:.08em;">
                                    <span id="accNum" style="filter:blur(4px);cursor:pointer;transition:filter .2s;" onclick="this.style.filter='none';document.getElementById('accReveal').style.display='none';">{{ $bankAcc }}</span>
                                    <span id="accReveal" style="font-size:10px;color:var(--a);cursor:pointer;font-family:var(--font);font-weight:500;" onclick="document.getElementById('accNum').style.filter='none';this.style.display='none';">click to reveal</span>
                                </div>
                            @else
                                <div class="bank-field-val empty">Not provided</div>
                            @endif
                        </div>
                        <div class="bank-field">
                            <div class="bank-field-lbl">IFSC Code</div>
                            @if($bankIfsc)
                                <div class="bank-field-val" style="letter-spacing:.1em;">{{ strtoupper($bankIfsc) }}</div>
                            @else
                                <div class="bank-field-val empty">Not provided</div>
                            @endif
                        </div>
                    </div>

                    {{-- Legacy single doc fallback --}}
                    @if($kycDocUrl && !$kycAadhaarUrl && !$kycPanUrl)
                    <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border);">
                        <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--mono);margin-bottom:10px;">Legacy Document</div>
                        <div class="kyc-doc-row">
                            <div class="kyc-doc-icon">📄</div>
                            <div>
                                <div class="kyc-doc-type">{{ ucfirst(str_replace('_', ' ', $kyc->document_type ?? 'Document')) }}</div>
                                <div class="kyc-doc-num">{{ $kyc->document_number ?? '' }}</div>
                            </div>
                        </div>
                        <div class="kyc-doc-preview">
                            <div class="kyc-doc-preview-header">
                                <div class="kyc-doc-preview-header-left">
                                    @if($kycIsPdf)
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--red);"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        PDF Document
                                    @else
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--blue);"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        Image Document
                                    @endif
                                </div>
                                <div class="kyc-doc-preview-actions">
                                    <a href="{{ $kycDocUrl }}" target="_blank" class="kyc-doc-btn kyc-doc-btn-view">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        View
                                    </a>
                                    <a href="{{ $kycDocUrl }}?download=1" download class="kyc-doc-btn kyc-doc-btn-dl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                            @if($kycIsImg)
                                <a href="{{ $kycDocUrl }}" target="_blank">
                                    <img src="{{ $kycDocUrl }}" alt="KYC document" loading="lazy" class="kyc-doc-preview-img">
                                </a>
                            @elseif($kycIsPdf)
                                <iframe src="{{ $kycDocUrl }}" class="kyc-doc-preview-iframe" title="KYC PDF Document"></iframe>
                            @else
                                <div class="kyc-doc-preview-fallback">Preview not available. Use View or Download above.</div>
                            @endif
                        </div>
                    </div>
                    @endif

                @endif
            </div>
        </div>

        {{-- Events --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-yellow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Campaign Events</div>
                        <div class="card-sub">{{ $campaign->events->count() }} event{{ $campaign->events->count() !== 1 ? 's' : '' }}</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($campaign->events->count() > 0)
                <div class="events-grid">
                    @foreach($campaign->events as $event)
                    @php
                        $evCls = match($event->status) {
                            'approved' => 'ev-approved',
                            'pending'  => 'ev-pending',
                            default    => 'ev-default',
                        };
                    @endphp
                    <div class="event-card">
                        <span class="event-badge {{ $evCls }}">{{ ucfirst($event->status) }}</span>
                        <div class="event-title">{{ $event->title }}</div>
                        <div class="event-date">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</div>
                        <div class="event-desc">{{ Str::limit($event->description, 100) }}</div>
                        <a href="{{ route('admin.events.show', $event->id) }}" class="event-link">
                            View details
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:10px;height:10px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p>No events have been created for this campaign.</p>
                </div>
                @endif
            </div>
        </div>

    </div>{{-- /left --}}

    {{-- RIGHT COLUMN --}}
    <div class="right-col">

        {{-- Status + Actions --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Status &amp; Actions</div>
                    </div>
                </div>
            </div>
            <div class="status-section">

                <div class="status-section-label">CAMPAIGN STATE</div>
                <div class="status-chips">
                    <span class="status-chip-lg {{ $chipClass }}">
                        <span class="chip-dot"></span>
                        {{ $chipLabel }}
                    </span>
                </div>

                <div class="kyc-pill kyc-pill-{{ $kyc?->status ?? 'none' }}">
                    @if(! $kyc) <span>⚠ KYC Not Submitted</span>
                    @elseif($kyc->status === 'pending') <span> KYC Pending Review</span>
                    @elseif($kyc->status === 'approved') <span>✓ KYC Approved</span>
                    @elseif($kyc->status === 'rejected') <span>✗ KYC Rejected</span>
                    @endif
                </div>

                @if($kyc && $kyc->status === 'pending')
                    <form action="{{ route('admin.kyc.approve', $kyc->id) }}" method="POST" style="margin-bottom:8px;">
                        @csrf
                        <button type="submit" class="action-btn btn-accent">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Approve KYC
                        </button>
                    </form>
                @endif

                @if($state === 'pending')
                    @if($kyc && $kyc->status === 'approved')
                        <form action="{{ route('admin.campaign.approve', $campaign->id) }}" method="POST" style="margin-bottom:8px;">
                            @csrf
                            <button type="submit" class="action-btn btn-green">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Approve Campaign
                            </button>
                        </form>
                    @else
                        <button type="button" class="action-btn btn-disabled" disabled title="KYC must be approved first">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Approve Campaign
                        </button>
                        <p style="font-size:10.5px;color:var(--amber);margin-top:5px;margin-bottom:8px;font-family:var(--mono);">⚠ KYC must be approved before approving campaign</p>
                    @endif
                    <button type="button" class="action-btn btn-red" onclick="openRejectModal({{ $campaign->id }})">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Reject Campaign
                    </button>

                @elseif($state === 'active')
                    <button type="button" class="action-btn btn-red" onclick="openRejectModal({{ $campaign->id }})">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reject Campaign
                    </button>
                    <form action="{{ route('admin.campaign.pause', $campaign->id) }}" method="POST" style="margin-top:8px;">
                        @csrf
                        <button type="submit" class="action-btn btn-yellow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>
                            Pause Campaign
                        </button>
                    </form>

                @elseif($state === 'paused')
                    <form action="{{ route('admin.campaign.resume', $campaign->id) }}" method="POST" style="margin-bottom:8px;">
                        @csrf
                        <button type="submit" class="action-btn btn-green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Resume Campaign
                        </button>
                    </form>
                    <button type="button" class="action-btn btn-red" onclick="openRejectModal({{ $campaign->id }})">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reject Campaign
                    </button>

                @elseif($state === 'rejected')
                    <form action="{{ route('admin.campaign.approve', $campaign->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="action-btn btn-green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Re-approve Campaign
                        </button>
                    </form>

                @elseif($state === 'expired' || $state === 'completed')
                    <div style="padding:10px 12px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;color:var(--text3);text-align:center;">
                        This campaign is {{ $chipLabel }} and no further actions are available.
                    </div>
                @endif

                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary action-btn btn-ghost" style="margin-top:12px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Back to Dashboard
                </a>

            </div>
        </div>

        {{-- Fundraiser --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Fundraiser</div>
                        <div class="card-sub">Campaign owner</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="fundraiser-box">
                    <div class="fundraiser-av">
                        @if($fundraiser?->avatar)
                            <img src="{{ asset('storage/'.$fundraiser->avatar) }}" alt="">
                        @else
                            {{ strtoupper(substr($fundraiser->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <div class="fundraiser-meta">
                        <div class="fundraiser-name">{{ $fundraiser->name ?? '—' }}</div>
                        <div class="fundraiser-email">{{ $fundraiser->email ?? 'No email' }}</div>
                        @php
                            $kycPillMap = [
                                'approved' => ['kyc-pill-approved','✓ KYC Verified'],
                                'pending'  => ['kyc-pill-pending','● KYC Pending'],
                                'rejected' => ['kyc-pill-rejected','✗ KYC Rejected'],
                                'none'     => ['kyc-pill-none','⚠ No KYC'],
                            ];
                            [$fkCls,$fkLbl] = $kycPillMap[$kycStatus] ?? $kycPillMap['none'];
                        @endphp
                        <span class="fundraiser-kyc {{ $fkCls }}">{{ $fkLbl }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fundraising --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Fundraising</div>
                        <div class="card-sub">Current progress</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <svg width="0" height="0" style="position:absolute;"><defs><linearGradient id="ringGrad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#2563eb "/><stop offset="100%" stop-color="#0d9488"/></linearGradient></defs></svg>
                <div class="fund-ring-row">
                    <div class="fund-ring-wrap">
                        <svg class="fund-ring" viewBox="0 0 104 104">
                            <circle class="fund-ring-bg" cx="52" cy="52" r="46"/>
                            <circle class="fund-ring-fill" cx="52" cy="52" r="46"
                                    style="stroke-dasharray:{{ $ringCirc }};stroke-dashoffset:{{ $ringOffset }};"/>
                        </svg>
                        <div class="fund-ring-center">
                            <div class="fund-ring-pct">{{ $percentage }}%</div>
                            <div class="fund-ring-lbl">funded</div>
                        </div>
                    </div>
                    <div class="fund-ring-side">
                        <div class="fund-raised">₹{{ number_format($raised) }}</div>
                        <div class="fund-goal">raised of ₹{{ number_format($campaign->goal_amount) }} goal</div>
                    </div>
                </div>
                <div class="fund-stats-3">
                    <div class="mini-stat">
                        <div class="mini-stat-val">{{ number_format($donorCount) }}</div>
                        <div class="mini-stat-lbl">Donors</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-val" style="font-size:13px;">₹{{ number_format($remaining) }}</div>
                        <div class="mini-stat-lbl">Remaining</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-val" style="font-size:13px;">₹{{ number_format($avgDonation) }}</div>
                        <div class="mini-stat-lbl">Avg gift</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Campaign Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-pink">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Campaign Info</div>
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding-top:10px;padding-bottom:10px;">
                <div class="info-row">
                    <span class="info-row-lbl">STATE</span>
                    <span class="badge b-{{ $state }}"><span class="badge-dot"></span>{{ $chipLabel }}</span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">KYC</span>
                    <span style="font-size:11px;font-weight:700;color:{{ $kyc?->status === 'approved' ? 'var(--green)' : ($kyc?->status === 'pending' ? 'var(--amber)' : 'var(--red)') }};">
                        @if(!$kyc) ⚠ Not Submitted
                        @elseif($kyc->status === 'pending') ✓ Pending
                        @elseif($kyc->status === 'approved') ✓ Verified
                        @else ✗ Rejected
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">UPDATES</span>
                    <span style="font-weight:700;color:var(--text);font-family:var(--mono);">{{ $updates->count() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">FUNDRAISER</span>
                    <span style="font-size:11.5px;font-weight:600;color:var(--text);">{{ $campaign->user->name ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">CATEGORY</span>
                    <span style="font-size:11.5px;font-weight:600;color:var(--text);">{{ $campaign->category->name ?? '—' }}</span>
                </div>
                @if($campaign->end_date)
                <div class="info-row">
                    <span class="info-row-lbl">END DATE</span>
                    <span style="font-size:11px;font-weight:600;color:{{ now()->gt($campaign->end_date) ? 'var(--red)' : 'var(--text2)' }};">
                        {{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y') }}
                        @if(now()->gt($campaign->end_date))<span style="font-size:9px;"> (expired)</span>@endif
                    </span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-row-lbl">EVENTS</span>
                    <span style="font-weight:700;color:var(--text);font-family:var(--mono);">{{ $campaign->events->count() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">CREATED</span>
                    <span style="font-weight:600;color:var(--text2);font-size:11px;">{{ $campaign->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

    </div>{{-- /right-col --}}
</div>{{-- /page-grid --}}

@endsection

@push('page_scripts')
<script>
/* ── Reject modal ── */
function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/campaigns/' + id + '/reject';
    document.getElementById('rejectModal').classList.add('show');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('show');
}
document.getElementById('rejectModal').addEventListener('click', function(e){
    if (e.target === this) closeRejectModal();
});
document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') { closeRejectModal(); closeLightbox(); }
});

/* ── Copy campaign link ── */
function copyCampaignLink(btn){
    var url = @json(Route::has('campaign.show') ? route('campaign.show', $campaign->id) : url('/admin/campaign/'.$campaign->id));
    var done = function(){
        var original = btn.innerHTML;
        btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Copied!';
        setTimeout(function(){ btn.innerHTML = original; }, 1600);
        if (typeof showToast === 'function') showToast('Campaign link copied', 'success');
    };
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(done).catch(function(){ window.prompt('Copy link:', url); });
    } else {
        window.prompt('Copy link:', url);
    }
}

/* ── Image lightbox ── */
var lightbox = document.getElementById('lightbox');
var lightboxImg = document.getElementById('lightboxImg');
function openLightbox(src){
    if (!src) return;
    lightboxImg.src = src;
    lightbox.classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeLightbox(){
    if (!lightbox.classList.contains('show')) return;
    lightbox.classList.remove('show');
    document.body.style.overflow = '';
}
lightbox.addEventListener('click', function(e){ if (e.target === lightbox) closeLightbox(); });

document.querySelectorAll('.cover-wrap img, .kyc-doc-tile-img, .kyc-selfie-img, .kyc-doc-preview-img').forEach(function(img){
    img.classList.add('zoomable');
    img.addEventListener('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        openLightbox(img.getAttribute('src'));
    });
});

@if(session('success'))
window.addEventListener('DOMContentLoaded', function(){ showToast(@json(session('success')), 'success'); });
@endif
@if(session('error'))
window.addEventListener('DOMContentLoaded', function(){ showToast(@json(session('error')), 'error'); });
@endif
</script>
@endpush
