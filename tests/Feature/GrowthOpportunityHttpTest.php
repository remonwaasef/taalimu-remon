<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\DemandAttribution;
use App\Models\DemandRequest;
use App\Models\Enrollment;
use App\Models\GrowthEvent;
use App\Models\Instructor;
use App\Models\Opportunity;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GrowthOpportunityHttpTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected User $teacherUser;
    protected Instructor $instructor;
    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = $this->createTenant(['domain' => 'http-' . uniqid()]);
        app()->instance('tenant', $this->tenant);

        $this->teacherUser = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Http Teacher',
            'email' => 'http_teacher_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
        ]);

        $this->instructor = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->teacherUser->id,
            'name' => 'Http Teacher',
            'email' => 'http_instructor_' . uniqid() . '@test.com',
            'specialization' => 'Mathematics',
            'status' => 'active',
        ]);

        $this->course = Course::create([
            'tenant_id' => $this->tenant->id,
            'instructor_id' => $this->instructor->id,
            'title' => 'Mathematics 101',
            'subject' => 'Mathematics',
            'capacity' => 20,
            'published' => true,
            'status' => 'active',
        ]);
    }

    protected function makeOpportunity(array $overrides = []): Opportunity
    {
        return Opportunity::create(array_merge([
            'tenant_id' => $this->tenant->id,
            'subject' => 'Mathematics',
            'level' => 'High School',
            'title' => 'Mathematics Opportunity',
            'status' => 'open',
            'demand_volume' => 8,
            'score' => 80,
        ], $overrides));
    }

    protected function makeStudent(string $name = 'Http Student'): Student
    {
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => $name,
            'email' => strtolower(str_replace(' ', '.', $name)) . '_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        return Student::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $user->id,
            'name' => $name,
            'email' => $user->email,
            'status' => 'active',
        ]);
    }

    protected function makeForeignOpportunity(): Opportunity
    {
        app()->forgetInstance('tenant');
        $other = $this->createTenant(['domain' => 'foreign-' . uniqid()]);
        $opp = Opportunity::create([
            'tenant_id' => $other->id,
            'subject' => 'Mathematics',
            'level' => 'High School',
            'title' => 'Foreign Opportunity',
            'status' => 'open',
            'demand_volume' => 4,
            'score' => 60,
        ]);
        app()->instance('tenant', $this->tenant);

        return $opp;
    }

    public function test_guests_are_redirected_to_login(): void
    {
        foreach (['growth.matching.index', 'growth.opportunities.index'] as $route) {
            $response = $this->get(route($route));
            $response->assertRedirect();
            $this->assertStringEndsWith('/login', parse_url($response->headers->get('Location'), PHP_URL_PATH));
        }
    }

    public function test_instructor_sees_matching_feed(): void
    {
        $this->makeOpportunity();

        $response = $this->actingAs($this->teacherUser)->get(route('growth.matching.index'));

        $response->assertStatus(200);
        $response->assertSee('Mathematics Opportunity');
    }

    public function test_instructor_sees_opportunities_index_and_create(): void
    {
        $this->makeOpportunity();

        $this->actingAs($this->teacherUser)
            ->get(route('growth.opportunities.index'))
            ->assertStatus(200)
            ->assertSee('Mathematics Opportunity');

        $this->actingAs($this->teacherUser)
            ->get(route('growth.opportunities.create'))
            ->assertStatus(200);
    }

    public function test_matching_show_rejects_foreign_tenant(): void
    {
        $foreign = $this->makeForeignOpportunity();

        // Tenant-scoped route binding hides foreign records (404): no
        // existence leak across tenants.
        $this->actingAs($this->teacherUser)
            ->get(route('growth.matching.show', $foreign))
            ->assertNotFound();
    }

    public function test_accept_transitions_open_to_matched(): void
    {
        $opp = $this->makeOpportunity();

        $response = $this->actingAs($this->teacherUser)
            ->from(route('growth.matching.show', $opp))
            ->post(route('growth.matching.accept', $opp));

        $response->assertRedirect();
        $this->assertEquals('matched', $opp->fresh()->status);
        $this->assertEquals($this->instructor->id, $opp->fresh()->matched_teacher_id);
    }

    public function test_accept_twice_is_rejected(): void
    {
        $opp = $this->makeOpportunity();

        $this->actingAs($this->teacherUser)->post(route('growth.matching.accept', $opp));

        $this->actingAs($this->teacherUser)
            ->from(route('growth.matching.show', $opp))
            ->post(route('growth.matching.accept', $opp))
            ->assertStatus(400);
    }

    public function test_non_instructor_cannot_accept(): void
    {
        $opp = $this->makeOpportunity();
        $plain = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Plain',
            'email' => 'plain_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'parent',
        ]);

        $this->actingAs($plain)
            ->post(route('growth.matching.accept', $opp))
            ->assertForbidden();
    }

    public function test_cross_tenant_accept_is_forbidden(): void
    {
        $foreign = $this->makeForeignOpportunity();

        // Resolves at binding level: foreign IDs 404 before any logic runs.
        $this->actingAs($this->teacherUser)
            ->post(route('growth.matching.accept', $foreign))
            ->assertNotFound();

        $untouched = Opportunity::withoutGlobalScopes()->find($foreign->id);
        $this->assertEquals('open', $untouched->status);
        $this->assertNull($untouched->matched_teacher_id);
    }

    public function test_decline_records_growth_event(): void
    {
        $opp = $this->makeOpportunity();

        $response = $this->actingAs($this->teacherUser)
            ->from(route('growth.matching.show', $opp))
            ->post(route('growth.matching.decline', $opp), ['reason' => 'Too far']);

        $response->assertRedirect();
        $this->assertEquals('open', $opp->fresh()->status);
        $this->assertTrue(
            GrowthEvent::where('tenant_id', $this->tenant->id)
                ->where('event_name', 'opportunity_declined')
                ->exists()
        );
    }

    public function test_group_formation_lifecycle_over_http(): void
    {
        $opp = $this->makeOpportunity();
        $student = $this->makeStudent();

        // Accept
        $this->actingAs($this->teacherUser)->post(route('growth.matching.accept', $opp));
        $this->assertEquals('matched', $opp->fresh()->status);

        // Show renders
        $this->actingAs($this->teacherUser)
            ->get(route('growth.opportunities.show', $opp))
            ->assertStatus(200)
            ->assertSee('Mathematics Opportunity');

        // Create group
        $response = $this->actingAs($this->teacherUser)
            ->from(route('growth.opportunities.show', $opp))
            ->post(route('growth.opportunities.groups.store', $opp), ['course_id' => $this->course->id]);
        $response->assertRedirect();
        $this->assertEquals('group_forming', $opp->fresh()->status);

        $group = $opp->fresh()->groups()->first();
        $this->assertNotNull($group);

        // Show group renders with student dropdown
        $this->actingAs($this->teacherUser)
            ->get(route('growth.opportunities.groups.show', [$opp, $group]))
            ->assertStatus(200)
            ->assertSee($student->name);

        // Add student
        $add = $this->actingAs($this->teacherUser)
            ->from(route('growth.opportunities.groups.show', [$opp, $group]))
            ->post(route('growth.opportunities.groups.students.store', [$opp, $group]), ['student_id' => $student->id]);
        $add->assertRedirect();

        $enrollment = Enrollment::where('tenant_id', $this->tenant->id)
            ->where('course_id', $this->course->id)
            ->where('user_id', $student->user_id)
            ->first();
        $this->assertNotNull($enrollment);
        $this->assertEquals('growth_opportunity', $enrollment->source);
        $this->assertEquals('opportunity:' . $opp->id, $enrollment->campaign);
        $this->assertEquals(1, $this->course->fresh()->enrolled_count);

        // Duplicate add rejected with errors
        $this->actingAs($this->teacherUser)
            ->from(route('growth.opportunities.groups.show', [$opp, $group]))
            ->post(route('growth.opportunities.groups.students.store', [$opp, $group]), ['student_id' => $student->id])
            ->assertRedirect()
            ->assertSessionHasErrors();

        // Remove student
        $this->actingAs($this->teacherUser)
            ->from(route('growth.opportunities.groups.show', [$opp, $group]))
            ->delete(route('growth.opportunities.groups.students.destroy', [$opp, $group, $student->id]))
            ->assertRedirect();
        $this->assertEquals('cancelled', $enrollment->fresh()->status);
        $this->assertEquals(0, $this->course->fresh()->enrolled_count);

        // Re-add then complete group
        $this->actingAs($this->teacherUser)
            ->post(route('growth.opportunities.groups.students.store', [$opp, $group]), ['student_id' => $student->id]);

        $this->actingAs($this->teacherUser)
            ->from(route('growth.opportunities.groups.show', [$opp, $group]))
            ->post(route('growth.opportunities.groups.complete', [$opp, $group]))
            ->assertRedirect();

        $this->assertEquals('filled', $group->fresh()->status);
        $this->assertContains($opp->fresh()->status, ['group_formed', 'filled']);
    }

    public function test_group_overcapacity_rejected(): void
    {
        $small = Course::create([
            'tenant_id' => $this->tenant->id,
            'instructor_id' => $this->instructor->id,
            'title' => 'Tiny Math',
            'subject' => 'Mathematics',
            'capacity' => 1,
            'published' => true,
            'status' => 'active',
        ]);
        $opp = $this->makeOpportunity(['matched_teacher_id' => $this->instructor->id, 'matched_course_id' => $small->id, 'status' => 'matched', 'matched_at' => now()]);

        $this->actingAs($this->teacherUser)
            ->post(route('growth.opportunities.groups.store', $opp), ['course_id' => $small->id]);

        $group = $opp->fresh()->groups()->first();
        $s1 = $this->makeStudent('Cap One');
        $s2 = $this->makeStudent('Cap Two');

        $this->actingAs($this->teacherUser)
            ->post(route('growth.opportunities.groups.students.store', [$opp, $group]), ['student_id' => $s1->id])
            ->assertRedirect();
        $this->assertTrue($group->fresh()->isFull());

        $this->actingAs($this->teacherUser)
            ->from(route('growth.opportunities.groups.show', [$opp, $group]))
            ->post(route('growth.opportunities.groups.students.store', [$opp, $group]), ['student_id' => $s2->id])
            ->assertStatus(400);
    }

    public function test_only_matched_teacher_manages_group(): void
    {
        $opp = $this->makeOpportunity();

        app()->forgetInstance('tenant');
        $otherUser = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Other',
            'email' => 'other_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
        ]);
        $otherInstructor = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $otherUser->id,
            'name' => 'Other',
            'email' => 'other_i_' . uniqid() . '@test.com',
            'specialization' => 'Physics',
            'status' => 'active',
        ]);
        app()->instance('tenant', $this->tenant);

        $this->actingAs($this->teacherUser)->post(route('growth.matching.accept', $opp));

        // Other teacher cannot start group formation for someone else's match
        $this->actingAs($otherUser)
            ->post(route('growth.opportunities.start-group', $opp))
            ->assertForbidden();

        // Matched teacher can
        $this->actingAs($this->teacherUser)
            ->from(route('growth.opportunities.show', $opp))
            ->post(route('growth.opportunities.start-group', $opp))
            ->assertRedirect();
        $this->assertEquals('group_forming', $opp->fresh()->status);
    }

    public function test_enrollment_converts_only_matching_demand(): void
    {
        $student = $this->makeStudent('Convert Me');

        $matching = DemandRequest::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Convert Me',
            'phone' => '01099999999',
            'email' => $student->email,
            'subject' => 'Mathematics',
            'level' => 'High School',
            'status' => 'new',
        ]);
        $unrelated = DemandRequest::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Someone Else',
            'phone' => '01000000000',
            'email' => 'elsewhere_' . uniqid() . '@test.com',
            'subject' => 'Mathematics',
            'level' => 'High School',
            'status' => 'new',
        ]);

        $opp = $this->makeOpportunity();
        $this->actingAs($this->teacherUser)->post(route('growth.matching.accept', $opp));
        $this->actingAs($this->teacherUser)
            ->post(route('growth.opportunities.groups.store', $opp), ['course_id' => $this->course->id]);
        $group = $opp->fresh()->groups()->first();

        $this->actingAs($this->teacherUser)
            ->post(route('growth.opportunities.groups.students.store', [$opp, $group]), ['student_id' => $student->id])
            ->assertRedirect();

        $this->assertEquals('converted', $matching->fresh()->status);
        $this->assertNotNull($matching->fresh()->converted_at);
        $this->assertEquals($this->course->id, $matching->fresh()->course_id);

        // Unrelated demand untouched — no bulk conversion
        $this->assertEquals('new', $unrelated->fresh()->status);

        $attribution = DemandAttribution::where('tenant_id', $this->tenant->id)
            ->where('demand_request_id', $matching->id)
            ->first();
        $this->assertNotNull($attribution);
        $this->assertEquals($opp->id, $attribution->opportunity_id);
        $this->assertNotNull($attribution->enrollment_id);

        // Opportunity page shows real conversion numbers (Arabic UI by default)
        $this->actingAs($this->teacherUser)
            ->get(route('growth.opportunities.show', $opp))
            ->assertStatus(200)
            ->assertSee('معدل التحويل');
    }

    public function test_group_completion_prompts_reviews_and_links_evidence(): void
    {
        $profile = \App\Models\PublicProfile::create([
            'tenant_id' => $this->tenant->id,
            'profilable_type' => Instructor::class,
            'profilable_id' => $this->instructor->id,
            'slug' => 'http-teacher-' . uniqid(),
            'title' => 'Http Teacher',
            'published' => true,
        ]);

        $opp = $this->makeOpportunity();
        $student = $this->makeStudent('Review Me');

        $this->actingAs($this->teacherUser)->post(route('growth.matching.accept', $opp));
        $this->actingAs($this->teacherUser)
            ->post(route('growth.opportunities.groups.store', $opp), ['course_id' => $this->course->id]);
        $group = $opp->fresh()->groups()->first();

        $this->actingAs($this->teacherUser)
            ->post(route('growth.opportunities.groups.students.store', [$opp, $group]), ['student_id' => $student->id]);

        $this->actingAs($this->teacherUser)
            ->post(route('growth.opportunities.groups.complete', [$opp, $group]))
            ->assertRedirect();

        $enrollment = Enrollment::where('tenant_id', $this->tenant->id)
            ->where('course_id', $this->course->id)
            ->where('user_id', $student->user_id)
            ->first();

        // Every placed student gets a review prompt with evidence context
        $prompt = \App\Models\TeacherNotification::where('tenant_id', $this->tenant->id)
            ->where('user_id', $student->user_id)
            ->where('type', 'review_requested')
            ->first();
        $this->assertNotNull($prompt);
        $this->assertEquals($enrollment->id, $prompt->data['enrollment_id']);
        $this->assertStringContainsString($profile->slug, $prompt->data['review_url']);

        // The prompt converts into a verified, enrollment-linked review
        $studentUser = User::find($student->user_id);
        $this->actingAs($studentUser)
            ->from(route('growth.reviews.index', $profile->slug))
            ->post(route('growth.reviews.store', $profile->slug), [
                'rating' => 5,
                'comment' => 'Excellent teacher',
                'enrollment_id' => $enrollment->id,
            ])
            ->assertRedirect();

        $review = \App\Models\Review::where('tenant_id', $this->tenant->id)
            ->where('reviewer_id', $studentUser->id)
            ->first();
        $this->assertNotNull($review);
        $this->assertEquals($enrollment->id, $review->enrollment_id);
        $this->assertTrue((bool) $review->verified);
    }

    public function test_group_page_shows_taalimu_source(): void
    {
        $opp = $this->makeOpportunity();
        $student = $this->makeStudent('Sourced Sam');

        $this->actingAs($this->teacherUser)->post(route('growth.matching.accept', $opp));
        $this->actingAs($this->teacherUser)
            ->post(route('growth.opportunities.groups.store', $opp), ['course_id' => $this->course->id]);
        $group = $opp->fresh()->groups()->first();
        $this->actingAs($this->teacherUser)
            ->post(route('growth.opportunities.groups.students.store', [$opp, $group]), ['student_id' => $student->id]);

        $this->actingAs($this->teacherUser)
            ->get(route('growth.opportunities.groups.show', [$opp, $group]))
            ->assertStatus(200)
            ->assertSee('تعليمو');
    }

    public function test_growth_maintain_command_expires_and_aggregates(): void
    {
        $expiring = $this->makeOpportunity(['expires_at' => now()->subDay()]);

        DemandRequest::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Cron Demand',
            'phone' => '01077777777',
            'subject' => 'Physics',
            'level' => 'High School',
            'status' => 'new',
        ]);

        $this->artisan('growth:maintain')->assertSuccessful();

        $this->assertEquals('expired', $expiring->fresh()->status);
        $this->assertTrue(
            Opportunity::where('tenant_id', $this->tenant->id)->where('subject', 'Physics')->exists()
        );
    }

    public function test_teacher_pages_render(): void
    {
        $opp = $this->makeOpportunity();

        // matching.show only serves open opportunities by design
        $this->actingAs($this->teacherUser)->get(route('growth.matching.show', $opp))->assertStatus(200);

        $this->actingAs($this->teacherUser)->post(route('growth.matching.accept', $opp));

        $this->actingAs($this->teacherUser)->get(route('growth.matching.matched'))->assertStatus(200);
        $this->actingAs($this->teacherUser)->get(route('growth.matching.group-forming'))->assertStatus(200);
        $this->actingAs($this->teacherUser)->get(route('growth.matching.group-formed'))->assertStatus(200);
        $this->actingAs($this->teacherUser)->get(route('growth.matching.stats'))->assertStatus(200);
    }

    public function test_store_generates_opportunities_from_demand(): void
    {
        DemandRequest::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Store Demand',
            'phone' => '01012345678',
            'subject' => 'Mathematics',
            'level' => 'High School',
            'status' => 'new',
        ]);

        $this->actingAs($this->teacherUser)
            ->from(route('growth.opportunities.create'))
            ->post(route('growth.opportunities.store'), ['subject' => 'Mathematics'])
            ->assertRedirect(route('growth.opportunities.index'));

        $this->assertTrue(
            Opportunity::where('tenant_id', $this->tenant->id)->where('subject', 'Mathematics')->exists()
        );
    }

    public function test_store_rejects_subject_without_demand(): void
    {
        $this->actingAs($this->teacherUser)
            ->from(route('growth.opportunities.create'))
            ->post(route('growth.opportunities.store'), ['subject' => 'NoSuchSubject'])
            ->assertRedirect()
            ->assertSessionHasErrors('subject');
    }
}
