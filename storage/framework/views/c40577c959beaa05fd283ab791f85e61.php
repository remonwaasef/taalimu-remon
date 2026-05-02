<?php $__env->startSection('content'); ?>
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-1"><?php echo e(isset($asset) ? __('center::assets.edit') : __('center::assets.add_new')); ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('center.assets.index')); ?>" class="text-muted text-decoration-none"><?php echo e(__('center::assets.title')); ?></a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page"><?php echo e(isset($asset) ? __('center::messages.blade_0101') : __('center::messages.blade_0102')); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="<?php echo e(isset($asset) ? route('center.assets.update', $asset) : route('center.assets.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php if(isset($asset)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

                <div class="row g-3 mr-1">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control rounded-3" id="name" placeholder="Name" value="<?php echo e(old('name', $asset->name ?? '')); ?>" required>
                            <label for="name"><?php echo e(__('center::assets.name')); ?> *</label>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="code" class="form-control rounded-3" id="code" placeholder="Code" value="<?php echo e(old('code', $asset->code ?? '')); ?>">
                            <label for="code"><?php echo e(__('center::assets.code')); ?></label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-select rounded-3" id="type" name="type" required>
                                <option value="equipment" <?php echo e((old('type', $asset->type ?? '') == 'equipment') ? 'selected' : ''); ?>><?php echo e(__('center::assets.equipment')); ?></option>
                                <option value="furniture" <?php echo e((old('type', $asset->type ?? '') == 'furniture') ? 'selected' : ''); ?>><?php echo e(__('center::assets.furniture')); ?></option>
                                <option value="electronics" <?php echo e((old('type', $asset->type ?? '') == 'electronics') ? 'selected' : ''); ?>><?php echo e(__('center::assets.electronics')); ?></option>
                                <option value="other" <?php echo e((old('type', $asset->type ?? '') == 'other') ? 'selected' : ''); ?>><?php echo e(__('center::assets.other')); ?></option>
                            </select>
                            <label for="type"><?php echo e(__('center::assets.type')); ?></label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-select rounded-3" id="status" name="status" required>
                                <option value="active" <?php echo e((old('status', $asset->status ?? '') == 'active') ? 'selected' : ''); ?>><?php echo e(__('center::assets.active')); ?></option>
                                <option value="maintenance" <?php echo e((old('status', $asset->status ?? '') == 'maintenance') ? 'selected' : ''); ?>><?php echo e(__('center::assets.maintenance')); ?></option>
                                <option value="broken" <?php echo e((old('status', $asset->status ?? '') == 'broken') ? 'selected' : ''); ?>><?php echo e(__('center::assets.broken')); ?></option>
                                <option value="lost" <?php echo e((old('status', $asset->status ?? '') == 'lost') ? 'selected' : ''); ?>><?php echo e(__('center::assets.lost')); ?></option>
                            </select>
                            <label for="status"><?php echo e(__('center::assets.status')); ?></label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-select rounded-3" id="classroom_id" name="classroom_id">
                                <option value=""><?php echo e(__('center::assets.none')); ?></option>
                                <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($classroom->id); ?>" <?php echo e((old('classroom_id', $asset->classroom_id ?? request('classroom_id')) == $classroom->id) ? 'selected' : ''); ?>><?php echo e($classroom->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label for="classroom_id"><?php echo e(__('center::assets.classroom')); ?></label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="date" name="purchase_date" class="form-control rounded-3" id="purchase_date" value="<?php echo e(old('purchase_date', isset($asset) && $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '')); ?>">
                            <label for="purchase_date"><?php echo e(__('center::assets.purchase_date')); ?></label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="number" step="0.01" name="cost" class="form-control rounded-3" id="cost" placeholder="Cost" value="<?php echo e(old('cost', $asset->cost ?? '')); ?>">
                            <label for="cost"><?php echo e(__('center::assets.cost')); ?></label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating mb-4">
                            <textarea name="notes" class="form-control rounded-3" id="notes" style="height: 100px"><?php echo e(old('notes', $asset->notes ?? '')); ?></textarea>
                            <label for="notes"><?php echo e(__('center::assets.notes')); ?></label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm fw-bold"><?php echo e(__('center::messages.blade_0099')); ?></button>
                    <a href="<?php echo e(route('center.assets.index')); ?>" class="btn btn-light px-4 rounded-pill border"><?php echo e(__('center::messages.blade_0100')); ?></a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\assets\create.blade.php ENDPATH**/ ?>