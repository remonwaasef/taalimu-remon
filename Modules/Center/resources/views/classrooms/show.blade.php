@extends('center::layouts.master')

@section('content')
<div class="animate__animated animate__fadeIn">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('center.classrooms.index') }}">إدارة القاعات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">تفاصيل القاعة</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark mb-0">{{ $classroom->name }}</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('center.classrooms.edit', $classroom) }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-edit me-1"></i> تعديل
            </a>
            <a href="{{ route('center.classrooms.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="fas fa-arrow-right me-1"></i> عودة
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Stats Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm border border-4 border-white" style="width: 80px; height: 80px; font-size: 2rem; background-color: {{ $classroom->color ?? '#435ebe' }}; color: white;">
                             @if($classroom->type == 'lab') 💻 @elseif($classroom->type == 'virtual') 🌐 @else 🏢 @endif
                        </div>
                        <h4 class="fw-bold mb-1">{{ $classroom->name }}</h4>
                        <span class="badge bg-light text-primary border border-primary border-opacity-10 px-3 py-2 rounded-pill">
                            {{ $classroom->type == 'lab' ? 'معمل حاسب' : ($classroom->type == 'virtual' ? 'قاعة افتراضية' : 'قاعة محاضرات') }}
                        </span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <div class="p-3 bg-light rounded-4 d-flex align-items-center">
                            <div class="bg-white rounded-3 p-2 me-3 shadow-sm">
                                <i class="fas fa-users text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">السعة الاستيعابية</small>
                                <span class="fw-bold text-dark">{{ $classroom->capacity ?? 'غير محدد' }} طالب</span>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-4 d-flex align-items-center">
                            <div class="bg-white rounded-3 p-2 me-3 shadow-sm">
                                <i class="fas fa-calendar-alt text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">إجمالي الحصص</small>
                                <span class="fw-bold text-dark">{{ $classroom->schedules->count() }} حصة أسبوعية</span>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-4 d-flex align-items-center">
                            <div class="bg-white rounded-3 p-2 me-3 shadow-sm">
                                <i class="fas fa-clock text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">تاريخ الإضافة</small>
                                <span class="fw-bold text-dark">{{ $classroom->created_at->format('Y-m-d') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content (Tabs) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <ul class="nav nav-tabs border-0" id="classroomTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active border-0 fw-bold text-dark position-relative py-3" id="scheduleTab" data-bs-toggle="tab" data-bs-target="#scheduleContent" type="button" role="tab" aria-controls="scheduleContent" aria-selected="true">
                                <i class="fas fa-calendar-week me-2 text-primary"></i> الجدول الأسبوعي
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link border-0 fw-bold text-dark position-relative py-3" id="assetsTab" data-bs-toggle="tab" data-bs-target="#assetsContent" type="button" role="tab" aria-controls="assetsContent" aria-selected="false">
                                <i class="fas fa-box me-2 text-primary"></i> العُهد والأصول ({{ $classroom->assets->count() }})
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="tab-content mt-4" id="classroomTabsContent">
                        <!-- Schedule Tab -->
                        <div class="tab-pane fade show active" id="scheduleContent" role="tabpanel" aria-labelledby="scheduleTab">
                            @if($classroom->schedules->isEmpty())
                                <div class="text-center py-5">
                                    <div class="text-muted opacity-50 mb-3">
                                        <i class="fas fa-calendar-times fa-4x"></i>
                                    </div>
                                    <h5 class="text-muted">لا توجد حصص مجدولة في هذه القاعة حالياً</h5>
                                    <a href="{{ route('center.schedules.create') }}" class="btn btn-primary rounded-pill mt-3">
                                        <i class="fas fa-plus me-1"></i> جدولة حصة جديدة
                                    </a>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-0 rounded-start">اليوم</th>
                                                <th class="border-0">الموعد</th>
                                                <th class="border-0">الدورة التدريبية</th>
                                                <th class="border-0">المدرس</th>
                                                <th class="border-0 rounded-end">الطلاب</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $days = [
                                                    'Saturday' => 'السبت',
                                                    'Sunday' => 'الأحد',
                                                    'Monday' => 'الاثنين',
                                                    'Tuesday' => 'الثلاثاء',
                                                    'Wednesday' => 'الأربعاء',
                                                    'Thursday' => 'الخميس',
                                                    'Friday' => 'الجمعة'
                                                ];
                                            @endphp
                                            @foreach($classroom->schedules as $schedule)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-3">
                                                            {{ $days[$schedule->day_of_week] ?? $schedule->day_of_week }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="small fw-bold text-dark">
                                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                                            <span class="text-muted px-1">-</span>
                                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold">{{ optional($schedule->course)->title ?? 'غير محدد' }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-user-tie text-muted me-2 small"></i>
                                                            <span class="small">{{ optional($schedule->instructor)->name ?? 'غير محدد' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border rounded-pill px-2">
                                                            {{ $schedule->bookings_count ?? $schedule->bookings()->count() }} / {{ $schedule->max_students }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Assets Tab -->
                        <div class="tab-pane fade" id="assetsContent" role="tabpanel" aria-labelledby="assetsTab">
                            @if($classroom->assets->isEmpty())
                                <div class="text-center py-5">
                                    <div class="text-muted opacity-50 mb-3">
                                        <i class="fas fa-boxes fa-4x"></i>
                                    </div>
                                    <h5 class="text-muted">لا توجد عُهد مسجلة لهذه القاعة</h5>
                                    <a href="{{ route('center.assets.create', ['classroom_id' => $classroom->id]) }}" class="btn btn-primary rounded-pill mt-3">
                                        <i class="fas fa-plus me-1"></i> إضافة عُهدة جديدة
                                    </a>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-0 rounded-start">الاسم</th>
                                                <th class="border-0">النوع</th>
                                                <th class="border-0">الحالة</th>
                                                <th class="border-0 rounded-end">العمليات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($classroom->assets as $asset)
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold">{{ $asset->name }}</div>
                                                        <small class="text-muted">{{ $asset->code }}</small>
                                                    </td>
                                                    <td>{{ __('center::assets.' . $asset->type) }}</td>
                                                    <td>
                                                        @php
                                                            $color = match($asset->status) {
                                                                'active' => 'success',
                                                                'maintenance' => 'warning',
                                                                'broken' => 'danger',
                                                                'lost' => 'secondary',
                                                                default => 'info'
                                                            };
                                                        @endphp
                                                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3">
                                                            {{ __('center::assets.' . $asset->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('center.assets.edit', $asset) }}" class="btn btn-sm btn-light rounded-circle"><i class="fas fa-edit"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('center.assets.create', ['classroom_id' => $classroom->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="fas fa-plus me-1"></i> إضافة قطعة أخرى
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div> <!-- End tab-content -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
