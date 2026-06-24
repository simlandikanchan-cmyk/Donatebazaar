<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JobPostController extends Controller
{
    /**
     * Display a listing of job posts.
     */
    public function index(Request $request)
    {
        $jobPosts = JobPost::withCount('applications')
            ->when($request->search, function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.job_posts.index', compact('jobPosts'));
    }

    /**
     * Show the form for creating a new job post.
     */
    public function create()
    {
        return view('admin.job_posts.create');
    }

    /**
     * Store a newly created job post.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            $this->rules(),
            $this->messages()
        );

        // Generate unique slug
        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();

        // Checkbox fields
        $data['is_remote'] = $request->boolean('is_remote');
        $data['featured'] = $request->boolean('featured');

        // Convert comma-separated skills into JSON array
        if (!empty($data['skills'])) {
            $data['skills'] = collect(explode(',', $data['skills']))
                ->map(fn ($skill) => trim($skill))
                ->filter()
                ->values()
                ->toArray();
        }

        // Publish date
        if ($data['status'] === 'active' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // Initialize counters
        $data['views_count'] = 0;
        $data['applications_count'] = 0;

        JobPost::create($data);

        return redirect()
            ->route('admin.job_posts.index')
            ->with('success', 'Job post created successfully.');
    }

    /**
     * Display the specified job post.
     */
    public function show(JobPost $jobPost)
    {
        $jobPost->load('applications');

        return view('admin.job_posts.show', compact('jobPost'));
    }

    /**
     * Show the form for editing the specified job post.
     */
    public function edit(JobPost $jobPost)
    {
        // Convert skills array to comma-separated string for form
        if (is_array($jobPost->skills)) {
            $jobPost->skills = implode(', ', $jobPost->skills);
        }

        return view('admin.job_posts.edit', compact('jobPost'));
    }

    /**
     * Update the specified job post.
     */
    public function update(Request $request, JobPost $jobPost)
    {
        $data = $request->validate(
            $this->rules(),
            $this->messages()
        );

        // Regenerate slug if title changed
        if ($jobPost->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
        }

        // Checkbox fields
        $data['is_remote'] = $request->boolean('is_remote');
        $data['featured'] = $request->boolean('featured');

        // Convert skills string to array
        if (!empty($data['skills'])) {
            $data['skills'] = collect(explode(',', $data['skills']))
                ->map(fn ($skill) => trim($skill))
                ->filter()
                ->values()
                ->toArray();
        } else {
            $data['skills'] = null;
        }

        // Set publish time when moving from draft to active
        if (
            $data['status'] === 'active' &&
            !$jobPost->published_at
        ) {
            $data['published_at'] = now();
        }

        // Optional: clear published_at if moved back to draft
        if ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }

        $jobPost->update($data);

        return redirect()
            ->route('admin.job_posts.index')
            ->with('success', 'Job post updated successfully.');
    }

    /**
     * Soft delete the specified job post.
     */
    public function destroy(JobPost $jobPost)
    {
        $jobPost->delete();

        return redirect()
            ->route('admin.job_posts.index')
            ->with('success', 'Job post deleted successfully.');
    }

    /**
     * Validation rules.
     */
    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],

            'description' => ['required', 'string'],

            'type' => [
                'required',
                Rule::in([
                    'full-time',
                    'part-time',
                    'contract',
                    'internship',
                    'freelance',
                    'volunteer',
                ]),
            ],

            'department' => ['nullable', 'string', 'max:255'],

            'location' => ['nullable', 'string', 'max:255'],

            'salary' => ['nullable', 'string', 'max:255'],

            'experience_required' => ['nullable', 'string', 'max:255'],

            'skills' => ['nullable', 'string'],

            'vacancies' => ['nullable', 'integer', 'min:1'],

            'featured' => ['nullable', 'boolean'],

            'is_remote' => ['nullable', 'boolean'],

            'meta_title' => ['nullable', 'string', 'max:255'],

            'meta_description' => ['nullable', 'string'],

            'application_deadline' => ['nullable', 'date'],

            'published_at' => ['nullable', 'date'],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'active',
                    'closed',
                ]),
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    private function messages(): array
    {
        return [
            'title.required' => 'Job title is required.',
            'description.required' => 'Job description is required.',
            'type.required' => 'Please select a job type.',
            'status.required' => 'Please select a job status.',
            'vacancies.min' => 'Vacancies must be at least 1.',
        ];
    }
}