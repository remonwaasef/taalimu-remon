<div
    x-data="{ open: false }"
    x-on:open-command-palette.window="open = true"
    x-on:keydown.window.cmd.k.prevent="open = true"
    x-on:keydown.window.ctrl.k.prevent="open = true"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-20 font-inter"
>
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
    ></div>

    <!-- Palette Container -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="mx-auto max-w-xl transform divide-y divide-brand-border dark:divide-slate-800 overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-start shadow-2xl ring-1 ring-black/5 transition-all relative border border-brand-border dark:border-slate-800"
    >
        <div class="relative">
            <i class="fas fa-search absolute top-3.5 start-4 text-slate-400 text-sm"></i>
            <input
                type="text"
                class="h-12 w-full border-0 bg-transparent ps-11 pe-4 text-slate-900 dark:text-slate-100 text-sm placeholder-slate-400 focus:ring-0 focus:outline-none"
                placeholder="ابحث عن أمر أو صفحة..."
                x-init="$watch('open', value => value && $nextTick(() => $el.focus()))"
            />
        </div>

        <div class="max-h-80 overflow-y-auto p-2 space-y-1 command-list">
            <div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">التنقل السريع</div>

            @if (app()->bound('tenant'))
                <a href="{{ tenant_route('instructor.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-brand-50 dark:hover:bg-slate-800 hover:text-brand-primary transition-colors">
                    <i class="fas fa-chart-pie w-4 text-center"></i>
                    <span>الانتقال للوحة التحكم</span>
                </a>

                <a href="{{ tenant_route('instructor.students.list') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-brand-50 dark:hover:bg-slate-800 hover:text-brand-primary transition-colors">
                    <i class="fas fa-user-graduate w-4 text-center"></i>
                    <span>دليل وقائمة الطلاب</span>
                </a>

                <a href="{{ tenant_route('instructor.groups.list') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-brand-50 dark:hover:bg-slate-800 hover:text-brand-primary transition-colors">
                    <i class="fas fa-users w-4 text-center"></i>
                    <span>إدارة المجموعات الدراسية</span>
                </a>

                <a href="{{ tenant_route('instructor.schedules.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-brand-50 dark:hover:bg-slate-800 hover:text-brand-primary transition-colors">
                    <i class="fas fa-calendar-alt w-4 text-center"></i>
                    <span>جدول المواعيد والحصص</span>
                </a>

                <div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider pt-3">إجراءات سريعة</div>

                <a href="{{ tenant_route('instructor.students.create') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-brand-50 dark:hover:bg-slate-800 hover:text-brand-primary transition-colors">
                    <i class="fas fa-user-plus w-4 text-center text-emerald-500"></i>
                    <span>إضافة طالب جديد</span>
                </a>

                <a href="{{ tenant_route('instructor.groups.create') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-brand-50 dark:hover:bg-slate-800 hover:text-brand-primary transition-colors">
                    <i class="fas fa-folder-plus w-4 text-center text-brand-primary"></i>
                    <span>إنشاء مجموعة جديدة</span>
                </a>
            @else
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-brand-50 dark:hover:bg-slate-800 hover:text-brand-primary transition-colors">
                    <i class="fas fa-chart-pie w-4 text-center"></i>
                    <span>الانتقال للوحة التحكم</span>
                </a>

                <div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider pt-3">إجراءات سريعة</div>

                <a href="{{ route('admin.tenants.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-brand-50 dark:hover:bg-slate-800 hover:text-brand-primary transition-colors">
                    <i class="fas fa-building w-4 text-center text-brand-primary"></i>
                    <span>إدارة المراكز</span>
                </a>
            @endif
        </div>

        <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 border-t border-brand-border dark:border-slate-800 text-[11px] text-slate-400 flex items-center justify-between">
            <span>اختصار البحث</span>
            <span class="font-mono font-medium">اضغط ESC للإغلاق</span>
        </div>
    </div>
</div>
