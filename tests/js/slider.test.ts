import { describe, it, expect, vi } from 'vitest';
import { snap, pctFromPointer } from '../../resources/assets/js/form/slider';

describe('snap', () => {
    it('rounds to nearest step', () => {
        expect(snap(26, 0, 100, 10)).toBe(30);
        expect(snap(24, 0, 100, 10)).toBe(20);
    });

    it('clamps to min', () => {
        expect(snap(-5, 0, 100, 1)).toBe(0);
    });

    it('clamps to max', () => {
        expect(snap(110, 0, 100, 1)).toBe(100);
    });

    it('returns exact value when already on step', () => {
        expect(snap(50, 0, 100, 10)).toBe(50);
    });

    it('works with fractional steps', () => {
        expect(snap(0.7, 0, 1, 0.25)).toBe(0.75);
    });

    it('works with non-zero min', () => {
        expect(snap(23, 20, 30, 5)).toBe(25);
    });
});

describe('pctFromPointer', () => {
    it('returns 0 at left edge', () => {
        const wrap = document.createElement('div');
        vi.spyOn(wrap, 'getBoundingClientRect').mockReturnValue({
            left: 100, right: 300, width: 200, top: 0, bottom: 0, height: 0, x: 100, y: 0, toJSON: () => {},
        });
        Object.defineProperty(window, 'getComputedStyle', {
            value: () => ({ paddingLeft: '0px' }),
            configurable: true,
        });

        expect(pctFromPointer(wrap, 100)).toBe(0);
    });

    it('returns 1 at right edge', () => {
        const wrap = document.createElement('div');
        vi.spyOn(wrap, 'getBoundingClientRect').mockReturnValue({
            left: 100, right: 300, width: 200, top: 0, bottom: 0, height: 0, x: 100, y: 0, toJSON: () => {},
        });
        Object.defineProperty(window, 'getComputedStyle', {
            value: () => ({ paddingLeft: '0px' }),
            configurable: true,
        });

        expect(pctFromPointer(wrap, 300)).toBe(1);
    });

    it('returns 0.5 at midpoint', () => {
        const wrap = document.createElement('div');
        vi.spyOn(wrap, 'getBoundingClientRect').mockReturnValue({
            left: 0, right: 200, width: 200, top: 0, bottom: 0, height: 0, x: 0, y: 0, toJSON: () => {},
        });
        Object.defineProperty(window, 'getComputedStyle', {
            value: () => ({ paddingLeft: '0px' }),
            configurable: true,
        });

        expect(pctFromPointer(wrap, 100)).toBe(0.5);
    });

    it('clamps below 0', () => {
        const wrap = document.createElement('div');
        vi.spyOn(wrap, 'getBoundingClientRect').mockReturnValue({
            left: 100, right: 300, width: 200, top: 0, bottom: 0, height: 0, x: 100, y: 0, toJSON: () => {},
        });
        Object.defineProperty(window, 'getComputedStyle', {
            value: () => ({ paddingLeft: '0px' }),
            configurable: true,
        });

        expect(pctFromPointer(wrap, 0)).toBe(0);
    });

    it('clamps above 1', () => {
        const wrap = document.createElement('div');
        vi.spyOn(wrap, 'getBoundingClientRect').mockReturnValue({
            left: 100, right: 300, width: 200, top: 0, bottom: 0, height: 0, x: 100, y: 0, toJSON: () => {},
        });
        Object.defineProperty(window, 'getComputedStyle', {
            value: () => ({ paddingLeft: '0px' }),
            configurable: true,
        });

        expect(pctFromPointer(wrap, 500)).toBe(1);
    });
});
