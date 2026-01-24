@extends('center::layouts.master')

@section('title', __('center::branches.create_branch'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary">{{ __('center::branches.create_branch') }}</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('center.branches.store', ['tenant' => $tenant->domain]) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">{{ __('center::branches.branch_name') }}</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
 
                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('center::branches.address') }}</label>
                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                    </div>
 
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">{{ __('center::branches.phone') }}</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="manager_id" class="form-label">{{ __('center::branches.manager') }}</label>
                            <select class="form-select" id="manager_id" name="manager_id">
                                <option value="">{{ __('center::branches.select_manager') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
 
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('center.branches.index', ['tenant' => $tenant->domain]) }}" class="btn btn-light me-2">{{ __('center::branches.cancel') }}</a>
                        <button type="submit" class="btn btn-primary px-4">{{ __('center::branches.create_branch') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
