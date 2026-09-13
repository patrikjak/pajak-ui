import { describe, it, expect } from 'vitest';
import { PajakButton } from '../../resources/assets/js/form/form-submit';

function makeIconLabelButton(): HTMLButtonElement {
    const btn = document.createElement('button');
    btn.className = 'pajak-btn';

    const label = document.createElement('span');
    label.className = 'pajak-btn__label';

    const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    icon.setAttribute('class', 'ex-icon');
    label.appendChild(icon);
    label.appendChild(document.createTextNode('Save'));

    btn.appendChild(label);

    return btn;
}

describe('PajakButton', () => {
    it('adds is-loading class and a spinner as the first child', () => {
        const btn = makeIconLabelButton();

        PajakButton.startLoading(btn);

        expect(btn.classList.contains('is-loading')).toBe(true);
        expect(btn.firstElementChild?.classList.contains('pajak-btn__spinner')).toBe(true);
    });

    it('removes is-loading class and the spinner on stop', () => {
        const btn = makeIconLabelButton();

        PajakButton.startLoading(btn);
        PajakButton.stopLoading(btn);

        expect(btn.classList.contains('is-loading')).toBe(false);
        expect(btn.querySelector('.pajak-btn__spinner')).toBeNull();
    });

    it('restores the original icon and text after loadingText is used', () => {
        const btn = makeIconLabelButton();
        const label = btn.querySelector<HTMLElement>('.pajak-btn__label')!;

        PajakButton.startLoading(btn, 'Saving…');

        expect(label.textContent).toBe('Saving…');
        expect(label.querySelector('svg')).toBeNull();

        PajakButton.stopLoading(btn);

        expect(label.querySelector('svg.ex-icon')).not.toBeNull();
        expect(label.textContent).toBe('Save');
    });

    it('does not overwrite the saved original content on a repeated startLoading call', () => {
        const btn = makeIconLabelButton();
        const label = btn.querySelector<HTMLElement>('.pajak-btn__label')!;

        PajakButton.startLoading(btn, 'Saving…');
        PajakButton.startLoading(btn, 'Still saving…');
        PajakButton.stopLoading(btn);

        expect(label.querySelector('svg.ex-icon')).not.toBeNull();
        expect(label.textContent).toBe('Save');
    });

    it('leaves label content untouched when no loadingText is given', () => {
        const btn = makeIconLabelButton();
        const label = btn.querySelector<HTMLElement>('.pajak-btn__label')!;

        PajakButton.startLoading(btn);
        PajakButton.stopLoading(btn);

        expect(label.querySelector('svg.ex-icon')).not.toBeNull();
        expect(label.textContent).toBe('Save');
    });
});
