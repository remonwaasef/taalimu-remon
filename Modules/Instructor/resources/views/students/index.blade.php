@extends('layouts.app-next')

@section('title', __('instructor::students.title') ?? 'Students Directory')

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'students'])
@endsection

@section('content')
    <x-ui.page-header
        title="{{ __('instructor::students.title') ?? 'Students Directory' }}"
        subtitle="{{ __('instructor::students.subtitle') ?? 'Manage student rosters, group assignments, and contact channels.' }}"
    >
        <x-slot name="actions">
            <x-ui.button variant="outline" icon="fas fa-file-import" size="md" data-bs-toggle="modal" data-bs-target="#importModal">
                {{ __('instructor::students.import') }}
            </x-ui.button>
            <x-ui.button variant="outline" icon="fas fa-file-export" size="md" href="{{ route('instructor.students.export') }}">
                {{ __('instructor::students.export') }}
            </x-ui.button>
            <x-ui.button variant="primary" icon="fas fa-user-plus" size="md" href="{{ route('instructor.students.create') }}">
                {{ __('instructor::students.add_new') }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- Students Overview Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <x-ui.stats-card
            title="{{ __('instructor::students.total_students') }}"
            value="{{ number_format($students->count()) }}"
            change="Roster"
            changeType="positive"
            icon="fas fa-user-graduate"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::students.currently_enrolled') }}"
            value="{{ number_format($students->sum(fn($s) => $s->enrollments->count())) }}"
            change="Active"
            changeType="positive"
            icon="fas fa-layer-group"
            iconColor="text-emerald-600 bg-emerald-50"
        />

        @php
            $totalRevenue = \App\Models\Sale::whereIn('student_id', $students->pluck('id'))->sum('paid_amount');
            $todayEnrollments = $students->filter(fn($s) => $s->created_at?->isToday())->count();
        @endphp

        <x-ui.stats-card
            title="{{ __('instructor::students.total_collected') }}"
            value="{{ number_format($totalRevenue, 0) }} EGP"
            change="Collected"
            changeType="positive"
            icon="fas fa-wallet"
            iconColor="text-amber-600 bg-amber-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::students.registered_today') }}"
            value="{{ number_format($todayEnrollments) }}"
            change="Today"
            changeType="neutral"
            icon="fas fa-user-plus"
            iconColor="text-sky-600 bg-sky-50"
        />
    </div>

    <!-- Student Table Card Component -->
    <x-ui.card noPadding="true" class="mb-8">
        @if($students->count() > 0)
            <x-ui.table :headers="[__('instructor::students.student'), __('instructor::students.parent_phone'), 'Enrolled Groups', 'Actions']">
                @foreach($students as $student)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$student->name" size="md" />
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900 dark:text-slate-100">{{ $student->name }}</h4>
                                    <p class="text-xs text-slate-400 font-mono">{{ $student->phone ?? 'No phone' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-xs font-mono text-slate-600 dark:text-slate-300">
                            @if($student->parent_phone)
                                <div class="flex items-center gap-2">
                                    <span>{{ $student->parent_phone }}</span>
                                    <a href="https://api.whatsapp.com/send?phone={{ preg_replace('/[^0-9]/', '', $student->parent_phone) }}" target="_blank" class="text-emerald-600 hover:text-emerald-700">
                                        <i class="fab fa-whatsapp text-sm"></i>
                                    </a>
                                </div>
                            @else
                                <span class="text-slate-400">N/A</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($student->enrollments as $enrollment)
                                    @if($enrollment->course)
                                        <x-ui.badge variant="brand" size="sm">{{ $enrollment->course->title }}</x-ui.badge>
                                    @endif
                                @empty
                                    <span class="text-xs text-slate-400">Not enrolled</span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-6 py-4 text-end">
                            <x-ui.button variant="outline" size="sm" icon="fas fa-edit" href="{{ route('instructor.students.edit', $student->id) }}">
                                Edit
                            </x-ui.button>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state
                title="No Students Found"
                description="Get started by adding your first student or importing a student list."
                icon="fas fa-user-graduate"
            >
                <x-slot name="action">
                    <x-ui.button variant="primary" icon="fas fa-user-plus" href="{{ route('instructor.students.create') }}">
                        Add First Student
                    </x-ui.button>
                </x-slot>
            </x-ui.empty-state>
        @endif
    </x-ui.card>
@endsection
