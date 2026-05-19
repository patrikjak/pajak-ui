declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

function dismissBanner(banner: HTMLElement): void {
    banner.style.transition = 'opacity 150ms, transform 150ms';
    banner.style.opacity = '0';
    banner.style.transform = 'translateY(-4px)';
    setTimeout(() => {
        banner.style.display = 'none';
    }, 160);
}

function initBanner(banner: HTMLElement): void {
    const closeBtn = banner.querySelector<HTMLElement>('[data-pajak-banner-close]');

    if (closeBtn) {
        closeBtn.addEventListener('click', () => dismissBanner(banner));
    }
}

export const PajakBanner = {
    initAll(): void {
        document.querySelectorAll<HTMLElement>('.pajak-banner').forEach((el) => initBanner(el));
    },

    init(el: HTMLElement): void {
        initBanner(el);
    },

    dismiss(el: HTMLElement): void {
        dismissBanner(el);
    },
} as const;

window.Pajak = { ...window.Pajak, PajakBanner };
