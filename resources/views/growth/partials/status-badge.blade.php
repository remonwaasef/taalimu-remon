@php
    $map = [
        'open' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'matched' => 'bg-violet-100 text-violet-800 dark:bg-violet-900 dark:text-violet-200',
        'group_forming' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200',
        'group_formed' => 'bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200',
        'filled' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'forming' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200',
        'expired' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'active' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    ];
    $classes = $map[$status] ?? 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300';
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ __(str_replace('_', ' ', ucfirst($status))) }}
</span>
