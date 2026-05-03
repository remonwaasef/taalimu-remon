@extends('center::layouts.hope-master')

@section('title', __('center::students.import.title'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::students.import.header') }}</h2>
        <a href="{{ route('center.students.index', ['tenant' => app('tenant')->domain]) }}" class="btn btn-outline-secondary rounded-pill px-4">
            ← العودة للقائمة
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h5 class="fw-bold mb-0">رفع ملف CSV</h5>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success rounded-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger rounded-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('center.students.import.post', ['tenant' => app('tenant')->domain]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Download Sample -->
                        <div class="alert alert-light border rounded-4 mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="fw-bold mb-1">📥 تحميل ملف نموذجي</h6>
                                    <small class="text-muted">{{ __('center::students.import.desc') }}</small>
                                </div>
                                <a href="{{ asset('sample-students.csv?v=' . time()) }}" download="students-template.csv" class="btn btn-outline-primary rounded-pill px-4">{{ __('center::students.import.download_template') }}</a>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="file" class="form-label fw-bold">اختر ملف CSV</label>
                            <input type="file" class="form-control rounded-pill" id="file" name="file" required accept=".csv, .txt">
                            <small class="form-text text-muted mt-2 d-block">
                                يجب أن يكون الملف بصيغة CSV. الحقول الإلزامية: <strong>name, email</strong>. الحقول الاختيارية: phone, parent_phone, grade_level.
                            </small>
                        </div>

                        <div class="alert alert-info rounded-4 bg-info bg-opacity-10 border-0">
                            <h6 class="fw-bold text-info mb-2">📋 صيغة الملف المطلوبة:</h6>
                            <div class="bg-white p-3 rounded-3 border">
                                <div class="mb-2">
                                    <strong>{{ __('center::students.import.first_row_headers') }}</strong><br>
                                    <code class="text-dark">name,email,phone,grade_level</code>
                                </div>
                                <div>
                                    <strong>{{ __('center::students.import.data_example') }}</strong><br>
                                    <code class="text-dark">أحمد محمد,ahmed@example.com,0501234567,1</code><br>
                                    <code class="text-dark">فاطمة علي,fatima@example.com,0559876543,7</code>
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <strong>{{ __('center::students.import.note') }}</strong> {{ __('center::students.import.grade_level') ?? 'grade_level' }} من 1-12 (1-6 ابتدائي، 7-9 إعدادي، 10-12 ثانوي)
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-2">
                            <span class="me-2">📤</span>{{ __('center::students.import.start_import') }}</button>
                    </form>
                </div>
            </div>

            <!-- Quick Guide -->
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">💡 إرشادات سريعة</h6>
                    <ul class="mb-0">
                        <li class="mb-2">{{ __('center::students.import.ensure_csv_format') }}<code>.csv</code></li>
                        <li class="mb-2">{{ __('center::students.import.headers_must_match') }}</li>
                        <li class="mb-2">{{ __('center::students.import.unique_email_tip') }}</li>
                        <li>{{ __('center::students.import.default_password_tip') }}<code>password123</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
