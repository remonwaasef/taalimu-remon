<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><?php echo e($course->title); ?></h2>
            <div class="d-flex align-items-center gap-3 text-muted">
                <?php if($course->instructor): ?>
                    <span><i class="fas fa-user-tie me-1"></i> <?php echo e($course->instructor->name); ?></span>
                <?php endif; ?>
                <span class="badge <?php echo e($course->status == 'published' ? 'bg-success' : 'bg-secondary'); ?>">
                    <?php echo e(ucfirst($course->status)); ?>

                </span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('center.curriculum.edit', $course->id)); ?>" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-chalkboard me-2"></i><?php echo e(__('center::courses.curriculum_content')); ?></a>
            <a href="<?php echo e(route('center.courses.edit', $course->id)); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-edit me-2"></i><?php echo e(__('center::courses.edit_course')); ?></a>
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#enrollStudentModal">
                <i class="fas fa-user-plus me-2"></i><?php echo e(__('center::courses.enroll_student_modal_title')); ?></button>
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
                        <h6 class="text-muted mb-1"><?php echo e(__('center::courses.enrolled_students')); ?></h6>
                        <h3 class="fw-bold mb-0"><?php echo e($course->enrollments->count()); ?></h3>
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
                        <h6 class="text-muted mb-1"><?php echo e(__('center::courses.completion_rate')); ?></h6>
                        <h3 class="fw-bold mb-0"><?php echo e(number_format($course->enrollments->avg('progress'), 1)); ?>%</h3>
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
                        <h6 class="text-muted mb-1"><?php echo e(__('center::courses.course_price')); ?></h6>
                        <h3 class="fw-bold mb-0"><?php echo e(format_price($course->price)); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Students List -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0"><?php echo e(__('center::courses.students_list')); ?></h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-start"><?php echo e(__('center::courses.student')); ?></th>
                        <th class="border-0"><?php echo e(__('center::courses.enrollment_date')); ?></th>
                        <th class="border-0"><?php echo e(__('center::courses.progress')); ?></th>
                        <th class="border-0"><?php echo e(__('center::courses.status')); ?></th>
                        <th class="border-0 rounded-end"><?php echo e(__('center::courses.actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $course->enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <?php echo e(substr($enrollment->user->student->name ?? $enrollment->user->name, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?php echo e($enrollment->user->student->name ?? $enrollment->user->name); ?></h6>
                                        <small class="text-muted"><?php echo e($enrollment->user->student->phone ?? 'N/A'); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($enrollment->enrolled_at->format('Y-m-d')); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 rounded-pill" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: <?php echo e($enrollment->progress); ?>%"></div>
                                    </div>
                                    <span class="ms-2 small"><?php echo e($enrollment->progress); ?>%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-<?php echo e($enrollment->status == 'active' ? 'success' : 'warning'); ?> bg-opacity-10 text-<?php echo e($enrollment->status == 'active' ? 'success' : 'warning'); ?>">
                                    <?php echo e(ucfirst($enrollment->status)); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e($enrollment->user->student ? route('center.students.show', $enrollment->user->student->id) : '#'); ?>" class="btn btn-sm btn-light rounded-pill">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-users-slash fa-2x mb-3"></i>
                                <p class="mb-0"><?php echo e(__('center::courses.no_students_enrolled')); ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Enroll Student Modal -->
    <div class="modal fade" id="enrollStudentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-5 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold fs-4"><?php echo e(__('center::courses.enroll_student_modal_title')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Custom Tabs -->
                    <ul class="nav nav-pills mb-4 bg-light p-1 rounded-pill" id="enrollTabs" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link active rounded-pill w-100 fw-bold" id="existing-tab" data-bs-toggle="pill" data-bs-target="#existing-panel" type="button" role="tab">
                                <i class="fas fa-search me-2"></i><?php echo e(__('center::courses.enrolled_student_tab')); ?></button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link rounded-pill w-100 fw-bold" id="quick-tab" data-bs-toggle="pill" data-bs-target="#quick-panel" type="button" role="tab">
                                <i class="fas fa-user-plus me-2"></i><?php echo e(__('center::courses.quick_new_student_tab')); ?></button>
                        </li>
                    </ul>

                    <div class="tab-content" id="enrollTabsContent">
                        <!-- Panel 1: Existing Student -->
                        <div class="tab-pane fade show active" id="existing-panel" role="tabpanel">
                            <form action="<?php echo e(route('center.courses.enroll', $course->id)); ?>" method="POST" class="p-2">
                                <?php echo csrf_field(); ?>
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><?php echo e(__('center::courses.choose_student_label')); ?></label>
                                    <select name="student_id" class="form-select border-2" id="studentSelect" required>
                                        <option value=""><?php echo e(__('center::courses.search_student_modal_placeholder')); ?></option>
                                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($student->id); ?>"><?php echo e($student->name); ?> (<?php echo e($student->phone); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i><?php echo e(__('center::courses.search_unregistered_hint')); ?></div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold fs-5 shadow-sm"><?php echo e(__('center::courses.confirm_enrollment_btn')); ?><i class="fas fa-arrow-left ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Panel 2: Quick New Student -->
                        <div class="tab-pane fade" id="quick-panel" role="tabpanel">
                            <form action="<?php echo e(route('center.courses.quick-enroll', $course->id)); ?>" method="POST" class="p-2">
                                <?php echo csrf_field(); ?>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="name" class="form-control border-2 rounded-4" id="qName" placeholder="<?php echo e(__('center::courses.full_name')); ?>" required>
                                            <label for="qName"><?php echo e(__('center::courses.full_name')); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="tel" name="phone" class="form-control border-2 rounded-4" id="qPhone" placeholder="<?php echo e(__('center::courses.phone')); ?>" required>
                                            <label for="qPhone"><?php echo e(__('center::courses.phone')); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="tel" name="parent_phone" class="form-control border-2 rounded-4" id="qParentPhone" placeholder="<?php echo e(__('center::courses.parent_phone')); ?>">
                                            <label for="qParentPhone"><?php echo e(__('center::courses.parent_phone')); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating mb-4">
                                            <select name="grade_id" class="form-select border-2 rounded-4" id="qGrade" required>
                                                <option value=""><?php echo e(__('center::courses.select_grade')); ?></option>
                                                <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <optgroup label="📂 <?php echo e($stage->name); ?>">
                                                        <?php $__currentLoopData = $stage->grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </optgroup>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <label for="qGrade"><?php echo e(__('center::courses.grade_level')); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info border-0 rounded-4 py-3 small mb-4">
                                    <i class="fas fa-magic me-2"></i><?php echo e(__('center::courses.quick_enroll_modal_hint')); ?></div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success rounded-pill py-3 fw-bold fs-5 shadow-sm"><?php echo e(__('center::courses.create_and_enroll_btn')); ?><i class="fas fa-bolt ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('styles'); ?>
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
        <style>
            .ts-control { border-radius: 0.5rem !important; padding: 0.75rem 1rem !important; }
            .ts-dropdown { border-radius: 0.5rem !important; box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important; }
        </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            new TomSelect('#studentSelect', {
                plugins: ['dropdown_input'],
                sortField: { field: "text", direction: "asc" },
                maxOptions: null
            });

            <?php if(isset($auto_enroll) && $auto_enroll): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('enrollStudentModal'));
                myModal.show();
            });
            <?php endif; ?>
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\courses\show.blade.php ENDPATH**/ ?>