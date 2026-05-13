/**
 * ============================================================
 * Taalimu Instant Search — Client-side fast filtering
 * ============================================================
 * Search seamlessly through tables without reloading
 * ============================================================
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    const searchInputs = document.querySelectorAll('[data-instant-search]');
    
    searchInputs.forEach(input => {
        const targetSelector = input.getAttribute('data-instant-search');
        const targetTable = document.querySelector(targetSelector);
        
        if (!targetTable) return;
        
        const tbody = targetTable.querySelector('tbody');
        if (!tbody) return;

        let emptyStateRow = tbody.querySelector('.instant-search-empty');
        if (!emptyStateRow) {
            emptyStateRow = document.createElement('tr');
            emptyStateRow.className = 'instant-search-empty';
            emptyStateRow.style.display = 'none';
            const colCount = tbody.querySelector('tr:first-child')?.children.length || 5;
            emptyStateRow.innerHTML = `
                <td colspan="${colCount}" class="text-center py-5">
                    <div class="mb-3 opacity-20">
                        <i class="fas fa-search fa-3x text-muted"></i>
                    </div>
                    <h6 class="text-muted small fw-bold">لا توجد نتائج مطابقة</h6>
                    <p class="text-muted x-small">جرب استخدام كلمات مفتاحية أخرى</p>
                </td>
            `;
            tbody.appendChild(emptyStateRow);
        }

        input.addEventListener('keyup', function(e) {
            const term = e.target.value.toLowerCase().trim();
            const rows = tbody.querySelectorAll('tr:not(.instant-search-empty):not(#empty-state-row)');
            let hasVisible = false;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(term)) {
                    row.style.display = '';
                    hasVisible = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle Empty State
            const origEmpty = document.getElementById('empty-state-row');
            if (origEmpty) origEmpty.style.display = 'none';

            if (!hasVisible && term !== '') {
                emptyStateRow.style.display = '';
            } else {
                emptyStateRow.style.display = 'none';
                if (origEmpty && rows.length === 0) origEmpty.style.display = '';
            }
        });
    });
});
