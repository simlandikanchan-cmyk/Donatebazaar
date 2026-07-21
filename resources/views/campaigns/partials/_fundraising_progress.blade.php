<div class="card">
    <div class="card-header">
        <div class="card-header-left">
            <div class="card-icon ic-indigo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="card-title">Fundraising</div>
                <div class="card-sub">Current progress</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="prog-numbers">
            <div class="prog-raised">₹{{ number_format($raised) }}</div>
            <div class="prog-goal">of ₹{{ number_format($campaign->goal_amount) }}</div>
        </div>
        <div class="prog-bar">
            <div class="prog-fill" style="width:{{ $percentage }}%"></div>
        </div>
        <div class="prog-pct">
            @if($isOverfunded)
                <span style="color:#10b981;font-weight:700;">{{ $rawPercent }}% funded — goal exceeded!</span>
            @else
                {{ $percentage }}% funded
            @endif
        </div>
        <div class="mini-stats">
            <div class="mini-stat">
                <div class="mini-stat-val">{{ $rawPercent }}%</div>
                <div class="mini-stat-lbl">Completed</div>
            </div>
            @if($isOverfunded)
                <div class="mini-stat" style="background:rgba(16,185,129,0.08);border-color:rgba(16,185,129,0.25);">
                    <div class="mini-stat-val" style="font-size:14px;color:#10b981;">+₹{{ number_format($surplus) }}</div>
                    <div class="mini-stat-lbl" style="color:#10b981;">Overfunded</div>
                </div>
            @else
                <div class="mini-stat">
                    <div class="mini-stat-val" style="font-size:14px;">₹{{ number_format($remaining) }}</div>
                    <div class="mini-stat-lbl">Remaining</div>
                </div>
            @endif
        </div>
        <div class="mini-stats-row2">
            <div class="mini-stat">
                <div class="mini-stat-val">{{ number_format($donorCount) }}</div>
                <div class="mini-stat-lbl">Donors</div>
            </div>
            <div class="mini-stat">
                <div class="mini-stat-val" style="font-size:14px;">₹{{ number_format($avgDonation) }}</div>
                <div class="mini-stat-lbl">Avg. Donation</div>
            </div>
        </div>
        @if($daysLeft !== null)
        <div class="mini-stats-row2">
            <div class="mini-stat" style="grid-column:1 / -1;{{ $isEnded ? 'background:rgba(239,68,68,0.06);border-color:rgba(239,68,68,0.2);' : '' }}">
                <div class="mini-stat-val" style="font-size:14px;{{ $isEnded ? 'color:#ef4444;' : '' }}">
                    {{ $isEnded ? 'Campaign will end within ' . abs($daysLeft) . ' days ' : $daysLeft . ' days left' }}
                </div>
                <div class="mini-stat-lbl" style="{{ $isEnded ? 'color:#ef4444;' : '' }}">{{ $isEnded ? 'Status' : 'Time Remaining' }}</div>
            </div>
        </div>
        @endif
        @if($donorCount > 0)
        <div class="donor-mini-list">
            @foreach($recentDonors as $d)
            <div class="donor-mini-row">
                <span class="donor-mini-name">{{ $d->is_anonymous ? 'Anonymous Donor' : ($d->donor_name ?? 'Anonymous') }}</span>
                <span class="donor-mini-amt">₹{{ number_format($d->total_amount) }}</span>
            </div>
            @endforeach
            @if($lastDonation)
            <div style="font-size:10px;color:var(--text3);text-align:center;margin-top:2px;">Last donation {{ \Carbon\Carbon::parse($lastDonation->created_at)->diffForHumans() }}</div>
            @endif
        </div>
        @else
        <div class="donor-mini-list">
            <div class="donor-mini-empty">No donations yet — share your campaign to get started.</div>
        </div>
        @endif
    </div>
</div>
