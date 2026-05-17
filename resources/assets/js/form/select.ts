import { type ComponentInstance, createRegistry } from '../registry';

interface OptionData {
    value: string;
    label: string;
    meta: string | null;
    selected: boolean;
    disabled: boolean;
}

interface GroupData {
    label: string | null; // null = ungrouped
    options: OptionData[];
}

const registry = createRegistry<ComponentInstance>();

// ─── SVG helpers ────────────────────────────────────────────────────────────

function svgCheck(): SVGElement {
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('class', 'pajak-select__option-check');
    svg.setAttribute('width', '14');
    svg.setAttribute('height', '14');
    svg.setAttribute('viewBox', '0 0 24 24');
    svg.setAttribute('fill', 'none');
    svg.setAttribute('stroke', 'currentColor');
    svg.setAttribute('stroke-width', '3');
    svg.setAttribute('stroke-linecap', 'round');
    svg.setAttribute('stroke-linejoin', 'round');
    svg.innerHTML = '<polyline points="20 6 9 17 4 12"/>';
    return svg;
}

function svgX(cls: string, size = 10): SVGElement {
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('class', cls);
    svg.setAttribute('width', String(size));
    svg.setAttribute('height', String(size));
    svg.setAttribute('viewBox', '0 0 24 24');
    svg.setAttribute('fill', 'none');
    svg.setAttribute('stroke', 'currentColor');
    svg.setAttribute('stroke-width', '3');
    svg.setAttribute('stroke-linecap', 'round');
    svg.setAttribute('stroke-linejoin', 'round');
    svg.innerHTML = '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>';
    return svg;
}

// ─── Read native <select> structure ─────────────────────────────────────────

function readGroups(native: HTMLSelectElement): GroupData[] {
    const groups: GroupData[] = [];

    Array.from(native.children).forEach((child) => {
        if (child.tagName === 'OPTGROUP') {
            const grp = child as HTMLOptGroupElement;
            groups.push({
                label: grp.label,
                options: Array.from(grp.querySelectorAll<HTMLOptionElement>('option')).map(optToData),
            });
        } else if (child.tagName === 'OPTION') {
            const opt = child as HTMLOptionElement;
            if (!opt.value) {
                return; // skip placeholder
            }
            let ungrouped = groups.find((g) => g.label === null);
            if (!ungrouped) {
                ungrouped = { label: null, options: [] };
                groups.push(ungrouped);
            }
            ungrouped.options.push(optToData(opt));
        }
    });

    return groups;
}

function optToData(opt: HTMLOptionElement): OptionData {
    return {
        value: opt.value,
        label: opt.text,
        meta: opt.dataset.meta ?? null,
        selected: opt.selected,
        disabled: opt.disabled,
    };
}

// ─── Build a single option element ──────────────────────────────────────────

function buildOption(
    opt: OptionData,
    isMultiple: boolean,
    onClick: (value: string) => void,
): HTMLElement {
    const el = document.createElement('div');
    el.className = 'pajak-select__option' + (opt.selected ? ' is-selected' : '');
    el.setAttribute('role', 'option');
    el.setAttribute('aria-selected', String(opt.selected));
    el.setAttribute('data-value', opt.value);
    if (opt.disabled) {
        el.setAttribute('aria-disabled', 'true');
        el.style.opacity = '0.45';
        el.style.cursor = 'not-allowed';
    }

    const labelSpan = document.createElement('span');
    labelSpan.textContent = opt.label;
    el.appendChild(labelSpan);

    if (opt.meta) {
        const meta = document.createElement('span');
        meta.className = 'pajak-select__option-meta';
        meta.textContent = opt.meta;
        el.appendChild(meta);
    }

    if (opt.selected && !isMultiple) {
        el.appendChild(svgCheck());
    }

    if (!opt.disabled) {
        el.addEventListener('click', (e) => {
            e.stopPropagation();
            onClick(opt.value);
        });
    }

    return el;
}

// ─── Dropdown portal helpers ─────────────────────────────────────────────────

function positionDropdown(trigger: HTMLElement, dropdown: HTMLElement): void {
    const rect = trigger.getBoundingClientRect();
    const gap = 4; // var(--space-1) equivalent
    const dropdownHeight = dropdown.offsetHeight;
    const spaceBelow = window.innerHeight - rect.bottom;
    const openUpward = spaceBelow < dropdownHeight + gap && rect.top > dropdownHeight + gap;

    dropdown.style.width = sprintf('%spx', String(Math.round(rect.width)));
    dropdown.style.left = sprintf('%spx', String(Math.round(rect.left)));

    if (openUpward) {
        dropdown.style.top = sprintf('%spx', String(Math.round(rect.top - dropdownHeight - gap)));
        dropdown.style.bottom = 'auto';
    } else {
        dropdown.style.top = sprintf('%spx', String(Math.round(rect.bottom + gap)));
        dropdown.style.bottom = 'auto';
    }
}

function attachDropdown(dropdown: HTMLElement): void {
    document.body.appendChild(dropdown);
}

function detachDropdown(wrap: HTMLElement, dropdown: HTMLElement): void {
    wrap.appendChild(dropdown);
}

// ─── Single-select upgrade ───────────────────────────────────────────────────

function upgradeSingle(
    wrap: HTMLElement,
    native: HTMLSelectElement,
    trigger: HTMLElement,
    valueEl: HTMLElement,
    dropdown: HTMLElement,
    searchable: boolean,
    searchPlaceholder: string,
): ComponentInstance {
    const dropdownId = sprintf('pajak-select-%s', Math.random().toString(36).slice(2));
    dropdown.setAttribute('id', dropdownId);

    wrap.classList.add('is-upgraded');

    const searchInput = wrap.querySelector<HTMLInputElement>('.pajak-select__search-input');
    let filterText = '';

    const syncValue = (): void => {
        const selected = Array.from(native.options).find((o) => o.selected && o.value);
        if (selected) {
            valueEl.textContent = selected.text;
            valueEl.classList.remove('is-placeholder');
        } else {
            valueEl.textContent = wrap.dataset.placeholder ?? '';
            valueEl.classList.add('is-placeholder');
        }
    };

    const populateDropdown = (): void => {
        // Remove existing options but keep search bar
        Array.from(dropdown.children).forEach((child) => {
            if (!child.classList.contains('pajak-select__search')) {
                child.remove();
            }
        });

        const groups = readGroups(native);
        const query = filterText.toLowerCase();
        let visibleCount = 0;

        groups.forEach((group, groupIdx) => {
            const filtered = query
                ? group.options.filter((o) => o.label.toLowerCase().includes(query))
                : group.options;

            if (filtered.length === 0) {
                return;
            }
            visibleCount += filtered.length;

            if (groupIdx > 0) {
                const divider = document.createElement('div');
                divider.className = 'pajak-select__divider';
                dropdown.appendChild(divider);
            }

            if (group.label !== null) {
                const gl = document.createElement('div');
                gl.className = 'pajak-select__group-label';
                gl.textContent = group.label;
                dropdown.appendChild(gl);
            }

            filtered.forEach((opt) => {
                dropdown.appendChild(
                    buildOption(opt, false, (val) => {
                        native.value = val;
                        native.dispatchEvent(new Event('change', { bubbles: true }));
                        syncValue();
                        close();
                    }),
                );
            });
        });

        if (visibleCount === 0) {
            const empty = document.createElement('div');
            empty.className = 'pajak-select__empty';
            empty.textContent = searchPlaceholder;
            dropdown.appendChild(empty);
        }
    };

    const reposition = (): void => positionDropdown(trigger, dropdown);

    const open = (): void => {
        if (native.disabled) {
            return;
        }
        filterText = '';
        if (searchInput) {
            searchInput.value = '';
        }
        populateDropdown();
        attachDropdown(dropdown);
        dropdown.removeAttribute('hidden');
        positionDropdown(trigger, dropdown);
        wrap.classList.add('is-open');
        trigger.setAttribute('aria-expanded', 'true');
        window.addEventListener('scroll', reposition, { passive: true, capture: true });
        window.addEventListener('resize', reposition, { passive: true });
        if (searchInput) {
            setTimeout(() => searchInput.focus(), 0);
        }
    };

    const close = (): void => {
        dropdown.setAttribute('hidden', '');
        detachDropdown(wrap, dropdown);
        wrap.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
        window.removeEventListener('scroll', reposition, true);
        window.removeEventListener('resize', reposition);
    };

    const toggle = (): void => {
        wrap.classList.contains('is-open') ? close() : open();
    };

    const onDocClick = (e: MouseEvent): void => {
        const target = e.target as Node;
        if (!wrap.contains(target) && !dropdown.contains(target)) {
            close();
        }
    };

    const onTriggerKeydown = (e: KeyboardEvent): void => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggle();
        }
        if (e.key === 'Escape') {
            close();
        }
    };

    trigger.removeAttribute('aria-hidden');
    trigger.setAttribute('role', 'combobox');
    trigger.setAttribute('aria-expanded', 'false');
    trigger.setAttribute('aria-haspopup', 'listbox');
    trigger.setAttribute('aria-controls', dropdownId);
    trigger.setAttribute('tabindex', native.disabled ? '-1' : '0');

    trigger.addEventListener('click', toggle);
    trigger.addEventListener('keydown', onTriggerKeydown);
    document.addEventListener('click', onDocClick);

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            filterText = searchInput.value;
            populateDropdown();
        });
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                close();
            }
        });
    }

    syncValue();

    return {
        destroy(): void {
            trigger.removeEventListener('click', toggle);
            trigger.removeEventListener('keydown', onTriggerKeydown);
            document.removeEventListener('click', onDocClick);
            if (wrap.classList.contains('is-open')) {
                window.removeEventListener('scroll', reposition, true);
                window.removeEventListener('resize', reposition);
                detachDropdown(wrap, dropdown);
            }
            wrap.classList.remove('is-upgraded', 'is-open');
            registry.delete(wrap);
        },
    };
}

// ─── Multi-select upgrade ────────────────────────────────────────────────────

function upgradeMulti(
    wrap: HTMLElement,
    native: HTMLSelectElement,
    trigger: HTMLElement,
    chipsContainer: HTMLElement,
    chipInput: HTMLInputElement,
    dropdown: HTMLElement,
): ComponentInstance {
    const dropdownId = sprintf('pajak-select-%s', Math.random().toString(36).slice(2));
    dropdown.setAttribute('id', dropdownId);

    wrap.classList.add('is-upgraded');

    let filterText = '';

    const getSelectedValues = (): string[] =>
        Array.from(native.options)
            .filter((o) => o.selected && o.value)
            .map((o) => o.value);

    const buildChip = (opt: HTMLOptionElement): HTMLElement => {
        const chip = document.createElement('span');
        chip.className = 'pajak-select__chip';
        chip.dataset.value = opt.value;

        const label = document.createElement('span');
        label.className = 'pajak-select__chip-label';
        label.textContent = opt.text;
        chip.appendChild(label);

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'pajak-select__chip-remove';
        btn.setAttribute('aria-label', sprintf('Remove %s', opt.text));
        btn.appendChild(svgX(''));
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            opt.selected = false;
            native.dispatchEvent(new Event('change', { bubbles: true }));
            syncChips();
            if (wrap.classList.contains('is-open')) {
                populateDropdown();
            }
        });
        chip.appendChild(btn);

        return chip;
    };

    const syncChips = (): void => {
        chipsContainer.innerHTML = '';
        Array.from(native.options)
            .filter((o) => o.selected && o.value)
            .forEach((o) => chipsContainer.appendChild(buildChip(o)));

        const hasSelection = getSelectedValues().length > 0;
        chipInput.placeholder = hasSelection ? '' : (wrap.dataset.placeholder ?? '');
    };

    const populateDropdown = (): void => {
        dropdown.innerHTML = '';
        const query = filterText.toLowerCase();
        const groups = readGroups(native);
        let visibleCount = 0;

        groups.forEach((group, groupIdx) => {
            const filtered = query
                ? group.options.filter((o) => o.label.toLowerCase().includes(query))
                : group.options;

            if (filtered.length === 0) {
                return;
            }
            visibleCount += filtered.length;

            if (groupIdx > 0) {
                const divider = document.createElement('div');
                divider.className = 'pajak-select__divider';
                dropdown.appendChild(divider);
            }

            if (group.label !== null) {
                const gl = document.createElement('div');
                gl.className = 'pajak-select__group-label';
                gl.textContent = group.label;
                dropdown.appendChild(gl);
            }

            filtered.forEach((opt) => {
                dropdown.appendChild(
                    buildOption(opt, true, (val) => {
                        const nativeOpt = Array.from(native.options).find((o) => o.value === val);
                        if (nativeOpt) {
                            nativeOpt.selected = !nativeOpt.selected;
                            native.dispatchEvent(new Event('change', { bubbles: true }));
                            filterText = '';
                            chipInput.value = '';
                            syncChips();
                            populateDropdown(); // re-render to update checkmarks
                        }
                    }),
                );
            });
        });

        if (visibleCount === 0) {
            const empty = document.createElement('div');
            empty.className = 'pajak-select__empty';
            empty.textContent = 'No options found';
            dropdown.appendChild(empty);
        }
    };

    const reposition = (): void => positionDropdown(trigger, dropdown);

    const open = (): void => {
        if (native.disabled) {
            return;
        }
        populateDropdown();
        attachDropdown(dropdown);
        dropdown.removeAttribute('hidden');
        positionDropdown(trigger, dropdown);
        wrap.classList.add('is-open');
        trigger.setAttribute('aria-expanded', 'true');
        window.addEventListener('scroll', reposition, { passive: true, capture: true });
        window.addEventListener('resize', reposition, { passive: true });
        chipInput.focus();
    };

    const close = (): void => {
        dropdown.setAttribute('hidden', '');
        detachDropdown(wrap, dropdown);
        wrap.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
        window.removeEventListener('scroll', reposition, true);
        window.removeEventListener('resize', reposition);
        filterText = '';
        chipInput.value = '';
    };

    const onDocClick = (e: MouseEvent): void => {
        const target = e.target as Node;
        if (!wrap.contains(target) && !dropdown.contains(target)) {
            close();
        }
    };

    trigger.removeAttribute('aria-hidden');
    trigger.setAttribute('role', 'combobox');
    trigger.setAttribute('aria-expanded', 'false');
    trigger.setAttribute('aria-haspopup', 'listbox');
    trigger.setAttribute('aria-controls', dropdownId);
    trigger.setAttribute('aria-multiselectable', 'true');

    trigger.addEventListener('click', (e) => {
        // Don't re-open when clicking a chip remove button
        if ((e.target as HTMLElement).closest('.pajak-select__chip-remove')) {
            return;
        }
        if (wrap.classList.contains('is-open')) {
            if (!(e.target as HTMLElement).closest('.pajak-select__chips') &&
                e.target !== chipInput) {
                close();
            }
        } else {
            open();
        }
    });

    chipInput.addEventListener('input', () => {
        filterText = chipInput.value;
        if (!wrap.classList.contains('is-open')) {
            open();
        } else {
            populateDropdown();
        }
    });

    chipInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            close();
        }
        if (e.key === 'Backspace' && chipInput.value === '') {
            // Remove last chip on backspace with empty input
            const selected = Array.from(native.options).filter((o) => o.selected && o.value);
            const last = selected[selected.length - 1];
            if (last) {
                last.selected = false;
                native.dispatchEvent(new Event('change', { bubbles: true }));
                syncChips();
                if (wrap.classList.contains('is-open')) {
                    populateDropdown();
                }
            }
        }
    });

    document.addEventListener('click', onDocClick);
    syncChips();

    return {
        destroy(): void {
            document.removeEventListener('click', onDocClick);
            if (wrap.classList.contains('is-open')) {
                window.removeEventListener('scroll', reposition, true);
                window.removeEventListener('resize', reposition);
                detachDropdown(wrap, dropdown);
            }
            wrap.classList.remove('is-upgraded', 'is-open');
            registry.delete(wrap);
        },
    };
}

// ─── sprintf-lite (single %s substitution) ──────────────────────────────────

function sprintf(template: string, ...args: string[]): string {
    let i = 0;
    return template.replace(/%s/g, () => args[i++] ?? '');
}

// ─── Main upgrade dispatcher ─────────────────────────────────────────────────

function upgradeSelect(wrap: HTMLElement): ComponentInstance {
    const existing = registry.get(wrap);
    if (existing) {
        return existing;
    }

    const native = wrap.querySelector<HTMLSelectElement>('.pajak-select__native');
    const dropdown = wrap.querySelector<HTMLElement>('.pajak-select__dropdown');

    if (!native || !dropdown) {
        const noop: ComponentInstance = { destroy: () => {} };
        registry.set(wrap, noop);
        return noop;
    }

    const isMultiple = wrap.hasAttribute('data-multiple');
    const isSearchable = wrap.hasAttribute('data-searchable');
    const searchPlaceholder = wrap.dataset.searchPlaceholder ?? 'No options found';

    let instance: ComponentInstance;

    if (isMultiple) {
        const trigger = wrap.querySelector<HTMLElement>('.pajak-select__trigger--multi');
        const chipsContainer = wrap.querySelector<HTMLElement>('.pajak-select__chips');
        const chipInput = wrap.querySelector<HTMLInputElement>('.pajak-select__chip-input');

        if (!trigger || !chipsContainer || !chipInput) {
            const noop: ComponentInstance = { destroy: () => {} };
            registry.set(wrap, noop);
            return noop;
        }

        instance = upgradeMulti(wrap, native, trigger, chipsContainer, chipInput, dropdown);
    } else {
        const trigger = wrap.querySelector<HTMLElement>('.pajak-select__trigger:not(.pajak-select__trigger--multi)');
        const valueEl = wrap.querySelector<HTMLElement>('.pajak-select__value');

        if (!trigger || !valueEl) {
            const noop: ComponentInstance = { destroy: () => {} };
            registry.set(wrap, noop);
            return noop;
        }

        instance = upgradeSingle(wrap, native, trigger, valueEl, dropdown, isSearchable, searchPlaceholder);
    }

    registry.set(wrap, instance);
    return instance;
}

// ─── Public API ──────────────────────────────────────────────────────────────

export const PajakSelect = {
    init(el: HTMLElement): ComponentInstance {
        return upgradeSelect(el);
    },

    initAll(root: ParentNode = document): ComponentInstance[] {
        return Array.from(root.querySelectorAll<HTMLElement>('[data-pajak-select]')).map(upgradeSelect);
    },
};
