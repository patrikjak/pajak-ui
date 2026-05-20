declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const initialized = new WeakSet<HTMLElement>();

function getPopover(id: string): HTMLElement | null {
    return document.querySelector<HTMLElement>(`[data-pajak-popover]#${id}`);
}

function openPopover(id: string): void {
    const pop = getPopover(id);
    if (!pop) {
        return;
    }

    document.querySelectorAll<HTMLElement>('[data-pajak-popover].is-open').forEach((other) => {
        if (other !== pop) {
            closePopoverEl(other);
        }
    });

    pop.removeAttribute('hidden');
    requestAnimationFrame(() => {
        pop.classList.add('is-open');
        pop.setAttribute('aria-hidden', 'false');
    });

    document.querySelectorAll<HTMLElement>(`[data-pajak-popover-trigger="${id}"]`).forEach((trigger) => {
        trigger.setAttribute('aria-expanded', 'true');
    });
}

function closePopoverEl(pop: HTMLElement): void {
    pop.classList.remove('is-open');
    pop.setAttribute('aria-hidden', 'true');

    const id = pop.id;
    document.querySelectorAll<HTMLElement>(`[data-pajak-popover-trigger="${id}"]`).forEach((trigger) => {
        trigger.setAttribute('aria-expanded', 'false');
    });

    pop.addEventListener('transitionend', () => {
        if (!pop.classList.contains('is-open')) {
            pop.setAttribute('hidden', '');
        }
    }, { once: true });
}

function closePopover(id: string): void {
    const pop = getPopover(id);
    if (pop) {
        closePopoverEl(pop);
    }
}

function togglePopover(id: string): void {
    const pop = getPopover(id);
    if (!pop) {
        return;
    }

    if (pop.classList.contains('is-open')) {
        closePopoverEl(pop);
    } else {
        openPopover(id);
    }
}

function initPopover(pop: HTMLElement): void {
    if (initialized.has(pop)) {
        return;
    }

    initialized.add(pop);

    pop.querySelectorAll<HTMLElement>('[data-pajak-popover-close]').forEach((btn) => {
        btn.addEventListener('click', () => closePopoverEl(pop));
    });
}

function initAll(): void {
    document.querySelectorAll<HTMLElement>('[data-pajak-popover-trigger]').forEach((trigger) => {
        const id = trigger.dataset.pajakPopoverTrigger ?? '';
        if (!id) {
            return;
        }

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            togglePopover(id);
        });
    });

    document.querySelectorAll<HTMLElement>('[data-pajak-popover]').forEach((pop) => {
        initPopover(pop);
    });

    document.addEventListener('click', (e) => {
        document.querySelectorAll<HTMLElement>('[data-pajak-popover].is-open').forEach((pop) => {
            if (!pop.contains(e.target as Node)) {
                closePopoverEl(pop);
            }
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll<HTMLElement>('[data-pajak-popover].is-open').forEach((pop) => {
                closePopoverEl(pop);
            });
        }
    });
}

export const PajakPopover = {
    open: openPopover,
    close: closePopover,
    toggle: togglePopover,
    initAll,
} as const;

window.Pajak = { ...window.Pajak, PajakPopover };
