# Modal

A larger overlay dialog with a structured head (icon + title + close button), scrollable body, and footer action bar. Built on the native `<dialog>` element. For small, icon-driven confirmations, use the [Dialog](dialog.md) component instead.

## Usage

```blade
{{-- 1. Render the modal somewhere in your page --}}
<x-pajak::modal id="submit-return" title="Submit your tax return?">
    <x-slot:description>Once submitted, your PIT-37 will be sent to the tax office.</x-slot:description>
    <x-slot:footer>
        <x-pajak::button variant="ghost" data-pajak-modal-close>Cancel</x-pajak::button>
        <x-pajak::button>Submit return</x-pajak::button>
    </x-slot:footer>
</x-pajak::modal>

{{-- 2. Trigger it from any element --}}
<button data-pajak-modal-trigger="submit-return">Submit</button>

{{-- 3. Initialise once on page load --}}
<script>
    PajakModal.initAll();
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | `string` | required | Unique ID used by JS and trigger attributes |
| `size` | `ModalSize` | `ModalSize::Md` | Controls `max-width` — Sm (360px), Md (440px), Lg (520px) |
| `sheet` | `bool` | `false` | Bottom-sheet variant — rounded top corners, anchored to bottom |
| `open` | `bool` | `false` | Renders `<dialog open>` server-side — for server-driven state |
| `dismissible` | `bool` | `true` | Shows close button; allows backdrop-click to close |

## Slots

| Slot | Required | Description |
|------|----------|-------------|
| `icon` | No | SVG icon inside the 40×40px rounded-square tile in the header |
| `title` | No | Modal heading (also passable as `title="..."` attribute) |
| `description` | No | Subtitle text below the heading |
| `$slot` | No | Body content — rendered inside `.pajak-modal__body` |
| `footer` | No | Action buttons — rendered in a subtle footer bar |

## Sizes

```php
use Pajak\Ui\Common\Enums\Modal\ModalSize;

ModalSize::Sm  // max-width: 360px
ModalSize::Md  // max-width: 440px (default)
ModalSize::Lg  // max-width: 520px — for forms or long content
```

## Opening and closing

### Trigger attribute (recommended)

```html
<button data-pajak-modal-trigger="my-modal">Open</button>
```

Call `PajakModal.initAll()` once on page load to wire all triggers, backdrop-clicks, and `data-pajak-modal-close` buttons.

### Close button inside the modal

Any element with `data-pajak-modal-close` inside the modal will close it when clicked. The built-in `×` button in the header uses this automatically when `dismissible="true"`.

```blade
<x-pajak::button variant="ghost" data-pajak-modal-close>Cancel</x-pajak::button>
```

### JS API

```js
PajakModal.open('my-modal')   // open programmatically
PajakModal.close('my-modal')  // close programmatically
PajakModal.initAll()           // wire all triggers, close buttons, and backdrop-click
```

### Server-driven (`open` prop)

Pass `:open="true"` to render `<dialog open>` immediately — useful after a redirect or form submission. Note: this renders as a plain block element without a backdrop or focus trap. For backdrop + focus trap from page load, omit `open` and call `PajakModal.open('id')` on `DOMContentLoaded` instead.

## Non-dismissible modal

Set `:dismissible="false"` to hide the close button and prevent backdrop-click from closing. Use for blocking operations like form submission in progress.

```blade
<x-pajak::modal id="submitting" :dismissible="false" title="Submitting your return…">
    <x-slot:description>Please don't close this window.</x-slot:description>
</x-pajak::modal>
```

## Bottom sheet

The sheet variant anchors the modal to the bottom of the viewport with rounded top corners — suitable for mobile confirmations.

```blade
<x-pajak::modal id="sign-modal" :sheet="true" title="Sign with mObywatel?">
    <x-slot:description>You'll be redirected to the mObywatel app to confirm and sign your return.</x-slot:description>
    <x-slot:footer>
        <x-pajak::button variant="ghost" data-pajak-modal-close>Not now</x-pajak::button>
        <x-pajak::button>Continue</x-pajak::button>
    </x-slot:footer>
</x-pajak::modal>
```

## Asset inclusion

```html
{{-- Full bundle --}}
<link rel="stylesheet" href="/vendor/pajak/ui/main.css">
<script src="/vendor/pajak/ui/main.js"></script>

{{-- Modal only --}}
<link rel="stylesheet" href="/vendor/pajak/ui/modal-standalone.css">
<script src="/vendor/pajak/ui/modal.js"></script>
```
