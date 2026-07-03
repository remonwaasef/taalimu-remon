<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class BenchmarkSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'benchmark:system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run performance benchmarks for the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting System Benchmark...');

        $tenant = \App\Models\Tenant::where('domain', 'loadtest')->first();
        if (! $tenant) {
            $this->error("Load test tenant not found. Run 'php artisan db:seed --class=LargeScaleSeeder' first.");

            return;
        }

        // Set tenant context
        app()->instance('tenant', $tenant);

        // 1. Database Query Benchmark
        $this->benchmarkDatabase();

        // 2. Cache Benchmark
        $this->benchmarkCache();

        // 3. Application Response Benchmark (Internal)
        $this->benchmarkApplicationResponse($tenant);

        $this->info('Benchmark Completed.');
    }

    protected function benchmarkDatabase()
    {
        $this->info("\n--- Database Benchmark ---");

        // Simple Select
        $start = microtime(true);
        $count = Student::where('tenant_id', app('tenant')->id)->count();
        $duration = (microtime(true) - $start) * 1000;
        $this->line("Count {$count} students: ".number_format($duration, 2).'ms');

        // Complex Query (with relationships)
        $start = microtime(true);
        $students = Student::where('tenant_id', app('tenant')->id)
            ->with(['user', 'enrollments'])
            ->latest()
            ->take(50)
            ->get();
        $duration = (microtime(true) - $start) * 1000;
        $this->line('Fetch 50 students with relations: '.number_format($duration, 2).'ms');

        // Search Query (using Index)
        $start = microtime(true);
        $search = Student::where('tenant_id', app('tenant')->id)
            ->where('email', 'like', 'student_5%')
            ->take(20)
            ->get();
        $duration = (microtime(true) - $start) * 1000;
        $this->line('Search students by email (indexed): '.number_format($duration, 2).'ms');
    }

    protected function benchmarkCache()
    {
        $this->info("\n--- Cache Benchmark ---");

        $key = 'benchmark_test_key';

        // Write
        $start = microtime(true);
        for ($i = 0; $i < 1000; $i++) {
            Cache::put($key.$i, 'value', 60);
        }
        $duration = (microtime(true) - $start) * 1000;
        $this->line('1000 Cache Writes: '.number_format($duration, 2).'ms');

        // Read
        $start = microtime(true);
        for ($i = 0; $i < 1000; $i++) {
            Cache::get($key.$i);
        }
        $duration = (microtime(true) - $start) * 1000;
        $this->line('1000 Cache Reads: '.number_format($duration, 2).'ms');
    }

    protected function benchmarkApplicationResponse($tenant)
    {
        $this->info("\n--- Application Logic Benchmark ---");

        // We will simulate internal request logic by calling services directly or simple controller actions
        // mocking request is complex in command, so we test Service methods which contain the heavy logic.

        $admin = User::where('email', 'admin@loadtest.com')->first();
        $this->info('Simulating Center Dashboard load for Admin...');

        $start = microtime(true);

        // Simulate AnalyticsController::index() logic
        $totalStudents = Student::count();
        $totalCourses = Course::count();
        $recentSales = \App\Models\Sale::with('student')->latest()->take(5)->get();

        $duration = (microtime(true) - $start) * 1000;
        $this->line('Dashboard Stats Calculation: '.number_format($duration, 2).'ms');

        if ($duration < 200) {
            $this->info('✅ Performance: EXCELLENT (< 200ms)');
        } elseif ($duration < 500) {
            $this->comment('⚠️ Performance: ACCEPTABLE (< 500ms)');
        } else {
            $this->error('❌ Performance: SLOW (> 500ms)');
        }
    }
}
