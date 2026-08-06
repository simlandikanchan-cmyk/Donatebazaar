@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/organizations.css')
@endpush


@section('page_title', 'Delete Partnership')
@section('page_subtitle', 'Confirm removal')
@section('sidebar_partnerships', 'active')

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
        <x-button variant="destructive" type="submit">
          Yes, Delete
        </x-button>
      </form>
      <a href="{{ route('admin.partnership.index') }}" style="flex:1;padding:11px;border-radius:10px;font-size:13px;font-weight:500;border:none;text-align:center;text-decoration:none;background:#e5e7eb;color:#374151;">
        Cancel
      </a>
    </div>
  </div>
</div>
@endsection
