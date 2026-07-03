<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class AddMissingSubscriptionFeaturesSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            [
                'name' => 'Exams Management',
                'code' => 'manage_exams',
                'type' => 'boolean',
                'category' => 'analysis',
            ],
            [
                'name' => 'Advanced Roles',
                'code' => 'advanced_roles',
                'type' => 'boolean',
                'category' => 'core',
            ],
            [
                'name' => 'Daily Schedules',
                'code' => 'daily_schedules',
                'type' => 'boolean',
                'category' => 'core',
            ],
            [
                'name' => 'Attendance Tracking',
                'code' => 'attendance_tracking',
                'type' => 'boolean',
                'category' => 'core',
            ],
            [
                'name' => 'Max Branches',
                'code' => 'max_branches',
                'type' => 'limit',
                'category' => 'core',
            ],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(['code' => $feature['code']], $feature);
        }
    }
}
