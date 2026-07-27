@extends('layouts.app-next')

@section('title', 'Finances & Fees')

@section('sidebar')
    <x-ui.sidebar brandName="Student Campus">
        <div class="space-y-1">
            <a href="{{ route('campus.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-home w-4 text-center"></i>
                <span>My Campus</span>
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Learning</div>

            <a href="{{ route('campus.courses.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-book-open w-4 text-center"></i>
                <span>My Courses</span>
            </a>

            <a href="{{ route('campus.schedule') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-calendar-alt w-4 text-center"></i>
                <span>Class Schedule</span>
            </a>

            <a href="{{ route('campus.attendance') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-user-check w-4 text-center"></i>
                <span>Attendance Record</span>
            </a>

            <a href="{{ route('campus.finances') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-brand-primary bg-brand-50 dark:bg-brand-900/30">
                <i class="fas fa-wallet w-4 text-center"></i>
                <span>Finances & Fees</span>
            </a>

            <a href="{{ route('campus.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-user-circle w-4 text-center"></i>
                <span>My Profile</span>
            </a>
        </div>
    </x-ui.sidebar>
@endsection

@section('content')
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-2">ديوني ومدفوعاتي</h2>
            <p class="text-muted mb-0">تابع حالتك المالية وسجل مدفوعاتك للمركز</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="p-3 bg-white rounded-ultra shadow-sm d-inline-block">
                <div class="text-muted small mb-1">إجمالي المبالغ المستحقة</div>
                <div class="fw-bold fs-3 text-danger">{{ number_format($totalDebt, 2) }} <small class="fs-6">ج.م</small></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-ultra overflow-hidden">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-0">سجل المعاملات المالية</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="p-4 border-0">رقم العملية</th>
                                <th class="p-4 border-0">التاريخ</th>
                                <th class="p-4 border-0">إجمالي المبلغ</th>
                                <th class="p-4 border-0">المبلغ المدفوع</th>
                                <th class="p-4 border-0">المتبقي</th>
                                <th class="p-4 border-0">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td class="p-4 border-light fw-bold text-primary">#{{ $sale->id }}</td>
                                    <td class="p-4 border-light text-muted">{{ $sale->created_at->format('Y-m-d') }}</td>
                                    <td class="p-4 border-light fw-bold">{{ number_format($sale->total_amount, 2) }} ج.م</td>
                                    <td class="p-4 border-light text-success fw-bold">{{ number_format($sale->paid_amount, 2) }} ج.م</td>
                                    <td class="p-4 border-light text-danger fw-bold">{{ number_format($sale->total_amount - $sale->paid_amount, 2) }} ج.م</td>
                                    <td class="p-4 border-light">
                                        @if($sale->status == 'paid')
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">مكتمل</span>
                                        @elseif($sale->status == 'partial')
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">جزئي</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">غير مدفوع</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="mb-3 fs-1 opacity-25">🧾</div>
                                        <p class="text-muted">لا يوجد سجل معاملات مالية حالياً.</p>
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
