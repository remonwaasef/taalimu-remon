@extends(auth()->check() && auth()->user()->role === 'instructor' ? 'instructor::components.layouts.hope-master' : 'center::layouts.hope-master')

@section('title', 'Subscription Successful')
@section('page-title', 'Subscription Successful')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success fa-5x"></i>
                    </div>
                    <h2 class="h4 text-gray-900 mb-4">Subscription Successful!</h2>
                    <p class="mb-4">Thank you for subscribing. Your plan is now active.</p>
                    @php
                        $user = auth()->user();
                        $dashboardRoute = ($user && $user->role === 'instructor') ? route('instructor.dashboard', ['tenant' => app('tenant')->domain]) : route('center.dashboard');
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="btn btn-primary">
                        الذهاب للوحة التحكم
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
