# Breadcrumbs

A navigation breadcrumb trail built from a typed `BreadcrumbItem` array.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the breadcrumb-only bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/breadcrumb-standalone.css') }}">
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/breadcrumb/breadcrumb';
```

---

## Basic usage

```blade
<x-pajak::breadcrumbs :items="[
    \Pajak\Ui\Common\Dto\BreadcrumbItem::link('Returns', '/returns'),
    \Pajak\Ui\Common\Dto\BreadcrumbItem::link('2025', '/returns/2025'),
    \Pajak\Ui\Common\Dto\BreadcrumbItem::current('Donations'),
]" />
```

---

## BreadcrumbItem

```php
use Pajak\Ui\Common\Dto\BreadcrumbItem;

// Named constructors (recommended)
BreadcrumbItem::home('/')                       // home icon link, label = "Home"
BreadcrumbItem::link('Reports', '/reports')    // clickable link
BreadcrumbItem::current('Q1 2026')             // current page (no link, aria-current="page")

// Direct constructor
new BreadcrumbItem('Reports', '/reports', false)
```

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `label` | `string` | — | Display text (shown as screen-reader-only text when `isHome` is true) |
| `href` | `?string` | `null` | Link URL; `null` renders the item as the current page |
| `isHome` | `bool` | `false` | Renders a house icon instead of text |

---

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `items` | `iterable<int, BreadcrumbItem>` | `[]` | Ordered list of breadcrumb items |
| `variant` | `BreadcrumbVariant` | `BreadcrumbVariant::Default` | Visual style — `Default`, `Compact`, `Pill` |
| `separator` | `BreadcrumbSeparator` | `BreadcrumbSeparator::Chevron` | Separator style — `Chevron`, `Slash` (ignored for `Pill` variant) |

---

## Variants

### Default — chevron separator

```blade
<x-pajak::breadcrumbs :items="$items" />
```

### Slash separator

```blade
@use('Pajak\Ui\Common\Enums\BreadcrumbSeparator')

<x-pajak::breadcrumbs :items="$items" :separator="BreadcrumbSeparator::Slash" />
```

### Compact — for narrow surfaces, modals, side panels

```blade
@use('Pajak\Ui\Common\Enums\BreadcrumbVariant')

<x-pajak::breadcrumbs :items="$items" :variant="BreadcrumbVariant::Compact" />
```

### Pill — segmented inline style

Renders a `<nav>` instead of `<ul>`. Chevron separator is always used.

```blade
@use('Pajak\Ui\Common\Enums\BreadcrumbVariant')

<x-pajak::breadcrumbs :items="$items" :variant="BreadcrumbVariant::Pill" />
```

### With home icon

```blade
<x-pajak::breadcrumbs :items="[
    \Pajak\Ui\Common\Dto\BreadcrumbItem::home('/'),
    \Pajak\Ui\Common\Dto\BreadcrumbItem::link('Clients', '/clients'),
    \Pajak\Ui\Common\Dto\BreadcrumbItem::current('Acme Sp. z o.o.'),
]" />
```
