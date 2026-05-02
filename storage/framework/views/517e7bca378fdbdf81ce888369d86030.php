<?php $__env->startSection('content'); ?>
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-1"><?php echo e(isset($classroom) ? __('center::messages.blade_0194') : __('center::messages.blade_0195')); ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('center.classrooms.index')); ?>" class="text-muted text-decoration-none"><?php echo e(__('center::messages.blade_0164')); ?></a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page"><?php echo e(isset($classroom) ? __('center::messages.blade_0196') : __('center::messages.blade_0197')); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-4">
        <!-- Input Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                <div class="card-body p-4">
                    <form action="<?php echo e(isset($classroom) ? route('center.classrooms.update', $classroom) : route('center.classrooms.store')); ?>" method="POST" id="classroomForm">
                        <?php echo csrf_field(); ?>
                        <?php if(isset($classroom)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

                        <h5 class="fw-bold text-dark mb-4"><i class="fas fa-info-circle me-2 text-primary"></i><?php echo e(__('center::messages.blade_0165')); ?></h5>

                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control rounded-3" id="nameInput" placeholder="<?php echo e(__('center::messages.blade_0187')); ?>" value="<?php echo e(old('name', $classroom->name ?? '')); ?>">
                            <label for="nameInput"><?php echo e(__('center::messages.blade_0166')); ?></label>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" name="capacity" class="form-control rounded-3" id="capacityInput" placeholder="<?php echo e(__('center::messages.blade_0188')); ?>" value="<?php echo e(old('capacity', $classroom->capacity ?? '')); ?>">
                                    <label for="capacityInput"><?php echo e(__('center::messages.blade_0167')); ?></label>
                                </div>
                                <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-select rounded-3" id="typeSelect" name="type">
                                        <option value="hall" <?php echo e((old('type', $classroom->type ?? '') == 'hall') ? 'selected' : ''); ?>><?php echo e(__('center::messages.blade_0168')); ?></option>
                                        <option value="lab" <?php echo e((old('type', $classroom->type ?? '') == 'lab') ? 'selected' : ''); ?>><?php echo e(__('center::messages.blade_0169')); ?></option>
                                        <option value="virtual" <?php echo e((old('type', $classroom->type ?? '') == 'virtual') ? 'selected' : ''); ?>>قاعة افتراضية (Zoom/Meet)</option>
                                    </select>
                                    <label for="typeSelect"><?php echo e(__('center::messages.blade_0170')); ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><?php echo e(__('center::messages.blade_0171')); ?></label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="color" class="form-control form-control-color" value="<?php echo e(old('color', $classroom->color ?? '#435ebe')); ?>" title="<?php echo e(__('center::messages.blade_0189')); ?>">
                                <small class="text-muted"><?php echo e(__('center::messages.blade_0172')); ?></small>
                            </div>
                        </div>

                        <!-- Quick Asset Selection -->
                        <h5 class="fw-bold text-dark mt-4 mb-3"><i class="fas fa-tools me-2 text-primary"></i><?php echo e(__('center::messages.blade_0173')); ?></h5>
                        <p class="small text-muted mb-3"><?php echo e(__('center::messages.blade_0174')); ?></p>
                        <div class="row g-2 mb-4">
                            <div class="col-md-4 col-6">
                                <div class="form-check form-switch p-2 border rounded-3 bg-white shadow-sm">
                                    <input class="form-check-input ms-0" type="checkbox" name="quick_assets[]" value="شاشة عرض/التلفزيون" id="asset_tv">
                                    <label class="form-check-label ms-2" for="asset_tv">شاشة عرض/TV</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-check form-switch p-2 border rounded-3 bg-white shadow-sm">
                                    <input class="form-check-input ms-0" type="checkbox" name="quick_assets[]" value="جهاز عرض (Projector)" id="asset_projector">
                                    <label class="form-check-label ms-2" for="asset_projector">Projector</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-check form-switch p-2 border rounded-3 bg-white shadow-sm">
                                    <input class="form-check-input ms-0" type="checkbox" name="quick_assets[]" value="<?php echo e(__('center::messages.blade_0190')); ?>" id="asset_ac">
                                    <label class="form-check-label ms-2" for="asset_ac"><?php echo e(__('center::messages.blade_0175')); ?></label>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-check form-switch p-2 border rounded-3 bg-white shadow-sm">
                                    <input class="form-check-input ms-0" type="checkbox" name="quick_assets[]" value="<?php echo e(__('center::messages.blade_0191')); ?>" id="asset_whiteboard">
                                    <label class="form-check-label ms-2" for="asset_whiteboard"><?php echo e(__('center::messages.blade_0176')); ?></label>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-check form-switch p-2 border rounded-3 bg-white shadow-sm">
                                    <input class="form-check-input ms-0" type="checkbox" name="quick_assets[]" value="<?php echo e(__('center::messages.blade_0192')); ?>" id="asset_camera">
                                    <label class="form-check-label ms-2" for="asset_camera"><?php echo e(__('center::messages.blade_0177')); ?></label>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-check form-switch p-2 border rounded-3 bg-white shadow-sm">
                                    <input class="form-check-input ms-0" type="checkbox" name="quick_assets[]" value="<?php echo e(__('center::messages.blade_0193')); ?>" id="asset_sound">
                                    <label class="form-check-label ms-2" for="asset_sound"><?php echo e(__('center::messages.blade_0178')); ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm fw-bold">
                                <i class="fas fa-save me-2"></i> <?php echo e(isset($classroom) ? __('center::messages.blade_0198') : __('center::messages.blade_0199')); ?>

                            </button>
                            <a href="<?php echo e(route('center.classrooms.index')); ?>" class="btn btn-light px-4 rounded-pill border"><?php echo e(__('center::messages.blade_0179')); ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Live Preview Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white position-relative overflow-hidden">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-4 opacity-75"><?php echo e(__('center::messages.blade_0180')); ?></h5>
                    
                    <div class="d-flex justify-content-center mb-4">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center shadow-lg animate__animated animate__pulse animate__infinite" style="width: 120px; height: 120px;">
                            <i class="fas fa-chalkboard-teacher fa-4x text-white" id="previewIcon"></i>
                        </div>
                    </div>

                    <h3 class="fw-bold mb-2" id="previewName"><?php echo e($classroom->name ?? __('center::messages.blade_0200')); ?></h3>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-users me-1"></i><?php echo e(__('center::messages.blade_0181')); ?><span id="previewCapacity"><?php echo e($classroom->capacity ?? '--'); ?></span><?php echo e(__('center::messages.blade_0182')); ?></p>
                </div>
                <!-- Decoration -->
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fas fa-shapes fa-5x"></i>
                </div>
            </div>
        
            <!-- Tips Card -->
            <div class="card border-0 shadow-sm rounded-4 mt-3 bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-lightbulb text-warning me-2"></i><?php echo e(__('center::messages.blade_0183')); ?></h6>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i><?php echo e(__('center::messages.blade_0184')); ?></li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i><?php echo e(__('center::messages.blade_0185')); ?></li>
                        <li><i class="fas fa-hammer text-secondary me-2"></i><?php echo e(__('center::messages.blade_0186')); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('nameInput');
            const capacityInput = document.getElementById('capacityInput');
            const previewName = document.getElementById('previewName');
            const previewCapacity = document.getElementById('previewCapacity');

            nameInput.addEventListener('input', function() {
                previewName.textContent = this.value || __('center::messages.blade_0201');
            });

            capacityInput.addEventListener('input', function() {
                previewCapacity.textContent = this.value || '--';
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\classrooms\create.blade.php ENDPATH**/ ?>