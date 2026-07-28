<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Campaign;
use App\Models\FundraiserLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // -------------------------------------------------------------------------
    // Show full profile page (our new page)
    // -------------------------------------------------------------------------
    public function show(Request $request): View
    {
        $user = $request->user();
        $campaignCount = $user->campaigns()->whereNotIn('campaign_state', ['expired', 'rejected'])->count();
        $campIds = $user->campaigns()->whereNotIn('campaign_state', ['expired', 'rejected'])->pluck('id');
        $donationCount = 0;
        $donationTotal = 0;
        if ($campIds->isNotEmpty()) {
            $donationCount = \App\Models\Donation::whereIn('campaign_id', $campIds)->whereNotNull('paid_at')->count();
            $donationTotal = Campaign::whereIn('id', $campIds)->sum('raised_amount');
        }
        $userCampaigns = $user->campaigns()->withCount('donations')->whereNotIn('campaign_state', ['expired', 'rejected'])->latest()->get();

        /* ── Fundraiser Level ── */
        $levelName = $user->fundraiserLevelName();
        $currentLevel = $user->assignedLevel;
        $nextLevel = null;
        $levelProgress = 0;
        $campaignsCompleted = 0;
        $totalRaisedAll = 0;
        if ($currentLevel) {
            $nextLevel = FundraiserLevel::nextAfter($currentLevel->level_number);
            if ($nextLevel) {
                $campaignsCompleted = $user->campaigns()->whereIn('campaign_state', ['completed', 'active'])->count();
                $totalRaisedAll = Campaign::where('user_id', $user->id)->sum('raised_amount');
                $campPct = $nextLevel->min_campaigns_completed > 0
                    ? min(100, ($campaignsCompleted / $nextLevel->min_campaigns_completed) * 100)
                    : 100;
                $raisedPct = $nextLevel->min_raised_percent > 0
                    ? min(100, ($totalRaisedAll / $nextLevel->min_raised_percent) * 100)
                    : 100;
                $levelProgress = min(100, round(($campPct + $raisedPct) / 2));
            }
        }

        return view('profile.show', compact(
            'user', 'campaignCount', 'donationCount', 'donationTotal', 'userCampaigns',
            'levelName', 'currentLevel', 'nextLevel', 'levelProgress', 'campaignsCompleted', 'totalRaisedAll'
        ));
    }

    // -------------------------------------------------------------------------
    // Breeze: show edit form  (keep for any Breeze routes still pointing here)
    // -------------------------------------------------------------------------
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Breeze: update profile info
    // -------------------------------------------------------------------------
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.show')->with('success', 'Profile updated successfully.');
    }

    // -------------------------------------------------------------------------
    // Update avatar / profile photo
    // -------------------------------------------------------------------------
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();

        // Delete old avatar from storage
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return Redirect::route('profile.show')->with('success', 'Profile photo updated.');
    }

    // -------------------------------------------------------------------------
    // Update cover image
    // -------------------------------------------------------------------------
    public function updateCover(Request $request): RedirectResponse
    {
        $request->validate([
            'cover_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $user = $request->user();

        // Delete old cover from storage
        if ($user->cover_image && Storage::disk('public')->exists($user->cover_image)) {
            Storage::disk('public')->delete($user->cover_image);
        }

        $path = $request->file('cover_image')->store('covers', 'public');
        $user->update(['cover_image' => $path]);

        return Redirect::route('profile.show')->with('success', 'Cover photo updated.');
    }

    // -------------------------------------------------------------------------
    // Update password
    // -------------------------------------------------------------------------
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return Redirect::route('profile.show')
                ->withErrors(['current_password' => 'The current password you entered is incorrect.'])
                ->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        return Redirect::route('profile.show')->with('success', 'Password changed successfully.');
    }

    // -------------------------------------------------------------------------
    // Breeze: delete account
    // -------------------------------------------------------------------------
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
