import { describe, it, expect, vi, beforeEach } from 'vitest';
import { PajakTable } from '../../resources/assets/js/table/table';
import { toastFromResponse } from '../../resources/assets/js/form/form-submit';

beforeEach(() => {
    vi.restoreAllMocks();
    document.body.innerHTML = '';
    sessionStorage.clear();
});

function jsonResponse(body: unknown, status: number): Response {
    return {
        ok: status >= 200 && status < 300,
        status,
        json: () => Promise.resolve(body),
        text: () => Promise.resolve(''),
    } as unknown as Response;
}

function mountTable(actionAttrs: string): HTMLElement {
    document.body.innerHTML = `
        <div id="t1" data-pajak-table data-table-name="users" data-url="/users/table">
            <div data-pajak-table-body></div>
            <button type="button" id="act" data-pajak-table-form-action="/users/5/resend"
                data-pajak-table-form-method="POST" ${actionAttrs}>Resend</button>
        </div>
    `;
    const wrapper = document.getElementById('t1')!;
    PajakTable.initTable(wrapper);

    return wrapper;
}

describe('toastFromResponse', () => {
    it('returns the response toast when present', () => {
        expect(toastFromResponse({ ok: true, status: 200, data: { toast: { type: 'success', title: 'Sent' } } }))
            .toEqual({ type: 'success', title: 'Sent' });
    });

    it('returns null for a successful response without a toast', () => {
        expect(toastFromResponse({ ok: true, status: 200, data: {} })).toBeNull();
    });

    it('builds an error toast from the message, then from the first validation error', () => {
        expect(toastFromResponse({ ok: false, status: 422, data: { message: 'No longer pending.', errors: { a: ['x'] } } }))
            .toEqual({ type: 'error', title: 'No longer pending.' });
        expect(toastFromResponse({ ok: false, status: 422, data: { errors: { a: ['First'] } } }))
            .toEqual({ type: 'error', title: 'First' });
        expect(toastFromResponse({ ok: false, status: 0, data: null })).toBeNull();
    });
});

describe('async table row actions', () => {
    it('sends fetch + JSON, shows the toast and refreshes the table on success', async () => {
        const fetchMock = vi.fn()
            .mockResolvedValueOnce(jsonResponse({ toast: { type: 'success', title: 'Invitation sent' } }, 200))
            .mockResolvedValue(jsonResponse(null, 200));
        vi.stubGlobal('fetch', fetchMock);

        const wrapper = mountTable('data-pajak-table-async');
        const events: string[] = [];
        wrapper.addEventListener('pajak:table:action-success', () => events.push('success'));

        document.getElementById('act')!.click();

        await vi.waitFor(() => expect(events).toEqual(['success']));
        await vi.waitFor(() => expect(fetchMock).toHaveBeenCalledTimes(2));

        expect(fetchMock.mock.calls[0][0]).toBe('/users/5/resend');
        expect(fetchMock.mock.calls[0][1].method).toBe('POST');
        expect(fetchMock.mock.calls[0][1].headers.Accept).toBe('application/json');
        expect(fetchMock.mock.calls[1][0]).toBe('/users/table');
        expect(document.querySelector('.pajak-toast__title')?.textContent).toBe('Invitation sent');
    });

    it('shows an error toast and does not refresh the table on a 422', async () => {
        const fetchMock = vi.fn().mockResolvedValue(
            jsonResponse({ message: 'The invitation is no longer pending.', errors: { invitation: ['x'] } }, 422),
        );
        vi.stubGlobal('fetch', fetchMock);

        const wrapper = mountTable('data-pajak-table-async');
        const events: string[] = [];
        wrapper.addEventListener('pajak:table:action-error', () => events.push('error'));

        document.getElementById('act')!.click();

        await vi.waitFor(() => expect(events).toEqual(['error']));

        expect(document.querySelector('.pajak-toast--error .pajak-toast__title')?.textContent)
            .toBe('The invitation is no longer pending.');
        expect(fetchMock).toHaveBeenCalledTimes(1);
    });

    it('spoofing-capable methods are sent natively', async () => {
        const fetchMock = vi.fn().mockResolvedValue(jsonResponse({}, 200));
        vi.stubGlobal('fetch', fetchMock);

        const wrapper = mountTable('data-pajak-table-async');
        wrapper.querySelector('#act')!.setAttribute('data-pajak-table-form-method', 'DELETE');

        document.getElementById('act')!.click();

        await vi.waitFor(() => expect(fetchMock).toHaveBeenCalled());
        expect(fetchMock.mock.calls[0][1].method).toBe('DELETE');
    });
});
