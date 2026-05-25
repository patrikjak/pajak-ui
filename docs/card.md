# Card

A surface container for presenting grouped content. Supports an optional kicker label, title, body, and footer slot. Lifts on hover via a CSS transition.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the card-only bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/card-standalone.css') }}">
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/card/card';
```

---

## Basic usage

```blade
<x-pajak::card>
    Your content here.
</x-pajak::card>
```

---

## With slots

```blade
<x-pajak::card>
    <x-slot:kicker>Refund</x-slot:kicker>
    <x-slot:title>Home Office</x-slot:title>
    You may qualify for up to PLN 3,600 annually.
    <x-slot:footer>
        <span>New</span>
    </x-slot:footer>
</x-pajak::card>
```

---

## Accent variant

Renders with a primary-colour background. Text inside `$kicker` and `$title` slots is automatically inverted to white.

```blade
<x-pajak::card :variant="\Pajak\Ui\Common\Enums\Card\CardVariant::Accent">
    <x-slot:title>Filing Status</x-slot:title>
    Individual taxpayer · 2025
    <x-slot:footer>On track</x-slot:footer>
</x-pajak::card>
```

---

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `CardVariant` | `CardVariant::Default` | `Default` (white surface) or `Accent` (primary fill) |

## Slots

| Slot | Description |
|------|-------------|
| `$kicker` | Small uppercase label above the title (e.g. a category or metric name) |
| `$title` | Card heading |
| `$slot` | Main body content |
| `$footer` | Area below the body, offset with a top margin (e.g. badges, actions) |

All named slots are optional.
