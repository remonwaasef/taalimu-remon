@extends('layouts.app-next')

@section('title', __('instructor::students.create_title'))

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'students'])
@endsection

@section('content')
    <x-ui.page-header
        title="{{ __('instructor::students.create_student_data') }}"
        subtitle="{{ __('instructor::students.create_student_hint') }}"
        :breadcrumb="[
            __('instructor::sidebar.students') => route('instructor.students.list'),
            __('instructor::students.create_title') => null
        ]"
    >
        <x-slot name="actions">
            <x-ui.button variant="secondary" icon="fas fa-arrow-right" href="{{ route('instructor.students.list') }}">
                {{ __('instructor::students.back') }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <div class="max-w-4xl mx-auto">
        <x-ui.card>
            <x-student-form 
                actionUrl="{{ route('instructor.students.store') }}"
                :courses="$courses"
                :showGrade="false"
                backUrl="{{ route('instructor.students.list') }}"
                checkPhoneUrl="{{ route('instructor.students.check-phone') }}"
            />
        </x-ui.card>
    </div>
@endsection

