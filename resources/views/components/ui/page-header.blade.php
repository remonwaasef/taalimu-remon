@props([
    'title',
    'subtitle' => null,
    'breadcrumb' => []
])

<div {{ $attributes->merge(['class' => 'mb-8 font-inter']) }}>
    @if(count($breadcrumb) > 0)
        <div class="mb-3">
            <x-ui.breadcrumb :items="$breadcrumb" />
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">
                {{ $title }}
            </h1>
            @if($subtitle)
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium max-w-2xl">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        @if(isset($actions))
            <div class="flex items-center gap-3 shrink-0">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
