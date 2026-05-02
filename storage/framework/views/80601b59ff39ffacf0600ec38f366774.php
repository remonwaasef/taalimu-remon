<?php $__env->startSection('content'); ?>
<div class="container-fluid p-0">
    <div class="mb-4">
        <a href="<?php echo e(route('center.questions.index')); ?>" class="text-muted text-decoration-none small">
            <i class="fas fa-arrow-right me-1"></i><?php echo e(__('center::questions.back_to_list')); ?></a>
        <h4 class="fw-bold mt-2"><?php echo e(__('center::questions.create_title')); ?></h4>
    </div>

    <form action="<?php echo e(route('center.questions.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold"><?php echo e(__('center::questions.question_text')); ?></label>
                            <textarea name="content" class="form-control rounded-4" rows="4" placeholder="<?php echo e(__('center::questions.content_placeholder')); ?>" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold"><?php echo e(__('center::questions.options')); ?></label>
                            <div id="optionsContainer">
                                <div class="option-row mb-3 d-flex gap-3 align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="correct_option" value="0" checked>
                                    </div>
                                    <input type="text" name="options[0][content]" class="form-control rounded-pill" placeholder="<?php echo e(__('center::questions.option_1')); ?>" required>
                                </div>
                                <div class="option-row mb-3 d-flex gap-3 align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="correct_option" value="1">
                                    </div>
                                    <input type="text" name="options[1][content]" class="form-control rounded-pill" placeholder="<?php echo e(__('center::questions.option_2')); ?>" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-2" onclick="addOption()">
                                <i class="fas fa-plus me-1"></i><?php echo e(__('center::questions.add_option')); ?></button>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold"><?php echo e(__('center::questions.explanation')); ?></label>
                            <textarea name="explanation" class="form-control rounded-4" rows="2" placeholder="<?php echo e(__('center::questions.explanation_placeholder')); ?>"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-bold mb-0"><?php echo e(__('center::questions.question_settings')); ?></h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold"><?php echo e(__('center::questions.category')); ?></label>
                            <select name="category_id" class="form-select rounded-pill">
                                <option value=""><?php echo e(__('center::questions.select_category')); ?></option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold"><?php echo e(__('center::questions.difficulty')); ?></label>
                            <select name="difficulty" class="form-select rounded-pill">
                                <option value="easy"><?php echo e(__('center::questions.easy')); ?></option>
                                <option value="medium" selected><?php echo e(__('center::questions.medium')); ?></option>
                                <option value="hard"><?php echo e(__('center::questions.hard')); ?></option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold"><?php echo e(__('center::questions.points')); ?></label>
                            <input type="number" name="points" class="form-control rounded-pill" value="1" min="1">
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold"><?php echo e(__('center::questions.type')); ?></label>
                            <select name="type" class="form-select rounded-pill" onchange="toggleType(this.value)">
                                <option value="mcq"><?php echo e(__('center::questions.mcq')); ?></option>
                                <option value="true_false"><?php echo e(__('center::questions.true_false')); ?></option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm">
                            <i class="fas fa-save me-2"></i><?php echo e(__('center::questions.save_question')); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let optionCount = 2;
    function addOption() {
        const container = document.getElementById('optionsContainer');
        const div = document.createElement('div');
        div.className = 'option-row mb-3 d-flex gap-3 align-items-center';
        div.innerHTML = `
            <div class="form-check">
                <input class="form-check-input" type="radio" name="correct_option" value="${optionCount}">
            </div>
            <input type="text" name="options[${optionCount}][content]" class="form-control rounded-pill" placeholder="<?php echo e(__('center::questions.option_placeholder')); ?>" required>
            <button type="button" class="btn btn-link text-danger p-0" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
        optionCount++;
    }

    function toggleType(type) {
        const container = document.getElementById('optionsContainer');
        const addBtn = document.querySelector('button[onclick="addOption()"]');
        
        if (type === 'true_false') {
            container.innerHTML = `
                <div class="option-row mb-3 d-flex gap-3 align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="correct_option" value="0" checked>
                    </div>
                    <input type="text" name="options[0][content]" class="form-control rounded-pill" value="<?php echo e(__('center::questions.true')); ?>" readonly>
                </div>
                <div class="option-row mb-3 d-flex gap-3 align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="correct_option" value="1">
                    </div>
                    <input type="text" name="options[1][content]" class="form-control rounded-pill" value="<?php echo e(__('center::questions.false')); ?>" readonly>
                </div>
            `;
            addBtn.style.display = 'none';
        } else {
            // Reset to MCQ
            addBtn.style.display = 'inline-block';
            // keep existing logic if needed or reset
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\questions\create.blade.php ENDPATH**/ ?>