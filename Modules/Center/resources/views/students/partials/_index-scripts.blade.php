    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stageBtns = document.querySelectorAll('.stage-btn');
            const subGradeContainers = document.querySelectorAll('.sub-grades-container');
            const smartGradeFilter = document.getElementById('smartGradeFilter');

            function updateSmartGradeFilter(grades) {
                if (smartGradeFilter) {
                    smartGradeFilter.value = grades ? grades : 'all';
                    smartGradeFilter.dispatchEvent(new Event('change'));
                }
            }

            stageBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    stageBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    subGradeContainers.forEach(c => c.style.display = 'none');
                    
                    const stage = this.getAttribute('data-stage');
                    if (stage === 'all') {
                        updateSmartGradeFilter(null);
                    } else {
                        const gradesAttr = this.getAttribute('data-grades');
                        updateSmartGradeFilter(gradesAttr);
                        const subGradeContainer = document.getElementById(stage + '-grades');
                        if (subGradeContainer) {
                            subGradeContainer.style.display = 'block';
                        }
                    }
                });
            });

            // Quick Payment Logic
            document.querySelectorAll('.quick-pay-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('payStudentId').value = this.dataset.id;
                    document.getElementById('payStudentName').textContent = this.dataset.name;
                    document.getElementById('payAmountInput').value = this.dataset.balance;
                    document.getElementById('payBalanceHint').textContent = '{{ __('center::students.current_balance') }}: ' + this.dataset.balance + ' {{ get_currency_symbol() }}';
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'quickPayModal' }));
                });
            });

            // Quick Enroll Logic (Delegated)
            $(document).on('click', '.quick-enroll-btn', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const enrolled = String($(this).data('enrolled') || '');
                window.openQuickEnrollModal(id, name, enrolled);
            });

            document.querySelectorAll('.grade-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.grade-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    updateSmartGradeFilter(this.getAttribute('data-grade'));
                });
            });

            // Bulk Action logic
            const selectAll = document.getElementById('select-all');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const bulkToolbar = document.getElementById('bulk-actions-toolbar');
            const selectedCount = document.getElementById('selected-count');

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    studentCheckboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateBulkToolbar();
                });
            }

            studentCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkToolbar);
            });

            function updateBulkToolbar() {
                const checkedCount = Array.from(studentCheckboxes).filter(cb => cb.checked).length;
                if (checkedCount > 0) {
                    bulkToolbar.classList.remove('d-none');
                    selectedCount.textContent = checkedCount;
                } else {
                    bulkToolbar.classList.add('d-none');
                    if (selectAll) selectAll.checked = false;
                }
            }

            window.bulkAction = function(action) {
                const selectedIds = Array.from(studentCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
                if (selectedIds.length === 0) return;
                if (confirm('{{ __('center::students.bulk_confirm', ['count' => "'+selectedIds.length+'"]) }}'.replace("'+selectedIds.length+'", selectedIds.length))) {
                    alert('Processing [' + action + '] for IDs: ' + selectedIds.join(', '));
                }
            };

            // Direct Email Logic
            document.querySelectorAll('.direct-email-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const studentId = this.dataset.id;
                    const subject = this.dataset.subject;
                    const message = this.dataset.message;
                    
                    const form = document.getElementById('directEmailForm');
                    form.action = `/students/${studentId}/send-email`;
                    document.getElementById('directEmailSubject').value = subject;
                    document.getElementById('directEmailMessage').value = message;
                    
                    if (confirm('{{ __('center::students.send_report_confirm') }}')) {
                        form.submit();
                    }
                });
            });

            // Removed manual filterStudents() since it's handled by smart-search.js
        });

        // AJAX Deletion with Undo Functionality
        $(document).on('submit', '.delete-student-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const studentName = form.data('name');
            const row = form.closest('.student-row');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: `سيتم حذف الطالب ${studentName}. يمكنك التراجع عن هذا الإجراء.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hide row immediately for UX
                    row.fadeOut();

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'تم الحذف بنجاح',
                                    text: `تم نقل الطالب ${studentName} إلى سلة المهملات.`,
                                    icon: 'success',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: true,
                                    confirmButtonText: 'تراجع (Undo)',
                                    timer: 8000,
                                    timerProgressBar: true
                                }).then((undoResult) => {
                                    if (undoResult.isConfirmed) {
                                        // Trigger restore
                                        $.post(response.restore_url, { _token: '{{ csrf_token() }}' }, function(restoreRes) {
                                            if (restoreRes.success) {
                                                row.fadeIn();
                                                Swal.fire({
                                                    title: 'تمت الاستعادة',
                                                    text: 'تمت استعادة الطالب بنجاح.',
                                                    icon: 'success',
                                                    toast: true,
                                                    position: 'top-end',
                                                    timer: 3000
                                                });
                                            }
                                        });
                                    }
                                });
                            }
                        },
                        error: function() {
                            row.fadeIn();
                            Swal.fire('خطأ', 'حدث خطأ أثناء الحذف.', 'error');
                        }
                    });
                }
            });
        });

    window.openQuickEnrollModal = function(studentId, studentName, enrolledStr) {
        const enrolledIds = enrolledStr ? String(enrolledStr).split(',').map(s => s.trim()).filter(Boolean) : [];

        const idInput = document.getElementById('enrollStudentId');
        const nameEl = document.getElementById('enrollStudentName');
        const selectEl = document.getElementById('courseSelect');
        const warningEl = document.getElementById('enrollWarning');
        const warningTextEl = document.getElementById('enrollWarningText');
        const submitBtn = document.getElementById('submitEnrollBtn');

        if (idInput) idInput.value = studentId;
        if (nameEl) nameEl.textContent = studentName;

        let availableCount = 0;

        if (selectEl) {
            selectEl.value = '';
            Array.from(selectEl.options).forEach(opt => {
                if (!opt.value) return;

                const originalText = opt.getAttribute('data-original-text') || opt.text;
                if (!opt.getAttribute('data-original-text')) {
                    opt.setAttribute('data-original-text', originalText);
                }

                if (enrolledIds.includes(String(opt.value))) {
                    opt.disabled = true;
                    opt.style.color = '#94a3b8';
                    opt.text = originalText + ' - {{ __('center::students.already_enrolled_label') }}';
                } else {
                    opt.disabled = false;
                    opt.style.color = '';
                    opt.text = originalText;
                    availableCount++;
                }
            });
        }

        if (availableCount === 0 && enrolledIds.length > 0) {
            if (warningEl) {
                if (warningTextEl) warningTextEl.textContent = '{{ __('center::students.all_courses_enrolled') }}';
                warningEl.classList.remove('d-none');
            }
            if (submitBtn) submitBtn.disabled = true;
        } else {
            if (warningEl) {
                if (warningTextEl) warningTextEl.textContent = '{{ __('center::students.already_enrolled_warning') }}';
                warningEl.classList.add('d-none');
            }
            if (submitBtn) submitBtn.disabled = false;
        }

        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'quickEnrollModal' }));
    };

    window.handleCourseSelectChange = function(selectEl) {
        const selectedOpt = selectEl.options[selectEl.selectedIndex];
        const isEnrolled = selectedOpt && selectedOpt.disabled;
        const warningEl = document.getElementById('enrollWarning');
        const submitBtn = document.getElementById('submitEnrollBtn');

        if (isEnrolled) {
            if (warningEl) warningEl.classList.remove('d-none');
            if (submitBtn) submitBtn.disabled = true;
        } else {
            if (warningEl) warningEl.classList.add('d-none');
            if (submitBtn) submitBtn.disabled = false;
        }
    };

    window.handleEnrollSubmit = function(e) {
        if (e) e.preventDefault();
        const selectEl = document.getElementById('courseSelect');
        const form = document.getElementById('enrollForm');
        const courseId = selectEl ? selectEl.value : null;

        if (!courseId) {
            Swal.fire({
                icon: 'warning',
                text: '{{ __('center::students.choose_course_first') }}',
                confirmButtonText: 'حسناً'
            });
            return false;
        }

        const selectedOpt = selectEl.options[selectEl.selectedIndex];
        if (selectedOpt && selectedOpt.getAttribute('data-enrolled') === 'true') {
            Swal.fire({
                icon: 'error',
                text: '{{ __('center::students.already_enrolled_warning') }}',
                confirmButtonText: 'حسناً'
            });
            return false;
        }

        form.action = `/courses/${courseId}/enroll`;
        form.submit();
        return true;
    };

    window.openDirectWhatsApp = function(phone, text = '') {
        if (!phone) {
            alert('لا يوجد رقم هاتف مسجل للطالب');
            return;
        }
        const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
        const query = text ? ('?phone=' + phone + '&text=' + encodeURIComponent(text)) : ('?phone=' + phone);

        if (isMobile) {
            window.open('https://api.whatsapp.com/send' + query, '_blank');
        } else {
            // Direct WhatsApp Web without intermediate landing page
            window.open('https://web.whatsapp.com/send' + query, '_blank');
        }
    };
</script>
