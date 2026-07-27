@extends('layouts.app-next')

@section('sidebar')
    @include('center::partials._sidebar-next')
@endsection

@section('content')
    @hasSection('page-title')
        <x-ui.page-header>
            <x-slot name="title">@yield('page-title')</x-slot>
            @hasSection('page-subtitle')
                <x-slot name="subtitle">@yield('page-subtitle')</x-slot>
            @endif
            @hasSection('page-actions')
                <x-slot name="actions">@yield('page-actions')</x-slot>
            @endif
        </x-ui.page-header>
    @endif
    @yield('panel-content')
@endsection
