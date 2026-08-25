<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryRepository
{
    public function getActive(): Collection
    {
        return Cache::remember('categories.active', 3600, function () {
            return Category::where('is_active', 1)->get();
        });
    }

    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }
}
