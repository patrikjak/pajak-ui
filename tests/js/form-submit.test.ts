import { describe, it, expect, vi, beforeEach } from 'vitest';
import { PajakButton, initFormSubmitLoaders } from '../../resources/assets/js/form/form-submit';
import { PajakToast } from '../../resources/assets/js/toast/toast';

beforeEach(() => {
    vi.restoreAllMocks();
    document.body.innerHTML = '';
    sessionStorage.clear();
});

function makeForm(): HTMLFormElement {
    const form = document.createElement('form');
    form.setAttribute('data-pajak-form', '');
    form.action = 'https://example.test/submit';
    form.method = 'POST';
    document.body.appendChild(form);

    return form;
}

function mockFetchJson(body: unknown, status: number): void {
    vi.stubGlobal('fetch', vi.fn().mockResolvedValue({
        ok: status >= 200 && status < 300,
        status,
        json: () => Promise.resolve(body),
    }));
}

function submit(form: HTMLFormElement): void {
    initFormSubmitLoaders(document);
    form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
}

async function flushPromises(): Promise<void> {
    for (let i = 0; i < 6; i++) {
        await Promise.resolve();
    }
}

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

describe('422 handling', () => {
    it('falls back to a toast built from the top-level message when no error field matches an input', async () => {
        mockFetchJson({
            message: 'You must set a password before disconnecting your only login method.',
            errors: { provider: ['You must set a password before disconnecting your only login method.'] },
        }, 422);

        const form = makeForm();
        submit(form);

        await vi.waitFor(() => {
            expect(document.querySelector('.pajak-toast--error')).not.toBeNull();
        });

        expect(document.querySelector('.pajak-toast__title')?.textContent).toBe(
            'You must set a password before disconnecting your only login method.',
        );
        expect(form.querySelector('.pajak-field__message--error')).toBeNull();
    });

    it('does not show a fallback toast when an error field matches a real input', async () => {
        mockFetchJson({
            message: 'The email field is invalid.',
            errors: { email: ['The email field is invalid.'] },
        }, 422);

        const form = makeForm();
        const field = document.createElement('div');
        field.className = 'pajak-field';
        const input = document.createElement('input');
        input.name = 'email';
        field.appendChild(input);
        form.appendChild(field);

        submit(form);

        await vi.waitFor(() => {
            expect(form.querySelector('.pajak-field__message--error')).not.toBeNull();
        });

        expect(document.querySelector('.pajak-toast')).toBeNull();
    });

    it('does not show a fallback toast when the form opts out via data-no-toast', async () => {
        mockFetchJson({
            message: 'You must set a password before disconnecting your only login method.',
            errors: { provider: ['You must set a password before disconnecting your only login method.'] },
        }, 422);

        const form = makeForm();
        form.setAttribute('data-no-toast', '');

        submit(form);
        await flushPromises();

        expect(document.querySelector('.pajak-toast')).toBeNull();
    });
});

describe('nested error keys', () => {
    function addInput(form: HTMLFormElement, name: string): HTMLInputElement {
        const field = document.createElement('div');
        field.className = 'pajak-field';
        const input = document.createElement('input');
        input.name = name;
        field.appendChild(input);
        form.appendChild(field);

        return input;
    }

    async function errorsFor(errors: Record<string, string[]>, form: HTMLFormElement): Promise<void> {
        mockFetchJson({ message: 'Invalid', errors }, 422);
        submit(form);

        await vi.waitFor(() => {
            expect(form.querySelector('.pajak-field__message--error')).not.toBeNull();
        });
    }

    it('maps an `a.b` key to the a[b] input', async () => {
        const form = makeForm();
        const input = addInput(form, 'address[city]');

        await errorsFor({ 'address.city': ['City is required.'] }, form);

        expect(input.hasAttribute('data-pajak-error-input')).toBe(true);
        expect(document.querySelector('.pajak-toast')).toBeNull();
    });

    it('maps an `a.b.c` key to the a[b][c] input', async () => {
        const form = makeForm();
        const input = addInput(form, 'a[b][c]');

        await errorsFor({ 'a.b.c': ['Bad.'] }, form);

        expect(input.hasAttribute('data-pajak-error-input')).toBe(true);
    });

    it('maps an `a.N` key to the Nth a[] input', async () => {
        const form = makeForm();
        addInput(form, 'permissions[]');
        addInput(form, 'permissions[]');
        const third = addInput(form, 'permissions[]');

        await errorsFor({ 'permissions.2': ['Unknown permission.'] }, form);

        expect(third.hasAttribute('data-pajak-error-input')).toBe(true);
        expect(form.querySelectorAll('[data-pajak-error-input]')).toHaveLength(1);
    });

    it('prefers an exact a[N] input over the positional match', async () => {
        const form = makeForm();
        addInput(form, 'tags[]');
        const exact = addInput(form, 'tags[1]');

        await errorsFor({ 'tags.1': ['Bad tag.'] }, form);

        expect(exact.hasAttribute('data-pajak-error-input')).toBe(true);
    });

    it('still falls back to a toast when a nested key matches nothing', async () => {
        mockFetchJson({ message: 'Nope', errors: { 'a.9': ['Nope'] } }, 422);

        const form = makeForm();
        addInput(form, 'a[]');
        submit(form);

        await vi.waitFor(() => {
            expect(document.querySelector('.pajak-toast--error')).not.toBeNull();
        });
    });
});

describe('redirect with a success toast', () => {
    it('stashes the toast instead of rendering it before navigating away', async () => {
        mockFetchJson({ toast: { type: 'success', title: 'Saved' } }, 200);
        const stash = vi.spyOn(PajakToast, 'stashForRedirect');

        const form = makeForm();
        form.dataset.redirect = '/thanks';

        submit(form);
        await flushPromises();

        expect(stash).toHaveBeenCalledWith({ type: 'success', title: 'Saved' });
        expect(document.querySelector('.pajak-toast')).toBeNull();
    });

    it('does not stash a toast when the form opts out via data-no-toast', async () => {
        mockFetchJson({ toast: { type: 'success', title: 'Saved' } }, 200);
        const stash = vi.spyOn(PajakToast, 'stashForRedirect');

        const form = makeForm();
        form.dataset.redirect = '/thanks';
        form.setAttribute('data-no-toast', '');

        submit(form);
        await flushPromises();

        expect(stash).not.toHaveBeenCalled();
    });
});
