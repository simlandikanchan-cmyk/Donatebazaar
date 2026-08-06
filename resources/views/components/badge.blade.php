@props([
    'type' => 'default',
    'size' => 'sm',
])

@php
    $badgeClass = match($type) {
        'active' => 'b-active',
        'pending' => 'b-pending',
        'rejected' => 'b-rejected',
        'paused' => 'b-paused',
        'expired' => 'b-expired',
        'inactive' => 'b-inactive',
        'verified' => 'b-active',
        default => 'b-default',
    };
    $sizeClass = $size === 'md' ? 'badge-md' : 'badge-sm';
@endphp

<span class="badge {{ $badgeClass }} {{ $sizeClass }}">
    {{ $slot }}
</span>
