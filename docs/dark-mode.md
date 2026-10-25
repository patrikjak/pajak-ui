# Dark Mode

All components support dark mode via a `data-theme="dark"` attribute on any ancestor element (typically `<html>`).

## Activation

```html
{{-- Whole page --}}
<html data-theme="dark">

{{-- Scoped to a container --}}
<div data-theme="dark">
  <x-pajak-form::input name="email" label="Email" />
</div>
```

No additional CSS imports are required - the dark token overrides are included in the same bundle as the light theme.

## How it works

The package ships a `[data-theme="dark"]` CSS block in `variables.scss` that re-maps every semantic alias and palette token. All component styles consume those aliases exclusively, so they respond to dark mode automatically with no component-level changes.

### Token mapping

| Alias | Light | Dark |
|-------|-------|------|
| `--bg-page` | `#F7F9FF` | `#0B0F1A` |
| `--bg-surface` | `#FFFFFF` | `#151B2C` |
| `--bg-elevated` | `#FFFFFF` | `#1F2740` |
| `--bg-input` | `--bg-surface` | `#0B0F1A` |
| `--bg-primary` | `#5386E4` | `#5386E4` |
| `--border-default` | `#E2E8F4` | `rgba(255,255,255,0.08)` |
| `--border-strong` | `#C8D2E8` | `rgba(255,255,255,0.14)` |
| `--border-focus` | `#5386E4` | `#84A5E7` |
| `--fg-primary` | `#1A1F2E` | `#EEF2FB` |
| `--fg-secondary` | `#45566D` | `#A4B3D0` |
| `--fg-tertiary` | `#7F93B5` | `#7F93B5` |
| `--fg-disabled` | `#A4B3D0` | `#45566D` |
| `--color-success` | `#27AE60` | `#3DD68C` |
| `--color-warning` | `#F5A623` | `#F7B955` |
| `--color-error` | `#E53935` | `#FF6B68` |

### Input backgrounds

Form controls (`input`, `textarea`, `select`, `checkbox`, `radio`) use `--bg-input` rather than `--bg-surface`. In dark mode this resolves to the page background (`#0B0F1A`) so controls sit visually inset below the card surface - matching the design.

## Toggling at runtime

```js
// Enable dark mode
document.documentElement.setAttribute('data-theme', 'dark');

// Revert to light mode
document.documentElement.removeAttribute('data-theme');

// Toggle
const html = document.documentElement;
html.toggleAttribute('data-theme') ||
  html.setAttribute('data-theme', 'dark');
```

Or drive it from a toggle component:

```blade
<x-pajak-form::toggle
    name="theme"
    label="Dark mode"
    id="theme-toggle"
/>
```

```js
document.getElementById('theme-toggle').addEventListener('change', e => {
    document.documentElement.setAttribute(
        'data-theme',
        e.target.checked ? 'dark' : 'light'
    );
});
```

## Light / dark / system switcher

`PajakTheme` (`js/theme/theme`, also in the main bundle) is a small helper for a three-way light / dark / system preference: it persists the choice to `localStorage` (key `pajak-theme`), falls back to the OS `prefers-color-scheme` for `system` (and follows OS changes live), and sets `data-theme="light|dark"` on `<html>`.

```ts
import { PajakTheme } from 'vendor/pajak/ui/js/theme/theme';

PajakTheme.get();         // 'light' | 'dark' | 'system' (stored; default 'system')
PajakTheme.resolved();    // 'light' | 'dark' (what is actually applied)
PajakTheme.set('dark');   // persist + apply + sync any bound switchers
```

The helper is opt-in: loading the bundle changes nothing for a visitor who has never picked a theme (your own `data-theme` is left alone). Once a preference is stored it is applied as soon as the script loads; call `PajakTheme.apply()` yourself if first-time visitors should follow the OS setting. To avoid a flash of the wrong theme, also apply it inline in `<head>` before first paint:

```html
<script>
    (function () {
        try {
            var mode = localStorage.getItem('pajak-theme') || 'system';
            var dark = mode === 'dark' || (mode === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
        } catch (e) {}
    })();
</script>
```

Drive it from a [segmented control](segmented.md) by adding `data-pajak-theme-switch` and using `light` / `dark` / `system` as the option values - the control is synced to the stored mode on load and picking an option calls `PajakTheme.set()`:

```blade
<x-pajak::segmented data-pajak-theme-switch>
    <x-pajak::segmented-option label="Light" value="light" />
    <x-pajak::segmented-option label="Dark" value="dark" />
    <x-pajak::segmented-option label="System" value="system" />
</x-pajak::segmented>
```

**Event:** `pajak:theme` is dispatched on `document` with `detail: { mode, resolved }` whenever `set()` is called.

## Source import

When using the source import approach, the dark token block lives in `variables.scss`. Import it once - the individual component partials inherit the overrides automatically:

```scss
@use 'vendor/pajak/ui/css/variables';   // includes both :root and [data-theme="dark"]
@use 'vendor/pajak/ui/css/form/form-standalone';
@use 'vendor/pajak/ui/css/button/index';
```
