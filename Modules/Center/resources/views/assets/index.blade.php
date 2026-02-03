@extends('center::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::assets.title') }}</h2>
        <a href="{{ route('center.assets.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <span class="me-2">+</span> {{ __('center::assets.add_new') }}
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive" style="min-height: 350px;">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start">{{ __('center::assets.name') }}</th>
                            <th class="border-0">{{ __('center::assets.type') }}</th>
                            <th class="border-0">{{ __('center::assets.status') }}</th>
                            <th class="border-0">{{ __('center::assets.classroom') }}</th>
                            <th class="border-0 rounded-end">{{ __('center::assets.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            📦
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $asset->name }}</div>
                                            <small class="text-muted">{{ $asset->code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill px-3">
                                        {{ __('center::assets.' . $asset->type) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'active' => 'success',
                                            'maintenance' => 'warning',
                                            'broken' => 'danger',
                                            'lost' => 'secondary'
                                        ];
                                        $color = $statusColors[$asset->status] ?? 'info';
                                    @endphp
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3">
                                        {{ __('center::assets.' . $asset->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($asset->classroom)
                                        <span class="text-primary fw-medium"><i class="fas fa-door-open me-1"></i> {{ $asset->classroom->name }}</span>
                                    @else
                                        <span class="text-muted">{{ __('center::assets.none') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                            ⋮
                                        </button>
                                        <ul class="dropdown-menu border-0 shadow">
                                            <li><a class="dropdown-item" href="{{ route('center.assets.edit', $asset) }}"><i class="fas fa-edit me-2 text-warning"></i> {{ __('center::assets.edit') }}</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('center.assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('{{ __('center::assets.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item text-danger">حذف</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">{{ __('center::assets.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $assets->links() }}
            </div>
        </div>
    </div>
@endsection
