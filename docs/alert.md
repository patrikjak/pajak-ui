# Alert

Contextual feedback messages for user actions or system state.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the alert-only bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/alert-standalone.css') }}">
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
// Alert only
@use 'vendor/pajak/ui/css/alert/alert-standalone';

// Or just the partial (when variables are already imported)
@use 'vendor/pajak/ui/css/alert/alert';
```

---

## Usage

```blade
{{-- Default info banner --}}
<x-pajak::alert>We auto-saved your progress 2 minutes ago.</x-pajak::alert>

{{-- With title --}}
<x-pajak::alert type="info" title="Have your PIT-11 ready">
    It contains all figures you need to fill in the income section.
</x-pajak::alert>

{{-- Types --}}
<x-pajak::alert type="success" title="Return submitted">Confirmation sent.</x-pajak::alert>
<x-pajak::alert type="warning" title="Deadline approaching">26 days remaining.</x-pajak::alert>
<x-pajak::alert type="error" title="Missing document">Upload PIT-11 before submitting.</x-pajak::alert>

{{-- Dismissible --}}
<x-pajak::alert :dismissible="true">You can dismiss this alert.</x-pajak::alert>

{{-- Outline variant --}}
<x-pajak::alert type="warning" variant="outline" title="Deadline approaching">
    26 days remaining until April 30, 2026.
</x-pajak::alert>

{{-- Inline variant — compact, no title --}}
<x-pajak::alert type="error" variant="inline">PESEL must be exactly 11 digits.</x-pajak::alert>
```

---

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `type` | `AlertType` | `AlertType::Info` | Colour and icon: info, success, warning, or error |
| `variant` | `AlertVariant` | `AlertVariant::Banner` | Layout variant |
| `title` | `?string` | `null` | Bold heading above the message; hidden in the inline variant |
| `dismissible` | `bool` | `false` | Show an ✕ close button |

### `AlertType` values

| Value | Appearance |
|-------|-----------|
| `info` | Blue tint background, dark blue text |
| `success` | Green tint background, dark green text |
| `warning` | Yellow tint background, dark amber text |
| `error` | Red tint background, dark red text |

### `AlertVariant` values

| Value | Appearance |
|-------|-----------|
| `banner` | Coloured background fill |
| `outline` | White background with a coloured border |
| `inline` | Compact padding, no title, smaller icon |

### Dismiss behaviour

The dismiss button renders an ✕ but ships without JavaScript. Wire it up yourself:

```js
document.querySelectorAll('.pajak-alert__close').forEach(btn => {
    btn.addEventListener('click', () => btn.closest('.pajak-alert').remove());
});
```
