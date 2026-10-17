import { describe, it, expect, beforeEach, vi } from 'vitest';

const STORAGE_KEY = 'pajak_redirect_toast';

beforeEach(() => {
    document.body.innerHTML = '';
    sessionStorage.clear();
});

async function freshToastModule() {
    vi.resetModules();

    return import('../../resources/assets/js/toast/toast');
}

describe('PajakToast.stashForRedirect', () => {
    it('writes the toast to sessionStorage without rendering it', async () => {
        const { PajakToast } = await freshToastModule();

        PajakToast.stashForRedirect({ type: 'success', title: 'Saved' });

        expect(document.querySelector('.pajak-toast')).toBeNull();
        expect(sessionStorage.getItem(STORAGE_KEY)).toBe(JSON.stringify({ type: 'success', title: 'Saved' }));
    });
});

describe('redirect toast replay', () => {
    it('shows a stashed toast on the next page load and clears it', async () => {
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify({ type: 'success', title: 'Saved' }));

        await freshToastModule();

        expect(document.querySelector('.pajak-toast--success')).not.toBeNull();
        expect(document.querySelector('.pajak-toast__title')?.textContent).toBe('Saved');
        expect(sessionStorage.getItem(STORAGE_KEY)).toBeNull();
    });

    it('does nothing when no toast was stashed', async () => {
        await freshToastModule();

        expect(document.querySelector('.pajak-toast')).toBeNull();
    });

    it('ignores malformed stashed data instead of throwing', async () => {
        sessionStorage.setItem(STORAGE_KEY, '{not json');

        await expect(freshToastModule()).resolves.toBeDefined();
        expect(document.querySelector('.pajak-toast')).toBeNull();
    });
});
