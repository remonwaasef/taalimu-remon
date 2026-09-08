@extends('layouts.app-next')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Growth Profile</h1>
            <p class="text-slate-500 mt-1">Manage your public teaching identity</p>
        </div>
        <div class="flex items-center gap-3">
            @if($profile->published)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 rounded-full text-sm font-medium">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    Live
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-sm font-medium">
                    <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                    Draft
                </span>
            @endif
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Referral Link --}}
    @if($profile && $profile->referral_code)
        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-900">{{ __('Your Referral Link') }}</p>
                    <p class="text-sm text-indigo-700 mt-0.5">{{ __('Share this link to track referrals') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="text" id="referral-link" value="{{ url('/t/' . $profile->slug . '?ref=' . $profile->referral_code) }}" readonly class="px-3 py-1.5 bg-white border border-indigo-200 rounded-lg text-sm text-slate-700 w-64">
                    <button onclick="navigator.clipboard.writeText(document.getElementById('referral-link').value); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy', 2000)" class="px-3 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">{{ __('Copy') }}</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Profile URL --}}
    @if($profile->published)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-800">Your public profile is live</p>
                    <p class="text-sm text-blue-600 mt-1">{{ $profile->getUrl() }}</p>
                </div>
                <button onclick="navigator.clipboard.writeText('{{ $profile->getUrl() }}')" class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                    Copy Link
                </button>
            </div>
        </div>
    @endif

    {{-- Profile Form --}}
    <form action="{{ route('growth.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- Basic Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Basic Information</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="slug" class="block text-sm font-medium text-slate-700 mb-1">Profile URL Slug</label>
                    <div class="flex items-center">
                        <span class="text-slate-400 text-sm mr-1">{{ url('/t') }}/</span>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $profile->slug) }}" required
                            class="flex-1 border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror"
                            pattern="[a-z0-9\-]+" title="Lowercase letters, numbers, and hyphens only">
                    </div>
                    @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Display Name</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $profile->title) }}"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="sm:col-span-2">
                    <label for="headline" class="block text-sm font-medium text-slate-700 mb-1">Headline / Tagline</label>
                    <input type="text" name="headline" id="headline" value="{{ old('headline', $profile->headline) }}"
                        placeholder="e.g., Mathematics Teacher | 10+ Years Experience"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="sm:col-span-2">
                    <label for="bio" class="block text-sm font-medium text-slate-700 mb-1">Bio</label>
                    <textarea name="bio" id="bio" rows="4"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('bio', $profile->bio) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Photos --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Photos</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="photo" class="block text-sm font-medium text-slate-700 mb-1">Profile Photo</label>
                    @if($profile->photo)
                        <img src="{{ asset('storage/' . $profile->photo) }}" class="w-20 h-20 rounded-full object-cover mb-2">
                    @endif
                    <input type="file" name="photo" id="photo" accept="image/*"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="cover_photo" class="block text-sm font-medium text-slate-700 mb-1">Cover Photo</label>
                    @if($profile->cover_photo)
                        <img src="{{ asset('storage/' . $profile->cover_photo) }}" class="w-full h-24 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Details</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="location" class="block text-sm font-medium text-slate-700 mb-1">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $profile->location) }}"
                        placeholder="e.g., Cairo, Egypt"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="experience_years" class="block text-sm font-medium text-slate-700 mb-1">Years of Experience</label>
                    <input type="number" name="experience_years" id="experience_years" value="{{ old('experience_years', $profile->experience_years) }}"
                        min="0" max="50"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="specializations" class="block text-sm font-medium text-slate-700 mb-1">Specializations</label>
                    <input type="text" name="specializations" id="specializations"
                        value="{{ old('specializations', $profile->specializations ? implode(', ', $profile->specializations) : '') }}"
                        placeholder="Comma separated: Mathematics, Physics"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="teaching_levels" class="block text-sm font-medium text-slate-700 mb-1">Teaching Levels</label>
                    <input type="text" name="teaching_levels" id="teaching_levels"
                        value="{{ old('teaching_levels', $profile->teaching_levels ? implode(', ', $profile->teaching_levels) : '') }}"
                        placeholder="Comma separated: Primary, Secondary"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Delivery Modes</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="delivery_modes[]" value="online" {{ in_array('online', old('delivery_modes', $profile->delivery_modes ?? [])) ? 'checked' : '' }}
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-slate-700">Online</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="delivery_modes[]" value="offline" {{ in_array('offline', old('delivery_modes', $profile->delivery_modes ?? [])) ? 'checked' : '' }}
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-slate-700">In-Person</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="delivery_modes[]" value="both" {{ in_array('both', old('delivery_modes', $profile->delivery_modes ?? [])) ? 'checked' : '' }}
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-slate-700">Both</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Social Links --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Social Links</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="social_whatsapp" class="block text-sm font-medium text-slate-700 mb-1">WhatsApp Number</label>
                    <input type="text" name="social_links[whatsapp]" id="social_whatsapp"
                        value="{{ old('social_links.whatsapp', $profile->social_links['whatsapp'] ?? '') }}"
                        placeholder="+201234567890"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="social_facebook" class="block text-sm font-medium text-slate-700 mb-1">Facebook URL</label>
                    <input type="url" name="social_links[facebook]" id="social_facebook"
                        value="{{ old('social_links.facebook', $profile->social_links['facebook'] ?? '') }}"
                        placeholder="https://facebook.com/..."
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="social_instagram" class="block text-sm font-medium text-slate-700 mb-1">Instagram URL</label>
                    <input type="url" name="social_links[instagram]" id="social_instagram"
                        value="{{ old('social_links.instagram', $profile->social_links['instagram'] ?? '') }}"
                        placeholder="https://instagram.com/..."
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">SEO Settings</h2>

            <div class="space-y-4">
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-slate-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $profile->meta_title) }}"
                        placeholder="Leave empty for auto-generated title"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="meta_description" class="block text-sm font-medium text-slate-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="2"
                        placeholder="Leave empty for auto-generated description"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">{{ old('meta_description', $profile->meta_description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                Save Profile
            </button>

            @if($profile->published)
                <form action="{{ route('growth.profile.unpublish') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm hover:bg-slate-200 transition-colors">
                        Unpublish Profile
                    </button>
                </form>
            @endif
        </div>
    </form>

    {{-- Publish CTA --}}
    @if(!$profile->published)
        <div class="mt-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-6 text-white">
            <h3 class="text-lg font-bold mb-2">Ready to go live?</h3>
            <p class="text-blue-100 text-sm mb-4">Publish your profile to start attracting students. You can update it anytime.</p>
            <form action="{{ route('growth.profile.publish') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-6 py-2.5 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition-colors">
                    Publish My Profile
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
