@extends('instructor::components.layouts.hope-master')

@section('title', __('instructor::reports.payment_reports'))
@section('page-title', __('instructor::reports.payment_reports'))
@section('page-subtitle', __('instructor::reports.payment_reports_subtitle'))

@section('content')
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">{{ __('instructor::reports.date') }}</th>
                        <th class="border-0">{{ __('instructor::reports.student') }}</th>
                        <th class="border-0">{{ __('instructor::reports.collected_for') }}</th>
                        <th class="border-0">{{ __('instructor::reports.amount') }}</th>
                        <th class="border-0">{{ __('instructor::reports.method') }}</th>
                        <th class="px-4 border-0">{{ __('instructor::reports.notes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-bold">{{ $payment->created_at->format('Y-m-d') }}</div>
                                <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $payment->student->name }}</div>
                            </td>
                            <td>
                                @foreach($payment->items as $item)
                                    <small class="d-block text-muted">{{ $item->item->title ?? 'N/A' }}</small>
                                @endforeach
                            </td>
                            <td>
                                <span class="fw-bold text-success">{{ number_format($payment->paid_amount) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark rounded-pill px-3">{{ __('instructor::reports.method_' . $payment->payment_method) ?? $payment->payment_method }}</span>
                            </td>
                            <td class="px-4">
                                <small class="text-muted">{{ $payment->notes ?? '-' }}</small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
