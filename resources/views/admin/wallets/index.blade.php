@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush


@section('sidebar_wallets', 'active')
@section('page_title', 'Wallets')
@section('page_subtitle', 'All user & organization wallets')

@section('content')

@if(session('success'))
  <div style="padding:12px 16px;border-radius:12px;background:var(--green-lt);color:var(--green);font-size:13px;font-weight:500;margin-bottom:16px;">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div style="padding:12px 16px;border-radius:12px;background:var(--red-lt);color:var(--red);font-size:13px;font-weight:500;margin-bottom:16px;">{{ session('error') }}</div>
@endif

<div class="chart-card" style="margin-bottom:24px;">
  <div class="chart-hdr">
    <div>
      <div class="chart-ttl">All Wallets</div>
      <div class="chart-sub">Manage wallet</div>
    </div>
    <form method="GET" style="display:flex;gap:8px;align-items:center;">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search owner name…"
             style="padding:7px 12px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:13px;width:200px;">
      <button type="submit" style="padding:7px 14px;border-radius:8px;border:none;background:var(--a);color:#fff;font-size:13px;font-weight:600;cursor:pointer;">Search</button>
    </form>
  </div>
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
      <thead>
        <tr style="border-bottom:1px solid var(--border);">
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">ID</th>
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">Owner</th>
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">Type</th>
          <th style="padding:12px;text-align:right;color:var(--text3);font-weight:500;">Balance</th>
          <th style="padding:12px;text-align:right;color:var(--text3);font-weight:500;">Reserved</th>
          <th style="padding:12px;text-align:right;color:var(--text3);font-weight:500;">Locked</th>
          <th style="padding:12px;text-align:center;color:var(--text3);font-weight:500;">Currency</th>
          <th style="padding:12px;text-align:center;color:var(--text3);font-weight:500;"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($wallets as $w)
          <tr style="border-bottom:1px solid var(--border);">
            <td style="padding:12px;font-family:var(--mono);font-size:12px;">{{ $w->id }}</td>
            <td style="padding:12px;font-weight:500;">{{ optional($w->owner)->name ?? '—' }}</td>
            <td style="padding:12px;color:var(--text3);">{{ class_basename($w->owner_type) }}</td>
            <td style="padding:12px;text-align:right;font-family:var(--mono);font-weight:600;color:var(--green);">₹{{ number_format($w->balance, 2) }}</td>
            <td style="padding:12px;text-align:right;font-family:var(--mono);color:var(--amber);">₹{{ number_format($w->reserved_balance, 2) }}</td>
            <td style="padding:12px;text-align:right;font-family:var(--mono);color:var(--pink);">₹{{ number_format($w->pending_settlement_balance, 2) }}</td>
            <td style="padding:12px;text-align:center;font-family:var(--mono);font-size:12px;">{{ $w->currency }}</td>
            <td style="padding:12px;text-align:center;">
              <a href="{{ route('admin.wallets.show', $w) }}" style="color:var(--a);font-weight:600;text-decoration:none;font-size:12px;">View →</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div style="padding:12px;">{{ $wallets->links('vendor.pagination.admin') }}</div>
</div>

@endsection
