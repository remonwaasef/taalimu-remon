@extends('center::layouts.app-next')

@section('title', __('center::roles.admins_and_roles'))

@section('panel-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-user-shield me-2"></i> {{ __('center::roles.roles_permissions') }}</h5>
                    <a href="{{ route('center.roles.create', ['tenant' => $tenant->domain]) }}" class="btn btn-primary btn-sm fw-bold">
                        <i class="fas fa-plus me-1"></i> {{ __('center::roles.create_new_custom_role') }}
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 border-bottom-0 text-uppercase small text-muted font-monospace">{{ __('center::roles.role_name') }}</th>
                                    <th class="border-bottom-0 text-uppercase small text-muted font-monospace">{{ __('center::roles.type') }}</th>
                                    <th class="border-bottom-0 text-uppercase small text-muted font-monospace">{{ __('center::roles.users_count') }}</th>
                                    <th class="text-end pe-4 border-bottom-0 text-uppercase small text-muted font-monospace">{{ __('center::roles.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 {{ is_null($role->tenant_id) ? 'bg-warning bg-opacity-10 text-warning' : 'bg-primary bg-opacity-10 text-primary' }}" style="width: 40px; height: 40px;">
                                                    <i class="fas {{ is_null($role->tenant_id) ? 'fa-lock' : 'fa-user-tag' }}"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $role->name }}</h6>
                                                    @if(is_null($role->tenant_id))
                                                        <small class="text-muted" style="font-size: 0.75rem;">{{ __('center::roles.system_managed') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if(is_null($role->tenant_id))
                                                <span class="badge rounded-pill bg-light text-dark border"><i class="fas fa-shield-alt me-1 text-warning"></i> {{ __('center::roles.system_role') }}</span>
                                            @else
                                                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10"><i class="fas fa-pen-fancy me-1"></i> {{ __('center::roles.custom_role') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- Showing random user count simulation or real if relation loaded --}}
                                            <span class="badge bg-light text-secondary border">{{ $role->users_count ?? 0 }} {{ __('center::roles.users') }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                {{-- View/Edit Button --}}
                                                <a href="{{ route('center.roles.edit', ['role' => $role->id, 'tenant' => $tenant->domain]) }}" 
                                                   class="btn btn-sm btn-light border" 
                                                   title="{{ is_null($role->tenant_id) ? __('center::roles.view_permissions') : __('center::roles.edit_permissions') }}">
                                                    <i class="fas {{ is_null($role->tenant_id) ? 'fa-eye text-secondary' : 'fa-edit text-primary' }}"></i>
                                                </a>

                                                {{-- Delete Button (Protected) --}}
                                                @if(!is_null($role->tenant_id))
                                                    <button type="button" class="btn btn-sm btn-light border text-danger" 
                                                            data-bs-toggle="modal" data-bs-target="#deleteRoleModal{{ $role->id }}"
                                                            title="{{ __('center::roles.delete') }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-light border disabled" disabled title="{{ __('center::roles.cannot_delete_system_role') }}">
                                                        <i class="fas fa-lock text-muted"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            @if(!is_null($role->tenant_id))
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteRoleModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header border-bottom-0">
                                                                <h5 class="modal-title fw-bold text-danger">{{ __('center::roles.delete_role') }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-start">
                                                                <p>{{ __('center::roles.delete_role_confirm_msg', ['role' => $role->name]) }}</p>
                                                            </div>
                                                            <div class="modal-footer border-top-0">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('center::roles.cancel') }}</button>
                                                                <form action="{{ route('center.roles.destroy', ['role' => $role->id, 'tenant' => $tenant->domain]) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">{{ __('center::roles.confirm_delete') }}</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="mb-3 p-3 rounded-circle bg-light text-secondary">
                                                    <i class="fas fa-shield-alt fa-2x opacity-50"></i>
                                                </div>
                                                <h6 class="fw-bold">{{ __('center::roles.no_roles_found') }}</h6>
                                                <p class="small text-muted mb-0">{{ __('center::roles.start_by_creating_role') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
