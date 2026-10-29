import { describe, it, expect, beforeEach, vi } from 'vitest';
import { PajakSidebar } from '../../resources/assets/js/sidebar/sidebar';

function mount(rail: boolean): { aside: HTMLElement; item: HTMLElement; other: HTMLElement } {
    document.body.innerHTML = `
        <aside id="nav" class="pajak-sb${rail ? ' pajak-sb--rail' : ''}">
            <div class="pajak-sb__brand">
                <button class="pajak-sb-rail-btn" data-pajak-sidebar-rail aria-label="Toggle rail"></button>
            </div>
            <nav class="pajak-sb__scroll">
                <a class="pajak-sb-item" id="billing" href="/billing">
                    <span class="pajak-sb-item__label">Billing</span>
                </a>
                <a class="pajak-sb-item" id="team" href="/team">
                    <span class="pajak-sb-item__label">Team</span>
                </a>
            </nav>
        </aside>
    `;

    const aside = document.getElementById('nav')!;
    aside.getBoundingClientRect = () => ({ left: 0, top: 0, right: 72, bottom: 600, width: 72, height: 600, x: 0, y: 0, toJSON: () => ({}) }) as DOMRect;
    document.getElementById('billing')!.getBoundingClientRect = () => ({ left: 14, top: 100, right: 58, bottom: 144, width: 44, height: 44, x: 14, y: 100, toJSON: () => ({}) }) as DOMRect;

    PajakSidebar.initAll();

    return { aside, item: document.getElementById('billing')!, other: document.getElementById('team')! };
}

function tooltip(): HTMLElement | null {
    return document.querySelector<HTMLElement>('.pajak-sb-tooltip');
}

function focusIn(el: HTMLElement): void {
    el.dispatchEvent(new FocusEvent('focusin', { bubbles: true }));
}

function focusOut(el: HTMLElement, relatedTarget: HTMLElement | null = null): void {
    el.dispatchEvent(new FocusEvent('focusout', { bubbles: true, relatedTarget }));
}

beforeEach(() => {
    document.body.innerHTML = '';
    vi.stubGlobal('localStorage', {
        getItem: () => null,
        setItem: () => undefined,
        removeItem: () => undefined,
    });
});

describe('sidebar rail tooltips', () => {
    it('shows the item label to the right of the item on keyboard focus', () => {
        const { item } = mount(true);

        focusIn(item);

        expect(tooltip()!.hidden).toBe(false);
        expect(tooltip()!.textContent).toBe('Billing');
        expect(tooltip()!.style.left).toBe('70px');
        expect(tooltip()!.style.top).toBe('122px');
    });

    it('shows on mouse hover but not on touch', () => {
        const { item } = mount(true);

        item.dispatchEvent(new MouseEvent('pointerover', { bubbles: true }));
        expect(tooltip()!.hidden).toBe(false);

        focusOut(item);
        expect(tooltip()!.hidden).toBe(true);

        const touch = new MouseEvent('pointerover', { bubbles: true });
        Object.defineProperty(touch, 'pointerType', { value: 'touch' });
        item.dispatchEvent(touch);
        expect(tooltip()!.hidden).toBe(true);
    });

    it('hides on pointer out and on focus out', () => {
        const { item } = mount(true);

        focusIn(item);
        focusOut(item);
        expect(tooltip()!.hidden).toBe(true);

        item.dispatchEvent(new MouseEvent('pointerover', { bubbles: true }));
        item.dispatchEvent(new MouseEvent('pointerout', { bubbles: true }));
        expect(tooltip()!.hidden).toBe(true);
    });

    it('stays visible when focus moves between children of the same item', () => {
        const { item } = mount(true);
        const label = item.querySelector<HTMLElement>('.pajak-sb-item__label')!;

        focusIn(item);
        focusOut(item, label);

        expect(tooltip()!.hidden).toBe(false);
    });

    it('follows the focused item', () => {
        const { item, other } = mount(true);

        focusIn(item);
        focusOut(item, other);
        focusIn(other);

        expect(tooltip()!.textContent).toBe('Team');
    });

    it('does nothing when the sidebar is not in rail mode', () => {
        const { item } = mount(false);

        focusIn(item);

        expect(tooltip()).toBeNull();
    });

    it('hides on Escape', () => {
        const { item } = mount(true);

        focusIn(item);
        document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }));

        expect(tooltip()!.hidden).toBe(true);
    });

    it('hides when the nav list scrolls', () => {
        const { aside, item } = mount(true);

        focusIn(item);
        aside.querySelector('.pajak-sb__scroll')!.dispatchEvent(new Event('scroll'));

        expect(tooltip()!.hidden).toBe(true);
    });

    it('hides when the rail is toggled and stops showing once expanded', () => {
        const { aside, item } = mount(true);

        focusIn(item);
        aside.querySelector<HTMLElement>('[data-pajak-sidebar-rail]')!.click();

        expect(aside.classList.contains('pajak-sb--rail')).toBe(false);
        expect(tooltip()!.hidden).toBe(true);

        focusIn(item);
        expect(tooltip()!.hidden).toBe(true);
    });

    it('is aria-hidden so the item label is not announced twice', () => {
        const { item } = mount(true);

        focusIn(item);

        expect(tooltip()!.getAttribute('aria-hidden')).toBe('true');
    });
});
