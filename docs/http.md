# HTTP Connector

A lightweight, typed HTTP client built on the native `fetch` API. No dependencies — no axios, no jQuery.

## Assets

### Pre-built (no build step required)

```html
<script type="module" src="{{ asset('vendor/pajak/ui/connector.js') }}"></script>
```

Or include everything via the full bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

### Source import (recommended for production)

Publish the sources:

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

Then import:

```ts
import { sendRequest, get, post, put, patch, destroy } from 'vendor/pajak/ui/js/http/connector';
```

## API

### Convenience methods

```ts
get<T>(url: string, params?: Record<string, string>): Promise<HttpResult<T>>
post<T>(url: string, payload?: HttpPayload): Promise<HttpResult<T>>
put<T>(url: string, payload?: HttpPayload): Promise<HttpResult<T>>
patch<T>(url: string, payload?: HttpPayload): Promise<HttpResult<T>>
destroy<T>(url: string, payload?: HttpPayload): Promise<HttpResult<T>>
```

### Low-level

```ts
sendRequest<T>(url: string, method: HttpMethod, payload?: HttpPayload): Promise<HttpResult<T>>
```

## Types

```ts
type HttpMethod  = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';
type HttpPayload = Record<string, unknown> | FormData | null;

type HttpResult<T> = HttpSuccess<T> | HttpError;

interface HttpSuccess<T> { ok: true;  status: number; data: T       }
interface HttpError      { ok: false; status: number; data: unknown  }
```

Network failures (no response) return `{ ok: false, status: 0, data: null }` — they never throw.

## Usage

### JSON request

```ts
import { post } from 'vendor/pajak/ui/js/http/connector';

const result = await post<{ id: number }>('/api/items', { name: 'foo' });

if (result.ok) {
    console.log(result.data.id);
} else {
    console.error(result.status, result.data);
}
```

### File upload (FormData)

```ts
import { post } from 'vendor/pajak/ui/js/http/connector';

const form = new FormData(document.querySelector('form')!);
const result = await post('/api/upload', form);
```

### GET with query params

```ts
import { get } from 'vendor/pajak/ui/js/http/connector';

const result = await get<Item[]>('/api/items', { page: '2', search: 'foo' });
// → GET /api/items?page=2&search=foo
```

## Behaviour

| Concern | Behaviour |
|---------|-----------|
| CSRF | Reads `meta[name="csrf-token"]` and sends it as `X-CSRF-TOKEN` on every mutating request |
| JSON | Sets `Content-Type: application/json` and serialises the body automatically for plain objects |
| FormData | Detected automatically — `Content-Type` is left unset so the browser sets the correct `multipart/form-data` boundary |
| GET params | A plain object passed to `get()` is appended as a query string |
| Errors | Non-2xx responses return `{ ok: false }` with the parsed response body as `data` |
| Network failure | Returns `{ ok: false, status: 0, data: null }` — never throws |
