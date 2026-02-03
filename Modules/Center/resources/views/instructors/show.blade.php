@extends('center::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::instructors.show') }}</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('center.instructors.edit', $instructor->id) }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-edit me-2"></i> {{ __('center::instructors.edit') }}
            </a>
            <a href="{{ route('center.instructors.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                {{ __('center::index.back') }}
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar: Basic Info & Profile Pic -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-body text-center p-5">
                    <div class="position-relative d-inline-block mb-4">
                        @if($instructor->image)
                            <img src="{{ Storage::url($instructor->image) }}" class="rounded-circle border border-4 border-white shadow" style="width: 150px; height: 150px; object-fit: cover;" alt="{{ $instructor->name }}">
                        @else
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow" style="width: 150px; height: 150px; font-size: 3rem;">
                                {{ mb_substr($instructor->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="position-absolute bottom-0 end-0 p-2 bg-white rounded-circle shadow-sm">
                            @php
                                $statusColors = [
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'on_hold' => 'danger'
                                ];
                                $color = $statusColors[$instructor->status] ?? 'info';
                            @endphp
                            <i class="fas fa-circle text-{{ $color }}"></i>
                        </span>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $instructor->name }}</h4>
                    <p class="text-muted mb-3">{{ $instructor->specialization ?? '-' }}</p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3 py-2 fw-bold">
                            {{ __('center::instructors.' . $instructor->status) }}
                        </span>
                    </div>

                    <hr class="opacity-10">

                    <div class="text-start mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3 text-primary"><i class="fas fa-envelope fa-fw"></i></div>
                            <div>
                                <small class="text-muted d-block">{{ __('center::instructors.email') }}</small>
                                <span class="fw-bold">{{ $instructor->email ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3 text-success"><i class="fas fa-phone fa-fw"></i></div>
                            <div>
                                <small class="text-muted d-block">{{ __('center::instructors.phone') }}</small>
                                <span class="fw-bold">{{ $instructor->phone ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">إحصائيات سريعة</h6>
                    <div class="row text-center g-3">
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3">
                                <h3 class="fw-bold mb-0 text-primary">{{ $instructor->courses_count ?? $instructor->courses()->count() }}</h3>
                                <small class="text-muted">دورات</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3">
                                <h3 class="fw-bold mb-0 text-success">{{ $instructor->commission_rate }}%</h3>
                                <small class="text-muted">العمولة</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0">المعلومات الشخصية والإدارية</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">{{ __('center::instructors.gender') }}</label>
                            <span class="fw-bold">{{ $instructor->gender ? __('center::instructors.' . $instructor->gender) : '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">{{ __('center::instructors.hiring_date') }}</label>
                            <span class="fw-bold text-primary">{{ $instructor->hiring_date ? $instructor->hiring_date->format('Y/m/d') : '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">{{ __('center::instructors.national_id') }}</label>
                            <span class="fw-bold">{{ $instructor->national_id ?? '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">{{ __('center::instructors.commission_rate') }}</label>
                            <span class="fw-bold text-success">{{ $instructor->commission_rate }}% من مبيعات الدورات</span>
                        </div>
                        <div class="col-12">
                            <hr class="opacity-10 my-2">
                            <label class="text-muted small d-block mb-2">{{ __('center::instructors.bio') }}</label>
                            <p class="text-dark bg-light p-3 rounded-3 mb-0" style="white-space: pre-line;">{{ $instructor->bio ?? 'لا توجد نبذة تعريفية.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Future Feature: Financial Records / History -->
            <div class="card border-0 shadow-sm rounded-4 bg-light">
                <div class="card-body p-5 text-center">
                    <div class="mb-3"><i class="fas fa-file-invoice-dollar fa-3x opacity-25"></i></div>
                    <h6 class="fw-bold">السجل المالي</h6>
                    <p class="text-muted small mb-0">قريباً: ستتمكن من متابعة أرباح المدرس وعمولاته المسددة من هنا.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
