@extends('center::layouts.master')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold text-dark">نظام الامتحانات والنتائج</h2>
        <p class="text-muted">إدارة الاختبارات ومتابعة أداء الطلاب في الدورات المختلفة.</p>
    </div>

    <div class="row g-4">
        <!-- Quizzes List -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-journal-check me-2 text-primary"></i>الاختبارات المتوفرة</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">الاختبار / الدورة</th>
                                    <th class="border-0">درجة النجاح</th>
                                    <th class="border-0">الوقت</th>
                                    <th class="border-0 text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quizzes as $quiz)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $quiz->title }}</div>
                                            <small class="text-muted">{{ $quiz->lesson->section->course->title }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">
                                                {{ $quiz->passing_score }}%
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $quiz->duration_minutes ?? 'بدون وقت' }} دقيقة</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('center.quizzes.edit', $quiz) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="bi bi-pencil me-1"></i> تعديل
                                                </a>
                                                <a href="{{ route('center.quizzes.show', $quiz) }}" class="btn btn-sm btn-light rounded-pill px-3">
                                                    <i class="bi bi-eye me-1"></i> معاينة
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">لا يوجد اختبارات منشأة بعد</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Attempts -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-reception-4 me-2 text-success"></i>نشاط الطلاب الأخير</h5>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        @forelse($recentAttempts as $attempt)
                            <div class="list-group-item px-0 border-0 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-{{ $attempt->passed ? 'success' : 'danger' }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-{{ $attempt->passed ? 'check-circle' : 'x-circle' }} text-{{ $attempt->passed ? 'success' : 'danger' }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="fw-bold small">{{ $attempt->user->name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $attempt->quiz->title }}</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $attempt->completed_at ? $attempt->completed_at->diffForHumans() : 'جاري الحل' }}</small>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="fw-bold text-{{ $attempt->passed ? 'success' : 'danger' }}">{{ number_format($attempt->score, 0) }}%</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">لا يوجد نتائج طلاب حتى الآن</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
