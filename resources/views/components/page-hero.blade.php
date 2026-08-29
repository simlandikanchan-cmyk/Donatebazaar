@props([
    'tag' => null,
    'title' => null,
    'subtitle' => null,
    'class' => '',
    'compact' => false,
])

<div class="welcome-banner {{ $compact ? 'welcome-banner--compact' : '' }} {{ $class }}">
    <span class="wb-glow g1"></span>
    <span class="wb-glow g2"></span>
    <svg class="wb-deco" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <circle cx="180" cy="30" r="80" stroke="currentColor" stroke-width=".5" opacity=".06"/>
        <circle cx="180" cy="30" r="100" stroke="currentColor" stroke-width=".5" opacity=".04"/>
        <circle cx="180" cy="30" r="120" stroke="currentColor" stroke-width=".5" opacity=".03"/>
        <path d="M0 200 Q 50 150 100 180 T 200 160" stroke="currentColor" stroke-width=".5" opacity=".04"/>
    </svg>
    <div class="wb-left">
        @if($tag)
            <div class="wb-tag">
                <span class="wb-tag-dot"></span>
                {{ $tag }}
            </div>
        @endif
        @if($title)
            <div class="wb-name">{{ $title }}</div>
        @endif
        @if($subtitle)
            <div class="wb-sub wb-sub--flex">{{ $subtitle }}</div>
        @endif
        @isset($badges)
            <div class="wb-badges">{{ $badges }}</div>
        @endisset
    </div>
    @isset($actions)
        <div class="wb-right">{{ $actions }}</div>
    @endisset
</div>
