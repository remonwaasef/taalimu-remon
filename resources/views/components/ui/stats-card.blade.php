@props([
    'title',
    'value',
    'change' => null,
    'changeType' => 'positive', // positive, negative, neutral
    'changeLabel' => 'vs last month',
    'icon' => 'fas fa-chart-line',
    'iconColor' => 'text-brand-primary bg-brand-50 dark:bg-brand-900/30',
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
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-brand-border dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200 relative overflow-hidden font-inter']) }}>
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $title }}</p>
            <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-slate-100 mt-2 tracking-tight">
                <span
                    x-data='{
                        end: @js($numeric),
                        raw: @js($value),
                        display: @js($value),
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
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg shrink-0 {{ $iconColor }}">
                <i class="{{ $icon }}"></i>
            </div>
        @endif
    </div>

    @if($change !== null)
        <div class="mt-4 pt-3 border-t border-brand-border/60 dark:border-slate-800/80 flex items-center gap-2 text-xs font-medium">
            @if($changeType === 'positive')
                <span class="inline-flex items-center text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full">
                    <i class="fas fa-arrow-up text-[10px] me-1"></i>{{ $change }}
                </span>
            @elseif($changeType === 'negative')
                <span class="inline-flex items-center text-red-600 dark:text-red-400 font-bold bg-red-50 dark:bg-red-950/40 px-2 py-0.5 rounded-full">
                    <i class="fas fa-arrow-down text-[10px] me-1"></i>{{ $change }}
                </span>
            @else
                <span class="inline-flex items-center text-slate-600 dark:text-slate-400 font-bold bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full">
                    {{ $change }}
                </span>
            @endif
            
            <span class="text-slate-400 dark:text-slate-500">{{ $changeLabel }}</span>
        </div>
    @endif
</div>
