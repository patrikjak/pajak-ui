import { describe, it, expect } from 'vitest';
import { PajakToggle } from '../../resources/assets/js/form/toggle';

function makeToggle(checked = false): HTMLElement {
    const label = document.createElement('label');
    label.className = 'pajak-toggle';

    const input = document.createElement('input');
    input.type = 'checkbox';
    input.className = 'pajak-toggle__input';
    input.checked = checked;
    label.appendChild(input);

    return label;
}

describe('PajakToggle', () => {
    it('reads initial checked state', () => {
        const el = makeToggle(true);
        const instance = PajakToggle.init(el);

        expect(instance.checked).toBe(true);
    });

    it('reads initial unchecked state', () => {
        const el = makeToggle(false);
        const instance = PajakToggle.init(el);

        expect(instance.checked).toBe(false);
    });

    it('setting checked updates the input', () => {
        const el = makeToggle(false);
        const instance = PajakToggle.init(el);

        instance.checked = true;

        expect(instance.checked).toBe(true);
    });

    it('emits pajak:toggle event on change', () => {
        const el = makeToggle(false);
        const instance = PajakToggle.init(el);

        let emitted: CustomEvent | null = null;
        el.addEventListener('pajak:toggle', (e) => { emitted = e as CustomEvent; });

        instance.checked = true;

        expect(emitted).not.toBeNull();
        expect((emitted as unknown as CustomEvent).detail.checked).toBe(true);
    });

    it('returns same instance on repeated init', () => {
        const el = makeToggle();
        const a = PajakToggle.init(el);
        const b = PajakToggle.init(el);

        expect(a).toBe(b);
    });

    it('destroy removes event listener', () => {
        const el = makeToggle(false);
        const instance = PajakToggle.init(el);

        let eventCount = 0;
        el.addEventListener('pajak:toggle', () => { eventCount++; });

        instance.destroy();
        const input = el.querySelector<HTMLInputElement>('.pajak-toggle__input')!;
        input.dispatchEvent(new Event('change', { bubbles: true }));

        expect(eventCount).toBe(0);
    });

    it('initAll upgrades all .pajak-toggle elements', () => {
        const container = document.createElement('div');
        container.appendChild(makeToggle());
        container.appendChild(makeToggle(true));

        const instances = PajakToggle.initAll(container);

        expect(instances).toHaveLength(2);
    });

    it('returns stub instance when input element is missing', () => {
        const label = document.createElement('label');
        label.className = 'pajak-toggle';

        const instance = PajakToggle.init(label);

        expect(instance.checked).toBe(false);
    });
});
