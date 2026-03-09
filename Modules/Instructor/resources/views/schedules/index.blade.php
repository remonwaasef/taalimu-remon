<x-instructor::layouts.master>
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">جدول الحصص</h2>
            <p class="text-muted mb-0">نظرة عامة على جدول الحصص الأسبوعي لمجموعاتك.</p>
        </div>
        <a href="{{ route('instructor.schedules.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> إضافة موعد حصة
        </a>
    </div>

    @php
        $daysOfWeek = [
            0 => ['name' => 'الأحد', 'color' => 'primary'],
            1 => ['name' => 'الإثنين', 'color' => 'success'],
            2 => ['name' => 'الثلاثاء', 'color' => 'info'],
            3 => ['name' => 'الأربعاء', 'color' => 'warning'],
            4 => ['name' => 'الخميس', 'color' => 'danger'],
            5 => ['name' => 'الجمعة', 'color' => 'secondary'],
            6 => ['name' => 'السبت', 'color' => 'dark'],
        ];

        $groupedSchedules = $schedules->groupBy('day_of_week')->sortKeys();
    @endphp

    <div class="row g-4">
        @foreach($daysOfWeek as $dayIndex => $dayInfo)
            @if($groupedSchedules->has($dayIndex))
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2 rounded-3 bg-{{ $dayInfo['color'] }} bg-opacity-10 text-{{ $dayInfo['color'] }} me-3">
                            <i class="fas fa-calendar-day fa-lg"></i>
                        </div>
                        <h4 class="fw-bold mb-0">{{ $dayInfo['name'] }}</h4>
                        <div class="ms-auto flex-grow-1 mx-3 border-bottom opacity-10"></div>
                        <span class="badge bg-light text-dark rounded-pill">{{ count($groupedSchedules[$dayIndex]) }} حصة</span>
                    </div>

                    <div class="row g-3">
                        @foreach($groupedSchedules[$dayIndex]->sortBy('start_time') as $schedule)
                            @php
                                $confirmedBookings = $schedule->bookings->where('status', 'confirmed')->count();
                                $occupancyRate = $schedule->max_students > 0 ? ($confirmedBookings / $schedule->max_students) * 100 : 0;
                                $statusColor = $occupancyRate >= 100 ? 'danger' : ($occupancyRate > 80 ? 'warning' : 'success');
                            @endphp
                            <div class="col-md-6 col-xl-4">
                                <div class="card border-0 shadow-sm rounded-4 h-100 session-card">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <span class="badge bg-light text-primary rounded-pill mb-2 px-3 py-2">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - 
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                </span>
                                                <h5 class="fw-bold text-dark mb-1">{{ $schedule->course->title }}</h5>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                    <li><a class="dropdown-item" href="{{ route('instructor.schedules.edit', $schedule) }}"><i class="fas fa-edit me-2"></i> تعديل</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('instructor.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item text-danger"><i class="fas fa-trash me-2"></i> حذف</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-door-open text-muted me-2" style="width: 20px;"></i>
                                                <span class="text-secondary small">{{ $schedule->classroom->name ?? 'قاعة غير محددة' }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted fw-semibold">السعة</small>
                                                <small class="fw-bold text-{{ $statusColor }}">{{ $confirmedBookings }}/{{ $schedule->max_students }}</small>
                                            </div>
                                            <div class="progress rounded-pill shadow-none" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $statusColor }}" role="progressbar" style="width: {{ $occupancyRate }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if($groupedSchedules->isEmpty())
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 text-center p-5">
                    <div class="mb-3">
                        <i class="far fa-calendar-times display-1 text-light"></i>
                    </div>
                    <h4 class="text-muted">لا يوجد مواعيد مضافة في الجدول حالياً.</h4>
                    <div class="mt-3">
                        <a href="{{ route('instructor.schedules.create') }}" class="btn btn-primary rounded-pill px-4">
                            أضف أول موعد للحصة الان
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .session-card {
            transition: all 0.3s ease;
            cursor: default;
        }
        .session-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        }
        .progress-bar {
            transition: width 0.6s ease;
        }
    </style>
@endsection
</x-instructor::layouts.master>
