@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush


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
    <table>
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
          <td>
            <div class="legal-name">{{ $row->title }}</div>
            <div class="legal-sub">{{ $row->exists ? 'Managed in admin' : 'Using default template' }}</div>
          </td>
          <td><span class="legal-sub">/{{ $row->slug }}</span></td>
          <td>
            <span class="status-pill {{ $row->exists?'s-active':'s-inactive' }}">
              <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
              {{ $row->exists ? 'Custom' : 'Default' }}
            </span>
          </td>
          <td>
            <div class="legal-meta">
              @if($row->updated_at)
                {{ $row->updated_at->format('M d, Y') }}
                @if($row->updated_by)<br><span style="font-size:11px;">by {{ $row->updated_by }}</span>@endif
              @else
                —
              @endif
            </div>
          </td>
          <td>
            <div class="actions">
              <a href="{{ route('admin.legal.edit', $row->slug) }}" class="btn btn-secondary act-btn act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
              <a href="{{ url(\App\Models\LegalPage::publicPath($row->slug)) }}" target="_blank" class="btn btn-secondary act-btn act-view"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>View</a>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
