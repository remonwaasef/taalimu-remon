@extends('center::layouts.master')

@section('title', 'Subscription Plans')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Subscription Plans</h1>
    </div>

    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        @foreach($packages as $package)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 rounded-4 {{ $tenant->subscribedToPrice($package->stripe_price_id) ? 'border-primary border-2' : '' }}">
                    <div class="card-body p-4">
                        @if($tenant->subscribedToPrice($package->stripe_price_id))
                            <span class="badge bg-primary rounded-pill mb-3">{{ __('center::messages.blade_0952') }}</span>
                        @endif
                        <h4 class="fw-bold mb-2">{{ $package->name }}</h4>
                        <div class="mb-3">
                            <span class="display-6 fw-black text-primary">{{ number_format($package->price, 0) }}</span>
                            <span class="text-muted">ر.س / شهرياً</span>
                        </div>
                        <p class="text-muted small mb-4">{{ $package->description }}</p>
                        
                        <ul class="list-unstyled mb-4">
                            @foreach($package->display_features ?? [] as $feature)
                                <li class="mb-2 small">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>

                        @if($tenant->subscribedToPrice($package->stripe_price_id))
                            <button class="btn btn-outline-primary w-100 rounded-pill disabled" disabled>{{ __('center::messages.blade_0953') }}</button>
                        @elseif($package->stripe_price_id)
                            <a href="{{ route('center.subscription.checkout', $package->id) }}" class="btn btn-primary w-100 rounded-pill">{{ __('center::messages.blade_0954') }}</a>
                        @else
                            <button class="btn btn-light w-100 rounded-pill disabled" disabled>{{ __('center::messages.blade_0955') }}</button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
