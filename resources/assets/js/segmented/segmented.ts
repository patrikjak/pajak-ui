import { onReady } from '../ready';

declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const initialized = new WeakSet<HTMLElement>();

function getOptions(root: HTMLElement): HTMLButtonElement[] {
    return Array.from(root.querySelectorAll<HTMLButtonElement>(':scope > .pajak-seg__opt'));
}

interface SelectOptions {
    // Update the control without dispatching `pajak-segmented:change` - for syncing state on load.
    silent?: boolean;
}

function selectOption(root: HTMLElement, btn: HTMLButtonElement, index: number, options: SelectOptions = {}): void {
    getOptions(root).forEach((b) => {
        b.setAttribute('aria-selected', 'false');
        b.setAttribute('tabindex', '-1');
    });
    btn.setAttribute('aria-selected', 'true');
    btn.setAttribute('tabindex', '0');

    if (options.silent) {
        return;
    }

    root.dispatchEvent(new CustomEvent('pajak-segmented:change', {
        bubbles: true,
        detail: { value: btn.dataset.value ?? null, index },
    }));
}

function initSegmented(root: HTMLElement): void {
    if (initialized.has(root)) {
        return;
    }

    initialized.add(root);

    const options = getOptions(root);

    // Roving tabindex - only the selected option (or first enabled) is tabbable
    const initialSelected =
        options.find((b) => b.getAttribute('aria-selected') === 'true' && !b.disabled) ??
        options.find((b) => !b.disabled) ??
        options[0];
    options.forEach((b) => {
        b.setAttribute('tabindex', b === initialSelected ? '0' : '-1');
    });

    options.forEach((btn, index) => {
        btn.addEventListener('click', () => {
            if (btn.disabled) {
                return;
            }
            selectOption(root, btn, index);
        });
    });

    root.addEventListener('keydown', (e: KeyboardEvent) => {
        const active = root.querySelector<HTMLButtonElement>(':scope > .pajak-seg__opt[tabindex="0"]');
        if (!active) {
            return;
        }

        const enabled = options.filter((b) => !b.disabled);
        const idx = enabled.indexOf(active);

        let next: HTMLButtonElement | null = null;

        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            next = enabled[(idx + 1) % enabled.length] ?? null;
        } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            next = enabled[(idx - 1 + enabled.length) % enabled.length] ?? null;
        } else if (e.key === 'Home') {
            next = enabled[0] ?? null;
        } else if (e.key === 'End') {
            next = enabled[enabled.length - 1] ?? null;
        }

        if (next) {
            e.preventDefault();
            const nextIndex = options.indexOf(next);
            selectOption(root, next, nextIndex);
            next.focus();
        }
    });
}

export const PajakSegmented = {
    initAll(root: ParentNode = document): void {
        root.querySelectorAll<HTMLElement>('[data-pajak-segmented]').forEach((el) => initSegmented(el));
    },

    init(el: HTMLElement): void {
        initSegmented(el);
    },

    // Selects the option whose `data-value` matches; returns false when none matches or it is disabled.
    select(el: HTMLElement, value: string, options: SelectOptions = {}): boolean {
        const buttons = getOptions(el);
        const index = buttons.findIndex((b) => b.dataset.value === value);

        if (index === -1 || buttons[index].disabled) {
            return false;
        }

        selectOption(el, buttons[index], index, options);

        return true;
    },

    value(el: HTMLElement): string | null {
        return getOptions(el).find((b) => b.getAttribute('aria-selected') === 'true')?.dataset.value ?? null;
    },
} as const;

window.Pajak = { ...window.Pajak, PajakSegmented };

onReady(() => PajakSegmented.initAll());
