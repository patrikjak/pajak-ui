import { createRegistry } from '../registry';

interface PajakSliderInstance {
    get value(): number;
    set value(v: number);
    destroy(): void;
}

interface PajakRangeInstance {
    get min(): number;
    set min(v: number);
    get max(): number;
    set max(v: number);
    destroy(): void;
}

const sliderRegistry = createRegistry<PajakSliderInstance>();
const rangeRegistry = createRegistry<PajakRangeInstance>();

const DRAGGING = 'pajak-slider__thumb--dragging';

// ─── Shared global pointer dispatcher ───────────────────────────────────────

type ActiveDrag = { onMove: (e: PointerEvent) => void; onUp: () => void };
let activeDrag: ActiveDrag | null = null;

window.addEventListener('pointermove', (e: PointerEvent) => { activeDrag?.onMove(e); }, { passive: true });
window.addEventListener('pointerup', () => { activeDrag?.onUp(); });

// ────────────────────────────────────────────────────────────────────────────

export function pctFromPointer(wrap: HTMLElement, clientX: number): number {
    const r = wrap.getBoundingClientRect();
    const pad = parseFloat(getComputedStyle(wrap).paddingLeft);
    const trackWidth = r.width - pad * 2;
    return Math.max(0, Math.min(1, (clientX - r.left - pad) / trackWidth));
}

export function snap(raw: number, min: number, max: number, step: number): number {
    const snapped = Math.round((raw - min) / step) * step + min;
    return Math.max(min, Math.min(max, snapped));
}

function upgradeSlider(wrap: HTMLElement): PajakSliderInstance {
    const existing = sliderRegistry.get(wrap);
    if (existing) {
        return existing;
    }

    const min  = parseFloat(wrap.dataset.min  ?? '0');
    const max  = parseFloat(wrap.dataset.max  ?? '100');
    const step = parseFloat(wrap.dataset.step ?? '1');

    const fill   = wrap.querySelector<HTMLElement>('.pajak-slider__fill')!;
    const thumb  = wrap.querySelector<HTMLElement>('.pajak-slider__thumb')!;
    const bubble = thumb.querySelector<HTMLElement>('.pajak-slider__bubble');
    const input  = wrap.querySelector<HTMLInputElement>('.pajak-slider__input');
    const suffix = wrap.dataset.suffix ?? '';

    let val = snap(parseFloat(wrap.dataset.value ?? '0'), min, max, step);

    function formatValue(v: number): string {
        return suffix ? `${v} ${suffix}` : String(v);
    }

    function applyValue(v: number): void {
        val = snap(v, min, max, step);
        const pct = (val - min) / (max - min) * 100;
        thumb.style.left = `${pct}%`;
        fill.style.width = `${pct}%`;
        thumb.setAttribute('aria-valuenow', String(val));
        if (bubble) {
            bubble.textContent = formatValue(val);
        }
        if (input) {
            input.value = String(val);
        }

        wrap.dispatchEvent(new CustomEvent('pajak:slider', {
            bubbles: true,
            detail: { value: val },
        }));
    }

    applyValue(val);

    const onPointerDown = (e: PointerEvent): void => {
        thumb.classList.add(DRAGGING);
        thumb.focus();
        applyValue(min + pctFromPointer(wrap, e.clientX) * (max - min));
        activeDrag = { onMove: onPointerMove, onUp: onPointerUp };
        e.preventDefault();
    };

    const onPointerMove = (e: PointerEvent): void => {
        applyValue(min + pctFromPointer(wrap, e.clientX) * (max - min));
    };

    const onPointerUp = (): void => {
        thumb.classList.remove(DRAGGING);
        activeDrag = null;
    };

    const onKeyDown = (e: KeyboardEvent): void => {
        const map: Record<string, number> = {
            ArrowLeft: -step, ArrowDown: -step,
            ArrowRight: step, ArrowUp: step,
            PageDown: -step * 10, PageUp: step * 10,
        };
        if (e.key === 'Home') {
            applyValue(min);
            e.preventDefault();
            return;
        }
        if (e.key === 'End') {
            applyValue(max);
            e.preventDefault();
            return;
        }
        const delta = map[e.key];
        if (delta !== undefined) {
            applyValue(val + delta);
            e.preventDefault();
        }
    };

    wrap.addEventListener('pointerdown', onPointerDown);
    thumb.addEventListener('keydown', onKeyDown);

    if (wrap.dataset.showBubble !== undefined) {
        wrap.closest('.pajak-slider')?.classList.add('pajak-slider--show-bubble');
    }

    const instance: PajakSliderInstance = {
        get value(): number { return val; },
        set value(v: number) { applyValue(v); },
        destroy(): void {
            wrap.removeEventListener('pointerdown', onPointerDown);
            thumb.removeEventListener('keydown', onKeyDown);
            if (activeDrag?.onUp === onPointerUp) {
                activeDrag = null;
            }
            sliderRegistry.delete(wrap);
        },
    };

    sliderRegistry.set(wrap, instance);
    return instance;
}

function upgradeRange(wrap: HTMLElement): PajakRangeInstance {
    const existing = rangeRegistry.get(wrap);
    if (existing) {
        return existing;
    }

    const absMin = parseFloat(wrap.dataset.min  ?? '0');
    const absMax = parseFloat(wrap.dataset.max  ?? '100');
    const step   = parseFloat(wrap.dataset.step ?? '1');
    const suffix = wrap.dataset.suffix ?? '';

    const fill    = wrap.querySelector<HTMLElement>('.pajak-slider__fill')!;
    const tMin    = wrap.querySelector<HTMLElement>('.pajak-slider__thumb[data-which="min"]')!;
    const tMax    = wrap.querySelector<HTMLElement>('.pajak-slider__thumb[data-which="max"]')!;
    const bMin    = tMin.querySelector<HTMLElement>('.pajak-slider__bubble');
    const bMax    = tMax.querySelector<HTMLElement>('.pajak-slider__bubble');
    const iMin    = wrap.querySelector<HTMLInputElement>('.pajak-slider__input-min');
    const iMax    = wrap.querySelector<HTMLInputElement>('.pajak-slider__input-max');

    let vmin = snap(parseFloat(wrap.dataset.valueMin ?? String(absMin)), absMin, absMax, step);
    let vmax = snap(parseFloat(wrap.dataset.valueMax ?? String(absMax)), absMin, absMax, step);

    let dragging: 'min' | 'max' | null = null;

    function formatValue(v: number): string {
        return suffix ? `${v} ${suffix}` : String(v);
    }

    function applyValues(): void {
        vmin = Math.max(absMin, Math.min(vmax - step, vmin));
        vmax = Math.min(absMax, Math.max(vmin + step, vmax));

        const pMin = (vmin - absMin) / (absMax - absMin) * 100;
        const pMax = (vmax - absMin) / (absMax - absMin) * 100;

        tMin.style.left = `${pMin}%`;
        tMax.style.left = `${pMax}%`;
        fill.style.left  = `${pMin}%`;
        fill.style.width = `${pMax - pMin}%`;

        tMin.setAttribute('aria-valuenow', String(vmin));
        tMax.setAttribute('aria-valuenow', String(vmax));
        if (bMin) {
            bMin.textContent = formatValue(vmin);
        }
        if (bMax) {
            bMax.textContent = formatValue(vmax);
        }
        if (iMin) {
            iMin.value = String(vmin);
        }
        if (iMax) {
            iMax.value = String(vmax);
        }

        wrap.dispatchEvent(new CustomEvent('pajak:slider', {
            bubbles: true,
            detail: { min: vmin, max: vmax },
        }));
    }

    applyValues();

    function valueFromPointer(clientX: number): number {
        return snap(absMin + pctFromPointer(wrap, clientX) * (absMax - absMin), absMin, absMax, step);
    }

    function nearestThumb(clientX: number): 'min' | 'max' {
        const v = valueFromPointer(clientX);
        return Math.abs(v - vmin) <= Math.abs(v - vmax) ? 'min' : 'max';
    }

    const onPointerDown = (e: PointerEvent): void => {
        const which = (e.target as HTMLElement).closest<HTMLElement>('.pajak-slider__thumb')?.dataset.which as 'min' | 'max' | undefined
            ?? nearestThumb(e.clientX);
        dragging = which;
        const t = which === 'min' ? tMin : tMax;
        t.classList.add(DRAGGING);
        t.focus();
        const v = valueFromPointer(e.clientX);
        if (which === 'min') {
            vmin = v;
        } else {
            vmax = v;
        }
        applyValues();
        activeDrag = { onMove: onPointerMove, onUp: onPointerUp };
        e.preventDefault();
    };

    const onPointerMove = (e: PointerEvent): void => {
        if (!dragging) {
            return;
        }
        const v = valueFromPointer(e.clientX);
        if (dragging === 'min') {
            vmin = v;
        } else {
            vmax = v;
        }
        applyValues();
    };

    const onPointerUp = (): void => {
        tMin.classList.remove(DRAGGING);
        tMax.classList.remove(DRAGGING);
        dragging = null;
        activeDrag = null;
    };

    const makeKeyHandler = (which: 'min' | 'max') => (e: KeyboardEvent): void => {
        const delta = (e.key === 'ArrowLeft' || e.key === 'ArrowDown') ? -step
                    : (e.key === 'ArrowRight' || e.key === 'ArrowUp')  ? +step
                    : 0;
        if (e.key === 'Home') {
            if (which === 'min') { vmin = absMin; } else { vmax = absMin; }
            applyValues();
            e.preventDefault();
            return;
        }
        if (e.key === 'End') {
            if (which === 'min') { vmin = absMax; } else { vmax = absMax; }
            applyValues();
            e.preventDefault();
            return;
        }
        if (delta) {
            if (which === 'min') { vmin += delta; } else { vmax += delta; }
            applyValues();
            e.preventDefault();
        }
    };

    const onKeyMin = makeKeyHandler('min');
    const onKeyMax = makeKeyHandler('max');

    wrap.addEventListener('pointerdown', onPointerDown);
    tMin.addEventListener('keydown', onKeyMin);
    tMax.addEventListener('keydown', onKeyMax);

    const instance: PajakRangeInstance = {
        get min(): number { return vmin; },
        set min(v: number) { vmin = v; applyValues(); },
        get max(): number { return vmax; },
        set max(v: number) { vmax = v; applyValues(); },
        destroy(): void {
            wrap.removeEventListener('pointerdown', onPointerDown);
            tMin.removeEventListener('keydown', onKeyMin);
            tMax.removeEventListener('keydown', onKeyMax);
            if (activeDrag?.onUp === onPointerUp) {
                activeDrag = null;
            }
            rangeRegistry.delete(wrap);
        },
    };

    rangeRegistry.set(wrap, instance);
    return instance;
}

export const PajakSlider = {
    init(el: HTMLElement): PajakSliderInstance | PajakRangeInstance {
        return el.dataset.range !== undefined ? upgradeRange(el) : upgradeSlider(el);
    },

    initAll(root: ParentNode = document): Array<PajakSliderInstance | PajakRangeInstance> {
        return Array.from(root.querySelectorAll<HTMLElement>('[data-pajak-slider]')).map((el) =>
            el.dataset.range !== undefined ? upgradeRange(el) : upgradeSlider(el),
        );
    },
};
