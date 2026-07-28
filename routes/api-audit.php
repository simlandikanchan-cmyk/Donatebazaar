<?php

use App\Models\Campaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Temporary route for query auditing
Route::get('/audit/queries', function () {
    $results = [];

    // Test 1: Homepage campaigns
    DB::enableQueryLog();
    $campaigns = Campaign::with('category', 'user')->limit(5)->get();
    $results['Homepage Campaigns'] = count(DB::getQueryLog());

    // Test 2: Campaign Detail
    DB::flushQueryLog();
    $campaign = Campaign::with([
        'donations.user',
        'products.categoryProduct',
        'updates',
        'followers'
    ])->find(57);
    $results['Campaign Detail'] = count(DB::getQueryLog());

    // Test 3: All Campaigns paginated
    DB::flushQueryLog();
    $allCampaigns = Campaign::with('category', 'user')
        ->withCount('donations')
        ->paginate(10);
    $results['All Campaigns (paginated)'] = count(DB::getQueryLog());

    // Test 4: Donations list
    DB::flushQueryLog();
    $donations = \App\Models\Donation::with('user', 'campaign')
        ->latest()
        ->limit(20)
        ->get();
    $results['Donations List'] = count(DB::getQueryLog());

    return response()->json([
        'audit_results' => $results,
        'target_threshold' => '< 15 queries per page',
        'note' => 'Delete this route after audit is complete'
    ]);
});