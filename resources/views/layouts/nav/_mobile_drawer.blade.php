<div id="db-backdrop" class="db-backdrop"></div>

<div id="mobile-drawer"
     class="db-drawer"
     role="dialog"
     aria-modal="true"
     aria-label="Menu"
     aria-hidden="true">

    <header class="db-drawer__header">
        <a href="{{ route('home') }}" class="db-drawer__brand" aria-label="DonateBazaar — Go to homepage">
            <span class="db-drawer__brand-mark" aria-hidden="true">
                <i data-lucide="heart"></i>
            </span>
            <span class="db-drawer__brand-name">DonateBazaar</span>
        </a>
        <button id="mobile-close" class="db-drawer__close" type="button" aria-label="Close menu">
            <i data-lucide="x"></i>
        </button>
    </header>

    <div class="db-drawer__scroll">

        <nav class="db-drawer__nav" aria-label="Primary">

            <a href="{{ route('home') }}"
               class="db-drawer__item {{ request()->routeIs('home') ? 'is-active' : '' }}"
               @if(request()->routeIs('home')) aria-current="page" @endif>
                <span class="db-drawer__item-icon" aria-hidden="true"><i data-lucide="home"></i></span>
                <span class="db-drawer__item-label">Home</span>
            </a>

            <button type="button"
                    class="db-drawer__item db-drawer__item--toggle"
                    aria-expanded="false"
                    aria-controls="drawer-sub-campaigns"
                    data-drawer-toggle="drawer-sub-campaigns">
                <span class="db-drawer__item-icon" aria-hidden="true"><i data-lucide="megaphone"></i></span>
                <span class="db-drawer__item-label">Campaigns</span>
                <span class="db-drawer__chevron" aria-hidden="true"><i data-lucide="chevron-right"></i></span>
            </button>
            <div id="drawer-sub-campaigns" class="db-drawer__sub" inert>
                <div class="db-drawer__subinner">
                    <a href="{{ route('all.campaigns') }}" class="db-drawer__subitem">All Campaigns</a>
                    <a href="{{ route('all.campaigns') }}" class="db-drawer__subitem">Medical</a>
                    <a href="{{ route('all.campaigns') }}" class="db-drawer__subitem">Education</a>
                    <a href="{{ route('all.campaigns') }}" class="db-drawer__subitem">Animal Welfare</a>
                    <a href="{{ route('ddrf.index') }}" class="db-drawer__subitem">Disaster Relief</a>
                </div>
            </div>

            <a href="{{ route('search') }}" class="db-drawer__item">
                <span class="db-drawer__item-icon" aria-hidden="true"><i data-lucide="search"></i></span>
                <span class="db-drawer__item-label">Search</span>
            </a>

            <a href="{{ route('all.campaigns') }}" class="db-drawer__item">
                <span class="db-drawer__item-icon" aria-hidden="true"><i data-lucide="layout-grid"></i></span>
                <span class="db-drawer__item-label">Categories</span>
            </a>

            <button type="button"
                    class="db-drawer__item db-drawer__item--toggle"
                    aria-expanded="false"
                    aria-controls="drawer-sub-about"
                    data-drawer-toggle="drawer-sub-about">
                <span class="db-drawer__item-icon" aria-hidden="true"><i data-lucide="info"></i></span>
                <span class="db-drawer__item-label">About</span>
                <span class="db-drawer__chevron" aria-hidden="true"><i data-lucide="chevron-right"></i></span>
            </button>
            <div id="drawer-sub-about" class="db-drawer__sub" inert>
                <div class="db-drawer__subinner">
                    <a href="{{ route('about') }}" class="db-drawer__subitem">About Us</a>
                    <a href="{{ route('impact') }}" class="db-drawer__subitem">Impact Stories</a>
                    <a href="{{ route('how.it.works') }}" class="db-drawer__subitem">How It Works</a>
                    <a href="{{ route('blogs.index') }}" class="db-drawer__subitem">Blog</a>
                    <a href="{{ route('events.index') }}" class="db-drawer__subitem">Events</a>
                    <a href="{{ route('partnership') }}" class="db-drawer__subitem">Partnerships</a>
                    <a href="{{ route('volunteer.apply') }}" class="db-drawer__subitem">Volunteer</a>
                    <a href="{{ route('application.step1') }}" class="db-drawer__subitem">Become an Organization</a>
                </div>
            </div>

            <a href="{{ route('contact') }}"
               class="db-drawer__item {{ request()->routeIs('contact') ? 'is-active' : '' }}"
               @if(request()->routeIs('contact')) aria-current="page" @endif>
                <span class="db-drawer__item-icon" aria-hidden="true"><i data-lucide="phone"></i></span>
                <span class="db-drawer__item-label">Contact</span>
            </a>

        </nav>

        @auth
            <div class="db-drawer__divider" role="separator"></div>

            <div class="db-drawer__user">
                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/default-avatar.png') }}"
                     alt="{{ e(auth()->user()->name) }}"
                     class="db-drawer__avatar"
                     width="44" height="44" loading="eager">
                <div class="db-drawer__user-meta">
                    <p class="db-drawer__user-name">{{ e(auth()->user()->name) }}</p>
                    <p class="db-drawer__user-email">{{ e(auth()->user()->email) }}</p>
                </div>
            </div>

            <nav class="db-drawer__actions" aria-label="Account">
                <a href="{{ Route::has('campaign.create') ? route('campaign.create') : '/campaign/create' }}"
                   class="db-drawer__cta">
                    <i data-lucide="plus"></i>
                    <span>Start Fundraiser</span>
                </a>

                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                   class="db-drawer__item">
                    <span class="db-drawer__item-icon" aria-hidden="true"><i data-lucide="layout-dashboard"></i></span>
                    <span class="db-drawer__item-label">Dashboard</span>
                </a>
                <a href="{{ route('profile.show') }}" class="db-drawer__item">
                    <span class="db-drawer__item-icon" aria-hidden="true"><i data-lucide="user"></i></span>
                    <span class="db-drawer__item-label">My Profile</span>
                </a>
                <a href="{{ Route::has('my.campaigns') ? route('my.campaigns') : route('dashboard') }}" class="db-drawer__item">
                    <span class="db-drawer__item-icon" aria-hidden="true"><i data-lucide="folder"></i></span>
                    <span class="db-drawer__item-label">My Campaigns</span>
                </a>
                <a href="{{ Route::has('saved.campaigns') ? route('saved.campaigns') : route('dashboard') }}" class="db-drawer__item">
                    <span class="db-drawer__item-icon" aria-hidden="true"><i data-lucide="bookmark"></i></span>
                    <span class="db-drawer__item-label">Saved Campaigns</span>
                </a>
                <a href="{{ route('recurring.index') }}" class="db-drawer__item">
                    <span class="db-drawer__item-icon" aria-hidden="true"><i data-lucide="receipt"></i></span>
                    <span class="db-drawer__item-label">Donation History</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="db-drawer__item">
                    <span class="db-drawer__item-icon" aria-hidden="true"><i data-lucide="settings"></i></span>
                    <span class="db-drawer__item-label">Settings</span>
                </a>
            </nav>
        @endauth

    </div>

    <div class="db-drawer__footer">
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="db-drawer__signout">
                    <i data-lucide="log-out"></i>
                    <span>Sign Out</span>
                </button>
            </form>
        @else
            <div class="db-drawer__auth">
                <a href="{{ route('login') }}" class="db-drawer__auth-btn db-drawer__auth-btn--ghost">Log in</a>
                <a href="{{ route('register') }}" class="db-drawer__auth-btn db-drawer__auth-btn--primary">Get Started</a>
            </div>
        @endauth
    </div>

</div>
