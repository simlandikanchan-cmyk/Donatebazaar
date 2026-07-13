@extends('layouts.admin')

@section('sidebar_success_stories', 'active')
@section('page_title', 'Success Stories')
@section('page_subtitle', 'Curate completed campaigns featured on the Impact page')

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
.camp-cell{display:flex;align-items:center;gap:11px;}
.camp-img{width:40px;height:40px;border-radius:9px;object-fit:cover;background:var(--surface2);flex-shrink:0;}
.camp-name{font-weight:600;color:var(--text);font-size:13.5px;}
.camp-sub{font-size:11.5px;color:var(--text3);}
.raised{font-family:var(--mono);font-size:13px;color:var(--text);font-weight:600;}
.goal{font-family:var(--mono);font-size:11.5px;color:var(--text3);}
.cat-pill{display:inline-block;padding:3px 10px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.18);}
.star{color:#f59e0b;}
.star.off{color:var(--border2);}
.actions{display:flex;align-items:center;justify-content:flex-end;gap:8px;}
.toggle-btn{display:inline-flex;align-items:center;gap:6px;height:34px;padding:0 14px;border-radius:7px;font-size:11.5px;font-weight:600;cursor:pointer;font-family:var(--font);border:1px solid transparent;transition:all .15s;}
.toggle-on{background:rgba(245,158,11,.14);color:#d97706;border-color:rgba(245,158,11,.28);}
.toggle-on:hover{background:rgba(245,158,11,.22);}
.toggle-off{background:var(--surface2);color:var(--text2);border-color:var(--border2);}
.toggle-off:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.empty-state{padding:64px 24px;text-align:center;}
.empty-icon-wrap{width:64px;height:64px;border-radius:18px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 16px;}
.empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
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
  <form method="GET" action="{{ route('admin.success-stories.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
    <div class="search-wrap">
      <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="search-input" name="search" value="{{ request('search') }}" placeholder="Search campaigns…">
    </div>
    <select class="filter-btn" name="featured" onchange="this.form.submit()">
      <option value="">All completed</option>
      <option value="1" {{ request('featured')==='1'?'selected':'' }}>Featured only</option>
      <option value="0" {{ request('featured')==='0'?'selected':'' }}>Not featured</option>
    </select>
    <button type="submit" class="filter-btn">Filter</button>
    @if(request('search') || request('featured')!==null)
      <a href="{{ route('admin.success-stories.index') }}" class="filter-btn">Clear</a>
    @endif
  </form>
</div>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg></div>
    <span class="card-head-title">Completed Campaigns</span>
    <span class="card-head-count">{{ $campaigns->total() }} completed · {{ $stats['featured'] }} featured</span>
  </div>

  @if($campaigns->isEmpty())
    <div class="empty-state">
      <div class="empty-icon-wrap">🏆</div>
      <h3>No completed campaigns yet</h3>
      <p>Completed campaigns can be featured on the public Impact / Success Stories page.</p>
    </div>
  @else
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:50px;">#</th>
            <th>Campaign</th>
            <th>Category</th>
            <th>Raised</th>
            <th>Featured</th>
            <th style="text-align:right;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($campaigns as $c)
          <tr>
            <td><span style="font-size:11.5px;color:var(--text3);font-family:var(--mono);">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
            <td>
              <div class="camp-cell">
                @if($c->cover_image)
                  <img src="{{ asset('storage/'.$c->cover_image) }}" class="camp-img" alt="">
                @else
                  <div class="camp-img" style="display:flex;align-items:center;justify-content:center;color:var(--text3);font-size:14px;">🎯</div>
                @endif
                <div>
                  <div class="camp-name">{{ Str::limit($c->title, 50) }}</div>
                  <div class="camp-sub">{{ $c->location ?? '—' }}</div>
                </div>
              </div>
            </td>
            <td>@if($c->category)<span class="cat-pill">{{ $c->category->name }}</span>@else — @endif</td>
            <td>
              <div class="raised">₹{{ number_format($c->raised_amount, 0) }}</div>
              <div class="goal">of ₹{{ number_format($c->goal_amount, 0) }}</div>
            </td>
            <td>
              <span style="font-size:16px;{{ $c->is_featured?'':'opacity:.3;' }}">★</span>
            </td>
            <td>
              <div class="actions">
                <form method="POST" action="{{ route('admin.success-stories.toggle', $c->id) }}">@csrf
                  <input type="hidden" name="is_featured" value="{{ $c->is_featured ? 0 : 1 }}">
                  <button type="submit" class="toggle-btn {{ $c->is_featured?'toggle-on':'toggle-off' }}">
                    @if($c->is_featured)
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.36 6.64a9 9 0 11-12.73 0M12 2v10"/></svg>Unfeature
                    @else
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>Feature
                    @endif
                  </button>
                </form>
                <a href="{{ route('campaign.public', ['category' => $c->category?->slug ?? 'campaign', 'slug' => $c->slug]) }}" target="_blank" class="toggle-btn toggle-off" title="View">View</a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($campaigns->hasPages())
    <div class="pagination-wrap">
      <span class="page-info">Showing {{ $campaigns->firstItem() }}–{{ $campaigns->lastItem() }} of {{ $campaigns->total() }}</span>
      <div class="page-btns">
        @if($campaigns->onFirstPage())<span class="page-btn" style="opacity:.4;cursor:not-allowed;">‹</span>
        @else<a href="{{ $campaigns->previousPageUrl() }}" class="page-btn">‹</a>@endif
        @foreach($campaigns->getUrlRange(1,$campaigns->lastPage()) as $page=>$url)
          <a href="{{ $url }}" class="page-btn {{ $campaigns->currentPage()==$page?'cur':'' }}">{{ $page }}</a>
        @endforeach
        @if($campaigns->hasMorePages())<a href="{{ $campaigns->nextPageUrl() }}" class="page-btn">›</a>
        @else<span class="page-btn" style="opacity:.4;cursor:not-allowed;">›</span>@endif
      </div>
    </div>
    @endif
  @endif
</div>
@endsection
