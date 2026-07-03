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
            const payModalEl = document.getElementById('quickPayModal');
            const payModal = payModalEl ? new bootstrap.Modal(payModalEl) : null;
            document.querySelectorAll('.quick-pay-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (payModal) {
                        document.getElementById('payStudentId').value = this.dataset.id;
                        document.getElementById('payStudentName').textContent = this.dataset.name;
                        document.getElementById('payAmountInput').value = this.dataset.balance;
                        document.getElementById('payBalanceHint').textContent = '{{ __('center::students.current_balance') }}: ' + this.dataset.balance + ' {{ get_currency_symbol() }}';
                        payModal.show();
                    }
                });
            });

            // Quick Enroll Logic
            const enrollModalEl = document.getElementById('quickEnrollModal');
            const enrollModal = enrollModalEl ? new bootstrap.Modal(enrollModalEl) : null;
            const courseSelect = document.getElementById('courseSelect');
            const enrollForm = document.getElementById('enrollForm');

            document.querySelectorAll('.quick-enroll-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (enrollModal) {
                        const enrolledIds = this.dataset.enrolled ? this.dataset.enrolled.split(',') : [];
                        document.getElementById('enrollStudentId').value = this.dataset.id;
                        document.getElementById('enrollStudentName').textContent = this.dataset.name;
                        
                        const options = courseSelect.querySelectorAll('option');
                        options.forEach(opt => {
                            if (opt.value && enrolledIds.includes(opt.value)) {
                                opt.setAttribute('data-enrolled', 'true');
                                if (!opt.textContent.includes('{{ __('center::students.already_enrolled_label') }}')) {
                                    opt.textContent = opt.textContent + ' {{ __('center::students.already_enrolled_label') }}';
                                }
                            } else {
                                opt.removeAttribute('data-enrolled');
                                opt.textContent = opt.textContent.replace(' {{ __('center::students.already_enrolled_label') }}', '');
                            }
                        });
                        
                        if ($.fn.select2) {
                            $(courseSelect).val("").trigger('change');
                        } else {
                            courseSelect.value = "";
                        }
                        
                        document.getElementById('enrollWarning').classList.add('d-none');
                        document.getElementById('submitEnrollBtn').disabled = false;
                        
                        enrollModal.show();
                    }
                });
            });

            $(courseSelect).on('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                const isEnrolled = selectedOpt && selectedOpt.getAttribute('data-enrolled') === 'true';
                const warning = document.getElementById('enrollWarning');
                const submitBtn = document.getElementById('submitEnrollBtn');
                
                if (isEnrolled) {
                    warning.classList.remove('d-none');
                    submitBtn.disabled = true;
                    submitBtn.classList.replace('btn-info', 'btn-secondary');
                } else {
                    warning.classList.add('d-none');
                    submitBtn.disabled = false;
                    submitBtn.classList.replace('btn-secondary', 'btn-info');
                }
            });

            document.getElementById('submitEnrollBtn').addEventListener('click', function() {
                const courseId = courseSelect.value;
                if (!courseId) return alert('{{ __('center::students.choose_course_first') }}');
                enrollForm.action = `/courses/${courseId}/enroll`;
                enrollForm.submit();
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

    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('#courseSelect').select2({
                    dropdownParent: $('#quickEnrollModal'),
                    width: '100%',
                    language: {
                        noResults: function() { return "{{ __('center::students.no_results') }}"; }
                    }
                });
            }
        });
    </script>
