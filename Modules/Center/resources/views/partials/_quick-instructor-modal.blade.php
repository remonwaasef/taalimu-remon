<x-ui.modal id="quick-instructor-modal" title="إضافة مدرس جديد" size="md">
    <form id="quick-instructor-form" class="space-y-4">
        @csrf
        <input type="hidden" name="status" value="active">
        <input type="hidden" name="commission_type" value="percentage">
        <input type="hidden" name="commission_rate" value="0">

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                {{ __('center::instructors.name') }} <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="name" required
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                {{ __('center::instructors.specialization') }} <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="specialization" required
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                {{ __('center::instructors.phone') }} <span class="text-rose-500">*</span>
            </label>
            <input type="tel" name="phone" required
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                {{ __('center::instructors.email') }}
            </label>
            <input type="email" name="email"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus me-1"></i> {{ __('center::instructors.save_instructor') }}
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
        const form = document.getElementById('quick-instructor-form');
        if (!form) return;

        // Open modal from any trigger element
        document.querySelectorAll('[data-quick-instructor-trigger]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'quick-instructor-modal' }));
            });
        });

        // Reset errors on input
        form.querySelectorAll('input').forEach(function (input) {
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
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جارٍ الإضافة...';

            form.querySelectorAll('.quick-add-error').forEach(function (el) { el.remove(); });
            form.querySelectorAll('.border-rose-500').forEach(function (el) { el.classList.remove('border-rose-500'); });

            let data = {};
            try {
                const response = await fetch('{{ route('center.instructors.store') }}', {
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
                    data = { success: false, message: 'حدث خطأ غير متوقع، حاول مرة أخرى.' };
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

                // Add the new instructor to all instructor selects on the page
                const selects = document.querySelectorAll('select[name="instructor_id"], #instructor_select');
                selects.forEach(function(select) {
                    if (select.tomselect) {
                        select.tomselect.addOption({ value: data.instructor.id, text: data.instructor.name });
                        select.tomselect.setValue(data.instructor.id);
                    } else {
                        select.appendChild(new Option(data.instructor.name, data.instructor.id, true, true));
                        select.value = data.instructor.id;
                        select.dispatchEvent(new Event('change'));
                    }
                });

                // Hide the empty-state notice
                const notice = document.querySelector('[data-empty-instructors-notice]');
                if (notice) notice.style.display = 'none';

                // Close modal and reset
                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'quick-instructor-modal' }));
                form.reset();

                // Update select in quick course modal if present
                const quickCourseSelect = document.getElementById('quick_course_instructor_id');
                if (quickCourseSelect) {
                    quickCourseSelect.appendChild(new Option(data.instructor.name, data.instructor.id, true, true));
                    quickCourseSelect.value = data.instructor.id;
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'تمت إضافة المدرس بنجاح 🎉',
                        text: data.message || '',
                        toast: true,
                        position: 'top-end',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                // If on dashboard, reload to update launchpad progress
                if (document.getElementById('quick-course-modal') || document.getElementById('demoDataForm') || document.getElementById('deleteDemoDataForm')) {
                    setTimeout(() => window.location.reload(), 1000);
                }
            } catch (error) {
                console.error('Quick instructor error:', error);
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
