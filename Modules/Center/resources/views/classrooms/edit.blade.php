@extends('center::layouts.master')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold text-dark">{{ isset($classroom) ? 'تعديل قاعة' : 'إضافة قاعة جديدة' }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('center.classrooms.index') }}">القاعات</a></li>
                <li class="breadcrumb-item active">{{ isset($classroom) ? 'تعديل' : 'إضافة' }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ isset($classroom) ? route('center.classrooms.update', $classroom) : route('center.classrooms.store') }}" method="POST">
                        @csrf
                        @if(isset($classroom)) @method('PUT') @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">اسم القاعة</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $classroom->name ?? '') }}" placeholder="مثلاً: قاعة 101">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">السعة الاستيعابية (اختياري)</label>
                            <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', $classroom->capacity ?? '') }}" placeholder="عدد الطلاب">
                            @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">حفظ القاعة</button>
                            <a href="{{ route('center.classrooms.index') }}" class="btn btn-light px-4 rounded-pill">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
