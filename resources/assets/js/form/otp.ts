import { createRegistry } from '../registry';

interface PajakOtpInstance {
    get value(): string;
    clear(): void;
    destroy(): void;
}

const registry = createRegistry<PajakOtpInstance>();

const BOX_ERROR_CLASS = 'pajak-otp__box--error';

function upgradeOtp(root: HTMLElement): PajakOtpInstance | null {
    const existing = registry.get(root);
    if (existing) {
        return existing;
    }

    const boxes = Array.from(root.querySelectorAll<HTMLInputElement>('.pajak-otp__box'));
    const hidden = root.querySelector<HTMLInputElement>('input[type="hidden"]');

    if (!hidden || boxes.length === 0) {
        return null;
    }

    const form = root.closest('form');
    const forbidden = root.dataset.numeric === 'false' ? /[^A-Za-z0-9]/g : /\D/g;
    const autosubmit = root.dataset.autosubmit === 'true';
    const abort = new AbortController();
    const { signal } = abort;

    let committed: string[] = boxes.map(() => '');

    const sanitize = (text: string): string => text.replace(forbidden, '');

    const clearErrorState = (): void => {
        boxes.forEach((box) => box.classList.remove(BOX_ERROR_CLASS));
        root.querySelectorAll('.pajak-field__message--error').forEach((message) => message.remove());
        hidden.removeAttribute('data-pajak-error-input');
    };

    const submit = (): void => {
        if (!form) {
            return;
        }

        const target = `#${form.id}`;
        const button = form.id
            ? Array.from(document.querySelectorAll<HTMLButtonElement>('button[data-pajak-form]'))
                .find((candidate) => candidate.getAttribute('data-pajak-form') === target)
            : undefined;

        if (button) {
            button.click();
        } else {
            form.requestSubmit();
        }
    };

    const sync = (): void => {
        committed = boxes.map((box) => box.value);
        hidden.value = committed.join('');
        hidden.dispatchEvent(new Event('input', { bubbles: true }));

        if (autosubmit && hidden.value.length === boxes.length) {
            submit();
        }
    };

    const reset = (): void => {
        boxes.forEach((box) => {
            box.value = '';
        });

        committed = boxes.map(() => '');
        hidden.value = '';
    };

    const fill = (start: number, chars: string): void => {
        const taken = chars.slice(0, boxes.length - start).split('');

        taken.forEach((char, offset) => {
            boxes[start + offset].value = char;
        });

        boxes[Math.min(start + taken.length, boxes.length - 1)].focus();
        sync();
    };

    boxes.forEach((box, index) => {
        box.addEventListener('focus', () => box.select(), { signal });

        box.addEventListener('input', (event) => {
            clearErrorState();

            const chars = sanitize(box.value);

            if (chars === '') {
                const rejected = box.value !== '' && (event as InputEvent).inputType?.startsWith('insert');

                box.value = rejected ? committed[index] : '';
                sync();

                return;
            }

            box.value = '';
            fill(index, chars);
        }, { signal });

        box.addEventListener('keydown', (event) => {
            const previous = boxes[index - 1];
            const next = boxes[index + 1];

            if (event.key === 'Backspace' && box.value === '' && previous) {
                event.preventDefault();
                previous.value = '';
                previous.focus();
                sync();
            } else if (event.key === 'ArrowLeft' && previous) {
                event.preventDefault();
                previous.focus();
            } else if (event.key === 'ArrowRight' && next) {
                event.preventDefault();
                next.focus();
            }
        }, { signal });

        box.addEventListener('paste', (event) => {
            event.preventDefault();
            clearErrorState();

            const chars = sanitize(event.clipboardData?.getData('text') ?? '');

            if (chars !== '') {
                fill(chars.length >= boxes.length ? 0 : index, chars);
            }
        }, { signal });
    });

    // PajakForm sets data-pajak-error-input on the hidden field when the server rejects the code
    form?.addEventListener('pajak:form:error', () => {
        const rejected = hidden.hasAttribute('data-pajak-error-input');

        if (!rejected && !autosubmit) {
            return;
        }

        reset();
        boxes.forEach((box) => box.classList.toggle(BOX_ERROR_CLASS, rejected));
        boxes[0].focus();
    }, { signal });

    const instance: PajakOtpInstance = {
        get value(): string {
            return hidden.value;
        },
        clear(): void {
            reset();
            clearErrorState();
        },
        destroy(): void {
            abort.abort();
            registry.delete(root);
        },
    };

    registry.set(root, instance);

    return instance;
}

export const PajakOtp = {
    init(el: HTMLElement): PajakOtpInstance | null {
        return upgradeOtp(el);
    },

    initAll(root: ParentNode = document): void {
        root.querySelectorAll<HTMLElement>('[data-pajak-otp]').forEach(upgradeOtp);
    },
};
