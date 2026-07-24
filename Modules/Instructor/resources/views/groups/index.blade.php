@extends('layouts.app-next')

@section('title', __('instructor::groups.title') ?? 'Study Groups')

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'groups'])
@endsection

@section('content')
    <x-ui.page-header
        title="{{ __('instructor::groups.title') ?? 'Study Groups' }}"
        subtitle="{{ __('instructor::groups.subtitle') ?? 'Manage active groups, registration links, and session schedules.' }}"
    >
        <x-slot name="actions">
            <x-ui.button variant="primary" icon="fas fa-plus" size="md" href="{{ route('instructor.groups.create') }}">
                {{ __('instructor::groups.create_new') }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <x-ui.card noPadding="true" class="mb-8">
        @if($courses->count() > 0)
            <x-ui.table :headers="[__('instructor::groups.table_group'), __('instructor::groups.students_count'), __('instructor::groups.registration_link'), __('instructor::groups.status'), __('instructor::groups.actions')]">
                @foreach($courses as $course)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100">
                            <div class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-1">{{ $course->title }}</div>
                            <div class="flex flex-wrap gap-1">
                                @forelse($course->schedules as $schedule)
                                    <x-ui.badge variant="brand" size="sm">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                    </x-ui.badge>
                                @empty
                                    <span class="text-[11px] text-slate-400">No schedule assigned</span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <x-ui.badge variant="brand" size="sm">{{ $course->enrollments_count ?? 0 }} enrolled</x-ui.badge>
                        </td>

                        <td class="px-6 py-4">
                            @if($course->registration_token)
                                <div class="flex items-center gap-2 max-w-xs">
                                    <input type="text" class="h-8 px-3 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg w-full text-slate-600 dark:text-slate-300 font-mono" value="{{ $course->getRegistrationUrl() }}" id="group_link_{{ $course->id }}" readonly />
                                    <x-ui.button variant="secondary" size="sm" onclick="navigator.clipboard.writeText(document.getElementById('group_link_{{ $course->id }}').value)">
                                        <i class="fas fa-copy"></i>
                                    </x-ui.button>
                                </div>
                            @else
                                <span class="text-xs text-slate-400">No registration link</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <x-ui.badge variant="success" size="sm" dot="true">Active</x-ui.badge>
                        </td>

                        <td class="px-6 py-4 text-end">
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.button variant="outline" size="sm" icon="fas fa-qrcode" href="{{ route('instructor.scanner', $course->id) }}">
                                    Scanner
                                </x-ui.button>
                                <x-ui.button variant="ghost" size="sm" icon="fas fa-edit" href="{{ route('instructor.groups.edit', $course->id) }}" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state
                title="No Groups Created"
                description="Get started by creating your first study group to invite students."
                icon="fas fa-users"
            >
                <x-slot name="action">
                    <x-ui.button variant="primary" icon="fas fa-plus" href="{{ route('instructor.groups.create') }}">
                        Create First Group
                    </x-ui.button>
                </x-slot>
            </x-ui.empty-state>
        @endif
    </x-ui.card>
@endsection
