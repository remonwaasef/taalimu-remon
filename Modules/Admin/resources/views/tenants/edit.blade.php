@extends('admin::layouts.master')

@section('title', 'تعديل المركز')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">تعديل المركز</h2>
        <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            عودة للقائمة
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('admin.tenants.update', $tenant->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">اسم المركز</label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" placeholder="مثال: أكاديمية النور" value="{{ old('name', $tenant->name) }}" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">النطاق الفرعي (Subdomain)</label>
                            <div class="input-group">
                                <input type="text" name="domain" class="form-control form-control-lg bg-light border-0" placeholder="academy" value="{{ old('domain', $tenant->domain) }}" required>
                                <span class="input-group-text border-0 bg-white text-muted">.{{ config('app.tenant_domain', 'localhost') }}</span>
                            </div>
                            @error('domain')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">الحالة</label>
                            <select name="status" class="form-select form-select-lg bg-light border-0">
                                <option value="active" {{ old('status', $tenant->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status', $tenant->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">تحديث المركز</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
