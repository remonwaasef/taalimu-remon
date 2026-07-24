@props([
    'type' => 'line', // line, avatar, card, table
    'count' => 1,
    'height' => 'h-4'
])

@if($type === 'line')
    <div class="space-y-3 w-full">
        @for($i = 0; $i < $count; $i++)
            <div class="animate-pulse bg-slate-200 dark:bg-slate-800 rounded-lg {{ $height }} w-full"></div>
        @endfor
    </div>
@elseif($type === 'avatar')
    <div class="animate-pulse bg-slate-200 dark:bg-slate-800 rounded-full w-10 h-10 shrink-0"></div>
@elseif($type === 'card')
    <div class="animate-pulse p-6 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 space-y-4">
        <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded-lg w-1/3"></div>
        <div class="h-8 bg-slate-200 dark:bg-slate-800 rounded-lg w-1/2"></div>
        <div class="h-3 bg-slate-200 dark:bg-slate-800 rounded-lg w-full"></div>
    </div>
@elseif($type === 'table')
    <div class="space-y-3 w-full p-4">
        @for($i = 0; $i < $count; $i++)
            <div class="flex items-center space-x-4 rtl:space-x-reverse animate-pulse">
                <div class="rounded-full bg-slate-200 dark:bg-slate-800 h-8 w-8"></div>
                <div class="flex-1 space-y-2 py-1">
                    <div class="h-3 bg-slate-200 dark:bg-slate-800 rounded w-3/4"></div>
                    <div class="h-2 bg-slate-200 dark:bg-slate-800 rounded w-1/2"></div>
                </div>
            </div>
        @endfor
    </div>
@endif
