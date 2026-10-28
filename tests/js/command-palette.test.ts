import { describe, it, expect, beforeEach, vi } from 'vitest';

function paletteHtml(): string {
    return `
        <button data-pajak-command-palette-trigger="cmdk"><kbd data-pajak-command-palette-shortcut></kbd></button>
        <dialog id="cmdk" data-pajak-command-palette>
            <input data-pajak-command-palette-input role="combobox">
            <div role="listbox">
                <div data-pajak-command-palette-group>
                    <div data-pajak-command-palette-row><a href="/users" data-pajak-command-palette-option><span data-pajak-command-palette-label>Používatelia</span></a></div>
                    <div data-pajak-command-palette-row><a href="/roles" data-pajak-command-palette-option><span data-pajak-command-palette-label>Roles</span><span data-pajak-command-palette-hint>Access</span></a></div>
                </div>
                <div data-pajak-command-palette-group>
                    <div data-pajak-command-palette-row data-keywords="night"><button type="button" data-pajak-command="theme.dark" data-pajak-command-palette-option><span data-pajak-command-palette-label>Dark theme</span></button></div>
                </div>
            </div>
            <p data-pajak-command-palette-empty hidden>Nothing</p>
        </dialog>`;
}

async function setup() {
    vi.resetModules();
    document.body.innerHTML = paletteHtml();

    const dialog = document.querySelector<HTMLDialogElement>('#cmdk')!;
    dialog.showModal = vi.fn(() => { dialog.setAttribute('open', ''); });
    dialog.close = vi.fn(() => { dialog.removeAttribute('open'); });

    const module = await import('../../resources/assets/js/command-palette/command-palette');
    module.PajakCommandPalette.initAll();

    const input = dialog.querySelector<HTMLInputElement>('input')!;
    const rows = () => [...dialog.querySelectorAll<HTMLElement>('[data-pajak-command-palette-row]')];
    const visibleLabels = () => rows().filter((row) => !row.hidden).map((row) => row.textContent?.trim());

    function type(value: string): void {
        input.value = value;
        input.dispatchEvent(new Event('input'));
    }

    return { module, dialog, input, rows, visibleLabels, type };
}

beforeEach(() => {
    document.body.innerHTML = '';
});

describe('normalize and score', () => {
    it('strips diacritics and case', async () => {
        const { normalize } = await import('../../resources/assets/js/command-palette/command-palette');

        expect(normalize('  Používatelia ')).toBe('pouzivatelia');
    });

    it('ranks label prefix above word prefix above substring', async () => {
        const { score } = await import('../../resources/assets/js/command-palette/command-palette');
        const entry = (label: string) => ({ label, hint: '', keywords: '' });

        expect(score(entry('users'), 'us')).toBeGreaterThan(score(entry('invite users'), 'us'));
        expect(score(entry('invite users'), 'us')).toBeGreaterThan(score(entry('status'), 'us'));
        expect(score(entry('status'), 'zzz')).toBe(-1);
    });
});

describe('filtering', () => {
    it('finds a diacritic label with a plain query', async () => {
        const { type, visibleLabels } = await setup();

        type('pouzivatelia');

        expect(visibleLabels()).toEqual(['Používatelia']);
    });

    it('matches hint and keywords and hides empty groups', async () => {
        const { type, visibleLabels, dialog } = await setup();

        type('night');

        expect(visibleLabels()).toEqual(['Dark theme']);
        expect(dialog.querySelectorAll<HTMLElement>('[data-pajak-command-palette-group]')[0].hidden).toBe(true);

        type('access');

        expect(visibleLabels()).toEqual(['RolesAccess']);
    });

    it('shows the empty state when nothing matches', async () => {
        const { type, dialog } = await setup();

        type('qqqq');

        expect(dialog.querySelector<HTMLElement>('[data-pajak-command-palette-empty]')!.hidden).toBe(false);
    });
});

describe('keyboard', () => {
    it('moves the active option with wrap-around and sets aria-activedescendant', async () => {
        const { input, rows } = await setup();

        input.dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowUp' }));

        const last = rows()[2].querySelector('[role], [data-pajak-command-palette-option]')!;

        expect(last.getAttribute('aria-selected')).toBe('true');
        expect(input.getAttribute('aria-activedescendant')).toBe(last.id);
    });

    it('Enter on an event option closes the palette and dispatches pajak:command', async () => {
        const { input, type, dialog } = await setup();
        const listener = vi.fn();
        document.addEventListener('pajak:command', listener);

        type('dark');
        input.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter' }));

        expect(dialog.close).toHaveBeenCalled();
        expect((listener.mock.calls[0][0] as CustomEvent).detail).toEqual({ command: 'theme.dark' });
    });
});

describe('opening', () => {
    it('Ctrl+K opens the first palette and resets the input', async () => {
        const { dialog, input } = await setup();
        input.value = 'stale';

        document.dispatchEvent(new KeyboardEvent('keydown', { key: 'k', ctrlKey: true }));

        expect(dialog.showModal).toHaveBeenCalled();
        expect(input.value).toBe('');
    });

    it('a trigger opens its palette', async () => {
        const { dialog } = await setup();

        document.querySelector<HTMLElement>('[data-pajak-command-palette-trigger]')!.click();

        expect(dialog.showModal).toHaveBeenCalled();
    });
});
