# Async

Container that fetches its content from a URL via AJAX on page load and shows a frosted-glass loading overlay while the request is in flight.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the async-only bundles:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/async-standalone.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/async.js') }}"></script>
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/async/async-standalone';
```

```ts
import { PajakAsync } from 'vendor/pajak/ui/js/async/async';
PajakAsync.initAll();
```

---

## Usage

```blade
{{-- Minimal: fetches /api/stats, shows spinner while loading --}}
<x-pajak::async url="/api/stats" />

{{-- With SSR fallback rendered on first paint (replaced after JS fetch) --}}
<x-pajak::async url="/api/stats">
    <p>Loading…</p>
</x-pajak::async>

{{-- Custom spinner size and overlay label --}}
<x-pajak::async url="/api/stats" size="lg" label="Refreshing data" />
```

The back-end URL must return raw HTML. The response is injected verbatim into the content area.

---

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `url` | `string` | — | URL to fetch HTML from (required) |
| `size` | `SpinnerSize` | `SpinnerSize::Md` | Arc spinner size shown in the overlay |
| `label` | `string` | `'Loading'` | Text shown next to the spinner in the overlay |

### `SpinnerSize` values

`xs` (12px) · `sm` (16px) · `md` (20px) · `lg` (24px) · `xl` (32px) · `xxl` (48px)

---

## JS API

```ts
// Init all [data-pajak-async] elements on the page
PajakAsync.initAll();

// Init a single element (useful after dynamic injection)
PajakAsync.init(el);

// Re-fetch an already-initialised element (e.g. after a user action)
PajakAsync.refresh(el);
```

`PajakAsync` is also available on `window.Pajak.PajakAsync` when using the pre-built bundle.

---

## Events

All events bubble and are dispatched on the wrapper element.

| Event | When |
|-------|------|
| `pajak:async:loading` | Fetch started |
| `pajak:async:loaded` | HTML injected successfully |
| `pajak:async:error` | Fetch failed (network error or non-2xx response) |

```ts
document.querySelector('[data-pajak-async]')
    ?.addEventListener('pajak:async:loaded', () => {
        console.log('content ready');
    });
```

The `pajak:async:error` event includes `detail.status` (HTTP status code) when the server returned a non-2xx response. On network failure, `detail` is absent.

---

## CSS States

| Class | Meaning |
|-------|---------|
| `is-loading` | Overlay visible; spinner spinning; content non-interactive |
| `is-loaded` | Content present; overlay hidden |
| `is-error` | Fetch failed; overlay shows error state |
