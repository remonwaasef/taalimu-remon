<?php

namespace App\Console\Commands;

use App\Models\Instructor;
use App\Models\NetworkIdentity;
use App\Models\PublicProfile;
use App\Models\Tenant;
use App\Services\NetworkIdentityService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BackfillNetworkIdentities extends Command
{
    protected $signature = 'network:backfill-identities {--dry-run : Show what would be created without making changes}';

    protected $description = 'Backfill NetworkIdentity records from existing PublicProfiles';

    public function __construct(
        protected NetworkIdentityService $networkIdentityService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in DRY-RUN mode - no changes will be made');
        }

        $this->info('Starting NetworkIdentity backfill...');

        $stats = [
            'identities_to_create' => 0,
            'identities_existing' => 0,
            'skipped_invalid' => 0,
            'errors' => 0,
        ];

        $teacherProfiles = PublicProfile::where('profilable_type', Instructor::class)
            ->whereHas('profilable', fn ($q) => $q->where('status', 'active'))
            ->with('profilable')
            ->get();

        $centerProfiles = PublicProfile::where('profilable_type', Tenant::class)
            ->whereHas('profilable', fn ($q) => $q->where('status', 'active'))
            ->with('profilable')
            ->get();

        $this->info("Found {$teacherProfiles->count()} teacher profiles");
        $this->info("Found {$centerProfiles->count()} center profiles");

        $this->processProfiles($teacherProfiles, 'teacher', $dryRun, $stats);
        $this->processProfiles($centerProfiles, 'center', $dryRun, $stats);

        $this->displayResults($stats);

        return Command::SUCCESS;
    }

    protected function processProfiles($profiles, string $type, bool $dryRun, array &$stats): void
    {
        $bar = $this->output->createProgressBar($profiles->count());
        $bar->start();

        foreach ($profiles as $profile) {
            try {
                $profilable = $profile->profilable;

                if (! $profilable) {
                    $stats['skipped_invalid']++;
                    Log::warning('Backfill: PublicProfile has no profilable', [
                        'public_profile_id' => $profile->id,
                        'profilable_type' => $profile->profilable_type,
                        'profilable_id' => $profile->profilable_id,
                    ]);
                    $bar->advance();
                    continue;
                }

                $existing = NetworkIdentity::where('profilable_type', $profile->profilable_type)
                    ->where('profilable_id', $profile->profilable_id)
                    ->first();

                if ($existing) {
                    $stats['identities_existing']++;
                    $bar->advance();
                    continue;
                }

                $stats['identities_to_create']++;

                if (! $dryRun) {
                    $status = $profile->published ? NetworkIdentity::STATUS_PUBLISHED : NetworkIdentity::STATUS_DRAFT;

                    if ($type === 'teacher' && $profilable instanceof Instructor) {
                        $this->networkIdentityService->createForInstructor($profilable, [
                            'public_profile_id' => $profile->id,
                            'slug' => $profile->slug,
                            'status' => $status,
                            'headline' => $profile->headline,
                            'short_description' => $profile->bio,
                        ]);
                    } elseif ($type === 'center' && $profilable instanceof Tenant) {
                        $this->networkIdentityService->createForCenter($profilable, [
                            'public_profile_id' => $profile->id,
                            'slug' => $profile->slug,
                            'status' => $status,
                            'headline' => $profile->headline,
                            'short_description' => $profile->bio,
                        ]);
                    }
                }

                $bar->advance();
            } catch (\Throwable $e) {
                $stats['errors']++;
                Log::error('Backfill error', [
                    'public_profile_id' => $profile->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
    }

    protected function displayResults(array $stats): void
    {
        $this->newLine();
        $this->info('=== BACKFILL RESULTS ===');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Identities to create', $stats['identities_to_create']],
                ['Identities already existing', $stats['identities_existing']],
                ['Skipped (invalid)', $stats['skipped_invalid']],
                ['Errors', $stats['errors']],
            ]
        );

        if ($this->option('dry-run')) {
            $this->warn('DRY-RUN: No changes were made. Run without --dry-run to execute.');
        }
    }
}