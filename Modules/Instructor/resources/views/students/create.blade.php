@extends('layouts.app-next')

@section('title', __('instructor::students.create_title'))

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'students'])
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h4 class="fw-bold mb-0">{{ __('instructor::students.create_student_data') }}</h4>
                    <p class="text-muted small">{{ __('instructor::students.create_student_hint') }}</p>
                </div>
                <div class="card-body p-4">
                    <x-student-form 
                        actionUrl="{{ route('instructor.students.store') }}"
                        :courses="$courses"
                        :showGrade="false"
                        backUrl="{{ route('instructor.students.list') }}"
                        checkPhoneUrl="{{ route('instructor.students.check-phone') }}"
                    />
                </div>
            </div>
        </div>
    </div>
@stop
