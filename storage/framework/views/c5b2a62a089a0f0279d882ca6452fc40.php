

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><?php echo e(__('center::students.title')); ?></h2>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('center.students.export')); ?>" class="btn btn-outline-success rounded-pill px-4 shadow-sm" id="export-students-btn">
                <i class="fas fa-file-export me-2"></i> <?php echo e(__('center::students.export_file') ?? 'تصدير'); ?>

            </a>
            <a href="<?php echo e(route('center.students.import')); ?>" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-file-import me-2"></i> <?php echo e(__('center::students.import_file')); ?>

            </a>
            <a href="<?php echo e(route('center.students.create')); ?>" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <span class="me-2">+</span> <?php echo e(__('center::students.add_new')); ?>

            </a>
        </div>
    </div>

    <?php if(session('generated_password')): ?>
        <div class="premium-ticket-container mb-5 animate__animated animate__fadeIn">
            <div class="premium-ticket shadow-lg">
                <div class="row g-0">
                    <!-- Left Side: Student Info -->
                    <div class="col-md-8 p-4 bg-white rounded-start-4 position-relative overflow-hidden">
                        <div class="ticket-decoration"></div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-id-card fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">بطاقة تسجيل الطالب</h4>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 mt-1">تم التفعيل بنجاح</span>
                            </div>
                        </div>

                        <div class="row g-4 mt-2">
                            <div class="col-sm-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">اسم الطالب</label>
                                <span class="fw-bold fs-5"><?php echo e(session('student_name')); ?></span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">رقم الهاتف</label>
                                <span class="fw-bold text-dark"><?php echo e(session('student_phone')); ?></span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">البريد الإلكتروني</label>
                                <span class="text-primary fw-bold"><?php echo e(session('student_email')); ?></span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">كلمة المرور المؤقتة</label>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-4 fw-bold text-danger font-monospace"><?php echo e(session('generated_password')); ?></span>
                                    <button onclick="copyToClipboard('<?php echo e(session('generated_password')); ?>')" class="btn btn-sm btn-light rounded-circle" title="نسخ">
                                        <i class="fas fa-copy text-primary"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2">
                            <?php
                                $msg = "مرحباً " . session('student_name') . "،\nيسعدنا انضمامك إلينا! 🎉\n\nبيانات الدخول الخاصة بك:\nرابط المنصة: " . url('/login') . "\nالبريد: " . session('student_email') . "\nكلمة المرور: " . session('generated_password') . "\n\nنصيحة: سيُطلب منك تغيير كلمة المرور عند أول دخول للأمان.";
                                $whatsappUrl = "https://wa.me/" . session('student_phone') . "?text=" . urlencode($msg);
                                $mailtoUrl = "mailto:" . session('student_email') . "?subject=تم إنشاء حسابك بنجاح&body=" . rawurlencode($msg);
                            ?>

                            <button onclick="copyAllDetails()" class="btn btn-outline-dark rounded-pill px-4">
                                <i class="fas fa-copy me-2"></i> نسخ كافة البيانات
                            </button>
                            <a href="<?php echo e($whatsappUrl); ?>" target="_blank" class="btn btn-success rounded-pill px-4">
                                <i class="fab fa-whatsapp me-2"></i> إرسال واتساب
                            </a>
                            <a href="<?php echo e($mailtoUrl); ?>" class="btn btn-light border rounded-pill px-4">
                                <i class="fas fa-envelope me-2"></i> إرسال إيميل
                            </a>
                        </div>
                    </div>

                    <!-- Right Side: QR Code -->
                    <div class="col-md-4 p-4 text-center d-flex flex-column align-items-center justify-content-center bg-light rounded-end-4 border-start border-dashed position-relative">
                        <div class="ticket-stub-decoration top"></div>
                        <div class="ticket-stub-decoration bottom"></div>
                        
                        <div class="qr-container bg-white p-2 rounded-3 shadow-sm mb-3">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo e(urlencode(url('/login') . '?email=' . session('student_email'))); ?>" alt="QR Code" style="width: 140px; height: 140px;">
                        </div>
                        <p class="small text-muted mb-0">امسح الكود للدخول المباشر</p>
                        <div class="mt-3 text-secondary small">
                            <i class="fas fa-clock me-1"></i> صالح لمدة غير محدودة
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    const toast = document.createElement('div');
                    toast.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-5 bg-dark text-white p-3 rounded-4 shadow animate__animated animate__fadeInUp';
                    toast.style.zIndex = '9999';
                    toast.innerHTML = '<i class="fas fa-check-circle text-success me-2"></i> تم نسخ كلمة المرور!';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 2000);
                });
            }

            function copyAllDetails() {
                const text = `<?php echo addslashes($msg); ?>`;
                navigator.clipboard.writeText(text).then(function() {
                    alert('تم نسخ جميع البيانات بنجاح في صيغة رسالة منظمة!');
                });
            }
        </script>

        <style>
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
                background: #f8f9fa; /* Matches page bg or parent container */
                border-radius: 50%;
                left: -15px;
                z-index: 10;
            }
            .ticket-stub-decoration.top { top: -15px; }
            .ticket-stub-decoration.bottom { bottom: -15px; }
            
            .ticket-decoration {
                position: absolute;
                top: -50px;
                right: -50px;
                width: 150px;
                height: 150px;
                background: var(--bs-primary);
                opacity: 0.03;
                border-radius: 50%;
            }
            
            .qr-container { transition: transform 0.3s ease; }
            .qr-container:hover { transform: scale(1.05); }

            [dir="rtl"] .ticket-stub-decoration {
                left: auto;
                right: -15px;
            }
            [dir="rtl"] .border-dashed {
                border-left: none !important;
                border-right: 2px dashed #dee2e6 !important;
            }
        </style>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <!-- Search & Filter -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="position-relative">
                        <input 
                            type="text" 
                            id="search-input"
                            class="form-control ps-5 rounded-pill border-0 shadow-sm" 
                            placeholder="<?php echo e(__('center::students.search_placeholder')); ?>"
                            style="background-color: var(--color-light); height: 48px;"
                        >
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Stage Filter Buttons -->
            <div class="mb-3">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-primary rounded-pill px-4 stage-btn active" data-stage="all">
                        <?php echo e(__('center::students.all')); ?>

                    </button>
                    <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button class="btn btn-outline-primary rounded-pill px-4 stage-btn" data-stage="stage-<?php echo e($stage->id); ?>" data-grades="<?php echo e($stage->grades->pluck('id')->implode(',')); ?>">
                            <?php echo e($stage->name); ?>

                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Sub-grade Buttons (Hidden by default) -->
            <div class="mb-4">
                <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div id="stage-<?php echo e($stage->id); ?>-grades" class="sub-grades-container" style="display: none;">
                        <div class="d-flex gap-2 flex-wrap">
                            <?php $__currentLoopData = $stage->grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button class="btn btn-sm btn-outline-secondary rounded-pill grade-btn" data-grade="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Bulk Actions Toolbar (Hidden by default) -->
            <div id="bulk-actions-toolbar" class="bg-primary bg-opacity-10 p-3 rounded-4 mb-3 d-none animate__animated animate__fadeInDown">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary rounded-pill me-3" id="selected-count">0</span>
                        <span class="fw-bold text-primary">طالب محدد</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-success rounded-pill px-3" onclick="bulkAction('activate')">
                            <i class="fas fa-check me-1"></i> تفعيل
                        </button>
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="bulkAction('deactivate')">
                            <i class="fas fa-times me-1"></i> إلغاء تفعيل
                        </button>
                        <button class="btn btn-sm btn-danger rounded-pill px-3" onclick="bulkAction('delete')">
                            <i class="fas fa-trash me-1"></i> حذف
                        </button>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="table-responsive" style="min-height: 350px;">
                <table class="table align-middle custom-table">
                    <thead>
                        <tr>
                            <th class="border-0 bg-transparent px-3" style="width: 40px;">
                                <div class="form-check custom-check">
                                    <input class="form-check-input" type="checkbox" id="select-all">
                                </div>
                            </th>
                            <th class="border-0 bg-transparent"><?php echo e(__('center::students.name')); ?></th>
                            <th class="border-0 bg-transparent"><?php echo e(__('center::students.phone')); ?></th>
                            <th class="border-0 bg-transparent d-none d-lg-table-cell"><?php echo e(__('center::students.email')); ?></th>
                            <th class="border-0 bg-transparent"><?php echo e(__('center::students.grade')); ?></th>
                            <th class="border-0 bg-transparent"><?php echo e(__('center::students.status')); ?></th>
                            <th class="border-0 bg-transparent text-end px-4"><?php echo e(__('center::students.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="student-row align-middle border-bottom" data-grade="<?php echo e($student->grade_id); ?>">
                                <td class="px-3">
                                    <div class="form-check custom-check">
                                        <input class="form-check-input student-checkbox" type="checkbox" value="<?php echo e($student->id); ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar me-3">
                                            <?php if($student->profile_photo): ?>
                                                <img src="<?php echo e(Storage::url($student->profile_photo)); ?>" alt="Avatar" class="rounded-circle shadow-sm" style="width: 42px; height: 42px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                                                    <span class="fw-bold"><?php echo e(mb_substr($student->name, 0, 1)); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0"><?php echo e($student->name); ?></div>
                                            <div class="text-muted x-small d-lg-none"><?php echo e($student->email); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark small fw-medium"><?php echo e($student->phone); ?></span>
                                        <?php if($student->parent_phone): ?>
                                            <span class="text-muted x-small">ولي الأمر: <?php echo e($student->parent_phone); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-muted small d-none d-lg-table-cell"><?php echo e($student->email); ?></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-light text-dark fw-normal rounded-pill px-2 py-1 border mb-1">
                                            <?php echo e($student->grade_level_name); ?>

                                        </span>
                                        <?php if($student->school_name || $student->section_type): ?>
                                            <span class="text-muted extra-small">
                                                <?php echo e($student->school_name); ?><?php echo e($student->school_name && $student->section_type ? ' - ' : ''); ?><?php echo e($student->section_type); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo e($student->status == 'active' ? 'success' : 'danger'); ?> bg-opacity-10 text-<?php echo e($student->status == 'active' ? 'success' : 'danger'); ?> rounded-pill px-3">
                                        <i class="fas <?php echo e($student->status == 'active' ? 'fa-check' : 'fa-times'); ?> me-1 small"></i>
                                        <?php echo e($student->status == 'active' ? 'نشط' : 'متوقف'); ?>

                                    </span>
                                </td>
                                <td class="text-end px-4">
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-light rounded-circle shadow-none" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu border-0 shadow-lg p-2 rounded-4">
                                            <li><a class="dropdown-item rounded-3 mb-1" href="<?php echo e(route('center.students.show', $student->id)); ?>"><i class="fas fa-eye me-2 text-primary opacity-75"></i> <?php echo e(__('center::students.view_details')); ?></a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="<?php echo e(route('center.students.edit', $student->id)); ?>"><i class="fas fa-edit me-2 text-info opacity-75"></i> <?php echo e(__('center::students.edit')); ?></a></li>
                                            <li><hr class="dropdown-divider opacity-10"></li>
                                            <li>
                                                <form action="<?php echo e(route('center.students.destroy', $student->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟ سيتم حذف بيانات الطالب نهائياً.');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="dropdown-item rounded-3 text-danger mb-0">
                                                        <i class="fas fa-trash-alt me-2 opacity-75"></i> <?php echo e(__('center::students.delete')); ?>

                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/searching-no-result-illustration-download-in-svg-png-gif-file-formats--resultless-not-found-nothing-found-data-empty-miscellaneous-pack-people-illustrations-5795908.png" style="width: 200px; opacity: 0.5;">
                                    <p class="text-muted mt-3 mb-0"><?php echo e(__('center::students.no_students')); ?></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <?php echo e($students->links('components.ui.pagination')); ?>

            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Stage filter buttons
        const stageBtns = document.querySelectorAll('.stage-btn');
        const subGradeContainers = document.querySelectorAll('.sub-grades-container');
        const searchInput = document.getElementById('search-input');
        let currentStageGrades = null;

        stageBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                stageBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Hide all sub-grade containers
                subGradeContainers.forEach(c => c.style.display = 'none');
                
                const stage = this.getAttribute('data-stage');
                if (stage === 'all') {
                    currentStageGrades = null;
                } else {
                    const gradesAttr = this.getAttribute('data-grades');
                    currentStageGrades = gradesAttr ? gradesAttr.split(',') : [];
                    // Show corresponding sub-grades
                    const subGradeContainer = document.getElementById(stage + '-grades');
                    if (subGradeContainer) {
                        subGradeContainer.style.display = 'block';
                    }
                }
                filterStudents();
            });
        });

        // Grade filter buttons
        document.querySelectorAll('.grade-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.grade-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentStageGrades = [this.getAttribute('data-grade')];
                filterStudents();
            });
        });

        // Real-time search
        if (searchInput) {
            searchInput.addEventListener('input', filterStudents);
        }

        // Bulk Action logic
            const selectAll = document.getElementById('select-all');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const bulkToolbar = document.getElementById('bulk-actions-toolbar');
            const selectedCount = document.getElementById('selected-count');

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    studentCheckboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateBulkToolbar();
                });
            }

            studentCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkToolbar);
            });

            function updateBulkToolbar() {
                const checkedCount = Array.from(studentCheckboxes).filter(cb => cb.checked).length;
                if (checkedCount > 0) {
                    bulkToolbar.classList.remove('d-none');
                    selectedCount.textContent = checkedCount;
                } else {
                    bulkToolbar.classList.add('d-none');
                    if (selectAll) selectAll.checked = false;
                }
            }

            window.bulkAction = function(action) {
                const selectedIds = Array.from(studentCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
                if (selectedIds.length === 0) return;

                if (confirm(`هل أنت متأكد من تنفيذ هذا الإجراء على ${selectedIds.length} طالب؟`)) {
                    // This would normally be an AJAX call
                    alert(`جاري تنفيذ عملية [${action}] على المعرفات: ` + selectedIds.join(', '));
                    // Success simulation: 
                    // location.reload();
                }
            };

            // Filter function
            function filterStudents() {
                const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
                const studentRows = document.querySelectorAll('.student-row');
                const tbody = document.querySelector('tbody');
                let emptyRow = document.getElementById('empty-state-row');

                if (!emptyRow) {
                    emptyRow = document.createElement('tr');
                    emptyRow.id = 'empty-state-row';
                    emptyRow.innerHTML = `<td colspan="7" class="text-center py-5">
                                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/searching-no-result-illustration-download-in-svg-png-gif-file-formats--resultless-not-found-nothing-found-data-empty-miscellaneous-pack-people-illustrations-5795908.png" style="width: 200px; opacity: 0.5;">
                                    <p class="text-muted mt-3 mb-0"><?php echo e(__('center::students.no_students')); ?></p>
                                </td>`;
                }
                
                let visibleCount = 0;
                
                studentRows.forEach(row => {
                    const rowGrade = row.getAttribute('data-grade');
                    const text = row.textContent.toLowerCase();
                    
                    const gradeMatch = !currentStageGrades || currentStageGrades.includes(rowGrade);
                    const searchMatch = !searchTerm || text.includes(searchTerm);
                    
                    if (gradeMatch && searchMatch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                const existingEmpty = document.getElementById('empty-state-row');
                if (visibleCount === 0) {
                    if (!existingEmpty) tbody.appendChild(emptyRow);
                    else existingEmpty.style.display = '';
                } else if (existingEmpty) {
                    existingEmpty.style.display = 'none';
                }
            }
        });
    </script>
    
    <style>
        .custom-table thead th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            padding-bottom: 15px;
        }

        .student-row {
            transition: all 0.2s ease;
        }

        .student-row:hover {
            background-color: #f8fbff;
        }

        .student-row td {
            height: 70px;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .x-small {
            font-size: 0.75rem;
        }

        .stage-btn.active {
            background-color: var(--bs-primary);
            color: white;
            border-color: var(--bs-primary);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }
        
        .grade-btn.active {
            background-color: var(--bs-secondary);
            color: white;
            border-color: var(--bs-secondary);
        }
        
        .sub-grades-container {
            padding: 10px 0;
            border-top: 1px dashed #dee2e6;
        }

        .animate__animated {
            --animate-duration: 0.5s;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules/Center\resources/views/students/index.blade.php ENDPATH**/ ?>