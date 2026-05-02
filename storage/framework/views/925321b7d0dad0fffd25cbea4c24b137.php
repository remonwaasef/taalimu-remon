

<?php $__env->startSection('content'); ?>
<div class="row align-items-center mb-5 animate__animated animate__fadeIn">
    <div class="col-md-6">
        <h2 class="fw-bold text-dark mb-2">الجدول الدراسي 📅</h2>
        <p class="text-muted mb-0">متابعة دقيقة لمواعيد محاضراتك خلال الأسبوع</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <span class="badge bg-white shadow-sm border rounded-pill px-4 py-2 text-primary fw-bold">
            <i class="far fa-clock me-2"></i> بتوقيت القاهرة
        </span>
    </div>
</div>

<div class="schedule-calendar-wrapper animate__animated animate__fadeInUp">
    <div class="schedule-grid">
        <?php
            // Reorder days to start from Saturday for a standard study week
            $orderedDayKeys = [6, 0, 1, 2, 3, 4, 5];
        ?>

        <?php $__currentLoopData = $orderedDayKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayNum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php 
                $dayName = $days[$dayNum]; 
                $isToday = now()->dayOfWeek == $dayNum;
            ?>
            <div class="schedule-column <?php echo e($isToday ? 'is-today' : ''); ?>">
                <div class="day-header">
                    <div class="day-name"><?php echo e($dayName); ?></div>
                    <?php if($isToday): ?>
                        <span class="today-badge">اليوم</span>
                    <?php endif; ?>
                </div>

                <div class="day-content">
                    <?php if(isset($schedules[$dayNum]) && count($schedules[$dayNum]) > 0): ?>
                        <?php $__currentLoopData = $schedules[$dayNum]->sortBy('start_time'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="session-card animate__animated animate__zoomIn">
                                <div class="session-time">
                                    <span class="time-main"><?php echo e(\Carbon\Carbon::parse($session->start_time)->format('g:i')); ?></span>
                                    <span class="time-ampm"><?php echo e(\Carbon\Carbon::parse($session->start_time)->format('A') == 'AM' ? 'صباحاً' : 'مساءً'); ?></span>
                                </div>
                                <div class="session-info">
                                    <div class="course-title" title="<?php echo e($session->course->title); ?>"><?php echo e($session->course->title); ?></div>
                                    <div class="instructor-name">
                                        <i class="fas fa-user-tie me-1"></i>
                                        <?php echo e($session->instructor->name ?? 'غير محدد'); ?>

                                    </div>
                                    <?php if($session->classroom): ?>
                                        <div class="classroom-badge">
                                            <i class="fas fa-map-marker-alt"></i> <?php echo e($session->classroom->name); ?>

                                        </div>
                                    <?php else: ?>
                                        <div class="classroom-badge online">
                                            <i class="fas fa-video"></i> أونلاين
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="session-status-dot"></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="empty-day">
                            <i class="fas fa-bed mb-2 opacity-25 d-block"></i>
                            <span>راحة</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<style>
    :root {
        --calendar-bg: #f8fafc;
        --card-bg: #ffffff;
        --accent-color: #4f46e5;
        --today-bg: rgba(79, 70, 229, 0.03);
    }

    .schedule-calendar-wrapper {
        background: var(--calendar-bg);
        border-radius: 24px;
        padding: 20px;
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);
        overflow-x: auto;
        direction: rtl;
    }

    .schedule-grid {
        display: flex;
        gap: 15px;
        min-width: 1000px; /* Ensures grid doesn't collapse on small screens */
    }

    .schedule-column {
        flex: 1;
        min-width: 140px;
        background: transparent;
        display: flex;
        flex-direction: column;
        gap: 15px;
        border-radius: 18px;
        padding: 5px;
        transition: all 0.3s ease;
    }

    .schedule-column.is-today {
        background: var(--today-bg);
        border: 1px dashed var(--accent-color);
    }

    .day-header {
        text-align: center;
        padding: 15px 5px;
        position: relative;
    }

    .day-name {
        font-weight: 800;
        color: #1e293b;
        font-size: 1.1rem;
    }

    .today-badge {
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--accent-color);
        color: white;
        font-size: 0.65rem;
        padding: 2px 10px;
        border-radius: 50px;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
    }

    .day-content {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .session-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: default;
    }

    .session-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -10px rgba(0,0,0,0.1);
        border-color: var(--accent-color);
    }

    .session-time {
        margin-bottom: 8px;
    }

    .time-main {
        font-weight: 800;
        font-size: 1.2rem;
        color: var(--accent-color);
        display: block;
        line-height: 1;
    }

    .time-ampm {
        font-size: 0.7rem;
        color: #64748b;
        font-weight: 600;
    }

    .course-title {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.85rem;
        margin-bottom: 5px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .instructor-name {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 8px;
    }

    .classroom-badge {
        display: inline-block;
        font-size: 0.7rem;
        padding: 3px 8px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 6px;
        font-weight: 600;
    }

    .classroom-badge.online {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .session-status-dot {
        position: absolute;
        top: 15px;
        left: 15px;
        width: 8px;
        height: 8px;
        background: #e2e8f0;
        border-radius: 50%;
    }

    .session-card:hover .session-status-dot {
        background: var(--accent-color);
        box-shadow: 0 0 10px var(--accent-color);
    }

    .empty-day {
        text-align: center;
        padding: 30px 10px;
        color: #94a3b8;
        font-size: 0.8rem;
        background: rgba(0,0,0,0.02);
        border-radius: 12px;
        border: 1px dashed rgba(0,0,0,0.05);
    }

    /* Custom Scrollbar */
    .schedule-calendar-wrapper::-webkit-scrollbar {
        height: 8px;
    }
    .schedule-calendar-wrapper::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .schedule-calendar-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .schedule-calendar-wrapper::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    @media (max-width: 768px) {
        .schedule-grid {
            min-width: 900px;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('campus::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Campus\resources\views\schedule.blade.php ENDPATH**/ ?>