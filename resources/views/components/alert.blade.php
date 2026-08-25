@props([
    'type' => 'info',
])

@php
    $typeClasses = [
        'info' => 'alert-info',
        'success' => 'alert-success',
        'warning' => 'alert-warning',
        'danger' => 'alert-danger',
    ];
    $class = $typeClasses[$type] ?? 'alert-info';
@endphp

<div class="alert {{ $class }}" role="alert">
    {{ $slot }}
</div>
