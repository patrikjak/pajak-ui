# Empty State

Placeholder component for empty lists, search results, and drop zones. Supports an icon art box, title, message, and actions — all as optional named slots.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the empty-state-only bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/empty-state-standalone.css') }}">
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/empty-state/empty-state';
```

---

## Basic usage

```blade
<x-pajak::empty-state>
    <x-slot:icon>
        <svg width="26" height="26" ...></svg>
    </x-slot:icon>
    <x-slot:title>No documents uploaded yet</x-slot:title>
    <x-slot:message>Drop your PIT-11, ZUS, and any deduction receipts here.</x-slot:message>
    <x-slot:actions>
        <x-pajak::button>Upload document</x-pajak::button>
    </x-slot:actions>
</x-pajak::empty-state>
```

---

## Variants

### Default

White surface card with border and subtle shadow.

### Dashed

Dashed border on a tinted background — suitable for drop zones. Highlights on hover.

```blade
<x-pajak::empty-state :variant="\Pajak\Ui\Common\Enums\EmptyState\EmptyStateVariant::Dashed">
    <x-slot:icon>...</x-slot:icon>
    <x-slot:title>Drop documents here</x-slot:title>
    <x-slot:message>PDF, JPG, PNG — up to 10 MB each</x-slot:message>
</x-pajak::empty-state>
```

### Compact

Reduced padding and smaller art box — suitable for inline use inside cards or panels.

```blade
<x-pajak::empty-state :variant="\Pajak\Ui\Common\Enums\EmptyState\EmptyStateVariant::Compact">
    <x-slot:title>All caught up</x-slot:title>
    <x-slot:message>No outstanding tasks for tax year 2025.</x-slot:message>
</x-pajak::empty-state>
```

---

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `EmptyStateVariant` | `EmptyStateVariant::Default` | Visual variant |

## Slots

| Slot | Description |
|------|-------------|
| `$icon` | SVG or image rendered inside the rounded art box |
| `$title` | Primary heading |
| `$message` | Supporting text (max-width constrained for readability) |
| `$actions` | Row of buttons or links below the message |

All slots are optional. The art box is only rendered when `$icon` is provided.
