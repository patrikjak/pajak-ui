import { createRegistry } from '../registry';

interface PajakToggleInstance {
    get checked(): boolean;
    set checked(value: boolean);
    destroy(): void;
}

const registry = createRegistry<PajakToggleInstance>();

function upgradeToggle(label: HTMLElement): PajakToggleInstance {
    const existing = registry.get(label);
    if (existing) {
        return existing;
    }

    const input = label.querySelector<HTMLInputElement>('.pajak-toggle__input');
    if (!input) {
        return { get checked() { return false; }, set checked(_) {}, destroy() {} };
    }

    const onChange = (): void => {
        label.dispatchEvent(new CustomEvent('pajak:toggle', {
            bubbles: true,
            detail: { checked: input.checked },
        }));
    };

    input.addEventListener('change', onChange);

    const instance: PajakToggleInstance = {
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

export const PajakToggle = {
    init(el: HTMLElement): PajakToggleInstance {
        return upgradeToggle(el);
    },

    initAll(root: ParentNode = document): PajakToggleInstance[] {
        return Array.from(root.querySelectorAll<HTMLElement>('.pajak-toggle')).map(upgradeToggle);
    },
};
