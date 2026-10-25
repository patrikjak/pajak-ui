# Tabs

Tab navigation component with four layout variants. Built on semantic `role="tablist"` / `role="tab"` markup. JavaScript handles click-to-activate; panel visibility is managed by the consumer.

> All components support dark mode - see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the tabs-only bundles:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/tabs-standalone.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/tabs.js') }}"></script>
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/tabs/tabs';
```

```ts
import { PajakTabs } from 'vendor/pajak/ui/js/tabs/tabs';
PajakTabs.initAll();
```

---

## Basic usage

```blade
<x-pajak::tabs>
    <x-pajak::tab label="Overview" :active="true" />
    <x-pajak::tab label="Documents" :count="12" />
    <x-pajak::tab label="Activity" />
    <x-pajak::tab label="Archive" :disabled="true" />
</x-pajak::tabs>
```

---

## Variants

### Underline (default)

Horizontal tabs with a bottom-border active indicator. Suitable for primary page navigation.

```blade
<x-pajak::tabs>
    <x-pajak::tab label="Overview" :active="true" />
    <x-pajak::tab label="Documents" />
</x-pajak::tabs>
```

### Pill

Rounded pill switcher on a tinted background. Suitable for view/filter switching.

```blade
<x-pajak::tabs :variant="\Pajak\Ui\Common\Enums\Tabs\TabsVariant::Pill">
    <x-pajak::tab label="All" :active="true" />
    <x-pajak::tab label="In progress" />
    <x-pajak::tab label="Submitted" />
</x-pajak::tabs>
```

### Segmented

Square-cornered segment control. Suitable for mode toggles and toolbar-style selectors. Supports icons.

```blade
<x-pajak::tabs :variant="\Pajak\Ui\Common\Enums\Tabs\TabsVariant::Segmented">
    <x-pajak::tab label="Cards" :active="true">
        <x-slot:icon>
            <svg width="13" height="13" ...></svg>
        </x-slot:icon>
    </x-pajak::tab>
    <x-pajak::tab label="List" />
</x-pajak::tabs>
```

### Vertical

Vertical stack with a left indicator bar on the active tab. Wrap alongside a panel in a `.pajak-tabs__layout` div.

```blade
<div class="pajak-tabs__layout">
    <x-pajak::tabs :variant="\Pajak\Ui\Common\Enums\Tabs\TabsVariant::Vertical">
        <x-pajak::tab label="Profile" />
        <x-pajak::tab label="Settings" :active="true" :count="2" />
        <x-pajak::tab label="Security" />
    </x-pajak::tabs>

    <div class="your-panel">
        <!-- panel content -->
    </div>
</div>
```

---

## Tab panels

Give each tab a `controls` id and render the content in an `<x-pajak::tab-panel>` with that id. The active tab's panel is visible; the others get the `hidden` attribute and are toggled by the JS. This makes every variant - including `Vertical` - usable as a real tabbed screen.

```blade
<div class="pajak-tabs__layout">
    <x-pajak::tabs :variant="\Pajak\Ui\Common\Enums\Tabs\TabsVariant::Vertical">
        <x-pajak::tab label="Profile" controls="panel-profile" :active="true" />
        <x-pajak::tab label="Security" controls="panel-security" />
    </x-pajak::tabs>

    <x-pajak::tab-panel id="panel-profile" :active="true">Profile settings…</x-pajak::tab-panel>
    <x-pajak::tab-panel id="panel-security">Security settings…</x-pajak::tab-panel>
</div>
```

`<x-pajak::tab-panel>` props: `id` (required), `active` (`false`) - renders `role="tabpanel"` and `hidden` unless active.

---

## `<x-pajak::tabs>` props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `TabsVariant` | `TabsVariant::Underline` | Visual variant |

## `<x-pajak::tab>` props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string` | - | Tab label text |
| `active` | `bool` | `false` | Whether this tab is selected |
| `disabled` | `bool` | `false` | Disables interaction |
| `controls` | `string\|null` | `null` | Id of the panel this tab shows (`aria-controls`); the JS toggles that panel's `hidden` attribute |
| `count` | `int\|null` | `null` | Optional count badge |
| `$icon` | slot | - | Optional SVG icon rendered before the label |

---

## Keyboard navigation

Tab groups follow the WAI-ARIA tablist pattern. Only the active tab is in the tab order (`tabindex="0"`); all others have `tabindex="-1"` (roving tabindex).

| Key | Action |
|-----|--------|
| `Tab` | Move focus into / out of the tab list |
| `→` / `↓` | Focus the next enabled tab (wraps to first) |
| `←` / `↑` | Focus the previous enabled tab (wraps to last) |
| `Home` | Focus the first enabled tab |
| `End` | Focus the last enabled tab |

Selecting a tab via arrow key activates it immediately (automatic selection mode).

---

## JS API

```ts
import { PajakTabs } from 'vendor/pajak/ui/js/tabs/tabs';

// Initialise all [data-pajak-tabs] elements on the page
PajakTabs.initAll();

// Initialise a specific element
PajakTabs.init(document.querySelector('[data-pajak-tabs]'));
```

Tabs initialise automatically once the DOM is ready. For dynamically injected tab groups, call `PajakTabs.initAll(container)` (or `PajakTabs.init(el)` on the injected wrapper element).

The JS toggles `aria-selected` on tab buttons when clicked and, for tabs that declare `controls`, shows the matching panel and hides the others. Tabs without `controls` are left alone, so apps that manage their own panels (Alpine.js, Livewire) keep working - react to the `aria-selected` change or to the `pajak-tabs:change` event.

**Event:** `pajak-tabs:change` bubbles from the `[data-pajak-tabs]` element when the selected tab changes, with `detail: { index: number, controls: string | null }`.
