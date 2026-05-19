# Skeleton

Shimmer placeholder shown while content is loading. Available as `<x-pajak::skeleton>`.

## Usage

```blade
{{-- Line (default) --}}
<x-pajak::skeleton style="width: 60%" />

{{-- Shape variants --}}
<x-pajak::skeleton shape="line-lg" style="width: 40%" />
<x-pajak::skeleton shape="line-sm" style="width: 65%" />
<x-pajak::skeleton shape="circle" style="width: 40px; height: 40px;" />
<x-pajak::skeleton shape="rect" style="width: 100%; height: 120px;" />
<x-pajak::skeleton shape="pill" style="width: 64px;" />
```

Width and height are always set via the `style` attribute (or a utility class) — the component does not accept `width`/`height` props, since skeleton dimensions are always context-specific.

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `shape` | `SkeletonShape` | `SkeletonShape::Line` | Shape and border-radius of the placeholder |

### `SkeletonShape` values

| Value | Height | Border-radius | Use case |
|-------|--------|---------------|----------|
| `line` | 12px | 6px | Body text line |
| `line-sm` | 9px | 5px | Secondary / caption text |
| `line-lg` | 16px | 6px | Heading or subheading |
| `circle` | — | full | Avatar, icon |
| `rect` | — | 6px | Image, card thumbnail, bar chart column |
| `pill` | 22px | full | Badge, tag, small button |

`circle` and `rect` have no intrinsic height — set both `width` and `height` via `style`.

## Asset Inclusion

### Standalone (skeleton only)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/skeleton-standalone.css') }}">
```

### Full bundle

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
```

### SCSS source import

```scss
// Skeleton only
@use 'vendor/pajak/ui/css/skeleton/skeleton-standalone';

// Or just the partial (when variables are already imported)
@use 'vendor/pajak/ui/css/skeleton/skeleton';
```
