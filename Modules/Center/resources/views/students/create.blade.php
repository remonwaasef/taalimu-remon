@extends('center::layouts.app-next')

@section('panel-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::students.form.add_new_student') }}</h2>
        <a href="{{ route('center.students.index') }}" class="btn btn-outline-secondary rounded-pill px-4">{{ __('center::students.form.back_to_list') }}</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <x-student-form 
                        actionUrl="{{ route('center.students.store') }}"
                        :courses="$courses"
                        :stages="$stages"
                        :showGrade="true"
                        backUrl="{{ route('center.students.index') }}"
                        checkPhoneUrl="{{ route('center.students.check-phone') }}"
                        lookupGuardianUrl="{{ route('center.guardians.lookup') }}"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection
