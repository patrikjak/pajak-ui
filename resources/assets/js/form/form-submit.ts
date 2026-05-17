import { post, put, patch, destroy, type HttpResult, type HttpMethod } from '../http/connector';
import { PajakToast, type ToastOptions } from '../toast/toast';

type ValidationErrors = Record<string, string[]>;

// ─── Extra data registry ─────────────────────────────────────────────────────

const extraDataRegistry = new WeakMap<HTMLFormElement, Map<string, string>>();

function getExtraData(form: HTMLFormElement): Map<string, string> {
    let map = extraDataRegistry.get(form);

    if (!map) {
        map = new Map();
        extraDataRegistry.set(form, map);
    }

    return map;
}

export const PajakForm = {
    addData(form: HTMLFormElement, key: string, value: string): void {
        getExtraData(form).set(key, value);
    },

    removeData(form: HTMLFormElement, key: string): void {
        getExtraData(form).delete(key);
    },

    clearData(form: HTMLFormElement): void {
        extraDataRegistry.delete(form);
    },
} as const;

// ─── Spinner ────────────────────────────────────────────────────────────────

function spinnerSvg(): SVGSVGElement {
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('class', 'pajak-btn__spinner');
    svg.setAttribute('viewBox', '0 0 24 24');
    svg.setAttribute('fill', 'none');
    svg.setAttribute('stroke-width', '2.75');
    svg.setAttribute('stroke-linecap', 'round');

    const track = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
    track.setAttribute('class', 'track');
    track.setAttribute('cx', '12');
    track.setAttribute('cy', '12');
    track.setAttribute('r', '9');

    const head = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
    head.setAttribute('class', 'head');
    head.setAttribute('cx', '12');
    head.setAttribute('cy', '12');
    head.setAttribute('r', '9');
    head.setAttribute('stroke-dasharray', '56.5');
    head.setAttribute('stroke-dashoffset', '38');

    svg.appendChild(track);
    svg.appendChild(head);

    return svg;
}

function startLoading(btn: HTMLButtonElement): void {
    btn.classList.add('is-loading');
    btn.appendChild(spinnerSvg());
}

function stopLoading(btn: HTMLButtonElement): void {
    btn.classList.remove('is-loading');
    btn.querySelector('.pajak-btn__spinner')?.remove();
}

// ─── Error display ──────────────────────────────────────────────────────────

function clearErrors(form: HTMLFormElement): void {
    form.querySelectorAll<HTMLElement>('.pajak-field__message--error[data-pajak-error]').forEach((el) => {
        el.remove();
    });

    form.querySelectorAll<HTMLElement>('[data-pajak-error-input]').forEach((el) => {
        el.removeAttribute('data-pajak-error-input');
        // Strip error state modifier — replace --error with --default
        el.classList.forEach((cls) => {
            if (cls.endsWith('--error')) {
                el.classList.remove(cls);
                el.classList.add(cls.replace('--error', '--default'));
            }
        });
    });
}

function applyErrors(form: HTMLFormElement, errors: ValidationErrors): void {
    clearErrors(form);

    for (const [field, messages] of Object.entries(errors)) {
        const message = messages[0];
        if (!message) {
            continue;
        }

        const input =
            form.querySelector<HTMLElement>(`[name="${field}"]`) ??
            form.querySelector<HTMLElement>(`[name="${field}[]"]`);

        if (!input) {
            continue;
        }

        // Mark input with error state
        input.setAttribute('data-pajak-error-input', '');
        input.classList.forEach((cls) => {
            if (cls.endsWith('--default') || cls.endsWith('--success')) {
                input.classList.remove(cls);
                input.classList.add(cls.replace(/--default$|--success$/, '--error'));
            }
        });

        // Find the closest .pajak-field and append the error message
        const field_ = input.closest('.pajak-field');

        if (!field_) {
            continue;
        }

        const msgEl = document.createElement('span');
        msgEl.className = 'pajak-field__message pajak-field__message--error';
        msgEl.setAttribute('data-pajak-error', '');
        msgEl.textContent = message;
        field_.appendChild(msgEl);

        // Remove error on next change
        input.addEventListener('input', () => {
            msgEl.remove();
            input.removeAttribute('data-pajak-error-input');
            input.classList.forEach((cls) => {
                if (cls.endsWith('--error')) {
                    input.classList.remove(cls);
                    input.classList.add(cls.replace('--error', '--default'));
                }
            });
        }, { once: true });
    }
}

// ─── HTTP method resolution ─────────────────────────────────────────────────

function resolveMethod(form: HTMLFormElement): HttpMethod {
    const spoofInput = form.querySelector<HTMLInputElement>('input[name="_method"]');
    const spoofed = spoofInput?.value?.toUpperCase();

    if (spoofed === 'PUT' || spoofed === 'PATCH' || spoofed === 'DELETE') {
        return spoofed;
    }

    const method = form.method.toUpperCase();

    return (method === 'POST' ? 'POST' : 'GET') as HttpMethod;
}

function buildFormData(form: HTMLFormElement): FormData | null {
    const data = new FormData(form);

    getExtraData(form).forEach((value, key) => {
        data.set(key, value);
    });

    const cancelled = !form.dispatchEvent(new CustomEvent('pajak:form:before-submit', {
        bubbles: true,
        cancelable: true,
        detail: { data },
    }));

    return cancelled ? null : data;
}

async function sendForm(form: HTMLFormElement): Promise<HttpResult | null> {
    const action = form.action;
    const method = resolveMethod(form);
    const data = buildFormData(form);

    if (data === null) {
        return null;
    }

    if (method === 'GET') {
        const params: Record<string, string> = {};
        data.forEach((value, key) => { params[key] = String(value); });
        const qs = new URLSearchParams(params).toString();
        const url = qs ? `${action}?${qs}` : action;

        const { get } = await import('../http/connector');
        return get(url);
    }

    if (method === 'PUT') {
        return put(action, data);
    }

    if (method === 'PATCH') {
        return patch(action, data);
    }

    if (method === 'DELETE') {
        return destroy(action, data);
    }

    return post(action, data);
}

// ─── Toast integration ───────────────────────────────────────────────────────

function maybeShowToast(form: HTMLFormElement, data: unknown): void {
    if (form.hasAttribute('data-no-toast')) {
        return;
    }

    const toast = (data as { toast?: ToastOptions })?.toast;

    if (toast) {
        PajakToast.show(toast);
    }
}

// ─── Submit handler ─────────────────────────────────────────────────────────

async function submitForm(form: HTMLFormElement, btn: HTMLButtonElement | null): Promise<void> {
    if (btn?.classList.contains('is-loading')) {
        return;
    }

    if (btn) {
        startLoading(btn);
    }

    clearErrors(form);

    const result = await sendForm(form);

    if (btn) {
        stopLoading(btn);
    }

    if (result === null) {
        return;
    }

    if (result.ok) {
        maybeShowToast(form, result.data);

        const redirect = form.dataset.redirect;

        if (redirect) {
            window.location.href = redirect;
            return;
        }

        form.dispatchEvent(new CustomEvent('pajak:form:success', {
            bubbles: true,
            detail: { response: result.data },
        }));

        return;
    }

    if (result.status === 422) {
        const errors = (result.data as { errors?: ValidationErrors })?.errors;

        if (errors) {
            applyErrors(form, errors);
        }

        return;
    }

    maybeShowToast(form, result.data);

    form.dispatchEvent(new CustomEvent('pajak:form:error', {
        bubbles: true,
        detail: { status: result.status, response: result.data },
    }));
}

function resolveSubmitButton(form: HTMLFormElement): HTMLButtonElement | null {
    return (
        form.querySelector<HTMLButtonElement>('[type="submit"]') ??
        (form.id
            ? document.querySelector<HTMLButtonElement>(`[type="submit"][form="${form.id}"]`)
            : null)
    );
}

async function handleSubmit(e: Event): Promise<void> {
    e.preventDefault();

    const form = e.currentTarget as HTMLFormElement;
    const btn = resolveSubmitButton(form);

    await submitForm(form, btn);
}

async function handleExternalButtonClick(e: Event): Promise<void> {
    const btn = e.currentTarget as HTMLButtonElement;
    const selector = btn.dataset.pajakForm;

    if (!selector) {
        return;
    }

    const form = document.querySelector<HTMLFormElement>(selector);

    if (!form) {
        return;
    }

    if (btn.disabled) {
        return;
    }

    btn.disabled = true;

    await submitForm(form, null);

    btn.disabled = false;
}

// ─── Public API ─────────────────────────────────────────────────────────────

const initializedForms = new WeakSet<HTMLFormElement>();
const initializedExternalButtons = new WeakSet<HTMLButtonElement>();

export function initFormSubmitLoaders(root: ParentNode = document): void {
    root.querySelectorAll<HTMLFormElement>('form[data-pajak-form]').forEach((form) => {
        if (initializedForms.has(form)) {
            return;
        }
        initializedForms.add(form);
        form.addEventListener('submit', (e) => { void handleSubmit(e); });
    });

    root.querySelectorAll<HTMLButtonElement>('button[data-pajak-form]').forEach((btn) => {
        if (initializedExternalButtons.has(btn)) {
            return;
        }
        initializedExternalButtons.add(btn);
        btn.addEventListener('click', (e) => { void handleExternalButtonClick(e); });
    });
}
