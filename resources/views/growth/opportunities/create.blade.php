<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('New Opportunity') }}</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">{{ __('New Opportunity') }}</h1>
        <p class="text-slate-500 dark:text-slate-400 mb-8">{{ __('Aggregate current demand into a scored opportunity') }}</p>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-xl" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6">
            <form method="POST" action="{{ route('growth.opportunities.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="subject" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Subject') }} <span class="text-red-500">*</span></label>
                    <input type="text" id="subject" name="subject" list="subject-list" value="{{ old('subject') }}" required
                           placeholder="{{ __('e.g. Mathematics') }}"
                           class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white @error('subject') border-red-500 @enderror">
                    <datalist id="subject-list">
                        @foreach($subjects as $subject)
                            <option value="{{ $subject }}"></option>
                        @endforeach
                    </datalist>
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="level" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Level') }}</label>
                    <input type="text" id="level" name="level" value="{{ old('level') }}" placeholder="{{ __('e.g. High School') }}"
                           class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('growth.opportunities.index') }}" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors font-medium">{{ __('Cancel') }}</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">{{ __('Generate Opportunity') }}</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
