<?php

namespace App\Services;

use Illuminate\Support\Collection;

class RolePresetService
{
    /**
     * Ready-made permission templates used to create tenant sub-roles
     * (e.g. a junior accountant or a reception cashier) with one click.
     *
     * Permission sets mirror the seeded core roles so sub-roles stay consistent.
     *
     * @return \Illuminate\Support\Collection<string, array{label: string, icon: string, suggested_name: string, permissions: array<int, string>}>
     */
    public function presets(): Collection
    {
        return collect([
            'accountant' => [
                'label' => 'preset_accountant',
                'icon' => 'fa-calculator',
                'suggested_name' => 'accountant_sub_suggestion',
                'permissions' => [
                    'view sales',
                    'create sales',
                    'edit sales',
                    'delete sales',
                    'view expenses',
                    'create expenses',
                    'edit expenses',
                    'delete expenses',
                    'view billing',
                    'manage billing',
                    'view reports',
                    'view analytics',
                ],
            ],
            'secretary' => [
                'label' => 'preset_secretary',
                'icon' => 'fa-clipboard-user',
                'suggested_name' => 'secretary_sub_suggestion',
                'permissions' => [
                    'view students',
                    'create students',
                    'edit students',
                    'view instructors',
                    'view courses',
                    'view schedule',
                    'manage schedule',
                    'view attendance',
                    'take attendance',
                    'view sales',
                    'create sales',
                ],
            ],
            'cashier' => [
                'label' => 'preset_cashier',
                'icon' => 'fa-cash-register',
                'suggested_name' => 'cashier_sub_suggestion',
                'permissions' => [
                    'view students',
                    'view courses',
                    'view sales',
                    'create sales',
                    'view billing',
                    'view schedule',
                ],
            ],
            'staff' => [
                'label' => 'preset_staff',
                'icon' => 'fa-user-gear',
                'suggested_name' => 'staff_sub_suggestion',
                'permissions' => [
                    'view students',
                    'view courses',
                    'view schedule',
                    'view attendance',
                ],
            ],
        ]);
    }
}
