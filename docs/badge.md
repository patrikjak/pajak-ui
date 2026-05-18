# Badge

Small inline label for status, categories, and counts.

## Usage

```blade
<x-pajak::badge>Default</x-pajak::badge>

<x-pajak::badge color="success" :dot="true">Complete</x-pajak::badge>

<x-pajak::badge color="warning" size="lg">In Progress</x-pajak::badge>

<x-pajak::badge color="error" :outline="true">Overdue</x-pajak::badge>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `color` | `BadgeColor` | `BadgeColor::Primary` | Color variant |
| `size` | `Size` | `Size::Md` | Badge size |
| `outline` | `bool` | `false` | Transparent background with colored border |
| `dot` | `bool` | `false` | Show a small dot indicator before the label text |

### `BadgeColor` values

| Value | Appearance |
|-------|-----------|
| `primary` | Solid blue (primary-500 background, white text) |
| `success` | Green tint background, dark green text |
| `warning` | Yellow tint background, dark amber text |
| `error` | Red tint background, dark red text |
| `info` | Blue tint background, dark blue text |
| `neutral` | Grey tint background, mid grey text |

### `Size` values

| Value | Font size | Padding |
|-------|-----------|---------|
| `sm` | 9px | 2px 7px |
| `md` | 11px | 3px 10px |
| `lg` | 13px | 5px 14px |

## Asset Inclusion

### Standalone (badge only)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/badge-standalone.css') }}">
```

### Full bundle

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
```

### SCSS source import

```scss
// Badge only
@use 'vendor/pajak/ui/css/badge/badge-standalone';

// Or just the partial (when variables are already imported)
@use 'vendor/pajak/ui/css/badge/badge';
```
