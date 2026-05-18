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

{{-- Variants --}}
<x-pajak::button variant="secondary">Save Draft</x-pajak::button>
<x-pajak::button variant="outline">View Details</x-pajak::button>
<x-pajak::button variant="ghost">Cancel</x-pajak::button>
<x-pajak::button variant="danger">Delete</x-pajak::button>

{{-- Type --}}
<x-pajak::button type="submit">Save</x-pajak::button>
<x-pajak::button type="reset">Reset</x-pajak::button>

{{-- Disabled --}}
<x-pajak::button disabled>Unavailable</x-pajak::button>

{{-- Loading --}}
<x-pajak::button :loading="true">Saving…</x-pajak::button>

{{-- Icon + label --}}
<x-pajak::button>
    <svg .../>
    Continue
</x-pajak::button>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `type` | `string` | `'button'` | HTML button type: `button` \| `submit` \| `reset` |
| `size` | `string` | `'md'` | `sm` \| `md` \| `lg` |
| `variant` | `string` | `'primary'` | `primary` \| `secondary` \| `outline` \| `ghost` \| `danger` |
| `disabled` | `bool` | `false` | Disables the button |
| `loading` | `bool` | `false` | Shows arc spinner; dims label; sets `cursor: progress` and blocks pointer events |

**Slot:** `$slot` — button label content. Can include SVG icons alongside text for icon+label buttons. For icon-only buttons, pass `class="px-0"` and set `aria-label`.

Additional HTML attributes (e.g. `class`, `id`, `data-*`) are forwarded to the `<button>` element. Consumer-provided `class` values are merged with the component's own classes, not replaced.

### Variants

| Variant | Use case |
|---------|----------|
| `primary` | Main call-to-action |
| `secondary` | Secondary actions, save/draft |
| `outline` | Neutral actions with visible boundary |
| `ghost` | Tertiary actions, cancel, close |
| `danger` | Destructive actions, delete, remove |
