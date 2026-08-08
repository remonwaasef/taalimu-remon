@extends('layouts.app-next')

@section('title', 'الجدول الدراسي')

@section('sidebar')
    @include('parent::partials.sidebar', ['active' => 'schedule'])
@endsection

@section('content')
    <x-ui.page-header title="الجدول الدراسي 🗓️" subtitle="مواعيد الحصص لأبناءك في كل أيام الأسبوع." />

    @if($schedules->isEmpty())
        <x-ui.card>
            <x-ui.empty-state title="لا توجد حصص مجدولة" description="لم يتم تحديد جدول لأبناءك بعد، أو أنهم غير مسجلين في دورات." icon="fas fa-calendar-times" />
        </x-ui.card>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($days as $dayKey => $dayName)
                @php $daySchedules = $schedules->get($dayKey, collect()); @endphp
                <x-ui.card title="{{ $dayName }}" subtitle="{{ $daySchedules->count() }} {{ $daySchedules->count() == 1 ? 'حصة' : 'حصص' }}" class="mb-0">
                    @if($daySchedules->isEmpty())
                        <div class="py-4 text-center text-xs text-slate-400 font-semibold">لا حصص في هذا اليوم</div>
                    @else
                        <div class="space-y-3">
                            @foreach($daySchedules as $schedule)
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-brand-50 dark:bg-brand-900/30 text-brand-primary flex items-center justify-center text-xs font-bold">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $schedule->course?->title }}</h5>
                                        <p class="text-[11px] text-slate-400 mt-0.5 truncate">
                                            <i class="fas fa-user-tie me-1"></i>{{ $schedule->instructor?->name }}
                                            <span class="mx-1">•</span>
                                            <i class="fas fa-door-open me-1"></i>{{ $schedule->classroom?->name ?? '—' }}
                                        </p>
                                    </div>
                                    <span class="shrink-0 text-[10px] text-slate-400 font-semibold">{{ $schedule->duration_minutes ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-ui.card>
            @endforeach
        </div>
    @endif
@endsection