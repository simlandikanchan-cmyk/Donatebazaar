<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Campaign;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /*
    |------------------------------------------------------------------
    | INDEX (List All Categories)
    |------------------------------------------------------------------
    */
    public function index()
    {
        $categories = Category::withCount(['campaigns' => function ($q) {
            $q->where('campaign_state', Campaign::STATE_ACTIVE);
        }])->latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    /*
    |------------------------------------------------------------------
    | CREATE FORM
    |------------------------------------------------------------------
    */
    public function create()
    {
        return view('admin.categories.create');
    }

    /*
    |------------------------------------------------------------------
    | STORE (Save New Category)
    |------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|max:255',
            'icon'  => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
        ]);

        // Generate unique slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        Category::create([
            'name'      => $request->name,
            'slug'      => $slug,
            'icon'      => $request->icon,
            'color'     => $request->color,
            'is_active' => true,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    /*
    |------------------------------------------------------------------
    | EDIT FORM
    |------------------------------------------------------------------
    */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /*
    |------------------------------------------------------------------
    | UPDATE CATEGORY
    |------------------------------------------------------------------
    */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'  => 'required|max:255',
            'icon'  => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
        ]);

        // Generate unique slug (ignore current category)
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;

        while (
            Category::where('slug', $slug)
                ->where('id', '!=', $category->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        $category->update([
            'name'      => $request->name,
            'slug'      => $slug,
            'icon'      => $request->icon,
            'color'     => $request->color,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    /*
    |------------------------------------------------------------------
    | DELETE CATEGORY
    |------------------------------------------------------------------
    */
    public function destroy(Category $category)
    {
        if ($category->campaigns()->count() > 0) {
            return back()->with('error', 'Cannot delete category. It has campaigns.');
        }

        $category->delete();

        return back()->with('success', 'Deleted successfully');
    }

    /*
    |------------------------------------------------------------------
    | TOGGLE ACTIVE STATUS (BONUS 🔥)
    |------------------------------------------------------------------
    */
    public function toggle(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        return back()->with('success', 'Category status updated');
    }
}