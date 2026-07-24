@props([
    'items' => []
])

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'flex']) }}>
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse font-inter text-xs">
        <li class="inline-flex items-center">
            <a href="/" class="inline-flex items-center text-slate-500 hover:text-brand-primary dark:text-slate-400 dark:hover:text-slate-200 transition-colors">
                <svg class="w-3.5 h-3.5 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Home</span>
            </a>
        </li>

        @foreach($items as $label => $url)
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-slate-400 mx-1 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    @if($loop->last || !$url)
                        <span class="text-slate-800 dark:text-slate-200 font-semibold me-1">{{ $label }}</span>
                    @else
                        <a href="{{ $url }}" class="text-slate-500 hover:text-brand-primary dark:text-slate-400 dark:hover:text-slate-200 transition-colors me-1">{{ $label }}</a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
