declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const initialized = new WeakSet<HTMLElement>();

function initSegmented(root: HTMLElement): void {
    if (initialized.has(root)) {
        return;
    }

    initialized.add(root);

    root.querySelectorAll<HTMLButtonElement>(':scope > .pajak-seg__opt').forEach((btn, index) => {
        btn.addEventListener('click', () => {
            if (btn.disabled) {
                return;
            }

            root.querySelectorAll<HTMLButtonElement>(':scope > .pajak-seg__opt').forEach((other) => {
                other.setAttribute('aria-selected', 'false');
            });

            btn.setAttribute('aria-selected', 'true');

            root.dispatchEvent(new CustomEvent('pajak-segmented:change', {
                bubbles: true,
                detail: { value: btn.dataset.value ?? null, index },
            }));
        });
    });
}

export const PajakSegmented = {
    initAll(): void {
        document.querySelectorAll<HTMLElement>('[data-pajak-segmented]').forEach((el) => initSegmented(el));
    },

    init(el: HTMLElement): void {
        initSegmented(el);
    },
} as const;

window.Pajak = { ...window.Pajak, PajakSegmented };
