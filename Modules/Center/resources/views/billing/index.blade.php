@extends('center::layouts.hope-master')

@section('content')
<div class="container">
    <h1>{{ __('center::billing.title') }}</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">{{ __('center::billing.current_subscription') }}</div>
        <div class="card-body">
            @if($subscription)
                <p><strong>{{ __('center::billing.plan') }}:</strong> {{ $subscription->package->name ?? __('center::billing.unknown_package') }}</p>
                <p><strong>{{ __('center::billing.status') }}:</strong> {{ ucfirst($subscription->status) }}</p>
                <p><strong>{{ __('center::billing.expires_at') }}:</strong> {{ $subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : '-' }}</p>
            @else
                <p class="text-danger">{{ __('center::billing.no_active_subscription') }}</p>
            @endif
        </div>
    </div>

    <h2>{{ __('center::billing.available_plans') }}</h2>
    <div class="row">
        @foreach($packages as $package)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        @php
                            $transPkg = __('features.packages.' . $package->slug);
                            $pkgName = ($transPkg === 'features.packages.' . $package->slug) ? $package->name : $transPkg;
                        @endphp
                        <h5 class="card-title">{{ $pkgName }}</h5>
                        <p class="card-text">{{ $package->description }}</p>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $package->price }} {{ __('center::billing.currency') }} / {{ $package->duration_in_days }} {{ __('center::billing.days') }}</h6>
                        <ul>
                            @foreach($package->features as $feature)
                                @php
                                    $transFeat = __('features.' . $feature->code);
                                    $featName = ($transFeat === 'features.' . $feature->code) ? $feature->name : $transFeat;
                                @endphp
                                <li>{{ $featName }}: {{ $feature->pivot->value == -1 ? __('center::billing.unlimited') : $feature->pivot->value }}</li>
                            @endforeach
                        </ul>
                        <button class="btn btn-primary">{{ __('center::billing.subscribe') }}</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
