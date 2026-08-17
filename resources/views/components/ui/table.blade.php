@props([
    'headers' => [],
    'label' => 'Table'
])

<div class="w-full overflow-x-auto rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm" role="region" aria-label="{{ $label }}" tabindex="0" data-mobile-cards>
    <table {{ $attributes->merge(['class' => 'w-full text-start text-sm text-slate-600 dark:text-slate-300 border-collapse']) }}>
        @if(count($headers) > 0 || isset($thead))
            <thead class="bg-slate-50/80 dark:bg-slate-800/80 border-b border-brand-border dark:border-slate-800 text-xs uppercase font-semibold text-slate-500 dark:text-slate-400 tracking-wider">
                @if(isset($thead))
                    {{ $thead }}
                @else
                    <tr>
                        @foreach($headers as $header)
                            <th scope="col" class="px-6 py-3.5 text-start font-inter">{{ $header }}</th>
                        @endforeach
                    </tr>
                @endif
            </thead>
        @endif

        <tbody class="divide-y divide-brand-border dark:divide-slate-800 font-inter">
            {{ $slot }}
        </tbody>

        @if(isset($tfoot))
            <tfoot class="bg-slate-50/80 dark:bg-slate-800/80 border-t border-brand-border dark:border-slate-800">
                {{ $tfoot }}
            </tfoot>
        @endif
    </table>
</div>
