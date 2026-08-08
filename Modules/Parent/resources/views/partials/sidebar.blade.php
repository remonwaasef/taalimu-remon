@props(['active' => ''])

@php
    $items = [
        'index' => ['route' => 'parent.index', 'label' => 'الرئيسية', 'icon' => 'fas fa-home'],
        'courses' => ['route' => 'parent.courses', 'label' => 'دورات الأبناء', 'icon' => 'fas fa-book-open'],
        'schedule' => ['route' => 'parent.schedule', 'label' => 'الجدول الدراسي', 'icon' => 'fas fa-calendar-alt'],
        'attendance' => ['route' => 'parent.attendance', 'label' => 'الحضور والغياب', 'icon' => 'fas fa-user-check'],
        'finances' => ['route' => 'parent.finances', 'label' => 'المدفوعات والفواتير', 'icon' => 'fas fa-wallet'],
        'profile' => ['route' => 'parent.profile', 'label' => 'البيانات الشخصية', 'icon' => 'fas fa-user-circle'],
    ];
@endphp

<x-ui.sidebar brandName="Parent Portal">
    <div class="space-y-1">
        @foreach($items as $key => $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ $active === $key ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <i class="{{ $item['icon'] }} w-4 text-center"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</x-ui.sidebar>