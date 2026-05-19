# Spinner

Loading indicators in three styles: arc (primary), dots, and indeterminate bar.

## Usage

```blade
{{-- Default — arc, medium, primary --}}
<x-pajak::spinner />

{{-- Arc spinner sizes --}}
<x-pajak::spinner size="xs" />
<x-pajak::spinner size="sm" />
<x-pajak::spinner size="md" />
<x-pajak::spinner size="lg" />
<x-pajak::spinner size="xl" />
<x-pajak::spinner size="xxl" />

{{-- Arc spinner colors --}}
<x-pajak::spinner color="neutral" />
<x-pajak::spinner color="muted" />
<x-pajak::spinner color="white" />
<x-pajak::spinner color="success" />
<x-pajak::spinner color="danger" />

{{-- Dot pulse --}}
<x-pajak::spinner type="dots" />
<x-pajak::spinner type="dots" color="neutral" />

{{-- Indeterminate bar (full-width of its container) --}}
<x-pajak::spinner type="bar" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `type` | `SpinnerType` | `SpinnerType::Arc` | Spinner style |
| `size` | `SpinnerSize` | `SpinnerSize::Md` | Size; arc only — no effect on dots or bar |
| `color` | `SpinnerColor` | `SpinnerColor::Primary` | Color; no effect on bar |

### `SpinnerType` values

| Value | Description |
|-------|-------------|
| `arc` | Rotating SVG arc — primary spinner across the system |
| `dots` | Three pulsing dots — softer, in-content loading |
| `bar` | Indeterminate sliding bar — long-running tasks |

### `SpinnerSize` values (arc only)

| Value | Pixel size |
|-------|-----------|
| `xs` | 12px |
| `sm` | 16px |
| `md` | 20px |
| `lg` | 24px |
| `xl` | 32px |
| `xxl` | 48px |

### `SpinnerColor` values

| Value | Color |
|-------|-------|
| `primary` | Blue (`color-primary-500`) |
| `neutral` | Mid grey (`color-neutral-500`) |
| `muted` | Light grey (`color-neutral-300`) |
| `white` | White — for use on dark/primary surfaces |
| `success` | Green (`color-success`) |
| `danger` | Red (`color-error`) |

## Asset Inclusion

### Standalone (spinner only)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/spinner-standalone.css') }}">
```

### Full bundle

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
```

### SCSS source import

```scss
// Spinner only
@use 'vendor/pajak/ui/css/spinner/spinner-standalone';

// Or just the partial (when variables are already imported)
@use 'vendor/pajak/ui/css/spinner/spinner';
```
