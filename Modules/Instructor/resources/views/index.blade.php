@extends('instructor::components.layouts.master')

@section('page-title', 'نظرة عامة على نشاطك')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card p-4 border-0 bg-white shadow-sm rounded-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3" style="background-color: rgba(58, 12, 163, 0.1);">
                        <i class="fas fa-user-graduate fs-4" style="color: var(--primary-color);"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small">إجمالي الطلاب</h6>
                        <h3 class="fw-bold mb-0">{{ $studentsCount }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 border-0 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-users text-success fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">المجموعات النشطة</h6>
                        <h3 class="fw-bold mb-0">{{ $courses->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 border-0 bg-white shadow-sm rounded-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-wallet text-warning fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small">إيرادات الشهر</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($monthlyRevenue, 2) }} <small class="fs-6">ج.م</small></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics & Quick Actions -->
    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card p-4 border-0 bg-white h-100 shadow-sm rounded-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">تحليلات الحضور الأسبوعية</h5>
                    <span class="badge rounded-pill px-3 py-2" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">آخر 7 أيام</span>
                </div>
                <div style="height: 300px;">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-4 border-0 bg-white h-100">
                <h5 class="fw-bold mb-4">روابط سريعة</h5>
                <div class="d-grid gap-3">
                    <a href="{{ route('instructor.students.list') }}" class="btn btn-light text-start p-3 rounded-4 border-0 shadow-none d-flex align-items-center gap-3 transition-all hover-translate">
                        <div class="rounded-3 p-2" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span class="fw-bold small">إضافة طالب جديد</span>
                    </a>
                    <a href="{{ route('instructor.groups.list') }}" class="btn btn-light text-start p-3 rounded-4 border-0 shadow-none d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 rounded-3 p-2 text-success">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <span>إنشاء مجموعة جديدة</span>
                    </a>
                    <a href="{{ route('instructor.attendance.index') }}" class="btn btn-light text-start p-3 rounded-4 border-0 shadow-none d-flex align-items-center gap-3">
                        <div class="bg-info bg-opacity-10 rounded-3 p-2 text-info">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <span>مسجل الغياب الذكي</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Groups Management Table -->
    <div class="row g-4">
        <div class="col-12" id="groups-section">
            <div class="card p-0 border-0 bg-white overflow-hidden">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0">المجموعات وإدارة التسجيل</h5>
                </div>
                
                <div class="table-responsive" style="min-height: 300px;">
                    <table class="table table-hover align-middle text-center mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3 text-start">المجموعة</th>
                                <th class="border-0">الطلاب</th>
                                <th class="border-0">رابط التسجيل</th>
                                <th class="border-0">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td class="px-4 text-start fw-bold">{{ $course->title }}</td>
                                <td>{{ $course->enrollments_count ?? 0 }}</td>
                                <td>
                                    @if($course->registration_token)
                                        <div class="input-group input-group-sm rounded-pill overflow-hidden" style="max-width: 250px; margin: 0 auto;">
                                            <input type="text" class="form-control border-0 bg-light" value="{{ $course->getRegistrationUrl() }}" readonly id="link_{{ $course->id }}">
                                            <button class="btn btn-primary px-3" onclick="copyLink('link_{{ $course->id }}')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-muted small">لا يوجد رابط</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                            <li><a class="dropdown-item" href="{{ route('instructor.scanner', $course->id) }}"><i class="fas fa-qrcode me-2 text-primary"></i> تحضير (QR Scanner)</a></li>
                                            <li><a class="dropdown-item" href="{{ route('instructor.groups.edit', $course->id) }}"><i class="fas fa-edit me-2 text-muted"></i> تعديل البيانات</a></li>
                                            <li>
                                                <form action="{{ route('instructor.groups.duplicate', $course->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item"><i class="fas fa-copy me-2 text-muted"></i> تكرار المجموعة</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('instructor.groups.rotate-link', $course->id) }}" method="POST" id="rotateFormDash_{{ $course->id }}">
                                                    @csrf
                                                    <button type="button" class="dropdown-item" onclick="if(confirm('هل أنت متأكد من تغيير رابط التسجيل؟ الروابط القديمة لن تعمل.')) document.getElementById('rotateFormDash_{{ $course->id }}').submit();">
                                                        <i class="fas fa-sync me-2 text-muted"></i> توليد رابط جديد
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('instructor.groups.destroy', $course->id) }}" method="POST" id="deleteFormDash_{{ $course->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="dropdown-item text-danger" onclick="if(confirm('هل أنت متأكد من حذف هذه المجموعة؟ سيتم إخفاؤها من النظام.')) document.getElementById('deleteFormDash_{{ $course->id }}').submit();">
                                                        <i class="fas fa-trash me-2"></i> حذف المجموعة
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
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
</div>

@push('scripts')
<script>
function copyLink(id) {
    var copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    // Optional: Show a toast or alert
    alert("تم نسخ الرابط!");
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
                    borderColor: '#3A0CA3',
                    backgroundColor: 'rgba(58, 12, 163, 0.05)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#3A0CA3',
                    borderWidth: 3,
                    pointHoverRadius: 6
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
                        backgroundColor: '#0f172a',
                        padding: 12,
                        titleFont: { family: 'Cairo', size: 14 },
                        bodyFont: { family: 'Cairo', size: 13 },
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                        ticks: { 
                            stepSize: 1,
                            font: { family: 'Cairo' }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Cairo' } }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
