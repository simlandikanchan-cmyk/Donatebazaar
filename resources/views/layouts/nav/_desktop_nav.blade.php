<nav class="db-navbar__nav" role="navigation" aria-label="Primary navigation">

    <a href="{{ route('home') }}"
       class="db-nav__link {{ request()->routeIs('home') ? 'db-nav__link--active' : '' }}"
       @if(request()->routeIs('home')) aria-current="page" @endif>
        Home
    </a>

    <a href="{{ route('all.campaigns') }}"
       class="db-nav__link {{ request()->routeIs('all.campaigns') ? 'db-nav__link--active' : '' }}"
       @if(request()->routeIs('all.campaigns')) aria-current="page" @endif>
        Campaigns
    </a>

    <div class="db-nav__dropdown" role="none">
        <button class="db-nav__link db-nav__dropdown-trigger"
                aria-haspopup="true"
                aria-expanded="false"
                aria-controls="about-menu"
                id="about-trigger">
            About
            <svg class="db-nav__chevron" width="11" height="11" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.5"
                 aria-hidden="true" focusable="false">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>

        <div class="db-nav__dropdown-menu"
             role="menu"
             id="about-menu"
             aria-labelledby="about-trigger">
            <a href="{{ route('about') }}" class="db-nav__dropdown-item" role="menuitem">About Us</a>
            <a href="{{ route('impact') }}" class="db-nav__dropdown-item" role="menuitem">Impact Stories</a>
            <a href="{{ route('how.it.works') }}" class="db-nav__dropdown-item" role="menuitem">How It Works</a>
            <a href="{{ route('blogs.index') }}" class="db-nav__dropdown-item" role="menuitem">Blog</a>
            <a href="{{ route('events.index') }}"
               class="db-nav__dropdown-item {{ request()->routeIs('events.index*') ? 'db-nav__dropdown-item--active' : '' }}"
               role="menuitem">Events</a>
            <a href="{{ route('partnership') }}" class="db-nav__dropdown-item" role="menuitem">Partnership</a>
            <a href="{{ route('ddrf.index') }}" class="db-nav__dropdown-item" role="menuitem">Disaster Relief</a>
            <a href="{{ route('volunteer.apply') }}" class="db-nav__dropdown-item" role="menuitem">Volunteer</a>
            <a href="{{ route('application.step1') }}" class="db-nav__dropdown-item" role="menuitem">Become an Organization</a>
        </div>
    </div>

    <a href="{{ route('contact') }}"
       class="db-nav__link {{ request()->routeIs('contact') ? 'db-nav__link--active' : '' }}"
       @if(request()->routeIs('contact')) aria-current="page" @endif>
        Contact
    </a>

</nav>
