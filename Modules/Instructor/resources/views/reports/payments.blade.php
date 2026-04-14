@extends('instructor::components.layouts.hope-master')

@section('title', __('instructor::reports.payment_reports'))
@section('page-title', __('instructor::reports.payment_reports'))
@section('page-subtitle', __('instructor::reports.payment_reports_subtitle'))

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">#</th>
                        <th class="border-0">{{ __('instructor::reports.date') }}</th>
                        <th class="border-0">{{ __('instructor::reports.student') }}</th>
                        <th class="border-0">{{ __('instructor::reports.amount') }}</th>
                        <th class="border-0">{{ __('instructor::reports.method') }}</th>
                        <th class="border-0">{{ __('instructor::reports.status') }}</th>
                        <th class="px-4 border-0">{{ __('instructor::reports.notes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $index => $payment)
                        <tr>
                            <td class="px-4 py-3 text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold">{{ $payment->created_at->format('Y-m-d') }}</div>
                                <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $payment->student->name ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="fw-bold text-success">{{ number_format($payment->paid_amount) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}</span>
                            </td>
                            <td>
                                @php
                                    $methodKey = 'instructor::reports.method_' . ($payment->payment_method ?? 'cash');
                                    $methodLabel = __($methodKey);
                                    if ($methodLabel === $methodKey) $methodLabel = $payment->payment_method ?? 'N/A';
                                @endphp
                                <span class="badge bg-light text-dark rounded-pill px-3">{{ $methodLabel }}</span>
                            </td>
                            <td>
                                @if($payment->status === 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('instructor::reports.paid') }}</span>
                                @elseif($payment->status === 'partial')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">{{ __('instructor::reports.partial') }}</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">{{ __('instructor::reports.pending') }}</span>
                                @endif
                            </td>
                            <td class="px-4">
                                <small class="text-muted">{{ $payment->notes ?? '-' }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="mb-3"><i class="fas fa-receipt fs-1 text-muted opacity-25"></i></div>
                                <h6 class="text-muted">{{ __('instructor::reports.no_payments') }}</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($payments->count() > 0)
                <tfoot class="bg-light">
                    <tr>
                        <td colspan="3" class="px-4 py-3 fw-bold">{{ __('instructor::reports.total') }}</td>
                        <td class="fw-bold text-success">{{ number_format($payments->sum('paid_amount')) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
