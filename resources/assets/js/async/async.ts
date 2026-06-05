declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

async function fetchContent(el: HTMLElement, isRefresh: boolean): Promise<void> {
    const url = el.dataset.url;

    if (!url) {
        return;
    }

    el.classList.remove('is-loaded', 'is-error');

    if (isRefresh) {
        el.classList.add('is-refreshing');
    }

    el.dispatchEvent(new CustomEvent('pajak:async:loading', { bubbles: true }));

    const csrfToken = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '';

    let response: Response;

    try {
        response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
            },
        });
    } catch {
        el.classList.remove('is-refreshing');
        el.classList.add('is-error');
        el.dispatchEvent(new CustomEvent('pajak:async:error', { bubbles: true }));

        return;
    }

    el.classList.remove('is-refreshing');

    if (!response.ok) {
        el.classList.add('is-error');
        el.dispatchEvent(new CustomEvent('pajak:async:error', { bubbles: true, detail: { status: response.status } }));

        return;
    }

    const html = await response.text();
    const content = el.querySelector<HTMLElement>('[data-pajak-async-content]') ?? el.querySelector('.pajak-async__content');

    if (content) {
        content.innerHTML = html;
    }

    el.classList.add('is-loaded');
    el.dispatchEvent(new CustomEvent('pajak:async:loaded', { bubbles: true }));
}

function init(el: HTMLElement): void {
    if (el.dataset.pajakAsyncInit) {
        return;
    }

    el.dataset.pajakAsyncInit = '1';
    fetchContent(el, false);
}

function refresh(el: HTMLElement): void {
    el.dataset.pajakAsyncInit = '1';
    fetchContent(el, true);
}

function initAll(): void {
    document.querySelectorAll<HTMLElement>('[data-pajak-async]').forEach(init);
}

export const PajakAsync = {
    initAll,
    init,
    refresh,
} as const;

window.Pajak = { ...window.Pajak, PajakAsync };
