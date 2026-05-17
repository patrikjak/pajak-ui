# Button Component

A general-purpose button component. Available as `<x-pajak::button>`.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

### Source import (recommended for production)

```scss
@use 'vendor/pajak/ui/css/button/button-standalone';
```

---

## Button

```blade
<x-pajak::button>Click me</x-pajak::button>

{{-- Sizes: sm | md (default) | lg --}}
<x-pajak::button size="lg">Submit</x-pajak::button>

{{-- Type --}}
<x-pajak::button type="submit">Save</x-pajak::button>
<x-pajak::button type="reset">Reset</x-pajak::button>

{{-- Disabled --}}
<x-pajak::button disabled>Unavailable</x-pajak::button>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `type` | `string` | `'button'` | HTML button type: `button` \| `submit` \| `reset` |
| `size` | `string` | `'md'` | `sm` \| `md` \| `lg` |
| `disabled` | `bool` | `false` | Disables the button |

**Slot:** `$slot` — button label content.

Additional HTML attributes (e.g. `class`, `id`, `data-*`) are forwarded to the `<button>` element. Consumer-provided `class` values are merged with the component's own classes, not replaced.
