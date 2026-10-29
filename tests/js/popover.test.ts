import { describe, it, expect, beforeEach } from 'vitest';
import { PajakPopover } from '../../resources/assets/js/popover/popover';

interface Geometry {
    anchor: { left: number; top: number };
    trigger: { left: number; top: number; width: number; height: number };
    pop: { left: number; top: number; width: number; height: number };
}

function rect({ left, top, width, height }: { left: number; top: number; width: number; height: number }): DOMRect {
    return { left, top, width, height, right: left + width, bottom: top + height, x: left, y: top, toJSON: () => ({}) } as DOMRect;
}

function define(el: HTMLElement, props: Record<string, unknown>): void {
    Object.entries(props).forEach(([key, value]) => {
        Object.defineProperty(el, key, { configurable: true, value });
    });
}

function mount(placement: string, geometry: Geometry): HTMLElement {
    document.body.innerHTML = `
        <div class="pajak-pop-anchor">
            <button data-pajak-popover-trigger="pop" aria-expanded="false">Open</button>
            <div id="pop" data-pajak-popover data-placement="${placement}" hidden aria-hidden="true">Body</div>
        </div>
    `;

    const anchor = document.querySelector<HTMLElement>('.pajak-pop-anchor')!;
    const trigger = document.querySelector<HTMLElement>('[data-pajak-popover-trigger]')!;
    const pop = document.getElementById('pop')!;

    anchor.getBoundingClientRect = () => rect({ ...geometry.anchor, width: 0, height: 0 });
    trigger.getBoundingClientRect = () => rect(geometry.trigger);
    define(pop, {
        offsetParent: anchor,
        offsetLeft: geometry.pop.left,
        offsetTop: geometry.pop.top,
        offsetWidth: geometry.pop.width,
        offsetHeight: geometry.pop.height,
    });

    PajakPopover.initAll();
    trigger.click();

    return pop;
}

beforeEach(() => {
    document.body.innerHTML = '';
});

describe('PajakPopover arrow positioning', () => {
    it('centres the arrow on a narrow trigger for bottom-end', () => {
        const pop = mount('bottom-end', {
            anchor: { left: 0, top: 0 },
            trigger: { left: 66, top: 0, width: 34, height: 34 },
            pop: { left: -140, top: 46, width: 240, height: 120 },
        });

        expect(pop.style.getPropertyValue('--pajak-pop-arrow-auto')).toBe('11px');
    });

    it('measures from the right edge for bottom-end with room to spare', () => {
        const pop = mount('bottom-end', {
            anchor: { left: 0, top: 0 },
            trigger: { left: 40, top: 0, width: 34, height: 34 },
            pop: { left: -140, top: 46, width: 240, height: 120 },
        });

        expect(pop.style.getPropertyValue('--pajak-pop-arrow-auto')).toBe('37px');
    });

    it('measures from the left edge for bottom', () => {
        const pop = mount('bottom', {
            anchor: { left: 100, top: 0 },
            trigger: { left: 100, top: 0, width: 34, height: 34 },
            pop: { left: 0, top: 46, width: 240, height: 120 },
        });

        expect(pop.style.getPropertyValue('--pajak-pop-arrow-auto')).toBe('11px');
    });

    it('centres the arrow on a wide trigger for top', () => {
        const pop = mount('top', {
            anchor: { left: 0, top: 0 },
            trigger: { left: 0, top: 0, width: 160, height: 36 },
            pop: { left: 0, top: -130, width: 240, height: 120 },
        });

        expect(pop.style.getPropertyValue('--pajak-pop-arrow-auto')).toBe('74px');
    });

    it('measures vertically for left and right placements', () => {
        const pop = mount('right', {
            anchor: { left: 0, top: 0 },
            trigger: { left: 0, top: 0, width: 34, height: 34 },
            pop: { left: 46, top: 0, width: 240, height: 120 },
        });

        expect(pop.style.getPropertyValue('--pajak-pop-arrow-auto')).toBe('11px');
    });

    it('keeps the arrow clear of the rounded corner on the near side', () => {
        const pop = mount('bottom', {
            anchor: { left: 0, top: 0 },
            trigger: { left: -20, top: 0, width: 34, height: 34 },
            pop: { left: 0, top: 46, width: 240, height: 120 },
        });

        expect(pop.style.getPropertyValue('--pajak-pop-arrow-auto')).toBe('8px');
    });

    it('clamps so the arrow never leaves the popover', () => {
        const pop = mount('bottom', {
            anchor: { left: 0, top: 0 },
            trigger: { left: 500, top: 0, width: 34, height: 34 },
            pop: { left: 0, top: 46, width: 240, height: 120 },
        });

        expect(pop.style.getPropertyValue('--pajak-pop-arrow-auto')).toBe('216px');
    });

    it('does nothing when the popover has no offsetParent', () => {
        document.body.innerHTML = `
            <button data-pajak-popover-trigger="pop">Open</button>
            <div id="pop" data-pajak-popover data-placement="bottom" hidden></div>
        `;

        PajakPopover.initAll();
        document.querySelector<HTMLElement>('[data-pajak-popover-trigger]')!.click();

        expect(document.getElementById('pop')!.style.getPropertyValue('--pajak-pop-arrow-auto')).toBe('');
    });
});
