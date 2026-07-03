<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected $tenantUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        // Setup Tenant
        $this->tenant = $this->createTenant(['domain' => 'test', 'name' => 'Test Center']);
        app()->instance('tenant', $this->tenant);

        // Setup Tenant User (Center Admin)
        $this->tenantUser = User::factory()->create(['email' => 'center@test.com', 'tenant_id' => $this->tenant->id, 'role' => 'center_admin']);

        // Setup Super Admin
        $this->admin = User::factory()->create(['email' => 'superadmin@test.com', 'role' => 'super_admin']);
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(null);
        $this->admin->assignRole('super_admin');
    }

    public function test_tenant_can_create_ticket()
    {
        $this->actingAs($this->tenantUser);

        $response = $this->post(route('center.tickets.store', ['tenant' => $this->tenant->domain]), [
            'subject' => 'Help needed',
            'category' => 'technical',
            'priority' => 'high',
            'message' => 'I cannot login.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tickets', ['subject' => 'Help needed', 'priority' => 'high']);
        $this->assertDatabaseHas('ticket_messages', ['message' => 'I cannot login.']);
    }

    public function test_tenant_can_view_tickets()
    {
        $this->actingAs($this->tenantUser);

        $ticket = Ticket::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->tenantUser->id,
            'subject' => 'Existing Ticket',
            'status' => 'open',
        ]);

        $response = $this->get(route('center.tickets.index', ['tenant' => $this->tenant->domain]));

        $response->assertStatus(200);
        $response->assertSee('Existing Ticket');
    }

    public function test_admin_can_view_and_reply_to_ticket()
    {
        // 1. Create Ticket as Tenant
        $ticket = Ticket::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->tenantUser->id,
            'subject' => 'Admin Help',
            'status' => 'open',
        ]);

        // 2. Login as Super Admin
        $this->actingAs($this->admin);

        // 3. View Ticket
        $response = $this->get(route('admin.tickets.show', $ticket->id));
        $response->assertStatus(200);
        $response->assertSee('Admin Help');

        // 4. Reply
        $response = $this->post(route('admin.tickets.reply', $ticket->id), [
            'message' => 'We are checking it.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ticket_messages', ['message' => 'We are checking it.']);

        // 5. Verify status update
        $this->assertEquals('answered', $ticket->fresh()->status);
    }

    public function test_admin_can_close_ticket()
    {
        $ticket = Ticket::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->tenantUser->id,
            'subject' => 'To Close',
            'status' => 'open',
        ]);

        $this->actingAs($this->admin);

        $response = $this->post(route('admin.tickets.close', $ticket->id));

        $response->assertRedirect();
        $this->assertEquals('closed', $ticket->fresh()->status);
    }
}
