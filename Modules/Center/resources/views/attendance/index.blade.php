@extends('center::layouts.app-next')

@section('page-title', __('center::attendance.management'))
@section('page-subtitle', __('center::attendance.subtitle'))

@section('panel-content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Today's Sessions -->
        <div class="lg:col-span-2">
            <x-ui.card :noPadding="true">
                <x-slot name="header">
                    <div class="flex items-center justify-between w-full">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-900/30 text-brand-primary dark:text-brand-300 flex items-center justify-center text-sm">
                                <i class="fas fa-history"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 m-0">
                                {{ __('center::attendance.today_sessions') }} <span class="text-xs text-slate-400 font-normal">({{ now()->translatedFormat('Y-m-d') }})</span>
                            </h3>
                        </div>
                    </div>
                </x-slot>

                <div class="overflow-x-auto">
                    <table class="w-full text-start text-sm border-collapse">
                        <thead class="bg-slate-50/95 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 text-[11px] uppercase font-bold text-slate-600 dark:text-slate-300 tracking-wider font-inter">
                            <tr>
                                <th class="px-6 py-3.5 text-start">{{ __('center::attendance.session_course') }}</th>
                                <th class="px-6 py-3.5 text-start">{{ __('center::attendance.instructor_classroom') }}</th>
                                <th class="px-6 py-3.5 text-center">{{ __('center::attendance.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 font-inter">
                            @forelse($todaySessions as $session)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900 dark:text-slate-100 text-sm">{{ $session->course->title }}</div>
                                        <span class="inline-flex items-center gap-1 mt-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-primary dark:bg-brand-900/30 dark:text-brand-300 border border-brand-200/50">
                                            <i class="far fa-clock text-[10px]"></i>
                                            {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-600 dark:text-slate-300">
                                        <div class="font-medium flex items-center gap-1.5 mb-1">
                                            <i class="fas fa-chalkboard-teacher text-slate-400 text-xs"></i>
                                            <span>{{ $session->instructor->name ?? __('center::attendance.unassigned_instructor') }}</span>
                                        </div>
                                        <div class="text-slate-400 flex items-center gap-1.5">
                                            <i class="fas fa-door-open text-slate-400 text-xs"></i>
                                            <span>{{ $session->classroom->name ?? __('center::schedules.classroom') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $sessionEnd = \Carbon\Carbon::parse($session->end_time);
                                            $isEnded = now()->isAfter($sessionEnd);
                                        @endphp
                                        <div class="flex items-center justify-center gap-2">
                                            @if($isEnded)
                                                <a href="{{ route('center.attendance.show', $session) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 transition-colors">
                                                    <i class="fas fa-user-times text-xs"></i>
                                                    <span>{{ __('center::attendance.view_absentees') }}</span>
                                                </a>
                                            @else
                                                <a href="{{ route('center.attendance.show', $session) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:text-brand-primary bg-white hover:bg-brand-50 border border-slate-200 hover:border-brand-primary/30 shadow-2xs transition-colors">
                                                    <i class="fas fa-clipboard-check text-xs"></i>
                                                    <span>{{ __('center::attendance.mark_attendance') }}</span>
                                                </a>
                                                <a href="{{ route('center.attendance.qr', $session) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-white bg-brand-primary hover:bg-brand-600 shadow-2xs transition-colors">
                                                    <i class="fas fa-qrcode text-xs"></i>
                                                    <span>{{ __('center::attendance.qr_code_btn') }}</span>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-slate-400 dark:text-slate-500 text-xs">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-2 text-slate-400">
                                            <i class="fas fa-calendar-times text-lg"></i>
                                        </div>
                                        <span>{{ __('center::attendance.no_sessions_today') }}</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($todaySessions->hasPages())
                    <div class="px-6 py-3.5 border-t border-slate-200 dark:border-slate-800">
                        {{ $todaySessions->links() }}
                    </div>
                @endif
            </x-ui.card>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-1">
            <x-ui.card :noPadding="true">
                <x-slot name="header">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-sm">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 m-0">
                            {{ __('center::attendance.recent_activity') }}
                        </h3>
                    </div>
                </x-slot>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/80 font-inter max-h-[500px] overflow-y-auto">
                    @forelse($recentAttendance as $record)
                        <div class="p-4 flex items-center gap-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs shrink-0 {{ $record->status == 'present' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-300' : ($record->status == 'late' ? 'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-300' : 'bg-red-50 text-red-600 dark:bg-red-950/50 dark:text-red-300') }}">
                                <i class="fas {{ $record->status == 'present' ? 'fa-user-check' : ($record->status == 'late' ? 'fa-user-clock' : 'fa-user-times') }}"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-xs text-slate-800 dark:text-slate-200 truncate">{{ $record->student->name }}</div>
                                <div class="text-[11px] text-slate-400 truncate mt-0.5">{{ $record->course->title }}</div>
                                <span class="text-[10px] text-slate-400 block mt-0.5">{{ $record->check_in_time->diffForHumans() }}</span>
                            </div>
                            <div class="shrink-0">
                                @if($record->status == 'present')
                                    <x-ui.badge variant="success" size="sm">{{ __('center::attendance.present') }}</x-ui.badge>
                                @elseif($record->status == 'late')
                                    <x-ui.badge variant="warning" size="sm">{{ __('center::attendance.late') }}</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger" size="sm">{{ __('center::attendance.absent') }}</x-ui.badge>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-slate-400 dark:text-slate-500">
                            <i class="fas fa-inbox text-2xl mb-2 block text-slate-300 dark:text-slate-600"></i>
                            {{ __('center::attendance.no_recent_activity') }}
                        </div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection
