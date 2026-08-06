<div class="db-navbar__actions">

    <x-button variant="outline" iconOnly href="{{ route('search') }}" aria-label="Search">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    </x-button>

    @auth

        <x-button variant="primary" href="{{ Route::has('campaign.create') ? route('campaign.create') : '/campaign/create' }}">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.8" stroke-linecap="round"
                 aria-hidden="true" focusable="false">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Start Fundraise
        </x-button>

        @php $unread = (int) auth()->user()->unreadNotifications()->count(); @endphp
        <div class="db-notif" id="notif-wrapper">
            <x-button variant="outline" iconOnly type="button" id="notif-btn"
                     aria-haspopup="true"
                     aria-expanded="false"
                     aria-controls="notif-panel"
                     aria-label="Notifications">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="1.8"
                      stroke-linecap="round" stroke-linejoin="round"
                      aria-hidden="true" focusable="false">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                @if($unread > 0)
                    <span class="db-badge" id="notif-badge" aria-hidden="true">
                        {{ $unread > 9 ? '9+' : $unread }}
                    </span>
                @else
                    <span class="db-badge" id="notif-badge" hidden aria-hidden="true">0</span>
                @endif
            </x-button>

            <div class="db-dropdown db-notif__panel"
                 id="notif-panel"
                 role="menu"
                 aria-labelledby="notif-btn"
                 aria-hidden="true">
                <div class="db-notif__header" role="none">
                    <span class="db-notif__title">Notifications</span>
                    <button type="button"
                            class="db-notif__mark-all"
                            id="notif-mark-all">
                        Mark all read
                    </button>
                </div>
                <div class="db-notif__divider" role="separator"></div>
                <div class="db-notif__list" id="notif-list" role="none">
                    <p class="db-notif__empty" id="notif-empty" hidden>No notifications yet.</p>
                </div>
            </div>
        </div>

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

        <x-button variant="outline" href="{{ route('login') }}">Log in</x-button>
        <x-button variant="primary" href="{{ route('register') }}">Get Started</x-button>

    @endauth

</div>
