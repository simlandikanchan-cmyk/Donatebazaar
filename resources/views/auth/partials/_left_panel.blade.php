<div class="deco-ring deco-ring-1"></div>
<div class="deco-ring deco-ring-2"></div>
<div class="deco-ring deco-ring-3"></div>
<div class="deco-blob"></div>

<a href="{{ route('home') }}" class="brand">
    <span class="brand-icon">
        <svg width="19" height="19" viewBox="0 0 24 24" fill="rgba(37,99,235,0.9)">
            <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402C1 3.335 4.18 1 7.5 1c1.862 0 3.706.902 4.5 2.338C12.794 1.902 14.638 1 16.5 1 19.82 1 23 3.335 23 7.191c0 4.105-5.37 8.863-11 14.402z"/>
        </svg>
    </span>
    <span class="brand-name">DonateBazaar</span>
</a>

<div class="left-content">
    <div class="left-tag">
        <span class="tag-dot"></span>
        {{ $tag ?? 'Welcome back' }}
    </div>
    <h1 class="left-heading">
        {!! $heading ?? 'Good to See<br><span class="dim">You Again</span>' !!}
    </h1>
    <p class="left-sub">{{ $subtitle ?? '' }}</p>
</div>
