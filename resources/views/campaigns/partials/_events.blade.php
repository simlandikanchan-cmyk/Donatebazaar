<div class="card">
    <div class="card-header">
        <div class="card-header-left">
            <div class="card-icon ic-yellow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="card-title">Campaign Events</div>
                <div class="card-sub">{{ $campaign->events->count() }} event{{ $campaign->events->count() !== 1 ? 's' : '' }}</div>
            </div>
        </div>
        <a href="{{ route('events.create', $campaign->id) }}" class="create-event-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Create Event
        </a>
    </div>
    <div class="card-body">
        @if($campaign->events->count() > 0)
        <div class="events-grid">
            @foreach($campaign->events as $event)
            @php
                $evClass = match($event->status) {
                    'approved' => 'ev-approved',
                    'pending'  => 'ev-pending',
                    default    => 'ev-default',
                };
            @endphp
            <div class="event-card">
                <span class="event-badge {{ $evClass }}">{{ ucfirst($event->status) }}</span>
                <div class="event-title">{{ $event->title }}</div>
                <div class="event-date">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</div>
                <div class="event-desc">{{ Str::limit($event->description, 110) }}</div>
                <a href="{{ route('events.show', $event->id) }}" class="event-link">
                    View details
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p>No events yet — create one to get started.</p>
        </div>
        @endif
    </div>
</div>
