import { describe, it, expect, beforeEach } from 'vitest';
import '../../resources/assets/js/form/form';
import { PajakRepeater } from '../../resources/assets/js/form/repeater';

const selectMarkup = `
    <div data-pajak-select data-searchable data-placeholder="Pick">
        <select class="pajak-select__native" name="unit">
            <option value="" disabled selected>Pick</option>
            <option value="g">Gram</option>
        </select>
        <div class="pajak-select__trigger"><span class="pajak-select__value"></span></div>
        <div class="pajak-select__dropdown" hidden>
            <div class="pajak-select__search"><input class="pajak-select__search-input" type="text"></div>
        </div>
    </div>
`;

beforeEach(() => {
    document.body.innerHTML = `
        <div class="pajak-repeater" data-pajak-repeater data-name="lines" data-min="0">
            <div class="pajak-repeater__rows"></div>
            <template class="pajak-repeater__template">
                <div class="pajak-repeater__row">
                    ${selectMarkup}
                    <button type="button" class="pajak-repeater__remove"></button>
                </div>
            </template>
            <button type="button" class="pajak-repeater__add">Add</button>
        </div>
    `;
});

describe('PajakRepeater', () => {
    it('passes the new row in the add event', () => {
        const wrap = document.querySelector<HTMLElement>('[data-pajak-repeater]')!;
        const rows: Element[] = [];
        wrap.addEventListener('pajak:repeater:add', (event) => {
            rows.push((event as CustomEvent<{ row: Element }>).detail.row);
        });

        PajakRepeater.init(wrap).add();

        expect(rows).toHaveLength(1);
        expect(rows[0]).toBe(wrap.querySelector('.pajak-repeater__row'));
    });

    it('upgrades components inside an added row', () => {
        const wrap = document.querySelector<HTMLElement>('[data-pajak-repeater]')!;
        PajakRepeater.init(wrap).add();

        const dropdown = wrap.querySelector<HTMLElement>('.pajak-select__dropdown')!;
        wrap.querySelector<HTMLElement>('.pajak-select__trigger')!.click();

        expect(dropdown.hidden).toBe(false);
    });
});
