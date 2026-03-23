@extends('instructor::components.layouts.master')

@section('page-title', 'نظرة عامة على نشاطك التعليمي')

@section('content')
<div class="container-fluid py-2">
    <!-- Top Stats Row -->
    <div class="row g-4 mb-5 animate__animated animate__fadeIn">
        <div class="col-md-4">
            <div class="stats-card p-4 h-100 position-relative border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #162963 0%, #2a4395 100%); color: white;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="opacity-75 mb-1 fw-bold small text-uppercase letter-spacing-1">{{ __('instructor::dashboard.total_students') }}</h6>
                        <h1 class="fw-bold mb-0 display-5">{{ number_format($totalStudents) }}</h1>
                    </div>
                    <div class="p-3 rounded-4 bg-white bg-opacity-20 backdrop-blur">
                        <i class="fas fa-user-graduate fs-3 text-white"></i>
                    </div>
                </div>
                <div class="mt-4 pt-2">
                    <div class="d-flex align-items-center mb-2">
                        <span class="small fw-bold me-2">طالب نشط</span>
                        <span class="ms-auto small opacity-75">70%</span>
                    </div>
                    <div class="progress" style="height: 6px; background: rgba(255, 255, 255, 0.15);">
                        <div class="progress-bar bg-white" style="width: 70%"></div>
                    </div>
                </div>
                <div class="position-absolute end-0 bottom-0 opacity-10 mb-n3 me-n3">
                    <i class="fas fa-graduation-cap fa-6x"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stats-card p-4 h-100 position-relative border-0 shadow-sm bg-white hover-lift">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-1 fw-bold small text-uppercase letter-spacing-1">{{ __('instructor::dashboard.active_groups') }}</h6>
                        <h1 class="fw-bold mb-0 display-5 text-dark">{{ number_format($totalCourses) }}</h1>
                    </div>
                    <div class="p-3 rounded-4 bg-success bg-opacity-10">
                        <i class="fas fa-layer-group fs-3 text-success"></i>
                    </div>
                </div>
                <div class="mt-4 pt-2">
                    <div class="d-flex align-items-center mb-2">
                        <span class="small fw-bold text-muted me-2">مجموعات فعالة</span>
                        <span class="ms-auto small text-success">مكتملة</span>
                    </div>
                    <div class="progress" style="height: 6px; background: #f1f5f9;">
                        <div class="progress-bar bg-success" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card p-4 h-100 position-relative border-0 shadow-sm bg-white hover-lift">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-1 fw-bold small text-uppercase letter-spacing-1">{{ __('instructor::dashboard.monthly_revenue') }}</h6>
                        <h1 class="fw-bold mb-0 display-5 text-dark">
                            {{ number_format($monthlyRevenue) }}
                            <small class="fs-6 fw-normal text-muted">{{ __('instructor::dashboard.currency') }}</small>
                        </h1>
                    </div>
                    <div class="p-3 rounded-4 bg-info bg-opacity-10">
                        <i class="fas fa-coins fs-3 text-info"></i>
                    </div>
                </div>
                <div class="mt-4 pt-2">
                    <div class="d-flex align-items-center mb-2">
                        <span class="small fw-bold text-muted me-2">إيرادات هذا الشهر</span>
                        <span class="ms-auto small text-info"><i class="fas fa-arrow-trend-up me-1"></i> 12% +</span>
                    </div>
                    <div class="progress" style="height: 6px; background: #f1f5f9;">
                        <div class="progress-bar bg-info" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics & Quick Links -->
    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">{{ __('instructor::dashboard.attendance_analytics') }}</h5>
                        <p class="text-muted small mb-0">معدل حضور الطلاب خلال آخر 7 أيام</p>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-light border-0 px-3 rounded-pill active">يومي</button>
                        <button class="btn btn-sm btn-light border-0 px-3 rounded-pill ms-2">شهري</button>
                    </div>
                </div>
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <h5 class="fw-bold mb-4 text-dark">{{ __('instructor::dashboard.quick_links') }}</h5>
                <div class="d-grid gap-3">
                    <a href="{{ route('instructor.students.create') }}" class="quick-action-btn bg-primary-subtle p-3 rounded-4 border-0 hover-lift text-decoration-none">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-primary text-white me-3">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ __('instructor::dashboard.add_new_student') }}</div>
                                <small class="text-muted">إضافة طالب جديد للمنصة</small>
                            </div>
                            <div class="ms-auto text-muted"><i class="fas fa-chevron-left small"></i></div>
                        </div>
                    </a>
                    
                    <a href="{{ route('instructor.groups.create') }}" class="quick-action-btn bg-success-subtle p-3 rounded-4 border-0 hover-lift text-decoration-none">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-success text-white me-3">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ __('instructor::dashboard.create_new_group') }}</div>
                                <small class="text-muted">إنشاء مجموعة أو مادة جديدة</small>
                            </div>
                            <div class="ms-auto text-muted"><i class="fas fa-chevron-left small"></i></div>
                        </div>
                    </a>
                    
                    <a href="{{ route('instructor.attendance.index') }}" class="quick-action-btn bg-info-subtle p-3 rounded-4 border-0 hover-lift text-decoration-none">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-info text-white me-3">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ __('instructor::dashboard.smart_attendance') }}</div>
                                <small class="text-muted">تسجيل الحضور عبر QR Code</small>
                            </div>
                            <div class="ms-auto text-muted"><i class="fas fa-chevron-left small"></i></div>
                        </div>
                    </a>
                </div>
                
                <div class="mt-4 p-3 bg-light rounded-4 border border-dashed text-center">
                    <p class="small text-muted mb-0">تحتاج مساعدة؟ تواصل مع الدعم الفني</p>
                    <a href="#" class="btn btn-link btn-sm text-primary fw-bold text-decoration-none">فتح تذكرة دعم</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Groups Section -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-list-ul me-2 text-primary"></i> {{ __('instructor::dashboard.groups_and_registration') }}</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm rounded-pill px-3 border-light" placeholder="بحث عن مجموعة..." style="max-width: 200px;">
                <a href="{{ route('instructor.groups.list') }}" class="btn btn-sm btn-primary px-3 rounded-pill">عرض الكل</a>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-muted fw-bold small text-uppercase">المجموعة</th>
                            <th class="border-0 py-3 text-muted fw-bold small text-uppercase">الطلاب</th>
                            <th class="border-0 py-3 text-muted fw-bold small text-uppercase">رابط التسجيل الذكي</th>
                            <th class="border-0 text-end px-4 py-3 text-muted fw-bold small text-uppercase">عمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-book-reader"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $course->title }}</div>
                                        <div class="text-muted x-small">عبر: {{ $course->schedules->count() }} حصص مبرمجة</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary rounded-pill px-3 py-2 bg-opacity-10 text-primary fw-bold">
                                        <i class="fas fa-users me-1"></i> {{ $course->enrollments_count ?? 0 }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($course->registration_token)
                                <div class="input-group input-group-sm bg-light rounded-pill p-1 border" style="max-width: 320px;">
                                    <input type="text" class="form-control bg-transparent border-0 px-3 x-small text-muted" value="{{ route('student.portal', $course->registration_token) }}" id="link-{{ $course->id }}" readonly>
                                    <button class="btn btn-primary rounded-pill px-3 py-1" onclick="copyLink('link-{{ $course->id }}')">
                                        <i class="fas fa-copy me-1"></i> نسخ
                                    </button>
                                </div>
                                @else
                                <span class="text-muted small italic">لا يوجد رابط نشط</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                <div class="btn-group">
                                    <a href="{{ route('instructor.scanner', $course->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 me-2">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle shadow-none border" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2 animate__animated animate__fadeInUp animate__faster">
                                            <li><a class="dropdown-item py-2 rounded-3" href="{{ route('instructor.groups.edit', $course->id) }}"><i class="fas fa-edit me-2 text-success"></i> تعديل البيانات</a></li>
                                            <li>
                                                <form action="{{ route('instructor.groups.duplicate', $course->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item py-2 rounded-3"><i class="fas fa-copy me-2 text-info"></i> تكرار المجموعة</button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider opacity-50"></li>
                                            <li><a class="dropdown-item py-2 rounded-3" href="{{ route('instructor.scanner', $course->id) }}"><i class="fas fa-qrcode me-2 text-primary"></i> مسح الحضور</a></li>
                                            <li>
                                                <form action="{{ route('instructor.groups.rotate-link', $course->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من توليد رابط جديد؟ سيتم إلغاء الرابط القديم.')">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item py-2 rounded-3 text-warning"><i class="fas fa-sync-alt me-2"></i> تجديد الرابط</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('instructor.groups.destroy', $course->id) }}" method="POST" onsubmit="return confirm('حذف المجموعة نهائياً؟ هذا الإجراء لا يمكن التراجع عنه.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 rounded-3 text-danger mt-1"><i class="fas fa-trash-alt me-2"></i> حذف المجموعة</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .letter-spacing-1 { letter-spacing: 0.5px; }
    .backdrop-blur { backdrop-filter: blur(10px); }
    .icon-circle {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-primary-subtle { background-color: rgba(22, 41, 99, 0.05); }
    .bg-success-subtle { background-color: rgba(34, 197, 94, 0.05); }
    .bg-info-subtle { background-color: rgba(13, 202, 240, 0.05); }
    
    .quick-action-btn {
        transition: all 0.3s ease;
        border: 1px solid transparent !important;
    }
    .quick-action-btn:hover {
        background-color: white !important;
        border-color: rgba(0,0,0,0.05) !important;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(22, 41, 99, 0.02);
    }
    
    .x-small { font-size: 0.75rem; }
    
    .animate__faster { animation-duration: 0.3s; }
</style>

@push('scripts')
<script>
function copyLink(id) {
    var copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    var btn = copyText.nextElementSibling;
    var originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i> تم النسخ';
    btn.classList.add('btn-success');
    btn.classList.remove('btn-primary');
    
    setTimeout(function() {
        btn.innerHTML = originalHTML;
        btn.classList.remove('btn-success');
        btn.classList.add('btn-primary');
    }, 2000);
}

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($days) !!},
                datasets: [{
                    label: 'عدد الحاضرين',
                    data: {!! json_encode($attendanceData) !!},
                    borderColor: '#162963',
                    backgroundColor: (context) => {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        if (!chartArea) return null;
                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, 'rgba(22, 41, 99, 0)');
                        gradient.addColorStop(1, 'rgba(22, 41, 99, 0.1)');
                        return gradient;
                    },
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#162963',
                    borderWidth: 3,
                    pointHoverRadius: 6,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#162963',
                        padding: 12,
                        titleFont: { family: 'Cairo', size: 14, weight: 'bold' },
                        bodyFont: { family: 'Cairo', size: 13 },
                        cornerRadius: 12,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                        ticks: { 
                            stepSize: 1,
                            font: { family: 'Cairo', size: 11 },
                            color: '#64748b'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            font: { family: 'Cairo', size: 11 },
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection

