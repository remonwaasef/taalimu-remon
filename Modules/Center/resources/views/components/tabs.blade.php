<!-- Alpine.js Tabs Component -->
<!-- Usage: <x-ui.tabs :tabs="['Tab 1', 'Tab 2']" default="0">Content</x-ui.tabs> -->

@props(['tabs', 'default' => 0])

<div x-data="{ activeTab: @js($default) }" class="w-full">
    <!-- Tab Navigation -->
    <div class="border-b border-slate-200 dark:border-slate-700" role="tablist">
        <nav class="flex gap-1" aria-label="Tabs">
            @foreach($tabs as $index => $tab)
                <button
                    role="tab"
                    :aria-selected="activeTab === {{ $index }}"
                    :aria-controls="{{ $id }}-panel-{{ $index }}"
                    id="{{ $id }}-tab-{{ $index }}"
                    @click="activeTab = {{ $index }}"
                    class="px-4 py-2.5 text-sm font-medium transition-colors rounded-t-lg -mb-px border-b-2
                        {{ $index === $default
                            ? 'text-brand-primary border-brand-primary bg-brand-50/50 dark:bg-brand-900/20'
                            : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600'
                        }}"
                >
                    {{ $tab }}
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Tab Panels -->
    <div class="pt-4">
        @foreach($tabs as $index => $tab)
            <div
                role="tabpanel"
                :aria-labelledby="{{ $id }}-tab-{{ $index }}"
                id="{{ $id }}-panel-{{ $index }}"
                x-show="activeTab === {{ $index }}"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="{{ $index !== $default ? 'hidden' : '' }}"
            >
                {{ $slot[$index] ?? '' }}
            </div>
        @endforeach
    </div>
</div>