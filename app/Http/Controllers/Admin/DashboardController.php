<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index()
    {
        return view('admin.dashboard', $this->dashboardService->indexData());
    }

    public function campaigns(Request $request)
    {
        return response()->json($this->dashboardService->campaignList($request));
    }
}