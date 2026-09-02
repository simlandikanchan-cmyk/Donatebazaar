<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $catId = $request->input('category');
        $status = $request->input('status', 'all');
        $sort = $request->input('sort', 'created_at');
        $dir = $request->input('direction', 'desc');

        $allowedSorts = ['name', 'price', 'stock', 'created_at', 'product_type'];
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        $dir = $dir === 'asc' ? 'asc' : 'desc';

        $query = CategoryProduct::with('category');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($catId) {
            $query->where('category_id', $catId);
        }

        if ($status === 'active') {
            $query->where('is_active', 1);
        } elseif ($status === 'inactive') {
            $query->where('is_active', 0);
        }

        $products = $query->orderBy($sort, $dir)->paginate(15);

        $activeCount = CategoryProduct::where('is_active', 1)->count();

        $categories = Category::orderBy('name')->get();

        return view('admin.category-products.index', compact(
            'products', 'categories', 'search', 'catId', 'status',
            'sort', 'dir', 'activeCount'
        ));
    }

    public function create()
    {
        $categories = Category::where('is_active', 1)->get();

        return view('admin.category-products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:0',
            'product_type' => ['required', Rule::in(['physical', 'digital', 'service', 'donation'])],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('category-products', 'public');
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

    public function edit(CategoryProduct $categoryProduct)
    {
        $categories = Category::where('is_active', 1)->get();

        return view('admin.category-products.edit', compact('categoryProduct', 'categories'));
    }

    public function update(Request $request, CategoryProduct $categoryProduct)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:0',
            'product_type' => ['required', Rule::in(['physical', 'digital', 'service', 'donation'])],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $categoryProduct->image;

        if ($request->hasFile('image')) {
            if ($categoryProduct->image && Storage::disk('public')->exists($categoryProduct->image)) {
                Storage::disk('public')->delete($categoryProduct->image);
            }
            $imagePath = $request->file('image')->store('category-products', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($categoryProduct->image && Storage::disk('public')->exists($categoryProduct->image)) {
                Storage::disk('public')->delete($categoryProduct->image);
            }
            $imagePath = null;
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

    public function destroy(CategoryProduct $categoryProduct)
    {
        if ($categoryProduct->image && Storage::disk('public')->exists($categoryProduct->image)) {
            Storage::disk('public')->delete($categoryProduct->image);
        }

        $categoryProduct->delete();

        return back()->with('success', 'Product deleted successfully.');
    }

    public function bulkToggle(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:category_products,id'],
            'is_active' => ['required', 'boolean'],
        ]);

        $count = CategoryProduct::whereIn('id', $data['ids'])->update([
            'is_active' => $data['is_active'],
        ]);

        $label = $data['is_active'] ? 'activated' : 'deactivated';

        return back()->with('success', "{$count} product(s) {$label}.");
    }

    public function bulkDelete(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:category_products,id'],
        ]);

        $products = CategoryProduct::whereIn('id', $data['ids'])->get();

        foreach ($products as $product) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
        }

        $count = count($products);

        return back()->with('success', "{$count} product(s) deleted.");
    }

    public function exportCsv(Request $request)
    {
        $search = $request->input('search');
        $catId = $request->input('category');
        $status = $request->input('status', 'all');

        $query = CategoryProduct::with('category');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($catId) {
            $query->where('category_id', $catId);
        }

        if ($status === 'active') {
            $query->where('is_active', 1);
        } elseif ($status === 'inactive') {
            $query->where('is_active', 0);
        }

        $products = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="category-products-'.now()->format('Y-m-d').'.csv"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID', 'Name', 'Description', 'Category', 'Type',
                'Price', 'Stock', 'Status', 'Image', 'Created At',
            ]);

            foreach ($products as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->name,
                    $p->description,
                    $p->category?->name,
                    $p->product_type,
                    $p->price,
                    $p->stock,
                    $p->is_active ? 'Active' : 'Inactive',
                    $p->image_url,
                    $p->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
