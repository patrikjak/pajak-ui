declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const initialized = new WeakSet<HTMLElement>();

function initAccordion(root: HTMLElement): void {
    if (initialized.has(root)) {
        return;
    }

    initialized.add(root);

    const mode = root.dataset.pajakAccordion ?? 'single';

    root.querySelectorAll<HTMLElement>(':scope > .pajak-acc__item > .pajak-acc__header').forEach((header) => {
        header.addEventListener('click', () => {
            const item = header.parentElement as HTMLElement;
            const isOpen = item.hasAttribute('open');

            if (mode === 'single') {
                root.querySelectorAll<HTMLElement>(':scope > .pajak-acc__item[open]').forEach((other) => {
                    if (other !== item) {
                        other.removeAttribute('open');
                        const otherHeader = other.querySelector<HTMLElement>(':scope > .pajak-acc__header');
                        if (otherHeader) {
                            otherHeader.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
            }

            if (isOpen) {
                item.removeAttribute('open');
                header.setAttribute('aria-expanded', 'false');
            } else {
                item.setAttribute('open', '');
                header.setAttribute('aria-expanded', 'true');
            }
        });
    });
}

export const PajakAccordion = {
    initAll(): void {
        document.querySelectorAll<HTMLElement>('[data-pajak-accordion]').forEach((el) => initAccordion(el));
    },

    init(el: HTMLElement): void {
        initAccordion(el);
    },
} as const;

window.Pajak = { ...window.Pajak, PajakAccordion };
