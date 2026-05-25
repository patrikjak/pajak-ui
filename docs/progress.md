# Progress

Linear determinate progress bar. Available as `<x-pajak::progress>`.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the progress-only bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/progress-standalone.css') }}">
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
// Progress only
@use 'vendor/pajak/ui/css/progress/progress-standalone';

// Or just the partial (when variables are already imported)
@use 'vendor/pajak/ui/css/progress/progress';
```

---

## Usage

```blade
{{-- Basic --}}
<x-pajak::progress :value="65" />

{{-- Sizes --}}
<x-pajak::progress :value="65" size="sm" />
<x-pajak::progress :value="65" size="md" />
<x-pajak::progress :value="65" size="lg" />

{{-- Colors --}}
<x-pajak::progress :value="100" color="success" />
<x-pajak::progress :value="30" color="warning" />
<x-pajak::progress :value="10" color="danger" />

{{-- Labeled with percentage value --}}
<x-pajak::progress :value="75" label="Income" :show-value="true" />
<x-pajak::progress :value="100" color="success" label="Personal details" :show-value="true" />
<x-pajak::progress :value="0" label="Review & submit" :show-value="true" />

{{-- Custom max --}}
<x-pajak::progress :value="7" :max="10" :show-value="true" />
```

---

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `value` | `int` | — | Current value (required) |
| `max` | `int` | `100` | Maximum value; percentage is computed as `value / max * 100` |
| `size` | `ProgressSize` | `ProgressSize::Md` | Bar height |
| `color` | `ProgressColor` | `ProgressColor::Primary` | Fill color |
| `label` | `?string` | `null` | Text label shown to the left of the bar |
| `showValue` | `bool` | `false` | Show computed percentage to the right of the bar |

When either `label` or `showValue` is set the bar is wrapped in a flex row (`pajak-progress-row`). Without either, only the bare `<div class="pajak-progress">` is rendered.

### `ProgressSize` values

| Value | Height |
|-------|--------|
| `sm` | 4px |
| `md` | 6px |
| `lg` | 10px |

### `ProgressColor` values

| Value | Color |
|-------|-------|
| `primary` | Blue (`color-primary-500`) |
| `success` | Green (`color-success`) |
| `warning` | Amber (`color-warning`) |
| `danger` | Red (`color-error`) |
