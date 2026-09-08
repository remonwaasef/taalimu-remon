<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seoData['title'] }}</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8">
            <a href="{{ route('growth.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">&larr; {{ __('Back to Dashboard') }}</a>
            <h1 class="text-3xl font-bold text-slate-900 mt-2">{{ __('Growth Insights') }}</h1>
            <p class="text-slate-500 mt-1">{{ __('Data-driven recommendations to grow your teaching business') }}</p>
        </div>

        {{-- Insights --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-slate-900 mb-4">{{ __('Recommendations') }}</h2>
            @if(count($insights) > 0)
                <div class="space-y-4">
                    @foreach($insights as $insight)
                        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4
                            {{ $insight['severity'] === 'high' ? 'border-red-500' : '' }}
                            {{ $insight['severity'] === 'medium' ? 'border-amber-500' : '' }}
                            {{ $insight['severity'] === 'low' ? 'border-green-500' : '' }}
                        ">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full
                                            {{ $insight['severity'] === 'high' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ $insight['severity'] === 'medium' ? 'bg-amber-100 text-amber-700' : '' }}
                                            {{ $insight['severity'] === 'low' ? 'bg-green-100 text-green-700' : '' }}
                                        ">
                                            {{ ucfirst($insight['severity']) }}
                                        </span>
                                        <span class="text-xs text-slate-400">{{ round($insight['confidence'] * 100) }}% confidence</span>
                                    </div>
                                    <p class="text-sm text-slate-500 mb-1">{{ $insight['fact'] }}</p>
                                    <p class="text-sm font-medium text-slate-900">{{ $insight['recommendation'] }}</p>
                                    @if($insight['action'])
                                        @if(isset($insight['action']['params']))
                                            <a href="{{ route($insight['action']['route'], $insight['action']['params']) }}" class="inline-block mt-3 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                                                {{ $insight['action']['label'] }}
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                    <p class="text-slate-500">{{ __('No insights available yet. Keep growing to receive recommendations.') }}</p>
                </div>
            @endif
        </div>

        {{-- Demand Forecast --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-slate-900 mb-4">{{ __('Demand Forecast') }}</h2>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                @if($forecast['available'])
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-500">{{ __('Trend:') }}</span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-sm font-medium rounded-full
                                {{ $forecast['trend'] === 'increasing' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $forecast['trend'] === 'decreasing' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $forecast['trend'] === 'stable' ? 'bg-slate-100 text-slate-700' : '' }}
                            ">
                                @if($forecast['trend'] === 'increasing')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                @elseif($forecast['trend'] === 'decreasing')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/></svg>
                                @endif
                                {{ ucfirst($forecast['trend']) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        @foreach($forecast['forecast'] as $month)
                            <div class="bg-slate-50 rounded-xl p-4">
                                <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($month['month'])->format('M Y') }}</p>
                                <p class="text-2xl font-bold text-slate-900">{{ $month['projected_demand'] }}</p>
                                <p class="text-xs text-slate-400">{{ __('projected demand requests') }}</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <div class="flex-1 bg-slate-200 rounded-full h-1.5">
                                        <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $month['confidence'] * 100 }}%"></div>
                                    </div>
                                    <span class="text-xs text-slate-400">{{ round($month['confidence'] * 100) }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(count($forecast['by_subject']) > 0)
                        <div>
                            <h3 class="text-sm font-medium text-slate-700 mb-3">{{ __('Demand by Subject') }}</h3>
                            <div class="space-y-2">
                                @foreach($forecast['by_subject'] as $subject => $count)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-slate-700">{{ $subject }}</span>
                                        <div class="flex items-center gap-2">
                                            <div class="w-32 bg-slate-200 rounded-full h-2">
                                                <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ ($count / max(array_values($forecast['by_subject'])) * 100) }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-slate-900">{{ $count }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <p class="text-slate-500">{{ $forecast['message'] }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Opportunities --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-slate-900 mb-4">{{ __('Growth Opportunities') }}</h2>
            @if(count($opportunities) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($opportunities as $opp)
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="font-bold text-slate-900">{{ $opp['subject'] }}</h3>
                                    @if($opp['level'])
                                        <p class="text-sm text-slate-500">{{ $opp['level'] }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-indigo-600">{{ $opp['score'] }}</p>
                                    <p class="text-xs text-slate-400">/100</p>
                                </div>
                            </div>

                            <div class="w-full bg-slate-200 rounded-full h-2 mb-3">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $opp['score'] }}%"></div>
                            </div>

                            <p class="text-sm text-slate-600 mb-3">{{ $opp['explanation'] }}</p>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">{{ $opp['demand_count'] }} {{ __('students interested') }}</span>
                                @if($opp['estimated_revenue']['estimated_revenue'] > 0)
                                    <span class="font-medium text-green-600">{{ number_format($opp['estimated_revenue']['estimated_revenue'], 0) }} {{ __('est. revenue') }}</span>
                                @endif
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <div class="grid grid-cols-5 gap-1">
                                    @foreach($opp['breakdown'] as $key => $factor)
                                        <div class="text-center">
                                            <div class="w-full bg-slate-200 rounded-full h-1.5 mb-1">
                                                <div class="bg-indigo-400 h-1.5 rounded-full" style="width: {{ $factor['score'] }}%"></div>
                                            </div>
                                            <p class="text-xs text-slate-400">{{ $factor['weight'] }}%</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                    <p class="text-slate-500">{{ __('No opportunities detected yet. Create programs and collect demand to see opportunities.') }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white border-t border-slate-100 py-6 text-center mt-12">
        <p class="text-sm text-slate-400">Powered by <span class="font-semibold text-slate-600">Taalimu</span></p>
    </div>
</body>
</html>
