@extends('center::layouts.master')

@section('title', 'تقارير الدورات')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تقارير الدورات</h1>
        <a href="{{ route('center.analytics.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-right"></i> عودة للرئيسية
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">تحليل الدورات</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>الدورة</th>
                            <th>المدرس</th>
                            <th>عدد الطلاب المسجلين</th>
                            <th>عدد الحصص (الجداول)</th>
                            <th>السعر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                            <tr>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->instructor->name ?? 'غير محدد' }}</td>
                                <td>{{ $course->enrollments_count }}</td>
                                <td>{{ $course->schedules_count }}</td>
                                <td>{{ number_format($course->price) }} ج.م</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
