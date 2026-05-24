# Stepper

Multi-step progress indicator with four variants: horizontal (numbered with labels), pill (dense scrollable), vertical (checklist with inline content), and bar (compact progress bar). Purely presentational — state is driven by PHP props.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the stepper-only bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/stepper-standalone.css') }}">
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/stepper/stepper';
```

---

## Variants

### Horizontal (default)

Numbered steps connected by horizontal lines. Supports title and description per step.

```blade
@use('Pajak\Ui\Common\Enums\Stepper\StepperStepState')

<x-pajak::stepper>
    <x-pajak::stepper-step :step="1" title="Identity" description="Verified" :state="StepperStepState::Done" />
    <x-pajak::stepper-step :step="2" title="Income" :state="StepperStepState::Done" />
    <x-pajak::stepper-step :step="3" title="Deductions" description="Add reliefs" :state="StepperStepState::Active" />
    <x-pajak::stepper-step :step="4" title="Review" :last="true" />
</x-pajak::stepper>
```

### Pill

Dense pill-shaped steps that scroll horizontally on narrow screens. Pass `:variant` to both wrapper and each step. Connectors are rendered automatically between non-last steps.

```blade
@use('Pajak\Ui\Common\Enums\Stepper\StepperStepState')

<x-pajak::stepper :variant="\Pajak\Ui\Common\Enums\Stepper\StepperVariant::Pill">
    <x-pajak::stepper-step :step="1" title="Identity" :state="StepperStepState::Done" :variant="\Pajak\Ui\Common\Enums\Stepper\StepperVariant::Pill" />
    <x-pajak::stepper-step :step="2" title="Income" :state="StepperStepState::Active" :variant="\Pajak\Ui\Common\Enums\Stepper\StepperVariant::Pill" />
    <x-pajak::stepper-step :step="3" title="Review" :variant="\Pajak\Ui\Common\Enums\Stepper\StepperVariant::Pill" :last="true" />
</x-pajak::stepper>
```

### Vertical

Steps stacked vertically with a connecting line. Supports inline content via `$slot` — useful for expanded content on the active step.

```blade
<x-pajak::stepper :variant="\Pajak\Ui\Common\Enums\Stepper\StepperVariant::Vertical">
    <x-pajak::stepper-step
        :step="1"
        title="Verify identity"
        description="Confirmed via Profil Zaufany."
        :state="\Pajak\Ui\Common\Enums\Stepper\StepperStepState::Done"
        :variant="\Pajak\Ui\Common\Enums\Stepper\StepperVariant::Vertical"
    />
    <x-pajak::stepper-step
        :step="2"
        title="Add deductions"
        :state="\Pajak\Ui\Common\Enums\Stepper\StepperStepState::Active"
        :variant="\Pajak\Ui\Common\Enums\Stepper\StepperVariant::Vertical"
    >
        Your largest unclaimed relief: <strong>Family allowance</strong>.
    </x-pajak::stepper-step>
    <x-pajak::stepper-step
        :step="3"
        title="Review"
        :variant="\Pajak\Ui\Common\Enums\Stepper\StepperVariant::Vertical"
        :last="true"
    />
</x-pajak::stepper>
```

### Bar

Compact progress bar with a label and step counter. No child steps — all data is on the wrapper.

```blade
<x-pajak::stepper
    :variant="\Pajak\Ui\Common\Enums\Stepper\StepperVariant::Bar"
    :current="3"
    :total="5"
    label="Deductions"
/>
```

---

## `<x-pajak::stepper>` props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `StepperVariant` | `StepperVariant::Horizontal` | Layout variant |
| `current` | `int` | `1` | Current step number (bar variant only) |
| `total` | `int` | `1` | Total step count (bar variant only) |
| `label` | `?string` | `null` | Current step label (bar variant only) |

## `<x-pajak::stepper-step>` props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `step` | `int` | — | Step number shown in the indicator |
| `title` | `string` | — | Step label |
| `state` | `StepperStepState` | `StepperStepState::Upcoming` | `Upcoming`, `Active`, or `Done` |
| `variant` | `StepperVariant` | `StepperVariant::Horizontal` | Must match the parent stepper variant |
| `description` | `?string` | `null` | Optional subtitle below the title |
| `last` | `bool` | `false` | Suppresses the trailing connector (pill variant) and the line (vertical variant) |
| `$slot` | slot | — | Optional inline content rendered below title/description (horizontal and vertical variants) |

> **Note:** Pass the same `variant` to both `<x-pajak::stepper>` and each `<x-pajak::stepper-step>` — the step component uses it to decide its HTML structure (`<li>` vs `<span>`).
