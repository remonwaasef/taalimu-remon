@extends('layouts.app-next')

@section('title', 'بوابة ولي الأمر')

@section('sidebar')
    @include('parent::partials.sidebar', ['active' => 'index'])
@endsection

@section('content')
    <x-ui.page-header
        title="أهلاً، {{ $guardian->name }} 👋"
        subtitle="تابع مستوى أبنائك الأكاديمي والحضور والمدفوعات من مكان واحد."
    />

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <x-ui.stats-card
            title="عدد الأبناء"
            value="{{ number_format($totalChildren) }}"
            change="متابعة"
            changeType="positive"
            changeLabel="مشترك في متابعتك"
            icon="fas fa-users"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="الدورات المسجلة"
            value="{{ number_format($totalCourses) }}"
            change="دورة"
            changeType="positive"
            changeLabel="لجميع الأبناء"
            icon="fas fa-book-open"
            iconColor="text-sky-600 bg-sky-50"
        />

        <x-ui.stats-card
            title="إجمالي المتبقي"
            value="{{ format_price($totalDebt) }}"
            change="رصيد"
            changeType="{{ $totalDebt > 0 ? 'negative' : 'positive' }}"
            changeLabel="مستحق على الأبناء"
            icon="fas fa-wallet"
            iconColor="text-amber-600 bg-amber-50"
        />
    </div>

    <!-- Children Cards -->
    <x-ui.card title="أبنائي 📚" subtitle="حالة كل ابن وأهم مؤشرات المتابعة" class="mb-8">
        @if($children->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($children as $student)
                    @php $agg = $aggregates[$student->id] ?? []; @endphp
                    <div class="rounded-2xl border border-brand-border dark:border-slate-800 overflow-hidden bg-white dark:bg-slate-900 hover:shadow-md transition-all">
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-11 h-11 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-primary flex items-center justify-center text-base">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-sm text-slate-900 dark:text-slate-100 truncate">{{ $student->name }}</h4>
                                    <p class="text-[11px] text-slate-400">{{ $student->grade?->name ?? '—' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/60 p-3 text-center">
                                    <div class="text-base font-bold text-slate-800 dark:text-slate-200">{{ number_format($agg['enrollments_count'] ?? 0) }}</div>
                                    <div class="text-[10px] text-slate-400 font-semibold">دورات</div>
                                </div>
                                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/60 p-3 text-center">
                                    @if($agg['attendance_rate'])
                                        <div class="text-base font-bold {{ $agg['attendance_rate'] >= 70 ? 'text-emerald-600' : 'text-amber-600' }}">{{ $agg['attendance_rate'] }}%</div>
                                    @else
                                        <div class="text-base font-bold text-slate-400">—</div>
                                    @endif
                                    <div class="text-[10px] text-slate-400 font-semibold">حضور</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between px-1 pb-2">
                                <span class="text-[11px] text-slate-400 font-semibold">المتبقي على الطالب</span>
                                <span class="text-sm font-bold {{ ($agg['total_debt'] ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ format_price($agg['total_debt'] ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-ui.empty-state
                title="لا يوجد أبناء مرتبطون"
                description="لم يتم ربط أي طالب بحسابك بعد. تواصل مع إدارة المركز لربط أبنائك."
                icon="fas fa-users-slash"
            />
        @endif
    </x-ui.card>
@endsection