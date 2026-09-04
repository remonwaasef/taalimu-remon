<x-ui.modal id="quick-student-modal" title="تسجيل طالب جديد" size="md">
    <form id="quick-student-form" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                {{ __('center::students.form.full_name') }} <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="name" required placeholder="مثال: يوسف أحمد"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    {{ __('center::students.form.phone_number') }} <span class="text-rose-500">*</span>
                </label>
                <input type="tel" name="phone" required placeholder="01xxxxxxxxx"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    {{ __('center::students.form.parent_phone') }} (اختياري)
                </label>
                <input type="tel" name="parent_phone" placeholder="01xxxxxxxxx"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                {{ __('center::students.form.grade_level') }} <span class="text-rose-500">*</span>
            </label>
            <select name="grade_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
                <option value="">{{ __('center::students.form.choose_grade') }}</option>
                @if(isset($stages))
                    @foreach($stages as $stage)
                        <optgroup label="{{ $stage->name }}">
                            @foreach($stage->grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @endif
            </select>
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    المجموعة الدراسية <span class="text-rose-500">*</span>
                </label>
                <button type="button" @click="$dispatch('open-modal', 'quick-course-modal')" class="text-xs font-bold text-brand-primary dark:text-brand-300 hover:underline">
                    + إنشاء مجموعة جديدة
                </button>
            </div>
            <select name="course_ids[]" id="quick_student_course_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
                <option value="">اختر المجموعة الدراسية...</option>
                @if(isset($courses))
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus me-1"></i> تسجيل الطالب
            </button>
            <button type="button" @click="show = false" class="btn btn-outline-secondary">
                {{ __('center::instructors.cancel') }}
            </button>
        </div>
    </form>
</x-ui.modal>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('quick-student-form');
        if (!form) return;

        // Reset errors on input
        form.querySelectorAll('input, select').forEach(function (input) {
            input.addEventListener('input', function () {
                input.classList.remove('border-rose-500');
                const err = input.parentNode.querySelector('.quick-add-error');
                if (err) err.remove();
            });
        });

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جارٍ التسجيل...';

            form.querySelectorAll('.quick-add-error').forEach(function (el) { el.remove(); });
            form.querySelectorAll('.border-rose-500').forEach(function (el) { el.classList.remove('border-rose-500'); });

            let data = {};
            try {
                const response = await fetch('{{ route('center.students.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(form)
                });

                try {
                    data = await response.json();
                } catch (parseErr) {
                    data = { success: false, message: 'حدث خطأ أثناء تسجيل الطالب.' };
                }

                if (!response.ok || !data.success) {
                    const errors = data.errors || {};
                    for (const field in errors) {
                        const input = form.querySelector('[name="' + field + '"], [name="' + field + '[]"]');
                        if (input) {
                            input.classList.add('border-rose-500');
                            const err = document.createElement('p');
                            err.className = 'quick-add-error text-rose-500 text-xs mt-1.5 flex items-center gap-1';
                            err.innerHTML = '<i class="fas fa-exclamation-circle"></i>' + errors[field].join(', ');
                            input.parentNode.appendChild(err);
                        }
                    }
                    if (!Object.keys(errors).length && data.message && typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: data.message, timer: 2500, showConfirmButton: false });
                    }
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    return;
                }

                // Close modal and reset
                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'quick-student-modal' }));
                form.reset();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم تسجيل الطالب بنجاح 🎉',
                        toast: true,
                        position: 'top-end',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                setTimeout(() => window.location.reload(), 1000);
            } catch (error) {
                console.error('Quick student error:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'حدث خطأ غير متوقع، حاول مرة أخرى.', timer: 2500, showConfirmButton: false });
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    });
</script>
@endpush
