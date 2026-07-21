<div class="card" style="margin-bottom:16px;">
    <div class="card-header">
        <div class="card-header-left">
            <div class="card-icon ic-yellow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <div class="card-title">Updates &amp; Documents</div>
                <div class="card-sub">{{ $updates->count() }} update{{ $updates->count() !== 1 ? 's' : '' }} submitted</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($updates->count() > 0)
            <div class="updates-list">
                @foreach($updates as $update)
                <div class="update-item">
                    <div class="update-item-header">
                        <div class="update-item-title">{{ $update->title }}</div>
                        <div class="update-item-date">{{ \Carbon\Carbon::parse($update->created_at)->format('d M Y') }}</div>
                    </div>
                    @if($update->body)
                    <div class="update-item-body">{{ $update->body }}</div>
                    @endif
                    @if($update->document_url)
                    <a href="{{ asset('storage/'.$update->document_url) }}" target="_blank" class="update-doc-pill">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        View attached document
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p>No updates or documents submitted for this campaign.</p>
            </div>
        @endif
    </div>
</div>
