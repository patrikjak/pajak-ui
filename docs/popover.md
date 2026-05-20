# Popover

A positioned overlay panel anchored to a trigger element. Supports four placement variants, an optional arrow-decorated close button, and head/body/foot slot layout.

## Asset inclusion

```html
<!-- Full bundle (already includes popover) -->
<link rel="stylesheet" href="/vendor/pajak/ui/main.css">
<script src="/vendor/pajak/ui/main.js"></script>

<!-- Standalone -->
<link rel="stylesheet" href="/vendor/pajak/ui/popover-standalone.css">
<script src="/vendor/pajak/ui/popover.js"></script>
```

## Basic usage

```blade
{{-- Trigger (any element) --}}
<button data-pajak-popover-trigger="my-pop" aria-expanded="false" aria-haspopup="true">
    Open
</button>

{{-- Popover --}}
<x-pajak::popover id="my-pop" title="Polish tax bracket">
    Your income up to <strong>120 000 zł</strong> is taxed at <strong>17%</strong>.
    <x-slot:footer>
        <x-pajak::button variant="ghost">Got it</x-pajak::button>
        <x-pajak::button>Learn more</x-pajak::button>
    </x-slot:footer>
</x-pajak::popover>
```

```js
PajakPopover.initAll();
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | `string` | — | **Required.** Unique ID used to link triggers to this popover. |
| `placement` | `PopoverPlacement` | `Bottom` | Arrow placement: `Bottom`, `BottomEnd`, `Top`, `Right`, `Left`. |
| `dismissible` | `bool` | `true` | Show an X close button in the header. |
| `title` | `string\|null` | `null` | Optional heading text. If absent and `dismissible` is `false`, the head section is omitted. |

## Slots

| Slot | Description |
|------|-------------|
| `$slot` (default) | Body content — rendered in `.pajak-pop__body`. |
| `footer` | Optional footer — rendered in `.pajak-pop__foot`, right-aligned. |

## Placement values

```blade
<x-pajak::popover id="pop-b"  placement="bottom" /> {{-- arrow top-left --}}
<x-pajak::popover id="pop-be" placement="bottom-end" /> {{-- arrow top-right --}}
<x-pajak::popover id="pop-t"  placement="top" /> {{-- arrow bottom-center --}}
<x-pajak::popover id="pop-r"  placement="right" /> {{-- arrow left-side --}}
<x-pajak::popover id="pop-l"  placement="left" /> {{-- arrow right-side --}}

{{-- Or pass the enum directly --}}
<x-pajak::popover id="pop-r" :placement="PopoverPlacement::Right" />
```

## Trigger wiring

Any element with `data-pajak-popover-trigger="<id>"` toggles the matching popover on click. Multiple triggers can point to the same popover.

```html
<button data-pajak-popover-trigger="my-pop">Toggle</button>
```

Close buttons inside the popover use `data-pajak-popover-close`:

```html
<button data-pajak-popover-close>Dismiss</button>
```

Popovers also close on Escape or a click outside.

## JS API

```js
PajakPopover.initAll()        // Wire all triggers and close buttons in the document
PajakPopover.open('my-pop')   // Open by ID
PajakPopover.close('my-pop')  // Close by ID
PajakPopover.toggle('my-pop') // Toggle by ID
```
