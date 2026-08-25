<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Services\SlugGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlugGeneratorTest extends TestCase
{
    use RefreshDatabase;

    private SlugGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new SlugGenerator();
    }

    public function test_generates_normal_slug(): void
    {
        $slug = $this->generator->unique(new Category(), 'Health & Wellness');

        $this->assertSame('health-wellness', $slug);
    }

    public function test_handles_special_characters(): void
    {
        $slug = $this->generator->unique(new Category(), 'Café & Résumé — 2025!');

        $this->assertSame('cafe-resume-2025', $slug);
    }

    public function test_appends_counter_for_duplicate_slug(): void
    {
        Category::create(['name' => 'Health', 'slug' => 'health', 'color' => '#000']);

        $slug = $this->generator->unique(new Category(), 'Health');

        $this->assertSame('health-1', $slug);
    }

    public function test_appends_counter_for_multiple_duplicates(): void
    {
        Category::create(['name' => 'A', 'slug' => 'a', 'color' => '#000']);
        Category::create(['name' => 'B', 'slug' => 'a-1', 'color' => '#000']);
        Category::create(['name' => 'C', 'slug' => 'a-2', 'color' => '#000']);

        $slug = $this->generator->unique(new Category(), 'A');

        $this->assertSame('a-3', $slug);
    }

    public function test_ignores_given_id_on_update(): void
    {
        $category = Category::create(['name' => 'Health', 'slug' => 'health', 'color' => '#000']);

        $slug = $this->generator->unique(new Category(), 'Health', 'slug', $category->id);

        $this->assertSame('health', $slug);
    }

    public function test_concurrent_creation_safety(): void
    {
        Category::create(['name' => 'Unique', 'slug' => 'unique', 'color' => '#000']);

        $slug1 = $this->generator->unique(new Category(), 'Unique');
        $slug2 = $this->generator->unique(new Category(), 'Unique');

        $this->assertSame('unique-1', $slug1);
        $this->assertSame('unique-1', $slug2);
    }
}
