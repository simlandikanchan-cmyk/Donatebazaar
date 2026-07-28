@extends('layouts.admin')

@section('sidebar_legal', 'active')
@section('page_title', 'Legal Pages')
@section('page_subtitle', 'Manage Privacy, Terms, Refund & Cookie policies')

@push('page_styles')
<style>
.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;}
.card-head{display:flex;align-items:center;gap:10px;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:11.5px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);background:var(--surface);border:1px solid var(--border2);padding:2px 8px;border-radius:100px;}
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{padding:10px 18px;text-align:left;font-size:10px;font-family:var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--text3);background:var(--surface2);border-bottom:1px solid var(--border);font-weight:500;white-space:nowrap;}
thead th:last-child{text-align:right;}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
td{padding:14px 18px;font-size:13px;vertical-align:middle;}
.legal-name{font-weight:600;color:var(--text);font-size:13.5px;}
.legal-sub{font-size:11.5px;color:var(--text3);font-family:var(--mono);margin-top:2px;}
.legal-meta{font-size:12px;color:var(--text3);}
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;}
.s-active{background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.22);}
.s-inactive{background:rgba(100,116,139,.08);color:var(--text3);border:1px solid var(--border2);}
.actions{display:flex;align-items:center;justify-content:flex-end;gap:5px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:500;text-decoration:none;border:1px solid transparent;transition:all .15s;cursor:pointer;font-family:var(--font);white-space:nowrap;}
.act-btn svg{width:11px;height:11px;}
.act-edit{background:var(--blue-lt);color:var(--blue);border-color:rgba(59,130,246,.18);}
.act-edit:hover{background:var(--blue);color:#fff;transform:translateY(-1px);}
.act-view{background:var(--surface2);color:var(--text2);border-color:var(--border2);}
.act-view:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
</style>
@endpush

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
