<div class="card" style="margin-bottom:16px;">
    <div class="cover-wrap">
        @if($campaign->cover_image)
            <img src="{{ asset('storage/' . $campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
        @else
            <div class="cover-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <span>No cover image</span>
            </div>
        @endif
        <span class="cover-badge">
            <span style="width:6px;height:6px;border-radius:50%;background:#fff;flex-shrink:0;display:inline-block;"></span>
            {{ $chipLabel }}
        </span>
    </div>
    <div class="campaign-title-block">
        <h2>{{ $campaign->title }}</h2>
        <div class="campaign-meta">Created {{ $campaign->created_at->diffForHumans() }}</div>
        <div class="title-meta-chips">
            @if($campaign->category)
            <span class="title-meta-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                {{ $campaign->category->name }}
            </span>
            @endif
            @if($campaign->location)
            <span class="title-meta-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $campaign->location }}
            </span>
            @endif
            @if($campaign->end_date)
            <span class="title-meta-chip {{ $isEnded ? 'warn' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {{ $isEnded ? 'Will End within ' . abs($daysLeft) . ' days' : $daysLeft . ' days left' }}
            </span>
            @endif
            @if($publicUrl)
            <a href="{{ $publicUrl }}" target="_blank" class="title-meta-chip" style="text-decoration:none;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Public Page
            </a>
            @endif
        </div>
    </div>
</div>
