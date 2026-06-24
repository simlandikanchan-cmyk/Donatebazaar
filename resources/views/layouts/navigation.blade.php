{{--
     Security notes:
    • All user-supplied output uses {{ }} (auto-escapes via e() / htmlspecialchars).
    • No {!! !!} raw output of user data anywhere.
    • Avatar URL is asset()-resolved — never user-controlled raw HTML.
    • CSRF token is present on the logout form.
    • Logout uses POST (not a GET link), preventing CSRF via prefetch.
    • Notification count is cast to int before display to prevent injection.
    • Role check uses strict string comparison.
--}}

<header id="db-navbar" class="db-navbar" role="banner">

    <div class="db-navbar__inner">

        {{-- ── Brand ── --}}
        <a href="{{ route('home') }}"
           class="db-navbar__brand"
           aria-label="{{ config('app.name', 'DonateBazaar') }} — Go to homepage">
            <span class="db-navbar__brand-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"
                     xmlns="http://www.w3.org/2000/svg" focusable="false">
                    <path d="M12 21.593C6.37 16.054 1 11.296 1 7.191 1 3.335 4.18 1 7.5 1c1.862
                             0 3.706.902 4.5 2.338C12.794 1.902 14.638 1 16.5 1 19.82 1 23 3.335
                             23 7.191c0 4.105-5.37 8.863-11 14.402z"/>
                </svg>
            </span>
            <span class="db-navbar__brand-name">DonateBazaar</span>
        </a>

        {{-- ── Desktop nav ── --}}
        <nav class="db-navbar__nav" role="navigation" aria-label="Primary navigation">

            <a href="{{ route('home') }}"
               class="db-nav__link {{ request()->routeIs('home') ? 'db-nav__link--active' : '' }}"
               @if(request()->routeIs('home')) aria-current="page" @endif>
                Home
            </a>

            <a href="{{ route('all.campaigns') }}"
               class="db-nav__link {{ request()->routeIs('all.campaigns*') ? 'db-nav__link--active' : '' }}"
               @if(request()->routeIs('all.campaigns*')) aria-current="page" @endif>
                Campaigns
            </a>

            {{-- About dropdown --}}
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
                    <a href="{{ route('about') }}"
                       class="db-nav__dropdown-item"
                       role="menuitem">
                        About Us
                    </a>
                    <a href="{{ route('how.it.works') }}"
                       class="db-nav__dropdown-item"
                       role="menuitem">
                        How It Works
                    </a>
                    <a href="{{ route('blogs.index') }}"
                       class="db-nav__dropdown-item"
                       role="menuitem">
                        Blog
                    </a>
                </div>
            </div>

            <a href="{{ route('contact') }}"
               class="db-nav__link {{ request()->routeIs('contact') ? 'db-nav__link--active' : '' }}"
               @if(request()->routeIs('contact')) aria-current="page" @endif>
                Contact
            </a>

        </nav>

        {{-- ── Right actions ── --}}
        <div class="db-navbar__actions">

            @auth

                <a href="{{ Route::has('campaign.create') ? route('campaign.create') : '/campaign/create' }}"
                   class="db-btn db-btn--primary"
                   aria-label="Start a new fundraiser">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.8" stroke-linecap="round"
                         aria-hidden="true" focusable="false">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Start Fundraise
                </a>

                {{-- Notification bell --}}
                @php $unread = (int) auth()->user()->unreadNotifications()->count(); @endphp
                <button class="db-icon-btn"
                        id="notif-btn"
                        aria-label="Notifications{{ $unread > 0 ? " — {$unread} unread" : '' }}"
                        aria-haspopup="true"
                        aria-expanded="false">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true" focusable="false">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    @if($unread > 0)
                        <span class="db-badge" aria-hidden="true">
                            {{ $unread > 9 ? '9+' : $unread }}
                        </span>
                    @endif
                </button>

                {{-- Profile dropdown --}}
                <div class="db-profile" id="db-profile-wrapper">
                    <button class="db-profile__trigger"
                            id="profile-btn"
                            aria-haspopup="true"
                            aria-expanded="false"
                            aria-controls="profile-menu"
                            aria-label="Account menu for {{ e(auth()->user()->name) }}">
                        <img src="{{ auth()->user()->avatar
                                ? asset('storage/' . auth()->user()->avatar)
                                : asset('images/default-avatar.png') }}"
                             alt="{{ e(auth()->user()->name) }}"
                             class="db-profile__avatar"
                             width="30" height="30"
                             loading="eager">
                        <svg class="db-profile__chevron" width="11" height="11"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.5" stroke-linecap="round"
                             aria-hidden="true" focusable="false">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>

                    <div class="db-dropdown"
                         id="profile-menu"
                         role="menu"
                         aria-labelledby="profile-btn"
                         aria-hidden="true">

                        <div class="db-dropdown__header" role="none">
                            {{-- ✅ FIXED: Show actual avatar image instead of initials --}}
                            <img src="{{ auth()->user()->avatar
                                    ? asset('storage/' . auth()->user()->avatar)
                                    : asset('images/default-avatar.png') }}"
                                 alt="{{ e(auth()->user()->name) }}"
                                 class="db-dropdown__avatar"
                                 width="38" height="38"
                                 loading="eager">
                            <div class="db-dropdown__user-info">
                                <p class="db-dropdown__name">{{ e(auth()->user()->name) }}</p>
                                <p class="db-dropdown__email">{{ e(auth()->user()->email) }}</p>
                            </div>
                        </div>

                        <div class="db-dropdown__divider" role="separator"></div>

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="db-dropdown__item"
                               role="menuitem">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.8"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true" focusable="false">
                                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                                </svg>
                                Admin Dashboard
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}"
                               class="db-dropdown__item"
                               role="menuitem">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.8"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true" focusable="false">
                                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                                </svg>
                                Dashboard
                            </a>
                        @endif

                        <a href="{{ route('profile.show') }}"
                           class="db-dropdown__item"
                           role="menuitem">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true" focusable="false">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            My Profile
                        </a>

                        <a href="{{ Route::has('my.campaigns') ? route('my.campaigns') : route('dashboard') }}"
                           class="db-dropdown__item"
                           role="menuitem">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true" focusable="false">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06
                                         a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78
                                         1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            My Campaigns
                        </a>

                        <div class="db-dropdown__divider" role="separator"></div>

                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="submit"
                                    class="db-dropdown__item db-dropdown__item--danger"
                                    role="menuitem">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.8"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true" focusable="false">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/>
                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                Sign Out
                            </button>
                        </form>

                    </div>
                </div>

            @else

                <a href="{{ route('login') }}" class="db-btn db-btn--ghost">Log in</a>
                <a href="{{ route('register') }}" class="db-btn db-btn--primary">Get Started</a>

            @endauth

        </div>

        {{-- ── Mobile hamburger ── --}}
        <button id="mobile-toggle"
                class="db-hamburger"
                aria-label="Open navigation menu"
                aria-expanded="false"
                aria-controls="mobile-drawer">
            <span class="db-hamburger__bar" aria-hidden="true"></span>
            <span class="db-hamburger__bar" aria-hidden="true"></span>
            <span class="db-hamburger__bar" aria-hidden="true"></span>
        </button>

    </div>

    {{-- ── Mobile drawer ── --}}
    <div id="mobile-drawer"
         class="db-mobile"
         role="dialog"
         aria-label="Mobile navigation"
         aria-hidden="true"
         aria-modal="false">

        <nav class="db-mobile__nav" aria-label="Mobile primary navigation">
            <a href="{{ route('home') }}"
               class="db-mobile__link {{ request()->routeIs('home') ? 'db-mobile__link--active' : '' }}"
               @if(request()->routeIs('home')) aria-current="page" @endif>
                Home
            </a>
            <a href="{{ route('all.campaigns') }}"
               class="db-mobile__link {{ request()->routeIs('all.campaigns*') ? 'db-mobile__link--active' : '' }}"
               @if(request()->routeIs('all.campaigns*')) aria-current="page" @endif>
                Campaigns
            </a>
            <a href="{{ route('about') }}" class="db-mobile__link">About</a>
            <a href="{{ route('how.it.works') }}" class="db-mobile__link db-mobile__link--sub">└ How It Works</a>
            <a href="{{ route('blogs.index') }}" class="db-mobile__link db-mobile__link--sub">└ Blog</a>
            <a href="{{ route('contact') }}"
               class="db-mobile__link {{ request()->routeIs('contact') ? 'db-mobile__link--active' : '' }}"
               @if(request()->routeIs('contact')) aria-current="page" @endif>
                Contact
            </a>
        </nav>

        <div class="db-mobile__divider" role="separator"></div>

        @auth
            <div class="db-mobile__user">
                {{-- ✅ FIXED: Show actual avatar image in mobile drawer too --}}
                <img src="{{ auth()->user()->avatar
                        ? asset('storage/' . auth()->user()->avatar)
                        : asset('images/default-avatar.png') }}"
                     alt="{{ e(auth()->user()->name) }}"
                     class="db-mobile__avatar"
                     width="38" height="38"
                     loading="eager"
                     style="border-radius:50%;object-fit:cover;flex-shrink:0;">
                <div>
                    <p class="db-mobile__user-name">{{ e(auth()->user()->name) }}</p>
                    <p class="db-mobile__user-email">{{ e(auth()->user()->email) }}</p>
                </div>
            </div>

            <nav class="db-mobile__nav" aria-label="Account navigation">
                <a href="{{ Route::has('campaign.create') ? route('campaign.create') : '/campaign/create' }}"
                   class="db-mobile__link db-mobile__link--cta">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.8" stroke-linecap="round"
                         aria-hidden="true" focusable="false">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Start Fundraise
                </a>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="db-mobile__link">Admin Dashboard</a>
                @else
                    <a href="{{ route('dashboard') }}" class="db-mobile__link">Dashboard</a>
                @endif

                <a href="{{ route('profile.show') }}" class="db-mobile__link">My Profile</a>
                <a href="{{ Route::has('my.campaigns') ? route('my.campaigns') : route('dashboard') }}" class="db-mobile__link">My Campaigns</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="db-mobile__link db-mobile__link--danger">
                        Sign Out
                    </button>
                </form>
            </nav>
        @else
            <div class="db-mobile__auth">
                <a href="{{ route('login') }}" class="db-btn db-btn--ghost db-btn--full">Log in</a>
                <a href="{{ route('register') }}" class="db-btn db-btn--primary db-btn--full">Get Started</a>
            </div>
        @endauth

    </div>

</header>

@once
<style>
/* ✅ Dropdown header avatar */
.db-dropdown__avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

/* ✅ Mobile drawer avatar */
.db-mobile__avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
</style>
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<script src="{{ asset('js/navbar.js') }}" defer></script>
@endonce