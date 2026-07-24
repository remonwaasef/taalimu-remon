@extends('layouts.app-next')

@section('title', 'Student Profile & Parent Access')

@section('sidebar')
    <x-ui.sidebar brandName="Student Campus">
        <div class="space-y-1">
            <a href="{{ route('campus.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-home w-4 text-center"></i>
                <span>My Campus</span>
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Learning & Parent Portal</div>

            <a href="{{ route('campus.courses.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-book-open w-4 text-center"></i>
                <span>My Courses</span>
            </a>

            <a href="{{ route('campus.attendance') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-user-check w-4 text-center"></i>
                <span>Attendance Record</span>
            </a>

            <a href="{{ route('campus.finances') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-wallet w-4 text-center"></i>
                <span>Finances & Fees</span>
            </a>

            <a href="{{ route('campus.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-brand-primary bg-brand-50 dark:bg-brand-900/30">
                <i class="fas fa-user-shield w-4 text-center"></i>
                <span>Profile & Parent Info</span>
            </a>
        </div>
    </x-ui.sidebar>
@endsection

@section('content')
    <x-ui.page-header
        title="Student Profile & Parent Information"
        subtitle="Manage student account credentials, contact phone numbers, and parent communication channels."
    />

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Avatar & Status Card (4 cols) -->
        <div class="lg:col-span-4">
            <x-ui.card class="text-center">
                <div class="mb-4 inline-block">
                    <x-ui.avatar :name="$student->name" size="xl" status="online" />
                </div>

                <h3 class="text-lg font-extrabold text-slate-900 dark:text-slate-100 font-inter">{{ $student->name }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ $student->grade_level_name ?? 'Active Student' }}</p>

                <div class="mt-4 inline-block">
                    <x-ui.badge variant="success" size="md" dot="true">Enrolled Student</x-ui.badge>
                </div>

                <div class="mt-6 pt-4 border-t border-brand-border dark:border-slate-800 text-start space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400">Student ID:</span>
                        <span class="font-mono font-bold text-slate-800 dark:text-slate-200">STD-{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400">Status:</span>
                        <span class="font-semibold text-emerald-600">Active</span>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Personal & Parent Information Details (8 cols) -->
        <div class="lg:col-span-8">
            <x-ui.card title="Personal & Parent Contact Details" subtitle="Verified information for center communication">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Full Name</label>
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-sm font-bold text-slate-900 dark:text-slate-100">
                            {{ $student->name }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-sm font-bold text-slate-900 dark:text-slate-100">
                            {{ $student->email }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Student Phone</label>
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-sm font-mono font-bold text-slate-900 dark:text-slate-100">
                            {{ $student->phone ?? 'N/A' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Parent Phone (WhatsApp Alerts)</label>
                        <div class="p-3.5 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200/60 dark:border-emerald-800/40 text-sm font-mono font-bold text-emerald-700 dark:text-emerald-300 flex items-center justify-between">
                            <span>{{ $student->parent_phone ?? 'N/A' }}</span>
                            <i class="fab fa-whatsapp text-emerald-600 text-base"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Grade Level</label>
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-sm font-bold text-slate-900 dark:text-slate-100">
                            {{ $student->grade_level_name ?? 'General Grade' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Student Code</label>
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-sm font-mono font-bold text-brand-primary">
                            STD-{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection
