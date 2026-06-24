<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryProductController extends Controller
{
    /**
     * INDEX
     */
    public function index()
    {
        $products = CategoryProduct::with('category')
            ->latest()
            ->paginate(15);

        return view(
            'admin.category-products.index',
            compact('products')
        );
    }

    /**
     * CREATE
     */
    public function create()
    {
        $categories = Category::where('is_active', 1)->get();

        return view(
            'admin.category-products.create',
            compact('categories')
        );
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([

            'category_id' => 'required|exists:categories,id',

            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'price' => 'required|numeric|min:1',

            'stock' => 'required|integer|min:0',

            'product_type' => 'required',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $request->file('image')
                ->store('category-products', 'public');
        }

        CategoryProduct::create([

            'category_id' => $request->category_id,

            'name' => $request->name,

            'description' => $request->description,

            'price' => $request->price,

            'stock' => $request->stock,

            'product_type' => $request->product_type,

            'image' => $imagePath,

            'is_active' => $request->boolean('is_active'),

        ]);

        return redirect()
            ->route('admin.category-products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * EDIT
     */
    public function edit(CategoryProduct $categoryProduct)
    {
        $categories = Category::where('is_active', 1)->get();

        return view(
            'admin.category-products.edit',
            compact('categoryProduct', 'categories')
        );
    }

    /**
     * UPDATE
     */
    public function update(
        Request $request,
        CategoryProduct $categoryProduct
    ) {

        $request->validate([

            'category_id' => 'required|exists:categories,id',

            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'price' => 'required|numeric|min:1',

            'stock' => 'required|integer|min:0',

            'product_type' => 'required',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        ]);

        $imagePath = $categoryProduct->image;

        if ($request->hasFile('image')) {

            if (
                $categoryProduct->image &&
                Storage::disk('public')->exists($categoryProduct->image)
            ) {
                Storage::disk('public')
                    ->delete($categoryProduct->image);
            }

            $imagePath = $request->file('image')
                ->store('category-products', 'public');
        }

        $categoryProduct->update([

            'category_id' => $request->category_id,

            'name' => $request->name,

            'description' => $request->description,

            'price' => $request->price,

            'stock' => $request->stock,

            'product_type' => $request->product_type,

            'image' => $imagePath,

            'is_active' => $request->boolean('is_active'),

        ]);

        return redirect()
            ->route('admin.category-products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * DELETE
     */
    public function destroy(CategoryProduct $categoryProduct)
    {
        if (
            $categoryProduct->image &&
            Storage::disk('public')->exists($categoryProduct->image)
        ) {
            Storage::disk('public')
                ->delete($categoryProduct->image);
        }

        $categoryProduct->delete();

        return back()
            ->with('success', 'Product deleted successfully.');
    }
}