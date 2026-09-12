<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\DemandAggregation;
use App\Models\DemandRequest;
use App\Models\Instructor;
use App\Models\Opportunity;
use App\Models\OpportunityGroup;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use App\Services\DemandAggregationService;
use App\Services\GroupFormationService;
use App\Services\OpportunityService;
use App\Services\OpportunityScoringService;
use App\Services\TeacherMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OpportunityTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;
    protected $instructor;
    protected $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = $this->createTenant(['domain' => 'test-' . uniqid()]);
        app()->instance('tenant', $this->tenant);

        $this->user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Teacher',
            'email' => 'teacher_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
        ]);

        $this->instructor = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->user->id,
            'name' => 'Test Teacher',
            'email' => 'instructor_' . uniqid() . '@test.com',
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

    public function test_opportunity_can_be_created_from_aggregation(): void
    {
        $aggregation = DemandAggregation::create([
            'tenant_id' => $this->tenant->id,
            'subject' => 'Mathematics',
            'level' => 'High School',
            'demand_count' => 10,
            'status' => 'completed',
            'metadata' => ['locations' => ['Cairo']],
        ]);

        $service = app(OpportunityService::class);
        $opportunity = $service->createFromAggregation($aggregation);

        $this->assertNotNull($opportunity);
        $this->assertEquals($this->tenant->id, $opportunity->tenant_id);
        $this->assertEquals($aggregation->id, $opportunity->demand_aggregation_id);
        $this->assertEquals('Mathematics', $opportunity->subject);
        $this->assertEquals('High School', $opportunity->level);
        $this->assertEquals('open', $opportunity->status);
        $this->assertEquals(10, $opportunity->demand_volume);
        $this->assertNotNull($opportunity->explanation);
    }

    public function test_opportunity_can_be_created_from_demand_request(): void
    {
        $demand = DemandRequest::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'John Doe',
            'phone' => '01012345678',
            'email' => 'john@example.com',
            'subject' => 'Physics',
            'level' => 'High School',
            'preferred_days' => 'Mon,Wed,Fri',
            'preferred_time' => '10:00-12:00',
            'delivery_mode' => 'offline',
            'location' => 'Cairo, Egypt',
            'status' => 'new',
        ]);

        $service = app(OpportunityService::class);
        $opportunity = $service->createFromDemandRequest($demand);

        $this->assertNotNull($opportunity);
        $this->assertEquals('Physics', $opportunity->subject);
        $this->assertEquals('open', $opportunity->status);
    }

    public function test_opportunity_lifecycle(): void
    {
        $opportunity = Opportunity::create([
            'tenant_id' => $this->tenant->id,
            'subject' => 'Chemistry',
            'level' => 'High School',
            'title' => 'Chemistry Opportunity',
            'status' => 'open',
            'demand_volume' => 5,
            'score' => 75.5,
            'demand_aggregation_id' => null,
        ]);

        $this->assertTrue($opportunity->isOpen());

        $opportunity->markAsMatched($this->instructor->id, $this->course->id);
        $opportunity->refresh();
        $this->assertTrue($opportunity->isMatched());
        $this->assertEquals($this->instructor->id, $opportunity->matched_teacher_id);
        $this->assertEquals($this->course->id, $opportunity->matched_course_id);

        $opportunity->startGroupFormation();
        $opportunity->refresh();
        $this->assertTrue($opportunity->isGroupForming());

        $opportunity->completeGroupFormation();
        $opportunity->refresh();
        $this->assertTrue($opportunity->isGroupFormed());

        $opportunity->markAsFilled();
        $opportunity->refresh();
        $this->assertTrue($opportunity->isFilled());
    }

    public function test_opportunity_scoring(): void
    {
        $aggregation = DemandAggregation::create([
            'tenant_id' => $this->tenant->id,
            'subject' => 'Biology',
            'level' => 'High School',
            'demand_count' => 15,
            'status' => 'completed',
            'metadata' => [
                'locations' => ['Cairo', 'Giza'],
                'preferred_days' => ['Mon', 'Wed'],
                'delivery_modes' => ['online', 'offline'],
            ],
        ]);

        $scoringService = app(OpportunityScoringService::class);
        $score = $scoringService->scoreOpportunity($this->tenant->id, 'Biology', 'High School');

        $this->assertArrayHasKey('total', $score);
        $this->assertArrayHasKey('breakdown', $score);
        $this->assertGreaterThanOrEqual(0, $score['total']);
        $this->assertLessThanOrEqual(100, $score['total']);
    }

    public function test_opportunity_can_be_accepted_by_teacher(): void
    {
        $opportunity = Opportunity::create([
            'tenant_id' => $this->tenant->id,
            'subject' => 'Mathematics',
            'level' => 'High School',
            'title' => 'Mathematics Opportunity',
            'status' => 'open',
            'demand_volume' => 8,
            'score' => 80,
            'demand_aggregation_id' => null,
        ]);

        $service = app(OpportunityService::class);
        $result = $service->acceptOpportunity($opportunity->id, $this->instructor->id);

        $this->assertEquals('matched', $result->status);
        $this->assertEquals($this->instructor->id, $result->matched_teacher_id);
        $this->assertNotNull($result->matched_course_id);
        $this->assertNotNull($result->matched_at);
    }

    public function test_opportunity_cannot_be_accepted_twice(): void
    {
        $opportunity = Opportunity::create([
            'tenant_id' => $this->tenant->id,
            'subject' => 'Physics',
            'level' => 'High School',
            'title' => 'Physics Opportunity',
            'status' => 'matched',
            'demand_volume' => 8,
            'score' => 80,
            'matched_teacher_id' => $this->instructor->id,
            'matched_course_id' => $this->course->id,
            'matched_at' => now(),
        ]);

        $service = app(OpportunityService::class);
        $this->expectException(\InvalidArgumentException::class);
        $service->acceptOpportunity($opportunity->id, $this->instructor->id);
    }

    public function test_opportunity_can_be_declined(): void
    {
        $opportunity = Opportunity::create([
            'tenant_id' => $this->tenant->id,
            'subject' => 'History',
            'level' => 'Middle School',
            'title' => 'History Opportunity',
            'status' => 'open',
            'demand_volume' => 3,
            'score' => 50,
        ]);

        $service = app(OpportunityService::class);
        $result = $service->declineOpportunity($opportunity->id, $this->instructor->id, 'Not interested');

        $this->assertNotNull($result);
    }

    public function test_group_formation_lifecycle(): void
    {
        $opportunity = Opportunity::create([
            'tenant_id' => $this->tenant->id,
            'subject' => 'English',
            'level' => 'Elementary',
            'title' => 'English Opportunity',
            'status' => 'matched',
            'demand_volume' => 12,
            'score' => 85,
            'matched_teacher_id' => $this->instructor->id,
            'matched_course_id' => $this->course->id,
            'matched_at' => now(),
        ]);

        $groupService = app(GroupFormationService::class);

        $group = $groupService->createGroup($opportunity, [
            'course_id' => $this->course->id,
        ]);

        $this->assertNotNull($group);
        $this->assertEquals('forming', $group->status);
        $this->assertEquals(0, $group->student_count);

        $student = Student::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => User::create([
                'tenant_id' => $this->tenant->id,
                'name' => 'Student 1',
                'email' => 'student1@test.com',
                'password' => Hash::make('password'),
                'role' => 'student',
            ])->id,
            'name' => 'Student 1',
            'email' => 'student1@test.com',
            'status' => 'active',
        ]);

        $enrollment = $groupService->addStudentToGroup($group, $student->id, $this->instructor->id);

        $group->refresh();
        $this->assertEquals(1, $group->student_count);
        $this->assertEquals('active', $enrollment->status);

        $groupService->completeGroup($group);
        $group->refresh();
        $this->assertEquals('filled', $group->status);
    }

    public function test_group_formation_prevents_overcapacity(): void
    {
        $smallCourse = Course::create([
            'tenant_id' => $this->tenant->id,
            'instructor_id' => $this->instructor->id,
            'title' => 'Small Math',
            'subject' => 'Mathematics',
            'capacity' => 2,
            'published' => true,
            'status' => 'active',
        ]);

        $opportunity = Opportunity::create([
            'tenant_id' => $this->tenant->id,
            'subject' => 'Mathematics',
            'level' => 'High School',
            'title' => 'Math Opportunity',
            'status' => 'matched',
            'demand_volume' => 5,
            'score' => 90,
            'matched_teacher_id' => $this->instructor->id,
            'matched_course_id' => $smallCourse->id,
            'matched_at' => now(),
        ]);

        $groupService = app(GroupFormationService::class);
        $group = $groupService->createGroup($opportunity, [
            'course_id' => $smallCourse->id,
        ]);

        $student1 = Student::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => User::create([
                'tenant_id' => $this->tenant->id,
                'name' => 'Student 1',
                'email' => 's1@test.com',
                'password' => Hash::make('password'),
                'role' => 'student',
            ])->id,
            'name' => 'Student 1',
            'email' => 's1@test.com',
            'status' => 'active',
        ]);

        $student2 = Student::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => User::create([
                'tenant_id' => $this->tenant->id,
                'name' => 'Student 2',
                'email' => 's2@test.com',
                'password' => Hash::make('password'),
                'role' => 'student',
            ])->id,
            'name' => 'Student 2',
            'email' => 's2@test.com',
            'status' => 'active',
        ]);

        $student3 = Student::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => User::create([
                'tenant_id' => $this->tenant->id,
                'name' => 'Student 3',
                'email' => 's3@test.com',
                'password' => Hash::make('password'),
                'role' => 'student',
            ])->id,
            'name' => 'Student 3',
            'email' => 's3@test.com',
            'status' => 'active',
        ]);

        $groupService->addStudentToGroup($group, $student1->id, $this->instructor->id);
        $groupService->addStudentToGroup($group, $student2->id, $this->instructor->id);

        $group->refresh();
        $this->assertTrue($group->isFull());

        $this->expectException(\InvalidArgumentException::class);
        $groupService->addStudentToGroup($group, $student3->id, $this->instructor->id);
    }

    public function test_demand_aggregation_service(): void
    {
        DemandRequest::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student A',
            'phone' => '01011111111',
            'email' => 'studenta@test.com',
            'subject' => 'Mathematics',
            'level' => 'High School',
            'status' => 'new',
        ]);

        DemandRequest::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student B',
            'phone' => '01022222222',
            'email' => 'studentb@test.com',
            'subject' => 'Mathematics',
            'level' => 'High School',
            'status' => 'new',
        ]);

        $service = app(DemandAggregationService::class);
        $count = $service->aggregateForTenant($this->tenant->id);

        $this->assertEquals(1, $count);

        $aggregation = DemandAggregation::where('tenant_id', $this->tenant->id)
            ->where('subject', 'Mathematics')
            ->first();

        $this->assertNotNull($aggregation);
        $this->assertEquals(2, $aggregation->demand_count);
        $this->assertEquals('completed', $aggregation->status);
    }

    public function test_aggregation_counters_are_cumulative_across_batches(): void
    {
        foreach (['A', 'B'] as $suffix) {
            DemandRequest::create([
                'tenant_id' => $this->tenant->id,
                'name' => 'Student ' . $suffix,
                'phone' => '01000000' . $suffix,
                'email' => 'student' . $suffix . '@test.com',
                'subject' => 'Mathematics',
                'level' => 'High School',
                'location' => 'Cairo',
                'status' => 'new',
            ]);
        }

        $service = app(DemandAggregationService::class);
        $service->aggregateForTenant($this->tenant->id);

        DemandRequest::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student C',
            'phone' => '01000000C',
            'email' => 'studentc@test.com',
            'subject' => 'Mathematics',
            'level' => 'High School',
            'location' => 'Giza',
            'status' => 'new',
        ]);

        $service->aggregateForTenant($this->tenant->id);

        $aggregation = DemandAggregation::where('tenant_id', $this->tenant->id)
            ->where('subject', 'Mathematics')
            ->first();

        // Cumulative pending demand (2 contacted + 1 contacted), history merged
        $this->assertEquals(3, $aggregation->demand_count);
        $this->assertCount(3, $aggregation->metadata['demand_ids']);
        $this->assertContains('Cairo', $aggregation->metadata['locations']);
        $this->assertContains('Giza', $aggregation->metadata['locations']);

        $opportunity = \App\Models\Opportunity::where('demand_aggregation_id', $aggregation->id)
            ->whereIn('status', ['open', 'matched', 'group_forming', 'group_formed'])
            ->first();
        $this->assertEquals(3, $opportunity->demand_volume);
    }

    public function test_opportunity_deletion_preserves_enrollments(): void
    {
        $opportunity = Opportunity::create([
            'tenant_id' => $this->tenant->id,
            'subject' => 'Mathematics',
            'level' => 'High School',
            'title' => 'Math Opportunity',
            'status' => 'matched',
            'demand_volume' => 2,
            'score' => 90,
            'matched_teacher_id' => $this->instructor->id,
            'matched_course_id' => $this->course->id,
            'matched_at' => now(),
        ]);

        $groupService = app(GroupFormationService::class);
        $group = $groupService->createGroup($opportunity, ['course_id' => $this->course->id]);

        $student = Student::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => User::create([
                'tenant_id' => $this->tenant->id,
                'name' => 'Doomed Student',
                'email' => 'doomed@test.com',
                'password' => Hash::make('password'),
                'role' => 'student',
            ])->id,
            'name' => 'Doomed Student',
            'email' => 'doomed@test.com',
            'status' => 'active',
        ]);

        $enrollment = $groupService->addStudentToGroup($group, $student->id, $this->instructor->id);

        $opportunity->forceDelete();

        // Scaffolding gone…
        $this->assertNull(OpportunityGroup::withoutGlobalScopes()->find($group->id));
        // …but the course enrollment, counters and evidence survive.
        $this->assertEquals('active', $enrollment->fresh()->status);
        $this->assertEquals(1, $this->course->fresh()->enrolled_count);
    }
}