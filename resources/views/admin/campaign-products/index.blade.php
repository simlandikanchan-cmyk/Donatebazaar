@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/campaigns.css')
@endpush


@section('page_title', 'Campaign Products')
@section('page_subtitle', 'Review and manage fundraiser products')
@section('sidebar_campaign-products', 'active')

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
      <select class="ftab-select" onchange="window.location.href=this.value">
        <option value="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}" {{ $status === 'all' ? 'selected' : '' }}>All ({{ $cntTotal }})</option>
        <option value="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}" {{ $status === 'pending' ? 'selected' : '' }}>Pending ({{ $cntPending }})</option>
        <option value="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'approved'])) }}" {{ $status === 'approved' ? 'selected' : '' }}>Approved ({{ $cntApproved }})</option>
        <option value="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'rejected'])) }}" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected ({{ $cntRejected }})</option>
      </select>
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
      <x-button variant="primary" type="button" class="cp-bulk-approve">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
        Approve All
      </x-button>
      <x-button variant="destructive" type="button" class="cp-bulk-reject">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        Reject All
      </x-button>
      <x-button variant="secondary" type="button" class="cp-bulk-clear">Clear Selection</x-button>
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
                    <x-button variant="primary" type="submit" class="c-btn">Approve</x-button>
                  </form>
                  <x-button variant="destructive" type="button" class="c-btn" onclick="showRejectModal({{ $product->id }}, '{{ addslashes($product->name) }}')">
                    Reject
                  </x-button>
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
                  <x-button variant="destructive" type="submit" class="cp-del">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m2 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"/></svg>
                  </x-button>
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
        <x-button variant="secondary" type="button" class="modal-btn">Cancel</x-button>
        <x-button variant="destructive" type="submit" class="modal-btn">Reject Product</x-button>
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
        <x-button variant="secondary" type="button" class="modal-btn">Cancel</x-button>
        <x-button variant="destructive" type="submit" class="modal-btn">Reject Products</x-button>
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
