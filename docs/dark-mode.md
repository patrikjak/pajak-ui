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

No additional CSS imports are required — the dark token overrides are included in the same bundle as the light theme.

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

Form controls (`input`, `textarea`, `select`, `checkbox`, `radio`) use `--bg-input` rather than `--bg-surface`. In dark mode this resolves to the page background (`#0B0F1A`) so controls sit visually inset below the card surface — matching the design.

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

## Source import

When using the source import approach, the dark token block lives in `variables.scss`. Import it once — the individual component partials inherit the overrides automatically:

```scss
@use 'vendor/pajak/ui/css/variables';   // includes both :root and [data-theme="dark"]
@use 'vendor/pajak/ui/css/form/form-standalone';
@use 'vendor/pajak/ui/css/button/index';
```
