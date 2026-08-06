@props([
    'size' => null,
    'class' => '',
])

<span {{ $attributes->merge(['class' => 'spinner' . ($size ? ' spinner--' . $size : '') . ' ' . $class]) }}
      aria-hidden="true"></span>
