import { createRegistry } from '../registry';

interface PajakCheckboxGroupInstance {
    get values(): string[];
    selectAll(): void;
    clear(): void;
    sync(): void;
    destroy(): void;
}

const registry = createRegistry<PajakCheckboxGroupInstance>();

function upgradeGroup(group: HTMLElement): PajakCheckboxGroupInstance {
    const existing = registry.get(group);
    if (existing) {
        return existing;
    }

    const selectAllInput = group.querySelector<HTMLInputElement>('input[data-pajak-checkbox-select-all]');
    const items = (): HTMLInputElement[] =>
        Array.from(group.querySelectorAll<HTMLInputElement>('.pajak-checkbox-group__items input[type="checkbox"]'));
    const enabledItems = (): HTMLInputElement[] => items().filter((input) => !input.disabled);

    const sync = (): void => {
        if (!selectAllInput) {
            return;
        }

        const enabled = enabledItems();
        const checkedCount = enabled.filter((input) => input.checked).length;

        selectAllInput.checked = enabled.length > 0 && checkedCount === enabled.length;
        selectAllInput.indeterminate = checkedCount > 0 && checkedCount < enabled.length;
    };

    const emit = (): void => {
        const all = items();

        group.dispatchEvent(new CustomEvent('pajak:checkbox-group', {
            bubbles: true,
            detail: { checked: all.filter((input) => input.checked).length, total: all.length },
        }));
    };

    // Items are updated programmatically and notified with a real `change`, so outside listeners
    // still hear about them; this group ignores its own select-all input, so there is no loop.
    const setAll = (checked: boolean): void => {
        enabledItems().forEach((input) => {
            if (input.checked !== checked) {
                input.checked = checked;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });

        sync();
        emit();
    };

    const onChange = (event: Event): void => {
        const target = event.target as HTMLInputElement | null;
        if (!target || target.type !== 'checkbox') {
            return;
        }

        if (target === selectAllInput) {
            setAll(selectAllInput.checked);

            return;
        }

        sync();
        emit();
    };

    group.addEventListener('change', onChange);
    sync();

    const instance: PajakCheckboxGroupInstance = {
        get values(): string[] {
            return items().filter((input) => input.checked).map((input) => input.value);
        },
        selectAll: () => setAll(true),
        clear: () => setAll(false),
        sync,
        destroy(): void {
            group.removeEventListener('change', onChange);
            registry.delete(group);
        },
    };

    registry.set(group, instance);
    return instance;
}

export const PajakCheckboxGroup = {
    init(el: HTMLElement): PajakCheckboxGroupInstance {
        return upgradeGroup(el);
    },

    initAll(root: ParentNode = document): PajakCheckboxGroupInstance[] {
        return Array.from(root.querySelectorAll<HTMLElement>('[data-pajak-checkbox-group]')).map(upgradeGroup);
    },
};
