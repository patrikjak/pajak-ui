import { describe, it, expect, vi, beforeEach } from 'vitest';
import { PajakAsync } from '../../resources/assets/js/async/async';

beforeEach(() => {
    vi.restoreAllMocks();
});

function makeAsyncEl(): HTMLElement {
    const el = document.createElement('div');
    el.className = 'pajak-async';
    el.dataset.url = '/fragment';

    const content = document.createElement('div');
    content.className = 'pajak-async__content';
    el.appendChild(content);

    return el;
}

function mockFetchText(body: string, ok = true, status = ok ? 200 : 500): void {
    vi.stubGlobal('fetch', vi.fn().mockResolvedValue({
        ok,
        status,
        text: () => Promise.resolve(body),
    }));
}

function waitForLoaded(el: HTMLElement): Promise<void> {
    return new Promise((resolve) => {
        el.addEventListener('pajak:async:loaded', () => resolve(), { once: true });
    });
}

describe('PajakAsync', () => {
    it('sets data-count to the number of loaded children', async () => {
        mockFetchText('<div>One</div><div>Two</div><div>Three</div>');
        const el = makeAsyncEl();

        PajakAsync.init(el);
        await waitForLoaded(el);

        expect(el.dataset.count).toBe('3');
    });

    it('sets data-count="0" and fires pajak:async:empty for an empty response', async () => {
        mockFetchText('');
        const el = makeAsyncEl();

        let emptyFired = false;
        el.addEventListener('pajak:async:empty', () => { emptyFired = true; });

        PajakAsync.init(el);
        await waitForLoaded(el);

        expect(el.dataset.count).toBe('0');
        expect(emptyFired).toBe(true);
    });

    it('does not fire pajak:async:empty when content was loaded', async () => {
        mockFetchText('<div>One</div>');
        const el = makeAsyncEl();

        let emptyFired = false;
        el.addEventListener('pajak:async:empty', () => { emptyFired = true; });

        PajakAsync.init(el);
        await waitForLoaded(el);

        expect(emptyFired).toBe(false);
    });

    it('updates data-count after refresh() with different content', async () => {
        mockFetchText('<div>One</div>');
        const el = makeAsyncEl();

        PajakAsync.init(el);
        await waitForLoaded(el);
        expect(el.dataset.count).toBe('1');

        mockFetchText('<div>One</div><div>Two</div>');
        const refreshed = waitForLoaded(el);
        PajakAsync.refresh(el);
        await refreshed;

        expect(el.dataset.count).toBe('2');
    });
});
