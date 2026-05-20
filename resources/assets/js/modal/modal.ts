declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

function getModal(id: string): HTMLDialogElement | null {
    return document.querySelector<HTMLDialogElement>(`dialog#${id}[data-pajak-modal]`);
}

function openModal(id: string): void {
    const modal = getModal(id);
    if (modal) {
        modal.showModal();
    }
}

function closeModal(id: string): void {
    const modal = getModal(id);
    if (modal) {
        modal.close();
    }
}

function initAll(): void {
    document.querySelectorAll<HTMLElement>('[data-pajak-modal-trigger]').forEach((trigger) => {
        const id = trigger.dataset.pajakModalTrigger ?? '';
        if (!id) {
            return;
        }

        trigger.addEventListener('click', () => openModal(id));
    });

    document.querySelectorAll<HTMLDialogElement>('dialog[data-pajak-modal]').forEach((modal) => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.close();
            }
        });

        modal.querySelectorAll<HTMLElement>('[data-pajak-modal-close]').forEach((btn) => {
            btn.addEventListener('click', () => modal.close());
        });
    });
}

export const PajakModal = {
    open: openModal,
    close: closeModal,
    initAll,
} as const;

window.Pajak = { ...window.Pajak, PajakModal };
