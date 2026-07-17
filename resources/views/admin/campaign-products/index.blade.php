@extends('layouts.admin')

@section('page_title', 'Campaign Products')
@section('page_subtitle', 'Review and manage fundraiser products')
@section('sidebar_campaign-products', 'active')

@push('page_styles')
<style>
/* ── Toolbar / filters (shared admin design system) ── */
.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;}
.toolbar-left{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.search-wrap{position:relative;}
.search-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text3);pointer-events:none;}
.search-input{width:240px;height:38px;padding:0 12px 0 34px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease),width .3s ease;}
.search-input::placeholder{color:var(--text3);}
.search-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);width:280px;}
.select-wrap{position:relative;}
.select-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text3);pointer-events:none;z-index:1;}
.filter-select{height:38px;padding:0 30px 0 34px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text2);font-family:var(--font);outline:none;cursor:pointer;transition:all var(--ease);appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 9px center;background-size:13px;}
.filter-select:hover,.filter-select:focus{border-color:var(--a);color:var(--a);background-color:var(--a-lt);box-shadow:0 0 0 3px var(--a-glow);}
.date-input{height:38px;padding:0 12px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text2);font-family:var(--font);outline:none;cursor:pointer;transition:all var(--ease);color-scheme:light;}
.date-input:hover,.date-input:focus{border-color:var(--a);color:var(--a);background-color:var(--a-lt);}
.filter-sep{color:var(--text3);font-size:13px;}
.clear-btn{display:inline-flex;align-items:center;gap:5px;height:38px;padding:0 13px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;color:var(--text3);text-decoration:none;transition:all var(--ease);}
.clear-btn:hover{border-color:var(--red);color:var(--red);background:var(--red-lt);}
.toolbar-right{display:flex;align-items:center;gap:8px;}
.export-btn{display:inline-flex;align-items:center;gap:6px;height:38px;padding:0 15px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;font-weight:500;color:var(--text2);text-decoration:none;cursor:pointer;transition:all var(--ease);}
.export-btn:hover{border-color:var(--green);color:var(--green);background:rgba(5,196,138,.06);}
.export-btn svg{width:14px;height:14px;}

/* ── Card header variants ── */
.card-head-right{display:flex;align-items:center;gap:8px;}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);}

/* ── Bulk action bar ── */
.cp-bulkbar{display:none;align-items:center;gap:12px;flex-wrap:wrap;padding:12px 20px;background:var(--a-lt);border-bottom:1px solid var(--a);}
.cp-bulkbar strong{color:var(--a);font-family:var(--mono);}
.cp-bulk-acts{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.cp-bulk-btn{display:inline-flex;align-items:center;gap:5px;height:34px;padding:0 14px;border-radius:var(--r-sm);font-size:12px;font-weight:600;border:1px solid transparent;cursor:pointer;font-family:var(--font);transition:all var(--ease);}
.cp-bulk-approve{background:var(--green);color:#fff;}
.cp-bulk-approve:hover{filter:brightness(1.05);}
.cp-bulk-reject{background:var(--red);color:#fff;}
.cp-bulk-reject:hover{filter:brightness(1.05);}
.cp-bulk-clear{background:var(--surface);border-color:var(--border2);color:var(--text3);}
.cp-bulk-clear:hover{color:var(--text2);border-color:var(--text3);}

/* ── Table action buttons ── */
.cp-actions{display:flex;align-items:center;gap:6px;flex-wrap:wrap;}
.cp-del{width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--border2);border-radius:var(--r-sm);background:var(--surface);color:var(--text3);cursor:pointer;transition:all var(--ease);}
.cp-del:hover{border-color:var(--red);color:var(--red);background:var(--red-lt);}
.cp-del svg{width:14px;height:14px;}
.cp-meta{font-size:11px;color:var(--text3);line-height:1.5;}
.meta-name{color:var(--text2);font-weight:600;}

@media(max-width:960px){.stats-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:860px){
  .search-input{width:100%;}.search-input:focus{width:100%;}
  .search-wrap{flex:1;min-width:180px;}
  .filter-bar-inner{flex-direction:column;align-items:stretch;}
}
@media(max-width:700px){.toolbar{flex-direction:column;align-items:stretch}.toolbar-left{flex-wrap:wrap}.select-wrap{flex:1;min-width:0}.filter-select{width:100%}.date-input{width:100%}.filter-sep{text-align:center}.toolbar-right{width:100%}.toolbar-right .export-btn{flex:1;justify-content:center}}
@media(max-width:640px){.card-head{flex-direction:column;align-items:flex-start;gap:8px}.cp-bulkbar{flex-direction:column;align-items:stretch;gap:10px}.cp-bulk-acts{justify-content:center}}
@media(max-width:540px){.stats-grid{grid-template-columns:1fr 1fr;gap:10px}.stat{padding:12px 14px}.stat-icon{width:32px;height:32px;border-radius:8px}.stat-icon svg{width:13px;height:13px}.stat-val{font-size:15px}}
@media(max-width:480px){.stats-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')

{{-- STATS --}}
<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-a">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">All Products</div>
      <div class="stat-val sv-a">{{ $cntTotal }}</div>
      <div class="stat-foot">Total in catalogue</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Pending</div>
      <div class="stat-val sv-amber">{{ $cntPending }}</div>
      <div class="stat-foot">Awaiting review</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Approved</div>
      <div class="stat-val sv-green">{{ $cntApproved }}</div>
      <div class="stat-foot">Live products</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Rejected</div>
      <div class="stat-val sv-red">{{ $cntRejected }}</div>
      <div class="stat-foot">Declined</div>
    </div>
  </div>
</div>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-left">
      <div class="ftabs">
        <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}"
           class="ftab {{ $status === 'all' ? 'on' : '' }}">All <span class="cnt">{{ $cntTotal }}</span></a>
        <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}"
           class="ftab {{ $status === 'pending' ? 'on' : '' }}">Pending <span class="cnt">{{ $cntPending }}</span></a>
        <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'approved'])) }}"
           class="ftab {{ $status === 'approved' ? 'on' : '' }}">Approved <span class="cnt">{{ $cntApproved }}</span></a>
        <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'rejected'])) }}"
           class="ftab {{ $status === 'rejected' ? 'on' : '' }}">Rejected <span class="cnt">{{ $cntRejected }}</span></a>
      </div>
    </div>
  </div>

  {{-- FILTER TOOLBAR --}}
  <div style="padding:16px 20px;border-bottom:1px solid var(--border);background:var(--surface2);">
    <form method="GET" action="{{ route('admin.campaign-products.index') }}" id="filterForm" class="toolbar">
      <div class="toolbar-left">
        <input type="hidden" name="status" value="{{ $status }}">

        <div class="search-wrap">
          <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          <input type="text" name="search" class="search-input" placeholder="Search product, campaign, owner…"
                 value="{{ $search }}" oninput="autoSubmit()">
        </div>

        <div class="select-wrap">
          <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
          <select name="source" class="filter-select" onchange="this.form.submit()">
            <option value="">All Sources</option>
            <option value="admin" {{ $source === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ $source === 'user' ? 'selected' : '' }}>User</option>
          </select>
        </div>

        <div class="select-wrap">
          <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
          <select name="category" class="filter-select" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ (string)$categoryId === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>

        <input type="date" name="from" value="{{ $from }}" class="date-input" onchange="this.form.submit()">
        <span class="filter-sep">—</span>
        <input type="date" name="to" value="{{ $to }}" class="date-input" onchange="this.form.submit()">

        @if($search || $source || $categoryId || $from || $to)
          <a href="{{ route('admin.campaign-products.index', ['status' => $status]) }}" class="clear-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/></svg>
            Clear
          </a>
        @endif

        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $dir }}">
      </div>

      <div class="toolbar-right">
        <a href="{{ route('admin.campaign-products.export', request()->only(['status', 'search', 'source', 'category'])) }}" class="export-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
          Export CSV
        </a>
      </div>
    </form>
  </div>

  {{-- BULK BAR --}}
  <div id="bulkBar" class="cp-bulkbar">
    <span><strong id="bulkCount">0</strong> product(s) selected</span>
    <div class="cp-bulk-acts">
      <button type="button" class="btn btn-green cp-bulk-btn cp-bulk-approve" onclick="bulkApprove()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
        Approve All
      </button>
      <button type="button" class="btn btn-red cp-bulk-btn cp-bulk-reject" onclick="openBulkRejectModal()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        Reject All
      </button>
      <button type="button" class="btn btn-secondary cp-bulk-btn cp-bulk-clear" onclick="clearSelection()">Clear Selection</button>
    </div>
  </div>

  @if($products->isEmpty())
    <div id="noResults" class="empty-state">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
      <strong>No products found</strong>
      <span>There are no {{ $status !== 'all' ? $status : '' }} products to display.</span>
    </div>
  @else
    <div class="p-table-wrap">
      <table class="p-table" id="productsTable">
        <thead>
          <tr>
            <th style="width:36px;">
              <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()" style="cursor:pointer;">
            </th>
            <th style="width:50px;">Image</th>
            <th>
              <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'name', 'direction' => ($sort === 'name' && $dir === 'asc') ? 'desc' : 'asc'])) }}"
                 style="color:var(--text);text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                Product
                @if($sort === 'name')
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="{{ $dir === 'asc' ? 'M12 5l7 7H5l7-7z' : 'M12 19l7-7H5l7 7z' }}"/></svg>
                @endif
              </a>
            </th>
            <th>Campaign</th>
            <th>Owner</th>
            <th>
              <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'price', 'direction' => ($sort === 'price' && $dir === 'asc') ? 'desc' : 'asc'])) }}"
                 style="color:var(--text);text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                Price
                @if($sort === 'price')
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="{{ $dir === 'asc' ? 'M12 5l7 7H5l7-7z' : 'M12 19l7-7H5l7 7z' }}"/></svg>
                @endif
              </a>
            </th>
            <th>
              <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'quantity', 'direction' => ($sort === 'quantity' && $dir === 'asc') ? 'desc' : 'asc'])) }}"
                 style="color:var(--text);text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                Qty
                @if($sort === 'quantity')
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="{{ $dir === 'asc' ? 'M12 5l7 7H5l7-7z' : 'M12 19l7-7H5l7 7z' }}"/></svg>
                @endif
              </a>
            </th>
            <th>Remaining</th>
            <th>Category</th>
            <th>Source</th>
            <th>
              <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'approval_status', 'direction' => ($sort === 'approval_status' && $dir === 'asc') ? 'desc' : 'asc'])) }}"
                 style="color:var(--text);text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                Status
                @if($sort === 'approval_status')
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="{{ $dir === 'asc' ? 'M12 5l7 7H5l7-7z' : 'M12 19l7-7H5l7 7z' }}"/></svg>
                @endif
              </a>
            </th>
            <th style="width:240px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $product)
          <tr>
            <td>
              <input type="checkbox" class="product-checkbox" value="{{ $product->id }}"
                     {{ $product->approval_status !== 'pending' ? 'disabled' : '' }}
                     onchange="updateBulkBar()" style="cursor:pointer;">
            </td>
            <td>
              @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     class="p-thumb"
                     onclick="openLightbox(this.src)"
                     style="cursor:pointer;">
              @else
                <span class="p-thumb-ph">&#128230;</span>
              @endif
            </td>
            <td>
              <strong style="color:var(--text);">{{ $product->name }}</strong>
              @if($product->description)
                <br><small style="color:var(--text3);">{{ Str::limit($product->description, 60) }}</small>
              @endif
            </td>
            <td>
              @if($product->campaign)
                <a href="{{ route('admin.campaign.show', $product->campaign) }}" style="color:var(--a);font-weight:600;">
                  {{ Str::limit($product->campaign->title, 40) }}
                </a>
              @else
                <span style="color:var(--text3);">&mdash;</span>
              @endif
            </td>
            <td>
              @if($product->user)
                <span style="color:var(--text);">{{ $product->user->name }}</span><br>
                <small style="color:var(--text3);">{{ $product->user->email }}</small>
              @else
                <span style="color:var(--text3);">&mdash;</span>
              @endif
            </td>
            <td style="font-family:var(--mono);color:var(--text);">&#8377;{{ number_format($product->price, 2) }}</td>
            <td style="font-family:var(--mono);color:var(--text);">{{ $product->quantity }}</td>
            <td style="font-family:var(--mono);color:var(--text);font-size:12px;">
              @if($product->quantity > 0)
                {{ $product->remaining_quantity }} / {{ $product->quantity }}
                <span style="color:{{ $product->remaining_quantity <= 0 ? 'var(--red)' : 'var(--green)' }};font-size:11px;">
                  ({{ round(($product->remaining_quantity / $product->quantity) * 100) }}%)
                </span>
              @else
                &mdash;
              @endif
            </td>
            <td>
              <span style="font-size:12px;color:var(--text2);">
                {{ $product->categoryProduct?->category?->name ?? $product->categoryProduct?->name ?? '—' }}
              </span>
            </td>
            <td>
              <span class="badge {{ $product->source === 'admin' ? 'b-active' : 'b-paused' }}">
                {{ ucfirst($product->source) }}
              </span>
            </td>
            <td>
              @if($product->approval_status === 'approved')
                <span class="badge b-active">Approved</span>
              @elseif($product->approval_status === 'rejected')
                <span class="badge b-rejected">Rejected</span>
              @else
                <span class="badge b-pending">Pending</span>
              @endif
            </td>
            <td>
              <div class="cp-actions">
                @if($product->approval_status === 'pending')
                  <form action="{{ route('admin.campaign-products.approve', $product) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-green c-btn c-btn-approve">Approve</button>
                  </form>
                  <button type="button" class="btn btn-red c-btn c-btn-reject"
                          onclick="showRejectModal({{ $product->id }}, '{{ addslashes($product->name) }}')">
                    Reject
                  </button>
                @else
                  <span class="cp-meta">
                    @if($product->approved_by && $product->approved_at)
                      by <span class="meta-name">{{ $product->approver?->name ?? 'Admin' }}</span>
                      <br>{{ $product->approved_at->format('d M Y, h:i A') }}
                    @endif
                  </span>
                @endif
                <form action="{{ route('admin.campaign-products.destroy', $product) }}" method="POST"
                      style="display:inline;"
                      onsubmit="return confirm('Delete product &quot;{{ addslashes($product->name) }}&quot;? This cannot be undone.')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-red cp-del" title="Delete product">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m2 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="pagination-wrap" style="margin-top:0;">
      {{ $products->withQueryString()->links() }}
    </div>
  @endif
</div>

{{-- Single Reject Modal --}}
<div class="overlay" id="rejectModal">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeRejectModal()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
    <div class="modal-head">
      <div class="modal-ico" style="background:var(--red-lt);color:var(--red);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
      </div>
      <div>
        <div class="modal-ttl">Reject Product</div>
        <div class="modal-sub">Reject <strong id="rejectProductName"></strong>?</div>
      </div>
    </div>
    <form id="rejectForm" method="POST">
      @csrf
      <div class="modal-lbl">Reason <span>*</span></div>
      <textarea name="reason" class="modal-ta" rows="3" placeholder="Provide a reason for rejection..." required minlength="10" maxlength="500" style="width:100%;"></textarea>
      <div class="modal-acts">
        <button type="button" class="btn btn-secondary modal-btn modal-cancel" onclick="closeRejectModal()">Cancel</button>
        <button type="submit" class="btn btn-red modal-btn modal-red">Reject Product</button>
      </div>
    </form>
  </div>
</div>

{{-- Bulk Reject Modal --}}
<div class="overlay" id="bulkRejectModal">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeBulkRejectModal()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
    <div class="modal-head">
      <div class="modal-ico" style="background:var(--red-lt);color:var(--red);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
      </div>
      <div>
        <div class="modal-ttl">Reject Selected Products</div>
        <div class="modal-sub">Reject <strong id="bulkRejectCount">0</strong> product(s)?</div>
      </div>
    </div>
    <form id="bulkRejectForm" method="POST" action="{{ route('admin.campaign-products.bulk-reject') }}">
      @csrf
      <div id="bulkRejectIds"></div>
      <div class="modal-lbl">Reason <span>*</span></div>
      <textarea name="reason" class="modal-ta" rows="3" placeholder="Provide a reason for rejection..." required minlength="10" maxlength="500" style="width:100%;"></textarea>
      <div class="modal-acts">
        <button type="button" class="btn btn-secondary modal-btn modal-cancel" onclick="closeBulkRejectModal()">Cancel</button>
        <button type="submit" class="btn btn-red modal-btn modal-red">Reject Products</button>
      </div>
    </form>
  </div>
</div>

{{-- Image Lightbox --}}
<div class="overlay" id="imageLightbox" onclick="closeLightbox()" style="cursor:zoom-out;background:rgba(0,0,0,.8);backdrop-filter:blur(8px);">
  <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);max-width:90%;max-height:90%;">
    <img id="lightboxImg" src="" alt="" style="max-width:100%;max-height:90vh;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,.5);">
  </div>
  <button type="button" class="lightbox-close" onclick="closeLightbox()">&times;</button>
</div>

<form id="bulkApproveForm" method="POST" action="{{ route('admin.campaign-products.bulk-approve') }}" style="display:none;">
  @csrf
  <div id="bulkApproveIds"></div>
</form>

@endsection

@push('page_scripts')
<script>
function showRejectModal(id, name) {
  document.getElementById('rejectProductName').textContent = name;
  document.getElementById('rejectForm').action =
    '{{ route('admin.campaign-products.reject', ['product' => '__ID__']) }}'.replace('__ID__', id);
  document.getElementById('rejectModal').classList.add('open');
}
function closeRejectModal() {
  document.getElementById('rejectModal').classList.remove('open');
}
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
  if (e.target === this) closeRejectModal();
});

function toggleAllCheckboxes() {
  var checked = document.getElementById('selectAll').checked;
  document.querySelectorAll('.product-checkbox:not(:disabled)').forEach(function(cb) {
    cb.checked = checked;
  });
  updateBulkBar();
}

function updateBulkBar() {
  var checked = document.querySelectorAll('.product-checkbox:checked:not(:disabled)');
  var bar = document.getElementById('bulkBar');
  if (checked.length > 0) {
    bar.style.display = 'flex';
    document.getElementById('bulkCount').textContent = checked.length;
  } else {
    bar.style.display = 'none';
  }
}

function clearSelection() {
  document.querySelectorAll('.product-checkbox').forEach(function(cb) { cb.checked = false; });
  document.getElementById('selectAll').checked = false;
  updateBulkBar();
}

function bulkApprove() {
  var checked = document.querySelectorAll('.product-checkbox:checked:not(:disabled)');
  if (checked.length === 0) return;

  var ids = [];
  checked.forEach(function(cb) { ids.push(cb.value); });

  var container = document.getElementById('bulkApproveIds');
  container.innerHTML = '';
  ids.forEach(function(id) {
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'ids[]';
    input.value = id;
    container.appendChild(input);
  });

  if (confirm('Approve ' + ids.length + ' product(s)?')) {
    document.getElementById('bulkApproveForm').submit();
  }
}

function openBulkRejectModal() {
  var checked = document.querySelectorAll('.product-checkbox:checked:not(:disabled)');
  if (checked.length === 0) return;

  document.getElementById('bulkRejectCount').textContent = checked.length;

  var container = document.getElementById('bulkRejectIds');
  container.innerHTML = '';
  checked.forEach(function(cb) {
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'ids[]';
    input.value = cb.value;
    container.appendChild(input);
  });

  document.getElementById('bulkRejectModal').classList.add('open');
}
function closeBulkRejectModal() {
  document.getElementById('bulkRejectModal').classList.remove('open');
}
document.getElementById('bulkRejectModal')?.addEventListener('click', function(e) {
  if (e.target === this) closeBulkRejectModal();
});

function openLightbox(src) {
  document.getElementById('lightboxImg').src = src;
  document.getElementById('imageLightbox').classList.add('open');
}
function closeLightbox() {
  document.getElementById('imageLightbox').classList.remove('open');
}
document.getElementById('imageLightbox')?.addEventListener('click', function(e) {
  if (e.target === this) closeLightbox();
});

let _t;
function autoSubmit(){clearTimeout(_t);_t=setTimeout(()=>document.getElementById('filterForm').submit(),400);}
</script>
@endpush
