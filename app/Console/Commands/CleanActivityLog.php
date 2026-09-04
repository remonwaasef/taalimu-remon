<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanActivityLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activitylog:clean
                            {--days=90 : Number of days to keep logs}
                            {--dry-run : Show what would be deleted without deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old activity log entries to prevent database bloat';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');

        $cutoffDate = now()->subDays($days);
        $this->info("Cleaning activity logs older than {$days} days (before {$cutoffDate->format('Y-m-d')})");

        if (!DB::getSchemaBuilder()->hasTable('activity_log')) {
            $this->warn('activity_log table does not exist. Skipping.');
            return Command::SUCCESS;
        }

        $count = DB::table('activity_log')
            ->where('created_at', '<', $cutoffDate)
            ->count();

        if ($count === 0) {
            $this->info('No old activity logs found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$count} log entries to delete.");

        if ($dryRun) {
            $this->warn('Dry run mode — no data will be deleted.');
            return Command::SUCCESS;
        }

        if (!$this->confirm("Are you sure you want to delete {$count} activity log entries?")) {
            $this->info('Cancelled.');
            return Command::SUCCESS;
        }

        DB::table('activity_log')
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        $this->info("✅ Deleted {$count} activity log entries.");
        return Command::SUCCESS;
    }
}
