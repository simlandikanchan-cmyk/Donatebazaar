<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PartnershipStatusUpdated;
use App\Models\Partnership;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class PartnershipAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'all');
        $sort = $request->input('sort', 'created_at');
        $dir = $request->input('direction', 'desc');

        $allowedSorts = ['name', 'email', 'organization_name', 'partnership_type', 'priority_score', 'status', 'created_at', 'reviewed_at'];
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        $dir = $dir === 'asc' ? 'asc' : 'desc';

        $query = Partnership::with('reviewer');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('organization_name', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $partnerships = $query
            ->orderBy($sort, $dir)
            ->paginate(10);

        $stats = [
            'total'    => Partnership::count(),
            'pending'  => Partnership::where('status', 'pending')->count(),
            'approved' => Partnership::where('status', 'approved')->count(),
            'rejected' => Partnership::where('status', 'rejected')->count(),
        ];

        return view('admin.partnership.index', compact(
            'partnerships', 'search', 'status', 'sort', 'dir', 'stats'
        ));
    }

    public function show($id)
    {
        $partnership = Partnership::findOrFail($id);

        return view('admin.partnership.show', compact('partnership'));
    }

    public function update(Request $request, $id)
    {
        $partnership = Partnership::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'admin_notes' => 'nullable|string',
        ]);

        $partnership->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        Mail::to($partnership->email)
            ->queue(new PartnershipStatusUpdated($partnership));

        return redirect()->route('admin.partnership.index')
            ->with('success', 'Partnership '.$request->status.' successfully.');
    }

    public function deletePage($id)
    {
        $partnership = Partnership::findOrFail($id);

        return view('admin.partnership.delete', compact('partnership'));
    }

    public function destroy($id)
    {
        $partnership = Partnership::findOrFail($id);

        if ($partnership->document) {
            \Storage::disk('public')->delete($partnership->document);
        }

        $partnership->delete();

        return redirect()->route('admin.partnership.index')
            ->with('success', 'Partnership request deleted successfully.');
    }

    public function exportCsv(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'all');

        $query = Partnership::with('reviewer');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('organization_name', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $partnerships = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="partnerships-'.now()->format('Y-m-d').'.csv"',
        ];

        $callback = function () use ($partnerships) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Organisation', 'Type',
                'Size', 'Location', 'Partnership Type', 'Goal', 'Timeline',
                'Website', 'Has Document', 'Priority Score', 'Status',
                'Admin Notes', 'Reviewed By', 'Reviewed At', 'Submitted At',
            ]);

            foreach ($partnerships as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->name,
                    $p->email,
                    $p->phone,
                    $p->organization_name,
                    $p->organization_type,
                    $p->organization_size,
                    $p->location,
                    $p->partnership_type,
                    $p->goal,
                    $p->timeline,
                    $p->website,
                    $p->has_document ? 'Yes' : 'No',
                    $p->priority_score,
                    $p->status,
                    $p->admin_notes,
                    $p->reviewer?->name,
                    $p->reviewed_at ? Carbon::parse($p->reviewed_at)->format('Y-m-d H:i:s') : '',
                    $p->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function bulkUpdate(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:partnerships,id'],
            'status' => ['required', 'in:approved,rejected,pending'],
        ]);

        $now = now();
        $userId = Auth::id();

        $count = Partnership::whereIn('id', $data['ids'])->update([
            'status' => $data['status'],
            'reviewed_at' => $now,
            'reviewed_by' => $userId,
        ]);

        return back()->with('success', "{$count} partnership(s) {$data['status']}.");
    }

    public function bulkDelete(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:partnerships,id'],
        ]);

        $partnerships = Partnership::whereIn('id', $data['ids'])->get();

        foreach ($partnerships as $p) {
            if ($p->document) {
                \Storage::disk('public')->delete($p->document);
            }
            $p->delete();
        }

        return back()->with('success', count($partnerships).' partnership(s) deleted.');
    }
}
