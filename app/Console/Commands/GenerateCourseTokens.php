<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateCourseTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courses:generate-tokens';

    protected $description = 'Generate registration tokens for all existing courses';

    public function handle()
    {
        $courses = \App\Models\Course::whereNull('registration_token')->get();
        $count = 0;

        foreach ($courses as $course) {
            $course->update([
                'registration_token' => \Illuminate\Support\Str::random(16)
            ]);
            $count++;
        }

        $this->info("Generated tokens for $count courses.");
    }
}
