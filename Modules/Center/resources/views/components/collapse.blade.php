<!-- Alpine.js Collapse Component -->
<!-- Usage: <x-ui.collapse :open="false">Content</x-ui.collapse> -->

@props(['open' => false])

<div x-data="{ isOpen: @js($open) }">
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="{{ !$open ? 'hidden' : '' }}"
    >
        {{ $slot }}
    </div>
</div>