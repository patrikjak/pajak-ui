# Divider

A flexible separator component covering horizontal rules, vertical inline dividers, and labeled "OR"-style separators.

## Usage

```blade
{{-- Default horizontal --}}
<x-pajak::divider />

{{-- Strong horizontal --}}
<x-pajak::divider strength="strong" />

{{-- Subtle horizontal --}}
<x-pajak::divider strength="subtle" />

{{-- Dashed --}}
<x-pajak::divider style="dashed" />

{{-- Dotted --}}
<x-pajak::divider style="dotted" />

{{-- Vertical (inline, between metadata items) --}}
<span>Invoice #142</span>
<x-pajak::divider orientation="vertical" />
<span>Acme Sp. z o.o.</span>

{{-- Labeled --}}
<x-pajak::divider :labeled="true">or</x-pajak::divider>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `orientation` | `DividerOrientation` | `DividerOrientation::Horizontal` | Horizontal (`<hr>`) or vertical (`<span>`). Ignored when `labeled=true`. |
| `strength` | `DividerStrength` | `DividerStrength::Default` | Line colour for solid horizontal dividers. No effect on dashed/dotted. |
| `style` | `DividerStyle` | `DividerStyle::Solid` | Solid, dashed, or dotted. Horizontal only. |
| `labeled` | `bool` | `false` | Renders a flex `<div>` with lines and the slot text. Overrides `orientation`, `strength`, and `style`. |

### `DividerOrientation` values

| Value | Element | Use case |
|-------|---------|----------|
| `horizontal` | `<hr>` | Section separators, list row separators |
| `vertical` | `<span>` | Inline metadata separators |

### `DividerStrength` values

| Value | Colour |
|-------|--------|
| `default` | `color-neutral-100` (`#E2E8F4`) |
| `strong` | `color-neutral-200` (`#C8D2E8`) |
| `subtle` | `color-neutral-50` (`#EEF2FB`) |

### `DividerStyle` values

| Value | Appearance |
|-------|-----------|
| `solid` | 1px solid line |
| `dashed` | 1px dashed line (always `color-neutral-200`) |
| `dotted` | 1.5px dotted line (always `color-neutral-200`) |

## Asset Inclusion

### Standalone (divider only)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/divider-standalone.css') }}">
```

### Full bundle

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
```

### SCSS source import

```scss
// Divider only
@use 'vendor/pajak/ui/css/divider/divider-standalone';

// Or just the partial (when variables are already imported)
@use 'vendor/pajak/ui/css/divider/divider';
```
