import { describe, it, expect, beforeEach } from 'vitest';
import { PajakSegmented } from '../../resources/assets/js/segmented/segmented';

function make(): HTMLElement {
    document.body.innerHTML = `
        <div data-pajak-segmented>
            <button class="pajak-seg__opt" data-value="light" aria-selected="true">Light</button>
            <button class="pajak-seg__opt" data-value="dark" aria-selected="false">Dark</button>
            <button class="pajak-seg__opt" data-value="system" aria-selected="false" disabled>System</button>
        </div>
    `;
    const el = document.querySelector<HTMLElement>('[data-pajak-segmented]')!;
    PajakSegmented.init(el);

    return el;
}

beforeEach(() => {
    document.body.innerHTML = '';
});

describe('PajakSegmented.select', () => {
    it('selects the option by value and dispatches change', () => {
        const el = make();
        const seen: unknown[] = [];
        el.addEventListener('pajak-segmented:change', (e) => seen.push((e as CustomEvent).detail));

        expect(PajakSegmented.select(el, 'dark')).toBe(true);

        expect(PajakSegmented.value(el)).toBe('dark');
        expect(seen).toEqual([{ value: 'dark', index: 1 }]);
        expect(el.querySelector('[data-value="dark"]')!.getAttribute('tabindex')).toBe('0');
        expect(el.querySelector('[data-value="light"]')!.getAttribute('tabindex')).toBe('-1');
    });

    it('silent select updates state without dispatching', () => {
        const el = make();
        let events = 0;
        el.addEventListener('pajak-segmented:change', () => { events++; });

        PajakSegmented.select(el, 'dark', { silent: true });

        expect(PajakSegmented.value(el)).toBe('dark');
        expect(events).toBe(0);
    });

    it('returns false for unknown or disabled values and leaves the selection', () => {
        const el = make();

        expect(PajakSegmented.select(el, 'nope')).toBe(false);
        expect(PajakSegmented.select(el, 'system')).toBe(false);
        expect(PajakSegmented.value(el)).toBe('light');
    });
});
