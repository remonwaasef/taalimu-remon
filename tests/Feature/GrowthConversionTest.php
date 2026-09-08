<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\DemandRequest;
use App\Models\Instructor;
use App\Models\PublicProfile;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Waitlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GrowthConversionTest extends TestCase
{
    use RefreshDatabase;

    private function createTenantWithPublishedCourse(): array
    {
        $tenant = $this->createTenant(['domain' => 'growth-conv-' . uniqid()]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test User',
            'email' => 'user_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
        ]);

        $instructor = Instructor::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'name' => 'Ahmed Mohammed',
            'email' => 'instructor_' . uniqid() . '@test.com',
            'specialization' => 'Mathematics',
            'status' => 'active',
        ]);

        $courseSlug = 'math-grade-12-' . uniqid();
        $course = Course::create([
            'tenant_id' => $tenant->id,
            'instructor_id' => $instructor->id,
            'title' => 'Mathematics Grade 12',
            'slug' => $courseSlug,
            'description' => 'Advanced mathematics for grade 12 students.',
            'short_description' => 'Complete math preparation for grade 12.',
            'price' => 500,
            'level' => 'Grade 12',
            'category' => 'Mathematics',
            'delivery_mode' => 'offline',
            'capacity' => 30,
            'enrolled_count' => 0,
            'start_date' => now()->addMonth(),
            'published' => true,
        ]);

        $profileSlug = 'ahmed-math-' . uniqid();
        $profile = PublicProfile::create([
            'tenant_id' => $tenant->id,
            'profilable_type' => Instructor::class,
            'profilable_id' => $instructor->id,
            'slug' => $profileSlug,
            'title' => 'Ahmed Mohammed',
            'headline' => 'Math Teacher',
            'published' => true,
            'visibility' => ['name' => true],
        ]);

        return compact('tenant', 'user', 'instructor', 'course', 'profile');
    }

    public function test_program_listing_returns_200(): void
    {
        ['profile' => $profile] = $this->createTenantWithPublishedCourse();

        $response = $this->get(route('growth.programs.index', $profile->slug));

        $response->assertStatus(200);
        $response->assertSee('Mathematics Grade 12');
    }

    public function test_program_detail_returns_200(): void
    {
        ['profile' => $profile, 'course' => $course] = $this->createTenantWithPublishedCourse();

        $response = $this->get(route('growth.programs.show', [$profile->slug, $course->slug]));

        $response->assertStatus(200);
        $response->assertSee('Mathematics Grade 12');
        $response->assertSee('500');
    }

    public function test_unpublished_program_returns_404(): void
    {
        ['tenant' => $tenant, 'profile' => $profile, 'instructor' => $instructor] = $this->createTenantWithPublishedCourse();

        $course = Course::create([
            'tenant_id' => $tenant->id,
            'instructor_id' => $instructor->id,
            'title' => 'Draft Course',
            'slug' => 'draft-course',
            'published' => false,
        ]);

        $response = $this->get(route('growth.programs.show', [$profile->slug, $course->slug]));

        $response->assertStatus(404);
    }

    public function test_waitlist_join_when_full(): void
    {
        ['profile' => $profile, 'course' => $course] = $this->createTenantWithPublishedCourse();

        $course->update(['capacity' => 1, 'enrolled_count' => 1]);

        $response = $this->post(route('growth.waitlist.store', [$profile->slug, $course->slug]), [
            'name' => 'Test Student',
            'phone' => '+201234567890',
            'email' => 'student@test.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('waitlists', [
            'tenant_id' => $course->tenant_id,
            'course_id' => $course->id,
            'name' => 'Test Student',
            'status' => 'pending',
        ]);
    }

    public function test_waitlist_rejects_when_not_full(): void
    {
        ['profile' => $profile, 'course' => $course] = $this->createTenantWithPublishedCourse();

        $response = $this->post(route('growth.waitlist.store', [$profile->slug, $course->slug]), [
            'name' => 'Test Student',
        ]);

        $response->assertSessionHasErrors('course');
    }

    public function test_demand_request_submission(): void
    {
        ['profile' => $profile] = $this->createTenantWithPublishedCourse();

        $response = $this->post(route('growth.demand.store', $profile->slug), [
            'name' => 'Demand Student',
            'phone' => '+201234567890',
            'subject' => 'English Language',
            'level' => 'Intermediate',
            'preferred_days' => 'Saturday, Sunday',
            'preferred_time' => 'Evening',
            'delivery_mode' => 'online',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('demand_requests', [
            'tenant_id' => $profile->tenant_id,
            'name' => 'Demand Student',
            'subject' => 'English Language',
            'status' => 'new',
        ]);
    }

    public function test_demand_show_page_returns_200(): void
    {
        ['profile' => $profile] = $this->createTenantWithPublishedCourse();

        $response = $this->get(route('growth.demand.show', $profile->slug));

        $response->assertStatus(200);
        $response->assertSee('Request a Program');
    }

    public function test_course_availability_status(): void
    {
        ['course' => $course] = $this->createTenantWithPublishedCourse();

        // Available
        $this->assertEquals('available', $course->availabilityStatus());

        // Limited seats
        $course->update(['capacity' => 28, 'enrolled_count' => 25]);
        $this->assertEquals('limited_seats', $course->availabilityStatus());

        // Full
        $course->update(['capacity' => 30, 'enrolled_count' => 30]);
        $this->assertEquals('full', $course->availabilityStatus());
        $this->assertTrue($course->isFull());
        $this->assertEquals(0, $course->getAvailableSeats());

        // Unpublished
        $course->update(['published' => false]);
        $this->assertEquals('coming_soon', $course->availabilityStatus());
    }

    public function test_waitlist_is_tenant_isolated(): void
    {
        $result1 = $this->createTenantWithPublishedCourse();
        $result2 = $this->createTenantWithPublishedCourse();

        $result1['course']->update(['capacity' => 1, 'enrolled_count' => 1]);
        $result2['course']->update(['capacity' => 1, 'enrolled_count' => 1]);

        $this->post(route('growth.waitlist.store', [$result1['profile']->slug, $result1['course']->slug]), [
            'name' => 'Student A',
        ]);

        $this->post(route('growth.waitlist.store', [$result2['profile']->slug, $result2['course']->slug]), [
            'name' => 'Student B',
        ]);

        $allWaitlists = DB::table('waitlists')->get();

        $hasStudentA = DB::table('waitlists')
            ->where('tenant_id', $result1['tenant']->id)
            ->where('name', 'Student A')
            ->exists();
        $this->assertTrue($hasStudentA, "Student A should belong to tenant {$result1['tenant']->id}. Total records: {$allWaitlists->count()}");

        $hasStudentBInTenant1 = DB::table('waitlists')
            ->where('tenant_id', $result1['tenant']->id)
            ->where('name', 'Student B')
            ->exists();
        $this->assertFalse($hasStudentBInTenant1, 'Student B should NOT belong to tenant 1');

        $hasStudentB = DB::table('waitlists')
            ->where('tenant_id', $result2['tenant']->id)
            ->where('name', 'Student B')
            ->exists();
        $this->assertTrue($hasStudentB, "Student B should belong to tenant {$result2['tenant']->id}. Waitlist records: " . $allWaitlists->toJson());
    }

    public function test_demand_request_is_tenant_isolated(): void
    {
        $result1 = $this->createTenantWithPublishedCourse();
        $result2 = $this->createTenantWithPublishedCourse();

        $this->post(route('growth.demand.store', $result1['profile']->slug), [
            'name' => 'Student A',
            'subject' => 'Math',
        ]);

        $this->post(route('growth.demand.store', $result2['profile']->slug), [
            'name' => 'Student B',
            'subject' => 'English',
        ]);

        $hasMathInTenant1 = DB::table('demand_requests')
            ->where('tenant_id', $result1['tenant']->id)
            ->where('subject', 'Math')
            ->exists();
        $this->assertTrue($hasMathInTenant1, 'Math demand should belong to tenant 1');

        $hasEnglishInTenant1 = DB::table('demand_requests')
            ->where('tenant_id', $result1['tenant']->id)
            ->where('subject', 'English')
            ->exists();
        $this->assertFalse($hasEnglishInTenant1, 'English demand should NOT belong to tenant 1');

        $hasEnglishInTenant2 = DB::table('demand_requests')
            ->where('tenant_id', $result2['tenant']->id)
            ->where('subject', 'English')
            ->exists();
        $this->assertTrue($hasEnglishInTenant2, 'English demand should belong to tenant 2');
    }
}
