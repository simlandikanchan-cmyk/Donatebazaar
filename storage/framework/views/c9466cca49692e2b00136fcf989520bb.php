

<header id="db-navbar" class="db-navbar" role="banner">

    <div class="db-navbar__inner">

        
        <a href="<?php echo e(route('home')); ?>"
           class="db-navbar__brand"
           aria-label="<?php echo e(config('app.name', 'DonateBazaar')); ?> — Go to homepage">
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

        
        <nav class="db-navbar__nav" role="navigation" aria-label="Primary navigation">

            <a href="<?php echo e(route('home')); ?>"
               class="db-nav__link <?php echo e(request()->routeIs('home') ? 'db-nav__link--active' : ''); ?>"
               <?php if(request()->routeIs('home')): ?> aria-current="page" <?php endif; ?>>
                Home
            </a>

            <a href="<?php echo e(route('all.campaigns')); ?>"
               class="db-nav__link <?php echo e(request()->routeIs('all.campaigns*') ? 'db-nav__link--active' : ''); ?>"
               <?php if(request()->routeIs('all.campaigns*')): ?> aria-current="page" <?php endif; ?>>
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
                    <a href="<?php echo e(route('about')); ?>"
                       class="db-nav__dropdown-item"
                       role="menuitem">
                        About Us
                    </a>
                    <a href="<?php echo e(route('how.it.works')); ?>"
                       class="db-nav__dropdown-item"
                       role="menuitem">
                        How It Works
                    </a>
                    <a href="<?php echo e(route('blogs.index')); ?>"
                       class="db-nav__dropdown-item"
                       role="menuitem">
                        Blog
                    </a>
                </div>
            </div>

            <a href="<?php echo e(route('contact')); ?>"
               class="db-nav__link <?php echo e(request()->routeIs('contact') ? 'db-nav__link--active' : ''); ?>"
               <?php if(request()->routeIs('contact')): ?> aria-current="page" <?php endif; ?>>
                Contact
            </a>

        </nav>

        
        <div class="db-navbar__actions">

            <?php if(auth()->guard()->check()): ?>

                <a href="<?php echo e(Route::has('campaign.create') ? route('campaign.create') : '/campaign/create'); ?>"
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

                
                <?php $unread = (int) auth()->user()->unreadNotifications()->count(); ?>
                <button class="db-icon-btn"
                        id="notif-btn"
                        aria-label="Notifications<?php echo e($unread > 0 ? " — {$unread} unread" : ''); ?>"
                        aria-haspopup="true"
                        aria-expanded="false">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true" focusable="false">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    <?php if($unread > 0): ?>
                        <span class="db-badge" aria-hidden="true">
                            <?php echo e($unread > 9 ? '9+' : $unread); ?>

                        </span>
                    <?php endif; ?>
                </button>

                
                <div class="db-profile" id="db-profile-wrapper">
                    <button class="db-profile__trigger"
                            id="profile-btn"
                            aria-haspopup="true"
                            aria-expanded="false"
                            aria-controls="profile-menu"
                            aria-label="Account menu for <?php echo e(e(auth()->user()->name)); ?>">
                        <img src="<?php echo e(auth()->user()->avatar
                                ? asset('storage/' . auth()->user()->avatar)
                                : asset('images/default-avatar.png')); ?>"
                             alt="<?php echo e(e(auth()->user()->name)); ?>"
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
                            
                            <img src="<?php echo e(auth()->user()->avatar
                                    ? asset('storage/' . auth()->user()->avatar)
                                    : asset('images/default-avatar.png')); ?>"
                                 alt="<?php echo e(e(auth()->user()->name)); ?>"
                                 class="db-dropdown__avatar"
                                 width="38" height="38"
                                 loading="eager">
                            <div class="db-dropdown__user-info">
                                <p class="db-dropdown__name"><?php echo e(e(auth()->user()->name)); ?></p>
                                <p class="db-dropdown__email"><?php echo e(e(auth()->user()->email)); ?></p>
                            </div>
                        </div>

                        <div class="db-dropdown__divider" role="separator"></div>

                        <?php if(auth()->user()->role === 'admin'): ?>
                            <a href="<?php echo e(route('admin.dashboard')); ?>"
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
                        <?php else: ?>
                            <a href="<?php echo e(route('dashboard')); ?>"
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
                        <?php endif; ?>

                        <a href="<?php echo e(route('profile.show')); ?>"
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

                        <a href="<?php echo e(Route::has('my.campaigns') ? route('my.campaigns') : route('dashboard')); ?>"
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

                        <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form">
                            <?php echo csrf_field(); ?>
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

            <?php else: ?>

                <a href="<?php echo e(route('login')); ?>" class="db-btn db-btn--ghost">Log in</a>
                <a href="<?php echo e(route('register')); ?>" class="db-btn db-btn--primary">Get Started</a>

            <?php endif; ?>

        </div>

        
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

    
    <div id="mobile-drawer"
         class="db-mobile"
         role="dialog"
         aria-label="Mobile navigation"
         aria-hidden="true"
         aria-modal="false">

        <nav class="db-mobile__nav" aria-label="Mobile primary navigation">
            <a href="<?php echo e(route('home')); ?>"
               class="db-mobile__link <?php echo e(request()->routeIs('home') ? 'db-mobile__link--active' : ''); ?>"
               <?php if(request()->routeIs('home')): ?> aria-current="page" <?php endif; ?>>
                Home
            </a>
            <a href="<?php echo e(route('all.campaigns')); ?>"
               class="db-mobile__link <?php echo e(request()->routeIs('all.campaigns*') ? 'db-mobile__link--active' : ''); ?>"
               <?php if(request()->routeIs('all.campaigns*')): ?> aria-current="page" <?php endif; ?>>
                Campaigns
            </a>
            <a href="<?php echo e(route('about')); ?>" class="db-mobile__link">About</a>
            <a href="<?php echo e(route('how.it.works')); ?>" class="db-mobile__link db-mobile__link--sub">└ How It Works</a>
            <a href="<?php echo e(route('blogs.index')); ?>" class="db-mobile__link db-mobile__link--sub">└ Blog</a>
            <a href="<?php echo e(route('contact')); ?>"
               class="db-mobile__link <?php echo e(request()->routeIs('contact') ? 'db-mobile__link--active' : ''); ?>"
               <?php if(request()->routeIs('contact')): ?> aria-current="page" <?php endif; ?>>
                Contact
            </a>
        </nav>

        <div class="db-mobile__divider" role="separator"></div>

        <?php if(auth()->guard()->check()): ?>
            <div class="db-mobile__user">
                
                <img src="<?php echo e(auth()->user()->avatar
                        ? asset('storage/' . auth()->user()->avatar)
                        : asset('images/default-avatar.png')); ?>"
                     alt="<?php echo e(e(auth()->user()->name)); ?>"
                     class="db-mobile__avatar"
                     width="38" height="38"
                     loading="eager"
                     style="border-radius:50%;object-fit:cover;flex-shrink:0;">
                <div>
                    <p class="db-mobile__user-name"><?php echo e(e(auth()->user()->name)); ?></p>
                    <p class="db-mobile__user-email"><?php echo e(e(auth()->user()->email)); ?></p>
                </div>
            </div>

            <nav class="db-mobile__nav" aria-label="Account navigation">
                <a href="<?php echo e(Route::has('campaign.create') ? route('campaign.create') : '/campaign/create'); ?>"
                   class="db-mobile__link db-mobile__link--cta">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.8" stroke-linecap="round"
                         aria-hidden="true" focusable="false">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Start Fundraise
                </a>

                <?php if(auth()->user()->role === 'admin'): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="db-mobile__link">Admin Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="db-mobile__link">Dashboard</a>
                <?php endif; ?>

                <a href="<?php echo e(route('profile.show')); ?>" class="db-mobile__link">My Profile</a>
                <a href="<?php echo e(Route::has('my.campaigns') ? route('my.campaigns') : route('dashboard')); ?>" class="db-mobile__link">My Campaigns</a>

                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="db-mobile__link db-mobile__link--danger">
                        Sign Out
                    </button>
                </form>
            </nav>
        <?php else: ?>
            <div class="db-mobile__auth">
                <a href="<?php echo e(route('login')); ?>" class="db-btn db-btn--ghost db-btn--full">Log in</a>
                <a href="<?php echo e(route('register')); ?>" class="db-btn db-btn--primary db-btn--full">Get Started</a>
            </div>
        <?php endif; ?>

    </div>

</header>

<?php if (! $__env->hasRenderedOnce('d2372e54-2edd-447d-9291-292d9cbe427d')): $__env->markAsRenderedOnce('d2372e54-2edd-447d-9291-292d9cbe427d'); ?>
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
<link rel="stylesheet" href="<?php echo e(asset('css/navbar.css')); ?>">
<script src="<?php echo e(asset('js/navbar.js')); ?>" defer></script>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\fundraise\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>