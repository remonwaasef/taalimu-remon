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
                'free-trial' => 'true',
                'basic' => 'true',
                'pro' => 'true',
                'enterprise' => 'true',
            ],
            'advanced_roles' => [
                'free-trial' => 'false',
                'basic' => 'false',
                'pro' => 'true',
                'enterprise' => 'true',
            ],
            'daily_schedules' => [
                'free-trial' => 'true',
                'basic' => 'true',
                'pro' => 'true',
                'enterprise' => 'true',
            ],
            'attendance_tracking' => [
                'free-trial' => 'true',
                'basic' => 'true',
                'pro' => 'true',
                'enterprise' => 'true',
            ],
            'multi_branch' => [
                'free-trial' => 'false',
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
