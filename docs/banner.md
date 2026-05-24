# Banner

A full-width, page-level notice with six tone variants, optional title, dismiss button, action slot, and progress bar.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the banner-only bundles:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/banner-standalone.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/banner.js') }}"></script>
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/banner/banner';
```

```ts
import { PajakBanner } from 'vendor/pajak/ui/js/banner/banner';
PajakBanner.initAll();
```

---

## Basic usage

```blade
<x-pajak::banner>
    We've prefilled your return from KAS.
    <a href="#">Review what we found.</a>
</x-pajak::banner>
```

---

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `type` | `BannerType` | `BannerType::Info` | Tone variant |
| `title` | `?string` | `null` | Bold heading above the slot content |
| `dismissible` | `bool` | `false` | Renders a close button; JS handles the hide animation |
| `top` | `bool` | `false` | Flush layout for use above the page navigation |
| `progress` | `?int` | `null` | Progress bar fill percentage (0–100); clamped automatically |

### Named slots

| Slot | Description |
|------|-------------|
| `$slot` (default) | Main content — text, links, HTML |
| `$actions` | Optional action buttons rendered after the body |

### Types

| Value | Description |
|-------|-------------|
| `BannerType::Info` | Blue tint — soft informational notice |
| `BannerType::Success` | Green tint — confirmation or positive outcome |
| `BannerType::Warning` | Yellow tint — deadline or cautionary notice |
| `BannerType::Error` | Red tint — blocking error or failure |
| `BannerType::Neutral` | Grey tint — ongoing process or neutral state |
| `BannerType::Promo` | Primary gradient — branded/promotional message |

---

## Examples

### With title and dismiss

```blade
<x-pajak::banner :type="BannerType::Info" title="We've prefilled your return" :dismissible="true">
    3 documents and 2 income sources were imported automatically.
</x-pajak::banner>
```

### With action buttons

```blade
<x-pajak::banner :type="BannerType::Warning" title="Your filing deadline is in 6 days">
    Submit by <strong>30 April 2026</strong> to avoid a late penalty.
    <x-slot name="actions">
        <button type="button" class="...">Remind me later</button>
        <button type="button" class="...">Continue filing</button>
    </x-slot>
</x-pajak::banner>
```

### With progress bar

```blade
<x-pajak::banner :type="BannerType::Neutral" :progress="62">
    5 of 8 documents synced. This usually takes about a minute.
</x-pajak::banner>
```

### Top / system banner

```blade
{{-- Rendered above the navigation, flush to the page edge --}}
<x-pajak::banner :top="true">
    Scheduled maintenance: Submissions to KAS will pause from <strong>Sat 11 PM</strong> to <strong>Sun 2 AM</strong>.
</x-pajak::banner>
```

---

## JS API

```ts
import { PajakBanner } from 'vendor/pajak/ui/js/banner/banner';

// Wire all dismiss handlers on the page
PajakBanner.initAll();

// Wire a single banner element
PajakBanner.init(el);

// Programmatically dismiss with animation
PajakBanner.dismiss(el);
```

Call `initAll()` once after the DOM is ready. The dismiss animation fades out and slides the banner up before hiding it.
