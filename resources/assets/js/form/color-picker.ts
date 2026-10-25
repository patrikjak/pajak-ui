import { createRegistry } from '../registry';

interface PajakColorPickerInstance {
    get value(): string;
    set value(value: string);
    open(): void;
    close(): void;
    destroy(): void;
}

interface Hsv {
    h: number; // 0-360
    s: number; // 0-1
    v: number; // 0-1
}

const HEX = /^#[0-9a-f]{6}$/i;

const registry = createRegistry<PajakColorPickerInstance>();

const clamp = (value: number, min = 0, max = 1): number => Math.min(max, Math.max(min, value));

function hexToHsv(hex: string, previousHue: number): Hsv {
    const r = parseInt(hex.slice(1, 3), 16) / 255;
    const g = parseInt(hex.slice(3, 5), 16) / 255;
    const b = parseInt(hex.slice(5, 7), 16) / 255;
    const max = Math.max(r, g, b);
    const delta = max - Math.min(r, g, b);

    let h = previousHue;

    // Greys have no hue - keep the last one so dragging back from grey doesn't jump to red
    if (delta !== 0) {
        if (max === r) {
            h = 60 * (((g - b) / delta + 6) % 6);
        } else if (max === g) {
            h = 60 * ((b - r) / delta + 2);
        } else {
            h = 60 * ((r - g) / delta + 4);
        }
    }

    return { h, s: max === 0 ? 0 : delta / max, v: max };
}

function hsvToHex({ h, s, v }: Hsv): string {
    const channel = (n: number): number => {
        const k = (n + h / 60) % 6;

        return v - v * s * Math.max(0, Math.min(k, 4 - k, 1));
    };

    return `#${[channel(5), channel(3), channel(1)]
        .map((c) => Math.round(c * 255).toString(16).padStart(2, '0'))
        .join('')
        .toUpperCase()}`;
}

function upgradeColorPicker(root: HTMLElement): PajakColorPickerInstance {
    const existing = registry.get(root);
    if (existing) {
        return existing;
    }

    const text = root.querySelector<HTMLInputElement>('[data-pajak-color-text]');
    const panel = root.querySelector<HTMLElement>('[data-pajak-color-panel]');
    const area = root.querySelector<HTMLElement>('[data-pajak-color-area]');
    const hue = root.querySelector<HTMLElement>('[data-pajak-color-hue]');

    if (!text || !panel || !area || !hue) {
        return { get value() { return ''; }, set value(_) {}, open() {}, close() {}, destroy() {} };
    }

    const swatch = root.querySelector<HTMLButtonElement>('[data-pajak-color-swatch]');
    const handle = root.querySelector<HTMLElement>('[data-pajak-color-handle]');
    const hueHandle = root.querySelector<HTMLElement>('[data-pajak-color-hue-handle]');
    const presets = Array.from(root.querySelectorAll<HTMLButtonElement>('[data-pajak-color-preset]'));
    const inline = root.classList.contains('pajak-color-picker--inline');

    let hsv: Hsv = { h: 0, s: 1, v: 1 };
    let syncing = false;

    const render = (): void => {
        const valid = HEX.test(text.value);

        if (valid) {
            root.style.setProperty('--pajak-color', text.value.toUpperCase());
        } else {
            root.style.removeProperty('--pajak-color');
        }

        swatch?.classList.toggle('is-empty', !valid);

        panel.style.setProperty('--pajak-hue', String(Math.round(hsv.h)));

        if (handle) {
            handle.style.left = `${hsv.s * 100}%`;
            handle.style.top = `${(1 - hsv.v) * 100}%`;
        }

        if (hueHandle) {
            hueHandle.style.left = `${(hsv.h / 360) * 100}%`;
        }

        hue.setAttribute('aria-valuenow', String(Math.round(hsv.h)));
        area.setAttribute('aria-valuetext', valid ? text.value.toUpperCase() : '');

        presets.forEach((preset) => {
            preset.classList.toggle('is-selected', valid && preset.dataset.pajakColorPreset === text.value.toUpperCase());
        });
    };

    const emit = (): void => {
        root.dispatchEvent(new CustomEvent('pajak:color', { bubbles: true, detail: { value: text.value } }));
    };

    // Writes the current hsv back into the text field and re-dispatches `input` there so form
    // listeners (e.g. clearing a validation error) still run - `syncing` stops us re-parsing it.
    const commit = (): void => {
        syncing = true;
        text.value = hsvToHex(hsv);
        text.dispatchEvent(new Event('input', { bubbles: true }));
        syncing = false;

        render();
        emit();
    };

    const syncFromText = (): void => {
        if (HEX.test(text.value)) {
            hsv = hexToHsv(text.value, hsv.h);
        }

        render();
    };

    const onText = (): void => {
        if (syncing) {
            return;
        }

        syncFromText();
        emit();
    };

    const drag = (target: HTMLElement, update: (event: PointerEvent, rect: DOMRect) => void): (() => void) => {
        let dragging = false;

        const onDown = (event: PointerEvent): void => {
            event.preventDefault();
            dragging = true;
            target.focus();
            target.setPointerCapture?.(event.pointerId);
            update(event, target.getBoundingClientRect());
        };

        const onMove = (event: PointerEvent): void => {
            if (dragging) {
                update(event, target.getBoundingClientRect());
            }
        };

        const onUp = (event: PointerEvent): void => {
            dragging = false;
            target.releasePointerCapture?.(event.pointerId);
        };

        target.addEventListener('pointerdown', onDown as EventListener);
        target.addEventListener('pointermove', onMove as EventListener);
        target.addEventListener('pointerup', onUp as EventListener);
        target.addEventListener('pointercancel', onUp as EventListener);

        return () => {
            target.removeEventListener('pointerdown', onDown as EventListener);
            target.removeEventListener('pointermove', onMove as EventListener);
            target.removeEventListener('pointerup', onUp as EventListener);
            target.removeEventListener('pointercancel', onUp as EventListener);
        };
    };

    const stopArea = drag(area, (event, rect) => {
        hsv = {
            ...hsv,
            s: clamp(rect.width ? (event.clientX - rect.left) / rect.width : 0),
            v: clamp(rect.height ? 1 - (event.clientY - rect.top) / rect.height : 0),
        };
        commit();
    });

    const stopHue = drag(hue, (event, rect) => {
        hsv = { ...hsv, h: clamp(rect.width ? (event.clientX - rect.left) / rect.width : 0) * 360 };
        commit();
    });

    const onAreaKey = (event: KeyboardEvent): void => {
        const step = event.shiftKey ? 0.1 : 0.01;
        const delta: Record<string, [number, number]> = {
            ArrowLeft: [-step, 0],
            ArrowRight: [step, 0],
            ArrowUp: [0, step],
            ArrowDown: [0, -step],
        };
        const change = delta[event.key];

        if (!change) {
            return;
        }

        event.preventDefault();
        hsv = { ...hsv, s: clamp(hsv.s + change[0]), v: clamp(hsv.v + change[1]) };
        commit();
    };

    const onHueKey = (event: KeyboardEvent): void => {
        const step = event.shiftKey ? 10 : 1;
        let next: number | null = null;

        if (event.key === 'ArrowRight' || event.key === 'ArrowUp') {
            next = hsv.h + step;
        } else if (event.key === 'ArrowLeft' || event.key === 'ArrowDown') {
            next = hsv.h - step;
        } else if (event.key === 'Home') {
            next = 0;
        } else if (event.key === 'End') {
            next = 360;
        }

        if (next === null) {
            return;
        }

        event.preventDefault();
        hsv = { ...hsv, h: clamp(next, 0, 360) };
        commit();
    };

    area.addEventListener('keydown', onAreaKey);
    hue.addEventListener('keydown', onHueKey);

    const onPreset = (event: Event): void => {
        const color = (event.currentTarget as HTMLElement).dataset.pajakColorPreset ?? '';

        if (HEX.test(color)) {
            hsv = hexToHsv(color, hsv.h);
            commit();
        }
    };

    presets.forEach((preset) => preset.addEventListener('click', onPreset));

    const isOpen = (): boolean => !panel.hidden;

    const onOutside = (event: Event): void => {
        if (!root.contains(event.target as Node)) {
            close();
        }
    };

    const onKeydown = (event: KeyboardEvent): void => {
        if (event.key === 'Escape' && isOpen() && !inline) {
            close();
            swatch?.focus();
        }
    };

    function open(): void {
        if (inline || isOpen() || swatch?.disabled) {
            return;
        }

        panel.hidden = false;
        swatch?.setAttribute('aria-expanded', 'true');
        document.addEventListener('pointerdown', onOutside);
    }

    function close(): void {
        if (inline || !isOpen()) {
            return;
        }

        panel.hidden = true;
        swatch?.setAttribute('aria-expanded', 'false');
        document.removeEventListener('pointerdown', onOutside);
    }

    const onSwatch = (): void => {
        if (isOpen()) {
            close();
        } else {
            open();
        }
    };

    swatch?.addEventListener('click', onSwatch);
    root.addEventListener('keydown', onKeydown);
    text.addEventListener('input', onText);
    syncFromText();

    const instance: PajakColorPickerInstance = {
        get value(): string { return text.value; },
        set value(value: string) {
            text.value = value;
            syncFromText();
            emit();
        },
        open,
        close,
        destroy(): void {
            close();
            stopArea();
            stopHue();
            area.removeEventListener('keydown', onAreaKey);
            hue.removeEventListener('keydown', onHueKey);
            presets.forEach((preset) => preset.removeEventListener('click', onPreset));
            swatch?.removeEventListener('click', onSwatch);
            root.removeEventListener('keydown', onKeydown);
            text.removeEventListener('input', onText);
            registry.delete(root);
        },
    };

    registry.set(root, instance);
    return instance;
}

export const PajakColorPicker = {
    init(el: HTMLElement): PajakColorPickerInstance {
        return upgradeColorPicker(el);
    },

    initAll(root: ParentNode = document): PajakColorPickerInstance[] {
        return Array.from(root.querySelectorAll<HTMLElement>('[data-pajak-color-picker]')).map(upgradeColorPicker);
    },
};
