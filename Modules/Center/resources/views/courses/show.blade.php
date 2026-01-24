@extends('center::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">{{ $course->title }}</h2>
            <div class="d-flex align-items-center gap-3 text-muted">
                @if($course->instructor)
                    <span><i class="fas fa-user-tie me-1"></i> {{ $course->instructor->name }}</span>
                @endif
                <span class="badge {{ $course->status == 'published' ? 'bg-success' : 'bg-secondary' }}">
                    {{ ucfirst($course->status) }}
                </span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('center.curriculum.edit', $course->id) }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-chalkboard me-2"></i> المحتوى
            </a>
            <a href="{{ route('center.courses.edit', $course->id) }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-edit me-2"></i> تعديل
            </a>
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#enrollStudentModal">
                <i class="fas fa-user-plus me-2"></i> تسجيل طالب
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 text-primary">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">الطلاب المسجلين</h6>
                        <h3 class="fw-bold mb-0">{{ $course->enrollments->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 text-success">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">نسبة الإكمال</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($course->enrollments->avg('progress'), 1) }}%</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3 text-info">
                        <i class="fas fa-money-bill fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">سعر الدورة</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($course->price, 2) }} ج.م</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Students List -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">قائمة الطلاب</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-start">الطالب</th>
                        <th class="border-0">تاريخ التسجيل</th>
                        <th class="border-0">التقدم</th>
                        <th class="border-0">الحالة</th>
                        <th class="border-0 rounded-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($course->enrollments as $enrollment)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        {{ substr($enrollment->user->student->name ?? $enrollment->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $enrollment->user->student->name ?? $enrollment->user->name }}</h6>
                                        <small class="text-muted">{{ $enrollment->user->student->phone ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $enrollment->enrolled_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 rounded-pill" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: {{ $enrollment->progress }}%"></div>
                                    </div>
                                    <span class="ms-2 small">{{ $enrollment->progress }}%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-{{ $enrollment->status == 'active' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $enrollment->status == 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ $enrollment->user->student ? route('center.students.show', $enrollment->user->student->id) : '#' }}" class="btn btn-sm btn-light rounded-pill">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-users-slash fa-2x mb-3"></i>
                                <p class="mb-0">لا يوجد طلاب مسجلين في هذه الدورة.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Enroll Student Modal -->
    <div class="modal fade" id="enrollStudentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">تسجيل طالب جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('center.courses.enroll', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">اختر الطالب</label>
                            <select name="student_id" class="form-select" id="studentSelect" required>
                                <option value="">ابحث عن طالب...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->phone }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-light rounded-pill ms-2" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">تسجيل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
        <style>
            .ts-control { border-radius: 0.5rem !important; padding: 0.75rem 1rem !important; }
            .ts-dropdown { border-radius: 0.5rem !important; box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important; }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            new TomSelect('#studentSelect', {
                plugins: ['dropdown_input'],
                sortField: { field: "text", direction: "asc" },
                maxOptions: null
            });
        </script>
    @endpush
@endsection
