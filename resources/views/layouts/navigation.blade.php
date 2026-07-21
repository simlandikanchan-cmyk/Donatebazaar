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

        @include('layouts.nav._brand')
        @include('layouts.nav._desktop_nav')
        @include('layouts.nav._right_actions')

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

    @include('layouts.nav._mobile_drawer')

</header>
