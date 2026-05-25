declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const initializedTriggers = new WeakSet<HTMLElement>();
const initializedModals = new WeakSet<HTMLDialogElement>();

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
    document.querySelectorAll<HTMLElement>('[data-pajak-modal-trigger], [data-pajak-modal-open]').forEach((trigger) => {
        if (initializedTriggers.has(trigger)) {
            return;
        }

        const id = (trigger.dataset.pajakModalTrigger ?? trigger.dataset.pajakModalOpen) ?? '';
        if (!id) {
            return;
        }

        initializedTriggers.add(trigger);
        trigger.addEventListener('click', () => openModal(id));
    });

    document.querySelectorAll<HTMLDialogElement>('dialog[data-pajak-modal]').forEach((modal) => {
        if (initializedModals.has(modal)) {
            return;
        }

        initializedModals.add(modal);

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
