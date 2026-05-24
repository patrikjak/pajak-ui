declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

type PajakWithSelect = Record<string, unknown> & { PajakSelect?: { init: (el: HTMLElement) => unknown } };

function upgradeFilterSelects(editor: HTMLElement): void {
    editor.querySelectorAll<HTMLElement>('[data-pajak-select]').forEach((wrap) => {
        (window.Pajak as PajakWithSelect | undefined)?.PajakSelect?.init(wrap);
    });
}

let documentClickListenerRegistered = false;

function ensureDocumentClickListener(): void {
    if (documentClickListenerRegistered) {
        return;
    }

    documentClickListenerRegistered = true;

    document.addEventListener('click', () => {
        document.querySelectorAll<HTMLElement>('[data-pajak-table-overflow-menu]').forEach((m) => {
            m.hidden = true;
        });
        document.querySelectorAll<HTMLElement>('.pajak-table-columns-menu').forEach((m) => {
            m.hidden = true;
        });
        document.querySelectorAll<HTMLElement>('.pajak-table-filter-picker').forEach((m) => {
            m.hidden = true;
        });
        closeAllFilterEditors();
    });
}

interface FilterEntry {
    value: unknown;
    operator?: string;
}

interface TableState {
    search: string | null;
    sort: { column: string; direction: string } | null;
    filters: Record<string, FilterEntry[]>;
    page: number;
    perPage: number | null;
    visibleColumns: string[];
}

interface PendingConfirm {
    key: string;
    url: string;
    method: string;
}

function getStorageKey(tableName: string): string {
    return `pajak_table_${tableName}`;
}

function migrateFilters(filters: Record<string, unknown>): Record<string, FilterEntry[]> {
    const result: Record<string, FilterEntry[]> = {};

    for (const key of Object.keys(filters)) {
        const val = filters[key];

        if (Array.isArray(val)) {
            result[key] = val as FilterEntry[];
        } else if (val !== null && typeof val === 'object') {
            result[key] = [val as FilterEntry];
        }
    }

    return result;
}

function loadState(tableName: string): TableState {
    const raw = sessionStorage.getItem(getStorageKey(tableName));

    if (!raw) {
        return { search: null, sort: null, filters: {}, page: 1, perPage: null, visibleColumns: [] };
    }

    try {
        const state = JSON.parse(raw) as TableState;
        state.filters = migrateFilters(state.filters as unknown as Record<string, unknown>);

        return state;
    } catch {
        return { search: null, sort: null, filters: {}, page: 1, perPage: null, visibleColumns: [] };
    }
}

function saveState(tableName: string, state: TableState): void {
    sessionStorage.setItem(getStorageKey(tableName), JSON.stringify(state));
}

async function fetchTable(wrapper: HTMLElement, state: TableState): Promise<void> {
    const url = wrapper.dataset.url;

    if (!url) {
        return;
    }

    wrapper.classList.add('is-loading');

    const csrfToken = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '';

    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({
            search: state.search,
            sort: state.sort,
            filters: state.filters,
            page: state.page,
            per_page: state.perPage,
            visible_columns: state.visibleColumns,
        }),
    });

    wrapper.classList.remove('is-restoring');
    wrapper.classList.remove('is-loading');

    if (!response.ok) {
        return;
    }

    const html = await response.text();
    const body = wrapper.querySelector<HTMLElement>('[data-pajak-table-body]');

    if (!body) {
        return;
    }

    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    const newBody = doc.querySelector<HTMLElement>('[data-pajak-table-body]');

    if (newBody) {
        body.replaceWith(newBody);
        reinitOverflowMenus(wrapper);
    }

    updatePagination(wrapper, doc);
}

function updatePagination(wrapper: HTMLElement, doc: Document): void {
    const tableName = wrapper.dataset.tableName ?? '';
    const existing = wrapper.querySelector<HTMLElement>('.pajak-table-pagination');
    const incoming = doc.querySelector<HTMLElement>('.pajak-table-pagination');

    if (existing && incoming) {
        existing.replaceWith(incoming);
        bindPaginationButtons(wrapper);
        initPerPage(wrapper, tableName);
    } else if (existing && !incoming) {
        existing.remove();
    } else if (!existing && incoming) {
        const scroll = wrapper.querySelector<HTMLElement>('.pajak-table-scroll');
        scroll?.insertAdjacentElement('afterend', incoming);
        bindPaginationButtons(wrapper);
        initPerPage(wrapper, tableName);
    }
}

function debounce<T extends unknown[]>(fn: (...args: T) => void, delay: number): (...args: T) => void {
    let timer: ReturnType<typeof setTimeout>;

    return (...args: T): void => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

function restoreSearchInput(wrapper: HTMLElement, state: TableState): void {
    if (!state.search) {
        return;
    }

    const input = wrapper.querySelector<HTMLInputElement>('[data-pajak-table-search]');
    const clearBtn = wrapper.querySelector<HTMLButtonElement>('[data-pajak-table-search-clear]');

    if (input) {
        input.value = state.search;
    }

    if (clearBtn) {
        clearBtn.hidden = false;
    }
}

function initSearch(wrapper: HTMLElement, tableName: string): void {
    const input = wrapper.querySelector<HTMLInputElement>('[data-pajak-table-search]');
    const clearBtn = wrapper.querySelector<HTMLButtonElement>('[data-pajak-table-search-clear]');

    if (!input) {
        return;
    }

    const debouncedFetch = debounce(() => {
        const state = loadState(tableName);
        state.search = input.value.trim() || null;
        state.page = 1;
        saveState(tableName, state);
        fetchTable(wrapper, state);
    }, 300);

    input.addEventListener('input', () => {
        if (clearBtn) {
            clearBtn.hidden = input.value.length === 0;
        }

        debouncedFetch();
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            input.value = '';
            clearBtn.hidden = true;
            input.dispatchEvent(new Event('input'));
            input.focus();
        });
    }
}

function initSort(wrapper: HTMLElement, tableName: string): void {
    wrapper.querySelectorAll<HTMLElement>('[data-pajak-table-sort]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const columnKey = btn.dataset.pajakTableSort ?? '';
            const state = loadState(tableName);

            if (state.sort?.column === columnKey) {
                state.sort.direction = state.sort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                state.sort = { column: columnKey, direction: 'asc' };
            }

            state.page = 1;
            saveState(tableName, state);
            fetchTable(wrapper, state);
        });
    });
}

function bindPaginationButtons(wrapper: HTMLElement): void {
    const tableName = wrapper.dataset.tableName ?? '';

    wrapper.querySelectorAll<HTMLButtonElement>('[data-pajak-table-page]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const page = parseInt(btn.dataset.pajakTablePage ?? '1', 10);
            const state = loadState(tableName);
            state.page = page;
            saveState(tableName, state);
            fetchTable(wrapper, state);
        });
    });
}

function initPerPage(wrapper: HTMLElement, tableName: string): void {
    const native = wrapper.querySelector<HTMLSelectElement>('[data-pajak-table-per-page]');

    if (!native) {
        return;
    }

    const selectWrap = native.closest<HTMLElement>('[data-pajak-select]');

    if (selectWrap) {
        (window.Pajak as PajakWithSelect | undefined)?.PajakSelect?.init(selectWrap);
    }

    native.addEventListener('change', () => {
        const perPage = parseInt(native.value, 10);
        const state = loadState(tableName);
        state.perPage = perPage;
        state.page = 1;
        saveState(tableName, state);
        fetchTable(wrapper, state);
    });
}

function closeAllFilterEditors(wrapper?: HTMLElement): void {
    const scope = wrapper ?? document;
    scope.querySelectorAll<HTMLElement>('[data-pajak-filter-key]').forEach((ed) => {
        ed.hidden = true;
    });
}

function initFilters(wrapper: HTMLElement, tableName: string): void {
    const chipsContainer = wrapper.querySelector<HTMLElement>('[data-pajak-table-filter-chips]');
    const toggleBtn = wrapper.querySelector<HTMLElement>('[data-pajak-table-filter-toggle]');
    const filterEditors = wrapper.querySelectorAll<HTMLElement>('[data-pajak-filter-key]');

    if (toggleBtn && filterEditors.length > 0) {
        const pickerMenu = document.createElement('div');
        pickerMenu.className = 'pajak-table-filter-picker';
        pickerMenu.hidden = true;

        filterEditors.forEach((editor) => {
            const key = editor.dataset.pajakFilterKey ?? '';
            const labelEl = editor.querySelector<HTMLElement>('.pajak-table-filter-editor__label');
            const label = labelEl?.textContent?.trim() ?? key;

            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'pajak-table-filter-picker__item';
            item.dataset.filterPickerKey = key;
            item.textContent = label;

            pickerMenu.appendChild(item);
        });

        pickerMenu.addEventListener('click', (event) => {
            event.stopPropagation();
        });

        document.body.appendChild(pickerMenu);

        filterEditors.forEach((editor) => {
            editor.addEventListener('click', (event) => {
                event.stopPropagation();
            });

            const applyBtn = editor.querySelector<HTMLElement>('[data-pajak-filter-apply]');

            if (applyBtn) {
                applyBtn.addEventListener('click', () => {
                    const filterKey = editor.dataset.pajakFilterKey ?? '';
                    const editIndex = editor.dataset.editIndex !== undefined ? parseInt(editor.dataset.editIndex, 10) : null;
                    const state = loadState(tableName);

                    const textInput = editor.querySelector<HTMLInputElement>('[data-pajak-filter-value]');
                    const operatorSelect = editor.querySelector<HTMLSelectElement>('[data-pajak-filter-operator]');
                    const checkboxes = editor.querySelectorAll<HTMLInputElement>('[data-pajak-filter-option]:checked');
                    const minInput = editor.querySelector<HTMLInputElement>('[data-pajak-filter-min]');
                    const maxInput = editor.querySelector<HTMLInputElement>('[data-pajak-filter-max]');

                    const allCheckboxes = editor.querySelectorAll<HTMLInputElement>('[data-pajak-filter-option]');
                    const isCheckboxFilter = allCheckboxes.length > 0;
                    let entry: FilterEntry | null = null;

                    if (textInput) {
                        entry = { value: textInput.value, operator: operatorSelect?.value };
                    } else if (isCheckboxFilter) {
                        if (checkboxes.length > 0) {
                            entry = { value: Array.from(checkboxes).map((cb) => cb.value) };
                        }
                    } else if (minInput || maxInput) {
                        entry = { value: { from: minInput?.value || null, to: maxInput?.value || null } };
                    }

                    if (isCheckboxFilter && checkboxes.length === 0) {
                        if (editIndex !== null && state.filters[filterKey]) {
                            state.filters[filterKey].splice(editIndex, 1);

                            if (state.filters[filterKey].length === 0) {
                                delete state.filters[filterKey];
                            }
                        }
                    } else if (entry !== null) {
                        if (!state.filters[filterKey]) {
                            state.filters[filterKey] = [];
                        }

                        if (editIndex !== null && state.filters[filterKey][editIndex] !== undefined) {
                            state.filters[filterKey][editIndex] = entry;
                        } else {
                            state.filters[filterKey].push(entry);
                        }
                    }

                    delete editor.dataset.editIndex;
                    state.page = 1;
                    saveState(tableName, state);
                    editor.hidden = true;
                    updateFilterChips(wrapper, state);
                    fetchTable(wrapper, state);
                });
            }

            document.body.appendChild(editor);
        });

        function positionBelow(el: HTMLElement, target: HTMLElement): void {
            const rect = target.getBoundingClientRect();
            el.style.top = `${rect.bottom + 4}px`;
            el.style.left = `${rect.right - el.offsetWidth}px`;
        }

        toggleBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            const isOpen = !pickerMenu.hidden;
            closeAllFilterEditors(wrapper);

            if (isOpen) {
                pickerMenu.hidden = true;
            } else {
                pickerMenu.hidden = false;
                positionBelow(pickerMenu, toggleBtn);
            }
        });

        pickerMenu.querySelectorAll<HTMLElement>('[data-filter-picker-key]').forEach((item) => {
            const key = item.dataset.filterPickerKey ?? '';
            const editor = filterEditors[Array.from(filterEditors).findIndex((e) => e.dataset.pajakFilterKey === key)];

            if (editor) {
                item.addEventListener('click', (event) => {
                    event.stopPropagation();
                    pickerMenu.hidden = true;
                    closeAllFilterEditors(wrapper);
                    editor.hidden = false;
                    positionBelow(editor, toggleBtn);
                    upgradeFilterSelects(editor);
                });
            }
        });
    }

    if (chipsContainer) {
        const clearAllBtn = chipsContainer.querySelector<HTMLElement>('[data-pajak-table-filter-clear-all]');

        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', () => {
                const state = loadState(tableName);
                state.filters = {};
                state.page = 1;
                saveState(tableName, state);
                updateFilterChips(wrapper, state);
                fetchTable(wrapper, state);
            });
        }
    }
}

function populateFilterEditor(editor: HTMLElement, filter: FilterEntry): void {
    const textInput = editor.querySelector<HTMLInputElement>('[data-pajak-filter-value]');
    const operatorSelect = editor.querySelector<HTMLSelectElement>('[data-pajak-filter-operator]');
    const checkboxes = editor.querySelectorAll<HTMLInputElement>('[data-pajak-filter-option]');
    const minInput = editor.querySelector<HTMLInputElement>('[data-pajak-filter-min]');
    const maxInput = editor.querySelector<HTMLInputElement>('[data-pajak-filter-max]');

    if (textInput) {
        textInput.value = typeof filter.value === 'string' ? filter.value : '';

        if (operatorSelect && filter.operator) {
            operatorSelect.value = filter.operator;
        }
    } else if (checkboxes.length > 0) {
        const selected = Array.isArray(filter.value) ? (filter.value as string[]) : [];
        checkboxes.forEach((cb) => {
            cb.checked = selected.includes(cb.value);
        });
    } else if (minInput || maxInput) {
        const range = filter.value as { from?: string | null; to?: string | null } | null;

        if (minInput) {
            minInput.value = range?.from ?? '';
        }

        if (maxInput) {
            maxInput.value = range?.to ?? '';
        }
    }
}

function chipValueSummary(editor: HTMLElement | null, filter: FilterEntry): string {
    if (!editor) {
        return '';
    }

    const textInput = editor.querySelector('[data-pajak-filter-value]');
    const checkboxes = editor.querySelectorAll<HTMLInputElement>('[data-pajak-filter-option]');
    const minInput = editor.querySelector('[data-pajak-filter-min]');
    const maxInput = editor.querySelector('[data-pajak-filter-max]');

    if (textInput) {
        return typeof filter.value === 'string' && filter.value ? `"${filter.value}"` : '';
    }

    if (checkboxes.length > 0) {
        const selected = Array.isArray(filter.value) ? (filter.value as string[]) : [];
        const labels: string[] = [];

        checkboxes.forEach((cb) => {
            if (selected.includes(cb.value)) {
                const labelEl = cb.closest('label')?.querySelector('.pajak-checkbox__label');
                labels.push(labelEl?.textContent?.trim() ?? cb.value);
            }
        });

        if (labels.length === 0) {
            return '';
        }

        if (labels.length <= 2) {
            return labels.join(', ');
        }

        return `${labels[0]} +${labels.length - 1}`;
    }

    if (minInput || maxInput) {
        const range = filter.value as { from?: string | null; to?: string | null } | null;
        const from = range?.from ?? null;
        const to = range?.to ?? null;

        if (from && to) {
            return `${from} – ${to}`;
        }

        if (from) {
            return `≥ ${from}`;
        }

        if (to) {
            return `≤ ${to}`;
        }
    }

    return '';
}

function updateFilterChips(wrapper: HTMLElement, state: TableState): void {
    const chipsContainer = wrapper.querySelector<HTMLElement>('[data-pajak-table-filter-chips]');
    const chipsList = wrapper.querySelector<HTMLElement>('[data-pajak-table-chips-list]');

    if (!chipsContainer || !chipsList) {
        return;
    }

    chipsList.innerHTML = '';

    const totalEntries = Object.values(state.filters).reduce((sum, entries) => sum + entries.length, 0);

    if (totalEntries === 0) {
        chipsContainer.hidden = true;

        return;
    }

    chipsContainer.hidden = false;

    Object.keys(state.filters).forEach((key) => {
        const entries = state.filters[key] ?? [];

        entries.forEach((entry, index) => {
            const chip = document.createElement('span');
            chip.className = 'pajak-table-filter-chip';

            const editor = document.querySelector<HTMLElement>(`[data-pajak-filter-key="${key}"]`);
            const labelEl = editor?.querySelector<HTMLElement>('.pajak-table-filter-editor__label');
            const humanLabel = labelEl?.textContent?.trim() ?? key;
            const summary = chipValueSummary(editor, entry);

            const textNode = document.createElement('span');
            textNode.className = 'pajak-table-filter-chip__text';

            const strong = document.createElement('strong');
            strong.textContent = `${humanLabel}:`;
            textNode.appendChild(strong);

            if (summary) {
                textNode.appendChild(document.createTextNode(` ${summary}`));
            }

            chip.appendChild(textNode);

            const editBtn = document.createElement('button');
            editBtn.type = 'button';
            editBtn.className = 'pajak-table-filter-chip__edit';
            editBtn.setAttribute('aria-label', 'Edit filter');
            editBtn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>';

            if (editor) {
                editBtn.addEventListener('click', (event) => {
                    event.stopPropagation();
                    closeAllFilterEditors();
                    populateFilterEditor(editor, entry);
                    editor.dataset.editIndex = String(index);
                    editor.hidden = false;
                    const rect = chip.getBoundingClientRect();
                    editor.style.top = `${rect.bottom + 4}px`;
                    editor.style.left = `${rect.left}px`;
                    upgradeFilterSelects(editor);
                });
            }

            chip.appendChild(editBtn);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'pajak-table-filter-chip__remove';
            removeBtn.setAttribute('aria-label', 'Remove filter');
            removeBtn.innerHTML = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
            removeBtn.addEventListener('click', () => {
                const current = loadState(wrapper.dataset.tableName ?? '');
                const filterEntries = current.filters[key];

                if (!filterEntries) {
                    return;
                }

                filterEntries.splice(index, 1);

                if (filterEntries.length === 0) {
                    delete current.filters[key];
                }

                current.page = 1;
                saveState(wrapper.dataset.tableName ?? '', current);
                updateFilterChips(wrapper, current);
                fetchTable(wrapper, current);
            });

            chip.appendChild(removeBtn);
            chipsList.appendChild(chip);
        });
    });
}

function initBulkSelection(wrapper: HTMLElement, tableName: string): void {
    const selectAll = wrapper.querySelector<HTMLInputElement>('[data-pajak-table-select-all]');
    const bulkBar = wrapper.querySelector<HTMLElement>('[data-pajak-table-bulk-bar]');
    const bulkCount = wrapper.querySelector<HTMLElement>('[data-pajak-table-bulk-count]');

    function getRowCheckboxes(): NodeListOf<HTMLInputElement> {
        return wrapper.querySelectorAll<HTMLInputElement>('[data-pajak-table-row-check]');
    }

    function updateSelectAllState(): void {
        if (!selectAll) {
            return;
        }

        const all = Array.from(getRowCheckboxes());
        const checkedCount = all.filter((cb) => cb.checked).length;

        if (checkedCount === 0) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        } else if (checkedCount === all.length) {
            selectAll.checked = true;
            selectAll.indeterminate = false;
        } else {
            selectAll.checked = false;
            selectAll.indeterminate = true;
        }
    }

    function updateBulkBar(): void {
        const checked = Array.from(getRowCheckboxes()).filter((cb) => cb.checked);

        updateSelectAllState();

        if (!bulkBar) {
            return;
        }

        if (checked.length > 0) {
            bulkBar.hidden = false;

            if (bulkCount) {
                const template = bulkCount.dataset.template ?? ':count selected';
                bulkCount.textContent = template.replace(':count', String(checked.length));
            }
        } else {
            bulkBar.hidden = true;
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', () => {
            const shouldCheck = !selectAll.indeterminate && selectAll.checked;
            getRowCheckboxes().forEach((cb) => {
                cb.checked = shouldCheck;
            });
            selectAll.indeterminate = false;
            updateBulkBar();
        });
    }

    wrapper.addEventListener('change', (event) => {
        if ((event.target as HTMLElement).dataset.pajakTableRowCheck !== undefined) {
            updateBulkBar();
        }
    });

    const clearBtn = wrapper.querySelector<HTMLElement>('[data-pajak-table-bulk-clear]');

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            getRowCheckboxes().forEach((cb) => {
                cb.checked = false;
            });

            if (selectAll) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }

            updateBulkBar();
        });
    }

    wrapper.querySelectorAll<HTMLElement>('[data-pajak-table-bulk-action]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const actionKey = btn.dataset.pajakTableBulkAction ?? '';
            const selectedIds = Array.from(getRowCheckboxes())
                .filter((cb) => cb.checked)
                .map((cb) => cb.value);

            wrapper.dispatchEvent(new CustomEvent('pajak:table:bulk-action', {
                bubbles: true,
                detail: { action: actionKey, ids: selectedIds, tableName },
            }));
        });
    });
}

function initConfirmDialogs(wrapper: HTMLElement): void {
    const pendingConfirm: { current: PendingConfirm | null } = { current: null };
    const tableId = wrapper.id;

    document.addEventListener('click', (event) => {
        const trigger = (event.target as HTMLElement).closest<HTMLElement>('[data-pajak-table-confirm]');

        if (!trigger) {
            return;
        }

        // Accept clicks from inside the wrapper or from overflow-menu items reparented to body
        const fromWrapper = wrapper.contains(trigger);
        const fromOverflow = trigger.dataset.pajakTableOwner === tableId;

        if (!fromWrapper && !fromOverflow) {
            return;
        }

        const key = trigger.dataset.pajakTableConfirm ?? '';
        const url = trigger.dataset.pajakTableConfirmUrl ?? '';
        const method = trigger.dataset.pajakTableConfirmMethod ?? 'POST';

        pendingConfirm.current = { key, url, method };

        const dialog = wrapper.querySelector<HTMLDialogElement>(`[data-pajak-table-confirm-dialog="${key}"]`);
        dialog?.showModal();
    });

    wrapper.querySelectorAll<HTMLElement>('[data-pajak-table-confirm-dialog]').forEach((dialog) => {
        const cancelBtn = dialog.querySelector<HTMLElement>('[data-pajak-table-confirm-cancel]');
        const submitBtn = dialog.querySelector<HTMLElement>('[data-pajak-table-confirm-submit]');

        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                (dialog as HTMLDialogElement).close();
                pendingConfirm.current = null;
            });
        }

        if (submitBtn) {
            submitBtn.addEventListener('click', async () => {
                (dialog as HTMLDialogElement).close();

                if (!pendingConfirm.current) {
                    return;
                }

                await submitFormAction(pendingConfirm.current.url, pendingConfirm.current.method);
                pendingConfirm.current = null;
            });
        }
    });
}

async function submitFormAction(url: string, method: string): Promise<void> {
    const csrfToken = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '';
    const upperMethod = method.toUpperCase();
    const httpMethod = ['GET', 'POST'].includes(upperMethod) ? upperMethod : 'POST';

    const form = document.createElement('form');
    form.method = httpMethod;
    form.action = url;

    if (httpMethod !== upperMethod) {
        const spoof = document.createElement('input');
        spoof.type = 'hidden';
        spoof.name = '_method';
        spoof.value = upperMethod;
        form.appendChild(spoof);
    }

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = csrfToken;
    form.appendChild(csrf);

    document.body.appendChild(form);
    form.submit();
}

function initFormActions(wrapper: HTMLElement): void {
    const tableId = wrapper.id;

    document.addEventListener('click', async (event) => {
        const btn = (event.target as HTMLElement).closest<HTMLElement>('[data-pajak-table-form-action]');

        if (!btn) {
            return;
        }

        // Accept clicks from inside the wrapper or from overflow-menu items reparented to body
        const fromWrapper = wrapper.contains(btn);
        const fromOverflow = btn.dataset.pajakTableOwner === tableId;

        if (!fromWrapper && !fromOverflow) {
            return;
        }

        const url = btn.dataset.pajakTableFormAction ?? '';
        const method = btn.dataset.pajakTableFormMethod ?? 'POST';

        await submitFormAction(url, method);
    });
}

function positionOverflowMenu(menu: HTMLElement, trigger: HTMLElement): void {
    const rect = trigger.getBoundingClientRect();
    const menuHeight = menu.offsetHeight;
    const spaceBelow = window.innerHeight - rect.bottom;

    menu.style.right = 'auto';
    menu.style.left = `${rect.right - menu.offsetWidth}px`;

    if (spaceBelow < menuHeight + 8 && rect.top > menuHeight + 8) {
        menu.style.top = `${rect.top - menuHeight - 4}px`;
    } else {
        menu.style.top = `${rect.bottom + 4}px`;
    }
}

function reinitOverflowMenus(wrapper: HTMLElement): void {
    document.querySelectorAll<HTMLElement>(`[data-pajak-table-overflow-menu][data-pajak-table-owner="${wrapper.id}"]`).forEach((m) => {
        m.remove();
    });

    initOverflowMenus(wrapper);
}

function initOverflowMenus(wrapper: HTMLElement): void {
    wrapper.querySelectorAll<HTMLElement>('[data-pajak-table-overflow]').forEach((overflow) => {
        const trigger = overflow.querySelector<HTMLElement>('[data-pajak-table-overflow-trigger]');
        const menu = overflow.querySelector<HTMLElement>('[data-pajak-table-overflow-menu]');

        if (!trigger || !menu) {
            return;
        }

        // Stamp every actionable item so document-level listeners can scope to this table
        menu.querySelectorAll<HTMLElement>(
            '[data-pajak-table-confirm], [data-pajak-table-form-action]',
        ).forEach((item) => {
            item.dataset.pajakTableOwner = wrapper.id;
        });

        menu.dataset.pajakTableOwner = wrapper.id;
        document.body.appendChild(menu);

        trigger.addEventListener('click', (event) => {
            event.stopPropagation();
            const isOpen = !menu.hidden;

            document.querySelectorAll<HTMLElement>('[data-pajak-table-overflow-menu]').forEach((m) => {
                m.hidden = true;
            });

            if (!isOpen) {
                menu.hidden = false;
                positionOverflowMenu(menu, trigger);
            }
        });
    });
}

function initColumnVisibility(wrapper: HTMLElement, tableName: string): void {
    const toggleBtn = wrapper.querySelector<HTMLElement>('[data-pajak-table-columns-toggle]');

    if (!toggleBtn) {
        return;
    }

    const menu = document.createElement('div');
    menu.className = 'pajak-table-columns-menu';
    menu.hidden = true;

    const state = loadState(tableName);
    const columns = wrapper.querySelectorAll<HTMLElement>('[data-pajak-table-column]');
    const allColKeys = Array.from(columns).map((c) => c.dataset.columnKey ?? '');

    columns.forEach((col) => {
        const key = col.dataset.columnKey ?? '';
        const label = col.querySelector('.pajak-table__sort-btn')?.textContent?.trim()
            ?? col.textContent?.trim()
            ?? key;

        const item = document.createElement('label');
        item.className = 'pajak-table-columns-menu__item';

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'pajak-table-columns-menu__checkbox';
        checkbox.checked = !state.visibleColumns.length || state.visibleColumns.includes(key);
        checkbox.addEventListener('change', () => {
            const currentState = loadState(tableName);

            currentState.visibleColumns = allColKeys.filter((colKey) => {
                const cb = menu.querySelector<HTMLInputElement>(`input[data-col="${colKey}"]`);

                return cb ? cb.checked : true;
            });

            saveState(tableName, currentState);
            applyColumnVisibility(wrapper, currentState.visibleColumns);
        });

        checkbox.dataset.col = key;
        item.appendChild(checkbox);
        item.appendChild(document.createTextNode(label));
        menu.appendChild(item);
    });

    toggleBtn.parentElement?.style.setProperty('position', 'relative');
    toggleBtn.insertAdjacentElement('afterend', menu);

    toggleBtn.addEventListener('click', (event) => {
        event.stopPropagation();
        menu.hidden = !menu.hidden;
    });

    if (state.visibleColumns.length > 0) {
        applyColumnVisibility(wrapper, state.visibleColumns);
    }
}

function applyColumnVisibility(wrapper: HTMLElement, visibleColumns: string[]): void {
    if (!visibleColumns.length) {
        return;
    }

    const visibleSet = new Set(visibleColumns);

    wrapper.querySelectorAll<HTMLElement>('[data-pajak-table-column], [data-column-key]').forEach((el) => {
        const key = el.dataset.columnKey ?? '';
        el.hidden = !visibleSet.has(key);
    });
}

function hasPersistedState(state: TableState): boolean {
    return (
        state.search !== null
        || state.sort !== null
        || Object.keys(state.filters).length > 0
        || state.page > 1
        || state.perPage !== null
    );
}

function initTable(wrapper: HTMLElement): void {
    if (wrapper.dataset.pajakTableInit) {
        return;
    }

    wrapper.dataset.pajakTableInit = '1';

    const tableName = wrapper.dataset.tableName ?? '';

    if (!tableName) {
        return;
    }

    const state = loadState(tableName);

    initSearch(wrapper, tableName);
    initSort(wrapper, tableName);
    bindPaginationButtons(wrapper);
    initPerPage(wrapper, tableName);
    initFilters(wrapper, tableName);
    initBulkSelection(wrapper, tableName);
    initConfirmDialogs(wrapper);
    initFormActions(wrapper);
    initOverflowMenus(wrapper);
    initColumnVisibility(wrapper, tableName);
    ensureDocumentClickListener();

    if (Object.keys(state.filters).length > 0) {
        updateFilterChips(wrapper, state);
    }

    if (hasPersistedState(state)) {
        restoreSearchInput(wrapper, state);
        wrapper.classList.add('is-restoring');
        fetchTable(wrapper, state);
    }
}

function initAll(): void {
    document.querySelectorAll<HTMLElement>('[data-pajak-table]').forEach(initTable);
}

export const PajakTable = {
    initAll,
    initTable,
} as const;

window.Pajak = { ...window.Pajak, PajakTable };
