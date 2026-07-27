@extends('center::layouts.app-next')

@section('title', __('center::branches.edit_branch'))

@section('panel-content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary">{{ __('center::branches.edit_branch') }}: {{ $branch->name }}</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('center.branches.update', ['branch' => $branch->id, 'tenant' => $tenant->domain]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">{{ __('center::branches.branch_name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $branch->name) }}" required>
                    </div>
 
                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('center::branches.address') }}</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $branch->address) }}</textarea>
                    </div>
 
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">{{ __('center::branches.phone') }}</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $branch->phone) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="manager_id" class="form-label">{{ __('center::branches.manager') }}</label>
                            <select class="form-select" id="manager_id" name="manager_id">
                                <option value="">{{ __('center::branches.select_manager') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $branch->manager_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->role }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
 
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('center.branches.index', ['tenant' => $tenant->domain]) }}" class="btn btn-light me-2">{{ __('center::branches.cancel') }}</a>
                        <button type="submit" class="btn btn-primary px-4">{{ __('center::branches.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
