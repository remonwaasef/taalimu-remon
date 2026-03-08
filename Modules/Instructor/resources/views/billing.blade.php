@extends('instructor::components.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold">إدارة الحسابات والمدفوعات</h3>
            <p class="text-muted">متابعة تحصيل الرسوم من الطلاب بشكل مبسط</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3">اسم الطالب</th>
                            <th class="border-0">إجمالي المطلوب</th>
                            <th class="border-0">إجمالي المدفوع</th>
                            <th class="border-0">المتبقي</th>
                            <th class="border-0 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        @php
                            $totalDue = $student->sales->sum('total_amount');
                            $totalPaid = $student->sales->sum('paid_amount');
                            $balance = $totalDue - $totalPaid;
                        @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-bold">{{ $student->name }}</div>
                                <small class="text-muted">{{ $student->phone }}</small>
                            </td>
                            <td>{{ number_format($totalDue, 2) }} ج.م</td>
                            <td>{{ number_format($totalPaid, 2) }} ج.م</td>
                            <td>
                                <span class="badge {{ $balance > 0 ? 'bg-danger-soft text-danger' : 'bg-success-soft text-success' }} rounded-pill px-3">
                                    {{ number_format($balance, 2) }} ج.م
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#payModal{{ $student->id }}">
                                    <i class="fas fa-hand-holding-usd me-1"></i> تحصيل مبلغ
                                </button>
                            </td>
                        </tr>

                        <!-- Quick Pay Modal -->
                        <div class="modal fade" id="payModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4">
                                    <form action="{{ route('instructor.mark-paid') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="fw-bold">تسجيل استلام مبلغ</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-4">
                                            <div class="text-center mb-4">
                                                <h6 class="text-muted">تحصيل من الطالب</h6>
                                                <h5 class="fw-bold">{{ $student->name }}</h5>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">المبلغ المستلم (ج.م)</label>
                                                <input type="number" name="amount" class="form-control rounded-3" step="0.01" required value="{{ $balance > 0 ? $balance : '' }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">ملاحظات</label>
                                                <textarea name="notes" class="form-control rounded-3" rows="2" placeholder="اختياري..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0 pb-4">
                                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">تأكيد عملية التحصيل</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
</style>
@endsection
