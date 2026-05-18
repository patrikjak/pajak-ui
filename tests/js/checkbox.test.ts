import { describe, it, expect } from 'vitest';
import { PajakCheckbox } from '../../resources/assets/js/form/checkbox';

function makeCheckbox(checked = false): HTMLElement {
    const label = document.createElement('label');
    label.className = 'pajak-checkbox';

    const input = document.createElement('input');
    input.type = 'checkbox';
    input.className = 'pajak-checkbox__input';
    input.checked = checked;
    label.appendChild(input);

    return label;
}

describe('PajakCheckbox', () => {
    it('reads initial checked state', () => {
        const el = makeCheckbox(true);
        const instance = PajakCheckbox.init(el);

        expect(instance.checked).toBe(true);
    });

    it('reads initial unchecked state', () => {
        const el = makeCheckbox(false);
        const instance = PajakCheckbox.init(el);

        expect(instance.checked).toBe(false);
    });

    it('setting checked updates the input', () => {
        const el = makeCheckbox(false);
        const instance = PajakCheckbox.init(el);

        instance.checked = true;

        expect(instance.checked).toBe(true);
    });

    it('indeterminate defaults to false', () => {
        const el = makeCheckbox();
        const instance = PajakCheckbox.init(el);

        expect(instance.indeterminate).toBe(false);
    });

    it('setting indeterminate updates the input', () => {
        const el = makeCheckbox();
        const instance = PajakCheckbox.init(el);

        instance.indeterminate = true;

        expect(instance.indeterminate).toBe(true);
    });

    it('emits pajak:checkbox event on change', () => {
        const el = makeCheckbox(false);
        const instance = PajakCheckbox.init(el);

        let emitted: CustomEvent | null = null;
        el.addEventListener('pajak:checkbox', (e) => { emitted = e as CustomEvent; });

        instance.checked = true;

        expect(emitted).not.toBeNull();
        expect((emitted as unknown as CustomEvent).detail.checked).toBe(true);
    });

    it('returns same instance on repeated init', () => {
        const el = makeCheckbox();
        const a = PajakCheckbox.init(el);
        const b = PajakCheckbox.init(el);

        expect(a).toBe(b);
    });

    it('destroy removes event listener', () => {
        const el = makeCheckbox(false);
        const instance = PajakCheckbox.init(el);

        let eventCount = 0;
        el.addEventListener('pajak:checkbox', () => { eventCount++; });

        instance.destroy();
        const input = el.querySelector<HTMLInputElement>('.pajak-checkbox__input')!;
        input.dispatchEvent(new Event('change', { bubbles: true }));

        expect(eventCount).toBe(0);
    });

    it('initAll upgrades all .pajak-checkbox elements', () => {
        const container = document.createElement('div');
        container.appendChild(makeCheckbox());
        container.appendChild(makeCheckbox(true));

        const instances = PajakCheckbox.initAll(container);

        expect(instances).toHaveLength(2);
    });

    it('returns stub instance when input element is missing', () => {
        const label = document.createElement('label');
        label.className = 'pajak-checkbox';

        const instance = PajakCheckbox.init(label);

        expect(instance.checked).toBe(false);
        expect(instance.indeterminate).toBe(false);
    });
});
