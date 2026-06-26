<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\PartnershipAdminController;
use App\Http\Controllers\Admin\CampaignKycController;
use App\Http\Controllers\Admin\KycController as AdminKycController;
use App\Http\Controllers\Admin\JobPostController as AdminJobPostController;
use App\Http\Controllers\Admin\JobPostApplicationController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\GiftCardController as AdminGiftCardController;
use App\Http\Controllers\Admin\CategoryProductController as AdminCategoryProductController;

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