<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;

class CategoryProductController extends Controller
{
    public function getProducts($id)
    {
        $products = CategoryProduct::where('category_id', $id)
            ->where('is_active', true)
            ->select(
                'id',
                'name',
                'description',
                'price',
                'stock',
                'image'          // must be selected for accessor to work
            )
            ->get()
            ->map(fn ($p) => [
                'id'          => $p->id,
                'name'        => $p->name,
                'description' => $p->description,
                'price'       => $p->price,
                'stock'       => $p->stock,
                'image_url'   => $p->image_url,   // uses the normalised accessor
            ]);

        return response()->json($products);
    }
}