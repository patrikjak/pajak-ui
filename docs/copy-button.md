# Copy Wrapper Component

A wrapper that makes any slotted content copy a value to the clipboard on click. Available as `<x-pajak::copy-button>`.

When clicked, a small "Copied!" tooltip bubble appears above the trigger for 1.5 seconds, then fades out. The inner content is left unchanged — it can be plain text, a `<code>` block, a button, an icon, or anything else.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the copy-button-only bundles:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/copy-button-standalone.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/copy-button.js') }}"></script>
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/copy-button/copy-button-standalone';
```

---

## Usage

```blade
{{-- Plain text --}}
<x-pajak::copy-button value="Hello, world!">
    Hello, world!
</x-pajak::copy-button>

{{-- Code snippet --}}
<x-pajak::copy-button value="npm install pajak/ui">
    <code>npm install pajak/ui</code>
</x-pajak::copy-button>

{{-- Icon button --}}
<x-pajak::copy-button :value="$apiKey">
    <button type="button" aria-label="Copy API key">
        <x-heroicon-o-clipboard class="w-4 h-4" />
    </button>
</x-pajak::copy-button>

{{-- Inline alongside a displayed value --}}
<div class="flex items-center gap-2">
    <code>{{ $apiKey }}</code>
    <x-pajak::copy-button :value="$apiKey">
        <x-heroicon-o-clipboard class="w-4 h-4 text-neutral-400" />
    </x-pajak::copy-button>
</div>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `value` | `string` | — | **Required.** The text written to the clipboard on click. |

**Slot:** `$slot` — the clickable trigger content. Can be any markup — text, `<code>`, `<button>`, icons, etc.

Additional HTML attributes (`class`, `id`, `data-*`, etc.) are merged onto the wrapper `<span>`.

---

## JavaScript API

```ts
import { PajakCopyButton } from 'vendor/pajak/ui/js/copy-button/copy-button';

PajakCopyButton.initAll();

// Custom "Copied!" label (e.g. for localisation)
PajakCopyButton.initAll('Zkopírováno!');
```

Or via the global namespace when using the pre-built bundle:

```js
window.Pajak.PajakCopyButton.initAll();
```

| Method | Signature | Description |
|--------|-----------|-------------|
| `initAll` | `(label?: string) => void` | Attaches click handlers to every `[data-pajak-copy]` element in the document. `label` is the tooltip text shown on success — defaults to `'Copied!'`. Safe to call multiple times; already-initialised triggers are skipped. |

### Re-initialising after DOM mutations

If copy triggers are injected dynamically (e.g. Livewire or AJAX), call `initAll()` again after the new HTML is in the DOM.
