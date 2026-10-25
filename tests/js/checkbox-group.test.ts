import { describe, it, expect, beforeEach } from 'vitest';
import { PajakCheckboxGroup } from '../../resources/assets/js/form/checkbox-group';

function checkboxLabel(input: HTMLInputElement): HTMLLabelElement {
    const label = document.createElement('label');
    label.className = 'pajak-checkbox';
    label.appendChild(input);

    return label;
}

function makeGroup(values: string[], checked: string[] = [], disabled: string[] = []): HTMLElement {
    const group = document.createElement('div');
    group.setAttribute('data-pajak-checkbox-group', '');

    const selectAll = document.createElement('input');
    selectAll.type = 'checkbox';
    selectAll.setAttribute('data-pajak-checkbox-select-all', '');
    group.appendChild(checkboxLabel(selectAll));

    const items = document.createElement('div');
    items.className = 'pajak-checkbox-group__items';

    values.forEach((value) => {
        const input = document.createElement('input');
        input.type = 'checkbox';
        input.name = 'perms[]';
        input.value = value;
        input.checked = checked.includes(value);
        input.disabled = disabled.includes(value);
        items.appendChild(checkboxLabel(input));
    });

    group.appendChild(items);
    document.body.appendChild(group);

    return group;
}

function selectAllOf(group: HTMLElement): HTMLInputElement {
    return group.querySelector<HTMLInputElement>('[data-pajak-checkbox-select-all]')!;
}

function item(group: HTMLElement, value: string): HTMLInputElement {
    return group.querySelector<HTMLInputElement>(`input[value="${value}"]`)!;
}

beforeEach(() => {
    document.body.innerHTML = '';
});

describe('PajakCheckboxGroup', () => {
    it('starts unchecked when no item is checked', () => {
        const group = makeGroup(['a', 'b']);
        PajakCheckboxGroup.init(group);

        expect(selectAllOf(group).checked).toBe(false);
        expect(selectAllOf(group).indeterminate).toBe(false);
    });

    it('starts indeterminate when some items are checked', () => {
        const group = makeGroup(['a', 'b'], ['a']);
        PajakCheckboxGroup.init(group);

        expect(selectAllOf(group).checked).toBe(false);
        expect(selectAllOf(group).indeterminate).toBe(true);
    });

    it('starts checked when all items are checked', () => {
        const group = makeGroup(['a', 'b'], ['a', 'b']);
        PajakCheckboxGroup.init(group);

        expect(selectAllOf(group).checked).toBe(true);
        expect(selectAllOf(group).indeterminate).toBe(false);
    });

    it('select-all checks every item, then unchecks every item', () => {
        const group = makeGroup(['a', 'b', 'c']);
        PajakCheckboxGroup.init(group);

        selectAllOf(group).checked = true;
        selectAllOf(group).dispatchEvent(new Event('change', { bubbles: true }));
        expect(['a', 'b', 'c'].every((v) => item(group, v).checked)).toBe(true);

        selectAllOf(group).checked = false;
        selectAllOf(group).dispatchEvent(new Event('change', { bubbles: true }));
        expect(['a', 'b', 'c'].some((v) => item(group, v).checked)).toBe(false);
    });

    it('skips disabled items when selecting all', () => {
        const group = makeGroup(['a', 'b'], [], ['b']);
        PajakCheckboxGroup.init(group);

        selectAllOf(group).checked = true;
        selectAllOf(group).dispatchEvent(new Event('change', { bubbles: true }));

        expect(item(group, 'a').checked).toBe(true);
        expect(item(group, 'b').checked).toBe(false);
        expect(selectAllOf(group).checked).toBe(true);
    });

    it('updates select-all state when an item changes', () => {
        const group = makeGroup(['a', 'b']);
        PajakCheckboxGroup.init(group);

        item(group, 'a').checked = true;
        item(group, 'a').dispatchEvent(new Event('change', { bubbles: true }));
        expect(selectAllOf(group).indeterminate).toBe(true);

        item(group, 'b').checked = true;
        item(group, 'b').dispatchEvent(new Event('change', { bubbles: true }));
        expect(selectAllOf(group).checked).toBe(true);
        expect(selectAllOf(group).indeterminate).toBe(false);
    });

    it('notifies item change listeners without looping', () => {
        const group = makeGroup(['a', 'b']);
        PajakCheckboxGroup.init(group);
        let itemChanges = 0;
        group.addEventListener('change', (event) => {
            if (event.target !== selectAllOf(group)) {
                itemChanges++;
            }
        });

        selectAllOf(group).checked = true;
        selectAllOf(group).dispatchEvent(new Event('change', { bubbles: true }));

        expect(itemChanges).toBe(2);
    });

    it('values lists checked item values and emits pajak:checkbox-group', () => {
        const group = makeGroup(['a', 'b', 'c'], ['b']);
        const instance = PajakCheckboxGroup.init(group);
        let detail: { checked: number; total: number } | null = null;
        group.addEventListener('pajak:checkbox-group', (event) => {
            detail = (event as CustomEvent).detail;
        });

        instance.selectAll();

        expect(instance.values).toEqual(['a', 'b', 'c']);
        expect(detail).toEqual({ checked: 3, total: 3 });

        instance.clear();
        expect(instance.values).toEqual([]);
    });

    it('initAll upgrades every group once', () => {
        makeGroup(['a']);
        makeGroup(['b']);

        const first = PajakCheckboxGroup.initAll(document);
        const second = PajakCheckboxGroup.initAll(document);

        expect(first).toHaveLength(2);
        expect(second[0]).toBe(first[0]);
    });
});
