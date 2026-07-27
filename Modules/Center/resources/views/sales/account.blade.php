@extends('center::layouts.app-next')

@section('page-title', __('center::sales.title'))
@section('page-subtitle', __('center::sales.subtitle'))

@section('page-actions')
    <div id="resultCount" class="btn btn-glass cursor-default opacity-100">
        <i class="fas fa-user-graduate me-2"></i> {{ $students->count() }} {{ __('center::sales.student_count') }}
    </div>
@endsection

@section('panel-content')
<div class="container-fluid">

    {{-- Search & Filter Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white rounded-start-pill px-3"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="searchInput"
                            data-smart-search="#billingTable"
                            data-search-fields="name,phone"
                            data-search-counter="#resultCount"
                            data-search-empty="#noResults"
                            data-search-container="#billingTableContainer"
                            data-search-highlight="true"
                            data-search-counter-suffix="{{ __('center::sales.student_count') }}"
                            class="form-control bg-white rounded-end-pill py-2"
                            placeholder="{{ __('center::sales.search_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select data-smart-filter="#billingTable" data-filter-key="status" class="form-select bg-white rounded-pill py-2">
                        <option value="all">{{ __('center::sales.all_students') }}</option>
                        <option value="unpaid">{{ __('center::sales.has_balance') }}</option>
                        <option value="paid">{{ __('center::sales.fully_paid') }}</option>
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
            <h5 class="text-muted">{{ __('center::sales.no_students_registered') }}</h5>
        </div>
    @else
        <div id="noResults" class="stats-card p-5 text-center d-none">
            <div class="mb-4">
                <i class="fas fa-search fs-1 text-muted opacity-25"></i>
            </div>
            <h5 class="text-muted">{{ __('center::sales.no_search_results') }}</h5>
        </div>

        <div class="stats-card p-0 overflow-hidden shadow-sm border-0" id="billingTableContainer">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="billingTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0">{{ __('center::sales.student_name') }}</th>
                            <th class="border-0">{{ __('center::sales.total_due') }}</th>
                            <th class="border-0">{{ __('center::sales.total_paid') }}</th>
                            <th class="border-0">{{ __('center::sales.balance') }}</th>
                            <th class="px-4 border-0 text-end">{{ __('center::sales.actions') }}</th>
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
                                <td>{{ number_format($totalDue) }} {{ get_currency_symbol() }}</td>
                                <td>{{ number_format($totalPaid) }} {{ get_currency_symbol() }}</td>
                                <td>
                                    @if($balance > 0)
                                        <span class="text-danger fw-bold">{{ number_format($balance) }} {{ get_currency_symbol() }}</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('center::sales.paid') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        @if($balance > 0)
                                            <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" 
                                                data-bs-toggle="modal" data-bs-target="#collectModal" 
                                                data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-balance="{{ $balance }}">
                                                <i class="fas fa-hand-holding-usd me-1"></i> {{ __('center::sales.collect') }}
                                            </button>
                                            @php
                                                // Customize message for Center context
                                                $reminderMsg = __('center::sales.billing_reminder', [
                                                    'name' => $student->name,
                                                    'balance' => $balance,
                                                    'currency' => get_currency_symbol(),
                                                    'center' => app('tenant')->name ?? 'المركز'
                                                ]);
                                                $phone = $student->phone;
                                                if (str_starts_with($phone, '0')) $phone = '2' . $phone;
                                                $whatsappUri = "https://api.whatsapp.com/send?phone=" . preg_replace('/[^0-9]/', '', $phone) . "&text=" . urlencode($reminderMsg);
                                            @endphp
                                            <a href="{{ $whatsappUri }}" target="_blank" class="btn btn-success btn-sm rounded-pill px-3">
                                                <i class="fab fa-whatsapp me-1"></i> {{ __('center::sales.whatsapp_reminder') }}
                                            </a>
                                        @else
                                            <span class="text-success small fw-medium"><i class="fas fa-check-circle me-1"></i> {{ __('center::sales.collected') }}</span>
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
    
    <div class="mt-4 d-flex justify-content-center">
        {{ $students->links() }}
    </div>
</div>

@push('modals')
<!-- Collection Modal -->
<div class="modal fade" id="collectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">{{ __('center::sales.record_payment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('center.sales.mark-paid') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="student_id" id="modal_student_id">
                    
                    <div class="mb-4 text-center">
                        <p class="text-muted mb-1">{{ __('center::sales.collect_from') }}</p>
                        <h4 class="fw-bold mb-0" id="modal_student_name"></h4>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('center::sales.amount_received') }}</label>
                        <div class="input-group">
                            <input type="number" name="amount" id="modal_amount" class="form-control bg-white border py-2" required>
                            <span class="input-group-text bg-white border">{{ get_currency_symbol() }}</span>
                        </div>
                        <div class="form-text text-danger" id="modal_balance_hint"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('center::sales.notes') }}</label>
                        <textarea name="notes" class="form-control bg-white border" rows="3" placeholder="{{ __('center::sales.notes_placeholder_alt') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('center::sales.cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('center::sales.confirm_collection') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

<style>
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .stats-card { background: #fff; border-radius: 1.25rem; }
    #billingTable thead th { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.025em; }
    #resultCount { transition: transform 0.2s ease; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal data handling (page-specific, cannot be generalized)
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
            document.getElementById('modal_balance_hint').textContent = '{{ __("center::sales.current_balance_hint") }}' + new Intl.NumberFormat().format(balance);
        });
    }
});
</script>
@endsection
