import { createRegistry } from '../registry';

interface PajakCheckboxSetOptions {
    // Skip the `change` event and `pajak:checkbox` dispatch - for programmatic updates
    // (e.g. a select-all handler) that must not re-trigger their own listeners.
    silent?: boolean;
}

interface PajakCheckboxInstance {
    get indeterminate(): boolean;
    set indeterminate(value: boolean);
    get checked(): boolean;
    set checked(value: boolean);
    setChecked(value: boolean, options?: PajakCheckboxSetOptions): void;
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
            setChecked() {},
            destroy() {},
        };
    }

    if (input.hasAttribute('data-pajak-indeterminate')) {
        input.indeterminate = true;
    }

    const onChange = (): void => {
        label.dispatchEvent(new CustomEvent('pajak:checkbox', {
            bubbles: true,
            detail: { checked: input.checked, indeterminate: input.indeterminate },
        }));
    };

    input.addEventListener('change', onChange);

    const setChecked = (value: boolean, options: PajakCheckboxSetOptions = {}): void => {
        input.checked = value;

        if (!options.silent) {
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    };

    const instance: PajakCheckboxInstance = {
        get indeterminate(): boolean { return input.indeterminate; },
        set indeterminate(value: boolean) { input.indeterminate = value; },
        get checked(): boolean { return input.checked; },
        set checked(value: boolean) { setChecked(value); },
        setChecked,
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
