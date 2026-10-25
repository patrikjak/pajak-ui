import { onReady } from '../ready';

declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const initialized = new WeakSet<HTMLElement>();

function getTabs(root: HTMLElement): HTMLButtonElement[] {
    return Array.from(root.querySelectorAll<HTMLButtonElement>(':scope > .pajak-tab'));
}

// Tabs that declare `aria-controls` drive the visibility of the panel with that id; tabs without
// it are left alone so apps that manage their own panels keep working.
function syncPanels(root: HTMLElement): void {
    getTabs(root).forEach((t) => {
        const panelId = t.getAttribute('aria-controls');
        const panel = panelId ? document.getElementById(panelId) : null;

        if (panel) {
            panel.hidden = t.getAttribute('aria-selected') !== 'true';
        }
    });
}

function selectTab(root: HTMLElement, tab: HTMLButtonElement): void {
    const tabs = getTabs(root);
    const previous = tabs.find((t) => t.getAttribute('aria-selected') === 'true');

    tabs.forEach((t) => {
        t.setAttribute('aria-selected', 'false');
        t.setAttribute('tabindex', '-1');
    });
    tab.setAttribute('aria-selected', 'true');
    tab.setAttribute('tabindex', '0');

    syncPanels(root);

    if (tab !== previous) {
        root.dispatchEvent(new CustomEvent('pajak-tabs:change', {
            bubbles: true,
            detail: { index: tabs.indexOf(tab), controls: tab.getAttribute('aria-controls') },
        }));
    }
}

function initTabs(root: HTMLElement): void {
    if (initialized.has(root)) {
        return;
    }

    initialized.add(root);

    const tabs = getTabs(root);

    // Set up initial roving tabindex - only the selected tab (or first) is tabbable
    const initialSelected = tabs.find((t) => t.getAttribute('aria-selected') === 'true') ?? tabs[0];
    tabs.forEach((t) => {
        t.setAttribute('tabindex', t === initialSelected ? '0' : '-1');
    });
    syncPanels(root);

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
    initAll(root: ParentNode = document): void {
        root.querySelectorAll<HTMLElement>('[data-pajak-tabs]').forEach((el) => initTabs(el));
    },

    init(el: HTMLElement): void {
        initTabs(el);
    },
} as const;

window.Pajak = { ...window.Pajak, PajakTabs };

onReady(() => PajakTabs.initAll());
