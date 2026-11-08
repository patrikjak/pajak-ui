import { describe, it, expect, beforeEach } from 'vitest';
import { PajakSelect } from '../../resources/assets/js/form/select';

beforeEach(() => {
    document.body.innerHTML = '';
});

function makeSelect(multiple: boolean): HTMLElement {
    const wrap = document.createElement('div');
    wrap.setAttribute('data-pajak-select', '');
    wrap.setAttribute('data-searchable', '');
    wrap.dataset.placeholder = 'Pick';
    wrap.dataset.searchPlaceholder = 'No match';

    if (multiple) {
        wrap.setAttribute('data-multiple', '');
    }

    wrap.innerHTML = `
        <select class="pajak-select__native" name="x" ${multiple ? 'multiple' : ''}>
            ${multiple ? '' : '<option value="" disabled selected>Pick</option>'}
            <option value="tomato" data-keywords="rajčina tomat">Paradajka</option>
            <option value="carrot">Mrkva</option>
            <option value="cheese">Sýr eidam</option>
        </select>
        ${multiple
            ? `<div class="pajak-select__trigger pajak-select__trigger--multi">
                   <div class="pajak-select__chips"></div>
                   <input class="pajak-select__chip-input" type="text">
               </div>`
            : `<div class="pajak-select__trigger"><span class="pajak-select__value"></span></div>`}
        <div class="pajak-select__dropdown" hidden>
            ${multiple ? '' : '<div class="pajak-select__search"><input class="pajak-select__search-input" type="text"></div>'}
        </div>
    `;
    document.body.appendChild(wrap);

    return wrap;
}

function offered(): string[] {
    return Array.from(document.querySelectorAll<HTMLElement>('.pajak-select__option')).map((el) => el.dataset.value ?? '');
}

describe('PajakSelect filtering', () => {
    it('matches a keyword ignoring accents in a single select', () => {
        const wrap = makeSelect(false);
        PajakSelect.init(wrap);
        wrap.querySelector<HTMLElement>('.pajak-select__trigger')!.click();
        const search = document.querySelector<HTMLInputElement>('.pajak-select__search-input')!;

        search.value = 'rajcina';
        search.dispatchEvent(new Event('input'));

        expect(offered()).toEqual(['tomato']);
    });

    it('matches a label ignoring accents and case', () => {
        const wrap = makeSelect(false);
        PajakSelect.init(wrap);
        wrap.querySelector<HTMLElement>('.pajak-select__trigger')!.click();
        const search = document.querySelector<HTMLInputElement>('.pajak-select__search-input')!;

        search.value = 'SYR';
        search.dispatchEvent(new Event('input'));

        expect(offered()).toEqual(['cheese']);
    });

    it('matches a keyword in a multiple select', () => {
        const wrap = makeSelect(true);
        PajakSelect.init(wrap);
        const input = wrap.querySelector<HTMLInputElement>('.pajak-select__chip-input')!;

        input.value = 'tomat';
        input.dispatchEvent(new Event('input'));

        expect(offered()).toEqual(['tomato']);
    });

    it('offers every option for an empty query', () => {
        const wrap = makeSelect(false);
        PajakSelect.init(wrap);
        wrap.querySelector<HTMLElement>('.pajak-select__trigger')!.click();

        expect(offered()).toEqual(['tomato', 'carrot', 'cheese']);
    });
});
