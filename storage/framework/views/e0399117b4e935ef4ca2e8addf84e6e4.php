

<?php $__env->startSection('content'); ?>
    <div class="row g-4 animate__animated animate__fadeIn">
        <!-- Student Header Card -->
        <div class="col-12">
            <div class="card border-0 shadow-elite rounded-5 overflow-hidden position-relative mb-4" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-4 p-md-5 position-relative" style="z-index: 2;">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-end">
                        <!-- Profile Image Section -->
                        <div class="position-relative flex-shrink-0">
                            <?php if($student->profile_photo): ?>
                                <img src="<?php echo e(asset('storage/' . $student->profile_photo)); ?>" alt="<?php echo e($student->name); ?>" class="rounded-circle shadow-lg border border-4 border-white" style="width: 130px; height: 130px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg border border-4 border-white" style="width: 130px; height: 130px; font-size: 3.5rem;">
                                    <?php echo e(substr($student->name, 0, 1)); ?>

                                </div>
                            <?php endif; ?>
                            <div class="position-absolute bottom-0 end-0 bg-success border border-white border-4 rounded-circle p-2 pulse-success" title="نشط"></div>
                        </div>

                        <!-- Main Info Section -->
                        <div class="flex-grow-1">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                                <div class="mb-3 mb-md-0">
                                    <h1 class="fw-bold text-dark mb-2 display-6"><?php echo e($student->name); ?></h1>
                                    <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold">
                                            <i class="fas fa-graduation-cap me-1"></i> <?php echo e($student->grade_level_name); ?>

                                        </span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 fw-bold">
                                            <i class="fas fa-barcode me-1"></i> <?php echo e($student->code); ?>

                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
                                        <?php if($student->school_name): ?>
                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1">
                                                <i class="fas fa-school me-1"></i> <?php echo e($student->school_name); ?>

                                            </span>
                                        <?php endif; ?>
                                        <?php if($student->section_type): ?>
                                            <span class="badge bg-dark bg-opacity-10 text-dark rounded-pill px-3 py-1">
                                                <i class="fas fa-shapes me-1"></i> <?php echo e($student->section_type); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="https://wa.me/<?php echo e($student->parent_phone ?? $student->guardian?->phone); ?>" target="_blank" class="btn btn-success rounded-pill px-4 shadow-sm hover-lift fw-bold">
                                        <i class="fab fa-whatsapp me-2"></i> ولي الأمر
                                    </a>
                                    <a href="<?php echo e(route('center.students.edit', $student->id)); ?>" class="btn btn-white border rounded-pill px-4 shadow-sm hover-lift text-dark fw-bold">
                                        <i class="fas fa-edit me-2"></i> تعديل البروفايل
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Stats Strip (Redesigned) -->
                            <div class="row g-3">
                                <!-- Attendance Card -->
                                <div class="col-6 col-lg-3">
                                    <div class="stats-item bg-white shadow-sm rounded-4 p-3 border-start border-4 border-primary h-100">
                                        <div class="text-muted small mb-2">الحضور (٪ / حاضر / غائب)</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="fw-bold text-primary fs-5"><?php echo e($stats['attendance_pct']); ?>%</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">النسبة</div>
                                            </div>
                                            <div class="text-center border-start border-end px-2">
                                                <div class="fw-bold text-success"><?php echo e($stats['attendance_count']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted">حضور</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold text-danger"><?php echo e($stats['absent_count']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted">غياب</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Points Card -->
                                <div class="col-6 col-lg-3">
                                    <div class="stats-item bg-white shadow-sm rounded-4 p-3 border-start border-4 border-indigo h-100">
                                        <div class="text-muted small mb-2">نقاط التميز (صافي / كسب / خصم)</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="fw-bold text-indigo fs-5"><?php echo e($stats['points']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted">الرصيد</div>
                                            </div>
                                            <div class="text-center border-start border-end px-2">
                                                <div class="fw-bold text-success"><?php echo e($stats['points_earned']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted">إضافة</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold text-danger"><?php echo e($stats['points_spent']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted">سحب</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quiz Stats Card -->
                                <div class="col-6 col-lg-3">
                                    <div class="stats-item bg-white shadow-sm rounded-4 p-3 border-start border-4 border-success h-100">
                                        <div class="text-muted small mb-2">الاختبارات (متوسط / عدد / أعلى)</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="fw-bold text-success fs-5"><?php echo e($stats['avg_quiz_score']); ?>%</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">المتوسط</div>
                                            </div>
                                            <div class="text-center border-start border-end px-2">
                                                <div class="fw-bold text-dark"><?php echo e($stats['quiz_count']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted">عدد</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold text-primary"><?php echo e($stats['highest_score']); ?>%</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">أعلى درجة</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sessions Card -->
                                <div class="col-6 col-lg-3">
                                    <div class="stats-item bg-white shadow-sm rounded-4 p-3 border-start border-4 border-warning h-100">
                                        <div class="text-muted small mb-2">الحصص (إجمالي / حاضر / باقي)</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="fw-bold text-dark fs-5"><?php echo e($stats['total_sessions']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted">إجمالي</div>
                                            </div>
                                            <div class="text-center border-start border-end px-2">
                                                <div class="fw-bold text-success"><?php echo e($stats['attendance_count']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted">حاضر</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold text-warning"><?php echo e($stats['remaining_sessions_count']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted">باقي</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Subtle Watermark -->
                <div class="position-absolute bottom-0 start-0 p-4 opacity-05 d-none d-lg-block" style="z-index: 1;">
                    <i class="fas fa-user-graduate" style="font-size: 180px; transform: rotate(15deg) translateY(40px);"></i>
                </div>
            </div>
        </div>

        <!-- Sidebar: Personal & Family -->
        <div class="col-lg-4">
            <!-- Sidebar Navigation (ScrollSpy Lite) -->
            <div class="card border-0 shadow-sm rounded-5 p-4 mb-4">
                <h6 class="fw-bold text-dark opacity-50 small text-uppercase mb-4">أقسام الملف</h6>
                <div class="nav flex-column gap-2 elite-profile-nav">
                    <button class="nav-link active rounded-pill text-start px-4 py-3 mb-1" data-bs-toggle="pill" data-bs-target="#pills-info">
                        <i class="fas fa-id-card-alt me-2"></i> المعلومات الأساسية
                    </button>
                    <button class="nav-link rounded-pill text-start px-4 py-3 mb-1" data-bs-toggle="pill" data-bs-target="#pills-academic">
                        <i class="fas fa-award me-2"></i> الأداء الأكاديمي
                    </button>
                    <button class="nav-link rounded-pill text-start px-4 py-3 mb-1" data-bs-toggle="pill" data-bs-target="#pills-attendance">
                        <i class="fas fa-calendar-check me-2"></i> سجل الحضور
                    </button>
                    <button class="nav-link rounded-pill text-start px-4 py-3 mb-1" data-bs-toggle="pill" data-bs-target="#pills-courses">
                        <i class="fas fa-book-open me-2"></i> الدورات والاشتراكات
                    </button>
                    <button class="nav-link rounded-pill text-start px-4 py-3 mb-1" data-bs-toggle="pill" data-bs-target="#pills-sales">
                        <i class="fas fa-receipt me-2"></i> السجل المالي
                    </button>
                    <button class="nav-link rounded-pill text-start px-4 py-3 mb-1" data-bs-toggle="pill" data-bs-target="#pills-points">
                        <i class="fas fa-star me-2"></i> النقاط والسلوك
                    </button>
                    <button class="nav-link rounded-pill text-start px-4 py-3 mb-1" data-bs-toggle="pill" data-bs-target="#pills-bookings">
                        <i class="fas fa-calendar-plus me-2"></i> الحجوزات
                    </button>
                    <button class="nav-link rounded-pill text-start px-4 py-3" data-bs-toggle="pill" data-bs-target="#pills-activity">
                        <i class="fas fa-history me-2"></i> سجل النشاطات
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-5 p-4">
                <h6 class="fw-bold text-primary mb-4"><i class="fas fa-users-cog me-2"></i> بيانات التواصل والأسرة</h6>
                
                <div class="contact-item mb-4">
                    <small class="text-muted d-block mb-1">هاتف الطالب</small>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold fs-6"><?php echo e($student->phone); ?></span>
                        <a href="tel:<?php echo e($student->phone); ?>" class="btn btn-sm btn-light rounded-circle shadow-sm"><i class="fas fa-phone-alt"></i></a>
                    </div>
                </div>

                <div class="contact-item mb-4">
                    <small class="text-muted d-block mb-1">ولي الأمر (<?php echo e($student->parent_relation ?? 'والد'); ?>)</small>
                    <div class="fw-bold fs-6 mb-1 text-dark"><?php echo e($student->guardian?->name ?? $student->parent_name); ?></div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small"><?php echo e($student->guardian?->phone ?? $student->parent_phone); ?></span>
                        <a href="https://wa.me/<?php echo e($student->guardian?->phone ?? $student->parent_phone); ?>" class="btn btn-sm btn-light text-success rounded-circle shadow-sm"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <?php if($siblings->count() > 0): ?>
                    <div class="pt-3 border-top mt-2">
                        <h6 class="fw-bold text-dark small mb-3">إخوة مسجلين بالمركز</h6>
                        <div class="d-flex flex-column gap-2">
                            <?php $__currentLoopData = $siblings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sibling): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('center.students.show', $sibling->id)); ?>" class="sibling-chip d-flex align-items-center gap-3 p-2 bg-light rounded-4 text-decoration-none hover-lift border">
                                    <div class="bg-white rounded-circle p-2 shadow-sm text-primary">
                                        <i class="fas fa-user-graduate small"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark small mb-0"><?php echo e($sibling->name); ?></div>
                                        <small class="text-muted extra-small"><?php echo e($sibling->grade->name ?? '-'); ?></small>
                                    </div>
                                    <i class="fas fa-chevron-left text-muted opacity-25"></i>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-8">
            <div class="tab-content">
                <!-- Tab: Basic Info -->
                <div class="tab-pane fade show active" id="pills-info">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h4 class="fw-bold mb-0">المعلومات الأساسية</h4>
                            <button type="button" onclick="window.print()" class="btn btn-light rounded-pill px-3 fw-bold">
                                <i class="fas fa-print me-2"></i> طباعة كارت الهوية
                            </button>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-block p-4 rounded-5 bg-light border border-white h-100">
                                    <div class="icon-circle bg-primary bg-opacity-10 text-primary mb-3">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <h6 class="fw-bold text-muted small">المدرسة والشعبة</h6>
                                    <div class="fw-bold text-dark fs-5"><?php echo e($student->school_name ?? 'غير محدد'); ?></div>
                                    <div class="text-primary fw-bold"><?php echo e($student->section_type ?? 'عام'); ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-block p-4 rounded-5 bg-light border border-white h-100">
                                    <div class="icon-circle bg-danger bg-opacity-10 text-danger mb-3">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h6 class="fw-bold text-muted small">العنوان بالتفصيل</h6>
                                    <div class="fw-bold text-dark fs-6"><?php echo e($student->address ?? 'لا يوجد عنوان مسجل'); ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-4 rounded-4 bg-white shadow-sm border h-100 text-center">
                                    <h6 class="text-muted small mb-2">تاريخ الميلاد</h6>
                                    <div class="fw-bold"><?php echo e($student->birth_date ? $student->birth_date->format('Y/m/d') : '---'); ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-4 rounded-4 bg-white shadow-sm border h-100 text-center">
                                    <h6 class="text-muted small mb-2">الرقم القومي</h6>
                                    <div class="fw-bold"><?php echo e($student->national_id ?? '---'); ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-4 rounded-4 bg-white shadow-sm border h-100 text-center">
                                    <h6 class="text-muted small mb-2">تاريخ الانضمام</h6>
                                    <div class="fw-bold text-success"><?php echo e($student->joined_at ? $student->joined_at->format('Y/m/d') : $student->created_at->format('Y/m/d')); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 p-4 bg-warning bg-opacity-10 rounded-5 border-dashed-warning">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-user-lock text-warning fs-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-warning">حساب الطالب</h6>
                                    <p class="text-muted mb-0 small">يستخدم الطالب هاتفه كاسم مستخدم لدخول المنصة ومراجعة الدروس والاختبارات.</p>
                                </div>
                                <form id="resetPasswordForm" action="<?php echo e(route('center.students.reset-password', $student->id)); ?>" method="POST" class="ms-auto">
                                    <?php echo csrf_field(); ?>
                                    <button type="button" id="resetPasswordBtn" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">
                                        <i class="fas fa-sync-alt me-2"></i> تصفير كلمة المرور
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Academic Performance (Elete add) -->
                <div class="tab-pane fade" id="pills-academic">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <h4 class="fw-bold mb-5">الأداء والنتائج</h4>
                        
                        <!-- Quizzes -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-dark border-start border-4 border-success ps-3 mb-4">آخر الاختبارات</h6>
                            <div class="row g-3">
                                <?php $__empty_1 = true; $__currentLoopData = $quiz_attempts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="col-md-6">
                                        <div class="quiz-result-card bg-white border rounded-4 p-3 shadow-sm d-flex align-items-center gap-3">
                                            <div class="grade-badge rounded-circle <?php echo e($attempt->score >= 50 ? 'bg-success' : 'bg-danger'); ?> text-white fw-bold">
                                                <?php echo e($attempt->score); ?>%
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-dark small mb-0"><?php echo e($attempt->quiz->title); ?></div>
                                                <small class="text-muted extra-small"><?php echo e($attempt->completed_at->diffForHumans()); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="col-12 text-center py-4 bg-light rounded-4 text-muted">لم يتم تسجيل اختبارات بعد</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Assignments -->
                        <div>
                            <h6 class="fw-bold text-dark border-start border-4 border-primary ps-3 mb-4">تسليمات الواجبات</h6>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr class="text-muted small">
                                            <th>الواجب</th>
                                            <th>التاريخ</th>
                                            <th>التقييم</th>
                                            <th>ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td class="fw-bold small text-dark"><?php echo e($submission->assignment->title); ?></td>
                                                <td class="small text-muted"><?php echo e($submission->submitted_at->format('Y/m/d')); ?></td>
                                                <td><span class="badge <?php echo e($submission->grade ? 'bg-success' : 'bg-warning'); ?> bg-opacity-10 text-<?php echo e($submission->grade ? 'success' : 'warning'); ?> rounded-pill px-3"><?php echo e($submission->grade ?? 'قيد التصحيح'); ?></span></td>
                                                <td class="small opacity-75"><?php echo e($submission->feedback ?? '---'); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr><td colspan="4" class="text-center py-4 text-muted small">لا توجد واجبات مسجلة</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Attendance (Elite add) -->
                <div class="tab-pane fade" id="pills-attendance">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h4 class="fw-bold mb-0">سجل الحضور والغياب</h4>
                            <div class="d-flex gap-2">
                                <div class="badge bg-success rounded-pill px-3">حاضر: <?php echo e($attendance_logs->where('status', 'present')->count()); ?></div>
                                <div class="badge bg-danger rounded-pill px-3">غائب: <?php echo e($attendance_logs->where('status', 'absent')->count()); ?></div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 rounded-start px-4">التاريخ</th>
                                        <th class="border-0">الحصة / المحتوى</th>
                                        <th class="border-0">وقت التحضير</th>
                                        <th class="border-0 rounded-end px-4">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $attendance_logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="px-4 fw-bold small"><?php echo e($log->session_date->format('Y-m-d')); ?></td>
                                            <td>
                                                <div class="small fw-bold text-dark"><?php echo e($log->course->title); ?></div>
                                                <small class="text-muted extra-small"><?php echo e(__('center::schedules.' . $log->schedule->day_of_week)); ?> (<?php echo e($log->schedule->start_time); ?>)</small>
                                            </td>
                                            <td class="small text-muted"><?php echo e($log->check_in_time ? $log->check_in_time->format('h:i A') : '---'); ?></td>
                                            <td class="px-4">
                                                <span class="badge bg-<?php echo e($log->status == 'present' ? 'success' : ($log->status == 'absent' ? 'danger' : 'warning')); ?> bg-opacity-10 text-<?php echo e($log->status == 'present' ? 'success' : ($log->status == 'absent' ? 'danger' : 'warning')); ?> rounded-pill px-3 font-arabic">
                                                    <?php echo e($log->status == 'present' ? 'حاضر' : ($log->status == 'absent' ? 'غائب' : 'متأخر')); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="4" class="text-center py-5 text-muted">لا يوجد سجل حضور حتى الآن</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab: Enrolled Courses -->
                <div class="tab-pane fade" id="pills-courses">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <h4 class="fw-bold mb-5">الدورات والمجموعات</h4>
                        <div class="row g-4">
                            <?php $__empty_1 = true; $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="col-md-6">
                                    <div class="course-elite-card bg-white border rounded-5 p-4 shadow-sm hover-lift h-100">
                                        <div class="d-flex justify-content-between mb-3 align-items-start">
                                            <div class="icon-sq bg-primary bg-opacity-10 text-primary rounded-4">
                                                <i class="fas fa-book-reader"></i>
                                            </div>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><?php echo e($enrollment->status); ?></span>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-1"><?php echo e($enrollment->course->title); ?></h5>
                                        <p class="text-muted extra-small mb-4">تاريخ الاشتراك: <?php echo e($enrollment->enrolled_at->format('Y/m/d')); ?></p>
                                        
                                        <div class="mb-2 d-flex justify-content-between small fw-bold">
                                            <span>التقدم</span>
                                            <span><?php echo e($enrollment->progress); ?>%</span>
                                        </div>
                                        <div class="progress rounded-pill bg-light" style="height: 6px;">
                                            <div class="progress-bar rounded-pill" style="width: <?php echo e($enrollment->progress); ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="col-12 text-center py-5 bg-light rounded-5 text-muted">غير مشترك في أي دورات حالياً</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tab: Financial Records -->
                <div class="tab-pane fade" id="pills-sales">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <h4 class="fw-bold mb-5">السجل المالي والمصروفات</h4>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr class="text-muted small">
                                        <th class="px-3">الرقم</th>
                                        <th>المبلغ</th>
                                        <th>المدفوع</th>
                                        <th>المتبقي</th>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="cursor-pointer" onclick="window.location='<?php echo e(route('center.sales.show', $sale->id)); ?>'">
                                            <td class="px-3 fw-bold">#<?php echo e($sale->id); ?></td>
                                            <td class="fw-bold text-dark"><?php echo e(number_format($sale->total_amount, 2)); ?></td>
                                            <td class="text-success fw-bold"><?php echo e(number_format($sale->paid_amount, 2)); ?></td>
                                            <td class="text-danger fw-bold"><?php echo e(number_format($sale->total_amount - $sale->paid_amount, 2)); ?></td>
                                            <td class="small text-muted"><?php echo e($sale->created_at->format('Y-m-d')); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger')); ?> bg-opacity-10 text-<?php echo e($sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger')); ?> rounded-pill px-3 fw-bold">
                                                    <?php echo e(__('center::sales.status_' . ($sale->status == 'pending' ? 'unpaid' : $sale->status))); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="6" class="text-center py-5 text-muted">لا توجد سجلات مالية</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab: Activity Log -->
                <div class="tab-pane fade" id="pills-activity">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <h4 class="fw-bold mb-5">تتبع النشاطات</h4>
                        <div class="activities-timeline">
                            <?php $__empty_1 = true; $__currentLoopData = $recent_activity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="timeline-item d-flex gap-4 mb-4">
                                    <div class="timeline-icon bg-light text-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                        <i class="fas fa-history small"></i>
                                    </div>
                                    <div class="timeline-content flex-grow-1 border-bottom pb-4">
                                        <div class="d-flex justify-content-between mb-1">
                                            <h6 class="fw-bold text-dark mb-0"><?php echo e($activity->description); ?></h6>
                                            <small class="text-muted extra-small"><?php echo e($activity->created_at->diffForHumans()); ?></small>
                                        </div>
                                        <div class="text-muted small">
                                            بواسطة: <span class="fw-bold"><?php echo e($activity->causer->name ?? 'النظام'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-5 text-muted">لا يوجد نشاط مسجل للتتبع</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tab: Points Log -->
                <div class="tab-pane fade" id="pills-points">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h4 class="fw-bold mb-0">سجل النقاط والسلوك</h4>
                            <div class="badge bg-indigo-accent text-white rounded-pill px-4 py-2 fs-6 shadow-sm">
                                الإجمالي: <?php echo e($stats['points']); ?> نقطة
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="text-muted small">
                                        <th>النقاط</th>
                                        <th>السبب</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $point_logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <span class="badge <?php echo e($log->points > 0 ? 'bg-success' : 'bg-danger'); ?> rounded-pill px-3">
                                                    <?php echo e($log->points > 0 ? '+' : ''); ?><?php echo e($log->points); ?>

                                                </span>
                                            </td>
                                            <td class="fw-bold small"><?php echo e($log->reason); ?></td>
                                            <td class="small text-muted"><?php echo e($log->created_at->format('Y-m-d h:i A')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="3" class="text-center py-5 text-muted">لا توجد نقاط مسجلة حالياً</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab: Bookings -->
                <div class="tab-pane fade" id="pills-bookings">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h4 class="fw-bold mb-0">الحجوزات والمواعيد</h4>
                            <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                                <i class="fas fa-plus me-2"></i> حجز موعد جديد
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 rounded-start px-4">الدورة</th>
                                        <th class="border-0">اليوم والوقت</th>
                                        <th class="border-0">القاعة</th>
                                        <th class="border-0 rounded-end px-4">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="px-4 fw-bold small text-dark"><?php echo e($booking->schedule->course->title); ?></td>
                                            <td>
                                                <div class="small fw-bold"><?php echo e(__('center::schedules.' . $booking->schedule->day_of_week)); ?></div>
                                                <small class="text-muted extra-small"><?php echo e($booking->schedule->start_time); ?> - <?php echo e($booking->schedule->end_time); ?></small>
                                            </td>
                                            <td class="small text-muted"><?php echo e($booking->schedule->classroom->name); ?></td>
                                            <td class="px-4">
                                                <span class="badge bg-<?php echo e($booking->status == 'confirmed' ? 'success' : 'danger'); ?> bg-opacity-10 text-<?php echo e($booking->status == 'confirmed' ? 'success' : 'danger'); ?> rounded-pill px-3">
                                                    <?php echo e($booking->status); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="4" class="text-center py-5 text-muted">لا توجد حجوزات نشطة حالياً</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Booking Modal -->
    <div class="modal fade" id="addBookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-elite rounded-5">
                <form action="<?php echo e(route('center.bookings.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="student_id" value="<?php echo e($student->id); ?>">
                    <div class="modal-header border-0 p-4 p-md-5 pb-0">
                        <h4 class="modal-title fw-bold">حجز موعد جديد للطالب</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 p-md-5">
                        <div class="mb-4">
                            <label class="form-label fw-bold opacity-75">اختر المجموعة / الموعد</label>
                            <select name="schedule_id" class="form-select rounded-4 p-3 border-light bg-light" required>
                                <option value="">--- اختر من المواعيد المتاحة ---</option>
                                <?php $__currentLoopData = $availableSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sch->id); ?>">
                                        <?php echo e($sch->course->title); ?> | <?php echo e(__('center::schedules.' . $sch->day_of_week)); ?> (<?php echo e($sch->start_time); ?> - <?php echo e($sch->end_time); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i> يتم عرض المواعيد المفعلة فقط في المركز.</small>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold opacity-75">ملاحظات إضافية (اختياري)</label>
                            <textarea name="notes" class="form-control rounded-4 p-3 border-light bg-light" rows="3" placeholder="أضف أي ملاحظات تتعلق بالحجز..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 p-md-5 pt-0">
                        <button type="button" class="btn btn-white border rounded-pill px-4 fw-bold" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">تأكيد الحجز</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ID Card Print Layout -->
    <div class="id-card-print d-none d-print-block">
        <div class="id-card-container">
            <!-- Front of Card -->
            <div class="id-card">
                <!-- Header / Logo Area -->
                <div class="id-header">
                    <div class="logo-area">
                        <?php if($tenant->logo): ?>
                            <img src="<?php echo e(asset('storage/' . $tenant->logo)); ?>" alt="Logo">
                        <?php else: ?>
                            <i class="fas fa-graduation-cap fa-2x text-white"></i>
                        <?php endif; ?>
                    </div>
                    <div class="center-name">
                        <h1><?php echo e($tenant->name ?? 'اسم المركز التعليمي'); ?></h1>
                        <span>بطاقة هوية طالب</span>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="id-body">
                    <div class="student-photo-wrapper">
                        <?php if($student->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . $student->profile_photo)); ?>" class="student-photo">
                        <?php else: ?>
                             <div class="student-photo-placeholder"><?php echo e(substr($student->name, 0, 1)); ?></div>
                        <?php endif; ?>
                        <div class="status-indicator"></div>
                    </div>

                    <h2 class="student-name"><?php echo e($student->name); ?></h2>
                    <div class="student-meta">
                        <span class="grade-badge"><?php echo e($student->grade_level_name ?? '---'); ?></span>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <label>كود الطالب</label>
                            <strong><?php echo e($student->code); ?></strong>
                        </div>
                        <div class="info-item">
                            <label>العام الدراسي</label>
                            <strong><?php echo e(date('Y')); ?> - <?php echo e(date('Y')+1); ?></strong>
                        </div>
                    </div>

                    <div class="qr-area">
                        <img src="<?php echo (new \chillerlan\QRCode\QRCode())->render($student->code); ?>" alt="QR Code">
                        <span class="code-text"><?php echo e($student->code); ?></span>
                    </div>
                </div>

                <!-- Footer -->
                <div class="id-footer">
                    <p>هذه البطاقة لإثبات هوية الطالب وتستخدم للدخول والحضور</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --elite-shadow: 0 20px 50px -15px rgba(58, 12, 163, 0.15);
            --indigo-accent: #6366f1;
        }
        
        body { background-color: #f8fafc; }
        .shadow-elite { box-shadow: var(--elite-shadow) !important; }
        .rounded-5 { border-radius: 2rem !important; }
        .text-indigo { color: var(--indigo-accent); }
        .font-arabic { font-family: 'Cairo', sans-serif; }
        .extra-small { font-size: 0.75rem; }

        /* Navigation */
        .elite-profile-nav .nav-link {
            border: none;
            color: #64748b;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
            position: relative;
        }
        .elite-profile-nav .nav-link:hover { background: #f1f5f9; color: var(--bs-primary); transform: translateX(-5px); }
        .elite-profile-nav .nav-link.active {
            background: #fff;
            color: var(--bs-primary);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transform: scale(1.02);
            border-right: 4px solid var(--bs-primary);
        }

        /* Widgets & Stats */
        .stats-item { transition: 0.3s; }
        .stats-item:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
        .opacity-05 { opacity: 0.05; }

        /* Widgets */
        .stats-mini-card:hover { transform: translateY(-5px); background: #fff !important; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .icon-circle { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 1rem; font-size: 1.2rem; }
        .icon-sq { width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        
        .pulse-success { animation: pulse-green 2s infinite; }
        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important; }
        .border-dashed-warning { border: 2px dashed rgba(255, 193, 7, 0.3); }
        
        .grade-badge { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.85rem; }

        /* Timeline */
        .timeline-item:last-child .timeline-content { border-bottom: none !important; }
        
        /* PRINT SPECIFIC STYLES - ID CARD */
        @media print {
            body * {
                visibility: hidden;
            }
            
            .id-card-print, .id-card-print * {
                visibility: visible;
            }

            .id-card-print {
                position: fixed;
                left: 0;
                top: 0;
                width: 100vw;
                height: 100vh;
                display: flex !important;
                align-items: flex-start;
                justify-content: center;
                background: white;
                padding-top: 2cm;
                z-index: 99999;
            }

            .id-card-container {
                width: 85.6mm; /* Standard ID Card Credit Card Size */
                height: 54mm;
                position: relative;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                z-index: 100000;
            }

            .id-card {
                width: 100%;
                height: 100%;
                border-radius: 4mm;
                overflow: hidden;
                position: relative;
                background: white;
                border: 1px solid #e2e8f0;
                display: flex;
                flex-direction: column;
            }

            /* Decorative Background Elements */
            .id-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 35%;
                background: var(--bs-primary);
                background: linear-gradient(135deg, var(--bs-primary) 0%, #4f46e5 100%);
                clip-path: polygon(0 0, 100% 0, 100% 70%, 0 100%);
                z-index: 0;
            }

            .id-header {
                position: relative;
                z-index: 1;
                display: flex;
                align-items: center;
                padding: 4mm 5mm 0;
                gap: 3mm;
                color: white;
            }

            .logo-area img {
                width: 10mm;
                height: 10mm;
                object-fit: contain;
                filter: brightness(0) invert(1); /* Make logo white if possible, or remove filter */
                background: rgba(255,255,255,0.2);
                border-radius: 2mm;
                padding: 1px;
            }
            
            .center-name h1 {
                font-size: 8pt;
                font-weight: 800;
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .center-name span {
                font-size: 6pt;
                opacity: 0.9;
                font-weight: 600;
            }

            .id-body {
                flex-grow: 1;
                position: relative;
                z-index: 2;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding-top: 2mm;
            }

            .student-photo-wrapper {
                width: 18mm;
                height: 18mm;
                border-radius: 50%;
                border: 2px solid white;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                overflow: hidden;
                margin-bottom: 2mm;
                background: #f1f5f9;
                position: relative;
            }
             
            .student-photo {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            .student-photo-placeholder {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                color: var(--bs-primary);
                font-size: 14pt;
            }

            .student-name {
                font-size: 11pt;
                font-weight: 800;
                color: #1e293b;
                margin: 0 0 1mm;
                text-align: center;
            }

            .student-meta {
                display: flex;
                gap: 2mm;
                margin-bottom: 2mm;
            }

            .student-meta .grade-badge {
                font-size: 6pt;
                background: #e0e7ff;
                color: #4338ca;
                padding: 0.5mm 2mm;
                border-radius: 2mm;
                font-weight: 700;
                width: auto;
                height: auto;
            }

            .info-grid {
                display: flex;
                justify-content: space-between;
                width: 80%;
                margin-bottom: 2mm;
                border-top: 1px solid #f1f5f9;
                padding-top: 2mm;
            }

            .info-item {
                text-align: center;
            }

            .info-item label {
                display: block;
                font-size: 5pt;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 0.5mm;
            }

            .info-item strong {
                display: block;
                font-size: 7pt;
                color: #334155;
            }

            .qr-area {
                margin-top: auto;
                margin-bottom: 2mm;
                text-align: center;
                width: 90%;
            }
            
            .qr-area img {
                width: 18mm;
                height: 18mm;
                display: block;
                margin: 0 auto;
            }
            
            .code-text {
                font-size: 6pt;
                font-family: monospace;
                letter-spacing: 2px;
                color: #475569;
                display: block;
                margin-top: 1px;
            }

            .id-footer {
                background: #f8fafc;
                padding: 1.5mm;
                text-align: center;
                border-top: 1px solid #e2e8f0;
            }
            
            .id-footer p {
                margin: 0;
                font-size: 5pt;
                color: #94a3b8;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('resetPasswordBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: 'تصفير كلمة المرور؟',
                text: 'سيتم إنشاء كلمة مرور عشوائية جديدة للطالب، هل أنت متأكد؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، تصفير الآن',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('resetPasswordForm').submit();
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules/Center\resources/views/students/show.blade.php ENDPATH**/ ?>