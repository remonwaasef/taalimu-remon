@extends('center::layouts.hope-master')

@section('page-title', __('center::messages.blade_0536'))
@section('page-subtitle', __('center::messages.blade_0537'))

@section('page-actions')
    <a href="{{ route('center.questions.index') }}" class="btn btn-outline-primary shadow-sm rounded-pill px-4" style="font-weight: 500;">
        <i class="fas fa-database me-2"></i>{{ __('center::messages.blade_0538') }}
    </a>
@endsection

@section('content')

    <!-- Premium Instructional Banner for Desktop -->
    <div class="card border-0 mb-5 position-relative overflow-hidden shadow-sm d-none d-md-block" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 1.5rem;">
        <!-- decorative blurred elements -->
        <div class="position-absolute top-0 end-0 translate-middle pointer-events-none" style="width: 300px; height: 300px; background: rgba(16, 185, 129, 0.08); filter: blur(60px); border-radius: 50%;"></div>
        <div class="position-absolute bottom-0 start-0 translate-middle pointer-events-none" style="width: 250px; height: 250px; background: rgba(59, 130, 246, 0.08); filter: blur(60px); border-radius: 50%;"></div>
        
        <div class="card-body p-4 p-lg-5 d-flex flex-column flex-md-row align-items-center justify-content-between position-relative z-1">
            <div class="mb-4 mb-md-0 d-flex align-items-center gap-4">
                <div class="rounded-4 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-clipboard-check text-white" style="font-size: 2rem;"></i>
                </div>
                <div>
                    <h4 class="fw-bolder text-dark mb-2" style="letter-spacing: -0.5px;">كيف تدير الاختبارات في تعلِيمُ؟</h4>
                    <p class="text-muted mb-0" style="max-width: 600px; line-height: 1.6; font-size: 0.95rem;">
                        <span class="fw-bold text-dark">الاختبارات جزء لا يتجزأ من المنهج التعليمي.</span> لإنشاء اختبار للطلاب، يرجى التوجه إلى قسم الدورات، ثم فتح <strong>المنهج الدراسي</strong> الخاص بالدورة المطلوبة، وإضافة اختبارك كعنصر داخل الفصول الدراسية وتحديد أسئلته.
                    </p>
                </div>
            </div>
            <div class="ms-md-4 text-center">
                <a href="{{ route('center.courses.index') }}" class="btn btn-primary btn-lg shadow px-4 py-3 rounded-pill" style="font-weight: 600; min-width: 200px;">
                    <i class="fas fa-layer-group me-2"></i> {{ __('center::messages.blade_0539') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Informational Banner for Mobile -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 d-md-none position-relative overflow-hidden" style="background: #ffffff;">
        <div class="position-absolute top-0 end-0 bg-primary opacity-10" style="width: 100px; height: 100px; border-radius: 0 0 0 100%;"></div>
        <div class="card-body p-4 d-flex flex-column align-items-center text-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-clipboard-check text-white fs-3"></i>
            </div>
            <h5 class="fw-bold mb-2">كيف تدير الاختبارات؟</h5>
            <p class="text-muted small mb-4">يتم إنشاء الاختبارات مباشرة من داخل قسم الدورات والمناهج الدراسية لضمان تقديم تسلسل دراسي صحيح ومنظم للطالب.</p>
            <a href="{{ route('center.courses.index') }}" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm">
                <i class="fas fa-layer-group me-2"></i>{{ __('center::messages.blade_0539') }}
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 0.5px;">{{ __('center::messages.blade_0540') }}</p>
                            <h2 class="fw-bolder text-dark mb-0">{{ number_format($totalQuizzesCount) }}</h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; background: #ecfdf5;">
                            <i class="fas fa-file-alt fa-lg" style="color: #059669;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 0.5px;">{{ __('center::messages.blade_0541') }}</p>
                            <h2 class="fw-bolder text-dark mb-0">{{ number_format($totalAttemptsCount) }}</h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; background: #f0f9ff;">
                            <i class="fas fa-users fa-lg" style="color: #0284c7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 0.5px;">{{ __('center::messages.blade_0542') }}</p>
                            <h2 class="fw-bolder text-dark mb-0">{{ number_format($avgPassingRate, 1) }}%</h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; background: #fffbeb;">
                            <i class="fas fa-trophy fa-lg" style="color: #d97706;"></i>
                        </div>
                    </div>
                    <!-- Mini progress bar -->
                    <div class="mt-3">
                        <div class="progress" style="height: 6px; border-radius: 6px; background: #f1f5f9;">
                            <div class="progress-bar" role="progressbar" style="width: {{ min($avgPassingRate, 100) }}%; background: linear-gradient(90deg, #10b981, #34d399); border-radius: 6px;" aria-valuenow="{{ $avgPassingRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quizzes List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-header bg-white border-0 p-4 pb-3 d-flex justify-content-between align-items-center" style="border-radius: 1.25rem 1.25rem 0 0;">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-clipboard-list me-2" style="color: #10b981;"></i>{{ __('center::messages.blade_0543') }}</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('center::messages.blade_1078') }}</th>
                                    <th>{{ __('center::messages.blade_0544') }}</th>
                                    <th>{{ __('center::messages.blade_0545') }}</th>
                                    <th class="text-center">{{ __('center::messages.blade_0546') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quizzes as $quiz)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background: #f0fdf4;">
                                                    <i class="fas fa-file-alt" style="color: #10b981;"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $quiz->title }}</div>
                                                    <small class="text-muted"><i class="fas fa-book me-1"></i>{{ $quiz->lesson->section->course->title }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill px-3 py-2" style="background: #ecfdf5; color: #059669;">
                                                {{ $quiz->passing_score }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted fw-medium small">
                                                <i class="fas fa-clock me-1" style="color: #10b981;"></i>
                                                {{ $quiz->duration_minutes ?? __('center::messages.blade_0554') }} {{ __('center::messages.blade_1079') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('center.quizzes.edit', $quiz) }}" class="btn btn-sm btn-light" title="{{ __('center::messages.blade_0552') }}">
                                                    <i class="fas fa-pen" style="color: #10b981;"></i>
                                                </a>
                                                <a href="{{ route('center.quizzes.show', $quiz) }}" class="btn btn-sm btn-light" title="{{ __('center::messages.blade_0553') }}">
                                                    <i class="fas fa-eye" style="color: #64748b;"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="py-5">
                                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                                                    <i class="fas fa-clipboard-list fa-2x" style="color: #94a3b8;"></i>
                                                </div>
                                                <h5 class="fw-bold text-dark mb-2">{{ __('center::messages.blade_0547') }}</h5>
                                                <p class="text-muted d-block mx-auto mb-4" style="max-width: 450px; line-height: 1.6;">
                                                    لإضافة أول اختبار لك، يجب أن تتوجه إلى قسم المستودع/الدورات، قم باختيار دورتك، افتح إدارة المنهج وأضف الاختبار من هناك ليظهر هنا للطلاب للبدء في حله.
                                                </p>
                                                <div class="d-flex justify-content-center gap-3 flex-wrap">
                                                    <a href="{{ route('center.courses.index') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                                                        <i class="fas fa-book-reader me-2"></i>الذهاب للدورات التدريبية
                                                    </a>
                                                    <a href="{{ route('center.questions.index') }}" class="btn btn-white px-4 py-2 rounded-pill border shadow-sm text-dark bg-white">
                                                        <i class="fas fa-database me-2 text-primary"></i>بنك الأسئلة
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Attempts -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-header bg-white border-0 p-4 pb-3" style="border-radius: 1.25rem 1.25rem 0 0;">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-bolt me-2" style="color: #f59e0b;"></i>{{ __('center::messages.blade_0549') }}</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    @forelse($recentAttempts as $attempt)
                        <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded-3" style="background: #f8fafc; transition: all 0.2s ease;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: {{ $attempt->passed ? '#ecfdf5' : '#fef2f2' }};">
                                <i class="fas fa-{{ $attempt->passed ? 'check' : 'times' }}" style="color: {{ $attempt->passed ? '#059669' : '#dc2626' }};"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-bold text-dark small mb-0">{{ $attempt->user->name }}</div>
                                <div class="text-muted text-truncate" style="font-size: 0.75rem; max-width: 140px;">{{ $attempt->quiz->title }}</div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="badge rounded-pill px-2" style="font-size: 0.65rem; background: {{ $attempt->passed ? '#ecfdf5' : '#fef2f2' }}; color: {{ $attempt->passed ? '#059669' : '#dc2626' }};">
                                        {{ $attempt->passed ? __('center::messages.blade_0555') : __('center::messages.blade_0556') }}
                                    </span>
                                    <small class="text-muted" style="font-size: 0.65rem;"><i class="fas fa-clock me-1"></i>{{ $attempt->completed_at ? $attempt->completed_at->diffForHumans() : __('center::messages.blade_0557') }}</small>
                                </div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <div class="fw-bolder fs-5" style="color: {{ $attempt->passed ? '#059669' : '#dc2626' }};">{{ number_format($attempt->score, 0) }}%</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: #f1f5f9;">
                                <i class="fas fa-chart-bar fa-2x" style="color: #cbd5e1;"></i>
                            </div>
                            <p class="text-muted small fw-medium mb-0">{{ __('center::messages.blade_0550') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
