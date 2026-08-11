<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MonitorDatabaseSizes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:monitor-sizes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report database table sizes and alert when any table exceeds growth thresholds';

    /**
     * Tables above these thresholds trigger an alert in the report.
     */
    private const ALERT_ROW_THRESHOLD = 1000000;

    private const ALERT_SIZE_MB = 512;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = DB::select(
            'SELECT table_name, table_rows, ROUND((data_length + index_length) / 1024 / 1024, 1) AS size_mb
             FROM information_schema.tables
             WHERE table_schema = DATABASE()
             ORDER BY (data_length + index_length) DESC'
        );

        $totalMb = 0;
        $alerts = [];

        foreach ($tables as $table) {
            $totalMb += (float) $table->size_mb;

            if ((int) $table->table_rows >= self::ALERT_ROW_THRESHOLD || (float) $table->size_mb >= self::ALERT_SIZE_MB) {
                $alerts[] = sprintf('%s => %s rows / %s MB', $table->table_name, number_format((int) $table->table_rows), $table->size_mb);
            }
        }

        $lines = [
            'Database Size Report',
            'Total: '.number_format($totalMb, 1).' MB across '.count($tables).' tables',
            '',
            'Largest tables:',
        ];

        foreach (array_slice($tables, 0, 10) as $table) {
            $lines[] = sprintf('%s => %s rows / %s MB', $table->table_name, number_format((int) $table->table_rows), $table->size_mb);
        }

        $lines[] = '';

        if ($alerts) {
            $lines[] = 'Growth alerts:';
            $lines = array_merge($lines, $alerts);
        } else {
            $lines[] = 'No table exceeds thresholds (rows: 1M / size: 512 MB).';
        }

        $message = implode(PHP_EOL, $lines);

        app(\App\Services\TelegramService::class)->sendAdminNotification($message);

        $this->info($message);

        return self::SUCCESS;
    }
}
