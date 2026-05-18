import { createRegistry } from '../registry';

interface PajakFileInstance {
    destroy(): void;
}

const registry = createRegistry<PajakFileInstance>();

function upgradeFile(label: HTMLElement): PajakFileInstance {
    const existing = registry.get(label);
    if (existing) {
        return existing;
    }

    const input = label.querySelector<HTMLInputElement>('.pajak-file__input');
    const textEl = label.querySelector<HTMLElement>('[data-pajak-file-text]');

    if (!input || !textEl) {
        const noop: PajakFileInstance = { destroy: () => {} };
        registry.set(label, noop);
        return noop;
    }

    const defaultText = input.dataset.placeholder ?? '';

    const onChange = (): void => {
        textEl.textContent = input.files?.[0]?.name ?? defaultText;
    };

    input.addEventListener('change', onChange);

    const instance: PajakFileInstance = {
        destroy(): void {
            input.removeEventListener('change', onChange);
            registry.delete(label);
        },
    };

    registry.set(label, instance);
    return instance;
}

export const PajakFile = {
    init(el: HTMLElement): PajakFileInstance {
        return upgradeFile(el);
    },

    initAll(root: ParentNode = document): PajakFileInstance[] {
        return Array.from(root.querySelectorAll<HTMLElement>('[data-pajak-file]')).map(upgradeFile);
    },
};
