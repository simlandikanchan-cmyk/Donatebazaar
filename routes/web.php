<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Models\Campaign;




Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {

//     $campaigns = Campaign::where('user_id', auth()->id())->get();

//     return view('dashboard', compact('campaigns'));

// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


// use App\Http\Controllers\User\DashboardController;
// use App\Http\Controllers\Admin\AdminDashboardController;
// use App\Http\Controllers\Frontend\HomeController;


// Route::get('/', [HomeController::class, 'index'])->name('home');
// /*
// |--------------------------------------------------------------------------
// | User Routes
// |--------------------------------------------------------------------------
// */
// Route::middleware(['auth'])->prefix('user')->group(function () {

//     Route::get('/dashboard', [DashboardController::class, 'index'])
//         ->name('user.dashboard');

// });
// /*
// |--------------------------------------------------------------------------
// | Admin Routes
// |--------------------------------------------------------------------------
// */
// Route::middleware(['auth'])->prefix('admin')->group(function () {

//     Route::get('/dashboard', [AdminDashboardController::class, 'index'])
//         ->name('admin.dashboard');

// });


//campaign route

// Route::middleware(['auth'])->group(function () {
//     Route::get('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
//     Route::post('/campaign/store', [CampaignController::class, 'store'])->name('campaign.store');
// });
// Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
// Route::get('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
// Route::post('/campaign/store', [CampaignController::class, 'store'])->name('campaign.store');

// Route::middleware('auth')->group(function () {
//     Route::get('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
//     Route::post('/campaign/store', [CampaignController::class, 'store'])->name('campaign.store');
// });

Route::middleware('auth')->group(function () {
    Route::get('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
    Route::post('/campaign/store', [CampaignController::class, 'store'])->name('campaign.store');
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
});


// Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');

// dashboard route

// Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])
//     ->middleware('auth')
//     ->name('dashboard');

// user dashboard route 

// Route::get('/dashboard', function () {

//     $campaigns = Campaign::where('user_id', auth()->id())->get();

//     return view('dashboard', compact('campaigns'));

// })->middleware(['auth', 'verified'])->name('dashboard');

// for dynamic data fetching for performance insights

Route::get('/dashboard', function () {

    $campaigns = Campaign::where('user_id', auth()->id())->get();

    $monthlyData = Campaign::where('user_id', auth()->id())
        ->selectRaw('MONTH(created_at) as month, SUM(raised_amount) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

    return view('dashboard', compact('campaigns', 'monthlyData'));

})->middleware(['auth', 'verified'])->name('dashboard');



// campaign edit for update route

Route::middleware(['auth'])->group(function () {

    Route::get('/campaign/{campaign}/edit', [CampaignController::class, 'edit'])
        ->name('campaign.edit');

    Route::put('/campaign/{campaign}', [CampaignController::class, 'update'])
        ->name('campaign.update');

});



// campaign show route

Route::middleware(['auth'])->group(function () {

    Route::get('/campaign/{campaign}', 
        [CampaignController::class, 'show']
    )->name('campaign.show');

});



// public view route


Route::get('/campaign/{slug}', 
    [CampaignController::class, 'publicShow']
)->name('campaign.public');

// homepage

// use App\Http\Controllers\Frontend\HomeController;

// Route::get('/home', [HomeController::class, 'index'])->name('home');
// Route::get('/', [HomeController::class, 'index'])->name('home');
