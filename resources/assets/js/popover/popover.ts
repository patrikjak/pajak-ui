declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const initialized = new WeakSet<HTMLElement>();
const initializedTriggers = new WeakSet<HTMLElement>();
let documentListenersAttached = false;

// Maps popover id → the trigger element that last opened it (for focus restoration)
const lastTrigger = new Map<string, HTMLElement>();

const FOCUSABLE = 'a[href], button:not(:disabled), input:not(:disabled), select:not(:disabled), textarea:not(:disabled), [tabindex]:not([tabindex="-1"])';

function getPopover(id: string): HTMLElement | null {
    return document.querySelector<HTMLElement>(`[data-pajak-popover]#${id}`);
}

function openPopover(id: string, trigger?: HTMLElement): void {
    const pop = getPopover(id);
    if (!pop) {
        return;
    }

    document.querySelectorAll<HTMLElement>('[data-pajak-popover].is-open').forEach((other) => {
        if (other !== pop) {
            closePopoverEl(other);
        }
    });

    if (trigger) {
        lastTrigger.set(id, trigger);
    }

    pop.removeAttribute('hidden');
    requestAnimationFrame(() => {
        pop.classList.add('is-open');
        pop.setAttribute('aria-hidden', 'false');

        // Move focus into the popover
        const firstFocusable = pop.querySelector<HTMLElement>(FOCUSABLE);
        if (firstFocusable) {
            firstFocusable.focus();
        } else {
            pop.setAttribute('tabindex', '-1');
            pop.focus();
        }
    });

    document.querySelectorAll<HTMLElement>(`[data-pajak-popover-trigger="${id}"]`).forEach((t) => {
        t.setAttribute('aria-expanded', 'true');
    });
}

function closePopoverEl(pop: HTMLElement): void {
    pop.classList.remove('is-open');
    pop.setAttribute('aria-hidden', 'true');

    const id = pop.id;
    document.querySelectorAll<HTMLElement>(`[data-pajak-popover-trigger="${id}"]`).forEach((t) => {
        t.setAttribute('aria-expanded', 'false');
    });

    let hiddenApplied = false;
    const applyHidden = (): void => {
        if (!hiddenApplied && !pop.classList.contains('is-open')) {
            hiddenApplied = true;
            pop.setAttribute('hidden', '');
        }
    };
    pop.addEventListener('transitionend', applyHidden, { once: true });
    // Fallback: if no CSS transition is defined, transitionend never fires
    setTimeout(applyHidden, 0);

    // Return focus to the trigger that opened this popover
    const trigger = lastTrigger.get(id);
    if (trigger) {
        trigger.focus();
        lastTrigger.delete(id);
    }
}

function closePopover(id: string): void {
    const pop = getPopover(id);
    if (pop) {
        closePopoverEl(pop);
    }
}

function togglePopover(id: string, trigger?: HTMLElement): void {
    const pop = getPopover(id);
    if (!pop) {
        return;
    }

    if (pop.classList.contains('is-open')) {
        closePopoverEl(pop);
    } else {
        openPopover(id, trigger);
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
        if (initializedTriggers.has(trigger)) {
            return;
        }

        const id = trigger.dataset.pajakPopoverTrigger ?? '';
        if (!id) {
            return;
        }

        initializedTriggers.add(trigger);
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            togglePopover(id, trigger as HTMLElement);
        });
    });

    document.querySelectorAll<HTMLElement>('[data-pajak-popover]').forEach((pop) => {
        initPopover(pop);
    });

    if (!documentListenersAttached) {
        documentListenersAttached = true;

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
}

export const PajakPopover = {
    open: (id: string) => openPopover(id),
    close: closePopover,
    toggle: (id: string) => togglePopover(id),
    initAll,
} as const;

window.Pajak = { ...window.Pajak, PajakPopover };
