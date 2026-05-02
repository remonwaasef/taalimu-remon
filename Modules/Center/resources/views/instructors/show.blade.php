@extends('center::layouts.hope-master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::instructors.show') }}</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('center.instructors.edit', $instructor->id) }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-edit me-2"></i> {{ __('center::instructors.edit') }}
            </a>
            <a href="{{ route('center.instructors.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                {{ __('center::messages.back') }}
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar: Basic Info & Profile Pic -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-body text-center p-5">
                    <div class="position-relative d-inline-block mb-4">
                        @if($instructor->image)
                            <img src="{{ Storage::url($instructor->image) }}" class="rounded-circle border border-4 border-white shadow" style="width: 150px; height: 150px; object-fit: cover;" alt="{{ $instructor->name }}">
                        @else
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow" style="width: 150px; height: 150px; font-size: 3rem;">
                                {{ mb_substr($instructor->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="position-absolute bottom-0 end-0 p-2 bg-white rounded-circle shadow-sm">
                            @php
                                $statusColors = [
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'on_hold' => 'danger'
                                ];
                                $color = $statusColors[$instructor->status] ?? 'info';
                            @endphp
                            <i class="fas fa-circle text-{{ $color }}"></i>
                        </span>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $instructor->name }}</h4>
                    <p class="text-muted mb-3">{{ $instructor->specialization ?? '-' }}</p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3 py-2 fw-bold">
                            {{ __('center::instructors.' . $instructor->status) }}
                        </span>
                    </div>

                    <hr class="opacity-10">

                    <div class="text-start mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3 text-primary"><i class="fas fa-envelope fa-fw"></i></div>
                            <div>
                                <small class="text-muted d-block">{{ __('center::instructors.email') }}</small>
                                <span class="fw-bold">{{ $instructor->email ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3 text-success"><i class="fas fa-phone fa-fw"></i></div>
                            <div>
                                <small class="text-muted d-block">{{ __('center::instructors.phone') }}</small>
                                <span class="fw-bold">{{ $instructor->phone ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <h6 class="fw-bold mb-4">{{ __('center::instructors.contact_info') }}</h6>
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-3">
                                <h4 class="fw-bold mb-0 text-primary">{{ $instructor->courses_count }}</h4>
                                <small class="text-muted d-block mt-1">{{ __('center::instructors.registered_phone') }}</small>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3 border border-primary border-opacity-10 position-relative overflow-hidden">
                                <h4 class="fw-bold mb-0 text-primary">{{ format_price($instructor->outstanding_balance) }}</h4>
                                <small class="text-muted d-block mt-1">{{ __('center::instructors.available_balance') }}</small>
                                @if($instructor->outstanding_balance > 0)
                                    <button type="button" class="btn btn-primary btn-sm rounded-pill mt-2 w-100" data-bs-toggle="modal" data-bs-target="#payoutModal">
                                        <i class="fas fa-hand-holding-usd me-1"></i> {{ __('center::instructors.payout') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0">{{ __('center::instructors.biography') }}</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">{{ __('center::instructors.gender') }}</label>
                            <span class="fw-bold">{{ $instructor->gender ? __('center::instructors.' . $instructor->gender) : '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">{{ __('center::instructors.hiring_date') }}</label>
                            <span class="fw-bold text-primary">{{ $instructor->hiring_date ? $instructor->hiring_date->format('Y/m/d') : '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">{{ __('center::instructors.national_id') }}</label>
                            <span class="fw-bold">{{ $instructor->national_id ?? '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">{{ __('center::instructors.commission_rate') }}</label>
                            <span class="fw-bold text-success">{{ __('center::instructors.commission_from_sales', ['rate' => $instructor->commission_rate]) }}</span>
                        </div>
                        <div class="col-12">
                            <hr class="opacity-10 my-2">
                            <label class="text-muted small d-block mb-2">{{ __('center::instructors.bio') }}</label>
                            <p class="text-dark bg-light p-3 rounded-3 mb-0" style="white-space: pre-line;">{{ $instructor->bio ?? __('center::instructors.no_bio') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Records / History -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">{{ __('center::instructors.course_statistics') }}</h5>
                    <div class="d-flex align-items-center gap-2">
                        <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 me-2">{{ format_price($instructor->outstanding_balance) }}</div>
                        <a href="{{ route('center.instructors.statement', $instructor->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="fas fa-file-invoice-dollar me-1"></i> {{ __('center::instructors.account_statement') }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 p-3 h6 small fw-bold">{{ __('center::instructors.table_date') }}</th>
                                    <th class="border-0 p-3 h6 small fw-bold">{{ __('center::instructors.table_student') }}</th>
                                    <th class="border-0 p-3 h6 small fw-bold text-center">{{ __('center::instructors.table_rate') }}</th>
                                    <th class="border-0 p-3 h6 small fw-bold">{{ __('center::instructors.table_amount') }}</th>
                                    <th class="border-0 p-3 h6 small fw-bold">{{ __('center::instructors.table_status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commissions as $commission)
                                    <tr>
                                        <td class="p-3 small text-muted">{{ $commission->created_at->format('Y-m-d') }}</td>
                                        <td class="p-3">
                                            <div class="fw-bold">{{ $commission->sale->student->name }}</div>
                                            <div class="small text-muted">فاتورة #{{ $commission->sale_id }}</div>
                                        </td>
                                        <td class="p-3 text-center small fw-bold text-primary">{{ $commission->rate }}%</td>
                                        <td class="p-3 fw-bold text-success">{{ format_price($commission->amount) }}</td>
                                        <td class="p-3">
                                            @php
                                                $cStatusColors = [
                                                    'earned' => 'success',
                                                    'pending' => 'warning',
                                                    'paid' => 'info'
                                                ];
                                                $cStat = $cStatusColors[$commission->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge rounded-pill bg-{{ $cStat }} bg-opacity-10 text-{{ $cStat }} px-3">
                                                {{ __('center::instructors.' . $commission->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-0">{{ __('center::instructors.no_financial_records') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($commissions->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $commissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>

    <!-- Payout Modal -->
    <div class="modal fade" id="payoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <form action="{{ route('center.instructors.payout', $instructor->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 p-4 pb-0">
                        <h5 class="fw-bold mb-0">{{ __('center::instructors.register_payout') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-4 text-center p-3 bg-light rounded-3">
                            <small class="text-muted d-block mb-1">{{ __('center::instructors.available_for_payout') }}</small>
                            <h4 class="fw-bold mb-0 text-success">{{ format_price($instructor->outstanding_balance) }}</h4>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">{{ __('center::instructors.amount_to_payout') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3">{{ get_currency_symbol() }}</span>
                                <input type="number" name="amount" step="0.01" class="form-control border-start-0 rounded-end-3" 
                                    max="{{ $instructor->outstanding_balance }}" min="1" value="{{ $instructor->outstanding_balance }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">{{ __('center::instructors.payment_method') }}</label>
                                <select name="payment_method" class="form-select rounded-3" required>
                                    <option value="cash">{{ __('center::instructors.cash') }}</option>
                                    <option value="bank_transfer">{{ __('center::instructors.bank_transfer') }}</option>
                                    <option value="online">{{ __('center::instructors.online') }}</option>
                                    <option value="other">{{ __('center::instructors.other') }}</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">{{ __('center::instructors.table_date') }}</label>
                                <input type="date" name="payout_date" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-bold">{{ __('center::instructors.notes') }}</label>
                            <textarea name="notes" class="form-control rounded-3" rows="2" placeholder="{{ __('center::instructors.optional') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">{{ __('center::instructors.cancel') }}</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('center::instructors.confirm_payout') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
