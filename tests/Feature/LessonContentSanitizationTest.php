<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mews\Purifier\Facades\Purifier;
use Tests\TestCase;

class LessonContentSanitizationTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $instructorUser;

    protected $lesson;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = $this->createTenant(['domain' => 'test', 'name' => 'Test Center']);
        app()->instance('tenant', $this->tenant);

        $package = Package::create([
            'name' => 'Pro Plan',
            'slug' => 'pro',
            'price' => 99,
            'duration_in_days' => 30,
            'stripe_price_id' => 'price_pro',
        ]);

        Subscription::create([
            'tenant_id' => $this->tenant->id,
            'stripe_price' => $package->stripe_price_id,
            'name' => 'main',
            'stripe_id' => 'sub_test',
            'stripe_status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
            'status' => 'active',
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->setPermissionsTeamId($this->tenant->id);

        $instructorProfile = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Dr. Instructor',
            'email' => 'instructor@test.com',
        ]);

        $role = \App\Models\Role::firstOrCreate(['name' => 'instructor', 'guard_name' => 'web']);
        $editPermission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'edit courses', 'guard_name' => 'web']);
        $updatePermission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'update courses', 'guard_name' => 'web']);
        $role->givePermissionTo([$editPermission, $updatePermission]);

        $this->instructorUser = User::factory()->create([
            'email' => 'instructor@test.com',
            'tenant_id' => $this->tenant->id,
            'role' => 'instructor',
            'instructor_id' => $instructorProfile->id,
        ]);
        $this->instructorUser->assignRole($role);

        $course = Course::create([
            'tenant_id' => $this->tenant->id,
            'instructor_id' => $instructorProfile->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'description' => 'Test Description',
            'price' => 100,
        ]);

        $section = $course->sections()->create(['title' => 'Section 1', 'sort_order' => 1]);

        $this->lesson = Lesson::create([
            'section_id' => $section->id,
            'title' => 'Text Lesson',
            'type' => 'text',
            'sort_order' => 1,
        ]);
    }

    public function test_purifier_lesson_profile_strips_xss_vectors_and_keeps_formatting()
    {
        $dirty = '<p onclick="alert(1)">hello</p>'
            .'<img src="x" onerror="alert(1)">'
            .'<a href="javascript:alert(1)">click</a>'
            .'<script>alert(1)</script>'
            .'<strong>bold</strong><ul><li>item</li></ul>';

        $clean = Purifier::clean($dirty, 'lesson');

        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('onerror', $clean);
        $this->assertStringNotContainsString('javascript:', $clean);
        $this->assertStringNotContainsString('<script', $clean);

        // Legitimate formatting must survive
        $this->assertStringContainsString('hello', $clean);
        $this->assertStringContainsString('<strong>bold</strong>', $clean);
        $this->assertStringContainsString('<li>item</li>', $clean);
    }

    public function test_waf_blocks_blatant_event_handler_payloads()
    {
        $this->actingAs($this->instructorUser);

        $response = $this->put(route('center.lessons.update', ['tenant' => $this->tenant->domain, 'lesson' => $this->lesson->id]), [
            'title' => 'Text Lesson',
            'type' => 'text',
            'content' => '<img src="x" onerror="alert(1)">',
        ]);

        $response->assertStatus(403);
    }

    public function test_entity_encoded_javascript_uri_is_neutralized_on_save()
    {
        $this->actingAs($this->instructorUser);

        // "javascript&colon;" slips past the WAF regex (no literal "javascript:")
        // but the browser would decode it when the attribute is rendered raw.
        $response = $this->put(route('center.lessons.update', ['tenant' => $this->tenant->domain, 'lesson' => $this->lesson->id]), [
            'title' => 'Text Lesson',
            'type' => 'text',
            'content' => '<a href="javascript&colon;alert(1)">click</a><p>safe text</p>',
        ]);

        $response->assertRedirect();

        $stored = $this->lesson->fresh()->content;
        // The stored markup must not contain a decodable javascript: URI.
        $this->assertStringNotContainsString('javascript:', $stored);
        $this->assertStringNotContainsString('javascript&colon;', $stored);
        $this->assertStringContainsString('safe text', $stored);
    }

    public function test_sanitized_content_cleans_legacy_stored_payloads()
    {
        // Simulate a row written before write-time purification existed.
        $this->lesson->forceFill([
            'content' => '<img src="x" onerror="alert(1)"><p>lesson body</p>',
        ])->save();

        $rendered = $this->lesson->fresh()->sanitizedContent();

        $this->assertStringNotContainsString('onerror', $rendered);
        $this->assertStringContainsString('lesson body', $rendered);
    }
}
