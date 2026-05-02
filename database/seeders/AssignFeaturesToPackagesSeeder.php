<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Feature;

class AssignFeaturesToPackagesSeeder extends Seeder
{
    public function run(): void
    {
        $newFeatures = [
            'manage_exams' => [
                'basic' => 'true',
                'pro' => 'true',
                'enterprise' => 'true',
            ],
            'advanced_roles' => [
                'basic' => 'false',
                'pro' => 'true',
                'enterprise' => 'true',
            ],
            'daily_schedules' => [
                'basic' => 'true',
                'pro' => 'true',
                'enterprise' => 'true',
            ],
            'attendance_tracking' => [
                'basic' => 'true',
                'pro' => 'true',
                'enterprise' => 'true',
            ],
            'multi_branch' => [
                'basic' => 'false',
                'pro' => 'false',
                'enterprise' => 'true',
            ],
        ];

        foreach ($newFeatures as $code => $values) {
            $feature = Feature::where('code', $code)->first();
            if (!$feature) continue;

            foreach ($values as $slug => $value) {
                $package = Package::where('slug', $slug)->first();
                if ($package) {
                    $package->features()->syncWithoutDetaching([
                        $feature->id => ['value' => $value]
                    ]);
                }
            }
        }
    }
}
