# Calendar / Date Picker

A floating date picker and date range picker with optional presets sidebar and optional time inputs (HH:MM).

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the calendar-only bundles:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/calendar-standalone.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/calendar.js') }}"></script>
```

### Source import (recommended for production)

Publish sources and import only what you need:

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/calendar/calendar';
```

```ts
import { PajakCalendar } from 'vendor/pajak/ui/js/calendar/calendar';
PajakCalendar.initAll();
```

---

## Single date picker

```blade
<x-pajak-calendar::date-picker
    name="due_date"
    label="Due date"
    placeholder="Pick a date"
/>
```

With a pre-selected date:

```blade
<x-pajak-calendar::date-picker
    name="due_date"
    value="2026-04-30"
/>
```

With min/max constraints:

```blade
<x-pajak-calendar::date-picker
    name="due_date"
    min="2026-01-01"
    max="2026-12-31"
/>
```

### With time

Adds a HH:MM input in the footer. The hidden form input value becomes `Y-m-d H:i`.

```blade
<x-pajak-calendar::date-picker
    name="appointment"
    :time="true"
    value="2026-04-30 09:30"
/>
```

---

## Date range picker

```blade
<x-pajak-calendar::date-picker
    name="period"
    :range="true"
    label="Date range"
/>
```

With pre-selected range:

```blade
<x-pajak-calendar::date-picker
    name="period"
    :range="true"
    start="2026-01-01"
    end="2026-12-31"
/>
```

This renders two hidden inputs: `period_start` and `period_end`. The "Apply" button commits the selection.

### Range with time

Both ends get independent HH:MM inputs. Hidden input values become `Y-m-d H:i`.

```blade
<x-pajak-calendar::date-picker
    name="period"
    :range="true"
    :time="true"
    start="2026-01-01 00:00"
    end="2026-12-31 23:59"
/>
```

---

## Range picker with presets

Pass preset buttons via the `presets` slot. JS automatically wires any `button[data-start][data-end]` inside the presets container.

```blade
<x-pajak-calendar::date-picker name="period" :range="true">
    <x-slot:presets>
        <button data-start="2026-05-12" data-end="2026-05-18">This week</button>
        <button data-start="2026-04-17" data-end="2026-05-17">Last 30 days</button>
        <button data-start="2026-05-01" data-end="2026-05-31">This month</button>
        <button data-start="2026-01-01" data-end="2026-12-31">This year</button>
        <button data-start="2025-01-01" data-end="2025-12-31">Last year</button>
    </x-slot:presets>
</x-pajak-calendar::date-picker>
```

With time defaults per preset (optional):

```blade
<button data-start="2026-01-01" data-end="2026-12-31"
        data-start-time="00:00" data-end-time="23:59">This year</button>
```

If `data-start-time`/`data-end-time` are omitted and time mode is on, the preset defaults to `00:00` / `23:59`.

---

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | Input name. Range mode appends `_start` / `_end`. |
| `range` | `bool` | `false` | Enable range selection mode. |
| `time` | `bool` | `false` | Show HH:MM time inputs in the footer. |
| `value` | `string\|null` | `null` | Initial value for single mode (`Y-m-d` or `Y-m-d H:i`). |
| `start` | `string\|null` | `null` | Initial range start (`Y-m-d` or `Y-m-d H:i`). |
| `end` | `string\|null` | `null` | Initial range end (`Y-m-d` or `Y-m-d H:i`). |
| `placeholder` | `string\|null` | `null` | Override the translated placeholder text. |
| `disabled` | `bool` | `false` | Disable the trigger. |
| `min` | `string\|null` | `null` | Earliest selectable date (`Y-m-d`). Days before are struck through. |
| `max` | `string\|null` | `null` | Latest selectable date (`Y-m-d`). Days after are struck through. |
| `id` | `string\|null` | `null` | Override the trigger/label `id` (defaults to `name`). |
| `label` | `string\|null` | `null` | Renders a `<label>` above the trigger. |
| `error` | `string\|null` | `null` | Renders an error message below via `<x-pajak-form::field-message>`. |

---

## Keyboard navigation

| Key | Action |
|-----|--------|
| `Enter` / `Space` | Open the calendar panel from the trigger |
| `Escape` | Close the panel and return focus to the trigger |
| `←` `→` `↑` `↓` | Move focus between day buttons |
| `PageUp` / `PageDown` | Navigate to previous / next month |
| `Home` / `End` | Jump to the first / last selectable day of the current month |
| `Enter` | Select the focused day and close the panel |
| `Tab` | Move focus through the panel controls (time inputs, action button) |

The day grid uses a roving tabindex — only the currently focused day is in the tab order. When the panel opens, focus moves automatically to the selected day (or today, or the first available day). When the panel closes, focus returns to the trigger button.

Month navigation is announced to screen readers via a live region.

---

## JS API

`PajakCalendar` is available on `window.Pajak.PajakCalendar` when using the full or calendar bundle.

```ts
// Init a single element
const instance = PajakCalendar.init(el);

// Init all date pickers in a container
PajakCalendar.initAll(document);

// Destroy (removes all listeners, detaches panel)
instance.destroy();
```

---

## Form submission

| Mode | Submitted fields |
|------|-----------------|
| Single, no time | `name` → `Y-m-d` |
| Single, with time | `name` → `Y-m-d H:i` |
| Range, no time | `name_start` → `Y-m-d`, `name_end` → `Y-m-d` |
| Range, with time | `name_start` → `Y-m-d H:i`, `name_end` → `Y-m-d H:i` |

In range mode the hidden inputs are only updated when the user clicks **Apply**.
