@extends('center::layouts.app-next')

@section('page-title', __('center::attendance.management'))
@section('page-subtitle', __('center::attendance.subtitle'))

@section('panel-content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Today's Sessions -->
        <div class="lg:col-span-2" x-data="{ sessionFilter: 'all' }">
            <x-ui.card :noPadding="true">
                <x-slot name="header">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 w-full">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-900/30 text-brand-primary dark:text-brand-300 flex items-center justify-center text-sm">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 m-0">
                                    {{ __('center::attendance.today_sessions') }}
                                </h3>
                                <span class="text-xs text-slate-400 font-normal">({{ now()->translatedFormat('l, d F Y') }})</span>
                            </div>
                        </div>

                        <!-- Quick Status Filters -->
                        <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-800/80 rounded-xl border border-slate-200/80 dark:border-slate-700 text-xs font-semibold">
                            <button 
                                type="button"
                                @click="sessionFilter = 'all'"
                                :class="sessionFilter === 'all' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-2xs' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'"
                                class="px-3 py-1.5 rounded-lg transition-all"
                            >
                                {{ __('center::attendance.filter_all') }}
                            </button>
                            <button 
                                type="button"
                                @click="sessionFilter = 'live'"
                                :class="sessionFilter === 'live' ? 'bg-emerald-500 text-white shadow-2xs font-bold' : 'text-emerald-600 hover:text-emerald-700 dark:text-emerald-400'"
                                class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5"
                            >
                                <span class="w-2 h-2 rounded-full bg-emerald-400" :class="sessionFilter !== 'live' && 'animate-pulse'"></span>
                                {{ __('center::attendance.filter_live') }}
                            </button>
                            <button 
                                type="button"
                                @click="sessionFilter = 'upcoming'"
                                :class="sessionFilter === 'upcoming' ? 'bg-blue-600 text-white shadow-2xs font-bold' : 'text-blue-600 hover:text-blue-700 dark:text-blue-400'"
                                class="px-3 py-1.5 rounded-lg transition-all"
                            >
                                {{ __('center::attendance.filter_upcoming') }}
                            </button>
                            <button 
                                type="button"
                                @click="sessionFilter = 'ended'"
                                :class="sessionFilter === 'ended' ? 'bg-slate-600 text-white shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400'"
                                class="px-3 py-1.5 rounded-lg transition-all"
                            >
                                {{ __('center::attendance.filter_ended') }}
                            </button>
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
                                @php
                                    $tz = config('app.timezone', 'Africa/Cairo');
                                    $now = now($tz);
                                    $todayDate = $now->format('Y-m-d');
                                    $startDateTime = \Carbon\Carbon::parse($todayDate . ' ' . $session->start_time, $tz);
                                    $endDateTime = \Carbon\Carbon::parse($todayDate . ' ' . $session->end_time, $tz);

                                    if ($now->gte($endDateTime)) {
                                        $sessionStatus = 'ended';
                                    } elseif ($now->betweenIncluded($startDateTime, $endDateTime)) {
                                        $sessionStatus = 'live';
                                    } else {
                                        $sessionStatus = 'upcoming';
                                    }
                                @endphp
                                <tr 
                                    x-show="sessionFilter === 'all' || sessionFilter === '{{ $sessionStatus }}'"
                                    class="transition-colors {{ $sessionStatus === 'live' ? 'bg-emerald-50/40 dark:bg-emerald-950/20 hover:bg-emerald-50/70 border-s-4 border-s-emerald-500' : ($sessionStatus === 'ended' ? 'opacity-80 hover:bg-slate-50/60 dark:hover:bg-slate-800/40' : 'hover:bg-slate-50/60 dark:hover:bg-slate-800/40') }}"
                                >
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <div class="font-bold text-slate-900 dark:text-slate-100 text-sm">{{ $session->course->title }}</div>
                                            
                                            {{-- Status Badge --}}
                                            @if($sessionStatus === 'live')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700/80 shadow-2xs">
                                                    <span class="relative flex h-2 w-2">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                    </span>
                                                    {{ __('center::attendance.status_live') }}
                                                </span>
                                            @elseif($sessionStatus === 'upcoming')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                                                    <i class="far fa-hourglass text-[10px]"></i>
                                                    {{ __('center::attendance.status_upcoming') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                                    <i class="fas fa-check text-[9px]"></i>
                                                    {{ __('center::attendance.status_ended') }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Time Badge (Forced LTR for correct reading order) --}}
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $sessionStatus === 'live' ? 'bg-white dark:bg-slate-800 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">
                                            <i class="far fa-clock text-[10px] text-slate-400"></i>
                                            <span dir="ltr" class="font-mono">{{ $startDateTime->format('h:i A') }} - {{ $endDateTime->format('h:i A') }}</span>
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
                                        <div class="flex items-center justify-center gap-2">
                                            @if($sessionStatus === 'ended')
                                                <a href="{{ route('center.attendance.show', $session) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:border-slate-700 transition-colors">
                                                    <i class="fas fa-clipboard-list text-xs"></i>
                                                    <span>{{ __('center::attendance.view_absentees') }}</span>
                                                </a>
                                            @elseif($sessionStatus === 'live')
                                                <a href="{{ route('center.attendance.show', $session) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs transition-colors">
                                                    <i class="fas fa-clipboard-check text-xs"></i>
                                                    <span>{{ __('center::attendance.mark_attendance') }}</span>
                                                </a>
                                                <a href="{{ route('center.attendance.qr', $session) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-700 bg-white hover:bg-emerald-50 border border-emerald-300 shadow-2xs transition-colors">
                                                    <i class="fas fa-qrcode text-xs"></i>
                                                    <span>{{ __('center::attendance.qr_code_btn') }}</span>
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
