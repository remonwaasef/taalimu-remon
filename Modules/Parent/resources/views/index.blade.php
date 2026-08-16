@extends('layouts.app-next')

@section('title', 'Ø¨ÙˆØ§Ø¨Ø© ÙˆÙ„ÙŠ Ø§Ù„Ø£Ù…Ø±')

@section('sidebar')
    @include('parent::partials.sidebar', ['active' => 'index'])
@endsection

@section('content')
    <x-ui.page-header
        title="Ø£Ù‡Ù„Ø§Ù‹ØŒ {{ $guardian->name }} ðŸ‘‹"
        subtitle="ØªØ§Ø¨Ø¹ Ù…Ø³ØªÙˆÙ‰ Ø£Ø¨Ù†Ø§Ø¦Ùƒ Ø§Ù„Ø£ÙƒØ§Ø¯ÙŠÙ…ÙŠ ÙˆØ§Ù„Ø­Ø¶ÙˆØ± ÙˆØ§Ù„Ù…Ø¯ÙÙˆØ¹Ø§Øª Ù…Ù† Ù…ÙƒØ§Ù† ÙˆØ§Ø­Ø¯."
    />

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8 motion-stagger">
        <x-ui.stats-card
            title="Ø¹Ø¯Ø¯ Ø§Ù„Ø£Ø¨Ù†Ø§Ø¡"
            value="{{ number_format($totalChildren) }}"
            change="Ù…ØªØ§Ø¨Ø¹Ø©"
            changeType="positive"
            changeLabel="Ù…Ø´ØªØ±Ùƒ ÙÙŠ Ù…ØªØ§Ø¨Ø¹ØªÙƒ"
            icon="fas fa-users"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="Ø§Ù„Ø¯ÙˆØ±Ø§Øª Ø§Ù„Ù…Ø³Ø¬Ù„Ø©"
            value="{{ number_format($totalCourses) }}"
            change="Ø¯ÙˆØ±Ø©"
            changeType="positive"
            changeLabel="Ù„Ø¬Ù…ÙŠØ¹ Ø§Ù„Ø£Ø¨Ù†Ø§Ø¡"
            icon="fas fa-book-open"
            iconColor="text-sky-600 bg-sky-50"
        />

        <x-ui.stats-card
            title="Ø¥Ø¬Ù…Ø§Ù„ÙŠ Ø§Ù„Ù…ØªØ¨Ù‚ÙŠ"
            value="{{ format_price($totalDebt) }}"
            change="Ø±ØµÙŠØ¯"
            changeType="{{ $totalDebt > 0 ? 'negative' : 'positive' }}"
            changeLabel="Ù…Ø³ØªØ­Ù‚ Ø¹Ù„Ù‰ Ø§Ù„Ø£Ø¨Ù†Ø§Ø¡"
            icon="fas fa-wallet"
            iconColor="text-amber-600 bg-amber-50"
        />
    </div>

    <!-- Children Cards -->
    <x-ui.card title="Ø£Ø¨Ù†Ø§Ø¦ÙŠ ðŸ“š" subtitle="Ø­Ø§Ù„Ø© ÙƒÙ„ Ø§Ø¨Ù† ÙˆØ£Ù‡Ù… Ù…Ø¤Ø´Ø±Ø§Øª Ø§Ù„Ù…ØªØ§Ø¨Ø¹Ø©" class="mb-8">
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
                                    <p class="text-[11px] text-slate-400">{{ $student->grade?->name ?? 'â€”' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/60 p-3 text-center">
                                    <div class="text-base font-bold text-slate-800 dark:text-slate-200">{{ number_format($agg['enrollments_count'] ?? 0) }}</div>
                                    <div class="text-[10px] text-slate-400 font-semibold">Ø¯ÙˆØ±Ø§Øª</div>
                                </div>
                                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/60 p-3 text-center">
                                    @if($agg['attendance_rate'])
                                        <div class="text-base font-bold {{ $agg['attendance_rate'] >= 70 ? 'text-emerald-600' : 'text-amber-600' }}">{{ $agg['attendance_rate'] }}%</div>
                                    @else
                                        <div class="text-base font-bold text-slate-400">â€”</div>
                                    @endif
                                    <div class="text-[10px] text-slate-400 font-semibold">Ø­Ø¶ÙˆØ±</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between px-1 pb-2">
                                <span class="text-[11px] text-slate-400 font-semibold">Ø§Ù„Ù…ØªØ¨Ù‚ÙŠ Ø¹Ù„Ù‰ Ø§Ù„Ø·Ø§Ù„Ø¨</span>
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
                title="Ù„Ø§ ÙŠÙˆØ¬Ø¯ Ø£Ø¨Ù†Ø§Ø¡ Ù…Ø±ØªØ¨Ø·ÙˆÙ†"
                description="Ù„Ù… ÙŠØªÙ… Ø±Ø¨Ø· Ø£ÙŠ Ø·Ø§Ù„Ø¨ Ø¨Ø­Ø³Ø§Ø¨Ùƒ Ø¨Ø¹Ø¯. ØªÙˆØ§ØµÙ„ Ù…Ø¹ Ø¥Ø¯Ø§Ø±Ø© Ø§Ù„Ù…Ø±ÙƒØ² Ù„Ø±Ø¨Ø· Ø£Ø¨Ù†Ø§Ø¦Ùƒ."
                icon="fas fa-users-slash"
            />
        @endif
    </x-ui.card>
@endsection
