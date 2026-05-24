# Tooltip

A wrapper component that shows a tooltip bubble on hover or keyboard focus. The default slot is the trigger; the `text` prop is the tooltip content.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the tooltip-only bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/tooltip-standalone.css') }}">
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
// Tooltip only
@use 'vendor/pajak/ui/css/tooltip/tooltip-standalone';

// Or just the partial (when variables are already imported)
@use 'vendor/pajak/ui/css/tooltip/tooltip';
```

---

## Usage

```blade
{{-- Basic (top, default) --}}
<x-pajak::tooltip text="Upload document">
    <button>Upload</button>
</x-pajak::tooltip>

{{-- With placement --}}
<x-pajak::tooltip text="Save draft" placement="right">
    <button>Save</button>
</x-pajak::tooltip>

{{-- Wrapping a pajak button --}}
<x-pajak::tooltip text="Delete this record" placement="bottom">
    <x-pajak::button variant="danger">Delete</x-pajak::button>
</x-pajak::tooltip>
```

---

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `text` | `string` | — | Tooltip label (required) |
| `placement` | `TooltipPlacement` | `TooltipPlacement::Top` | Which side the bubble appears on |

### `TooltipPlacement` values

| Value | Position |
|-------|----------|
| `top` | Above the trigger, centred |
| `bottom` | Below the trigger, centred |
| `left` | Left of the trigger, vertically centred |
| `right` | Right of the trigger, vertically centred |

---

## Behaviour

- Show/hide is pure CSS — no JavaScript required.
- Tooltip appears on `:hover` and `:focus-within` (keyboard accessible).
- The bubble fades in/out via `opacity` + `visibility` transition.
- `white-space: nowrap` by default; override via `$attributes` if wrapping text is needed.
- The tooltip span carries `role="tooltip"` for screen readers.
