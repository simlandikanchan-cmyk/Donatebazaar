@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush


@section('sidebar_settlements', 'active')
@section('page_title', 'Settlements')
@section('page_subtitle', 'Payout requests from organizations')

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
      <div class="chart-ttl">All Settlement Requests</div>
      <div class="chart-sub">Review and process payout requests</div>
    </div>
    <form method="GET" style="display:flex;gap:8px;align-items:center;">
      <select name="status" style="padding:7px 12px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:13px;">
        <option value="">All</option>
        <option value="pending_approval" @selected(request('status')==='pending_approval')>Pending Approval</option>
        <option value="approved" @selected(request('status')==='approved')>Approved</option>
        <option value="paid" @selected(request('status')==='paid')>Paid</option>
        <option value="rejected" @selected(request('status')==='rejected')>Rejected</option>
        <option value="processing" @selected(request('status')==='processing')>Processing</option>
        <option value="failed" @selected(request('status')==='failed')>Failed</option>
      </select>
      <x-button type="submit" variant="primary" size="sm">Filter</x-button>
    </form>
  </div>
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
      <thead>
        <tr style="border-bottom:1px solid var(--border);">
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">ID</th>
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">Organization</th>
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">Status</th>
          <th style="padding:12px;text-align:right;color:var(--text3);font-weight:500;">Net Amount</th>
          <th style="padding:12px;text-align:left;color:var(--text3);font-weight:500;">Created</th>
          <th style="padding:12px;text-align:center;color:var(--text3);font-weight:500;"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($settlements as $s)
          @php
            $statusColors = [
              'pending_approval' => 'var(--amber)',
              'approved' => 'var(--a)',
              'paid' => 'var(--green)',
              'rejected' => 'var(--red)',
              'processing' => 'var(--a)',
              'failed' => 'var(--red)',
            ];
            $statusLabels = [
              'pending_approval' => 'Pending Approval',
              'approved' => 'Approved',
              'paid' => 'Paid',
              'rejected' => 'Rejected',
              'processing' => 'Processing',
              'failed' => 'Failed',
            ];
            $sc = $statusColors[$s->status] ?? 'var(--text3)';
          @endphp
          <tr style="border-bottom:1px solid var(--border);">
            <td style="padding:12px;font-family:var(--mono);font-size:12px;">{{ $s->id }}</td>
            <td style="padding:12px;font-weight:500;">{{ optional($s->organization)->name ?? '—' }}</td>
            <td style="padding:12px;"><span style="color:{{ $sc }};font-weight:600;font-size:12px;">{{ $statusLabels[$s->status] ?? $s->status }}</span></td>
            <td style="padding:12px;text-align:right;font-family:var(--mono);font-weight:600;">₹{{ number_format($s->net_amount, 2) }}</td>
            <td style="padding:12px;font-family:var(--mono);font-size:12px;">{{ $s->created_at->format('Y-m-d') }}</td>
            <td style="padding:12px;text-align:center;">
              <a href="{{ route('admin.settlements.show', $s) }}" style="color:var(--a);font-weight:600;text-decoration:none;font-size:12px;">View →</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div style="padding:12px;">{{ $settlements->links('vendor.pagination.admin') }}</div>
</div>

@endsection
