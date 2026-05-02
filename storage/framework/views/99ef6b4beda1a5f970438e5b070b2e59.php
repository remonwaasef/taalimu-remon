<?php $__env->startSection('content'); ?>
<div class="animate__animated animate__fadeIn">
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6 text-start">
            <h2 class="fw-bold text-dark mb-1">
                <i class="fas fa-edit me-2 text-primary"></i><?php echo e(__('center::messages.blade_0202')); ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('center.classrooms.index')); ?>" class="text-decoration-none"><?php echo e(__('center::messages.blade_0203')); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo e($classroom->name); ?></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?php echo e(route('center.classrooms.show', $classroom)); ?>" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-eye me-1"></i><?php echo e(__('center::messages.blade_0204')); ?></a>
            <a href="<?php echo e(route('center.classrooms.index')); ?>" class="btn btn-light rounded-pill px-4 shadow-sm border">
                <i class="fas fa-arrow-right me-1"></i><?php echo e(__('center::messages.blade_0205')); ?></a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form Section -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><?php echo e(__('center::messages.blade_0206')); ?></h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2"><?php echo e(__('center::classrooms.id_label')); ?><?php echo e($classroom->id); ?></span>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo e(route('center.classrooms.update', $classroom)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control rounded-3 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nameInput" placeholder="Name" value="<?php echo e(old('name', $classroom->name)); ?>" required>
                            <label for="nameInput"><?php echo e(__('center::messages.blade_0207')); ?></label>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" name="capacity" class="form-control rounded-3 <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="capacityInput" placeholder="Capacity" value="<?php echo e(old('capacity', $classroom->capacity)); ?>">
                                    <label for="capacityInput"><?php echo e(__('center::messages.blade_0208')); ?></label>
                                    <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-select rounded-3" id="typeSelect" name="type">
                                        <option value="hall" <?php echo e(old('type', $classroom->type) == 'hall' ? 'selected' : ''); ?>><?php echo e(__('center::messages.blade_0209')); ?></option>
                                        <option value="lab" <?php echo e(old('type', $classroom->type) == 'lab' ? 'selected' : ''); ?>><?php echo e(__('center::messages.blade_0210')); ?></option>
                                        <option value="virtual" <?php echo e(old('type', $classroom->type) == 'virtual' ? 'selected' : ''); ?>><?php echo e(__('center::messages.blade_0253')); ?></option>
                                    </select>
                                    <label for="typeSelect"><?php echo e(__('center::messages.blade_0211')); ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold"><?php echo e(__('center::messages.blade_0212')); ?></label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="color" class="form-control form-control-color" value="<?php echo e(old('color', $classroom->color ?? '#435ebe')); ?>" title="<?php echo e(__('center::messages.blade_0220')); ?>">
                                <small class="text-muted"><?php echo e(__('center::messages.blade_0213')); ?></small>
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm fw-bold">
                                <i class="fas fa-save me-1"></i><?php echo e(__('center::messages.blade_0214')); ?></button>
                            <a href="<?php echo e(route('center.classrooms.index')); ?>" class="btn btn-light px-4 py-2 rounded-pill border"><?php echo e(__('center::messages.blade_0215')); ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-5">
            <div class="d-flex flex-column gap-4">
                <!-- Assets Linked -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden border-start border-4 border-info">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-tools me-2 text-info"></i><?php echo e(__('center::messages.blade_0216')); ?></h6>
                            <a href="<?php echo e(route('center.assets.create', ['classroom_id' => $classroom->id])); ?>" class="btn btn-sm btn-info text-white rounded-pill px-3"><?php echo e(__('center::messages.blade_0096')); ?></a>
                        </div>
                        
                        <?php if($classroom->assets->count() > 0): ?>
                            <div class="list-group list-group-flush">
                                <?php $__currentLoopData = $classroom->assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <div class="fw-bold small"><?php echo e($asset->name); ?></div>
                                            <small class="text-muted"><?php echo e(__('center::assets.' . $asset->type)); ?></small>
                                        </div>
                                        <span class="badge bg-light text-dark border rounded-pill"><?php echo e(__('center::assets.' . $asset->status)); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <p class="text-muted small mb-0"><?php echo e(__('center::messages.blade_0217')); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="card border-0 bg-primary bg-opacity-10 rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary mb-2">💡 <?php echo e(__('center::classrooms.accent_color_hint_title')); ?></h6>
                        <p class="small text-dark mb-0 opacity-75"><?php echo e(__('center::classrooms.accent_color_hint_text')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control-color {
        width: 60px;
        height: 60px;
        padding: 5px;
        border-radius: 12px;
        border: 2px solid #dee2e6;
        cursor: pointer;
    }
    .card { transition: transform 0.2s ease; }
    .card:hover { transform: translateY(-5px); }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\classrooms\edit.blade.php ENDPATH**/ ?>