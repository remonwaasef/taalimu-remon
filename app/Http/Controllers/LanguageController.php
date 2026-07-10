<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\Tenant;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function __construct(
        protected SettingsService $settingsService
    ) {}

    public function switch($locale)
    {
        if (! in_array($locale, ['ar', 'en', 'fr'])) {
            return redirect()->back();
        }

        Session::put('locale', $locale);

        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);

            if ($locale === 'fr' && auth()->user()->tenant_id) {
                $this->applyFrenchSystem(auth()->user()->tenant_id);
            }
        }

        return redirect()->back();
    }

    private function applyFrenchSystem(int $tenantId): void
    {
        try {
            $tenant = Tenant::find($tenantId);
            if (! $tenant) {
                return;
            }

            $hasStages = Stage::where('tenant_id', $tenant->id)->exists();
            if (! $hasStages) {
                $this->settingsService->applyTemplate($tenant, 'french_system');
            }
        } catch (\Exception $e) {
            Log::warning('Auto-apply French education system failed: '.$e->getMessage());
        }
    }
}
