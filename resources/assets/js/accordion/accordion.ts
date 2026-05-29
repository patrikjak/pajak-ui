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

    const headers = Array.from(
        root.querySelectorAll<HTMLElement>(':scope > .pajak-acc__item > .pajak-acc__header'),
    );

    headers.forEach((header) => {
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

        // Arrow key navigation between accordion headers (WAI-ARIA accordion pattern)
        header.addEventListener('keydown', (e: KeyboardEvent) => {
            const idx = headers.indexOf(header);
            let next: HTMLElement | null = null;

            if (e.key === 'ArrowDown') {
                next = headers[idx + 1] ?? headers[0] ?? null;
            } else if (e.key === 'ArrowUp') {
                next = headers[idx - 1] ?? headers[headers.length - 1] ?? null;
            } else if (e.key === 'Home') {
                next = headers[0] ?? null;
            } else if (e.key === 'End') {
                next = headers[headers.length - 1] ?? null;
            }

            if (next) {
                e.preventDefault();
                next.focus();
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
