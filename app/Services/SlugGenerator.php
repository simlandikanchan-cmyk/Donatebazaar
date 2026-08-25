<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Generates a URL-safe, unique slug for an Eloquent model column.
 *
 * Consolidates the duplicated per-controller slug loops (CampaignController,
 * Admin/EventController, EventController, Admin/CategoryController) into a single
 * tested implementation. Behaviour is identical to the original loops: derive a
 * base slug from the title, then append "-1", "-2", ... until unique.
 *
 * Note: the uniqueness check is best-effort (a read-then-write). For true
 * concurrent-creation safety, the slug column should also carry a UNIQUE index
 * (recommended as a follow-up migration); this generator keeps the application
 * layer behaviour stable in the meantime.
 */
final class SlugGenerator
{
    public function unique(Model $model, string $title, string $column = 'slug', ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 1;

        while ($this->slugExists($model, $column, $slug, $ignoreId)) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    private function slugExists(Model $model, string $column, string $slug, ?int $ignoreId): bool
    {
        $query = $model->newQuery()->where($column, $slug);

        if ($ignoreId !== null) {
            $query->whereKeyNot($ignoreId);
        }

        return $query->exists();
    }
}
