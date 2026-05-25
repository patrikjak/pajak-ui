# Detail Component

A key-value display component for showing structured data in a styled card. Available as `<x-pajak::detail>` (outer shell) and `<x-pajak::detail-row>` (each row).

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
```

Or use the detail-only bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/detail-standalone.css') }}">
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/detail/detail-standalone';
```

No JavaScript required for this component.

---

## Detail (outer shell)

```blade
{{-- Basic (no header) --}}
<x-pajak::detail>
    <x-pajak::detail-row key="Name">Jan Kowalski</x-pajak::detail-row>
    <x-pajak::detail-row key="Email">jan.kowalski@example.com</x-pajak::detail-row>
    <x-pajak::detail-row key="Status">Active</x-pajak::detail-row>
</x-pajak::detail>

{{-- With header title and edit action --}}
<x-pajak::detail title="Taxpayer profile" action-label="Edit" action-href="/profile/edit">
    <x-pajak::detail-row key="Full name">Jan Kowalski</x-pajak::detail-row>
    <x-pajak::detail-row key="Tax office">US Warszawa-Bemowo</x-pajak::detail-row>
</x-pajak::detail>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `string` | `'default'` | `default` \| `compact` \| `grid-2` \| `flush` |
| `title` | `string\|null` | `null` | Header title text; omit to suppress the header entirely |
| `action-label` | `string\|null` | `null` | Header action link label (e.g. `'Edit'`) |
| `action-href` | `string\|null` | `null` | URL for the header action link; defaults to `#` when `action-label` is set |

Additional HTML attributes are forwarded to the outer `<div>`.

---

## DetailRow

```blade
{{-- Plain text --}}
<x-pajak::detail-row key="Name">Jan Kowalski</x-pajak::detail-row>

{{-- Monospace value --}}
<x-pajak::detail-row key="PESEL" :mono="true">90010112345</x-pajak::detail-row>

{{-- Muted / empty state --}}
<x-pajak::detail-row key="Notes" :muted="true">No notes added</x-pajak::detail-row>

{{-- Link inside value --}}
<x-pajak::detail-row key="Document">
    <a href="/download/pit-11">PIT-11_2025_Acme.pdf</a>
</x-pajak::detail-row>

{{-- Copyable value using the copy-button component --}}
<x-pajak::detail-row key="Reference no." :copyable="true">
    <x-pajak::copy-button value="PIT-2025-00184736">
        <span class="pajak-detail__val--mono">PIT-2025-00184736</span>
    </x-pajak::copy-button>
</x-pajak::detail-row>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `key` | `string` | — | Label shown in the left column |
| `mono` | `bool` | `false` | Renders the value in a monospace font |
| `muted` | `bool` | `false` | Renders the value in muted/italic style |
| `copyable` | `bool` | `false` | Adds hover highlight for the row; pair with `<x-pajak::copy-button>` in the slot |

**Slot:** `$slot` — the value cell content. Accepts plain text, HTML, `<a>` tags, badge components, `<x-pajak::copy-button>`, or any inline content.

---

## Variants

### Default

Standard card with white background, border, and shadow.

```blade
<x-pajak::detail>
    <x-pajak::detail-row key="Name">Jan Kowalski</x-pajak::detail-row>
</x-pajak::detail>
```

### Compact

Tighter padding and smaller font — useful for dense UIs or sidebars.

```blade
<x-pajak::detail variant="compact" title="Employer">
    <x-pajak::detail-row key="Company">Acme Sp. z o.o.</x-pajak::detail-row>
    <x-pajak::detail-row key="NIP" :mono="true">123-456-78-90</x-pajak::detail-row>
</x-pajak::detail>
```

### Grid-2

Two-column layout. Rows go inside a `<x-slot:body>` named slot so the CSS grid can target the row container.

```blade
<x-pajak::detail variant="grid-2" title="Personal details" action-label="Edit" action-href="/edit">
    <x-slot:body>
        <x-pajak::detail-row key="First name">Jan</x-pajak::detail-row>
        <x-pajak::detail-row key="Last name">Kowalski</x-pajak::detail-row>
        <x-pajak::detail-row key="Birth date">01 Jan 1990</x-pajak::detail-row>
        <x-pajak::detail-row key="Citizenship">Polish</x-pajak::detail-row>
        <x-pajak::detail-row key="Phone">+48 600 123 456</x-pajak::detail-row>
        <x-pajak::detail-row key="Email">jan.kowalski@example.com</x-pajak::detail-row>
    </x-slot:body>
</x-pajak::detail>
```

> **Note:** For even row counts, the last two rows will have no bottom border. For odd counts, only the last row loses its border.

### Flush

Borderless/backgroundless — designed to embed inside an existing card or panel.

```blade
<div class="pajak-card">
    <div class="pajak-card__title">Filing status</div>
    <x-pajak::detail variant="flush">
        <x-pajak::detail-row key="Year">2025</x-pajak::detail-row>
        <x-pajak::detail-row key="Form">PIT-37</x-pajak::detail-row>
        <x-pajak::detail-row key="Submitted" :muted="true">Not yet submitted</x-pajak::detail-row>
    </x-pajak::detail>
</div>
```
