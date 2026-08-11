@extends('center::layouts.app-next')

@section('title', __('center::roles.edit_role'))

@section('panel-content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 fw-bold text-primary me-2">{{ __('center::roles.edit_role') }}: {{ $role->name }}</h5>
                            @if(is_null($role->tenant_id))
                                <span class="badge bg-warning text-dark"><i class="fas fa-lock me-1"></i> {{ __('center::roles.system_role_readonly') }}</span>
                            @else
                                <span class="badge bg-success"><i class="fas fa-user-tag me-1"></i> {{ __('center::roles.custom_role') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('center.roles.index', ['tenant' => $tenant->domain]) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('center::roles.back_to_list') }}
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('center.roles.update', ['role' => $role->id, 'tenant' => $tenant->domain]) }}" method="POST" id="roleForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Role Name -->
                        <div class="mb-5" style="max-width: 600px;">
                            <label for="name" class="form-label fw-bold text-dark">{{ __('center::roles.role_name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror shadow-none p-2 border" 
                                   id="name" name="name" value="{{ old('name', $role->name) }}" 
                                   placeholder="{{ __('center::roles.role_name_placeholder') }}"
                                   @if(is_null($role->tenant_id)) disabled @endif>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(is_null($role->tenant_id))
                                <div class="form-text text-warning"><i class="fas fa-exclamation-triangle"></i> {{ __('center::roles.cannot_edit_system_role_name') }}</div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2 flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <h6 class="fw-bold mb-0 text-uppercase tracking-wider text-secondary">{{ __('center::roles.permissions_matrix') }}</h6>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3" id="selectedCount">
                                    {{ $role->permissions->count() }} {{ __('center::roles.selected_permissions') }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="position-relative">
                                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted small"></i>
                                    <input type="text" id="permissionSearch" class="form-control form-control-sm ps-5 border rounded-pill" style="width: 240px;" placeholder="{{ __('center::roles.search_permissions') }}">
                                </div>
                                @if(!is_null($role->tenant_id))
                                    <button type="button" class="btn btn-sm btn-light text-primary fw-bold" onclick="toggleAllPermissions()">
                                        {{ __('center::roles.toggle_all_globally') }}
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Permission Matrix -->
                        <div class="row g-4">
                            @foreach($permissions as $group => $perms)
                                <div class="col-md-6 col-xl-4 permission-group-card" data-group-name="{{ $group }}">
                                    <div class="card h-100 border shadow-none hover-shadow transition-all">
                                        <div class="card-header bg-light border-bottom-0 d-flex justify-content-between align-items-center py-2">
                                            <span class="fw-bold text-uppercase small text-dark">
                                                <i class="fas fa-layer-group me-1 text-muted"></i> {{ __('center::roles.group_' . $group) }}
                                            </span>
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input group-toggle" type="checkbox" role="switch" 
                                                       data-group="group-{{ Str::slug($group) }}" 
                                                       id="toggle_{{ Str::slug($group) }}"
                                                       title="{{ __('center::roles.select_all_in_group') }}"
                                                       @if(is_null($role->tenant_id)) disabled @endif>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="list-group list-group-flush">
                                                @foreach($perms as $permission)
                                                    <label class="list-group-item list-group-item-action d-flex align-items-center cursor-pointer border-0 py-2 px-3 permission-row">
                                                        <input class="form-check-input me-3 mt-0 group-{{ Str::slug($group) }} permission-checkbox" 
                                                               type="checkbox" name="permissions[]" 
                                                               value="{{ $permission->name }}" 
                                                               id="perm_{{ $permission->id }}"
                                                               {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                               @if(is_null($role->tenant_id)) disabled @endif>
                                                        <span class="small user-select-none permission-label">
                                                            {{ __('center::roles.perm_' . str_replace(' ', '_', $permission->name)) }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('center.roles.index', ['tenant' => $tenant->domain]) }}" class="btn btn-light me-2 px-4">{{ __('center::roles.cancel') }}</a>
                            @if(!is_null($role->tenant_id))
                                <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                    <i class="fas fa-save me-2"></i> {{ __('center::roles.save_changes') }}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Group Toggles state based on pre-checked items
        document.querySelectorAll('.group-toggle').forEach(toggle => {
            const groupClass = toggle.dataset.group;
            const allInGroup = document.querySelectorAll('.' + groupClass);
            if (allInGroup.length > 0) {
                const allChecked = Array.from(allInGroup).every(cb => cb.checked);
                toggle.checked = allChecked;
            }
        });
        
        function updateSelectedCount() {
            const count = Array.from(document.querySelectorAll('.permission-checkbox:not([disabled])')).filter(cb => cb.checked).length;
            const total = Array.from(document.querySelectorAll('.permission-checkbox')).filter(cb => cb.checked).length;
            document.getElementById('selectedCount').textContent = total + ' ' + '{{ __('center::roles.selected_permissions') }}';
        }

        function syncGroupToggle(groupClass) {
            const allInGroup = document.querySelectorAll('.' + groupClass);
            const toggle = document.querySelector('.group-toggle[data-group="' + groupClass + '"]');
            if (toggle && allInGroup.length > 0) {
                toggle.checked = Array.from(allInGroup).every(cb => cb.checked);
            }
        }

        // Handle Group Toggles
        document.querySelectorAll('.group-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const isChecked = this.checked;
                document.querySelectorAll('.' + this.dataset.group).forEach(checkbox => {
                    if (!checkbox.disabled) {
                        checkbox.checked = isChecked;
                    }
                });
                updateSelectedCount();
            });
        });

        // Handle Individual Checkbox Changes
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                let groupClass = null;
                this.classList.forEach(cls => {
                    if (cls.startsWith('group-')) groupClass = cls;
                });

                if (groupClass) {
                    syncGroupToggle(groupClass);
                }
                updateSelectedCount();
            });
        });

        // Permission search filter
        const searchInput = document.getElementById('permissionSearch');
        searchInput.addEventListener('input', function() {
            const term = this.value.trim().toLowerCase();
            document.querySelectorAll('.permission-group-card').forEach(group => {
                let visibleRows = 0;
                group.querySelectorAll('.permission-row').forEach(row => {
                    const label = (row.querySelector('.permission-label')?.textContent || '').toLowerCase();
                    const matches = !term || label.includes(term) || row.querySelector('.permission-checkbox').value.includes(term);
                    row.style.display = matches ? '' : 'none';
                    if (matches) visibleRows++;
                });
                group.style.display = visibleRows > 0 ? '' : 'none';
            });
        });

        updateSelectedCount();
    });

    function toggleAllPermissions() {
        const allCheckboxes = Array.from(document.querySelectorAll('.permission-checkbox:not([disabled])'));
        const allChecked = allCheckboxes.every(cb => cb.checked);
        
        allCheckboxes.forEach(cb => cb.checked = !allChecked);
        document.querySelectorAll('.group-toggle:not([disabled])').forEach(toggle => toggle.checked = !allChecked);
        
        const total = document.querySelectorAll('.permission-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = total + ' ' + '{{ __('center::roles.selected_permissions') }}';
    }
</script>

<style>
    .hover-shadow:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.05)!important;
        transform: translateY(-2px);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush
@endsection
