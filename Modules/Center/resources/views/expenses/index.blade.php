@extends('center::layouts.app-next')

@section('page-title', __('center::expenses.title'))

@section('page-actions')
    <a href="{{ route('center.expenses.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus me-2"></i> {{ __('center::expenses.new_expense') }}
    </a>
@endsection

@section('panel-content')
<div class="container-fluid">

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <form action="{{ route('center.expenses.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-bold">{{ __('center::expenses.category') }}</label>
                <select name="category" class="form-select rounded-pill">
                    <option value="">{{ __('center::expenses.all_categories') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">{{ __('center::expenses.start_date') }}</label>
                <input type="date" name="start_date" class="form-control rounded-pill" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">{{ __('center::expenses.end_date') }}</label>
                <input type="date" name="end_date" class="form-control rounded-pill" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-light rounded-pill px-4 border w-100">
                    <i class="fas fa-filter me-2"></i> {{ __('center::expenses.filter') }}
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive" data-mobile-cards>
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 ps-4 py-3">{{ __('center::expenses.category') }}</th>
                        <th class="border-0">{{ __('center::expenses.amount') }}</th>
                        <th class="border-0">{{ __('center::expenses.date') }}</th>
                        <th class="border-0">{{ __('center::expenses.payment_method') }}</th>
                        <th class="border-0">{{ __('center::expenses.created_by') }}</th>
                        <th class="border-0 rounded-end px-4">{{ __('center::expenses.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr class="hover-bg">
                        <td class="ps-4">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 fw-bold">
                                {{ $expense->category }}
                            </span>
                        </td>
                        <td class="fw-bold text-danger">-{{ format_price($expense->amount) }}</td>
                        <td class="text-muted small">{{ $expense->date->format('Y-m-d') }}</td>
                        <td>
                            <span class="small text-dark fw-medium">
                                <i class="fas {{ $expense->payment_method == 'cash' ? 'fa-money-bill-wave text-success' : 'fa-credit-card text-info' }} me-1"></i>
                                {{ __('center::expenses.' . $expense->payment_method) }}
                            </span>
                        </td>
                        <td>
                            <div class="small">{{ $expense->creator?->name ?? 'System' }}</div>
                        </td>
                        <td class="px-4">
                            <div class="btn-group">
                                <a href="{{ route('center.expenses.edit', $expense->id) }}" class="btn btn-sm btn-light border-0 rounded-pill me-1">
                                    <i class="fas fa-edit text-muted"></i>
                                </a>
                                @if($expense->attachment)
                                <a href="{{ asset('storage/' . $expense->attachment) }}" target="_blank" class="btn btn-sm btn-light border-0 rounded-pill me-1">
                                    <i class="fas fa-paperclip text-muted"></i>
                                </a>
                                @endif
                                <form id="delete-expense-form-{{ $expense->id }}" action="{{ route('center.expenses.destroy', $expense->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-sm btn-light border-0 rounded-pill"
                                        data-confirm-delete
                                        data-form="delete-expense-form-{{ $expense->id }}"
                                        data-title="{{ __('center::expenses.delete_confirm') }}"
                                        data-text="{{ $expense->title }}">
                                    <i class="fas fa-trash text-danger opacity-75"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                            <p>{{ __('center::expenses.no_expenses') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($expenses->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $expenses->links() }}
    </div>
    @endif
</div>

<style>
    .hover-bg:hover {
        background-color: rgba(67, 97, 238, 0.02);
    }
    .form-select, .form-control {
        border-color: #eee;
    }
    .form-select:focus, .form-control:focus {
        border-color: #4361ee;
        box-shadow: none;
    }
</style>
@endsection
