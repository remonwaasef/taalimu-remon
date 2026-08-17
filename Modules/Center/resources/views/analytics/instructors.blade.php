@extends('center::layouts.app-next')

@section('title', __('center::messages.blade_0078'))

@section('panel-content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('center::messages.blade_0071') }}</h1>
        <a href="{{ route('center.analytics.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-right"></i>{{ __('center::messages.blade_0072') }}</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('center::messages.blade_0073') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive" data-mobile-cards>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('center::messages.blade_0074') }}</th>
                            <th>{{ __('center::messages.blade_0075') }}</th>
                            <th>{{ __('center::messages.blade_0076') }}</th>
                            <th>{{ __('center::messages.blade_0077') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($instructorStats as $instructor)
                            <tr>
                                <td>{{ $instructor->name }}</td>
                                <td>{{ $instructor->courses_count }}</td>
                                <td>{{ $instructor->total_students }}</td>
                                <td>-</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
