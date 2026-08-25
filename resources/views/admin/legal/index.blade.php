@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush

@extends('layouts.admin')

@section('sidebar_legal', 'active')
@section('page_title', 'Legal Pages')
@section('page_subtitle', 'Manage Privacy, Terms, Refund & Cookie policies')

@section('content')
<div class="main-card">
  <div class="card-head">
    <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
    <span class="card-head-title">Legal Pages</span>
    <span class="card-head-count">{{ $rows->count() }} pages</span>
  </div>
  <div class="table-wrap">
     <table id="legalTable">
      <thead>
        <tr>
          <th>Page</th>
          <th>Public URL</th>
          <th>Status</th>
          <th>Last Updated</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rows as $row)
          <tr>
            <td data-label="Page">
              <div class="legal-name">{{ $row->title }}</div>
            <div class="legal-sub">{{ $row->exists ? 'Managed in admin' : 'Using default template' }}</div>
          </td>
            <td data-label="Public URL"><span class="legal-sub">/{{ $row->slug }}</span></td>
            <td data-label="Status">
            <span class="status-pill {{ $row->exists?'s-active':'s-inactive' }}">
              <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
              {{ $row->exists ? 'Custom' : 'Default' }}
            </span>
          </td>
            <td data-label="Last Updated">
              <div class="legal-meta">
              @if($row->updated_at)
                {{ $row->updated_at->format('M d, Y') }}
                @if($row->updated_by)<br><span style="font-size:11px;">by {{ $row->updated_by }}</span>@endif
              @else
                —
              @endif
            </div>
          </td>
            <td data-label="Actions">
              <div class="actions">
              <a href="{{ route('admin.legal.edit', $row->slug) }}" class="btn btn-secondary act-btn act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
              @if($row->exists)
              <form method="POST" action="{{ route('admin.legal.destroy', $row->slug) }}" style="display:inline;" onsubmit="return confirm('Reset this legal page to default template? This will remove your custom content.');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-red act-btn ab-delete" title="Reset to default">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

