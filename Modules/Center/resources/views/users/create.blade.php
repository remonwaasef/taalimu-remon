@extends('center::layouts.hope-master')

@section('title', __('center::messages.blade_0937'))
@section('page-title', __('center::messages.blade_0938'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">{{ __('center::messages.blade_0927') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('center.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('center::messages.blade_0928') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('center::messages.blade_0929') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">{{ __('center::messages.blade_0930') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('center::messages.blade_0931') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">{{ __('center::messages.blade_0932') }}</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                            <option value="" selected disabled>{{ __('center::messages.blade_0933') }}</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}" {{ old('role') == $r->name ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $r->name)) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">{{ __('center::messages.blade_0934') }}</div>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('center.users.index') }}" class="btn btn-outline-secondary">{{ __('center::messages.blade_0935') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('center::messages.blade_0936') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
