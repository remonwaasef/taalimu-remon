@extends('layouts.app-next')

@section('title', 'الحضور والغياب')

@section('sidebar')
    @include('parent::partials.sidebar', ['active' => 'attendance'])
@endsection

@section('content')
    <x-ui.page-header title="الحضور والغياب 📋" subtitle="آخر سجلات الحضور لأبنائك في جميع الحصص." />

    <x-ui.card title="سجل الحضور" subtitle="أحدث التسجيلات أولاً">
        @if($attendances->isEmpty())
            <x-ui.empty-state title="لا توجد سجلات حضور بعد" description="ستظهر سجلات الحضور هنا بمجرد بدء الحصص." icon="fas fa-clipboard-list" />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-right">
                    <thead>
                        <tr class="text-[11px] text-slate-400 border-b border-brand-border dark:border-slate-800">
                            <th class="py-3 px-3 font-semibold">الطالب</th>
                            <th class="py-3 px-3 font-semibold">الحصة / الدورة</th>
                            <th class="py-3 px-3 font-semibold">التاريخ</th>
                            <th class="py-3 px-3 font-semibold">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr class="border-b border-brand-border/50 dark:border-slate-800/50 last:border-0">
                                <td class="py-3 px-3 font-semibold text-slate-700 dark:text-slate-200">{{ $attendance->student?->name }}</td>
                                <td class="py-3 px-3 text-slate-500 dark:text-slate-400">{{ $attendance->course?->title }}</td>
                                <td class="py-3 px-3 text-slate-500 dark:text-slate-400">{{ $attendance->date->format('Y-m-d') }}</td>
                                <td class="py-3 px-3">
                                    @php
                                        $statusColors = [
                                            'present' => 'success',
                                            'late' => 'warning',
                                            'absent' => 'danger',
                                            'excused' => 'primary',
                                        ];
                                        $statusLabels = [
                                            'present' => 'حاضر',
                                            'late' => 'متأخر',
                                            'absent' => 'غائب',
                                            'excused' => 'معذور',
                                        ];
                                    @endphp
                                    <x-ui.badge variant="{{ $statusColors[$attendance->status] ?? 'secondary' }}" size="sm">
                                        {{ $statusLabels[$attendance->status] ?? $attendance->status }}
                                    </x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $attendances->links() }}
            </div>
        @endif
    </x-ui.card>
@endsection