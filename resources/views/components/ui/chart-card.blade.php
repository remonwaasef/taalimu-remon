@props([
    'id' => 'chart-' . uniqid(),
    'title' => null,
    'subtitle' => null,
    'type' => 'area', // area, bar, line, donut
    'height' => 300,
    'series' => [],
    'categories' => []
])

<x-ui.card :title="$title" :subtitle="$subtitle">
    <div id="{{ $id }}" style="min-height: {{ $height }}px;"></div>
</x-ui.card>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const options = {
        series: @json($series),
        chart: {
            type: '{{ $type }}',
            height: {{ $height }},
            fontFamily: 'Inter, sans-serif',
            toolbar: { show: false },
            background: 'transparent'
        },
        theme: {
            mode: isDark ? 'dark' : 'light'
        },
        colors: ['#5B5FEF', '#22C55E', '#F59E0B', '#EF4444', '#06B6D4'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        xaxis: {
            categories: @json($categories),
            labels: { style: { colors: '#94A3B8', fontSize: '11px' } },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: { style: { colors: '#94A3B8', fontSize: '11px' } }
        },
        grid: {
            borderColor: isDark ? '#1E293B' : '#E7EAF3',
            strokeDashArray: 4
        },
        tooltip: {
            theme: isDark ? 'dark' : 'light'
        }
    };

    const chart = new ApexCharts(document.querySelector("#{{ $id }}"), options);
    chart.render();
});
</script>
@endpush
