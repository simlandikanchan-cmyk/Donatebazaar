@props([
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
    'disabled' => false,
    'iconLeft' => null,
    'iconRight' => null,
    'fullWidth' => false,
    'rounded' => false,
    'iconOnly' => false,
    'type' => 'button',
    'href' => null,
    'target' => null,
    'asChild' => false,
    'class' => '',
])

@php
    $variant = in_array($variant, ['primary', 'secondary', 'outline', 'ghost', 'destructive', 'link'], true)
        ? $variant
        : 'primary';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';

    $classes = 'btn btn--' . $variant . ' btn--' . $size
        . ($fullWidth ? ' btn--full' : '')
        . ($rounded ? ' btn--pill' : '')
        . ($iconOnly ? ' btn--icon' : '')
        . ($loading ? ' btn--loading' : '')
        . ' ' . $class;

    $isDisabled = $disabled || $loading;

    $buttonAttributes = $attributes->except(['href', 'target', 'type']);
@endphp

@if ($asChild)
    {{-- Render only the slot; caller controls the element --}}
    {{ $slot }}
@elseif ($href)
    <a {{ $buttonAttributes->merge(['class' => trim($classes), 'href' => $href]) }}
       @if ($target) target="{{ $target }}" @endif
       @if ($target === '_blank') rel="noopener noreferrer" @endif
       @if ($isDisabled) aria-disabled="true" @endif>
        @if ($iconLeft)
            <span class="btn__icon">{!! $iconLeft !!}</span>
        @endif
        <span class="btn__label">{{ $slot }}</span>
        @if ($iconRight)
            <span class="btn__icon">{!! $iconRight !!}</span>
        @endif
        @if ($loading)
            <x-spinner />
        @endif
    </a>
@else
    <button {{ $buttonAttributes->merge(['class' => trim($classes), 'type' => $type]) }}
            @if ($isDisabled) disabled @endif
            @if ($loading) aria-busy="true" aria-disabled="true" @endif>
        @if ($iconLeft)
            <span class="btn__icon">{!! $iconLeft !!}</span>
        @endif
        <span class="btn__label">{{ $slot }}</span>
        @if ($iconRight)
            <span class="btn__icon">{!! $iconRight !!}</span>
        @endif
        @if ($loading)
            <x-spinner />
        @endif
    </button>
@endif
