@props([
    'color' => 'primary',
    'label' => '',
    'value' => '',
    'footer' => null,
    'href' => null,
    'icon' => null,
])

@php
    $colorMap = [
        'primary' => ['si' => 'si-indigo', 'sv' => 'sv-indigo'],
        'pink' => ['si' => 'si-pink', 'sv' => 'sv-pink'],
        'green' => ['si' => 'si-green', 'sv' => 'sv-green'],
        'yellow' => ['si' => 'si-yellow', 'sv' => 'sv-yellow'],
        'blue' => ['si' => 'si-blue', 'sv' => 'sv-blue'],
        'secondary' => ['si' => 'si-purple', 'sv' => 'sv-purple'],
        'red' => ['si' => 'si-red', 'sv' => 'sv-red'],
    ];
    $c = $colorMap[$color] ?? $colorMap['primary'];
    $isLink = filled($href);
@endphp

@if ($isLink)
    <a href="{{ $href }}" class="stat-card is-link">
@else
    <div class="stat-card">
@endif

    @if ($icon)
        <div class="stat-icon-wrap {{ $c['si'] }}">
            {!! $icon !!}
        </div>
    @endif

    <div class="stat-info">
        <div class="stat-label">{{ $label }}</div>
        <div class="stat-val {{ $c['sv'] }}">{{ $value }}</div>
        @if ($footer)
            <div class="stat-foot">{{ $footer }}</div>
        @endif
    </div>

@if ($isLink)
    </a>
@else
    </div>
@endif
