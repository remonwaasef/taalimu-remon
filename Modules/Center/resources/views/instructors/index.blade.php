@extends('center::layouts.hope-master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::instructors.title') }}</h2>
        <a href="{{ route('center.instructors.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <span class="me-2">+</span> {{ __('center::instructors.add_new') }}
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4">
                    <h6 class="opacity-75 small fw-bold">{{ __('center::messages.blade_0443') }}</h6>
                    <h2 class="fw-bold mb-0">{{ $instructors->total() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <h6 class="text-muted small fw-bold">{{ __('center::messages.blade_0444') }}</h6>
                    <h2 class="fw-bold mb-0">{{ $activeCount }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <!-- Search -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <x-ui.search action="{{ route('center.instructors.index') }}" placeholder="{{ __('center::instructors.search_placeholder') }}" />
                </div>
            </div>

            <!-- Instructors Table -->
            <div class="table-responsive pb-5">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start">{{ __('center::instructors.name') }}</th>
                            <th class="border-0">{{ __('center::instructors.specialization') }}</th>
                            <th class="border-0">{{ __('center::instructors.status') }}</th>
                            <th class="border-0">{{ __('center::instructors.phone') }}</th>
                            <th class="border-0">{{ __('center::instructors.courses_count') }}</th>
                            <th class="border-0 rounded-end">{{ __('center::instructors.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($instructors as $instructor)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative me-3">
                                            @if($instructor->image)
                                                <img src="{{ Storage::url($instructor->image) }}" 
                                                     class="rounded-circle border shadow-sm" 
                                                     style="width: 45px; height: 45px; object-fit: cover;" 
                                                     alt="{{ $instructor->name }}"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            @endif
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                                 style="width: 45px; height: 45px; {{ $instructor->image ? 'display: none;' : '' }}">
                                                {{ mb_substr($instructor->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('center.instructors.show', $instructor->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">{{ $instructor->name }}</a>
                                            @if($instructor->hiring_date)
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ __('center::instructors.hired_on') }} {{ $instructor->hiring_date->format('Y/m/d') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $instructor->specialization ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'active' => 'success',
                                            'inactive' => 'secondary',
                                            'on_hold' => 'danger'
                                        ];
                                        $color = $statusColors[$instructor->status] ?? 'info';
                                    @endphp
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3 fw-bold" style="font-size: 0.75rem;">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                        {{ __('center::instructors.' . $instructor->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($instructor->email)
                                        <div class="small text-muted mb-1"><i class="far fa-envelope me-1"></i> {{ $instructor->email }}</div>
                                    @endif
                                    @if($instructor->phone)
                                        <div class="small text-muted"><i class="fas fa-phone-alt me-1"></i> {{ $instructor->phone }}</div>
                                    @endif
                                    @if(!$instructor->email && !$instructor->phone)
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $instructor->courses_count ?? 0 }}</td>
                                <td class="text-end px-4">
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-light rounded-circle shadow-none" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4">
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('center.instructors.show', $instructor->id) }}"><i class="far fa-eye me-2 text-primary opacity-75"></i> {{ __('center::instructors.show') }}</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('center.instructors.edit', $instructor->id) }}"><i class="far fa-edit me-2 text-success opacity-75"></i> {{ __('center::instructors.edit') }}</a></li>
                                            <li><hr class="dropdown-divider opacity-10"></li>
                                            <li>
                                                <form action="{{ route('center.instructors.destroy', $instructor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('center::instructors.confirm_delete_instructor') ?? __('center::messages.blade_0446') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item rounded-3 text-danger mb-0">
                                                        <i class="fas fa-trash-alt me-2 opacity-75"></i> {{ __('center::instructors.delete') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">{{ __('center::instructors.no_instructors') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $instructors->links('components.ui.pagination') }}
            </div>
        </div>
    </div>
@endsection
