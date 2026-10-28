import { onReady } from '../ready';

declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

interface Entry {
    row: HTMLElement;
    option: HTMLElement;
    group: HTMLElement | null;
    index: number;
    label: string;
    hint: string;
    keywords: string;
}

interface PaletteState {
    dialog: HTMLDialogElement;
    input: HTMLInputElement;
    empty: HTMLElement;
    entries: Entry[];
    visible: Entry[];
    active: number;
}

const initializedPalettes = new WeakSet<HTMLDialogElement>();
const initializedTriggers = new WeakSet<HTMLElement>();
const states = new WeakMap<HTMLDialogElement, PaletteState>();

let shortcutBound = false;

export function normalize(value: string): string {
    return value.normalize('NFD').replace(/\p{M}/gu, '').toLowerCase().trim();
}

function startsAtWord(haystack: string, needle: string): boolean {
    return haystack.split(/[\s\-_/.]+/).some((word) => word.startsWith(needle));
}

export function score(entry: Pick<Entry, 'label' | 'hint' | 'keywords'>, query: string): number {
    if (query === '') {
        return 0;
    }

    if (entry.label.startsWith(query)) {
        return 100;
    }

    if (startsAtWord(entry.label, query)) {
        return 80;
    }

    if (entry.label.includes(query)) {
        return 60;
    }

    const secondary = `${entry.hint} ${entry.keywords}`;

    if (startsAtWord(secondary, query)) {
        return 40;
    }

    if (secondary.includes(query)) {
        return 20;
    }

    const haystack = `${entry.label} ${secondary}`;

    return query.split(/\s+/).every((token) => haystack.includes(token)) ? 10 : -1;
}

function firstPalette(): HTMLDialogElement | null {
    return document.querySelector<HTMLDialogElement>('dialog[data-pajak-command-palette]');
}

function getPalette(id?: string): HTMLDialogElement | null {
    if (id === undefined) {
        return firstPalette();
    }

    return document.querySelector<HTMLDialogElement>(`dialog#${id}[data-pajak-command-palette]`);
}

function buildState(dialog: HTMLDialogElement): PaletteState | null {
    const input = dialog.querySelector<HTMLInputElement>('[data-pajak-command-palette-input]');
    const empty = dialog.querySelector<HTMLElement>('[data-pajak-command-palette-empty]');

    if (input === null || empty === null) {
        return null;
    }

    const entries: Entry[] = [];

    dialog.querySelectorAll<HTMLElement>('[data-pajak-command-palette-row]').forEach((row, index) => {
        const option = row.querySelector<HTMLElement>('[data-pajak-command-palette-option]');

        if (option === null) {
            return;
        }

        option.id = option.id || `${dialog.id}-option-${index}`;

        entries.push({
            row,
            option,
            group: row.closest<HTMLElement>('[data-pajak-command-palette-group]'),
            index,
            label: normalize(row.querySelector('[data-pajak-command-palette-label]')?.textContent ?? ''),
            hint: normalize(row.querySelector('[data-pajak-command-palette-hint]')?.textContent ?? ''),
            keywords: normalize(row.dataset.keywords ?? ''),
        });
    });

    return { dialog, input, empty, entries, visible: [], active: -1 };
}

function setActive(state: PaletteState, index: number, scroll = true): void {
    state.active = index;

    state.entries.forEach((entry) => {
        const isActive = state.visible[index] === entry;

        entry.option.classList.toggle('is-active', isActive);
        entry.option.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });

    const current = state.visible[index];

    if (current === undefined) {
        state.input.removeAttribute('aria-activedescendant');

        return;
    }

    state.input.setAttribute('aria-activedescendant', current.option.id);

    if (scroll) {
        current.option.scrollIntoView?.({ block: 'nearest' });
    }
}

function filter(state: PaletteState): void {
    const query = normalize(state.input.value);

    const scored = state.entries
        .map((entry) => ({ entry, score: score(entry, query) }))
        .filter(({ score: value }) => value >= 0)
        .sort((a, b) => b.score - a.score || a.entry.index - b.entry.index);

    const matched = new Set(scored.map(({ entry }) => entry));

    state.entries.forEach((entry) => {
        entry.row.hidden = !matched.has(entry);
    });

    scored.forEach(({ entry }) => {
        entry.row.parentElement?.appendChild(entry.row);
    });

    state.dialog.querySelectorAll<HTMLElement>('[data-pajak-command-palette-group]').forEach((group) => {
        group.hidden = !state.entries.some((entry) => entry.group === group && matched.has(entry));
    });

    state.visible = scored.map(({ entry }) => entry);
    state.empty.hidden = state.visible.length > 0;

    setActive(state, state.visible.length > 0 ? 0 : -1, false);
}

function run(entry: Entry | undefined): void {
    entry?.option.click();
}

function handleCommandClick(state: PaletteState, option: HTMLElement): void {
    const command = option.dataset.pajakCommand;

    if (command === undefined) {
        return;
    }

    state.dialog.close();
    document.dispatchEvent(new CustomEvent('pajak:command', { detail: { command } }));
}

function move(state: PaletteState, step: number): void {
    const count = state.visible.length;

    if (count === 0) {
        return;
    }

    setActive(state, (state.active + step + count) % count);
}

function bindPalette(dialog: HTMLDialogElement): void {
    const state = buildState(dialog);

    if (state === null) {
        return;
    }

    states.set(dialog, state);

    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            dialog.close();

            return;
        }

        const option = (event.target as HTMLElement).closest<HTMLElement>('[data-pajak-command-palette-option]');

        if (option !== null) {
            handleCommandClick(state, option);
        }
    });

    dialog.addEventListener('mousemove', (event) => {
        const option = (event.target as HTMLElement).closest<HTMLElement>('[data-pajak-command-palette-option]');
        const index = state.visible.findIndex((entry) => entry.option === option);

        if (index !== -1 && index !== state.active) {
            setActive(state, index, false);
        }
    });

    state.input.addEventListener('input', () => filter(state));

    state.input.addEventListener('keydown', (event) => {
        switch (event.key) {
            case 'ArrowDown':
                event.preventDefault();
                move(state, 1);
                break;
            case 'ArrowUp':
                event.preventDefault();
                move(state, -1);
                break;
            case 'Enter':
                event.preventDefault();
                run(state.visible[state.active]);
                break;
        }
    });

    filter(state);
}

function openPalette(id?: string): void {
    const dialog = getPalette(id);

    if (dialog === null) {
        return;
    }

    const state = states.get(dialog);

    if (state === undefined) {
        return;
    }

    state.input.value = '';
    filter(state);

    if (!dialog.open) {
        dialog.showModal();
    }

    state.input.focus();
}

function closePalette(id?: string): void {
    getPalette(id)?.close();
}

function isMac(): boolean {
    return /Mac|iPhone|iPad|iPod/.test(navigator.platform || navigator.userAgent);
}

function bindShortcut(): void {
    if (shortcutBound) {
        return;
    }

    shortcutBound = true;

    document.addEventListener('keydown', (event) => {
        if (!(event.metaKey || event.ctrlKey) || event.key.toLowerCase() !== 'k') {
            return;
        }

        const dialog = firstPalette();

        if (dialog === null || !states.has(dialog)) {
            return;
        }

        event.preventDefault();

        if (dialog.open) {
            dialog.close();
        } else {
            openPalette();
        }
    });
}

function initAll(root: ParentNode = document): void {
    root.querySelectorAll<HTMLDialogElement>('dialog[data-pajak-command-palette]').forEach((dialog) => {
        if (initializedPalettes.has(dialog)) {
            return;
        }

        initializedPalettes.add(dialog);
        bindPalette(dialog);
    });

    root.querySelectorAll<HTMLElement>('[data-pajak-command-palette-trigger]').forEach((trigger) => {
        if (initializedTriggers.has(trigger)) {
            return;
        }

        initializedTriggers.add(trigger);
        trigger.addEventListener('click', () => openPalette(trigger.dataset.pajakCommandPaletteTrigger || undefined));
    });

    root.querySelectorAll<HTMLElement>('[data-pajak-command-palette-shortcut]').forEach((hint) => {
        hint.textContent = isMac() ? '⌘K' : 'Ctrl K';
    });

    bindShortcut();
}

export const PajakCommandPalette = {
    open: openPalette,
    close: closePalette,
    initAll,
} as const;

window.Pajak = { ...window.Pajak, PajakCommandPalette };

onReady(() => PajakCommandPalette.initAll());
