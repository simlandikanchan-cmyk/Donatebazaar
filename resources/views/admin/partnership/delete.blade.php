
@extends('layouts.admin')

@section('page_title', 'Delete Partnership')
@section('page_subtitle', 'Confirm removal')
@section('sidebar_partnerships', 'active')

@push('page_styles')
<style>
@media(max-width:640px){.delete-card{margin-top:30px!important;max-width:calc(100% - 32px)!important;margin-left:16px!important;margin-right:16px!important}}
@media(max-width:480px){.delete-card .btn-row{flex-direction:column!important}.delete-card .btn-row>form,.delete-card .btn-row>a{width:100%!important}.delete-card>div:last-child{padding:18px 14px!important}}
</style>
@endpush

@section('content')
<div class="delete-card" style="max-width:520px;margin:60px auto;">
  <a href="{{ route('admin.partnership.index') }}" class="back-link" style="font-size:12px;color:var(--a);text-decoration:none;display:inline-block;margin-bottom:10px;">
    ← Back to list
  </a>
  <div style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:28px;">
    <div style="font-size:22px;font-weight:600;color:var(--red);">Delete Partnership</div>
    <div style="font-size:13px;color:var(--text3);margin-top:4px;">This action cannot be undone</div>
    <div style="margin-top:20px;background:var(--red-lt);color:var(--red);padding:12px;border-radius:10px;font-size:12px;font-family:var(--mono);">
      ⚠️ You are about to permanently delete this partnership request.
    </div>
    <div style="margin-top:18px;background:var(--surface2);padding:14px;border-radius:10px;font-size:13px;">
      <p><strong style="display:inline-block;width:100px;color:var(--text3);">Name:</strong> {{ $partnership->name ?? '-' }}</p>
      <p><strong style="display:inline-block;width:100px;color:var(--text3);">Email:</strong> {{ $partnership->email ?? '-' }}</p>
      <p><strong style="display:inline-block;width:100px;color:var(--text3);">Organization</strong> {{ $partnership->organization_name ?? '-' }}</p>
    </div>
    <div class="btn-row" style="margin-top:24px;display:flex;gap:10px;">
      <form method="POST" action="{{ route('admin.partnership.delete',$partnership->id) }}" style="flex:1;">
        @csrf
        @method('DELETE')
        <button class="btn btn-red">
          Yes, Delete
        </button>
      </form>
      <a href="{{ route('admin.partnership.index') }}" style="flex:1;padding:11px;border-radius:10px;font-size:13px;font-weight:500;border:none;text-align:center;text-decoration:none;background:#e5e7eb;color:#374151;">
        Cancel
      </a>
    </div>
  </div>
</div>
@endsection