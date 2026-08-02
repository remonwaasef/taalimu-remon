<x-ui.modal id="quick-classroom-modal" title="إضافة قاعة جديدة" size="md">
    <form id="quick-classroom-form" class="space-y-4">
        @csrf
        <input type="hidden" name="status" value="active">

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                {{ __('center::classrooms.name') }} <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="name" required
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                {{ __('center::classrooms.capacity') }}
            </label>
            <input type="number" name="capacity" min="1"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                {{ __('center::classrooms.type') }}
            </label>
            <select name="type" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
                <option value="classroom">{{ __('center::classrooms.type_classroom') }}</option>
                <option value="lab">{{ __('center::classrooms.type_lab') }}</option>
                <option value="auditorium">{{ __('center::classrooms.type_auditorium') }}</option>
            </select>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> {{ __('center::classrooms.save_classroom') }}
            </button>
            <button type="button" @click="show = false" class="btn btn-outline-secondary">
                {{ __('center::classrooms.cancel') }}
            </button>
        </div>
    </form>
</x-ui.modal>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('quick-classroom-form');
        if (!form) return;

        // Open modal from any trigger element
        document.querySelectorAll('[data-quick-classroom-trigger]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'quick-classroom-modal' }));
            });
        });

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
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جارٍ الإضافة...';

            form.querySelectorAll('.quick-add-error').forEach(function (el) { el.remove(); });
            form.querySelectorAll('.border-rose-500').forEach(function (el) { el.classList.remove('border-rose-500'); });

            let data = {};
            try {
                const response = await fetch('{{ route('center.classrooms.store') }}', {
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

                // Add the new classroom to the select and select it
                const select = document.querySelector('select[name="schedules[INDEX][classroom_id]"]');
                if (select) {
                    const option = new Option(data.classroom.name, data.classroom.id, true, true);
                    select.appendChild(option);
                    select.value = data.classroom.id;
                    select.dispatchEvent(new Event('change'));
                }

                // Hide the empty-state notice
                const notice = document.querySelector('[data-empty-classrooms-notice]');
                if (notice) notice.style.display = 'none';

                // Close modal and reset
                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'quick-classroom-modal' }));
                form.reset();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'تمت إضافة القاعة',
                        text: data.message || '',
                        toast: true,
                        position: 'top-end',
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            } catch (error) {
                console.error('Quick classroom error:', error);
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
