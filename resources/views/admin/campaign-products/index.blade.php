@extends('layouts.admin')

@section('page_title', 'Campaign Products')
@section('page_subtitle', 'Review and manage fundraiser products')

@section('sidebar_campaign-products', 'active')

@section('content')

<div class="sec-hdr">
    <div class="ftabs">
        <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}"
           class="ftab {{ $status === 'all' ? 'on' : '' }}">
            All <span class="cnt">{{ $cntTotal }}</span>
        </a>
        <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}"
           class="ftab {{ $status === 'pending' ? 'on' : '' }}">
            Pending <span class="cnt">{{ $cntPending }}</span>
        </a>
        <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'approved'])) }}"
           class="ftab {{ $status === 'approved' ? 'on' : '' }}">
            Approved <span class="cnt">{{ $cntApproved }}</span>
        </a>
        <a href="{{ route('admin.campaign-products.index', array_merge(request()->except(['status', 'page']), ['status' => 'rejected'])) }}"
           class="ftab {{ $status === 'rejected' ? 'on' : '' }}">
            Rejected <span class="cnt">{{ $cntRejected }}</span>
        </a>
    </div>
</div>

<div class="filter-bar" style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-bottom:16px;padding:14px 18px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r-sm);">

    <form method="GET" action="{{ route('admin.campaign-products.index') }}" id="filterForm" style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;flex:1;">
        <input type="hidden" name="status" value="{{ $status }}">

        <div style="position:relative;flex:1;min-width:200px;">
            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--text3);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" name="search" placeholder="Search product, campaign, owner..."
                   value="{{ $search }}"
                   style="width:100%;padding:9px 14px 9px 36px;border:1px solid var(--border2);border-radius:var(--r-xs);background:var(--bg);color:var(--text);font-size:13px;outline:none;"
                   onchange="this.form.submit()">
        </div>

        <select name="source" onchange="this.form.submit()"
                style="padding:9px 14px;border:1px solid var(--border2);border-radius:var(--r-xs);background:var(--bg);color:var(--text);font-size:13px;outline:none;min-width:120px;">
            <option value="">All Sources</option>
            <option value="admin" {{ $source === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ $source === 'user' ? 'selected' : '' }}>User</option>
        </select>

        <select name="category" onchange="this.form.submit()"
                style="padding:9px 14px;border:1px solid var(--border2);border-radius:var(--r-xs);background:var(--bg);color:var(--text);font-size:13px;outline:none;min-width:150px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ (string)$categoryId === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>

        <input type="date" name="from" value="{{ $from }}"
               onchange="this.form.submit()"
               style="padding:9px 14px;border:1px solid var(--border2);border-radius:var(--r-xs);background:var(--bg);color:var(--text);font-size:13px;outline:none;">

        <span style="color:var(--text3);font-size:13px;">—</span>

        <input type="date" name="to" value="{{ $to }}"
               onchange="this.form.submit()"
               style="padding:9px 14px;border:1px solid var(--border2);border-radius:var(--r-xs);background:var(--bg);color:var(--text);font-size:13px;outline:none;">

        @if($search || $source || $categoryId || $from || $to)
            <a href="{{ route('admin.campaign-products.index', ['status' => $status]) }}"
               style="padding:9px 14px;border:1px solid var(--border2);border-radius:var(--r-xs);color:var(--text3);font-size:13px;text-decoration:none;">
                &#10005; Clear
            </a>
        @endif

        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $dir }}">
    </form>

    <a href="{{ route('admin.campaign-products.export', request()->only(['status', 'search', 'source', 'category'])) }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border:1px solid var(--border2);border-radius:var(--r-xs);color:var(--text2);font-size:13px;text-decoration:none;white-space:nowrap;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
        Export CSV
    </a>
</div>

<div id="bulkBar" style="display:none;padding:12px 18px;background:var(--a-lt);border:1px solid var(--a);border-radius:var(--r-xs);margin-bottom:14px;align-items:center;gap:12px;flex-wrap:wrap;">
    <span style="font-size:13px;font-weight:600;color:var(--a);">
        <span id="bulkCount">0</span> product(s) selected
    </span>
    <button type="button" class="c-btn c-btn-approve" style="padding:7px 16px;font-size:13px;" onclick="bulkApprove()">
        Approve All
    </button>
    <button type="button" class="c-btn c-btn-reject" style="padding:7px 16px;font-size:13px;" onclick="openBulkRejectModal()">
        Reject All
    </button>
    <button type="button" style="padding:7px 16px;border:1px solid var(--border2);border-radius:var(--r-xs);background:transparent;color:var(--text3);font-size:13px;cursor:pointer;" onclick="clearSelection()">
        Clear Selection
    </button>
</div>

@if($products->isEmpty())
    <div id="noResults" style="display:block;text-align:center;padding:60px 20px;">
        <svg style="width:48px;height:48px;margin:0 auto 16px;color:var(--text3);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
        <strong style="display:block;color:var(--text2);font-size:16px;margin-bottom:4px;">No products found</strong>
        <span style="color:var(--text3);font-size:13px;">There are no {{ $status !== 'all' ? $status : '' }} products to display.</span>
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
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            @if($product->approval_status === 'pending')
                                <form action="{{ route('admin.campaign-products.approve', $product) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="c-btn c-btn-approve" style="padding:7px 12px;font-size:12px;">Approve</button>
                                </form>
                                <button type="button" class="c-btn c-btn-reject" style="padding:7px 12px;font-size:12px;"
                                        onclick="showRejectModal({{ $product->id }}, '{{ addslashes($product->name) }}')">
                                    Reject
                                </button>
                            @else
                                <span style="font-size:11px;color:var(--text3);line-height:1.5;">
                                    @if($product->approved_by && $product->approved_at)
                                        by {{ $product->approver?->name ?? 'Admin' }}
                                        <br>{{ $product->approved_at->format('d M Y, h:i A') }}
                                    @endif
                                </span>
                            @endif
                            <form action="{{ route('admin.campaign-products.destroy', $product) }}" method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Delete product &quot;{{ addslashes($product->name) }}&quot;? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding:5px 10px;border:1px solid var(--border2);border-radius:var(--r-xs);background:transparent;color:var(--text3);font-size:11px;cursor:pointer;"
                                        title="Delete product">
                                    &#128465;
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap" style="margin-top:16px;">
        {{ $products->withQueryString()->links() }}
    </div>
@endif

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
                <button type="button" class="modal-btn modal-cancel" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="modal-btn modal-red">Reject Product</button>
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
                <button type="button" class="modal-btn modal-cancel" onclick="closeBulkRejectModal()">Cancel</button>
                <button type="submit" class="modal-btn modal-red">Reject Products</button>
            </div>
        </form>
    </div>
</div>

{{-- Image Lightbox --}}
<div class="overlay" id="imageLightbox" onclick="closeLightbox()" style="cursor:zoom-out;background:rgba(0,0,0,.8);backdrop-filter:blur(8px);">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);max-width:90%;max-height:90%;">
        <img id="lightboxImg" src="" alt="" style="max-width:100%;max-height:90vh;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,.5);">
    </div>
    <button type="button" style="position:absolute;top:20px;right:30px;background:none;border:none;color:#fff;font-size:32px;cursor:pointer;" onclick="closeLightbox()">&times;</button>
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
</script>
@endpush
