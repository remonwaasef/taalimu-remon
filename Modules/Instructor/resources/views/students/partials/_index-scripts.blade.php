<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('studentSearchInput');
    const groupFilter = document.getElementById('groupFilter');
    const finFilters = document.querySelectorAll('.financial-filter');
    const rows = document.querySelectorAll('.student-row');
    const noResults = document.getElementById('noStudentsResults');
    const resultCount = document.getElementById('studentResultCount');
    const table = document.getElementById('studentsTable');

    const bulkBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');
    const selectAllCheckbox = document.getElementById('selectAllStudents');
    const studentCheckboxes = document.querySelectorAll('.student-checkbox');
    const bulkWhatsAppBtn = document.getElementById('bulkWhatsAppBtn');
    const cancelSelectionBtn = document.getElementById('cancelSelection');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.student-checkbox:checked');
        const count = checked.length;
        
        if (count > 0) {
            bulkBar.classList.remove('d-none');
            selectedCountSpan.textContent = count;
        } else {
            bulkBar.classList.add('d-none');
        }
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = count === studentCheckboxes.length && count > 0;
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            studentCheckboxes.forEach(cb => {
                const row = cb.closest('tr');
                if (row.style.display !== 'none') {
                    cb.checked = isChecked;
                }
            });
            updateBulkBar();
        });
    }

    studentCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBar);
    });

    if (cancelSelectionBtn) {
        cancelSelectionBtn.addEventListener('click', function() {
            studentCheckboxes.forEach(cb => cb.checked = false);
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateBulkBar();
        });
    }

    if (bulkWhatsAppBtn) {
        bulkWhatsAppBtn.addEventListener('click', function() {
            const selectedPhones = [];
            document.querySelectorAll('.student-checkbox:checked').forEach(cb => {
                const row = cb.closest('tr');
                const phone = row.dataset.phone;
                if (phone) {
                    let cleanPhone = phone.replace(/[^0-9]/g, '');
                    if (cleanPhone.startsWith('0')) cleanPhone = '20' + cleanPhone.substring(1);
                    selectedPhones.push(cleanPhone);
                }
            });

            if (selectedPhones.length > 0) {
                const first = selectedPhones[0];
                window.open(`https://api.whatsapp.com/send?phone=${first}`, '_blank');
                if (selectedPhones.length > 1) {
                    alert('{{ __('instructor::students.whatsapp_bulk_alert') }}');
                }
            }
        });
    }

    function applyStudentFilters() {
        const query = searchInput.value.trim().toLowerCase();
        const filterGroupId = groupFilter.value;
        const activeFinFilterEl = document.querySelector('.financial-filter:checked');
        const activeFinFilter = activeFinFilterEl ? activeFinFilterEl.value : 'all';
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const phone = row.dataset.phone.toLowerCase();
            const groups = JSON.parse(row.dataset.groups);
            const finStatus = row.dataset.finStatus;

            let matchSearch = !query || name.includes(query) || phone.includes(query);
            let matchGroup = filterGroupId === 'all' || groups.includes(parseInt(filterGroupId));
            let matchFin = activeFinFilter === 'all' || finStatus === activeFinFilter;

            if (matchSearch && matchGroup && matchFin) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
                const cb = row.querySelector('.student-checkbox');
                if (cb) cb.checked = false;
            }
        });

        updateBulkBar();
        if (resultCount) resultCount.textContent = visibleCount + ' {{ __('instructor::students.student_count') }}';
        
        if (noResults) {
            noResults.classList.toggle('d-none', visibleCount > 0 || rows.length === 0);
        }
    }

    // Quick Payment Logic
    const payModalEl = document.getElementById('quickPayModal');
    const payModal = payModalEl ? new bootstrap.Modal(payModalEl) : null;
    document.querySelectorAll('.quick-pay-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (payModal) {
                document.getElementById('payStudentId').value = this.dataset.id;
                document.getElementById('payStudentName').textContent = this.dataset.name;
                document.getElementById('payAmountInput').value = this.dataset.balance;
                document.getElementById('payBalanceHint').textContent = 'المستحق الحالي: ' + this.dataset.balance + ' ج.م';
                payModal.show();
            }
        });
    });

    // Quick Enroll Logic
    const enrollModalEl = document.getElementById('quickEnrollModal');
    const enrollModal = enrollModalEl ? new bootstrap.Modal(enrollModalEl) : null;
    document.querySelectorAll('.quick-enroll-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (enrollModal) {
                document.getElementById('enrollStudentId').value = this.dataset.id;
                document.getElementById('enrollStudentName').textContent = this.dataset.name;
                enrollModal.show();
            }
        });
    });

    // QR & Portal Modal Logic
    const qrModalEl = document.getElementById('qrModal');
    const qrModal = qrModalEl ? new bootstrap.Modal(qrModalEl) : null;
    const qrModalImg = document.getElementById('qrModalImg');
    const qrModalName = document.getElementById('qrModalName');
    const portalUrlInput = document.getElementById('portalUrlInput');
    const openPortalBtn = document.getElementById('openPortalBtn');
    const copyPortalBtn = document.getElementById('copyPortalBtn');

    document.querySelectorAll('.show-qr-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (qrModal) {
                qrModalName.textContent = this.dataset.name;
                // Generate the QR locally from the identifier — nothing is sent
                // to any external QR service.
                qrModalImg.innerHTML = '';
                if (typeof QRCode !== 'undefined' && this.dataset.identifier) {
                    new QRCode(qrModalImg, {
                        text: this.dataset.identifier,
                        width: 180,
                        height: 180,
                        correctLevel: QRCode.CorrectLevel.H
                    });
                }
                portalUrlInput.value = this.dataset.portal;
                openPortalBtn.href = this.dataset.portal;
                qrModal.show();
            }
        });
    });

    if (copyPortalBtn) {
        copyPortalBtn.addEventListener('click', function() {
            portalUrlInput.select();
            document.execCommand('copy');
            const originalIcon = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i>';
            this.classList.replace('btn-outline-primary', 'btn-success');
            setTimeout(() => {
                this.innerHTML = originalIcon;
                this.classList.replace('btn-success', 'btn-outline-primary');
            }, 2000);
        });
    }

    // Transfer Modal
    const transferModalEl = document.getElementById('transferModal');
    const transferModal = transferModalEl ? new bootstrap.Modal(transferModalEl) : null;
    const transferForm = document.getElementById('transferForm');
    const transferStudentName = document.getElementById('transferStudentName');
    const fromCourseIdInput = document.getElementById('fromCourseId');

    document.querySelectorAll('.transfer-student-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const groups = JSON.parse(this.dataset.groups);
            transferStudentName.textContent = '{{ __('instructor::students.transfer_student_prefix') }}' + this.dataset.name;
            transferForm.action = `/instructor/students/${id}/transfer`;
            fromCourseIdInput.value = groups[0] || ''; // Pick first group as from
            transferModal.show();
        });
    });

    // Notes Modal
    const notesModalEl = document.getElementById('notesModal');
    const notesModal = notesModalEl ? new bootstrap.Modal(notesModalEl) : null;
    const notesForm = document.getElementById('notesForm');
    const studentNotesText = document.getElementById('studentNotesText');

    document.querySelectorAll('.edit-notes-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            studentNotesText.value = this.dataset.notes || '';
            notesForm.action = `/instructor/students/${id}/update-notes`;
            notesModal.show();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyStudentFilters);
    if (groupFilter) groupFilter.addEventListener('change', applyStudentFilters);
    finFilters.forEach(f => f.addEventListener('change', applyStudentFilters));

    // Initial count
    applyStudentFilters();
});
</script>
