

<?php
    // Helper to sanitize phone numbers for wa.me links
    // Strips spaces, dashes, parentheses, and leading '+'
    // Uses tenant's default country code for local numbers (starting with 0)
    function sanitizePhoneForWhatsApp($phone) {
        if (!$phone) return '';
        $phone = preg_replace('/[^0-9]/', '', $phone); // Keep digits only
        // If phone starts with '0' (local format), prepend tenant's country code
        if (str_starts_with($phone, '0')) {
            $countryCode = app('tenant')->settings['default_country_code'] ?? '20'; // Default: Egypt
            $phone = $countryCode . substr($phone, 1); // Remove leading 0, add country code
        }
        return $phone;
    }
?>

<?php $__env->startSection('content'); ?>
    <?php if(session('generated_password')): ?>
        <?php
            $msg = "مرحباً " . session('student_name') . "،\nيسعدنا انضمامك إلينا! 🎉\n\nبيانات الدخول الخاصة بك:\nرابط المنصة: " . url('/login') . "\nاسم المستخدم: " . (session('student_phone') ?? $student->phone) . "\nكلمة المرور: " . session('generated_password') . "\n\nنصيحة: سيُطلب منك تغيير كلمة المرور عند أول دخول للأمان.";
            $whatsappUrl = "https://wa.me/" . sanitizePhoneForWhatsApp(session('student_phone') ?? $student->phone) . "?text=" . urlencode($msg);
            $mailtoUrl = "mailto:" . (session('student_email') ?? $student->email) . "?subject=تم إعادة تعيين كلمة مرورك&body=" . rawurlencode($msg);
            
            $qrUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute('center.login.magic', now()->addMinutes(15), ['student' => $student->id, 'tenant' => app('tenant')->domain]);
        ?>

        <div class="premium-ticket-container mb-5 animate__animated animate__fadeIn">
            <div class="premium-ticket shadow-lg">
                <div class="row g-0">
                    <!-- Left Side: Student Info -->
                    <div class="col-md-8 p-4 bg-white rounded-start-4 position-relative overflow-hidden">
                        <div class="ticket-decoration"></div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-key fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('center::students.profile.password_reset_title')); ?></h4>
                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 mt-1"><?php echo e(__('center::students.profile.new_credentials')); ?></span>
                            </div>
                        </div>

                        <div class="row g-4 mt-2">
                            <div class="col-sm-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1"><?php echo e(__('center::students.profile.student_name')); ?></label>
                                <span class="fw-bold fs-5"><?php echo e($student->name); ?></span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1"><?php echo e(__('center::students.profile.temporary_password')); ?></label>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-4 fw-bold text-danger font-monospace"><?php echo e(session('generated_password')); ?></span>
                                    <button onclick="copyToClipboard('<?php echo e(session('generated_password')); ?>')" class="btn btn-sm btn-light rounded-circle" title="<?php echo e(__('center::students.copy')); ?>">
                                        <i class="fas fa-copy text-primary"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2">
                            <button onclick="copyAllDetails()" class="btn btn-outline-dark rounded-pill px-4">
                                <i class="fas fa-copy me-2"></i><?php echo e(__('center::students.profile.copy_all')); ?></button>
                            <a href="<?php echo e($whatsappUrl); ?>" target="_blank" class="btn btn-success rounded-pill px-4">
                                <i class="fab fa-whatsapp me-2"></i><?php echo e(__('center::students.profile.send_whatsapp')); ?></a>
                        </div>
                    </div>

                    <!-- Right Side: QR Code -->
                    <div class="col-md-4 p-4 text-center d-flex flex-column align-items-center justify-content-center bg-light rounded-end-4 border-start border-dashed position-relative">
                        <div class="ticket-stub-decoration top"></div>
                        <div class="ticket-stub-decoration bottom"></div>
                        
                        <div class="qr-container bg-white p-2 rounded-3 shadow-sm mb-3">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo e(urlencode($qrUrl)); ?>" alt="QR Code" style="width: 140px; height: 140px;">
                        </div>
                        <p class="small text-muted mb-0"><?php echo e(__('center::students.magic_login_tip')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4 animate__animated animate__fadeIn">

        <!-- Student Header Card -->
        <div class="col-12">
            <div class="card border-0 shadow-elite rounded-5 overflow-hidden position-relative mb-4" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-4 p-md-5 position-relative" style="z-index: 2;">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start" style="text-align: right !important;">
                        <!-- Profile Image Section -->
                        <div class="position-relative flex-shrink-0">
                            <?php if($student->profile_photo): ?>
                                <img src="<?php echo e(asset('storage/' . $student->profile_photo)); ?>" alt="<?php echo e($student->name); ?>" class="rounded-circle shadow-lg border border-4 border-white" style="width: 130px; height: 130px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg border border-4 border-white" style="width: 130px; height: 130px; font-size: 3.5rem;">
                                    <?php echo e(substr($student->name, 0, 1)); ?>

                                </div>
                            <?php endif; ?>
                            <div class="position-absolute bottom-0 end-0 bg-success border border-white border-4 rounded-circle p-2 pulse-success" title="<?php echo e(__('center::students.active')); ?>"></div>
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
                                <?php
                                    $reminderMsg = __('center::students.debt_reminder_msg', ['name' => $student->name]);
                                    $whatsappUrl = "https://wa.me/" . sanitizePhoneForWhatsApp($student->guardian?->phone ?? $student->parent_phone) . "?text=" . urlencode($reminderMsg);
                                ?>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo e($whatsappUrl); ?>" target="_blank" class="btn btn-success rounded-pill px-4 shadow-sm hover-lift fw-bold">
                                        <i class="fab fa-whatsapp me-2"></i><?php echo e(__('center::students.profile.send_whatsapp')); ?></a>
                                    <a href="<?php echo e(route('center.students.edit', $student->id)); ?>" class="btn btn-white border rounded-pill px-4 shadow-sm hover-lift text-dark fw-bold">
                                        <i class="fas fa-edit me-2"></i><?php echo e(__('center::students.profile.edit_profile')); ?></a>
                                    <button type="button" class="btn btn-outline-primary bg-white border rounded-pill px-4 shadow-sm hover-lift text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#sendEmailModal" title="<?php echo e(__('center::students.send_email') ?? 'إرسال بريد إلكتروني'); ?>">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Stats Strip (Redesigned) -->
                            <div class="row g-3">
                                <!-- Attendance Card -->
                                <div class="col-6 col-lg-3">
                                    <div class="stats-item bg-white shadow-sm rounded-4 p-3 border-start border-4 border-primary h-100">
                                        <div class="text-muted small mb-2"><?php echo e(__('center::students.attendance_stats_header')); ?></div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="fw-bold text-primary fs-5"><?php echo e($stats['attendance_pct']); ?>%</div>
                                                <div style="font-size: 0.65rem;" class="text-muted"><?php echo e(__('center::students.attendance_pct')); ?></div>
                                            </div>
                                            <div class="text-center border-start border-end px-2">
                                                <div class="fw-bold text-success"><?php echo e($stats['attendance_count']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted"><?php echo e(__('center::students.present')); ?></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold text-danger"><?php echo e($stats['absent_count']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted"><?php echo e(__('center::students.absent')); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Points Card -->
                                <div class="col-6 col-lg-3">
                                    <div class="stats-item bg-white shadow-sm rounded-4 p-3 border-start border-4 border-indigo h-100">
                                        <div class="text-muted small mb-2"><?php echo e(__('center::students.points_stats_header')); ?></div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="fw-bold text-indigo fs-5"><?php echo e($stats['points']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted"><?php echo e(__('center::students.net')); ?></div>
                                            </div>
                                            <div class="text-center border-start border-end px-2">
                                                <div class="fw-bold text-success"><?php echo e($stats['points_earned']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted"><?php echo e(__('center::students.earned')); ?></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold text-danger"><?php echo e($stats['points_spent']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted"><?php echo e(__('center::students.spent')); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quiz Stats Card -->
                                <div class="col-6 col-lg-3">
                                    <div class="stats-item bg-white shadow-sm rounded-4 p-3 border-start border-4 border-success h-100">
                                        <div class="text-muted small mb-2"><?php echo e(__('center::students.quizzes_stats_header')); ?></div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="fw-bold text-success fs-5"><?php echo e($stats['avg_quiz_score']); ?>%</div>
                                                <div style="font-size: 0.65rem;" class="text-muted"><?php echo e(__('center::students.avg')); ?></div>
                                            </div>
                                            <div class="text-center border-start border-end px-2">
                                                <div class="fw-bold text-dark"><?php echo e($stats['quiz_count']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted"><?php echo e(__('center::students.count_stat')); ?></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold text-primary"><?php echo e($stats['highest_score']); ?>%</div>
                                                <div style="font-size: 0.65rem;" class="text-muted"><?php echo e(__('center::students.highest')); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sessions Card -->
                                <div class="col-6 col-lg-3">
                                    <div class="stats-item bg-white shadow-sm rounded-4 p-3 border-start border-4 border-warning h-100">
                                        <div class="text-muted small mb-2"><?php echo e(__('center::students.sessions_stats_header')); ?></div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="fw-bold text-dark fs-5"><?php echo e($stats['total_sessions']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted"><?php echo e(__('center::students.total')); ?></div>
                                            </div>
                                            <div class="text-center border-start border-end px-2">
                                                <div class="fw-bold text-success"><?php echo e($stats['attendance_count']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted"><?php echo e(__('center::students.present')); ?></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold text-warning"><?php echo e($stats['remaining_sessions_count']); ?></div>
                                                <div style="font-size: 0.65rem;" class="text-muted"><?php echo e(__('center::students.remaining')); ?></div>
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

        <!-- Sidebar Cards: QR + Contact as Horizontal Row -->
        <div class="col-12">
            <div class="row g-4 mb-4">
                <!-- QR Code Card -->
                <div class="col-md-5 col-lg-4">
                    <div class="card border-0 shadow-elite rounded-5 p-4 text-center overflow-hidden position-relative h-100">
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="fas fa-qrcode fs-1"></i>
                        </div>
                        <h6 class="fw-bold text-dark border-bottom pb-3 mb-3"><?php echo e(__('center::students.profile.qr_code')); ?></h6>
                        
                        <div class="qr-display-container bg-light rounded-4 p-3 mb-3 position-relative shadow-inner">
                            <div id="sidebar-student-qrcode" class="d-flex justify-content-center"></div>
                            <div class="mt-2">
                                <code class="text-primary fw-bold fs-5">#<?php echo e($student->code); ?></code>
                            </div>
                        </div>
                        
                        <p class="small text-muted mb-3 px-2"><?php echo e(__('center::students.profile.scan_qr_tip')); ?></p>
                        
                        <?php
                            $magicLoginUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute('center.login.magic', now()->addMinutes(15), [
                                'student' => $student->id, 
                                'tenant' => app('tenant')->domain
                            ]);
                        ?>
                        
                        <div class="d-grid gap-2">
                            <button onclick="copyToClipboard('<?php echo e($magicLoginUrl); ?>')" class="btn btn-primary rounded-pill fw-bold shadow-sm btn-sm">
                                <i class="fas fa-magic me-2"></i><?php echo e(__('center::students.profile.copy_magic_link')); ?></button>
                            <button onclick="printIDCard()" class="btn btn-outline-dark rounded-pill fw-bold border-2 btn-sm">
                                <i class="fas fa-print me-2"></i><?php echo e(__('center::students.profile.print_id_card')); ?></button>
                        </div>
                    </div>
                </div>

                <!-- Contact & Family Card -->
                <div class="col-md-7 col-lg-8">
                    <div class="card border-0 shadow-sm rounded-5 p-4 h-100">
                        <h6 class="fw-bold text-primary mb-4"><i class="fas fa-users-cog me-2"></i><?php echo e(__('center::students.profile.contact_info')); ?></h6>
                        
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <div class="contact-item">
                                    <small class="text-muted d-block mb-1"><?php echo e(__('center::students.profile.student_phone')); ?></small>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold fs-6"><?php echo e($student->phone); ?></span>
                                        <a href="tel:<?php echo e($student->phone); ?>" class="btn btn-sm btn-light rounded-circle shadow-sm" title="اتصال"><i class="fas fa-phone-alt"></i></a>
                                        
                                        <!-- WhatsApp Dropdown (Student) -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light text-success rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false" title="<?php echo e(__('center::students.wa_student')); ?>">
                                                <i class="fab fa-whatsapp"></i>
                                            </button>
                                            <ul class="dropdown-menu shadow-sm border-0 rounded-4">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2" href="https://wa.me/<?php echo e(sanitizePhoneForWhatsApp($student->phone)); ?>" target="_blank">
                                                        <i class="fas fa-comment text-muted"></i> <?php echo e(__('center::students.wa_general_msg')); ?>

                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <?php
                                                    $paymentMsg = __('center::students.wa_student_payment_msg', ['name' => $student->name]);
                                                    $paymentUrl = "https://wa.me/" . sanitizePhoneForWhatsApp($student->phone) . "?text=" . urlencode($paymentMsg);
                                                ?>
                                                <li>
                                                    <a class="dropdown-item text-danger d-flex align-items-center gap-2" href="<?php echo e($paymentUrl); ?>" target="_blank">
                                                        <i class="fas fa-file-invoice-dollar"></i> <?php echo e(__('center::students.wa_payment_reminder')); ?>

                                                    </a>
                                                </li>
                                                <?php
                                                    $attendanceMsg = __('center::students.wa_student_attendance_msg', ['name' => $student->name]);
                                                    $attendanceUrl = "https://wa.me/" . sanitizePhoneForWhatsApp($student->phone) . "?text=" . urlencode($attendanceMsg);
                                                ?>
                                                <li>
                                                    <a class="dropdown-item text-warning d-flex align-items-center gap-2" href="<?php echo e($attendanceUrl); ?>" target="_blank">
                                                        <i class="fas fa-user-clock"></i> <?php echo e(__('center::students.wa_attendance_alert')); ?>

                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="contact-item">
                                    <small class="text-muted d-block mb-1"><?php echo e(__('center::students.guardian_relation', ['relation' => $student->parent_relation ?? __('center::students.profile.basic_info.parent_default')])); ?></small>
                                    <div class="fw-bold fs-6 mb-1 text-dark"><?php echo e($student->guardian?->name ?? $student->parent_name); ?></div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted small"><?php echo e($student->guardian?->phone ?? $student->parent_phone); ?></span>
                                        <a href="tel:<?php echo e($student->guardian?->phone ?? $student->parent_phone); ?>" class="btn btn-sm btn-light rounded-circle shadow-sm" title="اتصال"><i class="fas fa-phone-alt"></i></a>
                                        
                                        <!-- WhatsApp Dropdown (Guardian) -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light text-success rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false" title="<?php echo e(__('center::students.wa_guardian')); ?>">
                                                <i class="fab fa-whatsapp"></i>
                                            </button>
                                            <ul class="dropdown-menu shadow-sm border-0 rounded-4">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2" href="https://wa.me/<?php echo e(sanitizePhoneForWhatsApp($student->guardian?->phone ?? $student->parent_phone)); ?>" target="_blank">
                                                        <i class="fas fa-comment text-muted"></i> <?php echo e(__('center::students.wa_general_msg')); ?>

                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <?php
                                                    $parentPaymentMsg = __('center::students.wa_guardian_payment_msg', ['name' => $student->name]);
                                                    $parentPaymentUrl = "https://wa.me/" . sanitizePhoneForWhatsApp($student->guardian?->phone ?? $student->parent_phone) . "?text=" . urlencode($parentPaymentMsg);
                                                ?>
                                                <li>
                                                    <a class="dropdown-item text-danger d-flex align-items-center gap-2" href="<?php echo e($parentPaymentUrl); ?>" target="_blank">
                                                        <i class="fas fa-file-invoice-dollar"></i> <?php echo e(__('center::students.wa_payment_reminder')); ?>

                                                    </a>
                                                </li>
                                                <?php
                                                    $parentAttendanceMsg = __('center::students.wa_guardian_attendance_msg', ['name' => $student->name]);
                                                    $parentAttendanceUrl = "https://wa.me/" . sanitizePhoneForWhatsApp($student->guardian?->phone ?? $student->parent_phone) . "?text=" . urlencode($parentAttendanceMsg);
                                                ?>
                                                <li>
                                                    <a class="dropdown-item text-warning d-flex align-items-center gap-2" href="<?php echo e($parentAttendanceUrl); ?>" target="_blank">
                                                        <i class="fas fa-user-clock"></i> <?php echo e(__('center::students.wa_attendance_alert')); ?>

                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            
                            <!-- Student Email -->
                            <div class="col-sm-6 mt-4">
                                <div class="contact-item">
                                    <small class="text-muted d-block mb-1"><?php echo e(__('center::students.profile.student_email') ?? 'البريد الإلكتروني للطالب'); ?></small>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold fs-6 text-dark text-truncate" title="<?php echo e($student->user->email ?? $student->email); ?>"><?php echo e($student->user->email ?? $student->email ?? '---'); ?></span>
                                        <?php if($student->user?->email || $student->email): ?>
                                            <a href="mailto:<?php echo e($student->user->email ?? $student->email); ?>" class="btn btn-sm btn-light rounded-circle shadow-sm text-primary" title="إرسال بريد"><i class="fas fa-envelope"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Parent Email -->
                            <div class="col-sm-6 mt-4">
                                <div class="contact-item">
                                    <small class="text-muted d-block mb-1"><?php echo e(__('center::students.profile.parent_email') ?? 'البريد الإلكتروني لولي الأمر'); ?></small>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold fs-6 text-dark text-truncate" title="<?php echo e($student->guardian?->email ?? $student->parent_email); ?>"><?php echo e($student->guardian?->email ?? $student->parent_email ?? '---'); ?></span>
                                        <?php if($student->guardian?->email || $student->parent_email): ?>
                                            <a href="mailto:<?php echo e($student->guardian?->email ?? $student->parent_email); ?>" class="btn btn-sm btn-light rounded-circle shadow-sm text-primary" title="إرسال بريد"><i class="fas fa-envelope"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if($siblings->count() > 0): ?>
                            <div class="pt-3 border-top mt-3">
                                <h6 class="fw-bold text-dark small mb-3"><?php echo e(__('center::students.profile.siblings')); ?></h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php $__currentLoopData = $siblings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sibling): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(route('center.students.show', $sibling->id)); ?>" class="sibling-chip d-flex align-items-center gap-2 p-2 bg-light rounded-4 text-decoration-none hover-lift border" style="min-width: 180px;">
                                            <div class="bg-white rounded-circle p-2 shadow-sm text-primary">
                                                <i class="fas fa-user-graduate small"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-dark small mb-0"><?php echo e($sibling->name); ?></div>
                                                <small class="text-muted extra-small"><?php echo e($sibling->grade->name ?? '-'); ?></small>
                                            </div>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Horizontal Tabs Navigation -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom p-0">
                    <div class="profile-tabs-wrapper">
                        <ul class="nav nav-pills profile-horizontal-tabs d-flex flex-wrap justify-content-center gap-2 p-2 mb-0" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill px-3 py-2 fw-bold text-nowrap" data-bs-toggle="pill" data-bs-target="#pills-info" type="button" role="tab">
                                    <i class="fas fa-id-card-alt me-1"></i><span class="d-none d-md-inline"><?php echo e(__('center::students.profile.tabs.basic_info')); ?></span><span class="d-md-none"><?php echo e(__('center::students.profile.tabs.basic_info')); ?></span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-3 py-2 fw-bold text-nowrap" data-bs-toggle="pill" data-bs-target="#pills-academic" type="button" role="tab">
                                    <i class="fas fa-award me-1"></i><span class="d-none d-md-inline"><?php echo e(__('center::students.profile.tabs.academic')); ?></span><span class="d-md-none"><?php echo e(__('center::students.profile.tabs.academic')); ?></span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-3 py-2 fw-bold text-nowrap" data-bs-toggle="pill" data-bs-target="#pills-attendance" type="button" role="tab">
                                    <i class="fas fa-calendar-check me-1"></i><span class="d-none d-md-inline"><?php echo e(__('center::students.profile.tabs.attendance')); ?></span><span class="d-md-none"><?php echo e(__('center::students.profile.tabs.attendance')); ?></span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-3 py-2 fw-bold text-nowrap" data-bs-toggle="pill" data-bs-target="#pills-courses" type="button" role="tab">
                                    <i class="fas fa-book-open me-1"></i><span class="d-none d-md-inline"><?php echo e(__('center::students.profile.tabs.courses')); ?></span><span class="d-md-none"><?php echo e(__('center::students.profile.tabs.courses')); ?></span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-3 py-2 fw-bold text-nowrap" data-bs-toggle="pill" data-bs-target="#pills-sales" type="button" role="tab">
                                    <i class="fas fa-receipt me-1"></i><span class="d-none d-md-inline"><?php echo e(__('center::students.profile.tabs.financial')); ?></span><span class="d-md-none"><?php echo e(__('center::students.profile.tabs.financial')); ?></span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-3 py-2 fw-bold text-nowrap" data-bs-toggle="pill" data-bs-target="#pills-points" type="button" role="tab">
                                    <i class="fas fa-star me-1"></i><span class="d-none d-md-inline"><?php echo e(__('center::students.profile.tabs.points')); ?></span><span class="d-md-none"><?php echo e(__('center::students.profile.tabs.points')); ?></span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-3 py-2 fw-bold text-nowrap" data-bs-toggle="pill" data-bs-target="#pills-bookings" type="button" role="tab">
                                    <i class="fas fa-calendar-plus me-1"></i><span class="d-none d-md-inline"><?php echo e(__('center::students.profile.tabs.bookings')); ?></span><span class="d-md-none"><?php echo e(__('center::students.profile.tabs.bookings')); ?></span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-3 py-2 fw-bold text-nowrap" data-bs-toggle="pill" data-bs-target="#pills-activity" type="button" role="tab">
                                    <i class="fas fa-history me-1"></i><span class="d-none d-md-inline"><?php echo e(__('center::students.profile.tabs.activity')); ?></span><span class="d-md-none"><?php echo e(__('center::students.profile.tabs.activity')); ?></span></button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area (Full Width) -->
        <div class="col-12">
            <div class="tab-content">
                <!-- Tab: Basic Info -->
                <div class="tab-pane fade show active" id="pills-info">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h4 class="fw-bold mb-0"><?php echo e(__('center::students.profile.basic_info.title')); ?></h4>
                            <button type="button" onclick="printIDCard()" class="btn btn-light rounded-pill px-3 fw-bold">
                                <i class="fas fa-print me-2"></i><?php echo e(__('center::students.profile.print_id_card')); ?></button>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-block p-4 rounded-5 bg-light border border-white h-100">
                                    <div class="icon-circle bg-primary bg-opacity-10 text-primary mb-3">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <h6 class="fw-bold text-muted small"><?php echo e(__('center::students.profile.basic_info.school_info')); ?></h6>
                                    <div class="fw-bold text-dark fs-5"><?php echo e($student->school_name ?? __('center::students.profile.basic_info.no_school')); ?></div>
                                    <div class="text-primary fw-bold"><?php echo e($student->section_type ?? __('center::students.profile.basic_info.general_section')); ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-block p-4 rounded-5 bg-light border border-white h-100">
                                    <div class="icon-circle bg-danger bg-opacity-10 text-danger mb-3">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h6 class="fw-bold text-muted small"><?php echo e(__('center::students.profile.basic_info.address')); ?></h6>
                                    <div class="fw-bold text-dark fs-6"><?php echo e($student->address ?? __('center::students.profile.basic_info.no_address')); ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-4 rounded-4 bg-white shadow-sm border h-100 text-center">
                                    <h6 class="text-muted small mb-2"><?php echo e(__('center::students.profile.basic_info.birth_date')); ?></h6>
                                    <div class="fw-bold"><?php echo e($student->birth_date ? $student->birth_date->format('Y/m/d') : '---'); ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-4 rounded-4 bg-white shadow-sm border h-100 text-center">
                                    <h6 class="text-muted small mb-2"><?php echo e(__('center::students.profile.basic_info.national_id')); ?></h6>
                                    <div class="fw-bold"><?php echo e($student->national_id ?? '---'); ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-4 rounded-4 bg-white shadow-sm border h-100 text-center">
                                    <h6 class="text-muted small mb-2"><?php echo e(__('center::students.profile.basic_info.joined_at')); ?></h6>
                                    <div class="fw-bold text-success"><?php echo e($student->joined_at ? $student->joined_at->format('Y/m/d') : $student->created_at->format('Y/m/d')); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 p-4 bg-warning bg-opacity-10 rounded-5 border-dashed-warning">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-user-lock text-warning fs-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-warning"><?php echo e(__('center::students.profile.basic_info.security_settings')); ?></h6>
                                    <p class="text-muted mb-0 small"><?php echo e(__('center::students.profile.basic_info.security_help')); ?></p>
                                </div>
                                <form id="resetPasswordForm" action="<?php echo e(route('center.students.reset-password', $student->id)); ?>" method="POST" class="ms-auto">
                                    <?php echo csrf_field(); ?>
                                    <button type="button" id="resetPasswordBtn" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">
                                        <i class="fas fa-sync-alt me-2"></i><?php echo e(__('center::students.profile.basic_info.reset_password')); ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Academic Performance (Elete add) -->
                <div class="tab-pane fade" id="pills-academic">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <h4 class="fw-bold mb-5"><?php echo e(__('center::students.profile.academic.title')); ?></h4>
                        
                        <!-- Quizzes -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-dark border-start border-4 border-success ps-3 mb-4"><?php echo e(__('center::students.profile.academic.quizzes')); ?></h6>
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
                                    <div class="col-12 text-center py-4 bg-light rounded-4 text-muted"><?php echo e(__('center::students.profile.academic.no_quizzes')); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Assignments -->
                        <div>
                            <h6 class="fw-bold text-dark border-start border-4 border-primary ps-3 mb-4"><?php echo e(__('center::students.profile.academic.assignments')); ?></h6>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr class="text-muted small">
                                            <th><?php echo e(__('center::students.profile.academic.assignment_title')); ?></th>
                                            <th><?php echo e(__('center::students.profile.academic.date')); ?></th>
                                            <th><?php echo e(__('center::students.profile.academic.grade')); ?></th>
                                            <th><?php echo e(__('center::students.profile.academic.feedback')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td class="fw-bold small text-dark"><?php echo e($submission->assignment->title); ?></td>
                                                <td class="small text-muted"><?php echo e($submission->submitted_at->format('Y/m/d')); ?></td>
                                                <td><span class="badge <?php echo e($submission->grade ? 'bg-success' : 'bg-warning'); ?> bg-opacity-10 text-<?php echo e($submission->grade ? 'success' : 'warning'); ?> rounded-pill px-3"><?php echo e($submission->grade ?? __('center::students.profile.academic.pending_grade')); ?></span></td>
                                                <td class="small opacity-75"><?php echo e($submission->feedback ?? '---'); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr><td colspan="4" class="text-center py-4 text-muted small"><?php echo e(__('center::students.profile.academic.no_assignments')); ?></td></tr>
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
                            <h4 class="fw-bold mb-0"><?php echo e(__('center::students.profile.attendance.title')); ?></h4>
                            <div class="d-flex gap-2">
                                <div class="badge bg-success rounded-pill px-3"><?php echo e(__('center::students.present')); ?>: <?php echo e($attendance_logs->where('status', 'present')->count()); ?></div>
                                <div class="badge bg-danger rounded-pill px-3"><?php echo e(__('center::students.absent')); ?>: <?php echo e($attendance_logs->where('status', 'absent')->count()); ?></div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 rounded-start px-4"><?php echo e(__('center::students.profile.attendance.date')); ?></th>
                                        <th class="border-0"><?php echo e(__('center::students.session_content')); ?></th>
                                        <th class="border-0"><?php echo e(__('center::students.profile.attendance.check_in')); ?></th>
                                        <th class="border-0 rounded-end px-4"><?php echo e(__('center::students.profile.attendance.status')); ?></th>
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
                                                    <?php echo e($log->status == 'present' ? __('center::students.present') : ($log->status == 'absent' ? __('center::students.absent') : __('center::students.late'))); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="4" class="text-center py-5 text-muted"><?php echo e(__('center::students.profile.attendance.no_logs')); ?></td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab: Enrolled Courses -->
                <div class="tab-pane fade" id="pills-courses">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <h4 class="fw-bold mb-5"><?php echo e(__('center::students.profile.tabs.courses')); ?></h4>
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
                                        <p class="text-muted extra-small mb-4"><?php echo e(__('center::students.enrollment_date')); ?>: <?php echo e($enrollment->enrolled_at->format('Y/m/d')); ?></p>
                                        
                                        <div class="mb-2 d-flex justify-content-between small fw-bold">
                                            <span><?php echo e(__('center::students.profile.academic.progress')); ?></span>
                                            <span><?php echo e($enrollment->progress); ?>%</span>
                                        </div>
                                        <div class="progress rounded-pill bg-light" style="height: 6px;">
                                            <div class="progress-bar rounded-pill" style="width: <?php echo e($enrollment->progress); ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="col-12 text-center py-5 bg-light rounded-5 text-muted"><?php echo e(__('center::students.profile.academic.no_courses')); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tab: Financial Records -->
                <div class="tab-pane fade" id="pills-sales">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h4 class="fw-bold mb-0"><?php echo e(__('center::students.profile.financial.title')); ?></h4>
                            <div class="d-flex gap-2">
                                <form action="<?php echo e(route('center.students.remind-debt', $student->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-outline-success rounded-pill px-4 fw-bold shadow-sm" onclick="return confirm('<?php echo e(__('center::students.whatsapp_reminder_confirm')); ?>');">
                                        <i class="fab fa-whatsapp me-2"></i><?php echo e(__('center::students.send_reminder')); ?>

                                    </button>
                                </form>
                                <a href="<?php echo e(route('center.students.statement', $student->id)); ?>" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                    <i class="fas fa-file-invoice-dollar me-2"></i><?php echo e(__('center::students.student_ledger')); ?>

                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr class="text-muted small">
                                        <th class="px-3"><?php echo e(__('center::students.profile.financial.invoice_id')); ?></th>
                                        <th><?php echo e(__('center::students.profile.financial.total')); ?></th>
                                        <th><?php echo e(__('center::students.profile.financial.paid')); ?></th>
                                        <th><?php echo e(__('center::students.profile.financial.remaining')); ?></th>
                                        <th><?php echo e(__('center::students.profile.financial.date')); ?></th>
                                        <th><?php echo e(__('center::students.profile.financial.status')); ?></th>
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
                                        <tr><td colspan="6" class="text-center py-5 text-muted"><?php echo e(__('center::students.profile.financial.no_records')); ?></td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab: Activity Log -->
                <div class="tab-pane fade" id="pills-activity">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <h4 class="fw-bold mb-5"><?php echo e(__('center::students.profile.activity.title')); ?></h4>
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
                                        <div class="text-muted small"><?php echo e(__('center::students.profile.activity.by_user')); ?> <span class="fw-bold"><?php echo e($activity->causer->name ?? __('center::students.profile.activity.system')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-5 text-muted"><?php echo e(__('center::students.profile.activity.no_activity')); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tab: Points Log -->
                <div class="tab-pane fade" id="pills-points">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h4 class="fw-bold mb-0"><?php echo e(__('center::students.profile.points.title')); ?></h4>
                            <div class="badge bg-indigo-accent text-white rounded-pill px-4 py-2 fs-6 shadow-sm">
                                <?php echo e(__('center::students.points_total', ['points' => $stats['points']])); ?>

                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="text-muted small">
                                        <th><?php echo e(__('center::students.profile.points.points')); ?></th>
                                        <th><?php echo e(__('center::students.profile.points.reason')); ?></th>
                                        <th><?php echo e(__('center::students.profile.points.date')); ?></th>
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
                                        <tr><td colspan="3" class="text-center py-5 text-muted"><?php echo e(__('center::students.profile.points.no_logs')); ?></td></tr>
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
                            <h4 class="fw-bold mb-0"><?php echo e(__('center::students.profile.bookings.title')); ?></h4>
                            <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                                <i class="fas fa-plus me-2"></i><?php echo e(__('center::students.profile.bookings.add_booking')); ?></button>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 rounded-start px-4"><?php echo e(__('center::students.profile.bookings.course')); ?></th>
                                        <th class="border-0"><?php echo e(__('center::students.profile.bookings.time')); ?></th>
                                        <th class="border-0"><?php echo e(__('center::students.profile.bookings.classroom')); ?></th>
                                        <th class="border-0 rounded-end px-4"><?php echo e(__('center::students.profile.bookings.status')); ?></th>
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
                                        <tr><td colspan="4" class="text-center py-5 text-muted"><?php echo e(__('center::students.profile.bookings.no_bookings')); ?></td></tr>
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
                        <h4 class="modal-title fw-bold"><?php echo e(__('center::students.profile.bookings.modal_title')); ?></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 p-md-5">
                        <div class="mb-4">
                            <label class="form-label fw-bold opacity-75"><?php echo e(__('center::students.select_schedule')); ?></label>
                            <select name="schedule_id" class="form-select rounded-4 p-3 border-light bg-light" required>
                                <option value=""><?php echo e(__('center::students.profile.bookings.choose_schedule')); ?></option>
                                <?php $__currentLoopData = $availableSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sch->id); ?>">
                                        <?php echo e($sch->course->title); ?> | <?php echo e(__('center::schedules.' . $sch->day_of_week)); ?> (<?php echo e($sch->start_time); ?> - <?php echo e($sch->end_time); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i><?php echo e(__('center::students.profile.bookings.active_only_hint')); ?></small>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold opacity-75"><?php echo e(__('center::students.profile.bookings.notes_label')); ?></label>
                            <textarea name="notes" class="form-control rounded-4 p-3 border-light bg-light" rows="3" placeholder="<?php echo e(__('center::students.profile.bookings.notes_placeholder')); ?>"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 p-md-5 pt-0">
                        <button type="button" class="btn btn-white border rounded-pill px-4 fw-bold" data-bs-dismiss="modal"><?php echo e(__('center::students.profile.bookings.cancel')); ?></button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm"><?php echo e(__('center::students.profile.bookings.confirm')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Send Email Modal -->
    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-elite rounded-5">
                <div class="modal-header border-0 bg-light p-4 p-md-5 rounded-top-5 pb-4">
                    <h5 class="modal-title fw-bold"><i class="fas fa-envelope me-2 text-primary"></i> إرسال بريد إلكتروني للطالب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('center.students.send-email', $student->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body p-4 p-md-5">
                        <?php if(!$student->email && !($student->user->email ?? null)): ?>
                            <div class="alert alert-warning small border-0 shadow-sm rounded-4 text-center">
                                <i class="fas fa-exclamation-triangle me-1"></i> هذا الطالب لا يمتلك بريداً إلكترونياً مسجلاً. قد لا ينجح الإرسال.
                            </div>
                        <?php endif; ?>
                        <div class="mb-4">
                            <label class="form-label fw-bold opacity-75">موضوع الرسالة (Subject) <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control rounded-4 p-3 border-light bg-light" required placeholder="مثال: تنبيه غياب، تحديث بيانات، أو تحية">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold opacity-75">نص الرسالة <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control rounded-4 p-3 border-light bg-light" rows="6" required placeholder="اكتب محتوى رسالتك هنا..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 p-md-5 pt-0">
                        <button type="button" class="btn btn-white border rounded-pill px-4 fw-bold" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fas fa-paper-plane me-2"></i> إرسال الآن
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ID Card Print Layout (positioned off-screen until print) -->
    <div class="id-card-print">
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
                        <h1><?php echo e($tenant->name ?? __('center::students.profile.id_card.center_name_fallback')); ?></h1>
                        <span><?php echo e(__('center::students.profile.id_card.title')); ?></span>
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
                            <label><?php echo e(__('center::students.profile.id_card.student_code')); ?></label>
                            <strong><?php echo e($student->code); ?></strong>
                        </div>
                        <div class="info-item">
                            <label><?php echo e(__('center::students.profile.id_card.academic_year')); ?></label>
                            <strong><?php echo e(date('Y')); ?> - <?php echo e(date('Y')+1); ?></strong>
                        </div>
                    </div>

                    <div class="qr-area">
                        <div id="student-qrcode"></div>
                        <span class="code-text"><?php echo e($student->code); ?></span>
                    </div>
                </div>

                <!-- Footer -->
                <div class="id-footer">
                    <p><?php echo e(__('center::students.profile.id_card.ownership_tip')); ?></p>
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

        /* Ticket Styles */
        .premium-ticket {
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }
        .border-dashed {
            border-left: 2px dashed #dee2e6 !important;
        }
        .ticket-stub-decoration {
            position: absolute;
            width: 30px;
            height: 30px;
            background: #f8fafc;
            border-radius: 50%;
            left: -15px;
            z-index: 10;
        }
        .ticket-stub-decoration.top { top: -15px; }
        .ticket-stub-decoration.bottom { bottom: -15px; }
        
        @media (max-width: 768px) {
            .border-dashed {
                border-left: none !important;
                border-top: 2px dashed #dee2e6 !important;
            }
            .ticket-stub-decoration {
                display: none;
            }
        }
        
        .grade-badge { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.85rem; }

        /* Timeline */
        .timeline-item:last-child .timeline-content { border-bottom: none !important; }
        
        /* PRINT SPECIFIC STYLES - ID CARD */
        @media print {
            /* ONLY APPLY IF body.print-id-card IS PRESENT */
            body.print-id-card > :not(.id-card-print) {
                display: none !important;
            }
            
            body.print-id-card .id-card-print {
                display: flex !important;
                visibility: visible !important;
                position: fixed !important;
                left: 0 !important;
                top: 0 !important;
                width: 100vw;
                height: 100vh;
                align-items: center;
                justify-content: center;
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                z-index: 999999;
                direction: rtl !important;
                text-align: right;
            }

            body.print-id-card .id-card-print * {
                visibility: visible !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        /* Screen Styles for ID Card - Hide offscreen but rendered for QR */
        .id-card-print {
            position: fixed;
            left: -9999px;
            top: 0;
            opacity: 0;
            z-index: -100;
            /* Do NOT use display: none, otherwise QR code won't generate dimensions */
        }

            .id-card-container {
                width: 85.6mm; /* Standard ID Card Credit Card Size */
                height: 54mm;
                position: relative;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                z-index: 100000;
                margin: 0 auto;
                direction: rtl !important;
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
                color: #000000 !important; /* Force Black */
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
                background: #e0e7ff !important;
                color: #4338ca !important; /* Force Blue */
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
                /* flex: 1; */
            }

            .info-item label {
                display: block;
                font-size: 6pt !important;
                color: #64748b !important;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 0.5mm;
            }

            .info-item strong {
                display: block;
                font-size: 8pt !important;
                color: #000000 !important; /* Force Black */
                font-weight: bold !important;
            }

            .qr-area {
                margin-top: auto;
                margin-bottom: 2mm;
                text-align: center;
                width: 90%;
            }
            
            .qr-area canvas,
            .qr-area img {
                width: 18mm !important;
                height: 18mm !important;
                display: block !important;
                visibility: visible !important;
                margin: 0 auto;
            }
            
            #student-qrcode {
                display: block !important;
                visibility: visible !important;
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

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('resetPasswordBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: "<?php echo e(__('center::students.profile.reset_password.modal_title')); ?>",
                text: "<?php echo e(__('center::students.profile.reset_password.modal_text')); ?>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: "<?php echo e(__('center::students.profile.reset_password.confirm_btn')); ?>",
                cancelButtonText: "<?php echo e(__('center::students.profile.reset_password.cancel')); ?>",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('resetPasswordForm').submit();
                }
            });
        });
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-5 bg-dark text-white p-3 rounded-4 shadow animate__animated animate__fadeInUp';
            toast.style.zIndex = '9999';
            toast.innerHTML = '<i class="fas fa-check-circle text-success me-2"></i> <?php echo e(__('center::students.profile.reset_password.copy_success')); ?>';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        });
    }

    function copyAllDetails() {
        <?php if(session('generated_password')): ?>
            const text = `<?php echo addslashes($msg); ?>`;
            navigator.clipboard.writeText(text).then(function() {
                alert("<?php echo e(__('center::students.profile.reset_password.copy_success')); ?>");
            });
        <?php endif; ?>
    }
</script>

<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- QR Code Library for ID Card -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    window.printIDCard = function() {
        window.open("<?php echo e(route('center.students.id-card', $student->id)); ?>", '_blank');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const qrContainer = document.getElementById('student-qrcode');
        const sidebarQrContainer = document.getElementById('sidebar-student-qrcode');

        if (typeof QRCode !== 'undefined') {
            if (qrContainer) {
                qrContainer.innerHTML = '';
                new QRCode(qrContainer, {
                    text: "<?php echo e($student->code); ?>",
                    width: 60,
                    height: 60,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
            }

            if (sidebarQrContainer) {
                sidebarQrContainer.innerHTML = '';
                new QRCode(sidebarQrContainer, {
                    text: "<?php echo e($student->code); ?>",
                    width: 150,
                    height: 150,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
            }
        } else {
            console.error('QRCode library not loaded');
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\students\show.blade.php ENDPATH**/ ?>