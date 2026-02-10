@if($onboardingStatus->show_cards)
<div class="onboarding-wrapper mb-8 animate__animated animate__fadeInDown">
    @if(!$onboardingStatus->all_done)
    <!-- Welcome Header -->
    <div class="welcome-banner bg-white rounded-5 border shadow-elite p-6 mb-6 overflow-hidden relative">
        <div class="d-flex align-items-center gap-4 relative" style="z-index: 2;">
            <div class="welcome-emoji fs-1 animate__animated animate__bounceIn">👋</div>
            <div class="flex-grow-1">
                <h3 class="fw-bold text-dark mb-1 font-arabic">{{ __('center::dashboard.onboarding.welcome', ['name' => auth()->user()->name]) }}</h3>
                <p class="text-muted mb-0 font-arabic">{{ __('center::dashboard.onboarding.subtitle') }}</p>
            </div>
            <div class="onboarding-progress-mini">
                <div class="progress-circle" style="--p: {{ $onboardingStatus->progress }};">
                    <span class="fw-black">{{ $onboardingStatus->progress }}%</span>
                </div>
            </div>
        </div>
        <!-- Decorative bg pattern -->
        <div class="banner-pattern"></div>
    </div>

    <!-- Triple Interactive Cards -->
    <div class="row g-4">
        <!-- 1. Instructor Card -->
        <div class="col-lg-4">
            <div class="onboarding-card bg-white rounded-5 border p-5 h-100 transition-all {{ $onboardingStatus->instructor_added ? 'is-completed' : '' }}" 
                 x-data="{ loading: false, success: {{ $onboardingStatus->instructor_added ? 'true' : 'false' }}, error: '' }">
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="card-step-icon {{ $onboardingStatus->instructor_added ? 'bg-success' : 'bg-primary' }} text-white rounded-4 shadow-sm">
                        <template x-if="success">
                            <i class="bi bi-check-lg fs-4 animate__animated animate__flipInY"></i>
                        </template>
                        <template x-if="!success">
                            <i class="bi bi-person-badge-fill fs-4"></i>
                        </template>
                    </div>
                    <h5 class="fw-bold mb-0 font-arabic">{{ __('center::dashboard.onboarding.add_first_instructor') }}</h5>
                </div>

                <div class="card-content">
                    <template x-if="success">
                        <div class="success-state text-center py-4">
                            <div class="text-success fw-bold mb-2 font-arabic"><i class="bi bi-check-circle-fill me-1"></i> {{ __('center::dashboard.onboarding.instructor_added_success') }}</div>
                            <small class="text-muted font-arabic">تم ضبط بيانات المدرس الأول بنجاح</small>
                        </div>
                    </template>

                    <template x-if="!success">
                        <form @submit.prevent="loading = true; error = ''; 
                            fetch('{{ route('center.onboarding.instructor') }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: JSON.stringify({ name: $refs.instName.value, phone: $refs.instPhone.value })
                            }).then(r => r.json()).then(d => { if(d.success) { success = true; confetti(); } else error = d.message; }).finally(() => loading = false)">
                            
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1 px-1 font-arabic">اسم المدرس الكامل</label>
                                <input type="text" x-ref="instName" class="form-control rounded-4 bg-light border-0 py-2 px-3 fw-bold" placeholder="أ. محمد علي">
                            </div>
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 px-1 font-arabic">رقم الهاتف</label>
                                <input type="tel" x-ref="instPhone" class="form-control rounded-4 bg-light border-0 py-2 px-3 fw-bold" placeholder="01XXX-XXXXXX">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm" :disabled="loading">
                                <span x-show="!loading" class="font-arabic">إضافة المدرس <i class="bi bi-plus-lg ms-1"></i></span>
                                <span x-show="loading" class="spinner-border spinner-border-sm"></span>
                            </button>
                            <p x-show="error" class="text-danger small mt-2 font-arabic" x-text="error"></p>
                        </form>
                    </template>
                </div>
            </div>
        </div>

        <!-- 2. Course Card -->
        <div class="col-lg-4">
            <div class="onboarding-card bg-white rounded-5 border p-5 h-100 transition-all {{ $onboardingStatus->course_added ? 'is-completed' : '' }}"
                 x-data="{ loading: false, success: {{ $onboardingStatus->course_added ? 'true' : 'false' }}, error: '' }">
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="card-step-icon {{ $onboardingStatus->course_added ? 'bg-success' : 'bg-indigo' }} text-white rounded-4 shadow-sm">
                        <template x-if="success">
                            <i class="bi bi-check-lg fs-4 animate__animated animate__flipInY"></i>
                        </template>
                        <template x-if="!success">
                            <i class="bi bi-journal-bookmark-fill fs-4"></i>
                        </template>
                    </div>
                    <h5 class="fw-bold mb-0 font-arabic">{{ __('center::dashboard.onboarding.add_first_course') }}</h5>
                </div>

                <div class="card-content">
                    <template x-if="success">
                        <div class="success-state text-center py-4">
                            <div class="text-success fw-bold mb-2 font-arabic"><i class="bi bi-check-circle-fill me-1"></i> {{ __('center::dashboard.onboarding.course_added_success') }}</div>
                            <small class="text-muted font-arabic">أصبحت أول دورة جاهزة لاستقبال الطلاب</small>
                        </div>
                    </template>

                    <template x-if="!success">
                        <form @submit.prevent="loading = true; error = ''; 
                            fetch('{{ route('center.onboarding.course') }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: JSON.stringify({ name: $refs.courseName.value, price: $refs.coursePrice.value })
                            }).then(r => r.json()).then(d => { if(d.success) { success = true; confetti(); } else error = d.message; }).finally(() => loading = false)">
                            
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1 px-1 font-arabic">اسم الدورة</label>
                                <input type="text" x-ref="courseName" class="form-control rounded-4 bg-light border-0 py-2 px-3 fw-bold" placeholder="أساسيات الرياضيات">
                            </div>
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 px-1 font-arabic">سعر الدورة (ج.م)</label>
                                <input type="number" x-ref="coursePrice" class="form-control rounded-4 bg-light border-0 py-2 px-3 fw-bold" placeholder="250">
                            </div>
                            <button type="submit" class="btn btn-indigo w-100 rounded-pill py-2 fw-bold shadow-sm text-white" :disabled="loading" style="background-color: #4361ee">
                                <span x-show="!loading" class="font-arabic">إنشاء الدورة <i class="bi bi-journal-plus ms-1"></i></span>
                                <span x-show="loading" class="spinner-border spinner-border-sm"></span>
                            </button>
                            <p x-show="error" class="text-danger small mt-2 font-arabic" x-text="error"></p>
                        </form>
                    </template>
                </div>
            </div>
        </div>

        <!-- 3. Student Card -->
        <div class="col-lg-4">
            <div class="onboarding-card bg-white rounded-5 border p-5 h-100 transition-all {{ $onboardingStatus->student_added ? 'is-completed' : '' }}"
                 x-data="{ loading: false, success: {{ $onboardingStatus->student_added ? 'true' : 'false' }}, error: '' }">
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="card-step-icon {{ $onboardingStatus->student_added ? 'bg-success' : 'bg-info' }} text-white rounded-4 shadow-sm">
                        <template x-if="success">
                            <i class="bi bi-check-lg fs-4 animate__animated animate__flipInY"></i>
                        </template>
                        <template x-if="!success">
                            <i class="bi bi-people-fill fs-4"></i>
                        </template>
                    </div>
                    <h5 class="fw-bold mb-0 font-arabic">{{ __('center::dashboard.onboarding.add_first_student') }}</h5>
                </div>

                <div class="card-content">
                    <template x-if="success">
                        <div class="success-state text-center py-4">
                            <div class="text-success fw-bold mb-2 font-arabic"><i class="bi bi-check-circle-fill me-1"></i> {{ __('center::dashboard.onboarding.student_added_success') }}</div>
                            <small class="text-muted font-arabic">مبروك! تم تسجيل أول طالب في نظامك</small>
                        </div>
                    </template>

                    <template x-if="!success">
                        <form @submit.prevent="loading = true; error = ''; 
                            fetch('{{ route('center.onboarding.student') }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: JSON.stringify({ name: $refs.studentName.value, phone: $refs.studentPhone.value })
                            }).then(r => r.json()).then(d => { if(d.success) { success = true; confetti(); } else error = d.message; }).finally(() => loading = false)">
                            
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1 px-1 font-arabic">اسم الطالب</label>
                                <input type="text" x-ref="studentName" class="form-control rounded-4 bg-light border-0 py-2 px-3 fw-bold" placeholder="أحمد محمد">
                            </div>
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 px-1 font-arabic">هاتف الطالب</label>
                                <input type="tel" x-ref="studentPhone" class="form-control rounded-4 bg-light border-0 py-2 px-3 fw-bold" placeholder="01XXX-XXXXXX">
                            </div>
                            <button type="submit" class="btn btn-info w-100 rounded-pill py-2 fw-bold shadow-sm text-white" :disabled="loading">
                                <span x-show="!loading" class="font-arabic">تسجيل الطالب <i class="bi bi-person-plus ms-1"></i></span>
                                <span x-show="loading" class="spinner-border spinner-border-sm"></span>
                            </button>
                            <p x-show="error" class="text-danger small mt-2 font-arabic" x-text="error"></p>
                        </form>
                    </template>
                </div>
            </div>
        </div>

        <!-- 4. Schedule Card -->
        <div class="col-lg-4 mt-4">
            <div class="onboarding-card bg-white rounded-5 border p-5 h-100 transition-all {{ $onboardingStatus->schedule_added ? 'is-completed' : '' }}"
                 x-data="{ loading: false, success: {{ $onboardingStatus->schedule_added ? 'true' : 'false' }}, error: '' }">
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="card-step-icon {{ $onboardingStatus->schedule_added ? 'bg-success' : 'bg-warning' }} text-white rounded-4 shadow-sm">
                        <template x-if="success">
                            <i class="bi bi-check-lg fs-4 animate__animated animate__flipInY"></i>
                        </template>
                        <template x-if="!success">
                            <i class="bi bi-calendar-event-fill fs-4"></i>
                        </template>
                    </div>
                    <h5 class="fw-bold mb-0 font-arabic">{{ __('center::dashboard.onboarding.add_first_schedule') }}</h5>
                </div>

                <div class="card-content">
                    <template x-if="success">
                        <div class="success-state text-center py-4">
                            <div class="text-success fw-bold mb-2 font-arabic"><i class="bi bi-check-circle-fill me-1"></i> {{ __('center::dashboard.onboarding.schedule_added_success') }}</div>
                            <small class="text-muted font-arabic">تم ضبط الجدول الدراسي الأساسي بنجاح</small>
                        </div>
                    </template>

                    <template x-if="!success">
                        <div class="text-center py-2">
                            <p class="text-muted small mb-4 font-arabic">سيتم إنشاء جدول افتراضي للدورة الحالية في قاعة المركز الرئيسية.</p>
                            <button @click="loading = true; fetch('{{ route('center.onboarding.schedule') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json()).then(d => { if(d.success) { success = true; confetti(); } else error = d.message; }).finally(() => loading = false)" 
                                    class="btn btn-warning w-100 rounded-pill py-2 fw-bold shadow-sm text-white" :disabled="loading">
                                <span x-show="!loading" class="font-arabic">ضبط الجدول الآن <i class="bi bi-calendar-plus ms-1"></i></span>
                                <span x-show="loading" class="spinner-border spinner-border-sm"></span>
                            </button>
                            <p x-show="error" class="text-danger small mt-2 font-arabic" x-text="error"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- 5. Attendance Card -->
        <div class="col-lg-4 mt-4">
            <div class="onboarding-card bg-white rounded-5 border p-5 h-100 transition-all {{ $onboardingStatus->attendance_added ? 'is-completed' : '' }}"
                 x-data="{ loading: false, success: {{ $onboardingStatus->attendance_added ? 'true' : 'false' }}, error: '' }">
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="card-step-icon {{ $onboardingStatus->attendance_added ? 'bg-success' : 'bg-danger' }} text-white rounded-4 shadow-sm">
                        <template x-if="success">
                            <i class="bi bi-check-lg fs-4 animate__animated animate__flipInY"></i>
                        </template>
                        <template x-if="!success">
                            <i class="bi bi-clipboard-check-fill fs-4"></i>
                        </template>
                    </div>
                    <h5 class="fw-bold mb-0 font-arabic">{{ __('center::dashboard.onboarding.record_first_attendance') }}</h5>
                </div>

                <div class="card-content">
                    <template x-if="success">
                        <div class="success-state text-center py-4">
                            <div class="text-success fw-bold mb-2 font-arabic"><i class="bi bi-check-circle-fill me-1"></i> {{ __('center::dashboard.onboarding.attendance_registered_success') }}</div>
                            <small class="text-muted font-arabic">مبروك! قمت بتسجيل أول عملية حضور بنجاح</small>
                        </div>
                    </template>

                    <template x-if="!success">
                        <div class="text-center py-2">
                            <p class="text-muted small mb-4 font-arabic">قم بتسجيل حضور الطالب الأول في الجدول الذي تم إنشاؤه.</p>
                            <button @click="loading = true; fetch('{{ route('center.onboarding.attendance') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json()).then(d => { if(d.success) { success = true; confetti(); } else error = d.message; }).finally(() => loading = false)" 
                                    class="btn btn-danger w-100 rounded-pill py-2 fw-bold shadow-sm text-white" :disabled="loading">
                                <span x-show="!loading" class="font-arabic">تسجيل الحضور <i class="bi bi-check2-all ms-1"></i></span>
                                <span x-show="loading" class="spinner-border spinner-border-sm"></span>
                            </button>
                            <p x-show="error" class="text-danger small mt-2 font-arabic" x-text="error"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    @endif

    <!-- Celebration Banner -->
    @if($onboardingStatus->all_done)
        <div class="completion-celebration mt-4 p-5 bg-success text-white rounded-5 text-center animate__animated animate__zoomIn shadow-lg border-0">
            <div class="display-4 mb-3">🎉</div>
            <h3 class="fw-bold mb-3 font-arabic">{{ __('center::dashboard.onboarding.completion_message') }}</h3>
            <p class="fs-5 mb-4 font-arabic opacity-90">نظامك الآن جاهز للعمل. بمجرد الضغط على الزر، ستنتقل إلى لوحة التحكم الرئيسية.</p>
            <button onclick="completeOnboarding()" 
                    class="btn btn-light btn-lg rounded-pill px-5 fw-bold font-arabic shadow-sm hover-lift border-0 py-3">
                ابدأ الاستخدام الفعلي الآن <i class="bi bi-rocket-takeoff ms-2"></i>
            </button>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
<script>
    function completeOnboarding() {
        const btn = event.target;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> جاري التحميل...';
        
        fetch('{{ route('center.onboarding.complete') }}', { 
            method: 'POST', 
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
        }).then(() => {
            confetti({
                particleCount: 150,
                spread: 70,
                origin: { y: 0.6 }
            });
            setTimeout(() => location.reload(), 1500);
        });
    }

    @if($onboardingStatus->all_done)
    window.addEventListener('load', () => {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    });
    @endif
</script>

<style>
    .rounded-5 { border-radius: 2rem !important; }
    .shadow-elite { box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05); }
    .transition-all { transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1); }
    
    .onboarding-card.is-completed {
        border-color: #10b981 !important;
        background-color: #f0fdf4 !important;
    }
    
    .card-step-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #fff 0%, #f8faff 100%);
    }

    /* Simple Progress Circle */
    .progress-circle {
        --p: 0;
        --c: #2563eb;
        --b: 5px;
        --w: 60px;
        width: var(--w);
        height: var(--w);
        border-radius: 50%;
        display: grid;
        place-content: center;
        background: radial-gradient(closest-side, white 80%, transparent 0 99.9%, white 0),
                    conic-gradient(var(--c) calc(var(--p)*1%), #e2e8f0 0);
        font-size: 0.8rem;
    }

    .banner-pattern {
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background-image: radial-gradient(#2563eb11 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.5;
    }

    [dir="rtl"] .banner-pattern { right: 0; left: auto; }
</style>
@endif
