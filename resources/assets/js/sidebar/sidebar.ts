declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const initializedSidebars = new WeakSet<HTMLDialogElement>();

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

function applyRailState(sidebar: HTMLDialogElement, isRail: boolean): boolean {
    const aside = sidebar.querySelector<HTMLElement>('.pajak-sb');
    if (!aside) {
        return false;
    }

    aside.classList.toggle('pajak-sb--rail', isRail);

    sidebar.querySelectorAll<HTMLElement>('[data-pajak-sidebar-rail]').forEach((btn) => {
        btn.classList.toggle('is-rail', isRail);
    });

    return true;
}

function toggleRail(id: string): void {
    const sidebar = getSidebar(id);
    if (!sidebar) {
        return;
    }

    const aside = sidebar.querySelector<HTMLElement>('.pajak-sb');
    if (!aside) {
        return;
    }

    const isRail = !aside.classList.contains('pajak-sb--rail');
    if (applyRailState(sidebar, isRail)) {
        localStorage.setItem(getRailStorageKey(id), isRail ? '1' : '0');
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

        if (localStorage.getItem(getRailStorageKey(id)) === '1') {
            const aside = sidebar.querySelector<HTMLElement>('.pajak-sb');
            aside?.classList.add('pajak-sb--no-transition');
            applyRailState(sidebar, true);
            // Double-rAF ensures layout is flushed before removing the class,
            // so transitions are not triggered during the restore.
            requestAnimationFrame(() => {
                requestAnimationFrame(() => aside?.classList.remove('pajak-sb--no-transition'));
            });
        }

        sidebar.querySelectorAll<HTMLElement>('[data-pajak-sidebar-rail]').forEach((btn) => {
            btn.addEventListener('click', () => toggleRail(id));
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
