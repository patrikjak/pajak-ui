declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const initialized = new WeakSet<HTMLElement>();

function initTabs(root: HTMLElement): void {
    if (initialized.has(root)) {
        return;
    }

    initialized.add(root);

    root.querySelectorAll<HTMLButtonElement>(':scope > .pajak-tab').forEach((tab) => {
        tab.addEventListener('click', () => {
            if (tab.disabled) {
                return;
            }

            root.querySelectorAll<HTMLButtonElement>(':scope > .pajak-tab').forEach((other) => {
                other.setAttribute('aria-selected', 'false');
            });

            tab.setAttribute('aria-selected', 'true');
        });
    });
}

export const PajakTabs = {
    initAll(): void {
        document.querySelectorAll<HTMLElement>('[data-pajak-tabs]').forEach((el) => initTabs(el));
    },

    init(el: HTMLElement): void {
        initTabs(el);
    },
} as const;

window.Pajak = { ...window.Pajak, PajakTabs };
