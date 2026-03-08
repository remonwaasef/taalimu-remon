@extends('instructor::components.layouts.master')

@section('page-title', 'إدارة المجموعات')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h3 class="fw-bold mb-0">المجموعات الدراسية</h3>
            <p class="text-muted small">إدارة المجموعات، روابط التسجيل، وعمليات التحضير</p>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="alert('سيكون متاحاً في المرحلة القادمة')">
                <i class="fas fa-plus me-2"></i> إنشاء مجموعة جديدة
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-start">المجموعة</th>
                            <th class="border-0">عدد الطلاب</th>
                            <th class="border-0">رابط التسجيل</th>
                            <th class="border-0">الحالة</th>
                            <th class="border-0">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr>
                            <td class="px-4 py-3 text-start">
                                <div class="fw-bold">{{ $course->title }}</div>
                                <div class="text-muted small">كود: {{ $course->code ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ $course->enrollments_count ?? 0 }} طالب</span>
                            </td>
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
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">نشطة</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu border-0 shadow-sm rounded-3">
                                        <li><a class="dropdown-item" href="{{ route('instructor.scanner', $course->id) }}"><i class="fas fa-qrcode me-2 text-primary"></i> تحضير (QR Scanner)</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2 text-muted"></i> تعديل</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="alert('سيتم توليد رابط جديد قريباً')"><i class="fas fa-link me-2 text-muted"></i> توليد رابط جديد</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/fogg-searching.png" alt="No data" style="width: 150px;" class="mb-3 opacity-50">
                                <h6 class="text-muted">لا يوجد مجموعات حالية</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
    alert("تم نسخ الرابط!");
}
</script>
@endpush
@endsection
