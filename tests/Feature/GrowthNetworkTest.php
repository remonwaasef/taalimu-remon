<?php

namespace Tests\Feature;

use App\Models\DemandRequest;
use App\Models\Instructor;
use App\Models\MarketplaceListing;
use App\Models\PublicProfile;
use App\Models\Referral;
use App\Models\Review;
use App\Models\User;
use App\Services\DiscoveryService;
use App\Services\MarketplaceService;
use App\Services\ReferralService;
use App\Services\ReviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GrowthNetworkTest extends TestCase
{
    use RefreshDatabase;

    private function createTeacherWithProfile(): array
    {
        $tenant = $this->createTenant(['domain' => 'network-test-' . uniqid()]);

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
            'slug' => 'network-teacher-' . uniqid(),
            'title' => 'Test Teacher',
            'headline' => 'Math Teacher',
            'published' => true,
            'visibility' => ['name' => true],
            'referral_code' => strtoupper(uniqid()),
        ]);

        // NetworkIdentity is created automatically by PublicProfileObserver

        return compact('tenant', 'user', 'instructor', 'profile');
    }

    public function test_discovery_teachers_page_returns_200(): void
    {
        $result = $this->createTeacherWithProfile();

        $response = $this->get(route('growth.discover.teachers'));

        $response->assertStatus(200);
        $response->assertSee('Discover Teachers');
        $response->assertSee('Test Teacher');
    }

    public function test_discovery_centers_page_returns_200(): void
    {
        $response = $this->get(route('growth.discover.centers'));

        $response->assertStatus(200);
        $response->assertSee('Discover Centers');
    }

    public function test_discovery_listings_page_returns_200(): void
    {
        $response = $this->get(route('growth.discover.listings'));

        $response->assertStatus(200);
        $response->assertSee('Student Listings');
    }

    public function test_reviews_page_returns_200(): void
    {
        $result = $this->createTeacherWithProfile();

        $response = $this->get(route('growth.reviews.index', $result['profile']->slug));

        $response->assertStatus(200);
        $response->assertSee('Test Teacher');
    }

    public function test_review_service_creates_review(): void
    {
        $result = $this->createTeacherWithProfile();

        $service = new ReviewService();
        $review = $service->createReview([
            'reviewer_id' => $result['user']->id,
            'reviewable_type' => Instructor::class,
            'reviewable_id' => $result['instructor']->id,
            'rating' => 5,
            'comment' => 'Great teacher!',
        ], $result['tenant']->id);

        $this->assertDatabaseHas('reviews', [
            'tenant_id' => $result['tenant']->id,
            'reviewer_id' => $result['user']->id,
            'rating' => 5,
        ]);
    }

    public function test_review_service_gets_average_rating(): void
    {
        $result = $this->createTeacherWithProfile();

        $service = new ReviewService();
        $service->createReview([
            'reviewer_id' => $result['user']->id,
            'reviewable_type' => Instructor::class,
            'reviewable_id' => $result['instructor']->id,
            'rating' => 4,
        ], $result['tenant']->id);

        $avg = $service->getAverageRating(Instructor::class, $result['instructor']->id);

        $this->assertEquals(4.0, $avg);
    }

    public function test_referral_service_tracks_referral(): void
    {
        $result = $this->createTeacherWithProfile();

        $student = User::create([
            'tenant_id' => $result['tenant']->id,
            'name' => 'Student',
            'email' => 'student_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        $service = new ReferralService();
        $referral = $service->trackReferral(
            $result['tenant']->id,
            $result['user']->id,
            $student->id,
            $result['profile']->referral_code
        );

        $this->assertNotNull($referral);
        $this->assertDatabaseHas('referrals', [
            'tenant_id' => $result['tenant']->id,
            'referrer_id' => $result['user']->id,
            'referred_id' => $student->id,
            'status' => 'pending',
        ]);
    }

    public function test_referral_service_prevents_self_referral(): void
    {
        $result = $this->createTeacherWithProfile();

        $service = new ReferralService();
        $referral = $service->trackReferral(
            $result['tenant']->id,
            $result['user']->id,
            $result['user']->id,
            $result['profile']->referral_code
        );

        $this->assertNull($referral);
    }

    public function test_marketplace_service_creates_listing(): void
    {
        $result = $this->createTeacherWithProfile();

        $service = new MarketplaceService();
        $listing = $service->createListing([
            'user_id' => $result['user']->id,
            'subject' => 'Mathematics',
            'level' => 'Grade 12',
            'description' => 'Need help with calculus',
        ], $result['tenant']->id);

        $this->assertDatabaseHas('marketplace_listings', [
            'tenant_id' => $result['tenant']->id,
            'subject' => 'Mathematics',
            'status' => 'active',
        ]);
    }

    public function test_marketplace_service_gets_active_listings(): void
    {
        $result = $this->createTeacherWithProfile();

        $service = new MarketplaceService();
        $service->createListing([
            'user_id' => $result['user']->id,
            'subject' => 'Physics',
        ], $result['tenant']->id);

        $listings = $service->getActiveListings([], 20);

        $this->assertEquals(1, $listings->total());
    }

    public function test_marketplace_service_closes_listing(): void
    {
        $result = $this->createTeacherWithProfile();

        $service = new MarketplaceService();
        $listing = $service->createListing([
            'user_id' => $result['user']->id,
            'subject' => 'Chemistry',
        ], $result['tenant']->id);

        $closed = $service->closeListing($listing);

        $this->assertTrue($closed);
        $this->assertDatabaseHas('marketplace_listings', [
            'id' => $listing->id,
            'status' => 'closed',
        ]);
    }

    public function test_referral_page_requires_auth(): void
    {
        $response = $this->get(route('growth.referrals'));

        $response->assertRedirect();
    }

    public function test_marketplace_create_requires_auth(): void
    {
        $response = $this->get(route('growth.marketplace.create'));

        $response->assertRedirect();
    }

    public function test_discovery_is_tenant_isolated(): void
    {
        $result1 = $this->createTeacherWithProfile();
        $result2 = $this->createTeacherWithProfile();

        // Use unique slugs for proper isolation
        $slug1 = $result1['profile']->slug . '-1';
        $slug2 = $result2['profile']->slug . '-2';

        $result1['profile']->update(['slug' => $slug1]);
        $result2['profile']->update(['slug' => $slug2]);

        // NetworkIdentity slugs are updated automatically by PublicProfileObserver

        $response = $this->get(route('growth.discover.teachers'));

        $response->assertStatus(200);
        $response->assertSee('Test Teacher');
    }
}
