<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SsoSignatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_sso_login_rejects_missing_signature()
    {
        $tenant = $this->createTenant(['domain' => 'sig']);
        $user = User::factory()->create([
            'email' => 'admin@sig.com',
            'tenant_id' => $tenant->id,
            'role' => 'center_admin',
        ]);

        app()->instance('tenant', $tenant);

        $loginToken = \Illuminate\Support\Str::random(64);
        Cache::put('login_token_'.$loginToken, [
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'locale' => 'ar',
        ], now()->addSeconds(60));

        $response = $this->post('http://sig.localhost/login/sso', [
            'token' => $loginToken,
        ]);

        $this->assertTrue($response->isRedirect());
        $this->assertNull(auth()->user());
    }

    public function test_sso_login_rejects_forged_signature()
    {
        $tenant = $this->createTenant(['domain' => 'sig2']);
        $user = User::factory()->create([
            'email' => 'admin@sig2.com',
            'tenant_id' => $tenant->id,
            'role' => 'center_admin',
        ]);

        app()->instance('tenant', $tenant);

        $loginToken = \Illuminate\Support\Str::random(64);
        Cache::put('login_token_'.$loginToken, [
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'locale' => 'ar',
        ], now()->addSeconds(60));

        $response = $this->post('http://sig2.localhost/login/sso', [
            'token' => $loginToken,
            'signature' => str_repeat('0', 64),
        ]);

        $this->assertTrue($response->isRedirect());
        $this->assertNull(auth()->user());
        $this->assertTrue(Cache::has('login_token_'.$loginToken), 'Token must not be consumed by a forged request.');
    }

    public function test_sso_login_accepts_valid_signature()
    {
        $tenant = $this->createTenant(['domain' => 'sig3']);
        $user = User::factory()->create([
            'email' => 'admin@sig3.com',
            'tenant_id' => $tenant->id,
            'role' => 'center_admin',
        ]);

        app()->instance('tenant', $tenant);

        $loginToken = \Illuminate\Support\Str::random(64);
        Cache::put('login_token_'.$loginToken, [
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'locale' => 'ar',
        ], now()->addSeconds(60));

        $signature = hash_hmac('sha256', $loginToken, config('app.key'));

        $response = $this->post('http://sig3.localhost/login/sso', [
            'token' => $loginToken,
            'signature' => $signature,
        ]);

        $response->assertRedirect();
        $this->assertSame($user->id, auth()->id());
        $this->assertFalse(Cache::has('login_token_'.$loginToken), 'Token must be single-use.');
    }
}
