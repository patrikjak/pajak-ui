# Banner

A full-width, page-level notice with six tone variants, optional title, dismiss button, action slot, and progress bar.

## Asset inclusion

```html
<!-- Full bundle -->
<link rel="stylesheet" href="vendor/pajak/ui/main.css">
<script type="module">
    import { PajakBanner } from '/vendor/pajak/ui/main.js';
    PajakBanner.initAll();
</script>

<!-- Standalone -->
<link rel="stylesheet" href="vendor/pajak/ui/banner-standalone.css">
<script type="module">
    import { PajakBanner } from '/vendor/pajak/ui/banner.js';
    PajakBanner.initAll();
</script>
```

## Basic usage

```blade
<x-pajak::banner>
    We've prefilled your return from KAS.
    <a href="#">Review what we found.</a>
</x-pajak::banner>
```

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

## JS API

Call `initAll()` once after the DOM is ready. The dismiss animation fades out and slides the banner up before hiding it.

| Method | Description |
|--------|-------------|
| `PajakBanner.initAll()` | Attaches dismiss handlers to all `.pajak-banner` elements |
| `PajakBanner.init(el)` | Attaches dismiss handler to a single banner element |
| `PajakBanner.dismiss(el)` | Programmatically dismisses a banner with animation |
