import { describe, it, expect, beforeEach } from 'vitest';
import { PajakColorPicker } from '../../resources/assets/js/form/color-picker';

function makePicker(value = '', { inline = false, presets = [] as string[] } = {}): HTMLElement {
    const root = document.createElement('div');
    root.className = `pajak-color-picker${inline ? ' pajak-color-picker--inline' : ''}`;
    root.setAttribute('data-pajak-color-picker', '');
    root.innerHTML = `
        ${inline ? '' : '<button type="button" class="pajak-color-picker__swatch is-empty" data-pajak-color-swatch aria-expanded="false"></button>'}
        <input type="text" name="c" data-pajak-color-text value="${value}">
        <div data-pajak-color-panel ${inline ? '' : 'hidden'}>
            <div data-pajak-color-area tabindex="0"><span data-pajak-color-handle></span></div>
            <div data-pajak-color-hue tabindex="0"><span data-pajak-color-hue-handle></span></div>
            ${presets.map((p) => `<button type="button" data-pajak-color-preset="${p}"></button>`).join('')}
        </div>
    `;
    document.body.appendChild(root);

    const rect = { left: 0, top: 0, width: 200, height: 100, right: 200, bottom: 100, x: 0, y: 0, toJSON: () => ({}) };
    root.querySelector('[data-pajak-color-area]')!.getBoundingClientRect = () => rect as DOMRect;
    root.querySelector('[data-pajak-color-hue]')!.getBoundingClientRect = () => rect as DOMRect;

    return root;
}

const text = (root: HTMLElement): HTMLInputElement => root.querySelector('[data-pajak-color-text]')!;
const swatch = (root: HTMLElement): HTMLButtonElement => root.querySelector('[data-pajak-color-swatch]')!;
const panel = (root: HTMLElement): HTMLElement => root.querySelector('[data-pajak-color-panel]')!;
const area = (root: HTMLElement): HTMLElement => root.querySelector('[data-pajak-color-area]')!;
const hue = (root: HTMLElement): HTMLElement => root.querySelector('[data-pajak-color-hue]')!;

function pointer(el: Element, type: string, x: number, y: number): void {
    const event = new MouseEvent(type, { bubbles: true, cancelable: true, clientX: x, clientY: y });
    el.dispatchEvent(event);
}

beforeEach(() => {
    document.body.innerHTML = '';
});

describe('PajakColorPicker', () => {
    it('reflects a valid initial value on the root and swatch', () => {
        const root = makePicker('#2A9D8F');
        PajakColorPicker.init(root);

        expect(root.style.getPropertyValue('--pajak-color')).toBe('#2A9D8F');
        expect(swatch(root).classList.contains('is-empty')).toBe(false);
    });

    it('keeps the swatch empty for an empty or invalid value', () => {
        const root = makePicker('nope');
        PajakColorPicker.init(root);

        expect(swatch(root).classList.contains('is-empty')).toBe(true);
        expect(root.style.getPropertyValue('--pajak-color')).toBe('');
    });

    it('typing a valid hex updates the swatch and handle position', () => {
        const root = makePicker();
        PajakColorPicker.init(root);

        text(root).value = '#FF0000';
        text(root).dispatchEvent(new Event('input', { bubbles: true }));

        expect(root.style.getPropertyValue('--pajak-color')).toBe('#FF0000');
        expect(swatch(root).classList.contains('is-empty')).toBe(false);
        const handle = root.querySelector<HTMLElement>('[data-pajak-color-handle]')!;
        expect(handle.style.left).toBe('100%');
        expect(handle.style.top).toBe('0%');
    });

    it('dragging the area sets saturation and brightness and writes the hex', () => {
        const root = makePicker('#FF0000');
        PajakColorPicker.init(root);
        let inputs = 0;
        text(root).addEventListener('input', () => { inputs++; });

        pointer(area(root), 'pointerdown', 100, 50); // s = 0.5, v = 0.5

        expect(text(root).value).toBe('#804040');
        expect(inputs).toBe(1);

        pointer(area(root), 'pointermove', 0, 0); // s = 0, v = 1 -> white
        expect(text(root).value).toBe('#FFFFFF');

        pointer(area(root), 'pointerup', 0, 0);
        pointer(area(root), 'pointermove', 200, 100);
        expect(text(root).value).toBe('#FFFFFF');
    });

    it('dragging the hue slider changes the hue and keeps saturation/brightness', () => {
        const root = makePicker('#FF0000');
        PajakColorPicker.init(root);

        pointer(hue(root), 'pointerdown', 100, 5); // h = 180

        expect(text(root).value).toBe('#00FFFF');
        expect(hue(root).getAttribute('aria-valuenow')).toBe('180');
    });

    it('keeps the hue when moving through grey', () => {
        const root = makePicker('#0000FF');
        PajakColorPicker.init(root);

        pointer(area(root), 'pointerdown', 0, 50); // grey: s = 0
        pointer(area(root), 'pointerup', 0, 50);
        pointer(area(root), 'pointerdown', 200, 0); // fully saturated again

        expect(text(root).value).toBe('#0000FF');
    });

    it('supports keyboard control of the area and hue', () => {
        const root = makePicker('#FF0000');
        PajakColorPicker.init(root);

        area(root).dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowLeft', shiftKey: true, bubbles: true }));
        expect(text(root).value).toBe('#FF1919');

        hue(root).dispatchEvent(new KeyboardEvent('keydown', { key: 'End', bubbles: true }));
        expect(hue(root).getAttribute('aria-valuenow')).toBe('360');
    });

    it('picking a preset sets the colour and marks it selected', () => {
        const root = makePicker('', { presets: ['#E76F51', '#2A9D8F'] });
        PajakColorPicker.init(root);

        root.querySelector<HTMLButtonElement>('[data-pajak-color-preset="#2A9D8F"]')!.click();

        expect(text(root).value).toBe('#2A9D8F');
        expect(root.querySelector('[data-pajak-color-preset="#2A9D8F"]')!.classList.contains('is-selected')).toBe(true);
        expect(root.querySelector('[data-pajak-color-preset="#E76F51"]')!.classList.contains('is-selected')).toBe(false);
    });

    it('opens and closes the panel from the swatch, Escape and outside clicks', () => {
        const root = makePicker('#2A9D8F');
        PajakColorPicker.init(root);

        swatch(root).click();
        expect(panel(root).hidden).toBe(false);
        expect(swatch(root).getAttribute('aria-expanded')).toBe('true');

        root.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape', bubbles: true }));
        expect(panel(root).hidden).toBe(true);
        expect(swatch(root).getAttribute('aria-expanded')).toBe('false');

        swatch(root).click();
        document.body.dispatchEvent(new MouseEvent('pointerdown', { bubbles: true }));
        expect(panel(root).hidden).toBe(true);

        swatch(root).click();
        area(root).dispatchEvent(new MouseEvent('pointerdown', { bubbles: true }));
        expect(panel(root).hidden).toBe(false);
    });

    it('does not open when the swatch is disabled', () => {
        const root = makePicker('#2A9D8F');
        swatch(root).disabled = true;
        const instance = PajakColorPicker.init(root);

        instance.open();

        expect(panel(root).hidden).toBe(true);
    });

    it('inline pickers keep the panel open', () => {
        const root = makePicker('#2A9D8F', { inline: true });
        const instance = PajakColorPicker.init(root);

        instance.close();

        expect(panel(root).hidden).toBe(false);
    });

    it('emits pajak:color and supports a value setter', () => {
        const root = makePicker();
        const instance = PajakColorPicker.init(root);
        const seen: string[] = [];
        root.addEventListener('pajak:color', (event) => seen.push((event as CustomEvent).detail.value));

        instance.value = '#00FF00';

        expect(instance.value).toBe('#00FF00');
        expect(root.style.getPropertyValue('--pajak-color')).toBe('#00FF00');
        expect(seen).toEqual(['#00FF00']);
    });

    it('initAll upgrades each picker once', () => {
        makePicker();
        makePicker();

        const first = PajakColorPicker.initAll(document);
        const second = PajakColorPicker.initAll(document);

        expect(first).toHaveLength(2);
        expect(second[0]).toBe(first[0]);
    });
});
