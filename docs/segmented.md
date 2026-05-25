# Segmented

A segmented control (button group selector) for switching between mutually exclusive options. Use for view switchers, filter toggles, and compact option pickers.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the segmented-only bundles:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/segmented-standalone.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/segmented.js') }}"></script>
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/segmented/segmented';
```

```ts
import { PajakSegmented } from 'vendor/pajak/ui/js/segmented/segmented';
PajakSegmented.initAll();
```

---

## Usage

```blade
<x-pajak::segmented>
    <x-pajak::segmented-option label="Day" :active="true" value="day" />
    <x-pajak::segmented-option label="Week" value="week" />
    <x-pajak::segmented-option label="Month" value="month" />
</x-pajak::segmented>
```

---

## Components

### `<x-pajak::segmented>` — container

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `SegmentedVariant` | `SegmentedVariant::Default` | Visual style |
| `size` | `Size` | `Size::Md` | Control size |
| `full` | `bool` | `false` | Stretches to full container width with equal-width options |

### `<x-pajak::segmented-option>` — option button

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `?string` | `null` | Button text — omit for icon-only |
| `active` | `bool` | `false` | Initial selected state |
| `disabled` | `bool` | `false` | Disables this option |
| `value` | `?string` | `null` | Value emitted in the `pajak-segmented:change` event |

| Slot | Description |
|------|-------------|
| `icon` | Optional leading SVG icon |

---

## Variants

```php
use Pajak\Ui\Common\Enums\Segmented\SegmentedVariant;

SegmentedVariant::Default   // subtle grey track, white selected card
SegmentedVariant::Pill      // fully rounded (pill-shaped) track and options
SegmentedVariant::Brand     // blue track, filled primary selected state
SegmentedVariant::Bordered  // white track with border, tinted selected state
```

## Sizes

```php
use Pajak\Ui\Common\Enums\Size;

Size::Sm   // compact — 12px font, reduced padding
Size::Md   // default
Size::Lg   // large  — 14px font, generous padding
```

---

## Examples

### Style variants

```blade
{{-- Default --}}
<x-pajak::segmented>
    <x-pajak::segmented-option label="All" />
    <x-pajak::segmented-option label="Active" :active="true" />
    <x-pajak::segmented-option label="Archived" />
</x-pajak::segmented>

{{-- Brand (filled) --}}
<x-pajak::segmented :variant="SegmentedVariant::Brand">
    <x-pajak::segmented-option label="Refund" :active="true" />
    <x-pajak::segmented-option label="Owed" />
    <x-pajak::segmented-option label="Even" />
</x-pajak::segmented>

{{-- Pill --}}
<x-pajak::segmented :variant="SegmentedVariant::Pill">
    <x-pajak::segmented-option label="PLN" :active="true" />
    <x-pajak::segmented-option label="EUR" />
</x-pajak::segmented>
```

### With icons

```blade
<x-pajak::segmented>
    <x-pajak::segmented-option label="Cards" :active="true">
        <x-slot:icon>
            <svg width="13" height="13" viewBox="0 0 24 24" ...></svg>
        </x-slot:icon>
    </x-pajak::segmented-option>
    <x-pajak::segmented-option label="List">
        <x-slot:icon>
            <svg width="13" height="13" viewBox="0 0 24 24" ...></svg>
        </x-slot:icon>
    </x-pajak::segmented-option>
</x-pajak::segmented>
```

### Icon-only

Omit `label` to render an icon-only button with compact padding.

```blade
<x-pajak::segmented>
    <x-pajak::segmented-option :active="true">
        <x-slot:icon><svg width="14" height="14" aria-label="Left" ...></svg></x-slot:icon>
    </x-pajak::segmented-option>
    <x-pajak::segmented-option>
        <x-slot:icon><svg width="14" height="14" aria-label="Center" ...></svg></x-slot:icon>
    </x-pajak::segmented-option>
</x-pajak::segmented>
```

### Full width

```blade
<x-pajak::segmented :full="true" :variant="SegmentedVariant::Brand">
    <x-pajak::segmented-option label="Monthly" :active="true" value="monthly" />
    <x-pajak::segmented-option label="Yearly" value="yearly" />
</x-pajak::segmented>
```

### Disabled option

```blade
<x-pajak::segmented>
    <x-pajak::segmented-option label="Personal" :active="true" />
    <x-pajak::segmented-option label="Joint" />
    <x-pajak::segmented-option label="Business" :disabled="true" />
</x-pajak::segmented>
```

---

## JS API

```ts
import { PajakSegmented } from 'vendor/pajak/ui/js/segmented/segmented';

PajakSegmented.initAll();   // wire all [data-pajak-segmented] containers
PajakSegmented.init(el);    // wire a single container element
```

### Change event

When an option is clicked, a `pajak-segmented:change` event bubbles up from the container:

```js
el.addEventListener('pajak-segmented:change', (e) => {
    console.log(e.detail.value);  // the data-value attribute, or null if unset
    console.log(e.detail.index);  // zero-based index of the selected option
});
```
