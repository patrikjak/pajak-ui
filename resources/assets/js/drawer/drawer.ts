declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

function getDrawer(id: string): HTMLDialogElement | null {
    return document.querySelector<HTMLDialogElement>(`dialog#${id}[data-pajak-drawer]`);
}

function openDrawer(id: string): void {
    const drawer = getDrawer(id);
    if (drawer) {
        drawer.showModal();
    }
}

function closeDrawer(id: string): void {
    const drawer = getDrawer(id);
    if (drawer) {
        drawer.close();
    }
}

function initAll(): void {
    document.querySelectorAll<HTMLElement>('[data-pajak-drawer-trigger]').forEach((trigger) => {
        const id = trigger.dataset.pajakDrawerTrigger ?? '';
        if (!id) {
            return;
        }

        trigger.addEventListener('click', () => openDrawer(id));
    });

    document.querySelectorAll<HTMLDialogElement>('dialog[data-pajak-drawer]').forEach((drawer) => {
        drawer.addEventListener('click', (e) => {
            if (e.target === drawer) {
                drawer.close();
            }
        });

        drawer.querySelectorAll<HTMLElement>('[data-pajak-drawer-close]').forEach((btn) => {
            btn.addEventListener('click', () => drawer.close());
        });
    });
}

export const PajakDrawer = {
    open: openDrawer,
    close: closeDrawer,
    initAll,
} as const;

window.Pajak = { ...window.Pajak, PajakDrawer };
