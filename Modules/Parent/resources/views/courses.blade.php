@extends('layouts.app-next')

@section('title', 'دورات الأبناء')

@section('sidebar')
    @include('parent::partials.sidebar', ['active' => 'courses'])
@endsection

@section('content')
    <x-ui.page-header title="دورات الأبناء 📚" subtitle="كل الدورات المسجلة لأبنائك مع تقدمهم الدراسي." />

    @if($children->count() === 0)
        <x-ui.card>
            <x-ui.empty-state title="لا يوجد أبناء مرتبطون" description="تواصل مع إدارة المركز لربط أبنائك بحسابك." icon="fas fa-users-slash" />
        </x-ui.card>
    @else
        @foreach($children as $student)
            <x-ui.card title="{{ $student->name }}" subtitle="{{ $student->grade?->name ?? '' }}" class="mb-6">
                @php $enrollments = $student->enrollments; @endphp
                @if($enrollments->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($enrollments as $enrollment)
                            @php $course = $enrollment->course; @endphp
                            @if($course)
                                <div class="rounded-2xl border border-brand-border dark:border-slate-800 overflow-hidden bg-white dark:bg-slate-900">
                                    <div class="h-28 bg-slate-100 dark:bg-slate-800 relative overflow-hidden flex items-center justify-center">
                                        @if($course->image)
                                            <img src="{{ asset('storage/'.$course->image) }}" class="w-full h-full object-cover" />
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-brand-primary/20 to-purple-600/20 flex items-center justify-center">
                                                <i class="fas fa-book-open text-2xl text-brand-primary opacity-40"></i>
                                            </div>
                                        @endif
                                        <div class="absolute top-3 end-3">
                                            <x-ui.badge variant="success" size="sm">{{ $enrollment->status_label ?? $enrollment->status }}</x-ui.badge>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-bold text-sm text-slate-900 dark:text-slate-100 line-clamp-1">{{ $course->title }}</h4>
                                        <p class="text-[11px] text-slate-400 mt-1 line-clamp-2">{{ $course->description ?? '' }}</p>
                                        <div class="mt-3 flex items-center justify-between">
                                            <span class="text-[11px] text-slate-400 font-semibold">التقدم: {{ round($enrollment->progress ?? 0) }}%</span>
                                            @if($enrollment->progress < 100)
                                                <x-ui.button variant="outline" size="sm" icon="fas fa-play" href="{{ route('center.courses.player', ['course' => $course->id]) }}">
                                                    متابعة
                                                </x-ui.button>
                                            @else
                                                <x-ui.badge variant="primary" size="sm"><i class="fas fa-check me-1"></i> مكتملة</x-ui.badge>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <x-ui.empty-state title="لا توجد دورات" description="{{ $student->name }} غير مسجل في أي دورة حالياً." icon="fas fa-book-open" />
                @endif
            </x-ui.card>
        @endforeach
    @endif
@endsection