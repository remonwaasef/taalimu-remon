<?php $__env->startPush('styles'); ?>
<style>
    .bg-info-soft { background-color: rgba(23, 162, 184, 0.1) !important; border-color: rgba(23, 162, 184, 0.2) !important; }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1) !important; border-color: rgba(220, 53, 69, 0.2) !important; }
    .active-reminder-info { background-color: rgba(0, 180, 216, 0.15) !important; border-color: #00b4d8 !important; }
    .active-reminder-danger { background-color: rgba(231, 76, 60, 0.15) !important; border-color: #e74c3c !important; }
    .btn-collapse-chevron[aria-expanded="true"] .fa-chevron-down {
        transform: rotate(180deg);
    }
    .btn-collapse-chevron .fa-chevron-down {
        transition: transform 0.2s ease-in-out;
    }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('title', __('center::settings.title')); ?>

<?php $__env->startSection('page-title', __('center::settings.title')); ?>

<?php $__env->startSection('panel-content'); ?>
    <?php $activeTab = request('tab', 'general'); ?>
    
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 p-0">
                    <ul class="nav nav-tabs nav-fill" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e($activeTab == 'general' ? 'active' : ''); ?> py-3 fw-bold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-selected="<?php echo e($activeTab == 'general' ? 'true' : 'false'); ?>">
                                <i class="fas fa-info-circle me-2"></i> <?php echo e(__('center::settings.tabs.general')); ?>

                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e($activeTab == 'academic' ? 'active' : ''); ?> py-3 fw-bold" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button" role="tab" aria-selected="<?php echo e($activeTab == 'academic' ? 'true' : 'false'); ?>">
                                <i class="fas fa-graduation-cap me-2"></i> <?php echo e(__('center::settings.tabs.academic')); ?>

                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e($activeTab == 'financial' ? 'active' : ''); ?> py-3 fw-bold" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial" type="button" role="tab" aria-selected="<?php echo e($activeTab == 'financial' ? 'true' : 'false'); ?>">
                                <i class="fas fa-coins me-2"></i> <?php echo e(__('center::settings.tabs.financial')); ?>

                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e($activeTab == 'appearance' ? 'active' : ''); ?> py-3 fw-bold" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab" aria-selected="<?php echo e($activeTab == 'appearance' ? 'true' : 'false'); ?>">
                                <i class="fas fa-paint-brush me-2"></i> <?php echo e(__('center::settings.tabs.appearance')); ?>

                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e($activeTab == 'whatsapp' ? 'active' : ''); ?> py-3 fw-bold" id="whatsapp-tab" data-bs-toggle="tab" data-bs-target="#whatsapp" type="button" role="tab" aria-selected="<?php echo e($activeTab == 'whatsapp' ? 'true' : 'false'); ?>">
                                <i class="fab fa-whatsapp me-2 text-success"></i> <?php echo e(__('center::settings.tabs.whatsapp')); ?>

                            </button>
                        </li>
                        
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e(in_array($activeTab, ['reminders', 'email_templates']) ? 'active' : ''); ?> py-3 fw-bold" id="reminders-tab" data-bs-toggle="tab" data-bs-target="#reminders" type="button" role="tab" aria-selected="<?php echo e(in_array($activeTab, ['reminders', 'email_templates']) ? 'true' : 'false'); ?>">
                                <i class="fas fa-bullhorn me-2 text-warning"></i> <?php echo e(__('center::settings.tabs.email_templates')); ?> / <?php echo e(__('center::settings.tabs.reminders')); ?>

                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e($activeTab == 'privacy' ? 'active' : ''); ?> py-3 fw-bold" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy" type="button" role="tab" aria-selected="<?php echo e($activeTab == 'privacy' ? 'true' : 'false'); ?>">
                                <i class="fas fa-user-shield me-2 text-danger"></i> <?php echo e(__('center::settings.tabs.privacy')); ?>

                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 fw-bold">
                            <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4 fw-bold">
                            <i class="fas fa-exclamation-triangle me-2"></i> <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                            <ul class="mb-0 small fw-bold">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="tab-content" id="settingsTabsContent">
                        <!-- General Settings -->
                        <?php echo $__env->make('center::settings.partials._tab-general', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('center::settings.partials._tab-reminders', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('center::settings.partials._tab-academic', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('center::settings.partials._tab-financial', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('center::settings.partials._tab-appearance', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('center::settings.partials._tab-whatsapp', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('center::settings.partials._tab-privacy', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .nav-tabs .nav-link {
        color: #64748b;
        background: #f8fafc;
        border-bottom: 2px solid transparent !important;
        transition: all 0.3s;
    }
    .nav-tabs .nav-link:hover {
        background: #f1f5f9;
        color: var(--primary-color);
    }
    .nav-tabs .nav-link.active {
        background: white !important;
        color: var(--primary-color) !important;
        border-bottom: 3px solid var(--primary-color) !important;
    }
    .form-control:focus {
        background: white !important;
        box-shadow: 0 0 0 4px rgba(58, 12, 163, 0.1);
    }
    .custom-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
    }
    .custom-switch .form-check-input:checked {
        background-color: #22c55e;
        border-color: #22c55e;
    }
    .x-small { font-size: 0.75rem; }
    .preset-card:hover {
        border-color: var(--primary-color, #3A0CA3) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(58, 12, 163, 0.15);
    }
    .preset-card.active-preset {
        border-color: var(--primary-color, #3A0CA3) !important;
        border-width: 2px;
        background: #f0f0ff !important;
    }
    .bg-primary-soft { background-color: rgba(58, 12, 163, 0.05) !important; }
    .border-dashed { border-style: dashed !important; }
    .italic { font-style: italic; }
    .var-btn:hover {
        background-color: var(--primary-color) !important;
        color: white !important;
        border-color: var(--primary-color) !important;
    }
    #preview-body span.text-primary {
        background: rgba(58, 12, 163, 0.1);
        padding: 0 4px;
        border-radius: 4px;
    }
    .accordion-button:not(.collapsed) {
        background-color: #f8f9ff !important;
        color: var(--primary-color) !important;
        box-shadow: none;
    }
    .accordion-button:focus { box-shadow: none; }
    .accordion-item { border-color: #e2e8f0 !important; }
    
    /* Academic Stage Collapse Chevron Animation */
    .btn-collapse-chevron i {
        transition: transform 0.3s ease;
        display: inline-block;
    }
    .btn-collapse-chevron.collapsed i {
        transform: rotate(-90deg);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const varBtns = document.querySelectorAll('.var-btn');
    varBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const textarea = document.getElementById(targetId);
            if (!textarea) return;
            const variable = this.dataset.var;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            textarea.value = text.substring(0, start) + variable + text.substring(end);
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + variable.length;
            
            if (typeof updatePreview === 'function') {
                updatePreview();
            }
        });
    });

    const presetCards = document.querySelectorAll('.preset-card');
    const presets = <?php echo json_encode(__('center::settings.email_templates.presets_data')); ?>;
    const defaultPresets = <?php echo json_encode(config('email_templates.presets', [])); ?>;
    
    presetCards.forEach(card => {
        card.addEventListener('click', function() {
            const presetKey = this.dataset.preset;
            let preset = (presets && presets[presetKey]) ? presets[presetKey] : (defaultPresets[presetKey] || null);
            
            if (preset) {
                const sSubj = document.getElementById('student_subject');
                const sBody = document.getElementById('student_body');
                const gSubj = document.getElementById('guardian_subject');
                const gBody = document.getElementById('guardian_body');

                if (sSubj) sSubj.value = preset.student_subject || '';
                if (sBody) sBody.value = preset.student_body || '';
                if (gSubj) gSubj.value = preset.guardian_subject || '';
                if (gBody) gBody.value = preset.guardian_body || '';
                
                presetCards.forEach(c => c.classList.remove('active-preset'));
                this.classList.add('active-preset');
                
                if (typeof updatePreview === 'function') {
                    updatePreview();
                }
            }
        });
    });

    const resetBtns = document.querySelectorAll('.reset-email-btn');
    resetBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const subjectInput = document.getElementById(this.dataset.subjectId);
            const bodyInput = document.getElementById(this.dataset.bodyId);
            if (subjectInput) subjectInput.value = this.dataset.defaultSubject;
            if (bodyInput) bodyInput.value = this.dataset.defaultBody;
            
            if (typeof updatePreview === 'function') {
                updatePreview();
            }
        });
    });

    if (typeof updatePreview === 'function') {
        updatePreview();
    }
});

// Reminder Scheduling Tab JS
function toggleReminderStyle(checkbox, elementId, colorClass) {
    const el = document.getElementById(elementId);
    if (!el) return;
    const classes = colorClass.split(' ');
    if (checkbox.checked) {
        el.classList.remove('bg-light');
        el.classList.add(...classes);
    } else {
        el.classList.add('bg-light');
        el.classList.remove(...classes);
    }
}

function toggleOverdueSettings(checkbox) {
    const panel = document.getElementById('overdueSettingsPanel');
    if (!panel) return;
    if (checkbox.checked) {
        panel.classList.remove('d-none');
    } else {
        panel.classList.add('d-none');
    }
}

function fillPreset(textareaId, text) {
    const textarea = document.getElementById(textareaId);
    if (!textarea) return;
    textarea.value = text;
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

function insertVariable(badge, textareaId) {
    const textarea = document.getElementById(textareaId);
    if (!textarea) return;
    const variable = badge.dataset.var || badge.textContent.trim();
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    textarea.value = text.substring(0, start) + variable + text.substring(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + variable.length;
}

function confirmTemplate() {
    const form = document.getElementById('applyTemplateForm');
    const select = form.querySelector('select[name="template_key"]');
    if (!select || !select.value) {
        alert("<?php echo e(__('center::settings.academic.select_template_first')); ?>");
        return;
    }
    
    if (confirm("<?php echo e(__('center::settings.academic.confirm_template')); ?>")) {
        form.submit();
    }
}

// Dynamic Academic Structure Management JS
let stageCounter = <?php echo e(count($stages ?? [])); ?>;

function addStage() {
    const container = document.getElementById('stages-container');
    const index = stageCounter++;
    
    const html = `
        <div class="stage-card card border bg-light mb-3 rounded-3 overflow-hidden shadow-none" data-index="${index}">
            <div class="card-header bg-white d-flex align-items-center gap-3 py-2 border-bottom">
                <input type="hidden" name="stages[${index}][id]" value="">
                
                <!-- Collapse trigger chevron -->
                <button type="button" class="btn btn-sm btn-link text-muted p-0 me-1 btn-collapse-chevron" data-bs-toggle="collapse" data-bs-target="#stage-collapse-${index}" aria-expanded="true" aria-controls="stage-collapse-${index}" style="text-decoration: none;">
                    <i class="fas fa-chevron-down"></i>
                </button>

                <input type="text" name="stages[${index}][name]" class="form-control form-control-sm fw-bold border-0 bg-light" value="" placeholder="<?php echo e(__('center::settings.academic.stage_name_placeholder')); ?>" required>
                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-light text-primary" onclick="addGrade(${index})" title="<?php echo e(__('center::settings.academic.add_grade')); ?>">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeStage(this)" title="<?php echo e(__('center::settings.academic.remove_stage')); ?>">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
            <div class="collapse show" id="stage-collapse-${index}">
                <div class="card-body p-3">
                    <div class="grades-container d-flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function removeStage(btn, id = null) {
    if (confirm("هل أنت متأكد من حذف هذه المرحلة وجميع صفوفها؟")) {
        if (id) {
            const deletionContainer = document.getElementById('deletion-inputs');
            deletionContainer.insertAdjacentHTML('beforeend', `<input type="hidden" name="deleted_stages[]" value="${id}">`);
        }
        btn.closest('.stage-card').remove();
    }
}

function addGrade(sIndex) {
    const stageCard = document.querySelector(`.stage-card[data-index="${sIndex}"]`);
    const container = stageCard.querySelector('.grades-container');
    const gIndex = Date.now() + Math.floor(Math.random() * 1000);
    
    // Auto expand the stage if collapsed so the user sees the new grade added
    const collapseEl = document.getElementById(`stage-collapse-${sIndex}`);
    if (collapseEl && !collapseEl.classList.contains('show')) {
        const bsCollapse = new bootstrap.Collapse(collapseEl, { show: true });
        bsCollapse.show();
    }
    
    const html = `
        <div class="grade-item d-flex align-items-center bg-white border rounded-pill px-3 py-1 shadow-sm">
            <input type="hidden" name="stages[${sIndex}][grades][${gIndex}][id]" value="">
            <input type="text" name="stages[${sIndex}][grades][${gIndex}][name]" class="form-control form-control-sm border-0 p-0 text-center" style="width: 100px; font-size: 0.85rem;" value="" placeholder="<?php echo e(__('center::settings.academic.grade_name_placeholder')); ?>" required>
            <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" onclick="removeGrade(this)">
                <i class="fas fa-times-circle"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function removeGrade(btn, id = null) {
    if (id) {
        const deletionContainer = document.getElementById('deletion-inputs');
        deletionContainer.insertAdjacentHTML('beforeend', `<input type="hidden" name="deleted_grades[]" value="${id}">`);
    }
    btn.closest('.grade-item').remove();
}

// Attendance / Late Rules Management JS
let lateLevelCounter = <?php echo e(count($lateLevels ?? [])); ?>;

function addLateLevel() {
    const container = document.getElementById('late-levels-container');
    const index = lateLevelCounter++;
    
    // Remove the defaults alert if it exists
    const alert = document.getElementById('system-defaults-alert');
    if (alert) alert.remove();
    
    const html = `
        <div class="late-level-item d-flex align-items-center gap-2 mb-2 bg-light p-2 rounded-3">
            <input type="number" name="settings[academic][late_levels][${index}][minutes]" class="form-control form-control-sm" style="width: 100px;" value="" placeholder="<?php echo e(__('center::settings.academic.threshold_minutes')); ?>" required>
            <input type="text" name="settings[academic][late_levels][${index}][label]" class="form-control form-control-sm" value="" placeholder="<?php echo e(__('center::settings.academic.level_label')); ?>" required>
            <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeLateLevel(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function removeLateLevel(btn) {
    btn.closest('.late-level-item').remove();
}

function restoreLateDefaults() {
    if (confirm("هل أنت متأكد من إعادة ضبط مستويات التأخير إلى الافتراضية؟")) {
        const container = document.getElementById('late-levels-container');
        container.innerHTML = `
            <div class="alert alert-info py-2 px-3 small border-0 mb-3 bg-opacity-10 text-info" id="system-defaults-alert">
                <i class="fas fa-info-circle me-2"></i><?php echo e(__('center::settings.academic_system_defaults_alert')); ?>

            </div>
        `;
        
        const defaults = [
            { minutes: 15, label: "تأخير بسيط" },
            { minutes: 30, label: "تأخير نصف ساعة" },
            { minutes: 60, label: "تأخير كبير (ساعة)" }
        ];
        
        defaults.forEach((level, index) => {
            const html = `
                <div class="late-level-item d-flex align-items-center gap-2 mb-2 bg-light p-2 rounded-3">
                    <input type="number" name="settings[academic][late_levels][${index}][minutes]" class="form-control form-control-sm" style="width: 100px;" value="${level.minutes}" placeholder="<?php echo e(__('center::settings.academic.threshold_minutes')); ?>" required>
                    <input type="text" name="settings[academic][late_levels][${index}][label]" class="form-control form-control-sm" value="${level.label}" placeholder="<?php echo e(__('center::settings.academic.level_label')); ?>" required>
                    <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeLateLevel(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });
        lateLevelCounter = defaults.length;
    }
}
</script>

<?php echo $__env->make('center::layouts.app-next', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\taalimu.com\taalimu.com\Modules/Center\resources/views/settings/index.blade.php ENDPATH**/ ?>