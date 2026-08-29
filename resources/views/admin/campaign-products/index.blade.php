@push('page_css')
@vite('resources/css/admin/entries/campaign-products.css')
@endpush

@extends('layouts.admin')

@section('page_title', 'Campaign Products')
@section('page_subtitle', 'Review and manage fundraiser products')
@section('sidebar_campaign-products', 'active')

@section('content')

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Catalogue</div>
    <div class="hero-name">Campaign Products</div>
    <div class="hero-sub">
      <svg class="hero-sub-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
      Review and manage fundraiser products. Approve submissions to make them live, or reject with a reason.
    </div>
    <div class="hero-badges">
      <span class="hero-badge hb-purple">{{ $cntTotal }} total</span>
      @if($cntPending > 0)
        <span class="hero-badge hb-amber">● {{ $cntPending }} pending</span>
      @endif
      @if($cntApproved > 0)
        <span class="hero-badge hb-green">✓ {{ $cntApproved }} approved</span>
      @endif
      @if($cntRejected > 0)
        <span class="hero-badge hb-red">✕ {{ $cntRejected }} rejected</span>
      @endif
    </div>
  </div>
</div>

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
      <select class="ftab-select" data-action="tab-select">
        <option value="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}" {{ $status === 'all' ? 'selected' : '' }}>All ({{ $cntTotal }})</option>
        <option value="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}" {{ $status === 'pending' ? 'selected' : '' }}>Pending ({{ $cntPending }})</option>
        <option value="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'approved'])) }}" {{ $status === 'approved' ? 'selected' : '' }}>Approved ({{ $cntApproved }})</option>
        <option value="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'rejected'])) }}" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected ({{ $cntRejected }})</option>
      </select>
    </div>
  </div>

  {{-- FILTER TOOLBAR --}}
  <div class="cp-filter-bar">
    <form method="GET" action="{{ route('admin.campaign-products.index') }}" id="filterForm" class="toolbar">
      <div class="toolbar-left">
        <input type="hidden" name="status" value="{{ $status }}">

        <div class="search-wrap">
          <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          <input type="text" name="search" class="search-input" placeholder="Search product, campaign, owner…"
                 value="{{ $search }}" data-action="auto-submit">
        </div>

        <div class="select-wrap">
          <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
          <select name="source" class="filter-select" data-action="form-submit">
            <option value="">All Sources</option>
            <option value="admin" {{ $source === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ $source === 'user' ? 'selected' : '' }}>User</option>
          </select>
        </div>

        <div class="select-wrap">
          <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
          <select name="category" class="filter-select" data-action="form-submit">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ (string)$categoryId === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>

        <input type="date" name="from" value="{{ $from }}" class="date-input" data-action="form-submit">
        <span class="filter-sep">—</span>
        <input type="date" name="to" value="{{ $to }}" class="date-input" data-action="form-submit">

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
      <button type="button" class="btn btn-green cp-bulk-btn cp-bulk-approve" data-action="bulk-approve">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
        Approve All
      </button>
      <button type="button" class="btn btn-red cp-bulk-btn cp-bulk-reject" data-action="open-bulk-reject">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        Reject All
      </button>
      <button type="button" class="btn btn-secondary cp-bulk-btn cp-bulk-clear" data-action="clear-selection">Clear Selection</button>
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
            <th class="cp-col-id">
              <input type="checkbox" id="selectAll" data-action="toggle-all" class="cp-cursor">
            </th>
            <th class="cp-col-img">Image</th>
            <th>
              <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'name', 'direction' => ($sort === 'name' && $dir === 'asc') ? 'desc' : 'asc'])) }}"
                 class="cp-sort-link">
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
                 class="cp-sort-link">
                Price
                @if($sort === 'price')
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="{{ $dir === 'asc' ? 'M12 5l7 7H5l7-7z' : 'M12 19l7-7H5l7 7z' }}"/></svg>
                @endif
              </a>
            </th>
            <th>
              <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'quantity', 'direction' => ($sort === 'quantity' && $dir === 'asc') ? 'desc' : 'asc'])) }}"
                 class="cp-sort-link">
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
                 class="cp-sort-link">
                Status
                @if($sort === 'approval_status')
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="{{ $dir === 'asc' ? 'M12 5l7 7H5l7-7z' : 'M12 19l7-7H5l7 7z' }}"/></svg>
                @endif
              </a>
            </th>
            <th class="cp-col-actions">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $product)
          <tr>
            <td>
              <input type="checkbox" class="product-checkbox cp-cursor" value="{{ $product->id }}"
                     {{ $product->approval_status !== 'pending' ? 'disabled' : '' }}
                     data-action="update-bulk" class="cp-cursor">
            </td>
            <td>
              @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     class="p-thumb cp-cursor"
                     data-action="open-lightbox">
              @else
                <span class="p-thumb-ph">&#128230;</span>
              @endif
            </td>
            <td>
              <strong class="cp-cell-name">{{ $product->name }}</strong>
              @if($product->description)
                <br><small class="cp-cell-desc">{{ Str::limit($product->description, 60) }}</small>
              @endif
            </td>
            <td>
              @if($product->campaign)
                <a href="{{ route('admin.campaign.show', $product->campaign) }}" class="cp-cell-link">
                  {{ Str::limit($product->campaign->title, 40) }}
                </a>
              @else
                <span class="cp-cell-muted">&mdash;</span>
              @endif
            </td>
            <td>
              @if($product->user)
                <span class="cp-cell-name">{{ $product->user->name }}</span><br>
                <small class="cp-cell-desc">{{ $product->user->email }}</small>
              @else
                <span class="cp-cell-muted">&mdash;</span>
              @endif
            </td>
            <td class="cp-cell-mono">&#8377;{{ number_format($product->price, 2) }}</td>
            <td class="cp-cell-mono">{{ $product->quantity }}</td>
            <td class="cp-cell-remaining">
              @if($product->quantity > 0)
                {{ $product->remaining_quantity }} / {{ $product->quantity }}
                <span class="cp-remaining-pct" style="color:{{ $product->remaining_quantity <= 0 ? 'var(--red)' : 'var(--green)' }};">
                  ({{ round(($product->remaining_quantity / $product->quantity) * 100) }}%)
                </span>
              @else
                &mdash;
              @endif
            </td>
            <td>
              <span class="cp-cat-name">
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
                  <form action="{{ route('admin.campaign-products.approve', $product) }}" method="POST" class="cp-inline">
                    @csrf
                    <button type="submit" class="btn btn-green c-btn c-btn-approve">Approve</button>
                  </form>
                  <button type="button" class="btn btn-red c-btn c-btn-reject"
                          data-action="open-reject" data-id="{{ $product->id }}" data-name="{{ $product->name }}">
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
                      class="cp-inline"
                      data-confirm="Delete product &quot;{{ $product->name }}&quot;? This cannot be undone.">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-red act-btn act-del"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="pagination-wrap cp-pagination">
      {{ $products->withQueryString()->links() }}
    </div>
  @endif
</div>

{{-- Single Reject Modal --}}
<div class="overlay" id="rejectModal">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-modal" data-target="#rejectModal">
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
      <textarea name="reason" class="modal-ta cp-modal-ta" rows="3" placeholder="Provide a reason for rejection..." required minlength="10" maxlength="500"></textarea>
      <div class="modal-acts">
        <button type="button" class="btn btn-secondary modal-btn modal-cancel" data-action="close-modal" data-target="#rejectModal">Cancel</button>
        <button type="submit" class="btn btn-red modal-btn modal-red">Reject Product</button>
      </div>
    </form>
  </div>
</div>

{{-- Bulk Reject Modal --}}
<div class="overlay" id="bulkRejectModal">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-modal" data-target="#bulkRejectModal">
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
      <textarea name="reason" class="modal-ta cp-modal-ta" rows="3" placeholder="Provide a reason for rejection..." required minlength="10" maxlength="500"></textarea>
      <div class="modal-acts">
        <button type="button" class="btn btn-secondary modal-btn modal-cancel" data-action="close-modal" data-target="#bulkRejectModal">Cancel</button>
        <button type="submit" class="btn btn-red modal-btn modal-red">Reject Products</button>
      </div>
    </form>
  </div>
</div>

{{-- Image Lightbox --}}
<div class="overlay cp-lightbox-overlay" id="imageLightbox">
  <div class="cp-lightbox-inner">
    <img id="lightboxImg" src="" alt="" class="cp-lightbox-img">
  </div>
  <button type="button" class="lightbox-close">&times;</button>
</div>

<form id="bulkApproveForm" method="POST" action="{{ route('admin.campaign-products.bulk-approve') }}" style="display:none;">
  @csrf
  <div id="bulkApproveIds"></div>
</form>

{{-- Page data for campaign-products-index.js --}}
<script type="application/json" id="campaignProductsData">@json([
    'rejectUrl' => route('admin.campaign-products.reject', ['product' => '__ID__']),
])</script>

@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/campaign-products-index.js')
@endpush

