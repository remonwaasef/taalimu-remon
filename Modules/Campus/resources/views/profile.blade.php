@extends('campus::layouts.master')

@section('content')
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-2">الملف الشخصي</h2>
            <p class="text-muted mb-0">إدارة بياناتك الشخصية ومعلومات التواصل</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-ultra p-4 text-center">
                <div class="mx-auto mb-4 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold display-4" style="width: 120px; height: 120px;">
                    {{ substr($student->name, 0, 1) }}
                </div>
                <h4 class="fw-bold mb-1">{{ $student->name }}</h4>
                <p class="text-muted small mb-4">{{ $student->grade_level_name }}</p>
                <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-4">طالب نشط</div>
                
                <div class="mt-2 p-3 bg-white shadow-sm rounded-4 border">
                    <img src="{{ $qrcode }}" class="img-fluid" alt="QR Code" style="max-width: 150px;">
                    <p class="small text-muted mt-2 mb-0">كود الحضور الشخصي</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-ultra h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-0">المعلومات الشخصية</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">الاسم الكامل</label>
                            <div class="p-3 bg-light rounded-3 fw-bold">{{ $student->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">البريد الإلكتروني</label>
                            <div class="p-3 bg-light rounded-3 fw-bold">{{ $student->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">رقم الهاتف</label>
                            <div class="p-3 bg-light rounded-3 fw-bold text-ltr">{{ $student->phone }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">رقم هاتف ولي الأمر</label>
                            <div class="p-3 bg-light rounded-3 fw-bold text-ltr">{{ $student->parent_phone }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">المرحلة الدراسية</label>
                            <div class="p-3 bg-light rounded-3 fw-bold">{{ $student->grade_level_name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">كود الطالب</label>
                            <div class="p-3 bg-light rounded-3 fw-bold">STD-{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-ltr { direction: ltr; display: inline-block; }
    </style>
@endsection
