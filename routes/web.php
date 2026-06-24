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
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\Admin\GiftCardController as AdminGiftCardController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\Admin\CategoryProductController as AdminCategoryProductController;
use App\Http\Controllers\Admin\EventController as AdminEventController;

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
Route::post('/send-otp',   [OtpController::class, 'sendOtp'])->name('otp.send');
Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');

Route::post('/store-phone', function (Request $request) {
    session(['phone' => $request->phone]);
    return response()->json(['success' => true]);
});

Route::post('/firebase-login', function (Request $request) {
    $user = User::firstOrCreate(
        ['phone' => $request->phone],
        ['role'  => 'donor']
    );
    auth()->login($user);
    return response()->json(['success' => true]);
});

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
Route::post('/donate/{campaign}', [PaymentController::class, 'redirectToPayment'])
     ->name('donate.redirect')
     ->middleware('auth');

Route::get('/payment/{campaign}', [PaymentController::class, 'paymentPage'])
     ->name('payment.page')
     ->middleware('auth');

Route::post('/payment/verify', [PaymentController::class, 'verify'])
     ->name('payment.verify')
     ->middleware('auth');

// Webhook — NO auth, NO CSRF
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])
     ->name('payment.webhook');

/*
|--------------------------------------------------------------------------
| Contact & Partnership (public)
|--------------------------------------------------------------------------
*/
Route::get('/contact',      [ContactController::class, 'index'])->name('contact');
Route::post('/contact',     [ContactController::class, 'store'])->name('contact.store');
Route::get('/partnership',  [PartnershipController::class, 'index'])->name('partnership');
Route::post('/partnership', [PartnershipController::class, 'store'])->name('partnership.store');

/*
|--------------------------------------------------------------------------
| Public Events
|--------------------------------------------------------------------------
*/
Route::get('/events/{event}',      [EventController::class, 'show'])->name('events.show');
Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::put('/events/{event}',      [EventController::class, 'update'])->name('events.update');

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
    Route::post('/campaigns/{campaign}/resubmit',[CampaignController::class, 'resubmit'])->name('campaign.resubmit');

    // ── KYC ───────────────────────────────────────────────────────────────
    Route::get('/kyc/upload/{campaign}',   [KycUploadController::class, 'show'])->name('kyc.upload.form');
    Route::post('/kyc/upload/{campaign}',  [KycUploadController::class, 'store'])->name('kyc.upload');
    Route::get('/kyc/view/{campaign}',     [KycUploadController::class, 'view'])->name('kyc.view');
    Route::get('/kyc/document/{campaign}', [KycUploadController::class, 'serveDocument'])->name('kyc.document');

    // ── Events ────────────────────────────────────────────────────────────
    Route::get('/campaign/{campaign}/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/campaign/{campaign}/events',       [EventController::class, 'store'])->name('events.store');

    // ── Volunteers ────────────────────────────────────────────────────────
    Route::get('/volunteer/apply',              [VolunteerController::class, 'apply']);
    Route::post('/admin/volunteer/{id}/status', [VolunteerController::class, 'updateStatus']);
    Route::get('/campaign/{id}/volunteers',     [VolunteerController::class, 'campaignVolunteers']);

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

    // ── User Blog Dashboard ───────────────────────────────────────────────
    Route::prefix('user/dashboard/blogs')->name('user.blogs.')->group(function () {
        Route::get('/',              [UserBlogController::class, 'index'])->name('index');
        Route::get('/create',        [UserBlogController::class, 'create'])->name('create');
        Route::post('/',             [UserBlogController::class, 'store'])->name('store');
        Route::post('/restore/{id}', [UserBlogController::class, 'restore'])->name('restore');
        Route::get('/{blog}',        [UserBlogController::class, 'show'])->name('show');
        Route::get('/{blog}/edit',   [UserBlogController::class, 'edit'])->name('edit');
        Route::put('/{blog}',        [UserBlogController::class, 'update'])->name('update');
        Route::delete('/{blog}',     [UserBlogController::class, 'destroy'])->name('destroy');
        Route::post('/{blog}/submit',[UserBlogController::class, 'submit'])->name('submit');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // ── Dashboard ─────────────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Category Products ─────────────────────────────────────────────────
    Route::resource('category-products', AdminCategoryProductController::class);

    // ── Campaigns ─────────────────────────────────────────────────────────
    Route::get('/campaign/{campaign}',            [AdminCampaignController::class, 'show'])->name('campaign.show');
    Route::get('/campaign/{campaign}/edit',       [AdminCampaignController::class, 'edit'])->name('campaign.edit');
    Route::put('/campaign/{campaign}/update',     [AdminCampaignController::class, 'update'])->name('campaign.update');
    Route::post('/campaign/{campaign}/approve',   [AdminCampaignController::class, 'approve'])->name('campaign.approve');
    Route::post('/campaign/{campaign}/reject',    [AdminCampaignController::class, 'reject'])->name('campaign.reject');
    Route::post('/campaign/{campaign}/pause',     [AdminCampaignController::class, 'pause'])->name('campaign.pause');
    Route::post('/campaign/{campaign}/resume',    [AdminCampaignController::class, 'resume'])->name('campaign.resume');

    // ── KYC ───────────────────────────────────────────────────────────────
    Route::post('/campaign/{campaign}/request-kyc', [CampaignKycController::class, 'requestKyc'])->name('campaign.request-kyc');
    Route::post('/kyc/{kyc}/approve',  [AdminKycController::class, 'approve'])->name('kyc.approve');
    Route::post('/kyc/{kyc}/reject',   [AdminKycController::class, 'reject'])->name('kyc.reject');
    Route::get('/kyc/{kyc}/document',  [AdminKycController::class, 'showDocument'])->name('kyc.document');

    // ── Categories ────────────────────────────────────────────────────────
    Route::resource('categories', CategoryController::class);

    // ── Partnerships ──────────────────────────────────────────────────────
    Route::get('/partnerships',            [PartnershipAdminController::class, 'index'])->name('partnership.index');
    Route::get('/partnerships/{id}',       [PartnershipAdminController::class, 'show'])->name('partnership.show');
    Route::post('/partnerships/{id}',      [PartnershipAdminController::class, 'update'])->name('partnership.update');
    Route::get('/partnership/delete/{id}', [PartnershipAdminController::class, 'deletePage'])->name('partnership.deletePage');
    Route::delete('/partnership/{id}',     [PartnershipAdminController::class, 'delete'])->name('partnership.delete');

    // ── Contact Messages ──────────────────────────────────────────────────
    Route::get('/messages',         [ContactMessageController::class, 'index'])->name('messages');
    Route::get('/messages/{id}',    [ContactMessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{id}', [ContactMessageController::class, 'destroy'])->name('messages.delete');

    // ── Contacts ──────────────────────────────────────────────────────────
    Route::get('/contacts',             [ContactController::class, 'adminIndex'])->name('contacts');
    Route::get('/contacts/delete/{id}', [ContactController::class, 'adminDelete'])->name('contacts.delete');

    // ── NGO Applications ──────────────────────────────────────────────────
    Route::get('/applications',              [ApplicationController::class, 'index'])->name('applications');
    Route::get('/applications/{id}',         [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{id}/approve',[ApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{id}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');

    // ── Job Posts ─────────────────────────────────────────────────────────
    Route::resource('job_posts', AdminJobPostController::class);

    // ── Job Post Applications ─────────────────────────────────────────────
    Route::get('/job_post_applications',                          [JobPostApplicationController::class, 'index'])->name('job_post_applications.index');
    Route::get('/job_post_applications/{jobPostApplication}',     [JobPostApplicationController::class, 'show'])->name('job_post_applications.show');
    Route::patch('/job_post_applications/{jobPostApplication}/status', [JobPostApplicationController::class, 'updateStatus'])->name('job_post_applications.updateStatus');
    Route::get('/job_post_applications/{jobPostApplication}/cv', [JobPostApplicationController::class, 'downloadCv'])->name('job_post_applications.downloadCv');

    // ── Admin Blog Management ─────────────────────────────────────────────
    Route::prefix('blogs')->name('blogs.')->group(function () {

        Route::pattern('blog', '[0-9]+');

        Route::get('/',          [AdminBlogController::class, 'index'])->name('index');
        Route::get('/pending',   [AdminBlogController::class, 'pending'])->name('pending');
        Route::get('/flagged',   [AdminBlogController::class, 'flagged'])->name('flagged');
        Route::get('/analytics', [AdminBlogController::class, 'analytics'])->name('analytics');
        Route::get('/carousel',  [AdminBlogController::class, 'carousel'])->name('carousel');
        Route::get('/create',    [AdminBlogController::class, 'create'])->name('create');
        Route::post('/',         [AdminBlogController::class, 'store'])->name('store');

        Route::post('/restore/{id}',     [AdminBlogController::class, 'restore'])->name('restore');
        Route::delete('/force/{id}',     [AdminBlogController::class, 'forceDestroy'])->name('force-destroy');
        Route::post('/carousel/reorder', [AdminBlogController::class, 'reorder'])->name('carousel.reorder');

        Route::get('/{blog}',          [AdminBlogController::class, 'show'])->name('show');
        Route::get('/{blog}/edit',     [AdminBlogController::class, 'edit'])->name('edit');
        Route::put('/{blog}',          [AdminBlogController::class, 'update'])->name('update');
        Route::delete('/{blog}',       [AdminBlogController::class, 'destroy'])->name('destroy');
        Route::post('/{blog}/approve', [AdminBlogController::class, 'approve'])->name('approve');
        Route::post('/{blog}/reject',  [AdminBlogController::class, 'reject'])->name('reject');
        Route::post('/{blog}/feature', [AdminBlogController::class, 'feature'])->name('feature');
        Route::post('/{blog}/archive', [AdminBlogController::class, 'archive'])->name('archive');
        Route::post('/{blog}/flag',    [AdminBlogController::class, 'flag'])->name('flag');

        Route::post('/reports/{report}/dismiss', [AdminBlogController::class, 'dismissReport'])->name('reports.dismiss');
    });

    // ── Events ────────────────────────────────────────────────────────────
    Route::get('/events',                    [AdminEventController::class, 'index'])->name('events.index');
    Route::get('/events/create',             [AdminEventController::class, 'create'])->name('events.create');
    Route::post('/events',                   [AdminEventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}',            [AdminEventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit',       [AdminEventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}',            [AdminEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}',         [AdminEventController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/{event}/approve',   [AdminEventController::class, 'approve'])->name('events.approve');
    Route::post('/events/{event}/reject',    [AdminEventController::class, 'reject'])->name('events.reject');
    Route::post('/events/{event}/publish',   [AdminEventController::class, 'publish'])->name('events.publish');
    Route::post('/events/{event}/draft',     [AdminEventController::class, 'draft'])->name('events.draft');
    Route::post('/events/{event}/toggle-setting', [AdminEventController::class, 'toggleSetting'])->name('events.toggleSetting');

    // ── Chatbot ───────────────────────────────────────────────────────────
    Route::post('/chatbot', [ChatbotController::class, 'chat']);
});

/*
|--------------------------------------------------------------------------
| Careers (public)
|--------------------------------------------------------------------------
*/
Route::prefix('career')->name('job_posts.')->group(function () {
    Route::get('/',                      [JobPostController::class, 'index'])->name('index');
    Route::get('/{jobPost:slug}',        [JobPostController::class, 'show'])->name('show');
    Route::post('/{jobPost:slug}/apply', [JobPostController::class, 'apply'])->name('apply');
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
    Route::post('/validate-code', [GiftCardController::class, 'validateCode'])->name('validate-code');
    Route::post('/redeem',        [GiftCardController::class, 'redeem'])->name('redeem.submit');
    Route::get('/success/{code}', [GiftCardController::class, 'redeemSuccess'])->name('redeem.success');
});

/*
|--------------------------------------------------------------------------
| Admin Gift Card Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin/gift-cards')->name('admin.gift-cards.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/',                   [AdminGiftCardController::class, 'index'])->name('index');
    Route::get('/{giftCard}',         [AdminGiftCardController::class, 'show'])->name('show');
    Route::post('/{giftCard}/status', [AdminGiftCardController::class, 'updateStatus'])->name('status');
    Route::post('/{giftCard}/resend', [AdminGiftCardController::class, 'resend'])->name('resend');
    Route::delete('/{giftCard}',      [AdminGiftCardController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Misc
|--------------------------------------------------------------------------
*/
Route::get('/category/{id}/products', [CategoryProductController::class, 'getProducts']);


use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\EventRegistrationController;
 
// 2. Add these routes in the "Public Events" section
//    (replace or add alongside your existing events routes):
 
Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
 
// Registration routes (anonymous users)
Route::get('/events/{event}/register',  [EventRegistrationController::class, 'register'])->name('events.register');
Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])->name('events.register.store');


// for site health check  broswer url http://127.0.0.1:8000/health
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::get('/health', HealthCheckResultsController::class);


// for preview donation recipt

use App\Models\Donation;
use App\Mail\DonationReceiptMail;

Route::get('/preview-receipt/{id}', function ($id) {
    $donation = Donation::findOrFail($id);
    return new DonationReceiptMail($donation);
});

// Newsletter Form

use App\Http\Controllers\NewsletterController;

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
     ->name('newsletter.subscribe');