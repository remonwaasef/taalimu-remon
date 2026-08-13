@extends('layouts.app-next')

@section('title', __('instructor::students.title') ?? 'دليل وقائمة الطلاب')

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'students'])
@endsection

@section('content')
    <x-ui.page-header
        title="{{ __('instructor::students.title') ?? 'دليل وقائمة الطلاب' }}"
        subtitle="{{ __('instructor::students.subtitle') ?? 'إدارة قائمة الطلاب، والمجموعات المسجلة، وطرق التواصل.' }}"
    >
        <x-slot name="actions">
            <x-ui.button variant="outline" icon="fas fa-file-import" size="md" data-bs-toggle="modal" data-bs-target="#importModal">
                {{ __('instructor::students.import') ?? 'استيراد طلاب' }}
            </x-ui.button>
            <x-ui.button variant="outline" icon="fas fa-file-export" size="md" href="{{ route('instructor.students.export') }}">
                {{ __('instructor::students.export') ?? 'تصدير القائمة' }}
            </x-ui.button>
            <x-ui.button variant="primary" icon="fas fa-user-plus" size="md" href="{{ route('instructor.students.create') }}">
                {{ __('instructor::students.add_new') ?? 'إضافة طالب جديد' }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- Students Overview Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <x-ui.stats-card
            title="{{ __('instructor::students.total_students') ?? 'إجمالي الطلاب' }}"
            value="{{ number_format($students->count()) }}"
            change="نشط"
            changeType="positive"
            icon="fas fa-user-graduate"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::students.currently_enrolled') ?? 'الاشتراكات الفعالة' }}"
            value="{{ number_format($students->sum(fn($s) => $s->enrollments->count())) }}"
            change="مجموعة"
            changeType="positive"
            icon="fas fa-layer-group"
            iconColor="text-emerald-600 bg-emerald-50"
        />

        @php
            $totalRevenue = \App\Models\Sale::whereIn('student_id', $students->pluck('id'))->sum('paid_amount');
            $todayEnrollments = $students->filter(fn($s) => $s->created_at?->isToday())->count();
        @endphp

        <x-ui.stats-card
            title="{{ __('instructor::students.total_collected') ?? 'المبالغ المحصلة' }}"
            value="{{ number_format($totalRevenue, 0) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}"
            change="تحصيل"
            changeType="positive"
            icon="fas fa-wallet"
            iconColor="text-amber-600 bg-amber-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::students.registered_today') ?? 'المسجلون اليوم' }}"
            value="{{ number_format($todayEnrollments) }}"
            change="اليوم"
            changeType="neutral"
            icon="fas fa-user-plus"
            iconColor="text-sky-600 bg-sky-50"
        />
    </div>

    <!-- Student Table Card Component -->
    <x-ui.card noPadding="true" class="mb-8">
        @if($students->count() > 0)
            <x-ui.table :headers="[__('instructor::students.student') ?? 'الطالب', __('instructor::students.parent_phone') ?? 'هاتف ولي الأمر', 'المجموعات المسجلة', 'الإجراءات']">
                @foreach($students as $student)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$student->name" size="md" />
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900 dark:text-slate-100">{{ $student->name }}</h4>
                                    <p class="text-xs text-slate-400 font-mono">{{ $student->phone ?? 'لا يوجد هاتف' }}</p>
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
                                    <span class="text-xs text-slate-400">غير مسجل في مجموعة</span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-6 py-4 text-end">
                            <x-ui.button variant="outline" size="sm" icon="fas fa-eye" href="{{ route('instructor.students.show', $student->id) }}">
                                عرض
                            </x-ui.button>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state
                title="لا يوجد طلاب مسجلون حتى الآن"
                description="ابدأ بإضافة أول طالب أو استيراد قائمة الطلاب."
                icon="fas fa-user-graduate"
            >
                <x-slot name="action">
                    <x-ui.button variant="primary" icon="fas fa-user-plus" href="{{ route('instructor.students.create') }}">
                        إضافة طالب جديد
                    </x-ui.button>
                </x-slot>
            </x-ui.empty-state>
        @endif
    </x-ui.card>
@endsection
