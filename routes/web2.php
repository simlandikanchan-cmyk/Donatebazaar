<?php

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

require __DIR__.'/public/pages.php';
require __DIR__.'/public/auth.php';
require __DIR__.'/public/campaigns.php';
require __DIR__.'/public/payments.php';
require __DIR__.'/public/events.php';
require __DIR__.'/public/blogs.php';
require __DIR__.'/public/partnership.php';

/*
|--------------------------------------------------------------------------
| User
|--------------------------------------------------------------------------
*/

require __DIR__.'/user/profile.php';
require __DIR__.'/user/dashboard.php';
require __DIR__.'/user/campaigns.php';
require __DIR__.'/user/kyc.php';
require __DIR__.'/user/events.php';
require __DIR__.'/user/volunteer.php';
require __DIR__.'/user/applications.php';
require __DIR__.'/user/recurring.php';
require __DIR__.'/user/blogs.php';

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

require __DIR__.'/admin/dashboard.php';
require __DIR__.'/admin/campaigns.php';
require __DIR__.'/admin/kyc.php';
require __DIR__.'/admin/categories.php';
require __DIR__.'/admin/partnerships.php';
require __DIR__.'/admin/contacts.php';
require __DIR__.'/admin/applications.php';
require __DIR__.'/admin/blogs.php';

/*
|--------------------------------------------------------------------------
| Integrations
|--------------------------------------------------------------------------
*/

require __DIR__.'/integrations/chatbot.php';
require __DIR__.'/integrations/google.php';