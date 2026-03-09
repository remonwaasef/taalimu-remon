@extends('instructor::components.layouts.master')

@section('page-title', 'نظرة عامة على نشاطك')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card p-4 border-0 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-user-graduate text-primary fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">إجمالي الطلاب</h6>
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
            <div class="card p-4 border-0 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-wallet text-warning fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">إيرادات الشهر</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($monthlyRevenue, 2) }} <small>ج.م</small></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Today's Schedule -->
    <div class="row g-4">
        <div class="col-lg-8" id="groups-section">
            <div class="card p-4 border-0 bg-white min-vh-50">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">المجموعات وإدارة التسجيل</h5>
                </div>
                
                <div class="table-responsive" style="min-height: 300px;">
                    <table class="table table-hover align-middle text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start">المجموعة</th>
                                <th class="border-0">الطلاب</th>
                                <th class="border-0">رابط التسجيل</th>
                                <th class="border-0 rounded-end">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td class="text-start fw-bold">{{ $course->title }}</td>
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
                                            <li><a class="dropdown-item" href="{{ route('instructor.groups.edit', $course->id) }}"><i class="fas fa-edit me-2 text-muted"></i> تعديل</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-link me-2 text-muted"></i> توليد رابط جديد</a></li>
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

        <div class="col-lg-4">
            <div class="card p-4 border-0 bg-white h-100">
                <h5 class="fw-bold mb-4">روابط سريعة</h5>
                <div class="d-grid gap-3">
                    <a href="{{ route('instructor.students.list') }}" class="btn btn-light text-start p-3 rounded-4 border-0 shadow-none d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-2 text-primary">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span>إضافة طالب جديد</span>
                    </a>
                    <a href="{{ route('instructor.groups.list') }}" class="btn btn-light text-start p-3 rounded-4 border-0 shadow-none d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 rounded-3 p-2 text-success">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <span>إنشاء مجموعة جديدة</span>
                    </a>
                    <button class="btn btn-light text-start p-3 rounded-4 border-0 shadow-none d-flex align-items-center gap-3" onclick="alert('سيكون متاحاً في المرحلة القادمة')">
                        <div class="bg-info bg-opacity-10 rounded-3 p-2 text-info">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <span>مسجل الغياب الذكي</span>
                    </button>
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
</script>
@endpush
@endsection
