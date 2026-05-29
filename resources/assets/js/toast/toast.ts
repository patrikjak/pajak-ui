export interface ToastOptions {
    type: 'success' | 'error' | 'warning' | 'info';
    title: string;
    message?: string;
    duration?: number;
}

const DEFAULT_DURATION = 4000;

// ─── Icons ────────────────────────────────────────────────────────────────────

const ICONS: Record<ToastOptions['type'], string> = {
    info: '<svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
    success: '<svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
    warning: '<svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    error: '<svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
};

const CLOSE_ICON = '<svg aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';

// ─── Container singleton ──────────────────────────────────────────────────────

let container: HTMLDivElement | null = null;

function getContainer(): HTMLDivElement {
    if (!container || !document.body.contains(container)) {
        container = document.createElement('div');
        container.className = 'pajak-toast-container';
        container.setAttribute('aria-live', 'polite');
        container.setAttribute('aria-atomic', 'false');
        document.body.appendChild(container);
    }

    return container;
}

// ─── Build toast element ──────────────────────────────────────────────────────

function buildToast(id: string, options: ToastOptions): HTMLDivElement {
    const duration = options.duration ?? DEFAULT_DURATION;

    const el = document.createElement('div');
    el.className = `pajak-toast pajak-toast--${options.type}`;
    el.setAttribute('role', 'alert');
    el.dataset.toastId = id;
    el.style.setProperty('--pajak-toast-duration', `${duration}ms`);

    const iconWrap = document.createElement('div');
    iconWrap.className = 'pajak-toast__icon-wrap';
    iconWrap.innerHTML = ICONS[options.type];

    const body = document.createElement('div');
    body.className = 'pajak-toast__body';

    const title = document.createElement('div');
    title.className = 'pajak-toast__title';
    title.textContent = options.title;
    body.appendChild(title);

    if (options.message) {
        const msg = document.createElement('div');
        msg.className = 'pajak-toast__message';
        msg.textContent = options.message;
        body.appendChild(msg);
    }

    const closeBtn = document.createElement('button');
    closeBtn.className = 'pajak-toast__close';
    closeBtn.setAttribute('aria-label', 'Dismiss');
    closeBtn.innerHTML = CLOSE_ICON;
    closeBtn.addEventListener('click', () => dismissEl(el));

    const progress = document.createElement('div');
    progress.className = 'pajak-toast__progress';

    el.appendChild(iconWrap);
    el.appendChild(body);
    el.appendChild(closeBtn);
    el.appendChild(progress);

    return el;
}

// ─── Dismiss ──────────────────────────────────────────────────────────────────

function dismissEl(el: HTMLElement): void {
    el.style.animation = `pajak-toast-out 250ms cubic-bezier(0.25,0.46,0.45,0.94) both`;
    el.addEventListener('animationend', () => el.remove(), { once: true });
}

// ─── Public API ───────────────────────────────────────────────────────────────

let counter = 0;

export const PajakToast = {
    show(options: ToastOptions): string {
        const id = `pajak-toast-${++counter}`;
        const el = buildToast(id, options);
        getContainer().appendChild(el);

        const duration = options.duration ?? DEFAULT_DURATION;
        setTimeout(() => {
            if (el.isConnected) {
                dismissEl(el);
            }
        }, duration);

        return id;
    },

    dismiss(id: string): void {
        const el = document.querySelector<HTMLElement>(`[data-toast-id="${id}"]`);

        if (el) {
            dismissEl(el);
        }
    },

    dismissAll(): void {
        document.querySelectorAll<HTMLElement>('[data-toast-id]').forEach((el) => dismissEl(el));
    },
} as const;
