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
                        <h3 class="fw-bold mb-0">124</h3>
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
                        <h3 class="fw-bold mb-0">8</h3>
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
                        <h3 class="fw-bold mb-0">12,500 <small>ج.م</small></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Today's Schedule -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card p-4 border-0 bg-white min-vh-50">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">حصص اليوم</h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start">المجموعة</th>
                                <th class="border-0">الوقت</th>
                                <th class="border-0">المكان</th>
                                <th class="border-0 rounded-end text-center">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>فيزياء 3 ثانوي (أ)</td>
                                <td><span class="badge bg-light text-dark fw-normal px-3 py-2">05:00 م</span></td>
                                <td>قاعة الأمل</td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm rounded-pill px-3">بدء التحضير</button>
                                </td>
                            </tr>
                            <tr>
                                <td>رياضيات 2 ثانوي</td>
                                <td><span class="badge bg-light text-dark fw-normal px-3 py-2">07:30 م</span></td>
                                <td>قاعة التميز</td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm rounded-pill px-3">بدء التحضير</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-4 border-0 bg-white h-100">
                <h5 class="fw-bold mb-4">روابط سريعة</h5>
                <div class="d-grid gap-3">
                    <button class="btn btn-light text-start p-3 rounded-4 border-0 shadow-none d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-2 text-primary">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span>إضافة طالب جديد</span>
                    </button>
                    <button class="btn btn-light text-start p-3 rounded-4 border-0 shadow-none d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 rounded-3 p-2 text-success">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <span>إنشاء مجموعة جديدة</span>
                    </button>
                    <button class="btn btn-light text-start p-3 rounded-4 border-0 shadow-none d-flex align-items-center gap-3">
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
@endsection
