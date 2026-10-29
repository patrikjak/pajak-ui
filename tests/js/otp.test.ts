import { describe, it, expect, vi, beforeEach } from 'vitest';
import { PajakOtp } from '../../resources/assets/js/form/otp';

interface Options {
    length?: number;
    numeric?: boolean;
    autosubmit?: boolean;
    formId?: string;
    submitButton?: boolean;
}

function mount({ length = 6, numeric = true, autosubmit = false, formId = 'otp-form', submitButton = false }: Options = {}): {
    form: HTMLFormElement;
    root: HTMLElement;
    boxes: HTMLInputElement[];
    hidden: HTMLInputElement;
} {
    const boxes = Array.from({ length }, (_, i) => `<input class="pajak-otp__box pajak-otp__box--md" type="text" id="c-digit-${i + 1}">`).join('');

    document.body.innerHTML = `
        <form id="${formId}">
            <div class="pajak-field" data-pajak-otp data-numeric="${numeric}" data-autosubmit="${autosubmit}">
                <label class="pajak-field__label" for="c-digit-1">Code</label>
                <div class="pajak-otp" role="group">
                    ${boxes}
                    <input type="hidden" name="code">
                </div>
                <span class="pajak-field__message pajak-field__message--error">Server said no</span>
            </div>
        </form>
        ${submitButton ? `<button type="button" data-pajak-form="#${formId}" id="ext-submit">Go</button>` : ''}
    `;

    const root = document.querySelector<HTMLElement>('[data-pajak-otp]')!;
    PajakOtp.init(root);

    return {
        form: document.querySelector('form')!,
        root,
        boxes: Array.from(root.querySelectorAll<HTMLInputElement>('.pajak-otp__box')),
        hidden: root.querySelector<HTMLInputElement>('input[type="hidden"]')!,
    };
}

function type(box: HTMLInputElement, value: string, inputType = 'insertText'): void {
    box.value = value;
    box.dispatchEvent(new InputEvent('input', { bubbles: true, inputType }));
}

function paste(box: HTMLInputElement, text: string): Event {
    const event = new Event('paste', { bubbles: true, cancelable: true });
    Object.defineProperty(event, 'clipboardData', { value: { getData: () => text } });
    box.dispatchEvent(event);

    return event;
}

function key(box: HTMLInputElement, name: string): KeyboardEvent {
    const event = new KeyboardEvent('keydown', { key: name, bubbles: true, cancelable: true });
    box.dispatchEvent(event);

    return event;
}

function values(boxes: HTMLInputElement[]): string {
    return boxes.map((box) => box.value || '_').join('');
}

beforeEach(() => {
    document.body.innerHTML = '';
});

describe('PajakOtp typing', () => {
    it('fills a box, advances focus and mirrors the code into the hidden field', () => {
        const { boxes, hidden } = mount();

        type(boxes[0], '4');

        expect(boxes[0].value).toBe('4');
        expect(document.activeElement).toBe(boxes[1]);
        expect(hidden.value).toBe('4');

        type(boxes[1], '2');

        expect(hidden.value).toBe('42');
        expect(document.activeElement).toBe(boxes[2]);
    });

    it('selects the box content on focus so typing replaces it', () => {
        const { boxes } = mount();
        type(boxes[0], '4');

        const select = vi.spyOn(boxes[0], 'select');
        boxes[0].dispatchEvent(new FocusEvent('focus'));

        expect(select).toHaveBeenCalled();
    });

    it('ignores characters the field does not accept and keeps the previous value', () => {
        const { boxes, hidden } = mount();
        type(boxes[0], '4');
        boxes[0].focus();

        type(boxes[0], 'x');

        expect(boxes[0].value).toBe('4');
        expect(hidden.value).toBe('4');
        expect(document.activeElement).toBe(boxes[0]);
    });

    it('clears a box when its content is deleted', () => {
        const { boxes, hidden } = mount();
        type(boxes[0], '4');
        type(boxes[1], '2');

        type(boxes[0], '', 'deleteContentBackward');

        expect(boxes[0].value).toBe('');
        expect(hidden.value).toBe('2');
    });

    it('dispatches input on the hidden field when the code changes', () => {
        const { boxes, hidden } = mount();
        const seen = vi.fn();
        hidden.addEventListener('input', seen);

        type(boxes[0], '4');

        expect(seen).toHaveBeenCalledTimes(1);
    });
});

describe('PajakOtp keyboard', () => {
    it('Backspace in an empty box clears and focuses the previous one', () => {
        const { boxes, hidden } = mount();
        type(boxes[0], '4');
        type(boxes[1], '2');
        boxes[2].focus();

        const event = key(boxes[2], 'Backspace');

        expect(event.defaultPrevented).toBe(true);
        expect(boxes[1].value).toBe('');
        expect(document.activeElement).toBe(boxes[1]);
        expect(hidden.value).toBe('4');
    });

    it('Backspace in a filled box is left to the browser', () => {
        const { boxes } = mount();
        type(boxes[0], '4');

        expect(key(boxes[0], 'Backspace').defaultPrevented).toBe(false);
    });

    it('arrow keys move between boxes and stop at the ends', () => {
        const { boxes } = mount();
        boxes[1].focus();

        key(boxes[1], 'ArrowRight');
        expect(document.activeElement).toBe(boxes[2]);

        key(boxes[2], 'ArrowLeft');
        key(boxes[1], 'ArrowLeft');
        expect(document.activeElement).toBe(boxes[0]);

        expect(key(boxes[0], 'ArrowLeft').defaultPrevented).toBe(false);
    });
});

describe('PajakOtp paste and autofill', () => {
    it('distributes pasted digits from the focused box', () => {
        const { boxes, hidden } = mount();

        const event = paste(boxes[1], '12-3');

        expect(event.defaultPrevented).toBe(true);
        expect(values(boxes)).toBe('_123__');
        expect(hidden.value).toBe('123');
        expect(document.activeElement).toBe(boxes[4]);
    });

    it('starts at the first box when the paste is a full code', () => {
        const { boxes, hidden } = mount();

        paste(boxes[3], '123456');

        expect(values(boxes)).toBe('123456');
        expect(hidden.value).toBe('123456');
        expect(document.activeElement).toBe(boxes[5]);
    });

    it('drops digits that do not fit', () => {
        const { boxes } = mount();

        paste(boxes[4], '789');

        expect(values(boxes)).toBe('____78');
    });

    it('spreads a one-time-code autofill dropped into the first box', () => {
        const { boxes, hidden } = mount();

        type(boxes[0], '123456', 'insertReplacementText');

        expect(values(boxes)).toBe('123456');
        expect(hidden.value).toBe('123456');
    });

    it('works with a different length', () => {
        const { boxes, hidden } = mount({ length: 4 });

        paste(boxes[0], '987654');

        expect(values(boxes)).toBe('9876');
        expect(hidden.value).toBe('9876');
    });
});

describe('PajakOtp numeric option', () => {
    it('strips letters when numeric', () => {
        const { boxes, hidden } = mount();

        paste(boxes[0], 'a1b2c3');

        expect(hidden.value).toBe('123');
    });

    it('accepts letters and digits but not punctuation when numeric is off', () => {
        const { boxes, hidden } = mount({ numeric: false });

        paste(boxes[0], 'ab-12 cd');

        expect(hidden.value).toBe('ab12cd');
    });
});

describe('PajakOtp error state', () => {
    it('clears the boxes, marks them and refocuses the first when the server rejects the code', () => {
        const { form, boxes, hidden } = mount();
        paste(boxes[0], '123456');
        hidden.setAttribute('data-pajak-error-input', '');

        form.dispatchEvent(new CustomEvent('pajak:form:error'));

        expect(values(boxes)).toBe('______');
        expect(hidden.value).toBe('');
        expect(boxes.every((box) => box.classList.contains('pajak-otp__box--error'))).toBe(true);
        expect(document.activeElement).toBe(boxes[0]);
    });

    it('leaves the boxes alone when the error is not about this field', () => {
        const { form, boxes, hidden } = mount();
        paste(boxes[0], '123456');

        form.dispatchEvent(new CustomEvent('pajak:form:error'));

        expect(hidden.value).toBe('123456');
        expect(boxes[0].classList.contains('pajak-otp__box--error')).toBe(false);
    });

    it('removes the error styling and message as soon as the user types', () => {
        const { root, form, boxes, hidden } = mount();
        paste(boxes[0], '123456');
        hidden.setAttribute('data-pajak-error-input', '');
        form.dispatchEvent(new CustomEvent('pajak:form:error'));

        type(boxes[0], '9');

        expect(boxes.some((box) => box.classList.contains('pajak-otp__box--error'))).toBe(false);
        expect(root.querySelector('.pajak-field__message--error')).toBeNull();
        expect(hidden.hasAttribute('data-pajak-error-input')).toBe(false);
    });

    it('removes a server-rendered error on paste', () => {
        const { root, boxes } = mount();

        paste(boxes[0], '1');

        expect(root.querySelector('.pajak-field__message--error')).toBeNull();
    });
});

describe('PajakOtp autosubmit', () => {
    it('does nothing when autosubmit is off', () => {
        const { form, boxes } = mount();
        form.requestSubmit = vi.fn();

        paste(boxes[0], '123456');

        expect(form.requestSubmit).not.toHaveBeenCalled();
    });

    it('submits the form once the last box is filled', () => {
        const { form, boxes } = mount({ autosubmit: true });
        form.requestSubmit = vi.fn();

        paste(boxes[0], '12345');
        expect(form.requestSubmit).not.toHaveBeenCalled();

        type(boxes[5], '6');
        expect(form.requestSubmit).toHaveBeenCalledTimes(1);
    });

    it('clicks the external data-pajak-form button when there is one', () => {
        const { form, boxes } = mount({ autosubmit: true, submitButton: true });
        form.requestSubmit = vi.fn();
        const click = vi.fn();
        document.getElementById('ext-submit')!.addEventListener('click', click);

        paste(boxes[0], '123456');

        expect(click).toHaveBeenCalledTimes(1);
        expect(form.requestSubmit).not.toHaveBeenCalled();
    });

    it('resets on any failed submit so the code can be retyped', () => {
        const { form, boxes, hidden } = mount({ autosubmit: true });
        form.requestSubmit = vi.fn();
        paste(boxes[0], '123456');

        form.dispatchEvent(new CustomEvent('pajak:form:error'));

        expect(hidden.value).toBe('');
        expect(boxes[0].classList.contains('pajak-otp__box--error')).toBe(false);
        expect(document.activeElement).toBe(boxes[0]);
    });
});

describe('PajakOtp lifecycle', () => {
    it('is idempotent: initialising twice returns the same instance', () => {
        const { root } = mount();

        expect(PajakOtp.init(root)).toBe(PajakOtp.init(root));
    });

    it('exposes the value and can clear', () => {
        const { root, boxes } = mount();
        paste(boxes[0], '123');

        const instance = PajakOtp.init(root)!;
        expect(instance.value).toBe('123');

        instance.clear();
        expect(instance.value).toBe('');
        expect(values(boxes)).toBe('______');
    });

    it('stops reacting after destroy', () => {
        const { root, boxes, hidden } = mount();

        PajakOtp.init(root)!.destroy();
        type(boxes[0], '4');

        expect(hidden.value).toBe('');
    });

    it('skips markup without boxes or a hidden field', () => {
        document.body.innerHTML = '<div data-pajak-otp></div>';

        expect(PajakOtp.init(document.querySelector<HTMLElement>('[data-pajak-otp]')!)).toBeNull();
    });
});
