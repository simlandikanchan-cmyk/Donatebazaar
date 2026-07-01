<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Models\Campaign;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Frontend\PartnershipController;
use App\Http\Controllers\Admin\PartnershipAdminController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\PublicCampaignController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\RecurringDonationController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\Admin\ApplicationController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\PublicBlogController;
use App\Http\Controllers\User\BlogController as UserBlogController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\KycUploadController;
use App\Http\Controllers\Admin\CampaignKycController;
use App\Http\Controllers\Admin\KycController as AdminKycController;
use App\Http\Controllers\HowItWorksController;
use App\Http\Controllers\DdrfController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\Admin\JobPostController as AdminJobPostController;
use App\Http\Controllers\Admin\JobPostApplicationController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\Admin\GiftCardController as AdminGiftCardController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\Admin\CategoryProductController as AdminCategoryProductController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\NewsletterController;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

/*
|--------------------------------------------------------------------------
| Public / Home
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Static Pages (public)
|--------------------------------------------------------------------------
*/
Route::get('/how-it-works', [HowItWorksController::class, 'index'])->name('how.it.works');
Route::get('/about', [AboutController::class, 'index'])->name('about');

/*
|--------------------------------------------------------------------------
| Auth / Profile
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| OTP Login
|--------------------------------------------------------------------------
*/
Route::get('/otp-login',   [OtpController::class, 'login'])->name('otp.login');
Route::get('/verify-otp',  [OtpController::class, 'verifyPage'])->name('otp.verify');

Route::post('/send-otp', [OtpController::class, 'sendOtp'])
     ->middleware('throttle:5,1')
     ->name('otp.send');

Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])
     ->middleware('throttle:10,1')
     ->name('otp.verify.post');

Route::post('/resend-otp', [OtpController::class, 'resend'])
     ->middleware('throttle:3,1')
     ->name('otp.resend');

/*
|--------------------------------------------------------------------------
| Public Campaign Routes
|--------------------------------------------------------------------------
*/
Route::get('/all-campaigns',    [CampaignController::class, 'publicCampaigns'])->name('all.campaigns');
Route::get('/category/{slug}',  [CampaignController::class, 'byCategory'])->name('campaigns.byCategory');
Route::get('/campaigns/{category}/{slug}', [PublicCampaignController::class, 'show'])->name('campaign.public');

/*
|--------------------------------------------------------------------------
| Payment
|--------------------------------------------------------------------------
*/
// Route::post('/donate/{campaign}', [PaymentController::class, 'redirectToPayment'])
//      ->name('donate.redirect')
//      ->middleware('auth');

Route::match(['get', 'post'], '/donate/{campaign}', [PaymentController::class, 'redirectToPayment'])
     ->name('donate.redirect')
     ->middleware('auth');

Route::get('/payment/{campaign}', [PaymentController::class, 'paymentPage'])
     ->name('payment.page')
     ->middleware('auth');

Route::post('/payment/verify', [PaymentController::class, 'verify'])
     ->name('payment.verify')
     ->middleware('auth');

// Webhook — no auth/CSRF by design (verify signature inside the controller instead)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])
     ->name('payment.webhook');

/*
|--------------------------------------------------------------------------
| Contact & Partnership (public)
|--------------------------------------------------------------------------
*/
Route::get('/contact',      [ContactController::class, 'index'])->name('contact');
Route::post('/contact',     [ContactController::class, 'store'])
     ->middleware('throttle:5,1')
     ->name('contact.store');
Route::get('/partnership',  [PartnershipController::class, 'index'])->name('partnership');
Route::post('/partnership', [PartnershipController::class, 'store'])
     ->middleware('throttle:5,1')
     ->name('partnership.store');

/*
|--------------------------------------------------------------------------
| Public Events (read-only)
|--------------------------------------------------------------------------
*/
Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Anonymous registration
Route::get('/events/{event}/register',  [EventRegistrationController::class, 'register'])->name('events.register');
Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])
     ->middleware('throttle:5,1')
     ->name('events.register.store');

/*
|--------------------------------------------------------------------------
| Public Blog Routes
|--------------------------------------------------------------------------
*/
Route::prefix('blog')->name('blogs.')->group(function () {

    Route::get('/',                [PublicBlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [PublicBlogController::class, 'byCategory'])->name('category');
    Route::get('/tag/{slug}',      [PublicBlogController::class, 'byTag'])->name('tag');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::post('/{blog}/like',    [PublicBlogController::class, 'toggleLike'])->name('like');
        Route::post('/{blog}/comment', [PublicBlogController::class, 'comment'])->name('comment');
        Route::post('/{blog}/report',  [PublicBlogController::class, 'report'])->name('report');
    });

    Route::get('/{slug}', [PublicBlogController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| DDRF (Disaster Relief)
|--------------------------------------------------------------------------
*/
Route::get('/disaster-relief', [DdrfController::class, 'index'])->name('ddrf.index');

/*
|--------------------------------------------------------------------------
| Google OAuth
|--------------------------------------------------------------------------
*/
Route::get('/auth/google',          [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ── Profile ──────────────────────────────────────────────────────────
    Route::get('/profile',           [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit',      [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',         [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',        [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar',   [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/cover',    [ProfileController::class, 'updateCover'])->name('profile.cover');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // ── User Dashboard ────────────────────────────────────────────────────
    Route::get('/user/dashboard', function () {
        $campaigns = Campaign::where('user_id', auth()->id())->get();

        $monthlyData = Campaign::where('user_id', auth()->id())
            ->selectRaw('MONTH(created_at) as month, SUM(raised_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('dashboard', compact('campaigns', 'monthlyData'));
    })->name('dashboard');

    // ── Campaigns ─────────────────────────────────────────────────────────
    Route::get('/campaign/create',                [CampaignController::class, 'create'])->name('campaign.create');
    Route::post('/campaign/store',                [CampaignController::class, 'store'])->name('campaign.store');
    Route::get('/campaigns',                      [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaign/{campaign}',            [CampaignController::class, 'show'])->name('campaign.show');
    Route::get('/campaign/{campaign}/edit',       [CampaignController::class, 'edit'])->name('campaign.edit');
    Route::put('/campaign/{campaign}',            [CampaignController::class, 'update'])->name('campaign.update');
    Route::post('/campaign/{campaign}/pause',     [CampaignController::class, 'pause'])->name('campaign.pause');
    Route::post('/campaign/{campaign}/resume',    [CampaignController::class, 'resume'])->name('campaign.resume');
    Route::post('/campaigns/{campaign}/resubmit', [CampaignController::class, 'resubmit'])->name('campaign.resubmit');

    // NOTE: campaign.edit/update/pause/resume/resubmit still need an authorization
    // check inside the controller (or a policy: $this->authorize('update', $campaign))
    // confirming the logged-in user actually owns $campaign. Being authenticated is
    // not the same as being authorized — otherwise any donor could edit any campaign.

    // ── KYC ───────────────────────────────────────────────────────────────
    Route::get('/kyc/upload/{campaign}',   [KycUploadController::class, 'show'])->name('kyc.upload.form');
    Route::post('/kyc/upload/{campaign}',  [KycUploadController::class, 'store'])->name('kyc.upload');
    Route::get('/kyc/view/{campaign}',     [KycUploadController::class, 'view'])->name('kyc.view');
    Route::get('/kyc/document/{campaign}', [KycUploadController::class, 'serveDocument'])->name('kyc.document');
    // NOTE: kyc.view / kyc.document return uploaded ID/financial documents — make sure
    // the controller checks that $campaign belongs to auth()->id() (or the user is an
    // admin), not just that *some* user is logged in.

    Route::get('/user/kyc', function () {
        $campaigns = Campaign::where('user_id', auth()->id())->get();
        return view('kyc.index', compact('campaigns'));
    })->name('user.kyc');

    // ── Events (owner-managed) ──────────────────────────────────────────────
    Route::get('/campaign/{campaign}/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/campaign/{campaign}/events',       [EventController::class, 'store'])->name('events.store');

    // FIXED: these were previously public (no auth), letting anyone edit/update
    // any event. Moved inside the auth group. Still add an ownership/policy check
    // in the controller — auth alone doesn't confirm this user owns $event.
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}',      [EventController::class, 'update'])->name('events.update');

    // ── Volunteers ────────────────────────────────────────────────────────
    // FIXED: '/volunteer/apply' was inside the auth group, which likely blocks
    // public applicants from applying. Moved out to the public section below.
    Route::get('/campaign/{id}/volunteers', [VolunteerController::class, 'campaignVolunteers'])->name('volunteers.campaign');

    // ── NGO / Organisation Application (multi-step) ───────────────────────
    Route::prefix('apply')->name('application.')->group(function () {
        Route::get('/step1',   [ApplicationController::class, 'step1'])->name('step1');
        Route::post('/step1',  [ApplicationController::class, 'step1Post'])->name('step1.post');
        Route::get('/step2',   [ApplicationController::class, 'step2'])->name('step2');
        Route::post('/step2',  [ApplicationController::class, 'step2Post'])->name('step2.post');
        Route::get('/step3',   [ApplicationController::class, 'step3'])->name('step3');
        Route::post('/step3',  [ApplicationController::class, 'step3Post'])->name('step3.post');
        Route::get('/step4',   [ApplicationController::class, 'step4'])->name('step4');
        Route::post('/submit', [ApplicationController::class, 'submit'])->name('submit');
    });

    // ── Recurring Donations ───────────────────────────────────────────────
    Route::get('/my-recurring-donations',                 [RecurringDonationController::class, 'index'])->name('recurring.index');
    Route::post('/campaign/{campaign}/recurring',         [RecurringDonationController::class, 'store'])->name('recurring.store');
    Route::patch('/recurring/{recurringDonation}/cancel', [RecurringDonationController::class, 'cancel'])->name('recurring.cancel');
    Route::patch('/recurring/{recurringDonation}/pause',  [RecurringDonationController::class, 'pause'])->name('recurring.pause');
    Route::patch('/recurring/{recurringDonation}/resume', [RecurringDonationController::class, 'resume'])->name('recurring.resume');
    // NOTE: same ownership caveat — confirm $recurringDonation->user_id === auth()->id()
    // in the controller before cancel/pause/resume.

    // ── User Blog Dashboard ───────────────────────────────────────────────
    Route::prefix('user/dashboard/blogs')->name('user.blogs.')->group(function () {
        Route::get('/',               [UserBlogController::class, 'index'])->name('index');
        Route::get('/create',         [UserBlogController::class, 'create'])->name('create');
        Route::post('/',              [UserBlogController::class, 'store'])->name('store');
        Route::post('/restore/{id}',  [UserBlogController::class, 'restore'])->name('restore');
        Route::get('/{blog}',         [UserBlogController::class, 'show'])->name('show');
        Route::get('/{blog}/edit',    [UserBlogController::class, 'edit'])->name('edit');
        Route::put('/{blog}',         [UserBlogController::class, 'update'])->name('update');
        Route::delete('/{blog}',      [UserBlogController::class, 'destroy'])->name('destroy');
        Route::post('/{blog}/submit', [UserBlogController::class, 'submit'])->name('submit');
    });
});

/*
|--------------------------------------------------------------------------
| Admin-only: escalated actions that were previously reachable by any
| authenticated user. FIXED: added role/'admin' middleware.
| Adjust the middleware name to whatever your app actually uses
| (e.g. 'role:admin', 'can:manage-volunteers', a Gate check, etc.)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/admin/volunteer/{id}/status', [VolunteerController::class, 'updateStatus'])
         ->name('admin.volunteer.status');
});

/*
|--------------------------------------------------------------------------
| Volunteers (public application form)
|--------------------------------------------------------------------------
*/
Route::get('/volunteer/apply', [VolunteerController::class, 'apply'])->name('volunteer.apply');

/*
|--------------------------------------------------------------------------
| Admin Routes (extracted to routes/admin.php)
|--------------------------------------------------------------------------
*/
require __DIR__.'/admin.php';

/*
|--------------------------------------------------------------------------
| Careers (public)
|--------------------------------------------------------------------------
*/
Route::prefix('career')->name('job_posts.')->group(function () {
    Route::get('/',                      [JobPostController::class, 'index'])->name('index');
    Route::get('/{jobPost:slug}',        [JobPostController::class, 'show'])->name('show');
    Route::post('/{jobPost:slug}/apply', [JobPostController::class, 'apply'])
         ->middleware('throttle:5,1')
         ->name('apply');
});

/*
|--------------------------------------------------------------------------
| Public Gift Card Routes
|--------------------------------------------------------------------------
*/
Route::prefix('gift-cards')->name('gift-cards.')->group(function () {
    Route::get('/',               [GiftCardController::class, 'index'])->name('index');
    Route::post('/order',         [GiftCardController::class, 'createOrder'])->name('order');
    Route::post('/verify',        [GiftCardController::class, 'verify'])->name('verify');
    Route::get('/redeem',         [GiftCardController::class, 'redeemPage'])->name('redeem');
    Route::post('/validate-code', [GiftCardController::class, 'validateCode'])
         ->middleware('throttle:10,1') // FIXED: added throttle — code-guessing endpoint
         ->name('validate-code');
    Route::post('/redeem',        [GiftCardController::class, 'redeem'])
         ->middleware('throttle:10,1') // FIXED: same reasoning
         ->name('redeem.submit');
    Route::get('/success/{code}', [GiftCardController::class, 'redeemSuccess'])->name('redeem.success');
});

/*
|--------------------------------------------------------------------------
| Misc
|--------------------------------------------------------------------------
*/
Route::get('/category/{id}/products', [CategoryProductController::class, 'getProducts'])
     ->name('category.products');

/*
|--------------------------------------------------------------------------
| Health Check
| Public infra status page — consider gating behind IP allowlist or auth
| in production so you're not exposing DB/cache/queue internals publicly.
|--------------------------------------------------------------------------
*/
Route::get('/health', HealthCheckResultsController::class)->name('health');

/*
|--------------------------------------------------------------------------
| Newsletter
|--------------------------------------------------------------------------
*/
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
     ->middleware('throttle:5,1')
     ->name('newsletter.subscribe');

/*
|--------------------------------------------------------------------------
| REMOVED: /preview-receipt/{id}
|--------------------------------------------------------------------------
| This route returned any user's donation receipt to anyone who guessed
| the numeric ID — no auth, no ownership check. It looked like a debug/
| preview route left in from development.
|
| If you need this for local testing of the mailable's markup, keep it
| but restrict it hard, e.g.:
|
| if (app()->environment('local')) {
|     Route::get('/preview-receipt/{donation}', function (Donation $donation) {
|         return new DonationReceiptMail($donation);
|     })->middleware(['auth', 'role:admin']);
| }
|
| Do not ship an equivalent of this route to production unguarded.
|--------------------------------------------------------------------------
*/