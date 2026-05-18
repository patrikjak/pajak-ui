import { createRegistry } from '../registry';

interface PajakCheckboxInstance {
    get indeterminate(): boolean;
    set indeterminate(value: boolean);
    get checked(): boolean;
    set checked(value: boolean);
    destroy(): void;
}

const registry = createRegistry<PajakCheckboxInstance>();

function upgradeCheckbox(label: HTMLElement): PajakCheckboxInstance {
    const existing = registry.get(label);
    if (existing) {
        return existing;
    }

    const input = label.querySelector<HTMLInputElement>('.pajak-checkbox__input');
    if (!input) {
        return {
            get indeterminate() { return false; },
            set indeterminate(_) {},
            get checked() { return false; },
            set checked(_) {},
            destroy() {},
        };
    }

    const onChange = (): void => {
        label.dispatchEvent(new CustomEvent('pajak:checkbox', {
            bubbles: true,
            detail: { checked: input.checked, indeterminate: input.indeterminate },
        }));
    };

    input.addEventListener('change', onChange);

    const instance: PajakCheckboxInstance = {
        get indeterminate(): boolean { return input.indeterminate; },
        set indeterminate(value: boolean) { input.indeterminate = value; },
        get checked(): boolean { return input.checked; },
        set checked(value: boolean) {
            input.checked = value;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        },
        destroy(): void {
            input.removeEventListener('change', onChange);
            registry.delete(label);
        },
    };

    registry.set(label, instance);
    return instance;
}

export const PajakCheckbox = {
    init(el: HTMLElement): PajakCheckboxInstance {
        return upgradeCheckbox(el);
    },

    initAll(root: ParentNode = document): PajakCheckboxInstance[] {
        return Array.from(root.querySelectorAll<HTMLElement>('.pajak-checkbox')).map(upgradeCheckbox);
    },
};
