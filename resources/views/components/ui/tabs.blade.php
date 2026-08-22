@props([
    'items' => [], // [ ['id' => 'tab1', 'label' => 'Tab 1', 'icon' => 'fas fa-cog', 'badge' => null] ]
    'default' => null,
    'variant' => 'underline' // underline, pills, segmented
])

@php
    $defaultTab = $default ?? ($items[0]['id'] ?? '');
@endphp

<div x-data="{ activeTab: '{{ $defaultTab }}' }" {{ $attributes->merge(['class' => 'w-full']) }}>
    <!-- Tab Navigation List -->
    <div class="flex items-center gap-1 overflow-x-auto pb-px border-b border-slate-200 dark:border-slate-800 scrollbar-none" role="tablist">
        @foreach($items as $item)
            @php
                $tabId = $item['id'] ?? '';
                $tabLabel = $item['label'] ?? '';
                $tabIcon = $item['icon'] ?? null;
                $tabBadge = $item['badge'] ?? null;
            @endphp
            <button
                type="button"
                role="tab"
                :aria-selected="activeTab === '{{ $tabId }}'"
                @click="activeTab = '{{ $tabId }}'"
                class="flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold whitespace-nowrap transition-all duration-150 relative -mb-px border-b-2 focus:outline-none"
                :class="activeTab === '{{ $tabId }}'
                    ? 'border-brand-primary text-brand-primary dark:text-brand-300'
                    : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:border-slate-300'"
            >
                @if($tabIcon)
                    <i class="{{ $tabIcon }} text-xs"></i>
                @endif
                <span>{{ $tabLabel }}</span>
                @if($tabBadge)
                    <span class="px-1.5 py-0.5 text-[10px] font-bold rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        {{ $tabBadge }}
                    </span>
                @endif
            </button>
        @endforeach
    </div>

    <!-- Tab Panels Container -->
    <div class="pt-6">
        {{ $slot }}
    </div>
</div>
