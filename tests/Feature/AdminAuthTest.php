<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected $google2fa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->google2fa = new Google2FA;
    }

    /**
     * Create a global (tenant_id = null) admin account.
     */
    private function globalAdmin(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'email' => 'admin@taalimu.com',
            'password' => 'password',
            'tenant_id' => null,
            'role' => 'super_admin',
        ], $attributes));
    }

    public function test_admin_without_2fa_logs_in_directly_to_dashboard()
    {
        $admin = $this->globalAdmin();

        $response = $this->post(route('admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_with_wrong_password_cannot_login()
    {
        $admin = $this->globalAdmin();

        $response = $this->post(route('admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_tenant_user_cannot_login_through_admin_panel()
    {
        $tenant = $this->createTenant();
        User::factory()->create([
            'email' => 'tenant-admin@test.com',
            'password' => 'password',
            'tenant_id' => $tenant->id,
            'role' => 'center_admin',
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'tenant-admin@test.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_tenant_user_cannot_access_admin_dashboard()
    {
        $tenant = $this->createTenant();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'center_admin',
        ]);

        $this->actingAs($user);

        $this->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_unauthenticated_user_is_redirected_to_admin_login()
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_logout_and_log_back_in_without_2fa()
    {
        $admin = $this->globalAdmin();

        $this->actingAs($admin);
        $this->post(route('admin.logout'));
        $this->assertGuest();

        $response = $this->post(route('admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_with_2fa_enabled_is_redirected_to_verification()
    {
        $secret = $this->google2fa->generateSecretKey();
        $admin = $this->globalAdmin();
        $admin->forceFill([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
        ])->save();

        $response = $this->post(route('admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.login.2fa'));
        $this->assertGuest();
    }

    public function test_admin_with_2fa_enabled_but_bypass_logs_in_directly()
    {
        $secret = $this->google2fa->generateSecretKey();
        $admin = $this->globalAdmin([
            'google2fa_bypass' => true,
        ]);
        $admin->forceFill([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
        ])->save();

        $response = $this->post(route('admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_with_2fa_enabled_can_verify_and_reach_dashboard()
    {
        $secret = $this->google2fa->generateSecretKey();
        $admin = $this->globalAdmin();
        $admin->forceFill([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
        ])->save();

        $this->post(route('admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $otp = $this->google2fa->getCurrentOtp($secret);

        $response = $this->post(route('admin.login.2fa.verify'), [
            'one_time_password' => $otp,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_with_2fa_enabled_is_rejected_with_wrong_otp()
    {
        $secret = $this->google2fa->generateSecretKey();
        $admin = $this->globalAdmin();
        $admin->forceFill([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
        ])->save();

        $this->post(route('admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response = $this->post(route('admin.login.2fa.verify'), [
            'one_time_password' => '000000',
        ]);

        $response->assertSessionHasErrors('one_time_password');
        $this->assertGuest();
    }
}