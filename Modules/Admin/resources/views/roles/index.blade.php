@extends('admin::layouts.app-next')

@section('title', __('admin.roles.title'))
@section('page-title', __('admin.roles.title'))

@section('panel-content')
<div class="row g-4 animate__animated animate__fadeIn">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-shield-alt text-primary me-2"></i>
                    {{ __('admin.roles.roles') }}
                </h5>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i> {{ __('admin.roles.create_new') }}
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" data-mobile-cards>
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0 text-muted small fw-bold text-uppercase">{{ __('admin.roles.role_name') }}</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">{{ __('admin.roles.permissions_count') }}</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">{{ __('admin.roles.users_count') }}</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase text-end pe-4">{{ __('admin.roles.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr class="transition-all">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                            <i class="fas fa-user-tag fa-sm"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark">{{ $role->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3">
                                        <i class="fas fa-key me-1 small"></i> {{ $role->permissions->count() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3">
                                        <i class="fas fa-users me-1 small"></i> {{ $role->users->count() }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-light btn-sm rounded-pill px-3 border hover-shadow" title="{{ __('admin.roles.edit_role') }}">
                                            <i class="fas fa-edit text-warning me-1"></i> تعديل
                                        </a>
                                        
                                        @if($role->name !== 'super_admin' && $role->users->count() == 0)
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline" id="deleteRowForm_1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-confirm-delete data-form="deleteRowForm_1" class="btn btn-light btn-sm rounded-pill px-3 border hover-shadow text-danger" title="حذف">
                                                <i class="fas fa-trash me-1"></i> حذف
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($roles->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $roles->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .transition-all { transition: all 0.2s ease; }
    .table tbody tr:hover { background-color: rgba(42, 77, 255, 0.02) !important; transform: scale(1.002); }
    .hover-shadow:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .badge { font-family: 'Cairo', sans-serif; letter-spacing: 0.5px; }
</style>
@endsection
