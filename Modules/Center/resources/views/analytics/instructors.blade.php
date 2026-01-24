@extends('center::layouts.master')

@section('title', 'تقارير المدرسين')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تقارير المدرسين</h1>
        <a href="{{ route('center.analytics.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-right"></i> عودة للرئيسية
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">أداء المدرسين</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>المدرس</th>
                            <th>عدد الدورات</th>
                            <th>إجمالي الطلاب</th>
                            <th>التقييم (قريباً)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($instructorStats as $instructor)
                            <tr>
                                <td>{{ $instructor->name }}</td>
                                <td>{{ $instructor->courses_count }}</td>
                                <td>{{ $instructor->total_students }}</td>
                                <td>-</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
