declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const BUBBLE_VISIBLE_MS = 1500;
const BUBBLE_CLASS = 'pajak-copy-bubble';
const VISIBLE_CLASS = 'is-visible';

const initialized = new WeakSet<HTMLElement>();

function toPx(value: number): string {
    return `${Math.round(value)}px`;
}

function showBubble(trigger: HTMLElement, label: string): void {
    const bubble = document.createElement('span');
    bubble.className = BUBBLE_CLASS;
    bubble.textContent = label;
    document.body.appendChild(bubble);

    const triggerRect = trigger.getBoundingClientRect();
    const bubbleRect = bubble.getBoundingClientRect();

    bubble.style.left = toPx(triggerRect.left + triggerRect.width / 2 - bubbleRect.width / 2);
    bubble.style.top = toPx(triggerRect.top - bubbleRect.height - 10);

    requestAnimationFrame(() => {
        bubble.classList.add(VISIBLE_CLASS);
    });

    setTimeout(() => {
        bubble.classList.remove(VISIBLE_CLASS);
        bubble.addEventListener('transitionend', () => bubble.remove(), { once: true });
    }, BUBBLE_VISIBLE_MS);
}

function initCopyTrigger(trigger: HTMLElement, label: string): void {
    if (initialized.has(trigger)) {
        return;
    }

    initialized.add(trigger);

    trigger.addEventListener('click', async () => {
        const value = trigger.dataset.pajakCopy ?? '';

        try {
            await navigator.clipboard.writeText(value);
        } catch {
            return;
        }

        showBubble(trigger, label);
    });
}

function initAll(label = 'Copied!'): void {
    document.querySelectorAll<HTMLElement>('[data-pajak-copy]').forEach((trigger) => {
        initCopyTrigger(trigger, label);
    });
}

export const PajakCopyButton = {
    initAll,
} as const;

window.Pajak = { ...window.Pajak, PajakCopyButton };
