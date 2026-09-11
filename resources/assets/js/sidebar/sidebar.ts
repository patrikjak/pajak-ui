declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const initializedSidebars = new WeakSet<HTMLDialogElement>();
const initializedRailButtons = new WeakSet<HTMLElement>();

function getSidebar(id: string): HTMLDialogElement | null {
    return document.querySelector<HTMLDialogElement>(`dialog#${id}[data-pajak-sidebar]`);
}

function getTrigger(id: string): HTMLElement | null {
    return document.querySelector<HTMLElement>(`[data-pajak-sidebar-trigger="${id}"]`);
}

function setTriggerOpen(id: string, open: boolean): void {
    const trigger = getTrigger(id);
    if (trigger) {
        trigger.classList.toggle('is-open', open);
    }
}

function openSidebar(id: string): void {
    const sidebar = getSidebar(id);
    if (sidebar) {
        sidebar.classList.add('is-open');
        setTriggerOpen(id, true);
    }
}

function closeSidebar(id: string): void {
    const sidebar = getSidebar(id);
    if (sidebar) {
        sidebar.classList.remove('is-open');
        setTriggerOpen(id, false);
    }
}

// Key format must match the inline script in sidebar.blade.php
function getRailStorageKey(id: string): string {
    return `pajak-sb-rail:${id}`;
}

// A sidebar's `.pajak-sb` aside is either standalone with its own id (desktop, always
// rendered) or nested inside a `<dialog data-pajak-sidebar>` that carries the id instead
// (mobile overlay) — the id used for persistence follows whichever one actually has it.
function railStorageId(aside: HTMLElement): string | null {
    const dialog = aside.closest<HTMLElement>('dialog[data-pajak-sidebar]');

    return dialog?.id || aside.id || null;
}

// Resolves a sidebar's `.pajak-sb` aside from its public id — either the id of the
// `<dialog>` wrapping it (mobile overlay) or the id on the aside itself (standalone desktop).
function getRailAside(id: string): HTMLElement | null {
    const dialog = document.querySelector<HTMLElement>(`dialog#${id}[data-pajak-sidebar]`);
    if (dialog) {
        return dialog.querySelector<HTMLElement>('.pajak-sb');
    }

    return document.querySelector<HTMLElement>(`.pajak-sb#${id}`);
}

function applyRailState(aside: HTMLElement, isRail: boolean): void {
    aside.classList.toggle('pajak-sb--rail', isRail);

    aside.querySelectorAll<HTMLElement>('[data-pajak-sidebar-rail]').forEach((btn) => {
        btn.classList.toggle('is-rail', isRail);
    });
}

function toggleRailAside(aside: HTMLElement): void {
    const isRail = !aside.classList.contains('pajak-sb--rail');
    applyRailState(aside, isRail);

    const id = railStorageId(aside);
    if (id) {
        localStorage.setItem(getRailStorageKey(id), isRail ? '1' : '0');
    }
}

function toggleRail(id: string): void {
    const aside = getRailAside(id);
    if (aside) {
        toggleRailAside(aside);
    }
}

function createTriggerButton(id: string): HTMLButtonElement {
    const btn = document.createElement('button');
    btn.className = 'pajak-sb-toggle pajak-sb-toggle--floating';
    btn.setAttribute('data-pajak-sidebar-trigger', id);
    btn.setAttribute('aria-label', 'Open navigation');
    btn.innerHTML = `
        <span class="pajak-sb-toggle__icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    `;
    btn.addEventListener('click', () => openSidebar(id));
    return btn;
}

function initAll(): void {
    document.querySelectorAll<HTMLDialogElement>('dialog[data-pajak-sidebar]').forEach((sidebar) => {
        if (initializedSidebars.has(sidebar)) {
            return;
        }

        initializedSidebars.add(sidebar);

        const id = sidebar.id;
        if (!id) {
            return;
        }

        // Auto-inject trigger into designated slot if none exists
        if (!getTrigger(id)) {
            const slot = document.querySelector<HTMLElement>(`[data-pajak-sidebar-trigger-slot="${id}"]`);
            if (slot) {
                const btn = createTriggerButton(id);
                slot.appendChild(btn);
            }
        }

        // Inject a backdrop div for click-outside-to-close
        const backdrop = document.createElement('div');
        backdrop.className = 'pajak-sb-backdrop';
        sidebar.appendChild(backdrop);
        backdrop.addEventListener('click', () => closeSidebar(id));

        sidebar.querySelectorAll<HTMLElement>('[data-pajak-sidebar-close]').forEach((btn) => {
            btn.addEventListener('click', () => closeSidebar(id));
        });
    });

    // Rail (collapse-to-icons) applies to every `.pajak-sb`, standalone or dialog-wrapped.
    document.querySelectorAll<HTMLElement>('.pajak-sb').forEach((aside) => {
        const id = railStorageId(aside);
        if (id && localStorage.getItem(getRailStorageKey(id)) === '1') {
            aside.classList.add('pajak-sb--no-transition');
            applyRailState(aside, true);
            // Double-rAF ensures layout is flushed before removing the class,
            // so transitions are not triggered during the restore.
            requestAnimationFrame(() => {
                requestAnimationFrame(() => aside.classList.remove('pajak-sb--no-transition'));
            });
        }

        aside.querySelectorAll<HTMLElement>('[data-pajak-sidebar-rail]').forEach((btn) => {
            if (initializedRailButtons.has(btn)) {
                return;
            }

            initializedRailButtons.add(btn);
            btn.addEventListener('click', () => toggleRailAside(aside));
        });
    });

    // Also wire up any manually placed triggers
    document.querySelectorAll<HTMLElement>('[data-pajak-sidebar-trigger]').forEach((trigger) => {
        const id = trigger.dataset.pajakSidebarTrigger ?? '';
        if (!id) {
            return;
        }
        // Only add listener if not already set up via auto-inject
        if (!trigger.classList.contains('pajak-sb-toggle--floating')) {
            trigger.addEventListener('click', () => openSidebar(id));
        }
    });
}

export const PajakSidebar = {
    open: openSidebar,
    close: closeSidebar,
    rail: toggleRail,
    initAll,
} as const;

window.Pajak = { ...window.Pajak, PajakSidebar };
