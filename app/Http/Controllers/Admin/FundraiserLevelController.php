<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundraiserLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FundraiserLevelController extends Controller
{
    public function index(): View
    {
        $levels = FundraiserLevel::orderBy('level_number')->get();

        $stats = [
            'total' => $levels->count(),
            'auto' => $levels->where('requires_admin_approval', false)->count(),
            'approval' => $levels->where('requires_admin_approval', true)->count(),
        ];

        return view('admin.fundraiser-levels.index', compact('levels', 'stats'));
    }

    public function create(): View
    {
        return view('admin.fundraiser-levels.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'level_number' => 'required|integer|min:1|unique:fundraiser_levels,level_number',
            'level_name' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'max_goal_amount' => 'required|numeric|min:0',
            'max_active_campaigns' => 'required|integer|min:1',
            'min_campaigns_completed' => 'required|integer|min:0',
            'min_raised_percent' => 'required|numeric|min:0|max:100',
            'requires_admin_approval' => 'nullable|boolean',
            'kyc_requirement' => 'required|in:none,basic,full,org',
            'badge_color' => 'nullable|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        $data['requires_admin_approval'] = $request->has('requires_admin_approval');
        $makeDefault = $request->has('is_default');

        $level = FundraiserLevel::create($data);

        if ($makeDefault) {
            $this->setDefault($level);
        }

        return redirect()->route('admin.fundraiser-levels.index')
            ->with('success', 'Fundraiser level created.');
    }

    public function edit(FundraiserLevel $fundraiserLevel): View
    {
        return view('admin.fundraiser-levels.edit', compact('fundraiserLevel'));
    }

    public function update(Request $request, FundraiserLevel $fundraiserLevel): RedirectResponse
    {
        $data = $request->validate([
            'level_number' => 'required|integer|min:1|unique:fundraiser_levels,level_number,'.$fundraiserLevel->id,
            'level_name' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'max_goal_amount' => 'required|numeric|min:0',
            'max_active_campaigns' => 'required|integer|min:1',
            'min_campaigns_completed' => 'required|integer|min:0',
            'min_raised_percent' => 'required|numeric|min:0|max:100',
            'requires_admin_approval' => 'nullable|boolean',
            'kyc_requirement' => 'required|in:none,basic,full,org',
            'badge_color' => 'nullable|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        $data['requires_admin_approval'] = $request->has('requires_admin_approval');

        $fundraiserLevel->update($data);

        if ($request->has('is_default')) {
            $this->setDefault($fundraiserLevel);
        }

        return redirect()->route('admin.fundraiser-levels.index')
            ->with('success', 'Fundraiser level updated.');
    }

    public function destroy(FundraiserLevel $fundraiserLevel): RedirectResponse
    {
        if ($fundraiserLevel->is_default) {
            return back()->with('error', 'Cannot delete the default level. Set another level as default first.');
        }

        $fundraiserLevel->delete();

        return back()->with('success', 'Fundraiser level deleted.');
    }

    protected function setDefault(FundraiserLevel $level): void
    {
        FundraiserLevel::where('id', '!=', $level->id)->update(['is_default' => false]);
        $level->update(['is_default' => true]);
    }
}
