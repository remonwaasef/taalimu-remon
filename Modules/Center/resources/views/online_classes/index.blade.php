@extends('center::layouts.master')

@section('title', 'تقارير الدروس الأونلاين')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto mb-3 mb-md-0">
            <h3 class="fw-bold mb-1">الدروس الأونلاين (Live Classes)</h3>
            <p class="text-muted small mb-0">مراقبة وإدارة الجلسات المباشرة لجميع المعلمين في المركز</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-video fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">إجمالي الدروس</p>
                            <h4 class="fw-bold mb-0 text-dark">{{ $stats['total'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-3">
                            <i class="fas fa-calendar-alt fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">مجدولة</p>
                            <h4 class="fw-bold mb-0 text-dark">{{ $stats['scheduled'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 me-3">
                            <i class="fas fa-signal fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">قيد الانعقاد</p>
                            <h4 class="fw-bold mb-0 text-dark">{{ $stats['in_progress'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                            <i class="fas fa-check-circle fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">مكتملة</p>
                            <h4 class="fw-bold mb-0 text-dark">{{ $stats['completed'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('center.online_classes.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">تصفية بالمعلم</label>
                    <select name="instructor_id" class="form-select rounded-pill">
                        <option value="">جميع المعلمين</option>
                        @foreach($instructors as $inst)
                            <option value="{{ $inst->id }}" {{ request('instructor_id') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">حالة الدرس</label>
                    <select name="status" class="form-select rounded-pill">
                        <option value="">كافة الحالات</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مجدول</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد الانعقاد</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>منتهي</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">التاريخ</label>
                    <input type="date" name="date" class="form-control rounded-pill" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary rounded-pill w-100"><i class="fas fa-filter me-2"></i> تصفية النتائج</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Classes Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-start">تفاصيل الدرس</th>
                            <th class="border-0">المعلم والمجموعة</th>
                            <th class="border-0">توقيت البدء</th>
                            <th class="border-0">الرابط والبيانات</th>
                            <th class="border-0">الحالة</th>
                            <th class="border-0">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($onlineClasses as $lesson)
                        <tr>
                            <td class="px-4 py-3 text-start">
                                <div class="fw-bold" style="color: var(--bs-primary);">{{ $lesson->title }}</div>
                                <div class="text-muted small"><i class="fas fa-desktop me-1"></i> {{ ucfirst($lesson->platform) }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $lesson->instructor->name ?? 'N/A' }}</div>
                                <div class="text-muted small">{{ $lesson->course->title ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div>{{ $lesson->start_time->format('Y-m-d') }}</div>
                                <div class="text-muted small fw-bold">{{ $lesson->start_time->format('h:i A') }} ({{ $lesson->duration_minutes }} دقيقة)</div>
                            </td>
                            <td>
                                <a href="{{ $lesson->meeting_link }}" target="_blank" class="btn btn-sm btn-light rounded-pill px-3 text-primary border">
                                    <i class="fas fa-external-link-alt me-1"></i> فتح الرابط
                                </a>
                                @if($lesson->meeting_id)
                                    <div class="text-muted small mt-1">ID: {{ $lesson->meeting_id }}</div>
                                @endif
                            </td>
                            <td>
                                @if($lesson->status == 'scheduled')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">مجدول</span>
                                @elseif($lesson->status == 'in_progress')
                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">قيد الانعقاد</span>
                                @elseif($lesson->status == 'completed')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">منتهي</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">ملغي</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                        <i class="fas fa-ellipsis-v text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2">
                                        <li>
                                            <form action="{{ route('center.online_classes.destroy', $lesson->id) }}" method="POST" id="deleteForm_{{ $lesson->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger" onclick="if(confirm('هل أنت متأكد من حذف هذه الجلسة؟ سيؤدي ذلك لإلغائها للمرحلة والطلاب.')) document.getElementById('deleteForm_{{ $lesson->id }}').submit();">
                                                    <i class="fas fa-trash me-2"></i> إلغاء وحذف الدرس
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/fogg-searching.png" alt="No data" style="width: 150px;" class="mb-3 opacity-50">
                                <h6 class="text-muted">لا توجد دروس مطابقة لشروط البحث.</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($onlineClasses->hasPages())
                <div class="p-3 border-top">
                    {{ $onlineClasses->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
