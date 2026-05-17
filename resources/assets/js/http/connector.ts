export type HttpMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';

export type HttpPayload = Record<string, unknown> | FormData | null;

export interface HttpSuccess<T> {
    ok: true;
    status: number;
    data: T;
}

export interface HttpError {
    ok: false;
    status: number;
    data: unknown;
}

export type HttpResult<T = unknown> = HttpSuccess<T> | HttpError;

function csrfToken(): string | null {
    return document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? null;
}

function buildHeaders(payload: HttpPayload): HeadersInit {
    const headers: Record<string, string> = {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };

    const token = csrfToken();
    if (token) {
        headers['X-CSRF-TOKEN'] = token;
    }

    if (!(payload instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
    }

    return headers;
}

function buildBody(method: HttpMethod, payload: HttpPayload): BodyInit | null {
    if (method === 'GET' || payload === null) {
        return null;
    }

    return payload instanceof FormData ? payload : JSON.stringify(payload);
}

function buildUrl(url: string, method: HttpMethod, payload: HttpPayload): string {
    if (method !== 'GET' || !(payload instanceof Object) || payload instanceof FormData) {
        return url;
    }

    const params = new URLSearchParams(payload as Record<string, string>);
    const query = params.toString();

    return query ? `${url}?${query}` : url;
}

export async function sendRequest<T = unknown>(
    url: string,
    method: HttpMethod = 'GET',
    payload: HttpPayload = null,
): Promise<HttpResult<T>> {
    const resolvedUrl = buildUrl(url, method, payload);

    let response: Response;

    try {
        response = await fetch(resolvedUrl, {
            method,
            headers: buildHeaders(payload),
            body: buildBody(method, payload),
        });
    } catch {
        return { ok: false, status: 0, data: null };
    }

    let data: unknown;

    try {
        data = await response.json();
    } catch {
        data = null;
    }

    if (response.ok) {
        return { ok: true, status: response.status, data: data as T };
    }

    return { ok: false, status: response.status, data };
}

export async function get<T = unknown>(url: string, params: Record<string, string> = {}): Promise<HttpResult<T>> {
    return sendRequest<T>(url, 'GET', Object.keys(params).length ? params : null);
}

export async function post<T = unknown>(url: string, payload: HttpPayload = null): Promise<HttpResult<T>> {
    return sendRequest<T>(url, 'POST', payload);
}

export async function put<T = unknown>(url: string, payload: HttpPayload = null): Promise<HttpResult<T>> {
    return sendRequest<T>(url, 'PUT', payload);
}

export async function patch<T = unknown>(url: string, payload: HttpPayload = null): Promise<HttpResult<T>> {
    return sendRequest<T>(url, 'PATCH', payload);
}

export async function destroy<T = unknown>(url: string, payload: HttpPayload = null): Promise<HttpResult<T>> {
    return sendRequest<T>(url, 'DELETE', payload);
}
