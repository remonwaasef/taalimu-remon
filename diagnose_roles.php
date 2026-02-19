<?php

use App\Models\User;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

$email = 'remon.waasef@gmail.com'; // Assuming this is the user's email based on the prompt context, or I'll look for a center admin
$user = User::where('email', 'like', '%remon%')->first() ?: User::where('role', 'center_admin')->first();

if (!$user) {
    echo "User not found\n";
    exit;
}

echo "Diagnostic for User: {$user->email} (ID: {$user->id})\n";
echo "Role Column: {$user->role}\n";
echo "Tenant ID: {$user->tenant_id}\n";

// Set Team Context
if ($user->tenant_id) {
    app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($user->tenant_id);
    echo "Spatie Team ID set to: {$user->tenant_id}\n";
}

echo "Spatie Roles:\n";
foreach ($user->roles as $role) {
    echo "- Name: {$role->name}, Guard: {$role->guard_name}, Global: " . (is_null($role->tenant_id) ? 'Yes' : 'No') . "\n";
}

echo "Permissions Check (view schedule):\n";
echo "Can 'view schedule'?: " . ($user->can('view schedule') ? 'Yes' : 'No') . "\n";

echo "Role Check (center_admin):\n";
echo "Has role 'center_admin'?: " . ($user->hasRole('center_admin') ? 'Yes' : 'No') . "\n";

echo "\nDatabase Raw Check (model_has_roles):\n";
$pivot = DB::table('model_has_roles')->where('model_id', $user->id)->get();
foreach ($pivot as $row) {
    $roleName = DB::table('roles')->where('id', $row->role_id)->value('name');
    echo "- Role ID: {$row->role_id} ({$roleName}), Team ID: " . ($row->tenant_id ?? 'NULL') . "\n";
}

echo "\nSchedule Check (ID 14):\n";
$schedule = DB::table('schedules')->where('id', 14)->first();
if ($schedule) {
    echo "- Schedule 14 Tenant ID: {$schedule->tenant_id}\n";
} else {
    echo "- Schedule 14 not found in DB\n";
}
