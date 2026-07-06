@extends('layouts.admin')

@section('sidebar_campaigns', 'active')

@section('page_title', 'All Campaigns')
@section('page_subtitle', 'Manage and review fundraiser campaigns')

@push('page_styles')
<style>
.flash-success{background:rgba(16,185,129,.09);border:1px solid rgba(16,185,129,.25);color:#065f46;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.flash-error{background:rgba(239,68,68,.09);border:1px solid rgba(239,68,68,.25);color:#dc2626;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
[data-theme="dark"] .flash-success{color:#34d399;}[data-theme="dark"] .flash-error{color:#f87171;}
.page-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;}
.page-head h2{font-family:var(--mono);font-size:20px;font-weight:800;color:var(--text);}
.page-head p{font-size:12px;color:var(--text3);margin-top:3px;font-family:var(--mono);}
.stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:10px;margin-bottom:24px;}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-sm);padding:14px 16px;box-shadow:var(--sh);display:flex;align-items:center;gap:12px;}
.stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:16px;height:16px;}
.stat-num{font-family:var(--mono);font-size:22px;font-weight:800;color:var(--text);line-height:1;}
.stat-lbl{font-size:10.5px;color:var(--text3);margin-top:3px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;}
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
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
.b-pending{background:rgba(245,158,11,.15);color:#b45309;border:1px solid rgba(245,158,11,.30);}
.b-active{background:rgba(16,185,129,.15);color:#065f46;border:1px solid rgba(16,185,129,.30);}
.b-paused{background:rgba(99,102,241,.15);color:#3730a3;border:1px solid rgba(99,102,241,.30);}
.b-rejected{background:rgba(239,68,68,.15);color:#991b1b;border:1px solid rgba(239,68,68,.30);}
.b-expired{background:rgba(107,114,128,.15);color:#374151;border:1px solid rgba(107,114,128,.30);}
.b-completed{background:rgba(59,130,246,.15);color:#1e40af;border:1px solid rgba(59,130,246,.30);}
[data-theme="dark"] .b-pending{color:#fbbf24;}[data-theme="dark"] .b-active{color:#34d399;}[data-theme="dark"] .b-paused{color:#a5b4fc;}[data-theme="dark"] .b-rejected{color:#f87171;}[data-theme="dark"] .b-expired{color:#9ca3af;}[data-theme="dark"] .b-completed{color:#93c5fd;}
.actions-cell{display:flex;align-items:center;gap:4px;}
.action-link{display:inline-flex;align-items:center;gap:4px; border: 1px solid #af80f1; padding:5px 10px;border-radius:6px;font-size:11px;font-weight:600;transition:background var(--ease),color var(--ease);white-space:nowrap;}
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
@media(max-width:860px){.stats-row{grid-template-columns:repeat(2,1fr);}}
@media(max-width:600px){.stats-row{grid-template-columns:1fr;}}


</style>

@endpush

@section('content')

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
            {{ $campaigns->onEachSide(1)->links('vendor.pagination.admin') }}
        </div>
        @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <p>No campaigns found.</p>
        </div>
        @endif
    </div>
</div>

@endsection
