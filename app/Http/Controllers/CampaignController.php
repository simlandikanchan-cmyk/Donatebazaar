<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    // Show all campaigns
    // public function index()
    // {
    //     $campaigns = Campaign::latest()->get(); // fetch all for testing
    //     return view('campaigns.index', compact('campaigns'));
    // }

//     public function index()
// {
//     $campaigns = Campaign::where('user_id', Auth::id())->latest()->get();
//     return view('campaigns.index', compact('campaigns'));
// }

public function index()
{
    $campaigns = Campaign::where('user_id', Auth::id())->latest()->get();
    return view('campaigns.index', compact('campaigns'));
}





    // Show create campaign form
    public function create()
    {
        $categories = Category::all(); // fetch categories for dropdown
        return view('campaigns.create', compact('categories'));
    }

public function edit(Campaign $campaign)
{
    // Check ownership
    if ($campaign->user_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    return view('campaigns.edit', compact('campaign'));
}


// public function update(Request $request, Campaign $campaign)
// {
//     if ($campaign->user_id !== auth()->id()) {
//         abort(403, 'Unauthorized');
//     }

//     $request->validate([
//         'title' => 'required|string|max:255',
//         'goal_amount' => 'required|numeric',
//         'description' => 'required',
//     ]);

//     $campaign->update([
//         'title' => $request->title,
//         'goal_amount' => $request->goal_amount,
//         'description' => $request->description,
//     ]);

//     return redirect()->route('dashboard')
//         ->with('success', 'Campaign updated successfully!');
// }

// update function for campaign

public function update(Request $request, Campaign $campaign)
{
    if ($campaign->user_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'goal_amount' => 'required|numeric',
        'description' => 'required',
        'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Update basic fields
    $campaign->title = $request->title;
    $campaign->goal_amount = $request->goal_amount;
    $campaign->description = $request->description;

    // ✅ If new image uploaded
    if ($request->hasFile('cover_image')) {

        // Delete old image
        if ($campaign->cover_image && Storage::exists('public/' . $campaign->cover_image)) {
            Storage::delete('public/' . $campaign->cover_image);
        }

        // Store new image
        $path = $request->file('cover_image')->store('campaigns', 'public');
        $campaign->cover_image = $path;
    }

    $campaign->save();

    return redirect()->route('dashboard')
        ->with('success', 'Campaign updated successfully!');
}


// view campaign function


// public function show(Campaign $campaign)
// {
//     // Only campaign owner can see it (optional)
//     if ($campaign->user_id !== auth()->id()) {
//         abort(403, 'Unauthorized');
//     }

//     return view('campaigns.show', compact('campaign'));
// }


public function show(Campaign $campaign)
{
    if ($campaign->user_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    return view('campaigns.show', compact('campaign'));
}



public function publicShow($slug)
{
    $campaign = Campaign::where('slug', $slug)
        ->where('status', 'approved')
        ->firstOrFail();

    return view('campaigns.public', compact('campaign'));
}





    // Store campaign
public function store(Request $request)
{
    // Validate request
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required',
        'goal_amount' => 'required|numeric',
        'category_id' => 'required|exists:categories,id',
        'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'location' => 'nullable|string|max:255',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'video_url' => 'nullable|url',
    ]);

    //  Handle image upload
    $imagePath = null;
    if ($request->hasFile('cover_image')) {
        $imagePath = $request->file('cover_image')->store('campaigns', 'public');
    }

    // Generate unique slug
    $baseSlug = Str::slug($request->title);
    $slug = $baseSlug;
    $counter = 1;

    while (Campaign::where('slug', $slug)->exists()) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }

    //Create campaign
    $campaign = Campaign::create([
        'user_id' => Auth::id(),              // logged-in user
        'category_id' => $request->category_id,
        'title' => $request->title,
        'slug' => $slug,
        'description' => $request->description,
        'goal_amount' => $request->goal_amount,
        'raised_amount' => 0,
        'location' => $request->location,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'video_url' => $request->video_url,
        'cover_image' => $imagePath,
        'is_featured' => $request->has('is_featured'),
        'is_urgent' => $request->has('is_urgent'),
        'status' => 'pending',               // or 'approved' for testing
    ]);

    //Redirect back with success
    return redirect()->route('campaigns.index')
                     ->with('success', 'Campaign submitted successfully!');
}

}
