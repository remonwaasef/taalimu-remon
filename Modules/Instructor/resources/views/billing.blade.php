@extends('instructor::components.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold">إدارة الحسابات والمدفوعات</h3>
            <p class="text-muted">متابعة تحصيل الرسوم من الطلاب بشكل مبسط</p>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="searchInput" class="form-control border-start-0 rounded-end-pill" placeholder="بحث بالاسم أو رقم الهاتف...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select id="filterStatus" class="form-select rounded-pill">
                        <option value="all">كل الطلاب</option>
                        <option value="unpaid">عليهم متبقي</option>
                        <option value="paid">مسددين بالكامل</option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <span id="resultCount" class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 billing-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="billingTable">
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
                            $totalDue = $student->enrollments->sum(function($enrollment) {
                                return $enrollment->course->price ?? 0;
                            });
                            $totalPaid = $student->sales->sum('paid_amount');
                            $balance = $totalDue - $totalPaid;
                        @endphp
                        <tr class="student-row" data-name="{{ $student->name }}" data-phone="{{ $student->phone }}" data-balance="{{ $balance }}">
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
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-sm rounded-start-pill px-3" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#payModal{{ $student->id }}"
                                        {{ $balance <= 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-hand-holding-usd me-1"></i> {{ $balance <= 0 ? 'تم السداد' : 'تحصيل' }}
                                    </button>
                                    @if($balance > 0)
                                        @php
                                            $msg = "تحية طيبة، نود تذكيركم بأن الطالب {$student->name} لديه مديونية متبقية قدرها " . number_format($balance, 2) . " ج.م لمجموعات المدرس " . (auth()->user()->name ?? 'المعلم') . ". يرجى السداد في أقرب وقت. شكراً لكم.";
                                        @endphp
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->phone) }}?text={{ urlencode($msg) }}" 
                                           target="_blank" class="btn btn-success btn-sm rounded-end-pill px-2" title="تذكير واتساب">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- No results message --}}
            <div id="noResults" class="text-center py-5 d-none">
                <i class="fas fa-search display-4 text-light mb-3"></i>
                <p class="text-muted">لا توجد نتائج مطابقة للبحث.</p>
            </div>
        </div>
    </div>
</div>

{{-- Modals OUTSIDE the table --}}
@foreach($students as $student)
@php
    $totalDue = $student->enrollments->sum(function($enrollment) {
        return $enrollment->course->price ?? 0;
    });
    $totalPaid = $student->sales->sum('paid_amount');
    $balance = $totalDue - $totalPaid;
@endphp
<div class="modal fade" id="payModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
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
                        <p class="text-muted small">المتبقي: <span class="text-danger fw-bold">{{ number_format($balance, 2) }} ج.م</span></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">المبلغ المستلم (ج.م)</label>
                        <input type="number" name="amount" class="form-control rounded-3" step="0.01" required value="{{ $balance > 0 ? $balance : '' }}" max="{{ $balance }}">
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

<style>
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .billing-card:hover { transform: none !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const rows = document.querySelectorAll('.student-row');
    const noResults = document.getElementById('noResults');
    const resultCount = document.getElementById('resultCount');
    const table = document.getElementById('billingTable');

    function applyFilters() {
        const query = searchInput.value.trim().toLowerCase();
        const filter = filterStatus.value;
        let visible = 0;

        rows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const phone = row.dataset.phone.toLowerCase();
            const balance = parseFloat(row.dataset.balance);

            let matchSearch = !query || name.includes(query) || phone.includes(query);
            let matchFilter = true;

            if (filter === 'unpaid') matchFilter = balance > 0;
            else if (filter === 'paid') matchFilter = balance <= 0;

            if (matchSearch && matchFilter) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        resultCount.textContent = visible + ' طالب';
        noResults.classList.toggle('d-none', visible > 0);
        table.classList.toggle('d-none', visible === 0);
    }

    searchInput.addEventListener('input', applyFilters);
    filterStatus.addEventListener('change', applyFilters);

    // Initial count
    applyFilters();
});
</script>
@endsection
