@extends('center::layouts.hope-master')

@section('title', __('center::messages.blade_0025'))

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('center::messages.blade_0017') }}</h1>
        <a href="{{ route('center.analytics.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-right"></i>{{ __('center::messages.blade_0018') }}</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('center::messages.blade_0019') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('center::messages.blade_0020') }}</th>
                            <th>{{ __('center::messages.blade_0021') }}</th>
                            <th>{{ __('center::messages.blade_0022') }}</th>
                            <th>{{ __('center::messages.blade_0023') }}</th>
                            <th>{{ __('center::messages.blade_0024') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                            <tr>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->instructor->name ?? __('center::messages.blade_0026') }}</td>
                                <td>{{ $course->enrollments_count }}</td>
                                <td>{{ $course->schedules_count }}</td>
                                <td>{{ format_price($course->price) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
