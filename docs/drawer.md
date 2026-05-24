# Drawer

A panel that slides in from a viewport edge — right, left, bottom, or top. Use for filter panels, navigation, detail views, and quick-action sheets. Built on the native `<dialog>` element.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the drawer-only bundles:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/drawer-standalone.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/drawer.js') }}"></script>
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/drawer/drawer';
```

```ts
import { PajakDrawer } from 'vendor/pajak/ui/js/drawer/drawer';
PajakDrawer.initAll();
```

---

## Usage

```blade
{{-- 1. Render the drawer somewhere in your page --}}
<x-pajak::drawer id="filters" title="Filter deductions">
    <x-slot:description>12 results across 4 categories</x-slot:description>
    <p>Filter content here.</p>
    <x-slot:footer>
        <x-pajak::button variant="ghost" data-pajak-drawer-close>Reset</x-pajak::button>
        <x-pajak::button>Apply (3)</x-pajak::button>
    </x-slot:footer>
</x-pajak::drawer>

{{-- 2. Trigger it from any element --}}
<button data-pajak-drawer-trigger="filters">Open filters</button>
```

---

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | `string` | required | Unique ID used by JS and trigger attributes |
| `side` | `DrawerSide` | `DrawerSide::Right` | Which edge the drawer slides from |
| `open` | `bool` | `false` | Renders `<dialog open>` server-side — for server-driven state |
| `dismissible` | `bool` | `true` | Shows close button; allows backdrop-click to close |

## Slots

| Slot | Required | Description |
|------|----------|-------------|
| `title` | No | Drawer heading (also passable as `title="..."` attribute) |
| `description` | No | Subtitle below the heading |
| `$slot` | No | Body content — scrollable |
| `footer` | No | Action buttons in the footer bar |

## Sides

```php
use Pajak\Ui\Common\Enums\Drawer\DrawerSide;

DrawerSide::Right   // 340px wide, slides from right (default)
DrawerSide::Left    // 280px wide, slides from left
DrawerSide::Bottom  // full width, max 80vh, slides from bottom — includes grabber
DrawerSide::Top     // full width, auto height, slides from top
```

---

## Opening and closing

### Trigger attribute (recommended)

```html
<button data-pajak-drawer-trigger="my-drawer">Open</button>
```

Call `PajakDrawer.initAll()` once on page load to wire all triggers, backdrop-clicks, and `data-pajak-drawer-close` buttons.

### Close button inside the drawer

Any element with `data-pajak-drawer-close` inside the drawer will close it when clicked. The built-in `×` button in the header uses this automatically when `dismissible="true"`.

```blade
<x-pajak::button variant="ghost" data-pajak-drawer-close>Cancel</x-pajak::button>
```

### JS API

```js
PajakDrawer.open('my-drawer')   // open programmatically
PajakDrawer.close('my-drawer')  // close programmatically
PajakDrawer.initAll()            // wire all triggers, close buttons, and backdrop-click
```

### Server-driven (`open` prop)

Pass `:open="true"` to render `<dialog open>` immediately. Note: this renders as a plain block element without a backdrop. For backdrop from page load, omit `open` and call `PajakDrawer.open('id')` on `DOMContentLoaded` instead.

---

## Examples

### Right — filter panel

```blade
<x-pajak::drawer id="filters" title="Filter deductions">
    <x-slot:description>12 results across 4 categories</x-slot:description>
    <!-- filter checkboxes -->
    <x-slot:footer>
        <x-pajak::button variant="ghost" data-pajak-drawer-close>Reset</x-pajak::button>
        <x-pajak::button>Apply (3)</x-pajak::button>
    </x-slot:footer>
</x-pajak::drawer>
```

### Left — navigation

```blade
<x-pajak::drawer id="nav" :side="DrawerSide::Left" title="PAJAK">
    <x-slot:description>Tax year 2025</x-slot:description>
    <!-- nav items -->
</x-pajak::drawer>
```

### Bottom — mobile quick actions

```blade
<x-pajak::drawer id="details" :side="DrawerSide::Bottom" title="Refund details">
    <x-slot:description>PIT-37 — 2025</x-slot:description>
    <!-- detail rows -->
    <x-slot:footer>
        <x-pajak::button variant="ghost" data-pajak-drawer-close>Edit</x-pajak::button>
        <x-pajak::button>Submit</x-pajak::button>
    </x-slot:footer>
</x-pajak::drawer>
```

### Non-dismissible

```blade
<x-pajak::drawer id="loading" :dismissible="false" title="Loading…" />
```
