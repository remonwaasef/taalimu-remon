<?php $__env->startSection('page-title', __('center::sales.title')); ?>
<?php $__env->startSection('page-subtitle', __('center::sales.subtitle')); ?>

<?php $__env->startSection('page-actions'); ?>
    <div id="resultCount" class="btn btn-glass cursor-default opacity-100">
        <i class="fas fa-user-graduate me-2"></i> <?php echo e($students->count()); ?> <?php echo e(__('center::sales.student_count')); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

    
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white rounded-start-pill px-3"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="searchInput" class="form-control bg-white rounded-end-pill py-2" placeholder="<?php echo e(__('center::sales.search_placeholder')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <select id="filterStatus" class="form-select bg-white rounded-pill py-2">
                        <option value="all"><?php echo e(__('center::sales.all_students')); ?></option>
                        <option value="unpaid"><?php echo e(__('center::sales.has_balance')); ?></option>
                        <option value="fully_paid"><?php echo e(__('center::sales.fully_paid')); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <?php if($students->isEmpty()): ?>
        <div class="stats-card p-5 text-center">
            <div class="mb-4">
                <i class="fas fa-users fs-1 text-muted opacity-25"></i>
            </div>
            <h5 class="text-muted"><?php echo e(__('center::sales.no_students_registered')); ?></h5>
        </div>
    <?php else: ?>
        <div id="noResults" class="stats-card p-5 text-center d-none">
            <div class="mb-4">
                <i class="fas fa-search fs-1 text-muted opacity-25"></i>
            </div>
            <h5 class="text-muted"><?php echo e(__('center::sales.no_search_results')); ?></h5>
        </div>

        <div class="stats-card p-0 overflow-hidden shadow-sm border-0" id="billingTableContainer">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="billingTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0"><?php echo e(__('center::sales.student_name')); ?></th>
                            <th class="border-0"><?php echo e(__('center::sales.total_due')); ?></th>
                            <th class="border-0"><?php echo e(__('center::sales.total_paid')); ?></th>
                            <th class="border-0"><?php echo e(__('center::sales.balance')); ?></th>
                            <th class="px-4 border-0 text-end"><?php echo e(__('center::sales.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $totalDue = $student->enrollments->sum(function($e) { return $e->course->price ?? 0; });
                                $totalPaid = $student->sales->sum('paid_amount');
                                $balance = $totalDue - $totalPaid;
                                $status = $balance > 0 ? 'unpaid' : 'paid';
                            ?>
                            <tr class="student-row" 
                                data-name="<?php echo e($student->name); ?>" 
                                data-phone="<?php echo e($student->phone); ?>" 
                                data-balance="<?php echo e($balance); ?>"
                                data-status="<?php echo e($status); ?>">
                                <td class="px-4 py-3">
                                    <div class="fw-bold text-dark"><?php echo e($student->name); ?></div>
                                    <small class="text-muted"><?php echo e($student->enrollments->pluck('course.title')->filter()->implode(', ')); ?></small>
                                </td>
                                <td><?php echo e(number_format($totalDue)); ?> <?php echo e(get_currency_symbol()); ?></td>
                                <td><?php echo e(number_format($totalPaid)); ?> <?php echo e(get_currency_symbol()); ?></td>
                                <td>
                                    <?php if($balance > 0): ?>
                                        <span class="text-danger fw-bold"><?php echo e(number_format($balance)); ?> <?php echo e(get_currency_symbol()); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><?php echo e(__('center::sales.paid')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <?php if($balance > 0): ?>
                                            <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" 
                                                data-bs-toggle="modal" data-bs-target="#collectModal" 
                                                data-id="<?php echo e($student->id); ?>" data-name="<?php echo e($student->name); ?>" data-balance="<?php echo e($balance); ?>">
                                                <i class="fas fa-hand-holding-usd me-1"></i> <?php echo e(__('center::sales.collect')); ?>

                                            </button>
                                            <?php
                                                // Customize message for Center context
                                                $reminderMsg = __('center::sales.billing_reminder', [
                                                    'name' => $student->name,
                                                    'balance' => $balance,
                                                    'currency' => get_currency_symbol(),
                                                    'center' => app('tenant')->name ?? 'المركز'
                                                ]);
                                                $phone = $student->phone;
                                                if (str_starts_with($phone, '0')) $phone = '2' . $phone;
                                                $whatsappUri = "https://api.whatsapp.com/send?phone=" . preg_replace('/[^0-9]/', '', $phone) . "&text=" . urlencode($reminderMsg);
                                            ?>
                                            <a href="<?php echo e($whatsappUri); ?>" target="_blank" class="btn btn-success btn-sm rounded-pill px-3">
                                                <i class="fab fa-whatsapp me-1"></i> <?php echo e(__('center::sales.whatsapp_reminder')); ?>

                                            </a>
                                        <?php else: ?>
                                            <span class="text-success small fw-medium"><i class="fas fa-check-circle me-1"></i> <?php echo e(__('center::sales.collected')); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('modals'); ?>
<!-- Collection Modal -->
<div class="modal fade" id="collectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><?php echo e(__('center::sales.record_payment')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('center.sales.mark-paid')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <input type="hidden" name="student_id" id="modal_student_id">
                    
                    <div class="mb-4 text-center">
                        <p class="text-muted mb-1"><?php echo e(__('center::sales.collect_from')); ?></p>
                        <h4 class="fw-bold mb-0" id="modal_student_name"></h4>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted"><?php echo e(__('center::sales.amount_received')); ?></label>
                        <div class="input-group">
                            <input type="number" name="amount" id="modal_amount" class="form-control bg-white border py-2" required>
                            <span class="input-group-text bg-white border"><?php echo e(get_currency_symbol()); ?></span>
                        </div>
                        <div class="form-text text-danger" id="modal_balance_hint"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted"><?php echo e(__('center::sales.notes')); ?></label>
                        <textarea name="notes" class="form-control bg-white border" rows="3" placeholder="<?php echo e(__('center::sales.notes_placeholder_alt')); ?>"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal"><?php echo e(__('center::sales.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><?php echo e(__('center::sales.confirm_collection')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopPush(); ?>

<style>
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .stats-card { background: #fff; border-radius: 1.25rem; }
    #billingTable thead th { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.025em; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const rows = document.querySelectorAll('.student-row');
    const noResults = document.getElementById('noResults');
    const resultCount = document.getElementById('resultCount');
    const tableContainer = document.getElementById('billingTableContainer');

    function applyFilters() {
        const query = searchInput.value.trim().toLowerCase();
        const filter = filterStatus.value;
        let visible = 0;

        rows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const phone = row.dataset.phone.toLowerCase();
            const status = row.dataset.status;

            const matchSearch = !query || name.includes(query) || phone.includes(query);
            const matchFilter = filter === 'all' || status === filter;

            if (matchSearch && matchFilter) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        if (resultCount) resultCount.textContent = visible + ' <?php echo e(__('center::sales.student_count')); ?>';
        if (noResults) noResults.classList.toggle('d-none', visible > 0);
        if (tableContainer) tableContainer.classList.toggle('d-none', visible === 0);
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (filterStatus) filterStatus.addEventListener('change', applyFilters);

    // Modal data handling
    const collectModal = document.getElementById('collectModal');
    if (collectModal) {
        collectModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const balance = button.getAttribute('data-balance');

            document.getElementById('modal_student_id').value = id;
            document.getElementById('modal_student_name').textContent = name;
            document.getElementById('modal_amount').value = balance;
            document.getElementById('modal_amount').max = balance;
            document.getElementById('modal_balance_hint').textContent = '<?php echo e(__('center::sales.current_balance_hint')); ?>' + new Intl.NumberFormat().format(balance);
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\sales\account.blade.php ENDPATH**/ ?>