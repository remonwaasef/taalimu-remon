<?php

namespace App\Console\Commands;

use App\Services\LocalizationAuditService;
use Illuminate\Console\Command;

class LocalizationAuditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'localization:audit
                            {--export-json= : Export detailed audit report to JSON file path}
                            {--export-md= : Export detailed audit report to Markdown file path}
                            {--lang=ar,en,fr : Comma-separated list of target languages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit project localization files (app & modules) for missing keys, language discrepancies, and hardcoded strings without modifying any files.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Starting Localization System Audit (App & Modules)...');
        $this->newLine();

        $langOption = $this->option('lang') ?? 'ar,en,fr';
        $languages = array_map('trim', explode(',', $langOption));

        $service = new LocalizationAuditService($languages);
        $result = $service->audit();

        $summary = $result['summary'];

        // Output Summary Table
        $this->table(
            ['Metric', 'Count'],
            [
                ['Target Languages', implode(', ', $languages)],
                ['Translation Files Audited', $summary['total_translation_files']],
                ['Referenced Translation Keys', $summary['total_referenced_keys']],
                ['Missing Keys Across Languages', $summary['total_missing_keys']],
                ['Unused / Dead Keys Detected', $summary['total_unused_keys']],
                ['Hardcoded Strings Detected', $summary['total_hardcoded_strings']],
                ['Syntax / Decoding Errors', $summary['total_syntax_errors']],
            ]
        );

        // Report Key Discrepancies
        if (!empty($result['discrepancies'])) {
            $this->newLine();
            $this->warn('⚠️ Language Structural Discrepancies (' . count($result['discrepancies']) . ' keys):');
            $rows = [];
            foreach (array_slice($result['discrepancies'], 0, 15) as $disc) {
                $rows[] = [$disc['key'], $disc['present_in'], $disc['missing_in']];
            }
            $this->table(['Translation Key', 'Present In', 'Missing In'], $rows);
        }

        // Report Hardcoded Strings
        if (!empty($result['hardcoded_strings'])) {
            $this->newLine();
            $this->warn('⚠️ Hardcoded User-Facing Strings Sample (' . count($result['hardcoded_strings']) . ' detected):');
            $rows = [];
            foreach (array_slice($result['hardcoded_strings'], 0, 10) as $hc) {
                $rows[] = [$hc['file'], $hc['string']];
            }
            $this->table(['Blade View File', 'Hardcoded String'], $rows);
        }

        // Export JSON
        if ($jsonPath = $this->option('export-json')) {
            if ($service->exportJson($jsonPath, $result)) {
                $this->info("✅ Exported JSON report to: {$jsonPath}");
            } else {
                $this->error("❌ Failed to write JSON report to: {$jsonPath}");
            }
        }

        // Export Markdown
        if ($mdPath = $this->option('export-md')) {
            if ($service->exportMarkdown($mdPath, $result)) {
                $this->info("✅ Exported Markdown report to: {$mdPath}");
            } else {
                $this->error("❌ Failed to write Markdown report to: {$mdPath}");
            }
        }

        $this->newLine();
        if ($summary['total_missing_keys'] === 0 && $summary['total_syntax_errors'] === 0) {
            $this->info('🎉 Localization Audit Passed with 100% Structural Integrity!');
            return Command::SUCCESS;
        }

        $this->warn('⚠️ Audit complete with findings. No translation files were modified.');
        return Command::SUCCESS;
    }
}
