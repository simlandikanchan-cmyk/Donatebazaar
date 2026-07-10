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
    const notifWrapper = document.getElementById('notif-wrapper');
    const notifPanel   = document.getElementById('notif-panel');
    const notifList    = document.getElementById('notif-list');
    const notifBadge   = document.getElementById('notif-badge');
    const notifEmpty   = document.getElementById('notif-empty');
    const notifMarkAll = document.getElementById('notif-mark-all');
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
        if (notifBtn && notifWrapper && !notifWrapper.contains(e.target)) {
            if (notifIsOpen()) closeNotifPanel();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (aboutTrigger && isOpen(aboutTrigger)) {
                close(aboutTrigger, aboutMenu);
                aboutTrigger.focus();
            }
            closeProfileMenu();
            closeNotifPanel();
            closeMobileDrawer();
        }
    });

    /* ── Mobile drawer (premium slide-in) ──────────────────── */
    const mobileClose = document.getElementById('mobile-close');

    function openMobileDrawer() {
        mobileDrawer.classList.add('is-open');
        mobileDrawer.setAttribute('aria-hidden', 'false');
        backdrop?.classList.add('is-open');
        mobileToggle.classList.add('is-open');
        mobileToggle.setAttribute('aria-expanded', 'true');
        mobileToggle.setAttribute('aria-label', 'Close navigation menu');
        document.body.style.overflow = 'hidden';
        if (window.lucide) window.lucide.createIcons();
        mobileClose?.focus();
    }

    function closeMobileDrawer() {
        if (!mobileDrawer?.classList.contains('is-open')) return;
        mobileDrawer.classList.remove('is-open');
        mobileDrawer.setAttribute('aria-hidden', 'true');
        backdrop?.classList.remove('is-open');
        mobileToggle?.classList.remove('is-open');
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

    if (mobileClose) {
        mobileClose.addEventListener('click', closeMobileDrawer);
    }

    if (backdrop) {
        backdrop.addEventListener('click', closeMobileDrawer);
    }

    // Accordion expandable sections
    if (mobileDrawer) {
        mobileDrawer.querySelectorAll('[data-drawer-toggle]').forEach((toggle) => {
            toggle.addEventListener('click', () => {
                const target = document.getElementById(toggle.getAttribute('data-drawer-toggle'));
                if (!target) return;
                const isOpen = target.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', String(isOpen));
                if (isOpen) {
                    target.removeAttribute('inert');
                } else {
                    target.setAttribute('inert', '');
                }
            });
        });

        // Close the drawer shortly after a navigation link is tapped
        mobileDrawer.querySelectorAll('a.db-drawer__item, a.db-drawer__subitem, a.db-drawer__brand').forEach((link) => {
            link.addEventListener('click', () => {
                setTimeout(closeMobileDrawer, 120);
            });
        });
    }

    // Focus trap inside mobile drawer
    if (mobileDrawer) {
        mobileDrawer.addEventListener('keydown', (e) => {
            if (!mobileDrawer.classList.contains('is-open')) return;
            if (e.key !== 'Tab') return;

            const focusables = [...mobileDrawer.querySelectorAll(
                'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])'
            )].filter(el => !el.disabled && !el.hasAttribute('inert') && el.offsetParent !== null);

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

    /* ── Notification panel ────────────────────────────────── */
    function getCsrf() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function notifIsOpen() {
        return notifBtn?.getAttribute('aria-expanded') === 'true';
    }

    function openNotifPanel() {
        if (!notifBtn || !notifPanel) return;
        notifBtn.setAttribute('aria-expanded', 'true');
        notifPanel.classList.add('is-open');
        notifPanel.setAttribute('aria-hidden', 'false');
        loadNotifications();
    }

    function closeNotifPanel() {
        if (!notifBtn || !notifPanel) return;
        notifBtn.setAttribute('aria-expanded', 'false');
        notifPanel.classList.remove('is-open');
        notifPanel.setAttribute('aria-hidden', 'true');
    }

    function updateBadge(count) {
        if (!notifBadge) return;
        count = parseInt(count, 10) || 0;
        if (count > 0) {
            notifBadge.textContent = count > 9 ? '9+' : count;
            notifBadge.hidden = false;
        } else {
            notifBadge.hidden = true;
        }
    }

    function notifIcon(type) {
        switch (type) {
            case 'kyc_requested':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
            case 'kyc_submitted':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
            default:
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';
        }
    }

    function renderNotifications(items) {
        if (!notifList) return;
        notifList.querySelectorAll('.db-notif__item').forEach((el) => el.remove());

        if (!items || items.length === 0) {
            notifEmpty.hidden = false;
            return;
        }
        notifEmpty.hidden = true;

        const fragment = document.createDocumentFragment();
        items.forEach((n) => {
            const item = document.createElement('a');
            item.className = 'db-notif__item' + (n.read_at ? '' : ' is-unread');
            item.href = n.url || '#';
            item.setAttribute('role', 'menuitem');
            item.dataset.id = n.id;
            item.innerHTML =
                '<span class="db-notif__icon" aria-hidden="true">' + notifIcon(n.type) + '</span>' +
                '<span class="db-notif__body">' +
                    '<span class="db-notif__msg">' + escapeHtml(n.message || n.title) + '</span>' +
                    '<span class="db-notif__time">' + escapeHtml(n.created_at) + '</span>' +
                '</span>' +
                (n.read_at ? '' : '<span class="db-notif__dot" aria-hidden="true"></span>');
            fragment.appendChild(item);
        });
        notifList.appendChild(fragment);
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }

    async function loadNotifications() {
        try {
            const res = await fetch('/notifications', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            });
            if (!res.ok) return;
            const data = await res.json();
            renderNotifications(data.notifications);
            updateBadge(data.unread_count);
        } catch (e) {
            /* no-op */
        }
    }

    async function markNotificationRead(id, redirectUrl) {
        try {
            await fetch('/notifications/' + encodeURIComponent(id) + '/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrf(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });
        } catch (e) {
            /* no-op */
        }
        if (redirectUrl && redirectUrl !== '#') {
            window.location.href = redirectUrl;
        }
    }

    async function markAllRead() {
        try {
            const res = await fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrf(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });
            if (!res.ok) return;
            const data = await res.json();
            updateBadge(data.unread_count);
            notifList?.querySelectorAll('.db-notif__item.is-unread').forEach((el) => {
                el.classList.remove('is-unread');
                el.querySelector('.db-notif__dot')?.remove();
            });
        } catch (e) {
            /* no-op */
        }
    }

    if (notifBtn && notifPanel) {
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (notifIsOpen()) {
                closeNotifPanel();
            } else {
                closeProfileMenu();
                if (aboutTrigger) close(aboutTrigger, aboutMenu);
                openNotifPanel();
            }
        });

        // Mark a notification as read when its item is clicked
        notifList?.addEventListener('click', (e) => {
            const item = e.target.closest('.db-notif__item');
            if (!item) return;
            const id = item.dataset.id;
            const url = item.getAttribute('href');
            if (id) {
                e.preventDefault();
                markNotificationRead(id, url);
            }
        });

        notifMarkAll?.addEventListener('click', (e) => {
            e.stopPropagation();
            markAllRead();
        });

        notifPanel.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            closeNotifPanel();
            notifBtn.focus();
        });
    }

})();