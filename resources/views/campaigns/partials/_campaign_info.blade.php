<div class="card">
    <div class="card-header">
        <div class="card-header-left">
            <div class="card-icon ic-pink">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <div class="card-title">Campaign Info</div>
            </div>
        </div>
    </div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
        <div class="info-row">
            <span class="info-row-lbl">STATUS</span>
            <span class="status-chip {{ $chipClass }}" style="font-size:10px;padding:3px 9px;"><span class="dot"></span> {{ $chipLabel }}</span>
        </div>
        <div class="info-row">
            <span class="info-row-lbl">GOAL</span>
            <span style="font-weight:700;color:var(--text);font-family:var(--font-mono);">₹{{ number_format($campaign->goal_amount) }}</span>
        </div>
        @if($campaign->category)
        <div class="info-row">
            <span class="info-row-lbl">CATEGORY</span>
            <span style="font-weight:600;color:var(--text2);font-size:11px;">{{ $campaign->category->name }}</span>
        </div>
        @endif
        @if($campaign->location)
        <div class="info-row">
            <span class="info-row-lbl">LOCATION</span>
            <span style="font-weight:600;color:var(--text2);font-size:11px;">{{ $campaign->location }}</span>
        </div>
        @endif
        @if($campaign->end_date)
        <div class="info-row">
            <span class="info-row-lbl">END DATE</span>
            <span style="font-weight:600;color:var(--text2);font-size:11px;">{{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y') }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-row-lbl">KYC</span>
            <span style="font-size:11px;font-weight:700;color:{{ $kyc?->status === 'approved' ? '#10b981' : ($kyc?->status === 'pending' ? '#f59e0b' : '#ef4444') }};">
                @if(! $kyc) Not Submitted
                @elseif($kyc->status === 'pending') Pending
                @elseif($kyc->status === 'approved') Verified
                @else Rejected
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-row-lbl">DONORS</span>
            <span style="font-weight:700;color:var(--text);font-family:var(--font-mono);">{{ number_format($donorCount) }}</span>
        </div>
        <div class="info-row">
            <span class="info-row-lbl">UPDATES</span>
            <span style="font-weight:700;color:var(--text);font-family:var(--font-mono);">{{ $updates->count() }}</span>
        </div>
        <div class="info-row">
            <span class="info-row-lbl">EVENTS</span>
            <span style="font-weight:700;color:var(--text);font-family:var(--font-mono);">{{ $campaign->events->count() }}</span>
        </div>
        <div class="info-row">
            <span class="info-row-lbl">CREATED</span>
            <span style="font-weight:600;color:var(--text2);font-size:11px;">{{ $campaign->created_at->format('d M Y') }}</span>
        </div>
    </div>
</div>
