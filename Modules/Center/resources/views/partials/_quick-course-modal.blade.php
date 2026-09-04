<x-ui.modal id="quick-course-modal" title="إنشاء دورة / مجموعة جديدة" size="md">
    <form id="quick-course-form" class="space-y-4">
        @csrf
        <input type="hidden" name="status" value="published">

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                {{ __('center::courses.form.title') }} <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="title" required placeholder="مثال: دورة الرياضيات - الصف الثالث الثانوي"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    {{ __('center::courses.instructor') }} <span class="text-rose-500">*</span>
                </label>
                <button type="button" @click="$dispatch('open-modal', 'quick-instructor-modal')" class="text-xs font-bold text-brand-primary dark:text-brand-300 hover:underline">
                    + إضافة مدرس جديد
                </button>
            </div>
            <select name="instructor_id" id="quick_course_instructor_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
                <option value="">{{ __('center::instructors.select_placeholder') }}</option>
                @if(isset($instructorsList))
                    @foreach($instructorsList as $instructor)
                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                    @endforeach
                @elseif(isset($instructors))
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    {{ __('center::courses.price') }} <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" step="1" min="0" name="price" value="0" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
                    <span class="absolute end-3 top-3 text-xs text-slate-400 font-bold">ج.م</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    عدد الحصص (شهرياً)
                </label>
                <input type="number" step="1" min="0" name="sessions_count" value="8"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                {{ __('center::courses.form.description') }} (اختياري)
            </label>
            <input type="text" name="description" placeholder="ملاحظات أو مواعيد الدورة..."
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-folder-plus me-1"></i> حفظ المجموعة
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
        const form = document.getElementById('quick-course-form');
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
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جارٍ الحفظ...';

            form.querySelectorAll('.quick-add-error').forEach(function (el) { el.remove(); });
            form.querySelectorAll('.border-rose-500').forEach(function (el) { el.classList.remove('border-rose-500'); });

            let data = {};
            try {
                const response = await fetch('{{ route('center.courses.store') }}', {
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
                    data = { success: false, message: 'حدث خطأ أثناء إنشاء الدورة.' };
                }

                if (!response.ok || !data.success) {
                    const errors = data.errors || {};
                    for (const field in errors) {
                        const input = form.querySelector('[name="' + field + '"]');
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

                // Add to course dropdown in quick student modal if present
                const courseSelects = document.querySelectorAll('select[name="course_ids[]"], #quick_student_course_id');
                courseSelects.forEach(function(select) {
                    select.appendChild(new Option(data.course.title, data.course.id, true, true));
                    select.value = data.course.id;
                });

                // Close modal and reset
                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'quick-course-modal' }));
                form.reset();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم إنشاء المجموعة بنجاح 🎉',
                        toast: true,
                        position: 'top-end',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                setTimeout(() => window.location.reload(), 1000);
            } catch (error) {
                console.error('Quick course error:', error);
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
