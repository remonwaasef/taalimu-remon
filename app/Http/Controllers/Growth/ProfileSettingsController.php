<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\PublicProfile;
use App\Services\GrowthProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProfileSettingsController extends Controller
{
    public function __construct(
        protected GrowthProfileService $profileService
    ) {}

    /**
     * Show the profile settings form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = $this->resolveProfile($user);

        Gate::authorize('viewSettings', $profile);

        return view('growth.settings.profile', compact('profile'));
    }

    /**
     * Update the profile settings.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $profile = $this->resolveProfile($user);

        Gate::authorize('update', $profile);

        $validated = $request->validate([
            'slug' => [
                'required', 'string', 'max:100', 'regex:/^[a-z0-9\-]+$/',
                \Illuminate\Validation\Rule::unique('public_profiles', 'slug')
                    ->ignore($profile->id)
                    ->where('tenant_id', $profile->tenant_id),
            ],
            'title' => 'nullable|string|max:255',
            'headline' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'photo' => 'nullable|image|max:2048',
            'cover_photo' => 'nullable|image|max:4096',
            'location' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'specializations' => 'nullable|array',
            'specializations.*' => 'string|max:100',
            'teaching_levels' => 'nullable|array',
            'teaching_levels.*' => 'string|max:50',
            'delivery_modes' => 'nullable|array',
            'delivery_modes.*' => 'in:online,offline,both',
            'social_links.facebook' => 'nullable|url|max:255',
            'social_links.instagram' => 'nullable|url|max:255',
            'social_links.whatsapp' => 'nullable|string|max:20',
            'visibility' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('growth/profiles', 'public');
        }

        if ($request->hasFile('cover_photo')) {
            $validated['cover_photo'] = $request->file('cover_photo')->store('growth/profiles', 'public');
        }

        $this->profileService->update($profile, $validated);

        return redirect()->route('growth.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Publish the profile.
     */
    public function publish(Request $request)
    {
        $user = $request->user();
        $profile = $this->resolveProfile($user);

        Gate::authorize('publish', $profile);

        $this->profileService->publish($profile);

        return redirect()->route('growth.profile.edit')
            ->with('success', 'Your profile is now live! Share your Taalimu link with students.');
    }

    /**
     * Unpublish the profile.
     */
    public function unpublish(Request $request)
    {
        $user = $request->user();
        $profile = $this->resolveProfile($user);

        Gate::authorize('publish', $profile);

        $this->profileService->unpublish($profile);

        return redirect()->route('growth.profile.edit')
            ->with('success', 'Your profile has been unpublished.');
    }

    /**
     * Resolve the profile for the current user.
     */
    protected function resolveProfile($user): PublicProfile
    {
        $instructor = $user->instructor;

        if ($instructor) {
            return $this->profileService->getOrCreateForInstructor($instructor);
        }

        $tenant = app()->bound('tenant') ? app('tenant') : null;

        if ($tenant && in_array($user->role, ['center_admin', 'admin'])) {
            return $this->profileService->getOrCreateForCenter($tenant);
        }

        abort(403, 'You do not have a profile to manage.');
    }
}
