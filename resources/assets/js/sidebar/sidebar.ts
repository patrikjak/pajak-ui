import { onReady } from '../ready';

declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

const initializedSidebars = new WeakSet<HTMLDialogElement>();
const initializedRailButtons = new WeakSet<HTMLElement>();
const initializedAsides = new WeakSet<HTMLElement>();
const initializedTriggers = new WeakSet<HTMLElement>();
const tooltipAsides = new WeakSet<HTMLElement>();

const TOOLTIP_GAP = 12;

let railTooltip: HTMLElement | null = null;
let railTooltipListening = false;

function getSidebar(id: string): HTMLDialogElement | null {
    return document.querySelector<HTMLDialogElement>(`dialog#${id}[data-pajak-sidebar]`);
}

function getTrigger(id: string): HTMLElement | null {
    return document.querySelector<HTMLElement>(`[data-pajak-sidebar-trigger="${id}"]`);
}

function setTriggerOpen(id: string, open: boolean): void {
    const trigger = getTrigger(id);
    if (trigger) {
        trigger.classList.toggle('is-open', open);
    }
}

function openSidebar(id: string): void {
    const sidebar = getSidebar(id);
    if (sidebar) {
        sidebar.classList.add('is-open');
        setTriggerOpen(id, true);
    }
}

function closeSidebar(id: string): void {
    const sidebar = getSidebar(id);
    if (sidebar) {
        sidebar.classList.remove('is-open');
        setTriggerOpen(id, false);
    }
}

// Key format must match the inline script in sidebar.blade.php
function getRailStorageKey(id: string): string {
    return `pajak-sb-rail:${id}`;
}

// A sidebar's `.pajak-sb` aside is either standalone with its own id (desktop, always
// rendered) or nested inside a `<dialog data-pajak-sidebar>` that carries the id instead
// (mobile overlay) - the id used for persistence follows whichever one actually has it.
function railStorageId(aside: HTMLElement): string | null {
    const dialog = aside.closest<HTMLElement>('dialog[data-pajak-sidebar]');

    return dialog?.id || aside.id || null;
}

// Resolves a sidebar's `.pajak-sb` aside from its public id - either the id of the
// `<dialog>` wrapping it (mobile overlay) or the id on the aside itself (standalone desktop).
function getRailAside(id: string): HTMLElement | null {
    const dialog = document.querySelector<HTMLElement>(`dialog#${id}[data-pajak-sidebar]`);
    if (dialog) {
        return dialog.querySelector<HTMLElement>('.pajak-sb');
    }

    return document.querySelector<HTMLElement>(`.pajak-sb#${id}`);
}

// A single tooltip is shared by every sidebar and lives on <body>: the nav list clips its
// overflow and the mobile panel is transformed, so an in-item tooltip would be cut off or
// mispositioned. It is aria-hidden because the item's own (visually hidden) label already
// names it in rail mode.
function getRailTooltip(): HTMLElement {
    if (!railTooltip) {
        railTooltip = document.createElement('div');
        railTooltip.className = 'pajak-sb-tooltip';
        railTooltip.setAttribute('aria-hidden', 'true');
        railTooltip.hidden = true;
    }

    // Re-attach if the body's content was replaced (e.g. by page-swapping navigation)
    if (!railTooltip.isConnected) {
        document.body.appendChild(railTooltip);
    }

    return railTooltip;
}

function hideRailTooltip(): void {
    if (railTooltip) {
        railTooltip.hidden = true;
    }
}

function showRailTooltip(item: HTMLElement): void {
    if (!item.closest('.pajak-sb')?.classList.contains('pajak-sb--rail')) {
        return;
    }

    const label = item.querySelector('.pajak-sb-item__label')?.textContent?.trim();

    if (!label) {
        return;
    }

    const rect = item.getBoundingClientRect();
    const tooltip = getRailTooltip();

    tooltip.textContent = label;
    tooltip.style.left = `${rect.right + TOOLTIP_GAP}px`;
    tooltip.style.top = `${rect.top + rect.height / 2}px`;
    tooltip.hidden = false;
}

function railItemOf(event: Event): HTMLElement | null {
    return (event.target as HTMLElement).closest<HTMLElement>('.pajak-sb-item');
}

function leavingItem(event: PointerEvent | FocusEvent, item: HTMLElement): boolean {
    return !item.contains(event.relatedTarget as Node | null);
}

function initRailTooltips(aside: HTMLElement): void {
    if (tooltipAsides.has(aside)) {
        return;
    }

    tooltipAsides.add(aside);

    aside.addEventListener('pointerover', (event) => {
        const item = railItemOf(event);

        // Touch taps would flash the tooltip while navigating
        if (item && event.pointerType !== 'touch') {
            showRailTooltip(item);
        }
    });

    aside.addEventListener('pointerout', (event) => {
        const item = railItemOf(event);

        if (item && leavingItem(event, item)) {
            hideRailTooltip();
        }
    });

    aside.addEventListener('focusin', (event) => {
        const item = railItemOf(event);

        if (item) {
            showRailTooltip(item);
        }
    });

    aside.addEventListener('focusout', (event) => {
        const item = railItemOf(event);

        if (item && leavingItem(event, item)) {
            hideRailTooltip();
        }
    });

    aside.querySelector('.pajak-sb__scroll')?.addEventListener('scroll', hideRailTooltip);

    if (!railTooltipListening) {
        railTooltipListening = true;
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                hideRailTooltip();
            }
        });
    }
}

function applyRailState(aside: HTMLElement, isRail: boolean): void {
    aside.classList.toggle('pajak-sb--rail', isRail);
    hideRailTooltip();

    aside.querySelectorAll<HTMLElement>('[data-pajak-sidebar-rail]').forEach((btn) => {
        btn.classList.toggle('is-rail', isRail);
    });
}

function toggleRailAside(aside: HTMLElement): void {
    const isRail = !aside.classList.contains('pajak-sb--rail');
    applyRailState(aside, isRail);

    const id = railStorageId(aside);
    if (id) {
        localStorage.setItem(getRailStorageKey(id), isRail ? '1' : '0');
    }
}

function toggleRail(id: string): void {
    const aside = getRailAside(id);
    if (aside) {
        toggleRailAside(aside);
    }
}

function createTriggerButton(id: string): HTMLButtonElement {
    const btn = document.createElement('button');
    btn.className = 'pajak-sb-toggle pajak-sb-toggle--floating';
    btn.setAttribute('data-pajak-sidebar-trigger', id);
    btn.setAttribute('aria-label', 'Open navigation');
    btn.innerHTML = `
        <span class="pajak-sb-toggle__icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    `;
    btn.addEventListener('click', () => openSidebar(id));
    return btn;
}

function initAll(root: ParentNode = document): void {
    root.querySelectorAll<HTMLDialogElement>('dialog[data-pajak-sidebar]').forEach((sidebar) => {
        if (initializedSidebars.has(sidebar)) {
            return;
        }

        initializedSidebars.add(sidebar);

        const id = sidebar.id;
        if (!id) {
            return;
        }

        // Auto-inject trigger into designated slot if none exists
        if (!getTrigger(id)) {
            const slot = document.querySelector<HTMLElement>(`[data-pajak-sidebar-trigger-slot="${id}"]`);
            if (slot) {
                const btn = createTriggerButton(id);
                slot.appendChild(btn);
            }
        }

        // Inject a backdrop div for click-outside-to-close
        const backdrop = document.createElement('div');
        backdrop.className = 'pajak-sb-backdrop';
        sidebar.appendChild(backdrop);
        backdrop.addEventListener('click', () => closeSidebar(id));

        sidebar.querySelectorAll<HTMLElement>('[data-pajak-sidebar-close]').forEach((btn) => {
            btn.addEventListener('click', () => closeSidebar(id));
        });
    });

    // Rail (collapse-to-icons) applies to every `.pajak-sb`, standalone or dialog-wrapped.
    root.querySelectorAll<HTMLElement>('.pajak-sb').forEach((aside) => {
        const id = railStorageId(aside);
        if (!initializedAsides.has(aside) && id && localStorage.getItem(getRailStorageKey(id)) === '1') {
            aside.classList.add('pajak-sb--no-transition');
            applyRailState(aside, true);
            // Double-rAF ensures layout is flushed before removing the class,
            // so transitions are not triggered during the restore.
            requestAnimationFrame(() => {
                requestAnimationFrame(() => aside.classList.remove('pajak-sb--no-transition'));
            });
        }

        initializedAsides.add(aside);
        initRailTooltips(aside);

        aside.querySelectorAll<HTMLElement>('[data-pajak-sidebar-rail]').forEach((btn) => {
            if (initializedRailButtons.has(btn)) {
                return;
            }

            initializedRailButtons.add(btn);
            btn.addEventListener('click', () => toggleRailAside(aside));
        });
    });

    // Also wire up any manually placed triggers
    root.querySelectorAll<HTMLElement>('[data-pajak-sidebar-trigger]').forEach((trigger) => {
        const id = trigger.dataset.pajakSidebarTrigger ?? '';
        if (!id || initializedTriggers.has(trigger)) {
            return;
        }

        initializedTriggers.add(trigger);

        // Only add listener if not already set up via auto-inject
        if (!trigger.classList.contains('pajak-sb-toggle--floating')) {
            trigger.addEventListener('click', () => openSidebar(id));
        }
    });
}

export const PajakSidebar = {
    open: openSidebar,
    close: closeSidebar,
    rail: toggleRail,
    initAll,
} as const;

window.Pajak = { ...window.Pajak, PajakSidebar };

onReady(() => PajakSidebar.initAll());
