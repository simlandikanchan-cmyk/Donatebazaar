/**
 * DonateBazaar — Navbar JS
 * Handles: dropdowns, profile menu, mobile drawer, scroll shadow
 * Zero dependencies · Accessible (ARIA) · Keyboard-navigable
 */

(function () {
    'use strict';

    /* ── Selectors ─────────────────────────────────────────── */
    const navbar       = document.getElementById('db-navbar');
    const mobileToggle = document.getElementById('mobile-toggle');
    const mobileDrawer = document.getElementById('mobile-drawer');
    const backdrop     = document.getElementById('db-backdrop');
    const profileBtn   = document.getElementById('profile-btn');
    const profileMenu  = document.getElementById('profile-menu');
    const notifBtn     = document.getElementById('notif-btn');
    const aboutTrigger = document.getElementById('about-trigger');
    const aboutMenu    = document.getElementById('about-menu');

    /* ── Helpers ───────────────────────────────────────────── */
    function open(trigger, menu, menuRole) {
        trigger.setAttribute('aria-expanded', 'true');
        menu.classList.add('is-open');
        if (menu.hasAttribute('aria-hidden')) {
            menu.setAttribute('aria-hidden', 'false');
        }
    }

    function close(trigger, menu) {
        trigger.setAttribute('aria-expanded', 'false');
        menu.classList.remove('is-open');
        if (menu.hasAttribute('aria-hidden')) {
            menu.setAttribute('aria-hidden', 'true');
        }
    }

    function isOpen(trigger) {
        return trigger.getAttribute('aria-expanded') === 'true';
    }

    /* ── Scroll shadow ─────────────────────────────────────── */
    if (navbar) {
        const onScroll = () => {
            navbar.classList.toggle('is-scrolled', window.scrollY > 10);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /* ── About dropdown ────────────────────────────────────── */
    if (aboutTrigger && aboutMenu) {
        aboutTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            if (isOpen(aboutTrigger)) {
                close(aboutTrigger, aboutMenu);
            } else {
                // Close others first
                closeProfileMenu();
                open(aboutTrigger, aboutMenu);
            }
        });

        // Keyboard nav inside dropdown
        aboutMenu.addEventListener('keydown', (e) => {
            const items = [...aboutMenu.querySelectorAll('[role="menuitem"]')];
            const idx   = items.indexOf(document.activeElement);
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                items[(idx + 1) % items.length]?.focus();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                items[(idx - 1 + items.length) % items.length]?.focus();
            } else if (e.key === 'Escape') {
                close(aboutTrigger, aboutMenu);
                aboutTrigger.focus();
            }
        });
    }

    /* ── Profile dropdown ──────────────────────────────────── */
    function closeProfileMenu() {
        if (profileBtn && profileMenu) close(profileBtn, profileMenu);
    }

    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (isOpen(profileBtn)) {
                closeProfileMenu();
            } else {
                // Close about dropdown first
                if (aboutTrigger) close(aboutTrigger, aboutMenu);
                open(profileBtn, profileMenu);
            }
        });

        profileMenu.addEventListener('keydown', (e) => {
            const items = [...profileMenu.querySelectorAll('[role="menuitem"]')];
            const idx   = items.indexOf(document.activeElement);
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                items[(idx + 1) % items.length]?.focus();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                items[(idx - 1 + items.length) % items.length]?.focus();
            } else if (e.key === 'Escape') {
                closeProfileMenu();
                profileBtn.focus();
            }
        });
    }

    /* ── Global close on outside click ────────────────────── */
    document.addEventListener('click', (e) => {
        if (aboutTrigger && !aboutTrigger.closest('.db-nav__dropdown').contains(e.target)) {
            if (isOpen(aboutTrigger)) close(aboutTrigger, aboutMenu);
        }
        if (profileBtn && !document.getElementById('db-profile-wrapper')?.contains(e.target)) {
            closeProfileMenu();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (aboutTrigger && isOpen(aboutTrigger)) {
                close(aboutTrigger, aboutMenu);
                aboutTrigger.focus();
            }
            closeProfileMenu();
            closeMobileDrawer();
        }
    });

    /* ── Mobile drawer ─────────────────────────────────────── */
    function openMobileDrawer() {
        mobileDrawer.classList.add('is-open');
        mobileDrawer.setAttribute('aria-hidden', 'false');
        backdrop.classList.add('is-open');
        mobileToggle.setAttribute('aria-expanded', 'true');
        mobileToggle.setAttribute('aria-label', 'Close navigation menu');
        document.body.style.overflow = 'hidden';

        // Focus first focusable element
        const firstFocusable = mobileDrawer.querySelector('a, button, [tabindex="0"]');
        firstFocusable?.focus();
    }

    function closeMobileDrawer() {
        if (!mobileDrawer?.classList.contains('is-open')) return;
        mobileDrawer.classList.remove('is-open');
        mobileDrawer.setAttribute('aria-hidden', 'true');
        backdrop.classList.remove('is-open');
        mobileToggle?.setAttribute('aria-expanded', 'false');
        mobileToggle?.setAttribute('aria-label', 'Open navigation menu');
        document.body.style.overflow = '';
    }

    if (mobileToggle) {
        mobileToggle.addEventListener('click', () => {
            if (mobileDrawer.classList.contains('is-open')) {
                closeMobileDrawer();
            } else {
                openMobileDrawer();
            }
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', closeMobileDrawer);
    }

    // Close drawer when any nav link inside is clicked
    if (mobileDrawer) {
        mobileDrawer.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', closeMobileDrawer);
        });
    }

    // Focus trap inside mobile drawer
    if (mobileDrawer) {
        mobileDrawer.addEventListener('keydown', (e) => {
            if (!mobileDrawer.classList.contains('is-open')) return;
            if (e.key !== 'Tab') return;

            const focusables = [...mobileDrawer.querySelectorAll(
                'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
            )].filter(el => !el.disabled && el.offsetParent !== null);

            if (!focusables.length) return;

            const first = focusables[0];
            const last  = focusables[focusables.length - 1];

            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        });
    }

    /* ── Notification button (placeholder) ────────────────── */
    if (notifBtn) {
        notifBtn.addEventListener('click', () => {
            // Dispatch custom event so app can hook in a notification panel
            notifBtn.dispatchEvent(new CustomEvent('db:notifications:open', { bubbles: true }));
        });
    }

})();