@extends('instructor::components.layouts.hope-master')

@section('title', __('instructor::reports.payment_reports'))
@section('page-title', __('instructor::reports.payment_reports'))
@section('page-subtitle', __('instructor::reports.payment_reports_subtitle'))

@section('content')

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body d-flex align-items-center gap-3 py-3 px-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                    <i class="fas fa-file-invoice-dollar text-primary fs-4"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold text-uppercase">{{ __('instructor::reports.total_due_label') }}</small>
                    <h4 class="fw-bold mb-0">{{ number_format($totalDue) }} <small class="text-muted fs-6">{{ app('tenant')->settings['currency'] ?? 'EGP' }}</small></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body d-flex align-items-center gap-3 py-3 px-4">
                <div class="bg-success bg-opacity-10 p-3 rounded-3">
                    <i class="fas fa-check-circle text-success fs-4"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold text-uppercase">{{ __('instructor::reports.total_collected') }}</small>
                    <h4 class="fw-bold mb-0 text-success">{{ number_format($totalPaid) }} <small class="fs-6">{{ app('tenant')->settings['currency'] ?? 'EGP' }}</small></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body d-flex align-items-center gap-3 py-3 px-4">
                <div class="bg-danger bg-opacity-10 p-3 rounded-3">
                    <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold text-uppercase">{{ __('instructor::reports.total_remaining') }}</small>
                    <h4 class="fw-bold mb-0 text-danger">{{ number_format($totalBalance) }} <small class="fs-6">{{ app('tenant')->settings['currency'] ?? 'EGP' }}</small></h4>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Students Financial Table --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">#</th>
                        <th class="border-0">{{ __('instructor::reports.student') }}</th>
                        <th class="border-0">{{ __('instructor::reports.enrolled_courses') }}</th>
                        <th class="border-0">{{ __('instructor::reports.total_due_label') }}</th>
                        <th class="border-0">{{ __('instructor::reports.total_collected') }}</th>
                        <th class="border-0">{{ __('instructor::reports.remaining') }}</th>
                        <th class="px-4 border-0">{{ __('instructor::reports.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                        <tr>
                            <td class="px-4 py-3 text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $student->name }}</div>
                                <small class="text-muted">{{ $student->phone }}</small>
                            </td>
                            <td>
                                @foreach($student->enrollments as $enrollment)
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 mb-1">{{ $enrollment->course->title ?? '-' }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="fw-bold">{{ number_format($student->total_due) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-success">{{ number_format($student->total_paid) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}</span>
                            </td>
                            <td>
                                @if($student->balance > 0)
                                    <span class="fw-bold text-danger">{{ number_format($student->balance) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}</span>
                                @else
                                    <span class="fw-bold text-success">0</span>
                                @endif
                            </td>
                            <td class="px-4">
                                @if($student->financial_status === 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('instructor::reports.paid') }}</span>
                                @elseif($student->financial_status === 'partial')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">{{ __('instructor::reports.partial') }}</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">{{ __('instructor::reports.unpaid') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="mb-3"><i class="fas fa-receipt fs-1 text-muted opacity-25"></i></div>
                                <h6 class="text-muted">{{ __('instructor::reports.no_students') }}</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($students->count() > 0)
                <tfoot class="bg-light">
                    <tr>
                        <td colspan="3" class="px-4 py-3 fw-bold">{{ __('instructor::reports.total') }}</td>
                        <td class="fw-bold">{{ number_format($totalDue) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}</td>
                        <td class="fw-bold text-success">{{ number_format($totalPaid) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}</td>
                        <td class="fw-bold text-danger">{{ number_format($totalBalance) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
