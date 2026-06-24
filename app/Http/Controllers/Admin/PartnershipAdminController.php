<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partnership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PartnershipStatusUpdated;
use Illuminate\Support\Facades\Auth;

class PartnershipAdminController extends Controller
{

    /*
    |-----------------------------------
    | Show All Partnerships (FILTERED + PAGINATED)
    |-----------------------------------
    */
    public function index(Request $request)
    {
        $query = Partnership::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('organization_name', 'like', "%{$request->search}%");
            });
        }

        //  Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // High priority filter
        if ($request->high_priority) {
            $query->where('priority_score', '>', 20);
        }

        //  With document
        if ($request->has('with_document')) {
            $query->whereNotNull('document');
        }

        //  Has website
        if ($request->has('with_website')) {
            $query->whereNotNull('website');
        }

        //  Sorting (IMPORTANT)
        $partnerships = $query
            ->orderByDesc('priority_score')
            ->latest()
            ->paginate(10);

        return view('admin.partnership.index', compact('partnerships'));
    }


    /*
    |-----------------------------------
    | Show Single Partnership
    |-----------------------------------
    */
    public function show($id)
    {
        $partnership = Partnership::findOrFail($id);

        return view('admin.partnership.show', compact('partnership'));
    }


    /*
    |-----------------------------------
    | Update Status (SMART VERSION)
    |-----------------------------------
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        $partnership = Partnership::findOrFail($id);

        $partnership->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(), //  track time
            'reviewed_by' => Auth::id() //  track admin
        ]);

        //  Use queue (performance)
        Mail::to($partnership->email)
            ->queue(new PartnershipStatusUpdated($partnership));

        return redirect()->route('admin.partnership.index')
            ->with('success', 'Updated Successfully');
    }


    /*
    |-----------------------------------
    | Delete Confirmation Page
    |-----------------------------------
    */
    public function deletePage($id)
    {
        $partnership = Partnership::findOrFail($id);

        return view('admin.partnership.delete', compact('partnership'));
    }


    /*
    |-----------------------------------
    | Delete Partnership
    |-----------------------------------
    */
    public function delete($id)
    {
        $partnership = Partnership::findOrFail($id);

        // Optional: delete document file
        if ($partnership->document) {
            \Storage::disk('public')->delete($partnership->document);
        }

        $partnership->delete();

        return redirect()->route('admin.partnership.index')
            ->with('success', 'Deleted Successfully');
    }

}