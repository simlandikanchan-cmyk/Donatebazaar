<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalPageController extends Controller
{
    public function index(): View
    {
        $pages = LegalPage::with('updatedBy')->orderBy('title')->get();
        $all = LegalPage::slugs();

        // Merge so every known slug appears even if not yet created.
        $rows = collect($all)->map(function ($title, $slug) use ($pages) {
            $existing = $pages->firstWhere('slug', $slug);

            return (object) [
                'slug' => $slug,
                'title' => $title,
                'exists' => (bool) $existing,
                'updated_at' => $existing?->updated_at,
                'updated_by' => $existing?->updatedBy?->name,
            ];
        });

        return view('admin.legal.index', compact('rows'));
    }

    public function edit(string $slug): View
    {
        abort_unless(array_key_exists($slug, LegalPage::slugs()), 404);

        $page = LegalPage::where('slug', $slug)->first();

        return view('admin.legal.edit', compact('page', 'slug'));
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        abort_unless(array_key_exists($slug, LegalPage::slugs()), 404);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        LegalPage::updateOrCreate(
            ['slug' => $slug],
            [
                'title' => $data['title'],
                'content' => $data['content'],
                'updated_by' => auth()->id(),
            ]
        );

        $route = match ($slug) {
            'privacy' => 'privacy',
            'terms' => 'terms',
            'refund' => 'refund',
            'cookies' => 'cookies',
            default => 'privacy',
        };

        return redirect()->route('admin.legal.index')
            ->with('success', 'Legal page updated successfully. View it at /'.$slug.'.');
    }

    public function destroy(string $slug): RedirectResponse
    {
        abort_unless(array_key_exists($slug, LegalPage::slugs()), 404);

        LegalPage::where('slug', $slug)->delete();

        return redirect()->route('admin.legal.index')
            ->with('success', 'Legal page reset to default template.');
    }
}
