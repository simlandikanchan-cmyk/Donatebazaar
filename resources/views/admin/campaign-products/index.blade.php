@extends('layouts.admin')

@section('page_title', 'Campaign Products')
@section('page_subtitle', 'Review and manage fundraiser products')

@section('sidebar_campaign-products', 'active')

@section('content')

<div class="card-toolbar">
    <div class="filter-tabs">
        <a href="{{ route('admin.campaign-products.index', ['status' => 'all']) }}"
           class="filter-tab {{ $status === 'all' ? 'active' : '' }}">
            All
            <span class="filter-count">{{ $cntTotal }}</span>
        </a>
        <a href="{{ route('admin.campaign-products.index', ['status' => 'pending']) }}"
           class="filter-tab {{ $status === 'pending' ? 'active' : '' }}">
            Pending
            <span class="filter-count">{{ $cntPending }}</span>
        </a>
        <a href="{{ route('admin.campaign-products.index', ['status' => 'approved']) }}"
           class="filter-tab {{ $status === 'approved' ? 'active' : '' }}">
            Approved
            <span class="filter-count">{{ $cntApproved }}</span>
        </a>
        <a href="{{ route('admin.campaign-products.index', ['status' => 'rejected']) }}"
           class="filter-tab {{ $status === 'rejected' ? 'active' : '' }}">
            Rejected
            <span class="filter-count">{{ $cntRejected }}</span>
        </a>
    </div>
</div>

@if($products->isEmpty())
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:48px;height:48px;color:var(--a);opacity:.4;"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
        <h3>No products found</h3>
        <p>There are no {{ $status !== 'all' ? $status : '' }} products to display.</p>
    </div>
@else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:50px;">Image</th>
                    <th>Product</th>
                    <th>Campaign</th>
                    <th>Owner</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th style="width:200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 style="width:40px;height:40px;border-radius:8px;object-fit:cover;border:1px solid var(--c);"
                                 onerror="this.style.display='none'">
                        @else
                            <span style="display:inline-flex;width:40px;height:40px;border-radius:8px;background:var(--d);align-items:center;justify-content:center;font-size:14px;color:var(--a);">📦</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $product->name }}</strong>
                        @if($product->description)
                            <br><small style="color:var(--b);">{{ Str::limit($product->description, 60) }}</small>
                        @endif
                    </td>
                    <td>
                        @if($product->campaign)
                            <a href="{{ route('admin.campaign.show', $product->campaign) }}" style="color:var(--a);">
                                {{ Str::limit($product->campaign->title, 40) }}
                            </a>
                        @else
                            <span style="color:var(--b);">—</span>
                        @endif
                    </td>
                    <td>
                        @if($product->user)
                            {{ $product->user->name }}<br>
                            <small style="color:var(--b);">{{ $product->user->email }}</small>
                        @else
                            <span style="color:var(--b);">—</span>
                        @endif
                    </td>
                    <td>₹{{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>
                        <span class="badge {{ $product->source === 'admin' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($product->source) }}
                        </span>
                    </td>
                    <td>
                        @if($product->approval_status === 'approved')
                            <span class="badge badge-success">Approved</span>
                        @elseif($product->approval_status === 'rejected')
                            <span class="badge badge-danger">Rejected</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($product->approval_status === 'pending')
                            <form action="{{ route('admin.campaign-products.approve', $product) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <button type="button" class="btn btn-sm btn-danger"
                                    onclick="showRejectModal({{ $product->id }}, '{{ addslashes($product->name) }}')">
                                Reject
                            </button>
                        @else
                            <span style="font-size:12px;color:var(--b);">
                                @if($product->approved_by && $product->approved_at)
                                    by {{ $product->approver?->name ?? 'Admin' }}
                                    <br>{{ $product->approved_at->format('d M Y, h:i A') }}
                                @endif
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $products->withQueryString()->links() }}
    </div>
@endif

{{-- Reject Modal --}}
<div class="modal-overlay" id="rejectModal" style="display:none;">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Reject Product</h3>
            <button type="button" onclick="closeRejectModal()" style="background:none;border:none;font-size:20px;cursor:pointer;">&times;</button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="modal-body">
                <p style="margin-bottom:12px;">Reject <strong id="rejectProductName"></strong>?</p>
                <div class="field-wrap">
                    <label class="field-label">Reason <span>*</span></label>
                    <textarea name="reason" class="field-input" rows="3" placeholder="Provide a reason for rejection..." required minlength="10" maxlength="500"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Reject Product</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('page_scripts')
<script>
function showRejectModal(id, name) {
    document.getElementById('rejectProductName').textContent = name;
    document.getElementById('rejectForm').action = '{{ route('admin.campaign-products.reject', '') }}/' + id;
    document.getElementById('rejectModal').style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endpush
