# Card

A surface container for presenting grouped content. Supports an optional kicker label, title, body, and footer slot. Lifts on hover via a CSS transition.

> All components support dark mode - see [dark-mode.md](dark-mode.md).

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

## Static (non-interactive) usage

For a card that isn't a clickable dashboard tile - e.g. a stacked settings/profile section - disable the hover-lift and use the wider title spacing meant for a heading-then-fields layout instead of a tight title-then-value stack.

```blade
<x-pajak::card :interactive="false">
    <x-slot:title>Personal Details</x-slot:title>
    Update your name and contact information.
</x-pajak::card>
```

---

## Icon header variant

Provide an `icon` slot to get the settings-style layout: a 34 × 34 coloured icon badge next to the title and `subtitle`, a divider, the body, and an optional right-aligned footer for actions. The badge colour is set with a `variant` attribute on the slot: `primary` (default), `green` or `amber`.

```blade
<x-pajak::card :interactive="false">
    <x-slot:icon variant="primary">
        <x-heroicon-o-user />
    </x-slot:icon>
    <x-slot:title>Profile Information</x-slot:title>
    <x-slot:subtitle>Your name and contact details</x-slot:subtitle>

    …body…

    <x-slot:footer>
        <x-pajak::button>Save</x-pajak::button>
    </x-slot:footer>
</x-pajak::card>
```

`kicker` is not rendered in this layout. Pass `:interactive="false"` for a stacked settings card without the hover-lift.

---

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `CardVariant` | `CardVariant::Default` | `Default` (white surface) or `Accent` (primary fill) |
| `interactive` | `bool` | `true` | When `false`, removes the hover-lift and widens the title's bottom margin, for non-clickable, non-dashboard cards |

## Slots

| Slot | Description |
|------|-------------|
| `$kicker` | Small uppercase label above the title (e.g. a category or metric name) |
| `$title` | Card heading |
| `$subtitle` | Muted line below the title (beside the icon in the icon-header layout) |
| `$icon` | Coloured icon badge - switches the card to the icon-header layout. Takes a `variant` attribute: `primary` (default) / `green` / `amber` (`CardIconVariant`) |
| `$slot` | Main body content |
| `$footer` | Area below the body, offset with a top margin (e.g. badges, actions) |

All named slots are optional.
