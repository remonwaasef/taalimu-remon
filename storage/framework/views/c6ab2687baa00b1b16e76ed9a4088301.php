<?php $__env->startSection('page-title', __('center::courses.title')); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('center.courses.create')); ?>" class="btn btn-glass">
        <i class="fas fa-plus me-2"></i> <?php echo e(__('center::courses.add_new')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <!-- Search & Filter -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <?php if (isset($component)) { $__componentOriginal0d0ae8bef4b4e146c2af1b2494139103 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d0ae8bef4b4e146c2af1b2494139103 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.search','data' => ['action' => ''.e(route('center.courses.index')).'','placeholder' => ''.e(__('center::courses.search_placeholder')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.search'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => ''.e(route('center.courses.index')).'','placeholder' => ''.e(__('center::courses.search_placeholder')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0d0ae8bef4b4e146c2af1b2494139103)): ?>
<?php $attributes = $__attributesOriginal0d0ae8bef4b4e146c2af1b2494139103; ?>
<?php unset($__attributesOriginal0d0ae8bef4b4e146c2af1b2494139103); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0d0ae8bef4b4e146c2af1b2494139103)): ?>
<?php $component = $__componentOriginal0d0ae8bef4b4e146c2af1b2494139103; ?>
<?php unset($__componentOriginal0d0ae8bef4b4e146c2af1b2494139103); ?>
<?php endif; ?>
                </div>
                <div class="col-md-3">
                    <?php if (isset($component)) { $__componentOriginal5964f3cfb7dbda62d89b754b9c63232a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5964f3cfb7dbda62d89b754b9c63232a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.filter','data' => ['name' => 'status','options' => ['published' => __('center::courses.status_published'), 'draft' => __('center::courses.status_draft'), 'archived' => __('center::courses.status_archived')],'label' => ''.e(__('center::courses.status_label')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.filter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['published' => __('center::courses.status_published'), 'draft' => __('center::courses.status_draft'), 'archived' => __('center::courses.status_archived')]),'label' => ''.e(__('center::courses.status_label')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5964f3cfb7dbda62d89b754b9c63232a)): ?>
<?php $attributes = $__attributesOriginal5964f3cfb7dbda62d89b754b9c63232a; ?>
<?php unset($__attributesOriginal5964f3cfb7dbda62d89b754b9c63232a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5964f3cfb7dbda62d89b754b9c63232a)): ?>
<?php $component = $__componentOriginal5964f3cfb7dbda62d89b754b9c63232a; ?>
<?php unset($__componentOriginal5964f3cfb7dbda62d89b754b9c63232a); ?>
<?php endif; ?>
                </div>
            </div>

            <!-- Courses Table -->
            <div class="table-responsive pb-5" style="min-height: 350px; overflow-x: auto;">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start"><?php echo e(__('center::courses.course_name')); ?></th>
                            <th class="border-0"><?php echo e(__('center::courses.instructor')); ?></th>
                            <th class="border-0"><?php echo e(__('center::courses.schedules')); ?></th>
                            <th class="border-0"><?php echo e(__('center::courses.price')); ?></th>
                            <th class="border-0"><?php echo e(__('center::courses.status')); ?></th>
                            <th class="border-0 rounded-end"><?php echo e(__('center::courses.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($course->image): ?>
                                            <img src="<?php echo e(Storage::url($course->image)); ?>" 
                                                 class="rounded-3 me-3" 
                                                 style="width: 48px; height: 48px; object-fit: cover;" 
                                                 alt="<?php echo e($course->title); ?>"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <?php endif; ?>
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center me-3" 
                                             style="width: 48px; height: 48px; <?php echo e($course->image ? 'display: none;' : ''); ?>">
                                            📚
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?php echo e($course->title); ?></div>
                                            <small class="text-muted"><?php echo e(Str::limit($course->description, 30)); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted"><?php echo e($course->instructor->name ?? __('center::courses.not_specified')); ?></td>
                                <td>
                                    <?php if($course->schedules->count() > 0): ?>
                                        <div class="<?php echo e(($loop->remaining < 2 && $courses->count() > 2) ? 'dropup' : 'dropdown'); ?>">
                                            <button class="btn btn-light btn-sm rounded-pill border shadow-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
                                                <i class="far fa-calendar-alt text-primary"></i>
                                                <span class="fw-bold"><?php echo e($course->schedules->count()); ?> <?php echo e(__('center::schedules.schedules_count')); ?></span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4" style="min-width: 250px;">
                                                <h6 class="dropdown-header text-primary fw-bold mb-2"><?php echo e(__('center::courses.schedules_details') ?? __('center::courses.schedules')); ?></h6>
                                                <div class="d-flex flex-column gap-2">
                                                    <?php $__currentLoopData = $course->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $days = [
                                                                0 => __('center::messages.sunday'), 1 => __('center::messages.monday'), 2 => __('center::messages.tuesday'), 
                                                                3 => __('center::messages.wednesday'), 4 => __('center::messages.thursday'), 5 => __('center::messages.friday'), 6 => __('center::messages.saturday')
                                                            ];
                                                            $dayName = $days[$schedule->day_of_week] ?? $schedule->day_of_week;
                                                            $start = \Carbon\Carbon::parse($schedule->start_time)->format('h:i A');
                                                            $end = \Carbon\Carbon::parse($schedule->end_time)->format('h:i A');
                                                        ?>
                                                        <div class="d-flex align-items-center bg-light rounded-3 p-2">
                                                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm text-primary me-2 flex-shrink-0" style="width: 32px; height: 32px;">
                                                                <i class="fas fa-calendar-day fa-sm"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold text-dark" style="font-size: 0.85rem;"><?php echo e($dayName); ?></div>
                                                                <div class="text-muted d-flex align-items-center gap-1" style="font-size: 0.7rem;">
                                                                    <span><?php echo e($start); ?> - <?php echo e($end); ?></span>
                                                                    <?php if($schedule->classroom): ?>
                                                                        <span class="vr mx-1"></span>
                                                                        <i class="fas fa-map-marker-alt text-danger"></i> <?php echo e($schedule->classroom->name); ?>

                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic"><?php echo e(__('center::courses.not_specified')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold text-success"><?php echo e(number_format($course->price, 2)); ?> <?php echo e(get_currency_symbol()); ?></td>
                                <td>
                                    <?php
                                        $badges = [
                                            'published' => 'success',
                                            'draft' => 'secondary',
                                            'archived' => 'warning'
                                        ];
                                        $labels = [
                                            'published' => __('center::courses.status_published'),
                                            'draft' => __('center::courses.status_draft'),
                                            'archived' => __('center::courses.status_archived')
                                        ];
                                    ?>
                                    <span class="badge bg-<?php echo e($badges[$course->status] ?? 'secondary'); ?> bg-opacity-10 text-<?php echo e($badges[$course->status] ?? 'secondary'); ?> rounded-pill px-3">
                                        <?php echo e($labels[$course->status] ?? $course->status); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1 justify-content-end">
                                        <button type="button" onclick="openEnrollModal('<?php echo e($course->id); ?>', '<?php echo e(addslashes($course->title)); ?>')" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold shadow-sm d-none d-xl-inline-block border-0">
                                            <i class="fas fa-user-plus me-1"></i> <?php echo e(__('center::courses.enroll_student')); ?>

                                        </button>
                                        <div class="<?php echo e(($loop->remaining < 2 && $courses->count() > 2) ? 'dropup' : 'dropdown'); ?>">
                                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                                <li><a class="dropdown-item" href="<?php echo e(route('center.courses.show', $course->id)); ?>"><i class="fas fa-eye me-2 text-muted"></i> <?php echo e(__('center::courses.view')); ?></a></li>
                                                <li><button type="button" class="dropdown-item fw-bold text-success" onclick="openEnrollModal('<?php echo e($course->id); ?>', '<?php echo e(addslashes($course->title)); ?>')"><i class="fas fa-user-plus me-2"></i> <?php echo e(__('center::courses.enroll_student')); ?></button></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="<?php echo e(route('center.courses.edit', $course->id)); ?>"><i class="fas fa-edit me-2 text-muted"></i> <?php echo e(__('center::courses.edit')); ?></a></li>
                                                <li><a class="dropdown-item" href="<?php echo e(route('center.curriculum.edit', $course->id)); ?>"><i class="fas fa-book-open me-2 text-muted"></i> <?php echo e(__('center::courses.content')); ?></a></li>
                                                <li><a class="dropdown-item" href="<?php echo e(route('center.schedules.create', ['course_id' => $course->id])); ?>"><i class="fas fa-calendar-plus me-2 text-info"></i> <?php echo e(__('center::students.add_new_schedule')); ?></a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="<?php echo e(route('center.courses.destroy', $course->id)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('center::courses.delete_confirm')); ?>');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash-alt me-2"></i> <?php echo e(__('center::courses.delete')); ?>

                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="mb-4">
                                        <div class="d-inline-flex p-4 rounded-circle mb-3" style="background: rgba(16, 185, 129, 0.05);">
                                            <i class="fas fa-book text-primary" style="font-size: 3rem; opacity: 0.5;"></i>
                                        </div>
                                    </div>
                                    <h5 class="text-muted fw-bold"><?php echo e(__('center::courses.no_courses')); ?></h5>
                                    <p class="text-muted small"><?php echo e(__('center::courses.no_courses_hint')); ?></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <?php echo e($courses->links('components.ui.pagination')); ?>

            </div>
        </div>
    </div>



    <?php $__env->startPush('modals'); ?>
    <!-- Unified Enroll Student Modal -->
    <div class="modal fade" id="unifiedEnrollModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-5 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0 pt-4 px-4 bg-light bg-opacity-50">
                    <h5 class="modal-title fw-bold fs-4"><?php echo e(__('center::courses.enroll_student')); ?>: <span id="dynamicCourseTitle" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-light bg-opacity-50 border-bottom">
                    <!-- Custom Tabs -->
                    <ul class="nav nav-pills bg-white p-1 rounded-pill shadow-sm" id="enrollTabs" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link active rounded-pill w-100 fw-bold" id="existing-tab" data-bs-toggle="pill" data-bs-target="#existing-panel" type="button" role="tab">
                                <i class="fas fa-search me-2"></i><?php echo e(__('center::courses.existing_student')); ?></button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link rounded-pill w-100 fw-bold" id="quick-tab" data-bs-toggle="pill" data-bs-target="#quick-panel" type="button" role="tab">
                                <i class="fas fa-user-plus me-2"></i><?php echo e(__('center::courses.quick_new_student')); ?></button>
                        </li>
                    </ul>
                </div>
                <div class="modal-body p-4 pt-3">
                    <div class="tab-content" id="enrollTabsContent">
                        <!-- Panel 1: Existing Student -->
                        <div class="tab-pane fade show active" id="existing-panel" role="tabpanel">
                            <form id="existingStudentForm" action="" method="POST" class="p-2">
                                <?php echo csrf_field(); ?>
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><?php echo e(__('center::courses.select_student_from_list')); ?></label>
                                    <select name="student_id" class="form-select border-2" id="unifiedStudentSelect" placeholder="<?php echo e(__('center::courses.search_student_placeholder')); ?>">
                                        <option value=""><?php echo e(__('center::courses.select_student_from_list')); ?></option>
                                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($student->id); ?>"><?php echo e($student->name); ?> (<?php echo e($student->phone); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i><?php echo e(__('center::courses.search_student_hint')); ?></div>
                                </div>
                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold fs-5 shadow-sm"><?php echo e(__('center::courses.complete_enrollment')); ?><i class="fas fa-check-circle ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Panel 2: Quick New Student -->
                        <div class="tab-pane fade" id="quick-panel" role="tabpanel">
                            <form id="quickNewStudentForm" action="" method="POST" class="p-2">
                                <?php echo csrf_field(); ?>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="name" class="form-control border-2 rounded-4 bg-light" id="qName" placeholder="<?php echo e(__('center::courses.full_name')); ?>" required>
                                            <label for="qName"><?php echo e(__('center::courses.full_name')); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="tel" name="phone" class="form-control border-2 rounded-4 bg-light" id="qPhone" placeholder="<?php echo e(__('center::courses.phone')); ?>" required>
                                            <label for="qPhone"><?php echo e(__('center::courses.phone')); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="tel" name="parent_phone" class="form-control border-2 rounded-4 bg-light" id="qParentPhone" placeholder="<?php echo e(__('center::courses.parent_phone')); ?>">
                                            <label for="qParentPhone"><?php echo e(__('center::courses.parent_phone')); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating mb-2">
                                            <select name="grade_id" class="form-select border-2 rounded-4 bg-light" id="qGrade" required>
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
                                <div class="alert flex-row d-flex align-items-center bg-info bg-opacity-10 text-info border-0 rounded-4 py-3 small my-3">
                                    <i class="fas fa-magic fa-lg me-3 ms-1"></i>
                                    <div><?php echo e(__('center::courses.quick_enroll_hint')); ?></div>
                                </div>
                                <div class="d-grid gap-2 mt-2">
                                    <button type="submit" id="quickEnrollSubmitBtn" class="btn btn-success rounded-pill py-3 fw-bold fs-5 shadow-sm"><?php echo e(__('center::courses.create_subscription_and_confirm')); ?><i class="fas fa-bolt ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('styles'); ?>
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
        <style>
            .ts-control { border-radius: 0.75rem !important; padding: 0.85rem 1rem !important; border-width: 2px !important; background-color: #f8f9fa !important; }
            .ts-dropdown { border-radius: 0.75rem !important; box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important; padding: 0.5rem; z-index: 2000 !important; background-color: #fff !important; border: 1px solid #dee2e6 !important; color: #1a1a1a !important; }
            .ts-dropdown .option { color: #1a1a1a !important; padding: 8px 12px !important; }
            .ts-dropdown .active { background-color: #f8f9fa !important; color: var(--bs-primary) !important; }
            .modal-content.rounded-5 { border-radius: 1.5rem !important; }
        </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            let unifiedTomSelect = null;
            let enrollModal = null;
            
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize TomSelect only once
                const selectEl = document.getElementById('unifiedStudentSelect');
                if (selectEl) {
                    unifiedTomSelect = new TomSelect('#unifiedStudentSelect', {
                        sortField: { field: "text", direction: "asc" },
                        maxOptions: 50,
                        <?php if(app()->isLocale('ar')): ?>
                        direction: 'rtl',
                        <?php endif; ?>
                        render: {
                            no_results: function(data, escape) {
                                return '<div class="no-results p-3 text-muted text-center"><?php echo e(__('center::courses.no_students_found')); ?></div>';
                            }
                        }
                    });
                }
                
                // Add loading state to the quick enroll form
                const quickForm = document.getElementById('quickNewStudentForm');
                if (quickForm) {
                    quickForm.addEventListener('submit', function(e) {
                        let btn = document.getElementById('quickEnrollSubmitBtn');
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> <?php echo e(__('center::schedules.saving')); ?>';
                    });
                }
                
                // Also add loading to existing form
                const existingForm = document.getElementById('existingStudentForm');
                if (existingForm) {
                    existingForm.addEventListener('submit', function(e) {
                        // Special check for TomSelect required validation
                        if (!document.getElementById('unifiedStudentSelect').value) {
                            e.preventDefault();
                            alert('<?php echo e(__('center::messages.blade_0342')); ?>');
                            return false;
                        }
                        
                        let btn = this.querySelector('button[type="submit"]');
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> <?php echo e(__('center::schedules.registering')); ?>';
                    });
                }
            });

            function openEnrollModal(courseId, courseTitle) {
                // Set the dynamic title
                document.getElementById('dynamicCourseTitle').innerText = courseTitle;
                
                // Update forms actions based on course ID
                let enrollUrl = `<?php echo e(route('center.courses.enroll', '__ID__')); ?>`.replace('__ID__', courseId);
                let quickEnrollUrl = `<?php echo e(route('center.courses.quick-enroll', '__ID__')); ?>`.replace('__ID__', courseId);
                
                document.getElementById('existingStudentForm').action = enrollUrl;
                document.getElementById('quickNewStudentForm').action = quickEnrollUrl;
                
                // Clear inputs if any previous data
                if (unifiedTomSelect) {
                    unifiedTomSelect.clear();
                }
                document.getElementById('qName').value = '';
                document.getElementById('qPhone').value = '';
                document.getElementById('qParentPhone').value = '';
                document.getElementById('qGrade').value = '';
                
                // Ensure Existing Tab is shown by default
                const tabEl = document.getElementById('existing-tab');
                if (tabEl) {
                    const tab = bootstrap.Tab.getOrCreateInstance(tabEl);
                    tab.show();
                }
                
                // Show modal using instance to avoid multiple backdrops
                const modalEl = document.getElementById('unifiedEnrollModal');
                enrollModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                enrollModal.show();
            }
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\courses\index.blade.php ENDPATH**/ ?>