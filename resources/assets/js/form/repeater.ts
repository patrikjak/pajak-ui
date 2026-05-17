import { type ComponentInstance, createRegistry } from '../registry';

interface RepeaterInstance extends ComponentInstance {
    readonly rowCount: number;
    add(): void;
}

const registry = createRegistry<RepeaterInstance>();

function replacePlaceholders(el: Element, index: number, name: string): void {
    const walk = (node: Element): void => {
        for (const attr of Array.from(node.attributes)) {
            if (attr.value.includes('__INDEX__') || attr.value.includes('__NAME__')) {
                attr.value = attr.value.replaceAll('__INDEX__', String(index)).replaceAll('__NAME__', name);
                continue;
            }

            if (attr.name === 'name' && attr.value !== '' && !attr.value.includes('[')) {
                attr.value = `${name}[${index}][${attr.value}]`;
                continue;
            }

            if ((attr.name === 'id' || attr.name === 'for') && attr.value !== '' && !attr.value.includes('[')) {
                attr.value = `${name}_${index}_${attr.value}`;
            }
        }
        for (const child of Array.from(node.children)) {
            walk(child);
        }
    };

    walk(el);
}

function upgradeRepeater(wrap: HTMLElement): RepeaterInstance {
    const existing = registry.get(wrap);
    if (existing) {
        return existing;
    }

    const rowsContainer = wrap.querySelector<HTMLElement>('.pajak-repeater__rows');
    const template = wrap.querySelector<HTMLTemplateElement>('template.pajak-repeater__template');
    const addButton = wrap.querySelector<HTMLButtonElement>('.pajak-repeater__add');

    if (!rowsContainer || !template || !addButton) {
        const noop: RepeaterInstance = { rowCount: 0, add: () => {}, destroy: () => {} };
        registry.set(wrap, noop);
        return noop;
    }

    const min = parseInt(wrap.dataset.min ?? '0', 10);
    const max = wrap.dataset.max !== undefined ? parseInt(wrap.dataset.max, 10) : null;
    const name = wrap.dataset.name ?? '';

    let nextIndex = rowsContainer.querySelectorAll(':scope > .pajak-repeater__row').length;

    const getRows = (): HTMLElement[] =>
        Array.from(rowsContainer.querySelectorAll<HTMLElement>(':scope > .pajak-repeater__row'));

    const syncState = (): void => {
        const rows = getRows();
        const count = rows.length;

        // Remove buttons
        rows.forEach((row) => {
            const btn = row.querySelector<HTMLButtonElement>('.pajak-repeater__remove');
            if (!btn) {
                return;
            }
            const locked = count <= min;
            btn.disabled = locked;
            btn.setAttribute('aria-disabled', String(locked));
        });

        // Add button
        if (max !== null && count >= max) {
            addButton.setAttribute('hidden', '');
        } else {
            addButton.removeAttribute('hidden');
        }
    };

    const removeRow = (row: HTMLElement): void => {
        row.remove();
        wrap.dispatchEvent(new CustomEvent('pajak:repeater:remove', { bubbles: true }));
        syncState();
    };

    const bindRemove = (row: HTMLElement): void => {
        const btn = row.querySelector<HTMLButtonElement>('.pajak-repeater__remove');
        btn?.addEventListener('click', () => {
            if (getRows().length > min) {
                removeRow(row);
            }
        });
    };

    const addRow = (): void => {
        if (max !== null && getRows().length >= max) {
            return;
        }

        const clone = template.content.cloneNode(true) as DocumentFragment;
        const row = clone.querySelector<HTMLElement>('.pajak-repeater__row');
        if (!row) {
            return;
        }

        replacePlaceholders(row, nextIndex++, name);
        bindRemove(row);
        rowsContainer.appendChild(row);

        wrap.dispatchEvent(new CustomEvent('pajak:repeater:add', { bubbles: true }));
        syncState();
    };

    // Bind remove on existing rows
    getRows().forEach(bindRemove);

    addButton.addEventListener('click', addRow);

    syncState();

    const instance: RepeaterInstance = {
        get rowCount(): number {
            return getRows().length;
        },
        add(): void {
            addRow();
        },
        destroy(): void {
            addButton.removeEventListener('click', addRow);
            registry.delete(wrap);
        },
    };

    registry.set(wrap, instance);
    return instance;
}

export const PajakRepeater = {
    init(el: HTMLElement): RepeaterInstance {
        return upgradeRepeater(el);
    },

    initAll(root: ParentNode = document): RepeaterInstance[] {
        return Array.from(
            root.querySelectorAll<HTMLElement>('[data-pajak-repeater]'),
        ).map(upgradeRepeater);
    },
};
