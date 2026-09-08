<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seoData['title'] }}</title>
    <meta name="description" content="{{ $seoData['description'] }}">
    <meta property="og:title" content="{{ $seoData['title'] }}">
    <meta property="og:description" content="{{ $seoData['description'] }}">
    <link rel="canonical" href="{{ $seoData['canonical'] }}">
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-6">
            <a href="{{ route('growth.teacher.show', $profile->slug) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">&larr; {{ __('Back to Profile') }}</a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <h1 class="text-3xl font-bold text-slate-900">{{ __('Request a Program') }}</h1>
                <p class="text-slate-500 mt-2">{{ __('Can not find what you are looking for? Tell us what you need and we will do our best to help.') }}</p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('growth.demand.store', $profile->slug) }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Your Name') }} *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Phone') }}</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Subject / Program Interest') }} *</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ __('e.g. Mathematics, English, IELTS Prep') }}">
                        @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Level') }}</label>
                            <input type="text" name="level" value="{{ old('level') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ __('e.g. Grade 10, Beginner') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Delivery Mode') }}</label>
                            <select name="delivery_mode" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Any') }}</option>
                                <option value="online" {{ old('delivery_mode') === 'online' ? 'selected' : '' }}>{{ __('Online') }}</option>
                                <option value="offline" {{ old('delivery_mode') === 'offline' ? 'selected' : '' }}>{{ __('Offline') }}</option>
                                <option value="both" {{ old('delivery_mode') === 'both' ? 'selected' : '' }}>{{ __('Both') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Preferred Days') }}</label>
                            <input type="text" name="preferred_days" value="{{ old('preferred_days') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ __('e.g. Saturday, Sunday') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Preferred Time') }}</label>
                            <input type="text" name="preferred_time" value="{{ old('preferred_time') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ __('e.g. Evening, 4-6 PM') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Location') }}</label>
                            <input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Budget Range') }}</label>
                            <input type="text" name="budget_range" value="{{ old('budget_range') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ __('e.g. 500-1000 EGP') }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Additional Message') }}</label>
                        <textarea name="message" rows="3" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ __('Tell us more about what you need...') }}">{{ old('message') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="w-full mt-6 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                    {{ __('Submit Request') }}
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white border-t border-slate-100 py-6 text-center mt-12">
        <p class="text-sm text-slate-400">Powered by <span class="font-semibold text-slate-600">Taalimu</span></p>
    </div>
</body>
</html>
