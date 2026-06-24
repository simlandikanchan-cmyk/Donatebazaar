<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JobPostController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPost::query()->where('status', 'active');

        // ── Exclude expired jobs ──────────────────────────────────────────
        // Jobs with no deadline are always shown.
        // Jobs whose deadline date has fully passed (end of that day) are hidden.
        $query->where(function ($q) {
            $q->whereNull('application_deadline')
              ->orWhereDate('application_deadline', '>=', now()->toDateString());
        });

        // ── Type filter ───────────────────────────────────────────────────
        if ($request->filled('type')) {
            if ($request->type === 'remote') {
                $query->where(function ($q) {
                    $q->where('type', 'remote')
                      ->orWhere('is_remote', true);
                });
            } else {
                $query->where('type', $request->type);
            }
        }

        // ── Search filter ─────────────────────────────────────────────────
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title',       'like', $search)
                  ->orWhere('description', 'like', $search)
                  ->orWhere('location',    'like', $search);
            });
        }

        $jobPosts = $query
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('job_posts.index', compact('jobPosts'));
    }

    public function show(JobPost $jobPost)
    {
        // 404 if inactive OR deadline has passed
        abort_unless(
            $jobPost->status === 'active' &&
            (
                is_null($jobPost->application_deadline) ||
                Carbon::parse($jobPost->application_deadline)->endOfDay()->isFuture()
            ),
            404
        );

        return view('job_posts.show', compact('jobPost'));
    }

    public function apply(Request $request, JobPost $jobPost)
    {
        // Reject applications for inactive or expired jobs
        abort_unless(
            $jobPost->status === 'active' &&
            (
                is_null($jobPost->application_deadline) ||
                Carbon::parse($jobPost->application_deadline)->endOfDay()->isFuture()
            ),
            404
        );

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:20',
            'cover_letter' => 'nullable|string|max:5000',
            'cv'           => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $cvPath = $request->file('cv')->store('job-applications', 'public');

        $jobPost->applications()->create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'phone'        => $validated['phone'] ?? null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'cv_path'      => $cvPath,
        ]);

        return back()->with('success', 'Application submitted successfully! We\'ll be in touch.');
    }
}