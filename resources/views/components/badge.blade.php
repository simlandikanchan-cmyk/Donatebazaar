@props([
    'type' => 'default',
    'size' => 'sm',
])

@php
    $sizeClasses = [
        'sm' => 'font-size:10px;padding:2px 8px;',
        'md' => 'font-size:11px;padding:3px 10px;',
    ];
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
@endphp

<span class="badge {{ $badgeClass }}" @if(isset($sizeClasses[$size])) style="{{ $sizeClasses[$size] }}" @endif>
    {{ $slot }}
</span>
