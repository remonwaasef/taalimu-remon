<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\TrialExpiredNotification;
use App\Services\PaymentProcessingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TrialExpirationAndPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected User $admin;
    protected Package $package;
    protected Subscription $subscription;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = $this->createTenant([
            'domain' => 'center-test',
            'name' => 'مركز الاختبار التعليمي',
        ]);
        app()->instance('tenant', $this->tenant);

        $this->package = Package::create([
            'name' => 'باقة النمو',
            'name_en' => 'Growth Plan',
            'slug' => 'pro',
            'price' => 950.00,
            'term_price' => 3450.00,
            'yearly_price' => 6000.00,
            'duration_in_days' => 150,
            'trial_days' => 14,
            'description' => 'باقة المراكز التعليمية',
            'stripe_price_id' => 'price_growth_test',
            'is_active' => true,
        ]);

        $this->admin = User::factory()->create([
            'name' => 'أحمد المدير',
            'email' => 'admin@center-test.com',
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin',
        ]);

        // Trial subscription that expired 1 hour ago
        $this->subscription = Subscription::forceCreate([
            'tenant_id' => $this->tenant->id,
            'package_id' => $this->package->id,
            'name' => 'default',
            'stripe_id' => 'sub_trial_test123',
            'stripe_status' => 'trialing',
            'stripe_price' => $this->package->slug,
            'quantity' => 1,
            'trial_ends_at' => now()->subHour(),
            'ends_at' => now()->subHour(),
            'status' => 'trialing',
            'billing_cycle' => 'monthly',
            'base_price' => 950.00,
            'total_amount' => 950.00,
            'discount_amount' => 0,
        ]);
    }

    public function test_send_subscription_reminders_sends_trial_expired_notification()
    {
        $this->artisan('app:send-subscription-reminders')
            ->assertExitCode(0);

        // Check that admin received TrialExpiredNotification in database
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->admin->id,
            'notifiable_type' => User::class,
            'type' => TrialExpiredNotification::class,
        ]);

        // Verify notification content for dashboard bell
        $notification = $this->admin->notifications()->first();
        $this->assertNotNull($notification);
        $this->assertEquals('trial_expired', $notification->data['type']);
        $this->assertStringContainsString('باقة النمو', $notification->data['message']);
        $this->assertStringContainsString('/subscription', $notification->data['url']);
    }

    public function test_expired_trial_redirects_to_subscription_page_with_warning()
    {
        config(['subscription.enforce_in_testing' => true]);

        $this->actingAs($this->admin);

        // Accessing students index should redirect because trial is expired
        $response = $this->get(route('center.students.index', ['tenant' => $this->tenant->domain]));

        $response->assertRedirect(route('center.subscription.index', ['tenant' => $this->tenant->domain]));
        $response->assertSessionHas('error');
    }

    public function test_subscription_index_shows_active_pay_button_for_expired_plan()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('center.subscription.index', ['tenant' => $this->tenant->domain]));

        $response->assertStatus(200);
        // Ensure the expired trial warning alert is rendered
        $response->assertSee(__('center::subscription.trial_expired_title'));
        // Ensure "Pay & Activate Now" CTA is present and not disabled
        $response->assertSee(__('center::subscription.pay_and_activate_now'));
        $response->assertSee(route('center.subscription.checkout', [
            'tenant' => $this->tenant->domain,
            'package' => $this->package->id,
        ]));
    }

    public function test_payment_activation_changes_trial_to_active_and_extends_expiry()
    {
        $paymentService = app(PaymentProcessingService::class);

        // Simulate successful payment (e.g. from Paymob or PayPal)
        $paymentService->activateSubscription(
            $this->tenant,
            'paymob',
            'txn_test_998877',
            $this->package,
            'monthly',
            950.00,
            950.00
        );

        $freshSub = $this->subscription->fresh();

        $this->assertEquals('active', $freshSub->status);
        $this->assertEquals('active', $freshSub->stripe_status);
        $this->assertEquals('paymob', $freshSub->gateway);
        $this->assertEquals($this->package->id, $freshSub->package_id);
        $this->assertTrue($freshSub->ends_at->isFuture());
        $this->assertGreaterThan(now()->addDays(25), $freshSub->ends_at);

        // Verify activeSubscription relation now returns this subscription
        $this->assertNotNull($this->tenant->fresh()->activeSubscription);

        // Verify SubscriptionLog recorded the payment
        $this->assertDatabaseHas('subscription_logs', [
            'tenant_id' => $this->tenant->id,
            'gateway' => 'paymob',
            'transaction_id' => 'txn_test_998877',
            'amount' => 950.00,
        ]);
    }
}
