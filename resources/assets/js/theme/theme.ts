import { onReady } from '../ready';
import { PajakSegmented } from '../segmented/segmented';

declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

export type ThemeMode = 'light' | 'dark' | 'system';

const STORAGE_KEY = 'pajak-theme';
const MODES: readonly ThemeMode[] = ['light', 'dark', 'system'];

const bound = new WeakSet<HTMLElement>();
const darkQuery = typeof window.matchMedia === 'function' ? window.matchMedia('(prefers-color-scheme: dark)') : null;

function isMode(value: unknown): value is ThemeMode {
    return typeof value === 'string' && (MODES as readonly string[]).includes(value);
}

function storedValue(): ThemeMode | null {
    try {
        const value = localStorage.getItem(STORAGE_KEY);

        return isMode(value) ? value : null;
    } catch {
        return null;
    }
}

function stored(): ThemeMode {
    return storedValue() ?? 'system';
}

function resolve(mode: ThemeMode): 'light' | 'dark' {
    if (mode === 'system') {
        return darkQuery?.matches ? 'dark' : 'light';
    }

    return mode;
}

function apply(mode: ThemeMode = stored()): void {
    document.documentElement.setAttribute('data-theme', resolve(mode));
}

function set(mode: ThemeMode): void {
    try {
        localStorage.setItem(STORAGE_KEY, mode);
    } catch {
        // Storage unavailable (private mode) - still apply for this page view
    }

    apply(mode);
    syncSwitchers(mode);

    document.dispatchEvent(new CustomEvent('pajak:theme', { detail: { mode, resolved: resolve(mode) } }));
}

function syncSwitchers(mode: ThemeMode): void {
    document.querySelectorAll<HTMLElement>('[data-pajak-theme-switch]').forEach((el) => {
        PajakSegmented.select(el, mode, { silent: true });
    });
}

// Binds a segmented control whose option values are `light` / `dark` / `system` to the theme.
function bind(el: HTMLElement): void {
    if (bound.has(el)) {
        return;
    }

    bound.add(el);
    PajakSegmented.init(el);
    PajakSegmented.select(el, stored(), { silent: true });

    el.addEventListener('pajak-segmented:change', (event) => {
        const value = (event as CustomEvent<{ value: string | null }>).detail.value;

        if (isMode(value)) {
            set(value);
        }
    });
}

function initAll(root: ParentNode = document): void {
    root.querySelectorAll<HTMLElement>('[data-pajak-theme-switch]').forEach(bind);
}

export const PajakTheme = {
    get: stored,
    resolved: (): 'light' | 'dark' => resolve(stored()),
    set,
    apply,
    bind,
    initAll,
} as const;

window.Pajak = { ...window.Pajak, PajakTheme };

// Opt-in: nothing changes for a visitor who never picked a theme - the app's own `data-theme` (or
// lack of one) is left alone until `set()` / `apply()` is called or a preference is stored.
if (storedValue() !== null) {
    apply();
}

darkQuery?.addEventListener('change', () => {
    if (storedValue() === 'system') {
        apply('system');
    }
});

onReady(() => initAll());
