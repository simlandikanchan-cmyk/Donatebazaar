@props([
    'icon' => 'plus',
    'color' => 'accent',
    'url',
    'label',
    'sub',
])

@php
    $iconMap = [
        'shield' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        'bank' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11m16-11v11M8 14v3m4-3v3m4-3v3"/></svg>',
        'plus' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 4v16m8-8H4"/></svg>',
        'clock' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
    ];
    $colorMap = [
        'accent' => 'accent',
        'yellow' => 'var(--yellow)',
        'red' => 'var(--red)',
        'green' => 'var(--green)',
    ];
    $iconColor = $colorMap[$color] ?? $colorMap['accent'];
@endphp

<a href="{{ $url }}" class="pending-item">
    <div class="pending-ico" style="color: {{ $iconColor }};">
        {!! $iconMap[$icon] ?? $iconMap['plus'] !!}
    </div>
    <div class="pending-body">
        <div class="pending-lbl">{{ $label }}</div>
        <div class="pending-sub">{{ $sub }}</div>
    </div>
    <svg class="pending-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
</a>
