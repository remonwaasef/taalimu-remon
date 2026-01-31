

<?php $__env->startSection('content'); ?>
    <div class="row g-4 position-relative scroll-container-elite">
        <!-- Sticky Sidebar Navigation -->
        <div class="col-xl-3 d-none d-xl-block">
            <div class="sticky-top" style="top: 100px; z-index: 10;">
                <div class="elite-nav-card bg-white rounded-5 shadow-elite border p-4">
                    <h6 class="fw-bold mb-4 text-dark opacity-50 small text-uppercase letter-spacing-1">أقسام التسجيل</h6>
                    <div class="nav flex-column gap-3 elite-vertical-nav">
                        <a href="#section-personal" class="nav-link active" data-section="personal">
                            <div class="nav-dot"></div>
                            <div class="nav-content">
                                <span class="nav-label">البيانات الشخصية</span>
                                <small class="text-muted">الاسم، الهاتف، الصورة</small>
                            </div>
                        </a>
                        <a href="#section-parent" class="nav-link" data-section="parent">
                            <div class="nav-dot"></div>
                            <div class="nav-content">
                                <span class="nav-label">بيانات ولي الأمر</span>
                                <small class="text-muted">الطوارئ، هاتف الوالد</small>
                            </div>
                        </a>
                        <a href="#section-academic" class="nav-link" data-section="academic">
                            <div class="nav-dot"></div>
                            <div class="nav-content">
                                <span class="nav-label">البيانات الدراسية</span>
                                <small class="text-muted">الصف، المدرسة، التخصص</small>
                            </div>
                        </a>
                    </div>

                    <div class="mt-5 pt-4 border-top">
                        <div class="d-flex align-items-center gap-2 text-success small mb-3">
                            <i class="fas fa-shield-halved"></i>
                            <span class="fw-bold">تشفير البيانات نشط</span>
                        </div>
                        <button type="button" onclick="document.getElementById('student-form').submit()" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-elite btn-elite-submit">
                            حفظ الملف النهائي <i class="fas fa-check-double ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form Column -->
        <div class="col-xl-9">
            <div class="header-action-bar mb-5 animate__animated animate__fadeIn">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">تسجيل طالب جديد</h2>
                        <p class="text-muted mb-0">واجهة تسجيل حديثة وموحدة لإدارة بيانات الطلاب</p>
                    </div>
                    <a href="<?php echo e(route('center.students.index')); ?>" class="btn btn-white border shadow-sm rounded-pill px-4 hover-lift">
                        <i class="fas fa-arrow-right me-2"></i> قائمة الطلاب
                    </a>
                </div>
            </div>

            <form action="<?php echo e(route('center.students.store')); ?>" method="POST" enctype="multipart/form-data" id="student-form" class="needs-validation" novalidate>
                <?php echo csrf_field(); ?>
                
                <!-- Section 1: Personal -->
                <div id="section-personal" class="elite-form-card bg-white rounded-5 shadow-elite border p-4 p-md-5 mb-5 transition-all">
                    <div class="section-header d-flex align-items-center gap-3 mb-5">
                        <div class="section-icon bg-primary shadow-soft text-white rounded-4">
                            <i class="fas fa-user-astronaut fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">المعلومات الشخصية</h4>
                            <p class="text-muted small mb-0">البيانات الأساسية لتعريف هوية الطالب في النظام</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control" id="nameInput" placeholder="الاسم">
                                <label for="nameInput">اسم الطالب بالكامل</label>
                                <div class="validation-indicator"></div>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger extra-small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control" id="phoneInput" placeholder="الهاتف">
                                <label for="phoneInput">رقم الهاتف</label>
                                <div class="validation-indicator"></div>
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger extra-small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control" id="emailInput" placeholder="الإيميل">
                                <label for="emailInput">البريد الإلكتروني (اختياري)</label>
                                <div class="validation-indicator"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="code" value="<?php echo e(old('code')); ?>" class="form-control" id="codeInput" placeholder="الكود">
                                <label for="codeInput">كود الطالب المميز</label>
                                <div class="validation-indicator"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="address" value="<?php echo e(old('address')); ?>" class="form-control" id="addressInput" placeholder="العنوان">
                                <label for="addressInput">العنوان التفصيلي</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="national_id" value="<?php echo e(old('national_id')); ?>" class="form-control" id="idInput" placeholder="الرقم القومي">
                                <label for="idInput">الرقم القومي</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating elite-input-group">
                                <input type="date" name="birth_date" value="<?php echo e(old('birth_date')); ?>" class="form-control" id="dateInput">
                                <label for="dateInput">تاريخ الميلاد</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating elite-input-group">
                                <select name="gender" class="form-select" id="genderSelect">
                                    <option value="">الجنس...</option>
                                    <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?>>ذكر</option>
                                    <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?>>أنثى</option>
                                </select>
                                <label for="genderSelect">الجنس</label>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="elite-image-upload rounded-5 p-5 text-center transition-all bg-light border-dashed">
                                <div class="upload-visual mb-3 mx-auto">
                                    <div class="avatar-preview-box rounded-circle shadow-sm mx-auto mb-3" id="imagePreview">
                                        <i class="fas fa-camera-retro text-primary fs-3"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold mb-1">الصورة الشخصية</h6>
                                <p class="text-muted small">اسحب ملف الصورة أو انقر للاختيار</p>
                                <input type="file" name="profile_photo" id="photoInput" class="fake-input" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Parent -->
                <div id="section-parent" class="elite-form-card bg-white rounded-5 shadow-elite border p-4 p-md-5 mb-5 transition-all">
                    <div class="section-header d-flex align-items-center gap-3 mb-5">
                        <div class="section-icon bg-info shadow-soft text-white rounded-4">
                            <i class="fas fa-user-shield fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">بيانات ولي الأمر</h4>
                            <p class="text-muted small mb-0">تفاصيل التواصل في حالات الضرورة والمتابعة الأبوية</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group position-relative">
                                <input type="tel" name="parent_phone" id="parent_phone" value="<?php echo e(old('parent_phone')); ?>" class="form-control" placeholder="هاتف ولي الأمر">
                                <label for="parent_phone">رقم هاتف ولي الأمر</label>
                                <div class="validation-indicator"></div>
                                <div id="parent-match-chip" class="match-chip d-none animate__animated animate__bounceIn">
                                    <i class="fas fa-magic me-1"></i> تم التعرف: <b id="match-name"></b>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="parent_name" id="pNameInput" value="<?php echo e(old('parent_name')); ?>" class="form-control" placeholder="الاسم">
                                <label for="pNameInput">اسم ولي الأمر</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="parent_relation" value="<?php echo e(old('parent_relation')); ?>" class="form-control" id="relInput" placeholder="القرابة">
                                <label for="relInput">صلة القرابة</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="tel" name="emergency_phone" value="<?php echo e(old('emergency_phone')); ?>" class="form-control" id="ePhoneInput" placeholder="طوارئ">
                                <label for="ePhoneInput">رقم طوارئ إضافي</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="parent_job" id="pJobInput" value="<?php echo e(old('parent_job')); ?>" class="form-control" placeholder="الوظيفة">
                                <label for="pJobInput">وظيفة ولي الأمر</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Academic -->
                <div id="section-academic" class="elite-form-card bg-white rounded-5 shadow-elite border p-4 p-md-5 mb-5 transition-all">
                    <div class="section-header d-flex align-items-center gap-3 mb-5">
                        <div class="section-icon bg-success shadow-soft text-white rounded-4">
                            <i class="fas fa-graduation-cap fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">البيانات الأكاديمية</h4>
                            <p class="text-muted small mb-0">تحديد المستوى الدراسي والانتماء التعليمي</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-12">
                            <div class="form-floating elite-input-group">
                                <select name="grade_id" class="form-select" id="gradeSelect">
                                    <option value="">اختر الصف...</option>
                                    <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <optgroup label="📂 <?php echo e($stage->name); ?>">
                                            <?php $__currentLoopData = $stage->grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($grade->id); ?>" <?php echo e(old('grade_id') == $grade->id ? 'selected' : ''); ?>><?php echo e($grade->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </optgroup>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <label for="gradeSelect">الصف الدراسي الحالي</label>
                                <div class="validation-indicator"></div>
                                <?php $__errorArgs = ['grade_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger extra-small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="school_name" value="<?php echo e(old('school_name')); ?>" class="form-control" id="schoolInput" placeholder="المدرسة">
                                <label for="schoolInput">اسم المدرسة</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="section_type" value="<?php echo e(old('section_type')); ?>" class="form-control" id="secInput" placeholder="التخصص">
                                <label for="secInput">الشعبة (علمي/أدبي)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unified Sticky Submit Bar for Mobile -->
                <div class="d-xl-none fixed-bottom bg-white border-top p-3 d-flex gap-2 shadow-lg" style="z-index: 1000;">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold">إتمام التسجيل</button>
                    <a href="#section-personal" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="fas fa-arrow-up"></i></a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('.elite-form-card');
        const navLinks = document.querySelectorAll('.elite-vertical-nav .nav-link');
        const photoInput = document.getElementById('photoInput');
        const imagePreview = document.getElementById('imagePreview');

        // Scroll Spy Effect
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });

        // Smooth Scroll for Sidebar
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href');
                const targetEl = document.querySelector(targetId);
                window.scrollTo({
                    top: targetEl.offsetTop - 120,
                    behavior: 'smooth'
                });
            });
        });

        // Image Preview Magic
        photoInput?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" class="w-100 h-100 object-fit-cover rounded-circle border">`;
                    imagePreview.classList.add('glow-success');
                }
                reader.readAsDataURL(file);
            }
        });

        // Smart Interaction & Validation
        const allInputs = document.querySelectorAll('.form-control, .form-select');
        allInputs.forEach(input => {
            input.addEventListener('input', function() {
                const group = this.closest('.elite-input-group');
                if (!group) return;

                if (this.value.trim() !== '') {
                    if (this.checkValidity()) {
                        group.classList.add('is-elite-valid');
                        group.classList.remove('is-elite-invalid');
                    } else {
                        group.classList.add('is-elite-invalid');
                        group.classList.remove('is-elite-valid');
                    }
                } else {
                    group.classList.remove('is-elite-valid', 'is-elite-invalid');
                }
            });
        });

        // Guardian Magic Lookup
        const parentPhone = document.getElementById('parent_phone');
        const matchChip = document.getElementById('parent-match-chip');
        const matchName = document.getElementById('match-name');
        let whisper;

        parentPhone?.addEventListener('input', function() {
            clearTimeout(whisper);
            const val = this.value.trim();
            if (val.length >= 10) {
                whisper = setTimeout(() => {
                    fetch(`<?php echo e(route('center.guardians.lookup')); ?>?phone=${val}`)
                        .then(r => r.json())
                        .then(data => {
                            if (data.found) {
                                matchChip.classList.remove('d-none');
                                matchName.textContent = data.guardian.name;
                                document.getElementById('pNameInput').value = data.guardian.name;
                                document.getElementById('pJobInput').value = data.guardian.job || '';
                                document.getElementById('addressInput').value = data.guardian.address || '';
                                // Visual feedback
                                document.getElementById('pNameInput').dispatchEvent(new Event('input'));
                            } else {
                                matchChip.classList.add('d-none');
                            }
                        });
                }, 400);
            } else {
                matchChip.classList.add('d-none');
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    :root {
        --elite-primary: #3A0CA3;
        --elite-accent: #4361EE;
        --elite-soft-bg: #F8FAFC;
        --elite-radius: 2.5rem;
        --elite-shadow: 0 20px 50px -15px rgba(58, 12, 163, 0.15);
    }

    body { background-color: #f1f5f9; }

    .shadow-elite { box-shadow: var(--elite-shadow) !important; }
    .rounded-5 { border-radius: var(--elite-radius) !important; }
    .transition-all { transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }

    /* Vertical Navigation Sidebar */
    .elite-nav-card { border: 1px solid rgba(0,0,0,0.05); }

    .elite-vertical-nav .nav-link {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px 15px;
        border-radius: 1.2rem;
        color: #64748b;
        transition: 0.3s;
        border: 1px solid transparent;
    }

    .nav-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #cbd5e1;
        transition: 0.3s;
    }

    .elite-vertical-nav .nav-link.active {
        background: rgba(58, 12, 163, 0.05);
        color: var(--elite-primary);
        border-color: rgba(58, 12, 163, 0.1);
    }

    .elite-vertical-nav .nav-link.active .nav-dot {
        background: var(--elite-primary);
        transform: scale(1.5);
        box-shadow: 0 0 10px rgba(58, 12, 163, 0.3);
    }

    .nav-label { font-weight: 800; font-size: 0.95rem; }
    .nav-content small { display: block; font-size: 0.72rem; }

    /* Modern Card Layout */
    .elite-form-card {
        border: 1px solid rgba(0,0,0,0.02);
    }

    .section-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Floating Inputs Elite */
    .elite-input-group { position: relative; }

    .form-control, .form-select {
        border: 2px solid #f1f5f9 !important;
        background: #f8fafc !important;
        border-radius: 1.4rem !important;
        padding-top: 1.8rem !important;
        padding-bottom: 0.8rem !important;
        font-weight: 700;
        color: #1e293b;
        transition: 0.3s;
    }

    .form-control:focus, .form-select:focus {
        background: white !important;
        border-color: var(--elite-accent) !important;
        box-shadow: 0 10px 25px -5px rgba(67, 97, 238, 0.1) !important;
    }

    .validation-indicator {
        position: absolute;
        bottom: 0;
        right: 0;
        height: 4px;
        width: 0%;
        background: #10b981;
        transition: 0.5s ease;
        border-radius: 0 0 1.4rem 1.4rem;
    }

    .is-elite-valid .validation-indicator { width: 100%; }
    .is-elite-valid .form-control { border-color: rgba(16, 185, 129, 0.3) !important; }

    /* Image Upload Elite */
    .elite-image-upload {
        position: relative;
        cursor: pointer;
        border: 2px dashed #cbd5e1;
    }

    .elite-image-upload:hover {
        background: white;
        border-color: var(--elite-primary);
        transform: translateY(-5px);
    }

    .avatar-preview-box {
        width: 100px;
        height: 100px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.4s;
    }

    .fake-input { position: absolute; inset: 0; opacity: 0; cursor: pointer; }

    /* Guardian Match Chip */
    .match-chip {
        position: absolute;
        top: -12px;
        left: 20px;
        background: #10b981;
        color: white;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 800;
        z-index: 5;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Buttons */
    .btn-elite-submit {
        background: linear-gradient(135deg, var(--elite-primary), var(--elite-accent));
        border: none;
        color: white;
        box-shadow: 0 10px 20px -5px rgba(58, 12, 163, 0.4);
    }

    .btn-elite-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px -5px rgba(58, 12, 163, 0.5);
        color: white;
    }

    .hover-lift:hover { transform: translateY(-3px); }
    .letter-spacing-1 { letter-spacing: 1px; }

    [dir="rtl"] .form-floating > label { right: 0; left: auto; padding-right: 1.5rem; }
    [dir="rtl"] .match-chip { right: 20px; left: auto; }
</style>
<?php $__env->stopSection(); ?>
```

<?php echo $__env->make('center::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules/Center\resources/views/students/create.blade.php ENDPATH**/ ?>