<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobPostApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class JobPostApplicationController extends Controller
{
    /**
     * Display a listing of all job applications.
     */
    public function index(Request $request)
    {
        $applications = JobPostApplication::with('jobPost')
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->job_id, function ($query) use ($request) {
                $query->where('job_id', $request->job_id);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%')
                      ->orWhere('phone', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Job filter dropdown
        $jobPosts = JobPost::orderBy('title')
            ->get(['id', 'title']);

        // Dashboard statistics
        $stats = [
            'total'       => JobPostApplication::count(),
            'new'         => JobPostApplication::where('status', 'new')->count(),
            'pending'     => JobPostApplication::where('status', 'pending')->count(),
            'shortlisted' => JobPostApplication::where('status', 'shortlisted')->count(),
            'interviewed' => JobPostApplication::where('status', 'interviewed')->count(),
            'hired'       => JobPostApplication::where('status', 'hired')->count(),
            'rejected'    => JobPostApplication::where('status', 'rejected')->count(),
        ];

        return view(
            'admin.job_post_applications.index',
            compact('applications', 'jobPosts', 'stats')
        );
    }

    /**
     * Display the specified application.
     */
    public function show(JobPostApplication $jobPostApplication)
    {
        $jobPostApplication->load('jobPost');

        return view(
            'admin.job_post_applications.show',
            compact('jobPostApplication')
        );
    }

    /**
     * Update application status and admin notes.
     */
    public function updateStatus(
        Request $request,
        JobPostApplication $jobPostApplication
    ) {
        $data = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'new',
                    'pending',
                    'shortlisted',
                    'interviewed',
                    'hired',
                    'rejected',
                ]),
            ],
            'admin_notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $jobPostApplication->update($data);

        // If hired, automatically close job if all vacancies are filled
        if ($data['status'] === 'hired') {
            $job = $jobPostApplication->jobPost;

            if (
                $job &&
                $job->vacancies &&
                $job->applications()
                    ->where('status', 'hired')
                    ->count() >= $job->vacancies
            ) {
                $job->update(['status' => 'closed']);
            }
        }

        return back()->with('success', 'Application status updated successfully.');
    }

    /**
     * Download applicant CV.
     */
    public function downloadCv(JobPostApplication $jobPostApplication)
    {
        if (!$jobPostApplication->cv_path) {
            return back()->with('error', 'CV file is not available.');
        }

        if (!Storage::disk('public')->exists($jobPostApplication->cv_path)) {
            return back()->with('error', 'CV file not found.');
        }

        $extension = pathinfo($jobPostApplication->cv_path, PATHINFO_EXTENSION);

        $filename = str($jobPostApplication->name)
            ->slug()
            ->append('-cv.' . $extension);

        return Storage::disk('public')->download(
            $jobPostApplication->cv_path,
            $filename
        );
    }

    /**
     * Delete a job application.
     */
    public function destroy(JobPostApplication $jobPostApplication)
    {
        // Delete CV file if it exists
        if (
            $jobPostApplication->cv_path &&
            Storage::disk('public')->exists($jobPostApplication->cv_path)
        ) {
            Storage::disk('public')->delete($jobPostApplication->cv_path);
        }

        // Decrement applications_count safely
        if ($jobPostApplication->jobPost) {
            $jobPostApplication->jobPost->decrement('applications_count');
        }

        $jobPostApplication->delete();

        return redirect()
            ->route('admin.job_post_applications.index')
            ->with('success', 'Application deleted successfully.');
    }
}