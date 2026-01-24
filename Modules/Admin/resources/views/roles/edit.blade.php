@extends('admin::layouts.master')

@section('title', __('admin.roles.edit_role'))
@section('page-title', __('admin.roles.edit_role'))

@section('content')
<div class="row g-4 animate__animated animate__fadeIn">
    <div class="col-12">
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold text-dark">{{ __('admin.roles.role_name') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-user-tag text-muted"></i></span>
                                <input type="text" name="name" id="name" class="form-control rounded-end-3 shadow-none border @error('name') is-invalid @enderror" required value="{{ old('name', $role->name) }}" {{ $role->name == 'super_admin' ? 'readonly' : '' }}>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 me-2">
                                <i class="fas fa-save me-2"></i> {{ __('admin.save_changes') }}
                            </button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-light rounded-pill px-4 border">
                                {{ __('admin.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-4 text-dark px-2">
                <i class="fas fa-key text-primary me-2"></i>
                {{ __('admin.roles.permissions') }}
            </h5>

            <div class="row g-4">
                @foreach($permissions as $groupName => $perms)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="fw-bold mb-0 text-primary d-flex align-items-center">
                                    <span class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                        <i class="fas fa-folder-open fa-xs"></i>
                                    </span>
                                    {{ ucfirst($groupName) }}
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex flex-column gap-2">
                                    @foreach($perms as $permission)
                                        <div class="form-check form-switch custom-switch p-0 d-flex justify-content-between align-items-center flex-row-reverse">
                                            <input class="form-check-input ms-0" type="checkbox" id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                            <label class="form-check-label text-muted small" for="perm_{{ $permission->id }}">
                                                {{ str_replace($groupName, '', $permission->name) ?: $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
</div>

<style>
    .hover-lift:hover { transform: translateY(-5px); transition: transform 0.2s ease; }
    .custom-switch .form-check-input { width: 40px; height: 20px; cursor: pointer; }
    .form-check-input:checked { background-color: var(--bs-primary); border-color: var(--bs-primary); }
</style>
@endsection
