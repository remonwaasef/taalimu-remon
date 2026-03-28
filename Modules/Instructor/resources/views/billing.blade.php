@extends('instructor::components.layouts.hope-master')

@section('page-title', __('instructor::billing.page_title'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold mb-1">{{ __('instructor::billing.title') }}</h3>
            <p class="text-muted mb-0">{{ __('instructor::billing.subtitle') }}</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div id="resultCount" class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                {{ $students->count() }} {{ __('instructor::billing.student_count') }}
            </div>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 rounded-start-pill px-3"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="searchInput" class="form-control bg-light border-0 rounded-end-pill py-2" placeholder="{{ __('instructor::billing.search_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select id="filterStatus" class="form-select bg-light border-0 rounded-pill py-2">
                        <option value="all">{{ __('instructor::billing.all_students') }}</option>
                        <option value="unpaid">{{ __('instructor::billing.has_balance') }}</option>
                        <option value="paid">{{ __('instructor::billing.fully_paid') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    @if($students->isEmpty())
        <div class="stats-card p-5 text-center">
            <div class="mb-4">
                <i class="fas fa-users fs-1 text-muted opacity-25"></i>
            </div>
            <h5 class="text-muted">{{ __('instructor::billing.no_students_registered') }}</h5>
        </div>
    @else
        <div id="noResults" class="stats-card p-5 text-center d-none">
            <div class="mb-4">
                <i class="fas fa-search fs-1 text-muted opacity-25"></i>
            </div>
            <h5 class="text-muted">{{ __('instructor::billing.no_search_results') }}</h5>
        </div>

        <div class="stats-card p-0 overflow-hidden shadow-sm border-0" id="billingTableContainer">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="billingTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0">{{ __('instructor::billing.student_name') }}</th>
                            <th class="border-0">{{ __('instructor::billing.total_due') }}</th>
                            <th class="border-0">{{ __('instructor::billing.total_paid') }}</th>
                            <th class="border-0">{{ __('instructor::billing.balance') }}</th>
                            <th class="px-4 border-0 text-end">{{ __('instructor::billing.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            @php
                                $totalDue = $student->enrollments->sum(function($e) { return $e->course->price ?? 0; });
                                $totalPaid = $student->sales->sum('paid_amount');
                                $balance = $totalDue - $totalPaid;
                                $status = $balance > 0 ? 'unpaid' : 'paid';
                            @endphp
                            <tr class="student-row" 
                                data-name="{{ $student->name }}" 
                                data-phone="{{ $student->phone }}" 
                                data-balance="{{ $balance }}"
                                data-status="{{ $status }}">
                                <td class="px-4 py-3">
                                    <div class="fw-bold text-dark">{{ $student->name }}</div>
                                    <small class="text-muted">{{ $student->enrollments->pluck('course.title')->filter()->implode(', ') }}</small>
                                </td>
                                <td>{{ number_format($totalDue) }} {{ __('instructor::dashboard.currency') }}</td>
                                <td>{{ number_format($totalPaid) }} {{ __('instructor::dashboard.currency') }}</td>
                                <td>
                                    @if($balance > 0)
                                        <span class="text-danger fw-bold">{{ number_format($balance) }} {{ __('instructor::dashboard.currency') }}</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('instructor::billing.paid') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        @if($balance > 0)
                                            <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" 
                                                data-bs-toggle="modal" data-bs-target="#collectModal" 
                                                data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-balance="{{ $balance }}">
                                                <i class="fas fa-hand-holding-usd me-1"></i> {{ __('instructor::billing.collect') }}
                                            </button>
                                            @php
                                                $reminderMsg = __('instructor::billing.reminder_msg', [
                                                    'student' => $student->name,
                                                    'balance' => $balance,
                                                    'instructor' => auth()->user()->name
                                                ]);
                                                $phone = $student->phone;
                                                if (str_starts_with($phone, '0')) $phone = '2' . $phone;
                                                $whatsappUri = "https://api.whatsapp.com/send?phone=" . preg_replace('/[^0-9]/', '', $phone) . "&text=" . urlencode($reminderMsg);
                                            @endphp
                                            <a href="{{ $whatsappUri }}" target="_blank" class="btn btn-success btn-sm rounded-pill px-3">
                                                <i class="fab fa-whatsapp me-1"></i> {{ __('instructor::billing.whatsapp_reminder') }}
                                            </a>
                                        @else
                                            <span class="text-success small fw-medium"><i class="fas fa-check-circle me-1"></i> {{ __('instructor::billing.collected') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Collection Modal -->
<div class="modal fade" id="collectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">{{ __('instructor::billing.record_payment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('instructor.mark-paid') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="student_id" id="modal_student_id">
                    
                    <div class="mb-4 text-center">
                        <p class="text-muted mb-1">{{ __('instructor::billing.collect_from') }}</p>
                        <h4 class="fw-bold mb-0" id="modal_student_name"></h4>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('instructor::billing.amount_received') }}</label>
                        <div class="input-group">
                            <input type="number" name="amount" id="modal_amount" class="form-control bg-light border-0 py-2" required>
                            <span class="input-group-text bg-light border-0">{{ __('instructor::dashboard.currency') }}</span>
                        </div>
                        <div class="form-text text-danger" id="modal_balance_hint"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('instructor::billing.notes') }}</label>
                        <textarea name="notes" class="form-control bg-light border-0" rows="3" placeholder="{{ __('instructor::billing.notes_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('instructor::billing.cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('instructor::billing.confirm_collection') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .stats-card { background: #fff; border-radius: 1.25rem; }
    #billingTable thead th { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.025em; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const rows = document.querySelectorAll('.student-row');
    const noResults = document.getElementById('noResults');
    const resultCount = document.getElementById('resultCount');
    const tableContainer = document.getElementById('billingTableContainer');

    function applyFilters() {
        const query = searchInput.value.trim().toLowerCase();
        const filter = filterStatus.value;
        let visible = 0;

        rows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const phone = row.dataset.phone.toLowerCase();
            const status = row.dataset.status;

            const matchSearch = !query || name.includes(query) || phone.includes(query);
            const matchFilter = filter === 'all' || status === filter;

            if (matchSearch && matchFilter) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        if (resultCount) resultCount.textContent = visible + ' {{ __('instructor::billing.student_count') }}';
        if (noResults) noResults.classList.toggle('d-none', visible > 0);
        if (tableContainer) tableContainer.classList.toggle('d-none', visible === 0);
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (filterStatus) filterStatus.addEventListener('change', applyFilters);

    // Modal data handling
    const collectModal = document.getElementById('collectModal');
    if (collectModal) {
        collectModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const balance = button.getAttribute('data-balance');

            document.getElementById('modal_student_id').value = id;
            document.getElementById('modal_student_name').textContent = name;
            document.getElementById('modal_amount').value = balance;
            document.getElementById('modal_amount').max = balance;
            document.getElementById('modal_balance_hint').textContent = '{{ __('instructor::billing.current_balance') }}' + new Intl.NumberFormat().format(balance);
        });
    }
});
</script>
@endsection
