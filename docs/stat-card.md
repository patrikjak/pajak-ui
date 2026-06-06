# Stat Card

Dashboard-style metric card displaying a label, value, icon, trend, and optional sub-text.

## Usage

```blade
<x-pajak::stat-card
    label="Tenants"
    value="8"
    :color="StatCardColor::Success"
    :trend-direction="StatCardTrend::Up"
>
    <x-slot:icon>
        <x-heroicon-o-home />
    </x-slot:icon>
    <x-slot:trend>+2 this month · 7 active, 1 trial</x-slot:trend>
    <x-slot:sub>Team members across tenants</x-slot:sub>
</x-pajak::stat-card>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string` | — | Small uppercase label (e.g. `"Tenants"`) |
| `value` | `string` | — | Large display value (e.g. `"8"` or `"PLN 1,240"`) |
| `:color` | `StatCardColor` | `Primary` | Icon bubble background colour variant |
| `:trend-direction` | `StatCardTrend\|null` | `null` | Colours the `$trend` slot text |

### `StatCardColor` values

| Case | Description |
|------|-------------|
| `Primary` | Blue (default) — `--color-primary-50` bg |
| `Success` | Green — `--color-success-light` bg |
| `Warning` | Amber — `--color-warning-light` bg |
| `Sand` | Warm sand/gold — `--color-accent-100` bg |
| `Error` | Red — `--color-error-light` bg |

### `StatCardTrend` values

| Case | Text colour |
|------|------------|
| `Up` | Success green |
| `Down` | Error red |
| `Warn` | Warning amber |

## Slots

| Slot | Description |
|------|-------------|
| `$icon` | Icon content placed in the colour-tinted bubble (top-right). Use `<x-heroicon-o-*>` — sized to 20 × 20 px automatically. |
| `$trend` | Trend text (e.g. `"+2 this month"`). Colour controlled by `:trend-direction`. |
| `$sub` | Secondary description below the value, in muted tertiary colour. |

## Asset inclusion

### Full bundle

```html
<link rel="stylesheet" href="/vendor/pajak/ui/main.css">
```

### Standalone

```html
<link rel="stylesheet" href="/vendor/pajak/ui/stat-card-standalone.css">
```

### SCSS source

```scss
@use 'vendor/pajak/ui/css/stat-card/stat-card';
```
