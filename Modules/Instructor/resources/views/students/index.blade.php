@extends('layouts.app-next')

@section('title', __('instructor::students.title') ?? 'Ø¯Ù„ÙŠÙ„ ÙˆÙ‚Ø§Ø¦Ù…Ø© Ø§Ù„Ø·Ù„Ø§Ø¨')

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'students'])
@endsection

@section('content')
    <x-ui.page-header
        title="{{ __('instructor::students.title') ?? 'Ø¯Ù„ÙŠÙ„ ÙˆÙ‚Ø§Ø¦Ù…Ø© Ø§Ù„Ø·Ù„Ø§Ø¨' }}"
        subtitle="{{ __('instructor::students.subtitle') ?? 'Ø¥Ø¯Ø§Ø±Ø© Ù‚Ø§Ø¦Ù…Ø© Ø§Ù„Ø·Ù„Ø§Ø¨ØŒ ÙˆØ§Ù„Ù…Ø¬Ù…ÙˆØ¹Ø§Øª Ø§Ù„Ù…Ø³Ø¬Ù„Ø©ØŒ ÙˆØ·Ø±Ù‚ Ø§Ù„ØªÙˆØ§ØµÙ„.' }}"
    >
        <x-slot name="actions">
            <x-ui.button variant="outline" icon="fas fa-file-import" size="md" data-bs-toggle="modal" data-bs-target="#importModal">
                {{ __('instructor::students.import') ?? 'Ø§Ø³ØªÙŠØ±Ø§Ø¯ Ø·Ù„Ø§Ø¨' }}
            </x-ui.button>
            <x-ui.button variant="outline" icon="fas fa-file-export" size="md" href="{{ route('instructor.students.export') }}">
                {{ __('instructor::students.export') ?? 'ØªØµØ¯ÙŠØ± Ø§Ù„Ù‚Ø§Ø¦Ù…Ø©' }}
            </x-ui.button>
            <x-ui.button variant="primary" icon="fas fa-user-plus" size="md" href="{{ route('instructor.students.create') }}">
                {{ __('instructor::students.add_new') ?? 'Ø¥Ø¶Ø§ÙØ© Ø·Ø§Ù„Ø¨ Ø¬Ø¯ÙŠØ¯' }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- Students Overview Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 motion-stagger">
        <x-ui.stats-card
            title="{{ __('instructor::students.total_students') ?? 'Ø¥Ø¬Ù…Ø§Ù„ÙŠ Ø§Ù„Ø·Ù„Ø§Ø¨' }}"
            value="{{ number_format($students->count()) }}"
            change="Ù†Ø´Ø·"
            changeType="positive"
            icon="fas fa-user-graduate"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::students.currently_enrolled') ?? 'Ø§Ù„Ø§Ø´ØªØ±Ø§ÙƒØ§Øª Ø§Ù„ÙØ¹Ø§Ù„Ø©' }}"
            value="{{ number_format($students->sum(fn($s) => $s->enrollments->count())) }}"
            change="Ù…Ø¬Ù…ÙˆØ¹Ø©"
            changeType="positive"
            icon="fas fa-layer-group"
            iconColor="text-emerald-600 bg-emerald-50"
        />

        @php
            $totalRevenue = \App\Models\Sale::whereIn('student_id', $students->pluck('id'))->sum('paid_amount');
            $todayEnrollments = $students->filter(fn($s) => $s->created_at?->isToday())->count();
        @endphp

        <x-ui.stats-card
            title="{{ __('instructor::students.total_collected') ?? 'Ø§Ù„Ù…Ø¨Ø§Ù„Øº Ø§Ù„Ù…Ø­ØµÙ„Ø©' }}"
            value="{{ number_format($totalRevenue, 0) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}"
            change="ØªØ­ØµÙŠÙ„"
            changeType="positive"
            icon="fas fa-wallet"
            iconColor="text-amber-600 bg-amber-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::students.registered_today') ?? 'Ø§Ù„Ù…Ø³Ø¬Ù„ÙˆÙ† Ø§Ù„ÙŠÙˆÙ…' }}"
            value="{{ number_format($todayEnrollments) }}"
            change="Ø§Ù„ÙŠÙˆÙ…"
            changeType="neutral"
            icon="fas fa-user-plus"
            iconColor="text-sky-600 bg-sky-50"
        />
    </div>

    <!-- Student Table Card Component -->
    <x-ui.card noPadding="true" class="mb-8">
        @if($students->count() > 0)
            <x-ui.table :headers="[__('instructor::students.student') ?? 'Ø§Ù„Ø·Ø§Ù„Ø¨', __('instructor::students.parent_phone') ?? 'Ù‡Ø§ØªÙ ÙˆÙ„ÙŠ Ø§Ù„Ø£Ù…Ø±', 'Ø§Ù„Ù…Ø¬Ù…ÙˆØ¹Ø§Øª Ø§Ù„Ù…Ø³Ø¬Ù„Ø©', 'Ø§Ù„Ø¥Ø¬Ø±Ø§Ø¡Ø§Øª']">
                @foreach($students as $student)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors {{ session('highlight_student') == $student->id ? 'flash-row' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$student->name" size="md" />
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900 dark:text-slate-100">{{ $student->name }}</h4>
                                    <p class="text-xs text-slate-400 font-mono">{{ $student->phone ?? 'Ù„Ø§ ÙŠÙˆØ¬Ø¯ Ù‡Ø§ØªÙ' }}</p>
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
                                <span class="text-slate-400">--</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($student->enrollments as $enrollment)
                                    @if($enrollment->course)
                                        <x-ui.badge variant="brand" size="sm">{{ $enrollment->course->title }}</x-ui.badge>
                                    @endif
                                @empty
                                    <span class="text-xs text-slate-400">ØºÙŠØ± Ù…Ø³Ø¬Ù„ ÙÙŠ Ù…Ø¬Ù…ÙˆØ¹Ø©</span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-6 py-4 text-end">
                            <x-ui.button variant="outline" size="sm" icon="fas fa-eye" href="{{ route('instructor.students.show', $student->id) }}">
                                Ø¹Ø±Ø¶
                            </x-ui.button>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state
                title="Ù„Ø§ ÙŠÙˆØ¬Ø¯ Ø·Ù„Ø§Ø¨ Ù…Ø³Ø¬Ù„ÙˆÙ† Ø­ØªÙ‰ Ø§Ù„Ø¢Ù†"
                description="Ø§Ø¨Ø¯Ø£ Ø¨Ø¥Ø¶Ø§ÙØ© Ø£ÙˆÙ„ Ø·Ø§Ù„Ø¨ Ø£Ùˆ Ø§Ø³ØªÙŠØ±Ø§Ø¯ Ù‚Ø§Ø¦Ù…Ø© Ø§Ù„Ø·Ù„Ø§Ø¨."
                icon="fas fa-user-graduate"
            >
                <x-slot name="action">
                    <x-ui.button variant="primary" icon="fas fa-user-plus" href="{{ route('instructor.students.create') }}">
                        Ø¥Ø¶Ø§ÙØ© Ø·Ø§Ù„Ø¨ Ø¬Ø¯ÙŠØ¯
                    </x-ui.button>
                </x-slot>
            </x-ui.empty-state>
        @endif
    </x-ui.card>
@endsection
