@extends('instructor::components.layouts.master')

@section('page-title', 'قائمة الطلاب')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h3 class="fw-bold mb-0">إدارة الطلاب</h3>
            <p class="text-muted small">عرض جميع الطلاب المسجلين في مجموعاتك</p>
        </div>
        <div class="col-auto">
            <div class="dropdown">
                <button class="btn btn-white bg-white shadow-sm rounded-pill px-4 dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-2 text-primary"></i> فلترة حسب المجموعة
                </button>
                <ul class="dropdown-menu border-0 shadow-sm rounded-3">
                    <li><a class="dropdown-item" href="#">الكل</a></li>
                    <!-- Future: Dynamic Course Filter -->
                </ul>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3">الطالب</th>
                            <th class="border-0">المجموعات المسجل بها</th>
                            <th class="border-0">الحالة</th>
                            <th class="border-0">تاريخ التسجيل</th>
                            <th class="border-0 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $student->name }}</div>
                                        <div class="text-muted small">{{ $student->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @foreach($student->enrollments as $enrollment)
                                    <span class="badge bg-light text-dark fw-normal rounded-pill border">{{ $enrollment->course->title ?? 'N/A' }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">نشط</span>
                            </td>
                            <td>{{ $student->created_at?->format('Y-m-d') ?? '--' }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->phone) }}" target="_blank" class="btn btn-light btn-sm rounded-circle p-2 mx-1 text-success">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <button class="btn btn-light btn-sm rounded-circle p-2 mx-1 text-primary">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/fogg-searching.png" alt="No data" style="width: 150px;" class="mb-3 opacity-50">
                                <h6 class="text-muted">لا يوجد طلاب مسجلين حالياً</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
