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
    initAll,
} as const;

window.Pajak = { ...window.Pajak, PajakSidebar };
