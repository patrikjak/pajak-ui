const registry = new WeakMap<HTMLButtonElement, () => void>();

function init(btn: HTMLButtonElement): void {
    if (registry.has(btn)) {
        return;
    }

    const inputId = btn.dataset.pajakPasswordToggle;

    if (!inputId) {
        return;
    }

    const input = document.getElementById(inputId) as HTMLInputElement | null;

    if (!input) {
        return;
    }

    const showLabel = btn.getAttribute('aria-label') ?? 'Show password';
    const hideLabel = btn.dataset.hideLabel ?? showLabel.replace(/show/i, 'Hide');

    function toggle(): void {
        const isVisible = input!.type === 'text';
        input!.type = isVisible ? 'password' : 'text';
        btn.setAttribute('aria-pressed', isVisible ? 'false' : 'true');
        btn.setAttribute('aria-label', isVisible ? showLabel : hideLabel);
    }

    btn.addEventListener('click', toggle);
    registry.set(btn, toggle);
}

function destroy(btn: HTMLButtonElement): void {
    const handler = registry.get(btn);

    if (handler) {
        btn.removeEventListener('click', handler);
        registry.delete(btn);
    }
}

function initAll(root: ParentNode = document): void {
    root.querySelectorAll<HTMLButtonElement>('[data-pajak-password-toggle]').forEach(init);
}

export const PajakPassword = { init, destroy, initAll } as const;
