# Error Page Component

A full-viewport error page component. Displays a dot-grid background, a large watermark, a coloured icon, a status pill, a title, a description, and optional action buttons. Supports five built-in HTTP error codes with matching colours and copy.

## Asset inclusion

**Full bundle** (already included when using `main.css` / `main.js`):
```html
<link rel="stylesheet" href="/vendor/pajak/ui/main.css">
```

**Standalone bundle** (error page only):
```html
<link rel="stylesheet" href="/vendor/pajak/ui/error-page-standalone.css">
```

## Basic usage

```blade
{{-- 404 with all defaults --}}
<x-pajak::error-page :code="404" />

{{-- 500 with custom copy --}}
<x-pajak::error-page
    :code="500"
    title="We'll be right back"
    description="Our engineers are on it. Please try again in a few minutes."
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `code` | `int` | `404` | HTTP status code. Supported: `401`, `403`, `404`, `500`, `503`. Any other code renders a neutral variant. |
| `title` | `?string` | `null` | Overrides the default title for that code. |
| `description` | `?string` | `null` | Overrides the default description for that code. |

## Slots

| Slot | Required | Description |
|------|----------|-------------|
| `brand` | No | Brand corner content (top-left). Pass your logo mark and app name. Hidden when omitted. |
| `actions` | No | Action buttons rendered below the description. Hidden when omitted. |
| `footer` | No | Support footer text (bottom-centre). Hidden when omitted. |

## Default copy per code

| Code | Title | Icon colour |
|------|-------|-------------|
| 404 | Page not found | Blue |
| 500 | Something went wrong | Red |
| 403 | Access denied | Amber |
| 401 | Session expired | Neutral |
| 503 | Back very shortly | Blue + maintenance stripe |

## Full example with all slots

```blade
<x-pajak::error-page :code="404">
    <x-slot:brand>
        <div class="my-brand-mark">A</div>
        <span class="my-brand-name">Acme</span>
    </x-slot:brand>

    <x-slot:actions>
        <a href="/" class="btn btn-primary">Go to dashboard</a>
        <button type="button" onclick="history.back()" class="btn btn-ghost">Go back</button>
    </x-slot:actions>

    <x-slot:footer>
        Having trouble? <a href="mailto:support@example.com">Contact support</a>
    </x-slot:footer>
</x-pajak::error-page>
```

## Laravel error pages

To use this component as Laravel's error pages, publish the views and create `resources/views/errors/404.blade.php`:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $exception->getMessage() ?: 'Page not found' }}</title>
    <link rel="stylesheet" href="/vendor/pajak/ui/error-page-standalone.css">
</head>
<body>
    <x-pajak::error-page :code="404">
        <x-slot:footer>
            Having trouble? <a href="mailto:support@example.com">Contact support</a>
        </x-slot:footer>
    </x-pajak::error-page>
</body>
</html>
```

Repeat for `500.blade.php`, `403.blade.php`, `401.blade.php`, and `503.blade.php` with the matching `:code` value.
