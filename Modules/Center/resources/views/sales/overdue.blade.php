@extends('center::layouts.master')

@section('title', 'الطلاب المتأخرون عن الدفع')

@section('content')
<div class="mb-4 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
            <i class="fas fa-wallet fa-lg"></i>
        </div>
        <div>
            <h2 class="fw-bold text-dark mb-0">قائمة المتأخرين عن الدفع</h2>
            <p class="text-muted small mb-0">عرض جميع الطلاب الذين لديهم مبالغ متبقية لم يتم تحصيلها بالكامل.</p>
        </div>
    </div>
    <div class="badge bg-danger rounded-pill px-3 py-2">
        إجمالي الطلاب: {{ $students->count() }}
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 ps-4">الطالب</th>
                        <th class="border-0">رقم الهاتف</th>
                        <th class="border-0">إجمالي المديونية</th>
                        <th class="border-0 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <span class="fw-bold text-dark">{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="text-muted">{{ $student->phone }}</td>
                            <td>
                                <span class="fw-bold text-danger">{{ number_format($student->total_debt, 2) }} {{ __('center::sales.currency') }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('center.sales.account', ['tenant' => $tenant->domain, 'student_id' => $student->id]) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                        <i class="fas fa-user-invoice me-1"></i> تحصيل
                                    </a>
                                    <a href="{{ route('center.students.show', ['tenant' => $tenant->domain, 'student' => $student->id]) }}" class="btn btn-outline-light text-dark btn-sm rounded-pill px-3 border">
                                        <i class="fas fa-user me-1"></i> الملف
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h5 class="fw-bold">لا يوجد متأخرات</h5>
                                    <p class="mb-0">جميع الطلاب قاموا بسداد مستحقاتهم بالكامل.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // If we want to auto-select the student in the account page, we can pass the ID
    // The account page already has logic for loading a student via query param if we add it
</script>
@endpush
@endsection
