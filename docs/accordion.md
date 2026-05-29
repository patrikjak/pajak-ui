# Accordion

Composable expandable/collapsible content sections with four visual variants and two expand modes.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the accordion-only bundles:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/accordion-standalone.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/accordion.js') }}"></script>
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/accordion/accordion';
```

```ts
import { PajakAccordion } from 'vendor/pajak/ui/js/accordion/accordion';
PajakAccordion.initAll();
```

---

## Basic usage

```blade
<x-pajak::accordion>
    <x-pajak::accordion-item title="Personal information" subtitle="Your name and address" :open="true">
        <p>Jan Kowalski · NIP 524-1234-567</p>
    </x-pajak::accordion-item>
    <x-pajak::accordion-item title="Income sources" badge="3 added">
        <p>Salary, freelance, rental income.</p>
    </x-pajak::accordion-item>
</x-pajak::accordion>
```

---

## Accordion props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `AccordionVariant` | `AccordionVariant::Default` | Visual style |
| `mode` | `AccordionMode` | `AccordionMode::Single` | `Single` closes siblings on open; `Multi` allows multiple open |

### Variants

| Value | Description |
|-------|-------------|
| `AccordionVariant::Default` | White card with border, shadow, and dividers |
| `AccordionVariant::Flush` | Borderless, sits inside a parent surface |
| `AccordionVariant::Faq` | Larger spacing, plus/minus CSS icon, suited for FAQs |
| `AccordionVariant::Filled` | Each item is its own card; header tints on open |

---

## AccordionItem props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `string` | — | Header label |
| `subtitle` | `?string` | `null` | Secondary line below the title |
| `badge` | `?string` | `null` | Count/status pill rendered after the title stack |
| `open` | `bool` | `false` | Whether the item starts expanded |

### Named slots

| Slot | Description |
|------|-------------|
| `$slot` (default) | Panel content — arbitrary HTML |
| `$icon` | Optional leading icon (SVG); renders in a coloured icon box |

### With leading icon

```blade
<x-pajak::accordion-item title="Documents" badge="12">
    <x-slot name="icon">
        <svg width="16" height="16" ...>...</svg>
    </x-slot>
    <p>PIT-11_acme_2025.pdf</p>
</x-pajak::accordion-item>
```

---

## Variants example

```blade
@use('Pajak\Ui\Common\Enums\AccordionVariant')
@use('Pajak\Ui\Common\Enums\AccordionMode')

{{-- FAQ, multi-expand --}}
<x-pajak::accordion :variant="AccordionVariant::Faq" :mode="AccordionMode::Multi">
    <x-pajak::accordion-item title="When is my tax return due?" :open="true">
        For most individuals the PIT return is due by <strong>30 April</strong>.
    </x-pajak::accordion-item>
    <x-pajak::accordion-item title="Can I file jointly with my spouse?">
        Yes — joint filing is supported for married couples.
    </x-pajak::accordion-item>
</x-pajak::accordion>
```

---

## Keyboard navigation

Accordion headers are standard `<button>` elements. In addition to click activation, the following keys navigate between headers:

| Key | Action |
|-----|--------|
| `Enter` / `Space` | Toggle the focused accordion item |
| `↓` | Move focus to the next header (wraps to first) |
| `↑` | Move focus to the previous header (wraps to last) |
| `Home` | Move focus to the first header |
| `End` | Move focus to the last header |

---

## JS API

```ts
import { PajakAccordion } from 'vendor/pajak/ui/js/accordion/accordion';

// Wire all [data-pajak-accordion] elements on the page
PajakAccordion.initAll();

// Wire a single accordion element
PajakAccordion.init(el);
```

Call `initAll()` after the DOM is ready, or after dynamically inserting accordion markup.
