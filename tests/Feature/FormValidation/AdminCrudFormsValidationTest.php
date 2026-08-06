<?php

namespace Tests\Feature\FormValidation;

use App\Models\Category;
use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\FundraiserLevel;
use App\Models\JobPost;
use App\Models\LegalPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudFormsValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'donor']);
    }

    private function validCategoryPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Education',
            'icon' => 'book',
            'color' => '#00ff00',
        ], $overrides);
    }

    // ─── Categories ───────────────────────────────────────────────────────

    public function test_category_create_happy_path(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', $this->validCategoryPayload());

        $response->assertSessionHasNoErrors();
    }

    public function test_category_create_name_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', $this->validCategoryPayload(['name' => '']));

        $response->assertSessionHasErrors('name');
    }

    public function test_category_create_name_max_length(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', $this->validCategoryPayload(['name' => str_repeat('A', 256)]));

        $response->assertSessionHasErrors('name');
    }

    public function test_category_update_happy_path(): void
    {
        $category = Category::create(['name' => 'Old', 'slug' => 'old', 'color' => '#000']);

        $response = $this->actingAs($this->admin)->put("/admin/categories/{$category->id}", $this->validCategoryPayload(['name' => 'Updated']));

        $response->assertSessionHasNoErrors();
    }

    public function test_category_create_guest_redirect(): void
    {
        $response = $this->post('/admin/categories', $this->validCategoryPayload());

        $response->assertRedirect('/login');
    }

    // ─── Category Products ─────────────────────────────────────────────────

    public function test_category_product_create_happy_path(): void
    {
        $category = Category::create(['name' => 'Products', 'slug' => 'products', 'color' => '#000']);

        $response = $this->actingAs($this->admin)->post('/admin/category-products', [
            'category_id' => $category->id,
            'name' => 'T-Shirt',
            'description' => 'A cotton t-shirt',
            'price' => 499,
            'stock' => 100,
            'product_type' => 'physical',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_category_product_create_name_required(): void
    {
        $category = Category::create(['name' => 'Products', 'slug' => 'products', 'color' => '#000']);

        $response = $this->actingAs($this->admin)->post('/admin/category-products', [
            'category_id' => $category->id, 'name' => '', 'price' => 100, 'stock' => 10, 'product_type' => 'physical',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_category_product_create_price_numeric(): void
    {
        $category = Category::create(['name' => 'Products', 'slug' => 'products', 'color' => '#000']);

        $response = $this->actingAs($this->admin)->post('/admin/category-products', [
            'category_id' => $category->id, 'name' => 'Test', 'price' => 'not-number', 'stock' => 10, 'product_type' => 'physical',
        ]);

        $response->assertSessionHasErrors('price');
    }

    public function test_category_product_create_price_min(): void
    {
        $category = Category::create(['name' => 'Products', 'slug' => 'products', 'color' => '#000']);

        $response = $this->actingAs($this->admin)->post('/admin/category-products', [
            'category_id' => $category->id, 'name' => 'Test', 'price' => 0, 'stock' => 10, 'product_type' => 'physical',
        ]);

        $response->assertSessionHasErrors('price');
    }

    public function test_category_product_create_stock_integer(): void
    {
        $category = Category::create(['name' => 'Products', 'slug' => 'products', 'color' => '#000']);

        $response = $this->actingAs($this->admin)->post('/admin/category-products', [
            'category_id' => $category->id, 'name' => 'Test', 'price' => 100, 'stock' => 'not-int', 'product_type' => 'physical',
        ]);

        $response->assertSessionHasErrors('stock');
    }

    public function test_category_product_create_stock_min_zero(): void
    {
        $category = Category::create(['name' => 'Products', 'slug' => 'products', 'color' => '#000']);

        $response = $this->actingAs($this->admin)->post('/admin/category-products', [
            'category_id' => $category->id, 'name' => 'Test', 'price' => 100, 'stock' => -1, 'product_type' => 'physical',
        ]);

        $response->assertSessionHasErrors('stock');
    }

    public function test_category_product_create_image_invalid_type(): void
    {
        $category = Category::create(['name' => 'Products', 'slug' => 'products', 'color' => '#000']);

        $response = $this->actingAs($this->admin)->post('/admin/category-products', [
            'category_id' => $category->id, 'name' => 'Test', 'price' => 100, 'stock' => 10, 'product_type' => 'physical',
            'image' => UploadedFile::fake()->create('document.pdf', 100),
        ]);

        $response->assertSessionHasErrors('image');
    }

    // ─── Coupons ──────────────────────────────────────────────────────────

    public function test_coupon_create_happy_path(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/coupons', [
            'code' => 'SAVE20',
            'discount_type' => 'percent',
            'discount_value' => 20,
            'min_amount' => 500,
            'max_discount' => 1000,
            'usage_limit' => 100,
            'is_active' => true,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_coupon_create_code_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/coupons', [
            'code' => '', 'discount_type' => 'percent', 'discount_value' => 10,
        ]);

        $response->assertSessionHasErrors('code');
    }

    public function test_coupon_create_code_unique(): void
    {
        Coupon::factory()->create(['code' => 'DUPLICATE']);

        $response = $this->actingAs($this->admin)->post('/admin/coupons', [
            'code' => 'DUPLICATE', 'discount_type' => 'percent', 'discount_value' => 10,
        ]);

        $response->assertSessionHasErrors('code');
    }

    public function test_coupon_create_discount_type_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/coupons', [
            'code' => 'SAVE10', 'discount_type' => '', 'discount_value' => 10,
        ]);

        $response->assertSessionHasErrors('discount_type');
    }

    public function test_coupon_create_discount_type_invalid(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/coupons', [
            'code' => 'SAVE10', 'discount_type' => 'invalid', 'discount_value' => 10,
        ]);

        $response->assertSessionHasErrors('discount_type');
    }

    public function test_coupon_create_discount_value_numeric(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/coupons', [
            'code' => 'SAVE10', 'discount_type' => 'percent', 'discount_value' => 'not-number',
        ]);

        $response->assertSessionHasErrors('discount_value');
    }

    public function test_coupon_create_discount_value_min_zero(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/coupons', [
            'code' => 'SAVE10', 'discount_type' => 'percent', 'discount_value' => -1,
        ]);

        $response->assertSessionHasErrors('discount_value');
    }

    public function test_coupon_create_expires_at_date(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/coupons', [
            'code' => 'SAVE10', 'discount_type' => 'percent', 'discount_value' => 10,
            'expires_at' => 'not-a-date',
        ]);

        $response->assertSessionHasErrors('expires_at');
    }

    public function test_coupon_update_happy_path(): void
    {
        $coupon = Coupon::factory()->create();

        $response = $this->actingAs($this->admin)->patch("/admin/coupons/{$coupon->id}", [
            'code' => 'UPDATED',
            'discount_type' => 'fixed',
            'discount_value' => 100,
        ]);

        $response->assertSessionHasNoErrors();
    }

    // ─── FAQs ─────────────────────────────────────────────────────────────

    public function test_faq_create_happy_path(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/faqs', [
            'category' => 'General',
            'question' => 'How do I start a campaign?',
            'answer' => 'You can start by registering and clicking "Create Campaign".',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_faq_create_category_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/faqs', [
            'category' => '', 'question' => 'Test?', 'answer' => 'Answer.',
        ]);

        $response->assertSessionHasErrors('category');
    }

    public function test_faq_create_category_max_length(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/faqs', [
            'category' => str_repeat('A', 121), 'question' => 'Test?', 'answer' => 'Answer.',
        ]);

        $response->assertSessionHasErrors('category');
    }

    public function test_faq_create_question_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/faqs', [
            'category' => 'General', 'question' => '', 'answer' => 'Answer.',
        ]);

        $response->assertSessionHasErrors('question');
    }

    public function test_faq_create_question_max_length(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/faqs', [
            'category' => 'General', 'question' => str_repeat('A', 501), 'answer' => 'Answer.',
        ]);

        $response->assertSessionHasErrors('question');
    }

    public function test_faq_create_answer_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/faqs', [
            'category' => 'General', 'question' => 'Test?', 'answer' => '',
        ]);

        $response->assertSessionHasErrors('answer');
    }

    public function test_faq_update_happy_path(): void
    {
        $faq = Faq::factory()->create();

        $response = $this->actingAs($this->admin)->put("/admin/faqs/{$faq->id}", [
            'category' => 'Updated', 'question' => 'Updated?', 'answer' => 'Updated answer.',
        ]);

        $response->assertSessionHasNoErrors();
    }

    // ─── Fundraiser Levels ────────────────────────────────────────────────

    public function test_fundraiser_level_create_happy_path(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/fundraiser-levels', [
            'level_number' => 1,
            'level_name' => 'Bronze',
            'description' => 'Entry level',
            'max_goal_amount' => 100000,
            'max_active_campaigns' => 2,
            'min_campaigns_completed' => 0,
            'min_raised_percent' => 0,
            'kyc_requirement' => 'none',
            'badge_color' => '#cd7f32',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_fundraiser_level_create_level_number_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/fundraiser-levels', [
            'level_number' => '', 'level_name' => 'Bronze', 'max_goal_amount' => 100000,
            'max_active_campaigns' => 2, 'min_campaigns_completed' => 0, 'min_raised_percent' => 0,
            'kyc_requirement' => 'none',
        ]);

        $response->assertSessionHasErrors('level_number');
    }

    public function test_fundraiser_level_create_level_number_integer(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/fundraiser-levels', [
            'level_number' => 'not-int', 'level_name' => 'Bronze', 'max_goal_amount' => 100000,
            'max_active_campaigns' => 2, 'min_campaigns_completed' => 0, 'min_raised_percent' => 0,
            'kyc_requirement' => 'none',
        ]);

        $response->assertSessionHasErrors('level_number');
    }

    public function test_fundraiser_level_create_level_name_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/fundraiser-levels', [
            'level_number' => 1, 'level_name' => '', 'max_goal_amount' => 100000,
            'max_active_campaigns' => 2, 'min_campaigns_completed' => 0, 'min_raised_percent' => 0,
            'kyc_requirement' => 'none',
        ]);

        $response->assertSessionHasErrors('level_name');
    }

    public function test_fundraiser_level_create_max_goal_amount_numeric(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/fundraiser-levels', [
            'level_number' => 1, 'level_name' => 'Bronze', 'max_goal_amount' => 'not-number',
            'max_active_campaigns' => 2, 'min_campaigns_completed' => 0, 'min_raised_percent' => 0,
            'kyc_requirement' => 'none',
        ]);

        $response->assertSessionHasErrors('max_goal_amount');
    }

    public function test_fundraiser_level_create_min_raised_percent_range(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/fundraiser-levels', [
            'level_number' => 1, 'level_name' => 'Bronze', 'max_goal_amount' => 100000,
            'max_active_campaigns' => 2, 'min_campaigns_completed' => 0, 'min_raised_percent' => 101,
            'kyc_requirement' => 'none',
        ]);

        $response->assertSessionHasErrors('min_raised_percent');
    }

    // ─── Job Posts ────────────────────────────────────────────────────────

    public function test_job_post_create_happy_path(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/job_posts', [
            'title' => 'Software Engineer',
            'description' => 'We are looking for a skilled software engineer.',
            'type' => 'full-time',
            'department' => 'Engineering',
            'location' => 'Mumbai',
            'status' => 'active',
            'vacancies' => 2,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_job_post_create_title_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/job_posts', [
            'title' => '', 'description' => 'Desc', 'type' => 'full-time', 'status' => 'active',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_job_post_create_type_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/job_posts', [
            'title' => 'Engineer', 'description' => 'Desc', 'type' => '', 'status' => 'active',
        ]);

        $response->assertSessionHasErrors('type');
    }

    public function test_job_post_create_type_invalid(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/job_posts', [
            'title' => 'Engineer', 'description' => 'Desc', 'type' => 'invalid-type', 'status' => 'active',
        ]);

        $response->assertSessionHasErrors('type');
    }

    public function test_job_post_create_status_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/job_posts', [
            'title' => 'Engineer', 'description' => 'Desc', 'type' => 'full-time', 'status' => '',
        ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_job_post_create_status_invalid(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/job_posts', [
            'title' => 'Engineer', 'description' => 'Desc', 'type' => 'full-time', 'status' => 'invalid',
        ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_job_post_create_application_deadline_date(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/job_posts', [
            'title' => 'Engineer', 'description' => 'Desc', 'type' => 'full-time', 'status' => 'active',
            'application_deadline' => 'not-a-date',
        ]);

        $response->assertSessionHasErrors('application_deadline');
    }

    // ─── Edge Cases ───────────────────────────────────────────────────────

    public function test_admin_routes_guest_redirect(): void
    {
        $response = $this->post('/admin/categories', ['name' => 'Test']);

        $response->assertRedirect('/login');
    }

    public function test_category_create_special_chars(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', $this->validCategoryPayload([
            'name' => "Children's Health & Education (2024)",
        ]));

        $response->assertSessionHasNoErrors();
    }

    public function test_faq_create_session_errors(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/faqs', ['category' => '', 'question' => '', 'answer' => '']);

        $response->assertSessionHasErrors();
        $response->assertRedirect();
    }
}
