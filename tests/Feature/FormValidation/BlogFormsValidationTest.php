<?php

namespace Tests\Feature\FormValidation;

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogFormsValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'donor']);
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    private function validBlogPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'How to Start a Fundraising Campaign — A Complete Guide',
            'content' => str_repeat('This is a detailed blog post about fundraising strategies and tips. ', 20),
            'excerpt' => 'A complete guide to starting your fundraising journey.',
            'meta_title' => 'Fundraising Guide',
            'meta_description' => 'Learn how to start a fundraising campaign.',
        ], $overrides);
    }

    // ─── User Blog Create (StoreBlogRequest) ──────────────────────────────

    public function test_user_blog_create_happy_path(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload());

        $response->assertSessionHasNoErrors();
    }

    public function test_user_blog_create_title_required(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload(['title' => '']));

        $response->assertSessionHasErrors('title');
    }

    public function test_user_blog_create_title_min_length(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload(['title' => 'ABC']));

        $response->assertSessionHasErrors('title');
    }

    public function test_user_blog_create_title_max_length(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload(['title' => str_repeat('A', 256)]));

        $response->assertSessionHasErrors('title');
    }

    public function test_user_blog_create_content_required(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload(['content' => '']));

        $response->assertSessionHasErrors('content');
    }

    public function test_user_blog_create_content_min_length(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload(['content' => 'Too short']));

        $response->assertSessionHasErrors('content');
    }

    public function test_user_blog_create_excerpt_max_length(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload(['excerpt' => str_repeat('A', 501)]));

        $response->assertSessionHasErrors('excerpt');
    }

    public function test_user_blog_create_cover_image_invalid_type(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload([
            'cover_image' => UploadedFile::fake()->create('document.pdf', 100),
        ]));

        $response->assertSessionHasErrors('cover_image');
    }

    public function test_user_blog_create_cover_image_oversized(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload([
            'cover_image' => UploadedFile::fake()->image('large.jpg', 4000, 4000)->size(5000),
        ]));

        $response->assertSessionHasErrors('cover_image');
    }

    public function test_user_blog_create_category_id_exists(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload(['category_id' => 99999]));

        $response->assertSessionHasErrors('category_id');
    }

    public function test_user_blog_create_meta_title_max_length(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload(['meta_title' => str_repeat('A', 201)]));

        $response->assertSessionHasErrors('meta_title');
    }

    public function test_user_blog_create_meta_description_max_length(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload(['meta_description' => str_repeat('A', 161)]));

        $response->assertSessionHasErrors('meta_description');
    }

    public function test_user_blog_create_guest_redirect(): void
    {
        $response = $this->post('/user/dashboard/blogs', $this->validBlogPayload());

        $response->assertRedirect('/login');
    }

    public function test_user_blog_create_slug_unique(): void
    {
        Storage::fake('public');

        $existing = Blog::factory()->create(['slug' => 'existing-slug']);

        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload(['slug' => 'existing-slug']));

        $response->assertSessionHasErrors('slug');
    }

    public function test_user_blog_create_tag_ids_invalid(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload([
            'tag_ids' => [99999],
        ]));

        $response->assertSessionHasErrors('tag_ids.0');
    }

    // ─── User Blog Update (UpdateBlogRequest) ─────────────────────────────

    public function test_user_blog_update_happy_path(): void
    {
        $blog = Blog::factory()->create(['author_id' => $this->user->id, 'author_role' => 'donor']);

        $response = $this->actingAs($this->user)->put("/user/dashboard/blogs/{$blog->id}", $this->validBlogPayload([
            'title' => 'Updated Blog Title',
        ]));

        $response->assertSessionHasNoErrors();
    }

    public function test_user_blog_update_title_required(): void
    {
        $blog = Blog::factory()->create(['author_id' => $this->user->id, 'author_role' => 'donor']);

        $response = $this->actingAs($this->user)->put("/user/dashboard/blogs/{$blog->id}", $this->validBlogPayload(['title' => '']));

        $response->assertSessionHasErrors('title');
    }

    // ─── User Blog Submit ─────────────────────────────────────────────────

    public function test_user_blog_submit_requires_auth(): void
    {
        $blog = Blog::factory()->create();

        $response = $this->post("/user/dashboard/blogs/{$blog->id}/submit");

        $response->assertRedirect('/login');
    }

    // ─── Blog Like ────────────────────────────────────────────────────────

    public function test_blog_like_requires_auth(): void
    {
        $blog = Blog::factory()->published()->create();

        $response = $this->post("/blog/{$blog->id}/like");

        $response->assertRedirect('/login');
    }

    public function test_blog_like_happy_path(): void
    {
        $blog = Blog::factory()->published()->create();

        $response = $this->actingAs($this->user)->post("/blog/{$blog->id}/like");

        $response->assertSessionHasNoErrors();
    }

    // ─── Admin Blog Create (BlogRequest) ──────────────────────────────────

    public function test_admin_blog_create_happy_path(): void
    {
        $category = Category::create(['name' => 'Tech', 'slug' => 'tech', 'color' => '#000', 'is_active' => true]);

        $response = $this->actingAs($this->admin)->post('/admin/blogs', [
            'title' => 'Admin Blog Post Title',
            'content' => str_repeat('Content for the admin blog post. ', 20),
            'category_id' => $category->id,
            'status' => 'draft',
            'is_featured' => false,
            'allow_comments' => true,
            'meta_title' => 'Admin Blog',
            'meta_description' => 'Admin blog description.',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_admin_blog_create_title_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/blogs', [
            'title' => '', 'content' => 'Some content',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_admin_blog_create_content_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/blogs', [
            'title' => 'Test Title', 'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_admin_blog_create_invalid_status(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/blogs', [
            'title' => 'Test Title', 'content' => 'Some content', 'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_admin_blog_create_cover_image_oversized(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post('/admin/blogs', [
            'title' => 'Test Title', 'content' => 'Some content',
            'cover_image' => UploadedFile::fake()->image('large.jpg', 6000, 6000)->size(6000),
        ]);

        $response->assertSessionHasErrors('cover_image');
    }

    // ─── Admin Blog Update ────────────────────────────────────────────────

    public function test_admin_blog_update_happy_path(): void
    {
        $blog = Blog::factory()->create();

        $response = $this->actingAs($this->admin)->put("/admin/blogs/{$blog->id}", [
            'title' => 'Updated Admin Blog',
            'content' => str_repeat('Updated content. ', 20),
        ]);

        $response->assertSessionHasNoErrors();
    }

    // ─── Moderate Blog ────────────────────────────────────────────────────

    public function test_admin_blog_approve_requires_auth(): void
    {
        $blog = Blog::factory()->pending()->create();

        $response = $this->post("/admin/blogs/{$blog->id}/approve");

        $response->assertRedirect('/login');
    }

    public function test_admin_blog_reject_requires_reason(): void
    {
        $blog = Blog::factory()->pending()->create();

        $response = $this->actingAs($this->admin)->post("/admin/blogs/{$blog->id}/reject", []);

        $response->assertSessionHasErrors('reason');
    }

    public function test_admin_blog_reject_reason_max_length(): void
    {
        $blog = Blog::factory()->pending()->create();

        $response = $this->actingAs($this->admin)->post("/admin/blogs/{$blog->id}/reject", [
            'reason' => str_repeat('A', 1001),
        ]);

        $response->assertSessionHasErrors('reason');
    }

    // ─── Publish Blog ─────────────────────────────────────────────────────

    public function test_admin_blog_publish_future_date_allowed(): void
    {
        $blog = Blog::factory()->pending()->create();

        $response = $this->actingAs($this->admin)->post("/admin/blogs/{$blog->id}/publish", [
            'publish_at' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'is_featured' => true,
        ]);

        $response->assertSessionHasNoErrors();
    }

    // ─── Edge Cases ───────────────────────────────────────────────────────

    public function test_user_blog_create_xss_in_title(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', $this->validBlogPayload([
            'title' => '<script>alert("xss")</script>',
        ]));

        $response->assertSessionHasNoErrors();
        $blog = Blog::where('author_id', $this->user->id)->first();
        $this->assertStringNotContainsString('<script>', $blog->title);
    }

    public function test_user_blog_create_session_errors(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/blogs', ['title' => '']);

        $response->assertSessionHasErrors();
        $response->assertRedirect();
    }

    public function test_admin_blog_bulk_action_ids_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/blogs/bulk', [
            'ids' => '', 'action' => 'publish',
        ]);

        $response->assertSessionHasErrors('ids');
    }
}
