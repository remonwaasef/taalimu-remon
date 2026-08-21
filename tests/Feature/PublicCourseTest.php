<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_courses_index_returns_200()
    {
        $tenant = $this->createTenant(['domain' => 'test-public']);

        $response = $this->get(route('center.public.courses.index', ['tenant' => $tenant->domain]));

        $response->assertStatus(200);
        $response->assertSee($tenant->name);
    }

    public function test_public_course_show_returns_200()
    {
        $tenant = $this->createTenant(['domain' => 'test-public-2']);
        $course = Course::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $response = $this->get(route('center.public.courses.show', [
            'tenant' => $tenant->domain,
            'course' => $course->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee($course->title);
    }

    public function test_public_course_show_returns_404_for_inactive_course()
    {
        $tenant = $this->createTenant(['domain' => 'test-public-3']);
        $course = Course::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'inactive',
        ]);

        $response = $this->get(route('center.public.courses.show', [
            'tenant' => $tenant->domain,
            'course' => $course->id,
        ]));

        $response->assertStatus(404);
    }

    public function test_public_courses_index_has_seo_meta_tags()
    {
        $tenant = $this->createTenant(['domain' => 'test-public-4']);

        $response = $this->get(route('center.public.courses.index', ['tenant' => $tenant->domain]));

        $response->assertStatus(200);
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('og:title', false);
    }
}
