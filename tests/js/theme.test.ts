import { describe, it, expect, beforeEach, vi } from 'vitest';

function stubLocalStorage(): void {
    const data = new Map<string, string>();

    vi.stubGlobal('localStorage', {
        getItem: (key: string) => data.get(key) ?? null,
        setItem: (key: string, value: string) => { data.set(key, value); },
        removeItem: (key: string) => { data.delete(key); },
        clear: () => { data.clear(); },
    });
}

function stubMatchMedia(dark: boolean): void {
    vi.stubGlobal('matchMedia', (query: string) => ({
        matches: dark && query.includes('dark'),
        media: query,
        addEventListener: () => {},
    }));
}

async function loadTheme(dark = false) {
    vi.resetModules();
    stubMatchMedia(dark);

    return (await import('../../resources/assets/js/theme/theme')).PajakTheme;
}

beforeEach(() => {
    document.body.innerHTML = '';
    document.documentElement.removeAttribute('data-theme');
    stubLocalStorage();
});

describe('PajakTheme', () => {
    it('does not touch data-theme when no preference was ever stored', async () => {
        const theme = await loadTheme(true);

        expect(theme.get()).toBe('system');
        expect(document.documentElement.hasAttribute('data-theme')).toBe(false);
    });

    it('apply() resolves system from prefers-color-scheme', async () => {
        const dark = await loadTheme(true);
        dark.apply();
        expect(dark.resolved()).toBe('dark');
        expect(document.documentElement.getAttribute('data-theme')).toBe('dark');

        const light = await loadTheme(false);
        light.apply();
        expect(light.resolved()).toBe('light');
        expect(document.documentElement.getAttribute('data-theme')).toBe('light');
    });

    it('applies a stored system preference on load using the OS setting', async () => {
        localStorage.setItem('pajak-theme', 'system');

        await loadTheme(true);

        expect(document.documentElement.getAttribute('data-theme')).toBe('dark');
    });

    it('set persists the mode, applies it and emits pajak:theme', async () => {
        const theme = await loadTheme(false);
        const seen: unknown[] = [];
        document.addEventListener('pajak:theme', (e) => seen.push((e as CustomEvent).detail));

        theme.set('dark');

        expect(localStorage.getItem('pajak-theme')).toBe('dark');
        expect(document.documentElement.getAttribute('data-theme')).toBe('dark');
        expect(seen).toEqual([{ mode: 'dark', resolved: 'dark' }]);
    });

    it('applies a stored mode on load and ignores an invalid stored value', async () => {
        localStorage.setItem('pajak-theme', 'dark');
        expect((await loadTheme(false)).get()).toBe('dark');

        localStorage.setItem('pajak-theme', 'purple');
        expect((await loadTheme(false)).get()).toBe('system');
    });

    it('binds a segmented switcher: reflects the stored mode and updates on change', async () => {
        localStorage.setItem('pajak-theme', 'dark');
        document.body.innerHTML = `
            <div data-pajak-segmented data-pajak-theme-switch>
                <button class="pajak-seg__opt" data-value="light" aria-selected="true">Light</button>
                <button class="pajak-seg__opt" data-value="dark" aria-selected="false">Dark</button>
                <button class="pajak-seg__opt" data-value="system" aria-selected="false">System</button>
            </div>
        `;
        const theme = await loadTheme(false);
        const switcher = document.querySelector<HTMLElement>('[data-pajak-theme-switch]')!;

        theme.initAll();
        expect(switcher.querySelector('[data-value="dark"]')!.getAttribute('aria-selected')).toBe('true');

        switcher.querySelector<HTMLButtonElement>('[data-value="light"]')!.click();

        expect(theme.get()).toBe('light');
        expect(document.documentElement.getAttribute('data-theme')).toBe('light');
    });
});
