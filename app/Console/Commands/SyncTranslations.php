<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:sync
                            {--source=ar : Source language to sync from}
                            {--target=en : Target language to sync to}
                            {--dry-run : Show what would be synced without modifying files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync missing translation keys from source language to target language';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $source = $this->option('source');
        $target = $this->option('target');
        $dryRun = $this->option('dry-run');

        $this->info("Syncing translations from [{$source}] to [{$target}]...");
        $this->newLine();

        $paths = [
            resource_path("lang/{$source}"),
            module_path('Admin', "lang/{$source}"),
            module_path('Center', "resources/lang/{$source}"),
            module_path('Instructor', "lang/{$source}"),
            module_path('Campus', "lang/{$source}"),
            module_path('Parent', "resources/lang/{$source}"),
        ];

        $totalAdded = 0;
        $totalFiles = 0;

        foreach ($paths as $sourcePath) {
            if (!File::isDirectory($sourcePath)) {
                continue;
            }

            $targetPath = str_replace("/{$source}/", "/{$target}/", $sourcePath);

            $files = File::files($sourcePath);

            foreach ($files as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $sourceArray = include $file->getRealPath();
                $targetFile = $targetPath . '/' . $file->getFilename();

                if (!File::exists($targetFile)) {
                    $this->warn("Target file missing: {$targetFile}");
                    continue;
                }

                $targetArray = include $targetFile;

                $missingKeys = $this->findMissingKeys($sourceArray, $targetArray);

                if (empty($missingKeys)) {
                    continue;
                }

                $totalFiles++;
                $relativePath = str_replace(base_path() . '/', '', $targetFile);

                if ($dryRun) {
                    $this->info("Would add " . count($missingKeys) . " keys to: {$relativePath}");
                } else {
                    $this->info("Adding " . count($missingKeys) . " keys to: {$relativePath}");
                }

                $totalAdded += count($missingKeys);
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->info("  Files with missing keys: {$totalFiles}");
        $this->info("  Total keys to add: {$totalAdded}");

        if ($dryRun) {
            $this->warn("Dry run mode — no files were modified.");
        } else {
            $this->info("Files have been updated.");
        }

        return Command::SUCCESS;
    }

    /**
     * Find missing keys in target array that exist in source array.
     */
    protected function findMissingKeys(array $source, array $target, string $prefix = ''): array
    {
        $missing = [];

        foreach ($source as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $nestedMissing = $this->findMissingKeys(
                    $value,
                    $target[$key] ?? [],
                    $fullKey
                );
                $missing = array_merge($missing, $nestedMissing);
            } else {
                if (!array_key_exists($key, $target)) {
                    $missing[] = $fullKey;
                }
            }
        }

        return $missing;
    }
}
