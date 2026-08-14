@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush

@extends('layouts.admin')

@section('sidebar_subscribers', 'active')
@section('page_title', 'Newsletter')
@section('page_subtitle', 'Manage newsletter subscribers & unsubscribes')

@section('topbar_left')
  <a href="{{ route('admin.subscribers.export') }}" class="add-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
    Export CSV
  </a>
@endsection

@push('page_styles')
<style>
.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;}
.card-head{display:flex;align-items:center;gap:10px;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:11.5px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);background:var(--surface);border:1px solid var(--border2);padding:2px 8px;border-radius:100px;}
.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:14px;flex-wrap:wrap;}
.search-wrap{position:relative;}
.search-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;}
.search-input{width:260px;height:36px;padding:0 14px 0 33px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.search-input::placeholder{color:var(--text3);}
.search-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.filter-btn{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 13px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all var(--ease);text-decoration:none;}
.filter-btn:hover,.filter-btn.on{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{padding:10px 18px;text-align:left;font-size:10px;font-family:var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--text3);background:var(--surface2);border-bottom:1px solid var(--border);font-weight:500;white-space:nowrap;}
thead th:last-child{text-align:right;}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
td{padding:13px 18px;font-size:13px;vertical-align:middle;}
.email-cell{font-weight:600;color:var(--text);font-size:13px;}
.meta-cell{font-size:12px;color:var(--text3);}
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;}
.s-active{background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.22);}
.s-inactive{background:rgba(100,116,139,.08);color:var(--text3);border:1px solid var(--border2);}
.actions{display:flex;align-items:center;justify-content:flex-end;gap:5px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:11.5px;font-weight:500;text-decoration:none;border:1px solid transparent;transition:all .15s;cursor:pointer;font-family:var(--font);white-space:nowrap;}
.act-btn svg{width:11px;height:11px;}
.act-edit{background:var(--blue-lt);color:var(--blue);border-color:rgba(59,130,246,.18);}
.act-edit:hover{background:var(--blue);color:#fff;transform:translateY(-1px);}
.act-del{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.18);}
.act-del:hover{background:var(--red);color:#fff;transform:translateY(-1px);}
.empty-state{padding:64px 24px;text-align:center;}
.empty-icon-wrap{width:64px;height:64px;border-radius:18px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 16px;}
.empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
.empty-state p{font-size:13px;color:var(--text3);margin-bottom:18px;}
.pagination-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--border);background:var(--surface2);flex-wrap:wrap;gap:10px;}
.page-info{font-size:12px;color:var(--text3);font-family:var(--mono);}
.page-btns{display:flex;gap:4px;}
.page-btn{height:30px;min-width:30px;padding:0 9px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11.5px;font-weight:600;border:1px solid var(--border2);background:var(--surface);color:var(--text2);cursor:pointer;transition:all .15s;font-family:var(--mono);text-decoration:none;}
.page-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.page-btn.cur{background:var(--a);border-color:var(--a);color:#fff;}
</style>
@endpush

@section('content')
<div class="toolbar">
  <form method="GET" action="{{ route('admin.subscribers.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
    <div class="search-wrap">
      <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="search-input" name="search" value="{{ request('search') }}" placeholder="Search email…">
    </div>
    <select class="filter-btn" name="status" onchange="this.form.submit()">
      <option value="">All statuses</option>
      <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
      <option value="unsubscribed" {{ request('status')==='unsubscribed'?'selected':'' }}>Unsubscribed</option>
    </select>
    <button type="submit" class="filter-btn">Filter</button>
    @if(request('search') || request('status'))
      <a href="{{ route('admin.subscribers.index') }}" class="filter-btn">Clear</a>
    @endif
  </form>
</div>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
    <span class="card-head-title">Subscribers</span>
    <span class="card-head-count">{{ $subscribers->total() }} total · {{ $stats['active'] }} active</span>
  </div>

  @if($subscribers->isEmpty())
    <div class="empty-state">
      <div class="empty-icon-wrap">✉️</div>
      <h3>No subscribers found</h3>
      <p>When visitors subscribe to the newsletter they'll appear here.</p>
    </div>
  @else
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:50px;">#</th>
            <th>Email</th>
            <th>Status</th>
            <th>Subscribed</th>
            <th>Unsubscribed</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($subscribers as $sub)
          <tr>
            <td><span style="font-size:11.5px;color:var(--text3);font-family:var(--mono);">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
            <td><span class="email-cell">{{ $sub->email }}</span></td>
            <td>
              <span class="status-pill {{ $sub->unsubscribed_at?'s-inactive':'s-active' }}">
                <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                {{ $sub->unsubscribed_at?'Unsubscribed':'Active' }}
              </span>
            </td>
            <td><span class="meta-cell">{{ $sub->subscribed_at?->format('M d, Y') ?? '—' }}</span></td>
            <td><span class="meta-cell">{{ $sub->unsubscribed_at?->format('M d, Y') ?? '—' }}</span></td>
            <td>
              <div class="actions">
                @if($sub->unsubscribed_at)
                  <form method="POST" action="{{ route('admin.subscribers.resubscribe', $sub->id) }}">@csrf
                    <button type="submit" class="btn btn-secondary act-btn act-edit" title="Re-subscribe"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>Resubscribe</button>
                  </form>
                @else
                  <form method="POST" action="{{ route('admin.subscribers.unsubscribe', $sub->id) }}">@csrf
                    <button type="submit" class="btn btn-secondary act-btn act-edit" title="Unsubscribe"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.36 6.64a9 9 0 11-12.73 0M12 2v10"/></svg>Unsubscribe</button>
                  </form>
                @endif
                <form method="POST" action="{{ route('admin.subscribers.destroy', $sub->id) }}" onsubmit="return confirm('Remove this subscriber permanently?');">@csrf @method('DELETE')
                  <button type="submit" class="btn btn-red act-btn act-del" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($subscribers->hasPages())
    <div class="pagination-wrap">
      <span class="page-info">Showing {{ $subscribers->firstItem() }}–{{ $subscribers->lastItem() }} of {{ $subscribers->total() }}</span>
      <div class="page-btns">
        @if($subscribers->onFirstPage())<span class="page-btn" style="opacity:.4;cursor:not-allowed;">‹</span>
        @else<a href="{{ $subscribers->previousPageUrl() }}" class="page-btn">‹</a>@endif
        @foreach($subscribers->getUrlRange(1,$subscribers->lastPage()) as $page=>$url)
          <a href="{{ $url }}" class="page-btn {{ $subscribers->currentPage()==$page?'cur':'' }}">{{ $page }}</a>
        @endforeach
        @if($subscribers->hasMorePages())<a href="{{ $subscribers->nextPageUrl() }}" class="page-btn">›</a>
        @else<span class="page-btn" style="opacity:.4;cursor:not-allowed;">›</span>@endif
      </div>
    </div>
    @endif
  @endif
</div>
@endsection
