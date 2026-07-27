@extends('center::layouts.app-next')
@section('page-title', __('center::analytics.teacher_commissions'))
@section('page-subtitle', __('center::analytics.commissions_detail'))

@section('page-actions')
    <a href="{{ route('center.analytics.finance') }}" class="btn btn-glass shadow-sm">
        <i class="fas fa-arrow-right me-2"></i>{{ __('center::analytics.back_to_finance') }}
    </a>
@endsection

@section('panel-content')
<div class="container-fluid">

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-money-bill-wave fa-lg"></i>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">{{ __('center::analytics.teacher_commissions') }}</span>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.total_commissions') }}</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalCommissions) }}</h3>
                </div>
                <div class="bg-info" style="height: 4px; width: 100%; opacity: 0.5;"></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-4 px-4">
            <h5 class="fw-bold mb-0 text-dark">{{ __('center::analytics.commission_log') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">{{ __('center::analytics.transaction_number') }}</th>
                            <th>{{ __('center::analytics.instructor') }}</th>
                            <th>{{ __('center::analytics.invoice_student') }}</th>
                            <th>{{ __('center::analytics.commission_value') }}</th>
                            <th>{{ __('center::analytics.due_date') }}</th>
                            <th class="px-4">{{ __('center::analytics.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $commission)
                            <tr>
                                <td class="px-4">#{{ $commission->id }}</td>
                                <td>
                                    @if($commission->instructor)
                                        <a href="{{ route('center.instructors.show', $commission->instructor_id) }}" class="fw-bold text-primary text-decoration-none">
                                            {{ $commission->instructor->name }}
                                        </a>
                                    @else
                                        <span class="text-muted small">{{ __('center::analytics.undefined') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($commission->sale)
                                        <a href="{{ route('center.sales.show', $commission->sale_id) }}" class="text-decoration-none text-dark fw-medium">#{{ $commission->sale_id }}</a>
                                        <small class="d-block text-muted">{{ $commission->sale->student->name ?? '' }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="fw-bold text-info">{{ format_price($commission->amount) }}</td>
                                <td class="small text-muted">{{ $commission->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-4">
                                    @if($commission->status == 'paid')
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i>{{ __('center::analytics.paid_f') }}</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i>{{ __('center::analytics.due_f') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fas fa-info-circle fa-3x text-light"></i>
                                    </div>
                                    <p class="mb-0">{{ __('center::analytics.no_commissions_recorded') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($commissions->hasPages())
            <div class="p-4 border-top">
                {{ $commissions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
