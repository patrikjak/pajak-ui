# Action tile

A linked tile with a coloured icon, a title and a short description - for quick-action grids on dashboards. Lifts on hover (shadow + 1px translate).

> All components support dark mode - see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
```

Or use the action-tile-only bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/action-tile-standalone.css') }}">
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/action-tile/action-tile';
```

---

## Usage

```blade
<x-pajak::action-tile
    href="{{ route('returns.create') }}"
    title="New return"
    description="Start a new tax filing"
    :color="\Pajak\Ui\Common\Enums\ActionTile\ActionTileColor::Green"
>
    <x-slot:icon>
        <x-heroicon-o-document-plus />
    </x-slot:icon>
</x-pajak::action-tile>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `string` | - | **Required.** Tile heading. |
| `href` | `string` | `'#'` | Link URL. |
| `description` | `string\|null` | `null` | Supporting text below the title. |
| `color` | `ActionTileColor` | `Blue` | Icon tile colour: `Blue`, `Green` or `Sand`. |

## Slots

| Slot | Description |
|------|-------------|
| `$icon` | Icon rendered inside the coloured tile (SVG is sized to 20px). Optional. |
