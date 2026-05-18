import { describe, it, expect, vi, beforeEach } from 'vitest';
import { sendRequest, get, post, put, patch, destroy } from '../../resources/assets/js/http/connector';

beforeEach(() => {
    vi.restoreAllMocks();
    document.head.innerHTML = '';
});

function mockFetch(status: number, body: unknown, ok = status >= 200 && status < 300): void {
    vi.stubGlobal('fetch', vi.fn().mockResolvedValue({
        ok,
        status,
        json: () => Promise.resolve(body),
    }));
}

describe('sendRequest', () => {
    it('returns ok result on 200', async () => {
        mockFetch(200, { id: 1 });

        const result = await sendRequest('/api/test');

        expect(result.ok).toBe(true);
        expect(result.status).toBe(200);
        if (result.ok) {
            expect(result.data).toEqual({ id: 1 });
        }
    });

    it('returns error result on 422', async () => {
        mockFetch(422, { message: 'Unprocessable' }, false);

        const result = await sendRequest('/api/test', 'POST', { name: '' });

        expect(result.ok).toBe(false);
        expect(result.status).toBe(422);
    });

    it('returns status 0 on network failure', async () => {
        vi.stubGlobal('fetch', vi.fn().mockRejectedValue(new Error('Network error')));

        const result = await sendRequest('/api/test');

        expect(result.ok).toBe(false);
        expect(result.status).toBe(0);
    });

    it('appends query params for GET requests', async () => {
        mockFetch(200, {});
        const fetchSpy = vi.mocked(fetch);

        await sendRequest('/api/search', 'GET', { q: 'hello', page: '2' });

        const calledUrl = fetchSpy.mock.calls[0]?.[0] as string;
        expect(calledUrl).toContain('q=hello');
        expect(calledUrl).toContain('page=2');
    });

    it('sends JSON body for POST', async () => {
        mockFetch(201, {});
        const fetchSpy = vi.mocked(fetch);

        await sendRequest('/api/items', 'POST', { name: 'test' });

        const init = fetchSpy.mock.calls[0]?.[1] as RequestInit;
        expect(init.body).toBe('{"name":"test"}');
    });

    it('sends null body for GET', async () => {
        mockFetch(200, {});
        const fetchSpy = vi.mocked(fetch);

        await sendRequest('/api/items', 'GET', null);

        const init = fetchSpy.mock.calls[0]?.[1] as RequestInit;
        expect(init.body).toBeNull();
    });

    it('does not set Content-Type for FormData', async () => {
        mockFetch(200, {});
        const fetchSpy = vi.mocked(fetch);
        const form = new FormData();
        form.append('file', 'data');

        await sendRequest('/api/upload', 'POST', form);

        const init = fetchSpy.mock.calls[0]?.[1] as RequestInit;
        const headers = init.headers as Record<string, string>;
        expect(headers['Content-Type']).toBeUndefined();
    });

    it('includes CSRF token from meta tag', async () => {
        const meta = document.createElement('meta');
        meta.setAttribute('name', 'csrf-token');
        meta.setAttribute('content', 'test-token-123');
        document.head.appendChild(meta);

        mockFetch(200, {});
        const fetchSpy = vi.mocked(fetch);

        await sendRequest('/api/test', 'POST', {});

        const init = fetchSpy.mock.calls[0]?.[1] as RequestInit;
        const headers = init.headers as Record<string, string>;
        expect(headers['X-CSRF-TOKEN']).toBe('test-token-123');
    });

    it('omits CSRF token when meta tag is absent', async () => {
        mockFetch(200, {});
        const fetchSpy = vi.mocked(fetch);

        await sendRequest('/api/test', 'POST', {});

        const init = fetchSpy.mock.calls[0]?.[1] as RequestInit;
        const headers = init.headers as Record<string, string>;
        expect(headers['X-CSRF-TOKEN']).toBeUndefined();
    });
});

describe('convenience methods', () => {
    it('get sends GET request', async () => {
        mockFetch(200, {});
        const fetchSpy = vi.mocked(fetch);

        await get('/api/items');

        const init = fetchSpy.mock.calls[0]?.[1] as RequestInit;
        expect(init.method).toBe('GET');
    });

    it('post sends POST request', async () => {
        mockFetch(201, {});
        const fetchSpy = vi.mocked(fetch);

        await post('/api/items', { name: 'x' });

        const init = fetchSpy.mock.calls[0]?.[1] as RequestInit;
        expect(init.method).toBe('POST');
    });

    it('put sends PUT request', async () => {
        mockFetch(200, {});
        const fetchSpy = vi.mocked(fetch);

        await put('/api/items/1', { name: 'x' });

        const init = fetchSpy.mock.calls[0]?.[1] as RequestInit;
        expect(init.method).toBe('PUT');
    });

    it('patch sends PATCH request', async () => {
        mockFetch(200, {});
        const fetchSpy = vi.mocked(fetch);

        await patch('/api/items/1', { name: 'x' });

        const init = fetchSpy.mock.calls[0]?.[1] as RequestInit;
        expect(init.method).toBe('PATCH');
    });

    it('destroy sends DELETE request', async () => {
        mockFetch(200, {});
        const fetchSpy = vi.mocked(fetch);

        await destroy('/api/items/1');

        const init = fetchSpy.mock.calls[0]?.[1] as RequestInit;
        expect(init.method).toBe('DELETE');
    });
});
