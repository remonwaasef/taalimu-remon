@props([
    'title',
    'value',
    'change' => null,
    'changeType' => 'positive', // positive, negative, neutral
    'changeLabel' => 'vs last month',
    'icon' => 'fas fa-chart-line',
    'iconColor' => 'text-brand-primary bg-brand-50 dark:bg-brand-900/30',
    'accent' => 'brand', // brand, emerald, sky, amber, purple
    'animate' => true
])

@php
    $numeric = null;
    if ($animate && preg_match('/[\d,.]+(\.\d+)?/', $value, $m)) {
        $clean = str_replace(',', '', $m[0]);
        if (is_numeric($clean)) {
            $numeric = (float) $clean;
        }
    }

    $accents = [
        'brand' => 'hover:border-brand-300 dark:hover:border-brand-700/60',
        'emerald' => 'hover:border-emerald-300 dark:hover:border-emerald-700/60',
        'sky' => 'hover:border-sky-300 dark:hover:border-sky-700/60',
        'amber' => 'hover:border-amber-300 dark:hover:border-amber-700/60',
        'purple' => 'hover:border-purple-300 dark:hover:border-purple-700/60',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5.5 shadow-xs hover:shadow-md transition-all duration-200 relative overflow-hidden font-inter group ' . ($accents[$accent] ?? $accents['brand'])]) }}>
    <!-- Top subtle gradient hover glow -->
    <div class="absolute inset-x-0 top-0 h-1 bg-transparent group-hover:bg-gradient-to-r group-hover:from-transparent group-hover:via-brand-primary/40 group-hover:to-transparent transition-all duration-300"></div>

    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0 flex-1">
            <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider truncate">{{ $title }}</p>
            <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-slate-100 mt-2 tracking-tight">
                <span
                    x-data='{
                        end: @json($numeric),
                        raw: @json($value),
                        display: @json($value),
                        init() {
                            if (this.end === null || this.end === 0) return;
                            if (window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
                            const prefix = this.raw.match(/^\D*/)[0];
                            const suffix = this.raw.match(/\D*$/)[0];
                            const commaSeparated = this.raw.includes(",");
                            const start = performance.now();
                            const duration = 550;
                            const tick = (now) => {
                                const p = Math.min((now - start) / duration, 1);
                                const eased = 1 - Math.pow(1 - p, 3);
                                const current = Math.round(this.end * eased);
                                const formatted = commaSeparated ? current.toLocaleString("en-US") : String(current);
                                this.display = prefix + formatted + suffix;
                                if (p < 1) requestAnimationFrame(tick);
                                else this.display = this.raw;
                            };
                            requestAnimationFrame(tick);
                        }
                    }'
                    x-text="display"
                >{{ $value }}</span>
            </h3>
        </div>

        @if($icon)
            <div class="w-11 h-11 rounded-xl flex items-center justify-center text-base shrink-0 shadow-2xs transition-transform duration-200 group-hover:scale-105 {{ $iconColor }}">
                <i class="{{ $icon }}"></i>
            </div>
        @endif
    </div>

    @if($change !== null)
        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center gap-2 text-xs font-medium">
            @if($changeType === 'positive')
                <span class="inline-flex items-center text-indigo-700 dark:text-indigo-300 font-bold bg-indigo-50 dark:bg-indigo-950/50 px-2 py-0.5 rounded-full border border-indigo-200/60 dark:border-indigo-800/40 text-[11px]">
                    <i class="fas fa-arrow-up text-[9px] me-1"></i>{{ $change }}
                </span>
            @elseif($changeType === 'negative')
                <span class="inline-flex items-center text-red-700 dark:text-red-300 font-bold bg-red-50 dark:bg-red-950/50 px-2 py-0.5 rounded-full border border-red-200/60 dark:border-red-800/40 text-[11px]">
                    <i class="fas fa-arrow-down text-[9px] me-1"></i>{{ $change }}
                </span>
            @else
                <span class="inline-flex items-center text-slate-700 dark:text-slate-300 font-bold bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full border border-slate-200/60 dark:border-slate-700 text-[11px]">
                    {{ $change }}
                </span>
            @endif
            
            <span class="text-slate-400 dark:text-slate-500 text-[11px] truncate">{{ $changeLabel }}</span>
        </div>
    @endif
</div>
