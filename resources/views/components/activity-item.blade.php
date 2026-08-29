@props([
    'color' => 'green',
    'initial',
])

@php
    $colorMap = [
        'green' => 'd-green',
        'yellow' => 'd-yellow',
        'pink' => 'd-pink',
        'blue' => 'd-blue',
        'primary' => 'd-primary',
        'secondary' => 'd-secondary',
        'red' => 'd-red',
    ];
    $dotClass = $colorMap[$color] ?? 'd-green';
@endphp

<div class="activity-item">
    <div class="activity-dot-col">
        <div class="activity-dot {{ $dotClass }}"><span class="ad-letter">{{ $initial ?? '?' }}</span></div>
        <div class="activity-line"></div>
    </div>
    <div class="activity-body">
        {{ $slot }}
    </div>
</div>
