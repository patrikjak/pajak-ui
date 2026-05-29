declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const initialized = new WeakSet<HTMLElement>();

function getTabs(root: HTMLElement): HTMLButtonElement[] {
    return Array.from(root.querySelectorAll<HTMLButtonElement>(':scope > .pajak-tab'));
}

function selectTab(root: HTMLElement, tab: HTMLButtonElement): void {
    getTabs(root).forEach((t) => {
        t.setAttribute('aria-selected', 'false');
        t.setAttribute('tabindex', '-1');
    });
    tab.setAttribute('aria-selected', 'true');
    tab.setAttribute('tabindex', '0');
}

function initTabs(root: HTMLElement): void {
    if (initialized.has(root)) {
        return;
    }

    initialized.add(root);

    const tabs = getTabs(root);

    // Set up initial roving tabindex — only the selected tab (or first) is tabbable
    const initialSelected = tabs.find((t) => t.getAttribute('aria-selected') === 'true') ?? tabs[0];
    tabs.forEach((t) => {
        t.setAttribute('tabindex', t === initialSelected ? '0' : '-1');
    });

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            if (tab.disabled) {
                return;
            }
            selectTab(root, tab);
        });
    });

    root.addEventListener('keydown', (e: KeyboardEvent) => {
        const active = root.querySelector<HTMLButtonElement>(':scope > .pajak-tab[tabindex="0"]');
        if (!active) {
            return;
        }

        const enabled = tabs.filter((t) => !t.disabled);
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
            selectTab(root, next);
            next.focus();
        }
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
