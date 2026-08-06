@props([
    'padding' => 'md',
    'shadow' => true,
    'class' => '',
])

@php
    $paddingClasses = [
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];
    $pad = $paddingClasses[$padding] ?? 'p-6';
    $shadowClass = $shadow ? 'shadow-sm' : '';
@endphp

<div {{ $attributes->merge(['class' => "bg-white rounded-lg border border-gray-200 $pad $shadowClass $class"]) }}>
    {{ $slot }}
</div>
