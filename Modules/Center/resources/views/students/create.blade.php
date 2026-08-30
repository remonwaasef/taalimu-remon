@extends('center::layouts.app-next')

@section('panel-content')
    <x-ui.page-header
        title="{{ __('center::students.form.add_new_student') }}"
        subtitle="{{ __('center::students.quick_enroll_desc', ['name' => '']) }}"
    >
        <x-slot name="actions">
            <x-ui.button variant="outline" size="sm" icon="fas fa-arrow-right" href="{{ route('center.students.index') }}">
                {{ __('center::students.form.back_to_list') }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <div class="max-w-4xl mx-auto">
        <x-ui.card>
            <x-student-form 
                actionUrl="{{ route('center.students.store') }}"
                :courses="$courses"
                :stages="$stages"
                :showGrade="true"
                backUrl="{{ route('center.students.index') }}"
                checkPhoneUrl="{{ route('center.students.check-phone') }}"
                lookupGuardianUrl="{{ route('center.guardians.lookup') }}"
            />
        </x-ui.card>
    </div>
@endsection
