# List

A grouped list of rows with optional leading, body, and trailing slots. Renders as a card-like container with dividers between rows.

## Usage

```blade
<x-pajak::list>
    <x-pajak::list-row>
        <x-slot:leading>
            <!-- icon tile, avatar, checkbox, etc. -->
        </x-slot:leading>
        <x-slot:title>Row title</x-slot:title>
        <x-slot:subtitle>Supporting text</x-slot:subtitle>
        <x-slot:trailing>
            <!-- badge, meta value, chevron, etc. -->
        </x-slot:trailing>
    </x-pajak::list-row>
</x-pajak::list>
```

## Components

### `<x-pajak::list>`

Container wrapper. No props — accepts only `$slot`.

### `<x-pajak::list-row>`

Individual row inside the list.

#### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `clickable` | `bool` | `false` | Adds hover background and pointer cursor |

#### Slots

| Slot | Required | Description |
|------|----------|-------------|
| `leading` | No | Left-side content — icon tile, avatar, checkbox |
| `title` | No | Primary label text |
| `subtitle` | No | Secondary/supporting text below the title |
| `trailing` | No | Right-side content — badge, meta value, toggle, chevron |
| `$slot` | No | Free-form body content (rendered after title/subtitle) |

`title` and `subtitle` can be passed as attributes instead of named slots when the value is a plain string:

```blade
{{-- attribute form (plain text only) --}}
<x-pajak::list-row title="PIT-11_2025_Acme.pdf" subtitle="248 KB · Mar 12, 2026" />

{{-- slot form (use when the value contains HTML) --}}
<x-pajak::list-row>
    <x-slot:title>PIT-11_2025_Acme.pdf</x-slot:title>
    <x-slot:subtitle>248 KB · <strong>Mar 12, 2026</strong></x-slot:subtitle>
</x-pajak::list-row>
```

## Examples

### Document rows with status badges

```blade
<x-pajak::list>
    <x-pajak::list-row :clickable="true">
        <x-slot:leading>
            <div class="icon-tile" style="background:#EBF0FB"><!-- icon --></div>
        </x-slot:leading>
        <x-slot:title>PIT-11_2025_Acme.pdf</x-slot:title>
        <x-slot:subtitle>248 KB · Mar 12, 2026</x-slot:subtitle>
        <x-slot:trailing>
            <x-pajak::badge color="{{ \Pajak\Ui\Common\Enums\BadgeColor::Success }}">Verified</x-pajak::badge>
        </x-slot:trailing>
    </x-pajak::list-row>
</x-pajak::list>
```

### Settings rows

```blade
<x-pajak::list>
    <x-pajak::list-row :clickable="true">
        <x-slot:title>Email</x-slot:title>
        <x-slot:subtitle>jan.kowalski@example.com</x-slot:subtitle>
        <x-slot:trailing>
            <svg class="chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </x-slot:trailing>
    </x-pajak::list-row>
</x-pajak::list>
```

## Asset inclusion

The list styles are included in the main bundle:

```html
<link rel="stylesheet" href="/vendor/pajak/ui/main.css">
```
