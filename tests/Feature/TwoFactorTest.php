<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;
    protected $google2fa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->google2fa = new Google2FA();

        // Setup Tenant with onboarding completed
        $this->tenant = $this->createTenant([
            'domain' => 'test',
            'name' => 'Test Center',
        ]);
        app()->instance('tenant', $this->tenant);

        // Setup User with center_admin role
        $this->user = User::factory()->create([
            'email' => 'user@test.com',
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin'
        ]);
    }

    public function test_user_can_access_2fa_enable_page()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('2fa.enable', ['tenant' => $this->tenant->domain]));

        // The route renders setup.blade.php which contains "Mandatory Security: 2FA Setup"
        $response->assertStatus(200);
        $response->assertSee('2FA');
    }

    public function test_user_can_enable_2fa_with_valid_otp()
    {
        $this->actingAs($this->user);

        // Generate secret and store directly on user (bypassing $fillable since this is sensitive)
        $secret = $this->google2fa->generateSecretKey();
        $this->user->forceFill(['google2fa_secret' => $secret])->save();

        // Generate valid OTP
        $otp = $this->google2fa->getCurrentOtp($secret);

        $response = $this->post(route('2fa.store', ['tenant' => $this->tenant->domain]), [
            'one_time_password' => $otp
        ]);

        $response->assertRedirect(route('center.dashboard', ['tenant' => $this->tenant->domain]));

        // Verify it's saved in database
        $this->user->refresh();
        $this->assertTrue((bool) $this->user->google2fa_enabled);
        $this->assertNotNull($this->user->google2fa_secret);
    }

    public function test_user_cannot_enable_2fa_with_invalid_otp()
    {
        $this->actingAs($this->user);

        // Generate secret and store on user
        $secret = $this->google2fa->generateSecretKey();
        $this->user->forceFill(['google2fa_secret' => $secret])->save();

        $response = $this->post(route('2fa.store', ['tenant' => $this->tenant->domain]), [
            'one_time_password' => '000000' // Invalid OTP
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('one_time_password');

        // Verify it's NOT enabled in database
        $this->user->refresh();
        $this->assertFalse((bool) $this->user->google2fa_enabled);
    }

    public function test_user_with_2fa_enabled_must_verify()
    {
        // Enable 2FA for user using forceFill (bypassing $fillable for sensitive fields)
        $secret = $this->google2fa->generateSecretKey();
        $this->user->forceFill([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true
        ])->save();

        $this->actingAs($this->user);

        // Try to access dashboard without 2FA verification
        $response = $this->get(route('center.dashboard', ['tenant' => $this->tenant->domain]));

        // Should be redirected to 2FA verification page (via TwoFactorMiddleware)
        $response->assertRedirect(route('2fa.verify', ['tenant' => $this->tenant->domain]));
    }

    public function test_user_can_verify_2fa_and_access_dashboard()
    {
        // Enable 2FA for user using forceFill
        $secret = $this->google2fa->generateSecretKey();
        $this->user->forceFill([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true
        ])->save();

        $this->actingAs($this->user);

        // Generate valid OTP using the raw secret (before encryption cast)
        $otp = $this->google2fa->getCurrentOtp($secret);

        $response = $this->post(route('2fa.verify.post', ['tenant' => $this->tenant->domain]), [
            'one_time_password' => $otp
        ]);

        $response->assertRedirect(route('center.dashboard', ['tenant' => $this->tenant->domain]));

        // Session should have 2fa_verified
        $this->assertTrue(session('2fa_verified'));
    }
}
