# Dialog

A small, centered confirmation dialog built on the native `<dialog>` element. Use for notices, confirmations, warnings, and success celebrations. For larger content with a close button, scrollable body, or form fields, use the [Modal](modal.md) component instead.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the dialog-only bundles:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/dialog-standalone.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/dialog.js') }}"></script>
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/dialog/dialog';
```

```ts
import { PajakDialog } from 'vendor/pajak/ui/js/dialog/dialog';
PajakDialog.initAll();
```

---

## Usage

```blade
{{-- 1. Render the dialog somewhere in your page --}}
<x-pajak::dialog id="confirm-discard" :type="$dangerType" title="Discard unsaved changes?">
    <x-slot:description>Leaving now will lose your changes.</x-slot:description>
    <x-slot:actions>
        <x-pajak::button variant="ghost" data-pajak-dialog-trigger="confirm-discard">Keep editing</x-pajak::button>
        <x-pajak::button variant="danger">Discard</x-pajak::button>
    </x-slot:actions>
</x-pajak::dialog>

{{-- 2. Trigger it from any element --}}
<button data-pajak-dialog-trigger="confirm-discard">Discard changes</button>
```

---

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | `string` | required | Unique ID used by JS and trigger attributes to open/close |
| `type` | `DialogType` | `DialogType::Info` | Colours the icon tile |
| `stacked` | `bool` | `false` | Stacks action buttons vertically (mobile-friendly) |
| `open` | `bool` | `false` | Renders `<dialog open>` server-side — for server-driven state |

## Slots

| Slot | Required | Description |
|------|----------|-------------|
| `icon` | No | Custom SVG icon inside the coloured circle; falls back to a type-appropriate default |
| `title` | No | Heading text (also passable as `title="..."` attribute) |
| `description` | No | Supporting paragraph text |
| `actions` | No | Button row rendered inside `.pajak-dialog__actions` |
| `$slot` | No | Free-form content rendered between description and actions |

## Types

```php
use Pajak\Ui\Common\Enums\Dialog\DialogType;

DialogType::Info    // blue  — notices, informational
DialogType::Success // green — confirmations, celebrations
DialogType::Warning // amber — cautions, missing fields
DialogType::Danger  // red   — destructive actions
```

---

## Opening and closing

### Trigger attribute (recommended)

Add `data-pajak-dialog-trigger="<id>"` to any element. Call `PajakDialog.initAll()` once on page load.

```html
<button data-pajak-dialog-trigger="my-dialog">Open</button>
```

### JS API

```js
PajakDialog.open('my-dialog')   // open programmatically
PajakDialog.close('my-dialog')  // close programmatically
PajakDialog.initAll()            // wire all trigger attributes + backdrop-click to close
```

Clicking the backdrop (outside the dialog box) also closes it automatically.

### Server-driven (PHP `open` prop)

Pass `:open="true"` to render `<dialog open>` — useful after a form submission or redirect where you want to show a dialog immediately.

```blade
<x-pajak::dialog id="submit-success" :open="$justSubmitted" :type="$successType" title="Return submitted">
    <x-slot:description>Your PIT-37 was filed successfully.</x-slot:description>
    <x-slot:actions>
        <x-pajak::button>Done</x-pajak::button>
    </x-slot:actions>
</x-pajak::dialog>
```

---

## Examples

### Notice — single action

```blade
<x-pajak::dialog id="session-ended" title="Your session has ended">
    <x-slot:description>For your security, you've been signed out.</x-slot:description>
    <x-slot:actions>
        <x-pajak::button>Sign in</x-pajak::button>
    </x-slot:actions>
</x-pajak::dialog>
```

### Destructive confirmation

```blade
<x-pajak::dialog id="confirm-delete" :type="DialogType::Danger" title="Discard unsaved changes?">
    <x-slot:description>You've edited this return but haven't saved. Leaving now will lose your changes.</x-slot:description>
    <x-slot:actions>
        <x-pajak::button variant="ghost">Keep editing</x-pajak::button>
        <x-pajak::button variant="danger">Discard</x-pajak::button>
    </x-slot:actions>
</x-pajak::dialog>
```

### Stacked actions (mobile-style)

```blade
<x-pajak::dialog id="missing-field" :type="DialogType::Warning" title="Missing required field" :stacked="true">
    <x-slot:description>You haven't entered your PESEL number.</x-slot:description>
    <x-slot:actions>
        <x-pajak::button>Add PESEL now</x-pajak::button>
        <x-pajak::button variant="ghost">Continue anyway</x-pajak::button>
    </x-slot:actions>
</x-pajak::dialog>
```
