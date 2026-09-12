<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Group') }} — {{ $details['course']?->title }}</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <a href="{{ route('growth.opportunities.show', $opportunity) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 mb-4">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('Back to opportunity') }}
        </a>

        <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $details['course']?->title }}</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">{{ __('Teacher') }}: {{ $details['teacher']?->name ?? '—' }}</p>
            </div>
            @include('growth.partials.status-badge', ['status' => $group->status])
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-xl" role="alert">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-xl" role="alert">{{ $errors->first() }}</div>
        @endif

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-4">
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Enrolled') }}</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $details['enrolled_count'] }}<span class="text-sm text-slate-400">/{{ $details['capacity'] }}</span></p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-4">
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Available seats') }}</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $details['available_seats'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-4">
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Fill rate') }}</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $details['fill_percentage'] }}%</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-4 flex items-end">
                @if($group->status === 'forming')
                    <form method="POST" action="{{ route('growth.opportunities.groups.complete', [$opportunity, $group]) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-medium">{{ __('Complete group') }}</button>
                    </form>
                @else
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('This group is complete.') }}</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Students') }}</h2>
                    @if(($details['taalimu_sourced_count'] ?? 0) > 0)
                        <span class="text-sm text-slate-500 dark:text-slate-400">{{ __(':count via Taalimu', ['count' => $details['taalimu_sourced_count']]) }}</span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-start">
                        <thead>
                            <tr class="text-start text-xs uppercase text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                                <th class="py-3 pe-4 font-medium">{{ __('Name') }}</th>
                                <th class="py-3 pe-4 font-medium">{{ __('Email') }}</th>
                                <th class="py-3 pe-4 font-medium">{{ __('Source') }}</th>
                                <th class="py-3 font-medium"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($details['students'] as $student)
                                <tr class="border-b border-slate-100 dark:border-slate-700 last:border-0">
                                    <td class="py-3 pe-4 font-medium text-slate-900 dark:text-white">{{ $student['name'] ?? '—' }}</td>
                                    <td class="py-3 pe-4 text-slate-600 dark:text-slate-300">{{ $student['email'] ?? '—' }}</td>
                                    <td class="py-3 pe-4">
                                        @if($student['is_taalimu_sourced'] ?? false)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                {{ __('Taalimu') }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-end">
                                        <form method="POST" action="{{ route('growth.opportunities.groups.students.destroy', [$opportunity, $group, $student['id']]) }}" onsubmit="return confirm('{{ __('Remove this student?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 text-sm bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-lg hover:bg-red-100 transition-colors font-medium">{{ __('Remove') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-8 text-center text-slate-500 dark:text-slate-400">{{ __('No students yet.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-3">{{ __('Add student') }}</h2>
                @if($group->canAddStudent())
                    <form method="POST" action="{{ route('growth.opportunities.groups.students.store', [$opportunity, $group]) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Student') }} <span class="text-red-500">*</span></label>
                            <select id="student_id" name="student_id" required class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white @error('student_id') border-red-500 @enderror">
                                <option value="">{{ __('Select a student') }}</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">{{ __('Enroll in group') }}</button>
                    </form>
                @else
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('This group cannot accept more students right now.') }}</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
