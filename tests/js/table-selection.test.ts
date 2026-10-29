import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import { PajakTable } from '../../resources/assets/js/table/table';

const PAGES: Record<number, string[]> = {
    1: ['1', '2'],
    2: ['3', '4'],
};

function rowsHtml(ids: string[]): string {
    return ids.map((id) => `
        <tr><td><input type="checkbox" data-pajak-table-row-check value="${id}"></td></tr>
    `).join('');
}

function paginationHtml(): string {
    return `
        <div class="pajak-table-pagination">
            <button type="button" data-pajak-table-page="1">1</button>
            <button type="button" data-pajak-table-page="2">2</button>
        </div>
    `;
}

function pageResponse(page: number): Response {
    const html = `<table><tbody data-pajak-table-body>${rowsHtml(PAGES[page] ?? [])}</tbody></table>${paginationHtml()}`;

    return { ok: true, status: 200, text: () => Promise.resolve(html), json: () => Promise.resolve({}) } as unknown as Response;
}

function mountTable(): HTMLElement {
    document.body.innerHTML = `
        <div id="t1" data-pajak-table data-table-name="users" data-url="/users/table">
            <input type="search" data-pajak-table-search>
            <div data-pajak-table-bulk-bar hidden>
                <span data-pajak-table-bulk-count data-template=":count selected"></span>
                <button type="button" data-pajak-table-bulk-clear>Clear</button>
                <button type="button" data-pajak-table-bulk-action="delete">Delete</button>
            </div>
            <table>
                <thead><tr><th><input type="checkbox" data-pajak-table-select-all></th></tr></thead>
                <tbody data-pajak-table-body>${rowsHtml(PAGES[1])}</tbody>
            </table>
            ${paginationHtml()}
        </div>
    `;

    const wrapper = document.getElementById('t1')!;
    PajakTable.initTable(wrapper);

    return wrapper;
}

function checkbox(wrapper: HTMLElement, id: string): HTMLInputElement {
    return wrapper.querySelector<HTMLInputElement>(`[data-pajak-table-row-check][value="${id}"]`)!;
}

function setChecked(el: HTMLInputElement, checked: boolean): void {
    el.checked = checked;
    el.dispatchEvent(new Event('change', { bubbles: true }));
}

function selectAll(wrapper: HTMLElement): HTMLInputElement {
    return wrapper.querySelector<HTMLInputElement>('[data-pajak-table-select-all]')!;
}

function bulkBar(wrapper: HTMLElement): HTMLElement {
    return wrapper.querySelector<HTMLElement>('[data-pajak-table-bulk-bar]')!;
}

function bulkCount(wrapper: HTMLElement): string {
    return wrapper.querySelector('[data-pajak-table-bulk-count]')!.textContent ?? '';
}

async function goToPage(wrapper: HTMLElement, page: number): Promise<void> {
    wrapper.querySelector<HTMLElement>(`[data-pajak-table-page="${page}"]`)!.click();
    await vi.waitFor(() => expect(checkbox(wrapper, PAGES[page][0])).not.toBeNull());
}

beforeEach(() => {
    vi.restoreAllMocks();
    sessionStorage.clear();
    vi.stubGlobal('fetch', vi.fn((_url: string, init: RequestInit) => {
        const body = JSON.parse(init.body as string) as { page: number };

        return Promise.resolve(pageResponse(body.page));
    }));
});

afterEach(() => {
    vi.useRealTimers();
});

describe('table row selection across pages', () => {
    it('keeps the selection and bulk count while paging, and restores the boxes when returning', async () => {
        const wrapper = mountTable();

        setChecked(checkbox(wrapper, '1'), true);
        setChecked(checkbox(wrapper, '2'), true);
        expect(bulkCount(wrapper)).toBe('2 selected');

        await goToPage(wrapper, 2);

        expect(checkbox(wrapper, '3').checked).toBe(false);
        expect(bulkBar(wrapper).hidden).toBe(false);
        expect(bulkCount(wrapper)).toBe('2 selected');
        expect(selectAll(wrapper).checked).toBe(false);
        expect(selectAll(wrapper).indeterminate).toBe(false);

        setChecked(checkbox(wrapper, '3'), true);
        expect(bulkCount(wrapper)).toBe('3 selected');
        expect(selectAll(wrapper).indeterminate).toBe(true);

        await goToPage(wrapper, 1);

        expect(checkbox(wrapper, '1').checked).toBe(true);
        expect(checkbox(wrapper, '2').checked).toBe(true);
        expect(selectAll(wrapper).checked).toBe(true);
        expect(bulkCount(wrapper)).toBe('3 selected');
    });

    it('scopes select-all to the rows on the current page', async () => {
        const wrapper = mountTable();

        selectAll(wrapper).checked = true;
        selectAll(wrapper).dispatchEvent(new Event('change'));
        expect(bulkCount(wrapper)).toBe('2 selected');

        await goToPage(wrapper, 2);

        selectAll(wrapper).checked = true;
        selectAll(wrapper).dispatchEvent(new Event('change'));
        expect(bulkCount(wrapper)).toBe('4 selected');

        selectAll(wrapper).checked = false;
        selectAll(wrapper).dispatchEvent(new Event('change'));
        expect(bulkCount(wrapper)).toBe('2 selected');
        expect(PajakTable.getSelection(wrapper)).toEqual(['1', '2']);
    });

    it('reports ids from every page in the bulk-action event', async () => {
        const wrapper = mountTable();
        const seen: unknown[] = [];
        wrapper.addEventListener('pajak:table:bulk-action', (event) => seen.push((event as CustomEvent).detail));

        setChecked(checkbox(wrapper, '2'), true);
        await goToPage(wrapper, 2);
        setChecked(checkbox(wrapper, '4'), true);

        wrapper.querySelector<HTMLElement>('[data-pajak-table-bulk-action]')!.click();

        expect(seen).toEqual([{ action: 'delete', ids: ['2', '4'], tableName: 'users' }]);
    });

    it('clears the selection when the search changes', async () => {
        vi.useFakeTimers();
        const wrapper = mountTable();

        setChecked(checkbox(wrapper, '1'), true);

        const input = wrapper.querySelector<HTMLInputElement>('[data-pajak-table-search]')!;
        input.value = 'ann';
        input.dispatchEvent(new Event('input'));
        await vi.advanceTimersByTimeAsync(300);

        expect(PajakTable.getSelection(wrapper)).toEqual([]);
        expect(bulkBar(wrapper).hidden).toBe(true);
        expect(checkbox(wrapper, '1').checked).toBe(false);
    });

    it('keeps the selection when only the page changes with an active search', async () => {
        vi.useFakeTimers();
        const wrapper = mountTable();

        const input = wrapper.querySelector<HTMLInputElement>('[data-pajak-table-search]')!;
        input.value = 'ann';
        input.dispatchEvent(new Event('input'));
        await vi.advanceTimersByTimeAsync(300);

        setChecked(checkbox(wrapper, '1'), true);
        wrapper.querySelector<HTMLElement>('[data-pajak-table-page="2"]')!.click();
        await vi.advanceTimersByTimeAsync(0);

        expect(PajakTable.getSelection(wrapper)).toEqual(['1']);
    });

    it('clearSelection resets the set, the boxes and the bulk bar', () => {
        const wrapper = mountTable();

        setChecked(checkbox(wrapper, '1'), true);
        PajakTable.clearSelection(wrapper);

        expect(PajakTable.getSelection(wrapper)).toEqual([]);
        expect(checkbox(wrapper, '1').checked).toBe(false);
        expect(bulkBar(wrapper).hidden).toBe(true);
    });

    it('the Clear button drops selected rows on other pages too', async () => {
        const wrapper = mountTable();

        setChecked(checkbox(wrapper, '1'), true);
        await goToPage(wrapper, 2);

        wrapper.querySelector<HTMLElement>('[data-pajak-table-bulk-clear]')!.click();

        expect(PajakTable.getSelection(wrapper)).toEqual([]);
        expect(bulkBar(wrapper).hidden).toBe(true);

        await goToPage(wrapper, 1);
        expect(checkbox(wrapper, '1').checked).toBe(false);
    });
});
