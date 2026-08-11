<?php

namespace Tests\Unit\Models;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Section;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['domain' => 'test', 'name' => 'Test', 'onboarding_status' => 'completed']);
        app()->instance('tenant', $this->tenant);
    }

    #[Test]
    public function it_generates_registration_token_on_creation()
    {
        $course = Course::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Math 101',
            'description' => 'Basic Mathematics',
            'price' => 50,
        ]);

        $this->assertNotEmpty($course->registration_token);
        $this->assertEquals(16, strlen($course->registration_token));
    }

    #[Test]
    public function it_has_sections_relationship()
    {
        $course = Course::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Math 101',
        ]);

        Section::create([
            'tenant_id' => $this->tenant->id,
            'course_id' => $course->id,
            'title' => 'Chapter 1',
            'sort_order' => 1,
        ]);

        $this->assertCount(1, $course->sections);
    }

    #[Test]
    public function it_has_instructors_relationship()
    {
        $instructor = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Mr. Smith',
            'email' => 'smith@test.com',
            'phone' => '01000000001',
        ]);

        $course = Course::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Math 101',
        ]);

        $course->instructors()->attach($instructor->id, ['role' => 'lead']);

        $this->assertCount(1, $course->instructors);
    }

    #[Test]
    public function it_can_get_registration_url()
    {
        $course = Course::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Math 101',
            'registration_token' => 'test-token-123',
        ]);

        $url = $course->getRegistrationUrl();
        $this->assertNotNull($url);
        $this->assertStringContainsString('test-token-123', $url);
    }

    #[Test]
    public function it_returns_null_registration_url_when_no_token()
    {
        $course = Course::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Math 101',
        ]);

        // The creating hook auto-fills a token; simulate a legacy record
        // without one, which the model must tolerate.
        $course->forceFill(['registration_token' => null])->save();

        $this->assertNull($course->getRegistrationUrl());
    }

    #[Test]
    public function it_uses_soft_deletes()
    {
        $course = Course::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Math 101',
        ]);

        $course->delete();

        $this->assertSoftDeleted('courses', ['id' => $course->id]);
        $this->assertNull(Course::find($course->id));
        $this->assertNotNull(Course::withTrashed()->find($course->id));
    }
}
