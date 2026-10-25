import { describe, it, expect, beforeEach } from 'vitest';
import { PajakTabs } from '../../resources/assets/js/tabs/tabs';

function setup(withPanels: boolean): { root: HTMLElement; tabs: HTMLButtonElement[]; panels: HTMLElement[] } {
    document.body.innerHTML = `
        <div data-pajak-tabs>
            <button class="pajak-tab" aria-selected="true" ${withPanels ? 'aria-controls="p1"' : ''}>One</button>
            <button class="pajak-tab" aria-selected="false" ${withPanels ? 'aria-controls="p2"' : ''}>Two</button>
            <button class="pajak-tab" aria-selected="false" disabled ${withPanels ? 'aria-controls="p3"' : ''}>Three</button>
        </div>
        ${withPanels ? '<div id="p1">1</div><div id="p2" hidden>2</div><div id="p3" hidden>3</div>' : ''}
    `;

    const root = document.querySelector<HTMLElement>('[data-pajak-tabs]')!;
    PajakTabs.init(root);

    return {
        root,
        tabs: Array.from(root.querySelectorAll<HTMLButtonElement>('.pajak-tab')),
        panels: withPanels ? ['p1', 'p2', 'p3'].map((id) => document.getElementById(id)!) : [],
    };
}

beforeEach(() => {
    document.body.innerHTML = '';
});

describe('PajakTabs panels', () => {
    it('shows only the selected tab panel on init', () => {
        const { panels } = setup(true);

        expect(panels.map((p) => p.hidden)).toEqual([false, true, true]);
    });

    it('switches panels when a tab is clicked', () => {
        const { tabs, panels } = setup(true);

        tabs[1].click();

        expect(panels.map((p) => p.hidden)).toEqual([true, false, true]);
        expect(tabs[1].getAttribute('aria-selected')).toBe('true');
        expect(tabs[0].getAttribute('aria-selected')).toBe('false');
    });

    it('does not switch to a disabled tab', () => {
        const { tabs, panels } = setup(true);

        tabs[2].click();

        expect(panels.map((p) => p.hidden)).toEqual([false, true, true]);
    });

    it('switches panels with the arrow keys', () => {
        const { root, panels } = setup(true);

        root.dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowRight', bubbles: true }));

        expect(panels.map((p) => p.hidden)).toEqual([true, false, true]);
    });

    it('emits pajak-tabs:change with the controlled panel id', () => {
        const { root, tabs } = setup(true);
        const seen: unknown[] = [];
        root.addEventListener('pajak-tabs:change', (e) => seen.push((e as CustomEvent).detail));

        tabs[1].click();
        tabs[1].click();

        expect(seen).toEqual([{ index: 1, controls: 'p2' }]);
    });

    it('leaves panels alone when tabs have no aria-controls', () => {
        const { tabs } = setup(false);

        tabs[1].click();

        expect(tabs[1].getAttribute('aria-selected')).toBe('true');
        expect(document.querySelectorAll('[hidden]')).toHaveLength(0);
    });

    it('initAll(root) only initialises tabs inside root', () => {
        document.body.innerHTML = `
            <section id="a"><div data-pajak-tabs><button class="pajak-tab" aria-selected="true">A</button><button class="pajak-tab">B</button></div></section>
            <section id="b"><div data-pajak-tabs><button class="pajak-tab" aria-selected="true">C</button><button class="pajak-tab">D</button></div></section>
        `;

        PajakTabs.initAll(document.getElementById('a')!);

        const tabsA = document.querySelectorAll('#a .pajak-tab');
        const tabsB = document.querySelectorAll('#b .pajak-tab');
        expect(tabsA[1].getAttribute('tabindex')).toBe('-1');
        expect(tabsB[1].getAttribute('tabindex')).toBeNull();
    });
});
