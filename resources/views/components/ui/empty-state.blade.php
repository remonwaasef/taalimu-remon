@props([
    'title' => 'No items found',
    'description' => 'Get started by creating a new entry or adjusting your filters.',
    'icon' => 'fas fa-inbox'
])

<div {{ $attributes->merge(['class' => 'text-center py-12 px-6 rounded-2xl border border-dashed border-brand-border dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 my-4 font-inter motion-reveal-sm']) }}>
    <div class="w-16 h-16 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-primary dark:text-brand-300 flex items-center justify-center text-2xl mx-auto mb-4 border border-brand-100 dark:border-brand-800/40 shadow-sm">
        <i class="{{ $icon }}"></i>
    </div>

    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 tracking-tight">{{ $title }}</h3>
    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto leading-relaxed">{{ $description }}</p>

    @if(isset($action))
        <div class="mt-6 flex justify-center">
            {{ $action }}
        </div>
    @endif
</div>
