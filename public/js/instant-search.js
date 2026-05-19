/**
 * ============================================================
 * Taalimu Smart Search — Universal DRY Search & Filter Engine
 * ============================================================
 * A single, reusable search library for ALL pages.
 * Works automatically via HTML data-* attributes.
 *
 * USAGE (in any Blade file):
 *
 *   <!-- Search Input -->
 *   <input type="text"
 *       data-smart-search="#myTable"
 *       data-search-fields="name,phone,email"
 *       data-search-counter="#resultCount"
 *       data-search-empty="#noResults"
 *       data-search-highlight="true"
 *       placeholder="بحث...">
 *
 *   <!-- Optional Filter (select/radio) -->
 *   <select data-smart-filter="#myTable" data-filter-key="status">
 *       <option value="all">الكل</option>
 *       <option value="active">نشط</option>
 *   </select>
 *
 *   <!-- Table Rows must have data-* matching search-fields -->
 *   <tr class="smart-row" data-name="أحمد" data-phone="0123" data-status="active">
 *
 * That's it! No JavaScript needed per-page.
 * ============================================================
 */

(function() {
    'use strict';

    // ─── Arabic Normalization ───
    function normalizeArabic(str) {
        if (!str) return '';
        return str
            .replace(/[إأآا]/g, 'ا')
            .replace(/ة/g, 'ه')
            .replace(/ى/g, 'ي')
            .replace(/ئ/g, 'ي')
            .replace(/ؤ/g, 'و')
            .replace(/[ًٌٍَُِّْ]/g, '')
            .replace(/\s+/g, ' ')
            .trim()
            .toLowerCase();
    }

    // ─── Highlight Helpers ───
    function clearHighlights(container) {
        if (!container) return;
        container.querySelectorAll('mark.smart-highlight').forEach(function(mark) {
            var parent = mark.parentNode;
            parent.replaceChild(document.createTextNode(mark.textContent), mark);
            parent.normalize();
        });
    }

    function highlightInElement(element, rawQuery) {
        if (!element || !rawQuery || rawQuery.length < 2) return;
        var walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, null, false);
        var textNodes = [];
        while (walker.nextNode()) textNodes.push(walker.currentNode);

        var normalizedQuery = normalizeArabic(rawQuery);

        textNodes.forEach(function(node) {
            var parent = node.parentNode;
            if (parent.classList && parent.classList.contains('smart-highlight')) return;
            if (parent.tagName === 'SCRIPT' || parent.tagName === 'STYLE') return;

            var text = node.textContent;
            var normalizedText = normalizeArabic(text);
            var idx = normalizedText.indexOf(normalizedQuery);
            if (idx === -1) return;

            var before = text.substring(0, idx);
            var match = text.substring(idx, idx + rawQuery.length);
            var after = text.substring(idx + rawQuery.length);

            var span = document.createElement('span');
            span.innerHTML = escapeHtml(before) +
                '<mark class="smart-highlight">' + escapeHtml(match) + '</mark>' +
                escapeHtml(after);
            parent.replaceChild(span, node);
        });
    }

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    // ─── Debounce ───
    function debounce(fn, delay) {
        var timer;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function() { fn.apply(context, args); }, delay);
        };
    }

    // ─── Core: Initialize a Smart Search Instance ───
    function initSmartSearch(input) {
        var tableSelector = input.getAttribute('data-smart-search');
        var table = document.querySelector(tableSelector);
        if (!table) return;

        var tbody = table.querySelector('tbody');
        if (!tbody) return;

        // Config from data attributes
        var searchFieldsAttr = input.getAttribute('data-search-fields');
        var searchFields = searchFieldsAttr ? searchFieldsAttr.split(',').map(function(f) { return f.trim(); }) : [];
        var counterSelector = input.getAttribute('data-search-counter');
        var emptySelector = input.getAttribute('data-search-empty');
        var tableContainerSelector = input.getAttribute('data-search-container');
        var enableHighlight = input.getAttribute('data-search-highlight') !== 'false';
        var counterEl = counterSelector ? document.querySelector(counterSelector) : null;
        var emptyEl = emptySelector ? document.querySelector(emptySelector) : null;
        var containerEl = tableContainerSelector ? document.querySelector(tableContainerSelector) : null;
        var counterSuffix = input.getAttribute('data-search-counter-suffix') || '';

        // Collect all filters for this table
        var filters = document.querySelectorAll('[data-smart-filter="' + tableSelector + '"]');

        // Row selector - support custom or default
        var rowSelector = input.getAttribute('data-search-row') || '.smart-row, .student-row, [data-name]';
        var rows = tbody.querySelectorAll(rowSelector);

        // Cache row data for performance
        var rowCache = Array.from(rows).map(function(row) {
            var data = { el: row, textCells: [], allText: '' };

            // If specific fields defined, use data attributes
            if (searchFields.length > 0) {
                searchFields.forEach(function(field) {
                    data[field] = normalizeArabic(row.getAttribute('data-' + field) || '');
                });
            }

            // Always cache full text content as fallback
            data.allText = normalizeArabic(row.textContent);

            // Cache highlightable cells (first 3 tds)
            var tds = row.querySelectorAll('td');
            for (var i = 0; i < Math.min(tds.length, 4); i++) {
                data.textCells.push(tds[i]);
            }

            return data;
        });

        // Create empty state if not exists
        var emptyRow = tbody.querySelector('.smart-search-empty');
        if (!emptyRow) {
            emptyRow = document.createElement('tr');
            emptyRow.className = 'smart-search-empty';
            emptyRow.style.display = 'none';
            var colCount = (rows[0] ? rows[0].children.length : 5);
            emptyRow.innerHTML =
                '<td colspan="' + colCount + '" class="text-center py-5">' +
                '<div class="mb-3 opacity-25"><i class="fas fa-search fa-3x text-muted"></i></div>' +
                '<h6 class="text-muted small fw-bold">لا توجد نتائج مطابقة</h6>' +
                '<p class="text-muted" style="font-size:0.75rem">جرب استخدام كلمات مفتاحية أخرى</p>' +
                '</td>';
            tbody.appendChild(emptyRow);
        }

        // ─── Filter Logic ───
        function applyFilters() {
            var rawQuery = input.value.trim();
            var query = normalizeArabic(rawQuery);
            var visible = 0;

            // Collect active filter values
            var activeFilters = {};
            filters.forEach(function(filterEl) {
                var key = filterEl.getAttribute('data-filter-key');
                if (!key) return;
                var val;
                if (filterEl.tagName === 'SELECT') {
                    val = filterEl.value;
                } else if (filterEl.type === 'radio') {
                    if (filterEl.checked) {
                        val = filterEl.value;
                    } else {
                        return; // Skip unchecked radios
                    }
                } else {
                    val = filterEl.value;
                }
                if (val && val !== 'all') {
                    activeFilters[key] = normalizeArabic(val);
                }
            });

            // Clear highlights
            if (enableHighlight) {
                rowCache.forEach(function(item) {
                    item.textCells.forEach(function(cell) { clearHighlights(cell); });
                });
            }

            // Filter rows
            rowCache.forEach(function(item) {
                // Search match
                var matchSearch = true;
                if (query) {
                    if (searchFields.length > 0) {
                        matchSearch = searchFields.some(function(field) {
                            return (item[field] || '').indexOf(query) !== -1;
                        });
                    }
                    // Fallback: full text search
                    if (!matchSearch) {
                        matchSearch = item.allText.indexOf(query) !== -1;
                    }
                }

                // Filter match
                var matchFilter = true;
                Object.keys(activeFilters).forEach(function(key) {
                    var rowVal = normalizeArabic(item.el.getAttribute('data-' + key) || '');
                    var filterVals = activeFilters[key].split(',').map(function(v) { return v.trim(); });
                    
                    var found = filterVals.some(function(filterVal) {
                        return rowVal.indexOf(filterVal) !== -1;
                    });

                    if (!found) {
                        matchFilter = false;
                    }
                });

                if (matchSearch && matchFilter) {
                    item.el.style.display = '';
                    visible++;
                    // Highlight
                    if (enableHighlight && rawQuery.length >= 2) {
                        item.textCells.forEach(function(cell) {
                            highlightInElement(cell, rawQuery);
                        });
                    }
                } else {
                    item.el.style.display = 'none';
                }
            });

            // Update counter
            if (counterEl) {
                counterEl.textContent = visible + ' ' + counterSuffix;
                counterEl.style.transform = 'scale(1.05)';
                setTimeout(function() { counterEl.style.transform = 'scale(1)'; }, 200);
            }

            // Empty state
            var origEmpty = document.getElementById('empty-state-row');
            if (origEmpty) origEmpty.style.display = 'none';

            emptyRow.style.display = (visible === 0 && (query || Object.keys(activeFilters).length > 0)) ? '' : 'none';

            if (emptyEl) emptyEl.classList.toggle('d-none', visible > 0);
            if (containerEl) containerEl.classList.toggle('d-none', visible === 0);
        }

        // ─── Event Listeners ───
        var debouncedApply = debounce(applyFilters, 150);
        input.addEventListener('input', function(e) {
            // Sync with global header search if user types in local search
            document.querySelectorAll('.global-search-input').forEach(function(globalInput) {
                if (globalInput.value !== input.value) {
                    globalInput.value = input.value;
                }
            });
            debouncedApply();
        });
        
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                input.value = '';
                document.querySelectorAll('.global-search-input').forEach(function(globalInput) { globalInput.value = ''; });
                applyFilters();
                input.blur();
            }
        });

        // Sync global header search to this local search
        var globalInputs = document.querySelectorAll('.global-search-input');
        globalInputs.forEach(function(globalInput) {
            globalInput.addEventListener('input', function(e) {
                if (input.value !== globalInput.value) {
                    input.value = globalInput.value;
                    debouncedApply();
                }
            });
            globalInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    globalInput.value = '';
                    input.value = '';
                    applyFilters();
                    globalInput.blur();
                }
            });
        });

        filters.forEach(function(filterEl) {
            filterEl.addEventListener('change', applyFilters);
        });
    }

    // ─── Keyboard Shortcut: / or Ctrl+K ───
    document.addEventListener('keydown', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') return;
        if (e.key === '/' || (e.ctrlKey && e.key === 'k') || (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'f')) {
            var searchInput = document.querySelector('.global-search-input') || document.querySelector('[data-smart-search]');
            if (searchInput) {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        }
    });

    // ─── Auto-Initialize on DOM Ready ───
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-smart-search]').forEach(initSmartSearch);
    });

    // ─── Inject Styles ───
    var style = document.createElement('style');
    style.textContent =
        'mark.smart-highlight{background:linear-gradient(135deg,#ffd60a,#ffe066);color:#1a1a1a;padding:1px 3px;border-radius:4px;font-weight:700;box-shadow:0 1px 3px rgba(255,214,10,.4)}' +
        '[data-smart-search]:focus{box-shadow:0 0 0 3px rgba(25,135,84,.15)!important;border-color:#198754!important}';
    document.head.appendChild(style);

})();
