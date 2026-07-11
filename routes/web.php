<?php

/*
|--------------------------------------------------------------------------
| Web Routes — Entry Point
|--------------------------------------------------------------------------
|
| Loads all individual route files from the web/ and admin/ directories.
| The file ordering matters: auth must load first so named routes are
| available before the other route files reference them (e.g. route('login')).
|
|--------------------------------------------------------------------------
*/

require __DIR__.'/web/auth.php';
require __DIR__.'/web/home.php';
require __DIR__.'/web/pages.php';
require __DIR__.'/web/contact.php';
require __DIR__.'/web/campaigns.php';
require __DIR__.'/web/donations.php';
require __DIR__.'/web/events.php';
require __DIR__.'/web/blogs.php';
require __DIR__.'/web/categories.php';
require __DIR__.'/web/career.php';
require __DIR__.'/web/gift-cards.php';
require __DIR__.'/web/volunteer.php';
require __DIR__.'/web/application.php';
require __DIR__.'/web/profile.php';
require __DIR__.'/web/dashboard.php';
require __DIR__.'/web/notifications.php';

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/admin/dashboard.php';
require __DIR__.'/admin/organizations.php';
require __DIR__.'/admin/campaigns.php';
require __DIR__.'/admin/categories.php';
require __DIR__.'/admin/blogs.php';
require __DIR__.'/admin/events.php';
require __DIR__.'/admin/partnerships.php';
require __DIR__.'/admin/messages.php';
require __DIR__.'/admin/applications.php';
require __DIR__.'/admin/job-posts.php';
require __DIR__.'/admin/gift-cards.php';
require __DIR__.'/admin/chatbot.php';
require __DIR__.'/admin/profile.php';
require __DIR__.'/admin/volunteers.php';
require __DIR__.'/admin/coupons.php';
// require __DIR__.'/admin/users.php';     // stub — add routes when ready
// require __DIR__.'/admin/reports.php';   // stub
// require __DIR__.'/admin/settings.php';  // stub
// require __DIR__.'/admin/roles.php';     // stub