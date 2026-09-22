<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\DemandRequest;
use App\Models\Instructor;
use App\Models\PublicProfile;
use App\Models\Tenant;
use App\Models\User;
use App\Services\DemandForecastService;
use App\Services\InsightService;
use App\Services\OpportunityScoringService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GrowthInsightsTest extends TestCase
{
    use RefreshDatabase;

    private function createTeacherWithDemand(): array
    {
        $tenant = $this->createTenant(['domain' => 'growth-insights-' . uniqid()]);

        // Bind tenant context so BelongsToTenant trait can auto-set tenant_id
        app()->instance('tenant', $tenant);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Teacher',
            'email' => 'teacher_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
        ]);

        $instructor = Instructor::create([
            'user_id' => $user->id,
            'name' => 'Test Teacher',
            'email' => 'instructor_' . uniqid() . '@test.com',
            'specialization' => 'Mathematics',
            'status' => 'active',
        ]);

        $profile = PublicProfile::create([
            'profilable_type' => Instructor::class,
            'profilable_id' => $instructor->id,
            'slug' => 'insights-teacher-' . uniqid(),
            'title' => 'Test Teacher',
            'headline' => 'Math Teacher',
            'published' => true,
            'visibility' => ['name' => true],
        ]);

        for ($i = 0; $i < 5; $i++) {
            DemandRequest::create([
                'tenant_id' => $tenant->id,
                'name' => "Student {$i}",
                'subject' => 'Mathematics',
                'level' => 'Grade 12',
                'status' => 'new',
            ]);
        }

        return compact('tenant', 'user', 'instructor', 'profile');
    }

    public function test_insights_page_returns_200(): void
    {
        ['tenant' => $tenant, 'user' => $user] = $this->createTeacherWithDemand();

        $this->actingAs($user);
        app()->instance('tenant', $tenant);

        $response = $this->get(route('growth.insights'));

        $response->assertStatus(200);
        $response->assertSee('Growth Insights');
        $response->assertSee('Recommendations');
        $response->assertSee('Demand Forecast');
        $response->assertSee('Growth Opportunities');
    }

    public function test_insights_requires_auth(): void
    {
        $response = $this->get(route('growth.insights'));

        $response->assertRedirect();
    }

    public function test_insight_service_generates_demand_insights(): void
    {
        $tenant = $this->createTenant(['domain' => 'insight-svc-' . uniqid()]);

        for ($i = 0; $i < 5; $i++) {
            DemandRequest::create([
                'tenant_id' => $tenant->id,
                'name' => "Student {$i}",
                'subject' => 'Physics',
                'status' => 'new',
            ]);
        }

        $service = new InsightService();
        $insights = $service->getInsights($tenant->id);

        $this->assertNotEmpty($insights);

        $demandInsights = array_filter($insights, fn ($i) => $i['type'] === 'demand_opportunity');
        $this->assertNotEmpty($demandInsights);
    }

    public function test_forecast_service_returns_data(): void
    {
        $tenant = $this->createTenant(['domain' => 'forecast-svc-' . uniqid()]);

        $a = DemandRequest::create(['tenant_id' => $tenant->id, 'name' => 'A', 'subject' => 'Math', 'status' => 'new']);
        $a->created_at = now()->subMonths(3)->startOfMonth()->addDays(5);
        $a->saveQuietly();

        $b = DemandRequest::create(['tenant_id' => $tenant->id, 'name' => 'B', 'subject' => 'Math', 'status' => 'new']);
        $b->created_at = now()->subMonths(2)->startOfMonth()->addDays(5);
        $b->saveQuietly();

        $c = DemandRequest::create(['tenant_id' => $tenant->id, 'name' => 'C', 'subject' => 'Math', 'status' => 'new']);
        $c->created_at = now()->subMonths(1)->startOfMonth()->addDays(5);
        $c->saveQuietly();

        $service = new DemandForecastService();
        $forecast = $service->forecast($tenant->id);

        $this->assertTrue($forecast['available']);
        $this->assertArrayHasKey('forecast', $forecast);
        $this->assertCount(3, $forecast['forecast']);
        $this->assertArrayHasKey('projected_demand', $forecast['forecast'][0]);
    }

    public function test_forecast_insufficient_data(): void
    {
        $tenant = $this->createTenant(['domain' => 'forecast-no-' . uniqid()]);

        $service = new DemandForecastService();
        $forecast = $service->forecast($tenant->id);

        $this->assertFalse($forecast['available']);
        $this->assertArrayHasKey('message', $forecast);
    }

    public function test_opportunity_scoring_returns_results(): void
    {
        $tenant = $this->createTenant(['domain' => 'opp-svc-' . uniqid()]);

        for ($i = 0; $i < 3; $i++) {
            DemandRequest::create([
                'tenant_id' => $tenant->id,
                'name' => "Student {$i}",
                'subject' => 'English',
                'status' => 'new',
            ]);
        }

        $service = new OpportunityScoringService();
        $opportunities = $service->getOpportunities($tenant->id);

        $this->assertNotEmpty($opportunities);
        $this->assertArrayHasKey('score', $opportunities[0]);
        $this->assertArrayHasKey('explanation', $opportunities[0]);
        $this->assertArrayHasKey('estimated_revenue', $opportunities[0]);
    }

    public function test_insights_are_tenant_isolated(): void
    {
        $result1 = $this->createTeacherWithDemand();
        $result2 = $this->createTeacherWithDemand();

        $this->actingAs($result1['user']);
        app()->instance('tenant', $result1['tenant']);

        $response = $this->get(route('growth.insights'));

        $response->assertStatus(200);
        $response->assertSee('Mathematics');
    }
}
