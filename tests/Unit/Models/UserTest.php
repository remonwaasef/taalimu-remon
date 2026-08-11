<?php

namespace Tests\Unit\Models;

use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['domain' => 'test', 'name' => 'Test', 'onboarding_status' => 'completed']);
        app()->instance('tenant', $this->tenant);
    }

    #[Test]
    public function it_generates_qr_identifier_for_students()
    {
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        $this->assertNotEmpty($user->qr_identifier);
        $this->assertEquals(12, strlen($user->qr_identifier));
    }

    #[Test]
    public function it_does_not_generate_qr_identifier_for_non_students()
    {
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'center_admin',
        ]);

        $this->assertNull($user->qr_identifier);
    }

    #[Test]
    public function it_hides_sensitive_fields()
    {
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'center_admin',
            'google2fa_secret' => 'secret123',
        ]);

        $array = $user->toArray();
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
        $this->assertArrayNotHasKey('google2fa_secret', $array);
    }

    #[Test]
    public function it_has_tenant_relationship()
    {
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'center_admin',
        ]);

        $this->assertEquals($this->tenant->id, $user->tenant->id);
    }

    #[Test]
    public function it_can_generate_phone_verification_code()
    {
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        $code = $user->generatePhoneVerificationCode();

        $this->assertNotEmpty($code);
        $this->assertEquals(6, strlen($code));
        // Column is encrypted in the DB, so read back through the model
        $this->assertEquals($code, $user->fresh()->phone_verification_code);
    }

    #[Test]
    public function it_can_mark_phone_as_verified()
    {
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone_verification_code' => '123456',
            'phone_verification_expires_at' => now()->addMinutes(15),
        ]);

        $user->markPhoneAsVerified();

        $this->assertNotNull($user->fresh()->phone_verified_at);
        $this->assertNull($user->fresh()->phone_verification_code);
    }

    #[Test]
    public function it_has_student_relationship()
    {
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        Student::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $user->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'phone' => '01000000001',
        ]);

        $this->assertNotNull($user->student);
        $this->assertEquals('Student', $user->student->name);
    }

    #[Test]
    public function it_encrypts_google2fa_secret()
    {
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => 'password',
            'role' => 'center_admin',
        ]);
        $user->forceFill(['google2fa_secret' => 'JBSWY3DPEHPK3PXP'])->save();

        $raw = \Illuminate\Support\Facades\DB::table('users')
            ->where('id', $user->id)
            ->value('google2fa_secret');

        $this->assertNotEquals('JBSWY3DPEHPK3PXP', $raw);
        $this->assertEquals('JBSWY3DPEHPK3PXP', $user->fresh()->google2fa_secret);
    }
}
