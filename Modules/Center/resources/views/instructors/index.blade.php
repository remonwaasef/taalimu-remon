@extends('center::layouts.master')

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
                    <h6 class="opacity-75 small fw-bold">إجمالي المدرسين</h6>
                    <h2 class="fw-bold mb-0">{{ $instructors->total() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <h6 class="text-muted small fw-bold">نشط حالياً</h6>
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
                            <th class="border-0">بيانات الاتصال</th>
                            <th class="border-0">{{ __('center::instructors.courses_count') }}</th>
                            <th class="border-0 rounded-end">{{ __('center::instructors.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($instructors as $instructor)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($instructor->image)
                                            <img src="{{ Storage::url($instructor->image) }}" class="rounded-circle me-3 border shadow-sm" style="width: 45px; height: 45px; object-fit: cover;" alt="{{ $instructor->name }}">
                                        @else
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 45px; height: 45px;">
                                                {{ mb_substr($instructor->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark">{{ $instructor->name }}</div>
                                            @if($instructor->hiring_date)
                                                <small class="text-muted" style="font-size: 0.7rem;">تعين في: {{ $instructor->hiring_date->format('Y/m/d') }}</small>
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
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                            ⋮
                                        </button>
                                        <ul class="dropdown-menu border-0 shadow">
                                            <li><a class="dropdown-item" href="{{ route('center.instructors.edit', $instructor->id) }}">{{ __('center::instructors.edit') }}</a></li>
                                            <li>
                                                <form action="{{ route('center.instructors.destroy', $instructor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من عملية الحذف؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger action-delete">
                                                        {{ __('center::instructors.delete') }}
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
