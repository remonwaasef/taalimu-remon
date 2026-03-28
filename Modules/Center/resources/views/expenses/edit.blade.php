@extends('center::layouts.hope-master')

@section('title', __('center::expenses.edit_expense'))

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('center.expenses.index') }}">{{ __('center::expenses.title') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('center::expenses.edit_expense') }}</li>
        </ol>
    </nav>
    <h2 class="fw-bold text-dark mb-0">{{ __('center::expenses.edit_expense') }}: {{ $expense->category }}</h2>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('center.expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">{{ __('center::expenses.category') }}</label>
                            <input type="text" name="category" class="form-control rounded-pill @error('category') is-invalid @enderror" value="{{ old('category', $expense->category) }}">
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">{{ __('center::expenses.amount') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-pill">{{ get_currency_symbol() }}</span>
                                <input type="number" step="0.01" name="amount" class="form-control border-start-0 rounded-end-pill @error('amount') is-invalid @enderror" value="{{ old('amount', $expense->amount) }}">
                            </div>
                            @error('amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">{{ __('center::expenses.date') }}</label>
                            <input type="date" name="date" class="form-control rounded-pill @error('date') is-invalid @enderror" value="{{ old('date', $expense->date->format('Y-m-d')) }}">
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">{{ __('center::expenses.payment_method') }}</label>
                            <select name="payment_method" class="form-select rounded-pill @error('payment_method') is-invalid @enderror">
                                <option value="cash" {{ old('payment_method', $expense->payment_method) == 'cash' ? 'selected' : '' }}>نقدي (Cash)</option>
                                <option value="bank" {{ old('payment_method', $expense->payment_method) == 'bank' ? 'selected' : '' }}>تحويل بنكي / فيزا</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small">{{ __('center::expenses.description') }}</label>
                            <textarea name="description" class="form-control rounded-4 @error('description') is-invalid @enderror" rows="3">{{ old('description', $expense->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small">{{ __('center::expenses.attachment') }}</label>
                            @if($expense->attachment)
                                <div class="mb-3 small d-flex align-items-center">
                                    <i class="fas fa-file-image text-primary me-2"></i>
                                    <span>{{ __('center::messages.blade_0415') }}</span>
                                    <a href="{{ asset('storage/' . $expense->attachment) }}" target="_blank" class="ms-1 text-primary">{{ __('center::messages.blade_0416') }}</a>
                                </div>
                            @endif
                            <div class="upload-box p-4 border-dashed rounded-4 text-center bg-light">
                                <input type="file" name="attachment" id="attachment" class="d-none">
                                <label for="attachment" class="cursor-pointer mb-0 w-100">
                                    <i class="fas fa-sync-alt fa-3x text-primary mb-2"></i>
                                    <p class="mb-0 text-muted">{{ __('center::messages.blade_0417') }}</p>
                                    <small class="text-muted">JPG, PNG, PDF (Max 2MB)</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <hr class="my-4 opacity-10">
                            <a href="{{ route('center.expenses.index') }}" class="btn btn-light rounded-pill px-4 me-2 border">{{ __('center::messages.blade_0418') }}</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5">{{ __('center::messages.blade_0419') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .upload-box {
        border: 2px dashed #ddd;
        transition: all 0.3s ease;
    }
    .upload-box:hover {
        border-color: #4361ee;
        background-color: #f8f9ff !important;
    }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection
