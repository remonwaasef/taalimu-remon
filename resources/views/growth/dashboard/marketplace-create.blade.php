<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Create Listing') }} — {{ __('Growth Dashboard') }} — Taalimu</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8">
            <a href="{{ route('growth.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">&larr; {{ __('Back to Dashboard') }}</a>
            <h1 class="text-3xl font-bold text-slate-900 mt-2">{{ __('Create Marketplace Listing') }}</h1>
            <p class="text-slate-500 mt-1">{{ __('Post what you need and let teachers find you') }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            @if($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('growth.marketplace.store') }}">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Subject') }} *</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="{{ __('e.g. Mathematics, English, Physics...') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Level') }}</label>
                        <input type="text" name="level" value="{{ old('level') }}"
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="{{ __('e.g. Grade 12, University, Beginner...') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Description') }}</label>
                        <textarea name="description" rows="4" maxlength="1000"
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="{{ __('Describe what you are looking for...') }}">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Location') }}</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="{{ __('e.g. Cairo, Online, Manhattan...') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Budget Range') }}</label>
                        <input type="text" name="budget_range" value="{{ old('budget_range') }}"
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="{{ __('e.g. $20-40/hour, Negotiable...') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Preferred Schedule') }}</label>
                        <input type="text" name="preferred_schedule" value="{{ old('preferred_schedule') }}"
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="{{ __('e.g. Weekends, Evenings, Flexible...') }}">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('growth.dashboard') }}" class="px-6 py-2.5 text-slate-700 font-medium rounded-xl hover:bg-slate-100 transition-colors">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                            {{ __('Create Listing') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white border-t border-slate-100 py-6 text-center mt-12">
        <p class="text-sm text-slate-400">Powered by <span class="font-semibold text-slate-600">Taalimu</span></p>
    </div>
</body>
</html>
