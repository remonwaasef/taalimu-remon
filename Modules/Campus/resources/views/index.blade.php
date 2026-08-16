@extends('layouts.app-next')

@section('title', 'Student Campus')

@section('sidebar')
    <x-ui.sidebar brandName="Student Campus">
        <div class="space-y-1">
            <a href="{{ route('campus.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-brand-primary bg-brand-50 dark:bg-brand-900/30">
                <i class="fas fa-home w-4 text-center"></i>
                <span>My Campus</span>
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Learning</div>

            <a href="{{ route('campus.courses.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-book-open w-4 text-center"></i>
                <span>My Courses</span>
            </a>

            <a href="{{ route('campus.schedule') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-calendar-alt w-4 text-center"></i>
                <span>Class Schedule</span>
            </a>

            <a href="{{ route('campus.attendance') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-user-check w-4 text-center"></i>
                <span>Attendance Record</span>
            </a>

            <a href="{{ route('campus.finances') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-wallet w-4 text-center"></i>
                <span>Finances & Fees</span>
            </a>

            <a href="{{ route('campus.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-user-circle w-4 text-center"></i>
                <span>My Profile</span>
            </a>
        </div>
    </x-ui.sidebar>
@endsection

@section('content')
    <x-ui.page-header
        title="Welcome back, {{ explode(' ', auth()->user()->name)[0] }}! ðŸš€"
        subtitle="Continue your learning journey and achieve your academic goals today."
    >
        @if(isset($nextLesson) && $nextLesson)
            <x-slot name="actions">
                <x-ui.button variant="primary" icon="fas fa-play" size="md" href="{{ route('center.courses.player', ['course' => $lastEnrollment->course_id, 'lesson' => $nextLesson->id]) }}">
                    Continue: {{ $nextLesson->title }}
                </x-ui.button>
            </x-slot>
        @endif
    </x-ui.page-header>

    <!-- Student Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8 motion-stagger">
        <x-ui.stats-card
            title="Earned Points"
            value="{{ number_format($points ?? 0) }}"
            change="Rank #{{ $rank ?? '-' }}"
            changeType="positive"
            changeLabel="leaderboard position"
            icon="fas fa-star"
            iconColor="text-amber-500 bg-amber-50"
        />

        <x-ui.stats-card
            title="Enrolled Courses"
            value="{{ number_format($enrollments->count()) }}"
            change="Active"
            changeType="positive"
            changeLabel="registered courses"
            icon="fas fa-book-open"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="Attendance Score"
            value="98%"
            change="Excellent"
            changeType="positive"
            changeLabel="class attendance"
            icon="fas fa-user-check"
            iconColor="text-emerald-600 bg-emerald-50"
        />
    </div>

    <!-- Portal Shortcuts Bar -->
    <div class="mb-8">
        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 font-inter">Quick Services Portal</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('campus.courses.index') }}" class="p-4 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-primary flex items-center justify-center text-lg mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-book-open"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">My Courses</span>
                <span class="text-[10px] text-slate-400 mt-0.5">Explore Library</span>
            </a>

            <a href="{{ route('campus.schedule') }}" class="p-4 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950/30 text-sky-600 flex items-center justify-center text-lg mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">Class Schedule</span>
                <span class="text-[10px] text-slate-400 mt-0.5">Lesson Timings</span>
            </a>

            <a href="{{ route('campus.attendance') }}" class="p-4 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 flex items-center justify-center text-lg mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-check"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">Attendance Record</span>
                <span class="text-[10px] text-slate-400 mt-0.5">Detailed Logs</span>
            </a>

            <a href="{{ route('campus.finances') }}" class="p-4 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/30 text-amber-600 flex items-center justify-center text-lg mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-wallet"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">Finances & Fees</span>
                <span class="text-[10px] text-slate-400 mt-0.5">Manage Tuition</span>
            </a>
        </div>
    </div>

    <!-- Enrolled Courses Cards Grid -->
    <x-ui.card title="My Registered Courses ðŸ“š" subtitle="Active courses enrolled in your center" noPadding="true" class="mb-8">
        @if(count($enrollments) > 0)
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($enrollments as $enrollment)
                    @php $course = $enrollment->course; @endphp
                    @if($course)
                        <div class="rounded-2xl border border-brand-border dark:border-slate-800 overflow-hidden bg-white dark:bg-slate-900 hover:shadow-md transition-all group flex flex-col justify-between">
                            <div>
                                <div class="h-36 bg-slate-100 dark:bg-slate-800 relative overflow-hidden flex items-center justify-center">
                                    @if($course->image)
                                        <img src="{{ asset('storage/' . $course->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-brand-primary/20 to-purple-600/20 flex items-center justify-center">
                                            <i class="fas fa-book-open text-3xl text-brand-primary opacity-40"></i>
                                        </div>
                                    @endif
                                    <div class="absolute top-3 end-3">
                                        <x-ui.badge variant="success" size="sm">{{ $enrollment->status_label ?? 'Active' }}</x-ui.badge>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <h4 class="font-bold text-sm text-slate-900 dark:text-slate-100 line-clamp-1">{{ $course->title }}</h4>
                                    <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $course->description ?? 'No course description available.' }}</p>
                                </div>
                            </div>

                            <div class="p-4 pt-0">
                                <x-ui.button variant="outline" size="sm" class="w-full" icon="fas fa-play" href="{{ route('center.courses.player', ['course' => $course->id]) }}">
                                    Start Learning
                                </x-ui.button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <x-ui.empty-state
                title="No Courses Enrolled"
                description="Explore the center course catalog and enroll in your first course."
                icon="fas fa-book-open"
            >
                <x-slot name="action">
                    <x-ui.button variant="primary" icon="fas fa-compass" href="{{ route('campus.courses.index') }}">
                        Browse Catalog
                    </x-ui.button>
                </x-slot>
            </x-ui.empty-state>
        @endif
    </x-ui.card>
@endsection
