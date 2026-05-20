declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

function getDialog(id: string): HTMLDialogElement | null {
    return document.querySelector<HTMLDialogElement>(`dialog#${id}[data-pajak-dialog]`);
}

function openDialog(id: string): void {
    const dialog = getDialog(id);
    if (dialog) {
        dialog.showModal();
    }
}

function closeDialog(id: string): void {
    const dialog = getDialog(id);
    if (dialog) {
        dialog.close();
    }
}

function initAll(): void {
    document.querySelectorAll<HTMLElement>('[data-pajak-dialog-trigger]').forEach((trigger) => {
        const id = trigger.dataset.pajakDialogTrigger ?? '';
        if (!id) {
            return;
        }

        trigger.addEventListener('click', () => openDialog(id));
    });

    document.querySelectorAll<HTMLDialogElement>('dialog[data-pajak-dialog]').forEach((dialog) => {
        dialog.addEventListener('click', (e) => {
            if (e.target === dialog) {
                dialog.close();
            }
        });
    });
}

export const PajakDialog = {
    open: openDialog,
    close: closeDialog,
    initAll,
} as const;

window.Pajak = { ...window.Pajak, PajakDialog };
