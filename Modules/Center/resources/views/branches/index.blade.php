@extends('center::layouts.app-next')

@section('title', __('center::branches.title'))

@section('panel-content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-building me-2"></i> {{ __('center::branches.title') }}</h5>
                <a href="{{ route('center.branches.create', ['tenant' => $tenant->domain]) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> {{ __('center::branches.add_new') }}
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" data-mobile-cards>
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">{{ __('center::branches.branch_name') }}</th>
                                <th>{{ __('center::branches.address') }}</th>
                                <th>{{ __('center::branches.phone') }}</th>
                                <th>{{ __('center::branches.manager') }}</th>
                                <th class="text-end pe-4">{{ __('center::branches.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branches as $branch)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $branch->name }}</td>
                                    <td>{{ $branch->address ?? '-' }}</td>
                                    <td>{{ $branch->phone ?? '-' }}</td>
                                    <td>
                                        @if($branch->manager)
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 25px; height: 25px; font-size: 0.75rem;">
                                                    {{ substr($branch->manager->name, 0, 1) }}
                                                </div>
                                                <span>{{ $branch->manager->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted small">{{ __('center::branches.not_assigned') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('center.branches.edit', ['branch' => $branch->id, 'tenant' => $tenant->domain]) }}" class="btn btn-sm btn-light text-primary me-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('center.branches.destroy', ['branch' => $branch->id, 'tenant' => $tenant->domain]) }}" method="POST" class="d-inline" id="deleteRowForm_1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-confirm-delete data-form="deleteRowForm_1" class="btn btn-sm btn-light text-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-building fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0">{{ __('center::branches.no_branches_found') }}</p>
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
@endsection
