<?php

namespace Tests\Feature\FormValidation;

use App\Models\Blog;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\FundraiserLevel;
use App\Models\JobPost;
use App\Models\User;
use App\Models\UserFundraiserLevel;
use App\Models\Volunteer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicFormsValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Campaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'donor']);

        $level = FundraiserLevel::create([
            'level_number' => 1, 'level_name' => 'Starter',
            'max_goal_amount' => 500000.00, 'max_active_campaigns' => 5, 'is_default' => true,
        ]);

        UserFundraiserLevel::create([
            'user_id' => $this->user->id, 'current_level_id' => $level->id, 'status' => 'active',
        ]);

        $category = Category::create([
            'name' => 'Health', 'slug' => 'health', 'icon' => 'heart', 'color' => '#2563eb', 'is_active' => true,
        ]);

        $this->campaign = Campaign::factory()->create([
            'user_id' => $this->user->id, 'category_id' => $category->id, 'campaign_state' => 'active',
        ]);
    }

    // ─── Contact Form ─────────────────────────────────────────────────────

    public function test_contact_happy_path(): void
    {
        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'General Inquiry',
            'message' => 'I would like to know more about your platform.',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_contact_name_required(): void
    {
        $response = $this->post('/contact', [
            'name' => '', 'email' => 'john@example.com', 'subject' => 'Test', 'message' => 'Test message.',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_contact_email_required(): void
    {
        $response = $this->post('/contact', [
            'name' => 'John', 'email' => '', 'subject' => 'Test', 'message' => 'Test message.',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_contact_email_format(): void
    {
        $response = $this->post('/contact', [
            'name' => 'John', 'email' => 'not-email', 'subject' => 'Test', 'message' => 'Test message.',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_contact_subject_required(): void
    {
        $response = $this->post('/contact', [
            'name' => 'John', 'email' => 'john@example.com', 'subject' => '', 'message' => 'Test message.',
        ]);

        $response->assertSessionHasErrors('subject');
    }

    public function test_contact_message_required(): void
    {
        $response = $this->post('/contact', [
            'name' => 'John', 'email' => 'john@example.com', 'subject' => 'Test', 'message' => '',
        ]);

        $response->assertSessionHasErrors('message');
    }

    public function test_contact_sql_injection(): void
    {
        $response = $this->post('/contact', [
            'name' => "'; DROP TABLE contacts; --",
            'email' => 'john@example.com',
            'subject' => 'Test',
            'message' => 'Test message.',
        ]);

        $this->assertDatabaseHas('contacts', ['name' => "'; DROP TABLE contacts; --"]);
    }

    // ─── Newsletter ───────────────────────────────────────────────────────

    public function test_newsletter_happy_path(): void
    {
        $response = $this->post('/newsletter/subscribe', ['email' => 'subscribe@example.com']);

        $response->assertSessionHasNoErrors();
    }

    public function test_newsletter_email_required(): void
    {
        $response = $this->post('/newsletter/subscribe', ['email' => '']);

        $response->assertSessionHasErrors('email');
    }

    public function test_newsletter_email_format(): void
    {
        $response = $this->post('/newsletter/subscribe', ['email' => 'not-email']);

        $response->assertSessionHasErrors('email');
    }

    public function test_newsletter_duplicate_email(): void
    {
        $this->post('/newsletter/subscribe', ['email' => 'dup@example.com']);
        $response = $this->post('/newsletter/subscribe', ['email' => 'dup@example.com']);

        $response->assertSessionHasNoErrors();
    }

    // ─── Partnership ──────────────────────────────────────────────────────

    public function test_partnership_happy_path(): void
    {
        Storage::fake('public');

        $response = $this->post('/partnership', [
            'name' => 'John Doe',
            'email' => 'john@partner.org',
            'phone' => '9876543210',
            'organization_name' => 'Green Earth NGO',
            'organization_type' => 'NGO',
            'organization_size' => '50-100',
            'location' => 'Mumbai',
            'website' => 'https://greenearth.org',
            'partnership_type' => 'financial',
            'goal' => 'Support education for underprivileged children',
            'timeline' => '6 months',
            'message' => 'We are excited to partner with you.',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_partnership_name_required(): void
    {
        $response = $this->post('/partnership', [
            'name' => '', 'email' => 'john@partner.org', 'organization_name' => 'Org', 'partnership_type' => 'financial',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_partnership_email_required(): void
    {
        $response = $this->post('/partnership', [
            'name' => 'John', 'email' => '', 'organization_name' => 'Org', 'partnership_type' => 'financial',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_partnership_email_format(): void
    {
        $response = $this->post('/partnership', [
            'name' => 'John', 'email' => 'invalid', 'organization_name' => 'Org', 'partnership_type' => 'financial',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_partnership_organization_name_required(): void
    {
        $response = $this->post('/partnership', [
            'name' => 'John', 'email' => 'john@partner.org', 'organization_name' => '', 'partnership_type' => 'financial',
        ]);

        $response->assertSessionHasErrors('organization_name');
    }

    public function test_partnership_partnership_type_required(): void
    {
        $response = $this->post('/partnership', [
            'name' => 'John', 'email' => 'john@partner.org', 'organization_name' => 'Org', 'partnership_type' => '',
        ]);

        $response->assertSessionHasErrors('partnership_type');
    }

    public function test_partnership_invalid_website(): void
    {
        $response = $this->post('/partnership', [
            'name' => 'John', 'email' => 'john@partner.org', 'organization_name' => 'Org',
            'partnership_type' => 'financial', 'website' => 'not-a-url',
        ]);

        $response->assertSessionHasErrors('website');
    }

    public function test_partnership_document_invalid_type(): void
    {
        Storage::fake('public');

        $response = $this->post('/partnership', [
            'name' => 'John', 'email' => 'john@partner.org', 'organization_name' => 'Org',
            'partnership_type' => 'financial',
            'document' => UploadedFile::fake()->create('script.exe', 100),
        ]);

        $response->assertSessionHasErrors('document');
    }

    public function test_partnership_document_too_large(): void
    {
        Storage::fake('public');

        $response = $this->post('/partnership', [
            'name' => 'John', 'email' => 'john@partner.org', 'organization_name' => 'Org',
            'partnership_type' => 'financial',
            'document' => UploadedFile::fake()->create('doc.pdf', 3000),
        ]);

        $response->assertSessionHasErrors('document');
    }

    // ─── Volunteer Apply ──────────────────────────────────────────────────

    public function test_volunteer_apply_happy_path(): void
    {
        $response = $this->actingAs($this->user)->post('/volunteer/apply', [
            'phone' => '9876543210',
            'country' => 'India',
            'state' => 'Maharashtra',
            'city' => 'Mumbai',
            'availability' => 'part_time',
            'skills' => 'Teaching, Mentoring',
            'bio' => 'I am passionate about social work and want to make a difference.',
            'message' => 'Looking forward to volunteering!',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_volunteer_apply_phone_required(): void
    {
        $response = $this->actingAs($this->user)->post('/volunteer/apply', [
            'phone' => '', 'country' => 'India', 'state' => 'MH', 'city' => 'Mumbai',
        ]);

        $response->assertSessionHasErrors('phone');
    }

    public function test_volunteer_apply_phone_format(): void
    {
        $response = $this->actingAs($this->user)->post('/volunteer/apply', [
            'phone' => '12345', 'country' => 'India', 'state' => 'MH', 'city' => 'Mumbai',
        ]);

        $response->assertSessionHasErrors('phone');
    }

    public function test_volunteer_apply_invalid_availability(): void
    {
        $response = $this->actingAs($this->user)->post('/volunteer/apply', [
            'phone' => '9876543210', 'country' => 'India', 'state' => 'MH', 'city' => 'Mumbai',
            'availability' => 'invalid_value',
        ]);

        $response->assertSessionHasErrors('availability');
    }

    public function test_volunteer_apply_guest_redirect(): void
    {
        $response = $this->post('/volunteer/apply', ['phone' => '9876543210']);

        $response->assertRedirect('/login');
    }

    // ─── Job Apply ────────────────────────────────────────────────────────

    public function test_job_apply_happy_path(): void
    {
        Storage::fake('public');

        $job = JobPost::create([
            'title' => 'Software Engineer',
            'slug' => 'software-engineer',
            'description' => 'We are hiring a software engineer.',
            'type' => 'full-time',
            'location' => 'Mumbai',
            'status' => 'active',
            'vacancies' => 2,
        ]);

        $response = $this->post("/career/{$job->slug}/apply", [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '9876543210',
            'cover_letter' => 'I am very interested in this position and believe I am a great fit.',
            'cv' => UploadedFile::fake()->create('resume.pdf', 500),
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_job_apply_name_required(): void
    {
        Storage::fake('public');

        $job = JobPost::create(['title' => 'Engineer', 'slug' => 'engineer', 'description' => 'Job desc', 'type' => 'full-time', 'status' => 'active', 'vacancies' => 1]);

        $response = $this->post("/career/{$job->slug}/apply", [
            'name' => '', 'email' => 'john@example.com', 'phone' => '9876543210',
            'cv' => UploadedFile::fake()->create('resume.pdf', 500),
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_job_apply_email_required(): void
    {
        Storage::fake('public');

        $job = JobPost::create(['title' => 'Engineer', 'slug' => 'engineer', 'description' => 'Job desc', 'type' => 'full-time', 'status' => 'active', 'vacancies' => 1]);

        $response = $this->post("/career/{$job->slug}/apply", [
            'name' => 'John', 'email' => '', 'phone' => '9876543210',
            'cv' => UploadedFile::fake()->create('resume.pdf', 500),
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_job_apply_email_format(): void
    {
        Storage::fake('public');

        $job = JobPost::create(['title' => 'Engineer', 'slug' => 'engineer', 'description' => 'Job desc', 'type' => 'full-time', 'status' => 'active', 'vacancies' => 1]);

        $response = $this->post("/career/{$job->slug}/apply", [
            'name' => 'John', 'email' => 'invalid', 'phone' => '9876543210',
            'cv' => UploadedFile::fake()->create('resume.pdf', 500),
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_job_apply_cv_required(): void
    {
        $job = JobPost::create(['title' => 'Engineer', 'slug' => 'engineer', 'description' => 'Job desc', 'type' => 'full-time', 'status' => 'active', 'vacancies' => 1]);

        $response = $this->post("/career/{$job->slug}/apply", [
            'name' => 'John', 'email' => 'john@example.com', 'phone' => '9876543210',
        ]);

        $response->assertSessionHasErrors('cv');
    }

    public function test_job_apply_cv_invalid_type(): void
    {
        Storage::fake('public');

        $job = JobPost::create(['title' => 'Engineer', 'slug' => 'engineer', 'description' => 'Job desc', 'type' => 'full-time', 'status' => 'active', 'vacancies' => 1]);

        $response = $this->post("/career/{$job->slug}/apply", [
            'name' => 'John', 'email' => 'john@example.com', 'phone' => '9876543210',
            'cv' => UploadedFile::fake()->create('script.exe', 500),
        ]);

        $response->assertSessionHasErrors('cv');
    }

    public function test_job_apply_cv_too_large(): void
    {
        Storage::fake('public');

        $job = JobPost::create(['title' => 'Engineer', 'slug' => 'engineer', 'description' => 'Job desc', 'type' => 'full-time', 'status' => 'active', 'vacancies' => 1]);

        $response = $this->post("/career/{$job->slug}/apply", [
            'name' => 'John', 'email' => 'john@example.com', 'phone' => '9876543210',
            'cv' => UploadedFile::fake()->create('resume.pdf', 6000),
        ]);

        $response->assertSessionHasErrors('cv');
    }

    // ─── Blog Comment ─────────────────────────────────────────────────────

    public function test_blog_comment_happy_path(): void
    {
        $blog = Blog::factory()->published()->create();

        $response = $this->actingAs($this->user)->post("/blog/{$blog->id}/comment", [
            'content' => 'Great article! Very informative.',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_blog_comment_content_required(): void
    {
        $blog = Blog::factory()->published()->create();

        $response = $this->actingAs($this->user)->post("/blog/{$blog->id}/comment", ['content' => '']);

        $response->assertSessionHasErrors('content');
    }

    public function test_blog_comment_content_min_length(): void
    {
        $blog = Blog::factory()->published()->create();

        $response = $this->actingAs($this->user)->post("/blog/{$blog->id}/comment", ['content' => 'A']);

        $response->assertSessionHasErrors('content');
    }

    public function test_blog_comment_content_max_length(): void
    {
        $blog = Blog::factory()->published()->create();

        $response = $this->actingAs($this->user)->post("/blog/{$blog->id}/comment", [
            'content' => str_repeat('A', 1001),
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_blog_comment_guest_redirect(): void
    {
        $blog = Blog::factory()->published()->create();

        $response = $this->post("/blog/{$blog->id}/comment", ['content' => 'Nice post!']);

        $response->assertRedirect('/login');
    }

    public function test_blog_comment_xss_stripped(): void
    {
        $blog = Blog::factory()->published()->create();

        $response = $this->actingAs($this->user)->post("/blog/{$blog->id}/comment", [
            'content' => '<script>alert("xss")</script>',
        ]);

        $response->assertSessionHasNoErrors();
    }

    // ─── Blog Report ──────────────────────────────────────────────────────

    public function test_blog_report_happy_path(): void
    {
        $blog = Blog::factory()->published()->create();

        $response = $this->actingAs($this->user)->post("/blog/{$blog->id}/report", [
            'reason' => 'This content is inappropriate.',
            'note' => 'Contains misleading information.',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_blog_report_reason_required(): void
    {
        $blog = Blog::factory()->published()->create();

        $response = $this->actingAs($this->user)->post("/blog/{$blog->id}/report", ['reason' => '']);

        $response->assertSessionHasErrors('reason');
    }

    public function test_blog_report_reason_max_length(): void
    {
        $blog = Blog::factory()->published()->create();

        $response = $this->actingAs($this->user)->post("/blog/{$blog->id}/report", [
            'reason' => str_repeat('A', 501),
        ]);

        $response->assertSessionHasErrors('reason');
    }

    public function test_blog_report_guest_redirect(): void
    {
        $blog = Blog::factory()->published()->create();

        $response = $this->post("/blog/{$blog->id}/report", ['reason' => 'spam']);

        $response->assertRedirect('/login');
    }

    // ─── Chatbot ──────────────────────────────────────────────────────────

    public function test_chatbot_message_required(): void
    {
        $response = $this->post('/chatbot', ['message' => '']);

        $response->assertSessionHasErrors('message');
    }

    public function test_chatbot_message_max_length(): void
    {
        $response = $this->post('/chatbot', ['message' => str_repeat('A', 1001)]);

        $response->assertSessionHasErrors('message');
    }

    public function test_chatbot_happy_path(): void
    {
        $response = $this->post('/chatbot', ['message' => 'How do I start a campaign?']);

        $response->assertSessionHasNoErrors();
    }

    // ─── Edge Cases ───────────────────────────────────────────────────────

    public function test_contact_session_errors_on_failure(): void
    {
        $response = $this->post('/contact', ['name' => '', 'email' => '', 'subject' => '', 'message' => '']);

        $response->assertSessionHasErrors();
        $response->assertRedirect();
    }

    public function test_newsletter_xss_email(): void
    {
        $response = $this->post('/newsletter/subscribe', ['email' => '<script>alert("xss")</script>']);

        $response->assertSessionHasErrors('email');
    }

    public function test_partnership_all_fields_empty(): void
    {
        $response = $this->post('/partnership', [
            'name' => '', 'email' => '', 'organization_name' => '', 'partnership_type' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'organization_name', 'partnership_type']);
    }
}
