<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\PublicProfile;
use App\Models\Tenant;
use App\Services\GrowthEventService;
use App\Services\GrowthProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicProfileController extends Controller
{
    public function __construct(
        protected GrowthProfileService $profileService,
        protected GrowthEventService $eventService
    ) {}

    /**
     * Show a teacher's public profile by slug.
     */
    public function showTeacher(Request $request, string $slug)
    {
        $profile = $this->profileService->findPublishedBySlug($slug, Instructor::class);

        // Allow owner/admin to preview their own profile even if unpublished
        if (! $profile && auth()->check()) {
            $candidate = PublicProfile::withoutGlobalScopes()
                ->where('slug', $slug)
                ->where('profilable_type', Instructor::class)
                ->first();
            if ($candidate && ((int) auth()->user()->tenant_id === (int) $candidate->tenant_id || auth()->user()->role === 'super_admin')) {
                $profile = $candidate;
            }
        }

        if (! $profile) {
            abort(404, 'Teacher profile not found.');
        }

        $instructor = $profile->profilable;

        if (! $instructor || $instructor->status !== 'active') {
            abort(404, 'Teacher not found.');
        }

        $tenant = Tenant::findOrFail($profile->tenant_id);

        if ($tenant->status !== 'active') {
            abort(404, 'Center not found.');
        }

        // If on central domain, redirect to tenant subdomain
        $mainHost = config('app.tenant_domain') ?: parse_url(config('app.url'), PHP_URL_HOST);
        if ($tenant->domain && in_array($request->getHost(), [$mainHost, 'www.' . $mainHost, 'taalimu.com', 'www.taalimu.com'])) {
            return redirect()->to(tenant_url('', $tenant), 301);
        }

        $this->trackView($profile, $request);

        $seoData = $this->buildSeoData($profile, $tenant, 'teacher');

        return view('growth.public.teacher', compact('profile', 'instructor', 'tenant', 'seoData'));
    }

    /**
     * Show a center's public profile by slug.
     */
    public function showCenter(Request $request, string $slug)
    {
        $profile = $this->profileService->findPublishedBySlug($slug, Tenant::class);

        // Allow owner/admin to preview their own profile even if unpublished
        if (! $profile && auth()->check()) {
            $candidate = PublicProfile::withoutGlobalScopes()
                ->where('slug', $slug)
                ->where('profilable_type', Tenant::class)
                ->first();
            if ($candidate && ((int) auth()->user()->tenant_id === (int) $candidate->tenant_id || auth()->user()->role === 'super_admin')) {
                $profile = $candidate;
            }
        }

        if (! $profile) {
            abort(404, 'Center profile not found.');
        }

        $tenant = Tenant::findOrFail($profile->tenant_id);

        if ($tenant->status !== 'active') {
            abort(404, 'Center not found.');
        }

        // If on central domain, redirect to tenant subdomain
        $mainHost = config('app.tenant_domain') ?: parse_url(config('app.url'), PHP_URL_HOST);
        if ($tenant->domain && in_array($request->getHost(), [$mainHost, 'www.' . $mainHost, 'taalimu.com', 'www.taalimu.com'])) {
            return redirect()->to(tenant_url('', $tenant), 301);
        }

        $this->trackView($profile, $request);

        $seoData = $this->buildSeoData($profile, $tenant, 'center');

        return view('growth.public.center', compact('profile', 'tenant', 'seoData'));
    }

    /**
     * Track the profile view event.
     */
    protected function trackView(PublicProfile $profile, Request $request): void
    {
        $source = $request->query('source');
        $campaign = $request->query('campaign');

        $this->eventService->trackProfileView($profile, $source, $campaign);
    }

    /**
     * Build SEO data for the profile.
     */
    protected function buildSeoData(PublicProfile $profile, Tenant $tenant, string $type): array
    {
        $title = $profile->meta_title
            ?? ($profile->title . ' | ' . $tenant->name);

        $description = $profile->meta_description
            ?? ($profile->headline ?? $profile->bio
                ? Str::limit(strip_tags($profile->headline ?? $profile->bio), 160)
                : 'Taalimu - ' . $tenant->name);

        return [
            'title' => $title,
            'description' => $description,
            'og_image' => $profile->og_image
                ?? ($profile->photo ? asset('storage/' . $profile->photo) : null),
            'canonical' => $profile->getUrl(),
            'type' => $type,
        ];
    }
}
